<?php
   
class VTMAM_Rule_update {
	

	public function __construct(){  
        $this->vtmam_update_rule();
    }
            
  public  function vtmam_update_rule () {
      global $post, $vtmam_rule; 
      $post_id = $post->ID;                                                                                                                                                          
      $vtmam_rule_new = new VTMAM_Rule();   //  always  start with fresh copy
      $selected = 's';

      $vtmam_rule = $vtmam_rule_new;  //otherwise vtmam_rule is not addressable!
       
     //*****************************************
     //  FILL / upd VTMAM_RULE...
     //*****************************************
     //   Candidate Population
     
     $vtmam_rule->post_id = $post_id;

     if ( ($_REQUEST['post_title'] > ' ' ) ) {
       //do nothing
     }
     else { 
       $vtmam_rule->rule_error_message[] = __('The Rule needs to have a title, but title is empty.', 'vtmam');
     }
      
     $vtmam_rule->inpop_selection = $_REQUEST['popChoice'];
     switch( $vtmam_rule->inpop_selection ) {
        case 'groups':
              $vtmam_rule->inpop[1]['user_input'] = $selected;
              //  $vtmam_checkbox_classes = new VTMAM_Checkbox_classes;
                  //get all checked taxonomies/roles as arrays

             if(!empty($_REQUEST['tax-input-role-in'])) {
                $vtmam_rule->role_in_checked = $_REQUEST['tax-input-role-in'];
             }
             if ((!$vtmam_rule->prodcat_in_checked) && (!$vtmam_rule->rulecat_in_checked) && (!$vtmam_rule->role_in_checked))  {
                $vtmam_rule->rule_error_message[] = __('In Cart Search Criteria Selection Metabox, "Use Selection Groups" was chosen, but no Categories or Roles checked', 'vtmam');
             }                                  
             //   And/Or switch for category/role relationship  
             $vtmam_rule->role_and_or_in_selection  = $_REQUEST['andorChoice']; 
             $this->vtmam_set_default_or_values();            
          break;
        
      }

  
      
          
     //   Population Handling Specifics   
     $vtmam_rule->specChoice_in_selection = $_REQUEST['specChoice'];
     
     switch( $vtmam_rule->specChoice_in_selection ) {
        case 'all':
            $vtmam_rule->specChoice_in[0]['user_input'] = $selected;
          break;
        case 'each':
            $vtmam_rule->specChoice_in[1]['user_input'] = $selected;
          break;
        case 'any':
            $vtmam_rule->specChoice_in[2]['user_input'] = $selected;
            if (empty($_REQUEST['anyChoice-max'])) {
                $vtmam_rule->rule_error_message[] = __('In Select Rule Application cs Metabox, "*Any* in the Population" was chosen, but Maximum products count not filled in', 'vtmam');
            } else { 
                $vtmam_rule->anyChoice_max['value'] = $_REQUEST['anyChoice-max'];
                if ($vtmam_rule->anyChoice_max['value'] == ' '){
                  $vtmam_rule->rule_error_message[] = __('In Select Rule Application  Metabox, "*Any* in the Population" was chosen, but Maximum products count not filled in', 'vtmam');
                } 
                if ( is_numeric($vtmam_rule->anyChoice_max['value'])  === false  ) {
                   $vtmam_rule->rule_error_message[] = __('In Select Rule Application  Metabox, "*Any* in the Population" was chosen, but Maximum products count not numeric', 'vtmam');              
                }
          }    
          break;
      }
              
       
     //   Maximum Amount for this role
     $vtmam_rule->amtSelected_selection = $_REQUEST['amtSelected']; 
     
     switch( $vtmam_rule->amtSelected_selection ) {
        case 'quantity':
            $vtmam_rule->amtSelected[0]['user_input'] = $selected;
          break;
        case 'currency':
            $vtmam_rule->amtSelected[1]['user_input'] = $selected;
          break;
     } 
     if (empty($_REQUEST['amtChoice-count'])) {
        $vtmam_rule->rule_error_message[] = __('In Min and Max Amount for this role Metabox, Min and Max Amount not filled in', 'vtmam');
     } else { 
        $vtmam_rule->minandmax_amt['value'] = $_REQUEST['amtChoice-count'];
        if ($vtmam_rule->minandmax_amt['value'] == ' '){
          $vtmam_rule->rule_error_message[] = __('In Min and Max Amount for this role Metabox, Min and Max Amount not filled in', 'vtmam');
        }  
        if ( is_numeric($vtmam_rule->minandmax_amt['value']) === false  ) {
           $vtmam_rule->rule_error_message[] = __('In Min and Max Amount for this role Metabox, Min and Max Amount not numeric', 'vtmam');              
        }
     }
    
                      
     //   Min and Max Direction choice                                                           
     $vtmam_rule->minandmaxSelected_selection = $_REQUEST['minandmaxSelected']; 
     
     switch( $vtmam_rule->minandmaxSelected_selection ) {
        case 'Minimum':
            $vtmam_rule->minandmaxSelected[0]['user_input'] = $selected;
          break;
        case 'Maximum':
            $vtmam_rule->minandmaxSelected[1]['user_input'] = $selected;
          break;
     } 
     
     //   Max Rule Type choice                                                           
     $vtmam_rule->maxRule_typeSelected_selection = $_REQUEST['maxRule-typeSelected']; 
     
     switch( $vtmam_rule->maxRule_typeSelected_selection ) {
        case 'cart':
            $vtmam_rule->maxRule_typeSelected[0]['user_input'] = $selected;
          break;
        case 'lifetime':
            $vtmam_rule->maxRule_typeSelected[1]['user_input'] = $selected;
          break;
     }     

     //v1.07.9 begin
     $vtmam_rule->repeatingGroups = $_REQUEST['repeating-groups'];
     if ( ( $vtmam_rule->repeatingGroups == '')  ||
          ( $vtmam_rule->repeatingGroups == ' ') ) {
        $vtmam_rule->repeatingGroups = '';   //re-initialize if default msg still there...
     } else {
       if ( is_numeric($vtmam_rule->repeatingGroups)  === false  ) {
           $vtmam_rule->rule_error_message[] = __('If Repeating Groups is chosen, this must be a number greater than 0.', 'vtmam');              
       }
     }  
     //v1.07.9 end
          
     //v1.07 begin
     $vtmam_rule->custMsg_text = $_REQUEST['cust-msg-text'];
     global $vtmam_info;
     if ( $vtmam_rule->custMsg_text == $vtmam_info['default_full_msg']) {
        $vtmam_rule->custMsg_text = '';   //re-initialize if default msg still there...
     }   
     //v1.07 end
     
    //*****************************************
    //  If errors were found, the error message array will be displayed by the UI on next screen send.
    //*****************************************
    $sizeof_rule_error_message = is_array($vtmam_rule->rule_error_message) ? sizeof($vtmam_rule->rule_error_message) : 0; //v2.0.0
    if  ( $sizeof_rule_error_message > 0 ) {   //v2.0.0
    //if  ( sizeof($vtmam_rule->rule_error_message) > 0 ) {
      $vtmam_rule->rule_status = 'pending';
    } else {
      $vtmam_rule->rule_status = 'publish';
    }
   
    $rules_set_found = false;
    $vtmam_rules_set = vtmam_get_rules_set(); //v2.0.0 
    $sizeof_rules_set = is_array($vtmam_rules_set) ? sizeof($vtmam_rules_set) : 0; //v2.0.0 
    if ($sizeof_rules_set > 0) {  //v2.0.0 
      $rules_set_found = true;
    }
          
    if ($rules_set_found) {
      $rule_found = false;
      for($i=0; $i < $sizeof_rules_set; $i++) { //v2.0.0
         if ($vtmam_rules_set[$i]->post_id == $post_id) {
            $vtmam_rules_set[$i] = $vtmam_rule;
            $i =  $sizeof_rules_set;  //v2.0.0
            $rule_found = true; 
         }
      }
      if (!$rule_found) {
         $vtmam_rules_set[] = $vtmam_rule;
      } 
    } else {
      $vtmam_rules_set = array ();
      $vtmam_rules_set[] = $vtmam_rule;
    }
  
    vtmam_set_rules_set($vtmam_rules_set);  //v2.0.0
     
  } //end function

 //default to 'OR', as the default value goes away and may be needed if the user switches back to 'groups'...
  public function vtmam_set_default_or_values () {
    global $vtmam_rule;  
    $vtmam_rule->role_and_or_in[1]['user_input'] = 's'; //'s' = 'selected'
    $vtmam_rule->role_and_or_in_selection = 'or'; 
  } 

  
} //end class
