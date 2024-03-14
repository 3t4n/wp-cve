<?php

class Elementor_Widget_miga_slide_everything extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        wp_register_script(
            "miga_slide_everything_scripts",
            plugins_url("../scripts/main.js", __FILE__),
            [],
            "1.0.0",
            true
        );
    }

    public function get_name()
    {
        return "miga_slide_everything_title";
    }

    public function get_title()
    {
        return __("Slide everything", "miga_slide_everything");
    }

    public function get_icon()
    {
        return "eicon-slides";
    }

    public function get_categories()
    {
        return ["general"];
    }

    protected function _register_controls()
    {
        $this->start_controls_section("sec1", [
            "label" => __("Settings", "miga_slide_everything"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control("sliderId", [
            "label" => esc_html__("Slider ID", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::TEXT,
            "default" => esc_html__("", "miga_slide_everything"),
            "placeholder" => esc_html__(
                "Type your #id here",
                "miga_slide_everything"
            ),
        ]);

        $this->add_control("endless_loop", [
            "label" => esc_html__("Loop slider", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);

        $this->add_control("automatic_slider", [
            "label" => esc_html__("Autoplay", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);
        $this->add_control("autoplay_delay", [
            "label" => esc_html__(
                "Autoplay delay (ms)",
                "miga_slide_everything"
            ),
            "type" => \Elementor\Controls_Manager::SLIDER,
            "size_units" => ["px"],
            "range" => [
                "px" => [
                    "min" => 100,
                    "max" => 10000,
                    "step" => 100,
                ],
            ],
            "default" => [
                "unit" => "px",
                "size" => 3000,
            ],
            "selectors" => ["none"],
            "condition" => ["automatic_slider" => "yes"],
        ]);

        $this->add_control("center_slides", [
            "label" => esc_html__("Center slides", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);
        $this->add_control("pagination", [
            "label" => esc_html__("Show pagination", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);

        $this->add_control(
            'dot_padding',
            [
                'label' => esc_html__('Pagination position', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_control("mousewheel", [
            "label" => esc_html__("Mouse wheel", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);
        $this->add_control("arrows", [
            "label" => esc_html__("Show arrows", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);

        $this->add_control("hr_method", ["type" => \Elementor\Controls_Manager::DIVIDER ]);

        $this->add_control("newmethod", [
            "label" => esc_html__("Keep child structure", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
                    'description' => esc_html__('Adds a <div> around each slide to keep Elementor settings.', 'miga_slide_everything'),
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);



        $this->add_control("hr1", ["type" => \Elementor\Controls_Manager::DIVIDER ]);

        $this->add_control("slidesPerView", [
            "label" => esc_html__("Slides per View", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::NUMBER,
            "min" => 1,
            "max" => 50,
            "step" => 1,
            "default" => 4,
        ]);
        $this->add_control("slidesPerViewTablet", [
            "label" => esc_html__(
                "Slides per View (Tablet)",
                "miga_slide_everything"
            ),
            "type" => \Elementor\Controls_Manager::NUMBER,
            "min" => 1,
            "max" => 50,
            "step" => 1,
            "default" => 2,
        ]);
        $this->add_control("slidesPerViewPhone", [
            "label" => esc_html__(
                "Slides per View (Phone)",
                "miga_slide_everything"
            ),
            "type" => \Elementor\Controls_Manager::NUMBER,
            "min" => 1,
            "max" => 50,
            "step" => 1,
            "default" => 1,
        ]);

        $this->add_control("hr2", [
            "type" => \Elementor\Controls_Manager::DIVIDER,
        ]);

        $this->add_control("spaceBetween", [
            "label" => esc_html__(
                "Space between slides",
                "miga_slide_everything"
            ),
            "type" => \Elementor\Controls_Manager::NUMBER,
            "min" => 1,
            "max" => 100,
            "step" => 1,
            "default" => 30,
        ]);


        $this->add_control("hr_note1", [ "type" => \Elementor\Controls_Manager::DIVIDER ]);
        $this->add_control("info_note1", [
                    "type" => \Elementor\Controls_Manager::RAW_HTML,
                    "label" => "",
                    "raw" => __(
                        "Slideshow is only visible in the preview/final page, not in the editor.\nStyle arrows/pagination with custom CSS.",
                        "miga_slide_everything"
                    ),
                ]);
        $this->add_control("hr_note2", [ "type" => \Elementor\Controls_Manager::DIVIDER ]);
        $this->add_control("info_note2", [
                    "type" => \Elementor\Controls_Manager::RAW_HTML,
                    "label" => "",
                    "raw" => __(
                        "For best results use a full width container and set overflow and width as needed.",
                        "miga_slide_everything"
                    ),
                ]);
        $this->end_controls_section();

        $this->start_controls_section("sec2", [
            "label" => __("Styling", "miga_slide_everything"),
            "tab" => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'arrow_left',
            [
                'label' => esc_html__('Arrow color (left)', 'miga_slide_everything'),
                'type' => \Elementor\Controls_Manager::COLOR,
            ]
        );
        $this->add_control(
            'arrow_right',
            [
                'label' => esc_html__('Arrow color (right)', 'miga_slide_everything'),
                'type' => \Elementor\Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'arrow_radius',
            [
                'label' => esc_html__('Arrow border radius', 'miga_slide_everything'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'custom' ],
                ],
        );


        $this->add_control(
            'arrow_height',
            [
                'label' => esc_html__('Arrow height', 'miga_slide_everything'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 400,
                        'step' => 2,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 44,
                ]
            ]
        );


        $this->add_control("abs_arrows", [
            "label" => esc_html__("Position arrows absolute", "miga_slide_everything"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_slide_everything"),
            "label_off" => esc_html__("No", "miga_slide_everything"),
            "return_value" => "yes",
            "default" => "No",
        ]);
        $this->add_control(
            'arrow_left_pos',
            [
                'label' => esc_html__('Arrow position (left)', 'miga_slide_everything'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'custom' ],
                'condition' => ['abs_arrows' => 'yes'],
                'default' => [
                    'unit' => 'custom',
                    'size' => '',
                ]
            ]
        );
        $this->add_control(
            'arrow_right_pos',
            [
                'label' => esc_html__('Arrow position (right)', 'miga_slide_everything'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'custom' ],
                'condition' => ['abs_arrows' => 'yes'],
                'default' => [
                    'unit' => 'custom',
                    'size' => '',
                ]
            ]
        );
        $this->add_control("arrow_note", [
                    "type" => \Elementor\Controls_Manager::RAW_HTML,
                    "label" => "",
                    'condition' => ['abs_arrows' => 'yes'],
                    "raw" => __(
                        "e.g. use 'inherit | 40px | 20px | inherit' to move the left arrow to the bottem right.",
                        "miga_slide_everything"
                    ),
                ]);
        $this->end_controls_section();
    }

    public function get_script_depends()
    {
        wp_register_script(
            "swiper",
            ELEMENTOR_ASSETS_URL . "/lib/swiper/swiper.min.js",
            ["jquery"],
            false,
            true
        );
        return ["swiper", "miga_slide_everything_scripts"];
    }

    public function get_style_depends()
    {
        return ["miga_slide_everything_styles"];
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $loopValue = $settings["endless_loop"] == "yes" ? 1 : 0;
        $centerSlides = $settings["center_slides"] == "yes" ? 1 : 0;
        $mousewheel = $settings["mousewheel"] == "yes" ? 1 : 0;
        $automaticSlider = $settings["automatic_slider"] == "yes" ? 1 : 0;
        $newmethod = $settings["newmethod"] == "yes" ? 1 : 0;
        $pagination = $settings["pagination"] == "yes" ? 1 : 0;
        $arrows = $settings["arrows"] == "yes" ? 1 : 0;
        $dotPadding = 10;
        if (isset($settings["dot_padding"])) {
            $dotPadding = $settings["dot_padding"]["size"];
        }
        $automaticSliderDelay = !empty($settings["autoplay_delay"])
            ? $settings["autoplay_delay"]["size"]
            : "";

        $latestSwiper = \Elementor\Plugin::$instance->experiments->is_feature_active('e_swiper_latest');

        echo '<div class="miga_slide_everything" data-mousewheel="'.esc_attr($mousewheel).'" data-sliderId="' .
            esc_attr($settings["sliderId"]) .
            '" data-spv="' .
            esc_attr($settings["slidesPerView"]) .
            '" data-spvt="' .
            esc_attr($settings["slidesPerViewTablet"]) .
            '" data-spvp="' .
            esc_attr($settings["slidesPerViewPhone"]) .
            '" data-spacebetween="' .
            esc_attr($settings["spaceBetween"]) .
            '" data-loop="' .
            esc_attr($loopValue) .
            '" data-centerSlides="' .
            esc_attr($centerSlides) .
            '" data-autoplay="' .
            esc_attr($automaticSlider) .
            '" data-newmethod="' .
            esc_attr($newmethod) .
            '" data-pagination="' .
            esc_attr($pagination) .'" '.
            'data-arrows="' .esc_attr($arrows) .'" '.
            'data-latestSwiper="' .$latestSwiper .'" '.
            'data-dot-padding="' .esc_attr($dotPadding) .'" '.
            'data-autoplay-delay="' .
            esc_attr($automaticSliderDelay) .
            '">';

        if ($isEditor) {
            echo "<center><strong>Slide Everything</strong><br/>Assigned container ID: <strong>". $settings["sliderId"].'</strong>';
            if (\Elementor\Plugin::$instance->experiments->is_feature_active('container') == false) {
                echo '<br/><strong>This widget only works with Flexbox. Please activate it!</strong>';
            }
            echo '</center>';
        }
        echo '</div>';

        if (!$isEditor) {
            echo '<style type="text/css">';
            echo '#'.$settings["sliderId"].' .swiper-button-prev {background-image: none } ';
            echo '#'.$settings["sliderId"].' .swiper-button-next {background-image: none } ';
            echo '#'.$settings["sliderId"].' .swiper-button-prev svg path {fill:'.$settings["arrow_left"].'} ';
            echo '#'.$settings["sliderId"].' .swiper-button-next svg path {fill:'.$settings["arrow_right"].'} ';
            echo '#'.$settings["sliderId"].' .swiper-button-prev:after {color:'.$settings["arrow_left"].'} ';
            echo '#'.$settings["sliderId"].' .swiper-button-next:after {color:'.$settings["arrow_right"].'} ';

            $h = $settings["arrow_height"]["size"].$settings["arrow_height"]["unit"];
            $w = ($settings["arrow_height"]["size"] * 0.6).$settings["arrow_height"]["unit"];
            echo '#'.$settings["sliderId"].' .swiper-button-next, #'.$settings["sliderId"].' .swiper-button-prev {height:'.$h.'; width:'.$w.'} ';

            if (!empty($settings["arrow_bg"])) {
                echo '#'.$settings["sliderId"].' .swiper-button-next, #'.$settings["sliderId"].' .swiper-button-prev {background-color: '.$settings["arrow_bg"].'} ';
            }

            if ($settings["abs_arrows"] == 'yes') {
                $posL = $settings["arrow_left_pos"];
                $posR = $settings["arrow_right_pos"];

                $aLT = $posL["top"];
                $aLL = $posL["left"];
                $aLR = $posL["right"];
                $aLB = $posL["bottom"];
                if ($posL["unit"] != "custom") {
                    $aLT .= $posL["unit"];
                    $aLL .= $posL["unit"];
                    $aLR .= $posL["unit"];
                    $aLB .= $posL["unit"];
                }
                echo '#'.$settings["sliderId"].' .swiper-button-prev {top:'.$aLT.'; bottom:'.$aLB.'; left:'.$aLL.'; right:'.$aLR.';}';

                $aRT = $posR["top"];
                $aRL = $posR["left"];
                $aRR = $posR["right"];
                $aRB = $posR["bottom"];
                if ($posR["unit"] != "custom") {
                    $aLT .= $posR["unit"];
                    $aLL .= $posR["unit"];
                    $aLR .= $posR["unit"];
                    $aLB .= $posR["unit"];
                }
                echo '#'.$settings["sliderId"].' .swiper-button-next {top:'.$aRT.'; bottom:'.$aRB.'; left:'.$aRL.'; right:'.$aRR.';}';
            }
            echo '</style>';
        }
    }
}
