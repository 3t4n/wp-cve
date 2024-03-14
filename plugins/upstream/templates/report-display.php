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

/* Some hosts disable this function, so let's make sure it is enabled before call it. */
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

$display_options = $report->getDisplayOptions();

?>

<script type="text/javascript">

jQuery(document).ready(function ($) {
	/* Load the Visualization API and the piechart package. */
	google.charts.load('current', {'packages': ['corechart', 'table', 'gantt', 'calendar']});

	/* Set a callback to run when the Google Visualization API is loaded. */
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {

		data = <?php echo json_encode( UpStream_Report_Generator::get_instance()->get_report_fields_from_post( false ) ); ?>;
		data['report'] = '<?php echo esc_attr( $report->id ); ?>';
		data['action'] = 'upstream_report_data';
		data['nonce'] = upstream.security;

		var jsonData = $.ajax({
			url: upstream.ajaxurl,
			type: 'post',
			dataType: "json",
			async: false,
			data: data
		}).responseText;

		/* Create our data table out of JSON data loaded from server. */
		var data = new google.visualization.DataTable(jsonData);
		var jo = JSON.parse(jsonData);
		var options = {};

		if ('options' in jo) {
			options = jo['options'];
		}

		if (jo['rows'].length == 0) {
			jQuery('#table_div').html('Report query returned no results.');
		}
		else {
			options.width = '100%';
			options.height = 500;
			options.allowHtml = true;

			<?php if ( 'Gantt' == $display_options['visualization_type'] || 'BarChart' == $display_options['visualization_type'] ) : ?>
			options.height = data.getNumberOfRows() * 45 + 50;
			<?php elseif ( 'Table' == $display_options['visualization_type'] || 'Calendar' == $display_options['visualization_type'] ) : ?>
			delete options.height;
			<?php endif; ?>

			/* Instantiate and draw our chart, passing in some options. */
			var chart = new google.visualization.<?php echo esc_js( $display_options['visualization_type'] ); ?>(document.getElementById('table_div'));
			chart.draw(data, options);

			$('body').on('click', '#export_csv', function () {
				var csvFormattedDataTable = google.visualization.dataTableToCsv(data);
				var encodedUri = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csvFormattedDataTable);
				this.href = encodedUri;
				this.download = 'table-data.csv';
				this.target = '_blank';
			});

			$('#print').click(function () {
				window.print();
			});

			$('#export_div').css('display', 'block');
		}
	}

});
</script>

<div class="right_col clearfix" role="main">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel" data-section="report-display">
			<div class="x_title">
				<h2>
					<?php echo esc_html( $report->title ); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div id="table_div"></div>                
			</div>
			<div id="export_div">
				<a id="export_csv" class="btn btn-success mt-3"><?php esc_html_e( 'Export CSV', 'upstream' ); ?></a>
				<a id="print" class="btn btn-primary mt-3"><?php esc_html_e( 'Print or Save to PDF', 'upstream' ); ?></a>
			</div>
		</div>
	</div>
</div>
<?php


if ( ! apply_filters( 'upstream_theme_override_footer', false ) ) {
	upstream_get_template_part( 'global/footer.php' );
}
?>
