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
namespace DhlVendor\Ramsey\Uuid\Provider\Node;

use DhlVendor\Ramsey\Collection\AbstractCollection;
use DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface;
use DhlVendor\Ramsey\Uuid\Type\Hexadecimal;
/**
 * A collection of NodeProviderInterface objects
 *
 * @extends AbstractCollection<NodeProviderInterface>
 */
class NodeProviderCollection extends \DhlVendor\Ramsey\Collection\AbstractCollection
{
    public function getType() : string
    {
        return \DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface::class;
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
        /** @var array<array-key, NodeProviderInterface> $data */
        $data = \unserialize($serialized, ['allowed_classes' => [\DhlVendor\Ramsey\Uuid\Type\Hexadecimal::class, \DhlVendor\Ramsey\Uuid\Provider\Node\RandomNodeProvider::class, \DhlVendor\Ramsey\Uuid\Provider\Node\StaticNodeProvider::class, \DhlVendor\Ramsey\Uuid\Provider\Node\SystemNodeProvider::class]]);
        $this->data = \array_filter($data, function ($unserialized) : bool {
            return $unserialized instanceof \DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface;
        });
    }
}
