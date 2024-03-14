<?php
if ( isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit(esc_html__('Please don\'t access this file directly.', 'WP2SL'));
}

class OEPL_Lead_Widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
			'OEPL_Lead_Widget', 
			__('SugarCRM Lead Form', 'OEPL_Lead_Widget'), 
			array( 'description' => __( 'This Widget will submit data into your SugarCRM Lead module.', 'WPBeginner Widget' ), ) 
		);
	}

	public function widget( $args, $instance ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT hidden, data_type, required, wp_custom_label, wp_meta_label, field_type, wp_meta_key, field_value, field_name, hidden_field_value FROM ".OEPL_TBL_MAP_FIELDS." WHERE is_show ='%s' ORDER BY display_order ASC", 'Y' );
		$RS = $wpdb->get_results($query,ARRAY_A);
		
		if(isset($instance['OEPL_Widget_Title'])){
			$title = apply_filters( 'widget_title', $instance['OEPL_Widget_Title'] );
		}

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			$Custom_CSS = get_option("OEPL_Form_Custom_CSS");
		
		if($Custom_CSS && $Custom_CSS != ''){
			echo '<style type="text/css">'.$Custom_CSS.'</style>';
		}

		wp_register_script('admin_js', OEPL_PLUGIN_URL.'js/admin.js',array('jquery'), false, true);
		wp_enqueue_script('admin_js');

		echo "<p><div class='LeadFormMsg'></div></p>";
		echo "<form id='OEPL_Widget_Form' method='POST' enctype='multipart/form-data'>";
		echo "<input type='hidden' value='".wp_create_nonce( 'upload_thumb' )."' name='_nonce' />";
  		echo '<input type="hidden" name="action" id="action" value="WidgetForm">';
		foreach ($RS as $module) {
			if($module['hidden'] === 'N') {
				### Add Date picker Class if filed type match
				switch ($module['data_type']) {
					case 'date':
						$JQclass = 'DatePicker nonHidden';
						$readonly = 'readonly';
						break;
					case 'datetimecombo':
						$JQclass = 'DateTimePicker nonHidden';
						$readonly = 'readonly';
						break;
					case 'file':
						$JQclass = 'files nonHidden';
						$readonly = '';
						break;
					default:
						$JQclass = 'nonHidden';
						$readonly = '';
						break;
				}

				### Add required class if reqiured is true
				if($module['required'] === 'Y'){
					$JQclass .= ' LeadFormRequired';
					$LabelAsterisk = ' <span class="required_cls">*</span>';				
				} else {
					$LabelAsterisk = '';
				}
				### Display Custom label if is set
				if($module['wp_custom_label'] && $module['wp_custom_label'] != ''){
					$label = $module['wp_custom_label'].$LabelAsterisk;
				} else {
					$label = $module['wp_meta_label'].$LabelAsterisk;
				}
				echo "<p>";
				echo "<label><strong>".$label." :</strong></label><br>";
				echo WP2SL_getHTMLElement($module['field_type'],$module['wp_meta_key'],$module['field_value'],$module['field_value'],'',$JQclass,$readonly);
				echo "</p>";
			} else {
				echo '<input type="hidden" class="LeadFormEach" name="'.$module['wp_meta_key'].'" value="'.$module['hidden_field_value'].'" />';
			}
		}

		$captchaSettings = get_option('OEPL_Captcha_status');
		$oepl_sel_captcha = get_option('OEPL_Select_Captcha');

		wp_localize_script( 'admin_js', 'obj_captcha', 
			array(
				'wp2sl_captcha_v2' => false,
			)
		);

		if($captchaSettings === 'Y') {
			if($oepl_sel_captcha === 'google') {
				$google_url = apply_filters( 'v2_reCaptcha', sprintf( esc_url( 'https://www.%s/recaptcha/api.js', 'async defer' ), 'google.com' ) );

				wp_localize_script( 'admin_js', 'obj_captcha', 
					array(
						'reCAPTCHA_key' => get_option('OEPL_RECAPTCHA_SITE_KEY'),
						'wp2sl_captcha_v2' => true,
					)
				);
				
				wp_register_script('v2_captcha', $google_url, array(), false, false);
				wp_enqueue_script('v2_captcha');
							  
				echo '<p><div class="g-recaptcha" id="grecaptcha" data-sitekey="'.get_option('OEPL_RECAPTCHA_SITE_KEY').'"></div></p>';	
			
			} else {
				$sess_time = time();
				echo '<p class="OEPL_captcha"><img src="'.OEPL_PLUGIN_URL.'captcha.php?t='.$sess_time.'" title="captcha" class="OEPL_captcha_img"/><img src="'.OEPL_PLUGIN_URL.'image/reload_captcha.png" title="Reload Captcha" class="OEPL_repload_captcha"/></p>';
			
				echo "<p><label><strong>".__('Enter Verification Code :', 'WP2SL')."</strong></label><input type='text' name='captcha' id='OEPL_CAPTCHA' maxlength='5' class='LeadFormRequired'/></p>";
			}
		}
		
		if(count($RS) > 0 ) {
			echo "<p><input type='submit' name='submit' value='Submit' id='WidgetFormSubmit'></p>";
		}
			
		echo "</form>";
		echo $args['after_widget'];
	}
			
	public function form( $instance ) {
		global $wpdb;
		$title = isset($instance['OEPL_Widget_Title']) ? $instance['OEPL_Widget_Title'] : __('New title', 'WP2SL');

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'OEPL_Widget_Title' ); ?>"><?php _e( 'Title:', 'WP2SL' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'OEPL_Widget_Title' ); ?>" name="<?php echo $this->get_field_name( 'OEPL_Widget_Title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<?php
		$query = $wpdb->prepare( "SELECT field_name, data_type, wp_meta_label, field_type, field_value  FROM ".OEPL_TBL_MAP_FIELDS." WHERE is_show='%s' AND hidden='%s', AND custom_field='%s' ORDER BY display_order ASC", 'Y', 'Y', 'N' );
		$RS = $wpdb->get_results($query,ARRAY_A);
		if(count($RS) > 0) {
			?>
			<div align="center">
				<h4><?php echo esc_html__( 'Hidden Attributes', 'WP2SL' ); ?></h4>
			</div>
			<hr />
			<?php
		}
		foreach($RS as $field){
			$FieldVal = isset($instance[$field['field_name']]) ? $instance[$field['field_name']] : '';

			if($field['data_type'] === 'date'){
				$JQclass = 'DatePicker widefat';
				$extra = 'readonly';
			} else if($field['data_type'] === 'datetimecombo') {
				$JQclass = 'DateTimePicker widefat';
				$extra = 'readonly';
			} else {
				$extra = '';
				$JQclass = 'widefat';
			}
			echo "<p>";
			echo "<label>".$field['wp_meta_label']."</label>";
			echo WP2SL_getHTMLElement($field['field_type'],$this->get_field_name($field['field_name']),$field['field_value'],$FieldVal,'',$JQclass,$extra);
			echo "</p>";
		} 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		foreach($new_instance as $key=>$val) {
			$instance[$key] = (!empty($val)) ? strip_tags($val) : '';
		}
		return $instance;
	}
} 

function OEPL_Lead_Widget_init() {
	register_widget( 'OEPL_Lead_Widget' );
}
add_action( 'widgets_init', 'OEPL_Lead_Widget_init' );

add_shortcode('OEPL_CRM_Lead_Form', 'OEPL_CRM_Lead_Form');
function OEPL_CRM_Lead_Form($atts, $content = null) {
	ob_start();
    the_widget('OEPL_Lead_Widget', 
    	array(
	        'before_widget' => '',
	        'after_widget' => '',
	        'before_title' => '',
	        'after_title' => ''
    	)
	);
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}