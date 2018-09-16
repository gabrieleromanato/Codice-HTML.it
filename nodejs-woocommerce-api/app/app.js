'use strict';

const express = require('express');
const path = require('path');
const fs = require('fs');
const https = require('https');
const favicon = require('serve-favicon');
const bodyParser = require('body-parser');
const port = process.env.PORT || 4000;
const app = express();
const cookieParser = require('cookie-parser');
const helmet = require('helmet');
const routes = require('./routes');
const API = require('./lib/API');
const db =  require('./lib/db');
const productUtils = require('./lib/products-utils');
const crypto = require('crypto');


const sslOptions = {
  key: fs.readFileSync('privkey.pem'),
  cert: fs.readFileSync('fullchain.pem')
};




app.disable('x-powered-by');

app.set('view engine', 'ejs');
app.set('env', 'production');
app.use(favicon(path.join(__dirname, 'favicon.ico')));


app.use(helmet());
app.use(cookieParser());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

app.use( (req, res, next ) => {
  res.locals.authenticated = (req.cookies.authenticated && parseInt(req.cookies.authenticated, 10) === 1);
  req.API = API;
  req.crypto = crypto;
  req.db = new db();
  req.utils = productUtils;
  next();
});



app.get('/', routes.index);
app.post('/login', routes.login);
app.get('/logout', routes.logout);
app.get('/dashboard', routes.dashboard);
app.get('/dashboard/products', routes.products);
app.get('/dashboard/products/new', routes.product);
app.post('/dashboard/products/new', routes.createProduct);
app.get('/dashboard/import', routes.importPage);
app.post('/dashboard/import/products', routes.importProducts);
app.get('/dashboard/view/:id', routes.singleProduct);

https.createServer(sslOptions, app).listen(port);

