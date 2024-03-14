<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
 
/**
* Plugin Name: Eazy Ad Unblocker
* Plugin URI: https://myplugins.net/demo
* Description: Prevent ad blockers from blocking ads on your site. 
* Version: 1.2.3
* Author: Pratyush Deb
* Author URI: https://myplugins.net/
* Text Domain: eazy-ad-unblocker
* Domain Path: /languages
**/

require("constants/constants.php");

$eazyAdUnblockerObj = new EazyAdUnblockerHideRestore(); //oops techniques
 
class EazyAdUnblocker
{
	protected $eazy_ad_unblocker_globalstyles;
	
	public function __construct()
	{
		//all plugin actions here
		$this->eazy_ad_unblocker_globalstyles = array("smoothness"=>plugins_url('css/themes/smoothness/smoothness.png', __FILE__),
										"redmond" => plugins_url('css/themes/redmond/redmond.png', __FILE__), 
										"ui-lightness"=>plugins_url('css/themes/ui-lightness/ui-lightness.png', __FILE__),
										"ui-darkness"=>plugins_url('css/themes/ui-darkness/ui-darkness.png', __FILE__),
										"le-frog"=>plugins_url('css/themes/le-frog/le-frog.png', __FILE__),
										"blitzer"=>plugins_url('css/themes/blitzer/blitzer.png', __FILE__)
										);
										
		add_action("wp_head", array($this, "eazy_ad_unblocker_dialog_head_func")); //styles and scripts
		
		add_action( 'wp_enqueue_scripts', array($this, 'eazy_ad_unblocker_add_jquery_ui')); //dialog js files

		add_action("wp_footer", array($this, "eazy_ad_unblocker_func_frontend")); //line up the dialog HTML
		
		add_action("admin_menu", array($this, "eazy_ad_unblocker_admin_callback")); //admin settings screen
		
		add_action('admin_post', array($this, 'eazy_ad_unblocker_admin_redirects')); //admin forms submit redirects
		
		add_filter( 'mce_buttons_2', array($this, 'eazy_ad_unblocker_editor_buttons')); //remove font selector
		
		add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'eazy_ad_unblocker_add_plugin_page_settings_link')); //add settings link to listing
		
		add_action( 'plugins_loaded', array($this, 'eazy_ad_unblocker_load_textdomain')); //load translations  
		
		add_action('add_meta_boxes', array($this, 'eazy_ad_unblocker_custom_metabox')); //add per page disable metaboxes
		
		add_action( 'save_post', array($this, 'eazy_ad_unblocker_save_per_page_disable')); //save metabox

		add_filter("wp_kses_allowed_html", array($this, "eazy_ad_unblocker_allow_script"), 1 ); //brave problem
		
		add_filter('auto_update_plugin', array($this, "eazy_ad_unblocker_hide_auto_update_link"), 10, 2);  //disable plugin auto-update link
		
		register_activation_hook( __FILE__, array($this, 'eazy_ad_unblocker_activate_func')); //activation hook
		
		register_uninstall_hook( __FILE__, array('EazyAdUnblocker', 'eazy_ad_unblocker_uninstall_func')); //uninstall hook
		
	}
	
	public function eazy_ad_unblocker_dialog_head_func()
	{
		//begin 
		global $post;
		
		//July 25 2021
		if(is_singular())
		{
			$eazy_ad_unblocker_val = get_post_meta($post->ID, "eazy_ad_unblocker_per_page_disabled", true);
			
			if($eazy_ad_unblocker_val == "yes")
			{
				return;
			}
		}
		//End 
		
		//redmond
		//Feb 25 2021
		$eazy_ad_unblock_option = get_option("eazy-ad-unblocker-settings");
		
		$eazy_ad_unblocker_saved_style = "redmond";
		
		if(isset($eazy_ad_unblock_option["eazy_style_name"]))
		{
			$eazy_ad_unblocker_saved_style = $eazy_ad_unblock_option["eazy_style_name"];
		}
		
		//$eazy_ad_unblocker_style_src = 'css/themes/'.$eazy_ad_unblocker_style.'/jquery-ui.min.css.php';
		
		$eazy_ad_unblocker_style_src = 'css/themes/'.$eazy_ad_unblocker_saved_style.'/jquery-ui.min.css.php';
		
		//$eazy_ad_unblocker_style_src = 'css/themes/'.$eazy_ad_unblocker_style.'/jquery-ui.'.$eazy_ad_unblocker_style.'.min.css.php';
		
		wp_enqueue_style("eazy-jquery-ui-css", plugins_url($eazy_ad_unblocker_style_src, __FILE__)); //include css locally
		//Feb 25 2021
		
		wp_enqueue_style("eazy-custom-ui-css", plugins_url('css/style.css.php', __FILE__), array(), '1.1.12');
	}
	
	public function eazy_ad_unblocker_add_jquery_ui()
	{
		//begin 
		global $post;
		
		//July 25 2021
		if(is_singular())
		{
			$eazy_ad_unblocker_val = get_post_meta($post->ID, "eazy_ad_unblocker_per_page_disabled", true);
			
			if($eazy_ad_unblocker_val == "yes")
			{
				return;
			}
		}
		//End 
		
		
		if ( ! wp_script_is( 'jquery'))
		{
			//Enqueue
			wp_enqueue_script( 'jquery' ); 
		}

		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-mouse');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-button');
		
		wp_enqueue_script('jquery-ui-position');
		
		wp_enqueue_script( "eazy_custom", plugins_url("js/custom.js", __FILE__), array('jquery'), '1.1.12', true );
	}
	
	public function eazy_ad_unblocker_func_frontend()
	{
		@session_start();
		
		//Oct 24 2021 widget fix
		if(is_admin() && is_dynamic_sidebar())
		{
			return;
		}
		//End Oct 24 2021
		
		//begin 
		global $post;
		
		//July 25 2021
		if(is_singular())
		{
			$eazy_ad_unblocker_val = get_post_meta($post->ID, "eazy_ad_unblocker_per_page_disabled", true);
			
			if($eazy_ad_unblocker_val == "yes")
			{
				return;
			}
		}
		//End 
		
		$unblock_option = get_option("eazy-ad-unblocker-settings");
		
		$unblock_option = sanitize_option( "eazy-ad-unblocker-settings", $unblock_option ); //sanitize retrieved option
		
		$unblock_array = $unblock_option;
		
		$content = $this->eazy_ad_unblocker_get_content($unblock_array["text"]);
			
		$title = $unblock_array["title"];
			
		$opacity = $unblock_array["opacity"];
		
		wp_localize_script("eazy_custom", "eazy_opacity", array("opacity"=>$opacity));
		
		//June 23, 2020 pass admin btn value
		$admin_btn_show = '';
		if(isset($unblock_array["close_btn"]))
		{
			$admin_btn_show = $unblock_array["close_btn"];
		}
		
		wp_localize_script("eazy_custom", "eazy_close_btn", array("admin_btn_show"=>$admin_btn_show));
		//End June 23, 2020 pass admin btn value end
		
		/*****July 11 2020****/
		$version_flag = version_compare(get_bloginfo('version'),'4.1', '<');
		
		wp_localize_script("eazy_custom", "eazy_version", array("version_flag"=>$version_flag));
		
		/****July 11 2020 end****/
		
		/***Dec 21 2020****/
		//put value of width in custom.js
		
		if(isset($unblock_array["unblocker_width"]))
		{
			$eff_width = intval($unblock_array["unblocker_width"]);
		}
		else
		{
			$eff_width = '';
		}
		
		wp_localize_script("eazy_custom", "eazy_unblocker_width", array("unblocker_width"=> $eff_width));
		/***Dec 21 2020 End***/
		
		/****March 22 2021 Begin*****/
		
		$eazy_ad_unblocker_popup_id = substr(md5(microtime()), 0, 12);
		
		if(isset($_SESSION["eazy_ad_rand_words"]))
		{
			$eazy_ad_unblocker_popup_id = $_SESSION["eazy_ad_rand_words"][7].'-'.$eazy_ad_unblocker_popup_id;
		}
		
		wp_localize_script("eazy_custom", "eazy_ad_unblocker_popupid", array("unblocker_id"=> $eazy_ad_unblocker_popup_id));
		
		/****March 22 2021 End*****/
		
		/****March 25 2021 begin***/
		
		global $eazy_ad_unblocker_popup_params_array;
		
		wp_localize_script("eazy_custom", "eazy_ad_unblocker_popup_params", $eazy_ad_unblocker_popup_params_array);
		
		/****March 25 2021 end***/
		
		?>
		<!--loader-->
		<div id="eazy_ad_unblocker_loading"></div>
		<!--loader end-->
		<div style="display:none"><!--footer fix-->
		<div id="<?php echo $_SESSION['EAZY_AD_UNBLOCKER_DIALOG_MESSAGE_ID']; //$_COOKIE ?>" title="<?php echo esc_attr($title); //eazy_ad_unblocker_dialog-message?>">
		  <?php 
				$content = wp_check_invalid_utf8( $content, true );
				
				//protect oEmbed
				$allowed_html = array(
									  "iframe"=>array("src"=>array(), 
														"width"=>array(), 
														"height"=>array(), 
														"border"=>array(), 
														"class"=>array(),
														"name"=>array(),
														"id"=>array()),
									  "img"=>array("src"=>array(), 
													"alt"=>array(), 
													"width"=>array(), 
													"height"=>array(), 
													"class"=>array(),
													"id"=>array()),
									  "audio"=>array("width"=>array(), 
													 "height"=>array(), 
													 "controls"=>array("yes")), 
									  "video"=>array("width"=>array(), 
													"height"=>array(), 
													"controls"=>array("yes")), 
									  "source"=>array("src"=>array(), 
													"type"=>array("video/mp4",
																  "video/m4v",
																  "video/webm",
																  "video/ogv",
																  "video/x-ms-wmv",
																  "video/flv", 
																  "audio/ogg", 
																  "audio/mp3",
																  "audio/m4a",
																  "audio/wav",
																  "audio/wma") 
													)
										); //"video/mp4", "video/ogg", "audio/ogg", "audio/mpeg"
				
				$allowed_html = array_merge(wp_kses_allowed_html( "post" ), $allowed_html);
				
				$content = wp_kses($content, $allowed_html, array("http", "https")); //allowed html only
				
				$content = do_shortcodes_in_html_tags( $content, true, array());
				
				echo $content;
		  ?>
		  <!--refresh button--eazy-ad-unblocker-refresh-btn-->
		  <button class="ui-button ui-widget ui-corner-all <?php echo $_SESSION['EAZY_AD_UNBLOCKER_REFRESH_BTN_CLASS']; ?>" onclick="javascript:location.reload();">
		  <img src="<?php echo plugins_url('images/refresh.png', __FILE__); ?>" alt="Refresh" />
		  </button>
		  <!--end refresh button-->
		</div> 
	</div><!--End footer fix-->
		<style>
			.adsBanner{
					background-color: transparent;
					height: 1px;
					width: 1px;
				}
				
				#eazy_ad_unblocker_loading{
					position: absolute;
					top: 0px;
					left: 0px;
					width: 100%;
					height: 100%;
					background: #fff url('<?php echo plugins_url('images/loader.gif', __FILE__); ?>') no-repeat center center;
					z-index: 9999;
					display: none;
				}
			</style>
			<div id="<?php echo $eazy_ad_unblocker_popup_id; ?>"><!--wrapfabtest March 22 2021-->
				<div class="adsBanner"><!--adsBanner-->
				</div>
			</div>
		<?php
	}
	
	public function eazy_ad_unblocker_get_content($encoded_html)
	{
		
		$content = str_replace('\r', '', $encoded_html);
		
		$content = str_replace('\n', '', $content);
		
		$content = stripslashes($content);
		
		/**before ad inserter fix Aug 31 2021**/
		
		global $wp_embed;

		add_filter( 'eazy_ad_unblocker_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
		add_filter( 'eazy_ad_unblocker_the_content', array( $wp_embed, 'autoembed'     ), 8 );

		add_filter( 'eazy_ad_unblocker_the_content', 'wptexturize' ); //
		add_filter( 'eazy_ad_unblocker_the_content', 'wpautop' ); //
		add_filter( 'eazy_ad_unblocker_the_content', 'convert_chars' ); //
		add_filter( 'eazy_ad_unblocker_the_content', 'shortcode_unautop' ); //
		
		add_filter( 'eazy_ad_unblocker_the_content', 'do_shortcode', 11 ); //
		add_filter( 'eazy_ad_unblocker_the_content', 'convert_smilies', 20 ); //

		/**after ad inserter fix Aug 31 2021**/
		
		//Widget fix Oct 24 2021
		
		$eazy_ad_unblocker_content = apply_filters('eazy_ad_unblocker_the_content', $content);
		
		remove_filter( 'eazy_ad_unblocker_the_content', array( $wp_embed, 'run_shortcode' ));
		remove_filter( 'eazy_ad_unblocker_the_content', array( $wp_embed, 'autoembed'     ));
		
		remove_all_filters('eazy_ad_unblocker_the_content');
		
		return $eazy_ad_unblocker_content;
		
		//end widget fix Oct 24 2021

	}
	
	/****End frontend***/
	
	//backend begins
	
	public function eazy_ad_unblocker_admin_callback()
	{
		$icon_url = plugins_url("images/eazy_admin_icon.png", __FILE__);
		
		add_menu_page("Eazy Ad Unblocker", "Eazy Ad Unblocker", "administrator", "eazy-ad-unblocker", array($this, "eazy_ad_unblock_admin_manage"), $icon_url);
		
		//eazy_ad_unblocker_hide_restore_folder_callback
		add_submenu_page( "eazy-ad-unblocker", "Hide or Restore", "Hide or Restore", "administrator", "eazy-ad-unblocker-hide-restore", array($this, 'eazy_ad_unblocker_hide_restore_folder_callback'));
		
		add_options_page('Eazy Ad Unblocker', 'Eazy Ad Unblocker', 'manage_options', 'eazy-ad-unblocker', array($this,'eazy_ad_unblock_admin_manage'));
	}
	
	public function eazy_ad_unblock_admin_manage()
	{
		@session_start();
		$settings = array( 'textarea_name' => 'unblocker_text' ); //, 'width'=>'500px'
		$content = "";
		//global $eazy_ad_unblocker_globalstyles; //Feb 25 2021
		
		$validate_msg=array();
		
		if(!empty($_POST["unblocker_save"]))
		{
			//verify nonce field
			
			if ( ! isset( $_POST['eazy_ad_unblocker_nonce'] ) || ! wp_verify_nonce( $_POST['eazy_ad_unblocker_nonce'], 'save_eazy_ad_unblocker_settings' ) )
			{
				wp_die(__("Form not verified", "eazy-ad-unblocker"));
			}
			
			$popup_title = sanitize_text_field($_POST["unblocker_title"]); //sanitization
			
			$popup_text = wp_kses_post($_POST["unblocker_text"]); //can't use sanitize_textarea_field as it removes everything including image tags 
			
			$popup_text = wp_check_invalid_utf8( $popup_text, true ); //strip out invalid utf-8
			
			$popup_opacity = floatval($_POST["unblocker_opacity"]); //sanitize float
			
			//June 23 2020 admin close btn//
			//yes no btn
			if(isset($_POST["unblocker_close_btn"]))
			{
				$popup_close_btn = $_POST["unblocker_close_btn"];
			}
			else
			{
				$popup_close_btn = '';
			}
			
			//End June 23 admin btn//
			
			//Begin Dec 21 2020//
			$eff_width;
			if(isset($_POST["unblocker_width"]))
			{
				$eff_width = $_POST["unblocker_width"];
				
				$eff_width = sanitize_text_field($eff_width);
				
				$eff_width = intval($eff_width);
			}
			else
			{
				$eff_width = '';
			}
			//End Dec 21 2020//
			
			//Feb 25 2021 style preview//
			$popup_style_preview = "";
			
			if(isset($_POST['unblocker_style_preview']))
			{
				$popup_style_preview = $_POST['unblocker_style_preview'];
			}
			else
			{
				$popup_style_preview = "";
			}
			
			//End Feb 25 2021 style preview//
			
			
			if(empty($popup_title))
			{
				$validate_msg[] = __("You must give a heading!", "eazy-ad-unblocker");
			}
			
			if(empty($popup_text))
			{
				$validate_msg[] = __("You must give a text!", "eazy-ad-unblocker");
			}
			
			if((!filter_var($popup_opacity, FILTER_VALIDATE_FLOAT) && !filter_var($popup_opacity, FILTER_VALIDATE_INT)) && $popup_opacity != 0)
			{
				$validate_msg[] = __("Opacity must be a number!", "eazy-ad-unblocker");
			}
			
			/****June 23, 2020 admin btn****/
			
			if(empty($popup_close_btn))
			{
				$validate_msg[] = __("You must give a value for 'Show popup close button'!", "eazy-ad-unblocker");
			}
			
			/*****End June 23, 2020*****/
			
			//Dec 21 2020//
			
			//End Dec 21 2020//
			
			/****Feb 25 2021****/
			if(empty($popup_style_preview))
			{
				$validate_msg[] = __("You must give a value for 'Choose popup style'!", "eazy-ad-unblocker");
			}
			/****Feb 25 2021****/
			
			if(empty($validate_msg))
			{
				
				//changed for close btn
				//Dec 21 2020
				//June 1 2022
				$unblock_settings = array("title"=>$popup_title, "text"=>$popup_text, "opacity"=>$popup_opacity, "close_btn"=>$popup_close_btn,
				"unblocker_width"=>$eff_width, "eazy_style_name"=>$popup_style_preview, "folder"=>basename(__DIR__));
				
				update_option("eazy-ad-unblocker-settings", $unblock_settings);
				
				$_SESSION["success"] = "<div class='updated notice is-dismissible'><p>".__("Settings saved!", "eazy-ad-unblocker")."</p></div>";
				
			}
			
		}
		
			$unblock_option = get_option("eazy-ad-unblocker-settings");
			
			$unblock_option = sanitize_option( "eazy-ad-unblocker-settings", $unblock_option );
			
			$unblock_array = $unblock_option;
			
			$content = stripslashes($unblock_array["text"]);
			
			$title = $unblock_array["title"];
			
			$opacity = $unblock_array["opacity"];
			
			//June 23 2020 Admin close btn//
			if(isset($unblock_array["close_btn"]))
			{
				$close_btn = $unblock_array["close_btn"];
			}
			else
			{
				$close_btn = '';
			}
			//June 23 end//
			
			//Dec 21 2020//
			if(isset($unblock_array["unblocker_width"]))
			{
				$eff_width = intval($unblock_array["unblocker_width"]);
			}
			else
			{
				$eff_width = '';
			}
			//Dec 21 2020 end//
			
			//Feb 25 2021
			if(isset($unblock_array["eazy_style_name"]))
			{
				$popup_style_preview = $unblock_array["eazy_style_name"];
			}
			else
			{
				$popup_style_preview = '';
			}
			//Feb 25 2021 end
		
		?>
		<style>
			.wrap .button-secondary{ background-color: #0085ba !important; color: #fff !important; padding: 2px 16px!important; }
			
		</style>
		<h1><?php echo __("Eazy Ad Unblocker Settings", "eazy-ad-unblocker"); //echo "Eazy Ad Unblocker Settings"; ?></h1>
		<?php if(isset($_SESSION["success"])){ echo $_SESSION["success"]; unset($_SESSION["success"]); } ?>
		<?php if(is_array($validate_msg)){ 
					foreach($validate_msg as $msg)
					{
						?>
						<div class="error notice"><p><?php echo $msg; ?></p></div>
						<?php
					}
				}		?>
				<div class="notice notice-info is-dismissible info"><h4><?php echo __("Ad blockers still blocking your popup? Try hiding the plugin.", "eazy-ad-unblocker"); ?>
				<a href="<?php echo admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore'); //April 11 2021 ?>"><?php echo __("Go here", "eazy-ad-unblocker"); ?></a></h4></div>
		<div id="wpwrap">
		<div class="wrap">
		<form action="" method="POST" >
		<?php wp_nonce_field( 'save_eazy_ad_unblocker_settings', 'eazy_ad_unblocker_nonce' ); ?>
		<table class="form-table" border="0" >
		<tr><td><?php echo __("Popup Title", "eazy-ad-unblocker"); ?></td><td><input type="text" name="unblocker_title" size="50" value="<?php echo $title; ?>" maxlength="200" /></td></tr>
		<tr><td><?php echo __("Popup Body", "eazy-ad-unblocker"); ?></td><td><?php wp_editor( $content, "adunblockText1", $settings ); ?><!----></td></tr>
		<tr><td><?php echo __("Popup Opacity", "eazy-ad-unblocker"); ?></td><td>
		<select name="unblocker_opacity" id="unblocker_opacity">
			<?php for($i = 0; $i < 11; $i++ )
			{ 
				
				?>
				<option value="<?php echo $i * 0.1; ?>" ><?php echo $i * 10; ?> %</option>
				<?php 
			}	?>		
		</select>
		<script>jQuery(document).ready(function($){ $("#unblocker_opacity").val(<?php echo $opacity; ?>); });</script>
		</td></tr>
		<!--begin admin btn June 23, 2020-->
		<tr><td><?php echo __("Show popup close button", "eazy-ad-unblocker"); ?></td>
		<td>
		<input type="radio" name="unblocker_close_btn" value="yes" <?php if($close_btn == 'yes'){ ?>checked="checked"<?php } ?> />&nbsp;<?php echo __("Yes", "eazy-ad-unblocker"); ?>&nbsp;&nbsp;
		<input type="radio" name="unblocker_close_btn" value="no" <?php if($close_btn == 'no'){ ?>checked="checked"<?php } ?> />&nbsp;<?php echo __("No", "eazy-ad-unblocker"); ?>
		</td>
		</tr>
		<!--end admin btn June 23, 2020-->
		<!--begin admin Dec 21 2020-->
		<tr><td><?php echo __("Popup Width For Large Screen", "eazy-ad-unblocker"); ?> (px)</td><td><input type="text" name="unblocker_width" size="4" value="<?php echo $eff_width; ?>" maxlength="4" /></td></tr>
		<!--end admin Dec 21 2020-->
		<!--color customization Feb 25 2021 begin-->
		<tr>
		<td><?php echo __("Choose popup style", "eazy-ad-unblocker"); ?></td>
		<td>
		<?php 
			
			foreach($this->eazy_ad_unblocker_globalstyles as $styleName=>$stylePreviewImage)
			{
				?>
				<input type="radio" name="unblocker_style_preview" value="<?php echo $styleName; ?>" <?php if($popup_style_preview == $styleName){ ?>checked="checked"<?php } ?> /> 
				<img src="<?php echo $stylePreviewImage; ?>" alt="<?php echo $styleName; ?>" align="top" />
				<?php
			}

		?>
		</td>
		</tr>
		<!--color customization Feb 25, 2021 end-->
		<tr><td><input type="submit" name="unblocker_save" value="<?php echo __("Save", "eazy-ad-unblocker"); ?>" class="button button-primary" /></td>
		<td>&nbsp;</td></tr>
		</table>
		</form>
		</div>
		</div>
		<?php 
	}
	
	public function eazy_ad_unblocker_admin_redirects()
	{

		global $pagenow;

		/* Redirect */
		if($pagenow == 'admin.php'){
			
			if(!empty($_POST["unblocker_save"]) && $_POST["unblocker_save"] == __("Save", "eazy-ad-unblocker"))
			{
				
				wp_redirect(admin_url('/admin.php?page=eazy-ad-unblocker')); //April 11 2021
				exit;
			}
			
			if(!empty($_POST["hide_eazy_ad_unblocker"]) && $_POST["hide_eazy_ad_unblocker"] == __("Hide", "eazy-ad-unblocker"))
			{
				
				wp_redirect(admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore')); //April 11 2021
				exit;
			}
			
			if(!empty($_POST["restore_eazy_ad_unblocker"]) && $_POST["restore_eazy_ad_unblocker"] == __("Restore", "eazy-ad-unblocker"))
			{
				
				wp_redirect(admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore')); //April 11 2021
				exit;
			}
		}
		else if($pagenow == "options-general.php")
		{
			if(!empty($_POST["unblocker_save"]) && $_POST["unblocker_save"] == __("Save", "eazy-ad-unblocker"))
			{
				
				wp_redirect(admin_url('/options-general.php?page=eazy-ad-unblocker')); //APril 11 2021
				exit;
			}
		}

	}
	
	//visual editor fonts control

	public function eazy_ad_unblocker_editor_buttons( $buttons ){
		array_unshift( $buttons, 'fontselect' ); 
		return $buttons;
	}

	//end visual editor
	
	//modifications in plugin listing

	public function eazy_ad_unblocker_add_plugin_page_settings_link( $links ) {
		$links[] = '<a href="'.
			admin_url( '/options-general.php?page=eazy-ad-unblocker' ).
			'">'.__('Settings').'</a>';
			
		return $links;
	}

	//modifications finished 
	
	//activate or deactivate hooks

	public function eazy_ad_unblocker_activate_func()
	{
		
		$default_settings_array = array("title"=>"Demo Title", "text"=>"Please disable your adblocker or whitelist this site!", "opacity"=> 0.7);
		
		foreach($default_settings_array as $key=>$value)
		{
			$default_settings_array[sanitize_text_field($key)] = sanitize_text_field($value);
		}
		
		if(get_option("eazy-ad-unblocker-settings") == false)
		{
			update_option("eazy-ad-unblocker-settings", $default_settings_array);
		}
	}
	
	// And here goes the uninstallation function:
	public static function eazy_ad_unblocker_uninstall_func()
	{
		//  codes to perform during unistallation
		@session_start();
		//unset session random words
		$_SESSION["eazy_ad_rand_words"] = ''; //April 10 2021
		unset($_SESSION["eazy_ad_rand_words"]);
		
		delete_option("eazy-ad-unblocker-settings");  //delete plugin data
		
	}
	
	//internationalization

	public function eazy_ad_unblocker_load_textdomain(){
		load_plugin_textdomain( 'eazy-ad-unblocker', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
	}
	
	/****BEGIN Per page block/ unblock funcs*******/

	public function eazy_ad_unblocker_custom_metabox()
	{
		add_meta_box( "eazy_ad_unblocker_per_page_disable", esc_html__("Disable Eazy Ad Unblocker", "eazy-ad-unblocker"), array($this, "eazy_ad_unblocker_custom_metabox_fill") , array(), 'side', 'high');
	}
	
	public function eazy_ad_unblocker_custom_metabox_fill($post)
	{
		$pid = $post->ID;
		
		$val = get_post_meta($pid, "eazy_ad_unblocker_per_page_disabled", true);
		
		$checked = '';
		
		if(!empty($val) && $val == 'yes')
		{
			$checked = "checked='checked'";
		}
		
		echo "<input type='checkbox' name='eazy_ad_unblocker_per_page_disabled' value='yes' ".$checked." id='eazy_ad_unblocker_per_page_disabled_".$pid."' />";
		
		echo "&nbsp;&nbsp;<label for='eazy_ad_unblocker_per_page_disabled'>".__('Disable Popup for this Page/Post', "eazy-ad-unblocker")."</label>";
	}
	
	public function eazy_ad_unblocker_save_per_page_disable($post_id)
	{
		if(current_user_can( 'edit_post', $post_id ))
		{
			
			$per_page_disable = (isset($_POST["eazy_ad_unblocker_per_page_disabled"]))?$_POST["eazy_ad_unblocker_per_page_disabled"]:'';
		
			update_post_meta($post_id, "eazy_ad_unblocker_per_page_disabled", $per_page_disable);

		}
	}
	
	/****Brave problem Dec 2020****/
	public function eazy_ad_unblocker_allow_script($allowedposttags)
	{
		 $allowedposttags['script'] = array(
			'type' => true,
			'src' => true,
			'height' => true,
			'width' => true,
		);
		
		return $allowedposttags;
		
	}
	
	//functions to copy and delete files

	//delete folder
	public function eazy_ad_unblocker_rrmdir($src){
		if (file_exists($src)){
			$dir = opendir($src);
			while (false !== ($file = readdir($dir))) {
				if (($file != '.') && ($file != '..')) {
					$full = $src . '/' . $file;
					if (is_dir($full)) {
						$this->eazy_ad_unblocker_rrmdir($full);
					} else {
						unlink($full);
					}
				}
			}
			closedir($dir);
			rmdir($src);
		}
	}
	
	//copy folder
	public function eazy_ad_unblocker_cpy($source, $dest){
		if(is_dir($source)) {
			$dir_handle=opendir($source);
			while($file=readdir($dir_handle)){
				if($file!="." && $file!=".."){
					if(is_dir($source."/".$file)){
						if(!is_dir($dest."/".$file)){
							mkdir($dest."/".$file);
						}
						$this->eazy_ad_unblocker_cpy($source."/".$file, $dest."/".$file);
					} else {
						copy($source."/".$file, $dest."/".$file);
					}
				}
			}
			closedir($dir_handle);
		} else {
			copy($source, $dest);
		}
	}
	
	//function for hide or restore plugin folder

	public function eazy_ad_unblocker_hide_restore_folder($dest)
	{
		
		$eazy_ad_unblocker_old_plugin_dir = dirname(__FILE__).DIRECTORY_SEPARATOR;
		
		$eazy_ad_unblocker_new_plugin_dir = dirname( dirname(__FILE__) ).DIRECTORY_SEPARATOR.$dest.DIRECTORY_SEPARATOR;
		
		/*****STEP 1: Copy the existing plugin to new folder*****/
		
		mkdir($eazy_ad_unblocker_new_plugin_dir, 0755);

		$this->eazy_ad_unblocker_cpy($eazy_ad_unblocker_old_plugin_dir, $eazy_ad_unblocker_new_plugin_dir);
		
		/****END STEP 1***/
		
		/*****STEP 2: Update active plugins data***/
		
		$eazy_ad_unblocker_old_plugins_option = get_option("active_plugins");
		
		$eazy_ad_unblocker_new_plugins_option = array();
		
		//echo "<pre>".print_r($eazy_ad_unblocker_old_plugins_option, 1)."</pre>";
		
		foreach($eazy_ad_unblocker_old_plugins_option as $key=>$value)
		{
			$eazy_ad_unblocker_old_name = basename($eazy_ad_unblocker_old_plugin_dir);
			
			$eazy_ad_unblocker_new_name = basename($eazy_ad_unblocker_new_plugin_dir);
			
			$eazy_ad_unblocker_value_array = explode("/", $value); 
			
			if($eazy_ad_unblocker_value_array[0] == $eazy_ad_unblocker_old_name)
			{
				
				$eazy_ad_unblocker_value_array[0] = $eazy_ad_unblocker_new_name;
				
				$newValue = implode("/", $eazy_ad_unblocker_value_array); 
				
				$eazy_ad_unblocker_new_plugins_option[$key] = $newValue;
			}
			else
			{
				$eazy_ad_unblocker_new_plugins_option[$key] = $value;
			}
		}
		
		//echo "<pre>".print_r($eazy_ad_unblocker_new_plugins_option, 1)."</pre>";
		
		$eazy_ad_unblocker_new_plugins_update = update_option("active_plugins", $eazy_ad_unblocker_new_plugins_option);
		
		if($eazy_ad_unblocker_new_plugins_update == false)
		{
			
			$this->eazy_ad_unblocker_rrmdir($eazy_ad_unblocker_new_plugin_dir); //remove new folder if there is error
			
			return false;
		}
		
		/*****END STEP 2*****/
		
		/*****STEP 3: store new folder name in options*****/
		
		$eazy_ad_unblocker_old_options = get_option("eazy-ad-unblocker-settings");
		
		//$eazy_ad_unblocker_old_options["folder"] = $dest;
		
		$eazy_ad_unblocker_old_options_keys = array_keys($eazy_ad_unblocker_old_options);
		
		$eazy_ad_unblocker_old_options_values = array_values($eazy_ad_unblocker_old_options);
		
		$eazy_ad_unblocker_old_options_keys[] = "folder";
		
		$eazy_ad_unblocker_old_options_values[] = $dest;
		
		$eazy_ad_unblocker_new_options = array_combine($eazy_ad_unblocker_old_options_keys, $eazy_ad_unblocker_old_options_values);
		
		if($eazy_ad_unblocker_new_options != false)
		{
			$eazy_ad_unblocker_new_options_update = update_option("eazy-ad-unblocker-settings", $eazy_ad_unblocker_new_options);
			
			if($eazy_ad_unblocker_new_options_update == false)
			{
				
				$this->eazy_ad_unblocker_rrmdir($eazy_ad_unblocker_new_plugin_dir); //remove new folder if there is error
				
				return false;
			}
		}
		else{
			
			$this->eazy_ad_unblocker_rrmdir($eazy_ad_unblocker_new_plugin_dir); //remove new folder if there is error
			
			return false;
		}
		
		
		/*****END STEP 3******/
		
		/*****STEP 4: Delete Old junk folder******/
		
		$eazy_ad_unblocker_old_dir = dirname(__FILE__);
		
		chmod($eazy_ad_unblocker_old_dir, 0755);
		
		$this->eazy_ad_unblocker_rrmdir($eazy_ad_unblocker_old_dir); //not working
		
		/*****END STEP 4*****/
		
		return true;
		
	}
	
	//callback function for hide ad guard

	public function eazy_ad_unblocker_hide_restore_folder_callback()
	{
		
		@session_start();
		
		if(!empty($_POST['hide_eazy_ad_unblocker']))
		{
			if ( ! isset( $_POST['eazy_ad_unblocker_hide_nonce'] ) || ! wp_verify_nonce( $_POST['eazy_ad_unblocker_hide_nonce'], 'hide_eazy_ad_unblocker_settings' ) )
			{
				wp_die(__("Hiding not allowed", "eazy-ad-unblocker"));
			}
			
			$dest = md5(microtime());
			
			$eazy_ad_unblocker_hide_restore = $this->eazy_ad_unblocker_hide_restore_folder($dest);
			
			if($eazy_ad_unblocker_hide_restore)
			{
				$_SESSION['eazy_ad_unblocker_hide_restore'] = "<div class='notice notice-success is-dismissible'><h4>".__("Plugin Folder Hidden successfully.", "eazy-ad-unblocker")."</h4></div>";
			}
			else{
				$_SESSION['eazy_ad_unblocker_hide_restore'] = "<div class='notice notice-error is-dismissible'><h4>".__("Sorry! Something went wrong!", "eazy-ad-unblocker")."</h4></div>";
			}
		}
		
		if(!empty($_POST['restore_eazy_ad_unblocker']))
		{
			if ( !isset( $_POST['eazy_ad_unblocker_restore_nonce'] ) || !wp_verify_nonce( $_POST['eazy_ad_unblocker_restore_nonce'], 'restore_eazy_ad_unblocker_settings' ) )
			{
				wp_die(__("Restoring not allowed", "eazy-ad-unblocker"));
			}
			
			$dest = "eazy-ad-unblocker";
			
			$eazy_ad_unblocker_restore = $this->eazy_ad_unblocker_hide_restore_folder($dest);
			
			if($eazy_ad_unblocker_restore)
			{
				$_SESSION['eazy_ad_unblocker_restore'] = "<div class='notice notice-success is-dismissible'><h4>".__("Plugin Folder restored successfully.", "eazy-ad-unblocker")."</h4></div>";
			}
			else{
				$_SESSION['eazy_ad_unblocker_restore'] = "<div class='notice notice-error is-dismissible'><h4>".__("Sorry! Something went wrong!", "eazy-ad-unblocker")."</h4></div>";
			}
		}
		
		?>
		<h1>Hide Or Restore Plugin Folder</h1>
		<div id="wpwrap">
		<div class="wrap">
		<div class="notice notice-warning is-dismissible"><h4><?php echo __("Please take a backup of your entire wordpress plugins folder and your database before doing anything here.", "eazy-ad-unblocker"); ?></h4></div>
		<?php if(isset($_SESSION['eazy_ad_unblocker_hide_restore']))
		{
			echo $_SESSION['eazy_ad_unblocker_hide_restore'];
			unset($_SESSION['eazy_ad_unblocker_hide_restore']);
		}	?>
		<form action="<?php echo admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore'); //April 11 2021 ?>" method="POST" >
		<?php wp_nonce_field( 'hide_eazy_ad_unblocker_settings', 'eazy_ad_unblocker_hide_nonce' ); ?>
		<table border="0" cellpadding="0" cellspacing="0" width="400">
		<tr><td colspan="2"><h4><?php echo __("Hide plugin from pesky ad blockers", "eazy-ad-unblocker"); ?></h4></td></tr>
		<tr><td><label for="hide_eazy_ad_unblocker"><?php echo __("Click to hide", "eazy-ad-unblocker"); ?></label></td>
		<td><input type="submit" name="hide_eazy_ad_unblocker" value="<?php echo __("Hide", "eazy-ad-unblocker"); ?>" class="btn-primary" /></td></tr>
		</table>
		</form>
		<br />
		<br />
		<div class="notice notice-warning is-dismissible"><h4><?php echo __("Please restore your plugin before updation if you have hidden it.", "eazy-ad-unblocker"); ?></h4></div>
		<?php if(isset($_SESSION['eazy_ad_unblocker_restore']))
		{
			echo $_SESSION['eazy_ad_unblocker_restore'];
			unset($_SESSION['eazy_ad_unblocker_restore']);
		}	?>
		
		<form action="<?php echo admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore');  //April 11 2021 ?>" method="POST" >
		<?php wp_nonce_field( 'restore_eazy_ad_unblocker_settings', 'eazy_ad_unblocker_restore_nonce' ); ?>
		<table border="0" cellpadding="0" cellspacing="0" width="400">
		<tr><td colspan="2"><h4><?php echo __("Restore plugin for Updation", "eazy-ad-unblocker"); ?></h4></td></tr>
		<tr><td><label for="hide_eazy_ad_unblocker"><?php echo __("Click to restore", "eazy-ad-unblocker"); ?></label></td>
		<td><input type="submit" name="restore_eazy_ad_unblocker" value="<?php echo __("Restore", "eazy-ad-unblocker"); ?>" class="btn-primary" /></td></tr>
		</table>
		</form>
		
		</div>
		</div>
		<?php
	}
	
	//hide auto-update link

	public function eazy_ad_unblocker_hide_auto_update_link($update, $item)
	{
		
		$unblocker_opts = get_option("eazy-ad-unblocker-settings");
		
		$unblocker_folder = isset($unblocker_opts["folder"])?$unblocker_opts["folder"]:'eazy-ad-unblocker';
		
		 /* only for eazy-ad-unblocker */
		$plugins = array ( 'eazy-ad-unblocker' );
		if ( in_array( $item->slug, $plugins ) ) {
			// update plugin
			return false; 
		} else {
			// use default settings
			return $update; 
		}

	}
}

//sub class with logic change

class EazyAdUnblockerHideRestore extends EazyAdUnblocker
{
	public function __construct()
	{
		
		parent::__construct();
		
		add_action("wp_enqueue_scripts", array($this, "eazy_ad_unblocker_get_ad_url")); //May 31 2022
		
		add_action("admin_notices", array($this, 'eazy_ad_unblocker_admin_notice')); //May 31 2022
	}
	
	public function eazy_ad_unblocker_hide_restore_folder_callback()
	{
		
		@session_start();
		
		if(!empty($_POST['hide_eazy_ad_unblocker']))
		{
			if ( ! isset( $_POST['eazy_ad_unblocker_hide_nonce'] ) || ! wp_verify_nonce( $_POST['eazy_ad_unblocker_hide_nonce'], 'hide_eazy_ad_unblocker_settings' ) )
			{
				wp_die(__("Hiding not allowed", "eazy-ad-unblocker"));
			}
			
			$validate_msgs = array();
			
			$dest = md5(microtime());
			
			if(empty($_POST['hide_eazy_ad_unblocker_folder']))
			{
				$validate_msgs[] = __("Sorry! Folder name cannot be empty!", "eazy-ad-unblocker");
			}
			else
			{
				
			
				$dest_folder = sanitize_text_field($_POST['hide_eazy_ad_unblocker_folder']);
				
				//check for regex
				//if(!ctype_alnum($dest_folder))
				$matches = array();
				if(!preg_match_all('/^[\p{L}\p{N}_-]+$/u', $dest_folder, $matches, PREG_PATTERN_ORDER)) //Jan 15 2023
				{
					$validate_msgs[] = __("Sorry! Folder name can only have letters, numbers, hyphens and underscores!", "eazy-ad-unblocker"); //Jan 15 2023
				}
				else
				{
					//check if folder name is taken
					$eazy_ad_unblocker_plugins_folder = dirname( dirname(__FILE__) );
					
					if($this->eazy_ad_unblocker_check_folder_available($eazy_ad_unblocker_plugins_folder, $dest_folder)) //Jan 15 2023
					{
						$eazy_ad_unblocker_hide_restore = $this->eazy_ad_unblocker_hide_restore_folder($dest_folder); //Jan 15 2023
					
						if($eazy_ad_unblocker_hide_restore)
						{
							$_SESSION['eazy_ad_unblocker_hide_restore'] = "<div class='notice notice-success is-dismissible success'><h4>".__("Plugin Folder Hidden successfully.", "eazy-ad-unblocker")."</h4></div>";
						}
						else{
							$_SESSION['eazy_ad_unblocker_hide_restore'] = "<div class='notice notice-error is-dismissible error'><h4>".__("Sorry! Something went wrong!", "eazy-ad-unblocker")."</h4></div>";
						}
					}
					else
					{
						$validate_msgs[] = __("Sorry! That folder already exists!", "eazy-ad-unblocker");
					}
				}
			}
			
		}
		
		if(!empty($_POST['restore_eazy_ad_unblocker']))
		{
			if ( !isset( $_POST['eazy_ad_unblocker_restore_nonce'] ) || !wp_verify_nonce( $_POST['eazy_ad_unblocker_restore_nonce'], 'restore_eazy_ad_unblocker_settings' ) )
			{
				wp_die(__("Restoring not allowed", "eazy-ad-unblocker"));
			}
			
			$dest = "eazy-ad-unblocker";
			
			$validation_msgs = array();
			
			if(basename(dirname(__FILE__)) == $dest)
			{
				$validation_msgs[] =  __("Plugin folder is not hidden!", "eazy-ad-unblocker");
			}
			else
			{
				$eazy_ad_unblocker_restore = $this->eazy_ad_unblocker_hide_restore_folder($dest);
				
				if($eazy_ad_unblocker_restore)
				{
					$_SESSION['eazy_ad_unblocker_restore'] = "<div class='notice notice-success is-dismissible success'><h4>".__("Plugin Folder restored successfully.", "eazy-ad-unblocker")."</h4></div>";
				}
				else{
					$_SESSION['eazy_ad_unblocker_restore'] = "<div class='notice notice-error is-dismissible error'><h4>".__("Sorry! Something went wrong!", "eazy-ad-unblocker")."</h4></div>";
				}
			}
		}
		
		?>
		<h1>Hide Or Restore Plugin Folder</h1>
		<?php //Oct 3 3021 
		if((!empty($_POST["hide_eazy_ad_unblocker"]) && $_POST["hide_eazy_ad_unblocker"] == __("Hide", "eazy-ad-unblocker")) 
		){
			?>
			<meta http-equiv="refresh" content="4;URL=<?php echo admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore'); ?>">
			<?php 
		}else if(!empty($_POST["restore_eazy_ad_unblocker"]) && $_POST["restore_eazy_ad_unblocker"] == __("Restore", "eazy-ad-unblocker")){
			?>
			<meta http-equiv="refresh" content="4;URL=<?php echo admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore'); ?>">
		<?php } //End Oct 3 2021?>
		<div id="wpwrap">
		<div class="wrap">
		<!--Oct 3 2021-->
		<?php if(basename(dirname(__FILE__)) == "eazy-ad-unblocker"){ ?>
		<div class="notice notice-success is-dismissible success"><h4><?php echo __("Your plugin folder is NOT hidden.", "eazy-ad-unblocker"); ?></h4></div>
		<?php }else{ ?>
		<div class="notice notice-error is-dismissible error"><h4><?php echo __("Your plugin folder is hidden. Restore it before updating the plugin.", "eazy-ad-unblocker"); ?></h4></div>	
		<!--Jan 15 2023-->
		<div class="notice notice-info is-dismissible"><h4><?php echo __("Your plugin folder is: ", "eazy-ad-unblocker"); echo basename(__DIR__); ?></h4></div>
		<!--Jan 15 2023 END-->
		<?php } ?>
		<!--Oct 3 2021 END-->
		<div class="notice notice-warning is-dismissible warning"><h4><?php echo __("Please take a backup of your entire wordpress plugins folder and your database before doing anything here.", "eazy-ad-unblocker"); ?></h4></div>
		<?php if(isset($_SESSION['eazy_ad_unblocker_hide_restore']))
		{
			echo $_SESSION['eazy_ad_unblocker_hide_restore'];
			unset($_SESSION['eazy_ad_unblocker_hide_restore']);
		}	?>
		<?php 
		
		if(!empty($validate_msgs))
		{
			foreach($validate_msgs as $msg)
			{
				?>
				<div class="notice notice-error is-dismissible"><h4><?php echo $msg; ?></h4></div>
				<?php
			}
		}
		?>
		<form action="<?php echo admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore'); // April 11 2021 ?>" method="POST" >
		<?php wp_nonce_field( 'hide_eazy_ad_unblocker_settings', 'eazy_ad_unblocker_hide_nonce' ); ?>
		<table border="0" cellpadding="5" cellspacing="0" width="400">
		<tr><td colspan="2"><h4><?php echo __("Hide plugin from pesky ad blockers", "eazy-ad-unblocker"); ?></h4></td></tr>
		<tr><td><label for="hide_eazy_ad_unblocker_folder"><?php echo __("Enter new folder name", "eazy-ad-unblocker"); ?></label></td>
		<td><input type="text" name="hide_eazy_ad_unblocker_folder" value="" size="30" maxlength="50" minlength="6" /></td></tr>
		<tr><td><label for="hide_eazy_ad_unblocker"><?php echo __("Click to hide", "eazy-ad-unblocker"); ?></label></td>
		<td><input type="submit" name="hide_eazy_ad_unblocker" value="<?php echo __("Hide", "eazy-ad-unblocker"); ?>" class="btn-primary" /></td></tr>
		</table>
		</form>
		<br />
		<br />
		<?php 
		
		 ?>
			<div class="notice notice-warning is-dismissible"><h4><?php echo __("Please restore your plugin before updation if you have hidden it.", "eazy-ad-unblocker"); ?></h4></div>
			<div class="notice notice-info is-dismissible"><h4><?php echo __("Hide your plugin again after updating it.", "eazy-ad-unblocker"); ?></h4></div>
			<?php if(isset($_SESSION['eazy_ad_unblocker_restore']))
			{
				echo $_SESSION['eazy_ad_unblocker_restore'];
				unset($_SESSION['eazy_ad_unblocker_restore']);
			}	?>
			<?php 
		
			if(!empty($validation_msgs))
			{
				foreach($validation_msgs as $msg)
				{
					?>
					<div class="notice notice-error is-dismissible"><h4><?php echo $msg; ?></h4></div>
					<?php
				}
			}
			?>
			<form action="<?php echo admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore'); //April 11 2021 ?>" method="POST" >
			<?php wp_nonce_field( 'restore_eazy_ad_unblocker_settings', 'eazy_ad_unblocker_restore_nonce' ); ?>
			<table border="0" cellpadding="0" cellspacing="0" width="400">
			<tr><td colspan="2"><h4><?php echo __("Restore plugin for Updation", "eazy-ad-unblocker"); ?></h4></td></tr>
			<tr><td><label for="hide_eazy_ad_unblocker"><?php echo __("Click to restore", "eazy-ad-unblocker"); ?></label></td>
			<td><input type="submit" name="restore_eazy_ad_unblocker" value="<?php echo __("Restore", "eazy-ad-unblocker"); ?>" class="btn-primary" /></td></tr>
			</table>
			</form>
		<?php ?>
		</div>
		</div>
		<?php
	}
	
	public function eazy_ad_unblocker_check_folder_available($check_folder_path, $check_name)
	{
		$check_full_path = $check_folder_path.DIRECTORY_SEPARATOR.$check_name;
		
		if(file_exists($check_full_path))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	//Oct 3 2021
	public function eazy_ad_unblocker_admin_redirects()
	{

		global $pagenow;

		/* Redirect */
		if($pagenow == 'admin.php'){
			
			if(!empty($_POST["unblocker_save"]) && $_POST["unblocker_save"] == __("Save", "eazy-ad-unblocker"))
			{
				
				wp_redirect(admin_url('/admin.php?page=eazy-ad-unblocker')); 
				exit();
			}
			
		}
		else if($pagenow == "options-general.php")
		{
			if(!empty($_POST["unblocker_save"]) && $_POST["unblocker_save"] == __("Save", "eazy-ad-unblocker"))
			{
				
				wp_redirect(admin_url('/options-general.php?page=eazy-ad-unblocker'));
				exit();
			}
		}
	}
	//End Oct 3 2021
	
	//May 31 2022
	protected function eazy_ad_unblocker_ip_visitor_country()
	{

		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
		$country  = "Unknown";

		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$ip_data_in = curl_exec($ch); // string
		curl_close($ch);

		$ip_data = json_decode($ip_data_in,true);
		$ip_data = str_replace('&quot;', '"', $ip_data); // for PHP 5.2 see stackoverflow.com/questions/3110487/

		if($ip_data && $ip_data['geoplugin_countryName'] != null) {
			$country = $ip_data['geoplugin_countryName'];
		}
		
		return $country;
	}
	
	public function eazy_ad_unblocker_get_ad_url()
	{
		@session_start();
		
		$eazy_ad_unblocker_ad_url = "";
		
		if(!isset($_SESSION["eazy_ad_unblocker_visitor_country"]))
		{
			$_SESSION["eazy_ad_unblocker_visitor_country"] = $this->eazy_ad_unblocker_ip_visitor_country();
		}
		
		session_write_close(); //Jan 15 2023
		
		switch($_SESSION["eazy_ad_unblocker_visitor_country"])
		{
			case "Russia":
				$eazy_ad_unblocker_ad_url = "https://yandex.ru/ads/system/context.js";
			break;
			default:
				$eazy_ad_unblocker_ad_url = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";
			break;
		}
		
		wp_localize_script("eazy_custom", "eazy_ad_unblocker", array("ad_url"=> $eazy_ad_unblocker_ad_url));
		
	}
	
	public function eazy_ad_unblocker_admin_notice(){
		
		$option_folder = get_option("eazy-ad-unblocker-settings");
		
		global $pagenow;
		
		$plugin_hidden = __("Eazy Ad Unblocker plugin folder is hidden. Go here to restore it before updating the plugin.", "eazy-ad-unblocker");
		
		$click_link = __("click", "eazy-ad-unblocker");
		
		if ( $pagenow == "plugins.php" && isset($option_folder["folder"]) && $option_folder["folder"] != "eazy-ad-unblocker"){
			 echo '<div class="notice notice-warning is-dismissible">
				 <p>'.$plugin_hidden.' <a href="'.admin_url('/admin.php?page=eazy-ad-unblocker-hide-restore').'"><strong>'.$click_link.'</strong></a></p>
			 </div>';
		}
	}
	//End May 31 2022
}

?>