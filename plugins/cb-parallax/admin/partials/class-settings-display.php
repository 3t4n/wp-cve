<?php
namespace CbParallax\Admin\Partials;

use CbParallax\Admin\Menu\Includes as MenuIncludes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'MenuIncludes\cb_parallax_validation' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/menu/includes/class-validation.php';
}

/**
 * The class that deals with the settings api.
 *
 * @link
 * @since             0.6.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/admin/menu/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_settings_display {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * The reference to the options class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    MenuIncludes\cb_parallax_options $options
	 */
	private $options;
	
	/**
	 * The array containing the allowed image_options.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    array $allowed_image_options
	 */
	private $allowed_image_options;
	
	/**
	 * The array containing the default image_options.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    array $default_image_options
	 */
	private array $default_image_options;
	
	/**
	 * The array containing the default plugin image_options.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    array $default_plugin_options
	 */
	private $default_plugin_options;
	
	/**
	 * The reference to the validation class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    MenuIncludes\cb_parallax_validation $validation
	 */
	private $validation;
	
	/**
	 * Holds the array containing the default image options.
	 */
	private function set_default_image_options() {
		
		$this->default_image_options = $this->options->get_default_image_options();
	}
	
	/**
	 * Holds the array containing the default plugin options.
	 */
	private function set_default_plugin_options() {
		
		$this->default_plugin_options = $this->options->get_default_plugin_options();
	}
	
	/**
	 * Holds the reference to the class that's responsible for validation operations.
	 */
	private function set_validation_instance() {
		
		$this->validation = new MenuIncludes\cb_parallax_validation( $this->domain, $this->options );
	}
	
	/**
	 * cb_parallax_admin constructor.
	 *
	 * @param string $domain
	 * @param MenuIncludes\cb_parallax_options $options
     * @param array $allowed_image_options
	 */
	public function __construct( $domain, $options, $allowed_image_options ) {
		
		$this->domain = $domain;
		$this->options = $options;
		$this->allowed_image_options = $allowed_image_options;
		$this->set_validation_instance();
		
		$this->set_default_image_options();
		$this->set_default_plugin_options();
	}
	
	/**
     * Creates and returns a string containing the hidden fields:
     * - The nonce for the form
     * - The image id
     * - The image url
	 *
	 * @param string $attachment_id
	 * @param string $url
	 *
	 * @return string $html
	 */
	public function get_hidden_fields_display( $attachment_id, $url ) {
		
		ob_start();
		?>
		<?php wp_nonce_field( 'cb_parallax_nonce_field', 'cb_parallax_nonce' ); ?>
        <input type="hidden" name="cb_parallax_options[cb_parallax_attachment_id]" id="cb_parallax_options[cb_parallax_attachment_id]"
            class="cb_parallax_attachment_id"
            value="<?php echo esc_attr( $attachment_id ) ?>" data-key="cb_parallax_attachment_id" data-input="cb-parallax"/>
        <input type="hidden" name="cb_parallax_options[cb_parallax_background_image_url_hidden]"
            id="cb_parallax_options[cb_parallax_background_image_url_hidden]"
            value="<?php echo esc_url( $url ) ?>" data-key="cb_parallax_background_image_url" data-input="cb-parallax"/>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
     * Creates and returns a string containing a button.
	 *
	 * @return string $html
	 */
	public function get_media_button_display() {
		
		ob_start();
		?>
        <div class="cb-parallax-remove-media-button-container">
            <a class="cb-parallax-remove-media" href="#"><i class="dashicons dashicons-no-alt" aria-hidden="true"></i></a>
        </div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
     * Creates and returns a settings field containing the image input field and the image placeholder.
	 *
	 * @param string $url
	 * @param string $section
	 *
	 * @return string $html
	 */
	public function get_background_image_display( $url, $section = 'image' ) {
		
		$placeholder_image_class = '' === $url ? '' : 'hidden';
		$postfix = 'plugin' === $section ? '' : '-small';
		$img_postfix = 'de_DE' === get_locale() ? 'de' : 'default';
		$placeholder_image_url = CBPARALLAX_ROOT_URL . 'admin/menu/images/placeholder' . $postfix . '-' . $img_postfix . '.png';
		
		ob_start();
		?>
        <div class="cb-parallax-image-container">
            <a href="#" class="cb-parallax-media-url">
                <img id="cb_parallax_options[cb_parallax_background_image_url]" class="cb_parallax_background_image" alt=""
                    src="<?php echo esc_url( $url ) ?>" data-key="cb_parallax_background_image_url" data-input="cb-parallax"/>
                <img id="cb_parallax_placeholder_image" class="cb_parallax_placeholder_image <?php echo $placeholder_image_class ?>" alt=""
                    src="<?php echo $placeholder_image_url ?>" data-key="cb_parallax_placeholder_image" data-input=""/>
            </a>
        </div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
	 * Creates and returns a settings field containing a checkbox and it's label.
	 *
	 * @param string $value
	 * @param array $args
	 *
	 * @return string $html
	 */
	public function get_checkbox_display( $value, $args ) {
		
		$option_key = $args['option_key'];
		$name = $args['name'];
		$description = $args['description'];
		$value = isset( $value ) && '1' === $value ? '1' : '0';
		
		ob_start();
		?>
        <div class="cb-parallax-single-option-container" id="<?php echo $option_key ?>_container" title="<?php echo $description; ?>">
            <div>
                <label for="<?php echo 'cb_parallax_options' ?>[<?php echo $option_key ?>]"
                    class="label-for-cb-parallax-switch"><?php echo $name ?></label>

                <label class="cb-parallax-switch">
                    <input type="checkbox" id="<?php echo 'cb_parallax_options' ?>[<?php echo $option_key ?>]"
                        class="cb-parallax-switch-input cb-parallax-input-checkbox <?php echo $option_key; ?>"
                        name="<?php echo 'cb_parallax_options' ?>[<?php echo $option_key ?>]" value="<?php echo $value ?>"
						<?php checked( 1, isset( $value ) ? $value : 0, true ); ?> data-key="<?php echo $option_key; ?>" data-input="cb-parallax"/>
                    <span class="cb-parallax-switch-label <?php echo $option_key ?>" data-on="On" data-off="Off"></span>
                    <span class="cb-parallax-switch-handle"></span>
                </label>
            </div>
        </div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
     * Creates and returns a settings field containing a color picker and it's label.
	 *
	 * @param string $value
     * @param array $args
	 *
	 * @return string $html
	 */
	public function get_color_picker_field( $value, $args ) {
		
		$option_key = $args['option_key'];
		$description = $args['description'];
		ob_start();
		?>
        <div class="cb-parallax-single-option-container" id="<?php echo $option_key ?>_container" title="<?php echo $description; ?>">
            <p class="cb-parallax-input-container">
                <label for="<?php echo $option_key ?>"><?php echo $args['name'] ?></label>
                <input type="text" id="<?php echo $option_key ?>" title="<?php echo $description ?>"
                    name="<?php echo 'cb_parallax_options' ?>[<?php echo $option_key ?>]"
                    Placeholder="<?php echo $this->default_image_options[ $option_key ] ?>" value="<?php echo $value ?>"
                    class="<?php echo $option_key ?> cb-parallax-color-picker
			cb-parallax-input-color-picker" data-alpha="true" data-key="<?php echo $option_key; ?>" data-input="color-picker"/>
            </p>
        </div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
     * Creates and returns a settings field containing a drop down box and it's label.
     *
	 * @param string $value
	 * @param array $args
	 *
	 * @return string $html
	 */
	public function get_select_field( $value, $args ) {
		
		$option_key = $args['option_key'];
		$description = $args['description'];
		$select_values = $args['select_values'];
		ob_start();
		?>
        <div class="cb-parallax-single-option-container" id="<?php echo $option_key ?>_container">
            <p class="cb-parallax-input-container" title="<?php echo $description; ?>">
                <label for="<?php echo 'cb_parallax_options' ?>[<?php echo $option_key; ?>]"><?php echo $args['name'] ?></label>
                <select type="select" name="<?php echo 'cb_parallax_options' ?>[<?php echo $option_key; ?>]"
                    class="floating-element fancy-select cb-parallax-fancy-select cb-parallax-input-select" data-key="<?php echo $option_key; ?>"
                    data-input="cb-parallax" id="<?php echo 'cb_parallax_options' ?>[<?php echo $option_key; ?>]">
					<?php foreach ( $select_values as $key => $select_value ) { ?>
                        <option value="<?php echo $select_value ?>" <?php echo selected( $value, $select_value, false ); ?> ><?php echo translate( $select_value, $this->domain ); ?></option>
					<?php } ?>
                </select>
            </p>
        </div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	}
	
	/**
     * Returns a string containing the form title.
     *
	 * @return string
	 */
	public function get_form_title() {
		
		return '<h2>' . __( 'Background Image Settings', $this->domain ) . '</h2>';
	}
	
	/**
     * Configures the visibility of the titles and returns them.
     *
	 * @param string $section
	 *
	 * @return string
	 */
	public function get_settings_title( $section ) {
		
		if ( 'meta-box' === $section ) {
			$parallax_class = 'hidden';
			$static_class = '';
		} else {
			$parallax_class = '';
			$static_class = 'hidden';
		}
		
		$title_parallax = '<h3 class="parallax ' . $parallax_class . '">' . __( 'Parallax Image Settings', $this->domain ) . '</h3>';
		$title_static = '<h3 class="static ' . $static_class . '">' . __( 'Static Image Settings', $this->domain ) . '</h3>';
		
		return $title_parallax . $title_static;
	}
	
}
