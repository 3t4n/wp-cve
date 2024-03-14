<?php

namespace Element_Ready\Widget_Controls\Skins;

use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Tabs_Widget extends Skin_Base {

	/**
	 * Skin base constructor.
	 *
	 * Initializing the skin base class by setting parent widget and registering
	 * controls actions.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Widget_Base $parent
	 */
	public function __construct( Widget_Base $parent ) {
		// define parent widget (button)
		$this->parent = $parent;

		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/button/section_button/before_section_end', [ $this, 'add_controls' ] );
		
	}

	/**
	 * Get skin ID.
	 *
	 * Retrieve the skin ID.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_id() {
		return 'skin_layout_icon';
	}

	/**
	 * Get skin title.
	 *
	 * Retrieve the skin title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_title() {
		return __( 'Icon Layout', 'element-ready-lite' );
	}

	/**
	 * Add skin controls
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_controls() {
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {
		$settings = $this->parent->get_settings();
	
		$tabs = $settings['tabs'];
        
		$id_int = substr( $this->parent->get_id_int(), 0, 3 );
	
		$a11y_improvements_experiment = \Elementor\Plugin::$instance->experiments->is_feature_active( 'a11y_improvements' );
	
		$this->parent->add_render_attribute( 'elementor-tabs', 'class', 'elementor-tabs' );
		
		?>
		<div <?php $this->parent->print_render_attribute_string( 'elementor-tabs' ); ?>>
			<div class="elementor-tabs-wrapper" role="tablist" >
				<?php
				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;
					
					$tab_title_setting_key = $item['_id'];
					$tab_title = $a11y_improvements_experiment ? $item['tab_title'] : '<a href="">' . $item['tab_title'] . '</a>';
					
					$this->parent->add_render_attribute( $tab_title_setting_key, [
						'id' => 'elementor-tab-title-' . esc_attr($id_int . $tab_count),
						'class' => [ 'elementor-tab-title', 'elementor-tab-desktop-title' ],
						'aria-selected' => 1 === $tab_count ? 'true' : 'false',
						'data-tab' => esc_attr($tab_count),
						'role' => 'tab',
						'tabindex' => 1 === $tab_count ? '0' : '-1',
						'aria-controls' => 'elementor-tab-content-' . esc_attr($id_int . $tab_count),
						'aria-expanded' => 'false',
					] );
				
					?>
					<div <?php $this->parent->print_render_attribute_string( $tab_title_setting_key ); ?>><?php
						// PHPCS - the main text of a widget should not be escaped.
						echo wp_kses_post($tab_title); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?></div>
				<?php endforeach; ?>
			</div>
			<div class="elementor-tabs-content-wrapper" role="tablist" aria-orientation="vertical">
				<?php
				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;
					$hidden = 1 === $tab_count ? 'false' : 'hidden';
					$tab_content_setting_key = $item['_id'];
					
					$tab_title_mobile_setting_key = $item['_id'];
					
					$this->parent->add_render_attribute( $tab_content_setting_key, [
						'id' => 'elementor-tab-content-' . esc_attr($id_int . $tab_count),
						'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
						'data-tab' => esc_attr($tab_count),
						'role' => 'tabpanel',
						'aria-labelledby' => 'elementor-tab-title-' . esc_attr($id_int . $tab_count),
						'tabindex' => '0',
						'hidden' => esc_attr($hidden),
					] );
					
					$this->parent->add_render_attribute( $tab_title_mobile_setting_key, [
						'class' => [ 'elementor-tab-title', 'elementor-tab-mobile-title' ],
						'aria-selected' => 1 === $tab_count ? 'true' : 'false',
						'data-tab' => esc_attr($tab_count),
						'role' => 'tab',
						'tabindex' => 1 === $tab_count ? '0' : '-1',
						'aria-controls' => 'elementor-tab-content-' . esc_attr($id_int . $tab_count),
						'aria-expanded' => 'false',
					] );
					
					?>
					<div <?php $this->parent->print_render_attribute_string( $tab_title_mobile_setting_key ); ?>><?php
						$this->parent->print_unescaped_setting( 'tab_title', 'tabs', $index );
					?></div>
					<div <?php $this->parent->print_render_attribute_string( $tab_content_setting_key ); ?>><?php
						$this->parent->print_text_editor( $item['tab_content'] );
					?></div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}


	/**
	 * Return empty for _content_template to force PHP rendering and update editor template
	 * _content_template isn't supported in Skin
	 * @return string The JavaScript template output.
	 */

	public function skin_print_template( $content, $button ) {
		if( 'skin_layout_icon' == $button->get_name() ) {
			return '';
		}
	  	return $content;
	}

}