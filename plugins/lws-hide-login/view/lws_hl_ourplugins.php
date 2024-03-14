<div class="lws_hl_div_title_plugins">
    <h3 class="lws_hl_title_plugins"> <?php esc_html_e('Discover our plugins', 'lws-hide-login'); ?>
    </h3>
    <p class="lws_hl_text_base">
        <?php esc_html_e('At LWS, we have developped several plugins for WordPress that you may find below. Click on the "Install" button to get those freely.', 'lws-hide-login'); ?>
    </p>
</div>

<div>
    <?php foreach ($plugins as $slug => $plugin) : ?>
    <div class="lws_hl_block_plugin_in_page">
        <div class="lws_hl_text_plugin_left">
            <h3 class="lws_hl_title_plugin">
                <img style="vertical-align:top; margin-right:5px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/plugin_' . $slug . '.svg')?>"
                    alt="" width="30px" height="30px">
                <?php echo esc_html($plugin[0]);?>
                <?php if ($plugin[2]) : ?>
                <span class="lws_hl_recommended"> <?php esc_html_e('recommended', 'lws-hide-login');?></span>
                <?php endif ?>
            </h3>
            <p class="lws_hl_text_plugin">
                <?php echo wp_kses($plugin[1], array('strong' => array())); ?>
            </p>
        </div>

        <button class="lws_hl_button_ad_block lws_hl_plugin_button_right" onclick="install_plugin(this)"
            id="<?php echo esc_attr('bis_' . $slug); ?>"
            value="<?php echo esc_attr($slug); ?>">
            <span>
                <img style="vertical-align:sub; margin-right:5px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/securise.svg')?>"
                    alt="" width="20px" height="19px">
                <span class="lws_hl_button_text"><?php esc_html_e('Install', 'lws-hide-login'); ?></span>
            </span>
            <span class="hidden" name="loading" style="padding-left:5px">
                <img style="vertical-align:sub; margin-right:5px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/loading.svg')?>"
                    alt="" width="18px" height="18px">
            </span>
            <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-hide-login'); ?></span>
            <span class="hidden" name="validated">
                <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/check_blanc.svg')?>">
                <?php esc_html_e('Activated', 'lws-hide-login'); ?>
            </span>
            <span class="hidden" name="failed">
                <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/croix_blanche.svg')?>">
                <?php esc_html_e('Failed', 'lws-hide-login'); ?>
            </span>
        </button>
    </div>
    <?php endforeach ?>
</div>