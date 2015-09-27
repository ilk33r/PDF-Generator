<?php
/**
 * Created by PhpStorm.
 * User: ilk3r
 * Date: 25/09/15
 * Time: 00:13
 */

namespace IO\PDFGenerator\UI;

use IO\PDFGenerator\UI\Color;

/**
 * Class Text
 * @package IO\PDFGenerator\UI
 */
class Text {

	/**
	 * Helvetica font width for 100pt size
	 */
	const HELVETICA_X_WIDTH = 0.49;
	/**
	 * Helvetica font aspect ratio for 100pt size
	 */
	const HELVETICA_ASPECT = 0.52;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var int
	 */
	private $fontSize;

	/**
	 * @var Color
	 */

	private $color;

	/**
	 * @var float
	 */
	private $positionX;

	/**
	 * @var float
	 */
	private $positionY;

	/**
	 * @var array
	 */
	private $wordWrap;

	/**
	 * @var float|int
	 */
	private $paddingLeft;

	/**
	 * @var float|int
	 */
	private $paddingRight;

	/**
	 * @var float|int
	 */
	private $characterHeightPixel;

	/**
	 * @var float|int
	 */
	private $canvasHeight;

	/**
	 * @param string $text
	 * @param int $fontSize
	 * @param int|float $paddingLeft
	 * @param int|float $paddingRight
	 * @param Color $color
	 */
	public function __construct($text, $fontSize, $paddingLeft = 0, $paddingRight = 0, Color $color = null) {

		$this->text = $text;
		$this->fontSize = $fontSize;

		if(is_null($color)) {
			$this->color = Color::WhiteColor();
		}else{
			$this->color = $color;
		}

		$this->wordWrap = array();
		$this->paddingLeft = $paddingLeft;
		$this->paddingRight = $paddingRight;
	}

	/**
	 * @return int
	 */
	public function getFontSize()
	{
		return $this->fontSize;
	}

	/**
	 * @param int|float $positionX
	 * @param int|float $positionY
	 * @param int|float $canvasWidth
	 * @param int|float $canvasHeight
	 * @param int $dpi
	 */
	public function setPosition($positionX, $positionY, $canvasWidth, $canvasHeight, $dpi) {

		$this->positionX = $positionX;
		$this->positionY = $positionY;

		$words = explode(' ', $this->text);
		$characterWidht = $this->fontSize * self::HELVETICA_X_WIDTH / 100;
		$characterHeight = $characterWidht / self::HELVETICA_ASPECT;
		$characterWidhtPixel = $characterWidht * $dpi;
		$this->characterHeightPixel = $characterHeight * $dpi;
		$this->canvasHeight = $canvasHeight;
		$lineCount = floor($canvasHeight / $this->characterHeightPixel);
		$currentLine = 0;
		$canvasRealWidth = $canvasWidth - $this->paddingLeft - $this->paddingRight;
		$currentString = '';

		foreach($words as $word) {

			$allWordWidth = mb_strlen($currentString) * $characterWidhtPixel;
			$currentWordWidth = mb_strlen($word) * $characterWidhtPixel;
			$sumOfWidth = $allWordWidth + $currentWordWidth;

			if($sumOfWidth <= $canvasRealWidth) {

				$currentString .= $word . ' ';
				continue;
			}

			if($currentLine <= $lineCount) {

				$wordObject = new \stdClass();
				$wordObject->startX = $this->paddingLeft + $this->positionX;
				$wordObject->string = $currentString;

				$this->wordWrap[] = $wordObject;
				$currentString = '';
				$currentLine++;
			}else{
				break;
			}
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {

		$textObjectString = $this->color->__toString() . ' ';
		$lineCount = count($this->wordWrap);
		$textHeight = $lineCount * $this->characterHeightPixel;
		$textBottom = $this->positionY + ($this->canvasHeight / 2) - ($textHeight / 2);

		foreach($this->wordWrap as $wordObject) {

			$textObjectString .= 'BT /F1 ' . $this->fontSize . ' TF ' . $wordObject->startX . ' '
								. $textBottom . ' (' . $wordObject->string . ')Tj ET' . "\n";
			$textBottom -= $this->characterHeightPixel;
		}

		return $textObjectString;
	}

}