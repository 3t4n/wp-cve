<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */
namespace WPDeskFIVendor\setasign\Fpdi;

use WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParserException;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNull;
/**
 * Class Fpdi
 *
 * This class let you import pages of existing PDF documents into a reusable structure for FPDF.
 */
class Fpdi extends \WPDeskFIVendor\setasign\Fpdi\FpdfTpl
{
    use FpdiTrait;
    use FpdfTrait;
    /**
     * FPDI version
     *
     * @string
     */
    const VERSION = '2.6.0';
}
