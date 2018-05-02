<?php
namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

use Povils\Figlet\Figlet;


class Question_Figlet implements Question 
{
	public function getClueContent($email, $key)
	{
		$file = file_get_contents('questions/golang/test.go');
		$string = $this->getObscureString($key);

		$txt = str_replace("%%", $string, $file);



    	$ans = new Answer();
    	$ans->answerHeader = "text/plain";
    	$ans->answerContent = base64_encode($txt);

    	return $ans;

	}	



	private function getObscureString($string) 
	{
		$output = "";
		for($i = 0;$i < strlen($string);$i++)
		{
			$output .= $this->getObscureChar(substr($string, $i, 1));
		}

		return $output;
	}

	private function getObscureChar($char) 
	{
		
		if($char == "0") {
			return "!";
		} elseif($char == "1") {
			return "@";
		} elseif ($char == "2") {
			return "#";
		} elseif ($char == "3") {
			return "$";
		} elseif ($char == "4") {
			return "^";
		} elseif ($char == "5") {
			return "&";
		} elseif ($char == "6") {
			return "5";
		}  elseif ($char == "7") {
			return "(";
		} elseif ($char == "8") {
			return ")";
		} elseif ($char == "9") {
			return ";";
		} elseif ($char == "a") {
			return "d";
		} elseif ($char == "b") {
			return ">";
		} elseif ($char == "c") {
			return "~";
		} elseif ($char == "d") {
			return "+";
		} elseif ($char == "e") {
			return "/";
		} elseif ($char == "f") {
			return "e";
		}
	}


}