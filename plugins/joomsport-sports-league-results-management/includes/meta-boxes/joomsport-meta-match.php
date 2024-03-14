<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
require_once JOOMSPORT_PATH_INCLUDES . 'classes'. DIRECTORY_SEPARATOR . 'joomsport-class-match.php';
require_once JOOMSPORT_PATH_HELPERS . 'js-helper-stages.php';
class JoomSportMetaMatch {
    public static function output( $post ) {
        global $post, $thepostid, $wp_meta_boxes;
        
        
        $thepostid = $post->ID;
        $metadata = get_post_meta($post->ID,'_joomsport_seasonid',true);
        $t_single = JoomSportHelperObjects::getTournamentType($metadata);
        require_once JOOMSPORT_PATH_HELPERS . 'tabs.php';
        $etabs = new esTabs();
        wp_nonce_field( 'joomsport_match_savemetaboxes', 'joomsport_match_nonce' );
        echo '<div id="joomsportContainerBE">';
        if(!$t_single){
        ?>
        <div class="jsBEsettings" style="padding:0px;">
            <!-- <tab box> -->
            <ul class="tab-box">
                <?php
                echo ($etabs->newTab(__('Main','joomsport-sports-league-results-management'), 'main_conf', '', 'vis'));
                
                echo ($etabs->newTab(__('Lineups','joomsport-sports-league-results-management'), 'col_conf', ''));
                do_action("joomsport_custom_tab_be_head", $thepostid, $etabs);
                
                ?>
            </ul>	
            <div style="clear:both"></div>
        </div>
        <?php }else{
            remove_meta_box('joomsport_match_boxscore_form_meta_box','joomsport_match','joomsportintab_match1');
            remove_meta_box('joomsport_match_matchevents_form_meta_box','joomsport_match','joomsportintab_match1');
            
            //die();
        } ?>
        <div id="main_conf_div" class="tabdiv">
            <div>
                <div>
                    <?php
                    do_meta_boxes(get_current_screen(), 'joomsportintab_match1', $post);
                    unset($wp_meta_boxes[get_post_type($post)]['joomsportintab_match1']);
                    ?>
                    
                </div>    
            </div>
        </div>   
        <?php
        
        if(!$t_single){
        ?>
        <div id="col_conf_div" class="tabdiv visuallyhidden">
            <div>
                <?php
                do_meta_boxes(get_current_screen(), 'joomsportintab_match2', $post);
                unset($wp_meta_boxes[get_post_type($post)]['joomsportintab_match2']);
                ?>
            </div>    
        </div>
        <?php
        }
        ?>
            <?php
            do_action("joomsport_custom_tab_be_body", $thepostid, $etabs);
            ?>
        </div>
        
        <?php
    }
        
        
    public static function js_meta_score($post){

        $metadata = get_post_meta($post->ID,'_joomsport_match_score',true);
        $matchObj = JoomSportHelperObjects::getMatchType($post->ID);
        $matchObj->getScore();

    }
    public static function js_meta_about($post){

        $metadata = get_post_meta($post->ID,'_joomsport_match_about',true);
        echo (wp_editor($metadata, 'about',array("textarea_rows"=>3)));


    }
    public static function player_events_view($prefixInt, $pevents, $events, $t_single, $home_team, $away_team, $home_players, $away_players){
        global $wpdb;
        $hTeam = get_the_title($home_team);
        $aTeam = get_the_title($away_team);
        if($prefixInt){
            $prefix = "_" . $prefixInt;
        }else{
            $prefix = "";
        }


        ?>
        <table class="table table-striped jsTblEvnts"  cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <th class="title" width="30"></th>
                <th class="title" width="20">#</th>
                <th class="title jsleft" width="120">
                    <?php echo esc_html(__('Event','joomsport-sports-league-results-management'));?>
                </th>
                <th class="jsleft">
                    <?php echo esc_html(__('Player','joomsport-sports-league-results-management'));?>
                </th>
                <th></th>
                <th class="title" width="100">
                    <?php echo esc_html(__('Event time (minute OR MM:SS)','joomsport-sports-league-results-management'));?>
                </th>
                <th class="title" width="60">
                    <?php echo esc_html(__('Quantity','joomsport-sports-league-results-management'));?>
                </th>
                <th class="title" width="20" colspan="2"></th>
            </tr>
            </thead>
            <tbody id="new_events<?php echo esc_attr($prefix)?>" class="tblEventsSortable">
            <?php
            $ps = 0;

            if (isset($pevents) && $pevents) {
                foreach ($pevents as $m_events) {
                    $eventname = $wpdb->get_var($wpdb->prepare("SELECT e_name FROM {$wpdb->joomsport_events} WHERE id = %d ORDER BY ordering", $m_events->e_id));
                    $player = get_the_title($m_events->player_id);

                    echo '<tr class="ui-state-default">';
                    echo '<td width="30"><i class="fa fa-bars" aria-hidden="true"></i></td>';
                    echo '<td><input type="hidden" name="stage_id[]" value="'.esc_attr($prefixInt).'" /><input type="hidden" name="em_id[]" value="" /><a href="javascript: void(0);" onClick="javascript:delJoomSportSelRow(this); return false;" title="'.esc_attr(__('Delete','joomsport-sports-league-results-management')).'"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
                    echo '<td><input type="hidden" name="new_eventid[]" value="'.esc_attr($m_events->e_id).'" />'.esc_html($eventname).'</td>';
                    echo '<td><input type="hidden" name="new_player[]" value="'.esc_attr($t_single?$m_events->player_id:($m_events->t_id.'*'.$m_events->player_id)).'" />'.esc_html($player).'</td>';

                    $vMinutes = $m_events->minutes_input?$m_events->minutes_input:$m_events->minutes;
                    $assist_players = '';

                    if($m_events->plFM){
                        $assistArr = explode(",", $m_events->plFM);
                        for($intM=0;$intM<count($assistArr);$intM++){
                            if($intM){$assist_players .= ", ";}
                            //echo $assistArr[$intM];
                            $assist_players .= get_the_title($assistArr[$intM]);
                        }
                    }

                    echo '<td>'.esc_html($assist_players).'<input type="hidden" name="sub_eventid[]" value="'.esc_attr($m_events->subevID).'" /><input type="hidden" name="sub_eventid_vals[]" value="'.esc_attr($m_events->subevPl).'" /></td>';
                    echo '<td><input type="text" class="jsNumberNotNegative jsNumberEventMinutes form-control" style="width:60px;" size="5" maxlength="5" name="e_minuteval[]" value="'.esc_attr($vMinutes).'" step="1" min="0" /></td>';
                    echo '<td><input type="number" class="jsNumberNotNegative form-control" style="width:60px;" size="5" maxlength="5" name="e_countval[]" value="'.esc_attr($m_events->ecount).'" step="1" min="0" /></td>';
                    echo '<td colspan="2"></td>';
                    echo '</tr>';
                    ++$ps;
                }
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="title"></td>
                <td>
                    <input type="hidden" id="subeventid<?php echo esc_attr($prefix)?>" name="subeventid" value="0" />
                    <div id="ncPlSubTitle<?php echo esc_attr($prefix)?>"></div>
                </td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td colspan="3" class="title" width="170">
                    <?php echo wp_kses(JoomSportHelperSelectBox::Simple('event_id'.$prefix, $events,0,' id="event_id'.$prefix.'" onchange="getSubEvents(\''.$prefix.'\');"',true), JoomsportSettings::getKsesSelect());?>
                </td>
                <td width="180">
                    <select id="playerz_id<?php echo esc_attr($prefix)?>" class="form-control" name="playerz_id">
                        <option value="0"><?php echo esc_html(__("Select Player","joomsport-sports-league-results-management"))?></option>
                        <?php
                        if($t_single){
                            echo '<option value="'.esc_attr($home_team).'">'.esc_html(get_the_title($home_team)).'</option>';
                            echo '<option value="'.esc_attr($away_team).'">'.esc_html(get_the_title($away_team)).'</option>';
                        }else{
                            ?>
                            <optgroup label="<?php echo esc_html($hTeam)?>">
                                <?php
                                if($home_players){
                                    for($intA=0;$intA<count($home_players);$intA++){
                                        echo '<option value="'.esc_attr($home_team.'*'.$home_players[$intA]).'">'.esc_html(get_the_title($home_players[$intA])).'</option>';
                                    }
                                }
                                ?>
                            </optgroup>
                            <optgroup label="<?php echo esc_html($aTeam)?>">
                                <?php
                                if($away_players){
                                    for($intA=0;$intA<count($away_players);$intA++){
                                        echo '<option value="'.esc_attr($away_team.'*'.$away_players[$intA]).'">'.esc_html(get_the_title($away_players[$intA])).'</option>';
                                    }
                                }
                                ?>
                            </optgroup>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td width="180">
                    <div id="ncPlSub<?php echo esc_attr($prefix)?>" style="display:none;">
                        <select id="playerzSub_id<?php echo esc_attr($prefix)?>" name="playerzSub_id[]" multiple class="jswf-chosen-select form-control" size="1" data-placeholder="Select player">
                            <?php
                            if($t_single){
                                echo '<option value="'.esc_attr($home_team).'">'.esc_html(get_the_title($home_team)).'</option>';
                                echo '<option value="'.esc_attr($away_team).'">'.esc_html(get_the_title($away_team)).'</option>';
                            }else{
                                ?>
                                <optgroup label="<?php echo esc_attr($hTeam)?>">
                                    <?php
                                    if($home_players){
                                        for($intA=0;$intA<count($home_players);$intA++){
                                            echo '<option value="'.esc_attr($home_team.'*'.$home_players[$intA]).'">'.esc_html(get_the_title($home_players[$intA])).'</option>';
                                        }
                                    }
                                    ?>
                                </optgroup>
                                <optgroup label="<?php echo esc_attr($aTeam)?>">
                                    <?php
                                    if($away_players){
                                        for($intA=0;$intA<count($away_players);$intA++){
                                            echo '<option value="'.esc_attr($away_team.'*'.$away_players[$intA]).'">'.esc_html(get_the_title($away_players[$intA])).'</option>';
                                        }
                                    }
                                    ?>
                                </optgroup>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </td>
                <td class="title" width="60">
                    <input name="e_minutes" style="width:60px;" id="e_minutes<?php echo esc_attr($prefix)?>" class="jsNumberNotNegative jsNumberEventMinutes form-control" type="text" maxlength="5" size="5" step="1" min="0"/>
                </td>
                <td class="title" width="60">
                    <input name="re_count" style="width:60px;" id="re_count<?php echo esc_attr($prefix)?>" class="jsNumberNotNegative form-control" type="number" maxlength="5" size="5" value="1" step="1" min="0" />
                </td>
                <td colspan="2">
                    <input class="btn btn-default button " type="button" style="cursor:pointer;"  value="<?php echo esc_attr(__('Add','joomsport-sports-league-results-management'));?>" onClick="bl_add_event('<?php echo esc_attr($prefixInt)?>');" />
                </td>
            </tr>
            </tfoot>
        </table>
        <?php
    }
    public static function js_meta_playerevents($post){
        global $wpdb;

        $metadata = get_post_meta($post->ID,'_joomsport_seasonid',true);
        $t_single = JoomSportHelperObjects::getTournamentType($metadata);
        $events = $wpdb->get_results("SELECT id, e_name as name FROM {$wpdb->joomsport_events} WHERE player_event='1' AND dependson='' AND events_sum = '0' ORDER BY ordering");
        $season_id = JoomSportHelperObjects::getMatchSeason($post->ID);

        $stagesSeparate = jsHelperStages::getStagesManual($season_id);
        
        $home_team = get_post_meta( $post->ID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $post->ID, '_joomsport_away_team', true );
        $hTeam = get_the_title($home_team);
        $aTeam = get_the_title($away_team);

        $home_players = get_post_meta($home_team,'_joomsport_team_players_'.$season_id,true);
        $home_players = JoomSportHelperObjects::cleanJSArray($home_players);
        $away_players = get_post_meta($away_team,'_joomsport_team_players_'.$season_id,true);
        $away_players = JoomSportHelperObjects::cleanJSArray($away_players);

        $query = "SELECT me.*,ev.e_name,(subev.e_id) as subevID, GROUP_CONCAT(CONCAT(subev.t_id,'*',subev.player_id)) as subevPl, GROUP_CONCAT(subev.player_id) as plFM"
                    ." FROM  {$wpdb->joomsport_match_events} as me"
                . " JOIN {$wpdb->joomsport_events} as ev ON me.e_id = ev.id AND me.match_id = %d"
                . " LEFT JOIN {$wpdb->joomsport_match_events} as subev ON subev.additional_to = me.id"
                ." WHERE ev.player_event = '1' AND ev.dependson=''"
                ." AND me.stage_id = 0"
                .' GROUP BY me.id'
                .' ORDER BY me.eordering, CAST(me.minutes AS UNSIGNED)';
        $pevents = $wpdb->get_results($wpdb->prepare($query, $post->ID));
        
        
        if(!$home_players && !$away_players && !$t_single){
            echo esc_html(__("No players added to selected teams in the season.","joomsport-sports-league-results-management"));
            
        }else{
        ?>
        <div>
            <?php
             self::player_events_view(0, $pevents, $events, $t_single, $home_team, $away_team, $home_players, $away_players);
            ?>
        </div>

        <?php
            if($stagesSeparate && count($stagesSeparate)){
                foreach($stagesSeparate as $stage){
                    $query = "SELECT me.*,ev.e_name,(subev.e_id) as subevID, GROUP_CONCAT(CONCAT(subev.t_id,'*',subev.player_id)) as subevPl, GROUP_CONCAT(subev.player_id) as plFM"
                        ." FROM  {$wpdb->joomsport_match_events} as me"
                        . " JOIN {$wpdb->joomsport_events} as ev ON me.e_id = ev.id AND me.match_id = %d"
                        . " LEFT JOIN {$wpdb->joomsport_match_events} as subev ON subev.additional_to = me.id"
                        ." WHERE ev.player_event = '1' AND ev.dependson=''"
                        ." AND me.stage_id = %d"

                        .' GROUP BY me.id'
                        .' ORDER BY me.eordering, CAST(me.minutes AS UNSIGNED)';
                    $pevents = $wpdb->get_results($wpdb->prepare($query, $post->ID, $stage->id));
                    ?>
                    <div>
                        <h4><?php echo esc_html($stage->m_name);?></h4>
                        <?php
                        self::player_events_view($stage->id, $pevents, $events, $t_single, $home_team, $away_team, $home_players, $away_players);
                        ?>
                    </div>
                    <?php
                }
            }

        }

    }
    public static function js_meta_lineup($post){
        global $wpdb;
        $season_id = JoomSportHelperObjects::getMatchSeason($post->ID);
        
        $home_team = get_post_meta( $post->ID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $post->ID, '_joomsport_away_team', true );
        $hTeam = get_the_title($home_team);
        $aTeam = get_the_title($away_team);
        
        $home_players = get_post_meta($home_team,'_joomsport_team_players_'.$season_id,true);
        $home_players = JoomSportHelperObjects::cleanJSArray($home_players);
        $away_players = get_post_meta($away_team,'_joomsport_team_players_'.$season_id,true);
        $away_players = JoomSportHelperObjects::cleanJSArray($away_players);
        
        $is_field = array();
        $is_field[] = JoomSportHelperSelectBox::addOption(1, __("Starting", "joomsport-sports-league-results-management"));
        $is_field[] = JoomSportHelperSelectBox::addOption(2, __("Substitute", "joomsport-sports-league-results-management"));
        $is_field[] = JoomSportHelperSelectBox::addOption(0, __("Not participated", "joomsport-sports-league-results-management"));
        
        ?>
        <div class="jsOvContainer">
            <div class="jsw50">
                <div style="text-align: center;">
                    <h3><?php echo esc_html($hTeam);?></h3>
                </div>
                <?php
                if($home_players){
                    ?>
                <input type="button" class="button jscheckall btn btn-default" value="<?php echo esc_attr(__('Starting for all','joomsport-sports-league-results-management'));?>" />
                <input type="button" class="button jscheckallnot btn btn-default" value="<?php echo esc_attr(__('Not participated for all','joomsport-sports-league-results-management'));?>" />
                <table class="table" id="new_squard1">
                    <?php
                    if($home_players){
                        for($intA=0;$intA<count($home_players);$intA++){
                            $sq1_type = $wpdb->get_var($wpdb->prepare("SELECT squad_type FROM {$wpdb->joomsport_squad} WHERE match_id=%d AND player_id=%d AND team_id=%d", $post->ID, $home_players[$intA], $home_team));
        
                            echo '<tr>';
                            echo '<td>'.get_the_title($home_players[$intA]).'</td>';
                            echo '<td width="280">'.JoomSportHelperSelectBox::Radio('squadradio1_'.esc_attr($home_players[$intA]), $is_field,$sq1_type,' class="jsgetcheckedSubs"',array('lclasses'=>array(1,1,0)));
                            echo '<input type="hidden" name="t1_squard[]" value="'.esc_attr($home_players[$intA]).'" />';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
                <?php
                }
                ?>
            </div>
            <div class="jsw50">
                <div style="text-align: center;">
                    <h3><?php echo esc_html($aTeam);?></h3>
                </div>
                <?php
                if($away_players){
                    ?>
                <input type="button" class="button jscheckall btn btn-default" value="<?php echo esc_attr(__('Starting for all','joomsport-sports-league-results-management'));?>" />
                <input type="button" class="button jscheckallnot btn btn-default" value="<?php echo esc_attr(__('Not participated for all','joomsport-sports-league-results-management'));?>" />
                <table class="table" id="new_squard2">
                    <?php
                    if($away_players){
                        for($intA=0;$intA<count($away_players);$intA++){
                            $sq2_type = $wpdb->get_var($wpdb->prepare("SELECT squad_type FROM {$wpdb->joomsport_squad} WHERE match_id=%d AND player_id=%d AND team_id=%d", $post->ID, $away_players[$intA], $away_team));
        
                            echo '<tr>';
                            echo '<td>'.esc_html(get_the_title($away_players[$intA])).'</td>';
                            echo '<td width="280">'.JoomSportHelperSelectBox::Radio('squadradio2_'.esc_attr($away_players[$intA]), $is_field,$sq2_type,' class="jsgetcheckedSubs"',array('lclasses'=>array(1,1,0)));
                            echo '<input type="hidden" name="t2_squard[]" value="'.esc_attr($away_players[$intA]).'" />';
                            echo '</td>';
                            echo '</tr>';
                            
                        }
                    }
                    ?>
                </table>
                <?php
                }
                ?>
            </div>
        </div>
        <?php
    } 
    public static function js_meta_subs($post){
        global $wpdb;
        $home_team = get_post_meta( $post->ID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $post->ID, '_joomsport_away_team', true );
        $hTeam = get_the_title($home_team);
        $aTeam = get_the_title($away_team);
        
        $subsin1 = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->joomsport_squad} WHERE match_id=%d AND team_id=%d AND is_subs='1' ORDER BY minutes", $post->ID, $home_team));
        $subsin2 = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->joomsport_squad} WHERE match_id=%d AND team_id=%d AND is_subs='1' ORDER BY minutes", $post->ID, $away_team));
        
        $season_id = JoomSportHelperObjects::getMatchSeason($post->ID);
        $home_players = get_post_meta($home_team,'_joomsport_team_players_'.$season_id,true);
        $home_players = JoomSportHelperObjects::cleanJSArray($home_players);
        $away_players = get_post_meta($away_team,'_joomsport_team_players_'.$season_id,true);
        $away_players = JoomSportHelperObjects::cleanJSArray($away_players);
        ?>
        <div class="jsOverXdiv">
        <?php if($home_players){
            ?>
        <h3><?php echo esc_html($hTeam);?></h3>
        <table class="table table-striped" id="subsid_1">
            <thead>
                <tr>
                    <th class="jscenter" width="5%">#</th>
                    <th style="text-align: left" width="250">
                        <?php echo esc_html(__('Player in','joomsport-sports-league-results-management')); ?>
                    </th>
                    <th style="text-align: left" width="250">
                        <?php echo esc_html(__('Player out','joomsport-sports-league-results-management')); ?>
                    </th>
                    <th class="jscenter" width="150">
                        <?php echo esc_html(__('Event time (minute)','joomsport-sports-league-results-management')); ?>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
              if ($subsin1 && count($subsin1)) {
                for ($i = 0;$i < count($subsin1);++$i) {
                    $subs = $subsin1[$i];
                    echo '<tr>';
                    echo '<td class="jscenter">';
                    echo '<a href="javascript: void(0);" onClick="javascript:delJoomSportSelRow(this);getSubsLists(\'squadradio1\'); return false;" title="'.esc_attr(__('Delete','joomsport-sports-league-results-management')).'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="hidden" value="'.esc_attr($subs->player_subs).'" name="players_team1_in_arr[]" />'.esc_html($subs->player_subs?get_the_title($subs->player_subs):'');
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="hidden" value="'.esc_attr($subs->player_id).'" name="players_team1_out_arr[]" />'.esc_html(get_the_title($subs->player_id));
                    echo '</td>';
                    echo '<td style="text-align: center">';
                    echo '<input type="number" class="jsNumberNotNegative form-control" style="width:50px;" value="'.esc_attr($subs->minutes).'" name="minutes1_arr[]" maxlength="5" size="5" step="1" min="0" />';
                    echo '</td>';
                    echo '<td>';
                    echo '</td>';
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td>
                        <?php echo wp_kses(JoomSportHelperSelectBox::Simple('players_team1_in', array(),'','id="players_team1_in"'), JoomsportSettings::getKsesSelect());?>
                    </td>
                    <td>
                        <?php echo wp_kses(JoomSportHelperSelectBox::Simple('players_team1_out', array(),'','id="players_team1_out"'), JoomsportSettings::getKsesSelect());?>
                    </td>
                    <td style="text-align: center">
                        <input type="number" class="jsNumberNotNegative form-control" style="width:50px;" name="minutes1" id="minutes1" value="" step="1" min="0" maxlength="5" size="5" />
                    </td>
                    <td class="jscenter">
                        <input class="button btn btn-default" type="button" value="<?php echo esc_attr(__('Add','joomsport-sports-league-results-management'));?>" style="cursor:pointer;" onclick="js_add_subs('subsid_1','players_team1_in','players_team1_out','minutes1');" />
                    </td>
                </tr>
            </tfoot>
        </table>
        <?php
        }
        ?>
        </div>
        <div class="jsOverXdiv">
        <?php if($away_players){
            ?>
        <h3><?php echo esc_html($aTeam);?></h3>
        <table class="table table-striped" id="subsid_2">
            <thead>
                <tr>
                    <th class="jscenter" width="5%">#</th>
                    <th style="text-align: left" width="250">
                        <?php echo esc_html(__('Player in','joomsport-sports-league-results-management')); ?>
                    </th>
                    <th style="text-align: left" width="250">
                        <?php echo esc_html(__('Player out','joomsport-sports-league-results-management')); ?>
                    </th>
                    <th class="jscenter" width="150">
                        <?php echo esc_html(__('Event time (minute)','joomsport-sports-league-results-management')); ?>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($subsin2 && $subsin2) {
                        for ($i = 0;$i < count($subsin2);++$i) {
                            $subs = $subsin2[$i];
                            echo '<tr>';
                            echo '<td class="jscenter">';
                            echo '<a href="javascript: void(0);" onClick="javascript:delJoomSportSelRow(this);getSubsLists(\'squadradio2\'); return false;" title="'.esc_html(__('Delete','joomsport-sports-league-results-management')).'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="hidden" value="'.esc_attr($subs->player_subs).'" name="players_team2_in_arr[]" />'.esc_html($subs->player_subs?get_the_title($subs->player_subs):'');
                            echo '</td>';
                            echo '<td>';
                            echo '<input type="hidden" value="'.esc_attr($subs->player_id).'" name="players_team2_out_arr[]" />'.esc_html(get_the_title($subs->player_id));
                            echo '</td>';
                            echo '<td style="text-align: center">';
                            echo '<input type="number" class="jsNumberNotNegative form-control" style="width:50px;" value="'.esc_attr($subs->minutes).'" name="minutes2_arr[]" step="1" min="0" maxlength="5" size="5" />';
                            echo '</td>';
                            echo '<td>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>
                    </td>
                    <td>
                        <?php echo wp_kses(JoomSportHelperSelectBox::Simple('players_team2_in', array(),'',' id="players_team2_in"'), JoomsportSettings::getKsesSelect());?>
                    </td>
                    <td>
                        <?php echo wp_kses(JoomSportHelperSelectBox::Simple('players_team2_out', array(),'',' id="players_team2_out"'), JoomsportSettings::getKsesSelect());?>
                    </td>
                    <td style="text-align: center">
                        <input type="number"class="jsNumberNotNegative form-control" style="width:50px;" name="minutes2" id="minutes2" step="1" min="0" value="" maxlength="5" size="5" />
                    </td>
                    <td class="jscenter">
                        <input class="button btn btn-default" type="button" value="<?php echo esc_attr(__('Add','joomsport-sports-league-results-management'));?>" style="cursor:pointer;" onclick="js_add_subs('subsid_2','players_team2_in','players_team2_out','minutes2');" />
                    </td>
                </tr>
            </tfoot>
        </table>
        <?php
        }
        ?>
        </div>
        <script>
            jQuery( document ).ready(function() {
                getSubsLists('squadradio1');
                getSubsLists('squadradio2');
            });
        </script>
        <?php
    }

    public static function js_meta_mevents($post){
        global $wpdb;
        $metadata = get_post_meta($post->ID,'_joomsport_seasonid',true);
        $t_single = JoomSportHelperObjects::getTournamentType($metadata);
        if(!$t_single){
            $home_team = get_post_meta( $post->ID, '_joomsport_home_team', true );
            $away_team = get_post_meta( $post->ID, '_joomsport_away_team', true );
            $hTeam = get_the_title($home_team);
            $aTeam = get_the_title($away_team);
            $metadata = get_post_meta($post->ID,'_joomsport_matchevents',true);
            $events = $wpdb->get_results("SELECT * FROM {$wpdb->joomsport_events} WHERE player_event='0' ORDER BY ordering");

            if(count($events)){
                echo '<div class="col-sm-10 clearfix">';
                echo '<table class="table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th class="jscenter" width="180">'.esc_html($hTeam).'</th>';
                echo '<th></th>';
                echo '<th class="jscenter" width="180">'.esc_html($aTeam).'</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<body>';
                foreach($events as $me){
                    ?>
                    <tr>
                        <td style="text-align: center;">
                            <input type="number" name="mevents1[]" class="jsNumberNotNegative form-control" style="width:50px;" value="<?php echo esc_attr(isset($metadata[$me->id]["mevents1"])?$metadata[$me->id]["mevents1"]:'')?>" size="5" min="0" />
                        </td>
                        <td style="padding:0px 20px; text-align: center;">
                            <?php echo esc_html($me->e_name);?>
                            <input type="hidden" name="mevent_id[]" value="<?php echo esc_attr($me->id)?>" />
                        </td>
                        <td style="text-align: center;">
                            <input type="number" name="mevents2[]" class="jsNumberNotNegative form-control" style="width:50px;" value="<?php echo esc_attr(isset($metadata[$me->id]["mevents2"])?$metadata[$me->id]["mevents2"]:'');?>" size="5" min="0" />
                        </td>
                    </tr>
                    <?php
                }
                echo '</body>';
                echo '</table>';
                echo '</div>';
            }else{
                $link = get_admin_url(get_current_blog_id(), 'admin.php?page=joomsport-page-events');
                 printf( __( 'There are no match events. Create new one on %s Extra fields list %s', 'joomsport-sports-league-results-management' ), '<a href="'.esc_url($link).'">','</a>' );
            }
        }

    }
    
    public static function js_meta_boxscore($post){
        global $wpdb;
        $home_team = get_post_meta( $post->ID, '_joomsport_home_team', true );
        $away_team = get_post_meta( $post->ID, '_joomsport_away_team', true );
        $season_id = JoomSportHelperObjects::getMatchSeason($post->ID);

        $complexBox = $wpdb->get_results('SELECT * FROM '.$wpdb->joomsport_box.' WHERE parent_id="0" AND ftype="0" AND published="1" ORDER BY ordering,name', 'OBJECT') ;
        if(!count($complexBox)){
            printf(__('No box score records available. Add new on %sBox score stats list%s','joomsport-sports-league-results-management'),'<a href="admin.php?page=joomsport-page-boxfields">','</a>');
            return;
        }
        echo '<h2>'.esc_html(get_the_title($home_team)).'</h2>';
        $res_html = '';

        $efbox = (int) JoomsportSettings::get('boxExtraField','0');
        
        $parentB = array();
        $parentInd = 0;
        for($intA=0;$intA<count($complexBox); $intA++){
            $complexBox[$intA]->extras = array();
            $childBox = array();
            if($complexBox[$intA]->complex == '1'){
                $childBox = $wpdb->get_results('SELECT * FROM '.$wpdb->joomsport_box.' WHERE parent_id="'.$complexBox[$intA]->id.'" AND published="1" AND ftype="0" ORDER BY ordering,name', 'OBJECT') ;
                for($intB=0;$intB<count($childBox); $intB++){
                    $options = json_decode($childBox[$intB]->options,true);
                    $extras = isset($options['extraVals'])?$options['extraVals']:array();
                    $childBox[$intB]->extras = $extras;
                    if(count($extras)){
                        foreach($extras as $extr){
                            array_push($complexBox[$intA]->extras, $extr);
                        }
                    }
                }
                
                if(count($childBox)){
                    $parentB[$parentInd]['object'] = $complexBox[$intA];
                    $parentB[$parentInd]['childs'] = $childBox;
                    $parentInd++;
                }
            }else{
                $options = json_decode($complexBox[$intA]->options,true);
                $extras = isset($options['extraVals'])?$options['extraVals']:array();
                $complexBox[$intA]->extras =  $extras;
                $parentB[$parentInd]['object'] = $complexBox[$intA];
                $parentB[$parentInd]['childs'] = $childBox;
                $parentInd++;
            }
            
            
            
        }
        
        $th1 = '';
        $th2 = '';
        
        $all_players = get_post_meta($home_team,'_joomsport_team_players_'.$season_id,true);
        $all_players = JoomSportHelperObjects::cleanJSArray($all_players);

        if($efbox){
            $season_relation = $wpdb->get_var('SELECT season_related FROM '.$wpdb->joomsport_ef.' WHERE id="'.$efbox.'"') ;


            $simpleBox = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->joomsport_ef_select.' WHERE fid="'.$efbox.'" ORDER BY eordering,sel_value', 'OBJECT') ;

            for($intS=0;$intS<count($simpleBox);$intS++){    
                $players = JoomSportHelperObjects::getPlayersByEF($home_team, $season_id, $efbox, $simpleBox[$intS]->id, $season_relation);
                $th1=$th2='';
                $boxtd = array();
                for($intA=0;$intA<count($parentB);$intA++){
                    $box = $parentB[$intA];
                    $intChld = 0;
                    for($intB=0;$intB<count($box['childs']); $intB++){
                        if(!count($box['childs'][$intB]->extras) || in_array($simpleBox[$intS]->id, $box['childs'][$intB]->extras)){
                            $intChld++;
                            $th2 .= "<th>".$box['childs'][$intB]->name."</th>";
                            $boxtd[] =  $box['childs'][$intB]->id;
                        }
                    }

                    if(!count($box['object']->extras) || in_array($simpleBox[$intS]->id, $box['object']->extras)){

                        if($intChld){
                            $th1 .= '<th colspan="'.$intChld.'">'.$box['object']->name.'</th>';
                        }else{
                            $th1 .= '<th rowspan="2">'.$box['object']->name.'</th>';
                            $boxtd[] =  $box['object']->id;
                        }
                    }
                }
                $res_html_head = $simpleBox[$intS]->name;
                $res_html_body  = '';
                
                $res_html_head .= '<table class="jsBoxStatDIv">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        '.($th1).'
                                    </tr>
                                    <tr>
                                        '.($th2).'
                                    </tr>
                                </thead>
                                <tbody>';
                                    
                                    for($intPP=0;$intPP<count($players);$intPP++){
                                        $res_html_body .=  '<tr>';
                                        $res_html_body .=  '<td>';
                                        $player = get_post($players[$intPP]);
                                        $res_html_body .=  esc_html($player->post_title);
                                        $res_html_body .=  '</td>';
                                        $player_stat = $wpdb->get_row("SELECT * FROM {$wpdb->joomsport_box_match} WHERE match_id={$post->ID} AND team_id={$home_team} AND player_id={$player->ID}");

                                        for($intBox=0;$intBox<count($boxtd);$intBox++){
                                            $boxfield = 'boxfield_'.$boxtd[$intBox];
                                            $res = isset($player_stat->{$boxfield})?$player_stat->{$boxfield}:'';
                                            $res_html_body .=  '<td><input data-inputboxtype="float" type="text" name="boxstat_'.esc_attr($home_team.'_'.$player->ID).'['.esc_attr($boxtd[$intBox]).']" value="'.esc_attr($res).'" /></td>';
                                        }

                                        $res_html_body .=  '</tr>';
                                    }
                                    
                              
                    if($res_html_body){    
                        $res_html .= $res_html_head.$res_html_body.'</tbody></table>'; 
                    }        

            }
        }else{
            $th1=$th2='';
            $boxtd = array();
            $players = get_post_meta($home_team,'_joomsport_team_players_'.$season_id,true);
            $players = JoomSportHelperObjects::cleanJSArray($players);
            for($intA=0;$intA<count($parentB);$intA++){
                $box = $parentB[$intA];
                $intChld = 0;
                for($intB=0;$intB<count($box['childs']); $intB++){
                    $intChld++;
                    $th2 .= "<th>".$box['childs'][$intB]->name."</th>";
                    $boxtd[] =  $box['childs'][$intB]->id;
                    
                }

                if($intChld){
                    $th1 .= '<th colspan="'.esc_attr($intChld).'">'.esc_html($box['object']->name).'</th>';
                }else{
                    $th1 .= '<th rowspan="2">'.esc_html($box['object']->name).'</th>';
                    $boxtd[] =  $box['object']->id;
                }
                
            }
                $res_html_head = $res_html_body  = '';
                $res_html_head .= '<table class="jsBoxStatDIv">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        '.($th1).'
                                    </tr>
                                    <tr>
                                        '.($th2).'
                                    </tr>
                                </thead>
                                <tbody>';

                                    for($intPP=0;$intPP<count($players);$intPP++){
                                        $res_html_body .=  '<tr>';
                                        $res_html_body .=  '<td>';
                                        $player = get_post($players[$intPP]);
                                        $res_html_body .=  esc_html($player->post_title);
                                        $res_html_body .=  '</td>';
                                        $player_stat = $wpdb->get_row("SELECT * FROM {$wpdb->joomsport_box_match} WHERE match_id={$post->ID} AND team_id={$home_team} AND player_id={$player->ID}");

                                        for($intBox=0;$intBox<count($boxtd);$intBox++){
                                            $boxfield = 'boxfield_'.$boxtd[$intBox];
                                            $res = isset($player_stat->{$boxfield})?$player_stat->{$boxfield}:'';
                                            $res_html_body .=  '<td><input data-inputboxtype="float" type="text" name="boxstat_'.esc_attr($home_team.'_'.$player->ID).'['.esc_attr($boxtd[$intBox]).']" value="'.esc_attr($res).'" /></td>';
                                        }

                                        $res_html_body .=  '</tr>';
                                    }
                    if($res_html_body){    
                        $res_html .= $res_html_head.$res_html_body.'</tbody></table>'; 
                    }

        }
        if($res_html){
            echo $res_html;
        }else{
            if(count($all_players)){
                printf(__('No box score records available. Add new on %sBox score stats list%s','joomsport-sports-league-results-management'),'<a href="admin.php?page=joomsport-page-boxfields">','</a>');
            
            }else{
                echo esc_html(__('Assign players to season to enter statistic','joomsport-sports-league-results-management'));
        
            }
        }
        //away
        
        echo '<h2>'.esc_html(get_the_title($away_team)).'</h2>';
        $res_html = '';
        
        $th1 = '';
        $th2 = '';
        $all_players = get_post_meta($away_team,'_joomsport_team_players_'.$season_id,true);
        $all_players = JoomSportHelperObjects::cleanJSArray($all_players);
        if($efbox){
            $simpleBox = $wpdb->get_results('SELECT id, sel_value as name FROM '.$wpdb->joomsport_ef_select.' WHERE fid="'.$efbox.'" ORDER BY eordering,sel_value', 'OBJECT') ;
            for($intS=0;$intS<count($simpleBox);$intS++){    
                $players = JoomSportHelperObjects::getPlayersByEF($away_team, $season_id, $efbox, $simpleBox[$intS]->id);
                
                $th1=$th2='';
                $boxtd = array();
                for($intA=0;$intA<count($parentB);$intA++){
                    $box = $parentB[$intA];
                    $intChld = 0;
                    for($intB=0;$intB<count($box['childs']); $intB++){
                        if(!count($box['childs'][$intB]->extras) || in_array($simpleBox[$intS]->id, $box['childs'][$intB]->extras)){
                            $intChld++;
                            $th2 .= "<th>".$box['childs'][$intB]->name."</th>";
                            $boxtd[] =  $box['childs'][$intB]->id;
                        }
                    }

                    if(!count($box['object']->extras) || in_array($simpleBox[$intS]->id, $box['object']->extras)){

                        if($intChld){
                            $th1 .= '<th colspan="'.esc_attr($intChld).'">'.esc_html($box['object']->name).'</th>';
                        }else{
                            $th1 .= '<th rowspan="2">'.esc_html($box['object']->name).'</th>';
                            $boxtd[] =  $box['object']->id;
                        }
                    }
                }
                $res_html_head = $simpleBox[$intS]->name;
                $res_html_body  = '';
                $res_html_head .= '<table class="jsBoxStatDIv">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        '.($th1).'
                                    </tr>
                                    <tr>
                                        '.($th2).'
                                    </tr>
                                </thead>
                                <tbody>';
                                    
                                    for($intPP=0;$intPP<count($players);$intPP++){
                                        $res_html_body .= '<tr>';
                                        $res_html_body .= '<td>';
                                        $player = get_post($players[$intPP]);
                                        $res_html_body .= $player->post_title;
                                        $res_html_body .= '</td>';
                                        $player_stat = $wpdb->get_row("SELECT * FROM {$wpdb->joomsport_box_match} WHERE match_id={$post->ID} AND team_id={$away_team} AND player_id={$player->ID}");

                                        for($intBox=0;$intBox<count($boxtd);$intBox++){
                                            $boxfield = 'boxfield_'.$boxtd[$intBox];
                                            $res = isset($player_stat->{$boxfield})?$player_stat->{$boxfield}:'';
                                            $res_html_body .= '<td><input type="text" data-inputboxtype="float" name="boxstat_'.esc_attr($away_team.'_'.$player->ID).'['.esc_attr($boxtd[$intBox]).']" value="'.esc_attr($res).'" /></td>';
                                        }

                                        $res_html_body .= '</tr>';
                                    }
                    if($res_html_body){    
                        $res_html .= $res_html_head.$res_html_body.'</tbody></table>'; 
                    }           

            }
        }else{
            $th1=$th2='';
            $boxtd = array();
            $players = get_post_meta($away_team,'_joomsport_team_players_'.$season_id,true);
            $players = JoomSportHelperObjects::cleanJSArray($players);
            for($intA=0;$intA<count($parentB);$intA++){
                $box = $parentB[$intA];
                $intChld = 0;
                for($intB=0;$intB<count($box['childs']); $intB++){
                    $intChld++;
                    $th2 .= "<th>".$box['childs'][$intB]->name."</th>";
                    $boxtd[] =  $box['childs'][$intB]->id;
                    
                }

                if($intChld){
                    $th1 .= '<th colspan="'.esc_attr($intChld).'">'.esc_html($box['object']->name).'</th>';
                }else{
                    $th1 .= '<th rowspan="2">'.esc_html($box['object']->name).'</th>';
                    $boxtd[] =  $box['object']->id;
                }
                
            }
                $res_html_body = $res_html_head  = '';
                $res_html_head .= '<table class="jsBoxStatDIv">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        '.($th1).'
                                    </tr>
                                    <tr>
                                        '.($th2).'
                                    </tr>
                                </thead>
                                <tbody>';

                                    for($intPP=0;$intPP<count($players);$intPP++){
                                        $res_html_body .= '<tr>';
                                        $res_html_body .= '<td>';
                                        $player = get_post($players[$intPP]);
                                        $res_html_body .= esc_html($player->post_title);
                                        $res_html_body .= '</td>';
                                        $player_stat = $wpdb->get_row("SELECT * FROM {$wpdb->joomsport_box_match} WHERE match_id={$post->ID} AND team_id={$away_team} AND player_id={$player->ID}");

                                        for($intBox=0;$intBox<count($boxtd);$intBox++){
                                            $boxfield = 'boxfield_'.$boxtd[$intBox];
                                            $res = isset($player_stat->{$boxfield})?$player_stat->{$boxfield}:'';
                                            $res_html_body .= '<td><input data-inputboxtype="float" type="text" name="boxstat_'.esc_attr($away_team.'_'.$player->ID).'['.esc_attr($boxtd[$intBox]).']" value="'.esc_attr($res).'" /></td>';
                                        }

                                        $res_html_body .= '</tr>';
                                    }
                    if($res_html_body){    
                        $res_html .= $res_html_head.$res_html_body.'</tbody></table>'; 
                    } 
        }
        if($res_html){
            echo ($res_html);
        }else{
            if(count($all_players)){
                printf(__('No box score records available. Add new on %sBox score stats list%s','joomsport-sports-league-results-management'),'<a href="admin.php?page=joomsport-page-boxfields">','</a>');
            
            }else{
                echo esc_html(__('Assign players to season to enter statistic','joomsport-sports-league-results-management'));
        
            }
            
        }
        
    }

    public static function js_meta_general($post){
        global $wpdb;
        $metadata = get_post_meta($post->ID,'_joomsport_match_general',true);
        $mstatuses = $wpdb->get_results('SELECT id,stName as name FROM '.$wpdb->joomsport_match_statuses.' ORDER BY ordering');
        $is_field = array();
        $is_field[] = JoomSportHelperSelectBox::addOption(0, __("Fixtures", "joomsport-sports-league-results-management"));
        $is_field[] = JoomSportHelperSelectBox::addOption(1, __("Played", "joomsport-sports-league-results-management"));
        $is_field[] = JoomSportHelperSelectBox::addOption(-1, __("Live", "joomsport-sports-league-results-management"));
        
        if(count($mstatuses)){
            $is_field = array_merge($is_field,$mstatuses);
        }

        $m_played = get_post_meta($post->ID,'_joomsport_match_played',true);
        $m_date = get_post_meta($post->ID,'_joomsport_match_date',true);
        $m_time = get_post_meta($post->ID,'_joomsport_match_time',true);
        $m_venue = get_post_meta($post->ID,'_joomsport_match_venue',true);
        $match_duration = JoomsportSettings::get('jsmatch_duration',0);

        if(isset($metadata['match_duration']) && $metadata['match_duration'] != ''){
            $match_duration = $metadata['match_duration'];
        }
        ?>
        <div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?php echo esc_html(__('Status','joomsport-sports-league-results-management'));?></label>
                <div class="col-sm-5">
                    <?php echo wp_kses(JoomSportHelperSelectBox::Simple('m_played', $is_field,$m_played,'',false), JoomsportSettings::getKsesSelect());?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?php echo esc_html(__('Date','joomsport-sports-league-results-management'));?></label>
                <div class="col-sm-5">
                    <input type="text" class="jsdatefield form-control" name="m_date" value="<?php echo esc_attr($m_date)?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?php echo esc_html(__('Time','joomsport-sports-league-results-management'));?></label>
                <div class="col-sm-5">
                    <input type="time" class="form-control" name="m_time" value="<?php echo esc_attr($m_time);?>" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?php echo esc_html(__('Venue','joomsport-sports-league-results-management'));?></label>
                <div class="col-sm-5">
                    <?php
                    $venues = get_posts(array(
                        'post_type' => 'joomsport_venue',
                        'post_status'      => 'publish',
                        'posts_per_page'   => -1,
                        'orderby' => 'title',
                        'order'=> 'ASC',
                    ));

                    $lists = array();

                    for($intA=0;$intA<count($venues);$intA++){
                        $tmp = new stdClass();
                        $tmp->id = $venues[$intA]->ID;
                        $tmp->name = $venues[$intA]->post_title;
                        $lists[] = $tmp;
                    }

                    if($m_venue == ''){
                        $home_team = get_post_meta( $post->ID, '_joomsport_home_team', true );
                        $m_venue = get_post_meta($home_team,'_joomsport_team_venue',true);
                    }
                    echo wp_kses(JoomSportHelperSelectBox::Simple('venue_id', $lists,$m_venue), JoomsportSettings::getKsesSelect());
                    ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"><?php echo __('Match duration','joomsport-sports-league-results-management');?></label>
                <div class="col-sm-5">
                    <input type="text" class="form-control inputbox" name="match_duration" style="width:50px;" maxlength="5" value="<?php echo esc_attr($match_duration);?>" onblur="extractNumber(this, 0, false);" onkeyup="extractNumber(this, 0, false);" onkeypress="return blockNonNumbers(this, event, false, false);">
                </div>
            </div>
        </div>
        <?php
    }

    public static function js_meta_ef($post){
        $metadata = get_post_meta($post->ID,'_joomsport_match_ef',true);
        $efields = JoomSportHelperEF::getEFList('2', 0);

        if(count($efields)){
            echo '<div class="jsminwdhtd jstable">';

            foreach ($efields as $ef) {
                JoomSportHelperEF::getEFInput($ef, isset($metadata[$ef->id])?$metadata[$ef->id]:null);
                //var_dump($ef);
                ?>
                
                <div class="jstable-row">
                    <div class="jstable-cell">
                        <?php echo esc_html($ef->name)?>
                    </div>
                    <div class="jstable-cell">
                        <div class="col-sm-6 row">
                            <?php
                            if($ef->field_type == '2'){
                                wp_editor(isset($metadata[$ef->id])?$metadata[$ef->id]:'', 'ef_'.$ef->id,array("textarea_rows"=>3));
                                echo '<input type="hidden" name="ef['.esc_attr($ef->id).']" value="ef_'.esc_attr($ef->id).'" />';
                            }else{
                                echo ($ef->edit);
                            }
                            ?>
                        </div>
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

    public static function joomsport_match_save_metabox($post_id, $post){
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['joomsport_match_nonce'] ) ? sanitize_text_field($_POST['joomsport_match_nonce']) : '';
        $nonce_action = 'joomsport_match_savemetaboxes';
 
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
        
        if('joomsport_match' == $_POST['post_type'] ){
            self::saveMetaScore($post_id, $_POST);
            self::saveMetaAbout($post_id);
            self::saveMetaGeneral($post_id, $_POST);

            self::saveMetaPlayerEvents($post_id, $_POST);
            self::saveMetaMatchEvents($post_id, $_POST);
            
            self::saveMetaEF($post_id, $_POST);
            self::saveMetaLineup($post_id, $_POST);
            self::saveMetaSubs($post_id, $_POST);
            self::saveMetaBoxScore($post_id);

            $season_id = get_post_meta($post_id, '_joomsport_seasonid',true);
            
            
            $home_team = get_post_meta( $post_id, '_joomsport_home_team', true );
            $away_team = get_post_meta( $post_id, '_joomsport_away_team', true );

            jsHelperMatchesDB::updateMatchDB($post_id);

            do_action('joomsport_update_standings',$season_id, array($home_team,$away_team));
            
            do_action('joomsport_update_playerlist',$season_id, array($home_team,$away_team));
            do_action("joomsport_custom_tab_be_save", $post_id);


        }
    }
    
    public static function saveMetaScore($post_id, $post){
        $maps = array();
        
        $md = wp_get_post_terms($post_id,'joomsport_matchday');
        $mdID = $md[0]->term_id;
        $metas = get_option("taxonomy_{$mdID}_metas");
        
        if(isset($post['mapid']) && count($post['mapid'])){
            for($intA=0;$intA<count($post['mapid']);$intA++){
                $map_index = intval($post['mapid'][$intA]);
                if($post['t1map'][$intA]!='' || $post['t2map'][$intA]!=''){
                    $map_team1 = intval($post['t1map'][$intA]);
                    $map_team2 = intval($post['t2map'][$intA]);
                    $maps[$post['mapid'][$intA]] = array($map_team1,$map_team2);
                }
            }
        }
        update_post_meta($post_id, '_joomsport_match_maps', $maps);
        
        
        $prev_home_score = get_post_meta( $post_id, '_joomsport_home_score', true );
        $prev_away_score = get_post_meta( $post_id, '_joomsport_away_score', true );
        
        
        
        $jmscore = isset($post['jmscore']) ? $post['jmscore']:array();
        $jmscore = array_map( 'sanitize_text_field', wp_unslash($jmscore));
        update_post_meta($post_id, '_joomsport_match_jmscore', $jmscore);
        if(!$metas['matchday_type']){
            update_post_meta($post_id, '_joomsport_home_score', intval($post['score1']));
            update_post_meta($post_id, '_joomsport_away_score', intval($post['score2']));
            
            $home_score = intval($post['score1']);
            $away_score = intval($post['score2']);
        }
        if(isset($post['knteamid']) && count($post['knteamid'])){
            update_post_meta($post_id, '_joomsport_home_team', intval($post['knteamid'][0]));
            update_post_meta($post_id, '_joomsport_away_team', intval($post['knteamid'][1]));
            if(isset($post['knteamscore']) && count($post['knteamscore'])){
                update_post_meta($post_id, '_joomsport_home_score', intval($post['knteamscore'][0]));
                update_post_meta($post_id, '_joomsport_away_score', intval($post['knteamscore'][1]));
                $home_score = intval($post['knteamscore'][0]);
                $away_score = intval($post['knteamscore'][1]);
            }
        }
        
        if($prev_home_score != $home_score || $prev_away_score != $away_score){
            do_action("joomsport_score_changed", $post_id);
        }

    }
    private static function saveMetaAbout($post_id){
        $meta_data = isset($_POST['about'])?  wp_kses_post($_POST['about']):'';
        update_post_meta($post_id, '_joomsport_match_about', $meta_data);
    }
    public static function saveMetaGeneral($post_id, $post){
        $meta_array = array();
        $metadata = get_post_meta($post_id,'_joomsport_match_general',true);
        if(!is_array($metadata)){
            $metadata = array();
        }
        $metadata['match_duration'] = isset($post['match_duration'])?  intval($post['match_duration']):'';
        update_post_meta($post_id, '_joomsport_match_general', $metadata);
        
        $m_played = intval($post['m_played']);
        $m_date = sanitize_text_field($post['m_date']);
        $m_time = sanitize_text_field($post['m_time']);
        update_post_meta($post_id, '_joomsport_match_played', $m_played);
        update_post_meta($post_id, '_joomsport_match_date', $m_date);
        update_post_meta($post_id, '_joomsport_match_time', $m_time);
        $venue_id = isset($post['venue_id'])?  intval($post['venue_id']):0;
        update_post_meta($post_id, '_joomsport_match_venue', $venue_id);
    }

    public static function saveMetaPlayerEvents($post_id, $post){
        global $wpdb;
        $season_id = JoomSportHelperObjects::getMatchSeason($post_id);
        
        $t_single = JoomSportHelperObjects::getTournamentType($season_id);
        $wpdb->delete( $wpdb->joomsport_match_events, array( 'match_id' => $post_id ), array( '%d' ) );
        if(isset($post['em_id']) && count($post['em_id'])){
            for($intA=0;$intA<count($post['em_id']);$intA++){

                $arr = explode('*', $post['new_player'][$intA]);
                if(count($arr) > 1){
                    $player_id = intval($arr[1]);
                    $team_id = intval($arr[0]);
                }else{
                    $player_id = intval($post['new_player'][$intA]);
                    $team_id = 0;
                }
                $minutes = sanitize_text_field($post['e_minuteval'][$intA]);
                $minutes_float = floatval(str_replace(':', '.', $minutes));
                $wpdb->insert($wpdb->joomsport_match_events, 
                        array(
                            "e_id" => intval($post['new_eventid'][$intA]),
                            "match_id" => $post_id,
                            "player_id" => $player_id,
                            "t_id" => $team_id,
                            "ecount" => intval($post['e_countval'][$intA]),
                            "minutes" => $minutes_float,
                            "eordering" => $intA,
                            "season_id" => $season_id,
                            "minutes_input" => $minutes,
                            "stage_id" => intval($post['stage_id'][$intA]),
                        ),
                        array('%d', '%d','%d','%d','%d','%s','%d', '%d','%s')
                );
                $curevent = $wpdb->insert_id;
                
                //subevents
                if(isset($post['sub_eventid_vals'][$intA]) && $post['sub_eventid_vals'][$intA]){
                    $plisSubArr = explode(',', $post['sub_eventid_vals'][$intA]);
                    $eorderingSub = 0;
                    if(count($plisSubArr)){
                        foreach($plisSubArr as $plisSub){
                            $plis = explode('*', $plisSub);

                            $wpdb->insert($wpdb->joomsport_match_events, 
                                    array(
                                        "e_id" => intval($post['sub_eventid'][$intA]),
                                        "match_id" => $post_id,
                                        "player_id" => intval($plis[1]),
                                        "t_id" => intval($plis[0]),
                                        "ecount" => intval($post['e_countval'][$intA]),
                                        "minutes" => $minutes_float,
                                        "eordering" => $eorderingSub,
                                        "season_id" => $season_id,
                                        "minutes_input" => $minutes,
                                        "additional_to" => $curevent,
                                        "stage_id" => intval($post['stage_id'][$intA]),
                                    ),
                                    array('%d', '%d','%d','%d','%d','%s','%d', '%d','%s', '%d')
                            );
                            
                            $eorderingSub++;
                        }
                    }
                }
                
            }
        }

    }
    public static function saveMetaMatchEvents($post_id, $post){
        $meta_array = array();
        if(isset($post['mevent_id']) && count($post['mevent_id'])){
            for($intA=0;$intA<count($post['mevent_id']);$intA++){
                if($post['mevents1'][$intA] || $post['mevents2'][$intA]){
                    $meta_array[$post['mevent_id'][$intA]] = array(
                        "mevents1" => intval($post['mevents1'][$intA]),
                        "mevents2" => intval($post['mevents2'][$intA])
                    );
                }
            }
        }
        //$meta_data = serialize($meta_array);
        update_post_meta($post_id, '_joomsport_matchevents', $meta_array);
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
        update_post_meta($post_id, '_joomsport_match_ef', $meta_array);
    }
    public static function saveMetaLineup($post_id, $post){
        global $wpdb;
        $home_team = get_post_meta( $post_id, '_joomsport_home_team', true );
        $away_team = get_post_meta( $post_id, '_joomsport_away_team', true );
        $season_id = JoomSportHelperObjects::getMatchSeason($post_id);
        $wpdb->delete( $wpdb->joomsport_squad, array( 'match_id' => $post_id ), array( '%d' ) );

        
        $sq1 = isset($post['t1_squard'])?$post['t1_squard']:array();
        for($intA=0;$intA<count($sq1);$intA++){
            if(isset($post['squadradio1_'.$sq1[$intA]])){

                $wpdb->insert($wpdb->joomsport_squad, 
                        array(
                            "player_id" => intval($sq1[$intA]),
                            "match_id" => $post_id,
                            "team_id" => $home_team,
                            "season_id" => $season_id,
                            "squad_type" => intval($post['squadradio1_'.$sq1[$intA]])
                        ),
                        array('%d', '%d','%d','%d','%s')
                );

            }
            
        }

        
        $meta_array = array();
        $sq2 = isset($post['t2_squard'])?$post['t2_squard']:array();
        for($intA=0;$intA<count($sq2);$intA++){
            if(isset($post['squadradio2_'.$sq2[$intA]])){
                $wpdb->insert($wpdb->joomsport_squad, 
                        array(
                            "player_id" => intval($sq2[$intA]),
                            "match_id" => $post_id,
                            "team_id" => $away_team,
                            "season_id" => $season_id,
                            "squad_type" => intval($post['squadradio2_'.$sq2[$intA]])
                        ),
                        array('%d', '%d','%d','%d','%s')
                );
            }
            
        }

    }
    public static function saveMetaSubs($post_id, $post){
        global $wpdb;
        $home_team = get_post_meta( $post_id, '_joomsport_home_team', true );
        $away_team = get_post_meta( $post_id, '_joomsport_away_team', true );
        $season_id = JoomSportHelperObjects::getMatchSeason($post_id);
        $sq1 = isset($post['players_team1_out_arr'])?$post['players_team1_out_arr']:array();
        for($intA=0;$intA<count($sq1);$intA++){
            $query = 'SELECT squad_type'
                    .' FROM '.$wpdb->joomsport_squad.' as s'
                    .' WHERE s.match_id='.$post_id
                    .' AND s.team_id = '.$home_team
                    ." AND s.is_subs!='0'"
                    .' AND s.player_id='.intval($sq1[$intA]);
            $allreadyused = (int) $wpdb->get_var($query);
            if(!$allreadyused){    
                $wpdb->update($wpdb->joomsport_squad, 
                        array(
                            "is_subs" => '1',
                            "minutes" => sanitize_text_field($post['minutes1_arr'][$intA]),
                            "player_subs" => intval($post['players_team1_in_arr'][$intA]),
                        ),
                        array(
                            "player_id" => intval($sq1[$intA]),
                            "match_id" => $post_id,
                            "team_id" => $home_team,
                            "season_id" => $season_id,
                        ),
                        array('%s', '%s','%d'),
                        array('%d', '%d','%d', '%d')
                );
            }else{
                $wpdb->insert($wpdb->joomsport_squad, 
                        array(
                            "player_id" => intval($sq1[$intA]),
                            "match_id" => $post_id,
                            "team_id" => $home_team,
                            "season_id" => $season_id,
                            "squad_type" => intval($allreadyused),
                            "is_subs" => '1',
                            "minutes" => sanitize_text_field($post['minutes1_arr'][$intA]),
                            "player_subs" => intval($post['players_team1_in_arr'][$intA]),
                        ),
                        array('%d', '%d','%d','%d','%s','%s', '%s','%d')
                );
            }
            
            $query = 'SELECT squad_type'
                    .' FROM '.$wpdb->joomsport_squad.' as s'
                    .' WHERE s.match_id='.$post_id
                    .' AND s.team_id = '.$home_team
                    ." AND s.is_subs!='0'"
                    .' AND s.player_id='.intval($post['players_team1_in_arr'][$intA]);
            $allreadyused = (int) $wpdb->get_var($query);
            if(!$allreadyused){
                $wpdb->update($wpdb->joomsport_squad, 
                        array(
                            "is_subs" => '-1',
                            "minutes" => sanitize_text_field($post['minutes1_arr'][$intA]),
                            "player_subs" => intval($sq1[$intA]),
                        ),
                        array(
                            "player_id" => intval($post['players_team1_in_arr'][$intA]),
                            "match_id" => $post_id,
                            "team_id" => $home_team,
                            "season_id" => $season_id,
                        ),
                        array('%s', '%s','%d'),
                        array('%d', '%d','%d', '%d')
                );
            }else{
                $wpdb->insert($wpdb->joomsport_squad, 
                        array(
                            "player_id" => intval($post['players_team1_in_arr'][$intA]),
                            "match_id" => $post_id,
                            "team_id" => $home_team,
                            "season_id" => $season_id,
                            "squad_type" => intval($allreadyused),
                            "is_subs" => '-1',
                            "minutes" => sanitize_text_field($post['minutes1_arr'][$intA]),
                            "player_subs" => intval($sq1[$intA]),
                        ),
                        array('%d', '%d','%d','%d','%s','%s', '%s','%d')
                );
            }
            
            
        }
        

        $sq2 = isset($post['players_team2_out_arr'])?$post['players_team2_out_arr']:array();
        for($intA=0;$intA<count($sq2);$intA++){

            
            $query = 'SELECT squad_type'
                    .' FROM '.$wpdb->joomsport_squad.' as s'
                    .' WHERE s.match_id='.$post_id
                    .' AND s.team_id = '.$away_team
                    ." AND s.is_subs!='0'"
                    .' AND s.player_id='.intval($sq2[$intA]);
            $allreadyused = (int) $wpdb->get_var($query);
            if(!$allreadyused){    
                $wpdb->update($wpdb->joomsport_squad, 
                        array(
                            "is_subs" => '1',
                            "minutes" => sanitize_text_field($post['minutes2_arr'][$intA]),
                            "player_subs" => intval($post['players_team2_in_arr'][$intA]),
                        ),
                        array(
                            "player_id" => intval($sq2[$intA]),
                            "match_id" => $post_id,
                            "team_id" => $away_team,
                            "season_id" => $season_id,
                        ),
                        array('%s', '%s','%d'),
                        array('%d', '%d','%d', '%d')
                );
            }else{
                $wpdb->insert($wpdb->joomsport_squad, 
                        array(
                            "player_id" => intval($sq2[$intA]),
                            "match_id" => $post_id,
                            "team_id" => $away_team,
                            "season_id" => $season_id,
                            "squad_type" => intval($allreadyused),
                            "is_subs" => '1',
                            "minutes" => sanitize_text_field($post['minutes2_arr'][$intA]),
                            "player_subs" => intval($post['players_team2_in_arr'][$intA]),
                        ),
                        array('%d', '%d','%d','%d','%s','%s', '%s','%d')
                );
            }
            
            $query = 'SELECT squad_type'
                    .' FROM '.$wpdb->joomsport_squad.' as s'
                    .' WHERE s.match_id='.$post_id
                    .' AND s.team_id = '.$away_team
                    ." AND s.is_subs!='0'"
                    .' AND s.player_id='.intval($post['players_team2_in_arr'][$intA]);
            $allreadyused = (int) $wpdb->get_var($query);
            if(!$allreadyused){
                $wpdb->update($wpdb->joomsport_squad, 
                        array(
                            "is_subs" => '-1',
                            "minutes" => sanitize_text_field($post['minutes2_arr'][$intA]),
                            "player_subs" => intval($sq2[$intA]),
                        ),
                        array(
                            "player_id" => intval($post['players_team2_in_arr'][$intA]),
                            "match_id" => $post_id,
                            "team_id" => $away_team,
                            "season_id" => $season_id,
                        ),
                        array('%s', '%s','%d'),
                        array('%d', '%d','%d', '%d')
                );
            }else{
                $wpdb->insert($wpdb->joomsport_squad, 
                        array(
                            "player_id" => intval($post['players_team2_in_arr'][$intA]),
                            "match_id" => $post_id,
                            "team_id" => $away_team,
                            "season_id" => $season_id,
                            "squad_type" => intval($allreadyused),
                            "is_subs" => '-1',
                            "minutes" => sanitize_text_field($post['minutes2_arr'][$intA]),
                            "player_subs" => intval($sq2[$intA]),
                        ),
                        array('%d', '%d','%d','%d','%s','%s', '%s','%d')
                );
            }
            
               
        }
    }
    private static function saveMetaBoxScore($post_id){
        global $wpdb;
       $home_team = get_post_meta( $post_id, '_joomsport_home_team', true );
       $away_team = get_post_meta( $post_id, '_joomsport_away_team', true );
       $season_id = JoomSportHelperObjects::getMatchSeason($post_id);
       $h_players = get_post_meta($home_team,'_joomsport_team_players_'.$season_id,true);
       $h_players = JoomSportHelperObjects::cleanJSArray($h_players);
       for($intA=0;$intA<count($h_players);$intA++){
           $insert_field = '';
           $insert_vals = '';
           $update_vals = '';
           $box_data = filter_input(INPUT_POST, 'boxstat_'.$home_team.'_'.$h_players[$intA], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
           if(is_array($box_data) && count($box_data)){
               foreach($box_data as $key=>$value){
                   $insert_field .= ',boxfield_'.intval($key);
                   $insert_vals .= ",".($value != ''?floatval($value):'NULL');
                   if($update_vals){
                       $update_vals .= ",";
                   }
                   $update_vals .= "boxfield_".intval($key)."=".($value != ''?floatval($value):'NULL');
               }
           }
           
           $dobl = $wpdb->get_var("SELECT id FROM {$wpdb->joomsport_box_match} WHERE match_id={$post_id} AND player_id={$h_players[$intA]} AND team_id={$home_team}");
           if($dobl){
               if($update_vals){
                    $wpdb->query("UPDATE {$wpdb->joomsport_box_match} SET $update_vals"
                       . " WHERE id={$dobl}");
               }
           }else{
               $wpdb->query("INSERT INTO {$wpdb->joomsport_box_match}(match_id,season_id,team_id,player_id".$insert_field.")"
                       . " VALUES({$post_id},{$season_id},{$home_team},{$h_players[$intA]}".$insert_vals.")");
           }
       }
       
       $a_players = get_post_meta($away_team,'_joomsport_team_players_'.$season_id,true);
       $a_players = JoomSportHelperObjects::cleanJSArray($a_players);
       for($intA=0;$intA<count($a_players);$intA++){
           $insert_field = '';
           $insert_vals = '';
           $update_vals = '';
           $box_data = filter_input(INPUT_POST, 'boxstat_'.$away_team.'_'.$a_players[$intA], FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
           if(is_array($box_data) && count($box_data)){
               foreach($box_data as $key=>$value){
                   $insert_field .= ',boxfield_'.intval($key);
                   $insert_vals .= ",".($value != ''?floatval($value):'NULL');
                   if($update_vals){
                       $update_vals .= ",";
                   }
                   $update_vals .= "boxfield_".intval($key)."=".($value != ''?floatval($value):'NULL');
               }
           }
           
           $dobl = $wpdb->get_var("SELECT id FROM {$wpdb->joomsport_box_match} WHERE match_id={$post_id} AND player_id={$a_players[$intA]} AND team_id={$away_team}");
           if($dobl){
               if($update_vals){
                    $wpdb->query("UPDATE {$wpdb->joomsport_box_match} SET $update_vals"
                       . " WHERE id={$dobl}");
               }
           }else{
               $wpdb->query("INSERT INTO {$wpdb->joomsport_box_match}(match_id,season_id,team_id,player_id".$insert_field.")"
                       . " VALUES({$post_id},{$season_id},{$away_team},{$a_players[$intA]}".$insert_vals.")");
           }
       }
       do_action('joomsport_calculate_boxscore', $post_id);
    }

}