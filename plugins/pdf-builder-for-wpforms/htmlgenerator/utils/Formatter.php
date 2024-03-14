<?php


namespace rednaoformpdfbuilder\htmlgenerator\utils;


use rednaoformpdfbuilder\DTO\CurrencySettings;
use rednaoformpdfbuilder\DTO\PDFDocumentOptions;

class Formatter
{
    /** @var CurrencySettings */
    public $CurrencySettings;
    /** @var PDFDocumentOptions */
    public $Options;

    /**
     * Formatter constructor.
     * @param $options PDFDocumentOptions
     */
    public function __construct($options)
    {
        if($options!=null&&isset($options->DocumentSettings->CurrencySettings))
            $this->CurrencySettings=$options->DocumentSettings->CurrencySettings;
        else{
            $this->CurrencySettings=new CurrencySettings();
            $this->CurrencySettings->DecimalSeparator='.';
            $this->CurrencySettings->NumberOfDecimals=2;
            $this->CurrencySettings->Position='left';
            $this->CurrencySettings->Symbol='$';
            $this->CurrencySettings->ThousandSeparator='.';
        }
    }


    public function FormatCurrency($value)
    {
       $formatted=$this->FormatNumber($value);

        switch($this->CurrencySettings->Position)
        {
            case 'left':
                return $this->CurrencySettings->Symbol.$formatted;
            case 'right':
                return $formatted.$this->CurrencySettings->Symbol;
            case 'left_space':
                return $this->CurrencySettings->Symbol.' '.$formatted;
            case 'right_space':
                return $formatted.' '.$this->CurrencySettings->Symbol;
        }

    }

    public function FormatNumber($value)
    {
        if(!isset($value)||$value=='')
            $value=0;

        return \number_format(\floatval($value),$this->CurrencySettings->NumberOfDecimals,$this->CurrencySettings->DecimalSeparator,
            $this->CurrencySettings->ThousandSeparator);
    }

}