<?php
namespace rnpdfimporter\DTO;
class FieldSettingsOptions{
	 public $Id;
	 public $MappedTo;
	 /** @var string[] */
	 public $CheckValues;
}


class DocumentManagerOptions{
	 public $FormId;
	 public $TemplateName;
	 public $Id;
	 public $PDFURL;
	 public $PDFName;
	 /** @var FieldSettingsOptions[] */
	 public $FieldSettings;
}


