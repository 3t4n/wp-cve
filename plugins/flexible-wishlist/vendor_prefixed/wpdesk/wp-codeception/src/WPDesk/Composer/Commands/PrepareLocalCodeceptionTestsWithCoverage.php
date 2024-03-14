<?php

namespace FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands;

use FlexibleWishlistVendor\Composer\Downloader\FilesystemException;
use FlexibleWishlistVendor\Symfony\Component\Console\Input\InputArgument;
use FlexibleWishlistVendor\Symfony\Component\Console\Input\InputInterface;
use FlexibleWishlistVendor\Symfony\Component\Console\Output\OutputInterface;
use FlexibleWishlistVendor\Symfony\Component\Yaml\Exception\ParseException;
use FlexibleWishlistVendor\Symfony\Component\Yaml\Yaml;
/**
 * Codeception tests run command.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
class PrepareLocalCodeceptionTestsWithCoverage extends \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests
{
    use LocalCodeceptionTrait;
    /**
     * Configure command.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('prepare-local-codeception-tests-with-coverage')->setDescription('Prepare local codeception tests.');
    }
    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(\FlexibleWishlistVendor\Symfony\Component\Console\Input\InputInterface $input, \FlexibleWishlistVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->prepareLocalCodeceptionTests($input, $output, \true);
        $configuration = $this->getWpDeskConfiguration();
        $plugin_file = $configuration->getApacheDocumentRoot() . '/wp-content/plugins/' . $configuration->getPluginFile();
        \file_put_contents($plugin_file, "\ndefine('C3_CODECOVERAGE_ERROR_LOG_FILE', '/tmp/c3_error.log'); include __DIR__ . '/c3.php';", \FILE_APPEND);
        return 0;
    }
}
