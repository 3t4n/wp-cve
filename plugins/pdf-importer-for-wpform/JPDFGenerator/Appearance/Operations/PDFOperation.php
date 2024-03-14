<?php


namespace rnpdfimporter\JPDFGenerator\Appearance\Operations;


use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;
use rnpdfimporter\JPDFGenerator\JSONItem\JSONItemBase;
use rnpdfimporter\PDFLib\core\operators\PDFOperator;

class PDFOperation
{

    public $Name;
    /** @var [] */
    public $Args;
    /**
     * PDFOperation constructor.
     */
    public function __construct($name,$args=null)
    {
        $this->Name=$name;
        if($args==null)
            $args=[];

        if(!is_array($args))
            $args=[$args];
        $this->Args=$args;
        if($this->Args==null)
            $this->Args=[];
    }

    public static function BegintMarkedContent($tag)
    {
        return new PDFOperation('BMC',$tag);
    }

    public static function EndMarkedContent()
    {
        return new PDFOperation('EMC');
    }

    public static function DrawObject($imageName)
    {
        return new PDFOperation('Do',$imageName);
    }

    public function ToText(){
        $value='';
        foreach($this->Args as $currentArg)
        {
            $value.=strval($currentArg).' ';
        }

        $value.= $this->Name;
        return $value;
    }

    public static function PushGraphicsState(){
        return new PDFOperation('q');
    }



    public static function PushGraphics()
    {
        return new PDFOperation('q');
    }

    public static function SetGraphicsState($state)
    {
        return new PDFOperation('gs',$state);
    }

    public static function SetFillColor($color)
    {
        switch($color['type'])
        {
            case 'Grayscale':
                return new PDFOperation('g',[$color['gray']]);
            case 'RGB':
                return new PDFOperation('rg',[$color['r'],$color['g'],$color['b']]);
            case 'CMYK':
                return new PDFOperation('k',[$color['c'],$color['m'],$color['y'],$color['k']]);

        }
    }

    public static function Scale($xPos,$yPos)
    {
        return PDFOperation::ConcatenateTransformationMatrix($xPos,0,0,$yPos,0,0);
    }
    public static function RotateRadians($angle)
    {
        return PDFOperation::ConcatenateTransformationMatrix(
            cos($angle),
            sin($angle),
            -sin($angle),
            cos($angle),
            0,
            0
        );
    }
    public static function Translate($xPos,$yPos)
    {
        return PDFOperation::ConcatenateTransformationMatrix(1,0,0,1,$xPos,$yPos);
    }

    private static function ConcatenateTransformationMatrix($a,$b,$c,$d,$e,$f)
    {
        return new PDFOperation('cm',[$a,$b,$c,$d,$e,$f]);
    }
    public static function SetStrokingColor($color)
    {
        switch($color['type'])
        {
            case 'Grayscale':
                return new PDFOperation('G',[$color['gray']]);
            case 'RGB':
                return new PDFOperation('RG',[$color['r'],$color['g'],$color['b']]);
            case 'CMYK':
                return new PDFOperation('K',[$color['c'],$color['m'],$color['y'],$color['k']]);

        }
    }

    public static function PopGraphicsState()
    {
        return new PDFOperation('Q');
    }

    public static function BeginText()
    {
        return new PDFOperation('BT');
    }

    public static function FillAndStroke(){
        return new PDFOperation('B');
    }


    public static function Fill(){
        return new PDFOperation('f');
    }

    public static function Stroke(){
        return new PDFOperation('S');
    }

    public static function ClosePath()
    {
        return new PDFOperation('h');
    }

    public static function MoveTo($xPos,$yPos)
    {
        return new PDFOperation('m',[$xPos,$yPos]);
    }
    public static function ShowText($text)
    {
        return new PDFOperation('TJ',$text);
    }

    public static function EndText()
    {
        return new PDFOperation('ET');
    }

    public static function SetFontAndSize($name,$size)
    {
        if($name[0]!='/')
            $name='/'.$name;
        return new PDFOperation('Tf',[$name,$size]);
    }

    public static function SkewRadians($xSkewAngle,$ySkewAngle)
    {
        return self::ConcatenateTransformationMatrix(1,tan($xSkewAngle),tan($ySkewAngle),1,0,0);
    }

    public static function RotateAndSkewTextRadiansAndTranslate($rotationAngle,$xSkewAngle,$ySkewAngle,$x,$y)
    {
        return self::SetTextMatrix(
            cos($rotationAngle),
            sin($rotationAngle)+tan($xSkewAngle),
            -sin($rotationAngle)+tan($ySkewAngle),
            cos($rotationAngle),$x,$y
        );
    }

    public static function Degrees($degreeAngle)
    {
        return array(
            'type'=>'degrees',
            'angle'=>$degreeAngle
        );
    }
    public static function SetTextMatrix($a,$b,$c,$d,$e,$f)
    {
        return new PDFOperation('Tm',array($a,$b,$c,$d,$e,$f));
    }

    public static function LineTo($xPos,$yPos)
    {
        return new PDFOperation('l',[$xPos,$yPos]);
    }

    public static function SetLineWidth($width)
    {
        return new PDFOperation('w',$width);
    }

    public static function SetLineCap($style)
    {
        return new PDFOperation('J');
    }

    public static function DegreesToRadians($degree)
    {
        return ($degree*pi())/180;
    }
    public static function ToRadians($rotation)
    {
        if($rotation['type']=='radians')
            return $rotation['angle'];

        return PDFOperation::DegreesToRadians($rotation['angle']);
    }

    public static function SetDashPattern($dashArray,$dashPhase)
    {
        return new PDFOperation('d',['['.implode(',',$dashArray).']',$dashPhase]);
    }
}