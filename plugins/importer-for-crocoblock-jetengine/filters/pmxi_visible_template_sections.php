<?php

function cc_jetengine_importer_pmxi_visible_template_sections( $sections, $post_type ) {

	// Enable add-on section for Users.
	if ( 'import_users' == $post_type )
		$sections[] = 'featured';

	// Enable add-on section for Customers.
	if ( 'shop_customer' == $post_type )
		$sections[] = 'featured';

	return $sections;
}