<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 9:27 AM
 */

namespace rnwcinv\htmlgenerator;


class DocumentOptionsDTO
{
    public $attachTo;
    /** @var ContainerOptionsDTO */
    public $containerOptions;
    public $name;
    public $pageId;
    public $pageType;
    public $myAccountDownload;
    /** @var PageOptionsDTO[] */
    public $pages;
    public $extensions;
    public $invoiceTemplateId;
    public $conditions;

}

class InvoiceNumberFormatDTO{
    public $type;
    public $prefix;
    public $sufix;
    public $digits;
}

class ContainerOptionsDTO{
    public $PDFFileName;
    public $orientation;
    /** @var PageSizeDTO */
    public $pageSize;
    public $styles;
    /** @var InvoiceNumberFormatDTO */
    public $InvoiceNumberFormat;
    /** @var FieldDTO[] */
    public $fieldOptions;
    public $hideFooter;
    public $hideHeader;
    public $showRepeatableHeader;
    public $showRepeatableFooter;

    public $RepeatableHeader;
    public $RepeatableFooter;
    public $RepeatableFooterField;
    public $RepeatableHeaderField;

}

class PageSizeDTO{
    public $type;
    public $width;
    public $height;
}


class PageOptionsDTO{
    public $footerOptions;
    public $contentOptions;
    public $headerOptions;
    /** @var FieldDTO[]  */
    public $fields;
}


class ContainerBaseDTO{
    public $height;
    public $width;
    public $position;
}

class FooterOptions extends ContainerBaseDTO{

}

class HeaderOptions extends ContainerBaseDTO{

}

class ContentOptionsDTO extends ContainerBaseDTO {

}


class FieldDTO{
    public $targetId;
    public $type;
    public $fieldID;
    /** @var FieldOptionsDTO */
    public $fieldOptions;
    public $styles;
}

class FieldOptionsDTO{
    public $fieldType;
    public $labelPosition;
}

