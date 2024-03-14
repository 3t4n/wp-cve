<?php
/**
 * Wp Fusion helper.
 *
 * PHP version 5
 *
 * @category HELPER
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
if (! defined('ABSPATH') ) {
    exit;
}
if (! is_plugin_active('wp-fusion-lite/wp-fusion-lite.php') ) { 
    return;   
}
/**
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * SALicenseManager class
 */
 
class SaFusion
{
    /**
     * Construct function
     *
     * @return array
     */
    public function __construct()
    {    
       
        add_filter('wpf_crms', array( $this, 'setCrm' ), 10, 1);
        
    }
    
    /**
     * Set smsalert CRM
     *
     * @param array $data data.
     *
     * @return array CRMS
     */
    function setCrm($data)
    {
        $data['smsalert']     = 'WPFSMSALert';
        return $data;
    }
    
    
    
}
 
 
class WPFSMSALert
{
        
    /**
     * Construct function
     *
     * @return array
     */
    public function __construct()
    {    
        $this->slug = 'smsalert';
        $this->name = 'SMSAlert';
        $this->crm  = 'smsalert';
        add_filter('wpf_crm_post_data', array( $this, 'format_post_data' ));    
        if (is_admin() ) {
            new WPF_SmsAlert_Admin($this->slug, $this->name, $this);
        }
        
        
    }
    
    
    
    /**
     * Get contact id
     *
     * @param $name name.
     *
     * @return void
     */
    public function get_contact_id($name)
    {
        return false;
    }
    
    /**
     * Get  tags function
     *
     * @param $contact_id contact_id.
     *
     * @return void
     */
    public function get_tags( $contact_id )
    {        
        return true;
    }
    
    /**
     * Sync  tags function
     *
     * @return void
     */
    public function sync_tags()
    {
        $available_tags = array();
        return $available_tags;
    }    
    
    /**
     * Performs initial sync once connection is configured
     *     
     * @return bool
     */
    public function sync()
    { 
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();        
        if ($islogged ) {            
            $this->sync_crm_fields();
            do_action('wpf_sync');
            return true;
        }
        return false;
    }
    
    /**
     * Sync crm fields function
     *
     * @return void
     */
    public function sync_crm_fields()
    {
        
        $smsalert_fields = array();        
        $smsalert_fields['name'] = array(
        'crm_label' => 'Name',
        'crm_field' => 'NAME'
        );
        $smsalert_fields['number'] = array(
        'crm_label' => 'Phone',
        'crm_field' => 'billing_phone'
        );
        $crm_fields = array();
        foreach ( $smsalert_fields as $index => $data ) {
            $crm_fields[ $data['crm_field'] ] = $data['crm_label'];
        }
        asort($crm_fields);
        wp_fusion()->settings->set('crm_fields', $crm_fields);
        return $crm_fields;
    }
    
    /**
     * Add contact function
     *
     * @param $data data.
     *
     * @return void
     */
    public function add_contact( $data )
    {        
        $group_name = wpf_get_option('group_auto_sync');
        $name  = $data['NAME']; 
        $phone =  !empty($data['billing_phone'])? $data['billing_phone'] : "";
        if (!empty($name) && !empty($phone)) {
            $datas[] = array('person_name'=>$name,'number'=>$phone);
            $response = SmsAlertcURLOTP::createContact($datas, $group_name);
        }        
    }
    
    /**
     * Update contact
     *
     * @param $contact_id contact_id.
     * @param $data       data.
     *
     * @return bool
     */
    public function update_contact( $contact_id, $data )
    {
        return true;
    }
}
//new WPFSMSALert();
new SaFusion();
/**
 * PHP version 5
 *
 * @category Helper
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * WPF_SmsAlert_Admin class
 */
class WPF_SmsAlert_Admin
{
    private $slug;
    private $name;
    private $crm;

    /**
     * Get things started
     *
     * @param $slug slug.
     * @param $name name.
     * @param $crm  crm.
     *
     * @return void
     */
    public function __construct( $slug, $name, $crm )
    {
        $this->slug = 'smsalert';
        $this->name = 'SMSAlert';
        $this->crm  = 'smsalert';
        add_filter('wpf_configure_settings', array( $this, 'register_connection_settings' ), 10, 2);
        add_action('show_field_smsalert_header_begin', array( $this, 'show_field_smsalert_header_begin' ), 10, 2);
        add_action('wp_ajax_wpf_test_connection_smsalert', array( $this, 'test_connection' ));
        if (wpf_get_option('crm') == 'smsalert' ) {
            $this->init();
        }
    }

    /**
     * Hooks to run when this CRM is selected as active
     *
     * @return void
     */
    public function init()
    {         
        add_filter('wpf_initialize_options_contact_fields', array( $this, 'add_default_fields' ), 10);
        add_filter('wpf_configure_settings', array( $this, 'register_settings' ), 10, 2);
        add_filter('wpf_meta_fields', array( $this, 'prepare_meta_fields' ), 70);
    }

    /**
     * Add CID field for syncing
     *
     * @param $meta_fields meta_fields.
     *
     * @return void
     */
    public function prepare_meta_fields( $meta_fields )
    {       
        return $meta_fields;
    }


    /**
     * Loads MailChimp connection information on settings page
     *
     * @param $settings settings.
     * @param $options  options.
     *
     * @return void
     */
    public function register_connection_settings( $settings, $options )
    {        
        $new_settings = array();
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
             $new_settings['smsalert_header'] = array(
            'title'   => __('SMSAlert Configuration', 'sms-alert'),
            'type'    => 'heading',
            'section' => 'setup',
             ); 
             if (!$islogged) {
                 $new_settings['smsalert_default_list'] = array(
                 'type'        => 'text',        
                 'section'     => 'setup',
                 'class'       => 'api_key hide',
                 'desc'        => __('<a href="'. get_admin_url() .'admin.php?page=sms-alert" target="_blank">Login to SMS Alert</a>  to configure SMS Notifications', 'sms-alert'),
                 );            
             } else {
                 $new_settings['group_auto_sync'] = array(
                 'title'        => __('Select SMSAlert Group', 'sms-alert'),                
                 'type'         => 'select',
				 'placeholder' => 'Select Group',
                 'section'     => 'setup',
                 'id'           => 'group_auto_sync',    
                 'desc'        =>  (empty($this->getGroupList())) ?'<a href="#" id="create_wf_group">Create Group </a>' : "",
                 'choices' => (!empty($this->getGroupList())) ? $this->getGroupList() : array(""=>"Select Group"), 
                 );
                
                 $new_settings['smsalert_key'] = array(
                 'title'       => __('Connect to SMSAlert', 'sms-alert'),            
                 'type'        => 'api_validate',
                 'section'     => 'setup',
                 'class'       => 'api_key hide',
                 'post_fields' => array( 'smsalert_key', 'group_auto_sync' ),
                 );                
             }                    
             $settings = wp_fusion()->settings->insert_setting_after('crm', $settings, $new_settings);
             return $settings;
    }
    
    /**
     * Loads MailChimp specific settings fields
     *
     * @return void
     */
    protected function getGroupList()
    {
        $obj=array();
        $groups = (array)json_decode(SmsAlertcURLOTP::groupList(), true);            
        if (!empty($groups['status']) && "success" === $groups['status'] )
		{			
			foreach ( $groups['description'] as $group ) {
				$obj[$group['Group']['name']] = $group['Group']['name']; 
			} 
		}
        return $obj;
    }  


    /**
     * Loads MailChimp specific settings fields
     *
     * @param $settings settings.
     * @param $options  options.
     *
     * @return void
     */
    public function register_settings( $settings, $options )
    {
        unset($settings['assign_tags']);
        unset($settings['login_sync']);
        unset($settings['login_meta_sync']);
        unset($settings['mc_default_list']);
        return $settings;
    }

    /**
     * Puts a div around the MailChimp configuration section so it can be toggled
     *
     * @param $id    id.
     * @param $field field.
     *
     * @return void
     */
    public function show_field_smsalert_header_begin( $id, $field )
    {    
        echo '</table>';
        $crm = wpf_get_option('crm');
        echo '<div id="' . esc_attr($this->slug) . '" class="crm-config ' . ( $crm == false || $crm != $this->slug ? 'hidden' : 'crm-active' ) . '" data-name="' . esc_attr($this->name) . '" data-crm="' . esc_attr($this->slug) . '">';
    }
    
    /**
     * Test connection function
     *
     * @param $options options.
     *
     * @return void
     */
    public function add_default_fields( $options )
    {
        if ($options['connection_configured'] == true ) {
            $smsalert_fields = array();        
            $smsalert_fields['name'] = array(
            'crm_label' => 'Name',
            'crm_field' => 'NAME'
            );
            $smsalert_fields['number'] = array(
            'crm_label' => 'Phone',
            'crm_field' => 'billing_phone'
            );    
            foreach ( $options['contact_fields'] as $field => $data ) {
                if (isset($smsalert_fields[ $field ]) && empty($options['contact_fields'][ $field ]['crm_field']) ) {
                    $options['contact_fields'][ $field ] = array_merge($options['contact_fields'][ $field ], $smsalert_fields[ $field ]);
                }
            }
        }
        return $options;
    }
    
    /**
     * Test connection function
     *
     * @return void
     */
    function test_connection()
    { 
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();    
        $options                          = array();
        
        if (false == $options['connection_configured'] && $islogged) {
            $options['connection_configured'] = true;        
            $options['crm']                   = 'smsalert';                
            $options['group_name']            = sanitize_text_field($_POST['group_auto_sync']);    
            wp_fusion()->settings->set_multiple($options);
            wp_send_json_success();
        }
        exit();
    }
    
}
