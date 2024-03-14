<?php
/*
    "WordPress Plugin Template" Copyright (C) 2018 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

function WoocommerceAliexpressDropshipping_init($file)
{

  require_once('WoocommerceAliexpressDropshipping_Plugin.php');
  $aPlugin = new WoocommerceAliexpressDropshipping_Plugin();

  // Install the plugin
  // NOTE: this file gets run each time you *activate* the plugin.
  // So in WP when you "install" the plugin, all that does it dump its files in the plugin-templates directory
  // but it does not call any of its code.
  // So here, the plugin tracks whether or not it has run its install operation, and we ensure it is run only once
  // on the first activation
  if (!$aPlugin->isInstalled()) {
    $aPlugin->install();
  } else {
    // Perform any version-upgrade activities prior to activation (e.g. database changes)
    $aPlugin->upgrade();
  }

  // Add callbacks to hooks
  $aPlugin->addActionsAndFilters();

  if (!$file) {
    $file = __FILE__;
  }
  // Register the Plugin Activation Hook
  register_activation_hook($file, array(&$aPlugin, 'activate'));


  // Register the Plugin Deactivation Hook
  register_deactivation_hook($file, array(&$aPlugin, 'deactivate'));
}







function theShark_alibay_getProductsCount_FROM_WP()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $args = array(
    'post_type'      => 'product',
    'post_status' => array('publish', 'draft'),
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'aliexpress',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'ebay',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'amazon',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'etsy',
        'compare' => 'LIKE'
      )
    )
  );
  $query = new WP_Query($args);
  $total = $query->found_posts;
  wp_reset_postdata();
  wp_send_json($total);
}



function theShark_alibay_get_categories_FROMWP()
{


  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  


  $categoriesArray = array();

  $orderby = 'name';
  $order = 'asc';
  $hide_empty = false;
  $cat_args = array(
    'orderby'    => $orderby,
    'order'      => $order,
    'hide_empty' => $hide_empty,
  );

  $product_categories = get_terms('product_cat', $cat_args);

  foreach ($product_categories as $product_category) {
    array_push($categoriesArray, array('name' => $product_category->name, 'count' => $product_category->count, 'term_id' => $product_category->term_id));
  }




  wp_send_json($categoriesArray);
}


function checkDailyUpdatePemuim_alibay()
{

  $currentUpdateCount = get_option('alibay_daily_update_count');
  $lastUpdateDate = get_option('alibay_last_update_date');

  $today = date('Y-m-d');

  if ($lastUpdateDate !== $today || !$currentUpdateCount) {
    $currentUpdateCount = 0;
    update_option('alibay_daily_update_count', $currentUpdateCount);
    update_option('alibay_last_update_date', $today);
  }

  if ($currentUpdateCount >= 500) {
    wp_send_json_error(array('message' => 'Daily update of 500 products limit reached. Please use wisely our APIs. you can start again tomorrow.'));
    return;
  }
}

function checkDailyUpdate_alibay()
{


  $currentUpdateCount = get_option('alibay_daily_update_count');
  $lastUpdateDate = get_option('alibay_last_update_date');

  $today = date('Y-m-d');

  if ($lastUpdateDate !== $today || !$currentUpdateCount) {
    $currentUpdateCount = 0;
    update_option('alibay_daily_update_count', $currentUpdateCount);
    update_option('alibay_last_update_date', $today);
  }

  if ($currentUpdateCount >= 15) {
    wp_send_json_error(array('message' => 'Daily update of 30 products limit reached, you can start again tomorrow or upgrade to premuim plan'));
    return;
  }
}

function checkDailyImportPremuim_alibay()
{
  $currentImportCount = get_option('alibay_daily_import_count');
  $lastImportDate = get_option('alibay_last_import_date');

  $today = date('Y-m-d');

  if ($lastImportDate !== $today || !$currentImportCount) {
    $currentImportCount = 0;
    update_option('alibay_daily_import_count', $currentImportCount);
    update_option('alibay_last_import_date', $today);
  }

  if ($currentImportCount >= 1500) {
    $results = array('error' => true, 'error_msg' => 'Daily import limit of 1500 products reached. Please try again tomorrow.', 'data' => '');
    wp_send_json($results);
    return;
  }
}

function checkDailyImport_alibay()
{
  $currentImportCount = get_option('alibay_daily_import_count');
  $lastImportDate = get_option('alibay_last_import_date');

  $today = date('Y-m-d');
  if ($lastImportDate !== $today || !$currentImportCount) {
    $currentImportCount = 0;
    update_option('alibay_daily_import_count', $currentImportCount);
    update_option('alibay_last_import_date', $today);
  }

  if ($currentImportCount >= 15) {
    $results = array('error' => true, 'error_msg' => 'Daily import limit of 15 products reached. Please try again tomorrow or upgrade to premium plan', 'data' => '');
    wp_send_json($results);
    return;
  }

  // Presumably, the rest of your function continues here...
}


function checkVersionActivation_alibay()
{
  $isPremuim = false;
  if ($isPremuim) {
    checkDailyImportPremuim_alibay();
  } else {
    checkDailyImport_alibay();
  }
}
function theShark_alibay_insertProductInWoocommerceAffiliate()
{



  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  checkVersionActivation_alibay();

  try {
    if (isset($_POST)) {
      $sku = isset($_POST['sku']) ? wc_clean($_POST['sku']) : '';
      $external_url = isset($_POST['affiliateLink']) ? wc_clean($_POST['affiliateLink']) : '';

      $images = isset($_POST['images']) ? wc_clean($_POST['images']) : array();
      $categories = isset($_POST['categories']) ? wc_clean($_POST['categories']) : array();
      $remoteCategories = isset($_POST['remoteCategories']) ? wc_clean($_POST['remoteCategories']) : array();
      $selectedCurrency = wc_clean($_POST['selectedCurrency']);
      $title = isset($_POST['title']) ? wc_clean($_POST['title']) : '';
      $description = isset($_POST['description']) ? wp_kses_post($_POST['description']) : '';
      $productType = 'external';
      $regularPrice = isset($_POST['regularPrice']) ? wc_clean($_POST['regularPrice']) : '0';
      $salePrice = isset($_POST['salePrice']) ? wc_clean($_POST['salePrice']) : '0';
      $postStatus = isset($_POST['postStatus']) ? wc_clean($_POST['postStatus']) : 'draft';
      $isFeatured = isset($_POST['isFeatured']) ? true : false;

      $attributes = isset($_POST['attributes']) ? wc_clean($_POST['attributes']) : array();
      $productUrl = isset($_POST['productUrl']) ? wc_clean($_POST['productUrl']) : '';
      $shortDescription = isset($_POST['shortDescription']) ? wc_clean($_POST['shortDescription']) : '';
      $reviews = isset($_POST['reviews']) ? wc_clean($_POST['reviews']) : array();
      $tags = isset($_POST['tags']) ? wc_clean($_POST['tags']) : array();

      $currentImportedNumber = get_option('isAllowedToImport_alibay');
      if (isset($currentImportedNumber)) {
        $finalCounter = (int) $currentImportedNumber + 1;
        update_option('isAllowedToImport_alibay', $finalCounter);
      } else {
        update_option('isAllowedToImport_alibay', '1');
      }

      if (null != get_option('isAllowedToImport_alibay') && (int) get_option('isAllowedToImport_alibay') > 200) {
        $results = array('error' => true, 'error_msg' => 'You have reached the permitted usage limit for this week. You can upgrade to a premium plan.', 'data' => '');
        wp_send_json($results);
      }

      $product = new WC_Product_External();

      if (isset($title)) {
        $product->set_name($title);
      }
      if (isset($isFeatured)) {
        $product->set_featured(true); // Set to true to make it featured
      }


      if (isset($description)) {
        $product->set_description($description);
      }
      if (isset($shortDescription)) {
        $product->set_short_description($shortDescription);
      }

      if (isset($sku)) {
        $product->set_sku($sku);
      }

      if (isset($postStatus)) {
        $product->set_status($postStatus);
      }



      if (isset($regularPrice)) {
        $product->set_regular_price($regularPrice);
        $product->set_price($regularPrice);
      }

      if (isset($salePrice)) {
        $product->set_sale_price($salePrice);
      }

      if (is_array($categories) && count($categories)) {
        $product->set_category_ids($categories);
      }

      // Save product images
      // $imageUploadErrors = theShark_alibay_save_product_images_for_ebay_alibay($product, $images);

      $response = theShark_alibay_save_product_images_for_ebay_alibay($product, $images);
      $product = $response['product'];
      $imageUploadErrors = $response['errors'];



      try {
        $post_id = $product->save();
      } catch (Exception $e) {
        $results = array(
          'error' => false,
          'error_msg' => '',
          'data' => 'Product inserted successfully',
          'postId' => $post_id,
          'imageUploadErrors' => $imageUploadErrors // Add image upload errors to the response
        );
        wp_send_json($results);
      }

      if (isset($productUrl)) {
        update_post_meta($post_id, 'productUrl', $productUrl);
      }

      if ($selectedCurrency) {
        update_post_meta($post_id, 'selectedCurrency', $selectedCurrency);
      }

      $lastUpdatedDate = current_time('mysql', 1);
      update_post_meta($post_id, 'lastUpdatedDate', $lastUpdatedDate);


      if ($external_url) {
        update_post_meta($post_id, '_product_url', esc_url_raw($external_url));
      }

      if (isset($post_id) && isset($tags) && count($tags)) {
        wp_set_post_tags($post_id, $tags, true);
        wp_set_object_terms($post_id, $tags, 'product_tag');
      }

      // Create and save product attributes
      $product_attributes = array();
      if (is_array($attributes) && count($attributes)) {
        foreach ($attributes as $attribute_data) {
          $attribute_name = sanitize_title($attribute_data['name']);
          $attribute_values = array_map('sanitize_title', $attribute_data['values']);

          $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);

          if (!$attribute_id) {
            $attribute_id = wc_create_attribute(array(
              'name' => $attribute_name,
              'slug' => $attribute_name,
              'type' => 'select', // 'select' for dropdown, 'text' for text field, etc.
              'order_by' => 'menu_order', // You can adjust this order if needed
              'has_archives' => true,
            ));
          }

          if ($attribute_id && !empty($attribute_values)) {
            wp_set_object_terms($post_id, $attribute_values, 'pa_' . $attribute_name);
          }

          $product_attributes['pa_' . $attribute_name] = array(
            'name' => 'pa_' . $attribute_name,
            'value' => implode(',', $attribute_values),
            'position' => 0,
            'is_visible' => 1,
            'is_variation' => $attribute_data['variation'] ? 1 : 0,
            'is_taxonomy' => 1,
          );
        }
        update_post_meta($post_id, '_product_attributes', $product_attributes);
      }
      $currentImportCount = get_option('alibay_daily_import_count', 0);
      update_option('alibay_daily_import_count', ++$currentImportCount);


      $category_ids = create_woocommerce_categories_and_get_ids_alibay($remoteCategories);
      $merged_category_ids = array_merge($categories, $category_ids);


      if (is_array($merged_category_ids) && count($merged_category_ids)) {

        wp_set_post_terms($post_id, $merged_category_ids, 'product_cat');
      }


      if (isset($post_id) && isset($reviews) && count($reviews)) {
        foreach ($reviews as $review) {
          $comment_content = $review['review'];

          // Process and insert images into the comment content
          preg_match_all('/<img[^>]+src="([^"]+)"/i', $comment_content, $matches);

          if (isset($matches[1]) && !empty($matches[1])) {
            foreach ($matches[1] as $image_url) {
              // Generate HTML for the image and insert it into the comment content
              $image_html = '<img src="' . esc_url($image_url) . '" alt="" />';
              $comment_content .= $image_html;
            }
          }

          $comment_id = wp_insert_comment(array(
            'comment_post_ID' => sanitize_text_field($post_id),
            'comment_author' => sanitize_text_field($review['username']),
            'comment_author_email' => sanitize_text_field($review['email']),
            'comment_author_url' => '',
            'comment_content' => $comment_content,
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => 5,
            'comment_author_IP' => '',
            'comment_agent' => '',
            'comment_date' => $review['datecreation'],
            'comment_approved' => 1,
          ));

          // Inserting the rating (an integer from 1 to 5)
          update_comment_meta($comment_id, 'rating', sanitize_text_field($review['rating']));

          // array_push($insertedSuccessfully, $comment_id);
        }

        // wp_send_json(array('insertedSuccessfully' => $insertedSuccessfully));
      }




      $results = array(
        'error' => false,
        'error_msg' => '',
        'data' => 'Product inserted successfully',
        'postId' => $post_id,
        'imageUploadErrors' => $imageUploadErrors // Add image upload errors to the response
      );
      wp_send_json($results);
    }
  } catch (Exception $ex) {
    $results = array(
      'error' => true,
      'error_msg' => 'Error received when trying to insert the product' . $ex->getMessage(),
      'data' => '',
    );
    wp_send_json($results);
  }
}






function theShark_alibay_insertProductInWoocommerce()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  checkVersionActivation_alibay();
  try {
    if (isset($_POST)) {
      $sku = isset($_POST['sku']) ? wc_clean($_POST['sku']) : '';
      $images = isset($_POST['images']) ? wc_clean($_POST['images']) : array();
      $categories = isset($_POST['categories']) ? wc_clean($_POST['categories']) : array();
      $remoteCategories = isset($_POST['remoteCategories']) ? wc_clean($_POST['remoteCategories']) : array();
      $selectedCurrency = wc_clean($_POST['selectedCurrency']);
      $title = isset($_POST['title']) ? wc_clean($_POST['title']) : '';
      $description = isset($_POST['description']) ? wp_kses_post($_POST['description']) : '';
      $productType = isset($_POST['productType']) ? wc_clean($_POST['productType']) : 'simple';
      $regularPrice = isset($_POST['regularPrice']) ? wc_clean($_POST['regularPrice']) : '0';
      $salePrice = isset($_POST['salePrice']) ? wc_clean($_POST['salePrice']) : '0';
      $quantity = isset($_POST['quantity']) ? wc_clean($_POST['quantity']) : '0';
      $postStatus = isset($_POST['postStatus']) ? wc_clean($_POST['postStatus']) : 'draft';
      $variations = isset($_POST['variations']) ? wc_clean($_POST['variations']) : array();
      $attributes = isset($_POST['attributes']) ? wc_clean($_POST['attributes']) : array();
      $productUrl = isset($_POST['productUrl']) ? wc_clean($_POST['productUrl']) : '';
      $shortDescription = isset($_POST['shortDescription']) ? wc_clean($_POST['shortDescription']) : '';
      $importVariationImages = isset($_POST['importVariationImages']) ? wc_clean($_POST['importVariationImages']) : '';
      $reviews = isset($_POST['reviews']) ? wc_clean($_POST['reviews']) : array();
      $tags = isset($_POST['tags']) ? wc_clean($_POST['tags']) : array();
      $isFeatured = isset($_POST['isFeatured']) ? true : false;



      $currentImportedNumber = get_option('isAllowedToImport_alibay');
      if (isset($currentImportedNumber)) {
        $finalCounter = (int) $currentImportedNumber + 1;
        update_option('isAllowedToImport_alibay',   $finalCounter);
      } else {
        update_option('isAllowedToImport_alibay',   '1');
      }

      if (null != get_option('isAllowedToImport_alibay') && (int) get_option('isAllowedToImport_alibay') > 200) {
        $results = array('error' => true, 'error_msg' => 'you have reached the permitted usage limit for this week you can upgrade to a premuim plan', 'data' => '');
        wp_send_json($results);
      }



      // if ($productType == 'simple') {
      if ($productType == 'simple') {

        $product = new WC_Product_Simple();

        if (isset($title)) {
          $product->set_name($title);
        }
        if (isset($description)) {
          $product->set_description($description);
        }
        if (isset($shortDescription)) {
          $product->set_short_description($shortDescription);
        }

        if (isset($sku)) {
          $product->set_sku($sku);
        }

        if (isset($postStatus)) {
          $product->set_status($postStatus);
        }

        if (isset($isFeatured)) {
          $product->set_featured(true); // Set to true to make it featured
        }

        if (isset($regularPrice)) {
          $product->set_regular_price($regularPrice);
          $product->set_price($regularPrice);
        }

        if (isset($salePrice)) {
          $product->set_sale_price($salePrice);
          // $product->set_price($salePrice);
        }

        if (isset($quantity)) {
          $product->set_stock_quantity($quantity);
          $product->set_manage_stock(true);
        }




        $imageUploadErrors = theShark_alibay_save_product_images_for_ebay_alibay($product, $images);




        try {
          $post_id = $product->save();
        } catch (Exception $e) {
          $results = array(
            'error' => false,
            'error_msg' => '',
            'data' => 'Product inserted successfully',
            'postId' => $post_id,
            'imageUploadErrors' => $imageUploadErrors // Add image upload errors to the response
          );
          wp_send_json($results);
        }



        if (isset($productUrl)) {
          update_post_meta($post_id, 'productUrl', $productUrl);
        }
        if (isset($selectedCurrency)) {
          update_post_meta($post_id, 'selectedCurrency', $selectedCurrency);
        }



        if (isset($post_id) && isset($tags) && count($tags)) {
          wp_set_post_tags($post_id, $tags, true);
          wp_set_object_terms($post_id, $tags, 'product_tag');
        }

        $lastUpdatedDate = current_time('mysql', 1);
        update_post_meta($post_id, 'lastUpdatedDate', $lastUpdatedDate);



        $product_attributes = array();
        if (is_array($attributes) && count($attributes)) {
          foreach ($attributes as $attribute_data) {
            $attribute_name = sanitize_title($attribute_data['name']);
            $attribute_values = array_map('sanitize_title', $attribute_data['values']);

            $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);

            if (!$attribute_id) {
              $attribute_id = wc_create_attribute(array(
                'name' => $attribute_name,
                'slug' => $attribute_name,
                'type' => 'select', // 'select' for dropdown, 'text' for text field, etc.
                'order_by' => 'menu_order', // You can adjust this order if needed
                'has_archives' => true,
              ));
            }

            if ($attribute_id && !empty($attribute_values)) {
              wp_set_object_terms($post_id, $attribute_values, 'pa_' . $attribute_name);
            }

            $product_attributes['pa_' . $attribute_name] = array(
              'name' => 'pa_' . $attribute_name,
              'value' => implode(',', $attribute_values),
              'position' => 0,
              'is_visible' => 1,
              'is_variation' => $attribute_data['variation'] ? 1 : 0,
              'is_taxonomy' => 1,
            );
          }
          update_post_meta($post_id, '_product_attributes', $product_attributes);
        }



        $category_ids = create_woocommerce_categories_and_get_ids_alibay($remoteCategories);
        $merged_category_ids = array_merge($categories, $category_ids);


        if (is_array($merged_category_ids) && count($merged_category_ids)) {

          wp_set_post_terms($post_id, $merged_category_ids, 'product_cat');
        }
        $currentImportCount = get_option('alibay_daily_import_count', 0);
        update_option('alibay_daily_import_count', ++$currentImportCount);


        if (isset($post_id) && isset($reviews) && count($reviews)) {

          foreach ($reviews as $review) {
            $comment_id = wp_insert_comment(array(
              'comment_post_ID'      => sanitize_text_field($post_id), // <=== The product ID where the review will show up
              'comment_author'       => sanitize_text_field($review['username']),
              'comment_author_email' => sanitize_text_field($review['email']), // <== Important
              'comment_author_url'   => '',
              'comment_content'      => $review['review'],
              'comment_type'         => '',
              'comment_parent'       => 0,
              'user_id'              => 5, // <== Important
              'comment_author_IP'    => '',
              'comment_agent'        => '',
              'comment_date'         => $review['datecreation'],
              'comment_approved'     => 1,
            ));

            // HERE inserting the rating (an integer from 1 to 5)
            update_comment_meta($comment_id, 'rating', sanitize_text_field($review['rating']));
          }
        }




        // variations
        $results = array(
          'error' => false,
          'error_msg' => '',
          'data' => 'Product inserted successfully',
          'postId' => $post_id

        );
        wp_send_json($results);
        // if (isset($post_id)) {
        //   // 
        // }
        // } else {
        //   $response['message'] = $post_id->get_error_message();
        //   wp_send_json($response);
        // }
      } else {
        /*--------VARIABLE------*/
        /*--------VARIABLE------*/
        /*---------VARIABLE-----*/
        /*---------VARIABLE-----*/
        /*----------VARIABLE----*/
        /*----------VARIABLE----*/
        /*----------VARIABLE----*/
        /*-------VARIABLE-------*/
        /*---------VARIABLE-----*/
        /*--------VARIABLE------*/
        /*--------VARIABLE------*/
        /*--------VARIABLE------*/
        /*----------VARIABLE----*/

        //Create main product

        try {
          $product = new WC_Product_Variable();
          if (isset($title)) {
            $product->set_name($title);
          }
          if (isset($description)) {
            $product->set_description($description);
          }
          if (isset($shortDescription)) {
            $product->set_short_description($shortDescription);
          }

          if (isset($isFeatured)) {
            $product->set_featured(true); // Set to true to make it featured
          }

          if (isset($sku)) {
            $product->set_sku($sku);
          }

          if (isset($postStatus)) {
            $product->set_status($postStatus);
          }


          //   //categories
          if (is_array($categories) && count($categories)) {
            $product->set_category_ids($categories);
          }
          //images

          $imageUploadErrors =   theShark_alibay_save_product_images_for_ebay_alibay($product, $images);



          $attributeArray = array();
          if (is_array($attributes) && count($attributes)) {
            foreach ($attributes as $attributeValue) {
              $values = $attributeValue['values'];
              $attr_label = $attributeValue['name'];
              $isVariation = $attributeValue['variation'];

              //Create the attribute object
              $attribute = new WC_Product_Attribute();

              //pa_size tax id
              // $attribute->set_id(0); // -> SET to 0

              //pa_size slug
              $attribute->set_name($attr_label); // -> removed 'pa_' prefix

              //Set terms slugs
              $attribute->set_options($values);

              //If enabled
              $attribute->set_visible(1);

              //If we are going to use attribute in order to generate variations
              // $attribute->set_variation(1);

              if ($isVariation == 'true') {
                $attribute->set_variation(1);
              } else {
                $attribute->set_variation(0);
              }

              array_push($attributeArray, $attribute);
            }
            $product->set_attributes($attributeArray);
          } else {
            $results = array(
              'error' => false,
              'error_msg' => '',
              'data' => 'Product inserted successfully',
              'postId' => $post_id,
              'imageUploadErrors' => $imageUploadErrors // Add image upload errors to the response
            );
            wp_send_json($results);
          }
        } catch (Exception $ex) {
          /* ERROR LIKE "SKU ALREADY EXISTS" */
          $results = array(
            'error' => true,
            'error_msg' => 'Error received when trying to insert the product' . $ex->getMessage(),
            'data' => ''
          );
          wp_send_json($results);
        }


        try {
          $post_id = $product->save();
        } catch (Exception $e) {
          $results = array(
            'error' => true,
            'error_msg' => 'Error received when trying to insert the product' . $ex->getMessage(),
            'data' => ''
          );
          wp_send_json($results);
        }
        if (isset($productUrl)) {
          update_post_meta($post_id, 'productUrl', $productUrl);
        }

        if (isset($selectedCurrency)) {
          update_post_meta($post_id, 'selectedCurrency', $selectedCurrency);
        }

        $lastUpdatedDate = current_time('mysql', 1);
        update_post_meta($post_id, 'lastUpdatedDate', $lastUpdatedDate);

        if (is_array($variations) && count($variations)) {
          array_splice($variations, 1);

          foreach ($variations as $variation) {

            $attributesVariations = $variation['attributesVariations'];
            $variationToCreate = new WC_Product_Variation();
            // $variationToCreate->set_regular_price(10);
            $variationToCreate->set_parent_id($post_id);
            if (isset($variation['SKU']) && !empty($variation['SKU'])) {
              $variationToCreate->set_sku($variation['SKU']);
            }
            if (!empty(sanitize_text_field($variation['regularPrice']))) {
              $variationToCreate->set_regular_price($variation['regularPrice']);
            }

            if (!empty(sanitize_text_field($variation['salePrice']))) {
              $variationToCreate->set_sale_price($variation['salePrice']);
            }

            //set image id
            // if (!empty(sanitize_text_field($variation['salePrice']))) {
            //   $variationToCreate->set_image_id($variation['salePrice']);
            // }





            $stockProduct = sanitize_text_field($variation['availQuantity']);
            if (isset($stockProduct)) {
              $variationToCreate->set_manage_stock(true);
              $variationToCreate->set_stock_quantity($stockProduct);
              $variationToCreate->set_stock_status('instock');
            }
            $variationsArray = array();
            foreach ($attributesVariations as $attributesVariation) {
              $variationsArray[$attributesVariation['name']] = $attributesVariation['value'];

              $arrayImageId = array();
              if (($importVariationImages == 'true')) {
                // wp_send_json($importVariationImages);

                $imageVariations = $attributesVariation['image'];
                if (isset($imageVariations)) {
                  $imageId = false;
                  foreach ($arrayImageId as $imageObject) {
                    if ($imageObject->imageVariations == $imageVariations) {
                      $imageId = $imageObject->id;
                      break;
                    }
                  }
                  if ($imageId != false) {
                    $variationToCreate->set_image_id($imageId);
                  } else {
                    $imageIdVariation  =  theShark_alibay_save_single_variation_image_alibay($variationToCreate, $imageVariations);
                    array_push($arrayImageId, array('image' => $imageVariations, 'id' => $imageIdVariation));
                    if (isset($imageIdVariation)) {
                      $variationToCreate->set_image_id($imageIdVariation);
                    }
                  }
                }
              }
            };
            // wp_send_json(array('variationsArray' =\\÷/> $variationsArray, 'ttt' => $attributesVariation['value']));

            $variationToCreate->set_attributes($variationsArray);
            try {
              $variationToCreate->save();
            } catch (Exception $e) {
              echo __('Error while saving variation');
            }
          }
        }


        if (isset($post_id) && isset($tags) && count($tags)) {
          wp_set_post_tags($post_id, $tags, true);
          wp_set_object_terms($post_id, $tags, 'product_tag');
        }

        $category_ids = create_woocommerce_categories_and_get_ids_alibay($remoteCategories);
        $merged_category_ids = array_merge($categories, $category_ids);


        if (is_array($merged_category_ids) && count($merged_category_ids)) {

          wp_set_post_terms($post_id, $merged_category_ids, 'product_cat');
        }


        $currentImportCount = get_option('alibay_daily_import_count', 0);
        update_option('alibay_daily_import_count', ++$currentImportCount);
        if (isset($post_id) && isset($reviews) && count($reviews)) {

          foreach ($reviews as $review) {
            $comment_id = wp_insert_comment(array(
              'comment_post_ID'      => sanitize_text_field($post_id), // <=== The product ID where the review will show up
              'comment_author'       => sanitize_text_field($review['username']),
              'comment_author_email' => sanitize_text_field($review['email']), // <== Important
              'comment_author_url'   => '',
              'comment_content'      => $review['review'],
              'comment_type'         => '',
              'comment_parent'       => 0,
              'user_id'              => 5, // <== Important
              'comment_author_IP'    => '',
              'comment_agent'        => '',
              'comment_date'         => $review['datecreation'],
              'comment_approved'     => 1,
            ));

            // HERE inserting the rating (an integer from 1 to 5)
            update_comment_meta($comment_id, 'rating', sanitize_text_field($review['rating']));
          }
        }





        // wp_send_json($variations);


        $results = array(
          'error' => false,
          'error_msg' => '',
          'data' => 'Product inserted successfully',
          'postId' => $post_id,
          'imageUploadErrors' => $imageUploadErrors // Add image upload errors to the response
        );
        wp_send_json($results);
      }
    }
  } catch (Exception $ex) {
    $results = array(
      'error' => true,
      'error_msg' => 'Error received when trying to insert the product' . $ex->getMessage(),
      'data' => ''
    );
    wp_send_json($results);
  }
}


function theShark_alibay_getProductsDraft()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $paged = isset($_POST['paged']) ? wc_clean($_POST['paged']) : '';

  $args = array(
    'post_type'      => 'product',
    'posts_per_page' => 20,
    'paged' => $paged,
    'meta_query' => array(
      array(
        'key' => 'isExpired', //meta key name here
        'value' => 'true',
        'compare' => 'LIKE',
      )
    )


  );


  $products = new WP_Query($args);
  $finalList = array();

  if ($products->have_posts()) {
    while ($products->have_posts()) : $products->the_post();
      $theid = get_the_ID();
      $product = new WC_Product($theid);
      if (has_post_thumbnail()) {
        $thumbnail = get_post_thumbnail_id();
        $image = $thumbnail ? wp_get_attachment_url($thumbnail) : '';
      }
      $finalList[] = array(
        'sku' => $product->get_sku(),
        'id' => $theid,
        // image => wp_get_attachment_image_src( get_post_thumbnail_id($products->post->ID)),
        'image' => $image,
        'title' =>  $product->get_title(),
        'productUrl' => get_post_meta($theid, 'productUrl', true)

      );
    endwhile;
  } else {
    echo __('No products found');
  }
  wp_reset_postdata();

  wp_send_json($finalList);
}





function theShark_alibay_getOldProductDetails()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  // $productUrl = 'https://www.aliexpress.com/item/4001024639837.html';
  $post_id = isset($_POST['post_id']) ? wc_clean($_POST['post_id']) : '';


  $product = wc_get_product($post_id);
  $oldVariations = $product->get_available_variations();


  wp_send_json($oldVariations);
}








function theShark_alibay_removeProductFromShop()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $post_id = isset($_POST['post_id']) ? wc_clean($_POST['post_id']) : '';
  if (isset($post_id)) {
    $id_remove = wp_delete_post($post_id);
    if ($id_remove != false && isset($id_remove)) {
      $results = array(
        'error' => false,
        'error_msg' => '',
        'data' => 'removed successfully'
      );
      wp_send_json($results);
    } else {
      $results = array(
        'error' => trye,
        'error_msg' => 'error while removing the product',
        'data' => ''
      );
      wp_send_json($results);
    }
  }
}




function theShark_alibay_insertReviewsIntoProduct()
{
  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $post_id = isset($_POST['post_id']) ? wc_clean($_POST['post_id']) : '';
  $reviews = isset($_POST['reviews']) ? wc_clean($_POST['reviews']) : array();
  $insertedSuccessfully = array();
  if (isset($post_id) && isset($reviews) && count($reviews)) {

    foreach ($reviews as $review) {
      $comment_id = wp_insert_comment(array(
        'comment_post_ID'      => sanitize_text_field($post_id), // <=== The product ID where the review will show up
        'comment_author'       => sanitize_text_field($review['username']),
        'comment_author_email' => sanitize_text_field($review['email']), // <== Important
        'comment_author_url'   => '',
        'comment_content'      => sanitize_text_field($review['review']),
        'comment_type'         => '',
        'comment_parent'       => 0,
        'user_id'              => 5, // <== Important
        'comment_author_IP'    => '',
        'comment_agent'        => '',
        'comment_date'         => date('Y-m-d H:i:s'),
        'comment_approved'     => 1,
      ));

      // HERE inserting the rating (an integer from 1 to 5)
      $response = update_comment_meta($comment_id, 'rating', sanitize_text_field($review['rating']));
      if ($response != false && isset($response)) {
        array_push($insertedSuccessfully, $comment_id);
      }
    }
    wp_send_json(array('insertedSuccessfully' => $insertedSuccessfully));
  }
}





function theShark_alibay_getAlreadyImportedProducts()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $listOfSkus = isset($_POST['listOfSkus']) ? wc_clean($_POST['listOfSkus']) : array();

  if (isset($listOfSkus) && count($listOfSkus)) {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => 40,
      'meta_query' => array(
        array(
          "key" => "_sku",
          "value" => $listOfSkus,
          "compare" => "IN"
        ), array(
          'key' => 'productUrl', //meta key name here
          'value' => 'aliexpress.com/item',
          'compare' => 'LIKE',
        )
      )
    );
    $products = new WP_Query($args);
    $finalList = array();

    if ($products->have_posts()) {
      while ($products->have_posts()) : $products->the_post();
        $theid = get_the_ID();
        $product = new WC_Product($theid);

        $finalList[] = array(
          'sku' => $product->get_sku(),
          'id' => $theid,
          'title' =>  $product->get_title(),
          'productUrl' => get_post_meta($theid, 'productUrl', true)

        );
      endwhile;
    } else {
      echo __('No products found');
    }
    wp_reset_postdata();

    wp_send_json($finalList);
  }
}





function theShark_alibay_getSKuAbdUrlByCategory_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $categoryId = isset($_POST['categoryId']) ? wc_clean($_POST['categoryId']) : array();

  if (isset($categoryId)) {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => -1,
      'post_status' => array('publish'),
      'meta_query' => array(
        array(
          'key' => 'productUrl', //meta key name here
          'value' => 'aliexpress.com/item',
          'compare' => 'LIKE',
        )
      ),
      'tax_query'             => array(
        array(
          'taxonomy'      => 'product_cat',
          'field' => 'term_id', //This is optional, as it defaults to 'term_id'
          'terms'         => $categoryId,
          'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
        )
      )


    );
    $products = new WP_Query($args);
    $finalList = array();

    if ($products->have_posts()) {
      while ($products->have_posts()) : $products->the_post();
        $theid = get_the_ID();
        $product = new WC_Product($theid);
        $finalList[] = array(
          'sku' => $product->get_sku(),
          'id' => $theid,
          'productUrl' => get_post_meta($theid, 'productUrl', true)

        );
      endwhile;
    } else {
      echo __('No products found');
    }
    wp_reset_postdata();

    wp_send_json($finalList);
  }
}







function theShark_alibay_searchCategoryByName()
{
  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $searchCategoryByNameInput = isset($_POST['searchCategoryByNameInput']) ? wc_clean($_POST['searchCategoryByNameInput']) : array();
  $product_categories  = get_terms('category', array('search' => $searchCategoryByNameInput));
  wp_send_json($product_categories);
}



function theShark_alibay_save_product_images_alibay($product, $images)
{
  if (is_array($images)) {
    array_splice($images, 1);

    $gallery = array();
    foreach ($images as $key => $image) {
      if (isset($image)) {
        $upload = wc_rest_upload_image_from_url(esc_url_raw($image));
        if (is_wp_error($upload)) {
          if (!apply_filters('woocommerce_rest_suppress_image_upload_error', false, $upload, $product->get_id(), $images)) {
            throw new WC_REST_Exception('woocommerce_product_image_upload_error', $upload->get_error_message(), 400);
          } else {
            continue;
          }
        }
        $attachment_id = wc_rest_set_uploaded_image_as_attachment($upload, $product->get_id());
      }
      if ($key == 0) {
        $product->set_image_id($attachment_id);
      } else {
        array_push($gallery, $attachment_id);
      }
    }
    if (!empty($gallery)) {
      $product->set_gallery_image_ids($gallery);
    }
  } else {
    $product->set_image_id('');
    $product->set_gallery_image_ids(array());
  }
  return $product;
}


function theShark_alibay_save_single_variation_image_alibay($product, $image)
{
  $gallery = array();
  if (isset($image)) {
    $upload = wc_rest_upload_image_from_url(esc_url_raw($image));
    if (is_wp_error($upload)) {
      if (!apply_filters('woocommerce_rest_suppress_image_upload_error', false, $upload, $product->get_id(), $image)) {
        throw new WC_REST_Exception('woocommerce_product_image_upload_error', $upload->get_error_message(), 400);
      }
    }
    $attachment_id = wc_rest_set_uploaded_image_as_attachment($upload, $product->get_id());
  }
  $product->set_image_id($attachment_id);
  return $attachment_id;
}


function theShark_alibay_searchProductByIdReviews_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $searchSkuValue = isset($_POST['searchSkuValue']) ? sanitize_text_field($_POST['searchSkuValue']) : '';

  if (isset($searchSkuValue)) {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => 20,
      'p' => $searchSkuValue
      // 'meta_query' => array(
      //   array(
      //     "key" => "_sku",
      //     "value" => $searchSkuValue,
      //     "compare" => "LIKE"
      //   )
      // )
    );





    $products = new WP_Query($args);
    $finalList = array();

    if ($products->have_posts()) {
      while ($products->have_posts()) : $products->the_post();
        $theid = get_the_ID();
        $product = new WC_Product($theid);
        if (has_post_thumbnail()) {
          $thumbnail = get_post_thumbnail_id();
          $image = $thumbnail ? wp_get_attachment_url($thumbnail) : '';
        }
        $finalList[] = array(
          'sku' => $product->get_sku(),
          'id' => $theid,
          // image => wp_get_attachment_image_src( get_post_thumbnail_id($products->post->ID)),
          'image' => $image,
          'title' =>  $product->get_title(),
          'productUrl' => get_post_meta($theid, 'productUrl', true),
          'lastUpdated' => get_post_meta($theid, 'lastUpdated', true),
          'status' => $product->get_status()


        );
      endwhile;
    } else {
      echo __('No products found');
    }
    wp_reset_postdata();

    wp_send_json($finalList);
  } else {
    $results = array(
      'error' => true,
      'error_msg' => 'cannot find result for the introduced sku value, please make sure the product is imported using theShark',
      'data' => ''
    );
    wp_send_json($results);
  }
}



function theShark_alibay_saveOptionsDB_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  


  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $isShippingCostEnabled = isset($_POST['isShippingCostEnabled']) ? sanitize_text_field($_POST['isShippingCostEnabled']) : 'N';
  $isEnableAutomaticUpdateForAvailability = isset($_POST['isEnableAutomaticUpdateForAvailability']) ? sanitize_text_field($_POST['isEnableAutomaticUpdateForAvailability']) : 'N';
  $isUpdateRegularPrice = isset($_POST['isUpdateRegularPrice']) ? sanitize_text_field($_POST['isUpdateRegularPrice']) : 'N';
  $isUpdateSalePrice = isset($_POST['isUpdateSalePrice']) ? sanitize_text_field($_POST['isUpdateSalePrice']) : 'N';
  $isUpdateStock = isset($_POST['isUpdateStock']) ? sanitize_text_field($_POST['isUpdateStock']) : 'N';
  $priceFormulaIntervalls = isset($_POST['priceFormulaIntervalls']) ? wc_clean($_POST['priceFormulaIntervalls']) : array();
  $onlyPublishProductWillSync = isset($_POST['onlyPublishProductWillSync']) ? sanitize_text_field($_POST['onlyPublishProductWillSync']) : 'N';
  $enableAutomaticUpdates = isset($_POST['enableAutomaticUpdates']) ? sanitize_text_field($_POST['enableAutomaticUpdates']) : 'N';
  $applyPriceFormulaAutomaticUpdate = isset($_POST['applyPriceFormulaAutomaticUpdate']) ? sanitize_text_field($_POST['applyPriceFormulaAutomaticUpdate']) : 'N';
  $syncRegularPrice = isset($_POST['syncRegularPrice']) ? sanitize_text_field($_POST['syncRegularPrice']) : 'N';
  $syncSalePrice = isset($_POST['syncSalePrice']) ? sanitize_text_field($_POST['syncSalePrice']) : 'N';
  $syncStock = isset($_POST['syncStock']) ? sanitize_text_field($_POST['syncStock']) : 'N';
  $_savedConfiguration = isset($_POST['_savedConfiguration']) ? wc_clean($_POST['_savedConfiguration']) : null;

  if (isset($_savedConfiguration)) {
    update_option('_savedConfiguration_alibay',   $_savedConfiguration);
  }



  if (isset($syncRegularPrice)) {
    update_option('alibay_aliexpress_syncRegularPrice', $syncRegularPrice);
  }

  if (isset($syncSalePrice)) {
    update_option('alibay_aliexpress_syncSalePrice', $syncSalePrice);
  }

  if (isset($syncStock)) {
    update_option('alibay_aliexpress_syncStock', $syncStock);
  }

  // wp_send_json($updateVariationsOnServer);
  if (isset($priceFormulaIntervalls)) {
    update_option('alibay_aliexpress_priceFormulaIntervalls', $priceFormulaIntervalls);
  }

  if (isset($isShippingCostEnabled)) {
    update_option('alibay_aliexpress_isShippingCostEnabled', $isShippingCostEnabled);
  }

  if (isset($isEnableAutomaticUpdateForAvailability)) {
    update_option('alibay_aliexpress_isEnableAutomaticUpdateForAvailability', $isEnableAutomaticUpdateForAvailability);
  }

  if (isset($isUpdateRegularPrice)) {
    update_option('alibay_aliexpress_isUpdateRegularPrice', $isUpdateRegularPrice);
  }


  if (isset($isUpdateSalePrice)) {
    update_option('alibay_aliexpress_isUpdateSalePrice', $isUpdateSalePrice);
  }


  if (isset($isUpdateStock)) {
    update_option('alibay_aliexpress_isUpdateStock', $isUpdateStock);
  }
  if (isset($onlyPublishProductWillSync)) {
    update_option('alibay_aliexpress_onlyPublishProductWillSync', $onlyPublishProductWillSync);
  }
  if (isset($enableAutomaticUpdates)) {
    update_option('alibay_aliexpress_enableAutomaticUpdates', $enableAutomaticUpdates);
  }
  if (isset($applyPriceFormulaAutomaticUpdate)) {
    update_option('alibay_aliexpress_applyPriceFormulaAutomaticUpdate', $applyPriceFormulaAutomaticUpdate);
  }


  wp_send_json($isShippingCostEnabled);
}






function theShark_alibay_insertReviewsIntoProductRM_PREMUIM_PLUGIN_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
  $reviews = isset($_POST['reviews']) ? wc_clean($_POST['reviews']) : array();
  $insertedSuccessfully = array();
  if (isset($post_id) && isset($reviews) && count($reviews)) {

    foreach ($reviews as $review) {
      $comment_id = wp_insert_comment(array(
        'comment_post_ID'      => sanitize_text_field($post_id), // <=== The product ID where the review will show up
        'comment_author'       => sanitize_text_field($review['username']),
        'comment_author_email' => sanitize_text_field($review['email']), // <== Important
        'comment_author_url'   => '',
        'comment_content'      => $review['review'],
        'comment_type'         => '',
        'comment_parent'       => 0,
        'user_id'              => 5, // <== Important
        'comment_author_IP'    => '',
        'comment_agent'        => '',
        'comment_date'         => $review['datecreation'],
        'comment_approved'     => 1,
      ));

      // HERE inserting the rating (an integer from 1 to 5)
      $response = update_comment_meta($comment_id, 'rating', sanitize_text_field($review['rating']));
      if ($response != false && isset($response)) {
        array_push($insertedSuccessfully, $comment_id);
      }
    }
    wp_send_json(array('insertedSuccessfully' => $insertedSuccessfully));
  }
}

function theShark_alibay_restoreConfiguration_alibay()
{
  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  $_savedConfiguration  = get_option('_savedConfiguration_alibay');
  wp_send_json(array('_savedConfiguration_alibay' => $_savedConfiguration));
}



function getProductsCount_FROM_WP_for_ebay_alibay()
{


  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $args = array(
    'post_type'      => 'product',
    'post_status' => array('publish', 'draft'),
    'meta_query' => array(
      array(
        'key' => 'productUrl', //meta key name here
        'value' => '.ebay.',
        'compare' => 'LIKE',
      )
    ),
  );
  $query = new WP_Query($args);
  $total = $query->found_posts;
  wp_reset_postdata();
  wp_send_json($total);
}

// Function to insert variations in batches
function theShark_alibay_insertVariations()
{


  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  try {
    // Get product ID and variations data from the POST request
    $post_id = wc_clean($_POST['postId']);
    $variations = wc_clean($_POST['variations']);


    if (is_array($variations) && count($variations)) {
      // array_splice($variations, 2);

      foreach ($variations as $variation) {

        $attributesVariations = $variation['attributesVariations'];
        $variationToCreate = new WC_Product_Variation();
        // $variationToCreate->set_regular_price(10);
        $variationToCreate->set_parent_id($post_id);
        if (isset($variation['SKU']) && !empty($variation['SKU']) && $variation['SKU'] != 'undefined') {
          $variationToCreate->set_sku($variation['SKU']);
        }

        if (!empty(sanitize_text_field($variation['regularPrice']))) {
          $variationToCreate->set_regular_price($variation['regularPrice']);
        }

        if (!empty(sanitize_text_field($variation['salePrice']))) {
          $variationToCreate->set_sale_price($variation['salePrice']);
        }


        $stockProduct = sanitize_text_field($variation['availQuantity']);
        if (isset($stockProduct)) {
          $variationToCreate->set_manage_stock(true);
          $variationToCreate->set_stock_quantity($stockProduct);
          $variationToCreate->set_stock_status('instock');
        }
        $variationsArray = array();
        foreach ($attributesVariations as $attributesVariation) {
          $variationsArray[$attributesVariation['name']] = $attributesVariation['value'];

          $arrayImageId = array();
          // if (($importVariationImages_ebay == 'true')) {
          // wp_send_json($importVariationImages);

          $imageVariations = $attributesVariation['image'];
          if (isset($imageVariations)) {
            $imageId = false;
            foreach ($arrayImageId as $imageObject) {
              if ($imageObject->imageVariations == $imageVariations) {
                $imageId = $imageObject->id;
                break;
              }
            }
            if ($imageId != false) {
              $variationToCreate->set_image_id($imageId);
            } else {
              $imageIdVariation  =  theShark_alibay_save_single_variation_image_alibay($variationToCreate, $imageVariations);
              array_push($arrayImageId, array('image' => $imageVariations, 'id' => $imageIdVariation));
              if (isset($imageIdVariation)) {
                $variationToCreate->set_image_id($imageIdVariation);
              }
            }
          }
          // }
        };
        // wp_send_json(array('variationsArray' =\\÷/> $variationsArray, 'ttt' => $attributesVariation['value']));

        $variationToCreate->set_attributes($variationsArray);
        try {
          $variationToCreate->save();
        } catch (Exception $e) {
          $results = array(
            'error' => true,
            'data' => 'Error while inserting variations ' . $e,
            'postId' => $post_id

          );
          wp_send_json($results);
        }
      }
      $results = array(
        'error' => false,
        'error_msg' => '',
        'data' => 'Product inserted successfully',
        'postId' => $post_id

      );
      wp_send_json($results);
    } else {
      $results = array(
        'error' => true,
        'data' => 'Cannot insert empty variations',
        'postId' => $post_id

      );
      wp_send_json($results);
    }
  } catch (Exception $e) {
    $results = array(
      'error' => true,
      'data' => 'Error while inserting variations ' . $e,
      'postId' => $post_id

    );
    wp_send_json($results);
  }
}




function theShark_alibay_setProductToDraft_for_ebay_alibay()
{

  $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
  if (isset($post_id)) {
    $result = update_post_meta($post_id, 'isExpired', 'true');
    $post = array('ID' => $post_id, 'post_status' => 'draft');
    wp_update_post($post);
  }

  wp_send_json(array('result' => $result));
}













function theShark_alibay_updateProductSimple_for_ebay_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  $newProduct = isset($_POST['newProduct']) ? wc_clean($_POST['newProduct']) : array();
  if (isset($newProduct) && count($newProduct)) {
    foreach ($newProduct as $product) {

      if (isset($product['id'])) {



        if (isset($product['regularPrice'])) {
          update_post_meta($product['id'], '_regular_price', $product['regularPrice']);
          update_post_meta($product['id'], '_price',  $product['regularPrice']);
          // wc_delete_product_transients( $product['variation_id'] );

        }


        if (isset($product['availQuantity']) &&  $product['availQuantity'] > -1) {
          // $isUpdateStockPriceOk = update_post_meta($product['variation_id'], '_stock', $product['availQuantity']);

          update_post_meta($product['id'], '_stock', $product['availQuantity']);
        }



        // if (isset($product['availQuantity']) &&  $product['availQuantity'] == 0) {
        //   array_push($outOfStock, $product['variation_id']);
        // } else {
        //   array_push($arrayOfSuccess, $product['variation_id']);
        // }
        $results = array(
          'error' => false,
          'error_msg' => '',

          'data' => array('success' => true, 'sku' => $product['productSku'])
        );
        wp_send_json($results);
      } else {
        $results = array(
          'error' => false,
          'error_msg' => '',
          'data' => array('success' => true, 'sku' => $product['productSku']),
        );
        wp_send_json($results);
      }
    }
  }
}

function theShark_alibay_updateProductVariations_for_ebay_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $arrayOfError = array();
  $arrayOfSuccess = array();
  $outOfStock = array();

  $updateVariationsOnServer = isset($_POST['updateVariationsOnServer']) ? wc_clean($_POST['updateVariationsOnServer']) : array();
  $totalOldVariations = isset($_POST['totalOldVariations']) ? wc_clean($_POST['totalOldVariations']) : 1;
  // wp_send_json($updateVariationsOnServer);
  if (isset($updateVariationsOnServer) && count($updateVariationsOnServer)) {
    foreach ($updateVariationsOnServer as $product) {

      if (isset($product['variation_id'])) {
        $isUpdateRegularPriceOk = false;
        $isUpdateSalePriceOk = false;
        $isUpdateStockPriceOk = false;

        if (isset($product['salePrice'])) {
          $isUpdateRegularPriceOk = update_post_meta($product['variation_id'], '_sale_price', $product['salePrice']);
        }
        if (isset($product['regularPrice'])) {
          $isUpdateSalePriceOk = update_post_meta($product['variation_id'], '_regular_price', $product['regularPrice']);
          update_post_meta($product['variation_id'], '_price',  $product['regularPrice']);
          wc_delete_product_transients($product['variation_id']);
        }
        if (isset($product['availQuantity']) &&  $product['availQuantity'] > -1) {
          $isUpdateStockPriceOk = update_post_meta($product['variation_id'], '_stock', $product['availQuantity']);
        }

        if (isset($product['availQuantity']) &&  $product['availQuantity'] == 0) {
          array_push($outOfStock, $product['variation_id']);
        } else {
          array_push($arrayOfSuccess, $product['variation_id']);
        }
      }
    }
  }
  $results = array(
    'error' => false,
    'error_msg' => '',
    'data' => array('totalOldVariations' => $totalOldVariations, 'error' => $arrayOfError, 'success' => $arrayOfSuccess, 'outOfStock' => $outOfStock)
  );
  wp_send_json($results);
}

function theShark_alibay_getOldProductDetails_for_ebay_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
  $product = wc_get_product($post_id);

  if ($product->get_type() == 'simple') {
    $regularPrice = $product->get_regular_price();
    $salePrice = $product->get_sale_price();
    $quantity = $product->get_stock_quantity();
    wp_send_json(array('type' => 'simple', 'quantity' => $quantity, 'regularPrice' => $regularPrice, 'salePrice' => $salePrice));
  }

  if ($product->get_type() == 'variable') {
    $oldVariations = $product->get_available_variations();
    wp_send_json($oldVariations);
  }
}


function theShark_alibay_searchProductBySku_for_ebay_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $searchSkuValue = isset($_POST['searchSkuValue']) ? sanitize_text_field($_POST['searchSkuValue']) : '';

  if (isset($searchSkuValue)) {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => 20,
      'meta_query' => array(
        array(
          "key" => "_sku",
          "value" => $searchSkuValue,
          "compare" => "LIKE"
        ),
        array(
          'key' => 'productUrl', //meta key name here
          'value' => 'ebay.',
          'compare' => 'LIKE',
        )
      )
    );





    $products = new WP_Query($args);
    $finalList = array();

    if ($products->have_posts()) {
      while ($products->have_posts()) : $products->the_post();
        $theid = get_the_ID();
        $product = new WC_Product($theid);
        if (has_post_thumbnail()) {
          $thumbnail = get_post_thumbnail_id();
          $image = $thumbnail ? wp_get_attachment_url($thumbnail) : '';
        }
        $finalList[] = array(
          'sku' => $product->get_sku(),
          'id' => $theid,
          // image => wp_get_attachment_image_src( get_post_thumbnail_id($products->post->ID)),
          'image' => $image,
          'title' =>  $product->get_title(),
          'productUrl' => get_post_meta($theid, 'productUrl', true)

        );
      endwhile;
    } else {
      echo __('No products found');
    }
    wp_reset_postdata();

    wp_send_json($finalList);
  } else {
    $results = array(
      'error' => true,
      'error_msg' => 'cannot find result for the introduced sku value, please make sure the product is imported using theShark',
      'data' => ''
    );
    wp_send_json($results);
  }
}






function theShark_alibay_removeProductFromShop_for_ebay_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
  if (isset($post_id)) {
    $id_remove = wp_delete_post($post_id);
    if ($id_remove != false && isset($id_remove)) {
      $results = array(
        'error' => false,
        'error_msg' => '',
        'data' => 'removed successfully'
      );
      wp_send_json($results);
    } else {
      $results = array(
        'error' => trye,
        'error_msg' => 'error while removing the product',
        'data' => ''
      );
      wp_send_json($results);
    }
  }
}

function theShark_alibay_insertReviewsIntoProduct_for_ebay_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
  $reviews = isset($_POST['reviews']) ? wc_clean($_POST['reviews']) : array();
  $insertedSuccessfully = array();
  if (isset($post_id) && isset($reviews) && count($reviews)) {

    foreach ($reviews as $review) {
      $comment_id = wp_insert_comment(array(
        'comment_post_ID'      => sanitize_text_field($post_id), // <=== The product ID where the review will show up
        'comment_author'       => sanitize_text_field($review['username']),
        'comment_author_email' => sanitize_text_field($review['email']), // <== Important
        'comment_author_url'   => '',
        'comment_content'      => sanitize_text_field($review['review']),
        'comment_type'         => '',
        'comment_parent'       => 0,
        'user_id'              => 5, // <== Important
        'comment_author_IP'    => '',
        'comment_agent'        => '',
        'comment_date'         => date('Y-m-d H:i:s'),
        'comment_approved'     => 1,
      ));

      // HERE inserting the rating (an integer from 1 to 5)
      $response = update_comment_meta($comment_id, 'rating', sanitize_text_field($review['rating']));
      if ($response != false && isset($response)) {
        array_push($insertedSuccessfully, $comment_id);
      }
    }
    wp_send_json(array('insertedSuccessfully' => $insertedSuccessfully));
  }
}

function theShark_alibay_getAlreadyImportedProducts_for_ebay_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $listOfSkus = isset($_POST['listOfSkus']) ? wc_clean($_POST['listOfSkus']) : array();

  if (isset($listOfSkus) && count($listOfSkus)) {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => 40,
      'meta_query' => array(
        array(
          "key" => "_sku",
          "value" => $listOfSkus,
          "compare" => "IN"
        ), array(
          'key' => 'productUrl', //meta key name here
          'value' => 'ebay.',
          'compare' => 'LIKE',
        )
      )
    );
    $products = new WP_Query($args);
    $finalList = array();

    if ($products->have_posts()) {
      while ($products->have_posts()) : $products->the_post();
        $theid = get_the_ID();
        $product = new WC_Product($theid);

        $finalList[] = array(
          'sku' => $product->get_sku(),
          'id' => $theid,
          'title' =>  $product->get_title(),
          'productUrl' => get_post_meta($theid, 'productUrl', true)

        );
      endwhile;
    } else {
      echo __('No products found');
    }
    wp_reset_postdata();

    wp_send_json($finalList);
  }
}


function theShark_alibay_getSKuAbdUrlByCategory_for_ebay_alibay()
{


  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $categoryId = isset($_POST['categoryId']) ? wc_clean($_POST['categoryId']) : array();

  if (isset($categoryId)) {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => -1,
      'post_status' => array('publish'),
      'meta_query' => array(
        array(
          'key' => 'productUrl', //meta key name here
          'value' => 'ebay.',
          'compare' => 'LIKE',
        )
      ),
      'tax_query'             => array(
        array(
          'taxonomy'      => 'product_cat',
          'field' => 'term_id', //This is optional, as it defaults to 'term_id'
          'terms'         => $categoryId,
          'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
        )
      )


    );
    $products = new WP_Query($args);
    $finalList = array();

    if ($products->have_posts()) {
      while ($products->have_posts()) : $products->the_post();
        $theid = get_the_ID();
        $product = new WC_Product($theid);
        $finalList[] = array(
          sku => $product->get_sku(),
          id => $theid,
          productUrl => get_post_meta($theid, 'productUrl', true)

        );
      endwhile;
    } else {
      echo __('No products found');
    }
    wp_reset_postdata();

    wp_send_json($finalList);
  }
}


function theShark_alibay_save_product_images_for_ebay_alibay($product, $images)
{
  $imageUploadErrors = array(); // Array to hold any image upload errors
  if (is_array($images)) {
    $gallery = array();
    foreach ($images as $key => $image) {
      if (isset($image)) {
        $upload = wc_rest_upload_image_from_url(esc_url_raw($image));

        if (is_wp_error($upload)) {
          // Collect the error message and continue with the next image
          $error_message = $upload->get_error_message();
          array_push($imageUploadErrors, $error_message);

          if (!apply_filters('woocommerce_rest_suppress_image_upload_error', false, $upload, $product->get_id(), $images)) {
            continue;
          }
        } else {
          $attachment_id = wc_rest_set_uploaded_image_as_attachment($upload, $product->get_id());
          if ($key == 0) {
            $product->set_image_id($attachment_id);
          } else {
            array_push($gallery, $attachment_id);
          }
        }
      }
    }
    if (!empty($gallery)) {
      $product->set_gallery_image_ids($gallery);
    }
  } else {
    $product->set_image_id('');
    $product->set_gallery_image_ids(array());
  }
  return array('product' => $product, 'errors' => $imageUploadErrors); // Return the product and any errors
}






function theShark_alibay_insertReviewsIntoProductRM_for_ebay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $post_id = isset($_POST['post_id']) ? sanitize_text_field($_POST['post_id']) : '';
  $reviews = isset($_POST['reviews']) ? wc_clean($_POST['reviews']) : array();
  $insertedSuccessfully = array();
  if (isset($post_id) && isset($reviews) && count($reviews)) {

    foreach ($reviews as $review) {
      $comment_id = wp_insert_comment(array(
        'comment_post_ID'      => sanitize_text_field($post_id), // <=== The product ID where the review will show up
        'comment_author'       => sanitize_text_field($review['username']),
        'comment_author_email' => sanitize_text_field($review['email']), // <== Important
        'comment_author_url'   => '',
        'comment_content'      => $review['review'],
        'comment_type'         => '',
        'comment_parent'       => 0,
        'user_id'              => 5, // <== Important
        'comment_author_IP'    => '',
        'comment_agent'        => '',
        'comment_date'         => $review['datecreation'],
        'comment_approved'     => 1,
      ));

      // HERE inserting the rating (an integer from 1 to 5)
      $response = update_comment_meta($comment_id, 'rating', sanitize_text_field($review['rating']));
      if ($response != false && isset($response)) {
        array_push($insertedSuccessfully, $comment_id);
      }
    }
    wp_send_json(array('insertedSuccessfully' => $insertedSuccessfully));
  }
}


function theShark_alibay_stopAutomaticUpdates_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  update_option('stop_automatic_update_for_ebay', 'stop');
  wp_send_json(array('stop_automatic' => true));
}










function theShark_alibay_removeProductFromShop_forEBAY_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $post_id = isset($_POST['post_id']) ? wc_clean($_POST['post_id']) : '';
  if (isset($post_id)) {
    $id_remove = wp_delete_post($post_id);
    if ($id_remove != false && isset($id_remove)) {
      $results = array(
        'error' => false,
        'error_msg' => '',
        'data' => 'removed successfully'
      );
      wp_send_json($results);
    } else {
      $results = array(
        'error' => trye,
        'error_msg' => 'error while removing the product',
        'data' => ''
      );
      wp_send_json($results);
    }
  }
}




function get_aliexpress_products_alibay()
{



  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}
  

  



  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $products_per_page = 20;

  // Define arrays to store selected supplier and product type filters
  $selected_suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : array();
  $selected_product_types = isset($_POST['product_types']) ? $_POST['product_types'] : array();

  // Query for products based on selected supplier and product type filters
  $args = array(
    'post_type' => 'product', // Adjust post type as needed
    'posts_per_page' => $products_per_page,
    'paged' => $page
  );

  // If specific supplier filters are selected, modify the query accordingly
  if (!empty($selected_suppliers)) {
    // Modify the meta_query to include the selected supplier filters
    $meta_query_suppliers = array('relation' => 'OR');

    foreach ($selected_suppliers as $supplier) {
      $meta_query_suppliers[] = array(
        'key' => 'productUrl', // Adjust metadata key as needed
        'value' => $supplier,
        'compare' => 'LIKE' // Use 'LIKE' for a partial match
      );
    }

    $args['meta_query'] = $meta_query_suppliers;
  }

  // If specific product type filters are selected, modify the query accordingly
  if (!empty($selected_product_types)) {
    // Modify the tax_query to include the selected product type filters
    $tax_query_product_types = array('relation' => 'OR');

    foreach ($selected_product_types as $product_type) {
      $tax_query_product_types[] = array(
        'taxonomy' => 'product_type', // Adjust taxonomy as needed
        'field' => 'slug',
        'terms' => $product_type
      );
    }

    $args['tax_query'] = $tax_query_product_types;
  }

  $products_query = new WP_Query($args);

  // Calculate total pages
  $total_pages = $products_query->max_num_pages;
  $total_products = $products_query->found_posts;

  // Process and prepare the products data
  $products = array();
  if ($products_query->have_posts()) {
    while ($products_query->have_posts()) {
      $products_query->the_post();
      global $product;
      $theid = get_the_ID();

      $product_data = array(
        'image' => get_the_post_thumbnail_url(),
        'sku' => $product->get_sku(),
        'id' => get_the_ID(),
        'title' => get_the_title(),
        'permalink' =>  get_edit_post_link($theid),
        'permalink_preview' => get_permalink(),
        'productUrl' => get_post_meta($theid, 'productUrl', true),
        'status' => get_post_status($theid),
        'lastUpdatedDate' => get_post_meta($theid, 'lastUpdatedDate', true),
        'productType' => $product->get_type(),
        'variationCount' => 0, // Initialize variation count
        'selectedCurrency' => get_post_meta($theid, 'selectedCurrency', true)

        // Add other product data fields as needed
      );

      if ($product->is_type('variable')) {
        // Get variations
        $variations = $product->get_children();

        // Count the number of variations
        $product_data['variationCount'] = count($variations);
      }


      $products[] = $product_data;
    }
  }

  // Return the products data and total pages as a JSON response
  wp_send_json(array(
    'products' => $products,
    'total_pages' => $total_pages,
    'total_products' => $total_products, // Add this line
  ));

  // Don't forget to exit
  wp_die();
}





function getProduct_FROMWP_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $products_per_page = 20;

  // Query for products with metadata key 'productUrl' containing 'aliexpress'
  $args = array(
    'post_type'      => 'product',
    'post_status' => array('publish', 'draft'),
    'paged' => $page,
    'posts_per_page' => 20,
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'aliexpress',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'ebay',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'amazon',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'etsy',
        'compare' => 'LIKE'
      )
    )
  );

  $products_query = new WP_Query($args);

  // Calculate total pages
  $total_pages = $products_query->max_num_pages;

  // Process and prepare the products data
  $products = array();
  if ($products_query->have_posts()) {
    while ($products_query->have_posts()) {
      $products_query->the_post();
      global $product;
      $theid = get_the_ID();

      $product_data = array(
        'image' => get_the_post_thumbnail_url(),
        'sku' => $product->get_sku(), // Get SKU using WooCommerce's get_sku() method
        'id' => get_the_ID(),
        'title' => get_the_title(),
        'permalink' =>  get_edit_post_link($theid),
        'permalink_preview' => get_permalink(),
        'productUrl' => get_post_meta($theid, 'productUrl', true),
        'status' => get_post_status($theid), // Get the product status
        'lastUpdatedDate' => get_post_meta($theid, 'lastUpdatedDate', true),
        'productType' => $product->get_type(),



        // Add other product data fields as needed
      );
      $products[] = $product_data;
    }
  }

  // Return the products data and total pages as a JSON response
  wp_send_json(array(
    'products' => $products,
    'total_pages' => $total_pages,
  ));

  // Don't forget to exit
  wp_die();
}



function theShark_alibay_searchProductBySku()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $searchSkuValue = isset($_POST['searchSkuValue']) ? wc_clean($_POST['searchSkuValue']) : '';

  if (isset($searchSkuValue)) {
    $args = array(
      'post_type'      => 'product',
      'posts_per_page' => 20,
      'meta_query' => array(
        array(
          "key" => "_sku",
          "value" => $searchSkuValue,
          "compare" => "LIKE"
        )

      )
    );





    $products_query = new WP_Query($args);

    // Calculate total pages
    $total_pages = $products_query->max_num_pages;

    // Process and prepare the products data
    $products = array();
    if ($products_query->have_posts()) {
      while ($products_query->have_posts()) {
        $products_query->the_post();
        global $product;
        $theid = get_the_ID();

        $product_data = array(
          'image' => get_the_post_thumbnail_url(),
          'sku' => $product->get_sku(), // Get SKU using WooCommerce's get_sku() method
          'id' => get_the_ID(),
          'title' => get_the_title(),
          'permalink' =>  get_edit_post_link($theid),
          'permalink_preview' => get_permalink(),
          'productUrl' => get_post_meta($theid, 'productUrl', true),
          'status' => get_post_status($theid), // Get the product status
          'lastUpdatedDate' => get_post_meta($theid, 'lastUpdatedDate', true),
          'productType' => $product->get_type(),



          // Add other product data fields as needed
        );
        $products[] = $product_data;
      }
    }

    // Return the products data and total pages as a JSON response
    wp_send_json(array(
      'products' => $products,
      'total_pages' => $total_pages,
    ));

    // Don't forget to exit
    wp_die();
  }
}







function get_products_by_type_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $product_type = isset($_POST['product_type']) ? wc_clean($_POST['product_type']) : 'simple';
  // $product_type  = 'external';
  $products_per_page = 20;




  $args = array(
    'post_type'      => 'product',
    'post_status' => array('publish', 'draft'),
    'paged' => $page,
    'posts_per_page' => 20,

    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'aliexpress',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'ebay',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'amazon',
        'compare' => 'LIKE'
      ),
      array(
        'key' => 'productUrl', //meta key name here
        'value' => 'etsy',
        'compare' => 'LIKE'
      )
    ),
    'tax_query' => array(
      array(
        'taxonomy' => 'product_type', // Taxonomy name for product type in WooCommerce
        'field' => 'slug', // You can also use 'term_id', 'name', or 'id' here
        'terms' => $product_type, // The product type you want to search for (e.g., 'simple')
        'operator' => 'IN', // Use 'IN' to search for an exact match
      ),
    ),
  );



  $products_query = new WP_Query($args);

  // Calculate total pages
  $total_pages = $products_query->max_num_pages;

  // Process and prepare the products data
  $products = array();
  if ($products_query->have_posts()) {
    while ($products_query->have_posts()) {
      $products_query->the_post();
      global $product;
      $theid = get_the_ID();

      $product_data = array(
        'image' => get_the_post_thumbnail_url(),
        'sku' => $product->get_sku(), // Get SKU using WooCommerce's get_sku() method
        'id' => get_the_ID(),
        'title' => get_the_title(),
        'permalink' =>  get_edit_post_link($theid),
        'permalink_preview' => get_permalink(),
        'productUrl' => get_post_meta($theid, 'productUrl', true),
        'status' => get_post_status($theid), // Get the product status
        'lastUpdatedDate' => get_post_meta($theid, 'lastUpdatedDate', true),
        'productType' => $product->get_type(),

        // Add other product data fields as needed
      );
      $products[] = $product_data;
    }
  }

  // Return the products data and total pages as a JSON response
  wp_send_json(array(
    'products' => $products,
    'total_pages' => $total_pages,
  ));

  // Don't forget to exit
  wp_die();
}






// Define a function to retrieve product details
function get_product_details_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  try {
    // Check if the productId parameter is provided in the AJAX request
    if (isset($_GET['productId'])) {
      $product_id = wc_clean($_GET['productId']);

      // Get the product by ID
      $product = wc_get_product($product_id);

      if ($product) {
        $product_data = array(
          'stock' => $product->get_stock_quantity(),
          'price' => $product->get_price(),
          'salePrice' => $product->get_sale_price(),
        );

        wp_send_json_success($product_data);
      } else {
        wp_send_json_error(array('message' => 'Product not found'));
      }
    } else {
      wp_send_json_error(array('message' => 'Product ID not provided'));
    }

    // Always exit to prevent further WordPress processing
    wp_die();
  } catch (Exception $e) {
    $results = array(
      'error' => true,
      'error_msg' => 'Error while retrieving details' . $e->getMessage(),
      'data' => ''
    );
    wp_send_json($results);
  }
}





function update_variations_on_woocommerce_alibay()
{



  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  // Check if the user is logged in and has permission
      if (!current_user_can('manage_options')) {

    wp_send_json_error(array('message' => 'Permission denied'));
  }
  $isPremuim = false;
  if ($isPremuim) {
    checkDailyUpdatePemuim_alibay();
  } else {
    checkDailyUpdate_alibay();
  }

  // $post_id = wc_clean($_POST['parentId']);
  $post_id = wc_get_product_id_by_sku($_POST['parentId']);

  $lastUpdatedDate = current_time('mysql', 1);
  update_post_meta($post_id, 'lastUpdatedDate', $lastUpdatedDate);

  // Check if the AJAX request contains the update type parameter
  $updateType =  wc_clean($_POST['updateType']);
  // wp_send_json_success($post_id);

  // Check if the AJAX request contains variations data
  if ($updateType === 'variations' && isset($_POST['variations'])) {
    $variations = $_POST['variations'];

    // Loop through the variations and update them in WooCommerce
    foreach ($variations as $variation) {
      $sku = wc_clean($variation['sku']);
      $quantity = wc_clean($variation['quantity']);
      $price = wc_clean($variation['regularPrice']);
      $salePrice = wc_clean($variation['salePrice']);
      $regularPrice = wc_clean($variation['regularPrice']);

      // Get the product variation ID by SKU
      $product_variation_id = wc_get_product_id_by_sku($sku);

      // Check if the product variation exists
      if ($product_variation_id) {
        $variation_data = array();

        // Update stock quantity
        update_post_meta($product_variation_id, '_stock', $quantity);

        // Update regular price and sale price if provided
        if ($regularPrice !== '') {
          update_post_meta($product_variation_id, '_regular_price', $regularPrice);
          update_post_meta($product_variation_id, '_price', $regularPrice);
        }
        if ($salePrice !== '') {
          update_post_meta($product_variation_id, '_sale_price', $salePrice);
        }

        // Update the variation data using WooCommerce functions
        wc_delete_product_transients($product_variation_id); // Clear/refresh the variation cache
      }
    }
    $currentUpdateCount = get_option('alibay_daily_update_count', 0);
    update_option('alibay_daily_update_count', ++$currentUpdateCount);

    wp_send_json_success(array('message' => 'Variations updated successfully'));
  } elseif ($updateType === 'simple' && isset($_POST['variations'])) {
    $variations = $_POST['variations'];
    // wp_send_json_success($variations);

    // Loop through the simple products and update them in WooCommerce
    foreach ($variations as $simpleProduct) {
      $product_id = wc_clean($simpleProduct['sku']);
      $quantity = wc_clean($simpleProduct['quantity']);
      $price = wc_clean($simpleProduct['regularPrice']);
      $salePrice = wc_clean($simpleProduct['salePrice']);
      $regularPrice = wc_clean($simpleProduct['regularPrice']);

      // Get the simple product ID by SKU
      // $product_id = wc_get_product_id_by_sku($sku);

      // Check if the simple product exists
      if ($product_id) {
        $product = wc_get_product($post_id);

        // Update stock quantity
        update_post_meta($product_id, '_stock', $quantity);


        if ($regularPrice !== '') {
          $product->set_regular_price($regularPrice);
          $product->set_price($regularPrice);
        }
        if ($salePrice !== '') {
          $product->set_sale_price($salePrice);
        }

        $product->save();
      }
    }

    $currentUpdateCount = get_option('alibay_daily_update_count', 0);
    update_option('alibay_daily_update_count', ++$currentUpdateCount);

    wp_send_json_success(array('message' => 'Simple products updated successfully'));
  } else {
    wp_send_json_error(array('message' => 'Invalid update type or no data provided'));
  }

  // Always exit to prevent further WordPress processing
  wp_die();
}



function set_product_to_draft_callback_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  // Check if the user has the necessary permissions (e.g., manage_options)
      if (!current_user_can('manage_options')) {

    wp_send_json_error(array('message' => 'Permission denied'));
  }

  // Get the product ID from the AJAX request
  $product_id = wc_clean($_POST['product_id']);

  // Check if the product ID is valid
  if ($product_id <= 0) {
    wp_send_json_error(array('message' => 'Invalid product ID'));
  }

  // Update the product status to "draft"
  $update_product = array(
    'ID' => $product_id,
    'post_status' => 'draft'
  );

  $updated = wp_update_post($update_product);

  if ($updated !== 0) {
    // Send a success response
    wp_send_json_success(array('message' => 'Product set to draft successfully'));
  } else {
    // Send an error response if the update fails
    wp_send_json_error(array('message' => 'Failed to set product to draft'));
  }
}

require_once(plugin_dir_path(__FILE__) . 'lib/simple_html_dom.php');



function fetchemote_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  try {
    // eBay URL to fetch HTML from
    $ebay_url = isset($_POST['url']) ? sanitize_text_field($_POST['url']) : '';

    // Check if the eBay URL is provided and valid
    if (empty($ebay_url) || !filter_var($ebay_url, FILTER_VALIDATE_URL)) {
      wp_send_json_error(array('message' => 'Invalid eBay URL'));
    }

    // Make an HTTP GET request to fetch the HTML content
    $response = wp_safe_remote_get($ebay_url);

    // Check for HTTP request errors
    if (is_wp_error($response)) {
      wp_send_json_error(array('message' => 'Error fetching eBay product page'));
    }

    // Get the HTML content from the response
    $html_content = wp_remote_retrieve_body($response);

    // Load the HTML content into a DOMDocument
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($html_content);
    libxml_clear_errors();


    // XPath query to locate the "Product not found" message
    $xpath = new DOMXPath($dom);
    $not_found_elements = $xpath->query('//p[@class="error-header__headline" and text()="Nous avons cherché partout."]');

    // Check if the "Product not found" message is found
    if ($not_found_elements->length > 0) {
      wp_send_json_error(array('message' => 'Product not found', 'status_code' => 488));
    } else {
      wp_send_json_success(array('message' => 'Product found', 'status_code' => 200));
    }
  } catch (Exception $e) {
    $results = array(
      'error' => true,
      'error_msg' => 'Error received when trying to insert the product: ' . $e->getMessage(),
      'data' => ''
    );
    wp_send_json($results);
  }
}





function load_woocommerce_variations_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  // if (isset($_POST['action']) && $_POST['action'] === 'load_woocommerce_variations') {
  // Get the product ID from the POST request
  $product_id = isset($_POST['product_id']) ? wc_clean($_POST['product_id']) : 0;

  // Check if the product ID is valid and exists
  if ($product_id > 0 && wc_get_product($product_id)) {
    // Get the product object
    $product = wc_get_product($product_id);

    // Check if the product has variations (for variable products)
    if ($product->is_type('variable')) {
      $variations = $product->get_available_variations();

      if (!empty($variations)) {
        // Send the variations as a JSON response
        wp_send_json_success(['variations' => $variations]);
      } else {
        // Variations not found, send an error response if needed
        wp_send_json_error(['message' => 'Variations not found.']);
      }
    } else {
      // This product type does not have variations
      wp_send_json_error(['message' => 'This product type does not have variations.']);
    }
  } else {
    // Invalid or non-existent product ID, send an error response
    wp_send_json_error(['message' => 'Invalid or non-existent product ID.']);
  }
  // }
  // wp_die();
}





// Add this PHP function to your WordPress plugin or theme functions.php file
function delete_product_callback_alibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  




  // if (isset($_POST['action']) && $_POST['action'] === 'delete_product') {
  // Get the product ID from the POST request
  $product_id = isset($_POST['product_id']) ? wc_clean($_POST['product_id']) : 0;

  if ($product_id > 0) {
    // Perform the product deletion operation here
    // For example, you can use the WordPress function wp_delete_post
    $result = wp_delete_post($product_id, true);

    if ($result) {
      // Deletion was successful, send a success response if needed
      wp_send_json_success(['success' => true]);
    } else {
      // Deletion failed, send an error response if needed
      wp_send_json_error(['success' => false, 'message' => 'Product deletion failed']);
    }
  }
  // }
  // wp_die();
}





function search_product_by_store_alibay($ebay_url)
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  try {
    // eBay URL to fetch HTML from
    $ebay_url = isset($_POST['ebay_url']) ? sanitize_text_field($_POST['ebay_url']) : '';

    // Check if the eBay URL is provided and valid
    if (empty($ebay_url) || !filter_var($ebay_url, FILTER_VALIDATE_URL)) {
      wp_send_json_error(array('message' => 'Invalid eBay URL'));
    }

    // Make an HTTP GET request to fetch the HTML content
    $response = wp_safe_remote_get($ebay_url);

    // Check for HTTP request errors
    if (is_wp_error($response)) {
      wp_send_json_error(array('message' => 'Error fetching eBay product page'));
    }

    // Get the HTML content from the response
    $html_content = wp_remote_retrieve_body($response);


    wp_send_json_success($html_content);
    // }
  } catch (Exception $e) {
    $results = array(
      'error' => true,
      'error_msg' => 'Error received when trying to insert the product: ' . $e->getMessage(),
      'data' => ''
    );
    wp_send_json($results);
  }
}


function updateProductUrlAlibay()
{

  // Check if the user is authenticated and has the necessary permissions
      if (!current_user_can('manage_options')) {

    // Handle unauthorized access
    wp_die('Unauthorized access', 'Error', array('response' => 403));
  }

   if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    wp_die('Unauthorized access', 'Error', array('response' => 403));
}


  



  $productUrl = isset($_POST['productUrl']) ? wc_clean($_POST['productUrl']) : '';
  $post_id = isset($_POST['product_id']) ? wc_clean($_POST['product_id']) : '';
  if (isset($productUrl)) {
    update_post_meta($post_id, 'productUrl', $productUrl);
  }
}





function create_woocommerce_categories_and_get_ids_alibay($categories)
{
  $category_ids = array();

  foreach ($categories as $category_name) {
    // Check if the product category already exists
    $term = term_exists($category_name, 'product_cat');
    if ($term) {
      // If the category exists, add its ID to the array
      $category_ids[] = (int) $term['term_id'];
    } else {
      // Create the product category if it does not exist and add its ID to the array
      $new_term = wp_insert_term(
        $category_name, // the term 
        'product_cat',  // the taxonomy for WooCommerce product categories
        array(
          'description' => 'A description for this category',
          'slug'        => sanitize_title($category_name)
        )
      );

      if (!is_wp_error($new_term)) {
        $category_ids[] = $new_term['term_id'];
      }
    }
  }

  return $category_ids;
}





function my_plugin_add_webp_support_alibay($mime_types)
{
  // Add WebP to the list of mime types
  $mime_types['webp'] = 'image/webp';

  return $mime_types;
}
add_filter('mime_types', 'my_plugin_add_webp_support_alibay');

function my_plugin_webp_is_displayable_alibay($result, $path)
{
  if ($result === false) {
    $displayable_image_types = array(IMAGETYPE_WEBP);
    $info = @getimagesize($path);

    if (empty($info)) {
      $result = false;
    } elseif (!in_array($info[2], $displayable_image_types)) {
      $result = false;
    } else {
      $result = true;
    }
  }

  return $result;
}



add_filter('file_is_displayable_image', 'my_plugin_webp_is_displayable_alibay', 10, 2);




add_action('wp_ajax_theShark-alibay-insertVariations', 'theShark_alibay_insertVariations');
add_action('wp_ajax_theShark_alibay_insertProductInWoocommerceAffiliate', 'theShark_alibay_insertProductInWoocommerceAffiliate');
add_action('wp_ajax_alibay-get-sku-and-url-by-Category_for_ebay', 'theShark_alibay_getSKuAbdUrlByCategory_for_ebay_alibay');
add_action('wp_ajax_alibay-get-already-imported-products_for_ebay', 'theShark_alibay_getAlreadyImportedProducts_for_ebay_alibay');
add_action('wp_ajax_alibay-insert-reviews-to-product_for_ebay', 'theShark_alibay_insertReviewsIntoProduct_for_ebay_alibay');
add_action('wp_ajax_alibay-remove-product-from-wp_for_ebay', 'theShark_alibay_removeProductFromShop_for_ebay_alibay');
add_action('wp_ajax_alibay-search-product-by-sku_for_ebay', 'theShark_alibay_searchProductBySku_for_ebay_alibay');
add_action('wp_ajax_alibay-update-product-variations_for_ebay', 'theShark_alibay_updateProductVariations_for_ebay_alibay');
add_action('wp_ajax_alibay-update-product-simple_for_ebay', 'theShark_alibay_updateProductSimple_for_ebay_alibay');
add_action('wp_ajax_alibay-get-old-product-details_for_ebay', 'theShark_alibay_getOldProductDetails_for_ebay_alibay');
add_action('wp_ajax_alibay-insert-reviews-to-productRM_for_ebay', 'theShark_alibay_insertReviewsIntoProductRM_for_ebay');
add_action('wp_ajax_alibay-stop_automatic_update_for_ebay', 'theShark_alibay_stopAutomaticUpdates_alibay');
add_action('wp_ajax_remove-product-from-wp-for-ebay', 'theShark_alibay_removeProductFromShop_forEBAY_alibay');
add_action('wp_ajax_insert-reviews-to-productRM-alibay', 'theShark_alibay_insertReviewsIntoProductRM_PREMUIM_PLUGIN_alibay');
add_action('wp_ajax_restoreConfiguration-alibay', 'theShark_alibay_restoreConfiguration_alibay');
add_action('wp_ajax_wooshark-insert-product-alibay', 'theShark_alibay_insertProductInWoocommerce');
add_action('wp_ajax_get_products-alibay', 'getProduct_FROMWP_alibay');
add_action('wp_ajax_search-category-by-name-alibay', 'theShark_alibay_searchCategoryByName');
add_action('wp_ajax_get-sku-and-url-by-Category-alibay', 'theShark_alibay_getSKuAbdUrlByCategory_alibay');
add_action('wp_ajax_get-already-imported-products-alibay', 'theShark_alibay_getAlreadyImportedProducts');
add_action('wp_ajax_insert-reviews-to-product-alibay', 'theShark_alibay_insertReviewsIntoProduct');
add_action('wp_ajax_remove-product-from-wp-alibay', 'theShark_alibay_removeProductFromShop');
add_action('wp_ajax_search-product-by-sku-alibay', 'theShark_alibay_searchProductBySku');
add_action('wp_ajax_get_products-draft-alibay', 'theShark_alibay_getProductsDraft');
add_action('wp_ajax_get-old-product-details-alibay', 'theShark_alibay_getOldProductDetails');
add_action('wp_ajax_get-product-by-id-alibay', 'theShark_alibay_searchProductByIdReviews_alibay');
add_action('wp_ajax_saveOptionsDB-alibay', 'theShark_alibay_saveOptionsDB_alibay');
add_action('wp_ajax_getProductsCount-alibay', 'theShark_alibay_getProductsCount_FROM_WP');
add_action('wp_ajax_get_categories-alibay', 'theShark_alibay_get_categories_FROMWP');
add_action('wp_ajax_getProductsCount_for_ebay', 'getProductsCount_FROM_WP_for_ebay_alibay');
add_action('wp_ajax_search_product_by_store_alibay', 'search_product_by_store_alibay');
add_action('wp_ajax_load_woocommerce_variations_alibay', 'load_woocommerce_variations_alibay');
add_action('wp_ajax_get_product_details_alibay', 'get_product_details_alibay');
add_action('wp_ajax_get_products_by_type_alibay', 'get_products_by_type_alibay');
add_action('wp_ajax_get_aliexpress_products_alibay', 'get_aliexpress_products_alibay');
add_action('wp_ajax_update_variations_on_woocommerce_alibay', 'update_variations_on_woocommerce_alibay');
add_action('wp_ajax_set_product_to_draft_callback_alibay', 'set_product_to_draft_callback_alibay');
add_action('wp_ajax_fetchemote_alibay', 'fetchemote_alibay');
add_action('wp_ajax_delete_product_callback_alibay', 'delete_product_callback_alibay');
add_action('wp_ajax_updateProductUrlAlibay', 'updateProductUrlAlibay');
