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
        <form method="post" id="ays-embeddings-form">
            <h1 class="wp-heading-inline">
            <?php
                echo __('Embeddings',"ays-chatgpt-assistant");
            ?>
            </h1>
            <hr>
            <div class="ays-chatgpt-embedding-video-box" style="text-align:center;">
                <iframe style="width: 100%;max-width: 560px;" height="315" src="https://www.youtube.com/embed/tetv6gx93Ew" loading="lazy" title="YouTube video player"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
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
                    <div class="ays-settings-wrapper">
                        <div class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-settings-nav-tab">
                            <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                                <a href="#tab1" data-tab="tab1" class="nav-tab nav-tab-active">
                                    <?php echo __("General", "ays-chatgpt-assistant");?>
                                </a>
                                <a href="#tab2" data-tab="tab2" class="nav-tab">
                                    <?php echo __("Embeddings", "ays-chatgpt-assistant");?>
                                </a>
                            </div>
                        </div>
                        <div class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-tabs-wrapper">
                            <div id="tab1" class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-tab-content ays-tab-content ays-tab-content-active">
                                <p class="ays-subtitle"><?php echo __('General',"ays-chatgpt-assistant")?></p>
                                <hr style="opacity:.25;">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-enable-embedding">
                                            <?php echo __( "Enable content embedding", "ays-chatgpt-assistant" ); ?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Enable this option to allow the ability to embed your website. The chatbot will be able to provide answers to your users, based on the information included in your website. You will need to add the Pinecone API Key and Index, otherwise the feature will not be connected. To get the API Key and Index you must follow the instructions.", "ays-chatgpt-assistant")?>">
                                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                                            </a>
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-hidden-suboption-checkbox" type="checkbox">
                                    </div>                            
                                </div>
                                <hr style="opacity:.25;">
                                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-embedding-container">
                                    <div>
                                        <h6>
                                            <?php echo __('Select posts to embed',$this->plugin_name)?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Choose specific posts you want to be embedded. By ticking on each checkbox, you will be able to choose the posts of the checked post type that can be embedded. In case of not choosing any specific post, all the posts will be embedded.", $this->plugin_name)?>">
                                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                                            </a>
                                        </h6>
                                        <br>
                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-posts-container">
                                            <div class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-posts-each-container form-group row mb-2">
                                                <div class="col-sm-4">
                                                    <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-post-type-post">
                                                        post
                                                    </label>
                                                </div>
                                                <div class="col-sm-1 <?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-hidden-suboption-parent">
                                                    <input type="checkbox" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-post-type-post" class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-post-type-all <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-hidden-suboption-checkbox">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-posts-each-container form-group row mb-2">
                                                <div class="col-sm-4">
                                                    <label for="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-post-type-page">
                                                        page
                                                    </label>
                                                </div>
                                                <div class="col-sm-1 <?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-hidden-suboption-parent">
                                                    <input type="checkbox" id="<?php echo CHATGPT_ASSISTANT_ID_PREFIX; ?>-post-type-page" class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-post-type-all <?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-hidden-suboption-checkbox">
                                                </div>
                                            </div>
                                            <div class="container" style="text-align:right;max-width:100%">
                                                <button type="button" class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-embed-posts btn btn-outline-primary btn-md"><i class="fa fa-spinner fa-spin display_none"></i> <?php echo __('Embed',$this->plugin_name); ?></button>
                                                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-response-message">
                                                    <div class="invalid-feedback <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-invalid-feedback"></div>
                                                    <div class="valid-feedback <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-valid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="opacity:.25">
                                    <div>
                                        <h6>
                                            <?php echo __('Custom content to embed',$this->plugin_name)?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Add custom content to embed", $this->plugin_name)?>">
                                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                                            </a>
                                        </h6>
                                        <br>
                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-custom-content-container">
                                            <textarea class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-custom-content" id="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-custom-content" rows="10" style="width:99%;min-height:150px;max-height:500px;padding:10px;" placeholder="Paste your content"></textarea>
                                            <div class="container" style="text-align:right;max-width:100%">
                                                <button type="button" class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-embed-custom-text btn btn-outline-primary btn-md"><i class="fa fa-spinner fa-spin display_none"></i> <?php echo __('Embed',$this->plugin_name); ?></button>
                                                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-response-message">
                                                    <div class="invalid-feedback <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-invalid-feedback"></div>
                                                    <div class="valid-feedback <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-valid-feedback"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div>
                                        <h6>
                                            <?php echo __('PDF embedding',$this->plugin_name)?>
                                            <a class="ays_help" data-bs-toggle="tooltip" title="<?php echo __("Add custom PDF file to embed its content", $this->plugin_name)?>">
                                                <img src="<?php echo CHATGPT_ASSISTANT_ADMIN_URL; ?>/images/icons/info-circle.svg">
                                            </a>
                                        </h6>
                                        <hr>
                                        <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-custom-file-container">
                                            <input type="file" class="form-control <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-custom-file" id="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-custom-file" accept=".pdf" style="width:99%;padding:0.375rem 0.75rem;margin-bottom:7px">
                                            <div class="container" style="text-align:right;max-width:100%">
                                                <button type="button" class="<?php echo CHATGPT_ASSISTANT_CLASS_PREFIX; ?>-embed-custom-file btn btn-outline-primary btn-md"><i class="fa fa-spinner fa-spin display_none"></i> <?php echo __('Embed',$this->plugin_name); ?></button>
                                                <div class="<?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-response-message">
                                                    <div class="invalid-feedback <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-invalid-feedback"></div>
                                                    <div class="valid-feedback <?php echo esc_attr(CHATGPT_ASSISTANT_CLASS_PREFIX); ?>-pinecone-valid-feedback"></div>
                                                </div>
                                            </div>
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
</div>