<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'ni_enquiry_setting' ) ) {
	class ni_enquiry_setting{
		
		
		
		function __construct() {
			 add_action( 'admin_menu', array( $this, 'add_setting_page' ) );
			 add_action( 'admin_init', array( $this, 'admin_init' ),110 );
			 add_action( 'admin_init', array( $this, 'admin_init_save'),100 );
		}
		function add_setting_page(){
			add_submenu_page( "ni-enquiry-dashboard",   __(  'Setting', 'niwoope') , __(  'Setting', 'niwoope'), 'manage_options', 'ni-enquiry-setting', array( $this, 'setting_page' ) );
		}
		function admin_init_save(){
			if (isset($_REQUEST["ni_enquiry_option"])){
				update_option('ni_enquiry_option',$_REQUEST["ni_enquiry_option"]);
				
			}
		}
		function setting_page(){
			    // Set class property
			$this->options = get_option( 'ni_enquiry_option' );
			//$this->options = get_option( 'invoice_setting_option' );
			?>
             <div class="container-fluid">
        		<div id="niwoope">
                	<div class="card" style="max-width:99%">
                      <div class="card-header bg-rgba-cyan-strong box1">
                        <?php esc_html_e("Setting","niwoope"); ?>
                      </div>
                      <div class="card-body">
                       	<div class="wrap">
							<?php //screen_icon(); ?>
                          <!--  <h2>My Settings</h2>           -->
                            <form method="post">
                            <?php
                                // This prints out all hidden setting fields
                                settings_fields( 'ni_enquiry_option_group' );   
                                do_settings_sections( 'my-setting-admin' );
                                //submit_button(); 
								
                            ?>
                            <input type="submit" class="btn btn-rgba-cyan-strong btn-lg" value="<?php esc_html_e("Save ","niwoope"); ?>" />
                            </form>
                        </div>
                      </div>
                    </div>
                </div>
             </div>   
			
			<?php
		}
		function admin_init(){
			register_setting(
				'ni_enquiry_option_group', // Option group
				'ni_enquiry_option', // Option name
				array( $this, 'sanitize' ) // Sanitize
			);
			
			add_settings_section(
				'setting_section_id', // ID
				 esc_html__(  'Enquiry Settings', 'niwoope') ,
				array( $this, 'print_section_info' ), // Callback
				'my-setting-admin' // Page
			);
			
		
			/*Email Variable*/
			/*To Email*/
			add_settings_field(
				'ni_to_email', 
				 esc_html__(  'To Email Address', 'niwoope') ,
				array( $this, 'add_to_email' ), 
				'my-setting-admin', 
				'setting_section_id'
			);
			/*cc Email*/
			add_settings_field(
				'ni_cc_email', 
				 esc_html__( 'CC Email Address', 'niwoope') ,
				array( $this, 'add_cc_email' ), 
				'my-setting-admin', 
				'setting_section_id'
			);
			/*Subject Line*/
			add_settings_field(
				'ni_subject_line', 
				 esc_html__( 'Subject Line', 'niwoope') ,
				array( $this, 'add_subject_line' ), 
				'my-setting-admin', 
				'setting_section_id'
			);
			/*Subject Line*/
		/*	add_settings_field(
				'ni_subject_line', 
				'Subject Line', 
				array( $this, 'add_subject_line' ), 
				'my-setting-admin', 
				'setting_section_id'
			);
			*/
			/*From Email*/
			add_settings_field(
				'ni_from_email', 
				 esc_html__( 'From Email Address', 'niwoope') ,
				array( $this, 'add_from_email' ), 
				'my-setting-admin', 
				'setting_section_id'
			);
			
			/*From Name*/
			add_settings_field(
				'ni_enquiry_from_name', 
				 esc_html__( 'From Name', 'niwoope') ,
				array( $this, 'add_enquiry_from_name' ), 
				'my-setting-admin', 
				'setting_section_id'
			);
			
			/*enquiry_button_text*/
			add_settings_field(
				'ni_enquiry_button_text', 
				 esc_html__( 'Enquiry Button Text', 'niwoope') ,
				array( $this, 'add_enquiry_button_text' ), 
				'my-setting-admin', 
				'setting_section_id'
			); 
			/*Email To Customer*/
			add_settings_field(
				'enable_email_to_customer', 
				 esc_html__( 'Email to customer', 'niwoope') ,
				array( $this, 'enable_email_to_customer' ), 
				'my-setting-admin', 
				'setting_section_id'
			);  
			
			add_settings_field(
				'ni_thank_you_message', 
				 esc_html__( 'Thank you message', 'niwoope') ,
				array( $this, 'add_thank_you_message' ), 
				'my-setting-admin', 
				'setting_section_id'
			); 
			
			
			/*Whats apps*/
			add_settings_field(
				'enable_whatsapp_enquiry', 
				 esc_html__( 'Enable Whatsapp Enquiry', 'niwoope') ,
				array( $this, 'enable_whatsapp_enquiry' ), 
				'my-setting-admin', 
				'setting_section_id'
			);  
			
			add_settings_field(
				'ni_whatsapp_button_text', 
				 esc_html__( 'Whatsapp Button Text', 'niwoope') ,
				array( $this, 'add_whatsapp_button_text' ), 
				'my-setting-admin', 
				'setting_section_id'
			); 
			
			add_settings_field(
				'ni_whatsapp_no', 
				 esc_html__( 'Whatsapp Number', 'niwoope') ,
				array( $this, 'add_whatsapp_no' ), 
				'my-setting-admin', 
				'setting_section_id'
			); 
			
			
			 
		}
		
		function add_to_email(){
			printf(
				'<input type="text"  id="ni_to_email" name="ni_enquiry_option[ni_to_email]" value="%s" size="40"/>',
				isset( $this->options['ni_to_email'] ) ? esc_attr( $this->options['ni_to_email']) : ''
				//esc_attr( $this->options['name'])
			);
		}
		function add_cc_email(){
			printf(
				'<input type="text" id="add_cc_email" name="ni_enquiry_option[add_cc_email]" value="%s" size="40" />',
				isset( $this->options['add_cc_email'] ) ? esc_attr( $this->options['add_cc_email']) : ''
				//esc_attr( $this->options['name'])
			);
		}
		function add_subject_line(){
			printf(
				'<input type="text" id="ni_subject_line" name="ni_enquiry_option[ni_subject_line]" value="%s"  size="40" />',
				isset( $this->options['ni_subject_line'] ) ? esc_attr( $this->options['ni_subject_line']) : ''
				//esc_attr( $this->options['name'])
			);
			
		}
		function add_from_email(){
			printf(
				'<input type="text" id="ni_from_email" name="ni_enquiry_option[ni_from_email]" value="%s" size="40" />',
				isset( $this->options['ni_from_email'] ) ? esc_attr( $this->options['ni_from_email']) : ''
				//esc_attr( $this->options['name'])
			);			
		}
		function add_enquiry_from_name(){
			printf(
				'<input type="text" id="enquiry_from_name" name="ni_enquiry_option[enquiry_from_name]" value="%s" size="40" />',
				isset( $this->options['enquiry_from_name'] ) ? esc_attr( $this->options['enquiry_from_name']) : ''
				//esc_attr( $this->options['name'])
			);		
		}
		function add_enquiry_button_text(){
			printf(
				'<input type="text" id="ni_enquiry_button_text" name="ni_enquiry_option[ni_enquiry_button_text]" value="%s" size="40" />('. esc_html__( 'change the enquiry button text like quotation, inquiry, enquiry etc', 'niwoope').')',
				isset( $this->options['ni_enquiry_button_text'] ) ? esc_attr( $this->options['ni_enquiry_button_text']) :   esc_html__( 'Enquiry', 'niwoope') 
				
			);	
		}
		function enable_email_to_customer() {
			$html = '<input type="checkbox" id="enable_email_to_customer" name="ni_enquiry_option[enable_email_to_customer]" value="1"' . checked(isset( $this->options['enable_email_to_customer'] ), true, false) . '/>';
			$html .= '<label for="enable_email_to_customer"> '.esc_html__(  'send an email enquiry form copy to customer ', 'niwoope').' </label>';
			echo $html;
	
		}
		/*Whatsapp*/
		function enable_whatsapp_enquiry(){
			$html = '<input type="checkbox" id="enable_whatsapp_enquiry" name="ni_enquiry_option[enable_whatsapp_enquiry]" value="1"' . checked(isset( $this->options['enable_whatsapp_enquiry'] ), true, false) . '/>';
			$html .= '<label for="enable_whatsapp_enquiry"> '.esc_html__(  'Show Whatsapp button on product detail page', 'niwoope').' </label>';
			echo $html;
		}
		function add_whatsapp_button_text(){
			printf(
				'<input type="text" id="ni_whatsapp_button_text" name="ni_enquiry_option[ni_whatsapp_button_text]" value="%s" size="40" />('.  esc_html__( 'change the whatsapp button text like whatsapp me, whatsapp, say hi etc.', 'niwoope'). ')',
				isset( $this->options['ni_whatsapp_button_text'] ) ? esc_attr( $this->options['ni_whatsapp_button_text']) : esc_html__( 'Whatsapp Me!', 'niwoope')  
				
			);	
		}
		function add_whatsapp_no(){
			printf(
				'<input type="text" id="ni_whatsapp_no" name="ni_enquiry_option[ni_whatsapp_no]" value="%s" size="40" />('. esc_html__( 'Enter your Whatsapp mobile no with country code', 'niwoope').' )',
				isset( $this->options['ni_whatsapp_no'] ) ? esc_attr( $this->options['ni_whatsapp_no']) : ''
				
			);	
		}
		function add_thank_you_message() {
			$us_partners_desc = $this->options;
			//print_r($this->options);
			//http://wp-kama.ru/filecode/wp-includes/class-wp-editor.php
			echo wp_editor( isset($us_partners_desc["ni_thank_you_message"])?stripslashes( $us_partners_desc["ni_thank_you_message"]):'', 'ni_thank_you_message', 
			array(
				'textarea_name' => 'ni_enquiry_option[ni_thank_you_message]',
			//	'textarea_rows' =>50,
			//	'editor_height' =>10 
				//'width' => 50
				) 
			 );
			//	die;
		}
		
		function print_section_info(){
    		
			 esc_html_e( 'Enter your settings below', 'niwoope');
		}
		function sanitize( $input ){
			if( !is_numeric( $input['id_number'] ) )
				$input['id_number'] = '';  
		
			if( !empty( $input['title'] ) )
				$input['title'] = sanitize_text_field( $input['title'] );
				
			if( !empty( $input['color'] ) )
				$input['color'] = sanitize_text_field( $input['color'] );
			return $input;
		}
	}
}
?>