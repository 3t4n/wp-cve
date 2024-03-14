<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CoffeeCode\Composer\Command;

use CoffeeCode\Composer\Package\CompletePackageInterface;
use CoffeeCode\Composer\Repository\RepositoryInterface;
use CoffeeCode\Composer\Repository\RootPackageRepository;
use CoffeeCode\Composer\Repository\RepositoryFactory;
use CoffeeCode\Composer\Util\Platform;
use CoffeeCode\Composer\Util\ProcessExecutor;
use CoffeeCode\Composer\Console\Input\InputArgument;
use CoffeeCode\Composer\Console\Input\InputOption;
use CoffeeCode\Symfony\Component\Console\Input\InputInterface;
use CoffeeCode\Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class HomeCommand extends BaseCommand
{
    use CompletionTrait;

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName('browse')
            ->setAliases(['home'])
            ->setDescription('Opens the package\'s repository URL or homepage in your browser')
            ->setDefinition([
                new InputArgument('packages', InputArgument::IS_ARRAY, 'Package(s) to browse to.', null, $this->suggestInstalledPackage()),
                new InputOption('homepage', 'H', InputOption::VALUE_NONE, 'Open the homepage instead of the repository URL.'),
                new InputOption('show', 's', InputOption::VALUE_NONE, 'Only show the homepage or repository URL.'),
            ])
            ->setHelp(
                <<<EOT
The home command opens or shows a package's repository URL or
homepage in your default browser.

To open the homepage by default, use -H or --homepage.
To show instead of open the repository or homepage URL, use -s or --show.

Read more at https://getcomposer.org/doc/03-cli.md#browse-home
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repos = $this->initializeRepos();
        $io = $this->getIO();
        $return = 0;

        $packages = $input->getArgument('packages');
        if (count($packages) === 0) {
            $io->writeError('No package specified, opening homepage for the root package');
            $packages = [$this->requireComposer()->getPackage()->getName()];
        }

        foreach ($packages as $packageName) {
            $handled = false;
            $packageExists = false;
            foreach ($repos as $repo) {
                foreach ($repo->findPackages($packageName) as $package) {
                    $packageExists = true;
                    if ($package instanceof CompletePackageInterface && $this->handlePackage($package, $input->getOption('homepage'), $input->getOption('show'))) {
                        $handled = true;
                        break 2;
                    }
                }
            }

            if (!$packageExists) {
                $return = 1;
                $io->writeError('<warning>Package '.$packageName.' not found</warning>');
            }

            if (!$handled) {
                $return = 1;
                $io->writeError('<warning>'.($input->getOption('homepage') ? 'Invalid or missing homepage' : 'Invalid or missing repository URL').' for '.$packageName.'</warning>');
            }
        }

        return $return;
    }

    private function handlePackage(CompletePackageInterface $package, bool $showHomepage, bool $showOnly): bool
    {
        $support = $package->getSupport();
        $url = $support['source'] ?? $package->getSourceUrl();
        if (!$url || $showHomepage) {
            $url = $package->getHomepage();
        }

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        if ($showOnly) {
            $this->getIO()->write(sprintf('<info>%s</info>', $url));
        } else {
            $this->openBrowser($url);
        }

        return true;
    }

    /**
     * opens a url in your system default browser
     */
    private function openBrowser(string $url): void
    {
        $url = ProcessExecutor::escape($url);

        $process = new ProcessExecutor($this->getIO());
        if (Platform::isWindows()) {
            $process->execute('start "web" explorer ' . $url, $output);

            return;
        }

        $linux = $process->execute('which xdg-open', $output);
        $osx = $process->execute('which open', $output);

        if (0 === $linux) {
            $process->execute('xdg-open ' . $url, $output);
        } elseif (0 === $osx) {
            $process->execute('open ' . $url, $output);
        } else {
            $this->getIO()->writeError('No suitable browser opening command found, open yourself: ' . $url);
        }
    }

    /**
     * Initializes repositories
     *
     * Returns an array of repos in order they should be checked in
     *
     * @return RepositoryInterface[]
     */
    private function initializeRepos(): array
    {
        $composer = $this->tryComposer();

        if ($composer) {
            return array_merge(
                [new RootPackageRepository(clone $composer->getPackage())], // root package
                [$composer->getRepositoryManager()->getLocalRepository()], // installed packages
                $composer->getRepositoryManager()->getRepositories() // remotes
            );
        }

        return RepositoryFactory::defaultReposWithDefaultManager($this->getIO());
    }
}
