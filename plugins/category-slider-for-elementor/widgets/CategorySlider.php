<?php

class Elementor_Widget_miga_category_slider extends \Elementor\Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        wp_register_script(
            "miga_category_slider_scripts",
            plugins_url("../scripts/main.js", __FILE__),
            [],
            "1.0.0",
            true
        );
        wp_register_style(
            "miga_category_slider_styles",
            plugins_url("../styles/main.css", __FILE__)
        );
    }

    public function get_name()
    {
        return "miga_category_slider_title";
    }

    public function get_title()
    {
        return __("Category slider", "miga_category_slider");
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
            "label" => __("Settings", "miga_category_slider"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control("categories", [
            "label" => esc_html__("Categories", "miga_category_slider"),
            "type" => \Elementor\Controls_Manager::SELECT,
            "default" => "post",
            "options" => [
                "post" => "post categories",
                "woo" => "WooCommerce categories",
            ],
        ]);

        $categories = get_categories([
            "orderby" => "name",
            "hide_empty" => false,
            "order" => "ASC",
        ]);
        $opts = [];
        foreach ($categories as $category) {
            $opts[$category->slug] = $category->name;
        }

        $this->add_control(
            'exclude',
            [
                'label' => esc_html__('Exclude post categories', 'miga_category_slider'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $opts,
                'default' => [],
                'condition' => [
                    'categories' => 'post',
                ],
            ]
        );

        $this->add_control("show_images", [
            "label" => esc_html__("Show images", "miga_category_slider"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Show", "miga_category_slider"),
            "label_off" => esc_html__("Hide", "miga_category_slider"),
            "return_value" => "yes",
            "default" => "yes",
        ]);

        $this->add_control("endless_loop", [
            "label" => esc_html__("Loop slider", "miga_category_slider"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_category_slider"),
            "label_off" => esc_html__("No", "miga_category_slider"),
            "return_value" => "yes",
            "default" => "No",
        ]);

        $this->add_control("automatic_slider", [
            "label" => esc_html__("Autoplay", "miga_category_slider"),
            "type" => \Elementor\Controls_Manager::SWITCHER,
            "label_on" => esc_html__("Yes", "miga_category_slider"),
            "label_off" => esc_html__("No", "miga_category_slider"),
            "return_value" => "yes",
            "default" => "No",
        ]);
        $this->add_control("autoplay_delay", [
            "label" => esc_html__(
                "Autoplay delay (ms)",
                "miga_category_slider"
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
                "size" => 300,
            ],
            "selectors" => ["none"],
            "condition" => ["automatic_slider" => "yes"],
        ]);



        $this->add_control("slideDuration", [
              "label" => esc_html__(
                  "Slide duration",
                  "miga_category_slider"
              ),
              "type" => \Elementor\Controls_Manager::NUMBER,
              "min" => 1,
              "max" => 10000,
              "step" => 1,
              "default" => 300,
          ]);

        $this->add_control("hr1", [
            "type" => \Elementor\Controls_Manager::DIVIDER,
        ]);

        $this->add_control("slidesPerView", [
            "label" => esc_html__("Slides per View", "miga_category_slider"),
            "type" => \Elementor\Controls_Manager::NUMBER,
            "min" => 1,
            "max" => 50,
            "step" => 1,
            "default" => 4,
        ]);
        $this->add_control("slidesPerViewTablet", [
            "label" => esc_html__(
                "Slides per View (Tablet)",
                "miga_category_slider"
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
                "miga_category_slider"
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
                "miga_category_slider"
            ),
            "type" => \Elementor\Controls_Manager::NUMBER,
            "min" => 1,
            "max" => 100,
            "step" => 1,
            "default" => 30,
        ]);

        $this->add_control("hr3", [
            "type" => \Elementor\Controls_Manager::DIVIDER,
        ]);

        $this->add_control("borderRadius", [
            "label" => esc_html__("Border radius", "plugin-name"),
            "type" => \Elementor\Controls_Manager::DIMENSIONS,
            "size_units" => ["px", "%", "em"],
            "selectors" => [
                "{{WRAPPER}} .miga_category_slider__item" =>
                    "border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
            ],
        ]);

        $this->add_control("height", [
            "label" => esc_html__("Height", "miga_category_slider"),
            "type" => \Elementor\Controls_Manager::SLIDER,
            "size_units" => ["px", "%"],
            "range" => [
                "px" => [
                    "min" => 0,
                    "max" => 1000,
                    "step" => 5,
                ],
            ],
            "default" => [
                "unit" => "px",
                "size" => 300,
            ],
            "selectors" => [
                "{{WRAPPER}} .miga_category_slider__item" =>
                    "height: {{SIZE}}{{UNIT}};",
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section("sec2", [
            "label" => __("Title", "miga_category_slider"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control("title_color", [
            "label" => esc_html__("Title Color", "plugin-name"),
            "type" => \Elementor\Controls_Manager::COLOR,
            "selectors" => [
                "{{WRAPPER}} .miga_category_slider__item_text" =>
                    "color: {{VALUE}}",
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                "name" => "content_typography",
                "selector" => "{{WRAPPER}} .miga_category_slider__item_text",
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section("sec2b", [
            "label" => __("Color", "miga_category_slider"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                "name" => "background",
                "label" => esc_html__("Background", "plugin-name"),
                "types" => ["classic", "gradient", "video"],
                "selector" => "{{WRAPPER}} .miga_category_slider__item",
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section("sec3", [
            "label" => __("Image", "miga_category_slider"),
            "tab" => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control("max_width", [
            "label" => esc_html__("Max image Width", "plugin-name"),
            "type" => \Elementor\Controls_Manager::SLIDER,
            "size_units" => ["px", "%"],
            "range" => [
                "px" => [
                    "min" => 0,
                    "max" => 1000,
                    "step" => 5,
                ],
                "%" => [
                    "min" => 0,
                    "max" => 100,
                ],
            ],
            "default" => [
                "unit" => "%",
                "size" => 50,
            ],
            "selectors" => [
                "{{WRAPPER}} .miga_category_slider__item img" =>
                    "max-width: {{SIZE}}{{UNIT}};",
            ],
        ]);

        $this->add_control("max_height", [
            "label" => esc_html__("Max image height", "plugin-name"),
            "type" => \Elementor\Controls_Manager::SLIDER,
            "size_units" => ["px", "%"],
            "range" => [
                "px" => [
                    "min" => 0,
                    "max" => 1000,
                    "step" => 5,
                ],
                "%" => [
                    "min" => 0,
                    "max" => 100,
                ],
            ],
            "default" => [
                "unit" => "%",
                "size" => 50,
            ],
            "selectors" => [
                "{{WRAPPER}} .miga_category_slider__item img" =>
                    "max-height: {{SIZE}}{{UNIT}};",
            ],
        ]);

        $this->add_control("margin_top", [
            "label" => esc_html__("Space between image/title", "plugin-name"),
            "type" => \Elementor\Controls_Manager::SLIDER,
            "size_units" => ["px"],
            "range" => [
                "px" => [
                    "min" => 0,
                    "max" => 200,
                    "step" => 5,
                ],
            ],
            "default" => [
                "unit" => "px",
                "size" => 10,
            ],
            "selectors" => [
                "{{WRAPPER}} .miga_category_slider__item_text" =>
                    "margin-top: {{SIZE}}{{UNIT}};",
            ],
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
        return ["swiper", "miga_category_slider_scripts"];
    }

    public function get_style_depends()
    {
        return ["miga_category_slider_styles"];
    }

    protected function render()
    {
        $isEditor = \Elementor\Plugin::$instance->editor->is_edit_mode();
        $settings = $this->get_settings_for_display();
        $isEditorCss = "";
        $maxWidth = "";
        $preview = "";
        $isWoo = false;
        $loopValue = esc_attr($settings["endless_loop"]) == "yes" ? 1 : 0;
        $automaticSlider =
            esc_attr($settings["automatic_slider"]) == "yes" ? 1 : 0;
        $automaticSliderDelay = !empty($settings["autoplay_delay"])
            ? esc_attr($settings["autoplay_delay"]["size"])
            : "";

        if ($isEditor) {
            $isEditorCss = " isEditor ";
            $maxWidth =
                "max-width:" . round(100 / $settings["slidesPerView"]) . "%";
            $preview = " - preview - ";
        }

        if ($settings["categories"] == "woo") {
            $categories = get_terms(["taxonomy" => "product_cat"]);
            $isWoo = true;
        } else {
            $categories = get_categories([
                "orderby" => "name",
                "hide_empty" => false,
                "order" => "ASC",
            ]);
        }

        if (isset($categories->errors)) {
            return;
        }
        echo '<div class="miga_category_slider__container swiper ' .
            esc_attr($isEditorCss) .
            '" data-spv="' .
            esc_attr($settings["slidesPerView"]) .
            '" data-spvt="' .
            esc_attr($settings["slidesPerViewTablet"]) .
            '" data-spvp="' .
            esc_attr($settings["slidesPerViewPhone"]) .
            '" data-spacebetween="' .
            esc_attr($settings["spaceBetween"]) .
            '" data-loop="' .
            $loopValue .
            '" data-autoplay="' .
            $automaticSlider .
            '" data-autoplay-delay="' .
            $automaticSliderDelay .
            '" data-speed="' .
            esc_attr($settings["slideDuration"]) .
            '" >';
        echo '<div class="swiper-wrapper">';
        $catCount = count($categories);
        foreach ($categories as $category) {
            if (!$isWoo && in_array($category->slug, $settings["exclude"])) {
                continue;
            }
            $cat_data = get_option("taxonomy_$category->term_id");
            $col_start = "";
            $col_end = "";
            if (!empty($cat_data)) {
                $col_start = $cat_data["color_start"]
                    ? $cat_data["color_start"]
                    : "";
                $col_end = $cat_data["color_end"] ? $cat_data["color_end"] : "";
            }
            $col = "";
            if (!empty($col_start) && !empty($col_end)) {
                // gradient
                $col =
                    "background-image: linear-gradient(" .
                    $col_start .
                    "," .
                    $col_end .
                    ")";
            } elseif (!empty($col_start)) {
                // one color
                $col = "background-color:" . $col_start;
            }
            $style = 'style="' . $maxWidth . ";" . $col . '"';
            echo '<div class="miga_category_slider__item swiper-slide ' .
                esc_attr($isEditorCss) .
                '" ' .
                $style .
                ">";
            echo '<div class="miga_category_slider__item_content">';
            if ($settings["show_images"] == "yes") {
                $image_id = get_term_meta(
                    $category->term_id,
                    "category-image-id",
                    true
                );
                if (!empty(wp_get_attachment_image_src($image_id))) {
                    // check categoy image
                    echo '<img src="' .
                        wp_get_attachment_image_src($image_id)[0] .
                        '" alt="' .
                        esc_attr($category->name) .
                        '"/>';
                } elseif (class_exists("WooCommerce")) {
                    // if empty and woocommerce -> use that
                    $thumbnail_id = get_woocommerce_term_meta(
                        $category->term_id,
                        "thumbnail_id",
                        true
                    );
                    $image = wp_get_attachment_url($thumbnail_id);
                    echo '<img src="' . $image . '"/>';
                }
            }

            echo '<p class="miga_category_slider__item_text">' .
                esc_attr($category->name) .
                "</p>";
            if (!empty($preview)) {
                echo '<p class="preview">' . $preview . "</p>";
            }
            echo "</div>";
            echo '<a href="' .
                esc_url(get_category_link($category->term_id)) .
                '"></a>';
            echo "</div>";
        }
        echo "</div>";
        echo '<div class="swiper-pagination"></div>';

        if ($catCount > $settings["slidesPerView"]) {
            echo '<div class="swiper-button-prev"></div>';
            echo '<div class="swiper-button-next"></div>';
        }

        echo "</div>";
    }
}
