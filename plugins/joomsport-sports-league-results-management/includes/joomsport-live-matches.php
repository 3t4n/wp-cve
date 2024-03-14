<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomsportLiveMatches {
    
    public static function init() {
        add_action( 'wp_ajax_joomsport_liveshrtc_reload_matches', array("JoomsportLiveMatches",'jsReloadMatchesAjax') );
        add_action( 'wp_ajax_nopriv_joomsport_liveshrtc_reload_matches', array("JoomsportLiveMatches",'jsReloadMatchesAjax') );

        add_action( 'wp_ajax_joomsport_liveshrtc_favreload', array("JoomsportLiveMatches",'jsReloadFavAjax') );
        add_action( 'wp_ajax_nopriv_joomsport_liveshrtc_favreload', array("JoomsportLiveMatches",'jsReloadFavAjax') );

        add_action( 'wp_ajax_joomsport_liveshrtc_reload', array("JoomsportLiveMatches",'jsReloadLiveAjax') );
        add_action( 'wp_ajax_nopriv_joomsport_liveshrtc_reload', array("JoomsportLiveMatches",'jsReloadLiveAjax') );

    }

    public static function getList(){

        $date = date("Y-m-d");

        $matches = self::getMatches($date);

        return $matches;
    }

    public static function getMatches($date, $played = null, $matches = null){
        global $wpdb;
        require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-match.php';

        $query = "SELECT m.postID AS ID, mdID, seasonID"
            . " FROM {$wpdb->joomsport_matches} as m"
            . " JOIN {$wpdb->posts} as p ON p.ID = m.postID"
            ." WHERE p.post_status='publish'"
            .((isset($matches) && count($matches)>=1)?" AND m.postID IN (".implode(",",$matches).")":"")
            .((isset($played) && $played !== '')?" AND m.status = ".intval($played):"")
            .($date?" AND m.date = '".$date."'":"")
            .' AND (m.teamHomeID > 0 AND m.teamAwayID > 0)'
            ." ORDER BY m.seasonID, m.date, m.time";


        $rows = $wpdb->get_results($query);

        $matches = array();

        if ($rows) {

            foreach ($rows as $row) {
                $match = new classJsportMatch($row->ID, false);
                $match->season_id = $row->seasonID;
                $terms = wp_get_object_terms( $row->seasonID, 'joomsport_tournament' );
                $match->league = '';
                if( $terms ){

                    $match->league .= $terms[0]->name;
                }
                //$match->league = $row->league;
                $matches[] = $match->getRowSimple();
            }
        }
        return $matches;
    }

    public static function jsReloadMatchesAjax(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-match.php';


        $date = addslashes(sanitize_text_field($_REQUEST["jdate"]));
        $played = (isset($_REQUEST['played']) && $_REQUEST['played'] != '')?intval($_REQUEST['played']):'';

        $rows = self::getMatches($date, $played);
        require JOOMSPORT_PATH_VIEWS . 'widgets' . DIRECTORY_SEPARATOR . 'live-matches.php';
        die();
    }

    public static function jsReloadFavAjax(){
        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-match.php';


        $matches = isset($_REQUEST['matches'])?array_map('intval', $_REQUEST['matches']):array();
        $is_fav = true;
        if(count($matches)){
            $rows = self::getMatches('', '', $matches);
            require JOOMSPORT_PATH_VIEWS . 'widgets' . DIRECTORY_SEPARATOR . 'live-matches.php';
        }
        die();
    }

    public static function jsReloadLiveAjax(){

        require_once JOOMSPORT_PATH . DIRECTORY_SEPARATOR. 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
        require_once JOOMSPORT_PATH_OBJECTS.'class-jsport-match.php';

        $matches = isset($_REQUEST['matches'])?array_map('intval', $_REQUEST['matches']):array();


        $return = array();

        if(count($matches)){
            $rows = self::getMatches('', '', $matches);

            if($rows && count($rows)) {
                foreach ($rows as $match) {
                    $return[$match->object->ID] = jsHelper::getScore($match);
                }
            }
        }

        echo (wp_json_encode($return));
        die();
    }

}