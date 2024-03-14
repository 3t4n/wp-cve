<?php

namespace wobef\classes\bootstrap;

use wobef\classes\helpers\Others;

class WOBEF_Verification
{
    public static function is_active()
    {
        if (Others::isAllowedDomain()) {
            return 'yes';
        }

        $is_active = get_option('wobef_is_active', 'no');
        return ($is_active == 'yes' || $is_active == 'skipped');
    }

    public static function skipped()
    {
        $skipped = get_option('wobef_is_active', 'no');
        return $skipped == 'skipped';
    }
}
