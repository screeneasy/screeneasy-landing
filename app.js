/**
 * Module dependencies.
 */
var express = require('express');
var routes = require('./routes');
var http = require('http');
var path = require('path');
var fs = require('fs');
var aws = require('aws-sdk');
var app = express();
aws.config.loadFromPath('./.tmp/config.json');
var s3 = new aws.S3();

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

app.post('/subscribe', function(req,res) {
   var email = req.body.email;
   var response = {};
   if (email === "") {
      response = {"status": "error", "error": "empty_email"};
      res.send(JSON.stringify(response));
      res.end();
   }
   else if(!validEmail(email)) {
      response = {"status": "error", "error": "invalid_email"};
      res.send(JSON.stringify(response));
      res.end();
   }
   else if(isSubscribed(email)) {
      response = {"status": "error", "error": "already_subscribed"};
      res.send(JSON.stringify(response));
      res.end();
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
});

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
});

function saveSurvey(email, type, ans2, ans3, ans4, callback) {
   var content = 'Email: ' + email + '\t1.' + type +
                                     '\t2.' + ans2 +
                                     '\t3.' + ans3 +
                                     '\t4.' + ans4 +
                                     '\n';
   console.log(callback);
   writeToS3('surveylist', content,callback);
}

function writeToS3(key, data,callback) {
    s3.createBucket({Bucket: 'screeneasy2013'}, function() {
      var params = {Bucket: 'screeneasy2013', Key: key, Body: data};
      s3.putObject(params, function(err, data) {
        if (err) {
          console.log(err);
          callback(err);
        }
        else {
          console.log("Successfully uploaded data to screeneasy/subscribelist");
          callback();
        }
      });
    });
}

function validEmail(email) {
   return (/.+@.+\..+/.test(email));
}

function subscribeEmail(email,refer, callback) {
    writeToS3('subscribelist', email + '-' + refer,callback);
}

function isSubscribed(email) {
   s3.getObject({Bucket: 'screeneasy', Key: 'subscribelist'}, function(err, data) {
       if( err ) {
          return false;
       }

       var email_list = data.Body.toString();
       var emails = email_list.split("\n");
       var isUserSubscribed = false;
       emails.forEach(function(v,k) {
          if (v.indexOf(email) > -1) {
             isUserSubscribed = true;
          }
       });

       return isUserSubscribed;
   });

   // Fail to read from s3, let's assume email is not subscribed
   return false;
}

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port %d', app.get('port'));
});
