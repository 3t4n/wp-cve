<?php

/**
 * Universal fly-out menu for WebFactory plugins
 * (c) WebFactory Ltd, 2021
 */


if (false == class_exists('wf_flyout')) {
    class wf_flyout
    {
        var $ver = 1.0;
        var $plugin_file = '';
        var $plugin_slug = '';
        var $config = array();


        function __construct($plugin_file)
        {
            $this->plugin_file = $plugin_file;
            $this->plugin_slug = basename(dirname($plugin_file));
            $this->load_config();

            if (!is_admin()) {
                return;
            } else {
                add_action('admin_init', array($this, 'init'));
            }
        } // __construct


        function load_config()
        {
            $config = array();
            require_once plugin_dir_path($this->plugin_file) . 'wf-flyout/config.php';

            $defaults = array(
                'plugin_screen' => '',
                'icon_border' => '#0000ff',
                'icon_right' => '40px',
                'icon_bottom' => '40px',
                'icon_image' => '',
                'icon_padding' => '2px',
                'icon_size' => '55px',
                'menu_accent_color' => '#ca4a1f',
                'custom_css' => '',
                'menu_items' => array(),
            );

            $config = array_merge($defaults, $config);
            if (!is_array($config['plugin_screen'])) {
                $config['plugin_screen'] = array($config['plugin_screen']);
            }

            $this->config = $config;
        } // load_config


        function is_plugin_screen()
        {
            $screen = get_current_screen();

            if (in_array($screen->id, $this->config['plugin_screen'])) {
                return true;
            } else {
                return false;
            }
        } // is_plugin_screen


        function init()
        {
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('admin_head', array($this, 'admin_head'));
            add_action('admin_footer', array($this, 'admin_footer'));
        } // init


        function admin_enqueue_scripts()
        {
            if (false === $this->is_plugin_screen()) {
                return;
            }

            wp_enqueue_style('wf_flyout', plugin_dir_url($this->plugin_file) . 'wf-flyout/wf-flyout.css', array(), $this->ver);
            wp_enqueue_script('wf_flyout', plugin_dir_url($this->plugin_file) . 'wf-flyout/wf-flyout.js', array(), $this->ver, true);;
        } // admin_enqueue_scripts


        function admin_head()
        {
            if (false === $this->is_plugin_screen()) {
                return;
            }

            $out = '<style type="text/css">';
            $out .= '#wf-flyout {
        right: ' . $this->config['icon_right'] . ';
        bottom: ' . $this->config['icon_bottom'] . ';
      }';
            $out .= '#wf-flyout #wff-image-wrapper {
        border: ' . $this->config['icon_border'] . ';
      }';
            $out .= '#wf-flyout #wff-button img {
        padding: ' . $this->config['icon_padding'] . ';
        width: ' . $this->config['icon_size'] . ';
        height: ' . $this->config['icon_size'] . ';
      }';
            $out .= '#wf-flyout .wff-menu-item.accent {
        background: ' . $this->config['menu_accent_color'] . ';
      }';
            $out .= $this->config['custom_css'];
            $out .= '</style>';

            self::wp_kses_wf($out);
        } // admin_head


        function admin_footer()
        {
            if (false === $this->is_plugin_screen()) {
                return;
            }

            $out = '';
            $icons_url = plugin_dir_url($this->plugin_file) . 'wf-flyout/icons/';
            $default_link_item = array('class' => '', 'href' => '#', 'target' => '_blank', 'label' => '', 'icon' => '');

            $out .= '<div id="wff-overlay"></div>';

            $out .= '<div id="wf-flyout">';

            $out .= '<a href="#" id="wff-button">';
            $out .= '<span class="wff-label">Open Quick Links</span>';
            $out .= '<span id="wff-image-wrapper">';
            $out .= '<img src="' . $icons_url . $this->config['icon_image'] . '" alt="Open Quick Links" title="Open Quick Links">';
            $out .= '</span>';
            $out .= '</a>';

            $out .= '<div id="wff-menu">';
            $i = 0;
            foreach (array_reverse($this->config['menu_items']) as $item) {
                $i++;
                $item = array_merge($default_link_item, $item);

                if (!empty($item['icon']) && substr($item['icon'], 0, 9) != 'dashicons') {
                    $item['class'] .= ' wff-custom-icon';
                    $item['class'] = trim($item['class']);
                }

                $out .= '<a href="' . $item['href'] . '" class="wff-menu-item wff-menu-item-' . $i . ' ' . $item['class'] . '" target="_blank">';
                $out .= '<span class="wff-label visible">' . $item['label'] . '</span>';
                if (substr($item['icon'], 0, 9) == 'dashicons') {
                    $out .= '<span class="dashicons ' . $item['icon'] . '"></span>';
                } elseif (!empty($item['icon'])) {
                    $out .= '<span class="wff-icon"><img src="' . $icons_url . $item['icon'] . '"></span>';
                }
                $out .= '</a>';
            } // foreach
            $out .= '</div>'; // #wff-menu

            $out .= '</div>'; // #wf-flyout

            self::wp_kses_wf($out);
        } // admin_footer

        public function wp_kses_wf($html)
        {
            add_filter('safe_style_css', function ($styles) {
                $styles_wf = array(
                    'text-align',
                    'margin',
                    'color',
                    'float',
                    'border',
                    'background',
                    'background-color',
                    'border-bottom',
                    'border-bottom-color',
                    'border-bottom-style',
                    'border-bottom-width',
                    'border-collapse',
                    'border-color',
                    'border-left',
                    'border-left-color',
                    'border-left-style',
                    'border-left-width',
                    'border-right',
                    'border-right-color',
                    'border-right-style',
                    'border-right-width',
                    'border-spacing',
                    'border-style',
                    'border-top',
                    'border-top-color',
                    'border-top-style',
                    'border-top-width',
                    'border-width',
                    'caption-side',
                    'clear',
                    'cursor',
                    'direction',
                    'font',
                    'font-family',
                    'font-size',
                    'font-style',
                    'font-variant',
                    'font-weight',
                    'height',
                    'letter-spacing',
                    'line-height',
                    'margin-bottom',
                    'margin-left',
                    'margin-right',
                    'margin-top',
                    'overflow',
                    'padding',
                    'padding-bottom',
                    'padding-left',
                    'padding-right',
                    'padding-top',
                    'text-decoration',
                    'text-indent',
                    'vertical-align',
                    'width',
                    'display',
                );

                foreach ($styles_wf as $style_wf) {
                    $styles[] = $style_wf;
                }
                return $styles;
            });

            $allowed_tags = wp_kses_allowed_html('post');
            $allowed_tags['input'] = array(
                'type' => true,
                'style' => true,
                'class' => true,
                'id' => true,
                'checked' => true,
                'disabled' => true,
                'name' => true,
                'size' => true,
                'placeholder' => true,
                'value' => true,
                'data-*' => true,
                'size' => true,
                'disabled' => true
            );

            $allowed_tags['textarea'] = array(
                'type' => true,
                'style' => true,
                'class' => true,
                'id' => true,
                'checked' => true,
                'disabled' => true,
                'name' => true,
                'size' => true,
                'placeholder' => true,
                'value' => true,
                'data-*' => true,
                'cols' => true,
                'rows' => true,
                'disabled' => true,
                'autocomplete' => true
            );

            $allowed_tags['select'] = array(
                'type' => true,
                'style' => true,
                'class' => true,
                'id' => true,
                'checked' => true,
                'disabled' => true,
                'name' => true,
                'size' => true,
                'placeholder' => true,
                'value' => true,
                'data-*' => true,
                'multiple' => true,
                'disabled' => true
            );

            $allowed_tags['option'] = array(
                'type' => true,
                'style' => true,
                'class' => true,
                'id' => true,
                'checked' => true,
                'disabled' => true,
                'name' => true,
                'size' => true,
                'placeholder' => true,
                'value' => true,
                'selected' => true,
                'data-*' => true
            );
            $allowed_tags['optgroup'] = array(
                'type' => true,
                'style' => true,
                'class' => true,
                'id' => true,
                'checked' => true,
                'disabled' => true,
                'name' => true,
                'size' => true,
                'placeholder' => true,
                'value' => true,
                'selected' => true,
                'data-*' => true,
                'label' => true
            );

            $allowed_tags['a'] = array(
                'href' => true,
                'data-*' => true,
                'class' => true,
                'style' => true,
                'id' => true,
                'target' => true,
                'data-*' => true,
                'role' => true,
                'aria-controls' => true,
                'aria-selected' => true,
                'disabled' => true
            );

            $allowed_tags['div'] = array(
                'style' => true,
                'class' => true,
                'id' => true,
                'data-*' => true,
                'role' => true,
                'aria-labelledby' => true,
                'value' => true,
                'aria-modal' => true,
                'tabindex' => true
            );

            $allowed_tags['li'] = array(
                'style' => true,
                'class' => true,
                'id' => true,
                'data-*' => true,
                'role' => true,
                'aria-labelledby' => true,
                'value' => true,
                'aria-modal' => true,
                'tabindex' => true
            );

            $allowed_tags['span'] = array(
                'style' => true,
                'class' => true,
                'id' => true,
                'data-*' => true,
                'aria-hidden' => true
            );

            $allowed_tags['style'] = array(
                'class' => true,
                'id' => true,
                'type' => true
            );

            $allowed_tags['fieldset'] = array(
                'class' => true,
                'id' => true,
                'type' => true
            );

            $allowed_tags['link'] = array(
                'class' => true,
                'id' => true,
                'type' => true,
                'rel' => true,
                'href' => true,
                'media' => true
            );

            $allowed_tags['form'] = array(
                'style' => true,
                'class' => true,
                'id' => true,
                'method' => true,
                'action' => true,
                'data-*' => true
            );

            $allowed_tags['script'] = array(
                'class' => true,
                'id' => true,
                'type' => true,
                'src' => true
            );

            echo wp_kses($html, $allowed_tags);

            add_filter('safe_style_css', function ($styles) {
                $styles_wf = array(
                    'text-align',
                    'margin',
                    'color',
                    'float',
                    'border',
                    'background',
                    'background-color',
                    'border-bottom',
                    'border-bottom-color',
                    'border-bottom-style',
                    'border-bottom-width',
                    'border-collapse',
                    'border-color',
                    'border-left',
                    'border-left-color',
                    'border-left-style',
                    'border-left-width',
                    'border-right',
                    'border-right-color',
                    'border-right-style',
                    'border-right-width',
                    'border-spacing',
                    'border-style',
                    'border-top',
                    'border-top-color',
                    'border-top-style',
                    'border-top-width',
                    'border-width',
                    'caption-side',
                    'clear',
                    'cursor',
                    'direction',
                    'font',
                    'font-family',
                    'font-size',
                    'font-style',
                    'font-variant',
                    'font-weight',
                    'height',
                    'letter-spacing',
                    'line-height',
                    'margin-bottom',
                    'margin-left',
                    'margin-right',
                    'margin-top',
                    'overflow',
                    'padding',
                    'padding-bottom',
                    'padding-left',
                    'padding-right',
                    'padding-top',
                    'text-decoration',
                    'text-indent',
                    'vertical-align',
                    'width'
                );

                foreach ($styles_wf as $style_wf) {
                    if (($key = array_search($style_wf, $styles)) !== false) {
                        unset($styles[$key]);
                    }
                }
                return $styles;
            });
        }
    } // wf_flyout
} // if class exists
