<?php
if ( !class_exists( 'RSC_Fields' ) ) {

	class RSC_Fields {

		public static function render_field( $field, $key = false ) {

			switch ( $field[ 'field_type' ] ) {
				case 'function':
					RSC_Fields::field_function( $field, $key );
					break;
				case 'text':
					RSC_Fields::field_text( $field, $key );
					break;
				case 'option': // Depricated, same as text
					RSC_Fields::field_text( $field, $key );
					break;
				case 'textarea':
					RSC_Fields::field_textarea( $field, $key );
					break;
				case 'wp_editor':
					RSC_Fields::field_wp_editor( $field, $key );
					break;
				case 'radio':
					RSC_Fields::field_radio( $field, $key );
					break;
				case 'select':
					RSC_Fields::field_select( $field, $key );
					break;
                case 'color_field':
					RSC_Fields::color_field( $field, $key );
					break;
				case 'file':
					RSC_Fields::field_file( $field, $key );
					break;
				/* case 'checkbox':
				  TC_Fields::field_checkbox( $field, $key );
				  break; */
				case 'date':
					RSC_Fields::field_date( $field, $key );
					break;
				default:
					RSC_Fields::field_text( $field, $key );
			}
		}

        /**
         * Render fields by type (function, text, textarea, etc)
         *
         * @param bool $obj_class
         * @param $field
         * @param $post_id
         * @param bool $show_title
         */
		public static function render_post_type_field( $obj_class, $field, $post_id, $show_title = true ) {

            /*
             * Backward Compatibility
             * PHP Deprecated: Required parameter follows optional parameter.
             */
            $obj_class = $obj_class ? $obj_class : false;

			if ( !$obj_class ) {
				echo '<strong>Class cannot be empty - called from render_post_type_field method</strong>';
				return;
			}

			if ( !class_exists( $obj_class ) ) {
				echo '<strong>Class ' . $obj_class . ' doesn\'t exists called from render_post_type_field method</strong>';
				return;
			}

			$obj = new $obj_class( $post_id );

			if ( $show_title ) { ?>
				<label><?php echo rsc_esc_html( $field[ 'field_title' ] ); ?><?php
				}
				// Function
				if ( $field[ 'field_type' ] == 'function' ) {
					eval( $field[ 'function' ] . '("' . $field[ 'field_name' ] . '"' . (isset( $post_id ) ? ',' . $post_id : '') . ');' );
					if ( isset( $field[ 'field_description' ] ) ) { ?>
						<span class="description"><?php echo isset( $field[ 'field_description' ] ) ? rsc_esc_html( $field[ 'field_description' ] ) : ''; ?></span><?php
					}
				}
				// Text
				if ( $field[ 'field_type' ] == 'text' ) { ?>
					<input type="text" class="regular-<?php echo esc_attr( $field[ 'field_type' ] ); ?>" value="<?php
					if ( isset( $obj ) ) {
						if ( $field[ 'post_field_type' ] == 'post_meta' ) {
							echo esc_attr( isset( $obj->details->{$field[ 'field_name' ]} ) ? $obj->details->{$field[ 'field_name' ]} : ''  );
						} else {
							echo esc_attr( $obj->details->{$field[ 'post_field_type' ]} );
						}
					}
					?>" id="<?php echo esc_attr( isset( $field[ 'field_name' ] ) ? $field[ 'field_name' ] : ''  ); ?>" name="<?php echo esc_attr( $field[ 'field_name' ] . '_' . $field[ 'post_field_type' ] ); ?>" placeholder="<?php echo esc_attr( isset( $field[ 'placeholder' ] ) ? $field[ 'placeholder' ] : ''  ); ?>"  <?php echo isset( $field[ 'required' ] ) ? 'required' : ''; ?> <?php echo isset( $field[ 'number' ] ) ? 'number="true"' : ''; ?>>
                        <?php if ( isset( $field[ 'field_description' ] ) ) { ?>
						<span class="description"><?php echo isset( $field[ 'field_description' ] ) ? rsc_esc_html( $field[ 'field_description' ] ) : ''; ?></span><?php
					}
				}
				// Textare
				if ( $field[ 'field_type' ] == 'textarea' ) { ?>
					<textarea class="regular-<?php echo esc_attr( $field[ 'field_type' ] ); ?>" id="<?php echo esc_attr( $field[ 'field_name' ] ); ?>" name="<?php echo esc_attr( $field[ 'field_name' ] . '_' . $field[ 'post_field_type' ] ); ?>"><?php
						if ( isset( $obj ) ) {
							if ( $field[ 'post_field_type' ] == 'post_meta' ) {
								echo esc_textarea( isset( $obj->details->{$field[ 'field_name' ]} ) ? $obj->details->{$field[ 'field_name' ]} : ''  );
							} else {
								echo esc_textarea( $obj->details->{$field[ 'post_field_type' ]} );
							}
						}
						?></textarea>
					<?php if ( isset( $field[ 'field_description' ] ) ) { ?>
						<span class="description"><?php echo isset( $field[ 'field_description' ] ) ? rsc_esc_html( $field[ 'field_description' ] ) : ''; ?></span><?php
					}
				}
				// Editor
				if ( $field[ 'field_type' ] == 'textarea_editor' ) {
					if ( isset( $obj ) ) {
						if ( $field[ 'post_field_type' ] == 'post_meta' ) {
							$editor_content = ( isset( $obj->details->{$field[ 'field_name' ]} ) ? $obj->details->{$field[ 'field_name' ]} : '' );
						} else {
							$editor_content = ( $obj->details->{$field[ 'post_field_type' ]} );
						}
					} else {
						$editor_content = '';
					}
					wp_editor( html_entity_decode( stripcslashes( $editor_content ) ), $field[ 'field_name' ], array( 'textarea_name' => $field[ 'field_name' ] . '_' . $field[ 'post_field_type' ], 'textarea_rows' => 5 ) );
					if ( isset( $field[ 'field_description' ] ) ) {
						?>
						<span class="description"><?php echo isset( $field[ 'field_description' ] ) ? rsc_esc_html( $field[ 'field_description' ] ) : ''; ?></span>
						<?php
					}
				}
				// Image
				if ( $field[ 'field_type' ] == 'image' ) { ?>
					<div class="file_url_holder">
						<label>
							<input class="file_url" type="text" size="36" name="<?php echo esc_attr( $field[ 'field_name' ] . '_file_url_' . $field[ 'post_field_type' ] ); ?>" value="<?php
							if ( isset( $obj ) ) {
								echo esc_attr( isset( $obj->details->{$field[ 'field_name' ] . '_file_url'} ) ? $obj->details->{$field[ 'field_name' ] . '_file_url'} : ''  );
							}
							?>" />
							<input class="file_url_button button-secondary" type="button" value="<?php _e( 'Browse', 'tc' ); ?>" />
							<?php if ( isset( $field[ 'field_description' ] ) ) { ?>
								<span class="description"><?php echo isset( $field[ 'field_description' ] ) ? rsc_esc_html( $field[ 'field_description' ] ) : ''; ?></span>
                            <?php } ?>
						</label>
					</div>
					<?php
				}
				if ( $show_title ) { ?>
				</label><?php
			}
		}

		public static function conditionals( $field, $echo = true, $additional_classes = '' ) {

		    $conditional_atts = '';

			if ( isset( $field[ 'conditional' ] ) ) {
				$conditional_atts .= ' class="rsc_conditional ' . esc_attr( $additional_classes ) . '" ';
				$conditional_atts .= ' data-condition-field_name="' . esc_attr( $field[ 'conditional' ][ 'field_name' ] ) . '" ';
				$conditional_atts .= ' data-condition-field_type="' . esc_attr( $field[ 'conditional' ][ 'field_type' ] ) . '" ';
				$conditional_atts .= ' data-condition-value="' . esc_attr( $field[ 'conditional' ][ 'value' ] ) . '" ';
				$conditional_atts .= ' data-condition-action="' . esc_attr( $field[ 'conditional' ][ 'action' ] ) . '" ';

			} else {
				$conditional_atts .= ' class="' . esc_attr( $additional_classes ) . '" ';
			}

			if ( $echo ) {
				echo $conditional_atts; // Attributes have been escaped.

			} else {
				return $conditional_atts; // Attributes have been escaped.
			}
		}

        /**
         * Render function fields
         *
         * @param $field
         * @param $key
         */
		public static function field_function( $field, $key ) {

		    if ( isset( $field[ 'default_value' ] ) ) {
				eval( $field[ 'function' ] . '("' . $field[ 'field_name' ] . '", "' . $field[ 'default_value' ] . '");' );

			} else {
				eval( $field[ 'function' ] . '("' . $field[ 'field_name' ] . '");' );
			} ?>
			<span class="description"><?php echo isset( $field[ 'field_description' ] ) ? rsc_esc_html( $field[ 'field_description' ] ) : ''; ?></span><?php
		}

        /**
         * Render input text fields
         *
         * @param $field
         * @param $key
         */
		public static function field_text( $field, $key ) {
			$rsc_settings = get_option( $key, false ); ?>
			<input type="text" class="<?php echo esc_attr( $field[ 'field_name' ] ); ?> <?php echo esc_attr( isset( $field[ 'field_class' ] ) ? $field[ 'field_class' ] : ''  ); ?>" id="<?php echo esc_attr( $field[ 'field_name' ] ); ?>" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr( $field[ 'field_name' ] ); ?>]" value="<?php echo (isset( $rsc_settings[ $field[ 'field_name' ] ] ) ? stripslashes( esc_attr( $rsc_settings[ $field[ 'field_name' ] ] ) ) : (isset( $field[ 'default_value' ] ) ? stripslashes( esc_attr( $field[ 'default_value' ] ) ) : '') ) ?>" <?php echo isset( $field[ 'required' ] ) ? 'required' : ''; ?> <?php echo isset( $field[ 'number' ] ) ? 'number="true"' : ''; ?> <?php echo isset( $field[ 'minlength' ] ) ? 'minlength="' . (int) $field[ 'minlength' ] . '"' : ''; ?> <?php echo isset( $field[ 'maxlength' ] ) ? 'maxlength="' . (int) $field[ 'maxlength' ] . '"' : ''; ?> <?php echo isset( $field[ 'rangelength' ] ) ? 'rangelength="' . (int) $field[ 'rangelength' ] . '"' : ''; ?> <?php echo isset( $field[ 'min' ] ) ? 'min="' . (int) $field[ 'min' ] . '"' : ''; ?> <?php echo isset( $field[ 'max' ] ) ? 'max="' . (int) $field[ 'max' ] . '"' : ''; ?> <?php echo isset( $field[ 'range' ] ) ? 'range="' . $field[ 'range' ] . '"' : ''; ?>>
			<span class="description"><?php echo stripslashes( isset( $field[ 'field_description' ] ) ? $field[ 'field_description' ] : ''  ); ?></span>
			<?php
		}

        /**
         * Render input text fields
         *
         * @param $field
         * @param $key
         */
		public static function color_field( $field, $key ) {
			$rsc_settings = get_option( $key, false ); ?>
            <input type="hidden" name="<?php echo esc_attr( $field[ 'field_name' ] ); ?>" value="<?php echo esc_attr( $field[ 'default_value' ] ); ?>" class="tc-default-color"/>
			<input type="text" class="rsc_color_field <?php echo esc_attr( $field[ 'field_name' ] ); ?> <?php echo esc_attr( isset( $field[ 'field_class' ] ) ? $field[ 'field_class' ] : ''  ); ?>" id="<?php echo esc_attr( $field[ 'field_name' ] ); ?>" name="<?php echo esc_attr($key); ?>[<?php echo esc_attr( $field[ 'field_name' ] ); ?>]" value="<?php echo (isset( $rsc_settings[ $field[ 'field_name' ] ] ) ? stripslashes( esc_attr( $rsc_settings[ $field[ 'field_name' ] ] ) ) : (isset( $field[ 'default_value' ] ) ? stripslashes( esc_attr( $field[ 'default_value' ] ) ) : '') ) ?>" <?php echo isset( $field[ 'required' ] ) ? 'required' : ''; ?> <?php echo isset( $field[ 'number' ] ) ? 'number="true"' : ''; ?> <?php echo isset( $field[ 'minlength' ] ) ? 'minlength="' . (int) $field[ 'minlength' ] . '"' : ''; ?> <?php echo isset( $field[ 'maxlength' ] ) ? 'maxlength="' . (int) $field[ 'maxlength' ] . '"' : ''; ?> <?php echo isset( $field[ 'rangelength' ] ) ? 'rangelength="' . (int) $field[ 'rangelength' ] . '"' : ''; ?> <?php echo isset( $field[ 'min' ] ) ? 'min="' . (int) $field[ 'min' ] . '"' : ''; ?> <?php echo isset( $field[ 'max' ] ) ? 'max="' . (int) $field[ 'max' ] . '"' : ''; ?> <?php echo isset( $field[ 'range' ] ) ? 'range="' . $field[ 'range' ] . '"' : ''; ?>>
            <span class="description"><?php echo stripslashes( isset( $field[ 'field_description' ] ) ? $field[ 'field_description' ] : ''  ); ?></span>
			<?php
		}

        /**
         * Render file text fields
         *
         * @param $field
         * @param $key
         */
		function field_file( $field, $key ) {
			$rsc_settings = get_option( esc_attr( $key ), false ); ?>
			<input class="file_url <?php echo esc_attr( isset( $field[ 'field_class' ] ) ? $field[ 'field_class' ] : ''  ); ?>" type="text" name="<?php echo esc_attr( $key ); ?>[<?php echo esc_attr( $field[ 'field_name' ] ); ?>]" value="<?php echo (isset( $rsc_settings[ $field[ 'field_name' ] ] ) ? stripslashes( $rsc_settings[ $field[ 'field_name' ] ] ) : (isset( $field[ 'default_value' ] ) ? stripslashes( $field[ 'default_value' ] ) : '') ); ?>" />
			<input class="file_url_button button-secondary" type="button" value="<?php _e( 'Browse', 'tc' ); ?>" />
			<span class="description"><?php echo stripslashes( isset( $field[ 'field_description' ] ) ? $field[ 'field_description' ] : ''  ); ?></span>
			<?php
		}

        /**
         * Render date text fields
         *
         * @param $field
         * @param $key
         */
		function field_date( $field, $key ) {
			$rsc_settings = get_option( esc_attr( $key ), false );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' ); ?>
			<input type="text" id="<?php echo esc_attr( $field[ 'field_name' ] ); ?>" name="<?php echo esc_attr( $field[ 'field_name' ] ); ?>" value="" />
			<input type="hidden" name="<?php echo esc_attr( $field[ 'field_name' ] ); ?>_raw" id="<?php echo esc_attr( $field[ 'field_name' ] ); ?>_raw" value="" />
			<span class="description"><?php echo ( $field[ 'field_description' ] ); ?></span>
			<script>
				jQuery( document ).ready( function ( $ ) {
					jQuery( '#<?php echo esc_attr( $field[ 'field_name' ] ); ?>' ).datepicker( {
						dateFormat: '<?php echo isset( $field[ 'date_format' ] ) ? rsc_sanitize_string( $field[ 'date_format' ] ) : 'dd-mm-yy'; ?>',
						onSelect: function ( dateText, inst ) {
							jQuery( '#<?php echo esc_attr( $field[ 'field_name' ] ); ?>_raw' ).val( inst.selectedYear + '-' + inv_leading_zeros( inst.selectedMonth ) + '-' + inv_leading_zeros( inst.selectedDay ) );
						}
					} );

					var current_value = jQuery( "#<?php echo esc_attr( $field[ 'field_name' ] ); ?>" ).val();

					if ( !current_value ) {
						jQuery( '#<?php echo esc_attr( $field[ 'field_name' ] ); ?>' ).datepicker( "setDate", 15 );
					}

				} );
			</script>
			<?php
		}

        /**
         * Render textarea fields
         *
         * @param $field
         * @param $key
         */
		public static function field_textarea( $field, $key ) {
			$rsc_settings = get_option( esc_attr( $key ), false ); ?>
			<textarea class="<?php echo esc_attr( $field[ 'field_name' ] ); ?> <?php echo esc_attr( isset( $field[ 'field_class' ] ) ? $field[ 'field_class' ] : ''  ); ?>" name="<?php echo esc_attr( $key ); ?>[<?php echo esc_attr( $field[ 'field_name' ] ); ?>]"><?php echo (isset( $rsc_settings[ $field[ 'field_name' ] ] ) ? stripslashes( $rsc_settings[ $field[ 'field_name' ] ] ) : (isset( $field[ 'default_value' ] ) ? stripslashes( $field[ 'default_value' ] ) : '') ) ?></textarea>
			<span class="description"><?php echo stripslashes( isset( $field[ 'field_description' ] ) ? $field[ 'field_description' ] : ''  ); ?></span><?php
		}

        /**
         * Render wp_editor fields
         *
         * @param $field
         * @param $key
         */
		public static function field_wp_editor( $field, $key ) {

			$rsc_settings = get_option( esc_attr( $key ), false );
			$saved_value = isset( $rsc_settings[ $field[ 'field_name' ] ] ) ? $rsc_settings[ $field[ 'field_name' ] ] : '';

			if ( $saved_value == '' && $field[ 'default_value' ] !== '' ) {
				$saved_value = $field[ 'default_value' ];
			} ?>
			<?php wp_editor( html_entity_decode( stripcslashes( esc_textarea( $saved_value ) ) ), 'inv_wp_editor_' . $field[ 'field_name' ], array( 'textarea_name' => esc_attr( $key . '[' . $field[ 'field_name' ] . ']' ), 'textarea_rows' => 2 ) ); ?>
			<br /><span class="description"><?php echo ( isset( $field[ 'field_description' ] ) ? $field[ 'field_description' ] : '' ); ?></span><?php
		}

        /**
         * Render radio fields
         *
         * @param $field
         * @param $key
         */
		public static function field_radio( $field, $key ) {
			$rsc_settings = get_option( esc_attr( $key ), false );
			$saved_value = isset( $rsc_settings[ $field[ 'field_name' ] ] ) ? $rsc_settings[ $field[ 'field_name' ] ] : '';
			if ( $saved_value == '' && $field[ 'default_value' ] !== '' ) {
				$saved_value = $field[ 'default_value' ];
			}

			foreach ( $field[ 'values' ] as $key => $value ) { ?>
				<input type="radio" class="<?php echo esc_attr( $field[ 'field_name' ] ); ?> <?php echo esc_attr( isset( $field[ 'field_class' ] ) ? $field[ 'field_class' ] : ''  ); ?>" name="<?php echo esc_attr( $key ); ?>[<?php echo esc_attr( $field[ 'field_name' ] ); ?>]" value="<?php echo stripslashes( esc_attr( $key ) ); ?>" <?php checked( $key, $saved_value, true ); ?> /> <?php echo rsc_esc_html( $value ); ?><?php
			} ?>
			<br /><span class="description"><?php echo stripslashes( isset( $field[ 'field_description' ] ) ? $field[ 'field_description' ] : ''  ); ?></span><?php
		}

        /**
         * Render checkbox fields
         *
         * @param $field
         * @param $key
         */
		public static function field_select( $field, $key ) {
			$rsc_settings = get_option( esc_attr( $key ), false );
			$saved_value = isset( $rsc_settings[ $field[ 'field_name' ] ] ) ? $rsc_settings[ $field[ 'field_name' ] ] : '';

			if ( $saved_value == '' && $field[ 'default_value' ] !== '' ) {
				$saved_value = $field[ 'default_value' ];
			} ?>
			<select name="<?php echo esc_attr( $key ); ?>[<?php echo esc_attr( $field[ 'field_name' ] ); ?>]" class="<?php echo esc_attr( $field[ 'field_name' ] ); ?> <?php echo esc_attr( isset( $field[ 'field_class' ] ) ? $field[ 'field_class' ] : ''  ); ?>"><?php
				foreach ( $field[ 'values' ] as $key => $value ) { ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $saved_value, true ); ?>><?php echo rsc_esc_html( $value ); ?></option><?php
				} ?>
			</select>
			<br />
            <span class="description"><?php echo stripslashes( isset( $field[ 'field_description' ] ) ? $field[ 'field_description' ] : ''  ); ?></span><?php
		}
	}
}
?>
