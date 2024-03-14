<?php 

$data = $this->db_obj->get_data();

$api_key = isset( $data['api_key'] ) && $data['api_key'] != '' ? esc_attr( $data['api_key'] ) : '';

$check_openai_connection = ChatGPT_assistant_Data::makeRequest($api_key, 'GET', 'models');
$check_openai_connection_code = false;

if(is_array($check_openai_connection)){
    $check_openai_connection_code = isset($check_openai_connection['openai_response_code']) && $check_openai_connection['openai_response_code'] == 200 ? true : false; 
}

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
        <form method="post" id="ays-logs-form">
            <!-- <input type="hidden" name="ays_tab" value="<?php // echo $ays_tab; ?>"> -->
            <h1 class="wp-heading-inline">
            <?php
                echo __('Logs',$this->plugin_name);
            ?>
            </h1>
            <hr>
            <div class="ays-settings-wrapper">
                <!-- <div class="<?php // echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-settings-nav-tab">
                    <div class="<?php // echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php // echo ($ays_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php // echo __("Logs", $this->plugin_name);?>
                        </a>
                    </div>
                </div> -->
                <!-- <div class="<?php // echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-tabs-wrapper"> -->
                    <!-- <div id="tab1" class="<?php // echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-tab-content ays-tab-content <?php // echo ($ays_tab == 'tab1') ? 'ays-tab-content-active' : ''; ?>"> -->
                        <div id="poststuff">
                            <div id="post-body" class="metabox-holder">
                                <div id="post-body-content" class="ays-pro-features-v2-main-box">
                                    <div class="ays-pro-features-v2-small-buttons-box" style="width:fit-content;">
                                        <div class="ays-pro-features-v2-video-button"></div>
                                        <a href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank" class="ays-pro-features-v2-upgrade-button">
                                            <div class="ays-pro-features-v2-upgrade-icon" style="background-image: url('<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg');" data-img-src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg"></div>
                                            <div class="ays-pro-features-v2-upgrade-text">
                                                <?php echo __("Upgrade" , "chatgpt-assistant"); ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="meta-box-sortables ui-sortable">
                                        <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/logs-table.png" style="width:100%;">
                                    </div>
                                </div>
                            </div>
                            <br class="clear">
                        </div>
                    <!-- </div> -->
                <!-- </div> -->
            </div>
            <hr>
        </form>
    </div>
</div>