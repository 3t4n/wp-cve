<?php
$log_404_send_report = isset( $settings['log_404']['send_report'] ) ? sanitize_text_field( $settings['log_404']['send_report'] ) : 'monthly';
$log_404_report_email = isset( $settings['log_404']['report_email'] ) ? sanitize_email( $settings['log_404']['report_email'] ) : '';
$logs_404 = \Upress\Booter\Log404::get_logs();
?>

<p class="notice notice-info">
	<?php esc_html_e( 'Logging 404 errors allows you to detect bad or spam URLs search engines trying to crawl.', 'booter' ); ?><br>
</p>

<table class="form-table">
    <tr valign="top">
        <th scope="row"><label for="booter-log_404-send_report"><?php esc_html_e( 'Receive 404 Errors Report', 'booter' ); ?></label></th>
        <td>
            <radio-toggle id="booter-log_404-send_report"
                          name="booter_settings[log_404][send_report]"
                          value="<?php echo $log_404_send_report === 'yes' ? 'daily' : $log_404_send_report; ?>"
                          options='<?php echo json_encode( [ 'no' => __( 'Off', 'booter' ), 'daily' => __( 'Daily', 'booter' ), 'weekly' => __( 'Weekly', 'booter' ), 'monthly' => __( 'Monthly', 'booter' ) ] ); ?>'
                          data-toggle-on=".js-booter-404-report-email"
                          data-off-value="off"
            ></radio-toggle>
            <p class="description">
		        <?php printf(
			        esc_html__( 'Receive a report for 404 errors. By default we will send the report to the admin email address %s.', 'booter' ),
			        sprintf( '<code>%s</code>', get_option( 'admin_email' ) )
		        ); ?>
            </p>


        </td>
    </tr>
    <tr valign="top" class="js-booter-404-report-email">
        <th scope="row"><label for="booter-log_404-report_email"><?php esc_html_e( 'Email Address', 'booter' ); ?></label></th>
        <td>
            <input id="booter-log_404-report_email" type="email" class="regular-text" name="booter_settings[log_404][report_email]" placeholder="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" value="<?php echo esc_attr( $log_404_report_email ); ?>">
        </td>
    </tr>

    <tr valign="top">
        <td colspan="2">
            <button type="submit" class="button" name="clear_404_log">
                <span class="dashicons dashicons-trash" aria-hidden="true"></span>
                <?php esc_html_e( 'Clear Log', 'booter' ); ?>
            </button>
        </td>
    </tr>
</table>

<table class="widefat" v-pre>
    <thead>
        <tr>
            <th><?php esc_html_e( 'URL', 'booter' ); ?></th>
            <th><?php esc_html_e( 'Hits', 'booter' ); ?></th>
            <th><?php esc_html_e( 'First Seen', 'booter' ); ?></th>
            <th><?php esc_html_e( 'Last Hit', 'booter' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if ( count( $logs_404 ) ) : ?>
            <?php foreach ( $logs_404 as $index => $log ) : ?>
                <tr class="<?php echo $index % 2 == 0 ? 'alternate' : ''; ?>">
                    <td><?php echo esc_html( $log->url ); ?></td>
                    <td><?php echo intval( $log->hits ); ?></td>
                    <td><?php printf( esc_html_x( '%s ago', '%s = human-readable time difference', 'booter' ), human_time_diff( time(), strtotime( $log->created_at ) ) ); ?></td>
                    <td><?php printf( esc_html_x( '%s ago', '%s = human-readable time difference', 'booter' ), human_time_diff( time(), strtotime( $log->updated_at ) ) ); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="42"><?php esc_html_e( 'No 404 Logs', 'booter' ); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php submit_button(); ?>
