<?php
/**
 * Widget Twitter
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_twitter_widget extends WP_Widget {


	function __construct() {

		add_action('admin_enqueue_scripts', array($this, 'scripts'));

		add_action('wp_footer', array($this, 'scripts_twitter'));

		parent::__construct(
			'ya_twitter_widget', // Base ID
			esc_html__( '[YAHMAN Add-ons] Twitter timeline', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Display timeline of Twitter', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => esc_html__( 'Follow me on Twitter', 'yahman-add-ons' ),
			'username' => '',
			'show-replies' => '',
			'chrome' => array(),
			'theme' => 'light',
			'width' => 800,
			'height'   => 400,
			'tweet_limit'   => 20,
			'link_color' => '',
			'border_color' => '',
			'aria_polite' => 'polite',
			'timeline' => 'profile',
			'dnt' => '',
		);

		return $defaults;
	}

	public function widget( $args, $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );

		if(empty($settings['username'])){
			$option = get_option('yahman_addons');
			if(empty($option['sns_account']['twitter']))return;
			$settings['username'] = $option['sns_account']['twitter'];
		}


		echo $args['before_widget'];
		if ( ! empty($settings['title']) ) {
			echo $args['before_title']. $settings['title'] .  $args['after_title'];
		}



		$timeline['likes'] = $timeline['liked'] = '';

		if ( $settings['timeline'] != 'profile' ) {
			$timeline['likes'] = '/likes';
			$timeline['liked'] = ' liked';
		}

		$twitter = '';

		$twitter_before = '<a class="twitter-timeline" href="https://twitter.com/'.$settings['username'].$timeline['likes'].'"';
		$twitter_after = '</a>';

		if ( ! empty($settings['width']) ) {
			$twitter .= ' data-width="'.$settings['width'].'"';
		}

		if ( ! empty($settings['height']) ) {
			$twitter .= ' data-height="'.$settings['height'].'"';
		}

		if ( $settings['theme'] != 'light' ) {
			$twitter .= ' data-theme="'.$settings['theme'].'"';
		}

		if ( $settings['tweet_limit'] != 20 ) {
			$twitter .= ' data-tweet-limit="'.$settings['tweet_limit'].'"';
		}

		if ( ! empty($settings['link_color']) ) {
			$twitter .= ' data-link-color="'.$settings['link_color'].'"';
		}

		if ( ! empty($settings['border_color']) ) {
			$twitter .= ' data-border-color="'.$settings['border_color'].'"';
		}

		if ( ! empty( $settings['chrome'] ) && is_array( $settings['chrome'] ) ) {
			$twitter .= ' data-chrome="' . esc_attr( join ( ' ', $settings['chrome'] ) ) . '"';
		}

		if ( $settings['aria_polite'] != 'polite' ) {
			$twitter .= ' data-aria-polite="'.$settings['aria_polite'].'"';
		}

		$twitter .= ' data-lang="'.get_locale().'"';

		$twitter .= '>';

		$twitter .= esc_html( 'Tweets' . $timeline['liked'] . ' by @ ' . $settings['username'] );
        //$twitter .= sprintf(esc_html__( 'Tweets by %s', 'yahman-add-ons'),esc_html($settings['username']));


		echo $twitter_before.$twitter.$twitter_after;


		echo $args['after_widget'];

	}


	public function form( $instance ) {

		// Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );

		$twitter_option = array();

		$twitter_option['theme'] = array (
			'light' => esc_html__( 'Light', 'yahman-add-ons'),
			'dark'  => esc_html__( 'Dark', 'yahman-add-ons'),
		);
		$twitter_option['chrome'] = array (
			'noheader' => esc_html__( 'No Header', 'yahman-add-ons'),
			'nofooter'  => esc_html__( 'No Footer', 'yahman-add-ons'),
			'noborders' => esc_html__( 'No Borders', 'yahman-add-ons'),
			'noscrollbar'  => esc_html__( 'No Scrollbar', 'yahman-add-ons'),
			'transparent' => esc_html__( 'Transparent Background', 'yahman-add-ons'),
		);
		$twitter_option['aria_polite'] = array (
			'polite' => esc_html__( 'polite', 'yahman-add-ons'),
			'assertive'  => esc_html__( 'assertive', 'yahman-add-ons'),
			'rude'  => esc_html__( 'rude', 'yahman-add-ons'),
		);
		$twitter_option['timeline_type'] = array (
			'profile' => esc_html_x( 'Profile', 'twitter_timeline' ,'yahman-add-ons'),
			'likes'  => esc_html_x( 'Likes', 'twitter_timeline' ,'yahman-add-ons'),
		);



		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php esc_html_e( 'Twitter Username:', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $settings['username'] ); ?>" />
			<br />
			<small><?php esc_html_e( 'If empty this slot, then take precedence from Social setting of YAHMAN Add-ons.', 'yahman-add-ons' ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'timeline' ) ); ?>"><?php esc_html_e( 'Timeline type:', 'yahman-add-ons' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'timeline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'timeline' ) ); ?>">
				<?php foreach ( $twitter_option['timeline_type'] as $key => $val ): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $settings['timeline'], $key ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e( 'Width:', 'yahman-add-ons' ); ?></label>
			<input type="number" min="180" max="1200" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" value="<?php echo esc_attr( $settings['width'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height:', 'yahman-add-ons' ); ?></label>
			<input type="number" min="200" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" value="<?php echo esc_attr( $settings['height'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'tweet_limit' ) ); ?>"><?php esc_html_e( 'Number of Tweets:', 'yahman-add-ons' ); ?></label>
			<input type="number" min="1" max="20" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tweet_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tweet_limit' ) ); ?>" value="<?php echo esc_attr( $settings['tweet_limit'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>"><?php esc_html_e( 'Theme:', 'yahman-add-ons' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'theme' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'theme' ) ); ?>">
				<?php foreach ( $twitter_option['theme'] as $key => $val ): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $settings['theme'], $key ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link_color' ) ); ?>" style="display:block;"><?php esc_html_e( 'Link Color:', 'yahman-add-ons'  ); ?></label>
			<input class="ya_color-picker" id="<?php echo esc_attr( $this->get_field_id( 'link_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_color' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['link_color'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'border_color' ) ); ?>" style="display:block;"><?php esc_html_e( 'Border Color:', 'yahman-add-ons'  ); ?></label>
			<input class="ya_color-picker" id="<?php echo esc_attr( $this->get_field_id( 'border_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'border_color' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['border_color'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'chrome' ) ); ?>"><?php esc_html_e( 'Layout Options:', 'yahman-add-ons' ); ?></label><br />
			<?php foreach ( $twitter_option['chrome'] as $key => $val ): ?>
				<input type="checkbox" <?php checked( in_array( $key, $settings['chrome'] ) ); ?> id="<?php echo $this->get_field_id( 'chrome_'.$key ); ?>" name="<?php echo $this->get_field_name( 'chrome' ); ?>[]" value="<?php echo esc_attr( $key ); ?>" />
				<label for="<?php echo $this->get_field_id( 'chrome_'.$key ); ?>">
					<?php echo esc_html( $val ); ?>
				</label>
				<br />
			<?php endforeach; ?>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'aria_polite' ) ); ?>"><?php esc_html_e( 'Accessibility:', 'yahman-add-ons' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'aria_polite' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'aria_polite' ) ); ?>">
				<?php foreach ( $twitter_option['aria_polite'] as $key => $val ): ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $settings['aria_polite'], $key ); ?>><?php echo esc_html( $val ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>


		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['username'] = sanitize_text_field( $new_instance['username'] );
		//$instance['width'] = (int) $new_instance['width'];
		//$instance['height'] = (int) $new_instance['height'] ;
		$instance['tweet_limit'] = (int) $new_instance['tweet_limit'];
		$instance['theme'] = sanitize_text_field( $new_instance['theme'] );
		$instance['timeline'] = sanitize_text_field( $new_instance['timeline'] );
		$instance['link_color'] = esc_attr( $new_instance['link_color'] );
		$instance['border_color'] = esc_attr( $new_instance['border_color'] );
		$instance['chrome'] = sanitize_text_field( $new_instance['chrome'] );
		$instance['aria_polite'] = sanitize_text_field( $new_instance['aria_polite'] );


		$width = absint( $new_instance['width'] );
		if ( $width ) {
			$instance['width'] = min ( max ( $width, 180 ), 1200 );
		} else {
			$instance['width'] = '';
		}

		$height = absint( $new_instance['height'] );
		if ( $height ) {
			$instance['height'] = max ( $height, 200 );
		} else {
			$instance['height'] = '';
		}


		$instance['chrome'] = array();
		$chrome_settings = array(
			'noheader',
			'nofooter',
			'noborders',
			'noscrollbar',
			'transparent'
		);
		if ( isset( $new_instance['chrome'] ) ) {
			foreach ( $new_instance['chrome'] as $chrome ) {
				if ( in_array( $chrome, $chrome_settings ) ) {
					$instance['chrome'][] = $chrome;
				}
			}
		}

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


	public function scripts_twitter($hook){
		wp_register_script( 'yahman_twitter-widgets', '' );
	}

} // class yahman_addons_twitter_widget
