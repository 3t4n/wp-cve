<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Writer;

use WpifyWooDeps\Endroid\QrCode\Label\LabelInterface;
use WpifyWooDeps\Endroid\QrCode\Logo\LogoInterface;
use WpifyWooDeps\Endroid\QrCode\QrCodeInterface;
use WpifyWooDeps\Endroid\QrCode\Writer\Result\ResultInterface;
interface WriterInterface
{
    /** @param array<mixed> $options */
    public function write(QrCodeInterface $qrCode, LogoInterface $logo = null, LabelInterface $label = null, array $options = []) : ResultInterface;
}
