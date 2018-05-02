<?php

namespace lk\gdgsrilanka\io18;
header("Access-Control-Allow-Origin: *");

require 'vendor/autoload.php';
include_once('User.php');

$VALID_QUESTIONS = "AB";

$email = $_REQUEST['u'];
$key = FALSE;
if(isset($_REQUEST['k']))
{
	$key = preg_replace('/^(\/)?(.*)$/', "$2", $_REQUEST['k']);
	if(strlen($key) == 0) {
		$key = FALSE;
	}
}

//if the user is not setting this shared key, we assume this URL is compromised and 
//  return 404
if(isset($_REQUEST['p'])) 
{
	if($_REQUEST['p'] != 'IO18p4s5w0r$_2018_IO_EXTENDED') 
	{
		header("HTTP/1.0 404 Not Found");
		die();
	}
} 
else 
{
	header("HTTP/1.0 404 Not Found");
	die();
}

//create the user and the required steps
$user = new User($email);
// var_dump($user);
$keysForSteps = $user->getKeysForSteps();
$questionOrder = $user->getQuestionOrder();

if($key != FALSE)
{
	$oldKeyPosition = $user->getKeyPosition($key);
	if($oldKeyPosition != FALSE)
	{
		if($oldKeyPosition == 4) {
			//this should not be the case
			echo 'You solved everything. no need to solve again';
		} else {
			//the given key is correct. give the next key (we have to compare with not the position of the given key, but)
			// with the position after that. because we're at the next question now.
			$currentQuestionType = substr($questionOrder, $oldKeyPosition, 1);
			if(stripos($VALID_QUESTIONS, $currentQuestionType) !== FALSE) {
				$output = array();
				$output['status'] = TRUE;
				$output['key'] = $keysForSteps[$oldKeyPosition + 1];
				
				header("Content-Type: application/json");
				echo json_encode($output);
			} else {
				//this is not the question type for this user's current position
				
				$output = array();
				$output['status'] = FALSE;
				$output['message'] = "Wrong question";
				header("Content-Type: application/json");
				echo json_encode($output);
			}
			
		}
	}
	else
	{
		//user has entered an invalid key. give the error page
		$output = array();
		$output['status'] = FALSE;
		$output['message'] = "Wrong key";
		header("Content-Type: application/json");
		echo json_encode($output);
	}
}
//if there is no key, we need to give an error
else 
{
	//API question came first. support that.
	$currentQuestionType = substr($questionOrder, 0, 1);
	if(stripos($VALID_QUESTIONS, strtoupper($currentQuestionType)) !== FALSE) {
		$output = array();
		$output['status'] = TRUE;
		$output['key'] = $keysForSteps[1];
		header("Content-Type: application/json");
		echo json_encode($output);
	} 
	else 
	{
		$output = array();
		$output['status'] = FALSE;
		$output['message'] = "Wrong start";
		header("Content-Type: application/json");
		echo json_encode($output);
	}
}

