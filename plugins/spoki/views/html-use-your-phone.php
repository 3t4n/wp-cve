<?php
$has_wc = spoki_has_woocommerce();
$is_current_tab = $GLOBALS['current_tab'] == 'use-your-phone';

if ($is_current_tab) : ?>
    <h2>
		<?php _e('Upgrade to <b>Spoki PRO</b>', "spoki") ?>
    </h2>
    <p>
		<?php _e('Start sending <b>custom notifications from your phone</b> number and much more!', "spoki") ?>
    </p>
    <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/spoki-update-to-pro.png' ?>"/>
    <ul>
        <li>• <?php _e('Send notifications from <b>your phone number</b>', "spoki") ?></li>
        <li>• <?php _e('<b>Customize messages</b>', "spoki") ?></li>
        <li>• <?php _e('Show <b>your company logo</b> and info associated to the number', "spoki") ?></li>
        <li>• <?php _e('Send automatic messages with <b>automations</b>', "spoki") ?></li>
        <li>• <?php _e('Send <b>marketing and promotional</b> messages in automations', "spoki") ?></li>
        <li>• <?php _e('<b>Chat</b> with your customers', "spoki") ?></li>
        <li>• <?php _e('Use the <b>Spoki WebApp Platform</b> where you want', "spoki") ?></li>
    </ul>
    <br/>

    <a href="<?php echo Spoki()->get_pro_plan_link() ?>" target="_blank">
        <button type="button" class="button button-primary bg-spoki" style="border: none; padding: 0.25rem 1rem">
			<?php _e('Go to pricing', "spoki") ?>
        </button>
    </a>
<?php endif; ?>