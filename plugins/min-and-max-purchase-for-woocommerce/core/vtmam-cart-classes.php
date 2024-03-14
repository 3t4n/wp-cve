<?php


class VTMAM_Cart {	
    public $cart_items;
    public $cart_item;
    
    //error messages at rule application time
    public $error_messages;
    //flag to prevent multiple processing iterations
    public $error_messages_processed; 
    
    //address info for lifetime max purchase      
    public $purchaser_ip_address;
    public $purchaser_email;
    public $billto_name;
    public $billto_address;
    public $billto_city;
    public $billto_state;
    public $billto_postcode;
    public $billto_country;
    public $shipto_name;
    public $shipto_address;
    public $shipto_city;
    public $shipto_state;
    public $shipto_postcode;
    public $shipto_country;

    public $error_messages_are_custom;   //v1.07    
    
    
	public function __construct(){
    $this->cart_items = array();
    $this->cart_item;
    $this->error_messages  = array(
       /* **The following array structure is created on-the-fly during the apply process**
        array(
          'msg_from_this_rule_id'    => '',
          'msg_from_this_rule_occurrence' => '',
          'msg_text'  => '',
          'msg_is_custom'   => ''    //v1.07 
        )
        */
    ); 
    $this->error_messages_processed;
    
    //address info off of screen for lifetime max purchase     
    $this->purchaser_ip_address;
    $this->purchaser_email;
    $this->billto_name;
    $this->billto_address;
    $this->billto_city;
    $this->billto_state;
    $this->billto_postcode;
    $this->billto_country;
    $this->shipto_name;
    $this->shipto_address;
    $this->shipto_city;
    $this->shipto_state;
    $this->shipto_postcode;
    $this->shipto_country;
    $this->error_messages_are_custom;     //v1.07              
  }
  

} //end class

class VTMAM_Cart_Item {

    public $product_id;  
    public $product_name;
    public $quantity;
    public $unit_price;
    public $total_price;
    public $prod_cat_list;
    public $rule_cat_list; 
    
    //used during rule process logic
    public $product_participates_in_rule;                            
  
	public function __construct(){
    $this->product_id;  
    $this->product_name;
    $this->quantity = 0.00;
    $this->unit_price = 0.00;
    $this->total_price = 0.00;
    $this->prod_cat_list= array();
    $this->rule_cat_list= array();
    $this->product_participates_in_rule = array(
        /* **The following array structure is created on-the-fly during the apply process**
        array(
          'post_id'    => '',    // rule id
          'inpop_selection'    => $vtmam_rules_set[$i]->inpop_selection, //needed to test for 'vargroup'
          'inpop_selection_numval' => $inpop_selection_numval, //from case structure above, makes inpop_selection sortable
          'ruleset_occurrence'    => $i, //saves having to look for this later
          'inpop_occurrence'    => $k,  //saves having to look for this later  
          'purch_hist_product_row_id'  => '',      
          'purch_hist_product_price_total'  => '',      
          'purch_hist_product_qty_total'  => ''          
        )
        */    
     );
                                                 
	}

} //end class


class VTMAM_Cart_Functions{
	
	public function __construct(){
		
	}


    public function vtmam_destroy_cart() { 
        global $vtmam_cart;
        unset($vtmam_cart);
    }
    
    /*
     Template Function
     In your theme, execute the function
     where you want the amount to show
    */
    public function vtmam_cart_oldprice() { 
        global $vtmam_cart;
        echo '$vtmam_cart->$cart_oldprice';
    }

    /*
     Template Function
     In your theme, execute the function
     where you want the amount to show
    */    
    public function vtmam_cart_yousave() { 
        global $vtmam_cart;
        echo '$vtmam_cart->$cart_yousave';
    }
    
    /*
     Template Function
     In your theme, execute the function
     where you want the amount to show
    */
    public function vtmam_cart_unit_oldprice($product_id) { 
        global $vtmam_cart;
        foreach($vtmam_cart->vtmam_cart_items as $key => $vtmam_cart_item) {
           if ($vtmam_cart_item->product_id == $product_id) {
              echo $vtmam_cart->cart_unit_oldprice;
              break;
           }
        }
    }
    
    /*
     Template Function
     In your theme, execute the function
     where you want the amount to show
    */    
    public function vtmam_cart_total_oldprice($product_id) { 
        global $vtmam_cart;
        foreach($vtmam_cart->vtmam_cart_items as $key => $vtmam_cart_item) {
           if ($vtmam_cart_item->product_id == $product_id) {
              echo $vtmam_cart_item->cart_total_oldprice;
              break;
           }
        }
    }
    
    /*
     Template Function
     In your theme, execute the function
     where you want the amount to show
    */    
    public function vtmam_cart_total_yousave($product_id) { 
        global $vtmam_cart;
        foreach($vtmam_cart->vtmam_cart_items as $key => $vtmam_cart_item) {
           if ($vtmam_cart_item->product_id == $product_id) {
              echo $vtmam_cart->cart_total_yousave;
              break;
           }
        }
    }    

} //end class
$vtmam_cart_functions = new VTMAM_Cart_Functions;

