<?php

/**
 *
 */

namespace Reuse\Builder;

use Reuse\Builder\Reuse_Builder_Admin_Lacalize;

class Reuse_Builder_Admin_Scripts
{

	protected $custom_scripts = array(
		array(
			'key'	=> 'reuseb_post_type',
			'value'	=> 'reuseb_form_builder',
		),
		array(
			'key'	=> 'reuseb_taxonomy',
			'value'	=> 'reuseb_taxonomy_generator',
		),
		array(
			'key'	=> 'preview',
			'value'	=> 'reuseb_term_meta_preview', //
		),
		array(
			'key'	=> 'reuseb_term_metabox',
			'value'	=> 'reuseb_term_meta_generator_builder',
		),
		array(
			'key'	=> 'reuseb_template',
			'value'	=> 'reuseb_template_settings',
		),
		array(
			'key'	=> 'reuseb_metabox',
			'value'	=> 'reuseb_metabox_builder',
		),
		array(
			'key'	=> 'preview',
			'value'	=> 'reuseb_metabox_preview',
		),
		array(
			'key'	=> 'reuseb_post_type',
			'value'	=> 'reuseb_post_type_builder',
		),
		array(
			'key'	=> 'builder_page_reuse_builder_settings',
			'value'	=> 'reuseb_settings',
		),
	);

	protected $restricted_post_types = array(
		null,
		'reuseb_template',
		'reuseb_post_type',
		'reuseb_taxonomy',
		'reuseb_term_metabox',
		'reuseb_metabox',
	);

	protected $form_builder_sections = array(
		'reuseb_post_type',
		'reuseb_taxonomy',
		'reuseb_term_metabox',
		'reuseb_metabox',
	);

	public function __construct()
	{
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		add_filter('reuseb_admin_generator_localize_args', array($this, 'reuseb_admin_generator_localize_args'), 10, 1);
		//add_filter('script_loader_tag', array($this, 'add_custom_attribute' ), 10, 2);
	}

	public function admin_enqueue_scripts($hook)
	{
		wp_register_script('react', REUSE_BUILDER_JS_VENDOR . 'react.min.js', array(), $ver = true, true);
		wp_enqueue_script('react');
		wp_register_script('react-dom', REUSE_BUILDER_JS_VENDOR . 'react-dom.min.js', array(), $ver = true, true);
		wp_enqueue_script('react-dom');
		$this->redq_rb_load_reuse_form_scripts();

		wp_register_script('form-builder-variable', REUSE_BUILDER_JS_VENDOR . 'reuseb-form-builder-variable.js', array(), false, false);
		wp_enqueue_script('form-builder-variable');


		wp_register_script('reuseb-admin-init', REUSE_BUILDER_JS_VENDOR . 'reuseb-init.js', array('jquery'), true, true);
		wp_enqueue_script('reuseb-admin-init');

		wp_register_style('reuse-helper', REUSE_BUILDER_CSS . 'reuse-helper.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('reuse-helper');

		// wp_register_style('scholar-bundle-admin', REUSE_BUILDER_CSS.'scholar-bundle-admin.css', array(), $ver = false, $media = 'all');
		// wp_enqueue_style('scholar-bundle-admin');

		wp_register_style('scholar-bundle-admin-two', REUSE_BUILDER_CSS . 'scholar-bundle-admin-two.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('scholar-bundle-admin-two');

		wp_register_style('reuseb-bundle-admin-two', REUSE_BUILDER_CSS . 'reuseb-bundle-admin-two.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('reuseb-bundle-admin-two');

		wp_register_style('icomoon-css', REUSE_BUILDER_JS_VENDOR . 'icomoon.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('icomoon-css');
		wp_register_style('flaticon-css', REUSE_BUILDER_JS_VENDOR . 'flaticon.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('flaticon-css');
		wp_register_style('ionicons-css', REUSE_BUILDER_JS_VENDOR . 'ionicons.min.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('ionicons-css');
		wp_register_style('font-awesome', REUSE_BUILDER_JS_VENDOR . 'font-awesome.min.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('font-awesome');

		// start shortcode clipboard
		wp_register_script('highlight-pack-js', REUSE_BUILDER_JS_VENDOR . 'highlight.pack.min.js', array('jquery'), $ver = true, true);
		wp_enqueue_script('highlight-pack-js');
		wp_register_script('clipboardjs', '//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js', array('jquery'), $ver = true, true);
		wp_enqueue_script('clipboardjs');
		wp_register_style('shortcode-admin-css', REUSE_BUILDER_JS_VENDOR . 'shortcode-admin.css', array(), $ver = false, $media = 'all');
		wp_enqueue_style('shortcode-admin-css');
		wp_register_script('shortcode-admin-js', REUSE_BUILDER_JS_VENDOR . 'shortcode-admin.js', array('jquery'), false, true);
		wp_enqueue_script('shortcode-admin-js');
		// end shortcode clipboard

		wp_register_script('reuseb-media-upload', REUSE_BUILDER_JS_VENDOR . 'reuseb-widget-media-upload.js', array(), false, true);
		wp_enqueue_script('reuseb-media-upload');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('dashicons');
		wp_enqueue_media();
		// google map loading. need to change this dynamically
		// wp_enqueue_script('google-maps-js', '//maps.googleapis.com/maps/api/js?key=AIzaSyBF0FPDHlurGkDKua7PfZjpD2fr2rQsRw0&libraries=places,geometry&language=en-US' , true , false );
		$this->load_backend_scripts(REUSE_BUILDER_JS);
	}

	public function load_backend_scripts($publicPath)
	{
		// All other assets
		$admin_scripts = json_decode(file_get_contents(REUSE_BUILDER_FILE . "/resource/admin-assets.json"), true);

		$all_scripts = array();
		$all_scripts = $this->current_scripts();
		foreach ($admin_scripts as $filename => $file) {
			if (in_array($filename, $all_scripts)) {
				wp_register_script($filename, $publicPath . $file['js'], array('jquery', 'underscore', 'wp-color-picker'), $ver = false, true);
				wp_enqueue_script($filename);

				wp_localize_script($filename, 'RE_ICON', array('icon_provider' => apply_filters('reuse_builder_icon_picker',  array()),)); // For reuse form

				wp_localize_script($filename, 'REUSEB_AJAX_DATA', array(
					'action' => 'reuseb_ajax',
					'nonce' => wp_create_nonce('reuseb_ajax_nonce'),
					'admin_url' => admin_url('admin-ajax.php'),
				));
			}
		}
	}

	// dynamically load
	public function current_scripts()
	{
		$info = get_current_screen();
		$current_screen = null;
		if ($info->base == 'post' || $info->base == 'term' || $info->base == 'edit-tags')
			$current_screen = $info->post_type;
		elseif ($info->post_type == null)
			$current_screen = $info->base; // take the base when it's a page or options
		$all_scripts = [];
		$custom_scripts = $this->custom_scripts;
		$reuse_builder_settings = stripslashes_deep(get_option('reuseb_settings', true));
		$geobox_post_types = json_decode($reuse_builder_settings);
		$geobox_post_types_array = $geobox_post_types != '1' && $geobox_post_types->geobox_enable_post_type != '' ? explode(',', $geobox_post_types->geobox_enable_post_type) : [];
		foreach ($geobox_post_types_array as $key => $post_type) {
			$custom_scripts[] = array(
				'key' => $post_type,
				'value' => 'reuseb_geobox',
			);
		}
		foreach ($custom_scripts as $script_name) {
			if ($current_screen == $script_name['key']) {
				array_push($all_scripts, $script_name['value']);
			} elseif ($script_name['key'] == 'preview' && !in_array($current_screen, $this->restricted_post_types)) {
				array_push($all_scripts, $script_name['value']);
			}
		}
		if (!in_array('reuseb_form_builder', $all_scripts) && in_array($current_screen, $this->form_builder_sections)) {
			array_push($all_scripts, 'reuseb_form_builder');
		}
		return $all_scripts;
	}

	public function add_custom_attribute($tag, $handle)
	{
		$all = array_merge($this->custom_scripts, $this->reuse_scripts);
		foreach ($all as $script) {
			if ($script === $handle) {
				return str_replace(' src', ' defer="defer" src', $tag);
			}
		}
		if ($handle === 'reuse_vendor') {
			return str_replace(' src', ' defer="defer" src', $tag);
		}
		// if needed add async in here as defer
		return $tag;
	}

	public function redq_rb_load_reuse_form_scripts()
	{
		if (!is_plugin_active('redq-reuse-form/redq-reuse-form.php')) {
			wp_register_script('reuse-form-variable', REUSE_BUILDER_JS_VENDOR . 'reuse-form-variable.js', array(), $ver = true, true);
			wp_enqueue_script('reuse-form-variable');
			wp_register_style('reuse-form-two', REUSE_BUILDER_CSS . 'reuse-form-two.css', array(), $ver = false, $media = 'all');
			wp_enqueue_style('reuse-form-two');
			wp_register_style('reuse-form', REUSE_BUILDER_CSS . 'reuse-form.css', array(), $ver = false, $media = 'all');
			wp_enqueue_style('reuse-form');
			$reuse_form_scripts = new Reuse_Builder_Reuse;
			$webpack_public_path = get_option('webpack_public_path_url', true);
			$reuse_form_scripts->load($webpack_public_path);
		}
	}

	function reuseb_admin_generator_localize_args($args)
	{
		$args['postTypes'] 				=  Reuse_Builder_Admin_Lacalize::redq_get_all_posts();
		$args['taxonomies'] 			=  Reuse_Builder_Admin_Lacalize::redq_get_all_taxonomies();
		$args['LANG'] 					=  Reuse_Builder_Admin_Lacalize::redq_admin_language();
		$args['ERROR_MESSAGE'] 			=  Reuse_Builder_Admin_Lacalize::redq_admin_error();
		$args['DYNAMIC_TABS'] 			=  Reuse_Builder_Admin_Lacalize::dynamic_page_builder_tab_list();
		$args['DYNAMIC_PAGE'] 			=  Reuse_Builder_Admin_Lacalize::dynamic_page_builder_data_provider();
		$args['_WEBPACK_PUBLIC_PATH_'] 	=  REUSE_BUILDER_JS;

		// $args['INITIAL_METABOX_PREVIEW'] =  array();
		return $args;
	}
}
