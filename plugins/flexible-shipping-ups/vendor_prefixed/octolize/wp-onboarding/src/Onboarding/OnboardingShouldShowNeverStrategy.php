<?php

/**
 * @package Octolize\Onboarding
 */
namespace UpsFreeVendor\Octolize\Onboarding;

/**
 * Never display strategy.
 */
class OnboardingShouldShowNeverStrategy implements \UpsFreeVendor\Octolize\Onboarding\OnboardingShouldShowStrategy
{
    public function should_display() : bool
    {
        return \false;
    }
}
