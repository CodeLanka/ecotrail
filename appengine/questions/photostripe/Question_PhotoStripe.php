<?php

namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

class Question_PhotoStripe implements Question {


	public function getClueContent($email, $key)
	{
		$ans = new Answer();
		$mainImage = imagecreatefromjpeg('questions/jigsaw/picture_'. rand(1,8) .'.jpg');
		$keyImage = $this->getKeyPhoto($key, imagesx($mainImage));
		
		$mainHeight = imagesy($mainImage);
		$blockHeight = intval($mainHeight / 22);

		for ($i=0; $i < 22; $i++) { 
			$this->pasteImageStripe($mainImage, $keyImage, ($i * $blockHeight) + rand(0, ($blockHeight / 2)), $i);
		}
		
		$ans->answerHeader = "text/html";
		$ans->answerContent = '<img class="clue-images" src="data:image/jpeg;base64,'. base64_encode($this->getImageText($mainImage)) . '"/><br/>';

		return $ans;
	}


	private function getKeyPhoto($key, $width) 
	{
		$image = imagecreatetruecolor($width, 20);
		$black = imagecolorallocate($image, 0, 0, 0);
		imagefill($image, 0, 0, $black);
		imagettftext($image, 14, 0, ($width / 2) - (14 * 16), 18, imagecolorallocate($image, 255, 255, 255), 'questions/jigsaw/lucon.ttf', $key);

		return $image;
	}



	private function getImageText($image)
	{
		ob_start();
	    imagepng($image);
	    $imgData = ob_get_contents();
	    ob_end_clean();
		
	    return $imgData;
	}


	private function pasteImageStripe($mainImage, $keyImage, $mainPosition, $stripePosition)
	{
		imagecopy($mainImage, $keyImage, 0, $mainPosition, 0, $stripePosition, imagesx($keyImage), 1);
	}

}