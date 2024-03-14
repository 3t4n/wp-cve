<?php


namespace rnpdfimporter\JPDFGenerator\Appearance\Layout;


use rnpdfimporter\JPDFGenerator\Appearance\Font\Font;

class SingleLineTextLayout extends LayoutBase
{
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
        $line=preg_replace("/[\n\f\r]/",'',$text);
         if($fontSize==null||$fontSize==0)
        {
            $fontSize=$this->ComputeFontSize([$line],$font,4,$bounds);
        }

        $encoded=$font->EncodeText($text);
        $width=$font->WidthOfTextAtSize($text,$fontSize);
        $height=$font->HeightAtSize($fontSize,false);

        $x=$bounds['x'];
        switch($alignment)
        {
            case 0:
                $x=$bounds['x'];
                break;
            case 1:
                $x=$bounds['x']+($bounds['width']/2)-($width/2);
                break;
            case 2:
                $x=$bounds['x']+$bounds['width']-$width;


        }

        $y=$bounds['y']+($bounds['height']/2-$height/2);
        return (object)array(
            'fontSize'=>$fontSize,
            'line'=>(object)array(
                'text'=>$line,
                'line'=>$line,
                'encoded'=>$encoded,
                'width'=>$width,
                'height'=>$height,
                'x'=>$x,
                'y'=>$y

            ),
            'bounds'=>(object)array(
                'x'=>$x,
                'y'=>$y,
                'width'=>$width,
                'height'=>$height
            )
        );
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