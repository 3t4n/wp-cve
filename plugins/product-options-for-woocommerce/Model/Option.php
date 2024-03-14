<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Model_Option {  
  
  protected $_wpdb;
  protected $_mainTable;
    
  protected $_value;          
          
      
  public function __construct(){
    global $wpdb;
    
    $this->_wpdb = $wpdb;   
    $this->_mainTable = "{$this->_wpdb->base_prefix}pofw_product_option";

    include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option/Value.php');		
    $this->_value = new Pektsekye_ProductOptions_Model_Option_Value();        		
  }	


  public function getProductOptions($productId){
    $productId = (int) $productId;
    
    $options = array();
    
    $valuesByOptionId = array();         
    $valueRows = $this->_value->getProductValues($productId);      
    foreach($valueRows as $r){
      $valuesByOptionId[$r['option_id']][$r['value_id']] = $r;
    }    
          
    $select = "SELECT * FROM {$this->_mainTable} WHERE product_id = {$productId} ORDER BY sort_order, title"; 
    $rows = $this->_wpdb->get_results($select, ARRAY_A);              
    foreach ($rows as $r){
      $r['values'] = isset($valuesByOptionId[$r['option_id']]) ? $valuesByOptionId[$r['option_id']] : array();
      $options[$r['option_id']] = $r;
    }
    
    return $options;
  }
  
  
  public function saveOptions($productId, $options){ 
    $productId = (int) $productId;
    
    foreach($options as $option){
      $optionId = (int) $option['option_id'];
      
      if (isset($option['is_delete']) && $option['is_delete'] == 1){
        $this->deleteOption($optionId);
        continue;
      }
      
      $title = esc_sql($option['title']);
      $type = esc_sql($option['type']);
      $required = isset($option['required']) && $option['required'] == 1 ? 1 : 0;
      $sortOrder = (int) $option['sort_order'];
      $price = isset($option['price']) ? (float) $option['price'] : 0;               
      if ($optionId > 0){
        $this->_wpdb->query("UPDATE {$this->_mainTable} SET title = '{$title}', type = '{$type}', required = {$required}, sort_order = {$sortOrder}, price = {$price}  WHERE option_id = {$optionId}");                    
      } else {
        $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, title = '{$title}', type = '{$type}', required = {$required}, sort_order = {$sortOrder}, price = {$price}");      
        $optionId = $this->_wpdb->insert_id;
      }      

      if (isset($option['values'])){
        $this->_value->saveValues($productId, $optionId, $option['values']);
      }     
    }       
  }
  
  
  public function deleteOption($optionId){    
    $optionId = (int) $optionId;
    
    $this->_value->deleteValues($optionId);
          
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE option_id = {$optionId}");                                   
  }
  
  
  public function deleteProductOptions($productId){    
    $productId = (int) $productId;
    
    $this->_value->deleteProductValues($productId);
          
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");                                   
  }

  				
  public function getOptionGroupByType($type){    
    $group = '';
    
    switch($type){
      case 'drop_down':
      case 'radio':
      case 'checkbox':            
      case 'multiple':
        $group = 'select';
        break;
      case 'field':
      case 'area':
        $group = 'text';
        break;               
    }    
    
    return $group;                               
  }		
	
		
}
