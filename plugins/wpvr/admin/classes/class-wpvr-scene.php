<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing Scene tab on Setup meta box
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Scene {

    /**
     * Instance of WPVR_Hotspot class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $hotspot;

    /**
     * Instance of WPVR_Format class
     * 
     * @var object
     * @since 8.0.0
     */
    protected $format;

    /**
     * Instance of WPVR_Validator class
     * 
     * @var object
     * @since 8.0.0
     */
    private $validator;

    /**
     * Number of scene or hotspot item
     * 
     * @var integer
     * @since 8.0.0
     */
    protected $data_limit;

    /**
     * Pro version license status
     * 
     * @var string
     * @since 8.0.0
     */
    protected $status;
    

    function __construct()
    {
        $this->hotspot   = new WPVR_Hotspot();
        $this->format    = new WPVR_Format();
        $this->validator = new WPVR_Validator();

        $this->status = apply_filters( 'check_pro_license_status', $this->status );

        if ($this->status !== false && $this->status == 'valid') {
            $this->data_limit = 999999999;
        } else {
            $this->data_limit = 5;
        }
    }


    /**
     * Render Scene Settings Content
     * 
     * @param array $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    public function render_scene($postdata)
    {
      ob_start();
        ?>
        
          <!-- Scene and Hotspot repeater -->
          <div class="scene-setup rex-pano-sub-tabs" data-limit="<?= $this->data_limit + 1;?>">
            <?php $this->render_scene_repeater_list($postdata); ?>
          </div>

        <?php 
      ob_end_flush();
    }


    /**
     * Render scene setup data repeater list
     * 
     * @param array $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_scene_repeater_list($postdata)
    {
      ob_start();
      ?>
      <nav class="rex-pano-tab-nav rex-pano-nav-menu scene-nav">
        <?php $this->render_nav_menu($postdata); // Will render scene navigation bar ?> 
      </nav>
      
      <div data-repeater-list="scene-list" class="rex-pano-tab-content">

        <!-- Default empty repeater -->
        <div data-repeater-item class="single-scene rex-pano-tab" data-title="0" id="scene-0">
            <?php $this->render_default_repeater_item(); ?>
        </div>
        <!-- Empty repeater end -->

          <?php $s = 1; $firstvalue = reset($postdata['panodata']["scene-list"]);
          foreach ($postdata['panodata']["scene-list"] as $pano_scene) { ?>

          <div data-repeater-item  class="single-scene rex-pano-tab <?php if($pano_scene['scene-id'] == $firstvalue['scene-id']) { echo 'active'; }; ?>" data-title="1" id="scene-<?php echo $s;?>">
              <?php $this->render_repeater_item_with_panodata($pano_scene, $s); ?>
          </div>
      
          <?php $s++; } ?>
      </div>
      <?php 
      ob_end_flush();
    }


    /**
     * Render scene nav menu 
     * 
     * @param array $postdata
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_nav_menu($postdata)
    {
      ob_start();
      ?>
      <ul>
        <?php $i = 1; $firstvalue = reset($postdata['panodata']["scene-list"]);
        foreach ($postdata['panodata']["scene-list"] as $pano_scene) { ?>

          <li class="<?php if ($pano_scene['scene-id'] == $firstvalue['scene-id']) {echo 'active';};?>">
            <span data-index="<?php echo $i;?>" data-href="#scene-<?php echo $i;?>">
              <i class="fa fa-image"></i>
            </span>
          </li>

        <?php $i++; } ?>
        <li class="add" data-repeater-create><span><i class="fa fa-plus-circle"></i></span></li>
      </ul>
      <?php
      ob_end_flush();
    }


    /**
     * Render repeater item for default scene
     * 
     * @param int $data_limit
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_default_repeater_item()
    {
      ob_start();
      ?>
      <div class="active_scene_id"><p></p></div>
      <div class="scene-content">
          <?php $this->render_default_repeater_item_scene_content(); ?>
      </div>

      <!-- hotspot setup -->
      <div class="hotspot-setup rex-pano-sub-tabs" data-limit="<?= $this->data_limit;?>">
          <?php $this->hotspot->render_hotspot($s = 0, $h =1)?>
      </div>
      <button data-repeater-delete type="button" title="Delete Scene" class="delete-scene"><i class="far fa-trash-alt"></i></button>
      <?php
      ob_end_flush();
    }


    /**
     * Render repeater items while scene has panaromic data
     * 
     * @param array $pano_scene
     * @param int $s scene number increment var
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_repeater_item_with_panodata($pano_scene, $s)
    {
      ob_start();
      ?>
      <div class="active_scene_id"><p></p></div>
      <div class="scene-content">
          <!-- 
            - Render repeater item scene content 
            - If scene has panaromic data 
          -->
          <?php $this->render_repeater_scene_content_with_data($pano_scene); ?>
      </div>
      <!-- 
        - Render repeater item hotspot content 
      -->
      <?php $this->render_repeater_item_hotspot_content($pano_scene, $s); ?>
  
      <button data-repeater-delete type="button" title="Delete Scene" class="delete-scene"><i class="far fa-trash-alt"></i></button>
      <?php
      ob_end_flush();
    }


    /**
     * Render scene content for default repeater item
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_default_repeater_item_scene_content()
    {
      ob_start();
      ?>

      <h6 class="title"><i class="fa fa-cog"></i> <?php  echo __('Scene Setting','wpvr') ?> </h6>
      
        <div class="scene-left">
            <?php WPVR_Meta_Field::render_scene_left_fields_empty_panodata(); ?>
        </div>

        <div class="scene-right">
            <?php do_action( 'wpvr_pro_scene_empty_right_fields' ) ?>
        </div>

      <?php
      ob_end_flush();
    }


    /**
     * Render repeater item scene content is scene has panaromic data
     * 
     * @param mixed $dscene
     * @param mixed $scene_id
     * @param mixed $scene_photo
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_repeater_scene_content_with_data($pano_scene)
    {
      ob_start();
      ?>
      <h6 class="title"><i class="fa fa-cog"></i> <?php echo __('Scene Setting','wpvr') ?> </h6>

        <div class="scene-left">
            <?php WPVR_Meta_Field::render_scene_left_fields_with_panodata($pano_scene) ;?>
        </div>
        
        <div class="scene-right">
            <?php do_action( 'wpvr_pro_scene_right_fields', $pano_scene ) ?>
        </div>


      <?php
      ob_end_flush();
    }


    /**
     * Render repeater item hotspot content
     * 
     * @param array $pano_hotspots
     * @param int $data_limit
     * @param int $s
     * 
     * @return void
     * @since 8.0.0
     */
    private function render_repeater_item_hotspot_content($pano_scene, $s)
    {
      if (!empty($pano_scene['hotspot-list'])) { ?>
        <div class="hotspot-setup rex-pano-sub-tabs" data-limit="<?= $this->data_limit;?>">
  
            <?php $this->hotspot->render_hotspot_with_panodata($pano_scene['hotspot-list'], $s); //Render hotspot while scene has hotspot data ?> 
  
        </div>
        <?php } else { ?>
        <div class="hotspot-setup rex-pano-sub-tabs" data-limit="<?= $this->data_limit;?>">
  
            <?php $this->hotspot->render_hotspot($s, $h = 1); //Render hotspot while scene has no hotspot data ?>
  
        </div>
        <?php }
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
      $panodata      = $this->format->prepare_panodata($_POST['panodata']);
      $default_scene = $this->format->prepare_default_scene($panodata);
      $previewtext = $this->validator->preview_text_validation($_POST['previewtext']);

      $gzoom       = $this->format->set_pro_checkbox_value(@$_POST['gzoom']);
      $default_global_zoom = '';
      $max_global_zoom = '';
      $min_global_zoom = '';
      if ($gzoom == 'on') {
        $default_global_zoom = $_POST['dzoom'];
        $max_global_zoom = $_POST['maxzoom'];
        $min_global_zoom = $_POST['minzoom'];
      }

      $custom_control = isset($_POST['customcontrol']) ? $_POST['customcontrol'] : null;

      $vrgallery            = $this->format->set_checkbox_value(@$_POST['vrgallery']);
      $vrgallery_title      = $this->format->set_checkbox_value(@$_POST['vrgallery_title']);
      $vrgallery_display    = $this->format->set_checkbox_value(@$_POST['vrgallery_display']);
      $vrgallery_icon_size  = $this->format->set_checkbox_value(@$_POST['vrgallery_icon_size']);

      $mouseZoom    = $this->format->set_pro_checkbox_value(@$_POST['mouseZoom']);
      $draggable    = $this->format->set_pro_checkbox_value(@$_POST['draggable']);
      $diskeyboard  = $this->format->set_pro_checkbox_value(@$_POST['diskeyboard']);
      $keyboardzoom = $this->format->set_checkbox_value(@$_POST['keyboardzoom']);
      $compass      = $this->format->set_checkbox_on_value(@$_POST['compass']);
      //===Gyroscopre control===//
      $gyro = $this->format->set_pro_checkbox_value(@$_POST['gyro']);
      if ($gyro == 'on') {
        if (!is_ssl()) {
          wp_send_json_error('<p><span>Warning:</span> Please add SSL to enable Gyroscope for WP VR. </p>');
          die();
        }
        $gyro = true;
        $deviceorientationcontrol = $this->format->set_checkbox_value(@$_POST['deviceorientationcontrol']);
      } else {
        $gyro = false;
        $deviceorientationcontrol = false;
      }
      //===Gyroscopre control===//

      $autoload           = $this->format->set_checkbox_value($_POST['autoload']);
      $control            = $this->format->set_checkbox_value($_POST['control']);

      $scene_fade_duration = $_POST['scenefadeduration'];
      $preview = esc_url($_POST['preview']);
      $rotation = sanitize_text_field($_POST['rotation']);
      $autorotation = sanitize_text_field($_POST['autorotation']);

      $autorotationinactivedelay = sanitize_text_field($_POST['autorotationinactivedelay']);
      $autorotationstopdelay = sanitize_text_field($_POST['autorotationstopdelay']);

      //===generic form===//
      $genericform = sanitize_text_field($_POST['genericform']);
      $genericformshortcode = $_POST['genericformshortcode'];
      //===generic form===//
    
      $this->validator->basic_setting_validation($autorotationinactivedelay, $autorotationstopdelay);   // Basic setting error control and validation //

      //===Company Logo===//
      $cpLogoSwitch  = isset($_POST['cpLogoSwitch']) ? $_POST['cpLogoSwitch'] : 'off';
      $cpLogoImg     = isset($_POST['cpLogoImg']) ? $_POST['cpLogoImg'] : '';
      $cpLogoContent = isset($_POST['cpLogoContent']) ? sanitize_text_field($_POST['cpLogoContent']) : '';
      //===Company Logo===//

      //===Explainer video===//
      $explainerSwitch = isset($_POST['explainerSwitch']) ? $_POST['explainerSwitch'] : 'off';
      $explainerContent = '';
      $explainerContent = isset($_POST['explainerContent']) ? $_POST['explainerContent'] : '';
      //===Explainer video===//


      $scene_fade_duration = '';
      $scene_fade_duration = $_POST['scenefadeduration'];
              
      $this->validator->scene_validation($panodata);                                                    // Scene content error control and validation //
    
      $this->validator->empty_scene_validation($panodata);                                              // Empty scene content error control and validation //
    
      $this->validator->duplicate_hotspot_validation($panodata);                                        // Duplicate error control and validation //

      $panodata = $this->format->remove_empty_scene_and_hotspot($panodata);                             // Remove Empty scene and hotspot //

      //===audio===//
      $bg_music          = isset($_POST['bg_music']) ? sanitize_text_field($_POST['bg_music']) : 'off';
      $bg_music_url      = isset($_POST['bg_music_url']) ? esc_url_raw($_POST['bg_music_url']) : '';
      $autoplay_bg_music = isset($_POST['autoplay_bg_music']) ? sanitize_text_field($_POST['autoplay_bg_music']) : 'off';
      $loop_bg_music     = isset($_POST['loop_bg_music']) ? sanitize_text_field($_POST['loop_bg_music']) : 'off';
      if ($bg_music == 'on') {
        if (empty($bg_music_url)) {
          wp_send_json_error('<p><span>Warning:</span> Please add an audio file as you enabled audio for this tour </p>');
          die();
        }
      }
      //===audio===//

      $advanced_control = array(
        'keyboardzoom'              => $keyboardzoom,
        'diskeyboard'               => $diskeyboard,
        'draggable'                 => $draggable, 
        'mouseZoom'                 => $mouseZoom,
        'gyro'                      => $gyro, 
        'deviceorientationcontrol'  => $deviceorientationcontrol, 
        'compass'                   => $compass,
        'vrgallery'                 => $vrgallery, 
        'vrgallery_title'           => $vrgallery_title, 
        'vrgallery_display'         => $vrgallery_display,
        'vrgallery_icon_size'       => $vrgallery_icon_size,
        'bg_music'                  => $bg_music,
        'bg_music_url'              => $bg_music_url, 
        'autoplay_bg_music'         => $autoplay_bg_music, 
        'loop_bg_music'             => $loop_bg_music,
        'cpLogoSwitch'              => $cpLogoSwitch, 
        'cpLogoImg'                 => $cpLogoImg, 
        'cpLogoContent'             => $cpLogoContent, 
        'hfov'                      => $default_global_zoom, 
        'maxHfov'                   => $max_global_zoom, 
        'minHfov'                   => $min_global_zoom,
        'explainerSwitch'           => $explainerSwitch,
        'explainerContent'          => $explainerContent,
      );
      
      $pano_array = array();
      $pano_array = array(
                      "panoid" => $panoid,
                      "autoLoad" => $autoload,
                      "showControls" => $control,
                      "customcontrol" => $custom_control,
                      "autoRotate" => $autorotation,
                      "autoRotateInactivityDelay" => $autorotationinactivedelay,
                      "autoRotateStopDelay" => $autorotationstopdelay,
                      "genericform" => $genericform,
                      "genericformshortcode" => $genericformshortcode,
                      "preview" => $preview,
                      "defaultscene" => $default_scene,
                      "scenefadeduration" => $scene_fade_duration,
                      "panodata" => $panodata,
                      "previewtext" => $previewtext);
      $pano_array = apply_filters( 'prepare_scene_pano_array_with_pro_version', $pano_array, $_POST, $advanced_control );
      $pano_array = $this->format->prepare_rotation_wrapper_data($pano_array, $rotation);                 // Prepare tour rotation wrapper data /
      update_post_meta($postid, 'panodata', $pano_array);
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
     * Responsible for showing Scene Preview
     * 
     * @param string $panoid
     * @param string $panovideo
     * 
     * @return wp_send_json_success
     * @since 8.0.0
     */
    public function wpvr_scene_preview($panoid, $panovideo)
    {
      $panodata     = $this->format->prepare_panodata($_POST['panodata']);

      $control      = $this->format->set_checkbox_value($_POST['control']);
      $autoload     = $this->format->set_checkbox_value($_POST['autoload']);

      $compass      = $this->format->set_checkbox_on_value(@$_POST['compass']);
      $mouseZoom    = $this->format->set_pro_checkbox_value(@$_POST['mouseZoom']);
      $draggable    = $this->format->set_checkbox_value(@$_POST['draggable']);
      $gzoom        = $this->format->set_pro_checkbox_value(@$_POST['gzoom']);
      $diskeyboard  = $this->format->set_checkbox_value(@$_POST['diskeyboard']);
      $keyboardzoom = $this->format->set_checkbox_value(@$_POST['keyboardzoom']);

      $floor_plan_enabler = $this->format->set_pro_checkbox_value(@$_POST['wpvr_floor_plan_enabler']);
      $floor_plan_image = $_POST['wpvr_floor_plan_image'];

      $scene_fade_duration       = sanitize_text_field($_POST['scenefadeduration']);
      $preview                   = esc_url($_POST['preview']);

      $default_scene = '';

      $rotation                  = sanitize_text_field($_POST['rotation']);
      $autorotation              = sanitize_text_field($_POST['autorotation']);
      $autorotationinactivedelay = sanitize_text_field($_POST['autorotationinactivedelay']);
      $autorotationstopdelay     = sanitize_text_field($_POST['autorotationstopdelay']);

      $default_global_zoom = '';
      $max_global_zoom     = '';
      $min_global_zoom     = '';
      if ($gzoom == 'on') {
          $default_global_zoom = $_POST['dzoom'];
          $max_global_zoom     = $_POST['maxzoom'];
          $min_global_zoom     = $_POST['minzoom'];
      }
    
      $default_scene = $this->format->prepare_default_scene($panodata);
    
      $this->validator->basic_setting_validation($autorotationinactivedelay, $autorotationstopdelay); // Basic setting error control and validation //
  
      $this->validator->scene_validation($panodata);                                                  // Scene content error control and validation //

      $this->validator->empty_scene_validation($panodata);                                            // Empty scene content error control and validation //
  
      $this->validator->duplicate_hotspot_validation($panodata);
      if($floor_plan_enabler == 'on'){
          $this->validator->empty_floor_plan_image_validation($floor_plan_image);
      }

      $default_data = array();
      if ($gzoom == 'on') {
          $default_data = array("firstScene" => $default_scene, "sceneFadeDuration" => $scene_fade_duration, "hfov" => $default_global_zoom, "maxHfov" => $max_global_zoom, "minHfov" => $min_global_zoom);
      } else {
          $default_data = array("firstScene" => $default_scene, "sceneFadeDuration" => $scene_fade_duration);
      }

      $scene_data = $this->format->prepare_scene_data_for_preview($panodata);
      
      
      $pano_id_array = array();
      $pano_id_array = array("panoid" => $panoid);
      $pano_response = array();
      $pano_response = array("autoLoad" => $autoload, "defaultZoom" => $default_global_zoom, "minZoom" => $min_global_zoom, "maxZoom" => $max_global_zoom, "showControls" => $control, "compass" => $compass, "mouseZoom" => $mouseZoom, "draggable" => $draggable, "disableKeyboardCtrl" => $diskeyboard, 'keyboardZoom' => $keyboardzoom, "preview" => $preview, "autoRotate" => $autorotation, "autoRotateInactivityDelay" => $autorotationinactivedelay, "autoRotateStopDelay" => $autorotationstopdelay, "default" => $default_data, "scenes" => $scene_data);
      
      $pano_response = $this->format->prepare_rotation_wrapper_data($pano_response, $rotation);

      $pano_floor_plan = array();
      $pano_floor_plan = array(
          "floor_plan_tour_enabler" => $floor_plan_enabler,
          "floor_plan_attachment_url" => $floor_plan_image
      );
      $call_to_action = array(
              'button_enable' =>sanitize_text_field($_POST['callToAction']),
              'button_text' =>sanitize_text_field($_POST['buttontext']),
              'button_url' =>sanitize_text_field($_POST['buttonurl'])
      );
      $response = array();
      $response = array($pano_id_array, $pano_response, $panovideo,$pano_floor_plan,$call_to_action);
      wp_send_json_success($response);
    }


    /**
     * Render shortcode for scene and hotspot post data
     * 
     * @param array $postdata
     * @param string $panoid
     * @param integer $id
     * @param mixed $radius
     * @param mixed $width
     * @param mixed $height
     * 
     * @return string
     * @since 8.0.0
     */
    public function render_scene_shortcode($postdata, $panoid, $id, $radius, $width, $height, $mobile_height)
    {
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
        //== Floor plan handle ==//
        $floor_plan_enable = 'off';
        $floor_plan_image = '';
        if (isset($postdata['floor_plan_tour_enabler']) && $postdata['floor_plan_tour_enabler'] == 'on'){
            $floor_plan_enable = $postdata['floor_plan_tour_enabler'];
            if(isset($postdata['floor_plan_attachment_url']) && !empty($postdata['floor_plan_attachment_url'])){
                $floor_plan_image = $postdata['floor_plan_attachment_url'];
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
            //===Floor plan  handle===//
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
          $draggable = $postdata['draggable'] == 'off' || $postdata['draggable'] == null ? false : true;
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

        $default_global_zoom = '';
        if (isset($postdata['hfov'])) {
            $default_global_zoom = $postdata['hfov'];
        }

        $max_global_zoom = '';
        if (isset($postdata['maxHfov'])) {
            $max_global_zoom = $postdata['maxHfov'];
        }

        $min_global_zoom = '';
        if (isset($postdata['minHfov'])) {
            $min_global_zoom = $postdata['minHfov'];
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

        $panodata = '';
        if (isset($postdata['panodata'])) {
            $panodata = $postdata['panodata'];
        }

        $hotspoticoncolor = '#00b4ff';
        $hotspotblink = 'on';
        $default_data = array();
        if ($default_global_zoom != '' && $max_global_zoom != '' && $min_global_zoom != '') {
            $default_data = array("firstScene" => $default_scene, "sceneFadeDuration" => $scene_fade_duration, "hfov" => $default_global_zoom, "maxHfov" => $max_global_zoom, "minHfov" => $min_global_zoom);
        } else {
            $default_data = array("firstScene" => $default_scene, "sceneFadeDuration" => $scene_fade_duration);
        }

        $scene_data = array();

        if (!empty($panodata["scene-list"])) {
            foreach ($panodata["scene-list"] as $panoscenes) {
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
                if (isset($panoscenes["scene-zoom"]) && $panoscenes["scene-zoom"] != "") {
                    $default_zoom = $panoscenes["scene-zoom"];
                } else {
                    if ($default_global_zoom != '') {
                        $default_zoom =  (int)$default_global_zoom;
                    }
                }


                $max_zoom = 120;
                if (isset($panoscenes["scene-maxzoom"]) && $panoscenes["scene-maxzoom"] != '') {
                    $max_zoom = (int)$panoscenes["scene-maxzoom"];
                } else {
                    if ($max_global_zoom != '') {
                        $max_zoom =  (int)$max_global_zoom;
                    }
                }



                $min_zoom = 50;
                if (isset($panoscenes["scene-minzoom"]) && $panoscenes["scene-minzoom"] != '') {
                    $min_zoom = (int)$panoscenes["scene-minzoom"];
                } else {
                    if ($min_global_zoom != '') {
                        $min_zoom =  (int)$min_global_zoom;
                    }
                }


                $hotspot_datas = array();
                if (isset($panoscenes["hotspot-list"])) {
                    $hotspot_datas = $panoscenes["hotspot-list"];
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
                        "text" => $hotspot_data["hotspot-title"],
                        "pitch" => $hotspot_data["hotspot-pitch"],
                        "yaw" => $hotspot_data["hotspot-yaw"],
                        "type" => $hotspot_type,
                        "cssClass" => $hotspot_data["hotspot-customclass"],
                        "URL" => $hotspot_data["hotspot-url"],
                        "wpvr_url_open" => $wpvr_url_open,
                        "clickHandlerArgs" => $on_click_content,
//                        "clickHandlerArgs" => $hotspot_content,
//                    'createTooltipArgs' =>  $hotspot_data['hotspot-hover'],
                        'createTooltipArgs' => $on_hover_content,
                        "sceneId" => $hotspot_data["hotspot-scene"],
                        "targetPitch" => (float)$hotspot_scene_pitch,
                        "targetYaw" => (float)$hotspot_scene_yaw,
                        'hotspot_type' => $hotspot_data['hotspot-type'],
                        'hotspot_target' => 'notBlank'
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
                        if ($image_info[0] > 4096) {
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
                                    if (isset($desktop_scene[0])) {
                                        $device_scene = $desktop_scene[0];
                                    }
                                }
                            }
                        }
                    }
                }

                $scene_info = array();

                if ($panoscenes["scene-type"] == 'cubemap') {
                    $pano_attachment = array(
                        $panoscenes["scene-attachment-url-face0"],
                        $panoscenes["scene-attachment-url-face1"],
                        $panoscenes["scene-attachment-url-face2"],
                        $panoscenes["scene-attachment-url-face3"],
                        $panoscenes["scene-attachment-url-face4"],
                        $panoscenes["scene-attachment-url-face5"]
                    );

                    $scene_info = array("type" => $panoscenes["scene-type"], "cubeMap" => $pano_attachment, "pitch" => $default_scene_pitch, "maxPitch" => $scene_max_pitch, "minPitch" => $scene_min_pitch, "maxYaw" => $scene_max_yaw, "minYaw" => $scene_min_yaw, "yaw" => $default_scene_yaw, "hfov" => $default_zoom, "maxHfov" => $max_zoom, "minHfov" => $min_zoom, "title" => $scene_ititle, "author" => $scene_author, "authorURL" => $scene_author_url, "vaov" => $scene_vaov, "haov" => $scene_haov, "vOffset" => $scene_vertical_offset, "hotSpots" => $hotspots);
                } else {
                    $scene_info = array("type" => $panoscenes["scene-type"], "panorama" => $device_scene, "pitch" => $default_scene_pitch, "maxPitch" => $scene_max_pitch, "minPitch" => $scene_min_pitch, "maxYaw" => $scene_max_yaw, "minYaw" => $scene_min_yaw, "yaw" => $default_scene_yaw, "hfov" => $default_zoom, "maxHfov" => $max_zoom, "minHfov" => $min_zoom, "title" => $scene_ititle, "author" => $scene_author, "authorURL" => $scene_author_url, "vaov" => $scene_vaov, "haov" => $scene_haov, "vOffset" => $scene_vertical_offset, "hotSpots" => $hotspots);
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
                    $panoscenes["scene-id"] => $scene_info
                );
                $scene_data[$panoscenes["scene-id"]] = $scene_info;
            }
        }

        $pano_id_array = array();
        $pano_id_array = array("panoid" => $panoid);
        $pano_response = array();
        $pano_response = array("autoLoad" => $autoload, "showControls" => $control, "orientationSupport" => 'false', "compass" => $compass, 'orientationOnByDefault' => $gyro_orientation, "mouseZoom" => $mouseZoom, "draggable" => $draggable, 'disableKeyboardCtrl' => $diskeyboard, 'keyboardZoom' => $keyboardzoom, "preview" => $preview, "autoRotate" => $autorotation, "autoRotateInactivityDelay" => $autorotationinactivedelay, "autoRotateStopDelay" => $autorotationstopdelay, "default" => $default_data, "scenes" => $scene_data);
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
            $width = '600px';
        }
        if (empty($height)) {
            $height = '400px';
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
        $html = '';

        $html .= '<style>';
        if ($width == 'embed') {
            $html .= 'body{
                overflow: hidden;
           }';
        }
        $status  = get_option('wpvr_edd_license_status');
        if ($status !== false && $status == 'valid') {
            if(isset($postdata['customcss_enable']) && $postdata['customcss_enable'] == 'on'){
                $html .= isset($postdata['customcss']) ? $postdata['customcss'] : '';
            }
        }
        $panoid2 = 'pano2'.$id;
        $status  = get_option('wpvr_edd_license_status');
        if ($status !== false && $status == 'valid') {
            foreach ($panodata['scene-list'] as $panoscenes){

                foreach($panoscenes['hotspot-list'] as $hotspot){
                    if (isset($hotspot['hotspot-customclass-color-icon-value']) && !empty($hotspot['hotspot-customclass-color-icon-value'])){
                        $hotspoticoncolor = $hotspot['hotspot-customclass-color-icon-value'];
                    }else{
                        $hotspoticoncolor = "#00b4ff";
                    }
                    $pulse_color = wpvr_hex2rgb($hotspoticoncolor);
                    if (isset($hotspot["hotspot-customclass-pro"]) && $hotspot["hotspot-customclass-pro"] != 'none') {
                        $html .= '#' . $panoid . ' div.pnlm-hotspot-base.fas.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                          #' . $panoid . ' div.pnlm-hotspot-base.fab.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                          #' . $panoid . ' div.pnlm-hotspot-base.fa-solid.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                          #' . $panoid . ' div.pnlm-hotspot-base.fa.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                          #' . $panoid . ' div.pnlm-hotspot-base.far.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].' {
                              display: block !important;
                              background-color: ' .$hotspoticoncolor.';
                              color: ' . $foreground_color . ';
                              border-radius: 100%;
                              width: 30px;
                              height: 30px;
                              font-size: 16px;
                              line-height: 30px;
                              animation: icon-pulse' . $panoid .'-'. $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].' 1.5s infinite cubic-bezier(.25, 0, 0, 1);
                          }';
                                    $html .= '#' . $panoid2 . ' div.pnlm-hotspot-base.fas.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                              #' . $panoid2 . ' div.pnlm-hotspot-base.fab.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                              #' . $panoid2 . ' div.pnlm-hotspot-base.fa-solid.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                              #' . $panoid2 . ' div.pnlm-hotspot-base.fa.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].',
                              #' . $panoid2 . ' div.pnlm-hotspot-base.far.custom-' . $id.'-' . $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'].' {
                              display: block !important;
                              background-color: ' . $hotspoticoncolor. ';
                              color: ' . $foreground_color . ';
                              border-radius: 100%;
                              width: 30px;
                              height: 30px;
                              font-size: 16px;
                              line-height: 30px;
                              animation: icon-pulse' . $panoid2 .'-'. $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title']. ' 1.5s infinite cubic-bezier(.25, 0, 0, 1);
                      }';
                    }
                    if (isset($hotspot['hotspot-blink'])) {
                        $hotspotblink = $hotspot['hotspot-blink'];
                        if ($hotspotblink == 'on') {
                            $html .= '@-webkit-keyframes icon-pulse'  .$panoid .'-'. $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'] .' {
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
                            $html .= '@-webkit-keyframes icon-pulse'  .$panoid2 .'-'. $panoscenes['scene-id'] .'-'. $hotspot['hotspot-title'] .' {
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

        if ($width == 'fullwidth') {
            $width = "100%";
        }

        if (wpvr_isMobileDevice()) {
            $html .= '<div id="master-container" class="wpvr-cardboard '.$enable_cardboard.'" style="max-width:' . $width . '; width: 100%; height: ' . $mobile_height . '; border-radius:' . $radius . '; direction:ltr; ">';
        } else {
            $html .= '<div id="master-container" class="wpvr-cardboard '.$enable_cardboard.'" style="max-width:' . $width . '; width: 100%; height: ' . $height . '; border-radius:' . $radius . '; direction:ltr; ">';
        }
        $is_pro = apply_filters('is_wpvr_pro_active',false);
        $status  = get_option('wpvr_edd_license_status');
        $is_cardboard = get_option('wpvr_cardboard_disable');
        if ($status !== false &&  'valid' == $status  && $is_pro && wpvr_isMobileDevice() && $is_cardboard == 'true' ) {
            $html .= '<button class="fullscreen-button">';
            $html .= '<span class="expand">';
            $html .= '<i class="fa fa-expand" aria-hidden="true"></i>';
            $html .= '</span>';

            $html .= '<span class="compress">';
            $html .= '<i class="fa fa-compress" aria-hidden="true"></i>';
            $html .= '</span>';
            $html .= '</button>';
            $embed_mode = '';
            if($width == "embed"){
                $embed_mode = "vr-embade-mode";
            }
            $html .= '<label class="wpvr-cardboard-switcher '.$embed_mode.'">
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
                $html .= '<div id="pano2' . $id . '" class="pano-wrap  pano-left cardboard-half" style="width: 49%!important; border-radius:' . $radius . ' text-align:center; direction:ltr;" ><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';
                $html .= '<div id="pano' . $id . '" class="pano-wrap  pano-right" style="width: 100%; text-align:center; direction:ltr; border-radius:' . $radius . '" >';
            } else {
                $html .= '<div id="pano2' . $id . '" class="pano-wrap pano-left" style="width: 49%; border-radius:' . $radius . '"><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';
                if ($radius) {
                    $html .= '<div id="pano' . $id . '" class="pano-wrap vrfullwidth" style=" text-align:center; height: ' . $height . '; border-radius:' . $radius . '; direction:ltr;" >';
                } else {
                    $html .= '<div id="pano' . $id . '" class="pano-wrap vrfullwidth" style=" text-align:center; height: ' . $height . '; direction:ltr;" >';
                }
            }
        } elseif ($width == 'embed') {
            $html .= '<div class="cardboard-vrembed vrembed">';
            $html .= '<div id="pano2' . $id . '" class="pano-wrap  pano-left" style=" width: 49%!important; text-align:center; direction:ltr;" ><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';
            $html .= '<div id="pano' . $id . '" class="pano-wrap  pano-right" style=" text-align:center; direction:ltr;" >';
        } else {
            if (wpvr_isMobileDevice()) {
                $html .= '<div id="pano2' . $id . '" class="pano-wrap pano-left cardboard-half" style="width: 49%; border-radius:' . $radius . '"><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';
                if ($radius) {
                    $html .= '<div id="pano' . $id . '" class="pano-wrap pano-right" style=" width: 100%; border-radius:' . $radius . ';">';
                } else {
                    $html .= '<div id="pano' . $id . '" class="pano-wrap pano-right" style=" width: 100%; ">';
                }
            } else {
                $html .= '<div id="pano2' . $id . '" class="pano-wrap pano-left" style="width: 49%; border-radius:' . $radius . '"><div id="center-pointer2' . $id . '" class="vr-pointer-container"><span class="center-pointer"></span></div></div>';

                if ($radius) {
                    $html .= '<div id="pano' . $id . '" class="pano-wrap pano-right" style="width: 100%; border-radius:' . $radius . ';">';
                } else {
                    $html .= '<div id="pano' . $id . '" class="pano-wrap pano-right" style="width: 100%;">';
                }
            }
        }
        // Vr mode transction scene to scene
        if ($status !== false &&  'valid' == $status  && $is_pro) {
            $html .= '<div id="center-pointer' . $id . '" class="vr-pointer-container" style="display:none"><span class="center-pointer"></span></div>';
        }
        $social_logo_top = '';
        //===company logo===//
        if (isset($postdata['cpLogoSwitch'])) {
            $cpLogoImg = $postdata['cpLogoImg'];
            $cpLogoContent = $postdata['cpLogoContent'];
            if ($postdata['cpLogoSwitch'] == 'on') {
                $html .= '<div id="cp-logo-controls">';
                $html .= '<div class="cp-logo-ctrl" id="cp-logo">';
                if ($cpLogoImg) {
                    $social_logo_top = '50px';
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
                    if (!empty($menuLocations['primary'])) {
                        $menuID = $menuLocations['primary'];
                        $primaryNav = wp_get_nav_menu_items($menuID);
                        $html .= '<ul class="wpvr-navbar-container">';
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
                        $html .= '</ul>';
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
                    $html .= '<div class="ctrl" id="gyroscope' . $id . '" ><i class="' . $custom_control['gyroscopeIcon'] . '" id="' . $custom_control['gyroscopeIcon'] . '" style="color:' . $custom_control['gyroscopeColor'] . ';"></i></div>';
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
            $explainerControlSwitch = '';
            if (isset($custom_control['explainerSwitch'])) {
                $explainerControlSwitch = $custom_control['explainerSwitch'];
            }
            if ($explainerControlSwitch == "on") {
                $html .= '<div class="explainer_button" id="explainer_button_' . $id . '" style="right:' . $explainer_right . '">';
                $html .= '<div class="ctrl" id="explainer_target_' . $id . '"><i class="' . $custom_control['explainerIcon'] . '" style="color:' . $custom_control['explainerColor'] . ';"></i></div>';
                $html .= '</div>';
            }

            //===explainer button===//
        }
        //===Custom Control===//

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
        //===Call TO  action Button===//
        $autoplay_bg_music = isset($postdata['bg_music']) ? $postdata['bg_music'] : "off";
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
                $html .= '<audio id="vrAudio' . $id . '" class="vrAudioDefault" data-autoplay="' . $autoplay_bg_music . '" onended="audionEnd' . $id . '()" ' . $bg_loop . '>
                                <source src="' . $bg_music_url . '" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            <button onclick="playPause' . $id . '()" class="ctrl audio_control" data-play="' . $autoplay_bg_music . '" data-play="' . $autoplay_bg_music . '" id="audio_control' . $id . '"><i id="vr-volume' . $id . '" class="wpvrvolumeicon' . $id . ' fas fa-volume-up" style="color:#fff;"></i></button>
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


        if( 'embed' === $width){
            if(WPVR_Helper::is_enable_social_share($postdata) === 'on'){
                $html .= '<div id="wpvr-social-share-bg-box'.$id.'" class="wpvr-social-share-bg-box" style="top:'.$social_logo_top.'">
                            <span class="share-btn-svg"><svg fill="none" viewBox="0 0 24 24" width="24" height="24" ><path fill="#1F1CF4" d="M18.4 2.4a3.2 3.2 0 00-3.2 3.2 3.2 3.2 0 00.075.67L8.01 9.901A3.2 3.2 0 005.6 8.8a3.2 3.2 0 101.325 6.112 3.2 3.2 0 001.086-.812l7.261 3.632a3.2 3.2 0 101.803-2.242c-.415.189-.786.466-1.085.81l-7.261-3.63A3.2 3.2 0 008.8 12a3.2 3.2 0 00-.075-.667L15.991 7.7a3.2 3.2 0 002.41 1.1 3.2 3.2 0 100-6.4z"/></svg></span>
                            <nav class="wpvr-share-nav">
                                '.WPVR_Helper::social_media_share_links_display_in_embed(home_url().'/?embed_page='. $id).'
                            </nav>
                        </div>';
            }
        }
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
        if( "embed" == $width ){
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
                $html .= '<div class="wpvr-call-to-action-button position-'.$text_align.'" style="max-width:' . $width.'">
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
										x' . $id . '.play();
                    jQuery("#audio_control' . $id . '").attr("data-play", "on");
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

        $is_pro = apply_filters('is_wpvr_pro_active',false);
        $status  = get_option('wpvr_edd_license_status');
        $html .= 'var vr_mode = "off";';
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
            // if(localStorage.getItem("vr_mode") == "on") {
            if(vr_mode == "on") {
                jQuery("#pano2' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
                jQuery("#pano' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
             }
        });
        var compassBlock = "";
        var infoBlock = "";
        jQuery(document).on("click",".vr_mode_change' . $id . '",function (){
          jQuery("#pano2' . $id . ' .pnlm-load-button").trigger("click");
          jQuery("#pano' . $id . ' .pnlm-load-button").trigger("click");
          var getValue =   jQuery(this).val();
          var getParent = jQuery(this).parent().parent();
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
                screen.orientation.lock("landscape-primary").then(function() {
                }).catch(function(error) {
                    alert("VR Glass Mode not supported in this device");
                });
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
                
                getParent.find(".pano-wrap").addClass("wpvr-cardboard-disable-event");
                getParent.find("#pano' .$id. ' #zoom-in-out-controls'.$id.'").hide();
                getParent.find("#pano' .$id. ' #controls'.$id.'").hide();
                getParent.find("#pano' .$id. ' #explainer_button_'.$id.'").hide();
                getParent.find("#pano' .$id. ' #floor_map_button_'.$id.'").hide();
                getParent.find("#pano' .$id. ' #vrgcontrols'.$id.'").hide();
                getParent.find("#pano' .$id. ' #sccontrols'.$id.'").hide();
                getParent.find("#pano' .$id. ' #adcontrol'.$id.'").hide();
                getParent.find("#pano' .$id. ' .owl-nav.wpvr_slider_nav").hide();
                getParent.find("#pano' .$id. ' #cp-logo-controls").hide();
                getParent.find("#pano' .$id. ' #wpvr-social-share-bg-box'.$id.'").hide();
                
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
                
                 panoshow' . $id . '.on("zoomchange", function (data){
                    panoshow2' . $id . '.setHfov(data, 0);
                });

                panoshow2' . $id . '.on("zoomchange", function (data){
                    panoshow' . $id . '.setHfov(data, 0);
                });
                
                jQuery(document).on("click","#pano2' . $id . '",function(event) {
                  panoshow' . $id . '.startOrientation();
                  panoshow2' . $id . '.startOrientation();
                  
                });
                
                jQuery(document).on("click","#pano' . $id . '",function(event) {
                  panoshow' . $id . '.startOrientation();
                  panoshow2' . $id . '.startOrientation();
                });
                
                panoshow' . $id . '.on("mousemove", function (data){
                    panoshow2' . $id . '.setPitch(data.pitch, 0);
                    panoshow2' . $id . '.setYaw(data.yaw, 0);
                    panoShowCardBoardOnTrigger(data);
            
                });
                panoshow2' . $id . '.on("mousemove", function (data){
                    panoshow' . $id . '.setPitch(data.pitch, 0);
                    panoshow' . $id . '.setYaw(data.yaw, 0);
                    panoShowCardBoardOnTrigger(data);
            
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
                getParent.find(".pano-wrap").removeClass("wpvr-cardboard-disable-event");

                getParent.find("#pano' .$id. ' #zoom-in-out-controls'.$id.'").show();
                getParent.find("#pano' .$id. ' #controls'.$id.'").show();
                getParent.find("#pano' .$id. ' #explainer_button_'.$id.'").show();
                getParent.find("#pano' .$id. ' #floor_map_button_'.$id.'").show();

                getParent.find("#pano2' . $id . ' .pnlm-controls-container").show();
                getParent.find("#pano' . $id . ' .pnlm-controls-container").show();
                getParent.find("#pano' .$id. ' #vrgcontrols'.$id.'").show();
                getParent.find("#pano' .$id. ' #sccontrols'.$id.'").hide();
                getParent.find("#pano' .$id. ' #adcontrol'.$id.'").show();
                getParent.find("#pano' .$id. ' .owl-nav.wpvr_slider_nav").hide();
                getParent.find("#pano' .$id. ' #cp-logo-controls").show();
                getParent.find("#pano' .$id. ' #wpvr-social-share-bg-box'.$id.'").show();
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
                // if(localStorage.getItem("vr_mode") == "off") {
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

        //=== end Dplicate mode only for vr mode===//

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

        $html .= '
        const node = document.querySelector(".add-pulse");
        panoshow' . $id . '.on("compasschange", function (data){
            console.log(data);
            // const node = document.querySelector(".add-pulse");
            // node.style.transform = data;
            // jQuery(".add-pulse").css({"transform":data});
    
            });
        ';
        $html .= 'panoshow' . $id . '.on("load", function (){
            // if(localStorage.getItem("vr_mode") == "off") {
            if(vr_mode == "off") {
                  jQuery(".vr-mode-title").hide();
                }
             else {
                jQuery("#pano2' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
                jQuery("#pano' . $id . ' .pnlm-compass.pnlm-controls.pnlm-control").css("display","none");
                jQuery("#pano2' . $id . ' .pnlm-panorama-info").hide();
                jQuery("#pano' . $id . ' .pnlm-panorama-info").hide();
                jQuery(".vr-mode-title").show();
             }
            setTimeout(() => {
                window.dispatchEvent(new Event("resize"));
            }, 200);
						if (jQuery("#pano' . $id . '").children().children(".pnlm-panorama-info:visible").length > 0) {
	               jQuery("#controls' . $id . '").css("bottom", "55px");
	           }
	           else {
	             jQuery("#controls' . $id . '").css("bottom", "5px");
	           }
					});';

        $html .= 'panoshow' . $id . '.on("render", function (){
              window.dispatchEvent(new Event("resize"));
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
                    // $html .= 'document.getElementById("'.$scene_key_gallery.'").addEventListener("click", function(e) { ';
                    // $html .= 'if (touchtime == 0) {';
                    // $html .= 'touchtime = new Date().getTime();';
                    // $html .= '} else {';
                    // $html .= 'if (((new Date().getTime()) - touchtime) < 800) {';
                    // $html .= 'panoshow'.$id.'.loadScene("'.$scene_key.'");';
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

        if ($autoplay_bg_music == 'on') {

            $html .= '
            jQuery(document).on("click","#explainer_button_' . $id . '",function() {
                jQuery("#explainer' . $id . '").slideToggle();
    
                playing' . $id . ' = false;
                var x' . $id . ' = document.getElementById("vrAudio' . $id . '");
                jQuery("#vr-volume' . $id . '").removeClass("fas fa-volume-up");
                jQuery("#vr-volume' . $id . '").addClass("fas fa-volume-mute");
                x' . $id . '.pause();
            });
    
            jQuery(document).on("click",".close-explainer-video",function() {
                jQuery(this).parent(".explainer").hide();
                var el_src = jQuery(".vr-iframe").attr("src");
                jQuery(".vr-iframe").attr("src", el_src);
              });
    
            ';
        } else {
            $html .= '
            jQuery(document).on("click","#explainer_button_' . $id . '",function() {
                jQuery("#explainer' . $id . '").slideToggle();
            });
    
            jQuery(document).on("click",".close-explainer-video",function() {
                jQuery(this).parent(".explainer").hide();
                var el_src = jQuery(".vr-iframe").attr("src");
                jQuery(".vr-iframe").attr("src", el_src);
              });
    
            ';
        }

        //===Explainer Script End===//


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
                    jQuery(".vrgctrl' . $id . '").html(' . $sin_qout . $angle_down . $sin_qout . ');
                    jQuery(".wpvr_slider_nav").show();
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
                    jQuery("#floor_map_button_' . $id . '").show();
                    jQuery("#vrgcontrols' . $id . '").show();
                    jQuery("#pano' . $id . '").find(".pnlm-panorama-info").show();
            });';
        }

        //==Old code working properly==//

        $previeword = "Click to Load Panorama";
        if (isset($postdata['previewtext']) && $postdata['previewtext'] != '') {
            $previeword = $postdata['previewtext'];
        }
        $html .= '
            jQuery(".elementor-tab-title").click(function(){
                      var element_id;
                      var pano_id;
                      var element_id = this.id;
                      element_id = element_id.split("-");
                      element_id = element_id[3];
                      jQuery("#elementor-tab-content-"+element_id).find("#master-container").children("div").eq(1).addClass("awwww");
                      var pano_id = jQuery(".awwww").attr("id");
                      jQuery("#elementor-tab-content-"+element_id).find("#master-container").children("div").eq(1).removeClass("awwww");;
                      if (pano_id != undefined) {
                        pano_id = pano_id.split("o");
                        pano_id = pano_id[1];
                        if (pano_id == "' . $id . '") {
                          jQuery("#pano' . $id . '").children(".pnlm-render-container").remove();
                          jQuery("#pano' . $id . '").children(".pnlm-ui").remove();
                          panoshow' . $id . ' = pannellum.viewer(response[0]["panoid"], scenes);
                          jQuery("#pano' . $id . '").children(".pnlm-ui").find(".pnlm-load-button p").text("' . $previeword . '")
                          setTimeout(function() {
                                //   panoshow' . $id . '.loadScene("' . $default_scene . '");
                                  window.dispatchEvent(new Event("resize"));
                                  if (jQuery("#pano' . $id . '").children().children(".pnlm-panorama-info:visible").length > 0) {
                                       jQuery("#controls' . $id . '").css("bottom", "55px");
                                   }
                                   else {
                                     jQuery("#controls' . $id . '").css("bottom", "5px");
                                   }
                                   
                          }, 200);
                        }
                      }
            });
        ';
        $html .= '
            jQuery(".geodir-tab-head dd, #vr-tour-tab").click(function(){
              jQuery("#pano' . $id . '").children(".pnlm-render-container").remove();
              jQuery("#pano' . $id . '").children(".pnlm-ui").remove();
              panoshow' . $id . ' = pannellum.viewer(response[0]["panoid"], scenes);
              setTimeout(function() {
                      panoshow' . $id . '.loadScene("' . $default_scene . '");
                      window.dispatchEvent(new Event("resize"));
                      if (jQuery("#pano' . $id . '").children().children(".pnlm-panorama-info:visible").length > 0) {
                           jQuery("#controls' . $id . '").css("bottom", "55px");
                       }
                       else {
                         jQuery("#controls' . $id . '").css("bottom", "5px");
                       }
              }, 200);
            });
        ';
        if (isset($postdata['previewtext']) && $postdata['previewtext'] != '') {
            $html .= '
            jQuery("#pano' . $id . '").children(".pnlm-ui").find(".pnlm-load-button p").text("' . $postdata['previewtext'] . '")
            ';
        }

        if ($default_global_zoom != '' || $max_global_zoom != '' || $min_global_zoom != '') {
            $html .= '
            jQuery(".globalzoom").val("on").change();
            ';
        }


        $html .= '});';
        $html .= '</script>';
        //script end

        return $html;
    }

    function replace_callback($matches){
        foreach ($matches as $match){
            return str_replace('<img','<img decoding="async"',$match);
        }

    }
}