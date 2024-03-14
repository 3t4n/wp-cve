<tr>
	<?php $all_login_records_nonce = wp_create_nonce( 'wll_all_login_records_nonce' ); ?>
	<th><?php esc_html_e( 'Export All Login Records', 'when-last-login-export-user-records' ); ?></th>
	<td>
	
	<a href='<?php echo admin_url( 'admin.php?page=when-last-login-settings&tab=export-user-records' ) . '&export=login-records&type=csv&nonce=' . $all_login_records_nonce; ?>' target='_BLANK' class='button button-primary'><?php esc_html_e( 'Export to CSV', 'when-last-login-export-user-records' ); ?></a> 
	
	<a href='<?php echo admin_url( 'admin.php?page=when-last-login-settings&tab=export-user-records' ) . '&export=login-records&type=json&nonce=' . $all_login_records_nonce; ?>' target='_BLANK' class='button button-primary'><?php esc_html_e( 'Export to JSON', 'when-last-login-export-user-records' ); ?></a>
</td>
</tr>
<tr>
	<?php $all_user_records_nonce = wp_create_nonce( 'wll_all_user_records_nonce' ); ?>
	<th><?php esc_html_e( 'Export All User Records', 'when-last-login-export-user-records' ); ?></th>
	<td>
	<a href='<?php echo admin_url( 'admin.php?page=when-last-login-settings&tab=export-user-records' ) . '&export=user-records&type=csv&user_nonce=' . $all_user_records_nonce; ?>' target='_BLANK' class='button button-primary'><?php esc_html_e( 'Export to CSV', 'when-last-login-export-user-records' ); ?></a> 
	
	<a href='<?php echo admin_url( 'admin.php?page=when-last-login-settings&tab=export-user-records' ) . '&export=user-records&type=json&user_nonce=' . $all_user_records_nonce; ?>' target='_BLANK' class='button button-primary'><?php esc_html_e( 'Export to JSON', 'when-last-login-export-user-records' ); ?></a></td>
</tr>

<?php if ( ! class_exists( 'WhenLastLoginStatistics' ) ) { ?>
	<tr>
		<?php 
			$wll_svg = plugins_url( 'images/wll-star.svg', __FILE__ );
			$wll_svg = '<img style="vertical-align: middle; display: inline-block;" src="' . esc_url( $wll_svg ) . '" alt="When Last Login User Statistics" width="20" height="20" />';
		?>
		<td colspan="2"><?php echo $wll_svg; ?> <span style="vertical-align: middle; display: inline-block;"><?php echo sprintf( 'If you need more advanced reporting, check out the %s', '<a href="https://yoohooplugins.com/plugins/when-last-login-user-statistics/?utm_source=export_records_plugin" target="_BLANK"><strong>' . esc_html__( 'When Last Login - User Statistics plugin' ) .  '</strong></a>' ); ?></span></td>
	</tr>
<?php } ?>

