<?php
/**
 * Template: Taxonomy Food Menu.
 *
 * @package RT_FoodMenu
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

\RT\FoodMenu\Helpers\Fns::render( 'archive-food-menu-cat' );
