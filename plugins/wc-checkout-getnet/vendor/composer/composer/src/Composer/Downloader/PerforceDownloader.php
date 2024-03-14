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

namespace CoffeeCode\Composer\Downloader;

use CoffeeCode\React\Promise\PromiseInterface;
use CoffeeCode\Composer\Package\PackageInterface;
use CoffeeCode\Composer\Repository\VcsRepository;
use CoffeeCode\Composer\Util\Perforce;

/**
 * @author Matt Whittom <Matt.Whittom@veteransunited.com>
 */
class PerforceDownloader extends VcsDownloader
{
    /** @var Perforce|null */
    protected $perforce;

    /**
     * @inheritDoc
     */
    protected function doDownload(PackageInterface $package, string $path, string $url, ?PackageInterface $prevPackage = null): PromiseInterface
    {
        return \CoffeeCode\React\Promise\resolve(null);
    }

    /**
     * @inheritDoc
     */
    public function doInstall(PackageInterface $package, string $path, string $url): PromiseInterface
    {
        $ref = $package->getSourceReference();
        $label = $this->getLabelFromSourceReference((string) $ref);

        $this->io->writeError('Cloning ' . $ref);
        $this->initPerforce($package, $path, $url);
        $this->perforce->setStream($ref);
        $this->perforce->p4Login();
        $this->perforce->writeP4ClientSpec();
        $this->perforce->connectClient();
        $this->perforce->syncCodeBase($label);
        $this->perforce->cleanupClientSpec();

        return \CoffeeCode\React\Promise\resolve(null);
    }

    private function getLabelFromSourceReference(string $ref): ?string
    {
        $pos = strpos($ref, '@');
        if (false !== $pos) {
            return substr($ref, $pos + 1);
        }

        return null;
    }

    public function initPerforce(PackageInterface $package, string $path, string $url): void
    {
        if (!empty($this->perforce)) {
            $this->perforce->initializePath($path);

            return;
        }

        $repository = $package->getRepository();
        $repoConfig = null;
        if ($repository instanceof VcsRepository) {
            $repoConfig = $this->getRepoConfig($repository);
        }
        $this->perforce = Perforce::create($repoConfig, $url, $path, $this->process, $this->io);
    }

    /**
     * @return array<string, mixed>
     */
    private function getRepoConfig(VcsRepository $repository): array
    {
        return $repository->getRepoConfig();
    }

    /**
     * @inheritDoc
     */
    protected function doUpdate(PackageInterface $initial, PackageInterface $target, string $path, string $url): PromiseInterface
    {
        return $this->doInstall($target, $path, $url);
    }

    /**
     * @inheritDoc
     */
    public function getLocalChanges(PackageInterface $package, string $path): ?string
    {
        $this->io->writeError('Perforce driver does not check for local changes before overriding');

        return null;
    }

    /**
     * @inheritDoc
     */
    protected function getCommitLogs(string $fromReference, string $toReference, string $path): string
    {
        return $this->perforce->getCommitLogs($fromReference, $toReference);
    }

    public function setPerforce(Perforce $perforce): void
    {
        $this->perforce = $perforce;
    }

    /**
     * @inheritDoc
     */
    protected function hasMetadataRepository(string $path): bool
    {
        return true;
    }
}
