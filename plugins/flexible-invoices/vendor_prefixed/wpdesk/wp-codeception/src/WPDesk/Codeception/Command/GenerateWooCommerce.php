<?php

namespace WPDeskFIVendor\WPDesk\Codeception\Command;

use WPDeskFIVendor\Codeception\Command\GenerateTest;
use WPDeskFIVendor\Codeception\CustomCommandInterface;
use WPDeskFIVendor\Symfony\Component\Console\Input\InputInterface;
use WPDeskFIVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Generates codeception example test for WP Desk plugin activation.
 *
 * @package WPDesk\Codeception\Command
 */
class GenerateWooCommerce extends \WPDeskFIVendor\Codeception\Command\GenerateTest implements \WPDeskFIVendor\Codeception\CustomCommandInterface
{
    /**
     * Get codeception command description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Generates woocommerce tests.';
    }
    /**
     * Returns the name of the command.
     *
     * @return string
     */
    public static function getCommandName()
    {
        return 'generate:woocommerce';
    }
    /**
     * Get generator class.
     *
     * @param array  $config .
     * @param string $class .
     * @return WooCommerceTestGenerator
     */
    protected function getGenerator($config, $class)
    {
        return new \WPDeskFIVendor\WPDesk\Codeception\Command\WooCommerceTestGenerator($config, $class);
    }
    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(\WPDeskFIVendor\Symfony\Component\Console\Input\InputInterface $input, \WPDeskFIVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $suite = $input->getArgument('suite');
        $class = $input->getArgument('class');
        $config = $this->getSuiteConfig($suite);
        $className = $this->getShortClassName($class);
        $path = $this->createDirectoryFor($config['path'], $class);
        $filename = $this->completeSuffix($className, 'Cest');
        $filename = $path . $filename;
        $gen = $this->getGenerator($config, $class);
        $res = $this->createFile($filename, $gen->produce());
        if (!$res) {
            $output->writeln("<error>Test {$filename} already exists</error>");
            return;
        }
        $output->writeln("<info>Test was created in {$filename}</info>");
    }
}
