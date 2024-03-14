<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pektsekye_ProductCategoryDropdowns_Block_Selector {

  protected $_widgetId = '';  
  protected $_title = '';     
  
     
  public function setWidgetId($id){
    $this->_widgetId = $id;
  } 
 
    
  public function getWidgetId(){
    return $this->_widgetId;
  } 
 
   
  public function getCategoriesJson(){ 
       
    $rootCategories = array();
    $categoryTree = array();       
    
    $taxonomy = 'product_cat';
    $args = array('orderby' => 'parent', 'order' => 'DESC', 'fields' => 'all');
    
    $ids = wc_get_products(array('return' =>'ids', 'limit' => -1));
    
    $productCategories = wp_get_object_terms($ids, $taxonomy, $args); // only get categories that have products
    
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
                $categoryTree[$id] = array('title' => wp_specialchars_decode($parentCat->name), 'url' => get_term_link($parentCat), 'children' => array());  
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
    
        $categoryTree[$catId]['title'] = wp_specialchars_decode($category->name);
        $categoryTree[$catId]['url'] = get_term_link($category);			
    }

    $rootCategories = array_keys($rootCategories);
    
    return json_encode(array('rootCategoryIds' => $rootCategories, 'categories' => $categoryTree));       
  }   
  
  
  public function getSelectedIdsJson(){ 
    $selectedIds = array();
    if (is_product_category()) {
      global $wp_query;
      $cat = $wp_query->get_queried_object();
      $categoryId = $cat->term_id;
      $parentcats = get_ancestors($categoryId, 'product_cat');
      $selectedIds = array_reverse($parentcats);
      $selectedIds[] = $categoryId;      
    }
    return json_encode($selectedIds);      
  }
  

  public function toHtml(){    
    include(Pektsekye_PCD()->getPluginPath() . 'view/frontend/templates/selector.php');
  }


}
