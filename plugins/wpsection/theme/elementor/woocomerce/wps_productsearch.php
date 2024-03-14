<?php

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;


if (class_exists('woocommerce')) {

class wpsection_wps_productsearch_Widget extends \Elementor\Widget_Base {


    public function get_name() {
        return 'wpsection_wps_productsearch';
    }

    public function get_title() {
        return __( 'Product Filter Search', 'wpsection' );
    }

    public function get_icon() {
        return 'eicon-site-search';
    }

    public function get_keywords() {
        return [ 'wpsection', 'wps_productsearch' ];
    }

	 public function get_categories() {
        return [  'wpsection_shop' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'Product Search',
            [
                'label' => esc_html__( 'Product Search', 'wpsection' ),
            ]
        );
		  		$this->add_control(
			'layout_control',
			[
				'label'       => __( 'Template Layout', 'wpsection' ),
				'type'        => 'elementor-layout-control',
				'default' => 'one',
				'options' => [
					'one' => [
						'label' => esc_html__('Layout 1', 'wpsection' ),
						'image' => plugin_dir_url( __FILE__ ) . 'images/s1.png',
					],
					'two' => [
						'label' => esc_html__('Layout 2', 'wpsection' ),
					'image' => plugin_dir_url( __FILE__ ) . 'images/s2.png',
					],
						
				
				],
			]
		);

        $this->end_controls_section();

// Button Setting
	
$this->start_controls_section(
			'wps_button_control',
			array(
				'label' => __( 'Button Settings', 'wpsection' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		
	

$this->add_control(
			'wps_button_color',
			array(
				'label'     => __( 'Button Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
	
				'selectors' => array(
					'{{WRAPPER}}   .wps_search_button' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'wps_button_color_hover',
			array(
				'label'     => __( 'Button Hover Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .wps_search_button:hover' => 'color: {{VALUE}} !important',

				),
			)
		);
$this->add_control(
			'wps_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .wps_search_button' => 'background: {{VALUE}} !important',
				),
			)
		);	
$this->add_control(
			'wps_button_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .wps_search_button:hover' => 'background: {{VALUE}} !important',
				),
			)
		);	
		
$this->add_control( 'wps_select_button_width',
					[
						'label' => esc_html__( 'Select Width',  'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						
						'selectors' => [
							'{{WRAPPER}} .wpsection-parts-search-box-form select' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
	
$this->add_control( 'wps_button_width',
					[
						'label' => esc_html__( 'Width',  'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 2000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						
						'selectors' => [
							'{{WRAPPER}} .wps_search_button' => 'width: {{SIZE}}{{UNIT}};',
						]
					
					]
				);
		

	$this->add_control( 'wps_button_height',
					[
						'label' => esc_html__( ' Height', 'wpsection' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px', '%' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 1000,
								'step' => 1,
							],
							'%' => [
								'min' => 0,
								'max' => 100,
							],
						],
						
						'selectors' => [
							'{{WRAPPER}} .wps_search_button' => 'height: {{SIZE}}{{UNIT}};',
					
						]
					]
				);		
			
	
		
	$this->add_control(
			'wps_button_padding',
			array(
				'label'     => __( 'Padding', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}}  .wps_search_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

	$this->add_control(
			'wps_button_margin',
			array(
				'label'     => __( 'Margin', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
				'selectors' => array(
					'{{WRAPPER}}  .wps_search_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'wps_button_typography',
				'label'    => __( 'Typography', 'wpsection' ),
				'selector' => '{{WRAPPER}}  .wps_search_button',
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'wps_button_border',
				'selector' => '{{WRAPPER}}  .wps_search_button ',
			)
		);
	

		$this->add_control(
			'wps_button_radius',
			array(
				'label'     => __( 'Border Radius', 'wpsection' ),
				'type'      => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' =>  ['px', '%', 'em' ],
			
				'selectors' => array(
					'{{WRAPPER}} .wps_search_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
				),
			)
		);


			$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wps_button_shadow',
				
				'label' => esc_html__( 'Box Shadow', 'wpsection' ),
				'selector' => '{{WRAPPER}} .defult_wps .wps_button',
			]
		);
		
		$this->end_controls_section();	


		
        }

    /**
     * Render button widget output on the frontend.
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access 
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
        ?>
 
<?php

      echo '
 <style>
.wpsection-parts-search-box-form {
  // padding: 30px;
  // border-width: 1px;
  // border-color: white;
  // border-style: solid;
    border-radius: 8px;
    display: inline-block;
    justify-content: space-between;
    margin-bottom: 0;
    gap: 30px;
}

.wpsection-parts-search-box-form select {
    display: revert;
    padding-left: 1;
    padding-right: 20px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
    background: #f9f9f9;
    padding: 15px 15px;
    border-radius: 4px;
    border-right: 20px solid #f9f9f9;
}
.jesan_color_dark .wpsection-parts-search-box-form select{
 	background: #383C45;
	border-right: 20px solid #383C45;
    color: #B8B8B8;
}
.search_one .wpsection-parts-search-box-form select {
    margin-bottom: 20px;
}

.wpsection-parts-search-box-form button {
    font-size: 16px;
    font-weight: 700;
    line-height: 16px;
    background: #222;
    border: 2px solid var(--color-high-dark);
    color: white;
    padding: 15px 70px;
    border-radius: 4px;
    height: 52px;
    text-transform: capitalize;
    transition: 0.3s;
    margin: 1px auto;
    display: block;
}
.wpsection-parts-search-box-form {
    width: 100%;
}
.wpsection-parts-search-box-form {
    box-shadow: 0px 2px 70px rgb(0 0 0 / 10%);
}


.wpsection-parts-search-box-form button:hover{
 background: #6a6a6a;
}
.search_two .wpsection-parts-search-box-form{
    display: flex!important;
}

</style>';		
		

?>



<?php  if ( 'one' === $settings['layout_control'] ) : ?>
    <div class="wps_search_filter_el defunt_nine product_search search_one">
            <div class="wpsection-parts-search-box-area">
                <?php echo do_shortcode( '[wps_el_filter_search]' );?>
            </div>
        </div>
<?php endif ;?>	
<?php  if ( 'two' === $settings['layout_control'] ) : ?>
    <div class="wps_search_filter_el_two defunt_nine product_search search_two">
            <div class="wpsection-parts-search-box-area">
                <?php echo do_shortcode( '[wps_el_filter_search]' );?>
            </div>
     </div>
<?php endif ;?>	


      
        <?php 
    }

}


// Register widget
Plugin::instance()->widgets_manager->register( new \wpsection_wps_productsearch_Widget() );

 }