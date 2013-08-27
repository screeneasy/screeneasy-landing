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
var util = require('util');

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

var bucket = "screeneasy-landing-data";

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
   else {
      isSubscribed(email, function(err) {
         if (err) {
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
      })
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
   var key = util.format("surveys/%s-%s", email, Date.now())
   writeToS3(key, content,callback);
}

function writeToS3(key, data,callback) {
    s3.createBucket({Bucket: bucket}, function() {
      var params = {Bucket: bucket, Key: key, Body: data};
      s3.putObject(params, callback);
}

function validEmail(email) {
   return (/.+@.+\..+/.test(email));
}

function subscribeEmail(email,refer, callback) {
    var key = util.format("emails/%s", email);
    writeToS3(key, util.format("%s-%s-%s", Date.now(), email, refer), callback);
}

function isSubscribed(email,callback) {
   s3.headObject({Bucket: bucket, Key: util.format('emails/%s', email)}, function(err,data) {
      callback(err === null);   
   });
}

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port %d', app.get('port'));
});
