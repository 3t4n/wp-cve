<?php

/*
	VIDEO WIDGET
*/

class Directorypress_Widget_Author extends WP_Widget {
	private $unique_id;
	function __construct() {
		$widget_ops = array( 'classname' => 'directorypress_widget_author', 'description' => 'DirectoryPress Author Widget' );
		WP_Widget::__construct( 'directorypress_author', 'directorypress-author', $widget_ops );
		
		add_action( 'admin_enqueue_scripts', array($this, 'scripts'));
		add_action( 'enqueue_block_editor_assets', array($this, 'scripts') );
		add_action ('directorypress_after_dynamic_style' , array($this, 'dynamic_styles'));
		
	}

	function scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('directorypress_admin_script');	
	}
	
	function widget( $args, $instance ) {
		
			
			global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object;
			extract( $args );
			
			$instance['id'] = $this->id;
			$title = isset( $instance['style'] ) ? $instance['title'] : '';
			$style = isset( $instance['style'] ) ? $instance['style'] : '1';
			
			
			$output = '';
			global $post;
			
			echo wp_kses_post($before_widget);
			if ( $title ){
				echo wp_kses_post($before_title . $title . $after_title);
			}
			if(class_exists('DirectoryPress')){
				if($style == 1){
					directorypress_display_template('partials/widgets/author/style-1.php', array('instance' => $instance));
					
				}elseif($style == 2){
					directorypress_display_template('partials/widgets/author/style-2.php', array('instance' => $instance));
					
				}elseif($style == 3){
					
					directorypress_display_template('partials/widgets/author/style-3.php', array('instance' => $instance));
					
				}
			}
			echo wp_kses_post($after_widget);
		
	}
	
	function dynamic_styles($instance){
		
		$data = $this->get_settings();
		$instance_id = array_key_first($data);
		$instance = $this->get_settings()[$instance_id];
		$id = 'directorypress_author-'. $instance_id;
		//var_dump($instance['phone_background']);
		$phone_background = (isset( $instance['phone_background'] ) && !empty( $instance['phone_background'] )) ? ('background:'. $instance['phone_background'] . ';') : '';
		$phone_background_hover = (isset( $instance['phone_background_hover'] ) && !empty( $instance['phone_background_hover'] )) ? ('background:'. $instance['phone_background_hover'] . ';') : '';
		$whatsapp_background = (isset( $instance['whatsapp_background'] ) && !empty( $instance['whatsapp_background'] )) ? ('background:'. $instance['whatsapp_background'] . ';') : '';
		$whatsapp_background_hover = (isset( $instance['whatsapp_background_hover'] ) && !empty( $instance['whatsapp_background_hover'] )) ? ('background:'. $instance['whatsapp_background_hover'] . ';') : '';
		$email_background = (isset( $instance['email_background'] ) && !empty( $instance['email_background'] )) ? ('background:'. $instance['email_background'] . ';') : '';
		$email_background_hover = (isset( $instance['email_background_hover'] ) && !empty( $instance['email_background_hover'] )) ? ('background:'. $instance['email_background_hover'] . ';') : '';
		$social_background = (isset( $instance['social_background'] ) && !empty( $instance['social_background'] )) ? ('background:'. $instance['social_background'] . ';') : '';
		$social_background_hover = (isset( $instance['social_background_hover'] ) && !empty( $instance['social_background_hover'] )) ? ('background:'. $instance['social_background_hover'] . ';') : '';
		$contact_btn_background = (isset( $instance['contact_btn_background'] ) && !empty( $instance['contact_btn_background'] )) ? ('background:'. $instance['contact_btn_background'] . ';') : '';
		$contact_btn_background_hover = (isset( $instance['contact_btn_background_hover'] ) && !empty( $instance['contact_btn_background_hover'] )) ? ('background:'. $instance['contact_btn_background_hover'] . ';') : '';
		$offer_btn_background = (isset( $instance['offer_btn_background'] ) && !empty( $instance['offer_btn_background'] )) ? ('background:'. $instance['offer_btn_background'] . ';') : '';
		$offer_btn_background_hover = (isset( $instance['offer_btn_background_hover'] ) && !empty( $instance['offer_btn_background_hover'] )) ? ('background:'. $instance['offer_btn_background_hover'] . ';') : '';
		
		$phone_text_color = (isset( $instance['phone_text_color'] ) && !empty( $instance['phone_text_color'] )) ? ('color:'. $instance['phone_text_color'] . ' !important;') : '';
		$phone_text_color_hover = (isset( $instance['phone_text_color_hover'] ) && !empty( $instance['phone_text_color_hover'] )) ? ('color:'. $instance['phone_text_color_hover'] . ' !important;') : '';
		$whatsapp_text_color = (isset( $instance['whatsapp_text_color'] ) && !empty( $instance['whatsapp_text_color'] )) ? ('color:'. $instance['whatsapp_text_color'] . ' !important;') : '';
		$whatsapp_text_color_hover = (isset( $instance['whatsapp_text_color_hover'] ) && !empty( $instance['whatsapp_text_color_hover'] )) ? ('color:'. $instance['whatsapp_text_color_hover'] . ' !important;') : '';
		$email_text_color = (isset( $instance['email_text_color'] ) && !empty( $instance['email_text_color'] )) ? ('color:'. $instance['email_text_color'] . ' !important;') : '';
		$email_text_color_hover = (isset( $instance['email_text_color_hover'] ) && !empty( $instance['email_text_color_hover'] )) ? ('color:'. $instance['email_text_color_hover'] . ' !important;') : '';
		$social_text_color = (isset( $instance['social_text_color'] ) && !empty( $instance['social_text_color'] )) ? ('color:'. $instance['social_text_color'] . ' !important;') : '';
		$social_text_color_hover = (isset( $instance['social_text_color_hover'] ) && !empty( $instance['social_text_color_hover'] )) ? ('color:'. $instance['social_text_color_hover'] . ' !important;') : '';
		$contact_text_color = (isset( $instance['contact_text_color'] ) && !empty( $instance['contact_text_color'] )) ? ('color:'. $instance['contact_text_color'] . ' !important;') : '';
		$contact_text_color_hover = (isset( $instance['contact_text_color_hover'] ) && !empty( $instance['contact_text_color_hover'] )) ? ('color:'. $instance['contact_text_color_hover'] . ' !important;') : '';
		$offer_text_color = (isset( $instance['offer_text_color'] ) && !empty( $instance['offer_text_color'] )) ? ('color:'. $instance['offer_text_color'] . ' !important;') : '';
		$offer_text_color_hover = (isset( $instance['offer_text_color_hover'] ) && !empty( $instance['offer_text_color_hover'] )) ? ('color:'. $instance['offer_text_color_hover'] . ' !important;') : '';
		
		DirectoryPress_Static_Files::addGlobalStyle("
		#{$id} .author-phone:not(.whatsapp) a{
			{$phone_background}
			{$phone_text_color}
		}
		#{$id} .author-phone:not(.whatsapp) a span{
			{$phone_text_color}
		}
		#{$id} .author-phone:not(.whatsapp) a:hover{
			{$phone_background_hover}
			{$phone_text_color_hover}
		}
		#{$id} .author-phone:not(.whatsapp) a:hover span{
			{$phone_text_color_hover}
		}
		#{$id} .author-phone.whatsapp a{
			{$whatsapp_background}
			{$whatsapp_text_color}
		}
		#{$id} .author-phone.whatsapp a span{
			{$whatsapp_text_color}
		}
		#{$id} .author-phone.whatsapp a:hover span{
			{$whatsapp_text_color_hover}
		}
		#{$id} .author-phone.whatsapp a:hover{
			{$whatsapp_background_hover}
			{$whatsapp_text_color_hover}
		}
		#{$id} .author-email-id{
			{$email_background}
			{$email_text_color}
		}
		#{$id} .author-email-id p{
			{$email_text_color}
		}
		#{$id} .author-email-id:hover p{
			{$email_text_color_hover}
		}
		#{$id} .author-email-id:hover{
			{$email_background_hover}
			{$email_text_color_hover}
		}
		#{$id} .author-social-follow-ul li a{
			{$social_background}
			{$social_text_color}
		}
		#{$id} .author-social-follow-ul li a:hover{
			{$social_background_hover}
			{$social_text_color_hover}
		}
		#{$id} .author-btn-holder a.author-contact-btn{
			{$contact_btn_background}
			{$contact_text_color}
		}
		#{$id} .author-btn-holder a.author-contact-btn:hover{
			{$contact_btn_background_hover}
			{$contact_text_color_hover}
		}
		#{$id} .author-btn-holder a.author-offer-btn{
			{$offer_btn_background}
			{$offer_text_color}
		}
		#{$id} .author-btn-holder a.author-offer-btn:hover{
			{$offer_btn_background_hover}
			{$offer_text_color_hover}
		}
		
		");
	}

	function update( $new_instance, $old_instance ) {
		//$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = $new_instance['style'];
		$instance['show_phone_number'] = $new_instance['show_phone_number'];
		$instance['show_whatsapp_number'] = $new_instance['show_whatsapp_number'];
		$instance['show_email'] = $new_instance['show_email'];
		$instance['show_social_links'] = $new_instance['show_social_links'];
		$instance['show_contact'] = $new_instance['show_contact'];
		$instance['show_offer_button'] = $new_instance['show_offer_button'];
		$instance['hide_from_anonymous'] = $new_instance['hide_from_anonymous'];
		
		$instance['phone_background'] = $new_instance['phone_background'];
		$instance['phone_background_hover'] = $new_instance['phone_background_hover'];
		$instance['whatsapp_background'] = $new_instance['whatsapp_background'];
		$instance['whatsapp_background_hover'] = $new_instance['whatsapp_background_hover'];
		$instance['email_background'] = $new_instance['email_background'];
		$instance['email_background_hover'] = $new_instance['email_background_hover'];
		$instance['social_background'] = $new_instance['social_background'];
		$instance['social_background_hover'] = $new_instance['social_background_hover'];
		$instance['contact_btn_background'] = $new_instance['contact_btn_background'];
		$instance['contact_btn_background_hover'] = $new_instance['contact_btn_background_hover'];
		$instance['offer_btn_background'] = $new_instance['offer_btn_background'];
		$instance['offer_btn_background_hover'] = $new_instance['offer_btn_background_hover'];
		
		$instance['phone_text_color'] = $new_instance['phone_text_color'];
		$instance['phone_text_color_hover'] = $new_instance['phone_text_color_hover'];
		$instance['whatsapp_text_color'] = $new_instance['whatsapp_text_color'];
		$instance['whatsapp_text_color_hover'] = $new_instance['whatsapp_text_color_hover'];
		$instance['email_text_color'] = $new_instance['email_text_color'];
		$instance['email_text_color_hover'] = $new_instance['email_text_color_hover'];
		$instance['social_text_color'] = $new_instance['social_text_color'];
		$instance['social_text_color_hover'] = $new_instance['social_text_color_hover'];
		$instance['contact_text_color'] = $new_instance['contact_text_color'];
		$instance['contact_text_color_hover'] = $new_instance['contact_text_color_hover'];
		$instance['offer_text_color'] = $new_instance['offer_text_color'];
		$instance['offer_text_color_hover'] = $new_instance['offer_text_color_hover'];
		
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$style = isset( $instance['style'] ) ? $instance['style'] : '';
		$show_phone_number = isset( $instance['show_phone_number'] ) ? $instance['show_phone_number'] : 1;
		$show_whatsapp_number = isset( $instance['show_whatsapp_number'] ) ? $instance['show_whatsapp_number'] : 1;
		$show_email = isset( $instance['show_email'] ) ? $instance['show_email'] : '1';
		$show_social_links = isset( $instance['show_social_links'] ) ? $instance['show_social_links'] : 1;
		$show_contact = isset( $instance['show_contact'] ) ? $instance['show_contact'] : '1';
		$show_offer_button = isset( $instance['show_offer_button'] ) ? $instance['show_offer_button'] : 1;
		$hide_from_anonymous = isset( $instance['hide_from_anonymous'] ) ? $instance['hide_from_anonymous'] : 0;

		$phone_background = isset( $instance['phone_background'] ) ? $instance['phone_background'] : '';
		$phone_background_hover = isset( $instance['phone_background_hover'] ) ? $instance['phone_background_hover'] : '';
		$whatsapp_background = isset( $instance['whatsapp_background'] ) ? $instance['whatsapp_background'] : '';
		$whatsapp_background_hover = isset( $instance['whatsapp_background_hover'] ) ? $instance['whatsapp_background_hover'] : '';
		$email_background = isset( $instance['email_background'] ) ? $instance['email_background'] : '';
		$email_background_hover = isset( $instance['email_background_hover'] ) ? $instance['email_background_hover'] : '';
		$social_background = isset( $instance['social_background'] ) ? $instance['social_background'] : '';
		$social_background_hover = isset( $instance['social_background_hover'] ) ? $instance['social_background_hover'] : '';
		$contact_btn_background = isset( $instance['contact_btn_background'] ) ? $instance['contact_btn_background'] : '';
		$contact_btn_background_hover = isset( $instance['contact_btn_background_hover'] ) ? $instance['contact_btn_background_hover'] : '';
		$offer_btn_background = isset( $instance['offer_btn_background'] ) ? $instance['offer_btn_background'] : '';
		$offer_btn_background_hover = isset( $instance['offer_btn_background_hover'] ) ? $instance['offer_btn_background_hover'] : '';
		
		$phone_text_color = isset( $instance['phone_text_color'] ) ? $instance['phone_text_color'] : '';
		$phone_text_color_hover = isset( $instance['phone_text_color_hover'] ) ? $instance['phone_text_color_hover'] : '';
		$whatsapp_text_color = isset( $instance['whatsapp_text_color'] ) ? $instance['whatsapp_text_color'] : '';
		$whatsapp_text_color_hover = isset( $instance['whatsapp_text_color_hover'] ) ? $instance['whatsapp_text_color_hover'] : '';
		$email_text_color = isset( $instance['email_text_color'] ) ? $instance['email_text_color'] : '';
		$email_text_color_hover = isset( $instance['email_text_color_hover'] ) ? $instance['email_text_color_hover'] : '';
		$social_text_color = isset( $instance['social_text_color'] ) ? $instance['social_text_color'] : '';
		$social_text_color_hover = isset( $instance['social_text_color_hover'] ) ? $instance['social_text_color_hover'] : '';
		$contact_text_color = isset( $instance['contact_text_color'] ) ? $instance['contact_text_color'] : '';
		$contact_text_color_hover = isset( $instance['contact_text_color_hover'] ) ? $instance['contact_text_color_hover'] : '';
		$offer_text_color = isset( $instance['offer_text_color'] ) ? $instance['offer_text_color'] : '';
		$offer_text_color_hover = isset( $instance['offer_text_color_hover'] ) ? $instance['offer_text_color_hover'] : '';

?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php echo esc_html__('Title', 'DIRECTORYPRESS'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'style' )); ?>"><?php esc_html_e('Style:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'style' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'style' )); ?>" class="widefat">
    			<option value="1"<?php selected( $style, '1');?>><?php echo esc_html__('One', 'DIRECTORYPRESS'); ?></option>
    			<option value="2"<?php selected( $style, '2');?>><?php echo esc_html__('Two', 'DIRECTORYPRESS'); ?></option>
				<option value="3"<?php selected( $style, '3');?>><?php echo esc_html__('Three', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_phone_number' )); ?>"><?php esc_html_e('Show Phone Number:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'show_phone_number' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'show_phone_number' )); ?>" class="widefat">
    			<option value="1"<?php selected( $show_phone_number, '1');?>><?php echo esc_html__('Yes', 'DIRECTORYPRESS'); ?></option>
    			<option value="0"<?php selected( $show_phone_number, '0');?>><?php echo esc_html__('No', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_whatsapp_number' )); ?>"><?php esc_html_e('Show Whatsapp Number:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'show_whatsapp_number' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'show_whatsapp_number' )); ?>" class="widefat">
    			<option value="1"<?php selected( $show_whatsapp_number, '1');?>><?php echo esc_html__('Yes', 'DIRECTORYPRESS'); ?></option>
    			<option value="0"<?php selected( $show_whatsapp_number, '0');?>><?php echo esc_html__('No', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_email' )); ?>"><?php esc_html_e('Show Email Id:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'show_email' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'show_email' )); ?>" class="widefat">
    			<option value="1"<?php selected( $show_email, '1');?>><?php echo esc_html__('Yes', 'DIRECTORYPRESS'); ?></option>
    			<option value="0"<?php selected( $show_email, '0');?>><?php echo esc_html__('No', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_social_links' )); ?>"><?php esc_html_e('Show Social Links:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'show_social_links' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'show_social_links' )); ?>" class="widefat">
    			<option value="1"<?php selected( $show_social_links, '1');?>><?php echo esc_html__('Yes', 'DIRECTORYPRESS'); ?></option>
    			<option value="0"<?php selected( $show_social_links, '0');?>><?php echo esc_html__('No', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_contact' )); ?>"><?php esc_html_e('Show Contact:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'show_contact' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'show_contact' )); ?>" class="widefat">
    			<option value="1"<?php selected( $show_contact, '1');?>><?php echo esc_html__('Yes', 'DIRECTORYPRESS'); ?></option>
    			<option value="0"<?php selected( $show_contact, '0');?>><?php echo esc_html__('No', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_offer_button' )); ?>"><?php esc_html_e('Show Offer Button:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'show_offer_button' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'show_offer_button' )); ?>" class="widefat">
    			<option value="1"<?php selected( $show_offer_button, '1');?>><?php echo esc_html__('Yes', 'DIRECTORYPRESS'); ?></option>
    			<option value="0"<?php selected( $show_offer_button, '0');?>><?php echo esc_html__('No', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id( 'hide_from_anonymous' )); ?>"><?php esc_html_e('Hide Contact Info from anonymous user:', 'DIRECTORYPRESS'); ?></label>
    		<select name="<?php echo esc_attr($this->get_field_name( 'hide_from_anonymous' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'hide_from_anonymous' )); ?>" class="widefat">
    			<option value="1"<?php selected( $hide_from_anonymous, '1');?>><?php echo esc_html__('Yes', 'DIRECTORYPRESS'); ?></option>
    			<option value="0"<?php selected( $hide_from_anonymous, '0');?>><?php echo esc_html__('No', 'DIRECTORYPRESS'); ?></option>
    		</select>
  		</p>
		<p class="directorypress-color-picker-wrap">
			<label for="<?php echo esc_attr($this->get_field_id( 'phone_background' )); ?>"><?php echo esc_html__('Phone background Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<div class="directorypress-color-picker-holder"><span class="button-wrap" style="position:absolute; width:102px;height:30px;z-index:100;"></span><input data-default-color="<?php echo esc_attr($phone_background); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'phone_background' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'phone_background' )); ?>" type="text" value="<?php echo esc_attr($phone_background); ?>" /></div>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'phone_background_hover' )); ?>"><?php echo esc_html__('Phone background Color Hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($phone_background_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'phone_background_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'phone_background_hover' )); ?>" type="text" value="<?php echo esc_attr($phone_background_hover); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'phone_text_color' )); ?>"><?php echo esc_html__('Phone text Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($phone_text_color); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'phone_text_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'phone_text_color' )); ?>" type="text" value="<?php echo esc_attr($phone_text_color); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'phone_text_color_hover' )); ?>"><?php echo esc_html__('Phone text Color hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($phone_text_color_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'phone_text_color_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'phone_text_color_hover' )); ?>" type="text" value="<?php echo esc_attr($phone_text_color_hover); ?>" />
		</p>
		
		<!-- whatsapp-->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'whatsapp_background' )); ?>"><?php echo esc_html__('Whatsapp background Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($whatsapp_background); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'whatsapp_background' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'whatsapp_background' )); ?>" type="text" value="<?php echo esc_attr($whatsapp_background); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'whatsapp_background_hover' )); ?>"><?php echo esc_html__('Whatsapp background Color Hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($whatsapp_background_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'whatsapp_background_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'whatsapp_background_hover' )); ?>" type="text" value="<?php echo esc_attr($whatsapp_background_hover); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'whatsapp_text_color' )); ?>"><?php echo esc_html__('Whatsapp text Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($whatsapp_text_color); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'whatsapp_text_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'whatsapp_text_color' )); ?>" type="text" value="<?php echo esc_attr($whatsapp_text_color); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'whatsapp_text_color_hover' )); ?>"><?php echo esc_html__('Whatsapp text Color hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($whatsapp_text_color_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'whatsapp_text_color_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'whatsapp_text_color_hover' )); ?>" type="text" value="<?php echo esc_attr($whatsapp_text_color_hover); ?>" />
		</p>
		
		<!-- Email-->
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'email_background' )); ?>"><?php echo esc_html__('Email background Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($email_background); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'email_background' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'email_background' )); ?>" type="text" value="<?php echo esc_attr($email_background); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'email_background_hover' )); ?>"><?php echo esc_html__('Email background Color Hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($email_background_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'email_background_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'email_background_hover' )); ?>" type="text" value="<?php echo esc_attr($email_background_hover); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'email_text_color' )); ?>"><?php echo esc_html__('Email text Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($email_text_color); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'email_text_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'email_text_color' )); ?>" type="text" value="<?php echo esc_attr($email_text_color); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'email_text_color_hover' )); ?>"><?php echo esc_html__('Email text Color hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($email_text_color_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'email_text_color_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'email_text_color_hover' )); ?>" type="text" value="<?php echo esc_attr($email_text_color_hover); ?>" />
		</p>
		<!-- Social links-->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'social_background' )); ?>"><?php echo esc_html__('Social icon background Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($social_background); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'social_background' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'social_background' )); ?>" type="text" value="<?php echo esc_attr($social_background); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'social_background_hover' )); ?>"><?php echo esc_html__('Social icon background Color Hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($social_background_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'social_background_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'social_background_hover' )); ?>" type="text" value="<?php echo esc_attr($social_background_hover); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'social_text_color' )); ?>"><?php echo esc_html__('Social icons text Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($social_text_color); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'social_text_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'social_text_color' )); ?>" type="text" value="<?php echo esc_attr($social_text_color); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'social_text_color_hover' )); ?>"><?php echo esc_html__('Social icons text Color hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($social_text_color_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'social_text_color_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'social_text_color_hover' )); ?>" type="text" value="<?php echo esc_attr($social_text_color_hover); ?>" />
		</p>
		<!-- Contact Button -->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'contact_btn_background' )); ?>"><?php echo esc_html__('Contact Button background Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($contact_btn_background); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'contact_btn_background' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_btn_background' )); ?>" type="text" value="<?php echo esc_attr($contact_btn_background); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'contact_btn_background_hover' )); ?>"><?php echo esc_html__('Contact Button background Color Hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($contact_btn_background_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'contact_btn_background_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_btn_background_hover' )); ?>" type="text" value="<?php echo esc_attr($contact_btn_background_hover); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'contact_text_color' )); ?>"><?php echo esc_html__('Contact Button text Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($contact_text_color); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'contact_text_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_text_color' )); ?>" type="text" value="<?php echo esc_attr($contact_text_color); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'contact_text_color_hover' )); ?>"><?php echo esc_html__('Contact Button text Color hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($contact_text_color_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'contact_text_color_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'contact_text_color_hover' )); ?>" type="text" value="<?php echo esc_attr($contact_text_color_hover); ?>" />
		</p>
		<!-- Offer Button-->
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'offer_btn_background' )); ?>"><?php echo esc_html__('Offer Button background Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($offer_btn_background); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'offer_btn_background' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'offer_btn_background' )); ?>" type="text" value="<?php echo esc_attr($offer_btn_background); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'offer_btn_background_hover' )); ?>"><?php echo esc_html__('Offer Button background Color Hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($offer_btn_background_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'offer_btn_background_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'offer_btn_background_hover' )); ?>" type="text" value="<?php echo esc_attr($offer_btn_background_hover); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'offer_text_color' )); ?>"><?php echo esc_html__('Offer Button text Color:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($offer_text_color); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'offer_text_color' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'offer_text_color' )); ?>" type="text" value="<?php echo esc_attr($offer_text_color); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'offer_text_color_hover' )); ?>"><?php echo esc_html__('Offer Button text Color hover:', 'DIRECTORYPRESS'); ?></label>
			<br>
			<input data-default-color="<?php echo esc_attr($offer_text_color_hover); ?>" class="color-picker" id="<?php echo esc_attr($this->get_field_id( 'offer_text_color_hover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'offer_text_color_hover' )); ?>" type="text" value="<?php echo esc_attr($offer_text_color_hover); ?>" />
		</p>
<?php

	}
}

/***************************************************/
