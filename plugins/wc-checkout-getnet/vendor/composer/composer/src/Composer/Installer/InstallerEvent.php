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

namespace CoffeeCode\Composer\Installer;

use CoffeeCode\Composer\Composer;
use CoffeeCode\Composer\DependencyResolver\Transaction;
use CoffeeCode\Composer\EventDispatcher\Event;
use CoffeeCode\Composer\IO\IOInterface;

class InstallerEvent extends Event
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var bool
     */
    private $devMode;

    /**
     * @var bool
     */
    private $executeOperations;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * Constructor.
     */
    public function __construct(string $eventName, Composer $composer, IOInterface $io, bool $devMode, bool $executeOperations, Transaction $transaction)
    {
        parent::__construct($eventName);

        $this->composer = $composer;
        $this->io = $io;
        $this->devMode = $devMode;
        $this->executeOperations = $executeOperations;
        $this->transaction = $transaction;
    }

    public function getComposer(): Composer
    {
        return $this->composer;
    }

    public function getIO(): IOInterface
    {
        return $this->io;
    }

    public function isDevMode(): bool
    {
        return $this->devMode;
    }

    public function isExecutingOperations(): bool
    {
        return $this->executeOperations;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }
}
