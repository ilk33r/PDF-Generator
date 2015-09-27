<?php
/**
 * Created by PhpStorm.
 * User: ilk3r
 * Date: 25/09/15
 * Time: 00:19
 */

namespace IO\PDFGenerator\Exceptions;


/**
 * Class PDFGeneratorException
 * @package IO\PDFGenerator\Exceptions
 */
class PDFGeneratorException extends \Exception {

	const EXCEPTION_TYPE_ZLIB = 0;
	const EXCEPTION_TYPE_DPI_RANGE = 1;
	const EXCEPTION_TYPE_TABLE_ROW_NOT_FOUND = 2;
	const EXCEPTION_TYPE_TABLE_COLUMN_NOT_FOUND = 3;
	const EXCEPTION_TYPE_DIRECTORY_IS_NOT_WRITABLE = 4;
	const EXCEPTION_TYPE_FILE_EXISTS = 5;

	/**
	 * @var array
	 */
	private $exceptionDatas = [
		array(20, 'Unable to load Zlib extension.', 'Zlib support in PHP is not enabled by default. You will need to configure PHP --with-zlib[=DIR]'),
		array(40, 'DPI is not allowed range.', 'Dpi value must be higher than 72 and smaller than 300.'),
		array(50, 'Table row does not exists.', 'You can not remove this row because row does not exists.'),
		array(51, 'Table column does not exists.', 'You can not retrive this column because column does not exists.'),
		array(10, 'Can not create pdf file', 'Directory is not writable.'),
		array(11, 'Can not create pdf file', 'File exists.'),
	];

	/**
	 * @var string
	 */
	private $detailMessage;

	/**
	 * @param int $exceptionType
	 * @param \Exception $previous
	 */
	public function __construct($exceptionType, \Exception $previous = null) {

		$exceptionData = $this->exceptionDatas[$exceptionType];
		$this->detailMessage = $exceptionData[2];
		parent::__construct($exceptionData[1], $exceptionData[0], $previous);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	/**
	 * @return string
	 */
	public function getDetailMessage() {
		return $this->detailMessage;
	}

}