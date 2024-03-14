<?php

/* Plugin Name: WooCommerce XML feed for Skroutz & Bestprice
  Plugin URI: https://www.papaki.com
  Description: XML feed creator for Skroutz & BestPrice
  Version: 1.6.9.1
  Author: Papaki
  Author URI: https://www.papaki.com
  License: GPLv3 or later
  WC tested up to: 6.2.1
 */
 /*
 Based on original plugin "Skroutz.gr & Bestprice.gr XML Feed for Woocommerce By emspace.gr" [https://wordpress.org/plugins/woo-xml-feed-skroutz-bestprice/]
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
load_plugin_textdomain('skroutz-woocommerce-feed', false, dirname(plugin_basename(__FILE__)) . '/languages/');

function papaki_wooshop_skroutzxml_activate() {

}

register_activation_hook(__FILE__, 'papaki_wooshop_skroutzxml_activate');

function skroutz_xml_admin_menu() {

    /* add new top level */
    add_menu_page(
            __('Skroutz & BestPrice', 'skroutz-woocommerce-feed'), __('Skroutz & BestPrice', 'skroutz-woocommerce-feed'), 'manage_options', 'skroutz_xml_admin_menu', 'skroutz_xml_admin_page', plugins_url('/', __FILE__) . '/images/xml-icon.png'
    );

    /* add the submenus */
    add_submenu_page(
            'skroutz_xml_admin_menu', __('Create XML Feeds', 'skroutz-woocommerce-feed'), __('Create XML Feeds', 'skroutz-woocommerce-feed'), 'manage_options', 'skroutz_xml_create_page', 'skroutz_xml_create_page'
    );
}

add_action('admin_menu', 'skroutz_xml_admin_menu');
add_action('admin_init', 'register_mysettings');

function enqueue_select2_jquery() {
     // wp_register_script('select2', plugins_url('woocommerce/assets/js/select2/select2.min.js'),null,false);
    // wp_register_style('select2', plugins_url('woocommerce/assets/css/select2.css'),null,false);
    wp_register_script('select2', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js",null,false);
    wp_register_style('select2', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css",null,false);

    wp_enqueue_style('select2');
    wp_enqueue_script('select2');
}
add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );
function select2jquery_inline() {
    ?>
<style type="text/css">
#main {
    height:100%;
}
.autocomplete {
    width:350px;
}
.gtin select {width:150px;}
.tablenav.top #doaction, #doaction2, #post-query-submit {margin: 0px 4px 0 4px;}
.select2-search.select2-search--inline {

}
</style>
<script type='text/javascript'>

jQuery(function($){
	$('.skroutz_bestprice.form-table select').select2();

});

</script>
    <?php
 }
add_action( 'admin_head', 'select2jquery_inline' );

function skroutz_xml_admin_page() {

    add_action('wp', 'skroutz_xml_setup_schedule');
    $skicon = plugins_url('/', __FILE__) . '/images/skroutz.png';
    $bpicon = plugins_url('/', __FILE__) . '/images/bp.png';
    $skbpicon = plugins_url('/',__FILE__) . '/images/skroutz_bestprice.png';

    echo '<div id="main">';
    echo '<div>';
    echo '</br>';
    echo '<h2>'  . __('Settings for XML Feeds for Skroutz and Bestprice', 'skroutz-woocommerce-feed') . '</h2>';
    echo '</div>';

    global $woocommerce;
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    $taxonomies = get_taxonomies();
    $meta_keys = get_meta_keys();
    
     echo '<form method="post" action="options.php">';
     settings_fields('skroutz-group');
     do_settings_sections('skroutz-group');
     echo '<table class="form-table skroutz_bestprice">';
     echo      '<tr valign="top">';
     echo      '<th scope="row">' . __('When in Stock Availability', 'skroutz-woocommerce-feed') . '</th><td>';
     $options = get_option('instockavailability');

    $items = array(
        __('Available in store / Delivery 1 to 3 days', 'skroutz-woocommerce-feed'),
        __('Delivery 1 to 3 days', 'skroutz-woocommerce-feed'),
        __('Delivery 4 to 10 days', 'skroutz-woocommerce-feed'),
        __('Attribute: Availability', 'skroutz-woocommerce-feed'),
        __('Custom Availability', 'skroutz-woocommerce-feed')
    );
    echo "<select id='drop_down1' name='instockavailability'>";
    foreach ($items as $key => $item) {
        $selected = ($options == $key) ? 'selected="selected"' : '';
        echo "<option value='" . esc_html($key) . "' $selected>" . esc_html($item) . "</option>";
    }
    echo "</select>";
    echo "</br></br> <em>" . __('Select <strong>Attribute: Availability</strong> only if you have added an attribute with name "Availability"', 'skroutz-woocommerce-feed') . "</em>";
    echo '</td>';
    echo '</tr>';

    

    echo '<tr valign="top">';
    echo  '<th scope="row">' . __('If a Product is out of Stock or on backorder', 'skroutz-woocommerce-feed') . '</th>';
    echo '<td>';

    $options2 = get_option('ifoutofstock');

    $items = array(
        __('Include as out of Stock or Upon Request', 'skroutz-woocommerce-feed'), 
        __('Exclude from feed', 'skroutz-woocommerce-feed'),
        __('Delivery 1 to 3 days', 'skroutz-woocommerce-feed'),
        __('Delivery 4 to 10 days', 'skroutz-woocommerce-feed'),
        __('Attribute: Out of Stock Availability', 'skroutz-woocommerce-feed'),
    );
    echo "<select id='drop_down2' name='ifoutofstock'>";
    foreach ($items as $key => $item) {
        $selected = ($options2 == $key) ? 'selected="selected"' : '';
        echo "<option value='" . esc_html($key) . "' $selected>" . esc_html($item) . "</option>";
    }
    echo "</select>";
    echo "</br></br> <em>" 
    .  __('• Select <strong>Attribute: Out of Stock Availability</strong> only if you have added an attribute with name "OutOfStockAvailability"', 'skroutz-woocommerce-feed')
    . __('<br>• If you select  <strong>“Include as out of Stock or Upon Request” </strong>:<br>
    &emsp; At Skroutz it will show the option: “Delivery up to 30 days” (former Upon Order option).<br>
    &emsp; At BestPrice it will show either “Out of stock” or “Upon order”, depending on product availability status.', 'skroutz-woocommerce-feed')
    . "</em>";
    
    echo '</td>';
    echo '</tr>';

    $include_tax = get_option('include_tax', false);

    echo '<tr valign="top">';
    echo '<th> <label for="include_tax">' . __('Auto Calculate Price with Tax', 'skroutz-woocommerce-feed') . '</label></th>';
    echo '<td><input style="margin-left:10px;" id="include_tax"  class="include_tax" type="checkbox" name="include_tax" value="1" ' . ($include_tax == 1 ? "checked" : "") . ' /></td>';
    echo "</tr>";

    $group_variations = get_option('group_variations', false);

    echo '<tr valign="top">';
    echo '<th> <label for="group_variations">' . __('Split variable products by color', 'skroutz-woocommerce-feed') . '</label></th>';
    echo '<td><input style="margin-left:10px;" id="group_variations"  class="group_variations" type="checkbox" name="group_variations" value="1" ' . ($group_variations == 1 ? "checked" : "") . ' /></td>';
    echo "</tr>";

     $custom_productId = get_option('custom_productId');
     echo '<tr>'; 
     echo '<th> <label for="custom_product_id">' . __('Custom Product Id', 'skroutz-woocommerce-feed') . '</label></th>';
     echo '<td><select name="custom_productId" class="autocomplete" tabindex="-1">';
     echo "<option value='' " . selected($selected, true, false) . ">" . __('-Default-', 'skroutz-woocommerce-feed') . "</option>";

     foreach ($meta_keys as $key => $metaKey) {
         $selected = false;
         if ($custom_productId == $metaKey) {
             $selected = true;
         }

         echo "<option value='" . esc_html($metaKey) . "' " . selected($selected, true, false) . ">" . esc_html($metaKey) . "</option>";
     }
     echo '</select>';
     echo '</td>';
     echo '</tr>';

     $custom_mpn = get_option('custom_mpn');
     echo '<tr>'; 
     echo '<th> <label for="custom_mpn">' . __('MPN', 'skroutz-woocommerce-feed') . '</label></th>';
     echo '<td><select name="custom_mpn" class="autocomplete" tabindex="-1">';
     echo "<option value='' " . selected($selected, true, false) . ">" . __('-Default-', 'skroutz-woocommerce-feed') . "</option>";

     foreach ($meta_keys as $key => $metaKey) {
         $selected = false;
         if ($custom_mpn == $metaKey) {
             $selected = true;
         }

         echo "<option value='" . esc_html($metaKey) . "' " . selected($selected, true, false) . ">" . esc_html($metaKey) . "</option>";
     }
     echo '</select>';
     echo '</td>';
     echo '</tr>';

     foreach ($attribute_taxonomies as $tax) {
         $term = wc_attribute_taxonomy_name($tax->attribute_name);
         $attribute_terms[$tax->attribute_id] = '';
         if (taxonomy_exists($term)) {
             $attribute_terms[$tax->attribute_id] = $term;
         }
     }

     $skroutz_atts_color = get_option('skroutz_atts_color', 'pa_color');
     $skroutz_atts_size = get_option('skroutz_atts_size', 'pa_size');
     $skroutz_atts_manuf = get_option('skroutz_atts_manuf', 'pa_brand');

     echo "<tr>";
     echo "<th>";
     echo '<label for="skroutz_atts_size">' . __('Size', 'skroutz-woocommerce-feed');
     echo "</th>";
     echo "<td>";
     echo '<select name="skroutz_atts_size">';
     echo "<option value='' " . selected($selected, true, false) . ">" . __('-Empty-', 'skroutz-woocommerce-feed') . "</option>";
        foreach ($attribute_taxonomies as $tax) {
            $selected = false;
            if ($skroutz_atts_size == $attribute_terms[$tax->attribute_id]) {
                $selected = true;
            }

            echo "<option value='" . esc_html($attribute_terms[$tax->attribute_id]) . "' " . selected($selected, true, false) . ">" . esc_html($tax->attribute_label) . "</option>";
        }
     echo '</select>';
     echo "</td>";
     echo '</tr>';
 
     echo "<tr>";
     echo "<th>";
     echo '<label for="skroutz_atts_color">' . __('Color', 'skroutz-woocommerce-feed');
     echo "</th>";
     echo "<td>";
     echo '<select name="skroutz_atts_color">';
     echo "<option value='' " . selected($selected, true, false) . ">" . __('-Empty-', 'skroutz-woocommerce-feed') . "</option>";

     foreach ($attribute_taxonomies as $tax) {
         $selected = false;
         if ($skroutz_atts_color == $attribute_terms[$tax->attribute_id]) {
             $selected = true;
         }
 
         echo "<option value='" . esc_html($attribute_terms[$tax->attribute_id]) . "' " . selected($selected, true, false) . ">" . esc_html($tax->attribute_label) . "</option>";
     }
     echo '</select>';

     echo "</td>";
     echo '</tr>';

    echo "<tr>";
    echo "<th>";
    echo '<label for="skroutz_atts_manuf">' . __('Manufacturer', 'skroutz-woocommerce-feed');
    
    echo "</th>";
    echo "<td>";
    echo '<select name="skroutz_atts_manuf">';
    if ($skroutz_atts_manuf == '') {
        $selected = true;
    }
    echo "<option value='' " . selected($selected, true, false) . ">" . __('-Empty-', 'skroutz-woocommerce-feed') . "</option>";
    $hasAttributeBrand = false;
    foreach ($attribute_taxonomies as $tax) {
        $selected = false;
        if ($skroutz_atts_manuf == $attribute_terms[$tax->attribute_id]) {
            $selected = true;
        }
        if($attribute_terms[$tax->attribute_id] === 'brand' || $attribute_terms[$tax->attribute_id] === 'pa_brand') {
            $hasAttributeBrand = true;
        }
        echo "<option value='" . esc_html($attribute_terms[$tax->attribute_id]) . "' " . selected($selected, true, false) . ">" . esc_html($tax->attribute_label) . "</option>";
    }
    if(!$hasAttributeBrand){
        foreach ($taxonomies as $tax) {
            $selected = false;
            if(strpos($tax, 'brand') === false) continue;
            if ($skroutz_atts_manuf == $tax) {
                $selected = true;
            }

            echo "<option value='" . esc_html($tax) . "' " . selected($selected, true, false) . ">" . esc_html($tax) . "</option>";
        }
    }
    echo '</select>';
    echo "</td>";
    echo '</tr>';

    echo '<tr valign="top">';
    echo '<th scope="row">' . __('Features for bestprice(Table of Features)', 'skroutz-woocommerce-feed') . '</th>';
    echo '<td>';

    $options3 = get_option('features');

    echo "<select class='autocomplete' id='drop_down3' name='features[]' multiple='multiple'>";
    foreach ($attribute_taxonomies as $tax) {
        $selected = false;
        if (is_array($options3) && in_array($attribute_terms[$tax->attribute_id], $options3)) {
            $selected = true;
        }

        echo "<option value='" . esc_html($attribute_terms[$tax->attribute_id]) . "' " . selected($selected, true, false) . ">" . esc_html($tax->attribute_label) . "</option>";
    }
    echo "</select>";

    echo '</td>';
    echo  '</tr>';

    echo '<tr>';
    echo '<th>' . __('Exclude categories from XML', 'skroutz-woocommerce-feed') . '</th>';
    echo '<td>';
    $cats_excluded = get_option('exclude_cats');

    echo '<select class="autocomplete" id="cat_drop" name="exclude_cats[]" multiple="multiple">';
    $avail_cats = get_terms('product_cat', array('get' => 'all'));
    
        foreach ($avail_cats as $cat) {
            $selected = false;
            if (is_array($cats_excluded) && in_array($cat->term_id, $cats_excluded)) {
                $selected = true;
            }

            echo '<option value="' . $cat->term_id . '" ' . selected($selected, true, false) . ' >' . $cat->name . '</option>';
        }
    echo '</select>';
    echo  '';
    echo '</td>';
    echo '</tr>';


    $enable_gtin = get_option('enable_gtin');
    $gtin_label = get_option('gtin_label');
    $gtin_value = get_option('gtin_value');
    $gtin_plugins = array(
        'hwp_product_gtin' => 'WooCommerce UPC, EAN, and ISBN',
        '_wpm_gtin_code' => 'Product GTIN (EAN, UPC, ISBN) for WooCommerce',
    );
    $gtin_values = array();
    foreach ($meta_keys as $key => $metaKey) {
        if(strpos($metaKey, 'gtin') !== false 
            || strpos($metaKey, 'ean') !== false 
            || strpos($metaKey, 'isbn') !== false 
            || strpos($metaKey, 'upc') !== false 
            || strpos($metaKey, 'barcode') !== false 
            || strpos($metaKey, 'mpn') !== false 
        ) {
            if(isset($gtin_plugins[$metaKey]) != null) {
                $gtin_values[$metaKey] = $gtin_plugins[$metaKey];
            } else {
                $gtin_values[$metaKey] = $metaKey;
            }
        }   
    }

    echo '<tr valign="top">';
    echo '<th> <label for="toggle_gtin">' . __('Enable GTIN Feed', 'skroutz-woocommerce-feed') . ' </label></th>';
    echo '<td><input style="margin-left:10px;" id="toggle_gtin" class="toggle_gtin" type="checkbox" name="enable_gtin" value="1" ' . ($enable_gtin == 1 ? "checked" : "") . ' /></td>';

    echo "</tr>";
    echo '<tr class="gtin" style="'. ($enable_gtin==1 ? '' : 'display:none') . '" valign="top">';
    echo '<th>' .  __('GTIN settings', 'skroutz-woocommerce-feed') . '</th>';
    echo '<td>';
    echo '<label>' . __('XML Tag Name', 'skroutz-woocommerce-feed') . ': ';
    echo '<select name="gtin_label">';
    echo '<option value="ean" ' . selected('ean' == $gtin_label, true, false) . '>Ean</option>';
    echo '<option value="barcode"' . selected('barcode' == $gtin_label, true, false) . '>Barcode</option>';
    echo '<option value="isbn"' . selected('isbn' == $gtin_label, true, false) . '>ISBN</option>';
    echo '</select></label> &nbsp;&nbsp;';

    echo '<label>' . __('GTIN Source Plugin', 'skroutz-woocommerce-feed') . ': ';
    echo '<select name="gtin_value">';
    echo "<option value='' " . selected($selected, true, false) . ">" . __('-Empty-', 'skroutz-woocommerce-feed') . "</option>";
    foreach ($gtin_values as $key => $gtin) {
        $selected = false; 
        if($key == $gtin_value) {
            $selected = true;
        }
        echo '<option value="'. esc_html($key) . '" ' . selected($selected, true, false) . '>' . esc_html($gtin) . '</option>';
    }
    echo '</select></label> &nbsp;&nbsp;'; 
    echo '</td>';
    echo '</tr>';

    $rollback = get_option('rollback');

    echo '<tr valign="top">';
    echo '<th> <label for="rollback">' . __('Rollback to previous version', 'skroutz-woocommerce-feed') . ' </label></th>';
    echo '<td><input style="margin-left:10px;" id="rollback" class="rollback" type="checkbox" name="rollback" value="1" ' . ($rollback == 1 ? "checked" : "") . ' />';
    echo "</br></br> <em>" . __('Select this <strong>ONLY</strong> if you already tried to product the XML feeds with the new 1.6.0. version of the plugin and experienced issues. <br/>The XML feeds will be produced the old way. Contact us at woordpress@enartia.com in order to resolve your issues before the next release.', 'skroutz-woocommerce-feed') . "</em>";
    echo '</td>';
    echo "</tr>";
    echo ' </table>';
    submit_button();
    echo '</form>';
    
    if(get_option('last_update')!="") {
        echo '<div class="feedsUrl" style="display: flex; flex-direction: column; max-width: 500px; margin: 0px 0 30px; padding: 10px 0; align-content: center;">';
        echo '<h3 style="margin: 0.3em 0;">' . __('XML Feed Urls:', 'skroutz-woocommerce-feed') . '</h3>';
        echo '<p style="">' . __('Last generated XML Feed time: ', 'skroutz-woocommerce-feed') . get_option('last_update'). '</p>';
        echo __('Skroutz XML Url: ', 'skroutz-woocommerce-feed') .' <a href="' . wp_upload_dir()['baseurl'] . '/skroutz/skroutz.xml" target="_blank">' . wp_upload_dir()['baseurl'] . '/skroutz/skroutz.xml</a></br>';
        echo __('Bestprice XML Url: ', 'skroutz-woocommerce-feed') . ' <a href="' . wp_upload_dir()['baseurl'] . '/best-price/bp.xml" target="_blank">' . wp_upload_dir()['baseurl'] . '/best-price/bp.xml</a>';
        echo '</div>';
    }

    echo '<a class="button button-primary" href="' . get_admin_url() . 'admin.php?page=skroutz_xml_create_page">' . __('Create XML Feeds', 'skroutz-woocommerce-feed') . '</a>';
    echo '</div>';
    
    wc_enqueue_js('
    $(".toggle_gtin").change(function() {
        if($(".toggle_gtin:checked").length) {
            $(".gtin").show();
        } else {
            $(".gtin").hide();

        }
      });');
}

function register_mysettings() { 
    register_setting('skroutz-group', 'instockavailability', 'sanitize_options');
    register_setting('skroutz-group', 'ifoutofstock', 'sanitize_options');
    register_setting('skroutz-group', 'include_tax');
    register_setting('skroutz-group', 'group_variations');    
    register_setting('skroutz-group', 'features', 'sanitize_options_multi');
    register_setting('skroutz-group', 'skroutz_atts_color', 'sanitize_options');
    register_setting('skroutz-group', 'skroutz_atts_manuf', 'sanitize_options');
    register_setting('skroutz-group', 'skroutz_atts_size', 'sanitize_options');
    register_setting('skroutz-group', 'enable_gtin');
    register_setting('skroutz-group', 'gtin_label', 'sanitize_options');
    register_setting('skroutz-group', 'gtin_value', 'sanitize_options');
    register_setting('skroutz-group', 'exclude_cats', 'sanitize_options_multi');
    register_setting('skroutz-group', 'custom_productId', 'sanitize_options');
    register_setting('skroutz-group', 'custom_mpn', 'sanitize_options');
    register_setting('skroutz-group', 'last_update', 'sanitize_options');
    register_setting('skroutz-group', 'rollback', 'sanitize_options');

}

function sanitize_options($input) {

    return esc_html($input);
}

function sanitize_options_multi($input) {

    $output = array();

    foreach ($input as $in_value) {
        $output[] = esc_html($in_value);
    }


    return $output;
}

function skroutz_xml_create_page() {

    $skicon = plugins_url('/', __FILE__) . '/images/skroutz.png';
    $bpicon = plugins_url('/', __FILE__) . '/images/bp.png';
    $skbpicon = plugins_url('/',__FILE__) . '/images/skroutz_bestprice.png';
    echo '<div>';
    echo '<h2>' . __('Create Feeds for Skroutz and Bestprice', 'skroutz-woocommerce-feed') . '</h2>';
    echo '</div>';

    settings_fields('skroutz-group');
    do_settings_sections('skroutz-group');
    $rollback = get_option('rollback') ? get_option('rollback') : '';
    

    if(empty($rollback)){
        generate_products_xml_data_new();
    } else {
        $active = 0;
        if ($active == 0 | $active == 1) {
            require_once 'createsk.php';
        }
        echo '</br>';
        if ($active == 0 | $active == 2) {
            require_once 'createbp.php';
        }


    }

    if (!wp_next_scheduled('skroutz_xml_hourly_event')) {
        wp_schedule_event(time(), 'hourly', 'skroutz_xml_hourly_event');
    }
}

add_action('skroutz_xml_hourly_event', 'skroutz_xml_do_this_hourly');
function is_parent($var) { return $var->parent == 0;}
function is_subcategory($var) { return $var->parent != 0;}
/**
 * On the scheduled action hook, run a function.
 */
function skroutz_xml_do_this_hourly() {
    // do something every hour
    $rollback = get_option('rollback') ? get_option('rollback') : '';
    
    if(empty($rollback)) {
        generate_products_xml_data_new();
    } else { 
        $active = 0; // get_option('activefeeds');
        if ($active == 0 | $active == 1) {
            require_once 'createsk.php';
        }
        if ($active == 0 | $active == 2) {
            require_once 'createbp.php';
        }
    }
    if (!wp_next_scheduled('skroutz_xml_hourly_event')) {
        wp_schedule_event(time(), 'hourly', 'skroutz_xml_hourly_event');
    }
}
function xml_schema($prod, $group_variations, $instockavailability,$avaibilities, $availabilityST, $ifoutofstock, $availabilitiesOutOfStock,  $noavailabilityST, $custom_productId, $custom_mpn, $skroutz_atts_color, $skroutz_atts_size, $skroutz_atts_manuf, $enable_gtin, $include_tax, $gtin_label, $gtin_value, $featureslist, $variation_atts, $attributes, $variable_extra=[], $parent=[]) {

    $product_id = $prod->get_id();

    if(!empty($custom_productId)) {
        $_id = get_post_meta( $prod->get_id(), $custom_productId, 1 ); 

        if(!empty($_id)) {
            $product_id = $_id;
        }
    }
    

    
    $split_color_variation = false;

    if(!empty($variable_extra)) {
        $split_color_variation = true;
        $product_id = $variable_extra['id'] ? $variable_extra['id'] : $prod->get_id();
        $colorTerm = get_term_by('slug', $variable_extra[$skroutz_atts_color], $skroutz_atts_color);
    }

    $format_price = false;
    if (function_exists('wc_get_price_decimal_separator') && function_exists('wc_get_price_thousand_separator') && function_exists('wc_get_price_decimals')) {
        $decimal_separator = wc_get_price_decimal_separator();
        $thousand_separator = wc_get_price_thousand_separator();
        $decimals = wc_get_price_decimals();
        $format_price = true;
    }
    $xml_rows = array();

    

    $stockstatus_ds = $prod->get_stock_status();
        if ((strcmp($stockstatus_ds, "outofstock") == 0 || strcmp($stockstatus_ds, "onbackorder") == 0) & ($ifoutofstock == 1)) {
            return;
        }
        $onfeed = $prod->get_meta('onfeed');

        if($split_color_variation && !empty($parent)) {
            $excludempn = $parent->get_meta('excludempn');
        }else {
            $excludempn = $prod->get_meta('excludempn');
        }
        
        if (strcmp($onfeed, "no") == 0) {
            return;
        }
        $xml_rows[$product_id] = array(
            'onfeed' => $onfeed,
            'excludempn' => $excludempn,
            'stockstatus' => $stockstatus_ds,
            'attributes' => $attributes
        );
        
        switch ($instockavailability) {
            case 3:
                $_product_attributes_ser_ds = $attributes;

                if (is_serialized($_product_attributes_ser_ds)) {
                    $_product_attributes = unserialize($_product_attributes_ser_ds);
                    foreach ($_product_attributes as $key => $attr) {
                        if ($attr['name'] == 'Διαθεσιμότητα') {
                            $availabilityST = $attr['value'];
                            break;
                        }
                    }
                } else if(is_array($_product_attributes_ser_ds) && !empty($_product_attributes_ser_ds)){

                    foreach($_product_attributes_ser_ds as $key => $attr) {
                        if($key == 'pa_availability')  {
                            if(!empty($parent) && $split_color_variation) {
                                $availabilityST = $parent->get_attribute($key);
                            } else {
                                $availabilityST = $prod->get_attribute($key);
                            }
                            break;
                        }                   
                    }
                }
                break;
            case 4:
                $tmp_availability = $prod->get_meta('_custom_availability');
                if ($tmp_availability != '') {
                    $availabilityST = $tmp_availability;
                }
                break;
            default:
                break;
        }
        $xml_rows[$product_id]['availabilityST'] = ($availabilityST == 'attribute'|| $availabilityST == 'χαρακτηριστικό') ? '' : $availabilityST;


        if($ifoutofstock == 4) {
            $_product_attributes_ser_ds = $attributes;

            if(is_array($_product_attributes_ser_ds) && !empty($_product_attributes_ser_ds)){

                foreach($_product_attributes_ser_ds as $key => $attr) {
                    if($key == 'pa_outofstockavailability')  {
                        $noavailabilityST = $prod->get_attribute($key);
                        break;
                    }                   
                }
            }
        }
        $xml_rows[$product_id]['noavailabilityST'] = ($noavailabilityST == 'Attribute: Out of Stock Availability'|| $noavailabilityST == 'Ιδιότητα: Out of Stock Διαθεσιμότητα') ? '' : $noavailabilityST;
        $tax = 0;
        if($include_tax) {
            $price = wc_get_price_excluding_tax($prod);
            $price = floatval( $price); 
            $tax_rates = WC_Tax::get_base_tax_rates( $prod->get_tax_class() );
            $taxes = WC_Tax::calc_tax($price,  $tax_rates, false, false);
            if(!empty($tax_rates)){
                foreach ($taxes as $taxes => $tax) {
                   $price += $tax;        
                }
            } else {
                $price = $prod->get_price();
            }
            
        } else {
            $price = $prod->get_price();
        }
        
        $xml_rows[$product_id]['price_raw'] = $price;
        if ($format_price && $price!='') {
            $price = number_format(floatval($price), $decimals, $decimal_separator, $thousand_separator);
        }
        $xml_rows[$product_id]['price'] = addslashes($price);
        $image_ds = get_the_post_thumbnail_url($prod->get_id(), 'shop_catalog');
        $xml_rows[$product_id]['image_ds'] = $image_ds;
        $image_big = get_the_post_thumbnail_url($prod->get_id(), 'shop_single_image_size');
        $xml_rows[$product_id]['image_big'] = $image_big;

        // sku and mpn
        $skus_ds = $prod->get_sku();
        $xml_rows[$product_id]['skus_ds'] = $skus_ds;

        if(!empty($custom_mpn)) {
            $_mpn = get_post_meta( $prod->get_id(), $custom_mpn, 1 ); 
            if(!empty($parent) && $split_color_variation && empty($_mpn)) {
                $_mpn = get_post_meta($parent->get_id(), $custom_mpn, 1 );
            }

            $xml_rows[$product_id]['mpn'] = $_mpn;
    
        } 

        $categories_ds = $prod->get_category_ids();
        $_weight_ds = $prod->get_weight();
        $_weight_ds = wc_get_weight( $_weight_ds, 'g', get_option('woocommerce_weight_unit') );
        $xml_rows[$product_id]['_weight_ds'] = $_weight_ds;
        
        if($enable_gtin && !empty($gtin_value)) {
            $val = get_post_meta( $prod->get_id(), $gtin_value, 1 );   
            if(!empty($parent) && $split_color_variation && empty($val)) {
                $val = get_post_meta($parent->get_id(), $gtin_value, 1 );
            }      
            $xml_rows[$product_id]['gtin'][$gtin_label] = !empty($val) ? $val : '';            
        }
        $gallery_ids = $prod->get_gallery_image_ids('view');
        if(!empty($gallery_ids)) {
            $xml_rows[$product_id]['additional_image'] = array();
            foreach($gallery_ids as $id) {
                $xml_rows[$product_id]['additional_image'][] = wp_get_attachment_url($id, 'full');
            }
        }
        $sizestring = '';
        $xml_rows[$product_id]['sizes'] = array();

        if($split_color_variation) {

            if(isset($variable_extra['image']) && !empty($variable_extra['image'])) {
                $var_image = $variable_extra['image'];
            } else {
                $var_image = wp_get_attachment_url($prod->get_image_id());
            }
            $xml_rows[$product_id]['image_big'] = $var_image;
            
            if(isset($variable_extra[$skroutz_atts_size])){
                $sizes_temp = array();

                foreach ($variable_extra[$skroutz_atts_size] as $size_term) {
                    $termObj = get_term_by('slug', $size_term, $skroutz_atts_size);    
                    $sizes_temp[] = format_number_skroutz($termObj->name);
                }

               if(empty($variable_extra[$skroutz_atts_size]) || (count(array_unique($sizes_temp)) == 1 && ($sizes_temp[0] == ''))) {

                    if (isset($attributes[$skroutz_atts_size]) && $attributes[$skroutz_atts_size] != null) {
                        $sizes = $attributes[$skroutz_atts_size]->get_terms();
                        $sizes_temp = array();
                        foreach ($sizes as $i => $size_term) {
                            $sizes_temp[] = format_number_skroutz($size_term->name);
                        }
                    }

               }

                $xml_rows[$product_id]['sizes'] = array_unique($sizes_temp);
                $sizestring = implode(', ', $xml_rows[$product_id]['sizes']);
            }
        } else {
            if(count($variation_atts[$skroutz_atts_size])>0){
                $sizes_temp = array();
                foreach ($variation_atts[$skroutz_atts_size] as $size_term) {
                    $termObj = get_term_by('slug', $size_term, $skroutz_atts_size);
                    $sizes_temp[] = format_number_skroutz($termObj->name);
                }
                $xml_rows[$product_id]['sizes'] = array_unique($sizes_temp);
                $sizestring = implode(', ', $xml_rows[$product_id]['sizes']);
            }
            else{
                if (isset($attributes[$skroutz_atts_size]) && $attributes[$skroutz_atts_size] != null) {
                    $sizes = $attributes[$skroutz_atts_size]->get_terms();
                    $sizes_temp = array();
                    foreach ($sizes as $i => $size_term) {
                        $sizes_temp[] = format_number_skroutz($size_term->name);
                    }
                    $xml_rows[$product_id]['sizes'] = array_unique($sizes_temp);
                    $sizestring = implode(', ', $xml_rows[$product_id]['sizes']);
                }
            }
        }        
        $xml_rows[$product_id]['sizestring'] = $sizestring;
        $man = '';

        if (isset($attributes[$skroutz_atts_manuf]) && $attributes[$skroutz_atts_manuf] != null) {
            $brands = $attributes[$skroutz_atts_manuf]->get_terms();
            foreach ($brands as $brand_term) {
                $man = $brand_term->name;
            }
        } else if( $skroutz_atts_manuf !== 'brand') {
            $terms = wp_get_object_terms($prod->get_id(),  $skroutz_atts_manuf, array("fields"=>"all"));
 
            if(!is_wp_error($terms)) {
                if(!empty($terms)) {
                    $man = $terms[0]->name;
                } else {

                    if(!empty($parent) && $split_color_variation) {
                            $terms = wp_get_object_terms($parent->get_id(),  $skroutz_atts_manuf, array("fields"=>"all"));
                        if(!is_wp_error($terms)) {
                            if(!empty($terms)) {
                                $man = $terms[0]->name;
                            }
                        }
                    }

                }
            }

        }

        $xml_rows[$product_id]['manufacturer'] = $man;
        $colorRes = '';
        $xml_rows[$product_id]['colors'] = array();

        if($split_color_variation){
            $xml_rows[$product_id]['colorstring'] =  $colorTerm->name;
        } else {
            if(count($variation_atts[$skroutz_atts_color])>0){
                $colors_temp = array();
                foreach ($variation_atts[$skroutz_atts_color] as $color_term) {
                    $colorTerm = get_term_by('slug', $color_term, $skroutz_atts_color);
                    $colors_temp[] = $colorTerm->name;
                }
                $xml_rows[$product_id]['colors'] = array_unique($colors_temp);
                $colorRes = implode(', ', $xml_rows[$product_id]['colors']);
            }
            else{
                if (isset($attributes[$skroutz_atts_color]) && $attributes[$skroutz_atts_color] != null) {
                    $colors = $attributes[$skroutz_atts_color]->get_terms();
                    $colors_temp = array();

                    foreach ($colors as $color_term) {
                        $colors_temp[] = $color_term->name;
                        // $colorRes .= $color_term->name . ', ';
                    }
                $xml_rows[$product_id]['colors'] = array_unique($colors_temp);
                $colorRes = implode(', ', $xml_rows[$product_id]['colors']);

                }
            }
            $xml_rows[$product_id]['colorstring'] = $colorRes;
        }
        $xml_rows[$product_id]['terms'] = array();

        foreach ($featureslist as $key => $feature) {
            $xml_rows[$product_id]['terms'][$key] = array();
            if(isset($attributes[$feature])) {
                $prod_terms = $attributes[$feature]->get_terms();
                if(is_array($prod_terms)){
                    foreach ($prod_terms as $the_term) {
                        $xml_rows[$product_id]['terms'][$feature][$the_term->slug] = $the_term->name;
                    }
                }
            }
        }
        $xml_rows[$product_id]['categories'] = array();
        $category_path = '';
        $categories_list = array();

        $prod_category_tree = get_the_terms($prod->get_id(), 'product_cat');
        if(empty($prod_category_tree) && $split_color_variation && !empty($parent)) {

            $prod_category_tree = get_the_terms($parent->get_id(), 'product_cat'); 
        }

        if(!empty($prod_category_tree)) {
            array_push($categories_list,  __('Home', 'skroutz-woocommerce-feed'));
            $subcategories = array_filter($prod_category_tree, "is_subcategory");
           
            if (!empty($subcategories)) {
                $only_one_cat = end($subcategories);
            } else {
                $only_one_cat = end($prod_category_tree);
            }

            $get_tree = array_reverse(get_ancestors($only_one_cat->term_id, 'product_cat', 'taxonomy'));

            foreach ($get_tree as $key => $parentCat) {
                $term = get_term_by('id', $parentCat, 'product_cat');
                array_push($categories_list, $term->name);
            }
            array_push($categories_list, $only_one_cat->name);
            $category_path = implode(', ', $categories_list);
            $xml_rows[$product_id]['category_id'] = $only_one_cat->term_id;
        }
        $xml_rows[$product_id]['category_path'] = $category_path;
        $title = str_replace("'", " ", $prod->get_title());
        $title = str_replace("&", "+", $title);
        $title = strip_tags($title);
        if($split_color_variation) {
            $xml_rows[$product_id]['title'] = $title . ' ' . $colorTerm->name;
            $xml_rows[$product_id]['link'] = $variable_extra['link'];
        } else {
            $xml_rows[$product_id]['title'] = $title;
            $xml_rows[$product_id]['link'] = get_permalink($prod->get_id());
        }
        $backorder = $prod->get_backorders();
        $xml_rows[$product_id]['backorder'] = $backorder;
        $xml_rows[$product_id]['descr'] = $prod->get_short_description();
        if(empty($xml_rows[$product_id]['descr']) && !empty($parent)) {
            $xml_rows[$product_id]['descr'] = $parent->get_short_description();
        }
    return $xml_rows;
}

function generate_products_xml_data_new() {
    //********* Start of initialization of xml files *****************/
    if (!defined('ABSPATH'))
        exit; // Exit if accessed directly
    require_once( 'simplexml.php' );
    global $wpdb;

    /*******************************
     ******** BESTPRICE.GR *********
     ******************************/
    if (!file_exists(wp_upload_dir()['basedir'] . '/best-price')) {
        wp_mkdir_p(wp_upload_dir()['basedir'] . '/best-price');
    }
    if (!file_exists(wp_upload_dir()['basedir'] . '/best-price/bp.xml')) {
        touch(wp_upload_dir()['basedir'] . '/best-price/bp.xml');
    }
    if (file_exists(wp_upload_dir()['basedir'] . '/best-price/bp.xml')) {
        $xmlFileBestprice = wp_upload_dir()['basedir'] . '/best-price/bp.xml';
    } else {
        echo "Could not create Bestprice file.";
    }
    $xmlBestprice = new feed_SimpleXMLExtended('<?xml version="1.0" encoding="utf-8"?><webstore/>');
    $now = date('Y-n-j G:i');
    $xmlBestprice->addChild('date', "$now");
    $productsBestprice = $xmlBestprice->addChild('products');
    $featureslist = get_option('features', []);

    /*******************************
     ******** SKROUTZ.GR *********
     ******************************/

    if (!file_exists(wp_upload_dir()['basedir'] . '/skroutz')) {
        wp_mkdir_p(wp_upload_dir()['basedir'] . '/skroutz');
    }
    
    if (!file_exists(wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml')) {
        touch(wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml');
    }
    
    if (file_exists(wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml')) {
        $xmlFileSkroutz = wp_upload_dir()['basedir'] . '/skroutz/skroutz.xml';
    } else {
        echo "Could not create skroutz file.";
    }

    $xmlSkroutz = new feed_SimpleXMLExtended('<?xml version="1.0" encoding="utf-8"?><webstore/>');
    // $now = date('Y-n-j G:i');
    $xmlSkroutz->addChild('created_at', "$now");
    $productsSkroutz = $xmlSkroutz->addChild('products');


    //********* End of initialization of xml files *****************/

    $xml_rows = array();
    $instockavailability = get_option('instockavailability');
    $avaibilities = array(
        __('Available in store / Delivery 1 to 3 days', 'skroutz-woocommerce-feed'), 
        __('Delivery 1 to 3 days', 'skroutz-woocommerce-feed'), 
        __('Delivery 4 to 10 days', 'skroutz-woocommerce-feed'), 
        __('attribute', 'skroutz-woocommerce-feed'));

    $availabilityST = $avaibilities[$instockavailability];
    $ifoutofstock = get_option('ifoutofstock');
    $availabilitiesOutOfStock = array(
        __('Include as out of Stock or Upon Request', 'skroutz-woocommerce-feed'), 
        __('Exclude from feed', 'skroutz-woocommerce-feed'),
        __('Delivery 1 to 3 days', 'skroutz-woocommerce-feed'),
        __('Delivery 4 to 10 days', 'skroutz-woocommerce-feed'),
        __('Attribute: Out of Stock Availability', 'skroutz-woocommerce-feed')
    );
    $noavailabilityST = $availabilitiesOutOfStock[$ifoutofstock];

    $format_price = false;
    if (function_exists('wc_get_price_decimal_separator') && function_exists('wc_get_price_thousand_separator') && function_exists('wc_get_price_decimals')) {
        $decimal_separator = wc_get_price_decimal_separator();
        $thousand_separator = wc_get_price_thousand_separator();
        $decimals = wc_get_price_decimals();
        $format_price = true;
    }
    
    $skroutz_atts_color = get_option('skroutz_atts_color', 'pa_color');
    $skroutz_atts_size = get_option('skroutz_atts_size', 'pa_size');    
    $skroutz_atts_manuf =  get_option('skroutz_atts_manuf', 'brand');
    $enable_gtin = get_option('enable_gtin', false);
    $include_tax = get_option('include_tax', false);
    $group_variations = get_option('group_variations', false);
    $gtin_label = get_option('gtin_label' , 'ean');
    $gtin_value = get_option('gtin_value', '');
    $cats_excluded = get_option('exclude_cats', []);
    $custom_productId = get_option('custom_productId');
    $custom_mpn = get_option('custom_mpn');

    $i=1;
    try{
        do {
            $query = new WC_Product_Query(
                array(
                'status' => array('publish'), 
                'limit' => 300,
                'paginate' => true,
                'page' => $i
            ));
            if(count($cats_excluded) > 0) {
                $query->set('tax_query', array(array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $cats_excluded,
                    'operator' => ('NOT IN'))));
            }
            
            $result = $query->get_products();
            $color_term_ids = array();
            foreach ($result->products as $index => $prod) {
                $availabilityST = $avaibilities[$instockavailability];
                $noavailabilityST = $availabilitiesOutOfStock[$ifoutofstock];
                $available_variations = array();
                $attributes = $prod->get_attributes();
                $group_colors = false;
                $variable_products = [];
                
                $onfeed = $prod->get_meta('onfeed');
                if (strcmp(strtolower($onfeed), "no") == 0) {
                    continue;
                }

                if($prod->get_type()=='variable'){
                    $available_variations = $prod->get_available_variations();
                    $variation_prices = $prod->get_variation_prices();
                    if(isset($attributes[$skroutz_atts_color]) && !empty($attributes[$skroutz_atts_color])) {
                        $group_colors = count($attributes[$skroutz_atts_color]['data']['options']) >= 1 ? true : false;
                    }
                }
        
                $variation_atts = array($skroutz_atts_color=>array(),$skroutz_atts_size=>array());
        
                foreach($available_variations as $var){
                    $var_product = wc_get_product($var['variation_id']);
                    $var_stock_status = $var_product->get_stock_status();
                    if(isset($var_stock_status) && $var_stock_status == 'outofstock'){
                        continue;
                    }       
                    // old one - legacy
                    if(isset($var['stock_status']) && $var['stock_status'] == 'outofstock') {
                        continue;
                    } 
        
                    $atts = $var['attributes'];

                    if(isset($atts['attribute_'.$skroutz_atts_size]) && $atts['attribute_'.$skroutz_atts_size]!=''){
                        $variation_atts[$skroutz_atts_size][]=$atts['attribute_'.$skroutz_atts_size];
                    }
        
                    if(isset($atts['attribute_'.$skroutz_atts_color]) && $atts['attribute_'.$skroutz_atts_color]!=''){
                        $variation_atts[$skroutz_atts_color][]=$atts['attribute_'.$skroutz_atts_color];
                    }
                    if($group_variations && $group_colors) {
                        if(isset($var['attributes']['attribute_'.$skroutz_atts_color])) {
                            if(!isset($color_term_ids[$var['attributes']['attribute_'.$skroutz_atts_color]])) {
                                $color_term_ids[$var['attributes']['attribute_'.$skroutz_atts_color]] = get_term_by('slug', $var['attributes']['attribute_'.$skroutz_atts_color], $skroutz_atts_color)->term_id;
                            }
                            $varId = $prod->get_id(). '-' . $color_term_ids[$var['attributes']['attribute_'.$skroutz_atts_color]];        
                            if(!isset($variable_products[$varId])){
                                $variable_products[$varId]['id'] = $varId;
                                $variable_products[$varId][$skroutz_atts_color] = $var['attributes']['attribute_'.$skroutz_atts_color];
        
                                if(isset($var['attributes']['attribute_'.$skroutz_atts_size])) {
                                    $variable_products[$varId][$skroutz_atts_size][] = $var['attributes']['attribute_'.$skroutz_atts_size];
                                    
                                }
                                    
                                if(!empty($var['image'])) {
                                    $variable_products[$varId]['image'] = $var['image']['url'];
                                }
                                $variable_products[$varId]['price'] = $var['display_price']; //needs to check for taxes
                                $variable_products[$varId]['link'] = get_permalink($prod->get_id()) . '?attribute_' . $skroutz_atts_color .'=' . $var['attributes']['attribute_'.$skroutz_atts_color];
                                $variable_products[$varId]['product'] = $var_product;
        
                            } else {
                                if(isset($var['attributes']['attribute_'.$skroutz_atts_size])) {
                                    $variable_products[$varId][$skroutz_atts_size][] = $var['attributes']['attribute_'.$skroutz_atts_size];
                                }
                                if(!isset($variable_products[$varId]['image']) && !empty($var['image'])){
                                    $variable_products[$varId]['image'] = $var['image']['url'];
                                }
        
                                if($variable_products[$varId]['price'] > $var['display_price']) {
                                    $variable_products[$varId]['price'] = $var['display_price'];
                                    $variable_products[$varId]['product'] = $var_product;
                                }
                            }
        
                        }
                    } 
                }
                if(!empty($variable_products)) {    
                    foreach ($variable_products as $key => $product) {
                        $xml = xml_schema($product['product'], $group_variations, $instockavailability,$avaibilities, $availabilityST, $ifoutofstock, $availabilitiesOutOfStock,  $noavailabilityST, $custom_productId, $custom_mpn, $skroutz_atts_color, $skroutz_atts_size, $skroutz_atts_manuf, $enable_gtin, $include_tax, $gtin_label, $gtin_value, $featureslist, $variation_atts, $attributes, $product, $prod);
                        write_skroutz_xml($xml, $productsSkroutz, $instockavailability, $ifoutofstock);
                        write_bestprice_xml($xml, $productsBestprice, $featureslist, $instockavailability, $ifoutofstock);
                    }
                    continue; // no need to do somthing more
                } 
                $xml = xml_schema($prod, $group_variations, $instockavailability,$avaibilities, $availabilityST, $ifoutofstock,$availabilitiesOutOfStock,  $noavailabilityST, $custom_productId,$custom_mpn, $skroutz_atts_color, $skroutz_atts_size, $skroutz_atts_manuf, $enable_gtin, $include_tax, $gtin_label, $gtin_value, $featureslist, $variation_atts, $attributes);
                write_skroutz_xml($xml, $productsSkroutz, $instockavailability, $ifoutofstock);
                write_bestprice_xml($xml, $productsBestprice, $featureslist, $instockavailability, $ifoutofstock);
        
            }
            $i++;
        } while ( $i <= $result->max_num_pages);
    } catch(Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    /**** end processes of xml creation  */
    echo '</br>' . __('SUCCESSFUL CREATION OF Skroutz XML', 'skroutz-woocommerce-feed') . '</br>';
    $xmlSkroutz->saveXML($xmlFileSkroutz);
    echo __('The file is located at', 'skroutz-woocommerce-feed') .' <a href="' . wp_upload_dir()['baseurl'] . '/skroutz/skroutz.xml" target="_blank">' . wp_upload_dir()['baseurl'] . '/skroutz/skroutz.xml</a>';

    echo '</br>' . __('SUCCESSFUL CREATION OF BestPrice XML', 'skroutz-woocommerce-feed') . '</br>';

    $xmlBestprice->saveXML($xmlFileBestprice);
    echo __('The file is located at', 'skroutz-woocommerce-feed') . ' <a href="' . wp_upload_dir()['baseurl'] . '/best-price/bp.xml" target="_blank">' . wp_upload_dir()['baseurl'] . '/best-price/bp.xml</a>';
    
    update_option('last_update', date('d-m-Y H:i'));

     return $xml_rows;

}

function generate_products_xml_data() {
    $xml_rows = array();
    $instockavailability = get_option('instockavailability');
    $avaibilities = array(
        __('Available in store / Delivery 1 to 3 days', 'skroutz-woocommerce-feed'), 
        __('Delivery 1 to 3 days', 'skroutz-woocommerce-feed'), 
        __('Delivery 4 to 10 days', 'skroutz-woocommerce-feed'), 
        __('attribute', 'skroutz-woocommerce-feed')
    );

    $availabilityST = $avaibilities[$instockavailability];
    $ifoutofstock = get_option('ifoutofstock');
    $format_price = false;
    if (function_exists('wc_get_price_decimal_separator') && function_exists('wc_get_price_thousand_separator') && function_exists('wc_get_price_decimals')) {
        $decimal_separator = wc_get_price_decimal_separator();
        $thousand_separator = wc_get_price_thousand_separator();
        $decimals = wc_get_price_decimals();
        $format_price = true;
    }
    
    $skroutz_atts_color = get_option('skroutz_atts_color', 'pa_color');
    $skroutz_atts_size = get_option('skroutz_atts_size', 'pa_size');
    $skroutz_atts_manuf =  get_option('skroutz_atts_manuf', 'brand');
    $enable_gtin = get_option('enable_gtin', false);
    $gtin_label = get_option('gtin_label' , 'ean');
    $gtin_value = get_option('gtin_value', '');

    $result = wc_get_products(array('status' => array('publish'), 'limit' => -1));
    foreach ($result as $index => $prod) {

        $availabilityST = $avaibilities[$instockavailability];
        $available_variations = array();
        if($prod->get_type()=='variable'){
            $available_variations = $prod->get_available_variations();
        }

        $attributes = $prod->get_attributes();
        $variation_atts = array($skroutz_atts_color=>array(),$skroutz_atts_size=>array());
        foreach($available_variations as $var){
            if(isset($var['stock_status']) && $var['stock_status']=='outofstock') continue;
            $atts = $var['attributes'];
            if(isset($atts['attribute_'.$skroutz_atts_size]) && $atts['attribute_'.$skroutz_atts_size]!=''){
                $variation_atts[$skroutz_atts_size][]=$atts['attribute_'.$skroutz_atts_size];
            }
            if(isset($atts['attribute_'.$skroutz_atts_color]) && $atts['attribute_'.$skroutz_atts_color]!=''){
                $variation_atts[$skroutz_atts_color][]=$atts['attribute_'.$skroutz_atts_color];
            }
        }

        $stockstatus_ds = $prod->get_stock_status();
        if ((strcmp($stockstatus_ds, "outofstock") == 0) & ($ifoutofstock == 1)) {
            continue;
        }
        $onfeed = $prod->get_meta('onfeed');
        if (strcmp($onfeed, "no") == 0) {
            continue;
        }
        $xml_rows[$prod->get_id()] = array(
            'onfeed' => $onfeed,
            'stockstatus' => $stockstatus_ds,
            'attributes' => $attributes
        );

        switch ($instockavailability) {
            case 3:
                $_product_attributes_ser_ds = $attributes;

                if (is_serialized($_product_attributes_ser_ds)) {
                    $_product_attributes = unserialize($_product_attributes_ser_ds);
                    foreach ($_product_attributes as $key => $attr) {
                        if ($attr['name'] == 'Διαθεσιμότητα') {
                            $availabilityST = $attr['value'];
                            break;
                        }
                    }
                } else if(is_array($_product_attributes_ser_ds) && !empty($_product_attributes_ser_ds)){

                    foreach($_product_attributes_ser_ds as $key => $attr) {
                        if($key == 'pa_availability')  {
                            $availabilityST = $prod->get_attribute($key);
                            break;
                        }                   
                    }
                }
                break;
            case 4:
                $tmp_availability = $prod->get_meta('_custom_availability');
                if ($tmp_availability != '') {
                    $availabilityST = $tmp_availability;
                }
                break;
            default:
                break;
        }
        $xml_rows[$prod->get_id()]['availabilityST'] = $availabilityST == 'attribute' ? '' : $availabilityST;
        $price = $prod->get_price();
        $xml_rows[$prod->get_id()]['price_raw'] = $price;
        if ($format_price && $price!='') {
            $price = number_format(floatval($price), $decimals, $decimal_separator, $thousand_separator);
        }
        $xml_rows[$prod->get_id()]['price'] = addslashes($price);
        $image_ds = get_the_post_thumbnail_url($prod->get_id(), 'shop_catalog');
        $xml_rows[$prod->get_id()]['image_ds'] = $image_ds;
        $image_big = get_the_post_thumbnail_url($prod->get_id(), 'shop_single_image_size');
        $xml_rows[$prod->get_id()]['image_big'] = $image_big;
        $skus_ds = $prod->get_sku();
        $xml_rows[$prod->get_id()]['skus_ds'] = $skus_ds;
        $categories_ds = $prod->get_category_ids();
        $_weight_ds = $prod->get_weight();
        $xml_rows[$prod->get_id()]['_weight_ds'] = $_weight_ds;

        if($enable_gtin && !empty($gtin_value)) {
            $val = get_post_meta( $prod->get_id(), $gtin_value, 1 );         
            $xml_rows[$prod->get_id()]['gtin'][$gtin_label] = !empty($val) ? $val : '';            
        }

        $gallery_ids = $prod->get_gallery_image_ids('view');
        if(!empty($gallery_ids)) {
            $xml_rows[$prod->get_id()]['additional_image'] = array();
            foreach($gallery_ids as $id) {
                $xml_rows[$prod->get_id()]['additional_image'][] = wp_get_attachment_url($id, 'full');
            }
        }
        $sizestring = '';
        $xml_rows[$prod->get_id()]['sizes'] = array();
        if(count($variation_atts[$skroutz_atts_size])>0){
            foreach ($variation_atts[$skroutz_atts_size] as $size_term) {
                $termObj = get_term_by('slug', $size_term, $skroutz_atts_size);
                $sizestring .= format_number_skroutz($termObj->name) . ', ';
                $xml_rows[$prod->get_id()]['sizes'][] = format_number_skroutz($termObj->name);
            }
        }
        else{
            if (isset($attributes[$skroutz_atts_size]) && $attributes[$skroutz_atts_size] != null) {
                $sizes = $attributes[$skroutz_atts_size]->get_terms();
                foreach ($sizes as $i => $size_term) {
                    $sizestring .= format_number_skroutz($size_term->name) . ', ';
                    $xml_rows[$prod->get_id()]['sizes'][] = format_number_skroutz($size_term->name);
                }
            }
        }
        if (strlen($sizestring) > 2) {
            $sizestring = substr($sizestring, 0, -2);
        }
        $xml_rows[$prod->get_id()]['sizestring'] = $sizestring;
        $man = '';

        if (isset($attributes[$skroutz_atts_manuf]) && $attributes[$skroutz_atts_manuf] != null) {
            $brands = $attributes[$skroutz_atts_manuf]->get_terms();
            foreach ($brands as $brand_term) {
                $man = $brand_term->name;
            }
        } else if( $skroutz_atts_manuf !== 'brand') {
            $terms = wp_get_object_terms($prod->get_id(),  $skroutz_atts_manuf, array("fields"=>"all"));
            if(!is_wp_error($terms)) {
                if(!empty($terms)) {
                    $man = $terms[0]->name;
                }
            }

        }

        $xml_rows[$prod->get_id()]['manufacturer'] = $man;
        $colorRes = '';
        $xml_rows[$prod->get_id()]['colors'] = array();
        if(count($variation_atts[$skroutz_atts_color])>0){
            foreach ($variation_atts[$skroutz_atts_color] as $color_term) {
                $xml_rows[$prod->get_id()]['colors'][] = format_number_skroutz($color_term);
                $colorRes .= $color_term . ', ';
            }
        }
        else{
            if (isset($attributes[$skroutz_atts_color]) && $attributes[$skroutz_atts_color] != null) {
                $colors = $attributes[$skroutz_atts_color]->get_terms();
                foreach ($colors as $color_term) {
                    $colorRes .= $color_term->name . ', ';
                    $xml_rows[$prod->get_id()]['colors'][] = $color_term->name;
                }
            }
        }
        if (strlen($colorRes) > 2) {
            $colorRes = substr($colorRes, 0, -2);
        }
        $xml_rows[$prod->get_id()]['colorstring'] = $colorRes;
        $xml_rows[$prod->get_id()]['terms'] = array();
        foreach ($attributes as $att_key => $prod_att) {
            $xml_rows[$prod->get_id()]['terms'][$att_key] = array();
            $prod_terms = $prod_att->get_terms();
            if(is_array($prod_terms)){
                foreach ($prod_terms as $the_term) {
                    $xml_rows[$prod->get_id()]['terms'][$att_key][] = $the_term->name;
                }
            }

        }
        $prod_category_tree = array_map('get_term', array_reverse(wc_get_product_cat_ids($prod->get_id())));
        $xml_rows[$prod->get_id()]['categories'] = array();
        $category_path = '';
        for ($i = 0; $i < count($prod_category_tree); $i++) {
            if ($i == 0) {
                $xml_rows[$prod->get_id()]['category_id'] = $prod_category_tree[$i]->term_id;
            }
            $category_path.=$prod_category_tree[$i]->name;
            $xml_rows[$prod->get_id()]['categories'][] = $prod_category_tree[$i]->name;
            if ($i < count($prod_category_tree) - 1)
                $category_path.=', ';
        }
        $xml_rows[$prod->get_id()]['category_path'] = $category_path;
        $title = str_replace("'", " ", $prod->get_title());
        $title = str_replace("&", "+", $title);
        $title = strip_tags($title);
        $xml_rows[$prod->get_id()]['title'] = $title;
        $xml_rows[$prod->get_id()]['link'] = get_permalink($prod->get_id());
        $backorder = $prod->get_backorders();
        $xml_rows[$prod->get_id()]['backorder'] = $backorder;
        $xml_rows[$prod->get_id()]['descr'] = $prod->get_short_description();
    }
    return $xml_rows;
}

function write_skroutz_xml($prod, $products, $instockavailability, $ifoutofstock) {
    foreach ($prod as $prod_id => $row) {
        $product = $products->addChild('product');

        $product->mpn = NULL;

        if($row['excludempn'] != 'yes') {
            if(isset($row['mpn'])) {
                $product->mpn->addCData($row['mpn']);
            } else if (addslashes(trim($row['skus_ds'])) != '') {
                $product->mpn->addCData($row['skus_ds']);
            } else {
                $product->mpn->addCData($prod_id);
            }
        }
        if(isset($row['gtin'])) {
            $label = array_keys($row['gtin'])[0];
            $product->addChild($label)->addCData($row['gtin'][$label]);
        }

        $product->addChild('uid', $prod_id);
        $product->name = NULL;
        $product->name->addCData($row['title']);
        $product->link = NULL;
        $product->link->addCData($row['link']);

        $product->image = NULL;
        $product->image->addCData($row['image_big']);

        $product->category = NULL;
        $product->category->addCData($row['category_path']);
        if(isset($row['additional_image'])) {
            foreach($row['additional_image'] as $id) {
                $product->addChild('additional_image')->addCData($id);
                }
            }
        $product->addChild('price', $row['price']);


        if (strcmp($row['stockstatus'], "instock") == 0) {
            $product->addChild('instock', "Y");
            $product->addChild('availability', $row['availabilityST']);
        } else {
            
            if (strcmp($row['backorder'], "notify") == 0) {
                $product->addChild('instock', "N");

                if($ifoutofstock == 0) {
                    $product->addChild('availability', __('Delivery up to 30 days', 'skroutz-woocommerce-feed'));
                } else {
                    if(isset($row['noavailabilityST'])) {
                        $product->addChild('availability', $row['noavailabilityST']);
                    } else {
                        $product->addChild('availability', __('Delivery up to 30 days', 'skroutz-woocommerce-feed'));
                    }
                }
            } else if (strcmp($row['backorder'], "yes") == 0) {
                $product->addChild('instock', "Y");
                $product->addChild('availability', $row['availabilityST']);
            } else {
                $product->addChild('instock', "N");
                if($ifoutofstock == 0) {
                    $product->addChild('availability', __('Delivery up to 30 days', 'skroutz-woocommerce-feed'));
                } else {
                    if(isset($row['noavailabilityST'])) {
                        $product->addChild('availability', $row['noavailabilityST']);
                    } else {
                        $product->addChild('availability', __('Delivery up to 30 days', 'skroutz-woocommerce-feed'));
                    }
                }
            }
        }
        $product->addChild('size', $row['sizestring']);

        $product->manufacturer = NULL;
        $product->manufacturer->addCData($row['manufacturer']);

        if($row['colorstring'] != '') {
            $product->color = NULL;
            $product->color->addCData($row['colorstring']);
        }
        if( $row['_weight_ds']> 0) {
            $product->addChild('weight', $row['_weight_ds']);
        }

        $product->description = NULL;
        $product->description->addCData($row['descr']);

    }    
}

function write_bestprice_xml($prod, $products, $featureslist,  $instockavailability, $ifoutofstock) {
    foreach ($prod as $prod_id => $row) {
        $product = $products->addChild('product');
        $product->mpn = NULL;


        if($row['excludempn'] != 'yes') {
            if(isset($row['mpn'])) {
                $product->mpn->addCData($row['mpn']);
            } else if (addslashes(trim($row['skus_ds'])) != '') {
                $product->mpn->addCData($row['skus_ds']);
            } else {
                $product->mpn->addCData($prod_id);
            }
        }

        if(isset($row['gtin'])) {
            $label = array_keys($row['gtin'])[0];
            $product->addChild($label)->addCData($row['gtin'][$label]);
        }

        $product->addChild('productId', $prod_id);
        $product->name = NULL;
        $product->name->addCData($row['title']);
        $product->link = NULL;
        $product->link->addCData($row['link']);
        
        $product->image = NULL;
        $product->image->addCData($row['image_big']);
        
        $product->categoryPath = NULL;
        $product->categoryPath->addCData($row['category_path']);
        $product->addChild('categoryID', $row['category_id']);
        $product->addChild('price', $row['price']);
        $product->description = NULL;
        $product->description->addCData($row['descr']);

        if(isset($row['additional_image'])) {
            foreach($row['additional_image'] as $id) {
                $product->addChild('additional_image')->addCData($id);
            }
        }

        if (strcmp($row['stockstatus'], "instock") == 0) {
            $product->addChild('instock', "Y");
            $product->addChild('availability', $row['availabilityST']);
        } else {
            if (strcmp($row['backorder'], "notify") == 0) {
                $product->addChild('instock', "N");

                if($ifoutofstock == 0){
                    $product->addChild('availability', __('Upon order', 'skroutz-woocommerce-feed'));
                } else {
                    if(isset($row['noavailabilityST'])) {
                        $product->addChild('availability', $row['noavailabilityST']);
                    } else {
                        $product->addChild('availability', __('Upon order', 'skroutz-woocommerce-feed'));
                    }
                }
            } else if (strcmp($row['backorder'], "yes") == 0) {
                $product->addChild('instock', "Y");
                $product->addChild('availability', $row['availabilityST']);
            } else {
                $product->addChild('instock', "N");
                
                if($ifoutofstock == 0){
                    if (strcmp($row['stockstatus'], "onbackorder") == 0) {
                        $product->addChild('availability', __('Upon order', 'skroutz-woocommerce-feed'));
                    } else {
                        $product->addChild('availability', __('Out of stock', 'skroutz-woocommerce-feed'));
                    }
                } else {
                    if(isset($row['noavailabilityST'])) {
                        $product->addChild('availability', $row['noavailabilityST']);
                    } else {
                        if (strcmp($row['stockstatus'], "onbackorder") == 0) {
                            $product->addChild('availability', __('Upon order', 'skroutz-woocommerce-feed'));
                        } else {
                            $product->addChild('availability', __('Out of stock', 'skroutz-woocommerce-feed'));
                        }
                    }
                }
            }
        }
        $product->addChild('size', $row['sizestring']);
        $product->manufacturer = NULL;
        $product->manufacturer->addCData($row['manufacturer']);
        if($row['colorstring'] != '') {
            $product->color = NULL;
            $product->color->addCData($row['colorstring']);
        }
        if( $row['_weight_ds']> 0) {
            $product->addChild('weight', $row['_weight_ds']);
        }


        $features = $product->addChild('features');
        if ($featureslist != null) {
            foreach ($featureslist as $feature) {
                if (array_key_exists($feature, $row['terms']) && array_key_exists($feature, $row['attributes'])) {
                    $attname = $row['attributes'][$feature]->get_taxonomy_object()->attribute_name;
                    $features->$attname = NULL;
                    $features->$attname->addCData(implode(', ', $row['terms'][$feature]));
                }
            }
        }
    }
}

function format_number_skroutz($pa_size) {
    return str_replace(',', '.', $pa_size);
}