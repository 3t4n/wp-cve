<?php

namespace FSPoster\App\Pages\Logs\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;

function wp_enqueue_logs_css()
{
	wp_register_style( 'fsp-logs', Pages::asset( 'Logs', 'css/fsp-logs.css' ) );
	wp_enqueue_style( 'fsp-logs' );
}

add_action( 'admin_print_styles', 'FSPoster\App\Pages\Logs\Views\wp_enqueue_logs_css' );
do_action( 'admin_print_styles' );

function wp_enqueue_logs_js()
{
	wp_register_script( 'fsp-logs', Pages::asset( 'Logs', 'js/fsp-logs.js' ) );
	wp_enqueue_script( 'fsp-logs' );
}

add_action( 'admin_print_scripts', 'FSPoster\App\Pages\Logs\Views\wp_enqueue_logs_js' );
do_action( 'admin_print_scripts' );
?>
<div class="fsp-row">
	<div class="fsp-col-12 fsp-title fsp-logs-title">
		<div class="fsp-title-text">
			<?php echo esc_html__( 'Logs', 'fs-poster' ); ?>
			<span id="fspLogsCount" class="fsp-title-count">0</span>
		</div>
		<div class="fsp-title-button">
			<div class="fsp-title-selector">
				<label><?php echo esc_html__( 'Filter results', 'fs-poster' ); ?></label>
				<select id="fspFilterSelector" class="fsp-form-select">
					<option value="all" <?php echo( $fsp_params[ 'filter_by' ] === 'all' ? 'selected' : '' ); ?>><?php echo esc_html__( 'all', 'fs-poster' ); ?></option>
					<option value="ok" <?php echo( $fsp_params[ 'filter_by' ] === 'ok' ? 'selected' : '' ); ?>><?php echo esc_html__( 'success', 'fs-poster' ); ?></option>
					<option value="error" <?php echo( $fsp_params[ 'filter_by' ] === 'error' ? 'selected' : '' ); ?>><?php echo esc_html__( 'error', 'fs-poster' ); ?></option>
				</select>
			</div>
			<div class="fsp-title-selector">
				<label><?php echo esc_html__( 'Count of rows', 'fs-poster' ); ?></label>
				<select id="fspRowsSelector" class="fsp-form-select">
					<option <?php echo Helper::getOption( 'logs_rows_count_' . get_current_user_id(), '4', TRUE ) === '4' ? 'selected' : ''; ?>>4</option>
					<option <?php echo Helper::getOption( 'logs_rows_count_' . get_current_user_id(), '4', TRUE ) === '8' ? 'selected' : ''; ?>>8</option>
					<option <?php echo Helper::getOption( 'logs_rows_count_' . get_current_user_id(), '4', TRUE ) === '15' ? 'selected' : ''; ?>>15</option>
				</select>
			</div>
			<button id="fspClearLogs" class="fsp-button fsp-is-danger">
				<i class="far fa-trash-alt"></i><span class="fsp-show"><?php echo esc_html__( 'CLEAR LOGS', 'fs-poster' ); ?></span>
			</button>
		</div>
	</div>
	<div id="fspLogs" class="fsp-col-12">
		<div id="fspLogs"></div>
	</div>
	<div id="fspLogsPages" class="fsp-col-12 fsp-logs-pagination"></div>
</div>

<script>
	FSPObject.page = <?php echo esc_html( $fsp_params[ 'logs_page' ] ); ?>;
</script>
