<?php

declare (strict_types=1);
namespace LassoLiteVendor\Bamarni\Composer\Bin;

use LassoLiteVendor\Composer\IO\ConsoleIO;
use LassoLiteVendor\Symfony\Component\Console\Input\InputInterface;
use LassoLiteVendor\Symfony\Component\Console\Output\OutputInterface;
final class PublicIO extends ConsoleIO
{
    public static function fromConsoleIO(ConsoleIO $io) : self
    {
        return new self($io->input, $io->output, $io->helperSet);
    }
    public function getInput() : InputInterface
    {
        return $this->input;
    }
    public function getOutput() : OutputInterface
    {
        return $this->output;
    }
}
