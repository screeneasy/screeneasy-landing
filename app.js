
/**
 * Module dependencies.
 */

var express = require('express')
  , routes = require('./routes')
  , http = require('http')
  , path = require('path')
  , fs = require('fs');

var app = express();
var credentials = JSON.parse(fs.readFileSync('credentials.json'));

// all environments
app.set('port', process.env.PORT || 3000);
app.set('views', __dirname + '/views');
app.set('view engine', 'ejs');
app.use(express.favicon());
app.use(express.logger('dev'));
app.use(express.bodyParser());
app.use(express.methodOverride());
app.use(app.router);
app.use(express.static(path.join(__dirname, 'public')));

// development only
if ('development' == app.get('env')) {
  app.use(express.errorHandler());
}

app.get('/', routes.index);
app.get('/readlist',express.basicAuth(credentials.user, credentials.password),function(req,res) {
   fs.readFile('public/subscribelist',function(err,data) {
      res.setHeader('Content-Type', 'text/plain');
      res.send(data);
      res.end()
   })
})

app.post('/subscribe', function(req,res) {
   var email = req.body.email;
   var response = {};
   if (email === "") {
      response = {"status": "error", "error": "empty_email"};
      res.send(JSON.stringify(response))
      res.end()
   }
   else if(!validEmail(email)) {
      response = {"status": "error", "error": "invalid_email"};
      res.send(JSON.stringify(response))
      res.end()
   }
   else if(isSubscribed(email)) {
      response = {"status": "error", "error": "already_subscribed"};
      res.send(JSON.stringify(response))
      res.end()
   }
   else {
      // write the file
      subscribeEmail(email, req.body.refer, function(err){
         if (!err) {
            res.send(JSON.stringify({"status": "success"}));
         }
         else {
            res.send(JSON.stringify({"error": "list_unwritable", "status": "error"}));
         }
      });
   }
})

app.post('/survey', function(req, res) {
   var email = req.body.email;
   var type = req.body.type;
   var answer2 = req.body.answer2;
   var answer3 = req.body.answer3;
   var answer4 = req.body.answer4;

   // Save the survey answers
   saveSurvey(email, type, answer2, answer3, answer4, function(err){
      if (!err) {
         res.send(JSON.stringify({"status": "success"}));
      } else {
         res.send(JSON.stringify({"error": "survey_unwritable", "status": "error"}));
      }
   });
})

function saveSurvey(email, type, ans2, ans3, ans4, callback) {
   fs.appendFile('public/surveylist', 'Email: ' + email +
                                      '\t1.' + type +
                                      '\t2.' + ans2 +
                                      '\t3.' + ans3 +
                                      '\t4.' + ans4 +
                                      '\n', callback)
}

function validEmail(email) {
   return /.+@.+\..+/.test(email);
}

function subscribeEmail(email,refer, callback) {
   fs.appendFile('public/subscribelist', email + '\t' + refer + '\n', callback)
}

function isSubscribed(email) {
   var emails = fs.readFileSync('public/subscribelist');
   var emails = emails.toString().split("\n");
   var isSubscribed = false;
   emails.forEach(function(v,k) {
      if (v.indexOf(email) > -1) {
         isSubscribed = true;
      }
   })
   return isSubscribed;
}

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});
