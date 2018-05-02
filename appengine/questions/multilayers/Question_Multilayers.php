<?php

namespace lk\gdgsrilanka\io18;

include_once 'Question.php';
include_once 'Answer.php';
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

/**
	key ->morse -> (0,1,s) -> base64 -> barcode -> base64 image hex -> binary -> smileys
	key -> base64 -> barcode -> base64imagehex -> color coded image (with random key hint)
*/
class Question_Multilayers implements Question {

	private $DEBUG = FALSE;
	private $PER_LETTER_SQUARE_SIZE = 40;

	private $colorIndexString;
	private $base64String = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

	public function getClueContent($email, $key)
	{
		$this->colorIndexString = $this->createColorIndexString();

		$ans = new Answer();
		$ans->answerHeader = "text/html";

		//get the key, base64 it, and generate the barcode
		$barcodeBase64String = $this->getPNGBarcodeContent(base64_encode($key));

		//now we need to convert this into the new image with key
		$ans->answerContent = '<img src="data:image/png;base64,'. base64_encode($this->getImageText($this->getDecodingHelperImage())) .'" /><br/><br/>';

		if($this->DEBUG == TRUE) 
		{
			$ans->answerContent .= $barcodeBase64String;
			$ans->answerContent .= '<img src="data:image/png;base64,'. $barcodeBase64String .'" /><br/>';
		}

		//this is the clue
		$ans->answerContent .= '<img class="clue-images" src="data:image/png;base64,'. base64_encode($this->getImageText($this->getClueImage($barcodeBase64String))) .'" /><br/>';


		return $ans;

	}


	private function getPNGBarcodeContent($string)
	{
		$gen = new BarcodeGeneratorPNG();
		$image = $gen->getBarCode($string, $gen::TYPE_CODE_128);

		return base64_encode($image);
	}


	/**
	  The color at the key matrix's position 0 need not be color index 0, this should be random
	    This generation sets the randomized color pattern
	*/
	private function createColorIndexString()
	{
		$initialIndex = "01234567";

		return $initialIndex;
	}


	/**
		Generates the image that will help decoding the clue
	*/
	private function getDecodingHelperImage()
	{
		$keyImage = imagecreatetruecolor($this->PER_LETTER_SQUARE_SIZE * 8, $this->PER_LETTER_SQUARE_SIZE * 8);
		for ($row=0; $row < 8; $row++) { 
			for ($col=0; $col < 8; $col++) { 
				$currentLetterReplacementKeyImage = $this->createLetterCompositeSquareFromLetterPos($row, $col);
				//echo '<img src="data:image/png;base64,'. base64_encode($this->getImageText($currentLetterReplacementKeyImage)) .'" /><br/>';
				imagecopy($keyImage, $currentLetterReplacementKeyImage, $col * $this->PER_LETTER_SQUARE_SIZE, $row * $this->PER_LETTER_SQUARE_SIZE, 0, 0, $this->PER_LETTER_SQUARE_SIZE, $this->PER_LETTER_SQUARE_SIZE);
				$textx = ($col + 0.5) * $this->PER_LETTER_SQUARE_SIZE;
				$texty = ($row + 0.5) * $this->PER_LETTER_SQUARE_SIZE;
				// var_dump($this->getBase64LetterForMatrixPosition($row, $col));
				imagettftext($keyImage, 12, 0, $textx, $texty, imagecolorallocate($keyImage, 255, 255, 255), 'questions/jigsaw/lucon.ttf', $this->getBase64LetterForMatrixPosition($row, $col));
			}
		}

		return $keyImage;

	}


	/**
		Generates the image with the actual clue
	*/
	private function getClueImage($clueString)
	{
		$boxesPerSide = ceil(sqrt(strlen($clueString)));

		$image = imagecreatetruecolor($boxesPerSide * $this->PER_LETTER_SQUARE_SIZE, $boxesPerSide * $this->PER_LETTER_SQUARE_SIZE);

		//fill the image white.
		imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));

		for ($row=0; $row < $boxesPerSide; $row++) { 
			for ($col=0; $col < $boxesPerSide; $col++) { 
				$currentLetter = substr($clueString, ($row * $boxesPerSide) + $col, 1);
				if($currentLetter !== FALSE)
				{
					$letterPosition = $this->get8x8MatrixPositionForLetter($currentLetter);
					if($letterPosition !== NULL)
					{
						$correspondingSquare = $this->createLetterCompositeSquareFromLetterPos($letterPosition[0], $letterPosition[1]);

						//we place the text in the clue square. Notice the row col are swapped for image x,y rather than
						//  matrix row col
						$destx = $col * $this->PER_LETTER_SQUARE_SIZE;
						$desty = $row * $this->PER_LETTER_SQUARE_SIZE;
						imagecopy($image, $correspondingSquare, $destx, $desty, 0, 0, $this->PER_LETTER_SQUARE_SIZE, $this->PER_LETTER_SQUARE_SIZE);
					}
					else 
					{
						//this might be a = sign
						$textx = ($col + 0.2) * $this->PER_LETTER_SQUARE_SIZE;
						$texty = ($row + 0.7) * $this->PER_LETTER_SQUARE_SIZE;
						imagettftext($image, 30, 0, $textx, $texty, imagecolorallocate($image, 0, 0, 0), 'questions/jigsaw/lucon.ttf', '=');
					
					}
				} 
				else 
				{
					//no filling
					// var_dump($currentLetter);
				}
			}
		}

		return $image;
	}

	private function createLetterCompositeSquareFromLetterPos($row, $col)
	{
		$rowColorIndex = substr($this->colorIndexString, $row, 1);
		$columnColorIndex = substr($this->colorIndexString, $col, 1);
		// echo $row . ' ' . $col . '<br/>';
		// var_dump($rowColorIndex);
		// var_dump($columnColorIndex);
		$rowColor = $this->getColorForIndex($rowColorIndex);
		$colColor = $this->getColorForIndex($columnColorIndex);

		// var_dump($rowColor);
		// var_dump($colColor);
		$box = imagecreatetruecolor($this->PER_LETTER_SQUARE_SIZE, $this->PER_LETTER_SQUARE_SIZE);
		$backgroundColor = imagecolorallocate($box, $rowColor[0], $rowColor[1], $rowColor[2]);
		$smallBoxColor = imagecolorallocate($box, $colColor[0], $colColor[1], $colColor[2]);

		imagefill($box, 0, 0, $backgroundColor);
		$p1 = $this->PER_LETTER_SQUARE_SIZE / 4;
		$p2 = ($this->PER_LETTER_SQUARE_SIZE * 3) / 4;
		imagefilledrectangle($box, $p1, $p1, $p2, $p2, $smallBoxColor);

		return $box;

	}


	/**
		If the entire base64 set is a 8x8 matrix, returns the position of a given letter
		  in that matrix in [row, col]
	*/
	private function get8x8MatrixPositionForLetter($base64Letter) 
	{		
		$pos = strpos($this->base64String, $base64Letter);
		if($pos !== FALSE)
		{
			$row = floor($pos / 8);
			$col = $pos % 8;

			return array($row, $col);
		} 
		else 
		{
			return NULL;
		}
	}

	private function getBase64LetterForMatrixPosition($row, $col)
	{
		return substr($this->base64String, ($row * 8) + $col, 1);
	}

	/**
	  From 0 - 7 there are 8 colors. Give the index, and you will get the corresponding color in
	    [R, G, B] array
	*/
	private function getColorForIndex($index)
	{
		switch ($index) {
			case 0:
				return array(0, 0, 0);
			case 1:
				return array(255, 0, 0);
			case 2:
				return array(0, 0, 255);
			case 3:
				return array(100, 127, 127);
			case 4:
				return array(255, 0, 255);
			case 5:
				return array(63, 127, 0);
			case 6:
				return array(127, 63, 63);
			case 7:
				return array(255, 127, 0);
		}
	}

	private function getImageText($image)
	{
		ob_start();
	    imagejpeg($image);
	    $imgData = ob_get_contents();
	    ob_end_clean();
		
	    return $imgData;
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



}