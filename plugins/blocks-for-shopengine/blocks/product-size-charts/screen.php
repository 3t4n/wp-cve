<?php

defined('ABSPATH') || exit;

extract($settings);
use ShopEngine\Widgets\Products;
use ShopEngine_Pro\Modules\Product_Size_Charts\Product_Size_Charts;

$product            = Products::instance()->get_product(get_post_type());
$product_size_chart = \ShopEngine\Core\Register\Module_List::instance()->get_list()['product-size-charts'];

if ($product_size_chart['status'] === 'active'):

    $chart_status = get_post_meta($product->get_id(), Product_Size_Charts::OPTION_STATUS_KEY, true);
    $chart_uid    = get_post_meta($product->get_id(), Product_Size_Charts::OPTION_KEY, true);
    $charts       = $product_size_chart['settings']['charts']['value'];

    if ($chart_status === 'yes' && !empty($chart_uid)) {

        $key = array_search($chart_uid, array_column($charts, '_uid'));

        if (false !== $key) {
            $chart = $charts[$key]['attachment_id'];
            include_once 'view.php';
        }
    } else {

        $categories = get_the_terms($product->get_id(), 'product_cat');
        $chart_id   = false;

        foreach ($categories as $category) {
            $key = array_search($category->term_id, array_column($charts, 'category_id'));
            if (false !== $key) {
                $chart_id = $key;
                break;
            }
        }

        if (false !== $chart_id) {
            $chart = $charts[$chart_id]['attachment_id'];
            include_once 'view.php';
        }
    }

endif;