<div class="wobel-float-side-modal" id="wobel-float-side-modal-filter-profiles">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('Filter Profiles', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-float-side-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-filter-profiles-items wobel-pb30">
                            <div class="wobel-table-border-radius">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Profile Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Date Modified', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Use Always', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                            <th><?php esc_html_e('Actions', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($filters_preset)) : ?>
                                            <?php foreach ($filters_preset as $filter_item) : ?>
                                                <?php include "filter_profile_item.php"; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>