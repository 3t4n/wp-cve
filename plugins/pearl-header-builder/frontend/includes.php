<?php
add_action('stm_hb', array('pearl_header_builder', 'build'));
add_shortcode('stm_hb', array('pearl_header_builder', 'build_from_shortcode'));


if (!class_exists('pearl_header_builder')) {
    Class Pearl_Header_Builder
    {

        public $headerName = 'stm_hb_settings';

        public static function build($headerName)
        {
            $headerName = (empty($headerName)) ? 'stm_hb_settings' : $headerName;
            return new self($headerName);
        }

        public static function build_from_shortcode($args, $content = "")
        {
            new Pearl_Header_Builder($args);
            ob_start();
            require_once(STM_HB_DIR . 'hb_templates/main.php');
            $content = ob_get_clean();
            return apply_filters('stm_hb_build_from_shortcode', $content);
        }

        private function __construct($args)
        {
            $this->headerName = (!empty($args['header'])) ? $args['header'] : 'stm_hb_settings';
            $this->inline_scripts();
            require_once(STM_HB_DIR . 'hb_templates/main.php');
        }

        function header_element_custom_styles()
        {
            $custom_css = '';
            $data = apply_filters('pearl_builder_elements', stm_hb_get_option('header_builder', false, $this->headerName));
            if (empty($data)) return null;

            foreach ($data as $row => $columns) {
                foreach ($columns as $column => $elements) {
                    foreach ($elements as $data) {
                        $element = sanitize_title($data['$$hashKey']);
                        if (!empty($element)) {

                            $element = ".stm-header__element.{$element}";
                            $media = array(
                                'default' => '(min-width:1023px)',
                                'tablet'  => '(max-width:1023px) and (min-width:425px)',
                                'mobile'  => '(max-width:425px)'
                            );


                            /*Generate margins*/
                            if (!empty($data['margins'])) {
                                foreach ($data['margins'] as $display => $margins) {
                                    if (!empty($margins)) {
                                        $custom_css .= "@media {$media[$display]}{{$element}{";
                                        foreach ($margins as $prop => $value) {
                                            if (isset($prop) && isset($value)) {
                                                $custom_css .= "margin-{$prop}:{$value}px !important;";
                                            }
                                        }
                                        $custom_css .= "}}";
                                    }
                                }
                            }
                            /*Generate text color*/
                            if (array_has($data, 'data.textColor.name') && $data['data']['textColor']['name'] === 'Custom') {
                                $custom_css .= $element . "{color: " . $data['data']['textColor']['value'] . "}";
                            }

                            /*Generate icon color*/
                            if (array_has($data, 'data.iconColor.name') && $data['data']['iconColor']['name'] === 'Custom') {
                                $custom_css .= $element . " [class*='_icon'] {color: " . $data['data']['iconColor']['value'] . "}";
                            }


                            /*Menu item hover line*/
                            if (array_has($data, 'data.lineColor') && !empty($data['data']['lineColor']) &&
                                array_has($data, 'data.line') && !empty($data['data']['line'])
                            ) {
                                $custom_css .= $element . " li:before {background-color: {$data['data']['lineColor']} !important;}";
                            }

                            /*Menu item a color*/
                            if (array_has($data, 'data.menuLinkColor') && !empty($data['data']['menuLinkColor'])) {
                                $custom_css .= $element . " li a {color: {$data['data']['menuLinkColor']} !important;}";
                            }
                            /*Menu item a color on hover*/
                            if (array_has($data, 'data.menuLinkColorOnHover') && !empty($data['data']['menuLinkColorOnHover'])) {
                                $custom_css .= $element . " li a:hover {color: {$data['data']['menuLinkColorOnHover']} !important;}";
                                $custom_css .= $element . " li:hover > a {color: {$data['data']['menuLinkColorOnHover']} !important;}";
                            }

                            if (!empty($data['order'])) {
                                foreach ($media as $device => $breakpoint) {
                                    if (!empty($data['order'][$device])) {
                                        $custom_css .= "@media {$breakpoint} {";
                                        $custom_css .= $element . "{order: -{$data['order'][$device]}}";
                                        $custom_css .= "}";
                                    }
                                }
                                $custom_css .= $element . "{}";
                            }

                            $disabled_devices = (!empty($data['disabled'])) ? array_keys(array_filter($data['disabled'])) : array();
                            if (!empty($disabled_devices)) {
                                foreach ($media as $device => $breakpoint) {
                                    if (in_array($device, $disabled_devices)) {
                                        $custom_css .= "@media {$breakpoint} {";
                                        $custom_css .= $element . "{display: none!important};";
                                        $custom_css .= "}";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $custom_css;
        }

        function header_elements_styles()
        {
            $custom_css = '';
            $top_bar_bg = stm_hb_get_image_url(stm_hb_get_option('top_bar_bg', false, $this->headerName));
            $header_bg = stm_hb_get_image_url(stm_hb_get_option('header_bg', false, $this->headerName));
            $bottom_bar_bg = stm_hb_get_image_url(stm_hb_get_option('bottom_bar_bg', false, $this->headerName));
            $all_bg = stm_hb_get_image_url(stm_hb_get_option('all_bg', false, $this->headerName));
            $all_bg_color = stm_hb_get_option('all_bg_color', false, $this->headerName);

            $top_bar_width = stm_hb_get_option('top_bar_width', false, $this->headerName);
            $header_width = stm_hb_get_option('header_width', false, $this->headerName);
            $bottom_bar_width = stm_hb_get_option('bottom_bar_width', false, $this->headerName);

            $styles = array(
                '.stm-header'                          => array(
                    'background-image' => esc_url($all_bg),
                ),
                '.stm-header:before'                   => array(
                    'background-color' => stm_hb_get_option('all_bg_color', false, $this->headerName),
                ),
                '.stm-header__row_color_top'           => array(
                    'padding-top'                          => stm_hb_get_option('top_bar_top', false, $this->headerName),
                    'padding-bottom'                       => stm_hb_get_option('top_bar_bottom', false, $this->headerName),
                    'margin-top'                          => stm_hb_get_option('top_bar_top_margin', false, $this->headerName),
                    'margin-bottom'                       => stm_hb_get_option('top_bar_bottom_margin', false, $this->headerName),
                    'background-image'                     => esc_url($top_bar_bg),
                    'color'                                => stm_hb_get_option('top_bar_text_color', false, $this->headerName),
                    'z-index'                                => stm_hb_get_option('top_bar_zindex', false, $this->headerName),
                    '.stm-icontext__text'                  => array(
                        'color' => stm_hb_get_option('top_bar_text_color', false, $this->headerName)
                    ),
                    'a'                                    => array(
                        'color' => stm_hb_get_option('top_bar_text_color', false, $this->headerName)
                    ),
                    '.dropdown-toggle'                     => array(
                        'color' => stm_hb_get_option('top_bar_text_color', false, $this->headerName) . '!important'
                    ),
                    'a:hover, .stm-navigation__default > ul > li > a:hover'                              => array(
                        'color' => stm_hb_get_option('top_bar_link_color_hover', false, $this->headerName) . '!important'
                    ),
                    'li:hover a'                           => array(
                        'color' => stm_hb_get_option('top_bar_link_color_hover', false, $this->headerName)
                    ),
                    '.stm-switcher__trigger_default:after' => array(
                        'border-top-color' => stm_hb_get_option('top_bar_text_color', false, $this->headerName)
                    ),
                    '.dropdown-toggle:after'               => array(
                        'border-top-color' => stm_hb_get_option('top_bar_text_color', false, $this->headerName) . '!important'
                    ),
                ),
                '.stm-header__row_color_top:before'    => array(
                    'background-color' => stm_hb_get_option('top_bar_color', false, $this->headerName),
                ),
                '.stm-header__row_color_center'        => array(
                    'padding-top'                          => stm_hb_get_option('header_top', false, $this->headerName),
                    'padding-bottom'                       => stm_hb_get_option('header_bottom', false, $this->headerName),
                    'margin-top'                          => stm_hb_get_option('header_top_margin', false, $this->headerName),
                    'margin-bottom'                       => stm_hb_get_option('header_bottom_margin', false, $this->headerName),
                    'background-image'                     => esc_url($header_bg),
                    'color'                                => stm_hb_get_option('header_text_color', false, $this->headerName),
                    'z-index'                                => stm_hb_get_option('header_zindex', false, $this->headerName),
                    '.stm-icontext__text'                  => array(
                        'color' => stm_hb_get_option('header_text_color', false, $this->headerName)
                    ),
                    'a'                                    => array(
                        'color' => stm_hb_get_option('header_text_color', false, $this->headerName)
                    ),
                    '.dropdown-toggle'                     => array(
                        'color' => stm_hb_get_option('header_text_color', false, $this->headerName) . '!important'
                    ),
                    'li:hover > a'                         => array(
                        'color' => stm_hb_get_option('header_text_color_hover', false, $this->headerName) . '!important'
                    ),
                    'a:hover, .stm-navigation__default > ul > li > a:hover'                              => array(
                        'color' => stm_hb_get_option('header_text_color_hover', false, $this->headerName) . '!important'
                    ),
                    'a > .divider'                         => array(
                        'color' => stm_hb_get_option('header_text_color', false, $this->headerName) . '!important'
                    ),
                    'a:hover > .divider'                   => array(
                        'color' => stm_hb_get_option('header_text_color', false, $this->headerName) . '!important'
                    ),
                    'li:hover > a > .divider'              => array(
                        'color' => stm_hb_get_option('header_text_color', false, $this->headerName) . '!important'
                    ),
                    '.stm-switcher__trigger_default:after' => array(
                        'border-top-color' => stm_hb_get_option('header_text_color', false, $this->headerName)
                    ),
                    '.dropdown-toggle:after'               => array(
                        'border-top-color' => stm_hb_get_option('header_text_color', false, $this->headerName) . '!important'
                    ),
                ),
                '.stm-header__row_color_center:before' => array(
                    'background-color' => stm_hb_get_option('header_color', false, $this->headerName),
                ),
                '.stm-header__row_color_bottom'        => array(
                    'padding-top'                          => stm_hb_get_option('bottom_bar_top', false, $this->headerName),
                    'padding-bottom'                       => stm_hb_get_option('bottom_bar_bottom', false, $this->headerName),
                    'margin-top'                          => stm_hb_get_option('bottom_bar_top_margin', false, $this->headerName),
                    'margin-bottom'                       => stm_hb_get_option('bottom_bar_bottom_margin', false, $this->headerName),
                    'background-image'                     => esc_url($bottom_bar_bg),
                    'color'                                => stm_hb_get_option('bottom_bar_text_color', false, $this->headerName),
                    'z-index'                                => stm_hb_get_option('bottom_bar_zindex', false, $this->headerName),
                    '.stm-icontext__text'                  => array(
                        'color' => stm_hb_get_option('bottom_bar_text_color', false, $this->headerName)
                    ),
                    'a'                                    => array(
                        'color' => stm_hb_get_option('bottom_bar_text_color', false, $this->headerName)
                    ),
                    '.dropdown-toggle'                     => array(
                        'color' => stm_hb_get_option('bottom_bar_text_color', false, $this->headerName) . '!important'
                    ),
                    'a:hover, .stm-navigation__default > ul > li > a:hover'                              => array(
                        'color' => stm_hb_get_option('bottom_bar_link_color_hover', false, $this->headerName) . '!important'
                    ),
                    'li:hover a'                           => array(
                        'color' => stm_hb_get_option('bottom_bar_link_color_hover', false, $this->headerName)
                    ),
                    '.stm-switcher__trigger_default:after' => array(
                        'border-top-color' => stm_hb_get_option('bottom_bar_text_color', false, $this->headerName)
                    ),
                    '.dropdown-toggle:after'               => array(
                        'border-top-color' => stm_hb_get_option('bottom_bar_text_color', false, $this->headerName) . '!important'
                    ),
                ),
                '.stm-header__row_color_bottom:before' => array(
                    'background-color' => stm_hb_get_option('bottom_bar_color', false, $this->headerName),
                ),
            );


            if(!empty($top_bar_width)) {
                $item = array('max-width' => $top_bar_width);
                $styles['.stm-header__row_color_top'] = $item + $styles['.stm-header__row_color_top'];
            }

            if(!empty($header_width)) {
                $item = array('max-width' => $header_width);
                $styles['.stm-header__row_color_center'] = $item + $styles['.stm-header__row_color_center'];
            }

            if(!empty($bottom_bar_width)) {
                $item = array('max-width' => $bottom_bar_width);
                $styles['.stm-header__row_color_bottom'] = $item + $styles['.stm-header__row_color_bottom'];
            }

            foreach ($styles as $element => $element_styles) {
                $custom_css .= "{$element}{";
                foreach ($element_styles as $parent_prop => $parent_value) {
                    if (!empty($parent_value)) {
                        $helpers = stm_hb_get_style($parent_prop);
                        $prefix = $helpers['prefix'];
                        $affix = $helpers['affix'];

                        /*Second lvl*/
                        if (is_array($parent_value)) {
                            $custom_css .= "} {$element} {$parent_prop} {";
                            foreach ($parent_value as $prop => $value) {
                                $helpers = stm_hb_get_style($parent_prop);
                                $prefix = $helpers['prefix'];
                                $affix = $helpers['affix'];

                                $custom_css .= "{$prop}:{$prefix}{$value}{$affix};";
                            }
                        } else {
                            /*First lvl*/
                            $custom_css .= "{$parent_prop}:{$prefix}{$parent_value}{$affix};";
                        }
                    }
                }
                $custom_css .= '}';
            }

            return $custom_css;
        }

        function get_custom_styled_elements_array()
        {
            $elements_list = array(
                'colors'        => array(
                    'main_color'      => array(
                        '.stm_hb_mtc',
                        '.stm_hb_mtc_h:hover',
                        '.stm_hb_mtc_b:before',
                        '.stm_hb_mtc_b_h:hover:before',
                        '.stm_hb_mtc_a:after',
                        '.stm_hb_mtc_a_h:hover:after',
                        '.stm_hb_mtc_a_h.active',
                        '.mini-cart',
                    ),
                    'secondary_color' => array(
                        '.stm_hb_stc',
                        '.stm_hb_stc_h:hover',
                        '.stm_hb_stc_a:after',
                        '.stm_hb_stc_a_h:hover:after',
                        '.stm_hb_stc_b:before',
                        '.stm_hb_stc_b_h:hover:before',
                    ),
                    'third_color'     => array(
                        '.stm_hb_ttc',
                        '.stm_hb_ttc_h:hover',
                        '.stm_hb_ttc_a:after',
                        '.stm_hb_ttc_a_h:hover:after',
                        '.stm_hb_ttc_b:before',
                        '.stm_hb_ttc_b_h:hover:before',
                    )
                ),
                'bg_colors'     => array(
                    'main_color'      => array(
                        '.stm_hb_mbc',
                        '.stm_hb_mbc_h:hover',
                        '.stm_hb_mbc_b:before',
                        '.stm_hb_mbc_b_h:hover:before',
                        '.stm_hb_mbc_a:after',
                        '.stm_hb_mbc_a_h:hover:after',
                        '.stm_hb_mbc_h.active',
                        '.stm-search .stm_widget_search button[type=submit]',
                    ),
                    'secondary_color' => array(
                        '.stm_hb_sbc',
                        '.stm_hb_sbc_h:hover',
                        '.stm_hb_sbc_a:after',
                        '.stm_hb_sbc_a_h:hover:after',
                        '.stm_hb_sbc_b:before',
                        '.stm_hb_sbc_b_h:hover:before',
                    ),
                    'third_color'     => array(
                        '.stm_hb_tbc',
                        '.stm_hb_tbc_h:hover',
                        '.stm_hb_tbc_h.active',
                        '.stm_hb_tbc_a:after',
                        '.stm_hb_tbc_a_h:hover:after',
                        '.stm_hb_tbc_b:before',
                        '.stm_hb_tbc_b_h:hover:before',
                    )
                ),
                'border_colors' => array(
                    'main_color'      => array(
                        '.stm_hb_mbdc',
                        '.stm_hb_mbdc_h:hover',
                        '.stm_hb_mbdc_b:before',
                        '.stm_hb_mbdc_b_h:hover:before',
                        '.stm_hb_mbdc_a:after',
                        '.stm_hb_mbdc_a_h:hover:after',
                        '.stm-search .stm_widget_search .form-control:focus',
                    ),
                    'secondary_color' => array(
                        '.stm_hb_sbdc',
                        '.stm_hb_sbdc_h:hover',
                        '.stm_hb_sbdc_a:after',
                        '.stm_hb_sbdc_a_h:hover:after',
                        '.stm_hb_sbdc_b:before',
                        '.stm_hb_sbdc_b_h:hover:before',

                    ),
                    'third_color'     => array(
                        '.stm_hb_tbdc',
                        '.stm_hb_tbdc_h:hover',
                        '.stm_hb_tbdc_a:after',
                        '.stm_hb_tbdc_a_h:hover:after',
                        '.stm_hb_tbdc_b:before',
                        '.stm_hb_tbdc_b_h:hover:before',
                    ),
                )
            );

            $main_color = stm_hb_get_option('main_color', '#297ee8', $this->headerName);
            $secondary_color = stm_hb_get_option('secondary_color', '#222222', $this->headerName);
            $third_color = stm_hb_get_option('third_color', '#297ee8', $this->headerName);

            /*Color*/
            $colors = $elements_list['colors'];

            /*Background color*/
            $bg_colors = $elements_list['bg_colors'];

            /*Border color*/
            $border_colors = $elements_list['border_colors'];

            ob_start();
            foreach ($colors as $color => $elements) { ?>
                <?php echo implode(',', $elements) ?> {color: <?php echo sanitize_text_field(${$color}); ?> !important}
            <?php }

            foreach ($bg_colors as $bg_color => $elements) { ?>
                <?php echo implode(',', $elements) ?> {background-color: <?php echo sanitize_text_field(${$bg_color}); ?> !important}
            <?php }

            foreach ($border_colors as $border_color => $elements) { ?>
                <?php echo implode(',', $elements) ?> {border-color: <?php echo sanitize_text_field(${$border_color}); ?> !important}
            <?php }


            //Header width
            $header_container_width = stm_hb_get_option('header_container_width', false, $this->headerName);
            $header_content_width = stm_hb_get_option('header_content_width', false, $this->headerName);

            if (!empty($header_container_width)) {
                echo ".stm-header{max-width:{$header_container_width}px;}";
            }

            if (!empty($header_content_width)) {
                echo ".stm-header__row_color > .container{max-width:{$header_content_width}px;}";
            }

            $styles = ob_get_clean();

            return $styles;

        }

        function inline_scripts()
        {
            $styles = '';
            $styles .= $this->header_element_custom_styles();
            $styles .= $this->header_elements_styles();
            $styles .= $this->get_custom_styled_elements_array();

            echo stm_hb_text_sanitize('<style type="text/css" id="styles-' . $this->headerName . '">');
            echo stm_hb_text_sanitize($styles);
            echo stm_hb_text_sanitize('</style>');
        }

    }
}


function stm_hb_get_option($option_name, $default = false, $hb = '')
{

    $hb = !empty($hb) ? $hb : 'header_builder';

    $options = get_option($hb, array());
    $option = null;

    if (!empty($options[$option_name])) {
        $option = $options[$option_name];
    } elseif (isset($default)) {
        $option = $default;
    }

    return $option;
}

function stm_hb($a)
{
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}

function stm_hb_parts()
{
    return array(
        'rows'  => array('top', 'center', 'bottom'),
        'cells' => array('left', 'center', 'right')
    );
}

function stm_hb_check_string($string)
{
    return $string === 'true';
}

function stm_hb_element_style($element)
{
    $style_class = '';
    if (!empty($element['data']) and !empty($element['data']['style'])) {
        $style_class = $element['data']['style'];
    }

    return " stm-header__element_{$style_class}";
}

function stm_hb_locate_builder_element($templates, $template_name, $__folder)
{
    $located = false;

    foreach ((array)$templates as $template) {

        $folder = $template;

        if (!empty($template_name)) {
            $template = $template_name;
        }

        if (substr($template, -4) !== '.php') {
            $template .= '.php';
        }

        if ($__folder !== 'elements') {
            $base = 'hb_templates/' . $__folder . '/' . $template;
        } else {
            $base = 'hb_templates/' . $__folder . '/' . $folder . '/' . $template;
        }

        if (!($located = locate_template('/' . $base))) {
            $located = STM_HB_DIR . $base;
        }

        if (file_exists($located)) {
            break;
        }

    }

    return apply_filters('stm_listings_locate_template', $located, $templates);
}

function stm_hb_load_element($__template, $__vars = array(), $__template_name = '', $__folder = 'elements')
{
    extract($__vars);
    include stm_hb_locate_builder_element($__template, $__template_name, $__folder);
}

function stm_get_assets_path()
{
    $assets = array();
    $url = STM_HB_URL;
    $assets['css'] = $url . 'assets/admin/assets/css/';
    $assets['frontend_css'] = $url . 'assets/frontend/assets/css/';
    $assets['frontend_js'] = $url . 'assets/frontend/assets/js/';
    $assets['frontend_vendor'] = $url . 'assets/frontend/assets/vendor/';
    $assets['js'] = $url . 'assets/admin/assets/js/';
    $assets['vendors'] = $url . 'assets/admin/assets/vendor/';
    $assets['v'] = WP_DEBUG ? time() : STM_HB_VER;

    return apply_filters('stm_get_assets_path', $assets);
}

function stm_hb_random($len = 10)
{
    $word = array_merge(range('a', 'z'), range('A', 'Z'));
    shuffle($word);
    return substr(implode($word), 0, $len);
}

function stm_hb_get_dropdown($dropdown)
{
    $choices = array(
        'first'  => [],
        'others' => []
    );

    if (!empty($dropdown[0]) and !empty($dropdown[0]['label'])) {
        $choices['first'] = $dropdown[0];
    }

    array_shift($dropdown);

    if (!empty($dropdown)) {
        $choices['others'] = $dropdown;
    }

    return $choices;
}

function stm_hb_array_to_style_string($arr, $important = false)
{
    $important = $important ? '!important' : '';
    $r = array_map(
        function ($v, $k) {
            return $k . ':' . $v;
        },
        $arr,
        array_keys($arr)
    );

    $r = implode($important . '; ', $r) . $important . ';';

    return $r;
}

function stm_hb_get_wpml_langs()
{
    do_action('wpml_add_language_selector');

    if (defined('ICL_LANGUAGE_CODE')) {
        $current_language_code = ICL_LANGUAGE_CODE;
        $langs = icl_get_languages('skip_missing=0');
        $wpml = array();

        if (!empty($langs)) {
            if (!empty($langs[$current_language_code])) {
                $current_language = $langs[$current_language_code];
                $wpml[] = array(
                    'label' => $current_language['native_name'],
                    'url'   => $current_language['url'],
                );
            }

            foreach ($langs as $lang_key => $lang_info) {
                if ($lang_key !== $current_language_code) {
                    $wpml[] = array(
                        'label' => $lang_info['native_name'],
                        'url'   => $lang_info['url'],
                    );
                }
            }
        }
    }

    if (empty($langs)) {
        $wpml = [
            array(
                'label' => esc_html__('English', 'pearl-header-builder'),
                'url'   => '/'
            ),
        ];
    }

    return apply_filters('pearl_get_wpml_langs', $wpml);
}

function stm_hb_get_image_url($id, $size = 'full')
{
    $url = '';
    if (!empty($id)) {
        $image = wp_get_attachment_image_src($id, $size, false);
        if (!empty($image[0])) {
            $url = $image[0];
        }
    }

    return $url;
}

function stm_hb_color_treads($color)
{
    $color = (strlen($color) == 6) ? "#{$color}" : $color;
    return apply_filters('pearl_color_treads', $color);
}

function stm_hb_get_style($key = '')
{
    $r = array(
        'prefix' => '',
        'affix'  => ''
    );

    $metrix = array(
        'padding-top',
        'padding-bottom',
        'margin-top',
        'margin-bottom',
        'line-height',
        'border-width',
        'max-width'
    );

    $src = array(
        'background-image'
    );

    /*Set px*/
    if (in_array($key, $metrix)) {
        $r['affix'] = 'px';
    }

    /*Set url*/
    if (in_array($key, $src)) {
        $r['prefix'] = 'url("';
        $r['affix'] = '")';
    }

    return apply_filters('stm_hb_get_style', $r);
}

function stm_hb_text_sanitize($text)
{
    return $text;
}

function stm_hb_get_cart_url()
{
    $cart_id = get_option('woocommerce_cart_page_id', '');
    return !empty($cart_id) ? get_the_permalink($cart_id) : '';
}

function stm_hb_cart_fragments($fragments)
{

    /*Mini cart*/
    ob_start();
    stm_hb_load_element('cart', array(), 'mini-cart');
    $mini_cart = ob_get_contents();
    ob_end_clean();

    /*Quantity*/
    ob_start();
    stm_hb_load_element('cart', array(), 'quantity');
    $quantity = ob_get_contents();
    ob_end_clean();


    $fragments['.mini-cart'] = $mini_cart;
    $fragments['.cart__quantity-badge'] = $quantity;

    return $fragments;
}

add_filter('woocommerce_add_to_cart_fragments', 'stm_hb_cart_fragments', 1000, 1);