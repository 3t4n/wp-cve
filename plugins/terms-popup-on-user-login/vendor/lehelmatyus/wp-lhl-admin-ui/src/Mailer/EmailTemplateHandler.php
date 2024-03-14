<?php

namespace WpLHLAdminUi\Mailer;

/**
 * Sends an email
 * using a given email template with tokens
 * give it an array of token and value pairs
 * and will replace tokens with values 
 * can sedn email afterwards
 */

class EmailTemplateHandler {

    private $template_path = '';

    // Use array or comma-separated string for multiple addresses
    private $to = "[Empty To Address]";
    private $subject = "[Empty Subject]";
    private $body = [
        '[Empty Body 1]',
        '',
        '',
        ''
    ];

    // set up tokens that the templates can take
    private $tokens = [
        '[email_first_name]' => '[Empty First Name]',
        '[email_last_name]' => '[Empty last Name]',
    ];

    private $debug = false;

    public function __construct($to, $subject = '', $body, $tokens = [], $tamplate_path = '', $debug = false) {

        $this->set_to($to);
        $this->set_subject($subject);
        $this->set_body($body);
        $this->set_tokens($tokens);
        $this->set_template_path($tamplate_path);
        $this->set_debug($debug);

        /**
         * User Tokens in Subject and Body
         * before we set body and subject
         */
        $body = str_replace(array_keys($this->tokens), array_values($this->tokens), $body);
        $subject = str_replace(array_keys($this->tokens), array_values($this->tokens), $subject);

        $this->set_body($body);
        $this->set_subject($subject);

        /**
         * Extend Tokens
         * make [body] and [email_subject]
         * as available tokens in the template file
         */
        // make subject available as a template arg
        $this->tokens['[email_subject]'] = $this->subject;
        // make body available for template as arg
        $this->__extend_body_to_tokens();
    }


    public function set_to($to) {
        if (!empty($to)) {
            $this->to = $this->__validate_to_address($to);
        }
    }
    public function get_to() {
        return $this->to;
    }

    public function set_subject($subject) {
        if (!empty($subject)) {

            $subject = str_replace(':', '', $subject);

            $trimmedSubject = substr($subject, 0, 44);
            // Check if the original subject line was longer than 70 characters
            if (strlen($subject) > 44) {
                $trimmedSubject .= '...'; // Add ellipsis to indicate truncation
            }
            $this->subject = sanitize_text_field($trimmedSubject);
        }
    }
    public function get_subject() {
        return $this->subject;
    }

    public function set_body($body) {
        if (!empty($body)) {
            $this->body = $this->__validate_body($body);
        } else {
            $this->body = "";
        }
    }
    public function get_body() {
        return $this->body;
    }

    public function set_tokens($tokens) {
        if (!empty($tokens)) {
            $this->tokens = $this->__validate_tokens($tokens);
        }
    }
    public function get_tokens() {
        return $this->tokens;
    }

    public function set_debug($debug) {
        $this->debug = false;
        if (!empty($debug)) {
            $this->debug = true;
        }
    }

    public function set_template_path($template_path) {
        if (!empty($template_path)) {
            $this->template_path = $this->__validate_template_path($template_path);
        } else {
            $this->template_path = plugin_dir_path(__FILE__) . '../../../../../email-templates/default.php';
        }
        // fallback 
    }
    public function get_template_path() {
        return $this->template_path;
    }


    public function send($template_path = "", $is_html = false) {

        // Get email template file
        $template_path = !empty($template_path) ? $template_path : $this->get_template_path();
        $email_template = file_get_contents($template_path);

        // returns false on fail
        if ($email_template === false) {
            return false;
        }

        // make year available as a template arg
        $this->tokens['[email_year]'] = date("Y");
        // $this->tokens = array_merge($this->tokens, $this->extra_tokens);
        // Replace variables in the template with argument array values
        $message = str_replace(array_keys($this->tokens), array_values($this->tokens), $email_template);

        // Set Content-Type and charset, default Content-Type is plaintext

        $message = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $message);
        // Sanitize the message with htmlspecialchars
        $message = htmlspecialchars($message, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Replace double quotes with HTML entity representation
        $message = str_replace('&quot;', '"', $message);
        $message = str_replace('&#039;', "'", $message);


        if ($is_html) {
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $result = wp_mail($this->to, $this->subject, $message, $headers);
        } else {
            $result = wp_mail($this->to, $this->subject, $message);
        }

        if ($this->debug) {
            error_log("Sent email to: {$this->to}; Subject: {$this->subject}");
            error_log($message);
        }

        return $result;
    }

    /************************************************
     * Helper methods
     ************************************************/

    /**
     * Make body available as argument for template [body]
     * alertanitvely make all body keys available in tokens such as [introduction], [footer] or whatever is set
     * alernatively create body keys such as [body_1], [body-2], [body-3] etc
     */
    private function __extend_body_to_tokens() {

        if (!is_array($this->body)) {
            $this->tokens['[body]'] = $this->body;
        } else if ($this->__is_assoc_arr($this->body)) {
            $this->tokens = array_merge($this->body, $this->tokens);
        } else {
            foreach ($this->body as $key => $value) {
                $this->tokens["[body-{$key}]"] = $value;
            }
        }
    }

    /**
     * Check if is an associative array
     */
    private function __is_assoc_arr(array $arr) {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /************************************************
     * Validator methods
     ************************************************/

    /**
     * Validates all arguments passed 
     * that can be used in a template
     */
    private function __validate_tokens($tokens) {
        $tokens = array_map([$this, '__sanitize_text_arguments'], $tokens);
        return $tokens;
    }

    /**
     * Validate template name
     */
    private function __validate_template_path($template_path) {
        return sanitize_file_name($template_path);
    }

    /**
     * Validates body argument passed
     */
    private function __validate_body($body) {
        if (empty($body)) return '';

        if (is_array($body)) {

            $body = array_map([$this, '__sanitize_html'], $body);
            $body = implode(',', $body);
            return $body;
        }
        return $body = sanitize_textarea_field($body);
    }

    /**
     * Validates that 
     */
    private function __validate_to_address($to) {

        if (empty($to)) {
            return get_bloginfo('admin_email');
        }

        if (is_array($to)) {
            $to = array_map([$this, '__sanitize_email'], $to);
            $to = implode(',', $to);
            return $to;
        }
        return $to = $this->__sanitize_text_arguments($to);
    }

    /************************************************
     * Sanitize methods
     ************************************************/

    private function __sanitize_text_arguments($arg) {
        $sanitized_arg = sanitize_text_field($arg);
        return $sanitized_arg;
    }

    private function __sanitize_html($arg) {
        $sanitized_arg = wp_kses($arg, $this->__get_body_allowed_html());
        return $sanitized_arg;
    }

    private function __sanitize_email($email) {
        $sanitized_email = sanitize_email($email);
        $sanitized_email = filter_var($sanitized_email, FILTER_SANITIZE_EMAIL);
        return $sanitized_email;
    }


    /**
     * Provides allowed html for body
     */
    private function __get_body_allowed_html() {

        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href'  => array(),
                'rel'   => array(),
                'title' => array(),
            ),
            'b' => array(),
            'blockquote' => array(
                'cite'  => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(),
            'img' => array(
                'alt'    => array(),
                'class'  => array(),
                'height' => array(),
                'src'    => array(),
                'width'  => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );

        return $allowed_tags;
    }
}
