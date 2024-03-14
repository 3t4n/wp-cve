<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_NotificationOutline implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-notification-outline';
    public $notification;

    /**
     * @param object $notification
     */
    public function __construct($notification){
        $this->notification = $notification;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            WADA_Log::debug('NotificationOutline notification: '.print_r($this->notification, true));
            ?>
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->notification->id); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Name', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->notification->name); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Active', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo (intval($this->notification->active) > 0 ? __('Yes', 'wp-admin-audit') : __('No', 'wp-admin-audit')); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Triggers', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo WADA_Model_Notification::printOverviewOfTriggers($this->notification->triggers, array('headline'=>'h4', 'subheader'=>'strong', 'linebreaks'=>true)); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Targets', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo WADA_Model_Notification::printOverviewOfTargets($this->notification->targets, array('subheader'=>'strong', 'linebreaks'=>true)); ?></td>
                </tr>
                <?php
                if(property_exists($this->notification, 'events')){ ?>
                    <tr>
                        <td class="label"><?php _e('#Events', 'wp-admin-audit'); ?></td>
                        <td class="value"><?php echo count($this->notification->events); ?></td>
                    </tr>
                    <?php
                }
                if(property_exists($this->notification, 'nr_queue_entries')){ ?>
                    <tr>
                        <td class="label"><?php _e('#Queue entries', 'wp-admin-audit'); ?></td>
                        <td class="value"><?php echo esc_html($this->notification->nr_queue_entries); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <?php

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}