<?php
defined( 'ABSPATH' ) || exit;

$post_id      = $args['post_id'];
$plugin_info  = $args['plugin_info'];
$core         = $args['core'];
$cpt          = $args['cpt'];
?>

<!-- CountDowm Timer -->
<div class="subscribe-form-fields">
	<div class="container-fluid p-3">
        <?php $cpt->settings->print_fields( 'general', 'related' ); ?>
	</div>
</div>
