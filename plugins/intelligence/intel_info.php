<?php

/**
 */

/** WordPress Administration Bootstrap */
$dirname = dirname( __FILE__ );
$a = explode('/', $dirname);
while(count($a)) {
	array_pop ($a);
	$root_dirname = implode('/', $a);
	if (file_exists ( $root_dirname . '/wp-admin/admin.php')) {
		require_once(  $root_dirname . '/wp-admin/admin.php' );
		break;
	}
}

if (!$root_dirname || !function_exists('wp_get_current_user')) {
	die;
}

$current_user = wp_get_current_user();

if (empty($current_user) || empty($current_user->roles)) {
	die;
}

if ( $current_user->roles[0] != 'administrator' ) {
	wp_die(
		'<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' .
		'<p>' . __( 'Sorry, you are not allowed to access Intelligence info.' ) . '</p>',
		403
	);
}

$title = __('Environment Info');

include( ABSPATH . 'wp-admin/admin-header.php' );
?>
<div class="wrap">
<h1 class="wp-heading-inline"><?php
echo esc_html( $title );
?></h1>

	<div class="intelligence-info">
	<?php
		include_once( $dirname . '/includes/intel.env_info.php');
		print intel_env_info_content();
	?>
	</div>
</div>




<?php
include( ABSPATH . 'wp-admin/admin-footer.php' );
?>