<?php

namespace DropshippingXmlFreeVendor\WPDesk\Composer\Codeception\Commands;

use DropshippingXmlFreeVendor\Composer\Command\BaseCommand as CodeceptionBaseCommand;
use DropshippingXmlFreeVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Base for commands - declares common methods.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
abstract class BaseCommand extends \DropshippingXmlFreeVendor\Composer\Command\BaseCommand
{
    /**
     * @param string $command
     * @param OutputInterface $output
     */
    protected function execAndOutput($command, \DropshippingXmlFreeVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        \passthru($command);
    }
}
