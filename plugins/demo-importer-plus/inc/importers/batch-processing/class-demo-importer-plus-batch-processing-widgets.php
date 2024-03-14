<?php
/**
 * Batch Processing
 *
 * @package Demo Importer Plus
 */

if ( ! class_exists( 'Demo_Importer_Plus_Batch_Processing_Widgets' ) ) :

	/**
	 * Demo_Importer_Plus_Batch_Processing_Widgets
	 */
	class Demo_Importer_Plus_Batch_Processing_Widgets {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
		}

		/**
		 * Import
		 *
		 * @return void
		 */
		public function import() {
			if ( defined( 'WP_CLI' ) ) {
				WP_CLI::line( 'Importing Widgets Data' );
			}

			$this->widget_media_image();
		}

		/**
		 * Widget Media Image
		 *
		 * @return void
		 */
		public function widget_media_image() {

			$data = get_option( 'widget_media_image', null );

			Demo_Importer_Plus_Sites_Importer_Log::add( '---- Processing Images from Widgets -----' );
			foreach ( $data as $key => $value ) {

				if (
					isset( $value['url'] ) &&
					isset( $value['attachment_id'] )
				) {

					$image = array(
						'url' => $value['url'],
						'id'  => $value['attachment_id'],
					);

					$downloaded_image = Demo_Importer_Plus_Sites_Image_Importer::get_instance()->import( $image );

					$data[ $key ]['url']           = $downloaded_image['url'];
					$data[ $key ]['attachment_id'] = $downloaded_image['id'];

					if ( defined( 'WP_CLI' ) ) {
						WP_CLI::line( 'Importing Widgets Image: ' . $value['url'] . ' | New Image ' . $downloaded_image['url'] );
					}
				}
			}

			update_option( 'widget_media_image', $data );
		}
	}

	/**
	 * Starting this by calling 'get_instance()' method
	 */
	Demo_Importer_Plus_Batch_Processing_Widgets::get_instance();

endif;
