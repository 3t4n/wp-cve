<?php


namespace rnpdfimporter\JPDFGenerator\Appearance\Layout;


use rnpdfimporter\JPDFGenerator\Appearance\Font\Font;

class CombedTextLayout extends LayoutBase
{

    public $CellCount;
    public function __construct($cellCount)
    {
        $this->CellCount=$cellCount;
    }


    /**
     * @param $text
     * @param $alignment
     * @param $fontSize
     * @param $font Font
     * @param $bounds
     */
    public function Generate($text,$alignment,$fontSize,$font,$bounds)
    {
        $text=strval($text);
        $line=preg_replace("/[\n\f\r\t\b\v]/",'',$text);
        $line=substr($line,0,$this->CellCount);




        if($fontSize==null||$fontSize==0)
        {
            $fontSize=$this->ComputeFontSize([$line],$font,4,$bounds);
        }

        $cellWidth=$bounds['width']/$this->CellCount;
        $height=$font->HeightAtSize($fontSize,false);


        $encoded=$font->EncodeText($text);
        $width=$font->WidthOfTextAtSize($text,$fontSize);

        $y=$bounds['y']+($bounds['height']/2-$height/2);

        $minX=$bounds['x'];
        $minY=$bounds['y'];
        $maxX=$bounds['x']+$bounds['width'];
        $maxY=$bounds['y']+$bounds['height'];

        $cellOffset=0;
        $charOffset=0;
        $cells=array();
        while($cellOffset<$this->CellCount)
        {
            $char=substr($line,$charOffset,1);
            $encoded=$font->EncodeText($char);
            $width=$font->WidthOfTextAtSize($char,$fontSize);

            $cellCenter=$bounds['x']+($cellWidth*$cellOffset+$cellWidth/2);
            $x=$cellCenter-$width/2;

            if($x<$minX)$minX=$x;
            if($y<$minY)$minY=$y;
            if($x+$width>$maxX)$maxX=$x+$width;
            if($y+$height>$maxY)$maxY=$y+$height;

            $cells[]=(object)array(
                'text'=>$line,
                'encoded'=>$encoded,
                'width'=>$width,
                'height'=>$height,
                'x'=>$x,
                'y'=>$y
            );
            $cellOffset+=1;

            $charOffset++;
        }


        return (object)array(
            'fontSize'=>$fontSize,
            'line'=>$cells,
            'bounds'=>(object)array(
                'x'=>$x,
                'y'=>$y,
                'width'=>$width,
                'height'=>$height
            )
        );
    }

    private function CharAtIndex($text,$index)
    {

    }

    /**
     * @param $line
     * @param $font Font
     * @param $bounds
     */
    private function ComputeFontSize($line, $font,$fontSize, $bounds)
    {
        $MaxFontSize=500;
        while($fontSize<500)
        {
            foreach ($line as $currentLine)
            {
                $width = $font->WidthOfTextAtSize($currentLine, $fontSize);
                if ($width > $bounds['width'])
                    return $fontSize - 1;
            }

            $height = $font->HeightAtSize($fontSize);
            $lineHeight = $height + $height * .2;
            $totalHeight = count($line) * $lineHeight;
            if ($totalHeight > $bounds['height'])
                return $fontSize - 1;
            $fontSize += 1;
        }

        return $fontSize;
    }


}