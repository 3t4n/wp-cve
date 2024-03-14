<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */
namespace WPDeskFIVendor\setasign\Fpdi\PdfParser\Type;

use WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParser;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Tokenizer;
/**
 * Class representing a PDF array object
 *
 * @property array $value The value of the PDF type.
 */
class PdfArray extends \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType
{
    /**
     * Parses an array of the passed tokenizer and parser.
     *
     * @param Tokenizer $tokenizer
     * @param PdfParser $parser
     * @return false|self
     * @throws PdfTypeException
     */
    public static function parse(\WPDeskFIVendor\setasign\Fpdi\PdfParser\Tokenizer $tokenizer, \WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParser $parser)
    {
        $result = [];
        // Recurse into this function until we reach the end of the array.
        while (($token = $tokenizer->getNextToken()) !== ']') {
            if ($token === \false || ($value = $parser->readValue($token)) === \false) {
                return \false;
            }
            $result[] = $value;
        }
        $v = new self();
        $v->value = $result;
        return $v;
    }
    /**
     * Helper method to create an instance.
     *
     * @param PdfType[] $values
     * @return self
     */
    public static function create(array $values = [])
    {
        $v = new self();
        $v->value = $values;
        return $v;
    }
    /**
     * Ensures that the passed array is a PdfArray instance with a (optional) specific size.
     *
     * @param mixed $array
     * @param null|int $size
     * @return self
     * @throws PdfTypeException
     */
    public static function ensure($array, $size = null)
    {
        $result = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType::ensureType(self::class, $array, 'Array value expected.');
        if ($size !== null && \count($array->value) !== $size) {
            throw new \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfTypeException(\sprintf('Array with %s entries expected.', $size), \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfTypeException::INVALID_DATA_SIZE);
        }
        return $result;
    }
}
