<!-- TEMPLATE FOR OUR PLUGINS -->
<!-- Here,too, need to change URLs and slugs -->

<div class="lws_tk_div_title_plugins">
    <h3 class="lws_tk_title_plugins"> <?php esc_html_e('Discover our plugins', 'lws-tools'); ?>
    </h3>
    <p class="lws_tk_text_base">
        <?php esc_html_e('At LWS, we have developped several plugins for WordPress that you may find below. Click on the "Install" button to get those freely.', 'lws-tools'); ?>
    </p>
</div>

<div>
    <?php foreach ($plugins as $slug => $plugin) : ?>
    <div class="lws_tk_block_plugin_in_page">
        <div class="lws_tk_text_plugin_left">
            <h3 class="lws_tk_title_plugin">
                <img style="vertical-align:top; margin-right:5px"
                    src="<?php echo esc_url(plugins_url('images/plugin_' . $slug . '.svg', __DIR__))?>"
                    alt="" width="30px" height="30px">
                <?php echo esc_html($plugin[0]);?>
                <?php if ($plugin[2]) : ?>
                <span class="lws_tk_recommended"> <?php esc_html_e('recommended', 'lws-tools');?></span>
                <?php endif ?>
            </h3>
            <p class="lws_tk_text_plugin">
                <?php echo wp_kses($plugin[1], array('strong' => array())); ?>
            </p>
        </div>

        <button class="lws_tk_button_ad_block lws_tk_plugin_button_right" onclick="install_plugin(this)"
            id="<?php echo esc_attr('bis_' . $slug); ?>"
            value="<?php echo esc_attr($slug); ?>">
            <span>
                <img style="vertical-align:sub; margin-right:5px"
                    src="<?php echo esc_url(plugins_url('images/securise.svg', __DIR__))?>"
                    alt="" width="20px" height="19px">
                <span class="lws_tk_button_text"><?php esc_html_e('Install', 'lws-tools'); ?></span>
            </span>
            <span class="hidden" name="loading" style="padding-left:5px">
                <img style="vertical-align:sub; margin-right:5px"
                    src="<?php echo esc_url(plugins_url('images/loading.svg', __DIR__))?>"
                    alt="" width="18px" height="18px">
            </span>
            <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lws-tools'); ?></span>
            <span class="hidden" name="validated">
                <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                    src="<?php echo esc_url(plugins_url('images/check_blanc.svg', __DIR__))?>">
                <?php esc_html_e('Activated', 'lws-tools'); ?>
            </span>
        </button>
    </div>
    <?php endforeach ?>
</div>

<!-- END -->