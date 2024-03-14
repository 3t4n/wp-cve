<?php

namespace Bpost\BpostApiClient\Bpost\Order\Box\Option;

/**
 * bPost Option class
 *
 * @author    Tijs Verkoyen <php-bpost@verkoyen.eu>
 * @version   3.0.0
 * @copyright Copyright (c), Tijs Verkoyen. All rights reserved.
 * @license   BSD License
 */
abstract class Option
{
    /**
     * @param \DOMDocument $document
     * @param string       $prefix
     * @return \DOMElement
     */
    abstract public function toXML(\DOMDocument $document, $prefix = null);
}
