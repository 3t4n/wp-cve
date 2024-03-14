<?php

class Youzify_Author {

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

	function __construct() { /** Do Nothing Here **/ }

	/**
	 * Author Box
	 */
	function get_author_box( $args ) {

		// Get User Id.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();

		?>

		<div class="youzify-author <?php echo $this->get_cover_class( $args ); ?>">

			<?php youzify_get_user_tools( $user_id ) ?>

			<!-- Box Content -->
			<?php $this->get_elements( $args ); ?>

		</div>

		<?php

	}

	/**
	 * Author Box Structure
	 */
	function get_box_structure( $layout = null ) {

		// Set Up New Array.
		$structure = array();

		$structure['youzify-author-v1'] = array(
			'cover'	  => array( 'photo' ),
			'content' => array( 'box_head', 'badges', 'ratings', 'buttons', 'networks', 'statistics' )
		);

		$structure['youzify-author-v2'] = array(
			'cover'	  => array( 'photo' ),
			'content' => array( 'box_head', 'badges', 'ratings', 'buttons', 'statistics', 'networks' )
		);

		$structure['youzify-author-v3'] = array(
			'cover'	  => array( 'photo', 'box_head' ),
			'content' => array( 'ratings', 'badges', 'statistics' , 'buttons', 'networks' )
		);

		$structure['youzify-author-v4'] = array(
			'cover'	  => array( 'photo', 'box_head' ),
			'content' => array( 'ratings', 'badges', 'buttons', 'networks', 'statistics' )
		);

		$structure['youzify-author-v5'] = array(
			'content' => array( 'photo', 'box_head', 'badges', 'ratings', 'buttons', 'statistics', 'networks' )
		);

		$structure['youzify-author-v6'] = array(
			'cover'	=> array( 'photo', 'box_head', 'ratings', 'badges', 'buttons', 'networks', 'statistics' )
		);

		$structure = apply_filters( 'youzify_get_author_box_structure', $structure, $layout );

		return $structure[ $layout ];

	}

	/**
	 * Author Box Elements Generator
	 */
	function get_elements( $args = null ) {

		$elements = array( 'cover', 'content' );

		// Get Header Structure
		$header_args = $this->get_box_structure( $args['layout'] );

		foreach ( $elements as $element ) :

			if ( isset( $header_args[ $element ] ) ) :

				if ( 'cover' == $element ) {
					$cover = youzify_users()->cover( $args['user_id'] );
					echo "<div class='youzify-header-cover'>";
					echo $cover;
					if ( bp_is_my_profile() ) echo '<span class="youzify-open-upload-cover youzify-open-bp-uploader" data-type="cover" data-youzify-tooltip="' . __( 'Change Cover', 'youzify' ) . '"><i class="fas fa-camera"></i></span>';
				} elseif ( 'content' == $element ) {
					echo "<div class='youzify-author-content'>";
				}

				echo '<div class="youzify-inner-content">';
				foreach ( $header_args[ $element ] as $element ) {
					do_action( 'youzify_before_author_box_' . $element, $args );
					$function = "get_box_$element";
					youzify_users()->$element( $args, $args['user_id'] );
					do_action( 'youzify_after_author_box_' . $element, $args );
				}
				echo '</div>';

				echo '</div>';

			endif;

		endforeach;
	}

	/**
	 * Cover Class
	 */
	function get_cover_class( $args ) {

		// Create Empty Array.
		$cover_class = array();

		// Get Box Layout.
		$cover_class[] = $args['layout'];

		// Add header cover overlay.
		if ( 'on' == $args['cover_overlay'] ) {
			$cover_class[] = 'youzify-header-overlay';
		}

		// Add header cover pattern.
		if ( 'on' == $args['cover_pattern'] ) {
			$cover_class[] = 'youzify-header-pattern';
		}

	 	// Return Class Name.
		return youzify_generate_class( $cover_class );
	}

}

/**
 * Get a unique instance of Author Box.
 */
function youzify_author_box() {
	return Youzify_Author::get_instance();
}

/**
 * Launch Youzify Author Box!
 */
youzify_author_box();