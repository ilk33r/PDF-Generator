<?php
/**
 * Created by PhpStorm.
 * User: ilk3r
 * Date: 24/09/15
 * Time: 15:13
 */

namespace IO\PDFGenerator\Data;

use IO\PDFGenerator\Data\Table;
use IO\PDFGenerator\Data\Paragraph;
use IO\PDFGenerator\Data\Image;
use IO\PDFGenerator\Data\Line;
use IO\PDFGenerator\Data\Block;

/**
 * Class Bucket
 * @package IO\PDFGenerator\Data
 */
class Bucket {

	/**
	 * @var float|int
	 */
	private $width = 2480;
	/**
	 * @var float|int
	 */
	private $height = 3520;
	/**
	 * @var int
	 */
	private $dpi = 300;
	/**
	 * @var \DateTime
	 */
	private $createDate;
	/**
	 * @var string
	 */
	private $creator;
	/**
	 * @var string
	 */
	private $producer;
	/**
	 * @var Table[]
	 */
	private $tables = array();
	/**
	 * @var array
	 */
	private $paragraphs = array();
	/**
	 * @var array
	 */
	private $images = array();
	/**
	 * @var array
	 */
	private $lines = array();
	/**
	 * @var array
	 */
	private $blocks = array();
	/**
	 * @var ObjectTypes[] $objectTypes
	 */
	private $objectTypes = array();

	/**
	 * @param int $dpi
	 */
	public function __construct($dpi = 300) {
		$this->width = round($dpi * $this->width / $this->dpi);
		$this->height = round($dpi * $this->height / $this->dpi);
		$this->dpi = $dpi;
	}

	/**
	 * @param Table $table
	 */
	public function addTable(Table $table) {

		$this->tables[] = $table;
		end($this->tables);
		$objectId = key($this->tables);
		reset($this->tables);

		$objectType = new ObjectTypes($objectId, ObjectTypes::TABLE);
		$this->objectTypes[] = $objectType;
	}

	/**
	 * @param Table $table
	 */
	public function removeTable(Table $table) {

		$key = array_search($table, $this->tables, true);

		if($key === FALSE) {
			return;
		}

		$objectType = $this->findObject($key);

		if($objectType) {

			unset($this->objectTypes[$objectType]);
			unset($this->tables[$key]);
		}

	}

	/**
	 * @param Paragraph $paragraph
	 */
	public function addParagraph(Paragraph $paragraph) {
		$this->paragraphs[] = $paragraph;
		end($this->paragraphs);
		$objectId = key($this->paragraphs);
		reset($this->paragraphs);

		$objectType = new ObjectTypes($objectId, ObjectTypes::PARAGRAPH);
		$this->objectTypes[] = $objectType;
	}

	/**
	 * @param Paragraph $paragraph
	 */
	public function removeParagraph(Paragraph $paragraph) {

		$key = array_search($paragraph, $this->paragraphs, true);

		if($key === FALSE) {
			return;
		}

		$objectType = $this->findObject($key);

		if($objectType) {

			unset($this->objectTypes[$objectType]);
			unset($this->paragraphs[$key]);
		}

	}

	/**
	 * @param Image $image
	 */
	public function addImage(Image $image) {
		$this->images[] = $image;
		end($this->images);
		$objectId = key($this->images);
		reset($this->images);

		$objectType = new ObjectTypes($objectId, ObjectTypes::IMAGE);
		$this->objectTypes[] = $objectType;
	}

	/**
	 * @param Image $image
	 */
	public function removeImage(Image $image) {

		$key = array_search($image, $this->images, true);

		if($key === FALSE) {
			return;
		}

		$objectType = $this->findObject($key);

		if($objectType) {

			unset($this->objectTypes[$objectType]);
			unset($this->images[$key]);
		}

	}

	/**
	 * @param Line $line
	 */
	public function addLine(Line $line) {
		$this->lines[] = $line;
		end($this->lines);
		$objectId = key($this->lines);
		reset($this->lines);

		$objectType = new ObjectTypes($objectId, ObjectTypes::LINE);
		$this->objectTypes[] = $objectType;
	}

	/**
	 * @param Line $line
	 */
	public function removeLine(Line $line) {

		$key = array_search($line, $this->lines, true);

		if($key === FALSE) {
			return;
		}

		$objectType = $this->findObject($key);

		if($objectType) {

			unset($this->objectTypes[$objectType]);
			unset($this->lines[$key]);
		}

	}

	/**
	 * @param Block $block
	 */
	public function addBlock(Block $block) {
		$this->blocks[] = $block;
		end($this->blocks);
		$objectId = key($this->blocks);
		reset($this->blocks);

		$objectType = new ObjectTypes($objectId, ObjectTypes::BLOCK);
		$this->objectTypes[] = $objectType;
	}

	/**
	 * @param Block $block
	 */
	public function removeBlock(Block $block) {

		$key = array_search($block, $this->blocks, true);

		if($key === FALSE) {
			return;
		}

		$objectType = $this->findObject($key);

		if($objectType) {

			unset($this->objectTypes[$objectType]);
			unset($this->blocks[$key]);
		}

	}

	/**
	 * @param int $objectId
	 * @return bool|int
	 */
	private function findObject($objectId) {

		$objectFinded = false;
		reset($this->objectTypes);

		/** @var ObjectTypes $currentObject */
		$currentObject = current($this->objectTypes);

		do {

			if($currentObject->getObjectId() == $objectId) {
				$objectFinded = key($this->objectTypes);
				break;
			}else{
				$currentObject = next($this->objectTypes);
			}

		} while($currentObject !== FALSE);

		reset($this->objectTypes);

		return $objectFinded;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreateDate()
	{
		return $this->createDate;
	}

	/**
	 * @param \DateTime $createDate
	 */
	public function setCreateDate(\DateTime $createDate)
	{
		$this->createDate = $createDate;
	}

	/**
	 * @return string
	 */
	public function getCreator()
	{
		return $this->creator;
	}

	/**
	 * @param string $creator
	 */
	public function setCreator($creator)
	{
		$this->creator = $creator;
	}

	/**
	 * @return string
	 */
	public function getProducer()
	{
		return $this->producer;
	}

	/**
	 * @param string $producer
	 */
	public function setProducer($producer)
	{
		$this->producer = $producer;
	}

	/**
	 * @return float|int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return float|int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @return int
	 */
	public function getDpi() {
		return $this->dpi;
	}

	/**
	 * @return string
	 */
	public function __toString() {

		$returnString = '';

		$pages = $this->getPages();
		$pageCount = count($pages);
		$objectSizes = array();

		/* UTF-8 BOM */
		$returnString .= "\xEF\xBB\xBF";
		/* PDF Header */
		$returnString .= "%PDF-1.4\n";
		/* Unicode */
		$returnString .= "%���������" . "\n";
		$objectSizes[] = mb_strlen($returnString);

		$catalogObject = "1 0 obj <</Type /Catalog /Pages 8 0 R /Version /1.4>>\nendobj\n";
		$returnString .= $catalogObject;
		$objectSizes[] = strlen($catalogObject);

		$infoObject = "2 0 obj <</CreationDate 3 0 R /Creator 4 0 R /ModDate 3 0 R /Producer 5 0 R>>\nendobj\n";
		$returnString .= $infoObject;
		$objectSizes[] = strlen($infoObject);

		$formattedDate = $this->createDate->format('YmdHis');
		$createDateObject = "3 0 obj (D:{$formattedDate}00Z00'00')\nendobj\n";
		$returnString .= $createDateObject;
		$objectSizes[] = strlen($createDateObject);

		$creatorObject = "4 0 obj ({$this->creator})\nendobj\n";
		$returnString .= $creatorObject;
		$objectSizes[] = strlen($creatorObject);

		$producerObject = "5 0 obj ({$this->producer})\nendobj\n";
		$returnString .= $producerObject;
		$objectSizes[] = strlen($producerObject);

		$fontObject = "6 0 obj <</Font <</F1 7 0 R>>>>\nendobj\n";
		$returnString .= $fontObject;
		$objectSizes[] = strlen($fontObject);

		$fontTypeObject = "7 0 obj <</Type /Font /Subtype /Type1 /BaseFont /Helvetica>>\nendobj\n";
		$returnString .= $fontTypeObject;
		$objectSizes[] = strlen($fontTypeObject);

		$pageDefinitionStartObjectId = 9;
		$pageObjects = '';

		for($i = 0; $i < $pageCount; $i ++) {

			$objectId = $i + $pageDefinitionStartObjectId;
			$pageObjects .= $objectId . ' 0 R';
			if($i < $pageCount) {
				$pageObjects .= ' ';
			}
		}

		$pageDefinitions = "8 0 obj <</Type /Pages /Kids [{$pageObjects}] /Count {$pageCount}>>\nendobj\n";
		$returnString .= $pageDefinitions;
		$objectSizes[] = strlen($pageDefinitions);

		$contentStartObjectId = $pageDefinitionStartObjectId + $pageCount;

		for($j = 0; $j < $pageCount; $j++) {

			$objectId = $j + $pageDefinitionStartObjectId;
			$currentContentStartObjectId = ($j * 2) + $contentStartObjectId;
			$pageObject = "{$objectId} 0 obj <</Type /Pages /Parent 8 0 R /Resources 6 0 R /MediaBox [0 0 {$this->width} {$this->height}] /Contents {$currentContentStartObjectId} 0 R>>\nendobj\n";
			$returnString .= $pageObject;
			$objectSizes[] = strlen($pageObject);
		}

		for($k = 0; $k < $pageCount; $k++) {

			$compressedString = gzcompress($pages[$k]);
			$compressedStringLength = strlen($compressedString) + 1;
			$currentContentStartObjectId = ($k * 2) + $contentStartObjectId;
			$lengthIdx = $currentContentStartObjectId + 1;

			$pageContent = "{$currentContentStartObjectId} 0 obj <</Length {$lengthIdx} 0 R /Filter /FlateDecode>>\nstream\n{$compressedString}\nendstream\nendobj\n";
			$returnString .= $pageContent;
			$objectSizes[] = strlen($pageContent);

			$pageContentLegth = "{$lengthIdx} 0 obj {$compressedStringLength}\nendobj\n";
			$returnString .= $pageContentLegth;
			$objectSizes[] = strlen($pageContentLegth);
		}


		$objectCount = count($objectSizes);
		$pdfObjectCount = $objectCount - 2;
		$xrefObjectCount = $objectCount - 1;
		$xrefObjectDef = "xref\n0 {$pdfObjectCount}\n0000000000 65535 f\n";
		$returnString .= $xrefObjectDef;

		for($y = 0; $y < $xrefObjectCount; $y++) {

			$currentOffset = $objectSizes[$y];
			$startOffsetString = (string)$currentOffset;
			$startOffsetStringLen = strlen($startOffsetString);
			$endCharacterCount = 10 - $startOffsetStringLen;
			$offsetRealString = '';

			for($x = 0; $x < $endCharacterCount; $x ++) {
				$offsetRealString .= '0';
			}
			$offsetRealString .= $startOffsetString;

			$returnString .= "{$offsetRealString} 00000 n\n";
		}

		$startXref = $objectSizes[$xrefObjectCount];
		$returnString .= "trailer <</Size {$pdfObjectCount}/Root 1 0 R>>\nstartxref\n{$startXref}\n%%EOF";

		return $returnString;
	}

	private function getPages() {

		$pages = array();
		$pageLeft = $this->height;

		foreach($this->objectTypes as $object) {

			$pageContent = '';

			switch($object->getObjectType()) {
				case ObjectTypes::TABLE:

					$objId = $object->getObjectId();
					$tableObject = $this->tables[$objId];

					if($tableObject->isStartInNewPage() && !empty($pageContent)) {
						$pages[] = $pageContent;
						$pageLeft = $this->height;
					}

					$tableObject->setPageLeftAndHeight($pageLeft, $this->height);

					do{

						$pageContent .= $tableObject->__toString();
						$hasNextPage = $tableObject->isHasNextPage();

						if($hasNextPage) {
							$pages[] = $pageContent;
							$pageLeft = $this->height;
						}

					}while($hasNextPage);

					break;
			}
		}

		return $pages;
	}
}

/**
 * Class ObjectTypes
 * @package IOPDF\Data
 */
class ObjectTypes {

	/**
	 * Table object
	 */
	const TABLE = 0;
	/**
	 * Paragraph object
	 */
	const PARAGRAPH = 1;
	/**
	 * Image object
	 */
	const IMAGE = 2;
	/**
	 * Line object
	 */
	const LINE = 3;
	/**
	 * Block object
	 */
	const BLOCK = 4;

	/**
	 * @var int $objectId
	 */
	private $objectId;
	/**
	 * @var int $objectType
	 */
	private $objectType;

	/**
	 * @param int $objectId
	 * @param int $type
	 */
	public function __construct($objectId, $type) {
		$this->objectId = $objectId;
		$this->objectType = $type;
	}

	/**
	 * @return int
	 */
	public function getObjectId()
	{
		return $this->objectId;
	}

	/**
	 * @return int
	 */
	public function getObjectType()
	{
		return $this->objectType;
	}
}