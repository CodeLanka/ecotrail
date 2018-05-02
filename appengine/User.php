<?php

namespace lk\gdgsrilanka\io18;


class User 
{
	private $CONSTANT_KEY = "io-18-quiz-game";
	private $QUESTIONS_IN_SPRINT = 4;
	private $email;
	private $keys = array();
	

	public function __construct($email) 
	{
		$this->email = $email;
		
		for($i=1;$i <= $this->QUESTIONS_IN_SPRINT+1;$i++) 
		{
			$this->keys[$i] = $this->getKeyToEnterStep($i);

		}
	}


	//23431adcacdd2009
	private function getUserHash()
	{
		return md5($this->email . $this->CONSTANT_KEY);
	}

	public function getQuestionOrder()
	{
		$keys = $this->getUserHash();
		
		$question_string = "";

		for ($i=0;(strlen($question_string) < 4 && $i < strlen($keys)); $i++) { 
			$current_question_selection = substr($keys, $i, 1);
			if(strpos($question_string, $current_question_selection) === FALSE && strpos($question_string, $this->getDopplegangerQuestionIndex($current_question_selection)) === FALSE)
			{
				//select question.
				$question_string .= $current_question_selection;
				// echo $current_question_selection . " is added<br/>";
			} 
			else 
			{
				// echo $current_question_selection ." or ".  " is here<br/>";
			}
		}

		
		
		
		return $question_string;

	}


	/**
	Gets the grouped other question for a given question e.g. 0 is grouped with 1 (same question for both letters)
	If 0 is given 1 is returned, if 1 is given, the other quetion, 0 is returned.
	*/
	private function getDopplegangerQuestionIndex($index)
	{
		
		switch (strtolower($index)) {
			case '0':
				return '1';
			case '1':
				return '0';
			case '2':
				return '3';
			case '3':
				return '2';
			case '4':
				return '5';
			case '5':
				return '4';
			case '6':
				return '7';
			case '7':
				return '6';
			case '8':
				return '9';
			case '9':
				return '8';
			case 'a':
				return 'b';
			case 'b':
				return 'a';
			case 'c':
				return 'd';
			case 'd':
				return 'c';
			case 'e':
				return 'f';
			case 'f':
				return 'e';
			default:
				return '0';
		}
	}

	/**
	  gets the key required to enter step x. if you provide the step as x, that is the key (say kx) which
	    will encode the current clue to enter step x. e.g. if x=1, a question will be rendered with the 
	      answer set as k1.
	*/
	private function getKeyToEnterStep($step)
	{
		return md5($this->getUserHash() . $step);
	}




	/**
		Gives the keys for all steps of the game for this user
	*/
	public function getKeysForSteps()
	{
		return $this->keys;
	}

	public function getFinalKey()
	{
		return $this->getKeyToEnterStep(5);
	}


	/**
	  Finds the position of the given $key in the user's keys for steps. If the key is present,
	    the step number is returned. If the key is invalid (no matches), FALSE is returned
	*/
	public function getKeyPosition($key)
	{
		for ($i=1; $i <= count($this->keys); $i++) { 
			if(strcmp($key, $this->keys[$i]) == 0)
			{
				return $i;
			}
		}

		return FALSE;
	}

	public function getUserDockerType()
	{
		$finalLetter = substr($this->getUserHash(), 31, 1);
		$finalLetterValue = intval($finalLetter, 16);

		switch ($finalLetterValue) {
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
				return 1;	//Tree
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
				return 2;	//Water
		}
	}

}