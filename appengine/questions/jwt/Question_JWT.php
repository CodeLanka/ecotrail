<?php

namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

class Question_JWT implements Question {

	private $debug = FALSE;
	private $STATIC_KEYS = array("Yala", "Kumana", "Wilpaththu", "Sinharaja", "Udawalawe", "Wasgomuwa", "Sinharaja", "Mahaeliya", "Kaudulla", "Minneriya");
	private $ACTIVE_WINDOW_MINS = 2;
	private $WRONG_ANSWERS_PADDING = 100;	//real answer will be padded with additional X wrong ones top and bottom
	private $WRONG_ANSWERS_TO_MIX_WITH = 300; //this much answers will be present within the padding and contain the real one

	public function getClueContent($email, $key)
	{
		$ans = new Answer();
		$ans->answerHeader = "text/html";
		
		$validTime = $this->getUsersValidTime($key);
		
		//todo remove
		if($this->debug == TRUE) {
			$validTime[0] = 23;
			$validTime[1] = 27;
		}
		
		$todaysValidEpochTime = $this->getEpochForTodaysValidTime($validTime);
		$epochTimeNow = time();

		//this logic certainly can be done better. but this is a time critical op. not a efficiency critical op.!
		// check whether the time now is between the selected time and X minutes later.
		if($todaysValidEpochTime <= $epochTimeNow && ($todaysValidEpochTime + ($this->ACTIVE_WINDOW_MINS * 60)) > $epochTimeNow)
		{
			//this is the active window. give the clues
			$jwts = $this->getManyJWTsWithRealClue($key);
			for ($i=0; $i < count($jwts); $i++) { 
				$ans->answerContent .= $jwts[$i] . '<br/>';
			}
		}
		else
		{
			$staticKey = $this->STATIC_KEYS[rand(0, count($this->STATIC_KEYS) - 1)];
			$token = array(
			    "key" => $staticKey,
			    "email" => $email,
			    "timezone" => "Asia/Colombo"
			);

			//this is an inactive window. find out whether the window is today or tomorrow
			if($this->isValidTimePassedForToday($validTime)) 
			{
				//give the next day's token
				// echo 'you are valid tomorrow <br/>';
				date_default_timezone_set('Asia/Colombo');
				$token["nbf"] = strtotime("+1 day", mktime($validTime[0], $validTime[1]));
				$token["exp"] = $token["nbf"] + (60 * $this->ACTIVE_WINDOW_MINS);
			} 
			else 
			{
				//give today's token
				// echo 'you are valid today <br/>';
				date_default_timezone_set('Asia/Colombo');
				$token["nbf"] = mktime($validTime[0], $validTime[1]);
				$token["exp"] = $token["nbf"] + (60 * $this->ACTIVE_WINDOW_MINS);
				
			}
			$jwt = JWT::encode($token, $staticKey);
			$ans->answerContent = $jwt;
		}

		
		return $ans;
	}



	/**
		gets the valid time where the user needs to come in every day [hour, min]
	*/
	private function getUsersValidTime($key)
	{
		//timetrek to the rescue. we're using the same date algo from there
		//  (https://github.com/tdevinda/timetrek/blob/master/operations/io-2017-timetrek/QuestionC.php)
		$ausHour = 7 + intval($key[0], 16);
      	$ausMin = ((intval($key[1], 16) % 6) *10) + (intval($key[2], 16) % 10);

      	return array($ausHour, $ausMin);


	}

	private function getEpochForTodaysValidTime($validTime)
	{
		date_default_timezone_set('Asia/Colombo');
		return mktime($validTime[0], $validTime[1], 0);
	}

	private function isValidTimePassedForToday($validTime)
	{
		date_default_timezone_set('Asia/Colombo');
		$now = getdate();

		if($now['hours'] > $validTime[0] || $now['hours'] == $validTime[0] && $now['minutes'] > $validTime[1])
		{
			//time has passed.
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}

	/**
		Gives out multitudes of JWTs along with the right one as well.
	*/
	private function getManyJWTsWithRealClue($clue)
	{
		$jwts = array();
		for ($i=0; $i < $this->WRONG_ANSWERS_PADDING; $i++) { 
			$jwts[$i] = $this->generateJWTWithKey(md5(rand(0, 1000) + 'highlands'), $clue);
		}

		$answerPos = rand(0, $this->WRONG_ANSWERS_TO_MIX_WITH);
		for ($i=0; $i < $answerPos; $i++) { 
			$jwts[count($jwts)] = $this->generateJWTWithKey(md5(rand(0, 1000) + 'lowlands'), $clue);
		}
		//now generate the real key
		$answerJWT = $this->generateJWTWithKey($clue, $clue);
		$jwts[count($jwts)] = $answerJWT;

		if($this->debug == TRUE) {
			$jwts[count($jwts) - 1] = '<b>'. $answerJWT . '</b>';
		} 

		//the rest of the mix
		for ($i=0; $i < $this->WRONG_ANSWERS_TO_MIX_WITH - $answerPos - 1; $i++) { 
			$jwts[count($jwts)] = $this->generateJWTWithKey(md5(rand(0, 1000) + 'shoreline'), $clue);
		}

		//bottom padding
		for ($i=0; $i < $this->WRONG_ANSWERS_PADDING; $i++) { 
			$jwts[count($jwts)] = $this->generateJWTWithKey(md5(rand(0, 1000) + 'rivers'), $clue);
		}

		return $jwts;

	}


	/**
		Generate an answer-type jwt with the given key
	*/
	private function generateJWTWithKey($key, $clue)
	{
		$token = array(
		    "key" => $key,
		);	
		$jwt = JWT::encode($token, $clue);

		return $jwt;

	}

}