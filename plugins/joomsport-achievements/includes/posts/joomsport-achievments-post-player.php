<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-achievments-meta-player.php';

class JoomSportAchievmentsPostPlayer {
    public function __construct() {

    }
    public static function init(){
        self::register_post_types();
    }
    public static function register_post_types(){
        add_action("admin_init", array("JoomSportAchievmentsPostPlayer","admin_init"));
        add_action( 'edit_form_after_title',  array( 'JoomSportAchievmentsPostPlayer','player_edit_form_after_title') );
        
        register_post_type( 'jsprt_achv_player',
                apply_filters( 'joomsport_achievments_register_post_type_player',
                        array(
                                'labels'              => array(
                                                'name'               => __( 'Player', 'joomsport-achievements' ),
                                                'singular_name'      => __( 'Player', 'joomsport-achievements' ),
                                                'menu_name'          => _x( 'Players', 'Admin menu name Players', 'joomsport-achievements' ),
                                                'add_new'            => __( 'Add Player', 'joomsport-achievements' ),
                                                'add_new_item'       => __( 'Add New Player', 'joomsport-achievements' ),
                                                'edit'               => __( 'Edit', 'joomsport-achievements' ),
                                                'edit_item'          => __( 'Edit Player', 'joomsport-achievements' ),
                                                'new_item'           => __( 'New Player', 'joomsport-achievements' ),
                                                'view'               => __( 'View Player', 'joomsport-achievements' ),
                                                'view_item'          => __( 'View Player', 'joomsport-achievements' ),
                                                'search_items'       => __( 'Search Player', 'joomsport-achievements' ),
                                                'not_found'          => __( 'No Player found', 'joomsport-achievements' ),
                                                'not_found_in_trash' => __( 'No Player found in trash', 'joomsport-achievements' ),
                                                'parent'             => __( 'Parent Player', 'joomsport-achievements' )
                                        ),
                                'description'         => __( 'This is where you can add new player.', 'joomsport-achievements' ),
                                'public'              => true,
                                'show_ui'             => true,
                                'show_in_menu'        => 'joomsport_achievments',
                                'publicly_queryable'  => true,
                                'exclude_from_search' => true,
                                'hierarchical'        => false,
                                'query_var'           => true,
                                'supports'            => array( 'title' ),
                                'show_in_nav_menus'   => true
                        )
                )
        );
    }
    public static function player_edit_form_after_title($post_type){
        global $post, $wp_meta_boxes;

        if($post_type->post_type == 'jsprt_achv_player'){
            
            echo JoomSportAchievmentsMetaPlayer::output($post_type);

        }
    

    }
    public static function admin_init(){
        add_meta_box('joomsport_achievments_player_about_form_meta_box', __('About player', 'joomsport-achievements'), array('JoomSportAchievmentsMetaPlayer','js_meta_about'), 'jsprt_achv_player', 'joomsportachvintab_player1', 'default');
        add_meta_box('joomsport_achievments_player_ef_form_meta_box', __('Extra fields', 'joomsport-achievements'), array('JoomSportAchievmentsMetaPlayer','js_meta_ef'), 'jsprt_achv_player', 'joomsportachvintab_player1', 'default');
        add_meta_box('joomsport_achievments_player_country', __('Country', 'joomsport-achievements'), array('JoomSportAchievmentsMetaPlayer','js_meta_country'), 'jsprt_achv_player', 'side', 'low');
        
        add_action( 'save_post',      array( 'JoomSportAchievmentsMetaPlayer', 'joomsport_player_save_metabox' ), 10, 2 );
    
    }
    
    
}    