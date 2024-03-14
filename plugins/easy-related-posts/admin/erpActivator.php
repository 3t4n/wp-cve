<?php

/**
 * Easy related posts .
 *
 * @package   Easy_Related_Posts
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * Activator class.
 *
 * @package Easy_Related_Posts
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpActivator {

    /**
     * Checks the options names from array1 if they are pressent in array2
     *
     * @param array $array1 Associative options array (optionName => optionValue)
     * @param array $array2 Associative options array (optionName => optionValue)
     * @return array An array containing the options names that are present only in array1
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function optionArraysDiff(Array $array1, Array $array2) {
        $keys1 = array_keys($array1);
        $keys2 = array_keys($array2);
        return array_diff($keys1, $keys2);
    }

    /**
     * Inserts to main options array in DB values that are present in $newOpts and not in $oldOpts
     *
     * @param array $newOpts New options array
     * @param array $oldOpts Old options array, default to main options present in DB
     * @param string $optsName Options name, default to erp main options array
     * @return boolean True if operation was succefull, false otherwise
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function addNonExistingMainOptions(Array $newOpts, $optsName, Array $oldOpts = NULL) {
        if (!is_string($optsName)) {
            return FALSE;
        }
        if (empty($oldOpts)) {
            $oldOpts = get_option($optsName);
        }
        $merged = is_array($oldOpts) ? $oldOpts + $newOpts : $newOpts;
        return update_option($optsName, $merged);
    }

    /**
     * Inserts non existing widget options in DB that are present in $newOpts and not in $oldOpts
     * @param array $newOpts New options array
     * @param array $oldOpts Old options array, default to widget options present in DB
     * @param string $optsName Options name, default to erp widget options array
     * @return boolean False if operation was successfull, false otherwise
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function addNonExistingWidgetOptions(Array $newOpts, $optsName, Array $oldOpts = NULL) {
        if (!is_string($optsName)) {
            return FALSE;
        }
        if (empty($oldOpts)) {
            $oldOpts = get_option($optsName);
        }
        if (empty($oldOpts)) {
            return add_option($optsName, array(1 => $newOpts));
        }
        foreach ($oldOpts as $k => $v) {
            /**
             * wid_erp_title is for backward compatability with version 1.*
             */
            if (is_array($v) && (isset($v['title']) || isset($v['wid_erp_title']))) {
                if(isset($v['wid_erp_title'])){
                    $oldOpts[$k] = array_merge($newOpts, self::translateOldWidOptions($oldOpts[$k]));
                } else {
                    $oldOpts[$k] = $oldOpts[$k] + $newOpts;
                }
            }
        }
        return update_option($optsName, $oldOpts);
    }
    
    private static function translateOldWidOptions($instance) {
        $opt = array();
        if(isset($instance['wid_erp_title'])){
            $opt['title'] = $instance['wid_erp_title'];
        }
        if(isset($instance['wid_getPostsBy'])){
            $opt['fetchBy']  = $instance['wid_getPostsBy'];
        }
        if(isset($instance['wid_num_of_p_t_dspl'])){
            $opt['numberOfPostsToDisplay']  = $instance['wid_num_of_p_t_dspl'];
        }
        
        $opt['content'] = array();
        if (isset($instance['wid_erp_thumb']) && $instance['wid_erp_thumb']) {
            array_push($opt['content'], 'thumbnail');
        }
        if (isset($instance['wid_erp_exc_or_tit']) && $instance['wid_erp_exc_or_tit'] == 'post_title') {
            array_push($opt['content'], 'title');
        } else {
            array_push($opt['content'], 'title');
            array_push($opt['content'], 'excerpt');
        }
        if(isset($instance ['wid_erp_pt_s'])){
            $opt['postTitleFontSize'] = $instance ['wid_erp_pt_s'];
        }
        if(isset($instance ['wid_erp_exc_s'])){
            $opt['excFontSize'] = $instance ['wid_erp_exc_s'];
        }
        
        $opt['dsplLayout'] = 'Basic';
        $opt['thumbCaption'] = false;
        
        if(isset($instance['wid_erp_thumb_crop'])){
            $opt['cropThumbnail'] = $instance['wid_erp_thumb_crop'] == 1;
        }
        if(isset($instance ['wid_erp_thumb_h'])){
            $opt['thumbnailHeight'] = $instance ['wid_erp_thumb_h'];
        }
        if(isset($instance ['wid_erp_thumb_w'])){
            $opt['thumbnailWidth'] = $instance ['wid_erp_thumb_w'];
        }
        if(isset($instance['wid_erp_pt_c'])){
            $opt['postTitleColor'] = isset($instance['wid_erp_pt_c_u']) && $instance['wid_erp_pt_c_u'] ? $instance['wid_erp_pt_c'] : '#ffffff';
        }
        if(isset($instance['wid_erp_exc_c'])){
            $opt['excColor'] = isset($instance['wid_erp_exc_c_u']) && $instance['wid_erp_exc_c_u'] ? $instance['wid_erp_exc_c'] : '#ffffff';
        }
        return $opt;
    }
}
