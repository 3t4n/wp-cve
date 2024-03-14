<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */
namespace WPDeskFIVendor\setasign\Fpdi\PdfParser\Type;

use WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParserException;
/**
 * Exception class for pdf type classes
 */
class PdfTypeException extends \WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParserException
{
    /**
     * @var int
     */
    const NO_NEWLINE_AFTER_STREAM_KEYWORD = 0x601;
}
