<?php
defined( 'ABSPATH' ) || exit;

$fields     = array();
$components = XLWCTY_Components::retrieve_components_fields();
if ( is_array( $components ) && count( $components ) > 0 ) {
	foreach ( $components as $slug => $component ) {
		if ( is_array( $component ) && count( $component ) > 0 ) {
			$fields[ $slug ] = $component;
		}
	}
}

return $fields;
