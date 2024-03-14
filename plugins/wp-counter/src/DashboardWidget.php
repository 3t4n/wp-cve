<?php
/**
 * Dashboard Widget Register
 *
 * @package Haruncpi\WpCounter
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

namespace Haruncpi\WpCounter;

/**
 * Dashboard Widget Class
 *
 * @since 1.2
 */
class DashboardWidget {
	/**
	 * Register hooks.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'visitor_status_widget' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'visitor_graph_widget' ) );
	}

	/**
	 * Visitor Status Widget Register
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function visitor_status_widget() {
		wp_add_dashboard_widget(
			'l24bd_wpcounter_dashboard_widget',
			'<span class="dashicons dashicons-chart-area"></span> Visitor Status',
			array( $this, 'display_status_widget' )
		);
	}

	/**
	 * Vistor graph widget register
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function visitor_graph_widget() {
		wp_add_dashboard_widget(
			'l24bd_wpcounter_dashboard_visitor_graph_widget',
			'<span class="dashicons dashicons-chart-area"></span> Last 7 Days Visitor',
			array( $this, 'display_visitor_graph_widget' )
		);
	}

	/**
	 * Visitor status widget display
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function display_status_widget() {
		Utils::load_view( 'widgets/status-widget.php' );
	}

	/**
	 * Visitor graph widget display
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function display_visitor_graph_widget() {
		Utils::load_view( 'widgets/visitor-graph-widget.php' );
	}
}
