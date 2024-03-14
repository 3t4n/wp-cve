<?php


namespace rnpdfimporter\JPDFGenerator\Appearance;


use rnpdfimporter\JPDFGenerator\Appearance\Font\Font;
use rnpdfimporter\JPDFGenerator\Appearance\Layout\CombedTextLayout;
use rnpdfimporter\JPDFGenerator\Appearance\Layout\MultipleLineTextLayout;
use rnpdfimporter\JPDFGenerator\Appearance\Layout\SingleLineTextLayout;
use rnpdfimporter\JPDFGenerator\Appearance\Operations\PDFOperation;
use rnpdfimporter\JPDFGenerator\Appearance\Operations\TextPosition;
use rnpdfimporter\JPDFGenerator\JSONItem\ArrayJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\DictionaryObjectItem;
use rnpdfimporter\JPDFGenerator\JSONItem\IndirectObjectJsonItem;
use rnpdfimporter\JPDFGenerator\JSONItem\JSONFactory;
use rnpdfimporter\JPDFGenerator\JSONItem\RawStringJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\Streams\ContentStreamJSONItem;
use rnpdfimporter\JPDFGenerator\JSONItem\StringJSONItem;
use rnpdfimporter\Lib\TextProcessor\TextProcessor;

class DefaultTextAppearance extends AppearanceBase
{

    public function Generate($text=null)
    {
        if(preg_match("/([\x{0590}-\x{06FF}\x{0700}-\x{085F}\x{FB00}-\x{FDFD}\x{FE70}-\x{FEFF}])/u", $text))
            $text=TextProcessor::GetInstance()->Process($text);

        $widgetColor=$this->GetDefaultColor($this->Item);
        $fieldColor=$widgetColor;
        $widgetFontSize=$this->GetDefaultFontSize($this->Item);
        $fieldFontSize=$this->GetDefaultFontSize($this->Item);

        $rectangle=$this->GetRectangle($this->Item);
        $ap=$this->Item->GetValue('/MK');
        /** @var DictionaryObjectItem $bs */
        $bs=$this->Item->GetValue('/BS');

        $borderWidth=1;
        if($bs!=null)
            $borderWidth=$bs->GetNumberValue('/W',1);
        $rotation=$this->GetRotation($ap);
        $aux=$this->AdjustDimensionForRotation($rectangle,$rotation);
        $width=$aux['width'];
        $height=$aux['height'];
        $rotate=$this->RotateInPlace($rectangle['width'],$rectangle['height'],$rotation);
        /** @var Font $font */
        $font=$this->Item->Generator->GetDefaultFont();

        $black=array(
            'type'=>'RGB',
            'r'=>0,
            'g'=>0,
            'b'=>0
        );


        $borderColor=$this->GetBorderColor($ap);

        $normalBackgroundColor=$this->GetBackgroundColor($ap);
        /** @var TextPosition[] $textLines */
        $textLines=[];
        $fontSize=0;
        $padding=$this->IsCombed()?0:1;
        $bounds=array(
            'x'=>$borderWidth+$padding,
            'y'=>$borderWidth+$padding,
            'width'=>$width-($borderWidth+$padding)*2,
            'height'=>$height-($borderWidth+$padding)*2
        );



        if($this->HasFlag(12))
            $layout=new MultipleLineTextLayout();
        else if($this->IsCombed())
            $layout=new CombedTextLayout($this->GetMaxLength()!=null?$this->GetMaxLength():0);
        else
            $layout=new SingleLineTextLayout();
        $layout=$layout->Generate($text,$this->GetAlignment($this->Item),$widgetFontSize,
            $font,$bounds);

        $textLines=null;
        if(is_array($layout->line))
            $textLines=$layout->line;
        else
            $textLines=[$layout->line];
        $fontSize=$layout->fontSize;

        $textColor=$widgetColor;
        if($widgetColor==null)
            $textColor=array(
                'type'=>'RGB',
                'r'=>0,
                'g'=>0,
                'b'=>0
            );

        $da=
            "(".(new PDFOperation('Tf',[$font!=null?'/'.$font->GetName():null,$widgetFontSize]))->ToText().' '.
            PDFOperation::SetFillColor($widgetColor==null?$black:$widgetColor)->ToText().')';


        $this->Item->SetValue('/DA',RawStringJSONItem::CreateFromText($this->Item->Generator,$this->Item->Parent,$da));

        $options=array(
            "x"=>0+($borderWidth==null?0: $borderWidth/2),
            "y"=>0+($borderWidth==null?0: $borderWidth/2),
            'width'=>$width-$borderWidth,
            'height'=>$height-$borderWidth,
            'borderWidth'=>$borderWidth==null?0:$borderWidth,
            'borderColor'=>$borderColor,
            'textColor'=>$textColor,
            "font"=>$font->GetName(),
            'fontSize'=>$fontSize,
            'color'=>$normalBackgroundColor,
            'textLines'=>$textLines,
            'padding'=>$padding
        );

        $operations= array_merge($rotate,$this->DrawTextField($options));
        $this->CreateAppearanceStream($font,$operations);

        //todo: check that comb flag works and multiline;
    }

    public function GetMaxLength()
    {
        $maxLength=$this->Item->GetValue('/MaxLen');
        if($maxLength==null)
            return null;

        $text=$maxLength->GetText(0);
        if(!is_numeric($text))
            return null;

        if(floatval($text)==0)
            return null;


        return floatval($text);
    }
    public function IsCombed()
    {
        return $this->HasFlag(24)&&//iscombed
            !$this->HasFlag(12)&& //is not multiline
            !$this->HasFlag(13)&&//is not password
            !$this->HasFlag(20)&&//is not file selector
            $this->GetMaxLength()!=null;
    }

    public function HasFlag($index){
        $flag=1<<$index;

        $fieldFlags=$this->Item->GetNumberValue('/Ff',0);
        return $fieldFlags&$flag;
    }
    public function DrawTextField($options)
    {
        $x=floatval($options['x']);
        $y=floatval($options['y']);
        $width=floatval($options['width']);
        $height=floatval($options['height']);
        $borderWidth=floatval($options['borderWidth']);
        $padding=floatval($options['padding']);


        $clipX=$x+$borderWidth/2+$padding;
        $clipY=$y+$borderWidth/2+$padding;
        $clipWidth=$width-($borderWidth/2+$padding)*2;
        $clipHeight=$height-($borderWidth/2+$padding)*2;

        $clippingArea=[
            new PDFOperation('m',[$clipX,$clipY]),
            new PDFOperation('l',[$clipX,$clipY+$clipHeight]),
            new PDFOperation('l',[$clipX+$clipWidth,$clipY+$clipHeight]),
            new PDFOperation('l',[$clipX+$clipWidth,$clipY]),
            new PDFOperation('h'),
            new PDFOperation('W'),
            new PDFOperation('n')

        ];

        $backGround=$this->DrawRectangle(array(
           'x'=>$x,
           'y'=>$y,
           'width'=>$width,
           'height'=>$height,
           'borderWidth'=>$options['borderWidth'],
           'color'=>$options['color'],
           'borderColor'=>$options['borderColor'],
           'rotate'=>$this->Degrees(0),
            'xSkew'=>$this->Degrees(0),
            'ySkew'=>$this->Degrees(0)
        ));

        $lines=$this->DrawTextLines($options['textLines'],array(
           'color'=>$options['textColor'],
           'font'=>$options['font'],
            'size'=>$options['fontSize'],
            'rotate'=>$this->Degrees(0),
            'xSkew'=>$this->Degrees(0),
            'ySkew'=>$this->Degrees(0)
        ));

        $markedContent=array_merge(array(
            PDFOperation::BegintMarkedContent('/Tx'),
            PDFOperation::PushGraphicsState(),

        ),$lines,array(PDFOperation::PopGraphicsState(),PDFOperation::EndMarkedContent()));

        return array_merge(array(PDFOperation::PushGraphicsState()),$backGround,$clippingArea,$markedContent,array(PDFOperation::PopGraphicsState()));









    }



    public function DrawRectangle($options)
    {
        $op=array();
        $op[]=PDFOperation::PushGraphicsState();

        if(isset($options['graphicState']))
        {
            $op[]=PDFOperation::SetGraphicsState($options['graphicState']);
        }

        if(isset($options['color']))
        {
            $op[]=PDFOperation::SetFillColor($options['color']);
        }

        if(isset($options['borderColor']))
        {
            $op[]=PDFOperation::SetStrokingColor($options['borderColor']);
        }

        $op[]=PDFOperation::SetLineWidth($options['borderWidth']);
        if(isset($options['borderLineCap']))
            $op[]=PDFOperation::SetLineCap($options['borderLineCap']);

        $op[]=PDFOperation::SetDashPattern(isset($options['borderDashArray'])?$options['borderDashArray']:[]
            ,isset($options['borderDashPhase'])?$options['borderDashPhase']:0);

        $op[]=PDFOperation::Translate($options['x'],$options['y']);
        $op[]=PDFOperation::RotateRadians(PDFOperation::ToRadians($options['rotate']));
        $op[]=PDFOperation::SkewRadians(PDFOperation::ToRadians($options['xSkew']),PDFOperation::ToRadians($options['ySkew']));
        $op[]=PDFOperation::MoveTo(0,0);
        $op[]=PDFOperation::LineTo(0,$options['height']);
        $op[]=PDFOperation::LineTo($options['width'],$options['height']);
        $op[]=PDFOperation::LineTo($options['width'],0);
        $op[]=PDFOperation::ClosePath();

        if(isset($options['color'])&&isset($options['borderWidth']))
        {
            $op[]=PDFOperation::FillAndStroke();
        }else{
            if(isset($options['color']))
            {
                $op[]=PDFOperation::Fill();
            }
            if(isset($options['borderColor']))
                $op[]=PDFOperation::Stroke();
        }


        $op[]=PDFOperation::PopGraphicsState();



        return $op;

    }

    private function DrawTextLines($textLines, $options)
    {
        $op=array();
        $op[]=PDFOperation::BeginText();
        $op[]=PDFOperation::SetFillColor($options['color']);
        $op[]=PDFOperation::SetFontAndSize($options['font'],$options['size']);

        for($i=0;$i<count($textLines);$i++)
        {
            $encoded=$textLines[$i]->encoded;
            $x=$textLines[$i]->x;
            $y=$textLines[$i]->y;

            $op[]=PDFOperation::RotateAndSkewTextRadiansAndTranslate(
                PDFOperation::ToRadians($options['rotate']),
                PDFOperation::ToRadians($options['xSkew']),
                PDFOperation::ToRadians($options['ySkew']),
                $x,
                $y
            );

            $op[]=PDFOperation::ShowText($encoded);
        }

        $op[]=PDFOperation::EndText();

        return $op;

    }

    /**
     * @param Font $font
     * @param array $operations
     */
    private function CreateAppearanceStream(Font $font, $operations)
    {
        $rectangle=$this->GetRectangle($this->Item);
        $fontName=$font->GetName();
        $resource=(object)array(
            '/Font'=>(object)array(
                '/'.$fontName=>$font->GetRef()
            )
        );

        $bbox=new ArrayJSONItem($this->Item->Generator,$this->Item->Parent,[]);
        $bbox->AddNumberItem(0,0,$rectangle['width'],$rectangle['height']);

        $matrix=new ArrayJSONItem($this->Item->Generator,$this->Item->Parent,[]);
        $matrix->AddNumberItem(1,0,0,1,0,0);

        $dic=array(
            '/Resources'=>$resource,
            '/BBox'=>$bbox,
            '/Matrix'=>$matrix
        );



        $cs=ContentStreamJSONItem::Create($this->Item->Generator,$this->Item,$operations,JSONFactory::ObjectToJsonItem($this->Item->Generator,
             $this->Item->Parent,array_merge(array(
            '/BBox'=>ArrayJSONItem::CreateWithNumbers($this->Item->Generator,$this->Item->Parent,0,0,0,0),
            '/Matrix'=>ArrayJSONItem::CreateWithNumbers($this->Item->Generator,$this->Item->Parent,1,0,0,1,0,0),
            '/Type'=>'/XObject',
            '/SubType'=>'/Form'
        )
            ,$dic
        )));

        $indirectObject=new IndirectObjectJsonItem($this->Item->Generator,null,$cs);

        $this->Item->Generator->InsertIndirectObject($indirectObject);

        $previousAppearance=$this->Item->GetValue('/AP');
        //$previousNormal=$previousAppearance->GetValue('/N');


        $this->Item->SetValue('/AP',JSONFactory::ObjectToJsonItem($this->Item->Generator,$this->Item,array(
            '/N'=>$indirectObject->GetObjectNumber().' 0 R'
        )));





    }


}