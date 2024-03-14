<?php


namespace rnpdfimporter\JPDFGenerator\Appearance;


use rnpdfimporter\JPDFGenerator\Appearance\Operations\PDFOperation;
use rnpdfimporter\JPDFGenerator\JSONItem\ArrayJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;
use rnpdfimporter\PDFLib\core\objects\PDFNumber;
use rnpdfimporter\Utilities\Sanitizer;

abstract class AppearanceBase
{

    /** @var DictionaryObjectItem */
    public $Item;
    /**
     * AppearanceBase constructor.
     * @param $item DictionaryObjectItem
     */
    public function __construct($item)
    {
        $this->Item=$item;
    }


    /**
     * @param DictionaryObjectItem
     * @return array|null
     */
    public function GetDefaultColor($dic)
    {
        $defaultAppearance=$dic->GetRealValue('/DA');
        if($defaultAppearance==null)
            $defaultAppearance='';
        $matches=array();
        preg_match_all('/(\d*\.\d+|\d+)[\0\t\n\f\r\ ]*(\d*\.\d+|\d+)?[\0\t\n\f\r\ ]*(\d*\.\d+|\d+)?[\0\t\n\f\r\ ]*(\d*\.\d+|\d+)?[\0\t\n\f\r\ ]+(g|rg|k)/',$defaultAppearance,$matches,PREG_SET_ORDER);
        $match=[];
        if($matches!=null&&count($matches)>0)
            $match=array_pop($matches);

        $match=array_pad($match,6,null);
        $c1=$match[1];
        $c2=$match[2];
        $c3=$match[3];
        $c4=$match[4];
        $colorSpace=$match[5];


        if($colorSpace=='g'&&$c1!=='')
        {
            return array(
                "type"=>'Grayscale',
                'gray'=>$c1
            );
        }

        if($colorSpace==='rg'&&$c1!==''&&$c2!==''&&$c3!=='')
        {
            return array(
                'type'=>'RGB',
                'r'=>$c1,
                'g'=>$c2,
                'b'=>$c3
            );
        }

        if($colorSpace==='k'&&$c1!==''&&$c2!==''&&$c3!=='')
        {
            return array(
                'type'=>'cmyk',
                'c'=>$c1,
                'm'=>$c2,
                'y'=>$c3,
                'k'=>$c4
            );
        }

        return null;

    }

    /**
     * @param DictionaryObjectItem
     * @return array|null
     */
    public function GetDefaultFontSize($dic)
    {
        $defaultAppearance=$dic->GetRealValue('/DA');
        if($defaultAppearance==null)
            $defaultAppearance='';
        $matches=array();
        preg_match_all('/\/([^\0\t\n\f\r\ ]+)[\0\t\n\f\r\ ]+(\d*\.\d+|\d+)[\0\t\n\f\r\ ]+Tf/',$defaultAppearance,$matches,PREG_SET_ORDER);
        $match=[];
        if($matches!=null&&count($matches)>0)
            $match=array_pop($matches);

        if(isset($match[2]))
            return $match[2];
        return null;


    }

    /**
     * @param $dic DictionaryObjectItem
     */
    public function GetRectangle($dic)
    {
        $rect=$dic->GetValue('/Rect');
        if($rect==null||!$rect instanceof ArrayJSONItem||count($rect->Items)!=4)
            return array(
                'x'=>0,
                'y'=>0,
                'width'=>0,
                'height'=>0
            );

        $lowerLeftX=floatval($rect->Items[0]->GetText(0));
        $lowerLeftY=floatval($rect->Items[1]->GetText(0));
        $upperRightX=floatval($rect->Items[2]->GetText(0));
        $upperRightY=floatval($rect->Items[3]->GetText(0));

        $x=$lowerLeftX;
        $y=$lowerLeftY;
        $width=$upperRightX-$lowerLeftX;
        $height=$upperRightY-$lowerLeftY;

        return array(
            'x'=>$x,
            'y'=>$y,
            'width'=>$width,
            'height'=>$height
        );




    }

    protected function Degrees($degree)
    {
        return array(
            'type'=>'degrees',
            'angle'=>$degree);
    }


    protected function RotateInPlace($width,$height,$rotation)
    {
        if($rotation==0)
        {
            return [
                $this->Translate(0,0),
                $this->RotateDegrees(0)
            ];

        }

        if($rotation==90)
        {
            return [
                $this->Translate($width,0),
                $this->RotateDegrees(90)
            ];

        }

        if($rotation==180)
        {
            return [
                $this->Translate($width,$height),
                $this->RotateDegrees(180)
            ];

        }

        if($rotation==270)
        {
            return [
                $this->Translate(0,$height),
                $this->RotateDegrees(270)
            ];

        }

        return [];
    }

    protected function RotateRadians($angle)
    {
        return $this->ConcatenateTransformationMatrix(
            cos($angle),
            sin($angle),
            -sin($angle),
            cos($angle),
            0,
            0
        );
    }

    protected function DegreeInRadians($degree)
    {
        return ($degree*pi())/100;
    }

    protected function RotateDegrees($angle)
    {
        return $this->RotateRadians($this->DegreeInRadians($angle));
    }
    protected function Translate($xPos,$yPos)
    {
        return $this->ConcatenateTransformationMatrix(1,0,0,1,$xPos,$yPos);
    }

    protected function ConcatenateTransformationMatrix($a,$b,$c,$d,$e,$f)
    {
        return new PDFOperation('cm',[$a,$b,$c,$d,$e,$f]);
    }

    protected function AdjustDimensionForRotation($rectancle,$rotation)
    {
        if($rotation===90||$rotation===270)
            return array(
                'width'=>$rectancle['height'],
                'height'=>$rectancle['width']
            );

        return array(
            'width'=>$rectancle['width'],
            'height'=>$rectancle['height']
        );

    }

    /**
     * @param $ap DictionaryObjectItem
     */
    protected function GetRotation($ap)
    {
        if($ap==null)
            return 0;

        $quadrants = ($ap->GetNumberValue('/R',0) / 90) % 4;
        if ($quadrants === 0) return 0;
        if ($quadrants === 1) return 90;
        if ($quadrants === 2) return 180;
        if ($quadrants === 3) return 270;
        return 0;

    }


    /**
     * @param $Item DictionaryObjectItem
     */
    protected function GetAlignment( $Item)
    {
        $q=$Item->GetRealValue('/Q');
        if($q==null)
            return 0;
        $q=Sanitizer::GetStringValueFromPath($q,['data','Text'],'0');
        switch ($q)
        {
            case 0:
                return 0;
            case 1:
                return 1;
            case 2:
                return 2;
            default:
                return 0;

        }

    }


    /**
     * @param $Item DictionaryObjectItem
     * @return null
     */
    public function GetFont( $Item)
    {
        return $this->Item->Generator->GetDefaultFont();
    }


    /**
     * @param $Item DictionaryObjectItem
     * @return null
     */
    public function GetBorderColor($Item,$scale=1)
    {
        /** @var ArrayJSONItem $bc */
        if($Item==null)
            return null;
        $bc=$Item->GetValue('/BC');
        if($bc==null)
            return null;

        switch(count($bc->Items))
        {
            case 1:
                return array(
                    'type'=>'Grayscale',
                    'gray'=>$bc->Items[0]->GetText(0)*$scale
                );
                break;
            case 3:
                return array(
                    'type'=>'RGB',
                    'r'=>$bc->Items[0]->GetText(0)*$scale,
                    'g'=>$bc->Items[1]->GetText(0)*$scale,
                    'b'=>$bc->Items[2]->GetText(0)*$scale,
                );
                break;
            case 4:
                return array(
                    'type'=>'CMYK',
                    'c'=>$bc->Items[0]->GetText(0)*$scale,
                    'm'=>$bc->Items[1]->GetText(0)*$scale,
                    'y'=>$bc->Items[2]->GetText(0)*$scale,
                    'k'=>$bc->Items[3]->GetText(0)*$scale
                );
                break;
        }

        return null;
    }


    public function GetBackgroundColor($Item,$scale=1)
    {
        /** @var ArrayJSONItem $bc */
        if($Item==null)
            return null;
        $bc=$Item->GetValue('/BG');
        if($bc==null)
            return null;

        switch(count($bc->Items))
        {
            case 1:
                return array(
                    'type'=>'Grayscale',
                    'gray'=>$bc->Items[0]->GetText(0)*$scale
                );
                break;
            case 3:
                return array(
                    'type'=>'RGB',
                    'r'=>$bc->Items[0]->GetText(0)*$scale,
                    'g'=>$bc->Items[1]->GetText(0)*$scale,
                    'b'=>$bc->Items[2]->GetText(0)*$scale,
                );
                break;
            case 4:
                return array(
                    'type'=>'CMYK',
                    'c'=>$bc->Items[0]->GetText(0)*$scale,
                    'm'=>$bc->Items[1]->GetText(0)*$scale,
                    'y'=>$bc->Items[2]->GetText(0)*$scale,
                    'k'=>$bc->Items[3]->GetText(0)*$scale
                );
                break;
        }

        return null;
    }


    public abstract function Generate();
}