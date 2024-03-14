<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomSportAchievmentsMetaSeason {
    public static function output( $post ) {
        global $post, $thepostid, $wp_meta_boxes;
        
        
        $thepostid = $post->ID;

        wp_nonce_field( 'joomsport_achievments_season_savemetaboxes', 'joomsport_achievments_season_nonce' );
        ?>
        <div id="joomsportAchvContainerBE">
            
            <div id="main_conf_div" class="tabdiv">
                <div>
                    <div>
                        <?php
                        do_meta_boxes(get_current_screen(), 'joomsportachvintab_season1', $post);
                        unset($wp_meta_boxes[get_post_type($post)]['joomsportachvintab_season1']);
                        ?>

                    </div>    
                </div>
            </div>   
            
        </div>
        <?php
    }
        
        
    
    public static function js_meta_about($post){

        $metadata = get_post_meta($post->ID,'_jsprt_achv_season_about',true);
        echo wp_editor($metadata, 'about',array("textarea_rows"=>3));


    }
    public static function js_meta_ranking($post){
        $metadata = get_post_meta($post->ID,'_jsprt_achv_season_ranking',true);
        global $wpdb;
        $value=0;
        $sql = "SELECT * FROM {$wpdb->jsprtachv_results_fields} ORDER BY ordering";
        $resultfields = $wpdb->get_results($sql);
        if(count($resultfields)){
            echo JoomSportAchievmentsHelperSelectBox::Simple('sRanking', $resultfields,(isset($metadata['sRanking'])?$metadata['sRanking']:0),' ',true);

            $sortway[] = JoomSportAchievmentsHelperSelectBox::addOption(0, __('Desc', 'joomsport-achievements'));
            $sortway[] = JoomSportAchievmentsHelperSelectBox::addOption(1, __('Asc', 'joomsport-achievements'));    
            echo JoomSportAchievmentsHelperSelectBox::Simple('sRankingWay', $sortway,(isset($metadata['sRankingWay'])?$metadata['sRankingWay']:0),' ',false);
        }else{
            echo sprintf(__("There are no Result Fields available. Create new one on %sResult Fields list%s","joomsport-achievements"),'<a href="'.get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-page-resfields').'">','</a>');
        }
        
    }
    public static function js_meta_points($post){
        global $wpdb;
        $metadata = get_post_meta($post->ID,'_jsprt_achv_season_points',true);
        $pts[0] = new stdClass();
        $pts[0]->name = __("Points", "joomsport-achievements");
        $pts[0]->id = 0;
        
        $sql = "SELECT id,name FROM {$wpdb->jsprtachv_results_fields} ORDER BY ordering";
        $resultfields = $wpdb->get_results($sql);
        if(count($resultfields)){
            $pts = array_merge($pts, $resultfields);
        }
        $method = array();
        $method[] = JoomSportAchievmentsHelperSelectBox::addOption("0",__('Min', 'joomsport-achievements'));
        $method[] = JoomSportAchievmentsHelperSelectBox::addOption("1",__('Max', 'joomsport-achievements'));
        $method[] = JoomSportAchievmentsHelperSelectBox::addOption("2",__('Sum Desc', 'joomsport-achievements'));
        $method[] = JoomSportAchievmentsHelperSelectBox::addOption("3",__('Sum Asc', 'joomsport-achievements'));
        
        ?>
        <script>
            function addAchvPoints(){
                if(jQuery("#achv_place").val() && jQuery("#achv_points").val()){
                    var tr = jQuery("<tr>");
                    tr.append('<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo __('Delete', 'joomsport-achievements');?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>');
                    tr.append('<td><input type="text" name="achv_place_res[]" value="'+jQuery("#achv_place").val()+'" /></td>');
                    tr.append('<td><input type="text" name="achv_points_res[]" value="'+jQuery("#achv_points").val()+'" /></td>');
                    jQuery('#tblAchvPointsTable').append(tr);
                    jQuery("#achv_place").val("");
                    jQuery("#achv_points").val("");
                }
            }
        </script>    
        <table class="jsach_ptscfg">
            <tr>
                <td>
                    <?php echo __('Rank by', 'joomsport-achievements');?>

                </td>
                <td>
                    <?php 
                    echo JoomSportAchievmentsHelperSelectBox::Simple('seasonRanking', $pts,isset($metadata['ranking_criteria'])?$metadata['ranking_criteria']:0,' ',false);
                    ?>
                </td>
            </tr>    
        </table>
        <table class="jsach_method jsach_ptscfg">
            <tr>
                <td>
                    <?php echo __('Ranking method', 'joomsport-achievements');?>

                </td>
                <td>
                    <?php 
                    echo JoomSportAchievmentsHelperSelectBox::Radio('seasonRankingMethod', $method, isset($metadata['ranking_method'])?$metadata['ranking_method']:0);
                    ?>
                </td>
            </tr>    
        </table>
        <table class="tblAchvPoints">
            <thead>
                <tr>
                    <th width="50"></th>
                    <th>
                        <?php echo __('Place', 'joomsport-achievements');?>
                    </th>
                    <th>
                        <?php echo __('Points', 'joomsport-achievements');?>
                    </th>
                </tr>
            </thead>
            <tbody id="tblAchvPointsTable">
                <?php
                if(isset($metadata['pts_by_place']) && count($metadata['pts_by_place'])){
                    foreach($metadata['pts_by_place'] as $key=>$value){

                        ?>
                        <tr>
                            <td>
                                <a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo __('Delete', 'joomsport-achievements');?>">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                            <td>
                                <input type="text" name="achv_place_res[]" value="<?php echo $key;?>" />
                            </td>
                            <td>
                                <input type="text" name="achv_points_res[]" value="<?php echo $value;?>" />
                            </td>

                        <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>

                    </td>
                    <td>
                        <input type="text" class="text" name="achv_place" id="achv_place" value="" />
                    </td>
                    <td>
                        <input type="text" class="text" name="achv_points" id="achv_points" value="" />
                        <input type="button" class="button" style="margin-left:6px;" onclick="addAchvPoints();" value="<?php echo __('Add', 'joomsport-achievements');?>" />
                    </td>

                </tr>
            </tfoot>
        </table>
        <script>
        jQuery(document).ready( function(){   
            
            if(jQuery("select[name='seasonRanking']").val() == 0){
                jQuery('.tblAchvPoints').show();
                jQuery('.jsach_method').hide();
            }else{
                jQuery('.tblAchvPoints').hide();
                jQuery('.jsach_method').show();

            }
        });
        </script>    
        <?php
    }
    public static function js_meta_ef($post){

        $metadata = get_post_meta($post->ID,'_jsprt_achv_season_ef',true);
        
        $efields = JoomSportAchievmentsHelperEF::getEFList('3', 0);

        if(count($efields)){
            echo '<div class="jsminwdhtd jstable" style="width:auto;">';
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
    

    public static function joomsport_season_save_metabox($post_id, $post){
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['joomsport_achievments_season_nonce'] ) ? $_POST['joomsport_achievments_season_nonce'] : '';
        $nonce_action = 'joomsport_achievments_season_savemetaboxes';
 
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
        
        if('jsprt_achv_season' == $_POST['post_type'] ){
            
            self::saveMetaAbout($post_id);
            $ranking1 = self::saveMetaRanking($post_id);
            $ranking2 = self::saveMetaPoints($post_id);
            self::saveMetaEF($post_id);
            
            if($ranking1 || $ranking2){
                self::recalculateStages($post_id);
            }

        }
    }
    

    private static function saveMetaAbout($post_id){
        $meta_data = isset($_POST['about'])?  wp_kses_post($_POST['about']):'';
        update_post_meta($post_id, '_jsprt_achv_season_about', $meta_data);
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
        update_post_meta($post_id, '_jsprt_achv_season_ef', $meta_array);
    }
    private static function saveMetaRanking($post_id){
        $metadata_old = get_post_meta($post_id,'_jsprt_achv_season_ranking',true);
        $meta_data['sRanking'] = (int) filter_input(INPUT_POST, 'sRanking');
        $meta_data['sRankingWay'] = (int) filter_input(INPUT_POST, 'sRankingWay');
        update_post_meta($post_id, '_jsprt_achv_season_ranking', $meta_data);
        if($meta_data == $metadata_old){
            return false;
        }else{
            return true;
        }
        
    }
    private static function saveMetaPoints($post_id){
        $metadata_old = get_post_meta($post_id,'_jsprt_achv_season_points',true);
        $meta_data = array();
        $meta_data['ranking_criteria'] = (int) filter_input(INPUT_POST, 'seasonRanking');
        $meta_data['ranking_method'] = (int) filter_input(INPUT_POST, 'seasonRankingMethod');
        $meta_data['pts_by_place'] = array();
        $places = filter_input(INPUT_POST, 'achv_place_res', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $points = filter_input(INPUT_POST, 'achv_points_res', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        for($intA=0;$intA<count($places);$intA++){
            if(isset($places[$intA]) && isset($points[$intA])){
                if(intval($places[$intA]) && floatval($points[$intA])){
                    $meta_data['pts_by_place'][$places[$intA]] = $points[$intA];
                }
            }
        }
        update_post_meta($post_id, '_jsprt_achv_season_points', $meta_data);
        if($meta_data == $metadata_old){
            return false;
        }else{
            return true;
        }
    }
    
    private static function recalculateStages($post_id){
        $stages = self::getAllStages($post_id);
        global $wpdb;
        if(count($stages)){
            foreach ($stages as $stage_id) {
                $season_id = (int) get_post_meta($stage_id,'_jsprt_achv_stage_season',true);
                
                if($season_id){
                    $ranking = JoomSportAchievmentsHelperObject::getRankingCriteria($season_id);
                    if(!isset($ranking['sRanking'])){
                        $ranking = JoomSportAchievmentsHelperObject::getRankingCriteria($post_id);
                    }
                    if(isset($ranking['sRanking'])){
                        $points = JoomSportAchievmentsHelperObject::getPointsByPlace($season_id);
                        $sql = "SELECT field_type FROM {$wpdb->jsprtachv_results_fields} WHERE id=".intval($ranking['sRanking']);
                        $field_type = $wpdb->get_var($sql);

                        switch ($field_type) {
                            case 0:
                                $ordering = "LENGTH(field_{$ranking['sRanking']}), field_{$ranking['sRanking']}";

                                break;
                            case 1:
                                $ordering = "CAST(field_{$ranking['sRanking']} as DECIMAL(10,5))";

                                break;
                            default:
                                $ordering = "field_{$ranking['sRanking']}";
                                break;
                        }
                        $wpdb->query("UPDATE {$wpdb->jsprtachv_stage_result} SET points=0 WHERE stage_id={$stage_id}");
                            
                        $all = $wpdb->get_results("SELECT * FROM {$wpdb->jsprtachv_stage_result} WHERE stage_id={$stage_id} AND field_{$ranking['sRanking']} != '' ORDER BY {$ordering} ".($ranking['sRankingWay']?" asc":" desc"));
                        for($intA=0;$intA<count($all);$intA++){
                            $wpdb->query("UPDATE {$wpdb->jsprtachv_stage_result} SET rank=".($intA+1)." WHERE id={$all[$intA]->id}");
                            if(isset($points['ranking_criteria'])
                                    && $points['ranking_criteria'] == '0'
                                    && isset($points['pts_by_place'][$intA+1])){
                                    $wpdb->query("UPDATE {$wpdb->jsprtachv_stage_result} SET points=".floatval($points['pts_by_place'][$intA+1])." WHERE id={$all[$intA]->id}");

                            }
                        }
                    }
                }
            }
        }
    }
    private static function getAllStages($post_id){
        $stages = array();
        $seasons = array($post_id);
        $childs = self::getSeasonChildrens($post_id);

        if(count($childs)){
            foreach($childs as $chld){
                $seasons[] = $chld->ID;
            }
        }

        $args = array(
            'posts_per_page' => -1,
            'offset'           => 0,
            'meta_key'          => '_jsprt_achv_stage_date',
            'orderby'          => 'meta_value',
            'order'            => 'ASC',
            'post_type'        => 'jsprt_achv_stage',
            'post_status'      => 'publish',
            'meta_query' => array(
                    array(
                            'key' => '_jsprt_achv_stage_season',
                            'value' => $seasons,
                            'compare' => 'IN',
                    )
            )

        );
        $posts_array = get_posts( $args );
        for($intA=0;$intA<count($posts_array);$intA++){
            $stages[] = $posts_array[$intA]->ID;
        }
        return $stages;
    }
    
    private static  function getSeasonChildrens($post_id){
        $args = array(
                'post_parent' => $post_id,
                'post_type'   => 'jsprt_achv_season', 
                'numberposts' => -1,
                'post_status' => 'published',
                'orderby' => 'menu_order title',
                'order'   => 'ASC',
        );
        $children = get_children( $args );
        return $children;
    }
}
