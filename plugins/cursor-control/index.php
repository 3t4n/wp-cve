<?php

/*
Plugin Name: Cursor Control
Plugin URI: http://www.clevelandwebdeveloper.com/wordpress-plugins/cursor-control/
Description: Easily upload custom images to use as your default cursor, text input cursor, and your cursor for links and buttons.
Version: 1.1
Author: Justin Saad
Author URI: http://www.clevelandwebdeveloper.com
License: GPL2
*/


class cursor_control {

	public function __construct() {
		//do when class is instantiated	
		//add_shortcode('spacer', array($this, 'addShortcodeHandler'));
		//add_filter( 'tiny_mce_version', array($this, 'my_refresh_mce'));
		
		$this->plugin_slug = "cursor_control";
		$this->plugin_label = "Cursor Control";
		
		//plugin row links
		add_filter( 'plugin_row_meta', array($this,'plugin_row_links'), 10, 2 );
		
		add_action('init', array($this, 'load_scripts')); //loads on wordpress init
		
        if(is_admin()){
			//add_action( 'admin_enqueue_scripts', array($this, 'enqueue_color_picker') );
			//uncomment following line to add Settings link to plugin page
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this, 'add_plugin_action_links') );
			
		    add_action('admin_menu', array($this, 'add_plugin_page'));
		    add_action('admin_init', array($this, 'page_init'));
			
			//image upload script
			add_action('admin_enqueue_scripts', array($this,'motech_imageupload_script'));

		}
	}
	
	function motech_imageupload_script() {
		if (isset($_GET['page']) && $_GET['page'] == $this->plugin_slug.'-setting-admin') {
			wp_enqueue_media();
			wp_register_script('motech_imageupload-js', plugins_url( 'js/motech_imageupload.js' , __FILE__ ), array('jquery'));
			wp_enqueue_script('motech_imageupload-js');
		}
	}
	
/*	function enqueue_color_picker( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'motech-script-handle', plugins_url('motech-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}*/
	
    public function add_plugin_page(){
        // This page will be under "Settings"
		add_options_page('Settings Admin', $this->plugin_label, 'manage_options', $this->plugin_slug.'-setting-admin', array($this, 'create_admin_page'));
    }
	
    public function print_section_info(){ //section summary info goes here
		print 'Upload your cursor and configure basic settings. <br><span style="color:red">NOTE - The cursor images are compatible with Firefox, Chrome and Safari. It will NOT work in Internet Explorer.</span>';
    }
	
    public function get_donate_button(){ ?>
	<style type="text/css">
	.motechdonate{border: 1px solid #DADADA; background:white; font-family: tahoma,arial,helvetica,sans-serif;font-size: 12px;overflow: hidden;padding: 5px;position: absolute;right: 0;text-align: center;top: 0;width: 275px; box-shadow:0px 0px 8px rgba(153, 153, 153, 0.81);z-index:9;-webkit-transition: all .2s ease-out;  -moz-transition: all .2s ease-out;-o-transition: all .2s ease-out;transition: all .2s ease-out;}
	.motechdonate:hover {background:rgb(176, 249, 255);}
	.motechdonate form{display:block;}
	#motech_top_banner {background: rgb(221, 215, 215);margin: -5px;margin-bottom: 7px;padding-bottom: 4px;line-height: 16px;font-size: 18px;text-indent: 6px;}
	.motechdonate ul {padding-left:16px;}
	.motechdonate li {list-style-type: disc;list-style-position: outside;}
	
	@media only screen and (max-width: 1300px) {
		.donly {display:none;}
		.motechdonate li {list-style-type:none;}
		.motechdonate *, .motechdonate {width:auto !important;}
	}
	@media only screen and (max-width: 400px) {
		.motechdonate {bottom: -90px;left: 0px;top: auto;right: auto;}
	}
	</style>
    <div class="motechdonate">
        <div style="width: 276px; text-align: left;">
        	<div id="motech_top_banner" class="donly">Ways to say thanks</div>
        <div style="overflow: hidden; width: 276px; text-align: left; float: left;">
        <div class="donly">A lot of effort went into the development of this plugin. You can say 'Thank You' by doing any of the following</div>
        <ul>
        <li><span class="donly">Donate a few dollars to my company The Motech Network to help with future development and updates.</span>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input name="cmd" value="_s-xclick" type="hidden"><input name="hosted_button_id" value="9TL57UDBAB7LU" type="hidden"><input type="hidden" name="no_shipping" value="1"><input type="hidden" name="item_name" value="The Motech Network Plugin Support - <?php echo $this->plugin_label ?>" /><input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" type="image"> <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt="" border="0" height="1" width="1"></form>        
        </li>
        <li class="donly">Follow me on <a href="https://twitter.com/ClevelandWebDev" target="_blank">Twitter</a></li>	
         <li class="donly">Connect with me on <a href="http://www.linkedin.com/in/ClevelandWebDeveloper/" target="_blank">LinkedIn</a></li>
         <li class="donly"><a href="http://wordpress.org/support/view/plugin-reviews/cursor-control" target="_blank" title="Rate it">Rate it</a> on WordPress.org</li>
         <li class="donly">Blog about it & link to the <a href="http://www.clevelandwebdeveloper.com/wordpress-plugins/cursor-control/" target="_blank">plugin page</a></li>
         <li class="donly">Check out my other <a href="http://www.clevelandwebdeveloper.com/wordpress-plugins/" target="_blank">WordPress plugins</a></li>
         <li class="donly"><a href="mailto:info@clevelandwebdeveloper.com" target="_blank">Email me</a> to say thanks. If you can let me know where my plugins are being used 'in the wild' I always appreciate that.</li>
        </ul>
        <div class="donly">Thanks in advance for your support.</div>
        <div style="font-style:italic;" class="donly">-Justin</div>
        </div>
        </div>
	</div>    
    
    <?php

    }
	
    public function create_admin_page(){
        ?>
		<div class="wrap" style="position:relative">
        	<?php $this->get_donate_button() ?>
		    <?php screen_icon(); ?>
		    <h2><?php echo $this->plugin_label ?></h2>			
		    <form method="post" action="options.php" style="max-width:770px;">
		        <?php
	            // This prints out all hidden setting fields
			    settings_fields($this->plugin_slug.'_option_group');	
			    do_settings_sections($this->plugin_slug.'-setting-admin');
			?>
		        <?php submit_button(); ?>
		    </form>
		</div>
	<?php
    }
	
    public function page_init(){
        	
		//create settings section
        add_settings_section(
		    $this->plugin_slug.'_setting_section',
		    'Configuration',
		    array($this, 'print_section_info'),
		    $this->plugin_slug.'-setting-admin'
		);
		
		//add text input field
		$field_slug = "custom_defaultcursor_image_upload";
		$field_label = "Custom Default Cursor Image";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_image_upload'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Enter a URL or upload a custom default cursor image. Leave empty to use the default cursor image. You can upload png, jpg, and gif files. Maximum cursor dimensions are 128 x 128 pixels, but smaller images recommended.' //description of the field (optional)
			)			
		);
		
		//add text input field
		$field_slug = "x_offset";
		$field_label = "X Offset";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Set the hotspot, in pixels, from the left of the image. 0 will be all the way at the left (eg: If you enter \'4\', that would mean the hotspot is 4 pixels to the right of the image\'s left side.)', //description of the field (optional)
				"maxlength" => 3, //max character length for the input (optional)
				"default" => 0, //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
			
		//add text input field
		$field_slug = "y_offset";
		$field_label = "Y Offset";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Set the hotspot, in pixels, from the top of the image. 0 will be all the way at the top (eg: If you enter \'4\', that would mean the hotspot is 4 pixels down from the top of the image.)', //description of the field (optional)
				"maxlength" => 3, //max character length for the input (optional)
				"default" => 0, //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
		
		//add text input field
		$field_slug = "custom_textinputcursor_image_upload";
		$field_label = "Custom Cursor Image for Text Inputs";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_image_upload'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Enter a URL or upload a custom cursor image to use for your text inputs. This cursor will apply to text fields, textareas (large text fields), password fields, search fields, and email fields. Leave empty to use the browser default. You can upload png, jpg, and gif files. Maximum cursor dimensions are 128 x 128 pixels, but smaller images recommended.' //description of the field (optional)
			)			
		);
		
		//add text input field
		$field_slug = "x_offset_textinput";
		$field_label = "X Offset for Text Input Cursor";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Set the hotspot, in pixels, from the left of the image. 0 will be all the way at the left (eg: If you enter \'4\', that would mean the hotspot is 4 pixels to the right of the image\'s left side.)', //description of the field (optional)
				"maxlength" => 3, //max character length for the input (optional)
				"default" => 0, //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
			
		//add text input field
		$field_slug = "y_offset_textinput";
		$field_label = "Y Offset for Text Input Cursor";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Set the hotspot, in pixels, from the top of the image. 0 will be all the way at the top (eg: If you enter \'4\', that would mean the hotspot is 4 pixels down from the top of the image.)', //description of the field (optional)
				"maxlength" => 3, //max character length for the input (optional)
				"default" => 0, //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
		
		//add text input field
		$field_slug = "custom_linksandbuttonscursor_image_upload";
		$field_label = "Custom Cursor Image for Links & Buttons";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_image_upload'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Enter a URL or upload a custom cursor image to use for your links and buttons. Leave empty to use the browser default. You can upload png, jpg, and gif files. Maximum cursor dimensions are 128 x 128 pixels, but smaller images recommended.' //description of the field (optional)
			)			
		);
		
		//add text input field
		$field_slug = "x_offset_linksandbuttons";
		$field_label = "X Offset for Links & Buttons Cursor";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Set the hotspot, in pixels, from the left of the image. 0 will be all the way at the left (eg: If you enter \'4\', that would mean the hotspot is 4 pixels to the right of the image\'s left side.)', //description of the field (optional)
				"maxlength" => 3, //max character length for the input (optional)
				"default" => 0, //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
			
		//add text input field
		$field_slug = "y_offset_linksandbuttons";
		$field_label = "Y Offset for Links & Buttons Cursor";
		$field_id = $this->plugin_slug.'_'.$field_slug;
		register_setting($this->plugin_slug.'_option_group', $field_id);
		add_settings_field(
		    $field_id,
		    $field_label, 
		    array($this, 'create_a_text_input'), //callback function for text input
		    $this->plugin_slug.'-setting-admin',
		    $this->plugin_slug.'_setting_section',
		    array(								// The array of arguments to pass to the callback.
				"id" => $field_id, //sends field id to callback
				"desc" => 'Set the hotspot, in pixels, from the top of the image. 0 will be all the way at the top (eg: If you enter \'4\', that would mean the hotspot is 4 pixels down from the top of the image.)', //description of the field (optional)
				"maxlength" => 3, //max character length for the input (optional)
				"default" => 0, //sets the default field value (optional), when grabbing this option value later on remember to use get_option(option_name, default_value) so it will return default value if no value exists yet
			)			
		);
	
    } //end of page_init function
	
	/**
	 * This following set of functions handle all input field creation
	 * 
	 */
	function create_image_upload($args) {
		?>
			<?php
			//set default value if applicable
            if(isset($args["default"])) {
                $default = $args["default"];
            } else {
                $default = false;
            }
            ?>
            <input class="motech_upload_image" type="text" size="36" name="<?php echo $args["id"] ?>" value="<?php echo get_option($args["id"], $default) ?>" /> 
            <input class="motech_upload_image_button" class="button" type="button" value="Upload Image" />
        	<br />
			<?php
			if(isset($args["desc"])) {
				echo "<span class='description'>".$args["desc"]."</span>";
			} else {
				echo "<span class='description'>Enter a URL or upload an image.</span>";	
			}
			?>
            <?php
				$current_image = get_option($args["id"],$default);
				if(!empty($current_image)) {
					echo "<br><strong>Preview</strong><br><img style='padding-left:20px; max-width: 50%; max-height: 400px;' src='".$current_image."'>";	
				}
			?>
        <?php
	} // end create_image_upload
	function create_a_checkbox($args) {
		$html = '<input type="checkbox" id="'  . $args["id"] . '" name="'  . $args["id"] . '" value="1" ' . checked(1, get_option($args["id"], $args["default"]), false) . '/>';
		
		// Here, we will take the desc argument of the array and add it to a label next to the checkbox
		$html .= '<label for="'  . $args["id"] . '"> ' . $args["desc"] . '</label>'; 
		
		echo $html;
		
	} // end create_a_checkbox
	
	function create_a_text_input($args) {
		//grab placeholder if there is one
		if(isset($args["placeholder"])) {
			$placeholder_html = "placeholder=\"".$args["placeholder"]."\"";
		}	else {
			$placeholder_html = "";
		}
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		//grab maxlength if there is one
		if(isset($args["maxlength"])) {
			$max_length_html = "maxlength=\"".$args["maxlength"]."\"";
		}	else {
			$max_length_html = "";
		}
		// Render the output
		echo '<input type="text" '  . $placeholder_html . $max_length_html . ' id="'  . $args["id"] . '" class="'.$args["class"].'" name="'  . $args["id"] . '" value="' . get_option($args["id"], $default) . '" />';
		if($args["desc"]) {
			echo "<p class='description'>".$args["desc"]."</p>";
		}
		
	} // end create_a_text_input
	
	function create_a_textarea_input($args) {
		//grab placeholder if there is one
		if($args["placeholder"]) {
			$placeholder_html = "placeholder=\"".$args["placeholder"]."\"";
		}	else {
			$placeholder_html = "";
		}
		//get default value if there is one
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		// Render the output
		echo '<textarea '  . $placeholder_html . ' id="'  . $args["id"] . '"  name="'  . $args["id"] . '" rows="5" cols="50">' . get_option($args["id"], $default) . '</textarea>';
		if($args["desc"]) {
			echo "<p class='description'>".$args["desc"]."</p>";
		}		
	}
	
	function create_a_radio_input($args) {
	
		$radio_options = $args["radio_options"];
		$html = "";
		if($args["desc"]) {
			$html .= $args["desc"] . "<br>";
		}
		//get default value if there is one
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		foreach($radio_options as $radio_option) {
			$html .= '<input type="radio" id="'  . $args["id"] . '_' . $radio_option["value"] . '" name="'  . $args["id"] . '" value="'.$radio_option["value"].'" ' . checked($radio_option["value"], get_option($args['id'], $default), false) . '/>';
			$html .= '<label for="'  . $args["id"] . '_' . $radio_option["value"] . '"> '.$radio_option["label"].'</label><br>';
		}
		
		echo $html;
	
	} // end create_a_radio_input callback

	function create_a_select_input($args) {
	
		$select_options = $args["select_options"];
		$html = "";
		if($args["desc"]) {
			$html .= $args["desc"] . "<br>";
		}
		//get default value if there is one
		if(isset($args["default"])) {
			$default = $args["default"];
		} else {
			$default = false;
		}
		$html .= '<select id="'  . $args["id"] . '" name="'  . $args["id"] . '">';
			foreach($select_options as $select_option) {
				$html .= '<option value="'.$select_option["value"].'" ' . selected( $select_option["value"], get_option($args["id"], $default), false) . '>'.$select_option["label"].'</option>';
			}
		$html .= '</select>';
		
		echo $html;
	
	} // end create_a_select_input callback

	// add the shortcode handler 
/*	function addShortcodeHandler($atts, $content = null) {
			extract(shortcode_atts(array( "height" => '' ), $atts));
			if ($height > 0 ) {
				$spacer_css = "padding-top: " . $height . ";";
			} elseif($height < 0) {
				$spacer_css = "margin-top: " . $height . ";";
			}
			return '<span style="display:block;clear:both;height: 0px;'.$spacer_css.'"></span>';
	}*/
	
	
/*	function add_custom_button() {
	   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		 return;
	   if ( get_user_option('rich_editing') == 'true') {
		 add_filter('mce_external_plugins', array($this, 'add_custom_tinymce_plugin'));
		 add_filter('mce_buttons', array($this, 'register_custom_button'));
	   }
	}*/
	
/*	function register_custom_button($buttons) {
	   array_push($buttons, "|", get_class($this));
	   return $buttons;
	}*/
	
/*	function add_custom_tinymce_plugin($plugin_array) {
	   //use this in a plugin
	   $plugin_array[get_class($this)] = plugins_url( 'editor_plugin.js' , __FILE__ );
	   //use this in a theme
	   //$plugin_array[get_class($this)] = get_bloginfo('template_url').'/editor_plugin.js';
	   return $plugin_array;
	}*/
	
/*	function my_refresh_mce($ver) {
	  $ver += 5;
	  return $ver;
	}*/
	
	function plugin_row_links($links, $file) {
		$plugin = plugin_basename(__FILE__); 
		if ($file == $plugin) // only for this plugin
				return array_merge( $links,
			array( '<a target="_blank" href="http://www.linkedin.com/in/ClevelandWebDeveloper/">' . __('Find me on LinkedIn' ) . '</a>' ),
			array( '<a target="_blank" href="http://twitter.com/ClevelandWebDev">' . __('Follow me on Twitter') . '</a>' )
		);
		return $links;
	}
	
	//add plugin action links logic
	function add_plugin_action_links( $links ) {
	 
		return array_merge(
			array(
				'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page='.$this->plugin_slug.'-setting-admin">Settings</a>'
			),
			$links
		);
	 
	}
	
	function load_scripts() {
		if (!is_admin()) {
			//wp_enqueue_script('progression_base', plugins_url( 'squiggle.js' , __FILE__ ), false, false, true);
			add_action('wp_head', array($this, 'plugin_data'), 100);
		}
	}
	
	function plugin_data() {
			$default_cursor_setting = get_option('cursor_control_custom_defaultcursor_image_upload', false);
			if(!empty($default_cursor_setting)) { //there is an image set
				$default_cursor = $default_cursor_setting;
			} else {
				$default_cursor = false;
			}
			
			$textinput_cursor_setting = get_option('cursor_control_custom_textinputcursor_image_upload', false);
			if(!empty($textinput_cursor_setting)) { //there is an image set
				$textinput_cursor = $textinput_cursor_setting;
			} else {
				$textinput_cursor = false;
			}
			
			$linksandbuttons_cursor_setting = get_option('cursor_control_custom_linksandbuttonscursor_image_upload', false);
			if(!empty($linksandbuttons_cursor_setting)) { //there is an image set
				$linksandbuttons_cursor = $linksandbuttons_cursor_setting;
			} else {
				$linksandbuttons_cursor = false;
			}
		?>
            <style>
				<?php if ($default_cursor) : ?>
				body {cursor:url('<?php echo $default_cursor ?>') <?php echo get_option('cursor_control_x_offset', 0) ?> <?php echo get_option('cursor_control_y_offset', 0) ?>, auto;}
				<?php endif ?>
				<?php if ($textinput_cursor) : ?>
				input[type="text"], textarea, input[type="password"], input[type="email"], input[type="search"] {cursor:url('<?php echo $textinput_cursor ?>') <?php echo get_option('cursor_control_x_offset_textinput', 0) ?> <?php echo get_option('cursor_control_y_offset_textinput', 0) ?>, auto;}
				<?php endif ?>
				<?php if ($linksandbuttons_cursor) : ?>
				input[type="submit"], button, a {cursor:url('<?php echo $linksandbuttons_cursor ?>') <?php echo get_option('cursor_control_x_offset_linksandbuttons', 0) ?> <?php echo get_option('cursor_control_y_offset_linksandbuttons', 0) ?>, auto;}
				<?php endif ?>
			</style>        
        <?php
	}
	
} //end class

new cursor_control();