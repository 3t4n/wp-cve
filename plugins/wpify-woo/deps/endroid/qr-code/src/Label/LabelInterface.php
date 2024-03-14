<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Label;

use WpifyWooDeps\Endroid\QrCode\Color\ColorInterface;
use WpifyWooDeps\Endroid\QrCode\Label\Alignment\LabelAlignmentInterface;
use WpifyWooDeps\Endroid\QrCode\Label\Font\FontInterface;
use WpifyWooDeps\Endroid\QrCode\Label\Margin\MarginInterface;
interface LabelInterface
{
    public function getText() : string;
    public function getFont() : FontInterface;
    public function getAlignment() : LabelAlignmentInterface;
    public function getMargin() : MarginInterface;
    public function getTextColor() : ColorInterface;
}
