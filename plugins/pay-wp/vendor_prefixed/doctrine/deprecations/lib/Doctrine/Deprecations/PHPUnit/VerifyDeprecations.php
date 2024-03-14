<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Deprecations\PHPUnit;

use WPPayVendor\Doctrine\Deprecations\Deprecation;
use function sprintf;
trait VerifyDeprecations
{
    /** @var array<string,int> */
    private $doctrineDeprecationsExpectations = [];
    /** @var array<string,int> */
    private $doctrineNoDeprecationsExpectations = [];
    public function expectDeprecationWithIdentifier(string $identifier) : void
    {
        $this->doctrineDeprecationsExpectations[$identifier] = \WPPayVendor\Doctrine\Deprecations\Deprecation::getTriggeredDeprecations()[$identifier] ?? 0;
    }
    public function expectNoDeprecationWithIdentifier(string $identifier) : void
    {
        $this->doctrineNoDeprecationsExpectations[$identifier] = \WPPayVendor\Doctrine\Deprecations\Deprecation::getTriggeredDeprecations()[$identifier] ?? 0;
    }
    /**
     * @before
     */
    public function enableDeprecationTracking() : void
    {
        \WPPayVendor\Doctrine\Deprecations\Deprecation::enableTrackingDeprecations();
    }
    /**
     * @after
     */
    public function verifyDeprecationsAreTriggered() : void
    {
        foreach ($this->doctrineDeprecationsExpectations as $identifier => $expectation) {
            $actualCount = \WPPayVendor\Doctrine\Deprecations\Deprecation::getTriggeredDeprecations()[$identifier] ?? 0;
            $this->assertTrue($actualCount > $expectation, \sprintf("Expected deprecation with identifier '%s' was not triggered by code executed in test.", $identifier));
        }
        foreach ($this->doctrineNoDeprecationsExpectations as $identifier => $expectation) {
            $actualCount = \WPPayVendor\Doctrine\Deprecations\Deprecation::getTriggeredDeprecations()[$identifier] ?? 0;
            $this->assertTrue($actualCount === $expectation, \sprintf("Expected deprecation with identifier '%s' was triggered by code executed in test, but expected not to.", $identifier));
        }
    }
}
