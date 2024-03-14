<?php
/**
 * Plugin view manager class
 *
 * @package SF_Plugin_View
 */

/**
 * SF_Plugin_View
 */
class SF_Plugin_View {

	/**
	 * Hold view filename
	 *
	 * @since 1.0
	 * @var  string
	 */
	public $filename;

	/**
	 * Associative array of data that will be available in the view
	 *
	 * @since 1.0
	 * @var  array
	 */
	public $data;

	/**
	 * Hold plugin abspath
	 *
	 * @since 1.0
	 * @var  string
	 */
	public $abspath;

	/**
	 * Hold plugin views dirname
	 *
	 * @since 1.0
	 * @var  string
	 */
	public $views_dirname = 'views';

	/**
	 * Hold debug enabled status
	 *
	 * @since 1.0
	 * @var bool
	 */
	public $debug = false;

	/**
	 * Returns or render view html
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $filename view.
	 * @param array  $data array of values.
	 */
	public function __construct( $filename = null, array $data = null ) {

		if ( null !== $filename ) {
			$this->set_file_name( $filename );
		}

		if ( null !== $data ) {
			$this->set_data( $data );
		}

	}


	/* =Class Setters ----------------------------------------------- */

	/**
	 * Set path to the directory that contains views directory inside
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $abspath is string value.
	 */
	public function set_abspath( $abspath = '' ) {
		$this->abspath = $abspath;
	}

	/**
	 * Set name for the views directory
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $dirname .
	 */
	public function set_views_dirname( $dirname = '' ) {
		$this->views_dirname = $dirname;
	}

	/**
	 * Set debug attribute
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param bool|int $debug .
	 */
	public function set_debug( $debug ) {
		$this->debug = (bool) $debug;
	}

	/**
	 * Set View file name without .php
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $filename .
	 */
	public function set_file_name( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * Set View file data
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param array $data .
	 */
	public function set_data( $data ) {
		$this->data = $data;
	}

	/**
	 * Try to render .
	 *
	 * @param bool $renderer .
	 */
	public function render( $renderer = false ) {
		if ( ! $renderer ) {
			// Check if file exists.
			if ( $this->file_exists() ) {
				// Store rendered view.
				ob_start();
				include $this->file;
				$this->render = ob_get_clean();
			} else {
				/* translators: %s: search term */
				$mess = sprintf( __( "Can't Load template %s", 'plugin_view' ), $this->filename );
				$this->add_error( 'no_file_found', $mess );
			}
		} else {
			// Check if file exists.
			if ( $this->file_exists() ) {
				// Store rendered view.
				include $this->file;
			} else {
				// Maybe add Errors to view.
				$output = '';
				/* translators: %s: search term */
				$mess = sprintf( __( "Can't Load template %s", 'plugin_view' ), $this->filename );
				$this->add_error( 'no_file_found', $mess );
				if ( $this->debug && isset( $this->error ) && $this->error->get_error_messages() ) {
					foreach ( $this->error->get_error_messages() as $error ) {
						$output .= '<p class="view-error">' . $error . '</p>';
					}
				}
				echo wp_kses_post( $output );
			}
		}
	}

	/**
	 * Try to get render view
	 *
	 * @since 2.7.4
	 * @access public
	 */
	public function get_render() {
		// Check if render attribute presents.
		if ( isset( $this->render ) ) {
			return $this->render;
		}
	}

	/**
	 * Check if requested template exists
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return  bool
	 */
	public function file_exists() {
		if ( file_exists( $this->abspath . $this->views_dirname . '/' . $this->filename . '.php' ) ) {

			// Store full file path.
			$this->file = $this->abspath . $this->views_dirname . '/' . $this->filename . '.php';
		}

		return isset( $this->file );
	}

	/**
	 * Returns view html
	 *
	 * @since 1.0
	 * @access private
	 *
	 * @return  string
	 */
	public function __toString() {
		$output = '';

		// Check if render attribute presents.
		if ( isset( $this->render ) ) {
			$output = $this->render;
		}

		// Maybe add Errors to view.
		if ( $this->debug && isset( $this->error ) && $this->error->get_error_messages() ) {
			foreach ( $this->error->get_error_messages() as $error ) {
				$output .= '<p class="view-error">' . $error . '</p>';
			}
		}

		return $output;
	}

	/**
	 * Add View Error
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param string $key error code .
	 * @param string $message error message .
	 * @param array  $data error data .
	 */
	public function add_error( $key = '', $message = '', array $data = null ) {
		if ( ! isset( $this->error ) ) {
			$this->error = new WP_Error();
		}

		$this->error->add( $key, $message, $data );
	}

}
