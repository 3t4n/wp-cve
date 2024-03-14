<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Core_BlockFeatures extends RKMW_Classes_BlockController {

    /** @var object checkin */
    public $checkin;

    /** @var array features */
    public $features;

    /**
     * Initialize Features
     * @return mixed|void
     */
    public function init() {
        parent::init();

        //Checkin to API
        $this->checkin = RKMW_Classes_RemoteController::checkin();

        //Set the features
        $this->features = $this->getFeatures();

        //show the features block
        echo $this->getView('Blocks/Features');
    }

    /**
     * Get the Plugin Features
     * @return mixed|void
     */
    public function getFeatures() {
        $features = array(
            array(
                'title' => esc_html__("Keyword Research", RKMW_PLUGIN_NAME),
                'description' => esc_html__("Find the Best Keywords that your own website can rank for and get personalized competition data for each keyword.", RKMW_PLUGIN_NAME),
                'mode' => esc_html__("Freemium", RKMW_PLUGIN_NAME),
                'option' => (bool)RKMW_Classes_Helpers_Tools::getMenuVisible('research/research'),
                'menu' => 'research/research',
                'logo' => 'kr_92.png',
                'link' => ((isset($this->checkin->subscription_kr) && $this->checkin->subscription_kr) ? RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'research') : RKMW_Classes_RemoteController::getCloudLink('plans')),
                'dependency' => false,
            ), //Keyword Research
            array(
                'title' => esc_html__("Keywords Briefcase", RKMW_PLUGIN_NAME),
                'description' => esc_html__("Add keywords in your portfolio based on your current Campaigns, Trends, Performance for a successful SEO strategy.", RKMW_PLUGIN_NAME),
                'mode' => esc_html__("Free", RKMW_PLUGIN_NAME),
                'option' => (bool)RKMW_Classes_Helpers_Tools::getMenuVisible('research/briefcase'),
                'menu' => 'research/briefcase',
                'logo' => 'briefcase_92.png',
                'link' => RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'briefcase'),
                'dependency' => false,
            ),//SEO Briefcase
            array(
                'title' => esc_html__("Keywords Suggestion", RKMW_PLUGIN_NAME),
                'description' => esc_html__("See the trending keywords suitable for your website's future topics. We check for new keywords weekly based on your latest researches.", RKMW_PLUGIN_NAME),
                'mode' => esc_html__("Free", RKMW_PLUGIN_NAME),
                'option' => (bool)RKMW_Classes_Helpers_Tools::getMenuVisible('research/suggested'),
                'menu' => 'research/suggested',
                'logo' => 'suggested_92.png',
                'link' => RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'suggested'),
                'dependency' => false,
            ),
            array(
                'title' => esc_html__("Keywords History", RKMW_PLUGIN_NAME),
                'description' => esc_html__("We save the researched for the last 30 days. Use this to see the research progress and to find even better keywords.", RKMW_PLUGIN_NAME),
                'mode' => esc_html__("Free", RKMW_PLUGIN_NAME),
                'option' => (bool)RKMW_Classes_Helpers_Tools::getMenuVisible('research/history'),
                'menu' => 'research/history',
                'logo' => 'history_92.png',
                'link' => RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'history'),
                'dependency' => false,
            ),
            array(
                'title' => esc_html__("Google Rankings", RKMW_PLUGIN_NAME),
                'description' => esc_html__("Track your average rankings evolution from Google Search Console for your organic searched keywords.", RKMW_PLUGIN_NAME),
                'mode' => esc_html__("Free", RKMW_PLUGIN_NAME),
                'option' => (bool)RKMW_Classes_Helpers_Tools::getMenuVisible('rankings/rankings'),
                'menu' => 'rankings/rankings',
                'logo' => 'ranking_92.png',
                'link' => RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'rankings'),
                'dependency' => false,
            ), //Google Search Console
            array(
                'title' => esc_html__("Google Search Console", RKMW_PLUGIN_NAME),
                'description' => esc_html__("Connect your website with Google Search Console and get insights based on organic searched keywords.", RKMW_PLUGIN_NAME),
                'mode' => esc_html__("Free", RKMW_PLUGIN_NAME),
                'option' => (bool)RKMW_Classes_Helpers_Tools::getMenuVisible('rankings/gscsync'),
                'menu' => 'rankings/gscsync',
                'logo' => 'websites_92.png',
                'link' => RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'gscsync'),
                'dependency' => false,
            ), //Google Search Console
//            array(
//                'title' => esc_html__("SEO Audit", RKMW_PLUGIN_NAME),
//                'description' => esc_html__("Improve your Online Presence by knowing how your website is performing.", RKMW_PLUGIN_NAME),
//                'mode' => esc_html__("Free", RKMW_PLUGIN_NAME),
//                'option' => esc_html__("Available Soon", RKMW_PLUGIN_NAME),
//                'menu' => false,
//                'logo' => 'audit_92.png',
//                'link' => false,
//                'dependency' => false,
//            ), //SEO Audit

//            array(
//                'title' => esc_html__("Google Rankings Check", RKMW_PLUGIN_NAME),
//                'description' => esc_html__("Accurately track your rankings with the user-friendly Google Rankings Checker.", RKMW_PLUGIN_NAME),
//                'mode' => esc_html__("PRO", RKMW_PLUGIN_NAME),
//                'option' => ((isset($this->checkin->subscription_serpcheck) && $this->checkin->subscription_serpcheck) ? true : esc_html__("Buy Credits", RKMW_PLUGIN_NAME)),
//                'menu' => false,
//                'logo' => 'ranking_92.png',
//                'link' => ((isset($this->checkin->subscription_serpcheck) && $this->checkin->subscription_serpcheck) ? RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'rankings') : RKMW_Classes_RemoteController::getCloudLink('plans')),
//                'dependency' => esc_html__("Google Rankings", RKMW_PLUGIN_NAME),
//            ), //Google SERP Checker

        );

        return apply_filters('rkmw_features', $features);
    }

    /**
     * Hook the action for this plugin
     */
    public function action() {
        if (!current_user_can('rkmw_manage_settings')) {
            return;
        }

        switch (RKMW_Classes_Helpers_Tools::getValue('action')) {
            case 'rkmw_settings_feature':
                $menu = RKMW_Classes_Helpers_Tools::getValue('menu');

                if (!empty($menu)) {
                    $dbmenu = RKMW_Classes_Helpers_Tools::getOption('menu');
                    $menu = array_merge($dbmenu, $menu);

                    RKMW_Classes_Helpers_Tools::saveOptions('menu', $menu);
                    RKMW_Classes_RemoteController::saveOptions(array('menu' => $menu));
                }
                break;
        }
    }
}
