<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Writer;

use WpifyWooDeps\Endroid\QrCode\Writer\Result\ResultInterface;
interface ValidatingWriterInterface
{
    public function validateResult(ResultInterface $result, string $expectedData) : void;
}
