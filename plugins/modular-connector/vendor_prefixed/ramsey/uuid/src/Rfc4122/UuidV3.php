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
namespace Modular\ConnectorDependencies\Ramsey\Uuid\Rfc4122;

use Modular\ConnectorDependencies\Ramsey\Uuid\Codec\CodecInterface;
use Modular\ConnectorDependencies\Ramsey\Uuid\Converter\NumberConverterInterface;
use Modular\ConnectorDependencies\Ramsey\Uuid\Converter\TimeConverterInterface;
use Modular\ConnectorDependencies\Ramsey\Uuid\Exception\InvalidArgumentException;
use Modular\ConnectorDependencies\Ramsey\Uuid\Rfc4122\FieldsInterface as Rfc4122FieldsInterface;
use Modular\ConnectorDependencies\Ramsey\Uuid\Uuid;
/**
 * Version 3 UUIDs are named-based, using combination of a namespace and name
 * that are hashed into a 128-bit unsigned integer using MD5
 *
 * @psalm-immutable
 * @internal
 */
final class UuidV3 extends Uuid implements UuidInterface
{
    /**
     * Creates a version 3 (name-based, MD5-hashed) UUID
     *
     * @param Rfc4122FieldsInterface $fields The fields from which to construct a UUID
     * @param NumberConverterInterface $numberConverter The number converter to use
     *     for converting hex values to/from integers
     * @param CodecInterface $codec The codec to use when encoding or decoding
     *     UUID strings
     * @param TimeConverterInterface $timeConverter The time converter to use
     *     for converting timestamps extracted from a UUID to unix timestamps
     */
    public function __construct(Rfc4122FieldsInterface $fields, NumberConverterInterface $numberConverter, CodecInterface $codec, TimeConverterInterface $timeConverter)
    {
        if ($fields->getVersion() !== Uuid::UUID_TYPE_HASH_MD5) {
            throw new InvalidArgumentException('Fields used to create a UuidV3 must represent a ' . 'version 3 (name-based, MD5-hashed) UUID');
        }
        parent::__construct($fields, $numberConverter, $codec, $timeConverter);
    }
}
