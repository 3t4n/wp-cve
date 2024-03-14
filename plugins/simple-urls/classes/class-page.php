<?php
/**
 * Declare class Page
 *
 * @package Page
 */

namespace LassoLite\Classes;

use LassoLite\Admin\Constant;

use LassoLite\Classes\Helper;

/**
 * Page
 */
class Page {

	/**
	 * Title
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Slug
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Template
	 *
	 * @var string
	 */
	public $template;

	/**
	 * Active css class
	 *
	 * @var string
	 */
	public $active_class;

	/**
	 * Page constructor.
	 *
	 * @param string $title    Title.
	 * @param string $slug     Slug.
	 * @param string $template Templage.
	 */
	public function __construct( $title, $slug, $template ) {
		$page = Helper::GET()['page'] ?? ''; // phpcs:ignore

		$this->title        = $title;
		$this->slug         = SIMPLE_URLS_SLUG . '-' . $slug;
		$this->template     = $template;
		$this->active_class = $this->slug === $page ? 'active' : '';
	}

	/**
	 * Get Lasso Lite page URL
	 *
	 * @param string $slug Page slug.
	 */
	public static function get_page_url( $slug = '' ) {
		if ( empty( $slug ) ) {
			return admin_url( 'edit.php?post_type=' . SIMPLE_URLS_SLUG );
		}

		$page_url = add_query_arg(
			array(
				'post_type' => Constant::LASSO_POST_TYPE,
				'page'      => $slug,
			),
			self_admin_url( 'edit.php' )
		);

		return $page_url;
	}

	/**
	 * Get Lasso Lite page URL without prefix
	 *
	 * @param string $slug Page slug.
	 */
	public static function get_lite_page_url( $slug = '' ) {
		if ( empty( $slug ) ) {
			return admin_url( 'edit.php?post_type=' . SIMPLE_URLS_SLUG );
		}

		$slug = Helper::add_prefix_page( $slug );

		return self::get_page_url( $slug );
	}
}
