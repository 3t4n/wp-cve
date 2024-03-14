<?php

namespace WPDeskFIVendor\WPDesk\Composer\Codeception;

use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareCodeceptionDb;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTests;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTestsWithCoverage;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareParallelCodeceptionTests;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareWordpressForCodeception;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests;
use WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTestsWithCoverage;
/**
 * Links plugin commands handlers to composer.
 */
class CommandProvider implements \WPDeskFIVendor\Composer\Plugin\Capability\CommandProvider
{
    public function getCommands()
    {
        return [new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTestsWithCoverage(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareCodeceptionDb(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareWordpressForCodeception(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTests(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTestsWithCoverage(), new \WPDeskFIVendor\WPDesk\Composer\Codeception\Commands\PrepareParallelCodeceptionTests()];
    }
}
