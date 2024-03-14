<?php
namespace rednaoformpdfbuilder\DTO;
class PDFControlBaseOptions{
	 public $Id;
	 public $Type;
	 public $TargetId;
	 /** @var PDFControlBaseStyles */
	 public $Styles;
}


class PDFControlBaseStyles{
	 public $Top;
	 public $Left;
	 public $Width;
	 public $Height;
}


