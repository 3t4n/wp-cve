<?php
add_action( 'init', 'create_catalog_taxonomy',0 );

function create_catalog_taxonomy() {
	register_taxonomy(
		'tcpc_catalog',
		'tcpc',
		array(
			'labels'=>array(
                'name' => 'Product Catalogs',
                'add_new_item' => 'Add New Catalog',
                'new_item_name' => "New Catalog",
              ),
      'show_ui' => true,
      'show_tagcloud' => false,
      'hierarchical' => true
		)

	);
}

// Adding a catagory for  products

 add_action( 'init', 'tcpc_taxonomy', 0 );

function tcpc_taxonomy() {
	  $args = array('hierarchical' => true);
		register_taxonomy(
		'tcpc_category',
		 'tcpc',
		 array(
			 'labels'=>array(
								 'name' => 'Product Category',
								 'add_new_item' => 'Add New Category',
								 'new_item_name' => "New Category",
							 ),
			 'show_ui' => true,
			 'show_tagcloud' => false,
			 'hierarchical' => true
		 )
	  );
	}


 ?>
