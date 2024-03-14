<form id="login-form-settings" method="POST" action="options.php">
    <?php
        settings_fields('my-plugin-settings');
        do_settings_sections('my-plugin-settings');
    ?>
    <div class="key-block mt-5">

        <div>
            <a href="https://www.conveythis.com/" target="_blank">
                <img src="<?php echo CONVEY_PLUGIN_PATH?>app/widget/images/conveythis-logo-vertical-blue.png" alt="ConveyThis">
            </a>
        </div>

        <div>Take a few steps to set up the plugin</div>
        
        <div class="m-auto my-4 text-center" style="max-width: 500px;width: 100%">
            <p>Paste Api key here</p>
            <div class="ui input w-100">
                <input type="text" name="api_key" id="conveythis_api_key" class="conveythis-input-text text-truncate" 
                    value="<?php echo $this->variables->api_key ?>"
                    placeholder="pub_XXXXXXXXXXXXXXXX"
                    <?php if($this->variables->api_key !== "") echo 'readonly' ?>
                >
            </div>
            <label class="validation-label" style="float: left; margin-top: 5px;">This field is required</label>

            <div class="my-4">
                <p>What is the source (current) language of your website?</p>
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
            </div>
            
            <div class="my-4">
                <p>Choose language you want to translate into</p>
                <?php if($this->variables->api_key !== "") {?>

                    <div class=" ui dropdown  fluid  search selection dropdown-target-languages widget-trigger"> <!-- multiple -->
                        <input type="hidden" name="target_languages" value="<?php echo  implode( ',', $this->variables->target_languages ); ?>">
                        <i class="dropdown icon"></i>
                        <div class="default text">French or German or Italian or Portuguese …</div>
                        <div class="menu">

                            <?php foreach ($this->variables->languages as $language): ?>

                                <div class="item target-language-<?php echo esc_attr($language['code2']); ?>" data-value="<?php echo esc_attr($language['code2']); ?>">
                                    <?php esc_html_e($language['title_en'], 'conveythis-translate'); ?>
                                </div>

                            <?php endforeach; ?>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                        </svg>
                    </div>

                <?php } else {?>

                    <div class="ui dropdown fluid search selection widget-trigger dropdown-current-language">
                        <input type="hidden" name="target_languages" value="<?php echo implode(',', $this->variables->target_languages); ?>">
                        <i class="dropdown icon"></i>
                        <div class="default text"><?php echo  __( 'Select target language', 'conveythis-translate' ); ?></div>
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

                <?php }?>

            </div>

            <div class="my-4">
                <input type="submit" name="submit" id="submit" class="btn btn-primary btn-custom" value="Save settings">
            </div>

        </div>

    </div>

</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

    var submitBlocked = true;

    document.getElementById('login-form-settings').addEventListener('submit', function(e) {
        if (submitBlocked) {
            e.preventDefault();

            var apiKeyInput = e.target.elements['api_key'];
            var apiKeyValue = apiKeyInput.value;
            var validationLabel = e.target.querySelector('.validation-label');
            var inputElements = e.target.querySelector('input#conveythis_api_key');

            $.ajax({
                url: 'https://api.conveythis.com/25/examination/pubkey/',
                method: 'POST',
                data: {'pub_key': apiKeyValue},
                success: function(response) {
                    let data = JSON.parse(JSON.stringify(response));

                    if (data.data.check !== false) {
                        validationLabel.style.display = 'none';
                        inputElements.classList.remove('validation-failed');

                        submitBlocked = false;
                        document.getElementById("submit").click();
                    } else {
                        validationLabel.style.display = 'block';
                        inputElements.classList.add('validation-failed');
                    }
                },
                error: function() {
                    alert('Server error, please contact support');
                }
            });
        } else {
            submitBlocked = true;
        }
    });


</script>