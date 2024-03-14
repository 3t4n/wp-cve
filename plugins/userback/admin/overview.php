<?php $nonce = wp_create_nonce('userback_plugin_settings_update'); ?>
<div class="userback-container">
    <h2>Userback</h2>
    <div class="setting-title">Settings</div>
    <form class="setting">
        <div class="setting-row" data-type="all">
            <div class="setting-label">Enable Userback</div>
            <div class="setting-value"><input type="checkbox" name="rp-is-active"></div>
        </div>
        <div class="setting-row" data-type="all">
            <div class="setting-label">Role</div>
            <div class="setting-value">
                <select name="rp-role" size="6" multiple>
                    <option value="0">Everyone (do not require login)</option>
                    <?php wp_dropdown_roles(); ?>
                </select>
            </div>
        </div>
        <div class="setting-row" data-type="all">
            <div class="setting-label">Page:</div>
            <div class="setting-value">
                <select name="rp-page" size="10" multiple>
                    <option value="0">All Pages and Blog Posts</option>
                    <option value="-1">All Pages and Blog Posts (Draft and Pending Review only)</option>
                    <option value="-2">All Pages</option>
                    <option value="-3">All Pages (Draft and Pending Review only)</option>
                    <option value="-4">All Blog Posts</option>
                    <option value="-5">All Blog Posts (Draft and Pending Review only)</option>
                </select>
            </div>
        </div>
        <div class="setting-row" data-type="all">
            <div class="setting-label">Access Token:</div>
            <div class="setting-value">
                <div>
                    <input type="text" name="rp-access-token" rows="15" spellcheck="false" required>
                </div>
                <div class="get-your-token">
                    <a href="https://app.userback.io/dashboard/?get_code=1" target="_blank">Get your access token here</a>
                </div>

                <div><b>Note</b>: The highlighted area is your access token.</div>
                <p><img class="code-example" src="<?php print plugins_url(PLUGIN_DIR_USERBACK . '/assets/code_sample.png'); ?>"></p>
            </div>
        </div>
        <br>
        <input type="hidden" name="userback_plugin_nonce" value="<?php print esc_attr($nonce); ?>">
        <button class="button button-primary" id="save">Save</button>
    </form>
</div>
