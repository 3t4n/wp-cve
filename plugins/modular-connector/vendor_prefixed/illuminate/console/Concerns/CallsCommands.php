<?php

namespace Modular\ConnectorDependencies\Illuminate\Console\Concerns;

use Modular\ConnectorDependencies\Symfony\Component\Console\Input\ArrayInput;
use Modular\ConnectorDependencies\Symfony\Component\Console\Output\NullOutput;
use Modular\ConnectorDependencies\Symfony\Component\Console\Output\OutputInterface;
/** @internal */
trait CallsCommands
{
    /**
     * Resolve the console command instance for the given command.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @return \Symfony\Component\Console\Command\Command
     */
    protected abstract function resolveCommand($command);
    /**
     * Call another console command.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @param  array  $arguments
     * @return int
     */
    public function call($command, array $arguments = [])
    {
        return $this->runCommand($command, $arguments, $this->output);
    }
    /**
     * Call another console command without output.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @param  array  $arguments
     * @return int
     */
    public function callSilent($command, array $arguments = [])
    {
        return $this->runCommand($command, $arguments, new NullOutput());
    }
    /**
     * Call another console command without output.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @param  array  $arguments
     * @return int
     */
    public function callSilently($command, array $arguments = [])
    {
        return $this->callSilent($command, $arguments);
    }
    /**
     * Run the given the console command.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @param  array  $arguments
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    protected function runCommand($command, array $arguments, OutputInterface $output)
    {
        $arguments['command'] = $command;
        return $this->resolveCommand($command)->run($this->createInputFromArguments($arguments), $output);
    }
    /**
     * Create an input instance from the given arguments.
     *
     * @param  array  $arguments
     * @return \Symfony\Component\Console\Input\ArrayInput
     */
    protected function createInputFromArguments(array $arguments)
    {
        return \Modular\ConnectorDependencies\tap(new ArrayInput(\array_merge($this->context(), $arguments)), function ($input) {
            if ($input->getParameterOption('--no-interaction')) {
                $input->setInteractive(\false);
            }
        });
    }
    /**
     * Get all of the context passed to the command.
     *
     * @return array
     */
    protected function context()
    {
        return \Modular\ConnectorDependencies\collect($this->option())->only(['ansi', 'no-ansi', 'no-interaction', 'quiet', 'verbose'])->filter()->mapWithKeys(function ($value, $key) {
            return ["--{$key}" => $value];
        })->all();
    }
}
