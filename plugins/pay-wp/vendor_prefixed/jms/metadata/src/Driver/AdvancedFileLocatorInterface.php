<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata\Driver;

/**
 * Forces advanced logic on a file locator.
 *
 * @author Jordan Stout <j@jrdn.org>
 */
interface AdvancedFileLocatorInterface extends \WPPayVendor\Metadata\Driver\FileLocatorInterface
{
    /**
     * Finds all possible metadata files.*
     *
     * @return string[]
     */
    public function findAllClasses(string $extension) : array;
}
