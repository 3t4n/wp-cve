<?php $industries = wobel\classes\helpers\Industry_Helper::get_industries(); ?>

<div id="wobel-body">
    <div class="wobel-dashboard-body">
        <div id="wobel-activation">
            <?php if (isset($is_active) && $is_active === true && $activation_skipped !== true) : ?>
                <div class="wobel-wrap">
                    <div class="wobel-tab-middle-content">
                        <div id="wobel-activation-info">
                            <strong><?php esc_html_e("Congratulations, Your plugin is activated successfully. Let's Go!", 'ithemeland-woocommerce-bulk-orders-editing-lite') ?></strong>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="wobel-wrap wobel-activation-form">
                    <div class="wobel-tab-middle-content">
                        <?php if (!empty($flush_message) && is_array($flush_message)) : ?>
                            <div class="wobel-alert <?php echo ($flush_message['message'] == "Success !") ? "wobel-alert-success" : "wobel-alert-danger"; ?>">
                                <span><?php echo esc_html($flush_message['message']); ?></span>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="wobel-activation-form">
                            <h3 class="wobel-activation-top-alert">Fill the below form to get the latest updates' news and <strong style="text-decoration: underline;">Special Offers(Discount)</strong>, Otherwise, Skip it!</h3>
                            <input type="hidden" name="action" value="wobel_activation_plugin">
                            <div class="wobel-activation-field">
                                <label for="wobel-activation-email"><?php esc_html_e('Email', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> </label>
                                <input type="email" name="email" placeholder="Email ..." id="wobel-activation-email">
                            </div>
                            <div class="wobel-activation-field">
                                <label for="wobel-activation-industry"><?php esc_html_e('What is your industry?', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> </label>
                                <select name="industry" id="wobel-activation-industry">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                    <?php
                                    if (!empty($industries)) :
                                        foreach ($industries as $industry_key => $industry_label) :
                                    ?>
                                            <option value="<?php echo esc_attr($industry_key); ?>"><?php echo esc_attr($industry_label); ?></option>
                                    <?php
                                        endforeach;
                                    endif
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="activation_type" id="wobel-activation-type" value="">
                            <button type="button" id="wobel-activation-activate" class="wobel-button wobel-button-lg wobel-button-blue" value="1"><?php esc_html_e('Activate', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
                            <button type="button" id="wobel-activation-skip" class="wobel-button wobel-button-lg wobel-button-gray" style="float: left;" value="skip"><?php esc_html_e('Skip', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>