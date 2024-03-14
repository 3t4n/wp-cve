<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */
defined('ABSPATH') or die;
?>
<div id="wlr-main">
    <section class="full-width">
        <div class="row full-width">
            <div class="columns twelve mtb-2">
                <div class="lpr-row side-elements">
                    <a class="button lpr-btn-back"
                       href="<?php echo esc_url(admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'point_users')))) ?>">
                        <?php esc_html_e('Back', 'wp-loyalty-rules'); ?>
                    </a>
                </div>
            </div>
            <div class="lpr-row lpr-card-customer-main">
                <div class="lpr-card-customer">
                    <div class="lpr_card">
                        <div class="lpr_card-body">
                            <form action="<?php echo esc_url($base_url); ?>" method="post" class="wlr-imprt-preview"
                                  id="wlr_imprt_preview_form_id" enctype="multipart/form-data">
                                <?php $total_count = (isset($total_count) && !empty($total_count)) ? $total_count : 0; ?>
                                <!-- file data -->
                                <input type="hidden" name="name"
                                       value="<?php echo (isset($file['name']) && !empty($file['name'])) ? esc_attr($file['name']) : ''; ?>"/>
                                <input type="hidden" name="type"
                                       value="<?php echo (isset($file['type']) && !empty($file['type'])) ? esc_attr($file['type']) : ''; ?>"/>
                                <input type="hidden" name="tmp_name"
                                       value="<?php echo (isset($file['tmp_name']) && !empty($file['tmp_name'])) ? esc_attr($file['tmp_name']) : ''; ?>"/>
                                <input type="hidden" name="error"
                                       value="<?php echo (isset($file['error']) && !empty($file['error'])) ? esc_attr($file['error']) : ''; ?>"/>
                                <input type="hidden" name="size"
                                       value="<?php echo (isset($file['size']) && !empty($file['size'])) ? esc_attr($file['size']) : ''; ?>"/>
                                <input type="hidden" id="wlr-total-count" name="total_count"
                                       value="<?php echo esc_attr($total_count); ?>"/>
                                <input type="hidden" id="wlr-need-update" name="need_update"
                                       value="<?php echo (isset($need_update) && !empty($need_update)) ? esc_attr($need_update) : 'no'; ?>"/>
                                <table>
                                    <tr>
                                        <td><?php esc_html_e('Total items:', 'wp-loyalty-rules'); ?></td>
                                        <td><?php echo esc_html(sprintf('%s', $total_count)); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_html_e('Processed items:', 'wp-loyalty-rules'); ?></td>
                                        <td>
                                            <span id="wlr-process-count"><?php echo esc_html($process_count); ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td><?php esc_html_e('Field Name', 'wp-loyalty-rules'); ?></td>
                                        <td><?php esc_html_e('Field Value', 'wp-loyalty-rules'); ?></td>
                                    </tr>
                                    <?php if (isset($header) && !empty($header)): ?>
                                        <?php foreach ($header as $header_key => $header_value): ?>
                                            <tr>
                                                <td><?php echo esc_html($header_key); ?></td>
                                                <td><?php echo esc_html($header_value); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </table>
                                <input type="hidden" id="limit_start" name="limit_start" value="0"/>
                                <div class="row row-brd">
                                    <div class="lpr-label-input float-right">
                                        <a id="wlr-process-import-button"
                                           class="button"> <?php esc_html_e('Import', 'wp-loyalty-rules'); ?></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="notification" class="lpr-notification">

                </div>
            </div>
        </div>
    </section>
</div>