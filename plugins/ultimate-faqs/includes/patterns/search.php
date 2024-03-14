<?php
/**
 * Search FAQs
 */
return array(
    'title'       =>	__( 'Search FAQs', 'ultimate-faqs' ),
    'description' =>	_x( 'Adds the FAQ search form.', 'Block pattern description', 'ultimate-faqs' ),
    'categories'  =>	array( 'ewd-ufaq-block-patterns' ),
    'content'     =>	'<!-- wp:group {"className":"ewd-ufaq-pattern-search"} -->
                        <div class="wp-block-group ewd-ufaq-pattern-search"><!-- wp:ultimate-faqs/ewd-ufaq-search-block /--></div>
                        <!-- /wp:group -->',
);
