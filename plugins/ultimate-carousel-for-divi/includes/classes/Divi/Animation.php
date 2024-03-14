<?php
namespace WPT\UltimateDiviCarousel\Divi;

/**
 * Animation.
 */
class Animation
{
    protected $container;

    /**
     * Constructor.
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function add_field($args)
    {
        $module      = $args['module'];
        $toggle_slug = $args['toggle_slug'];
        $tab_slug    = $args['tab_slug'];
        $prefix      = $args['prefix'] . '_';
        $priority    = $args['priority'] - 1;

        // Cache results so that translation/escaping only happens once.
        $i18n = [];
        if (!isset($i18n[$prefix . 'animation'])) {
            // phpcs:disable WordPress.WP.I18n.MissingTranslatorsComment
            $i18n[$prefix . 'animation'] = [
                'toggle'    => [
                    'title' => esc_html__('Animation', 'ultimate-carousel-for-divi'),
                ],
                'style'     => [
                    'label'       => esc_html__('Animation Style', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('Pick an animation style to enable animations for this element. Once enabled, you will be able to customize your animation style further. To disable animations, choose the None option.', 'ultimate-carousel-for-divi'),
                    'options'     => [
                        'fade'   => et_builder_i18n('Fade'),
                        'slide'  => et_builder_i18n('Slide'),
                        'bounce' => esc_html__('Bounce', 'ultimate-carousel-for-divi'),
                        'zoom'   => esc_html__('Zoom', 'ultimate-carousel-for-divi'),
                        'flip'   => et_builder_i18n('Flip'),
                        'fold'   => esc_html__('Fold', 'ultimate-carousel-for-divi'),
                        'roll'   => esc_html__('Roll', 'ultimate-carousel-for-divi'),
                    ],
                ],
                'direction' => [
                    'label'       => esc_html__('Animation Direction', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('Pick from up to five different animation directions, each of which will adjust the starting and ending position of your animated element.', 'ultimate-carousel-for-divi'),
                ],
                'duration'  => [
                    'label'       => esc_html__('Animation Duration', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('Speed up or slow down your animation by adjusting the animation duration. Units are in milliseconds and the default animation duration is one second.', 'ultimate-carousel-for-divi'),
                ],
                'delay'     => [
                    'label'       => esc_html__('Animation Delay', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('If you would like to add a delay before your animation runs you can designate that delay here in milliseconds. This can be useful when using multiple animated modules together.', 'ultimate-carousel-for-divi'),
                ],
                'opacity'   => [
                    'label'       => esc_html__('Animation Starting Opacity', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('By increasing the starting opacity, you can reduce or remove the fade effect that is applied to all animation styles.', 'ultimate-carousel-for-divi'),
                ],
                'speed'     => [
                    'label'       => esc_html__('Animation Speed Curve', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('Here you can adjust the easing method of your animation. Easing your animation in and out will create a smoother effect when compared to a linear speed curve.', 'ultimate-carousel-for-divi'),
                ],
                'repeat'    => [
                    'label'       => esc_html__('Animation Repeat', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('By default, animations will only play once. If you would like to loop your animation continuously you can choose the Loop option here.', 'ultimate-carousel-for-divi'),
                    'options'     => [
                        'once' => esc_html__('Once', 'ultimate-carousel-for-divi'),
                        'loop' => esc_html__('Loop', 'ultimate-carousel-for-divi'),
                    ],
                ],
                'menu'      => [
                    'label'       => esc_html__('Dropdown Menu Animation', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('Select an animation to be used when dropdown menus appear. Dropdown menus appear when hovering over links with sub items.', 'ultimate-carousel-for-divi'),
                ],
                'intensity' => [
                    'label'       => esc_html__('Animation Intensity', 'ultimate-carousel-for-divi'),
                    'description' => esc_html__('Intensity effects how subtle or aggressive your animation will be. Lowering the intensity will create a smoother and more subtle animation while increasing the intensity will create a snappier more aggressive animation.', 'ultimate-carousel-for-divi'),
                ],
            ];
            // phpcs:enable
        }

        $additional_options          = [];
        $animations_intensity_fields = [
            $prefix . 'animation_intensity_slide',
            $prefix . 'animation_intensity_zoom',
            $prefix . 'animation_intensity_flip',
            $prefix . 'animation_intensity_fold',
            $prefix . 'animation_intensity_roll',
        ];

        $additional_options[$prefix . 'animation_style'] = [
            'label'           => $i18n[$prefix . 'animation']['style']['label'],
            'description'     => $i18n[$prefix . 'animation']['style']['description'],
            'type'            => 'select_animation',
            'option_category' => 'configuration',
            'default'         => 'none',
            'options'         => [
                'none'   => et_builder_i18n('None'),
                'fade'   => $i18n[$prefix . 'animation']['style']['options']['fade'],
                'slide'  => $i18n[$prefix . 'animation']['style']['options']['slide'],
                'bounce' => $i18n[$prefix . 'animation']['style']['options']['bounce'],
                'zoom'   => $i18n[$prefix . 'animation']['style']['options']['zoom'],
                'flip'   => $i18n[$prefix . 'animation']['style']['options']['flip'],
                'fold'   => $i18n[$prefix . 'animation']['style']['options']['fold'],
                'roll'   => $i18n[$prefix . 'animation']['style']['options']['roll'],
            ],
            'tab_slug'        => $tab_slug,
            'toggle_slug'     => $toggle_slug,
            'priority'        => ++$priority,
            'affects'         => array_merge(
                [
                    $prefix . 'animation_repeat',
                    $prefix . 'animation_direction',
                    $prefix . 'animation_duration',
                    $prefix . 'animation_delay',
                    $prefix . 'animation_starting_opacity',
                    $prefix . 'animation_speed_curve',
                ],
                $animations_intensity_fields
            ),
        ];

        $additional_options[$prefix . 'animation_direction'] = [
            'label'               => $i18n[$prefix . 'animation']['direction']['label'],
            'description'         => $i18n[$prefix . 'animation']['direction']['description'],
            'type'                => 'select',
            'option_category'     => 'configuration',
            'default'             => 'center',
            'options'             => [
                'center' => et_builder_i18n('Center'),
                'left'   => et_builder_i18n('Right'),
                'right'  => et_builder_i18n('Left'),
                'bottom' => et_builder_i18n('Up'),
                'top'    => et_builder_i18n('Down'),
            ],
            'tab_slug'            => $tab_slug,
            'toggle_slug'         => $toggle_slug,
            'priority'            => ++$priority,
            'depends_show_if_not' => ['none', 'fade'],
            'mobile_options'      => true,
        ];

        $additional_options[$prefix . 'animation_duration'] = [
            'label'               => $i18n[$prefix . 'animation']['duration']['label'],
            'description'         => $i18n[$prefix . 'animation']['duration']['description'],
            'type'                => 'range',
            'option_category'     => 'configuration',
            'range_settings'      => [
                'min'  => 0,
                'max'  => 2000,
                'step' => 50,
            ],
            'default'             => '1000ms',
            'validate_unit'       => true,
            'fixed_unit'          => 'ms',
            'fixed_range'         => true,
            'tab_slug'            => $tab_slug,
            'toggle_slug'         => $toggle_slug,
            'priority'            => ++$priority,
            'depends_show_if_not' => 'none',
            'reset_animation'     => true,
            'mobile_options'      => true,
        ];

        $additional_options[$prefix . 'animation_delay'] = [
            'label'               => $i18n[$prefix . 'animation']['delay']['label'],
            'description'         => $i18n[$prefix . 'animation']['delay']['description'],
            'type'                => 'range',
            'option_category'     => 'configuration',
            'range_settings'      => [
                'min'  => 0,
                'max'  => 3000,
                'step' => 50,
            ],
            'default'             => '0ms',
            'validate_unit'       => true,
            'fixed_unit'          => 'ms',
            'fixed_range'         => true,
            'tab_slug'            => $tab_slug,
            'toggle_slug'         => $toggle_slug,
            'priority'            => ++$priority,
            'depends_show_if_not' => 'none',
            'reset_animation'     => true,
            'mobile_options'      => true,
        ];

        foreach ($animations_intensity_fields as $animations_intensity_field) {
            $animation_style = str_replace($prefix . 'animation_intensity_', '', $animations_intensity_field);

            $additional_options[$animations_intensity_field] = [
                'label'           => $i18n[$prefix . 'animation']['intensity']['label'],
                'description'     => $i18n[$prefix . 'animation']['intensity']['description'],
                'type'            => 'range',
                'option_category' => 'configuration',
                'range_settings'  => [
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 1,
                ],
                'default'         => '50%',
                'validate_unit'   => true,
                'fixed_unit'      => '%',
                'fixed_range'     => true,
                'tab_slug'        => $tab_slug,
                'toggle_slug'     => $toggle_slug,
                'depends_show_if' => $animation_style,
                'priority'        => ++$priority,
                'reset_animation' => true,
                'mobile_options'  => true,
            ];
        }

        $additional_options[$prefix . 'animation_starting_opacity'] = [
            'label'               => $i18n[$prefix . 'animation']['opacity']['label'],
            'description'         => $i18n[$prefix . 'animation']['opacity']['description'],
            'type'                => 'range',
            'option_category'     => 'configuration',
            'range_settings'      => [
                'min'       => 0,
                'max'       => 100,
                'step'      => 1,
                'min_limit' => 0,
                'max_limit' => 100,
            ],
            'default'             => '0%',
            'validate_unit'       => true,
            'fixed_unit'          => '%',
            'fixed_range'         => true,
            'tab_slug'            => $tab_slug,
            'toggle_slug'         => $toggle_slug,
            'priority'            => ++$priority,
            'depends_show_if_not' => 'none',
            'reset_animation'     => true,
            'mobile_options'      => true,
        ];

        $additional_options[$prefix . 'animation_speed_curve'] = [
            'label'               => $i18n[$prefix . 'animation']['speed']['label'],
            'description'         => $i18n[$prefix . 'animation']['speed']['description'],
            'type'                => 'select',
            'option_category'     => 'configuration',
            'default'             => 'ease-in-out',
            'options'             => [
                'ease-in-out' => et_builder_i18n('Ease-In-Out'),
                'ease'        => et_builder_i18n('Ease'),
                'ease-in'     => et_builder_i18n('Ease-In'),
                'ease-out'    => et_builder_i18n('Ease-Out'),
                'linear'      => et_builder_i18n('Linear'),
            ],
            'tab_slug'            => $tab_slug,
            'toggle_slug'         => $toggle_slug,
            'priority'            => ++$priority,
            'depends_show_if_not' => 'none',
            'mobile_options'      => true,
        ];

        $additional_options[$prefix . 'animation_repeat'] = [
            'label'               => $i18n[$prefix . 'animation']['repeat']['label'],
            'description'         => $i18n[$prefix . 'animation']['repeat']['description'],
            'type'                => 'select',
            'option_category'     => 'configuration',
            'default'             => 'once',
            'options'             => [
                'once' => $i18n[$prefix . 'animation']['repeat']['options']['once'],
                'loop' => $i18n[$prefix . 'animation']['repeat']['options']['loop'],
            ],
            'tab_slug'            => $tab_slug,
            'toggle_slug'         => $toggle_slug,
            'priority'            => ++$priority,
            'depends_show_if_not' => 'none',
            'mobile_options'      => true,
        ];

        return $additional_options;
    }

    public function process_animation_style($args)
    {
        $module       = $args['module'];
        $prefix       = $args['prefix'] . '_';
        $props        = $module->props;
        $module_class = $args['module_class'];

        // Animation Styles.
        $animation_style            = isset($props[$prefix . 'animation_style']) && '' !== $props[$prefix . 'animation_style'] ? $props[$prefix . 'animation_style'] : false;
        $animation_repeat           = isset($props[$prefix . 'animation_repeat']) && '' !== $props[$prefix . 'animation_repeat'] ? $props[$prefix . 'animation_repeat'] : 'once';
        $animation_direction        = isset($props[$prefix . 'animation_direction']) && '' !== $props[$prefix . 'animation_direction'] ? $props[$prefix . 'animation_direction'] : 'center';
        $animation_duration         = isset($props[$prefix . 'animation_duration']) && '' !== $props[$prefix . 'animation_duration'] ? $props[$prefix . 'animation_duration'] : '500ms';
        $animation_delay            = isset($props[$prefix . 'animation_delay']) && '' !== $props[$prefix . 'animation_delay'] ? $props[$prefix . 'animation_delay'] : '0ms';
        $animation_intensity        = isset($props[$prefix . "animation_intensity_{$animation_style}"]) && '' !== $props[$prefix . "animation_intensity_{$animation_style}"] ? $props[$prefix . "animation_intensity_{$animation_style}"] : '50%';
        $animation_starting_opacity = isset($props[$prefix . 'animation_starting_opacity']) && '' !== $props[$prefix . 'animation_starting_opacity'] ? $props[$prefix . 'animation_starting_opacity'] : '0%';
        $animation_speed_curve      = isset($props[$prefix . 'animation_speed_curve']) && '' !== $props[$prefix . 'animation_speed_curve'] ? $props[$prefix . 'animation_speed_curve'] : 'ease-in-out';

        // Animation style and direction values for Tablet & Phone. Basically, style for tablet and
        // phone are same with the desktop because we only edit responsive settings for the affected
        // fields under animation style. Variable $animation_style_responsive need to be kept as
        // unmodified variable because it will be used by animation intensity.
        $animation_style_responsive = $animation_style;
        $animation_style_tablet     = $animation_style;
        $animation_style_phone      = $animation_style;
        $animation_direction_tablet = et_pb_responsive_options()->get_any_value($props, $prefix . 'animation_direction_tablet');
        $animation_direction_phone  = et_pb_responsive_options()->get_any_value($props, $prefix . 'animation_direction_phone');

        if ($animation_style && 'none' !== $animation_style && !wp_doing_ajax()) {
            $transformed_animations = [
                'desktop' => false,
                'tablet'  => false,
                'phone'   => false,
            ];
            // Fade doesn't have direction.
            if ('fade' === $animation_style) {
                $animation_direction_tablet = '';
                $animation_direction_phone  = '';
            } else {
                $directions_list = ['top', 'right', 'bottom', 'left'];
                if (in_array($animation_direction, $directions_list, true)) {
                    $animation_style .= ucfirst($animation_direction);
                }

                foreach (preg_grep('/(transform_)/', array_keys($props)) as $index => $key) {
                    if (strpos($key, 'link') !== false || strpos($key, 'hover') !== false || strpos($key, 'last_edited') !== false) {
                        continue;
                    }

                    if (!empty($props[$key])) {
                        if (!$transformed_animations['desktop'] && strpos($key, 'tablet') === false && strpos($key, 'phone') === false) {
                            $transformed_animations['desktop'] = true;
                            $transformed_animations['tablet']  = true;
                            $transformed_animations['phone']   = true;
                        } elseif (!$transformed_animations['tablet'] && strpos($key, 'tablet') !== false) {
                            $transformed_animations['tablet'] = true;
                            $transformed_animations['phone']  = true;
                        } elseif (!$transformed_animations['phone'] && strpos($key, 'phone') !== false) {
                            $transformed_animations['phone'] = true;
                        }

                        if ($transformed_animations['desktop'] && $transformed_animations['tablet'] && $transformed_animations['phone']) {
                            break;
                        }
                    }
                }

            }

            if ($module_class) {
                // Desktop animation data.
                $animation_data = [
                    'class'            => esc_attr(trim($module_class)),
                    'style'            => esc_html($animation_style),
                    'repeat'           => esc_html($animation_repeat),
                    'duration'         => esc_html($animation_duration),
                    'delay'            => esc_html($animation_delay),
                    'intensity'        => esc_html($animation_intensity),
                    'starting_opacity' => esc_html($animation_starting_opacity),
                    'speed_curve'      => esc_html($animation_speed_curve),
                ];

                // Being save to generate Tablet & Phone data attributes. As default, tablet
                // default value will inherit desktop value and phone default value will inherit
                // tablet value. Ensure to pass the value only if it's different compared to
                // desktop value to avoid duplicate values.
                $animation_attributes = [
                    'repeat'           => 'animation_repeat',
                    'duration'         => 'animation_duration',
                    'delay'            => 'animation_delay',
                    'intensity'        => "animation_intensity_{$animation_style_responsive}",
                    'starting_opacity' => 'animation_starting_opacity',
                    'speed_curve'      => 'animation_speed_curve',
                ];

                foreach ($animation_attributes as $animation_key => $animation_attribute) {
                    $animation_attribute_tablet = '';
                    $animation_attribute_phone  = '';

                    // Ensure responsive status for current attribute is activated.
                    if (!et_pb_responsive_options()->is_responsive_enabled($props, $animation_attribute)) {
                        continue;
                    }

                    // Tablet animation value.
                    $animation_attribute_tablet = et_pb_responsive_options()->get_any_value($props, "{$animation_attribute}_tablet", $animation_data[$animation_key]);
                    if (!empty($animation_attribute_tablet)) {
                        $animation_data["{$animation_key}_tablet"] = $animation_attribute_tablet;
                    }

                    // Phone animation value.
                    $animation_attribute_phone = et_pb_responsive_options()->get_any_value($props, "{$animation_attribute}_phone", $animation_data[$animation_key]);
                    if (!empty($animation_attribute_phone)) {
                        $animation_data["{$animation_key}_phone"] = $animation_attribute_phone;
                    }
                }

                // Animation style is little bit different. We need to check the direction to get
                // the correct style. We need to ensure the direction is valid, then add it as
                // suffix for the animation style.
                if (et_pb_responsive_options()->is_responsive_enabled($props, 'animation_direction')) {
                    // Tablet animation style.
                    if (!empty($animation_direction_tablet)) {
                        $animation_style_tablet_suffix  = in_array($animation_direction_tablet, $directions_list, true) ? ucfirst($animation_direction_tablet) : '';
                        $animation_data['style_tablet'] = $animation_style_tablet . $animation_style_tablet_suffix;
                    }

                    // Phone animation style.
                    if (!empty($animation_direction_phone)) {
                        $animation_style_phone_suffix  = in_array($animation_direction_phone, $directions_list, true) ? ucfirst($animation_direction_phone) : '';
                        $animation_data['style_phone'] = $animation_style_phone . $animation_style_phone_suffix;
                    } elseif (!empty($animation_data['style_tablet'])) {
                        $animation_data['style_phone'] = $animation_data['style_tablet'];
                    }
                }

                // overwrite animation name to match the custom animation generated on transforms options processing.
                if ($transformed_animations['desktop']) {
                    $animation_data['style'] = 'transformAnim';
                }
                if ($transformed_animations['tablet']) {
                    $animation_data['style_tablet'] = 'transformAnim';
                }
                if ($transformed_animations['phone']) {
                    $animation_data['style_phone'] = 'transformAnim';
                }

                et_builder_handle_animation_data($animation_data);
            }

            // Only print et_animated on front-end. Avoid adding it on computed callback of post slider(s)
            // and modules because it'll cause the module to be visually hidden.
            if (!et_core_is_fb_enabled()) {
                $module->props['is_title_animated'] = true;
            }

        }
    }

}
