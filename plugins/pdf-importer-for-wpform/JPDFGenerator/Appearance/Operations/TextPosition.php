<?php


namespace rnpdfimporter\JPDFGenerator\Appearance\Operations;


class TextPosition
{
    public $Text;
    public $x;
    public $y;
    public $width;
    public $height;

    /**
     * TextPosition constructor.
     * @param $Text
     * @param $x
     * @param $y
     * @param $width
     * @param $height
     */
    public function __construct($Text, $x, $y, $width, $height)
    {
        $this->Text = $Text;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }


}