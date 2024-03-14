<?php

namespace wobel\classes\bootstrap;

use wobel\classes\helpers\Others;

class WOBEL_Verification
{
    public static function is_active()
    {
        if (Others::isAllowedDomain()) {
            return 'yes';
        }

        $is_active = get_option('wobel_is_active', 'no');
        return ($is_active == 'yes' || $is_active == 'skipped');
    }

    public static function skipped()
    {
        $skipped = get_option('wobel_is_active', 'no');
        return $skipped == 'skipped';
    }
}
