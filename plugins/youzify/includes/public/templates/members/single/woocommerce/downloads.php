<?php
/**
 * WC Downloads Template
 */
?>
<div class="youzify-wc-main-content youzify-wc-downloads-content">

	<?php do_action( 'youzify_wc_before_downloads_content' ); ?>

	<?php echo do_shortcode( '[youzify_woocommerce_downloads]' ); ?>

	<?php do_action( 'youzify_wc_after_downloads_content' ); ?>

</div>