<?php
namespace Shop_Ready\extension\elewidgets\widgets\checkout;
use Shop_Ready\base\elementor\style_controls\common\Widget_Animation;

class Login_Form extends \Shop_Ready\extension\elewidgets\Widget_Base {

    use Widget_Animation;
	public $wrapper_class = true;

	protected function register_controls() {

		  // Notice 
		  $this->start_controls_section(
			'notice_content_section',
			[
				'label' => esc_html__( 'Notice', 'shopready-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'woo_ready_usage_direction_notice',
				[
					'label' => esc_html__( 'Important Note', 'shopready-elementor-addon' ),
					'type' => \Elementor\Controls_Manager::RAW_HTML,
					'raw' => esc_html__( 'Login Form Will shown when logout', 'shopready-elementor-addon' ),
					'content_classes' => 'woo-ready-account-notice',
				]
			);
	

		$this->end_controls_section(); 

		$this->start_controls_section(
			'editor_content_section',
			[
				'label' => esc_html__( 'Editor Only', 'shopready-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

			

				$this->add_control(
					'show_checkout_content',
					[
						'label'        => esc_html__( 'Checkout Content?', 'shopready-elementor-addon' ),
						'type'         => \Elementor\Controls_Manager::SWITCHER,
						'label_on'     => esc_html__( 'Show', 'shopready-elementor-addon' ),
						'label_off'    => esc_html__( 'Hide', 'shopready-elementor-addon' ),
						'return_value' => 'yes',
						'default'      => '',
					]
				);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'shopready-elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'wready_form_collapsible',
			[
				'label'        => esc_html__( 'Collapsible?', 'shopready-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'wready_form_lost_pass',
			[
				'label'        => esc_html__( 'Lost Password?', 'shopready-elementor-addon' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
				'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'toggle_content',
			[
				'label' => esc_html__( 'Toggle Switch', 'shopready-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('Click Here to open Login Form','shopready-elementor-addon'),
				'condition' => [
					'wready_form_collapsible' => ['yes']
				]
			]
		);
		
		$this->add_control(
			'return_customer',
			[
				'label' => esc_html__( 'Heading', 'shopready-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__('returning customer','shopready-elementor-addon'),
				'condition' => [
					'wready_form_collapsible' => ['yes']
				]
			]
		);
		
		$this->add_control(
			'message',
			[
				'label' => esc_html__( 'Message', 'shopready-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__('If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.','shopready-elementor-addon'),
				
			]
		);

		$this->add_control(
			'redirect_custom',
			[
				'label' => esc_html__( 'Custom Redirect', 'shopready-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'shopready-elementor-addon' ),
				'label_off' => esc_html__( 'No', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'shopready-elementor-addon' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://site-link.com', 'shopready-elementor-addon' ),
				
				
			]
		);
	

		$this->end_controls_section();

  
	}


	protected function html() {

		$settings = $this->get_settings_for_display();

		if( is_null( WC()->cart ) ){
			return;	
		}
       
		shop_ready_widget_template_part(
            'checkout/template-part/login-form.php',
            array(
                'settings'  => $settings,
            )
        );
	
	}

}