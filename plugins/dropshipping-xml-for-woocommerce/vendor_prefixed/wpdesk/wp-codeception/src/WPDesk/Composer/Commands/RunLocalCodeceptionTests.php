<?php

namespace DropshippingXmlFreeVendor\WPDesk\Composer\Codeception\Commands;

use DropshippingXmlFreeVendor\Symfony\Component\Console\Input\InputArgument;
use DropshippingXmlFreeVendor\Symfony\Component\Console\Input\InputInterface;
use DropshippingXmlFreeVendor\Symfony\Component\Console\Output\OutputInterface;
use DropshippingXmlFreeVendor\Symfony\Component\Yaml\Exception\ParseException;
use DropshippingXmlFreeVendor\Symfony\Component\Yaml\Yaml;
/**
 * Codeception tests run command.
 *
 * @package WPDesk\Composer\Codeception\Commands
 */
class RunLocalCodeceptionTests extends \DropshippingXmlFreeVendor\WPDesk\Composer\Codeception\Commands\RunCodeceptionTests
{
    use LocalCodeceptionTrait;
    /**
     * Configure command.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('run-local-codeception-tests')->setDescription('Run local codeception tests.')->setDefinition(array(new \DropshippingXmlFreeVendor\Symfony\Component\Console\Input\InputArgument(self::SINGLE, \DropshippingXmlFreeVendor\Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'Name of Single test to run.', ' ')));
    }
    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int 0 if everything went fine, or an error code
     */
    protected function execute(\DropshippingXmlFreeVendor\Symfony\Component\Console\Input\InputInterface $input, \DropshippingXmlFreeVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $configuration = $this->getWpDeskConfiguration();
        $this->prepareWpConfig($output, $configuration);
        $singleTest = $input->getArgument(self::SINGLE);
        $sep = \DIRECTORY_SEPARATOR;
        $codecept = "vendor{$sep}bin{$sep}codecept";
        $cleanOutput = $codecept . ' clean';
        $this->execAndOutput($cleanOutput, $output);
        $runLocalTests = $codecept . ' run -f --steps --html --verbose acceptance ' . $singleTest;
        $this->execAndOutput($runLocalTests, $output);
        return 0;
    }
}
