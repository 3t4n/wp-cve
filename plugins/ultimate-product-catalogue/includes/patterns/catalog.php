<?php
/**
 * Catalog
 */
return array(
    'title'       =>	__( 'Catalog', 'ultimate-product-catalogue' ),
    'description' =>	_x( 'Adds a catalog.', 'Block pattern description', 'ultimate-product-catalogue' ),
    'categories'  =>	array( 'ewd-upcp-block-patterns' ),
    'content'     =>	'<!-- wp:group {"className":"ewd-upcp-pattern-catalog"} -->
                        <div class="wp-block-group ewd-upcp-pattern-catalog"><!-- wp:ultimate-product-catalogue/ewd-upcp-display-catalog-block /--></div>
                        <!-- /wp:group -->',
);
