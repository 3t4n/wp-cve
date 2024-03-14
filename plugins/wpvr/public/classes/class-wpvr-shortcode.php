<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing Shortcode on frontend
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/public/classes
 */

class WPVR_Shortcode {

    /**
	 * The ID of this plugin.
	 *
	 * @since    8.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

    /**
     * Instance of WPVR_StreetView class
     * 
     * @var object
     * @since 8.0.0
     */
    private $streetview;

    /**
     * Instance of WPVR_Video class
     * 
     * @var object
     * @since 8.0.0
     */
    private $video;

    /**
     * Instance of WPVR_Scene class
     * 
     * @var object
     * @since 8.0.0
     */
    private $scene;

    function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
        $this->streetview = new WPVR_StreetView();
        $this->video = new WPVR_Video();
        $this->scene = new WPVR_Scene();
    }

    /**
     * Shortcode output for the plugin
     * 
     * @param array $atts
     *
     * @return array
     * @since 8.0.0
     */
    public function wpvr_shortcode($atts)
    {

        extract(
            shortcode_atts(
                array(
                    'id' => 0,
                    'width' => null,
                    'height' => null,
                    'mobile_height' => null,
                    'radius' => null
                ),
                $atts
            )
        );
        $id = esc_attr($id);
        $width = esc_attr($width);
        $height = esc_attr($height);
        $mobile_height = esc_attr($mobile_height);
        $radius = esc_attr($radius);
        if (!$id) {
            $obj = get_page_by_path($slug, OBJECT, $this->post_type);
            if ($obj) {
                $id = $obj->ID;
            } else {
                return __('Invalid Wpvr slug attribute', $this->plugin_name);
            }
        }

        if (empty($mobile_height)) {
            $mobile_height = "300px";
        }
        $get_post = get_post_status($id);
        if ( $get_post !== 'publish' ) {
            return esc_html__('Oops! It seems like this post isn\'t published yet. Stay tuned for updates!', 'wpvr') ;
        }
        if( post_password_required(  $id ) ){
            return get_the_password_form();
        }
        $postdata = get_post_meta($id, 'panodata', true);
        $panoid = 'pano'.$id;

        if (isset($postdata['streetviewdata'])){
            $html = $this->streetview->render_streetview_shortcode($postdata, $width, $height);
            return $html;
        }


        if (isset($postdata['vidid'])) {
            $html = $this->video->render_video_shortcode($postdata, $id, $width, $height, $radius);
            return $html;
        }

        $html = $this->scene->render_scene_shortcode($postdata, $panoid, $id, $radius, $width, $height, $mobile_height);
        return $html;
    }
}