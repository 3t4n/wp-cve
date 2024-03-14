<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Bacon;

use WpifyWooDeps\BaconQrCode\Common\ErrorCorrectionLevel;
use WpifyWooDeps\Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use WpifyWooDeps\Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelInterface;
use WpifyWooDeps\Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use WpifyWooDeps\Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use WpifyWooDeps\Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelQuartile;
final class ErrorCorrectionLevelConverter
{
    public static function convertToBaconErrorCorrectionLevel(ErrorCorrectionLevelInterface $errorCorrectionLevel) : ErrorCorrectionLevel
    {
        if ($errorCorrectionLevel instanceof ErrorCorrectionLevelLow) {
            return ErrorCorrectionLevel::valueOf('L');
        } elseif ($errorCorrectionLevel instanceof ErrorCorrectionLevelMedium) {
            return ErrorCorrectionLevel::valueOf('M');
        } elseif ($errorCorrectionLevel instanceof ErrorCorrectionLevelQuartile) {
            return ErrorCorrectionLevel::valueOf('Q');
        } elseif ($errorCorrectionLevel instanceof ErrorCorrectionLevelHigh) {
            return ErrorCorrectionLevel::valueOf('H');
        }
        throw new \Exception('Error correction level could not be converted');
    }
}
