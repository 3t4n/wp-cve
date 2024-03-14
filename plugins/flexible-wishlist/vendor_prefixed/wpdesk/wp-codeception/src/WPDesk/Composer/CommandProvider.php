<?php

namespace FlexibleWishlistVendor\WPDesk\Composer\Codeception;

use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareCodeceptionDb;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTests;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTestsWithCoverage;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareParallelCodeceptionTests;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareWordpressForCodeception;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests;
use FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTestsWithCoverage;
/**
 * Links plugin commands handlers to composer.
 */
class CommandProvider implements \FlexibleWishlistVendor\Composer\Plugin\Capability\CommandProvider
{
    public function getCommands()
    {
        return [new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\CreateCodeceptionTests(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTests(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\RunLocalCodeceptionTestsWithCoverage(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareCodeceptionDb(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareWordpressForCodeception(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTests(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareLocalCodeceptionTestsWithCoverage(), new \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\PrepareParallelCodeceptionTests()];
    }
}
