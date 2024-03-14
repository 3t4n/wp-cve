<div class="wrap">
    <?php
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/google-services/menu.php');
    wp_enqueue_script('jquery');
    ?>
    <h2 class="wpms_uppercase"><?php esc_html_e('Google Analytics Tracking Methods', 'wp-meta-seo') ?></h2>
    <form name="input" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" style="margin-top: 20px">
        <?php wp_nonce_field('gadash_form', 'gadash_security'); ?>
        <input type="hidden" name="wpms_nonce" value="<?php echo esc_attr(wp_create_nonce('wpms_nonce')) ?>">
        <div class="wpmsga-advanced-configuration">
            <div class="content-box">
                <p class="ju-description">
                    <?php esc_html_e('You can active the tracking for 4 methods: Universal,
                     Classic Analytics(legacy) or using the Analytics v4 or Tag manager', 'wp-meta-seo') ?></p>
                <table class="wpms-settings-options">
                    <tr>
                        <td class="wpms-settings-title">
                            <label for="wpms_gg_service_tracking_type"
                                   class="wpms-text"
                                   title="<?php esc_attr_e('Analytics tracking type', 'wp-meta-seo') ?>">
                                <?php esc_html_e('Analytics property type', 'wp-meta-seo'); ?></label>
                        </td>
                        <td>
                            <label>
                                <select onclick="requireTrackingIdTitle(this.value)" id="wpms_gg_service_tracking_type"
                                        class="wpms-large-input"
                                        name="_metaseo_gg_service_disconnect[wpms_gg_service_tracking_type]">
                                    <option value="universal"
                                        <?php
                                        selected($this->gaDisconnect['wpms_gg_service_tracking_type'], 'universal') ?>>
                                        <?php esc_html_e('Universal Analytics', 'wp-meta-seo') ?>
                                    </option>
                                    <option value="classic"
                                        <?php
                                        selected($this->gaDisconnect['wpms_gg_service_tracking_type'], 'classic') ?>>
                                        <?php esc_html_e('Classic Analytics', 'wp-meta-seo') ?>
                                    </option>
                                    <option value="analytics4"
                                        <?php
                                        selected($this->gaDisconnect['wpms_gg_service_tracking_type'], 'analytics4') ?>>
                                        <?php esc_html_e('Analytics 4 Property', 'wp-meta-seo') ?>
                                    </option>
                                    <option value="tagmanager"
                                        <?php
                                        selected($this->gaDisconnect['wpms_gg_service_tracking_type'], 'tagmanager') ?>>
                                        <?php esc_html_e('Tag Manager', 'wp-meta-seo') ?>
                                    </option>
                                </select>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td id="wpms-trackingId-title" class="wpms-settings-title">
                            <label
                                    for="wpms_gg_service_tracking_id"
                                    class="wpms-text"
                                    title="<?php esc_attr_e('Analytics UA-Tracking ID', 'wp-meta-seo') ?>">
                                <?php
                                $tracking_type = $this->gaDisconnect['wpms_gg_service_tracking_type'];
                                if ($tracking_type === 'analytics4') {
                                    esc_html_e('Measurement ID', 'wp-meta-seo');
                                } elseif ($tracking_type === 'tagmanager') {
                                    esc_html_e('Container ID', 'wp-meta-seo');
                                } else {
                                    esc_html_e('UA-Tracking ID', 'wp-meta-seo');
                                }

                                ?></label>
                        </td>
                        <td>
                            <input type="text" class="wpms-large-input" id="wpms_gg_service_tracking_id"
                                   name="_metaseo_gg_service_disconnect[wpms_gg_service_tracking_id]"
                                   value="<?php echo esc_attr($this->gaDisconnect['wpms_gg_service_tracking_id']) ?>"
                                   size="61">
                            <input type="hidden" name="wpms_nonce"
                                   value="<?php echo esc_attr(wp_create_nonce('wpms_nonce')) ?>">
                        </td>
                    </tr>
                </table>
                <p class="ju-description">
                    <?php esc_html_e('OR use directly any of the tracking JS code', 'wp-meta-seo') ?></p>
                <!--Google Analytics-->
                <div class="wpmsga-advanced-box" style="margin-bottom: 5px">
                    <h3 class="wpms-gg-services-directly-header down">
                        <?php esc_html_e('Google Analytics', 'wp-meta-seo') ?>
                    </h3>
                    <div class="content-box analytics-content-box hidden">
                        <p class="ju-description"><?php esc_html_e('Paste directly in the field the JS code given by Google. If you use the tracking ID above, this is not necessary', 'wp-meta-seo') ?></p>
                        <textarea name="_metaseo_gg_service_disconnect[wpmsga_code_tracking]"
                                  class="wpmsga_code_tracking wpms-gg-analytics-code">
                        <?php echo esc_textarea($this->gaDisconnect['wpmsga_code_tracking']); ?>
                    </textarea>
                        </label>
                    </div>
                </div>
                <!--End Google Analytics-->
                <!--Google Tag manager-->
                <div class="wpmsga-advanced-box">
                    <h3 class="wpms-gg-services-directly-header down">
                        <?php esc_html_e('Tag Manager', 'wp-meta-seo') ?>
                    </h3>
                    <div class="content-box tagmanager-content-box hidden">
                        <p class="ju-description">
                            <?php
                            echo sprintf(
                                esc_html__('Add the Tag manager code in the %s of your website', 'wp-meta-seo'),
                                '<strong>' . esc_html__('<head>', 'wp-meta-seo') . '</strong>'
                            );
                            ?>
                        </p>
                        <textarea style="height: 160px"
                                  name="_metaseo_gg_service_disconnect[wpmstm_header_code_tracking]"
                                  class="wpmsga_code_tracking wpms-gg-tagmanager-header-code">
                        <?php echo esc_textarea($this->gaDisconnect['wpmstm_header_code_tracking']); ?>
                    </textarea>
                        <p class="ju-description">
                            <?php
                            echo sprintf(
                                esc_html__('Add the Tag manager code in the %s of your website', 'wp-meta-seo'),
                                '<strong>' . esc_html__('<body>', 'wp-meta-seo') . '</strong>'
                            );
                            ?>
                        </p>
                        <textarea style="height: 100px" name="_metaseo_gg_service_disconnect[wpmstm_body_code_tracking]"
                                  class="wpmsga_code_tracking wpms-gg-tagmanager-body-code">
                        <?php echo esc_textarea($this->gaDisconnect['wpmstm_body_code_tracking']); ?>
                    </textarea>
                    </div>
                </div>
                <!--End Google Tag manager-->
            </div>
        </div>

        <p>
            <button type="submit" class="ju-button orange-button wpmsga_authorize gg_service_disconnect_setting"
                    name="gg_service_disconnect_setting"
            ><?php esc_html_e('Save Changes', 'wp-meta-seo'); ?></button>
        </p>
    </form>
    <script>
        // Remove the default empty spaces in field
        jQuery('.wpms-gg-analytics-code').val(jQuery('.wpms-gg-analytics-code').val().trim());
        jQuery('.wpms-gg-tagmanager-header-code').val(jQuery('.wpms-gg-tagmanager-header-code').val().trim());
        jQuery('.wpms-gg-tagmanager-body-code').val(jQuery('.wpms-gg-tagmanager-body-code').val().trim());

        // Slide toggle
        jQuery('body').on('click', '.wpms-gg-services-directly-header', function (e) {
            if (jQuery(this).hasClass('down')) {
                jQuery(this).removeClass('down').addClass('up');
            } else {
                jQuery(this).removeClass('up').addClass('down');
            }

            jQuery(this).closest('.wpmsga-advanced-box').find('.content-box').stop().slideToggle();
        });
    </script>
</div>