<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Team extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'team';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Team', 'better-el-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	
	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		// start of the Content tab section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'better-elementor-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Team Image
		$this->add_control(
			'better_team_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'label_block' => true,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		// Team Name
		$this->add_control(
        	'better_team_title',
			[
				'label'         => esc_html__('Name', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'John Doe',
			]
		);
		
		// Team Designation
		$this->add_control(
        	'better_team_desg',
			[
				'label'         => esc_html__('Designation', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'Web Developer',
			]
        );

		
		// List Repeater 
		$repeater = new \Elementor\Repeater();

		// Social List
		$repeater->add_control(
        	'better_social_title',
			[
				'label'         => esc_html__('Social Title', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::TEXT,
				'label_block'   => true,
				'default' => 'fa fa-star',
			]
        );

		$repeater->add_control(
        	'better_social_icon',
			[
				'label'         => esc_html__('Social Icon', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::ICON,
				'label_block'   => true,
				'default' => 'fa fa-star',
			]
        );

		// List Group Title 
		$repeater->add_control(
			'better_social_link',
			[
				'label'         => esc_html__('Social Link', 'better-elementor-widgets'),
				'type'          => \Elementor\Controls_Manager::URL,
				'label_block'   => true,
				'default'       => [
					'url'   => '#',
				],
			]
		);

		// Brand Logo List
		$this->add_control(
			'better_social_list',
			[
				'label' => esc_html__( 'Social Profile List', 'better-elementor-widgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'better_social_title' => __( 'Facebook', 'better-elementor-widgets' ),
						'better_social_icon' => 'fab fa-facebook-f',
						'better_social_link' => 'https://www/yourlink.com',
					],
					[
						'better_social_title' => __( 'Twitter', 'better-elementor-widgets' ),
						'better_social_icon' => 'fa fa-twitter',
						'better_social_link' => 'https://www/yourlink.com',
					],
					[
						'better_social_title' => __( 'Linkedin', 'better-elementor-widgets' ),
						'better_social_icon' => 'fa fa-linkedin',
						'better_social_link' => 'https://www/yourlink.com',
					],
					[
						'better_social_title' => __( 'Linkedin', 'better-elementor-widgets' ),
						'better_social_icon' => __( 'Youtube', 'better-elementor-widgets' ),
						'better_social_icon' => 'fa fa-youtube',
						'better_social_link' => 'https://www/yourlink.com',
					],
				],
				'title_field' => '{{{ better_social_title }}}',
			]
		);
		

		$this->end_controls_section();

		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Team Title Options
		$this->add_control(
			'better_team_title_options',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Team Title Color
		$this->add_control(
			'better_team_title_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
				'{{WRAPPER}} .better-team.style-1 .team-hover h4' => 'color: {{VALUE}}',
				],
			]
		);

		// Team Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_team _title_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-team.style-1 .team-hover h4',
			]
		);

		// Team Designation Options
		$this->add_control(
			'better_team_desg_options',
			[
				'label' => esc_html__( 'Designation', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Team Designation Color
		$this->add_control(
			'better_team_designation_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
				'{{WRAPPER}} .better-team.style-1 .team-hover p' => 'color: {{VALUE}}',
				],
			]
		);

		// Team Designation Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_team_designation_typography',
				'label' => esc_html__( 'Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-team.style-1 .team-hover p',
			]
		);

		// Social List Options
		$this->add_control(
			'better_social_list_options',
			[
				'label' => esc_html__( 'Social Link', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Social Icon Color
		$this->add_control(
			'better_social_icon_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#db3157',
				'selectors' => [
				'{{WRAPPER}} .better-team.style-1 .team-social a' => 'color: {{VALUE}}',
				],
			]
		);

		// Social Icon Background Color
		$this->add_control(
			'better_social_icon_background',
			[
				'label' => esc_html__( 'Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
				'{{WRAPPER}} .better-team.style-1 .team-social a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tabs();

		$this->end_controls_section();
		// end of the Style tab section

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	    // get our input from the widget settings.
	    $settings = $this->get_settings_for_display();
	    $better_team_image = esc_url($settings['better_team_image']['url']);
	    $better_team_title = esc_html($settings['better_team_title']);
	    $better_team_desg = esc_html($settings['better_team_desg']);

	    if ($settings['better_social_list']) { ?>
	        <div class="better-team style1">
	            <img src="<?php echo $better_team_image; ?>" alt="">
	            <div class="team-hover">
	                <div class="team-hover-table">
	                    <div class="team-hover-cell">
	                        <h4><?php echo $better_team_title; ?></h4>
	                        <p><?php echo $better_team_desg; ?></p>
	                        <div class="team-social">
	                            <?php
	                            foreach ($settings['better_social_list'] as $item) {
	                                $better_social_title = esc_attr($item['better_social_title']);
	                                $better_social_icon = esc_attr($item['better_social_icon']);
	                                $better_social_link = esc_url($item['better_social_link']['url']);
	                            ?>
	                                <a href="<?php echo $better_social_link; ?>"><i class="<?php echo $better_social_icon; ?>"></i></a>
	                            <?php
	                            }
	                            ?>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	<?php }
	}

}