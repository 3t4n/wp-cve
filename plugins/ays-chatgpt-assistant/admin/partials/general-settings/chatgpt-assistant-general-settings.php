<?php
    require_once 'chatgpt-assistant-general-settings-action-options.php';
?>
<div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-empty-key-notice" <?php echo ($check_openai_connection_code) ? 'style="display:none"' : '' ?>>
    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-empty-key-notice-container">
        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-empty-key-notice-left">
            <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-empty-key-notice-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#F2AB26" width="25" height="25">
                    <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/>
                </svg>
            </div>
            <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-empty-key-notice-text">
                <p>
                    <?php echo __('Please enter your OpenAI API Key!', 'chatgpt-assistant'); ?>
                </p>
            </div>
        </div>
        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-empty-key-notice-right">
            <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-empty-key-notice-button">
                <a href="<?php echo admin_url('admin.php?page=' . $this->plugin_name . '&ays_tab=tab3'); ?>"><?php echo __('Go to Settings', 'chatgpt-assistant'); ?></a>
            </div>
        </div>
    </div>
</div>
<div class="wrap" style="position:relative;">
    <div class="container-fluid">
        <form method="post" id="ays-general-settings-form">
            <input type="hidden" name="ays_tab" value="<?php echo esc_attr($ays_tab); ?>">
            <h1 class="wp-heading-inline">
            <?php
                echo __('General Settings', "ays-chatgpt-assistant");
            ?>
            </h1>
            <hr/>
            <div class="ays-settings-wrapper">
                <div>
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("General", "ays-chatgpt-assistant");?>
                        </a>
                    </div>
                </div>
                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-tabs-wrapper">
                    <div id="tab1" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-tab-content ays-tab-content <?php echo ($ays_tab == 'tab1') ? 'ays-tab-content-active' : ''; ?>">
                        <fieldset>
                            <legend>
                                <h5><?php echo __('API Parameters',"ays-chatgpt-assistant")?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0px;">
                                <div class="col-sm-12 ays-pro-features-v2-main-box">
                                    <div class="ays-pro-features-v2-small-buttons-box">
                                        <div class="ays-pro-features-v2-video-button"></div>
                                        <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                            <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                            <div class="ays-pro-features-v2-upgrade-text">
                                                <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="form-group row" style="padding:5px;">
                                        <div class="col-sm-4">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-chat">
                                                <?php echo __( "Chat API", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Select for which API you want to add Chat.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-select-full-width" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[api_option_chat]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-chat">
                                                <option value=""><?php echo __('Select API type', 'chatgpt-assistant'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group row" style="padding:5px;">
                                        <div class="col-sm-4">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-embedding">
                                                <?php echo __( "Embedding API", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Select for which API you want to activate Embedding option.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-select-full-width" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[api_option_embedding]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-embedding">
                                                <option value=""><?php echo __('Select API type', 'chatgpt-assistant'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group row" style="padding:5px;">
                                        <div class="col-sm-4">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-image">
                                                <?php echo __( "Image API", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("This option is available for OpenAI only.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-select-full-width" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[api_option_image]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-image">
                                                <option value=""><?php echo __('Select API type', 'chatgpt-assistant'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group row" style="padding:5px;">
                                        <div class="col-sm-4">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-image">
                                                <?php echo __( "Speech to Text API", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("This option is available for OpenAI only.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-select-full-width" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[api_option_stt]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-api-option-stt">
                                                <option value=""><?php echo __('Select API type', 'chatgpt-assistant'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- API parameters -->
                        <hr />
                        <fieldset>
                            <legend>
                                <h5><?php echo __('Default parameters',"ays-chatgpt-assistant")?></h5>
                            </legend>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-textarea-height">
                                        <?php echo __( "Textarea height", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Set the height of the textareas by entering a numeric value. It applies to all textareas of the dashboard.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div>
                                        <input class="ays-text-input <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-textarea-height" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[textarea_height]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-textarea-height" value="<?php echo esc_attr($chatgpt_assistant_textarea_height); ?>">
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Default parameters -->
                        <hr>
                        <fieldset>
                            <legend>
                                <h5><?php echo __('Who will have permission to ChatGPT Assistant',"ays-chatgpt-assistant")?></h5>
                            </legend>
                            <div class="form-group row" style="margin:0px;">
                                <div class="col-sm-12 ays-pro-features-v2-main-box">
                                    <div class="ays-pro-features-v2-small-buttons-box">
                                        <div class="ays-pro-features-v2-video-button"></div>
                                        <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                            <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                            <div class="ays-pro-features-v2-upgrade-text">
                                                <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="form-group row" style="padding:5px;">
                                        <div class="col-sm-4">
                                            <label for="ays_user_roles">
                                                <?php echo __( "Select user role for giving access to plugin", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Give access to the ChatGPT Assistant plugin to only the selected user role(s) on your WP dashboard.", "ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select name="ays_user_roles[]" id="ays_user_roles" multiple>
                                                <option value="administrator" selected>Administrator</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <blockquote>
                                        <?php echo __( "Control the access of the plugin from the dashboard of those user roles.", "ays-chatgpt-assistant" ); ?>
                                        <br>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;z-index: 3;">
            <?php
                wp_nonce_field('general_settings_action', 'general_settings_action');
                $other_attributes = array();
                submit_button(__('Save changes', "ays-chatgpt-assistant"), 'primary '.esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX).'-loader-banner '.esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX).'-general-settings-save', 'ays_submit', true, $other_attributes);
                echo wp_kses_post($loader_iamge);
            ?>
            </div>
        </form>
    </div>
</div>
