<?php

declare (strict_types=1);
namespace WpifyWooDeps\Endroid\QrCode\Writer\Result;

final class PdfResult extends AbstractResult
{
    private \WpifyWooDeps\FPDF $fpdf;
    public function __construct(\WpifyWooDeps\FPDF $fpdf)
    {
        $this->fpdf = $fpdf;
    }
    public function getPdf() : \WpifyWooDeps\FPDF
    {
        return $this->fpdf;
    }
    public function getString() : string
    {
        return $this->fpdf->Output('S');
    }
    public function getMimeType() : string
    {
        return 'application/pdf';
    }
}
