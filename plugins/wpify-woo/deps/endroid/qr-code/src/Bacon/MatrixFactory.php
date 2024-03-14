<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Bacon;

use WpifyWooDeps\BaconQrCode\Encoder\Encoder;
use WpifyWooDeps\Endroid\QrCode\Matrix\Matrix;
use WpifyWooDeps\Endroid\QrCode\Matrix\MatrixFactoryInterface;
use WpifyWooDeps\Endroid\QrCode\Matrix\MatrixInterface;
use WpifyWooDeps\Endroid\QrCode\QrCodeInterface;
final class MatrixFactory implements MatrixFactoryInterface
{
    public function create(QrCodeInterface $qrCode) : MatrixInterface
    {
        $baconErrorCorrectionLevel = ErrorCorrectionLevelConverter::convertToBaconErrorCorrectionLevel($qrCode->getErrorCorrectionLevel());
        $baconMatrix = Encoder::encode($qrCode->getData(), $baconErrorCorrectionLevel, \strval($qrCode->getEncoding()))->getMatrix();
        $blockValues = [];
        $columnCount = $baconMatrix->getWidth();
        $rowCount = $baconMatrix->getHeight();
        for ($rowIndex = 0; $rowIndex < $rowCount; ++$rowIndex) {
            $blockValues[$rowIndex] = [];
            for ($columnIndex = 0; $columnIndex < $columnCount; ++$columnIndex) {
                $blockValues[$rowIndex][$columnIndex] = $baconMatrix->get($columnIndex, $rowIndex);
            }
        }
        return new Matrix($blockValues, $qrCode->getSize(), $qrCode->getMargin(), $qrCode->getRoundBlockSizeMode());
    }
}
