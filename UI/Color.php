<?php
/**
 * Created by PhpStorm.
 * User: ilk3r
 * Date: 24/09/15
 * Time: 17:27
 */

namespace IO\PDFGenerator\UI;

/**
 * Class Color
 * @package IO\PDFGenerator\UI
 */
class Color {

	/**
	 * @var float
	 */
	private $red;
	/**
	 * @var float
	 */
	private $green;
	/**
	 * @var float
	 */
	private $blue;

	/**
	 * @param float $red
	 * @param float $green
	 * @param float $blue
	 */
	public function __construct($red, $green, $blue) {
		$this->red = (float)$red;
		$this->green = (float)$green;
		$this->blue = (float)$blue;
	}

	/**
	 * @return Color
	 */
	public static function WhiteColor() {
		return new Color(255.0, 255.0, 255.0);
	}

	/**
	 * @return Color
	 */
	public static function BlackColor() {
		return new Color(0.0, 0.0, 0.0);
	}

	/**
	 * @return Color
	 */
	public static function RedColor() {
		return new Color(255.0, 0.0, 0.0);
	}

	/**
	 * @return Color
	 */
	public static function GreenColor() {
		return new Color(0.0, 255.0, 0.0);
	}

	/**
	 * @return Color
	 */
	public static function BlueColor() {
		return new Color(0.0, 0.0, 255.0);
	}

	/**
	 * @param string $hex
	 * @return Color
	 */
	public static function CreateFromHexColor($hex = '#FFFFFF') {

		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
			$r = (float)hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = (float)hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = (float)hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = (float)hexdec(substr($hex,0,2));
			$g = (float)hexdec(substr($hex,2,2));
			$b = (float)hexdec(substr($hex,4,2));
		}

		return new Color($r, $g, $b);
	}

	/**
	 * @return string
	 */
	public function __toString() {

		$redColor = $this->red / 255.0;
		$greenColor = $this->green / 255.0;
		$blueColor = $this->blue / 255.0;

		return $redColor . ' ' . $greenColor . ' ' . $blueColor . ' rg';
	}
}