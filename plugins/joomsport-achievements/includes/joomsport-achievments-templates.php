<?php

/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

class JoomsportAchievmentsTemplates {
    
    public static function init() {
        add_filter( 'the_content', array( 'JoomsportAchievmentsTemplates', 'joomsport_content' ) );

    }

    public static function joomsport_content($content){
        if ( !in_the_loop() ) return $content;
        global $controllerAchvSportLeague;
        if(is_singular('jsprt_achv_team')
                || is_singular('jsprt_achv_season')
                || is_singular('jsprt_achv_player')
                || is_singular('jsprt_achv_stage')
               
                ){
            require JOOMSPORT_ACHIEVEMENTS_PATH . 'sportleague' . DIRECTORY_SEPARATOR . 'sportleague.php';
            
            if ( post_password_required() ) {
                echo get_the_password_form();
                return;
            }
            ob_start();
            $controllerAchvSportLeague->execute();
            return ob_get_clean();
            
        }
        return $content;
    }   

    
}


JoomsportAchievmentsTemplates::init();
