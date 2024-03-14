<?php
    require_once 'chatgpt-assistant-image-generator-actions-options.php';
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
    <form method="post" id="ays-image-generator-form">
        <h1 class="wp-heading-inline">
            <?php
                echo __( esc_html( get_admin_page_title() ), "ays-chatgpt-assistant" );
            ?>
        </h1>
    
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
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
                            <div class="form-group row p-3">
                                <input type="hidden" class="ays-chatgpt-assistant-openai-api-key" value="<?php echo esc_attr($api_key) ?>">
                                <div class="col-sm-3 p-4">
                                    <div class="row m-0 mb-3">
                                        <label for="ays-chatgpt-assistant-ig-select-model" class="form-label"><?php echo __('Model', 'chatgpt-assistant'); ?></label>
                                        <select class="form-select" id="ays-chatgpt-assistant-ig-select-model">
                                            <?php foreach ($models as $key => $value) : ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="row m-0 mb-3">
                                        <label for="ays-chatgpt-assistant-ig-select-size" class="form-label"><?php echo __('Size', 'chatgpt-assistant'); ?></label>
                                        <select class="form-select" id="ays-chatgpt-assistant-ig-select-size">
                                            <?php foreach ($sizes as $key => $value) : ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="row m-0 mb-3">
                                        <label for="ays-chatgpt-assistant-ig-select-style" class="form-label"><?php echo __('Style', 'chatgpt-assistant'); ?></label>
                                        <select class="form-select" id="ays-chatgpt-assistant-ig-select-style">
                                            <?php foreach ($styles as $key => $value) : ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="row m-0 mb-3">
                                        <label for="ays-chatgpt-assistant-ig-select-resolution" class="form-label"><?php echo __('Resolution', 'chatgpt-assistant'); ?></label>
                                        <select class="form-select" id="ays-chatgpt-assistant-ig-select-resolution">
                                            <?php foreach ($resolutions as $key => $value) : ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="row m-0 mb-3">
                                        <label for="ays-chatgpt-assistant-ig-select-photography" class="form-label"><?php echo __('Photography', 'chatgpt-assistant'); ?></label>
                                        <select class="form-select" id="ays-chatgpt-assistant-ig-select-photography">
                                            <?php foreach ($photography as $key => $value) : ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-9 p-4 d-flex flex-column justify-content-between">
                                    <div class="m-0">
                                        <div class="row m-0 mb-3">
                                            <label for="ays-chatgpt-assistant-ig-prompt" class="form-label"><?php echo __('Prompt', 'chatgpt-assistant'); ?></label>
                                            <textarea id="ays-chatgpt-assistant-ig-prompt" style="resize:none;height:<?php echo $textarea_height?>px"></textarea>
                                            <small class="form-text text-muted">
                                                <?php echo sprintf( __('%sNote:%s DALL·E 3 model takes in the default prompt provided and automatically re-writes it for safety reasons, and to add more detail (more detailed prompts generally result in higher quality images). While it is not currently possible to disable this feature, you can use prompting to get outputs closer to your requested image by adding the following to your prompt: %sI NEED to test how the tool works with extremely simple prompts. DO NOT add any detail, just use it AS-IS%s:.', 'chatgpt-assistant'),
                                                    '<strong>',
                                                    '</strong>',
                                                    '<code><strong>',
                                                    '</strong></code>'
                                                ); ?>
                                            </small>
                                        </div>
                                        <div class="row m-0">
                                            <button type="button" id="ays-chatgpt-assistant-ig-generate" class="btn btn-outline-primary btn-md" style="width:fit-content"><?php echo __('Generate', 'chatgpt-assistant'); ?> <i class="fa fa-spinner fa-spin display_none"></i></button>
                                        </div>
                                    </div>
                                    <div class="m-0">
                                        <div class="row m-0 ays-chatgpt-assistant-image-generator-buttons ays-chatgpt-assistant-image-box-template">
                                            <div>
                                                <a role="button" target="_blank" download="image" class="ays-chatgpt-assistant-ig-download btn btn-primary btn-md w-100"><?php echo __('Download', 'chatgpt-assistant'); ?></a>
                                                <button type="button" class="ays-chatgpt-assistant-ig-save-media btn btn-secondary btn-md w-100"><?php echo __('Add to Media', 'chatgpt-assistant'); ?> <i class="fa fa-spinner fa-spin display_none"></i></button>
                                            </div>
                                        </div>
                                        <div class="row m-0 mt-4 ays-chatgpt-assistant-image-generator-img"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>