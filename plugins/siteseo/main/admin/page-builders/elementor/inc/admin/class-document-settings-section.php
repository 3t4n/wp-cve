<?php

namespace SiteSeoElementorAddon\Admin;

if ( ! defined('ABSPATH')) {
	exit();
}

class Document_Settings_Section {
	use \SiteSeoElementorAddon\Singleton;

	/**
	 * Initialize class.
	 *
	 * @return void
	 */
	private function _initialize() {
		add_action('elementor/editor/before_enqueue_scripts', [$this, 'check_security']);
		add_action('elementor/documents/register_controls', [$this, 'add_siteseo_section_to_document_settings'], 20);
		add_action('elementor/document/after_save', [$this, 'on_save'], 99, 2);
		add_action('siteseo/page-builders/elementor/save_meta', [$this, 'on_siteseo_meta_save'], 99);
		add_action('elementor/editor/before_enqueue_scripts', [$this, 'register_elements_assets'], 9999);
	}

	/**
	 * Is the current user allowed to view metaboxes?
	 *
	 * @return boolean
	 */
	public function check_security($metabox) {
		if (is_bool($metabox)) {
			return true;
		}

		if (is_super_admin()) {
			return true;
		}

		global $wp_roles;

		//Get current user role
		if (isset(wp_get_current_user()->roles[0])) {
			$siteseo_user_role = wp_get_current_user()->roles[0];
			//If current user role matchs values from Security settings then apply
			if (empty($metabox)) {
				return true;
			}
			if (!array_key_exists($siteseo_user_role, $metabox)) {
				return true;
			}

			return false;
		}
	}

	public function register_elements_assets() {
		wp_register_script(
			'siteseo-elementor-base-script',
			SITESEO_ELEMENTOR_ADDON_URL . 'assets/js/base.js',
			['jquery'],
			SITESEO_VERSION,
			true
		);

		if (get_current_user_id()) {
			if (get_user_meta(get_current_user_id(), 'elementor_preferences', true)) {
				$settings = get_user_meta(get_current_user_id(), 'elementor_preferences', true);

				if ( ! empty($settings) && isset($settings['ui_theme']) && 'dark' == $settings['ui_theme']) {
					wp_enqueue_style(
						'siteseo-el-dark-mode-style',
						SITESEO_ELEMENTOR_ADDON_URL . 'assets/css/dark-mode.css'
					);
				}
			}
		}

		global $post;

		$term	  = '';
		$origin	= '';
		$post_type = '';
		$post_id   = '';
		$keywords  = '';

		if (is_archive()) {
			$origin = 'term';
		}

		if (is_singular()) {
			$post_id   = $post->ID;
			$post_type = $post->post_type;
			$origin	= 'post';
			$keywords  = get_post_meta($post_id, '_siteseo_analysis_target_kw', true);
			if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
				$is_elementor = true;
			}
		}

		$siteseo_real_preview = [
			'siteseo_nonce'		=> wp_create_nonce('siteseo_real_preview_nonce'),
			'siteseo_real_preview' => admin_url('admin-ajax.php'),
			'post_id'			   => $post_id,
			'i18n'				  => ['progress' => __('Analysis in progress...', 'siteseo')],
			'post_type'			 => $post_type,
			'post_tax'			  => $term,
			'origin'				=> $origin,
			'keywords'			  => $keywords,
			'is_elementor'		  => $is_elementor,
		];

		wp_localize_script('siteseo-elementor-base-script', 'siteseoElementorBase', $siteseo_real_preview);
	}

	/**
	 * Add WP SiteSEO section under document settings.
	 *
	 * @return void
	 */
	public function add_siteseo_section_to_document_settings(\Elementor\Core\Base\Document $document) {
		$post_id = $document->get_main_id();

		$seo_metabox = siteseo_get_service('AdvancedOption')->getSecurityMetaboxRole() ? siteseo_get_service('AdvancedOption')->getSecurityMetaboxRole() : true;
		$ca_metabox = siteseo_get_service('AdvancedOption')->getSecurityMetaboxRoleContentAnalysis() ? siteseo_get_service('AdvancedOption')->getSecurityMetaboxRoleContentAnalysis() : true;

		if ($this->check_security($seo_metabox) === true) {
			$this->_add_title_section($document, $post_id);
			$this->_add_advanced_section($document, $post_id);
			$this->_add_social_section($document, $post_id);
			$this->_add_redirection_section($document, $post_id);
		}

		if ($this->check_security($ca_metabox) === true) {
			$this->_add_content_analysis_section($document, $post_id);
		}
	}

	/**
	 * Add title section.
	 *
	 * @param \Elementor\Core\Base\Document $document
	 * @param int						   $post_id
	 *
	 * @return void
	 */
	private function _add_title_section($document, $post_id) {
		$document->start_controls_section(
			'siteseo_title_settings',
			[
				'label' => __('SEO Title / Description', 'siteseo'),
				'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$s_title = get_post_meta($post_id, '_siteseo_titles_title', true);
		$s_desc  = get_post_meta($post_id, '_siteseo_titles_desc', true);

		$original_desc = substr(strip_tags(get_the_content(null, true, $post_id)), 0, 140);

		$desc  = $s_desc ? $s_desc : $original_desc;
		$title = ! empty($s_title) ? $s_title : get_the_title($post_id);

		$document->add_control(
			'_siteseo_titles_title',
			[
				'label'	   => __('Title', 'siteseo'),
				'type'		=> 'siteseotextlettercounter',
				'field_type'  => 'text',
				'label_block' => true,
				'separator'   => 'none',
				'default'		=> $s_title ? $s_title : '',
			]
		);

		$document->add_control(
			'_siteseo_titles_desc',
			[
				'label'	   => __('Meta Description', 'siteseo'),
				'type'		=> 'siteseotextlettercounter',
				'field_type'  => 'textarea',
				'label_block' => true,
				'separator'   => 'none',
				'default'		=> $s_desc ? $s_desc : '',
			]
		);

		$document->add_control(
			'social_preview_google',
			[
				'label'	   => __('Google Snippet Preview', 'siteseo'),
				'type'		=> 'siteseo-social-preview',
				'label_block' => true,
				'separator'   => 'none',
				'network'	 => 'google',
				'title'	   => $title ? $title : '',
				'description' => $desc ? $desc : '',
				'link'		=> get_permalink($post_id),
				'post_id'	 => $post_id,
				'origin'	  => is_singular() ? 'post' : 'term',
				'post_type'   => get_post_status($post_id),
			]
		);

		$document->end_controls_section();
	}

	/**
	 * Add advanced section.
	 *
	 * @param \Elementor\Core\Base\Document $document
	 * @param int						   $post_id
	 *
	 * @return void
	 */
	private function _add_advanced_section($document, $post_id) {
		$document->start_controls_section(
			'_siteseo_advanced_settings',
			[
				'label' => __('SEO Advanced', 'siteseo'),
				'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$robots_index	   = get_post_meta($post_id, '_siteseo_robots_index', true);
		$robots_follow	  = get_post_meta($post_id, '_siteseo_robots_follow', true);
		$robots_imageindex  = get_post_meta($post_id, '_siteseo_robots_imageindex', true);
		$robots_archive	 = get_post_meta($post_id, '_siteseo_robots_archive', true);
		$robots_snippet	 = get_post_meta($post_id, '_siteseo_robots_snippet', true);
		$robots_canonical   = get_post_meta($post_id, '_siteseo_robots_canonical', true);
		$robots_primary_cat = get_post_meta($post_id, '_siteseo_robots_primary_cat', true);
		$robots_breadcrumbs = get_post_meta($post_id, '_siteseo_robots_breadcrumbs', true);

		$document->add_control(
			'_siteseo_robots_index',
			[
				'label'	   => __('Don\'t display this page in search engine results / Sitemaps (noindex)', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::SWITCHER,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => 'yes' === $robots_index ? 'yes' : '',
			]
		);

		$document->add_control(
			'_siteseo_robots_follow',
			[
				'label'	   => __('Don\'t follow links for this page (nofollow)', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::SWITCHER,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => 'yes' === $robots_follow ? 'yes' : '',
			]
		);

		$document->add_control(
			'_siteseo_robots_imageindex',
			[
				'label'	   => __('Don\'t index images for this page (noimageindex)', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::SWITCHER,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => 'yes' === $robots_imageindex ? 'yes' : '',
			]
		);

		$document->add_control(
			'_siteseo_robots_archive',
			[
				'label'	   => __('Don\'t display a "Cached" link in the Google search results (noarchive)', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::SWITCHER,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => 'yes' === $robots_archive ? 'yes' : '',
			]
		);

		$document->add_control(
			'_siteseo_robots_snippet',
			[
				'label'	   => __('Don\'t display a description in search results for this page (nosnippet)', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::SWITCHER,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => 'yes' === $robots_snippet ? 'yes' : '',
			]
		);

		$document->add_control(
			'_siteseo_robots_canonical',
			[
				'label'	   => __('Canonical URL', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => $robots_canonical ? $robots_canonical : '',
			]
		);

		global $typenow;
		global $pagenow;
		if (('post' == $typenow || 'product' == $typenow) && ('post.php' == $pagenow || 'post-new.php' == $pagenow)) {
			$cats = get_categories();

			if ('product' == $typenow) {
				$cats = get_the_terms($post_id, 'product_cat');
			}

			if ( ! empty($cats)) {
				$options = [];

				foreach ($cats as $category) {
					$options[$category->term_id] = $category->name;
				}
				$options['none'] = __('None (will disable this feature)', 'siteseo');
			}

			if ( ! empty($options)) {
				$document->add_control(
					'_siteseo_robots_primary_cat',
					[
						'label'	   => __('Select a primary category', 'siteseo'),
						'description' => __('Set the category that gets used in the %category% permalink and in our breadcrumbs if you have multiple categories.', 'siteseo'),
						'type'		=> \Elementor\Controls_Manager::SELECT,
						'label_block' => true,
						'separator'   => 'none',
						'options'	 => $options,
						'default'	 => $robots_primary_cat ? (int) $robots_primary_cat : 'none',
					]
				);
			}
		}

		if (is_plugin_active('siteseo-pro/siteseo-pro.php')) {
			$document->add_control(
				'_siteseo_robots_breadcrumbs',
				[
					'label'	   => __('Custom breadcrumbs', 'siteseo'),
					'description' => __('Enter a custom value, useful if your title is too long', 'siteseo'),
					'type'		=> \Elementor\Controls_Manager::TEXT,
					'label_block' => true,
					'separator'   => 'none',
					'default'	 => $robots_breadcrumbs ? $robots_breadcrumbs : '',
				]
			);
		}

		$document->end_controls_section();
	}

	/**
	 * Add social section.
	 *
	 * @param \Elementor\Core\Base\Document $document
	 * @param int						   $post_id
	 *
	 * @return void
	 */
	private function _add_social_section($document, $post_id) {
		$document->start_controls_section(
			'_siteseo_social_settings',
			[
				'label' => __('SEO Social', 'siteseo'),
				'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$fb_title	  = get_post_meta($post_id, '_siteseo_social_fb_title', true);
		$fb_desc	   = get_post_meta($post_id, '_siteseo_social_fb_desc', true);
		$fb_image	  = get_post_meta($post_id, '_siteseo_social_fb_img', true);
		$twitter_title = get_post_meta($post_id, '_siteseo_social_twitter_title', true);
		$twitter_desc  = get_post_meta($post_id, '_siteseo_social_twitter_desc', true);
		$twitter_image = get_post_meta($post_id, '_siteseo_social_twitter_img', true);

		$default_preview_title = get_the_title($post_id);
		$default_preview_desc  = substr(strip_tags(get_the_content(null, true, $post_id)), 0, 140);

		$document->add_control(
			'_siteseo_social_note',
			[
				//'label' => __( 'Important Note', 'siteseo' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => sprintf(__('<p class="elementor-control-field-description"><span class="dashicons dashicons-external"></span><a href="https://developers.facebook.com/tools/debug/sharing/?q=%s" target="_blank">Ask Facebook to update its cache</a></p>', 'siteseo'), get_permalink(get_the_id())),
				//'content_classes' => 'your-class',
			]
		);

		$document->add_control(
			'_siteseo_social_note_2',
			[
				//'label' => __( 'Important Note', 'siteseo' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => __('<p class="elementor-control-field-description"><strong>Did you know?</strong> LinkedIn, Instagram and Pinterest use the same social metadata as Facebook. Twitter does the same if no Twitter cards tags are defined below.</p>', 'siteseo'),
				//'content_classes' => 'your-class',
			]
		);

		$document->add_control(
			'_siteseo_social_fb_title',
			[
				'label'	   => __('Facebook Title', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => $fb_title ? $fb_title : '',
			]
		);

		$document->add_control(
			'_siteseo_social_fb_desc',
			[
				'label'	   => __('Facebook description', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => $fb_desc ? $fb_desc : '',
			]
		);

		$document->add_control(
			'_siteseo_social_fb_img',
			[
				'label'	   => __('Facebook Thumbnail', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => [
					'url' => $fb_image ? $fb_image : '',
				],
			]
		);

		$document->add_control(
			'social_preview_facebook',
			[
				'label'	   => __('Facebook Preview', 'siteseo'),
				'type'		=> 'siteseo-social-preview',
				'label_block' => true,
				'separator'   => 'none',
				'network'	 => 'facebook',
				'image'	   => $fb_image ? $fb_image : '',
				'title'	   => $fb_title ? $fb_title : $default_preview_title,
				'description' => $fb_desc ? $fb_desc : $default_preview_desc,
			]
		);

		$document->add_control(
			'_siteseo_social_twitter_title',
			[
				'label'	   => __('Twitter Title', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => $twitter_title ? $twitter_title : '',
			]
		);

		$document->add_control(
			'_siteseo_social_twitter_desc',
			[
				'label'	   => __('Twitter description', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::TEXTAREA,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => $twitter_desc ? $twitter_desc : '',
			]
		);

		$document->add_control(
			'_siteseo_social_twitter_img',
			[
				'label'	   => __('Twitter Thumbnail', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => [
					'url' => $twitter_image ? $twitter_image : '',
				],
			]
		);

		$document->add_control(
			'social_preview_twitter',
			[
				'label'	   => __('Twitter Preview', 'siteseo'),
				'type'		=> 'siteseo-social-preview',
				'label_block' => true,
				'separator'   => 'none',
				'network'	 => 'twitter',
				'image'	   => $twitter_image ? $twitter_image : '',
				'title'	   => $twitter_title ? $twitter_title : $default_preview_title,
				'description' => $twitter_desc ? $twitter_desc : $default_preview_desc,
			]
		);

		$document->end_controls_section();
	}

	/**
	 * Add redirection section.
	 *
	 * @param \Elementor\Core\Base\Document $document
	 * @param int						   $post_id
	 *
	 * @return void
	 */
	private function _add_redirection_section($document, $post_id) {
		$document->start_controls_section(
			'siteseo_redirection_settings',
			[
				'label' => __('SEO Redirection', 'siteseo'),
				'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$redirections_enabled = get_post_meta($post_id, '_siteseo_redirections_enabled', true);
		$redirections_type	= get_post_meta($post_id, '_siteseo_redirections_type', true);
		$redirections_value   = get_post_meta($post_id, '_siteseo_redirections_value', true);

		$document->add_control(
			'_siteseo_redirections_enabled',
			[
				'label'	   => __('Enable redirection?', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::SWITCHER,
				'label_block' => false,
				'separator'   => 'none',
				'default'	 => 'yes' === $redirections_enabled ? 'yes' : '',
			]
		);

		$document->add_control(
			'_siteseo_redirections_type',
			[
				'label'	   => __('URL redirection', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::SELECT,
				'label_block' => true,
				'separator'   => 'none',
				'options'	 => [
					301 => __('301 Moved Permanently', 'siteseo'),
					302 => __('302 Found / Moved Temporarily', 'siteseo'),
					307 => __('307 Moved Temporarily', 'siteseo')
				],
				'default' => $redirections_type ? (int) $redirections_type : 301,
			]
		);

		$document->add_control(
			'_siteseo_redirections_value',
			[
				'label'	   => __('Enter your new URL in absolute (eg: https://www.example.com/)', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => $redirections_value ? $redirections_value : '',
			]
		);

		$document->end_controls_section();
	}

	/**
	 * Add Content analysis section.
	 *
	 * @param \Elementor\Core\Base\Document $document
	 * @param int						   $post_id
	 *
	 * @return void
	 */
	private function _add_content_analysis_section($document, $post_id) {
		$document->start_controls_section(
			'siteseo_content_analysis_settings',
			[
				'label' => __('SEO Content Analysis', 'siteseo'),
				'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
			]
		);

		$keywords = get_post_meta($post_id, '_siteseo_analysis_target_kw', true);

		$document->add_control(
			'_siteseo_analysis_note',
			[
				//'label' => __( 'Important Note', 'siteseo' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => __('<p class="elementor-control-field-description">Enter a few keywords for analysis to help you write optimized content.</p><p class="elementor-control-field-description"><strong>Did you know?</strong> Writing content for your users is the most important thing! If it doesn‘t feel natural, your visitors will leave your site, Google will know it and your ranking will be affected.</p>', 'siteseo'),
				//'content_classes' => 'your-class',
			]
		);

		$document->add_control(
			'_siteseo_analysis_target_kw',
			[
				'label'	   => __('Target keywords', 'siteseo'),
				'type'		=> \Elementor\Controls_Manager::TEXT,
				'description' => __('Separate target keywords with commas. Do not use spaces after the commas, unless you want to include them', 'siteseo'),
				'label_block' => true,
				'separator'   => 'none',
				'default'	 => $keywords ? $keywords : '',
			]
		);

		if (is_plugin_active('siteseo-pro/siteseo-pro.php')) {
			$document->add_control(
				'siteseo_google_suggest_kw',
				[
					'label'	   => __('Google suggestions', 'siteseo'),
					'type'		=> 'siteseo-google-suggestions',
					'label_block' => true,
					'separator'   => 'none',
				]
			);
		}

		$document->add_control(
			'siteseo_content_analyses',
			[
				'label'	   => '',
				'type'		=> 'siteseo-content-analysis',
				'description' => __('To get the most accurate analysis, save your post first. We analyze all of your source code as a search engine would.', 'siteseo'),
				'label_block' => true,
				'separator'   => 'none',
			]
		);

		$document->end_controls_section();
	}

	/**
	 * Before saving of the values in elementor.
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function on_save(\Elementor\Core\Base\Document $document, $data) {
		$settings = ! empty($data['settings']) ? $data['settings'] : [];

		if (empty($settings)) {
			return;
		}

		$post_id = $document->get_main_id();

		if ( ! $post_id) {
			return;
		}

		$siteseo_settings = array_filter(
			$settings,
			function ($key) {
				return in_array($key, $this->get_allowed_meta_keys(), true);
			},
			ARRAY_FILTER_USE_KEY
		);

		if (empty($siteseo_settings)) {
			return;
		}

		if (isset($siteseo_settings['_siteseo_social_fb_img'])) {
			$siteseo_settings['_siteseo_social_fb_img'] = $siteseo_settings['_siteseo_social_fb_img']['url'];
		}

		if (isset($siteseo_settings['_siteseo_social_twitter_img'])) {
			$siteseo_settings['_siteseo_social_twitter_img'] = $siteseo_settings['_siteseo_social_twitter_img']['url'];
		}

		$siteseo_settings = array_map('sanitize_text_field', $siteseo_settings);

		$post_id = wp_update_post(
			[
				'ID'		 => $post_id,
				'meta_input' => $siteseo_settings,
			]
		);

		if (is_wp_error($post_id)) {
			throw new \Exception(wp_kses_post($post_id->get_error_message()));
		}
	}

	/**
	 * Save siteseo meta to elementor.
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function on_siteseo_meta_save($post_id) {
		if ( ! class_exists('\Elementor\Core\Settings\Manager')) {
			return;
		}

		if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
			$meta = get_post_meta($post_id);

			$siteseo_meta = array_filter(
				$meta,
				function ($key) {
					return in_array($key, $this->get_allowed_meta_keys(), true);
				},
				ARRAY_FILTER_USE_KEY
			);

			if (empty($siteseo_meta)) {
				return;
			}

			$settings = [];

			foreach ($siteseo_meta as $key => $sm) {
				$settings[$key] = maybe_unserialize( ! empty($sm[0]) ? $sm[0] : '');
			}

			$seo_data['settings'] = $settings;

			$page_settings = get_metadata('post', $post_id, \Elementor\Core\Settings\Page\Manager::META_KEY, true);
			$settings	  = array_merge($page_settings, $settings);

			remove_action('siteseo/page-builders/elementor/save_meta', [$this, 'on_siteseo_meta_save'], 99);
			$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
			$page_settings_manager->ajax_before_save_settings($settings, $post_id);
			$page_settings_manager->save_settings($settings, $post_id);
			add_action('siteseo/page-builders/elementor/save_meta', [$this, 'on_siteseo_meta_save'], 99);
		}
	}

	public function get_allowed_meta_keys() {
		return siteseo_get_meta_helper()->get_meta_fields();
	}
}
