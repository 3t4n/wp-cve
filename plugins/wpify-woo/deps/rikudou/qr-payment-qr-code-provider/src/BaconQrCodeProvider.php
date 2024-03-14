<?php

namespace WpifyWooDeps\Rikudou\QrPaymentQrCodeProvider;

use WpifyWooDeps\BaconQrCode\Renderer\Image\EpsImageBackEnd;
use WpifyWooDeps\BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use WpifyWooDeps\BaconQrCode\Renderer\Image\SvgImageBackEnd;
use WpifyWooDeps\BaconQrCode\Renderer\ImageRenderer;
use WpifyWooDeps\BaconQrCode\Renderer\RendererStyle\RendererStyle;
use WpifyWooDeps\BaconQrCode\Writer;
use Imagick;
use XMLWriter;
final class BaconQrCodeProvider implements QrCodeProvider
{
    public function getQrCode(string $data) : QrCode
    {
        if (\class_exists(Imagick::class)) {
            $backend = new ImagickImageBackEnd();
        } elseif (\class_exists(XMLWriter::class)) {
            $backend = new SvgImageBackEnd();
        } else {
            $backend = new EpsImageBackEnd();
        }
        $renderer = new ImageRenderer(new RendererStyle(400), $backend);
        $writer = new Writer($renderer);
        return new BaconQrCode($writer, $data, $backend);
    }
    public static function isInstalled() : bool
    {
        return \class_exists(Writer::class);
    }
}
