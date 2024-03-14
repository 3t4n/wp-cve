<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
* Joy_Of_Text_Plugin_Admin Class
*

*/


final class Joy_Of_Text_Plugin_Admin {
        /**
        * Joy_Of_Text_Plugin_Admin The single instance of Joy_Of_Text_Plugin_Admin.
        * @var object
        * @access private
        * @since 1.0.0
        */
        
        private static $_instance = null;
        
        
        /**
        * Constructor function.
        */
        public function __construct () {
            // Register the settings with WordPress.
            add_action( 'admin_init', array( $this, 'register_settings' ) );
            // Register the settings screen within WordPress.
            add_action( 'admin_menu', array( $this, 'register_settings_screen' ) );
        
            // Add toolbar link to JOT
            add_action( 'admin_bar_menu', array($this,'add_jot_toolbar_link'), 999);
        } // End __construct()
        
        /**
        * Main Joy_Of_Text_Plugin_Admin Instance
        *
        * Ensures only one instance of Joy_Of_Text_Plugin_Admin is loaded or can be loaded.
        *
        */
        public static function instance () {
            if ( is_null( self::$_instance ) )
            self::$_instance = new self();
            return self::$_instance;
        } // End instance()
        
        /**
        * Register the admin screen.
        */
        public function register_settings_screen () {
            //$this->_hook = add_submenu_page( 'options-general.php', __( 'Joy Of Text Plugin Settings', 'jot-plugin' ), __( 'JOT Settings', 'jot-plugin' ), 'manage_options', 'jot-plugin', array( $this, 'settings_screen' ) );
            add_menu_page(__('Messaging', 'jot-plugin'), __('Messaging', 'jot-plugin'), 'manage_options', 'jot-plugin', array( $this, 'settings_screen' ),'dashicons-phone');
        } // End register_settings_screen()
        
        /**
        * add a link to the WP Toolbar
        */
        function add_jot_toolbar_link($wp_admin_bar) {
            $toolbar_label = __( 'JOT', 'jot-plugin' );
            $toolbar_label = apply_filters('jot_whitelabel_dashboard_admin_toolbar_label',$toolbar_label);
            
            $args = array(
                'id' => 'jot-messages-toolbar',
                'title' => '<span class="ab-icon"></span><span class="ab-label">'. $toolbar_label .'</span>', 
                'href' => admin_url('admin.php?page=jot-plugin'), 
                'meta' => array(
                    'target'=> '_self',
                    'class' => 'jot-messages-toolbar', 
                    'title' => 'JOT Messaging'
                    )
            );
            $wp_admin_bar->add_node($args);
        }
        
        /**
        * Output the markup for the settings screen.
        */
        public function settings_screen () {
            global $title;
            $sections = Joy_Of_Text_Plugin()->settings->get_settings_sections();
            $tab = $this->_get_current_tab( $sections );
            
            
            $subform = $this->get_subform();
            $tabform = $tab . "-" . $subform;
            
            echo wp_kses_post($this->get_admin_header_html( $sections, $title ));
            switch ( $tabform ) {
                case 'smsprovider-main'; 
                    $this->write_smsprovider_fields($sections, $tab);          
                break;
                case 'messages-main':
                    $this->write_message_fields($sections, $tab);
                break;
                case 'group-list-main':
                    $this->write_group_list_fields($sections, $tab);
                break;                
                case 'extensions-main':                
                    $this->write_extensions_fields($sections, $tab);                
                break;
                default:
                   do_action("jot_render_extension_tab",$tabform);
                break;
            }
                    
        } // End settings_screen()
            
            
        /**
        * Write out message_fields tab screen
        */    
        public function write_smsprovider_fields($sections,$tab) {
            
            echo "<form id=\"smsprovider-fields-form\" action=\"options.php\" method=\"post\">";
            settings_fields( 'jot-plugin-settings-' . $tab );
                        
            $pagehtml = Joy_Of_Text_Plugin()->settings->render_smsprovider_settings($sections,$tab);
            echo wp_kses($pagehtml['html'], Joy_Of_Text_Plugin()->settings->allowed_html_tags());
                                    
            if (isset($_GET['section']))  {
               // Don't display Save button on Get Started or system info tabs
               $section_name = sanitize_text_field($_GET['section']);
               if ($section_name != 'getstarted' && $section_name != 'systeminfo' && $section_name != 'licencekeys')  {
                   submit_button( __(  $sections[$tab]['buttontext'], 'jot-plugin' ) );
               }
            }
            
            echo "</form>";
            echo "<br>";
            
            // Display a guidance messages
            $auth = get_option('jot-plugin-smsprovider');
            $selected_provider = Joy_Of_Text_Plugin()->currentsmsprovidername;
            if (isset($auth['jot-accountsid-' . $selected_provider])) {
                $sid = $auth['jot-accountsid-' . $selected_provider];
            } else {
                $sid = null;
            }
            $guidance = "";
            
            if (!is_null($sid)) {               
                if ($pagehtml['message_code'] !=0 ) {
                    $guidance = $pagehtml['message_text'];
                    $cssclass = "jot-messagered";
                } else {
                    if ( Joy_Of_Text_Plugin()->settings->get_current_smsprovider_number() == 'default') {
                       $guidance = __( 'Please select your "from" number and save.', 'jot-plugin' );
                        $cssclass = "jot-messagered";
                    } else {
                        $guidance = $pagehtml['message_text'];
                        $cssclass = "jot-messagegreen";
                    }
                } 
            }
            
            
            echo "<div id=\"jot-messagestatus\" class=\"". esc_attr($cssclass) . "\">" . esc_html($guidance) . "</div>";
            
            $this->write_page_footer();
           
        }
        
        /**
        * Write out message_fields tab screen
        */
        public function write_message_fields($sections,$tab) {
            
            echo "<form id=\"jot-message-field-form\" action=\"\" method=\"post\">";
            settings_fields( 'jot-plugin-settings-' . $tab );
            
            echo wp_kses(Joy_Of_Text_Plugin()->settings->render_message_panel($sections,$tab), Joy_Of_Text_Plugin()->settings->allowed_html_tags());
                        
            echo "<a href=\"#\" class=\"button button-primary\" id=\"jot-sendmessage\">" . __("Send your message","jot-plugin") . "</a>"; 
            echo "</form>";
            echo "<br>";
            echo "<div id=\"jot-messagestatus\"></div>";
            echo "<div id=\"jot-sendstatus-div\">";
            echo "</div>";
            
            $this->write_page_footer();
            
        }
        
        public function write_group_list_fields($sections,$tab) {            
           
            echo "<form id=\"group-list-fields-form\" action=\"\" method=\"post\">";
            echo "<input type=\"hidden\"  name=\"jot-form-id\" value=\"jot-group-add\">";
            settings_fields( 'jot-plugin-settings-' . $tab );            
            echo "</form>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            
            
            $lastid = Joy_Of_Text_Plugin()->lastgrpid;
            wp_localize_script( 'jot-js', 'jot_lastgroup',
		       array( 'id' => $lastid ) );
            
            echo wp_kses(Joy_Of_Text_Plugin()->settings->render_grouplisttabs(), Joy_Of_Text_Plugin()->settings->allowed_html_tags());
            echo wp_kses(Joy_Of_Text_Plugin()->settings->render_groupdetails($sections, $tab, $lastid), Joy_Of_Text_Plugin()->settings->allowed_html_tags());
            echo wp_kses(Joy_Of_Text_Plugin()->settings->render_groupmembers($sections, $tab, $lastid), Joy_Of_Text_Plugin()->settings->allowed_html_tags());
            echo wp_kses(Joy_Of_Text_Plugin()->settings->render_groupinvites($sections, $tab, $lastid), Joy_Of_Text_Plugin()->settings->allowed_html_tags());
                                    
            do_action("jot_render_extension_subtab",$sections, $tab, $lastid);
                        
            $this->write_page_footer();         
        }
        
        public function write_extensions_fields($sections,$tab) {
            
             
             
                echo "<span style='padding-top:10px;display: block;clear:left;font-size:28px;color:black'>";
                echo __("Checkout the Joy Of Text Pro","jot-plugin");
                echo __(" and its fab features including : ","jot-plugin");                
                echo "</span>";
                echo "<p>";
                echo "<div style='display: block;'>";
                echo "<div style='margin:0px 0px 10px 0px;float:left;'>";
                echo "<a href='http://www.getcloudsms.com/downloads/joy-of-text-pro-version-3/'>";
                echo  "<img src='" . plugins_url('images/jotpro.png', dirname(__FILE__)) . "' width='320' height='200' alt='" . __("Joy Of Text Pro","jot-plugin") . "' style='margin:0px 10px 0px 0px'>";
                echo "</a>";
                echo "</div>";
                echo "<p style='font-size:20px;color:black;'>";                         
                echo "<span style='margin-left:20px;font-size:20px;color:black'>" . __("- Multiple member groups.","jot-plugin") . "</span>";
                echo "<br><span style='margin-left:20px;font-size:20px;color:black'>" . __("- Receiving SMS and MMS messages.","jot-plugin") . "</span>";
                echo "<br><span style='margin-left:20px;font-size:20px;color:black'>" . __("- Subscriber opt-in and opt-out supported.","jot-plugin") . "</span>";
                echo "<br><span style='margin-left:20px;font-size:20px;color:black'>" . __("- Create groups based on WP roles.","jot-plugin") . "</span>";
                echo "<br><span style='margin-left:20px;font-size:20px;color:black'>" . __("- Subscribing to groups using a text message.","jot-plugin") . "</span>";
                echo "<br><span style='margin-left:20px;font-size:20px;color:black'>" . __("- WooCommerce integration - send order notifications messages.","jot-plugin") . "</span>";
                echo "<br><span style='margin-left:20px;font-size:20px;color:black'>" . __("- Integration with advanced Twilio features such as number pools....and many more!","jot-plugin") . "</span>";
                echo "</div>";
             
                echo "<p>";
                echo "<span style='font-size:28px;color:black'>";
                echo __("A number of extensions are available for the Joy Of Text Lite plugin, these include:","jot-plugin");
                echo "</span>";
                echo "<br><br><p>";
                
                echo "<div style='display: block;'>";
                echo "<div style='margin:0px 0px 10px 0px;float:left;'>";
                echo "<a href='http://www.getcloudsms.com/downloads/joy-text-message-scheduler-extension/'>";
                echo  "<img src='" . plugins_url('images/jotsched.png', dirname(__FILE__)) . "' width='320' height='200' alt='" . __("JOT Scheduler Extension","jot-plugin") . "' style='margin:0px 10px 0px 0px'>";
                echo "</a>";
                echo "</div>";
                echo "<p style='font-size:20px;color:black'>";
                echo __("The JOT Scheduler extension allows you to schedule a batch of messages to be sent at a future date and","jot-plugin");
                echo "<br>" . __("time and seemlessly integrates into the existing JOT Pro and Lite screens.","jot-plugin");
                $tagurl = "<a href='https://youtu.be/YI4QjVFwM9A' target='_blank'>" . __("schedule plans","jot-plugin") . "</a>";
                echo "<br><br>" . sprintf(__("The plugin also allows '%s' to be created. These will send a series of messages, based a member's subscription date.","jot-plugin"),esc_url($tagurl));
                echo "<br>" . __("For example, you could send them a message 1 week, 1 month or a year after they subscribed. ","jot-plugin");
                echo "</div>";
               
                   
                echo "<div style='display: block;clear:left;'>";
                echo "<div style='margin:0px 0px 10px 0px;float:left;'>";
                echo "<a href='http://www.getcloudsms.com/downloads/joy-text-buddypress-extension///'>";
                echo  "<img src='" . plugins_url('images/jotbp.png', dirname(__FILE__)) . "' width='320' height='200' alt='" . __("JOT WooCommerce Extension","jot-plugin") . "' style='margin:0px 10px 0px 0px'>";
                echo "</a>";
                echo "</div>";
                echo "<p style='font-size:20px;color:black;'>";
                echo __(" The JOT Buddypress plugin lets you synchronise your Buddypress members the Joy Of Text,","jot-plugin");
                echo "<br>" . __("allowing you to send messsages to your valued Buddypress members.","jot-plugin");               
                echo "</div>";
                echo "<br><br><p><p>";   
                                
                echo "<div style='display: block;clear:left;'>";
                echo "<div style='margin:0px 0px 10px 0px;float:left;'>";
                echo "<a href='http://www.getcloudsms.com/downloads/joy-of-text-woocommerce-integration-extension//'>";
                echo  "<img src='" . plugins_url('images/jotwoo.png', dirname(__FILE__)) . "' width='320' height='200' alt='" . __("JOT WooCommerce Extension","jot-plugin") . "' style='margin:0px 10px 0px 0px'>";
                echo "</a>";
                echo "</div>";
                echo "<p style='font-size:20px;color:black;'>";
                echo __("The JOT Woocommerce integration plugin will send SMS notifications to your WooCommerce customers when they place an order.","jot-plugin");
                $merge_url = "<a href='http://www.getcloudsms.com/documentation/joy-text-supported-merge-tags/'>" . __("merge tags","jot-plugin") . "</a>";
                echo  sprintf(__(" Notifications can contain a number of %s, ","jot-plugin"),esc_html($merge_url));
                echo __(" and can be sent to both customers and to your site admin or shop owner.","jot-plugin");
                echo __(" The plugin also lets you to synchronise your WooCommerce customers into the Joy Of Text,","jot-plugin");
                echo __(" allowing you to send messsages to your valued Woo customers.","jot-plugin");
                echo "<br>" . __(" For example, you could send SMS messages about offers, sales, opening times or discount codes.","jot-plugin");
                echo "</div>";
                echo "<br><br><p><p>";
                
                echo "<p><br>";
                echo "<span style='font-size:28px;color:black'>";
                echo "Powered by the Joy Of Text Pro.";
                echo "</span>";
                echo "<br><br><p>";
                
                echo "<div style='display: block;clear:left;'>";
                echo "<div style='margin:0px 0px 10px 0px;float:left;'>";
                echo "<a href='http://www.spacemash.co.uk'>";
                echo  "<img src='" . plugins_url('images/spacemash_black_320x200.png', dirname(__FILE__)) . "' width='320' height='200' alt='" . __("SpaceMash.co.uk","jot-plugin") . "' style='margin:0px 10px 0px 0px'>";
                echo "</a>";
                echo "</div>";
                echo "<p style='font-size:20px;color:black;'>";
                echo __("It's an exciting time for rocket and space enthusiasts, with many breathtaking launches and stunning landings taking place each month. ","jot-plugin");
                $sm_url = "<a href='https://www.spacemash.co.uk'>" . __("SpaceMash.co.uk","jot-plugin") . "</a>";
                echo  sprintf(__(" %s uses the Joy Of Text Pro and Scheduler plugins to send SMS reminders for all live streamed launches. SpaceMash will send you a reminder just prior to the launch, including a link to a live stream so you can watch the impressive event.","jot-plugin"),$sm_url);
                echo  "<br><br>" . __(" Don't miss a live launch by signing up for SMS updates for only $15 per year.","jot-plugin");
                echo "</div>";
                echo "<br><br><p><p>";
                
              
                
           
              
                
            
        }
        
        public function write_page_footer() {
            
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<div class=''>" . __(" Joy Of Text Lite Version : ","jot-plugin") . Joy_Of_Text_Plugin()->version . " (". Joy_Of_Text_Plugin()->product .")" . "<br>" . _("For feedback and support, please send an email to") . " " . "<a href=\"mailto:jotplugin@gmail.com\">jotplugin@gmail.com</a></div>";
            echo "<br>";
            echo "<div class='jot-footer-upgrade'>" . __("Upgrade to the ","jot-plugin") . "<a href='https://www.getcloudsms.com/downloads/joy-of-text-pro-version-3/' target='_blank'>" .  __("Joy Of Text Pro Version","jot-plugin") . "</a>.";        
            
            $end_date = new DateTime("11/30/2021");
            $now = new DateTime();

            if ($now < $end_date) {
                echo __(" Get $10 off the Joy Of Text Pro using the discount code JOT10OFF before ","jot-plugin") . $end_date->format('jS F Y') . "." ;
            }
            echo "</div>";
        }
        
        
        /**
        * Register the settings within the Settings API.
        */
        public function register_settings () {
                           
                    register_setting( 'jot-plugin-settings-smsprovider', 'jot-plugin-smsprovider',array($this,'sanitise_sms_settings'));
                    register_setting( 'jot-plugin-settings-messages', 'jot-plugin-messages');
                    register_setting( 'jot-plugin-settings-group-list', 'jot-plugin-group-list');
                    register_setting( 'jot-plugin-settings-woocommerce', 'jot-plugin-woo-manager');
                    
        } // End register_settings()
        
        
        
        
        public function sanitise_sms_settings($input) {
            
            $debug = false;
            
                                    
            if ( empty( $_POST['_wp_http_referer'] ) ) {
		return $input;
            }
            parse_str( $_POST['_wp_http_referer'], $referrer );
            
            if (isset($referrer['tab'])) {                 
                   $tab = sanitize_text_field($referrer['tab']);
            } else {
                   return $input;
            }
            
            //Debug
            if ($debug) {
                $fields = Joy_Of_Text_Plugin()->settings->get_settings_fields($tab);
                echo "<br>Saved";
                var_dump($fields);
                echo "<br><br><hr><br><br>Input<br>";
                var_dump($input);
            }
            //Debug
            
                        
            if (isset($referrer['section'])) {                 
                   $sectiontab = $referrer['section'];
            } else {
                   return $input;
            }
            
            if (isset($referrer['subsection'])) {                 
                   $subsectiontab = $referrer['subsection'];
            } else {
                   $subsectiontab = "";
            }
                        
            $input = $input ? $input : array();
            
            // Trim Twilio details
            if (isset($input['jot-accountsid-twilio'])) {
                $input['jot-accountsid-twilio'] = trim(sanitize_text_field($input['jot-accountsid-twilio']));
            }
            if (isset($input['jot-authsid-twilio'])) {
                $input['jot-authsid-twilio'] = trim(sanitize_text_field($input['jot-authsid-twilio']));
            
            }
            
            // Get existing settings array
            $smsdetails       = get_option('jot-plugin-smsprovider') ? get_option('jot-plugin-smsprovider') : array() ;
            
            // If there are fields of type checkbox for this tab, that are not in input then set them to false            
            $fields = Joy_Of_Text_Plugin()->settings->get_settings_fields($tab);
            							   
            foreach ($fields as $key => $value) {
                if (isset($value['sectiontab'])) {
                    if ($value['type'] == 'checkbox' && $value['sectiontab'] == $sectiontab) {                          
                        if (array_key_exists($key, $input)) {
                            // Key found in input array                            
                        } else {
                            // Key not found in input array, so give it false values.
                            if (isset($value['options']) ) {
                                // Handle multi value checkboxes
                                if ($debug) {
                                    echo "<br>" . $value['subsection'] . "<>" .  $subsectiontab;
                                }
                                if ($value['subsection'] == $subsectiontab) {                                                                
                                    $input[$key] = array();  
                                }                                
                            } else {
                                // Not multi value - key not found so add it into the input array
                                $input[$key] = false;                            
                            }
                        }
                    }
                }
            }
            
            //Validate all input fields
            foreach ($input as $input_key => $input_value) {
                
                if ($debug) {
                    if (is_array($input[$input_key])) {
                        echo "<br>IN :" . $input_key . " " . print_r($input[$input_key],true);    
                    } else {
                        echo "<br>IN :" . $input_key . " " . $input_value;    
                    }  
                }
                
                if (isset($fields[$input_key]['validate'])) {
                    $validate_filter = $fields[$input_key]['validate'];
                } else {
                    $validate_filter = "";    
                }
                
                if (is_array($input[$input_key])) {
                    //Input is an array of values
                    $validated_subarray = array();
                    foreach ($input[$input_key] as $sub_array_key => $sub_array_value) {
                        $validated_subarray[$sub_array_key] = $this->validation_filter($validate_filter,$sub_array_value);                        
                    }
                    $input[$input_key] = $validated_subarray;
                } else {
                    //Not an array of values
                    $input[$input_key] = $this->validation_filter($validate_filter,$input_value);                    
                }
                
                if ($debug) {
                    if (is_array($input[$input_key])) {
                        echo "<br>OUT :" . print_r($input[$input_key],true);    
                    } else {
                        echo "<br>OUT :" . $input[$input_key];    
                    }                
                    echo "<br>======================";    
                }
                
            }            
            
            // Merge new settings with existing settings (priority goes to left hand array)
            if (is_array($input) && is_array($smsdetails)){
                $smsdetails_merge = $input + $smsdetails;
            } else {
                $smsdetails_merge = array();
                if (!is_array($input)) {                
                    add_settings_error('jot_settings_notice', 'jot_input_array_error', "Unable to save settings. (Error with input array)", "error");
                } else {
                    $smsdetails_merge = $input;
                }
                if (!is_array($smsdetails)) {
                    add_settings_error('jot_settings_notice', 'jot_provider_array_error', "Unable to save settings. (Error with provider array)", "error");
                }  else {
                    $smsdetails_merge = $smsdetails;
                }
            }
            
            //Debug
            if ($debug) {
                echo "<br><br><hr><br><br>Input after processing<br>"; 
                var_dump($input); 
                echo "<br><br><hr><br><br>Output<br>"; 
                var_dump($smsdetails_merge); 
                exit; 
            }
            //Debug
            
            return apply_filters('jot-sanitise-sms-settings',$smsdetails_merge,$input);
        }
        
        
        public function validation_filter($validate_filter,$input_value) {
            
            switch ( $validate_filter ) {
                    case 'email';
                        $input_value = sanitize_email($input_value);                        
                    break;
                    case 'url':                        
                        $input_value = sanitize_url($input_value);                          
                    break;
                    case 'textarea':                        
                        $input_value = sanitize_textarea_field($input_value);                          
                    break;    
                    default:
                        $input_value = sanitize_text_field($input_value);   
                    break;
            }
            
            return $input_value;
            
        }
        
        
        /**
        * Return marked up HTML for the header tag on the settings screen.
        */
        public function get_admin_header_html ( $sections, $title ) {
            $response = '';
            $defaults = array(
            'tag' => 'h2',
            'atts' => array( 'class' => 'jot-plugin-wrapper' ),
            'content' => $title
            );
            $args = $this->_get_admin_header_data( $sections, $title );
            $args = wp_parse_args( $args, $defaults );
            $atts = '';
            if ( 0 < count ( $args['atts'] ) ) {
                foreach ( $args['atts'] as $k => $v ) {
                    $atts .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
                }
            }
            $response = '<' . $args['tag']  . $atts . '>' . $args['content'] . '</' . $args['tag']  . '>' . "\n";
            return $response;
        } // End get_admin_header_html()
       
        /**
        * Return the current tab key.
        */
        private function _get_current_tab ( $sections = array() ) {
            if ( isset ( $_GET['tab'] ) ) {
                $response = sanitize_title_with_dashes( $_GET['tab'] );
            } else {
                if ( is_array( $sections ) && ! empty( $sections ) ) {
                list( $first_section ) = array_keys( $sections );
                $response = $first_section;
                } else {
                $response = '';
                }
            }
            return $response;
        } // End _get_current_tab()
        
        /**
        * Return the current subform key.
        */
        
        private function get_subform () {
            if ( isset ($_GET['subform']) ) {
                $response = sanitize_title_with_dashes( $_GET['subform'] );
            } else {
                $response = 'main';               
            }
            return $response;
        } // End _get_current_tab()
       
        
        /**
        * Return an array of data, used to construct the header tag.
        */
        private function _get_admin_header_data ( $sections, $title ) {
            $response = array( 'tag' => 'h2', 'atts' => array( 'class' => 'jot-plugin-wrapper' ), 'content' => $title );
                if ( is_array( $sections ) && 1 < count( $sections ) ) {
                $response['content'] = '';
                $response['content'] = '<a href="http://www.getcloudsms.com/lite-documentation/" target="_blank" class="nav-tab" id="jot-help" title="Help"><img src="' . plugins_url( 'images/Help.png', dirname(__FILE__) ) .  '" title="Help" id="jot-help-image"></a>';
                $response['atts']['class'] = 'nav-tab-wrapper';
                $tab = $this->_get_current_tab( $sections );
                foreach ( $sections as $key => $value ) {
                    $class = 'nav-tab';
                    if ( $tab == $key ) {
                    $class .= ' nav-tab-active';
                    }
                    $response['content'] .= '<a href="' . admin_url( 'admin.php?page=jot-plugin&tab=' . sanitize_title_with_dashes( $key ) ) . '" class="' . esc_attr( $class ) . '">' . esc_html( $value['tabname']) . '</a>';
                }
            }
            return (array)apply_filters( 'jot-plugin-get-admin-header-data', $response );
        } // End _get_admin_header_data()

} // End Class