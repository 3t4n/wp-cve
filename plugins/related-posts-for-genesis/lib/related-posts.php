<?php
if(!defined('ABSPATH')) {
	echo "Well done! Try Again";
	die();
}
// Adds custom image size for images in Related Posts section.
add_image_size( 'related', 400, 222, true );

add_action( 'genesis_entry_footer', 'wg_related_posts', 12 );
function wg_related_posts() {
    global $do_not_duplicate;

    // If we are not on a single post page, abort.
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    global $count;
    $count = 0;

    $related = '';

    $do_not_duplicate = array();

    // Get the tags for the current post.
    $tags = get_the_terms( get_the_ID(), 'post_tag' );

    // Get the categories for the current post.
    $cats = get_the_terms( get_the_ID(), 'category' );

    // If we have some tags, run the tag query.
    if ( $tags ) {
        $query    = wg_related_tax_query( $tags, $count, 'tag' );
        $related .= $query['related'];
        $count    = $query['count'];
    }

    // If we have some categories and less than 3 posts, run the cat query.
    if ( $cats && $count <= 2 ) {
        $query    = wg_related_tax_query( $cats, $count, 'category' );
        $related .= $query['related'];
        $count    = $query['count'];
    }

    // End here if we don't have any related posts.
    if ( ! $related ) {
        return;
    }


    // Display the related posts section.
    echo '<div class="related">';
    // Checks whether user has entered anything on customizer
   if (get_theme_mod( 'related_posts_title') ) {
        echo '<h3 class="related-title">' .get_theme_mod( 'related_posts_title' ) . '</h3>' ;
    } else {
        // If user hasn't entered anything, this will used as default.
        echo '<h3 class="related-title">You May Also Like</h3>';
    }
    // This statement holds the related posts section
    echo '<div class="related-posts">' . $related . '</div>';
    echo '</div>';
}

/**
 * The taxonomy query.
 *
 * @since  1.0.0
 * 
 * @param  array  $terms Array of the taxonomy's objects.
 * @param  int    $count The number of posts.
 * @param  string $type  The type of taxonomy, e.g: `tag` or `category`.
 *
 * @return string
 */
function wg_related_tax_query( $terms, $count, $type ) {
    global $do_not_duplicate;

    // If the current post does not have any terms of the specified taxonomy, abort.
    if ( ! $terms ) {
        return;
    }

    // Array variable to store the IDs of the posts.
    // Stores the current post ID to begin with.
    $post_ids = array_merge( array( get_the_ID() ), $do_not_duplicate );

    $term_ids = array();

    // Array variable to store the IDs of the specified taxonomy terms.
    foreach ( $terms as $term ) {
        $term_ids[] = $term->term_id;
    }

    $tax_query = array(
        array(
            'taxonomy'  => 'post_format',
            'field'     => 'slug',
            'terms'     => array(
                'post-format-link',
                'post-format-status',
                'post-format-aside',
                'post-format-quote',
            ),
            'operator' => 'NOT IN',
        ),
    );
    // This variable holds the mod information
    $number_mod = get_theme_mod( 'related_posts_number' );
    // Here variable "$number_mod" is used to check whether user has entered info or not.
    if (!$number_mod == "" ) {
        $showposts = $number_mod - $count;
    } else {
        // This is the default value
        $showposts = 3 - $count;
    }

    $args = array(
        $type . '__in'        => $term_ids,
        'post__not_in'        => $post_ids,
        'showposts'           => $showposts,
        'ignore_sticky_posts' => 1,
        'tax_query'           => $tax_query,
    );

    $related  = '';

    $tax_query = new WP_Query($args);

    if ( $tax_query->have_posts() ) {
        while ( $tax_query->have_posts() ) {
            $tax_query->the_post();

            $do_not_duplicate[] = get_the_ID();

            $count++;

            $title = get_the_title();

            $related .= '<div class="related-post">';

            $related .= '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" title="Permanent Link to ' . $title . '">' . genesis_get_image(array( 'size' => 'related', 'attr' => array( 'class' => 'related-post-image' ) )) . '</a>';

            $related .= '<div class="related-post-info"><a class="related-post-title" href="' . esc_url( get_permalink() ) . '" rel="bookmark" title="Permanent Link to ' . $title . '">' . $title . '</a>';

            $related .= show_date_on_frontend();

            $related .= show_tags_on_frontend();

            $related .= show_cats_on_frontend();

            $related .= '</div></div>';
        }
    }

    wp_reset_postdata();

    $output = array(
        'related' => $related,
        'count'   => $count,
    );

    return $output;
}
?>