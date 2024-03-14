<div class="wrap">
    <?php
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/pages/google-services/menu.php');
    wp_enqueue_script('jquery');
    ?>
    <div class="wpms-gg-services-connect">
        <h2 class="wpms_uppercase"><?php esc_html_e('Google Analytics tracking & report', 'wp-meta-seo') ?></h2>
        <p class="ju-description"><?php esc_html_e('Enable Google Analytics tracking and reports using a Google Analytics direct connection. Require free Google Cloud credentials', 'wp-meta-seo') ?></p>
        <p class="wpms-ga-link-document">
            <a class="ju-link-classic" href="<?php echo esc_url('https://www.joomunited.com/wordpress-documentation/wp-meta-seo/343-wp-meta-seo-google-analytics-integration') ?>"
               target="_blank"><?php esc_html_e('DOCUMENTATION', 'wp-meta-seo') ?></a>
            <a class="ju-link-classic" href="<?php echo esc_url('https://console.cloud.google.com/apis/dashboard') ?>" style="margin-left: 15px"
               target="_blank"><?php esc_html_e('GET GOOGLE CREDENTIALS >>', 'wp-meta-seo') ?></a>
        </p>
        <?php
        $display_body_info = '';
        $display_body_access = 'style=display:none;';

        ?>
        <form name="input" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post" style="margin-top: 20px">
            <?php wp_nonce_field('gadash_form', 'gadash_security'); ?>
            <input type="hidden" name="wpms_nonce" value="<?php echo esc_attr(wp_create_nonce('wpms_nonce')) ?>">

            <div class="ju-settings-option wpms_ga_info background-none" <?php echo esc_attr($display_body_info) ?>>
                <div class="wpms_row_full">
                    <label class="ju-setting-label"><?php esc_html_e('Client ID', 'wp-meta-seo') ?></label>
                    <div class="ju-switch-button">
                        <input type="text" name="wpmsga_dash_clientid" class="wpms-large-input wpmsga_dash_input" size="60"
                               value="<?php echo esc_attr((!empty($this->google_alanytics['wpmsga_dash_clientid'])) ? $this->google_alanytics['wpmsga_dash_clientid'] : '') ?>">
                    </div>
                </div>
                <div class="wpms_row_full">
                    <label class="ju-setting-label"><?php esc_html_e('Client Secret', 'wp-meta-seo') ?></label>
                    <div class="ju-switch-button">
                        <input type="text" name="wpmsga_dash_clientsecret" class="wpms-large-input wpmsga_dash_input" size="60"
                               value="<?php echo esc_attr((!empty($this->google_alanytics['wpmsga_dash_clientsecret'])) ? $this->google_alanytics['wpmsga_dash_clientsecret'] : '') ?>">
                    </div>
                </div>
                <div class="wpms_row_full">
                    <label class="ju-setting-label" title="<?php esc_attr_e('JavaScript origins', 'wp-meta-seo') ?>">
                        <?php esc_html_e('JavaScript origins', 'wp-meta-seo'); ?>
                    </label>
                    <div class="ju-switch-button">
                        <input type="text" readonly class="wpms-large-input" id="wpms_ga_js_origins" name="wpms_ga_js_origins" value="<?php echo esc_attr(site_url()); ?>" size="60" />
                    </div>
                </div>
                <div class="wpms_row_full">
                    <label class="ju-setting-label" title="<?php esc_attr_e('Redirect URIs', 'wp-meta-seo') ?>">
                        <?php esc_html_e('Redirect URIs', 'wp-meta-seo'); ?>
                    </label>
                    <div class="ju-switch-button">
                        <input type="text" readonly class="wpms-large-input" id="wpms_ga_redirect_uris" name="wpms_ga_redirect_uris" value="<?php echo esc_attr(admin_url('admin.php?page=metaseo_google_analytics&view=wpms_gg_service_data&task=wpms_ga')) ?>" size="140" />
                    </div>
                </div>
                <div class="wpms_row_full save-ga-field">
                    <input type="button" class="ju-button save-ga-infomation orange-button"
                           value="<?php esc_html_e('Save and Connect', 'wp-meta-seo') ?>" />
                    <img class="save-ga-loader" src="<?php echo esc_url(WPMETASEO_PLUGIN_URL . '/assets/images/ajax-loader1.gif') ?>" width="50px"
                         style="display:none;margin-left:10px;vertical-align: middle" />
                </div>
            </div>

            <script>
                jQuery(document).ready(function ($) {
                    $('input.wpmsga_dash_input').on('change', function () {
                        if ($('input[name="wpmsga_dash_clientid"]').val() !== '' && $('input[name="wpmsga_dash_clientsecret"]').val()) {
                            $('.save-ga-infomation').show();
                        } else {
                            $('.save-ga-infomation').hide();
                        }
                    });

                    $('input.save-ga-infomation').click(function () {
                        var wpmsga_dash_clientid = $('input[name="wpmsga_dash_clientid"').val();
                        var wpmsga_dash_clientsecret = $('input[name="wpmsga_dash_clientsecret"').val();
                        $('.save-ga-field .save-ga-loader').show();
                        if (wpmsga_dash_clientid !== '' && wpmsga_dash_clientsecret !== '') {
                            $.ajax({
                                url: ajaxurl,
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    'action': 'wpms_gg_save_information',
                                    'wpmsga_dash_clientid': wpmsga_dash_clientid,
                                    'wpmsga_dash_clientsecret': wpmsga_dash_clientsecret,
                                    'wpms_nonce': $('input[name="wpms_nonce"]').val()
                                },
                                success: function (res) {
                                    if (res.status) {

                                        $('.save-ga-field .save-ga-loader').hide();
                                        window.location.href = res.authUrl;
                                    }
                                }
                            });
                        } else {
                            if (wpmsga_dash_clientid === '') {
                                $('input[name="wpmsga_dash_clientid"').focus();
                            }
                            if (wpmsga_dash_clientsecret === '') {
                                $('input[name="wpmsga_dash_clientsecret"').focus();
                            }
                        }
                    });

                });
            </script>

        </form>
    </div>
</div>