<?php
namespace rednaoformpdfbuilder\DTO;
class FieldSummaryItemOptions{
	 public $Id;
	 public $Label;
}


class FieldSummaryOptions{
	 public $Type;
	 /** @var FieldSummaryItemOptions[] */
	 public $Fields;
}


