<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dcms_Sab_Admin_Form{

	private $options;


	public function dcms_sab_create_admin_form(){

		$this->options = get_option( 'dcms_sab_bd_options' );

		?>
		<div class="wrap">

			<h2><?php _e('Simple Author Bio','simple-author-bio') ?></h2>
			
			<form action="options.php" method="post">
				<?php
					
					settings_fields('dcms_sab_options_group');
					do_settings_sections('dcms_sab_options');

					submit_button();
				?>
			</form>
		</div>

		<?php

	}

	
	public function dcms_sab_admin_init(){

		register_setting('dcms_sab_options_group', 
							'dcms_sab_bd_options');
		
		// Basic
		add_settings_section('dcms_sab_basic_section', 
							__('Basic Configuration','simple-author-bio'), 
							[$this,'dcms_sab_section_callback_basic'], 
							'dcms_sab_options' );

		// Advanced
		add_settings_section('dcms_sab_advanced_section', 
							__('Advanced Configuration','simple-author-bio'), 
							[$this, 'dcms_sab_section_callback_advanced'], 
							'dcms_sab_options' );

		// Fields
		$this->dcms_sab_add_setting_field( __('Hide author without description','simple-author-bio'), 
											'dcms_sab_chk_hide_author', 
											'dcms_sab_basic_section',
											__('You can fill this info in your profile','simple-author-bio'));

		$this->dcms_sab_add_setting_field( __('Show social network box','simple-author-bio'), 
											'dcms_sab_chk_show_social', 
											'dcms_sab_basic_section',
											__('social icons in front-end','simple-author-bio'));

		$this->dcms_sab_add_setting_field( __('Show link view all posts','simple-author-bio'), 
											'dcms_sab_chk_show_view_all', 
											'dcms_sab_basic_section',
											__('author\'s posts link in front-end','simple-author-bio'));
		
		$this->dcms_sab_add_setting_field( __('Load FontAwesome','simple-author-bio'), 
											'dcms_sab_chk_load_fontawesome', 
											'dcms_sab_advanced_section',
											__('If your theme loads FontAwesome uncheck this','simple-author-bio'));
		
		$this->dcms_sab_add_setting_field( __('Load Default CSS','simple-author-bio'), 
											'dcms_sab_chk_load_css', 
											'dcms_sab_advanced_section',
											__('Default CSS file plugin','simple-author-bio'));

	}


	public function dcms_sab_check_fields( $args ){

		$field 		 = $args[0];
		$msg		 = $args[1];

		$field_value = isset($this->options[$field]);

		echo '<input id="'.$field.'" name="dcms_sab_bd_options['.$field.']" '.checked($field_value, true, false).' type="checkbox" />';
		
		if ( !empty($msg) ) echo ' <i>'.$msg.'</i>';

	}


	public function dcms_sab_add_setting_field( $field_text , $field_name , $section , $message = '', $type = 'check'){

		add_settings_field('dcms_sab_fields_'.$field_name, 

							__( $field_text ,'simple-author-bio'), 

							[$this,'dcms_sab_'.$type.'_fields'], 
							'dcms_sab_options', 
							$section,
							[ $field_name, $message ]);
	}


	public function dcms_sab_section_callback_advanced(){
		echo '<hr/>';
		echo '<span><span class="dashicons dashicons-arrow-right"></span>'.sprintf(__('You can modify the template: %s', 'simple-author-bio'),'  /wp-content/plugins/dcms_simple_author_bio/template/box-author-bio.txt').'</span>';
	}

	public function dcms_sab_section_callback_basic(){
		echo '<hr/>';
		echo '<span><span class="dashicons dashicons-arrow-right"></span>'.__('You can see additional social networks in your profile', 'simple-author-bio').'</span>';
	}

}

