<?php

function eic_vp_dep_boolean_inverse($value)
{
    $args   = func_get_args();
    $result = true;
    foreach ($args as $val)
    {
        $result = ($result and !empty($val));
    }
    return !$result;
}

function eic_admin_premium_not_installed()
{
    return !EasyImageCollage::is_premium_active();
}


function eic_admin_premium_installed()
{
    return EasyImageCollage::is_premium_active();
}

VP_Security::instance()->whitelist_function('eic_vp_dep_boolean_inverse');
VP_Security::instance()->whitelist_function('eic_admin_premium_not_installed');
VP_Security::instance()->whitelist_function('eic_admin_premium_installed');