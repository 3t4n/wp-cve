<?php

class AREOI_Plugins
{
	private static $initiated = false;

	public static function init() {

		if ( !self::$initiated ) {

			self::override_ninja_forms();
		}
	}

	public static function override_ninja_forms()
	{
		$override = areoi2_get_option('areoi-lightspeed-plugins-nf-styles', false );

		if ( !areoi_has_plugin( 'ninja-forms/ninja-forms.php' ) || !$override ) return;

		$settings = areoi2_get_option( 'ninja_forms_settings', null );

		if ( $settings ) {
			if ( is_array( $settings ) ) {
				$settings['opinionated_styles'] = ''; 
				update_option( 'ninja_forms_settings', $settings );
			}
		}

		add_filter( 'body_class', function( $classes ) {
			$classes[] = 'areoi-override-nf';
			return $classes;
		} );

		add_filter( 'ninja_forms_field_template_file_paths', function( $paths ) {
			$paths[] = AREOI__PLUGIN_LIGHTSPEED_DIR . 'plugins/ninja-forms/templates/';
			return $paths;
		});

		add_action( 'ninja_forms_display_fields', function( $fields = array(), $form_id = null ) {
			
			foreach ( $fields as $field_key => $field ) {
				
				$fields[$field_key]['container_class'] .= ' mb-3';

				switch ( $field['type'] ) {
					case 'listselect':
						$fields[$field_key]['element_class'] .= ' form-select';
						break;
					case 'color':
						$fields[$field_key]['element_class'] .= ' form-control form-control-color';
						break;
					case 'submit':
					case 'button':
						$fields[$field_key]['element_class'] .= ' btn';
						break;
					case 'checkbox':
					case 'listcheckbox':
						$fields[$field_key]['element_class'] .= ' form-check-input';
						break;
					default:
						$fields[$field_key]['element_class'] .= ' form-control';
						break;
				}
			}

			return $fields;
		} );

		add_action( 'wp_enqueue_scripts', function() {
			$styles = '
				.nf-form-cont .alert {
					display: none;
				}
				.nf-form-cont.areoi-nf-has-errors .alert {
					display: block;
				}
				.nf-form-cont.areoi-nf-has-errors .invalid-feedback {
					display: block;
				}
				.nf-form-cont .form-label label {
					font-weight: inherit;
				}
			';
			wp_add_inline_style( 'nf-display', areoi_minify_css( $styles ) );

			$scripts = '';
			$enqueue 		= 'plugins/ninja-forms/ninja-forms.js';
			if ( file_exists( AREOI__PLUGIN_LIGHTSPEED_DIR . $enqueue ) ) {
				ob_start(); include( AREOI__PLUGIN_LIGHTSPEED_DIR . $enqueue ); $scripts = ob_get_clean();
			}
			wp_add_inline_script( 'nf-front-end', areoi_minify_js( $scripts ) );
		} );
	}
}
