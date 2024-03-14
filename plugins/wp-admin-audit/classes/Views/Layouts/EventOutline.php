<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_EventOutline implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-event-outline';
    public $event;

    /**
     * @param object $event
     */
    public function __construct($event){
        $this->event = $event;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            $occurredOn = WADA_DateUtils::formatUTCasDatetimeForWP($this->event->occurred_on);
            ?>
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->event->id); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Date', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($occurredOn); ?></td>
                </tr>
                <?php
                if(is_multisite()):
                    $blogDetails = get_blog_details( array( 'blog_id' => $this->event->site_id ) );
                    ?>
                    <tr>
                        <td class="label"><?php _e('Site', 'wp-admin-audit'); ?></td>
                        <td class="value"><?php echo esc_html($blogDetails->blogname) . '(#'.esc_html($this->event->site_id).')'; ?></td>
                    </tr>
                <?php
                endif;

                $sensorLink =  admin_url(sprintf(
                    'admin.php?page=wp-admin-audit-settings&tab=tab-sensors&subpage=sensor&amp;sid=%s',
                    absint( $this->event->sensor_id )
                ));
                ?>
                <tr>
                    <td class="label"><?php _e('Event type', 'wp-admin-audit'); ?></td>
                    <td class="value"><a href="<?php echo $sensorLink; ?>" title="<?php esc_attr_e('Open sensor settings', 'wp-admin-audit'); ?>"><?php echo esc_html($this->event->sensor_name); ?></a></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Severity', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->event->severity_text); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('IP address', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->event->source_ip); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('User agent', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->event->source_client); ?></td>
                </tr><?php
                if(property_exists($this->event, 'nr_queue_entries')){ ?>
                    <tr>
                        <td class="label"><?php _e('#Queue entries', 'wp-admin-audit'); ?></td>
                        <td class="value"><?php echo esc_html($this->event->nr_queue_entries); ?></td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
        <?php

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}