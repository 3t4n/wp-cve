<?php
if (!function_exists('add_action')) die('Access denied');

if ( get_option('user_script_hotspotloaded') ) {
	$user_script_hotspotloaded = get_option('user_script_hotspotloaded'); ?>
	<script>
		function wp_pano_call_hotspotloaded(response) { <?php echo $user_script_hotspotloaded; ?>	}
	</script>
<?php }

if ( get_option('user_script_before') ) {
	$user_script_before = get_option('user_script_before'); ?>
	<script>
		function wp_pano_call_before() { <?php echo $user_script_before; ?>	}
	</script>
<?php }

if ( get_option('user_script_after') ) {
	$user_script_after = get_option('user_script_after'); ?>
	<script>
		function wp_pano_call_after() { <?php echo $user_script_after; ?> }
	</script>
<?php }	?>