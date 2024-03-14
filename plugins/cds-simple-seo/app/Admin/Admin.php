<?php 

namespace app\Admin;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

use app\Helpers\Import as Import;
use app\init\Sitemap as Sitemap;

/**
 * Administration class, menus, admin options, mostly hooks
 *
 * @since  2.0.0
 */
class Admin {

    public function __construct() {
		add_action('admin_menu', [$this, 'addMenu']);
		add_action('save_post', [$this, 'savePostData']);
		add_action('edit_attachment', [$this, 'savePostData']);
		add_action('add_attachment', [$this, 'savePostData']);
		add_action('edited_category', [$this, 'saveTaxonomy']);
		add_action('create_category', [$this, 'saveTaxonomy']);
		add_action('edited_post_tag', [$this, 'saveTaxonomy']);
		add_action('create_post_tag', [$this, 'saveTaxonomy']);
		
		/* Import */
		$Import = new Import();
		add_action('admin_post_sseo_allinone_import', [$Import, 'importAIOSEO']);
		add_action('admin_post_sseo_rankmath_import', [$Import, 'importRanked']);
		add_action('admin_post_sseo_yoast_import', [$Import, 'importYoast']);
		
		/* WooCommerce */
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			add_action('edited_product_cat', [$this, 'saveTaxonomy']);  
			add_action('create_product_cat', [$this, 'saveTaxonomy']);
			add_action('edited_product_tag', [$this, 'saveTaxonomy']);  
			add_action('create_product_tag', [$this, 'saveTaxonomy']);
		}

		/* Sitemap */		
		if (get_option('sseo_generate_sitemap') == true) {
			$sitemap = new Sitemap();
			add_action('publish_post', [$sitemap, 'buildSitemap']);
			add_action('edit_post', [$sitemap, 'buildSitemap']);
			add_action('delete_post', [$sitemap, 'buildSitemap']);
		}
		add_action('admin_post_sseo_create_sitemap', [$this, 'generateSiteMap']);
		add_action('admin_post_sseo_delete_sitemap', [$this, 'siteMapDelete']);
    }

	/**
	 *
	 * @since  2.0.0
	 */
    public function addMenu() {
		add_options_page(
			__('SEO Options', SSEO_TXTDOMAIN), 
			__('Simple SEO', SSEO_TXTDOMAIN), 
			'manage_options', 
			'simpleSEOAdminOptions', 
			[$this, 'simpleSEOAdminOptions']);
	}

	/**
	 *
	 * @since  2.0.0
	 */
    public function simpleSEOAdminOptions() {
		new Meta\Options();
	}

	/**
	 *
	 * @since  2.0.0
	 */
	public function savePostData($postId) {
		/* verify nonce */
		if (!isset($_POST['sseo_nonce']) || !wp_verify_nonce($_POST['sseo_nonce'], SSEO_PATH)) {
			return $postId;
		}

		if (wp_is_post_revision($postId)) {
			return $postId;
		}

		/* check autosave */
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $postId;
		}

		/* Check permissions */
		if ($_POST['post_type'] == 'page') {
			if (!current_user_can('edit_page', $postId)) {
				return $postId;
			}
		} elseif (!current_user_can('edit_post', $postId)) {
			return $postId;
		}

		Save::savePost($postId);
	}

	/**
	 * Saves taxonomy data
	 *
	 * @since  2.0.0
	 */
	public function saveTaxonomy($term) {
		Save::saveTaxonomy($term);
	}

	/**
	 * Delete the current sitemap
	 * 
	 * @since 2.0.14
	 */
	public function siteMapDelete() {	
		$nonce = null;
		if (!empty($_REQUEST['_wpnonce'])) {
			$nonce = $_REQUEST['_wpnonce'];
		}

		if (wp_verify_nonce($nonce) && current_user_can('administrator')) {
			@unlink(ABSPATH.'sitemap.xml');
			wp_redirect('/wp-admin/options-general.php?page=simpleSEOAdminOptions&sitemap_deleted=1');
		} else {
			die(__('Failed Security Check, No no no!', SSEO_TXTDOMAIN));
		}
	}

	/**
	 * Triggers the creation of a basic sitemap
	 * 
	 * @since 2.0.14
	 */
	public function generateSiteMap() {
		$nonce = null;
		if (!empty($_REQUEST['_wpnonce'])) {
			$nonce = $_REQUEST['_wpnonce'];
		}

		if (wp_verify_nonce($nonce) && current_user_can('administrator')) {
			if (get_option('sseo_generate_sitemap') == true) {
				$sitemap = new Sitemap();
				$sitemap->buildSitemap();
			}
			wp_redirect('/wp-admin/options-general.php?page=simpleSEOAdminOptions&sitemap_created=1');
		} else {
			die(__('Failed Security Check, No no no!', SSEO_TXTDOMAIN));
		}	
	}
}

?>