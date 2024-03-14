<?php
/**
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
$emblems = 0;

echo '<div class="col-xs-12">';
    if($rows && count($rows)){
    $seasonID = 0;
    echo '<div class="table-responsive"><table class="table">';
            foreach($rows as $match){
            $partic_home = $match->getParticipantHome();
            $partic_away = $match->getParticipantAway();
            $match_time = get_post_meta($match->id,'_joomsport_match_time',true);
            $match_date = get_post_meta($match->id,'_joomsport_match_date',true);

            if(isset($is_fav) && $is_fav){
                $match_time = classJsportDate::getDate($match_date, $match_time);
            }

            if($seasonID != $match->season_id){
                echo '<tr>';
                    echo '<td colspan="7" class="jsLiveSeason">'.esc_html($match->league).'</td>';
                echo '</tr>';
                $seasonID = $match->season_id;
            }

            echo '<tr id="jsLiveMatch_'.esc_attr($match->object->id).'">';
                echo '<td class="jsLiveTime">'.esc_html($match_time).'</td>';
                echo '<td class="jsLiveTeam jsLiveHome">';
                    if(is_object($partic_home)){
                    echo jsHelper::nameHTML($partic_home->getName(true));
                    }
                echo '</td>';
                echo '<td class="jsLiveTeamEmblem">';
                if($emblems && is_object($partic_home)){
                    echo wp_kses_post($partic_home->getEmblem(true, 0, 'emblInline', 0));
                }
                echo '</td>';
                echo '<td class="jsLiveScore">';
                    echo '<div id="modJsUpdScore'.esc_attr($match->id).'">';
                        echo jsHelper::getScore($match);
                        echo '</div>';
                echo '</td>';
                echo '<td class="jsLiveTeamEmblem">';
                if($emblems && is_object($partic_away)){
                    echo wp_kses_post($partic_away->getEmblem(true, 0, 'emblInline', 0));
                }
                echo '</td>';
                echo '<td class="jsLiveTeam jsLiveAway">';
                    if(is_object($partic_away)){
                    echo jsHelper::nameHTML($partic_away->getName(true));
                    }
                echo '</td>';
                echo '<td class="jsLiveFavour"><i class="fa fa-heart-o" data-id="'.esc_attr($match->id).'"></i></td>';
                echo '</tr>';
            }
            echo '</table></div>';
    }else{
        echo __('No matches found','joomsport-sports-league-results-management');
    }
echo '</div>';