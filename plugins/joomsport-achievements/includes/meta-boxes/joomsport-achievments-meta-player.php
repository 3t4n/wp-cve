<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomSportAchievmentsMetaPlayer {
    public static function output( $post ) {
        global $post, $thepostid, $wp_meta_boxes;
        
        
        $thepostid = $post->ID;

        wp_nonce_field( 'joomsport_achievments_player_savemetaboxes', 'joomsport_achievments_player_nonce' );
        ?>
        <div id="joomsportAchvContainerBE">
            
            <div id="main_conf_div" class="tabdiv">
                <div>
                    <div>
                        <?php
                        do_meta_boxes(get_current_screen(), 'joomsportachvintab_player1', $post);
                        unset($wp_meta_boxes[get_post_type($post)]['joomsportachvintab_player1']);
                        ?>

                    </div>    
                </div>
            </div>   
            
        </div>
        <?php
        
    }
        
        
    
    public static function js_meta_about($post){

        $metadata = get_post_meta($post->ID,'_jsprt_achv_player_about',true);
        echo wp_editor($metadata, 'about',array("textarea_rows"=>3));


    }
    
    public static function js_meta_country($post){
        global $wpdb;
        $metadata = get_post_meta($post->ID,'_jsprt_achv_player_country',true);
        $countries = $wpdb->get_results('SELECT id, country as name FROM '.$wpdb->jsprtachv_country.'  ORDER BY country', 'OBJECT');
        
        echo JoomSportAchievmentsHelperSelectBox::Simple('jsprt_achv_player_country', $countries, $metadata);
        
    }
    
    /*<!--jsonlyinproPHP-->*/
    public static function js_meta_ef($post){

        $metadata = get_post_meta($post->ID,'_jsprt_achv_player_ef',true);
        
        $efields = JoomSportAchievmentsHelperEF::getEFList('0', 0);

        if(count($efields)){
            echo '<div class="jsminwdhtd jstable"  style="width:auto;">';
            foreach ($efields as $ef) {

                JoomSportAchievmentsHelperEF::getEFInput($ef, isset($metadata[$ef->id])?$metadata[$ef->id]:null);
                //var_dump($ef);
                ?>
                
                <div class="jstable-row">
                    <div class="jstable-cell"><?php echo $ef->name?></div>
                    <div class="jstable-cell">
                        <?php 
                        if($ef->field_type == '2'){
                            wp_editor(isset($metadata[$ef->id])?$metadata[$ef->id]:'', 'ef_'.$ef->id,array("textarea_rows"=>3));
                            echo '<input type="hidden" name="ef['.$ef->id.']" value="ef_'.$ef->id.'" />';
                        }else{
                            echo $ef->edit;
                        }
                        ?>
                    </div>    
                        
                </div>    
                <?php
            }
            echo '</div>';
        }else{
            $link = get_admin_url(get_current_blog_id(), 'admin.php?page=joomsport-achievments-page-extrafields');
             printf( __( 'There are no extra fields assigned to this section. Create new one on %s Extra fields list %s', 'joomsport-achievements' ), '<a href="'.$link.'">','</a>' );

        }

    }
    

    public static function joomsport_player_save_metabox($post_id, $post){
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['joomsport_achievments_player_nonce'] ) ? $_POST['joomsport_achievments_player_nonce'] : '';
        $nonce_action = 'joomsport_achievments_player_savemetaboxes';
 
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
        
        if('jsprt_achv_player' == $_POST['post_type'] ){
            
            self::saveMetaAbout($post_id);
            self::saveMetaCountry($post_id);
            self::saveMetaEF($post_id);

        }
    }
    

    private static function saveMetaAbout($post_id){
        $meta_data = isset($_POST['about'])?  wp_kses_post($_POST['about']):'';
        update_post_meta($post_id, '_jsprt_achv_player_about', $meta_data);
    }

    private static function saveMetaEF($post_id){
        $meta_array = array();
        if(isset($_POST['ef']) && count($_POST['ef'])){
            foreach ($_POST['ef'] as $key => $value){
                if(isset($_POST['ef_'.$key])){
                    $meta_array[$key] = sanitize_text_field($_POST['ef_'.$key]);
                }else{
                    $meta_array[$key] = sanitize_text_field($value);
                }
            }
        }
        //$meta_data = serialize($meta_array);
        update_post_meta($post_id, '_jsprt_achv_player_ef', $meta_array);
    }
    private static function saveMetaCountry($post_id){
        $meta_data = isset($_POST['jsprt_achv_player_country'])?  intval($_POST['jsprt_achv_player_country']):'';
        update_post_meta($post_id, '_jsprt_achv_player_country', $meta_data);
    }
}
