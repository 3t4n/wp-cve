<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * The admin-specific Ajax files.
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin
 */

class Wpvr_Ajax {

    /**
     * Instance of WPVR_Format class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $format;


    /**
     * Instance of WPVR_StreetView class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $streetview;


    /**
     * Instance of WPVR_Video class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $video;


    /**
     * Instance of WPVR_Scene class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $scene;


    /**
     * Instance of WPVR_Validator class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $validator;


    function __construct()
    {
        $this->format     = new WPVR_Format();
        $this->streetview = new WPVR_StreetView();
        $this->video      = new WPVR_Video();
        $this->scene      = new WPVR_Scene();
        $this->validator  = new WPVR_Validator();
        
        add_action( 'wp_ajax_wpvr_save',              array($this, 'wpvr_save_data')  );
        add_action( 'wp_ajax_wpvr_preview',           array($this, 'wpvr_show_preview')  );
        add_action( 'wp_ajax_wpvrstreetview_preview', array($this, 'wpvrstreetview_preview') );
        add_action( 'wp_ajax_wpvr_file_import',       array($this, 'wpvr_file_import')  );
        add_action( 'wp_ajax_wpvr_role_management',   array($this, 'wpvr_role_management')  );
        add_action( 'wp_ajax_wpvr_notice',            array($this, 'wpvr_notice')  );
        add_action( 'wp_ajax_wpvr_halloween_offer_notice_dismiss',  array($this, 'wpvr_halloween_offer_notice_dismiss')  );
        add_action( 'wp_ajax_wpvr_dismiss_black_friday_notice',  array($this, 'dismiss_black_friday_notice')  );

    }

  

    /**
     * Responsible for Tour Preview
     * 
     * @return void
     * @since 8.0.0
     */
    public function wpvr_show_preview()
    {
      //===Current user capabilities check===//
      if ( !current_user_can( 'edit_posts' ) ) {
        $response = array(
          'success'   => false,
          'data'  => 'Contact admin.'
        );
        wp_send_json($response);
      }
      //===Current user capabilities check===//
      //===Nonce check===//
        $nonce  = sanitize_text_field($_POST['nonce']);
        if ( !wp_verify_nonce($nonce, 'wpvr') ){
          $response = array(
              'success'   => false,
              'data'  => 'Permission denied.'
          );
          wp_send_json($response);
      }
      //===Nonce check===//

        $panoid = '';
        $postid = sanitize_text_field($_POST['postid']);
        $panoid = 'pano' . $postid;
        if(isset($_POST['panovideo'])){
          $panovideo = sanitize_text_field($_POST['panovideo']);
        }

        $post_type = get_post_type($postid);
        if ($post_type != 'wpvr_item') {
          die();
        }

        do_action( 'wpvr_pro_street_view_preview', $postid, $panoid );

        if($panovideo == 'off') {
          $this->scene->wpvr_scene_preview($panoid, $panovideo);     // Preapre preview based on Scene data //
        } else {              
          $this->video->wpvr_video_preview($panoid);                 // Prepare preview based on Video data //
        }
    }


    /**
     * Responsible for saving WPVR data
     * 
     * @return void
     * @since 8.0.0
     */
    public function wpvr_save_data()
    {

        //===Current user capabilities check===//
        if ( !current_user_can( 'edit_posts' ) ) {
          $response = array(
            'success'   => false,
            'data'  => 'Permission denied.'
          );
          wp_send_json($response);
        }
        //===Current user capabilities check===//
        //===Nonce check===//
          $nonce  = sanitize_text_field($_POST['nonce']);
          if ( !wp_verify_nonce($nonce, 'wpvr') ){
            $response = array(
                'success'   => false,
                'data'  => 'Permission denied.'
            );
            wp_send_json($response);
        }
        //===Nonce check===//
        $panoid = '';
        $postid = sanitize_text_field($_POST['postid']);
        $post_type = get_post_type($postid);
        if ($post_type != 'wpvr_item') {
            die();
        }
        $panoid = 'pano' . $postid;

        $post_status = get_post_status($postid);
        if($post_status != 'publish') {
          wp_update_post(array(
              'ID' => $postid,
              'post_status' => 'publish'
          ));
        }

        $post_array = [];
        if( isset($_POST['post_status'])){
            $post_status = sanitize_text_field($_POST['post_status']);
            $post_array['post_status'] = $post_status;
        }if( isset($_POST['post_password'])){
            $visibility = sanitize_text_field($_POST['post_password']);
            $post_array['post_password'] = $visibility;
        }if( isset($_POST['visibility'])){
            $visibility = sanitize_text_field($_POST['visibility']);
            $post_array['visibility'] = $visibility;
            if($visibility == 'public' || $visibility == 'private' ){
                $post_array['post_password'] = '';
            }
        }
        if(isset($_POST['post_value'])){
            $post_value = sanitize_text_field($_POST['post_value']);
            if( $post_array['visibility'] == 'private' ){
                $post_array['post_status'] = 'private';
            }elseif($post_value === 'Publish'){
                $post_array['post_status'] = 'publish';
            }
        }
        $post_title = sanitize_text_field($_POST['post_title']);
        wp_update_post(array(
            'ID' => $postid,
            'post_status' => $post_array['post_status'],
            'post_password' => $post_array['post_password'],
            'visibility' => $post_array['visibility'],
            'post_title' => $post_title,

        ));

        do_action( 'wpvr_pro_update_street_view', $postid, $panoid );

        if ($_POST['panovideo'] == "on") {
            $this->video->wpvr_update_meta_box($postid, $panoid);
        } else {
            $this->scene->wpvr_update_meta_box($postid, $panoid);
        }


    }


    /**
     * Responsible for importing tour
     * 
     * @return void
     * @since 8.0.0
     */
    public function wpvr_file_import()  
    {
        //===Current user capabilities check===//
        if ( !current_user_can( 'edit_posts' ) ) {
          $response = array(
            'success'   => false,
            'data'  => 'Permission denied.'
          );
          wp_send_json($response);
        }
        //===Current user capabilities check===//
      //===Nonce check===//
        $nonce  = sanitize_text_field($_POST['nonce']);
        if ( !wp_verify_nonce($nonce, 'wpvr') ){
          $response = array(
              'success'   => false,
              'data'  => 'Permission denied.'
          );
          wp_send_json($response);
      }
      //===Nonce check===//
      WPVR_Import::prepare_tour_import_feature();
    }
    

    
    /**
     * WPVR Role Management
     * 
     * @return void
     * @since 8.0.0
     */
    function wpvr_role_management()
    {

        //===Current user capabilities check===//
        if ( !current_user_can( 'edit_posts' ) ) {
          $response = array(
            'success'   => false,
            'data'  => 'Permission denied.'
          );
          wp_send_json($response);
        }
        //===Current user capabilities check===//
        //===Nonce check===//
          $nonce  = sanitize_text_field($_POST['nonce']);
          if ( !wp_verify_nonce($nonce, 'wpvr') ){
            $response = array(
                'success'   => false,
                'data'  => 'Permission denied.'
            );
            wp_send_json($response);
        }
        //===Nonce check===//

        $editor = sanitize_text_field($_POST['editor']);
        $author = sanitize_text_field($_POST['author']);
        $fontawesome = sanitize_text_field($_POST['fontawesome']);
        $cardboard = sanitize_text_field($_POST['wpvr_cardboard_disable']);
        $wpvr_webp_conversion = sanitize_text_field($_POST['wpvr_webp_conversion']);
        $mobile_media_resize = sanitize_text_field($_POST['mobile_media_resize']);
        $high_res_image = sanitize_text_field($_POST['high_res_image']);
        $dis_on_hover = sanitize_text_field($_POST['dis_on_hover']);
        $wpvr_frontend_notice = sanitize_text_field($_POST['wpvr_frontend_notice']);
        $wpvr_frontend_notice_area = sanitize_text_field($_POST['wpvr_frontend_notice_area']);
        $wpvr_script_control = sanitize_text_field($_POST['wpvr_script_control']);
        $wpvr_script_list = sanitize_text_field($_POST['wpvr_script_list']);

        $wpvr_video_script_control = sanitize_text_field($_POST['wpvr_video_script_control']);
        $wpvr_video_script_list = sanitize_text_field($_POST['wpvr_video_script_list']);

        //        $enable_woocommerce = sanitize_text_field($_POST['woocommerce']);

        $wpvr_script_list = str_replace(' ', '', $wpvr_script_list);

        update_option('wpvr_editor_active', $editor);
        update_option('wpvr_author_active', $author);
        update_option('wpvr_fontawesome_disable', $fontawesome);
        update_option('wpvr_cardboard_disable', $cardboard);
        update_option('wpvr_webp_conversion', $wpvr_webp_conversion);
        update_option('mobile_media_resize', $mobile_media_resize);
        update_option('high_res_image', $high_res_image);
        update_option('dis_on_hover', $dis_on_hover);
        update_option('wpvr_frontend_notice', $wpvr_frontend_notice);
        update_option('wpvr_frontend_notice_area', $wpvr_frontend_notice_area);
        update_option('wpvr_script_control', $wpvr_script_control);
        update_option('wpvr_script_list', $wpvr_script_list);

        update_option('wpvr_video_script_control', $wpvr_video_script_control);
        update_option('wpvr_video_script_list', $wpvr_video_script_list);

        //        update_option('wpvr_enable_woocommerce', $enable_woocommerce);

        $response = array(
          'status' => 'success',
          'message' => 'Successfully saved',
        );
        wp_send_json($response);
    }

    
    /**
     * WPVR Notice
     * 
     * @return void
     * @since 8.0.0
     */
    function wpvr_notice()
    {
        //===Current user capabilities check===//
        if ( !current_user_can( 'edit_posts' ) ) {
          $response = array(
            'success'   => false,
            'data'  => 'Permission denied.'
          );
          wp_send_json($response);
        }
        //===Current user capabilities check===//
      //===Nonce check===//
        $nonce  = sanitize_text_field($_POST['nonce']);
        if ( !wp_verify_nonce($nonce, 'wpvr') ){
          $response = array(
              'success'   => false,
              'data'  => 'Permission denied.'
          );
          wp_send_json($response);
      }
      //===Nonce check===//
      update_option('wpvr_black_friday_notice', '1');
    }

    /**
     * WPVR Halloween Notice
     * 
     * @return void
     */
    function wpvr_halloween_offer_notice_dismiss() {
        update_option( '_is_wpvr_promotion', 'no' );
        wp_send_json([
            'success' => true,
        ]);
    }


    /**
     * Dismiss black friday notice
     */
    function dismiss_black_friday_notice() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wpvr')) {
            wp_die(__( 'Permission check failed', 'wpvr' ));
        }
        update_option( '_wpvr_christmas_23', 'yes' );
        echo json_encode(['success' => true,]);
        wp_die();
    }
}
