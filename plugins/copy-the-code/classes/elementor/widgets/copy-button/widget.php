<?php
/**
 * Elementor Copy Button Block
 *
 * @package Copy the Code
 * @since 3.1.0
 */

namespace CopyTheCode\Elementor\Block;

use CopyTheCode\Helpers;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Copy Button Block
 *
 * @since 3.1.0
 */
class CopyButton extends Widget_Base {

    /**
     * Constructor
     * 
     * @param array $data
     * @param array $args
     * 
     * @since 3.1.0
     */
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    /**
     * Get name
     */
    public function get_name() {
		return 'ctc_copy_button';
	}

    /**
     * Get title
     */
	public function get_title() {
		return esc_html__( 'Copy to Clipboard Button', 'copy-the-code' );
	}

    /**
     * Get icon
     */
	public function get_icon() {
		return 'eicon-button';
	}

    /**
     * Get categories
     */
	public function get_categories() {
		return Helpers::get_categories();
	}

    /**
     * Get keywords
     */
	public function get_keywords() {
		return Helpers::get_keywords( [ 'copy', 'button', 'clipboard', 'copy button', 'clipboard button' ] );
    }

    /**
     * Render
     */
    public function render() {
        ?>
        <div class="ctc-block ctc-copy-button">
            <div class="ctc-block-actions">
                <?php
                    Helpers::render_copy_button( $this );
                ?>
            </div>
            <?php Helpers::render_copy_content( $this ); ?>
        </div>
        <?php
    }

    /**
     * Register controls
     */
    protected function _register_controls() {
        Helpers::register_copy_content_section( $this );

        Helpers::register_copy_button_section( $this );

        Helpers::register_copy_button_style_section( $this );
    }

}