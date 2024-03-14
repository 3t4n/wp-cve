<?php

namespace WpifyWooDeps\rikudou\SkQrPayment\Xz;

interface XzBinaryLocatorInterface
{
    /**
     * Returns the path to the xz binary
     *
     * @return string
     */
    public function getXzBinary() : string;
}
