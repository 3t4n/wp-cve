<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_PostUtils
{

    /**
     * @param array $currentMeta
     * @param array $priorMeta
     * @return array<array>
     */
    public static function getChangedMeta($currentMeta, $priorMeta, $ignoreMetaKeys = array()){
        $changedMeta = array();

        $currentKeys = array_keys($currentMeta);
        $priorKeys = array_keys($priorMeta);
        $allMetaKeys = array_unique(array_merge($currentKeys, $priorKeys));

        $flattenHelpers = array(
            'WP_Post' => array( WADA_PostUtils::class, 'flattenPost' )
        );

        foreach($allMetaKeys AS $metaKey){
            if(in_array($metaKey, $ignoreMetaKeys)){
                WADA_Log::debug('getChangedMeta skip '.$metaKey);
                continue;
            }
            $inCurrent = in_array($metaKey, $currentKeys);
            $inPrior = in_array($metaKey, $priorKeys);
            $currentValue = $inCurrent ? WADA_PHPUtils::flattenArray($currentMeta[$metaKey]) : null;
            $priorValue = $inPrior ? WADA_PHPUtils::flattenArray($priorMeta[$metaKey]) : null;

            $changed = false;
            if($inCurrent && $inPrior){
                if($currentValue != $priorValue){
                    $changed = true;
                }
            }else {
                $changed = true;
            }

            if($changed){
                $changedMeta[] = array(
                    'info_key' => $metaKey,
                    'info_value' => $currentValue,
                    'prior_value' => $priorValue
                );
            }
        }

        return $changedMeta;
    }

    /**
     * @param string $currentPermalink
     * @param string $priorPermalink
     * @return array
     */
    public static function getChangedPermalink($currentPermalink, $priorPermalink){
        return WADA_CompUtils::getChangedString('permalink', $currentPermalink, $priorPermalink);
    }

    /**
     * @param string $currentTemplateSlug
     * @param string $priorTemplateSlug
     * @return array
     */
    public static function getChangedTemplateSlug($currentTemplateSlug, $priorTemplateSlug){
        return WADA_CompUtils::getChangedString('template-slug', $currentTemplateSlug, $priorTemplateSlug);
    }

    /**
     * @param string $taxonomy Taxonomy slug
     * @param array $currentTermIds An array of term taxonomy IDs
     * @param array $priorTermIds Old array of term taxonomy IDs
     * @param bool $append Whether to append new terms to the old terms
     */
    public static function getChangedTerms($taxonomy, $currentTermIds, $priorTermIds, $append){
        if($append){
            $currentTermIds = array_merge($currentTermIds, $priorTermIds);
            $currentTermIds = array_unique($currentTermIds, SORT_NUMERIC);
        }
        $allTermIds = array_unique(array_merge($currentTermIds, $priorTermIds), SORT_NUMERIC);

        $termNames = array();
        foreach($allTermIds AS $termId){
            $term = get_term($termId, $taxonomy);
            $termNames[$termId] = $term ? $term->name : '';
        }

        $changedTermIdsObjArr = WADA_CompUtils::getChangedIdsOfIdArrays($taxonomy, $currentTermIds, $priorTermIds);
        if($changedTermIdsObjArr && count($changedTermIdsObjArr)){
            foreach($changedTermIdsObjArr AS $key => $changeInfoArr){
                $changedTermIdsObjArr[$key] = array(
                    'info_key'      => $changeInfoArr['info_key'],
                    'info_value'    => $changeInfoArr['info_value'] ? $termNames[$changeInfoArr['info_value']].' (ID '.$changeInfoArr['info_value'].')' : null,
                    'prior_value'   => $changeInfoArr['prior_value'] ? $termNames[$changeInfoArr['prior_value']].' (ID '.$changeInfoArr['prior_value'].')' : null
                );
            }
        }

        return $changedTermIdsObjArr;
    }

    /**
     * @param WP_Post $wpPost
     * @return string
     */
    public static function flattenPost($wpPost){
        $attributesToRecord = array(
            'ID', 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_title', 'post_excerpt',
            'post_status', 'comment_status', 'ping_status', 'post_password', 'post_name',
            'to_ping', 'pinged',
            'post_modified', 'post_modified_gmt', 'post_content_filtered', 'post_parent', 'guid', 'menu_order',
            'post_type', 'post_mime_type', 'comment_count', 'filter');

        $arrResult = array();
        if($wpPost && is_object($wpPost)){
            foreach($attributesToRecord as $attribute){
                if(isset($currentPost->$attribute)) {
                    $arrResult[$attribute] = $currentPost->$attribute;
                }
            }
        }

        return WADA_PHPUtils::flattenArray($arrResult);
    }

}
