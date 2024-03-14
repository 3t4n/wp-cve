<?php
$has_wc = spoki_has_woocommerce();
$is_current_tab = $GLOBALS['current_tab'] == 'invite-a-friend';
$account_info = $this->options['account_info'] ?? ['plan' => []];
$is_credit_based = isset($account_info['plan']['is_credit_based']) && $account_info['plan']['is_credit_based'] == true;

if ($is_current_tab) : ?>
    <h2>
		<?php _e('Invite a friend', "spoki") ?>
    </h2>
    <p>
		<?php _e('Earn <b>100 contacts for every friend</b> for FREE!', "spoki") ?>
    </p>
    <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/bring-a-friend.png' ?>"/>
    <ol style="font-size: 0.9rem; line-height: 1.4;">
        <li><?php _e('Tell your friend to download and activate the Spoki Plugin', "spoki") ?></li>
        <li><?php _e('Make sure your friend has entered the phone number in his Spoki Plugin', "spoki") ?></li>
        <li><?php _e('Submit the friend\'s website url through the form below', "spoki") ?></li>
        <li><?php _e('<b>You</b> will receive <b>100 contacts within 24h</b>', "spoki") ?> 🎉</li>
        <li><?php _e('Your <b>friend</b> will receive <b>100 contacts within 24h</b>', "spoki") ?> 🎉</li>
        <li><?php _e('Invite as many friends as you want!', "spoki") ?></li>
    </ol>
    <p>
        * <?php _e("Contacts will be available for the current period", "spoki") ?>
    </p>
    <br/>
    <h3><?php _e('Notify us the friend you invited', "spoki") ?></h3>
    <ul>
        <li>- <?php _e('<b>You</b> will receive <b>100 contacts within 24h</b>', "spoki") ?></li>
        <li>- <?php _e('Your <b>friend</b> will receive <b>100 contacts within 24h</b>', "spoki") ?></li>
    </ul>
    <label for="friend-url"><?php _e('Friend\'s Website URL', "spoki") ?></label>
    <br/>
    <input id="friend-url" class="regular-text" type="text" placeholder="https://myfriendwebsite.com"/>
    </br></br>
    <button id="invite-friend-btn" type="button" class="button button-primary">
		<?php _e('Submit', "spoki") ?>
    </button>
    <script>
        document.getElementById('invite-friend-btn').addEventListener('click', function () {
            const url = document.getElementById('friend-url').value.trim();
            if (!url.length) {
                alert('<?php _e("insert a valid url", "spoki") ?>');
                return;
            }
            const message = `<?php /* translators: %1$s: Domain.*/printf(__('Hi Spoki, my website is %1$s. I invited a friend with the website follow: ', "spoki"), get_bloginfo('url') ) ?>`.trim() + url;
            window.open('https://api.whatsapp.com/send/?phone=393666989618&text=' + encodeURI(message))
        });
    </script>
<?php elseif ($has_wc && !$is_credit_based && $GLOBALS['current_tab'] == 'welcome'): ?>
    <div class="notice notifications-status-message bg-spoki-secondary" style="border-color: #b185e8">
        <p style="font-weight: normal"><?php _e("Earn 100＋ available contacts for FREE", "spoki") ?>:<a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=invite-a-friend"><?php _e("invite a friend", "spoki") ?></a></p>
    </div>
<?php endif; ?>