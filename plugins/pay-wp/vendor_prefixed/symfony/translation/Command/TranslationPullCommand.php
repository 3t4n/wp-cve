<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Command;

use WPPayVendor\Symfony\Component\Console\Command\Command;
use WPPayVendor\Symfony\Component\Console\Completion\CompletionInput;
use WPPayVendor\Symfony\Component\Console\Completion\CompletionSuggestions;
use WPPayVendor\Symfony\Component\Console\Input\InputArgument;
use WPPayVendor\Symfony\Component\Console\Input\InputInterface;
use WPPayVendor\Symfony\Component\Console\Input\InputOption;
use WPPayVendor\Symfony\Component\Console\Output\OutputInterface;
use WPPayVendor\Symfony\Component\Console\Style\SymfonyStyle;
use WPPayVendor\Symfony\Component\Translation\Catalogue\TargetOperation;
use WPPayVendor\Symfony\Component\Translation\MessageCatalogue;
use WPPayVendor\Symfony\Component\Translation\Provider\TranslationProviderCollection;
use WPPayVendor\Symfony\Component\Translation\Reader\TranslationReaderInterface;
use WPPayVendor\Symfony\Component\Translation\Writer\TranslationWriterInterface;
/**
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 */
final class TranslationPullCommand extends \WPPayVendor\Symfony\Component\Console\Command\Command
{
    use TranslationTrait;
    protected static $defaultName = 'translation:pull';
    protected static $defaultDescription = 'Pull translations from a given provider.';
    private $providerCollection;
    private $writer;
    private $reader;
    private $defaultLocale;
    private $transPaths;
    private $enabledLocales;
    public function __construct(\WPPayVendor\Symfony\Component\Translation\Provider\TranslationProviderCollection $providerCollection, \WPPayVendor\Symfony\Component\Translation\Writer\TranslationWriterInterface $writer, \WPPayVendor\Symfony\Component\Translation\Reader\TranslationReaderInterface $reader, string $defaultLocale, array $transPaths = [], array $enabledLocales = [])
    {
        $this->providerCollection = $providerCollection;
        $this->writer = $writer;
        $this->reader = $reader;
        $this->defaultLocale = $defaultLocale;
        $this->transPaths = $transPaths;
        $this->enabledLocales = $enabledLocales;
        parent::__construct();
    }
    public function complete(\WPPayVendor\Symfony\Component\Console\Completion\CompletionInput $input, \WPPayVendor\Symfony\Component\Console\Completion\CompletionSuggestions $suggestions) : void
    {
        if ($input->mustSuggestArgumentValuesFor('provider')) {
            $suggestions->suggestValues($this->providerCollection->keys());
            return;
        }
        if ($input->mustSuggestOptionValuesFor('domains')) {
            $provider = $this->providerCollection->get($input->getArgument('provider'));
            if ($provider && \method_exists($provider, 'getDomains')) {
                $domains = $provider->getDomains();
                $suggestions->suggestValues($domains);
            }
            return;
        }
        if ($input->mustSuggestOptionValuesFor('locales')) {
            $suggestions->suggestValues($this->enabledLocales);
            return;
        }
        if ($input->mustSuggestOptionValuesFor('format')) {
            $suggestions->suggestValues(['php', 'xlf', 'xlf12', 'xlf20', 'po', 'mo', 'yml', 'yaml', 'ts', 'csv', 'json', 'ini', 'res']);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $keys = $this->providerCollection->keys();
        $defaultProvider = 1 === \count($keys) ? $keys[0] : null;
        $this->setDefinition([new \WPPayVendor\Symfony\Component\Console\Input\InputArgument('provider', null !== $defaultProvider ? \WPPayVendor\Symfony\Component\Console\Input\InputArgument::OPTIONAL : \WPPayVendor\Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The provider to pull translations from.', $defaultProvider), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('force', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Override existing translations with provider ones (it will delete not synchronized messages).'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('intl-icu', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Associated to --force option, it will write messages in "%domain%+intl-icu.%locale%.xlf" files.'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('domains', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL | \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY, 'Specify the domains to pull.'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('locales', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL | \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY, 'Specify the locales to pull.'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('format', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL, 'Override the default output format.', 'xlf12')])->setHelp(<<<'EOF'
The <info>%command.name%</> command pulls translations from the given provider. Only
new translations are pulled, existing ones are not overwritten.

You can overwrite existing translations (and remove the missing ones on local side) by using the <comment>--force</> flag:

  <info>php %command.full_name% --force provider</>

Full example:

  <info>php %command.full_name% provider --force --domains=messages --domains=validators --locales=en</>

This command pulls all translations associated with the <comment>messages</> and <comment>validators</> domains for the <comment>en</> locale.
Local translations for the specified domains and locale are deleted if they're not present on the provider and overwritten if it's the case.
Local translations for others domains and locales are ignored.
EOF
);
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(\WPPayVendor\Symfony\Component\Console\Input\InputInterface $input, \WPPayVendor\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $io = new \WPPayVendor\Symfony\Component\Console\Style\SymfonyStyle($input, $output);
        $provider = $this->providerCollection->get($input->getArgument('provider'));
        $force = $input->getOption('force');
        $intlIcu = $input->getOption('intl-icu');
        $locales = $input->getOption('locales') ?: $this->enabledLocales;
        $domains = $input->getOption('domains');
        $format = $input->getOption('format');
        $xliffVersion = '1.2';
        if ($intlIcu && !$force) {
            $io->note('--intl-icu option only has an effect when used with --force. Here, it will be ignored.');
        }
        switch ($format) {
            case 'xlf20':
                $xliffVersion = '2.0';
            // no break
            case 'xlf12':
                $format = 'xlf';
        }
        $writeOptions = ['path' => \end($this->transPaths), 'xliff_version' => $xliffVersion, 'default_locale' => $this->defaultLocale];
        if (!$domains) {
            $domains = $provider->getDomains();
        }
        $providerTranslations = $provider->read($domains, $locales);
        if ($force) {
            foreach ($providerTranslations->getCatalogues() as $catalogue) {
                $operation = new \WPPayVendor\Symfony\Component\Translation\Catalogue\TargetOperation(new \WPPayVendor\Symfony\Component\Translation\MessageCatalogue($catalogue->getLocale()), $catalogue);
                if ($intlIcu) {
                    $operation->moveMessagesToIntlDomainsIfPossible();
                }
                $this->writer->write($operation->getResult(), $format, $writeOptions);
            }
            $io->success(\sprintf('Local translations has been updated from "%s" (for "%s" locale(s), and "%s" domain(s)).', \parse_url($provider, \PHP_URL_SCHEME), \implode(', ', $locales), \implode(', ', $domains)));
            return 0;
        }
        $localTranslations = $this->readLocalTranslations($locales, $domains, $this->transPaths);
        // Append pulled translations to local ones.
        $localTranslations->addBag($providerTranslations->diff($localTranslations));
        foreach ($localTranslations->getCatalogues() as $catalogue) {
            $this->writer->write($catalogue, $format, $writeOptions);
        }
        $io->success(\sprintf('New translations from "%s" has been written locally (for "%s" locale(s), and "%s" domain(s)).', \parse_url($provider, \PHP_URL_SCHEME), \implode(', ', $locales), \implode(', ', $domains)));
        return 0;
    }
}
