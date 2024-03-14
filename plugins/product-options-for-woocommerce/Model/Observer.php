<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Model_Observer {  

  protected $_productOptions = array();          
  protected $_poPriceAdded = array(); 
                
      
  public function __construct(){
    add_action('woocommerce_add_to_cart_validation', array($this, 'validate_selected_options'), 10, 2); 
    add_action('woocommerce_add_cart_item_data', array($this, 'save_selected_options'), 10, 2);    
    add_filter('woocommerce_get_item_data', array($this, 'display_selected_options_on_checkout'), 10, 2);
    add_action('woocommerce_new_order_item', array($this, 'display_selected_options_with_order_info'), 1, 3);  
    add_action('woocommerce_before_calculate_totals', array($this, 'add_option_price_on_checkout'), 99);	
		add_action('delete_post', array($this, 'delete_post'));    	          		
  }	


  public function getOptionModel(){
    include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option.php');		
    return new Pektsekye_ProductOptions_Model_Option();    
  }  
  
  
  public function getProductOptions($productId){
    if (!isset($this->_productOptions[$productId])){    
      $this->_productOptions[$productId] = $this->getOptionModel()->getProductOptions($productId);
    }
    return $this->_productOptions[$productId];
  }


  public function validate_selected_options($isValid, $productId){
         
    if (!$isValid){
      return false;    
    }
    
    foreach ($this->getProductOptions($productId) as $option){
      $oId = (int) $option['option_id'];      
      if ($option['required'] == 1 && (empty($_POST['pofw_option'][$oId]) || (is_array($_POST['pofw_option'][$oId]) && $_POST['pofw_option'][$oId][0] == ''))){                     
        $isValid = false;
        wc_add_notice(__('Please specify the product required option(s).', 'product-options-for-woocommerce' ), 'error');
        break;       
      }              											                                                    
    }  
      
    return $isValid;
  }


  public function save_selected_options($cart_item_data, $product_id){ 
    if (isset($_POST['pofw_option'])) {   
      $optionValues = (array) $_POST['pofw_option'];
      foreach($optionValues as $oId => $value){
        if (is_array($value)){
          $value = array_map('intval', $value);
        } elseif (ctype_digit($value)){
          $value = (int) $value;
        } else {
          $value = sanitize_textarea_field(stripslashes($value));
        }
        $cart_item_data['pofw_option'][$oId] = $value;        
      }
    }
    return $cart_item_data;
  }


  public function display_selected_options_on_checkout($cart_data, $cart_item){
    
    $custom_items = array(); 
    if (isset($cart_item['pofw_option'])) {
      $custom_items = $this->formatSelectedValues($cart_item['product_id'], $cart_item['pofw_option']);
    }
  
    return array_merge((array)$cart_data, $custom_items);
  }


  public function display_selected_options_with_order_info($item_id, $values, $cart_item_key){
    if (isset($values->legacy_values['pofw_option'])){   
      $selectedValues = $this->formatSelectedValues($values->legacy_values['product_id'], $values->legacy_values['pofw_option']);      
      foreach ($selectedValues as $value){
        wc_add_order_item_meta($item_id, $value['name'], $value['value']);
      }
    }
  }


  public function add_option_price_on_checkout($cart){

    foreach (WC()->cart->get_cart() as $key => $citem) {
    
      if (!isset($citem['pofw_option']) || isset($this->_poPriceAdded[$key]))
        continue;
        
      $selectedValues = $citem['pofw_option'];
               
      $productId = $citem["variation_id"] == 0 ? $citem["product_id"] : $citem["variation_id"];
      
      $_product = wc_get_product($productId);

      $orgPrice = $citem["data"]->get_price();//$_product->get_price();
      
      $optionPrice = 0;
        
      foreach ($this->getProductOptions($productId) as $oId => $option){
        if (!isset($selectedValues[$oId])){
          continue;
        }

        $selectedValue = $selectedValues[$oId];
        
        if ($option['type'] == 'drop_down' || $option['type'] == 'radio'){
          if (is_array($selectedValue)){
            continue;
          }
          $vId = (int) $selectedValue;
          if (isset($option['values'][$vId])){
            $optionPrice += (float) $option['values'][$vId]['price'];
          }
        } elseif ($option['type'] == 'multiple' || $option['type'] == 'checkbox'){
          foreach ((array) $selectedValue as $vId){
            if (isset($option['values'][$vId])){
              $optionPrice += (float) $option['values'][$vId]['price'];
            }            
          }          
        } elseif ($option['type'] == 'field' || $option['type'] == 'area'){
          if (is_array($selectedValue)){
            continue;
          }
          if (!empty($selectedValue)){
            $optionPrice += (float) $option['price'];
          }
        }
                           											                                                    
      }

      $citem["data"]->set_price($orgPrice + $optionPrice);
      $this->_poPriceAdded[$key] = 1;      
    }
      
    WC()->cart->set_session();  
  }
  


  public function formatSelectedValues($productId, $selectedValues){
    
    $formatedValues = array();

    foreach ($this->getProductOptions($productId) as $oId => $option){
      if (!isset($selectedValues[$oId])){
        continue;
      }

      $selectedValue = $selectedValues[$oId];
      
      $value = '';        
      if ($option['type'] == 'drop_down' || $option['type'] == 'radio'){
        if (is_array($selectedValue)){
          continue;
        }
        $vId = (int) $selectedValue;
        if (isset($option['values'][$vId])){
          $value = $option['values'][$vId]['title'];
        }
      } elseif ($option['type'] == 'multiple' || $option['type'] == 'checkbox'){
        foreach ((array) $selectedValue as $vId){
          if (isset($option['values'][$vId])){
            $value .= ($value != '' ? ', ' : '') . $option['values'][$vId]['title'];
          }            
        }          
      } elseif ($option['type'] == 'field' || $option['type'] == 'area'){
        if (is_array($selectedValue)){
          continue;
        }
        $value = $selectedValue;
      }
      
      if ($value != ''){
        $formatedValues[] = array("name" => $option['title'], "value" => $value);
      }                      											                                                    
    }
    
    return $formatedValues;    
  }  

		
	public function delete_post($id){
		if (!current_user_can('delete_posts') || !$id || get_post_type($id) != 'product'){
			return;
		}
    $this->getOptionModel()->deleteProductOptions($id);
	}		
		
}
