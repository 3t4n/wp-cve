<?php


	//display taxnonomy
function better_tax_choice() {
	if ( class_exists( 'WooCommerce' ) ) {
		$categories = get_terms('product_cat' );
		$blogs = array();
		$i     = 0;
		foreach ( $categories as $category ) {
			if ( $i == 0 ) {
				$default = $category->name ;
				$i ++;
			}
			$blogs[ $category->term_id ] = $category->name;
		}
		return $blogs;
	};
}


add_action( 'elementor/editor/before_enqueue_scripts', function() {
   wp_enqueue_script(
   	'better-elementor',
   	plugin_dir_url( __DIR__ ) .'/assets/js/better-elementor.js', 
   		array('jquery'),
   	'1',
   	true // in_footer
   );
} );


