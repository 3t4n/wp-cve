<?php

namespace AOP\App;

class User
{
    public static function isAdministrator()
    {
        return current_user_can('manage_options') && in_array('administrator', wp_get_current_user()->roles);
    }

    public static function isEditor()
    {
        return current_user_can('edit_others_posts') && in_array('editor', wp_get_current_user()->roles);
    }

    public static function hasCapability($capability)
    {
        if ($capability === 'edit_others_posts') {
            return self::isEditor() || self::isAdministrator();
        }

        if ($capability === 'manage_options') {
            return self::isAdministrator();
        }

        return false;
    }
}
