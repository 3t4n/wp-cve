<?php
/**
 * Widget Google AdSense responsive
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_google_ad_responsive_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
			'ya_ad_responsive', // Base ID
			esc_html__( '[YAHMAN Add-ons] Google AdSense Responsive', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support a responsive ad unit for Google AdSense.', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'data_ad_slot'    => '',
			'data_ad_format'    => 'auto',
			'full-width-responsive'    => 'true',
			'note'    => '',
		);

		return $defaults;
	}


	
	
	
	

	public function widget( $args, $instance ) {

		if(is_404())return;

		$settings = array();

		$option = get_option('yahman_addons');

		
		if( !isset($option['google_ad']['id']) ) return;

		$settings = wp_parse_args( $instance, $this->default_settings() );

		$settings['client'] = $option['google_ad']['id'];




		if( empty( $settings['data_ad_slot'] ) ){

			$settings['data_ad_slot'] =  isset($option['google_ad']['slot_responsive']) ? $option['google_ad']['slot_responsive'] : '';
		}

		
		if ( $settings['data_ad_slot'] === '') return;



		echo str_replace( array(' shadow_box' , 'class="') , array('' , 'class="ya_ad_widget ') , $args['before_widget']);


		$settings['labeling'] = isset($option['google_ad']['labeling']) ? $option['google_ad']['labeling'] : '0';
		if ($settings['labeling'] == '1'){
			$settings['labeling'] = esc_html__( 'Advertisements', 'yahman-add-ons' );
		}else if ($settings['labeling'] == '2'){
			$settings['labeling'] = esc_html__( 'Sponsored Links', 'yahman-add-ons' );
		}else{
			$settings['labeling'] = '';
		}


		
		$settings['format'] = !empty( $instance['data_ad_format'] ) ? $instance['data_ad_format'] : 'auto';

		$settings['rectangle'] = $settings['w_rectangle'] = '';

		$settings['is_w_rectangle'] = false;

		if($settings['format'] === 'rectangle'){
			$settings['rectangle'] =' ad_rectangle';
		}else if($settings['format'] === 'w_rectangle'){
			$settings['format'] = 'rectangle';

			if(wp_is_mobile()){
				$settings['rectangle'] =' ad_rectangle';
			}else{
				$settings['is_w_rectangle'] = true;
				$settings['rectangle'] =' w_rectangle f_box jc_sb f_wrap';
				$settings['w_rectangle'] =' ad_rectangle';
			}

		}else if($settings['format'] === "horizontal"){
			
			$settings['full-width-responsive'] = 'false';
		}


		echo '<div class="responsive_wrap ad_box ta_c fit_widget ad_responsive'.$settings['rectangle'].'" itemscope itemtype="https://schema.org/WPAdBlock">';


		$i = 1;

		if($settings['is_w_rectangle']) $i = 0;

		while($i < 2):

			echo '<div class="ad_wrap clearfix'.$settings['w_rectangle'].'">';

			if ( $settings['labeling'] != '' ) {
				echo '<div class="ad_labeling w100">' . esc_html($settings['labeling']) . '</div>';
			}

			wp_register_script( 'google-adsense-js', '' );
					//add_action( 'wp_footer', 'yahman_addons_google_adsense_script');
				//"rectangle"（レクタングル）、"vertical"（縦長）、"horizontal"（横長）に変更したり、これらをカンマで区切って組み合わせた値（"rectangle, horizontal" など）に変更したりします。

				//if(!wp_is_mobile())$width_responsive = "false";

            //if($args['id'] == 'on_pagination' && !wp_is_mobile())$data['format'] = "vertical";
            //if($args['id'] == 'before_h2_no1' && !wp_is_mobile())$data['format'] = "rectangle,vertical";
            //if($args['id'] == 'before_h2_no2' && !wp_is_mobile())$data['format'] = "horizontal";
            //if($args['id'] == 'before_h2_no3' && !wp_is_mobile())$data['format'] = "horizontal";
            //if($args['id'] == 'sidebar-1' && !wp_is_mobile())$data['format'] = "vertical";
			echo apply_filters( 'widget_text',
				'<ins class="adsbygoogle"
				style="display:block"
				data-ad-client="'.$settings['client'].'"
				data-ad-slot="'.$settings['data_ad_slot'].'"
				data-ad-format="'.$settings['format'].'"
				data-full-width-responsive="'.$settings['full-width-responsive'].'">
				</ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>'
			);

			echo '</div>';

			++$i;

		endwhile;

		echo '</div>';
		echo $args['after_widget'];

	}


	
	
	

	public function form( $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );

		//$settings['data_ad_slot'] = ! empty( $instance['data_ad_slot'] ) ? $instance['data_ad_slot'] : '';
		//$settings['data_ad_format'] = ! empty( $instance['data_ad_format'] ) ? $instance['data_ad_format'] : 'auto';
		//$settings['note'] = ! empty( $instance['note'] ) ? $instance['note'] : '';
		//https://support.google.com/adsense/answer/7183212
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php echo sprintf(esc_html__( '%s ad unit\'s ID(data-ad-slot):', 'yahman-add-ons'),esc_html_x('Responsive', 'google_ad', 'yahman-add-ons')); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_slot' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['data_ad_slot'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php esc_html_e( 'If empty this slot, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_format' ) ); ?>">
				<?php esc_html_e( 'Format', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'data_ad_format' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_format' )); ?>">
				<option <?php echo selected( $settings['data_ad_format'], 'auto' ); ?> value="auto" ><?php echo esc_html_x( 'Auto', 'google_ad','yahman-add-ons' ); ?></option>
				<option <?php echo selected( $settings['data_ad_format'], 'rectangle' ); ?> value="rectangle" ><?php echo esc_html_x( 'rectangle', 'google_ad','yahman-add-ons' ); ?></option>
				<option <?php echo selected( $settings['data_ad_format'], 'horizontal' ); ?> value="horizontal" ><?php echo esc_html_x( 'horizontal', 'google_ad','yahman-add-ons' ); ?></option>
				<option <?php echo selected( $settings['data_ad_format'], 'vertical' ); ?> value="vertical" ><?php echo esc_html_x( 'vertical', 'google_ad','yahman-add-ons' ); ?></option>
				<option <?php echo selected( $settings['data_ad_format'], 'w_rectangle' ); ?> value="w_rectangle" ><?php echo esc_html_x( 'double rectangle', 'google_ad','yahman-add-ons' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'full-width-responsive' ) ); ?>">
				<?php esc_html_e( 'Full width responsive ', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'full-width-responsive' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'full-width-responsive' )); ?>">
				<option <?php echo selected( $settings['full-width-responsive'], 'true' ); ?> value="true" ><?php echo esc_html_x( 'True', 'google_ad','yahman-add-ons' ); ?></option>
				<option <?php echo selected( $settings['full-width-responsive'], 'false' ); ?> value="false" ><?php echo esc_html_x( 'False', 'google_ad','yahman-add-ons' ); ?></option>
			</select>
		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'note' ) ); ?>"><?php esc_html_e('Note', 'yahman-add-ons'); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'note' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'note' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['note'] ); ?>">
		</p>
		<?php
	}


	

	

	
	

	

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['data_ad_slot'] =  ! empty( $new_instance['data_ad_slot'] ) ? sanitize_text_field( $new_instance['data_ad_slot'] ) : '';
		$instance['data_ad_format'] = ! empty( $new_instance['data_ad_format'] )  ? esc_attr($new_instance['data_ad_format']) : 'auto';
		$instance['full-width-responsive'] = ! empty( $new_instance['full-width-responsive'] )  ? esc_attr($new_instance['full-width-responsive']) : 'true';
		$instance['note'] =  ! empty( $new_instance['note'] ) ? sanitize_text_field( $new_instance['note'] ) : '';
		return $instance;
	}

} // class yahman_addons_google_ad_responsive_widget
