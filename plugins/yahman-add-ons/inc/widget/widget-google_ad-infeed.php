<?php
/**
 * Widget Google AdSense infeed
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_google_ad_in_feed_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_ad_in_feed', // Base ID
			esc_html__( '[YAHMAN Add-ons] Google AdSense In-feed', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support a In-feed ad unit for Google AdSense.', 'yahman-add-ons' ), ) // Args
		);
	}

	
	
	
	

	public function widget( $args, $instance ) {
		if(is_404())return;

		$data = array();
		$option = get_option('yahman_addons');

		$data['client'] = isset($option['google_ad']['id']) ? $option['google_ad']['id'] : '';
		$data['slot'] = isset($option['google_ad']['slot_infeed']) ? $option['google_ad']['slot_infeed'] : '';
		$data['layout_key'] = isset($option['google_ad']['layout_infeed']) ? $option['google_ad']['layout_infeed'] : '';
		$data['slot_mobile'] = isset($option['google_ad']['slot_infeed_mobile']) ? $option['google_ad']['slot_infeed_mobile'] : '';
		$data['layout_key_mobile'] = isset($option['google_ad']['layout_infeed_mobile']) ? $option['google_ad']['layout_infeed_mobile'] : '';

		if ( $data['client'] != '' && ( ($data['slot'] != '' || !empty( $instance['data_ad_slot'] )) && ($data['layout_key'] != '' || !empty( $instance['data_ad_layout_key'] )) ) || ( ($data['slot_mobile'] != '' || !empty( $instance['data_ad_slot_mobile'] )) && ($data['layout_key_mobile'] != '' || !empty( $instance['data_ad_layout_key_mobile'] )) )){
			//echo $args['before_widget'];

			
			if(!empty( $instance['data_ad_slot'] ))$data['slot'] = $instance['data_ad_slot'];
			if(!empty( $instance['data_ad_layout_key'] ))$data['layout_key'] = $instance['data_ad_layout_key'];
			if(!empty( $instance['data_ad_slot_mobile'] ))$data['slot_mobile'] = $instance['data_ad_slot_mobile'];
			if(!empty( $instance['data_ad_layout_key_mobile'] ))$data['layout_key_mobile'] = $instance['data_ad_layout_key_mobile'];

			echo '<aside class="ad_box ad_infeed ta_c w100" itemscope itemtype="https://schema.org/WPAdBlock">';



			wp_register_script( 'google-adsense-js', '' );
				//add_action( 'wp_footer', 'yahman_addons_google_adsense_script');

			if(wp_is_mobile() && ( $data['slot_mobile'] != '' && $data['layout_key_mobile'] != '')){
				$data['layout_key'] = $data['layout_key_mobile'];
				$data['slot'] = $data['slot_mobile'];
			}
			if($data['layout_key'] != '' && $data['slot'] != ''){
				echo apply_filters( 'widget_text',
					'<ins class="adsbygoogle"
					style="display:block"
					data-ad-format="fluid"
					data-ad-layout-key="'.$data['layout_key'].'"
					data-ad-client="'.$data['client'].'"
					data-ad-slot="'.$data['slot'].'">
					</ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>'
				);
			}


			echo '</aside>';
			//echo $args['after_widget'];
		}
	}


	
	
	

	public function form( $instance ) {
		$settings['data_ad_slot'] = ! empty( $instance['data_ad_slot'] ) ? $instance['data_ad_slot'] : '';
		$settings['data_ad_layout_key'] = ! empty( $instance['data_ad_layout_key'] ) ? $instance['data_ad_layout_key'] : '';
		$settings['data_ad_slot_mobile'] = ! empty( $instance['data_ad_slot_mobile'] ) ? $instance['data_ad_slot_mobile'] : '';
		$settings['data_ad_layout_key_mobile'] = ! empty( $instance['data_ad_layout_key_mobile'] ) ? $instance['data_ad_layout_key_mobile'] : '';
		$settings['note'] = ! empty( $instance['note'] ) ? $instance['note'] : '';
		//https://support.google.com/adsense/answer/7183212
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php  echo sprintf(esc_html__( '%s ad unit\'s ID(data-ad-slot):', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons')) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_slot' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['data_ad_slot'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php esc_html_e( 'If empty this slot, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_layout_key' ) ); ?>"><?php  echo sprintf(esc_html__( '%s ad unit\'s layout key(data-ad-layout-key):', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons')); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data_ad_layout_key' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_layout_key' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['data_ad_layout_key'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_layout_key' ) ); ?>"><?php esc_html_e( 'If empty this key, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot_mobile' ) ); ?>"><?php echo esc_html_x('For Mobile', 'google_ad', 'yahman-add-ons'); ?><?php  echo sprintf(esc_html__( '%s ad unit\'s ID(data-ad-slot):', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons')) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot_mobile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_slot_mobile' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['data_ad_slot_mobile'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot_mobile' ) ); ?>"><?php esc_html_e( 'If empty this slot, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_layout_key_mobile' ) ); ?>"><?php echo esc_html_x('For Mobile', 'google_ad', 'yahman-add-ons'); ?><?php  echo sprintf(esc_html__( '%s ad unit\'s layout key(data-ad-layout-key):', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons')); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data_ad_layout_key_mobile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_layout_key_mobile' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['data_ad_layout_key_mobile'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_layout_key_mobile' ) ); ?>"><?php esc_html_e( 'If empty this key, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?></label>
		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'note' ) ); ?>"><?php esc_html_e('Note', 'yahman-add-ons'); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'note' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'note' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['note'] ); ?>">
		</p>
		<?php
	}


	

	

	
	

	

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['data_ad_slot'] = ( ! empty( $new_instance['data_ad_slot'] ) ) ? sanitize_text_field( $new_instance['data_ad_slot'] ) : '';

		$instance['data_ad_layout_key'] = ! empty( $new_instance['data_ad_layout_key'] )  ? sanitize_text_field($new_instance['data_ad_layout_key']) : '';
		$instance['data_ad_slot_mobile'] = ( ! empty( $new_instance['data_ad_slot_mobile'] ) ) ? sanitize_text_field( $new_instance['data_ad_slot_mobile'] ) : '';
		$instance['data_ad_layout_key_mobile'] = ! empty( $new_instance['data_ad_layout_key_mobile'] )  ? sanitize_text_field($new_instance['data_ad_layout_key_mobile']) : '';
		$instance['note'] =  ! empty( $new_instance['note'] ) ? sanitize_text_field( $new_instance['note'] ) : '';
		return $instance;
	}

} // class yahman_addons_google_ad_in_feed_widget
