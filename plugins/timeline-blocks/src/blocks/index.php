<?php

/**
 * Server-side rendering for the post template block
 *
 * @since   1.0.0
 * @package Timeline Blocks for Gutenberg
 */

/**
 * Renders the post block on server.
 */
function timeline_block_render_block_core_latest_posts($attributes) {
    $list_items_markup = $post_thumb_id = '';
    $template = $attributes['layoutcount'];

    if ($attributes['layoutcount']) {
        switch ($template) {
            case '1' :
                $list_items_markup = tb_timeline_layout1($attributes);
                break;
            case '2' :
                $list_items_markup = tb_timeline_layout2($attributes);
                break;
        }
    }

    // Build the classes
    $class = "tb-timeline-template{$attributes['layoutcount']} align-{$attributes['align']}";

    $block_id = 'tb_post_layouts-' . $attributes['block_id'];

    if (isset($attributes['className'])) {
        $class .= ' ' . $attributes['className'];
    }
    $timeline_class = 'tb-timeline';

    // Output the post markup
    $block_content = sprintf('<div id="%1$s" class="%2$s"><div class="%3$s">%4$s</div></div>',esc_attr($block_id), esc_attr($class), esc_attr($timeline_class), $list_items_markup);

    return $block_content;
}

/*
 *  Get the featured image
 */

function timeline_block_get_featured_image($post_id, $attributes) {
    // Get the post thumbnail
    $fetured_image = '';
    $post_thumb_id = get_post_thumbnail_id($post_id);
    if (isset($attributes['displayPostImage']) && $attributes['displayPostImage'] && $post_thumb_id) {
        if ($attributes['imageCrop'] === 'landscape') {
            $post_thumb_size = 'tb-blogpost-landscape';
        } else {
            $post_thumb_size = 'tb-blogpost-square';
        }

        $fetured_image = sprintf('<div class="tb-image"><a href="%1$s" rel="bookmark">%2$s</a></div>', esc_url(get_permalink($post_id)), wp_get_attachment_image($post_thumb_id, $post_thumb_size));
    }
    return $fetured_image;
}

/*
 *  Get the category of post
 */

function timeline_block_get_category($post_id, $seprator) {
    $categories_list = get_the_category_list($seprator, "  ", $post_id);
    $category = "";
    
    if (!empty($categories_list)){
        $category = sprintf('<div class="tb-timeline-category-link tb-inline"> %1$s </div> ', $categories_list);
    return $category;
  }
}

/*
 *  Get the excerpt
 */

function timeline_block_get_excerpt($post_id, $post_query, $attributes) {
    if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) {
        $excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $post_id, 'display'));

        if (!isset($attributes['wordsExcerpt'])) {
            $wordsExcerpt = 25;
        } else {
            $wordsExcerpt = $attributes['wordsExcerpt'];
        }

        if (empty($excerpt)) {
            $excerpt = apply_filters('the_excerpt', wp_trim_words(get_the_content(), $wordsExcerpt));
        }

        if (!$excerpt) {
            $excerpt = null;
        }

        if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) {
            $excerpt_data = wp_kses_post($excerpt ?? '');
        }
    }
    return $excerpt_data;
}

/*
 *  Get the post author
 */

function timeline_block_get_tags_get_author($post_id, $post) {

    $list_items_markup = sprintf('<a href="%2$s">%1$s</a></span></span>', esc_html(get_the_author_meta('display_name', $post->post_author)), esc_html(get_author_posts_url($post->post_author)));

    return $list_items_markup;
}

/*
 *  Get the post tags
 */

function timeline_block_get_tags($post_id, $tag_text) {
    $list_items_markup= '';
    $tags_list = get_the_tag_list("", ", ", "", $post_id);
    if (!empty($tags_list)) {
        if(!empty($tag_text)){
            $list_items_markup .= sprintf('<div class="tb-timeline-post-tags"><span class="link-label">%1$s  </span> %2$s </div> ', $tag_text, $tags_list);
        }else{
            $list_items_markup .= sprintf('<div class="tb-timeline-post-tags"> %1$s </div> ', $tags_list);
        }
        return $list_items_markup; 
    }
}
/*
 *  Get the post social share icon
 */

function timeline_block_get_social_share_icons($post_id) {

    $social_share_info = sprintf('<a data-share="facebook" href="https://www.facebook.com/sharer.php?u=%1$s" class="tb-facebook-share social-share-default tb-social-share" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>', get_the_permalink($post_id));
    $social_share_info .= sprintf('<a data-share="twitter" href="https://twitter.com/share?url=%1$s" class="tb-twitter-share social-share-default tb-social-share" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a>', get_the_permalink($post_id));
    $social_share_info .= sprintf('<a data-share="linkedin" href="https://www.linkedin.com/shareArticle?url=%1$s" class="tb-linkedin-share social-share-default tb-social-share" target="_blank"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>', get_the_permalink($post_id));
    return $social_share_info;
}

/*
 *  Timeline layout 1
 */

function tb_timeline_layout1($attributes) {
    $post_thumb_id = '';
    $list_items_markup= '';

    $recent_posts = array(
        'posts_per_page' => $attributes['postsToShow'],
        'post_status' => 'publish',
        'order' => $attributes['order'],
        'orderby' => $attributes['orderBy'],
        'cat' => !empty($attributes['categories']) ? $attributes['categories'] : '',
    );

    $post_query = new WP_Query($recent_posts);

    $list_items_markup = $post_thumb_id = '';

    //loop the query
    if ($post_query->have_posts()) {

        //loop the query
        while ($post_query->have_posts()) {

            $post_query->the_post();

            // Get the post ID
            $post_id = get_the_ID();

            $post_thumb_id = get_post_thumbnail_id($post_id);

            if ($post_thumb_id && isset($attributes['displayPostImage']) && $attributes['displayPostImage']) {
                $post_thumb_class = 'has-thumb';
            } else {
                $post_thumb_class = 'no-thumb';
            }

            // Start the markup for the post
            $list_items_markup .= sprintf('<article class="%1$s tb-timeline-item">',esc_attr($post_thumb_class));
            $list_items_markup .= sprintf('<div class="tb-timeline-content">');
            $list_items_markup .= sprintf ('<div class="tb-first-inner-wrap">', esc_attr($post_thumb_class));
            
            // Get the post title
            $title = get_the_title($post_id);
            if (!$title) {
                $title = __('Untitled', TB_DOMAIN);
            }

            //Get title tag
            if (isset($attributes['titleTag'])) {
                $post_title_tag = $attributes['titleTag'];
            } else {
                $post_title_tag = 'h2';
            }
            $list_items_markup .= sprintf('<div class="tb-timeline-title-wrap"><'.$post_title_tag.' class="tb-title"><a href="%1$s" rel="bookmark" class="tb-timeline-title tb-layout-1">%2$s</a></'.$post_title_tag.'></div>',esc_url(get_permalink($post_id)), esc_html($title));
            $list_items_markup .= timeline_block_get_featured_image($post_id,$attributes);

            $list_items_markup .= sprintf('<div class="tb-timeline-second-content-wrap" >');

            $list_items_markup .= sprintf('<div class="tb-content-wrap" >');
            // Wrap the text content
            $list_items_markup .= sprintf('<div class="tb-category-link-wraper">');
            if (isset($attributes['displayPostCategory']) && $attributes['displayPostCategory']) {
                $list_items_markup .= timeline_block_get_category($post_id,",  ");
            }
            $list_items_markup .= sprintf( '</div>');

            // Wrap the byline content
            $list_items_markup .= sprintf('<div class="tb-timeline-byline"> <div class="tb-timeline-metadatabox">');
            if (isset($attributes['displayPostAuthor']) && $attributes['displayPostAuthor']) {
                $list_items_markup .= sprintf('<div class="post-author"><i class="fas fa-pencil-alt"></i><span class="tb-blogpost-author">');
                $list_items_markup .= timeline_block_get_author($post_id); 
                $list_items_markup .= sprintf('</div>');
            }

            // Get the post date
            if (isset($attributes['displayPostDate']) && $attributes['displayPostDate']) {
                $list_items_markup .= sprintf('<div class="mdate "><i class="fas fa-calendar-alt"></i> %1$s </div>', get_the_date('F, Y', $post_id));
            }

            //Get comments
            $comments = get_comments_number($post_id);
            if ($comments > 0 ){
                $number_commnet = '<i class="fas fa-comment"></i>' . $comments;
            }else{
                $number_commnet = '<i class="fas fa-comment"></i> 0' ;
            }
            if (isset($attributes['displayPostComments']) && $attributes['displayPostComments']) { 
                $list_items_markup .= sprintf('<div class="post-comments "> %1$s </div>', $number_commnet);
            }  
            
            // Wrap the excerpt content
            $list_items_markup .= sprintf('<div class="tb-timeline-text">');
            $list_items_markup .= sprintf('<div class="tb-timeline-excerpt">');
            if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) {
                $list_items_markup .= timeline_block_get_excerpt($post_id,$post_query,$attributes);
            }

            //Display Readmore  
            if (isset($attributes['displayPostLink']) && $attributes['displayPostLink']) {

                if ($attributes['readmoreView'] == 'text-only') {

                    $list_items_markup .= sprintf('<div class="tb-text-only"><a class="tb-blogpost-link" href="%1$s" rel="bookmark">%2$s</a></div>', esc_url(get_permalink($post_id)), esc_html($attributes['readMoreText']));
                } else if ($attributes['readmoreView'] == 'tb-button') {

                    $list_items_markup .= sprintf('<div class="tb-button-view"><a class="tb-button tb-link gb-text-link" href="%1$s" rel="bookmark">%2$s</a></div>', esc_url(get_permalink($post_id)), esc_html($attributes['readMoreText']));
                }
            }

            // Close the excerpt content
            $list_items_markup .= sprintf('</div></div><div class="tb-timeline-bototm-wrap">');
            if (isset($attributes['displayPostTag']) && $attributes['displayPostTag']) {
                $list_items_markup .= timeline_block_get_tags($post_id,'');
            }
            $list_items_markup .= sprintf('<div class="tb-timeline-social-wrap"><div class="social-share-data">');
            if (isset($attributes['displayPostSocialshare']) && $attributes['displayPostSocialshare']) {
                $list_items_markup .= timeline_block_get_social_share_icons($post_id);
            }
            $list_items_markup .= sprintf('</div></div></div></div>');
            $list_items_markup .= "</article>\n";
        }
    }

    return $list_items_markup;
}

/*
 *  Timeline layout 2
 */

function tb_timeline_layout2($attributes) {
    $post_thumb_id = '';
    $list_items_markup= '';

    $args = array(
        'posts_per_page' => $attributes['postsToShow'],
        'post_status' => 'publish',
        'order' => $attributes['order'],
        'orderby' => $attributes['orderBy'],
        'cat' => !empty($attributes['categories']) ? $attributes['categories'] : '',
    );

    $post_query = new WP_Query($args);

    $list_items_markup = $post_thumb_id = '';

    if ($post_query->have_posts()) {

        //loop the query
        while ($post_query->have_posts()) {

            $post_query->the_post();

            // Get the post ID
            $post_id = get_the_ID();

            $post_thumb_id = get_post_thumbnail_id($post_id);

            if ($post_thumb_id && isset($attributes['displayPostImage']) && $attributes['displayPostImage']) {
                $post_thumb_class = 'has-thumb';
            } else {
                $post_thumb_class = 'no-thumb';
            }
            // Start the markup for the post
        $list_items_markup .= sprintf('<article class="%1$s tb-timeline-item">',esc_attr($post_thumb_class));
        $list_items_markup .= sprintf('<div class="timeline-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            width="21px" height="20px" viewBox="0 0 21 20" enable-background="new 0 0 21 20" xml:space="preserve">
            <path fill="#FFFFFF" d="M19.998,6.766l-5.759-0.544c-0.362-0.032-0.676-0.264-0.822-0.61l-2.064-4.999
                c-0.329-0.825-1.5-0.825-1.83,0L7.476,5.611c-0.132,0.346-0.462,0.578-0.824,0.61L0.894,6.766C0.035,6.848-0.312,7.921,0.333,8.499
                l4.338,3.811c0.279,0.246,0.395,0.609,0.314,0.975l-1.304,5.345c-0.199,0.842,0.708,1.534,1.468,1.089l4.801-2.822
                c0.313-0.181,0.695-0.181,1.006,0l4.803,2.822c0.759,0.445,1.666-0.23,1.468-1.089l-1.288-5.345
                c-0.081-0.365,0.035-0.729,0.313-0.975l4.34-3.811C21.219,7.921,20.855,6.848,19.998,6.766z"/>
            </svg></div>');
        $list_items_markup .= sprintf('<div class="tb-timeline-content">');

        $list_items_markup .= sprintf('<div>');
        $list_items_markup .= timeline_block_get_featured_image($post_id,$attributes);
        $list_items_markup .= sprintf('</div>');

        $list_items_markup .= sprintf ('<div class="tb-first-inner-wrap">', esc_attr($post_thumb_class));
        $list_items_markup .= sprintf('<div class="tb-content-wrap">');
        
        // Get the post title
        $title = get_the_title($post_id);
        if (!$title) {
            $title = __('Untitled', TB_DOMAIN);
        }

        //Get title tag
        if (isset($attributes['titleTag'])) {
            $post_title_tag = $attributes['titleTag'];
        } else {
            $post_title_tag = 'h2';
        }
        $list_items_markup .= sprintf('<div class="tb-timeline-title-wrap "><'.$post_title_tag.' className="tb-title"><a href="%1$s" rel="bookmark" class="tb-timeline-title">%2$s</a></'.$post_title_tag.'>',esc_url(get_permalink($post_id)), esc_html($title));
       $list_items_markup .=  sprintf('</div>');
        
        // Wrap the text content
        $list_items_markup .= sprintf('<div class="tb-category-link-wraper">');
        if (isset($attributes['displayPostDate']) && $attributes['displayPostDate']) {
            $list_items_markup .= sprintf('<div class="mdate tb-inline"><i class="fas fa-calendar-alt"></i> %1$s </div>', get_the_date('d  F, Y', $post_id));
        }
        
        // Get the post date
        if (isset($attributes['displayPostAuthor']) && $attributes['displayPostAuthor']) {
            $list_items_markup .= sprintf('<div class="post-author tb-inline"><i class="fas fa-pencil-alt"></i><span class="tb-blogpost-author">');
            $list_items_markup .= timeline_block_get_author($post_id); 
            $list_items_markup .= sprintf('</div>');
        }
        
        //Get comments
        $comments = get_comments_number($post_id);
        if ($comments > 0 ){
            $number_commnet = '<i class="fas fa-comment"></i>' . $comments;
        }else{
            $number_commnet = '<i class="fas fa-comment"></i> 0' ;
        }
        if (isset($attributes['displayPostComments']) && $attributes['displayPostComments']) { 
            $list_items_markup .= sprintf('<div class="post-comments tb-inline"> %1$s </div>', $number_commnet);
        }  
        $list_items_markup .= sprintf( '</div>');
        if (isset($attributes['displayPostCategory']) && $attributes['displayPostCategory']) {
            $list_items_markup .= timeline_block_get_category($post_id,",  ");
        }
        $list_items_markup .= sprintf('<div class="tb-timeline-second-content-wrap" >');
        
        // Wrap the byline content
        $list_items_markup .= sprintf('<div class="tb-timeline-byline"> <div class="tb-timeline-metadatabox">');
      
        // Wrap the excerpt content
        $list_items_markup .= sprintf('<div class="tb-timeline-text">');
        $list_items_markup .= sprintf('<div class="tb-timeline-excerpt">');
        if (isset($attributes['displayPostExcerpt']) && $attributes['displayPostExcerpt']) {
            $list_items_markup .= timeline_block_get_excerpt($post_id,$post_query,$attributes);
        }

        //Display Readmore  
        if (isset($attributes['displayPostLink']) && $attributes['displayPostLink']) {
            if ($attributes['readmoreView'] == 'text-only') {

                $list_items_markup .= sprintf('<p class="tb-text-only"><a class="tb-blogpost-link" href="%1$s" rel="bookmark">%2$s</a></p>', esc_url(get_permalink($post_id)), esc_html($attributes['readMoreText']));
            } else if ($attributes['readmoreView'] == 'tb-button') {

                $list_items_markup .= sprintf('<div class="tb-button-view"><a class="tb-button tb-link gb-text-link" href="%1$s" rel="bookmark">%2$s</a></div>', esc_url(get_permalink($post_id)), esc_html($attributes['readMoreText']));
            }
        }
        
        //Get comments
        $comments = get_comments_number($post_id);
        if ($comments > 0 ){
            $number_commnet = '<i class="fas fa-comment"></i>' . $comments;
        }else{
            $number_commnet = '<i class="fas fa-comment"></i> 0' ;
        }
        
        // Close the excerpt content
        $list_items_markup .= sprintf('</div></div><div class="tb-timeline-bototm-wrap">');
        if (isset($attributes['displayPostTag']) && $attributes['displayPostTag']) {
            $list_items_markup .= timeline_block_get_tags($post_id,'');
        }
        $list_items_markup .= sprintf('<div class="tb-timeline-social-wrap"><div class="social-share-data">');
        if (isset($attributes['displayPostSocialshare']) && $attributes['displayPostSocialshare']) {
            $list_items_markup .= timeline_block_get_social_share_icons($post_id);
        }
        $list_items_markup .= sprintf('</div></div></div></div>');
        $list_items_markup .= "</article>\n";
        }
    }

    return $list_items_markup;
}

//**
function timeline_register_block_core_latest_posts() {

    // Check if the register function exists
    if (!function_exists('register_block_type')) {
        return;
    }

    register_block_type('timeline-blocks/tb-timeline-blocks', array(
        'attributes' => array(
            'block_id' => array(
                'type' => 'string',
                'default' => 'not_set',
            ),
            'categories' => array(
                'type' => 'string',
            ),
            'className' => array(
                'type' => 'string',
            ),
            'postsToShow' => array(
                'type' => 'number',
                'default' => 4,
            ),
            'displayPostDate' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'displayPostExcerpt' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'wordsExcerpt' => array(
                'type' => 'number',
                'default' => 25,
            ),
            'displayPostAuthor' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'displayPostTag' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'displayPostCategory' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'displayPostImage' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'displayPostLink' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'displayPostComments' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'displayPostSocialshare' => array(
                'type' => 'boolean',
                'default' => true,
            ),
            'align' => array(
                'type' => 'string',
                'default' => 'center',
            ),
            'width' => array(
                'type' => 'string',
                'default' => 'wide',
            ),
            'order' => array(
                'type' => 'string',
                'default' => 'desc',
            ),
            'orderBy' => array(
                'type' => 'string',
                'default' => 'date',
            ),
            'imageCrop' => array(
                'type' => 'string',
                'default' => 'landscape',
            ),
            'layoutcount' => array(
                'type' => 'number',
                'default' => 1,
            ),
            'readMoreText' => array(
                'type' => 'string',
                'default' => 'Read More',
            ),
            'titleTag' => array(
                'type' => 'string',
                'default' => 'h3',
            ),
            'titlefontSize' => array(
                'type' => 'number',
                'default' => '',
            ),
            'titleFontFamily' => array(
                'type' => 'string',
                'default' => '',
            ),
            'titleFontWeight' => array(
                'type' => 'string',
                'default' => '',
            ),
            'titleFontSubset' => array(
                'type' => 'string',
                'default' => '',
            ),
            'postmetafontSize' => array(
                'type' => 'number',
                'default' => '',
            ),
            'postexcerptfontSize' => array(
                'type' => 'number',
                'default' => '',
            ),
            'postctafontSize' => array(
                'type' => 'number',
                'default' => '',
            ),
            'metaFontFamily' => array(
                'type' => 'string',
                'default' => '',
            ),
            'metaFontSubset' => array(
                'type' => 'string',
                'default' => '',
            ),
            'metafontWeight' => array(
                'type' => 'string',
                'default' => '',
            ),
            'excerptFontFamily' => array(
                'type' => 'string',
                'default' => '',
            ),
            'excerptFontWeight' => array(
                'type' => 'string',
                'default' => '',
            ),
            'excerptFontSubset' => array(
                'type' => 'string',
                'default' => '',
            ),
            'ctaFontFamily' => array(
                'type' => 'string',
                'default' => '',
            ),
            'ctaFontSubset' => array(
                'type' => 'string',
                'default' => '',
            ),
            'ctafontWeight' => array(
                'type' => 'string',
                'default' => '',
            ),
            'socialSharefontSize' => array(
                'type' => 'number',
                'default' => '',
            ),
            'readmoreView' => array(
                'type' => 'string',
                'default' => 'text-only',
            ),
            'belowTitleSpace' => array(
                'type' => 'number',
                'default' => '',
            ),
            'belowImageSpace' => array(
                'type' => 'number',
                'default' => '',
            ),
            'belowexerptSpace' => array(
                'type' => 'number',
                'default' => '',
            ),
            'belowctaSpace' => array(
                'type' => 'number',
                'default' => 10,
            ),
            'innerSpace' => array(
                'type' => 'number',
                'default' => 20,
            ),
            'boxbgColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'titleColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'postmetaColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'postexcerptColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'postctaColor' => array(
                'type' => 'string',
                'default' => '#abb8c3',
            ),
            'socialShareColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'designtwoboxbgColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'timelineBgColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'timelineFgColor' => array(
                'type' => 'string',
                'default' => '',
            ),
            'readmoreBgColor' => array(
                'type' => 'string',
                'default' => '#282828',
            ),
        ),
        'render_callback' => 'timeline_block_render_block_core_latest_posts',
    ));
}

add_action('init', 'timeline_register_block_core_latest_posts');

/**
 * Create API fields for additional info
 */
function timeline_block_register_rest_fields() {

    // Add landscape featured image source
    register_rest_field(
            'post', 'featured_image_src', array(
        'get_callback' => 'timeline_block_get_image_src_landscape',
        'update_callback' => null,
        'schema' => null,
            )
    );

    // Add square featured image source
    register_rest_field(
            'post', 'featured_image_src_square', array(
        'get_callback' => 'timeline_block_get_image_src_square',
        'update_callback' => null,
        'schema' => null,
            )
    );

    // Add author info
    register_rest_field(
            'post', 'author_info', array(
        'get_callback' => 'timeline_block_get_author_info',
        'update_callback' => null,
        'schema' => null,
            )
    );

    // Add category info
    register_rest_field(
            'post', 'category_info', array(
        'get_callback' => 'timeline_block_get_catgeory_info',
        'update_callback' => null,
        'schema' => null,
            )
    );

    // Add tags info
    register_rest_field(
            'post', 'tags_info', array(
        'get_callback' => 'timeline_block_get_tags_info',
        'update_callback' => null,
        'schema' => null,
            )
    );

    // Add Social share info
    register_rest_field(
            'post', 'social_share_info', array(
        'get_callback' => 'timeline_block_get_social_share_info',
        'update_callback' => null,
        'schema' => null,
            )
    );

    // Add PostExcert
    register_rest_field(
            'post', 'wordExcerpt_info', array(
        'get_callback' => 'timeline_block_get_wordExcerpt',
        'update_callback' => null,
        'schema' => null,
            )
    );

    // Add Comment
    register_rest_field(
            'post', 'comment_info', array(
        'get_callback' => 'timeline_block_get_comment_info',
        'update_callback' => null,
        'schema' => null,
            )
    );
}

add_action('rest_api_init', 'timeline_block_register_rest_fields');

/**
 * Get landscape featured image source for the rest field
 */
function timeline_block_get_image_src_landscape($object, $field_name, $request) {
    $feat_img_array = wp_get_attachment_image_src(
            $object['featured_media'], 'tb-blogpost-landscape', false
    );
    return $feat_img_array[0] ?? ' ';
}

/**
 * Get square featured image source for the rest field
 */
function timeline_block_get_image_src_square($object, $field_name, $request) {
    $feat_img_array = wp_get_attachment_image_src(
            $object['featured_media'], 'tb-blogpost-square', false
    );
    return $feat_img_array[0] ?? ' ' ;
}

/**
 * Get author info for the rest field
 */
function timeline_block_get_author_info($object, $field_name, $request) {
    // Get the author name
    $author_data['display_name'] = get_the_author_meta('display_name', $object['author']);

    // Get the author link
    $author_data['author_link'] = get_author_posts_url($object['author']);

    // Return the author data
    return $author_data;
}

/**
 * Get category info for the rest field
 */
function timeline_block_get_catgeory_info($object, $field_name, $request) {
    $object['ID'] = '';
    $categories_list = get_the_category_list(", ", " ", $object['ID']);
    $cat_class = '';

    $category_info = sprintf('%1$s', $categories_list);
    // Return the category data
    return $category_info;
}

/**
 * Get tags info for the rest field
 */
function timeline_block_get_tags_info($object, $field_name, $request) {
    // Get the author name
    $object['ID'] = '';
    $tags_list = get_the_tag_list("", ", ", "", $object['ID']);
    $tags_info = sprintf('%1$s', $tags_list);
    // Return the tag data
    return $tags_info;
}

/**
 * Get excerpt info for the rest field
 */
function timeline_block_get_wordExcerpt($object, $field_name, $request) {
    $object['ID'] = '';
    $excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $object['ID'], 'display'));

    if (empty($excerpt)) {
        $excerpt = apply_filters('the_excerpt', get_the_content($object['ID']));
    }

    if (!$excerpt) {
        $excerpt = null;
    }
    $list_items_markup = wp_kses_post($excerpt);

    return $list_items_markup;
}

/**
 * Get social share info for the rest field
 */
function timeline_block_get_social_share_info($object, $field_name, $request) {
    $object['ID'] = '';
    $social_share_info = sprintf('<a data-share="facebook" href="https://www.facebook.com/sharer.php?u=%1$s" class="tb-facebook-share social-share-default tb-social-share" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>', get_the_permalink($object['ID']));
    $social_share_info .= sprintf('<a data-share="twitter" href="https://twitter.com/share?url=%1$s" class="tb-twiiter-share social-share-default tb-social-share" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a>', get_the_permalink($object['ID']));
    $social_share_info .= sprintf('<a data-share="linkedin" href="https://www.linkedin.com/shareArticle?url=%1$s" class="tb-linkedin-share social-share-default tb-social-share" target="_blank"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>', get_the_permalink($object['ID']));
    return $social_share_info;
}

/**
 * Get commentinfo for the rest field
 */
function timeline_block_get_comment_info($object, $field_name, $request) {
    $object['ID'] = '';
    $comments = get_comments_number($object['ID']);
    if ($comments > 0) {
        return $comments;
    } else {
        return '0';
    }
}

/**
 * Get author_name for the rest field
 */
function timeline_block_get_author($post_id) {
    $list_items_markup = sprintf('<a href="%2$s">%1$s</a></span></span>', esc_html(get_the_author_meta('display_name', get_the_author_meta('ID'))), esc_html(get_author_posts_url(get_the_author_meta('ID')))
    );
    return $list_items_markup;
}
