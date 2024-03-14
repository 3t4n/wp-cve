<?php namespace MSMoMDP\Wp;

use MSMoMDP\Std\Core\Arr;
use MSMoMDP\Std\Html\Html;
use MSMoMDP\Std\Html\Element;


class Settings {

	public static function append_to_option( string $optionId, string $valueToAppend ) {
		$currentVal = get_option( $optionId );
		if ( $currentVal != false ) {

			update_option( $optionId, $currentVal . $valueToAppend );
		}
	}

	public static function update_setting_array( string $optionId, string $optionSubFieldId, $value, bool $createIfNotExist = false ) : bool {
		$options = get_option( $optionId, null );
		if ( ! isset( $options ) && $createIfNotExist ) {
			$options = array();
		}
		if ( isset( $options ) && ( array_key_exists( $optionSubFieldId, $options ) || $createIfNotExist ) ) {
			$options[ $optionSubFieldId ] = $value;
			return update_option( $optionId, $options );
		}
		return false;
	}

	public static function get_setting_array_field( string $optionId, string $optionSubFieldId, $def = '' ) {
		$options = get_option( $optionId );
		if ( $options && array_key_exists( $optionSubFieldId, $options ) ) {
			return $options[ $optionSubFieldId ];
		}
		return $def;
	}

	public static function get_user_data( $key, $sub_key, $user_id, $def ) {
		$specific_options = self::get_setting_array_field( $key, $sub_key, array() );
		return Arr::get( $specific_options, $user_id, $def );
	}

	public static function update_user_data( $key, $sub_key, $user_id, $val ) {
		 $specific_options            = self::get_setting_array_field( $key, $sub_key, array() );
		$specific_options[ $user_id ] = $val;
		self::update_setting_array( $key, $sub_key, $specific_options, true );
	}

	public static function delete_user_data( $key, $sub_key, $user_id = null ) {
		if ( ! $user_id ) {
			$options = get_option( $key, array() );
			if ( array_key_exists( $sub_key, $options ) ) {
				$options[ $sub_key ] = array();
				update_option( $key, $options );
			}
		} else {
			self::update_user_data( $key, $sub_key, $user_id, null );
		}
	}

	public static function first_init_for_public( $optionId, $optionConfig ) {
		$options = get_option( $optionId, null );
		if ( is_null( $options ) || ( \is_array( $options ) && ! \count( $options ) ) ) {
			$val = array();
			foreach ( $optionConfig as $item ) {
				$val[ $item['id'] ] = $item['defVal'] ?? '';
			}
			add_option( $optionId, $val );
		}
	}

	public static function init_setting_array( string $optionId, string $sectionId, string $sectionTitle,
		string $page, array $fields = array(), $renderDescriptionClb = null, bool $cleanNotSupportedFields = true ) {
		$options = get_option( $optionId, null );
		if ( is_null( $options ) ) {
			if ( add_option( $optionId, array() ) ) {
				$options = get_option( $optionId );
			}
		}
		if ( ! is_array( $options ) ) {
			if ( delete_option( $optionId ) ) {
				if ( add_option( $optionId, array() ) ) {
					$options = get_option( $optionId );
				}
			}
		}
		self::add_settings_section( $sectionId, $sectionTitle, $options, $page, $fields, $renderDescriptionClb, $cleanNotSupportedFields, $optionId );

		register_setting(
			$optionId,
			$optionId
		);
	}

	public static function add_settings_section( string $sectionId, string $title, array $options,
		string $page, array $fields = array(), $renderDescriptionClb = null, bool $cleanNotSupportedFields = true, string $optionId = '' ) {
		add_settings_section(
			$sectionId,                     // ID used to identify this section and with which to register options
			__( $title, 'gg-monarch-sidebar-minimized-on-mobile' ),        // Title to be displayed on the administration page
			$renderDescriptionClb,
			$page
		);
		if ( $cleanNotSupportedFields && $optionId ) {
			$ids = array();
			foreach ( $fields as $field ) {
				$ids[] = $field['id'];
			}
			$filteredOptions = array();
			foreach ( $options as $k => $v ) {
				if ( in_array( $k, $ids ) ) {
					$filteredOptions[ $k ] = $v;
				}
			}
			if ( count( $filteredOptions ) < count( $options ) ) {
				$options = $filteredOptions;
				update_option( $optionId, $options );
			}
		}
		foreach ( $fields as $field ) {
			self::add_settings_field(
				$field['id'],
				Arr::sget( $field, 'title', $field['id'] ),
				Arr::sget( $field, 'defVal', '' ),
				$page,
				$sectionId,
				$options,
				Arr::sget( $field, 'renderArgs', array() ),
				$optionId
			);

		}
	}

	public static function add_settings_field( string $id, string $title, string $defVal, string $page,
		string $section, array $options, array $renderArgs = array(), string $optionId = null ) {
		if ( ! array_key_exists( $id, $options ) ) {
			$options[ $id ] = $defVal;
			if ( ! empty( $optionId ) ) {
				update_option( $optionId, $options );
			}
		}
		if ( ! array_key_exists( 'placeholder', $renderArgs ) ) {
			$renderArgs['placeholder'] = $defVal;
		}
		$args = array_merge(
			array(
				'page'  => $page,
				'value' => $options[ $id ],
				'name'  => $id,
			),
			$renderArgs
		);

		if ( ! Arr::get( $renderArgs, 'hidden', null ) ) {
			add_settings_field(
				$id,                                // ID used to identify the field throughout the theme
				$title,            // The label to the left of the option interface element
				'MSMoMDP\Wp\Settings::render_option',            // The name of the function responsible for rendering the option interface
				$page,                              // The page on which this option will be displayed
				$section,
				$args
			);
		}

	}

	public static function render_hint( $hint, $proOnly, $disabled, $readonly ) {
		if ( $hint ) {
			$classes_hint = array( 'dp-settings__el__hint' );
			if ( $proOnly ) {
				$classes_hint[] = 'dpit-pro-only';
			}
			Html::render(
				'span',
				$classes_hint,
				array(
					'margin-right' => '20px',
					'font-style'   => 'italic',
					'font-weight'  => '300',
					'font-size'    => '12px',
				),
				$hint,
				array(
					'disabled' => $disabled ? 'disabled' : null,
					'readonly' => $readonly ? 'readonly' : null,
				)
			);
		}
	}


	public static function render_option( array $args ) {
		$page = $args['page'];
		$name = $args['name'];

		$size        = Arr::sget( $args, 'size', 52 );
		$type        = Arr::sget( $args, 'type', 'text' );
		$value       = Arr::sget( $args, 'value', '' );
		$placeholder = Arr::sget( $args, 'placeholder', '' );
		$hidden      = Arr::sget( $args, 'hidden', false );
		$disabled    = Arr::sget( $args, 'disabled', '0' ) == '1';
		$readonly    = Arr::sget( $args, 'readonly', '0' ) == '1';
		$proOnly     = Arr::sget( $args, 'pro_only', '0' ) == '1';
		$hint        = Arr::get( $args, 'hint' );
		$style       = array();
		if ( $hint ) {
			$style = array(
				'margin-right' => '10px',
			);
		}
		$classes = array( 'dp-settings__el' );
		if ( $proOnly ) {
			$readonly  = true;
			$classes[] = 'dpit-pro-only';
		}

		if ( $type === 'color' ) {
			$type      = 'text';
			$classes[] = 'dp-color-picker-field';

		}

		if ( ! $hidden ) {
			$id = implode( '-', array( $page, $type, $name ) );
			switch ( $type ) {
				case 'text':
					$inputType = Arr::sget( $args, 'inputType', 'text' );
					$attrs     = array(
						'type'        => $inputType,
						'id'          => $id,
						'name'        => $page . '[' . $name . ']',
						'value'       => $value,
						'size'        => $size,
						'placeholder' => $placeholder,
						'disabled'    => $disabled ? 'disabled' : null,
						'readonly'    => $readonly ? 'readonly' : null,
					);
					if ( $inputType === 'number' ) {
						$min = Arr::get( $args, 'min' );
						if ( $min || $min === 0 || $min === '0' ) {
							$attrs['min'] = $min;
						}
						$max = Arr::get( $args, 'max' );
						if ( $max || $max === 0 || $max === '0' ) {
							$attrs['max'] = $max;
						}
					}
					Html::render( 'input', $classes, $style, null, $attrs );
					break;
				case 'checkbox':
					Html::render(
						'input',
						$classes,
						$style,
						null,
						array(
							'type'        => 'checkbox',
							'id'          => $id,
							'name'        => $page . '[' . $name . ']',
							'value'       => '1',
							checked( 1, $value, false ),
							'disabled'    => $disabled ? 'disabled' : null,
							'readonly'    => $readonly ? 'readonly' : null,
							'size'        => $size,
							'placeholder' => $placeholder,
						)
					);
					break;
				case 'textarea':
					Html::render(
						'textarea',
						$classes,
						$style,
						$value,
						array(
							'id'          => $id,
							'name'        => $page . '[' . $name . ']',
							'cols'        => $size,
							'rows'        => Arr::sget( $args, 'rowCnt', 5 ),
							'placeholder' => $placeholder,
							'disabled'    => $disabled ? 'disabled' : null,
							'readonly'    => $readonly ? 'readonly' : null,
						//'$1' => 'readonly'
						)
					);
					break;
				case 'action':
						$action = Arr::sget( $args, 'name', '' );
						Html::render(
							'div',
							'button dp-intro-admin-edit-btn dp-intro-admin-edit-btn--settings',
							null,
							Html::get_str( 'span', 'dp-intro-admin-edit-btn__text', null, Arr::sget( $args, 'title', $action ) ),
							array(
								'data-msg-success' => Arr::sget( $args, 'msg_success', '' ),
								'data-msg-failed'  => Arr::sget( $args, 'msg_failed', '' ),
								'data-orig-title'  => Arr::sget( $args, 'title', $action ),
								'id'               => 'dpit_action_' . $action,
								'data-action'      => $action,
							)
						);
					break;
				case 'select':
					$options     = Arr::get( $args, 'options', array() );
					$optionsHtml = array();
					foreach ( $options as $option ) {
						$valAndLabel = explode( ':', $option );
						if ( count( $valAndLabel ) ) {
							$optionVal   = $valAndLabel[0];
							$optionLabel = $optionVal;
							if ( count( $valAndLabel ) > 1 ) {
								$optionLabel = $valAndLabel[1];
							}
							$optionsHtml[] = '<option value="' . $optionVal . '" ' . selected( $value, $optionVal, false ) . '>' . $optionLabel . '</option>';
						}
					}
					$select = new Element(
						'select',
						null,
						array(
							'id'          => $id,
							'class'       => $classes,
							'name'        => $page . '[' . $name . ']',
							'placeholder' => $placeholder,
							'disabled'    => $disabled ? 'disabled' : null,
							'readonly'    => $readonly ? 'readonly' : null,
							'style'       => $style,
						),
						$optionsHtml
					);
					$select->render();
					break;
			}
			self::render_hint( $hint, $proOnly, $disabled, $readonly );
		}
	}
}
