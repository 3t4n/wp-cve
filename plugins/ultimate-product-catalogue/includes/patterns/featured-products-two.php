<?php
/**
 * Featured Products - 2 Per Row
 */
return array(
    'title'       =>	__( 'Featured Products - 2 Per Row', 'ultimate-product-catalogue' ),
    'description' =>	_x( 'Adds featured products, organized 2 per row. You can choose a specific category or specific products to show.', 'Block pattern description', 'ultimate-product-catalogue' ),
    'categories'  =>	array( 'ewd-upcp-block-patterns' ),
    'content'     =>	'<!-- wp:group {"className":"ewd-upcp-pattern-insert-products-two"} -->
                        <div class="wp-block-group ewd-upcp-pattern-insert-products-two"><!-- wp:ultimate-product-catalogue/ewd-upcp-insert-products-block /--></div>
                        <!-- /wp:group -->',
);
