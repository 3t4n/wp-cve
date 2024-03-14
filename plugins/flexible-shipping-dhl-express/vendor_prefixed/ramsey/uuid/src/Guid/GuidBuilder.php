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
namespace DhlVendor\Ramsey\Uuid\Guid;

use DhlVendor\Ramsey\Uuid\Builder\UuidBuilderInterface;
use DhlVendor\Ramsey\Uuid\Codec\CodecInterface;
use DhlVendor\Ramsey\Uuid\Converter\NumberConverterInterface;
use DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface;
use DhlVendor\Ramsey\Uuid\Exception\UnableToBuildUuidException;
use DhlVendor\Ramsey\Uuid\UuidInterface;
use Throwable;
/**
 * GuidBuilder builds instances of Guid
 *
 * @see Guid
 *
 * @psalm-immutable
 */
class GuidBuilder implements \DhlVendor\Ramsey\Uuid\Builder\UuidBuilderInterface
{
    /**
     * @var NumberConverterInterface
     */
    private $numberConverter;
    /**
     * @var TimeConverterInterface
     */
    private $timeConverter;
    /**
     * @param NumberConverterInterface $numberConverter The number converter to
     *     use when constructing the Guid
     * @param TimeConverterInterface $timeConverter The time converter to use
     *     for converting timestamps extracted from a UUID to Unix timestamps
     */
    public function __construct(\DhlVendor\Ramsey\Uuid\Converter\NumberConverterInterface $numberConverter, \DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface $timeConverter)
    {
        $this->numberConverter = $numberConverter;
        $this->timeConverter = $timeConverter;
    }
    /**
     * Builds and returns a Guid
     *
     * @param CodecInterface $codec The codec to use for building this Guid instance
     * @param string $bytes The byte string from which to construct a UUID
     *
     * @return Guid The GuidBuilder returns an instance of Ramsey\Uuid\Guid\Guid
     *
     * @psalm-pure
     */
    public function build(\DhlVendor\Ramsey\Uuid\Codec\CodecInterface $codec, string $bytes) : \DhlVendor\Ramsey\Uuid\UuidInterface
    {
        try {
            return new \DhlVendor\Ramsey\Uuid\Guid\Guid($this->buildFields($bytes), $this->numberConverter, $codec, $this->timeConverter);
        } catch (\Throwable $e) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\UnableToBuildUuidException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
    /**
     * Proxy method to allow injecting a mock, for testing
     */
    protected function buildFields(string $bytes) : \DhlVendor\Ramsey\Uuid\Guid\Fields
    {
        return new \DhlVendor\Ramsey\Uuid\Guid\Fields($bytes);
    }
}
