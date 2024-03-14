<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
 */

if(!defined('ABSPATH')){
    exit;
}

class WADA_Widget_LastActivities implements WADA_Widget_WidgetInterface
{
    const WIDGET_IDENTIFIER = 'wada-widget-last-activities';

    public function __construct(){
        add_action('wp_dashboard_setup', array($this, 'addIfActiveAndRelevant'));
    }

    public function addIfActiveAndRelevant(){
        $active = WADA_Settings::isLastActivitiesWidgetEnabled();
        if($active){
            $userCanManageOptions = current_user_can( 'manage_options' ); // consistent with permissions elsewhere in plugin - only then add dashboard widget
            if($userCanManageOptions) {
                wp_add_dashboard_widget(
                    static::WIDGET_IDENTIFIER,
                    'WP Admin Audit' . ' &#8212; ' . __('Last Activities', 'wp-admin-audit'),
                    array($this, 'display')
                );
            }
        }
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }

        $eventsView = new WADA_View_Events();
        $nrOfItems = WADA_Settings::getLastActivitiesWidgetNrOfItems(5);
        $events = $eventsView->getItemsListOnly($nrOfItems, 1);
        WADA_Log::debug('LastActivities->display events: '.print_r($events, true));
        (new WADA_Layout_EventsList($events, true))->display();
        ?>
        <div class="wada-central-container">
            <a href="<?php echo admin_url('admin.php?page=wp-admin-audit-events'); ?>"><?php echo __('View event log', 'wp-admin-audit'); ?></a>
        </div>
        <?php

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }

}