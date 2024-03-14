<?php
/**
 * Skills widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined( 'ABSPATH' ) || die();

class Skills extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Skill Bars', 'skt-addons-elementor' );
    }

	public function get_custom_help_url() {
		return '#';
	}

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-progress-bar';
    }

    public function get_keywords() {
        return [ 'progress', 'skill', 'bar', 'chart' ];
    }

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
            '_section_skills',
            [
                'label' => __( 'Skills', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'name',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __( 'Name', 'skt-addons-elementor' ),
                'default' => __( 'Design', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type a skill name', 'skt-addons-elementor' ),
            ]
        );

        $repeater->add_control(
            'level',
            [
                'label' => __( 'Level (Out Of 100)', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                    'size' => 95
                ],
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'customize',
            [
                'label' => __( 'Want To Customize?', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'description' => __( 'You can customize this skill bar color from here or customize from Style tab', 'skt-addons-elementor' ),
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-skill-info' => 'color: {{VALUE}};',
                ],
                'condition' => ['customize' => 'yes'],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'level_color',
            [
                'label' => __( 'Level Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .skt-skill-level' => 'background-color: {{VALUE}};',
                ],
                'condition' => ['customize' => 'yes'],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'base_color',
            [
                'label' => __( 'Base Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.skt-skill' => 'background-color: {{VALUE}};',
                ],
                'condition' => ['customize' => 'yes'],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'skills',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '<# print((name || level.size) ? (name || "Skill") + " - " + level.size + level.unit : "Skill - 0%") #>',
                'default' => [
                    [
                        'name' => 'Design',
                        'level' => ['size' => 97, 'unit' => '%']
                    ],
                    [
                        'name' => 'UX',
                        'level' => ['size' => 88, 'unit' => '%']
                    ],
                    [
                        'name' => 'Coding',
                        'level' => ['size' => 92, 'unit' => '%']
                    ],
                    [
                        'name' => 'Speed',
                    ],
                    [
                        'name' => 'Passion',
                        'level' => ['size' => 100, 'unit' => '%']
                    ]
                ]
            ]
        );

        $this->add_control(
            'view',
            [
                'type' => Controls_Manager::SELECT,
                'label' => __( 'Text Position', 'skt-addons-elementor' ),
                'separator' => 'before',
                'default' => 'inside',
                'options' => [
                    'inside' => __( 'Text Inside', 'skt-addons-elementor' ),
                    'outside' => __( 'Text Outside', 'skt-addons-elementor' ),
                ],
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__bars_style_controls();
		$this->__content_style_controls();
	}

    protected function __bars_style_controls() {

		$this->start_controls_section(
            '_section_style_bars',
            [
                'label' => __( 'Skill Bars', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-skill--outside' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-skill--inside' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label' => __( 'Spacing Between', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-skill--outside' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-skill--inside:not(:first-child)' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-skill, {{WRAPPER}} .skt-skill-level' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .skt-skill'
            ]
        );

        $this->end_controls_section();
	}

    protected function __content_style_controls() {

        $this->start_controls_section(
            '_section_content',
            [
                'label' => __( 'Content', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => __( 'Text Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-skill-info' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'level_color',
            [
                'label' => __( 'Level Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-skill-level' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'base_color',
            [
                'label' => __( 'Base Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-skill' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'info_typography',
                'selector' => '{{WRAPPER}} .skt-skill-info',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'info_text_shadow',
                'selector' => '{{WRAPPER}} .skt-skill-info',
            ]
        );

		$this->end_controls_section();
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! is_array( $settings['skills'] ) ) {
            return;
        }

        foreach ( $settings['skills'] as $index => $skill ) :
            $name_key = $this->get_repeater_setting_key( 'name', 'bars', $index );
            $this->add_inline_editing_attributes( $name_key, 'none' );
            $this->add_render_attribute( $name_key, 'class', 'skt-skill-name' );
            ?>
            <div class="skt-skill skt-skill--<?php echo esc_attr( $settings['view'] ); ?> elementor-repeater-item-<?php echo esc_attr($skill['_id']); ?>">
                <div class="skt-skill-level" data-level="<?php echo esc_attr( $skill['level']['size'] ); ?>">
                    <div class="skt-skill-info"><span <?php echo wp_kses_post($this->get_render_attribute_string( $name_key )); ?>><?php echo esc_html( $skill['name'] ); ?></span><span class="skt-skill-level-text"></span></div>
                </div>
            </div>
            <?php
        endforeach;
    }

    protected function content_template() {
        ?>
        <#
        if (_.isArray(settings.skills)) {
            _.each(settings.skills, function(skill, index) {
            var nameKey = view.getRepeaterSettingKey( 'name', 'skills', index);
            view.addInlineEditingAttributes( nameKey, 'none' );
            view.addRenderAttribute( nameKey, 'class', 'skt-skill-name' );
            #>
            <div class="skt-skill skt-skill--{{settings.view}} elementor-repeater-item-{{skill._id}}">
                <div class="skt-skill-level" data-level="{{skill.level.size}}">
                    <div class="skt-skill-info"><span {{{view.getRenderAttributeString( nameKey )}}}>{{skill.name}}</span><span class="skt-skill-level-text"></span></div>
                </div>
            </div>
            <# });
        } #>
        <?php
    }
}