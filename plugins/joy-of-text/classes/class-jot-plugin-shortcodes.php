<?php
/**
* Joy_Of_Text shortcodes. Processes shortcode requests
*
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



final class Joy_Of_Text_Plugin_Shortcodes {
 
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
 
    /**
     * Initializes the plugin 
     */
    function __construct() {
          add_shortcode('jotform',array($this, 'process_jotform_shortcode'));
          
          add_action( 'wp_ajax_group_subscribe', array( &$this, 'group_subscribe' ) );
          add_action( 'wp_ajax_nopriv_group_subscribe', array( &$this, 'group_subscribe' ) );
         
          // Generate HTML for Group Invite
          add_action( 'wp_ajax_process_generate_invite_html', array( $this, 'process_generate_invite_html' ) );
         
    } // end constructor
 
    private static $_instance = null;
        
        public static function instance () {
            if ( is_null( self::$_instance ) )
                self::$_instance = new self();
            return self::$_instance;
        } // End instance()

     public function process_jotform_shortcode ($atts) {
        
               $error = 0;
               $subhtml = "";
               
	       // Style      - new|old - option for display old style form or the new form
	       // multitype - select|multiselect|checkbox
	       $atts = shortcode_atts(
		array(
                    'id'          => '',
                    'group_id'    => '',
		    'formstyle'   => 'new',
		    'multitype'   => '',
		    'name'        => 'yes',
                    'email'       => 'no',
                    'address'     => 'no',
                    'city'        => 'no',
                    'state'       => 'no',
                    'zip'         => 'no'  			
		), $atts, 'jotform' );
              
	       	      
               // Group id in 'id' or 'group_id' fields
               if ($atts['group_id'] != "") {
                    $all_group_id = explode(",",$atts['group_id']);		   
               } elseif ($atts['id'] != "") {
                    $all_group_id = explode(",",$atts['id']);
               }
	       
	       // Select the primary group id - i.e. the first in the list    
	       $group_id = isset($all_group_id[0]) ? $all_group_id[0] : "";
              
	       if ( ! ctype_digit(strval($group_id)) ) {
                    // Id is not an integer          
                    $error = 1;
               } else {
                
                    //Get group invite details from database.
                    global $wpdb;
                    $table = $wpdb->prefix."jot_groupinvites";
                    $sql = " SELECT jot_grpid, jot_grpinvdesc, jot_grpinvnametxt, jot_grpinvnumtxt, jot_grpinvretchk, jot_grpinvrettxt" .
                       " FROM " . $table .
                       " WHERE jot_grpid = " . $group_id;
                    
                    
                    $groupinvite = $wpdb->get_row( $sql );
                    if (!$groupinvite) {
                        //group is not found
                        $error=2;            
                    }
                    
                    switch ( $error ) {
                             case 0;
                                  $subhtml = $this->get_wrapped_jotform($group_id, $all_group_id, $groupinvite, $atts, "");
                             break;
                             case 1;
                                   //Group ID is not an integer
                                   $subhtml = "<div>";
                                   $subhtml .= '<p>jotform shortcode error. Group ID field in shortcode is not valid.<p>';
                                   $subhtml .= '</div>';
                             break;
                             case 2:
                                   // ID not found
                                   $subhtml = "<div>";
                                   $subhtml .= '<p>jotform shortcode error. Group ID field in shortcode "' . $atts['id'] . '" is not found.<p>';
                                   $subhtml .= '</div>';
                             break;
                             default:
                             # code...
                             break;
                    }
                }
                    
               return apply_filters( 'jot_jotform_shortcode', $subhtml);
                
     }
     
     public function get_wrapped_jotform($group_id, $all_group_id, $groupinvite, $atts, $confirm_set) {
	      
	       $subhtml = '<div>';
	       $subhtml .= '<form id="jot-subscriber-form-' . $group_id . '" action="" method="post">';
	       if (count($all_group_id) == 1) {
		    $subhtml .= '<input type="hidden"  name="jot-group-id" value="' . $group_id . '">';
	       }
	       $subhtml .= '<input type="hidden"  name="jot_form_id" value="jot-subscriber-form">';
	       $subhtml .= '<input type="hidden"  name="jot-form-special"  id="jot-form-special" class="jot-special" value="">';
	       $subhtml .= '<input type="hidden"  name="jot-verified-number"  id="jot-verified-number"  value="">';
	       
	     
	       $style = isset($atts['formstyle']) ?  $atts['formstyle'] : 'new';
	       if (strtolower($style) == 'old') {
		  $subhtml .= $this->get_old_jotform($group_id, $groupinvite, $atts, $confirm_set);                  
	       } else {
		  $subhtml .= $this->get_jotform($group_id, $all_group_id, $groupinvite, $atts, $confirm_set);
		  
		  // GDPR notice.
		  $jot_grpgdprtxt = Joy_Of_Text_Plugin()->settings->get_groupmeta($group_id,'jot_grpgdprtxt');
		  $jot_grpgdprchk = Joy_Of_Text_Plugin()->settings->get_groupmeta($group_id,'jot_grpgdprchk');
		  
		  // If GDPR notice checked
		  if ($jot_grpgdprchk) {
			if ($jot_grpgdprtxt != "") {
			      $subhtml .= '<fieldset class="jot-fieldset">';			
			       
			      if ($jot_grpgdprtxt != "") {
				    $jot_grpgdprtxt_replaced_tags = Joy_Of_Text_Plugin()->messenger->get_replace_tags($jot_grpgdprtxt,array(),$group_id);
			      } else {
				    $jot_grpgdprtxt_replaced_tags = "";
			      }
			       
			      $subhtml .= "<p></p>";
			      $subhtml .= '<div id="jot-gdprnotice">';
			      $subhtml .= $jot_grpgdprtxt_replaced_tags;
			      $subhtml .= '</div>';
			      $subhtml .= '</fieldset>';
			}
		  }
		    
		  // Subscribe error/status message
		  $subhtml .= '<fieldset class="jot-fieldset">';
                  $subhtml .= '<div id="jot-subscribemessage"></div>';
	          $subhtml .= '</fieldset>';
	       }
	      
	       $subhtml .= '</form>';
	       $subhtml .= '</div>';
	      
	       return $subhtml;

     }
     
     public function get_jotform($group_id, $all_group_id, $groupinvite, $atts, $confirm_set) {	  
	  
	  if ($groupinvite) {
	       $jot_grpinvdesc    = $groupinvite->jot_grpinvdesc;
	       $jot_grpinvnametxt = $groupinvite->jot_grpinvnametxt;
	       $jot_grpinvnumtxt  = $groupinvite->jot_grpinvnumtxt;
	  } else {
	       $jot_grpinvdesc    = isset($atts['jot_grpinvdesc'])    ? $atts['jot_grpinvdesc']    : "";
               $jot_grpinvnametxt = isset($atts['jot_grpinvnametxt']) ? $atts['jot_grpinvnametxt'] : "";
               $jot_grpinvnumtxt  = isset($atts['jot_grpinvnumtxt'])  ? $atts['jot_grpinvnumtxt']  : "";
	  }
	  
	  
	  $subhtml = '<fieldset class="jot-fieldset">';
	  $subhtml .= '<h3 id="jot-confirm-header">' . $jot_grpinvdesc . '</h3>';
	  $subhtml .= '<p></p>';
	    
	  // Name field
	  $name = isset($atts['name']) ?  $atts['name'] : 'yes';
	  if (strtolower($name) == 'no') {
	    $subhtml .= '<input id="jot-subscribe-name" name="jot-subscribe-name" maxlength="40" size="40" type="hidden" value="No name given"/>';  
	  } else {
	    $subhtml .= '<label for="jot-subscribe-name">' . $jot_grpinvnametxt . '</label>';
	    $subhtml .= '<input id="jot-subscribe-name" name="jot-subscribe-name" maxlength="40" size="40" type="text"/>';
	    $subhtml .= '<p></p>';
	  }  
	  
	  // Number field
	  $subhtml .= '<label for="jot-subscribe-num">' . $jot_grpinvnumtxt . '</label>';
	  $subhtml .= '<input id="jot-subscribe-num" name="jot-subscribe-num" maxlength="200" size="40" type="text"/>';
	  $subhtml .= '<p></p>';
	  
	 
	  // Option group select
	  if (count($all_group_id) > 1) {
	  
	       switch ( strtolower($atts['multitype']) ) {
                        case 'checkbox';
			      $subhtml .= '<label for="jot-group-checkboxes">' . __("Select groups","jot-plugin") . '</label>';
			      
			      foreach ($all_group_id as $grpid) {
				   $groupdetails = Joy_Of_Text_Plugin()->settings->get_group_details($grpid);				   
				   $group_desc = isset($groupdetails->jot_groupdesc) ? $groupdetails->jot_groupdesc : "";
				   if ($group_desc == "") {
					$group_desc = sprintf(__("Group %d","jot-plugin"), $grpid );
				   }
				   $subhtml .= "<div class='jot-multi-item'>";
				   $subhtml .= '<label for="jot-group-checkbox-' . $grpid . '" class="jot-group-checkbox">' ;
				   $subhtml .= '<input id="jot-group-checkbox-' . $grpid . '" name="jot-group-id[]" type="checkbox" value="' . $grpid . '"/>'  . $group_desc;
				   $subhtml .= '</label>';
				   $subhtml .= "</div>";
				   $subhtml .= "<br>";
			      }
			      $subhtml .= "<p></p>";
                        break;
		        case 'select';
		        case 'multiselect';
                              $subhtml .= '<label for="jot-group-select">' . __("Select group","jot-plugin") . '</label>'  . '<br>';
			      
			      if (strtolower($atts['multitype']) == "multiselect") {
				   $multiple = " multiple ";
				   $multiarray = "[]";
			      } else {
				   $multiple = "";
				   $multiarray = "";
			      }
			      
			      $subhtml .= '<select id="jot-group-id" name="jot-group-id' . $multiarray . '"' . $multiple . '>';
			      foreach ($all_group_id as $grpid) {
				   $groupdetails = Joy_Of_Text_Plugin()->settings->get_group_details($grpid);				   
				   $group_desc = isset($groupdetails->jot_groupdesc) ? $groupdetails->jot_groupdesc : "";
				   if ($group_desc == "") {
					$group_desc = sprintf(__("Group %d","jot-plugin"), $grpid );
				   }
				   $subhtml .= '<option value="'  . $grpid . '">' . $group_desc . '</option>';
			      }
			      $subhtml .= "</select>";
			      $subhtml .= "<p></p>";
                        break;		        
                        default:
                              $subhtml .= "JOTFORM type not known"; 
                        break;
	       }        
	       
	
	
	  }     
	  // Button
	  if ($confirm_set == 1) {
	      $button_label = __("Get confirmation code","jot-plugin");
	  } else {
	      $button_label = __("Subscribe","jot-plugin");
	  }
	  $subhtml .= '<input type="button" id="jot-subscribegroup-button" class="button" value="' . $button_label . '"/>';
	  	  
	  $subhtml .= '</fieldset>';
                                  
	  return $subhtml;
	  
	  
     }
     
     public function get_old_jotform($group_id, $groupinvite, $atts, $confirm_set) {
	  
	  
	  $subhtml = '<table>';
	  $subhtml .= '<tr><th colspan=2 class="jot-td-c">' . $groupinvite->jot_grpinvdesc . '</th></tr>';
	    
	  if (strtolower($atts['name']) == 'no') {
	    $subhtml .= '<tr><th></th><td><input id="jot-subscribe-name" name="jot-subscribe-name" maxlength="40" size="40" type="hidden" value="No name given"/></td></tr>';  
	  } else {
	    $subhtml .= '<tr><th>' . $groupinvite->jot_grpinvnametxt . '</th><td><input id="jot-subscribe-name" name="jot-subscribe-name" maxlength="40" size="40" type="text"/></td></tr>';
	  }  
	  
	  $subhtml .= '<tr><th>' . $groupinvite->jot_grpinvnumtxt . '</th><td><input id="jot-subscribe-num" name="jot-subscribe-num" maxlength="200" size="40" type="text"/></td></tr>';
	  
	  if ($confirm_set == 1) {
	      $button_label = __("Get confirmation code","jot-plugin");
	  } else {
	      $button_label = __("Subscribe","jot-plugin");
	  }
	  $subhtml .= '<tr>';
          $subhtml .= '<td><input type="button" id="jot-subscribegroup-button" class="button" value="' . $button_label . '"/></td>';
	  $subhtml .= '<td><div id="jot-subscribemessage"></div></td>';
          $subhtml .= '</tr>';
	  $subhtml .= '</table>';
                                  
	  return $subhtml; 
	  
     }
     
     
     public function process_generate_invite_html() {
           
            $formdata = $_POST['formdata'];            
                       
            $jot_groupid = isset($formdata['jot_grpinvdesc'])    ? sanitize_text_field($formdata['jot_grpinvdesc'])    : "";
            
            if ($jot_groupid == "") {
                 echo json_encode(array("html" => __("Group ID not set. Could not build HTML","jot-plugin")));            
                 die();
            }
            
            $group_id = isset($formdata['jot_groupid']) ? sanitize_text_field($formdata['jot_groupid'])  : "0000";
            $atts = array(
                        'jot_grpinvdesc'    => isset($formdata['jot_grpinvdesc'])    ? sanitize_text_field($formdata['jot_grpinvdesc'])    : "",
                        'jot_grpinvnametxt' => isset($formdata['jot_grpinvnametxt']) ? sanitize_text_field($formdata['jot_grpinvnametxt']) : "",
                        'jot_grpinvnumtxt'  => isset($formdata['jot_grpinvnumtxt'])  ? sanitize_text_field($formdata['jot_grpinvnumtxt'])  : "",
			'jot_grpgdprtxt'    => isset($formdata['jot_grpgdprtxt'])    ? sanitize_text_field($formdata['jot_grpgdprtxt'])    : "",
			'jot_grpgdprchk'    => isset($formdata['jot_grpgdprchk'])    ? true : false,
                        );
            
            $confirm_set = 0;
            
            $all_group_id = array($group_id);
            $subhtml = Joy_Of_Text_Plugin()->shortcodes->get_wrapped_jotform($group_id, $all_group_id, array(), $atts, $confirm_set);
            $subhtml = wp_kses($subhtml, Joy_Of_Text_Plugin()->settings->allowed_html_tags());
            
	    echo json_encode(array("html" => $subhtml));            
            die();
           
   }
    
    
} // end class