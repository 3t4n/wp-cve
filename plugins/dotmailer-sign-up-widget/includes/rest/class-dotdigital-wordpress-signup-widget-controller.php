<?php

/**
 * Restful controller for the widget content
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Rest;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\Contact;
use Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Contact;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
use Dotdigital_WordPress\Includes\Widget\Dotdigital_WordPress_Sign_Up_Widget;
class Dotdigital_WordPress_Signup_Widget_Controller
{
    /**
     * @var Dotdigital_WordPress_Contact
     */
    private $dotdigital_contact;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dotdigital_contact = new Dotdigital_WordPress_Contact();
    }
    /**
     * Register the routes for the objects of the controller.
     */
    public function register()
    {
        register_rest_route('dotdigital/v1', '/signup-widget', array('methods' => 'GET', 'callback' => array($this, 'get'), 'permission_callback' => '__return_true'));
        register_rest_route('dotdigital/v1', '/signup-widget', array('methods' => 'POST', 'callback' => array($this, 'post'), 'args' => array('email' => array('required' => \true), 'redirection' => array('required' => \true)), 'permission_callback' => '__return_true'));
    }
    /**
     * Return content
     *
     * @param \WP_REST_Request $request
     * @return false|string
     */
    public function get(\WP_REST_Request $request)
    {
        \ob_start();
        the_widget(\DM_Widget::class, array(), array('showtitle' => $request['showtitle'] ?? \false, 'showdesc' => $request['showdesc'] ?? \false, 'redirection' => $request['redirecturl'] ?? '', 'is_ajax' => $request['is_ajax'] ?? \false));
        return \ob_get_clean();
    }
    /**
     * Save widget from data content
     *
     * @param \WP_REST_Request $request
     *
     * @return null
     */
    public function post(\WP_REST_Request $request)
    {
        $data = $request->get_params();
        if ($data['is_ajax'] && (!isset($_SERVER['HTTP_X_WP_NONCE']) || !wp_verify_nonce(wp_unslash($_SERVER['HTTP_X_WP_NONCE']), 'wp_rest'))) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $this->process_response(\false, Dotdigital_WordPress_Sign_Up_Widget::get_fill_required_message(), $data);
        }
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $datafields = isset($_POST['datafields']) ? wp_unslash($_POST['datafields']) : array();
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $lists = isset($_POST['lists']) ? wp_unslash($_POST['lists']) : array();
        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        if ($this->has_invalid_email($email)) {
            $this->process_response(\false, Dotdigital_WordPress_Sign_Up_Widget::get_invalid_email_message(), $data);
        }
        if ($this->has_no_lists_but_should_have($lists)) {
            $this->process_response(\false, Dotdigital_WordPress_Sign_Up_Widget::get_nobook_message(), $data);
        }
        if ($this->has_missing_required_data_fields($datafields)) {
            $this->process_response(\false, Dotdigital_WordPress_Sign_Up_Widget::get_fill_required_message(), $data);
        }
        $this->create_contact($data);
        return null;
    }
    /**
     * Create and subscribe the contact.
     *
     * @param array $data
     * @return void
     */
    private function create_contact($data)
    {
        try {
            $contact = new Contact();
            $contact->setIdentifiers(array('email' => $data['email']));
            $contact->setLists(\array_values($data['lists'] ?? array()));
            $contact->setDataFields($this->prepare_data_fields($data['datafields'] ?? array(), $data['is_ajax'] ?? \false));
            $this->dotdigital_contact->create_or_update($contact);
        } catch (\Exception $e) {
            \error_log($e->getMessage());
            $this->process_response(\false, Dotdigital_WordPress_Sign_Up_Widget::get_failure_message(), $data);
        }
        $this->process_response(\true, Dotdigital_WordPress_Sign_Up_Widget::get_success_message(), $data);
    }
    /**
     * @param string $email
     *
     * @return bool
     */
    private function has_invalid_email(string $email)
    {
        return \filter_var($email, \FILTER_VALIDATE_EMAIL) === \false;
    }
    /**
     * Check if payload has lists.
     *
     * If any visible lists are configured, the payload must contain at least one list. Otherwise we don't mind.
     *
     * @param array $lists
     * @return bool
     */
    private function has_no_lists_but_should_have(array $lists)
    {
        $has_visible_lists = \count(\array_filter(get_option(Dotdigital_WordPress_Config::SETTING_LISTS_PATH), function ($list) {
            return $list['isVisible'];
        })) > 0;
        return $has_visible_lists && empty($lists);
    }
    /**
     * @param array $datafields
     * @return bool
     */
    private function has_missing_required_data_fields($datafields)
    {
        foreach ($datafields as $datafield) {
            if ($datafield['required'] && empty($datafield['value'])) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @param array $datafields
     * @param bool $is_ajax
     * @return array
     */
    private function prepare_data_fields(array $datafields, bool $is_ajax)
    {
        if ($is_ajax) {
            return $this->prepare_data_fields_for_ajax($datafields);
        }
        return $this->prepare_data_fields_for_http_post($datafields);
    }
    /**
     * @param array $datafields
     * @return array
     */
    private function prepare_data_fields_for_ajax(array $datafields)
    {
        $processed_datafield = array();
        foreach ($datafields as $datafield) {
            $processed_datafield[$datafield['key']] = $datafield['value'];
        }
        return $processed_datafield;
    }
    /**
     * @param array $datafields
     * @return array
     */
    private function prepare_data_fields_for_http_post(array $datafields)
    {
        $processed_datafield = array();
        foreach ($datafields as $key => $datafield) {
            $processed_datafield[$key] = $datafield['value'];
        }
        return $processed_datafield;
    }
    /**
     * @param bool $success
     * @param string $message
     * @param array $data
     * @return void
     */
    private function process_response(bool $success, string $message, array $data)
    {
        if ($data['is_ajax']) {
            $this->ajax_response($success, $message, $data['redirection']);
        } else {
            $this->post_response($success, $message, $data);
        }
    }
    /**
     * @param bool $success
     * @param string $message
     * @param array $data
     * @return void
     */
    private function post_response($success, $message, $data)
    {
        if (!empty($data['redirection'])) {
            wp_redirect($data['redirection']);
            exit;
        }
        wp_redirect(add_query_arg(array('success' => (int) $success, 'message' => $message, 'widget_id' => $data['widget_id']), $data['origin']));
        exit;
    }
    /**
     * @param bool $success
     * @param string $message
     * @param string $redirection
     * @return void
     */
    private function ajax_response($success, $message, $redirection)
    {
        $data = array('success' => $success, 'message' => $message);
        if (!empty($redirection)) {
            $data['redirection'] = $redirection;
        }
        wp_send_json($data);
    }
}
