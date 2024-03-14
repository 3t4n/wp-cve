<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classExtrafieldAchvRadio
{
    public static function getValue($ef)
    {
        $html = '';
        if ($ef != '') {
            $html = $ef ? __('Yes','joomsport-achievements') : __('No','joomsport-achievements');
        }

        return $html;
    }
}
