<?php
/**
 * The Template for displaying a report parameters
 *
 * This template can be overridden by copying it to yourtheme/upstream/report-parameters.php.
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __FILE__ ) . '/../includes/admin/metaboxes/metabox-functions.php';

if ( function_exists( 'set_time_limit' ) ) {
	set_time_limit( 120 );
}

$exception   = null;
$get_data    = isset( $_GET ) ? wp_unslash( $_GET ) : array();
$server_data = isset( $_SERVER ) ? wp_unslash( $_SERVER ) : array();

try {
	if ( ! session_id() ) {
		session_start();
	}
} catch ( \Exception $e ) {
	$exception = $e;
}

add_action(
	'init',
	function() {
		try {
			if ( ! session_id() ) {
				session_start();
			}
		} catch ( \Exception $e ) {
			$exception = $e;
		}
	},
	9
);


if ( ! apply_filters( 'upstream_theme_override_header', false ) ) {
	upstream_get_template_part( 'global/header.php' );
}

if ( ! apply_filters( 'upstream_theme_override_sidebar', false ) ) {
	upstream_get_template_part( 'global/sidebar.php' );
}

if ( ! apply_filters( 'upstream_theme_override_topnav', false ) ) {
	upstream_get_template_part( 'global/top-nav.php' );
}

$report = UpStream_Report_Generator::get_instance()->get_report( sanitize_text_field( $get_data['report'] ) );
if ( ! $report ) {
	return;
}

$display_fields = array();

?>

	<div class="right_col" role="main">

		<form action="<?php esc_url( $server_data['REQUEST_URI'] ); ?>" method="post">
			<?php wp_nonce_field( 'upstream_report_form', 'upstream_report_form_nonce' ); ?>

			<?php foreach ( $report->getAllFieldOptions() as $section_id => $option_info ) : ?>
				<div id="report-parameters-<?php echo esc_attr( $option_info['type'] ); ?>>">
					<?php include 'report-parameters/section.php'; ?>
				</div>
			<?php endforeach; ?>

			<?php require 'report-parameters/display-fields.php'; ?>

			<div class="col-md-12 mt-2">
				<input type="submit" name="submit" value="<?php esc_html_e( 'Submit Filters', 'upstream' ); ?>" class="btn btn-primary">
			</div>
		</form>
	</div>

<?php


if ( ! apply_filters( 'upstream_theme_override_footer', false ) ) {
	upstream_get_template_part( 'global/footer.php' );
}
?>
