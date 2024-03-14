<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
class JoomSportAchievmentsMetaStage {
    public static function output( $post ) {
        global $post, $thepostid, $wp_meta_boxes;
        
        
        $thepostid = $post->ID;

        wp_nonce_field( 'joomsport_achievments_stage_savemetaboxes', 'joomsport_achievments_stage_nonce' );
        ?>
        <div id="joomsportAchvContainerBE">
            
            <div id="main_conf_div" class="tabdiv">
                <div>
                    <div>
                        <?php
                        do_meta_boxes(get_current_screen(), 'joomsportachvintab_stage1', $post);
                        unset($wp_meta_boxes[get_post_type($post)]['joomsportachvintab_stage1']);
                        ?>

                    </div>    
                </div>
            </div>   
            
        </div>
        <?php
    }
        
        
    
    public static function js_meta_about($post){

        $metadata = get_post_meta($post->ID,'_jsprt_achv_stage_about',true);
        echo wp_editor($metadata, 'about',array("textarea_rows"=>3));


    }
    public static function js_meta_season($post){
        $metadata = (int) get_post_meta($post->ID,'_jsprt_achv_stage_season',true);
        if(!$metadata){
            $metadata = isset($_REQUEST['season_id'])?intval($_REQUEST['season_id']):0;
        }
        $args = array(
            'post_type' => 'jsprt_achv_season',
            'name'=>'season_id',
            'selected'=>$metadata
        );
        wp_dropdown_pages($args);
    }
    public static function js_meta_categories($post){

        global $wpdb;
        $value = '';

        $sql = "SELECT * FROM {$wpdb->jsprtachv_stages}";
        $stageCats = $wpdb->get_results($sql);
        echo '<div>';
        for($intA=0;$intA<count($stageCats);$intA++){
            $value = get_post_meta($post->ID, '_jsprt_achv_stage_stagecat_'.absint($stageCats[$intA]->id), true);
            $selvals = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->jsprtachv_stages_val.' WHERE fid='.absint($stageCats[$intA]->id).' ORDER BY eordering', 'OBJECT') ;
            echo '<div>';
            echo '<div>'.$stageCats[$intA]->name.'</div>';
            echo '<div>';
            echo JoomSportAchievmentsHelperSelectBox::Simple('stagecat_'.absint($stageCats[$intA]->id), $selvals,$value,' ',true);
            echo '</div>';
            echo '</div>';
        }
        if(!count($stageCats)){
            $link = get_admin_url(get_current_blog_id(), 'admin.php?page=jsprtachv-page-gamestages');
            printf( __( "There are no stage categories. Create new one on  %s Stage Categories list %s", 'joomsport-achievements' ), '<a href="'.$link.'">','</a>' );

        }
        
        echo '</div>';
    }
    public static function js_meta_date($post){

        global $wpdb;
        $stage_date = get_post_meta($post->ID,'_jsprt_achv_stage_date',true);
        $stage_time = get_post_meta($post->ID,'_jsprt_achv_stage_time',true);
        ?>
        <div>
            <label><?php echo __('Date','joomsport-achievements');?></label>
            <div>
                <input type="text" class="jsachvdatefield" name="stage_date" value="<?php echo $stage_date?>" />
            </div>
        </div>
        <div>
            <label><?php echo __('Time','joomsport-achievements');?></label>
            <div>
                <input type="text" name="stage_time" value="<?php echo $stage_time?>" />
            </div>
        </div>
        <?php
    }
    
    public static function js_meta_results($post){
        global $wpdb;
        $metadata = get_post_meta($post->ID,'_jsprt_achv_stage_ef',true);
        $fields_sorting = '';//get_post_meta($post->ID,'_jsprt_achv_stage_result_sorting',true);
        $result_table = $wpdb->get_results("SELECT * FROM {$wpdb->jsprtachv_stage_result} WHERE stage_id={$post->ID} ORDER BY rank,id");
        $efields = JoomSportAchievmentsHelperEF::getEFList('0', 0);

        $sql = "SELECT * FROM {$wpdb->jsprtachv_results_fields} WHERE complex='0' ORDER BY ordering";

        $resultFields = $wpdb->get_results( $sql );


        
            echo '<div class="" style="padding-bottom:5px;">';
            if(count($efields)){
                echo __( 'Filters', 'joomsport-achievements' ).': &nbsp; ';
                foreach ($efields as $ef) {
                    if($ef->field_type == '3'){
                        JoomSportAchievmentsHelperEF::getEFInputFilters($ef, isset($metadata[$ef->id])?$metadata[$ef->id]:null);
                        echo '&nbsp;';
                        echo $ef->edit;

                    }
                }
                echo '&nbsp;';
            }
            echo '<input type="button" id="JSACHV_participiants_SALL" class="button" value="'.__( 'Select All', 'joomsport-achievements' ).'" />';
            
            echo '</div>';
        
        $args = array(
            'posts_per_page' => -1,
            'offset'           => 0,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_type'        => 'jsprt_achv_player',
            'post_status'      => 'publish',

        );
        $posts_array = get_posts( $args );

        if(count($posts_array)){
            echo '<select name="participiants[]" id="JSACHV_participiants" class="jswf-chosen-select" data-placeholder="'.__('Add item','joomsport-achievements').'" multiple>';
            foreach ($posts_array as $tm) {
                $selected = '';
                
                echo '<option value="'.$tm->ID.'" '.$selected.'>'.$tm->post_title.'</option>';
            }
            echo '</select>';
            echo '<input style="margin-top:5px;" type="button" id="JSACHV_participiants_ADD" class="button jsach-button-success" value="'.__( 'Add', 'joomsport-achievements' ).'" />';
            
        }else{
            $link = get_admin_url(get_current_blog_id(), 'edit.php?post_type=jsprt_achv_player');
            printf( __( "There are no participants. Create new one on  %s Participant list %s", 'joomsport-achievements' ), '<a href="'.$link.'">','</a>' );
        }
        ?>
        <div>
            <?php if(count($posts_array)){?>
            <table class="table" id="JSACHV_results_TBL">
                <thead>
                    <tr class="ui-sortable">
                        <?php 
                        if($fields_sorting && count($fields_sorting)){
                            foreach($fields_sorting as $fld){
                                switch ($fld) {
                                    case 0:
                                        ?>
                                        <th class="particTitle" adfIndex="0"><?php echo __( 'Participant', 'joomsport-achievements' );?><input type="hidden" name="fields_sorting[]" value="0" /></th>
                            
                                        <?php

                                        break;
                                    case -1:
                                        ?>
                                        <th class="delTitle" adfIndex="-1">#<input type="hidden" name="fields_sorting[]" value="-1" /></th>
                                        
                                        <?php

                                        break;
                                    default:
                                        for($intA=0;$intA<count($resultFields);$intA++){
                                            if($fld == $resultFields[$intA]->id){
                                                echo '<th class="jsaAdf" adfIndex="'.$resultFields[$intA]->id.'">'.$resultFields[$intA]->name.'<input type="hidden" name="fields_sorting[]" value="'.$resultFields[$intA]->id.'" /></th>';
                                            }
                                        }
                                        break;
                                }
                            }
                        }else{
                            ?>
                            <th class="delTitle" adfIndex="-1">#<input type="hidden" name="fields_sorting[]" value="-1" /></th>
                            <th class="particTitle" adfIndex="0"><?php echo __( 'Participant', 'joomsport-achievements' );?><input type="hidden" name="fields_sorting[]" value="0" /></th>
                            <?php
                            for($intA=0;$intA<count($resultFields);$intA++){
                                echo '<th class="jsaAdf" adfIndex="'.$resultFields[$intA]->id.'">'.$resultFields[$intA]->name.'<input type="hidden" name="fields_sorting[]" value="'.$resultFields[$intA]->id.'" /></th>';
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for($intA=0;$intA<count($result_table);$intA++){
                        echo '<tr>';
                        if($fields_sorting && count($fields_sorting)){
                            foreach($fields_sorting as $fld){
                                switch ($fld) {
                                    case 0:
                                        echo '<td>'.get_the_title($result_table[$intA]->partic_id).'</td>';
                            
                                        break;
                                    case -1:
                                        echo '<td><a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));"><i class="fa fa-trash" aria-hidden="true"></i></a><input type="hidden" name="partic_id[]" value="'.$result_table[$intA]->partic_id.'"></td>';
                            
                                        break;
                                    default:
                                        for($intB=0;$intB<count($resultFields);$intB++){
                                            if($fld == $resultFields[$intB]->id){
                                                echo '<td><input type="text" class="JSACHV_result_input" name="field_'.$resultFields[$intB]->id.'[]" value="'.($result_table[$intA]->{'field_'.$resultFields[$intB]->id}).'" /></td>';
                                            }
                                        }
                                        break;
                                }
                            }
                        }else{
                            
                            echo '<td><a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));"><i class="fa fa-trash" aria-hidden="true"></i></a><input type="hidden" name="partic_id[]" value="'.$result_table[$intA]->partic_id.'"></td>';
                            echo '<td>'.get_the_title($result_table[$intA]->partic_id).'</td>';
                            for($intB=0;$intB<count($resultFields);$intB++){
                                echo '<td><input type="text" class="JSACHV_result_input" name="field_'.$resultFields[$intB]->id.'[]" value="'.($result_table[$intA]->{'field_'.$resultFields[$intB]->id}).'" /></td>';
                            }
                            
                        }
                        echo '</tr>';
                    }
                    
                    ?>
                </tbody>
            </table>    
            <?php } ?>
        </div>
    <script>
    /*jQuery( document ).ready(function() {
        
        jQuery('#JSACHV_results_TBL').sorttable({
            placeholder: 'placeholder',
            helperCells: null
        }).disableSelection();
    });  */  
    </script>
        <?php
    }
    
    public static function js_meta_ef($post){

        $metadata = get_post_meta($post->ID,'_jsprt_achv_stage_ef',true);
        
        $efields = JoomSportAchievmentsHelperEF::getEFList('2', 0);

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
    

    public static function joomsport_stage_save_metabox($post_id, $post){
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['joomsport_achievments_stage_nonce'] ) ? $_POST['joomsport_achievments_stage_nonce'] : '';
        $nonce_action = 'joomsport_achievments_stage_savemetaboxes';
 
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
        
        if('jsprt_achv_stage' == $_POST['post_type'] ){
            
            self::saveMetaAbout($post_id);
            //self::saveMetaCountry($post_id);
            self::saveMetaEF($post_id);
            self::saveMetaResults($post_id);
            self::saveMetaCategories($post_id);
            self::saveMetaSeason($post_id);
            self::saveMetaDates($post_id);
        }
    }
    

    private static function saveMetaAbout($post_id){
        $meta_data = isset($_POST['about'])?  wp_kses_post($_POST['about']):'';
        update_post_meta($post_id, '_jsprt_achv_stage_about', $meta_data);
    }
    private static function saveMetaDates($post_id){
        $meta_data = isset($_POST['stage_date'])?  sanitize_text_field($_POST['stage_date']):'';
        update_post_meta($post_id, '_jsprt_achv_stage_date', $meta_data);
        
        $meta_data = isset($_POST['stage_time'])?  sanitize_text_field($_POST['stage_time']):'';
        update_post_meta($post_id, '_jsprt_achv_stage_time', $meta_data);
    }
    private static function saveMetaSeason($post_id){
        $meta_data = (int) filter_input(INPUT_POST, 'season_id');
        update_post_meta($post_id, '_jsprt_achv_stage_season', $meta_data);
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
        update_post_meta($post_id, '_jsprt_achv_stage_ef', $meta_array);
    }
    private static function saveMetaCountry($post_id){
        //$meta_data = isset($_POST['jsprt_achv_player_country'])?  intval($_POST['jsprt_achv_player_country']):'';
        //update_post_meta($post_id, '_jsprt_achv_player_country', $meta_data);
    }
    private static function saveMetaResults($post_id){
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->jsprtachv_stage_result} WHERE stage_id={$post_id}");
        $resfields = $wpdb->get_results("SELECT * FROM {$wpdb->jsprtachv_results_fields} ORDER BY ordering,complex,id");
        $partic = filter_input(INPUT_POST, 'partic_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $fields_sorting = filter_input(INPUT_POST, 'fields_sorting', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        
        for($intB=0;$intB<count($resfields);$intB++){
            if($resfields[$intB]->complex == '0'){
                $res[$resfields[$intB]->id] = filter_input(INPUT_POST, "field_".$resfields[$intB]->id, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            }else{
                $fields_sorting[] = $resfields[$intB]->id;
            }
            
        }
        $sql = "SELECT * FROM {$wpdb->jsprtachv_stages}";
        $stageCats = $wpdb->get_results($sql);
        $Asql_insert = $Asql_vals = '';
        for($intC=0;$intC<count($stageCats);$intC++){
                $val = (int) filter_input(INPUT_POST, 'stagecat_'.absint($stageCats[$intC]->id));
                $Asql_insert .= ",stagecat_".$stageCats[$intC]->id;
                $Asql_vals .= ",'".($val)."'";
            }
        if($partic) {
            for ($intA = 0; $intA < count($partic); $intA++) {
                $sql_insert = "INSERT INTO {$wpdb->jsprtachv_stage_result}(stage_id,partic_id";
                $sql_vals = " VALUES({$post_id},{$partic[$intA]}";
                $player_fields = array();
                for ($intB = 0; $intB < count($resfields); $intB++) {
                    if ($resfields[$intB]->complex == '0') {
                        $sql_insert .= ",field_" . $resfields[$intB]->id;
                        $sql_vals .= ",'" . ($res[$resfields[$intB]->id][$intA]) . "'";
                        $player_fields[$resfields[$intB]->id] = $res[$resfields[$intB]->id][$intA];
                    } else if ($resfields[$intB]->complex == '2') {
                        $options = json_decode($resfields[$intB]->options, true);

                        if (isset($options["multisum"]) && count($options["multisum"])) {
                            $resVal = 0;
                            foreach ($options["multisum"] as $fieldMulti) {
                                if (isset($player_fields[$fieldMulti])) {
                                    $resVal += floatval($player_fields[$fieldMulti]);
                                }

                            }

                            $sql_insert .= ",field_" . $resfields[$intB]->id;
                            $sql_vals .= ",'" . ($resVal) . "'";
                        }
                    } else {

                        $options = json_decode($resfields[$intB]->options, true);
                        if ($options['depend1'] && $options['depend2']) {
                            if (isset($player_fields[$options['depend1']]) && isset($player_fields[$options['depend2']])) {
                                $resVal = '';
                                switch ($options['calc']) {
                                    case '0':
                                        if ($player_fields[$options['depend2']]) {
                                            $resVal = round(floatval($player_fields[$options['depend1']]) / floatval($player_fields[$options['depend2']]), 2);
                                        } else {
                                            $resVal = 0;
                                        }

                                        break;
                                    case '1':
                                        $resVal = floatval($player_fields[$options['depend1']]) * floatval($player_fields[$options['depend2']]);


                                        break;
                                    case '2':
                                        $resVal = floatval($player_fields[$options['depend1']]) + floatval($player_fields[$options['depend2']]);

                                        break;
                                    case '3':
                                        $resVal = floatval($player_fields[$options['depend1']]) - floatval($player_fields[$options['depend2']]);

                                        break;
                                    case '4':
                                        $resVal = ($player_fields[$options['depend1']]) . '/' . ($player_fields[$options['depend2']]);

                                        break;

                                    default:
                                        break;
                                }
                                $sql_insert .= ",field_" . $resfields[$intB]->id;
                                $sql_vals .= ",'" . ($resVal) . "'";
                            }
                        }
                    }
                }


                $sql_insert .= $Asql_insert . ')';
                $sql_vals .= $Asql_vals . ')';

                $wpdb->query($sql_insert . $sql_vals);
            }
        }
        
        $season_id = (int) filter_input(INPUT_POST, 'season_id');
        if($season_id){
            $ranking = JoomSportAchievmentsHelperObject::getRankingCriteria($season_id);
            
            if(isset($ranking['sRanking'])){
                $points = JoomSportAchievmentsHelperObject::getPointsByPlace($season_id);
                $sql = "SELECT field_type FROM {$wpdb->jsprtachv_results_fields} WHERE id=".intval($ranking['sRanking']);
                $field_type = $wpdb->get_var($sql);
                
                $sql_for_null = '';
                switch ($field_type) {
                    case 0:
                        $ordering = "LENGTH(field_{$ranking['sRanking']}), field_{$ranking['sRanking']}";
                        $sql_for_null = " AND field_{$ranking['sRanking']} != ''";
                        break;
                    case 1:
                        $ordering = "CAST(field_{$ranking['sRanking']} as DECIMAL(10,5))";

                        break;
                    default:
                        $ordering = "field_{$ranking['sRanking']}";
                        $sql_for_null = " AND field_{$ranking['sRanking']} != ''";
                        break;
                }
                
                $all = $wpdb->get_results("SELECT * FROM {$wpdb->jsprtachv_stage_result} WHERE stage_id={$post_id} ".$sql_for_null." ORDER BY {$ordering} ".($ranking['sRankingWay']?" asc":" desc"));
                $indivRank = 1;
                $prev_result = '';
                $fieldrank = 'field_'.$ranking['sRanking'];
                for($intA=0;$intA<count($all);$intA++){
                    if($prev_result == $all[$intA]->$fieldrank){
                        $currank = $indivRank;
                    }else{
                        $currank = $intA+1;
                        $indivRank = $intA+1;
                    }
                    $wpdb->query("UPDATE {$wpdb->jsprtachv_stage_result} SET rank=".($currank)." WHERE id={$all[$intA]->id}");
                    
                    if(isset($points['ranking_criteria'])
                            && $points['ranking_criteria'] == '0'
                            && isset($points['pts_by_place'][$currank])){
                            $wpdb->query("UPDATE {$wpdb->jsprtachv_stage_result} SET points=".floatval($points['pts_by_place'][$currank])." WHERE id={$all[$intA]->id}");
                
                    }
                    $prev_result = $all[$intA]->$fieldrank;
                }
            }
        }
        //die();
        update_post_meta($post_id, '_jsprt_achv_stage_result_sorting', $fields_sorting);
    }
    private static function saveMetaCategories($post_id){
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->jsprtachv_stages}";
        $stageCats = $wpdb->get_results($sql);
        for($intA=0;$intA<count($stageCats);$intA++){
            $val = (int) filter_input(INPUT_POST, 'stagecat_'.absint($stageCats[$intA]->id));
            update_post_meta($post_id, '_jsprt_achv_stage_stagecat_'.absint($stageCats[$intA]->id), $val);
        }
    }
}
