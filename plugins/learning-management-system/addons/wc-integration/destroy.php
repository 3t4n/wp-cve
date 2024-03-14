<?php
/**
 * Destroy/tear down wc integration.
 *
 * @since 1.8.1
 */

use Masteriyo\Capabilities;

// Remove Masteriyo student capabilities from WC customer.
$customer = get_role( 'customer' );
if ( $customer ) {
	foreach ( Capabilities::get_student_capabilities() as $cap => $grant ) {
		$customer->remove_cap( $cap );
	}
}

// Remove Masteriyo manager capabilities from WC manager.
$manager = get_role( 'shop_manager' );
if ( $manager ) {
	foreach ( Capabilities::get_manager_capabilities() as $cap => $grant ) {
		$manager->remove_cap( $cap );
	}
}

// Add student role to all the WC Customers.
$customers = get_users(
	array(
		'role'   => 'customer',
		'number' => -1,
	)
);

foreach ( $customers as $customer ) {
	if ( in_array( 'masteriyo_student', $customer->roles, true ) ) {
		$customer->remove_role( 'masteriyo_student' );
	}
}
