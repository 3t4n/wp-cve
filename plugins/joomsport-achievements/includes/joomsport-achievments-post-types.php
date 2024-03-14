<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */


class JoomSportAchievmentsPostTypes {
    public function __construct() {
        add_action( 'init', array( __CLASS__, 'register_post_types' ), 0 );
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 0 );
        
    }
   

    public static function register_post_types(){
        if ( post_type_exists('joomsport_achievments_season') ) {
            return;
        }
        
        $custom_posts = array(
            "joomsport-achievments-post-season",
            "joomsport-achievments-post-stage",
           // "joomsport-achievments-post-team",
            "joomsport-achievments-post-player",
        );

        foreach ($custom_posts as $cpost) {
            include_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'posts' . DIRECTORY_SEPARATOR . $cpost . '.php';
            $className = str_replace('-', '', $cpost);
            $postObject = new $className();
            $postObject->init();
        }
        flush_rewrite_rules();

    }
    
    public static function register_taxonomies(){
        $custom_taxonomies = array(
            "joomsport-achievments-taxonomy-league"
        );

        foreach ($custom_taxonomies as $ctaxonomy) {
            include_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'taxonomies' . DIRECTORY_SEPARATOR . $ctaxonomy . '.php';
            $className = str_replace('-', '', $ctaxonomy);
            $postObject = new $className();
            $postObject->init();
        }
        flush_rewrite_rules();
    }
 
}
new JoomSportAchievmentsPostTypes();

