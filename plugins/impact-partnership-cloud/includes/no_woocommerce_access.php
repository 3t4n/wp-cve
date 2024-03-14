<?php
	$user         = wp_get_current_user();
	$params       = array(
		'app_name'     => 'Impact',
		'scope'        => 'read_write',
		'user_id'      => $user->user_login,
		'return_url'   => home_url() . '/wp-admin/admin.php?page=impact-settings',
		'callback_url' => home_url() . '/wp-json/impact/v1/callback',
	);
	$store_url    = home_url();
	$query_string = http_build_query( $params );
	$endpoint     = '/wc-auth/v1/authorize';
	$url          = $store_url . $endpoint . '?' . $query_string;
	?>

<div class="wrap">
	<div class="row">
		<div class="col-md-6">
			<h3>No Woocommerce API access</h3>
			<p>
				It seems that the Impact Cloud Partnership plugin doesn't have access to the woocommerce data.
				Please click <a href="<?php echo esc_attr( $url ); ?>">here</a> and approve access for the plugin to work correctly.
			</p>
		</div>
	</div>
</div>
