<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class IfwPsn_Vendor_Twig_Profiler_Dumper_Text extends IfwPsn_Vendor_Twig_Profiler_Dumper_Base
{
    protected function formatTemplate(IfwPsn_Vendor_Twig_Profiler_Profile $profile, $prefix)
    {
        return sprintf('%s└ %s', $prefix, $profile->getTemplate());
    }

    protected function formatNonTemplate(IfwPsn_Vendor_Twig_Profiler_Profile $profile, $prefix)
    {
        return sprintf('%s└ %s::%s(%s)', $prefix, $profile->getTemplate(), $profile->getType(), $profile->getName());
    }

    protected function formatTime(IfwPsn_Vendor_Twig_Profiler_Profile $profile, $percent)
    {
        return sprintf('%.2fms/%.0f%%', $profile->getDuration() * 1000, $percent);
    }
}

//class_alias('IfwPsn_Vendor_Twig_Profiler_Dumper_Text', 'Twig\Profiler\Dumper\TextDumper', false);
