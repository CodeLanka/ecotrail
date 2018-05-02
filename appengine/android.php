<?php
namespace lk\gdgsrilanka\io18;
header("Access-Control-Allow-Origin: *");

require 'vendor/autoload.php';
include_once('User.php');

$VALID_QUESTIONS = "012";
//get the email and the key (if present)
$email = $_REQUEST['u'];
$key = FALSE;
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


//if there is a key, we need to find out which step he is in (or if the key is valid at all)
if($key != FALSE)
{
	$oldKeyPosition = $user->getKeyPosition($key);
	if($oldKeyPosition != FALSE)
	{
		if($oldKeyPosition == 4) {
			//he's solved everythng. point to the last clue
			echo 'You solved everything. no need to solve again';
		} else {
			//the given key is correct. Render the next step (we have to compare with not the position of the given key, but)
			// with the position after that. because we're at the next question now.
			$currentQuestionType = substr($questionOrder, $oldKeyPosition, 1);
			if(stripos($VALID_QUESTIONS, strtoupper($currentQuestionType)) !== FALSE) {
				// echo 'giving '. $keysForSteps[$oldKeyPosition + 1] . '<br/>';
				//echo getMorse($keysForSteps[$oldKeyPosition + 1]);	

				$output = array();
				$output['status'] = "success";
				$output['message'] = "Feel the vibe.. Keep the screen ON";
				$output['data'] = morseToJSON(getMorse($keysForSteps[$oldKeyPosition + 1]));
				 header("Content-Type: application/json");
				echo json_encode($output);
			} else {
				//this is not the question type for this user's current position
				echo 'wrong question type';
				$output = array();
				$output['status'] = "success";
				$output['message'] = "Feel the vibe.. Keep the screen ON";
				$output['data'] = morseToJSON(getMorse("dead"));
				header("Content-Type: application/json");
				echo json_encode($output);
			}
			
		}
	}
	else
	{
		//user has entered an invalid key. give the error page
		$output = array();
		$output['status'] = "success";
		$output['message'] = "Feel the vibe.. Keep the screen ON";
		$output['data'] = morseToJSON(getMorse("dead"));
		header("Content-Type: application/json");
		echo json_encode($output);
	}
}
//if there is no key, we need to give an error
else 
{
	//android question came first. support that.
	$currentQuestionType = substr($questionOrder, 0, 1);
	if(stripos($VALID_QUESTIONS, strtoupper($currentQuestionType)) !== FALSE) {
		$output = array();
		$output['status'] = "success";
		$output['message'] = "Feel the vibe.. Keep the screen ON";
		$output['data'] = morseToJSON(getMorse($keysForSteps[1]));
		header("Content-Type: application/json");
		echo json_encode($output);
	} 
	else 
	{
		$output = array();
		$output['status'] = "success";
		$output['message'] = "Feel the vibe.. Keep the screen ON";
		$output['data'] = morseToJSON(getMorse("dead"));
		header("Content-Type: application/json");
		echo json_encode($output);
	}
}





function getMorse($data) 
{

	$code = array(
		'1' => '.----',
		'2' => '..---',
		'3' => '...--',
		'4' => '....-',
		'5' => '.....',
		'6' => '-....',
		'7' => '--...',
		'8' => '---..',
		'9' => '----.',
		'0' => '-----',
		'a' =>'.-',
	    'b' => '-...',
	    'c' => '-.-.',
	    'd' => '-..',
	    'e' => '.',
	    'f' => '..-.');

	$output = '';

	for ($i=0; $i < strlen($data); $i++) { 
		// echo substr($data, $i, 1) .'->'. $code[substr($data, $i, 1)] . '<br/>';
		$output .= $code[substr($data, $i, 1)] . "|";
	}
	

	return $output;
}

function morseToJSON($morse)
{
	$output = array();
	$output[0] = 500;

	$position = 1;
	for ($i=0; $i < strlen($morse); $i++) { 
		$char = substr($morse, $i, 1);
		if($char == '.')
		{
			$output[$position++] = 200;
			$output[$position++] = 300;
		} 
		elseif($char == '-')
		{
			$output[$position++] = 400;
			$output[$position++] = 300;
		}
		elseif($char == '|')
		{
			$output[$position++] = 0;
			$output[$position++] = 600;
		}
	}

	return $output;
}