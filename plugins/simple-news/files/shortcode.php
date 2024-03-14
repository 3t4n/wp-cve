<?php
// News Shortcode

add_shortcode('news', 'hjemmesider_news');
function hjemmesider_news($atts) {
    global $post;
    ob_start();

// define attributes and their defaults
    extract(shortcode_atts(array('order' => 'order', 'number' => 99999, 'offset' => 0, 'cat' => 'cat', 'col' => 0, 'excerpt' => 'yes', 'type' => 'normal' ), $atts));


// define query parameters based on attributes

/* single post = news */
    if ( is_singular('news') ) {
        $options = array(
            'post_type' => 'news',
            'post__not_in' => array($post->ID),
            'order' => $order,
            'orderby' => 'date',
            'posts_per_page' => $number,
            'offset' => $offset,
            'cat' => array($cat));
    }

/* related news */
    if ( $type = 'related' ) {
        $options = array(
            'post_type' => 'news',
            'post__not_in' => array($post->ID),
            'category__in' => wp_get_post_categories( $post->ID ),
            'order' => $order,
            'orderby' => 'date',
            'posts_per_page' => $number,
            'offset' => $offset,
            'cat' => array($cat));
    }

/* pages, cat */
    else {
        $options = array(
            'post_type' => 'news',
            'order' => $order,
            'orderby' => 'date',
            'posts_per_page' => $number,
            'offset' => $offset,
            'cat' => array($cat));
    }

$the_query = new WP_Query($options);
$img_options = get_option( 'simple_news_settings' );

 // run the loop based on the query

    if ($the_query->have_posts()) {

        static $i = 1;
        $shortcode = 'sh-id-' . $i;

        echo '<div class="simple-news-con col-' . $col . ' ' . $shortcode . '">';
            while ($the_query->have_posts()): $the_query->the_post();
                simple_news_loop(); wp_reset_postdata();
            endwhile;
        echo '</div>';

    }

    $i++;
    $myvariable = ob_get_clean();
    return $myvariable;
}
