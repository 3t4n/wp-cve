<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Model_Option_Value {  
  
  protected $_wpdb;
  protected $_mainTable;         
          
      
  public function __construct(){
    global $wpdb;
    
    $this->_wpdb = $wpdb;   
    $this->_mainTable = "{$this->_wpdb->base_prefix}pofw_product_option_value";     
  }	


  public function getProductValues($productId){ 
    $productId = (int) $productId;
    
    $select = "SELECT * FROM {$this->_mainTable} WHERE product_id = {$productId} ORDER BY sort_order, title"; 

    return $this->_wpdb->get_results($select, ARRAY_A);    
  }
  

  public function saveValues($productId, $optionId, $values){ 
    $productId = (int) $productId;
    $optionId = (int) $optionId;
    foreach($values as $value){
      $valueId = (int) $value['value_id'];
      
      if (isset($value['is_delete']) && $value['is_delete'] == 1){
        $this->deleteValue($valueId);
        continue;
      }
              
      $title = esc_sql($value['title']);
      $price = (float) $value['price'];         
      $sortOrder = (int) $value['sort_order'];            
      if ($valueId > 0){
        $this->_wpdb->query("UPDATE {$this->_mainTable} SET title = '{$title}', price = {$price}, sort_order = {$sortOrder}  WHERE value_id = {$valueId}");                    
      } else {
        $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, option_id = {$optionId}, title = '{$title}', price = {$price}, sort_order = {$sortOrder}");      
      }       
    }
  }


  public function deleteValue($valueId){    
    $valueId = (int) $valueId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE value_id = {$valueId}");                              
  }


  public function deleteValues($optionId){    
    $optionId = (int) $optionId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE option_id = {$optionId}");                              
  }
  
  
  public function deleteProductValues($productId){    
    $productId = (int) $productId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");                              
  }  	
  
  
}
