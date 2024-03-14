<?php

/**
 * @package Octolize\Onboarding
 */
namespace UpsFreeVendor\Octolize\Onboarding;

/**
 * Always display strategy.
 */
class OnboardingShouldShowAlwaysStrategy implements \UpsFreeVendor\Octolize\Onboarding\OnboardingShouldShowStrategy
{
    public function should_display() : bool
    {
        return \true;
    }
}
