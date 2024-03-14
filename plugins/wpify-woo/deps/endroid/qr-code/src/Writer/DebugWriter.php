<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Writer;

use WpifyWooDeps\Endroid\QrCode\Label\LabelInterface;
use WpifyWooDeps\Endroid\QrCode\Logo\LogoInterface;
use WpifyWooDeps\Endroid\QrCode\QrCodeInterface;
use WpifyWooDeps\Endroid\QrCode\Writer\Result\DebugResult;
use WpifyWooDeps\Endroid\QrCode\Writer\Result\ResultInterface;
final class DebugWriter implements WriterInterface, ValidatingWriterInterface
{
    public function write(QrCodeInterface $qrCode, LogoInterface $logo = null, LabelInterface $label = null, array $options = []) : ResultInterface
    {
        return new DebugResult($qrCode, $logo, $label, $options);
    }
    public function validateResult(ResultInterface $result, string $expectedData) : void
    {
        if (!$result instanceof DebugResult) {
            throw new \Exception('Unable to write logo: instance of DebugResult expected');
        }
        $result->setValidateResult(\true);
    }
}
