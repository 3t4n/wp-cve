<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:48 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters;


use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\PDFFieldBase;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\PDFFormItem;

abstract class PHPFormatterBase
{
    /** @var PDFFieldBase */
    public $TemplateField;
    public function __construct($templateField=null)
    {
        $this->TemplateField=$templateField;
    }

    public  abstract function __toString();
    public abstract function IsEmpty();
    public abstract function ToText();
}