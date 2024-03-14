<?php

defined('ABSPATH') || exit;

global $wpdb;

return [
    'ALTER TABLE '.$wpdb->prefix.'mipconnector_product_image_url DROP INDEX `product_shop_id`, DROP INDEX `id_image`;',
    'ALTER TABLE '.$wpdb->prefix.'mipconnector_product_image_url DROP COLUMN `image_url`, DROP COLUMN `image_shop_url`;',
    'ALTER TABLE '.$wpdb->prefix.'mipconnector_product_image_url CHANGE COLUMN `id_image` `id_image` INT(11) UNSIGNED NOT NULL AFTER `product_shop_id`, ADD PRIMARY KEY (`product_shop_id`, `id_image`) USING BTREE;',
    'ALTER TABLE '.$wpdb->prefix.'mipconnector_product_url ADD COLUMN `variation_shop_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" AFTER `product_shop_id`, DROP PRIMARY KEY, ADD PRIMARY KEY (`variation_shop_id`, `product_shop_id`) USING BTREE;'
];