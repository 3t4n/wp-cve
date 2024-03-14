<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_TermUtils
{

    public static function getTaxonomyNameBySensor($sensorId, $default=null){
        if(is_null($default)) {
            $taxonomy = __('Taxonomy', 'wp-admin-audit');
        }else{
            $taxonomy = $default;
        }
        switch($sensorId){
            case WADA_Sensor_Base::EVT_CATEGORY_CREATE:
            case WADA_Sensor_Base::EVT_CATEGORY_UPDATE:
            case WADA_Sensor_Base::EVT_CATEGORY_DELETE:
                $taxonomy = __('Category', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_POST_TAG_CREATE:
            case WADA_Sensor_Base::EVT_POST_TAG_UPDATE:
            case WADA_Sensor_Base::EVT_POST_TAG_DELETE:
                $taxonomy = __('Tag', 'wp-admin-audit');
                break;
            case WADA_Sensor_Base::EVT_MENU_CREATE:
            case WADA_Sensor_Base::EVT_MENU_UPDATE:
            case WADA_Sensor_Base::EVT_MENU_DELETE:
                $taxonomy = __('Menu', 'wp-admin-audit');
                break;
        }
        return $taxonomy;
    }

    /**
     * @return array
     */
    protected static function getTermAttributes(){
        return array('name', 'slug', 'description', 'parent');
    }

    /**
     * @param WP_Term $currentTerm
     * @param WP_Term $priorTerm
     * @return array<array>
     */
    public static function getTermChanges($currentTerm, $priorTerm){
        $attributes2Check = self::getTermAttributes();
        $changedAttributes = WADA_CompUtils::getChangedAttributes($priorTerm, $currentTerm, $attributes2Check);

        if(property_exists($currentTerm, 'parent')
            && property_exists($priorTerm, 'parent')
            && $currentTerm->parent != $priorTerm->parent){
            $parentNameCurr = get_term($currentTerm->parent, $currentTerm->taxonomy)->name;
            $parentNamePrior = get_term($priorTerm->parent, $priorTerm->taxonomy)->name;
            $changedAttributes[] = array(
                'info_key' => 'parent_name',
                'info_value' => $parentNameCurr,
                'prior_value' => $parentNamePrior
            );
        }
        return $changedAttributes;
    }

}
