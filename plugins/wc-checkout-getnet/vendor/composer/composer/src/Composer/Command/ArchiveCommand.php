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

use CoffeeCode\Composer\Factory;
use CoffeeCode\Composer\IO\IOInterface;
use CoffeeCode\Composer\Config;
use CoffeeCode\Composer\Composer;
use CoffeeCode\Composer\Package\BasePackage;
use CoffeeCode\Composer\Package\CompletePackageInterface;
use CoffeeCode\Composer\Package\Version\VersionParser;
use CoffeeCode\Composer\Package\Version\VersionSelector;
use CoffeeCode\Composer\Pcre\Preg;
use CoffeeCode\Composer\Repository\CompositeRepository;
use CoffeeCode\Composer\Repository\RepositoryFactory;
use CoffeeCode\Composer\Repository\RepositorySet;
use CoffeeCode\Composer\Script\ScriptEvents;
use CoffeeCode\Composer\Plugin\CommandEvent;
use CoffeeCode\Composer\Plugin\PluginEvents;
use CoffeeCode\Composer\Util\Filesystem;
use CoffeeCode\Composer\Util\Loop;
use CoffeeCode\Composer\Util\Platform;
use CoffeeCode\Composer\Util\ProcessExecutor;
use CoffeeCode\Composer\Console\Input\InputArgument;
use CoffeeCode\Symfony\Component\Console\Input\InputInterface;
use CoffeeCode\Composer\Console\Input\InputOption;
use CoffeeCode\Symfony\Component\Console\Output\OutputInterface;

/**
 * Creates an archive of a package for distribution.
 *
 * @author Nils Adermann <naderman@naderman.de>
 */
class ArchiveCommand extends BaseCommand
{
    use CompletionTrait;

    private const FORMATS = ['tar', 'tar.gz', 'tar.bz2', 'zip'];

    protected function configure(): void
    {
        $this
            ->setName('archive')
            ->setDescription('Creates an archive of this composer package')
            ->setDefinition([
                new InputArgument('package', InputArgument::OPTIONAL, 'The package to archive instead of the current project', null, $this->suggestAvailablePackage()),
                new InputArgument('version', InputArgument::OPTIONAL, 'A version constraint to find the package to archive'),
                new InputOption('format', 'f', InputOption::VALUE_REQUIRED, 'Format of the resulting archive: tar, tar.gz, tar.bz2 or zip (default tar)', null, self::FORMATS),
                new InputOption('dir', null, InputOption::VALUE_REQUIRED, 'Write the archive to this directory'),
                new InputOption('file', null, InputOption::VALUE_REQUIRED, 'Write the archive with the given file name.'
                    .' Note that the format will be appended.'),
                new InputOption('ignore-filters', null, InputOption::VALUE_NONE, 'Ignore filters when saving package'),
            ])
            ->setHelp(
                <<<EOT
The <info>archive</info> command creates an archive of the specified format
containing the files and directories of the Composer project or the specified
package in the specified version and writes it to the specified directory.

<info>php composer.phar archive [--format=zip] [--dir=/foo] [--file=filename] [package [version]]</info>

Read more at https://getcomposer.org/doc/03-cli.md#archive
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $composer = $this->tryComposer();
        $config = null;

        if ($composer) {
            $config = $composer->getConfig();
            $commandEvent = new CommandEvent(PluginEvents::COMMAND, 'archive', $input, $output);
            $eventDispatcher = $composer->getEventDispatcher();
            $eventDispatcher->dispatch($commandEvent->getName(), $commandEvent);
            $eventDispatcher->dispatchScript(ScriptEvents::PRE_ARCHIVE_CMD);
        }

        if (!$config) {
            $config = Factory::createConfig();
        }

        $format = $input->getOption('format') ?? $config->get('archive-format');
        $dir = $input->getOption('dir') ?? $config->get('archive-dir');

        $returnCode = $this->archive(
            $this->getIO(),
            $config,
            $input->getArgument('package'),
            $input->getArgument('version'),
            $format,
            $dir,
            $input->getOption('file'),
            $input->getOption('ignore-filters'),
            $composer
        );

        if (0 === $returnCode && $composer) {
            $composer->getEventDispatcher()->dispatchScript(ScriptEvents::POST_ARCHIVE_CMD);
        }

        return $returnCode;
    }

    /**
     * @throws \Exception
     */
    protected function archive(IOInterface $io, Config $config, ?string $packageName, ?string $version, string $format, string $dest, ?string $fileName, bool $ignoreFilters, ?Composer $composer): int
    {
        if ($composer) {
            $archiveManager = $composer->getArchiveManager();
        } else {
            $factory = new Factory;
            $process = new ProcessExecutor();
            $httpDownloader = Factory::createHttpDownloader($io, $config);
            $downloadManager = $factory->createDownloadManager($io, $config, $httpDownloader, $process);
            $archiveManager = $factory->createArchiveManager($config, $downloadManager, new Loop($httpDownloader, $process));
        }

        if ($packageName) {
            $package = $this->selectPackage($io, $packageName, $version);

            if (!$package) {
                return 1;
            }
        } else {
            $package = $this->requireComposer()->getPackage();
        }

        $io->writeError('<info>Creating the archive into "'.$dest.'".</info>');
        $packagePath = $archiveManager->archive($package, $format, $dest, $fileName, $ignoreFilters);
        $fs = new Filesystem;
        $shortPath = $fs->findShortestPath(Platform::getCwd(), $packagePath, true);

        $io->writeError('Created: ', false);
        $io->write(strlen($shortPath) < strlen($packagePath) ? $shortPath : $packagePath);

        return 0;
    }

    /**
     * @return (BasePackage&CompletePackageInterface)|false
     */
    protected function selectPackage(IOInterface $io, string $packageName, ?string $version = null)
    {
        $io->writeError('<info>Searching for the specified package.</info>');

        if ($composer = $this->tryComposer()) {
            $localRepo = $composer->getRepositoryManager()->getLocalRepository();
            $repo = new CompositeRepository(array_merge([$localRepo], $composer->getRepositoryManager()->getRepositories()));
            $minStability = $composer->getPackage()->getMinimumStability();
        } else {
            $defaultRepos = RepositoryFactory::defaultReposWithDefaultManager($io);
            $io->writeError('No composer.json found in the current directory, searching packages from ' . implode(', ', array_keys($defaultRepos)));
            $repo = new CompositeRepository($defaultRepos);
            $minStability = 'stable';
        }

        if ($version !== null && Preg::isMatchStrictGroups('{@(stable|RC|beta|alpha|dev)$}i', $version, $match)) {
            $minStability = $match[1];
            $version = (string) substr($version, 0, -strlen($match[0]));
        }

        $repoSet = new RepositorySet($minStability);
        $repoSet->addRepository($repo);
        $parser = new VersionParser();
        $constraint = $version !== null ? $parser->parseConstraints($version) : null;
        $packages = $repoSet->findPackages(strtolower($packageName), $constraint);

        if (count($packages) > 1) {
            $versionSelector = new VersionSelector($repoSet);
            $package = $versionSelector->findBestCandidate(strtolower($packageName), $version, $minStability);
            if ($package === false) {
                $package = reset($packages);
            }

            $io->writeError('<info>Found multiple matches, selected '.$package->getPrettyString().'.</info>');
            $io->writeError('Alternatives were '.implode(', ', array_map(static function ($p): string {
                return $p->getPrettyString();
            }, $packages)).'.');
            $io->writeError('<comment>Please use a more specific constraint to pick a different package.</comment>');
        } elseif (count($packages) === 1) {
            $package = reset($packages);
            $io->writeError('<info>Found an exact match '.$package->getPrettyString().'.</info>');
        } else {
            $io->writeError('<error>Could not find a package matching '.$packageName.'.</error>');

            return false;
        }

        if (!$package instanceof CompletePackageInterface) {
            throw new \LogicException('Expected a CompletePackageInterface instance but found '.get_class($package));
        }
        if (!$package instanceof BasePackage) {
            throw new \LogicException('Expected a BasePackage instance but found '.get_class($package));
        }

        return $package;
    }
}
