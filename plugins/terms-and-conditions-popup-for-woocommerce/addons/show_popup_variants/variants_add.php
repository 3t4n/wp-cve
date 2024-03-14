<?php
class BeRocket_terms_cond_add_open_popup_variants_settings extends BeRocket_plugin_variations  {
    public $plugin_name = 'terms_cond_popup';
    public $version_number = 5;
    function __construct() {
        $this->default = array(
            'popup_open_addon' => array(
                'page_load'             => '',
                'page_load_timer'       => '0',
                'scroll_px'             => '',
                'scroll_px_top'         => '100',
                'scroll_block'          => '',
                'scroll_block_element'  => '#place_order',
            ),
        );
        parent::__construct();
        add_filter('berocket_terms_cond_pages_contents', array($this, 'popup_open_types'), 12);
        add_filter('brfr_'.$this->plugin_name.'_addon_popup_open', array($this, 'addon_popup_open_section'));
    }
    function settings_page($data) {
        $data['Advanced'] = array_merge($data['Advanced'], 
            array(
                'popup_open_start_section' => array(
                    'section'   => 'addon_popup_open',
                    'label'     => ''
                ),
                'popup_open_page_load' => array(
                    "label"     => __('Open Popup on Page Load', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array('popup_open_addon', "page_load"),
                    "value"     => '1',
                    'label_for' => __('Terms and Conditions Popup will open on page load', 'terms-and-conditions-popup-for-woocommerce'),
                    "class"     => "berocket_popup_open_page_load"
                ),
                'popup_open_page_load_timer' => array(
                    "label"     => __('Page Load Timer', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "number",
                    "name"      => array('popup_open_addon', "page_load_timer"),
                    "value"     => '0',
                    "tr_class"  => "berocket_popup_open_page_load_timer",
                    'label_for' => __('ms', 'terms-and-conditions-popup-for-woocommerce'),
                    "extra"     => 'min="0"'
                ),
                'popup_open_scroll_px' => array(
                    "label"     => __('Open Popup after Scroll', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array('popup_open_addon', "scroll_px"),
                    "value"     => '1',
                    'label_for' => __('Terms and Conditions Popup will open after scrolling for a setuped amount of px', 'terms-and-conditions-popup-for-woocommerce'),
                    "class"     => "berocket_popup_open_scroll_px"
                ),
                'popup_open_scroll_px_top' => array(
                    "label"     => __('Scroll Top', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "number",
                    "name"      => array('popup_open_addon', "scroll_px_top"),
                    "value"     => '100',
                    "tr_class"  => "berocket_popup_open_scroll_px_top",
                    'label_for' => __('px', 'terms-and-conditions-popup-for-woocommerce'),
                    "extra"     => 'min="1"'
                ),
                'popup_open_scroll_block' => array(
                    "label"     => __('Open Popup on Scroll to Element', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array('popup_open_addon', "scroll_block"),
                    "value"     => '1',
                    'label_for' => __('Terms and Conditions Popup will open after scrolling to some element on page', 'terms-and-conditions-popup-for-woocommerce'),
                    "class"     => "berocket_popup_open_scroll_block"
                ),
                'popup_open_scroll_block_element' => array(
                    "label"    => __( 'Scroll to Element', "terms-and-conditions-popup-for-woocommerce" ),
                    "name"     => array('popup_open_addon', "scroll_block_element"),
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => '#place_order', 'text' => __('Place Order Button', 'terms-and-conditions-popup-for-woocommerce')),
                        array('value' => '.woocommerce-terms-and-conditions-link', 'text' => __('Terms and Conditions Button', 'terms-and-conditions-popup-for-woocommerce')),
                        array('value' => '#order_review', 'text' => __('Order Review Table', 'terms-and-conditions-popup-for-woocommerce')),
                    ),
                    "tr_class"  => "berocket_popup_open_scroll_block_element",
                    "value"    => '#place_order',
                ),
            )
        );
        return $data;
    }
    function addon_popup_open_section($html) {
        $html .= '<td colspan="2"><h3>'.__('Show Popup Variants Addon Settings', 'terms-and-conditions-popup-for-woocommerce').'</h3>
        <script>
        jQuery(document).ready(function() {
            function berocket_popup_open_page_load() {
                if( jQuery(".berocket_popup_open_page_load").prop("checked") ) {
                    jQuery(".berocket_popup_open_page_load_timer").show();
                } else {
                    jQuery(".berocket_popup_open_page_load_timer").hide();
                }
            }
            berocket_popup_open_page_load();
            jQuery(document).on("change", ".berocket_popup_open_page_load", berocket_popup_open_page_load);
        });
        jQuery(document).ready(function() {
            function berocket_popup_open_scroll_px() {
                if( jQuery(".berocket_popup_open_scroll_px").prop("checked") ) {
                    jQuery(".berocket_popup_open_scroll_px_top").show();
                } else {
                    jQuery(".berocket_popup_open_scroll_px_top").hide();
                }
            }
            berocket_popup_open_scroll_px();
            jQuery(document).on("change", ".berocket_popup_open_scroll_px", berocket_popup_open_scroll_px);
        });
        jQuery(document).ready(function() {
            function berocket_popup_open_scroll_block() {
                if( jQuery(".berocket_popup_open_scroll_block").prop("checked") ) {
                    jQuery(".berocket_popup_open_scroll_block_element").show();
                } else {
                    jQuery(".berocket_popup_open_scroll_block_element").hide();
                }
            }
            berocket_popup_open_scroll_block();
            jQuery(document).on("change", ".berocket_popup_open_scroll_block", berocket_popup_open_scroll_block);
        });
        </script>
        </td>';
        return $html;
    }
    function popup_open_types($popup_pages) {
        $BeRocket_terms_cond_popup = BeRocket_terms_cond_popup::getInstance();
        $options = $BeRocket_terms_cond_popup->get_option();
        $popup_open = ( empty($options['popup_open_addon']) ? array() : $options['popup_open_addon'] );
        $popup_open = array_merge($this->default['popup_open_addon'], $popup_open);
        if( ! empty($popup_open['page_load']) ) {
            $popup_pages['term_cond_page']['popup_open']['page_open'] = array(
                'type'      => 'page_open',
                'timer'     => ( empty($popup_open['page_load_timer']) ? 0 : $popup_open['page_load_timer'] )
            );
        }
        if( ! empty($popup_open['scroll_px']) ) {
            $popup_pages['term_cond_page']['popup_open']['scroll_px'] = array(
                'type'      => 'scroll_px',
                'scroll'    => ( empty($popup_open['scroll_px_top']) ? 1 : $popup_open['scroll_px_top'] )
            );
        }
        if( ! empty($popup_open['scroll_block']) ) {
            $popup_pages['term_cond_page']['popup_open']['scroll_block'] = array(
                'type'      => 'scroll_block',
                'selector'  => ( empty($popup_open['scroll_block_element']) ? '' : $popup_open['scroll_block_element'] )
            );
        }
        return $popup_pages;
    }
}
new BeRocket_terms_cond_add_open_popup_variants_settings();
