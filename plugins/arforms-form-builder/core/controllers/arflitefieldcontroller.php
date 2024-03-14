<?php

class arflitefieldcontroller {


	function __construct() {

		add_filter( 'arflitedisplayfieldhtml', array( $this, 'arflitedisplayfieldhtml' ), 10, 2 );

		add_filter( 'arflitefieldtype', array( $this, 'arflite_change_type' ), 15, 2 );

		add_filter( 'arflitedisplaysavedfieldvalue', array( $this, 'arflite_use_field_key_value' ), 10, 3 );

		add_action( 'arflitedisplayaddedfields', array( $this, 'arfliteshow' ) );

		add_filter( 'arflitedisplayfieldoptions', array( $this, 'arflite_display_field_options' ) );

		add_filter( 'arflitedisplayfieldoptions', array( $this, 'arflite_display_basic_field_options' ) );

		add_action( 'arflitefieldinputhtml', array( $this, 'arflite_input_html' ) );

		add_action( 'arflitefieldinputhtml', array( $this, 'arflite_input_fieldhtml' ) );

		add_filter( 'arfliteaddfieldclasses', array( $this, 'arflite_add_field_class' ), 20, 2 );

		add_action( 'wp_ajax_arflite_is_prevalidateform_outside', array( $this, 'arflite_prevalidateform_outside' ) );

		add_action( 'wp_ajax_nopriv_arflite_is_prevalidateform_outside', array( $this, 'arflite_prevalidateform_outside' ) );

		add_action( 'wp_ajax_arflite_is_resetformoutside', array( $this, 'arflite_resetformoutside' ) );

		add_action( 'wp_ajax_nopriv_arflite_is_resetformoutside', array( $this, 'arflite_resetformoutside' ) );

		add_action( 'wp_ajax_arflite_add_new_preset', array( $this, 'arflite_add_new_preset' ) );

		add_action( 'wp_ajax_arflite_upload_radio_label_img', array( $this, 'arflite_upload_radio_label_img' ) );

		add_action( 'wp_ajax_arflite_save_new_preset_field', array( $this, 'arflite_save_new_preset_field_function' ) );

		add_action( 'wp_ajax_arflite_get_field_data_dynamic', array( $this, 'arflite_get_field_data_dynamic' ) );

	}

	function arflitedisplayfieldhtml( $show, $field_type ) {

		if ( in_array( $field_type, array( 'hidden', 'user_id', 'html' ) ) ) {
			$show = false;
		}

		return $show;
	}

	function arflite_change_type( $type, $field ) {

		global $arfliteshowfields;

		if ( $type != 'user_id' && ! empty( $arfliteshowfields ) && ! in_array( $field->id, $arfliteshowfields ) && ! in_array( $field->field_key, $arfliteshowfields ) ) {
			$type = 'hidden';
		}

		if ( $type == 'website' ) {
			$type = 'url';
		}

		return $type;
	}

	function arflite_use_field_key_value( $opt, $opt_key, $field ) {

		if ( ( isset( $field['use_key'] ) && $field['use_key'] ) || ( isset( $field['type'] ) && $field['type'] == 'data' ) ) {
			$opt = $opt_key;
		}

		return $opt;
	}

	function arfliteshow( $field ) {

		global $arfliteajaxurl;

		$field_name = 'item_meta[' . $field['id'] . ']';

		include ARFLITE_VIEWS_PATH . '/arflite_displayfield.php';
	}

	function arflite_display_field_options( $display ) {

		if ( isset( $display['type'] ) && $display['type'] != '' ) {

			switch ( $display['type'] ) {

				case 'user_id':
				case 'hidden':
					$display['label_position'] = false;

					$display['description'] = false;

				case 'form':
					$display['required'] = false;

					$display['default_blank'] = false;

					break;

				case 'email':
				case 'url':
				case 'website':
				case 'phone':
				case 'image':
				case 'date':
				case 'number':
					$display['size'] = true;

					$display['invalid'] = true;

					$display['clear_on_focus'] = true;

					break;

				case 'time':
					   $display['size'] = true;

					break;

				case 'html':
					$display['label_position'] = false;

					$display['description'] = false;

			}
		}

		return $display;
	}

	function arflite_display_basic_field_options( $display ) {

		if ( isset( $display['type'] ) && $display['type'] != '' ) {

			switch ( $display['type'] ) {

				case 'captcha':
					$display['required'] = false;

					$display['invalid'] = true;

					$display['default_blank'] = false;

					break;

				case 'radio':
					$display['default_blank'] = false;

					break;

				case 'text':
				case 'textarea':
					   $display['size'] = true;

					   $display['clear_on_focus'] = true;

					break;

				case 'select':
					$display['size'] = true;

					break;
			}
		}

		return $display;
	}

	function arflite_input_html( $field, $echo = true ) {

		global $arformsmain, $arflitenovalidate;

		$add_html = '';

		if ( isset( $field['read_only'] ) && $field['read_only'] ) {

			global $arflitereadonly;

			if ( $arflitereadonly == 'disabled' || ( current_user_can( 'administrator' ) && is_admin() ) ) {
				return;
			}

			$add_html .= ' readonly="readonly" ';
		}

		if ( isset( $field['max'] ) && $field['max'] != '' && 0 < $field['max'] ) {
			$add_html .= ' maxlength="' . $field['max'] . '" ';
			if ( $field['type'] == 'textarea' ) {
				$add_html .= ' class="arf_text_is_countable" ';
			}
		}

		$use_html = $arformsmain->arforms_get_settings('use_html','general_settings');
		$use_html = !empty( $use_html ) ? $use_html : true;

		if ( $use_html ) {

			if ( $field['type'] == 'number' ) {

				if ( $field['maxnum'] != '' && ! is_numeric( $field['minnum'] ) ) {
					$field['minnum'] = 0;
				}

				if ( $field['maxnum'] != '' && ! is_numeric( $field['maxnum'] ) ) {
					$field['maxnum'] = 9999999;
				}

				if ( isset( $field['step'] ) && ! is_numeric( $field['step'] ) ) {
					$field['step'] = 1;
				}

				if ( $field['maxnum'] > 0 ) {
					$add_html .= ' max="' . $field['maxnum'] . '"';
				}

				if ( $field['minnum'] > 0 ) {
					$add_html .= ' min="' . $field['minnum'] . '"';
				}
			} elseif ( in_array( $field['type'], array( 'url', 'email' ) ) ) {

				if ( ! isset( $field['default_value'] ) ) {
					$field['default_value'] = isset( $field['field_options']['default_value'] ) ? $field['field_options']['default_value'] : '';
				}
				if ( ! $arflitenovalidate && isset( $field['value'] ) && $field['default_value'] == $field['value'] ) {
					$arflitenovalidate = true;
				}
			}
		}

		if ( isset( $field['dependent_fields'] ) && $field['dependent_fields'] ) {

			$trigger = ( $field['type'] == 'checkbox' || $field['type'] == 'radio' ) ? 'onclick' : 'onchange';

			$add_html .= ' ' . $trigger . '="frmCheckDependent(this.value,\'' . $field['id'] . '\')"';
		}

		if ( $echo ) {
			$allowed_html_arr = arflite_retrieve_attrs_for_wp_kses();
			echo wp_kses( $add_html, $allowed_html_arr );
		}

		return $add_html;
	}

	function arflite_add_field_class( $class, $field ) {

		if ( $field['type'] == 'date' ) {
			$class .= 'frm_date';
		}

		return $class;
	}

	function arflite_input_fieldhtml( $field, $echo = true ) {
		global $arflitemainhelper;

		$class    = '';
		$add_html = '';

		if ( $field['type'] == 'date' || $field['type'] == 'phone' ) {
			$field['size'] = '';
		}

		if ( isset( $field['max'] ) && $field['max'] != '' && 0 < $field['max'] ) {
			$add_html .= ' maxlength="' . $field['max'] . '" ';
		}

		if ( isset( $field['minlength'] ) && $field['minlength'] != '' && 0 < $field['minlength'] ) {
			$add_html .= ' minlength="' . $field['minlength'] . '" ';
			if ( $field['type'] == 'phone' || $field['type'] == 'tel' ) {
				$add_html .= ' data-validation-minlength-message="' . $field['invalid'] . '" ';
			}
		}
		if ( isset( $field['size'] ) && $field['size'] > 0 ) {

			if ( ! in_array( $field['type'], array( 'textarea', 'select', 'data', 'time' ) ) ) {
				$add_html .= ' size="' . $field['size'] . '"';
			}

			$class .= ' auto_width';
		}

		if ( ! is_admin() || ! isset( $_GET ) || ! isset( $_GET['page'] ) || sanitize_text_field( $_GET['page'] ) == 'ARForms_entries' ) {

			$action = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';

			$action = $arflitemainhelper->arflite_get_param( $action );

			if ( isset( $field['required'] ) && $field['required'] ) {

				if ( $field['type'] == 'select' ) {
					$class .= 'select_controll_' . $field['id'] . ' arf_required arf_select_controll';
				} elseif ( $field['type'] == 'time' ) {
					$class .= 'time_controll_' . $field['id'] . ' arf_required ';
				} else {
					if ( $field['type'] == 'textarea' && $field['required'] == 1 && isset( $field['max'] ) && $field['max'] != '' ) {
						$class .= ' arf_text_is_countable arf_required ';
					} elseif ( $field['type'] == 'textarea' && $field['required'] != 1 && isset( $field['max'] ) && $field['max'] != '' ) {
						$class .= ' arf_text_is_countable';
					} else {
						$class .= ' arf_required ';
					}
				}
			}

			if ( $field['type'] == 'phone' && isset( $field['phonetype'] ) && $field['phonetype'] == 1 ) {
				$class .= ' arf_phone_utils ';
			}

			if ( isset( $field['clear_on_focus'] ) && $field['clear_on_focus'] && ! empty( $field['default_value'] ) ) {

				$val = esc_attr( $field['default_value'] );

				$add_html .= ' onfocus="arflitecleardedaultvalueonfocus(' . "'" . $val . "'" . ',this,' . "'" . $field['default_blank'] . "'" . ')" onblur="arflitereplacededaultvalueonfocus(' . "'" . $val . "'" . ',this,' . "'" . $field['default_blank'] . "'" . ')" placeholder="' . $val . '"';

				if ( $field['value'] == $field['default_value'] ) {
					$class .= ' arfdefault';
				}
			}
		}

		if ( isset( $field['input_class'] ) && ! empty( $field['input_class'] ) ) {
			$class .= ' ' . $field['input_class'];
		}

		$class = apply_filters( 'arfliteaddfieldclasses', $class, $field );

		if ( ! empty( $class ) ) {
			$add_html .= ' class="' . $class . '"';
		}

		if ( isset( $field['shortcodes'] ) && ! empty( $field['shortcodes'] ) ) {

			foreach ( $field['shortcodes'] as $k => $v ) {

				$add_html .= ' ' . $k . '="' . $v . '"';

				unset( $k );

				unset( $v );
			}
		}

		if ( $echo ) {
			$allowed_html_arr = arflite_retrieve_attrs_for_wp_kses();
			echo wp_kses( $add_html, $allowed_html_arr );
		}

		return $add_html;
	}

	function arflite_prevalidateform_outside() {

		if ( !isset( $_POST['token'] ) || ( isset( $_POST['token'] ) && '' != $_POST['token'] && ! wp_verify_nonce( sanitize_text_field( $_POST['token'] ), 'arflite_validate_outside_nonce' ) ) ) {
			echo 0;
			die;
		}

		$form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;

		$arf_errors = array();

		$arf_form_data = array();

		$values = $_POST;

		$arf_form_data = apply_filters( 'arflite_populate_field_from_outside', $arf_form_data, $form_id, $values );

		$arf_errors = apply_filters( 'arflite_validate_form_outside_errors', $arf_errors, $form_id, $values, $arf_form_data );

		if ( isset( $arf_errors['arf_form_data'] ) ) {
			$arf_form_data = array_merge( $arf_form_data, $arf_errors['arf_form_data'] );
		}

		unset( $arf_errors['arf_form_data'] );

		if ( count( $arf_form_data ) > 0 ) {
			echo esc_attr( '^arf_populate=' );
			foreach ( $arf_form_data as $field_id => $field_value ) {
				echo esc_attr( $field_id . '^|^' . $field_value . '~|~' );
			}
			echo esc_attr( '^arf_populate=' );
		}

		if ( count( $arf_errors ) > 0 ) {
			foreach ( $arf_errors as $field_id => $error ) {
				echo esc_attr( $field_id . '^|^' . $error . '~|~' );
			}
		} else {
			echo esc_attr( 0 );
		}

		die();
	}

	function arflite_resetformoutside() {
		global $arfliteform, $arflitefieldhelper;

		if ( !isset( $_POST['token'] ) || ( isset( $_POST['token'] ) && '' != $_POST['token'] && ! wp_verify_nonce( sanitize_text_field( $_POST['token'] ), 'arflite_reset_form_outside_nonce' ) ) ) {
			$returnarr                = array();
			$returnarr['conf_method'] = 'spamerror';
			$returnarr['message']     = __( 'Sorry, the action "Reset Form Outside" could not be processed due to security reason', 'arforms-form-builder' );
			$return                   = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST );
			echo wp_json_encode( $returnarr );
			die;
		}

		$form_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : 0;

		$arf_form_data = array();

		$form = $arfliteform->arflitegetOne( (int) $form_id );

		$fields = $arflitefieldhelper->arflite_get_form_fields_tmp( false, $form->id, false, 0 );

		$values = $arfliterecordhelper->arflite_setup_new_vars( $fields, $form );

		$arf_form_data = apply_filters( 'arflite_populate_field_after_from_submit', $arf_form_data, $form_id, $values, $form );

		if ( count( $arf_form_data ) > 0 ) {
			$arferr = array();
			foreach ( $arf_form_data as $field_id => $field_value ) {
				$arferr[ $fieldid ] = $fieldvalue;
			}
			$return['conf_method'] = 'validationerror';
			$return['message']     = $arferr;
			$return                = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST );
			echo json_encode( $return );
			exit;
		}

		die();
	}

	function arflite_add_new_preset() {

		$fn = isset( $_SERVER['HTTP_X_FILENAME'] ) ? sanitize_file_name( $_SERVER['HTTP_X_FILENAME'] ) : false;

		if ( $fn && isset( $_FILES['preset_file']['tmp_name'] ) ) {

			$upload_main_url = ARFLITE_UPLOAD_PRESET_FILE_DIR;

			$arflitefilecontroller = new arflitefilecontroller( $_FILES['preset_file'], false ); //phpcs:ignore

			$arflitefilecontroller->default_error_msg = __( 'Please select a CSV file', 'arforms-form-builder' );

			if ( ! $arflitefilecontroller ) {
				echo 'error~|~' . esc_html__( 'Please select a CSV file', 'arforms-form-builder' );
				die;
			}

			$arflitefilecontroller->check_cap    = true;
			$arflitefilecontroller->capabilities = array( 'arfviewforms', 'arfeditforms', 'arfchangesettings' );

			$arflitefilecontroller->check_nonce  = true;
			$arflitefilecontroller->nonce_data   = isset( $_POST['_nonce_add_preset'] ) ? sanitize_text_field( $_POST['_nonce_add_preset'] ) : ''; //phpcs:ignore
			$arflitefilecontroller->nonce_action = 'arflite_wp_preset_nonce';

			$arflitefilecontroller->check_only_image = false;

			$arflitefilecontroller->check_specific_ext = true;
			$arflitefilecontroller->allowed_ext        = array( 'csv' );

			$destination = $upload_main_url . $fn;

			$upload_file = $arflitefilecontroller->arflite_process_upload( $destination );

			if ( false == $upload_file ) {
				echo 'error~|~' . $arflitefilecontroller->error_message; //phpcs:ignore
				die;
			} else {
				echo esc_html( $fn );
				die;
			}
		} else {
			echo 'error~|~' . esc_html__( 'Please select a CSV file', 'arforms-form-builder' );
			die;
		}
	}

	function arflite_save_new_preset_field_function() {

		if ( !isset( $_POST['_wpnonce_arflite'] ) || ( isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' ) ) ) {
			echo esc_attr( 'error' );
			die;
		}

		$fn                    = isset( $_POST['file_name'] ) ? sanitize_file_name( $_POST['file_name'] ) : '';
		$arf_preset_future_use = isset( $_POST['arf_save_preset_for_future'] ) ? sanitize_text_field( $_POST['arf_save_preset_for_future'] ) : false;

		$arf_preset_title = isset( $_POST['arf_preset_title'] ) ? sanitize_text_field( $_POST['arf_preset_title'] ) : '';
		$upload_main_url  = ARFLITE_UPLOAD_PRESET_FILE_DIR;

		if ( $fn != '' ) {
			$file = $upload_main_url . $fn;

			if ( ! file_exists( $file ) ) {
				echo esc_attr( 'error' );
				die;
			}

			$csv_data = array();
			ini_set( 'auto_detect_line_endings', true );

			$fh = fopen( $file, 'r' );

			if ( false == $fh ) {
				echo esc_attr( 'error' );
				die;
			}

			$i = 0;

			$csv_length = 0;
			while ( ( $line = fgetcsv( $fh, 1000, "\t" ) ) !== false ) {
				$csv_data[] = $line;
				$i++;
			}

			$preset_data_array          = array();
			$preset_data_array['title'] = $arf_preset_title;
			$data_value                 = '';

			if ( is_array( $csv_data ) && count( $csv_data ) > 0 && $csv_data[0][0] != '' ) {
				$k = 0;
				foreach ( $csv_data as $data ) {
					if ( $data[0] != '' ) {
						$preset_data_array['data'][ $k ]['label'] = $data[0];
						$data[0]                                  = str_replace( '"', "'", $data[0] );

						if ( isset( $data[1] ) && $data[1] != '' ) {
							$data_value                              .= '"' . htmlspecialchars( $data[0], ENT_QUOTES, 'UTF-8' ) . '|' . htmlspecialchars( str_replace( '"', "'", $data[1] ), ENT_QUOTES, 'UTF-8' ) . '",';
							$preset_data_array['data'][ $k ]['value'] = htmlspecialchars( str_replace( '"', "'", $data[1] ), ENT_QUOTES, 'UTF-8' );
						} else {
							$data_value                              .= '"' . htmlspecialchars( $data[0], ENT_QUOTES, 'UTF-8' ) . '",';
							$preset_data_array['data'][ $k ]['value'] = htmlspecialchars( str_replace( '"', "'", $data[0] ), ENT_QUOTES, 'UTF-8' );
						}
						$k++;
					}
				}
				if ( $arf_preset_future_use == true && isset( $preset_data_array['data'] ) ) {

					$arf_preset_values = ( get_option( 'arflite_preset_values' ) != '' ) ? maybe_unserialize( get_option( 'arflite_preset_values' ) ) : '';

					if ( ! is_array( $arf_preset_values ) || $arf_preset_values == '' ) {
						$arf_preset_values = array();
					}
					array_push( $arf_preset_values, $preset_data_array );
					$arf_preset_values = isset( $arf_preset_values ) ? $arf_preset_values : array();
					update_option( 'arflite_preset_values', $arf_preset_values );

					$data_value = substr( $data_value, 0, -1 );
					echo '<li class="arf_selectbox_option" data-label="' . esc_attr( htmlspecialchars( str_replace( '"', "'", $arf_preset_title ), ENT_QUOTES, 'UTF-8' ) ) . '" data-value=\'[' . esc_attr( $data_value ) . ']\'>' . esc_attr( htmlspecialchars( str_replace( '"', "'", $arf_preset_title ), ENT_QUOTES, 'UTF-8' ) ) . '</li>';
				} else {
					$data_value = substr( $data_value, 0, -1 );
					echo '<li class="arf_selectbox_option" data-label="Custom" data-value=\'[' . esc_attr( htmlspecialchars( $data_value ) ) . ']\'>' . esc_attr( __( 'Custom', 'arforms-form-builder' ) ) . '</li>';
				}
			} else {
				echo 'error';
			}
		} else {
			echo 'error';
		}
		die();
	}

	function arflite_upload_radio_label_img() {
		if ( isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' ) ) {
			echo esc_attr( 'security_error' );
			die;
		}
		$file = isset( $_POST['image'] ) ? esc_url_raw( $_POST['image'] ) : '';
		?>
		<img src="<?php echo esc_attr( $file ); ?>" class="update_img_radio_btn" />
		<?php
		die();
	}

	function arflitespecialchars( $obj ) {
		global $arfliteformcontroller;
		$newArray = array();
		$return   = array();
		if ( is_object( $obj ) ) {
			$newArray = $arfliteformcontroller->arfliteObjtoArray( $obj );
		} else {
			$newArray = $obj;
		}
		if ( is_array( $newArray ) ) {
			foreach ( $newArray as $key => $value ) {
				if ( is_array( $value ) ) {
					$return[ $key ] = array_map( array( $this, __FUNCTION__ ), $value );
				} elseif ( is_object( $value ) ) {
					$value          = $arfliteformcontroller->arfliteObjtoArray( $value );
					$return[ $key ] = array_map( array( $this, __FUNCTION__ ), $value );
				} else {
					$value          = str_replace( "'", '&#8217', $value );
					$return[ $key ] = $value;
				}
			}
		} else {
			$return = str_replace( "'", '&#8217', $newArray );
		}
		return $return;
	}

	function arflite_get_field_multicolumn_icon( $column, $arf_editor_index_row_val = '{arf_editor_index_row}' ) {
		if ( $column == '' || $column < 1 || $column > 6 ) {
			return '';
		}
		$data_value  = '';
		$function_id = '';
		$checked     = '';
		$svg_icon    = '';
		switch ( $column ) {
			case 1:
				$function_id = 'single_column';
				$data_value  = 'arf_1';
				$checked     = "checked='checked'";
				$svg_icon    = "<svg id='multicolumn_one' height='24' width='18'>" . ARFLITE_CUSTOM_COL1_ICON . '</svg>';
				break;
			case 2:
				$function_id = 'two_column';
				$data_value  = 'arf_2';
				$checked     = '';
				$svg_icon    = "<svg id='multicolumn_two' height='24' width='27'>" . ARFLITE_CUSTOM_COL2_ICON . '</svg>';
				break;
			case 3:
				$function_id = 'three_column';
				$data_value  = 'arf_3';
				$checked     = '';
				$svg_icon    = "<svg id='multicolumn_three' height='24' width='35'>" . ARFLITE_CUSTOM_COL3_ICON . '</svg>';
				break;
			case 4:
				$function_id = 'four_column';
				$data_value  = 'arf_4';
				$checked     = '';
				$svg_icon    = "<svg id='multicolumn_four' height='24' width='35'>" . ARFLITE_CUSTOM_COL4_ICON . '</svg>';
				break;
			case 5:
				$function_id = 'five_column';
				$data_value  = 'arf_5';
				$checked     = '';
				$svg_icon    = "<svg id='multicolumn_five' height='24' width='45'>" . ARFLITE_CUSTOM_COL5_ICON . '</svg>';
				break;
			case 6:
				$function_id = 'six_column';
				$data_value  = 'arf_6';
				$checked     = '';
				$svg_icon    = "<svg id='multicolumn_six' height='24' width='50'>" . ARFLITE_CUSTOM_COL6_ICON . '</svg>';
				break;
		}
		$return_func  = "<div class='arf_multicolumn_opt' id='{$function_id}' data-value='{$data_value}'>";
		$return_func .= "<input type='radio' class='rdostandard multicolfield' name='classes' onclick='arflitemakeNewSortable({$column},this);' data-id='" . esc_attr( $arf_editor_index_row_val ) . "' id='classes_" . esc_attr( $arf_editor_index_row_val . '_' . $column ) . "' {$checked} value='" . esc_attr( $data_value ) . "' />";
		$return_func .= "<label for='classes_{$arf_editor_index_row_val}_{$column}'>";
		$return_func .= "<span class='lblsubtitle_span_column'></span>";
		$return_func .= $svg_icon;
		$return_func .= '</label>';
		$return_func .= '</div>';
		return $return_func;
	}

	function arflite_get_multicolumn_expand_icon() {
		$icon = '<div class="arf_multi_column_expand_icon"><svg width="11px" height="20px"><g>' . ARFLITE_FIELD_MULTICOLUMN_EXPAND_ICON . '</g></svg></div>';
		return $icon;
	}

	function arflite_get_field_control_icons( $type = '', $field_required_cls = '', $field_id = '{arf_field_id}', $field_required = 0, $field_type = '{arf_field_type}', $form_id = '{arf_form_id}' ) {
		if ( $type == '' ) {
			return '';
		}
		$svg_icon = '';
		switch ( $type ) {
			case 'require':
				$svg_icon = "<div class='arf_field_option_icon'><a title='" . __( 'Required', 'arforms-form-builder' ) . "' data-title='" . __( 'Required', 'arforms-form-builder' ) . "' class='arf_field_option_input arf_field_icon_tooltip {$field_required_cls}' id='isrequired_{$field_id}' href='javascript:void(0)' onclick='javascript:arflitemakerequiredfieldfunction({$field_id},{$field_required},2)'><svg id='required' height='20' width='21'><g>" . ARFLITE_CUSTOM_REQUIRED_ICON . '</g></svg></a></div>';
				break;
			case 'options':
				$svg_icon = "<div  class='arf_field_option_icon arf_field_settings_icon'><a title='" . __( 'Field Settings', 'arforms-form-builder' ) . "' data-title='" . __( 'Field Settings', 'arforms-form-builder' ) . "' class='arf_field_option_input arf_field_icon_tooltip' href='javascript:void(0)' onClick=\"javascript:arfliteshowfieldoptions({$field_id},'{$field_type}');\"><svg id='fieldoption' height='20' width='20'><g>" . ARFLITE_CUSTOM_FIELDOPTION_ICON . '</g></svg></a></div>';
				break;
			case 'delete':
				$svg_icon = "<div class='arf_field_option_icon arf_field_action_iconbox'><a title='" . __( 'Delete Field', 'arforms-form-builder' ) . "' data-title='" . __( 'Delete Field', 'arforms-form-builder' ) . "' class='arf_field_option_input arf_field_icon_tooltip' data-toggle='arfmodal' href='#delete_field_message_{$field_id}' id='arf_field_delete_{$field_id}' onClick=\"arflitechangedeletemodalwidth('arfdeletemodabox', {$field_id});\"><svg id='delete' height='19' width='19'><g>" . ARFLITE_CUSTOM_DELETE_ICON . '</g></svg></a></div>';
				break;
			case 'duplicate':
				$svg_icon = "<div class='arf_field_option_icon'><a title='" . __( 'Duplicate Field', 'arforms-form-builder' ) . "' data-title='" . __( 'Duplicate Field', 'arforms-form-builder' ) . "' class='arf_field_option_input arf_field_icon_tooltip' href='javascript:void(0)' onclick=\"javascript:arfliteduplicatefield({$form_id},'{$field_type}',{$field_id},{$field_id});\"><svg id='duplicate' height='19' width='19'><g>" . ARFLITE_CUSTOM_DUPLICATE_ITEM . '</g></svg></a></div>';
				break;
			case 'move':
				$svg_icon = "<div class='arf_field_option_icon'><a title='" . __( 'Move', 'arforms-form-builder' ) . "' data-title='" . __( 'Move', 'arforms-form-builder' ) . "' class='arf_field_option_input arf_field_icon_tooltip'><svg id='moveing' height='20' width='21'><g>" . ARFLITE_CUSTOM_MOVING_ICON . '</g></svg></a></div>';
				break;
			case 'edit_options':
				$svg_icon = "<div class='arf_field_option_icon'><a title='" . __( 'Manage Options', 'arforms-form-builder' ) . "' data-title='" . __( 'Manage Options', 'arforms-form-builder' ) . "' class='arf_field_option_input arf_field_icon_tooltip arf_edit_value_option_button' data-field-id='{$field_id}' id='arf_edit_value_option_button'><svg id='edit_opt_icon' height='20' width='21'><g>" . ARFLITE_FIELD_EDIT_OPTION_ICON . '</g></svg></a></div>';
				break;
			case 'running_total_icon':
				$svg_icon = "<div class='arf_field_option_icon arf_html_running_total_icon'><a title='" . __( 'Running Total (Math Logic) is Enabled', 'arforms-form-builder' ) . "' data-title='" . __( 'Running Total (Math Logic) is Enabled', 'arforms-form-builder' ) . "' class='arf_field_option_input arf_field_icon_tooltip'><svg id='running_total_icon' height='20' width='21'><g>" . ARFLITE_FIELD_HTML_RUNNING_TOTAL_ICON . '</g></svg></a></div>';
			default:
				$svg_icon = apply_filters( 'arflite_field_option_icon_render_outside', $svg_icon );
				break;
		}
		return $svg_icon;
	}

	function arflite_get_field_data_dynamic() {

		if ( !isset( $_POST['_wpnonce_arflite'] ) || ( isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' ) ) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		$dynamic_field_key = isset( $_POST['field_key'] ) ? stripslashes( sanitize_text_field( $_POST['field_key'] ) ) : '';
		$record_count      = isset( $_POST['arf_page'] ) ? sanitize_text_field( $_POST['arf_page'] ) : '';
		$offset            = ( ( 50 * $record_count ) - 50 );
		$continue          = true;
		$destroy           = false;

		$arf_preset_values = maybe_unserialize( get_option( 'arflite_preset_values' ) );

		$csv_preset_cntr = 0;

		if ( ! empty( $arf_preset_values ) && is_array( $arf_preset_values ) ) {
			foreach ( $arf_preset_values as $key => $value ) {
				if ( '["csv_preset_' . $key . '"]' == $dynamic_field_key ) {
					$preset_data = array();

					$total_csv_data = count( $value['data'] );

					if ( ( $total_csv_data / 50 ) < $record_count ) {
						$continue = false;
						$destroy  = true;
					}

					$data_flag = 50 * $record_count;

					for ( $i = $data_flag - 50; $i < $data_flag; $i++ ) {
						$csv_preset_cntr++;

						if ( $i >= $total_csv_data ) {
							break;
						}
						$data          = $value['data'][ $i ];
						$preset_data[] = htmlspecialchars( $data['label'], ENT_QUOTES, 'UTF-8' ) . '|' . htmlspecialchars( $data['value'], ENT_QUOTES, 'UTF-8' );

					}

					$dynamic_field_data = array(
						'field_data_dynamic_arr' => $preset_data,
						'total_records'          => $total_csv_data,
						'continue'               => $continue,
					);
					echo json_encode( $dynamic_field_data );
				}
			}
		}

		die();
	}
}
?>
