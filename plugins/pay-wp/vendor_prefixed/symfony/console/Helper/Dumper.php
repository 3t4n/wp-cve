<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Console\Helper;

use WPPayVendor\Symfony\Component\Console\Output\OutputInterface;
use WPPayVendor\Symfony\Component\VarDumper\Cloner\ClonerInterface;
use WPPayVendor\Symfony\Component\VarDumper\Cloner\VarCloner;
use WPPayVendor\Symfony\Component\VarDumper\Dumper\CliDumper;
/**
 * @author Roland Franssen <franssen.roland@gmail.com>
 */
final class Dumper
{
    private $output;
    private $dumper;
    private $cloner;
    private $handler;
    public function __construct(\WPPayVendor\Symfony\Component\Console\Output\OutputInterface $output, ?\WPPayVendor\Symfony\Component\VarDumper\Dumper\CliDumper $dumper = null, ?\WPPayVendor\Symfony\Component\VarDumper\Cloner\ClonerInterface $cloner = null)
    {
        $this->output = $output;
        $this->dumper = $dumper;
        $this->cloner = $cloner;
        if (\class_exists(\WPPayVendor\Symfony\Component\VarDumper\Dumper\CliDumper::class)) {
            $this->handler = function ($var) : string {
                $dumper = $this->dumper ?? ($this->dumper = new \WPPayVendor\Symfony\Component\VarDumper\Dumper\CliDumper(null, null, \WPPayVendor\Symfony\Component\VarDumper\Dumper\CliDumper::DUMP_LIGHT_ARRAY | \WPPayVendor\Symfony\Component\VarDumper\Dumper\CliDumper::DUMP_COMMA_SEPARATOR));
                $dumper->setColors($this->output->isDecorated());
                return \rtrim($dumper->dump(($this->cloner ?? ($this->cloner = new \WPPayVendor\Symfony\Component\VarDumper\Cloner\VarCloner()))->cloneVar($var)->withRefHandles(\false), \true));
            };
        } else {
            $this->handler = function ($var) : string {
                switch (\true) {
                    case null === $var:
                        return 'null';
                    case \true === $var:
                        return 'true';
                    case \false === $var:
                        return 'false';
                    case \is_string($var):
                        return '"' . $var . '"';
                    default:
                        return \rtrim(\print_r($var, \true));
                }
            };
        }
    }
    public function __invoke($var) : string
    {
        return ($this->handler)($var);
    }
}
