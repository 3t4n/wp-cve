<?php
/**]
 * Setup wc integration.
 *
 * @since 1.8.1
 */

use Masteriyo\Capabilities;

// Copy Masteriyo student capabilities to WC customer.
$customer = get_role( 'customer' );
if ( $customer ) {
	foreach ( Capabilities::get_student_capabilities() as $cap => $grant ) {
		$customer->add_cap( $cap, $grant );
	}
}

// Copy Masteriyo manager capabilities to WC manager.
$manager = get_role( 'shop_manager' );
if ( $manager ) {
	foreach ( Capabilities::get_manager_capabilities() as $cap => $grant ) {
		$manager->add_cap( $cap, $grant );
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
	if ( ! in_array( 'masteriyo_student', $customer->roles, true ) ) {
		$customer->add_role( 'masteriyo_student' );
	}
}
