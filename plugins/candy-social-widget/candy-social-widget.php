<?php
/*
 * Plugin Name: Candy Social Widget
 * Plugin URI: https://wordpress.org/plugins/candy-social-widget/
 * Description: Free WordPress plugin for displaying social media icons with links to your social media profiles in any widgetized area.
 * Version: 3.0
 * Author: WPExplorer
 * Author URI: https://www.wpexplorer.com/
 * Text Domain: candy-social-widget
 * Domain Path: /languages/
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the Candy_Social_Widget widget.
 */
if ( ! class_exists( 'Candy_Social_Widget' ) ) {

	final class Candy_Social_Widget extends WP_Widget {

		/**
		 * Plugin directory url.
		 *
		 * @var string
		 */
		protected $dir_url;

		/**
		 * Default widget settings.
		 *
		 * @var array
		 */
		protected $defaults;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {

			$this->dir_url = trailingslashit( plugin_dir_url( __FILE__ ) );

			$widget_ops = array(
				'classname'                   => 'candy_social_widget',
				'description'                 => __( 'Display your social icons.', 'candy-social-widget' ),
				'customize_selective_refresh' => true,
			);

			parent::__construct(
				'candy_social_widget',
				__( 'Candy Social Widget', 'candy-social-widget' ),
				$widget_ops
			);

			$this->defaults = array(
				'title'       => __( 'Follow Us', 'candy-social-widget' ),
				'description' => '',
				'icon_dims'   => 34,
				'icon_size'   => 16,
				'icon_gap'    => 8,
				'icon_shape'  => 'rounded',
				'url_target'  => '_blank',
				'profiles'    => array(),
			);

			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );

			add_action( 'admin_print_scripts-widgets.php', array( $this, 'admin_scripts' ) );

		}

		/**
		 * Outputs the content of the widget.
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			extract( $args );

			extract( wp_parse_args( (array) $instance, $this->defaults ) );

			echo $before_widget;

			if ( $title = apply_filters( 'widget_title', $title ) ) {
				echo wp_kses_post( $before_title . $title . $after_title );
			}

			if ( $description ) {
				echo '<div class="csw-desc">' . wpautop( wp_kses_post( $description ) ) . '</div>';
			}

			if ( $profiles ) {

				$social_ops = $this->get_social_ops();

				$ul_css = '';

				$icon_gap  = absint( $icon_gap );
				$icon_dims = absint( $icon_dims );
				$icon_size = absint( $icon_size );

				if ( ( $icon_gap || 0 === $icon_gap ) && $this->defaults['icon_gap'] !== $icon_gap ) {
					$ul_css .= 'gap:' . absint( $icon_gap ) . 'px;';
				}

				if ( $icon_dims && $this->defaults['icon_dims'] !== $icon_dims ) {
					$icon_dims = $icon_dims . 'px';
					$ul_css .= '--csw-icon-dims:' . $icon_dims . ';';
				}

				if ( $icon_size && $this->defaults['icon_size'] !== $icon_size ) {
					$ul_css .= '--csw-icon-size:' . $icon_size . 'px;';
				}

				if ( $ul_css ) {
					$ul_css = ' style="' . esc_attr( $ul_css ) . '"';
				}

				echo '<ul class="candy_social_widget_ul csw-shape-' . esc_attr( $icon_shape ) . '"' . $ul_css . '>';

					foreach ( $profiles as $k => $v ) {

						if ( is_customize_preview() ) {
							$url = isset( $v['url'] ) ?  esc_url( $v['url'] ) : '#';
						} else {
							$url = isset( $v['url'] ) ?  esc_url( $v['url'] ) : '';
						}

						if ( ! isset( $social_ops[ $v[ 'site' ] ] ) || ! $url ) {
							continue;
						}

						$target = '_blank' === $url_target ? ' target="blank" rel="noopener nofollow"' : '';

						echo '<li class="csw-' . esc_attr( $v['site'] ) . '"><a href="' . esc_url( $url ) . '"' . $target .'><span class="' . $social_ops[ $v['site'] ]['icon_class'] . '" aria-hidden="true">' . $this->get_icon( $v['site'] ) . '</span><span class="screen-reader-text">' . $social_ops[ $v['site'] ]['name'] . '</span></a></li>';

					}

				echo '</ul>';

			}

			echo $after_widget;

		}

		/**
		 * Returns the SVG icon for a given site.
		 */
		private function get_icon( $site ) {
			$file = plugin_dir_path( __FILE__ ) . 'assets/icons/' . $site . '.svg';
			if ( file_exists( $file ) ) {
				return file_get_contents( $file );
			}
		}

		/**
		 * Outputs the options form on admin.
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {

			extract( wp_parse_args( $instance, $this->defaults ) );

			$social_ops = $this->get_social_ops(); ?>

			<div class="csw-form">

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'candy-social-widget' ); ?>:</label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat">
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description', 'candy-social-widget' ); ?>:</label>
					<textarea id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" rows="4" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" class="widefat"><?php echo wp_kses_post( $description ); ?></textarea>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'icon_dims' ) ); ?>"><?php esc_html_e( 'Icon Dimensions', 'candy-social-widget' ); ?>:</label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'icon_dims' ) ); ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'icon_dims' ) ); ?>" value="<?php echo absint( $icon_dims ); ?>" class="small-text" step="1" min="12" max="100" placeholder="34"> <small>px</small>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'icon_size' ) ); ?>"><?php esc_html_e( 'Icon Font Size', 'candy-social-widget' ); ?>:</label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'icon_size' ) ); ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'icon_size' ) ); ?>" value="<?php echo absint( $icon_size ); ?>" class="small-text" step="1" min="12" max="100" placeholder="16"> <small>px</small>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'icon_gap' ) ); ?>"><?php esc_html_e( 'Space Between Icons', 'candy-social-widget' ); ?>:</label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'icon_gap' ) ); ?>" type="number" name="<?php echo esc_attr( $this->get_field_name( 'icon_gap' ) ); ?>" value="<?php echo absint( $icon_gap ); ?>" class="small-text" step="1" min="0" max="100" placeholder="8"> <small>px</small>
				</p>


				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'icon_shape' ) ); ?>"><?php esc_html_e( 'Icon Shape', 'candy-social-widget' ); ?>:</label>
					<select id="<?php echo $this->get_field_id( 'icon_shape' ); ?>" name="<?php echo $this->get_field_name( 'icon_shape' ); ?>">
						<option value="rounded" <?php selected( 'rounded', $icon_shape ); ?>><?php esc_html_e( 'Rounded', 'candy-social-widget' ); ?></option>
						<option value="round" <?php selected( 'round', $icon_shape ); ?>><?php esc_html_e( 'Round', 'candy-social-widget' ); ?></option>
						<option value="square" <?php selected( 'square', $icon_shape ); ?>><?php esc_html_e( 'Square', 'candy-social-widget' ); ?></option>
					</select>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'url_target' ) ); ?>"><?php esc_html_e( 'Link Target', 'candy-social-widget' ); ?>:</label>
					<select id="<?php echo $this->get_field_id( 'url_target' ); ?>" name="<?php echo $this->get_field_name( 'url_target' ); ?>">
						<option value="_blank" <?php selected( '_blank', $url_target ); ?>><?php esc_html_e( 'New Tab', 'candy-social-widget' ); ?></option>
						<option value="_self" <?php selected( '_self', $url_target ); ?>><?php esc_html_e( 'Same Tab', 'candy-social-widget' ); ?></option>
					</select>
				</p>

				<div><?php esc_html_e( 'Social Profiles', 'candy-social-widget' ); ?>:</div>

				<ul id="<?php echo esc_attr( $this->get_field_id( 'profiles' ) ); ?>" class="csw-sortable">
					<?php
					foreach ( $profiles as $profile_k => $profile_v ) :
						$site = $profile_v['site'] ?? '';
						$deprecated_profiles = [
							'android',
							'stumbleupon',
						];
						if ( ! $site || in_array( $site, $deprecated_profiles ) ) {
							continue;
						}
						?>
						<li>
							<span class="csw-remove dashicons dashicons-no-alt"></span>
							<p>
								<label><?php esc_html_e( 'Profile', 'candy-social-widget' ); ?>:</label>
								<select type="text" name="<?php echo $this->get_field_name( 'social_site' ); ?>[]">
									<?php foreach( $social_ops as $k => $v ) : ?>
										<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $k, $site ); ?>><?php echo esc_attr( $v['name'] ); ?></option>
									<?php endforeach; ?>
								</select>
							</p>
							<p>
								<label><?php esc_html_e( 'URL', 'candy-social-widget' ); ?>:</label>
								<input type="text" name="<?php echo $this->get_field_name('social_url'); ?>[]" value="<?php echo $profile_v['url']; ?>">
							</p>
						</li>
					<?php endforeach; ?>
				</ul>

				<p><a href="#" class="csw-add button"><?php _e( 'Add Profile', 'candy-social-widget' ); ?></a></p>

				<div class="csw-clone">
					<span class="csw-remove dashicons dashicons-no-alt"></span>
					<p>
						<label><?php esc_html_e( 'Profile', 'candy-social-widget' ); ?>:</label>
						<select type="text" name="<?php echo $this->get_field_name( 'social_site' ); ?>[]">
							<?php foreach( $social_ops as $k => $v ) : ?>
								<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_attr( $v['name'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<p>
						<label><?php esc_html_e( 'URL', 'candy-social-widget' ); ?>:</label>
						<input type="text" name="<?php echo $this->get_field_name( 'social_url' ); ?>[]">
					</p>
				</div>
			</div>

		<?php }

		/**
		 * Processing widget options on save.
		 *
		 * @param array $new The new options
		 * @param array $old The previous options
		 *
		 * @return array
		 */
		public function update( $new, $old ) {
			$instance = $old;
			$instance['title']       = ! empty( $new['title'] ) ? wp_strip_all_tags( $new['title'] ) : '';
			$instance['description'] = ! empty( $new['description'] ) ? wp_kses_post( $new['description'] ) : '';
			$instance['icon_dims']   = ! empty( $new['icon_dims'] ) ? absint( $new['icon_dims'] ) : $this->defaults['icon_dims'];
			$instance['icon_size']   = ! empty( $new['icon_size'] ) ? absint( $new['icon_size'] ) : $this->defaults['icon_size'];
			$instance['icon_gap']    = isset( $new['icon_gap'] ) ? absint( $new['icon_gap'] ) : $this->defaults['icon_gap'];
			$instance['icon_shape']  = ! empty( $new['icon_shape'] ) ? wp_strip_all_tags( $new['icon_shape'] ) : $this->defaults['icon_shape'];
			$instance['url_target']  = ! empty( $new['url_target'] ) ? wp_strip_all_tags( $new['url_target'] ) : $this->defaults['url_target'];

			$instance[ 'profiles' ] = array();

			if ( ! empty( $new['social_site'] ) ) {
				$social_profiles = [];
				for ( $i=0; $i < ( count( $new['social_site'] ) - 1 ); $i++ ) {
					$social_profiles[] = array(
						'site' => wp_strip_all_tags( $new['social_site'][ $i ] ),
						'url'  => esc_url( $new['social_url'][ $i ] )
					);
				}
				$instance['profiles'] = $social_profiles;
			} else {
				$instance['profiles'] = $new['profiles'] ?? []; // Gutenberg block fix - because apparently the widget editor saves multiple times.
			}

			return $instance;
		}

		/**
		 * Load front-end scripts for this widget.
		 *
		 * @return null
		 */
		public function front_scripts( $hook ) {
			wp_enqueue_style(
				'candy-social-widget',
				$this->dir_url . 'assets/css/csw-front.min.css',
				false,
				'1.0'
			);
		}

		/**
		 * Load admin scripts for this widget.
		 *
		 * @return null
		 */
		public function admin_scripts( $hook ) {
			wp_enqueue_style(
				'candy-social-widget',
				$this->dir_url . 'assets/css/csw-admin.css',
				false,
				'1.0'
			);

			wp_enqueue_script(
				'candy-social-widget',
				$this->dir_url . 'assets/js/csw-admin.js',
				array( 'jquery' ),
				'1.0',
				true
			);

			wp_localize_script(
				'candy-social-widget',
				'candySocialWidget',
				array(
					'confirm' => esc_html__( 'Do you really want to delete this item?', 'candy-social-widget' ),
				)
			);
		}

		/**
		 * Returns array of social options.
		 *
		 * @return array
		 */
		public function get_social_ops() {
			$options = array(
				'amazon' => array(
					'name' => 'Amazon',
					'icon_class' => 'cswi cswi-amazon',
				),
				'buffer' => array(
					'name' => 'Buffer',
					'icon_class' => 'cswi cswi-buffer',
				),
				'dribbble' => array(
					'name' => 'Dribbble',
					'icon_class' => 'cswi cswi-dribbble',
				),
				'email' => array(
					'name' => 'Email',
					'icon_class' => 'cswi cswi-email',
				),
				'facebook' => array(
					'name' => 'Facebook',
					'icon_class' => 'cswi cswi-facebook',
				),
				'flickr' => array(
					'name' => 'Flickr',
					'icon_class' => 'cswi cswi-flickr',
				),
				'github' => array(
					'name' => 'Github',
					'icon_class' => 'cswi cswi-github',
				),
				'houzz' => array(
					'name' => 'Houzz',
					'icon_class' => 'cswi cswi-houzz',
				),
				'instagram' => array(
					'name' => 'Instagram',
					'icon_class' => 'cswi cswi-instagram',
				),
				'linkedin' => array(
					'name' => 'Linkedin',
					'icon_class' => 'cswi cswi-linkedin',
				),
				'paypal' => array(
					'name' => 'Paypal',
					'icon_class' => 'cswi cswi-paypal',
				),
				'pinterest' => array(
					'name' => 'Pinterest',
					'icon_class' => 'cswi cswi-pinterest',
				),
				'pocket' => array(
					'name' => 'Pocket',
					'icon_class' => 'cswi cswi-pocket',
				),
				'reddit' => array(
					'name' => 'Reddit',
					'icon_class' => 'cswi cswi-reddit',
				),
				'rss' => array(
					'name' => 'RSS',
					'icon_class' => 'cswi cswi-rss',
				),
				'steam' => array(
					'name' => 'Steam',
					'icon_class' => 'cswi cswi-steam',
				),
				'stripe' => array(
					'name' => 'Stripe',
					'icon_class' => 'cswi cswi-stripe',
				),
				'tiktok' => array(
					'name' => 'Tiktok',
					'icon_class' => 'cswi cswi-tiktok',
				),
				'twitter' => array(
					'name' => 'Twitter "X"',
					'icon_class' => 'cswi cswi-twitter',
				),
				'tumblr' => array(
					'name' => 'Tumblr',
					'icon_class' => 'cswi cswi-tumblr',
				),
				'vimeo' => array(
					'name' => 'Vimeo',
					'icon_class' => 'cswi cswi-vimeo',
				),
				'vk' => array(
					'name' => 'VK',
					'icon_class' => 'cswi cswi-vk',
				),
				'wordpress' => array(
					'name' => 'WordPress',
					'icon_class' => 'cswi cswi-wordpress',
				),
				'xing' => array(
					'name' => 'Xing',
					'icon_class' => 'cswi cswi-xing',
				),
				'yelp' => array(
					'name' => 'Yelp',
					'icon_class' => 'cswi cswi-yelp',
				),
				'youtube' => array(
					'name' => 'YouTube',
					'icon_class' => 'cswi cswi-youtube',
				),
			);
			return apply_filters( 'candy_social_widget_social_ops', $options );
		}
	}

}

/* Register Candy_Social_Widget widget */
add_action( 'widgets_init', function() {
    register_widget( 'Candy_Social_Widget' );
} );