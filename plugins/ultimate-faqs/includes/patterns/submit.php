<?php
/**
 * Submit Question
 */
return array(
    'title'       =>	__( 'Submit Question', 'ultimate-faqs' ),
    'description' =>	_x( 'Adds the submit question form.', 'Block pattern description', 'ultimate-faqs' ),
    'categories'  =>	array( 'ewd-ufaq-block-patterns' ),
    'content'     =>	'<!-- wp:group {"className":"ewd-ufaq-pattern-submit"} -->
                        <div class="wp-block-group ewd-ufaq-pattern-submit"><!-- wp:ultimate-faqs/ewd-ufaq-submit-faq-block /--></div>
                        <!-- /wp:group -->',
);
