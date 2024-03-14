<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

defined('ABSPATH') or die;
$woocommerce = \Wlr\App\Helpers\Woocommerce::getInstance();
$base_url = isset($base_url) ? $base_url : '';
$app_url = isset($app_url) ? $app_url : '#';
$back = (isset($back) && !empty($back)) ? $back : '';
$search = isset($search) && !empty($search) ? $search : '';
$search_email = (isset($search_email) && !empty($search_email)) ? $search_email : '';
$wp_date_format = isset($wp_date_format) && !empty($wp_date_format) ? $wp_date_format : 'Y-m-d';
?>
<div id="wlpe-expire-points">
    <div class="wlpe-expire-points-content-holder">
        <form action="<?php echo esc_url($base_url); ?>" method="post"
              id="manage_customer_expire_point_form"
              name="manage_customer_expire_point">
            <div class="content-header">
                <div class="heading"><p><?php esc_html_e('MANAGE POINTS EXPIRY', 'wp-loyalty-rules') ?></p></div>
                <div class="wlpe-search-filter-block">
                    <div class="wlpe-back-to-apps">
                        <a class="button" target="_self"
                           href="<?php echo esc_url($app_url); ?>">
                            <img src="<?php echo esc_url($back); ?>"
                                 alt="<?php esc_attr_e("Back", "wp-loyalty-rules"); ?>">
                            <?php esc_html_e('Back to WPLoyalty', 'wp-loyalty-rules'); ?></a>
                    </div>
                    <div class="search">
                        <input type="text" name="search"
                               placeholder="<?php esc_attr_e('Search by customer email address', 'wp-loyalty-rules') ?>"
                               value="<?php echo esc_attr($search); ?>"/>
                        <a onclick="wlpe_jquery('#manage_customer_expire_point_form').submit();"
                           class="wlpe-email-search">
                            <img src="<?php echo esc_url($search_email); ?>" alt="search">
                        </a>
                    </div>
                    <div class="wlpe-filter" id="wlpe-filter-status-block"
                         onclick="wlpe.showFilter()">
                        <?php if (isset($filter_status) && !empty($filter_status) && isset($point_sort) && !empty($point_sort)): ?>
                            <?php foreach ($filter_status as $key => $status): ?>
                                <div class="wlpe-filter-status">
                                    <button
                                        type="button" <?php echo $key === $point_sort ? 'class="active-filter"' : '' ?>
                                        onclick="wlpe.filterPoints('#wlpe-main #manage_customer_expire_point_form','<?php echo esc_js($key); ?>')"
                                        value="<?php echo esc_attr($key); ?>"><?php esc_html_e($status, 'wp-loyalty-rules') ?></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="point_sort"
                   value="<?php echo isset($point_sort) ? esc_attr($point_sort) : 'all'; ?>"/>
            <input type="hidden" name="page" value="<?php echo esc_attr(WLPE_PLUGIN_SLUG); ?>"/>
            <input type="hidden" name="view" value="expire_points"/>
            <input type="hidden" name="sort_order" id="user_expire_point_filter_order"
                   value="<?php echo isset($filter_order) ? esc_attr($filter_order) : 'id'; ?>"/>
            <input type="hidden" name="sort_order_dir" id="user_expire_point_filter_order_dir"
                   value="<?php echo isset($filter_order_dir) ? esc_attr($filter_order_dir) : 'ASC'; ?>"/>
        </form>
        <?php if (empty($items)):
            $no_points_yet = (isset($no_points_yet) && !empty($no_points_yet)) ? $no_points_yet : '';
            ?>
            <div class="wlpe-no-points">
                <div>
                    <img src="<?php echo esc_url($no_points_yet); ?>" alt="">
                </div>
                <div class="no-points-label">
                    <?php esc_html_e('No transactions yet. You will see points and their expiry here after you have enabled this feature.', 'wp-loyalty-rules') ?>
                </div>
            </div>
        <?php else: ?>
            <div class="wlpe-body-content">
                <div class="wlpe-body-header">
                    <div><b><?php esc_html_e('CUSTOMER', 'wp-loyalty-rules') ?></b></div>
                    <div><b><?php esc_html_e('POINT AVAILABLE / EARNED', 'wp-loyalty-rules') ?></b>
                    </div>
                    <div><b><?php esc_html_e('STATUS', 'wp-loyalty-rules') ?></b></div>
                    <div><b><?php esc_html_e('POINTS USED', 'wp-loyalty-rules') ?></b></div>
                    <div><b><?php esc_html_e('EXPIRES ON', 'wp-loyalty-rules') ?></b></div>
                    <div><b><?php esc_html_e('EMAIL EXPIRE ON', 'wp-loyalty-rules') ?></b></div>
                    <div><b><?php esc_html_e('LAST MODIFIED', 'wp-loyalty-rules') ?></b></div>
                </div>
                <div class="wlpe-body-data">
                    <?php if (isset($items) && !empty($items) && is_array($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <div class="wlpe-data-row">
                                <div class="customer">
                                    <p><?php echo isset($item->user_email) && !empty($item->user_email) ? esc_html($item->user_email) : '' ?></p>
                                    <small><?php echo isset($item->created_at) && !empty($item->created_at) ? esc_html(sprintf(__("Created at: %s", "wp-loyalty-rules"), $item->created_at)) : '-' ?></small>
                                </div>
                                <div class="available-points">
                                    <?php $available_point_content = (isset($item->available_points) && !empty($item->available_points) ? $item->available_points : '0') . ' / ' . (isset($item->points) && !empty($item->points) ? $item->points : '0'); ?>
                                    <p><?php echo esc_html($available_point_content); ?></p>
                                </div>
                                <div class="point-status">
                                    <?php $status = isset($item->status) && !empty($item->status) ? $item->status : ""; ?>
                                    <p class="<?php echo esc_attr($status) ?>"><?php echo esc_html($status); ?></p>
                                </div>
                                <div class="used-points">
                                    <p><?php echo isset($item->used_points) && !empty($item->used_points) ? (int)$item->used_points : '-' ?></p>
                                </div>
                                <div class="expiery-date-block expiry-date">
                                    <div class="<?php echo esc_attr('point-expiery-date-' . $item->id); ?>">
                                        <?php $expire_date = isset($item->expire_date) && !empty($item->expire_date) ? $item->expire_date : '-'; ?>
                                        <p><?php echo esc_html($expire_date); ?></p>
                                        <?php if (in_array($item->status, array('open', 'active'))): ?>
                                            <i class="wlr wlrf-edit"
                                               id="<?php echo esc_attr('wlpe-edit-expiery-date-' . $item->id) ?>"
                                               onclick="wlpe.showWlpeDatePicker('point','<?php echo esc_js($item->id) ?>','<?php echo esc_js($item->expire_date) ?>');"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="<?php echo esc_attr('point-update-date-' . $item->id); ?>"
                                         style="display: none">
                                        <?php
                                        $original_date = ($wp_date_format != 'm/d/Y') ? str_replace('/', '-', $expire_date) : $expire_date;
                                        $new_date = date("Y-m-d", strtotime($original_date));
                                        ?>
                                        <span class="spinner"></span>
                                        <input type="date"
                                               id="<?php echo esc_attr('wlpe-point-expiry-date-picker-' . $item->id); ?>"
                                               class="<?php echo esc_attr('wlpe-update-point-expiery-date-' . $item->id); ?>"
                                               value="<?php echo esc_attr($new_date); ?>"
                                               min="<?php echo esc_attr(date('Y-m-d')); ?>"
                                        >
                                        <i class="wlr wlrf-tick" id="wlpe-point-expire-save-date"
                                           onclick="wlpe.updatePointExpieryDate(<?php echo esc_js($item->id) ?>,'point-expiry-date')"></i>
                                        <i class="wlr wlrf-close" id="wlpe-point-expire-close-date"
                                           onclick="wlpe.closeWlpeDatePicker('point',<?php echo esc_js($item->id) ?>);"></i>
                                    </div>
                                </div>
                                <div class="expiery-date-block email-expiry-date">
                                    <div class="<?php echo esc_attr('email-expiery-date-' . $item->id); ?>">
                                        <?php $expire_email_date = isset($item->expire_email_date) && !empty($item->expire_email_date) ? $item->expire_email_date : '-'; ?>
                                        <p><?php echo esc_html($expire_email_date); ?></p>
                                        <?php if (in_array($item->status, array('open', 'active')) && $item->is_expire_email_send == 0): ?>
                                            <i class="wlr wlrf-edit"
                                               id="<?php echo esc_attr('wlpe-edit-email-expiery-date-' . $item->id); ?>"
                                               onclick="wlpe.showWlpeDatePicker('email','<?php echo esc_js($item->id) ?>','<?php echo esc_js($item->expire_email_date) ?>');"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="<?php echo esc_attr('email-update-date-' . $item->id) ?>"
                                         style="display: none">
                                        <?php
                                        $original_email_expiry_date = ($wp_date_format != 'm/d/Y') ? str_replace('/', '-', $expire_email_date) : $expire_email_date;
                                        $new_email_expiry_date = date("Y-m-d", strtotime($original_email_expiry_date));
                                        ?>
                                        <span class="spinner"></span>
                                        <input type="date"
                                               id="<?php echo esc_attr('wlpe-email-expiry-date-picker-' . $item->id); ?>"
                                               class="<?php echo esc_attr('wlpe-update-email-expiery-date-' . $item->id); ?>"
                                               value="<?php echo esc_attr($new_email_expiry_date) ?>"
                                               min="<?php echo esc_attr(date('Y-m-d')) ?>"
                                               max="<?php echo ($new_date <= date('Y-m-d')) ? '': esc_attr($new_date) ?>">
                                        <i class="wlr wlrf-tick" id="wlpe-email-expire-save-date"
                                           onclick="wlpe.updateEmailExpiryDate(<?php echo esc_attr($item->id) ?>,'email-expiry-date')"></i>
                                        <i class="wlr wlrf-close" id="wlpe-email-expire-close-date"
                                           onclick="wlpe.closeWlpeDatePicker('email',<?php echo esc_attr($item->id); ?>);"></i>
                                    </div>
                                </div>
                                <div>
                                    <p><?php echo isset($item->modified_at) && !empty($item->modified_at) ? esc_attr($item->modified_at) : '-' ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (isset($pagination)): ?>
                <div class="wlpe-pagination">
                    <?php echo $pagination->createLinks(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>
