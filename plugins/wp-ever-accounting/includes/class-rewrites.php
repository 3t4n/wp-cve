<?php
/**
 * EverAccounting  Rewrites Event Handlers.
 *
 * @since       1.1.0
 * @package     EverAccounting
 * @class       Rewrites
 */

namespace EverAccounting;

defined( 'ABSPATH' ) || exit();

/**
 * Rewrites Handlers.
 *
 * @since       1.1.0
 * @package     EverAccounting
 * @class       Rewrites
 */
class Rewrites {

	/**
	 * EverAccounting_Rewrites constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_rewrite_rules' ) );
		add_filter( 'query_vars', array( $this, 'register_query_var' ) );
		add_action( 'template_redirect', array( $this, 'rewrite_templates' ) );
	}

	/**
	 * Add the required rewrite rules
	 *
	 * @return void
	 */
	public function add_rewrite_rules() {
		$eaccounting_slug = eaccounting_get_parmalink_base();
		add_rewrite_rule( '^' . $eaccounting_slug . '/invoice/([0-9]{1,})/(.*)?/?$', 'index.php?eaccounting=true&ea_page=invoice&id=$matches[1]&key=$matches[2]', 'top' );
		add_rewrite_rule( '^' . $eaccounting_slug . '/bill/([0-9]{1,})/(.*)?/?$', 'index.php?eaccounting=true&ea_page=bill&id=$matches[1]&key=$matches[2]', 'top' );
	}

	/**
	 * Register our query vars
	 *
	 * @param array $vars The query vars.
	 *
	 * @return array
	 */
	public function register_query_var( $vars ) {
		$vars[] = 'eaccounting';
		$vars[] = 'ea_page';
		$vars[] = 'id';
		$vars[] = 'key';

		return $vars;
	}

	/**
	 * Load our template on our rewrite rule
	 *
	 * @return void
	 */
	public function rewrite_templates() {
		if ( 'true' === get_query_var( 'eaccounting' ) ) {
			eaccounting_get_template( 'eaccounting.php' );
			exit();
		}
	}

}

new Rewrites();
