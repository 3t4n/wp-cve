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
namespace DhlVendor\Ramsey\Uuid\Builder;

use DhlVendor\Ramsey\Collection\AbstractCollection;
use DhlVendor\Ramsey\Uuid\Converter\Number\GenericNumberConverter;
use DhlVendor\Ramsey\Uuid\Converter\Time\GenericTimeConverter;
use DhlVendor\Ramsey\Uuid\Converter\Time\PhpTimeConverter;
use DhlVendor\Ramsey\Uuid\Guid\GuidBuilder;
use DhlVendor\Ramsey\Uuid\Math\BrickMathCalculator;
use DhlVendor\Ramsey\Uuid\Nonstandard\UuidBuilder as NonstandardUuidBuilder;
use DhlVendor\Ramsey\Uuid\Rfc4122\UuidBuilder as Rfc4122UuidBuilder;
use Traversable;
/**
 * A collection of UuidBuilderInterface objects
 *
 * @extends AbstractCollection<UuidBuilderInterface>
 */
class BuilderCollection extends \DhlVendor\Ramsey\Collection\AbstractCollection
{
    public function getType() : string
    {
        return \DhlVendor\Ramsey\Uuid\Builder\UuidBuilderInterface::class;
    }
    /**
     * @psalm-mutation-free
     * @psalm-suppress ImpureMethodCall
     * @psalm-suppress InvalidTemplateParam
     */
    public function getIterator() : \Traversable
    {
        return parent::getIterator();
    }
    /**
     * Re-constructs the object from its serialized form
     *
     * @param string $serialized The serialized PHP string to unserialize into
     *     a UuidInterface instance
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function unserialize($serialized) : void
    {
        /** @var array<array-key, UuidBuilderInterface> $data */
        $data = \unserialize($serialized, ['allowed_classes' => [\DhlVendor\Ramsey\Uuid\Math\BrickMathCalculator::class, \DhlVendor\Ramsey\Uuid\Converter\Number\GenericNumberConverter::class, \DhlVendor\Ramsey\Uuid\Converter\Time\GenericTimeConverter::class, \DhlVendor\Ramsey\Uuid\Guid\GuidBuilder::class, \DhlVendor\Ramsey\Uuid\Nonstandard\UuidBuilder::class, \DhlVendor\Ramsey\Uuid\Converter\Time\PhpTimeConverter::class, \DhlVendor\Ramsey\Uuid\Rfc4122\UuidBuilder::class]]);
        $this->data = \array_filter($data, function ($unserialized) : bool {
            return $unserialized instanceof \DhlVendor\Ramsey\Uuid\Builder\UuidBuilderInterface;
        });
    }
}
