<?php 
namespace UltraAddons\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Work Hour Widget
 * Create Nice Working or Business hour with this Widget. 
 * Credit: B M Rafiul Alam
 * @since 1.1.0.8
 * @package UltraAddons
 * @author Saiful islam <codersaiful@gmail.com>
 * @author Rafiul <bmrafiul.alam@gmail.com>
 */

class Work_Hour extends Base{
	
    /**
     * Get your widget name
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string keywords
     */
    public function get_keywords() {
        return [ 'ultraaddons', 'ua', 'work', 'hour', 'business'];
    }
	 protected function register_controls() {
        //For Content
        $this->work_hour_content();
		 //For Style Section
        $this->work_hour_style(); 
		//For Box Section
        $this->work_hour_box();
		//For Box Section
        $this->work_hour_row();
    }

	/**
	 * Frontend text and values.
	 */
	protected function work_hour_content(){
        $this->start_controls_section(
            'wh_content_tab',
            [
                'label'     => esc_html__( 'Content', 'ultraaddons' ),
            ]
        );
        $this->add_control(
			'_ua_wh_title', [
				'label' => __( 'Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Office Hour' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		 $this->add_control(
			'_ua_wh_sub_title', [
				'label' => __( 'Sub Title', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Add your work hour sub title' , 'ultraaddons' ),
				'label_block' => true,
			]
		);
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'_ua_wh_day', [
				'label' => __( 'Day', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Monday' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'_ua_wh_start_time', [
				'label' => __( 'Start Time', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '08:00' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'_ua_wh_end_time', [
				'label' => __( 'End Time', 'ultraaddons' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '19:00' , 'ultraaddons' ),
				'label_block' => false,
			]
		);
		$repeater->add_control(
			'_ua_wh_closed',
			[
				'label' => __( 'Closed?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_control(
			'_ua_wh_day_highlight',
			[
				'label' => __( 'Highlight?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ua_wh_list_shadow',
				'label' => __( 'Highlight Day', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.ua-work-hours-row',
				'condition' =>[
					'_ua_wh_day_highlight' =>'yes'
				],
			]
		);
		$repeater->add_control(
			'_ua_wh_bg_color', [
				'label' => __( 'Background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#ddd',
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.ua-work-hours-row.highlight' => 'background: {{VALUE}} !important;',
				],
			]
        );
		$repeater->add_control(
			'_ua_wh_text_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.ua-work-hours-row .ua-work-day' => 'color: {{VALUE}};',
						'{{WRAPPER}} {{CURRENT_ITEM}}.ua-work-hours-row .ua-work-timing' => 'color: {{VALUE}};',
				],
			]
        );
		
		$this->add_control(
			'_ua_wh_list',
			[
				'label' => __( 'Work Hour List', 'ultraaddons' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'_ua_wh_day' => __( 'Monday', 'ultraaddons' ),
					],
					[
						'_ua_wh_day' => __( 'Tuesday', 'ultraaddons' ),
					],
					[
						'_ua_wh_day' => __( 'Wednesday', 'ultraaddons' ),
					],
					[
						'_ua_wh_day' => __( 'Thursday', 'ultraaddons' ),
					],
					[
						'_ua_wh_day' => __( 'Friday', 'ultraaddons' ),
					],
					[
						'_ua_wh_day' => __( 'Saturady', 'ultraaddons' ),
					],
					[
						'_ua_wh_day' => __( 'Sunday', 'ultraaddons' ),
					],
				],
				'title_field' => '{{{ _ua_wh_day }}}',
			]
		);
		$this->add_control(
			'_ua_wh_day_format',
			[
				'label' => __( '24 Hours Format?', 'ultraaddons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ultraaddons' ),
				'label_off' => __( 'No', 'ultraaddons' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->end_controls_section();
    }
	/**
	 * Style.
	 */
	protected function work_hour_style(){
		$this->start_controls_section(
            'wh_style',
            [
                'label'     => esc_html__( 'Work Hours', 'ultraaddons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'wh_title_typography',
					'label' => 'Title Typography',
					'selector' => '{{WRAPPER}} .ua-work-hour-title',
			]
        );
		
		$this->add_control(
			'wh_title_color', [
				'label' => __( 'Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
					'{{WRAPPER}} .ua-work-hour-title' => 'color: {{VALUE}};',
				],
			]
        );
		$this->add_responsive_control(
			'_ua_wh_title_margin',
			[
				'label'       => esc_html__( 'Title Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-work-hour-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);
		$this->add_responsive_control(
			'_ua_wh_title_alignment',
			[
				'label' => esc_html__( 'Alignment', 'ultraaddons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'ultraaddons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'ultraaddons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'ultraaddons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'justify', 'ultraaddons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .ua-work-hour-title, .ua-work-hour-sub-title' => 'text-align: {{VALUE}};',
		
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'wh_sub_title_typography',
					'label' => 'Sub Title Typography',
					'selector' => '{{WRAPPER}} .ua-work-hour-sub-title',
			]
        );
		$this->add_control(
			'wh_sub_title_color', [
				'label' => __( 'Sub Title Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
					'{{WRAPPER}} .ua-work-hour-sub-title' => 'color: {{VALUE}};',
				],
			]
        );
		$this->add_responsive_control(
			'_ua_wh_sub_title_margin',
			[
				'label'       => esc_html__( 'Sub Title Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-work-hour-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);
		$this->add_control(
			'wh_content_color', [
				'label' => __( 'Content Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'default'=>'#333',
				'selectors' => [
					'{{WRAPPER}} .ua-work-hours-row' => 'color: {{VALUE}};',
				],
			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'wh_row_typography',
					'label' => 'Row Typography',
					'selector' => '{{WRAPPER}} .ua-work-hours-row',

			]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
					'name' => 'wh_highlight_row_typography',
					'label' => 'Highlight Typography',
					'selector' => '{{WRAPPER}} .ua-work-hours-row.highlight',
					'condition' =>[
					'_ua_wh_day_highlight' =>'yes'
				],

			]
        );
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => '_ua_wh_row_border',
				'label' => __( 'Row Border', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-work-hours-row',
			]
		);
		$this->add_responsive_control(
			'_ua_wh_row_margin',
			[
				'label'       => esc_html__( 'Row Margin', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-work-hours-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);
		$this->end_controls_section();
	}
	/**
	 * Style.
	 */
	protected function work_hour_box(){
		$this->start_controls_section(
            'wh_box',
            [
                'label'     => esc_html__( 'Box', 'ultraaddons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'wh_box_bg', [
				'label' => __( 'Box background Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ua-work-hours' => 'background-color: {{VALUE}};',
				],
			]
        );
		$this->add_responsive_control(
			'_ua_wh_box_radius',
			[
				'label'       => esc_html__( 'Box Radius', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-work-hours' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'_ua_wh_box_margin',
			[
				'label'       => esc_html__( 'Box Padding', 'ultraaddons' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .ua-work-hours' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => '_ua_wh_box_shadow',
				'label' => __( 'Box Shadow', 'ultraaddons' ),
				'selector' => '{{WRAPPER}} .ua-work-hours',
			]
		);
		
		$this->end_controls_section();
	}
	/**
	 * Style.
	 */
	protected function work_hour_row(){
		$this->start_controls_section(
            'wh_row_style',
            [
                'label'     => esc_html__( 'Row', 'ultraaddons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->start_controls_tabs(
			'style_tabs'
		);
		/**
		 * Normal tab
		 */
		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => __( 'Normal', 'ultraaddons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
				[
					'name' => 'row_background',
					'label' => __( 'Row Background', 'ultraaddons' ),
					'types' => [ 'classic', 'gradient'],
					'exclude' => [ 'image' ],
					'selector' => '{{WRAPPER}} .ua-work-hours-row',
				]
			);
		
		$this->end_controls_tab();
		/**
		 * Button Hover tab
		 */
		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'ultraaddons' ),
			]
		);
		$this->add_group_control(
		Group_Control_Background::get_type(),
			[
				'name' => 'row_background_hover',
				'label' => __( 'Row Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ua-work-hours-row:hover',
			]
		);
		$this->add_control(
			'_ua_row_hover_text_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-work-hours-row:hover' => 'color: {{VALUE}};',
				],
			]
        );
		$this->end_controls_tabs();
		
		/*Odd-Even Row*/
		$this->start_controls_tabs(
			'odd_even_tabs'
		);
		/**
		 * Odd tab
		 */
		$this->start_controls_tab(
			'style_odd_tab',
			[
				'label' => __( 'Odd', 'ultraaddons' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
				[
					'name' => 'odd_row_background',
					'label' => __( 'Row Background', 'ultraaddons' ),
					'types' => [ 'classic', 'gradient'],
					'exclude' => [ 'image' ],
					'selector' => '{{WRAPPER}} .ua-work-hours-row.odd-row',
				]
			);
			$this->add_control(
			'_ua_row_odd_text_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-work-hours-row.odd-row' => 'color: {{VALUE}};',
				],
			]
        );
		
		$this->end_controls_tab();
		/**
		 * Even tab
		 */
		$this->start_controls_tab(
			'style_even_tab',
			[
				'label' => __( 'Even', 'ultraaddons' ),
			]
		);
		$this->add_group_control(
		Group_Control_Background::get_type(),
			[
				'name' => 'even_row_background',
				'label' => __( 'Even Row Background', 'ultraaddons' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .ua-work-hours-row.even-row',
			]
		);
		$this->add_control(
			'_ua_row_even_text_color', [
				'label' => __( 'Text Color', 'ultraaddons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .ua-work-hours-row.even-row' => 'color: {{VALUE}};',
				],
			]
        );
		$this->end_controls_tabs();
		
		$this->end_controls_section();
	}
	
	/**
	 * Render Methods
	 */
    protected function render() {
	$settings =	$this->get_settings_for_display();
	?>
	 <div class="ua-work-hours">
		 <h2 class="ua-work-hour-title"> <?php echo $settings['_ua_wh_title']; ?></h2>
		 <span class="ua-work-hour-sub-title"><?php echo $settings['_ua_wh_sub_title']; ?></span>
	 <?php
	 if ( isset( $settings['_ua_wh_list'] ) ) :
		$count = 0;
		 foreach (  $settings['_ua_wh_list'] as $item ) :
		 $dayHighlight 	= ($item['_ua_wh_day_highlight']=='yes') ? 'highlight' : '';
		 $count 		= $count+1;
		 $rowNumber 	= ($count % 2 == 0) ? 'even-row' : 'odd-row';
		 $row_class 	= $rowNumber . ' '. $dayHighlight;
		?>
		<div class="ua-work-hours-row elementor-repeater-item-<?php echo $item['_id']; ?> <?php echo $row_class;?>">
			<span class="ua-work-day">
			<?php echo $item['_ua_wh_day'];?>
			</span>
			<?php
			if($item['_ua_wh_closed']!='yes'){?>
			<span class="ua-work-timing">
				<?php
				$start_time = strtotime($item['_ua_wh_start_time']);
				$end_time 	= strtotime($item['_ua_wh_end_time']);
				/**
				 * Checking condition format 24 hrs/12 hrs.
				 */
				$time_format = ($settings['_ua_wh_day_format'] =='yes')
				? date("H:i", $start_time) . " - " .  date("H:i", $end_time) 
				: date("h:i A", $start_time) . " - " .  date("h:i A", $end_time);
				echo $time_format;
				?>
			</span>
			<?php }else{?>
			<span class="ua-work-timing closed">
			<?php echo $item['_ua_wh_closed']? 'Closed': '' ; ?>
			</span>
			<?php } ?>
		</div>
	<?php 
	endforeach;
	endif;
	?>
	</div>
	<?php }
}