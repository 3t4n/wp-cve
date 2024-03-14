<?php
/**<!--WPJSSTDDEL--!>
 * WP-JoomSport
 * @author      BearDev
 * @package     JoomSport
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class JoomSportAchievmentsHelperObject{
    public static function getRankingCriteria($season_id){
        $post = get_post($season_id);
        $metadata = get_post_meta($post->ID,'_jsprt_achv_season_ranking',true);
        if((!isset($metadata['sRanking']) || !$metadata['sRanking']) && $post->post_parent != 0){
            return self::getRankingCriteria($post->post_parent);
            
        }
        return $metadata;
    }
    public static function getPointsByPlace($season_id){
        $post = get_post($season_id);
        $metadata = get_post_meta($post->ID,'_jsprt_achv_season_points',true);
        if((!isset($metadata['ranking_criteria']) || $metadata['ranking_criteria'] == '0') 
                && (!isset($metadata['pts_by_place']) || !count($metadata['pts_by_place'])) 
                && $post->post_parent != 0){
            return self::getPointsByPlace($post->post_parent);
            
        }
        return $metadata;
    }
    public static function getSeasonRankingCriteria($season_id){
        $post = get_post($season_id);
        $metadata = get_post_meta($post->ID,'_jsprt_achv_season_points',true);
        if((!isset($metadata['ranking_method']) || !$metadata['ranking_method']) && $post->post_parent != 0){
            return self::getSeasonRankingCriteria($post->post_parent);
            
        }
        return $metadata;
    }
}