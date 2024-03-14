<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
declare (strict_types=1);
namespace DhlVendor\Ramsey\Uuid\Rfc4122;

use DhlVendor\Ramsey\Uuid\Codec\CodecInterface;
use DhlVendor\Ramsey\Uuid\Converter\NumberConverterInterface;
use DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface;
use DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException;
use DhlVendor\Ramsey\Uuid\Rfc4122\FieldsInterface as Rfc4122FieldsInterface;
use DhlVendor\Ramsey\Uuid\Uuid;
/**
 * Random, or version 4, UUIDs are randomly or pseudo-randomly generated 128-bit
 * integers
 *
 * @psalm-immutable
 */
final class UuidV4 extends \DhlVendor\Ramsey\Uuid\Uuid implements \DhlVendor\Ramsey\Uuid\Rfc4122\UuidInterface
{
    /**
     * Creates a version 4 (random) UUID
     *
     * @param Rfc4122FieldsInterface $fields The fields from which to construct a UUID
     * @param NumberConverterInterface $numberConverter The number converter to use
     *     for converting hex values to/from integers
     * @param CodecInterface $codec The codec to use when encoding or decoding
     *     UUID strings
     * @param TimeConverterInterface $timeConverter The time converter to use
     *     for converting timestamps extracted from a UUID to unix timestamps
     */
    public function __construct(\DhlVendor\Ramsey\Uuid\Rfc4122\FieldsInterface $fields, \DhlVendor\Ramsey\Uuid\Converter\NumberConverterInterface $numberConverter, \DhlVendor\Ramsey\Uuid\Codec\CodecInterface $codec, \DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface $timeConverter)
    {
        if ($fields->getVersion() !== \DhlVendor\Ramsey\Uuid\Uuid::UUID_TYPE_RANDOM) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\InvalidArgumentException('Fields used to create a UuidV4 must represent a ' . 'version 4 (random) UUID');
        }
        parent::__construct($fields, $numberConverter, $codec, $timeConverter);
    }
}
