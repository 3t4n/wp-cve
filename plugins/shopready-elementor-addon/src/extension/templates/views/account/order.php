<?php
/*
* QuomodoSoft
* Single Product Template File
*/
defined('ABSPATH') || exit;

// usage area notification, alert, form Error
do_action( 'mangocube_template_common','account_orders');
// Woocommerce Default Hook
do_action( 'woocommerce_before_account_orders' );

?>


<div class="mangocube-single-account-orders-container">

   <?php  do_action( 'mangocube_single_account_orders_notification' ); ?>

	<?php  do_action( 'mangocube_act_tpl_account_orders' ); ?>

</div>

<?php
// Woocommerce Default Hook
do_action( 'woocommerce_after_account_orders' );


