<?php
/*
===================================================================
Plugin Name:  ONet Regenerate Thumbnails
Plugin URI:   http://onetdev.com/repo/onet-regen-thumbnails
Description:  This plugin helps you to fix any missing thumbnail issue in a fairly short time. The only thing you need to do is just few simple click and the plugin does the rest. It can be really helpful if you have thousands of images in your database.
Git:          https://bitbucket.org/orosznyet/onet-regen-thumbs
Version:      1.5
Author:       József Koller
Author URI:   http://www.onetdev.com
Text Domain:  onetrt
Domain Path:  /lang

===================================================================

Copyright (C) 2014 József Koller

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

===================================================================
*/


// Register init function
add_action( 'init', 'ONetRegenThumbs_wrap' );
function ONetRegenThumbs_wrap() {
global $ONetRegenThumbs_inst;
	ob_start();
	$ONetRegenThumbs_inst = new ONetRegenThumbs();
}

// Then the class
class ONetRegenThumbs {
	var $menu_item; // Menu item's hook
	var $cap = null; // used capabilities
	var $opts = null; // options
	var $tasks = null; // tasks meta. does not contains attachment IDs.
	var $task_items = array(); // Task related items in one array

	

	/******************************************
	* CONSTRUCTING & REGISTERING              *
	******************************************/



	/**
	* Register all important functions and filters
	* @since 1.0
	* @param void
	* @return void
	**/
	public function __construct() {
		// Load localizations. You can add you own localisation file (eg: onetrt-hu_HU.mo) into lang folder.
		load_plugin_textdomain('onetrt', false, '/onet-regen-thumbnails/lang');

		$this->load_opts();
		$plugin = plugin_basename( __FILE__ );

		// Register actions
		add_action('wp_loaded',                     array($this, 'reg_custom_sizes') );  // Add custom images sized ;)
		add_action('admin_menu',                    array($this, 'add_admin_menu') );  // Add menu item under "Tools"
		add_action('admin_enqueue_scripts',         array($this, 'admin_assets_enqueue') ); // Enqueue important scripts/css files
		add_action('wp_ajax_onetrt',                array($this, 'ajax_resume_image_process') ); // The heart of the plugin, this handle each resize request.
		add_action('media_buttons_context',         array($this, 'admin_posteditor_button') );
		add_filter('plugin_action_links_'.$plugin,  array($this, 'plugin_page_link'));

		// Apply filters
		add_filter('media_row_actions',             array($this, 'image_action_row'), 10, 2);
		add_filter('jpeg_quality',                  array($this, 'image_full_quality') );
		add_filter('wp_editor_set_quality',         array($this, 'image_full_quality') );

		// Related capabilities
		$this->cap = apply_filters( 'onetrt_cap', 'manage_options' );
	}


	/**
	* Load all the required options (important for the whole page)
	* @since 1.4
	* @param boolean $force_update
	* @param boolean $return_defaults
	* @return object list of options
	**/
	function load_opts ( $force_update = 0, $return_defaults = 0 ) {
		// Load options
		$default_opts = array(
			"after_media_btn"   => false,         // Display button after "upload media" in post editor
			"only_complement"   => true,          // Regenerate every thumbnail or only the missing ones.
			"bulk_action"       => true,          // Bulk action in post listers.
			"advanced"          => false,         // Display advanced settings
			"massgen"           => false,         // "Speed" mode for image processing. So called "unlimited images / query"
			"success_redir"     => false,         // Redirect user after success process.
			"quality_overwrite" => false,         // Overwrite default WP JPG quality
			"quality"           => 90,            // Quality of saved images (useg for JPGs)
			"thumbs"            => array()
		);

		// Return with the default options
		if ( ! $force_update && $this->opts != null ) return $this->opts; 
		if ( $return_defaults ) return $default_opts;

		// Notmal mode
		$custom_opts = get_option("onetrt_opts", array());
		$this->opts = (object)array_merge($default_opts,$custom_opts);
		return $this->opts;
	}


	/**
	* Register menu item for interface
	* @since 1.0
	* @param void
	* @return void
	**/
	function add_admin_menu () {
		$this->menu_item = add_management_page( __( 'Regenerate Thumbnails', 'onetrt' ), __( 'Regen. Thumbnails', 'onetrt' ), $this->cap, 'onet-regen-thumbs', array($this, 'ui_main') );
	}

	/**
	* Register/Load the needed JS and CSS.
	* @since 1.0
	* @param (array) Hook for current screen
	* @return void
	**/
	public function admin_assets_enqueue ($hook_suffix) {
	global $current_screen;
		// Register custom scripts.
		wp_register_script("onet-regenthumb-main", plugins_url( 'assets/js/main.js', __FILE__ ), array("jquery","jquery-ui-core","jquery-ui-progressbar"), "1.0",true);
		wp_register_script("onet-regenthumb-postactions-main", plugins_url( 'assets/js/post-actions.js', __FILE__ ), array("jquery"), "1.0",true);
		wp_register_style( "onet-regenthumb-styles", plugins_url( 'assets/css/main.css', __FILE__ ) );
		wp_register_style( "jquery-ui-theme-flick", plugins_url( 'assets/js/jquery-ui/flick/jquery-ui-1.10.3.custom.min.css', __FILE__ ) );
		$postMime = isset($_GET['post_mime_type']) ? $_GET['post_mime_type'] : null;
		$opts = $this->load_opts();

		$lang = array(
			"unload_confirm"           => __("You are about to leave the page which means that the remaining thumbnail will not be processed. Do you really want to leave?","onetrt"),
			"error_terminated"         => __('There were some problems with your server or your internet connection. Please try again later.','onetrt'),
			"error_noimage"            => __('You have no images. Sorry, I can\'t help you at the moment.','onetrt'),
			"error_retrissue"          => __('There was a connection issue. {retryNum} more retries left.','onetrt'),
			"error_serverside"         => __('The script encountered a fatal error. The process has been terminated.','onetrt'),
			"error_ecode"              => __('Fatal error. Code {errorCode}.','onetrt'),
			"error_intended"           => __('The server does not answered in time but the process will go on.','onetrt'),
			"success_done"             => __('The process is done. You can leave the page.','onetrt'),
			"success_thumbs_done"      => __('The following thumbnails were generated: {imageList}.','onetrt'),
			"success_update_itemsdone" => __('{fileNum} images were updated.'),
			"notice_issueresolved"     => __('Connection issue resolved.','onetrt'),
			"notice_started"           => __("Process started.","onetrt"),
			"time_infinity"            => __('infinity','onetrt'),
			"time_year"                => __('y','onetrt'),
			"time_month"               => __('m','onetrt'),
			"time_day"                 => __('d','onetrt'),
			"time_hour"                => __('h','onetrt'),
			"time_min"                 => __('m','onetrt'),
			"time_sec"                 => __('s','onetrt')
			);
		wp_localize_script("onet-regenthumb-main", "onetrt_optspage", $lang );
		wp_localize_script("onet-regenthumb-main", "onetrt_opts", (array)$opts );

		// Scripts for main ui
		if ( $hook_suffix == $this->menu_item ) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('onet-regenthumb-main');
			wp_enqueue_style('onet-regenthumb-styles');
			wp_enqueue_style('jquery-ui-theme-flick');

		// Script for post editor/lister
		} else if ( in_array( $current_screen->post_type, get_post_types( '', 'names' ) ) || ($current_screen->base == "upload" && $postMime == "image") || $current_screen->base == "upload" ) {
			$lang = array(
				"labels" => array(
					"bulk_action"     => __("Regen. thumbnails","onetrt"),
					"bulk_confirm"    => __("This action may take a while. Do you want to continue? ","onetrt"),
					"bulk_noselected" => __("Select at least one entry to perform this action.","onetrt")
				),
				"is_lister"   => in_array($current_screen->base, array("edit","upload")) ? 1 : 0,
				"is_imageatt" => ($current_screen->base == "upload" && $postMime == "image") || $current_screen->base == "upload" ? 1 : 0,
				"media_btn"   => $opts->after_media_btn ? 1 : 0,
				"bulk_action" => $opts->bulk_action ? 1 : 0,
				"refresh_uri" => $this->admin_uri( array("refresh_byid"=>"") )
				);
			wp_localize_script("onet-regenthumb-postactions-main", "onetrt_postaction", $lang );
			wp_enqueue_script("onet-regenthumb-postactions-main");
		}
	}



	/******************************************
	* WORDPRESS HACKS                         *
	******************************************/



	/**
	* Adds a simple link in plugin manager
	* @since 1.1
	* @param (array) active links
	* @param (array) extended links
	**/
	function plugin_page_link( $links ) {
		if (current_user_can( $this->cap)) array_push( $links, '<a href="tools.php?page=onet-regen-thumbs">'.__("Settings","onetrt").'</a>' );
		return $links;
	}

	/**
	* This will add a button after "Upload" button in post editor
	* @since 1.0
	* @param void
	* @param void
	**/
	public function admin_posteditor_button() {
	global $post;
		$opts = $this->load_opts();
		if (!current_user_can( $this->cap) || !$opts->after_media_btn) return;
		return ' <a href="'.$this->admin_uri(array("refresh_byid"=>$post->ID)).'" target="_blank" id="onetrt-single" class="button" title="'.__('Regenerate thumbnail for related images','onetrt').'"><span class="ico"></span> '.__('Regenerate thumbnails','onetrt').'</a>';
	}

	/**
	* Adds a direct link to regenerate thumbnails for a particular image in attachments
	* @since 1.0
	* @param (array) current links, (object) post data
	* @return (array) updated links
	**/
	public function image_action_row ($att, $post) {
		if ( preg_match("/^image/i",$post->post_mime_type) ) {
			$att[] = '<a href="'.$this->admin_uri(array("refresh_byid"=>$post->ID)).'" target="_blank" click="'.__("This will redirect you to regeneration page. Do you want to continue?","onetrt").'">'.__("Regenerate thumbs", "onetrt").'</a>';
		}
		return $att;
	}

	/**
	* Register thumbnails made by user in admin
	* @since 1.2
	* @param void
	* @return void
	**/
	function reg_custom_sizes() {
		$opts = $this->load_opts();
		if ( $opts != null && is_array($opts->thumbs) && count($opts->thumbs) ) {
			foreach ($opts->thumbs AS $thumb) add_image_size( $thumb['name'], $thumb['width'], $thumb['height'], $thumb['crop'] );
		}
	}


	/**
	* Update Wordpress JPG quality
	* @since 1.3
	* @param (int) current quality
	**/
	function image_full_quality($quality) {
		$opts = $this->load_opts();
		if ($opts->quality_overwrite == true) {
			return $opts->quality;
		} return $quality;
	}



	/******************************************
	* ADMINISTRATION INTERFACE                *
	******************************************/


	/**
	* Main user interface. This function prepare each PHP variable for the view which is being called at the end.
	* @since 1.0
	* @param void
	* @return void
	**/ 
	public function ui_main () {
	global $wpdb, $_wp_additional_image_sizes, $onetrt_edit_thumb, $onetrt_admin_meta_boxes, $onetrt_dashboard_vars;

		# Basic variables
		$own            = $this;                           // make sure that view can access instance variables.
		$update_msg     = array();                  // blank variable for notification
		$nonce          = wp_create_nonce( "onetrt" );   // used for ajax validation
		$custom_dims    = count($_wp_additional_image_sizes); // Number of custom dimensions
		$ids            = array();                         // Contains each queued IDs
		$ids_count      = 0;                         // Number of actual images in task
		$is_total_regen = true;                 // Basicly regen process is "total" (if this is false it means partial regen)
		$update_url     = false;                    // If true it will put task ID to the URL so it won't be "double stored"
		$task_id        = 0;                           // Task ID for regenerating
		$opts           = $this->load_opts();
		
		# Clean tasks before doing anything.
		$this->clean_tasks();

		# Define admin metabox überdatas
		$onetrt_admin_meta_boxes = array(
			"settings" => array(
				"file"  => "admin.meta.settings.php",
				"title" => __("Settings", "onetrt")
			),
			"customdim" => array(
				"file"  => "admin.meta.customdim.php",
				"title" => __("Custom dimension manager", "onetrt")
			),
			"dashboard" => array(
				"file"  => "admin.meta.dashboard.php",
				"title" => __("Dashboard", "onetrt")
			)
		);


		# Default notifications
		if ( isset($_GET['cancel']) ) $update_msg[] = __('Process was terminated manually.','onetrt');
		if ( isset($_GET['success']) ) $update_msg[] = __('Regenerate was successful.','onetrt');
		if ( isset($_GET['settings_updated']) ) $update_msg[] = __( 'Changes has been saved.', 'onetrt' );


		# Thumbnail editor save code:
		# Save custom thumbnail dimensions
		if (isset($_POST['save_thumb']) || isset($_POST['update_thumb'])) {
			$thumb_meta = array(
				"name"   => sanitize_title_with_dashes(remove_accents($_POST['thumb_name'])),
				"width"  => (int)$_POST['thumb_width'],
				"height" => (int)$_POST['thumb_height'],
				"crop"   => isset($_POST['thumb_crop'])
				);
			if (!empty($_POST['ename'])) unset($opts->thumbs[$_POST['ename']]); // remove previous instance if ename is exists
			$opts->thumbs[$thumb_meta['name']] = $thumb_meta;
			update_option("onetrt_opts",(array)$opts);
			$update_msg[] = isset($_POST['save_thumb']) ? __('Thumbnail saved.', 'onetrt') : __('Thumbnail updated.','onetrt');
		}


		# Thumbnail editor save code:
		# Remove thumbnail dimensions
		if (isset($_GET['thumb_remove']) && isset($opts->thumbs[$_GET['thumb_remove']])) {
			unset($opts->thumbs[$_GET['thumb_remove']]);
			update_option("onetrt_opts",(array)$opts);
			$custom_dims--;
			$update_msg[] = __('Thumbnail removed.', 'onetrt');
		}


		# Check if thumbeditor is requested
		$onetrt_edit_thumb = array( "name" => "", "width" => "", "height" => "", "crop" => false ); // Default variables
		if (!empty($_GET['thumb_edit']) && !empty($opts->thumbs[sanitize_title_with_dashes($_GET['thumb_edit'])]) ) {
			$onetrt_edit_thumb = array_merge($onetrt_edit_thumb,$opts->thumbs[sanitize_title_with_dashes($_GET['thumb_edit'])]);
		}


		# Save incoming settings
		if ( isset($_POST['save_settings']) ) {
			$new_opts = array(
				"after_media_btn"   => isset($_POST['after_media_btn']),
				"only_complement"   => isset($_POST['only_complement']),
				"bulk_action"       => isset($_POST['bulk_action']),
				"advanced"          => isset($_POST['advanced']),
				"massgen"           => isset($_POST['massgen']),
				"success_redir"     => isset($_POST['success_redir']),
				"quality_overwrite" => isset($_POST['quality_overwrite']),
				"quality"           => isset($_POST['quality']) ? (int)$_POST['quality'] : 90,
				"thumbs"            => $opts->thumbs
				);
			$new_opts = array_merge( (array)$opts, $new_opts );
			update_option("onetrt_opts", $new_opts );
			$opts = (object)$new_opts;

			// Remopve another notifications.
			$update_msg = array();

			// Redirect user on save
			// Added in 1.5 because there was some problems with "old settings data"
			ob_end_clean();
			wp_redirect( 'tools.php?page=onet-regen-thumbs&settings_updated' );
			exit;
		}


		# Fetch all attachments. WP tends to refuse get_children so that's why i use simple SQL instead.
		$atts = $wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type='attachment' AND post_mime_type LIKE 'image%'");
		$atts_count = count( $atts );

		# Try to get task id from URL
		if ( !empty($_GET['task_id']) && ($task = $this->get_task((int)$_GET['task_id'])) != "no_task") {
			$ids_count = count($task->items);
			$task_id = $task->meta->id;

			// var_dump($task);

		# Fetch specific IDs if it's provided in the URL
		} else if (!empty($_GET['refresh_byid']) && preg_match("/^[0-9\;]*$/i",$_GET['refresh_byid'])) {
			$ids_sql = "SELECT ID,post_type,post_mime_type FROM ".$wpdb->prefix."posts WHERE `ID` IN (".str_replace(";", ",", $_GET['refresh_byid']).")";
			$ids_raw = $wpdb->get_results($ids_sql);
			$is_total_regen = false;
			
			foreach ($ids_raw AS $item) {
				// Fetch related attachments if the element is not a attachment
				if ($item->post_type != "attachment") {
					$related_atts = get_children(array(
						'numberposts'    => 1000,
						'order'          => 'ASC',
						'post_mime_type' => 'image',
						'post_parent'    => $item->ID,
						'post_status'    => null,
						'post_type'      => 'attachment',
						));
					foreach ($related_atts AS $rel_att) $ids[] = $rel_att->ID;

				// if the result is attachment
				} else if (preg_match("/^image/i",$item->post_mime_type) ) $ids[] = $item->ID;
			}
		}


		# If there is no incoming partial regen request use total array
		if (count($ids) < 1 && $task_id < 1) {
			$ids_count = $atts_count;
			$task_id = 0;
			// Old method: add all attachments. foreach ($atts AS $att) $ids[] = $att->ID;
		# otherwise create task for selected images
		} else if ($task_id < 1) {
			$ids_count = count($ids);
			$task_id = $this->create_task($ids);
			$update_url = $this->admin_uri(array("task_id"=>$task_id),false);
		}

		# Final step
		# Pass variables to Dashboard
		$onetrt_dashboard_vars = array(
			"media_image_list_uri" => admin_url( "upload.php?post_mime_type=image" ),     // link to Wordpress's media manager
			"cancel_uri"           => $this->admin_uri(array("cancel"=>1)),               // Cancel URL which displays a "terminated" notification
			"reset_uri"            => $this->admin_uri(),                                 // This will be visible if custom regen set is provided
			"success_uri"          => $this->admin_uri(array("success"=>1)),              // success redirect ID
			"custom_dims"          => $custom_dims,                                       // Actual custom dimensions
			"ids_count"            => $ids_count,                                         // Number of items relate to current task
			"task_id"              => $task_id,                                           // Task ID to resume
			"update_url"           => $update_url,                                        // Update URL which is a simple url or blank or false
			"atts_count"           => $atts_count,                                        // Total attachments
			"is_total_regen"       => $is_total_regen                                     // True is the the session is based on the whole library
			);

		# Give the variables to view and render it instantly.
		require "view/main_ui.php";
		return;
	}

	/**
	* Draws side metabox for admin
	* @since 1.3
	* @param (string) element ID
	* @param (string) box title
	* @param (string) box content
	**/
	function _ui_admin_render_meta ($id, $title, $content) {
		require(dirname(__FILE__)."/view/admin.meta.support.php");
	}

	/**
	* Custom support metabox
	* @since 1.3
	* @param void
	* @return void
	**/
	function ui_admin_metabox ($box) {
	global $onetrt_edit_thumb, $onetrt_admin_meta_boxes, $onetrt_dashboard_vars;
		$opts = $this->load_opts();
		$own = $this;

		if ($box == "support") {
			$this->_ui_admin_render_meta(
			"onetrt-support",
			__("Contact and Support", "onetrt"),
				sprintf(__('If you have any question, problem or suggestion please check out the %s and if you can\'t find the answer for your question please do not hesitate to ask.', "onetrt"),
					'<a href="http://wordpress.org/support/plugin/onet-regenerate-thumbnails">'.__("official support page", "onetrt").'</a>'
				)."<br/><br/><strong>".__("Premium Support","onetrt")."</strong><br/>".
				sprintf(__('The developer provides premium support for low hourly rate. If you want to get exclusive support directly from the developer please write to %s. ','onetrt'),'<a href="mailto:orosznyet@gmail.com?subject='.urlencode(__("[PREMIUM SUPPORT] ONet Regen Thumbnails","onetrt")).'">orosznyet@gmail.com</a>'
				)
			);
		} else if (isset($onetrt_admin_meta_boxes[$box]) ) {
			$onetrt_admin_meta_boxes[$box]['file'];
			ob_start();
			require(dirname(__FILE__)."/view/".$onetrt_admin_meta_boxes[$box]['file']);
			$cont = ob_get_clean();
			if (strlen(trim($cont)) > 1) $this->_ui_admin_render_meta("onetrt-".$box,$onetrt_admin_meta_boxes[$box]['title'],$cont);
		}
	}



	/******************************************
	* IMAGE PROCESSING                        *
	******************************************/



	/**
	* Genererate each thumbnail for a particular attachment
	* @since 1.0
	* @param (int) attachment ID
	* @return (null|array) result
	**/
	public function generate_thumb_all ( $att_id ) {
		$att_raw = get_posts( array(
			'include'        => $att_id,
			'post_type'      => 'attachment',
			'post_mime_type' => 'image'
		));
		if (!count($att_raw)) return null;
		
		$att = $att_raw[0];
		$file = get_attached_file( $att->ID );
		$meta = wp_generate_attachment_metadata( $att->ID, $file );
		wp_update_attachment_metadata( $att->ID, $meta );
		
		return $att;
	}

	/**
	* Regenerate missing image dimensions for a particular attachment
	* @param (int) attachment ID
	* @return (array) new metadata for actual image
	**/
	public function generate_thumb_missing ( $att_id ) {
		// Fetch the attachment
		$att_raw = get_posts( array(
			'include'        => $att_id,
			'post_type'      => 'attachment',
			'post_mime_type' => 'image'
		));
		$att = $att_raw[0];
		$file = get_attached_file( $att_id ); // COMPLETE THIS SHIT.

		$metadata = array();
		if ( preg_match('!^image/!', get_post_mime_type( $att )) && file_is_displayable_image($file) ) {
			$imagesize = getimagesize( $file );
			$metadata['width'] = $imagesize[0];
			$metadata['height'] = $imagesize[1];
			list($uwidth, $uheight) = wp_constrain_dimensions($metadata['width'], $metadata['height'], 128, 96);
			$metadata['hwstring_small'] = "height='$uheight' width='$uwidth'";

			// Make the file path relative to the upload dir
			$metadata['file'] = _wp_relative_upload_path($file);

			// make thumbnails and other intermediate sizes
			global $_wp_additional_image_sizes;

			foreach ( get_intermediate_image_sizes() as $s ) {
				$sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => FALSE );
				if ( isset( $_wp_additional_image_sizes[$s]['width'] ) )
					$sizes[$s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); // For theme-added sizes
				else
					$sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
				if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
					$sizes[$s]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] ); // For theme-added sizes
				else
					$sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
				if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
					$sizes[$s]['crop'] = intval( $_wp_additional_image_sizes[$s]['crop'] ); // For theme-added sizes
				else
					$sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
			}

			$sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

			// Only generate image if it does not already exist
			$att_meta = wp_get_attachment_metadata($att_id);

			foreach ($sizes as $size => $size_data ) {
				// Size already exists
				if (isset($att_meta['sizes'][$size]) && file_exists($this->imageTrySize($file,$size_data['width'],$size_data['height']))) {
					$metadata['sizes'][$size] = $att_meta['sizes'][$size];
				} else {
					// Generate new image
					$resized = image_make_intermediate_size( $file, $size_data['width'], $size_data['height'], $size_data['crop'] );
					if ( $resized )
						$metadata['sizes'][$size] = $resized;
				}
			}

			// Get image meta and update database manually
			$metadata['image_meta'] = wp_read_image_metadata( $file );
			update_post_meta( $att_id, "_wp_attachment_metadata", $metadata );
		}

		return $att;
	}

	/**
	* Take care of incoming AJAX requests
	* Code list: 0 - init, 1 - no cap, 2 - invalid nonce, 3 - missing proc id, 4 - invalid proc id, 200 - all right, 201 - completed
	* @since 1.0
	* @param (boolean) die with json on finish or return result
	* @return [(array) process result]
	**/
	public function ajax_resume_image_process () {
	global $wpdb;

		$return = array("code"=>0,"files_done"=>array(),"task_id"=>0,"task_meta"=>null,"debug"=>array());
		$task_id = 0;
		$opts = $this->load_opts();

		# Check nonce and capabilities
		if (!current_user_can($this->cap) ) $return['code'] = 1; // noCap
		if (!wp_verify_nonce( $_GET['nonce'], "onetrt" ) ) $return['code'] = 2; // invalidNonce
		if (!isset($_GET['task_id'])) $return['code'] = 3; // no attachment ID

		# Generate task id for total it there is none
		if ((int)$_GET['task_id'] == 0 && $return['code'] == 0) {
			header("Onetrt-ajax: started");
			$ids = array();
			$atts = $wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type='attachment' AND post_mime_type LIKE 'image%'");
			$atts_count = count( $atts );
			foreach ($atts AS $att) $ids[] = $att->ID;
			$return['code'] = 200;
			$return['task_id'] = $this->create_task($ids);
			$task_data = $this->get_task($return['task_id'],true);
			$return['task_meta'] = $task_data->meta;

		# Load items
		} else if ($return['code'] == 0) {
			if (($task = $this->get_task((int)$_GET['task_id'])) != "no_task") {
				header("Onetrt-ajax: started");
				if (!isset($_GET['status_update'])) {
					$return['code'] = 200;
					$return['task_id'] = $task->meta->id;
					$return['task_meta'] = $task->meta;
					$atts = $task->items;
					$doNext = true;

					$startTime = $this->microtime_float(); // Init time for script so we know
					$initTime = $startTime - $_SERVER['REQUEST_TIME']; // Elapsed time
					$finalTime = $startTime + (int)$this->get_exectime_limit() - ( $initTime*2 ); // The last second of the script
					$memoryLimit = $this->get_memory_limit(); // Actual memory limit
					$usedMemory = @memory_get_usage(); // get actual memory usage
					$i = 0;

					// Okey, now lets move on to the image processing
					while ($doNext == true) {
						if ($opts->only_complement == true) :
							// Create only missing images
							$result = $this->generate_thumb_missing((int)$atts[0]);
						else :
							// Regenerate each thumbnails sizes
							$result = $this->generate_thumb_all((int)$atts[0]);
						endif;

						// Update output and refresh database
						$return['files_done'][] = array("id"=>$result->ID,"name"=>$result->post_title);
						$this->remove_attachment_from_task($task->meta->id,(int)$atts[0]);
						
						// Prepare next query if settings want me to do so
						if ($opts->massgen != true) $doNext = false;
						// Otherwise check if there is enough memory and script runtime left to proceed to the next item
						else {
							$doNext = true;
							$because = "there_is_more";

							// If the exec time is over the final time
							if ($this->microtime_float() > $finalTime) {
								$doNext = false;
								$because = "short_time";
							}
							// Check if free memory is too low
							if ($memoryLimit < 0 && ($memoryLimit*0.7) > @memory_get_usage()) {
								$doNext = false;
								$because = "low_memory";
							}
							// Check if there is no more items
							if (count($atts) <= 1) {
								$doNext = false;
								$because = "no_more";
							} else {
								unset($atts[0]);
								$atts = array_values($atts);
							}

							// This will help to debug anyway
							if ( isset( $atts[$i] ) ) {
								$return['mass_debug'][(int)$atts[$i]] = array(
									"actual_time"=>$this->microtime_float(),
									"final_time"=>$finalTime,
									"memory_limit"=>$memoryLimit,
									"memory_usage"=>@memory_get_usage(),
									"donext"=>$doNext,
									"because"=>$because
								);
							}
						}
						$i++;
					}

				} else {
					$return['code'] = 200;
					$return['task_meta'] = $task->meta;
				}
			} else $return['code'] == 4;
		}

		# Fine, return the stuff
		header("Onetrt-ajax: finished");
		echo json_encode($return);
		exit;
	}



	/******************************************
	* TASK MANAGER                            *
	* This section was introduced in 1.3      *
	******************************************/



	/**
	* Create and add task to database. This function also updates cache too.
	* @since 1.3
	* @param (array) task items
	* @return (int) id for the task
	**/
	function create_task ($items) {
		if (!is_array($items)) return "array_required";
		else $items = array_unique($items);
		$id = floor(time()/20).rand(1,9);

		# Check if ID is reserved and if yes request try new id
		$tasks = $this->get_tasks(true,true);
		if (isset($tasks[$id])) return $this->create_task($items);

		# Otherwise add a new tasklist
		$task_meta = array(
			"id"          => $id,
			"created"     => time(),
			"updated"     => time(),
			"finished"    => 0,
			"items_total" => count($items),
			"items_left"  => count($items),
			"items_done"  => 0
			);

		# Update task array
		$tasks[$id] = (object)$task_meta;
		update_option("onetrt_tasks", $tasks);
		$this->tasks = $tasks;

		# Create additional option for task
		add_option( "onetrt_task_".$id, implode(";",$items), '', 'no');

		# return task ID
		return $id;
	}

	/**
	* List all tasks from database, excluding items for tasks
	* @since 1.3
	* @param [(boolean) including finished tasks]
	* @param [(boolean) force re-cache if cache already exists]
	* @return (array) list of actual tasks
	**/
	function get_tasks ($unfiltered=false,$ignore_cache=false) {
		$tasks = array();
		
		# Check things about cache
		if ($this->tasks == null || !is_array($this->tasks) || $ignore_cache == true) {
			$actual = get_option( "onetrt_tasks", "empty" ); // load actual items from database
			// Create task container option entry if current empty.
			if ($actual == "empty" || !is_array($actual)) {
				delete_option( "onetrt_tasks" ); // try to remove previous entry anyway.
				add_option("onetrt_tasks",array(),"","no"); // This variable is not loading automatically!
			// Otherwise load datas into cache
			} else {
				$this->tasks = $actual;
			}
		}

		# Return instantly if results have to be unfiltered
		if ($unfiltered) return $this->tasks;

		# Now filter returning task array
		foreach ($this->tasks AS $id => $task) {
			if ($task->finished < 1) $tasks[$id] = $task;
		}
		return $tasks;
	}

	/**
	* Fetch a task
	* @since 1.3
	* @param (int) task ID
	* @param [(boolean) ignore cache]
	* @return (object) meta and remaining items array
	**/
	function get_task ($id,$ignore_cache=false) {
		$tasks = $this->get_tasks(true);
		$id = (int)$id;
		$task_items = array();

		# script terminates if no task
		if (!isset($tasks[$id])) return "no_task";

		# check for cache
		if (!$ignore_cache && isset($this->task_items[$id])) {
			$task_items = $this->task_items[$id];
		} else {
			$from_opt = get_option( "onetrt_task_".$id, ";" );
			$task_items = explode(";", (string)$from_opt);
			$this->task_items[$id] = $task_items;
		}

		$ret = array("items"=>array_filter($task_items),"meta"=>$tasks[$id]);

		return (object)$ret;
	}

	/**
	* Update task (mark attachment as processed)
	* @since 1.3
	* @param (int) task ID
	* @param (int) attachment ID
	* @return (boolen) if marking was success
	**/

	function remove_attachment_from_task ($task_id, $att_id) {
		$task_id = (int)$task_id;
		$att_id = (int)$att_id;

		# get task items
		$task = $this->get_task($task_id);
		if ($task == "no_task") return false; // If no related task return with false !!

		# Okey, find the entry than remove
		if (is_array($task->items) && ($key = array_search($att_id, $task->items)) !== false) {
			# first, update task item list
			unset($task->items[$key]);
			update_option( "onetrt_task_".$task_id, implode(";",$task->items) );
			$this->task_items[$task_id] = $task->items;

			# second, update task meta
			$task->meta->updated = time();
			$task->meta->items_left = count($task->items);
			$task->meta->items_done = $task->meta->items_total - $task->meta->items_left;
			if ($task->meta->items_left <= 0) $task->meta->finished = time();
			$this->tasks[$task_id] = $task->meta;
			update_option("onetrt_tasks",$this->tasks);
			
		} return false;
	}

	/**
	* Remove done and old items while the task meta remains the same.
	* @since 1.3
	* @param [(int|float) maximum age in hours, you can use float number if you want to be more specific than hours.]
	* @return (int) array of removed items
	**/
	function clean_tasks ($maxage=24) {
		$maxage = (float)$maxage * 3600; // convert to seconds.
		$tasks = $this->get_tasks(true);
		if (count($tasks) < 1) return;
		$removed = array();

		# remove tem lists for finished or expired lists
		foreach ($tasks AS $id => $task) {
			if ($task->updated < time()-$maxage) {
				delete_option( "onetrt_task_".$id );
				$tasks[$id]->finished = time();
				array_push($removed,$id);
			}
		}

		# Then update database as well
		update_option("onetrt_tasks", $tasks);

		return $removed;
	}

	/**
	* Refresh task cache, it is a alias function basically
	* @since 1.3
	* @param void
	* @return void
	**/
	function tasks_recache() {
		$this->get_tasks(false,true);
		return null;
	}



	/******************************************
	* UTILITY METHODS                         *
	******************************************/



	/**
	* Returns with a direct link to admin
	* @since 1.0
	* @param [(assoc array) additional link params]
	* @param [(boolean) use &amp; or &]
	**/
	public function admin_uri ($params=array(),$amp=true) {
		$link = admin_url( "tools.php?page=onet-regen-thumbs");
		if (count($params)) {
			$link .= $amp ? "&amp;" : "&";
			$new_params = array();
			foreach ($params AS $key => $val) $new_params[] = $key."=".(string)$val;
			$link .= implode($amp ? "&amp;" : "&",$new_params);
		}
		return $link;
	}


	/**
	* Try another image url dimensions based on a particular image. This function will not check but returns the patch/file name to cehck on your own.
	* Works best with base filename so you better not try with already formatted file (eg: whatever_01-500x600.jpg)
	* @since 1.0
	* @param (string) image path or image name
	* @param (int) width of image
	* @param (int) height of image
	* @return (string) fromated filename/path
	**/
	public function imageTrySize ($file, $width, $height) {
		$pre = substr($file, 0, strrpos($file, "."));
		$uto = substr($file, strrpos($file, "."));
		return $pre."-".$width."x".$height.$uto;
	}

	/**
	* Get float microtime
	* @since 1.3
	* @param void
	* @return (float) unix timestamp with microtime
	**/
	function microtime_float(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	/**
	* Get the memory limit for the script
	* @since 1.3
	* @param void
	* @return (int) memory limit in bytes
	**/
	function get_memory_limit () {
		$memory_limit = @ini_get('memory_limit');
		if (preg_match('/^(\d+)(.)$/', strtolower($memory_limit), $matches)) {
			if ($matches[2] == 'm')        $memory_limit = $matches[1] * 1024 * 1024;
			else if ($matches[2] == 'k')   $memory_limit = $matches[1] * 1024;
		}
		return $memory_limit;
	}

	/**
	* Get execute time limit for script
	* @since 1.3
	* @param void
	* @return (int) execute time in seconds
	**/
	function get_exectime_limit () {
		$exec_limit = @ini_get('max_execution_time');
		return $exec_limit;
	}

}
?>