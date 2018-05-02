const functions = require('firebase-functions');

// call the packages we need
var express    = require('express');
var bodyParser = require('body-parser');
var app        = express();
var firebase = require('firebase-admin');
var jwt_decode = require('jwt-decode');

//var firebasex = require('firebase-nodejs');

// configure app
//app.use(morgan('dev')); // log requests to the console

// configure body parser
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

var port     = process.env.PORT || 8080; // set our port

//var serviceAccount = require('ecotrail-cb90b3e73080.json');

firebase.initializeApp({
    credential: firebase.credential.cert("ecotrail-cb90b3e73080.json"),
    databaseURL: 'https://ecotrail-56538.firebaseio.com'
});

// ROUTES FOR OUR API
// =============================================================================

// create our router
var router = express.Router();

// middleware to use for all requests
router.use(function(req, res, next) {
	// do logging
	console.log('Something is happening.');
	next();
});

// test route to make sure everything is working (accessed at GET http://localhost:8080/api)
router.get('/', function(req, res) {
	console.log(req);
	res.json({ message: 'hooray! welcome to our api!' });	
});

// on routes that end in /bears
// ----------------------------------------------------
router.route('/user').post(function(req, res) {

    var decoded = jwt_decode(req.header("authToken"));
    console.log(decoded);
        var data = {
            "email_A": req.param("name"),
            "email_B": decoded.sub,
            "Hash_A": req.param("hash"),
            "Hash_B": decoded.iss,
            "time": "",
            "jti": decoded.jti
        }
        firebase.database().ref().push(data);

        res.json({message: "Good Job !!" + "  " + req.param("email") + " and  " + decoded.sub + "   See you at Google I/O Extended Sri Lanka 2018"});

            });



	

// REGISTER OUR ROUTES -------------------------------
app.use('/api', router);

// START THE SERVER
// =============================================================================
app.listen(port);
console.log('Magic happens on port ' + port);
