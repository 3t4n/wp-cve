<?php
/*
  Plugin Name: Templates Importer by Sonaar
 */

/**
 * Do not call outside of WordPress
 */
function_exists( 'add_filter' ) || exit;

function printImportCTA($block){
  $SRMP3_plan = get_site_option('SRMP3_purchased_plan');
  $licenseKey = get_site_option('sonaar_music_licence');

  if($block == 'heading'){
    if ( !function_exists( 'run_sonaar_music_pro' ) ){
      return  sprintf(__('<a href="%1$s" target="_blank">Get MP3 Audio Player Pro</a>', 'sonaar-music'), 'https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin');
    }
    switch ($SRMP3_plan) {
      case '1':
        $SRMP3_plan = sprintf(__('<a href="%1$s" target="_blank">Upgrade to get access</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=5');
        break;

      case '6':
        $SRMP3_plan = __('Enjoy!', 'sonaar-music');
        break;

      case '5':
        $SRMP3_plan = sprintf(__('<a href="%1$s" target="_blank">Upgrade to get access</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=6');
        break;

      case '7':
        $SRMP3_plan = __('Enjoy!', 'sonaar-music');
        break;

      case '3':
        $SRMP3_plan = __('Enjoy!', 'sonaar-music');
        break;

      case '4':
        $SRMP3_plan = __('Enjoy!', 'sonaar-music');
        break;

      default:
        $SRMP3_plan = __('Enter your License Key to get access.', 'sonaar-music');
        break;
    }
    return $SRMP3_plan;

  }
  if($block == 'after_import'){
    if ( !function_exists( 'run_sonaar_music_pro' ) ){
      return __('You are using the free version of MP3 Audio Player. The templates are only compatible with the PRO version. <br><a href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin">Get MP3 Audio Player Pro</a>', 'sonaar-music');
    }
    switch ($SRMP3_plan) {
      case '1':
        $SRMP3_plan = sprintf(__('You need the Player Elementor Templates access with your current Starter plan.<br><a href="%1$s">Upgrade here</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=5');
        break;
  
      case '6':
        $SRMP3_plan = true;
        break;
  
      case '5':
        $SRMP3_plan = sprintf(__('You need the Player Elementor Templates access with your current Business plan<br><a href="%1$s">Upgrade here</a>', 'sonaar-music'), 'https://sonaar.io/cart/?nocache=true&edd_action=sl_license_upgrade&license_id='. $licenseKey .'&upgrade_id=6');
        break;
  
      case '7':
        $SRMP3_plan = true;
        break;
  
      case '3':
        $SRMP3_plan = true;
        break;
  
      case '4':
        $SRMP3_plan = true;
        break;
  
      default:
        $SRMP3_plan = sprintf( __( 'We must validate your license first. Make sure to <strong>remove</strong> and re-activate your license key <a href="%1$s" >here</a> ', 'sonaar-music' ), admin_url( 'edit.php?post_type=' . SR_PLAYLIST_CPT . '&page=sonaar_music_pro_license' ) , 'sonaar-music');
        break;
    }
    return $SRMP3_plan;
  }
}
function srmp3_get_json_url() {
  $SRMP3_plan = get_site_option('SRMP3_purchased_plan');
  
  if( !is_admin() ) return false;
  
  ?>
   <div class="srmp3_wrap_templates">
    <h1 class="srmp3_import_head">Import Player Templates<div class="srmp3_pro_badge"><i class="sricon-Sonaar-symbol">&nbsp;</i>Pro feature</div></h1>
    <div class="srmp3_import_subtitle">Save time and effort with our pre-designed MP3 Audio Player Pro skin templates for Elementor. To get access, you need either the Player Elementor Templates access, the Unlimited or the Lifetime Plan or <a href="https://sonaar.io/mp3-audio-player-pro/pricing/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin" target="_blank">MP3 Audio Player Pro</a>. Imported templates will be available in your Templates section and will be editable with Elementor.</div>  
    <?php if ( function_exists( 'printPurchasedPlan' ) ){ ?>
      <div class="srmp3_import_license-msg">You are currently on the <span class="srmp3_import_license-msg--plan"><?php echo esc_html( printPurchasedPlan() ) ?> plan. </span><?php echo wp_kses_post(printImportCTA('heading')) ?></div>
    <?php }else{ ?>
      <div class="srmp3_import_license-msg">You are currently on the <span class="srmp3_import_license-msg--plan">free version. </span><?php echo wp_kses_post(printImportCTA('heading')) ?></div>

    <?php } 
  $licence = get_site_option('sonaar_music_licence');
  
  $demo_folder = 'http://assets.sonaar.io/import/';

  $api_url = 'https://sonaar.io/wp-json/wp/v2/sonaar-api/srmp3-templates/?licence=' . $licence;
  $templates_json_url = wp_remote_get( $api_url );
  if ( !$templates_json_url ) {
    $templates_json_url = new WP_Error( 'Error 6721', "Please contact Sonaar.io support team with Error Code 6721" );
  }

  if ( is_wp_error( $templates_json_url ) ) {
    new WP_Error( 'Error 6722', "Please contact Sonaar.io support team with Error Code 6722" );
  }

  if ( is_wp_error( $templates_json_url ) || $templates_json_url['response']['code'] !== 200 || $templates_json_url['headers']['content-type'] !== 'application/json; charset=UTF-8' ){
    new WP_Error( 'Error 6723', "Please contact Sonaar.io support team with Error Code 6723" );
  }

  if ( is_wp_error( $templates_json_url )) {
    echo '<br>';
    $message = $templates_json_url->get_error_message();
    echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($message) . '</p></div>';
    echo '</div>'; // close srmp3_wrap_templates div
    return;
  }

  $url = json_decode( wp_remote_retrieve_body( $templates_json_url ) , true);

  $relativeurl = $url;
  $filename = basename($relativeurl);
  $dirname = str_replace($filename, '', $relativeurl) . '/templates/';
  $templatesList = srmp3_getTemplatesList($url);
  $canImport = true;
  srmp3_outputTemplates($templatesList, $dirname, $canImport);

}

function srmp3_getTemplatesList($url){
  $data = file_get_contents($url);

  if ($data === false) {
      // Handle error - unable to fetch JSON data
      echo "Error: Unable to fetch JSON data.\n";
      exit();
  }

  $jsonData = json_decode($data);

  if ($jsonData === null) {
      // Handle error - unable to parse JSON data
      echo "Error: Unable to parse JSON data.\n";
      exit();
  }
  return $jsonData;
}

function srmp3_outputTemplates($templatesList, $dirname) {
  
  $url = admin_url('edit.php?post_type=elementor_library&tabs_group=library');
  ?>

    <div class="srmp3_import_messages">
      <div class="srmp3_import_notice srmp3_import_success">
        <?php echo sprintf(__('<strong>Yepi! Your template has been successfully imported!</strong> You can access it in Templates > <a href="%1$s">Saved Templates</a>.', 'sonaar-music' ), esc_html($url)) ;?>
        <br><a href="https://elementor.com/help/adding-templates/" target="_blank">Watch video: how to insert a template in Elementor.</a>
      </div>
      <div class="srmp3_import_notice srmp3_import_failed"><strong>Oops! Template can't been imported.</strong></div>
    </div>

    <div id="srp_templates_container">
      <div class="srp_search_main"><div class="srp_search_container"><i class="fas fa-search"></i><input class="srp_search" enterkeyhint="done" placeholder="Search for a template keyword, eg: spectrum, Example 005, Grid, etc.." \></div></div>

      <ul class="template-list">
        <?php foreach ($templatesList->items as $template): ?>
          <?php
          $tmplt_filename = basename($template->filename);
          $tmplt_img = str_replace('.json', '.jpg', $tmplt_filename);
          $tmplt_title = str_replace('.json', '', $tmplt_filename);
          $tmplt_title = str_replace('-', ' ', $tmplt_title);
          $tmplt_title = ucwords($tmplt_title);
          ?>
          
            <li class="template-thumbnail">
              <img src="<?php echo esc_html($dirname . $tmplt_img) ?>">
              <div class="srmp3_importing"><?php echo __( 'Please Wait', 'sonaar-music' );?></div>
              <div class="srmp3_import_overlay" data-title="<?php echo esc_html($tmplt_title); ?>" data-filename="<?php echo esc_html($dirname . $tmplt_filename); ?>">
              <div class="srp_elementor_import"><?php echo __( 'Import Template', 'sonaar-music' );?></div>
              </div>
              <div class="srp-tmpl-title"><?php echo esc_html($tmplt_title) ?></div>
            </li>
        
        <?php endforeach; ?>
      </ul>
  </div>
  <?php
}

function import_srmp3_template() {
  
  check_ajax_referer('sonaar_music_admin_ajax_nonce', 'nonce');

  // Check if the current user has the required capability
  if (!current_user_can('manage_options')) {
    $ret = array(
        'success' => false,
        'message' => 'You do not have sufficient permissions to perform this action.'
    );
    echo json_encode($ret);
    exit;
  }

  $ret = array(); 
  $checkLicenseType = printImportCTA('after_import');

  if ($checkLicenseType !== true){
    $ret['success'] = false;
    $ret['message'] = $checkLicenseType;
    echo json_encode($ret);
    exit;
  }

  if (!did_action('elementor/loaded')) {
      $ret['success'] = false;
      $ret['message'] = esc_html__('Elementor plugin must be installed and active to run the template importer.', 'sonaar-music');
    
      echo json_encode($ret);
      exit;
  }

  if (null == \Elementor\Plugin::instance()->templates_manager) {
      $ret['success'] = false;
      $ret['message'] = esc_html__('Could not use the Elementor importer.', 'sonaar-music');
    
      echo json_encode($ret);
      exit;
  }

  $filename = strip_tags($_POST['filename']);

  if (substr($filename, -5) !== '.json') {
    $ret['success'] = false;
    echo 'Error reading file. Not a JSON';
  
    $ret['message'] = esc_html__('Could not load the template file. Please contact Sonaar.io support team!', 'sonaar-music');
  
    echo json_encode($ret);
    exit;
  }

  $fileContent = file_get_contents($filename);

  if (false == $fileContent ) {
    $ret['success'] = false;
    $error = error_get_last();
    echo 'Error reading file: ' . $error['message'];
  
    $ret['message'] = $error['message'];
    $ret['message'] = esc_html__('Could not load the template file. Please contact Sonaar.io support team!', 'sonaar-music');
  
    echo json_encode($ret);
    exit;
  }

  $result = \Elementor\Plugin::instance()->templates_manager->import_template( [
          'fileData' => base64_encode( $fileContent ),
          'fileName' => 'mytemplate.json',
      ]
  );

  if (is_wp_error($result)) {
    $ret['success'] = false;
    if($result->get_error_message() == 'Type container does not exist.'){
      $ret['message'] = __('The template your are importing uses Flexbox Container feature but it\'s deactivated.<br>Please enable <strong>Flexbox Container</strong> in Elementor > Settings > Features, then try again. (Error Code 6747)', 'sonaar-music');
    } else if($result->get_error_message() == 'Invalid template type.'){
      $ret['message'] = __('The template your are trying to import uses Flexbox Container feature from Elementor, but this feature is currently deactivated in your Elementor Settings.<br>Please enable <strong>Flexbox Container</strong> in Elementor > Settings > Features, then try again. (Error Code 6748)', 'sonaar-music');
    } else {
      $ret['message'] = $result->get_error_message();
    }
    echo json_encode($ret);
    exit;
  }

  if (empty($result) || empty($result[0])) {
    $ret['success'] = false;
    $ret['message'] = esc_html__('Importer did not return successfully. Please contact support team!', 'sonaar-music');

    echo json_encode($ret);
    exit;
  }

  $ret['success'] = true;

  echo json_encode($ret);
  exit;
}