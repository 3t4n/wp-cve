<?php
/*
 * Elementor Education Addon Category Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_category'])) { // enable & disable
if ( class_exists( 'LearnPress' ) ) {

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Category extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_category';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Category', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-product-categories';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Category widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_category',
			[
				'label' => __( 'Category Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'category_style',
			[
				'label' => esc_html__( 'Category Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_control(
			'cat_limit',
			[
				'label' => esc_html__( 'Category Count', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'description' => esc_html__( 'Enter the number of items to show.', 'education-addon' ),
			]
		);
		$this->add_control(
			'cat_column',
			[
				'label' => __( 'Columns', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options' => [
					'col-3' => esc_html__( 'Three Column', 'education-addon' ),
					'col-4' => esc_html__( 'Four Column', 'education-addon' ),
					'col-6' => esc_html__( 'Six Column', 'education-addon' ),
				],
				'default' => 'col-6',
				'description' => esc_html__( 'Select your column.', 'education-addon' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .category-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .category-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'secn_bg_color',
					'label' => __( 'Background Color', 'education-addon' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .category-item',
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'secn_hov_bg_color',
					'label' => __( 'Hover Background Color', 'education-addon' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .category-item:after, {{WRAPPER}} .categories-style-two .naedu-icon:before',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .category-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .category-item',
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_margin',
				[
					'label' => __( 'Title Spacing', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .category-item h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'sastool_title_typography',
					'selector' => '{{WRAPPER}} .category-item h3',
				]
			);
			$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .category-item h3, {{WRAPPER}} .category-item h3 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .category-item h3 a:hover, {{WRAPPER}} .category-item:hover h3' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Count
			$this->start_controls_section(
				'section_count_style',
				[
					'label' => esc_html__( 'Count', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'count_margin',
				[
					'label' => __( 'Count Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .category-item span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'count_padding',
				[
					'label' => __( 'Count Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .category-item span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'count_typography',
					'selector' => '{{WRAPPER}} .category-item span',
				]
			);
			$this->add_control(
				'count_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .category-item span' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'count_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .category-item span' => 'background: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Category widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Category query
		$settings = $this->get_settings_for_display();
		$category_style = !empty( $settings['category_style'] ) ? $settings['category_style'] : '';
		$cat_limit = !empty( $settings['cat_limit'] ) ? $settings['cat_limit'] : '';
		$cat_column = !empty( $settings['cat_column'] ) ? $settings['cat_column'] : '';

		if ($category_style === 'two') {
			$style_cls = ' categories-style-two';
		} else {
			$style_cls = '';
		}

		// Columns
		if($cat_column === 'col-3') {
      $col_class = 'nich-col-xl-4 nich-col-lg-4 nich-col-sm-6';
    } elseif($cat_column === 'col-4') {
      $col_class = 'nich-col-xl-3 nich-col-lg-4 nich-col-sm-6';
    } else {
      $col_class = 'nich-col-6 nich-col-md-4 nich-col-lg-3 nich-col-xl-2';
    }

    if($cat_limit !== '') {
      $args['number'] = $cat_limit;
    }

		$pro_cats = get_terms('course_category', array(
							    'hide_empty' => false,
								  'number' => (int)$cat_limit,
								));

    // Turn output buffer on
    ob_start(); ?>
    <div class="naedu-categories<?php echo $style_cls; ?>">
      <div class="nich-row nich-justify-content-center">   
      <?php foreach ($pro_cats as $pro_cat) {
				$thumbnail_id = get_term_meta ( $pro_cat->term_id, 'lp-taxonomy-image-id', true );
        $cat_image    = wp_get_attachment_image_src( $thumbnail_id, 'medium', false, '' );
        $cat_image    = $cat_image[0];

        if($cat_image){
          $cat_image  = $cat_image;
        } else {
          $cat_image  = '';
        }

        $catUrl = get_term_link($pro_cat->term_id); ?>
      	<div class="<?php echo $col_class; ?>">
				<?php if ($category_style === 'two') { ?>
					<div class="category-item">
            <?php if($cat_image){ ?><a href="<?php echo esc_url($catUrl); ?>" class="naedu-icon"><img src="<?php echo esc_url($cat_image); ?>" alt="<?php echo esc_attr($pro_cat->name); ?>"></a><?php } ?>
            <h3><a href="<?php echo esc_url($catUrl); ?>"><?php echo esc_attr($pro_cat->name); ?></a></h3>
          </div>					
				<?php } else { ?>
      		<div class="category-item">
            <span><?php echo esc_attr($pro_cat->count); ?></span>
            <?php if($cat_image){ ?><a href="<?php echo esc_url($catUrl); ?>"><img src="<?php echo esc_url($cat_image); ?>" alt="<?php echo esc_attr($pro_cat->name); ?>"></a><?php } ?>
            <h3><a href="<?php echo esc_url($catUrl); ?>"><?php echo esc_attr($pro_cat->name); ?></a></h3>
          </div>
				<?php } ?>
	      </div>
      <?php } ?>
    	</div>
  	</div>
		<?php
		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Category() );
}
} // enable & disable
