<?php do_action( 'autoship_before_autoship_admin_coupons' ); ?>

	<iframe src="<?php echo esc_attr( autoship_get_merchants_url() ); ?>/widgets/dashboard/<?php echo urlencode( $site_id ); ?>/coupons?tokenBearerAuth=<?php echo urlencode( $token_auth ); ?>"
	        class="autoship-admin-coupons-iframe autoship-admin-dashboard-iframe" frameborder="0"></iframe>

<?php do_action( 'autoship_after_autoship_admin_coupons' ); ?>