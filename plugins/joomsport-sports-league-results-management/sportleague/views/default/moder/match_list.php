<?php
$seasons = JoomsportModerateHelper::getSeasonsParticipated();
$teamsObjs = JoomsportModerateHelper::getModerTeams();
$matches = JoomsportModerateHelper::getModerMatches($filters);

if(JoomsportModerateHelper::Can('match.add', 0)){ // can add Team
    ?>
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <ul class="nav navbar-nav pull-right">
                <button id="jsModerNewMatch" class="btn btn-default"><?php echo __('Add Match','joomsport-sports-league-results-management');?></button>
            </ul>
        </div>
    </nav>
    <?php
}
?>
<div class="form-group">
    <?php
    //select season
    if(count($seasons)){
        echo '<select id="moderSeasonFilter" name="moderSeasonFilter" class="form-control jswf-chosen-select moderSelectFilter jsleft">';
        echo '<option value="0">'.__('Select Season','joomsport-sports-league-results-management').'</option>';
        foreach ($seasons as $key => $value) {
            for($intA = 0; $intA < count($value); $intA++){
                $tm = $value[$intA];
                echo '<option value="'.esc_attr($tm->id).'" '.(isset($filters["seasonID"]) && $filters["seasonID"] == $tm->id?" selected":"").'>'.wp_kses_post($key .' '.$tm->name).'</option>';
            }
        }
        echo '</select>';
    }
    //select team
    if(count($teamsObjs)){
        echo '<select id="moderTeamFilter" name="moderTeamFilter" class="form-control jswf-chosen-select moderSelectFilter jsleft">';
        echo '<option value="0">'.__('Select Team','joomsport-sports-league-results-management').'</option>';
        foreach ($teamsObjs as $teamsObj) {
            echo '<option value="'.esc_attr($teamsObj->ID).'" '.(isset($filters["teamID"]) && $filters["teamID"] == $teamsObj->ID?" selected":"").'>'.wp_kses_post(get_the_title($teamsObj->ID)).'</option>';
        }
        echo '</select>';
    }
    ?>

</div>
<table class="table table-striped">
    <?php
    for($intA=0;$intA<count($matches);$intA++){
        echo '<tr>';
        echo '<td class="jsmodtrash jscenter">';
        if(JoomsportModerateHelper::Can('match.del', $matches[$intA]->object->ID)){
            echo '<i class="fa fa-trash jsmoderDelMatch" data-id="'.esc_attr($matches[$intA]->object->ID).'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '<td class="jsmodtourn jsleft">';
        $terms = wp_get_object_terms( $matches[$intA]->season_id, 'joomsport_tournament' );
        $post_name = '';
        if( $terms ){
            $post_name .= $terms[0]->name;
        }
        echo $title =  wp_kses_post($post_name ." ".get_the_title($matches[$intA]->season_id));
        echo '</td>';
        echo '<td class="jsmodteams jsleft">';
        $partic_home = $matches[$intA]->getParticipantHome();
        $partic_away = $matches[$intA]->getParticipantAway();
        if(is_object($partic_home)){
            echo wp_kses_post(jsHelper::nameHTML($partic_home->getName(false,0)));
        }
        if(is_object($partic_away)){
            echo wp_kses_post(jsHelper::nameHTML($partic_away->getName(false,0)));
        }
        echo '</td>';
        echo '<td class="jsmodedit jscenter">';
        if(JoomsportModerateHelper::Can('match.edit', $matches[$intA]->object->ID)){
            echo '<i class="fa fa-edit jsModerEditMatch" data-id="'.esc_attr($matches[$intA]->object->ID).'" aria-hidden="true"></i>';
        }
        echo '</td>';
        echo '<td class="jsmoddate jsleft">';
        $m_date = get_post_meta($matches[$intA]->object->ID,'_joomsport_match_date',true);
        $m_time = get_post_meta($matches[$intA]->object->ID,'_joomsport_match_time',true);
            $match_date_str = classJsportDate::getDate($m_date, $m_time);
            echo wp_kses_post($match_date_str);
        echo '</td>';
        echo '<td class="jsmodmday jsleft">';
            echo wp_kses_post($matches[$intA]->getMdayName());
        echo '</td>';
        echo '</tr>';
    }
    if(!count($matches)){
        echo '<div>'.__('No matches found','joomsport-sports-league-results-management').'</div>';
    }
    ?>
</table>