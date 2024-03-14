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

use DhlVendor\Ramsey\Uuid\Exception\NodeException;
use DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface;
use DhlVendor\Ramsey\Uuid\Type\Hexadecimal;
/**
 * FallbackNodeProvider retrieves the system node ID by stepping through a list
 * of providers until a node ID can be obtained
 */
class FallbackNodeProvider implements \DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface
{
    /**
     * @var NodeProviderCollection
     */
    private $nodeProviders;
    /**
     * @param NodeProviderCollection $providers Array of node providers
     */
    public function __construct(\DhlVendor\Ramsey\Uuid\Provider\Node\NodeProviderCollection $providers)
    {
        $this->nodeProviders = $providers;
    }
    public function getNode() : \DhlVendor\Ramsey\Uuid\Type\Hexadecimal
    {
        $lastProviderException = null;
        foreach ($this->nodeProviders as $provider) {
            try {
                return $provider->getNode();
            } catch (\DhlVendor\Ramsey\Uuid\Exception\NodeException $exception) {
                $lastProviderException = $exception;
                continue;
            }
        }
        throw new \DhlVendor\Ramsey\Uuid\Exception\NodeException('Unable to find a suitable node provider', 0, $lastProviderException);
    }
}
