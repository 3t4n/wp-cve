<?php

$REVI_DISPLAY_WIDGET_FLOATING = get_option('REVI_DISPLAY_WIDGET_FLOATING');
if (isset($REVI_DISPLAY_WIDGET_FLOATING) && $REVI_DISPLAY_WIDGET_FLOATING == 1) {
    add_action('wp_footer', 'revi_load_widget_floating', 100);
}

add_action('woocommerce_thankyou', 'revi_popup_order_confirmation', 111, 1);


// GENERAL WIDGETS
function revi_load_widget_vertical()
{
    $reviwidgets = new reviwidgets();
    echo $reviwidgets->loadReviWidget("vertical", array());
}

function revi_load_widget_wide()
{
    $reviwidgets = new reviwidgets();
    echo $reviwidgets->loadReviWidget("wide", array());
}

function revi_load_widget_floating()
{
    $reviwidgets = new reviwidgets();
    echo $reviwidgets->loadReviWidget("floating", array());
}

function revi_load_widget_small()
{
    $reviwidgets = new reviwidgets();
    echo $reviwidgets->loadReviWidget("small", array());
}

function revi_load_widget_general()
{
    $reviwidgets = new reviwidgets();
    echo $reviwidgets->loadReviWidget("general", array());
}

// POPUP
function revi_popup_order_confirmation($id_order)
{
    if (!is_null($id_order)) {

        $revimodel = new revimodel();
        $result_encoded = $revimodel->getNewReviewUrl($id_order);

        if (!empty($result_encoded)) {

            $template_vars = array(
                'id_order' => $id_order,
                'result_encoded' => $result_encoded,
            );

            $reviwidgets = new reviwidgets();
            echo $reviwidgets->loadReviWidget("popup_new_review", $template_vars);
        }
    }
}



if (WOOCOMMERCE_ACTIVE) {

    // PRODUCT WIDGETS
    add_filter('woocommerce_after_shop_loop_item_title', 'revi_product_list', 5);
    function revi_product_list()
    {
        // if (is_product_category() || is_shop()) {
        global $post;
        $id_product = $post->ID;

        $reviwidgets = new reviwidgets();
        echo $reviwidgets->loadReviWidget("product_list", array(), $id_product);
        // }
    }

    // Remove Woocommerce Reviews
    add_action('init', 'revi_woocommerce_remove_reviews');
    function revi_woocommerce_remove_reviews()
    {
        $REVI_WOOCOMMERCE_REVIEWS = get_option('REVI_WOOCOMMERCE_REVIEWS');
        if (!isset($REVI_WOOCOMMERCE_REVIEWS) || !$REVI_WOOCOMMERCE_REVIEWS) {
            remove_post_type_support('product', 'comments');
            remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
        }
    }

    // WOCOMMERCE TABS
    $REVI_TAB_REVIEWS = get_option('REVI_TAB_REVIEWS');
    if (isset($REVI_TAB_REVIEWS) && $REVI_TAB_REVIEWS) {
        add_filter('woocommerce_product_tabs', 'revi_product_tab', 121);

        function revi_product_tab($tabs)
        {
            $revimodel = new revimodel();
            global $post;
            $id_product = $post->ID;
            $product_info = $revimodel->getReviProduct($id_product);

            $tabs['reviews']['callback'] = 'revi_load_widget_product'; // Custom description callback
            $tabs['reviews']['title'] = __('Reviews', 'revi-io-customer-and-product-reviews') . ' (' . $product_info->num_ratings . ')'; // Rename the reviews tab
            return $tabs;
        }
    } else {
        add_filter('woocommerce_after_single_product', 'revi_load_widget_product', 121);
    }

    // PRODUCT COMMENTS WIDGET
    function revi_load_widget_product()
    {
        global $post;
        $id_product = $post->ID;

        $reviwidgets = new reviwidgets();
        echo $reviwidgets->loadReviWidget("product", array(), $id_product);
    }

    // PRODUCT SMALL WIDGET
    $REVI_TAB_PRODUCT_STARS = get_option('REVI_TAB_PRODUCT_STARS');
    if (isset($REVI_TAB_PRODUCT_STARS) && $REVI_TAB_PRODUCT_STARS == 1) {
        add_filter('woocommerce_before_add_to_cart_form', 'revi_load_widget_product_small', 9);
    } else {
        add_filter('woocommerce_single_product_summary', 'revi_load_widget_product_small', 9);
    }

    function revi_load_widget_product_small()
    {
        global $post;
        $id_product = $post->ID;

        $reviwidgets = new reviwidgets();
        echo $reviwidgets->loadReviWidget("product_small", array(), $id_product);
    }


    //REMOVES WOOCOMMERCE DEFAULT STRUCTURED DATA
    add_filter('woocommerce_structured_data_product', 'structured_data_product_nulled_wiped', 10, 2);
    function structured_data_product_nulled_wiped($markup, $product)
    {
    }

    // STRUCTURED META DATA
    add_action('wp_head', 'revi_schema_product');
    function revi_schema_product()
    {
        if (get_option('REVI_SUBSCRIPTION') < 2 || !is_product()) {
            return;
        }

        global $product;
        $product_data = is_a($product, 'WC_Product') ? $product : wc_get_product(get_the_id());

        if (!is_a($product_data, 'WC_Product')) {
            return;
        }

        $revimodel = new revimodel();
        $product_info = $revimodel->getReviProduct($product_data->get_id());
        $brand_name = get_product_attribute_or_meta($product_data, PRODUCT_BRAND);
        $gtin = get_product_attribute_or_meta($product_data, PRODUCT_EAN, 'EAN');
        $comments = $revimodel->getProductComments($product_data->get_id(), REVI_DEFAULT_LANGUAGE);

        $productData = [
            "@context" => "http://schema.org",
            "@type" => "Product",
            "name" => $product_data->get_name(),
            "sku" => $product_data->get_sku(),
            "offers" => get_offers_data($product_data),
            "url" => get_permalink($product_data->get_id()),
        ];

        if (!empty($brand_name)) {
            $productData["brand"] = ["@type" => "Brand", "name" => $brand_name];
        }

        if (!empty($gtin)) {
            $productData["gtin13"] = $gtin;
        }

        // Optional fields
        if ($product_description = wp_strip_all_tags($product_data->post->post_excerpt)) {
            $productData["description"] = $product_description;
        }

        if ($thumbnail_url = get_the_post_thumbnail_url($product_data->get_id(), 'full')) {
            $productData["image"] = $thumbnail_url;
        }

        if (isset($product_info->num_ratings) && $product_info->num_ratings > 0) {
            $productData["aggregateRating"] = get_aggregate_rating($product_info);

            if (!empty($comments)) {
                $productData["review"] = array_map('map_comments_to_schema', $comments);
            }
        }

        echo '<!-- ReviProductSchema --><script type="application/ld+json">' . json_encode($productData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
    }


    function get_product_attribute_or_meta($product_data, $attributes, $prefix = '')
    {
        foreach ($attributes as $attr) {
            $value = $product_data->get_attribute($attr) ?: get_post_meta($product_data->get_id(), $attr, true);
            if (!empty($value)) {
                return $value;
            }

            // Check for numeric suffixes if prefix is provided
            if ($prefix) {
                for ($i = 0; $i < 200; $i++) {
                    $value = $product_data->get_attribute($prefix . $i);
                    if (!empty($value)) {
                        return $value;
                    }
                }
            }
        }
        return '';
    }

    function get_offers_data($product_data)
    {
        return [
            "@type" => "Offer",
            "availability" => "http://schema.org/" . ($product_data->is_in_stock() ? 'InStock' : 'OutOfStock'),
            "price" => wc_get_price_including_tax($product_data),
            "priceValidUntil" => date('Y-m-d', strtotime('+1 year')),
            "priceCurrency" => get_woocommerce_currency(),
            "seller" => [
                "@type" => "Organization",
                "name" => get_option('blogname'),
                "url" => get_site_url()
            ]
        ];
    }


    function map_comments_to_schema($comment)
    {
        return [
            "@type" => "Review",
            "reviewRating" => [
                "@type" => "Rating",
                "ratingValue" => $comment->rating,
                "bestRating" => "5"
            ],
            "author" => [
                "@type" => "Person",
                "name" => !empty($comment->customer_name) ? $comment->customer_name : 'Anonymous'
            ],
            "datePublished" => $comment->date,
            "description" => $comment->comment
        ];
    }

    function get_aggregate_rating($product_info)
    {
        return [
            "@type" => "AggregateRating",
            "bestRating" => "5",
            "ratingValue" => $product_info->avg_rating,
            "reviewCount" => $product_info->num_ratings
        ];
    }
}
