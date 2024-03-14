<div class="lws_hl_mainpage">
    
    <h3 class="lws_hl_titre" style="margin-top:0px"><?php esc_html_e("How does LWS Hide Login works?", "lws-hide-login") ?></h3>
    <p class="lws_hl_text_p">            
        <?php echo wp_kses(__("This plugin allows you to <strong>hide your dashboard as well as the login page to non-registered users.</strong> ", "lws-hide-login"), array('strong' => array())); ?>
        <?php esc_html_e("Those changes make your website more secure against hacking and help prevent unauthorized access.", "lws-hide-login");?>
    </p>

    <p class="lws_hl_text_p">            
        <?php esc_html_e("By default, the login page is not modified but you can modify it. If you leave the field empty, the redirection will be deactivated. For the 404 page, shown when accessing the administration page or login page without being connected or with the wrong URL, leaving it empty will redirect to the home page. Change it by the page of your choice.", "lws-hide-login");?>
    </p>

    <div id="lws_hl_banner"></div>

    <h3 class="lws_hl_titre"><?php esc_html_e("Why should I secure my website?", "lws-hide-login"); ?></h3>
    <p class="lws_hl_text_p">
        <?php esc_html_e("Securing your website with our plugin make it harder for hackers and malicious people to access confidential or private data.", "lws-hide-login");?>
        <?php esc_html_e("It is not only protecting your data but also the data of everyone accessing your website. It makes it harder to find how to log in, discouraging lots of people from actually trying to hack you.", "lws-hide-login");?>
    </p>
    
    <p class="lws_hl_text_p">
        <?php esc_html_e("Of course, it is not perfect, if someone truly want yo hack you, they will try everything.", "lws-hide-login");?>
        <?php esc_html_e("That is why you need to take the security of your website with seriousness.", "lws-hide-login");?>
    </p>

    
    <?php if (is_multisite() && !is_network_admin() ) : ?>
        <div class="form_update_success notice">
        <?php if (current_user_can( 'setup_network' )) : ?>
            <?php esc_html_e('To change the redirections, please go on this plugin\'s page in your Network Settings.', 'lws-hide-login'); ?>
        <?php else : ?>
            <?php esc_html_e('Please ask your Network Administrator to change the redirections for you.', 'lws-hide-login'); ?>
        <?php endif ?>
        </div>
    <?php endif ?>
    
    <?php if (isset($form_updated)) : ?>        
        <div class="form_update_success notice is-dismissible">
            <?php echo esc_html($form_updated); ?>
        </div>
    <?php endif ?>

    <div class="lws_hl_formbloc">
        <fieldset class="lws_hl_fieldset_config" id="lws_hl_form_fieldset_config">
            <form method="POST">
                <?php wp_nonce_field( 'lws_hide_login_nonce_form_config_param', 'lws_hide_login_form_config_param_nonce_hide_admin' ); ?>
                <div class="lws_hl_form_fields">
                    <div class="lws_hl_field">
                        <h3 class="lws_hl_titre lws_hl_titre_inputs"> <?php esc_html_e("Dashboard redirection", "lws-hide-login"); ?></h3>
                        <div id="lws_hl_input_change_redirection">
                            <span class="website_url_span"><?php echo esc_url(get_site_url() . "/"); ?></span> 
                            <span class="lws_hl_input_block">
                                <input class="lws_hl_input_url" type="text" 
                                value="<?php if (!is_multisite() || !is_network_admin() ){
                                     echo esc_html(get_option('lws_aff_new_redirection') ? get_option('lws_aff_new_redirection') : "404");
                                 }else{
                                    echo esc_html(get_site_option('lws_aff_new_redirection') ? get_site_option('lws_aff_new_redirection') : "404");
                                 } ?>" 
                                name="input_change_redirection" id="input_change_redirection">
                                <input class="lws_hl_button_update_redirect" name="lws_hl_form_change_404" type="submit" id="lws_hl_form_change_404" value="<?php esc_html_e('Modify redirection', "lws-hide-login"); ?>">
                            </span>
                        </div>  
                    </div>
                    <div class="lws_hl_field">
                        <h3 class="lws_hl_titre lws_hl_titre_inputs"> <?php esc_html_e("New login address", "lws-hide-login"); ?></h3>
                        <div id="lws_hl_input_change_login">
                            <span class="website_url_span"><?php echo esc_url(get_site_url() . "/"); ?></span> 
                            <span class="lws_hl_input_block">
                                <input class="lws_hl_input_url" type="text" 
                                value="<?php if (!is_multisite() || !is_network_admin() ){
                                     echo esc_html(get_option('lws_aff_new_login') ? get_option('lws_aff_new_login') : "");
                                 }else{
                                    echo esc_html(get_site_option('lws_aff_new_login') ? get_site_option('lws_aff_new_login') : "");
                                 } ?>"                 
                                 name="input_change_login" id="input_change_login">
                                <input class="lws_hl_button_update_redirect" name="lws_hl_form_change_redirect" type="submit" id="lws_hl_form_change_redirect" value="<?php esc_html_e('Modify URL', "lws-hide-login"); ?>">
                            </span>
                        </div>
                    </div>
                </div>                        
            </form>
        </fieldset>
    </div>
</div>

<script>
    jQuery("#input_change_login").on({
        keydown: function(e) {
            if (e.which === 32)
            return false;
        },
        change: function() {
            this.value = this.value.replace(/\s/g, "");
        }
    });

    jQuery("#input_change_redirection").on({
        keydown: function(e) {
            if (e.which === 32)
            return false;
        },
        change: function() {
            this.value = this.value.replace(/\s/g, "");
        }
    });
</script>

<?php if (is_multisite() && !is_network_admin()) : ?>
    <script>
        jQuery('#lws_hl_form_fieldset_config').prop('disabled', true);
    </script>
<?php endif ?>
