<?php
/**
 * WC Edit Account Template
 */
?>
<div class="youzify-wc-main-content youzify-wc-edit-account-content">

	<?php do_action( 'youzify_wc_before_edit_account_content' ); ?>

	<?php echo do_shortcode( '[youzify_woocommerce_edit_account]' ); ?>

	<?php do_action( 'youzify_wc_after_edit_account_content' ); ?>

</div>