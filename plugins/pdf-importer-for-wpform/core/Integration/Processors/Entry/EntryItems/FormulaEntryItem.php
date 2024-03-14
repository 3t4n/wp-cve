<?php


namespace rnpdfimporter\core\Integration\Processors\Entry\EntryItems;


use Exception;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\PHPFormatterBase;
use rnpdfimporter\pr\core\Parser\Elements\ParseMain;

class FormulaEntryItem extends EntryItemBase
{

    /** @var ParseMain */
    public $Parser;
    public function __construct($parser)
    {
        parent::__construct();
        $this->Parser=$parser;

    }

    public function GetText()
    {
        return $this->Parser->ParseText();
    }

    protected function InternalGetObjectToSave()
    {
        throw new Exception('Method not implemented');
    }

    public function InitializeWithOptions($field, $options)
    {
        throw new Exception('Method not implemented');
    }

    public function GetHtml($style = 'standard')
    {
        return $this->GetText();
    }
}