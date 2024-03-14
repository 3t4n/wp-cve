<?php

use ShopWP\Utils;

$DB_Settings_General = ShopWP\Factories\DB\Settings_General_Factory::build();

if (!is_singular('wps_products') && !is_singular('wps_collections') ) {
    return;
}

global $post;

$is_singular_products = is_singular('wps_products');
$is_singular_collections = is_singular('wps_collections');
$enabled_default_pages = $DB_Settings_General->get_col_val('enable_default_pages', 'bool');

$post_type = get_post_type();
$post_type_data = get_post_type_object($post_type);

if ($post_type !== 'wps_products' && $post_type !== 'wps_collections') {
    return;
}

if ($is_singular_products) {

    if ($enabled_default_pages) {
        $parent_url = $DB_Settings_General->get_col_val('url_products', 'string');

    } else {
        $parent_url = '/' . $post_type_data->rewrite['slug'];
    }        

} else if ($is_singular_collections) {

    if ($enabled_default_pages) {
        $parent_url = $DB_Settings_General->get_col_val('url_collections', 'string');

    } else {
        $parent_url = '/' . $post_type_data->rewrite['slug'];
    }            
}

if ($is_singular_products || $is_singular_collections) { ?>

    <style>

        .wps-breadcrumbs-name {
            text-transform: capitalize;
        }

        .wps-breadcrumbs-inner {
            max-width: 1100px;
            display: flex;
            padding: 0;
            margin: 2em auto 15px auto;
            list-style: none;
        }

        .wps-breadcrumbs-link {
            margin: 0;
        }

        .wps-breadcrumbs-inner > li:first-of-type {
            margin-left: 0;
        }
        
        .wps-breadcrumbs-seperator {
            position: relative;
            left: 8px;
            font-size: 16px;
            top: 0px;
            margin-right: 17px;
        }
        
    </style>

    <ul id="wps-breadcrumbs" class="wps-breadcrumbs-inner" itemscope="" itemtype="http://schema.org/BreadcrumbList">

        <li class="wps-breadcrumbs-item-home" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a class="wps-breadcrumbs-link" href="<?= esc_url_raw($parent_url); ?>" title="Products" itemprop="item">
                <span class="wps-breadcrumbs-name" itemprop="name"><?= sanitize_text_field($post_type_data->label); ?></span>
                <meta itemprop="position" content="1">
            </a>
            <span class="wps-breadcrumbs-seperator">❯</span>
        </li>

        <li class="wps-breadcrumbs-item-home" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <p class="wps-breadcrumbs-link" title="Home" itemprop="item">
                <span class="wps-breadcrumbs-name" itemprop="name"><?= sanitize_text_field(get_the_title()); ?></span>
                <meta itemprop="position" content="2">
            </p>
        </li>

    </ul>

<?php }