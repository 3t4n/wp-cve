<?php
namespace HAL;

class Main {
	/**
	 * The instance *Singleton* of this class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Main the *Singleton* instance.
	 */
	public static function get_instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_plugin_settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'get_the_archive_title', array( $this, 'get_title_label' ) );
		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );
	}

	public function enqueue_scripts() {
		if ( isset( $_GET['page'] ) && 'hide-archive-label' === $_GET['page'] ) {
			wp_enqueue_style( 'hide-archive-label', HAL_URL . 'css/admin-dashboard.css', array(), HAL_VERSION, 'all' );
			wp_register_script( 'match-height', HAL_URL . 'js/jquery-matchHeight.min.js', array( 'jquery' ), HAL_VERSION, false );
			wp_enqueue_script( 'hide-archive-label', HAL_URL . 'js/hide-archive-label-admin.js', array( 'match-height', 'jquery-ui-tooltip' ), HAL_VERSION, false );
		}
		wp_enqueue_style( 'hide-archive-label-admin-menu-icon', HAL_URL . 'css/menu-icon.css', array(), HAL_VERSION, 'all' );
	}

	/**
	 * Remove archive labels.
	 *
	 * @param  string $title Current archive title to be displayed.
	 * @return string        Modified archive title to be displayed.
	 */
	public function get_title_label( $title = '' ) {
		$options = $this->hide_archive_label_get_options();
		if ( 'remove_accessibly' === $options['remove'] ) {
			$title_label = '<span class="' . ARCHIVE_TITLE_CSS_A11Y . '">%1$s</span>%2$s';
		} else {
			$title_label = '%2$s';
		}

		$labels = $this->archive_label_options();
		if ( empty( $labels ) ) {
			return $title;
		}
		$selected = array_filter( $options );
		$labels   = array_intersect_key( $labels, $selected );
		foreach ( $labels as $key => $label ) {
			if ( is_callable( $key ) && call_user_func( $key ) ) {
				if ( $key() ) {
					$title = call_user_func( __CLASS__ . '::get_title_' . $key, $title_label );
					break;
				}
			}
		}

		return trim( (string) $title );
	}

	public static function get_title_is_category( $title ) {
		return sprintf(
			$title,
			esc_html_x( 'Category:', 'Archive title label.', 'archive-title' ),
			single_cat_title( '', false )
		);
	}

	public static function get_title_is_tag( $title ) {
		return sprintf(
			$title,
			esc_html_x( 'Tag:', 'Archive title label.', 'archive-title' ),
			single_tag_title( '', false )
		);
	}

	public static function get_title_is_author( $title ) {
		return sprintf(
			$title,
			esc_html_x( 'Author:', 'Archive title label.', 'archive-title' ),
			'<span class="vcard">' . get_the_author() . '</span>'
		);
	}

	public static function get_title_is_tax( $title ) {
		$queried_object = get_queried_object();
		if ( $queried_object ) {
			$tax = get_taxonomy( $queried_object->taxonomy );
			/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term. */
			return sprintf(
				$title,
				$tax->labels->singular_name,
				single_term_title( '', false )
			);
		}

	}

	public static function get_title_is_post_type_archive( $title ) {
		return sprintf(
			$title,
			esc_html_x( 'Archives:', 'Archive title label.', 'archive-title' ),
			post_type_archive_title( '', false )
		);
	}

	public static function get_title_is_year( $title ) {
		return sprintf(
			$title,
			esc_html_x( 'Year:', 'Archive title label.', 'archive-title' ),
			get_the_date( _x( 'Y', 'yearly archives date format' ) )
		);
	}

	public static function get_title_is_month( $title ) {
		return sprintf(
			$title,
			esc_html_x( 'Month:', 'Archive title label.', 'archive-title' ),
			get_the_date( _x( 'F Y', 'monthly archives date format' ) )
		);
	}

	public static function get_title_is_day( $title ) {
		return sprintf(
			$title,
			esc_html_x( 'Day:', 'Archive title label.', 'archive-title' ),
			get_the_date( _x( 'F j, Y', 'daily archives date format' ) )
		);
	}

	public function archive_label_options() {
		$options = array(
			'is_category'          => esc_html__( 'Category', 'hide-archive-label' ),
			'is_tag'               => esc_html__( 'Tag', 'hide-archive-label' ),
			'is_author'            => esc_html__( 'Author', 'hide-archive-label' ),
			'is_tax'               => esc_html__( 'Taxonomy', 'hide-archive-label' ),
			'is_post_type_archive' => esc_html__( 'Archives', 'hide-archive-label' ),
			'is_year'              => esc_html__( 'Year', 'hide-archive-label' ),
			'is_month'             => esc_html__( 'Month', 'hide-archive-label' ),
			'is_day'               => esc_html__( 'Day', 'hide-archive-label' ),
		);
		return $options;
	}

	/**
	 * Add settings menu
	 */
	public function add_plugin_settings_menu() {
		$icon = HAL_URL . 'images/icon.svg';
		// Add menu under tools
		add_submenu_page(
			'tools.php',
			esc_html_x( 'Hide Archive Label', 'UI String', 'hide-archive-label' ),
			esc_html_x( 'Hide Archive Label', 'UI String', 'hide-archive-label' ),
			'manage_options',
			'hide-archive-label',
			array( $this, 'settings_page' )
		);
	}

	/**
	 * Hide Archive Label: settings_page
	 * Hide Archive Label Setting function
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		require HAL_PATH . 'inc/partials/admin-display.php';
	}

	/**
	 * Hide Archive Label: action_links
	 * Hide Archive Label Settings Link function callback
	 *
	 * @param arrray $links Link url.
	 *
	 * @param arrray $file File name.
	 */
	function action_links( $links, $file ) {
		if ( 'hide-archive-label/class-hide-archive-label.php' === $file ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=hide-archive-label' ) ) . '">' . esc_html__( 'Settings', 'hide-archive-label' ) . '</a>';

			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	public function register_settings() {
		register_setting(
			'hide-archive-label-group',
			'hide_archive_label_options',
			array( $this, 'sanitize_callback' )
		);
	}

	public function sanitize_callback( $input ) {
		if ( isset( $input['reset'] ) && $input['reset'] ) {
			// If reset, restore defaults
			return $this->hide_archive_label_default_options();
		}
		// Verify the nonce before proceeding.
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		|| ( ! isset( $_POST['hide_archive_label_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hide_archive_label_nonce'] ) ), HAL_BASENAME ) )
		|| ( ! check_admin_referer( HAL_BASENAME, 'hide_archive_label_nonce' ) ) ) {

			return esc_html__( 'Invalid Nonce', 'hide-archive-label' );

		} else {
			if ( null !== $input ) {
				$input['remove']               = sanitize_key( $input['remove'] );
				$input['is_category']          = ( isset( $input['is_category'] ) && '1' === $input['is_category'] ) ? '1' : '0';
				$input['is_tag']               = ( isset( $input['is_tag'] ) && '1' === $input['is_tag'] ) ? '1' : '0';
				$input['is_author']            = ( isset( $input['is_author'] ) && '1' === $input['is_author'] ) ? '1' : '0';
				$input['is_tax']               = ( isset( $input['is_tax'] ) && '1' === $input['is_tax'] ) ? '1' : '0';
				$input['is_post_type_archive'] = ( isset( $input['is_post_type_archive'] ) && '1' === $input['is_post_type_archive'] ) ? '1' : '0';
				$input['is_year']              = ( isset( $input['is_year'] ) && '1' === $input['is_year'] ) ? '1' : '0';
				$input['is_month']             = ( isset( $input['is_month'] ) && '1' === $input['is_month'] ) ? '1' : '0';
				$input['is_day']               = ( isset( $input['is_day'] ) && '1' === $input['is_day'] ) ? '1' : '0';
			}

			return $input;
		}
	}

	public function hide_archive_label_default_options( $option = null ) {
		$default_options = array(
			'remove'               => 'remove_accessibly',
			'is_category'          => '0',
			'is_tag'               => '0',
			'is_author'            => '0',
			'is_tax'               => '0',
			'is_post_type_archive' => '0',
			'is_year'              => '0',
			'is_month'             => '0',
			'is_day'               => '0',
		);

		if ( null === $option ) {
			return apply_filters( 'hide_archive_label_options', $default_options );
		} else {
			return $default_options[ $option ];
		}
	}

	public function hide_archive_label_get_options() {
		$defaults = $this->hide_archive_label_default_options();
		$options  = get_option( 'hide_archive_label_options', $defaults );

		return wp_parse_args( $options, $defaults );
	}
}
