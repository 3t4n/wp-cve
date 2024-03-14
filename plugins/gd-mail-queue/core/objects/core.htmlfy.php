<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_htmlfy {
    public $replace;
    public $template;
    public $additional;
	public $preprocess;
    public $header;
    public $footer;
    public $preheader = 'content';
    public $preheader_limit = 65;
    public $embed_local_images;

    private $_powered_by = '';
    private $_templates = array();
    private $_additional = array();

    public function __construct() {
        foreach (array('replace', 'template', 'additional', 'preprocess', 'header', 'footer', 'preheader', 'preheader_limit', 'embed_local_images') as $key) {
            $this->$key = gdmaq_settings()->get($key, 'htmlfy');
        }

        add_action('gdmaq_plugin_init', array($this, 'init'), 15);
    }

    /** @return gdmaq_core_htmlfy */
    public static function instance() {
        static $_gdmaq_htmlfy = false;

        if (!$_gdmaq_htmlfy) {
            $_gdmaq_htmlfy = new gdmaq_core_htmlfy();
        }

        return $_gdmaq_htmlfy;
    }

    public function init() {
        $this->_powered_by = apply_filters('gdmaq_htmlfy_poweredby', '<a href="https://plugins.dev4press.com/gd-mail-queue/">GD Mail Queue plugin for WordPress by Dev4Press</a>');

        add_action('gdmaq_mailer_phpmailer_htmlfy', array($this, 'htmlfy'));
    }

    /** @param PHPMailer\PHPMailer\PHPMailer $phpmailer */
    public function htmlfy(&$phpmailer) {
        $template = $this->htmlfy_content($phpmailer->Body, $phpmailer->Subject);

        $phpmailer->AltBody = $phpmailer->Body;
        $phpmailer->Body = $template;
        $phpmailer->isHTML(true);

        gdmaq_settings()->update_statistics('total_htmlfy_mails', 1);
        gdmaq_settings()->update_statistics_for_type(gdmaq_mailer()->get_current_type(), 'total_htmlfy_mails', 1);

        gdmaq_settings()->save('statistics');
    }

    public function htmlfy_content($content, $subject, $args = array()) {
        $defaults = array(
            'is_html' => false,
            'preheader' => true,
            'preprocess' => $this->preprocess,
            'replace' => $this->replace,
            'template' => $this->template,
            'additional' => $this->additional,
            'header' => $this->header,
            'footer' => $this->footer,
            'embed_local_images' => $this->embed_local_images
        );

        $args = wp_parse_args($args, $defaults);
        $args = apply_filters('gdmaq_htmlfy_process_args', $args, $content, $subject);

        if ($args['replace'] == 'custom') {
            $args['replace'] = 'template';
        }

        $template = $this->_get_template($args);

	    $html_content = $args['is_html'] ? $content : $this->_process_text_to_html($content, $args['preprocess']);
        $preheader = $args['preheader'] === true ? $this->_generate_preheader($subject, $content) : ($args['preheader'] === false ? '' : $args['preheader']);

        $tags = array(
            '{{EMAIL_SUBJECT}}' => $subject,
            '{{EMAIL_PREHEADER}}' => $preheader,
            '{{EMAIL_CONTENT}}' => $html_content,
            '{{EMAIL_HEADER}}' => $args['header'],
            '{{EMAIL_FOOTER}}' => $args['footer'],
            '{{EMAIL_POWEREDBY}}' => $this->_powered_by,
            '{{WEBSITE_URL}}' => get_option('siteurl'),
            '{{WEBSITE_NAME}}' => wp_specialchars_decode(get_option('blogname'), ENT_QUOTES),
            '{{WEBSITE_TAGLINE}}' => wp_specialchars_decode(get_option('blogdescription'), ENT_QUOTES),
            '{{CURRENT_DATE}}' => date_i18n(get_option('date_format')),
            '{{CURRENT_TIME}}' => date_i18n(get_option('time_format'))
        );

        $tags['{{WEBSITE_LINK}}'] = '<a href="'.$tags['{{WEBSITE_URL}}'].'">'.$tags['{{WEBSITE_NAME}}'].'</a>';

        $tags = apply_filters('gdmaq_htmlfy_process_tags', $tags, $args, $content, $subject);

        foreach ($tags as $tag => $value) {
            $template = str_replace($tag, $value, $template);
        }

        return $template;
    }

    private function _get_template($args) {
        $template = '';

        if ($args['replace'] == 'additional') {
            $tpl = $args['additional'];

            if (!isset($this->_additional[$tpl])) {
                $path = gdmaq()->get_additional_template_path($tpl);

                if ($path !== false && file_exists($path)) {
                    $this->_additional[$tpl] = file_get_contents($path);
                } else {
                    $this->_additional[$tpl] = '';
                }
            }

            $template = $this->_additional[$tpl];
        }

        if (empty($template) || $args['replace'] == 'template') {
            $tpl = $args['template'];

            if (!isset($this->_templates[$tpl])) {
                $path = GDMAQ_PATH.'templates/'.$tpl.'.html';

                if (!file_exists($path)) {
                    $path = GDMAQ_PATH.'templates/clean-basic.html';
                }

                $this->_templates[$tpl] = file_get_contents($path);
            }

            $template = $this->_templates[$tpl];
        }

        return $template;
    }

	private function _convert_text_links($body) {
		return preg_replace('#<(https?://[^*]+?)>#', '$1', $body);
	}

	private function _convert_line_breaks($body) : string {
		return nl2br($body);
	}

	private function _convert_plain_links($body) : string {
		return make_clickable($body);
	}

	private function _process_text_to_html($body, $preprocess) : string {
		$body = $this->_convert_text_links($body);
		$body = $this->_convert_plain_links($body);
		$body = $this->_convert_line_breaks($body);

		return gdmaq_process_plain_content_for_html($body, $preprocess);
	}

    private function _generate_preheader($subject, $body) {
        $preheader = '';

        if ($this->preheader == 'subject') {
            $preheader = $subject;
        } else if ($this->preheader == 'content') {
            $preheader = $body;
        }

        $output = '';
        if (!empty($preheader)) {
            $output.= mb_substr($preheader, 0, $this->preheader_limit);
            $output.= '...';
        }

        return apply_filters('gdmaq_htmlfy_generate_preheader', $output, $preheader, $subject, $body, $this->preheader, $this->preheader_limit);
    }
}

/** @return gdmaq_core_htmlfy */
function gdmaq_htmlfy() {
    return gdmaq_core_htmlfy::instance();
}
