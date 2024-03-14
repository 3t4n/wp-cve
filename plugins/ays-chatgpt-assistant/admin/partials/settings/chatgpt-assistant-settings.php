<?php
    require_once 'chatgpt-assistant-settings-action-options.php';
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
<?php if ($id <= 0) : ?>
    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal">
        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-container">
            <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-parent">
                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-image-row">
                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-image-container">
                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/chatgpt-icon.png" alt="ChatGPT Assistant Icon">
                    </div>
                </div>
                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-header-row">
                    <h2><?php echo __('AI Assistant with ChatGPT by AYS', "ays-chatgpt-assistant"); ?></h2>
                </div>
                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-content">
                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-box" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-label-row">
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" fill="#27B192">
                                <path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0S160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17v80c0 13.3 10.7 24 24 24h80c13.3 0 24-10.7 24-24V448h40c13.3 0 24-10.7 24-24V384h40c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/>
                            </svg>
                            <?php echo __('Enter your OpenAI API Key', "ays-chatgpt-assistant"); ?>
                        </p>
                    </label>
                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-connection-row">
                        <form method="post">
                            <input type="hidden" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_id" value="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-main-id">
                            <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-make-connection">                             
                                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-connection">                            
                                    <input type="text" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-box" id="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-box" name="ays_chatgpt_assistant_api_key" value="<?php echo esc_attr($api_key); ?>">
                                </div>
                                <div style="">
                                    <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-connection-message"></span>                                
                                </div>
                                <div style="margin-top:5px;font-size:13px;font-style:italic;color:#a7a7a7">
                                    <ol style="margin-left:0;padding-left:1rem">
                                        <li><?php echo sprintf(__('%sSign up%s and visit your %sOpenAI key page%s', "ays-chatgpt-assistant"), '<a style="color:#3d3d3d" href="https://platform.openai.com/signup" target="_blank">', '</a>', '<a style="color:#3d3d3d" href="https://platform.openai.com/account/api-keys" target="_blank">', '</a>'); ?></li>
                                        <li><?php echo __('Create a new key and paste the key here.', "ays-chatgpt-assistant"); ?></li>
                                        <li><?php echo __('Click the "Connect API Key" button.', "ays-chatgpt-assistant"); ?></li>
                                    </ol>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-footer-row">
                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-modal-buttons">
                        <button type="button" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-skip-button" name="ays_chatgpt_assistant_skip_bttn">
                            <?php echo __('Skip', "ays-chatgpt-assistant"); ?>
                        </button>
                        <button type="button" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-connect-button" name="ays_chatgpt_assistant_connect_bttn">
                            <?php echo __('Connect API Key', "ays-chatgpt-assistant"); ?>
                        </button>
                        <?php echo $api_loader_iamge; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="wrap" style="position:relative;">
    <div class="container-fluid">
        <form method="post" id="ays-settings-form">
            <input type="hidden" name="ays_tab" value="<?php echo esc_attr($ays_tab); ?>">
            <h1 class="wp-heading-inline">
            <?php
                echo __('Settings', "ays-chatgpt-assistant");
            ?>
            </h1>
            <hr/>
            <div class="ays-settings-wrapper">
                <div>
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("General", "ays-chatgpt-assistant");?>
                        </a>
                        <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Styles", "ays-chatgpt-assistant");?>
                        </a>
                        <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("API Key", "ays-chatgpt-assistant");?>
                        </a>
                    </div>
                </div>
                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-tabs-wrapper">
                    <div id="tab1" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-tab-content ays-tab-content <?php echo ($ays_tab == 'tab1') ? 'ays-tab-content-active' : ''; ?>">
                        <input type="hidden" name="ays_chatgpt_assistant_chatbox_onoff" value="<?php echo $chatbox_onoff; ?>">    
                        <p class="ays-subtitle"><?php echo __('General',"ays-chatgpt-assistant")?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <h5><?php echo __('General settings',"ays-chatgpt-assistant")?></h5>
                            </legend>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-show-dashboard-chat">
                                        <?php echo __( "Show Dashboard Chat ", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to show the chatbot on the admin dashboard.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-show-dashboard-chat" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_show_dashboard_chat" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-show-dashboard-chat" value="on" <?php echo ($this->chatgpt_assistant_show_dashboard_chat) ? 'checked' : '';  ?>>
                                </div>
                            </div>                            
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-position">
                                        <?php echo __( "Chat position", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the position of chatbot.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbox_position" class="form-select <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX);?>-chatbox-position ays-text-input ays-text-input-short ays-input-height">
                                        <?php
                                            foreach($chatbox_positions as $key => $val){
                                                $selected = ($key == $this->chatbox_position) ? 'selected' : '';
                                                echo "<option ".esc_attr($selected)." value='".esc_attr($key)."'>".esc_attr($val)."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-icon-position">
                                        <?php echo __( "Chat icon position", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the icon position of chatbot.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbox_icon_position" class="form-select <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX);?>-chatbox-icon-position ays-text-input ays-text-input-short ays-input-height">
                                        <?php
                                            foreach($chatbox_icon_positions as $key => $val){
                                                $selected = ($key == $this->chatbox_icon_position) ? 'selected' : '';
                                                echo "<option ".esc_attr($selected)." value='".esc_attr($key)."'>".esc_attr($val)."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-icon-size">
                                        <?php echo __( "Chat icon size", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the size of the chat icon (width and height are equal). Please note that the option does not work with the chatbot embedded by a shortcode.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex">
                                    <div>
                                        <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-icon-size" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_icon_size" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-icon-size" value="<?php echo esc_attr($this->chatgpt_assistant_chat_icon_size); ?>">
                                    </div>
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-box">
                                        <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-width">
                                        <?php echo __( "Chat width", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Set the initial width for the chatbot's display","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex">
                                    <div>
                                        <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-width" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_width" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-width" value="<?php echo esc_attr($this->chatgpt_assistant_chat_width); ?>">
                                    </div>
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-box">
                                        <select class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-width-format-change <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-option-select-input form-select" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_width_format">
                                            <option value="%" <?php echo $this->chatgpt_assistant_chat_width_format == '%' ? 'selected' : ''; ?>>%</option>
                                            <option value="px" <?php echo $this->chatgpt_assistant_chat_width_format == 'px' ? 'selected' : ''; ?>>px</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-height">
                                        <?php echo __( "Chat height", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Set the initial height for the chatbot's display","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex">
                                    <div>
                                        <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-height" type="number" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_height" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-height" value="<?php echo esc_attr($this->chatgpt_assistant_chat_height); ?>">
                                    </div>
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-box">
                                        <select class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-width-format-change <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-option-select-input form-select" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_height_format">
                                            <option value="%" <?php echo $this->chatgpt_assistant_chat_height_format == '%' ? 'selected' : ''; ?>>%</option>
                                            <option value="px" <?php echo $this->chatgpt_assistant_chat_height_format == 'px' ? 'selected' : ''; ?>>px</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-auto-opening-chatbox">
                                        <?php echo __( "Auto opening Chatbox", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option, and the chat box will be opened automatically each time the user refreshes the page.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 ays_toggle_parent">
                                    <div>
                                        <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-auto-opening-chatbox ays_toggle_checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_auto_opening_chatbox" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-auto-opening-chatbox" value="on" <?php echo ($this->chatgpt_assistant_auto_opening_chatbox) ? 'checked' : '';  ?>>
                                    </div>
                                    <hr class="ays_toggle_target" style="<?php echo ($this->chatgpt_assistant_auto_opening_chatbox) ? '' : 'display:none;';  ?>">
                                    <div class="form-group row ays_toggle_target " style="<?php echo ($this->chatgpt_assistant_auto_opening_chatbox) ? '' : 'display:none;';  ?>">
                                        <div class="col-sm-3">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-auto-opening-chatbox-delay">
                                                <?php echo __( "Delay", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Adjust the delay before the chatbox automatically opens in milliseconds.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="number" class="ays-text-input ays-text-input-short" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_auto_opening_chatbox_delay" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-auto-opening-chatbox-delay" value="<?php echo esc_attr($this->chatgpt_assistant_auto_opening_chatbox_delay);?>">
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <hr/>                            
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-greeting-message">
                                        <?php echo __( "Greeting message", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Tick this option to display a greeting message at the beginning of the conversation with the user.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 ays_toggle_parent">
                                    <div>
                                        <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-greeting-message ays_toggle_checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_greeting_message" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-greeting-message" value="on" <?php echo ($this->chatgpt_assistant_greeting_message) ? 'checked' : '';  ?>>
                                    </div>
                                    <hr class="ays_toggle_target" style="<?php echo ($this->chatgpt_assistant_greeting_message) ? '' : 'display:none;';  ?>">
                                    <div class="form-group row ays_toggle_target " style="<?php echo ($this->chatgpt_assistant_greeting_message) ? '' : 'display:none;';  ?>">
                                        <div class="col-sm-3">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-greeting-message-text">
                                                <?php echo __( "Message", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Greeting message custom text. Note in order to use the default message leave blank.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_greeting_message_text" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-greeting-message-text" style="height:<?php echo $textarea_height?>px"><?php echo esc_attr($this->chatgpt_assistant_greeting_message_text); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-regenerate-response">
                                        <?php echo __( "Regenerate Response", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Tick this option, to enable response regeneration.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-regenerate-response" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_regenerate_response" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-regenerate-response" value="on" <?php echo ($this->chatgpt_assistant_regenerate_response) ? 'checked' : '';  ?>>
                                </div>
                            </div> 
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-placeholder">
                                        <?php echo __( "Message Placeholder Text", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("In this field you can write the message, you want to be displayed in the placeholder. In case of leaving it blank, the default text will be displayed.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_message_placeholder" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-message-placeholder" placeholder="Enter your message" style="height:<?php echo $textarea_height?>px"><?php echo esc_attr($this->chatgpt_assistant_message_placeholder); ?></textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbot-name">
                                        <?php echo __( "ChatBot Name", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Write the name you want to be displayed as the name of the Chatbot.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbot_name" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chatbot-name" placeholder="AI Assistant" style="height:<?php echo $textarea_height?>px"><?php echo esc_attr($this->chatgpt_assistant_chatbot_name); ?></textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-compliance-text">
                                        <?php echo __( "Compliance Text", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to provide additional information about the Chatbot operation. For example, you can mention, that, while chatbot strives to provide precise information, certain responses may not be completely accurate and shouldn't be taken as professional advice.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_compliance_text" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-compliance-text" placeholder="Note: AI Assistant can make mistakes. Consider checking important information." style="height:<?php echo $textarea_height?>px"><?php echo esc_attr($this->chatgpt_assistant_compliance_text); ?></textarea>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row ays-pro-features-v2-main-box">
                                <div class="ays-pro-features-v2-small-buttons-box" style="width:fit-content;">
                                    <div class="ays-pro-features-v2-video-button"></div>
                                    <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                        <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                        <div class="ays-pro-features-v2-upgrade-text">
                                            <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-save-chat-log">
                                        <?php echo __( "Save Chat Log", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Tick this option to save all the chat conversations.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 ays_toggle_parent">
                                    <div>
                                        <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-save-chat-log ays_toggle_checkbox" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-save-chat-log" value="on" checked>
                                    </div>
                                    <hr style="opacity:.15;">
                                    <div class="ays_toggle_target">
                                        <div class="form-group row" style="padding:5px;">
                                            <div class="col-sm-4">
                                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-information-form">
                                                    <?php echo __( "Enable information form", "ays-chatgpt-assistant" ); ?>
                                                    <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Tick this option to add the Information Form. Note: This step is optional and the users can send messages to Chatbot even if they don't fill their data. The data of the Information Form option is stored on the Logs page.","ays-chatgpt-assistant")?>">
                                                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-information-form" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-information-form">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row ays-pro-features-v2-main-box">
                                <div class="ays-pro-features-v2-small-buttons-box" style="width:fit-content;">
                                    <div class="ays-pro-features-v2-video-button"></div>
                                    <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                        <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                        <div class="ays-pro-features-v2-upgrade-text">
                                            <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-export">
                                        <?php echo __( "Export chat", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("The option will allow your users to export the chat conversation.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-export" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-export">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat">
                                        <?php echo __( "Rate chat", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option and the users can rate the chat with like and dislike buttons. Note: The option will work only on the front-end.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 ays_toggle_parent">
                                    <div>
                                        <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat ays_toggle_checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_enable_rate_chat" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat" value="on" <?php echo ($this->chatgpt_assistant_enable_rate_chat) ? 'checked' : '';  ?>>
                                    </div>
                                    <div class="ays_toggle_target" style="<?php echo ($this->chatgpt_assistant_enable_rate_chat) ? '' : 'display:none;';  ?>">
                                        <hr>
                                        <div class="form-group row" style="margin-right:0;margin-left:0">
                                            <div class="col-sm-4">
                                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-text">
                                                    <?php echo __( "Text", "ays-chatgpt-assistant" ); ?>
                                                    <!-- <a class="ays_help" data-bs-toggle="tooltip" title="<?php // echo __("....","ays-chatgpt-assistant")?>">
                                                        <img src="<?php // echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                    </a> -->
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_rate_chat_text" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-text" placeholder="How Satisfied are You?" style="height:<?php echo $textarea_height?>px"><?php echo esc_attr($this->chatgpt_assistant_rate_chat_text); ?></textarea>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-sub-container" style="margin-right:0;margin-left:0">
                                            <div class="col-sm-6">
                                                <div class="form-group row <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-container <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-suboption-parent" data-section="like">
                                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-heading-box">
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-heading">
                                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/like-icon.svg" alt="Like">
                                                            <div><?php echo __('Like Actions', 'chatgpt-assistant') ; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-actions-box">
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-option-title">
                                                            <?php echo __('Action', 'chatgpt-assistant'); ?>
                                                        </div>
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-actions-actions">
                                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-like-action-feedback">
                                                                <input type="radio" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_rate_chat[like][action]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-like-action-feedback" data-child-action="feedback" value="feedback" <?php echo ($this->chatgpt_assistant_rate_chat_like['action'] == 'feedback') ? 'checked' : '';  ?>>
                                                                <?php echo __('Ask a Feedback', 'chatgpt-assistant'); ?>
                                                            </label>
                                                            <div class="ays-pro-features-v2-main-box" style="padding:0">
                                                                <div class="ays-pro-features-v2-small-buttons-box-middle ays-pro-features-v2-big-buttons-box">
                                                                    <div class="ays-pro-features-v2-video-button"></div>
                                                                    <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                                                        <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url(<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                                                        <div class="ays-pro-features-v2-upgrade-text"><?php echo __('Upgrade', 'chatgpt-assistant'); ?></div>
                                                                    </a>
                                                                </div>
                                                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-like-action-help">
                                                                    <input type="radio" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-like-action-help">
                                                                    <?php echo __('Show Help Buttons', 'chatgpt-assistant'); ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-feedback-box">
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-option-title">
                                                            <?php echo __('Text', 'chatgpt-assistant'); ?>
                                                        </div>
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-feedback">
                                                            <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_rate_chat[like][text]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-like-text"><?php echo $this->chatgpt_assistant_rate_chat_like['text']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="<?php // echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-help-box" data-child-container="help" style="<?php // echo ($this->chatgpt_assistant_rate_chat_like['action'] == 'help') ? '' : 'display:none';  ?>">
                                                        <hr>
                                                        <div class="<?php // echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-help-buttons-box"></div>
                                                        <button class="<?php // echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-help-add-new" data-section="like" type="button"><?php // echo __('Add New', 'chatgpt-assistant'); ?></button>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="col-sm-6 <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-border">
                                                <div class="form-group row <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-container <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-suboption-parent" data-section="dislike">
                                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-heading-box">
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-heading">
                                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/dislike-icon.svg" alt="Dislike">
                                                            <div><?php echo __('Dislike Actions', 'chatgpt-assistant') ; ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-actions-box">
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-option-title">
                                                            <?php echo __('Action', 'chatgpt-assistant'); ?>
                                                        </div>
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-actions-actions">
                                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-dislike-action-feedback">
                                                                <input type="radio" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_rate_chat[dislike][action]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-dislike-action-feedback" data-child-action="feedback" value="feedback" <?php echo ($this->chatgpt_assistant_rate_chat_dislike['action'] == 'feedback') ? 'checked' : '';  ?>>
                                                                <?php echo __('Ask a Feedback', 'chatgpt-assistant'); ?>
                                                            </label>
                                                            <div class="ays-pro-features-v2-main-box" style="padding:0">
                                                                <div class="ays-pro-features-v2-small-buttons-box-middle ays-pro-features-v2-big-buttons-box">
                                                                    <div class="ays-pro-features-v2-video-button"></div>
                                                                    <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                                                        <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url(<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg);" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                                                        <div class="ays-pro-features-v2-upgrade-text"><?php echo __('Upgrade', 'chatgpt-assistant'); ?></div>
                                                                    </a>
                                                                </div>
                                                                <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-dislike-action-help">
                                                                    <input type="radio" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-dislike-action-help">
                                                                    <?php echo __('Show Help Buttons', 'chatgpt-assistant'); ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-feedback-box">
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-option-title">
                                                            <?php echo __('Text', 'chatgpt-assistant'); ?>
                                                        </div>
                                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-feedback">
                                                            <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_rate_chat[dislike][text]" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-rate-chat-dislike-text"><?php echo $this->chatgpt_assistant_rate_chat_dislike['text']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="<?php // echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-help-box" data-child-container="help" style="<?php // echo ($this->chatgpt_assistant_rate_chat_dislike['action'] == 'help') ? '' : 'display:none';  ?>">
                                                        <hr>
                                                        <div class="<?php // echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-help-buttons-box"></div>
                                                        <button class="<?php // echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-rate-chat-help-add-new" data-section="dislike" type="button"><?php // echo __('Add New', 'chatgpt-assistant'); ?></button>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <blockquote>
                                <?php echo sprintf(__( "You can view the rates on the %sRates page%s", "ays-chatgpt-assistant" ) , "<a href='".admin_url('admin.php?page=' . $this->plugin_name . '-rates')."' target='_blank'>", "</a>"); ?>
                            </blockquote>
                        </fieldset> <!-- Chatbot General settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <h5><?php echo __('Chat settings',"ays-chatgpt-assistant")?></h5>
                            </legend>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-context">
                                        <?php echo __( "Context", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("The text that you will write in the Context field will be added to the beginning of the prompt. Note, in case you want to use the default message, you will need to leave the field blank.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_context" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-context" placeholder="Converse as if you are an AI assistant. Answer the question as truthfully as possible." style="height:<?php echo $textarea_height?>px"><?php echo esc_attr($this->chatgpt_assistant_context); ?></textarea>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-profession">
                                        <?php echo __( "Act as", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the profession of the chatbot. In order to disable this option leave it blank.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-profession" type="text" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_profession" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-profession" value="<?php echo esc_attr($this->chatgpt_assistant_profession); ?>" placeholder="Customer Support">
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-tone">
                                        <?php echo __( "Tone", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the tone of the chatbot.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_tone" class="form-select <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX);?>-tone ays-text-input ays-text-input-short ays-input-height">
                                            <option value="none" <?php echo ($this->chatgpt_assistant_tone == 'none') ? 'selected' : ''; ?>><?php echo __("None" , "ays-chatgpt-assistant")?></option>
                                        <?php
                                            foreach($chatbox_tones as $tone_key => $tone_val){
                                                $selected = ($tone_key == $this->chatgpt_assistant_tone) ? 'selected' : '';
                                                echo "<option ".esc_attr($selected)." value='".esc_attr($tone_key)."'>".esc_attr($tone_val)."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-language">
                                        <?php echo __( "Language", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the language of the chatbot to answer.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_language" class="form-select <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX);?>-language ays-text-input ays-text-input-short ays-input-height">
                                        <?php                                            
                                            foreach($supported_coutries as $lang_key => $lang_val){
                                                $selected = ($lang_key == $this->chatgpt_assistant_language) ? 'selected' : '';
                                                echo "<option ".esc_attr($selected)." value='".esc_attr($lang_key)."'>".esc_attr($lang_val)."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-name">
                                        <?php echo __( "AI Name", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the name of the chatbot. You can write any name you wish. For better result, you can also customize the other features in this page as well.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-name" type="text" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_name" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-name" value="<?php echo esc_attr($this->chatgpt_assistant_name); ?>" placeholder="Monica">
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <h5><?php echo __('Advanced settings',"ays-chatgpt-assistant")?></h5>
                            </legend>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-model">
                                        <?php echo __( "Model", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the suitable model for generating text completion, and consider whether it's designed for natural language or coding tasks. Picking the right one for your task is crucial for optimal results.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex align-items-center flex-wrap">
                                    <select name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_model" class="form-select <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX);?>-chat-model ays-text-input ays-text-input-short ays-input-height" data-setting="advanced" data-option="model">
                                        <option disabled>gpt-4-turbo (PRO)</option>
                                        <option disabled>gpt-4 (PRO)</option>
                                        <?php
                                            foreach($chatbox_models as $model_key => $model_val){
                                                $selected = ($model_key == $this->chatgpt_assistant_chat_model) ? 'selected' : '';
                                                echo "<option ".esc_attr($selected)." value='".esc_attr($model_key)."'>".esc_attr($model_val)."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-temprature">
                                        <?php echo __( "Temprature", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Controls the 'creativity' of the generated text by controlling the randomness of the responses. Higher temperature values lead to more varied and unpredictable responses, while lower temperature values lead to more conservative and predictable responses. Number between 0 and 2.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex align-items-center flex-wrap">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-setting-range" type="range" min="0" max="2" step="0.1" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_temprature" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-temprature" value="<?php echo esc_attr($this->chatgpt_assistant_chat_temprature); ?>" data-setting="advanced" data-option="temperature">                                   
                                    <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-limit-text"><?php echo esc_attr($this->chatgpt_assistant_chat_temprature); ?></span>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-top-p">
                                        <?php echo __( "Top P", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __(" 'Nucleus sampling' is an alternative to traditional sampling methods in language models. With this option, the model considers only the most probable tokens, based on a specified probability threshold. For example, if the top_p value is set to 0.1, only the tokens with the highest probability mass that make up the top 10% of the distribution will be considered for output. This can help generate more focused and coherent responses, while still allowing for some level of randomness and creativity in the generated text.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex align-items-center flex-wrap">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-setting-range" type="range" min="0" max="1" step="0.01" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chat_top_p" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chat-top-p" value="<?php echo esc_attr($this->chatgpt_assistant_chat_top_p); ?>" data-setting="advanced" data-option="topP">                                   
                                    <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-limit-text"><?php echo esc_attr($this->chatgpt_assistant_chat_top_p); ?></span>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-max-tokens">
                                        <?php echo __( "Maximum tokens", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specifies the maximum number of tokens (words or word-like units) that the chatbot will generate in response to a prompt. This can be used to control the length of the generated text. Maximum context length is 2048.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex align-items-center flex-wrap">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-setting-range" type="range" min="0" max="2048" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_max_tokens" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-max-tokens" value="<?php echo esc_attr($this->chatgpt_assistant_max_tokens); ?>" data-setting="advanced" data-option="maxToken">
                                    <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-limit-text"><?php echo esc_attr($this->chatgpt_assistant_max_tokens); ?></span>                                                           
                                </div>
                            </div>                            
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-frequency-penalty">
                                        <?php echo __( "Frequency penalty", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Encourages the chatbot to generate text with a more diverse vocabulary. A higher frequency penalty value will reduce the likelihood of the chatbot repeating words that have already been used in the generated text. Number between -2.0 and 2.0.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex align-items-center flex-wrap">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-setting-range" type="range" min="-2" max="2" step="0.01" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_frequency_penalty" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-frequency-penalty" value="<?php echo esc_attr($this->chatgpt_assistant_frequency_penalty); ?>" data-setting="advanced" data-option="freuencyPenality">
                                    <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-limit-text"><?php echo esc_attr($this->chatgpt_assistant_frequency_penalty); ?></span>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-presence-penalty">
                                        <?php echo __( "Presence penalty", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Encourages the chatbot to generate text that includes specific phrases or concepts. A higher presence penalty value will reduce the likelihood of the chatbot repeating the same phrases or concepts multiple times in the generated text. Number between -2.0 and 2.0.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex align-items-center flex-wrap">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-setting-range" type="range" min="-2" max="2" step="0.01" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_presence_penalty" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-presence-penalty" value="<?php echo esc_attr($this->chatgpt_assistant_presence_penalty); ?>" data-setting="advanced" data-option="presencePenality">
                                    <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-limit-text"><?php echo esc_attr($this->chatgpt_assistant_presence_penalty); ?></span>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-best-of">
                                        <?php echo __( "Best of", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Allows the chatbot to generate multiple responses to a single prompt and return the 'best' response based on a specified metric, such as highest log-probability or lowest perplexity. This can be useful for generating high-quality text or for exploring different variations of a response. Note: Because this parameter generates many completions, it can quickly consume your token quota","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex align-items-center flex-wrap">
                                    <input class="ays-text-input ays-text-input-short <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-setting-range" type="range" min="1" max="20" step="1" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_best_of" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-best-of" value="<?php echo esc_attr($this->chatgpt_assistant_best_of); ?>" data-setting="advanced" data-option="bestOf">
                                    <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-chat-limit-text"><?php echo esc_attr($this->chatgpt_assistant_best_of); ?></span>
                                </div>
                            </div>
                            <br>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <button type="button" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-reset-settings btn btn-primary" data-setting-type="advanced">
                                        <?php echo __( "Reset", "ays-chatgpt-assistant" ); ?>
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <h5><?php echo __('Chatbot Shortcode',"ays-chatgpt-assistant"); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label>
                                        <?php echo __( "Shortcode", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" data-bs-html="true" title="<?php echo __('Copy the given shortcode and insert it into any post or page to show the chatbot. ', "ays-chatgpt-assistant" ); ?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatgpt-assistant-shortcode" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_chatgpt_assistant]'>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-full-screen-mode">
                                        <?php echo __( "Full-screen mode", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to provide the opportunity to display the chat box in full-screen mode.","ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-full-screen-mode" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_full_screen_mode" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-full-screen-mode" value="on" <?php echo ($this->chatgpt_assistant_full_screen_mode) ? 'checked' : '';  ?>>
                                </div>
                            </div>
                            <hr/>
                            <blockquote>
                                <?php echo sprintf(__( "Note in order to use the shortcode you need to be %s connected %s to your OpenAI account.", "ays-chatgpt-assistant" ) , "<a href='".admin_url('admin.php?page=' . $this->plugin_name)."' target='_blank'>", "</a>"); ?>
                                <br>
                            </blockquote>
                        </fieldset> <!-- Chatbot shortcode -->
                        <hr>                        
                        <fieldset>
                            <legend>
                                <h5><?php echo __('Text-to-speech settings',"ays-chatgpt-assistant")?></h5>
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
                                                <?php echo __( "Enable text to speech for response", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("After enabling this option, the chat will be shown in the front. By default, it will be disabled.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-switch">
                                                <input class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch" type="checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbox_onoff" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-onoff" value="on">
                                                <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-slider <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr style="opacity:.15;">
                                    <div class="form-group row" style="padding:5px;">
                                        <div class="col-sm-4">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-tts">
                                                <?php echo __( "Text-to-Speech Voice", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("This option allows you to choose the preferred voice for the text-to-speech functionality.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_text_to_speech_voice" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-text-to-speech-voice" class="form-select <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-text-to-speech-voice ays-text-input ays-text-input-short ays-input-height">
                                                <option>Microsoft David - English (United States)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <h5><?php echo __('Speech-to-text settings',"ays-chatgpt-assistant")?></h5>
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
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-tts">
                                                <?php echo __( "Enable Speech-to-Text", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Click the microphone icon in the chatbox and start speaking. Send the audio and the system will provide an answer in text format.","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-switch">
                                                <input class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch" type="checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_response_stt" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-stt" value="on">
                                                <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-slider <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr style="opacity:.15;">
                                    <div class="form-group row" style="padding:5px;">
                                        <div class="col-sm-4">
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-stt-autoplay">
                                                <?php echo __( "Enable Autoplay for Speech-to-Text Response", "ays-chatgpt-assistant" ); ?>
                                                <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to activate the autoplay for the Speech-to-Text response","ays-chatgpt-assistant")?>">
                                                    <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <label class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-switch">
                                                <input class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch" type="checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_stt_autoplay" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-stt-autoplay" value="on">
                                                <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-slider <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div id="tab2" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-tab-content ays-tab-content <?php echo ($ays_tab == 'tab2') ? 'ays-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Styles',"ays-chatgpt-assistant")?></p>
                        <hr/>
                            <!-- Chat Theme -->
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-default-theme">
                                        <?php echo __( "Theme", $this->plugin_name ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Select your desired Theme from the ready-made templates. Then, customize it based on your preferences with the options below.", $this->plugin_name)?>">
                                            <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="ays-chatgpt-assistant-themes-main-div-wrap">
                                        <div class="ays-chatgpt-assistant-themes-main-div">
                                            <input type="radio" class="ays-chatgpt-assistant-themes-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-default-theme" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_theme" value="default" <?php echo ($this->chatbox_theme == 'default') ? 'checked' : '' ?>>
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-default-theme" class="ays-chatgpt-assistant-theme-item">
                                                <span><?php echo __('Default', $this->plugin_name); ?></span>
                                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/themes/default-theme.png' ?>" alt="Default theme">
                                            </label>
                                        </div>
                                        <div class="ays-chatgpt-assistant-themes-main-div">
                                            <input type="radio" class="ays-chatgpt-assistant-themes-inp" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatgpt-theme" name="<?php echo CHATGPT_ASSISTANT_NAME_PREFIX; ?>_chatbox_theme" value="chatgpt" <?php echo ($this->chatbox_theme == 'chatgpt') ? 'checked' : '' ?>>
                                            <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatgpt-theme" class="ays-chatgpt-assistant-theme-item">
                                                <span><?php echo __('ChatGPT', $this->plugin_name); ?></span>
                                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL . '/images/themes/chatgpt-theme.png' ?>" alt="ChatGPT theme">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <!-- Chat Widget color -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-color">
                                        <?php echo __( "Chat Widget color", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Choose the color of the chat widget and the 'Send' button.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbox_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-color" value="<?php echo $chatbox_color;?>">
                                </div>
                            </div>
                            <hr/>
                            <!-- Chat Widget background color -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-background-color">
                                        <?php echo __( "Chat Widget background color", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the background color of the chat widget.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbox_background_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-background-color" value="<?php echo $this->chatgpt_assistant_chatbox_background_color;?>">
                                </div>
                            </div> 
                            <hr>
                            <!-- Chat dark mode -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-mode">
                                        <?php echo __( "Chat dark mode", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option for activating the dark mode. If this option isn't activated the chat will be in light mode.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <label class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-switch">
                                        <input class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch" type="checkbox" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbox_mode" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-mode" value="dark" <?php echo $chatbox_mode; ?> >
                                        <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-slider <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-toggle-switch-round"></span>
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <!-- Message font size -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-font-size">
                                        <?php echo __( "Message font size", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the font size for chat message text.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex">
                                    <div>
                                        <input type="number" class="ays-text-input ays-text-input-short" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_message_font_size" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-font-size" value="<?php echo esc_attr($this->chatgpt_assistant_message_font_size);?>">
                                    </div>
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-box">
                                        <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Message spacing -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-spacing">
                                        <?php echo __( "Messages spacing", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the distance between messages in the chat.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex">
                                    <div>
                                        <input type="number" class="ays-text-input ays-text-input-short" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_message_spacing" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-spacing" value="<?php echo esc_attr($this->chatgpt_assistant_message_spacing);?>">
                                    </div>
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-box">
                                        <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Chat header text color -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-header-text-color">
                                        <?php echo __( "Header text color", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the color of the chat widget header text.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbox_header_text_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbox-header-text-color" value="<?php echo $this->chatgpt_assistant_chatbox_header_text_color;?>">
                                </div>
                            </div> 
                            <hr>
                            <!-- Message border radius -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-border-radius">
                                        <?php echo __( "Message border radius", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the border radius for chat message container.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex">
                                    <div>
                                        <input type="number" class="ays-text-input ays-text-input-short" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_message_border_radius" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-border-radius" value="<?php echo esc_attr($this->chatgpt_assistant_message_border_radius);?>">
                                    </div>
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-box">
                                        <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Chatbot border radius -->
                            <div class="form-group row" style="padding:5px;">
                                <div class="col-sm-4">
                                    <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbot-border-radius">
                                        <?php echo __( "Chatbot border radius", "ays-chatgpt-assistant" ); ?>
                                        <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Specify the border radius for chatbot container.", "ays-chatgpt-assistant")?>">
                                            <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8 d-flex">
                                    <div>
                                        <input type="number" class="ays-text-input ays-text-input-short" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_chatbot_border_radius" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-chatbot-border-radius" value="<?php echo esc_attr($this->chatgpt_assistant_chatbot_border_radius);?>">
                                    </div>
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-box">
                                        <input type="text" value="px" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-hint-input" disabled>
                                    </div>
                                </div>
                            </div>
                            <!-- === USER MESSAGE STYLES START === -->
                                <hr>
                                    <p class="ays-subtitle"><?php echo __('User message styles' , "ays-chatgpt-assistant")?></p>
                                <hr>
                                <!-- User message background color -->
                                <div class="form-group row" style="padding:5px;">
                                    <div class="col-sm-4">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-bg-color">
                                            <?php echo __( "User message background color", "ays-chatgpt-assistant" ); ?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Choose the background color for the user message.", "ays-chatgpt-assistant")?>">
                                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_message_bg_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-bg-color" value="<?php echo esc_attr($this->message_bg_color);?>">
                                    </div>
                                </div>
                                <hr>
                                <!-- User message text color -->
                                <div class="form-group row" style="padding:5px;">
                                    <div class="col-sm-4">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-text-color">
                                            <?php echo __( "User message text color", "ays-chatgpt-assistant" ); ?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Choose the text color for the user message.", "ays-chatgpt-assistant")?>">
                                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_message_text_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-message-text-color" value="<?php echo esc_attr($this->message_text_color);?>">
                                    </div>
                                </div>
                            <!-- === USER MESSAGE STYLES END === -->

                            <!-- === CHATBOT MESSAGE STYLES START === -->
                                <hr>
                                    <p class="ays-subtitle"><?php echo __('Chatbot message styles' , "ays-chatgpt-assistant")?></p>
                                <hr>
                                <!-- Response background color -->
                                <div class="form-group row" style="padding:5px;">
                                    <div class="col-sm-4">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-bg-color">
                                            <?php echo __( "Response background color", "ays-chatgpt-assistant" ); ?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Choose the background color for the response message.", "ays-chatgpt-assistant")?>">
                                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_response_bg_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-bg-color" value="<?php echo esc_attr($this->response_bg_color);?>">
                                    </div>
                                </div>
                                <hr>
                                <!-- Response color -->
                                <div class="form-group row" style="padding:5px;">
                                    <div class="col-sm-4">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-text-color">
                                            <?php echo __( "Response text color", "ays-chatgpt-assistant" ); ?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Choose the text color for the response message.", "ays-chatgpt-assistant")?>">
                                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_response_text_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-text-color" value="<?php echo esc_attr($this->response_text_color);?>">
                                    </div>
                                </div>
                                <hr>
                                <!-- Response icons color -->
                                <div class="form-group row" style="padding:5px;">
                                    <div class="col-sm-4">
                                        <label for="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-icons-color">
                                            <?php echo __( "Response icons color", "ays-chatgpt-assistant" ); ?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Choose the icons color for the response message.", "ays-chatgpt-assistant")?>">
                                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/info-circle.svg">
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="color" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_response_icons_color" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-response-icons-color" value="<?php echo esc_attr($this->response_icons_color);?>">
                                    </div>
                                </div>
                            <!-- === CHATBOT MESSAGE STYLES END === -->
                    </div>
                    <div id="tab3" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-tab-content ays-tab-content <?php echo ($ays_tab == 'tab3') ? 'ays-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('API Key',"ays-chatgpt-assistant")?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/chatgpt-icon-key.png" width="50" alt="ChatGPT Icon">
                                <h5><?php echo __('Connect to OpenAI',"ays-chatgpt-assistant"); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <input type="hidden" name="<?php echo esc_attr(CHATGPT_ASSISTANT_NAME_PREFIX); ?>_id" value="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr(CHATGPT_ASSISTANT_ID_PREFIX); ?>-main-id">
                                    <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-make-connection">                             
                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-connection">                            
                                            <input type="text" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-box" name="ays_chatgpt_assistant_api_key" value="<?php echo esc_attr($api_key); ?>" <?php echo $connection_input_readonly; ?>>
                                            <button type="button" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-<?php echo $connection_button_class; ?>-button" name="ays_chatgpt_assistant_save_bttn"><?php echo $connection_button_text; ?></button>
                                            <?php echo $api_loader_iamge; ?>
                                        </div>
                                        <div style="margin-top: 5px;">
                                            <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX)."-api-key-connection-message "; echo $check_openai_connection_code ? CHATGPT_ASSISTANT_CLASS_PREFIX."-api-key-connection-success-message" : ""; ?>"><?php echo $connection_text;?></span>                                
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <blockquote>
                                <p><strong><?php echo __('To get an API key for the OpenAI API, follow these steps:', "ays-chatgpt-assistant"); ?></strong></p>
                                <ol style="margin-left:0">
                                    <li><?php echo sprintf(__('Sign up %shere%s. You can use your Google or Microsoft account to sign up if you don`t want to create using an email/password combination. You may need a valid mobile number to verify your account.', "ays-chatgpt-assistant"), '<a href="https://platform.openai.com/signup" target="_blank">', '</a>'); ?></li>
                                    <li><?php echo sprintf(__('Now, visit your %sOpenAI key page%s.', "ays-chatgpt-assistant"), '<a href="https://platform.openai.com/account/api-keys" target="_blank">', '</a>'); ?></li>
                                    <li><?php echo __('Create a new key by clicking the "Create new secret key" button.', "ays-chatgpt-assistant"); ?></li>
                                    <li><?php echo __('Copy the key and paste it here', "ays-chatgpt-assistant"); ?></li>
                                    <li><?php echo __('Click "Connect" button.', "ays-chatgpt-assistant"); ?></li>
                                </ol>
                                <br>
                            </blockquote>
                        </fieldset>
                        <hr/>
                        <fieldset class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-connection-wrapper">
                            <legend>
                                <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/gemini-icon-key.png" width="50" alt="Gemini Icon">
                                <h5><?php echo __('Connect to Gemini',"ays-chatgpt-assistant"); ?></h5>
                            </legend>
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
                                <div class="form-group row" style="padding:0px;margin:0;">
                                    <div class="col-sm-12" style="padding:20px;">
                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-make-connection">                             
                                            <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-connection">                            
                                                <input type="text" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-api-key-box-gemini">
                                                <button type="button" class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-connect-button-gemini">Connect</button>
                                            </div>
                                            <div>
                                                <span class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX)."-api-key-connection-message-gemini"; ?>">Disconnected</span>                                
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <blockquote>
                                    <p><strong><?php echo sprintf(__('To get an API key for the Gemini API, you can create a key with one click in %sGoogle AI Studio%s.', "ays-chatgpt-assistant"), '<a href="https://makersuite.google.com/app/apikey" target="_blank">', '</a>'); ?></strong></p>
                                </blockquote>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;z-index: 3;">
            <?php
                wp_nonce_field('settings_action', 'settings_action');
                $other_attributes = array();
                submit_button(__('Save changes', "ays-chatgpt-assistant"), 'primary '.esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX).'-loader-banner '.esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX).'-general-settings-save', 'ays_submit', true, $other_attributes);
                echo wp_kses_post($loader_iamge);
            ?>
            </div>
        </form>
    </div>
</div>
