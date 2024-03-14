<?php
/*
* Magical addons functions 
*
*
*/

function mgproducts_display_get_allowed_html_tags()
{
    $allowed_html = [
        'b' => [],
        'i' => [],
        'u' => [],
        'em' => [],
        'br' => [],
        'abbr' => [
            'title' => [],
        ],
        'span' => [
            'class' => [],
        ],
        'strong' => [],
    ];

    $allowed_html['a'] = [
        'href' => [],
        'title' => [],
        'class' => [],
        'id' => [],
    ];

    return $allowed_html;
}

function mgproducts_display_kses_tags($string = '')
{
    return wp_kses($string, mgproducts_display_get_allowed_html_tags());
}
/**
 * Check elementor version
 *
 * @param string $version
 * @param string $operator
 * @return bool
 */
function mgproducts_display_elementor_version_check($operator = '<', $version = '2.6.0')
{
    return defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, $version, $operator);
}

/**
 *  Taxonomy List
 * @return array
 */
function mgproducts_display_taxonomy_list($taxonomy = 'product_cat', $getvalue = 'slug')
{
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            if ($getvalue == 'slug') {
                $options[$term->slug] = $term->name;
            } else {
                $options[$term->term_id] = $term->name;
            }
        }
        return $options;
    }
}

/* 
* Category list
* return first one
*/
function mgproducts_display_pcatlist($id = null, $taxonomy = 'product_cat', $limit = 1)
{
    $terms = get_the_terms($id, $taxonomy);
    $i = 0;
    if (is_wp_error($terms))
        return $terms;

    if (empty($terms))
        return false;

    foreach ($terms as $term) {
        $i++;
        $link = get_term_link($term, $taxonomy);
        if (is_wp_error($link)) {
            return $link;
        }
        echo '<a href="' . esc_url($link) . '">' . $term->name . '</a>';
        if ($i == $limit) {
            break;
        } else {
            continue;
        }
    }
}

/**
 * Get Post List
 * return array
 */
function mgproducts_display_product_name($post_type = 'product')
{
    $options = array();
    $options['0'] = __('Select', 'magical-products-display');
    // $perpage = mgproducts_display_get_option( 'loadproductlimit', 'mgproducts_display_others_tabs', '20' );
    $all_post = array('posts_per_page' => -1, 'post_type' => $post_type);
    $post_terms = get_posts($all_post);
    if (!empty($post_terms) && !is_wp_error($post_terms)) {
        foreach ($post_terms as $term) {
            $options[$term->ID] = $term->post_title;
        }
        return $options;
    }
}

// Customize rating html
if (!function_exists('mgproducts_display_wc_get_rating_html')) {
    function mgproducts_display_wc_get_rating_html($mgpde_class = '')
    {
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        global $product;
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average      = $product->get_average_rating();
        //   if ( $rating_count > 0 ) {
        $rating_whole = $average / 5 * 100;
        $wrapper_class = is_single() ? 'rating-number' : 'top-rated-rating';
        ob_start();
?>
        <div class="mgpde-rating">
            <div class="mgpdeg-product-rating <?php echo esc_attr($mgpde_class); ?>">
                <div class="<?php echo esc_attr($wrapper_class); ?>">
                    <span class="wd-product-ratting">
                        <span class="wd-product-user-ratting" style="width: <?php echo esc_attr($rating_whole); ?>%;">
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                            <i class="eicon-star"></i>
                        </span>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                        <i class="eicon-star-o"></i>
                    </span>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        //   } else { $html  = ''; }
        return $html;
    }
}
// Customize rating html
if (!function_exists('mgproducts_display_wc_empty_rating_html')) {
    function mgproducts_display_wc_empty_rating_html()
    {
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        global $product;
        $rating_count = $product->get_rating_count();
        if ($rating_count < 1) {
        ?>
            <div class="mgp-display-no-rating"></div>
        <?php
        }
    }
}
// Customize rating html
if (!function_exists('mgproducts_display_wc_rating_number')) {
    function mgproducts_display_wc_rating_number($text = 'Reviews')
    {
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        global $product;
        $rating_count = $product->get_rating_count();
        if ($rating_count > 0) {
            $count_text = $rating_count . ' ' . $text;
            echo '<span class="mgp-rating-count">(' . esc_html($count_text) . ')</span>';
        } else {
            $count_text_ziro = '0 ' . $text;

            echo '<span class="mgp-rating-count">(' . esc_html($count_text_ziro) . ')</span>';
        }
    }
}

/* 
* Category list
* return first one
*/
function mgproducts_display_product_category($id = null, $taxonomy = 'product_cat', $limit = 1)
{
    $terms = get_the_terms($id, $taxonomy);
    $i = 0;
    if (is_wp_error($terms))
        return $terms;

    if (empty($terms))
        return false;

    foreach ($terms as $term) {
        $i++;
        $link = get_term_link($term, $taxonomy);
        if (is_wp_error($link)) {
            return $link;
        }
        echo '<a href="' . esc_url($link) . '">' . $term->name . '</a>';
        if ($i == $limit) {
            break;
        } else {
            continue;
        }
    }
}

function mgproducts_display_products_badge()
{
    global $post, $product;

    if ($product->is_on_sale()) {
        ?>
        <div class="mgp-display-badge">
            <?php esc_html_e('Sale!', 'magical-products-display'); ?>
        </div>
    <?php
    } elseif ($product->is_featured()) {
    ?>
        <div class="mgp-display-badge">
            <?php esc_html_e('Featured!', 'magical-products-display'); ?>
        </div>


    <?php
    }
}

function mgproducts_allowed_html_tags()
{
    $allowed_html = [
        'b' => [],
        'i' => [],
        'u' => [],
        'em' => [],
        'br' => [],
        'abbr' => [
            'title' => [],
        ],
        'span' => [
            'class' => [],
        ],
        'strong' => [],
    ];

    $allowed_html['a'] = [
        'href' => [],
        'title' => [],
        'class' => [],
        'id' => [],
    ];

    return $allowed_html;
}

function mgproducts_kses_tags($string = '')
{
    return wp_kses($string, mgproducts_allowed_html_tags());
}


function mgproducts_mpupdate__product_views_count()
{
    if (is_singular('product')) {
        $post_id = get_queried_object_id();
        $views_count = get_post_meta($post_id, '_product_views_count', true);
        $views_count = ($views_count) ? $views_count + 1 : 1;
        update_post_meta($post_id, '_product_views_count', $views_count);
    }
}
add_action('wp_head', 'mgproducts_mpupdate__product_views_count');


// Pro only text 

function mpd_display_pro_only_text()
{
    $pro_only_text = esc_html__('Pro Only', 'magical-products-display');
    $pro_only = '<strong style="color:red;font-size:80%">(' . $pro_only_text . ')</strong>';
    if (get_option('mgppro_is_active', 'no') == 'yes') {
        return false;
    } else {
        return $pro_only;
    }
}


// widget help pro link 
if (!function_exists('mpd_goprolink')) :
    function mpd_goprolink($texts)
    {
        ob_start();

    ?>
        <div class="elementor-nerd-box">
            <img class="elementor-nerd-box-icon" src="<?php echo esc_url(ELEMENTOR_ASSETS_URL . 'images/go-pro.svg'); ?>" />
            <div class="elementor-nerd-box-title"><?php echo esc_html($texts['title']); ?></div>
            <div class="elementor-nerd-box-message"><?php echo esc_html($texts['massage']); ?></div>
            <?php
            // Show a `Go Pro` button only if the user doesn't have Pro.
            if ($texts['link']) { ?>
                <a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro" href="<?php echo esc_url($texts['link']); ?>" target="_blank">
                    <?php echo esc_html__('UPGRADE NOW', 'magical-products-display'); ?>
                </a>
            <?php } ?>
        </div>
<?php
        return ob_get_clean();
    }
endif;
