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

namespace CoffeeCode\Composer\Util;

use CoffeeCode\Composer\Config;
use CoffeeCode\Composer\IO\IOInterface;
use CoffeeCode\Composer\Pcre\Preg;

/**
 * @author Jonas Renaudot <jonas.renaudot@gmail.com>
 */
class Hg
{
    /** @var string|false|null */
    private static $version = false;

    /**
     * @var \CoffeeCode\Composer\IO\IOInterface
     */
    private $io;

    /**
     * @var \CoffeeCode\Composer\Config
     */
    private $config;

    /**
     * @var \CoffeeCode\Composer\Util\ProcessExecutor
     */
    private $process;

    public function __construct(IOInterface $io, Config $config, ProcessExecutor $process)
    {
        $this->io = $io;
        $this->config = $config;
        $this->process = $process;
    }

    public function runCommand(callable $commandCallable, string $url, ?string $cwd): void
    {
        $this->config->prohibitUrlByConfig($url, $this->io);

        // Try as is
        $command = $commandCallable($url);

        if (0 === $this->process->execute($command, $ignoredOutput, $cwd)) {
            return;
        }

        // Try with the authentication information available
        if (Preg::isMatch('{^(https?)://((.+)(?:\:(.+))?@)?([^/]+)(/.*)?}mi', $url, $match) && $this->io->hasAuthentication((string) $match[5])) {
            $auth = $this->io->getAuthentication((string) $match[5]);
            $authenticatedUrl = $match[1] . '://' . rawurlencode($auth['username']) . ':' . rawurlencode($auth['password']) . '@' . $match[5] . $match[6];

            $command = $commandCallable($authenticatedUrl);

            if (0 === $this->process->execute($command, $ignoredOutput, $cwd)) {
                return;
            }

            $error = $this->process->getErrorOutput();
        } else {
            $error = 'The given URL (' . $url . ') does not match the required format (http(s)://(username:password@)example.com/path-to-repository)';
        }

        $this->throwException('Failed to clone ' . $url . ', ' . "\n\n" . $error, $url);
    }

    /**
     * @param non-empty-string $message
     *
     * @return never
     */
    private function throwException($message, string $url): void
    {
        if (null === self::getVersion($this->process)) {
            throw new \RuntimeException(Url::sanitize('Failed to clone ' . $url . ', hg was not found, check that it is installed and in your PATH env.' . "\n\n" . $this->process->getErrorOutput()));
        }

        throw new \RuntimeException(Url::sanitize($message));
    }

    /**
     * Retrieves the current hg version.
     *
     * @return string|null The hg version number, if present.
     */
    public static function getVersion(ProcessExecutor $process): ?string
    {
        if (false === self::$version) {
            self::$version = null;
            if (0 === $process->execute('hg --version', $output) && Preg::isMatch('/^.+? (\d+(?:\.\d+)+)(?:\+.*?)?\)?\r?\n/', $output, $matches)) {
                self::$version = $matches[1];
            }
        }

        return self::$version;
    }
}
