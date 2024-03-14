<?php
/**
 * Fields functions.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajax class.
 */
trait REVIVESO_Fields {

	use REVIVESO_HelperFunctions;

	/**
	 * Send AJAX response.
	 *
	 * @param array   $data    Data to send using ajax.
	 * @param boolean $success Optional. If this is an error. Defaults: true.
	 */
	protected function do_field( $data ) {
		$data = $this->do_filter( 'admin_fields', $data, $data['name'] );

		if ( ! isset( $data['type'] ) || empty( $data['type'] ) ) {
			$data['type'] = 'text';
		}
	
		$class = array( 'reviveso-form-control', 'reviveso-form-el' );
		if ( isset( $data['class'] ) && ! empty( $data['class'] ) ) {
			if ( is_array( $data['class'] ) ) {
				$class = array_merge( $class, $data['class'] );
			} else {
				array_push( $class, $data['class'] );
			}
		}
	
		$name = $data['name'];
		
	
		$attr = array();
		if ( isset( $data['required'] ) && true === $data['required'] ) {
			$attr[] = 'required';
			$attr[] = 'data-required="yes"';
		} else {
			$attr[] = 'data-required="no"';
		}
	
		if ( isset( $data['checked'] ) && true === $data['checked'] ) {
			$attr[] = 'checked';
		}
	
		if ( isset( $data['disabled'] ) && true === $data['disabled'] ) {
			$attr[] = 'disabled';
		}
	
		if ( isset( $data['readonly'] ) && true === $data['readonly'] ) {
			$attr[] = 'readonly';
		}
	
		if ( isset( $data['attributes'] ) && ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
			foreach ( $data['attributes'] as $key => $value ) {
				$attr[] = $key . '="' . $value . '"';
			}
		}

		if ( isset( $data['condition'] ) && ! empty( $data['condition'] ) && is_array( $data['condition'] ) ) {
			$cattr = 'data-condition="' . htmlspecialchars( wp_json_encode( $data['condition'] ), ENT_QUOTES, 'UTF-8' ) . '"';
			array_push( $attr, $cattr );
		}

		if ( isset( $data['show_if'] ) && ! empty( $data['show_if'] ) ) {
			$cattr = 'data-show-if="' . $data['show_if'] . '"';
			array_push( $attr, $cattr );
		}
	
		$value = isset( $data['value'] ) ? $data['value'] : '';
	
		if ( $data['type'] == 'hidden' ) {
			echo '<input type="hidden" name="reviveso_plugin_settings[' . esc_attr( $name ) . ']" id="' . esc_attr( $data['name'] ) . '" autocomplete="off" value="' . esc_attr( $value ) . '" />';
			return;
		}

		$tooltip = '';
		if ( isset( $data['description'] ) && ! empty( $data['description'] ) ) {
			if ( isset( $data['tooltip'] ) && $data['tooltip'] ) {
				$tooltip = '<span class="tooltip" title="' . esc_attr( $data['description'] ) . '"><span title="" class="dashicons dashicons-editor-help"></span></span>';
			} else {
				$tooltip = '<div class="description">' . wp_kses_post( $data['description'] ) . '</div>';
			}
		}
	
		if ( $data['type'] == 'checkbox' ) {
			$value = ! empty( $value ) ? $value : '1';
			$check = !empty( $data['checked'] ) ? rest_sanitize_boolean( $data['checked'] ) : false;

			
			echo '<div class="reviveso-toggle ' . esc_attr( $name ) . '-wrapper">';
				echo '<input class="reviveso-toggle__input" type="checkbox" id="' . esc_attr( $name ) . '" name="reviveso_plugin_settings[' . esc_attr( $name ) . ']" data-setting="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" ' . checked( 1, $check, false ) . '>';
				echo '<div class="reviveso-toggle__items">';
					echo '<span class="reviveso-toggle__track"></span>';
					echo '<span class="reviveso-toggle__thumb"></span>';
					echo '<svg class="reviveso-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>';
					echo '<svg class="reviveso-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6"><path d="M0 0h2v6H0z"></path></svg>';
				echo '</div>';
				echo '<span class="reviveso-toggle-description">' . isset( $data['description'] ) ? wp_kses_post( $data['description'] ) : '' . '</span>';
			echo '</div>';
			
			return;
		}

		if ( isset( $data['type'] ) ) {
			if ( in_array( $data['type'], array( 'text', 'email', 'password', 'date', 'number' ) ) ) {
				echo '<input type="' . esc_attr( $data['type'] ) . '" name="reviveso_plugin_settings[' . esc_attr( $name ) . ']" id="' . esc_attr( $data['name'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" autocomplete="off" value="' . esc_attr( $value ) . '" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' />';
			} elseif ( $data['type'] == 'textarea' ) {
				echo '<textarea class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" id="' . esc_attr( $data['name'] ) . '" name="reviveso_plugin_settings[' . esc_attr( $name ) . ']" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' autocomplete="off">' . wp_kses_post( $value ) . '</textarea>';
			} elseif ( $data['type'] == 'select' ) {
				echo '<select id="' . esc_attr( $data['name'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" name="reviveso_plugin_settings[' . esc_attr( $name ) . ']" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' autocomplete="off">';
				if ( ! empty( $data['options'] ) && is_array( $data['options'] ) ) {
					foreach ( $data['options'] as $key => $option ) {
						$disabled = ( strpos( $key, 'premium' ) !== false ) ? ' disabled' : '';
						echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value, false ) . esc_attr( $disabled ) . '>' . esc_html( $option ) . '</option>';
					}
				}
				echo '</select>';
			} elseif ( $data['type'] == 'multiple' ) {
				echo '<select id="' . esc_attr( $data['name'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" name="reviveso_plugin_settings[' . esc_attr( $name ) . '][]" multiple="multiple" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' style="width: 95%">';
				if ( ! empty( $data['options'] ) && is_array( $data['options'] ) ) {
					foreach ( $data['options'] as $key => $option ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, $value ), true, false ) . '>' . esc_html( $option ) . '</option>';
					}
				} elseif ( ! empty( $value ) ) {
					foreach ( $value as $author ) {
						$key = $author;
						if ( 'allowed_authors' === $data['name'] ) {
							$user = get_user_by( 'id', $key );
							$author = $user->display_name;
						}
						echo '<option value="' . esc_attr( $key ) . '" selected="selected">' . esc_html( $author ) . '</option>';
					}
				}
				echo '</select>';
			} elseif ( $data['type'] == 'multiple_tax' ) {
				echo '<select id="' . esc_attr( $data['name'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" name="reviveso_plugin_settings[' . esc_attr( $name ) . '][]" multiple="multiple" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' style="width: 95%">';
				if ( ! empty( $data['options'] ) && is_array( $data['options'] ) ) {
					foreach ( $data['options'] as $key => $option ) {
						echo '<optgroup label="' . esc_attr( $option['label'] ) . '">';
						if ( isset( $option['categories'] ) && ! empty( $option['categories'] ) && is_array( $option['categories'] ) ) {
							foreach ( $option['categories'] as $cat_slug => $cat_name ) {
								echo '<option value="' . esc_attr( $cat_slug ) . '" ' . selected( in_array( $cat_slug, $value ), true, false ) . '>' . esc_html( $cat_name ) . '</option>';
							}
						}
						echo '</optgroup>';
					}
				} elseif ( ! empty( $value ) ) {
					foreach ( $value as $taxonomy ) {
						$taxonomy          = $this->process_taxonomy( $taxonomy );
						$term              = get_term( $taxonomy[1] );
						if ( ! is_wp_error( $term ) || ! is_null( $term ) ) {
							$get_taxonomy_data = get_taxonomy( $term->taxonomy );
							$cat_name          = $get_taxonomy_data->label . ': ' . $term->name;

							echo '<option value="' . esc_attr( join( '|', $taxonomy ) ) . '" selected="selected">' . esc_html( $cat_name ) . '</option>';
						}
					}
				}
				echo '</select>';
			} elseif ( $data['type'] == 'wp_editor' ) {
				echo '<div class="reviveso-form-control reviveso-form-el reviveso-editor" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) )  . '>';
				wp_editor( html_entity_decode( $value, ENT_COMPAT, "UTF-8" ), $data['name'], array(
					'textarea_name' => 'reviveso_plugin_settings[' . esc_attr( $name ) . ']',
					'textarea_rows' => '8',
					'teeny'         => true,
					'tinymce'       => false,
					'media_buttons' => false,
				) );
				echo '</div>';
				$tooltip = '';
			}elseif( $data['type'] == 'tool' ){
					?>
					<form action="<?php echo esc_attr( admin_url('admin-post.php') ); ?>" id="form_<?php echo esc_attr( $data['name'] ); ?>" method="POST" >
						<?php wp_nonce_field( 'reviveso_' . $data['name'], 'reviveso_' . $data['name'] ); ?>
						<input type="hidden" name="action" value="reviveso_<?php echo esc_attr( $data['name'] ); ?>">
						<input type="submit" class="button button-large" value="<?php echo esc_attr( $data['label'] ); ?>">
					</form>
					<?php
				
				$tooltip = '';
			}elseif( $data['type'] == 'tool-export' ){ ?>

				<form action="<?php echo esc_attr( admin_url('admin-post.php') ); ?>" method="post">
					<input type="hidden" name="action" value="reviveso_export_settings" />
					<?php wp_nonce_field( 'reviveso_export_nonce', 'reviveso_export_nonce' ); ?>
					<?php submit_button( __( 'Export Settings', 'revive-so' ), 'button-large button-secondary default', 'reviveso-export', false ); ?>
				</form>

			<?php
			$tooltip = '';
			}elseif( $data['type'] == 'tool-import' ){ ?>
					<form id="form_tool_import" action="<?php echo esc_attr( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data">
						<input type="file" id="reviveso_import_settings_input" name="import_file" accept=".json"/>
						<input type="hidden" name="action" value="reviveso_import_settings" />
						<?php wp_nonce_field( 'reviveso_import_nonce', 'reviveso_import_nonce' ); ?>
						<?php submit_button( __( 'Import Settings', 'revive-so' ), 'button-large button-secondary default', 'reviveso-import', false ); ?>
					</form>
				<?php
			
			$tooltip = '';
			}elseif( $data['type'] == 'tool-status' ){ ?>

					<span><?php esc_html_e( 'System Status', 'revive-so' ); ?></span>
					<p><?php esc_html_e( 'In order to use this plugin, please ensure your server meets the following PHP configurations. Your hosting provider will help you modify server configurations, if required.', 'revive-so' ); ?></p>
					<?php $this->systemStatus(); ?>

				<?php
			
			$tooltip = '';
			}else{
				do_action( "reviveso_do_field_{$data['type']}", $data );
			}
			echo wp_kses_post( $tooltip );
			return;
		}
	}
}