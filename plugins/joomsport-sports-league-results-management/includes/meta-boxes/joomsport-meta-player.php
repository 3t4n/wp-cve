<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomSportMetaPlayer {
    public static function output( $post ) {
        global $post, $thepostid, $wp_meta_boxes;
        
        
        $thepostid = $post->ID;

        require_once JOOMSPORT_PATH_HELPERS . 'tabs.php';
        $etabs = new esTabs();
        wp_nonce_field( 'joomsport_player_savemetaboxes', 'joomsport_player_nonce' );
        ?>
        <div id="joomsportContainerBE">
            <div class="jsBEsettings" style="padding:0px;">
                <!-- <tab box> -->
                <ul class="tab-box">
                    <?php
                    echo ($etabs->newTab(__('Main','joomsport-sports-league-results-management'), 'main_conf', '', 'vis'));
                    if(JoomSportUserRights::isAdmin()){
                        echo ($etabs->newTab(__('Season related settings','joomsport-sports-league-results-management'), 'col_conf', ''));
                    }
                    do_action("joomsport_custom_tab_be_head", $thepostid, $etabs);

                    ?>
                </ul>	
                <div style="clear:both"></div>
            </div>
            <div id="main_conf_div" class="tabdiv">
                <div>
                    <div>
                        <?php
                        do_meta_boxes(get_current_screen(), 'joomsportintab_player1', $post);
                        unset($wp_meta_boxes[get_post_type($post)]['joomsportintab_player1']);
                        ?>

                    </div>    
                </div>
            </div>   
            <?php
            if(JoomSportUserRights::isAdmin()){
            ?>
            <div id="col_conf_div" class="tabdiv visuallyhidden">
                <div style="margin-bottom: 25px;margin-left:10px;">
                    <?php
                    $results = JoomSportHelperObjects::getSeasons(-1,false);
                    echo __('Select Season', 'joomsport-sports-league-results-management').'&nbsp;&nbsp;';
                    if(!empty($results)){
                        echo wp_kses(JoomSportHelperSelectBox::Optgroup('spb_season_id', $results, ''), JoomsportSettings::getKsesSelect());
                    }else{
                        echo '<div style="color:red;">'.__('Participant is not assigned to any season. Open Main tab and use Assign to season field.', 'joomsport-sports-league-results-management').'</div>';
                    }
                    
                    ?>
                </div>
                <div>
                    <?php
                    do_meta_boxes(get_current_screen(), 'joomsportintab_player2', $post);
                    unset($wp_meta_boxes[get_post_type($post)]['joomsportintab_player2']);
                    ?>
                </div>    
            </div>
            <?php } ?>
            <?php
            do_action("joomsport_custom_tab_be_body", $thepostid, $etabs);
            ?>
        </div>
        <?php
    }
        
        
    public static function js_meta_personal($post){

        $metadata = get_post_meta($post->ID,'_joomsport_player_personal',true);
        $selected_user = (int) get_post_meta($post->ID,'_joomsport_player_user',true);
        global $wpdb;

        ?>
        <div class="jsminwdhtd jstable">
            <div class="jstable-row">
                <div class="jstable-cell">
                    <?php echo __('First name', 'joomsport-sports-league-results-management');?>
                </div>
                <div class="jstable-cell">
                    <input type="text" class="form-control" name="personal[first_name]" value="<?php echo isset($metadata['first_name'])?esc_attr($metadata['first_name']):""?>" />
                </div>
            </div>
            <div class="jstable-row">
                <div class="jstable-cell">
                    <?php echo __('Last name', 'joomsport-sports-league-results-management');?>
                </div>
                <div class="jstable-cell">
                    <input type="text" class="form-control" name="personal[last_name]" value="<?php echo isset($metadata['last_name'])?esc_attr($metadata['last_name']):""?>" />
                </div>
            </div>
            <div class="jstable-row">
                <div class="jstable-cell">
                    <?php echo __('Short name', 'joomsport-sports-league-results-management');?>
                </div>
                <div class="jstable-cell">
                    <input type="text" class="form-control" name="personal[short_name]" value="<?php echo isset($metadata['short_name'])?esc_attr($metadata['short_name']):""?>" />
                </div>
            </div>
            <div class="jstable-row">
                <div class="jstable-cell">
                    <?php echo __('Name in home country', 'joomsport-sports-league-results-management');?>
                </div>
                <div class="jstable-cell">
                    <input type="text" class="form-control" name="personal[home_name]" value="<?php echo isset($metadata['home_name'])?esc_attr($metadata['home_name']):""?>" />
                </div>
            </div>
            <?php
            if(JoomSportUserRights::isAdmin()){
                /*
                $query = "SELECT ID FROM {$wpdb->prefix}users as u"
                . " JOIN {$wpdb->prefix}postmeta as pm ON pm.meta_key = '_joomsport_player_user' AND pm.meta_value = u.ID"
                . " WHERE u.ID != {$selected_user}";
                $exclude_users = $wpdb->get_col($query);
                $args = array(
                    'show_option_all'         => null, // string
                    'show_option_none'        => __('Not connected', 'joomsport-sports-league-results-management'), // string
                    'hide_if_only_one_author' => null, // string
                    'orderby'                 => 'display_name',
                    'order'                   => 'ASC',
                    'include'                 => null, // string
                    'exclude'                 => $exclude_users, // string
                    'multi'                   => false,
                    'show'                    => 'display_name',
                    'echo'                    => true,
                    'selected'                => $selected_user,
                    'include_selected'        => false,
                    'name'                    => 'wp_user', // string

                ); 
                ?> 
                <div class="jstable-row">
                    <div class="jstable-cell">
                        <?php echo __('Connected user', 'joomsport-sports-league-results-management');?>
                    </div>
                    <div class="jstable-cell">
                        <?php if(!wp_dropdown_users( $args )){
                            echo __('No users to connect', 'joomsport-sports-league-results-management');
                        } ?> 
                    </div>
                </div>
                <?php
                 
                 */
            }?>
            </div>
            <?php
            
    }
    public static function js_meta_about($post){

        $metadata = get_post_meta($post->ID,'_joomsport_player_about',true);
        wp_editor($metadata, 'about',array("textarea_rows"=>3));


    }

    public static function js_meta_ef($post){

        $metadata = get_post_meta($post->ID,'_joomsport_player_ef',true);
        
        $efields = JoomSportHelperEF::getEFList('0', 0);

        if(count($efields)){
            echo '<div class="jsminwdhtd jstable">';
            foreach ($efields as $ef) {

                JoomSportHelperEF::getEFInput($ef, isset($metadata[$ef->id])?$metadata[$ef->id]:null);
                //var_dump($ef);
                ?>
                
                <div class="jstable-row">
                    <div class="jstable-cell"><?php echo esc_html($ef->name)?></div>
                    <div class="jstable-cell">
                        <?php 
                        if($ef->field_type == '2'){
                            wp_editor(isset($metadata[$ef->id])?$metadata[$ef->id]:'', 'ef_'.esc_attr($ef->id),array("textarea_rows"=>3));
                            echo '<input type="hidden" name="ef['.esc_attr($ef->id).']" value="ef_'.esc_attr($ef->id).'" />';
                        }else{
                            echo ($ef->edit);
                        }
                        ?>
                    </div>    
                        
                </div>    
                <?php
            }
            echo '</div>';
        }else{
            $link = get_admin_url(get_current_blog_id(), 'admin.php?page=joomsport-page-extrafields');
             printf( __( 'There are no extra fields assigned to this section. Create new one on %s Extra fields list %s', 'joomsport-sports-league-results-management' ), '<a href="'.$link.'">','</a>' );

        }
        
    }

    public static function js_meta_teams($post){
        ?>
            <div id="js_player_teamsDIV">
                <?php echo __('No season selected','joomsport-sports-league-results-management');?>
            </div>
        <?php
    }
    public static function js_meta_bonuses($post){
        ?>
            <div id="js_player_bonusesDIV">
                <?php echo __('No season selected','joomsport-sports-league-results-management');?>
            </div>
        <?php
    }
    public static function js_meta_ef_assigned($post){
        ?>
            <div id="js_player_efassignedDIV">
                <?php echo __('No season selected','joomsport-sports-league-results-management');?>
            </div>
        <?php
    }
    public static function js_meta_jersey($post){
        ?>
            <div id="js_player_jerseyDIV">
                <?php echo __('No season selected','joomsport-sports-league-results-management');?>
            </div>
        <?php
    }

    public static function joomsport_player_save_metabox($post_id, $post){
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['joomsport_player_nonce'] ) ? sanitize_text_field($_POST['joomsport_player_nonce']) : '';
        $nonce_action = 'joomsport_player_savemetaboxes';
 
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
        
        if('joomsport_player' == $_POST['post_type'] ){
            self::saveMetaPersonal($post_id, $_POST);
            self::saveMetaAbout($post_id);
            self::saveMetaEF($post_id, $_POST);
            if(JoomSportUserRights::isAdmin()){
                self::saveMetaTeams($post_id);
                self::saveMetaBonuses($post_id);
                self::saveMetaEFAssigned($post_id);
                self::saveMetaJersey($post_id);
            }
            do_action("joomsport_custom_tab_be_save", $post_id);
        }
    }
    
    public static function saveMetaPersonal($post_id, $post){
        $meta_array = array();
        $meta_array = isset($post['personal'])?  ($post['personal']):'';
        $meta_array = array_map( 'sanitize_text_field', wp_unslash( $post['personal'] ) );
        update_post_meta($post_id, '_joomsport_player_personal', $meta_array);
        
        /*if(JoomSportUserRights::isAdmin()){
            $userid = isset($_POST['wp_user'])?  intval($_POST['wp_user']):0;
            update_post_meta($post_id, '_joomsport_player_user', $userid);
        }*/
    }
    
    private static function saveMetaJersey($post_id){
        global $wpdb;
        $season_id = isset($_POST['spb_season_id'])?  intval($_POST['spb_season_id']):0;
        if($season_id){
            $wpdb->query("DELETE FROM {$wpdb->joomsport_teamplayers} WHERE seasonID = {$season_id} AND playerID = {$post_id}");

        }
        if(isset($_POST['jersey'])){
            $meta_array = array_map( 'sanitize_text_field', wp_unslash( $_POST['jersey'] ) );
            if($meta_array && count($meta_array) && $season_id){
                foreach($meta_array as $key=>$val){
                    if(isset($_POST["departed"][$key])){
                        $wpdb->query("UPDATE {$wpdb->joomsport_playerlist} SET departed='1' WHERE season_id={$season_id} AND team_id={$key} AND player_id={$post_id}");
                        $wpdb->query("INSERT IGNORE INTO {$wpdb->joomsport_teamplayers}(id,seasonID,teamID,playerID,departed) VALUES(0,{$season_id},{$key},{$post_id},'1')");
                    }else{
                        $wpdb->query("UPDATE {$wpdb->joomsport_playerlist} SET departed='0' WHERE season_id={$season_id} AND team_id={$key} AND player_id={$post_id}");

                    }
                }
            }
        }else{
            $meta_array = array();
        }
        if($season_id){
            update_post_meta($post_id, '_joomsport_player_jersey_'.$season_id, $meta_array);
        }
        
    }
    private static function saveMetaAbout($post_id){
        $meta_data = isset($_POST['about'])?  wp_kses_post($_POST['about']):'';
        update_post_meta($post_id, '_joomsport_player_about', $meta_data);
    }
    public static function saveMetaEF($post_id, $post){
        $meta_array = array();
        if(isset($post['ef']) && count($post['ef'])){
            foreach ($post['ef'] as $key => $value){
                if(isset($post['ef_'.$key])){
                    $meta_array[$key] = sanitize_text_field($post['ef_'.$key]);
                }else{
                    $meta_array[$key] = sanitize_text_field($value);
                }
            }
        }
        //$meta_data = serialize($meta_array);
        update_post_meta($post_id, '_joomsport_player_ef', $meta_array);
    }
    private static function saveMetaTeams($post_id){
        $season_id = isset($_POST['spb_season_id'])?  intval($_POST['spb_season_id']):0;
        
        if($season_id){
            $teams_id = isset($_POST['teams_id'])?  array_map( 'intval', $_POST['teams_id']):array();
            $teamsin = JoomSportHelperObjects::getPlayerTeams($season_id, $post_id);
            for($intA = 0; $intA < count($teamsin); $intA++){
                $playersin = get_post_meta($teamsin[$intA],'_joomsport_team_players_'.$season_id,true);
                if(!$teams_id || !in_array($teamsin[$intA], $teams_id)){
                    $playersin = array_diff($playersin, array($post_id));
                    update_post_meta($teamsin[$intA], '_joomsport_team_players_'.$season_id, $playersin);
                }
            }
            for($intA = 0; $intA < count($teams_id); $intA++){
                
                $playersin = get_post_meta($teams_id[$intA],'_joomsport_team_players_'.$season_id,true);
                if(!$playersin){
                    $playersin = array();
                }
                if(!$playersin ||  ($playersin && !in_array($post_id, $playersin))){
                    $playersin[] = $post_id;
                    update_post_meta($teams_id[$intA], '_joomsport_team_players_'.$season_id, $playersin);
                }
            }
            do_action('joomsport_update_playerlist', $season_id, $teams_id);
            //update_post_meta($post_id, '_joomsport_team_players_'.$season_id, $players_id);
        }
    }
    private static function saveMetaBonuses($post_id){
        $season_id = isset($_POST['spb_season_id'])?  intval($_POST['spb_season_id']):0;
        if($season_id){
            $bonuses = isset($_POST['js_bonuses'])?  floatval($_POST['js_bonuses']):'0';
            update_post_meta($post_id, '_joomsport_team_bonuses_'.$season_id, $bonuses);
        }
    }
    private static function saveMetaEFAssigned($post_id){
        $season_id = isset($_POST['spb_season_id'])?  intval($_POST['spb_season_id']):0;
        $meta_array = array();
        if($season_id){
            if(isset($_POST['efs']) && count($_POST['efs'])){
                foreach ($_POST['efs'] as $key => $value){
                    if(isset($_POST['efs_'.$key])){
                        $meta_array[$key] =sanitize_text_field($_POST['efs_'.$key]);
                    }else{
                        $meta_array[$key] = sanitize_text_field($value);
                    }
                }
            }
            //$meta_data = serialize($meta_array);
            update_post_meta($post_id, '_joomsport_player_ef_'.$season_id, $meta_array);
        }
        
        
    }
}
