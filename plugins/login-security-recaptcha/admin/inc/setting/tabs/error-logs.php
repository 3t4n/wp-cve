<?php
defined( 'ABSPATH' ) || die();
require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
$error_logs = STLSR_Logger::get_error_logs();

if ( count( $error_logs ) ) {
	?>
	<div class="stlsr-tab-header">
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="stlsr-clear-error-logs-form">

			<div class="stlsr-tab-heading"><?php esc_html_e( 'Last 20 Error Logs', 'login-security-recaptcha' ); ?></div>

			<?php $nonce = wp_create_nonce( 'clear-error-logs' ); ?>
			<input type="hidden" name="clear-error-logs" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="stlsr-clear-error-logs">

			<button type="submit" class="button button-primary stlsr-tab-heading-btn" id="stlsr-clear-error-logs-btn" data-message="<?php esc_attr_e( 'Are you sure to clear the error logs?', 'login-security-recaptcha' ); ?>"><?php esc_html_e( 'Clear Error Logs', 'login-security-recaptcha' ); ?></button>

		</form>
	</div>

	<div class="stlsr-error-logs-section">
		<table class="stlsr-error-logs">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Form', 'login-security-recaptcha' ); ?></th>
					<th><?php esc_html_e( 'Captcha', 'login-security-recaptcha' ); ?></th>
					<th><?php esc_html_e( 'Error Code', 'login-security-recaptcha' ); ?></th>
					<th><?php esc_html_e( 'IP Address', 'login-security-recaptcha' ); ?></th>
					<th><?php esc_html_e( 'Timestamp', 'login-security-recaptcha' ); ?></th>
				</tr>
			</thead>
			<tbody class="stlsr-error-logs-body">
				<?php foreach ( $error_logs as $error_log ) { ?>
				<tr>
					<td><span class="stlsr-form"><?php echo isset( $error_log['form'] ) ? esc_html( $error_log['form'] ) : '-'; ?></span></td>
					<td><span class="stlsr-captcha"><?php echo isset( $error_log['captcha'] ) ? esc_html( $error_log['captcha'] ) : '-'; ?></span></td>
					<td><span class="stlsr-msg"><?php echo isset( $error_log['msg'] ) ? esc_html( $error_log['msg'] ) : '-'; ?></span></td>
					<td><span class="stlsr-ip"><?php echo isset( $error_log['ip'] ) ? esc_html( $error_log['ip'] ) : '-'; ?></span></td>
					<td><span class="stlsr-time"><?php echo isset( $error_log['time'] ) ? esc_html( STLSR_Helper::wp_date( 'Y-m-d H:i:s', $error_log['time'] ) ) : '-'; ?></span></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<p><em><?php esc_html_e( 'Note: If you use Google reCAPTCHA v3 for a period of time and in the future, it keeps on returning missing-input-response or 0.1 scores. Then, you will probably need to re-create reCAPTCHA v3 keys.', 'login-security-recaptcha' ); ?></em></p>
	<?php
} else {
	?>
	<p><?php esc_html_e( 'There was no error.', 'login-security-recaptcha' ); ?></p>
	<?php
}
