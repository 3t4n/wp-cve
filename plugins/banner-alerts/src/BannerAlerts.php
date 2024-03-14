<?php
namespace Plugin\BannerAlerts;

use WP_Query;

class BannerAlerts
{
	private $pluginPath;

	private $defaultDisplayOptions = array(
		'display-title' => '1',
		'display-readmore' => '0',
		'display-mode' => '1',
		'display-dismiss' => '1',
		'display-styles' => ".banner-alerts {\n    max-width: 1100px; margin: 0 auto; padding: 5px;\n}\n",
		'display-speed' => '400',
		'use-slider' => '1',
	);

	function __construct($pluginPath)
	{
		$this->pluginPath = $pluginPath;

		add_action('init', array($this, 'onInit'));

		add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

		if (is_admin()) {
			require_once __DIR__ . '/Admin/AdminPages.php';
			$adminPages = new AdminPages($pluginPath);
		}
	}

	function onActivate($network_wide) {
		if ($network_wide && is_multisite()) {
			$this->purgeMultisiteRewriteRules();
		} else {
			$this->registerPostTypes();
			flush_rewrite_rules(false);
		}
	}

	function onDeactivate($network_wide) {
		if ($network_wide && is_multisite()) {
			$this->purgeMultisiteRewriteRules();
		} else {
			flush_rewrite_rules(false);
		}
	}

	function onInit() {
		if (isset($_GET['action'], $_GET['plugin']) && 'deactivate' == $_GET['action'] && plugin_basename($this->pluginPath) == $_GET['plugin'])
			return;

		add_action('wp_ajax_get_banner_alerts', array($this, 'ajaxGetAfter'));
		add_action('wp_ajax_nopriv_get_banner_alerts', array($this, 'ajaxGetAfter'));

		$this->registerPostTypes();
	}

	function purgeMultisiteRewriteRules() {
		if (function_exists('get_sites')) {
			$site_ids = get_sites(array(
				'fields' => 'ids',
			));

			if ($site_ids) {
				foreach ($site_ids as $site_id) {
					switch_to_blog($site_id);
					delete_option('rewrite_rules');
					restore_current_blog();
				}
			}
		}
	}

	function registerPostTypes ()
	{
		register_post_type('banner_alert', array(
			'label' => __('Alerts'),
			'labels' => array(
			    'name'               => 'Alerts',
			    'singular_name'      => 'Alert',
			    'add_new'            => 'Add New',
			    'add_new_item'       => 'Add New Alert',
			    'edit_item'          => 'Edit Alert',
			    'new_item'           => 'New Alert',
			    'all_items'          => 'All Alerts',
			    'view_item'          => 'View Alert',
			    'search_items'       => 'Search Alerts',
			    'not_found'          => 'No alerts found',
			    'not_found_in_trash' => 'No alerts found in Trash',
			    'parent_item_colon'  => '',
			    'menu_name'          => 'Alerts'
			),

			'public' => true,
			'publicly_queryable' => true,
			'hierarchical' => false,
			'has_archive' => false,
			'rewrite' => array(
				'slug' => 'alerts',
				'with_front' => false
			),

			'menu_icon' => 'dashicons-format-status',

			'supports' => array('title', 'editor', 'author', 'excerpt')
		));
	}

	function enqueueScripts ()
	{
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script('banner-alerts', $this->assetUrl('/js/banner-alerts' . $suffix . '.js'), array('jquery'));

		wp_localize_script('banner-alerts', 'banner_alerts_vars', array(
			'dismissText' => __('Dismiss', 'banner-alerts'),
			'readMoreText' => __('Read More', 'banner-alerts'),
			'ajaxurl' => admin_url('admin-ajax.php')
		));
	}

	function ajaxGetAfter ()
	{
		$date = (isset($_POST['timestamp']) && (string)(int)$_POST['timestamp'] === $_POST['timestamp']) ? (int)$_POST['timestamp'] : null;
		$output = array();

		if (is_null($date)) {
			$output = array(
				'message' => __('A valid unix timestamp is required.', 'banner-alerts')
			);

			wp_send_json($output, 400);
			die();
		}

		$formatted_date = date('Y-m-d H:i:s', $date);

		$alerts = new WP_Query(array(
			'post_type' => 'banner_alert',
			'post_status' => 'publish',
			
			'order' => 'DESC',
			'orderby' => 'modified',

			'posts_per_page' => -1,

			'date_query' => array(
				'inclusive' => false,
				array(
					'column' => 'post_modified_gmt',
					'after' => $formatted_date
				)
			)
		));

		$alerts = $alerts->get_posts();

		$displayOptions = get_option('options-general_banner-alerts_display', array());
		$displayOptions = wp_parse_args($displayOptions, $this->defaultDisplayOptions);
		
		$output = array(
			'alerts' => $this->pluckFields($alerts, array(
				'id' => 'ID',
				'title' => 'post_title',
				'content' => function ($object) { return do_shortcode($object->post_content); },
				'excerpt' => 'post_excerpt',
				'date_modified_gmt' => function ($object) { return strtotime($object->post_modified_gmt); },
				'permalink' => function ($object) { return get_permalink($object->ID); },
			)),
			'options' => $displayOptions,
		);

		wp_send_json($output);
		die();
	}

	protected function pluckFields ($objects, $fields)
	{
		$output = array();

		foreach ($objects as $object)
		{
			$row = array();
			foreach ($fields as $name => $field)
			{
				if ($field instanceof \Closure)
					$row[$name] = $field((object)$object);
				else if (is_object($object) && isset($object->$field))
					$row[$name] = $object->$field;
				else if (is_array($object) && isset($object[$field]))
					$row[$name] = $object[$field];
			}
			$output[] = $row;
		}

		return $output;
	}

	protected function assetUrl ($assetPath)
	{
		return plugins_url('ui/' . ltrim($assetPath, '/'), plugin_basename($this->pluginPath));
	}
}
