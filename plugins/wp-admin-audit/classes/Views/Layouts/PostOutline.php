<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Layout_PostOutline implements WADA_Layout_LayoutInterface
{
    const LAYOUT_IDENTIFIER = 'wada-layout-post-outline';
    public $wpPost;
    public $postId;
    public $event;

    /**
     * @param WP_Post $wpPost
     * @param object $event
     */
    public function __construct($wpPost, $event){
        $this->wpPost = $wpPost;
        $this->event = $event;
        $this->postId = $event->object_id;
    }

    public function display($returnAsString = false){
        if($returnAsString){
            ob_start();
        }
        if($this->wpPost):
    ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <?php
            $postDate = WADA_DateUtils::formatWPDatetimeForWP($this->wpPost->post_date);
            ?>
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->wpPost->ID); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Title', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->wpPost->post_title); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Date', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($postDate); ?></td>
                </tr>
                <tr>
                    <td class="label"><?php _e('Type', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->wpPost->post_type); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        else: ?>
        <div class="<?php echo self::LAYOUT_IDENTIFIER; ?>">
            <table class="data wada-detail-table">
                <tbody>
                <tr>
                    <td class="label"><?php _e('ID', 'wp-admin-audit'); ?></td>
                    <td class="value"><?php echo esc_html($this->postId); ?></td>
                </tr>
                <tr>
                    <td class="value" colspan="2">
                        <strong>
                            <?php if($this->event->event_group == WADA_Sensor_Base::GRP_MEDIA): ?><?php _e('Media subject no longer existing', 'wp-admin-audit'); ?>
                            <?php else: ?><?php _e('Post no longer existing', 'wp-admin-audit'); ?><?php endif; ?>
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php
        endif;

        return WADA_HtmlUtils::returnAsStringOrRender($returnAsString);
    }
}