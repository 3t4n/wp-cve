<?php
/*
Plugin Name: WPreCaptcha
Description: WPreCAPTCHA protects your site from spam and abuse. It uses advanced risk analysis techniques to tell humans and bots apart.
Author: WPreCaptcha.com
Version: 1.0
Author URI: https://www.WPreCaptcha.com
*/
if ( ! defined( 'ABSPATH' ) ) exit;
define( 'WP_RECAPTCHA_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_RECAPTCHA_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_RECAPTCHA_VERSION', '1.0' );
if( ! class_exists( 'WP_reCaptcha' ) ):
/**
* Main The WP reCaptcha class
*/
class WP_reCaptcha{
   public $site_key='';
   public $secret_key='';
   public $vesrion=0;
   public $form='';
   public $lang='';
   public $hide_d=0;
   public $hide_m=0;
   public $hide_vi_d=0;
   public $hide_vi_m=0;
   public $theme='';
   public $size='';
   public $pos='';
   public $forms;
   public $settings=array();
   public $processed=0;
   public $err_message='';
   public $module='';
   public $options=array();
   protected $plugin_screen_hook_suffix = null;
   protected $plugin_slug = 'ptmgh_wp_recaptcha';
   public function __construct() {}
   public function set_default_options() {
      $defaults = array(
         'version'                 => 3,
   		 'site_key_3'              => '',
   		 'site_key_3_v'            => '',
		 'secret_key_3'            => '',
		 'secret_key_3_v'          => '',
         'score'                   => 0.5,
         'hide_d'               => 0,
         'hide_m'               => 0,
		 'site_key_2'              => '',
		 'site_key_2_v'            => '',
		 'secret_key_2'            => '',
		 'secret_key_2_v'          => '',
		 'theme_v2'				   => 'light',
		 'size_v2'				   => 'normal',
		 'site_key_i'              => '',
		 'site_key_i_v'            => '',
		 'secret_key_i'            => '',
		 'secret_key_i_v'          => '',
         'hide_vi_d'               => 0,
         'hide_vi_m'               => 0,
         'position_i'              => 'bottomright',
		 'language'                => 'en',
		 'auto_language'           => 0,
         'login_form'			   => 0,
         'registration_form'	   => 0,
         'lost_password_form'	   => 0,
         'reset_password_form'	   => 0,
         'comments_form'		   => 0
	  );
	  return $defaults;
   }
   // Set plugin
   public function set_plugin() {
      if (get_option('ptmgh_wp_recaptcha_options')==false) {
         add_option('ptmgh_wp_recaptcha_options', $this->set_default_options());
      }
   }
   public function load_plugin() {
      $this->set_plugin();
      if (is_admin()){
         add_action('admin_menu', array( $this, 'add_plugin_admin_menu' ));
         add_action('admin_bar_menu', array($this,'top_menu_item'),1000 );
         add_action('admin_init', array($this,'admin_init'));
         add_action('wp_ajax_wp-recaptcha-test-keys',array($this, 'test_keys'));
         add_action('wp_ajax_wp-recaptcha-feedback',array($this, 'send_feedback'));
         add_action('admin_notices', array($this,'my_admin_notice'));
         add_action('admin_menu', function () {
            $position = 1;
            global $menu;
            $separator = [
            0 => '',
            1 => 'read',
            2 => 'separator' . $position,
            3 => '',
            4 => 'wp-menu-separator'
            ];
            $menu[$position] = $separator;
         });
         add_action('admin_footer', array($this,'upgrade_link_css'));
         add_action('admin_enqueue_scripts', array($this,'admin_scripts_styles'));
      }
      add_action('init', array($this,'init_plugin'));
   }
   
   public function admin_scripts_styles() {
      global $wp_scripts;
      wp_enqueue_script('jquery');
      wp_enqueue_script('jquery-ui-core');
      wp_enqueue_script('jquery-effects-core');
      wp_enqueue_script('jquery-ui-accordion');
      wp_enqueue_script('jquery-effects-slide');
      wp_enqueue_script('jquery-ui-tooltip');
      $jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.12.1';
      wp_enqueue_style( 'jquery-ui-style', WP_RECAPTCHA_URL .'css/themes/smoothness/jquery-ui.min.css', array(), $jquery_version );
      wp_enqueue_style( 'wprecaptcha-admin-css',WP_RECAPTCHA_URL .'css/admin.css?v='.time() );
      wp_enqueue_script( 'wprecaptcha-admin-js1',WP_RECAPTCHA_URL .'js/admin.js?v='.time() );
   }

   public function upgrade_link_css() {
      global $hook_suffix;
   ?>
<style>
.upgrade-link {color: #6bbc5b !important;}
.upgrade-link:hover {color: #7ad368 !important;}
</style>
   <?php
      if ( $hook_suffix == 'plugins.php') {
         $this->deactivation_feedback();
      }
   }
   
   public function my_admin_notice() {
      global $pagenow;
      if ( isset($_GET['page']) ) {
         if ($_GET['page'] == 'ptmgh_wp_recaptcha') {
            return;
         }
      }
   ?>
     <div class="notice notice-info is-dismissible"><div style="padding:20px;font-size:1.2em">
     <strong><?php echo __( 'Thank you for installing WP reCAPTCHA plugin!', 'wprecaptcha' );?></strong>
     <br><?php echo __( "Let's get started:", 'wprecaptcha' );?>
     <a href="admin.php?page=ptmgh_wp_recaptcha"><?php echo __( "Settings", 'wprecaptcha' );?></a>
     </div></div>
   <?php
   }
   
   public function admin_init() {
      register_setting('ptmgh_wp_recaptcha_options','ptmgh_wp_recaptcha_options',array( $this,'options_validate'));
   }
   public function options_validate($input) {
      return $input;
   }
   
   public function add_plugin_admin_menu() {
		// Add main menu item
		$this->plugin_screen_hook_suffix[] = add_menu_page(
		   __( 'WP reCAPTCHA Settings', 'wprecaptcha' ),
			__( 'WP reCAPTCHA', 'wprecaptcha' ),
			'manage_options',
            $this->plugin_slug,
			array( $this,'admin_options_page'),
            plugins_url( 'images/wpcaptcha.png', __FILE__ ),0
		);

		$this->plugin_screen_hook_suffix[] = add_submenu_page(
			$this->plugin_slug,
			__( 'Settings', 'wprecaptcha' ),
			__( 'Settings', 'wprecaptcha' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'admin_options_page' )
		);
	}

   public function top_menu_item( $wp_admin_bar ) {
      $args = array(
		'id'    => 'wp_recaptcha',
        'title' => '<div class="wp-menu-image" style="display:inline-block !important;position:relative;top:4px"><img src="'.plugins_url( 'images/wpcaptcha.png', __FILE__ ).'"></div><span class="ab-label">&nbsp;WP reCAPTCHA</span>',
        'href'  => admin_url( 'admin.php?page=ptmgh_wp_recaptcha' ),
        'meta'  => array( 'class' => 'menupop' )
      );
	  $wp_admin_bar->add_node( $args );
      $wp_admin_bar->add_node(
         array(
			'id'     => 'wp_recaptcha-settings',
			'parent' => 'wp_recaptcha',
			'title'  => __('Settings', 'wprecaptcha'),
			'href'  => admin_url( 'admin.php?page=ptmgh_wp_recaptcha' )
		 )
      );
   }
   
   public function admin_options_page() {
      if ( !current_user_can( 'manage_options' ) )  {
         wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }
      $is_network = is_network_admin();
      $plugins=get_plugins();
      //print_r($plugins);
      $options = get_option('ptmgh_wp_recaptcha_options');
      $vrf=0;
      $vrf3=0;
      $vrf2=0;
      $vrfi=0;
      $auth3= __('Please authenticate Your API keys for: Version 3', 'wprecaptcha');
      $auth2= __('Please authenticate Your API keys for: Version 2 - checkbox', 'wprecaptcha');
      $authi= __('Please authenticate Your API keys for: Version 2 - invisible', 'wprecaptcha');
      if (($options['site_key_3_v']==1) && ($options['secret_key_3_v']==1)) {
          $vrf=1;
          $vrf3=1;
          $auth3= __('API keys for Version 3 successfully authenticated', 'wprecaptcha');
      }
      if (($options['site_key_2_v']==1) && ($options['secret_key_2_v']==1)) {
          $vrf=1;
          $vrf2=1;
          $auth2= __('API keys for Version 2 - checkbox successfully authenticated', 'wprecaptcha');
      }
      if (($options['site_key_i_v']==1) && ($options['secret_key_i_v']==1)) {
          $vrf=1;
          $vrfi=1;
          $authi= __('API keys for Version 2 - invisible successfully authenticated', 'wprecaptcha');
      }
$languages=array(
'ar'=> 'Arabic',
'af'=> 'Afrikaans',
'am'=> 'Amharic',
'hy'=> 'Armenian',
'az'=> 'Azerbaijani',
'eu'=> 'Basque',
'bn'=> 'Bengali',
'bg'=> 'Bulgarian',
'ca'=> 'Catalan',
'zh-HK'=> 'Chinese (Hong Kong)',
'zh-CN'=> 'Chinese (Simplified)',
'zh-TW'=> 'Chinese (Traditional)',
'hr'=> 'Croatian',
'cs'=> 'Czech',
'da'=> 'Danish',
'nl'=> 'Dutch',
'en-GB'=> 'English (UK)',
'en'=> 'English (US)',
'et'=> 'Estonian',
'fil'=> 'Filipino',
'fi'=> 'Finnish',
'fr'=> 'French',
'fr-CA'=> 'French (Canadian)',
'gl'=> 'Galician',
'ka'=> 'Georgian',
'de'=> 'German',
'de-AT'=> 'German (Austria)',
'de-CH'=> 'German (Switzerland)',
'el'=> 'Greek',
'gu'=> 'Gujarati',
'iw'=> 'Hebrew',
'hi'=> 'Hindi',
'hu'=> 'Hungarian',
'is'=> 'Icelandic',
'id'=> 'Indonesian',
'it'=> 'Italian',
'ja'=> 'Japanese',
'kn'=> 'Kannada',
'ko'=> 'Korean',
'lo'=> 'Laothian',
'lv'=> 'Latvian',
'lt'=> 'Lithuanian',
'ms'=> 'Malay',
'ml'=> 'Malayalam',
'mr'=> 'Marathi',
'mn'=> 'Mongolian',
'no'=> 'Norwegian',
'fa'=> 'Persian',
'pl'=> 'Polish',
'pt'=> 'Portuguese',
'pt-BR'=> 'Portuguese (Brazil)',
'pt-PT'=> 'Portuguese (Portugal)',
'ro'=> 'Romanian',
'ru'=> 'Russian',
'sr'=> 'Serbian',
'si'=> 'Sinhalese',
'sk'=> 'Slovak',
'sl'=> 'Slovenian',
'es'=> 'Spanish',
'es-419'=> 'Spanish (Latin America)',
'sw'=> 'Swahili',
'sv'=> 'Swedish',
'ta'=> 'Tamil',
'te'=> 'Telugu',
'th'=> 'Thai',
'tr'=> 'Turkish',
'uk'=> 'Ukrainian',
'ur'=> 'Urdu',
'vi'=> 'Vietnamese',
'zu'=> 'Zulu'
);

?>
<h2><?php echo __('WP reCAPTCHA', 'wprecaptcha');?> | <a href="https://www.wprecaptcha.com" target="_blank"><?php echo __('Visit plugin site', 'wprecaptcha');?></a></h2>
<div class="ptmbg-settings-div">
<h2 class="nav-tab-wrapper nav-settings" style="margin-bottom:20px">
<span id="nav-auth" class="nav-tab nav-tab-active"><?php echo __('Settings', 'wprecaptcha');?></span>
<span id="nav-forms" class="nav-tab"><?php echo __('Forms', 'wprecaptcha');?></span>
<span id="nav-lang" class="nav-tab"><?php echo __('Languages', 'wprecaptcha');?></span>
<span id="nav-shortcode" class="nav-tab"><?php echo __('Shortcode Generator', 'wprecaptcha');?></span>
<span id="nav-guide" class="nav-tab"><?php echo __('Quickstart Guide', 'wprecaptcha');?></span>
</h2>

<div id="settings-shortcode" class="settings-tab" style="display:none">
<?php if ($vrf==0) {
   echo '<p class="description" style="color:red">'. __('Please go to "Settings" tab to authenticate your API keys.', 'wprecaptcha'). '</p>';
} else { ?>
<p class="description" style="color:red"><?php echo __('To add specific reCAPTCHA settings to a form, create and add a shortcode to the form page.', 'wprecaptcha');?></p>
<p class="description" style="color:red"><?php echo __('AVAILABLE FOR PRO VERSION ONLY.', 'wprecaptcha');?></p>

<div style="margin:10px 0"><input type="text" id="short-code" value="[wp-recaptcha]">&nbsp;<span class="button button-primary" style="position:absolute;left:400px" onClick="copyShortCode()"><span class="dashicons dashicons-external" style="position:relative;top:5px"></span>&nbsp;<?php echo __('Copy code', 'wprecaptcha');?></span></div>
<div style="padding:10px;margin:20px 0;background-color:#fff;position:relative;border:1px solid;border-radius:6px;min-height:48px">
<img id="s-g-badge" class="s-g-badge-right" src="<?php echo WP_RECAPTCHA_URL;?>images/recaptcha.png">
<div id="s-no-visible" style="max-width:320px;display:none">
<p id="google-copyright"><?php echo __('This site is protected by reCAPTCHA and the Google', 'wprecaptcha');?> <a href="https://policies.google.com/privacy" target="_blank"><?php echo __('Privacy Policy', 'wprecaptcha');?></a> <?php echo __('and', 'wprecaptcha');?> <a href="https://policies.google.com/terms" target="_blank"><?php echo __('Terms of Service', 'wprecaptcha');?></a> apply.</p>
</div>
<img id="s-g-theme" src="<?php echo WP_RECAPTCHA_URL;?>/images/captcha_light.png">
</div>

<div class="tab-content">
<table class="my-form-table" cellspacing="8">
<tr><th style="padding-top:10px">reCAPTCHA Version</th>
<td class="radio-box" id="vs" style="padding-top:10px">
<?php
if (($options['site_key_3_v']==1) && ($options['secret_key_3_v']==1)) {
   echo '<div class="radio-btn" id="vs3"><div class="radio-icon"></div><div class="radio-title">&nbsp;'. __('Version 3', 'wprecaptcha'). '</div></div>';
}
if (($options['site_key_2_v']==1) && ($options['secret_key_2_v']==1)) {
   echo '<div class="radio-btn" id="vs2"><div class="radio-icon"></div><div class="radio-title">&nbsp;'.__('Version 2 - checkbox', 'wprecaptcha'). '</div></div>';
}
if (($options['site_key_i_v']==1) && ($options['secret_key_i_v']==1)) {
   echo '<div class="radio-btn" id="vsi"><div class="radio-icon"></div><div class="radio-title">&nbsp;'. __('Version 2 - invisible', 'wprecaptcha'). '</div></div>';
}

?>
</td>
</tr>
<tr class="v-opt vsi"><th>Google Badge position</th>
<td class="radio-box" id="ps" colspan="2">
<div class="radio-btn" id="ps1"><div class="radio-icon radio-on"></div><div class="radio-title">&nbsp;<?php echo __('Bottom right', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="ps2"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Bottom left', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="ps3"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Inline', 'wprecaptcha');?></div></div>
</td>
</tr>
<tr class="v-opt vs3 vsi"><th><?php echo __('Hide Google badge', 'wprecaptcha');?></th>
<td>
<div class="ui-checkbox">
<div onclick="checkboxShort('hide-d')" id="checkboxshort-hide-d" class="ui-pointer ui-btn-icon-left ui-checkbox-off"><?php echo __('Hide badge for Desktop devices', 'wprecaptcha');?></div>
</div>
<div class="ui-checkbox">
<div onclick="checkboxShort('hide-m')" id="checkboxshort-hide-m" class="ui-pointer ui-btn-icon-left ui-checkbox-off"><?php echo __('Hide badge for Mobile devices', 'wprecaptcha');?></div>
</div>
</td>
</tr>
<tr class="v-opt vs2"><th><?php echo __('Version 2 Theme', 'wprecaptcha');?></th>
<td class="radio-box" id="ts" colspan="2">
<div class="radio-btn" id="ts1"><div class="radio-icon radio-on"></div><div class="radio-title">&nbsp;<?php echo __('Light', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="ts2"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Dark', 'wprecaptcha');?></div></div>
</td>
</tr>
<tr class="v-opt vs2"><th><?php echo __('Version 2 Size', 'wprecaptcha');?></th>
<td class="radio-box" id="gs" colspan="2">
<div class="radio-btn" id="gs1"><div class="radio-icon radio-on"></div><div class="radio-title">&nbsp;<?php echo __('Normal', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="gs2"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Compact', 'wprecaptcha');?></div></div>
</td>
</tr>
<tr><td colspan="2" style="padding:0 !important"><hr></td></tr>
<tr><th><?php echo __('reCAPTCHA Language', 'wprecaptcha');?></th>
<td style="position:relative">
<select id="sc-lang" name="language" onChange="generateShortCode()">
<?php foreach ( $languages as $code => $name ) { ?>
<option value="<?php echo esc_attr( $code ) ?>" <?php if ($code=='en') {echo "selected";}?>><?php echo esc_html( $name ) ?></option>
<?php } ?>
</select>
<div id="short-language-hide" style="width:100%;height:100%;opacity:0.5;background-color:#F6FBFD;position:absolute;top:0;left:0;display:none">&nbsp;</div>
</td></tr>
<tr><td></td>
<td>
<div class="ui-checkbox" style="margin:0">
<div onclick="checkboxShort('auto-language')" id="checkboxshort-auto-language" class="ui-pointer ui-btn-icon-left ui-checkbox-off"><?php echo __('Switch Languages Automatically.', 'wprecaptcha');?></div>
</div>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><th><?php echo __('Form ID', 'wprecaptcha');?></th>
<td><input type="text" name="formId" id="sc-form-id" onInput="generateShortCode()">
<p class="description">If on page have more that one form you need insert ID on form who want protected.</p>
</td></tr>
</table>
<div style="padding:10px">
<p style="color:#f00;font-size:1.6em;font-weight:bold;max-size:600px">Shortcode Generator is available for Pro version only.</p>
<a href="https://www.wprecaptcha.com/" target="_blank"><span class="button upgrade-btn" style="margin-top:3px"><?php echo __('Upgrade to Pro', 'wprecaptcha');?></span></a>
</div>
</div>
<?php } ?>
</div>

<div id="settings-guide" class="settings-tab" style="display:none;width:calc(100% - 20px)">
<h3>Using the WP reCaptcha plugin is very easy:</h3>
<ul style="list-style-type:decimal;margin-left:20px">
<li>If You have any <strong>Google reCAPTCHA</strong> integrations for your forms - <span style="color:red">please disable the integrations.</span></li>
<li>If you have any other <strong>Google reCAPTCHA</strong> plugins - <span style="color:red">please deactivate or uninstall</span> the plugins before use wpRECAPTCHA.</li>
<li>Go to <strong>"Settings"</strong> tab</li>
<ul>
<li><a href="https://www.google.com/recaptcha/intro/v3.html" target="blank">Register your website with Google</a></li>
<li>Get the required Google API keys for the selected version of reCAPTCHA, and enter to fields.</li>
<li>Click "Authenticate" button.</li>
<li>Save changes.</li>
</ul></li>
<li>Go to <strong>"Languages"</strong> tab and select your site language or check "Switch Languages Automatically".<br>Save changes.</li>
<li>Go to <strong>"Forms"</strong> tab -> WordPress Default Forms and just check form who want protect.<br>Save changes.</li>
</ul>
<p><strong>This is all. Not other actions required.</strong></p>
<p><strong>Note:</strong><p>
<ul style="color:red">
<li>External Plugins Forms and Woocommerce Forms is available for Pro version only.</li>
<li>Shortcode Generator is available for Pro version only.</li>
</ul></div>

<style>
.save-changes-on {
   border:1px solid #f00;border-radius:6px;padding:2px;
   background-color:#ffffe6;
}
.save-changes {
   display:inline-block;
   border:1px solid #f1f1f1;
   border-radius:3px;
   padding:2px;
   background-color:#f1f1f1;
}
.save-changes-on {
   border-color: #f00;
   background-color:#ffffe6;
}
p.submit {
  display:inline-block !important;
  padding:0 !important;
  margin:0 !important
}
#submit-box {margin-bottom:20px}
.border-green {}
.upgrade-btn {
   color:#fff !important;
   background-color: #449d44 !important;
   border-color: #398439 !important;
}
.upgrade-btn:hover {
   background-color:#5cb85c !important;
   border-color:#4cae4c !important;
}

</style>
<form action="options.php" method="post">
<?php settings_fields('ptmgh_wp_recaptcha_options'); ?>
<input type="hidden" name="ptmgh_wp_recaptcha_options[version]" id="version" value="<?php echo $options['version']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[site_key_3_v]" id="site-key-3-v" value="<?php echo $options['site_key_3_v']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[secret_key_3_v]" id="secret-key-3-v" value="<?php echo $options['secret_key_3_v']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[site_key_2_v]" id="site-key-2-v" value="<?php echo $options['site_key_2_v']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[secret_key_2_v]" id="secret-key-2-v" value="<?php echo $options['secret_key_2_v']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[theme_v2]" id="theme-v2" value="<?php echo $options['theme_v2']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[size_v2]" id="size-v2" value="<?php echo $options['size_v2']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[site_key_i_v]" id="site-key-i-v" value="<?php echo $options['site_key_i_v']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[secret_key_i_v]" id="secret-key-i-v" value="<?php echo $options['secret_key_i_v']; ?>">
<input type="hidden" name="ptmgh_wp_recaptcha_options[position_i]" id="position" value="<?php echo $options['position_i']; ?>">
<div id="submit-box">
<div class="save-changes">
<?php submit_button();?><div style="display:inline-block;max-width:400px"><div id="save-changes"><span><?php echo __('Click Button to Save Your Updates.', 'wprecaptcha');?></span></div></div>
</div>
<a href="https://www.wprecaptcha.com/" target="_blank"><span class="button upgrade-btn" style="margin-top:3px"><?php echo __('Upgrade to Pro', 'wprecaptcha');?></span></a>
</div>

<div id="settings-auth" class="settings-tab">
<p class="description"><a href="https://www.google.com/recaptcha/intro/v3.html" target="blank"><?php echo __('Register your website with Google', 'wprecaptcha');?></a> <?php echo __('Get the required Google API keys for the selected version of reCAPTCHA, and enter them below.', 'wprecaptcha');?></p>
<div class="tab-content">
<table class="my-form-table" cellspacing="14">
<tr><th><?php echo __('Select reCAPTCHA Version', 'wprecaptcha');?></th>
<td class="radio-box" id="v" colspan="2">
<div class="radio-btn" id="v3"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Version 3', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="v2"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Version 2 - checkbox', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="vi"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Version 2 - invisible', 'wprecaptcha');?></div></div>
</td>
</tr>

<tr class="v-opt v3"><th><?php echo __('Hide Google badge', 'wprecaptcha');?></th>
<td>
<div id="hide-v3-d-box" class="ui-checkbox">
<input type="checkbox" id="hide-v3-d" name="ptmgh_wp_recaptcha_options[hide_d]" <?php checked( true, $options['hide_d'] ); ?> value="1" />
<label for="hide-v3-d"><?php echo __('Hide badge for Desktop devices', 'wprecaptcha');?></label>
</div>

<div id="hide-v3-m-box" class="ui-checkbox">
<input type="checkbox" id="hide-v3-m" name="ptmgh_wp_recaptcha_options[hide_m]" <?php checked( true, $options['hide_m'] ); ?> value="1" />
<label for="hide-v3-m"><?php echo __('Hide badge for Mobile devices', 'wprecaptcha');?></label>
</div>

</td>
</tr>

<tr class="v-opt v2">
<th><?php echo __('Version 2 Theme', 'wprecaptcha');?></th>
<td class="radio-box" id="t" colspan="2">
<div class="radio-btn" id="light"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Light', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="dark"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Dark', 'wprecaptcha');?></div></div>
</td>
</tr>

<tr class="v-opt v2">
<th><?php echo __('Version 2 Size', 'wprecaptcha');?></th>
<td class="radio-box" id="s" colspan="2">
<div class="radio-btn" id="normal"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Normal', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="compact"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Compact ', 'wprecaptcha');?></div></div>
</td>
</tr>

<tr class="v-opt vi"><th><?php echo __('Google badge position', 'wprecaptcha');?></th>
<td class="radio-box" id="p" colspan="2">
<div class="radio-btn" id="bottomright"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Bottom Right', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="bottomleft"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Bottom Left', 'wprecaptcha');?></div></div>
<div class="radio-btn" id="inline"><div class="radio-icon"></div><div class="radio-title">&nbsp;<?php echo __('Inline', 'wprecaptcha');?></div></div>
</td>
</tr>

<tr class="v-opt vi"><th><?php echo __('Hide Google badge', 'wprecaptcha');?></th>
<td>
<div id="hide-vi-d-box" class="ui-checkbox">
<input type="checkbox" id="hide-vi-d" name="ptmgh_wp_recaptcha_options[hide_vi_d]" <?php checked( true, $options['hide_vi_d'] ); ?> value="1" />
<label for="hide-vi-d"><?php echo __('Hide badge for Desktop devices', 'wprecaptcha');?></label>
</div>

<div id="hide-vi-m-box" class="ui-checkbox">
<input type="checkbox" id="hide-vi-m" name="ptmgh_wp_recaptcha_options[hide_vi_m]" <?php checked( true, $options['hide_vi_m'] ); ?> value="1" />
<label for="hide-vi-m"><?php echo __('Hide badge for Mobile devices', 'wprecaptcha');?></label>
</div>
</td>
</tr>
<!-- Version 3 -->
<?php if ($vrf3==0) {?>
<style>#auth-3 .ui-state-default {color:#f00}</style>
<?php } else { ?>
<style>#auth-3 .ui-state-default {color:#090}</style>
<?php } ?>
<tr class="v-opt v3"><td colspan="3">
<div id="auth-3">
<h3><?php echo $auth3;?></h3>
<div><table>
<tr><th><?php echo __('Version 3 Site Key', 'wprecaptcha');?></th>
<td><input type="text" id="site-key-3" name="ptmgh_wp_recaptcha_options[site_key_3]" value="<?php echo $options['site_key_3']; ?>"/>
<div class="err-message"></div>
</td>
<td class="td-ver">
<span id="ver-site-key-3" class="dashicons dashicons-yes" style="display:<?php if ($options['site_key_3_v']==0) {echo 'none';} else {echo'block';}?>">
</span></td>
</tr>
<tr><th><?php echo __('Version 3 Secret Key', 'wprecaptcha');?></th>
<td><input type="text" id="secret-key-3" name="ptmgh_wp_recaptcha_options[secret_key_3]" value="<?php echo $options['secret_key_3']; ?>"/>
<div class="err-message"></div>
</td>
<td class="td-ver">
<span id="ver-secret-key-3" class="dashicons dashicons-yes" style="display:<?php if ($options['secret_key_3_v']==0) {echo 'none';} else {echo'block';}?>">
</span></td>
</tr>
<tr><td></td>
<td style="position:relative">
<?php $this->test_buttons_block();?>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td colspan="2">
<h2>Instructions:</h2>
<table>
<tr><td>1. <a href="https://www.google.com/recaptcha/intro/v3.html" target="blank">Register your website with Google</a></td></tr>
<tr><td>2. Get the required Google API keys for the version 3 of reCAPTCHA, and enter to fields.</td></tr>
<tr><td>3. Click "Authenticate" button.</td></tr>
</table>
<h2>Error Messages:</h2>
<table>
<tr><td><span class="red"><?php echo __('Invalid site key', 'wprecaptcha');?></span></td><td> - <?php echo __('Site key not exist in Google.', 'wprecaptcha');?></td></tr>
<tr><td><span class="red"><?php echo __('Invalid domain for site key', 'wprecaptcha');?></span></td><td> - <?php echo __('Site key is for other domain.', 'wprecaptcha');?></td></tr>
<tr><td><span class="red"><?php echo __('Invalid input secret', 'wprecaptcha');?></span></td><td> - <?php echo __('Secret key is invalid.', 'wprecaptcha');?></td></tr>
</table>
</td></tr>
</table>
</div>
</div>
</td></tr>
<!-- Version 2 -->
<?php if ($vrf2==0) {?>
<style>#auth-2 .ui-state-default {color:#f00}</style>
<?php } else { ?>
<style>#auth-2 .ui-state-default {color:#090}</style>
<?php } ?>
<tr class="v-opt v2"><td colspan="3">
<div id="auth-2">
<h3><?php echo $auth2;?></h3>
<div><table>
<tr class="v-opt v2"><th><?php echo __('Version 2 Site Key', 'wprecaptcha');?></th>
<td><input type="text" id="site-key-2" name="ptmgh_wp_recaptcha_options[site_key_2]" value="<?php echo $options['site_key_2']; ?>"/>
<div class="err-message"></div>
</td>
<td class="td-ver">
<span id="ver-site-key-2" class="dashicons dashicons-yes" style="display:<?php if ($options['site_key_2_v']==0) {echo 'none';} else {echo'block';}?>">
</span></td>
</tr>
<tr class="v-opt v2"><th><?php echo __('Version 2 Secret Key', 'wprecaptcha');?></th>
<td><input type="text" id="secret-key-2" name="ptmgh_wp_recaptcha_options[secret_key_2]" value="<?php echo $options['secret_key_2']; ?>"/>
<div class="err-message"></div>
</td>
<td class="td-ver">
<span id="ver-secret-key-2" class="dashicons dashicons-yes" style="display:<?php if ($options['secret_key_2_v']==0) {echo 'none';} else {echo'block';}?>">
</span></td>
</tr>
<tr><td></td>
<td style="position:relative;height:80px">
<?php $this->test_buttons_block();?>
<div id="g-recaptcha2" style="position:absolute;right:0;top:0"></div>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td colspan="2">
<h2>Instructions:</h2>
<table>
<tr><td>1. <a href="https://www.google.com/recaptcha/intro/v3.html" target="blank">Register your website with Google</a></td></tr>
<tr><td>2. Get the required Google API keys for the version 2 of reCAPTCHA (checkbox), and enter to fields.</td></tr>
<tr><td>3. Click "Authenticate" button.</td></tr>
<tr><td>4. Click "I'm not Robot" checkbox.</td></tr>
</table>
<h2>Error Messages:</h2>
<table>
<tr><td><span class="red"><?php echo __('Invalid site key', 'wprecaptcha');?></span></td><td> - <?php echo __('Site key not exist in Google.', 'wprecaptcha');?></td></tr>
<tr><td><span class="red"><?php echo __('Invalid domain for site key', 'wprecaptcha');?></span></td><td> - <?php echo __('Site key is for other domain.', 'wprecaptcha');?></td></tr>
<tr><td><span class="red"><?php echo __('Invalid input secret', 'wprecaptcha');?></span></td><td> - <?php echo __('Secret key is invalid.', 'wprecaptcha');?></td></tr>
</table>
</td></tr>
</table>
</div>
</div>
</td></tr>
<!-- Version Invisible -->
<?php if ($vrfi==0) {?>
<style>#auth-i .ui-state-default {color:#f00}</style>
<?php } else { ?>
<style>#auth-i .ui-state-default {color:#090}</style>
<?php } ?>
<tr class="v-opt vi"><td colspan="3">
<div id="auth-i">
<h3><?php echo $authi;?></h3>
<div><table>
<tr class="v-opt vi"><th><?php echo __('Invisible Site Key', 'wprecaptcha');?></th>
<td><input type="text" id="site-key-i" name="ptmgh_wp_recaptcha_options[site_key_i]" value="<?php echo $options['site_key_i']; ?>"/>
<div class="err-message"></div>
</td>
<td class="td-ver">
<span id="ver-site-key-i" class="dashicons dashicons-yes" style="display:<?php if ($options['site_key_i_v']==0) {echo 'none';} else {echo'block';}?>">
</span></td>
</tr>
<tr class="v-opt vi"><th><?php echo __('Invisible Secret Key', 'wprecaptcha');?></th>
<td><input type="text" id="secret-key-i" name="ptmgh_wp_recaptcha_options[secret_key_i]" value="<?php echo $options['secret_key_i']; ?>"/>
<div class="err-message"></div>
</td>
<td class="td-ver">
<span id="ver-secret-key-i" class="dashicons dashicons-yes" style="display:<?php if ($options['secret_key_i_v']==0) {echo 'none';} else {echo'block';}?>">
</span></td>
</tr>
<tr><td></td>
<td style="position:relative;height:80px">
<?php $this->test_buttons_block();?>
<div id="g-recaptchai" style="position:absolute;right:0;top:0"></div>
</td></tr>
<tr><td colspan="2"><hr></td></tr>
<tr><td colspan="2">
<h2>Instructions:</h2>
<table>
<tr><td>1. <a href="https://www.google.com/recaptcha/intro/v3.html" target="blank">Register your website with Google</a></td></tr>
<tr><td>2. Get the required Google API keys for the version 2 of reCAPTCHA (invisible), and enter to fields.</td></tr>
<tr><td>3. Click "Authenticate" button.</td></tr>
<tr><td>4. After see Google badge - Click "Submit button"</td></tr>
</table>
<h2>Error Messages:</h2>
<table>
<tr><td><span class="red"><?php echo __('Invalid site key', 'wprecaptcha');?></span></td><td> - <?php echo __('Site key not exist in Google.', 'wprecaptcha');?></td></tr>
<tr><td><span class="red"><?php echo __('Invalid domain for site key', 'wprecaptcha');?></span></td><td> - <?php echo __('Site key is for other domain.', 'wprecaptcha');?></td></tr>
<tr><td><span class="red"><?php echo __('Invalid input secret', 'wprecaptcha');?></span></td><td> - <?php echo __('Secret key is invalid.', 'wprecaptcha');?></td></tr>
</table>
</td></tr>
</table>
</div>
</div>
</td></tr>
</table>
</div>

</div>
<style>
.table-border {border:1px solid #ddd;border-radius:4px}
</style>

<div id="settings-forms" class="settings-tab"  style="display:none">
<?php if ($vrf==0) {
   echo '<p class="description" style="color:red">'. __('Please go to "Settings" tab to authenticate your API keys.', 'wprecaptcha'). '</p>';
} ?>
<div class="tab-content">
<div style="padding:20px">
<div id="check-box-off" class="checkbox-box">
<div id="register-off-box" class="ui-checkbox">
<input type="checkbox" id="register-off" name="ptmgh_wp_recaptcha_options[register_off]" <?php checked( true, $options['register_off'] ); ?> value="1" />
<label for="register-off"><?php echo __('Disable reCAPTCHA for registered users', 'wprecaptcha');?></label>
</div>
</div>
<h3><?php echo __('Enable reCAPTCHA for', 'wprecaptcha');?></h3>
<div id="accordion" style="width:500px">
<h3><?php echo __('WordPress Default Forms', 'wprecaptcha');?></h3>
<div id="check-box-default" class="checkbox-box">

<div id="login-form-box" class="ui-checkbox">
<input type="checkbox" id="login-form" name="ptmgh_wp_recaptcha_options[login_form]" <?php checked( true, $options['login_form'] ); ?> value="1" />
<label for="login-form"><?php echo __('Login Form', 'wprecaptcha');?></label>
</div>

<div id="register-form-box" class="ui-checkbox">
<input type="checkbox" id="register-form" name="ptmgh_wp_recaptcha_options[register_form]" <?php checked( true, $options['register_form'] ); ?> value="1" />
<label for="register-form"><?php echo __('Register Form', 'wprecaptcha');?></label>
</div>

<div id="lost-password-form-box" class="ui-checkbox">
<input type="checkbox" id="lost-password-form" name="ptmgh_wp_recaptcha_options[lost_password_form]" <?php checked( true, $options['lost_password_form'] ); ?> value="1" />
<label for="lost-password-form"><?php echo __('Lost Password Form', 'wprecaptcha');?></label>
</div>

<div id="reset-password-form-box" class="ui-checkbox">
<input type="checkbox" id="reset-password-form" name="ptmgh_wp_recaptcha_options[reset_password_form]" <?php checked( true, $options['reset_password_form'] ); ?> value="1" />
<label for="reset-password-form"><?php echo __('Reset Password Form', 'wprecaptcha');?></label>
</div>

<div id="comments-form-box" class="ui-checkbox">
<input type="checkbox" id="comments-form" name="ptmgh_wp_recaptcha_options[comments_form]" <?php checked( true, $options['comments_form'] ); ?> value="1" />
<label for="comments-form"><?php echo __('Comments Form', 'wprecaptcha');?></label>
</div>

</div>
<?php
   $this->external_forms($options,$plugins,$is_network);
   $this->woocommerce($options,$plugins,$is_network);
?>
</div>
<p style="color:#f00;font-weight:bold;font-size:1.6em;max-width:500px"><?php echo __('External Plugins Forms and Woocommerce Forms is available for Pro version only.', 'wprecaptcha');?></p>
<a href="https://www.wprecaptcha.com/" target="_blank"><span class="button upgrade-btn" style="margin-top:3px"><?php echo __('Upgrade to Pro', 'wprecaptcha');?></span></a>
</div>
</div>
</div>

<!-- Languages -->
<div id="settings-lang" class="settings-tab" style="display:none">
<?php if ($vrf==0) {
   echo '<p class="description" style="color:red">'. __('Please go to "Settings" tab to authenticate your API keys.', 'wprecaptcha'). '</p>';
} ?>
<div class="tab-content">
<div style="padding:20px;position:relative">
<table class="table_clean my-form-table" style="width:400px">
<tr><th><?php echo __('reCAPTCHA Language', 'wprecaptcha');?></th>
<td>
<select id="lang" name="ptmgh_wp_recaptcha_options[language]">
<?php foreach ( $languages as $code => $name ) { ?>
<option value="<?php echo esc_attr( $code ) ?>" <?php echo $options['language']  == $code ? "selected='selected'" : '' ?>><?php echo esc_html( $name ) ?></option>
<?php } ?>
</select>
</td></tr>
<tr><td colspan="2" id="check-box2" class="checkbox-box">
<div id="language-box" class="ui-checkbox">
<input type="checkbox" id="auto-language" name="ptmgh_wp_recaptcha_options[auto_language]" <?php checked( true, $options['auto_language'] ); ?> value="1" />
<label for="auto-language"><?php echo __('Switch Languages Automatically.', 'wprecaptcha');?></label>
</div>
</td></tr>
</table>
<div id="hide-language" style="position:absolute;top:0;left:0;height:70px;width:100%;opacity:0.5;background-color:#F6FBFD;display:<?php if ($options['auto_language']==0) {echo 'none';} else {echo 'block';}?>"></div>
</div>
</div>
</div>

</form>
<div id="page-loader">
<h3><?php echo __('Keys authenticate successfully completed.', 'wprecaptcha');?><br><?php echo __('Saving to database...', 'wprecaptcha');?></h3>
<img src="<?php echo WP_RECAPTCHA_URL;?>images/spinner.gif" width="28" height="28" style="display:block; margin:0 auto">
</div>
</div>
<button class="recaptcha-invisible" style="display:none"></button>
<script>
var siteURL='<?php echo get_site_url();?>';
var plURL='<?php echo WP_RECAPTCHA_URL;?>';
var cVer='<?php echo $options['version'];?>';
var cTheme='<?php echo $options['theme_v2'];?>';
var cSize='<?php echo $options['size_v2'];?>';
var cPos='<?php echo $options['position_i'];?>';
var cID='';
var wdID='';
( function( $ ) {
	$( document ).ready( function() {
	
       $( "#check-box-externals" ).accordion({collapsible: true, active: false, heightStyle: "content"});
       $( "#accordion" ).accordion({
       collapsible: true,
       <?php if ($vrf==0) { ?>
       active: false,
       <?php } else { ?>
       active: 0,
       <?php } ?>
       heightStyle: "content"});

       $( "#auth-3" ).accordion({
       collapsible: true,
       <?php if ($vrf3==1) { ?>
       active: false,
       <?php } else { ?>
       active: 0,
       <?php } ?>
       heightStyle: "content"});

       $( "#auth-2" ).accordion({
       collapsible: true,
       <?php if ($vrf2==1) { ?>
       active: false,
       <?php } else { ?>
       active: 0,
       <?php } ?>
       heightStyle: "content"});

       $( "#auth-i" ).accordion({
       collapsible: true,
       <?php if ($vrfi==1) { ?>
       active: false,
       <?php } else { ?>
       active: 0,
       <?php } ?>
       heightStyle: "content"});
    
       $(".ptmbg-settings-div input[type='checkbox']").each(function() {
           updateCheckbox($(this).attr("id"));
       });

       $('.radio-icon').each(function() {
          if ($(this).hasClass('radio-on')) {
             $(this).removeClass('radio-on');
             $(this).next().removeClass('radio-title-on');
          }
       });

	   $('#v'+cVer+' .radio-icon').addClass('radio-on');
	   $('#v'+cVer+' .radio-title').addClass('radio-title-on');
	   $('.v'+cVer).css('display','table-row');
       $('.h-v').hide();
       $('.h-'+cVer).show();

	   $('#'+cTheme+' .radio-icon').addClass('radio-on');
	   $('#'+cTheme+' .radio-title').addClass('radio-title-on');

	   $('#'+cSize+' .radio-icon').addClass('radio-on');
	   $('#'+cSize+' .radio-title').addClass('radio-title-on');

	   $('#'+cPos+' .radio-icon').addClass('radio-on');
	   $('#'+cPos+' .radio-title').addClass('radio-title-on');

	   $('#v .radio-btn').on('click',function() {
          var id=$(this).attr('id');
          $('#v .radio-icon').each(function() {
             if ($(this).hasClass('radio-on')) {
                $(this).removeClass('radio-on');
                $(this).next().removeClass('radio-title-on');
             }
          });
	      $('#'+id+' .radio-icon').addClass('radio-on');
	      $('#'+id+' .radio-title').addClass('radio-title-on');
	      $('#version').val(id.replace("v",""));
	      $('#settings-auth .v-opt').hide();
	      $('.'+id).css('display','table-row');
          $('.h-v').hide();
          $('.h-'+id).show();
          showSaveMessage();
       });

   	   $('#t .radio-btn').on('click',function() {
          var id=$(this).attr('id');
          $('#t .radio-icon').each(function() {
             if ($(this).hasClass('radio-on')) {
                $(this).removeClass('radio-on');
                $(this).next().removeClass('radio-title-on');
             }
          });
	      $('#'+id+' .radio-icon').addClass('radio-on');
	      $('#'+id+' .radio-title').addClass('radio-title-on');
	      $('#theme-v2').val(id);
          showSaveMessage();
       });

   	   $('#s .radio-btn').on('click',function() {
          var id=$(this).attr('id');
          $('#s .radio-icon').each(function() {
             if ($(this).hasClass('radio-on')) {
                $(this).removeClass('radio-on');
                $(this).next().removeClass('radio-title-on');
             }
          });
	      $('#'+id+' .radio-icon').addClass('radio-on');
	      $('#'+id+' .radio-title').addClass('radio-title-on');
	      $('#size-v2').val(id);
          showSaveMessage();
       });
   	   $('#p .radio-btn').on('click',function() {
          var id=$(this).attr('id');
          $('#p .radio-icon').each(function() {
             if ($(this).hasClass('radio-on')) {
                $(this).removeClass('radio-on');
                $(this).next().removeClass('radio-title-on');
             }
          });
	      $('#'+id+' .radio-icon').addClass('radio-on');
	      $('#'+id+' .radio-title').addClass('radio-title-on');
	      $('#position').val(id);
          showSaveMessage();
       });
       $('.nav-settings .nav-tab').on('click',function() {
          var id=$(this).attr('id');
          $('.nav-settings .nav-tab').each(function() {
             if ($(this).hasClass('nav-tab-active')) {
                $(this).removeClass('nav-tab-active');
             }
          });
          $('.ptmbg-settings-div .settings-tab').each(function() {
             $(this).hide();
          });
          $('#'+id).addClass('nav-tab-active');
          $('#settings-'+id.replace('nav-','')).show();
          if ((id=='nav-shortcode') || (id=='nav-guide')) {
             $('#submit-box').hide();
          } else {
             $('#submit-box').show();
          }
          generateShortCode();
       });

       $('input[type="text"]').focus(function() {
           $('.border-red').removeClass('border-red');
           $('.border-green').removeClass('border-green');
           $('.err-message').hide();
       });

       $('#score-v3').on('change',function() {
           $('#val-score-3').html($(this).val());
       });

       $('#settings-auth input[type="text"]').on('input', function() {
          var id=$(this).attr('id');
          $('#ver-'+id).hide();
          $('#'+id+'-v').val('0');
       });
       $('#check-box-externals').tooltip({
          show: {effect: "slideDown",delay: 250},
          position: { my: "left+15 center", at: "right center" },
          classes: {"ui-tooltip": "highlight"}
       });
       $('.ptmbg-settings-div input[type="radio"],.ptmbg-settings-div input[type="checkbox"]').on('click',function() {
          var id=$(this).attr('id');
          if (!$("#"+id).parents('#check-box-externals').length) {
              showSaveMessage();
          }
       });
       $('.check-btn, .verify-btn').hide();
       resetShortCode();
       <?php if ($vrf==1) {?>
       $('#nav-forms').click();
       <?php }?>
	});

    function resetRadio() {
       $('.radio-icon').each(function() {
          if ($(this).hasClass('radio-on')) {
             $(this).removeClass('radio-on');
             $(this).next().removeClass('radio-title-on');
          }
       });
    }

} )( jQuery );

function checkCheckbox(id) {
   if (jQuery("#"+id).is (":checked")) {
      jQuery('#checkbox-'+id).removeClass("ui-checkbox-on");
      jQuery('#checkbox-'+id).addClass("ui-checkbox-off");
      if (jQuery("#"+id).parents('#check-box-externals').length) {
         jQuery("#"+id+'-message').html('<?php echo __('Click the "Save Changes" button to deactivate reCAPTCHA for this form.', 'wprecaptcha');?>');
         jQuery("#"+id+'-message').show();
      }
   } else {
      jQuery('#checkbox-'+id).removeClass("ui-checkbox-off");
      jQuery('#checkbox-'+id).addClass("ui-checkbox-on");
      if (jQuery("#"+id).parents('#check-box-externals').length) {
         jQuery("#"+id+'-message').html('<?php echo __('Click the "Save Changes" button to activate reCAPTCHA for this form.', 'wprecaptcha');?>');
         jQuery("#"+id+'-message').show();
      }
   }
   jQuery("#"+id).click();
   if (id=='auto-language') {
      if (jQuery("#checkbox-"+id).hasClass('ui-checkbox-on')) {
          jQuery("#hide-language").show();
      } else {
          jQuery("#hide-language").hide();
      }
   }
}
</script>
<?php
   }
   // Test Buttons
   public function test_buttons_block() { ?>
<div class="button-primary check-btn" onClick="checkWpRecaptcha()"><?php echo __('Submit', 'wprecaptcha');?></div>
<div class="button-primary verify-btn" onClick="verifyWpRecaptcha()"><?php echo __('Verify reCAPTCHA', 'wprecaptcha');?></div>
<div class="button-primary test-btn" onClick="testWpRecaptcha()"><?php echo __('Authenticate', 'wprecaptcha');?></div>
<img class="ajax-loader" src="<?php echo WP_RECAPTCHA_URL;?>images/spinner.gif" width="28" height="28">
<?php
   }
   // Test Keys
   public function test_keys() {
      $version=sanitize_text_field($_POST['version']);
      $response_token = sanitize_text_field($_POST['token']);
      $secret_key = sanitize_text_field($_POST['gc_key']);
      $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
      $response = wp_remote_post( $verify_url, array(
			'method' => 'POST',
			'body'   => array(
				'secret'   => $secret_key,
				'response' => $response_token,
				'remoteip' => $_SERVER["REMOTE_ADDR"]
			)
      ));
      if ( ! is_wp_error( $response ) ) {
         $data = json_decode(wp_remote_retrieve_body( $response ),true);
         $data['error']=trim($data['error-codes'][0]);
         echo json_encode($data);
      } else {
		 echo json_encode(array('error'=>$response->get_error_message()));
      }
      exit;
   }
   // Init plugin
   public function init_plugin() {
      $this->options = get_option('ptmgh_wp_recaptcha_options');
      if (($this->options['register_off'] == 1) && (is_user_logged_in())) {
         return;
      }
      $version = $this->options['version'];
      if (($this->options['site_key_'.$version.'_v'] == 1) && ($this->options['secret_key_'.$version.'_v'] == 1)) {
         $this->site_key=$this->options['site_key_'.$version];
         $this->secret_key=$this->options['secret_key_'.$version];
         $this->version=$version;
         $this->score=$this->options['score'];
         $this->lang='';
         if ($this->auto_language==0) {
            $this->lang=$this->options['language'];
         }
         $this->hide_d=$this->options['hide_d'];
         $this->hide_m=$this->options['hide_m'];
         $this->theme=$this->options['theme_v2'];
         $this->size=$this->options['size_v2'];
         $this->hide_vi_d=$this->options['hide_vi_d'];
         $this->hide_vi_m=$this->options['hide_vi_m'];

         if ((!is_user_logged_in()) && (($this->options['login_form']== 1) || ($this->options['registration_form']== 1) || ($this->options['reset_password_form']== 1) || ($this->options['lost_password_form']== 1))) {
            $action='';
            if ($_SERVER['QUERY_STRING'] != '') {
               if (isset($_GET['action'])) {
                  $action=$_GET['action'];
               } else if (isset($_GET['loggedout'])) {
                  $action='';
               }
            }
            if (($this->options['login_form']== 1) && ($action=='')) {
               add_action('login_form', array($this,recaptcha_field));
               add_action('wp_authenticate_user', array( $this, 'verify_captcha'));
            }
            if (($this->options['register_form']== 1) && ($action=='register'))  {
               add_action('register_form', array($this,recaptcha_field));
               add_action('registration_errors', array( $this, 'verify_captcha'));
            }
            if (($this->options['lost_password_form']== 1) && ($action=='lostpassword'))  {
            //if ($this->options['lost_password_form']== 1) {
               add_action('lostpassword_form', array($this,recaptcha_field));
               add_action('lostpassword_post', array( $this, 'verify_captcha'));
            }

            if (($this->options['reset_password_form']== 1) && ($action=='rp')) {
               add_action('resetpass_form', array($this,recaptcha_field));
               add_action('resetpass_post', array( $this, 'verify_captcha'));
            }
         }
         if (($this->options['comments_form']== 1) && ($placed==0)) {
            add_action('comment_form_after_fields', array($this,recaptcha_field));
            add_action('resetpass_post', array( $this, 'verify_captcha'));
         }
      }
   }
   // Verify
   public function verify_captcha($input) {
      if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["g-recaptcha-response"]))) {
         $response_token = sanitize_text_field($_POST["g-recaptcha-response"]);
         $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
         $response = wp_remote_post( $verify_url, array(
			'method' => 'POST',
			'body'   => array(
				'secret'   => $this->secret_key,
				'response' => $response_token,
				'remoteip' => $_SERVER["REMOTE_ADDR"]
			)
        ));
        if (!is_wp_error( $response ) ) {
           $data = json_decode(wp_remote_retrieve_body( $response ),true);
           if ($data["success"]) {
              if (($this->version==3) && ($data['score'] == 0 )){
                 if (is_array($input)) {
                    wp_die("<p><strong>".__("ERROR:", "wprecaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wprecaptcha")."</p>", "reCAPTCHA", array("response" => 403, "back_link" => 1));
                 } else {
                    return new WP_Error("reCAPTCHA", "<strong>".__("ERROR:", "wprecaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wprecaptcha"));
                 }
              } else {
                 return $input;
              }
           }
        }
        if (is_array($input)) {
           wp_die("<p><strong>".__("ERROR:", "wprecaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wprecaptcha")."</p>", "wprecaptcha", array("response" => 403, "back_link" => 1));
        } else {
           return new WP_Error("reCAPTCHA", "<strong>".__("ERROR:", "wprecaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wprecaptcha"));
        }
      } else {
         wp_die("<p><strong>".__("ERROR:", "wprecaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "wprecaptcha")."</p>", "wprecaptcha", array("response" => 403, "back_link" => 1));
      }
   }
   // recaptcha field
   public function recaptcha_field() {
      if ($this->version =='3') {
     ?>
      <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value=""/>
      <style>
      #wp-submit {visibility:hidden}
      #submit {visibility:hidden}
      #google-copyright {margin-bottom:10px !important;display:none;font-size:0.9em}
      <?php if ($this->hide_d==1) {?>
      #google-copyright {display:block !important}
      .grecaptcha-badge {display:none !important}
      <?php }
      if ($this->hide_m==1) {?>
      @media only screen and (max-width: 800px) {
      #google-copyright {display:block !important}
      .grecaptcha-badge {display:none !important}
      }
      <?php }
      ?>
      </style>
      <script>
      var sbtnId='wp-submit';
      var formId='';
      window.onload = function() {
         if (document.getElementById('submit')) {
            sbtnId='submit';
         }
         var script=document.createElement('script');
         script.type = 'text/javascript';
         script.src = 'https://www.google.com/recaptcha/api.js?render=<?php echo $this->site_key;?>&hl=<?php echo $this->lang;?>';
         script.onload = function() {onLoadCaptchaCallback()};
         document.body.appendChild(script);
      }
      
      function onLoadCaptchaCallback() {
         var form=document.getElementById('g-recaptcha-response').parentElement;
         formId=form.id;
         document.getElementById(formId).onsubmit = function() {
            if (document.getElementById('g-recaptcha-response').value =='') {
               document.getElementById(sbtnId).style.visibility='hidden';
                  grecaptcha.execute('<?php echo $this->site_key;?>', {action: 'homepage'}).then(function(token) {
                  document.getElementById('g-recaptcha-response').value=token;
                  setTimeout(function(){ document.getElementById(formId).submit()}, 200);
               });
               return false;
            } else {
               return true;
            }
         };
         document.getElementById(sbtnId).style.visibility='visible';
      }
      </script>
      <p id="google-copyright"><?php echo __('This site is protected by reCAPTCHA and the Google', 'wprecaptcha');?> <a href="https://policies.google.com/privacy" target="_blank"><?php echo __('Privacy Policy', 'wprecaptcha');?></a> <?php echo __('and', 'wprecaptcha');?> <a href="https://policies.google.com/terms" target="_blank"><?php echo __('Terms of Service', 'wprecaptcha');?></a> apply.</p>
      <?php } else if ($this->version =='2') { ?>
      <div id="g-recaptcha"></div>
      <style>
      #wp-submit {visibility:hidden}
      #submit {visibility:hidden}
      #login {width:350px !important}
      #g-recaptcha {margin-bottom:10px}
      </style>
      <script>
      var sbtnId='wp-submit';
      var formId='';
      var wdID=0;
      window.onload = function() {
         if (document.getElementById('submit')) {
            sbtnId='submit';
         }
         var form=document.getElementById('g-recaptcha').parentElement;
         formId=form.id;
         var script=document.createElement('script');
         script.type = 'text/javascript';
         script.src = 'https://www.google.com/recaptcha/api.js?onload=onLoadCaptchaCallback&render=explicit&hl=<?php echo $this->lang;?>';
         script.async = true;
         script.defer = true;
         script.onload = function() {onLoadCaptchaCallback()};
         document.body.appendChild(script);
      }

      function onLoadCaptchaCallback() {
         wdID=grecaptcha.render(document.getElementById('g-recaptcha'), {
            'sitekey' : '<?php echo $this->site_key;?>',
            'theme' : '<?php echo $this->theme;?>',
            'size' : '<?php echo $this->size;?>'
         });
         document.getElementById(formId).onsubmit = function() {
            if (document.getElementById('g-recaptcha-response').value =='') {
               document.getElementById(sbtnId).style.visibility='hidden';
               document.getElementById('g-recaptcha-response').value=grecaptcha.getResponse(wdID);
               setTimeout(function(){ document.getElementById(formId).submit()}, 200);
               return false;
            } else {
               return true;
            }
         };
         document.getElementById(sbtnId).style.visibility='visible';
      }

      </script>
      <?php } else if ($this->version =='i') { ?>
      <div id="g-recaptcha"></div>
      <style>
      #wp-submit {visibility:hidden}
      #submit {visibility:hidden}
      #google-copyright {margin-bottom:10px !important;display:none;font-size:0.9em}
      <?php if ($this->hide_vi_d==1) {?>
      #google-copyright {display:block !important}
      .grecaptcha-badge {display:none !important}
      <?php }
      if ($this->hide_vi_m==1) {?>
      @media only screen and (max-width: 800px) {
      #google-copyright {display:block !important}
      .grecaptcha-badge {display:none !important}
      }
      <?php }
      ?>
      </style>
      <script>
      var sbtnId='wp-submit';
      var formId='';
      var wdID=0;
      window.onload = function() {
         if (document.getElementById('submit')) {
            sbtnId='submit';
         }
         var forms=document.forms;
         for (var i=0;i< document.forms.length;i++) {
             var id=document.forms[i].id;
             if ((id=='loginform') || (id=='registerform') || (id=='lostpasswordform') || (id=='resetpassform') || (id=='commentform')) {
                formId=id;
             }
         }
         var script=document.createElement('script');
         script.type = 'text/javascript';
         script.src = 'https://www.google.com/recaptcha/api.js?onload=onLoadCaptchaCallback&render=explicit&hl=<?php echo $this->lang;?>';
         script.async = true;
         script.defer = true;
         script.onload = function() {onLoadCaptchaCallback()};
         document.body.appendChild(script);
      }

      function onLoadCaptchaCallback() {
         wdID=grecaptcha.render(document.getElementById(sbtnId), {
            'sitekey' : '<?php echo $this->site_key;?>',
            'callback' : formOnSubmit
         });

         document.getElementById(sbtnId).style.visibility='visible';
      }
      
      function formOnSubmit() {
         if (document.getElementById('g-recaptcha-response').value =='') {
            document.getElementById(sbtnId).style.visibility='hidden';
            document.getElementById('g-recaptcha-response').value=grecaptcha.getResponse(wdID);
            setTimeout(function(){ document.getElementById(formId).submit()}, 200);
         } else {
            document.getElementById(formId).submit();
         }
      }

      </script>
      <p id="google-copyright"><?php echo __('This site is protected by reCAPTCHA and the Google', 'wprecaptcha');?> <a href="https://policies.google.com/privacy" target="_blank"><?php echo __('Privacy Policy', 'wprecaptcha');?></a> <?php echo __('and', 'wprecaptcha');?> <a href="https://policies.google.com/terms" target="_blank"><?php echo __('Terms of Service', 'wprecaptcha');?></a> apply.</p>
      <?php }
   }

   public function add_external_form($title,$id,$plugin,$url,$plugins,$is_network,$pages,$posts,$slug,$options) {
      $active=0;
      $output='<a href="'.$url.'" target="_blank">'. __( 'Install Now', 'wprecaptcha' ).'</a>';
      if ( array_key_exists( $plugin, $plugins ) ) {
         if (( $is_network && is_plugin_active_for_network( $plugin ) ) || ( ! $is_network && is_plugin_active( $plugin ) )) {
            $active=1;
         } else {
           $output='<a href="' . self_admin_url( 'plugins.php' ) . '">' . __( 'Activate', 'wprecaptcha' ) . '</a>';
         }
      }
      ?>
      <h3><?php echo $title?></h3>
      <div>
      <?php
      if ($active==0) {
         echo $output;
      } else {
         $pattern='/'.$slug.'/';
         $forms=array();
         foreach ($pages as $post) {
            if ($post->post_status != 'publish') {
               continue;
            }
            if (preg_match($pattern,preg_replace("/\n|\r/",'', $post->post_content))) {
               $form=array();
               $form['id']=$post->ID;
               $form['title']=$post->post_title;
               $form['url']=$post->guid;
               $forms[]=$form;
            }
         }
         foreach ($posts as $post) {
            if ($post->post_status != 'publish') {
               continue;
            }
            if (preg_match($pattern,preg_replace("/\n|\r/",'', $post->post_content))) {
               $form=array();
               $form['id']=$post->ID;
               $form['title']=$post->post_title;
               $form['url']=$post->guid;
               $forms[]=$form;
            }
         }
         if (count($forms)==0) {
            echo 'Forms in pages or posts not found.';
         } else {
            foreach ($forms as $form) { ?>
      <div id="<?php echo $id . '-' . $form['id'];?>-box" class="ui-checkbox <?php echo $active;?>">
      <input type="checkbox" id="<?php echo $id . '-' . $form['id'];?>" name="ptmgh_wp_recaptcha_options[<?php echo $id . '_post_' . $form['id'];?>]" <?php checked( true, $options[$id. '_post_' . $form['id']] ); ?> value="1" />
      <label for="<?php echo $id . '-' . $form['id'];?>"><?php echo $form['title'];?></label>
      <div class="check-box-right" title="<?php echo __('Click Icon to View Form', 'wprecaptcha');?>"><a href="<?php echo $form['url'];?>" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span></a></div>
      </div>
      <div id="<?php echo $id . '-' . $form['id'];?>-message" style="color:red;font-size:0.8em"></div>
      <?php
            }
         }
      }
      echo '</div>';
   }
   // Woocommerce
   public function woocommerce($options,$plugins,$is_network) { ?>
<h3 class="pro-version"><?php echo __('Woocommerce Forms', 'wprecaptcha');?></h3>
<div id="check-box-woocommerce" class="checkbox-box pro-version">
<?php
      if ($this->check_plugin('woocommerce/woocommerce.php','https://wordpress.org/plugins/woocommerce/',$plugins,$is_network)) {
?>

<div id="woocommerce-login-form-box" class="ui-checkbox">
<input type="checkbox" id="woocommerce-login-form" name="ptmgh_wp_recaptcha_options[woc_login_form]" <?php checked( true, $options['woc_login_form'] ); ?> value="1" />
<label for="woocommerce-login-form"><?php echo __('Login Form', 'wprecaptcha');?></label>
</div>

<div id="woocommerce-register-form-box" class="ui-checkbox">
<input type="checkbox" id="woocommerce-register-form" name="ptmgh_wp_recaptcha_options[woc_register_form]" <?php checked( true, $options['woc_register_form'] ); ?> value="1" />
<label for="woocommerce-register-form"><?php echo __('Register Form', 'wprecaptcha');?></label>
</div>

<div id="woocommerce-lost-password-form-box" class="ui-checkbox">
<input type="checkbox" id="woocommerce-lost-password-form" name="ptmgh_wp_recaptcha_options[woc_lost_password_form]" <?php checked( true, $options['woc_lost_password_form'] ); ?> value="1" />
<label for="woocommerce-lost-password-form"><?php echo __('Lost Password Form', 'wprecaptcha');?></label>
</div>

<div id="woocommerce-reset-password-form-box" class="ui-checkbox">
<input type="checkbox" id="woocommerce-reset-password-form" name="ptmgh_wp_recaptcha_options[woc_reset_password_form]" <?php checked( true, $options['woc_reset_password_form'] ); ?> value="1" />
<label for="woocommerce-reset-password-form"><?php echo __('Reset Password Form', 'wprecaptcha');?></label>
</div>

<?php if ($options['version'] != 'i') { ?>
<div id="woocommerce-order-form-box" class="ui-checkbox">
<input type="checkbox" id="woocommerce-order-form" name="ptmgh_wp_recaptcha_options[woc_order_form]" <?php checked( true, $options['woc_order_form'] ); ?> value="1" />
<label for="woocommerce-order-form"><?php echo __('Order Form', 'wprecaptcha');?></label>
</div>
<?php } ?>
</div>
<?php
      } else {
         echo '</div>';
      }
   }

   public function check_plugin($plugin,$url,$plugins,$is_network) {
      if ( array_key_exists( $plugin, $plugins ) ) {
         if (( $is_network && is_plugin_active_for_network( $plugin ) ) || ( ! $is_network && is_plugin_active( $plugin ) )) {
            return true;
         } else {
           echo '<a href="' . self_admin_url( 'plugins.php' ) . '">' . __( 'Activate', 'wprecaptcha' ) . '</a>';
           return false;
         }
      } else {
         echo '<a href="'.$url.'" target="_blank">'. __( 'Install Now', 'wprecaptcha' ).'</a>';
         return false;
      }
   }
   
   public function external_forms($options,$plugins) {
      $pages=get_pages();
      $posts=get_posts();
      echo '<h3>External Plugins Forms</h3>';
      echo '<div id="check-box-externals">';
      // Gravity Forms
      $this->add_external_form(__('Gravity Forms', 'wprecaptcha'),'gf','gravityforms/gravityforms.php','https://www.gravityforms.com',$plugins,$is_network,$pages,$posts,'\[gravityform',$options);
      // Contact Form 7
      $this->add_external_form(__('Contact Form 7', 'wprecaptcha'),'cf7','contact-form-7/wp-contact-form-7.php','https://wordpress.org/plugins/contact-form-7/',$plugins,$is_network,$pages,$posts,'\[contact-form-7 ',$options);
      // Jetpack Contact Form
      $this->add_external_form(__('Jetpack Contact Form', 'wprecaptcha'),'jetpack','jetpack/jetpack.php','https://wordpress.org/plugins/jetpack/',$plugins,$is_network,$pages,$posts,'wp\:jetpack\/contact-form ',$options);
      // Contact Form by WPForms
      $this->add_external_form(__('Contact Form by WPForms', 'wprecaptcha'),'wpforms','wpforms-lite/wpforms.php','https://wordpress.org/plugins/wpforms-lite/',$plugins,$is_network,$pages,$posts,'\[wpforms ',$options);
      // MailChimp for Wordpress
      $this->add_external_form(__('MailChimp for Wordpress', 'wprecaptcha'),'mc4wp','mailchimp-for-wp/mailchimp-for-wp.php','https://wordpress.org/plugins/mailchimp-for-wp/',$plugins,$is_network,$pages,$posts,'\[mc4wp_form ',$options);
      echo '</div>';
   }
   
   public function deactivation_feedback() {  ?>
<style>
#deactivate-feedback {
  position: fixed;
  overflow: auto;
  height: 100%;
  width: 100%;
  top: 0;
  z-index: 100000;
  background: rgba(0, 0, 0, 0.6);
  display:none;
}

.deactivate-feedback-content {
  width:600px;
  margin:0 auto;
  margin-top:32px;
  background-color:#fff;
  padding:1px 20px 20px 20px;
  position:relative;

}

#deactivate-feedback .radio-btn {cursor:pointer;margin:4px 0}
#deactivate-feedback textarea {width:100%}
#deactivate-feedback .ui-checkbox {left:-8px}
.fb-border-red {border:1px solid #f00 !important}

#deactivate-feedback .fb-err-message {
  position:absolute;
  top:-10px;
  left:20px;
  padding:2px;
  color:#f00;
  background-color:#fff;
  display:none;
}
.feedback-ajax-loader {
    margin:auto;
    position:absolute;
    top:0;
    left:0;
    bottom:0;
    right:0;
    display:none;
}
</style>
<div id="deactivate-feedback">
<div class="deactivate-feedback-content">
<div id="deactivate-feedback-content">
<h1>Quick Feedback</h1>
<p>If you have a moment, please let us know why you are deactivating:</p>
<div class="radio-btn" id="i1"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;The plugin is not working</div></div>
<div class="text-area" id="t1"></div>
<div class="radio-btn" id="i2"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;The plugin didn't work as expected</div></div>
<div class="text-area" id="t2"></div>
<div class="radio-btn" id="i3"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;The plugin suddenly stopped working</div></div>
<div class="text-area" id="t3"></div>
<div class="radio-btn" id="i4"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;The plugin broke my site</div></div>
<div class="text-area" id="t4"></div>
<div class="radio-btn" id="i5"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;I couldn't understand how to get it work</div></div>
<div class="text-area" id="t5"></div>
<div class="radio-btn" id="i6"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;I found a better plugin</div></div>
<div class="text-area" id="t6"></div>
<div class="radio-btn" id="i7"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;The plugin is great, but I need specific feature that you don't support</div></div>
<div class="text-area" id="t7"></div>
<div class="radio-btn" id="i8"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;I no longer need the plugin</div></div>
<div class="text-area" id="t8"></div>
<div class="radio-btn" id="i9"><div class="radio-icon radio-off"></div><div class="radio-title">&nbsp;It's a temporary deactivation, I'm just debugging an issue</div></div>
<div class="text-area" id="t9"></div>
<div class="radio-btn" id="i10"><div class="radio-icon radio-on"></div><div class="radio-title" style="font-weight:bold">&nbsp;Other</div></div>
<div class="text-area" id="t10"><div style="position:relative"><textarea class="fb-comment" maxlength="250" placeholder="Please specify" onFocus="hideFbErrors()"></textarea><span class="fb-err-message">Field is required.</span></div></div>
<div class="ui-checkbox">
<div onclick="checkboxSiteData()" id="checkbox-site-data" class="ui-pointer ui-btn-icon-left ui-checkbox-on" style="text-overflow:clip">Send installed plugis info and allow to contact me back</div>
</div>
<div style="border-top:1px solid #dedede;margin-top:10px;padding-top:10px;text-align:right">
<span>
<span class="button" style="width:100px;text-align:center" onClick="jQuery('#deactivate-feedback').fadeOut('slow');">Close</span>
<span class="button button-primary" style="text-align:center" onClick="sendFeedBack();">
<span class="dashicons dashicons-email" style="position:relative;top:5px"></span>&nbsp;Send & Deactivate</span>
</span>
</div>
</div>
<img class="feedback-ajax-loader" src="<?php echo WP_RECAPTCHA_URL;?>images/spinner.gif" width="48" height="48">
</div>
</div>
<script>
var deactivateLink='';
( function( $ ) {
	$( document ).ready( function() {
       deactivateLink = $( '#the-list .active[data-plugin="wprecaptcha/index.php"] .deactivate a' );
       deactivateLink.click( function( e ) {
	      e.preventDefault();
          $('#deactivate-feedback').fadeIn('slow');
       });

       $('#deactivate-feedback .radio-btn').on("click", function() {
          var $this=$(this);
          $('#deactivate-feedback .radio-icon').each(function() {
             if ($(this).hasClass("radio-on")) {
                 $(this).removeClass("radio-on");
                 $(this).addClass("radio-off");
                 var id=$(this).parent().attr('id');
                 $('#'+id+' .radio-title').css("font-weight","normal");
             }
          });
          $('.text-area').html('');
          var id=$this.attr('id');
          $('#'+id+' .radio-icon').removeClass("radio-off");
          $('#'+id+' .radio-icon').addClass("radio-on");
          $('#'+id+' .radio-title').css("font-weight","bold");
          var html='';
          if (id=='i1') {
             html='<div style="position:relative"><textarea maxlength="250" class="fb-comment" placeholder="Kindly share what didn&rsquo;t work so we can fix it in future updates..." onFocus="hideFbErrors()"></textarea><span class="fb-err-message">Field is required.</span></div>';
          } else if (id=='i2') {
             html='<div style="position:relative"><textarea maxlength="250" class="fb-comment" placeholder="What did you expect?" onFocus="hideFbErrors()"></textarea><span class="fb-err-message">Field is required.</span></div>';
          } else if (id=='i3') {
             html='<div style="border:1px solid #dedede;padding:2px;margin-bottom:2px">Need help? We are ready to answer your questions. <a href="http://support.wprecaptcha.com/" target="_blank">Contact Support</a></div>';
          } else if (id=='i4') {
             html='<div style="border:1px solid #dedede;padding:2px;margin-bottom:2px">Need help? We are ready to answer your questions. <a href="http://support.wprecaptcha.com/" target="_blank">Contact Support</a></div>';
          } else if (id=='i5') {
             html='<div style="border:1px solid #dedede;padding:2px;margin-bottom:2px">Need help? We are ready to answer your questions. <a href="http://support.wprecaptcha.com/" target="_blank">Contact Support</a></div>';
          } else if (id=='i6') {
             html='<div style="position:relative"><input maxlength="250" type="text" class="fb-comment" placeholder="What&rsquo;s the plugin name?" onFocus="hideFbErrors()"><span class="fb-err-message">Field is required.</span></div>';
          } else if (id=='i7') {
             html='<div style="position:relative"><textarea maxlength="250" class="fb-comment" placeholder="What feature?" onFocus="hideFbErrors()"></textarea><span class="fb-err-message">Field is required.</span></div>';
          } else if (id=='i10') {
             html='<div style="position:relative"><textarea maxlength="250" class="fb-comment" placeholder="Please specify" onFocus="hideFbErrors()"></textarea><span class="fb-err-message">Field is required.</span></div>';
          }
          id=id.replace(/\D/,'');
          $('#t'+id).html(html);
       });
    });
} )( jQuery );

function checkboxSiteData() {
   if (jQuery('#checkbox-site-data').hasClass('ui-checkbox-on')) {
      jQuery('#checkbox-site-data').removeClass('ui-checkbox-on');
      jQuery('#checkbox-site-data').addClass('ui-checkbox-off');
   } else {
      jQuery('#checkbox-site-data').removeClass('ui-checkbox-off');
      jQuery('#checkbox-site-data').addClass('ui-checkbox-on');
   }
}

function hideFbErrors() {
   jQuery('.fb-border-red').removeClass('fb-border-red');
   jQuery('.fb-err-message').hide();
}

function sendFeedBack() {
   var comment='';
   if (jQuery('#deactivate-feedback .fb-comment').length) {
      comment=jQuery('#deactivate-feedback .fb-comment').val().trim();
      if (comment == '') {
         jQuery('#deactivate-feedback .fb-comment').addClass('fb-border-red');
         jQuery('#deactivate-feedback .fb-err-message').show();
         return;
      }
   }
   var issue ='';
   jQuery('#deactivate-feedback .radio-icon').each(function() {
      if (jQuery(this).hasClass("radio-on")) {
         var id=jQuery(this).parent().attr('id');
         issue=jQuery('#'+id+' .radio-title').html();
      }
   });
   var siteinfo=0;
   if (jQuery('#checkbox-site-data').hasClass('ui-checkbox-on')) {
      siteinfo=1;
   }
   jQuery('#deactivate-feedback-content').css("visibility","hidden");
   jQuery('.feedback-ajax-loader').show();
   jQuery.post('<?php echo get_site_url();?>/wp-admin/admin-ajax.php', {
      action: "wp-recaptcha-feedback",
      issue:issue,
      comment:comment,
      siteinfo:siteinfo
      },
      function(data){
         window.location.href = deactivateLink.attr('href');
      }
   );
}


</script>
<?php
   }
   
   public function send_feedback() {
      global $wp_version;
      $current_user = wp_get_current_user();
      $options=array();
      $options['issue']=trim(sanitize_text_field($_POST['issue']));
      $options['comment']=trim(sanitize_text_field($_POST['comment']));
      $options['name'] = get_bloginfo( 'name' );
      $options['url'] = get_bloginfo( 'url' );
      $options['ip'] = $_SERVER['SERVER_ADDR'];
      $options['language'] = get_bloginfo( 'language' );
      $options['email'] = $current_user->data->user_email;
      $options['plugin'] = 'WP reCAPTCHA - Lite';
      $options['plugin_version'] = WP_RECAPTCHA_VERSION;
      $options['wp_version'] = $wp_version;
      $options['plugins']='';
      $options['theme']='';
      if (trim(sanitize_text_field($_POST['siteinfo']==1))) {
          $plugins=get_plugins();
          $info=array();
          foreach ($plugins as $plugin) {
            $info[]=$plugin['Name'] . ' - ' . $plugin['Version'] . ' ('. $plugin['PluginURI'] .')';
          }
          $options['plugins'] = implode(', ',$info);
          $theme=wp_get_theme();
          $options['theme'] = $theme->get('Name') . ' - ' . $theme->get('Version') . ' (' . $theme->get('ThemeURI') . ')';
      }
      $response=wp_remote_post( 'https://www.wprecaptcha.com/deactivation-feedback/', array(
			'method'  => 'POST',
			'body'    => $options,
			'timeout' => 15,
      ));
      print_r($response);
   }
}
endif; // End If class exists check.
$wp_recaptcha=new WP_recaptcha;
add_action( 'plugins_loaded', array( $wp_recaptcha, 'load_plugin'));

