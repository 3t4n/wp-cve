<div class="tab-pane fade show active" id="v-pills-main" role="tabpanel" aria-labelledby="main-tab">
    <div class="row">
        <div class="col-md-8">
            <div class="title">Main configuration</div>
            <div class="alert alert-danger" id="conveythis_confirmation_message_danger" role="alert" style="display: none;border: #ce1717 2px solid;color: #000;padding-left: 10px;background: #fff;">
                We're sorry, you haven't verified your account. Follow the link in your email <span style="display: inline-block;"><b></b></span>
            </div>
            <div class="alert alert-warning" id="conveythis_confirmation_message_warning" role="alert" style="display: none;border: #ffecb5 2px solid;color: #000;padding-left: 10px;background: #fff;">
                Your account is not verified, you can use the plugin until <span></span>
            </div>

            <div class="alert alert-danger" id="conveythis_word_translation_exceeded_warning" role="alert" style="display: none;border: #f5c2c7 2px solid;color: #000;padding-left: 10px;background: #fff;">
                Your translation word limit has been exceeded, please upgrade your plan <span></span>
            </div>

            <div class="form-group" id="apiKey">
                <div class="subtitle">Api Key</div>
                <div class="ui input w-100">
                    <input type="text" name="api_key" id="conveythis_api_key" class="conveythis-input-text text-truncate" value="<?php echo  esc_attr( $this->variables->api_key ); ?>" placeholder="pub_XXXXXXXXXXXXXXXX" />
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                </div>
                <label class="validation-label">This field is required</label>
            </div>

            <div class="form-group" id="sourceLanguage">
                <div class="subtitle">Source Language</div>
                <label for="">What is the source (current) language of your website?</label>
                <div class="ui dropdown fluid search selection widget-trigger dropdown-current-language">
                    <input type="hidden" name="source_language" value="<?php echo esc_html($this->variables->source_language); ?>">
                    <i class="dropdown icon"></i>
                    <div class="default text"><?php echo  __( 'Select source language', 'conveythis-translate' ); ?></div>
                    <div class="menu">

                        <?php foreach( $this->variables->languages as $language ): ?>

                            <div class="item" data-value="<?php echo  esc_attr( $language['code2'] ); ?>">
                                <?php echo  esc_html_e( $language['title_en'], 'conveythis-translate' ); ?>
                            </div>

                        <?php endforeach; ?>

                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                </div>
                <label class="validation-label">This field is required</label>
            </div>
            <div class="form-group" id="targetLanguages">
                <div class="subtitle">Target Languages</div>
                <label for="">Choose languages you want to translate into.</label>
                <div class=" ui dropdown  fluid multiple search selection dropdown-target-languages widget-trigger">
                    <input type="hidden" name="target_languages" value="<?php echo  implode( ',', $this->variables->target_languages ); ?>">
                    <i class="dropdown icon"></i>
                    <div class="default text">French, German, Italian, Portuguese…</div>
                    <div class="menu">

                        <?php
                        foreach ($this->variables->languages as $language) {
//                            if (!empty($this->source_language) && $this->source_language == $language['code2']) {
//                                continue;
//                            }
                            ?>

                            <div class="item target-language-<?php echo esc_attr($language['code2']); ?>" data-value="<?php echo esc_attr($language['code2']); ?>">
                                <?php esc_html_e($language['title_en'], 'conveythis-translate'); ?>
                            </div>

                        <?php
                        }
                        ?>

                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                </div>
                <label class="validation-label">This field is required</label>
                <label class="hide-paid" for="">On the free plan, you can only choose one target language.<br>
                    If you want to use more than 1 language, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.</label>
            </div>

        </div>

        <div class="col-md-4" style="display: flex; align-items: center; justify-content: center;" id="widget-preview-general">
            <div class="widget-preview">
                                <span>
                                    Widget preview
                                </span>
                <div class="customize-view-button-wrapper <?php echo 'widget-'.$this->variables->style_widget; ?>" dir="ltr" style="margin: 0; padding: 10px 10px 10px 15px; border: none; border-top: 1px solid rgba(34,36,38,.1);">
                    <div id="customize-view-button" style="z-index: 1;"></div>
                </div>

            </div>
        </div>
    </div>

</div>