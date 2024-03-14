<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classExtrafieldAchvSelect
{
    public static function getValue($ef)
    {
        global $jsDatabase;
        $query = 'SELECT sel_value FROM '.$jsDatabase->db->jsprtachv_ef_select." WHERE id='".(int) $ef."'";

        return $jsDatabase->selectValue($query);
    }
}
