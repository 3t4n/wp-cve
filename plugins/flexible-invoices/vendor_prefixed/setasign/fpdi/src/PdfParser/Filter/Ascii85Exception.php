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
 * Exception for Ascii85 filter class
 */
class Ascii85Exception extends \WPDeskFIVendor\setasign\Fpdi\PdfParser\Filter\FilterException
{
    /**
     * @var integer
     */
    const ILLEGAL_CHAR_FOUND = 0x301;
    /**
     * @var integer
     */
    const ILLEGAL_LENGTH = 0x302;
}
