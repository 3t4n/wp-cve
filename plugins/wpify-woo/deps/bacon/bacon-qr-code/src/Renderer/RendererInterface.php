<?php

declare (strict_types=1);
namespace WpifyWooDeps\BaconQrCode\Renderer;

use WpifyWooDeps\BaconQrCode\Encoder\QrCode;
interface RendererInterface
{
    public function render(QrCode $qrCode) : string;
}
