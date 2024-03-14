<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Reading_Time_Post extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-reading-time-post';
	}

	public function get_title() {
		return esc_html__( 'Reading Time Post', 'thim-elementor-kit' ); 
	}

	public function get_icon() {
		return 'thim-eicon eicon-history';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_POST );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			)
		);
        $this->add_control(
			'reading_layout',
			array(
				'label'   => esc_html__( 'Style Reading', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'times',
				'options' => array(
					'times'  => esc_html__( 'Times', 'thim-elementor-kit' ),
					'progress' => esc_html__( 'Progress Indicator', 'thim-elementor-kit' ),
				),
                
			)
		);  
        $this->add_control(
			'results_text',
			array(
				'label'       => esc_html__( 'Results Text', 'thim-elementor-kit' ), 
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => 'you need {times} read',
                'description' => esc_html__( 'you use this {times} format to show times', 'thim-elementor-kit' ),
                'condition' => array(
					'reading_layout' => 'times',
				),
			)
		);
		$this->end_controls_section();
        $this->_register_style_times_sc();
        $this->_register_style_progress_sc();
	}
    protected function _register_style_times_sc(){ 
        $this->start_controls_section(
			'section_times_style',
			array(
				'label' => esc_html__( 'Times', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => array(
					'reading_layout' => 'times',
				),
			)
		); 
        $this->add_control(
            'heading_time_style',
            array(
                'label'     => esc_html__('Time', 'thim-elementor-kit'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'time_color',
            array(
                'label'     => esc_html__('Color', 'thim-elementor-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    'body {{WRAPPER}} .number-time' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'time_typography',
                'selector' => 'body {{WRAPPER}} .number-time',
            )
        );
        $this->add_control(
            'heading_label_style',
            array(
                'label'     => esc_html__('Text', 'thim-elementor-kit'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'label_color',
            array(
                'label'     => esc_html__('Color', 'thim-elementor-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    'body {{WRAPPER}} .thim-kits-reading-time' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'label_typography',
                'selector' => 'body {{WRAPPER}} .thim-kits-reading-time',
            )
        );
        $this->end_controls_section();
    }
    protected function _register_style_progress_sc(){ 
        $this->start_controls_section(
			'section_progress_style',
			array(
				'label' => esc_html__( 'Progress', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => array(
					'reading_layout' => 'progress',
				),
			)
		); 
        $this->add_responsive_control(
			'progress_height',
			array(
				'label'          => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array('px'),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .reading-progress' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		); 
        $this->add_control(
            'progress_color',
            array(
                'label'     => esc_html__('Color', 'thim-elementor-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    'body {{WRAPPER}} .reading-progress' => '--thim-kits-progress-color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'progress_background',
            array(
                'label'     => esc_html__('Background', 'thim-elementor-kit'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    'body {{WRAPPER}} .reading-progress' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->end_controls_section();
    }
	public function render() { 
		do_action( 'thim-ekit/modules/single-post/before-preview-query' ); 

		$settings = $this->get_settings_for_display();
		?>

		<div class="thim-ekit-single-reading_time-post">
			<?php 
            if($settings['reading_layout'] == 'times'){ ?>
                <div class="thim-kits-reading-time">
                    <span class="thim-kits-time" data-results="<?php echo $settings['results_text'];?>"></span> 
                </div>
            <?php
            }else{
                echo '<progress class="reading-progress thim-ekits-js-progress"  value="0" max="100"></progress>';
            }
            ?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-post/after-preview-query' );
	}
}
