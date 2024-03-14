<?php


namespace rnpdfimporter\JPDFGenerator\Appearance\Font;


use rnpdfimporter\JPDFGenerator\JPDFGenerator;
use rnpdfimporter\JPDFGenerator\Utils\ObjectUtils;

class Font
{
    /** @var JPDFGenerator */
    public $Generator;
    public $FontName;
    public $ObjectNumber;
    public function __construct($generator,$name)
    {
        $this->Generator=$generator;
        $this->FontName=$name;
        $this->ObjectNumber=$this->Generator->GetNextIndirectObjectNumber();
    }

    public function WidthOfTextAtSize($text, $fontSize)
    {
        $this->Generator->CPDF->selectFont($this->FontName);
        return $this->Generator->CPDF->getTextWidth($fontSize,$text);

    }

    public function GetName(){
        return $this->FontName;
    }



    public function HeightAtSize($fontSize,$descender=true)
    {
       $this->Generator->CPDF->selectFont($this->FontName);
       return $this->Generator->CPDF->getFontHeight($fontSize);





    }

    public function EncodeText($text)
    {
        $this->Generator->CPDF->selectFont($this->FontName);
        $this->Generator->CPDF->registerText($this->FontName,$text);
        $text=$this->Generator->CPDF->filterText($text,false);
        return "[($text)]";
    }

    public function GetRef()
    {
        $font=$this->Generator->CPDF->GetFontByName($this->FontName);
        $number=0;
        if($font!=null)
            $number=$font['fontObject'];
        return $number.' 0 R';
    }

}