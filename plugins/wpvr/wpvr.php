<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://rextheme.com/
 * @since             7.3.6
 * @package           Wpvr
 *
 * @wordpress-plugin
 * Plugin Name:       WP VR
 * Plugin URI:        https://rextheme.com/wpvr/
 * Description:       WP VR - 360 Panorama and virtual tour creator for WordPress is a customized panaroma & virtual builder tool for WordPress Website.
 * Version:           8.4.0
 * Tested up to:      6.4
 * Author:            Rextheme
 * Author URI:        http://rextheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpvr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require plugin_dir_path(__FILE__) . 'elementor/elementor.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WPVR_VERSION', '8.4.0');
define('WPVR_FILE', __FILE__);
define("WPVR_PLUGIN_DIR_URL", plugin_dir_url(__FILE__));
define("WPVR_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
define("WPVR_PLUGIN_PUBLIC_DIR_URL", plugin_dir_url(__FILE__) . 'public/');
define('WPVR_BASE', plugin_basename(WPVR_FILE));
define( 'WPVR_DEV_MODE', false );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpvr-activator.php
 */
function activate_wpvr()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wpvr-activator.php';
    Wpvr_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpvr-deactivator.php
 */
function deactivate_wpvr()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wpvr-deactivator.php';
    Wpvr_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wpvr');
register_deactivation_hook(__FILE__, 'deactivate_wpvr');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wpvr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    7.3.6
 */
function run_wpvr()
{

    $plugin = new Wpvr();
    $plugin->run();

    // black friday banner class initialization
    new WPVR_Special_Occasion_Banner( 'christmas', '2023-12-21 19:00:00', '2024-01-05 23:59:00' );
}
run_wpvr();


/**
 * Array information checker
 *
 * @param mixed $needle
 * @param mixed $haystack
 * @param bool $strict
 *
 * @return bool
 * @since 7.3.6
 */
function wpvr_in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if ((($strict ? $item === $needle : $item == $needle)) || is_array($item) && wpvr_in_array_r($needle, $item, $strict)) {
            return true;
        }
    }
    return false;
}


/**
 * Initialize the plugin tracker
 *
 * @return void
 * @since 7.3.6
 */
function appsero_init_tracker_wpvr() {
    if (!class_exists('Appsero\Client')) {
        require_once __DIR__ . '/appsero/src/Client.php';
    }
    $client = new Appsero\Client( 'cab9761e-b067-4824-9c71-042df5d58598', 'WP VR', __FILE__ );

    // Active insights
    $client->insights()->init();
}

appsero_init_tracker_wpvr();


function wpvr_block()
{
    wp_register_script(
        'wpvr-block',
        plugins_url('build/index.build.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor')
    );

    if(is_admin()) {
        wp_enqueue_style(
            'gutyblocks/guty-block',
            plugins_url('src/view.css', __FILE__),
            array()
        );
    }


    if (function_exists('register_block_type')) {
        register_block_type('wpvr/wpvr-block', array(
            'attributes'      => array(
                'id' => array(
                    'type' => 'string',
                    'default' => '0',
                ),
                'width' => array(
                    'type' => 'string',
                    'default' => '600',
                ),
                'width_unit' => array(
                    'type' => 'string',
                    'default' => 'px',
                ),
                'height' => array(
                    'type' => 'string',
                    'default' => '400',
                ),
                'height_unit' => array(
                    'type' => 'string',
                    'default' => 'px',
                ),
                'mobile_height' => array(
                    'type' => 'string',
                    'default' => '300',
                ),
                'mobile_height_unit' => array(
                    'type' => 'string',
                    'default' => 'px',
                ),
                'radius' => array(
                    'type' => 'string',
                    'default' => '0',
                ),
                'border_width' => array(
                    'type' => 'string',
                    'default' => '0px',
                ),
                'border_style' => array(
                    'type' => 'string',
                    'default' => 'none',
                ),
                'border_color' => array(
                    'type' => 'string',
                    'default' => 'none',
                ),
                'radius_unit' => array(
                    'type' => 'string',
                    'default' => 'px',
                ),
                'content' => array(
                    'type' => 'string',
                    'source' => 'html',
                    'default' => '<script>          </script>'
                ),
            ),
            'editor_script' => 'wpvr-block',
            'render_callback' => 'wpvr_block_render',
        ));
    }
}

add_action('init', 'wpvr_block');

function wpvr_block_render($attributes)
{
    // var_dump($attributes);
    if (isset($attributes['id'])) {
        $id = $attributes['id'];
    } else {
        $id = 0;
    }
    if (isset($attributes['width'])) {
        $width = $attributes['width'];
    }
    if (isset($attributes['width_unit'])) {
        $width_unit = $attributes['width_unit'];
    }
    if (isset($attributes['height'])) {
        $height = $attributes['height'];
    }
    if (isset($attributes['height_unit'])) {
        $height_unit = $attributes['height_unit'];
    }
    if (isset($attributes['mobile_height'])) {
        $mobile_height = $attributes['mobile_height'];
    }
    if (isset($attributes['mobile_height_unit'])) {
        $mobile_height_unit = $attributes['mobile_height_unit'];
    }
    if (isset($attributes['radius']) && isset($attributes['radius_unit'])) {
        $radius = $attributes['radius'] . $attributes['radius_unit'];
    }
    $border_style = '';
    if(isset($attributes['border_width'],$attributes['border_style'],$attributes['border_color'])){
        $border_style = $attributes['border_width'] .' '. $attributes['border_style'] .' '.$attributes['border_color'];
    }

    if (isset($attributes['className'])) {
        $className = $attributes['className'];
    } else {
        $className = '';
    }
    $get_post = get_post_status($id);
    if ( $get_post !== 'publish' ) {
        return esc_html__('Oops! It seems like this post isn\'t published yet. Stay tuned for updates!', 'wpvr') ;
    }
    if( post_password_required(  $id ) ){
        return get_the_password_form();
    }
    $postdata = get_post_meta($id, 'panodata', true);
    $panoid = 'pano' . $id;
    $panoid2 = 'pano2' . $id;

    if (isset($postdata['streetviewdata'])) {
        if (empty($width)) {
            $width = '600px';
        }
        if (empty($height)) {
            $height = '400px';
        }
        $streetviewurl = $postdata['streetviewurl'];
        $html = '';
        $html .= '<div class="vr-streetview '.$className.'" style="text-align: center; max-width:100%; width:' . $width . $width_unit. '; height:' . $height . $height_unit.'; margin: 0 auto;">';
        $html .= '<iframe src="' . $streetviewurl . '" frameborder="0" style="border:0; width:100px; height:100%;" allowfullscreen=""></iframe>';
        $html .= '</div>';

        return $html;
    }
    $is_pro = apply_filters('is_wpvr_pro_active',false);

    if (isset($postdata['vidid'])) {
        if (empty($width)) {
            $width = '600';
        }
        if (empty($height)) {
            $height = '400';
        }
        $videourl = $postdata['vidurl'];

        $videourl = $postdata['vidurl'];
        $autoplay = 'off';
        if (isset($postdata['autoplay'])) {
            $autoplay = $postdata['autoplay'];
        }
        $loop = 'off';
        if (isset($postdata['loop'])) {
            $loop = $postdata['loop'];
        }

        if (strpos($videourl, 'youtube') > 0 || strpos($videourl, 'youtu') > 0) {
            $explodeid = '';
            $explodeid = explode("=", $videourl);
            $foundid = '';
            $muted = '&mute=1';

            if ($autoplay == 'on') {
                $autoplay = '&autoplay=1';
            } else {
                $autoplay = '';
            }

            if ($loop == 'on') {
                $loop = '&loop=1';
            } else {
                $loop = '';
            }

            if (strpos($videourl, 'youtu') > 0) {
                $explodeid = explode("/", $videourl);
                $foundid = $explodeid[3] . '?' . $autoplay . $loop;
                $expdata = $explodeid[3];
            } else {
                $foundid = $explodeid[1] . '?' . $autoplay . $loop;
                $expdata = $explodeid[1];
            }

            $playlist = '&playlist='. $expdata;
            $playlist = str_replace("?feature=shared","",$playlist);

            $html = '';
            $html .= '<div class="'.$className.'" style="text-align:center; max-width:100%; width:' . $width .$width_unit .'; height:' . $height .$height_unit .'; border-radius: ' . $radius . '; margin: 0 auto;">';

            $html .= '
            <iframe src="https://www.youtube.com/embed/' . $expdata . '?rel=0&modestbranding=1' . $loop . '&autohide=1' . $muted . '&showinfo=0&controls=1' . $autoplay . ''.$playlist.'"  width="100%" height="100%" style="border-radius: ' . $radius . ';" frameborder="0" allowfullscreen></iframe>
        ';
            $html .= '</div>';
        } elseif (strpos($videourl, 'vimeo') > 0) {
            $explodeid = '';
            $explodeid = explode("/", $videourl);
            $foundid = '';

            if ($autoplay == 'on') {
                $autoplay = '&autoplay=1&muted=1';
            } else {
                $autoplay = '';
            }

            if ($loop == 'on') {
                $loop = '&loop=1';
            } else {
                $loop = '';
            }

            $foundid = $explodeid[3] . '?' . $autoplay . $loop;
            $html = '';
            $html .= '<div class="'.$className.'" style="text-align:center; max-width:100%; width:' . $width . $width_unit.'; height:' . $height . $height_unit.'; margin: 0 auto;">';
            $html .= '<iframe src="https://player.vimeo.com/video/' . $foundid . '" width="' . $width . '" height="' . $height . '" style="border-radius: ' . $radius . ';" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            $html .= '</div>';
        } else {
            $html = '';
            $html .= '<div id="pano' . $id . '" class="pano-wrap '.$className.'" style="max-width:100%; width:' . $width . $width_unit.'; height: ' . $height .$height_unit.'; border-radius:' . $radius . '; margin: 0 auto;">';
            $html .= '<div style="width:100%; height:100%; border-radius: ' . $radius . ';">' . $postdata['panoviddata'] . '</div>';

            $html .= '
            <style>
                .video-js {
                    border-radius:' . $radius . ';
                }
                .video-js canvas{
                    border-radius:' . $radius . ';
                }
                #pano' . $id . ' .vjs-poster {
                    border-radius: ' . $radius . ';
                }
            </style>
            
            ';

            // $html .= '<script>';
            // $html .= 'videojs(' . $postdata['vidid'] . ', {';
            // $html .= 'plugins: {';
            // $html .= 'pannellum: {}';
            // $html .= '}';
            // $html .= '});';
            // $html .= '</script>';
            $html .= '</div>';

            //video js vr setup //
            $html .= '<script>';
            $html .= '
                (function (window, videojs) {
                    var player = window.player = videojs("' . $postdata['vidid'] . '");
                    player.mediainfo = player.mediainfo || {};
                    player.mediainfo.projection = "equirectangular";
                
                    // AUTO is the default and looks at mediainfo
                    var vr = window.vr = player.vr({ projection: "AUTO", debug: true, forceCardboard: false, antialias: false });
                    }(window, window.videojs));
                
                ';
            $html .= '</script>';
            //video js vr end //
        }
        return $html;
    }

    $control = false;
    if (isset($postdata['showControls'])) {
        $control = $postdata['showControls'];
    }

    if ($control) {
        if (isset($postdata['customcontrol'])) {
            $custom_control = $postdata['customcontrol'];
            if ($custom_control['panupSwitch'] == "on" || $custom_control['panDownSwitch'] == "on" || $custom_control['panLeftSwitch'] == "on" || $custom_control['panRightSwitch'] == "on" || $custom_control['panZoomInSwitch'] == "on" || $custom_control['panZoomOutSwitch'] == "on" || $custom_control['panFullscreenSwitch'] == "on" || $custom_control['gyroscopeSwitch'] == "on" || $custom_control['backToHomeSwitch'] == "on") {
                $control = false;
            }
        }
    }

    $floor_plan_enable = 'off';
    $floor_plan_image = '';
    if (isset($postdata['floor_plan_tour_enabler']) && $postdata['floor_plan_tour_enabler'] == 'on'){
        $floor_plan_enable = $postdata['floor_plan_tour_enabler'];
        if(isset($postdata['floor_plan_attachment_url']) && !empty($postdata['floor_plan_attachment_url'])){
            $floor_plan_image = $postdata['floor_plan_attachment_url'];
        }
    }

    $vrgallery = false;
    if (isset($postdata['vrgallery'])) {
        $vrgallery = $postdata['vrgallery'];
    }

    $vrgallery_title = false;
    if (isset($postdata['vrgallery_title'])) {
        $vrgallery_title = $postdata['vrgallery_title'];
    }

    $vrgallery_display = false;
    if (isset($postdata['vrgallery_display'])) {
        $vrgallery_display = $postdata['vrgallery_display'];
    }
    $vrgallery_icon_size = false;
    if (isset($postdata['vrgallery_icon_size'])) {
        $vrgallery_icon_size = $postdata['vrgallery_icon_size'];
    }

    $gyro = false;
    $gyro_orientation = false;
    if (isset($postdata['gyro'])) {
        $gyro = $postdata['gyro'];
        if (isset($postdata['deviceorientationcontrol'])) {
            $gyro_orientation = $postdata['deviceorientationcontrol'];
        }
    }

    $compass = false;
    $audio_right = "5px";
    if (isset($postdata['compass'])) {
        $compass = $postdata['compass'] == 'on' || $postdata['compass'] != null ? true : false;
        if ($compass) {
            $audio_right = "60px";
        }
    }
    $floor_map_right = "10px";
    if((isset($postdata['compass']) && $postdata['compass'] == 'on') && (isset($postdata['bg_music']) && $postdata['bg_music'] == 'on')){
        $floor_map_right = "85px";
    }elseif(isset($postdata['compass']) && $postdata['compass'] == 'on'){
        $floor_map_right = "55px";
    }elseif (isset($postdata['bg_music']) && $postdata['bg_music'] == "on") {
        $floor_map_right = "25px";
    }

    //===explainer  handle===//

    //===explainer  handle===//
    $explainer_right = "10px";
    if ((isset($postdata['compass']) && $postdata['compass'] == 'on') && (isset($postdata['bg_music']) && $postdata['bg_music'] == 'on') && ( $floor_plan_enable == 'on' && !empty($floor_plan_image) ) ) {
        $explainer_right = "130px";
    } elseif (isset($postdata['compass']) && $postdata['compass'] == 'on' && ($floor_plan_enable == 'on' && !empty($floor_plan_image) )) {
        $explainer_right = "100px";
    } elseif (isset($postdata['bg_music']) && $postdata['bg_music'] == "on" && ($floor_plan_enable == 'on' && !empty($floor_plan_image) )) {
        $explainer_right = "60px";
    } elseif((isset($postdata['compass']) && $postdata['compass'] == 'on') && (isset($postdata['bg_music']) && $postdata['bg_music'] == 'on') ) {
        $explainer_right = "80px";
    }elseif (isset($postdata['compass']) && $postdata['compass'] == 'on') {
        $explainer_right = "55px";
    } elseif (isset($postdata['bg_music']) && $postdata['bg_music'] == "on") {
        $explainer_right = "30px";
    } elseif ($floor_plan_enable == 'on' && !empty($floor_plan_image) ) {
        $explainer_right = "40px";
    }
    $enable_cardboard = '';
    $is_cardboard = get_option('wpvr_cardboard_disable');
    if(wpvr_isMobileDevice() && $is_cardboard == 'true' ){
        $enable_cardboard = 'enable-cardboard';
        $audio_right = "73px";
        if (isset($postdata['compass'])) {
            $compass = $postdata['compass'] == 'on' || $postdata['compass'] != null ? true : false;
            if ($compass) {
                $audio_right = "130px";
            }
        }

        $floor_map_right = "60px";
        if((isset($postdata['compass']) && $postdata['compass'] == 'on') && (isset($postdata['bg_music']) && $postdata['bg_music'] == 'on')){
            $floor_map_right = "150px";
        }elseif(isset($postdata['compass']) && $postdata['compass'] == 'on'){
            $floor_map_right = "120px";
        }elseif (isset($postdata['bg_music']) && $postdata['bg_music'] == "on") {
            $floor_map_right = "90px";
        }

        //===explainer  handle===//

        $explainer_right = "65px";

        if ((isset($postdata['compass']) && $postdata['compass'] == true) && (isset($postdata['bg_music']) && $postdata['bg_music'] == 'on')) {
            $explainer_right = "150px";
        } elseif((isset($postdata['compass']) && $postdata['compass'] == true) && (isset($postdata['bg_music']) && $postdata['bg_music'] == 'on') && ($floor_plan_enable == 'on' && !empty($floor_plan_image) )) {
            $explainer_right = "180px";
        } elseif (isset($postdata['compass']) && $postdata['compass'] == true && ($floor_plan_enable == 'on' && !empty($floor_plan_image) )) {
            $explainer_right = "150px";
        } elseif (isset($postdata['bg_music']) && $postdata['bg_music'] == "on" && ($floor_plan_enable == 'on' && !empty($floor_plan_image) )) {
            $explainer_right = "120px";
        }elseif (isset($postdata['compass']) && $postdata['compass'] == true) {
            $explainer_right = "130px";
        } elseif (isset($postdata['bg_music']) && $postdata['bg_music'] == "on") {
            $explainer_right = "90px";
        }elseif ($floor_plan_enable == 'on' && !empty($floor_plan_image) ) {
            $explainer_right = "90px";
        }
    }

    //===explainer  handle===//

    $mouseZoom = true;
    if (isset($postdata['mouseZoom'])) {
        if($postdata['mouseZoom'] == "off") {
            $mouseZoom = false;
        }
        else {
            $mouseZoom = true;
        }
    }

    $draggable = true;
    if (isset($postdata['draggable'])) {
        $draggable = $postdata['draggable'] == 'on' || $postdata['draggable'] != null ? true : false;
    }

    $diskeyboard = false;
    if (isset($postdata['diskeyboard'])) {
        $diskeyboard = $postdata['diskeyboard'] == 'off' || $postdata['diskeyboard'] == null ? false : true;
    }

    $keyboardzoom = true;
    if (isset($postdata['keyboardzoom'])) {
        $keyboardzoom = $postdata['keyboardzoom'];
    }

    $autoload = false;

    if (isset($postdata['autoLoad'])) {
        $autoload = $postdata['autoLoad'];
    }

    $default_scene = '';
    if (isset($postdata['defaultscene'])) {
        $default_scene = $postdata['defaultscene'];
    }

    $preview = '';
    if (isset($postdata['preview'])) {
        $preview = $postdata['preview'];
    }

    $autorotation = '';
    if (isset($postdata["autoRotate"])) {
        $autorotation = $postdata["autoRotate"];
    }
    $autorotationinactivedelay = '';
    if (isset($postdata["autoRotateInactivityDelay"])) {
        $autorotationinactivedelay = $postdata["autoRotateInactivityDelay"];
    }
    $autorotationstopdelay = '';
    if (isset($postdata["autoRotateStopDelay"])) {
        $autorotationstopdelay = $postdata["autoRotateStopDelay"];
    }

    $scene_fade_duration = '';
    if (isset($postdata['scenefadeduration'])) {
        $scene_fade_duration = $postdata['scenefadeduration'];
    }

    $panodata = array();
    if (isset($postdata['panodata'])) {
        $panodata = $postdata['panodata'];
    }

    $default_zoom_global = 100;
    if (isset($postdata['hfov']) && $postdata['hfov'] != '') {
        $default_zoom_global = $postdata['hfov'];
    }

    $min_zoom_global = 50;
    if (isset($postdata['minHfov']) && $postdata['minHfov'] != '') {
        $min_zoom_global = $postdata['minHfov'];
    }

    $max_zoom_global = 120;
    if (isset($postdata['maxHfov']) && $postdata['maxHfov'] != '') {
        $max_zoom_global = $postdata['maxHfov'];
    }

    $hotspoticoncolor = '#00b4ff';
    $hotspotblink = 'on';
    $default_data = array();
    $default_data = array('firstScene' => $default_scene, 'sceneFadeDuration' => $scene_fade_duration, 'hfov' => $default_zoom_global, 'maxHfov' => $max_zoom_global, 'minHfov' => $min_zoom_global);
    $scene_data = array();
    if (is_array($panodata) && isset($panodata['scene-list'])) {
        if (!empty($panodata['scene-list'])) {
            foreach ($panodata['scene-list'] as $panoscenes) {
                $scene_ititle = '';
                if (isset($panoscenes["scene-ititle"])) {
                    $scene_ititle = sanitize_text_field($panoscenes["scene-ititle"]);
                }

                $scene_author = '';
                if (isset($panoscenes["scene-author"])) {
                    $scene_author = sanitize_text_field($panoscenes["scene-author"]);
                }

                $scene_author_url = '';
                if (isset($panoscenes["scene-author-url"])) {
                    $scene_author_url = sanitize_text_field($panoscenes["scene-author-url"]);
                }

                $scene_vaov = 180;
                if (isset($panoscenes["scene-vaov"])) {
                    $scene_vaov = (float)$panoscenes["scene-vaov"];
                }

                $scene_haov = 360;
                if (isset($panoscenes["scene-haov"])) {
                    $scene_haov = (float)$panoscenes["scene-haov"];
                }

                $scene_vertical_offset = 0;
                if (isset($panoscenes["scene-vertical-offset"])) {
                    $scene_vertical_offset = (float)$panoscenes["scene-vertical-offset"];
                }

                $default_scene_pitch = null;
                if (isset($panoscenes["scene-pitch"])) {
                    $default_scene_pitch = (float)$panoscenes["scene-pitch"];
                }

                $default_scene_yaw = null;
                if (isset($panoscenes["scene-yaw"])) {
                    $default_scene_yaw = (float)$panoscenes["scene-yaw"];
                }

                $scene_max_pitch = '';
                if (isset($panoscenes["scene-maxpitch"])) {
                    $scene_max_pitch = (float)$panoscenes["scene-maxpitch"];
                }


                $scene_min_pitch = '';
                if (isset($panoscenes["scene-minpitch"])) {
                    $scene_min_pitch = (float)$panoscenes["scene-minpitch"];
                }


                $scene_max_yaw = '';
                if (isset($panoscenes["scene-maxyaw"])) {
                    $scene_max_yaw = (float)$panoscenes["scene-maxyaw"];
                }


                $scene_min_yaw = '';
                if (isset($panoscenes["scene-minyaw"])) {
                    $scene_min_yaw = (float)$panoscenes["scene-minyaw"];
                }

                $default_zoom = 100;
                if (isset($panoscenes["scene-zoom"]) && $panoscenes["scene-zoom"] != '') {
                    $default_zoom = (int)$panoscenes["scene-zoom"];
                } else {
                    if ($default_zoom_global != '') {
                        $default_zoom =  (int)$default_zoom_global;
                    }
                }


                $max_zoom = 120;
                if (isset($panoscenes["scene-maxzoom"]) && $panoscenes["scene-maxzoom"] != '') {
                    $max_zoom = (int)$panoscenes["scene-maxzoom"];
                } else {
                    if ($max_zoom_global != '') {
                        $max_zoom =  (int)$max_zoom_global;
                    }
                }

                $min_zoom = 120;
                if (isset($panoscenes["scene-minzoom"]) && $panoscenes["scene-minzoom"] != '') {
                    $min_zoom = (int)$panoscenes["scene-minzoom"];
                } else {
                    if ($min_zoom_global != '') {
                        $min_zoom =  (int)$min_zoom_global;
                    }
                }

                $hotspot_datas = array();
                if (isset($panoscenes['hotspot-list'])) {
                    $hotspot_datas = $panoscenes['hotspot-list'];
                }

                $hotspots = array();

                foreach ($hotspot_datas as $hotspot_data) {
                    $status  = get_option('wpvr_edd_license_status');
                    if ($status !== false && $status == 'valid') {

                        if (isset($hotspot_data["hotspot-customclass-pro"]) && $hotspot_data["hotspot-customclass-pro"] != 'none') {
                            $hotspot_data['hotspot-customclass'] = $hotspot_data["hotspot-customclass-pro"] . ' custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot_data['hotspot-title'];

                        }
                        if (isset($hotspot_data['hotspot-blink'])) {
                            $hotspotblink = $hotspot_data['hotspot-blink'];
                        }
                    }
                    $hotspot_scene_pitch = '';
                    if (isset($hotspot_data["hotspot-scene-pitch"])) {
                        $hotspot_scene_pitch = $hotspot_data["hotspot-scene-pitch"];
                    }
                    $hotspot_scene_yaw = '';
                    if (isset($hotspot_data["hotspot-scene-yaw"])) {
                        $hotspot_scene_yaw = $hotspot_data["hotspot-scene-yaw"];
                    }

                    $hotspot_type = $hotspot_data["hotspot-type"] !== 'scene' ? 'info' : $hotspot_data["hotspot-type"];
                    $hotspot_content = '';

                    ob_start();
                    do_action('wpvr_hotspot_content', $hotspot_data);
                    $hotspot_content = ob_get_clean();

                    if (!$hotspot_content) {
                        $hotspot_content = $hotspot_data["hotspot-content"];
                    }

                    if (isset($hotspot_data["wpvr_url_open"][0])) {
                        $wpvr_url_open = $hotspot_data["wpvr_url_open"][0];
                    } else {
                        $wpvr_url_open = "off";
                    }
                    $on_hover_content = preg_replace_callback('/<img[^>]*>/', "replace_callback", $hotspot_data['hotspot-hover']);
                    $on_click_content = preg_replace_callback('/<img[^>]*>/', "replace_callback", $hotspot_content);
                    $hotspot_info = array(
                        'text' => $hotspot_data['hotspot-title'],
                        'pitch' => $hotspot_data['hotspot-pitch'],
                        'yaw' => $hotspot_data['hotspot-yaw'],
                        'type' => $hotspot_type,
                        'cssClass' => $hotspot_data['hotspot-customclass'],
                        'URL' => $hotspot_data['hotspot-url'],
                        "wpvr_url_open" => $wpvr_url_open,
                        "clickHandlerArgs" => $on_click_content,
//                    "clickHandlerArgs" => $hotspot_content,
//                    'createTooltipArgs' =>  $hotspot_data['hotspot-hover'],
                        'createTooltipArgs' => $on_hover_content,
                        "sceneId" => $hotspot_data["hotspot-scene"],
                        "targetPitch" => (float)$hotspot_scene_pitch,
                        "targetYaw" => (float)$hotspot_scene_yaw,
                        'hotspot_type' => $hotspot_data['hotspot-type']
                    );

                    $hotspot_info['URL'] = ($hotspot_data['hotspot-type'] === 'fluent_form' || $hotspot_data['hotspot-type'] === 'wc_product') ? '' : $hotspot_info['URL'];

                    if ($hotspot_data["hotspot-customclass"] == 'none' || $hotspot_data["hotspot-customclass"] == '') {
                        unset($hotspot_info["cssClass"]);
                    }
                    if (empty($hotspot_data["hotspot-scene"])) {
                        unset($hotspot_info['targetPitch']);
                        unset($hotspot_info['targetYaw']);
                    }
                    array_push($hotspots, $hotspot_info);
                }

                $device_scene = $panoscenes['scene-attachment-url'];
                $mobile_media_resize = get_option('mobile_media_resize');
                $file_accessible = ini_get('allow_url_fopen');
                if ($mobile_media_resize == "true") {
                    if ($file_accessible == "1") {
                        $image_info = getimagesize($device_scene);
                        if ($image_info && $image_info[0] > 4096) {
                            $src_to_id_for_mobile = '';
                            $src_to_id_for_desktop = '';
                            if (wpvr_isMobileDevice()) {
                                $src_to_id_for_mobile = attachment_url_to_postid($panoscenes['scene-attachment-url']);
                                if ($src_to_id_for_mobile) {
                                    $mobile_scene = wp_get_attachment_image_src($src_to_id_for_mobile, 'wpvr_mobile');
                                    if ($mobile_scene[3]) {
                                        $device_scene = $mobile_scene[0];
                                    }
                                }
                            } else {
                                $src_to_id_for_desktop = attachment_url_to_postid($panoscenes['scene-attachment-url']);
                                if ($src_to_id_for_desktop) {
                                    $desktop_scene = wp_get_attachment_image_src($src_to_id_for_mobile, 'full');
                                    if ($desktop_scene && $desktop_scene[0]) {
                                        $device_scene = $desktop_scene[0];
                                    }
                                }
                            }
                        }
                    }
                }

                $scene_info = array();

                if ($panoscenes["scene-type"] == 'cubemap') {
                    $pano_type = 'cubemap';
                    $pano_attachment = array(
                        $panoscenes["scene-attachment-url-face0"],
                        $panoscenes["scene-attachment-url-face1"],
                        $panoscenes["scene-attachment-url-face2"],
                        $panoscenes["scene-attachment-url-face3"],
                        $panoscenes["scene-attachment-url-face4"],
                        $panoscenes["scene-attachment-url-face5"]
                    );

                    $scene_info = array('type' => $panoscenes['scene-type'], 'cubeMap' => $pano_attachment, "pitch" => $default_scene_pitch, "maxPitch" => $scene_max_pitch, "minPitch" => $scene_min_pitch, "maxYaw" => $scene_max_yaw, "minYaw" => $scene_min_yaw, "yaw" => $default_scene_yaw, "hfov" => $default_zoom, "maxHfov" => $max_zoom, "minHfov" => $min_zoom, "title" => $scene_ititle, "author" => $scene_author, "authorURL" => $scene_author_url, "vaov" => $scene_vaov, "haov" => $scene_haov, "vOffset" => $scene_vertical_offset, 'hotSpots' => $hotspots);
                } else {
                    $scene_info = array('type' => $panoscenes['scene-type'], 'panorama' => $device_scene, "pitch" => $default_scene_pitch, "maxPitch" => $scene_max_pitch, "minPitch" => $scene_min_pitch, "maxYaw" => $scene_max_yaw, "minYaw" => $scene_min_yaw, "yaw" => $default_scene_yaw, "hfov" => $default_zoom, "maxHfov" => $max_zoom, "minHfov" => $min_zoom, "title" => $scene_ititle, "author" => $scene_author, "authorURL" => $scene_author_url, "vaov" => $scene_vaov, "haov" => $scene_haov, "vOffset" => $scene_vertical_offset, 'hotSpots' => $hotspots);
                }



                if (isset($panoscenes["ptyscene"])) {
                    if ($panoscenes["ptyscene"] == "off") {
                        unset($scene_info['pitch']);
                        unset($scene_info['yaw']);
                    }
                }

                if (empty($panoscenes["scene-ititle"])) {
                    unset($scene_info['title']);
                }
                if (empty($panoscenes["scene-author"])) {
                    unset($scene_info['author']);
                }
                if (empty($panoscenes["scene-author-url"])) {
                    unset($scene_info['authorURL']);
                }

                if (empty($scene_vaov)) {
                    unset($scene_info['vaov']);
                }

                if (empty($scene_haov)) {
                    unset($scene_info['haov']);
                }

                if (empty($scene_vertical_offset)) {
                    unset($scene_info['vOffset']);
                }

                if (isset($panoscenes["cvgscene"])) {
                    if ($panoscenes["cvgscene"] == "off") {
                        unset($scene_info['maxPitch']);
                        unset($scene_info['minPitch']);
                    }
                }
                if (empty($panoscenes["scene-maxpitch"])) {
                    unset($scene_info['maxPitch']);
                }

                if (empty($panoscenes["scene-minpitch"])) {
                    unset($scene_info['minPitch']);
                }

                if (isset($panoscenes["chgscene"])) {
                    if ($panoscenes["chgscene"] == "off") {
                        unset($scene_info['maxYaw']);
                        unset($scene_info['minYaw']);
                    }
                }
                if (empty($panoscenes["scene-maxyaw"])) {
                    unset($scene_info['maxYaw']);
                }

                if (empty($panoscenes["scene-minyaw"])) {
                    unset($scene_info['minYaw']);
                }

                // if (isset($panoscenes["czscene"])) {
                //     if ($panoscenes["czscene"] == "off") {
                //         unset($scene_info['hfov']);
                //         unset($scene_info['maxHfov']);
                //         unset($scene_info['minHfov']);
                //     }
                // }

                $scene_array = array();
                $scene_array = array(
                    $panoscenes['scene-id'] => $scene_info
                );

                $scene_data[$panoscenes['scene-id']] = $scene_info;
            }
        }
    }
    $pano_id_array = array();
    $pano_id_array = array('panoid' => $panoid);
    $pano_response = array();
    $pano_response = array('autoLoad' => $autoload, 'showControls' => $control, 'compass' => $compass, 'orientationOnByDefault' => $gyro_orientation, 'mouseZoom' => $mouseZoom, 'draggable' => $draggable, 'disableKeyboardCtrl' => $diskeyboard, 'keyboardZoom' => $keyboardzoom, "preview" => $preview, "autoRotate" => $autorotation, "autoRotateInactivityDelay" => $autorotationinactivedelay, "autoRotateStopDelay" => $autorotationstopdelay, 'default' => $default_data, 'scenes' => $scene_data);
    if (empty($autorotation)) {
        unset($pano_response['autoRotate']);
        unset($pano_response['autoRotateInactivityDelay']);
        unset($pano_response['autoRotateStopDelay']);
    }
    if (empty($autorotationinactivedelay)) {
        unset($pano_response['autoRotateInactivityDelay']);
    }
    if (empty($autorotationstopdelay)) {
        unset($pano_response['autoRotateStopDelay']);
    }

    $response = array();
    $response = array($pano_id_array, $pano_response);
    if (!empty($response)) {
        $response = json_encode($response);
    }
    if (empty($width)) {
        $width = '600';
    }
    if (empty($height)) {
        $height = '400';
    }
    if( 'fullwidth' == $width){
        $width = "100%";
    }

    $foreground_color = '#fff';
    $pulse_color = wpvr_hex2rgb($hotspoticoncolor);
    $rgb = wpvr_HTMLToRGB($hotspoticoncolor);
    $hsl = wpvr_RGBToHSL($rgb);
    if ($hsl->lightness > 200) {
        $foreground_color = '#000000';
    } else {
        $foreground_color = '#fff';
    }

    $class = 'myclass';
    $html = 'test';
    $html = '';
    $html .= '<style>';
    if ($width == 'embed') {
        $html .= 'body{
             overflow: hidden;
        }';
    }
    $status  = get_option('wpvr_edd_license_status');
    $status  = get_option('wpvr_edd_license_status');
    if ($status !== false && $status == 'valid') {
        if(isset($postdata['customcss_enable']) && $postdata['customcss_enable'] == 'on'){
            $html .= isset($postdata['customcss']) ? $postdata['customcss'] : '';
        }
    }
    if ($status !== false && $status == 'valid') {
        if (is_array($panodata) && isset($panodata['scene-list'])) {
            foreach ($panodata['scene-list'] as $panoscenes) {

                foreach ($panoscenes['hotspot-list'] as $hotspot) {
                    if (isset($hotspot['hotspot-customclass-color-icon-value']) && !empty($hotspot['hotspot-customclass-color-icon-value'])) {
                        $hotspoticoncolor = $hotspot['hotspot-customclass-color-icon-value'];
                    } else {
                        $hotspoticoncolor = "#00b4ff";
                    }
                    $pulse_color = wpvr_hex2rgb($hotspoticoncolor);
                    if (isset($hotspot["hotspot-customclass-pro"]) && $hotspot["hotspot-customclass-pro"] != 'none') {
                        $html .= '#' . $panoid . ' div.pnlm-hotspot-base.fas.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
              #' . $panoid . ' div.pnlm-hotspot-base.fab.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
              #' . $panoid . ' div.pnlm-hotspot-base.fa.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
              #' . $panoid . ' div.pnlm-hotspot-base.fa-solid.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
              #' . $panoid . ' div.pnlm-hotspot-base.far.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ' {
                  display: block !important;
                  background-color: ' . $hotspoticoncolor . ';
                  color: ' . $foreground_color . ';
                  border-radius: 100%;
                  width: 30px;
                  height: 30px;
                  font-size: 16px;
                  line-height: 30px;
                  animation: icon-pulse' . $panoid . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ' 1.5s infinite cubic-bezier(.25, 0, 0, 1);
              }';
                        $html .= '#' . $panoid2 . ' div.pnlm-hotspot-base.fas.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
                  #' . $panoid2 . ' div.pnlm-hotspot-base.fab.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
                  #' . $panoid2 . ' div.pnlm-hotspot-base.fa-solid.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
                  #' . $panoid2 . ' div.pnlm-hotspot-base.fa.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ',
                  #' . $panoid2 . ' div.pnlm-hotspot-base.far.custom-' . $id . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ' {
                  display: block !important;
                  background-color: ' . $hotspoticoncolor . ';
                  color: ' . $foreground_color . ';
                  border-radius: 100%;
                  width: 30px;
                  height: 30px;
                  font-size: 16px;
                  line-height: 30px;
                  animation: icon-pulse' . $panoid2 . ' 1.5s infinite cubic-bezier(.25, 0, 0, 1);
          }';
                    }
                    if (isset($hotspot['hotspot-blink'])) {
                        $hotspotblink = $hotspot['hotspot-blink'];
                        if ($hotspotblink == 'on') {
                            $html .= '@-webkit-keyframes icon-pulse' . $panoid . '-' . $panoscenes['scene-id'] . '-' . $hotspot['hotspot-title'] . ' {
                0% {
                    box-shadow: 0 0 0 0px rgba(' . $pulse_color[0] . ', 1);
                }
                100% {
                    box-shadow: 0 0 0 10px rgba(' . $pulse_color[0] . ', 0);
                }
            }
            @keyframes icon-pulse' . $panoid . ' {
                0% {
                    box-shadow: 0 0 0 0px rgba(' . $pulse_color[0] . ', 1);
                }
                100% {
                    box-shadow: 0 0 0 10px rgba(' . $pulse_color[0] . ', 0);
                }
            }';
                        }
                    }

                }

            }
        }
    }

    $status  = get_option('wpvr_edd_license_status');
    if ($status !== false && $status == 'valid') {
        if (!$gyro) {
            $html .= '#' . $panoid . ' div.pnlm-orientation-button {
                    display: none;
                }';
        }
    } else {
        $html .= '#' . $panoid . ' div.pnlm-orientation-button {
                    display: none;
                }';
    }
    $floor_plan_custom_color = isset($postdata['floor_plan_custom_color']) ? $postdata['floor_plan_custom_color'] : '#cca92c';
    $foreground_color_pointer = '#fff';
    if($floor_plan_custom_color != ''){
        $pointer_pulse = wpvr_hex2rgb($floor_plan_custom_color);
        $floor_rgb = wpvr_HTMLToRGB($floor_plan_custom_color);
        $floor_hsl = wpvr_RGBToHSL($floor_rgb);
        if ($floor_hsl->lightness > 200) {
            $foreground_color_pointer = '#000000';
        }
        $html .= '
            .wpvr-floor-map .floor-plan-pointer.add-pulse:before {
                border: 17px solid '.$floor_plan_custom_color.';
            }
            @-webkit-keyframes pulse {
                0% {
                    -webkit-box-shadow: 0 0 0 0 rgba('.$pointer_pulse[0].', 0.7);
                }
                70% {
                    -webkit-box-shadow: 0 0 0 10px rgba('.$pointer_pulse[0].', 0);
                }
                100% {
                    -webkit-box-shadow: 0 0 0 0 rgba('.$pointer_pulse[0].', 0);
                }
            }
            @keyframes pulse {
                0% {
                    -moz-box-shadow: 0 0 0 0 rgba('.$pointer_pulse[0].', 0.7);
                    box-shadow: 0 0 0 0 rgba('.$pointer_pulse[0].', 0.7);
                }
                70% {
                    -moz-box-shadow: 0 0 0 10px rgba('.$pointer_pulse[0].', 0);
                    box-shadow: 0 0 0 10px rgba('.$pointer_pulse[0].', 0);
                }
                100% {
                    -moz-box-shadow: 0 0 0 0 rgba('.$pointer_pulse[0].', 0);
                    box-shadow: 0 0 0 0 rgba('.$pointer_pulse[0].', 0);
                }
            }';
    }
    $html .= '</style>';



    if (wpvr_isMobileDevice()) {
        $html .= '<div id="master-container" class="wpvr-cardboard '.$className.' '.$enable_cardboard.' " style="max-width:' . $width . $width_unit.'; width: 100%; height: ' . $mobile_height .$mobile_height_unit .'; border-radius:' . $radius . '; direction:ltr; border : '.$border_style.' ">';
    } else {
        $html .= '<div id="master-container" class="wpvr-cardboard '.$className.' '.$enable_cardboard.'" style="max-width:' . $width .$width_unit .'; width: 100%; height: ' . $height .$height_unit .'; border-radius:' . $radius . '; direction:ltr; border : '.$border_style.'">';
    }
    $status  = get_option('wpvr_edd_license_status');
    $is_cardboard = get_option('wpvr_cardboard_disable');
    if ($status !== false &&  'valid' == $status  && $is_pro &&  wpvr_isMobileDevice() && $is_cardboard == 'true' ) {
        $html .= '<button class="fullscreen-button">';
        $html .= '<span class="expand">';
        $html .= '<i class="fa fa-expand" aria-hidden="true"></i>';
        $html .= '</span>';

        $html .= '<span class="compress">';
        $html .= '<i class="fa fa-compress" aria-hidden="true"></i>';
        $html .= '</span>';
        $html .= '</button>';
        $html .= '<label class="wpvr-cardboard-switcher">
                <input type="checkbox" class="vr_mode_change' . $id . '" name="vr_mode_change" value="off">
                <span class="switcher-box">
                    <span class="normal-mode-tooltip">Normal VR Mode</span>
                    <svg width="78" height="60" viewBox="0 0 78 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M42.25 21.4286C42.25 22.2811 41.9076 23.0986 41.2981 23.7014C40.6886 24.3042 39.862 24.6429 39 24.6429C38.138 24.6429 37.3114 24.3042 36.7019 23.7014C36.0924 23.0986 35.75 22.2811 35.75 21.4286C35.75 20.5761 36.0924 19.7585 36.7019 19.1557C37.3114 18.5529 38.138 18.2143 39 18.2143C39.862 18.2143 40.6886 18.5529 41.2981 19.1557C41.9076 19.7585 42.25 20.5761 42.25 21.4286ZM19.5 30C18.9254 30 18.3743 30.2258 17.9679 30.6276C17.5616 31.0295 17.3333 31.5745 17.3333 32.1429C17.3333 32.7112 17.5616 33.2562 17.9679 33.6581C18.3743 34.06 18.9254 34.2857 19.5 34.2857H28.1667C28.7413 34.2857 29.2924 34.06 29.6987 33.6581C30.1051 33.2562 30.3333 32.7112 30.3333 32.1429C30.3333 31.5745 30.1051 31.0295 29.6987 30.6276C29.2924 30.2258 28.7413 30 28.1667 30H19.5ZM47.6667 32.1429C47.6667 31.5745 47.8949 31.0295 48.3013 30.6276C48.7076 30.2258 49.2587 30 49.8333 30H58.5C59.0746 30 59.6257 30.2258 60.0321 30.6276C60.4384 31.0295 60.6667 31.5745 60.6667 32.1429C60.6667 32.7112 60.4384 33.2562 60.0321 33.6581C59.6257 34.06 59.0746 34.2857 58.5 34.2857H49.8333C49.2587 34.2857 48.7076 34.06 48.3013 33.6581C47.8949 33.2562 47.6667 32.7112 47.6667 32.1429ZM32.5 0C31.9254 0 31.3743 0.225765 30.9679 0.627629C30.5616 1.02949 30.3333 1.57454 30.3333 2.14286V8.57143H18.4167C14.8693 8.57183 11.4528 9.89617 8.84994 12.2798C6.24706 14.6634 4.64954 17.9306 4.37667 21.4286H2.16667C1.59203 21.4286 1.04093 21.6543 0.634602 22.0562C0.228273 22.4581 0 23.0031 0 23.5714V36.4286C0 36.9969 0.228273 37.5419 0.634602 37.9438C1.04093 38.3457 1.59203 38.5714 2.16667 38.5714H4.33333V46.0714C4.33333 49.7655 5.81711 53.3083 8.45825 55.9204C11.0994 58.5325 14.6815 60 18.4167 60H25.3933C29.1269 59.9986 32.7071 58.5311 35.347 55.92L37.921 53.3786C38.0618 53.2393 38.229 53.1288 38.4131 53.0534C38.5971 52.978 38.7943 52.9392 38.9935 52.9392C39.1927 52.9392 39.3899 52.978 39.5739 53.0534C39.758 53.1288 39.9252 53.2393 40.066 53.3786L42.6357 55.92C45.2766 58.5322 48.8586 59.9998 52.5937 60H59.5833C63.3185 60 66.9006 58.5325 69.5418 55.9204C72.1829 53.3083 73.6667 49.7655 73.6667 46.0714V38.5714H75.8333C76.408 38.5714 76.9591 38.3457 77.3654 37.9438C77.7717 37.5419 78 36.9969 78 36.4286V23.5714C78 23.0031 77.7717 22.4581 77.3654 22.0562C76.9591 21.6543 76.408 21.4286 75.8333 21.4286H73.6233C73.3505 17.9306 71.753 14.6634 69.1501 12.2798C66.5472 9.89617 63.1307 8.57183 59.5833 8.57143H47.6667V2.14286C47.6667 1.57454 47.4384 1.02949 47.0321 0.627629C46.6257 0.225765 46.0746 0 45.5 0H32.5ZM69.3333 22.5V46.0714C69.3333 48.6289 68.3061 51.0816 66.4776 52.89C64.6491 54.6983 62.1692 55.7143 59.5833 55.7143H52.5937C50.0093 55.7132 47.5311 54.6973 45.7037 52.89L43.1297 50.3486C42.5864 49.8108 41.9413 49.3842 41.2312 49.0931C40.5211 48.8021 39.76 48.6522 38.9913 48.6522C38.2227 48.6522 37.4616 48.8021 36.7515 49.0931C36.0414 49.3842 35.3963 49.8108 34.853 50.3486L32.2833 52.89C30.4559 54.6973 27.9777 55.7132 25.3933 55.7143H18.4167C15.8308 55.7143 13.3509 54.6983 11.5224 52.89C9.6939 51.0816 8.66667 48.6289 8.66667 46.0714V22.5C8.66667 19.9426 9.6939 17.4899 11.5224 15.6815C13.3509 13.8731 15.8308 12.8571 18.4167 12.8571H59.5833C62.1692 12.8571 64.6491 13.8731 66.4776 15.6815C68.3061 17.4899 69.3333 19.9426 69.3333 22.5Z" fill="#216DF0"/>
                    </svg>
                </span>
            </label>';
    }

    if ($width == 'fullwidth') {
        if (wpvr_isMobileDevice()) {
            $html .= '<div class="cardboard-vrfullwidth vrfullwidth">';
            $html .= '<div id="pano2' . $id . '" class="pano-wrap  pano-left cardboard-half" style="width: 49%!important; border-radius:' . $radius . ' ;text-align:center; direction:ltr;" ><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';
            $html .= '<div id="pano' . $id . '" class="pano-wrap  pano-right" style="width: 100%; text-align:center; direction:ltr; border-radius:' . $radius . '" >';
        } else {
            $html .= '<div id="pano2' . $id . '" class="pano-wrap pano-left" style="width: 49%; border-radius:' . $radius . ';"><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';
            $html .= '<div id="pano' . $id . '" class="pano-wrap vrfullwidth" style=" text-align:center; height: ' . $height .$height_unit .'; border-radius:' . $radius . '; direction:ltr;" >';
        }
    } else {
        if (wpvr_isMobileDevice()) {
            $html .= '<div id="pano2' . $id . '" class="pano-wrap pano-left cardboard-half" style="width: 49%; border-radius:' . $radius . ';">
                        <div id="center-pointer2' . $id . '" class="vr-pointer-container">
                            <span class="center-pointer"></span>
                        </div>
                       </div>';

            $html .= '<div id="pano' . $id . '" class="pano-wrap pano-right" style=" width: 100%; border-radius:' . $radius . ';">';
        } else {

            $html .= '<div id="pano2' . $id . '" class="pano-wrap pano-left" style="width: 49%; border-radius:' . $radius . ';"><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';

            $html .= '<div id="pano' . $id . '" class="pano-wrap pano-right" style="width: 100%; border-radius:' . $radius . ';">';

        }
    }
    // Vr mode transction scene to scene
    if ($status !== false &&  'valid' == $status  && $is_pro &&  wpvr_isMobileDevice() && $is_cardboard == 'true') {
        $html .= '<div id="center-pointer' . $id . '" class="vr-pointer-container" style="display:none"><span class="center-pointer"></span></div>';
    }


    //===company logo===//
    if (isset($postdata['cpLogoSwitch'])) {
        $cpLogoImg = $postdata['cpLogoImg'];
        $cpLogoContent = $postdata['cpLogoContent'];
        if ($postdata['cpLogoSwitch'] == 'on') {
            $html .= '<div id="cp-logo-controls">';
            $html .= '<div class="cp-logo-ctrl" id="cp-logo">';
            if ($cpLogoImg) {
                $html .= '<img loading="lazy" src="' . $cpLogoImg . '" alt="Company Logo">';
            }

            if ($cpLogoContent) {
                $html .= '<div class="cp-info">' . $cpLogoContent . '</div>';
            }
            $html .= '</div>';
            $html .= '</div>';
        }
    }
    //===company logo ends===//

    //===Background Tour===//
    if (isset($postdata['bg_tour_enabler'])) {

        $bg_tour_enabler = $postdata['bg_tour_enabler'];
        if ($bg_tour_enabler == 'on') {
            $bg_tour_navmenu = $postdata['bg_tour_navmenu'];
            $bg_tour_title = $postdata['bg_tour_title'];
            $bg_tour_subtitle = $postdata['bg_tour_subtitle'];

            if ($bg_tour_navmenu == 'on') {
                $menuLocations = get_nav_menu_locations();
                $menuID = $menuLocations['primary'];
                $primaryNav = wp_get_nav_menu_items($menuID);

                if ($primaryNav) {
                    $html .= '<div class="wpvr-navbar-container">';
                    foreach ($primaryNav as $primaryNav_key => $primaryNav_value) {
                        if ($primaryNav_value->menu_item_parent == "0") {
                            $html .= '<li>';
                            $html .= '<a href="' . $primaryNav_value->url . '">' . $primaryNav_value->title . '</a>';
                            $html .= '<ul class="wpvr-navbar-dropdown">';
                            foreach ($primaryNav as $pm_key => $pm_value) {
                                if ($pm_value->menu_item_parent == $primaryNav_value->ID) {
                                    $html .= '<li>';
                                    $html .= '<a href="' . $pm_value->url . '">' . $pm_value->title . '</a>';
                                    $html .= '</li>';
                                }
                            }
                            $html .= '</ul>';
                            $html .= '</li>';
                        }
                    }
                    $html .= '</div>';
                }
            }

            $html .= '<div class="wpvr-home-content">';
            $html .= '<div class="wpvr-home-title">' . $bg_tour_title . '</div>';
            $html .= '<div class="wpvr-home-subtitle">' . $bg_tour_subtitle . '</div>';
            $html .= '</div>';
        }
    }
    //===Background Tour End===//

    //===Custom Control===//
    if (isset($custom_control)) {
        if ($custom_control['panZoomInSwitch'] == "on" || $custom_control['panZoomOutSwitch'] == "on" || $custom_control['gyroscopeSwitch'] == "on" || $custom_control['backToHomeSwitch'] == "on") {
            $html .= '<div id="zoom-in-out-controls' . $id . '" class="zoom-in-out-controls">';

            if ($custom_control['backToHomeSwitch'] == "on") {
                $html .= '<div class="ctrl" id="backToHome' . $id . '"><i class="' . $custom_control['backToHomeIcon'] . '" style="color:' . $custom_control['backToHomeColor'] . ';"></i></div>';
            }

            if ($custom_control['panZoomInSwitch'] == "on") {
                $html .= '<div class="ctrl" id="zoom-in' . $id . '"><i class="' . $custom_control['panZoomInIcon'] . '" style="color:' . $custom_control['panZoomInColor'] . ';"></i></div>';
            }

            if ($custom_control['panZoomOutSwitch'] == "on") {
                $html .= '<div class="ctrl" id="zoom-out' . $id . '"><i class="' . $custom_control['panZoomOutIcon'] . '" style="color:' . $custom_control['panZoomOutColor'] . ';"></i></div>';
            }

            if ($custom_control['gyroscopeSwitch'] == "on") {
                $html .= '<div class="ctrl" id="gyroscope' . $id . '"><i class="' . $custom_control['gyroscopeIcon'] . '" style="color:' . $custom_control['gyroscopeColor'] . ';"></i></div>';
            }

            $html .= '</div>';
        }
        //===zoom in out Control===//

        if ($custom_control['panupSwitch'] == "on" || $custom_control['panDownSwitch'] == "on" || $custom_control['panLeftSwitch'] == "on" || $custom_control['panRightSwitch'] == "on" || $custom_control['panFullscreenSwitch'] == "on") {

            //===Custom Control===//
            $html .= '<div class="controls" id="controls' . $id . '">';

            if ($custom_control['panupSwitch'] == "on") {
                $html .= '<div class="ctrl pan-up" id="pan-up' . $id . '"><i class="' . $custom_control['panupIcon'] . '" style="color:' . $custom_control['panupColor'] . ';"></i></div>';
            }

            if ($custom_control['panDownSwitch'] == "on") {
                $html .= '<div class="ctrl pan-down" id="pan-down' . $id . '"><i class="' . $custom_control['panDownIcon'] . '" style="color:' . $custom_control['panDownColor'] . ';"></i></div>';
            }

            if ($custom_control['panLeftSwitch'] == "on") {
                $html .= '<div class="ctrl pan-left" id="pan-left' . $id . '"><i class="' . $custom_control['panLeftIcon'] . '" style="color:' . $custom_control['panLeftColor'] . ';"></i></div>';
            }

            if ($custom_control['panRightSwitch'] == "on") {
                $html .= '<div class="ctrl pan-right" id="pan-right' . $id . '"><i class="' . $custom_control['panRightIcon'] . '" style="color:' . $custom_control['panRightColor'] . ';"></i></div>';
            }

            if ($custom_control['panFullscreenSwitch'] == "on") {
                $html .= '<div class="ctrl fullscreen" id="fullscreen' . $id . '"><i class="' . $custom_control['panFullscreenIcon'] . '" style="color:' . $custom_control['panFullscreenColor'] . ';"></i></div>';
            }
            $html .= '</div>';
        }

        //===explainer button===//
        if ($custom_control['explainerSwitch'] == "on") {
            $html .= '<div class="explainer_button" id="explainer_button_' . $id . '" style="right:' . $explainer_right . '">';
            $html .= '<div class="ctrl" id="explainer_target_' . $id . '"><i class="' . $custom_control['explainerIcon'] . '" style="color:' . $custom_control['explainerColor'] . ';"></i></div>';
            $html .= '</div>';
        }

        //===explainer button===//

    }
    //===Custom Control===//

    //===Scene navigation Control===//
    if (isset($postdata['scene_navigation']) && $postdata['scene_navigation'] === 'on') {
        $html .= '<style>
                #et-boc .et-l .pnlm-controls-container, 
                .pnlm-controls-container{
                    top: 33px;
                }
                
                #et-boc .et-l .zoom-in-out-controls, 
                .zoom-in-out-controls {
                    top: 37px;
                }
            </style>';
        $html .= '<div id="custom-scene-navigation' . $id . '" class="custom-scene-navigation">
                <span class="hamburger-menu"><svg width="16" height="10" fill="none" viewBox="0 0 22 15" xmlns="http://www.w3.org/2000/svg"><rect width="21.177" height="2.647" fill="#f7fffb" rx="1.324"/><rect width="21.177" height="2.647" y="6.177" fill="#f7fffb" rx="1.324"/><rect width="21.177" height="2.647" y="12.352" fill="#f7fffb" rx="1.324"/></svg></span> 
              </div>
              
              <div id="custom-scene-navigation-nav' . $id . '" class="custom-scene-navigation-nav">
                  <ul></ul>
              </div> 
              ';
    }

    //===Scene navigation  Control===//

    //=====custom generic form=====//
    if (isset($postdata["genericform"]) && $postdata["genericform"] == 'on') {
        $shortcode_val = isset($postdata["genericformshortcode"]) && $postdata["genericformshortcode"] !== ""? do_shortcode($postdata["genericformshortcode"]) : "No shortcode found";
        $html .= '<div class="generic_form_button" id="generic_form_button_' . $id . '">';
        $html .= '<div class="ctrl" id="generic_form_target_' . $id . '"><i class="fab fa-wpforms" style="color:#f7fffb;"></i></div>';
        $html .= '</div>';

        $html .= '<div class="wpvr-generic-form" id="wpvr-generic-form' . $id . '" style="display: none">';
        $html .= '<span class="close-generic-form"><i class="fa fa-times"></i></span>';
        $html .= '<div class="generic-form-container">'.$shortcode_val.'</div>';
        $html .= '</div>';
    }
    //=====custom generic form=====//

    //===Floor map button===//
    $status  = get_option('wpvr_edd_license_status');
    if ($status !== false &&  'valid' == $status  && $is_pro){
        if ($floor_plan_enable == "on" && !empty($floor_plan_image)) {
            $html .= '<div class="floor_map_button" id="floor_map_button_' . $id . '" style="right:'.$floor_map_right.'">';
            $html .= '<div class="ctrl" id="floor_map_target_' . $id . '"><i class="fas fa-map" style="color:#f7fffb;"></i></div>';
            $html .= '</div>';
        }
    }

    //===floor map button===//
    if ($vrgallery) {
        //===Carousal setup===//
        $size = '';
        if($vrgallery_icon_size){
            $size = 'vrg-icon-size-large';
        }
        $html .= '<div id="vrgcontrols' . $id . '" class="vrgcontrols">';

        $html .= '<div class="vrgctrl' . $id . ' vrbounce '.$size.'">';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div id="sccontrols' . $id . '" class="scene-gallery vrowl-carousel owl-theme">';
        if (isset($panodata["scene-list"])) {
            foreach ($panodata["scene-list"] as $panoscenes) {
                $scene_key = $panoscenes['scene-id'];
                if ($vrgallery_title == 'on') {
                    $scene_key_title = $panoscenes['scene-ititle'];
                    // $scene_key_title = $panoscenes['scene-id'];
                } else {
                    $scene_key_title = "";
                }

                if ($panoscenes['scene-type'] == 'cubemap') {
                    $img_src_url = $panoscenes['scene-attachment-url-face0'];
                } else {
                    $img_src_url = $panoscenes['scene-attachment-url'];
                }


                $src_to_id = attachment_url_to_postid($img_src_url);
                $thumbnail_array = wp_get_attachment_image_src($src_to_id, 'thumbnail');
                if ($thumbnail_array) {
                    $thumbnail = $thumbnail_array[0];
                } else {
                    $thumbnail = $img_src_url;
                }

                $html .= '<ul style="width:150px;"><li title="Double click to view scene">' . $scene_key_title . '<img loading="lazy" class="scctrl" id="' . $scene_key . '_gallery_' . $id . '" src="' . $thumbnail . '"></li></ul>';
            }
        }
        $html .= '</div>';
        $html .= '
        <div class="owl-nav wpvr_slider_nav">
        <button type="button" role="presentation" class="owl-prev wpvr_owl_prev">
            <div class="nav-btn prev-slide"><i class="fa fa-angle-left"></i></div>
        </button>
        <button type="button" role="presentation" class="owl-next wpvr_owl_next">
            <div class="nav-btn next-slide"><i class="fa fa-angle-right"></i></div>
        </button>
        </div>
        ';
        //===Carousal setup end===//
    }

    if (isset($postdata['bg_music'])) {
        $bg_music = $postdata['bg_music'];
        $bg_music_url = $postdata['bg_music_url'];
        $autoplay_bg_music = $postdata['autoplay_bg_music'];
        $loop_bg_music = $postdata['loop_bg_music'];
        $bg_loop = '';
        if ($loop_bg_music == 'on') {
            $bg_loop = 'loop';
        }

        if ($bg_music == 'on') {
            $html .= '<div id="adcontrol' . $id . '" class="adcontrol" style="right:' . $audio_right . '">';
            $html .= '<audio class="vrAudioDefault" id="vrAudio' . $id . '" data-autoplay="' . $autoplay_bg_music . '"  onended="audionEnd' . $id . '()" ' . $bg_loop . '>
                    <source src="' . $bg_music_url . '" type="audio/mpeg">
                    Your browser does not support the audio element.
                  </audio>
                  <button onclick="playPause' . $id . '()" class="ctrl audio_control" data-play="' . $autoplay_bg_music . '" id="audio_control' . $id . '"><i id="vr-volume' . $id . '" class="wpvrvolumeicon' . $id . ' fas fa-volume-up" style="color:#fff;"></i></button>
                  ';
            $html .= '</div>';
        }
    }

    //===Explainer video section===//
    $explainerContent = "";
    if (isset($postdata['explainerContent'])) {
        $explainerContent = $postdata['explainerContent'];
    }
    $html .= '<div class="explainer" id="explainer' . $id . '" style="display: none">';
    $html .= '<span class="close-explainer-video"><i class="fa fa-times"></i></span>';
    $html .= '' . $explainerContent . '';
    $html .= '</div>';
    //===Explainer video section End===//

    //===Floor plan section===//
    $floor_map_image = "";
    $floor_map_pointer = array();
    $floor_map_scene_id = '';
    $floor_plan_custom_color = '#cca92c';

    if (isset($postdata['floor_plan_attachment_url'])) {
        $floor_map_image = $postdata['floor_plan_attachment_url'];
        $floor_map_pointer = $postdata['floor_plan_pointer_position'];
        $floor_map_scene_id = $postdata['floor_plan_data_list'];
        $floor_plan_custom_color = $postdata['floor_plan_custom_color'];
    }
    $html .= '<div class="wpvr-floor-map" id="wpvr-floor-map' . $id . '" style="display: none">';
    $html .= '<span class="close-floor-map-plan"><i class="fa fa-times"></i></span>';
    $html .= '<img loading="lazy" src="'.$floor_map_image.'">';

    foreach($floor_map_pointer as $key=> $pointer_position){
        $html .= '<div class="floor-plan-pointer ui-draggable ui-draggable-handle" scene_id = "'.$floor_map_scene_id[$key]->value.'" id="'.$pointer_position->id.'" data-top="'.$pointer_position->data_top.'" data-left="'.$pointer_position->data_left.'" style="'.$pointer_position->style.'">                        
                                    
                                    <svg class="floor-pointer-circle" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="11.5" stroke="'.$floor_plan_custom_color.'"/>
                                        <circle cx="12" cy="12" r="5" fill="'.$foreground_color_pointer.'"/>
                                    </svg>
                                    <svg class="floor-pointer-flash" width="54" height="35" viewBox="0 0 54 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.454054 1.32433L11.7683 34.3243C11.9069 34.7285 12.287 35 12.7143 35H41.2857C41.713 35 42.0931 34.7285 42.2317 34.3243L53.5459 1.32432C53.7685 0.675257 53.2862 0 52.6 0H1.4C0.713843 0 0.231517 0.675258 0.454054 1.32433Z" fill="url(#paint0_linear_1_10)"/>
                                        <defs>
                                        <linearGradient id="paint0_linear_1_10" x1="27" y1="4.59807e-08" x2="26.5" y2="28" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="'.$floor_plan_custom_color.'" stop-opacity="0"/>
                                        <stop offset="1" stop-color="'.$floor_plan_custom_color.'"/>
                                        </linearGradient>
                                        </defs>
                                    </svg>
    
                                </div>';
    }
    $html .= '</div>';
    //===Floor plan section===//

    $html .= '<div class="wpvr-hotspot-tweak-contents-wrapper" style="display: none">';
    $html .= '<i class="fa fa-times cross" data-id="' . $id . '"></i>';
    $html .= '<div class="wpvr-hotspot-tweak-contents-flex">';
    $html .= '<div class="wpvr-hotspot-tweak-contents">';
    ob_start();
    do_action('wpvr_hotspot_tweak_contents', $scene_data);
    $hotspot_content = ob_get_clean();
    $html .= $hotspot_content;
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    $html .= '<div class="custom-ifram-wrapper" style="display: none;">';
    $html .= '<i class="fa fa-times cross" data-id="' . $id . '"></i>';
    $html .= '<div class="custom-ifram-flex">';
    $html .= '<div class="custom-ifram">';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';


    if( "fullwidth" == $width ){
        $html .= '</div>';
    }
    if ($status !== false &&  'valid' == $status  && $is_pro) {
        $call_to_action = isset($postdata['calltoaction']) ? $postdata['calltoaction'] : 'off';
        if( 'on' == $call_to_action){
            $buttontext = isset($postdata['buttontext']) ? $postdata['buttontext'] : '';
            $buttonurl = isset($postdata['buttonurl']) ? $postdata['buttonurl'] : '';
            $cta_btn_style = isset($postdata['button_configuration']) ? $postdata['button_configuration'] : array();

            $button_open_new_tab = isset($cta_btn_style['button_open_new_tab']) ? $cta_btn_style['button_open_new_tab'] : "off";
            $target = '_self';
            $button_position = isset($cta_btn_style['button_position']) ? $cta_btn_style['button_position'] : "";
            $background_color = isset($cta_btn_style['button_background_color']) ? $cta_btn_style['button_background_color'] : "";
            $color = isset($cta_btn_style['button_font_color']) ? $cta_btn_style['button_font_color'] : "";
            $font_size = isset($cta_btn_style['button_font_size']) ? $cta_btn_style['button_font_size'] : "";
            $font_weight = isset($cta_btn_style['button_font_weight']) ? $cta_btn_style['button_font_weight'] : "";
            $text_align = isset($cta_btn_style['button_alignment']) ? $cta_btn_style['button_alignment'] : "";
            $text_transform = isset($cta_btn_style['button_transform']) ? $cta_btn_style['button_transform'] : "";
            $font_style = isset($cta_btn_style['button_text_style']) ? $cta_btn_style['button_text_style'] : "";
            $text_decoration = isset($cta_btn_style['button_text_decoration']) ? $cta_btn_style['button_text_decoration'] : "";
            $line_height = isset($cta_btn_style['button_line_height']) ? $cta_btn_style['button_line_height'] : "";
            $letter_spacing = isset($cta_btn_style['button_letter_spacing']) ? $cta_btn_style['button_letter_spacing'] : "";
            $word_spacing = isset($cta_btn_style['button_word_spacing']) ? $cta_btn_style['button_word_spacing'] : "";

            $border_width = isset($cta_btn_style['button_border_width']) ? $cta_btn_style['button_border_width'] : "";
            $border_style = isset($cta_btn_style['button_border_style']) ? $cta_btn_style['button_border_style'] : "";
            $border_color = isset($cta_btn_style['button_border_color']) ? $cta_btn_style['button_border_color'] : "";
            $border_radius = isset($cta_btn_style['button_border_radius']) ? $cta_btn_style['button_border_radius'] : "";

            $button_pt = isset($cta_btn_style['button_pt']) ? $cta_btn_style['button_pt'] : "";
            $button_pr = isset($cta_btn_style['button_pr']) ? $cta_btn_style['button_pr'] : "";
            $button_pb = isset($cta_btn_style['button_pb']) ? $cta_btn_style['button_pb'] : "";
            $button_pl = isset($cta_btn_style['button_pl']) ? $cta_btn_style['button_pl'] : "";

            if($button_open_new_tab == 'on'){
                $target = '_blank';
            }
            $style = 'background-color: '.$background_color.';
                      color: '.$color.';
                      font-size: '.$font_size.'px;
                      font-weight: '.$font_weight.';
                      text-align: center;
                      display: inline-block;
                      text-transform: '.$text_transform.';
                      font-style: '.$font_style.';
                      text-decoration: '.$text_decoration.';
                      line-height: '.$line_height.';
                      letter-spacing: '.$letter_spacing.'px;
                      word-spacing: '.$word_spacing.'px;
                      border: '.$border_width.'px '.$border_style.' '.$border_color.';
                      border-radius: '.$border_radius.'px;
                      padding: '.$button_pt.'px '.$button_pr.'px '.$button_pb.'px '.$button_pl.'px;
                     ';
            $html .= '<div class="wpvr-call-to-action-button position-'.$text_align.'" style="max-width:' . $width . $width_unit.'">
                        <a href="'.$buttonurl.'" style="'.$style.'" target="'.$target.'">'.$buttontext.'</a>
                      </div>';

        }
    }

    //script started

    $html .= '<script>';



    if (isset($postdata['bg_music'])) {
        if ($bg_music == 'on') {
            $html .= '
            var x' . $id . ' = document.getElementById("vrAudio' . $id . '");

            var playing' . $id . ' = false;

              function playPause' . $id . '() {

                if (playing' . $id . ') {
                  jQuery("#vr-volume' . $id . '").removeClass("fas fa-volume-up");
                  jQuery("#vr-volume' . $id . '").addClass("fas fa-volume-mute");
                  x' . $id . '.pause();
                  jQuery("#audio_control' . $id . '").attr("data-play", "off");
                  playing' . $id . ' = false;

                }
                else {
                  jQuery("#vr-volume' . $id . '").removeClass("fas fa-volume-mute");
                  jQuery("#vr-volume' . $id . '").addClass("fas fa-volume-up");
                  jQuery("#audio_control' . $id . '").attr("data-play", "on");
                  x' . $id . '.play();
                  playing' . $id . ' = true;
                }
              }

              function audionEnd' . $id . '() {
                playing' . $id . ' = false;
                jQuery("#vr-volume' . $id . '").removeClass("fas fa-volume-up");
                jQuery("#vr-volume' . $id . '").addClass("fas fa-volume-mute");
                jQuery("#audio_control' . $id . '").attr("data-play", "off");
              }
              ';

            if ($autoplay_bg_music == 'on') {
                $html .= '
                document.getElementById("pano' . $id . '").addEventListener("click", musicPlay' . $id . ');
                function musicPlay' . $id . '() {
                    playing' . $id . ' = true;
                    document.getElementById("vrAudio' . $id . '").play();
                    document.getElementById("pano' . $id . '").removeEventListener("click", musicPlay' . $id . ');
                }
                ';
            }
        }
    }
    $html .= 'jQuery(document).ready(function() {';
    $html .= 'var response = ' . $response . ';';
    $html .= 'var scenes = response[1];';
    $html .= 'if(scenes) {';
    $html .= 'var scenedata = scenes.scenes;';
    $html .= 'for(var i in scenedata) {';
    $html .= 'var scenehotspot = scenedata[i].hotSpots;';
    $html .= 'for(var i = 0; i < scenehotspot.length; i++) {';
    $html .= 'if(scenehotspot[i]["clickHandlerArgs"] != "") {';
    $html .= 'scenehotspot[i]["clickHandlerFunc"] = wpvrhotspot;';
    $html .= '}';
    if (wpvr_isMobileDevice() && get_option('dis_on_hover') == "true") {
    } else {
        $html .= 'if(scenehotspot[i]["createTooltipArgs"] != "") {';
        $html .= 'scenehotspot[i]["createTooltipFunc"] = wpvrtooltip;';
        $html .= '}';
    }
    $html .= '}';
    $html .= '}';
    $html .= '}';
    $html .= 'var panoshow' . $id . ' = pannellum.viewer(response[0]["panoid"], scenes);';


    //===Dplicate mode only for vr mode===//
    $response2 = json_decode($response);
    $response2[1]->compass = false;
    $response2[1]->autoRotate = false;
    $response = json_encode($response2);
    $html .= 'var response_duplicate = ' . $response . ';';
    $html .= 'var scenes_duplicate = response_duplicate[1];';

    $html .= 'if(scenes_duplicate) {';
    $html .= 'var scenedata = scenes_duplicate.scenes;';
    $html .= 'for(var i in scenedata) {';
    $html .= 'var scenehotspot = scenedata[i].hotSpots;';
    $html .= 'for(var i = 0; i < scenehotspot.length; i++) {';
    $html .= 'if(scenehotspot[i]["clickHandlerArgs"] != "") {';
    $html .= 'scenehotspot[i]["clickHandlerFunc"] = wpvrhotspot;';
    $html .= '}';
    if (wpvr_isMobileDevice() && get_option('dis_on_hover') == "true") {
    } else {
        $html .= 'if(scenehotspot[i]["createTooltipArgs"] != "") {';
        $html .= 'scenehotspot[i]["createTooltipFunc"] = wpvrtooltip;';
        $html .= '}';
    }
    $html .= '}';
    $html .= '}';
    $html .= '}';
    $html .= 'var vr_mode = "off";';
    $status  = get_option('wpvr_edd_license_status');
    if ($status !== false &&  'valid' == $status  && $is_pro) {
        $html .= 'var panoshow2' . $id . ' = pannellum.viewer("pano2' . $id . '", scenes_duplicate);';

// Show Cardboard Mode in Tour
        $html .= '
        var tim;
        var im = 0;
        var active_scene = "'.$default_scene.'";
        var c_time;
        c_time = new Date();
        var timer = c_time.getTime() + 2000;
       function panoShowCardBoardOnTrigger(data){
            if(scenes_duplicate) {
                var scenedata = scenes_duplicate.scenes;
                for(var i in scenedata) {
                    if(active_scene === i) {
                        var scenehotspot = scenedata[i].hotSpots;
                        for(var j in scenehotspot) {
                            var plusFiveYaw = Math.round(scenehotspot[j].yaw) + 5;
                            var minusFiveYaw = Math.round(scenehotspot[j].yaw) - 5;
                            var plusFivePitch = Math.round(scenehotspot[j].pitch) + 5;
                            var minusFivePitch = Math.round(scenehotspot[j].pitch) - 5;
                            var firstCondition = ( Math.round(data.pitch) > minusFivePitch) && (Math.round(data.pitch) < plusFivePitch) ;
                            var secCondition = (Math.round(data.yaw) > minusFiveYaw) && (Math.round(data.yaw) < plusFiveYaw);
                            if(( Math.round(data.pitch) > minusFivePitch) && (Math.round(data.pitch) < plusFivePitch) ){
                                if((Math.round(data.yaw) > minusFiveYaw) && (Math.round(data.yaw) < plusFiveYaw)){
                                    jQuery(".center-pointer").addClass("wpvr-pluse-effect")
                                    var getScene = scenehotspot[j].sceneId;
                                    if(scenehotspot[j].type == "scene"){
                                            panoshow' . $id . '.loadScene(getScene);
                                            panoshow2' . $id . '.loadScene(getScene);
//                                            var inside_current_time_object = new Date();
//                                            var inside_timer = inside_current_time_object.getTime();
//                                            if(inside_timer > timer) {
//                                                panoshow' . $id . '.loadScene(getScene);
//                                                panoshow2' . $id . '.loadScene(getScene);
//                                                jQuery(".center-pointer").removeClass("wpvr-pluse-effect")
//                                            }
                                    }else{
                                        jQuery(".center-pointer").removeClass("wpvr-pluse-effect")
                                    }
                                }
                                else {
                                    jQuery(".center-pointer").removeClass("wpvr-pluse-effect")
                                    c_time = new Date();
                                    timer = c_time.getTime() + 2000;
                                }
                            }
                            else {
                                c_time = new Date();
                                timer = c_time.getTime() + 2000;
                            }
                        }
                    }
                }
            }
       };
       
       function vrDeviseOrientation(){
            var data = {
                pitch: panoshow' . $id . '.getPitch(),
                yaw: panoshow' . $id . '.getYaw(),
            };
            panoShowCardBoardOnTrigger(data);
       }
    ';
        $html .= '
           
            function requestFullScreen(){
                var elem = document.getElementById("master-container");
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                  } else if (elem.webkitRequestFullscreen) { /* Safari */
                    elem.webkitRequestFullscreen();
                  } else if (elem.msRequestFullscreen) { /* IE11 */
                    elem.msRequestFullscreen();
                  }
            }
            function requestExitFullscreen(){
                var elem = document.getElementById("master-container");
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                 } else if (document.webkitExitFullscreen) { /* Safari */
                    document.webkitExitFullscreen();
                 } else if (document.msExitFullscreen) { /* IE11 */
                    document.msExitFullscreen();
                 }
            }
            jQuery(document).on("click",".fullscreen-button .expand",function() {
                jQuery(this).hide()
                jQuery(this).parent().find(".compress").show()
                requestFullScreen()
                screen.orientation.lock("landscape-primary")
                .then(function() {
                })
                .catch(function(error) {
                    alert("Not Supported for this devise");
                });
        
            });   
            jQuery(document).on("click",".fullscreen-button .compress",function() {
                jQuery(this).hide()
                jQuery(this).parent().find(".expand").show()
                requestExitFullscreen()
                screen.orientation.unlock();
                 
            }); 
            ';

        $html .= '
        panoshow' . $id . '.on("scenechange", function (scene){
            jQuery(".center-pointer").removeClass("wpvr-pluse-effect")
            active_scene = scene;
        });
        var compassBlock = "";
        var infoBlock = "";
        var getValue = "";
        jQuery(document).on("click",".vr_mode_change' . $id . '",function (){
          
          jQuery("#pano2' . $id . ' .pnlm-load-button").trigger("click");
          jQuery("#pano' . $id . ' .pnlm-load-button").trigger("click");
        
          getValue =   jQuery(this).val();
          var getParent = jQuery(this).parent().parent();
          var fullWidthParent = getParent.parent();
          var compass = getParent.find("#pano2' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display");
          var panoInfo = getParent.find("#pano' . $id . ' .pnlm-panorama-info").css("display");
          if(compass == "block"){
            compassBlock = "block";
          }
          if(panoInfo == "block"){
            infoBlock = "block";
          }
            if (getValue == "off") {
                requestFullScreen()
                screen.orientation.lock("landscape-primary")
                .then(function() {
                })
                .catch(function(error) {
                    alert("VR Glass Mode not supported in this device");
                });
                getParent.find(".pano-wrap").addClass("wpvr-cardboard-disable-event");
                // localStorage.setItem("vr_mode", "on");
                vr_mode = "on";
                jQuery(".vr-mode-title").show();
                jQuery(this).val("on");
                getParent.find("#pano2' . $id . '").css({
                    "opacity": "1", 
                    "visibility": "visible",
                    "position": "relative",
                });
                gyroSwitch = true;
                panoshow' . $id . '.startOrientation();
                panoshow2' . $id . '.startOrientation();
                panoshow2' . $id . '.setPitch(panoshow' . $id . '.getPitch(), 0);
                panoshow2' . $id . '.setYaw(panoshow' . $id . '.getYaw(), 0);
                getParent.find("#pano' .$id. ' #zoom-in-out-controls'.$id.'").hide();
                getParent.find("#pano' .$id. ' #controls'.$id.'").hide();
                getParent.find("#pano' .$id. ' #explainer_button_'.$id.'").hide();
                getParent.find("#pano' .$id. ' #generic_form_button_'.$id.'").hide();
                getParent.find("#pano' .$id. ' #floor_map_button_'.$id.'").hide();
                getParent.find("#pano' .$id. ' #vrgcontrols'.$id.'").hide();
                getParent.find("#pano' .$id. ' #sccontrols'.$id.'").hide();
                getParent.find("#pano' .$id. ' #adcontrol'.$id.'").hide();
                getParent.find("#pano' .$id. ' .owl-nav.wpvr_slider_nav").hide();
                getParent.find("#pano' .$id. ' #cp-logo-controls").hide();
                
                getParent.find("#pano' .$id. ' #custom-scene-navigation'.$id.'").hide();
                
                getParent.find("#pano2' . $id . ' .pnlm-controls-container").hide();
                getParent.find("#pano' . $id . ' .pnlm-controls-container").hide();
                
                getParent.find("#pano2' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").hide();
                getParent.find("#pano' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").hide();
                
               getParent.find("#pano2' . $id . ' .pnlm-panorama-info").hide();
               getParent.find("#pano' . $id . ' .pnlm-panorama-info").hide();
               getParent.find("#pano' . $id . '").addClass("cardboard-half");
               
                getParent.find("#center-pointer' . $id . '").show();
                getParent.find(".fullscreen-button").hide();
    
                
                if (window.DeviceOrientationEvent) {
                    window.addEventListener("deviceorientation", vrDeviseOrientation);
                }
              
                panoshow' . $id . '.on("mousemove", function (data){
                    panoshow2' . $id . '.setPitch(data.pitch, 0);
                    panoshow2' . $id . '.setYaw(data.yaw, 0);
                    panoShowCardBoardOnTrigger(data);
            
                });
                panoshow2' . $id . '.on("mousemove", function (data){
                    panoshow2' . $id . '.setPitch(data.pitch, 0);
                    panoshow' . $id . '.setYaw(data.yaw, 0);
                    panoShowCardBoardOnTrigger(data);
            
                });

                panoshow' . $id . '.on("zoomchange", function (data){
                    panoshow2' . $id . '.setHfov(data, 0);
                });

                panoshow2' . $id . '.on("zoomchange", function (data){
                    panoshow' . $id . '.setHfov(data, 0);
                });
                
                panoshow' . $id . '.on("touchmove", function (data){
                    panoshow' . $id . '.stopOrientation();
                    panoshow2' . $id . '.stopOrientation();
                    panoshow2' . $id . '.setPitch(data.pitch, 0);
                    panoshow2' . $id . '.setYaw(data.yaw, 0);
                    panoShowCardBoardOnTrigger(data);
            
                });
                
                panoshow2' . $id . '.on("touchmove", function (data){
                    panoshow' . $id . '.stopOrientation();
                    panoshow2' . $id . '.stopOrientation();
                    panoshow' . $id . '.setPitch(data.pitch, 0);
                    panoshow' . $id . '.setYaw(data.yaw, 0);
                    panoShowCardBoardOnTrigger(data);
            
                });
                
            }
            else if(getValue == "on") {
                screen.orientation.unlock();
                requestExitFullscreen();
                 // localStorage.setItem("vr_mode", "off");
                vr_mode = "off";
                jQuery(".vr-mode-title").hide();
                jQuery(this).val("off");
                getParent.find("#pano2' . $id . '").css({
                    "opacity": "0", 
                    "visibility": "hidden",
                    "position": "absolute",
                });
                panoshow' . $id . '.stopOrientation();
                panoshow2' . $id . '.stopOrientation();
                getParent.find(".pano-wrap").removeClass("wpvr-cardboard-disable-event");
                getParent.find("#pano' .$id. ' #zoom-in-out-controls'.$id.'").show();
                getParent.find("#pano' .$id. ' #controls'.$id.'").show();
                getParent.find("#pano' .$id. ' #explainer_button_'.$id.'").show();
                getParent.find("#pano' .$id. ' #generic_form_button_'.$id.'").show();
                getParent.find("#pano' .$id. ' #floor_map_button_'.$id.'").show();

                getParent.find("#pano2' . $id . ' .pnlm-controls-container").show();
                getParent.find("#pano' . $id . ' .pnlm-controls-container").show();
                getParent.find("#pano' .$id. ' #vrgcontrols'.$id.'").show();
                getParent.find("#pano' .$id. ' #sccontrols'.$id.'").hide();
                getParent.find("#pano' .$id. ' #adcontrol'.$id.'").show();
                getParent.find("#pano' .$id. ' .owl-nav.wpvr_slider_nav").hide();
                getParent.find("#pano' .$id. ' #cp-logo-controls").show();
                getParent.find("#pano' .$id. ' #custom-scene-navigation'.$id.'").show();
                
                if(compassBlock == "block"){
                    getParent.find("#pano2' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").show();
                    getParent.find("#pano' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").show();
                }
                if(infoBlock == "block"){
                    getParent.find("#pano2' . $id . ' .pnlm-panorama-info").show();
                    getParent.find("#pano' . $id . ' .pnlm-panorama-info").show();
                }
               
                getParent.find("#pano' . $id . '").removeClass("cardboard-half");
                getParent.find("#center-pointer' . $id . '").hide();
                getParent.find(".fullscreen-button").hide();
                panoshow' . $id . '.off("mousemove");
                panoshow' . $id . '.off("touchmove");
                panoshow2' . $id . '.off("mousemove");
                panoshow2' . $id . '.off("touchmove");
                if (window.DeviceOrientationEvent) {
                    window.removeEventListener("deviceorientation", vrDeviseOrientation);
                }
            }
        });';

        $html .= 'panoshow2' . $id . '.on("load", function (){
//                if(localStorage.getItem("vr_mode") == "off") {
                 if( vr_mode == "off") {
                      jQuery(".vr-mode-title").hide();
                    }
                 else {
                    jQuery("#pano2' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
                    jQuery("#pano' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
                    jQuery("#pano2' . $id . ' .pnlm-panorama-info").hide();
                    jQuery("#pano' . $id . ' .pnlm-panorama-info").hide();
                    jQuery(".vr-mode-title").show();
                 }
         });';
    }

    $html .= 'jQuery("#pano' . $id . ' .wpvr-floor-map .floor-plan-pointer").on("click",function(){
           var scene_id = jQuery(this).attr("scene_id");
           panoshow' . $id . '.loadScene(scene_id)
           jQuery(".floor-plan-pointer").removeClass("add-pulse")
           jQuery(this).addClass("add-pulse")
    });';

    $html .= '
    panoshow' . $id . '.on("mousemove", function (data){
        jQuery(".add-pulse").css({"transform":"rotate("+data.yaw+"deg)"});
    });
';



    $status  = get_option('wpvr_edd_license_status');
    if ($status !== false &&  'valid' == $status  && $is_pro){
        $html .= 'panoshow' . $id . '.on("scenechange", function (scene){
            jQuery(".center-pointer").removeClass("wpvr-pluse-effect")
            jQuery(".floor-plan-pointer").each(function(index ,element){
                var scene_id = jQuery(this).attr("scene_id");
                if( active_scene == scene_id ){
                    jQuery(".floor-plan-pointer").removeClass("add-pulse")
                    jQuery(this).addClass("add-pulse")
                }
            });
            
        });';
        $html .= 'panoshow' . $id . '.on("load", function (){
           if(jQuery(".floor-plan-pointer").length > 0){
               jQuery(".floor-plan-pointer").each(function(index ,element){
                    var scene_id = jQuery(this).attr("scene_id");
                    if( active_scene == scene_id ){
                        jQuery(".floor-plan-pointer").removeClass("add-pulse")
                        jQuery(this).addClass("add-pulse")
                    }
                });
           }
        });';
    }

    if ($status !== false &&  'valid' == $status  && $is_pro){
        $html .= '
         jQuery("#pano' . $id . ' .custom-scene-navigation").on("click",function(){
            jQuery("#custom-scene-navigation-nav' . $id . ' ul").empty()
                if(scenes){
                    var sceneList = scenes.scenes;
                    var getScene = panoshow' . $id . '.getScene();
                    for (const key in sceneList) {
                    if (sceneList.hasOwnProperty(key)) {
                    if( key === getScene){
                        jQuery("#custom-scene-navigation-nav' . $id . ' ul").append("<li class=\"scene-navigation-list active\" scene_id= " + key + " >" + key + "</li>");
                    }else{
                            jQuery("#custom-scene-navigation-nav' . $id . ' ul").append("<li class=\"scene-navigation-list\" scene_id= " + key + " >" + key + "</li>");
                        }
                    }
                }
            }
             jQuery("#custom-scene-navigation-nav' . $id . '").toggleClass("visible");
         });
     ';

        
        $html .= '
        jQuery("#pano' . $id . ' #custom-scene-navigation-nav' . $id . ' ul").on("click", "li.scene-navigation-list", function() {
            if (scenes) {
                jQuery(this).siblings("li").removeClass("active");
                jQuery(this).addClass("active");

                var scene_key = jQuery(this).attr("scene_id");
                panoshow' . $id . '.loadScene(scene_key);
            }
        });
     ';
    }

    //Duplicate mode only for vr mode end===//
    $html .= 'panoshow' . $id . '.on("load", function (){
    
//              if(localStorage.getItem("vr_mode") == "off") {
               if( vr_mode == "off") {
                  jQuery(".vr-mode-title").hide();
             } 
             else {
                jQuery("#pano2' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
                jQuery("#pano' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
                jQuery("#pano2' . $id . ' .pnlm-panorama-info").hide();
                jQuery("#pano' . $id . ' .pnlm-panorama-info").hide();
                jQuery(".vr-mode-title").show();
             }
             
    
          if (jQuery("#pano' . $id . '").children().children(".pnlm-panorama-info:visible").length > 0) {
               jQuery("#controls' . $id . '").css("bottom", "55px");
           }
           else {
             jQuery("#controls' . $id . '").css("bottom", "5px");
           }
        });';


    $html .= '
        if (scenes.autoRotate) {
          panoshow' . $id . '.on("load", function (){
           setTimeout(function(){ panoshow' . $id . '.startAutoRotate(scenes.autoRotate, 0); }, 3000);
          });
          panoshow' . $id . '.on("scenechange", function (){
           setTimeout(function(){ panoshow' . $id . '.startAutoRotate(scenes.autoRotate, 0); }, 3000);
          });
        }
        ';
    $html .= 'var touchtime = 0;';
    if ($vrgallery) {
        if (isset($panodata["scene-list"])) {
            foreach ($panodata["scene-list"] as $panoscenes) {
                $scene_key = $panoscenes['scene-id'];
                $scene_key_gallery = $panoscenes['scene-id'] . '_gallery_' . $id;
                $img_src_url = $panoscenes['scene-attachment-url'];
                // $html .= 'document.getElementById("' . $scene_key_gallery . '").addEventListener("click", function(e) { ';
                // $html .= 'if (touchtime == 0) {';
                // $html .= 'touchtime = new Date().getTime();';
                // $html .= '} else {';
                // $html .= 'if (((new Date().getTime()) - touchtime) < 800) {';
                // $html .= 'panoshow' . $id . '.loadScene("' . $scene_key . '");';
                // $html .= 'touchtime = 0;';
                // $html .= '} else {';
                // $html .= 'touchtime = new Date().getTime();';
                // $html .= '}';
                // $html .= '}';
                // $html .= '});';
                $html .= '
                jQuery(document).on("click","#' . $scene_key_gallery . '",function() {
                    panoshow' . $id . '.loadScene("' . $scene_key . '");
                });
                ';
            }
        }
    }



    //===Custom Control===//
    if (isset($custom_control)) {
        if ($custom_control['panupSwitch'] == "on") {
            $html .= 'document.getElementById("pan-up' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.setPitch(panoshow' . $id . '.getPitch() + 10);';
            $html .= '});';
        }

        if ($custom_control['panDownSwitch'] == "on") {
            $html .= 'document.getElementById("pan-down' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.setPitch(panoshow' . $id . '.getPitch() - 10);';
            $html .= '});';
        }

        if ($custom_control['panLeftSwitch'] == "on") {
            $html .= 'document.getElementById("pan-left' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.setYaw(panoshow' . $id . '.getYaw() - 10);';
            $html .= '});';
        }

        if ($custom_control['panRightSwitch'] == "on") {
            $html .= 'document.getElementById("pan-right' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.setYaw(panoshow' . $id . '.getYaw() + 10);';
            $html .= '});';
        }

        if ($custom_control['panZoomInSwitch'] == "on") {
            $html .= 'document.getElementById("zoom-in' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.setHfov(panoshow' . $id . '.getHfov() - 10);';
            $html .= '});';
        }

        if ($custom_control['panZoomOutSwitch'] == "on") {
            $html .= 'document.getElementById("zoom-out' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.setHfov(panoshow' . $id . '.getHfov() + 10);';
            $html .= '});';
        }

        if ($custom_control['panFullscreenSwitch'] == "on") {
            $html .= 'document.getElementById("fullscreen' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.toggleFullscreen();';
            $html .= '});';
        }

        if ($custom_control['backToHomeSwitch'] == "on") {
            $html .= 'document.getElementById("backToHome' . $id . '").addEventListener("click", function(e) {';
            $html .= 'panoshow' . $id . '.loadScene("' . $default_scene . '");';
            $html .= '});';
        }

        if ($custom_control['gyroscopeSwitch'] == "on") {
            $html .= '
            var element = document.getElementById("gyroscope' . $id . '");
            var gyroSwitch = true;
            panoshow' . $id . '.on("load", function (){
                if(gyroSwitch == false) {
                    panoshow' . $id . '.stopOrientation();
                    element.children[0].style.color = "red";
                }
                else {
                    panoshow' . $id . '.startOrientation();
                    element.children[0].style.color = "'.$custom_control['gyroscopeColor'].'";
                }
            });
            panoshow' . $id . '.on("scenechange", function (){
                if (panoshow' . $id . '.isOrientationActive()) {
                    element.children[0].style.color = "'.$custom_control['gyroscopeColor'].'";
                }
                else {
                    element.children[0].style.color = "red";
                }
            });

            panoshow' . $id . '.on("touchstart", function (){
            if (panoshow' . $id . '.isOrientationActive()) {
                gyroSwitch = true;
                element.children[0].style.color = "'.$custom_control['gyroscopeColor'].'";
            }
            else {
                gyroSwitch = false;
                element.children[0].style.color = "red";
            }
            });
            ';
            $html .= 'document.getElementById("gyroscope' . $id . '").addEventListener("click", function(e) {';
            $html .= '
                var element = document.getElementById("gyroscope' . $id . '");
                if (panoshow' . $id . '.isOrientationActive()) {
                    
                  panoshow' . $id . '.stopOrientation();
                  gyroSwitch = false;
                  element.children[0].style.color = "red";
                }
                else {
                  panoshow' . $id . '.startOrientation();
                  gyroSwitch = true;
                  element.children[0].style.color = "'.$custom_control['gyroscopeColor'].'";
                }

              ';
            $html .= '});';
        }
    }

    $angle_up = '<i class="fa fa-angle-up"></i>';
    $angle_down = '<i class="fa fa-angle-down"></i>';
    $sin_qout = "'";

    //===Explainer Script===//
    $html .= '
    jQuery(document).on("click","#explainer_button_' . $id . '",function() {
        jQuery("#explainer' . $id . '").slideToggle();
        // jQuery(".explainer").slideToggle();
      });

      jQuery(document).on("click",".close-explainer-video",function() {
        jQuery(this).parent(".explainer").hide();
      });
      jQuery(document).on("click","#pano' . $id . '",function(event) {
        var isActiveModal = event.target.closest(".custom-ifram-wrapper");
        var isForm = event.target.closest(".wpvr-hotspot-tweak-contents");
        if( isActiveModal == null && isForm == null){
             jQuery(".custom-ifram-wrapper").hide();
             jQuery(this).removeClass("show-modal");
             jQuery(".wpvr-hotspot-tweak-contents-wrapper").hide("show-modal");
        }else if(isForm != null){
            jQuery(this).addClass("show-modal");
        }
      });

    ';
    //===Explainer Script End===//

    //===generic form script===//
    if (isset($postdata["genericform"]) && $postdata["genericform"] == 'on') {
        $html .= '
        jQuery(document).on("click","#generic_form_button_' . $id . '",function() {
          jQuery("#wpvr-generic-form' . $id . '").fadeToggle();
        });
  
        jQuery(document).on("click",".close-generic-form",function() {
          jQuery(this).parent(".wpvr-generic-form").fadeOut()
        });
        ';
    }
    //===generic from script===//

    //===Floor map  Script===//
    $html .= '
    jQuery(document).on("click","#floor_map_button_' . $id . '",function() {
        jQuery("#wpvr-floor-map' . $id . '").toggle().removeClass("fullwindow");
      });

      jQuery(document).on("dblclick","#wpvr-floor-map' . $id . '",function(){
        jQuery(this).addClass("fullwindow");
        jQuery(this).parents(".pano-wrap").addClass("show-modal");
      });
      
      jQuery(document).on("click",".close-floor-map-plan",function() {
        jQuery(this).parent(".wpvr-floor-map").hide();
        jQuery(this).parent(".wpvr-floor-map").removeClass("fullwindow");
        jQuery(this).parents(".pano-wrap").removeClass("show-modal");
      });

    ';
    //===Floor map Script End===//

    if ($vrgallery_display) {
        if (!$autoload) {
            $html .= '
          jQuery(document).ready(function($){
              jQuery("#sccontrols' . $id . '").hide();
              jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_up . $sin_qout . ');
              jQuery("#sccontrols' . $id . '").hide();
              jQuery(".wpvr_slider_nav").hide();
          });
          ';

            $html .= '
            var slide' . $id . ' = "down";
            jQuery(document).on("click","#vrgcontrols' . $id . '",function() {

              if (slide' . $id . ' == "up") {
                jQuery(".vrgctrl' . $id . '").empty();
                jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_up . $sin_qout . ');
                slide' . $id . ' = "down";
              }
              else {
                jQuery(".vrgctrl' . $id . '").empty();
                jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_down . $sin_qout . ');
                slide' . $id . ' = "up";
              }
              jQuery(".wpvr_slider_nav").slideToggle();
              jQuery("#sccontrols' . $id . '").slideToggle();
            });
            ';
        } else {
            $html .= '
          jQuery(document).ready(function($){
            jQuery("#sccontrols' . $id . '").show();
            jQuery(".wpvr_slider_nav").show();
              jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_down . $sin_qout . ');
          });
          ';

            $html .= '
          var slide' . $id . ' = "down";
          jQuery(document).on("click","#vrgcontrols' . $id . '",function() {

            if (slide' . $id . ' == "up") {
              jQuery(".vrgctrl' . $id . '").empty();
              jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_down . $sin_qout . ');
              slide' . $id . ' = "down";
            }
            else {
              jQuery(".vrgctrl' . $id . '").empty();
              jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_up . $sin_qout . ');
              slide' . $id . ' = "up";
            }
            jQuery(".wpvr_slider_nav").slideToggle();
            jQuery("#sccontrols' . $id . '").slideToggle();
          });
          ';
        }
    } else {
        $html .= '
          jQuery(document).ready(function($){
            jQuery("#sccontrols' . $id . '").hide();
            jQuery(".wpvr_slider_nav").hide();
              jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_up . $sin_qout . ');
          });
          ';

        $html .= '
          var slide' . $id . ' = "down";
          jQuery(document).on("click","#vrgcontrols' . $id . '",function() {

            if (slide' . $id . ' == "up") {
              jQuery(".vrgctrl' . $id . '").empty();
              jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_up . $sin_qout . ');
              slide' . $id . ' = "down";
            }
            else {
              jQuery(".vrgctrl' . $id . '").empty();
              jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_down . $sin_qout . ');
              slide' . $id . ' = "up";
            }
            jQuery(".wpvr_slider_nav").slideToggle();
            jQuery("#sccontrols' . $id . '").slideToggle();
          });
          ';
    }

    if (!$autoload) {
        $html .= '

          jQuery(document).ready(function(){
                jQuery("#controls' . $id . '").hide();
                jQuery("#zoom-in-out-controls' . $id . '").hide();
                jQuery("#adcontrol' . $id . '").hide();
                jQuery("#explainer_button_' . $id . '").hide();
                jQuery("#generic_form_button_' . $id . '").hide();
                jQuery("#floor_map_button_' . $id . '").hide();
                jQuery("#vrgcontrols' . $id . '").hide();
                jQuery("#pano' . $id . '").find(".pnlm-panorama-info").hide();
          });

          ';

        if ($vrgallery_display) {
            $html .= 'var load_once = "true";';
            $html .= 'panoshow' . $id . '.on("load", function (){
                    if (load_once == "true") {
                      load_once = "false";
                      jQuery("#sccontrols' . $id . '").slideToggle();
                    }
            });';
        }

        $html .= 'panoshow' . $id . '.on("load", function (){
              jQuery("#controls' . $id . '").show();
              jQuery("#zoom-in-out-controls' . $id . '").show();
              jQuery("#adcontrol' . $id . '").show();
              jQuery("#explainer_button_' . $id . '").show();
              jQuery("#generic_form_button_' . $id . '").show();
              jQuery("#floor_map_button_' . $id . '").show();
              jQuery("#vrgcontrols' . $id . '").show();
              jQuery("#pano' . $id . '").find(".pnlm-panorama-info").show();
          });';
    }


    if (isset($postdata['previewtext'])) {
        $html .= '
        jQuery("#pano' . $id . '").children(".pnlm-ui").find(".pnlm-load-button p").text("' . $postdata['previewtext'] . '")
        ';
    } else {
        $html .= '
        jQuery("#pano' . $id . '").children(".pnlm-ui").find(".pnlm-load-button p").text("Click To Load Panorama")
        ';
    }
    $html .= '});';

    $html .= '</script>';
    //script end
    return $html;
}


function wpvr_hex2rgb($colour)
{
    if (isset($colour[0]) && $colour[0] == '#') {
        $colour = substr($colour, 1);
    }
    if (strlen($colour) == 6) {
        list($r, $g, $b) = array($colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5]);
    } elseif (strlen($colour) == 3) {
        list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
    } else {
        return false;
    }
    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    return array($r . ', ' . $g . ', ' . $b);
}

function wpvr_HTMLToRGB($htmlCode)
{
    $r = 0;
    $g = 0;
    $b = 0;
    if (isset($htmlCode[0]) && $htmlCode[0] == '#') {
        $htmlCode = substr($htmlCode, 1);
    }

    if (strlen($htmlCode) == 3) {
        $htmlCode = $htmlCode[0] . $htmlCode[0] . $htmlCode[1] . $htmlCode[1] . $htmlCode[2] . $htmlCode[2];
    }

    if (isset($htmlCode[0]) && isset($htmlCode[1])) {
        $r = hexdec($htmlCode[0] . $htmlCode[1]);
    }
    if (isset($htmlCode[2]) && isset($htmlCode[3])) {
        $g = hexdec($htmlCode[2] . $htmlCode[3]);
    }
    if (isset($htmlCode[4]) && isset($htmlCode[5])) {
        $b = hexdec($htmlCode[4] . $htmlCode[5]);
    }

    return $b + ($g << 0x8) + ($r << 0x10);
}

function wpvr_RGBToHSL($RGB)
{
    $r = 0xFF & ($RGB >> 0x10);
    $g = 0xFF & ($RGB >> 0x8);
    $b = 0xFF & $RGB;

    $r = ((float)$r) / 255.0;
    $g = ((float)$g) / 255.0;
    $b = ((float)$b) / 255.0;

    $maxC = max($r, $g, $b);
    $minC = min($r, $g, $b);

    $l = ($maxC + $minC) / 2.0;

    if ($maxC == $minC) {
        $s = 0;
        $h = 0;
    } else {
        if ($l < .5) {
            $s = ($maxC - $minC) / ($maxC + $minC);
        } else {
            $s = ($maxC - $minC) / (2.0 - $maxC - $minC);
        }
        if ($r == $maxC) {
            $h = ($g - $b) / ($maxC - $minC);
        }
        if ($g == $maxC) {
            $h = 2.0 + ($b - $r) / ($maxC - $minC);
        }
        if ($b == $maxC) {
            $h = 4.0 + ($r - $g) / ($maxC - $minC);
        }

        $h = $h / 6.0;
    }

    $h = (int)round(255.0 * $h);
    $s = (int)round(255.0 * $s);
    $l = (int)round(255.0 * $l);

    return (object) array('hue' => $h, 'saturation' => $s, 'lightness' => $l);
}

add_action('rest_api_init', 'wpvr_rest_data_route');
function wpvr_rest_data_route()
{
    register_rest_route('wpvr/v1', '/panodata/', array(
        'methods' => 'GET',
        'callback' => 'wpvr_rest_data_set',
        'permission_callback' => 'wpvr_rest_route_permission'
    ));
}

function wpvr_rest_route_permission()
{
    return true;
}

function wpvr_rest_data_set()
{
    $query = new WP_Query(array(
        'post_type' => 'wpvr_item',
        'posts_per_page' => -1,
    ));

    $wpvr_list = array();
    $list_none = array('value' => 0, 'label' => 'None');
    array_push($wpvr_list, $list_none);
    while ($query->have_posts()) {
        $query->the_post();
        $title = mb_convert_encoding(get_the_title(), 'UTF-8', 'HTML-ENTITIES');
        $post_id = get_the_ID();
        $title = $post_id . ' : ' . $title;
        $list_ob = array('value' => $post_id, 'label' => $title);
        array_push($wpvr_list, $list_ob);
    }

    return $wpvr_list;
}

function wpvr_isMobileDevice()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function wpvr_directory()
{
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir_temp = $upload_dir . '/wpvr/temp/';
    if (!is_dir($upload_dir_temp)) {
        wp_mkdir_p($upload_dir_temp, 0700);
    }
}

add_action('admin_init', 'wpvr_directory');


function wpvr_add_role_cap()
{
    $editor_active = get_option('wpvr_editor_active');

    $author_active = get_option('wpvr_author_active');

    $admin = get_role('administrator');
    $admin->add_cap('publish_wpvr_tour');
    $admin->add_cap('edit_wpvr_tours');
    $admin->add_cap('read_wpvr_tour');
    $admin->add_cap('edit_wpvr_tour');
    $admin->add_cap('edit_wpvr_tours');
    $admin->add_cap('publish_wpvr_tours');
    $admin->add_cap('publish_wpvr_tour');
    $admin->add_cap('delete_wpvr_tour');
    $admin->add_cap('edit_other_wpvr_tours');
    $admin->add_cap('delete_other_wpvr_tours');

    if ($editor_active == "true") {
        $editor = get_role('editor');
        if ($editor) {
            $editor->add_cap('publish_wpvr_tour');
            $editor->add_cap('edit_wpvr_tours');
            $editor->add_cap('read_wpvr_tour');
            $editor->add_cap('edit_wpvr_tour');
            $editor->add_cap('edit_wpvr_tours');
            $editor->add_cap('publish_wpvr_tours');
            $editor->add_cap('publish_wpvr_tour');
            $editor->add_cap('delete_wpvr_tour');
            $editor->add_cap('edit_other_wpvr_tours');
            $editor->add_cap('delete_other_wpvr_tours');
        }
    } else {
        $editor = get_role('editor');
        if ($editor) {
            $editor->remove_cap('publish_wpvr_tour');
            $editor->remove_cap('edit_wpvr_tours');
            $editor->remove_cap('read_wpvr_tour');
            $editor->remove_cap('edit_wpvr_tour');
            $editor->remove_cap('edit_wpvr_tours');
            $editor->remove_cap('publish_wpvr_tours');
            $editor->remove_cap('publish_wpvr_tour');
            $editor->remove_cap('delete_wpvr_tour');
            $editor->remove_cap('edit_other_wpvr_tours');
            $editor->remove_cap('delete_other_wpvr_tours');
        }
    }

    if ($author_active == "true") {
        $author = get_role('author');
        if ($author) {
            $author->add_cap('read_wpvr_tour');
            $author->add_cap('edit_wpvr_tour');
            $author->add_cap('edit_wpvr_tours');
            $author->add_cap('publish_wpvr_tours');
            $author->add_cap('publish_wpvr_tour');
            $author->add_cap('delete_wpvr_tour');
        }
    } else {
        $author = get_role('author');
        if ($author) {
            $author->remove_cap('read_wpvr_tour');
            $author->remove_cap('edit_wpvr_tour');
            $author->remove_cap('edit_wpvr_tours');
            $author->remove_cap('publish_wpvr_tours');
            $author->remove_cap('publish_wpvr_tour');
            $author->remove_cap('delete_wpvr_tour');
        }
    }
}

add_action('admin_init', 'wpvr_add_role_cap', 999);

function wpvr_role_management_from_post_type($args, $post_type)
{
    if ('wpvr_item' !== $post_type) {
        return $args;
    }

    $editor_active = get_option('wpvr_editor_active');
    $author_active = get_option('wpvr_author_active');
    $user = wp_get_current_user();

    if ($editor_active == "true") {
        if (in_array('editor', (array) $user->roles)) {
            $args['show_in_menu'] = true;
        }
    }

    if ($author_active == "true") {
        if (in_array('author', (array) $user->roles)) {
            $args['show_in_menu'] = true;
        }
    }

    return $args;
}
add_filter('register_post_type_args', 'wpvr_role_management_from_post_type', 10, 2);

function wpvr_cache_admin_notice()
{
    $option = get_option('wpvr_warning');
    if (!$option) {
        ?>
        <div class="notice notice-warning" id="wpvr-warning" style="position: relative;">
            <p><?php _e('Since you have updated the plugin, please clear the browser cache for smooth functioning. Follow these steps if you are using <a href="https://support.google.com/accounts/answer/32050?co=GENIE.Platform%3DDesktop&hl=en" target="_blank">Google Chrome</a>, <a href="https://support.mozilla.org/en-US/kb/how-clear-firefox-cache" target="_blank">Mozilla Firefox</a>, <a href="https://clear-my-cache.com/en/apple-mac-os/safari.html" target="_blank">Safai</a> or <a href="https://support.microsoft.com/en-us/help/10607/microsoft-edge-view-delete-browser-history" target="_blank">Microsoft Edge</a>', 'wpvr'); ?></p>
            <button type="button" id="wpvr-dismissible" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
        <?php
    }
}
// add_action('admin_notices', 'wpvr_cache_admin_notice');

//===Oxygen widget===//
add_action('plugins_loaded', function () {
    if (!class_exists('OxyEl')) {
        return;
    }
    require_once __DIR__ . '/oxygen/oxy-manager.php';
});

add_action('init', 'wpvr_mobile_media_handle');
function wpvr_mobile_media_handle()
{
    add_image_size('wpvr_mobile', 4096, 2048); //mobile
}


add_action(
/**
 * @param $api \VisualComposer\Modules\Api\Factory
 */
    'vcv:api',
    function ($api) {
        $elementsToRegister = [
            'wpvrelement',
        ];
        $pluginBaseUrl = rtrim(plugins_url(basename(__DIR__)), '\\/');
        /** @var \VisualComposer\Modules\Elements\ApiController $elementsApi */
        $elementsApi = $api->elements;
        foreach ($elementsToRegister as $tag) {
            $manifestPath = __DIR__ . '/vc/' . $tag . '/manifest.json';
            $elementBaseUrl = $pluginBaseUrl . '/vc/' . $tag;
            $elementsApi->add($manifestPath, $elementBaseUrl);
        }
    }
);

function wpvr_redirect_after_activation($plugin)
{
    if ($plugin == plugin_basename(__FILE__)) {
        $url = admin_url('admin.php?page=wpvr-setup-wizard');
        $url = esc_url($url, FILTER_SANITIZE_URL);
        exit(wp_safe_redirect($url));
    }
}
add_action('activated_plugin', 'wpvr_redirect_after_activation');

function replace_callback($matches){
    foreach ($matches as $match){
        return str_replace('<img','<img decoding="async" ',$match);
    }

}


/**
 * Add promoition notice flag in option table
 *
 * @return void
 */
function wpvr_add_promotional_banner_flag(){
    if( !get_option( '_is_wpvr_promotion' ) ){
        update_option( '_is_wpvr_promotion', 'yes' );
    }
}
add_action( 'admin_init', 'wpvr_add_promotional_banner_flag' );