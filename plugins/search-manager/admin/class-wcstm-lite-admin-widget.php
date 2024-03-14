<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class WCSTM_Lite_Admin_Widget {

	protected $terms;

	public function __construct() {

		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

	}

	public function add_dashboard_widgets() {

		wp_add_dashboard_widget( 'dashboard_recent_search_widget', 'Recent Search Terms', array(
			$this,
			'do_recent_search'
		) );

		wp_add_dashboard_widget( 'dashboard_top_search_widget', 'Unsuccessful Search Terms', array(
			$this,
			'do_unsuccessful_search'
		) );

	}

	public function do_recent_search() {

		$terms = get_option( 'wcstm_lite_terms_recent' );
		$this->get_recent_widget( $terms );

	}

	public function do_unsuccessful_search() {

		$terms = get_option( 'wcstm_lite_terms_unsuccessful' );
		$this->get_unsuccessful_widget( $terms );

	}

	protected function get_recent_widget( $terms ) {

		if ( ! empty( $terms ) ) {
			?>
			<table class="widefat fixed">
				<thead>
					<tr>
						<th><?php _e( 'Search Term', 'wcstm_lite' ) ?></th>
						<th><?php _e( 'Results', 'wcstm_lite' ) ?></th>
						<th><?php _e( 'Date', 'wcstm_lite' ) ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $terms as $term ) { ?>
						<tr>
							<td><?php echo $term['term'] ?></td>
							<td><?php echo $term['results'] ?></td>
							<td><?php echo $term['date'] ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php
		} else {
			?>
			<p><?php _e( 'No data yet available', 'wcstm_lite' ) ?></p>
			<?php
		}

	}

	protected function get_unsuccessful_widget( $terms ) {

		if ( ! empty( $terms ) ) {
			?>
			<table class="widefat fixed">
				<thead>
					<tr>
						<th><?php _e( 'Search Term', 'wcstm_lite' ) ?></th>
						<th><?php _e( 'Results', 'wcstm_lite' ) ?></th>
						<th><?php _e( 'Date', 'wcstm_lite' ) ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $terms as $term ) { ?>
						<tr>
							<td><?php echo $term['term'] ?></td>
							<td><?php echo $term['results'] ?></td>
							<td><?php echo $term['date'] ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<p><a href="https://codecanyon.net/item/search-manager-plugin-for-woocommerce-and-wordpress/15589890?ref=teamdev-ltd"><strong>Go premium</strong></a> and fix unsuccessful searches.</p>
			<?php
		} else {
			?>
			<p><?php _e( 'No data yet available', 'wcstm_lite' ) ?></p>
			<?php
		}

	}

}

new WCSTM_Lite_Admin_Widget();