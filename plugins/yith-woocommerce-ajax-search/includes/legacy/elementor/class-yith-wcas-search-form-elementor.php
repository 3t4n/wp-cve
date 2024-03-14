<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH WooCommerce Ajax Search Premium
 */

if ( ! defined( 'ABSPATH' ) || ! defined( 'YITH_WCAS_VERSION' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Plugin;

/**
 * Class YITH_WCAS_Search_Form_Elementor
 */
class YITH_WCAS_Search_Form_Elementor extends \Elementor\Widget_Base {


	/**
	 * Return the name of widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'yith-wcas-search-form';
	}

	/**
	 * Return the title.
	 *
	 * @return string|void
	 */
	public function get_title() {
		return __( 'YITH WooCommerce Ajax Search', 'yith-woocommerce-ajax-search' );
	}

	/**
	 * Return the icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-search';
	}

	/**
	 * Return the categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'yith', 'general' );
	}

	/**
	 * Return the keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'search', 'ajax', 'yith' );
	}

	/**
	 * Register controls
	 */
	protected function _register_controls() { //phpcs:ignore
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'YITH WooCommerce Ajax Search', 'yith-woocommerce-ajax-search' ),
			)
		);

		$this->add_control(
			'wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'%s [<a href="%s">%s</a>].',
					__( 'This widget inherits the style from the settings of the YITH WooCommerce Ajax Search plugin that you can edit', 'yith-woocommerce-ajax-search' ),
					get_admin_url( null, 'admin.php?page=yith_wcas_panel&tab=output' ),
					__( 'here', 'yith-woocommerce-ajax-search' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'template',
			array(
				'label'   => __( 'Template', 'yith-woocommerce-ajax-search' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''     => __( 'Default', 'yith-woocommerce-ajax-search' ),
					'info' => __( 'Wide', 'yith-woocommerce-ajax-search' ),
				),
			)
		);

		$this->add_control(
			'class',
			array(
				'label'   => __( 'Class', 'yith-woocommerce-ajax-search' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => false,
				),
			)
		);


		$this->end_controls_section();
	}

	/**
	 * Render the form
	 */
	protected function render() {

		if ( yith_wcas_user_switch_to_block() ) {
			if ( Plugin::$instance->editor->is_edit_mode() ) {
				ob_start();
				ywcas_show_elementor_preview();
				$shortcode = ob_get_contents();
				ob_end_clean();

			} else {
				$shortcode = do_shortcode( '[yith_woocommerce_ajax_search]' );
			}
		} else {
			$settings  = $this->get_settings_for_display();
			$shortcode = do_shortcode( '[yith_woocommerce_ajax_search template=' . $settings['template'] . ' class=' . $settings['class'] . ' ]' );
		}

		?>
        <div class="elementor-shortcode"><?php echo $shortcode; //phpcs:ignore ?></div>
		<?php

	}

}
