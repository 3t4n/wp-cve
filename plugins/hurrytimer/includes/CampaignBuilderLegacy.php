<?php
/**
 * This trait provide legacy classes for campaign.
 */

namespace Hurrytimer;

trait CampaignBuilderLegacy
{
    private function legacyLabelClass()
    {
        return Installer::get_instance()->should_backward_compat_2_2_28_and_prior() ? 'hurrytimer-cdt__label' : '';
    }

    private function legacyDigitClass()
    {
        return Installer::get_instance()->should_backward_compat_2_2_28_and_prior() ? 'hurrytimer-cdt__time' : '';

    }


    private function legacyBlockClass()
    {

        return Installer::get_instance()->should_backward_compat_2_2_28_and_prior() ? 'hurrytimer-cdt__dur' : '';

    }

    private
    function legacyHeadlineClass()
    {
        return Installer::get_instance()->should_backward_compat_2_2_28_and_prior() ? 'hurrytimer-cdt__headline' : '';

    }

    private function legacyTimerClass()
    {
        return Installer::get_instance()->should_backward_compat_2_2_28_and_prior() ? 'hurrytimer-cdt__inner' : '';

    }


    private function legacySeparatorClass()
    {
        return Installer::get_instance()->should_backward_compat_2_2_28_and_prior() ? 'hurrytimer-cdt__sep' : '';
    }

    function legacyCampaignClass( $id )
    {
        return Installer::get_instance()->should_backward_compat_2_2_28_and_prior() ? "hurrytimer-cdt hurrytimer-cdt--{$id}" : '';
    }
}
