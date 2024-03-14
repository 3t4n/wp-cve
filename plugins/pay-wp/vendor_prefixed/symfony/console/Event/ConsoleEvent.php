<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Console\Event;

use WPPayVendor\Symfony\Component\Console\Command\Command;
use WPPayVendor\Symfony\Component\Console\Input\InputInterface;
use WPPayVendor\Symfony\Component\Console\Output\OutputInterface;
use WPPayVendor\Symfony\Contracts\EventDispatcher\Event;
/**
 * Allows to inspect input and output of a command.
 *
 * @author Francesco Levorato <git@flevour.net>
 */
class ConsoleEvent extends \WPPayVendor\Symfony\Contracts\EventDispatcher\Event
{
    protected $command;
    private $input;
    private $output;
    public function __construct(?\WPPayVendor\Symfony\Component\Console\Command\Command $command, \WPPayVendor\Symfony\Component\Console\Input\InputInterface $input, \WPPayVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->command = $command;
        $this->input = $input;
        $this->output = $output;
    }
    /**
     * Gets the command that is executed.
     *
     * @return Command|null
     */
    public function getCommand()
    {
        return $this->command;
    }
    /**
     * Gets the input instance.
     *
     * @return InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }
    /**
     * Gets the output instance.
     *
     * @return OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }
}
