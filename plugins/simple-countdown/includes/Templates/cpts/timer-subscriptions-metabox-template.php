<?php
defined( 'ABSPATH' ) || exit;

$_post_id    = $args['post_id'];
$plugin_info = $args['plugin_info'];
$core        = $args['core'];
$cpt         = $args['cpt'];

$emails = $cpt->get_timer_subscriptions( $_post_id );
?>

<!-- CountDowm Timer -->
<div class="subscribe-form-emails">
	<div class="container-fluid p-3 py-5 opacity-50">
	</div>
</div>
