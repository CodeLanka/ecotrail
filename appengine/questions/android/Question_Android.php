<?php
namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

class Question_Android implements Question
{

	public function getClueContent($email, $key)
	{
		$answer = new Answer();
		$answer->answerHeader = "text/html";
		$answer->answerContent = "<h2>Fix up and feel the vibe https://github.com/ecotrail/ecotrailvibe</h2>";

		return $answer;

	}


	private function stringToHex($string)
	{

	}


	private function getMorse($data) 
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
}