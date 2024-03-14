<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Console\Completion\Output;

use Modular\ConnectorDependencies\Symfony\Component\Console\Completion\CompletionSuggestions;
use Modular\ConnectorDependencies\Symfony\Component\Console\Output\OutputInterface;
/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 * @internal
 */
class BashCompletionOutput implements CompletionOutputInterface
{
    public function write(CompletionSuggestions $suggestions, OutputInterface $output) : void
    {
        $values = $suggestions->getValueSuggestions();
        foreach ($suggestions->getOptionSuggestions() as $option) {
            $values[] = '--' . $option->getName();
            if ($option->isNegatable()) {
                $values[] = '--no-' . $option->getName();
            }
        }
        $output->writeln(\implode("\n", $values));
    }
}
