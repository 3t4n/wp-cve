<?php

/*
 * this class should be used to stores properties and methods shared by the
 * admin and public side of wordpress
 */

class daexthefu_Shared {

	protected static $instance = null;

	private $data = array();

	private function __construct() {

		$this->data['slug'] = 'daexthefu';
		$this->data['ver']  = '1.08';
		$this->data['dir']  = substr( plugin_dir_path( __FILE__ ), 0, - 7 );
		$this->data['url']  = substr( plugin_dir_url( __FILE__ ), 0, - 7 );

		//Here are stored the plugin option with the related default values
		$this->data['options'] = [

			//Database Version -----------------------------------------------------------------------------------------
			$this->get( 'slug' ) . "_database_version"                              => "0",

			//Content --------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_title'                                         => "Was this helpful?",
			$this->get( 'slug' ) . '_layout'                                        => "0",
			$this->get( 'slug' ) . '_alignment'                                     => "0",
			$this->get( 'slug' ) . '_button_type'                                   => "0",
			$this->get( 'slug' ) . '_positive_feedback_icon'                        => "happy-face",
			$this->get( 'slug' ) . '_positive_feedback_text'                        => "Yes",
			$this->get( 'slug' ) . '_negative_feedback_icon'                        => "sad-face",
			$this->get( 'slug' ) . '_negative_feedback_text'                        => "No",
			$this->get( 'slug' ) . '_comment_form'                                  => "0",
			$this->get( 'slug' ) . '_comment_form_textarea_label_positive_feedback' => "We're glad that you liked the post! Let us know why (optional)",
			$this->get( 'slug' ) . '_comment_form_textarea_label_negative_feedback' => "We're sorry to hear that. Please let us know how we can improve. (optional)",
			$this->get( 'slug' ) . '_comment_form_textarea_placeholder'             => "Type your message",
			$this->get( 'slug' ) . '_comment_form_button_submit_text'               => "Submit",
			$this->get( 'slug' ) . '_comment_form_button_cancel_text'               => "Cancel",
			$this->get( 'slug' ) . '_successful_submission_text'                    => "Thanks for your feedback!",
			$this->get( 'slug' ) . '_background'                                    => "0",
			$this->get( 'slug' ) . '_border'                                        => "1",

			//Fonts ----------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_title_font_family'                             => "'Inter', sans-serif",
			$this->get( 'slug' ) . '_title_font_size'                               => "24",
			$this->get( 'slug' ) . '_title_font_style'                              => "normal",
			$this->get( 'slug' ) . '_title_font_weight'                             => "600",
			$this->get( 'slug' ) . '_title_line_height'                             => "48",
			$this->get( 'slug' ) . '_rating_button_font_family'                     => "'Inter', sans-serif",
			$this->get( 'slug' ) . '_rating_button_font_size'                       => "19",
			$this->get( 'slug' ) . '_rating_button_font_style'                      => "normal",
			$this->get( 'slug' ) . '_rating_button_font_weight'                     => "400",
			$this->get( 'slug' ) . '_rating_button_line_height'                     => "48",
			$this->get( 'slug' ) . '_base_font_family'                              => "'Inter', sans-serif",
			$this->get( 'slug' ) . '_base_font_size'                                => "13",
			$this->get( 'slug' ) . '_base_font_style'                               => "normal",
			$this->get( 'slug' ) . '_base_font_weight'                              => "400",
			$this->get( 'slug' ) . '_base_line_height'                              => "24",
			$this->get( 'slug' ) . '_comment_textarea_font_family'                  => "'Inter', sans-serif",
			$this->get( 'slug' ) . '_comment_textarea_font_size'                    => "13",
			$this->get( 'slug' ) . '_comment_textarea_font_style'                   => "normal",
			$this->get( 'slug' ) . '_comment_textarea_font_weight'                  => "400",
			$this->get( 'slug' ) . '_comment_textarea_line_height'                  => "24",
			$this->get( 'slug' ) . '_button_font_family'                            => "'Inter', sans-serif",
			$this->get( 'slug' ) . '_button_font_size'                              => "14",
			$this->get( 'slug' ) . '_button_font_style'                             => "normal",
			$this->get( 'slug' ) . '_button_font_weight'                            => "600",
			$this->get( 'slug' ) . '_button_line_height'                            => "48",

			//Colors ----------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_title_font_color'                              => "#424447",
			$this->get( 'slug' ) . '_rating_button_font_color'                      => "#1E1E1F",
			$this->get( 'slug' ) . '_rating_button_background_color'                => "#E7E7E8",
			$this->get( 'slug' ) . '_button_icon_primary_color'                     => "#c8c8c8",
			$this->get( 'slug' ) . '_button_icon_secondary_color'                   => "#666666",
			$this->get( 'slug' ) . '_button_icon_primary_color_positive_selected'   => "#7db340",
			$this->get( 'slug' ) . '_button_icon_secondary_color_positive_selected' => "#397038",
			$this->get( 'slug' ) . '_button_icon_primary_color_negative_selected'   => "#e89795",
			$this->get( 'slug' ) . '_button_icon_secondary_color_negative_selected' => "#94322c",
			$this->get( 'slug' ) . '_button_icons_border_color'                     => "#dddddd",
			$this->get( 'slug' ) . '_label_font_color'                              => "#2E2E2F",
			$this->get( 'slug' ) . '_character_counter_font_color'                  => "#898A8C",
			$this->get( 'slug' ) . '_comment_textarea_font_color'                   => "#1E1E1F",
			$this->get( 'slug' ) . '_comment_textarea_background_color'             => "#ffffff",
			$this->get( 'slug' ) . '_comment_textarea_border_color'                 => "#E7E7E8",
			$this->get( 'slug' ) . '_comment_textarea_border_color_selected'        => "#063F85",
			$this->get( 'slug' ) . '_primary_button_background_color'               => "#2B2D30",
			$this->get( 'slug' ) . '_primary_button_border_color'                   => "#2B2D30",
			$this->get( 'slug' ) . '_primary_button_font_color'                     => "#ffffff",
			$this->get( 'slug' ) . '_secondary_button_background_color'             => "#ffffff",
			$this->get( 'slug' ) . '_secondary_button_border_color'                 => "#B8B9BA",
			$this->get( 'slug' ) . '_secondary_button_font_color'                   => "#063F85",
			$this->get( 'slug' ) . '_successful_submission_font_color'              => "#2E2E2F",
			$this->get( 'slug' ) . '_background_color'                              => "#f5f6f7",
			$this->get( 'slug' ) . '_border_color'                                  => "#E7E7E8",

			//Spacing --------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_container_horizontal_padding'                  => "0",
			$this->get( 'slug' ) . '_container_vertical_padding'                    => "32",
			$this->get( 'slug' ) . '_container_horizontal_margin'                   => "0",
			$this->get( 'slug' ) . '_container_vertical_margin'                     => "64",
			$this->get( 'slug' ) . '_border_radius'                                 => "4",

			//Analysis -------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_set_max_execution_time'                        => "1",
			$this->get( 'slug' ) . '_max_execution_time_value'                      => "300",
			$this->get( 'slug' ) . '_set_memory_limit'                              => "0",
			$this->get( 'slug' ) . '_memory_limit_value'                            => "512",
			$this->get( 'slug' ) . '_limit_posts_analysis'                          => '10000',
			$this->get( 'slug' ) . '_analysis_post_types'                           => [ 'post', 'page' ],

			//Capability -----------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_statistics_menu_capability'                    => "manage_options",
			$this->get( 'slug' ) . '_maintenance_menu_capability'                   => "manage_options",

			//Advanced -------------------------------------------------------------------------------------------------
			$this->get( 'slug' ) . '_test_mode'                                     => "0",
			$this->get( 'slug' ) . '_assets_mode'                                   => "1",
			$this->get( 'slug' ) . '_post_types'                                    => [ 'post', 'page' ],
			$this->get( 'slug' ) . '_pagination_items'                              => '20',
			$this->get( 'slug' ) . '_google_font_url'                               => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap',
			$this->get( 'slug' ) . '_textarea_characters'                           => '400',
			$this->get( 'slug' ) . '_unique_submission'                             => '0',
			$this->get( 'slug' ) . '_cookie_expiration'                             => '0',
			$this->get( 'slug' ) . '_character_counter'                             => '1',

		];

	}

	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	//retrieve data
	public function get( $index ) {
		return $this->data[ $index ];
	}

	/*
	 * Get the number of records available in the "_archive" db table
	 *
	 * @return int The number of records in the "_archive" db table
	 */
	public function number_of_records_in_archive() {

		global $wpdb;
		$table_name  = $wpdb->prefix . $this->get( 'slug' ) . "_archive";
		$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

		return $total_items;

	}

	/**
	 * Verifies the cookies and the IP address of the user to determine if this the form has been already submitted by
	 * the same user.
	 *
	 * If the form has never been submitted by the user True is returned. Otherwise False is returned.
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function is_unique_submission( $post_id ) {

		$post_id = intval( $post_id, 10 );

		/**
		 * Step 1
		 *
		 * Check if the "daexthefu-data" cookie includes the ID of the current post. In this case returns false.
		 */
		if ( intval( get_option( $this->get( 'slug' ) . '_unique_submission' ), 10 ) === 1 or
		     intval( get_option( $this->get( 'slug' ) . '_unique_submission' ), 10 ) === 3 ) {

			if ( isset( $_COOKIE['daexthefu-data'] ) ) {

				$data_a = json_decode( stripslashes( $_COOKIE['daexthefu-data'] ) );

				if ( is_array( $data_a ) ) {

					foreach ( $data_a as $value ) {

						if ( $value === $post_id ) {
							return false;
						}

					}

				}

			}

		}

		/**
		 * Step 2
		 *
		 * Check if a submission for the same post with the same user ip already exist. If it exists returns false.
		 */
		if ( intval( get_option( $this->get( 'slug' ) . '_unique_submission' ), 10 ) === 2 or
		     intval( get_option( $this->get( 'slug' ) . '_unique_submission' ), 10 ) === 3 ) {

			global $wpdb;
			$table_name     = $wpdb->prefix . $this->get( 'slug' ) . "_feedback";
			$safe_sql       = $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE post_id = %d AND ip_address = %s",
				$post_id,
				$this->get_ip_address() );
			$num_of_records = intval( $wpdb->get_var( $safe_sql ), 10 );

			if ( $num_of_records > 0 ) {
				return false;
			}

		}

		return true;

	}

	/**
	 * Returns an array that includes the SVG of the icon an array with the allowed HTML used later by wp_kses().
	 *
	 * @param $name
	 *
	 * @return array
	 */
	public function get_icon( $name ) {

		//Store the eye visible svg in a string
		$positive_feedback_icon_svg = $this->get_svg_content( $name );

		//Configure the allowed tags and attributes of the svg based on the svg
		switch ( $name ) {

			case 'happy-face':

				$allowed_html = [
					'svg'    => [],
					'defs'   => [],
					'style'  => [],
					'path'   => [
						'class' => [],
						'd'     => [],
					],
					'g'      => [
						'id' => []
					],
					'circle' => [
						'class' => [],
						'cx'    => [],
						'cy'    => [],
						'r'     => []
					]
				];

				break;

			case 'sad-face':

				$allowed_html = [
					'svg'    => [],
					'defs'   => [],
					'style'  => [],
					'path'   => [
						'class' => [],
						'd'     => [],
					],
					'g'      => [
						'id' => []
					],
					'circle' => [
						'class' => [],
						'cx'    => [],
						'cy'    => [],
						'r'     => []
					]
				];

				break;

			case 'thumb-up':

				$allowed_html = [
					'svg'   => [],
					'defs'  => [],
					'style' => [],
					'path'  => [
						'class' => [],
						'd'     => [],
					],
					'g'     => [
						'id' => []
					],
					'rect'  => [
						'x'      => [],
						'y'      => [],
						'class'  => [],
						'width'  => [],
						'height' => [],
						'rx'     => [],
						'ry'     => []
					]
				];

				break;

			case 'thumb-down':

				$allowed_html = [
					'svg'   => [],
					'defs'  => [],
					'style' => [],
					'path'  => [
						'class' => [],
						'd'     => [],
					],
					'g'     => [
						'id' => []
					],
					'rect'  => [
						'x'      => [],
						'y'      => [],
						'class'  => [],
						'width'  => [],
						'height' => [],
						'rx'     => [],
						'ry'     => []
					]
				];

				break;

		}

		return [
			'svg'          => $positive_feedback_icon_svg,
			'allowed_html' => $allowed_html
		];

	}

	/**
	 * Echo the HTML of a button based on the provided data.
	 *
	 * @param $form The data of the form.
	 * @param $is_positive_feedback If true it's a positive feedback button, otherwise is a negative feedback button.
	 */
	public function generated_button_html( $is_positive_feedback ) {

		$button_type = intval( get_option( $this->get( 'slug' ) . '_button_type' ), 10 );

		if ( $is_positive_feedback ) {

			//Get the positive feedback icon
			$positive_feedback_icon = $this->get_icon( get_option( $this->get( 'slug' ) . '_positive_feedback_icon' ) );

		} else {

			//Get the negative feedback icon
			$negative_feedback_icon = $this->get_icon( get_option( $this->get( 'slug' ) . '_negative_feedback_icon' ) );

		}

		if ( $button_type === 0 && $is_positive_feedback === true ) {

			//Icon - Positive Feedback

			?>

            <div class="daexthefu-yes daexthefu-button daexthefu-button-type-icon" data-value="1">
				<?php echo wp_kses( $positive_feedback_icon['svg'], $positive_feedback_icon['allowed_html'] ); ?>
            </div>

			<?php

		} elseif ( $button_type === 0 && $is_positive_feedback === false ) {

			//Icon - Negative Feedback

			?>

            <div class="daexthefu-no daexthefu-button daexthefu-button-type-icon" data-value="0">
				<?php echo wp_kses( $negative_feedback_icon['svg'], $negative_feedback_icon['allowed_html'] ); ?>
            </div>

			<?php

		} elseif ( $button_type === 1 && $is_positive_feedback === true ) {

			//Icon & Text - Positive Feedback

			?>

            <div class="daexthefu-yes daexthefu-button daexthefu-button-type-icon-and-text" data-value="1">
                <div class="daexthefu-button-icon"><?php echo wp_kses( $positive_feedback_icon['svg'],
						$positive_feedback_icon['allowed_html'] ); ?></div>
                <div class="daexthefu-button-text"><?php echo esc_html( get_option( $this->get( 'slug' ) . '_positive_feedback_text' ) ); ?></div>
            </div>

			<?php

		} elseif ( $button_type === 1 && $is_positive_feedback === false ) {

			//Icon & Text - Positive Feedback

			?>

            <div class="daexthefu-no daexthefu-button daexthefu-button-type-icon-and-text" data-value="0">
                <div class="daexthefu-button-icon"><?php echo wp_kses( $negative_feedback_icon['svg'],
						$negative_feedback_icon['allowed_html'] ); ?></div>
                <div class="daexthefu-button-text"><?php echo esc_html( get_option( $this->get( 'slug' ) . '_negative_feedback_text' ) ); ?></div>
            </div>

			<?php

		} elseif ( $button_type === 2 && $is_positive_feedback === true ) {

			//Text - Positive Feedback

			?>

            <div class="daexthefu-yes daexthefu-button daexthefu-button-type-text" data-value="1">
                <div class="daexthefu-button-text"><?php echo esc_html( get_option( $this->get( 'slug' ) . '_positive_feedback_text' ) ); ?></div>
            </div>

			<?php

		} elseif ( $button_type === 2 && $is_positive_feedback === false ) {

			//Text - Negative Feedback

			?>

            <div class="daexthefu-no daexthefu-button daexthefu-button-type-text" data-value="0">
                <div class="daexthefu-button-text"><?php echo esc_html( get_option( $this->get( 'slug' ) . '_negative_feedback_text' ) ); ?></div>
            </div>

			<?php

		}

	}

	/**
	 * Echo an icon with color code based on the provided percentage of positive feedback.
	 *
	 * The color codes are:
	 *
	 * 81-100 / Dark Green / c5
	 * 61-80 / Light Green / c4
	 * 41-60 / Orange / c3
	 * 21-40 / Light Red / c2
	 * 0-20 / Dark Red / c1
	 *
	 * No data / Grey / c0
	 *
	 * @param $positive_percentage
	 */
	public function generate_pfr_icon( $positive_percentage ) {

		if ( $positive_percentage === '-1' ) {
			$color_code_class = 'daexthefu-circle-c0';
		}
		{
			$positive_percentage = intval( $positive_percentage, 10 );
		}

		if ( $positive_percentage >= 80 and $positive_percentage <= 100 ) {
			$color_code_class = 'daexthefu-circle-c1';
		} elseif ( $positive_percentage >= 60 and $positive_percentage <= 79 ) {
			$color_code_class = 'daexthefu-circle-c2';
		} elseif ( $positive_percentage >= 40 and $positive_percentage <= 59 ) {
			$color_code_class = 'daexthefu-circle-c3';
		} elseif ( $positive_percentage >= 20 and $positive_percentage <= 39 ) {
			$color_code_class = 'daexthefu-circle-c4';
		} elseif ( $positive_percentage >= 0 and $positive_percentage <= 19 ) {
			$color_code_class = 'daexthefu-circle-c5';
		}

		echo '<div class="daexthefu-circle ' . esc_attr( $color_code_class ) . '"></div>';

	}

	/*
	 * Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options.
	 */
	public function set_met_and_ml() {

		/*
		 * Set the custom "Max Execution Time Value" defined in the options if
		 * the 'Set Max Execution Time' option is set to "Yes"
		 */
		if ( intval( get_option( $this->get( 'slug' ) . '_set_max_execution_time' ), 10 ) === 1 ) {
			ini_set( 'max_execution_time', intval( get_option( "daexthefu_max_execution_time_value" ), 10 ) );
		}

		/*
		 * Set the custom "Memory Limit Value" ( in megabytes ) defined in the
		 * options if the 'Set Memory Limit' option is set to "Yes"
		 */
		if ( intval( get_option( $this->get( 'slug' ) . '_set_memory_limit' ), 10 ) === 1 ) {
			ini_set( 'memory_limit', intval( get_option( "daexthefu_memory_limit_value" ), 10 ) . 'M' );
		}

	}

	/**
	 * Reset the plugin options.
	 *
	 * Set the initial value to all the plugin options.
	 */
	public function reset_plugin_options() {

		$options = $this->get( 'options' );
		foreach ( $options as $option_name => $default_option_value ) {
			update_option( $option_name, $default_option_value );
		}

	}

	/**
	 * Echo all the dismissible notices based on the values of the $notices array.
	 *
	 * @param $notices
	 */
	public function dismissible_notice( $notices ) {

		foreach ( $notices as $notice ) {
			echo '<div class="' . esc_attr( $notice['class'] ) . ' settings-error notice is-dismissible below-h2"><p>' . esc_html( $notice['message'] ) . '</p></div>';
		}

	}

	/**
	 * Get the number of seconds associated with the provided identifier of a time period defined with the "Cookie
	 * Expiration" option.
	 *
	 * @param $period
	 *
	 * @return float|int
	 */
	public function get_cookie_expiration_seconds( $period ) {

		switch ( intval( $period, 10 ) ) {

			//Unlimited
			case 0:
				$expiration = 3153600000;
				break;

			//One Hour
			case 1:
				$expiration = 3600;
				break;

			//One Day
			case 2:
				$expiration = 3600 * 24;
				break;

			//One Week
			case 3:
				$expiration = 3600 * 24 * 7;
				break;

			//One Month
			case 4:
				$expiration = 3600 * 24 * 30;
				break;

			//Three Months
			case 5:
				$expiration = 3600 * 2490;
				break;

			//Six Months
			case 6:
				$expiration = 3600 * 24 * 180;
				break;

			//One Year
			case 7:
				$expiration = 3600 * 24 * 365;
				break;

		}

		return $expiration;

	}

	/**
	 * Get the IP address of the user. If the retrieved IP address is not valid an empty string is returned.
	 *
	 * @return string
	 */
	public function get_ip_address() {

		$ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );

		if ( rest_is_ip_address( $ip_address ) ) {
			return $ip_address;
		} else {
			return '';
		}

	}

	/**
	 * Get the SVG of the icon with the provided name.
	 *
	 * @param $name
	 *
	 * @return string
	 */
	private function get_svg_content( $name ) {

		$content = '';

		switch ( $name ) {

			case 'happy-face':

				$content = '<?xml version="1.0" encoding="UTF-8"?>
                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                            <defs>
                                <style>.happy-face-cls-1{fill:#c9c9c9;}.happy-face-cls-2{fill:#e1e1e1;}.happy-face-cls-3{fill:#676767;}</style>
                            </defs>
                            <g id="happy_face">
                                <circle class="happy-face-cls-1 daexthefu-icon-primary-color" cx="24" cy="24" r="17"/>
                                <path class="happy-face-cls-2 daexthefu-icon-circle"
                                      d="m24,3c11.58,0,21,9.42,21,21s-9.42,21-21,21S3,35.58,3,24,12.42,3,24,3m0-1C11.85,2,2,11.85,2,24s9.85,22,22,22,22-9.85,22-22S36.15,2,24,2h0Z"/>
                                <circle class="happy-face-cls-3 daexthefu-icon-secondary-color" cx="18" cy="22" r="2"/>
                                <circle class="happy-face-cls-3 daexthefu-icon-secondary-color" cx="30" cy="22" r="2"/>
                                <path class="happy-face-cls-3 daexthefu-icon-secondary-color"
                                      d="m16.79,29c-1.19,0-1.89,1.31-1.25,2.32,1.77,2.81,4.9,4.68,8.47,4.68s6.7-1.87,8.47-4.68c.63-1.01-.06-2.32-1.25-2.32-3.67,0-10.76,0-14.43,0Z"/>
                            </g>
                        </svg>';

				break;

			case 'sad-face':

				$content = '<?xml version="1.0" encoding="UTF-8"?>
                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                            <defs>
                                <style>
                                    .sad-face-cls-1{fill:#c9c9c9;}.sad-face-cls-2{fill:#676767;}.sad-face-cls-3{fill:#e1e1e1;}.sad-face-cls-4{fill:#676767;}
                                </style>
                            </defs>
                            <g id="sad_face">
                                <circle class="sad-face-cls-1 daexthefu-icon-primary-color" cx="24" cy="24" r="17"/>
                                <path class="sad-face-cls-3 daexthefu-icon-circle"
                                      d="m24,3c11.58,0,21,9.42,21,21s-9.42,21-21,21S3,35.58,3,24,12.42,3,24,3m0-1C11.85,2,2,11.85,2,24s9.85,22,22,22,22-9.85,22-22S36.15,2,24,2h0Z"/>
                                <circle class="sad-face-cls-4 daexthefu-icon-secondary-color" cx="18" cy="22" r="2"/>
                                <circle class="sad-face-cls-4 daexthefu-icon-secondary-color" cx="30" cy="22" r="2"/>
                                <path class="sad-face-cls-2 daexthefu-icon-secondary-color" d="M16.9,34.5c-0.4,0-0.8-0.1-1.1-0.4c-0.6-0.6-0.6-1.5,0-2.1c2.2-2.2,5.1-3.4,8.1-3.4c3.1,0,6,1.2,8.1,3.4
                                c0.6,0.6,0.6,1.5,0,2.1s-1.5,0.6-2.1,0c-1.6-1.6-3.7-2.5-6-2.5s-4.4,0.9-6,2.5C17.7,34.4,17.3,34.5,16.9,34.5z"/>
                            </g>
                        </svg>';

				break;

			case 'thumb-up':

				$content = '<?xml version="1.0" encoding="UTF-8"?>
                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                            <defs>
                                <style>.thumb-up-cls-1{fill:#c9c9c9;}.thumb-up-cls-2{fill:#e1e1e1;}.thumb-up-cls-3{fill:#676767;}</style>
                            </defs>
                            <g id="thumb_up">
                                <path class="thumb-up-cls-2 daexthefu-icon-circle"
                                      d="m24,3c11.58,0,21,9.42,21,21s-9.42,21-21,21S3,35.58,3,24,12.42,3,24,3m0-1C11.85,2,2,11.85,2,24s9.85,22,22,22,22-9.85,22-22S36.15,2,24,2h0Z"/>
                                <g>
                                    <rect class="thumb-up-cls-3 daexthefu-icon-secondary-color" x="10" y="20" width="6" height="15" rx="1.5" ry="1.5"/>
                                    <path class="thumb-up-cls-1 daexthefu-icon-primary-color"
                                          d="m30.57,9.06l-.49-.1c-.81-.17-1.61.35-1.78,1.16l-5.3,11.74c-.17.81,3.16,1.61,3.97,1.78l1.96.41c.81.17,1.61-.35,1.78-1.16l2.18-10.27c.34-1.61-.7-3.21-2.31-3.56Z"/>
                                    <path class="thumb-up-cls-1 daexthefu-icon-primary-color"
                                          d="m38.17,20h-18.67c-.83,0-1.5.67-1.5,1.5v12c0,.83.67,1.5,1.5,1.5h16.27c.71,0,1.33-.5,1.47-1.21l2.4-12c.19-.93-.53-1.8-1.47-1.8Z"/>
                                </g>
                            </g>
                        </svg>';

				break;

			case 'thumb-down':

				$content = '<?xml version="1.0" encoding="UTF-8"?>
                    <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                        <defs>
                            <style>.thumb-down-cls-1{fill:#c9c9c9;}.thumb-down-cls-2{fill:#e1e1e1;}.thumb-down-cls-3{fill:#676767;}</style>
                        </defs>
                        <g id="thumb_down">
                            <path class="thumb-down-cls-2 daexthefu-icon-circle"
                                  d="m24,3c11.58,0,21,9.42,21,21s-9.42,21-21,21S3,35.58,3,24,12.42,3,24,3m0-1C11.85,2,2,11.85,2,24s9.85,22,22,22,22-9.85,22-22S36.15,2,24,2h0Z"/>
                            <g>
                                <rect class="thumb-down-cls-3 daexthefu-icon-secondary-color" x="10" y="13" width="6" height="15" rx="1.5" ry="1.5"/>
                                <path class="thumb-down-cls-1 daexthefu-icon-primary-color"
                                      d="m30.57,38.94l-.49.1c-.81.17-1.61-.35-1.78-1.16l-5.3-11.74c-.17-.81,3.16-1.61,3.97-1.78l1.96-.41c.81-.17,1.61.35,1.78,1.16l2.18,10.27c.34,1.61-.7,3.21-2.31,3.56Z"/>
                                <path class="thumb-down-cls-1 daexthefu-icon-primary-color"
                                      d="m38.17,28h-18.67c-.83,0-1.5-.67-1.5-1.5v-12c0-.83.67-1.5,1.5-1.5h16.27c.71,0,1.33.5,1.47,1.21l2.4,12c.19.93-.53,1.8-1.47,1.8Z"/>
                            </g>
                        </g>
                    </svg>';

				break;

		}

		return $content;

	}

}