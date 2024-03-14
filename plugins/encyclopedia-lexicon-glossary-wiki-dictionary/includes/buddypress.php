<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class BuddyPress
{
    public static function init(): void
    {
        add_filter('bp_get_activity_content_body', [TypeConverter::class, 'convertToString']);
        add_filter('bp_get_activity_content_body', [Core::class, 'addCrossLinks']);
    }
}

BuddyPress::init();
