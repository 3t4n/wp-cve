<?php

require_once FNSF_AF2_MENU_PARENTS_CLASS;
class Fnsf_Af2Integrationen extends Fnsf_Af2MenuCustom {

    protected function fnsf_get_heading() { return 'Integrations'; }
    protected function fnsf_get_menu_custom_template() { return FNSF_AF2_CUSTOM_MENU_INTEGRATIONEN; }

    protected function fnsf_get_menu_blur_option_() { return true; }

    protected function fnsf_get_af2_custom_contents_() {
        
        return array(
            array('name' => 'Deals & Projects',     'type' => 'Agency software',        'active' => 1),
            array('name' => 'KlickTipp',            'type' => 'E-mail marketing',       'active' => 1),
            array('name' => 'ActiveCampaign',       'type' => 'E-mail marketing',       'active' => 1),
            array('name' => 'FinCRM',               'type' => 'Financial CRM',             'active' => 1),
            array('name' => 'Zapier',               'type' => 'Automation',        'active' => 1),
            array('name' => 'MessageBird',          'type' => 'SMS verification',      'active' => 1),
            array('name' => 'HubSpot',              'type' => 'CRM',                    'active' => 1),
            array('name' => 'Pipedrive',            'type' => 'CRM',                    'active' => 1),
            array('name' => 'GetResponse',          'type' => 'E-mail marketing',       'active' => 1),
            array('name' => 'Mailchimp',            'type' => 'E-mail marketing',       'active' => 1)
        );

    }

    protected function fnsf_load_resources() {
        wp_enqueue_style('af2_integrationen_style');

        parent::fnsf_load_resources();
    }
}