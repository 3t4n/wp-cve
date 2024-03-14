<?php

namespace FlexibleWishlistVendor\WPDesk\Composer\Codeception;

use FlexibleWishlistVendor\Composer\Composer;
use FlexibleWishlistVendor\Composer\IO\IOInterface;
use FlexibleWishlistVendor\Composer\Plugin\Capable;
use FlexibleWishlistVendor\Composer\Plugin\PluginInterface;
/**
 * Composer plugin.
 *
 * @package WPDesk\Composer\Codeception
 */
class Plugin implements \FlexibleWishlistVendor\Composer\Plugin\PluginInterface, \FlexibleWishlistVendor\Composer\Plugin\Capable
{
    /**
     * @var Composer
     */
    private $composer;
    /**
     * @var IOInterface
     */
    private $io;
    public function activate(\FlexibleWishlistVendor\Composer\Composer $composer, \FlexibleWishlistVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function deactivate(\FlexibleWishlistVendor\Composer\Composer $composer, \FlexibleWishlistVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function uninstall(\FlexibleWishlistVendor\Composer\Composer $composer, \FlexibleWishlistVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    public function getCapabilities()
    {
        return [\FlexibleWishlistVendor\Composer\Plugin\Capability\CommandProvider::class => \FlexibleWishlistVendor\WPDesk\Composer\Codeception\CommandProvider::class];
    }
}
