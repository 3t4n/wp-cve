<?php
/**
 * Featured FAQs
 */
return array(
    'title'       =>	__( 'Featured FAQs', 'ultimate-faqs' ),
    'description' =>	_x( 'Adds a list of featured FAQs. Use the available block attributes to specify which FAQs you would like to show.', 'Block pattern description', 'ultimate-faqs' ),
    'categories'  =>	array( 'ewd-ufaq-block-patterns' ),
    'content'     =>	'<!-- wp:group {"className":"ewd-ufaq-pattern-featured-faqs"} -->
                        <div class="wp-block-group ewd-ufaq-pattern-featured-faqs"><!-- wp:ultimate-faqs/ewd-ufaq-display-faq-block {"post_count":"5","group_by_category":"no"} /--></div>
                        <!-- /wp:group -->',
);
