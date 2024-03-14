<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode;

use WpifyWooDeps\Endroid\QrCode\Color\ColorInterface;
use WpifyWooDeps\Endroid\QrCode\Encoding\EncodingInterface;
use WpifyWooDeps\Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelInterface;
use WpifyWooDeps\Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeInterface;
interface QrCodeInterface
{
    public function getData() : string;
    public function getEncoding() : EncodingInterface;
    public function getErrorCorrectionLevel() : ErrorCorrectionLevelInterface;
    public function getSize() : int;
    public function getMargin() : int;
    public function getRoundBlockSizeMode() : RoundBlockSizeModeInterface;
    public function getForegroundColor() : ColorInterface;
    public function getBackgroundColor() : ColorInterface;
}
