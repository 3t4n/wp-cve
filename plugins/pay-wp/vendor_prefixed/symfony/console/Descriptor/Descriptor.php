<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Console\Descriptor;

use WPPayVendor\Symfony\Component\Console\Application;
use WPPayVendor\Symfony\Component\Console\Command\Command;
use WPPayVendor\Symfony\Component\Console\Exception\InvalidArgumentException;
use WPPayVendor\Symfony\Component\Console\Input\InputArgument;
use WPPayVendor\Symfony\Component\Console\Input\InputDefinition;
use WPPayVendor\Symfony\Component\Console\Input\InputOption;
use WPPayVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
abstract class Descriptor implements \WPPayVendor\Symfony\Component\Console\Descriptor\DescriptorInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;
    /**
     * {@inheritdoc}
     */
    public function describe(\WPPayVendor\Symfony\Component\Console\Output\OutputInterface $output, object $object, array $options = [])
    {
        $this->output = $output;
        switch (\true) {
            case $object instanceof \WPPayVendor\Symfony\Component\Console\Input\InputArgument:
                $this->describeInputArgument($object, $options);
                break;
            case $object instanceof \WPPayVendor\Symfony\Component\Console\Input\InputOption:
                $this->describeInputOption($object, $options);
                break;
            case $object instanceof \WPPayVendor\Symfony\Component\Console\Input\InputDefinition:
                $this->describeInputDefinition($object, $options);
                break;
            case $object instanceof \WPPayVendor\Symfony\Component\Console\Command\Command:
                $this->describeCommand($object, $options);
                break;
            case $object instanceof \WPPayVendor\Symfony\Component\Console\Application:
                $this->describeApplication($object, $options);
                break;
            default:
                throw new \WPPayVendor\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('Object of type "%s" is not describable.', \get_debug_type($object)));
        }
    }
    /**
     * Writes content to output.
     */
    protected function write(string $content, bool $decorated = \false)
    {
        $this->output->write($content, \false, $decorated ? \WPPayVendor\Symfony\Component\Console\Output\OutputInterface::OUTPUT_NORMAL : \WPPayVendor\Symfony\Component\Console\Output\OutputInterface::OUTPUT_RAW);
    }
    /**
     * Describes an InputArgument instance.
     */
    protected abstract function describeInputArgument(\WPPayVendor\Symfony\Component\Console\Input\InputArgument $argument, array $options = []);
    /**
     * Describes an InputOption instance.
     */
    protected abstract function describeInputOption(\WPPayVendor\Symfony\Component\Console\Input\InputOption $option, array $options = []);
    /**
     * Describes an InputDefinition instance.
     */
    protected abstract function describeInputDefinition(\WPPayVendor\Symfony\Component\Console\Input\InputDefinition $definition, array $options = []);
    /**
     * Describes a Command instance.
     */
    protected abstract function describeCommand(\WPPayVendor\Symfony\Component\Console\Command\Command $command, array $options = []);
    /**
     * Describes an Application instance.
     */
    protected abstract function describeApplication(\WPPayVendor\Symfony\Component\Console\Application $application, array $options = []);
}
