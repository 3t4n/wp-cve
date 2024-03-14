<?php
/**
 * OAuth Notice view.
 *
 * @package GoogleAnalytics
 */

$msg = isset( $msg ) ? $msg : '';
?>
<div class="ga-alert ga-alert-warning">
	<?php echo wp_kses_post( $msg ); ?>
</div>
