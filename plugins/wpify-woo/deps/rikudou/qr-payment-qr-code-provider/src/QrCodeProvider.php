<?php

namespace WpifyWooDeps\Rikudou\QrPaymentQrCodeProvider;

interface QrCodeProvider
{
    public function getQrCode(string $data) : QrCode;
    public static function isInstalled() : bool;
}
