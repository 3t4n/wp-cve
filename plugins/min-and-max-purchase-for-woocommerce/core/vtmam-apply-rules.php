<?php

class VTMAM_Apply_Rules{
	
	public function __construct(){
		global $vtmam_cart, $vtmam_rules_set, $vtmam_rule;
    //get pre-formatted rules from options field

     //***********************
     //v2.0.0a begin
     //v2.1.0 begin - do not execute when in admin and trashing/deleting rules. 
     //  when doing this action, REQUEST_URI= /wp-includes/js/plupload/wp-plupload.min.js?ver=5.8.2
     global $vtmam_setup_options;
     $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.3
     if (strpos($pageURL,'plupload') !== false) {
       if ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ){ 
          error_log( print_r(  'VTMAM_Apply_Rules INVALID, REQUEST_URI found = plupload, which shows up at trash/delete in wp-admin', true ) );
       }                                                                      
        return;     
     }
     //v2.1.0 end  
    
    
     //v2.0.0 begin                
     //no cart rules in admin (only catalog)!!
     //wp-admin calls doing ajax can be confused with other calls - best to test the ACTIVE PAGE:
     $pageURL = sanitize_url($_SERVER["REQUEST_URI"]); //v2.0.3 - somehow, this is needed again here - otherwise, there are issues with the checkout crossouts.
     if ( (strpos($pageURL,'wp-admin') !== false) ||  //v2.0.3
          (defined( 'DOING_CRON' )) ) {           
        return;          
     }     
     //v2.0.0 end 

     //v2.0.0a end
     //***********************       
    
    $vtmam_rules_set = vtmam_get_rules_set(); //v2.0.0

     if ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ){ 
        error_log( print_r(  '$vtmam_rules_set at APPLY-RULES BEGIN', true ) );        
        error_log( var_export($vtmam_rules_set, true ) );
     } 

    // create a new vtmam_cart intermediary area, load with parent cart values.  results in global $vtmam_cart.
    vtmam_load_vtmam_cart_for_processing(); 
    
    $this->vtmam_minandmax_purchase_check();
    
         
    //v2.0.0
    if ( ( isset( $vtmam_setup_options['debugging_mode_on'] )) &&
         ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ) ) { 
      error_log( print_r(  ' ', true ) );
      error_log( print_r(  '$vtmam_rules_set at END', true ) );
      error_log( var_export($vtmam_rules_set, true ) );
      error_log( print_r(  '$vtmam_cart at END', true ) );
      error_log( var_export($vtmam_cart, true ) );      
    }
    //v2.0.0
      
    
     return;
	}


  public function vtmam_minandmax_purchase_check() { 
    global $post, $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info;
     
     
    //************************************************
    //BEGIN processing to mark product as participating in the rule or not...
    //************************************************
    
    /*  Analyze each rule, and load up any cart products found into the relevant rule
        fill rule array with product cart data :: load inpop info 
    */  
    $sizeof_vtmam_rules_set = is_array($vtmam_rules_set) ? sizeof($vtmam_rules_set) : 0; //v2.0.0
    
     // **********************************************************************
    //GROUP Order ruleset by min/max selected to facilitate error message printing 
    // **********************************************************************
    //create temp min/max arrays
    $vtmam_rules_set_min = array();
    $vtmam_rules_set_max = array();
    
    //separate rules into temp arrays
    for($i=0; $i < $sizeof_vtmam_rules_set; $i++) {  
       if ( $vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum' ) { 
          $vtmam_rules_set_min []  = $vtmam_rules_set[$i];
       }  else {
          $vtmam_rules_set_max []  = $vtmam_rules_set[$i];
       }
    }

    $sizeof_vtmam_rules_set_min = is_array($vtmam_rules_set_min) ? sizeof($vtmam_rules_set_min) : 0; //v2.0.0
    $sizeof_vtmam_rules_set_max = is_array($vtmam_rules_set_max) ? sizeof($vtmam_rules_set_max) : 0; //v2.0.0
    
    //REBUILD $vtmam_rules_set from min/max arrays, min first.
    $vtmam_rules_set = array();
     for($i=0; $i < $sizeof_vtmam_rules_set_min; $i++) {  //v2.0.0
     //for($i=0; $i < sizeof($vtmam_rules_set_min); $i++) {
        $vtmam_rules_set [] =  $vtmam_rules_set_min [$i] ; //then add in max rows
     }
     for($i=0; $i < $sizeof_vtmam_rules_set_max; $i++) {  //v2.0.0
     //for($i=0; $i < sizeof($vtmam_rules_set_max); $i++) {
        $vtmam_rules_set [] =  $vtmam_rules_set_max [$i] ;
     }
    // end rule grouping
    // **********************************************************************
    
    $sizeof_vtmam_rules_set = is_array($vtmam_rules_set) ? sizeof($vtmam_rules_set) : 0; //v2.0.0
    $sizeof_cart_items      = is_array($vtmam_cart->cart_items) ? sizeof($vtmam_cart->cart_items) : 0; //v2.0.0      
    for($i=0; $i < $sizeof_vtmam_rules_set; $i++) {           
    //for($i=0; $i < $sizeof_vtmam_rules_set; $i++) {                                                               
      if ( $vtmam_rules_set[$i]->rule_status == 'publish' ) {       
        for($k=0; $k < $sizeof_cart_items; $k++) {    //v2.0.0               
            switch( $vtmam_rules_set[$i]->inpop_selection ) {  
              case 'groups':
                  //test if product belongs in rule inpop
                  if ( $this->vtmam_product_is_in_inpop_group($i, $k) ) {
                    $this->vtmam_load_inpop_found_list($i, $k);                        
                  }
                break;
            }
 
                                              
        }   
      } 
    }  //end inpop population processing
    
                                                                                                      
    //************************************************
    //BEGIN processing to mark rules as requiring action y/n
    //************************************************
            
    /*  Analyze each Rule population, and see if they satisfy the rule
    *     identify and label each rule as requiring action = yes/no
    */
    $sizeof_vtmam_rules_set = is_array($vtmam_rules_set) ? sizeof($vtmam_rules_set) : 0; //v2.0.0
    for($i=0; $i < $sizeof_vtmam_rules_set; $i++) {   //v2.0.0
    //for($i=0; $i < $sizeof_vtmam_rules_set; $i++) {        
        if ( $vtmam_rules_set[$i]->rule_status == 'publish' ) {  
          $sizeof_inpop_found_list = is_array($vtmam_rules_set[$i]->inpop_found_list) ? sizeof($vtmam_rules_set[$i]->inpop_found_list) : 0; //v2.0.0
          if ( $sizeof_inpop_found_list == 0 ) {   //v2.0.0
          //if ( sizeof($vtmam_rules_set[$i]->inpop_found_list) == 0 ) {
             $vtmam_rules_set[$i]->rule_requires_cart_action = 'no';   // cut out unnecessary logic...
          } else {
            
            $vtmam_rules_set[$i]->rule_requires_cart_action = 'pending';
            //$sizeof_inpop_found_list = sizeof($vtmam_rules_set[$i]->inpop_found_list);    //v2.0.0 moved above
            /*
                AS only one product can be found with 'single', override to 'all' speeds things along
            */
            if ($vtmam_rules_set[$i]->inpop_selection ==  'single') {
               $vtmam_rules_set[$i]->specChoice_in_selection = 'all' ; 
            }
            
           
            switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
               case 'all':  //$specChoice_value = 'all'  => total up everything in the population as a unit  
                    if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency'){   //price total
                        if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
                            if ($vtmam_rules_set[$i]->inpop_total_price >= $vtmam_rules_set[$i]->minandmax_amt['value']) {                                                 
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'no';
                            } else {
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'yes';
                            }
                        } else {
                            if ($vtmam_rules_set[$i]->inpop_total_price <= $vtmam_rules_set[$i]->minandmax_amt['value']) {                                                 
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'no';
                            } else {
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'yes';
                            }
                        }
                    } else {  //qty total
                        if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
                            if ($vtmam_rules_set[$i]->inpop_qty_total >= $vtmam_rules_set[$i]->minandmax_amt['value']) {
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'no';
                            } else {
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'yes';
                            }
                        } else { 
                            if ($vtmam_rules_set[$i]->inpop_qty_total <= $vtmam_rules_set[$i]->minandmax_amt['value']) {
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'no';
                            } else {
                              $vtmam_rules_set[$i]->rule_requires_cart_action = 'yes';
                            }
                        }
                    } 
                    
                    //v1.07.9 begin
                      if ( ( $vtmam_rules_set[$i]->repeatingGroups > 0 ) &&
                           ( $vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum' )  && 
                          ( ($vtmam_rules_set[$i]->inpop_qty_total % $vtmam_rules_set[$i]->repeatingGroups) > 0) ) { 
                      $vtmam_rules_set[$i]->rule_requires_cart_action = 'yes';
                    }
                    //v1.07.9 end
                                        
                    if ($vtmam_rules_set[$i]->rule_requires_cart_action == 'yes') {
                       for($k=0; $k < $sizeof_inpop_found_list; $k++) {
                          $this->vtmam_mark_product_as_requiring_cart_action($i,$k);                          
                       }
                    }  		
              		break;
               case 'each': //$specChoice_value = 'each' => apply the rule to each product individually across all products found         		
              		  for($k=0; $k < $sizeof_inpop_found_list; $k++) {
                        if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency'){   //price total
                            if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_total_price'] >= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                  // $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                                      
                                  //v1.07.9 begin
                                    if ( ( $vtmam_rules_set[$i]->repeatingGroups > 0 ) &&
                                        ( ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] % $vtmam_rules_set[$i]->repeatingGroups) > 0) ) { 
                                    $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                  } else {
                                    $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                  }
                                  //v1.07.9 end
                     
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                }
                             } else {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_total_price'] <= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                   $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                }
                             }   
                        }  else {
                            if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] >= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                  // $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                                      
                                  //v1.07.9 begin
                                    if ( ( $vtmam_rules_set[$i]->repeatingGroups > 0 ) &&
                                        ( ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] % $vtmam_rules_set[$i]->repeatingGroups) > 0) ) { 
                                    $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                  } else {
                                    $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                  }
                                  //v1.07.9 end
                                
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                }
                            } else {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] <= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                   $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                }
                            }
                        }
                    }
                        
                  break;
               case 'any':  //$specChoice_value = 'any'  =>   "You must buy a minimum or maximum of $10 for each of any of 2 products from this group."       		
              		  $any_action_cnt = 0;
                    for($k=0; $k < $sizeof_inpop_found_list; $k++) {
                        if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency'){   //price total
                            if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_total_price'] >= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                   //$vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                                      
                                  //v1.07.9 begin
                                    if ( ( $vtmam_rules_set[$i]->repeatingGroups > 0 ) &&
                                        ( ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] % $vtmam_rules_set[$i]->repeatingGroups) > 0) ) { 
                                    $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                    $any_action_cnt++;
                                  } else {
                                    $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                  }
                                  //v1.07.9 end
                                        
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                   $any_action_cnt++;
                                }
                            } else {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_total_price'] <= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                   $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                   $any_action_cnt++;
                                }
                            }
                        }  else {
                            if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] >= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                   //$vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                                       
                                  //v1.07.9 begin
                                    if ( ( $vtmam_rules_set[$i]->repeatingGroups > 0 ) &&
                                        ( ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] % $vtmam_rules_set[$i]->repeatingGroups) > 0) ) { 
                                    $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                    $any_action_cnt++;
                                  } else {
                                    $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                  }
                                  //v1.07.9 end
                                   
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                   $any_action_cnt++;
                                }
                            } else {
                                if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'] <= $vtmam_rules_set[$i]->minandmax_amt['value']){
                                   $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'no';
                                }  else {
                                   $this->vtmam_mark_product_as_requiring_cart_action($i,$k);
                                   $any_action_cnt++;
                                }
                            }
                        }
                        //if 'any' limit reached, end the loop, don't mark any mor products as requiring cart action
                        if ($any_action_cnt >= $vtmam_rules_set[$i]->anyChoice_max['value']) {
                            $k = $sizeof_inpop_found_list;   
                        }
                    } 
                  break;
            }
        }        
      }
    }   


            
    //************************************************
    //BEGIN processing to produce error messages
    //************************************************
    /*
     * For those rules whose product population has failed the rules test,
     *   document the rule failure in an error message
     *   and ***** place the error message into the vtmam cart *****
     *   
     * All of the inpop_found info placed into the rules array during the apply-rules process
     *      is only temporary.  None of that info is stored on the rules array on a 
     *      more permanent basis.  Once the error messages are displayed, they too are discarded
     *      from the rules array (by simply not updating the array on the options table). 
     *      The errors are available to the rules_ui on the error-display go-round because 
     *           the info is held in the global namespace.                                   
    */
    $vtmam_info['error_message_needed'] = 'no';
    for($i=0; $i < $sizeof_vtmam_rules_set; $i++) {               
        if ( $vtmam_rules_set[$i]->rule_status == 'publish' ) {    
            switch( true ) {            
              case ($vtmam_rules_set[$i]->rule_requires_cart_action == 'no'):
                  //no error message for this rule, go to next in loop
                break;  
                  
              case ( ($vtmam_rules_set[$i]->rule_requires_cart_action == 'yes') || ($vtmam_rules_set[$i]->rule_requires_cart_action == 'pending') ):
                                     
                //************************************************
                //Create Error Messages for single or group 
                //************************************************
 
                //errmsg pre-processing
                $this->vtmam_init_recursive_work_elements($i); 
                               
                switch( $vtmam_rules_set[$i]->inpop_selection ) {
                  case 'single': 
                     $vtmam_rules_set[$i]->errProds_total_price = $vtmam_rules_set[$i]->inpop_total_price;
                     $vtmam_rules_set[$i]->errProds_qty         = $vtmam_rules_set[$i]->inpop_qty_total;
                     $vtmam_rules_set[$i]->errProds_ids []      = $vtmam_rules_set[$i]->inpop_found_list[0]['prod_id'];
                     $vtmam_rules_set[$i]->errProds_names []    = $vtmam_rules_set[$i]->inpop_found_list[0]['prod_name'];
                     $this->vtmam_create_text_error_message($i);
                     break; //Error Message Processing *Complete* for this Rule
 
                 default:  // 'groups' or 'cart' or 'vargroup'                                                 
                    
                    if ( $vtmam_rules_set[$i]->inpop_selection  == 'groups' ) {
                    
                      //BEGIN Get Category Names for rule (groups only)
                      $this->vtmam_init_cat_work_elements($i); 
                      
                      $sizeof_prodcat_in_checked = is_array($vtmam_rules_set[$i]->prodcat_in_checked) ? sizeof($vtmam_rules_set[$i]->prodcat_in_checked) : 0; //v2.0.0
                      //if ( ( sizeof($vtmam_rules_set[$i]->prodcat_in_checked) > 0 )  && 
                      if ( ( $sizeof_prodcat_in_checked > 0 )  && 
                            ($vtmam_setup_options['show_prodcat_names_in_errmsg'] == 'yes' ) ) { 
                        foreach ($vtmam_rules_set[$i]->prodcat_in_checked as $cat_id) { 
                            $cat_info = get_term_by('id', $cat_id, $vtmam_info['parent_plugin_taxonomy'] ) ;
                            If ($cat_info) {
                               $vtmam_rules_set[$i]->errProds_cat_names [] = $cat_info->name;
                            }
                        }
                      }                  
                      $sizeof_rulecat_in_checked = is_array($vtmam_rules_set[$i]->rulecat_in_checked) ? sizeof($vtmam_rules_set[$i]->rulecat_in_checked) : 0; //v2.0.0                
                      //if ( ( sizeof($vtmam_rules_set[$i]->rulecat_in_checked) > 0 ) && 
                      if ( ( $sizeof_rulecat_in_checked > 0 ) && 
                            ($vtmam_setup_options['show_rulecat_names_in_errmsg'] == 'yes' ) ) { 
                        foreach ($vtmam_rules_set[$i]->rulecat_in_checked as $cat_id) { 
                          $cat_info = get_term_by('id', $cat_id, $vtmam_info['rulecat_taxonomy'] ) ;
                          If ($cat_info) {
                             $vtmam_rules_set[$i]->errProds_cat_names [] = $cat_info->name;
                          }
                        }
                      } 
                      //End Category Name Processing (groups only)
                    } 
                    
                    //PROCESS all ERROR products
                    $sizeof_inpop_found_list = is_array($vtmam_rules_set[$i]->inpop_found_list) ? sizeof($vtmam_rules_set[$i]->inpop_found_list) : 0; //v2.0.0
                    //for($k=0; $k < sizeof($vtmam_rules_set[$i]->inpop_found_list); $k++) {
                    for($k=0; $k < $sizeof_inpop_found_list; $k++) {   //v2.0.0
                      if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] == 'yes'){
                        //aggregate totals and add name into list
                        $vtmam_rules_set[$i]->errProds_qty         += $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_qty'];
                        $vtmam_rules_set[$i]->errProds_total_price += $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_total_price'];
                        $vtmam_rules_set[$i]->errProds_ids []       = $vtmam_rules_set[$i]->inpop_found_list[0]['prod_id'];
                        $vtmam_rules_set[$i]->errProds_names []     = $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_name'];                                             

                        switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
                          case 'all':
                              //Don't create a message now,message applies to the whole population, wait until 'for' loop completes to print
                            break;
                          default:  // 'each' and 'any'
                              //message applies to each product as setup in previous processing
                              $this->vtmam_create_text_error_message($i); 
                              //clear out errProds work elements
                              $this->vtmam_init_recursive_work_elements($i);                            
                            break;
                        }  
                                     
                      }
                    }
                    
                    if ( $vtmam_rules_set[$i]->specChoice_in_selection == 'all' ) {    
                       $this->vtmam_create_text_error_message($i);
                    }  
                         
              }  //end messaging
              
              break; 
            } //end proccessing for this rule
            
                           
        }    
    }   //end rule processing
   
    
    //Show error messages in table format, if desired and needed.
    if ( ( $vtmam_setup_options['show_error_messages_in_table_form'] == 'yes' ) && ($vtmam_info['error_message_needed'] == 'yes') ) {
       $this->vtmam_create_table_error_message();
    }
    
    if ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ) {
      error_log( print_r(  '$vtmam_info', true ) );
      error_log( var_export($vtmam_info, true ) );
      error_log( print_r(  '$vtmam_rules_set', true ) );
      error_log( var_export($vtmam_rules_set, true ) );
      error_log( print_r(  '$vtmam_cart', true ) );
      error_log( var_export($vtmam_cart, true ) );
      error_log( print_r(  '$vtmam_setup_options', true ) );
      error_log( var_export($vtmam_setup_options, true ) );  
    }
    
  }  //end vtmam_minandmax_purchase_check
  
   
   
   
        
    public function vtmam_create_table_error_message () { 
      global $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info; 
      
      $vtmam_info['line_cnt']++; //line count used in producing height parameter when messages sent to js.
      
      $vtmam_info['cart_color_cnt'] = 0;
      
      $rule_id_list = ' ';
      
      $cart_count = is_array($vtmam_cart->cart_items) ? sizeof($vtmam_cart->cart_items) : 0; //v2.0.0
      //$cart_count = sizeof($vtmam_cart->cart_items);
      
      //separate out the messages into min or max groups
      $msg_minandmax_label = 'Minimum';
      $this->vtmam_create_table_error_message_mom ($msg_minandmax_label); 
           
      $msg_minandmax_label = 'Maximum';
      $this->vtmam_create_table_error_message_mom ($msg_minandmax_label);  
  } 
          
  //produce min or max messages
  public function vtmam_create_table_error_message_mom ($msg_minandmax_label) { 
      global $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info; 
      
      $mom_error_msg_produced = null;   //v2.0.0
      
      $message = __('<span id="table-error-messages">', 'vtmam');
      
      $sizeof_vtmam_rules_set = is_array($vtmam_rules_set) ? sizeof($vtmam_rules_set) : 0; //v2.0.0
      for($i=0; $i < $sizeof_vtmam_rules_set; $i++) { 
      //for($i=0; $i < sizeof($vtmam_rules_set); $i++) {               
        //verify that the rule both requires action, and has the group label we're interested in...
        if ( ( $vtmam_rules_set[$i]->rule_requires_cart_action == 'yes' ) && ( $vtmam_rules_set[$i]->minandmaxSelected_selection == $msg_minandmax_label ) ) { 
          //v1.07 begin
          if ( $vtmam_rules_set[$i]->custMsg_text > ' ') { //custom msg override              
              /*
              ==>> text error msg function always executed, so msg already loaded there - don't load here
              $vtmam_cart->error_messages[] = array (
                'msg_from_this_rule_id' => $vtmam_rules_set[$i]->post_id, 
                 'msg_minandmax_label' => $vtmam_rules_set[$i]->minandmaxSelected_selection, 
                'msg_from_this_rule_occurrence' => $i, 
                'msg_text'  => $vtmam_rules_set[$i]->custMsg_text,
                'msg_is_custom'   => 'yes' 
              );
              $this->vtmam_set_custom_msgs_status ('customMsg');
              */
              continue;
           }           
          //v1.07 end           
          $mom_error_msg_produced = 'yes';
          switch ( $vtmam_rules_set[$i]->specChoice_in_selection ) {
            case  'all' :
                 $vtmam_info['action_cnt'] = 0;
                 $sizeof_inpop_found_list = is_array($vtmam_rules_set[$i]->inpop_found_list) ? sizeof($vtmam_rules_set[$i]->inpop_found_list) : 0; //v2.0.0
                 for($k=0; $k < $sizeof_inpop_found_list; $k++) {  //v2.0.0
                 //for($k=0; $k < sizeof($vtmam_rules_set[$i]->inpop_found_list); $k++) { 
                    if ($vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] == 'yes'){
                       $vtmam_info['action_cnt']++;
                    }
                 }
                switch (true) {
                  case ( ( $vtmam_rules_set[$i]->inpop_selection == ('cart' || 'groups' || 'vargroup') ) && ( $vtmam_info['action_cnt'] > 1 ) ) : 
                      //this rule = whole cart                      
                      $vtmam_info['bold_the_error_amt_on_detail_line'] = 'no';
                      $message .= $this->vtmam_table_detail_lines_cntl($i);   
                      $message .= $this->vtmam_table_totals_line($i);
                      $message .= $this->vtmam_table_text_line($i);
                    break;

                  case $vtmam_info['action_cnt'] == 1 :
                      $vtmam_info['bold_the_error_amt_on_detail_line'] = 'yes';
                      $message .= $this->vtmam_table_detail_lines_cntl($i);
                      $message .= $this->vtmam_table_text_line($i);
                    break;
                } 
              break;
            case  'each' :
                $vtmam_info['bold_the_error_amt_on_detail_line'] = 'yes';
                $message .= $this->vtmam_table_detail_lines_cntl($i);
                $message .= $this->vtmam_table_text_line($i);
              break;
            case  'any' :
                $vtmam_info['bold_the_error_amt_on_detail_line'] = 'yes';
                $message .= $this->vtmam_table_detail_lines_cntl($i);
                $message .= $this->vtmam_table_text_line($i);
              break;
          
          } 
          $message .= __('<br /><br />', 'vtmam');  //empty line between groups
        }
        
        //new color for next rule
        $vtmam_info['cart_color_cnt']++; 
      } 
    
      //close up owning span
      $message .= __('</span>', 'vtmam'); //end "table-error-messages"
      
      $rule_id_list = null; //v2.0.0 unused currently
                                                                                         
      if ($mom_error_msg_produced) {
        $vtmam_cart->error_messages[] = array (
          'msg_from_this_rule_id' => $rule_id_list, 
          'msg_minandmax_label' => $msg_minandmax_label, 
          'msg_from_this_rule_occurrence' => '', 
          'msg_text'  => $message,
          'msg_is_custom'   => 'no'    //v1.07 
           );
         $this->vtmam_set_custom_msgs_status ('standardMsg');     //v1.07       
      }
  } 
   
  
   public function vtmam_table_detail_lines_cntl ($i) {
      global $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info;
      
      $message_details = $this->vtmam_table_titles();
      
      $sizeof_inpop_found_list = is_array($vtmam_rules_set[$i]->inpop_found_list) ? sizeof($vtmam_rules_set[$i]->inpop_found_list) : 0; //v2.0.0
      
      //Version 1.01  new IF structure  replaced straight 'for' loop
      if ( $vtmam_rules_set[$i]->specChoice_in_selection == 'all' ) {
         for($r=0; $r < $sizeof_inpop_found_list; $r++) {   //v2.0.0
         //for($r=0; $r < sizeof($vtmam_rules_set[$i]->inpop_found_list); $r++) {  
            $k = $vtmam_rules_set[$i]->inpop_found_list[$r]['prod_id_cart_occurrence'];
            $message_details .= $this->vtmam_table_line ($i, $k);  
          }
      } else {    // each or any
        for($r=0; $r < $sizeof_inpop_found_list; $r++) {   //v2.0.0
        //for($r=0; $r < sizeof($vtmam_rules_set[$i]->inpop_found_list); $r++) { //
            if ($vtmam_rules_set[$i]->inpop_found_list[$r]['prod_requires_action'] == 'yes'){
              $k = $vtmam_rules_set[$i]->inpop_found_list[$r]['prod_id_cart_occurrence'];
              $message_details .= $this->vtmam_table_line ($i, $k);
           }  
        }
      }
      
      return $message_details;
   }
        
   public function vtmam_table_line ($i, $k){
      global $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info;

     $vtmam_info['line_cnt']++;
       
     $message_line = __('<span class="table-msg-line">', 'vtmam');  //v2.0.0
     $message_line .= __('<span class="product-column color-grp', 'vtmam');
     $message_line .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
     $message_line .= __('">', 'vtmam');
     $message_line .= $vtmam_cart->cart_items[$k]->product_name;
     $message_line .= __('</span>', 'vtmam'); //end "product" end "color-grp"
     
     if ($vtmam_rules_set[$i]->amtSelected_selection == 'quantity')   {
        $message_line .= __('<span class="quantity-column color-grp', 'vtmam');
        $message_line .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
        if ( $vtmam_info['bold_the_error_amt_on_detail_line'] == 'yes') {
           $message_line .= __(' bold-this', 'vtmam');
        }
        $message_line .= __('">', 'vtmam');
      } else {
        $message_line .= __('<span class="quantity-column">', 'vtmam');  
      }
     $message_line .= $vtmam_cart->cart_items[$k]->quantity;
     if ( ($vtmam_rules_set[$i]->amtSelected_selection == 'quantity') && ($vtmam_info['bold_the_error_amt_on_detail_line'] == 'yes') ) {
       $message_line .= __(' &nbsp;(Error)', 'vtmam');
     }
     $message_line .= __('</span>', 'vtmam'); //end "quantity" end "color-grp"
     
     $message_line .= __('<span class="price-column">', 'vtmam');
     $message_line .= vtmam_format_money_element($vtmam_cart->cart_items[$k]->unit_price);
     //$message_line .= $vtmam_cart->cart_items[$k]->unit_price;
     $message_line .= __('</span>', 'vtmam'); //end "price"
     
     if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency')   {
        $message_line .= __('<span class="total-column color-grp', 'vtmam');
        $message_line .= $vtmam_info['cart_color_cnt'];
        if ( $vtmam_info['bold_the_error_amt_on_detail_line'] == 'yes') {
           $message_line .= __(' bold-this', 'vtmam');
        }
        $message_line .= __('">', 'vtmam');
      } else {
        $message_line .= __('<span class="total-column">', 'vtmam');   
      }
     //$message_line .= $vtmam_cart->cart_items[$k]->total_price;
     $message_line .= vtmam_format_money_element($vtmam_cart->cart_items[$k]->total_price);
     if ( ($vtmam_rules_set[$i]->amtSelected_selection == 'currency') && ($vtmam_info['bold_the_error_amt_on_detail_line'] == 'yes') ) {
       $message_line .= __(' &nbsp;(Error)', 'vtmam');
     }     
     $message_line .= __('</span>', 'vtmam'); //end "total-column"  end "color-grp"
     $message_line .= __('</span>', 'vtmam'); //end "table-msg-line"
     
     //keep a running total
     $vtmam_info['cart_grp_info']['qty']   += $vtmam_cart->cart_items[$k]->quantity; 
     $vtmam_info['cart_grp_info']['price'] += $vtmam_cart->cart_items[$k]->total_price; 
     
     return  $message_line;
   }
   
         
   public function vtmam_table_totals_line ($i){
      global $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info;

     $vtmam_info['line_cnt']++;
      
     $message_totals = __('<span class="table-totals-line">', 'vtmam');  //v2.0.0
     $message_totals .= __('<span class="product-column">', 'vtmam');
     $message_totals .= __('&nbsp;', 'vtmam');
     $message_totals .= __('</span>', 'vtmam'); //end "product"
     
     if ($vtmam_rules_set[$i]->amtSelected_selection == 'quantity')   {
        $message_totals .= __('<span class="quantity-column quantity-column-total color-grp', 'vtmam');
        $message_totals .= $vtmam_info['cart_color_cnt'];
        $message_totals .= __('">(', 'vtmam');
        //grp total qty
        $message_totals .= $vtmam_info['cart_grp_info']['qty'];
        $message_totals .= __(') Error', 'vtmam');
      } else {
        $message_totals .= __('<span class="quantity-column">', 'vtmam');
        $message_totals .= __('&nbsp;', 'vtmam');                                                                                    
      }     
     $message_totals .= __('</span>', 'vtmam'); //end "quantity" "color-grp"
     
     $message_totals .= __('<span class="price-column">', 'vtmam');
     $message_totals .= __('&nbsp;', 'vtmam');
     $message_totals .= __('</span>', 'vtmam'); //end "price"
     
     if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency')   {
        $message_totals .= __('<span class="quantity-column total-column-total color-grp', 'vtmam');
        $message_totals .= $vtmam_info['cart_color_cnt'];
        $message_totals .= __('">(', 'vtmam');
        //grp total price
        $message_totals .= vtmam_format_money_element($vtmam_info['cart_grp_info']['price']);
        $message_totals .= __(') Error', 'vtmam'); 
      } else {
        $message_totals .= __('<span class="quantity-column">', 'vtmam');
        $message_totals .= __('&nbsp;', 'vtmam');
      }
     $message_totals .= __('</span>', 'vtmam'); //end "total" "color-grp"
     $message_totals .= __('</span>', 'vtmam'); //end "table-totals-line"
     
     return $message_totals;
   }
   
   public function vtmam_table_titles() {
     global $vtmam_info;
     
          $message_title  = __('<span class="table-titles">', 'vtmam');   //v2.0.0
             $message_title .= __('<span class="product-column product-column-title">Product:</span>', 'vtmam');
             $message_title .= __('<span class="quantity-column quantity-column-title">Quantity:</span>', 'vtmam');
             $message_title .= __('<span class="price-column price-column-title">Price:</span>', 'vtmam');
             $message_title .= __('<span class="total-column total-column-title">Total:</span>', 'vtmam');           
          $message_title .= __('</span>', 'vtmam'); //end "table-titles"
        
      $this->vtmam_init_grp_info();
      
      return $message_title;
   }
   
   public function vtmam_init_grp_info() {
     global $vtmam_info;
     $vtmam_info['cart_grp_info'] = array( 'qty'    => 0,
                                           'price'    => 0
                                          );
   }
/* v1.07.1   
   public function vtmam_format_money_element($money) { 
     global $vtmam_setup_options; 
           
     $formatted = sprintf("%01.2f", $money); //yields 2places filled right of the dec
     $formatted = $this->vtmam_get_currency_symbol( $vtmam_setup_options['use_this_currency_sign'] ) . $formatted;
     return $formatted;
   }
   
   public function vtmam_get_currency_symbol( $currency ) {
    	$currency_symbol = '';
    	switch ($currency) {
    		case 'BRL' : $currency_symbol = '&#82;&#36;'; break;
    		case 'AUD' : $currency_symbol = '&#36;'; break;
    		case 'CAD' : $currency_symbol = '&#36;'; break;
    		case 'MXN' : $currency_symbol = '&#36;'; break;
    		case 'NZD' : $currency_symbol = '&#36;'; break;
    		case 'HKD' : $currency_symbol = '&#36;'; break;
    		case 'SGD' : $currency_symbol = '&#36;'; break;
    		case 'USD' : $currency_symbol = '&#36;'; break;
    		case 'EUR' : $currency_symbol = '&euro;'; break;
    		case 'CNY' : $currency_symbol = '&yen;'; break;
    		case 'RMB' : $currency_symbol = '&yen;'; break;
    		case 'JPY' : $currency_symbol = '&yen;'; break;
    		case 'TRY' : $currency_symbol = '&#84;&#76;'; break;
    		case 'NOK' : $currency_symbol = '&#107;&#114;'; break;
    		case 'ZAR' : $currency_symbol = '&#82;'; break;
    		case 'CZK' : $currency_symbol = '&#75;&#269;'; break;
    		case 'MYR' : $currency_symbol = '&#82;&#77;'; break;
    		case 'DKK' : $currency_symbol = '&#107;&#114;'; break;
    		case 'HUF' : $currency_symbol = '&#70;&#116;'; break;
    		case 'ILS' : $currency_symbol = '&#8362;'; break;
    		case 'PHP' : $currency_symbol = '&#8369;'; break;
    		case 'PLN' : $currency_symbol = '&#122;&#322;'; break;
    		case 'SEK' : $currency_symbol = '&#107;&#114;'; break;
    		case 'CHF' : $currency_symbol = '&#67;&#72;&#70;'; break;
    		case 'TWD' : $currency_symbol = '&#78;&#84;&#36;'; break;
    		case 'THB' : $currency_symbol = '&#3647;'; break;
    		case 'GBP' : $currency_symbol = '&pound;'; break;
    		case 'RON' : $currency_symbol = 'lei'; break;
    		default    : $currency_symbol = ''; break;
    	}
    	return $currency_symbol;
  } 
*/           
   public function vtmam_table_text_line($i){
      global $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info;

      $vtmam_info['line_cnt']++;
     
       //SHOW TARGET MIN $/QTY AND CURRENTLY REACHED TOTAL
      
      $message_text = __('<span class="table-error-msg"><span class="bold-this color-grp', 'vtmam');    //v2.0.0
      $message_text .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
      $message_text .= __('">', 'vtmam');
      $message_text .= __('Error => </span>', 'vtmam');  //end "color-grp"
      if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
        //$message_text .= __('Minimum Purchase ', 'vtmam'); 
        //v1.07.9 begin
        if ($vtmam_rules_set[$i]->repeatingGroups > 0) {
          $message_text .= __('</span>Minimum Purchase/Repeating Groups ', 'vtmam');
        } else {
          $message_text .= __('</span>Minimum Purchase ', 'vtmam');
        }
        //v1.07.9 end         
      } else {
        $message_text .= __('Maximum Purchase ', 'vtmam'); 
      }
      
      
      if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency') {
        if ( $vtmam_rules_set[$i]->specChoice_in_selection == 'all' ) {
          $message_text .= __('total', 'vtmam');
        }
      } else {
        $message_text .= __(' <span class="color-grp', 'vtmam');
        $message_text .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
        $message_text .= __('">', 'vtmam');
        $message_text .= __('quantity</span>', 'vtmam');    //end "color-grp"
      }
      $message_text .= __(' of <span class="color-grp', 'vtmam'); 
      $message_text .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
      $message_text .= __('">', 'vtmam');
      
      if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency') {
        $message_text .= vtmam_format_money_element($vtmam_rules_set[$i]->minandmax_amt['value']);
        if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') {
            $message_text .= __('</span> required ', 'vtmam');     //if branch end "color-grp"
        } else {
            $message_text .= __('</span> allowed ', 'vtmam');     //if branch end "color-grp"
        }
      } else {
        $message_text .= $vtmam_rules_set[$i]->minandmax_amt['value'];
        if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') { 
            $message_text .= __(' </span>units required  ', 'vtmam');    //if branch end "color-grp"
        } else { 
            $message_text .= __(' </span>units allowed ', 'vtmam');    //if branch end "color-grp"
        }
      }                    
      
      switch( $vtmam_rules_set[$i]->inpop_selection ) {      
         case 'single' : 
            $message_text .= __('for this product.', 'vtmam');
            break;
         case 'vargroup' : 
            switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
                case 'all': 
                    $message_text .= __('for this group.', 'vtmam');
                  break;
                case 'each':
                    $message_text .= __('for each product within the group.', 'vtmam');                             
                  break;
                case 'any':
                    $message_text .= __('for the first ', 'vtmam');
                    $message_text .= __('<span class="color-grp', 'vtmam');
                    $message_text .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
                    $message_text .= __('">', 'vtmam'); 
                    $message_text .= $vtmam_rules_set[$i]->anyChoice_max['value']; 
                    $message_text .= __(' </span>product(s) found within the product group.', 'vtmam');   //end "color-grp"
                                               
                  break;
              }
            break;
         case  'groups' :
             switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
                case 'all': 
                    $message_text .= __('for this group.', 'vtmam');
                  break;
                case 'each':
                    $message_text .= __('for each product within the group.', 'vtmam');                             
                  break;
                case 'any':
                    $message_text .= __('for the first ', 'vtmam');
                    $message_text .= __('<span class="color-grp', 'vtmam');
                    $message_text .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
                    $message_text .= __('">', 'vtmam'); 
                    $message_text .= $vtmam_rules_set[$i]->anyChoice_max['value']; 
                    $message_text .= __(' </span>product(s) found within the product group.', 'vtmam');   //end "color-grp"
                                               
                  break;
              }
            break;
         case  'cart' : 
             switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
                case 'all': 
                    $message_text .= __('for the cart.', 'vtmam');
                  break;
                case 'each':
                    $message_text .= __('for each product the cart.', 'vtmam');                             
                  break;
                case 'any':
                    $message_text .= __('for the first ', 'vtmam');
                    $message_text .= __('<span class="color-grp', 'vtmam');
                    $message_text .= $vtmam_info['cart_color_cnt'];  //append the count which corresponds to a css color...
                    $message_text .= __('">', 'vtmam'); 
                    $message_text .= $vtmam_rules_set[$i]->anyChoice_max['value']; 
                    $message_text .= __(' </span>product(s) found within the cart.', 'vtmam');  //end "color-grp"                            
                  break;
              }
            break;
      }
      
      //show rule id in error msg      
      if ( ( $vtmam_setup_options['show_rule_ID_in_errmsg'] == 'yes' ) ||  ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ) ) {
        $message_text .= __('<span class="rule-id"> (Rule ID = ', 'vtmam');
        $message_text .= $vtmam_rules_set[$i]->post_id;
        $message_text .= __(') </span>', 'vtmam');
      }
      
          
      $message_text .= __('</span>', 'vtmam'); //end "table-error-msg"  

    
     //SHOW CATEGORIES TO WHICH THIS MSG APPLIES IN GENERAL, IF RELEVANT
      $sizeof_errProds_cat_names = is_array($vtmam_rules_set[$i]->errProds_cat_names) ? sizeof($vtmam_rules_set[$i]->errProds_cat_names) : 0; //v2.0.0
      if ( ( $vtmam_rules_set[$i]->inpop_selection <> 'single'  ) && ( $sizeof_errProds_cat_names > 0 ) ) {   //v2.0.0
      //if ( ( $vtmam_rules_set[$i]->inpop_selection <> 'single'  ) && ( sizeof($vtmam_rules_set[$i]->errProds_cat_names) > 0 ) ) {
        $vtmam_info['line_cnt']++;
        $message_text .= __('<span class="table-text-line">', 'vtmam');
        $vtmam_rules_set[$i]->errProds_size = $sizeof_errProds_cat_names;    //v2.0.0
        //$vtmam_rules_set[$i]->errProds_size = sizeof($vtmam_rules_set[$i]->errProds_cat_names);    //v2.0.0
        $message_text .= __('<span class="table-text-cats">The maximum purchase rule applies to any products in the following categories: </span><span class="black-font-italic">', 'vtmam');
        for($k=0; $k < $vtmam_rules_set[$i]->errProds_size; $k++) {
            $message_text .= __(' "', 'vtmam');
            $message_text .= $vtmam_rules_set[$i]->errProds_cat_names[$k];
            $message_text .= __('" ', 'vtmam');  
        }        
        $message_text .= __('</span>', 'vtmam');  //end "table-text-cats"
        $message_text .= __('</span>', 'vtmam');  //end "table-text-line"
      } 
        
      return $message_text;     
   }                                    
  
        
   public function vtmam_create_text_error_message ($i) { 
     global $vtmam_setup_options, $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info; 
     
     $vtmam_rules_set[$i]->rule_requires_cart_action = 'yes';
          
      //v1.07 begin
      if ( $vtmam_rules_set[$i]->custMsg_text > ' ') { //custom msg override              
          $vtmam_cart->error_messages[] = array (
            'msg_from_this_rule_id' => $vtmam_rules_set[$i]->post_id, 
            'msg_minandmax_label' => $vtmam_rules_set[$i]->minandmaxSelected_selection, 
            'msg_from_this_rule_occurrence' => $i, 
            'msg_text'  => $vtmam_rules_set[$i]->custMsg_text,
            'msg_is_custom'   => 'yes' 
          );
          $this->vtmam_set_custom_msgs_status('customMsg'); 
          return;
       }           
      //v1.07 end
         
     if  ( $vtmam_setup_options['show_error_messages_in_table_form'] == 'yes' ) {
        $vtmam_info['error_message_needed'] = 'yes';
        //   $vtmam_cart->error_messages[] = array ('msg_from_this_rule_id' => $vtmam_rules_set[$i]->post_id, 'msg_from_this_rule_occurrence' => $i,'msg_text'  => '' );  
     } else {     
        //SHOW PRODUCT NAME(S) IN ERROR
        $message; //initialize $message
        if ( $vtmam_rules_set[$i]->inpop_selection ==  ('cart' || 'groups' || 'vargroup') ) {
            if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') { 
                  $message .= __('<span class="errmsg-begin">Minimum Purchase Required -</span> for ', 'vtmam');
              } else {
                  $message .= __('<span class="errmsg-begin">Maximum Purchase Allowed -</span> for ', 'vtmam');
              }
        }
        switch( $vtmam_rules_set[$i]->inpop_selection ) {  
          case 'cart':
              switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
                case 'all': 
                    //$message .= __('all', 'vtmam');
                  break;
                case 'each':
                    $message .= __('each of', 'vtmam');                             
                  break;
                case 'any':
                    $message .= __('each of', 'vtmam');                             
                  break;
              } 
              $message .= __(' the product(s) in this group: <span class="red-font-italic">', 'vtmam'); 
              $message .= $this->vtmam_list_out_product_names($i);
              $message .= __('</span>', 'vtmam'); 
            break;
          case 'groups':                    
              switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
                case 'all': 
                    //$message .= __('all', 'vtmam');
                  break;
                case 'each':                                     
                    $message .= __('each of', 'vtmam');                             
                  break;
                case 'any':
                    $message .= __('each of', 'vtmam');                             
                  break;
              }
              $message .= __(' the products in this group: <span class="red-font-italic">', 'vtmam');
              $message .= $this->vtmam_list_out_product_names($i);
              $message .= __('</span>', 'vtmam'); 
            break;
          case 'vargroup':
              switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
                case 'all': 
                    $message .= __(' the products in this group: <span class="red-font-italic">', 'vtmam');
                  break;
                default:
                    $message .= __(' this product: <span class="red-font-italic">', 'vtmam');;                             
                  break;

              }
              $message .= $this->vtmam_list_out_product_names($i);
              $message .= __('</span>', 'vtmam'); 
            break;
          case 'single':
              $message .= __(' this product: <span class="red-font-italic">"', 'vtmam'); 
              $message .= $vtmam_rules_set[$i]->errProds_names [0];
              $message .= __('"</span>  ', 'vtmam');
            break;
        }                    
                                        
        //SHOW TARGET MIN $/QTY AND CURRENTLY REACHED TOTAL
        if ($vtmam_rules_set[$i]->amtSelected_selection == 'currency')   {
          
          if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') { 
            $message .= __('<br /><span class="errmsg-text">A minimum of &nbsp;<span class="errmsg-amt-required"> ', 'vtmam'); 
            $message .= vtmam_format_money_element( $vtmam_rules_set[$i]->minandmax_amt['value'] );
            switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
            case 'all': 
                $message .= __('</span> &nbsp;for the total group must be purchased.  The current total ', 'vtmam');
                $message .= __('for all the products ', 'vtmam'); 
                $message .= __('in the group is: <span class="errmsg-amt-current"> ', 'vtmam');
              break;
            default:    //each or any
                $message .= __('</span> &nbsp;for this product must be purchased.  The current total ', 'vtmam');
                $message .= __('for this product is: ', 'vtmam');                             
              break;

            }                                                      
          } else {
            $message .= __('<br /><span class="errmsg-text">A maximum of &nbsp;<span class="errmsg-amt-required"> ', 'vtmam'); 
            $message .= vtmam_format_money_element( $vtmam_rules_set[$i]->minandmax_amt['value'] );
            switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
            case 'all': 
                $message .= __('</span> &nbsp;for the total group may be purchased.  The current total ', 'vtmam');
                $message .= __('for all the products ', 'vtmam'); 
                $message .= __('in the group is: <span class="errmsg-amt-current"> ', 'vtmam');
              break;
            default:    //each or any
                $message .= __('</span> &nbsp;for this product may be purchased.  The current total ', 'vtmam');
                $message .= __('for this product is: ', 'vtmam');                             
              break;

            }                            
          }                                                  
          
          $message .= vtmam_format_money_element( $vtmam_rules_set[$i]->errProds_total_price );
          $message .= __(' </span></span> ', 'vtmam');
          
        } else {
          if ($vtmam_rules_set[$i]->minandmaxSelected_selection == 'Minimum') { 
            $message .= __('<br /><span class="errmsg-text">A minimum quantity of &nbsp;<span class="errmsg-amt-required"> ', 'vtmam'); 
            $message .= $vtmam_rules_set[$i]->minandmax_amt['value'];
            switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
              case 'all': 
                  $message .= __(' units</span> &nbsp;&nbsp;for the total group must be purchased.  The current total ', 'vtmam');  
                  $message .= __('for all the products ', 'vtmam');
                  $message .= __('in the group is: <span class="errmsg-amt-current"> ', 'vtmam');
                break;
              default:
                  $message .= __(' units</span> &nbsp;&nbsp;for each product in the group must be purchased.  The current total ', 'vtmam'); 
                  $message .= __('for this product is: ', 'vtmam');                              
                break;
            }
          } else {
            $message .= __('<br /><span class="errmsg-text">A maximum quantity of &nbsp;<span class="errmsg-amt-required"> ', 'vtmam'); 
            $message .= $vtmam_rules_set[$i]->minandmax_amt['value'];
            switch( $vtmam_rules_set[$i]->specChoice_in_selection ) {
              case 'all': 
                  $message .= __(' units</span> &nbsp;&nbsp;for the total group may be purchased.  The current total ', 'vtmam');  
                  $message .= __('for all the products ', 'vtmam');
                  $message .= __('in the group is: <span class="errmsg-amt-current"> ', 'vtmam');
                break;
              default:
                  $message .= __(' units</span> &nbsp;&nbsp;for each product in the group may be purchased.  The current total ', 'vtmam'); 
                  $message .= __('for this product is: ', 'vtmam');                              
                break;
            }
          }
                   
          $message .= $vtmam_rules_set[$i]->errProds_qty;
          if ($vtmam_rules_set[$i]->errProds_qty > 1) {
            $message .= __(' units.</span></span> ', 'vtmam');
          } else {
            $message .= __(' unit.</span></span> ', 'vtmam');
          }
          
        }
                                                       
              
        //show rule id in error msg      
        if ( ( $vtmam_setup_options['show_rule_ID_in_errmsg'] == 'yes' ) ||  ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ) ) {
          $message .= __('<span class="rule-id"> (Rule ID = ', 'vtmam');
          $message .= $vtmam_rules_set[$i]->post_id;
          $message .= __(') </span>', 'vtmam');
        }
  
        //SHOW CATEGORIES TO WHICH THIS MSG APPLIES IN GENERAL, IF RELEVANT
        $sizeof_errProds_cat_names = is_array($vtmam_rules_set[$i]->errProds_cat_names) ? sizeof($vtmam_rules_set[$i]->errProds_cat_names) : 0; //v2.0.0
        if ( ( $vtmam_rules_set[$i]->inpop_selection <> 'single'  ) && ( $sizeof_errProds_cat_names > 0 ) ) {    //v2.0.0
        //if ( ( $vtmam_rules_set[$i]->inpop_selection <> 'single'  ) && ( sizeof($vtmam_rules_set[$i]->errProds_cat_names) > 0 ) ) {
          $vtmam_rules_set[$i]->errProds_size = $sizeof_errProds_cat_names;   //v2.0.0
          //$vtmam_rules_set[$i]->errProds_size = sizeof($vtmam_rules_set[$i]->errProds_cat_names);
          $message .= __('<br />:: <span class="black-font">The maximum purchase rule applies to any products in the following categories: </span><span class="black-font-italic">', 'vtmam');
          for($k=0; $k < $vtmam_rules_set[$i]->errProds_size; $k++) {
              $message .= __(' "', 'vtmam');
              $message .= $vtmam_rules_set[$i]->errProds_cat_names[$k];
              $message .= __('" ', 'vtmam');
              $message .= __('</span>', 'vtmam');
          }
        }                                                  
                
        //queue the message to go back to the screen     
        $vtmam_cart->error_messages[] = array (
          'msg_from_this_rule_id' => $vtmam_rules_set[$i]->post_id, 
          'msg_minandmax_label' => $vtmam_rules_set[$i]->minandmaxSelected_selection, 
          'msg_from_this_rule_occurrence' => $i, 
          'msg_text'  => $message,
          'msg_is_custom'   => 'no'    //v1.07 
        ); 
        $this->vtmam_set_custom_msgs_status ('standardMsg');     //v1.07
        
      }  //end text message formatting
      /*
      if ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ){   
        echo '$message'; echo '<pre>'.print_r($message, true).'</pre>' ;
        echo '$vtmam_rules_set[$i]->errProds_qty = '; echo '<pre>'.print_r($vtmam_rules_set[$i]->errProds_qty, true).'</pre>' ;
        echo '$vtmam_rules_set[$i]->errProds_total_price = ' ; echo '<pre>'.print_r($vtmam_rules_set[$i]->errProds_total_price, true).'</pre>' ;
        echo '$vtmam_rules_set[$i]->errProds_names = '; echo '<pre>'.print_r($vtmam_rules_set[$i]->errProds_names, true).'</pre>' ;
        echo '$vtmam_rules_set[$i]->errProds_cat_names = '; echo '<pre>'.print_r($vtmam_rules_set[$i]->errProds_cat_names, true).'</pre>' ;   
      } 
      */
     
  } 
      
   //*************************************  
   //v1.07 new function 
   //*************************************    
   public function vtmam_set_custom_msgs_status ($message_state) { 
      global $vtmam_cart;
      switch( $vtmam_cart->error_messages_are_custom ) {  
        case 'all':
             if ($message_state == 'standardMsg') {
                $vtmam_cart->error_messages_are_custom = 'some';
             }
          break;
        case 'some':
          break;          
        case 'none':
             if ($message_state == 'customMsg') {
                $vtmam_cart->error_messages_are_custom = 'some';
             }
          break; 
        default:  //no state set yet
             if ($message_state == 'standardMsg') {
                $vtmam_cart->error_messages_are_custom = 'none';
             } else {
                $vtmam_cart->error_messages_are_custom = 'all';
             }
          break;                    
      }

      return;
   }      
   //v1.07 end
         
      
        
public function vtmam_product_is_in_inpop_group ($i, $k) { 
      global $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info, $vtmam_setup_options;
      /* at this point, the checked list produced at rule store time could be out of sync with the db, as the cats/roles originally selected to be
      *  part of this rule could have been deleted.  this won't affect these loops, as the deleted cats/roles will simply not be in the 
      *  'get_object_terms' list. */

      $vtmam_is_role_in_list  = $this->vtmam_is_role_in_list_test ($i, $k);
      
      if ($vtmam_is_role_in_list) {
         return true;
      }
      
      return false;
   }

  
    public function vtmam_is_role_in_list_test ($i, $k) {
      global $vtmam_cart, $vtmam_rules_set, $vtmam_rule, $vtmam_info, $vtmam_setup_options;     
      $sizeof_role_in_checked = is_array($vtmam_rules_set[$i]->role_in_checked) ? sizeof($vtmam_rules_set[$i]->role_in_checked) : 0; //v2.0.0  
      //if ( sizeof($vtmam_rules_set[$i]->role_in_checked) > 0 ) {
      if ( $sizeof_role_in_checked > 0 ) {  //v2.0.0 
            if (in_array($this->vtmam_get_current_user_role(), $vtmam_rules_set[$i]->role_in_checked )) {   //if role is in previously checked_list
                 /* if ( $vtmam_setup_options['debugging_mode_on'] == 'yes' ){ 
                    echo 'current user role= <pre>'.print_r($this->vtmam_get_current_user_role(), true).'</pre>' ;
                    echo 'rule id= <pre>'.print_r($vtmam_rules_set[$i]->post_id, true).'</pre>' ;  
                    echo 'role_in_checked= <pre>'.print_r($vtmam_rules_set[$i]->role_in_checked, true).'</pre>' ; 
                    echo 'i= '.$i . '<br>'; echo 'k= '.$k . '<br>';
                  }  */
              return true;                                
            } 
      } 
      return false;
    }
    

    public function vtmam_get_current_user_role() {
    	global $current_user;     
    	$user_roles = $current_user->roles;
    	$user_role = array_shift($user_roles);
      if  ($user_role <= ' ') {
        $user_role = 'notLoggedIn';
      }      
    	return $user_role;
      }
      
    public function vtmam_list_out_product_names($i) {
      $prodnames = null;  //v2.0.0
    	global $vtmam_rules_set;     
    	$sizeof_errProds_cat_names = is_array($vtmam_rules_set[$i]->errProds_cat_names) ? sizeof($vtmam_rules_set[$i]->errProds_cat_names) : 0; //v2.0.0
		for($p=0; $p < $sizeof_errProds_cat_names; $p++) { //v2.0.0
		//for($p=0; $p < sizeof($vtmam_rules_set[$i]->errProds_names); $p++) { //v2.0.0
          $prodnames .= __(' "', 'vtmam');
          $prodnames .= $vtmam_rules_set[$i]->errProds_names[$p];
          $prodnames .= __('"  ', 'vtmam');
      } 
    	return $prodnames;
    }
      
   public function vtmam_load_inpop_found_list($i, $k) {
    	global $vtmam_cart, $vtmam_rules_set;
      $vtmam_rules_set[$i]->inpop_found_list[] = array('prod_id' => $vtmam_cart->cart_items[$k]->product_id,
                                                       'prod_name' => $vtmam_cart->cart_items[$k]->product_name,
                                                       'prod_qty' => $vtmam_cart->cart_items[$k]->quantity, 
                                                       'prod_total_price' => $vtmam_cart->cart_items[$k]->total_price,
                                                       'prod_cat_list' => $vtmam_cart->cart_items[$k]->prod_cat_list,
                                                       'rule_cat_list' => $vtmam_cart->cart_items[$k]->rule_cat_list,
                                                       'prod_id_cart_occurrence' => $k, //used to mark product in cart if failed a rule
                                                       'prod_requires_action'  => '' 
                                                      );
     $vtmam_rules_set[$i]->inpop_qty_total   += $vtmam_cart->cart_items[$k]->quantity;
     $vtmam_rules_set[$i]->inpop_total_price += $vtmam_cart->cart_items[$k]->total_price;
   }
     
  public function vtmam_init_recursive_work_elements($i){ 
    global $vtmam_rules_set;
    $vtmam_rules_set[$i]->errProds_qty = 0 ;
    $vtmam_rules_set[$i]->errProds_total_price = 0 ;
    $vtmam_rules_set[$i]->errProds_ids = array() ;
    $vtmam_rules_set[$i]->errProds_names = array() ;    
  }
  public function vtmam_init_cat_work_elements($i){ 
    global $vtmam_rules_set;
    $vtmam_rules_set[$i]->errProds_cat_names = array() ;             
  }     

  public function vtmam_mark_product_as_requiring_cart_action($i,$k){ 
    global $vtmam_rules_set, $vtmam_cart;
    //mark the product in the rules_set
    $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_requires_action'] = 'yes';
    $z = $vtmam_rules_set[$i]->inpop_found_list[$k]['prod_id_cart_occurrence'];
    //prepare for future rollout needs if a rule population conflict ensues
    $vtmam_cart->cart_items[$z]->product_participates_in_rule[] =  
        array(
          'post_id'            => $vtmam_rules_set[$i]->post_id,
          'inpop_selection'    => $vtmam_rules_set[$i]->inpop_selection, //needed to test for 'vargroup'
          'ruleset_occurrence' => $i,
          'inpop_occurrence'   => $k 
        ) ;           
  }     
  

} //end class


