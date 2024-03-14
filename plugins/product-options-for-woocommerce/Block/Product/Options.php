<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Block_Product_Options {

  protected $_option; 
  protected $_numberOfDecimals;   
  protected $_decimalSeparator;
  protected $_thousandSeparator;
  protected $_currencySymbol;
  protected $_currencyPosition;

  protected $_productOptions;  


	public function __construct(){
    include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option.php');		
		$this->_option =  new Pektsekye_ProductOptions_Model_Option();			 
            
    $this->_numberOfDecimals  = wc_get_price_decimals();            
    $this->_decimalSeparator  = wc_get_price_decimal_separator();
    $this->_thousandSeparator = wc_get_price_thousand_separator();
    $this->_currencySymbol    = get_woocommerce_currency_symbol();
    $this->_currencyPosition  = get_option('woocommerce_currency_pos'); 		  			
	}


  public function getProductId(){
    global $product;
    return (int) $product->get_id();              
  }
 
  
  public function getProductOptions(){
    if (!isset($this->_productOptions)){
      $this->_productOptions = $this->_option->getProductOptions($this->getProductId());
    }
    return $this->_productOptions;
  }
  
  
  public function getOptions(){ 
    $data = array();        

    foreach ($this->getProductOptions() as $option){
      $oId = (int) $option['option_id'];
               
      $data[$oId] = array(       
        'title' => $option['title'],
        'type' => $option['type'],
        'required' => (int) $option['required'],
        'sort_order' => (int) $option['sort_order'],
        'price' => (float) $option['price']                  
      );
      
      $data[$oId]['values'] = array();     
        
      foreach($option['values'] as $value){
        $vId = (int) $value['value_id'];
        $data[$oId]['values'][$vId] = array(         
          'title' => $value['title'],
          'price' => (float) $value['price'],
          'sort_order' => (int) $value['sort_order']
        );                  
      }
      											                                                    
    }    
    
    return $data; 
  } 


  public function getOptionDataJson(){
    $data = array();        

    foreach ($this->getProductOptions() as $option){
      $oId = (int) $option['option_id'];
           
      if ($option['price'] != 0){
        $data['optionPrices'][$oId] = (float) $option['price'];                  
      }     
        
      foreach($option['values'] as $value){
        $vId = (int) $value['value_id'];
        if ($value['price'] != 0){
          $data['valuePrices'][$vId] = (float) $value['price'];                  
        }                          
      }    											                                                    
    }    
    
    return json_encode($data);              
  }
  
    
  public function getProductPrice(){
    global $product;
    return $product->get_price();              
  }
  
  
  public function getNumberOfDecimals(){
    return $this->_numberOfDecimals;              
  }
    
  
  public function getDecimalSeparator(){
    return $this->_decimalSeparator;              
  }  
 
 
  public function getThousandSeparator(){
    return $this->_thousandSeparator;              
  }
  

  public function getCurrencyPosition(){
    return $this->_currencyPosition;              
  }
  
  
  public function getIsOnSale(){
    global $product;
    return $product->is_on_sale() ? 1 : 0;              
  }
     
  
  public function formatPrice($price){
    if ($price == 0)
      return '';
  
    $negative = $price < 0;
    $price = floatval($negative ? $price * -1 : $price);
    $price = number_format($price, $this->_numberOfDecimals, $this->_decimalSeparator, $this->_thousandSeparator);
    
    switch($this->_currencyPosition){
      case 'left':
        $price = $this->_currencySymbol . $price;      
      break;
      case 'left_space':
        $price = $this->_currencySymbol . ' ' . $price;      
      break;      
      case 'right':
        $price .= $this->_currencySymbol;
      break;      
      case 'right_space':
        $price .= ' ' . $this->_currencySymbol;      
      break;      
      default:
        $price = $this->_currencySymbol . $price;                  
    }

    return ($negative ? '-' : '+' ) . $price;               
  }   
   
    
  public function toHtml(){
    include_once(Pektsekye_PO()->getPluginPath() . 'view/frontend/templates/product/options.php');
  }


}
