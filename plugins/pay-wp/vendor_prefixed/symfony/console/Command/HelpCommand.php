<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Console\Command;

use WPPayVendor\Symfony\Component\Console\Completion\CompletionInput;
use WPPayVendor\Symfony\Component\Console\Completion\CompletionSuggestions;
use WPPayVendor\Symfony\Component\Console\Descriptor\ApplicationDescription;
use WPPayVendor\Symfony\Component\Console\Helper\DescriptorHelper;
use WPPayVendor\Symfony\Component\Console\Input\InputArgument;
use WPPayVendor\Symfony\Component\Console\Input\InputInterface;
use WPPayVendor\Symfony\Component\Console\Input\InputOption;
use WPPayVendor\Symfony\Component\Console\Output\OutputInterface;
/**
 * HelpCommand displays the help for a given command.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class HelpCommand extends \WPPayVendor\Symfony\Component\Console\Command\Command
{
    private $command;
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();
        $this->setName('help')->setDefinition([new \WPPayVendor\Symfony\Component\Console\Input\InputArgument('command_name', \WPPayVendor\Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'The command name', 'help'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('format', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, 'The output format (txt, xml, json, or md)', 'txt'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('raw', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'To output raw command help')])->setDescription('Display help for a command')->setHelp(<<<'EOF'
The <info>%command.name%</info> command displays help for a given command:

  <info>%command.full_name% list</info>

You can also output the help in other formats by using the <comment>--format</comment> option:

  <info>%command.full_name% --format=xml list</info>

To display the list of available commands, please use the <info>list</info> command.
EOF
);
    }
    public function setCommand(\WPPayVendor\Symfony\Component\Console\Command\Command $command)
    {
        $this->command = $command;
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(\WPPayVendor\Symfony\Component\Console\Input\InputInterface $input, \WPPayVendor\Symfony\Component\Console\Output\OutputInterface $output)
    {
        if (null === $this->command) {
            $this->command = $this->getApplication()->find($input->getArgument('command_name'));
        }
        $helper = new \WPPayVendor\Symfony\Component\Console\Helper\DescriptorHelper();
        $helper->describe($output, $this->command, ['format' => $input->getOption('format'), 'raw_text' => $input->getOption('raw')]);
        $this->command = null;
        return 0;
    }
    public function complete(\WPPayVendor\Symfony\Component\Console\Completion\CompletionInput $input, \WPPayVendor\Symfony\Component\Console\Completion\CompletionSuggestions $suggestions) : void
    {
        if ($input->mustSuggestArgumentValuesFor('command_name')) {
            $descriptor = new \WPPayVendor\Symfony\Component\Console\Descriptor\ApplicationDescription($this->getApplication());
            $suggestions->suggestValues(\array_keys($descriptor->getCommands()));
            return;
        }
        if ($input->mustSuggestOptionValuesFor('format')) {
            $helper = new \WPPayVendor\Symfony\Component\Console\Helper\DescriptorHelper();
            $suggestions->suggestValues($helper->getFormats());
        }
    }
}
