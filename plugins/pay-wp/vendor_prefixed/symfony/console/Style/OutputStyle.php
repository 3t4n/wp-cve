<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Console\Style;

use WPPayVendor\Symfony\Component\Console\Formatter\OutputFormatterInterface;
use WPPayVendor\Symfony\Component\Console\Helper\ProgressBar;
use WPPayVendor\Symfony\Component\Console\Output\ConsoleOutputInterface;
use WPPayVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * Decorates output to add console style guide helpers.
 *
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class OutputStyle implements \WPPayVendor\Symfony\Component\Console\Output\OutputInterface, \WPPayVendor\Symfony\Component\Console\Style\StyleInterface
{
    private $output;
    public function __construct(\WPPayVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->output = $output;
    }
    /**
     * {@inheritdoc}
     */
    public function newLine(int $count = 1)
    {
        $this->output->write(\str_repeat(\PHP_EOL, $count));
    }
    /**
     * @return ProgressBar
     */
    public function createProgressBar(int $max = 0)
    {
        return new \WPPayVendor\Symfony\Component\Console\Helper\ProgressBar($this->output, $max);
    }
    /**
     * {@inheritdoc}
     */
    public function write($messages, bool $newline = \false, int $type = self::OUTPUT_NORMAL)
    {
        $this->output->write($messages, $newline, $type);
    }
    /**
     * {@inheritdoc}
     */
    public function writeln($messages, int $type = self::OUTPUT_NORMAL)
    {
        $this->output->writeln($messages, $type);
    }
    /**
     * {@inheritdoc}
     */
    public function setVerbosity(int $level)
    {
        $this->output->setVerbosity($level);
    }
    /**
     * {@inheritdoc}
     */
    public function getVerbosity()
    {
        return $this->output->getVerbosity();
    }
    /**
     * {@inheritdoc}
     */
    public function setDecorated(bool $decorated)
    {
        $this->output->setDecorated($decorated);
    }
    /**
     * {@inheritdoc}
     */
    public function isDecorated()
    {
        return $this->output->isDecorated();
    }
    /**
     * {@inheritdoc}
     */
    public function setFormatter(\WPPayVendor\Symfony\Component\Console\Formatter\OutputFormatterInterface $formatter)
    {
        $this->output->setFormatter($formatter);
    }
    /**
     * {@inheritdoc}
     */
    public function getFormatter()
    {
        return $this->output->getFormatter();
    }
    /**
     * {@inheritdoc}
     */
    public function isQuiet()
    {
        return $this->output->isQuiet();
    }
    /**
     * {@inheritdoc}
     */
    public function isVerbose()
    {
        return $this->output->isVerbose();
    }
    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose()
    {
        return $this->output->isVeryVerbose();
    }
    /**
     * {@inheritdoc}
     */
    public function isDebug()
    {
        return $this->output->isDebug();
    }
    protected function getErrorOutput()
    {
        if (!$this->output instanceof \WPPayVendor\Symfony\Component\Console\Output\ConsoleOutputInterface) {
            return $this->output;
        }
        return $this->output->getErrorOutput();
    }
}
