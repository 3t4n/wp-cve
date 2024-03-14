<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Manage all validation requirements and messages
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_Validator {
   
  /**
   * Get all error messages
   * 
   * @var array
   * @since 8.0.0
   */
  public $errors = array();


  /**
   * Auto-rotation data error control and validation
   * 
   * @param number $autorotationinactivedelay
   * @param number $autorotationstopdelay
   * 
   * @return void
   * @since 8.0.0
   */
  public function basic_setting_validation($autorotationinactivedelay, $autorotationstopdelay)
  {
    if (!empty($autorotationinactivedelay) && !empty($autorotationstopdelay)) {
        $this->add_error('autorotation_dual_action', '<span class="pano-error-title">Dual Action Error for Auto-Rotation</span><p> You can not use both Resume Auto-rotation & Stop Auto-rotation on the same tour. You can use only one of them.</p>');
    }
  }


  /**
   * Preview Image Message error control and validation
   * 
   * @param string $text
   * 
   * @return string
   * @since 8.0.0
   */
  public function preview_text_validation($text)
  {
    $prevtext = sanitize_text_field($text);
    if (strlen($prevtext) > 50) {
      $this->add_error('preview_text', '<p><span>Warning:</span> Don\'t add more than 50 characters in Preview Image Message</p>');
    }
    return $prevtext;
  }


  /**
  * Scene content error control and validation
  * @param array $panodata
  * 
  * @return void
  * @since 8.0.0
  */
  public function scene_validation($panodata)
  {
    if ($panodata["scene-list"] != "") {
      foreach ($panodata["scene-list"] as $scenes_val) {
        $scene_id_validate = $scenes_val["scene-id"];

        if (!empty($scene_id_validate)) {

          $scene_id_validated = preg_replace('/[^0-9a-zA-Z_]/', "", $scene_id_validate);
          if ($scene_id_validated != $scene_id_validate) {
            $this->add_error('invalid_scene_id', '<span class="pano-error-title">Invalid Scene ID</span> <p>Scene ID can\'t contain spaces and special characters. <br/>Please assign a unique Scene ID with letters and numbers where Scene ID is : ' . $scene_id_validate . '</p>');
          }

          $this->scene_attachment_validation($scenes_val, $scene_id_validate);    // Attachment error control and validation //

          $this->scene_pitch_yaw_validation($scenes_val, $scene_id_validate);     // Pitch and yaw error controll and validation //

          $this->scene_default_zoom_validation($scenes_val, $scene_id_validate);  // Default zoom error controll and validation //

          $this->scene_max_zoom_validation($scenes_val, $scene_id_validate);      // Max zoom error controll and validation //

          $this->scene_min_zoom_validation($scenes_val, $scene_id_validate);      // Min zoom error controll and validation //

          $this->scene_hotspot_validation($scenes_val, $scene_id_validate);       // Hotspot error controll and validation //
        }
      }
    }
  }


  /**
   * Empty image, Scene ID, and duplicate scene 
   * Error control and validation
   * 
   * @param array $panodata
   * 
   * @return void
   * @since 8.0.0
   */
  public function empty_scene_validation($panodata)
  {
    $allsceneids = array();
    
    foreach ($panodata["scene-list"] as $panoscenes) {
      if (empty($panoscenes['scene-id']) && !empty($panoscenes['scene-attachment-url'])) {
        $this->add_error('missing_scene_id', '<span class="pano-error-title">Missing Scene ID</span> <p>Please assign a unique Scene ID to your uploaded scene.</p>');
      }
      if (!empty($panoscenes['scene-id'])) {
        array_push($allsceneids, $panoscenes['scene-id']);
      }
    }

    foreach ($panodata["scene-list"] as $panoscenes) {

      if ($panoscenes['dscene'] == 'on') {
        $default_scene = $panoscenes['scene-id'];
      }
    }
    if (empty($default_scene)) {
      if ($allsceneids) {
        $default_scene = $allsceneids[0];
      } else {
        $this->add_error('missing_image_scene', '<span class="pano-error-title">Missing Image & Scene ID</span> <p>Please Upload An Image and Set A Scene ID To See The Preview</p>');
      }
    }

    $allsceneids_count = array_count_values($allsceneids);
    foreach ($allsceneids_count as $key => $value) {
      if ($value > 1) {
        $this->add_error('duplicate_scene', '<span class="pano-error-title">Duplicate Scene ID</span> <p>You\'ve assigned a duplicate Scene ID. <br/>Please assign unique Scene IDs to each scene. </p>');
      }
    }
  }


  /**
   * Scene attachment error controll and validation
   * 
   * @param array $scenes_val
   * @param string $scene_id_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function scene_attachment_validation($scenes_val, $scene_id_validate)
  {
    if ($scenes_val['scene-type'] == 'cubemap') {
      if (empty($scenes_val["scene-attachment-url-face0"])) {
        $this->add_error('cubemap_0', '<span class="pano-error-title">Missing Cubemap Scene Face 0</span><p> Please upload a 360 Degree image where Scene ID is: ' . $scene_id_validate . '</p>');
      }

      if (empty($scenes_val["scene-attachment-url-face1"])) {
        $this->add_error('cubemap_1', '<span class="pano-error-title">Missing Cubemap Scene Face 1</span><p> Please upload a 360 Degree image where Scene ID is: ' . $scene_id_validate . '</p>');
      }

      if (empty($scenes_val["scene-attachment-url-face2"])) {
        $this->add_error('cubemap_2', '<span class="pano-error-title">Missing Cubemap Scene Face 2</span><p> Please upload a 360 Degree image where Scene ID is: ' . $scene_id_validate . '</p>');
      }

      if (empty($scenes_val["scene-attachment-url-face3"])) {
        $this->add_error('cubemap_3', '<span class="pano-error-title">Missing Cubemap Scene Face 3</span><p> Please upload a 360 Degree image where Scene ID is: ' . $scene_id_validate . '</p>');
      }

      if (empty($scenes_val["scene-attachment-url-face4"])) {
        $this->add_error('cubemap_4', '<span class="pano-error-title">Missing Cubemap Scene Face 4</span><p> Please upload a 360 Degree image where Scene ID is: ' . $scene_id_validate . '</p>');
      }

      if (empty($scenes_val["scene-attachment-url-face5"])) {
        $this->add_error('cubemap_5', '<span class="pano-error-title">Missing Cubemap Scene Face 5</span><p> Please upload a 360 Degree image where Scene ID is: ' . $scene_id_validate . '</p>');
      }
    } else {
      if (empty($scenes_val["scene-attachment-url"])) {
        $this->add_error('missing_scene', '<span class="pano-error-title">Missing Scene Image</span><p> Please upload a 360 Degree image where Scene ID is: ' . $scene_id_validate . '</p>');
      }
    }
  }



  /**
   * Scene default zoom error control and validation
   * 
   * @param array $scenes_val
   * @param string $scene_id_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function scene_default_zoom_validation($scenes_val, $scene_id_validate)
  {
    if (!empty($scenes_val["scene-zoom"])) {
      $validate_default_zoom = $scenes_val["scene-zoom"];
      $validated_default_zoom = preg_replace('/[^0-9-]/', '', $validate_default_zoom);
      if ($validated_default_zoom != $validate_default_zoom) {
         $this->add_error('invalid_default_zoom', '<span class="pano-error-title">Invalid Default Zoom Value</span><p> You can only set Default Zoom in Degree values from 50 to 120 where Scene ID: ' . $scene_id_validate . '</p>');
      }
      $default_zoom_value = (int)$scenes_val["scene-zoom"];
      if ($default_zoom_value > 120 || $default_zoom_value < 50) {
         $this->add_error('invalid_default_zoom', '<span class="pano-error-title">Invalid Default Zoom Value</span><p> You can only set Default Zoom in Degree values from 50 to 120 where Scene ID: ' . $scene_id_validate . '</p>');
      }
    }
  }


  /**
   * Scene max zoom error control and validation
   * 
   * @param array $scenes_val
   * @param string $scene_id_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function scene_max_zoom_validation($scenes_val, $scene_id_validate)
  {
    if (!empty($scenes_val["scene-maxzoom"])) {
      $validate_max_zoom = $scenes_val["scene-maxzoom"];
      $validated_max_zoom = preg_replace('/[^0-9-]/', '', $validate_max_zoom);
      if ($validated_max_zoom != $validate_max_zoom) {
         $this->add_error('invalid_max_zoom', '<span class="pano-error-title">Invalid Max-zoom Value:</span><p> You can only set Max-zoom in degree values (50-120) where Scene ID: ' . $scene_id_validate . '</p>');
      }
      $max_zoom_value = (int)$scenes_val["scene-maxzoom"];
      if ($max_zoom_value > 120) {
        $this->add_error('limit_max_zoom', '<span class="pano-error-title">Max-zoom Value Limit Exceeded</span><p> You can set the Max-zoom Value up to 120 degrees.</p>');
      }

      if ($max_zoom_value < 50) {
       $this->add_error('limit_max_zoom', '<span class="pano-error-title">Max-zoom Value Limit Exceeded</span><p> You can not set the Max-zoom Value lower than 50 degrees.</p>');
      }
    }
  }


  /**
   * Scene min zoom error control and validation
   * 
   * @param array $scenes_val
   * @param string $scene_id_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function scene_min_zoom_validation($scenes_val, $scene_id_validate)
  {
    if (!empty($scenes_val["scene-minzoom"])) {
      $validate_min_zoom = $scenes_val["scene-minzoom"];
      $validated_min_zoom = preg_replace('/[^0-9-]/', '', $validate_min_zoom);
      if ($validated_min_zoom != $validate_min_zoom) {
        $this->add_error('invalid_min_zomm', '<span class="pano-error-title">Invalid Min-zoom Value</span><p> You can only set Min-zoom in degree values (50-120) where Scene ID: ' . $scene_id_validate . '</p>');
      }
      $min_zoom_value = (int)$scenes_val["scene-minzoom"];
      if ($min_zoom_value < 50) {
        $this->add_error('low_min_zoom', '<span class="pano-error-title">Low Min-Zoom Value</span><p> The Min-zoom value must be more than 50 in degree values where Scene ID: ' . $scene_id_validate . '</p>');
      }

      if ($min_zoom_value > 120) {
        $this->add_error('low_min_zoom', '<span class="pano-error-title">Hight Min-Zoom Value</span><p> The Min-zoom value must be less than 120 in degree values where Scene ID: ' . $scene_id_validate . '</p>');
      }
    }
  }


  /**
   * Scene hotspot error control and validation
   * 
   * @param array $scenes_val
   * @param string $scene_id_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function scene_hotspot_validation($scenes_val, $scene_id_validate)
  {
    if ($scenes_val["hotspot-list"] != "") {
      foreach ($scenes_val["hotspot-list"] as $hotspot_val) {

        $hotspot_title_validate = $hotspot_val["hotspot-title"];

        if (!empty($hotspot_title_validate)) {
          $this->hotspot_id_validation($scene_id_validate, $hotspot_title_validate);                         // ID error control and validation //
          
          $this->hotspot_pitch_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate);        // Pitch error control and validation //

          $this->hotspot_yaw_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate);          // Yaw error control and validation //

          $this->hotspot_custom_icon_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate);  // Custom icon error control and validation //

          $this->hotspot_url_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate);          // URL error control and validation //

          $this->info_hotspot_type_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate);    // Info type error control and validation //

          $this->hotspot_shortcode_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate);    // Shortcode error control and validation //

          $this->scene_type_hotspot_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate);   // Scene type error control and validation //
        }
      }
    }
  }


  /**
   * Scene pitch and yaw error control and validation
   * 
   * @param array $scenes_val
   * @param string $scene_id_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function scene_pitch_yaw_validation($scenes_val, $scene_id_validate)
  {
    if (!empty($scenes_val["scene-pitch"])) {
      $validate_scene_pitch = $scenes_val["scene-pitch"];
      $validated_scene_pitch = preg_replace('/[^0-9.-]/', '', $validate_scene_pitch);
      if ($validated_scene_pitch != $validate_scene_pitch) {
         $this->add_error('invalid_pitch', '<span class="pano-error-title">Invalid Pitch Value</span><p> The Pitch Value can only contain float numbers where Scene ID: ' . $scene_id_validate . '</p>');
      }
    }

    if (!empty($scenes_val["scene-yaw"])) {
      $validate_scene_yaw = $scenes_val["scene-yaw"];
      $validated_scene_yaw = preg_replace('/[^0-9.-]/', '', $validate_scene_yaw);
      if ($validated_scene_yaw != $validate_scene_yaw) {
         $this->add_error('invalid_yaw', '<span class="pano-error-title">Invalid Yaw Value</span><p> The Yaw Value can only contain float numbers where Scene ID: ' . $scene_id_validate . '</p>');
       }
    }
  }


  /**
   * Hotspot pitch error control and validation
   * 
   * @param array $hotspot_val
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function hotspot_pitch_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate)
  {
    $hotspot_pitch_validate = $hotspot_val["hotspot-pitch"];
    if (empty($hotspot_pitch_validate)) {
      $this->add_error('pitch_required', '<p><span>Warning:</span> Hotspot pitch is required for every hotspot where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
    }
    if (!empty($hotspot_pitch_validate)) {
      $hotspot_pitch_validated = preg_replace('/[^0-9.-]/', '', $hotspot_pitch_validate);
      if ($hotspot_pitch_validated != $hotspot_pitch_validate) {
        $this->add_error('invalid_pitch', '<span class="pano-error-title">Invalid Pitch Value</span> <p>The Pitch Value can only contain float numbers where Scene ID: ' . $scene_id_validate . ' Hotspot ID is: ' . $hotspot_title_validate . '</p>');
      }
    }
  }


  /**
   * Hotspot yaw error control and validation
   * 
   * @param array $hotspot_val
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function hotspot_yaw_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate)
  {
    $hotspot_yaw_validate = $hotspot_val["hotspot-yaw"];
    if (empty($hotspot_yaw_validate)) {
      $this->add_error('yaw_required', '<p><span>Warning:</span> Hotspot yaw is required for every hotspot where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
    }
    if (!empty($hotspot_yaw_validate)) {
      $hotspot_yaw_validated = preg_replace('/[^0-9.-]/', '', $hotspot_yaw_validate);
      if ($hotspot_yaw_validated != $hotspot_yaw_validate) {
        $this->add_error('invalid_yaw', '<span class="pano-error-title">Invalid Yaw Value</span> <p>The Yaw Value can only contain float numbers where Scene ID: ' . $scene_id_validate . ' Hotspot ID is: ' . $hotspot_title_validate . '</p>');
      }
    }
  }


  /**
   * Info type hotspot error control and validation
   * 
   * @param array $hotspot_val
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function info_hotspot_type_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate)
  {
    if ($hotspot_val["hotspot-type"] == "info") {
      if (!empty($hotspot_val["hotspot-scene"])) {
        $this->add_error('target_scene_info_type', '<p><span>Warning:</span> Don\'t add Target Scene ID on info type hotspot where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
      }
      if (!empty($hotspot_val["hotspot-url"]) && !empty($hotspot_val["hotspot-content"])) {
        $this->add_error('both_click_content_url', '<span class="pano-error-title">Warning!</span> <p>You can not set both On Click Content and URL on a hotspot where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
      }
    }
  }

  /**
   * Scene type hotspot error control and validation
   * 
   * @param array $hotspot_val
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function scene_type_hotspot_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate)
  {
    if ($hotspot_val["hotspot-type"] == "scene") {
      if (empty($hotspot_val["hotspot-scene"])) {
        $this->add_error('target_scene_missing', '<span class="pano-error-title">Target Scene Missing</span> <p>Assign a Target Scene to the Scene-type Hotspot where Scene ID: ' . $scene_id_validate . ' and Hotspot ID : ' . $hotspot_title_validate . '</p>');
      }
      if (!empty($hotspot_val["hotspot-url"]) || !empty($hotspot_val["hotspot-content"])) {
        $this->add_error('both_url_click_content', '<p><span>Warning:</span> Don\'t add Url or On click content on scene type hotspot where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
      }
    }
  }


  /**
   * Hotspot URL error control and validation
   * 
   * @param array $hotspot_val
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function hotspot_url_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate)
  {
    $hotspot_url_validate = $hotspot_val["hotspot-url"];
    if (!empty($hotspot_url_validate)) {
      $hotspot_url_validated = esc_url($hotspot_url_validate);
      if ($hotspot_url_validated != $hotspot_url_validate) {
        $this->add_error('invalid_hotspot_url', '<p><span>Warning:</span> Hotspot Url is invalid where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
      }
    }
  }


  /**
   * Hotspot Shortcode error control and validation
   * 
   * @param array $hotspot_val
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function hotspot_shortcode_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate)
  {
    if ($hotspot_val["hotspot-type"] == "shortcode_editor") {
      if (substr($hotspot_val['hotspot-shortcode'], 0, 1) === '[') {
        $pattern = get_shortcode_regex();
        preg_match('/' . $pattern . '/s', $hotspot_val['hotspot-shortcode'], $matches);
        if (is_array($matches) && !isset($matches[2])) {
          $this->add_error('invalid_shortcode', '<p><span>Warning:</span> This is not a valid shortcode where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>'); 
        }
      }
    }
  }

  /**
   * Hotspot custom icon error control and validation
   * 
   * @param array $hotspot_val
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   * @since 8.0.0
   */
  private function hotspot_custom_icon_validation($hotspot_val, $scene_id_validate, $hotspot_title_validate)
  {
    if (is_plugin_active('wpvr-pro/wpvr-pro.php')) {
      $status  = get_option('wpvr_edd_license_status');
      if ($status !== false && $status == 'valid') {
        if ($hotspot_val["hotspot-customclass-pro"] != 'none' && !empty($hotspot_val["hotspot-customclass"])) {
          $this->add_error('both_custom_icon', '<span class="pano-error-title">Warning!</span> <p>You can not use both Custom Icon and Custom Icon Class for a hotspot where scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
        }
      }
    }
  }


  /**
   * Hotspot ID error control and validation
   * 
   * @param string $scene_id_validate
   * @param string $hotspot_title_validate
   * 
   * @return void
   */
  private function hotspot_id_validation($scene_id_validate, $hotspot_title_validate)
  {
    $hotspot_title_validated = preg_replace('/[^0-9a-zA-Z_]/', "", $hotspot_title_validate);
    if ($hotspot_title_validated != $hotspot_title_validate) {
      $this->add_error('invalid_hotspot_id', '<span class="pano-error-title">Invalid Hotspot ID</span> <p>Hotspot ID can\'t contain spaces and special characters.<br/> Please assign a unique Hotspot ID with letters and numbers where Scene id: ' . $scene_id_validate . ' and hotspot id : ' . $hotspot_title_validate . '</p>');
    }
  }


  /**
   * Duplicate hotspot error control and validation
   * 
   * @param array $panodata
   * 
   * @return void
   * @since 8.0.0
   */
  public function duplicate_hotspot_validation($panodata)
  {
    foreach ($panodata["scene-list"] as $panoscenes) {
      if (!empty($panoscenes['scene-id'])) {
        $allhotspot = array();
        foreach ($panoscenes["hotspot-list"] as $hotspot_val) {
          if (!empty($hotspot_val["hotspot-title"])) {
            array_push($allhotspot, $hotspot_val["hotspot-title"]);
          }
        }
        $allhotspotcount = array_count_values($allhotspot);
        foreach ($allhotspotcount as $key => $value) {
          if ($value > 1) {
            $this->add_error('duplicate_hotspot', '<span class="pano-error-title">Duplicate Hotspot ID</span> <p>You\'ve assigned a duplicate Hotspot ID. <br/>Please assign unique Hotspot IDs to each Hotspot.</p>');
          }
        }
      }
    }
  }


  /**
   * Empty or no video url error control and validation
   * 
   * @param string $videourl
   * 
   * @return void
   * @since 8.0.0
   */
  public function empty_video_validation($videourl)
  {
    if($videourl == ''){
      $this->add_error('no_video', '<span class="pano-error-title">No Video Found!</span> <p>You haven\'t uploaded or set the link to a 360 degree video. Please Upload or Set a video to see the Preview.</p>');
    }
  }

  /**
   * Empty or no video url error control and validation
   *
   * @param string $videourl
   *
   * @return void
   * @since 8.0.0
   */
  public function empty_floor_plan_image_validation($floor_plan)
  {
    if($floor_plan == ''){
      $this->add_error('no_floor_plan_image', '<span class="pano-error-title">No Image Found!</span> <p>Please provide floor plan image</p>');
    }
  }


  /**
   * Add validation messages to errors array
   * 
   * @param string $key
   * @param string $value
   * 
   * @return void
   * @since 8.0.0
   */
  public function add_error($key, $value)
  {
    $this->errors[$key] = $value;
    $this->display_errors();
  }


  /**
   * Display validation message or sending back responses
   * 
   * @return wp_send_json_error
   * @since 8.0.0
   */
  private function display_errors()
  {
    foreach($this->errors as $error){
        wp_send_json_error($error);
        die();
    }
  }
}