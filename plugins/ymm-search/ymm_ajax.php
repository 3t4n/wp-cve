<?php

error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_GET['action']) || !isset($_GET['values']))
  exit;
  
define('SHORTINIT', true);

require '../../../wp-load.php';
require_once( ABSPATH . WPINC . '/formatting.php' );

$values = array_map('sanitize_text_field', array_map('stripslashes', (array) $_GET['values']));


if ($_GET['action'] == 'ymm_selector_fetch'){

  $categoryId = isset($_GET['cId']) ? (int) $_GET['cId'] : 0;

  $whereProducts = ''; 
  if ($categoryId > 0){
    $select = "SELECT p.ID FROM {$wpdb->posts} p
               JOIN {$wpdb->term_relationships} tr 
                ON p.ID = tr.object_id AND p.post_type = 'product' AND p.post_status = 'publish'
               JOIN {$wpdb->term_taxonomy} tt 
                ON  tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = 'product_cat' 
               WHERE tt.term_id = {$categoryId}";  
                  
    $productIds = (array) $wpdb->get_col($select);
    if (count($productIds) > 0){
      $whereProducts = ' AND product_id IN ('.implode(',', $productIds).')';
    }   
  }
  
  $nextlevel = count($values);
  
  if ($nextlevel == 0){
    $select = "SELECT DISTINCT make FROM {$wpdb->base_prefix}ymm WHERE make != '' {$whereProducts} ORDER BY make";
    $values = (array) $wpdb->get_col($select);
  } else if ($nextlevel == 1){
    $select = "SELECT DISTINCT model FROM {$wpdb->base_prefix}ymm WHERE make = '".esc_sql($values[0])."' AND model != '' {$whereProducts} ORDER BY model";
    $values = (array) $wpdb->get_col($select);      
  } else {
    $select = "SELECT DISTINCT year_from, year_to FROM {$wpdb->base_prefix}ymm WHERE make = '".esc_sql($values[0])."' AND model = '".esc_sql($values[1])."' AND (year_from != 0 or year_to != 0) {$whereProducts}";      
    $rows = (array) $wpdb->get_results($select, ARRAY_A);

    $y = array();
    
    foreach ($rows as $r) {

      $from = (int) $r['year_from'];
      $to = (int) $r['year_to'];	

      if ($from == 0){
        $y[$to] = 1;          
      } elseif ($to == 0){
        $y[$from] = 1;
      } elseif ($from == $to){
        $y[$from] = 1;
      } elseif ($from < $to){          	
        while ($from <= $to){
          $y[$from] = 1;
          $from++;
        }            
      }
    } 

    krsort($y);
              
    $values = array_keys($y);
  }
  
echo json_encode($values);
exit;


} elseif ($_GET['action'] == 'ymm_selector_get_categories'){

  if (count($values) < 3){
    echo '{}'; 
    exit;     
  }
  
  require_once( ABSPATH . WPINC . '/link-template.php' );
  require_once( ABSPATH . WPINC . '/taxonomy.php' );
  require_once( ABSPATH . WPINC . '/class-wp-taxonomy.php' );
  require_once( ABSPATH . WPINC . '/rewrite.php' );
  require_once( ABSPATH . WPINC . '/class-wp-rewrite.php');
  require_once( ABSPATH . WPINC . '/class-wp-term.php' );
  require_once( ABSPATH . WPINC . '/class-wp-term-query.php' );
  require_once( ABSPATH . WPINC . '/post-formats.php' );
  require_once( ABSPATH . WPINC . '/meta.php' );
  require_once( ABSPATH . WPINC . '/class-wp-meta-query.php' );
  require_once( ABSPATH . WPINC . '/post.php' );
  require_once( ABSPATH . WPINC . '/l10n.php' );
  wp_plugin_directory_constants();

  $GLOBALS['wp_rewrite'] = new WP_Rewrite();

  $permalinks = get_option('woocommerce_permalinks', array());
  $permalinks['category_rewrite_slug']  = untrailingslashit( empty( $permalinks['category_base'] ) ? _x( 'product-category', 'slug', 'woocommerce' )   : $permalinks['category_base'] );

  register_taxonomy( 'product_cat',
    array( 'product' ) ,
    array(
      'hierarchical'          => true,
      'update_count_callback' => '_wc_term_recount',
      'show_ui'               => true,
      'query_var'             => true,
      'rewrite'          => array(
        'slug'         => $permalinks['category_rewrite_slug'],
        'with_front'   => false,
        'hierarchical' => true
      )
     )
  );

  $year = (int) $values[2];
  $select = "SELECT DISTINCT product_id FROM {$wpdb->base_prefix}ymm WHERE (make = '".esc_sql($values[0])."' or make = '') AND (model = '".esc_sql($values[1])."' or model = '') AND (year_from <= {$year} or year_from = 0) AND (year_to >= {$year} or year_to = 0)";

  $pIds = (array) $wpdb->get_col($select);
  
  if (count($pIds) == 0){
    echo '{}';
    exit;     
  }
      
  $rootCategories = array();
  $categoryTree = array();       
  
  $taxonomy = 'product_cat';
  $args = array('orderby' => 'parent', 'order' => 'DESC', 'fields' => 'all');

  $productCategories = wp_get_object_terms($pIds, $taxonomy, $args);

  $childIds = array();
     
  foreach($productCategories as $category){
      $catId = $category->term_id; 

      $parentIds = get_ancestors($catId, $taxonomy);
    
      if (count($parentIds) == 0){
        $rootCategories[$catId] = 1;
      } else {
        $parentIds = array_reverse($parentIds);
        $rootCategories[$parentIds[0]] = 1;
    
        foreach ($parentIds as $i => $id) {
    
          $parentCat = get_term($id, $taxonomy);

          if (!is_wp_error($parentCat) && $parentCat) {

            if (!isset($categoryTree[$id])){
              $categoryTree[$id] = array('title' => $parentCat->name, 'children' => array());  
            }
            
            $nextInd = $i + 1;
            $childId = isset($parentIds[$nextInd]) ? $parentIds[$nextInd] : $catId;
            if (!isset($childIds[$childId])){ //no duplicates
              $categoryTree[$id]['children'][] = $childId;
              $childIds[$childId] = 1;
            }  
          }
        }
      }
  
      $categoryTree[$catId]['title'] = $category->name;
      $categoryTree[$catId]['url'] = get_term_meta($catId, 'display_type', true) != 'subcategories' ? get_term_link($category) : '';			
  }

  $rootCategories = array_keys($rootCategories);

  echo json_encode(array('rootCategoryIds'=>$rootCategories,'categories'=>$categoryTree));
  exit;  
  
  
}
