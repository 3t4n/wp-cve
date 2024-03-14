<?php
/**
 * Creates minified css via PHP.
 */

if( ! class_exists( 'BWF_Blocks_CSS' ) ) {
	
	/**
	 * Class to create a minified css output.
	 */
	class BWF_Blocks_CSS {
	
		/**
		 * The css selector that you're currently adding rules to
		 *
		 * @access protected
		 * @var string
		 */
		protected $_selector = '';
	
		/**
		 * Associative array of Google Fonts to load.
		 *
		 * Do not access this property directly, instead use the `get_google_fonts()` method.
		 *
		 * @var array
		 */
		protected static $google_fonts = array();
	
		/**
		 * Stores the final css output with all of its rules for the current selector.
		 *
		 * @access protected
		 * @var string
		 */
		protected $_selector_output = '';
	
		/**
		 * Can store a list of additional selector states which can be added and removed.
		 *
		 * @access protected
		 * @var array
		 */
		protected $_selector_states = array();
	
		/**
		 * Stores a list of css properties that require more formating
		 *
		 * @access private
		 * @var array
		 */
		private $_special_properties_list = array(
			'transition',
			'transition-delay',
			'transition-duration',
			'transition-property',
			'transition-timing-function',
			'flex',
			'background',
			'padding',
			'margin',
			'content',
			'border',
			'font',
			'text',
			'box-shadow',
		);
	
		/**
		 * Stores all of the rules that will be added to the selector
		 *
		 * @access protected
		 * @var string
		 */
		protected $_css = '';
	
		/**
		 * Stores all of the custom css.
		 *
		 * @access protected
		 * @var string
		 */
		protected $_css_string = '';
	
		/**
		 * The string that holds all of the css to output
		 *
		 * @access protected
		 * @var string
		 */
		protected $_output = '';
	
		/**
		 * Stores media queries
		 *
		 * @var null
		 */
		protected $_media_query = null;
	
		/**
		 * The string that holds all of the css to output inside of the media query
		 *
		 * @access protected
		 * @var string
		 */
		protected $_media_query_output = '';
	
		/**
		 * Sets a selector to the object and changes the current selector to a new one
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @param  string $selector - the css identifier of the html that you wish to target.
		 * @return $this
		 */
		public function set_selector( $selector = '' ) {
			// Render the css in the output string everytime the selector changes.
			if ( '' !== $this->_selector ) {
				$this->add_selector_rules_to_output();
			}
			$this->_selector = $selector;
			return $this;
		}
		/**
		 * Sets css string for final output.
		 *
		 * @param  string $string - the css string.
		 * @return $this
		 */
		public function add_css_string( $string ) {
			$this->_css_string .= $string;
			return $this;
		}
	
		/**
		 * Wrapper for the set_selector method, changes the selector to add new rules
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @see    set_selector()
		 * @param  string $selector the css selector.
		 * @return $this
		 */
		public function change_selector( $selector = '' ) {
			return $this->set_selector( $selector );
		}
	
		/**
		 * Adds a pseudo class to the selector ex. :hover, :active, :focus
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @param  $state - the selector state
		 * @param  reset - if true the        $_selector_states variable will be reset
		 * @return $this
		 */
		public function add_selector_state( $state, $reset = true ) {
			if ( $reset ) {
				$this->reset_selector_states();
			}
			$this->_selector_states[] = $state;
			return $this;
		}
	
		/**
		 * Adds multiple pseudo classes to the selector
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @param  array $states - the states you would like to add
		 * @return $this
		 */
		public function add_selector_states( $states = array() ) {
			$this->reset_selector_states();
			foreach ( $states as $state ) {
				$this->add_selector_state( $state, false );
			}
			return $this;
		}
	
		/**
		 * Removes the selector's pseudo classes
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @return $this
		 */
		public function reset_selector_states() {
			$this->add_selector_rules_to_output();
			if ( ! empty( $this->_selector_states ) ) {
				$this->_selector_states = array();
			}
			return $this;
		}
	
		/**
		 * Adds a new rule to the css output
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @param  string $property - the css property.
		 * @param  string $value - the value to be placed with the property.
		 * @param  string $prefix - not required, but allows for the creation of a browser prefixed property.
		 * @return $this
		 */
		public function add_rule( $property, $value, $prefix = null ) {
			$format = is_null( $prefix ) ? '%1$s:%2$s;' : '%3$s%1$s:%2$s;';
			if ( $value && ! empty( $value ) || 0 === $value || '0' === $value ) {
				$this->_css .= sprintf( $format, $property, $value, $prefix );
			}
			return $this;
		}
	
		/**
		 * Adds browser prefixed rules, and other special rules to the css output
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @param  string $property - the css property
		 * @param  string $value - the value to be placed with the property
		 * @return $this
		 */
		public function add_special_rules( $property, $value ) {
			// Switch through the property types and add prefixed rules.
			switch ( $property ) {
				case 'border-top-left-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-topleft', $value, '-moz-' );
					break;
	
				case 'border-top-right-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-topright', $value, '-moz-' );
					break;
	
				case 'border-bottom-left-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-bottomleft', $value, '-moz-' );
					break;
	
				case 'border-bottom-right-radius':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( 'border-radius-bottomright', $value, '-moz-' );
					break;
	
				case 'background':
					$this->add_background_property( $property, $value );
					break;
	
				case 'padding':
					$this->add_sizing_property( $property, $value );
					break;
	
				case 'margin':
					$this->add_sizing_property( $property, $value );
					break;
	
				case 'border':
					$this->add_border_property( $property, $value );
					break;
	
				case 'text':
					$this->add_typograpghy_property( $property, $value );
					break;
	
				case 'font':
					$this->add_typograpghy_property( $property, $value );
					break;
	
				case 'box-shadow':
					$this->add_boxshadow_property( $property, $value );
					break;
	
				case 'content':
					$this->add_rule( $property, sprintf( '%s', $value ) );
					break;
	
				case 'flex':
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					break;
	
				default:
					$this->add_rule( $property, $value );
					$this->add_rule( $property, $value, '-webkit-' );
					$this->add_rule( $property, $value, '-moz-' );
					break;
			}
	
			return $this;
		}
	
		/**
		 * @param string $property
		 * @param array $value
		 */
		public function add_unit_value_rule( $property, $value, $index_key = 'value' ) {
			if( is_array( $value ) ) {
				if( isset( $value[ $index_key ] ) && '' !== $value[$index_key] && null !== $value[$index_key] ) {
					$unit = isset( $value[ 'unit' ] ) && $value[ 'unit' ] ? $value[ 'unit' ] : 'px';
					$this->add_rule( $property, sprintf( '%1$s%2$s', $value[ $index_key ], $unit ) );	
				}
			} else if( is_string( $value ) || is_numeric( $value ) ) {
				
				$this->add_rule( $property, $value );
			}
	
			return $this;
		}
	
		/**
		 * Adds a css property with value to the css output
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @param  string $property - the css property
		 * @param  string $value - the value to be placed with the property
		 * @param  string optional $unit_value_pair - pass true or value_key when value contain [ 'value' => '10', unit => 'px' ] values structure
		 * @return $this
		 */
		public function add_property( $property, $value = null, $unit_value_pair = false ) {
			if ( null === $value || '' === $value ) {
				return $this;
			}
			
			if( $unit_value_pair ) {
				$unit_value_pair = true === $unit_value_pair ? 'value' : $unit_value_pair;
				$this->add_unit_value_rule( $property, $value, $unit_value_pair );
			} else {
				if ( in_array( $property, $this->_special_properties_list ) ) {
					$this->add_special_rules( $property, $value );
				} else {
					$this->add_rule( $property, $value );
				}
			}
			return $this;
		}
	
		/**
		 * Adds multiple properties with their values to the css output
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @param  array $properties - a list of properties and values
		 * @return $this
		 */
		public function add_properties( $properties ) {
			foreach ( (array) $properties as $property => $value ) {
				$this->add_property( $property, $value );
			}
			return $this;
		}
	
		/**
		 * Sets a media query in the class
		 *
		 * @since  1.1
		 * @param  string $value
		 * @return $this
		 */
		public function start_media_query( $value ) {
			// Add the current rules to the output
			$this->add_selector_rules_to_output();
	
			// Add any previous media queries to the output
			if ( $this->has_media_query() ) {
				$this->add_media_query_rules_to_output();
			}
	
			// Set the new media query
			$this->_media_query = $value;
			return $this;
		}
	
		/**
		 * Stops using a media query.
		 *
		 * @see    start_media_query()
		 *
		 * @since  1.1
		 * @return $this
		 */
		public function stop_media_query() {
			return $this->start_media_query( null );
		}
	
		/**
		 * Gets the media query if it exists in the class
		 *
		 * @since  1.1
		 * @return string|int|null
		 */
		public function get_media_query() {
			return $this->_media_query;
		}
	
		/**
		 * Checks if there is a media query present in the class
		 *
		 * @since  1.1
		 * @return boolean
		 */
		public function has_media_query() {
			if ( ! empty( $this->get_media_query() ) ) {
				return true;
			}
	
			return false;
		}
	
		/**
		 * Adds the current media query's rules to the class' output variable
		 *
		 * @since  1.1
		 * @return $this
		 */
		private function add_media_query_rules_to_output() {
			if ( ! empty( $this->_media_query_output ) ) {
				$this->_output .= sprintf( '@media all and %1$s{%2$s}', $this->get_media_query(), $this->_media_query_output );
	
				// Reset the media query output string.
				$this->_media_query_output = '';
			}
	
			return $this;
		}
	
		/**
		 * Adds the current selector rules to the output variable
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @return $this
		 */
		private function add_selector_rules_to_output() {
			if ( ! empty( $this->_css ) ) {
				$this->prepare_selector_output();
				$selector_output = sprintf( '%1$s{%2$s}', $this->_selector_output, $this->_css );
	
				if ( $this->has_media_query() ) {
					$this->_media_query_output .= $selector_output;
					$this->reset_css();
				} else {
					$this->_output .= $selector_output;
				}
	
				// Reset the css.
				$this->reset_css();
			}
	
			return $this;
		}
	
		/**
		 * Prepares the $_selector_output variable for rendering
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @return $this
		 */
		private function prepare_selector_output() {
			if ( ! empty( $this->_selector_states ) ) {
				// Create a new variable to store all of the states.
				$new_selector = '';
	
				foreach ( (array) $this->_selector_states as $state ) {
					$format = end( $this->_selector_states ) === $state ? '%1$s%2$s' : '%1$s%2$s,';
					$new_selector .= sprintf( $format, $this->_selector, $state );
				}
				$this->_selector_output = $new_selector;
			} else {
				$this->_selector_output = $this->_selector;
			}
			return $this;
		}
	
	
		/**
		 * Outputs a string if set.
		 *
		 * @param array  $string a string setting.
		 * @param string $unit if needed add unit.
		 * @return string
		 */
		public function render_string( $string = null, $unit = null ) {
			if ( empty( $string ) ) {
				return false;
			}
			$string = $string . ( isset( $unit ) && ! empty( $unit ) ? $unit : '' );
	
			return $string;
		}
		/**
		 * Outputs a string if set.
		 *
		 * @param array  $number a string setting.
		 * @param string $unit if needed add unit.
		 * @return string
		 */
		public function render_number( $number = null, $unit = null ) {
			if ( ! is_numeric( $number ) ) {
				return false;
			}
			$number = $number . ( isset( $unit ) && ! empty( $unit ) ? $unit : '' );
	
			return $number;
		}

		/**
		 * Generates the background output.
		 *
		 * @param array  $background an array of background settings.
		 * @param object $css an object of css output.
		 */
		public function add_background_property( $property, $value ) {
	
			$gradient = null;
			if( is_string( $value ) || is_numeric( $value ) ) {
				$this->add_rule( $property, $value );
			} else if( is_array( $value ) && ! empty( $value ) ) {
				foreach ($value as $key => $bg) {
					
					if( empty( $bg ) ) {
						continue;
					}
					
					switch ($key) {
						case 'gradient' :
							$gradient = $bg;
							$this->add_rule( $property, $bg );
							break;
	
						case 'image' :
							$image_url = $bg['url'] ?? '';
							if ( $image_url ) {
								if ( $gradient ) {
									$this->add_rule( "background-$key", sprintf( "url('%s'), %s", $image_url, $gradient ) );
								} else {
									$this->add_rule( "background-$key", sprintf( "url('%s')", $image_url ) );
								}
							}
							break;
	
						case 'position' :
							$position = is_array( $bg ) ? ( ( isset( $bg[0] ) ? $bg[0] * 100 : 0 ) . '% ' . ( isset( $bg[1] ) ? $bg[1] * 100 : 0 ) . '%' ) : '';
							$this->add_rule( "background-$key", $position );
							break;
						
						default :
							$this->add_rule( "background-$key", $bg );
					}
				}
			}
			return $this;
		
		}
		/**
		 * Spacing for padding and margin 
		 * @param string $property css name
		 * @param object $value css value [ top => '', bottom => '', left => '', right => '', unit => '' ]
		 */
		public function add_sizing_property( $property, $value ) {
			if( is_string( $value ) ) {
				$this->add_rule( $property, $value );
			} else if ( is_array( $value ) && ! empty( $value ) ) {
				$unit = isset( $value['unit'] ) && ! empty( $value['unit'] ) ? $value['unit'] : 'px';
				if( isset( $value['top'] ) && isset( $value['bottom'] ) && isset( $value['left'] ) && isset( $value['right'] ) ) {
	
					if( ! empty( $value['top'] ) && $value['top'] === $value['right'] && $value['right'] === $value['left'] && $value['left'] === $value['bottom'] ) {
						$val = sprintf( '%1$s%2$s', $value['top'], $unit  );
						$this->add_rule( $property, $val );
						return $this;
					} else if (
						( $value['top'] || '0' === (string) $value['top'] ) &&
						( $value['right'] || '0' === (string) $value['right'] ) &&
						( $value['bottom'] || '0' === (string) $value['bottom'] ) &&
						( $value['left'] || '0' === (string) $value['left'] )
					 ) {
						$val = $this->shorthand_css( $value['top'], $value['right'], $value['bottom'], $value['left'], $unit );
						$this->add_rule( $property, $val );
						return $this;
					 }
	
				}
				foreach ( $value as $key => $spacing ) {
					if( 'unit' === $key ) {
						continue;
					}
	
					if( ! empty( $spacing ) || 0 === $spacing || '0' === $spacing ) {
						$this->add_rule( $key, $spacing . $unit, $property . '-'  );
					}
				}
			}
			
			return $this;
		}
	
		public function add_typograpghy_property( $property, $value, $skipAttrs = [] ) {
			if( is_array( $value ) ) {
				foreach( $value as $key => $typo ) {
					if( 'sizeUnit' === $key || in_array( $key, $skipAttrs ) ) {
						continue;
					}
					if( 'size' === $key ) {
						if( empty( $typo ) ) {
							continue;
						}
						if( is_string( $typo ) ) {
							$this->add_rule( $key, $typo, $property . '-' );
						} else {
							$unit = isset( $value['sizeUnit'] ) && ! empty( $value['sizeUnit'] ) ? $value['sizeUnit'] : 'px';
							$this->add_rule( $key, $typo . $unit, $property . '-' );
						}
					} else if ('shadow' === $key) {
						if( is_array( $typo ) && ! empty( $typo ) ) {
							$x_axis = isset( $typo['x'] ) ? $typo['x'] . 'px' : '0px';
							$y_axis = isset( $typo['y'] ) ? $typo['y'] . 'px' : '0px';
							$blur = isset( $typo['blur'] ) ? $typo['blur'] . 'px' : '0px';
							$color = isset( $typo['color'] ) ? $typo['color'] : '';
							$val = sprintf( '%1$s %2$s %3$s %4$s', $x_axis, $y_axis, $blur, $color );
							$this->add_rule( $key, $val, $property . '-' );
						}
						
					} else {
						$this->add_rule( $key, $typo, $property . '-' );
					}
				}
			} else if( is_string( $value ) ) {
				$this->add_rule( $property, $value );
			}
			return $this;
	
		}
	
		public function add_boxshadow_property( $property, $value ) {
			if( is_array( $value ) && count( array_filter( $value ) ) > 0 ) {
				$h_offset = isset( $value['h_offset'] ) && ! empty( $value['h_offset'] ) ? $value['h_offset'] . 'px' : '0px';
				$v_offset = isset( $value['v_offset'] ) && ! empty( $value['v_offset'] ) ? $value['v_offset'] . 'px' : '0px'; 
				$blur     = isset( $value['blur'] ) && ! empty( $value['blur'] ) ? $value['blur'] . 'px' : '0px';
				$spread   = isset( $value['spread'] ) && ! empty( $value['spread'] ) ? $value['spread'] . 'px' : '0px';
				$color    = isset( $value['color'] ) && ! empty( $value['color'] ) ? $value['color'] : 'transparent';
				$inset    = isset( $value['inset'] ) && ! empty( $value['inset'] ) ? 'inset' : '';
				$val      = trim( sprintf( '%1$s %2$s %3$s %4$s %5$s %6$s', $h_offset, $v_offset, $blur, $spread, $color, $inset  ) );
				$this->add_rule( $property, $val );
			} else if ( is_string( $value ) ) {
				$this->add_rule( $property, $value );
			}
			return $this;
		}

		public function shorthand_css( $top, $right, $bottom, $left, $unit ) {
			if ( '' === $top && '' === $right && '' === $bottom && '' === $left ) {
				return;
			}
		
			$top = ( floatval( $top ) <> 0 ) ? floatval( $top ) . $unit . ' ' : '0 '; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$right = ( floatval( $right ) <> 0 ) ? floatval( $right ) . $unit . ' ' : '0 '; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$bottom = ( floatval( $bottom ) <> 0 ) ? floatval( $bottom ) . $unit . ' ' : '0 '; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$left = ( floatval( $left ) <> 0 ) ? floatval( $left ) . $unit . ' ' : '0 '; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		
			if ( $right === $left ) {
				$left = '';
		
				if ( $top === $bottom ) {
					$bottom = '';
		
					if ( $top === $right ) {
						$right = '';
					}
				}
			}
		
			return trim( $top . $right . $bottom . $left );
		}
	
		public function add_border_property( $property, $value ) {
			if( is_array( $value ) && count( array_filter( $value ) ) > 0 ) {
				$unit          = isset( $value['unit'] ) && ! empty( $value['unit'] ) ? $value['unit'] : 'px';
				$color         = $value['color'] ?? '';
				$border_top    = $value['top'] ?? '';
				$border_left   = $value['left'] ?? '';
				$border_right  = $value['right'] ?? '';
				$border_bottom = $value['bottom'] ?? '';
				$color_left    = $value['color_left'] ?? '';
				$color_right   = $value['color_right'] ?? '';
				$color_bottom  = $value['color_bottom'] ?? '';
				$border_style  = $value['style'] ?? '';
				

				if(
					( ! empty( $border_top ) || '0' === (string) $border_top ) &&
					( ! empty( $border_right ) || '0' === (string) $border_right ) &&
					( ! empty( $border_bottom ) || '0' === (string) $border_bottom ) &&
					( ! empty( $border_left ) || '0' === (string) $border_left ) ) {
					$this->add_rule( 'border-width', $this->shorthand_css( $border_top, $border_right, $border_bottom, $border_left, $unit ) );
					$this->add_rule( 'border-style', $border_style );

				} else if ( empty( $border_top ) && empty( $border_right ) && empty( $border_bottom ) && empty( $border_left )  ) {
					$this->add_rule( 'border-style', $border_style );
				} else {
					if( $border_top || '0' === (string) $border_top ) {
						$this->add_rule( 'border-top-width', sprintf( '%1$s%2$s', $border_top, $unit ) );
						$this->add_rule( 'border-top-style', $border_style );

					}
					if( $border_left || '0' === (string) $border_left ) {
						$this->add_rule( 'border-left-width', sprintf( '%1$s%2$s', $border_left, $unit ) );
						$this->add_rule( 'border-left-style', $border_style );
	
					}
					if( $border_right || '0' === (string) $border_right ) {
						$this->add_rule( 'border-right-width', sprintf( '%1$s%2$s', $border_right, $unit ) );
						$this->add_rule( 'border-right-style', $border_style );
	
					}
					if( $border_bottom || '0' === (string) $border_bottom ) {
						$this->add_rule( 'border-bottom-width', sprintf( '%1$s%2$s', $border_bottom, $unit ) );
						$this->add_rule( 'border-bottom-style', $border_style );
	
					}

				}

				if( $color && $color_right && $color_bottom && $color_left ) {
					if( ( $color == $color_bottom ) && ( $color == $color_right ) && ( $color == $color_left ) ) {
						$this->add_rule( 'border-color', $color );
					} else {
						$this->add_rule( 'border-color', "$color $color_right $color_bottom $color_left" );
					}
				} else {
					$color && $this->add_rule( 'border-top-color', $color );
					$color_left && $this->add_rule( 'border-left-color', $color_left );
					$color_right &&	$this->add_rule( 'border-right-color', $color_right );
					$color_bottom && $this->add_rule( 'border-bottom-color', $color_bottom );
				}
	
	
				//Compute Border radius
				$radius_unit = isset( $value['radius_unit'] ) && ! empty( $value['radius_unit'] ) ? $value['radius_unit'] : 'px';
				$radius_TL   = isset( $value['radius'] ) ? $value['radius'] : '';
				$radius_TR   = isset( $value['top-right'] ) ? $value['top-right'] : '';
				$radius_BL   = isset( $value['bottom-left'] ) ? $value['bottom-left'] : '';
				$radius_BR   = isset( $value['bottom-right'] ) ? $value['bottom-right'] : '';
				
				if( ( $radius_TL || 0 === $radius_TL || '0' === $radius_TL ) && $radius_TL === $radius_TR && $radius_TR === $radius_BL && $radius_BL === $radius_BR ) {
					$this->add_rule( 'border-radius', sprintf( '%1$s%2$s', $radius_TL, $radius_unit ) );
				} else {
					if( $radius_TL || 0 === $radius_TL || '0' === $radius_TL ) {
						$this->add_rule( 'border-top-left-radius', sprintf( '%1$s%2$s', $radius_TL, $radius_unit ) );
					}
					if( $radius_TR || 0 === $radius_TR || '0' === $radius_TR ) {
						$this->add_rule( 'border-top-right-radius', sprintf( '%1$s%2$s', $radius_TR, $radius_unit ) );
					}
					if( $radius_BL || 0 === $radius_BL || '0' === $radius_BL) {
						$this->add_rule( 'border-bottom-left-radius', sprintf( '%1$s%2$s', $radius_BL, $radius_unit ) );
					}
					if( $radius_BR || 0 === $radius_BR || '0' === $radius_TL ) {
						$this->add_rule( 'border-bottom-right-radius', sprintf( '%1$s%2$s', $radius_BR, $radius_unit ) );
					}
				}
			} else if ( is_string( $value ) ) {
				$this->add_rule( $property, $value );
			}
			return $this;
		}
	
		/**
		 * Resets the css variable
		 *
		 * @access private
		 * @since  1.1
		 *
		 * @return void
		 */
		private function reset_css() {
			$this->_css = '';
			return;
		}
	
		/**
		 * Returns the google fonts array from the compiled css.
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @return string
		 */
		public function fonts_output() {
			return self::$google_fonts;
		}
	
		/**
		 * Returns the minified css in the $_output variable
		 *
		 * @access public
		 * @since  1.0
		 *
		 * @return string
		 */
		public function css_output() {
			// Add current selector's rules to output
			$this->add_selector_rules_to_output();
	
			$this->_output .= $this->_css_string;
	
			// Output minified css
			return $this->_output;
		}
	
		public function minify_css( $style = '' ) {
			if( empty( $style ) ) $style;
			$style = preg_replace( '/\s{2,}/s', ' ', $style );
			$style = preg_replace( '/\s*([:;{}])\s*/', '$1', $style );
			$style = preg_replace( '/;}/', '}', $style );
			return $style;
		}
	
		public function custom_css( $custom_css = '', $replace_selector = '' ) {
			if( empty( $replace_selector ) || empty( $custom_css ) ) {
				return $this;
			}
			$this->_output .= $this->minify_css( preg_replace( '/selector/i', $replace_selector, $custom_css ) );
			return $this;
		}
	
	}
}
