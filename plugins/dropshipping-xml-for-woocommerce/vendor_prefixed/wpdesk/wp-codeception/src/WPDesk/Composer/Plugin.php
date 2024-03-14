<?php

namespace DropshippingXmlFreeVendor\WPDesk\Composer\Codeception;

use DropshippingXmlFreeVendor\Composer\Composer;
use DropshippingXmlFreeVendor\Composer\IO\IOInterface;
use DropshippingXmlFreeVendor\Composer\Plugin\Capable;
use DropshippingXmlFreeVendor\Composer\Plugin\PluginInterface;
/**
 * Composer plugin.
 *
 * @package WPDesk\Composer\Codeception
 */
class Plugin implements \DropshippingXmlFreeVendor\Composer\Plugin\PluginInterface, \DropshippingXmlFreeVendor\Composer\Plugin\Capable
{
    /**
     * @var Composer
     */
    private $composer;
    /**
     * @var IOInterface
     */
    private $io;
    public function activate(\DropshippingXmlFreeVendor\Composer\Composer $composer, \DropshippingXmlFreeVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function deactivate(\DropshippingXmlFreeVendor\Composer\Composer $composer, \DropshippingXmlFreeVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    /**
     * @inheritDoc
     */
    public function uninstall(\DropshippingXmlFreeVendor\Composer\Composer $composer, \DropshippingXmlFreeVendor\Composer\IO\IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }
    public function getCapabilities()
    {
        return [\DropshippingXmlFreeVendor\Composer\Plugin\Capability\CommandProvider::class => \DropshippingXmlFreeVendor\WPDesk\Composer\Codeception\CommandProvider::class];
    }
}
