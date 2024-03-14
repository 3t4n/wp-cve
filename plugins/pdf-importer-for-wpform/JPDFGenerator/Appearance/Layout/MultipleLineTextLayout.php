<?php


namespace rnpdfimporter\JPDFGenerator\Appearance\Layout;


use rnpdfimporter\JPDFGenerator\Appearance\Font\Font;

class MultipleLineTextLayout extends LayoutBase
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
        $lines=preg_split("/\r\n|\r|\n/",$text);
        $textLines=[];
        if($fontSize==null||$fontSize==0)
        {
            $fontSize=12;
        }

        $height=$font->HeightAtSize($fontSize,false);
        $lineHeight=$height+$height*.2;

        $minX=$bounds['x'];
        $minY=$bounds['y'];
        $maxX=$bounds['x']+$bounds['width'];
        $maxY=$bounds['y']+$bounds['height'];

        $y=$bounds['y']+$bounds['height'];
        foreach($lines as $currentLine)
        {
            $prevReminder=$currentLine;
            while($prevReminder!=null)
            {
                $result=$this->SplitOutLines($prevReminder,$bounds['width'],$font,$fontSize);
                $line=$result['line'];
                $encoded=$result['encoded'];
                $width=$result['width'];
                $remainder=$result['remainder'];

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
                        $x=$bounds['x']+$bounds['widht']-$width;
                }

                $y-=$lineHeight;
                if($x<$minX)
                    $minX=$x;
                if($y<$minY)
                    $minY=$y;
                if($x+$width>$maxX)
                    $maxX=$x+$width;
                if($y+$height>$maxY)
                    $maxY=$y+$height;

                $textLines[]=(object)array(
                    'text'=>$line,
                    'encoded'=>$encoded,
                    'width'=>$width,
                    'height'=>$height,
                    'x'=>$x,
                    'y'=>$y
                );

                $prevReminder=null;
                if($remainder!=null)
                    $prevReminder=trim($remainder);




            }
        }


        return (object)array(
            'fontSize'=>$fontSize,
            'lineHeight'=>$lineHeight,
            'line'=>$textLines,
            'bounds'=>(object)array(
                'x'=>$minX,
                'y'=>$minY,
                'width'=>$maxX-$minX,
                'height'=>$maxY-$minY
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

    /**
     * @param $input
     * @param $maxWidth
     * @param $font Font
     * @param $fontSize
     */
    private function SplitOutLines($input, $maxWidth, $font, $fontSize)
    {
        $lastWhiteSpaceIdx=strlen($input);
        while($lastWhiteSpaceIdx>0)
        {
            $line=substr($input,0,$lastWhiteSpaceIdx);
            $encoded=$font->EncodeText($line);
            $width=$font->WidthOfTextAtSize($line,$fontSize);
            if($width<$maxWidth)
            {
                $remainder=substr($input,$lastWhiteSpaceIdx);
                return array(
                    'line'=>$line,
                    'encoded'=>$encoded,
                    'width'=>$width,
                    'remainder'=>$remainder
                );
            }
            $lastWhiteSpaceIdx=$this->LastIndexOfWhitespace($line);
            if($lastWhiteSpaceIdx==null)
                $lastWhiteSpaceIdx=0;
        }

        return array(
            'line'=>$input,
            'encoded'=>$font->EncodeText($input),
            'width'=>$font->WidthOfTextAtSize($input,$fontSize),
            'remainder'=>null
        );


    }

    public function LastIndexOfWhitespace($line)
    {
        for($i=strlen($line)-1;$i>0;$i--)
        {
            if(preg_match('/\s/',$line[$i]))
                return $i;

        }

        return null;

    }


}