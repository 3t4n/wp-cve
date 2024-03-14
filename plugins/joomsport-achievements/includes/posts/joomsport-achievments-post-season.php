<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-achievments-meta-season.php';


class JoomSportAchievmentsPostSeason {
    public function __construct() {

    }
    public static function init(){
        self::register_post_types();
    }
    public static function register_post_types(){
        add_action("admin_init", array("JoomSportAchievmentsPostSeason","admin_init"));
        add_action( 'edit_form_after_title',  array( 'JoomSportAchievmentsPostSeason','season_edit_form_after_title') );
        
    
        register_post_type( 'jsprt_achv_season',
                apply_filters( 'joomsport_achievments_register_post_type_season',
                        array(
                                'labels'              => array(
                                                'name'               => __( 'Season', 'joomsport-achievements' ),
                                                'singular_name'      => __( 'Season', 'joomsport-achievements' ),
                                                'menu_name'          => _x( 'Seasons', 'Admin menu name Seasons', 'joomsport-achievements' ),
                                                'add_new'            => __( 'Add Season', 'joomsport-achievements' ),
                                                'add_new_item'       => __( 'Add New Season', 'joomsport-achievements' ),
                                                'edit'               => __( 'Edit', 'joomsport-achievements' ),
                                                'edit_item'          => __( 'Edit Season', 'joomsport-achievements' ),
                                                'new_item'           => __( 'New Season', 'joomsport-achievements' ),
                                                'view'               => __( 'View Season', 'joomsport-achievements' ),
                                                'view_item'          => __( 'View Season', 'joomsport-achievements' ),
                                                'search_items'       => __( 'Search Season', 'joomsport-achievements' ),
                                                'not_found'          => __( 'No Season found', 'joomsport-achievements' ),
                                                'not_found_in_trash' => __( 'No Season found in trash', 'joomsport-achievements' ),
                                                'parent'             => __( 'Parent Season', 'joomsport-achievements' )
                                        ),
                                'description'         => __( 'This is where you can add new season.', 'joomsport-achievements' ),
                                'public'              => true,
                                'show_ui'             => true,
                                'show_in_menu'        => 'joomsport_achievments',
                                'publicly_queryable'  => true,
                                'exclude_from_search' => false,
                                'hierarchical'        => true,
                                'query_var'           => true,
                                'supports'            => array( 'title','page-attributes','thumbnail'),
                                'show_in_nav_menus'   => true
                        )
                )
        );
         
    }
    
    public static function season_edit_form_after_title($post_type){
        global $post, $wp_meta_boxes;

        if($post_type->post_type == 'jsprt_achv_season'){
            
            echo JoomSportAchievmentsMetaSeason::output($post_type);

        }
    

    }
    public static function admin_init(){
        add_meta_box('joomsport_achievments_season_about_form_meta_box', __('About season', 'joomsport-achievements'), array('JoomSportAchievmentsMetaSeason','js_meta_about'), 'jsprt_achv_season', 'joomsportachvintab_season1', 'default');
        add_meta_box('joomsport_achievments_season_ef_form_meta_box', __('Extra fields', 'joomsport-achievements'), array('JoomSportAchievmentsMetaSeason','js_meta_ef'), 'jsprt_achv_season', 'joomsportachvintab_season1', 'default');
        add_meta_box('joomsport_achievments_season_ranking_box', __('Stage Ranking', 'joomsport-achievements'), array('JoomSportAchievmentsMetaSeason','js_meta_ranking'), 'jsprt_achv_season', 'joomsportachvintab_season1', 'default');
        add_meta_box('joomsport_achievments_season_points_box', __('Season Ranking', 'joomsport-achievements'), array('JoomSportAchievmentsMetaSeason','js_meta_points'), 'jsprt_achv_season', 'joomsportachvintab_season1', 'default');
        
        add_action( 'save_post',      array( 'JoomSportAchievmentsMetaSeason', 'joomsport_season_save_metabox' ), 10, 2 );
    }
    
   

}    



add_action( 'wp_trash_post', 'jsachievments_to_run_on_post_trash' );

function jsachievments_to_run_on_post_trash( $post_id ){
    $childs = get_posts(
            array(
                'post_parent' => $post_id,
                'post_type' => 'joomsport_achievments_season'
            ) 
    );

    if(empty($childs))
        return;

    foreach($childs as $post){
        wp_trash_post($post->ID);
    }

}
