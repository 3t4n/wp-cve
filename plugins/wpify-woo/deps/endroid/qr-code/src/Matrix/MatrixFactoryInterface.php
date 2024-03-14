<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Matrix;

use WpifyWooDeps\Endroid\QrCode\QrCodeInterface;
interface MatrixFactoryInterface
{
    public function create(QrCodeInterface $qrCode) : MatrixInterface;
}
