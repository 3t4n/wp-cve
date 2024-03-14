<?php
/**
 * Catalog -Just Products
 */
return array(
    'title'       =>	__( 'Catalog - Just Products', 'ultimate-product-catalogue' ),
    'description' =>	_x( 'Adds a catalog, but with only the product thumbnails showing. No sidebar and no header area (icons, etc.).', 'Block pattern description', 'ultimate-product-catalogue' ),
    'categories'  =>	array( 'ewd-upcp-block-patterns' ),
    'content'     =>	'<!-- wp:group {"className":"ewd-upcp-pattern-catalog ewd-upcp-pattern-just-products"} -->
                        <div class="wp-block-group ewd-upcp-pattern-catalog ewd-upcp-pattern-just-products"><!-- wp:ultimate-product-catalogue/ewd-upcp-display-catalog-block {"sidebar":"No"} /--></div>
                        <!-- /wp:group -->',
);
