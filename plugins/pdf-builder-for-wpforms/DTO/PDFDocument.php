<?php
namespace rednaoformpdfbuilder\DTO;
class PageOptions{
	 /** @var PDFControlBaseOptions[] */
	 public $Fields;
	 /** @var SectionOption */
	 public $HeaderSection;
	 /** @var SectionOption */
	 public $FooterSection;
	 /** @var SectionOption */
	 public $ContentSection;
}


class SectionOption{
	 public $Type;
	 public $Height;
}


class PDFDocumentOptions{
	 public $Id;
	 /** @var PageOptions[] */
	 public $Pages;
	 public $Styles;
	 /** @var DocumentSettings */
	 public $DocumentSettings;
	 public $FormId;
	 public $Name;
}


class CurrencySettings{
	 public $Symbol;
	 public $Position;
	 public $ThousandSeparator;
	 public $DecimalSeparator;
	 public $NumberOfDecimals;
}


class DocumentSettings{
	 public $Width;
	 public $Height;
	 /** @var boolean */
	 public $CreatedFromTemplate;
	 public $PageType;
	 public $BackgroundImageId;
     public $ImageCompression;
	 public $BackgroundImageURL;
	 /** @var boolean */
	 public $EnableMultiplePage;
	 public $BackgroundStyle;
	 /** @var boolean */
	 public $HideFooter;
	 /** @var boolean */
	 public $HideHeader;
	 /** @var 'landscape'|'portrait' */
	 public $Orientation;
	 public $FileName;
	 public $BaseStyles;
	 /** @var CurrencySettings */
	 public $CurrencySettings;
	 public $FormattedFileName;
     public $ShowRepeatableHeader;
     public $ShowRepeatableFooter;
     public $RepeatableHeader;
     public $RepeatableFooter;
     public $RepeatableFooterField;
     public $RepeatableHeaderField;
}


