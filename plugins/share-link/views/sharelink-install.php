<div class="wrap sharelink sharelink-install">
    <h2> Welcome to Share Link! </h2>
    <div class="clear"></div>

    <p>This plugin will allow you to display share market related information form your Share Link account into your Wordpress website. If you do not already have a Share Link account you can sign up below.</p>
    <a target="_blank" class="button button-secondary button-hero install-now" href="<?php echo SHARELINK_PRICING_WEB_PAGE; ?>" data-name="Classic Editor" data-slug="classic-editor">Get A Share Link Account</a>

    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <input type="hidden" name="sharelink-license" />
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="default_category">License Key</label><p class="description"> <a id="how_to_find_license" href="#">How to find my license code?</a> </p></th>
                    <td>
                        <input type="text" name="license-key" size="30" value="<?php echo SharelinkOptions::getLicense(); ?>" />
                        <?php if (!SharelinkOptions::getLicenseIsActivated() && SharelinkOptions::getLicense()): ?>
                            <p class="description error" id="invalid-domain">Invalid: This key might expired or this domain was removed in your sharelink account configurations. <a href="<?php echo admin_url('admin.php?page=sharelink-error-403'); ?>">Check Domain</a></p>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="submit" value="Save Changes" class="button-primary" />
    </form>

    <div id="license_code" style="display: none">
        <p>Once you have an account you can enter your license code below to activate the account. You can find your license code <a target="_blank" href="<?php echo SHARELINK_APP_BASE_URL; ?>/login">by logging in</a> then going to Settings > Configuration from the Share Link menu.</p>
        <img src="<?php echo SHARELINK_URL; ?>/images/get-the-key.png" />
    </div>
</div>