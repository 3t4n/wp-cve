<?php
/**
 * Widget Google AdSense link unit
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_google_ad_link_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_ad_link', // Base ID
			esc_html__( '[YAHMAN Add-ons] Google AdSense Link unit', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support a link unit ad for Google AdSense.', 'yahman-add-ons' ), ) // Args
		);
	}


	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'data_ad_slot'    => '',
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

			$settings['data_ad_slot'] =  isset($option['google_ad']['slot_link']) ? $option['google_ad']['slot_link'] : '';
		}

		
		if ( $settings['data_ad_slot'] === '' ) return;

		echo str_replace( array(' shadow_box' , 'class="') , array('' , 'class="ya_ad_widget ') , $args['before_widget']);

		$settings['labeling'] = isset($option['google_ad']['labeling']) ? $option['google_ad']['labeling'] : '0';
		if ($settings['labeling'] == '1'){
			$settings['labeling'] = esc_html__( 'Advertisements', 'yahman-add-ons' );
		}else if ($settings['labeling'] == '2'){
			$settings['labeling'] = esc_html__( 'Sponsored Links', 'yahman-add-ons' );
		}else{
			$settings['labeling'] = '';
		}


		echo '<div class="ad_box ad_link fit_widget" itemscope itemtype="https://schema.org/WPAdBlock">';

		if ( $settings['labeling'] != '' ) {
			echo '<div class="ad_labeling w100">' . esc_html($settings['labeling']) . '</div>';
		}

		echo '<div class="ad_wrap clearfix" style="text-align:center;">';






		wp_register_script( 'google-adsense-js', '' );
			//add_action( 'wp_footer', 'yahman_addons_google_adsense_script');

		echo apply_filters( 'widget_text',
			'<ins class="adsbygoogle"
			style="display:block;"
			data-ad-client="'.$settings['client'].'"
			data-ad-slot="'.$settings['data_ad_slot'].'"
			data-ad-format="link"
			data-full-width-responsive="'.$settings['full-width-responsive'].'">
			</ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>'
		);

		echo '</div></div>';
		echo $args['after_widget'];

	}


	
	
	

	public function form( $instance ) {
		$settings = wp_parse_args( $instance, $this->default_settings() );
		//$settings['data_ad_slot'] = ! empty( $instance['data_ad_slot'] ) ? $instance['data_ad_slot'] : '';
		//$settings['note'] = ! empty( $instance['note'] ) ? $instance['note'] : '';
		//https://support.google.com/adsense/answer/7183212
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php  echo sprintf(esc_html__( '%s ad unit\'s ID(data-ad-slot):', 'yahman-add-ons'),esc_html_x('Link unit', 'google_ad', 'yahman-add-ons')) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_slot' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['data_ad_slot'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php esc_html_e( 'If empty this slot, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?></label>
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
		$instance['data_ad_slot'] = ( ! empty( $new_instance['data_ad_slot'] ) ) ? sanitize_text_field( $new_instance['data_ad_slot'] ) : '';
		$instance['note'] =  ! empty( $new_instance['note'] ) ? sanitize_text_field( $new_instance['note'] ) : '';
		$instance['full-width-responsive'] = ! empty( $new_instance['full-width-responsive'] )  ? esc_attr($new_instance['full-width-responsive']) : 'true';
		return $instance;
	}

} // class yahman_addons_google_ad_in_article_widget
