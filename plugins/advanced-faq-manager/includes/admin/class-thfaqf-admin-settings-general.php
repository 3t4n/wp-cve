<?php

if(!defined('WPINC')){	die; }

if(!class_exists('THFAQF_Admin_Settings_General')):

class THFAQF_Admin_Settings_General extends THFAQF_Admin_Settings{
	protected static $_instance = null;

	public function __construct() {
		parent::__construct();
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function render_page(){
		$this->output_tabs();
		$this->output_content();
    }

    private function output_tabs(){
    	?>
    	<h1 class="thpladmin-page-title">General Settings</h1>
    	<?php
    }

    private function output_content(){
		if(isset($_POST['reset_settings']))
			$this->reset_settings();	
			
		if(isset($_POST['save_settings']))
			$this->save_settings();
		
		$eurl = get_admin_url();
		$settings = THFAQF_Utils::get_faq_settings(false);
		$enable_icon_options = isset($settings['enable_disable_title_icons']) ? $settings['enable_disable_title_icons']: false;
		$thfaq_custom_css = isset($settings['enable_thfaq_custom_css']) ? $settings['enable_thfaq_custom_css']: false;
        $enable_icon_options = $enable_icon_options == false ? 'thfaq-hide-expndicon' : '';
		$thfaq_custom_css = $thfaq_custom_css == false ? 'thfaqf-disabled-panel' : '';
		?>            
        <div style="padding-left: 30px;">               
		    <form id="general_settings_form" method="post" action="">
		    	<?php $this->wp_verify_nonce();?>
                <table class="form-table thfaq-form-table" style="width: 100%;"><tbody>
                	<?php $this->render_form_section_separator($this->settings_fields['section_accordion_settings']); ?>
                    <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['accordion_display_mode'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['faq_border_radius'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		             <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['expand_style'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['open_multiple_faqs'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['show_updated_date'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		               <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['like_and_dislike_option'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['enable_disable_comment'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		            <tr class="">
		            	<?php
		            	$this->render_form_field_element($this->settings_fields['enable_disable_title_icons'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		              <tr class="thfaq-icon-poss <?php echo $enable_icon_options; ?>">
		            	<?php
						$this->render_form_field_element($this->settings_fields['icon_picker'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
                    <?php 
                    	$this->render_form_section_separator($this->settings_fields['section_display_settings']);
                    	$this->render_form_section_subtitle($this->settings_fields['display_settings_subtitle']);
                    ?>
                    <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['title_color'], $settings, $this->cell_props_CPL);
		            	$this->render_form_field_element($this->settings_fields['title_bg_color'], $settings, $this->cell_props_CPR);
						?>
		            </tr>
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['content_color'], $settings, $this->cell_props_CPL);
		            	$this->render_form_field_element($this->settings_fields['content_bg_color'], $settings, $this->cell_props_CPR);
						?>
		            </tr>
		            <tr>
		            	<?php
		            	$this->render_form_field_element($this->settings_fields['title_active_color'], $settings, $this->cell_props_CPL); 
						$this->render_form_field_element($this->settings_fields['expnd_icon_color'], $settings, $this->cell_props_CPL);
						?>
		            </tr>
		            <?php 
                    	$this->render_form_section_separator($this->settings_fields['section_display_tab_settings']); ?>
                    <tr>
		            <?php
						$this->render_form_field_element($this->settings_fields['enable_search_option_faq_layout'], $settings, $this->cell_props_CPL);
					?>
		            </tr>
		            <?php
                    	$this->render_form_section_subtitle($this->settings_fields['display_settings_tab_subtitle']);
                    ?>
		            <tr>
		            	<?php
		            	$this->render_form_field_element($this->settings_fields['tab_active_color'], $settings, $this->cell_props_CPL); 
		            	$this->render_form_field_element($this->settings_fields['tab_bg_color'], $settings, $this->cell_props_CPR);
						?>
		            </tr>
		            <?php $this->render_form_section_separator($this->settings_fields['section_social_share_settings']); ?>
                    <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['enable_share_button'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['social_share_title'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['social_share_options'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>	
		            <tr>
		            	<?php
						$this->render_form_field_element($this->settings_fields['enable_thfaq_custom_css'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr> 
		            <tr class="thfaqf-additonal-css-wrapper <?php echo $thfaq_custom_css; ?>">
		            	<?php
						$this->render_form_field_element($this->settings_fields['thfaq_custom_css'], $settings, $this->cell_props_L);
		            	$this->render_form_element_empty_cell();
						?>
		            </tr>    
                </tbody></table> 
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes">
                    <input type="submit" name="reset_settings" class="button" value="Reset to default" onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            	<p class="mt-20">Here you can <a href="<?php echo $eurl.'export.php'; ?>"><i>Export</i></a>/ <a href="<?php echo $eurl.'import.php'; ?>"> <i>Import</i></a> FAQs</p>
            </form>
    	</div>       
    	<?php
	}

    private function wp_verify_nonce() {
    	?>
    	<input type="hidden" name="wp_thfaqgs_nonce" value="<?php echo wp_create_nonce('thfaqgs_nonce'); ?>">
        <input type="hidden" name="wp_thfaqrs_nonce" value="<?php echo wp_create_nonce('thfaqrs_nonce'); ?>">
    	<?php
    }

    public function save_settings(){
		if (!isset( $_POST['wp_thfaqgs_nonce']) || ! wp_verify_nonce($_POST['wp_thfaqgs_nonce'], 'thfaqgs_nonce')) {
    		echo $responce = '<div class="thfaq_update_message failed"><p><b>Sorry, your nonce did not verify.</b></p></div>';
			exit;
        }else {
			$settings = array();
			foreach( $this->settings_fields as $key => $field ){
				$type = isset($field['type']) ? $field['type'] : 'text';

				if($type != 'separator' && $type != 'subtitle' && isset($_POST[$key])){
					$settings[$key] = self::sanitize_field_value($_POST, $key, $type);
				}
			}

			$result = THFAQF_Utils::save_faq_settings($settings);
			if($result=='true'){
				?>
			 	<div class="thfaq_update_message updated">
			 		<p><b>Your changes were saved.</b></p>
			 	</div> 
			 	<?php
			}else{
				?>
			 	<div class="thfaq_update_message failed">
			 		<p><b>Your changes were not saved due to an error(or you made none!)</b></p>
			 	</div>
			 	<?php
			}
		}
	}

	public static function sanitize_field_value($posted, $key, $type){
		$posted_data = isset($posted[$key]) ? $posted[$key] : false;
		$value = is_array($posted_data) ? implode(',', $posted_data) : $posted_data;
		$value = stripslashes($value);

		switch($type) {
			case 'text':
			case 'select':
				$value = sanitize_text_field($value); 
				break;
			case 'textarea':
				$value = sanitize_textarea_field($value);
				break;		
			case 'colorpicker':
				$value = sanitize_hex_color($value);
				break;
			case 'radio_btn': 
			case 'iconpicker': 
				$value = sanitize_text_field($value);
				break;	
			case 'number':
				$value = is_numeric($value) ? absint($value) : '';
				break;
			case 'switch':
			case 'checkbox':
				$value = filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
				break;
			default:
				$value = sanitize_text_field($value); 
				break;
		}
		return $value;
	}

	public function reset_settings(){
		if (!isset( $_POST['wp_thfaqrs_nonce']) || ! wp_verify_nonce($_POST['wp_thfaqrs_nonce'], 'thfaqrs_nonce')) {
			echo $responce = '<div class="thfaq_update_message failed"><p><b>Sorry, your nonce did not verify.</b></p></div>';
			exit;
        }else {
			THFAQF_Utils::delete_faq_settings();
			$settings = THFAQF_Utils::prepare_default_settings();
			$result = THFAQF_Utils::save_faq_settings($settings);

			if($result=='true'){
				?>
				<div class="thfaq_updated">
					<p><b>Settings successfully reset.</b></p>
				</div> 
				<?php
			}else{
				?>
				<div class="thfaq_updated_failed">
					<p><b>Error restoring, Please try again.</b></p>
		        </div>
		        <?php
			}
		}
	}
}

endif;

