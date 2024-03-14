<?php

namespace MailOptin\ElementorConnect;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use ElementorPro\Modules\Forms\Classes\Action_Base;
use ElementorPro\Modules\Forms\Controls\Fields_Map;
use MailOptin\Core\AjaxHandler;
use MailOptin\Core\OptinForms\ConversionDataBuilder;
use MailOptin\Core\Repositories\ConnectionsRepository;
use function MailOptin\Core\moVar;

class Elementor extends Action_Base
{
    public function get_label()
    {
        return __('MailOptin', 'mailoptin');
    }

    public function get_name()
    {
        return 'mailoptin';
    }

    public function on_export($element)
    {
        unset($element['settings']['mailoptin_connection']);
        unset($element['settings']['mailoptin_connection_list']);
        unset($element['settings']['mailoptin_double_optin_field']);
        unset($element['settings']['mailoptin_tags_text']);
        unset($element['settings']['mailoptin_tags_select2']);
        unset($element['settings']['mailoptin_fields_map']);
        unset($element['settings']['mailoptin_upgrade_pro']);

        return $element;
    }

    public function email_service_providers()
    {
        $connections = ConnectionsRepository::get_connections();

        if (Init::is_mailoptin_detach_libsodium()) {
            $connections['leadbank'] = __('MailOptin Leads', 'mailoptin');
        }

        //escape webhook connection
        unset($connections['WebHookConnect']);

        return $connections;
    }

    public function email_providers_and_lists()
    {
        $data = [];

        foreach ($this->email_service_providers() as $key => $value) {
            if ($key == 'leadbank') continue;

            $data[$value] = ConnectionsRepository::connection_email_list($key);
        }

        return $data;
    }

    public function register_settings_section($widget)
    {
        $widget->start_controls_section(
            'section_mailoptin',
            [
                'label'     => __('MailOptin', 'mailoptin'),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                ],
            ]
        );

        $widget->add_control(
            'mailoptin_connection',
            [
                'label'   => __('Select Email Service', 'mailoptin'),
                'type'    => Controls_Manager::SELECT,
                'options' => $this->email_service_providers()
            ]
        );

        $widget->add_control(
            'mailoptin_connection_list',
            [
                'label'     => __('Select Email List', 'mailoptin'),
                'type'      => 'moselect',
                'options'   => $this->email_providers_and_lists(),
                'condition' => [
                    'mailoptin_connection!' => ['leadbank', 'ConvertFoxConnect'],
                ],
            ]
        );

        if (Init::is_mailoptin_detach_libsodium()) {

            //add double optin
            $widget->add_control(
                'mailoptin_double_optin_field',
                [
                    'label'       => __('Double Optin', 'mailoptin'),
                    'type'        => Controls_Manager::SWITCHER,
                    'condition'   => [
                        'mailoptin_connection' => \MailOptin\Connections\Init::double_optin_support_connections(true)
                    ],
                    'default'     => 'no',
                    'label_on'    => __('Enabled', 'mailoptin'),
                    'label_off'   => __('Disabled', 'mailoptin'),
                    'description' => esc_html__('Double optin requires users to confirm their email address before they are added or subscribed.', 'mailoptin')
                ]
            );

            $widget->add_control(
                'mailoptin_tags_text',
                [
                    'label'       => __('Tags', 'mailoptin'),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition'   => [
                        'mailoptin_connection' => \MailOptin\Connections\Init::text_tag_connections()
                    ],
                    'description' => esc_html__('Enter comma-separated list of tags to assign to subscribers.', 'mailoptin')
                ]
            );

            $widget->add_control(
                'mailoptin_tags_select2',
                [
                    'label'       => __('Tags', 'mailoptin'),
                    'type'        => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple'    => true,
                    'render_type' => 'none',
                    'condition'   => [
                        'mailoptin_connection' => \MailOptin\Connections\Init::select2_tag_connections()
                    ],
                    'description' => esc_html__('Select tags that will be assigned to subscribers.', 'mailoptin')
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control('remote_id', ['type' => Controls_Manager::HIDDEN]);

            $repeater->add_control('local_id', ['type' => Controls_Manager::SELECT]);

            $widget->add_control(
                'mailoptin_fields_map',
                [
                    'label'     => __('Field Mapping', 'mailoptin'),
                    'type'      => Fields_Map::CONTROL_TYPE,
                    'separator' => 'before',
                    'fields'    => $repeater->get_controls()
                ]
            );
        } else {
            $widget->add_control(
                'mailoptin_upgrade_pro',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw'  => '
                        <style type="text/css">
                        .elementor-panel .mailoptin-panel-nerd-box {
                            padding: 30px 20px;
                            text-align: center; }
                            .elementor-panel .mailoptin-panel-nerd-box .mailoptin-panel-nerd-box-icon {
                              font-size: 52px;
                              color: #a4afb7; }
                            .elementor-panel .mailoptin-panel-nerd-box .mailoptin-panel-nerd-box-title {
                              margin-top: 20px;
                              font-size: 16px;
                              font-weight: bold; }
                            .elementor-panel .mailoptin-panel-nerd-box .mailoptin-panel-nerd-box-message {
                              margin-top: 20px;
                              line-height: 1.4;
                              font-size: 11px; }
                            .elementor-panel .mailoptin-panel-nerd-box .elementor-button.mailoptin-panel-nerd-box-link {
                              background-color: #fcb92c;
                              color: #ffffff;
                              padding: 7px 25px;
                              margin-top: 20px;
                              -webkit-box-shadow: 0 0 1px rgba(0, 0, 0, 0.1), 0 2px 2px rgba(0, 0, 0, 0.1);
                                      box-shadow: 0 0 1px rgba(0, 0, 0, 0.1), 0 2px 2px rgba(0, 0, 0, 0.1);
                              -webkit-transition: .5s;
                              -o-transition: .5s;
                              transition: .5s; }
                              .elementor-panel .mailoptin-panel-nerd-box .elementor-button.mailoptin-panel-nerd-box-link:hover {
                                background-color: #fdca5e; }
                                .elementor-panel #elementor-panel-get-pro-elements .mailoptin-panel-nerd-box-message {
                            text-transform: uppercase; }
                        </style>
                        <div class="mailoptin-panel-nerd-box">
						<i class="mailoptin-panel-nerd-box-icon eicon-hypster"></i>
						<div class="mailoptin-panel-nerd-box-title">' .
                              __('Upgrade to Premium', 'mailoptin') .
                              '</div>
						<div class="mailoptin-panel-nerd-box-message">' .
                              sprintf(
                                  __('Upgrade to Premium to assign tags to subscribers, add support custom fields and remove the 500 subscribers monthly limit.', 'mailoptin'),
                                  '<strong>',
                                  '</strong>'
                              ) .
                              '</div>
						<a class="mailoptin-panel-nerd-box-link elementor-button elementor-button-default elementor-go-pro" href="https://mailoptin.io/pricing/?utm_source=wp_dashboard&utm_medium=upgrade&utm_campaign=elementor_settings" target="_blank">' .
                              __('Go Premium Now', 'mailoptin') .
                              '</a>
					</div>',
                ]
            );
        }

        $widget->end_controls_section();
    }

    public function run($record, $ajax_handler)
    {
        $form_name             = $record->get_form_settings('form_name');
        $connection_service    = $record->get_form_settings('mailoptin_connection');
        $connection_email_list = $record->get_form_settings('mailoptin_connection_list');
        $POSTed_data           = $record->get('sent_data');
        $full_name             = isset($POSTed_data['mo_name']) ? $POSTed_data['mo_name'] : moVar($POSTed_data, 'name', '');
        $first_name            = isset($POSTed_data['mo_first_name']) ? $POSTed_data['mo_first_name'] : moVar($POSTed_data, 'first_name', '');
        $last_name             = isset($POSTed_data['mo_last_name']) ? $POSTed_data['mo_last_name'] : moVar($POSTed_data, 'last_name', '');

        $form_tags = $record->get_form_settings('mailoptin_tags_text');
        if (in_array($connection_service, \MailOptin\Connections\Init::select2_tag_connections())) {
            $form_tags = $record->get_form_settings('mailoptin_tags_select2');
        }

        // get the default
        $double_optin = false;
        if (in_array($connection_service, \MailOptin\Connections\Init::double_optin_support_connections(true))) {
            $double_optin = $record->get_form_settings('mailoptin_double_optin_field') === "yes";
        }

        $name  = \MailOptin\Connections\Init::return_name($full_name, $first_name, $last_name);
        $email = isset($POSTed_data['mo_email']) ? $POSTed_data['mo_email'] : $POSTed_data['email'];

        $optin_data = new ConversionDataBuilder();
        // since it's non mailoptin form, set it to zero.
        $optin_data->optin_campaign_id         = 0;
        $optin_data->payload                   = $POSTed_data;
        $optin_data->name                      = $name;
        $optin_data->email                     = $email;
        $optin_data->optin_campaign_type       = esc_html__('Elementor Form', 'mailoptin');
        $optin_data->connection_service        = $connection_service;
        $optin_data->connection_email_list     = $connection_email_list;
        $optin_data->is_timestamp_check_active = false;
        $optin_data->is_double_optin           = $double_optin;
        if (isset($_REQUEST['referrer'])) {
            $optin_data->conversion_page = esc_url_raw($_REQUEST['referrer']);
        }
        $optin_data->user_agent = esc_html($_SERVER['HTTP_USER_AGENT']);
        $optin_data->form_tags  = $form_tags;

        $fields_map = $record->get_form_settings('mailoptin_fields_map');
        if (Init::is_mailoptin_detach_libsodium() && is_array($fields_map) && ! empty($fields_map)) {
            foreach ($fields_map as $mapped_field) {
                $optin_data->form_custom_field_mappings[$mapped_field['remote_id']] = $mapped_field['local_id'];
            }
        }

        $response = AjaxHandler::do_optin_conversion(
            apply_filters('mo_connections_elementor_optin_data', $optin_data, $record)
        );

        if ($response['success'] === false) {
            $ajax_handler->add_error_message($response['message']);

            return;
        }

        $ajax_handler->set_success(true);
    }

    /**
     * Singleton poop.
     *
     * @return Elementor|null
     */
    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

}