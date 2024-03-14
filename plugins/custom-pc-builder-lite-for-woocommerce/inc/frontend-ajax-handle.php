<?php

defined('ABSPATH') or die('Keep Quit');
use \Josantonius\Session\Session;
if (!function_exists('NK_getProductByCatID')) {
    add_action('wp_ajax_nkcpcb_get_product_by_cat', 'NK_getProductByCatID');
    add_action('wp_ajax_nopriv_nkcpcb_get_product_by_cat', 'NK_getProductByCatID');
    function NK_getProductByCatID()
    {
        $cat_ids = []; $cat_id = 0;
        if(isset($_GET['catid'])){
            $cat_id = sanitize_text_field($_GET['catid']);
            $cat_ids[] = $cat_id;
        }
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 15,
            'status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term',
                    'terms' => $cat_ids,
                    'operator' => 'IN'
                )
            )
        );
        $products = new WP_Query($args);
        ?>
        <?php if ($products->have_posts()): ?>
        <div class="right-col" id="right-result">
            <input type="hidden" id="catid" value="<?= esc_attr($cat_id) ?>">
            <div class="top-row">
                <div class="flex-row">
                    <div class="sort form-group form-inline flex-row">
                        <label style="width: 80px"><?= __('Sort by', 'nk-custom-pc-builder') ?>:</label>
                        <select id="sort-select">
                            <option value="newest" selected><?= __('Newest','nk-custom-pc-builder')?></option>
                            <option value="expensive"><?= __('High to low','nk-custom-pc-builder')?></option>
                            <option value="cheap"><?= __('Low to hight','nk-custom-pc-builder')?></option>
                            <option value="alphabet"><?= __('Alphabet','nk-custom-pc-builder')?></option>
                        </select>
                    </div>
                    <div class="paginator">
                        <?php custom_pc_builder_lite_generate_paginator($products,1) ?>
                    </div>
                </div>

            </div>
            <div class="product-items">

                <?php while ($products->have_posts()) : $products->the_post(); ?>
                    <?php $wc_product = wc_get_product(); ?>
                    <div class="p-item">
                        <a href="<?= get_permalink() ?>" class="p-img">
                            <img src="<?= get_the_post_thumbnail_url($wc_product->get_id(), 'medium') ?>"
                                 alt="<?= get_the_title() ?>">
                        </a>
                        <div class="info">
                            <a href="<?= get_permalink() ?>" class="p-name"><?= get_the_title() ?></a>
                            <div class="short-desc">
                                <?php
                                if ( ! $wc_product->managing_stock() && ! $wc_product->is_in_stock() ){
                                    echo '<p class="stock out-of-stock outofstock"><i class="nk-icon-close"></i>'.__('Out of Stock','nk-custom-pc-builder').'</p>';}
                                else{
                                    echo '<p class="stock in-stock"><i><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="474.8px" height="474.801px" viewBox="0 0 474.8 474.801" style="enable-background:new 0 0 474.8 474.801;" xml:space="preserve">	<g>
                                    <path d="M396.283,257.097c-1.14-0.575-2.282-0.862-3.433-0.862c-2.478,0-4.661,0.951-6.563,2.857l-18.274,18.271    c-1.708,1.715-2.566,3.806-2.566,6.283v72.513c0,12.565-4.463,23.314-13.415,32.264c-8.945,8.945-19.701,13.418-32.264,13.418    H82.226c-12.564,0-23.319-4.473-32.264-13.418c-8.947-8.949-13.418-19.698-13.418-32.264V118.622    c0-12.562,4.471-23.316,13.418-32.264c8.945-8.946,19.7-13.418,32.264-13.418H319.77c4.188,0,8.47,0.571,12.847,1.714    c1.143,0.378,1.999,0.571,2.563,0.571c2.478,0,4.668-0.949,6.57-2.852l13.99-13.99c2.282-2.281,3.142-5.043,2.566-8.276    c-0.571-3.046-2.286-5.236-5.141-6.567c-10.272-4.752-21.412-7.139-33.403-7.139H82.226c-22.65,0-42.018,8.042-58.102,24.126    C8.042,76.613,0,95.978,0,118.629v237.543c0,22.647,8.042,42.014,24.125,58.098c16.084,16.088,35.452,24.13,58.102,24.13h237.541    c22.647,0,42.017-8.042,58.101-24.13c16.085-16.084,24.134-35.45,24.134-58.098v-90.797    C402.001,261.381,400.088,258.623,396.283,257.097z"/>
                                    <path d="M467.95,93.216l-31.409-31.409c-4.568-4.567-9.996-6.851-16.279-6.851c-6.275,0-11.707,2.284-16.271,6.851    L219.265,246.532l-75.084-75.089c-4.569-4.57-9.995-6.851-16.274-6.851c-6.28,0-11.704,2.281-16.274,6.851l-31.405,31.405    c-4.568,4.568-6.854,9.994-6.854,16.277c0,6.28,2.286,11.704,6.854,16.274l122.767,122.767c4.569,4.571,9.995,6.851,16.274,6.851    c6.279,0,11.704-2.279,16.274-6.851l232.404-232.403c4.565-4.567,6.854-9.994,6.854-16.274S472.518,97.783,467.95,93.216z"/>
                                </g></svg></i>'.__('In Stock','nk-custom-pc-builder').'</p>';
                                }
                                ?>
                            </div>
                            <span class="p-price"><?= wc_price($wc_product->get_price()) ?></span>
                        </div>
                        <div class="action">
                            <?php if ( ! $wc_product->managing_stock() && ! $wc_product->is_in_stock() ):?>
                                <button class="btn btn-danger"><i class="nk-icon-close"></i></button>
                            <?php else: ?>
                                <button data-product_id="<?= esc_attr($wc_product->get_id()) ?>" data-cat_id="<?= esc_attr($cat_id) ?>"
                                        class="addToPCBuilder btn btn-primary"><i class="nk-icon-plus"></i></button>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>
        </div>
    <?php endif ?>
        <?php wp_die();
    }
}
if (!function_exists('NK_addPCBuilderList')) {
    add_action('wp_ajax_add_pc_builder_list', 'NK_addPCBuilderList');
    add_action('wp_ajax_nopriv_add_pc_builder_list', 'NK_addPCBuilderList');
    function NK_addPCBuilderList()
    {
        try {
            if (!isset($_POST['catid']) || !get_term(sanitize_text_field($_POST['catid'])))
                throw new Exception(__('Category does not exists','nk-custom-pc-builder'));
            if (!isset($_POST['id']) || !get_post(sanitize_text_field($_POST['id'])))
                throw new Exception(__('Product does not exists','nk-custom-pc-builder'));
            $pro = wc_get_product(sanitize_text_field($_POST['id']));
            if (!$pro)
                throw new Exception(__('Product does not exists','nk-custom-pc-builder'));

            $pc_builder = Session::get('pc_builder');
            $cat_id = sanitize_text_field($_POST['catid']);
            $pc_builder[$cat_id] = array(
                'id' => $pro->get_id(),
                'quantity' => 1,
                'price' => $pro->get_price(),
                'total' => $pro->get_price()
            );
            $total = 0;
            foreach ($pc_builder as $item) {
                $total += ((int)abs($item['quantity']) * (float)$item['price']);
            }
            $item = $pc_builder[$cat_id]['id']; ?>

            <div class="inner-item">
                <?php $wc_product = wc_get_product($item); ?>
                <a href="<?= get_permalink($item) ?>" class="p-img">
                    <img src="<?= get_the_post_thumbnail_url($item, 'medium') ?>"
                         alt="<?= get_the_title($item) ?>">
                </a>
                <div class="info">
                    <a href="<?= get_permalink($item) ?>" class="p-name"><?= get_the_title($item) ?></a>
                </div>
                <div class="price-wrap">
                    <div class="p-price"
                         data-price="<?= $wc_product->get_price() ?>"><?= wc_price($wc_product->get_price()) ?></div>
                    <div class="p-quantity">
                        <input type="number" data-value="<?= esc_attr($cat_id) ?>" value="1" class="input_quantity"
                               min="1">
                    </div>
                    <i> = </i>
                    <div class="p-total"
                         data-price="<?= esc_attr($wc_product->get_price()) ?>"><?= wc_price($wc_product->get_price()) ?> </div>
                </div>
                <div class="action">
                    <button data-toggle="nk-popup" class="btn btn-success" data-id="<?= esc_attr($cat_id) ?>"><i
                                class="nk-icon-edit"></i></button>
                    <button class="btn btn-danger remove" data-cat_id="<?= esc_attr($cat_id) ?>"
                            data-product_id="<?= esc_attr($item) ?>"><i class="nk-icon-delete"></i></button>
                </div>
            </div>
            <?php
            Session::set('pc_builder',$pc_builder);
            Session::set('grand_total',(float)$total);
            wp_die();
        }
        catch (\Exception $ex){
            echo $ex->getMessage();wp_die();
        }
    }
}
if (!function_exists('NK_removePCBuilderList')) {
    add_action('wp_ajax_remove_pc_builder_list', 'NK_removePCBuilderList');
    add_action('wp_ajax_nopriv_remove_pc_builder_list', 'NK_removePCBuilderList');
    function NK_removePCBuilderList()
    {
        try {
            if (!isset($_POST['catid']) || !get_term(sanitize_text_field($_POST['catid'])))
                throw new Exception(__('Category does not exists', 'nk-custom-pc-builder'));
            $cat = get_term(sanitize_text_field($_POST['catid']));
            $pc_builder = Session::get('pc_builder');
            unset($pc_builder[$cat->term_id]);
            $total = 0;
            foreach ($pc_builder as $item) {
                $total += ((int)abs($item['quantity']) * (float)$item['price']);
            }
            Session::set('pc_builder',$pc_builder);
            Session::set('grand_total',(float)$total);
            ?>
            <button data-toggle="nk-popup" class="btn btn-primary" data-id="<?= esc_attr($cat->term_id) ?>"><i
                        class="nk-icon-plus"></i> <?= __('Select', 'nk-custom-pc-builder') ?> <?= esc_attr($cat->name) ?></button>
            <?php
            wp_die();
        }
        catch(\Exception $ex){
            echo $ex->getMessage();wp_die();
        }
    }
}
if (!function_exists('NK_destroyPCBuilderList')) {
    add_action('wp_ajax_destroy_pc_builder_list', 'NK_destroyPCBuilderList');
    add_action('wp_ajax_nopriv_destroy_pc_builder_list', 'NK_destroyPCBuilderList');
    function NK_destroyPCBuilderList()
    {
        Session::destroy('pc_builder');
        Session::destroy('grand_total');
        wp_die();
    }
}
if (!function_exists('NK_updateQuantityPCBuilderList')) {
    add_action('wp_ajax_update_quantity_pc_builder_list', 'NK_updateQuantityPCBuilderList');
    add_action('wp_ajax_nopriv_update_quantity_pc_builder_list', 'NK_updateQuantityPCBuilderList');
    function NK_updateQuantityPCBuilderList()
    {
        try {
            if (!isset($_POST['catid']) || !get_term(sanitize_text_field($_POST['catid'])))
                throw new Exception(__('Category does not exists', 'nk-custom-pc-builder'));
            $cat = get_term(sanitize_text_field($_POST['catid']));
            $pc_builder = Session::get('pc_builder');
            $pc_builder[$cat->term_id]['quantity'] = $_POST['quantity'] == null ? 1 : abs(sanitize_text_field($_POST['quantity']));
            $total = 0;
            foreach ($pc_builder as $item) {
                $total += ($item['quantity'] * $item['price']);
            }
            Session::set('pc_builder',$pc_builder);
            Session::set('grand_total',(float)$total);
            echo json_encode(['error' => 0, 'message' => __('Update success','nk-custom-pc-builder')]);
            wp_die();
        }
        catch(\Exception $ex){
            echo json_encode(['error' => $ex->getCode(), 'message' => $ex->getMessage()]);
            wp_die();
        }
    }
}
if (!function_exists('NK_addToCart')){
    add_action('wp_ajax_add_to_cart_pc_builder_list', 'NK_addToCart');
    add_action('wp_ajax_nopriv_add_to_cart_pc_builder_list', 'NK_addToCart');
    function NK_addToCart(){
        global $woocommerce;
        $pc_builder = Session::get('pc_builder');
        if(!empty($pc_builder)){
            foreach ($pc_builder as $cat){
                $woocommerce->cart->add_to_cart($cat['id'],$cat['quantity']);
            }
            Session::destroy('pc_builder');
            Session::destroy('grand_total');
            echo wp_json_encode(['err'=> 0,'message' => __('Success','nk-custom-pc-builder')]);
        }
        else{
            echo wp_json_encode(['err'=>1,'message' =>__('Please choose product','nk-custom-pc-builder')]);
        }
        wp_die();
    }
}
if (!function_exists('NK_filterProduct')) {
    add_action('wp_ajax_nkcpcb_filter', 'NK_filterProduct');
    add_action('wp_ajax_nopriv_nkcpcb_filter', 'NK_filterProduct');
    function NK_filterProduct()
    {
        $data = json_decode(preg_replace('/\\\"/', "\"", sanitize_text_field($_POST['data'])), true);
        $cat = custom_pc_builder_lite_get_value_by_key('category', $data);//end($data);// get category, end of array

        $tax[] = array(
            'taxonomy' => 'product_cat',
            'field' => 'term',
            'terms' => (int)$cat,
            'operator' => 'IN'
        );
        $sort = custom_pc_builder_lite_get_value_by_key('sort', $data);
        switch ($sort) {
            case 'newest':
                $orderby = 'date';
                $order = 'desc';
                $metakey = '';
                $metavalue = '';
                break;
            case 'expensive':
                $orderby = 'meta_value_num';
                $order = 'desc';
                $metakey = '_price';
                $metavalue = '';
                break;
            case 'cheap':
                $orderby = 'meta_value_num';
                $order = 'asc';
                $metakey = '_price';
                $metavalue = '';
                break;
            case 'alphabet':
                $orderby = 'title';
                $order = 'asc';
                $metakey = '';
                $metavalue = '';
                break;
            default:
                $orderby = 'date';
                $order = 'desc';
                $metakey = '';
                $metavalue = '';
                break;
        }
        $currentPage = custom_pc_builder_lite_get_value_by_key('page', $data);
        $keyword = custom_pc_builder_lite_get_value_by_key('keyword', $data);
        add_filter( 'posts_where', 'custom_pc_builder_lite_post_title_filter', 10, 2 );
        $products = new WP_Query(
            array(
                'nk_search_post_title' => $keyword,
                'post_type' => 'product',
                'posts_per_page' => 20,
                'status' => 'publish',
                'tax_query' => $tax,
                'paged' => $currentPage,
                'orderby' => $orderby,
                'order' => $order,
                'meta_key' => $metakey,
                'meta_value' => $metavalue
            )
        );
        remove_filter( 'posts_where', 'custom_pc_builder_lite_post_title_filter', 10 );
        ?>

        <input type="hidden" id="catid" value="<?=  esc_attr(custom_pc_builder_lite_get_value_by_key('category', $data)) ?>">
        <div class="top-row">
            <div class="flex-row">
                <div class="sort form-group form-inline flex-row">
                    <label style="width: 80px"><?= __('Sort by', 'nk-custom-pc-builder') ?>:</label>
                    <select id="sort-select">
                        <option value="newest" <?php selected($sort,'newest')?>><?= __('Newest','nk-custom-pc-builder')?></option>
                        <option value="expensive" <?php selected($sort,'expensive')?>><?= __('High to low','nk-custom-pc-builder')?></option>
                        <option value="cheap" <?php selected($sort,'cheap')?>><?= __('Low to hight','nk-custom-pc-builder')?></option>
                        <option value="alphabet" <?php selected($sort,'alphabet')?>><?= __('Alphabet','nk-custom-pc-builder')?></option>
                    </select>
                </div>
                <div class="paginator">
                    <?php custom_pc_builder_lite_generate_paginator($products,$currentPage) ?>
                </div>
            </div>

        </div>
        <?php if ($products->have_posts()) :?>
        <div class="product-items">

            <?php while ($products->have_posts()) : $products->the_post(); ?>
                <?php $wc_product = wc_get_product(); ?>
                <div class="p-item">
                    <a href="<?= get_permalink() ?>" class="p-img">
                        <img src="<?= get_the_post_thumbnail_url($wc_product->get_id(), 'medium') ?>"
                             alt="<?= get_the_title() ?>">
                    </a>
                    <div class="info">
                        <a href="<?= get_permalink() ?>" class="p-name"><?= get_the_title() ?></a>
                        <div class="short-desc">
                            <?php
                            if ( ! $wc_product->managing_stock() && ! $wc_product->is_in_stock() ){
                                echo '<p class="stock out-of-stock outofstock"><i class="nk-icon-close"></i>'.__('Out of Stock','nk-custom-pc-builder').'</p>';}
                            else{
                                echo '<p class="stock in-stock"><i><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="474.8px" height="474.801px" viewBox="0 0 474.8 474.801" style="enable-background:new 0 0 474.8 474.801;" xml:space="preserve">	<g>
		<path d="M396.283,257.097c-1.14-0.575-2.282-0.862-3.433-0.862c-2.478,0-4.661,0.951-6.563,2.857l-18.274,18.271    c-1.708,1.715-2.566,3.806-2.566,6.283v72.513c0,12.565-4.463,23.314-13.415,32.264c-8.945,8.945-19.701,13.418-32.264,13.418    H82.226c-12.564,0-23.319-4.473-32.264-13.418c-8.947-8.949-13.418-19.698-13.418-32.264V118.622    c0-12.562,4.471-23.316,13.418-32.264c8.945-8.946,19.7-13.418,32.264-13.418H319.77c4.188,0,8.47,0.571,12.847,1.714    c1.143,0.378,1.999,0.571,2.563,0.571c2.478,0,4.668-0.949,6.57-2.852l13.99-13.99c2.282-2.281,3.142-5.043,2.566-8.276    c-0.571-3.046-2.286-5.236-5.141-6.567c-10.272-4.752-21.412-7.139-33.403-7.139H82.226c-22.65,0-42.018,8.042-58.102,24.126    C8.042,76.613,0,95.978,0,118.629v237.543c0,22.647,8.042,42.014,24.125,58.098c16.084,16.088,35.452,24.13,58.102,24.13h237.541    c22.647,0,42.017-8.042,58.101-24.13c16.085-16.084,24.134-35.45,24.134-58.098v-90.797    C402.001,261.381,400.088,258.623,396.283,257.097z"/>
		<path d="M467.95,93.216l-31.409-31.409c-4.568-4.567-9.996-6.851-16.279-6.851c-6.275,0-11.707,2.284-16.271,6.851    L219.265,246.532l-75.084-75.089c-4.569-4.57-9.995-6.851-16.274-6.851c-6.28,0-11.704,2.281-16.274,6.851l-31.405,31.405    c-4.568,4.568-6.854,9.994-6.854,16.277c0,6.28,2.286,11.704,6.854,16.274l122.767,122.767c4.569,4.571,9.995,6.851,16.274,6.851    c6.279,0,11.704-2.279,16.274-6.851l232.404-232.403c4.565-4.567,6.854-9.994,6.854-16.274S472.518,97.783,467.95,93.216z"/>
	</g></svg></i>'.__('In Stock','nk-custom-pc-builder').'</p>';
                            }
                            ?>
                        </div>
                        <span class="p-price"><?= wc_price($wc_product->get_price()) ?></span>
                    </div>
                    <div class="action">
                        <?php if ( ! $wc_product->managing_stock() && ! $wc_product->is_in_stock() ):?>
                            <button class="btn btn-danger"><i class="nk-icon-close"></i></button>
                        <?php else:

                            ?>
                            <button data-product_id="<?= esc_attr($wc_product->get_id()) ?>" data-cat_id="<?= esc_attr($cat) ?>"
                                    class="addToPCBuilder btn btn-primary"><i class="nk-icon-plus"></i></button>
                        <?php endif ?>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>
    <?php endif; wp_die();
    }

}
