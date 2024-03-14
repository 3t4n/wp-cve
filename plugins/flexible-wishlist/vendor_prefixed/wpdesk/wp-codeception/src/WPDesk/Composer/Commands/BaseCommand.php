<?php

namespace FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands;

use FlexibleWishlistVendor\Composer\Command\BaseCommand as CodeceptionBaseCommand;
use FlexibleWishlistVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Base for commands - declares common methods.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
abstract class BaseCommand extends \FlexibleWishlistVendor\Composer\Command\BaseCommand
{
    /**
     * @param string $command
     * @param OutputInterface $output
     */
    protected function execAndOutput($command, \FlexibleWishlistVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        \passthru($command);
    }
}
