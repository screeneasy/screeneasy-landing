ScreenEasy Landing Page
====================

ScreenEasy.me landing page

# Dev locally:
- mv credentials.json.dist credentials.json
- npm install
- ./node_modules/nodemon/nodemon.js app.js -e js,css,html

# Deploy on Heroku:
- git remote add heroku git@heroku.com:screeneasy.git
- git push heroku master
