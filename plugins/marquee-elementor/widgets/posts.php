<?php

namespace MRQ\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class posts extends Widget_Base{

  public function get_name(){
    return 'Marquee posts';
  }

  public function get_title(){
    return 'Marquee Posts';
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
		  'posts',
		  'marquee posts',
		  'elementor posts',
		  'title',
		  'post title',
		  'anas',
	  ];
  }
  public function cats(){
	$arr = [];
	$categories = get_categories( array(
		'orderby' => 'name',
		'order'   => 'ASC'
	) );
	$arr['All'] = 'All';
	foreach( $categories as $category ) {
		$arr[$category->name] = $category->name;
		}
		return $arr;
	}


  protected function _register_controls(){

    $this->start_controls_section(
      'section_content',
      [
        'label' => 'Settings',
      ]
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
		'separator',
		[
		  'label' => 'Post Separator',
		  'type' => \Elementor\Controls_Manager::TEXT,
		  'default' => '-',
  
		],
  
	  );
	$this->add_control(
		'postcat',
		[
			'label' => esc_html__( 'Post Category', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'All',
			'options' =>  $this->cats(),
		]
	);
	$this->add_control(
		'orderBy',
		[
			'label' => esc_html__( 'Order By', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'All',
			'options' => [
				'none'  => esc_html__( 'None', 'plugin-name' ),
				'ID' => esc_html__( 'ID', 'plugin-name' ),
				'author' => esc_html__( 'Author', 'plugin-name' ),
				'title' => esc_html__( 'Title', 'plugin-name' ),
				'name' => esc_html__( 'Slug', 'plugin-name' ),
				'date' => esc_html__( 'Publish Date', 'plugin-name' ),
				'modified' => esc_html__( 'Modified Date', 'plugin-name' ),
				'rand' => esc_html__( 'Random Order', 'plugin-name' ),
			],
		]
	);
	$this->add_control(
		'order',
		[
			'label' => esc_html__( 'Order', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::SELECT,
			'default' => 'All',
			'options' => [
				'ASC'  => esc_html__( 'Ascending', 'plugin-name' ),
				'DESC' => esc_html__( 'Descending', 'plugin-name' ),

			],
		]
	);
	$this->add_control(
		'enable_link',
		[
			'label' => esc_html__( 'Enable Post Link', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => esc_html__( 'Show', 'your-plugin' ),
			'label_off' => esc_html__( 'Hide', 'your-plugin' ),
			'return_value' => 'yes',
			'default' => 'yes',
		]
	);
    $this->add_control(
		'spaceBetween',
		[
			'label' => esc_html__( 'Space Between Posts', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'rem' ],        
	'range' => [
				'px' => [
					'min' => 0,
					'max' => 500,
					'step' => 5,
				],
				'rem' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default' => [
				'unit' => 'px',
				'size' => 15,
			],
			'selectors' => [
				'{{WRAPPER}} .marquee span' => 'padding-inline-end: {{SIZE}}{{UNIT}};',
			],       

		]
	);
    $this->add_control(
		'postsNum',
		[
			'label' => esc_html__( 'Number of Posts(0 = All)', 'plugin-name' ),
			'type' => \Elementor\Controls_Manager::NUMBER,
			'min' => 0,
			'max' => 100,
			'step' => 1,
			'default' => 5,
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

    $this->end_controls_section();

    $this->start_controls_section(
      'style_section',
      [
				'label' => esc_html__( 'Style Section', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
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
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'plugin-name' ),
			]
		);
		$this->add_control(
			'text_color_normal',
			[
				'type' => \Elementor\Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'plugin-name' ),
				'default' => '#fefefe',
		'selectors' => [
		'{{WRAPPER}} .marquee span, {{WRAPPER}} .marquee span a' => 'color: {{VALUE}} !important;',
		],
			]
		);		
		$this->end_controls_tab();		
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
			'{{WRAPPER}} .marquee span a:hover' => 'color: {{VALUE}} !important;',
			],
				]
			);		
			$this->end_controls_tab();

			
			
		$this->end_controls_tabs();

    $this->end_controls_section();


  }
  

  protected function render(){
    $settings = $this->get_settings_for_display();
	$retrunCat = '';

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
	if ( $settings['postcat'] !== 'All' ) {
		$retrunCat =  $settings['postcat'];
	} else {
		$retrunCat = '';
	}
    ?>
    <div class="marquee_wrapper">

      <div class="marquee__content">
        <div  >
        <marquee class="marquee"  behavior="scroll" loop="<?php echo esc_attr($settings['loop']) ?>" hspace="<?php echo esc_attr($settings['hspace']) ?>" vspace="<?php echo esc_attr($settings['vspace']) ?>" Scrollamount="<?php echo esc_attr($settings['Scrollamount']) ?>" scrolldelay="<?php echo esc_attr($settings['delay']) ?>" direction="<?php echo esc_attr($settings['direction']) ?>">
					<?php 

			$all_query = new \WP_Query(array(
				'post_type'=>'post',
				'category_name' => $retrunCat ,
				'orderby' => $settings['orderBy'],
				'order'   => $settings['order'],			
				'post_status'=>'publish',
				'posts_per_page'=>$settings['postsNum'],
			));
			if ($all_query->have_posts()){
				while ($all_query->have_posts()){
					$all_query->the_post();
					?>
			<span>
			<?php
				if ( 'yes' === $settings['enable_link'] ) { ?>
					<a href="<?php the_permalink(); ?>"> 
				<?php }
			
				 the_title();?>
				<?php
				if ( 'yes' === $settings['enable_link'] ) { ?>
					</a>
				<?php }	?>	 
				
			</span>
			<?php if( ($all_query->current_post + 1) < ($all_query->post_count) )
				{ ?>
					<span class="sep"><?php echo sanitize_text_field($settings['separator']) ?></span>
			<?php }
			
					
				}
			}
			// Restore original post data.
			wp_reset_postdata();
			?>         
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
