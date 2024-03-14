<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
require_once JOOMSPORT_ACHIEVEMENTS_PATH_INCLUDES . 'meta-boxes' . DIRECTORY_SEPARATOR . 'joomsport-achievments-meta-stage.php';


class JoomSportAchievmentsPostStage {
    public function __construct() {

    }
    public static function init(){
        self::register_post_types();
    }
    public static function register_post_types(){
        add_action("admin_init", array("JoomSportAchievmentsPostStage","admin_init"));
        add_action( 'wp_ajax_stageadf_filters', array("JoomSportAchievmentsPostStage",'joomsport_stageadf_filters') );
        add_action( 'wp_ajax_achvstage_seasonmodal', array("JoomSportAchievmentsPostStage",'joomsport_achvstage_seasonmodal') );
        
        add_action( 'edit_form_after_title',  array( 'JoomSportAchievmentsPostStage','match_edit_form_after_title') );
        register_post_type( 'jsprt_achv_stage',
                apply_filters( 'joomsport_achievement_register_post_type_stage',
                        array(
                                'labels'              => array(
                                                'name'               => __( 'Stage', 'joomsport-achievements' ),
                                                'singular_name'      => __( 'Stage', 'joomsport-achievements' ),
                                                'menu_name'          => _x( 'Stages', 'Admin menu name Matches', 'joomsport-achievements' ),
                                                'add_new'            => __( 'Add Stage', 'joomsport-achievements' ),
                                                'add_new_item'       => __( 'Add New Stage', 'joomsport-achievements' ),
                                                'edit'               => __( 'Edit', 'joomsport-achievements' ),
                                                'edit_item'          => __( 'Edit Stage', 'joomsport-achievements' ),
                                                'new_item'           => __( 'New Stage', 'joomsport-achievements' ),
                                                'view'               => __( 'View Stage', 'joomsport-achievements' ),
                                                'view_item'          => __( 'View Stage', 'joomsport-achievements' ),
                                                'search_items'       => __( 'Search Stage', 'joomsport-achievements' ),
                                                'not_found'          => __( 'No Stage found', 'joomsport-achievements' ),
                                                'not_found_in_trash' => __( 'No Stage found in trash', 'joomsport-achievements' ),
                                                'parent'             => __( 'Parent Stage', 'joomsport-achievements' )
                                        ),
                                'description'         => __( 'This is where you can add new stage.', 'joomsport-achievements' ),
                                'public'              => true,
                                'show_ui'             => true,
                                'show_in_menu'        => 'joomsport_achievments',
                                'publicly_queryable'  => true,
                                'exclude_from_search' => false,
                                'hierarchical'        => true,
                                'query_var'           => true,
                                'supports'            => array( 'title','thumbnail'),
                                'show_in_nav_menus'   => false
                                
                        )
                )
        );


       
    }

    public static function match_edit_form_after_title($post_type){
        global $post, $wp_meta_boxes;

        if($post_type->post_type == 'jsprt_achv_stage'){
            
            echo JoomSportAchievmentsMetaStage::output($post_type);

        }
    

    }
    public static function admin_init(){
        add_meta_box('joomsport_achievments_stage_about_form_meta_box', __('About stage', 'joomsport-achievements'), array('JoomSportAchievmentsMetaStage','js_meta_about'), 'jsprt_achv_stage', 'joomsportachvintab_stage1', 'default');
        add_meta_box('joomsport_achievments_stage_ef_form_meta_box', __('Extra fields', 'joomsport-achievements'), array('JoomSportAchievmentsMetaStage','js_meta_ef'), 'jsprt_achv_stage', 'joomsportachvintab_stage1', 'default');
        add_meta_box('joomsport_achievments_stage_result_box', __('Results', 'joomsport-achievements'), array('JoomSportAchievmentsMetaStage','js_meta_results'), 'jsprt_achv_stage', 'joomsportachvintab_stage1', 'default');
        add_meta_box('joomsport_achievments_stage_season', __('Season', 'joomsport-achievements'), array('JoomSportAchievmentsMetaStage','js_meta_season'), 'jsprt_achv_stage', 'side');
        add_meta_box('joomsport_achievments_stage_categories', __('Stage categories', 'joomsport-achievements'), array('JoomSportAchievmentsMetaStage','js_meta_categories'), 'jsprt_achv_stage', 'side');
        add_meta_box('joomsport_achievments_stage_dates', __('Date & Time', 'joomsport-achievements'), array('JoomSportAchievmentsMetaStage','js_meta_date'), 'jsprt_achv_stage', 'side');
        
        add_action( 'save_post',      array( 'JoomSportAchievmentsMetaStage', 'joomsport_stage_save_metabox' ), 10, 2 );
    
    
    }
    
    public static function joomsport_stageadf_filters(){
        $ef = filter_input(INPUT_POST, 'jsfilters');
        $ef = json_decode($ef, true);
        
        $args = array(
            'posts_per_page' => -1,
            'offset'           => 0,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => 'jsprt_achv_player',
            'post_status'      => 'publish',

        );
        $posts_array = $posts_array_filtered = get_posts( $args );

        if($ef && count($ef)){
            for($intA = 0; $intA < count($posts_array); $intA++){
                $filtered = true;
                $metadata = get_post_meta($posts_array[$intA]->ID,'_jsprt_achv_player_ef',true);

                foreach ($ef as $key => $value) {
                    $keyIndex = substr($key, 3);
                    
                    if($ef[$key] != $metadata[$keyIndex]){
                        $filtered = false;
                    }
                }
                if(!$filtered){
                    unset($posts_array_filtered[$intA]);
                }
            }
        }
        if(count($posts_array_filtered)){
            foreach ($posts_array_filtered as $option) {
                echo '<option value="'.$option->ID.'">'.$option->post_title.'</option>';
            }
        }
        exit();
    }
    
    
    public static function joomsport_achvstage_seasonmodal(){
        
        ?>
            <table>    
                <tr>
                    <td>
                        <label><?php echo  __("Season", "joomsport-achievements");?> *</label>
                    </td>
                    <?php
                    
                    echo '<td>';
                    $args = array(
                        'post_type' => 'jsprt_achv_season',
                        'name'=>'season_id'
                    );
                    wp_dropdown_pages($args);
                    ?>
                    </td>
                </tr>
                
            </table>    
        <?php
        wp_die();
    }
}    