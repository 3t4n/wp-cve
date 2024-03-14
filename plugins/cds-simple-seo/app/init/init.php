<?php 

namespace app\init;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

use app\Admin\Meta as Meta;

/**
 * Initialization class for, everything.
 *
 * @since 2.0.0
 */
class init {
    /**
     * Plugin version for enqueueing, etc.
     * The value is retrieved from the CDS_SSEO_VERSION constant.
     *
     * @since 2.0.0
     *
     * @var string
     */
	public $version = '';
	
	public function __construct() {
		$this->version = SSEO_VERSION;
		$this->settings();
		new Enqueue();

		/* headings, content, column sorting */
		$post_types = get_post_types(['public' => true]);
		if (is_array($post_types)) {
			foreach($post_types as $post_type) {
				add_filter('manage_'.$post_type.'_posts_columns', [$this, 'columnHeading'], 10, 1);
				add_action('manage_'.$post_type.'_posts_custom_column', [$this, 'columnContent'], 10, 2);
				add_action('manage_edit-'.$post_type.'_sortable_columns', [$this, 'columnSort'], 10, 2);
			}
			unset($post_type);
		}
		
		/* Default metabox for posts/pages */
		add_action('add_meta_boxes', [$this, 'metaBoxes']);
		
		/* Taxonomies */
		add_action('category_add_form_fields', [$this, 'renderNewTaxonomyMetaBox']);
		add_action('category_edit_form_fields', [$this, 'renderEditTaxonomyMetaBox']);
		add_action('post_tag_add_form_fields', [$this, 'renderNewTaxonomyMetaBox']);
		add_action('post_tag_edit_form_fields', [$this, 'renderEditTaxonomyMetaBox']);
		
		/* Quick Edit */
		add_action('quick_edit_custom_box', [$this, 'renderQuickEditMetaBox']);

		/* WooCommerce */
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			add_action('product_cat_add_form_fields', [$this, 'renderNewTaxonomyMetaBox']);
			add_action('product_cat_edit_form_fields', [$this, 'renderEditTaxonomyMetaBox']);
			add_action('product_tag_add_form_fields', [$this, 'renderNewTaxonomyMetaBox']);
			add_action('product_tag_edit_form_fields', [$this, 'renderEditTaxonomyMetaBox']);
		}
		
		/* Header, Title, Meta */
		add_action('wp_head', [$this, 'renderHeader']);
		$title = new Meta\Title();
		add_filter('pre_get_document_title', [$title, 'getTitle']);
		add_filter('wp_title', [$title, 'getTitle']);
		
		/* Analytics */
		add_filter('wp_head', [$this, 'renderAnalytics']);
		
		/* Sitemap */
		if (get_option('sseo_generate_sitemap') == true) {
			/* disable WordPress sitemaps in favor of SimpleSEO sitemap. */
			apply_filters('wp_sitemaps_enabled', false);
		}
	}
	
	/**
 	 * Registers all the settings.
	 *
 	 * @since  2.0.0
 	 */
	private function settings() {
		register_setting('sseo-settings-group', 'sseo_default_meta_title');
		register_setting('sseo-settings-group', 'sseo_default_meta_description');
		register_setting('sseo-settings-group', 'sseo_default_meta_keywords');
		register_setting('sseo-settings-group', 'sseo_gsite_verification');
		register_setting('sseo-settings-group', 'sseo_ganalytics');
		register_setting('sseo-settings-group', 'sseo_g4analytics');
		register_setting('sseo-settings-group', 'sseo_bing');
		register_setting('sseo-settings-group', 'sseo_yandex');
		register_setting('sseo-settings-group', 'sseo_robot_noindex');
		register_setting('sseo-settings-group', 'sseo_robot_nofollow');
		register_setting('sseo-settings-group', 'sseo_fb_app_id');
		register_setting('sseo-settings-group', 'sseo_fb_title');
		register_setting('sseo-settings-group', 'sseo_fb_description');
		register_setting('sseo-settings-group', 'sseo_fb_image');
		register_setting('sseo-settings-group', 'sseo_twitter_username');
		register_setting('sseo-settings-group', 'sseo_tw_title');
		register_setting('sseo-settings-group', 'sseo_tw_description');
		register_setting('sseo-settings-group', 'sseo_tw_image');
		register_setting('sseo-settings-group', 'sseo_canonical_url');
		register_setting('sseo-settings-group', 'sseo_generate_sitemap');
		register_setting('sseo-settings-group', 'sseo_sitemap_post_types');
    }
	
	/**
 	 * Activation.
	 *
	 * @since  2.0.0
 	 */
	public function activate() {
    }
    
	/**
 	 * Deactivate.
	 *
	 * @since  2.0.0
 	 */
	public function deactivate() {
    }

	/**
	 * Adds column heading names
	 *
	 * @since  2.0.0
	 */
	public function columnHeading($columns) {
		$columns['post_name'] = __('Post Name', SSEO_TXTDOMAIN);
		$columns['seo_title'] = __('SEO Title', SSEO_TXTDOMAIN);
		$columns['seo_description'] = __('Meta Description', SSEO_TXTDOMAIN);
		return $columns;
	}

	/**
	 * Adds column heading names
	 *
	 * @since  2.0.0
	 */
	public function columnContent($column, $post_id) {
		switch ($column) {
			case 'post_name':
				$post = get_post($post_id);
				echo esc_html($post->post_name);
				break;
			case 'seo_title':
				echo esc_html(get_post_meta($post_id, 'sseo_meta_title', true));
				break;
			case 'seo_description':
				echo esc_html(get_post_meta($post_id, 'sseo_meta_description', true));
				break;
		}
	}

	/**
	 * Adds column heading names
	 *
	 * @since  2.0.0
	 */
	public function columnSort($columns) {
		$columns['post_name'] = 'post_name';
		$columns['seo_title'] = 'seo_title';
		$columns['seo_description'] = 'seo_description';
		return $columns;
	}

	/**
	 * Creats our meta box for Simple SEO.
	 * We can then use the app\Admin\Meta\Post
	 * in many places.
	 *
	 * @since  2.0.0
	 */
	public function metaBoxes($postType) {
		add_meta_box(
			'simple-seo', 
			__('Simple SEO'), 
			[$this, 'renderMetaBox'],
			$postType
		);
	}

	/**
	 * Renders the meta box for pages, posts, etc
	 *
	 * @since  2.0.0
	 */
	public function renderMetaBox($post) {
		new Meta\Post($post);
	}

	/**
	 * Renders the meta box for a new taxonomy
	 *
	 * @since  2.0.0
	 */
	public function renderNewTaxonomyMetaBox() {
		$Taxonomy = new Meta\Taxonomy();
		$Taxonomy->newMetaBox();
	}

	/**
	 * Renders the meta box for editing a taxonomy
	 *
	 * @since  2.0.0
	 */
	public function renderEditTaxonomyMetaBox($term) {
		$Taxonomy = new Meta\Taxonomy();
		$Taxonomy->editMetaBox($term);
	}

	/**
	 * Renders the meta box for editing a quick edit
	 *
	 * @since  2.0.0
	 */
	public function renderQuickEditMetaBox($columnName) {
		new Meta\QuickEdit($columnName);
	}

	/**
	 * Renders the meta data for the header
	 *
	 * @since  2.0.0
	 */
	public function renderHeader() {
		if (!is_admin()) {
			new Meta\Header();
		}
	}

	/**
	 * Renders the meta data for Analytics
	 *
	 * @since  2.0.0
	 */
	public function renderAnalytics() {
		if (!is_admin()) {
			new Meta\Analytics();
		}
	}
	
}

?>