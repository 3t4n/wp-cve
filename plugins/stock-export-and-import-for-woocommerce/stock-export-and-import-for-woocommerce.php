<?php
/**
 * Plugin Name: Stock Export and Import for WooCommerce
 * Description: Export and import stock statuses and quantities for WooCommerce products in Comma-Separated Values (CSV) format.
 * Version: 1.0.6
 * Author: WP Zone
 * Author URI: https://wpzone.co/?utm_source=stock-export-and-import-for-woocommerce&utm_medium=link&utm_campaign=wp-plugin-author-uri
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
* WC tested up to: 8.1.1
 */
 
/*
    Stock Export and Import for WooCommerce
    Copyright (C) 2023  WP Zone

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

/* CREDITS:
 * This plugin contains code copied from and/or based on the following third-party products,
 * in addition to any others indicated in code comments or license files:
 *
 * WordPress, by Automattic, GPLv2+
 * WooCommerce, by Automattic, GPLv3+
 *
 * See licensing and copyright information in the ./license directory.
 * This file was modified by Jonathan Hall.
*/

add_action('admin_menu', 'hm_wcsxi_admin_menu');
function hm_wcsxi_admin_menu() {
	add_submenu_page('woocommerce', 'Stock Export &amp; Import', 'Stock Export/Import', 'manage_woocommerce', 'hm_wcsxi', 'hm_wcsxi_page');
}

function hm_wcsxi_default_report_settings() {
	return array(
		'cat' => 0,
		'include_header' => 1,
		'orderby' => 'quantity',
		'orderdir' => 'desc'
	);
}

// This function generates the admin page HTML
function hm_wcsxi_page() {

	// Print header
	echo('
		<div class="wrap" style="max-width: 1000px;">
			<h2>Stock Export &amp; Import</h2>
	');
	
	// Check for WooCommerce
	if (!class_exists('WooCommerce')) {
		echo('<div class="error"><p>This plugin requires that WooCommerce is installed and activated.</p></div></div>');
		return;
	}


	// Perform import if requested
	if (!empty($_POST['hm_wcsxi_do_import'])) {
		
		// Verify the nonce
		check_admin_referer('hm_wcsxi_do_import');
		
		if (isset($_FILES['hm_wcsxi_import_file']) && empty($_FILES['hm_wcsxi_import_file']['error']) && is_uploaded_file($_FILES['hm_wcsxi_import_file']['tmp_name'])) {
			
			$updateCount = 0;
			
			// Try to increase memory before starting import
			ini_set('memory_limit', '256M');
			
			$fh = fopen($_FILES['hm_wcsxi_import_file']['tmp_name'], 'r');
			
			while (($row = fgetcsv($fh)) !== false) {
				$fieldCount = count($row);
				if ($fieldCount < 3 || !is_numeric($row[0]))
					continue;
				
				if (update_post_meta($row[0], '_stock_status', (empty($row[$fieldCount - 2]) || strcasecmp($row[$fieldCount - 2], 'no') == 0 ? 'outofstock' : 'instock'))
					|| update_post_meta($row[0], '_manage_stock', $row[$fieldCount - 1] == '--' ? 'no' : 'yes')
					|| ($row[$fieldCount - 1] == '--' ? false : update_post_meta($row[0], '_stock', (empty($row[$fieldCount - 1]) || !is_numeric($row[$fieldCount - 1]) ? 0 : $row[$fieldCount - 1]))))
						++$updateCount;
			}
			fclose($fh);
			@unlink($_FILES['hm_wcsxi_import_file']['tmp_name']);
			
			echo('<div class="updated"><p>Import complete. <strong>'.$updateCount.'</strong> product(s) were updated.</p></div>');
		} else {
			echo('<div class="error"><p>The file was not uploaded successfully. Please check that the file size does not exceed the maximum upload size permitted by your server, and try again.</p></div>');
		}
	}





	$savedReportSettings = get_option('hm_wcsxi_report_settings');
	$reportSettings = (empty($savedReportSettings) ?
						hm_wcsxi_default_report_settings() :
						array_merge(hm_wcsxi_default_report_settings(),
								$savedReportSettings[0]
						));

	
	
	// Print form
	
	echo('	<h3>Export Stock</h3>
	
			<p class="description">
				Optionally limit the export to a single product category. You can also choose the order of the products and whether or not the report will include a header row. Click Export Stock to download the report in Comma-Separated Values (CSV) format.
			</p>
	
			<form action="" method="post">
				<input type="hidden" name="hm_wcsxi_do_export" value="1" />
		');
	wp_nonce_field('hm_wcsxi_do_export');
	echo('
	
	<span style="margin-right: 20px; white-space: nowrap; padding-bottom: 5px; display: inline-block;">
		<label for="hm_wcsxi_field_cat">Product category:</label>');
	
	wp_dropdown_categories(array(
		'taxonomy' => 'product_cat',
		'id' => 'hm_wcsxi_field_cat',
		'name' => 'cat',
		'orderby' => 'NAME',
		'order' => 'ASC',
		'show_option_all' => 'All Categories',
		'selected' => $reportSettings['cat']
	));
	echo('
	</span>
	
	<span style="margin-right: 20px; white-space: nowrap; padding-bottom: 5px; display: inline-block;">
		<label for="hm_wcsxi_field_orderby">Sort by:</label>
		<select name="orderby" id="hm_wcsxi_field_orderby">
			<option value="ID"'.($reportSettings['orderby'] == 'ID' ? ' selected="selected"' : '').'>Product ID</option>
			<option value="sku"'.($reportSettings['orderby'] == 'sku' ? ' selected="selected"' : '').'>Product SKU</option>
			<option value="title"'.($reportSettings['orderby'] == 'title' ? ' selected="selected"' : '').'>Product Name</option>
		</select>
		<select name="orderdir">
			<option value="asc"'.($reportSettings['orderdir'] == 'asc' ? ' selected="selected"' : '').'>ascending</option>
			<option value="desc"'.($reportSettings['orderdir'] == 'desc' ? ' selected="selected"' : '').'>descending</option>
		</select>
	</span>
	<span style="margin-right: 20px; white-space: nowrap; padding-bottom: 5px; display: inline-block;">
		<label>
			<input type="checkbox" name="include_header"'.(empty($reportSettings['include_header']) ? '' : ' checked="checked"').' />
			Include header row
		</label>
	</span>
	<button type="submit" class="button-primary">Export Stock</button>
	</form>');
	
	
	echo('
		
		<h3>Import Stock</h3>
		
		<p class="description">
			The import file must be in Comma-Separated Values (CSV) format. The first field in each row must be the product ID, and the last two fields must be the In Stock indicator and the stock quantity, respectively (this is the format produced by the export function of this plugin). If the value of the In Stock indicator is empty, zero, or &quot;no&quot;, the product is considered to be out of stock; all other values are taken to mean that the product is in stock. A value of &quot;--&quot; (two dashes) in the stock quantity field will disable stock management for that product (an empty or other non-numeric value is treated as zero).
			<strong>Always remember to back up your WooCommerce database before attempting batch updates.</strong>
		</p>
		
		<form action="" method="post" enctype="multipart/form-data" style="margin-bottom: 30px;">
			<input type="hidden" name="hm_wcsxi_do_import" value="1" />
		');
	wp_nonce_field('hm_wcsxi_do_import');
	echo('
		<input type="file" name="hm_wcsxi_import_file" style="margin-right: 20px; margin-bottom: 5px;" />
		<button type="submit" class="button-primary">Import Stock</button>
	</form>');
			
	
	$potent_slug = 'stock-export-and-import-for-woocommerce';
	include(__DIR__.'/plugin-credit.php');
	
	// End wrap
	echo('</div>');
	
}

// Hook into WordPress init; this function performs report generation when
// the admin form is submitted
add_action('init', 'hm_wcsxi_on_init');
function hm_wcsxi_on_init() {
	global $pagenow;
	
	// Check if we are in admin and on the report page
	if (!is_admin())
		return;
	if ( $pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'hm_wcsxi' && !empty($_POST['hm_wcsxi_do_export']) && current_user_can('manage_woocommerce') ) {
		
		// Verify the nonce
		check_admin_referer('hm_wcsxi_do_export');
		
		add_filter('nocache_headers', 'hm_wcsxi_filter_nocache_headers', 9999);
		nocache_headers();
		
		$newSettings = array_intersect_key($_POST, hm_wcsxi_default_report_settings());
		foreach ($newSettings as $key => $value)
			if (!is_array($value))
				$newSettings[$key] = htmlspecialchars($value);
		
		// Update the saved report settings
		$savedReportSettings = get_option('hm_wcsxi_report_settings');
		$savedReportSettings[0] = array_merge(hm_wcsxi_default_report_settings(), $newSettings);
		

		update_option('hm_wcsxi_report_settings', $savedReportSettings);
		
		// Assemble the filename for the report download
		$filename =  'Product Stock - ';
		if (!empty($_POST['cat']) && is_numeric($_POST['cat'])) {
			$cat = get_term($_POST['cat'], 'product_cat');
			if (!empty($cat->name))
				$filename .= addslashes(html_entity_decode($cat->name)).' - ';
		}
		$filename .= date('Y-m-d', current_time('timestamp')).'.csv';
		
		// Send headers
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		
		// Try to increase memory before starting export
		ini_set('memory_limit', '256M');
		
		// Output the report header row (if applicable) and body
		$stdout = fopen('php://output', 'w');
		if (!empty($_POST['include_header']))
			hm_wcsxi_export_header($stdout);
		hm_wcsxi_export_body($stdout);
		
		exit;
	}
}

// This function outputs the report header row
function hm_wcsxi_export_header($dest) {
	$header = array('Product ID', 'Product SKU', 'Product Name', 'Price', 'In Stock', 'Stock Quantity');
	fputcsv($dest, $header);
}

// This function generates and outputs the report body rows
function hm_wcsxi_export_body($dest) {
	
	$queryParams = array(
		'post_type' => 'product',
		'posts_per_page' => 50,
		'order' => ($_POST['orderdir'] == 'desc' ? 'DESC' : 'ASC')
	);
	
	// Order
	if ($_POST['orderby'] == 'ID' || $_POST['orderby'] == 'title')
		$queryParams['orderby'] = $_POST['orderby'];
	else {
		$queryParams['meta_key'] = '_sku';
		$queryParams['orderby'] = 'meta_value';
	}
	
	// Category
	if (!empty($_POST['cat']) && is_numeric($_POST['cat'])) {
		$queryParams['tax_query'] = array(array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
			'terms' => $_POST['cat']
		));
	}
	
	
	// Output report rows
	$page = 0;
	while (true) {
		$queryParams['paged'] = ++$page;
		query_posts($queryParams);
		if (!have_posts())
			break;
		while (have_posts()) {
			the_post();
			$product_id = get_the_ID();
			fputcsv($dest, array(
				$product_id,
				get_post_meta($product_id, '_sku', true),
				get_the_title(),
				get_post_meta($product_id, '_price', true),
				(strcasecmp(get_post_meta($product_id, '_stock_status', true), 'instock') == 0 ? 'X' : ''),
				(strcasecmp(get_post_meta($product_id, '_manage_stock', true), 'yes') == 0 ? floatval(get_post_meta($product_id, '_stock', true))*1 : '--'),
			));
		}
		wp_reset_query();
	}
}


function hm_wcsxi_filter_nocache_headers($headers) {
	// Reference: https://owasp.org/www-community/OWASP_Application_Security_FAQ
	
	$cacheControl = array_map( 'trim', explode(',', $headers['Cache-Control']) );
	$cacheControl = array_unique( array_merge( [
		'no-cache',
		'no-store',
		'must-revalidate',
		'pre-check=0',
		'post-check=0',
		'max-age=0',
		's-maxage=0'
	], $cacheControl ) );
	
	$headers['Cache-Control'] = implode(', ', $cacheControl);
	$headers['Pragma'] = 'no-cache';
	
	return $headers;
}
?>