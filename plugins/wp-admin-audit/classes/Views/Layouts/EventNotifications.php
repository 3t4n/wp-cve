<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_EventNotifications implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-event-notifications';
    public $eventNotifications;
    public $postId;

    /**
     * @param array $eventNotifications
     */
    public function __construct($eventNotifications){
        $this->eventNotifications = $eventNotifications;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->eventNotifications && count($this->eventNotifications)):
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table form-table wada-compact-table">
                <tbody>
                    <tr>
                        <th><?php _e('Notification name', 'wp-admin-audit'); ?></th>
                        <th><?php _e('Notification log', 'wp-admin-audit'); ?></th>
                        <th><?php _e('Sending complete', 'wp-admin-audit'); ?></th>
                        <th><?php _e('Send errors', 'wp-admin-audit'); ?></th>
                        <?php if(property_exists($this->eventNotifications[0], 'nr_queue_entries')): ?>
                            <th><?php _e('Queue (#entries)', 'wp-admin-audit'); ?></th>
                        <?php endif; ?>
                    </tr>
                <?php
                foreach($this->eventNotifications AS $eventNotification){
                    $sentOn = WADA_DateUtils::formatUTCasDatetimeForWP($eventNotification->sent_on);
                    $eventLogUrl = admin_url(
                            sprintf(
                                    'admin.php?page=wp-admin-audit-notifications&subpage=log&enid=%s',
                                absint( $eventNotification->id )
                            )
                    );
                    $eventLogLink = sprintf(
                            '<a href="'.$eventLogUrl.'">%s</a>',
                            __('View log', 'wp-admin-audit')
                    );

                    $eventQueueUrl = admin_url(
                        sprintf(
                            'admin.php?page=wp-admin-audit-notifications&subpage=queue&enid=%s',
                            absint( $eventNotification->id )
                        )
                    );
                    $eventQueueLink = sprintf(
                            '<a href="'.$eventQueueUrl.'">%s</a>',
                        sprintf(__('View queue (%d entries)', 'wp-admin-audit'), $eventNotification->nr_queue_entries)
                    );
                    ?>
                        <tr>
                            <td><?php echo $eventNotification->notification_name; ?></td>
                            <td><?php echo $eventLogLink; ?></td>
                            <td><?php echo ($eventNotification->sent > 0) ? '<span class="wada-green"><span class="dashicons dashicons-yes"></span> '.$sentOn .'</span>' : '<span class="wada-warning">'.__('No, still in progress', 'wp-admin-audit').'</span>' ; ?></td>
                            <td><?php echo ($eventNotification->send_errors > 0) ? ('<span class="wada-error"><span class="dashicons dashicons-warning"></span> '.__('Yes', 'wp-admin-audit').'</span>') : ('<span class="wada-greyed-out">'.__('No', 'wp-admin-audit').'</span>'); ?></td>
                            <?php if(property_exists($this->eventNotifications[0], 'nr_queue_entries')): ?>
                                <td><?php echo (($eventNotification->nr_queue_entries > 0) ? $eventQueueLink : ('<span class="wada-greyed-out">'.esc_html($eventNotification->nr_queue_entries).'</span>')); ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    <?php
        else: ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="value" colspan="2"><strong><?php _e('No event notifications', 'wp-admin-audit'); ?></strong></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        endif;

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}