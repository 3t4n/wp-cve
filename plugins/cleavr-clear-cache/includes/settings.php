<?php if (!defined('ABSPATH')) {
    exit;
} ?>


<div class="wrap">
    <h1>Cleavr</h1>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php settings_fields('cleavr'); ?>

        <table class="form-table">
            <tr>
                <td>
                    <h3>
                        Clear Cache Trigger Hook
                    </h3>
                    <input type="text" class="regular-text code" name="cleavr_nginx_cache_hook" autocomplete="off" placeholder="https://app.cleavr.io/hooks/flush/..."
                           value="<?php echo esc_attr(get_option('cleavr_nginx_cache_hook')); ?>"/>
                    <p class="description">The <a href="https://docs.cleavr.io/nginx-cache#clear-cache-trigger-hook"
                                                  target="_blank">clear cache trigger hook</a> from your Cleavr site.
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <label for="cleavr_auto_clear_cache">
                        <input name="cleavr_auto_clear_cache" type="checkbox" id="cleavr_auto_clear_cache"
                               value="1" <?php checked(get_option('cleavr_auto_clear_cache'), '1'); ?> />
                        Automatically clear the cache on content changes
                    </label>
                </td>
            </tr>
        </table>

        <p class="submit">
            <?php echo get_submit_button(null, 'primary large', 'submit', false); ?>
            &nbsp;
            <a href="<?php echo wp_nonce_url(admin_url(add_query_arg('action', 'clear-nginx-cache', $this->admin_page)), 'clear-nginx-cache'); ?>"
               class="button button-secondary button-large delete<?php if (is_wp_error($this->cleavrcc_is_valid_hook())) : ?> disabled<?php endif; ?>">Clear
                Cache</a>
        </p>
    </form>
</div>
