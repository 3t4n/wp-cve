<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing Video tab content on Setup metabox
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Video {

    /**
     * Instance of WPVR_Format class
     */
    protected $format;

    /**
     * Instance of WPVR_Validation class
     */
    protected $validator;


    function __construct()
    {
        $this->format = new WPVR_Format();

        $this->validator = new WPVR_Validator();
    }


    /**
     * Render Video Tab Content
     * @param mixed $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    public function render_video($postdata)
    {
        ob_start();
        ?>
        
        <h6 class="title"><?php echo __('Video Settings : ', 'wpvr'); ?></h6>
        
        <?php 
        WPVR_Meta_Field::render_video_setting_meta_fields($postdata);
        ob_end_flush();
    }


    /**
     * Update post meta data
     * 
     * @param integer $postid
     * @param integer $panoid
     * 
     * @return void
     * @since 8.0.0
     */
    public function wpvr_update_meta_box($postid, $panoid)
    {
        $vidid = 'vid' . $postid;
        $videourl = esc_url_raw($_POST['videourl']);
        
        $videodata = $this->format->prepare_video_settings_data();
        $vidtype = '';

        $this->validator->empty_video_validation($videourl);

        if (strpos($videourl, 'youtube') > 0) {
            $vidtype = 'youtube';
            $html = $this->format->prepare_youtube_video_meta_data($videourl, $videodata);
        } elseif (strpos($videourl, 'youtu.be') > 0) {
            $vidtype = 'youtube';
            $html = $this->format->prepare_youtu_be_video_meta_data($videourl, $videodata);
        } elseif (strpos($videourl, 'vimeo') > 0) {
            $vidtype = 'vimeo';
            $html = $this->format->prepare_vimeo_video_meta_data($videourl, $videodata);
        } else {
            $vidtype = 'selfhost';
            $html = $this->format->prepare_selfhost_video_meta_data($videourl, $vidid, $videodata);
        }

        $videoarray = array();
        $videoarray = array(
                            __("panoid") => $panoid, 
                            __("panoviddata") => $html, 
                            __("vidid") => $vidid, 
                            __("vidurl") => $videourl, 
                            __("vidtype") => $vidtype,
                            __("autoplay") => $videodata['autoplay'], 
                            __("loop") => $videodata['loop']
                        );
        update_post_meta($postid, 'panodata', $videoarray);
        $response = array(
            'success'   => true,
            'data'  => array(
                'post_ID' => $postid,
                'post_status' => get_post_status($postid)
            )
        );
        wp_send_json($response);
        die();
    }


    /**
     * Video Tour Preview
     * 
     * @param mixed $panoid
     * 
     * @return wp_send_json_response
     * @since 8.0.0
     */
    public function wpvr_video_preview($panoid)
    {
        $randid = rand(1000, 1000000);
        $vidid = 'vid' . $randid;
        $videourl = esc_url_raw($_POST['videourl']);
        $videodata = $this->format->prepare_video_settings_data();
        $vidtype = '';

        $this->validator->empty_video_validation($videourl);

        if (strpos($videourl, 'youtube') > 0) {
            $vidtype = 'youtube';
            $html = $this->format->prepare_youtube_video_preview($videourl, $videodata);
        } elseif (strpos($videourl, 'youtu.be') > 0) {
            $vidtype = 'youtube';
            $html = $this->format->prepare_youtu_be_video_meta_data($videourl, $videodata);
        } elseif (strpos($videourl, 'vimeo') > 0) {
            $vidtype = 'vimeo';
            $html = $this->format->prepare_vimeo_video_meta_data($videourl, $videodata);
        } else {
            $vidtype = 'selfhost';
            $html = $this->format->prepare_selfhost_video_meta_data($videourl, $vidid, $videodata);
        }

        $response = array();
        $response = array(__("panoid") => $panoid, __("panodata") => $html, __("vidid") => $vidid, __("vidtype") => $vidtype);
        wp_send_json_success($response);
    }


    /**
     * Render shortcode while postdata has video data 
     * 
     * @param array $postdata
     * @param integer $id
     * 
     * @return string
     * @since 8.0.0
     */
    public function render_video_shortcode($postdata, $id, $width, $height, $radius)
    {
        if (empty($width)) {
            $width = '600px';
        }
        if (empty($height)) {
            $height = '400px';
        }

        $autoplay = 'off';
        if (isset($postdata['autoplay'])) {
            $autoplay = $postdata['autoplay'];
        }

        $loop = 'off';
        if (isset($postdata['loop'])) {
            $loop = $postdata['loop'];
        }

        if (strpos($postdata['vidurl'], 'youtube') > 0 || strpos($postdata['vidurl'], 'youtu') > 0) {
            $html = $this->format->preapre_youtube_video_shortcode_data($postdata, $width, $height, $autoplay, $loop, $radius);
        } elseif (strpos($postdata['vidurl'], 'vimeo') > 0) {
            $html = $this->format->prepare_vimeo_video_shortcode_data($postdata, $width, $height, $autoplay, $loop, $radius);
        } else {
            $html = $this->format->prepare_regular_video_shortcode_data($id, $postdata, $width, $height, $radius);
        }
        return $html;
    }

}