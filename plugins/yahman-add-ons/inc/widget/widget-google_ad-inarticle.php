<?php
/**
 * Widget Google AdSense inarticle
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_google_ad_in_article_widget extends WP_Widget {

	
	function __construct() {
		parent::__construct(
			'ya_ad_in_article', // Base ID
			esc_html__( '[YAHMAN Add-ons] Google AdSense In-article', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support a In-article ad unit for Google AdSense.', 'yahman-add-ons' ), ) // Args
		);
	}

	
	
	
	

	public function widget( $args, $instance ) {
		if(is_404())return;

		$data = array();
		$option = get_option('yahman_addons');

		$data['client'] = isset($option['google_ad']['id']) ? $option['google_ad']['id'] : '';
		$data['slot'] = isset($option['google_ad']['slot_inarticle']) ? $option['google_ad']['slot_inarticle'] : '';

		
		if ( $data['client'] === '' && ( $data['slot'] === '' || empty( $instance['data_ad_slot'] ) ) ) return;

		echo str_replace( array(' shadow_box' , 'class="') , array('' , 'class="ya_ad_widget ') , $args['before_widget']);

		$data['labeling'] = isset($option['google_ad']['labeling']) ? $option['google_ad']['labeling'] : '0';
		if ($data['labeling'] == '1'){
			$data['labeling'] = esc_html__( 'Advertisements', 'yahman-add-ons' );
		}else if ($data['labeling'] == '2'){
			$data['labeling'] = esc_html__( 'Sponsored Links', 'yahman-add-ons' );
		}else{
			$data['labeling'] = '';
		}
		
		if(!empty( $instance['data_ad_slot'] ))$data['slot'] = $instance['data_ad_slot'];

		echo '<div class="ad_box ad_inarticle" itemscope itemtype="https://schema.org/WPAdBlock">';

		if ( $data['labeling'] != '' ) {
			echo '<div class="ad_labeling w100">' . esc_html($data['labeling']) . '</div>';
		}

		echo '<div class="ad_wrap clearfix">';


		wp_register_script( 'google-adsense-js', '' );
			//add_action( 'wp_footer', 'yahman_addons_google_adsense_script');

		echo apply_filters( 'widget_text',
			'<ins class="adsbygoogle"
			style="display:block; text-align:center;"
			data-ad-layout="in-article"
			data-ad-format="fluid"
			data-ad-client="'.$data['client'].'"
			data-ad-slot="'.$data['slot'].'">
			</ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>'
		);

		echo '</div></div>';
		echo $args['after_widget'];

	}


	
	
	

	public function form( $instance ) {
		$settings['data_ad_slot'] = ! empty( $instance['data_ad_slot'] ) ? $instance['data_ad_slot'] : '';
		$settings['data_ad_slot_res'] = ! empty( $instance['data_ad_slot_res'] ) ? $instance['data_ad_slot_res'] : '';
		$settings['note'] = ! empty( $instance['note'] ) ? $instance['note'] : '';

		//https://support.google.com/adsense/answer/7183212
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php  echo sprintf(esc_html__( '%s ad unit\'s ID(data-ad-slot):', 'yahman-add-ons'),esc_html_x('In-article', 'google_ad', 'yahman-add-ons')) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'data_ad_slot' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['data_ad_slot'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'data_ad_slot' ) ); ?>"><?php esc_html_e( 'If empty this slot, then take precedence from YAHMAN Add-ons setting.', 'yahman-add-ons' ); ?></label>
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
		return $instance;
	}

} // class yahman_addons_google_ad_in_article_widget
