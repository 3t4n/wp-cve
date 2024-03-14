<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_EventsList implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-events-list';
    public $events;
    public $userId;
    public $onlyShowTimeSince;

    /**
     * @param object[] $events
     */
    public function __construct($events, $onlyShowTimeSince=false){
        $this->events = $events;
        $this->onlyShowTimeSince = $onlyShowTimeSince;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->events && count($this->events)):
            $colspan = 2;
            $timestampHeader = __('Date / Time', 'wp-admin-audit');
            if ($this->onlyShowTimeSince) {
                $colspan = 1;
                $timestampHeader = __('When', 'wp-admin-audit');
            }
            ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table form-table wada-compact-table striped widefat wada-50p-first-col">
                <thead>
                    <tr>
                        <th><?php _e('Event', 'wp-admin-audit'); ?></th>
                        <th colspan="<?php echo $colspan; ?>"><?php echo $timestampHeader; ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($this->events AS $event):
                    $eventId = is_array($event) ? $event['id'] : $event->id;
                    $summary = is_array($event) ? $event['summary_short'] : $event->summary_short;
                    $occurredOn = is_array($event) ? $event['occurred_on'] : $event->occurred_on;
                    WADA_Log::debug('EventsList->display event: '.print_r($event, true));
                    $linkUrl = sprintf(admin_url('admin.php?page=wp-admin-audit-events&subpage=event-details&sid=%d'), $eventId);
                    $eventLink = sprintf('<a href="%s">%s</a>', $linkUrl, $summary);

                    $timeAgoStr = WADA_DateUtils::timeAgo($occurredOn);
                    if(!$this->onlyShowTimeSince){
                        $timeAgoStr = '('.$timeAgoStr.')';
                    }
                    ?>
                    <tr>
                        <td><?php echo $eventLink; ?></td>
                        <?php if(!$this->onlyShowTimeSince): ?>
                            <td><?php echo esc_html(WADA_DateUtils::formatUTCasDatetimeForWP($occurredOn)); ?></td>
                        <?php endif; ?>
                        <td><?php echo $timeAgoStr; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        else: ?>
            <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
                <table class="data wada-detail-table form-table wada-compact-table">
                    <tbody>
                    <tr>
                        <th><?php _e('Event', 'wp-admin-audit'); ?></th>
                        <th colspan="2"><?php _e('Date', 'wp-admin-audit'); ?></th>
                    </tr>
                    <tr>
                        <td class="value" colspan="2"><strong><?php _e('No events', 'wp-admin-audit'); ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php
        endif;

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}