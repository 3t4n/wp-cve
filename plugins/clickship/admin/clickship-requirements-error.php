<?php
/**
 * Admin View: Notice - PHP & WP minimum requirements.
 *
 *
 */
?>
<div class="error">
	<p><strong><?php echo  sprintf(
			/* translators: 1: Minimum PHP version 2: Minimum WordPress version */
			__( 'Update required: require PHP version %1$s and WordPress version %2$s or newer.', 'woocommerce' ),
			CLICKSHIP_MIN_PHP_VERSION,
			CLICKSHIP_MIN_WP_VERSION
		  )?>
		  </strong></p>
</div>
