<?php
namespace lk\gdgsrilanka\io18;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: X-Question-Number");

require 'vendor/autoload.php';
include_once('User.php');
include_once('questions/qr/Question_QRHash.php');
include_once('questions/jigsaw/Question_Jigsaw.php');
// include_once('questions/csr/Question_CSR.php');
include_once('questions/android/Question_Android.php');
include_once('questions/golang/Question_Figlet.php');
include_once('questions/api-question/Question_API.php');
include_once('questions/photostripe/Question_PhotoStripe.php');
include_once('questions/multilayers/Question_Multilayers.php');
include_once('questions/jwt/Question_JWT.php');

//get the email and the key (if present)
$email = $_REQUEST['u'];
$key = FALSE;

if(!preg_match('/.+@.+\..+/', $email)) {
	die();
}

if(isset($_REQUEST['k']))
{
	$key = preg_replace('/^(\/)?(.*)$/', "$2", $_REQUEST['k']);
	if(strlen($key) == 0) {
		$key = FALSE;
	}
}


//create the user and the required steps
$user = new User($email);
// var_dump($user);
$keysForSteps = $user->getKeysForSteps();
$questionOrder = $user->getQuestionOrder();

//todo REMOVE WHEN LIVE!!

if(isset($_REQUEST['debug'])) {
	echo $email . "->". $user->getQuestionOrder() ."<br/>\n". 
		$user->getKeysForSteps()[1] ."<br/>\n".
		$user->getKeysForSteps()[2] ."<br/>\n".
		$user->getKeysForSteps()[3] ."<br/>\n".
		$user->getKeysForSteps()[4] ."<br/>\n".
		$user->getFinalKey() . "<br/>\n";
}

//if there is a key, we need to find out which step he is in (or if the key is valid at all)
if($key != FALSE)
{
	$keyPosition = $user->getKeyPosition($key);
	if($keyPosition != FALSE)
	{
		
		if($keyPosition == 4) {
			header("X-Question-Number: ". ($keyPosition + 1));
			//he's solved everythng. point to the last clue
			if($user->getUserDockerType() == 1) {
				echo '<h2>One final step to complete the trail. Find the tree container at pamudithai/treeservice</h2>';
			} else if($user->getUserDockerType() == 2) {
				echo '<h2>One final step to complete the trail. Find the water container at pamudithai/waterservice</h2>';
			}

			echo '<h3>For an ecosystem to thrive you need the trees and water. Not everyone has got both.</h3>';

			echo '<p>Some tips on docker:<br/></p><ul>
				<li>-v can mount local files on the container</li>
				<li>--net=host exposes local ports to containers</li>
				<li>you will need to port map to the correct port. find it out. -p is the switch</li>
				</ul><br/><br/><br/>';
			
			
		} 
		elseif($keyPosition == 5) {
			//user has solved the docker as well..
			header("X-Question-Number: 6");
			header("Content-Type: text/html");
			echo '<h2>Congratulations! You have won the EcoTrail</h2><br/><h2>Please register yourself at <a href="https://io.rsvp.lk/gdgsrilanka/google-io-2018/ecotrail">RSVP.lk EcoTrail Registrations</a></h2>

			<br/><br/></br/>

			<h4>In memory of Dr. Amith Munindradasa, who was one with nature</h4>
			<br/><br/>
			<h3>Credits</h3><hr/>
			<h4>Wildlife photography by:</h4>
			<h5>Raveen Harith Perera</h5>
			<br/>
			<h4>Beta testers</h4>
			<h5>Lahiru Madushankha</h5>
			<h5>Dumindu Ranasingharachchi</h5>
			<br/>
			<h4>Microservices by</h4>
			<h5>Pamuditha Imalka</h5>
			<br/>
			<h4>Artwork by</h4>
			<h5>Pasan Ranathunga</h5>
			
			<br/><br/><br/>';	
		}
		else {
			//the given key is correct. Render the next step
			// echo "Step ". $keyPosition . " solved. Now solve for ". $keysForSteps[$keyPosition + 1];
			$question = getQuestionAt(hexdec(substr($questionOrder, $keyPosition, 1)));
			$answer = $question->getClueContent($email, $keysForSteps[$keyPosition + 1]);
			header("X-Question-Number: ". ($keyPosition + 1));
			header("Content-Type: ". $answer->answerHeader);

			echo $answer->answerContent;
		}
	}
	else
	{
		
		//user has entered an invalid key. give the error page
		header("Content-Type: text/html");
		echo "<h3>You are wandering off...<br/> There are animals off the trail. Be careful. Keep to the trail.!</h3>";	
		
	}
}
//if there is no key, we need to present the first question.
else 
{
	//presenting the first question
	$question = getQuestionAt(hexdec(substr($questionOrder, 0, 1)));
	$answer = $question->getClueContent($email, $keysForSteps[1]);
	header("Content-Type: ". $answer->answerHeader);
	header("X-Question-Number: 1");
	echo $answer->answerContent;
}




/**
  Gets the question object according to the question number required.
*/
function getQuestionAt($questionNumber)
{
	switch ($questionNumber) {
		case 0:
		case 1:
			return new Question_Android();
		case 2:
		case 3:
			return new Question_QRHash();
		case 4:
		case 5:
			return new Question_Figlet();
		case 6:
		case 7:
			return new Question_PhotoStripe();
		case 8:
		case 9:
			return new Question_Multilayers();
		case 10: //a
		case 11: //b
			return new Question_API();
		case 12: //c
		case 13: //d
			return new Question_Jigsaw();
		case 14: //e
		case 15: //f
			return new Question_JWT();
		
	}
}

