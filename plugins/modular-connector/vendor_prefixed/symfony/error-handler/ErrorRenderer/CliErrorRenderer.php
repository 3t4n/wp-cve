<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\ErrorHandler\ErrorRenderer;

use Modular\ConnectorDependencies\Symfony\Component\ErrorHandler\Exception\FlattenException;
use Modular\ConnectorDependencies\Symfony\Component\VarDumper\Cloner\VarCloner;
use Modular\ConnectorDependencies\Symfony\Component\VarDumper\Dumper\CliDumper;
// Help opcache.preload discover always-needed symbols
\class_exists(CliDumper::class);
/**
 * @author Nicolas Grekas <p@tchwork.com>
 * @internal
 */
class CliErrorRenderer implements ErrorRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function render(\Throwable $exception) : FlattenException
    {
        $cloner = new VarCloner();
        $dumper = new class extends CliDumper
        {
            protected function supportsColors() : bool
            {
                $outputStream = $this->outputStream;
                $this->outputStream = \fopen('php://stdout', 'w');
                try {
                    return parent::supportsColors();
                } finally {
                    $this->outputStream = $outputStream;
                }
            }
        };
        return FlattenException::createFromThrowable($exception)->setAsString($dumper->dump($cloner->cloneVar($exception), \true));
    }
}
