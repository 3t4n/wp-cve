<?php

namespace MailOptin\Connections;

use MailOptin\Core\Connections\ConnectionFactory;

class Init
{
    public static function select2_tag_connections()
    {
        return ['GetResponseConnect', 'OntraportConnect', 'ConvertKitConnect', 'InfusionsoftConnect', 'FluentCRMConnect', 'Ctctv3Connect', 'EgoiConnect'];
    }

    public static function text_tag_connections()
    {
        return ['AweberConnect', 'MailChimpConnect', 'ConvertFoxConnect', 'SendlaneConnect', 'DripConnect', 'ActiveCampaignConnect', 'ZohoCRMConnect', 'WeMailConnect', 'CleverReachConnect', 'OmnisendConnect', 'EmailOctopusConnect'];
    }

    public static function no_name_mapping_connections()
    {
        return ['CleverReachConnect'];
    }

    public static function double_optin_support_connections($only_keys = false)
    {
        //True means double optin is enabled, and false means double optin is disabled by default
        $double_optin_connections = ['DripConnect' => false, 'FluentCRMConnect' => true, 'MailChimpConnect' => true, 'MailjetConnect' => false, 'MailsterConnect' => true, 'SendinblueConnect' => false, 'EgoiConnect' => false, 'FlodeskConnect' => false];

        if ($only_keys) {
            return array_keys($double_optin_connections);
        }

        return $double_optin_connections;
    }

    public static function return_name($name, $first_name, $last_name)
    {
        if (empty($name)) {

            if ( ! empty($first_name)) {

                if ( ! empty($last_name)) {
                    return $first_name . ' ' . $last_name;
                }

                return $first_name;
            }

            if ( ! empty($last_name)) {
                return $last_name;
            }
        }

        return $name;
    }

    /**
     * Return full name from first and last names
     *
     * @param $first_name
     * @param $last_name
     *
     * @return mixed|string
     */
    public static function get_full_name($first_name = '', $last_name = '')
    {
        $full_name = '';

        if (empty($first_name) && empty($last_name)) {
            return $full_name;
        }

        if ( ! empty($first_name) && empty($last_name)) {
            $full_name = $first_name;
        } elseif (empty($first_name) && ! empty($last_name)) {
            $full_name = $last_name;
        } else {
            $full_name = $first_name . ' ' . $last_name;
        }

        return $full_name;
    }

    /**
     * @return array
     */
    public static function mo_select_list_options($saved_integration)
    {
        $lists = [];
        if ( ! empty($saved_integration) && $saved_integration != 'leadbank') {
            $lists = ConnectionFactory::make($saved_integration)->get_email_list();
        }

        if ( ! empty($lists)) {

            $options[''] = esc_html__('Select...', 'mailoptin');

            foreach ($lists as $value => $label) {

                if (empty($value)) continue;

                // Add list to select options.
                $options[$value] = $label;
            }

            return $options;
        }

        return [];
    }

    /**
     * @return array
     */
    public static function merge_vars_field_map($saved_integration, $saved_list)
    {
        $field_map = [];

        if ( ! empty($saved_integration) && $saved_integration != 'leadbank') {

            if (defined('MAILOPTIN_DETACH_LIBSODIUM')) {

                $instance = ConnectionFactory::make($saved_integration);

                if (in_array($instance::OPTIN_CUSTOM_FIELD_SUPPORT, $instance::features_support())) {

                    $cfields = $instance->get_optin_fields($saved_list);

                    if (is_array($cfields) && ! empty($cfields)) {

                        foreach ($cfields as $key => $value) {

                            $field_map[$key] = $value;
                        }
                    }
                }
            }
        }

        return $field_map;
    }

    public static function init()
    {
        \MailOptin\RegisteredUsersConnect\Connect::get_instance(); // should always come first before any connect.
        \MailOptin\MailChimpConnect\Connect::get_instance();
        \MailOptin\MailjetConnect\Connect::get_instance();
        \MailOptin\AweberConnect\Connect::get_instance();
        \MailOptin\CampaignMonitorConnect\Connect::get_instance();
        \MailOptin\VerticalResponseConnect\Connect::get_instance();
        \MailOptin\SendyConnect\Connect::get_instance();
        \MailOptin\DripConnect\Connect::get_instance();
        \MailOptin\SendlaneConnect\Connect::get_instance();
        \MailOptin\SendFoxConnect\Connect::get_instance();
        \MailOptin\WeMailConnect\Connect::get_instance();
        \MailOptin\WordPressUserRegistrationConnect\Connect::get_instance();
        \MailOptin\EmmaConnect\Connect::get_instance();
        \MailOptin\OntraportConnect\Connect::get_instance();
        \MailOptin\ConvertKitConnect\Connect::get_instance();
        \MailOptin\ActiveCampaignConnect\Connect::get_instance();
        \MailOptin\CtctConnect\Connect::get_instance();
        \MailOptin\Ctctv3Connect\Connect::get_instance();
        \MailOptin\SalesforceConnect\Connect::get_instance();
        \MailOptin\MicrosoftDynamic365Connect\Connect::get_instance();
        \MailOptin\HubspotConnect\Connect::get_instance();
        \MailOptin\InfusionsoftConnect\Connect::get_instance();
        \MailOptin\CleverReachConnect\Connect::get_instance();
        \MailOptin\MailerliteConnect\Connect::get_instance();
        \MailOptin\Mailerlitev2Connect\Connect::get_instance();
        \MailOptin\EmailOctopusConnect\Connect::get_instance();
        \MailOptin\FluentCRMConnect\Connect::get_instance();
        \MailOptin\WebHookConnect\Connect::get_instance();
        \MailOptin\GEMConnect\Connect::get_instance();
        \MailOptin\SendinblueConnect\Connect::get_instance();
        \MailOptin\SendGridConnect\Connect::get_instance();
        \MailOptin\MailgunConnect\Connect::get_instance();
        \MailOptin\MailPoetConnect\Connect::get_instance();
        \MailOptin\MailsterConnect\Connect::get_instance();
        \MailOptin\MoosendConnect\Connect::get_instance();
        \MailOptin\OmnisendConnect\Connect::get_instance();
        \MailOptin\BenchmarkEmailConnect\Connect::get_instance();
        \MailOptin\GetResponseConnect\Connect::get_instance();
        \MailOptin\KlaviyoConnect\Connect::get_instance();
        \MailOptin\ZohoCampaignsConnect\Connect::get_instance();
        \MailOptin\ZohoCRMConnect\Connect::get_instance();
        \MailOptin\NewsmanConnect\Connect::get_instance();
        \MailOptin\ConvertFoxConnect\Connect::get_instance();
        \MailOptin\IContactConnect\Connect::get_instance();
        \MailOptin\EgoiConnect\Connect::get_instance();
        \MailOptin\FlodeskConnect\Connect::get_instance();
        \MailOptin\ElementorConnect\Connect::get_instance();
        \MailOptin\WPFormsConnect\Connect::get_instance();
        \MailOptin\NinjaFormsConnect\Connect::get_instance();
        \MailOptin\ContactForm7Connect\Connect::get_instance();
        \MailOptin\GravityFormsConnect\Connect::get_instance();
        \MailOptin\LeadBankConnect\Connect::get_instance();
        \MailOptin\FacebookCustomAudienceConnect\Connect::get_instance();
        \MailOptin\FormidableFormConnect\Connect::get_instance();
        \MailOptin\ForminatorFormConnect\Connect::get_instance();
        \MailOptin\UltimateMemberConnect\Connect::get_instance();
        \MailOptin\WooCommerceConnect\Connect::get_instance();
        \MailOptin\WooMembershipConnect\Connect::get_instance();
        \MailOptin\WooSubscriptionsConnect\Connect::get_instance();
        \MailOptin\LearnDashConnect\Connect::get_instance();
        \MailOptin\LifterLMSConnect\Connect::get_instance();
        \MailOptin\TutorLMSConnect\Connect::get_instance();
        \MailOptin\MemberPressConnect\Connect::get_instance();
        \MailOptin\PmProConnect\Connect::get_instance();
        \MailOptin\RCPConnect\Connect::get_instance();
        \MailOptin\UserRegistrationOptinConnect\Connect::get_instance();
        \MailOptin\CommentOptinConnect\Connect::get_instance();
        \MailOptin\EasyDigitalDownloadsConnect\Connect::get_instance();
        \MailOptin\GiveWPConnect\Connect::get_instance();
        \MailOptin\FluentFormConnect\Connect::get_instance();
        GoogleAnalytics::get_instance();
    }
}