<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */

class JoomSportClassMatchdayRound{
    private $_mdID = null;
    private $_seasonID = null;
    public function __construct($mdID) {
        $this->_mdID = $mdID;
        $metas = get_option("taxonomy_{$mdID}_metas");
        $this->_seasonID = $metas['season_id'];
    }
    public function getViewEdit(){
        global $wpdb;
        //$jsconfig =  new JoomsportSettings();
        $canAddMatches = JoomSportUserRights::canAddMatches();

        $t_single = JoomSportHelperObjects::getTournamentType($this->_seasonID);
        $metaquery =  array(
                array(
                    'key'     => '_joomsport_match_date',
                ),
                array(
                    'key'     => '_joomsport_match_time',
                    
                )
            ) ;
        
        if(!current_user_can('manage_options')){
            $teamsToModer = JoomSportUserRights::getUserPosts();
            if(!count($teamsToModer)){
                $teamsToModer = array(-100);
                
            }
            $metaquery[] = 
                array(
                    'relation' => 'OR',
                        array(
                    'key' => '_joomsport_home_team',
                    'value' => $teamsToModer,
                    'compare' => 'IN'
                    ),

                    array(
                    'key' => '_joomsport_away_team',
                    'value' => $teamsToModer,
                        'compare' => 'IN'
                    ) 
                ) ;
        }
        add_filter('posts_orderby','joomsport_ordermatchbydatetime');
        $matches = new WP_Query(array(
            'post_type' => 'joomsport_match',
            'posts_per_page'   => -1,
            'orderby' => 'id',
            'order'=>'ASC',
            'tax_query' => array(
                array(
                'taxonomy' => 'joomsport_matchday',
                'field' => 'term_id',
                'terms' => $this->_mdID)
            ),
            'meta_query' => $metaquery    
        ));

        remove_filter('posts_orderby','joomsport_ordermatchbydatetime');
        ob_start();
        $participiants = JoomSportHelperObjects::getParticipiants($this->_seasonID);
        $groups = $wpdb->get_results("SELECT id, group_name as name FROM {$wpdb->joomsport_groups} WHERE s_id = {$this->_seasonID} ORDER BY ordering"); 
        $season_options = get_post_meta($this->_seasonID,'_joomsport_season_point',true);
        
        $is_field_extra = array();
        $is_field_extra[] = JoomSportHelperSelectBox::addOption(0, __("No", "joomsport-sports-league-results-management"));
        $is_field_extra[] = JoomSportHelperSelectBox::addOption(1, __("Yes", "joomsport-sports-league-results-management"));
        $enabla_extra = (isset($season_options['s_enbl_extra']) && $season_options['s_enbl_extra']) ? 1:0;
        
        $mstatuses = $wpdb->get_results('SELECT id,stName as name FROM '.$wpdb->joomsport_match_statuses.' ORDER BY ordering');
        $is_field = array();
        $is_field[] = JoomSportHelperSelectBox::addOption(0, __("Fixtures", "joomsport-sports-league-results-management"));
        $is_field[] = JoomSportHelperSelectBox::addOption(1, __("Played", "joomsport-sports-league-results-management"));
        $is_field[] = JoomSportHelperSelectBox::addOption(-1, __("Live", "joomsport-sports-league-results-management"));
        
        if(count($mstatuses)){
            $is_field = array_merge($is_field,$mstatuses);
        }
        $venues = get_posts(array(
            'post_type' => 'joomsport_venue',
            'post_status'      => 'publish',
            'posts_per_page'   => -1,
            'orderby' => 'title',
            'order'=> 'ASC',
            )
        );
        $lists = array();

        for($intA=0;$intA<count($venues);$intA++){
            $tmp = new stdClass();
            $tmp->id = $venues[$intA]->ID;
            $tmp->name = $venues[$intA]->post_title;
            $lists[] = $tmp;
        }
        $is_fieldPl = array();
        $is_fieldPl[] = JoomSportHelperSelectBox::addOption(0, __("No", "joomsport-sports-league-results-management"));
        $is_fieldPl[] = JoomSportHelperSelectBox::addOption(1, __("Yes", "joomsport-sports-league-results-management"));
        
        $metas = get_option("taxonomy_{$this->_mdID}_metas");
        
        ?>

        <div class="jsOverXdiv">
            <?php
            if(current_user_can('manage_options')) {
                ?>
                <table class="form-table">
                    <tbody>
                    <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label
                                    for="name"><?php echo esc_html(__("Playoff", "joomsport-sports-league-results-management")); ?></label>
                        </th>
                        <td>
                            <?php echo wp_kses(JoomSportHelperSelectBox::Radio('is_playoff', $is_fieldPl, isset($metas['is_playoff']) ? esc_attr($metas['is_playoff']) : 0, ''), JoomsportSettings::getKsesRadio()); ?>
                            <p class="description"><?php echo esc_html(__("Playoff matchday results are not counted towards Standings.", "joomsport-sports-league-results-management")) ?></p>
                        </td>
                    </tr>

                    </tbody>
                </table>
            <?php
            }
            ?>
            <table class="mglTable" id="mglMatchDay">
                <thead>
                    <tr>
                        <th style="width:30px;">
                            #
                        </th>
                        <?php
                        if(count($groups)){
                            echo '<th>';
                            echo esc_html(__("Group","joomsport-sports-league-results-management"));
                            echo '</th>';
                        }
                        ?>
                        <th>
                            <?php echo esc_html(__('Home', 'joomsport-sports-league-results-management'));?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Score', 'joomsport-sports-league-results-management'));?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Away', 'joomsport-sports-league-results-management'));?>
                        </th>
                        <?php
                        if($enabla_extra && JoomsportSettings::get('mdf_et')){
                           echo '<th>'.esc_html(__('ET', 'joomsport-sports-league-results-management')).'</th>';
                        }
                        ?>
                        <?php
                        if(JoomsportSettings::get('mdf_played',1)){
                           echo '<th>'.esc_html(__('Status', 'joomsport-sports-league-results-management')).'</th>';
                        }
                        ?>
                        <?php
                        if(JoomsportSettings::get('mdf_date',1)){
                           echo '<th>'.esc_html(__('Date', 'joomsport-sports-league-results-management')).'</th>';
                        }
                        ?>
                        <?php
                        if(JoomsportSettings::get('mdf_time',1)){
                           echo '<th>'.esc_html(__('Time', 'joomsport-sports-league-results-management')).'</th>';
                        }
                        ?>
                        <?php
                        
                        if(JoomsportSettings::get('mdf_venue')){
                           echo '<th>'.esc_html(__('Venue', 'joomsport-sports-league-results-management')).'</th>';
                        }
                        ?>
                        <?php
                        
                        $efields = JoomSportHelperEF::getEFList('2', 0);
                        for($intA=0; $intA < count($efields); $intA ++){
                            $ef = $efields[$intA];
                            if(JoomsportSettings::get('extra_'.$ef->id)){
                                echo '<th>'.esc_html($ef->name).'</th>';
                            }
                        }
                        
                        ?>
                        <th>

                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    for($intA = 0; $intA < count($matches->posts); $intA ++){
                        //var_dump($matches->posts[$intA]);
                        //continue;
                        $match = $matches->posts[$intA];
                        $home_team = get_post_meta( $match->ID, '_joomsport_home_team', true );
                        $away_team = get_post_meta( $match->ID, '_joomsport_away_team', true );
                        $home_score = get_post_meta( $match->ID, '_joomsport_home_score', true );
                        $away_score = get_post_meta( $match->ID, '_joomsport_away_score', true );
                        $m_played = get_post_meta( $match->ID, '_joomsport_match_played', true );
                        $m_date = get_post_meta( $match->ID, '_joomsport_match_date', true );
                        $m_time = get_post_meta( $match->ID, '_joomsport_match_time', true );
                        $venue_id = (int) get_post_meta( $match->ID, '_joomsport_match_venue', true );
                        $group_id = (int) get_post_meta( $match->ID, '_joomsport_groupID', true );
                        
                        
                        $jmscore = get_post_meta($match->ID, '_joomsport_match_jmscore',true);
                        $metadataEF = get_post_meta($match->ID,'_joomsport_match_ef',true);

                        ?>
                        <tr>
                            <td>
                                <?php
                                if(current_user_can('delete_jscp_match', $match->ID)){
                                ?>
                                <a href="javascript:void(0);" onclick="javascript:(this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode));"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                <?php
                                }
                                ?>
                                <input type="hidden" name="match_id[]" value="<?php echo esc_attr($match->ID);?>">
                            </td>
                            <?php
                            if(count($groups)){
                                echo '<td>';
                                echo wp_kses(JoomSportHelperSelectBox::Simple('group_id[]', $groups,$group_id,'',true), JoomsportSettings::getKsesSelect());
                                echo '</td>';
                            }
                            ?>
                            <td><?php echo esc_html(get_the_title($home_team));?><input type="hidden" name="home_team[]" value="<?php echo esc_attr($home_team);?>"></td>
                            <td nowrap="nowrap"><input type="number" name="home_score[]" class="mglScore jsNumberNotNegative" value="<?php echo esc_attr($home_score);?>">:<input type="number" name="away_score[]" class="mglScore jsNumberNotNegative" value="<?php echo esc_attr($away_score);?>"></td>

                            <td><?php echo esc_html(get_the_title($away_team));?><input type="hidden" name="away_team[]" value="<?php echo esc_attr($away_team);?>"></td>
                            
                            <?php
                            if ($enabla_extra && JoomsportSettings::get('mdf_et')) {
                                echo '<td class="col-extra-time" nowrap="nowrap">';
                                echo wp_kses(JoomSportHelperSelectBox::Simple('extra_time[]', $is_field_extra,isset($jmscore['is_extra'])?esc_attr($jmscore['is_extra']):'','',false), JoomsportSettings::getKsesSelect());
                                echo '</td>';
                            }
                            if (JoomsportSettings::get('mdf_played',1)) {
                                echo '<td>';
                                echo wp_kses(JoomSportHelperSelectBox::Simple('m_played[]', $is_field,$m_played,'',false), JoomsportSettings::getKsesSelect());
                                echo '</td>';
                            }
                            if (JoomsportSettings::get('mdf_date',1)) {
                                echo '<td>';
                                echo '<input type="text" class="jsdatefield" name="m_date[]" maxlength="10" size="12" value="'.esc_attr($m_date).'" />';

                                echo '</td>';
                            }
                            if (JoomsportSettings::get('mdf_time',1)) {
                                echo '<td><input type="time"  name="m_time[]" maxlength="5" size="12" value="'.esc_attr($m_time).'" />';
                            }
                            
                            if (JoomsportSettings::get('mdf_venue')) {
                                echo '<td>'. wp_kses(JoomSportHelperSelectBox::Simple('venue_id[]', $lists,$venue_id), JoomsportSettings::getKsesSelect()).'</td>';
                            }

                            for($intE=0; $intE < count($efields); $intE ++){
                                $ef = $efields[$intE];
                                if(JoomsportSettings::get('extra_'.$ef->id)){
                                    
                                    JoomSportHelperEF::getEFInput($ef, isset($metadataEF[$ef->id])?$metadataEF[$ef->id]:null,'ef_'.$ef->id,true);

                                    if($ef->field_type == '2'){
                                    }else{
                                        echo '<td>'. ($ef->edit).'</td>';
                                    }
                                }
                            }
                            
                            ?>
                            
                            <td>
                                <?php if(JoomSportUserRights::isAdmin() || JoomsportSettings::get('moder_edit_matches_reg', 0)){?>
                                <a href="post.php?post=<?php echo intval($match->ID);?>&action=edit"><input type="button" class="button" value="<?php echo esc_attr(__('Details', 'joomsport-sports-league-results-management'));?>"></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php    
                    }
                    ?>
                </tbody>
                <?php 
                if(current_user_can('edit_jscp_matchs') && $canAddMatches){
                ?>
                <tfoot>
                    <tr>
                        <td>

                        </td>
                        <?php
                        if(count($groups)){
                            echo '<td>';
                            echo wp_kses(JoomSportHelperSelectBox::Simple('js_groupid_add', $groups,0,' id="js_groupid_add"',true), JoomsportSettings::getKsesSelect());
                            echo '</td>';
                        }
                        ?>
                        <td>
                            <select name="set_home_team"  id="set_home_team">
                                <option value="0"><?php echo __('Select participant', 'joomsport-sports-league-results-management');?></option>
                                <?php
                                if(count($participiants)){
                                    foreach ($participiants as $part) {
                                        echo '<option value="'.esc_attr($part->ID).'">'.esc_html($part->post_title).'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td nowrap="nowrap">
                            <input type="number" name="set_score_home" id="set_score_home" class="mglScore jsNumberNotNegative" />:<input type="number" name="set_score_away" id="set_score_away" class="mglScore jsNumberNotNegative" />
                        </td>

                        <td>
                            <select name="set_away_team" id="set_away_team">
                                <option value="0"><?php echo __('Select participant', 'joomsport-sports-league-results-management');?></option>
                                <?php
                                if(count($participiants)){
                                    foreach ($participiants as $part) {
                                        echo '<option value="'.esc_attr($part->ID).'">'.esc_html($part->post_title).'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        
                        <?php
                        if ($enabla_extra && JoomsportSettings::get('mdf_et')) {
                            echo '<td class="col-extra-time" nowrap="nowrap">';
                            echo wp_kses(JoomSportHelperSelectBox::Simple('extra_timez', $is_field_extra,0,' id="extra_timez"',false), JoomsportSettings::getKsesSelect());
                            echo '</td>';
                        }
                        if (JoomsportSettings::get('mdf_played',1)) {
                            echo '<td>';
                            echo wp_kses(JoomSportHelperSelectBox::Simple('m_played_foot', $is_field,0,' id="m_played_foot"',false), JoomsportSettings::getKsesSelect());
                            echo '</td>';
                        }
                        if (JoomsportSettings::get('mdf_date',1)) {
                            echo '<td>';
                            echo '<input type="text" placeholder="YY-mm-dd" size="12" class="jsdatefield" name="m_date_foot" id="m_date_foot" value="" />';
                            echo '</td>';
                        }
                        
                        if (JoomsportSettings::get('mdf_time',1)) {
                            echo '<td><input type="time" placeholder="H:i" name="m_time_foot" size="12" id="m_time_foot" value="" /></td>';
                        }
                        
                        if (JoomsportSettings::get('mdf_venue')) {
                            echo '<td>';
                            echo wp_kses(JoomSportHelperSelectBox::Simple('venue_id_foot', $lists,0, ' id="venue_id_foot"'), JoomsportSettings::getKsesSelect());
                
                            echo '</td>';
                        }
                        
                        for($intA=0; $intA < count($efields); $intA ++){
                            $ef = $efields[$intA];
                            if(JoomsportSettings::get('extra_'.$ef->id)){
                                echo '<td>';
                                JoomSportHelperEF::getEFInput($ef, null,'ef_foot');

                                if($ef->field_type == '2'){
                                }else{
                                    echo ($ef->edit);
                                }
                                echo '<input type="hidden" name="jscef[]" value="'.esc_attr($ef->id).'" />';
                                echo '</td>'; 
                            }
                        }
                        
                        ?>
                        
                        <td>
                            <input type="button" class="button mgl-add-button" value="<?php echo esc_attr(__("Add New", 'joomsport-sports-league-results-management'));?>" />
                        </td>
                    </tr>
                </tfoot>
                <?php
                }
                ?>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }
    public function save(){

        $t_single = JoomSportHelperObjects::getTournamentType($this->_seasonID);
        $matches = array();
        $metaquery =  array(
                
            ) ;
        
        if(!current_user_can('manage_options')){
            $teamsToModer = JoomSportUserRights::getTeamsArray($t_single);
            if(!count($teamsToModer)){
                $teamsToModer = array(-100);
                
            }
            $metaquery = 
                array(
                    'relation' => 'OR',
                        array(
                    'key' => '_joomsport_home_team',
                    'value' => $teamsToModer,
                    'compare' => 'IN'
                    ),

                    array(
                    'key' => '_joomsport_away_team',
                    'value' => $teamsToModer,
                        'compare' => 'IN'
                    ) 
                ) ;
        }
        remove_filter( 'get_terms_args', 'jsmday_filter_get_terms_args' );
        $matches_old = get_posts(array(
            'post_type' => 'joomsport_match',
            'posts_per_page' => -1,
            'offset'           => 0,
            'tax_query' => array(
                array(
                'taxonomy' => 'joomsport_matchday',
                'field' => 'term_id',
                'terms' => $this->_mdID)
            ),
            'meta_query' => $metaquery)
        );
        
        $post_name = '';
        
        $terms = wp_get_object_terms( $this->_seasonID, 'joomsport_tournament' );
        if( $terms ){
            $post_name .= $terms[0]->slug;
        }
        $post_name .= " ".get_the_title($this->_seasonID);
        if(isset($_POST['match_id']) && count($_POST['match_id'])){
            for($intA = 0; $intA < count($_POST['match_id']); $intA++){
                
                $postM = null;
                
                $metadata = get_post_meta(intval($_POST['home_team'][$intA]),'_joomsport_team_personal',true);
                $home_team = isset($metadata['middle_name'])?(sanitize_text_field($metadata['middle_name'])):"";
                
                $metadata = get_post_meta(intval($_POST['away_team'][$intA]),'_joomsport_team_personal',true);
                $away_team = isset($metadata['middle_name'])?(sanitize_text_field($metadata['middle_name'])):"";
                if(!$home_team){
                    $home_team = get_the_title(intval($_POST['home_team'][$intA]));
                }
                if(!$away_team){
                    $away_team = get_the_title(intval($_POST['away_team'][$intA]));
                }
                $score = intval($_POST['home_score'][$intA]) .' : '. intval($_POST['away_score'][$intA]);
                $sep  = empty(JoomsportSettings::get('jsconf_home_away_separator_vs')) ? ' vs ' : ' '.JoomsportSettings::get('jsconf_home_away_separator_vs').' ';

                $title = $home_team.$sep.$away_team;
                
                $arr = array(
                        'post_type' => 'joomsport_match',
                        'post_title' => wp_strip_all_tags( $title ),
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_author' => get_current_user_id(),
                        'post_name' => wp_strip_all_tags($post_name." ".$title)
                );
                if(intval($_POST['match_id'][$intA])){
                    $arr['ID'] = intval($_POST['match_id'][$intA]);
                    $postM = get_post(intval($_POST['match_id'][$intA]));
                    $arr['comment_status'] = $postM->comment_status;
                    
                    
                }
                $groupID = 0;
                if(isset($_POST['group_id'][$intA])){
                    $groupID = intval($_POST['group_id'][$intA]);
                }
                
                if($postM && $postM->post_title == wp_strip_all_tags( $title )){
                    $post_id = $postM->ID;
                }else{
                    $post_id = wp_insert_post( $arr );
                }
                
                $scoreChanged = false;
                
                if($post_id){
                    $allmeta = get_post_meta( $post_id,'',true);
                    if(!isset($allmeta["_joomsport_home_team"][0]) || $allmeta["_joomsport_home_team"][0] != intval($_POST['home_team'][$intA])){
                        update_post_meta($post_id, '_joomsport_home_team', intval($_POST['home_team'][$intA]));
                    }
                    if(!isset($allmeta["_joomsport_away_team"][0]) || $allmeta["_joomsport_away_team"][0] != intval($_POST['away_team'][$intA])){
                    
                        update_post_meta($post_id, '_joomsport_away_team', intval($_POST['away_team'][$intA]));
                    }
                    if(!isset($allmeta["_joomsport_home_score"][0]) ||  $allmeta["_joomsport_home_score"][0] != intval($_POST['home_score'][$intA]) || ($allmeta["_joomsport_home_score"][0] == '' && ($_POST['home_score'][$intA]) != '')){
                        update_post_meta($post_id, '_joomsport_home_score', intval($_POST['home_score'][$intA]));
                        $scoreChanged = true;
                    }
                    if(!isset($allmeta["_joomsport_away_score"][0]) || $allmeta["_joomsport_away_score"][0] != intval($_POST['away_score'][$intA]) || ($allmeta["_joomsport_away_score"][0] == '' && ($_POST['away_score'][$intA]) != '')){
                    
                        update_post_meta($post_id, '_joomsport_away_score', intval($_POST['away_score'][$intA]));
                        $scoreChanged = true;
                    }
                    if(!isset($allmeta["_joomsport_groupID"][0]) || $allmeta["_joomsport_groupID"][0] != $groupID){
                        update_post_meta($post_id, '_joomsport_groupID', $groupID);
                    }
                    if(!isset($allmeta["_joomsport_seasonid"][0]) || $allmeta["_joomsport_seasonid"][0] != $this->_seasonID){
                        update_post_meta($post_id, '_joomsport_seasonid', $this->_seasonID);
                    }
                    
                    
                    
                    
                    
                    if(isset($_POST['m_played'][$intA]) && (!isset($allmeta["_joomsport_match_played"][0]) || $allmeta["_joomsport_match_played"][0] != intval($_POST['m_played'][$intA]))){
                        update_post_meta($post_id, '_joomsport_match_played', intval($_POST['m_played'][$intA]));
                    }
                    if(isset($_POST['m_date'][$intA]) && (!isset($allmeta["_joomsport_match_date"][0]) || $allmeta["_joomsport_match_date"][0] != ($_POST['m_date'][$intA]))){
                        update_post_meta($post_id, '_joomsport_match_date', sanitize_text_field($_POST['m_date'][$intA]));
                    }
                    if(isset($_POST['m_time'][$intA]) && (!isset($allmeta["_joomsport_match_time"][0]) || $allmeta["_joomsport_match_time"][0] != ($_POST['m_time'][$intA]))){
                        update_post_meta($post_id, '_joomsport_match_time', sanitize_text_field($_POST['m_time'][$intA]));
                    }
                    if(isset($_POST['venue_id'][$intA]) && (!isset($allmeta["_joomsport_match_venue"][0]) || $allmeta["_joomsport_match_venue"][0] != intval($_POST['venue_id'][$intA]))){
                        update_post_meta($post_id, '_joomsport_match_venue', intval($_POST['venue_id'][$intA]));
                    }
                    
                    //ef && extra
                    
                    if(isset($_POST['extra_time'][$intA])){
                        $jmscore = get_post_meta($post_id, '_joomsport_match_jmscore',true);
                        if(!$jmscore || !is_array($jmscore)){
                            $jmscore = array();
                        }
                        $jmscore['is_extra'] = intval($_POST['extra_time'][$intA]);
                        
                        update_post_meta($post_id, '_joomsport_match_jmscore', $jmscore); 
                    }
                    if(isset($_POST['jscef']) && count($_POST['jscef'])){
                        $metadata = get_post_meta($post_id,'_joomsport_match_ef',true);
                        if(!$metadata || !is_array($metadata)){
                            $metadata = array();
                        }
                        foreach ($_POST['jscef'] as $efid) {
                            if(isset($_POST['ef_'.$efid][$intA])){
                                $metadata[$efid] = sanitize_text_field($_POST['ef_'.$efid][$intA]);
                            }
                        }
                        update_post_meta($post_id, '_joomsport_match_ef', $metadata);
                        
                        
                    }

                }
                
                if($scoreChanged){
                    do_action("joomsport_score_changed", $post_id);
                }
                
                $matches[] = $post_id;
                wp_set_post_terms( $post_id, array((int) $this->_mdID), 'joomsport_matchday');
                jsHelperMatchesDB::updateMatchDB($post_id);
            }
        }
        
        $recalcTeams = array();
        
        for($intA=0; $intA < count($matches_old); $intA++){
            $match_id = $matches_old[$intA]->ID;
            if(!in_array($match_id, $matches)){
                $recalcTeams[] = get_post_meta( $match_id, '_joomsport_home_team', true );
                $recalcTeams[] = get_post_meta( $match_id, '_joomsport_away_team', true );
                wp_delete_post($match_id);
            }
        }


        if(current_user_can('manage_options')) {
            $metasMD = get_option("taxonomy_" . ((int)$this->_mdID) . "_metas");
            $metasMD["is_playoff"] = isset($_POST["is_playoff"]) ? intval($_POST["is_playoff"]) : 0;
            update_option("taxonomy_" . ((int)$this->_mdID) . "_metas", $metasMD);
        }
        
        if(count($recalcTeams)){
            do_action('joomsport_update_playerlist', $this->_seasonID, $recalcTeams);
        }
        
        do_action('joomsport_update_standings',$this->_seasonID, array());
        
    }
    
    public function SaveMatch()
    {
        $form_data = isset($_POST['formdata'])?sanitize_text_field( urldecode($_POST['formdata'])):'';

        if($form_data){
            parse_str($form_data, $output);
        }
        if(!JoomSportUserRights::canAddMatch($this->_seasonID, $output["set_home_team"], $output["set_away_team"])){
            $msg['error'] = __( 'Please select your team', 'joomsport-sports-league-results-management' ); 
            echo esc_html(wp_json_encode($msg));
            die();
        }

                $post_name = '';
        
                $terms = wp_get_object_terms( $this->_seasonID, 'joomsport_tournament' );
                if( $terms ){
                    $post_name .= $terms[0]->slug;
                }
                $post_name .= " ".get_the_title($this->_seasonID);
                
                $metadata = get_post_meta(intval($output["set_home_team"]),'_joomsport_team_personal',true);
                $home_team = isset($metadata['middle_name'])?(sanitize_text_field($metadata['middle_name'])):"";
                
                $metadata = get_post_meta(intval($output["set_away_team"]),'_joomsport_team_personal',true);
                $away_team = isset($metadata['middle_name'])?(sanitize_text_field($metadata['middle_name'])):"";
                if(!$home_team){
                    $home_team = get_the_title(intval($output["set_home_team"]));
                }
                if(!$away_team){
                    $away_team = get_the_title(intval($output["set_away_team"]));
                }


                $sep  = empty(JoomsportSettings::get('jsconf_home_away_separator_vs')) ? ' vs ' : ' '.JoomsportSettings::get('jsconf_home_away_separator_vs').' ';

                $title = $home_team.$sep.$away_team;
                $arr = array(
                        'post_type' => 'joomsport_match',
                        'post_title' => wp_strip_all_tags( $title ),
                        'post_content' => '',
                        'post_status' => 'publish',
                        'post_author' => get_current_user_id(),
                        'post_name' => wp_strip_all_tags($post_name." ".$title)
                );
                
                $groupID = 0;
                if(isset($output["js_groupid_add"])){
                    $groupID = intval($output["js_groupid_add"]);
                }
                $post_id = wp_insert_post( $arr );

                if($post_id){
                    update_post_meta($post_id, '_joomsport_home_team', intval($output["set_home_team"]));
                    update_post_meta($post_id, '_joomsport_away_team', intval($output["set_away_team"]));
                    update_post_meta($post_id, '_joomsport_home_score', intval($output["set_score_home"]));
                    update_post_meta($post_id, '_joomsport_away_score', intval($output["set_score_away"]));
                    update_post_meta($post_id, '_joomsport_groupID', $groupID);
                    update_post_meta($post_id, '_joomsport_seasonid', $this->_seasonID);
                    
                    if(isset($output["m_played_foot"])){
                        update_post_meta($post_id, '_joomsport_match_played', intval($output["m_played_foot"]));
                    }
                    if(isset($output["m_date_foot"])){
                        update_post_meta($post_id, '_joomsport_match_date', sanitize_text_field($output["m_date_foot"]));
                    }
                    if(isset($output["m_time_foot"])){
                        update_post_meta($post_id, '_joomsport_match_time', sanitize_text_field($output["m_time_foot"]));
                    }
                    
                    $m_date = get_post_meta( $post_id, '_joomsport_match_date', true );
                    $m_time = get_post_meta( $post_id, '_joomsport_match_time', true );
                    if(!$m_date){
                        update_post_meta($post_id, '_joomsport_match_date', ''); 
                    }
                    if(!$m_time){
                        update_post_meta($post_id, '_joomsport_match_time', ''); 
                    }
                    
                    
                    if(isset($output["venue_id_foot"])){
                        update_post_meta($post_id, '_joomsport_match_venue', intval($output["venue_id_foot"]));
                    }
                    
                    //ef && extra

                    if(isset($output["extra_timez"])){
                        $jmscore = get_post_meta($post_id, '_joomsport_match_jmscore',true);
                        if(!is_array($jmscore)){$jmscore = array();}
                        $jmscore['is_extra'] = $output["extra_timez"];
                        update_post_meta($post_id, '_joomsport_match_jmscore', $jmscore); 
                    }
                    
                    //$efields = JoomSportHelperEF::getEFList('2', 0);
                    $metadata = get_post_meta($post_id,'_joomsport_match_ef',true);
                    if(!is_array($metadata)){$metadata = array();}
                    if(isset($output["ef_foot"]) && count($output["ef_foot"])){
                        foreach ($output["ef_foot"] as $key => $value) {
                            $metadata[$key] = sanitize_text_field($value);
                        }
                    }
                    
                    update_post_meta($post_id, '_joomsport_match_ef', $metadata);
                    wp_set_post_terms( $post_id, array((int)$this->_mdID), 'joomsport_matchday');


                    jsHelperMatchesDB::updateMatchDB($post_id);
                }


        echo intval($post_id);
        
        
        if(intval($output["m_played_foot"]) == 1){
            do_action('joomsport_update_standings',$this->_seasonID, array($home_team, $away_team));
        }
        
        
    }

}
function joomsport_ordermatchbydatetime($orderby) {
        global $wpdb;
        return str_replace($wpdb->prefix.'posts.post_date',$wpdb->prefix.'postmeta.meta_value,  mt1.meta_value, '.$wpdb->prefix.'posts.post_date', $orderby);
}