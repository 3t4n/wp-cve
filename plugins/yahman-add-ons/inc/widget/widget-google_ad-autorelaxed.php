<?php
/**
 * Widget Google AdSense Matched content
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_google_ad_matched_content_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_ad_autorelaxed', // Base ID
			esc_html__( '[YAHMAN Add-ons] Google AdSense Matched content', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support a Matched content ad unit for Google AdSense.', 'yahman-add-ons' ), ) // Args
		);
	}


	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'slot'    => '',
			'rows_mobile' => '',
			'columns_mobile' => '',
			'ui_mobile' => '',
			'rows_pc'   => '',
			'columns_pc' => '',
			'ui_pc' => '',
			'note' => '',
		);

		return $defaults;
	}

	
	
	
	

	public function widget( $args, $instance ) {

		if( is_404() ) return;

		$data = array();
		$option = get_option('yahman_addons');

		$this->settings = wp_parse_args( $instance, $this->default_settings() );
		$settings = $this->settings;

		$data['client'] = isset($option['google_ad']['id']) ? $option['google_ad']['id'] : '';

		if(isset($settings['slot'])){
			$data['slot'] = $settings['slot'];
		}else{
			$data['slot'] = isset($option['google_ad']['slot_autorelaxed']) ? $option['google_ad']['slot_autorelaxed'] : '';
		}



		
		if ( $data['client'] === '' && $data['slot'] === '' ) return;

		echo str_replace( array(' shadow_box' , 'class="') , array('' , 'class="ya_ad_widget ') , $args['before_widget']);

		echo '<div class="ad_box ad_autorelaxed" itemscope itemtype="https://schema.org/WPAdBlock">';


		echo '<div class="ad_wrap clearfix">';


		wp_register_script( 'google-adsense-js', '' );
			//add_action( 'wp_footer', 'yahman_addons_google_adsense_script');

		$data['matched'] = $data['rows'] = $data['columns'] = $data['ui'] = $data['separate'] = '';
		$data['mobile'] = $data['pc'] = false;

		$autorelaxed_data = array('rows_mobile','rows_pc','columns_mobile','columns_pc','ui_mobile','ui_pc');

		foreach ($autorelaxed_data as $key => $value) {
			if(isset($settings[$value])){
				$data[$value] = $settings[$value];
			}else{
				$data[$value] = isset($option['google_ad'][$value.'_autorelaxed']) ? $option['google_ad'][$value.'_autorelaxed'] : '';
			}
		}

		if($data['rows_mobile'] !== '' && $data['columns_mobile'] !== '' && $data['ui_mobile']){
			$data['rows'] = $data['rows_mobile'];
			$data['columns'] = $data['columns_mobile'];
			$data['ui'] = $data['ui_mobile'];
			$data['mobile'] = true;
			$data['separate'] = ',';
		}
		if($data['rows_pc'] !== '' && $data['columns_pc'] !== '' && $data['ui_pc']){

			$data['rows'] .= $data['separate'].$data['rows_pc'];
			$data['columns'] .= $data['separate'].$data['columns_pc'];
			$data['ui'] .= $data['separate'].$data['ui_pc'];
			$data['pc'] = true;
		}

		if($data['mobile'] || $data['pc']){
			$data['matched']='
			data-matched-content-rows-num="'.$data['rows'].'"
			data-matched-content-columns-num="'.$data['columns'].'"
			data-matched-content-ui-type="'.$data['ui'].'"
			';
		}
		echo apply_filters( 'widget_text',
			'<ins class="adsbygoogle"
			style="display:block;"
			data-ad-format="autorelaxed"
			data-ad-client="'.$data['client'].'"
			data-ad-slot="'.$data['slot'].'"'.$data['matched']
			.'></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>'
		);

		echo '</div></div>';
		echo $args['after_widget'];

	}


	
	
	

	public function form( $instance ) {

        // Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );

		$ui_type = array(
			'' => '',
			'image_sidebyside' => esc_attr__('Image and text side by side', 'yahman-add-ons'),
			'image_card_sidebyside' => esc_attr__('Image and text side by side with card', 'yahman-add-ons'),
			'image_stacked' => esc_attr__('Image stacked above text', 'yahman-add-ons'),
			'image_card_stacked' => esc_attr__('Image stacked above text with card', 'yahman-add-ons'),
			'text' => esc_attr__('Text only', 'yahman-add-ons'),
			'text_card' => esc_attr__('Text with card', 'yahman-add-ons')
		);
		$num_type = array(
			'' => '',
			'1' => 1,
			'2' => 2,
			'3' => 3,
			'4' => 4,
			'5' => 5,
			'6' => 6
		);

		$device = array(
			'pc' => esc_html_x('desktop', 'google_ad', 'yahman-add-ons'),
			'mobile' => esc_html_x('mobile', 'google_ad', 'yahman-add-ons'),
		);
		//https://support.google.com/adsense/answer/7183212
		//https://support.google.com/adsense/answer/7533385?hl=ja

		$autorelaxed_data = array('rows_mobile','rows_pc','columns_mobile','columns_pc','ui_mobile','ui_pc');

		?>
		<p>
			<?php esc_html_e( 'If empty, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slot' ) ); ?>"><?php echo sprintf(esc_html__( '%s ad unit\'s ID(data-ad-slot):', 'yahman-add-ons'),esc_html_x('Matched content', 'google_ad', 'yahman-add-ons')); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slot' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slot' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['slot'] ); ?>">
		</p>


		<?php foreach ($device as $device_key => $device_val) : ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'rows_'.$device_key ) ); ?>">
					<?php echo sprintf(esc_html__( 'content rows num for %s:', 'yahman-add-ons'), $device_val ); ?>
				</label><br />
				<select id="<?php echo esc_attr( $this->get_field_id( 'rows_'.$device_key )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rows_'.$device_key )); ?>">
					<?php foreach ($num_type as $num_type_key => $num_type_val) { ?>
						<option <?php echo selected( $settings['rows_'.$device_key], $num_type_key ); ?> value="<?php echo $num_type_key; ?>" >
							<?php echo $num_type_val; ?>
						</option>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'columns_'.$device_key ) ); ?>">
					<?php echo sprintf(esc_html__( 'content columns num for %s:', 'yahman-add-ons'), $device_val ); ?>
				</label><br />
				<select id="<?php echo esc_attr( $this->get_field_id( 'columns_'.$device_key )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns_'.$device_key )); ?>">
					<?php foreach ($num_type as $num_type_key => $num_type_val) { ?>
						<option <?php echo selected( $settings['columns_'.$device_key], $num_type_key ); ?> value="<?php echo $num_type_key; ?>" >
							<?php echo $num_type_val; ?>
						</option>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'ui_'.$device_key ) ); ?>">
					<?php echo sprintf(esc_html__( 'content rows num for %s:', 'yahman-add-ons'), $device_val ); ?>
				</label><br />
				<select id="<?php echo esc_attr( $this->get_field_id( 'ui_'.$device_key )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'ui_'.$device_key )); ?>">
					<?php foreach ($ui_type as $ui_type_key => $ui_type_val) { ?>
						<option <?php echo selected( $settings['ui_'.$device_key], $ui_type_key ); ?> value="<?php echo $ui_type_key; ?>" >
							<?php echo $ui_type_val; ?>
						</option>
					<?php } ?>
				</select>
			</p>

			<?php
		endforeach;
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'note' ) ); ?>"><?php esc_html_e('Note', 'yahman-add-ons'); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'note' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'note' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['note'] ); ?>">
		</p>
		<?php
	}


	

	

	
	

	

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$types = array('slot','rows_pc','columns_pc','ui_pc','rows_mobile','columns_mobile','ui_mobile','note');
		foreach ($types as $key => $value) {
			$instance[$value] = sanitize_text_field( $new_instance[$value] );
		}


		return $instance;
	}

} // class yahman_addons_google_ad_matched_content_widget
