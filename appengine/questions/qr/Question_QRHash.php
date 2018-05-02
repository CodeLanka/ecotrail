<?php

namespace lk\gdgsrilanka\io18;



include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
// use lk\gdgsrilanka\io18\Question;
// use lk\gdgsrilanka\io18\Answer;

class Question_QRHash implements Question
{


	public function getClueContent($email, $key)
	{
		$ans = new Answer();
		$ans->answerHeader = 'text/html';
		// $ans->answerHeader = 'image/png';
		$qr = new QrCode($key);
		$qr->setMargin(0);
		$qr->setSize(290);
		$qr->setErrorCorrectionLevel(ErrorCorrectionLevel::MEDIUM);
		$qrString = $qr->writeString();

		$imageString = $this->getMatrix($qrString);
		// $imageString = $qrString;
		$ans->answerContent = '<span style="font-family: monospace">'. $imageString . '</span>';

		return $ans;

	}


	private function getMatrix($qrData)
	{
		$imageString = '';
		$imageStringHex = '';

		$image = imagecreatefromstring($qrData);
		for($y = 5;$y < 290;$y += 10)
		{
			$currentString = '';
			for($x = 5; $x < 290;$x+=10) 
			{
				if(imagecolorat($image, $x, $y) > 3000)
				{
					//this is white
					$currentString .= '0';
				}
				else 
				{
					$currentString .= '1';
				}

			}
			$imageString .= ($currentString . '<br/>');
			$imageStringHex .= $this->binaryStringToHexString($currentString) . '<br/>';
			
		}

		imagedestroy($image);
		return $imageStringHex;

	}


	private function binaryStringToHexString($binaryString)
	{
		$output = '';
		$paddingLength = strlen($binaryString) % 4;
		// echo 'len='. strlen($binaryString) . ' '. $paddingLength . '<br/>';
		// echo $paddingLength . '<br/>';
		for ($i=0; $i < (4 - $paddingLength); $i++) { 
				$binaryString = ('0'. $binaryString);
		}

		for ($i=0; $i < strlen($binaryString); $i += 4) { 
			$currentHexString = dechex(bindec(substr($binaryString, $i, 4)));
			// $output .= $currentHexString . '('. substr($binaryString, $i, 4) .')';
			$output .= $currentHexString;
		}

		return $output;
	}
}