<?php

namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

class Question_Jigsaw implements Question {

	private $debug = FALSE;

	public function getClueContent($email, $key)
	{
		$ans = new Answer();
		$ans->answerHeader = "text/html";
		$image = imagecreatefromjpeg('questions/jigsaw/picture_4'. /*rand(1,8)*/'' .'.jpg');

		if($this->debug == TRUE) 
		{
			$w = imagesx($image);  
			$h = imagesy($image);

			for ($i=0; $i < 32; $i++) { 
				$x = ($i % 8) * ($w / 8);
				$y = floor($i / 8) * ($h / 4);

				imagettftext($image, 20, 0, $x + 10, $y + 25, imagecolorallocate($image, 0, 0, 0), 'questions/jigsaw/lucon.ttf', $i .'('. $key[$i] .')');
			}

			// $ans->answerContent = $key . '<br/>';

			
		}
		$swaps = $this->getSwaps();

		$oldkey = $key;
		for ($i=0; $i < count($swaps); $i++) { 

			$image = $this->swapImagePart($image, $swaps[$i][0], $swaps[$i][1]);
			// echo 'swapping '. $swaps[$i][0] . ' with '. $swaps[$i][1] . '<br/>';
			// echo $key[$swaps[$i][0]] .'<-->' . $key[$swaps[$i][1]] . '<br/>';
			$key = $this->swapKeyPart($key, $swaps[$i][0], $swaps[$i][1]);
		}
		$ans->answerContent = '<img class="clue-images" src="data:image/jpeg;base64,'. base64_encode($this->getImageText($image)) . '"/><br/>'. $key;
		// $ans->answerHeader = "text/html";
		// $ans->answerContent = "this is a test";

		return $ans;

	}

	/**
	  Swaps an image's content by breaking it into 8x4 parts parts start from 0 and end at 31

	*/
	private function swapImagePart($image, $from, $with)
	{
		
		$w = imagesx($image);  
		$h = imagesy($image);
		
		$image1 = imagecreatetruecolor(($w / 8), ($h / 4));
		$image2 = imagecreatetruecolor(($w / 8), ($h / 4));

		$x1 = ($from % 8) * ($w / 8);
		$y1 = floor($from / 8) * ($h / 4);
		
		imagecopy($image1, $image, 0, 0, $x1, $y1, $w / 8, $h / 4);
		// imagecopy($tempImage, $image, 0, 0, 0, 0, $w / 8, $h / 4);

		$x2 = ($with % 8) * ($w / 8);
		$y2 = floor($with / 8) * ($h / 4);
		imagecopy($image2, $image, 0, 0, $x2, $y2, $w / 8, $h / 4);


		imagecopy($image, $image1, $x2, $y2, 0, 0, ($w / 8), ($h / 4));
		imagecopy($image, $image2, $x1, $y1, 0, 0, ($w / 8), ($h / 4));

		// echo $from. '->'. $with . " ". $x1. ','. $y1 . ':'. $x2 .','. $y2 . "<br/>";
		return $image;
		// return $x1. ','. $y1 . ':'. $x2 .','. $y2;
	}

	/**
	  Swaps the letters of the key according to the given positions
	*/
	private function swapKeyPart($key, $from, $with) 
	{
		$str1 = substr($key, $from, 1);
		$str2 = substr($key, $with, 1);

		$key[$from] = $str2;
		$key[$with] = $str1;
		
		return $key;
	}

	private function getImageText($image)
	{
		ob_start();
	    imagejpeg($image);
	    $imgData = ob_get_contents();
	    ob_end_clean();
		
	    return $imgData;
	}

	private function getSwaps()
	{
		$swaps = array();
		for ($i=0; $i < 20; $i++) { 
			$swaps[$i] = array(rand(0,31), rand(0,31));
		}

		return $swaps;
	}

}

//1536*2048