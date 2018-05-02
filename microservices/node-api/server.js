var jquery = require('jquery');
var express    = require('express');
var bodyParser = require('body-parser');
var app        = express();
var firebase = require('firebase-admin');
var jwt_decode = require('jwt-decode');
var moment = require('moment');
var axios = require('axios');

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

var port     = process.env.PORT || 8080; // set our port


firebase.initializeApp({
    credential: firebase.credential.cert("ecotrail-196902.json"),
    databaseURL: 'https://ecotrail-196902.firebaseio.com'
});

var router = express.Router();

router.use(function(req, res, next) {

	console.log('Something is happening.');
	next();
});

router.route('/user').post(function(req, res) {
    
    var d = new Date();
    var count = 1;
    var ref = firebase.database().ref("/users");
    var reqest = req.body;
    var state = null;
    var key = null;

    var  datas = axios({
        method: 'post',
        url: 'https://io-18-quiz.appspot.com/verify/',
        data:  req.body
    }).then(function(response){
        state=response.data.state;
        key = response.data.message;
        if(state) {

            ref.child(key).once('value', gotUserData);
            var count = null;
            function gotUserData(snapshot){

                snapshot.forEach(userSnapshot => {
                    var key = userSnapshot.key;
                    //var ida = userSnapshot.val().Hash_A;
                    // var idb = userSnapshot.val().Hash_B;
                    count++;
                })
                if(count <= 2){
                    firebase.database().ref("/users").child(key).push({"key": key,"waterEmail":req.body.emailWater,"treeEmail":req.body.emailTree});
                    res.json({message: "Good Job !!" + " " + "See you at Google I/O Extended Sri Lanka 2018 ",key:key});
               }else{
                    res.json("All trees can't support all the water sources in the world and all water sources cannot feed all the trees. You need to find more trees or water");
               }
            }
        }else{
            res.json({message:key,state:state});
        }
    });

            });

// REGISTER OUR ROUTES
app.use('/api', router);

// START THE SERVER

app.listen(port);
console.log('Magic happens on port ' + port);
