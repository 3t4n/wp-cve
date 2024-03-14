<?php
/** @var \Freemius $quick_paypal_payments_fs Freemius global object. */
global $qem_fs;
// remove freemius tabs from this page
$qem_fs->add_filter( 'is_submenu_visible', function ( $is_visible, $menu_id ) {
	return false;
}, 9999, 2 );
qem_messages();
