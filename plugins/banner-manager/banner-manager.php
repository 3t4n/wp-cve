<?php
/*
Plugin Name: banner-manager
Description: Manage your image banners, count views and clicks, etc, ...
Version: 16.04.19
Author: karrikas
Author URI: http://karrikas.com/
Author Email: karrikas@karrikas.com
License: GPL3
*/

define('BM_PLUGIN_DIR', dirname(__FILE__));
define('BM_PLUGIN_FILE', __FILE__);
define('BM_PLUGIN_URL', WP_PLUGIN_URL .'/banner-manager');

global $wpdb;
define('BM_TABLE_BANNERS', $wpdb->prefix . 'bm_banners');
define('BM_TABLE_GROUPS', $wpdb->prefix . 'bm_groups');
define('BM_TABLE_STATS', $wpdb->prefix . 'bm_stats');



/**
 * GLOBAL groupskey
 * Publikatzen diren bannerren gakoak gordetzen joaten dira
 * bannerrak ez errepikatzeko.
 */
$GLOBALS['groupkey'] = array();

// textgdomain
load_plugin_textdomain('banner-manager', false, basename(dirname(__FILE__)) .'/languages');

// Install plugin
require_once(BM_PLUGIN_DIR .'/install.php');

// Uninstall plugin
require_once(BM_PLUGIN_DIR .'/uninstall.php');

// generate pages
require_once(BM_PLUGIN_DIR .'/pages.php');

// generate widget
require_once(BM_PLUGIN_DIR .'/widget.php');

// redirect when click
require_once(BM_PLUGIN_DIR .'/redirect.php');

// add js code
function add_scripts()
{
    wp_register_script('jquery', false, array(), false, true);
    wp_register_script('swfobject', false, array(), false, true);

    // scripta erregistratu
    //wp_register_script('banner-manager', BM_PLUGIN_URL .'/load.js', array('jquery', 'swfobject'), false, true);
    wp_register_script('banner-manager', BM_PLUGIN_URL .'/load.min.js', array('jquery', 'swfobject'), false, true);

    // kargatzeko ilaran jarri
    wp_enqueue_script('banner-manager');
}

add_action('init', 'add_scripts');

/**
 * Add banner to template.
 * @param $category
 */
function wp_banner_manager( $category = null )
{
    // if not category end
    if(!data::get_category( $category ))
    {
        return;
    }

    // get randon banner
    $banners = data::get_banners_by_group_rand( $category );

    // seguru egon groupkey-a ez dela erabili
    /**
     * TODO: Ez errepikatzeko kode hau akatsa duna da, hurrengo taldetan
     * lehenago zehatutako taldea badago bakarrik adibidez errepikatu
     * egingo litzateke.
     */
    if($banners)
    {
        foreach($banners as $sel_banner)
        {
            if(!in_array($sel_banner->groupkey,$GLOBALS['groupkey']))
            {
                $banner = $sel_banner;
                continue;
            }
        }
        // ez bada batez zehaztu, lehena hartu
        if(!isset($banner))
        {
            $banner = $banners[0];
        }
    }

    // if not banner end
    if(!isset($banner))
    {
        return;
    }

    // taldea gorde ez errepikatzeko
    $GLOBALS['groupkey'][] = $banner->groupkey;


    // width and height
    $intWidth = $banner->width;
    $intHeight = $banner->height;

    // url
    $url = sprintf('%s?action=redirect&id=%d&url=%s', admin_url('admin-ajax.php'), $banner->id_banner, $banner->link);

    // type
    $type = pathinfo($banner->src,PATHINFO_EXTENSION);

    $blank = ($banner->blank)? 'true' : 'false';
    $blank_st = ($banner->blank)? '_blank' : '_self';
    $html = sprintf('<a href="%s" target="%s"><img alt="%s" src="%s"></a>',$url, $blank_st, esc_attr($banner->title), $banner->src);

    // generate id
    $id = 'id'. md5(rand());
    ?>
    <div class="wp_bm_banner_set" id="<?php echo $id; ?>">
        <input type="hidden" name="src" value="<?php echo $banner->src?>" />
        <input type="hidden" name="link" value="<?php echo $url;?>" />
        <input type="hidden" name="blank" value="<?php echo $blank;?>" />
        <input type="hidden" name="type" value="<?php echo $type;?>" />
        <input type="hidden" name="width" value="<?php echo $intWidth; ?>" />
        <input type="hidden" name="height" value="<?php echo $intHeight; ?>" />
    </div>
    <noscript><?php echo $html; ?></noscript>
    <?php
    // count view
    data::add_stat_view( $banner->id_banner );
}

/**
 * Allow upload swf files
 */
function bm_allow_swf($mimes) {
    $mimes['swf'] = 'application/x-shockwave-flash';
    return $mimes;
}
add_filter('upload_mimes','bm_allow_swf');
