<?php
/**
 * Mrshop Settings
 *
 */


function  wpsection_the_pagination($args = array(), $echo = 1)
{
	
	global $wp_query;
	
	$default =  array('base' => str_replace( 99999, '%#%', esc_url( get_pagenum_link( 99999 ) ) ), 'format' => '?paged=%#%', 'current' => max( 1, get_query_var('paged') ),
						'total' => $wp_query->max_num_pages, 'next_text' => '&raquo;', 'prev_text' => '&laquo;', 'type'=>'list','add_args' => false);
						
	$args = wp_parse_args($args, $default);			
	
	
	$pagination = str_replace("<ul class='page-numbers'", '<ul class="pagination"', paginate_links($args) );
	
	if(paginate_links(array_merge(array('type'=>'array'),$args)))
	{
		if($echo) echo wp_kses_post($pagination);
		return $pagination;
	}
}

function wpsection_trim($text, $len, $more = null) {
    $text = strip_shortcodes($text);
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $excerpt_length = apply_filters('excerpt_length', $len, 10);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]', 10);
    $excerpt_more = ($more) ? $more : ' ...';
    $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    return $text;
}

// Modify get_wps_blog_categories to add 'Select2' and set it as selected by default
function get_wps_blog_categories() {
    $options = array();
    $taxonomy = 'category';

    if (!empty($taxonomy)) {
        $terms = get_terms(
            array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            )
        );

        if (!empty($terms)) {
            $options[''] = 'All Categories';

            foreach ($terms as $term) {
                if (isset($term->slug) && isset($term->name)) {
                    $options[$term->slug] = $term->name;
                }
            }
        }
    }

    return $options;
}

/**
 * Display the total review count for a product in a WooCommerce template.
 */
function display_review_count() {
    global $product;

    // Check if the product has an average rating (meaning there are reviews).
    if ( $product->get_average_rating() ) {
        $product_id = $product->get_id(); // Get the product ID
        $review_count_var = get_wc_total_review_count($product_id); // Call the function to get the review count

        // Output the review count in a span element.
        echo '<span class="mr_review_number">' . esc_html($review_count_var) . '</span>';
    }
}

function get_wc_total_review_count($product_id) {
    global $wpdb;

    // WooCommerce product reviews are stored in the 'wp_comments' table.
    $query = "SELECT COUNT(comment_ID)
              FROM {$wpdb->comments}
              WHERE comment_post_ID = %d
              AND comment_approved = 1
              AND comment_type = 'review'";
    $review_count = $wpdb->get_var($wpdb->prepare($query, $product_id));

    return (int) $review_count;
}

/*===============================
    Quick View
==============================*/
 
function wpsection_quick_view_scripts() {
    wp_enqueue_script('wc-add-to-cart-variation');
  
        wp_enqueue_style( 'quick-view', get_template_directory_uri() . '/assets/css/quick-view.css' );
        wp_enqueue_style( 'popupcss', get_template_directory_uri() . '/assets/css/popupcss.css' );
        wp_enqueue_script( 'magnific-popup', get_template_directory_uri().'/assets/js/jquery.magnific-popup.js', array( 'jquery' ), '1.1.0', true );
        wp_enqueue_script( 'wpsection-quick-ajax', get_template_directory_uri().'/assets/js/quick.js', array( 'jquery' ), '1.0.0', true );

         // Generate a nonce token
         $wpsection_nonce = wp_create_nonce('wpsection_nonce'); 
         // Add the nonce to the localized script
         wp_localize_script('wpsection-quick-ajax', 'WpsectionAjax', array(
             'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
             'nonce' => $wpsection_nonce, // Add the nonce to the array
         ));

}
add_action( 'wp_enqueue_scripts', 'wpsection_quick_view_scripts' );
 





	

if ( ! function_exists( 'mr_all_cat_list' ) ) {
    function mr_all_cat_list( ) {
        $elements = get_terms( 'product_cat', array('hide_empty' => false) );
        $product_cat_array = array();

        if ( !empty($elements) ) {
            foreach ( $elements as $element ) {
                $info = get_term($element, 'product_cat');
                $product_cat_array[ $info->term_id ] = $info->name;
            }
        }
    
        return $product_cat_array;
    }

}



if ( ! function_exists( 'mr_shop_product_cat_list' ) ) {
    function mr_shop_product_cat_list( ) {
        $elements = get_terms( 'product_cat', array('hide_empty' => false) );
        $product_cat_array = array();

        if ( !empty($elements) ) {
            foreach ( $elements as $element ) {
                $info = get_term($element, 'product_cat');
                $product_cat_array[ $info->term_id ] = $info->name;
            }
        }
    
        return $product_cat_array;
    }

    function mr_shop_product_tag_list( ) {
        $elements = get_terms( 'product_tag', array('hide_empty' => false) );
        $product_cat_array = array();

        if ( !empty($elements) ) {
            foreach ( $elements as $element ) {
                $info = get_term($element, 'product_tag');
                $product_cat_array[ $info->term_id ] = $info->name;
            }
        }
    
        return $product_cat_array;
    }

}
if ( ! function_exists( 'mr_product_rating' ) ) {

    function mr_product_rating() {
        global $product;
        $rating = intval( $product->get_average_rating() );

        // If there's a rating, display the full stars and the remaining empty stars.
        // If no rating, display all 5 empty stars.
        $full_stars = $rating > 0 ? $rating : 0;
        $empty_stars = 5 - $full_stars;

        ?>
        <ul class="mr_star_rating">
            <?php
            for ( $rs = 1; $rs <= $full_stars; $rs++ ) {
                echo '<li class="mr_star_full"><i class="eicon-star"></i></li>';
            }
            for ( $rns = 1; $rns <= $empty_stars; $rns++ ) {
                echo '<li class="mr_star_empty"><i class="eicon-star-o"></i></li>';
            }
            ?>
        </ul>
        <?php
    }
}


//function for Hot Sale

if ( ! function_exists( 'mr_product_cat_list' ) ) {
function mr_product_cat_list( ) {
 
    $term_id = 'product_cat';
    $categories = get_terms( $term_id );
 
    $cat_array['all'] = "Categories";

    if ( !empty($categories) ) {
        foreach ( $categories as $cat ) {
            $cat_info = get_term($cat, $term_id);
            $cat_array[ $cat_info->slug ] = $cat_info->name;
        }
    }
 
    return $cat_array;
}

}

if ( ! function_exists( 'mr_product_tag_list' ) ) {
function mr_product_tag_list( ) {
 
    $term_id = 'product_tag';
    $tag = get_terms( $term_id );
 
    $tag_array['all'] = "Tags";

    if ( !empty($tag) ) {
        foreach ( $tag as $tag ) {
            $tag_info = get_term($tag, $term_id);
            $tag_array[ $tag_info->slug ] = $tag_info->name;
        }
    }
 
    return $tag_array;
}
}
if ( ! function_exists( 'mr_get_product_prices' ) ) {
function mr_get_product_prices( $product ) {

    $saleargs = array(
        'qty'   => '1',
        'price' => $product->get_sale_price(),
    );
    $args     = array(
        'qty'   => '1',
        'price' => $product->get_regular_price(),
    );

    $tax_display_mode      = get_option( 'woocommerce_tax_display_shop' );
    $display_price         = $tax_display_mode == 'incl' ? wc_get_price_including_tax( $product ) : wc_get_price_excluding_tax( $product );
    $display_regular_price = $tax_display_mode == 'incl' ? wc_get_price_including_tax( $product, $args ) : wc_get_price_excluding_tax( $product, $args );
    $display_sale_price    = $tax_display_mode == 'incl' ? wc_get_price_including_tax( $product, $saleargs ) : wc_get_price_excluding_tax( $product, $saleargs );
    switch ( $product->get_type() ) {
        case 'variable':
            $price = $product->get_variation_regular_price( 'min', true );
            $sale  = $display_price;
            break;
        case 'simple':
            $price = $display_regular_price;
            $sale  = $display_sale_price;
            break;
    }
    if ( isset( $sale ) && ! empty( $sale ) && isset( $price ) && ! empty( $price ) ) {
        return array(
            'sale'  => $sale,
            'price' => $price,
        );
    }
    return false;
}
}


if ( ! function_exists( 'mr_product_special_price_calc' ) ) {
function mr_product_special_price_calc( $data ) {
    // sale and price
    if ( ! empty( $data ) ) {
        extract( $data );
    }
    $prefix = '';
    if ( isset( $sale ) && ! empty( $sale ) && isset( $price ) && ! empty( $price ) ) {
        if ( $price > $sale ) {
            $prefix  = '-';
            $dval    = $price - $sale;
            $percent = ( $dval / $price ) * 100;
        }
    }

    if ( isset( $percent ) && ! empty( $percent ) ) {
        return array(
            'prefix'  => $prefix,
            'percent' => round( $percent ),
        );
    }
    return false;
}

}