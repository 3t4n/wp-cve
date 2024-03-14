<?php

namespace FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands;

use FlexibleWishlistVendor\Symfony\Component\Console\Input\InputArgument;
use FlexibleWishlistVendor\Symfony\Component\Console\Input\InputInterface;
use FlexibleWishlistVendor\Symfony\Component\Console\Output\OutputInterface;
use FlexibleWishlistVendor\Symfony\Component\Yaml\Exception\ParseException;
use FlexibleWishlistVendor\Symfony\Component\Yaml\Yaml;
/**
 * Prepare Database for Codeception tests command.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
class PrepareCodeceptionDb extends \FlexibleWishlistVendor\WPDesk\Composer\Codeception\Commands\BaseCommand
{
    use LocalCodeceptionTrait;
    /**
     * Configure command.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('prepare-codeception-db')->setDescription('Prepare codeception database.');
    }
    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int 0 if everything went fine, or an error code
     */
    protected function execute(\FlexibleWishlistVendor\Symfony\Component\Console\Input\InputInterface $input, \FlexibleWishlistVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $configuration = $this->getWpDeskConfiguration();
        $this->installPlugin($configuration->getPluginDir(), $output, $configuration);
        $this->prepareCommonWpWcConfiguration($configuration, $output);
        $this->prepareWpConfig($output, $configuration);
        $this->activatePlugins($output, $configuration);
        $this->executeWpCliAndOutput('db export ' . \getcwd() . '/tests/codeception/tests/_data/db.sql', $output, $configuration->getApacheDocumentRoot());
        return 0;
    }
}
