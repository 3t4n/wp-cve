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
use WPPayVendor\Symfony\Component\Console\Exception\InvalidArgumentException;
use WPPayVendor\Symfony\Component\Console\Input\InputArgument;
use WPPayVendor\Symfony\Component\Console\Input\InputInterface;
use WPPayVendor\Symfony\Component\Console\Input\InputOption;
use WPPayVendor\Symfony\Component\Console\Output\OutputInterface;
use WPPayVendor\Symfony\Component\Console\Style\SymfonyStyle;
use WPPayVendor\Symfony\Component\Translation\Provider\FilteringProvider;
use WPPayVendor\Symfony\Component\Translation\Provider\TranslationProviderCollection;
use WPPayVendor\Symfony\Component\Translation\Reader\TranslationReaderInterface;
use WPPayVendor\Symfony\Component\Translation\TranslatorBag;
/**
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 */
final class TranslationPushCommand extends \WPPayVendor\Symfony\Component\Console\Command\Command
{
    use TranslationTrait;
    protected static $defaultName = 'translation:push';
    protected static $defaultDescription = 'Push translations to a given provider.';
    private $providers;
    private $reader;
    private $transPaths;
    private $enabledLocales;
    public function __construct(\WPPayVendor\Symfony\Component\Translation\Provider\TranslationProviderCollection $providers, \WPPayVendor\Symfony\Component\Translation\Reader\TranslationReaderInterface $reader, array $transPaths = [], array $enabledLocales = [])
    {
        $this->providers = $providers;
        $this->reader = $reader;
        $this->transPaths = $transPaths;
        $this->enabledLocales = $enabledLocales;
        parent::__construct();
    }
    public function complete(\WPPayVendor\Symfony\Component\Console\Completion\CompletionInput $input, \WPPayVendor\Symfony\Component\Console\Completion\CompletionSuggestions $suggestions) : void
    {
        if ($input->mustSuggestArgumentValuesFor('provider')) {
            $suggestions->suggestValues($this->providers->keys());
            return;
        }
        if ($input->mustSuggestOptionValuesFor('domains')) {
            $provider = $this->providers->get($input->getArgument('provider'));
            if ($provider && \method_exists($provider, 'getDomains')) {
                $domains = $provider->getDomains();
                $suggestions->suggestValues($domains);
            }
            return;
        }
        if ($input->mustSuggestOptionValuesFor('locales')) {
            $suggestions->suggestValues($this->enabledLocales);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $keys = $this->providers->keys();
        $defaultProvider = 1 === \count($keys) ? $keys[0] : null;
        $this->setDefinition([new \WPPayVendor\Symfony\Component\Console\Input\InputArgument('provider', null !== $defaultProvider ? \WPPayVendor\Symfony\Component\Console\Input\InputArgument::OPTIONAL : \WPPayVendor\Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The provider to push translations to.', $defaultProvider), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('force', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Override existing translations with local ones (it will delete not synchronized messages).'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('delete-missing', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Delete translations available on provider but not locally.'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('domains', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL | \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY, 'Specify the domains to push.'), new \WPPayVendor\Symfony\Component\Console\Input\InputOption('locales', null, \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL | \WPPayVendor\Symfony\Component\Console\Input\InputOption::VALUE_IS_ARRAY, 'Specify the locales to push.', $this->enabledLocales)])->setHelp(<<<'EOF'
The <info>%command.name%</> command pushes translations to the given provider. Only new
translations are pushed, existing ones are not overwritten.

You can overwrite existing translations by using the <comment>--force</> flag:

  <info>php %command.full_name% --force provider</>

You can delete provider translations which are not present locally by using the <comment>--delete-missing</> flag:

  <info>php %command.full_name% --delete-missing provider</>

Full example:

  <info>php %command.full_name% provider --force --delete-missing --domains=messages --domains=validators --locales=en</>

This command pushes all translations associated with the <comment>messages</> and <comment>validators</> domains for the <comment>en</> locale.
Provider translations for the specified domains and locale are deleted if they're not present locally and overwritten if it's the case.
Provider translations for others domains and locales are ignored.
EOF
);
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(\WPPayVendor\Symfony\Component\Console\Input\InputInterface $input, \WPPayVendor\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $provider = $this->providers->get($input->getArgument('provider'));
        if (!$this->enabledLocales) {
            throw new \WPPayVendor\Symfony\Component\Console\Exception\InvalidArgumentException(\sprintf('You must define "framework.translator.enabled_locales" or "framework.translator.providers.%s.locales" config key in order to work with translation providers.', \parse_url($provider, \PHP_URL_SCHEME)));
        }
        $io = new \WPPayVendor\Symfony\Component\Console\Style\SymfonyStyle($input, $output);
        $domains = $input->getOption('domains');
        $locales = $input->getOption('locales');
        $force = $input->getOption('force');
        $deleteMissing = $input->getOption('delete-missing');
        if (!$domains && $provider instanceof \WPPayVendor\Symfony\Component\Translation\Provider\FilteringProvider) {
            $domains = $provider->getDomains();
        }
        // Reading local translations must be done after retrieving the domains from the provider
        // in order to manage only translations from configured domains
        $localTranslations = $this->readLocalTranslations($locales, $domains, $this->transPaths);
        if (!$domains) {
            $domains = $this->getDomainsFromTranslatorBag($localTranslations);
        }
        if (!$deleteMissing && $force) {
            $provider->write($localTranslations);
            $io->success(\sprintf('All local translations has been sent to "%s" (for "%s" locale(s), and "%s" domain(s)).', \parse_url($provider, \PHP_URL_SCHEME), \implode(', ', $locales), \implode(', ', $domains)));
            return 0;
        }
        $providerTranslations = $provider->read($domains, $locales);
        if ($deleteMissing) {
            $provider->delete($providerTranslations->diff($localTranslations));
            $io->success(\sprintf('Missing translations on "%s" has been deleted (for "%s" locale(s), and "%s" domain(s)).', \parse_url($provider, \PHP_URL_SCHEME), \implode(', ', $locales), \implode(', ', $domains)));
            // Read provider translations again, after missing translations deletion,
            // to avoid push freshly deleted translations.
            $providerTranslations = $provider->read($domains, $locales);
        }
        $translationsToWrite = $localTranslations->diff($providerTranslations);
        if ($force) {
            $translationsToWrite->addBag($localTranslations->intersect($providerTranslations));
        }
        $provider->write($translationsToWrite);
        $io->success(\sprintf('%s local translations has been sent to "%s" (for "%s" locale(s), and "%s" domain(s)).', $force ? 'All' : 'New', \parse_url($provider, \PHP_URL_SCHEME), \implode(', ', $locales), \implode(', ', $domains)));
        return 0;
    }
    private function getDomainsFromTranslatorBag(\WPPayVendor\Symfony\Component\Translation\TranslatorBag $translatorBag) : array
    {
        $domains = [];
        foreach ($translatorBag->getCatalogues() as $catalogue) {
            $domains += $catalogue->getDomains();
        }
        return \array_unique($domains);
    }
}
