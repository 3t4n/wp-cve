<?php
/**
 * Reamaze Settings Base
 *
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists('Reamaze_Settings_Page') ) :

/**
 * Reamaze_Settings_Page
 */
abstract class Reamaze_Settings_Page {
	protected $id    = '';
	protected $label = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'reamaze_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'reamaze_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'reamaze_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Add this page to settings
	 */
	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;

		return $pages;
	}

	/**
   * Get settings array
   *
   * @return array
   */
  public function get_settings() {
    return array();
  }

	/**
	 * Output settings page
	 */
	public function output() {
	  $this->_output_fields();
	}

	/**
	 * Save settings
	 */
	public function save() {
	  $options = $this->get_settings();
	  $update_options = array();
	  $user_update_options = array();

	  if ( empty( $_POST ) ) {
      return false;
    }

    foreach ( $options as $value ) {
			global $allowedposttags;
      if ( ! isset( $value['id'] ) || ! isset( $value['type'] ) ) {
        continue;
      }

      $option_name = $value['id'];
			$option_value = '';

			switch ( $value['type'] ) {
        case 'checkbox':
          $option_value = is_null( $_POST[ $value['id'] ] ) ? 'no' : 'yes';
          break;
        case 'textarea':
          if ( ! ( isset( $value['raw'] ) && $value['raw'] ) ) {
            $option_value = sanitize_textarea_field( trim( $_POST[ $value['id'] ] ) );
          } else {
						$option_value = trim( wp_kses_post( wp_unslash( $_POST[ $value['id'] ] ) ) );
          }
          break;
        case 'email':
          $option_value = sanitize_email( $_POST[ $value['id'] ] );
          break;
        case 'multiselect':
          $option_value = array_filter( array_map( 'sanitize_text_field', (array) $_POST[ $value['id'] ] ) );
          break;
        default:
	  			$option_value = sanitize_text_field( $_POST[ $value['id'] ] );
          break;
      }

      if ( isset( $value['user_setting'] ) && $value['user_setting'] ) {
        $user_update_options[ $option_name ] = $option_value;
      } else {
        $update_options[ $option_name ] = $option_value;
      }
    }

    foreach ( $update_options as $name => $value ) {
      update_option( $name, $value );
    }

    foreach ( $user_update_options as $name => $value ) {
      update_user_meta( wp_get_current_user()->ID, $name, $value );
    }

    return true;
	}

	/**
	 * Output fields
	 */
	protected function _output_fields() {
	  $options = $this->get_settings();

	  foreach ( $options as $value ) {
      if ( ! isset( $value['type'] ) ) {
        continue;
      }

      if ( ! isset( $value['id'] ) ) {
        $value['id'] = '';
      }
      if ( ! isset( $value['class'] ) ) {
        $value['class'] = '';
      }
      if ( ! isset( $value['css'] ) ) {
        $value['css'] = '';
      }
      if ( ! isset( $value['default'] ) ) {
        $value['default'] = '';
      }
      if ( ! isset( $value['desc'] ) ) {
        $value['desc'] = '';
      }
      if ( ! isset( $value['placeholder'] ) ) {
        $value['placeholder'] = '';
      }

      $type = $value['type'];
      if ( isset( $value['user_setting'] ) && $value['user_setting'] ) {
        $option_value = get_user_meta( wp_get_current_user()->ID, $value['id'], true );
        if ( empty( $option_value ) ) {
          $option_value = '';
        }
      } else {
        $option_value = get_option( $value['id'], $value['default'] );
      }
      $description = $value['desc'];

      if ( ! empty( $description ) ) {
        $description = '<p class="description">' . $description . '</p>';
      }

      $custom_attributes = array();

      if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
        foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
          $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
        }
      }

      // Switch based on type
      switch ( $value['type'] ) {
        // Section Titles
        case 'title':
          if ( ! empty( $value['title'] ) ) {
            echo '<h3 style="font-size: 18px;">' . esc_html( $value['title'] ) . '</h3>';
          }
          if ( ! empty( $value['desc'] ) ) {
            echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
          }
          echo '<table class="form-table">'. "\n\n";
          break;

        // Section Ends
        case 'sectionend':
          echo '</table>';
          break;

        // Standard text inputs and subtypes like 'number'
        case 'text':
        case 'email':
        case 'number':
        case 'password':
          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td>
              <input
                name="<?php echo esc_attr( $value['id'] ); ?>"
                id="<?php echo esc_attr( $value['id'] ); ?>"
                type="<?php echo esc_attr( $type); ?>"
                style="<?php echo esc_attr( $value['css'] ); ?>"
                value="<?php echo esc_attr( $option_value); ?>"
                class="regular-text <?php echo esc_attr( $value['class'] ); ?>"
                placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                <?php echo esc_html( implode( ' ', $custom_attributes ) ); ?>
                /> <?php echo wp_kses_post( $description ); ?>
            </td>
          </tr><?php
          break;

        // Textarea
        case 'textarea':
          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td>
              <?php echo wp_kses_post( $description ); ?>

              <textarea
                name="<?php echo esc_attr( $value['id'] ); ?>"
                id="<?php echo esc_attr( $value['id'] ); ?>"
                style="<?php echo esc_attr( $value['css'] ); ?>"
                class="<?php echo esc_attr( $value['class'] ); ?>"
                placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
                <?php echo esc_html( implode( ' ', $custom_attributes ) ); ?>
                ><?php echo esc_textarea( $option_value );  ?></textarea>
            </td>
          </tr><?php
          break;

        // Select boxes
        case 'select':
        case 'multiselect':
          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td>
              <select
                name="<?php echo esc_attr( $value['id'] ); ?><?php if ( $value['type'] == 'multiselect' ) echo '[]'; ?>"
                id="<?php echo esc_attr( $value['id'] ); ?>"
                style="<?php echo esc_attr( $value['css'] ); ?>"
                class="<?php echo esc_attr( $value['class'] ); ?>"
                <?php echo ('multiselect' == $value['type'] ) ? 'multiple="multiple"' : ''; ?>
                <?php echo esc_html( implode( ' ', $custom_attributes ) ); ?>
                >
                <?php
                  foreach ( $value['options'] as $key => $val ) {
                    ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php

                      if ( is_array( $option_value ) ) {
                        selected( in_array( $key, $option_value ), true );
                      } else {
                        selected( $option_value, $key );
                      }

                    ?>><?php echo esc_html( $val ) ?></option>
                    <?php
                  }
                ?>
               </select> <?php echo wp_kses_post( $description ); ?>
            </td>
          </tr><?php
          break;

        // Radio inputs
        case 'radio' :
          ?><tr valign="top">
            <th scope="row" class="titledesc">
              <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
            </th>
            <td>
              <fieldset>
                <?php echo wp_kses_post( $description ); ?>
                <ul>
                <?php
                  foreach ( $value['options'] as $key => $val ) {
                    ?>
                    <li>
                      <label><input
                        name="<?php echo esc_attr( $value['id'] ); ?>"
                        value="<?php echo esc_attr( $key ); ?>"
                        type="radio"
                        style="<?php echo esc_attr( $value['css'] ); ?>"
                        class="<?php echo esc_attr( $value['class'] ); ?>"
                        <?php checked( $key, $option_value ); ?>
												<?php echo esc_html( implode( ' ', $custom_attributes ) ); ?>
                        /> <?php echo esc_html( $val ) ?></label>
                    </li>
                    <?php
                  }
                ?>
                </ul>
              </fieldset>
            </td>
          </tr><?php
          break;

        // Checkbox input
        case 'checkbox':
          $visbility_class = array();

          if ( ! isset( $value['hide_if_checked'] ) ) {
            $value['hide_if_checked'] = false;
          }
          if ( ! isset( $value['show_if_checked'] ) ) {
            $value['show_if_checked'] = false;
          }
          if ( 'yes' == $value['hide_if_checked'] || 'yes' == $value['show_if_checked'] ) {
            $visbility_class[] = 'hidden_option';
          }
          if ( 'option' == $value['hide_if_checked'] ) {
            $visbility_class[] = 'hide_options_if_checked';
          }
          if ( 'option' == $value['show_if_checked'] ) {
            $visbility_class[] = 'show_options_if_checked';
          }

          if ( ! isset( $value['checkboxgroup'] ) || 'start' == $value['checkboxgroup'] ) {
            ?>
              <tr valign="top" class="<?php echo esc_attr(implode(' ', $visbility_class) ); ?>">
                <th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
                <td>
                  <fieldset>
            <?php
          } else {
            ?>
              <fieldset class="<?php echo esc_attr( implode(' ', $visbility_class) ); ?>">
            <?php
          }

          if ( ! empty( $value['title'] ) ) {
            ?>
              <legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ) ?></span></legend>
            <?php
          }

          ?>
            <label for="<?php echo esc_attr( $value['id'] ) ?>">
              <input
                name="<?php echo esc_attr( $value['id'] ); ?>"
                id="<?php echo esc_attr( $value['id'] ); ?>"
                type="checkbox"
                value="1"
                <?php checked( $option_value, 'yes' ); ?>
								<?php echo esc_html( implode( ' ', $custom_attributes ) ); ?>
              /> <?php echo wp_kses_post( $description ) ?>
            </label>
          <?php

          if ( ! isset( $value['checkboxgroup'] ) || 'end' == $value['checkboxgroup'] ) {
                  ?>
                  </fieldset>
                </td>
              </tr>
            <?php
          } else {
            ?>
              </fieldset>
            <?php
          }
          break;

        // Default
        default:
          break;
      }
    }
	}
}

endif;
