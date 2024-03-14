<?php
/**
 * Static class for registering and displaying the widget.
 *
 * @package     Connections Login Form
 * @subpackage  Widget
 * @copyright   Copyright (c) 2014, Steven A. Zahm
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

use Connections_Directory\Shortcode\Login_Form;
use Connections_Directory\Utility\_array;

/**
 * Class CN_Login_Form_Widget
 */
class CN_Login_Form_Widget extends WP_Widget {

	/**
	 * Copy of the widget configured options.
	 *
	 * @access public
	 * @since  2.0
	 *
	 * @var array
	 */
	public $instance = array();

	public function __construct() {

		$options = array(
			'description' => __( 'Login', 'connections_login' ),
		);

		parent::__construct(
			'cnl_login_form',
			'Connections : ' . __( 'Login Form', 'connections_login' ),
			$options
		);
	}

	/**
	 * Registers the widget with the WordPress Widget API.
	 *
	 * @access public
	 * @since  2.0
	 *
	 * @return void
	 */
	public static function register() {

		register_widget( __CLASS__ );
	}

	/**
	 * The widget default options.
	 *
	 * @access private
	 * @since  2.0
	 * @static
	 *
	 * @return array
	 */
	public function defaults() {

		$defaults = array(
			'title-logged-out'             => esc_html__( 'Login', 'connections_login' ),
			'title-logged-in'              => esc_html__( 'Welcome %username%', 'connections_login' ),
			'image'                        => 'none',
			'image_size'                   => 38,
			'display_entry_only'           => '0',
			'display_remember_me_checkbox' => '1',
			'display_lost_password_link'   => '1',
			'display_register_link'        => '0',
			'display_profile_link'         => '1',
			'display_logout_link'          => '1',
			'links-logged-in'              => '',
			'links-logged-out'             => '',
		);

		/**
		 * Filter the widget's default settings.
		 *
		 * @since 2.0
		 *
		 * @param array $defaults
		 */
		return apply_filters( 'cn_login_widget_default_settings', $defaults );
	}

	/**
	 * The supported image types.
	 *
	 * @access private
	 * @since  2.0
	 * @static
	 *
	 * @return array
	 */
	private function imageTypes() {

		/**
		 * Filter the widget's supported image types.
		 *
		 * @since 2.0
		 *
		 * @param array
		 */
		return apply_filters(
			'cn_login_image_types',
			array(
				'none'   => esc_html__( 'None', 'connections_login' ),
				'avatar' => esc_html__( 'Avatar', 'connections_login' ),
			)
		);
	}

	/**
	 * Logic for handling updates from the widget form.
	 *
	 * @access  private
	 * @since  1.0
	 * @param array $new
	 * @param array $old
	 *
	 * @return array
	 */
	public function update( $new, $old ) {

		// Common Settings.
		$new['display_entry_only'] = _array::get( $new, 'display_entry_only', '0' );

		// Logged-out Settings.
		$new['title-logged-out']             = sanitize_text_field( $new['title-logged-out'] );
		$new['links-logged-out']             = wp_kses_data( $new['links-logged-out'] );
		$new['display_remember_me_checkbox'] = _array::get( $new, 'display_remember_me_checkbox', '0' );
		$new['display_lost_password_link']   = _array::get( $new, 'display_lost_password_link', '0' );
		$new['display_register_link']        = _array::get( $new, 'display_register_link', '0' );

		// Logged-in Settings.
		$new['title-logged-in']      = sanitize_text_field( $new['title-logged-in'] );
		$new['links-logged-in']      = wp_kses_data( $new['links-logged-in'] );
		$new['display_profile_link'] = _array::get( $new, 'display_profile_link', '0' );
		$new['display_logout_link']  = _array::get( $new, 'display_logout_link', '0' );
		$new['image']                = isset( $new['image'] ) && in_array( $new['image'], array_keys( $this->imageTypes() ) ) ? $new['image'] : 'none';
		$new['image_size']           = isset( $new['image_size'] ) ? absint( $new['image_size'] ) : 38;

		/**
		 * Filter the widget's update settings.
		 *
		 * @since 2.0
		 *
		 * @param array $new
		 * @param array $old
		 */
		return apply_filters( 'cn_login_widget_update_settings', $new, $old );
	}

	/**
	 * Function for handling the widget control in admin panel.
	 *
	 * @access private
	 * @since  1.0
	 * @param  array $instance
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( $instance, $this->defaults() );

		?>
		<h4><?php esc_html_e( 'Common Settings', 'connections_login' ); ?></h4>
		<?php

		/**
		 * @since 2.0
		 *
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_before_widget_common_settings', $instance, $this );

		cnHTML::field(
			array(
				'type'   => 'checkbox',
				'prefix' => '',
				'id'     => $this->get_field_id( 'display_entry_only' ),
				'name'   => $this->get_field_name( 'display_entry_only' ),
				'label'  => esc_html__( 'Display only on the single entry detail/profile page?', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
				'layout' => '%field% %label%',
			),
			$instance['display_entry_only']
		);

		/**
		 * @since 2.0
		 *
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_after_widget_common_settings', $instance, $this );

		?>
		<hr style="border: 2px solid #ddd; margin: 1em 0">

		<h4><?php esc_html_e( 'User Logged-out Settings', 'connections_login' ); ?></h4>
		<?php

		/**
		 * @todo If the user has an image or logo, show it as a thumbnail with their entry name which will link to
		 *       their directory entry if the user has been linked.
		 */

		/**
		 * @since 2.0
		 *
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_before_widget_logged_out_settings', $instance, $this );

		cnHTML::text(
			array(
				'prefix' => '',
				'class'  => 'widefat',
				'id'     => $this->get_field_id( 'title-logged-out' ),
				'name'   => $this->get_field_name( 'title-logged-out' ),
				'label'  => esc_html__( 'Title:', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
			),
			esc_html( $instance['title-logged-out'] )
		);

		cnHTML::field(
			array(
				'type'   => 'checkbox',
				'prefix' => '',
				'id'     => $this->get_field_id( 'display_remember_me_checkbox' ),
				'name'   => $this->get_field_name( 'display_remember_me_checkbox' ),
				'label'  => esc_html__( 'Display the "Remember me" checkbox?', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
				'layout' => '%field% %label%',
			),
			$instance['display_remember_me_checkbox']
		);

		cnHTML::field(
			array(
				'type'   => 'checkbox',
				'prefix' => '',
				'id'     => $this->get_field_id( 'display_lost_password_link' ),
				'name'   => $this->get_field_name( 'display_lost_password_link' ),
				'label'  => esc_html__( 'Display the "Lost Password" link?', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
				'layout' => '%field% %label%',
			),
			$instance['display_lost_password_link']
		);

		if ( get_option( 'users_can_register' ) ) {

			cnHTML::field(
				array(
					'type'   => 'checkbox',
					'prefix' => '',
					'id'     => $this->get_field_id( 'display_register_link' ),
					'name'   => $this->get_field_name( 'display_register_link' ),
					'label'  => esc_html__( 'Display the registration link?', 'connections_login' ),
					'before' => '<p>',
					'after'  => '</p>',
					'layout' => '%field% %label%',
				),
				$instance['display_register_link']
			);
		}

		cnHTML::textarea(
			array(
				'prefix' => '',
				'class'  => 'widefat',
				'cols'   => 20,
				'rows'   => 3,
				'id'     => $this->get_field_id( 'links-logged-out' ),
				'name'   => $this->get_field_name( 'links-logged-out' ),
				'label'  => esc_html__( 'Custom Links:', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
			),
			$instance['links-logged-out']
		);

		/**
		 * @since 2.0
		 *
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_after_widget_logged_out_settings', $instance, $this );

		?>
		<hr style="border: 2px solid #ddd; margin: 1em 0">

		<h4><?php esc_html_e( 'User Logged-in Settings', 'connections_login' ); ?></h4>
		<?php

		/**
		 * @since 2.0
		 *
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_before_widget_logged_in_settings', $instance, $this );

		cnHTML::text(
			array(
				'prefix' => '',
				'class'  => 'widefat',
				'id'     => $this->get_field_id( 'title-logged-in' ),
				'name'   => $this->get_field_name( 'title-logged-in' ),
				'label'  => esc_html__( 'Title:', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
			),
			esc_html( $instance['title-logged-in'] )
		);

		cnHTML::field(
			array(
				'type'    => 'select',
				'prefix'  => '',
				'id'      => $this->get_field_id( 'image' ),
				'name'    => $this->get_field_name( 'image' ),
				'label'   => __( 'Show Image:', 'connections_login' ),
				'before'  => '<p>',
				'after'   => '</p>',
				'layout'  => '%label% %field%',
				'options' => $this->imageTypes(),
			),
			$instance['image']
		);

		cnHTML::input(
			array(
				'type'   => 'number',
				'prefix' => '',
				'class'  => 'small-text',
				'id'     => $this->get_field_id( 'image_size' ),
				'name'   => $this->get_field_name( 'image_size' ),
				'label'  => __( 'Image Size:', 'connections_login' ),
				'before' => '<p>',
				'after'  => 'px</p>',
				'layout' => '%label% %field%',
			),
			absint( $instance['image_size'] )
		);

		cnHTML::field(
			array(
				'type'   => 'checkbox',
				'prefix' => '',
				'id'     => $this->get_field_id( 'display_profile_link' ),
				'name'   => $this->get_field_name( 'display_profile_link' ),
				'label'  => esc_html__( 'Display the user profile link?', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
				'layout' => '%field% %label%',
			),
			$instance['display_profile_link']
		);

		cnHTML::field(
			array(
				'type'   => 'checkbox',
				'prefix' => '',
				'id'     => $this->get_field_id( 'display_logout_link' ),
				'name'   => $this->get_field_name( 'display_logout_link' ),
				'label'  => esc_html__( 'Display the logout link?', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
				'layout' => '%field% %label%',
			),
			$instance['display_logout_link']
		);

		cnHTML::textarea(
			array(
				'prefix' => '',
				'class'  => 'widefat',
				'cols'   => 20,
				'rows'   => 3,
				'id'     => $this->get_field_id( 'links-logged-in' ),
				'name'   => $this->get_field_name( 'links-logged-in' ),
				'label'  => esc_html__( 'Custom Links:', 'connections_login' ),
				'before' => '<p>',
				'after'  => '</p>',
			),
			$instance['links-logged-in']
		);

		/**
		 * @since 2.0
		 *
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_after_widget_logged_in_settings', $instance, $this );

		?>

		<hr style="border: 2px solid #ddd; margin: 1em 0">

		<h4><?php esc_html_e( 'Instructions', 'connections_login' ); ?></h4>

		<p class="description">
			<?php _e( 'Enter one link per line as <code>Text | URL | Capability</code>', 'connections_login' ); ?><br>
			<?php _e( 'In place of the <code>Text</code> you can use placeholder tokens.', 'connections_login' ); ?><br>
			<?php _e( 'In place of the <code>URL</code> you can use a URL placeholder tokens.', 'connections_login' ); ?><br>
			<?php _e( 'The <code>Capability</code> is optional.', 'connections_login' ); ?><br>
			<?php _e( 'Example: <code>Profile | %profile_url% | edit_posts</code>', 'connections_login' ); ?>
		</p>

		<p class="description">
			<?php _e( 'Supported placeholder tokens are:', 'connections_login' ); ?>
		</p>

		<ul class="description">

			<?php

			$tokens = array_map( 'esc_html', Connections_Login::supportedTokens() );

			echo '<li><code>' . implode( '</code></li><li><code>', $tokens ) . '</code></li>';

			?>
		</ul>

		<?php
	}

	/**
	 * Function for displaying the widget on the page.
	 *
	 * @access private
	 * @since  1.0
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		$instance = wp_parse_args( $instance, $this->defaults() );

		/*
		 * Convert some of the $atts values in the array to boolean.
		 */
		cnFormatting::toBoolean( $instance['display_entry_only'] );
		cnFormatting::toBoolean( $instance['display_remember_me_checkbox'] );
		cnFormatting::toBoolean( $instance['display_register_link'] );

		// Make the saved options available.
		$this->instance = $instance;

		if ( $instance['display_entry_only'] && ! get_query_var( 'cn-entry-slug' ) ) {
			return;
		}

		/**
		 * @var string $before_widget
		 * @var string $after_widget
		 * @var string $before_title
		 * @var string $after_title
		 */
		extract( $args );

		echo $before_widget;

		/**
		 * @since 2.0
		 *
		 * @param array                $args
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_widget_before', $args, $instance, $this );

		if ( is_user_logged_in() ) {

			$title = apply_filters( 'widget_title', $instance['title-logged-in'], $instance, $this->id_base, $this );
			$title = Connections_Login::replaceTokens( $title, 'string' );

			echo $before_title . $title . $after_title . PHP_EOL;

			/**
			 * @since 2.0
			 *
			 * @param array                $args
			 * @param array                $instance
			 * @param CN_Login_Form_Widget $this
			 */
			do_action( 'cn_login_widget_logged_in_before', $args, $instance, $this );

			if ( in_array( $instance['image'], array_keys( $this->imageTypes() ) ) ) {

				$this->displayImage( $instance['image'] );
			}

			$this->renderLinks( 'logged_in' );

			/**
			 * @since 2.0
			 *
			 * @param array                $args
			 * @param array                $instance
			 * @param CN_Login_Form_Widget $this
			 */
			do_action( 'cn_login_widget_logged_in_after', $args, $instance, $this );

		} else {

			$title = apply_filters( 'widget_title', $instance['title-logged-out'], $instance, $this->id_base, $this );

			echo $before_title . $title . $after_title . PHP_EOL;

			/**
			 * @since 2.0
			 *
			 * @param array                $args
			 * @param array                $instance
			 * @param CN_Login_Form_Widget $this
			 */
			do_action( 'cn_login_widget_logged_out_before', $args, $instance, $this );

			$atts = array(
				'remember' => $instance['display_remember_me_checkbox'],
			);

			$form = new Login_Form( $atts );
			$form->render();

			$this->renderLinks( 'logged_out' );

			/**
			 * @since 2.0
			 *
			 * @param array                $args
			 * @param array                $instance
			 * @param CN_Login_Form_Widget $this
			 */
			do_action( 'cn_login_widget_logged_out_after', $args, $instance, $this );
		}

		/**
		 * @since 2.0
		 *
		 * @param array                $args
		 * @param array                $instance
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_widget_after', $args, $instance, $this );

		echo $after_widget;
	}

	/**
	 * Render the image.
	 *
	 * @access private
	 * @since  2.0
	 * @static
	 *
	 * @param string $type The image type to display.
	 */
	public function displayImage( $type ) {

		ob_start();

		switch ( $type ) {

			case 'avatar':
				$user = wp_get_current_user();

				echo get_avatar( $user->ID, $this->instance['image_size'] );
				break;

			default:
				/**
				 * @since 2.0
				 *
				 * @param CN_Login_Form_Widget $this
				 */
				do_action( "cn_login_display_image_{$type}", $this );
				break;
		}

		$html = ob_get_clean();

		if ( 1 < strlen( $html ) ) {

			echo '<div class="cn_image_container">' . $html . '</div>';
		}
	}

	/**
	 * Renders the links in an unordered list.
	 *
	 * @access private
	 * @since  2.0
	 * @static
	 *
	 * @param string $context Use `logged_in` to render the links displayed to the user when logged in.
	 *                        Use `logged_out` to render the links displayed to the user when logged out.
	 */
	public function renderLinks( $context ) {

		$user  = wp_get_current_user();
		$links = array();

		if ( 'logged_in' == $context ) {

			if ( $this->instance['display_profile_link'] ) {

				$links['profile'] = array(
					'text' => esc_html__( 'Profile', 'connections_login' ),
					'url'  => get_edit_profile_url( $user->ID ),
				);
			}

			if ( $this->instance['display_logout_link'] ) {

				$links['logout'] = array(
					'text' => esc_html__( 'Logout', 'connections_login' ),
					'url'  => apply_filters( 'cn_login_logout_url', wp_logout_url( get_permalink() ) ),
				);
			}

			$links = array_merge( $links, $this->parseLinks( $this->instance['links-logged-in'] ) );
		}

		if ( 'logged_out' == $context ) {

			if ( $this->instance['display_lost_password_link'] ) {

				$links['lost_password'] = array(
					'text' => esc_html__( 'Lost Password', 'connections_login' ),
					'url'  => apply_filters( 'cn_login_widget_lost_password_url', wp_lostpassword_url() ),
				);
			}

			if ( get_option( 'users_can_register' ) && $this->instance['display_register_link'] ) {

				if ( ! is_multisite() ) {

					$links['register'] = array(
						'text' => esc_html__( 'Register', 'connections_login' ),
						'url'  => apply_filters( 'cn_login_widget_register_url', site_url( 'wp-login.php?action=register', 'login' ) ),
					);

				} else {

					$links['register'] = array(
						'text' => esc_html__( 'Register', 'connections_login' ),
						'url'  => apply_filters( 'cn_login_widget_register_url', site_url( 'wp-signup.php', 'login' ) ),
					);

				}

			}

			$links = array_merge( $links, $this->parseLinks( $this->instance['links-logged-out'] ) );
		}

		/**
		 * The dynamic portion of the hook name is the context.
		 *
		 * @since 2.0
		 *
		 * @param array                $links
		 * @param CN_Login_Form_Widget $this
		 */
		$links = apply_filters( 'cn_login_widget_' . $context . '_links', $links, $this );

		/**
		 * The dynamic portion of the hook name is the context.
		 *
		 * @since 2.0
		 *
		 * @param array                $links
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_widget_before_' . $context . '_links', $links, $this );

		if ( ! empty( $links ) && is_array( $links ) && 0 < count( $links ) ) {

			echo '<ul class="pagenav cn_login_links">';

			foreach ( $links as $id => $link ) {

				$anchor = apply_filters(
					'cn_login_widget_link_anchor',
					'<a href="' . esc_url( Connections_Login::replaceTokens( $link['url'], 'url', array( 'logout_url' => wp_logout_url( get_permalink() ) ) ) ) . '">' . esc_html( Connections_Login::replaceTokens( $link['text'] ) ) . '</a>',
					$id,
					$link,
					$context,
					$this
				);

				echo '<li class="' . esc_attr( $id ) . '-link">' . $anchor . '</li>';
			}

			echo '</ul>';
		}

		/**
		 * The dynamic portion of the hook name is the context.
		 *
		 * @since 2.0
		 *
		 * @param array                $links
		 * @param CN_Login_Form_Widget $this
		 */
		do_action( 'cn_login_widget_after_' . $context . '_links', $links, $this );
	}

	/**
	 * Parse the Custom Links textarea input.
	 *
	 * @access private
	 * @since  2.0
	 * @static
	 *
	 * @param $string
	 *
	 * @return array
	 */
	public function parseLinks( $string ) {

		$raw   = array_map( 'trim', preg_split( '/\r\n|[\r\n]/', $string ) );
		$links = array();

		foreach ( $raw as $link ) {

			$link       = array_map( 'trim', explode( '|', $link ) );
			$capability = '';

			if ( sizeof( $link ) == 3 ) {

				list( $text, $url, $capability ) = $link;

			} elseif ( sizeof( $link ) == 2 ) {

				list( $text, $url ) = $link;

			} else {

				continue;
			}

			// Check capability.
			if ( ! empty( $capability ) ) {

				if ( ! current_user_can( strtolower( $capability ) ) ) {
					continue;
				}
			}

			$links[ sanitize_title( $text ) ] = array(
				'text' => $text,
				'url'  => $url,
			);
		}

		return $links;
	}
}
