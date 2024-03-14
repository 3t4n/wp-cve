<?php

namespace MRQ\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class marquee extends Widget_Base{

  public function get_name(){
    return 'marquee';
  }

  public function get_title(){
    return 'Marquee';
  }

  public function get_icon(){
    return 'mrq-marquee';
  }

  public function get_categories(){
    return ['general'];
  }
  public function get_keywords()
  {
	  return [
		  'marquee',
		  'elementor marquee',
		  'scroll',
		  'text',
		  'scroll text',
		  'anas',
	  ];
  }

  protected function _register_controls(){

    $this->start_controls_section(
      'section_content',
      [
        'label' => 'Settings',
      ]
    );


    $this->add_control(
      'content_heading',
      [
        'label' => 'Content Heading',
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => 'My Other Example Heading',
        'dynamic' => [
          'active' => true,
        ]

      ],

    );

	$this->add_control(
		'direction',
		[
			'label' => esc_html__( 'Direction', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'up',
			'options' => [
				'up'  => esc_html__( 'up', 'plugin-name' ),
				'down' => esc_html__( 'down', 'plugin-name' ),
				'left' => esc_html__( 'left', 'plugin-name' ),
				'right' => esc_html__( 'right', 'plugin-name' ),
			],
		]
	);

    $this->add_control(
			'delay',
			[
				'label' => esc_html__( 'Delay', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 1000,
				'step' => 1,
				'default' => 5,
			]
		);

    $this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop(0 = infinite loops)', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 1,
				'default' => 0,
			]
		);

    $this->add_control(
			'Scrollamount',
			[
				'label' => esc_html__( 'Scroll amount', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 1000,
				'step' => 1,
				'default' => 5,
			]
		);
    $this->add_control(
			'vspace',
			[
				'label' => esc_html__( 'Vertical Space(Recommended 0)', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 5,
				'default' => 0,
			]
		);
    $this->add_control(
			'hspace',
			[
				'label' => esc_html__( 'Horizontal Space(Recommended 0)', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 1000,
				'step' => 5,
				'default' => 0,
			]
		);
		$this->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'plugin-name' ),
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);		
		$this->add_control(
			'tag',
			[
				'label' => esc_html__( 'HTML TAG', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'None',
				'options' => [
					''  => esc_html__( 'None', 'plugin-name' ),
					'p' => esc_html__( 'p', 'plugin-name' ),
					'div' => esc_html__( 'div', 'plugin-name' ),
					'H1' => esc_html__( 'h1', 'plugin-name' ),
					'H2' => esc_html__( 'h2', 'plugin-name' ),
					'H3' => esc_html__( 'h3', 'plugin-name' ),
					'H4' => esc_html__( 'h4', 'plugin-name' ),
					'H5' => esc_html__( 'h5', 'plugin-name' ),
				],
			]
		);
    $this->end_controls_section();

    $this->start_controls_section(
      'style_section',
      [
				'label' => esc_html__( 'Style Section', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );
		$this->add_control(
			'text_color',
			[
				'type' => \Elementor\Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'plugin-name' ),
				'default' => '#fefefe',
        'selectors' => [
          '{{WRAPPER}} .marquee a' => 'color: {{VALUE}} !important;',
        ],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .marquee',
			]
		);


    $this->add_control(
			'width',
			[
				'label' => esc_html__( 'width', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],        
        'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .marquee' => 'width: {{SIZE}}{{UNIT}};',
				],       

			]
		);
    $this->add_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],        
        'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .marquee' => 'height: {{SIZE}}{{UNIT}};',
				],       

			]
		);

		$this->start_controls_tabs(
			'style_tabs'
		);
			$this->start_controls_tab(
				'style_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'plugin-name' ),
				]
			);
			$this->add_control(
				'text_color_hover',
				[
					'type' => \Elementor\Controls_Manager::COLOR,
					'label' => esc_html__( 'Text Color', 'plugin-name' ),
					'default' => '#fefefe',
			'selectors' => [
			'{{WRAPPER}} .marquee:hover a' => 'color: {{VALUE}} !important;',
			],
				]
			);		
			$this->end_controls_tab();
		$this->end_controls_tabs();

    $this->end_controls_section();


  }
  

  protected function render(){
    $settings = $this->get_settings_for_display();

    $this->add_inline_editing_attributes('label_heading', 'basic');
    $this->add_render_attribute(
      'label_heading',
      [
        'class' => ['marquee__label-heading'],
      ]
    );
	if ( ! empty( $settings['website_link']['url'] ) ) {
		$this->add_link_attributes( 'website_link', $settings['website_link'] );
	}
    if($settings['loop'] == 0) {
      $settings['loop'] = 'INFINITE';
    }

    ?>
    <div class="marquee_wrapper">

      <div class="marquee__content">
        <div <?php echo esc_html_e($this->get_render_attribute_string('content_heading'),'enter your text'); ?>>
        <marquee class="marquee"  behavior="scroll" loop="<?php echo esc_attr($settings['loop']) ?>" hspace="<?php echo esc_attr($settings['hspace']) ?>" vspace="<?php echo esc_attr($settings['vspace']) ?>" Scrollamount="<?php echo esc_attr($settings['Scrollamount']) ?>" scrolldelay="<?php echo esc_attr($settings['delay']) ?>" direction="<?php echo esc_attr($settings['direction']) ?>">
		<a <?php echo esc_url( $this->get_render_attribute_string( 'website_link' )); ?>>
			<?php if ( $settings['tag'])  { ?>
			<<?php echo esc_html_e($settings['tag']) ?>> 
			<?php } ?>
				<?php echo esc_html_e($settings['content_heading']) ?>
			<?php if ( $settings['tag'] ) { ?>
			</<?php echo esc_html_e($settings['tag']) ?>> 
			<?php } ?> 
		</a>           
        </marquee>
        </div>
      </div>
    </div>
    <?php
  }

  protected function content_template(){
    ?>
    <#
        view.addInlineEditingAttributes( 'content_heading', 'basic' );
    view.addRenderAttribute(
        'content_heading',
        {
            'class': [ 'marquee__content-heading' ],
        }
    );
        #>
        <div class="marquee_wrapper">
      <div >
         
        <div    >
        <marquee class="marquee__content marquee" behavior="scroll" loop="{{{ settings.loop }}}" hspace="{{{ settings.hspace }}}" vspace="{{{ settings.vspace }}}" Scrollamount="{{{ settings.Scrollamount }}}" scrolldelay="{{{ settings.delay }}}" direction="{{{ settings.direction }}}"   {{{ view.getRenderAttributeString( 'content_heading' ) }}}>
		<a href="{{ settings.website_link.url }}">
			<# if ( settings.tag.length ) { #>
				<{{{ settings.tag }}}> 
			<# } #>
			{{{ settings.content_heading }}} 
			<# if ( settings.tag.length ) { #>
			</{{{ settings.tag }}}>
			<# } #>
		</a>
         </marquee>
        </div>
        
      </div>
    </div>
        <?php
  }
}
