<?php
$has_wc = spoki_has_woocommerce();
$is_current_tab = $GLOBALS['current_tab'] == 'news';

if ($is_current_tab) : ?>
    <div>
        <h2><?php _e('What\'s New in Spoki', "spoki") ?> <small>v<?php echo Spoki()->version ?></small></h2>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/news.png' ?>"/>
        <h3 style="font-weight: 400">
            <ul style="line-height: 1.5;">
                <li>• Easy connect your Spoki account to the plugin</li>
            </ul>
        </h3>
        <h4><?php _e('Other changes', "spoki") ?></h4>
        <ul>
            <li>• Improved performance</li>
        </ul>
        <br/>
        <a href="https://wordpress.org/plugins/spoki/#developers" target="_blank">
            <button type="button" class="button button-secondary">
				<?php _e('Complete feature list', "spoki") ?>
            </button>
        </a>
    </div>
<?php endif; ?>