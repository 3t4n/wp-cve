<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    
    /**
    * Joy_Of_Text_Plugin_Settings Class
    */
    final class Joy_Of_Text_Plugin_Settings {
        
    
        private static $_instance = null;
        /**
        * Main Joy_Of_Text_Plugin_Settings Instance
        *
        * Ensures only one instance of Joy_Of_Text_Plugin_Settings is loaded or can be loaded.
        *
        * @since 1.0.0
        * @static
        * @return Main Joy_Of_Text_Plugin_Settings instance
        */
        public static function instance () {
            if ( is_null( self::$_instance ) )
                self::$_instance = new self();
            return self::$_instance;
        } // End instance()
        
        /**
        * Constructor function
        */

        public function __construct () {
            
            add_action( 'wp_ajax_process_refresh_languages', array( $this, 'process_refresh_languages' ) );
            
            add_filter( 'safe_style_css', array($this, 'allowed_style_attributes'));
            
        } // End __construct()
        
        
               
        
        /**
        * Render a field of a given type.
        * @param array $args The field parameters.
        * @return void
        */
        public function render_field ( $args ) {
            $html = '';
            
            if (!isset($args['type'])) {
                return "Invalid field definition";             
            }
            
            
            // Make sure we have some kind of default, if the key isn't set.
            if ( ! isset( $args['default'] ) ) {
                $args['default'] = '';
            }
            $method = 'render_field_' . $args['type'] ;
            if ( ! method_exists( $this, $method ) ) {
                $method = 'render_field_text';
            }
            // Construct the key.
            $key = Joy_Of_Text_Plugin()->token . '-' . $args['section'] . '[' . $args['id'] . ']';
            $method_output = $this->$method( $key, $args );
            if ( is_wp_error( $method_output ) ) {
                // if ( defined( 'WP_DEBUG' ) || true == constant( 'WP_DEBUG' ) ) print_r( $method_output ); // Add better error display.
            } else {
                $html .= $method_output;
            }
            // Output the description
            
            if ( isset( $args['description'] ) ) {
                $description  = '<p class="description">' . wp_kses_post( $args['description'] ) ;
                // Allow extra markup to be added after description. 
                if ( isset( $args['markup'] ) ) {                
                    $description .=  '  (' . $args['markup'] . ')' ;
                }
                if ( isset( $args['markuplink'] ) ) {                
                    $description .=   $args['markuplink'] ;
                }  
                $description .= '</p>' . "\n";
                               
                $html .= $description;
            }
            
            if (isset($args['display'])) {
                if ($args['display']=='echo') {
                    echo wp_kses($html, Joy_Of_Text_Plugin()->settings->allowed_html_tags());
                } else {
                    return $html;
                }
            } else {
                return $html;
            }
        } // End render_field()
        
        
        
        /**
        * Retrieve the settings fields details
        */
        public function get_settings_sections () {
            $settings_sections = array();
            
            // Define section tabs
            $settings_sections['smsprovider'] = array(
                    'tabname'    => __( 'Settings' , 'jot-plugin'),
                    'buttontext' => __( 'Save Settings' , 'jot-plugin')                    
            );
            $settings_sections['messages'] = array(
                    'tabname'    => __( 'Messages', 'jot-plugin' ),
                    'buttontext' => __( 'Send Messages', 'jot-plugin' )                    
            );
            $settings_sections['group-list'] = array(
                    'tabname'    => __( 'Group Manager', 'jot-plugin' ),
                    'buttontext' => __( 'Add Group', 'jot-plugin' )                    
            );
            //$settings_sections['scheduler-manager'] = array(
	    //	    'tabname'    => __( 'Schedule Manager', 'jot-plugin' )                    
	    //);
            $settings_sections['extensions'] = array(
		    'tabname'    => __( 'Go Pro', 'jot-plugin' )                    
	    ); 
                                  
            // Don't forget to add fields for the section in the get_settings_fields() function below
            return (array)apply_filters( 'jot-plugin-settings-sections', $settings_sections );
        } // End get_settings_sections()
        
                
        /**
        * Retrieve the settings fields details
        */
        public function get_settings_fields ( $section ) {
            if (!isset($subform)) {
                $subform = 'main';
            }
            $settings_fields = array();
            
            // Declare the default settings fields.
            switch ( $section ) {
                case 'smsprovider':
                    $settings_fields['jot-smsproviders'] = array(
                        'name' => __( 'SMS Providers', 'jot-plugin' ),
                        'type' => 'select',
                        'default' => '',
                        'section' => 'smsprovider',
                        'subform' => 'main',                        
                        'description' => __( 'Select your SMS provider.', 'jot-plugin' )
                    );
                    $settings_fields['jot-voice-gender'] = array(
                        'name' => __( 'Select voice', 'jot-plugin' ),
                        'type' => 'radio',
                        'default' => 'woman',
                        'section' => 'smsprovider',
                        'options' => array('man' => __('Male','jot-plugin'), 'alice' => __('Female','jot-plugin')),
                        'subform' => 'main',
                        'description' => __( 'Select the voice used for text-to-voice calls.', 'jot-plugin' )
                    );
                    $settings_fields['jot-voice-accent'] = array(
                        'name' => __( 'Select voice language', 'jot-plugin' ),
                        'type' => 'select',
                        'default' => '',
                        'section' => 'smsprovider',
                        'subform' => 'main',                       
                        'description' => __( 'Select the language for text-to-voice calls.', 'jot-plugin' )
                    );
                    $settings_fields['jot-voice-answer-machine-detect'] = array(
                        'name' => __( 'Answer Machine Detection?', 'jot-plugin' ),
                        'label' => __( 'Enable Answer Machine Detection?', 'jot-plugin' ),
                        'type' => 'checkbox',
                        'default' => 'false',
                        'section' => 'smsprovider',
                        'sectiontab'  => 'general',
                        'subform' => 'main',
                        'description' => __( 'Do you want to be enable Twilio\'s Answer Machine Detection service? Twilio charge for this service.', 'jot-plugin' )
                    );
                    $settings_fields['jot-smscountrycode'] = array(
                        'name' => __('Your country code', 'jot-plugin' ),
                        'type' => 'select',
                        'default' => '',
                        'section' => 'smsprovider',
                        'subform' => 'main',                        
                        'description' => __( 'Enter the country you are in so Twilio can convert your number into an international format.', 'jot-plugin' )
                    );
                           
          
                break;
                case 'messages':
                   
                    $settings_fields['jot-message-grouplist'] = array(
                        'name' => __( 'Recipients', 'jot-plugin' ),
                        'type' => 'optgroupselect',
                        'default' => '',
                        'section' => 'messages',
                        'subform' => 'main',                        
                        'description' => __( 'Select the recipients you wish to message. Click the Group Name to select all its members', 'jot-plugin' )
                    );
                    $tagurl = "<a href='http://www.getcloudsms.com/documentation/joy-text-supported-merge-tags/' target='_blank'>" . __("merge tags","jot-plugin") . "</a>";
                    $settings_fields['jot-message'] = array(
                        'name' => __( 'Enter your message', 'jot-plugin' ),
                        'type' => 'textarea',
                        'default' => '',
                        'section' => 'messages',
                        'subform' => 'main',
                        'maxlength' => 640,
                        'rows' =>5,
                        'cols' =>100,
                        'description' => sprintf( __("You can include the %s - %%name%%, %%number%% or %%lastpost%% into your message.","jot-plugin"),$tagurl),
                        'markup' => "<span id='jot-message-count-message'></span>"
                    );
                    $defsite = sprintf( '(from %s)',$_SERVER['SERVER_NAME'] );
                    $settings_fields['jot-message-suffix'] = array(
                        'name' => __( 'Message suffix', 'jot-plugin' ),
                        'type' => 'text',
                        'placeholder' => __("e.g.","jot-plugin") . " " . $defsite,
                        'section' => 'messages',
                        'subform' => 'main',
                        'description' => __( 'Suffix to append to the end of each message.', 'jot-plugin' )
                    );
                    $settings_fields['jot-message-type'] = array(
                        'name' => __( 'Send message as', 'jot-plugin' ),
                        'type' => 'radio',
                        'default' => 'jot-sms',
                        'section' => 'messages',
                        'options' => array('jot-sms' => 'SMS', 'jot-call' => 'A voice call'),
                        'subform' => 'main',
                        'description' => __( 'Send message as an SMS or as a text-to-voice call.', 'jot-plugin' )
                    );
                break;
                case 'group-list':
                    // Group details fields
                    $settings_fields['jot_groupnameupd'] = array(
                        'name' => __( 'Group name', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'section' => 'group-list',
                        'subform' => 'main',
                        'maxlength' => 40,
                        'description' => __( 'Enter your group name.', 'jot-plugin' )
                    );
                    $settings_fields['jot_groupdescupd'] = array(
                        'name' => __( 'Group description', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'section' => 'group-list',
                        'subform' => 'main',
                        'maxlength' => 60,
                        'description' => __( 'Enter your group description.', 'jot-plugin' )
                    );
                    // Group invite fields
                    $settings_fields['jot_grpinvdesc'] = array(
                        'name' => __( 'Text for description field', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => __('Please subscribe for SMS updates ', 'jot-plugin'),
                        'section' => 'group-list',
                        'subform' => 'main',
                        'maxlength' => 60                        
                    );
                    $settings_fields['jot_grpinvnametxt'] = array(
                        'name' => __( 'Text for name field', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => __('Enter your name : ', 'jot-plugin'),
                        'section' => 'group-list',
                        'subform' => 'main',
                        'maxlength' => 40                        
                    );
                    $settings_fields['jot_grpinvnumtxt'] = array(
                        'name' => __( 'Text for phone number field', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => __('Enter your phone number :', 'jot-plugin'),
                        'section' => 'group-list',
                        'subform' => 'main',
                        'maxlength' => 40                        
                    );
                     $settings_fields['jot_grpinvretchk'] = array(
                        'name' => __( 'Send welcome message', 'jot-plugin' ),
                        'label' => __( 'Send welcome message ?', 'jot-plugin' ),
                        'type' => 'checkbox',
                        'default' => 'true',
                        'section' => 'group-list',
                        'subform' => 'main',
                        'description' => __( 'Send each new subscriber a welcome text when they first subscribe. Remember you will be charged to send this message.', 'jot-plugin' )
                    );
                    $tagurl = "<a href='http://www.getcloudsms.com/documentation/joy-text-supported-merge-tags/' target='_blank'>" . __("merge tags","jot-plugin") . "</a>";
                    $settings_fields['jot_grpinvrettxt'] = array(
                        'name' => __( 'Enter welcome message', 'jot-plugin' ),
                        'type' => 'textarea',
                        'default' => __('Thank you for subscribing to our group.', 'jot-plugin'),
                        'section' => 'group-list',
                        'subform' => 'main',
                        'maxlength' => 160,
                        'rows' =>5,
                        'cols' =>100,
                        'description' => sprintf( __("You can include the %s - %%name%%, %%number%% or %%lastpost%% into your welcome message.","jot-plugin"),$tagurl),
                        'markup' => "<span id='jot-message-count-welcome'></span>"
                     
                    );
                    $settings_fields['jot_grpinvformtxt'] = array(
                        'name' => __( 'HTML for your invite form', 'jot-plugin' ),
                        'type' => 'textarea',
                        'default' => '',
			'readonly' => 'yes',
                        'section' => 'group-list',
                        'subform' => 'main',
                        'description' => __( 'Click to generate the HTML for the form. Place it on your site to display the invitation form.', 'jot-plugin' ),
			'markuplink' => "<p><a href='#' class='button' id='jot-generate-invite-html'>" . __("Generate HTML","jot-plugin"). "</a>"
                    );
                    $settings_fields['jot_grpinvshortcode'] = array(
                        'name' => __( 'Invite form shortcode', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'size' => 80,
                        'section' => 'group-list',
                        'subform' => 'main',
                        'description' => __( 'Alternatively, use this shortcode to create the invitation form.', 'jot-plugin' )
                    );
                    $settings_fields['jot_grpid'] = array(
                        'name' => '',
                        'type' => 'hidden',
                        'default' => '',
                        'section' => 'group-list',
                        'subform' => 'main' 
                    );
                    $settings_fields['jot_groupname'] = array(
                        'name' => __( 'Enter the group name', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'section' => 'group-list',
                        'subform' => 'add',
                        'maxlength' => 40,
                        'description' => __( 'Enter your group name.', 'jot-plugin' )
                    );
                    $settings_fields['jot_groupdesc'] = array(
                        'name' => __( 'Enter the group description', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'section' => 'group-list',
                        'subform' => 'add',
                        'maxlength' => 60,
                        'description' => __( 'Enter your group description.', 'jot-plugin' )
                    );
                    $settings_fields['jot_grpgdprtxt'] = array(
                        'name' => __( 'GDPR notice text', 'jot-plugin' ),
                        'type' => 'textarea',
                        'default' => __("This form collects your name and phone number so that we can send you SMS notifications about our services.","jot-plugin"),
			'section' => 'group-list',
                        'subform' => 'main',
                        'description' => sprintf(__( 'Enter the text of the GDPR notice. The notice can include %s', 'jot-plugin' ), $tagurl)
                    );
                    $settings_fields['jot_grpgdprchk'] = array(
                        'name' => __( 'Add GDPR message?', 'jot-plugin' ),
                        'label' => __( 'Add GDPR message to the JOTFORM?', 'jot-plugin' ),
                        'type' => 'checkbox',
                        'default' => 'false',
                        'section' => 'group-list',
                        'subform' => 'main',
                        'description' => __( 'Add the GDPR notice to the bottom the JOTFORM?', 'jot-plugin' )
                    );
                break;
                case 'extensions':                   
                    $settings_fields['jot-extensions-name'] = array(
                        'name' => __( 'Please enter your name', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'placeholder' => __("Your name","jot-plugin"),
                        'section' => 'extensions',
                        'subform' => 'main',
                        'maxlength' => 40  
                    );
                    $settings_fields['jot-extensions-email'] = array(
                        'name' => __( 'Please enter your email address', 'jot-plugin' ),
                        'type' => 'text',
                        'default' => '',
                        'placeholder' => __("Your email address","jot-plugin"),
                        'section' => 'extensions',
                        'subform' => 'main',
                        'maxlength' => 40       
                    );
                    $settings_fields['jot-extensions-message'] = array(
                        'name' => __( 'Enter your message', 'jot-plugin' ),
                        'type' => 'textarea',
                        'placeholder' => __("Your comments or suggestions.","jot-plugin"),
                        'default' => '',
                        'section' => 'extensions',
                        'subform' => 'main'
                    );
                    $settings_fields['jot-extensions-mail'] = array(
                        'name' => __( 'Subscribe to our newsletter?', 'jot-plugin' ),
                        'type' => 'checkbox',                       
                        'default' => 'true',
                        'section' => 'extensions',
                        'subform' => 'main'
                    );
                break;
                default:
                    $settings_fields = (array) apply_filters("jot_render_get_extension_fields",$section);                   
                break;
            }
           
            
            return (array)apply_filters( 'jot_get_settings_fields', $settings_fields, $section );
        } // End get_settings_fields()
        
        
        
        public function render_smsprovider_settings ($sections, $tab) {
            
                      
                $return_array = array();
                $return_array['message_code'] = '';
                $return_array['message_text'] = '';
               
                if (isset($_GET['section'])) {                 
                   $sectiontab = sanitize_text_field($_GET['section']);
                } else {
                   $sectiontab = "getstarted";
                }
                
                $html = $this->write_settings_navbar($tab,$sectiontab);
                $html .= $this->render_saved_notice();
                           
                $html .= "<table class=\"jot-formtab form-table\">\n";
                
                switch ( $sectiontab ) {
                    case 'getstarted'; 
                           $html .= $this->render_getstarted($sections, $tab);                          
                    break;
                    case 'twiliosettings'; 
                           $ret = $this->render_twiliosettings($sections, $tab);
                           $html .= $ret['html'];
                           $return_array['message_code'] = $ret['message_code'];
                           $return_array['message_text'] = $ret['message_text'];
                    break;
                    case 'licencekeys';
                           $html .= $this->render_licences($sections, $tab);     
                    break;
                    case 'inbound';
                           //$html .= $this->render_inbound($sections, $tab);                                
                    break;
                    case 'notification';
                           //$html .= $this->render_notifications($sections, $tab);       
                    break;
                    case 'general';
                           $html .= $this->render_general($sections, $tab);     
                    break;
                    default;
                           $html .= $this->render_getstarted($sections, $tab);  
                    break;
                }  
                
                $html .= "</table>";
    
                $return_array['html'] = $html;
                                                   
                return apply_filters( 'jot_render_smsprovider_settings',$return_array);

                    
        } // End render_groupdetails()
        
        public function write_settings_navbar($tab,$insection) {
            
            $sectionurl = admin_url( 'admin.php?page=jot-plugin&tab=' . $tab . '&section=');
            
            $sectionarray = array (
                'getstarted' => __('Get Started','jot-plugin'),
                'twiliosettings' => __('Twilio Settings','jot-plugin'),
                'licencekeys' => __('Licence Keys','jot-plugin'),
                'general' => __('General Settings','jot-plugin')                                  
                
            );
            
            $html =  "<div id='jot-navcontainer'>";
            $html .= "<ul id='jot-navlist'>";
            
            $sectionarray_size = count( $sectionarray );
            $counter = 1;
            foreach ($sectionarray as $key => $value) {
                if ($insection == $key) {
                    $html .= "<li><b>" . $value ."<b></li>";
                } else {
                    $html .= "<li><a href='" . $sectionurl . $key .  "'>" . $value . "</a></li>";                            
                }
                if ($counter < $sectionarray_size) {
                    $html .= "|";
                }
                $counter++;
            }       
            
            $html .= "</ul>";
            $html .= "</div>";
            
            return $html;
            
        }
        
        /**
        * Renders page for displaying SMS providersettings
        *
        * @return string HTML markup for the field.
        */
        public function render_getstarted($sections, $tab) {
                       
            $html = "<tr><td>";
            
            $html .= "<ul class='jot-getstarted'>";
            $twilio_url = "<a href='http://www.twilio.com' target='_blank'>" . __("Twilio.com","jot-plugin") . "</a>";
            
            $html .= "<li>" . sprintf( __("Step 1 - Get your account from %s","jot-plugin"), $twilio_url) ;
            
            $html .= "<ul class='jot-getstarted-nested'>";
            $html .= "<li>" . "<span class='getstarted-description'>" . __("To use this plugin you'll need a Twilio account.","jot-plugin") . "</span>" . "</li>";
            //$twilio_referral_url = "<a href='https://www.twilio.com/referral/vDJcVW' target='_blank'>" . __("referral","jot-plugin") .  "</a>";
            //$twilio_referral_tcs_url = "<a href='https://www.twilio.com/legal/referral-program' target='_blank'>" . __("here","jot-plugin") .  "</a>";
            //$html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("If you're new to Twilio, you can get <b>$10</b> added to your Twilio account, using this %s link.","jot-plugin"), $twilio_referral_url) . "</span>" . "</li>";
	    //$html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("The terms and conditions of Twilio's referral program are available %s.","jot-plugin"), $twilio_referral_tcs_url) . "</span>" . "</li>";
	    $html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("Register an account at %s, purchase a phone number and get your unique 'Twilio Account SID' and 'Twilio Auth Token'.","jot-plugin"), $twilio_url) . "</span>" . "</li>";
            
            $twilio_10dlc_url = "<a href='https://support.twilio.com/hc/en-us/articles/1260800720410-What-is-A2P-10DLC-' target='_blank'>" . __("Twilio's","jot-plugin") .  "</a>";
            $html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("If you are in the US or will be sending messages to US based numbers, then you may need to register your messaging campaigns. Check %s guidance to see if this applies to you.","jot-plugin"), $twilio_10dlc_url) . "</span>" . "</li>";
                       
            $url = "<a href='" . admin_url() .   "admin.php?page=jot-plugin&tab=smsprovider&section=twiliosettings' target='_blank'>" . __("Messaging-Settings-Twilio Settings","jot-plugin") .  "</a>";
            $html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("Go to %s and enter your 'Twilio Account SID' and 'Twilio Auth Token' and press the 'Save Settings' button.","jot-plugin"), $url) . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("Your Twilio number(s) should then be displayed, so select the number you want to send your messages from.","jot-plugin") . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("Select the country you are in. The plugin and Twilio need to know this, to check that the member phone numbers you've entered are valid.","jot-plugin") . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("Press the 'Save Settings' button and move to Step 2.","jot-plugin") . "</span>";
            $html .= "</ul>";
            $html .= "</li>";
            
            
            $html .= "<li>" .  __("Step 2 - If you have any product extensions, activate your licence keys","jot-plugin") ;
            $url = "<a href='" . admin_url() .   "admin.php?page=jot-plugin&tab=smsprovider&section=licencekeys' target='_blank'>" . __("Messaging-Settings-Licence Keys","jot-plugin") .  "</a>";
            $html .= "<ul class='jot-getstarted-nested'>";
            $html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("Go to %s to activate your licence key. This will enable automatic plugin updates through the Wordpress dashboard.","jot-plugin"), $url) . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("To activate the licences, enter your key(s) and press the 'Activate Licence' key.","jot-plugin") . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("Press the 'Save Settings' button and move to Step 3.","jot-plugin") . "</span>" . "</li>";
            $html .= "</ul>";
            $html .= "</li>";
            
            $html .= "<li>" .  __("Step 3 - Add some members to a group","jot-plugin");
            $get_oldest_group = Joy_Of_Text_Plugin()->messenger->get_oldest_groups();
            if (isset($get_oldest_group)) {
                $url = "<a href='" . admin_url() .   "admin.php?page=jot-plugin&tab=group-list&lastid=" . $get_oldest_group . "&subtab=jottabgroupmembers' target='_blank'>" . __("Group Manager-Member List","jot-plugin") .  "</a>" ;
            } else {
                $url = "<a href='" . admin_url() .   "admin.php?page=jot-plugin&tab=group-list' target='_blank'>" . __("Group Manager","jot-plugin") .  "</a>" ;
            }
            
            $html .= "<ul class='jot-getstarted-nested'>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("To add members, go to the Group Manager tab, then select the 'Member List' tab. Enter the new member's details and press the 'floppy disk' icon to save.","jot-plugin") . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("For example, click %s and add members to the group.","jot-plugin"), $url) . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("Once you've added some members, move to Step 4.","jot-plugin") . "</span>" . "</li>";
            $html .= "</ul>";
            $html .= "</li>";
            
            $html .= "<li>" .  __("Step 4 - Send some messages","jot-plugin") ;
            $html .= "<ul class='jot-getstarted-nested'>";
            $url = "<a href='" . admin_url() .   "admin.php?page=jot-plugin&tab=messages' target='_blank'>" . __("Messages","jot-plugin") .  "</a>";
            $html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("Go to the %s tab, select one or more members, enter the message text and press 'Send your messages'.","jot-plugin"), $url) . "</span>" . "</li>";
            $html .= "</ul>";
            $html .= "</li>";
            
            $html .= "<li>" .  __("Step 5 - Create a form.","jot-plugin") ;
            
            if (isset($get_oldest_group)) {
                $url = "<a href='" . admin_url() .   "admin.php?page=jot-plugin&tab=group-list&lastid=" . $get_oldest_group . "&subtab=jottabgroupinvite' target='_blank'>" . __("Group Manager-Group Invite","jot-plugin") .  "</a>" ;
            } else {
                $url = "<a href='" . admin_url() .   "admin.php?page=jot-plugin&tab=group-list' target='_blank'>" . __("Group Manager","jot-plugin") .  "</a>" ;
            }
            
            $html .= "<ul class='jot-getstarted-nested'>";
            $html .= "<li> " . "<span class='getstarted-description'>" . sprintf (__("Go to the %s tab to tailor your member subscription form.","jot-plugin"), $url) . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("You can create a subscription form for each group you add.","jot-plugin") . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("In the Group Invite tab, you'll see the HTML to create the form or you can use the provided shortcode. ","jot-plugin") . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("Add the HTML or shortcode to the appropriate page on your website.","jot-plugin") . "</span>" . "</li>";
            $html .= "<li> " . "<span class='getstarted-description'>" . __("When visitors to your site enter their name and number details into the form, you can choose to send them a 'welcome' message.","jot-plugin") . "</span>" . "</li>";
           
            $merge_url = "<a href='http://www.getcloudsms.com/documentation/joy-text-supported-merge-tags/' target='_blank'>" . __("merge tags","jot-plugin") .  "</a>" ;
            $html .= "<li> " . "<span class='getstarted-description'>" . sprintf(__("You can add %s and web links into 'welcome' message.","jot-plugin"),$merge_url) . "</span>" . "</li>";
           
            $html .= "</ul>";
            $html .= "</li>";
                       
            $html .= "</ul><br><br><br>";
            $url = "<a href='http://www.getcloudsms.com/lite-documentation/' target='_blank'>" . __("Lite Documentation","jot-plugin") .  "</a>";
            $html .= "<span class='getstarted-description'>" . sprintf( __("For detailed documentation please go to %s","jot-plugin"),$url) . "</span>";
            
            $html .= "</td></tr>";
            
            
            return $html;
        }
        
        public function render_twiliosettings($sections, $tab) {
            
            $fields = $this->get_settings_fields($tab);
            $smsdetails = get_option('jot-plugin-smsprovider');
            
            $html = "";
            
            // List all SMS providers
            $html .= $this->render_section_header(__("Twilio Settings","jot-plugin"));
                
            $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
                            
            // List all the SMS provider specific fields
            if ($selected_provider != 'default' && !empty($selected_provider)) {
                                        
                    foreach ($fields as $k=>$v) {
                        
                        if (isset($v['optional'])) {
                            if ($v['optional']) {
                                $currval = isset($smsdetails[$k . '-' . $selected_provider]) ? $smsdetails[$k . '-' . $selected_provider] : "";
                                $html .= $this->render_row($k, $k. '-' . $selected_provider,$currval,$tab);
                                if (get_option($k)) {
                                    $displaynums = true; 
                                }
                            }
                        }
                    }
                    // Render account status
		    $twilio_acc_details_json = Joy_Of_Text_Plugin()->currentsmsprovider->getAccountDetails();		   
		    $twilio_acc_details = json_decode($twilio_acc_details_json);                    
		    $twilio_acc_status = isset($twilio_acc_details->status) ? $twilio_acc_details->status : "";
		    $twilio_acc_name   = isset($twilio_acc_details->friendly_name) ? $twilio_acc_details->friendly_name : "";
		    $twilio_acc_type   = isset($twilio_acc_details->type) ? $twilio_acc_details->type : "";
		    $display_acc_status = (($twilio_acc_status != "" && !is_numeric($twilio_acc_status)) ? $twilio_acc_status : "<<Not set>>" );
		    $display_acc_name = (($twilio_acc_name != "") ? $twilio_acc_name  : "<<Not set>>" );
		    $display_acc_type = (($twilio_acc_type != "") ? $twilio_acc_type  : "<<Not set>>" );
		    $html .= $this->render_row('jot-accountstatus','',ucfirst($display_acc_status),$tab);
		    $html .= $this->render_row('jot-accountname','',ucfirst($display_acc_name),$tab);
		    $html .= $this->render_row('jot-accounttype','',ucfirst($display_acc_type),$tab);
                    
                    // Get account balance info
                    $twilio_acc_balance_json = Joy_Of_Text_Plugin()->currentsmsprovider->getAccountBalance();
                    $twilio_acc_balance = json_decode($twilio_acc_balance_json);
                    $display_balance = isset($twilio_acc_balance->balance) ? number_format((float)$twilio_acc_balance->balance, 2, '.', '') : "-";
                    $display_currency = isset($twilio_acc_balance->currency) ? $twilio_acc_balance->currency : "-";
                    $display_balance_currency = $display_balance . " " . $display_currency;
                    
                    // Render account balance		    
		    $html .= $this->render_row('jot-accountbalance','',$display_balance_currency,$tab);
                    
                    $smsprovider_numbers = Joy_Of_Text_Plugin()->currentsmsprovider->getPhoneNumbers();
                    $smsprovider_currnumber = $this->get_current_smsprovider_number();
                                
                    $html .= $this->render_row_multi('jot-phonenumbers','jot-phonenumbers-' . $selected_provider ,$smsprovider_numbers['all_numbers'], $smsprovider_currnumber, $tab);
                    $allcountrycodes = $this->get_countrycodes();
                      
                    // Set country code to US if not already set. 
                    $currcc = $this->get_smsprovider_settings('jot-smscountrycode');
                       
                    if (empty($currcc)) {
                        $this->set_smsprovider_settings('jot-smscountrycode','US');
                        $currcc = $this->get_smsprovider_settings('jot-smscountrycode');
                     }
                    $html .= $this->render_row_multi('jot-smscountrycode','',$allcountrycodes,$currcc,$tab);
                      
                   
            }
            
            $message_code = isset($smsprovider_numbers['message_code']) ? $smsprovider_numbers['message_code'] : "";
            $message_text = isset($smsprovider_numbers['message_text']) ? $smsprovider_numbers['message_text'] : "";            
                       
            if ($message_code == 0) {                
                if ( $this->get_current_smsprovider_number() == 'default') {
                    $message_code = -1;
                    $message_text = __( 'Please select your "from" number and save.', 'jot-plugin' );                   
                }
            }
                        
            $return_array = array("message_code"=> $message_code,
                         "message_text"=> $message_text,
                         'html'=> $html);
            
            return apply_filters( 'jot_render_smsprovider_settings', $return_array);
                
            
        }
        
        
        public function render_saved_notice() {
	    
	    $html = "";
	    $got_errors = false;
	    
	    $errors = get_settings_errors();
	    foreach ($errors as $error) {
		if ($error['setting'] == 'jot_settings_notice') {
		    $html .=  "<div class=\"notice notice-error is-dismissible\">";
		    $html .=  "<p>";
		    $html .=  "<strong>" . $error['message'] . "</strong>";
		    $html .=  "</p>";
		    $html .=  "</div>";
		    $got_errors = true;
		}
	    }
	   
	    if( isset($_GET['settings-updated']) && !$got_errors ) { 
                $html .=  "<div id=\"update_notice_message\" class=\"notice notice-success is-dismissible\">";
                $html .=  "<p>";
		$html .=  "<strong>" . __('Settings saved.','jot-plugin') . "</strong>";
		$html .=  "</p>";
                $html .=  "</div>";
		
	    }
	    return $html;
	    
	}
        
        
        public function render_licences($sections, $tab) {
            $html  = "<tr><th colspan=2>"; 
            $html .= __("Enter the licence keys for your ","jot-plugin") . "<a href='http://www.getcloudsms.com/products/' target='_BLANK'>" . __("extensions","jot-plugin") . "</a>";
            $html .= "</th></tr>";
            $html = apply_filters('jot_render_additional_licences',$html,$tab);
            return $html;
        }
        
        public function render_general($sections, $tab) {
            
            $html = "";
            
            // Voice Preferences
            $voicegender = $this->get_smsprovider_settings('jot-voice-gender');
            $voiceaccent = $this->get_smsprovider_settings('jot-voice-accent');
            
               
            if (empty($voicegender)) {
                $this->set_voice_preference('alice');          
                $voicesettings = get_option('jot-plugin-smsprovider');
                $voicegender = $voicesettings['jot-voice-gender'];           
            }
            if (empty($voiceaccent)) {           
                $this->set_voiceaccent_preference('en-GB');
                $voicesettings = get_option('jot-plugin-smsprovider');          
                $voiceaccent = $voicesettings['jot-voice-accent'];
            }
            $html .= $this->render_section_header(__("Voice preferences","jot-plugin")); 
            $html .= $this->render_row('jot-voice-gender','',$voicegender,$tab);
            $allaccents = $this->get_accents();
            $html .= $this->render_row_multi('jot-voice-accent','' ,$allaccents, $voiceaccent, $tab);
            $html .= $this->render_row('jot-voice-answer-machine-detect','',$this->get_smsprovider_settings('jot-voice-answer-machine-detect'),$tab);
                        
            return $html;
                    
        }
        
        
        /**
        * Gets the current selected SMS provider
        *
        */
        public function get_current_smsprovider() {

            if (isset($_GET['smsprovider'])) {
                $this->set_current_smsprovider(sanitize_text_field($_GET['smsprovider']));
            }
            $sms =  get_option('jot-plugin-smsprovider');
            return $sms['jot-smsproviders'];   
            
        }
        
        /**
        * Sets the current selected SMS provider if sent in URL
        *
        */
        public function set_current_smsprovider() {
            $smsprov =  get_option('jot-plugin-smsprovider');
            $smsprov['jot-smsproviders'] = sanitize_text_field($_GET['smsprovider']) ;   
            update_option('jot-plugin-smsprovider',$smsprov);            
        }
       
        /**
        * Gets the current selected SMS provider number
        *
        */
        public function get_current_smsprovider_number() {
            
            $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
            $sms =  get_option('jot-plugin-smsprovider');
            $jot_number =  isset($sms['jot-phonenumbers-' . $selected_provider]) ? $sms['jot-phonenumbers-' . $selected_provider] : "";   
            return $jot_number;
        }
        
        
        /**
        *
        * Gets the SMS provider settings for the given key
        * 
        */
        public function get_smsprovider_settings($variable) {
          
            $settings =  get_option('jot-plugin-smsprovider');
            if (isset($settings[$variable])) {
                return $settings[$variable];            
            } else {
                return "";
            }
        }
        
        /**
        *    
        * Sets the SMS provider settings for the given key
        *
        */
        public function set_smsprovider_settings($variable,$value) {
           
            $settings =  get_option('jot-plugin-smsprovider');
            $settings[$variable] = $value ;   
            update_option('jot-plugin-smsprovider',$settings);
            
        }
        
        /*
         *
         * Set and Get the voice preference
         *
         */
        public function set_voice_preference($value) {
            $smsdetails =  get_option('jot-plugin-smsprovider');
            $smsdetails['jot-voice-gender'] = $value ;   
            update_option('jot-plugin-smsprovider',$smsdetails);  
        }
        
        public function set_voiceaccent_preference($value) {            
            $smsdetails =  get_option('jot-plugin-smsprovider');
            $smsdetails['jot-voice-accent'] = $value ;   
            update_option('jot-plugin-smsprovider',$smsdetails);  
        }
        
       
        /*
         *
         * Get accents available for 'man' or 'alice' 
         *
         */
        public function get_accents() {
            
            $voicesettings = get_option('jot-plugin-smsprovider');          
            $voicegender = $voicesettings['jot-voice-gender'];
            
            switch ( $voicegender ) {
                case 'man'; 
                    $allaccents = array('en' => __('English','jot-plugin'),
                                        'en-GB' => __('English, UK','jot-plugin'),
                                        'es' => __('Spanish','jot-plugin'),
                                        'fr' => __('French','jot-plugin'),
                                        'de' => __('German','jot-plugin'),
                                        'it' => __('Italian','jot-plugin')
                                        );   
                break;
                case 'alice';
                    $allaccents = array('da-DK'=> __('Danish, Denmark','jot-plugin'),	
                                        'de-DE'=> __('German, Germany','jot-plugin'),	
                                        'en-AU'=> __('English, Australia','jot-plugin'),	
                                        'en-CA'=> __('English, Canada','jot-plugin'),	
                                        'en-GB'=> __('English, UK','jot-plugin'),	
                                        'en-IN'=> __('English, India','jot-plugin'),	
                                        'en-US'=> __('English, United States','jot-plugin'),	
                                        'ca-ES'=> __('Catalan, Spain','jot-plugin'),	
                                        'es-ES'=> __('Spanish, Spain','jot-plugin'),	
                                        'es-MX'=> __('Spanish, Mexico','jot-plugin'),	
                                        'fi-FI'=> __('Finnish, Finland','jot-plugin'),	
                                        'fr-CA'=> __('French, Canada','jot-plugin'),	
                                        'fr-FR'=> __('French, France','jot-plugin'),	
                                        'it-IT'=> __('Italian, Italy','jot-plugin'),	
                                        'ja-JP'=> __('Japanese, Japan','jot-plugin'),	
                                        'ko-KR'=> __('Korean, Korea','jot-plugin'),	
                                        'nb-NO'=> __('Norwegian, Norway','jot-plugin'),	
                                        'nl-NL'=> __('Dutch, Netherlands','jot-plugin'),	
                                        'pl-PL'=> __('Polish-Poland','jot-plugin'),	
                                        'pt-BR'=> __('Portuguese, Brazil','jot-plugin'),	
                                        'pt-PT'=> __('Portuguese, Portugal','jot-plugin'),	
                                        'ru-RU'=> __('Russian, Russia','jot-plugin'),	
                                        'sv-SE'=> __('Swedish, Sweden','jot-plugin'),	
                                        'zh-CN'=> __('Chinese (Mandarin)','jot-plugin'),	
                                        'zh-HK'=> __('Chinese (Cantonese)','jot-plugin'),	
                                        'zh-TW'=> __('Chinese (Taiwanese Mandarin)','jot-plugin')
                                       );
                            
                break;
                default;
                       $allaccents = array('en-GB' => 'English, UK');  
                break;
             }
             return $allaccents;
        }
        
        /*
         *
         * Get the accents for a frontend Ajax request
         *
         */
        public function  process_refresh_languages () {
            $formdata = $_POST['formdata'];    
            $jot_voice_gender = sanitize_text_field($formdata['jot_voice_gender']);
            $this->set_voice_preference($jot_voice_gender);
            $this->set_voiceaccent_preference('en-GB');
            
            $allaccents = $this->get_accents();
            echo json_encode($allaccents);
            wp_die();
            
        }
        
        /*
         *
         * Get country codes
         *
         */
        public function get_countrycodes() {
            
            
                    $countrycodes = array(  'AF' => __('Afghanistan - (AF)','jot-plugin') ,
                                            'AX' => __('Aland Islands - (AX)','jot-plugin') ,
                                            'AL' => __('Albania - (AL)','jot-plugin') ,
                                            'DZ' => __('Algeria - (DZ)','jot-plugin') ,
                                            'AS' => __('American Samoa - (AS)','jot-plugin') ,
                                            'AD' => __('Andorra - (AD)','jot-plugin') ,
                                            'AO' => __('Angola - (AO)','jot-plugin') ,
                                            'AI' => __('Anguilla - (AI)','jot-plugin') ,
                                            'AQ' => __('Antarctica - (AQ)','jot-plugin') ,
                                            'AG' => __('Antigua and Barbuda - (AG)','jot-plugin') ,
                                            'AR' => __('Argentina - (AR)','jot-plugin') ,
                                            'AM' => __('Armenia - (AM)','jot-plugin') ,
                                            'AW' => __('Aruba - (AW)','jot-plugin') ,
                                            'AU' => __('Australia - (AU)','jot-plugin') ,
                                            'AT' => __('Austria - (AT)','jot-plugin') ,
                                            'AZ' => __('Azerbaijan - (AZ)','jot-plugin') ,
                                            'BS' => __('Bahamas - (BS)','jot-plugin') ,
                                            'BH' => __('Bahrain - (BH)','jot-plugin') ,
                                            'BD' => __('Bangladesh - (BD)','jot-plugin') ,
                                            'BB' => __('Barbados - (BB)','jot-plugin') ,
                                            'BY' => __('Belarus - (BY)','jot-plugin') ,
                                            'BE' => __('Belgium - (BE)','jot-plugin') ,
                                            'BZ' => __('Belize - (BZ)','jot-plugin') ,
                                            'BJ' => __('Benin - (BJ)','jot-plugin') ,
                                            'BM' => __('Bermuda - (BM)','jot-plugin') ,
                                            'BT' => __('Bhutan - (BT)','jot-plugin') ,
                                            'BO' => __('Bolivia (Plurinational State of) - (BO)','jot-plugin') ,
                                            'BQ' => __('Bonaire, Sint Eustatius and Saba - (BQ)','jot-plugin') ,
                                            'BA' => __('Bosnia and Herzegovina - (BA)','jot-plugin') ,
                                            'BW' => __('Botswana - (BW)','jot-plugin') ,
                                            'BV' => __('Bouvet Island - (BV)','jot-plugin') ,
                                            'BR' => __('Brazil - (BR)','jot-plugin') ,
                                            'IO' => __('British Indian Ocean Territory - (IO)','jot-plugin') ,
                                            'BN' => __('Brunei Darussalam - (BN)','jot-plugin') ,
                                            'BG' => __('Bulgaria - (BG)','jot-plugin') ,
                                            'BF' => __('Burkina Faso - (BF)','jot-plugin') ,
                                            'BI' => __('Burundi - (BI)','jot-plugin') ,
                                            'KH' => __('Cambodia - (KH)','jot-plugin') ,
                                            'CM' => __('Cameroon - (CM)','jot-plugin') ,
                                            'CA' => __('Canada - (CA)','jot-plugin') ,
                                            'CV' => __('Cabo Verde - (CV)','jot-plugin') ,
                                            'KY' => __('Cayman Islands - (KY)','jot-plugin') ,
                                            'CF' => __('Central African Republic - (CF)','jot-plugin') ,
                                            'TD' => __('Chad - (TD)','jot-plugin') ,
                                            'CL' => __('Chile - (CL)','jot-plugin') ,
                                            'CN' => __('China - (CN)','jot-plugin') ,
                                            'CX' => __('Christmas Island - (CX)','jot-plugin') ,
                                            'CC' => __('Cocos (Keeling) Islands - (CC)','jot-plugin') ,
                                            'CO' => __('Colombia - (CO)','jot-plugin') ,
                                            'KM' => __('Comoros - (KM)','jot-plugin') ,
                                            'CG' => __('Congo - (CG)','jot-plugin') ,
                                            'CD' => __('Congo (Democratic Republic of the) - (CD)','jot-plugin') ,
                                            'CK' => __('Cook Islands - (CK)','jot-plugin') ,
                                            'CR' => __('Costa Rica - (CR)','jot-plugin') ,
                                            'CI' => __('Cote d\'Ivoire - (CI)','jot-plugin') ,
                                            'HR' => __('Croatia - (HR)','jot-plugin') ,
                                            'CU' => __('Cuba - (CU)','jot-plugin') ,
                                            'CW' => __('Curacao - (CW)','jot-plugin') ,
                                            'CY' => __('Cyprus - (CY)','jot-plugin') ,
                                            'CZ' => __('Czech Republic - (CZ)','jot-plugin') ,
                                            'DK' => __('Denmark - (DK)','jot-plugin') ,
                                            'DJ' => __('Djibouti - (DJ)','jot-plugin') ,
                                            'DM' => __('Dominica - (DM)','jot-plugin') ,
                                            'DO' => __('Dominican Republic - (DO)','jot-plugin') ,
                                            'EC' => __('Ecuador - (EC)','jot-plugin') ,
                                            'EG' => __('Egypt - (EG)','jot-plugin') ,
                                            'SV' => __('El Salvador - (SV)','jot-plugin') ,
                                            'GQ' => __('Equatorial Guinea - (GQ)','jot-plugin') ,
                                            'ER' => __('Eritrea - (ER)','jot-plugin') ,
                                            'EE' => __('Estonia - (EE)','jot-plugin') ,
                                            'ET' => __('Ethiopia - (ET)','jot-plugin') ,
                                            'FK' => __('Falkland Islands (Malvinas) - (FK)','jot-plugin') ,
                                            'FO' => __('Faroe Islands - (FO)','jot-plugin') ,
                                            'FJ' => __('Fiji - (FJ)','jot-plugin') ,
                                            'FI' => __('Finland - (FI)','jot-plugin') ,
                                            'FR' => __('France - (FR)','jot-plugin') ,
                                            'GF' => __('French Guiana - (GF)','jot-plugin') ,
                                            'PF' => __('French Polynesia - (PF)','jot-plugin') ,
                                            'TF' => __('French Southern Territories - (TF)','jot-plugin') ,
                                            'GA' => __('Gabon - (GA)','jot-plugin') ,
                                            'GM' => __('Gambia - (GM)','jot-plugin') ,
                                            'GE' => __('Georgia - (GE)','jot-plugin') ,
                                            'DE' => __('Germany - (DE)','jot-plugin') ,
                                            'GH' => __('Ghana - (GH)','jot-plugin') ,
                                            'GI' => __('Gibraltar - (GI)','jot-plugin') ,
                                            'GR' => __('Greece - (GR)','jot-plugin') ,
                                            'GL' => __('Greenland - (GL)','jot-plugin') ,
                                            'GD' => __('Grenada - (GD)','jot-plugin') ,
                                            'GP' => __('Guadeloupe - (GP)','jot-plugin') ,
                                            'GU' => __('Guam - (GU)','jot-plugin') ,
                                            'GT' => __('Guatemala - (GT)','jot-plugin') ,
                                            'GG' => __('Guernsey - (GG)','jot-plugin') ,
                                            'GN' => __('Guinea - (GN)','jot-plugin') ,
                                            'GW' => __('Guinea-Bissau - (GW)','jot-plugin') ,
                                            'GY' => __('Guyana - (GY)','jot-plugin') ,
                                            'HT' => __('Haiti - (HT)','jot-plugin') ,
                                            'HM' => __('Heard Island and McDonald Islands - (HM)','jot-plugin') ,
                                            'VA' => __('Holy See - (VA)','jot-plugin') ,
                                            'HN' => __('Honduras - (HN)','jot-plugin') ,
                                            'HK' => __('Hong Kong - (HK)','jot-plugin') ,
                                            'HU' => __('Hungary - (HU)','jot-plugin') ,
                                            'IS' => __('Iceland - (IS)','jot-plugin') ,
                                            'IN' => __('India - (IN)','jot-plugin') ,
                                            'ID' => __('Indonesia - (ID)','jot-plugin') ,
                                            'IR' => __('Iran (Islamic Republic of) - (IR)','jot-plugin') ,
                                            'IQ' => __('Iraq - (IQ)','jot-plugin') ,
                                            'IE' => __('Ireland - (IE)','jot-plugin') ,
                                            'IM' => __('Isle of Man - (IM)','jot-plugin') ,
                                            'IL' => __('Israel - (IL)','jot-plugin') ,
                                            'IT' => __('Italy - (IT)','jot-plugin') ,
                                            'JM' => __('Jamaica - (JM)','jot-plugin') ,
                                            'JP' => __('Japan - (JP)','jot-plugin') ,
                                            'JE' => __('Jersey - (JE)','jot-plugin') ,
                                            'JO' => __('Jordan - (JO)','jot-plugin') ,
                                            'KZ' => __('Kazakhstan - (KZ)','jot-plugin') ,
                                            'KE' => __('Kenya - (KE)','jot-plugin') ,
                                            'KI' => __('Kiribati - (KI)','jot-plugin') ,
                                            'KP' => __('Korea (Democratic People\'s Republic of) - (KP)','jot-plugin') ,
                                            'KR' => __('Korea (Republic of) - (KR)','jot-plugin') ,
                                            'KW' => __('Kuwait - (KW)','jot-plugin') ,
                                            'KG' => __('Kyrgyzstan - (KG)','jot-plugin') ,
                                            'LA' => __('Lao People\'s Democratic Republic - (LA)','jot-plugin') ,
                                            'LV' => __('Latvia - (LV)','jot-plugin') ,
                                            'LB' => __('Lebanon - (LB)','jot-plugin') ,
                                            'LS' => __('Lesotho - (LS)','jot-plugin') ,
                                            'LR' => __('Liberia - (LR)','jot-plugin') ,
                                            'LY' => __('Libya - (LY)','jot-plugin') ,
                                            'LI' => __('Liechtenstein - (LI)','jot-plugin') ,
                                            'LT' => __('Lithuania - (LT)','jot-plugin') ,
                                            'LU' => __('Luxembourg - (LU)','jot-plugin') ,
                                            'MO' => __('Macao - (MO)','jot-plugin') ,
                                            'MK' => __('Macedonia (the former Yugoslav Republic of) - (MK)','jot-plugin') ,
                                            'MG' => __('Madagascar - (MG)','jot-plugin') ,
                                            'MW' => __('Malawi - (MW)','jot-plugin') ,
                                            'MY' => __('Malaysia - (MY)','jot-plugin') ,
                                            'MV' => __('Maldives - (MV)','jot-plugin') ,
                                            'ML' => __('Mali - (ML)','jot-plugin') ,
                                            'MT' => __('Malta - (MT)','jot-plugin') ,
                                            'MH' => __('Marshall Islands - (MH)','jot-plugin') ,
                                            'MQ' => __('Martinique - (MQ)','jot-plugin') ,
                                            'MR' => __('Mauritania - (MR)','jot-plugin') ,
                                            'MU' => __('Mauritius - (MU)','jot-plugin') ,
                                            'YT' => __('Mayotte - (YT)','jot-plugin') ,
                                            'MX' => __('Mexico - (MX)','jot-plugin') ,
                                            'FM' => __('Micronesia (Federated States of) - (FM)','jot-plugin') ,
                                            'MD' => __('Moldova (Republic of) - (MD)','jot-plugin') ,
                                            'MC' => __('Monaco - (MC)','jot-plugin') ,
                                            'MN' => __('Mongolia - (MN)','jot-plugin') ,
                                            'ME' => __('Montenegro - (ME)','jot-plugin') ,
                                            'MS' => __('Montserrat - (MS)','jot-plugin') ,
                                            'MA' => __('Morocco - (MA)','jot-plugin') ,
                                            'MZ' => __('Mozambique - (MZ)','jot-plugin') ,
                                            'MM' => __('Myanmar - (MM)','jot-plugin') ,
                                            'NA' => __('Namibia - (NA)','jot-plugin') ,
                                            'NR' => __('Nauru - (NR)','jot-plugin') ,
                                            'NP' => __('Nepal - (NP)','jot-plugin') ,
                                            'NL' => __('Netherlands - (NL)','jot-plugin') ,
                                            'NC' => __('New Caledonia - (NC)','jot-plugin') ,
                                            'NZ' => __('New Zealand - (NZ)','jot-plugin') ,
                                            'NI' => __('Nicaragua - (NI)','jot-plugin') ,
                                            'NE' => __('Niger - (NE)','jot-plugin') ,
                                            'NG' => __('Nigeria - (NG)','jot-plugin') ,
                                            'NU' => __('Niue - (NU)','jot-plugin') ,
                                            'NF' => __('Norfolk Island - (NF)','jot-plugin') ,
                                            'MP' => __('Northern Mariana Islands - (MP)','jot-plugin') ,
                                            'NO' => __('Norway - (NO)','jot-plugin') ,
                                            'OM' => __('Oman - (OM)','jot-plugin') ,
                                            'PK' => __('Pakistan - (PK)','jot-plugin') ,
                                            'PW' => __('Palau - (PW)','jot-plugin') ,
                                            'PS' => __('Palestine, State of - (PS)','jot-plugin') ,
                                            'PA' => __('Panama - (PA)','jot-plugin') ,
                                            'PG' => __('Papua New Guinea - (PG)','jot-plugin') ,
                                            'PY' => __('Paraguay - (PY)','jot-plugin') ,
                                            'PE' => __('Peru - (PE)','jot-plugin') ,
                                            'PH' => __('Philippines - (PH)','jot-plugin') ,
                                            'PN' => __('Pitcairn - (PN)','jot-plugin') ,
                                            'PL' => __('Poland - (PL)','jot-plugin') ,
                                            'PT' => __('Portugal - (PT)','jot-plugin') ,
                                            'PR' => __('Puerto Rico - (PR)','jot-plugin') ,
                                            'QA' => __('Qatar - (QA)','jot-plugin') ,
                                            'RE' => __('Reunion - (RE)','jot-plugin') ,
                                            'RO' => __('Romania - (RO)','jot-plugin') ,
                                            'RU' => __('Russian Federation - (RU)','jot-plugin') ,
                                            'RW' => __('Rwanda - (RW)','jot-plugin') ,
                                            'BL' => __('Saint Barthelemy - (BL)','jot-plugin') ,
                                            'SH' => __('Saint Helena, Ascension and Tristan da Cunha - (SH)','jot-plugin') ,
                                            'KN' => __('Saint Kitts and Nevis - (KN)','jot-plugin') ,
                                            'LC' => __('Saint Lucia - (LC)','jot-plugin') ,
                                            'MF' => __('Saint Martin (French part) - (MF)','jot-plugin') ,
                                            'PM' => __('Saint Pierre and Miquelon - (PM)','jot-plugin') ,
                                            'VC' => __('Saint Vincent and the Grenadines - (VC)','jot-plugin') ,
                                            'WS' => __('Samoa - (WS)','jot-plugin') ,
                                            'SM' => __('San Marino - (SM)','jot-plugin') ,
                                            'ST' => __('Sao Tome and Principe - (ST)','jot-plugin') ,
                                            'SA' => __('Saudi Arabia - (SA)','jot-plugin') ,
                                            'SN' => __('Senegal - (SN)','jot-plugin') ,
                                            'RS' => __('Serbia - (RS)','jot-plugin') ,
                                            'SC' => __('Seychelles - (SC)','jot-plugin') ,
                                            'SL' => __('Sierra Leone - (SL)','jot-plugin') ,
                                            'SG' => __('Singapore - (SG)','jot-plugin') ,
                                            'SX' => __('Sint Maarten (Dutch part) - (SX)','jot-plugin') ,
                                            'SK' => __('Slovakia - (SK)','jot-plugin') ,
                                            'SI' => __('Slovenia - (SI)','jot-plugin') ,
                                            'SB' => __('Solomon Islands - (SB)','jot-plugin') ,
                                            'SO' => __('Somalia - (SO)','jot-plugin') ,
                                            'ZA' => __('South Africa - (ZA)','jot-plugin') ,
                                            'GS' => __('South Georgia and the South Sandwich Islands - (GS)','jot-plugin') ,
                                            'SS' => __('South Sudan - (SS)','jot-plugin') ,
                                            'ES' => __('Spain - (ES)','jot-plugin') ,
                                            'LK' => __('Sri Lanka - (LK)','jot-plugin') ,
                                            'SD' => __('Sudan - (SD)','jot-plugin') ,
                                            'SR' => __('Suriname - (SR)','jot-plugin') ,
                                            'SJ' => __('Svalbard and Jan Mayen - (SJ)','jot-plugin') ,
                                            'SZ' => __('Swaziland - (SZ)','jot-plugin') ,
                                            'SE' => __('Sweden - (SE)','jot-plugin') ,
                                            'CH' => __('Switzerland - (CH)','jot-plugin') ,
                                            'SY' => __('Syrian Arab Republic - (SY)','jot-plugin') ,
                                            'TW' => __('Taiwan, Province of China - (TW)','jot-plugin') ,
                                            'TJ' => __('Tajikistan - (TJ)','jot-plugin') ,
                                            'TZ' => __('Tanzania, United Republic of - (TZ)','jot-plugin') ,
                                            'TH' => __('Thailand - (TH)','jot-plugin') ,
                                            'TL' => __('Timor-Leste - (TL)','jot-plugin') ,
                                            'TG' => __('Togo - (TG)','jot-plugin') ,
                                            'TK' => __('Tokelau - (TK)','jot-plugin') ,
                                            'TO' => __('Tonga - (TO)','jot-plugin') ,
                                            'TT' => __('Trinidad and Tobago - (TT)','jot-plugin') ,
                                            'TN' => __('Tunisia - (TN)','jot-plugin') ,
                                            'TR' => __('Turkey - (TR)','jot-plugin') ,
                                            'TM' => __('Turkmenistan - (TM)','jot-plugin') ,
                                            'TC' => __('Turks and Caicos Islands - (TC)','jot-plugin') ,
                                            'TV' => __('Tuvalu - (TV)','jot-plugin') ,
                                            'UG' => __('Uganda - (UG)','jot-plugin') ,
                                            'UA' => __('Ukraine - (UA)','jot-plugin') ,
                                            'AE' => __('United Arab Emirates - (AE)','jot-plugin') ,
                                            'GB' => __('United Kingdom of Great Britain and Northern Ireland - (GB)','jot-plugin') ,
                                            'US' => __('United States of America - (US)','jot-plugin') ,
                                            'UM' => __('United States Minor Outlying Islands - (UM)','jot-plugin') ,
                                            'UY' => __('Uruguay - (UY)','jot-plugin') ,
                                            'UZ' => __('Uzbekistan - (UZ)','jot-plugin') ,
                                            'VU' => __('Vanuatu - (VU)','jot-plugin') ,
                                            'VE' => __('Venezuela (Bolivarian Republic of) - (VE)','jot-plugin') ,
                                            'VN' => __('Viet Nam - (VN)','jot-plugin') ,
                                            'VG' => __('Virgin Islands (British) - (VG)','jot-plugin') ,
                                            'VI' => __('Virgin Islands (U.S.) - (VI)','jot-plugin') ,
                                            'WF' => __('Wallis and Futuna - (WF)','jot-plugin') ,
                                            'EH' => __('Western Sahara - (EH)','jot-plugin') ,
                                            'YE' => __('Yemen - (YE)','jot-plugin') ,
                                            'ZM' => __('Zambia - (ZM)','jot-plugin') ,
                                            'ZW' => __('Zimbabwe - (ZW)','jot-plugin') 
                                       );
                            
            
             return $countrycodes;
        }
        
        /**
        * Renders page for displaying Message panel
        *
        * @return string HTML markup for the field.
        */
        public function render_message_panel ($sections, $tab,$usage = null,$args= null) {            
            
            $smsmessage =  get_option('jot-plugin-messages');
                        
            if($args) {
                // Values passed into function through $args
                $message_body = $args['jot-message'];
                $message_suffix = $args['jot-message-suffix'];
                               
            } else {
                // Values to be retrieved from stored options
                $message_body = $smsmessage['jot-message'];
                $message_suffix = $smsmessage['jot-message-suffix'];
            }
             
            $html = "<table class=\"jot-formtab form-table\">\n";
            
            if ($usage == 'schedplanner') {
                // Group list will be added by Sched Extension
            } else {
                // Show active number
                $current_selected_number = $this->get_current_smsprovider_number();
                $html .= "<tr>";
                $html .= "<th>";
                $html .= __("Current Twilio number:","jot-plugin");
                $html .= "</th><td>";
                if ($current_selected_number == "default") {
                   $html .= __("None.","jot-plugin");
                } else {
                   $html .= $current_selected_number;
                }                   
                $html .= "</td>";
                $html .= "</tr>";  
                $html .= $this->render_row_multi('jot-message-grouplist','',$this->get_groups(),'',$tab);
            }
            
            
            $html .= $this->render_row('jot-message','',$message_body,$tab);
            $html .= $this->render_row('jot-message-suffix','',$message_suffix,$tab);
            $html .= $this->render_row('jot-message-type','','',$tab);
            
            // Add additional fields on Message panel
            $html = apply_filters("jot_render_extension_message_fields",$html,$tab);

            $html .= "</table>";
           
            
            return apply_filters( 'jot_render_message_panel', $html);
        } // End render_message_panel()
        
               
        
        
        /**
        * Renders page for displaying Add Group panel
        *
        * @return string HTML markup for the field.
        */
        public function render_groupadd($sections, $tab) {
        
            $html .= "<table class=\"jot-formtab form-table\">\n";
            
            $html .= $this->render_row('jot_groupname','','',$tab);
            $html .= $this->render_row('jot_groupdesc','','',$tab);
                                    
            $html .= "</table>";
           
            
            return apply_filters('jot_render_groupadd',$html);
        } // End render_groupdetails()
        
        
        
        
        /**
        * Renders the details of a selected group
        *
        * @return string HTML markup for the field.
        */
        public function render_groupdetails ($sections, $tab, $lastid) {
        
            //Get group list from database.
            global $wpdb;
            $table = $wpdb->prefix."jot_groups";
            $sql = " SELECT jot_groupid, jot_groupname,jot_groupdesc, jot_ts " .
                   " FROM " . $table .
                   " WHERE jot_groupid = %s";
            $sqlprep = $wpdb->prepare($sql, $lastid);
            $groupdetails = $wpdb->get_row( $sqlprep );
            
            $jot_groupname = isset($groupdetails->jot_groupname) ? stripslashes($groupdetails->jot_groupname) : __("Group name not found","jotplugin");
            $jot_groupdesc = isset($groupdetails->jot_groupdesc) ? stripslashes($groupdetails->jot_groupdesc) : __("Group description not found","jotplugin");
            
            if (isset($_GET['subtab'])) {
                if (sanitize_text_field($_GET['subtab']) == 'jottabgroupdetails') {
                    $style = "style='display:block'";
                } else {
                    $style = "style='display:none'";
                }
            } else {
                $style = "style='display:block'";
            }
            
            $html  = "<div id='jottabgroupdetails' $style>";          
            $html .= "<h3> Group Details - <span id='jot_grptitle'>" . $jot_groupname . "</span></h3>";
            $html .= "<form id='jot-group-details-form' action='' method='post'>";
            $html .= "<input type=\"hidden\"  id=\"jot_form_id\" name=\"jot_form_id\" value=\"jot-group-details-form\">";
            $html .= "<input type=\"hidden\"  id=\"jot_grpid\" name=\"jot_grpid\" value=\"" . $lastid . "\">";
            $html .= "<table class=\"jot-formtab form-table\">\n";
            
            $html .= $this->render_row('jot_groupnameupd','',$jot_groupname,$tab);
            $html .= $this->render_row('jot_groupdescupd','',$jot_groupdesc,$tab);
                        
                        
            $html .= "</table>";
            $html .= "</form>";
            $html .= "<p>";
            $html .= "<input type=\"button\" id=\"jot-savegrpdetails\" class=\"button\" value=\"" . __("Save group details", "jot-plugin") . "\">";
            $html .= "<div id=\"jot-grpdetails-message\"></div>";
            $html .= "</div>";
            
            return apply_filters('jot_render_groupdetails',$html);
        } // End render_groupdetails()
        
        function render_grouplisttabs( $current = 'jottabgroupdetails' ) {
            if (isset($_GET['subtab'])) {
                $current = sanitize_text_field($_GET['subtab']);
            } else {
                $current = "";
            }
            $tabs = array( 'jottabgroupdetails' => 'Group Details', 'jottabgroupmembers' => 'Member List', 'jottabgroupinvite' => 'Group Invite' );
            $tabs = apply_filters('jot_render_grouplisttabs', $tabs);
            echo '<h2 class="nav-tab-wrapper">';
            foreach( $tabs as $tab => $name ){
                $class = ( $tab == $current ) ? ' nav-tab-active' : '';
                echo "<a class='jot-subtab nav-tab$class' href='#$tab'>" . esc_html($name) . "</a>";
        
            }
            echo '</h2>';
        }
        
        public function render_row_multi($field_name,$alt_field_name, $field_values, $currval, $tab) {
                       
            $fields = $this->get_settings_fields($tab);
            $html = "";
            
            if (!isset($fields[$field_name])) {
                return "Field not found";
            }
            
            $field_args = $fields[$field_name];
            
            
            $html .= "<tr><th>";
            $html .= $field_args['name'];
            $html .= "</th><td>";
            $field_args['options'] = $field_values;
            if (!empty($alt_field_name)) {
                    $field_args['id'] = $alt_field_name;
            }  else {
                    $field_args['id'] = $field_name;
            }    
            $field_args['currval'] = $currval;
            $html .=  $this->render_field($field_args);
            $html .= "</td></tr>";
            
            return apply_filters('jot_render_row_multi',$html);        
        }
        
        public function render_row($field_name, $alt_field_name, $field_value, $tab) {
        
            //if (!isset($args['type'])) {
            //    return "";
            //}
            
            //if (!isset($args['name'])) {
            //    return "";
            //}
        
            $fields = $this->get_settings_fields($tab);
           
            $field_args = $fields[$field_name];
            $html = "";
            
            if (!empty($alt_field_name)) {
                    $field_args['id'] = $alt_field_name;
            }  else {
                    $field_args['id'] = $field_name;
            }
            
                      
            if ($field_args['type']=='hidden') {
                $field_args['value'] = $field_value;
                $html .=  $this->render_field($field_args);
            } else {
                $html .= "<tr><th>";
                $html .= stripslashes($field_args['name']);
                $html .= "</th><td>";
                if (!isset($field_value) || is_null($field_value) || $field_value == '') {    
                    if (isset($field_args['default'])) {
                       $val = $field_args['default'];
                    } else {
                        $val = '';
                    }
                } else {
                    $val = $field_value;
                }
                $field_args['value'] = $val;
                $html .=  $this->render_field($field_args);
                $html .= "</td></tr>";                                    

            }
            return apply_filters('jot_render_row',$html);        
        }
        
         public function render_section_header($field_text) {
            $html = "<tr><th class='jot-section-header' colspan=2>";
            $html .= $field_text;
            $html .= "</th></tr>";
            return apply_filters('jot_render_section_header',$html);        
        }
        
        public function render_row_options($field_name, $currselections, $tab) {
            
                                  
            $fields = $this->get_settings_fields($tab);
            $field_args = $fields[$field_name];
            
            $html = "";
            
            
            $html .= "<tr><th>";
            $html .= stripslashes($field_args['name']);
            $html .= "</th><td>";
            foreach ( $field_args['options'] as $k => $v ) {
                if (array_key_exists($k, $currselections) !== false) {
                   $currval = 'true';    
                } else {
                   $currval = 'false';
                }
                $key = Joy_Of_Text_Plugin()->token . '-' . $field_args['section'] . '[' . $field_name . '][' . $k . ']';
                $html .= '<label for="' . esc_attr( $k ) . '">';
                $html .= '<input id="' . esc_attr( $k ) . '" name="' . esc_attr( $key ) . '" type="checkbox" value="true"' . checked( $currval, 'true', false ) . ' />' . "\n";
                $html .= wp_kses_post( $v ) . '</label>' . "<p>";
            }
            $html .= '<p class="description">' . $field_args['description']. '</p>';
            $html .= "</td></tr>";
            
            return apply_filters('jot_render_row_options',$html);        
        }
        
        /**
        * Renders the admin form to construct invites to the groups
        *
        * @return string HTML markup for the field.
        */
        public function render_groupmembers($sections, $tab, $grpid) {
            
            //Get group member list from database.
            global $wpdb;
            $table_grpmem  = $wpdb->prefix."jot_groupmembers";
            $table_grpxref = $wpdb->prefix."jot_groupmemxref";
            $sql = " SELECT a.jot_grpmemid, a.jot_grpid, a.jot_grpmemname, a.jot_grpmemnum, a.jot_grpmemts, b.jot_grpxrefts " .
                   " FROM " . $table_grpmem . " a, " . $table_grpxref . " b " .
                   " WHERE a.jot_grpid = %d " .
                   " AND a.jot_grpmemid = b.jot_grpmemid" .
                   " AND a.jot_grpid = b.jot_grpid" .
                   " ORDER BY 3 ASC";
            $sqlprep = $wpdb->prepare($sql, $grpid);
            $groupmembers = $wpdb->get_results( $sqlprep );

            if (isset($_GET['subtab'])) {
                if (sanitize_text_field($_GET['subtab']) == 'jottabgroupmembers') {
                    $style = "style='display:block'";
                } else {
                    $style = "style='display:none'";
                }
            } else {
                $style = "style='display:none'";
            }
            
            $html = "<div id='jottabgroupmembers' $style>";        
            $html .= "<table><tr><td>";
            $html .= "<p class='description'>";
            $html .= "Add new members or update existing member details.";
            $html .= "</p>";
            $html .= "</td><td>";
            $html .= "<div id=\"jot-messagestatus\"></div>";
            $html .= "</td></tr></table>";           
                                  
            $html .=  "<form id='jot-group-members-form' action='' method='post'>";
            $html .= "<table id=\"jot-groupmem-tab\" class='jot-groupmem-tab'>\n";
            $html .=  "<tr class='jot-mem-table-headers'><th>Member Name</th><th>Member Phone Number</th><th>Actions</th></tr>\n";
                  
            //Member add row
            $html .= "<tr class='jot-member-add'>";
            $args['value'] = '';
            $html .= "<td class='jot-td-l'>" . $this->render_field_text('jot-mem-add-name', $args )  . "</td>";
            $args['value'] = '';
            $html .= "<td class='jot-td-r'>" . $this->render_field_text('jot-mem-add-num', $args )  . "</td>";                 
            $html .= "<td class='jot-td-l jot-td-mem-actions'>";
            
            $html .= "<div class='divider'></div><a href='#' id='jot-mem-new-" . $grpid . "'><img src='" . plugins_url( 'images/add.png', dirname(__FILE__) ) .  "' title='Add new'></a>";
            $html .= "<div class='divider'></div><a href='" .admin_url() . "admin-post.php?action=process_downloadgroup&grpid=" . $grpid . "' id='jot-grp-mem-download-" . $grpid . "'><img src='" . plugins_url( 'images/download.png', dirname(__FILE__) ) .  "' title='" . __("Download Members","jot-plugin") . "'></a>" ;
            $html .= "<div class='divider'></div><a href='#' id='jot-mem-refresh-" . $grpid. "'><img src='" . plugins_url( 'images/refresh.png', dirname(__FILE__) ) .  "' title='Refresh member list'></a>";
            $html .= "<div class='divider'></div>";
            
            
            $html .= "</td>";
            $html .= "</tr>\n";
            
            foreach ( $groupmembers as $groupmember ) 
            {
                $html .= "<tr class='jot-member-list'>";
                $args['value'] = $groupmember->jot_grpmemname;
                $html .= "<td class='jot-td-l' title='" . __("Member ID : ","jot-plugin") . $groupmember->jot_grpmemid . ",\n" . __("Date added to group : ","jot-plugin") . $groupmember->jot_grpxrefts . "'>" . $this->render_field_text('jot-mem-upd-name-'. $groupmember->jot_grpid . '-' . $groupmember->jot_grpmemid, $args )  . "</td>";
                $args['value'] = $groupmember->jot_grpmemnum;
                $html .= "<td class='jot-td-r'>" . $this->render_field_text('jot-mem-upd-num-'. $groupmember->jot_grpid . '-' . $groupmember->jot_grpmemid, $args )  . "</td>";                 
                $html .= "<td class='jot-td-l'>";
                $html .= "<div class=\"divider\"></div>";
                $html .= "<a href='#' id='jot-mem-save-" . $groupmember->jot_grpid . '-' . $groupmember->jot_grpmemid . "'><img src='" . plugins_url( 'images/save.png', dirname(__FILE__) ) .  "' title='Save'></a>";
                $html .= "<div class=\"divider\"></div>";
                $html .= "<a href='#' id='jot-mem-delete-" . $groupmember->jot_grpid . '-' . $groupmember->jot_grpmemid . "'><img src='" . plugins_url( 'images/delete.png', dirname(__FILE__) ) .  "' title='Delete'></a>";     
                $html .= "<div class=\"divider\"></div>";
                $html .= "</td>";
		
                $html .= "</tr>\n";
        	
            }
            
            $html .= "</table>\n";
            $html .= "</form>\n";
            $html .= "</div>\n";
           
            
            return apply_filters('jot_render_groupmembers',$html);
        } // End render_groupinvites()
        
        /**
        * Renders the admin form to construct invites to the groups
        *
        */
                        
        public function render_groupinvites($sections, $tab, $lastid) {
            
            //Get group invite details from database.
            global $wpdb;
            $table = $wpdb->prefix."jot_groupinvites";
            $sql = " SELECT jot_grpid, jot_grpinvdesc, jot_grpinvnametxt, jot_grpinvnumtxt, jot_grpinvretchk, jot_grpinvrettxt" .
                   " FROM " . $table .
                   " WHERE jot_grpid = %d ";
            $sqlprep = $wpdb->prepare($sql, $lastid);   
            $groupinvite = $wpdb->get_row( $sqlprep );
            
            if (isset($_GET['subtab'])) {
                if (sanitize_text_field($_GET['subtab']) == 'jottabgroupinvite') {
                    $style = "style='display:block'";
                } else {
                    $style = "style='display:none'";
                }
            } else {
                $style = "style='display:none'";
            }
                  
            $html = "<div id='jottabgroupinvite' $style>";
            $html  .= "<h3> Group Invite</h3>";
            $html .= "<p class='description'>";
            $html .= "Tailor the form used to invite people to your group list.";
            $html .= "</p>";
            $html .= "<form id='jot-group-invite-form' action='' method='post'>";
            $html .= "<input type=\"hidden\"  name=\"jot_form_id\" value=\"jot-group-invite-form\">";
            $html .= $this->render_row('jot_grpid','',$lastid,$tab);
            $html .= "<table class=\"jot-formtab form-table\">\n";
           
            $jot_grpinvdesc = isset($groupinvite->jot_grpinvdesc) ? $groupinvite->jot_grpinvdesc : "";
            $jot_grpinvnametxt = isset($groupinvite->jot_grpinvnametxt) ? $groupinvite->jot_grpinvnametxt : "";
            $jot_grpinvnumtxt = isset($groupinvite->jot_grpinvnumtxt) ? $groupinvite->jot_grpinvnumtxt : "";
            $jot_grpinvrettxt = isset($groupinvite->jot_grpinvrettxt) ? $groupinvite->jot_grpinvrettxt : "";
            
            // GDPR notice
	    $jot_grpgdprtxt = $this->get_groupmeta($lastid,'jot_grpgdprtxt');
	    $html .= $this->render_row('jot_grpgdprtxt','',$jot_grpgdprtxt,$tab);
            
            // GDPR checkbox
            $jot_grpgdprchk = Joy_Of_Text_Plugin()->settings->get_groupmeta($lastid,'jot_grpgdprchk');
            $html .= $this->render_row('jot_grpgdprchk','',$jot_grpgdprchk  == 1 ? 'true' : 'false' ,$tab);
            
            if (isset($groupinvite->jot_grpinvretchk)) {
                   $jot_grpinvretchk = $groupinvite->jot_grpinvretchk == 1 ? 'true' : $groupinvite->jot_grpinvretchk;
            } else {
                   $jot_grpinvretchk = 'false';
            }
            
            $html .= $this->render_row('jot_grpinvdesc','',$jot_grpinvdesc,$tab);
            $html .= $this->render_row('jot_grpinvnametxt','',$jot_grpinvnametxt,$tab);
            $html .= $this->render_row('jot_grpinvnumtxt','',$jot_grpinvnumtxt,$tab);
            $html .= $this->render_row('jot_grpinvretchk','',$jot_grpinvretchk ,$tab);
            $html .= $this->render_row('jot_grpinvrettxt','',stripcslashes($jot_grpinvrettxt),$tab);
            
            //$html .= $this->render_row('jot_grpinvformtxt','','',$tab);
            
            // Write form template HTML
	    $confirm_set = 0;
	    $all_group_id  = array($lastid);
	    
	    $html .= $this->render_row('jot_grpinvformtxt',
				       '',
				       Joy_Of_Text_Plugin()->shortcodes->get_wrapped_jotform($lastid, $all_group_id, $groupinvite, array(), $confirm_set),
				       $tab);
            
            $html .= $this->render_row('jot_grpinvshortcode','','[jotform id=' . $lastid . '] or [jotform id=' . $lastid . ' name=no] or [jotform id=' . $lastid . ' formstyle=old]',$tab);           
            
            
            $html .= "</table>";           
            $html .= "</form>";
            $html .= "<p>";
            $html .= "<input type=\"button\" id=\"jot-saveinvite\" class=\"button\" value=\"". __("Save invite details", "jot-plugin") . "\">";
            $html .= "<div id=\"jot-invite-message\"></div>";
            $html .= "</div>";
            
            return apply_filters('jot_render_groupinvites',$html);
        } // End render_groupinvites()
        
       
        /**
        * Render HTML markup for the "text" field type.
        *
        */
        protected function render_field_text ( $key, $args ) {
            if (isset($args['maxlength'])) {
                $maxlength = " maxlength='" . $args['maxlength']. "' ";
            }  else {
                $maxlength = " maxlength='40' ";
            }
            
            if (isset($args['size'])) {
                $size = $args['size'];
            }  else {
                $size = 40;
            }
            
            if (isset($args['placeholder'])) {
                    $placeholder = $args['placeholder'];
            } else {
                    $placeholder = "";
            }
            
            if (isset($args['readonly'])) {
                    $readonly = 'readonly="readonly" ';
            } else {
                    $readonly = "";
            }           
            
            $html = '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" '  . $maxlength .' size="' . $size . '" type="text" value="' . esc_attr( stripslashes($args['value']) ) . '" placeholder="' . $placeholder . '" ' . $readonly . '/>' . "\n";
                 
            return apply_filters('jot_render_field_text',$html);
        } // End render_field_text()
        
        /**
        * Render HTML markup for the "textvalue" field type.
        *
        */
        protected function render_field_textvalue ( $key, $args ) {
                                       
              $html =  "<span id='" . esc_attr( $key ) . "'>" . esc_html( $args['value'] ) . "</span>";
                 
            return apply_filters('jot_render_field_textvalue',$html);
        } // End render_field_text()
        
        
        /**
        * Render HTML markup for the hidden field type.
        *
        */
        protected function render_field_hidden ( $key, $args ) {
            
            $html = '<input id="' . esc_attr( $key ) . '" type="hidden" ' .' name="' . esc_attr( $key ) . '" value="' . esc_attr( $args['value']  ) . '" />' . "\n"; 
                
        return apply_filters('jot_render_field_hidden',$html);
        } // End render_field_text()
        
        
        /**
        * Render HTML markup for the "radio" field type.
        *
        */
        protected function render_field_radio ( $key, $args ) {
            $html = '';
            if ( isset( $args['options'] ) && ( 0 < count( (array)$args['options'] ) ) ) {
                $html = '';
                $html .= '<div id="container-' . $key . '">';
                foreach ( $args['options'] as $k => $v ) {
                    $html .= '<input type="radio" name="' . esc_attr( $key ) . '" value="' . esc_attr( $k ) . '"' . checked( esc_attr( $this->get_value( $args['id'], $args['default'], $args['section'] ) ), $k, false ) . ' /> ' . esc_html( $v ) . "<span class='divider'></span>";
                }
                $html .= '</div>';
            }
            return apply_filters('jot_render_field_radio',$html);
        } // End render_field_radio()
        
        
        /**
        * Render HTML markup for the "textarea" field type.
        *
        */
        protected function render_field_textarea ( $key, $args ) {
                
                if (isset($args['maxlength'])) {
                    $maxlength = " maxlength='" . $args['maxlength']. "' ";
                }  else {
                    $maxlength = " ";
                }
                if (isset($args['cols'])) {
                    $cols = $args['cols'];
                } else {
                    $cols = 40;
                }
                if (isset($args['rows'])) {
                    $rows = $args['rows'];
                } else {
                    $rows = 5;
                }
                		
		
                if (isset($args['wrap'])) {
                    $wrap = " wrap='off' ";
                } else {
                    $wrap = "";
                }
        
                if (isset($args['placeholder'])) {
                    $placeholder = $args['placeholder'];
                } else {
                    $placeholder = "";
                }
                
                if (isset($args['readonly'])) {
                    $readonly = ' readonly ';
                } else {
                    $readonly = "";
                }
		
		if (isset($args['hidden'])) {
                    $hidden = ' style="display:none" ';
                } else {
                    $hidden = "";
                }   
                 
                $html = '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" cols="' . $cols . '" rows="' . $rows. '" placeholder="' . $placeholder .'"' . $maxlength. $readonly . $wrap . $hidden . '>' . esc_textarea($args['value']) . '</textarea>' . "\n";
                
            
            return apply_filters('jot_render_field_textarea',$html);
        } // End render_field_textarea()
        
          
        /**
        * Render HTML markup for the "checkbox" field type.
        *
        */
        protected function render_field_checkbox ( $key, $args ) {
            
                       
            $has_description = false;
            $html = '';
            if ( isset( $args['label'] ) ) {
                $has_description = true;
                $html .= '<label for="' . esc_attr( $key ) . '">';
            }
            if (isset($args['value'])) {
                $html .= '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="checkbox" value="true"' . checked( $args['value'], 'true', false ) . ' />' . "\n";
            }
              
           
            if ( $has_description ) {
                $html .= wp_kses_post( $args['label'] ) . '</label>';
            }
        return apply_filters('jot_render_field_checkbox',$html);
        } // End render_field_checkbox()
        
        /**
        * Render HTML markup for the "select" field type.
        *         
        */
        protected function render_field_select ( $key, $args ) {
                      
            $html = '';
            $size = '';
            $multiple = '';
            $arr = '';
            $currselections = array();
            if(isset($args['size'])) {
                $size = ' size="' . $args['size'] . '" ';
            }            
            if(isset($args['multiple'])) {
                $multiple = ' multiple ';
                $arr = '[]';
                if (is_array($args['currval'])) {
                   $currselections = $args['currval'];
                }
            }
            
            if ( isset( $args['options'] ) && ( 0 < count( (array)$args['options'] ) ) ) {
                $html .= '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . $arr .'"' . $size . $multiple . '>';
                foreach ( $args['options'] as $k => $v ) {
                    if(isset($args['multiple'])) {
                       if (array_search($k, $currselections) !== false && is_array($currselections) == 1) {
                          $sel = ' selected="selected" ';                          
                       } else {
                          $sel = "";
                       }                       
                       $html .= '<option value="' . esc_attr( $k ) . '"' . $sel . '>' . esc_html( $v ) . '</option>';                        
                    } else {
                       $html .= '<option value="' . esc_attr( $k ) . '"' . selected( esc_attr( $k ),$args['currval'],false) . '>' . esc_html( $v ) . '</option>';
                    }
                }
                $html .= '</select>';
            }
        return apply_filters('jot_render_field_select',$html);
        } // End render_field_select()
        
        
        /**
        * Render HTML markup for the "select" field type which contains optgroups.
        *         
        */
        protected function render_field_optgroupselect ( $key, $args ) {
            // $key is the optgroup name
            // $args['option'] contains an array with element called 'id' and 'value' 
            
            $html = '';
            if ( isset( $args['options'] ) && ( 0 < count( (array)$args['options'] ) ) ) {
                $html .= '<select class="jot-optgroup" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '[]" multiple="multiple">';
                foreach ( $args['options'] as $k => $v ) {
                    $html .= "<optgroup label='" . esc_attr($k) . "'>";
                    foreach ($v as $val) {
                        $html .= '<option value="' . esc_attr( $val['id'] ) . '">' . esc_html( $val['value'] ) . '</option>';
                    }
                }
                $html .= '</select>';
            }
        return apply_filters('jot_render_field_select',$html);
        } // End render_field_select()
        
        
        
        public function get_value ( $key, $default, $section ) {
            $response = false;
            $values = get_option( 'jot-plugin-' . $section, array() );
            if ( is_array( $values ) && isset( $values[$key] ) ) {
                $response = $values[$key];
            } else {
                $response = $default;
            }
            $response = stripslashes($response);
        return apply_filters('jot_get_value',$response);
        } // End get_value()
        
        
        public function get_groups() {
        
            //Get group list from database for groups with 1 or more member
            global $wpdb;
            $currkey = '';
            $table_groups = $wpdb->prefix."jot_groups";
            $table_members = $wpdb->prefix."jot_groupmembers";
            $sql = " SELECT distinct jot_groupname, jot_groupid, jot_grpmemid, jot_grpmemname, jot_grpmemnum " .
                   " FROM " . $table_groups . ", " .$table_members  .
                   " WHERE jot_groupid = jot_grpid " .
                   " ORDER BY 1,4" ;
                   
                  
            $groups = $wpdb->get_results( $sql );
            $grouparr = array();
            $groupmemarr = array();
            
            $i=0;
            foreach ($groups as $group){
                
                if ($i==0) {
                    $currkey =  $group->jot_groupname;                    
                }
               
                if ($currkey != $group->jot_groupname ) {
                    
                                                                  
                    $grouparr[$currkey] = $groupmemarr;
                    unset($groupmemarr);
                    $groupmemarr = array();
                    $currkey = $group->jot_groupname;
                    $groupmemarr[] = array("id"=>$group->jot_groupid . "-" . $group->jot_grpmemid, "value"=> $group->jot_grpmemname . " (" . $group->jot_grpmemnum . ")"); 
                }  else {
                    $groupmemarr[] = array("id"=>$group->jot_groupid . "-" . $group->jot_grpmemid, "value"=> $group->jot_grpmemname . " (" . $group->jot_grpmemnum . ")"); 
                }                
                $i++;                
            }
            // Catch last group
            $grouparr[$currkey] = $groupmemarr;
            
            return apply_filters('jot_get_all_groups_and_members',$grouparr);
        
        }
        
        public function get_member($jotmemid) {
        
            //Get member details for given memberid
            global $wpdb;
            
            $table_members = $wpdb->prefix."jot_groupmembers";
            $sql = " SELECT jot_grpmemid, jot_grpmemname, jot_grpmemnum " .
                   " FROM " . $table_members  .
                   " WHERE jot_grpmemid = %d";
            $sqlprep = $wpdb->prepare($sql, $jotmemid);           
            $member = $wpdb->get_row( $sqlprep );
            $memarr = array("jot_grpmemid" => $member->jot_grpmemid, "jot_grpmemname" => $member->jot_grpmemname, "jot_grpmemnum" => $member->jot_grpmemnum );
                          
            return apply_filters('jot_get_member',$memarr);
        }
        
        public function get_all_groups_and_members($grpid = "") {
	    
	    if (!is_array($grpid) && $grpid !="") {
		$grpid = array($grpid);
	    }
	    
            if (!empty($grpid)) {		
                $grpclause = " AND b.jot_grpid IN (" . implode( ", ", $grpid ) . ")";
            } else {
                $grpclause = "";
            }
        
            //Get group list from database for groups with 1 or more member
            global $wpdb;
              
            $tablegrpmem = $wpdb->prefix."jot_groupmembers"; // a
            $tablexref = $wpdb->prefix."jot_groupmemxref";   // b
            $tablegrps = $wpdb->prefix."jot_groups"; //c    
            $sql = "SELECT  c.jot_groupname, b.jot_grpid, b.jot_grpmemid, a.jot_grpmemname, a.jot_grpmemnum  " . 
		" FROM " . $tablegrpmem .  " a," . $tablexref . " b, " . $tablegrps . " c " . 
		" WHERE a.jot_grpmemid = b.jot_grpmemid " .
		" AND b.jot_grpid = c.jot_groupid " .               
                $grpclause .
                " ORDER BY 1,4";
		
            $groups = $wpdb->get_results( $sql );
            
            return apply_filters('jot_get_all_groups_and_members',$groups,$grpid);
        }
         
        public function save_groupmeta($jot_grpid, $key, $value) {
            global $wpdb;
            
            // Check if key exists already for this group
            $table = $wpdb->prefix."jot_groupmeta";
            $sql = " SELECT jot_groupmetaid " .
            " FROM " . $table .
            " WHERE jot_groupid  = %d " .
            " AND jot_groupmetakey = %s";
            
            $sqlprep = $wpdb->prepare($sql, $jot_grpid, $key );
              
            $key_exists =$wpdb->get_col($sqlprep); 
             
            if ( $key_exists ) {
                    $type = "update";
                     $data = array(
                    'jot_groupid'       => $jot_grpid,        
                    'jot_groupmetakey'  => $key,
                    'jot_groupmetaval'  => $value                             
                    );			    
                    
                    $success=$wpdb->update( $table, $data, array( 'jot_groupid' => $jot_grpid, 'jot_groupmetakey' => $key ) );
                    
            } else {
                    $type = "insert";
                    $data = array(
                    'jot_groupid'       => $jot_grpid,        
                    'jot_groupmetakey'  => $key,
                    'jot_groupmetaval'  => $value                             
                    );
                    $success=$wpdb->insert( $table, $data );
                    
            }
            if ($success === false) {
                 $errorcode = 999;			 
            } else {
                 $errorcode = 0;
            }
            $response = array('errorcode' => $errorcode, 'errormsg' => '', 'sqlerr' => $wpdb->last_error, 'success' => $success, 'type' => $type);
            return $response;
		    
	}
	       
	public function get_groupmeta($jot_grpid, $key, $default = "") {
            global $wpdb;
                
            $table = $wpdb->prefix."jot_groupmeta";
            $sql = " SELECT jot_groupmetaval " .
                " FROM " . $table .
                " WHERE jot_groupid  = %d " .
                " AND jot_groupmetakey = %s";
                
            $sqlprep = $wpdb->prepare($sql, $jot_grpid, $key );
            $metaval = $wpdb->get_col($sqlprep);
                
            $retval = isset($metaval[0]) ? $metaval[0] : $default;
                            
            return $retval;
		    
	}
        
        public function get_group_details($jotgrpid) {
            
	    if (!$jotgrpid) {
		return;
	    }
	    
            //Get group list from database.
            global $wpdb;
            $table = $wpdb->prefix."jot_groups";
            $sql = " SELECT jot_groupid, jot_groupname,jot_groupdesc, jot_ts " . 
                   " FROM " . $table .
                   " WHERE jot_groupid = " . $jotgrpid;
            
            $sql = apply_filters('jot_get_group_details_sql',$sql, $jotgrpid);
            
            $groupdetails = $wpdb->get_row( $sql );    
	
            return apply_filters('jot_get_group_details',$groupdetails,$jotgrpid);
            
        }
        
        public function allowed_style_attributes() {
            $styles[] = 'display';
            return $styles;
        }
        
        /*
        public function allowed_html_tags() {
                $allowed_atts = array(
                    'align'      => array(),
                    'class'      => array(),
                    'type'       => array(),
                    'id'         => array(),                    
                    'style'      => array(),                    
                    'src'        => array(),
                    'alt'        => array(),
                    'href'       => array(),
                    'rel'        => array(),
                    'rev'        => array(),
                    'target'     => array(),                    
                    'type'       => array(),
                    'value'      => array(),
                    'name'       => array(),
                    'option'     => array(),                    
                    'action'     => array(),
                    'method'     => array(),
                    'for'        => array(),
                    'width'      => array(),
                    'size'       => array(),
                    'rows'       => array(),
                    'cols'       => array(),
                    'height'     => array(),
                    'selected'   => array(),
                    'multiple'   => array(),
                    'checked'    => array(),
                    'data'       => array(),
                    'colspan'    => array(),
                    'readonly'   => array(),
                    'placeholder'=> array(),
                    'title'      => array(),
            );
            $allowed_tags['form']     = $allowed_atts;
            $allowed_tags['label']    = $allowed_atts;
            $allowed_tags['input']    = $allowed_atts;
            $allowed_tags['textarea'] = $allowed_atts;
            $allowed_tags['select']   = $allowed_atts;
            $allowed_tags['option']   = $allowed_atts;
            $allowed_tags['optgroup'] = $allowed_atts;            
            $allowed_tags['style']    = $allowed_atts;
            $allowed_tags['strong']   = $allowed_atts;           
            $allowed_tags['table']    = $allowed_atts;
            $allowed_tags['span']     = $allowed_atts;          
            $allowed_tags['pre']      = $allowed_atts;
            $allowed_tags['div']      = $allowed_atts;
            $allowed_tags['img']      = $allowed_atts;
            $allowed_tags['h1']       = $allowed_atts;
            $allowed_tags['h2']       = $allowed_atts;
            $allowed_tags['h3']       = $allowed_atts;
            $allowed_tags['h4']       = $allowed_atts;
            $allowed_tags['h5']       = $allowed_atts;
            $allowed_tags['h6']       = $allowed_atts;
            $allowed_tags['ol']       = $allowed_atts;
            $allowed_tags['ul']       = $allowed_atts;
            $allowed_tags['li']       = $allowed_atts;
            $allowed_tags['em']       = $allowed_atts;
            $allowed_tags['hr']       = $allowed_atts;
            $allowed_tags['br']       = $allowed_atts;
            $allowed_tags['th']       = $allowed_atts;
            $allowed_tags['tr']       = $allowed_atts;
            $allowed_tags['td']       = $allowed_atts;
            $allowed_tags['p']        = $allowed_atts;
            $allowed_tags['a']        = $allowed_atts;
            $allowed_tags['b']        = $allowed_atts;
            $allowed_tags['i']        = $allowed_atts;
            
            return $allowed_tags;
        }
        */
        
        public function allowed_html_tags() {
            return array(
                    
                    'h1'       => $this->prefix_allowed_global_attributes(),
                    'h2'       => $this->prefix_allowed_global_attributes(),
                    'h3'       => $this->prefix_allowed_global_attributes(),
                    'h4'       => $this->prefix_allowed_global_attributes(),
                    'h5'       => $this->prefix_allowed_global_attributes(),
                    'h6'       => $this->prefix_allowed_global_attributes(),
                    
    
                    // Text Content.
                    'div'        => $this->prefix_allowed_global_attributes(),                    
                    'hr'         => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'   => true, 
                                    'color'   => true,
                                    'noshade' => true, 
                                    'size'    => true, 
                                    'width'   => true, 
                            )
                    ),
                    'li'         => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'value' => true,
                            )
                    ),
                    'ol'         => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'reversed' => true,
                                    'start'    => true,
                            )
                    ),
                    'p'          => $this->prefix_allowed_global_attributes(),                    
                    'ul'         => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'compact' => true,
                                    'type'    => true,
                            )
                    ),
    
                    // Inline Text Sematics
                    'a'      => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'download' => true,
                                    'href' => true,                                    
                                    'referrerpolicy' => true,
                                    'rel' => true,
                                    'target' => true,
                                    'type' => true,
                            )
                    ),                    
                    'b'      => $this->prefix_allowed_global_attributes(),                    
                    'br'     => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'clear' => true, 
                            )
                    ),                    
                    'data'   => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'value' => true,
                            )
                    ),
                    'i'      => $this->prefix_allowed_global_attributes(),                    
                    'span'   => $this->prefix_allowed_global_attributes(),
                    'strong' => $this->prefix_allowed_global_attributes(),
                    
    
                    // Image & Media.                    
                    'audio' => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'autoplay' => true,
                                    'buffered' => true,
                                    'controls' => true,
                                    'loop'     => true,
                                    'muted'    => true,
                                    'played'   => true,
                                    'preload'  => true,
                                    'src'      => true,
                                    'volume'   => true,
                            )
                    ),
                    'img'   => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'          => true, 
                                    'alt'            => true,
                                    'border'         => true, 
                                    'crossorigin'    => true,
                                    'decoding'       => true,
                                    'height'         => true,
                                    'hspace'         => true, 
                                    'importance'     => true,
                                    'intrinsicsize'  => true,
                                    'ismap'          => true,
                                    'loading'        => true,
                                    'longdesc'       => true, 
                                    'name'           => true,
                                    'onerror'        => true,
                                    'referrerpolicy' => true,
                                    'sizes'          => true,
                                    'src'            => true,
                                    'srcset'         => true,
                                    'usemap'         => true,
                                    'vspace'         => true, 
                                    'width'          => true,
                            )
                    ),                    
                    'video' => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'autoplay'             => true,
                                    'autoPictureInPicture' => true,
                                    'buffered'             => true,
                                    'controls'             => true,
                                    'controlslist'         => true,
                                    'crossorigin'          => true,
                                    'currentTime'          => true,
                                    'duration'             => true,
                                    'height'               => true,
                                    'intrinsicsize'        => true,
                                    'loop'                 => true,
                                    'muted'                => true,
                                    'playinline'           => true,
                                    'poster'               => true,
                                    'preload'              => true,
                                    'src'                  => true,
                                    'width'                => true,
                            )
                    ),
    
                    // Embedded Content.                    
                    'object'  => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'archive' => true, // Deprecated.
                                    'border' => true, // Deprecated.
                                    'classid' => true, // Deprecated.
                                    'codebase' => true, // Deprecated.
                                    'codetype' => true, // Deprecated.
                                    'data' => true,
                                    'declare' => true, // Deprecated.
                                    'form' => true,
                                    'height' => true,
                                    'name' => true,
                                    'standby' => true, // Deprecated.
                                    'tabindex' => true, // Deprecated.
                                    'type' => true,
                                    'typemustmatch' => true,
                                    'usemap' => true,
                                    'width' => true,
                            )
                    ),                       
    
                    // Table Content.
                    'caption'  => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align' => true, // Deprecated.
                            )
                    ),
                    'col'      => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'   => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'span'    => true,
                                    'valign'  => true, // Deprecated.
                                    'width'   => true, // Deprecated.
                            )
                    ),
                    'colgroup'      => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'   => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'span'    => true,
                                    'valign'  => true, // Deprecated.
                                    'width'   => true, // Deprecated.
                            )
                    ),
                    'table'    => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'       => true, // Deprecated.
                                    'bgcolor'     => true, // Deprecated.
                                    'border'      => true, // Deprecated.
                                    'cellpadding' => true, // Deprecated.
                                    'cellspacing' => true, // Deprecated.
                                    'frame'       => true, // Deprecated.
                                    'rules'       => true, // Deprecated.
                                    'summary'     => true, // Deprecated.
                                    'width'       => true, // Deprecated.
                            )
                    ),
                    'tbody'    => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'   => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'valign'  => true, // Deprecated.
                            )
                    ),
                    'td'       => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'abbr'    => true, // Deprecated.
                                    'align'   => true, // Deprecated.
                                    'axis'    => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'colspan' => true,
                                    'headers' => true,
                                    'rowspan' => true,
                                    'scope'   => true, // Deprecated.
                                    'valign'  => true, // Deprecated.
                                    'width'   => true, // Deprecated.
                            )
                    ),
                    'tfoot'    => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'   => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'valign'  => true, // Deprecated.
                            )
                    ),
                    'th'       => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'abbr'    => true,
                                    'align'   => true, // Deprecated.
                                    'axis'    => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'colspan' => true,
                                    'headers' => true,
                                    'rowspan' => true,
                                    'scope'   => true,
                                    'valign'  => true, // Deprecated.
                                    'width'   => true, // Deprecated.
                            )
                    ),
                    'thead'    => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'   => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'valign'  => true, // Deprecated.
                            )
                    ),
                    'tr'       => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'align'   => true, // Deprecated.
                                    'bgcolor' => true, // Deprecated.
                                    'char'    => true, // Deprecated.
                                    'charoff' => true, // Deprecated.
                                    'valign'  => true, // Deprecated.
                            )
                    ),
    
                    // Forms.
                    'button'   => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'autofocus'      => true,
                                    'disabled'       => true,
                                    'form'           => true,
                                    'formaction'     => true,
                                    'formenctype'    => true,
                                    'formmethod'     => true,
                                    'formnovalidate' => true,
                                    'formtarget'     => true,
                                    'name'           => true,
                                    'type'           => true,
                                    'value'          => true,
                            )
                    ),
                    'datalist' => $this->prefix_allowed_global_attributes(),
                    'fieldset' => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'disabled' => true,
                                    'form'     => true,
                                    'name'     => true,
                            )
                    ),
                    'form'     => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'accept'         => true, // Deprecated.
                                    'accept-charset' => true,
                                    'action'         => true,
                                    'enctype'        => true,
                                    'method'         => true,
                                    'name'           => true,
                                    'novalidate'     => true,
                                    'target'         => true,
                            )
                    ),
                    'input'    => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'accept'         => true,
                                    'alt'            => true,
                                    'autocomplete'   => true,
                                    'autofocus'      => true,
                                    'capture'        => true,
                                    'checked'        => true,
                                    'dirname'        => true,
                                    'disabled'       => true,
                                    'form'           => true,
                                    'formaction'     => true,
                                    'formenctype'    => true,
                                    'formmethod'     => true,
                                    'formnovalidate' => true,
                                    'formtarget'     => true,
                                    'height'         => true,
                                    'list'           => true,
                                    'max'            => true,
                                    'maxlength'      => true,
                                    'min'            => true,
                                    'minlength'      => true,
                                    'multiple'       => true,
                                    'name'           => true,
                                    'pattern'        => true,
                                    'placeholder'    => true,
                                    'readonly'       => true,
                                    'required'       => true,
                                    'size'           => true,
                                    'src'            => true,
                                    'step'           => true,
                                    'type'           => true,
                                    'value'          => true,
                                    'width'          => true,
                            )
                    ),
                    'label'    => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'for'  => true,
                                    'form' => true, // Deprecated.
                            )
                    ),
                    'optgroup' => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'disabled' => true,
                                    'label' => true,
                            )
                    ),
                    'option'   => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'disabled' => true,
                                    'label'    => true,
                                    'selected' => true,
                                    'value'    => true,
                            )
                    ),                    
                    'select'   => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'autofocus' => true,
                                    'disabled'  => true,
                                    'form'      => true,
                                    'multiple'  => true,
                                    'name'      => true,
                                    'required'  => true,
                                    'size'      => true,
                            )
                    ),
                    'textarea' => array_merge(
                            $this->prefix_allowed_global_attributes(),
                            array(
                                    'autofocus'   => true,
                                    'cols'        => true,
                                    'disabled'    => true,
                                    'form'        => true,
                                    'maxlength'   => true,
                                    'minlength'   => true,
                                    'name'        => true,
                                    'placeholder' => true,
                                    'readonly'    => true,
                                    'required'    => true,
                                    'rows'        => true,
                                    'spellcheck'  => true,
                                    'wrap'        => true,
                            )
                    ),
    
            );
    }
    
    /**
     * Allowed Global Attributes.
     *
     * @return array
     */
    private function prefix_allowed_global_attributes() {
            return array(
                    'aria-*'              => true,                    
                    'class'               => true,
                    'contenteditable'     => true,
                    'data-*'              => true,                    
                    'hidden'              => true,
                    'id'                  => true, 
                    'style'               => true,
                    'tabindex'            => true,
                    'title'               => true,                    
            );
    }    
    
} // End Class