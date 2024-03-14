<form method="POST">
<?php wp_nonce_field( 'lws_tk_security', 'nonce_security_listing_nonce' ); ?>
    <div class="lws_tk_opti_title">
        <h2 class="lws_tk_opti_title_text"> <?php esc_html_e('Security', 'lws-tools'); ?>
        </h2>
        <button class="lws_tk_button_opti_save" type="submit" name="lws_tk_security" id="lws_tk_security">
            <img style="vertical-align:sub; margin-right:5px"
                src="<?php echo esc_url(plugins_url('images/enregistrer.svg', __DIR__))?>"
                alt="LWS Cache" width="20px" height="20px">
            <img style="vertical-align:sub; margin-right:5px" class="hidden"
                src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                alt="LWS Cache" width="20px" height="20px">
            <span><?php esc_html_e('Apply modifications', 'lws-tools'); ?></span>
        </button>

    </div>
    <?php foreach ($secu_list as $key => $opti) : ?>
    <div class="lws_tk_optipage_bloc_table">
        <div class="lws_tk_optipage_table">
            <input type="checkbox" class="lws_tk_checkboxes"
                id="<?php echo esc_attr($key); ?>"
                name="lws_tk_security_list[]"
                value="<?php echo esc_attr($key); ?>" <?php echo get_option('lws_tk_' . $key) ? esc_attr('checked') : '';?>>
            <label for="<?php echo esc_attr($key); ?>">
                <?php echo esc_html($opti[0]); ?>
            </label>
            <?php if ($key === 'less_revision') : ?>
            <input style="width:120px"
                name="<?php echo esc_attr($key . "_revision_number"); ?>"
                type="number" min="1"
                value="<?php echo get_option('lws_tk_reduce_revisions_number') ? esc_attr((int)get_option('lws_tk_reduce_revisions_number')) : 1; ?>">
            <?php endif ?>
            <?php if ($opti[2]) : ?>
            <span class="lws_tk_recommended"> <?php esc_html_e('recommended', 'lws-tools');?></span>
            <?php endif ?>
        </div>
        <div class="lws_tk_optipage_table_text">
            <?php esc_html_e($opti[1]); ?>
        </div>
    </div>
    <?php endforeach ?>
    <?php if (isset($_SERVER['lwsapitoken']) && explode('/', getcwd())[1] == 'htdocs') :?>
    <div class="lws_tk_opti_title">
        <h2 class="lws_tk_opti_title_text"> <?php esc_html_e('WP Manager', 'lws-tools'); ?>
        </h2>
    </div>
    <?php foreach ($wp_manager as $key => $manager) : ?>
    <div class="lws_tk_optipage_bloc_table">
        <div class="lws_tk_optipage_table">
            <input type="checkbox" class="lws_tk_checkboxes"
                id="<?php echo esc_attr($key); ?>"
                name="lws_tk_wpmanager_list[]"
                value="<?php echo esc_attr($key); ?>" <?php echo $opts[$key] ? esc_attr('checked') : '';?>>
            <label for="<?php echo esc_attr($key); ?>">
                <?php echo esc_html($manager[0]); ?>
            </label>
        </div>
        <div class="lws_tk_optipage_table_text">
            <?php esc_html_e($manager[1]); ?>
        </div>
    </div>
    <?php endforeach ?>
    <?php endif?>

</form>

<script>
    jQuery('#lws_tk_security').on('click', function() {
        jQuery('#lws_tk_security').children()[1].classList.remove('hidden')
        jQuery('#lws_tk_security').children()[0].classList.add('hidden')
    });
</script>