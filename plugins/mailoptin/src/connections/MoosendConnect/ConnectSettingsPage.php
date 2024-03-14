<?php

namespace MailOptin\MoosendConnect;

use MailOptin\Core\Admin\Customizer\CustomControls\WP_Customize_Chosen_Single_Select_Control;
use MailOptin\Core\Admin\Customizer\EmailCampaign\Customizer;
use MailOptin\Core\Connections\AbstractConnect;
use MailOptin\Core\Repositories\EmailCampaignRepository;

class ConnectSettingsPage
{
    public function __construct()
    {
        add_filter('mailoptin_connections_settings_page', array($this, 'connection_settings'));
    
        add_filter('mailoptin_email_campaign_customizer_page_settings', array($this, 'campaign_customizer_settings'));
        add_filter('mailoptin_email_campaign_customizer_settings_controls', array($this, 'campaign_customizer_controls'), 10, 4);
        
        add_action('wp_cspa_settings_after_title', array($this, 'output_error_log_link'), 10, 2);
    }
    
    public function campaign_customizer_settings($settings)
    {
        $settings['MoosendConnect_segments'] = array(
            'default'   => apply_filters('mailoptin_customizer_email_campaign_MoosendConnect_segments', ''),
            'type'      => 'option',
            'transport' => 'postMessage',
        );
        
        return $settings;
    }
    
    /**
     * @param $controls
     * @param $wp_customize
     * @param $option_prefix
     * @param Customizer $customizerClassInstance
     *
     * @return mixed
     */
    public function campaign_customizer_controls($controls, $wp_customize, $option_prefix, $customizerClassInstance)
    {
        $list_id =  EmailCampaignRepository::get_merged_customizer_value($customizerClassInstance->email_campaign_id,'connection_email_list');
        
        $segments = Connect::get_instance()->get_list_segments($list_id);
        
        // always prefix with the name of the connect/connection service.
        $controls['MoosendConnect_segments'] = new WP_Customize_Chosen_Single_Select_Control(
            $wp_customize,
            $option_prefix . '[MoosendConnect_segments]',
            apply_filters('mailoptin_customizer_settings_campaign_MoosendConnect_segments_args', array(
                    'label'       => __('Moosend Segments'),
                    'section'     => $customizerClassInstance->campaign_settings_section_id,
                    'settings'    => $option_prefix . '[MoosendConnect_segments]',
                    'description' => __('Select a segment to send to. Leave empty to send to all list subscribers.', 'mailoptin'),
                    'choices'     => $segments,
                    'priority'    => 199
                )
            )
        );
        
        return $controls;
    }

    public function connection_settings($arg)
    {
        $connected = AbstractMoosendConnect::is_connected(true);
        if (true === $connected) {
            $status = sprintf('<span style="color:#008000">(%s)</span>', __('Connected', 'mailoptin'));
        } else {
            $msg = '';
            if (is_string($connected)) {
                $msg = esc_html(" &mdash; $connected");
            }
            $status = sprintf("<span style='color:#FF0000'>(%s$msg) </span>", __('Not Connected', 'mailoptin'));
        }

        $settingsArg[] = [
            'section_title_without_status' => __('Moosend', 'mailoptin'),
            'section_title'                => __('Moosend Connection', 'mailoptin') . " $status",
            'type'                         => AbstractConnect::EMAIL_MARKETING_TYPE,
            'moosend_api_key'              => [
                'type'          => 'text',
                'obfuscate_val' => true,
                'label'         => __('Enter API Key', 'mailoptin'),
                'description'   => sprintf(
                    __('%sHere\'s how to find your api key%s', 'mailoptin'),
                    '<a target="_blank" href="https://help.moosend.com/hc/en-us/articles/208061865-How-do-I-connect-to-the-Moosend-Web-API-">',
                    '</a>'
                )
            ]
        ];

        return array_merge($arg, $settingsArg);
    }

    public function output_error_log_link($option, $args)
    {
        //Not a moosend connection section
        if (MAILOPTIN_CONNECTIONS_DB_OPTION_NAME !== $option || ! isset($args['moosend_api_key'])) {
            return;
        }

        //Output error log link if  there is one
        echo AbstractConnect::get_optin_error_log_link('moosend');
    }

    public static function get_instance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}