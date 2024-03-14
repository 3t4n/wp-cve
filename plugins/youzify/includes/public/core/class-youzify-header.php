<?php

if ( ! class_exists( 'Youzify_Header' ) ) {

class Youzify_Header {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function __construct() {

		// Add Profile Header
		if ( 'youzify-horizontal-layout' == youzify_get_profile_layout() ) {
			add_action( 'youzify_profile_header', array( $this, 'profile_header' ) );
		} else {

			$layout = youzify_option( 'youzify_profile_layout', 'youzify-right-sidebar' );

			switch ( $layout ) {

				case 'youzify-3columns':
       				if ( youzify_option( 'youzify_profile_vertical_header_position', 'left' ) == 'left' ) {
						add_action( 'youzify_before_left_sidebar_widgets', array( $this, 'profile_header' ) );
       				} else {
						add_action( 'youzify_before_right_sidebar_widgets', array( $this, 'profile_header' ) );
       				}
					break;

				case 'youzify-left-sidebar':
					add_action( 'youzify_before_left_sidebar_widgets', array( $this, 'profile_header' ) );
					break;

				case 'youzify-right-sidebar':
					add_action( 'youzify_before_right_sidebar_widgets', array( $this, 'profile_header' ) );
					break;

			}

		}

		// Add Group Header
		add_action( 'youzify_group_header', array( $this, 'group_header' ) );

		// Remove Issues With Prefetching Adding Extra Views
		remove_action( 'wp_head', array( $this, 'adjacent_posts_rel_link_wp_head' ) , 10, 0 );

	}

	/**
	 * Profile Header.
	 */
	function profile_header() {

		if ( ! apply_filters( 'youzify_display_profile_header', true ) ) {
			return;
		}

		// Get Header Layout.
		$header_layout = youzify_option( 'youzify_header_layout', 'hdr-v1' );

		if ( false !== strpos( $header_layout, 'youzify-author' ) ) {
			// Get Auhtor Box Arguments.
			$args = array(
				'target'  	=> 'header',
				'layout'  	=> $header_layout,
				'user_id' 	=> bp_displayed_user_id(),
				'meta_icon' => youzify_option( 'youzify_header_meta_icon', 'fas fa-map-marker-alt' ),
				'meta_type' => youzify_option( 'youzify_header_meta_type', 'full_location' ),
				'cover_overlay'	=> youzify_option( 'youzify_enable_header_overlay', 'on' ),
				'cover_pattern'	=> youzify_option( 'youzify_enable_header_pattern', 'on' )
			);
			// Get Author Box Header.
	 		youzify_author_box()->get_author_box( $args );
		} else {
			// Get Standard Header.
			$this->get_header( 'youzify_users' );
		}

	}

	/**
	 * Group Header.
	 */
	function group_header() {

		if ( ! apply_filters( 'youzify_display_group_header', true ) ) {
			return;
		}

		// Get Standard Header.
		$this->get_header( 'youzify_groups' );

	}

	/**
	 * Get Profile Header.
	 */
	function get_header( $component ) {

		?>

		<div class="youzify-header-cover">
			<?php echo $component()->cover(); ?>
			<?php if ( bp_is_my_profile() ) echo '<span class="youzify-open-upload-cover youzify-open-bp-uploader" data-type="cover"><i class="fas fa-camera"></i><span class="youzify-cover-uploader-label">' . __( 'Change Cover', 'youzify' ) . '</span></span>'; ?>
			<?php do_action( 'youzify_before_header_cover' ); ?>
			<div class="youzify-cover-content">
				<?php do_action( 'youzify_before_header_cover_content' ); ?>
				<?php $this->get_elements( $component, 'firstrow', 'outer' ); ?>
				<div class="youzify-inner-content">
					<?php do_action( 'youzify_before_header_cover_inner_content' ); ?>
					<?php $this->get_elements( $component, 'firstrow', 'photo' ); ?>
					<div class="youzify-head-content">
						<?php do_action( 'youzify_before_header_cover_head_content' ); ?>
						<?php $this->get_elements( $component, 'firstrow', 'head' ); ?>
						<?php do_action( 'youzify_after_header_cover_head_content' ); ?>
					</div>
					<?php $this->get_elements( $component, 'firstrow', 'inner' ); ?>
					<?php do_action( 'youzify_after_header_cover_inner_content' ); ?>
				</div>
				<?php do_action( 'youzify_after_header_cover_content' ); ?>
			</div>
			<?php do_action( 'youzify_after_header_cover' ); ?>
		</div>

		<div class="youzify-header-content">
			<div class="youzify-header-head">
				<?php $this->get_elements( $component, 'secondrow', 'inner' ); ?>
			</div>
			<?php $this->get_elements( $component, 'secondrow', 'outer' ); ?>
		</div>

		<?php
	}

	/**
	 * Header Class.
	 */
	function get_class( $component ) {

		// Create Empty Array.
		$header_class = array();

		// Add Header Main Class
		$header_class[] = 'youzify-profile-header';

		// Get Options.
		if ( 'user' == $component ) {
			// $header_type 	= youzify_option( 'youzify_header_type' );
			$header_layout 	= youzify_option( 'youzify_header_layout', 'hdr-v1' );
			$header_effect 	= youzify_option( 'youzify_hdr_load_effect', 'fadeIn' );
			$header_overlay	= youzify_option( 'youzify_enable_header_overlay', 'on' );
			$header_pattern	= youzify_option( 'youzify_enable_header_pattern', 'on' );
		} elseif ( 'group' == $component ) {
			// $header_type 	= youzify_option( 'youzify_group_header_type' );
			$header_layout 	= youzify_option( 'youzify_group_header_layout', 'hdr-v1' );
			$header_overlay	= youzify_option( 'youzify_enable_group_header_overlay', 'on' );
			$header_pattern	= youzify_option( 'youzify_enable_group_header_pattern', 'on' );
		}

		// Add a class depending on another one.
		if ( 'hdr-v4' == $header_layout || 'hdr-v5' == $header_layout ) {
			$header_class[] = "youzify-hdr-v3";
		} elseif ( 'hdr-v8' == $header_layout ) {
			$header_class[] = "youzify-hdr-v1";
		}

		// Add header layout.
		$header_class[] = "youzify-$header_layout";

		// Add header cover overlay.
		if ( 'on' == $header_overlay ) {
			$header_class[] = 'youzify-header-overlay';
		}

		// Add header cover pattern.
		if ( 'on' == $header_pattern ) {
			$header_class[] = 'youzify-header-pattern';
		}

		if ( 'user' == $component ) {
			// Add effect class.
		 	$header_class[] = youzify_widgets()->get_loading_effect( $header_effect, 'class' );
		}

	 	// Return Class Name.
		return youzify_generate_class( $header_class );
	}

	/**
	 * Header Structure
	 */
	function get_header_structure( $component ) {

		$args = array();

		$args['hdr-v1'] = array(
			'firstrow' 	=> array(
				'photo'	=> array( 'photo' ),
				'head' 	=> array( 'name', 'ratings', 'meta' ),
				'inner' => array( 'statistics' ),
			)
		);

		$args['hdr-v2'] = array(
			'firstrow' 	=> array(
				'outer' => array( 'photo' ),
				'inner' => array( 'name', 'ratings', 'meta' )
			),
			'secondrow' => array(
				'outer' => array( 'networks', 'statistics' )
			)
		);

		$args['hdr-v3'] = array(
			'firstrow' 	=> array(
				'inner' => array( 'photo', 'name', 'ratings', 'meta', 'networks'  )
			)
		);

		$args['hdr-v7'] = array(
			'firstrow' 	=> array(
				'outer' => array( 'photo' ),
				'inner' => array( 'ratings', 'networks' )
			),
			'secondrow' => array(
				'inner' => array( 'name', 'meta' ),
				'outer' => array( 'statistics' )
			)
		);

		$args['hdr-v8'] = array(
			'firstrow' 	=> array(
				'photo'	=> array( 'photo' ),
				'head' 	=> array( 'name', 'ratings', 'meta', 'networks' ),
				'inner' => array( 'statistics' ),
			)
		);

		$args = apply_filters( 'youzify_profile_headers_args', $args );

		// Get Header Layout.
		if ( 'youzify_users' == $component ) {
			$header_layout = youzify_option( 'youzify_header_layout', 'hdr-v1' );
		} elseif ( 'youzify_groups' == $component ) {
			$header_layout = youzify_option( 'youzify_group_header_layout', 'hdr-v1' );
		}

		// Mutual Structure
		$common_structure = array( 'hdr-v4', 'hdr-v5', 'hdr-v6' );

		// Return Structure
		if ( in_array( $header_layout, $common_structure ) ) {
			return $args['hdr-v3'];
		} else {
			return $args[ $header_layout ];
		}

	}

	/**
	 * Header Elements Generator
	 */
	function get_elements( $component, $row, $target ) {

		// Get Header Structure
		$header_args = $this->get_header_structure( $component );
		if ( isset( $header_args[ $row ][ $target ] ) ) :
			foreach ( $header_args[ $row ][ $target ] as $element ) {
				do_action( 'youzify_before_header_' . $element .'_' . $target );
				$component()->$element();
				do_action( 'youzify_after_header_' . $element .'_' . $target );
			}
		endif;

	}
}


/**
 * Get a unique instance of Youzify Header.
 */
function youzify_headers() {
	return Youzify_Header::get_instance();
}

/**
 * Launch Youzify Groups!
 */
youzify_headers();

}