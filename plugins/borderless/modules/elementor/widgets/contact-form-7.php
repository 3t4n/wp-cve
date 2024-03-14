<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Contact_Form_7 extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-contact-form-7';
	}
	
	public function get_title() {
		return 'Contact Form 7';
	}
	
	public function get_icon() {
		return 'borderless-icon-contact-form-7';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}
	
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Contact Form 7', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'contact_form_7',
			[
				'label' => esc_html__( 'Select Contact Form', 'borderless' ),
                'description' => esc_html__('Contact form 7 - plugin must be installed and there must be some contact forms made with the contact form 7','borderless'),
				'type' => Controls_Manager::SELECT2,
				'multiple' => false,
				'label_block' => 1,
				'options' => get_contact_form_7_posts(),
			]
		);

		$this->end_controls_section();
	}
	
	protected function render() {

		$settings = $this->get_settings_for_display();

        if(!empty($settings['contact_form_7'])){ ?>
			<div class="borderless-elementor-contact-form-7-widget">
				<div class="borderless-elementor-contact-form-7" contact-form-7-id="<?php echo wp_kses( ( $settings['contact_form_7'] ), true ); ?>">
				<?php  echo do_shortcode('[contact-form-7 id="'.wp_kses( ( $settings['contact_form_7'] ), true ).'"]'); ?>   
				</div>  
			</div>
    	<?php }		 

	}
	
	protected function _content_template() {

    }
	
	
}