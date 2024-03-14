<?php
/**
 * Author: Vitaly Kukin
 * Date: 12.10.2016
 * Time: 9:49
 */

function dm_sql_list() {

	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	return [

		"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}adsw_ali_meta (
            `id` BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
            `post_id` BIGINT(20) unsigned NOT NULL,
            `product_id` VARCHAR(20) NOT NULL,
            `origPrice` DECIMAL(10,2) DEFAULT '0.00',
            `origPriceMax` DECIMAL(10,2) DEFAULT '0.00',
            `origSalePrice` DECIMAL(10,2) DEFAULT '0.00',
            `origSalePriceMax` DECIMAL(10,2) DEFAULT '0.00',
            `productUrl` VARCHAR(255) DEFAULT NULL,
            `feedbackUrl` VARCHAR(255) DEFAULT NULL,
            `storeUrl` VARCHAR(255) DEFAULT NULL,
            `storeName` VARCHAR(255) DEFAULT NULL,
            `storeRate` VARCHAR(255) DEFAULT NULL,
            `adminDescription` TEXT DEFAULT NULL,
            `skuOriginaAttr` LONGTEXT DEFAULT NULL,
            `skuOriginal` LONGTEXT DEFAULT NULL,
            `currencyCode` CHAR(4) DEFAULT 'USD',
            `needUpdate` TINYINT(1) DEFAULT 1,
            PRIMARY KEY (`id`),
            KEY (`post_id`),
            KEY (`product_id`)
	    ) {$charset_collate};",
	];
}