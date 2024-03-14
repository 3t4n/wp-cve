<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
declare (strict_types=1);
namespace DhlVendor\Ramsey\Uuid;

use DhlVendor\Ramsey\Uuid\Builder\BuilderCollection;
use DhlVendor\Ramsey\Uuid\Builder\FallbackBuilder;
use DhlVendor\Ramsey\Uuid\Builder\UuidBuilderInterface;
use DhlVendor\Ramsey\Uuid\Codec\CodecInterface;
use DhlVendor\Ramsey\Uuid\Codec\GuidStringCodec;
use DhlVendor\Ramsey\Uuid\Codec\StringCodec;
use DhlVendor\Ramsey\Uuid\Converter\Number\GenericNumberConverter;
use DhlVendor\Ramsey\Uuid\Converter\NumberConverterInterface;
use DhlVendor\Ramsey\Uuid\Converter\Time\GenericTimeConverter;
use DhlVendor\Ramsey\Uuid\Converter\Time\PhpTimeConverter;
use DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface;
use DhlVendor\Ramsey\Uuid\Generator\DceSecurityGenerator;
use DhlVendor\Ramsey\Uuid\Generator\DceSecurityGeneratorInterface;
use DhlVendor\Ramsey\Uuid\Generator\NameGeneratorFactory;
use DhlVendor\Ramsey\Uuid\Generator\NameGeneratorInterface;
use DhlVendor\Ramsey\Uuid\Generator\PeclUuidNameGenerator;
use DhlVendor\Ramsey\Uuid\Generator\PeclUuidRandomGenerator;
use DhlVendor\Ramsey\Uuid\Generator\PeclUuidTimeGenerator;
use DhlVendor\Ramsey\Uuid\Generator\RandomGeneratorFactory;
use DhlVendor\Ramsey\Uuid\Generator\RandomGeneratorInterface;
use DhlVendor\Ramsey\Uuid\Generator\TimeGeneratorFactory;
use DhlVendor\Ramsey\Uuid\Generator\TimeGeneratorInterface;
use DhlVendor\Ramsey\Uuid\Guid\GuidBuilder;
use DhlVendor\Ramsey\Uuid\Math\BrickMathCalculator;
use DhlVendor\Ramsey\Uuid\Math\CalculatorInterface;
use DhlVendor\Ramsey\Uuid\Nonstandard\UuidBuilder as NonstandardUuidBuilder;
use DhlVendor\Ramsey\Uuid\Provider\Dce\SystemDceSecurityProvider;
use DhlVendor\Ramsey\Uuid\Provider\DceSecurityProviderInterface;
use DhlVendor\Ramsey\Uuid\Provider\Node\FallbackNodeProvider;
use DhlVendor\Ramsey\Uuid\Provider\Node\NodeProviderCollection;
use DhlVendor\Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use DhlVendor\Ramsey\Uuid\Provider\Node\SystemNodeProvider;
use DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface;
use DhlVendor\Ramsey\Uuid\Provider\Time\SystemTimeProvider;
use DhlVendor\Ramsey\Uuid\Provider\TimeProviderInterface;
use DhlVendor\Ramsey\Uuid\Rfc4122\UuidBuilder as Rfc4122UuidBuilder;
use DhlVendor\Ramsey\Uuid\Validator\GenericValidator;
use DhlVendor\Ramsey\Uuid\Validator\ValidatorInterface;
use const PHP_INT_SIZE;
/**
 * FeatureSet detects and exposes available features in the current environment
 *
 * A feature set is used by UuidFactory to determine the available features and
 * capabilities of the environment.
 */
class FeatureSet
{
    /**
     * @var bool
     */
    private $disableBigNumber = \false;
    /**
     * @var bool
     */
    private $disable64Bit = \false;
    /**
     * @var bool
     */
    private $ignoreSystemNode = \false;
    /**
     * @var bool
     */
    private $enablePecl = \false;
    /**
     * @var UuidBuilderInterface
     */
    private $builder;
    /**
     * @var CodecInterface
     */
    private $codec;
    /**
     * @var DceSecurityGeneratorInterface
     */
    private $dceSecurityGenerator;
    /**
     * @var NameGeneratorInterface
     */
    private $nameGenerator;
    /**
     * @var NodeProviderInterface
     */
    private $nodeProvider;
    /**
     * @var NumberConverterInterface
     */
    private $numberConverter;
    /**
     * @var TimeConverterInterface
     */
    private $timeConverter;
    /**
     * @var RandomGeneratorInterface
     */
    private $randomGenerator;
    /**
     * @var TimeGeneratorInterface
     */
    private $timeGenerator;
    /**
     * @var TimeProviderInterface
     */
    private $timeProvider;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var CalculatorInterface
     */
    private $calculator;
    /**
     * @param bool $useGuids True build UUIDs using the GuidStringCodec
     * @param bool $force32Bit True to force the use of 32-bit functionality
     *     (primarily for testing purposes)
     * @param bool $forceNoBigNumber True to disable the use of moontoast/math
     *     (primarily for testing purposes)
     * @param bool $ignoreSystemNode True to disable attempts to check for the
     *     system node ID (primarily for testing purposes)
     * @param bool $enablePecl True to enable the use of the PeclUuidTimeGenerator
     *     to generate version 1 UUIDs
     */
    public function __construct(bool $useGuids = \false, bool $force32Bit = \false, bool $forceNoBigNumber = \false, bool $ignoreSystemNode = \false, bool $enablePecl = \false)
    {
        $this->disableBigNumber = $forceNoBigNumber;
        $this->disable64Bit = $force32Bit;
        $this->ignoreSystemNode = $ignoreSystemNode;
        $this->enablePecl = $enablePecl;
        $this->setCalculator(new \DhlVendor\Ramsey\Uuid\Math\BrickMathCalculator());
        $this->builder = $this->buildUuidBuilder($useGuids);
        $this->codec = $this->buildCodec($useGuids);
        $this->nodeProvider = $this->buildNodeProvider();
        $this->nameGenerator = $this->buildNameGenerator();
        $this->randomGenerator = $this->buildRandomGenerator();
        $this->setTimeProvider(new \DhlVendor\Ramsey\Uuid\Provider\Time\SystemTimeProvider());
        $this->setDceSecurityProvider(new \DhlVendor\Ramsey\Uuid\Provider\Dce\SystemDceSecurityProvider());
        $this->validator = new \DhlVendor\Ramsey\Uuid\Validator\GenericValidator();
    }
    /**
     * Returns the builder configured for this environment
     */
    public function getBuilder() : \DhlVendor\Ramsey\Uuid\Builder\UuidBuilderInterface
    {
        return $this->builder;
    }
    /**
     * Returns the calculator configured for this environment
     */
    public function getCalculator() : \DhlVendor\Ramsey\Uuid\Math\CalculatorInterface
    {
        return $this->calculator;
    }
    /**
     * Returns the codec configured for this environment
     */
    public function getCodec() : \DhlVendor\Ramsey\Uuid\Codec\CodecInterface
    {
        return $this->codec;
    }
    /**
     * Returns the DCE Security generator configured for this environment
     */
    public function getDceSecurityGenerator() : \DhlVendor\Ramsey\Uuid\Generator\DceSecurityGeneratorInterface
    {
        return $this->dceSecurityGenerator;
    }
    /**
     * Returns the name generator configured for this environment
     */
    public function getNameGenerator() : \DhlVendor\Ramsey\Uuid\Generator\NameGeneratorInterface
    {
        return $this->nameGenerator;
    }
    /**
     * Returns the node provider configured for this environment
     */
    public function getNodeProvider() : \DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface
    {
        return $this->nodeProvider;
    }
    /**
     * Returns the number converter configured for this environment
     */
    public function getNumberConverter() : \DhlVendor\Ramsey\Uuid\Converter\NumberConverterInterface
    {
        return $this->numberConverter;
    }
    /**
     * Returns the random generator configured for this environment
     */
    public function getRandomGenerator() : \DhlVendor\Ramsey\Uuid\Generator\RandomGeneratorInterface
    {
        return $this->randomGenerator;
    }
    /**
     * Returns the time converter configured for this environment
     */
    public function getTimeConverter() : \DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface
    {
        return $this->timeConverter;
    }
    /**
     * Returns the time generator configured for this environment
     */
    public function getTimeGenerator() : \DhlVendor\Ramsey\Uuid\Generator\TimeGeneratorInterface
    {
        return $this->timeGenerator;
    }
    /**
     * Returns the validator configured for this environment
     */
    public function getValidator() : \DhlVendor\Ramsey\Uuid\Validator\ValidatorInterface
    {
        return $this->validator;
    }
    /**
     * Sets the calculator to use in this environment
     */
    public function setCalculator(\DhlVendor\Ramsey\Uuid\Math\CalculatorInterface $calculator) : void
    {
        $this->calculator = $calculator;
        $this->numberConverter = $this->buildNumberConverter($calculator);
        $this->timeConverter = $this->buildTimeConverter($calculator);
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (isset($this->timeProvider)) {
            $this->timeGenerator = $this->buildTimeGenerator($this->timeProvider);
        }
    }
    /**
     * Sets the DCE Security provider to use in this environment
     */
    public function setDceSecurityProvider(\DhlVendor\Ramsey\Uuid\Provider\DceSecurityProviderInterface $dceSecurityProvider) : void
    {
        $this->dceSecurityGenerator = $this->buildDceSecurityGenerator($dceSecurityProvider);
    }
    /**
     * Sets the node provider to use in this environment
     */
    public function setNodeProvider(\DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface $nodeProvider) : void
    {
        $this->nodeProvider = $nodeProvider;
        $this->timeGenerator = $this->buildTimeGenerator($this->timeProvider);
    }
    /**
     * Sets the time provider to use in this environment
     */
    public function setTimeProvider(\DhlVendor\Ramsey\Uuid\Provider\TimeProviderInterface $timeProvider) : void
    {
        $this->timeProvider = $timeProvider;
        $this->timeGenerator = $this->buildTimeGenerator($timeProvider);
    }
    /**
     * Set the validator to use in this environment
     */
    public function setValidator(\DhlVendor\Ramsey\Uuid\Validator\ValidatorInterface $validator) : void
    {
        $this->validator = $validator;
    }
    /**
     * Returns a codec configured for this environment
     *
     * @param bool $useGuids Whether to build UUIDs using the GuidStringCodec
     */
    private function buildCodec(bool $useGuids = \false) : \DhlVendor\Ramsey\Uuid\Codec\CodecInterface
    {
        if ($useGuids) {
            return new \DhlVendor\Ramsey\Uuid\Codec\GuidStringCodec($this->builder);
        }
        return new \DhlVendor\Ramsey\Uuid\Codec\StringCodec($this->builder);
    }
    /**
     * Returns a DCE Security generator configured for this environment
     */
    private function buildDceSecurityGenerator(\DhlVendor\Ramsey\Uuid\Provider\DceSecurityProviderInterface $dceSecurityProvider) : \DhlVendor\Ramsey\Uuid\Generator\DceSecurityGeneratorInterface
    {
        return new \DhlVendor\Ramsey\Uuid\Generator\DceSecurityGenerator($this->numberConverter, $this->timeGenerator, $dceSecurityProvider);
    }
    /**
     * Returns a node provider configured for this environment
     */
    private function buildNodeProvider() : \DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface
    {
        if ($this->ignoreSystemNode) {
            return new \DhlVendor\Ramsey\Uuid\Provider\Node\RandomNodeProvider();
        }
        return new \DhlVendor\Ramsey\Uuid\Provider\Node\FallbackNodeProvider(new \DhlVendor\Ramsey\Uuid\Provider\Node\NodeProviderCollection([new \DhlVendor\Ramsey\Uuid\Provider\Node\SystemNodeProvider(), new \DhlVendor\Ramsey\Uuid\Provider\Node\RandomNodeProvider()]));
    }
    /**
     * Returns a number converter configured for this environment
     */
    private function buildNumberConverter(\DhlVendor\Ramsey\Uuid\Math\CalculatorInterface $calculator) : \DhlVendor\Ramsey\Uuid\Converter\NumberConverterInterface
    {
        return new \DhlVendor\Ramsey\Uuid\Converter\Number\GenericNumberConverter($calculator);
    }
    /**
     * Returns a random generator configured for this environment
     */
    private function buildRandomGenerator() : \DhlVendor\Ramsey\Uuid\Generator\RandomGeneratorInterface
    {
        if ($this->enablePecl) {
            return new \DhlVendor\Ramsey\Uuid\Generator\PeclUuidRandomGenerator();
        }
        return (new \DhlVendor\Ramsey\Uuid\Generator\RandomGeneratorFactory())->getGenerator();
    }
    /**
     * Returns a time generator configured for this environment
     *
     * @param TimeProviderInterface $timeProvider The time provider to use with
     *     the time generator
     */
    private function buildTimeGenerator(\DhlVendor\Ramsey\Uuid\Provider\TimeProviderInterface $timeProvider) : \DhlVendor\Ramsey\Uuid\Generator\TimeGeneratorInterface
    {
        if ($this->enablePecl) {
            return new \DhlVendor\Ramsey\Uuid\Generator\PeclUuidTimeGenerator();
        }
        return (new \DhlVendor\Ramsey\Uuid\Generator\TimeGeneratorFactory($this->nodeProvider, $this->timeConverter, $timeProvider))->getGenerator();
    }
    /**
     * Returns a name generator configured for this environment
     */
    private function buildNameGenerator() : \DhlVendor\Ramsey\Uuid\Generator\NameGeneratorInterface
    {
        if ($this->enablePecl) {
            return new \DhlVendor\Ramsey\Uuid\Generator\PeclUuidNameGenerator();
        }
        return (new \DhlVendor\Ramsey\Uuid\Generator\NameGeneratorFactory())->getGenerator();
    }
    /**
     * Returns a time converter configured for this environment
     */
    private function buildTimeConverter(\DhlVendor\Ramsey\Uuid\Math\CalculatorInterface $calculator) : \DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface
    {
        $genericConverter = new \DhlVendor\Ramsey\Uuid\Converter\Time\GenericTimeConverter($calculator);
        if ($this->is64BitSystem()) {
            return new \DhlVendor\Ramsey\Uuid\Converter\Time\PhpTimeConverter($calculator, $genericConverter);
        }
        return $genericConverter;
    }
    /**
     * Returns a UUID builder configured for this environment
     *
     * @param bool $useGuids Whether to build UUIDs using the GuidStringCodec
     */
    private function buildUuidBuilder(bool $useGuids = \false) : \DhlVendor\Ramsey\Uuid\Builder\UuidBuilderInterface
    {
        if ($useGuids) {
            return new \DhlVendor\Ramsey\Uuid\Guid\GuidBuilder($this->numberConverter, $this->timeConverter);
        }
        /** @psalm-suppress ImpureArgument */
        return new \DhlVendor\Ramsey\Uuid\Builder\FallbackBuilder(new \DhlVendor\Ramsey\Uuid\Builder\BuilderCollection([new \DhlVendor\Ramsey\Uuid\Rfc4122\UuidBuilder($this->numberConverter, $this->timeConverter), new \DhlVendor\Ramsey\Uuid\Nonstandard\UuidBuilder($this->numberConverter, $this->timeConverter)]));
    }
    /**
     * Returns true if the PHP build is 64-bit
     */
    private function is64BitSystem() : bool
    {
        return \PHP_INT_SIZE === 8 && !$this->disable64Bit;
    }
}
