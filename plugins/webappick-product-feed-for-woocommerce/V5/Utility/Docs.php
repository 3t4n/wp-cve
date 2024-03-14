<?php

namespace CTXFeed\V5\Utility;

/**
 * Class Docs
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Utility
 * @author     Nashir Uddin <nashirbabu@gmail.com>
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class Docs
{
	/**
	 * Singleton instance holder
	 *
	 * @var Docs
	 */
	private static $instance;

	/**
	 * Get Class Instance
	 *
	 * @return Docs
	 */
	public static function getInstance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		add_filter('removable_query_args', array($this, 'filter_removable_query_args'), 10, 1);
	}

	/**
	 * Render Docs Page
	 *
	 * @return array
	 * @see Woo_Feed_Admin::load_admin_pages()
	 */
	public function woo_feed_docs()
	{
		$result = [];
		$faqs = $this->__get_feed_help();
		$icons = array(
			'Getting_Started' => 'dashicons dashicons-sos',
			'FAQs' => 'dashicons dashicons-editor-help',
			'Feed_Configuration' => 'dashicons dashicons-admin-generic',
			'Filter_Products' => 'dashicons dashicons-filter',
			'Channels' => 'dashicons dashicons-networking',
			'Google_&_Facebook' => 'dashicons dashicons-rss',
			'Installation' => 'dashicons dashicons-plugins-checked',
			'Dynamic_Attributes' => 'dashicons dashicons-image-filter',
		);
		foreach ( $faqs as $faq ) {
			$_icon = str_replace('#038;', '', $faq->title->rendered);
			$icon = str_replace(' ', '_', $_icon);
			if ( !isset($faq->icon ) ) $faq->icon = isset( $icons[$icon] ) ? $icons[$icon] : 'dashicons-admin-generic';

			$result[$faq->id]['title'] =html_entity_decode($faq->title->rendered, ENT_QUOTES, 'UTF-8');
			$result[$faq->id]['icon'] = $icons[$icon];
			$result[$faq->id]['id'] = $faq->id;

			$faq_response = wp_remote_get('https://webappick.com/wp-json/wp/v2/docs/?per_page=60&parent=' . $faq->id . '&_fields=parent,title,link,id,doc_tag');
			$question_lists = json_decode(wp_remote_retrieve_body($faq_response));

			if( is_array($question_lists) && count( $question_lists )> 0 ) {
				foreach ($question_lists as $qa) {
					if (!isset($qa->icon)) $qa->icon = 'dashicons-media-text';
					$doc_url = add_query_arg(array(
						'utm_source' => 'freePlugin',
						'utm_medium' => 'free_plugin_doc',
						'utm_campaign' => 'free_to_pro',
						'utm_term' => 'wooFeed',
					), $qa->link);
					$result[$faq->id]['docList'][] = array(
						'item' => html_entity_decode($qa->title->rendered, ENT_QUOTES, 'UTF-8'),
						'link' => $doc_url,
					);
				}
			}
		}

		return array_values( $result );
	}

	/**
	 * Get Docs Data
	 *
	 * @return array
	 */
	private function __get_feed_help() {
		// force fetch docs json.
		if (isset($_GET['reload'], $_GET['_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_nonce'])), 'webappick-feed-docs')) {
			$help_docs = false;
		} else {
			$help_docs = get_transient('webappick_feed_help_docs');
		}
		if (false === $help_docs) {
			// bitbucket cache-control: max-age=900 (15 minutes)
			$help_url = 'https://webappick.com/wp-json/wp/v2/docs/?parent=3946&_fields=parent,title,link,id&order=asc';
			$response = wp_safe_remote_get($help_url, array('timeout' => 15)); // phpcs:ignore
			$help_docs = wp_remote_retrieve_body($response);
			if ( is_wp_error($response) || 200 != $response['response']['code'] ) {
				$help_docs = '[]';
			}
			set_transient('webappick_feed_help_docs', $help_docs, 12 * HOUR_IN_SECONDS);
		}
		$help_docs = json_decode( trim( $help_docs ) );

		return $help_docs;
	}

	/**
	 * Add items to removable query args array
	 *
	 * @param array $removable_query_args
	 *
	 * @return array
	 */
	public function filter_removable_query_args( $removable_query_args ) {
		global $pagenow, $plugin_page;
		if ( 'admin.php' === $pagenow && 'webappick-feed-docs' === $plugin_page ) {
			$removable_query_args = array_merge( $removable_query_args, array( 'reload', '_nonce' ) );
		}

		return $removable_query_args;
	}
}
