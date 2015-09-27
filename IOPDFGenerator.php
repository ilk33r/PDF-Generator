<?php
/**
 * Created by PhpStorm.
 * User: ilk3r
 * Date: 24/09/15
 * Time: 15:24
 */

namespace IO\PDFGenerator;

use IO\PDFGenerator\Data\Bucket;
use IO\PDFGenerator\Exceptions\PDFGeneratorException;
use IO\PDFGenerator\Data\Table;
use IO\PDFGenerator\UI\Color;

/**
 * Class IOPDFGenerator
 * @package IO\PDFGenerator
 */
class IOPDFGenerator {

	/** @var Bucket $bucket */
	private $bucket;

	/**
	 * @param string $creator
	 * @param string $producer
	 * @param null $dpi
	 * @throws PDFGeneratorException
	 */
	public function __construct($creator = 'PHP IO PDF Generator', $producer = 'PHP IO PDF Generator PDFContext', $dpi = null) {

		if(!extension_loaded('zlib')) {
			throw new PDFGeneratorException(PDFGeneratorException::EXCEPTION_TYPE_ZLIB);
		}

		if(is_int($dpi)) {

			if($dpi < 72 || $dpi > 300) {
				throw new PDFGeneratorException(PDFGeneratorException::EXCEPTION_TYPE_DPI_RANGE);
			}

			$this->bucket = new Bucket($dpi);
		}else{
			$this->bucket = new Bucket();
		}

		$this->bucket->setCreateDate(new \DateTime('now'));
		$this->bucket->setCreator($creator);
		$this->bucket->setProducer($producer);
	}

	/**
	 * @param $column
	 * @param $marginTop
	 * @param $marginLeftColumn
	 * @param Color|null $borderColor
	 * @return Table
	 */
	public function createTable($column, $marginTop, $marginLeftColumn, Color $borderColor = null) {

		$pdfWidth = $this->bucket->getWidth();
		$columnWidth = $pdfWidth / $column;
		$marginLeft = $columnWidth * $marginLeftColumn;
		$table = new Table($column, $marginTop, $marginLeft, $columnWidth, $borderColor, $this->bucket->getDpi());
		$this->bucket->addTable($table);

		return $table;
	}

	/**
	 * @param Table $table
	 */
	public function removeTable(Table $table) {
		$this->bucket->removeTable($table);
	}

	/**
	 * @param $path
	 * @param $fileName
	 * @throws PDFGeneratorException
	 */
	public function generatePdf($path, $fileName) {

		if(!is_writable($path)) {
			throw new PDFGeneratorException(PDFGeneratorException::EXCEPTION_TYPE_DIRECTORY_IS_NOT_WRITABLE);
		}

		$pathComponent = ( (substr($path, strlen($path) - 1, 1) != '/') ? '/' : '') . $fileName;

		if(file_exists($pathComponent)) {
			throw new PDFGeneratorException(PDFGeneratorException::EXCEPTION_TYPE_FILE_EXISTS);
		}

		$pdfContent = $this->bucket->__toString();

		$pdfHandle = fopen($pathComponent, 'wb');
		fwrite($pdfContent, $pdfHandle);
		fclose($pdfHandle);
	}

}