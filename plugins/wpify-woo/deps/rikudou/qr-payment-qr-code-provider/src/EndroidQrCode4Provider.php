<?php

namespace WpifyWooDeps\Rikudou\QrPaymentQrCodeProvider;

use WpifyWooDeps\Endroid\QrCode\QrCode as EndroidQrCode;
use WpifyWooDeps\Endroid\QrCode\Writer\PngWriter;
use WpifyWooDeps\Endroid\QrCode\Writer\Result\ResultInterface;
final class EndroidQrCode4Provider implements QrCodeProvider
{
    public function getQrCode(string $data) : QrCode
    {
        $code = EndroidQrCode::create($data);
        $writer = new PngWriter();
        return new EndroidQrCode4($writer->write($code));
    }
    public static function isInstalled() : bool
    {
        return \interface_exists(ResultInterface::class);
    }
}
