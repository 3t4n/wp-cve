<div id="yop-main-area" class="bootstrap-yop wrap add-edit-poll">
    <h1>
        <?php esc_html_e( 'Poll Settings', 'yop-poll' ); ?>
    </h1>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content" style="position:relative">
                <form id="yop-poll-settings-form" action="">
                    <input type="hidden" id="_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-update-settings' ) ); ?>" name="_token">
                    <div class="meta-box-sortables ui-sortable">
                        <div id="titlediv">
                            <div class="inside"></div>
                        </div>
                        <div class="container-fluid yop-poll-hook">
                            <div class="tabs-container">
                                <!-- Nav tabs -->
                                <ul class="main-settings nav nav-tabs settings-steps" role="tablist">
                                    <li role="presentation" id="tab-notifications"  class="active">
                                        <a href="#settings-general" aria-controls="general" role="tab" data-toggle="tab">
                                            <?php esc_html_e( 'General', 'yop-poll' ); ?>
                                        </a>
                                    </li>
                                    <li role="presentation" id="tab-notifications"  class="">
                                        <a href="#settings-notifications" aria-controls="notifications" role="tab" data-toggle="tab">
                                            <?php esc_html_e( 'Notifications', 'yop-poll' ); ?>
                                        </a>
                                    </li>
                                    <li role="presentation" id="tab-integrations">
                                        <a href="#settings-integrations" aria-controls="integrations" role="tab" data-toggle="tab">
                                            <?php esc_html_e( 'Integrations', 'yop-poll' ); ?>
                                        </a>
                                    </li>
                                    <li role="presentation" id="tab-messages">
                                        <a href="#settings-messages" aria-controls="messages" role="tab" data-toggle="tab">
                                            <?php esc_html_e( 'Messages', 'yop-poll' ); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content settings-steps-content">
                                    <div role="tabpanel" class="tab-pane active" id="settings-general">
                                        <div class="row submenu" style="padding-top: 30px;">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <?php esc_html_e( 'Remove plugin data when uninstalling', 'yop-poll' ); ?>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <?php
                                                $remove_plugin_data_yes = '';
                                                $remove_plugin_data_no = '';
                                                if ( ( true === isset( $settings['general']['remove-data'] ) ) && ( 'yes' === $settings['general']['remove-data'] ) ) {
                                                    $remove_plugin_data_yes = 'selected';
                                                } else {
                                                    $remove_plugin_data_no = 'selected';
                                                }
                                                ?>
                                                <select name="general-remove-data" id="general-remove-data" class="general-remove-data admin-select" style="width:100%">
                                                    <option value="yes" <?php echo esc_attr( $remove_plugin_data_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                    <option value="no" <?php echo esc_attr( $remove_plugin_data_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                </select>
                                            </div>
                                        </div>
										<div class="row submenu" style="padding-top: 30px;">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                <?php esc_html_e( 'Use Custom Headers When Retrieving Ips', 'yop-poll' ); ?>
                                            </div>
                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                <?php
                                                $use_custom_headers_yes = '';
                                                $use_custom_headers_no = '';
                                                if ( ( true === isset( $settings['general']['use-custom-headers-for-ip'] ) ) && ( 'yes' === $settings['general']['use-custom-headers-for-ip'] ) ) {
                                                    $use_custom_headers_yes = 'selected';
                                                } else {
                                                    $use_custom_headers_no = 'selected';
                                                }
                                                ?>
                                                <select name="general-use-custom-headers-for-ip" id="general-use-custom-headers-for-ip" class="general-use-custom-headers-for-ip admin-select" style="width:100%">
                                                    <option value="yes" <?php echo esc_attr( $use_custom_headers_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                    <option value="no" <?php echo esc_attr( $use_custom_headers_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="settings-notifications">
                                        <div class="row submenu" style="padding-top: 20px;">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="email-from-name">
                                                        <?php esc_html_e( 'From Name', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="email-from-name" id="email-from-name" value="<?php echo isset( $settings['notifications']['new-vote']['from-name'] ) ? esc_attr( $settings['notifications']['new-vote']['from-name'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email-from-email">
                                                        <?php esc_html_e( 'From Email', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="email-from-email" id="email-from-email" value="<?php echo isset( $settings['notifications']['new-vote']['from-email'] ) ? esc_attr( $settings['notifications']['new-vote']['from-email'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email-recipients">
                                                        <?php esc_html_e( 'Recipients', 'yop-poll' ); ?>
                                                    </label>
                                                    <div><?php esc_html_e( 'Use comma separated email addresses: email@xmail.com,email2@ymail.com', 'yop-poll' ); ?></div>
                                                    <input class="form-control settings-required-field" name="email-recipients" id="email-recipients" value="<?php echo isset( $settings['notifications']['new-vote']['recipients'] ) ? esc_attr( $settings['notifications']['new-vote']['recipients'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email-subject">
                                                        <?php esc_html_e( 'Subject', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="email-subject" id="email-subject" value="<?php echo isset( $settings['notifications']['new-vote']['subject'] ) ? esc_attr( $settings['notifications']['new-vote']['subject'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email-message">
                                                        <?php esc_html_e( 'Body', 'yop-poll' ); ?>
                                                    </label>
                                                    <textarea class="form-control settings-required-field" name="email-message" id="email-message" rows="15"><?php echo isset( $settings['notifications']['new-vote']['message'] ) ? esc_textarea( $settings['notifications']['new-vote']['message'] ) : ''; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="settings-integrations">
                                        <br><br>
                                        <div class="row submenu">
                                            <div class="row submenu" style="padding-top: 20px;">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <?php esc_html_e( 'Use Google reCaptcha:', 'yop-poll' ); ?>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <?php
                                                    $reCaptcha_integration_yes = '';
                                                    $reCaptcha_integration_no = '';
                                                    $reCaptcha_data_section = '';
                                                    if ( ( true === isset( $settings['integrations']['reCaptcha']['enabled'] ) ) && ( 'yes' === $settings['integrations']['reCaptcha']['enabled'] ) ) {
                                                        $reCaptcha_integration_yes = 'selected';
                                                    } else {
                                                        $reCaptcha_integration_no = 'selected';
                                                        $reCaptcha_data_section = 'hide';
                                                    }
                                                    ?>
                                                    <select name="integrations-reCaptcha-enabled" id="integrations-reCaptcha-enabled" class="integrations-reCaptcha-enabled admin-select" style="width:100%">
                                                        <option value="yes" <?php echo esc_attr( $reCaptcha_integration_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                        <option value="no" <?php echo esc_attr( $reCaptcha_integration_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row submenu integrations-reCaptcha-section <?php echo esc_attr( $reCaptcha_data_section ); ?>" style="padding-top: 20px; margin-left: 20px;">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Site Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-reCaptcha-site-key" id ="integrations-reCaptcha-site-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['reCaptcha']['site-key'] ) ? esc_attr( $settings['integrations']['reCaptcha']['site-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding-top: 10px;">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Secret Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-reCaptcha-secret-key" id ="integrations-reCaptcha-secret-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['reCaptcha']['secret-key'] ) ? esc_attr( $settings['integrations']['reCaptcha']['secret-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row submenu" style="padding-top: 20px;">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <?php esc_html_e( 'Use Invisible reCaptcha v2:', 'yop-poll' ); ?>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <?php
                                                    $reCaptcha_v2_invisible_integration_yes = '';
                                                    $reCaptcha_v2_invisible_integration_no = '';
                                                    $reCaptcha_v2_invisible_data_section = '';
                                                    if ( ( true === isset( $settings['integrations']['reCaptchaV2Invisible']['enabled'] ) ) && ( 'yes' === $settings['integrations']['reCaptchaV2Invisible']['enabled'] ) ) {
                                                        $reCaptcha_v2_invisible_integration_yes = 'selected';
                                                    } else {
                                                        $reCaptcha_v2_invisible_integration_no = 'selected';
                                                        $reCaptcha_v2_invisible_data_section = 'hide';
                                                    }
                                                    ?>
                                                    <select name="integrations-reCaptchaV2Invisible-enabled" id="integrations-reCaptchaV2Invisible-enabled" class="integrations-reCaptchaV2Invisible-enabled admin-select" style="width:100%">
                                                        <option value="yes" <?php echo esc_attr( $reCaptcha_v2_invisible_integration_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                        <option value="no" <?php echo esc_attr( $reCaptcha_v2_invisible_integration_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row submenu integrations-reCaptchaV2Invisible-section <?php echo esc_attr( $reCaptcha_v2_invisible_data_section ); ?>" style="padding-top: 20px; margin-left: 20px;">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Site Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-reCaptchaV2Invisible-site-key" id ="integrations-reCaptchaV2Invisible-site-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['reCaptchaV2Invisible']['site-key'] ) ? esc_attr( $settings['integrations']['reCaptchaV2Invisible']['site-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding-top: 10px;">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Secret Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-reCaptchaV2Invisible-secret-key" id ="integrations-reCaptchaV2Invisible-secret-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['reCaptchaV2Invisible']['secret-key'] ) ? esc_attr( $settings['integrations']['reCaptchaV2Invisible']['secret-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row submenu" style="padding-top: 20px;">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <?php esc_html_e( 'Use Google reCaptcha v3:', 'yop-poll' ); ?>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <?php
                                                    $reCaptcha_v3_integration_yes = '';
                                                    $reCaptcha_v3_integration_no = '';
                                                    $reCaptcha_v3_data_section = '';
                                                    if ( ( true === isset( $settings['integrations']['reCaptchaV3']['enabled'] ) ) && ( 'yes' === $settings['integrations']['reCaptchaV3']['enabled'] ) ) {
                                                        $reCaptcha_v3_integration_yes = 'selected';
                                                    } else {
                                                        $reCaptcha_v3_integration_no = 'selected';
                                                        $reCaptcha_v3_data_section = 'hide';
                                                    }
                                                    ?>
                                                    <select name="integrations-reCaptchaV3-enabled" id="integrations-reCaptchaV3-enabled" class="integrations-reCaptchaV3-enabled admin-select" style="width:100%">
                                                        <option value="yes" <?php echo esc_attr( $reCaptcha_v3_integration_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                        <option value="no" <?php echo esc_attr( $reCaptcha_v3_integration_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row submenu integrations-reCaptchaV3-section <?php echo esc_attr( $reCaptcha_v3_data_section ); ?>" style="padding-top: 20px; margin-left: 20px;">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Site Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-reCaptchaV3-site-key" id ="integrations-reCaptchaV3-site-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['reCaptchaV3']['site-key'] ) ? esc_attr( $settings['integrations']['reCaptchaV3']['site-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding-top: 10px;">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Secret Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-reCaptchaV3-secret-key" id ="integrations-reCaptchaV3-secret-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['reCaptchaV3']['secret-key'] ) ? esc_attr( $settings['integrations']['reCaptchaV3']['secret-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding-top: 10px;">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Min Allowed Score:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-reCaptchaV3-min-allowed-score" id ="integrations-reCaptchaV3-min-allowed-score" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['reCaptchaV3']['min-allowed-score'] ) ? esc_attr( $settings['integrations']['reCaptchaV3']['min-allowed-score'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row submenu" style="padding-top: 20px;">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <?php esc_html_e( 'Use hCaptcha:', 'yop-poll' ); ?>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <?php
                                                    $h_captcha_integration_yes = '';
                                                    $h_captcha_integration_no = '';
                                                    $h_captcha_data_section = '';
                                                    if ( ( true === isset( $settings['integrations']['hCaptcha']['enabled'] ) ) && ( 'yes' === $settings['integrations']['hCaptcha']['enabled'] ) ) {
                                                        $h_captcha_integration_yes = 'selected';
                                                    } else {
                                                        $h_captcha_integration_no = 'selected';
                                                        $h_captcha_data_section = 'hide';
                                                    }
                                                    ?>
                                                    <select name="integrations-hCaptcha-enabled" id="integrations-hCaptcha-enabled" class="integrations-hCaptcha-enabled admin-select" style="width:100%">
                                                        <option value="yes" <?php echo esc_attr( $h_captcha_integration_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                        <option value="no" <?php echo esc_attr( $h_captcha_integration_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row submenu integrations-hCaptcha-section <?php echo esc_attr( $h_captcha_data_section ); ?>" style="padding-top: 20px; margin-left: 20px;">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Site Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-hCaptcha-site-key" id ="integrations-hCaptcha-site-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['hCaptcha']['site-key'] ) ? esc_attr( $settings['integrations']['hCaptcha']['site-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row" style="padding-top: 10px;">
                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                            <?php esc_html_e( '- Secret Key:', 'yop-poll' ); ?>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                            <input name="integrations-hCaptcha-secret-key" id ="integrations-hCaptcha-secret-key" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['hCaptcha']['secret-key'] ) ? esc_attr( $settings['integrations']['hCaptcha']['secret-key'] ) : ''; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row submenu" style="padding-top: 20px;">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <a href="#" class="upgrade-to-pro" data-screen="media-integration">
                                                        <img src="<?php echo esc_url( YOP_POLL_URL ); ?>admin/assets/images/pro-horizontal.svg" class="responsive" />
                                                    </a>
                                                    <?php esc_html_e( 'Use Facebook integration:', 'yop-poll' ); ?>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <?php
                                                    $facebook_integration_yes = '';
                                                    $facebook_integration_no = '';
                                                    $facebook_data_section = '';
                                                    if ( ( true === isset( $settings['integrations']['facebook']['enabled'] ) ) && ( 'yes' === $settings['integrations']['facebook']['enabled'] ) ) {
                                                        $facebook_integration_yes = 'selected';
                                                    } else {
                                                        $facebook_integration_no = 'selected';
                                                        $facebook_data_section = 'hide';
                                                    }
                                                    ?>
                                                    <select name="integrations-facebook-enabled" id="integrations-facebook-enabled" class="integrations-facebook-enabled admin-select" style="width:100%">
                                                        <option value="yes" <?php echo esc_attr( $facebook_integration_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                        <option value="no" <?php echo esc_attr( $facebook_integration_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row submenu integrations-facebook-section <?php echo esc_attr( $facebook_data_section ); ?>" style="padding-top: 20px; margin-left: 20px;">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                    <?php esc_html_e( '- App ID:', 'yop-poll' ); ?>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <input name="integrations-facebook-app-id" id ="integrations-facebook-app-id" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['facebook']['app-id'] ) ? esc_attr( $settings['integrations']['facebook']['app-id'] ) : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="row submenu" style="padding-top: 20px;">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                    <a href="#" class="upgrade-to-pro" data-screen="media-integration">
                                                        <img src="<?php echo esc_url( YOP_POLL_URL ); ?>admin/assets/images/pro-horizontal.svg" class="responsive" />
                                                    </a>
                                                    <?php esc_html_e( 'Use Google integration:', 'yop-poll' ); ?>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                    <?php
                                                    $google_integration_yes = '';
                                                    $google_integration_no = '';
                                                    $google_data_section = '';
                                                    if ( ( true === isset( $settings['integrations']['google']['enabled'] ) ) && ( 'yes' === $settings['integrations']['google']['enabled'] ) ) {
                                                        $google_integration_yes = 'selected';
                                                    } else {
                                                        $google_integration_no = 'selected';
                                                        $google_data_section = 'hide';
                                                    }
                                                    ?>
                                                    <select name="integrations-google-enabled" id="integrations-google-enabled" class="integrations-google-enabled admin-select" style="width:100%">
                                                        <option value="yes" <?php echo esc_attr( $google_integration_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
                                                        <option value="no" <?php echo esc_attr( $google_integration_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row submenu integrations-google-section <?php echo esc_attr( $google_data_section ); ?>" style="padding-top: 20px; margin-left: 20px;">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                        <?php esc_html_e( '- App ID:', 'yop-poll' ); ?>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                        <input name="integrations-google-app-id" id ="integrations-google-app-id" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['google']['app-id'] ) ? esc_attr( $settings['integrations']['google']['app-id'] ) : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="row" style="padding-top: 10px;">
                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 input-caption">
                                                        <?php esc_html_e( '- App Secret:', 'yop-poll' ); ?>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                        <input name="integrations-google-app-secret" id ="integrations-google-app-secret" class="form-control settings-required-field" value="<?php echo isset( $settings['integrations']['google']['app-secret'] ) ? esc_attr( $settings['integrations']['google']['app-secret'] ) : ''; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="settings-messages">
                                        <br><br>
                                        <div class="row submenu">
                                            <div class="col-md-3">
                                                <a class="btn btn-link btn-block submenu-item submenu-item-active btn-underline" data-content="settings-messages-buttons">
                                                    <?php esc_html_e( 'Vote Buttons', 'yop-poll' ); ?>
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn btn-link btn-block submenu-item" data-content="settings-messages-voting">
                                                    <?php esc_html_e( 'Voting', 'yop-poll' ); ?>
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn btn-link btn-block submenu-item" data-content="settings-messages-results">
                                                    <?php esc_html_e( 'Results', 'yop-poll' ); ?>
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <a class="btn btn-link btn-block submenu-item" data-content="settings-messages-captcha">
                                                    <?php esc_html_e( 'Captcha', 'yop-poll' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row submenu-content settings-messages-buttons">
                                            <div class="col-md-12">
                                                <div><br /><br /></div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-buttons-anonymous" class="input-caption">
                                                        <?php esc_html_e( 'Vote as anonymous', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-buttons-anonymous" id="messages-buttons-anonymous"
                                                           value="<?php echo isset( $settings['messages']['buttons']['anonymous'] ) ? esc_attr( $settings['messages']['buttons']['anonymous'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-buttons-wordpress" class="input-caption">
                                                        <?php esc_html_e( 'Vote with your WordPress account', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-buttons-wordpress" id="messages-buttons-wordpress"
                                                           value="<?php echo isset( $settings['messages']['buttons']['wordpress'] ) ? esc_attr( $settings['messages']['buttons']['wordpress'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-buttons-facebook" class="input-caption">
                                                        <?php esc_html_e( 'Vote with your facebook account', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-buttons-facebook" id="messages-buttons-facebook"
                                                           value="<?php echo isset( $settings['messages']['buttons']['facebook'] ) ? esc_attr( $settings['messages']['buttons']['facebook'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-buttons-google" class="input-caption">
                                                        <?php esc_html_e( 'Vote with your google account', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-buttons-google" id="messages-buttons-google"
                                                           value="<?php echo isset( $settings['messages']['buttons']['google'] ) ? esc_attr( $settings['messages']['buttons']['google'] ) : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row submenu-content settings-messages-voting hide">
                                            <div class="col-md-12">
                                                <div><br /><br /></div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-poll-ended" class="input-caption">
                                                        <?php esc_html_e( 'Poll Ended', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-poll-ended" id="messages-voting-poll-ended"
                                                           value="<?php echo isset( $settings['messages']['voting']['poll-ended'] ) ? esc_attr( $settings['messages']['voting']['poll-ended'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-poll-not-started" class="input-caption">
                                                        <?php esc_html_e( 'Poll Not Started', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-poll-not-started" id="messages-voting-poll-not-started"
                                                           value="<?php echo isset( $settings['messages']['voting']['poll-not-started'] ) ? esc_attr( $settings['messages']['voting']['poll-not-started'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-already-voted-on-poll" class="input-caption">
                                                        <?php esc_html_e( 'Already voted on poll', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-already-voted-on-poll" id="messages-voting-already-voted-on-poll"
                                                           value="<?php echo isset( $settings['messages']['voting']['already-voted-on-poll'] ) ? esc_attr( $settings['messages']['voting']['already-voted-on-poll'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-invalid-poll" class="input-caption">
                                                        <?php esc_html_e( 'Invalid Poll', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-invalid-poll" id="messages-voting-invalid-poll"
                                                           value="<?php echo isset( $settings['messages']['voting']['invalid-poll'] ) ? esc_attr( $settings['messages']['voting']['invalid-poll'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-no-answer-selected" class="input-caption">
                                                        <?php esc_html_e( 'No Answer(s) selected', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-no-answer-selected" id="messages-voting-no-answer-selected"
                                                           value="<?php echo isset( $settings['messages']['voting']['no-answers-selected'] ) ? esc_attr( $settings['messages']['voting']['no-answers-selected'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-min-answers-required" class="input-caption">
                                                        <?php esc_html_e( 'Minimum answers required', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-min-answers-required" id="messages-voting-min-answers-required"
                                                           value="<?php echo isset( $settings['messages']['voting']['min-answers-required'] ) ? esc_attr( $settings['messages']['voting']['min-answers-required'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-max-answers-required" class="input-caption">
                                                        <?php esc_html_e( 'Maximum answers required', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-max-answers-required" id="messages-voting-max-answers-required"
                                                           value="<?php echo isset( $settings['messages']['voting']['max-answers-required'] ) ? esc_attr( $settings['messages']['voting']['max-answers-required'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-no-value-for-other" class="input-caption">
                                                        <?php esc_html_e( 'No value for other', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-no-value-for-other" id="messages-voting-no-value-for-other"
                                                           value="<?php echo isset( $settings['messages']['voting']['no-answer-for-other'] ) ? esc_attr( $settings['messages']['voting']['no-answer-for-other'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-no-value-for-custom-field" class="input-caption">
                                                        <?php esc_html_e( 'No value for custom field', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-no-value-for-custom-field" id="messages-voting-no-value-for-custom-field"
                                                           value="<?php echo isset( $settings['messages']['voting']['no-value-for-custom-field'] ) ? esc_attr( $settings['messages']['voting']['no-value-for-custom-field'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-consent-not-checked" class="input-caption">
                                                        <?php esc_html_e( 'Consent not checked', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-consent-not-checked" id="messages-voting-consent-not-checked"
                                                           value="<?php echo isset( $settings['messages']['voting']['consent-not-checked'] ) ? esc_attr( $settings['messages']['voting']['consent-not-checked'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-captcha-not-checked" class="input-caption">
                                                        <?php esc_html_e( 'Captcha missing', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-captcha-not-checked" id="messages-voting-captcha-not-checked"
                                                           value="<?php echo isset( $settings['messages']['voting']['no-captcha-selected'] ) ? esc_attr( $settings['messages']['voting']['no-captcha-selected'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-vote-not-allowed-by-ban" class="input-caption">
                                                        <?php esc_html_e( 'Vote not allowed by ban setting', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-vote-not-allowed-by-ban" id="messages-voting-vote-not-allowed-by-ban"
                                                           value="<?php echo isset( $settings['messages']['voting']['not-allowed-by-ban'] ) ? esc_attr( $settings['messages']['voting']['not-allowed-by-ban'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-vote-not-allowed-by-block" class="input-caption">
                                                        <?php esc_html_e( 'Vote not allowed by block setting', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-vote-not-allowed-by-block" id="messages-voting-vote-not-allowed-by-block"
                                                           value="<?php echo isset( $settings['messages']['voting']['not-allowed-by-block'] ) ? esc_attr( $settings['messages']['voting']['not-allowed-by-block'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-vote-not-allowed-by-limit" class="input-caption">
                                                        <?php esc_html_e( 'Vote not allowed by limit setting', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-vote-not-allowed-by-limit" id="messages-voting-vote-not-allowed-by-limit"
                                                           value="<?php echo isset( $settings['messages']['voting']['not-allowed-by-limit'] ) ? esc_attr( $settings['messages']['voting']['not-allowed-by-limit'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-voting-thank-you" class="input-caption">
                                                        <?php esc_html_e( 'Thank you for your vote', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-voting-thank-you" id="messages-voting-thank-you"
                                                           value="<?php echo isset( $settings['messages']['voting']['thank-you'] ) ? esc_attr( $settings['messages']['voting']['thank-you'] ) : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row submenu-content settings-messages-results hide">
                                            <div class="col-md-12">
                                                <div><br /><br /></div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-results-single-vote" class="input-caption">
                                                        <?php esc_html_e( 'Single Vote', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-results-single-vote" id="messages-results-single-vote"
                                                           value="<?php echo isset( $settings['messages']['results']['single-vote'] ) ? esc_attr( $settings['messages']['results']['single-vote'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-results-multiple-votes" class="input-caption">
                                                        <?php esc_html_e( 'Multiple Votes', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-results-multiple-votes" id="messages-results-multiple-votes"
                                                           value="<?php echo isset( $settings['messages']['results']['multiple-votes'] ) ? esc_attr( $settings['messages']['results']['multiple-votes'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-results-single-answer" class="input-caption">
                                                        <?php esc_html_e( 'Single Answer', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-results-single-answer" id="messages-results-single-answer"
                                                           value="<?php echo isset( $settings['messages']['results']['single-answer'] ) ? esc_attr( $settings['messages']['results']['single-answer'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-results-multiple-answers" class="input-caption">
                                                        <?php esc_html_e( 'Multiple Answers', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-results-multiple-answers" id="messages-results-multiple-answers"
                                                           value="<?php echo isset( $settings['messages']['results']['multiple-answers'] ) ? esc_attr( $settings['messages']['results']['multiple-answers'] ) : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row submenu-content settings-messages-captcha hide">
                                            <div class="col-md-12">
                                                <div><br /><br /></div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-captcha-accessibility-alt" class="input-caption">
                                                        <?php esc_html_e( 'Accessibility Alt', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-captcha-accessibility-alt" id="messages-captcha-accessibility-alt"
                                                           value="<?php echo isset( $settings['messages']['captcha']['accessibility-alt'] ) ? esc_attr( $settings['messages']['captcha']['accessibility-alt'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-captcha-accessibility-title" class="input-caption">
                                                        <?php esc_html_e( 'Accessibility Title', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-captcha-accessibility-title" id="messages-captcha-accessibility-title"
                                                           value="<?php echo isset( $settings['messages']['captcha']['accessibility-title'] ) ? esc_attr( $settings['messages']['captcha']['accessibility-title'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-captcha-accessibility-description" class="input-caption">
                                                        <?php esc_html_e( 'Accessibility Description', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-captcha-accessibility-description" id="messages-captcha-accessibility-description"
                                                           value="<?php echo isset( $settings['messages']['captcha']['accessibility-description'] ) ? esc_attr( $settings['messages']['captcha']['accessibility-description'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-captcha-accessibility-explanation" class="input-caption">
                                                        <?php esc_html_e( 'Accessibility Explanation', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-captcha-accessibility-explanation" id="messages-captcha-accessibility-explanation"
                                                           value="<?php echo isset( $settings['messages']['captcha']['explanation'] ) ? esc_attr( $settings['messages']['captcha']['explanation'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-captcha-refresh-alt" class="input-caption">
                                                        <?php esc_html_e( 'Refresh Alt', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-captcha-refresh-alt" id="messages-captcha-refresh-alt"
                                                           value="<?php echo isset( $settings['messages']['captcha']['refresh-alt'] ) ? esc_attr( $settings['messages']['captcha']['refresh-alt'] ) : ''; ?>">
                                                </div>
                                                <div class="form-group messages-fields">
                                                    <label for="messages-captcha-refresh-title" class="input-caption">
                                                        <?php esc_html_e( 'Refresh Title', 'yop-poll' ); ?>
                                                    </label>
                                                    <input class="form-control settings-required-field" name="messages-captcha-refresh-title" id="messages-captcha-refresh-title"
                                                           value="<?php echo isset( $settings['messages']['captcha']['refresh-title'] ) ? esc_attr( $settings['messages']['captcha']['refresh-title'] ) : ''; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /.container -->
                    </div>
                </form>
            </div>
            <div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <div class="postbox" id="submitdiv">
                        <button type="button" class="handlediv button-link" aria-expanded="true">
                            <span class="screen-reader-text">
                                <?php esc_html_e( 'Toggle panel: Publish', 'yop-poll' ); ?>
                            </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span>
                                <?php esc_html_e( 'Publish', 'yop-poll' ); ?>
                            </span>
                        </h2>
                        <div class="inside">
                            <div id="submitpoll" class="submitbox">
                                <div id="minor-publishing">
                                    <div class="clear"></div>
                                    <div id="major-publishing-actions">
                                        <div id="publishing-action">
                                            <span class="spinner publish"></span>
                                            <button name="save_settings" class="button button-primary button-large save-settings" type="button">
                                                <?php esc_html_e( 'Save settings', 'yop-poll' ); ?>
                                            </button>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $upgrade_page = rand( 1, 2 );
            //$upgrade_page = 1;
            if ( 1 === $upgrade_page ) {
                include YOP_POLL_PATH . 'admin/views/general/upgrade-short-1.php';
            } else {
                include YOP_POLL_PATH . 'admin/views/general/upgrade-short-2.php';
            }
            ?>
        </div>
    </div>
</div>
<!-- begin live preview -->
<div class="bootstrap-yop">
    <div id="yop-poll-preview" class="hide">
    </div>
</div>
<!-- end live preview -->
