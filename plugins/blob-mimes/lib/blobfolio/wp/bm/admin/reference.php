<?php
/**
 * Lord of the Files: Admin Page (Reference)
 *
 * This is a simple self-hosted reference page explaining the available
 * filters, etc.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm\admin;

final class reference extends \blobfolio\wp\bm\admin\page {
	/**
	 * Page Slug
	 */
	const SLUG = 'reference';

	/**
	 * Parent Menu
	 */
	const MENU = 'tools.php';

	/**
	 * Page Title
	 */
	const TITLE = 'File Validation Reference';

	/**
	 * Help
	 *
	 * @var ?array
	 */
	protected static $_help;



	/**
	 * Admin Page: Scripts
	 *
	 * This version can be overloaded by the child safe in the knowledge
	 * that all conditions have been met.
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_scripts() : void {
		\wp_enqueue_style(
			'lotf-prism-css',
			\LOTF_BASE_URL . '/assets/prism.css',
			array(),
			'1.18.0'
		);

		\wp_enqueue_script(
			'lotf-showdown-js',
			\LOTF_BASE_URL . '/assets/showdown.min.js',
			array(),
			'1.18.0',
			true
		);

		\wp_enqueue_script(
			'lotf-prism-js',
			\LOTF_BASE_URL . '/assets/prism.min.js',
			array(),
			'1.18.0',
			true
		);

		\wp_enqueue_script(
			'lotf-reference-js',
			\LOTF_BASE_URL . '/assets/reference.min.js',
			array('lotf-prism-js', 'lotf-showdown-js'),
			'LOTF1',
			true
		);
	}

	/**
	 * Get Help
	 *
	 * @return array Help.
	 */
	public static function help() : array {
		if (null === self::$_help) {
			self::$_help = array(
				'main'=>array(
					'title'=>\esc_html__('Developer Reference', 'blob-mimes'),
					'content'=>\trim(\file_get_contents(\LOTF_BASE_PATH . '/help/README.md')),
				),
				'type_filters'=>array(
					'title'=>\esc_html__('MIME Type Filters', 'blob-mimes'),
					'content'=>\trim(\file_get_contents(\LOTF_BASE_PATH . '/help/TYPE_FILTERS.md')),
				),
				'svg_filters'=>array(
					'title'=>\esc_html__('SVG Filters', 'blob-mimes'),
					'content'=>\trim(\file_get_contents(\LOTF_BASE_PATH . '/help/SVG_FILTERS.md')),
				),
				'deprecated'=>array(
					'title'=>\esc_html__('Deprecated Filters', 'blob-mimes'),
					'content'=>\trim(\file_get_contents(\LOTF_BASE_PATH . '/help/DEPRECATED.md')),
				),
			);
		}

		return self::$_help;
	}
}
