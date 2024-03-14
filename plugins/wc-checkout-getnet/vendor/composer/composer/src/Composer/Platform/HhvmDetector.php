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

namespace CoffeeCode\Composer\Platform;

use CoffeeCode\Composer\Util\Platform;
use CoffeeCode\Composer\Util\ProcessExecutor;
use CoffeeCode\Symfony\Component\Process\ExecutableFinder;

class HhvmDetector
{
    /** @var string|false|null */
    private static $hhvmVersion = null;
    /** @var ?ExecutableFinder */
    private $executableFinder;
    /** @var ?ProcessExecutor */
    private $processExecutor;

    public function __construct(?ExecutableFinder $executableFinder = null, ?ProcessExecutor $processExecutor = null)
    {
        $this->executableFinder = $executableFinder;
        $this->processExecutor = $processExecutor;
    }

    public function reset(): void
    {
        self::$hhvmVersion = null;
    }

    public function getVersion(): ?string
    {
        if (null !== self::$hhvmVersion) {
            return self::$hhvmVersion ?: null;
        }

        self::$hhvmVersion = defined('HHVM_VERSION') ? HHVM_VERSION : null;
        if (self::$hhvmVersion === null && !Platform::isWindows()) {
            self::$hhvmVersion = false;
            $this->executableFinder = $this->executableFinder ?: new ExecutableFinder();
            $hhvmPath = $this->executableFinder->find('hhvm');
            if ($hhvmPath !== null) {
                $this->processExecutor = $this->processExecutor ?? new ProcessExecutor();
                $exitCode = $this->processExecutor->execute(
                    ProcessExecutor::escape($hhvmPath).
                    ' --php -d hhvm.jit=0 -r "echo HHVM_VERSION;" 2>/dev/null',
                    self::$hhvmVersion
                );
                if ($exitCode !== 0) {
                    self::$hhvmVersion = false;
                }
            }
        }

        return self::$hhvmVersion ?: null;
    }
}
