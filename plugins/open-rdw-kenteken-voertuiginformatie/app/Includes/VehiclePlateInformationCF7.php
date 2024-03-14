<?php

namespace Tussendoor\OpenRDW\Includes;

use WPCF7_FormTag;

class VehiclePlateInformationCF7
{
    public $dot_config;

    public function __construct()
    {

        global $dot_config;
        $this->dot_config = $dot_config;
        
        add_action('init', [$this, 'wpcf7_add_shortcode_open_rdw'], 7);
        add_action('admin_init', [$this, 'wpcf7_add_tag_generator_open_rdw'], 22);
        
        add_filter('wpcf7_validate_open_rdw', [$this, 'wpcf7_validate_open_rdw'], 10, 2);
        add_filter('wpcf7_validate_open_rdw*', [$this, 'wpcf7_validate_open_rdw'], 10, 2);
    }

    public function wpcf7_validate_open_rdw($result, $tag)
    {
        $tag = new WPCF7_FormTag($tag);

        $name = $tag->name;
        $value = isset($_POST[$name]) ? sanitize_text_field($_POST[$name]) : null;

        if ($tag->is_required() && empty($value)) {
            $result->invalidate($tag, wpcf7_get_message('invalid_required'));
        }

        return $result;
    }

    /*--------------------------------------------------------------
    - Register the shortcode handler
    --------------------------------------------------------------*/

    public function wpcf7_add_shortcode_open_rdw()
    {
        if (function_exists('wpcf7_add_form_tag')) {
            wpcf7_add_form_tag('open_rdw', [$this, 'wpcf7_open_rdw_shortcode_handler'], true);
            wpcf7_add_form_tag('open_rdw*', [$this, 'wpcf7_open_rdw_shortcode_handler'], true);
        }
    }

    /*--------------------------------------------------------------
    - Register the tag wpcf7-tag-generator
    --------------------------------------------------------------*/

    public function wpcf7_add_tag_generator_open_rdw()
    {
        if (function_exists('wpcf7_add_tag_generator')) {
            wpcf7_add_tag_generator(
                'open_rdw',
                'Kenteken (Open RDW)',
                'wpcf7-tg-pane-open-rdw',
                [$this, 'wpcf7_tg_pane_open_rdw']
            );
        }
    }

    /*--------------------------------------------------------------
    - Shortcode handler
    --------------------------------------------------------------*/

    public function wpcf7_open_rdw_shortcode_handler($tag)
    {
        $tag = new WPCF7_FormTag($tag);

        if (empty($tag->name)) {
            return '';
        }

        $atts['class']  = $tag->get_class_option(!empty($class) ? $class : null).' open-data-rdw-hook';
        $atts['id']     = 'open-data-rdw';

        $value = (string) reset($tag->values);

        if ($tag->has_option('placeholder') || $tag->has_option('watermark')) {
            $atts['placeholder'] = $value;
            $value = '';
        }

        $atts['value']      = $value;
        $atts['type']       = 'text';
        $atts['name']       = $tag->name;
        $atts['style']      = 'text-transform:uppercase';
        $atts['maxlength']  = '8';

        $atts = wpcf7_format_atts($atts);

        $html = sprintf(
            '<span class="wpcf7-form-control-wrap %1$s"><input %2$s />%3$s</span>',
            $tag->name,
            $atts,
            !empty($validation_error) ? $validation_error : null
        );

        $html .= ' <img src="' .$this->dot_config['plugin.asset_url'] . '/images/front/ajax-loader.gif" id="open_rdw-loading" style="display:none">';
        $html .= ' <img src="' .$this->dot_config['plugin.asset_url'] . '/images/front/warning-icon.png" id="open_rdw-error" style="display:none">';
        $html .= ' <img src="' .$this->dot_config['plugin.asset_url'] . '/images/front/accepted-icon.png" id="open_rdw-accepted" style="display:none">';

        return $html;
    }

    /*--------------------------------------------------------------
    - Tag generator
    --------------------------------------------------------------*/

    public function wpcf7_tg_pane_open_rdw($contact_form)
    {
        $rdw = new VehiclePlateInformation();
        $fields = VehiclePlateInformationFields::fields();
        include $this->dot_config['plugin.view'] . 'admin/vehicle-plate-information-wpcf7.php';
    }
}
