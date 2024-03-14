<?php
namespace rednaoformpdfbuilder\DTO;
class FieldControlWrapper{
	 /** @var FieldControlOptions */
	 public $Field;
}


class ColumnItemOptions{
	 /** @var PDFControlBaseOptions[] */
	 public $Fields;
	 public $Width;
}


class RowItemOptions{
	 /** @var ColumnItemOptions[] */
	 public $Columns;
}


class TableItemOptions{
	 /** @var RowItemOptions[] */
	 public $Rows;
}


class TableControlOptions{
	 /** @var TableItemOptions */
	 public $TableItem;
}


