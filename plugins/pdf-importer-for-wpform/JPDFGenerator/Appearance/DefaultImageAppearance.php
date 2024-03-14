<?php


namespace rnpdfimporter\JPDFGenerator\Appearance;


use rnpdfimporter\JPDFGenerator\Appearance\Font\Font;
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

class DefaultImageAppearance extends AppearanceBase
{

    public function Generate($fileUrl=null)
    {
        if($fileUrl!=null)
            $fileUrl=explode("\n",$fileUrl)[0];

        $imageId=$this->InsertImage($fileUrl);
        if($imageId==null)
        {
            return;
            $this->Item->RemoveKey('/AP');
            $parent=$this->Item->Parent;
            if($parent instanceof IndirectObjectJsonItem)
                $this->Item->Generator->RemoveIndirectObject($parent->GetObjectNumber());
            return;
        }

        $rectangle=$this->GetRectangle($this->Item);
        $bs=$this->Item->GetValue('/BS');
        $ap=$this->Item->GetValue('/MK');

        $borderWidth=1;
        if($bs!=null)
            $borderWidth=$bs->GetNumberValue('/W',1);

        $rotation=$this->GetRotation($ap);
        $aux=$this->AdjustDimensionForRotation($rectangle,$rotation);

        $drawingArea=array(
          'x'=>0+$borderWidth,
          'y'=>0+$borderWidth,
          'width'=>$aux['width']-$borderWidth*2,
           'height'=>$aux['height']-$borderWidth*2
        );

        $options=array(
            'x'=>$drawingArea['x'],
            'y'=>$drawingArea['y'],
            'width'=>$drawingArea['width'],
            'height'=>$drawingArea['height'],
            'rotate'=>PDFOperation::Degrees(0),
            'xSkew'=>PDFOperation::Degrees(0),
            'ySkew'=>PDFOperation::Degrees(0)
        );

        $rotate=$this->RotateInPlace($rectangle['width'],$rectangle['height'],$rotation);
        $imageName=$imageId.'_image';
        $appearance=array_merge($rotate,$this->DrawImage($imageName,$options));

        $cs=ContentStreamJSONItem::Create($this->Item->Generator,$this->Item,$appearance,JSONFactory::ObjectToJsonItem($this->Item->Generator,
            $this->Item->Parent,array_merge(array(
                    '/BBox'=>'[0 0 '.$rectangle['width'].' '.$rectangle['height'].']',
                    '/Matrix'=>'[1 0 0 1 0 0]',
                    '/Type'=>'/XObject',
                    '/SubType'=>'/Form',
                    '/Resources'=>array(
                        '/XObject'=>array(
                            '/'.$imageName=>$imageId.' 0 R'
                        )
                    ),
                )
            )));


        $indirectObject=new IndirectObjectJsonItem($this->Item->Generator,null,$cs);

        $this->Item->Generator->InsertIndirectObject($indirectObject);

        $previousAppearance=$this->Item->GetValue('/AP');
        //$previousNormal=$previousAppearance->GetValue('/N');


        $this->Item->SetValue('/AP',JSONFactory::ObjectToJsonItem($this->Item->Generator,$this->Item,array(
            '/N'=>$indirectObject->GetObjectNumber().' 0 R'
        )));



    }

    public function HasFlag($index){
        $flag=1<<$index;

        $fieldFlags=$this->Item->GetNumberValue('/Ff',0);
        return $fieldFlags&$flag;
    }
    public function DrawButton($options)
    {
        $x=floatval($options['x']);
        $y=floatval($options['y']);
        $width=floatval($options['width']);
        $height=floatval($options['height']);
        $borderWidth=floatval($options['borderWidth']);

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



        return array_merge(array(PDFOperation::PushGraphicsState()),$backGround,array(PDFOperation::PopGraphicsState()));









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

    private function InsertImage($fileUrl)
    {
        if($fileUrl=='')
            return null;


        $upload=wp_upload_dir();
        $url=$upload['baseurl'];

        if(strpos($fileUrl,$url)===0)
        {
            $relativeURL=str_replace($url,'',$fileUrl);
            $relativePath=parse_url($relativeURL);

            if($relativePath==null||!isset($relativePath['path']))
                return null;

            $absolutePath=realpath($upload['basedir'].DIRECTORY_SEPARATOR.$relativePath['path']);
            if($absolutePath==false||strpos($absolutePath,$upload['baseurl'])!==false)
                return null;

            if(!file_exists($absolutePath))
                return null;

            $fileUrl=$absolutePath;
            $imageSize=getimagesize($fileUrl);
            if($imageSize==null)
                return null;
        }else{
            $imageSize =getimagesize($fileUrl);
            if($imageSize==null)
                return null;
        }


        $width=$imageSize[0];
        $height=$imageSize[1];


        switch ($imageSize['mime'])
        {
            case 'image/png':
                return $this->Item->Generator->CPDF->addPngFromFile($fileUrl,0,0,$width,$height,true);
            case 'image/jpeg':
                return $this->Item->Generator->CPDF->addJpegFromFile($fileUrl,0,0,$width,$height,true);
            default:
                return null;
        }




    }

    private function DrawImage($imageName, $options)
    {
        $operations=array();
        $operations[]=PDFOperation::PushGraphicsState();

        if(isset($operations['graphicsState']))
            $operations[]=PDFOperation::SetGraphicsState($options['graphicsState']);

        $operations[]=PDFOperation::Translate($options['x'],$options['y']);
        $operations[]=PDFOperation::RotateRadians(PDFOperation::ToRadians($options['rotate']));
        $operations[]=PDFOperation::Scale($options['width'],$options['height']);
        $operations[]=PDFOperation::SkewRadians(PDFOperation::ToRadians($options['xSkew']),PDFOperation::ToRadians($options['ySkew']));
        $operations[]=PDFOperation::DrawObject('/'.$imageName);
        $operations[]=PDFOperation::PopGraphicsState();

        return $operations;

    }


}