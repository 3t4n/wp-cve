<?php 
if (!defined('ABSPATH')) exit;

class Pektsekye_ProductOptions_Block_Adminhtml_Product_Edit_Tab_CustomOptions_Options {

  protected $_option;  

  
  public function __construct(){
    include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option.php');		
    $this->_option = new Pektsekye_ProductOptions_Model_Option(); 	
  }


  public function getProductId(){
    global $post;
    return isset($post->ID) ? (int) $post->ID : 0;                 
  }


  public function getOptionDataJson(){
    $data = array();        

    $productId = $this->getProductId();
    
    $lastOptionId = 0;
    $lastSortOrder = 0;
    $lastValueId = 0;      
    $lastValueSortOrder = array();
                 
    $options = $this->_option->getProductOptions($productId);
    foreach ($options as $option){
      $oId = (int) $option['option_id'];
               
      $data['optionsData'][$oId] = array(
        'option_id' => $oId,        
        'title' => $option['title'],
        'type' => $option['type'],
        'required' => (int) $option['required'],
        'sort_order' => (int) $option['sort_order'],
        'price' => (float) $option['price']                
      );
      
      $lastValueSortOrder[$oId] = 0;        
      foreach($option['values'] as $value){
        $vId = (int) $value['value_id'];
        $data['optionsData'][$oId]['values'][] = array(
          'value_id' => $vId,          
          'title' => $value['title'],
          'price' => (float) $value['price'],
          'sort_order' => (int) $value['sort_order']
        );
        if ($vId > $lastValueId)
          $lastValueId = $vId;          
        if ($value['sort_order'] > $lastValueSortOrder[$oId])
          $lastValueSortOrder[$oId] = (int) $value['sort_order'];                  
      }
      
      $data['optionIds'][] = $oId;
      
      if ($oId > $lastOptionId)
        $lastOptionId = $oId;        
      if ($option['sort_order'] > $lastSortOrder)
        $lastSortOrder = (int) $option['sort_order'];											                                                    
    }
    
    $data['lastOptionId'] = $lastOptionId;
    $data['lastSortOrder'] = $lastSortOrder;
    $data['lastValueId'] = $lastValueId;            
    $data['lastValueSortOrder'] = $lastValueSortOrder;
    
    return json_encode($data);
  }


  public function toHtml(){
     include_once(Pektsekye_PO()->getPluginPath() . 'view/adminhtml/templates/product/edit/tab/customoptions/options.php');
  }


}
