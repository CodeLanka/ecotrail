<?php
namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

class Question_API implements Question
{

	public function getClueContent($email, $key)
	{
		$answer = new Answer();
		$answer->answerHeader = "text/html";
		$answer->answerContent = "<h2>Take a look at <a href='https://api.ecotrail.gdgsrilanka.org/'>api.ecotrail.gdgsrilanka.org</a><br/>There are problems to solve!</h2>";

		return $answer;

	}

}