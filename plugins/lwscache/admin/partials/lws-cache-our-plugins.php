<div class="lwscache_div_title_plugins">
    <h3 class="lwscache_title_plugins"> <?php esc_html_e('Discover our plugins', 'lwscache'); ?>
    </h3>
    <p class="lwscache_text_base">
        <?php esc_html_e('At LWS, we have developped several plugins for WordPress that you may find below. Click on the "Install" button to get those freely.', 'lwscache'); ?>
    </p>
</div>

<div>
    <?php foreach ($plugins as $slug => $plugin) : ?>
    <div class="lwscache_block_plugin_in_page">
        <div class="lwscache_text_plugin_left">
            <h3 class="lwscache_title_plugin">
                <img style="vertical-align:top; margin-right:5px"
                    src="<?php echo esc_url(plugins_url('icons/plugin_' . $slug . '.svg', __DIR__))?>"
                    alt="" width="30px" height="30px">
                <?php echo esc_html($plugin[0]);?>
                <?php if ($plugin[2]) : ?>
                <span class="lwscache_recommended"> <?php esc_html_e('recommended', 'lwscache');?></span>
                <?php endif ?>
            </h3>
            <p class="lwscache_text_plugin">
                <?php echo wp_kses($plugin[1], array('strong' => array())); ?>
            </p>
        </div>

        <button class="lwscache_button_ad_block lwscache_plugin_button_right" onclick="install_plugin(this)"
            id="<?php echo esc_attr('bis_' . $slug); ?>"
            value="<?php echo esc_attr($slug); ?>">
            <span>
                <img style="vertical-align:sub; margin-right:5px"
                    src="<?php echo esc_url(plugins_url('icons/securise.svg', __DIR__))?>"
                    alt="" width="20px" height="19px">
                <span class="lwscache_button_text"><?php esc_html_e('Install', 'lwscache'); ?></span>
            </span>
            <span class="hidden" name="loading" style="padding-left:5px">
                <img style="vertical-align:sub; margin-right:5px"
                    src="<?php echo esc_url(plugins_url('icons/loading.svg', __DIR__))?>"
                    alt="" width="18px" height="18px">
            </span>
            <span class="hidden" name="activate"><?php echo esc_html_e('Activate', 'lwscache'); ?></span>
            <span class="hidden" name="validated">
                <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                    src="<?php echo esc_url(plugins_url('icons/check_blanc.svg', __DIR__))?>">
                <?php esc_html_e('Activated', 'lwscache'); ?>
            </span>
            <span class="hidden" name="failed">
                <img style="vertical-align:sub; margin-right:5px" width="18px" height="18px"
                    src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'icons/croix_blanche.svg')?>">
                <?php esc_html_e('Failed', 'lwscache'); ?>
            </span>
        </button>
    </div>
    <?php endforeach ?>
</div>