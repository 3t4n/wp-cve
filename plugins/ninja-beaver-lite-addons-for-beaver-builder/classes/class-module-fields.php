<?php
if ( ! class_exists( 'NJBA_Rgba_Colors' ) ) {
	class NJBA_Rgba_Colors {
		/**
		 * Hex to Rgba For color
		 *
		 * @param $hex
		 * @param $opacity
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function njba_hex2rgba( $hex, $opacity ) {
			$hex = str_replace( '#', '', $hex );
			if ( strlen( $hex ) == 3 ) {
				$r = hexdec( $hex[0] . $hex[0] );
				$g = hexdec( $hex[1] . $hex[1] );
				$b = hexdec( $hex[2] . $hex[2] );
			} else {
				$r = hexdec( substr( $hex, 0, 2 ) );
				$g = hexdec( substr( $hex, 2, 2 ) );
				$b = hexdec( substr( $hex, 4, 2 ) );
			}
			$rgba = array( $r, $g, $b, $opacity );

			return 'rgba(' . implode( ', ', $rgba ) . ')';
		}

		/**
		 * Change Color To hex
		 *
		 * @param string $code
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function njba_parse_color_to_hex( $code = '' ) {
			$color = '';
			$hex   = '';
			if ( $code != '' ) {
				if ( strpos( $code, 'rgba' ) !== false ) {
					$code  = ltrim( $code, 'rgba(' );
					$code  = rtrim( $code, ')' );
					$rgb   = explode( ',', $code );
					$hex   .= str_pad( dechex( $rgb[0] ), 2, '0', STR_PAD_LEFT );
					$hex   .= str_pad( dechex( $rgb[1] ), 2, '0', STR_PAD_LEFT );
					$hex   .= str_pad( dechex( $rgb[2] ), 2, '0', STR_PAD_LEFT );
					$color = $hex;
				} else {
					$color = ltrim( $code, '#' );
				}
			}

			return $color;
		}
	}
}

if ( ! class_exists( 'NJBA_Custom_Option_Field_Type' ) ) {
	class NJBA_Custom_Option_Field_Type {
		/**
		 * njba_Custom_Option_Type constructor.
		 */
		public function __construct() {
			add_action( 'fl_builder_control_njba-radio', [ $this, 'njbaRadioField' ], 1, 4 );
			add_action( 'fl_builder_control_njba-multinumber', [ $this, 'njbaMultiNumberField' ], 1, 3 );
		}

		/**
		 * njba modules option type for radio fields
		 *
		 * @param $name
		 * @param $value
		 * @param $field
		 *
		 * @since 1.0.0
		 */
		public function njbaRadioField( $name, $value, $field ) {
			if ( ! isset( $field['options'] ) ) {
				return;
			}
			$options = ( isset( $field['options'] ) && is_array( $field['options'] ) ) ? $field['options'] : array();
			$toggle  = ( isset( $field['toggle'] ) && is_array( $field['toggle'] ) ) ? $field['toggle'] : array();
			foreach ( $options as $opt_key => $opt_value ) {
				?>
                <div class="njba-field-wrap">
                    <label class="njba-label njba-option-<?php echo $opt_key; ?> <?php echo $name; ?> <?php echo ( $opt_key == $value || ( '' == $value && $opt_key == $default ) ) ? 'selected' : ''; ?>">
                        <input type="radio" class="njba-field-radio" name="<?php echo $name; ?>"
                               value="<?php echo $opt_key; ?>" <?php echo ( $opt_key == $value || ( '' == $value && $opt_key == $default ) ) ? 'checked="checked"' : ''; ?> />
                        <span><?php echo $opt_value; ?></span>
                    </label>
                </div>
				<?php
			}
			?>
            <input type="hidden" class="njba-field-radio-data" value="<?php echo ( $value && '' !== $value ) ? $value : $default; ?>"
                   data-name="<?php echo $name; ?>" <?php echo count( $toggle ) ? "data-toggle='" . json_encode( $toggle ) . "'" : ''; ?> />
            <script> NJBAFields._initRadioFields();
                jQuery('.fl-builder-settings-fields .njba-field-radio  ').click(function () {
                    jQuery('.fl-builder-settings:visible').find('.fl-builder-settings-fields input[type="radio"]').parent().removeClass('selected');
                    jQuery('.fl-builder-settings:visible').find('.fl-builder-settings-fields input[type="radio"]:checked').parent().addClass('selected');
                });
            </script>
			<?php
		}

		/**
		 * njba modules option type for multiple Input Fields.
		 *
		 * @param $name
		 * @param $value
		 * @param $field
		 */
		public function njbaMultiNumberField( $name, $value, $field ) {
			if ( ! isset( $field['options'] ) || ! is_array( $field['options'] ) ) {
				return;
			}
			$options = $field['options'];
			//$class   = ( isset( $field['class'] ) ) ? $field['class'] : '';
			//$default = isset( $field['default'] ) ? $field['default'] : array();
			$value = (array) $value;
			?>
            <div class="njba-multinumber-wrap">
				<?php
				foreach ( $options as $key => $opt ) {
					$placeholder = isset( $opt['placeholder'] ) ? $opt['placeholder'] : '';
					//$size        = isset( $opt['size'] ) ? 'size="' . $opt['size'] . '"' : '';
					//$maxlength   = isset( $opt['maxlength'] ) ? 'maxlength="' . $opt['maxlength'] . '"' : '';
					$icon    = isset( $opt['icon'] ) ? 'fa ' . $opt['icon'] : '';
					$preview = isset( $opt['preview'] ) ? $opt['preview'] : array();
					$tooltip = isset( $opt['tooltip'] ) ? $opt['tooltip'] : '';
					//$description = isset( $opt['description'] ) ? 'description="' . $opt['description'] . '"' : '';
					?>
                    <span class="njba-multinumber <?php echo $icon; ?> njba-field" <?php echo count( $preview ) ? "data-preview='" . json_encode( $preview ) . "'" : ''; ?> title="<?php echo $tooltip; ?>">
                        <input type="number" name="<?php echo $name . '[][' . $key . ']'; ?>" value="<?php if ( $value[ $key ] >= '0' ) {
	                        echo $value[ $key ];
                        } ?>" class="njba-field-multinumber" placeholder="<?php echo $placeholder; ?>"/>
                    </span>
					<?php
				}
				?>
            </div>
			<?php
		}
	}

	$njba_Custom_Option_Field_Type = new NJBA_Custom_Option_Field_Type();
}

if ( ! class_exists( 'NJBA_Countdown' ) ) {
	class NJBA_Countdown {
		/**
		 * NJBA_Countdown constructor.
		 */
		public function __construct() {
			add_action( 'fl_builder_control_njba-normal-date', [ $this, 'njbaNormalDateField' ], 1, 4 );
			add_action( 'fl_builder_control_njba-evergreen-date', [ $this, 'njbaEvergreenDateField' ], 1, 4 );
		}

		/**
		 * njba modules option type for Normal Date Countdown.
		 *
		 * @param $name
		 * @param $value
		 * @param $field
		 * @param $settings
		 *
		 * @since 1.0.0
		 */
		public function njbaNormalDateField( $name, $value, $field, $settings ) {
			//$custom_class = isset( $field['class'] ) ? $field['class'] : '';
			$preview = isset( $field['preview'] ) ? json_encode( $field['preview'] ) : json_encode( array( 'type' => 'refresh' ) );
			echo '<div class="njba-date-wrap fl-field" data-type="select" data-preview=\'' . $preview . '\'><div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_days" ><option value="0">' . __( 'Date',
					'bb-njba' ) . '</option>';
			for ( $i = 1; $i <= 31; $i ++ ) {
				$selected = '';
				if ( isset( $settings->fixed_date_days ) ) {
					if ( $i == $settings->fixed_date_days ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
				} else if ( $i == 29 ) {
					$selected = 'selected';
				}
				if ( $i <= 9 ) {
					echo '<option value="' . $i . '" ' . $selected . '>0' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
				}
			}
			echo '</select></br><label>' . __( 'Date', 'bb-njba' ) . '</label></div>';
			echo '<div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_month" >';
			echo '<option value="0">' . __( 'Month', 'bb-njba' ) . '</option>';
			echo '<option value="01" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '01' ) ? 'selected' : '' ) . ' >Jan</option>';
			echo '<option value="02" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '02' ) ? 'selected' : '' ) . ' >Feb</option>';
			echo '<option value="03" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '03' ) ? 'selected' : '' ) . ' >Mar</option>';
			echo '<option value="04" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '04' ) ? 'selected' : '' ) . ' >Apr</option>';
			echo '<option value="05" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '05' ) ? 'selected' : '' ) . ' >May</option>';
			echo '<option value="06" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '06' ) ? 'selected' : '' ) . ' >Jun</option>';
			echo '<option value="07" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '07' ) ? 'selected' : '' ) . ' >Jul</option>';
			echo '<option value="08" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '08' ) ? 'selected' : '' ) . ' >Aug</option>';
			echo '<option value="09" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '09' ) ? 'selected' : '' ) . ' >Sep</option>';
			echo '<option value="10" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '10' ) ? 'selected' : '' ) . ' >Oct</option>';
			echo '<option value="11" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '11' ) ? 'selected' : '' ) . ' >Nov</option>';
			echo '<option value="12" ' . ( ( isset( $settings->fixed_date_month ) && $settings->fixed_date_month == '12' ) ? 'selected' : '' ) . ' >Dec</option>';
			echo '</select></br><label>' . __( 'Months', 'bb-njba' ) . '</label></div>';
			echo '<div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_year" >';
			echo '<option value="0">' . __( 'Year', 'bb-njba' ) . '</option>';
			for ( $i = date( 'Y' ); $i < date( 'Y' ) + 6; $i ++ ) {
				$selected = '';
				if ( isset( $settings->fixed_date_year ) ) {
					if ( $i == $settings->fixed_date_year ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
				} else if ( $i == date( 'Y' ) + 5 ) {
					$selected = 'selected';
				}
				echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
			}
			echo '</select></br><label>' . __( 'Years', 'bb-njba' ) . '</label></div>';
			echo '<div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_hour" >';
			echo '<option value="0">' . __( 'Hour', 'bb-njba' ) . '</option>';
			for ( $i = 0; $i < 24; $i ++ ) {
				$selected = '';
				if ( isset( $settings->fixed_date_hour ) ) {
					if ( $i == $settings->fixed_date_hour ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
				} else if ( $i == 23 ) {
					$selected = 'selected';
				}
				if ( $i <= 9 ) {
					echo '<option value="' . $i . '" ' . $selected . '>0' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
				}
			}
			echo '</select></br><label>' . __( 'Hours', 'bb-njba' ) . '</label></div>';
			echo '<div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_minutes" >';
			echo '<option value="0">' . __( 'Minutes', 'bb-njba' ) . '</option>';
			for ( $i = 0; $i < 60; $i ++ ) {
				$selected = '';
				if ( isset( $settings->fixed_date_minutes ) ) {
					if ( $i == $settings->fixed_date_minutes ) {
						$selected = "selected";
					} else {
						$selected = '';
					}
				} else if ( $i == 59 ) {
					$selected = 'selected';
				}
				if ( $i <= 9 ) {
					echo '<option value="' . $i . '" ' . $selected . '>0' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
				}
			}
			echo '</select></br><label>' . __( 'Minutes', 'bb-njba' ) . '</label></div><div>';
		}

		/**
		 * njba modules option type for evergreen date countdown
		 *
		 * @param $name
		 * @param $value
		 * @param $field
		 * @param $settings
		 *
		 * @since 1.0.0
		 */
		public function njbaEvergreenDateField( $name, $value, $field, $settings ) {
			$custom_class = isset( $field['class'] ) ? $field['class'] : '';
			$selected     = '';
			$preview      = isset( $field['preview'] ) ? json_encode( $field['preview'] ) : json_encode( array( 'type' => 'refresh' ) );
			echo '<div class="fl-field njba-evergreen-wrap" data-type="select" data-preview=\'' . $preview . '\'><div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_days" >';
			echo '<option value="0">' . __( 'Days', 'bb-njba' ) . '</option>';
			for ( $i = 0; $i <= 31; $i ++ ) {
				if ( isset( $settings->evergreen_date_days ) ) {
					if ( $i == $settings->evergreen_date_days ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
				} else if ( $i == 30 ) {
					$selected = 'selected';
				}
				if ( $i <= 9 ) {
					echo '<option value="' . $i . '" ' . $selected . '>0' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
				}
			}
			echo '</select></br><label>' . __( 'Days', 'bb-njba' ) . '</label></div>';
			echo '<div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_hour" >';
			echo '<option value="0">' . __( 'Hours', 'bb-njba' ) . '</option>';
			for ( $i = 0; $i < 24; $i ++ ) {
				if ( isset( $settings->evergreen_date_hour ) ) {
					if ( $i == $settings->evergreen_date_hour ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
				} else if ( $i == 23 ) {
					$selected = 'selected';
				}
				if ( $i <= 9 ) {
					echo '<option value="' . $i . '" ' . $selected . '>0' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
				}
			}
			echo '</select></br><label>' . __( 'Hours', 'bb-njba' ) . '</label></div>';
			echo '<div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_minutes" >';
			echo '<option value="0">' . __( 'Minutes', 'bb-njba' ) . '</option>';
			for ( $i = 0; $i < 60; $i ++ ) {
				if ( isset( $settings->evergreen_date_minutes ) ) {
					if ( $i == $settings->evergreen_date_minutes ) {
						$selected = 'selected';
					} else {
						$selected = '';
					}
				} else if ( $i == 59 ) {
					$selected = 'selected';
				}
				if ( $i <= 9 ) {
					echo '<option value="' . $i . '" ' . $selected . '>0' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
				}
			}
			echo '</select></br><label>' . __( 'Minutes', 'bb-njba' ) . '</label></div>';
			echo '<div class="njba-countdown-custom-fields"><select class="text text-full" name="' . $name . '_seconds" >';
			echo '<option value="0">' . __( 'Seconds', 'bb-njba' ) . '</option>';
			for ( $i = 0; $i < 60; $i ++ ) {
				if ( isset( $settings->evergreen_date_seconds ) ) {
					$selected = $i == $settings->evergreen_date_seconds ? 'selected' : '';
				} else if ( $i == 59 ) {
					$selected = 'selected';
				}
				if ( $i <= 9 ) {
					echo '<option value="' . $i . '" ' . $selected . '>0' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
				}
			}
			echo '</select></br><label>' . __( 'Seconds', 'bb-njba' ) . '</label></div></div>';
		}
	}

	$njba_countdown = new NJBA_Countdown();
}
if ( ! class_exists( 'NJBA_Simplify' ) ) {
	class NJBA_Simplify {
		/**
		 * NJBA_Simplify constructor.
		 */
		public function __construct() {
			add_action( 'fl_builder_control_njba-simplify', [ $this, 'njbaSimplify' ], 1, 4 );
		}

		/**
		 * njba modules option type for simplify.
		 *
		 * @param $name
		 * @param $value
		 * @param $field
		 * @param $settings
		 */
		public function njbaSimplify( $name, $value, $field, $settings ) {
			if ( is_object( $value ) ) {
				$value = json_decode( json_encode( $value ), true );
			}
			$preview  = isset( $field['preview'] ) ? json_encode( $field['preview'] ) : json_encode( array( 'type' => 'refresh' ) );
			$selector = '';
			$simplify = 'collapse';
			$medias   = array(
				'desktop'       => ( isset( $value['desktop'] ) ) ? $value['desktop'] : '',
				'medium_device' => ( isset( $value['medium'] ) ) ? $value['medium'] : '', // Medium Device
				'small_device'  => ( isset( $value['small'] ) ) ? $value['small'] : '',   // Small Device
			);
			if ( $medias['medium_device'] != '' || $medias['small_device'] != '' ) {
				$simplify = 'expand';
			}
			$simplify       = ( isset( $value['simplify'] ) ) ? $value['simplify'] : $simplify;
			$simplify_style = ( $simplify == 'collapse' ) ? 'style="display:none;"' : 'style="display:inline-block;"';
			$html           = '<div class="njba-simplify-wrapper">';
			$html           .= '  <div class="njba-simplify-items" >';
			foreach ( $medias as $key => $default_value ) {
				switch ( $key ) {
					case 'desktop':
						$style    = '';
						$selector = ' data-type="text" data-preview=\'' . $preview . '\'';
						$class    = 'fl-field require';
						$data_id  = strtolower( ( preg_replace( '/\s+/', '_', $key ) ) );
						$dashicon = "<i class='dashicons dashicons-desktop njba-help-tooltip'></i>";
						$html     .= "<div class='njba-size-wrap'>";
						$html     .= $this->njbaSimplifyMediaField( $name, $class, $dashicon, $key, $default_value, $selector, $data_id, $style );
						$html     .= "<div class='simplify' njba-toggle='" . $simplify . "'>
                                        <input type='hidden' class='simplify_toggle' name='" . $name . "[][simplify]' value='" . $simplify . "'>
                                        <i class='simplify-icon dashicons dashicons-arrow-right-alt2 njba-help-tooltip'></i>
                                        <div class='njba-tooltip simplify-options'>" . __( "Responsive Options", "njba" ) . '</div>
                                      </div>';
						$html     .= '</div>';
						break;
					case 'medium_device':
						$style    = $simplify_style;
						$selector = '';
						$class    = 'optional';
						$data_id  = strtolower( ( preg_replace( '/\s+/', '_', $key ) ) );
						$dashicon = "<i class='dashicons dashicons-tablet njba-help-tooltip' style='transform: rotate(90deg);'></i>";
						$html     .= "<div class='njba-simplify-size-wrap'>";
						$html     .= $this->njbaSimplifyMediaField( $name, $class, $dashicon, $key, $default_value, $selector, $data_id, $style );
						break;
					case 'small_device':
						$style    = $simplify_style;
						$selector = '';
						$class    = 'optional';
						$data_id  = strtolower( ( preg_replace( '/\s+/', '_', $key ) ) );
						$dashicon = "<i class='dashicons dashicons-smartphone njba-help-tooltip'></i>";
						$html     .= $this->njbaSimplifyMediaField( $name, $class, $dashicon, $key, $default_value, $selector, $data_id, $style );
						$html     .= '</div>';
						break;
				}
			}
			$html .= '  </div>';
			$html .= '</div>';
			echo $html;
		}

		/**
		 * simplify input fields
		 *
		 * @param $name
		 * @param $class
		 * @param $dashicon
		 * @param $key
		 * @param $default_value
		 * @param $selector
		 * @param $data_id
		 * @param $style
		 *
		 * @return string
		 */
		public function njbaSimplifyMediaField( $name, $class, $dashicon, $key, $default_value, $selector, $data_id, $style ) {
			$tooltipVal = str_replace( '_', ' ', $data_id );
			$html       = '<div class="njba-simplify-item ' . $class . ' ' . $data_id . ' "' . $selector . ' ' . $style . '>';
			$html       .= '<span class="njba-icon">';
			$html       .= $dashicon;
			$html       .= '<div class="njba-tooltip ' . $data_id . '">' . ucwords( $tooltipVal ) . '</div>';
			$html       .= '</span>';
			$html       .= '    <input type="text" name="' . $name . '[][' . str_replace( '_device', '',
					$key ) . ']" class="njba-simplify-input" maxlength="3" size="6" value="' . $default_value . '" />';
			$html       .= '  </div>';

			return $html;
		}
	}

	new NJBA_Simplify();
}
if ( ! class_exists( 'NJBA_Render_Js_Css' ) ) {
	class NJBA_Render_Js_Css {
		/**
		 * NJBA_Render_Js_Css constructor.
		 */
		public function __construct() {
			add_filter( 'fl_builder_render_css', [ $this, 'fl_njba_render_css' ], 10, 3 );
			add_filter( 'fl_builder_render_js', [ $this, 'fl_njba_render_js' ], 10, 3 );
		}

		/**
		 * Render Global njba-layout-builder css
		 *
		 * @param $css
		 * @param $nodes
		 * @param $global_settings
		 *
		 * @return string
		 * @since  1.0.0
		 */
		public function fl_njba_render_css( $css, $nodes, $global_settings ) {
			$css .= file_get_contents( NJBA_MODULE_DIR . 'assets/css/njba-frontend.css' );

			return $css;
		}

		/**
		 * Render Global njba-layout-builder js
		 *
		 * @param $js
		 * @param $nodes
		 * @param $global_settings
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function fl_njba_render_js( $js, $nodes, $global_settings ) {
			$temp = file_get_contents( NJBA_MODULE_DIR . 'assets/js/njba-frontend.js' ) . $js;
			$js   = $temp;

			return $js;
		}
	}

	new NJBA_Render_Js_Css();
}

if ( ! class_exists( 'NJBA_Draggable' ) ) {
	class NJBA_Draggable {
		/**
		 * NJBA_Draggable constructor.
		 */
		public function __construct() {
			add_action( 'fl_builder_control_njba-draggable', [ $this, 'njba_draggable' ], 1, 4 );
		}

		/**
		 * njba draggable fields
		 *
		 * @param $name
		 * @param $value
		 * @param $field
		 * @param $settings
		 *
		 * @since 1.0.0
		 */
		public function njba_draggable( $name, $value, $field, $settings ) {
			$val     = ( isset( $value ) && $value != '' ) ? $value : '0,0';
			$coord   = explode( ',', $val );
			$preview = isset( $field['preview'] ) ? json_encode( $field['preview'] ) : json_encode( array( 'type' => 'refresh' ) );
			echo "<script>jQuery(function(){ NJBAFields._initDraggableFields({name:'" . $name . "'}); });</script><div class='njba-draggable-wrap fl-field' data-type='text' data-preview='" . $preview . "'><div class='njba-draggable-section'></div><div class='njba-draggable-point' style='top:" . $coord[1] . "%;left:" . $coord[0] . "%;'></div></div><input type='hidden' value='" . $val . "' name='" . $name . "' />";
		}
	}

	new NJBA_Draggable();
}
?>
<?php
if ( ! class_exists( 'NJBA_FB_Setting' ) ) { //NJBA_FB_Setting Type
	class NJBA_FB_Setting {
		/**
		 * Returns Facebook App ID from njba Settings.
		 * @return mixed
		 * @since 1.6
		 */
		public function njbaGetFbAppId() {
			$options = get_option( 'njba_options' );

			return $options['facebook_app_id'];
		}

		/**
		 * Build the URL of Facebook SDK.
		 * @return string
		 * @since 1.6
		 */
		public function njbaGetFbSdkUrl() {
			$app_id = $this->njbaGetFbAppId();
			if ( $app_id && ! empty( $app_id ) ) {
				return sprintf( 'https://connect.facebook.net/%s/sdk.js#xfbml=1&version=v2.12&appId=%s', get_locale(), $app_id );
			}

			return sprintf( 'https://connect.facebook.net/%s/sdk.js#xfbml=1&version=v2.12', get_locale() );
		}

		/**
		 * This Function not used any placed in at that time.
		 * @return string
		 */
		public function njbaGetFbAppSettingsUrl() {
			$app_id = $this->njbaGetFbAppId();
			if ( $app_id ) {
				return sprintf( 'https://developers.facebook.com/apps/%d/settings/', $app_id );
			}

			return 'https://developers.facebook.com/apps/';
		}

		/**
		 * Get njba module Description.
		 * @return string
		 * @since 1.6
		 */
		public function njbaGetFbModuleDesc() {
			$app_id = $this->njbaGetFbAppId();
			if ( ! $app_id ) {
				return sprintf( __( 'You can set your Facebook App ID in the <a href="%s" target="_blank"> General Settings</a></br></br>For Facebook App ID, you need to <a href="https://developers.facebook.com/docs/apps/register/" target="_blank"> register and configure</a> an app.</br></br>Once registered, add the domain to your <a href="https://developers.facebook.com/apps/" target="_blank"> App Domains </a></br></br>Looking for More Info <a href="https://www.ninjabeaveraddon.com/documentation/" target="_blank"> Click Here </a>.',
					'bb-njba' ), admin_url( 'admin.php?page=njba-admin-setting#general' ) );
			}

			return sprintf( __( 'You are connected to Facebook App %1$s, <a href="%2$s" target="_blank"> Change App </a></br></br>For Facebook App ID, you need to <a href="https://developers.facebook.com/docs/apps/register/" target="_blank"> register and configure</a> an app.</br></br>Once registered, add the domain to your <a href="https://developers.facebook.com/apps/" target="_blank"> App Domains </a></br></br>Looking for More Info <a href="https://www.ninjabeaveraddon.com/documentation/" target="_blank"> Click Here </a>.',
				'bb-njba' ), $app_id, admin_url( 'admin.php?page=njba-admin-setting#general' ) );
		}
	}
}
