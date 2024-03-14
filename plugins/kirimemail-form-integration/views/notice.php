<?php if ($type == 'plugins-woocommerce') : ?>
    <div id="message" class="notice-warning notice is-dismissible">
        <p>Kirim.Email WooCommerce Integration only active when there is WooCommerce Plugins.</p>
        <p><?php is_plugin_active(); ?></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
<?php elseif ($type == 'plugins') : ?>
    <div id="message" class="notice-warning notice is-dismissible">
        <p>Please configure your Kirim.Email username and api token. Get your token <a
                href="<?php echo esc_attr(KIRIMEMAIL_APP_URL . 'account/tokenconfig'); ?>">here</a>. </p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
<?php else: ?>

<?php endif; ?>
