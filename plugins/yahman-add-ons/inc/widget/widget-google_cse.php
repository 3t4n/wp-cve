<?php
/**
 * Widget Google Custom Search
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_google_cse_widget extends WP_Widget {
	
	function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'scripts'));

		parent::__construct(
			'ya_cse', // Base ID
			esc_html__( '[YAHMAN Add-ons] Google Custom Search', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support a Google Custom Search.', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => '',
			'cx'    => '',
			'action_url'    => 'https://www.google.com/cse',
			'icon_color'    => '',
		);

		return $defaults;
	}

	
	
	
	

	public function widget( $args, $instance ) {



		$settings = wp_parse_args( $instance, $this->default_settings() );

		if($settings['cx'] === '') return;

		$data = array();
		$option = get_option('yahman_addons');

		$settings['locale'] = get_locale();

		if($settings['icon_color'] === ''){
			$settings['icon_color'] = '%23000';
		}else{
			$settings['icon_color'] = str_replace("#", "%23", $settings['icon_color']);
		}

		echo $args['before_widget'];

		if ( $settings['title'] ) {
			echo $args['before_title'] . esc_html($settings['title']) . $args['after_title'];
		}

		$button_style = 'width:32px;height:32px;color:transparent;border:none;background:url(&#39;data:image/svg+xml;charset=utf8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20width%3D%2232%22%20height%3D%2232%22%3E%3Cpath%20fill%3D%22'.$settings['icon_color'].'%22%20d%3D%22M19.8%2C14.5c0-3-2.4-5.4-5.4-5.4s-5.4%2C2.4-5.4%2C5.4s2.4%2C5.4%2C5.4%2C5.4S19.8%2C17.4%2C19.8%2C14.5z%20M26%2C24.5c0%2C0.8-0.7%2C1.5-1.5%2C1.5%20c-0.4%2C0-0.8-0.2-1.1-0.5l-4.1-4.1c-1.4%2C1-3.1%2C1.5-4.8%2C1.5c-4.7%2C0-8.5-3.8-8.5-8.5S9.8%2C6%2C14.5%2C6s8.5%2C3.8%2C8.5%2C8.5%20c0%2C1.7-0.5%2C3.4-1.5%2C4.8l4.1%2C4.1C25.8%2C23.7%2C26%2C24.1%2C26%2C24.5z%22%2F%3E%3C%2Fsvg%3E&#39;)no-repeat center center;';

		?>

		<form id="cse-search-box" action="<?php echo esc_attr($settings['action_url']); ?>">
			<input type="hidden" name="cx" value="<?php echo esc_attr($settings['cx']); ?>" />
			<input type="hidden" name="ie" value="UTF-8" />
			<input type="text" name="q" size="31" aria-label="<?php esc_attr_e( 'Search' , 'yahman-add-ons' ); ?>" />
			<input type="submit" name="sa" value="Search" style="<?php echo $button_style; ?>" />
		</form>
		<script async src="//www.google.com/cse/brand?form=cse-search-box&lang=<?php echo esc_attr($settings['locale']); ?>"></script>

		<?php




		echo $args['after_widget'];

	}



	
	
	

	public function form( $instance ) {
    // Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>">
		</p>


		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cx' ) ); ?>"><?php echo esc_html_e('CX', 'yahman-add-ons'); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'cx' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cx' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['cx'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'cx' ) ); ?>"><?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('000000000000:xxxxxxxxx'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'action_url' ) ); ?>"><?php echo esc_html_e('search results URL', 'yahman-add-ons'); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'action_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'action_url' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['action_url'] ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'action_url' ) ); ?>"><?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('https://xxxxxxxx.com/search'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_color' ) ); ?>" style="display:block;"><?php esc_html_e( 'Color of the icon.', 'yahman-add-ons'  ); ?></label>
			<input class="ya_color-picker" id="<?php echo esc_attr( $this->get_field_id( 'icon_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_color' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['icon_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
		</p>

		<?php
	}


	

	

	
	

	

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = isset( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['cx'] = isset( $new_instance['cx'] ) ? sanitize_text_field( $new_instance['cx'] ) : '';
		$instance['action_url'] = isset( $new_instance['action_url'] ) ? esc_url( $new_instance['action_url'] ) : '';
		$instance['icon_color'] = isset( $new_instance['icon_color'] ) ? sanitize_text_field( $new_instance['icon_color'] ) : '';
		return $instance;
	}

	public function scripts($hook){

		if ($hook == 'widgets.php' || $hook == 'customize.php') {
			wp_enqueue_style( 'wp-color-picker');
			wp_enqueue_script( 'wp-color-picker');

			wp_enqueue_script('yahman_addons_widget-color-picker', YAHMAN_ADDONS_URI . 'assets/js/customizer/color-picker-widget.min.js', array('wp-color-picker'));

			wp_register_script('wp-color-picker-alpha',YAHMAN_ADDONS_URI . 'assets/js/customizer/wp-color-picker-alpha.min.js', array('wp-color-picker'), null , true );
			wp_add_inline_script(
				'wp-color-picker-alpha',
				'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
			);
			wp_enqueue_script( 'wp-color-picker-alpha' );

		}

	}

} // class yahman_addons_google_ad_responsive_widget
