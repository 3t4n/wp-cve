<?php
/**
 * Extends ComposerPackage to return the typed Strauss config.
 *
 * @license MIT
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\BrianHenryIE\Strauss\Composer;

use CoffeeCode\BrianHenryIE\Strauss\Composer\Extra\StraussConfig;
use CoffeeCode\Composer\Factory;
use CoffeeCode\Composer\IO\NullIO;

class ProjectComposerPackage extends ComposerPackage
{
    protected string $author;

    protected string $vendorDirectory;

    public function __construct(string $absolutePath, array $overrideAutoload = null)
    {
        if (is_dir($absolutePath)) {
            $absolutePathDir = $absolutePath;
            $absolutePathFile = rtrim($absolutePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'composer.json';
        } else {
            $absolutePathDir = rtrim($absolutePath, 'composer.json');
            $absolutePathFile = $absolutePath;
        }
        unset($absolutePath);

        $composer = Factory::create(new NullIO(), $absolutePathFile, true);

        parent::__construct($composer, $overrideAutoload);

        $authors = $this->composer->getPackage()->getAuthors();
        if (empty($authors) || !isset($authors[0]['name'])) {
            $this->author = explode("/", $this->packageName, 2)[0];
        } else {
            $this->author = $authors[0]['name'];
        }

        $vendorDirectory = $this->composer->getConfig()->get('vendor-dir');
        if (is_string($vendorDirectory)) {
            $vendorDirectory = str_replace($absolutePathDir, '', (string) $vendorDirectory);
            $this->vendorDirectory = $vendorDirectory;
        } else {
            $this->vendorDirectory = 'vendor' . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * @return StraussConfig
     * @throws \Exception
     */
    public function getStraussConfig(): StraussConfig
    {
        $config = new StraussConfig($this->composer);
        $config->setVendorDirectory($this->getVendorDirectory());
        return $config;
    }


    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getVendorDirectory(): string
    {
        return rtrim($this->vendorDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}
