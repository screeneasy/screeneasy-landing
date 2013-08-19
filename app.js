
/**
 * Module dependencies.
 */

var express = require('express')
  , routes = require('./routes')
  , user = require('./routes/user')
  , http = require('http')
  , path = require('path')
  , fs = require('fs');

var app = express();

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
app.get('/users', user.list);
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
      subscribeEmail(email, function(err){
         if (!err) {
            res.send(JSON.stringify({"status": "success"}));
         }
         else {
            res.send(JSON.stringify({"error": "list_unwritable", "status": "error"}));
         }
      });
   }
})

function validEmail(email) {
   return /[A-Za-z0-9]+@[A-Za-z0-9]+\.[a-zA-Z0-9]+/.test(email);
}

function subscribeEmail(email, callback) {
   fs.appendFile('public/subscribelist', email + '\n', callback)
}

function isSubscribed(email) {
   var emails = fs.readFileSync('public/subscribelist');
   var emails = emails.toString().split("\n");
   return emails.indexOf(email) > -1;
}

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});
