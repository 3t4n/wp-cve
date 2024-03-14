<?php
/**
 * @package talentlms-wordpress
 */

namespace TalentlmsIntegration\Pages;

use TalentlmsIntegration\Services\PluginService;

class Help implements PluginService
{

    public function register(): void
    {
        add_filter(
            'admin_head',
            array($this, 'tlms_contextualHelp'),
            10,
            0
        );
    }

    public function tlms_contextualHelp(): void
    {
        $screen = get_current_screen();
        $screen_id = get_current_screen()->id;

        if ($screen_id == 'toplevel_page_talentlms'
            || $screen_id == 'talentlms_page_talentlms-setup'
            || $screen_id == 'talentlms_page_talentlms-integrations'
        ) {
            $screen->add_help_tab(
                array(
                    'id'      => 'about',
                    'title'   => esc_html__('About TalentLMS', 'talentlms'),
                    'content' =>
                        '<p><strong>' . esc_html__('TalentLMS', 'talentlms') . '</strong>'
                        .esc_html__('a super-easy, cloud-based learning platform to train your people and customers', 'talentlms')
                        .'</p>'
                        .'<p><strong>' . esc_html__('ShortCodes', 'talentlms') . '</strong></p>'
                        .'<ul>'
                        .'<li><strong>[talentlms-courses]</strong>'
                        .esc_html__('Shortcode for listing your TalentLMS courses.', 'talentlms') . '</li>'
                        .'</ul>'
                )
            );
        }
        
        if ($screen_id == 'toplevel_page_talentlms') {
            $screen->add_help_tab(
                array(
                    'id'      => 'screen-content',
                    'title'   => esc_html__('Screen Content', 'talentlms'),
                    'content' =>
                        '<p>' . esc_html__('TalentLMS Setup', 'talentlms') . '</p>'
                        .'<ul>'
                        .'<li>' . '<strong>Setup</strong> '
                        .esc_html__('Setup your TalentLMS domain and API key to get your plugin started.', 'talentlms')
                        .'</li>'
                        .'<li>' . '<strong>Integrations</strong> '
                        .esc_html__('Integrate your TalentLMS WordPress plugin with other popular WordPress plugins', 'talentlms')
                        .'</li>'
                        .'<li>' . '<strong>Shortcodes</strong> '
                        .esc_html__('A coprehensive list of all WordPress TalentLMS plugin\'s shortcodes', 'talentlms')
                        .'</li>'
                        .'<li>' . '<strong>Help</strong> '
                        . esc_html__('Details about the plugin and any help you might need.', 'talentlms')
                        .'</li>'
                        .'</ul>'
                )
            );
        } elseif ($screen_id == 'talentlms_page_talentlms-setup') {
            $screen->add_help_tab(
                array(
                    'id'      => 'screen-content',
                    'title'   => esc_html__('Screen Content', 'talentlms'),
                    'content' =>
                        '<p>' . esc_html__('TalentLMS Setup') . ':</p>'
                        .'<ul>'
                        .'<li>'
                        . esc_html__('TalentLMS Domain: Fill in your TalentLMS domain. A valid TalentLMS domain for the plugin would be like: <pre>&lt;your_domain&gt;.talentlms.com</pre> Do not include the prefix http(s)://', 'talentlms')
                        .'</li>'
                        .'<li>'
                        . esc_html__('API Key: Fill in your TalentLMS API key. You can find this in your TalentLMS  Home / Account & Settings > Security. Click on <i>Enable the API</i> and copy paste your API key.', 'talentlms')
                        .'</li>'
                        .'</ul>'
                )
            );
        } elseif ($screen_id == 'talentlms_page_talentlms-integrations') {
            $screen->add_help_tab(
                array(
                    'id'      => 'screen-content',
                    'title'   => esc_html__('TalentLMS Integrations', 'talentlms'),
                    'content' =>
                        '<p>' . esc_html__('WooCommerce', 'talentlms') . ':</p>'
                        .'<ul>'
                        .'<li>' . '<strong>'. __('Refresh course list', 'talentlms').':</strong> '
                        . esc_html__('Click this option in case some of your TalentLMS courses do not appear in the list', 'talentlms')
                        .'</li>'
                        .'<li>' . '<strong>'. __('Integrate', 'talentlms') . ':</strong> '
                        . esc_html__('Choose your TalentLMS courses you want to integrate as WooCommerce products. All TalentLMS categories will be integrated by default. In case you need to integrate courses that have been already integrated choose the option "Re-sync"', 'talentlms')
                        .'</li>'
                        .'</ul>'
                )
            );
        }

        if ($screen_id == 'toplevel_page_talentlms'
            || $screen_id == 'talentlms_page_talentlms-setup'
            || $screen_id == 'talentlms_page_talentlms-integrations'
        ) {
            $screen->set_help_sidebar(
                '<p><strong>'.esc_html__('For more information', 'talentlms').':</strong></p>'
                .'<p><a href="https://www.talentlms.com/" target="_blank">'
                .esc_html__('TalentLMS', 'talentlms')
                .'</a></p>'
                .'<p><a href="https://support.talentlms.com/" target="_blank">'
                .esc_html__('Support', 'talentlms')
                .'</a></p>'
            );
        }
    }
}
