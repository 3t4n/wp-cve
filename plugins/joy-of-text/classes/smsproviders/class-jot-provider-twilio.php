<?php
/**
* Joy_Of_Text Twilio. Class for Twilio API functions
*
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



final class Joy_Of_Text_Plugin_Smsprovider {
 
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
 
    /**
     * Initializes the plugin 
     */
    function __construct() {
 
        add_filter( 'jot_get_settings_fields', array($this,'add_provider_fields'),10,2 );
           
    } // end constructor
 
    private static $_instance = null;
        
        public static function instance () {
            if ( is_null( self::$_instance ) )
                self::$_instance = new self();
            return self::$_instance;
        } // End instance()

    /**
    * Get account details from Twilio
    */
    public function getPhoneNumbers() {
            
            $TwilioAuth = get_option('jot-plugin-smsprovider');
            $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
            $sid = isset($TwilioAuth['jot-accountsid-' . $selected_provider]) ? $TwilioAuth['jot-accountsid-' . $selected_provider] : ""; 
                         
            try {                     
                //$client = new Services_Twilio($sid, $token);
                $data = array("PageSize" => 1000);
                $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/IncomingPhoneNumbers.json";
                $jot_response = Joy_Of_Text_Plugin()->messenger->call_curl($url,$data,'get');                
               
                $allnumbers['default'] = __("Select a number","jot-plugin");
                $numbers_json = json_decode($jot_response);
                
                if (isset($numbers_json)) {
                    if (isset($numbers_json->code)) {
                        // Error occurred
                         $errormessage = sprintf( __('A Twilio error occurred. "%s %s". Check your Twilio credentials.', 'jot-plugin'), $numbers_json->code, $numbers_json->message );
                         return array('message_code'=>$numbers_json->code, 'message_text'=> $errormessage, 'all_numbers'=>$allnumbers);
                    } else {
                    
                        foreach ($numbers_json->incoming_phone_numbers as $number) {
                            $allnumbers[$number->phone_number] = $number->phone_number;
                        }
                        return array('message_code'=>0, 'message_text'=> __('Success! You are connecting to Twilio.','jot-plugin'), 'all_numbers'=>$allnumbers);
                    }
                } 
            
            }  catch (Exception $e) {
                // Ignore error
            }
    }
    
    public function send_smsmessage($tonumber, $message) {

                         
            $TwilioAuth = get_option('jot-plugin-smsprovider');
            $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
            
            $sid = $TwilioAuth['jot-accountsid-' . $selected_provider]; 
            $fromnumber = $TwilioAuth['jot-phonenumbers-' . $selected_provider];
            
               
                                        
                $data = array (
                    'From' => $fromnumber,
                    'To' => $tonumber,
                    'Body' => stripcslashes($message)
                );
                $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";
                $jot_response = Joy_Of_Text_Plugin()->messenger->call_curl($url,$data,'post');
                
                // Process response
                $err_json = json_decode($jot_response);
                
                if (isset($err_json->code)) {                    
                    if ($err_json->code > 0) {                        
                        $error = array('send_message_number'=>$tonumber,'send_message_errorcode'=>$err_json->code, 'send_message_msg'=> $err_json->message);
                    }
                } else {                    
                    if ($err_json->error_code != null ){                        
                       $error = array('send_message_number'=>$tonumber,'send_message_errorcode'=>$err_json->code, 'send_message_msg'=> $err_json->message);
                    } else {                       
                       $error = array('send_message_number'=>$tonumber,'send_message_errorcode'=>'0', 'send_message_msg'=> __('SMS message sent successfully','jot-plugin'), 'send_details'=>$jot_response);
                    }
                }
           
            return $error; 
            
    }
    
     public function send_callmessage($tonumber, $message) {

            $error = 0;
            
            // Save message content for call type
            $messageid = uniqid(rand(), false);
            $error = Joy_Of_Text_Plugin()->messenger->save_call_message($messageid, $message);
            
            // If no save error 
            if ($error == 0) {             
                $TwilioAuth = get_option('jot-plugin-smsprovider');
                $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
                
                $sid = $TwilioAuth['jot-accountsid-' . $selected_provider]; 
                $token = $TwilioAuth['jot-authsid-' . $selected_provider];
                $fromnumber = $TwilioAuth['jot-phonenumbers-' . $selected_provider];
                
                $answer_machine_detection = Joy_Of_Text_Plugin()->settings->get_smsprovider_settings('jot-voice-answer-machine-detect');
                
                //$call_url = 'http://' . $_SERVER['SERVER_NAME'] . "?messageid=" . $messageid;
                $call_url = get_site_url() . "?messageid=" . $messageid;
                
                if ($answer_machine_detection == "") {
                    $data = array (
                        'From' => $fromnumber,
                        'To' => $tonumber,
                        'Url' => $call_url
                    );                
                } else {
                    $data = array (
                        'From' => $fromnumber,
                        'To' => $tonumber,
                        'MachineDetection' => 'DetectMessageEnd',
                        'Url' => $call_url
                    );
                }
                            
                
                // log message info to a file
                Joy_Of_Text_Plugin()->messenger->log_to_file(__METHOD__,"====== Message start : " . date('m/d/Y h:i:s a', time()) . " Messageid:" . $messageid . " Before call. From:" . $fromnumber. " To:" . $tonumber . " Callurl:" . $call_url );
                            
                
                $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Calls.json";
                $jot_response = Joy_Of_Text_Plugin()->messenger->call_curl($url,$data,'post');
                                
                // Process response
                $err_json = json_decode($jot_response);
                if (isset($err_json->code)) {                    
                    if ($err_json->code > 0) {                        
                        $error = array('send_message_number'=>$tonumber,'send_message_errorcode'=>$err_json->code, 'send_message_msg'=> $err_json->message);
                        // log error message to file
                        Joy_Of_Text_Plugin()->messenger->log_to_file(__METHOD__,"Messageid " . $messageid . " " . print_r($error,true));
                       
                    }
                } else {                    
                    if ($err_json->error_code != null ){                        
                       $error = array('send_message_number'=>$tonumber,'send_message_errorcode'=>$err_json->code, 'send_message_msg'=> $err_json->message);
                       // log error message to file
                       Joy_Of_Text_Plugin()->messenger->log_to_file(__METHOD__,"*** Call Error  - Messageid " . $messageid . " " . print_r($error,true));                       
                    } else {                       
                       $error = array('send_message_number'=>$tonumber,'send_message_errorcode'=>'0', 'send_message_msg'=> __('Voice call message sent successfully','jot-plugin'), 'send_details'=>$jot_response);
                    }
                }
            }                     
           return $error;  
    }
    
    
    public function get_callmessage() {
        
        global $wpdb;
      
        if (isset($_GET['messageid'])) {
            $messagecontent = Joy_Of_Text_Plugin()->messenger->get_saved_message(sanitize_text_field($_GET['messageid']));
        }
                     
        Joy_Of_Text_Plugin()->messenger->log_to_file(__METHOD__,"====== Message end : " . date('m/d/Y h:i:s a', time()) . " Messageid:" . sanitize_text_field($_GET['messageid']) . " messagecontent:" . $messagecontent);
       
        $voicesettings = get_option('jot-plugin-smsprovider');
        $voicegender = $voicesettings['jot-voice-gender'];
        $voiceaccent = $voicesettings['jot-voice-accent'];
       
        if (!isset($voicegender)) {
            Joy_Of_Text_Plugin()->settings->set_voice_preference('alice');          
            $voicesettings = get_option('jot-plugin-smsprovider');
            $voicegender = $voicesettings['jot-voice-gender'];           
        }
        if (!isset($voiceaccent)) {           
            Joy_Of_Text_Plugin()->settings->set_voiceaccent_preference('en-GB');
            $voicesettings = get_option('jot-plugin-smsprovider');          
            $voiceaccent = $voicesettings['jot-voice-accent'];
        }
              
       
        if (!empty($messagecontent)) {
                
           $xml  =  '<?xml version="1.0" encoding="UTF-8"?>';
           $xml .=  '<Response>';
           $xml .= '<Say voice="' . $voicegender . '" language="'. $voiceaccent . '">' . stripslashes($messagecontent) . '</Say>';
           $xml .= '</Response>';
           
           //Joy_Of_Text_Plugin()->messenger->log_to_file(__METHOD__,"XML >>>" . $xml . "<<<");                                   
           
           // Output Twiml 
           header('Content-type: text/xml'); 
           echo $xml;
        }
        
        Joy_Of_Text_Plugin()->messenger->delete_saved_message(sanitize_text_field($_GET['messageid']));
        die();
    }
    
       
    public function add_provider_fields( $settings_fields,$section ) {
        
        switch ( $section ) {
                case 'smsprovider':
                    
                    $settings_fields['jot-accountsid'] = array(
                        'name' => __( 'Twilio Account SID', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'section' => 'smsprovider',
                        'subform' => 'main',
                        'optional' => true,
                        'description' => __( 'Enter your Account SID number that you received from Twilio.', 'jot-plugin' )
                    );
                    $settings_fields['jot-authsid'] = array(
                        'name' => __( 'Twilio Auth Token', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'section' => 'smsprovider',
                        'subform' => 'main',
                        'optional' => true,
                        'description' => __( 'Enter your Auth token that you received from Twilio.', 'jot-plugin' )
                    );
                    $settings_fields['jot-phonenumbers'] = array(
                        'name' => __( 'Phone Numbers', 'jot-plugin' ),
                        'type' => 'select',
                        'default' => '',
                        'section' => 'smsprovider',
                        'subform' => 'main',                       
                        'description' => __( 'Select the Twilio number you wish to send your SMS messages from.', 'jot-plugin' )
                    );
                    $settings_fields['jot-accountstatus'] = array(
                        'name' => __( 'Twilio Account Status', 'jot-plugin' ),
                        'type' => 'textvalue',
                        'default' => '',
                        'section' => 'smsprovider',
                        'sectiontab'  => 'twiliosettings',
                        'subform' => 'main',                        
                        'description' => __( 'Twilio account status', 'jot-plugin' )
                    );
                    $settings_fields['jot-accountname'] = array(
                        'name' => __( 'Twilio Account Name', 'jot-plugin' ),
                        'type' => 'textvalue',
                        'default' => '',
                        'section' => 'smsprovider',
                        'sectiontab'  => 'twiliosettings',
                        'subform' => 'main',                        
                        'description' => __( 'Twilio account name', 'jot-plugin' )
                    );
                    $settings_fields['jot-accounttype'] = array(
                        'name' => __( 'Twilio Account Type', 'jot-plugin' ),
                        'type' => 'textvalue',
                        'default' => '',
                        'section' => 'smsprovider',
                        'sectiontab'  => 'twiliosettings',
                        'subform' => 'main',                        
                        'description' => __( 'Twilio account type', 'jot-plugin' )
                    );
                    $settings_fields['jot-accountbalance'] = array(
                        'name' => __( 'Twilio Account Balance', 'jot-plugin' ),
                        'type' => 'textvalue',
                        'default' => '',
                        'section' => 'smsprovider',
                        'sectiontab'  => 'twiliosettings',
                        'subform' => 'main',                        
                        'description' => __( 'Twilio account balance', 'jot-plugin' )
                    );
                break;               
        } 
        return $settings_fields;
    }
    
    /*
    *
    * Confirm that the given number is valid by calling Twilio's lookup function.
    *
    */
    public function verify_number($number) {
            
       $intnumber = "";
       $countrycode = "US";
       
       //jot-plugin-smsprovider[jot-smscountrycode]
       $currcc = Joy_Of_Text_Plugin()->settings->get_smsprovider_settings('jot-smscountrycode');
       if (isset($currcc)) {
            $countrycode = $currcc;
       }
            
       $data = array();
       
       $url = "https://lookups.twilio.com/v1/PhoneNumbers/" . $number . "?CountryCode=" . $countrycode;
       
       $twilio_response = Joy_Of_Text_Plugin()->messenger->call_curl($url,$data,'get');
       
       $twilio_json = json_decode($twilio_response);
       
       if (!empty($twilio_json->phone_number)) {
            $intnumber = $twilio_json->phone_number;
         
       }
       return $intnumber;
            
    }
    
    /**
    * Get account balance from Twilio
    */
    public function getAccountBalance() {
            
            
            $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
            $sid = Joy_Of_Text_Plugin()->settings->get_smsprovider_settings('jot-accountsid-' . $selected_provider);
                                   
            try {                     
                
                $data = array();
                $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Balance.json";
                $jot_response = Joy_Of_Text_Plugin()->messenger->call_curl($url,$data,'get');                
              
                return $jot_response;                 
            
            }  catch (Exception $e) {
                // Ignore error
            }
    }
    
    /**
    * Get account details from Twilio
    */
    public function getAccountDetails() {
            
            
            $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
            $sid = Joy_Of_Text_Plugin()->settings->get_smsprovider_settings('jot-accountsid-' . $selected_provider);
                                   
            try {                     
                
                $data = array();
                $url = "https://api.twilio.com/2010-04-01/Accounts/$sid.json";
                $jot_response = Joy_Of_Text_Plugin()->messenger->call_curl($url,$data,'get');
                
                return $jot_response;                 
            
            }  catch (Exception $e) {
                // Ignore error
            }
    }
    
    
} // end class
 