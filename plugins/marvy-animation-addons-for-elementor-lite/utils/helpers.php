<?php

use Elementor\Plugin;


/***
 * @return array|mixed
 */
function marvy_get_config() {
    return !empty($GLOBALS['marvy_config']) && !empty($GLOBALS['marvy_config']['bg-animation']) ?  $GLOBALS['marvy_config']['bg-animation'] : [];
}

/****
 * @param null $element
 *
 * @return array|mixed
 */
function marvy_get_setting($element = null)
{
    $animations = marvy_get_config();
    $defaults = array_fill_keys(array_keys($animations), true);
    $elements = get_option('marvy_option_settings');
    $elements = !empty($elements) ? $elements : [];
    $elements = array_merge($defaults, $elements);

    return (!empty($element) ? (!empty($elements[$element]) ? $elements[$element] : []) : array_keys(array_filter($elements)));
}

/****
 * @return bool
 */
function marvy_is_preview_mode()
{
    if (isset($_REQUEST['elementor-preview'])) {
        return false;
    }

    if (isset($_REQUEST['ver'])) {
        return false;
    }

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor') {
        return false;
    }

    $url_params = !empty($_SERVER['HTTP_REFERER']) ?  parse_url($_SERVER['HTTP_REFERER'],PHP_URL_QUERY) : parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY);
    
    $url_params !=null && parse_str($url_params,$params);
    if(!empty($params['action']) && $params['action'] == 'elementor'){
        return false;
    }

    return true;
}

/****
 * @param null $id
 *
 * @return array|false[]|string[]|void
 */
function marvy_filter_widgets($id = null)
{
    $animations = marvy_get_config();
    $defaults = array_keys($animations);
    $get_setting = marvy_get_setting();
    $defaults = array_intersect($defaults, $get_setting);
    $new_default = array_map(function ($animation) {
        return 'marvy_enable_' . $animation;
    }, $defaults);

	$elementor_page = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );
	if (!(! ! $elementor_page) ) return;
//	if (!Plugin::$instance->db->is_built_with_elementor($id)) return;

    $elements = Plugin::$instance->documents->get($id);
    $collections = [];
    if(!empty($elements)) {
        $collections = get_marvy_animation_in_content($elements->get_elements_data(), $new_default);
    }

    return array_map(function ($animation) {
        return substr($animation, 13);
    }, array_intersect($new_default, $collections));

}

/******
 * @param $elements
 * @param $animation_list
 *
 * @return array
 */
function get_marvy_animation_in_content($elements,$animation_list){
    $animations = [];
    foreach ($elements as $element) {
        // collect extensions for section
        if (isset($element['elType']) && in_array($element['elType'],["section","container"])) {
            $keys = array_values(array_filter(array_keys($element['settings']),function($val) use ($animation_list){
                return in_array($val,$animation_list);
            }));
            $animations = array_merge($animations,$keys);
        }
        if (!empty($element['elements'])) {
            $animations = array_merge($animations, get_marvy_animation_in_content($element['elements'],$animation_list));
        }
    }
    return $animations;
}

/******
 * @return bool
 */
function isMarvyProInstall()
{
    return get_option('MarvyPro_is_install') == 1;
}

/******
 * @param false $is_deactivate
 */
function marvy_plugin_activation($is_deactivate = false)
{
    $pluginName = "Marvy";
    $arg = 'plugin='. $pluginName . '&domain=' . get_bloginfo('wpurl') . '&site_name=' . get_bloginfo('name');
    if ($is_deactivate) {
        $arg .= '&is_deactivated=true';
    }
    wp_remote_get('https://innoquad.in/plugin-server/active-server.php?' . $arg);
}

/******
 *
 */
function marvy_check_pro(){
    $is_install = false;
    if (!function_exists('get_plugins')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    foreach ($plugins as $key => $data) {
        if ($data['TextDomain'] === "marvy-animation-addons") {
            $is_install = true;
        }
    }
    if(!$is_install){
        delete_option('MarvyPro_is_install');
    }
}