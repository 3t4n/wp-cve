<?php

declare (strict_types=1);
namespace WPPayVendor\Metadata\Driver;

use WPPayVendor\Metadata\ClassMetadata;
/**
 * Base file driver implementation.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class AbstractFileDriver implements \WPPayVendor\Metadata\Driver\AdvancedDriverInterface
{
    /**
     * @var FileLocatorInterface|FileLocator
     */
    private $locator;
    public function __construct(\WPPayVendor\Metadata\Driver\FileLocatorInterface $locator)
    {
        $this->locator = $locator;
    }
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata
    {
        if (null === ($path = $this->locator->findFileForClass($class, $this->getExtension()))) {
            return null;
        }
        return $this->loadMetadataFromFile($class, $path);
    }
    /**
     * {@inheritDoc}
     */
    public function getAllClassNames() : array
    {
        if (!$this->locator instanceof \WPPayVendor\Metadata\Driver\AdvancedFileLocatorInterface) {
            throw new \RuntimeException(\sprintf('Locator "%s" must be an instance of "AdvancedFileLocatorInterface".', \get_class($this->locator)));
        }
        return $this->locator->findAllClasses($this->getExtension());
    }
    /**
     * Parses the content of the file, and converts it to the desired metadata.
     */
    protected abstract function loadMetadataFromFile(\ReflectionClass $class, string $file) : ?\WPPayVendor\Metadata\ClassMetadata;
    /**
     * Returns the extension of the file.
     */
    protected abstract function getExtension() : string;
}
