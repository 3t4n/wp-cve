<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Writer;

use WpifyWooDeps\Endroid\QrCode\Bacon\MatrixFactory;
use WpifyWooDeps\Endroid\QrCode\Label\LabelInterface;
use WpifyWooDeps\Endroid\QrCode\Logo\LogoInterface;
use WpifyWooDeps\Endroid\QrCode\QrCodeInterface;
use WpifyWooDeps\Endroid\QrCode\Writer\Result\ConsoleResult;
use WpifyWooDeps\Endroid\QrCode\Writer\Result\ResultInterface;
/**
 * Writer of QR Code for CLI.
 */
class ConsoleWriter implements WriterInterface
{
    /**
     * {@inheritDoc}
     */
    public function write(QrCodeInterface $qrCode, LogoInterface $logo = null, LabelInterface $label = null, $options = []) : ResultInterface
    {
        $matrixFactory = new MatrixFactory();
        $matrix = $matrixFactory->create($qrCode);
        return new ConsoleResult($matrix, $qrCode->getForegroundColor(), $qrCode->getBackgroundColor());
    }
}
