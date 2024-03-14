<?php
/*
Plugin Name: MaxGalleria
Plugin URI: http://maxgalleria.com
Description: The gallery platform for WordPress.
Version: 6.4.0
Author: Max Foundry
Author URI: http://maxfoundry.com

Copyright 2014-2022 Max Foundry, LLC (http://maxfoundry.com)
*/

class MaxGalleria {
	private $_addons;
	
	public $admin;
	public $common;
	public $meta;
	public $nextgen;
	public $settings;
	public $shortcode;
	public $shortcode_thumb;
	public $new_gallery;
	public $image_gallery;
	public $video_gallery;
	public $gallery_widget;
	public $gallery_thumb_widget;
  //public $license_valid;
	
	public function __construct() {
		$this->_addons = array();
		
		$this->set_global_constants();
		$this->set_activation_hooks();
		$this->initialize_properties();
		$this->add_thumb_sizes();
		$this->setup_hooks();
		$this->register_media_sources();
		$this->register_templates();
	}
	
	function activate() {
		update_option(MAXGALLERIA_VERSION_KEY, MAXGALLERIA_VERSION_NUM);
		
    $this->copy_template();
    
    $current_user_id = get_current_user_id();     
    $havemeta = get_user_meta( $current_user_id, MAXGALLERIA_REVIEW_NOTICE, true );
    if ($havemeta === '') {
      $review_date = date('Y-m-d', strtotime("+1 days"));        
      update_user_meta( $current_user_id, MAXGALLERIA_REVIEW_NOTICE, $review_date );      
    }
				
    //if ( 'impossible_default_value_12143' === get_option( 'mg_check_presets', 'impossible_default_value_12143' ) ) {
		$mg_hide_presets = get_option( 'mg_hide_presets', 'none' );
		if($mg_hide_presets !== 'on' && $mg_hide_presets !== 'off') {
			if($this->check_for_mg_posts())
				update_option( "mg_hide_presets", "off", true );
			else
				update_option( "mg_hide_presets", "on", true );
			//update_option( "mg_check_presets", "impossible_default_value_12143", true );
		}
				
	}
		   
  function copy_template() {
        
		// Copy gallery post type template file to theme directory
    $source = MAXGALLERIA_PLUGIN_DIR . '/single-maxgallery.php';
    $destination = $this->get_theme_dir() . '/single-maxgallery.php';
    if(!defined('PRESERVE_MAXGALLERIA_TEMPLATE')) {
      copy($source, $destination);
    }  
    else if(!file_exists($destination)) {
      copy($source, $destination);
    }
		flush_rewrite_rules();    
  }
	
	public function add_thumb_sizes() {
		// In addition to the thumbnail support when registering the custom post type, we need to add theme support
		// to properly handle the featured image for a gallery, just in case the theme itself doesn't have it.
		add_theme_support('post-thumbnails');
		
		// Additional sizes, cropped
		add_image_size(MAXGALLERIA_META_IMAGE_THUMB_SMALL, 100, 100, true);
		add_image_size(MAXGALLERIA_META_IMAGE_THUMB_MEDIUM, 150, 150, true);
		add_image_size(MAXGALLERIA_META_IMAGE_THUMB_LARGE, 200, 200, true);
		add_image_size(MAXGALLERIA_META_VIDEO_THUMB_SMALL, 150, 100, true);
		add_image_size(MAXGALLERIA_META_VIDEO_THUMB_MEDIUM, 200, 133, true);
		add_image_size(MAXGALLERIA_META_VIDEO_THUMB_LARGE, 250, 166, true);
	}

	public function admin_page_is_maxgallery_post_type($post_id = 0) {
		global $post;
		global $post_type;
		
		if (isset($post_id) && $post_id > 0 && get_post_type($post_id) == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($_GET['post']) && $_GET['post'] > 0 && get_post_type($_GET['post']) == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($_GET['post_type']) && $_GET['post_type'] == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($post_type) && $post_type == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		if (isset($post) && $post->post_type == MAXGALLERIA_POST_TYPE) {
			return true;
		}
		
		return false;
	}
	
	public function admin_page_is_media_edit() {
		if ($this->common->url_contains('wp-admin/media.php') && $this->common->url_contains('action=edit')) {
			return true;
		}
		
		return false;
	}
	
	public function admin_page_is_post_edit() {
		if ($this->common->url_contains('wp-admin/post.php') && $this->common->url_contains('action=edit')) {
			return true;
		}
		
		return false;
	}
	
	public function call_function_for_each_site($function) {
		global $wpdb;
		
		// Hold this so we can switch back to it
		$current_blog = $wpdb->blogid;
		
		// Get all the blogs/sites in the network and invoke the function for each one
		$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blog_ids as $blog_id) {
			switch_to_blog($blog_id);
			call_user_func($function);
		}
		
		// Now switch back to the root blog
		switch_to_blog($current_blog);
	}
	
	public function create_gallery_columns($column) {
		// The Title and Date columns are standard, so we don't have to explicitly provide output for them
		
		global $post;
		$maxgallery = new MaxGalleryOptions($post->ID);

		// Get all the attachments (the -1 gets all of them)
		$args = array('post_parent' => $post->ID, 'post_type' => 'attachment', 'orderby' => 'menu_order', 'order' => 'asc', 'numberposts' => -1);
		$attachments = get_posts($args);
		
		// Rounded borders
		$style = 'border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px;';
		
		switch ($column) {
			case 'type':
				if ($maxgallery->is_image_gallery()) {
					echo '<img src="' . MAXGALLERIA_PLUGIN_URL . '/images/image-32.png" alt="' . esc_html__('Image', 'maxgalleria') . '" title="' . esc_html__('Image', 'maxgalleria') . '" style="' . esc_attr($style) . '" />';
				}
				
				if ($maxgallery->is_video_gallery()) {
					echo '<img src="' . MAXGALLERIA_PLUGIN_URL . '/images/video-32.png" alt="' . esc_html__('Video', 'maxgalleria') . '" title="' . esc_html__('Video', 'maxgalleria') . '" style="' . esc_attr($style) . '" />';
				}
				
				break;
			case 'thumbnail':
				if (has_post_thumbnail($post->ID)) {
					echo get_the_post_thumbnail($post->ID, array(32, 32), array('style' => $style));
				}
				else {
					// Show the first thumb
					foreach ($attachments as $attachment) {
						$no_media_icon = 0;
						echo wp_get_attachment_image($attachment->ID, array(32, 32), $no_media_icon, array('style' => $style));
						break;
					}
				}
				break;
			case 'template':
				$template_key = $maxgallery->get_template();
				echo esc_html($this->get_template_name($template_key));
				break;
			case 'number':
				if ($maxgallery->is_image_gallery()) {
					if (count($attachments) == 0) { esc_html_e('0 images', 'maxgalleria'); }
					if (count($attachments) == 1) { esc_html_e('1 image', 'maxgalleria'); }
					if (count($attachments) > 1) { printf(esc_html__('%d images', 'maxgalleria'), count($attachments)); }
				}

				if ($maxgallery->is_video_gallery()) {
					if (count($attachments) == 0) { esc_html_e('0 videos', 'maxgalleria'); }
					if (count($attachments) == 1) { esc_html_e('1 video', 'maxgalleria'); }
					if (count($attachments) > 1) { printf(esc_html__('%d videos', 'maxgalleria'), count($attachments)); }
				}
				
				break;
			case 'shortcode':
				echo '[maxgallery id="' . esc_html($post->ID) . '"]';
				
				if ($post->post_status == 'publish') {
					echo '<br />';
					echo '[maxgallery name="' . esc_html($post->post_name) . '"]';
				}
				
				break;
		}
	}
	
	public function create_plugin_action_links($links, $file) {
		static $this_plugin;
		
		if (!$this_plugin) {
			$this_plugin = plugin_basename(__FILE__);
		}
		
		if ($file == $this_plugin) {
			$settings_link = '<a href="' . admin_url() . 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE . '&page=maxgalleria-settings">' . esc_html__('Settings', 'maxgalleria') . '</a>';
			array_unshift($links, $settings_link);
			
			$galleries_link = '<a href="' . admin_url() . 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE . '">' . esc_html__('Galleries', 'maxgalleria') . '</a>';
			array_unshift($links, $galleries_link);
		}

		return $links;
	}
	
	public function create_sortable_gallery_columns($vars) {
		if (isset($vars['orderby'])) {
			switch ($vars['orderby']) {
				case 'type':
					$vars = array_merge($vars, array('meta_key' => 'maxgallery_type', 'orderby' => 'meta_value'));
					break;
				case 'template':
					$vars = array_merge($vars, array('meta_key' => 'maxgallery_template', 'orderby' => 'meta_value'));
					break;
			}
		}
		
		return $vars;
	}
	
	function deactivate() {
		delete_option(MAXGALLERIA_VERSION_KEY);
		
    if(!defined('PRESERVE_MAXGALLERIA_TEMPLATE')) {
      // Delete the gallery post type template file from the theme directory
      $file = $this->get_theme_dir() . '/single-maxgallery.php';
      unlink($file);
    }
		
		flush_rewrite_rules();
	}
	
	public function define_gallery_columns($columns) {
		$columns = apply_filters(MAXGALLERIA_FILTER_GALLERY_COLUMNS, array(
			'cb' => '<input type="checkbox" />',
			'title' => esc_html__('Title', 'maxgalleria'),
			'thumbnail' => esc_html__('Thumbnail', 'maxgalleria'),
			'type' => esc_html__('Type', 'maxgalleria'),
			'template' => esc_html__('Template', 'maxgalleria'),
			'number' => esc_html__('Number of Media', 'maxgalleria'),
			'shortcode' => esc_html__('Shortcode', 'maxgalleria'),
			'date' => esc_html__('Date', 'maxgalleria')
		));
		
		return $columns;
	}
	
	public function define_sortable_gallery_columns($columns) {		
		// Title and Date are sortable by default

		$columns['type'] = 'type';
		$columns['template'] = 'template';
		$columns['number'] = 'number';
		
		return $columns;
	}
	
	public function do_activation($network_wide) {
		if ($network_wide) {
			$this->call_function_for_each_site(array($this, 'activate'));
		}
		else {
			$this->activate();
		}
	}
	
	public function do_deactivation($network_wide) {	
		if ($network_wide) {
			$this->call_function_for_each_site(array($this, 'deactivate'));
		}
		else {
			$this->deactivate();
		}
	}
	
	public function enqueue_admin_print_scripts() {
		if ($this->admin_page_is_maxgallery_post_type()) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-migrate', ABSPATH . WPINC . '/js/jquery/jquery-migrate.min.js', array('jquery'));            
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-upload');
			
			// For the media uploader
			wp_enqueue_media();      
			wp_enqueue_script('maxgalleria-media-script', MAXGALLERIA_PLUGIN_URL . '/js/media.js', array('jquery'));
      wp_enqueue_script('maxgalleria-media-script');
      wp_localize_script('maxgalleria-media-script', 'mg_media', 
        array('nonce' => wp_create_nonce(MG_META_NONCE )
      ));													
      

			// Other stuff
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
                  
			wp_enqueue_script('maxgalleria-topbox', MAXGALLERIA_PLUGIN_URL . '/libs/topbox/js/topbox.js', array('jquery'));			
		  wp_enqueue_script('maxgalleria-colorpicker-js', MAXGALLERIA_PLUGIN_URL . '/js/colpick/colpick.js', array('jquery'), null );			
                  
      wp_enqueue_style('fontawesome', MAXGALLERIA_PLUGIN_URL . '/libs/fontawesome-free-6.0.0-web/css/all.min.css');
												            
      $screen = get_current_screen();
			if($screen->id == 'edit-maxgallery') {              
				$show_gallery_ad = get_option('show_gallery_ad', "on");
				wp_enqueue_script('maxgalleria-promo', MAXGALLERIA_PLUGIN_URL . '/js/promo.js', array('jquery'));                                    
				wp_localize_script( 'maxgalleria-promo', 'mg_promo', 
							array( 'pluginurl' => MAXGALLERIA_PLUGIN_URL,
									   'show_promo' => $show_gallery_ad,
									   'nonce' => wp_create_nonce(MG_META_NONCE ),
									   'admin_url' => admin_url('admin-ajax.php'),
									   'addon_link' => MG_ADDON_PAGE_LINK,
									   'carousel_link' => MG_IMAGE_CAROUSEL_LINK,									
									   'albums_link' => MG_ALBUMS_LINK,
									   'video_showcase_link' => MG_VIDEO_SHOWCASE_LINK,
									   'slick_slider_link' => MG_SLICK_SLIDER_LINK,
									   'masonry_link' => MG_MASONRY_LINK,
									   'image_showcase_link' => MG_IMAGE_SHOWCASE_LINK,
									   'image_slider_link' => MG_IMAGE_SLIDER_LINK,									
									   'facebook_link' => MG_FACEBOOK_LINK,
									   'vimeo_link' => MG_VIMEO_LINK,
									   'instgram_link' => MG_INSTAGRAM_LINK,
									   'flickr_link' => MG_FLICKR_LINK,
									   'fwgrid_link' => MG_FWGRID_LINK,
									   'mg_md_link' => MG_MD_LINK,
										 'hero_link' => MG_HERO_LINK
									));													
      }
    } else {
      wp_enqueue_script('maxgalleria-promo', MAXGALLERIA_PLUGIN_URL . '/js/tm.js', array('jquery'));
      if(defined('MAXGALLERIA_ALBUMS_PLUGIN_URL')) {
        $albumsurls = MAXGALLERIA_ALBUMS_PLUGIN_URL;
      } else {
        $albumsurls = "";
      }  
      wp_localize_script( 'maxgalleria-promo', 'mg_promo', 
          array( 'pluginurl' => MAXGALLERIA_PLUGIN_URL,
                 'albumsurl' => $albumsurls
      ));						
      
		}
	}

	public function enqueue_admin_print_styles() {		
				
		$screen = get_current_screen();
		
		if ($this->admin_page_is_maxgallery_post_type()) {
			wp_enqueue_style('thickbox');
			wp_enqueue_style('maxgalleria-jquery-ui', MAXGALLERIA_PLUGIN_URL . '/libs/jquery-ui/jquery-ui.css');
			wp_enqueue_style('maxgalleria-topbox', MAXGALLERIA_PLUGIN_URL . '/libs/topbox/css/topbox.css');
			wp_enqueue_style('maxgalleria', MAXGALLERIA_PLUGIN_URL . '/maxgalleria.css');
      wp_enqueue_style('maxgalleria-colorpicker', MAXGALLERIA_PLUGIN_URL . '/js/colpick/css/colpick.css');
      wp_enqueue_style('maxgalleria-md-css', MAXGALLERIA_PLUGIN_URL . '/admin/material-plugin.css');
									      
		}
    wp_enqueue_style('mg-notice', MAXGALLERIA_PLUGIN_URL . '/admin/mg-notice.css');
    
    if($screen->id == 'maxgallery_page_mg-upgrade-to-pro') {    
      wp_enqueue_style('mg-upgrade', MAXGALLERIA_PLUGIN_URL . '/admin/mg-upgrade-to-pro.css');
      
      wp_enqueue_style('mg-foundation', MAXGALLERIA_PLUGIN_URL . '/libs/foundation/foundation-float.min.css');  
    }
        
	}
	  
	public function get_all_addons() {
		return $this->_addons;
	}

	public function get_media_source_addons() {
		$media_source_addons = array();
		
		foreach ($this->_addons as $addon) {
			if ($addon['type'] == 'media_source') {
				array_push($media_source_addons, $addon);
			}
		}
		
		return $media_source_addons;
	}
	
	public function get_template_addons() {
		$template_addons = array();
		
		foreach ($this->_addons as $addon) {
			if ($addon['type'] == 'template') {
				array_push($template_addons, $addon);
			}
		}
		
		return $template_addons;
	}
	
	public function get_template_name($template_key) {
		$template_name = '';
		$templates = $this->get_template_addons();
		
		foreach ($templates as $template) {
			if ($template['key'] == $template_key) {
				$template_name = $template['name'];
				break;
			}
		}
		
		return $template_name;
	}
	
	public function get_theme_dir() {
    if(is_child_theme())
		  return WP_CONTENT_DIR . '/themes/' . get_stylesheet();
    else
		  return WP_CONTENT_DIR . '/themes/' . get_template();
	}
	
	public function hide_add_new() {
		global $submenu;
		unset($submenu['edit.php?post_type=' . MAXGALLERIA_POST_TYPE][10]);
	}
	
	public function initialize_properties() {
		// The order doesn't really matter, except maxgallery-options.php must be included first so
		// that the MaxGalleryOptions class can be created in other parts of the system as needed
		
		require_once 'maxgallery-options.php';
		require_once 'maxgalleria-admin.php';
		require_once 'maxgalleria-common.php';
		require_once 'maxgalleria-meta.php';
		require_once 'maxgalleria-nextgen.php';
		require_once 'maxgalleria-settings.php';
		require_once 'maxgalleria-shortcode.php';
		require_once 'maxgalleria-shortcode-thumb.php';
		require_once 'maxgalleria-new-gallery.php';
		require_once 'maxgalleria-image-gallery.php';
		require_once 'maxgalleria-video-gallery.php';
		require_once 'widgets/gallery-widget.php';
		require_once 'widgets/gallery-thumb-widget.php';
    		
		$this->admin = new MaxGalleriaAdmin();
		$this->common = new MaxGalleriaCommon();
		$this->meta = new MaxGalleriaMeta();
		$this->nextgen = new MaxGalleriaNextGen();
		$this->settings = new MaxGalleriaSettings();
		$this->shortcode = new MaxGalleriaShortcode();
		$this->shortcode_thumb = new MaxGalleriaShortcodeThumb();
		$this->new_gallery = new MaxGalleriaNewGallery();
		$this->image_gallery = new MaxGalleriaImageGallery();
		$this->video_gallery = new MaxGalleriaVideoGallery();
		$this->gallery_widget = new MaxGalleriaGalleryWidget();
		$this->gallery_thumb_widget = new MaxGalleriaGalleryThumbWidget();
	}
	
	public function load_textdomain() {
		load_plugin_textdomain('maxgalleria', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}
	
	public function media_button() {
		global $pagenow, $wp_version;
		$output = '';

		// Only run in post/page creation and edit screens
		if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
			$title = esc_html__('MaxGalleria Gallery', 'maxgalleria');
			$icon = MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-16.png';
			$img = '<span class="wp-media-buttons-icon" style="background-image: url(' . $icon . '); width: 16px; height: 16px; margin-top: 1px;"></span>';
			$output = '<a href="#TB_inline?width=640&inlineId=select-maxgallery-container" class="thickbox button" title="' . $title . '" style="padding-left: .4em;">' . $img . ' ' . $title . '</a>';
		}

		echo wp_kses_post($output);
	}
  	
	public function media_button_admin_footer() {
		require_once 'maxgalleria-media-button.php';
	}
	
	public function register_gallery_post_type() {
		$slug = $this->settings->get_rewrite_slug();
		$exclude_from_search = $this->settings->get_exclude_galleries_from_search();
		$exclude_from_search = $exclude_from_search == 'on' ? true : false;
		
		$labels = apply_filters(MAXGALLERIA_FILTER_GALLERY_POST_TYPE_LABELS, array(
			'name' => esc_html__('MaxGalleria', 'maxgalleria'),
			'singular_name' => esc_html__('Gallery', 'maxgalleria'),
			'add_new' => esc_html__('Add New', 'maxgalleria'),
			'add_new_item' => esc_html__('Add New Gallery', 'maxgalleria'),
			'edit_item' => esc_html__('Edit Gallery', 'maxgalleria'),
			'new_item' => esc_html__('New Gallery', 'maxgalleria'),
			'view_item' => esc_html__('View Gallery', 'maxgalleria'),
			'search_items' => esc_html__('Search Galleries', 'maxgalleria'),
			'not_found' => esc_html__('No galleries found', 'maxgalleria'),
			'not_found_in_trash' => esc_html__('No galleries found in trash', 'maxgalleria'),
			'parent_item_colon' => ''
		));
		
		$args = apply_filters(MAXGALLERIA_FILTER_GALLERY_POST_TYPE_ARGS, array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'menu_icon' => MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-16.png',
			'rewrite' => array('slug' => $slug),
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'thumbnail'),
			'taxonomies' => array('category', 'post_tag'),
			'exclude_from_search' => $exclude_from_search
		));
		
		register_post_type(MAXGALLERIA_POST_TYPE, $args);
	}
	
	public function register_addon($addon) {
		array_push($this->_addons, $addon);
	}
	
	public function register_media_sources() {
		// YouTube
		require_once MAXGALLERIA_PLUGIN_DIR . '/addons/media-sources/youtube/youtube.php';
		$youtube = new MaxGalleriaYouTube();
		$youtube_addon = array(
			'key' => $youtube->addon_key,
			'name' => $youtube->addon_name,
			'type' => $youtube->addon_type,
			'subtype' => $youtube->addon_subtype,
			'settings' => $youtube->addon_settings
		);
		$this->register_addon($youtube_addon);
	}
 
	public function register_templates() {
		// Image Tiles template
		require_once MAXGALLERIA_PLUGIN_DIR . '/addons/templates/image-tiles/image-tiles.php';
		$image_tiles = new MaxGalleriaImageTiles();
		$image_tiles_addon = array(
			'key' => $image_tiles->addon_key,
			'name' => $image_tiles->addon_name,
			'type' => $image_tiles->addon_type,
			'subtype' => $image_tiles->addon_subtype,
			'settings' => $image_tiles->addon_settings,
			'image' => $image_tiles->addon_image,
			'output' => $image_tiles->addon_output
		);
		$this->register_addon($image_tiles_addon);
		
		// Video Tiles template
		require_once MAXGALLERIA_PLUGIN_DIR . '/addons/templates/video-tiles/video-tiles.php';
		$video_tiles = new MaxGalleriaVideoTiles();
		$video_tiles_addon = array(
			'key' => $video_tiles->addon_key,
			'name' => $video_tiles->addon_name,
			'type' => $video_tiles->addon_type,
			'subtype' => $video_tiles->addon_subtype,
			'settings' => $video_tiles->addon_settings,
			'image' => $video_tiles->addon_image,
			'output' => $video_tiles->addon_output
		);
		$this->register_addon($video_tiles_addon);
	}
  
	public function register_widgets() {
		register_widget('MaxGalleriaGalleryWidget');
		register_widget('MaxGalleriaGalleryThumbWidget');
	}
	
	public function set_activation_hooks() {
		register_activation_hook(__FILE__, array($this, 'do_activation'));
		register_deactivation_hook(__FILE__, array($this, 'do_deactivation'));
	}
	
	public function set_global_constants() {	
		define('MAXGALLERIA_VERSION_KEY', 'maxgalleria_version');
		define('MAXGALLERIA_VERSION_NUM', '6.4.0');
		define('MAXGALLERIA_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));
		define('MAXGALLERIA_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MAXGALLERIA_PLUGIN_NAME);
		define('MAXGALLERIA_PLUGIN_URL', rtrim(plugin_dir_url(__FILE__), '/'));
    define('MAXGALLERIA_POST_TYPE', 'maxgallery');
		define('MAXGALLERIA_SETTINGS', admin_url() . 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE . '&page=maxgalleria-settings');
		define('MAXGALLERIA_META_IMAGE_THUMB_SMALL', 'maxgallery-meta-image-thumb-small');
		define('MAXGALLERIA_META_IMAGE_THUMB_MEDIUM', 'maxgallery-meta-image-thumb-medium');
		define('MAXGALLERIA_META_IMAGE_THUMB_LARGE', 'maxgallery-meta-image-thumb-large');
		define('MAXGALLERIA_META_VIDEO_THUMB_SMALL', 'maxgallery-meta-video-thumb-small');
		define('MAXGALLERIA_META_VIDEO_THUMB_MEDIUM', 'maxgallery-meta-video-thumb-medium');
		define('MAXGALLERIA_META_VIDEO_THUMB_LARGE', 'maxgallery-meta-video-thumb-large');
		define('MAXGALLERIA_THUMB_SHAPE_LANDSCAPE', 'landscape');
		define('MAXGALLERIA_THUMB_SHAPE_PORTRAIT', 'portrait');
		define('MAXGALLERIA_THUMB_SHAPE_SQUARE', 'square');
		define('MAXGALLERIA_SETTING_REWRITE_SLUG', 'maxgalleria_setting_rewrite_slug');
		define('MAXGALLERIA_SETTING_EXCLUDE_GALLERIES_FROM_SEARCH', 'maxgalleria_setting_exlude_galleries_from_search');
		define('MAXGALLERIA_SETTING_DEFAULT_IMAGE_GALLERY_TEMPLATE', 'maxgalleria_setting_default_image_gallery_template');
		define('MAXGALLERIA_SETTING_DEFAULT_VIDEO_GALLERY_TEMPLATE', 'maxgalleria_setting_default_video_gallery_template');
    define('MAXGALLERIA_ADMIN_NOTICE', 'maxgalleria_admin_notice-2');
    define('MAXGALLERIA_REVIEW_NOTICE', 'maxgalleria_review_notice');
		define('MG_META_NONCE', 'maxgalleria_meta_nonce');
    define('MG_WP_CONTENT_FOLDER_NAME', basename(WP_CONTENT_DIR));
		
		define('MG_EDD_SHOP_URL', 'http://maxgalleria.com/');		
		define('MAXGALLERIA_SETTING_SHOW_ADDON_PAGE', 'maxgalleria_setting_default_show_addons_page');
		
		if(!defined('MAXGALLERIA_MEDIA_LIBRARY_SRC_FIX'))
      define("MAXGALLERIA_MEDIA_LIBRARY_SRC_FIX", "mgmlp_src_fix");
		
		define('MG_ADDON_PAGE_LINK',
			'https://maxgalleria.com/addons/?utm_source=MGGetAddon&utm_medium=tout&utm_campaign=tout');
		define('MG_IMAGE_CAROUSEL_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-image-carousel/?utm_source=MGGetAddon&amp;utm_medium=image-carousel&amp;utm_campaign=image-carousel');
		define('MG_ALBUMS_LINK', 
		  'https://maxgalleria.com/downloads/maxgalleria-albums/?utm_source=MGGetAddon&amp;utm_medium=albums&amp;utm_campaign=albums' );
		define('MG_VIDEO_SHOWCASE_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-video-showcase/?utm_source=MGGetAddon&utm_medium=videoshowcase&utm_campaign=videoshowcase');
		define('MG_SLICK_SLIDER_LINK', 
			'https://maxgalleria.com/downloads/slick-slider-for-wordpress/?utm_source=MGGetAddon&utm_medium=slick&utm_campaign=slick' );			
		define('MG_MASONRY_LINK', 
			'https://maxgalleria.com/downloads/masonry-pinterest-like-layout/?utm_source=MGGetAddon&utm_medium=masonry&utm_campaign=masonry');
		define('MG_IMAGE_SHOWCASE_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-image-showcase/?utm_source=MGGetAddon&utm_medium=imageshowcase&utm_campaign=imageshowcase');	
		define ('MG_IMAGE_SLIDER_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-image-slider/?utm_source=MGGetAddon&utm_medium=imageslider&utm_campaign=imageslider');
		define('MG_FACEBOOK_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-facebook/?utm_source=MGGetAddon&utm_medium=facebook&utm_campaign=facebook');
		define('MG_VIMEO_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-vimeo/?utm_source=MGGetAddon&utm_medium=vimeo&utm_campaign=vimeo');			
		define('MG_INSTAGRAM_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-instagram/?utm_source=MGGetAddon&utm_medium=instagram&utm_campaign=instagram');			
		define('MG_FLICKR_LINK',
			'https://maxgalleria.com/downloads/maxgalleria-flickr/?utm_source=MGGetAddon&utm_medium=flickr&utm_campaign=flickr');			
		define('MLPP_LINK', 'https://maxgalleria.com/downloads/media-library-plus-pro/?utm_source=MGGetAddon	');
		define('MG_FWGRID_LINK', 
			'https://maxgalleria.com/downloads/full-width-grid/?utm_source=MGGetAddon&utm_medium=fwgrid&utm_campaign=slick' );
		define('MG_MD_LINK', 
			'https://maxgalleria.com/downloads/material-deisgn/?utm_source=MGGetAddon&utm_medium=material-deisgn&utm_campaign=slick' );			
		define('MG_HERO_LINK', 
			'https://www.maxgalleria.com/hero-slider-wordpress/?utm_source=MGGetAddon&utm_medium=hero-slider&utm_campaign=hero' );			
				
		
		// Bring in all the actions and filters
		require_once 'maxgalleria-hooks.php';
	}
	
	public function set_icon_edit_image() {
		if ($this->admin_page_is_maxgallery_post_type()) {
			echo '<style>';
			echo '#icon-edit { background: url("'. MAXGALLERIA_PLUGIN_URL . '/images/maxgalleria-icon-32.png' . '") no-repeat transparent; }';
			echo '</style>';
		}
	}
	
	public function setup_hooks() {
		add_action('init', array($this, 'load_textdomain'));
		add_action('init', array($this, 'register_gallery_post_type'));
		add_action('init', array($this, 'display_mg_admin_notice'));
		add_filter('plugin_action_links', array($this, 'create_plugin_action_links'), 10, 2);
		add_action('admin_print_scripts', array($this, 'enqueue_admin_print_scripts'));
		add_action('admin_print_styles', array($this, 'enqueue_admin_print_styles'));
		add_action('admin_head', array($this, 'set_icon_edit_image'));
		add_action('admin_menu', array($this, 'hide_add_new'));
		add_filter('manage_edit-' . MAXGALLERIA_POST_TYPE . '_columns', array($this, 'define_gallery_columns'));
		add_filter('manage_edit-' . MAXGALLERIA_POST_TYPE . '_sortable_columns', array($this, 'define_sortable_gallery_columns'));
		add_action('manage_posts_custom_column', array($this, 'create_gallery_columns'));
		add_filter('request', array($this, 'create_sortable_gallery_columns'));
		add_filter('media_upload_tabs', array($this, 'set_media_upload_tabs'), 50, 1);
		add_filter('media_view_strings', array($this, 'set_media_view_strings'), 50, 1);
		add_filter('post_mime_types', array($this, 'set_post_mime_types'), 50, 1);
		add_filter('upload_mimes', array($this, 'set_upload_mimes'), 50, 1);
		add_action('media_buttons', array($this, 'media_button'));
		add_action('admin_footer', array($this, 'media_button_admin_footer'));
		add_action('widgets_init', array($this, 'register_widgets'));
		add_action('after_switch_theme', array($this, 'copy_template'));
    
    if(!defined('ATTACHMENT_QUERY_OFF') && !class_exists('eml') && !function_exists( 'wpuxss_get_eml_slug' ) )     
      add_action( 'pre_get_posts', array($this, 'modify_attachments'));
    
    //add_action( 'pre_get_posts', array($this, 'limit_contributor_access'));  
    
    add_action( 'admin_menu', array($this, 'limit_contributor_access'));    
				
		//add_action( 'admin_bar_menu', array($this, 'current_maxgalleria_gallery'), 999 );

		
//    this is not working yet:    
//    check daily for the template in the theme folder; copy and update permalinks if missing.        
//    if ( ! wp_next_scheduled( 'mg_task_hook' ) ) {
//      wp_schedule_event( time(), 'daily', 'mg_task_hook' );
//    }
//
//    add_action( 'mg_task_hook', array($this, 'mg_daily_check') );
		
    add_action('admin_head', array($this, 'admin_head_hook'), 1);             
    
    //add_filter('mce_buttons', array($this, 'tinymce_button'));
    //add_filter('mce_external_plugins', array($this, 'add_tinymce_button'));
        
    add_action('enqueue_block_editor_assets', array($this, 'load_mg_block'));
    
    add_filter('query_vars', array($this, 'mg_query_vars')); 
    
    add_action('wp_ajax_nopriv_mg_get_image_info', array($this, 'mg_get_image_info'));
    add_action('wp_ajax_mg_get_image_info', array($this, 'mg_get_image_info'));		
        
    add_action('wp_ajax_nopriv_mg_save_image_info', array($this, 'mg_save_image_info'));
    add_action('wp_ajax_mg_save_image_info', array($this, 'mg_save_image_info'));		
        
    add_action('wp_ajax_nopriv_mg_display_bulk_edit', array($this, 'mg_display_bulk_edit'));
    add_action('wp_ajax_mg_display_bulk_edit', array($this, 'mg_display_bulk_edit'));		
        
    add_action('wp_ajax_nopriv_mg_save_bulk_info', array($this, 'mg_save_bulk_info'));
    add_action('wp_ajax_mg_save_bulk_info', array($this, 'mg_save_bulk_info'));	
        
    add_action('wp_ajax_nopriv_mg_get_video_info', array($this, 'mg_get_video_info'));
    add_action('wp_ajax_mg_get_video_info', array($this, 'mg_get_video_info'));	
        
    add_action('wp_ajax_nopriv_mg_save_video_info', array($this, 'mg_save_video_info'));
    add_action('wp_ajax_mg_save_video_info', array($this, 'mg_save_video_info'));	
        
    add_action('wp_ajax_nopriv_mg_display_bulk_video', array($this, 'mg_display_bulk_video'));
    add_action('wp_ajax_mg_display_bulk_video', array($this, 'mg_display_bulk_video'));		
    
    add_action('wp_ajax_nopriv_mg_save_bulk_video', array($this, 'mg_save_bulk_video'));
    add_action('wp_ajax_mg_save_bulk_video', array($this, 'mg_save_bulk_video'));	
        
    add_action('wp_ajax_nopriv_mg_add_videos', array($this, 'mg_add_videos'));
    add_action('wp_ajax_mg_add_videos', array($this, 'mg_add_videos'));	
    
    // added 'display' to the list of accepted styles used in wp_kses_post()
    add_filter( 'safe_style_css', function( $styles ) {
      $styles[] = 'display';
      return $styles;
    } );
    
	}
  
  public function load_mg_block() {
    
    global $wpdb;
    
    wp_register_script(
      'mg-block', MAXGALLERIA_PLUGIN_URL . '/block/index.js',
      array('wp-blocks','wp-editor','wp-i18n'),
      true
    );
    
    $sql = "SELECT ID, post_title from {$wpdb->prefix}posts where post_type = 'maxgallery' and post_status = 'publish'  order by post_title";

    $gallery_list = array();

    $rows = $wpdb->get_results($sql);
    
    if($rows) {
      foreach($rows as $row) {
        $gallery_list[] = array(
          'id' => strval($row->ID),
          'name' => $row->post_title
        );
      }
    }
    
    // keep as array, not casting as an object    
    wp_localize_script( 'mg-block', 'mg_block_info', 
      array( 'galleries' => $gallery_list)); 
    
    wp_enqueue_script('mg-block');
    
  }
      
  function limit_contributor_access() {
    if( !current_user_can( 'publish_posts' ) ):
      remove_menu_page( 'edit.php?post_type=maxgallery' );
    endif;
  }
  
  public function modify_attachments( $query ) {
    
    if ( is_admin() && strpos( $_SERVER[ 'REQUEST_URI' ], 'admin-ajax.php' ) !== false && $_REQUEST['action'] === 'query-attachments'  ) {      
      add_filter( 'posts_groupby', array($this, 'group_attachments') );
    }
    return $query;
  }
    
  public function group_attachments($groupby) {  
    if ($groupby != '') {
      $groupby .= " , guid";
    } else {
      $groupby .= " guid";
    }
    return $groupby;    
  }
      
	public function set_media_upload_tabs($tabs) {
		// Remove the "From URL", "Gallery", and "NextGEN" tabs from the media library popup.
		// Only the tabs "From Computer" and "Media Library" should be shown.

		if ($this->admin_page_is_maxgallery_post_type()) {
			unset($tabs['type_url']);	// From URL tab
			unset($tabs['gallery']);	// Gallery tab
			unset($tabs['nextgen']);	// NextGEN tab
		}
		
		return $tabs;
	}
	
	public function set_media_view_strings($strings) {
		if ($this->admin_page_is_maxgallery_post_type()) {
			// Remove these
			unset($strings['insertFromUrlTitle']);
			unset($strings['setFeaturedImageTitle']);
			unset($strings['createGalleryTitle']);
			unset($strings['createPlaylistTitle']);
			unset($strings['createVideoPlaylistTitle']);
			
			// Change these for better context in MaxGalleria galleries
			$strings['insertMediaTitle'] = esc_html__('Add Images', 'maxgalleria');
			$strings['insertIntoPost'] = esc_html__('Add to Gallery', 'maxgalleria');
			$strings['uploadedToThisPost'] = esc_html__('Added to this gallery', 'maxgalleria');
		}
		
		return $strings;
	}
	
	public function set_post_mime_types($mime_types) {
		// Remove the video and audio types
		
		if ($this->admin_page_is_maxgallery_post_type()) {
			unset($mime_types['video']);
			unset($mime_types['audio']);
		}
		
		return $mime_types;
	}
	
	public function set_upload_mimes($mime_types) {
		// Only allow image file type uploads. The complete list allowed by WordPress is
		// located in the get_allowed_mime_types() function in wp-includes/functions.php.
		
		if ($this->admin_page_is_maxgallery_post_type()) {
			$mime_types = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
				'bmp' => 'image/bmp',
				'webp' => 'image/webp',
				'tif/tiff' => 'image/tiff'
			);
		}
		
		return $mime_types;
	}
	
	public function thickbox_l10n_fix() {
		// When combining scripts, localization is lost for thickbox.js, so we call this
		// function to fix it. See http://wordpress.org/support/topic/plugin-affecting-photo-galleriessliders
		// for more details.
		echo '<script type="text/javascript">';
		echo "var thickboxL10n = " . json_encode(array(
			'next' => esc_html__('Next >'),
			'prev' => esc_html__('< Prev'),
			'image' => esc_html__('Image'),
			'of' => esc_html__('of'),
			'close' => esc_html__('Close'),
			'noiframes' => esc_html__('This feature requires inline frames. You have iframes disabled or your browser does not support them.'),
			'loadingAnimation' => includes_url('js/thickbox/loadingAnimation.gif'),
			'closeImage' => includes_url('js/thickbox/tb-close.png')));
		echo '</script>';
	}
  
  public function display_mg_admin_notice () {
        
    $current_user_id = get_current_user_id(); 

    $notice = get_user_meta( $current_user_id, MAXGALLERIA_ADMIN_NOTICE, true );
    $review = get_user_meta( $current_user_id, MAXGALLERIA_REVIEW_NOTICE, true );
        
    //if( $notice !== 'off' )
    //  add_action( 'admin_notices', array($this, 'mg_admin_notice' ));      
    
    if( $review !== 'off') {
      if($review === false)
        add_action( 'admin_notices', array($this, 'mg_review_notice' ));            
      else {
        $now = date("Y-m-d"); 
        $review_time = strtotime($review);
        $now_time = strtotime($now);
        if($now_time > $review_time)
          add_action( 'admin_notices', array($this, 'mg_review_notice' ));
      }
    }  
  }
  
//  public function mg_admin_notice() {
//   if( current_user_can( 'manage_options' ) ) {  ? >
//      <div class="updated notice">         
//      </div>
//    < ?php     
//    }    
//  }

    public function mg_review_notice() {
   if( current_user_can( 'manage_options' ) ) {  ?>
      <div class="updated notice maxgalleria-notice">         
          <div id='maxgallery_logo'></div>
          <div id='maxgalleria-notice-3'><p id='mg-notice-title'><?php esc_html_e( 'Rate us Please!', 'maxgalleria' ); ?></p>
          <p><?php esc_html_e( 'Your rating is the simplest way to support MaxGalleria. We really appreciate it!', 'maxgalleria' ); ?></p>
        
          <ul id="review-notice-links">
            <li> <span class="dashicons dashicons-smiley"></span><a href="<?php echo admin_url(); ?>edit.php?post_type=maxgallery&page=mg-review-notice"><?php esc_html_e( "I've already left a review", "maxgalleria" ); ?></a></li>
            <li><span class="dashicons dashicons-calendar-alt"></span><a href="<?php echo admin_url(); ?>edit.php?post_type=maxgallery&page=mg-review-later"><?php esc_html_e( "Maybe Later", "maxgalleria" ); ?></a></li>
            <li><span class="dashicons dashicons-external"></span><a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/maxgalleria?filter=5"><?php esc_html_e( "Sure! I'd love to!", "maxgalleria" ); ?></a></li>
          </ul>
          </div>
          <a class="dashicons dashicons-dismiss close-mg-notice" href="<?php echo admin_url(); ?>edit.php?post_type=maxgallery&page=mg-review-notice"></a>
          
      </div>
    <?php     
    }
  }
  
  private function mg_daily_check() {

    $source = MAXGALLERIA_PLUGIN_DIR . '/single-maxgallery.php';
    $destination = $this->get_theme_dir() . '/single-maxgallery.php';
    
    if(!file_exists($destination)) {
      copy($source, $destination);
		  flush_rewrite_rules();
    }
		
  }
	
	public function mg_get_attachment_url($attachment, $uploads) {
		$url = "";
		if ( $file = get_post_meta( $attachment->ID, '_wp_attached_file', true) ) {					
			if ( 0 === strpos( $file, $uploads['basedir'] ) ) {
				// Replace file location with url location.
				$url = str_replace($uploads['basedir'], $uploads['baseurl'], $file);
			} elseif ( false !== strpos($file, MG_WP_CONTENT_FOLDER_NAME . '/uploads') ) {
				$url = $uploads['baseurl'] . substr( $file, strpos($file, MG_WP_CONTENT_FOLDER_NAME . '/uploads') + 18 );
			} else {
				// It's a newly-uploaded file, therefore $file is relative to the basedir.
				$url = $uploads['baseurl'] . "/$file";				  	
			}	
		} else {
			$url = $attachment->guid;
		}
		
	  // On SSL front-end, URLs should be HTTPS.
		if ( is_ssl() && ! is_admin() && 'wp-login.php' !== $GLOBALS['pagenow'] ) {
			$url = set_url_scheme( $url );
		}
								
		$url = apply_filters( 'wp_get_attachment_url', $url, $attachment->ID );
								
		return $url;
	}
	
	// check if MG is already running on the site
	private function check_for_mg_posts() {
		global $wpdb;
		
		$sql = "SELECT ID from {$wpdb->prefix}posts where post_type = 'maxgallery' limit 0, 1";
		
		if($wpdb->get_row($sql)) 
			return true;
		else
			return false;
		
	}
		
	public function current_maxgalleria_gallery( $wp_admin_bar ) {
		
		global $wpdb;
		
		if(current_user_can( 'manage_options')) {
		
			$sql = "select option_value from {$wpdb->prefix}options where option_name = 'mg_current_gallery'";

			$gallery_id = $wpdb->get_var($sql);

			if($gallery_id != null) {

				$args = array(
					'id'    => 'mg_current_gallery',
					'title' => 'Current Gallery',
					'href' => admin_url() . "post.php?post={$gallery_id}&action=edit", 	
				);

				$wp_admin_bar->add_node( $args );
			}	
		}
	}
	
	public function admin_head_hook() {
		global $wp_query;
		if(isset($wp_query->post->ID)) {
			if(MAXGALLERIA_POST_TYPE == get_post_type($wp_query->post->ID)) {
				add_filter( 'admin_body_class', array($this, 'mg_body_class'));
			}	
		}
	}
	
	public function mg_body_class($classes) {
		$mg_class = "maxgalery-pt";
		if(is_array($classes))
			$classes[] = $mg_class;
		else
			$classes .= " " . $mg_class;
		return $classes;	
	}
    
	public function tinymce_button($buttons){
    array_push($buttons, "maxgalleria_tinymce");
    return $buttons;
	}

	public function add_tinymce_button($plugin_array){
		$js_url = trailingslashit(MAXGALLERIA_PLUGIN_URL . '/js/');
		$plugin_array['maxgalleria_tinymce'] = $js_url . 'maxgalleria_tinymce.js' ;
		return $plugin_array;        
	}
  
  // function by stever, https://www.php.net/strip%20tags
  public function stripUnwantedTagsAndAttrs($html_str){
    $content = mb_convert_encoding($html_str, 'HTML-ENTITIES', 'UTF-8');
    $xml = new DOMDocument('utf8');
    //$xml->encoding = 'utf8';
    //Suppress warnings: proper error handling is beyond scope of example
    libxml_use_internal_errors(true);
    //List the tags you want to allow here, NOTE you MUST allow html and body otherwise entire string will be cleared
    $allowed_tags = array("b", "br", "em", "hr", "i", "li", "ol", "p", "s", "span", "table", "tr", "td", "u", "ul", "strong");
    //List the attributes you want to allow here
    $allowed_attrs = array ("class", "id", "style");
    if (!strlen($content)){return false;}
    if ($xml->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)){
      foreach ($xml->getElementsByTagName("*") as $tag){
        if (!in_array($tag->tagName, $allowed_tags)){
          $tag->parentNode->removeChild($tag);
        }else{
          foreach ($tag->attributes as $attr){
            if (!in_array($attr->nodeName, $allowed_attrs)){
              $tag->removeAttribute($attr->nodeName);
            }
          }
        }
      }
    }
    return $xml->saveHTML();
  }
  
  // removes unneeded <p></p> tags from HTML string
  // used to allow HTML in image captions
  public function remove_ptags($html_string) {
    $begin_pg_tag = strpos($html_string, '<p>');
    if($begin_pg_tag > -1) {
      $html_string = substr($html_string, $begin_pg_tag+3);
      $end_pg_tag = strrpos($html_string, '</p>');
      $updated_string = substr($html_string, 0 , $end_pg_tag);
    } else {
      $updated_string = $html_string;
    }
    return $updated_string;
  }
  
  // determines is the license has expired
  public function is_valid_license($license_expires_option, $license_status_option) {
    
    $expriation_date = get_option($license_expires_option);
    $license_status = get_option($license_status_option, 'inactive');
    
    if($license_status == 'inactive');
      return $license_status;
    
    $expire_time = strtotime($expriation_date);

    $currnet_date_time = date('Y-m-d H:i:s');
    $today_time = strtotime($currnet_date_time);
    if($expire_time < $today_time || $license_status != 'valid') {
      $valid = 'expired';
    } else {
      $valid = 'valid';
    }
    return $valid;
  }
  
  public function mg_query_vars( $vars ) {
    $vars[] = 'mg_page';
    return $vars;
  }
  
  public function mg_get_image_info() {
        
    if (isset($_POST) && check_admin_referer($this->image_gallery->nonce_image_edit['action'], $this->image_gallery->nonce_image_edit['name'])) {
      
      if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
        $image_id = trim(sanitize_text_field($_POST['image_id']));
      else
        $image_id = 0;
      
      if($image_id) {
        $image = get_post($image_id);
        
        $post_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        
        $image_link = get_post_meta($image->ID, 'maxgallery_attachment_image_link', true);
        
        $action_link_text = get_post_meta($image->ID, 'maxgallery_attachment_action_link_text', true);
        
        $image_html = wp_get_attachment_image($image->ID, MAXGALLERIA_META_IMAGE_THUMB_LARGE);
                
        $data = array ('post_title' => esc_html($image->post_title), 'post_alt' => esc_attr($post_alt), 'caption' => wp_kses_post($image->post_excerpt), 'image_link' => esc_url($image_link), 'action_link_text' => wp_kses_post($action_link_text), 'image_html' => $image_html);
        echo json_encode($data);
                
      }
      
    }
    die();
    
  }
  
  public function mg_save_image_info() {
        
    if (isset($_POST) && check_admin_referer($this->image_gallery->nonce_image_edit['action'], $this->image_gallery->nonce_image_edit['name'])) {
      
      if ((isset($_POST['image_id'])) && (strlen(trim($_POST['image_id'])) > 0))
        $image_id = trim(sanitize_text_field($_POST['image_id']));
      else
        $image_id = 0;
      
      if ((isset($_POST['post_title'])) && (strlen(trim($_POST['post_title'])) > 0))
        $post_title = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($_POST['post_title']));
      else
        $post_title = '';
      
      if ((isset($_POST['caption'])) && (strlen(trim($_POST['caption'])) > 0))
        $post_excerpt = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($_POST['caption']));
      else
        $post_excerpt = '';
      
      if ((isset($_POST['post_alt'])) && (strlen(trim($_POST['post_alt'])) > 0))
        $post_alt = stripslashes(sanitize_text_field($_POST['post_alt']));
      else
        $post_alt = '';
      
      if ((isset($_POST['image_link'])) && (strlen(trim($_POST['image_link'])) > 0)) {
        $image_link = sanitize_url($_POST['image_link']);
        if ($image_link != '' && !$this->common->string_starts_with($image_link, 'http://') && !$this->common->string_starts_with($image_link, 'https://') ) {
          $image_link = 'http://' . $image_link;
        }        
      } else
        $image_link = '';
      
      if ((isset($_POST['action_link_text'])) && (strlen(trim($_POST['action_link_text'])) > 0))
        $action_link_text = stripslashes(sanitize_text_field($_POST['action_link_text']));
      else
        $action_link_text = '';
      
      if ((isset($_POST['template'])) && (strlen(trim($_POST['template'])) > 0))
        $template = stripslashes(sanitize_text_field($_POST['template']));
      else
        $template = '';
      
      
      if($image_id != 0) {
        
        $temp = array();
        $temp['ID'] = $image_id;
        $temp['post_title'] = $this->remove_ptags($post_title);
        $temp['post_excerpt'] = $this->remove_ptags($post_excerpt);
        wp_update_post($temp);
        
		    update_post_meta($image_id, '_wp_attachment_image_alt', $post_alt);
        
        update_post_meta($image_id, 'maxgallery_attachment_image_link', stripslashes($image_link));

        if($template === 'material-design') {
          update_post_meta($image_id, 'maxgallery_attachment_action_link_text', $action_link_text);
        }
        
      }
          
    }
    
    die();
  }
  
  public function mg_display_bulk_edit() {
        
    $html = '';
    
    if (isset($_POST) && check_admin_referer($this->image_gallery->nonce_image_edit['action'], $this->image_gallery->nonce_image_edit['name'])) {
      
      if ((isset($_POST['image_ids'])) && (strlen(trim($_POST['image_ids'])) > 0))
        $image_ids = trim(sanitize_text_field($_POST['image_ids']));
      else
        $image_ids = '';
      
      if (isset($image_ids)) {
        $image_ids_array = explode(',', $image_ids);

        $html .= '<table id="bulk-edit-table" cellpadding="0" cellspacing="0">' . PHP_EOL;
          foreach ($image_ids_array as $image_id) {
            if (isset($image_id)) {
              $image = get_post($image_id);
                if (isset($image)) {
        $html .= '  <tr>' . PHP_EOL;
        $html .= '	  <td class="thumb">' . PHP_EOL;
        $html .=       wp_get_attachment_image($image->ID, MAXGALLERIA_META_IMAGE_THUMB_SMALL) . PHP_EOL;
        $html .= '		  <input type="hidden" name="image-edit-id[]" value="' . esc_attr($image->ID) . '" />' . PHP_EOL;
        $html .= '		</td>' . PHP_EOL;
        $html .= '		<td>' . PHP_EOL;
        $html .= '			<div class="fields">' . PHP_EOL;
        $html .= '				<div class="field">' . PHP_EOL;
        $html .= '					<div class="field-label">' . esc_html__('Title', 'maxgalleria') . '</div>' . PHP_EOL;
        $html .= '				  <div class="field-value">' . PHP_EOL;
        $html .= '					  <input type="text" name="image-edit-title[]" value="' . esc_html($image->post_title) .'" />' . PHP_EOL;
        $html .= '					</div>' . PHP_EOL;
        $html .= '			 </div>' . PHP_EOL;
        $html .= '			 <div class="clear"></div>' . PHP_EOL;

        $html .= '			 <div class="field">' . PHP_EOL;
        $html .= '			   <div class="field-label">' . esc_html__('Alt Text', 'maxgalleria') . '</div>' . PHP_EOL;
        $html .= '				 <div class="field-value">' . PHP_EOL;
        $html .= '				   <input type="text" name="image-edit-alt[]" value="' . esc_html(get_post_meta($image->ID, '_wp_attachment_image_alt', true)) . '" />' . PHP_EOL;
        $html .= '				 </div>' . PHP_EOL;
        $html .= '			 </div>' . PHP_EOL;
        $html .= '			 <div class="clear"></div>' . PHP_EOL;

        $html .= '			 <div class="field">'  . PHP_EOL;
        $html .= '			   <div class="field-label">'.  esc_html__('Caption', 'maxgalleria') . '</div>' . PHP_EOL;
        $html .= '				 <div class="field-value">' . PHP_EOL;
        $html .= '				   <input type="text" name="image-edit-caption[]" value="' . esc_html($image->post_excerpt) . '" />' . PHP_EOL;
        $html .= '				 </div>' . PHP_EOL;
        $html .= '			</div>' . PHP_EOL;
        $html .= '			<div class="clear"></div>' . PHP_EOL;

        $html .= '			<div class="field">' . PHP_EOL;
        $html .= '				<div class="field-label">' . esc_html__('Link', 'maxgalleria') . '</div>' . PHP_EOL;
        $html .= '			  <div class="field-value last">' . PHP_EOL;
        $html .= '				  <input type="text" name="image-edit-link[]" value="' . esc_url(get_post_meta($image->ID, 'maxgallery_attachment_image_link', true)) . '" />' . PHP_EOL;
        $html .= '				</div>' . PHP_EOL;
        $html .= '			</div>' . PHP_EOL;
        $html .= '			<div class="clear"></div>' . PHP_EOL;
        $html .= '	  </div>' . PHP_EOL;
        $html .= '	</td>' . PHP_EOL;
        $html .= '</tr>' . PHP_EOL;
                } 
              }
            }
        $html .= '<tr>' . PHP_EOL;
        $html .= '  <td>' . PHP_EOL;
        $html .= '    <a class="btn btn-primary" id="bulk-save-button">' . esc_html__('Save Changes', 'maxgalleria') . '</a>' . PHP_EOL;
        $html .= '    <input type="button" class="btn" id="bulk-cancel-button" value="' . esc_html__('Cancel', 'maxgalleria') . '" />' . PHP_EOL;
        $html .= '  </td>' . PHP_EOL;
        $html .= '  <td></td>' . PHP_EOL;
        $html .= '</tr>' . PHP_EOL; 
            
        $html .= '</table>' . PHP_EOL;
                
      }

      $html .= wp_nonce_field($this->image_gallery->nonce_image_edit_bulk['action'], $this->image_gallery->nonce_image_edit_bulk['name']);
      
      $allowed_html = array(
        'a' => array(
          'href' => array(),
          'title' => array(),
          'class' => array(),
          'id' => array()
        ),
        'img' => array(
          'src' => array(),
          'alt' => array(),
          'class' => array(),
          'loading' => array(),
          'srcset' => array(),
          'srcset' => array(),
          'sizes' => array(),
          'width' => array(),
          'height' => array()
        ),
        'input' => array(
          'id' => array(),
          'class' => array(),
          'value' => array(),
          'name' => array(),
          'type' => array()
        ),
        'div' => array(
          'class' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'table' => array(
          'id' => array(),
          'cellpadding' => array(),
          'cellspacing' => array()
        ),
        'tr' => array(),
        'td' => array(
          'class' => array()
        )
      );    
                
      echo wp_kses($html, $allowed_html);
      
    }
    
    die();
    
  }
  
  public function mg_save_bulk_info() {
    
    global $wpdb;
    $form_data = '';
        
    if (isset($_POST) && check_admin_referer($this->image_gallery->nonce_image_edit_bulk['action'], $this->image_gallery->nonce_image_edit_bulk['name'])) {
            
      if ((isset($_POST['template'])) && (strlen(trim($_POST['template'])) > 0))
        $template = trim(sanitize_text_field($_POST['template']));
      else
        $template = '';
              
      if ((isset($_POST['form_data'])) && (strlen(trim($_POST['form_data'])) > 0)) {
        parse_str($_POST['form_data'], $form_data);
        
        $i = 0; 
        if(isset($form_data['image-edit-id'])) {
          foreach($form_data['image-edit-id'] as $image_edit_id) {
            
            $temp = array();
            $temp['ID'] = sanitize_text_field($image_edit_id);
            $post_title = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($form_data['image-edit-title'][$i]));
            $post_excerpt = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($form_data['image-edit-caption'][$i]));
            $temp['post_title'] = $this->remove_ptags($post_title);
            $temp['post_excerpt'] = $this->remove_ptags($post_excerpt);

            wp_update_post($temp);

            // Determine if we need to prepend http:// to the link
            $link = esc_url_raw($form_data['image-edit-link'][$i]);
            if (isset($link) && $link != '') {
              if (!$this->common->string_starts_with($link, 'http://') && !$this->common->string_starts_with($link, 'https://')) {
                $link = 'http://' . $link;
              }
            }

            // Now update the meta
            update_post_meta($image_edit_id, '_wp_attachment_image_alt', stripslashes(sanitize_text_field($form_data['image-edit-alt'][$i])));
            update_post_meta($image_edit_id, 'maxgallery_attachment_image_link', stripslashes($link));

            if($template == 'material-design' && isset($form_data['image-edit-action-text'])) {
              $action_link_text = sanitize_text_field($form_data['image-edit-action-text'][$i]);		
              update_post_meta($image->ID, 'maxgallery_attachment_action_link_text', stripslashes($action_link_text));
            }
                        
            $i++;
          }
          
        }
      }
    }
    die();
  }
  
  public function mg_get_video_info() {
    
    if (isset($_POST) && check_admin_referer($this->video_gallery->nonce_video_edit['action'], $this->video_gallery->nonce_video_edit['name'])) {
                  
      if ((isset($_POST['video_id'])) && (strlen(trim($_POST['video_id'])) > 0))
        $video_id = trim(sanitize_text_field($_POST['video_id']));
      else
        $video_id = 0;
            
      if($video_id  != 0) {
        
        $video = get_post($video_id);
        
        $post_alt = get_post_meta($video_id, '_wp_attachment_image_alt', true);
        
        $image_html = wp_get_attachment_image($video->ID, MAXGALLERIA_META_IMAGE_THUMB_LARGE);
                
        $data = array ('post_title' => esc_html($video->post_title), 'post_alt' => esc_attr($post_alt), 'caption' => wp_kses_post($video->post_excerpt), 'image_html' => $image_html);
        echo json_encode($data);
                
      }
            
    }
    
    die();
  }
  
    public function mg_save_video_info() {
      
    
    if (isset($_POST) && check_admin_referer($this->video_gallery->nonce_video_edit['action'], $this->video_gallery->nonce_video_edit['name'])) {
      
            
      if ((isset($_POST['video_id'])) && (strlen(trim($_POST['video_id'])) > 0))
        $video_id = trim(sanitize_text_field($_POST['video_id']));
      else
        $video_id = 0;
      
      if ((isset($_POST['post_title'])) && (strlen(trim($_POST['post_title'])) > 0))
        $post_title = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($_POST['post_title']));
      else
        $post_title = '';
      
      if ((isset($_POST['caption'])) && (strlen(trim($_POST['caption'])) > 0))
        $post_excerpt = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($_POST['caption']));
      else
        $post_excerpt = '';
      
      if ((isset($_POST['post_alt'])) && (strlen(trim($_POST['post_alt'])) > 0))
        $post_alt = stripslashes(sanitize_text_field($_POST['post_alt']));
      else
        $post_alt = '';
            
      if($video_id != 0) {
        
        $video = get_post($video_id);
      
        // First update the post itself
        $temp = array();
        $temp['ID'] = $video->ID;

        $temp['post_title'] = $this->remove_ptags($post_title);
        $temp['post_excerpt'] = $this->remove_ptags($post_excerpt);

        wp_update_post($temp);

        update_post_meta($video->ID, '_wp_attachment_image_alt', $post_alt);

        update_post_meta($video->ID, 'maxgallery_attachment_video_enable_related_videos', 1);

        update_post_meta($video->ID, 'maxgallery_attachment_video_enable_hd_playback', 1);
      
      }  
      
    }
    
    die();
    
  }
  
  public function mg_display_bulk_video() {
    
    $html = '';
    
    if (isset($_POST) && check_admin_referer($this->video_gallery->nonce_video_edit['action'], $this->video_gallery->nonce_video_edit['name'])) {
      
      if ((isset($_POST['video_ids'])) && (strlen(trim($_POST['video_ids'])) > 0))
        $video_ids = trim(sanitize_text_field($_POST['video_ids']));
      else
        $video_ids = '';
      
      if (isset($video_ids)) {
        $video_ids_array = explode(',', $video_ids);
                  
        $html .= '<table id="bulk-edit-table" cellpadding="0" cellspacing="0">' . PHP_EOL;
				foreach ($video_ids_array as $video_id) {
					if (isset($video_id)) {
						$video = get_post($video_id);
							if (isset($video)) { 
        
				$html .= '				<tr>' . PHP_EOL;
				$html .= '					<td class="thumb">' . PHP_EOL;
				$html .= wp_get_attachment_image($video->ID, MAXGALLERIA_META_VIDEO_THUMB_SMALL);
        $html .= '		  <input type="hidden" name="video-edit-id[]" value="' . esc_attr($video->ID) . '" />' . PHP_EOL;        
				$html .= '					</td>' . PHP_EOL;
				$html .= '					<td>' . PHP_EOL;
				$html .= '						<div class="fields">' . PHP_EOL;
				$html .= '							<div class="field">' . PHP_EOL;
				$html .= '								<div class="field-label">' . esc_html__('Title', 'maxgalleria') . '</div>' . PHP_EOL;
				$html .= '								<div class="field-value">' . PHP_EOL;
				$html .= '									<input type="text" name="video-edit-title[]" value="' . esc_html($video->post_title) . '" />' . PHP_EOL;
				$html .= '								</div>' . PHP_EOL;
				$html .= '							</div>' . PHP_EOL;
				$html .= '							<div class="clear"></div>' . PHP_EOL;
											
				$html .= '							<div class="field">' . PHP_EOL;
				$html .= '								<div class="field-label">' . esc_html__('Alt Text', 'maxgalleria') . '</div>' . PHP_EOL;
				$html .= '								<div class="field-value">' . PHP_EOL;
				$html .= '									<input type="text" name="video-edit-alt[]" value="' . esc_html(get_post_meta($video->ID, '_wp_attachment_image_alt', true)) . '" />' . PHP_EOL;
				$html .= '								</div>' . PHP_EOL;
				$html .= '							</div>' . PHP_EOL;
				$html .= '							<div class="clear"></div>' . PHP_EOL;
											
				$html .= '							<div class="field">' . PHP_EOL;
				$html .= '								<div class="field-label">' .  esc_html__('Caption', 'maxgalleria') .'</div>' . PHP_EOL;
				$html .= '								<div class="field-value">' . PHP_EOL;
				$html .= '									<input type="text" name="video-edit-caption[]" value="' . esc_html($video->post_excerpt) . '" />' . PHP_EOL;
				$html .= '								</div>' . PHP_EOL;
				$html .= '							</div>' . PHP_EOL;
				$html .= '							<div class="clear"></div>' . PHP_EOL;
				$html .= '						</div>' . PHP_EOL;
				$html .= '					</td>' . PHP_EOL;
				$html .= '				</tr>' . PHP_EOL;                                                      
                } 
              }
            }
        $html .= '<tr>' . PHP_EOL;
        $html .= '  <td></td>' . PHP_EOL;
        $html .= '  <td>' . PHP_EOL;
        $html .= '    <a class="btn btn-primary" id="bulk-save-button">' . esc_html__('Save Changes', 'maxgalleria') . '</a>' . PHP_EOL;
        $html .= '    <input type="button" class="btn" id="bulk-cancel-button" value="' . esc_html__('Cancel', 'maxgalleria') . '" />' . PHP_EOL;
        $html .= '  </td>' . PHP_EOL;
        $html .= '  <td></td>' . PHP_EOL;
        $html .= '</tr>' . PHP_EOL; 
            
        $html .= '</table>' . PHP_EOL;
                
      }

      $html .= wp_nonce_field($this->video_gallery->nonce_video_edit_bulk['action'], $this->video_gallery->nonce_video_edit_bulk['name']);
      
      $allowed_html = array(
        'a' => array(
          'href' => array(),
          'title' => array(),
          'class' => array(),
          'id' => array()
        ),
        'img' => array(
          'src' => array(),
          'alt' => array(),
          'class' => array(),
          'loading' => array(),
          'srcset' => array(),
          'srcset' => array(),
          'sizes' => array(),
          'width' => array(),
          'height' => array()
        ),
        'input' => array(
          'id' => array(),
          'class' => array(),
          'value' => array(),
          'name' => array(),
          'type' => array()
        ),
        'div' => array(
          'class' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'table' => array(
          'id' => array(),
          'cellpadding' => array(),
          'cellspacing' => array()
        ),
        'tr' => array(),
        'td' => array(
          'class' => array()
        )
      );
      
      echo wp_kses($html, $allowed_html);
            
    }
    
    die();    
    
  }
  
  public function mg_save_bulk_video() {
    
    global $wpdb;
    $form_data = '';
            
    if (isset($_POST) && check_admin_referer($this->video_gallery->nonce_video_edit_bulk['action'], $this->video_gallery->nonce_video_edit_bulk['name'])) {
      
      if ((isset($_POST['form_data'])) && (strlen(trim($_POST['form_data'])) > 0)) {
        parse_str($_POST['form_data'], $form_data);
              
        if(isset($form_data['video-edit-id'])) {
          
          $i = 0;
            
          foreach ($form_data['video-edit-id'] as $video_edit_id) {            
            // First update the post itself
            $temp = array();
            $temp['ID'] = sanitize_text_field($video_edit_id);

            $post_title = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($form_data['video-edit-title'][$i]));
            $post_excerpt = $this->stripUnwantedTagsAndAttrs(wp_filter_post_kses($form_data['video-edit-caption'][$i]));

            $temp['post_title'] = $this->remove_ptags($post_title);
            $temp['post_excerpt'] = $this->remove_ptags($post_excerpt);

            wp_update_post($temp);

            // Now update the image alt in the meta
            update_post_meta($video_edit_id, '_wp_attachment_image_alt', stripslashes(sanitize_text_field($form_data['video-edit-alt'][$i])));

            // Increment the counter
            $i++;
          }
                      
        }   
        
      }  
            
    }
        
    die();
    
  }
  
  private function check_for_mp4_video($gallery, $video_url, $uploads_dir) {
    global $wpdb;
    $updated = false;

    $comma_position = strpos($video_url, ',');
    if($comma_position !== false) {
      $comma_position2 = strrpos($video_url, ',');								

      $video = esc_url_raw(substr($video_url, 0, $comma_position ));
      $thumb_url = esc_url_raw(substr($video_url, $comma_position+1, $comma_position2 - ($comma_position+1) ));
      $thumb_id = sanitize_text_field(substr($video_url, $comma_position2+1));

      $video_path = str_replace( $uploads_dir['baseurl'], $uploads_dir['basedir'], $video ); 
      $thumbnail_path = str_replace( $uploads_dir['baseurl'], $uploads_dir['basedir'], $thumb_url ); 

      require_once(ABSPATH . 'wp-admin/includes/media.php');

      $filetype = wp_check_filetype(basename($thumbnail_path), null);
      $data = wp_read_video_metadata($video_path);

      // Include image.php so we can call wp_generate_attachment_metadata()
      require_once(ABSPATH . 'wp-admin/includes/image.php');

      // Insert the attachment
      $attachment_id = $thumb_id;

      // update the partent of the post thumbnail record
      $menu_order = $this->common->get_next_menu_order($gallery->ID);

      $post_table = $wpdb->prefix . "posts";
      $record = array('post_parent' => $gallery->ID, 'menu_order' => $menu_order );
      $where = array('ID' => $thumb_id);
      $wpdb->update( $post_table, $record, $where);

      $data['id'] = $attachment_id;								
      if(!isset($data['duration']) && isset($data['length']))
        $data['duration'] = $data['length'];
      if(!isset($data['title']))
        $data['title'] = preg_replace( '/\.[^.]+$/', '', basename($video_path));
           
      // Save some of the data in the post meta of the attachment
      do_action(MAXGALLERIA_ACTION_VIDEO_ATTACHMENT_POST_META, $attachment_id, $video, $thumb_url, $data);
      $updated = true;
    }  
    return $updated;
  }
  
  public function mg_add_videos() {
    
    global $wpdb;
    //global $maxgalleria;
    //$video_gallery = $this->video_gallery;
  
    if (isset($_POST) && check_admin_referer($this->video_gallery->nonce_video_add['action'], $this->video_gallery->nonce_video_add['name'])) {
      
      // lines could contain mixed data, so too early to sanitize until it is parsed
      if ((isset($_POST['video_urls'])) && (strlen(trim($_POST['video_urls'])) > 0)) {
			  $video_urls = explode("\n", $_POST['video_urls']);
        foreach($video_urls as $video_url) {
          $video_url = sanitize_url($video_url);
        }
      } else
        $video_urls = '';
      
      if ((isset($_POST['post_id'])) && (strlen(trim($_POST['post_id'])) > 0))
        $post_id = sanitize_text_field($_POST['post_id']);
      else
        $post_id = 0;
      
      if(is_numeric($post_id) && $post_id != 0) {

        $gallery = get_post($post_id);

        do_action(MAXGALLERIA_ACTION_BEFORE_ADD_VIDEOS_TO_GALLERY, $video_urls);

        if(class_exists('MaxGalleriaVideo'))
          global $maxgalleria_video;

        $site_url = site_url();
        $uploads_dir = wp_upload_dir();

        foreach ($video_urls as $video_url) {

          // Use rtrim to remove the \n on the end of the strings
          $video_url = rtrim($video_url);

          if ($video_url != '') {

            if(strpos($video_url, '.mp4') != false)
              $mp4 = true;
            else
              $mp4 = false;

            if(class_exists('MaxGalleriaVideo') && class_exists("MaxGalleriaMediaLibProS3") && $mp4) {

              global $maxgalleria_media_library_pro_s3;

              if(strpos($video_url, $maxgalleria_media_library_pro_s3->bucket) != false) {
                $updated = $this->check_for_mp4_video($gallery, $video_url, $uploads_dir);
              }            
            } else {

              $updated = $this->check_for_mp4_video($gallery, $video_url, $uploads_dir);

              $video_url = sanitize_url($video_url);
              // is this a vimeo url?
              if( strpos($video_url, "vimeo.com") === false) {
                $vimeo = false;
                if(strpos($video_url, "youtube.com") != false) {
                  $page_link = strpos($video_url, "youtube.com/watch?v=");

                  // if an yourtube embedded url convert it to a page link
                  if($page_link === false) {
                    $video_pos = strrpos($video_url, '/');
                    $video_url = "https://www.youtube.com/watch?v=" . substr($video_url, $video_pos+1);
                  }
                }
              } else {
                $vimeo = true;
              }

              // Get the data for the video; first initialize the API URL
              // and then pass it to the filter so it can get populated
              $api_url = '';
              $api_url = apply_filters(MAXGALLERIA_FILTER_VIDEO_API_URL, $api_url, $video_url);


              // Continue only if we have an API URL to call
              if ($api_url != '') {
                // Perform a remote GET to get the body of data from
                // the API URL and then decode it into JSON bits
                $response = wp_remote_get($api_url);
                $contents = wp_remote_retrieve_body($response);
                $data = json_decode($contents, true);


                // Get the URL of the video thumbnail; first initialize the thumb
                // URL and then pass it to the filter so it can get populated
                $thumb_url = '';
                $thumb_url = apply_filters(MAXGALLERIA_FILTER_VIDEO_THUMB_URL, $thumb_url, $video_url, $data);

                // Now that we have the thumb URL, get its remote contents
                // so we can store it as an attachment for the gallery
                $response = wp_remote_get($thumb_url);
                $contents = wp_remote_retrieve_body($response);

                // Upload and get file data
                $default_name = basename($thumb_url);
                $position = strrpos($default_name, '.');

                if($vimeo)
                  $new_file_name = $data[0]['title'];
                else
                  $new_file_name = $data['items'][0]['snippet']['title'];

                // remove illegal characters
                $new_file_name = preg_replace("[\\~#%&*{}/:<>?|\"-]", "", $new_file_name);
                // replace spaces
                $new_file_name = str_replace(' ', '-', $new_file_name);
                // remove extra dashes
                $new_file_name = preg_replace('/-+/', '-', $new_file_name);
                // limit to 30 characters														
                $new_file_name = substr($new_file_name, 0, 30);
                // remove ending dashes
                $new_file_name = rtrim($new_file_name, '-');							
                // add the extention
                $new_file_name = $new_file_name . substr($default_name, $position);
                //$upload = wp_upload_bits(basename($thumb_url), null, $contents);

                if($vimeo) // add missing extention
                  $new_file_name .= '.jpg';

                $upload = wp_upload_bits($new_file_name, null, $contents);

                $guid = $upload['url'];
                $file = $upload['file'];
                $file_type = wp_check_filetype(basename($file), null);

                // Set up the video thumb as an attachment; first initialize the
                // attachment and then pass it to the filter so it can get populated
                $attachment = array();
                $attachment = apply_filters(MAXGALLERIA_FILTER_VIDEO_ATTACHMENT, $attachment, $video_url, $gallery->ID, $guid, $file_type['type'], $data);

                // Include image.php so we can call wp_generate_attachment_metadata()
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Insert the attachment
                $attachment_id = wp_insert_attachment($attachment, $file, $gallery->ID);
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $file);
                wp_update_attachment_metadata($attachment_id, $attachment_data);


                if(class_exists("MaxGalleriaMediaLibProS3")) {

                  global $maxgalleria_media_library_pro_s3;

                  $image_url = wp_get_attachment_url($attachment_id);
                  $filename = get_attached_file($attachment_id);

                  // upload the image
                  $post_type = 'attachment';
                  $location = $maxgalleria_media_library_pro_s3->get_location($image_url, $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->uploads_folder_name);
                  $destination_location = $maxgalleria_media_library_pro_s3->get_destination_location($location);
                  $destination_folder  = $maxgalleria_media_library_pro_s3->get_destination_folder($destination_location, $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->uploads_folder_name_length);
                  $upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $location, $filename, $attachment_id);

                  // upload thumbnails
                  $metadata = wp_get_attachment_metadata($attachment_id);

                  foreach($metadata['sizes'] as $thumbnail) {
                    $source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
                    $upload_result = $maxgalleria_media_library_pro_s3->upload_to_s3($post_type, $destination_location . '/' . $thumbnail['file'], $source_file, 0);
                  }

                  // delete from local server										
                  if($maxgalleria_media_library_pro_s3->remove_from_local) {
                    if($upload_result['statusCode'] == '200')	{
                      $maxgalleria_media_library_pro_s3->remove_media_file($filename);										
                      foreach($metadata['sizes'] as $thumbnail) {
                        $source_file = $maxgalleria_media_library_pro_s3->maxgalleria_media_library_pro->get_absolute_path($maxgalleria_media_library_pro_s3->uploadsurl . $destination_folder . $thumbnail['file']);
                        $maxgalleria_media_library_pro_s3->remove_media_file($source_file);										
                      }
                    }
                  }
                }

                // Save some of the data in the post meta of the attachment
                do_action(MAXGALLERIA_ACTION_VIDEO_ATTACHMENT_POST_META, $attachment_id, $video_url, $thumb_url, $data);
                $updated = true;
              }
            }
          }
        }

        do_action(MAXGALLERIA_ACTION_AFTER_ADD_VIDEOS_TO_GALLERY, $video_urls);
      
      }
      echo 'ok';
      
    }
    
    die();
  }

      			
}

// Let's get this party started
$maxgalleria = new MaxGalleria();

?>