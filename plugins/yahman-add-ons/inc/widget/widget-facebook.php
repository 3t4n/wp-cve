<?php
/**
 * Widget Facebook
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_facebook_widget extends WP_Widget {


	function __construct() {

		parent::__construct(
			'ya_facebook_widget', // Base ID
			esc_html__( '[YAHMAN Add-ons] Facebook timeline', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Display timeline of Facebook', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => esc_html__( 'Like us on Facebook', 'yahman-add-ons' ),
			'href' => '',
			'width' => '',
			'height'   => '',
			'tabs' => array('timeline'),
			'hide_cover' => false,
			'show_facepile' => true,
			'hide_cta' => false,
			'small_header' => false,
			'adapt_container_width' => true,
			'lazy' => false,

		);

		return $defaults;
	}

	public function widget( $args, $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );

		if(empty($settings['href'])){
			$option = get_option('yahman_addons');
			if(empty($option['sns_account']['facebook']))return;
			$settings['href'] = $option['sns_account']['facebook'];
		}


		echo $args['before_widget'];
		if ( ! empty($settings['title']) ) {
			echo $args['before_title']. $settings['title'] .  $args['after_title'];
		}

		require_once YAHMAN_ADDONS_DIR . 'inc/facebook_script.php';
		$fb_lang = yahman_addons_facebook_lang(get_locale());

		$facebook = ' data-href="https://www.facebook.com/'.$settings['href'].'/"';

		$facebook_before = '<div class="fb-page"';
		$facebook_after = '<blockquote cite="https://www.facebook.com/'.$settings['href'].'/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/'.$settings['href'].'/">'.get_bloginfo( 'name' ).'</a></blockquote></div>';

		if ( ! empty($settings['width']) ) {
			$facebook .= ' data-width="'.$settings['width'].'"';
		}

		if ( ! empty($settings['height']) ) {
			$facebook .= ' data-height="'.$settings['height'].'"';
		}



		if ( ! empty( $settings['tabs'] ) && is_array( $settings['tabs'] ) ) {
			$facebook .= ' data-tabs="' . esc_attr( join ( ',', $settings['tabs'] ) ) . '"';
		}



		if ( $settings['hide_cover'] ) {
			$facebook .= ' data-hide-cover="true"';
		}

		if ( !$settings['show_facepile'] ) {
			$facebook .= ' data-show-facepile="false"';
		}

		if ( $settings['hide_cta'] ) {
			$facebook .= ' data-hide-cta="true"';
		}

		if ( $settings['small_header'] ) {
			$facebook .= ' data-small-header="true"';
		}

		if ( !$settings['adapt_container_width'] ) {
			$facebook .= ' data-data-adapt-container-width="false"';
		}

		$facebook .= '>';

		//$facebook .= esc_html( 'Tweets' . $timeline['liked'] . ' by @ ' . $settings['username'] );
        //$twitter .= sprintf(esc_html__( 'Tweets by %s', 'yahman-add-ons'),esc_html($settings['username']));


		echo $facebook_before.$facebook.$facebook_after;

		echo $args['after_widget'];

		add_action('wp_footer', 'yahman_addons_facebook_script');

/* iframe varsion
			$facebook_before =  '<iframe src="https://www.facebook.com/plugins/page.php?href='.urlencode( 'https://www.facebook.com/'.esc_attr($settings['href']) );

			$facebook = '';

			if ( ! empty( $settings['tabs'] ) && is_array( $settings['tabs'] ) ) {
				$facebook .= '&tabs=' . esc_attr( join ( ',', $settings['tabs'] ) );
			}
			if ( !$settings['hide_cover'] ) {
				$facebook .= '&hide_cover=false';
			}

			if ( !$settings['show_facepile'] ) {
				$facebook .= '&show_facepile=false';
			}

			if ( $settings['hide_cta'] ) {
				$facebook .= '&hide_cta=true';
			}

			if ( $settings['small_header'] ) {
				$facebook .= '&small_header=true';
			}

			if ( !$settings['adapt_container_width'] ) {
				$facebook .= '&adapt_container_width=false';
			}

			if ( $settings['lazy'] ) {
				$facebook .= '&lazy=true';
			}

			$facebook .= '"';

			if ( ! empty($settings['width']) ) {
				$facebook .= ' width="'.$settings['width'].'"';
			}else{
				$facebook .= ' width="300"';
			}

			if ( ! empty($settings['height']) ) {
				$facebook .= ' height="'.$settings['height'].'"';
			}else{
				$facebook .= ' height="500"';
			}


			$facebook_after = ' style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>';
*/

		}


		public function form( $instance ) {

		// Get Widget Settings.
			$settings = wp_parse_args( $instance, $this->default_settings() );

			$facebook_widget_tabs = array (
				'timeline' => esc_html__( 'Timeline', 'yahman-add-ons'),
				'events'  => esc_html__( 'Events', 'yahman-add-ons'),
				'messages'  => esc_html__( 'Messages', 'yahman-add-ons'),
			);
			$facebook_widget_layout_option = array (
				'hide_cover' => esc_html__( 'Hide cover photo', 'yahman-add-ons'),
				'show_facepile'  => esc_html__( 'Show friend\'s faces', 'yahman-add-ons'),
				'hide_cta' => esc_html__( 'Hide CTA', 'yahman-add-ons'),
				'small_header'  => esc_html__( 'Show small header', 'yahman-add-ons'),
				'adapt_container_width' => esc_html__( 'Adapt to plugin container width', 'yahman-add-ons'),
				'lazy' => esc_html__( 'Use the browser\'s lazy loading mechanism', 'yahman-add-ons'),
			);




			?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'yahman-add-ons' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'href' ); ?>"><?php esc_html_e( 'Facebook page:', 'yahman-add-ons' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'href' ); ?>" name="<?php echo $this->get_field_name( 'href' ); ?>" type="text" value="<?php echo esc_attr( $settings['href'] ); ?>" />
				<br />
				<?php
				esc_html_e('type the &lowast;&lowast;&lowast;&lowast;&lowast;&lowast; part of your url', 'yahman-add-ons');
				echo '<br />'.esc_html__('e.g.&nbsp;', 'yahman-add-ons');
				echo esc_html('https://www.facebook.com/').'<strong class="highlighter">&lowast;&lowast;&lowast;&lowast;&lowast;&lowast;</strong>';


				?><br />
				<small><?php esc_html_e( 'If empty this slot, then take precedence from Social setting of YAHMAN Add-ons.', 'yahman-add-ons' ); ?></small>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>"><?php esc_html_e( 'Show tabs:', 'yahman-add-ons' ); ?></label><br />
				<?php foreach ( $facebook_widget_tabs as $key => $val ): ?>
					<input type="checkbox" <?php checked( in_array( $key, $settings['tabs'] ) ); ?> id="<?php echo $this->get_field_id( 'tabs_'.$key ); ?>" name="<?php echo $this->get_field_name( 'tabs' ); ?>[]" value="<?php echo esc_attr( $key ); ?>" />
					<label for="<?php echo $this->get_field_id( 'tabs_'.$key ); ?>">
						<?php echo esc_html( $val ); ?>
					</label>
					<br />
				<?php endforeach; ?>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e( 'Width:', 'yahman-add-ons' ); ?></label>
				<input type="number" min="180" max="500" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" value="<?php echo esc_attr( $settings['width'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height:', 'yahman-add-ons' ); ?></label>
				<input type="number" min="70" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" value="<?php echo esc_attr( $settings['height'] ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'layout_option' ) ); ?>"><?php esc_html_e( 'Layout Options:', 'yahman-add-ons' ); ?></label><br />
				<?php foreach ( $facebook_widget_layout_option as $key => $val ): ?>
					<input type="checkbox" <?php checked( $settings[$key] ); ?> id="<?php echo $this->get_field_id( $key ); ?>" name="<?php echo $this->get_field_name( $key ); ?>" />
					<label for="<?php echo $this->get_field_id( $key ); ?>">
						<?php echo esc_html( $val ); ?>
					</label>
					<br />
				<?php endforeach; ?>
			</p>




			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			$instance['href'] = sanitize_text_field( $new_instance['href'] );

			$option_settings = array(
				'hide_cover',
				'show_facepile',
				'hide_cta',
				'small_header',
				'adapt_container_width',
				'lazy'
			);
			foreach ($option_settings as $value) {
				$instance[$value] = isset( $new_instance[$value] ) ? true : false ;

			}


			$width = absint( $new_instance['width'] );
			if ( $width ) {
				$instance['width'] = min ( max ( $width, 180 ), 500 );
			} else {
				$instance['width'] = '';
			}

			$height = absint( $new_instance['height'] );
			if ( $height ) {
				$instance['height'] = max ( $height, 70 );
			} else {
				$instance['height'] = '';
			}


			$instance['tabs'] = array();
			$tabs_settings = array(
				'timeline',
				'events',
				'messages',
			);
			if ( isset( $new_instance['tabs'] ) ) {
				foreach ( $new_instance['tabs'] as $tabs ) {
					if ( in_array( $tabs, $tabs_settings ) ) {
						$instance['tabs'][] = $tabs;
					}
				}
			}

			return $instance;
		}




} // class yahman_addons_facebook_widget
