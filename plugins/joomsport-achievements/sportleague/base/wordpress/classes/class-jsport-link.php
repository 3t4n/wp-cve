<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class classJsportAchvLink
{
    public static function season($text, $season_id, $onlylink = false, $Itemid = '', $linkable = true)
    {
        if (!$Itemid) {
            $Itemid = self::getItemId();
        }
        
        $link = get_permalink($season_id);
        if ($onlylink) {
            return $link;
        }

        return '<a href="'.$link.'">'.$text.'</a>';
    }
    public static function calendar($text, $season_id, $onlylink = false, $Itemid = '', $linkable = true)
    {
        if (!$Itemid) {
            $Itemid = self::getItemId();
        }
        $link = get_permalink($season_id);
        $link = add_query_arg( 'action', 'calendar', $link );
        if ($onlylink) {
            return $link;
        }

        return '<a href="'.$link.'">'.$text.'</a>';
    }
    public static function tournament($text, $tournament_id, $onlylink = false, $Itemid = '', $linkable = true)
    {
        if (!$Itemid) {
            $Itemid = self::getItemId();
        }

    }
    public static function team($text, $team_id, $season_id = 0, $onlylink = false, $Itemid = '', $linkable = true)
    {
        if (!$Itemid) {
            $Itemid = self::getItemId();
        }
        $link = get_permalink($team_id);
        if($season_id){
            $link = add_query_arg( 'sid', $season_id, $link );
        }
        if ($onlylink) {
            return $link;
        }

        return '<a href="'.$link.'">'.$text.'</a>';
    }
    
    public static function player($text, $player_id, $season_id = 0, $onlylink = false, $Itemid = '', $linkable = true)
    {
        if (!$Itemid) {
            $Itemid = self::getItemId();
        }
        $link = get_permalink($player_id);
        if($season_id){
            $link = add_query_arg( 'sid', $season_id, $link );
        }
        if ($onlylink) {
            return $link;
        }

        return '<a href="'.$link.'">'.$text.'</a>';
    }
    public static function stage($text, $stage_id, $onlylink = false, $Itemid = '', $linkable = true)
    {
        if (!$Itemid) {
            $Itemid = self::getItemId();
        }
        
        $link = get_permalink($stage_id);
        if ($onlylink) {
            return $link;
        }

        return '<a href="'.$link.'">'.$text.'</a>';
    }
    public static function getItemId()
    {

        return 0;
    }
}
