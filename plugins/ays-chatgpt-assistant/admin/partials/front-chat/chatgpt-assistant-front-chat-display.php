<?php
    require_once 'chatgpt-assistant-front-chat-actions-options.php';
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
<div class="wrap">
    <div class="container-fluid">
        <form method="post" id="ays-chat-form">
            <h1 class="wp-heading-inline">
            <?php
                echo __('Settings', "ays-chatgpt-assistant");
            ?>
            </h1>
            <hr/>
            <div class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-settings-wrapper">
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-4">
                        <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-chatbox-onoff">
                            <?php echo __( "Show chat window on front end", $this->plugin_name ); ?>
                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("After enabling this option, the chat will be shown in the front.",$this->plugin_name)?>">
                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_divider_left">
                        <label class="ays-chatgpt-assistant-toggle-switch-switch">
                            <input class="ays-chatgpt-assistant-toggle-switch" type="checkbox" name="ays_chatgpt_assistant_chatbox_onoff" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-chatbox-onoff" value="on" <?php echo $chatbox_onoff; ?> >
                            <span class="ays-chatgpt-assistant-toggle-switch-slider ays-chatgpt-assistant-toggle-switch-round"></span>
                        </label>
                    </div>
                </div>
                <hr>
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-4">
                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-request-limitations">
                            <?php echo __( "Limitations", "ays-chatgpt-assistant" ); ?>
                            <a class="ays_help" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo __("Set limitation for the users who want to use the chatbot from front end.","ays-chatgpt-assistant")?>">
                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_toggle_parent ays_divider_left">
                        <div>
                            <label class="ays-chatgpt-assistant-toggle-switch-switch">
                                <input type="checkbox" class="ays-chatgpt-assistant-toggle-switch <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-request-limitations ays_toggle_checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[enable_request_limitations]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-request-limitations" value="on" <?php echo ($chatgpt_assistant_enable_request_limitations) ? 'checked' : '';  ?>>
                                <span class="ays-chatgpt-assistant-toggle-switch-slider ays-chatgpt-assistant-toggle-switch-round"></span>
                            </label>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_request_limitations) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_request_limitations) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-request-limitations-limit">
                                    <?php echo __( "Request Limits", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Set how many times the users will be able to use the chatbot from the front end. You will be able to request limitation per hour, day, week and month.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <input type="number" class="ays-text-input ays-text-input-short" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[request_limitations_limit]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-request-limitations-limit" value="<?php echo esc_attr($chatgpt_assistant_request_limitations_limit);?>" style="margin-top:0">
                                <span style="line-height:40px;color:#4f4f4f;padding:0 10px"><?php echo __('per', 'chatgpt-assistant') ?></span>
                                <select name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[request_limitations_interval]" class="form-select <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX);?>-request-limitations-interval ays-text-input ays-text-input-short ays-input-height">
                                    <?php
                                        foreach($request_limitation_intervals as $key => $val){
                                            $selected = ($key == $chatgpt_assistant_request_limitations_interval) ? 'selected' : '';
                                            echo "<option ".esc_attr($selected)." value='".esc_attr($key)."'>".esc_attr($val)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-4">
                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-icon-size-front">
                            <?php echo __( "Chat icon size", "ays-chatgpt-assistant" ); ?>
                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the size of the chat icon only for the front-end chatbot (width and height are equal).","ays-chatgpt-assistant")?>">
                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_divider_left">
                        <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-icon-size-front" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[chat_icon_size_front]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-icon-size-front" value="<?php echo esc_attr($chatgpt_assistant_chat_icon_size_front); ?>">
                        <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                    </div>
                </div>
                <hr/>
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-4">
                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-enable-icon-text">
                            <?php echo __( "Icon text", "ays-chatgpt-assistant" ); ?>
                            <a class="ays_help" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo __("Enable this option to display a small text next to the chatbot icon for quick identification.","ays-chatgpt-assistant")?>">
                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_toggle_parent ays_divider_left">
                        <div>
                            <label class="ays-chatgpt-assistant-toggle-switch-switch">
                                <input type="checkbox" class="ays-chatgpt-assistant-toggle-switch <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-enable-icon-text ays_toggle_checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[enable_icon_text]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-enable-icon-text" value="on" <?php echo ($chatgpt_assistant_enable_icon_text) ? 'checked' : '';  ?>>
                                <span class="ays-chatgpt-assistant-toggle-switch-slider ays-chatgpt-assistant-toggle-switch-round"></span>
                            </label>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text">
                                    <?php echo __( "Text", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enter the text you want to appear next to the chatbot icon.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[icon_text]" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-icon-text" style="height:<?php echo $textarea_height?>px"><?php echo esc_attr($chatgpt_assistant_icon_text); ?></textarea>
                            </div>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-bg">
                                    <?php echo __( "Background color", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("The background color of the chat icon.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[icon_bg]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-bg" value="<?php echo $chatgpt_assistant_icon_bg;?>">
                            </div>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-color">
                                    <?php echo __( "Text color", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("The text color of the chat icon.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[icon_text_color]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-color" value="<?php echo $chatgpt_assistant_icon_text_color;?>">
                            </div>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-font-size">
                                    <?php echo __( "Text font size", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("The text font size of the chat icon text.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-icon-text-font-size" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[icon_text_font_size]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-font-size" value="<?php echo esc_attr($chatgpt_assistant_icon_text_font_size); ?>">
                                <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                            </div>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-border-width">
                                    <?php echo __( "Border width", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("The border width of the chat icon text container.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-icon-text-border-width" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[icon_text_border_width]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-border-width" value="<?php echo esc_attr($chatgpt_assistant_icon_text_border_width); ?>">
                                <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                            </div>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-border-color">
                                    <?php echo __( "Border color", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("The border color of the chat icon container.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6 d-flex">
                                <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[icon_text_border_color]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-border-color" value="<?php echo $chatgpt_assistant_icon_text_border_color;?>">
                            </div>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-open-delay">
                                    <?php echo __( "Open Delay", "ays-chatgpt-assistant" ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Set the time when the chat icon text will be displayed.","ays-chatgpt-assistant")?>">
                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-icon-text-open-delay" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_settings[icon_text_open_delay]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-icon-text-open-delay" value="<?php echo esc_attr($chatgpt_assistant_icon_text_open_delay); ?>">
                                <input type="text" value="ms" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                            </div>
                        </div>
                        <hr class="ays_toggle_target" style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                        <div class="form-group row ays_toggle_target " style="<?php echo ($chatgpt_assistant_enable_icon_text) ? '' : 'display:none;';  ?>">
                            <div class="col-sm-3">
                                <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-icon-text-show-once">
                                    <?php echo __( "Show Once", $this->plugin_name ); ?>
                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to display the icon text only once.",$this->plugin_name)?>">
                                        <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-switch">
                                    <input class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch" type="checkbox" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_settings[icon_text_show_once]" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-icon-text-show-once" value="on" <?php echo ($chatgpt_assistant_icon_text_show_once) ? 'checked' : '';  ?> >
                                    <span class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-slider <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-4">
                        <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-access-for-guests">
                            <?php echo __( "Allow access for guests", $this->plugin_name ); ?>
                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to restrict chatbot access for users who are not logged in.",$this->plugin_name)?>">
                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_divider_left">
                        <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-switch">
                            <input class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch" type="checkbox" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_settings[access_for_guests]" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-access-for-guests" value="on" <?php echo $chatgpt_assistant_access_for_guests; ?> >
                            <span class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-slider <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-round"></span>
                        </label>
                    </div>
                </div>
                <hr/>
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-4">
                        <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-access-for-logged-in">
                            <?php echo __( "Allow access for logged in users", $this->plugin_name ); ?>
                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to restrict chatbot access for logged in users.",$this->plugin_name)?>">
                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_divider_left">
                        <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-switch">
                            <input class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch" type="checkbox" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_settings[access_for_logged_in]" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-access-for-logged-in" value="on" <?php echo $chatgpt_assistant_access_for_logged_in; ?> >
                            <span class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-slider <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-round"></span>
                        </label>
                    </div>
                </div>
                <hr>
                <!-- Password protection start -->
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-4">
                        <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-password-protection">
                            <?php echo __( "Password protection", $this->plugin_name ); ?>
                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __(" If this option is enabled and there is a password set for the WordPress post, the chatbot will not be displayed on the front-end until the user fills in the password.",$this->plugin_name)?>">
                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-8 ays_divider_left">
                        <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-switch">
                            <input class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch" type="checkbox" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_settings[password_protection]" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-password-protection" value="on" <?php echo $chatgpt_assistant_password_protection; ?> >
                            <span class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-slider <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-round"></span>
                        </label>
                    </div>
                </div>
                <hr>
                <!-- Change chat icon start -->
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-12 ays-pro-features-v2-main-box" style="padding:10px;">
                        <div class="ays-pro-features-v2-small-buttons-box">
                            <div class="ays-pro-features-v2-video-button"></div>
                            <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                <div class="ays-pro-features-v2-upgrade-text">
                                    <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                </div>
                            </a>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-chat-icon">
                                    <?php echo __( "Change chat icon",  "ays-chatgpt-assistant" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8 <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-image-container ays_divider_left">
                                <button id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-chat-icon" class="button <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-add-chat-icon" type="button"><?php echo __("Add image" , "ays-chatgpt-assistant"); ?></button>                                
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Change chat icon start -->

                <!-- Change chat avatar start -->
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-12 ays-pro-features-v2-main-box" style="padding:10px;">
                        <div class="ays-pro-features-v2-small-buttons-box">
                            <div class="ays-pro-features-v2-video-button"></div>
                            <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                <div class="ays-pro-features-v2-upgrade-text">
                                    <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                </div>
                            </a>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-chat-avatar">
                                    <?php echo __( "Change chat avatar", $this->plugin_name ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8 <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-image-container ays_divider_left">
                                <div class="ays-chatgpt-assistant-avatars-main-div-wrap">
                                    <div class="ays-chatgpt-assistant-avatars-main-div">
                                        <input type="radio" class="ays-chatgpt-assistant-avatars-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-1" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_avatar" value="avatar-1">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-1" class="ays-chatgpt-assistant-avatar-item">
                                            <!-- <span><?php // echo __('Default', $this->plugin_name); ?></span> -->
                                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/avatar-1.jpg' ?>">
                                        </label>
                                    </div>
                                    <div class="ays-chatgpt-assistant-avatars-main-div">
                                        <input type="radio" class="ays-chatgpt-assistant-avatars-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-2" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_avatar" value="avatar-2">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-2" class="ays-chatgpt-assistant-avatar-item">
                                            <!-- <span><?php // echo __('Default', $this->plugin_name); ?></span> -->
                                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/avatar-2.jpg' ?>">
                                        </label>
                                    </div>
                                    <div class="ays-chatgpt-assistant-avatars-main-div">
                                        <input type="radio" class="ays-chatgpt-assistant-avatars-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-3" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_avatar" value="avatar-3">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-3" class="ays-chatgpt-assistant-avatar-item">
                                            <!-- <span><?php // echo __('Default', $this->plugin_name); ?></span> -->
                                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/avatar-3.jpg' ?>">
                                        </label>
                                    </div>
                                    <div class="ays-chatgpt-assistant-avatars-main-div">
                                        <input type="radio" class="ays-chatgpt-assistant-avatars-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-4" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_avatar" value="avatar-4">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-4" class="ays-chatgpt-assistant-avatar-item">
                                            <!-- <span><?php // echo __('Default', $this->plugin_name); ?></span> -->
                                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/avatar-4.jpg' ?>">
                                        </label>
                                    </div>
                                    <div class="ays-chatgpt-assistant-avatars-main-div">
                                        <input type="radio" class="ays-chatgpt-assistant-avatars-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-5" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_avatar" value="avatar-5">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-5" class="ays-chatgpt-assistant-avatar-item">
                                            <!-- <span><?php // echo __('Default', $this->plugin_name); ?></span> -->
                                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/avatar-5.jpg' ?>">
                                        </label>
                                    </div>
                                    <div class="ays-chatgpt-assistant-avatars-main-div">
                                        <input type="radio" class="ays-chatgpt-assistant-avatars-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-6" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_avatar" value="avatar-6">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-avatar-6" class="ays-chatgpt-assistant-avatar-item">
                                            <!-- <span><?php // echo __('Default', $this->plugin_name); ?></span> -->
                                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/avatars/avatar-6.jpg' ?>">
                                        </label>
                                    </div>
                                </div>
                                <hr style="opacity:.25">
                                <button id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-chat-avatar" class="button <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-add-chat-icon" type="button"><?php echo __('Upload', 'chatgpt-assistant'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Change chat avatar start -->

                <!-- Display chat on pages start -->
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-12 ays-pro-features-v2-main-box" style="padding:10px;">
                        <div class="ays-pro-features-v2-small-buttons-box">
                            <div class="ays-pro-features-v2-video-button"></div>
                            <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                <div class="ays-pro-features-v2-upgrade-text">
                                    <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                </div>
                            </a>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>
                                    <span><?php echo __('Display',  "ays-chatgpt-assistant"); ?></span>
                                    <a class="ays_help" data-bs-toggle="tooltip"
                                    title="<?php echo __('Define the pages your popup will be loaded on.', " ays-chatgpt-assistant"); ?>">
                                        <i class="ays_fa ays_fa-info-circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-8 ays_divider_left">
                                <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-label-style <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-display-type-select" for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-display-chat-on-all-pages"><?php echo __("All pages",  "ays-chatgpt-assistant"); ?>
                                    <input type="radio" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-display-chat-on-all-pages" checked />
                                </label>
                                <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-label-style <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-display-type-select" for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-display-chat-on-except-pages"><?php echo __("Except",  "ays-chatgpt-assistant"); ?>
                                    <input type="radio" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-display-chat-on-except-pages" />
                                </label>
                                <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-label-style <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-display-type-select" for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-display-chat-on-selected-pages"><?php echo __("Include",  "ays-chatgpt-assistant"); ?>
                                    <input type="radio" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-display-chat-on-selected-pages" />
                                </label>
                                <a class="ays_help" data-bs-toggle="tooltip" style="font-size:15px;" data-html="true"
                                    title="<?php
                                        echo __('Choose the method of calculation.', "ays-chatgpt-assistant") .
                                        "<ul style='list-style-type: circle;padding-left: 20px;'>".
                                            "<li>". __('All pages - The popup will display on all pages.', "ays-chatgpt-assistant") ."</li>".
                                            "<li>". __('Except - Choose the post/page and post/page types excluding the popup.', "ays-chatgpt-assistant") ."</li>".
                                            "<li>". __('Include - Choose the post/page and post/page types including the popup.', "ays-chatgpt-assistant") ."</li>".
                                        "</ul>";
                                    ?>">
                                    <i class="ays_fa ays_fa-info-circle"></i>
                                </a>
                            </div>
                        </div>                      
                    </div>           
                </div>           
                <!-- Display chat on pages end -->
                <hr/>
                <!-- Logged in/guests access start -->
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-12 ays-pro-features-v2-main-box" style="padding:10px;">
                        <div class="ays-pro-features-v2-small-buttons-box">
                            <div class="ays-pro-features-v2-video-button"></div>
                            <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                <div class="ays-pro-features-v2-upgrade-text">
                                    <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                </div>
                            </a>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-access-for-guests">
                                    <?php echo __( "Allow access for guests",  "ays-chatgpt-assistant" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-8 ays_divider_left">
                                <label class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-switch">
                                    <input class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch" type="checkbox" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-access-for-guests" checked>
                                    <span class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-slider <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-toggle-switch-round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <!-- Logged in access start -->
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;">
            <?php
                wp_nonce_field('front_chat_action', 'front_chat_action');
                $other_attributes = array();
                submit_button(__('Save changes', $this->plugin_name), 'primary ays-chatgpt-assistant-loader-banner ays-chatgpt-assistant-general-settings-save', 'ays_submit', true, $other_attributes);
                // echo $loader_iamge;
            ?>
            </div>
        </form>
    </div>
</div>