<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */
namespace WPDeskFIVendor\setasign\Fpdi\PdfParser\Filter;

/**
 * Exception for flate filter class
 */
class FlateException extends \WPDeskFIVendor\setasign\Fpdi\PdfParser\Filter\FilterException
{
    /**
     * @var integer
     */
    const NO_ZLIB = 0x401;
    /**
     * @var integer
     */
    const DECOMPRESS_ERROR = 0x402;
}
