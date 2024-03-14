<?php
defined( 'ABSPATH' ) || exit;

/**
 * Notice Class
 * 
 * @since 3.0.0
 */
class WRGRGM_Notice {

    /**
	 * Current class instance
	 *
	 * @var WRGRGM_Notice
	 */
	private static $instance;

	/**
	 * Default settings for Notices
	 *
	 * @var array
	 */
	private static $defaults = array();

	/**
	 * Register the Notice class
	 *
	 * @return void
	 */
	public static function register() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}
    }
    
    /**
	 * Initiate file with default settings.
	 */
	private function __construct() {

		self::$defaults = array(
			'id'               => '',
            'type'             => 'info',
            'title'            => '',
			'message'          => '',
			'class'            => 'wrgrgm-active-notice',
			'dismissible'      => false
		);
	}

	/**
	 * Add notice template in array format
	 *
	 * @param array $notice Combination of notice format.
	 * @return void
	 */
	public static function push( array $notice ) {

		$notice = wp_parse_args( $notice, self::$defaults );

		$classes = array( 'wrgrgm-notice', 'notice' );

		$classes[] = $notice['class'];
		if ( isset( $notice['type'] ) ) {
			$classes[] = 'notice-' . $notice['type'];
        }
        
        if ( $notice['dismissible'] ) {
            $classes[] = 'is-dismissible';
        }

		$notice['classes'] = implode( ' ', $classes );

		self::$instance->load_template( $notice );
	}

	/**
	 * Notice Template
	 *
	 * @param array $notice Combination of notice format.
	 * @return void
	 */
	private function load_template( array $notice ) { ?>

        <div id="<?php echo esc_attr( $notice['id'] ); ?>" class="<?php echo esc_attr( $notice['classes'] ); ?>">
            <?php if ( ! empty($notice['title']) ): ?>
            <p><strong><?php esc_html_e( $notice['title'] ) ?></strong></p>
            <?php endif; ?>
			<p><?php _e( $notice['message'] ) ?></p>
		</div>
		<?php
	}
}

WRGRGM_Notice::register();