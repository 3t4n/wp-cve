<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */
defined("ABSPATH") or die();
$earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
$woocommerce_helper = new \Wlr\App\Helpers\Woocommerce();
if (isset($expire_details) && is_array($expire_details) && isset($expire_details['expire_points']) && !empty($expire_details['expire_points'])): ?>
    <div class="wlr-expire-point-blog" id="<?php echo WLR_PLUGIN_PREFIX . '-expire-point-details-table' ?>"
         style="width: 100%;">
        <div class="wlr-heading-container">
            <h3 class="wlr-heading"><?php echo sprintf(esc_html__('Upcoming %s expiration', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3)); ?></h3>
        </div>
        <div id="<?php echo WLR_PLUGIN_PREFIX . '-expire-point-table' ?>">
            <table class="wlr-table" style="width: 100%;">
                <thead id="<?php echo WLR_PLUGIN_PREFIX . '-expire-point-table-header' ?>" class="wlr-table-header">
                <tr>
                    <th class=" wlr-text-color"
                        style="width: 50%;"><?php echo sprintf(esc_html__('%s available / %s earned', 'wp-loyalty-rules'), ucfirst($earn_campaign_helper->getPointLabel(3)), ucfirst($earn_campaign_helper->getPointLabel(3))); ?></th>
                    <th class="set-center wlr-text-color"
                        style="width: 50%;"><?php echo __('Expires on', 'wp-loyalty-rules') ?></th>
                </tr>
                </thead>
                <?php foreach ($expire_details['expire_points'] as $expire_point): ?>
                    <tr>
                        <td style="width: 50%;"
                            class="<?php echo WLR_PLUGIN_PREFIX . '-transaction-table-body' ?> wlr-text-color wlr-border-color">
                            <?php $available_point = isset($expire_point->available_points) && !empty($expire_point->available_points) ? $expire_point->available_points : '0';
                            $point = isset($expire_point->points) && !empty($expire_point->points) ? $expire_point->points : '0';
                            echo sprintf(__('%s / %s', 'wp-loyalty-rules'), $available_point, $point); ?>
                        </td>
                        <td style="width: 50%;"
                            class="<?php echo WLR_PLUGIN_PREFIX . '-transaction-table-body' ?> set-center wlr-text-color wlr-border-color">
                            <?php echo isset($expire_point->expire_date) && !empty($expire_point->expire_date) ? $woocommerce_helper->beforeDisplayDate($expire_point->expire_date) : '-'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php if (isset($expire_details['expire_points_total']) && $expire_details['expire_points_total'] > 0):
                $endpoint_url = wc_get_endpoint_url('loyalty_reward');
                ?>
                <div style="text-align: right">
                    <?php if (isset($expire_details['offset']) && 1 !== (int)$expire_details['offset']) :
                        $endpoint_url_with_params = add_query_arg(array('expire_point_page' => $expire_details['offset'] - 1), $endpoint_url); ?>
                        <a class="woocommerce-button woocommerce-button--previous woocommerce-Button wlr-cursor wlr-text-color"
                           id="<?php echo WLR_PLUGIN_PREFIX . '-prev-button' ?>"
                           onclick="wlr_jquery( 'body' ).trigger( 'wlr_redirect_url', [ '<?php echo esc_url($endpoint_url_with_params . '#wlr-expire-point-details-table') ?>'] )">
                            <?php esc_html_e('Prev', 'wp-loyalty-rules'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if (isset($expire_details['current_point_expire_count']) && intval($expire_details['current_point_expire_count']) < $expire_details['expire_points_total']) :
                        $endpoint_url_with_params = add_query_arg(array('expire_point_page' => $expire_details['offset'] + 1), $endpoint_url);
                        ?>
                        <a class="woocommerce-button woocommerce-button--next woocommerce-Button wlr-cursor wlr-text-color"
                           id="<?php echo WLR_PLUGIN_PREFIX . '-next-button' ?>"
                           onclick="wlr_jquery( 'body' ).trigger( 'wlr_redirect_url', [ '<?php echo esc_url($endpoint_url_with_params . '#wlr-expire-point-details-table') ?>'] )">
                            <?php esc_html_e('Next', 'wp-loyalty-rules'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
