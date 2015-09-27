<?php
/**
 * Created by PhpStorm.
 * User: ilk3r
 * Date: 24/09/15
 * Time: 17:13
 */

namespace IO\PDFGenerator\Data;

use IO\PDFGenerator\Exceptions\PDFGeneratorException;
use IO\PDFGenerator\UI\Color;
use IO\PDFGenerator\UI\Text;

/**
 * Class Table
 * @package IO\PDFGenerator\Data
 */
class Table {

	/**
	 * @var int
	 */
	private $columnCount;
	/**
	 * @var float
	 */
	private $columnWidth;
	/**
	 * @var float
	 */
	private $marginTop;
	/**
	 * @var float
	 */
	private $marginLeft;
	/**
	 * @var int|float
	 */
	private $tableWidth;
	/**
	 * @var bool
	 */
	private $hasBorder;
	/**
	 * @var Color
	 */
	private $borderColor;
	/**
	 * @var TableRow[]
	 */
	private $rows;
	/**
	 * @var bool
	 */
	private $startInNewPage;
	/**
	 * @var int|float
	 */
	private $pageLeft;
	/**
	 * @var int|float
	 */
	private $pageHeight;
	/**
	 * @var bool
	 */
	private $hasNextPage;
	/**
	 * @var int
	 */
	private $rowLeft;
	/**
	 * @var int
	 */
	private $startRow;
	/**
	 * @var int|float
	 */
	private $tableHeight;

	/**
	 * @param int $column
	 * @param float $marginTop
	 * @param float $marginLeft
	 * @param float $columnWidth
	 * @param Color $borderColor
	 * @param bool $startInNewPage
	 * @param int $dpi
	 */
	public function __construct($column, $marginTop, $marginLeft, $columnWidth, $tableWidth, Color $borderColor = null, $dpi = 300) {

		$this->columnCount = $column;
		$this->columnWidth = $columnWidth;
		$this->marginTop = $marginTop;
		$this->marginLeft = $marginLeft;
		$this->tableWidth = $tableWidth;

		if(is_null($borderColor)) {
			$this->hasBorder = false;
		}else{
			$this->hasBorder = true;
			$this->borderColor = $borderColor;
		}

		$this->startInNewPage = false;
		$this->rows = array();
		$this->hasNextPage = false;
		$this->rowLeft = 0;
		$this->startRow = 0;
		$this->tableHeight = 0;
		$this->dpi = $dpi;
	}

	/**
	 * @param int $rowHeight
	 * @return TableRow
	 */
	public function addRow($rowHeight = 20) {

		$tableRow = new TableRow($this->columnCount, $rowHeight, $this->hasBorder, $this->borderColor,
			$this->marginLeft, $this->columnWidth);
		$this->rows[] = $tableRow;
		$this->rowLeft += 1;

		return $tableRow;
	}

	/**
	 * @param int $rowNumber
	 * @throws PDFGeneratorException
	 */
	public function removeRow($rowNumber = 0) {

		if(isset($this->rows[$rowNumber])) {
			$this->rows[$rowNumber]->setIsDeleted(true);
			$this->rowLeft -= 1;
		}else{
			throw new PDFGeneratorException(PDFGeneratorException::EXCEPTION_TYPE_TABLE_ROW_NOT_FOUND);
		}
	}

	/**
	 * @return boolean
	 */
	public function isStartInNewPage()
	{
		return $this->startInNewPage;
	}

	/**
	 * @param boolean $startInNewPage
	 */
	public function setStartInNewPage($startInNewPage)
	{
		$this->startInNewPage = $startInNewPage;
	}

	/**
	 * @param int|float $pageLeft
	 * @param int|float $pageHeight
	 */
	public function setPageLeftAndHeight($pageLeft, $pageHeight)
	{
		$this->pageLeft = $pageLeft - $this->marginTop;
		$this->pageHeight = $pageHeight;
	}

	/**
	 * @return boolean
	 */
	public function isHasNextPage()
	{
		return $this->hasNextPage;
	}

	/**
	 * @return string
	 */
	public function __toString() {

		$returnString = '';

		$rowCount = count($this->rows);
		$lastRow = 0;
		$firstBorderDrawed = false;
		for($i = $this->startRow; $i < $rowCount; $i++) {

			$currentRow = $this->rows[$i];
			if($currentRow->isIsDeleted()) {
				continue;
			}

			$this->tableHeight = $currentRow->getRowHeight();

			if($this->tableHeight >= $this->pageLeft) {

				if($this->rowLeft > 0) {

					$this->hasNextPage = true;
				}else{
					$this->hasNextPage = false;
				}
			}

			if($this->hasBorder && $firstBorderDrawed) {

				$firstBorderDrawed = true;
				$sectionWidth = $this->tableWidth + $this->marginLeft;
				/* top border */
				$returnString .= $this->borderColor->__toString() . ' ' . $this->marginLeft . ' ' . $this->pageLeft .
					' m ' . $sectionWidth . ' ' . $this->pageLeft . ' l h S' . "\n";
			}

			$currentRow->setPageLeft($this->pageLeft);
			$returnString .= $currentRow->__toString();
			$this->pageLeft -= $currentRow->getRowHeight();
			$this->rowLeft -= 1;
			$lastRow = $i;
		}

		$this->startRow = $lastRow;

		return $returnString;
	}

}

/**
 * Class TableRow
 * @package IO\PDFGenerator\Data
 */
class TableRow {

	/**
	 * @var int
	 */
	private $columnCount;
	/**
	 * @var int|float
	 */
	private $rowHeight;
	/**
	 * @var bool
	 */
	private $hasBorder;
	/**
	 * @var Color
	 */
	private $borderColor;
	/**
	 * @var TableColumn[]
	 */
	private $columns;
	/**
	 * @var bool
	 */
	private $isDeleted;
	/**
	 * @var float|int
	 */
	private $marginLeft;
	/**
	 * @var float|int
	 */
	private $columnWidth;
	/**
	 * @var int|float
	 */
	private $tableWidth;
	/**
	 * @var float|int
	 */
	private $pageLeft;


	/**
	 * @param int $columnCount
	 * @param int|float $rowHeight
	 * @param bool $hasBorder
	 * @param Color $borderColor
	 * @param int|float $marginLeft
	 * @param int|float $columnWidth
	 * @param int $dpi
	 */
	public function __construct($columnCount, $rowHeight, $hasBorder, $borderColor, $marginLeft, $columnWidth, $tableWidth, $dpi = 300) {

		$this->columnCount = $columnCount;
		$this->rowHeight = $rowHeight;
		$this->hasBorder = $hasBorder;
		$this->borderColor = $borderColor;
		$this->marginLeft = $marginLeft;
		$this->columnWidth = $columnWidth;
		$this->tableWidth = $tableWidth;
		$this->columns = array();
		$this->isDeleted = false;
		$this->pageLeft = 0;
		$this->dpi = $dpi;

		for($i = 0; $i < $columnCount; $i++) {

			$this->columns[] = new TableColumn($columnWidth, $rowHeight, $hasBorder, $borderColor, $dpi);
		}
	}

	/**
	 * @return boolean
	 */
	public function isIsDeleted()
	{
		return $this->isDeleted;
	}

	/**
	 * @param boolean $isDeleted
	 */
	public function setIsDeleted($isDeleted)
	{
		$this->isDeleted = $isDeleted;
	}

	/**
	 * @return mixed
	 */
	public function getRowHeight()
	{
		return $this->rowHeight;
	}

	/**
	 * @param int $columnNumber
	 * @return array|TableColumn[]
	 * @throws PDFGeneratorException
	 */
	public function getColumn($columnNumber = 0) {

		if(isset($this->columns[$columnNumber])) {
			return $this->columns;
		}else{
			throw new PDFGeneratorException(PDFGeneratorException::EXCEPTION_TYPE_TABLE_COLUMN_NOT_FOUND);
		}
	}

	/**
	 * @param float|int $pageLeft
	 */
	public function setPageLeft($pageLeft)
	{
		$this->pageLeft = $pageLeft;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$rowString = '';

		$borderEnd = $this->pageLeft - $this->rowHeight;
		$sectionWidth = $this->marginLeft + ($this->columnWidth * $this->columnCount);

		if($this->hasBorder) {

			/* left border */
			$rowString .= $this->borderColor->__toString() . ' ' . $this->marginLeft . ' ' . $this->pageLeft .
				' m ' . $this->marginLeft . ' ' . $borderEnd . ' l h S' . "\n";

			/* bottom border */
			$rowString .= $this->borderColor->__toString() . ' ' . $this->marginLeft . ' ' . $borderEnd .
				' m ' . $sectionWidth . ' ' . $borderEnd . ' l h S' . "\n";
		}

		foreach($this->columns as $column) {
			$rowString .= $column->__toString();
		}

		return $rowString;
	}
}

/**
 * Class TableColumn
 * @package IO\PDFGenerator\Data
 */
class TableColumn {

	/**
	 * @var int|float
	 */
	private $columnWidth;
	/**
	 * @var int|float
	 */
	private $columnHeight;
	/**
	 * @var Color
	 */
	private $columnBackgroundColor;
	/**
	 * @var bool
	 */
	private $hasBorder;
	/**
	 * @var Color
	 */
	private $borderColor;
	/**
	 * @var Text
	 */
	private $text;
	/**
	 * @var int|float
	 */
	private $pageLeft;
	/**
	 * @var int|float
	 */
	private $columnStartX;
	/**
	 * @var int
	 */
	private $dpi;
	/**
	 * @var int|float
	 */
	private $paddingLeft;
	/**
	 * @var int|float
	 */
	private $paddingRight;

	/**
	 * @param int|float $columnWidth
	 * @param int|float $columnHeight
	 * @param bool $hasBorder
	 * @param Color $borderColor
	 * @param int $dpi
	 */
	public function __construct($columnWidth, $columnHeight, $hasBorder, $borderColor, $dpi = 300) {

		$this->columnWidth = $columnWidth;
		$this->columnHeight = $columnHeight;
		$this->hasBorder = $hasBorder;
		$this->borderColor = $borderColor;
		$this->columnBackgroundColor = Color::WhiteColor();
		$this->text = null;
		$this->dpi = $dpi;
		$this->paddingLeft = 0;
		$this->paddingRight = 0;
	}

	/**
	 * @param int|float $pageLeft
	 * @param int|float $columnStartX
	 */
	public function setPageLeftAndColumnStartX($pageLeft, $columnStartX) {

		$this->pageLeft = $pageLeft;
		$this->columnStartX = $columnStartX;
	}

	/**
	 * @param float|int $paddingLeft
	 */
	public function setPaddingLeft($paddingLeft)
	{
		$this->paddingLeft = $paddingLeft;
	}

	/**
	 * @param float|int $paddingRight
	 */
	public function setPaddingRight($paddingRight)
	{
		$this->paddingRight = $paddingRight;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$columnString = '';

		/* column background */
		$columnString .= $this->columnBackgroundColor->__toString() . ' '
						. $this->columnStartX . ' ' . $this->pageLeft . ' ' . $this->columnWidth .
						' ' . $this->columnHeight . ' re f' . "\n";

		$borderEndX = $this->columnStartX + $this->columnWidth;
		$borderEndY = $this->pageLeft - $this->columnHeight;

		if($this->hasBorder) {

			/* right border */
			$columnString .= $this->borderColor->__toString() . ' ' . $borderEndX . ' ' . $this->pageLeft .
				' m ' . $borderEndX . ' ' . $borderEndY . ' l h S' . "\n";
		}

		if(!is_null($this->text)) {

			$this->text->setPosition($this->columnStartX, $this->paddingLeft, $this->columnWidth, $this->columnHeight, $this->dpi);
			$columnString .= $this->text->__toString();
		}

		return $columnString;
	}
}