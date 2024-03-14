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
    <h1 class="wp-heading-inline">
        <?php
            echo __( esc_html( get_admin_page_title() ), "ays-chatgpt-assistant" );
        ?>
    </h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div style="width:90%;margin:auto;text-align:center">
                    <div>
                        <h3 style="text-align:center"><?php echo __('Content Generator for Gutenberg', "ays-chatgpt-assistant"); ?></h3>
                    </div>
                    <div class="ays-survey-create-survey-youtube-video" style="margin-top:30px">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/7fWQLNcz-KA?si=K8ouJtM5kTNhrPS-" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="max-width:100%;margin:auto;display:block" loading="lazy"></iframe>
                    </div>
                    <br>
                    <blockquote style="text-align:left;border:none;background:#FFE9DE;line-height:0;border-radius:5px">
                        <img src="<?php echo esc_attr(CHATGPT_ASSISTANT_ADMIN_URL); ?>/images/icons/pro-features-icons/Locked_24x24.svg">
                        <?php echo sprintf(__( "This feature is available in the %sPRO%s version.", "ays-chatgpt-assistant" ) , "<a href='https://ays-pro.com/wordpress/chatgpt-assistant' target='_blank'>", "</a>"); ?>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</div>