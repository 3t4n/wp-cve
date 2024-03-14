<?php
/**
 * Disable video auto start on mobile
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LearnDash_PowerPack_Disable_Video_Auto_Start_On_Mobile', false ) ) {
	/**
	 * Learndash_Powerpack_Disable_Video_Auto_Start_On_Mobile Class.
	 */
	class LearnDash_PowerPack_Disable_Video_Auto_Start_On_Mobile {
		/**
		 * Current class name
		 *
		 * @var string
		 */
		public $current_class = '';

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->current_class = get_class( $this );

			if ( learndash_powerpack_is_current_class_active( $this->current_class ) === 'active' ) {
				add_filter( 'learndash_lesson_video_data', [ $this, 'learndash_lesson_video_data_func' ], 10, 2 );
			}
		}

		/**
		 * Turn off auto-play for mobile devices.
		 *
		 * @param array $video_data The array with the video data.
		 * @param array $settings Settings.
		 *
		 * @return array The modified video data array.
		 */
		public function learndash_lesson_video_data_func( $video_data, $settings ) {
			// Turn off auto-play for mobile devices.
			if ( wp_is_mobile() ) {
				$video_data['videos_auto_start'] = false;
			}

			return $video_data;
		}

		/**
		 * Add class details.
		 *
		 * @return array The class details.
		 */
		public function learndash_powerpack_class_details() {
			$ld_type           = esc_html__( 'video', 'learndash-powerpack' );
			$class_title       = esc_html__( 'Video Progression', 'learndash-powerpack' );
			$class_description = esc_html__( 'Enable this option to disable video auto-start on mobile.', 'learndash-powerpack' );

			return [
				'title'       => $class_title,
				'ld_type'     => $ld_type,
				'description' => $class_description,
				'settings'    => false,
			];
		}
	}

	new LearnDash_PowerPack_Disable_Video_Auto_Start_On_Mobile();
}

