<?php if ( ! defined( 'WPINC' ) )  die;
/**
 * FlowFlow.
 *
 * @var array $context
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
$options = $context['options'];
$auth = $context['auth_options'];
$auth['facebook_access_token'] = isset($auth['facebook_access_token']) ? $auth['facebook_access_token'] : '';

$fb_own_app = flow\settings\FFSettingsUtils::YepNope2ClassicStyleSafe($auth, 'facebook_use_own_app', false);
//$facebook_long_life_token = $context['facebook_long_life_token'];
?>
<div class="section-content" data-tab="auth-tab">
    <div class="section" id="auth-settings">
        <div class="ff-notice">Starting from Flow-Flow 4.6 in order to access Facebook API and Instagram API by Facebook you need to obtain new Facebook token via our approved app. Please use Connect button below or Refresh token if you have added token previously. Check detailed guide <a target="_blank" href="https://docs.social-streams.com/article/46-authenticate-with-facebook">here</a>.</div>
        <h1 class="desc-following" style="min-height: 38px"><span style="vertical-align: middle;line-height: 38px;">Facebook and Instagram integration</span> <span id="facebook-auth" class='admin-button auth-button blue-button'>Connect</span></h1>
        <p class="desc">Single login to access Facebook and Instagram. <a target="_blank" href="http://docs.social-streams.com/article/46-authenticate-with-facebook">More info</a></p>
        <dl class="section-settings">
            <dt class="ff-toggler ff-fb-own-app" <?php echo $fb_own_app ? '' : 'style="display:none"' ?>>Use own app <p class="desc">Deprecated, please get token via our app</p></dt>
            <dd class="ff-toggler ff-fb-own-app" <?php echo $fb_own_app ? '' : 'style="display:none"' ?>>
                <label><input class="clearcache switcher" <?php echo $fb_own_app ? 'checked' : ''?> type="checkbox" id="facebook_use_own_app" name="flow_flow_fb_auth_options[facebook_use_own_app]" value="yep"/><div><div></div></div></label>
            </dd>
            <dt class="vert-aligned">Access Token</dt>
            <dd>
                <input class="clearcache" type="text" id="facebook_access_token" name="flow_flow_fb_auth_options[facebook_access_token]" placeholder="Acquired from Facebook" value="<?php echo $auth['facebook_access_token']?>"/><a <?php echo $fb_own_app ? 'style="display:none"' : '' ?> class="ff-pseudo-link" href="#" id="fb-refresh-token">Refresh token</a>
			    <?php
			    $extended = $context['extended_facebook_access_token'];
			    if(!empty($auth['facebook_access_token']) && !empty($extended) ) {
				    //if ($auth['facebook_access_token'] != $extended)
					    echo '<p class="desc" style="margin: 30px 0 5px">Generated long-life token</p><textarea disabled rows=3>' . $extended . '</textarea>';
			    } else {
				    if (empty($extended)) {
					    echo '<p class="desc fb-token-notice" style="margin: 10px 0 5px; color: red !important">! Extended token is not generated, Facebook feeds might not work</p>';
				    }
			    }
			    ?>
            </dd>
            <dt class="vert-aligned own-app-input">APP ID</dt>
            <dd class="own-app-input">
                <input class="clearcache" type="text" name="flow_flow_fb_auth_options[facebook_app_id]" placeholder="Copy and paste from Facebook" value="<?php echo isset($auth['facebook_app_id']) ? $auth['facebook_app_id'] : ''?>"/>
            </dd>
            <dt class="vert-aligned own-app-input">APP Secret</dt>
            <dd class="own-app-input">
                <input class="clearcache" type="text" name="flow_flow_fb_auth_options[facebook_app_secret]" placeholder="Copy and paste from Facebook" value="<?php echo isset($auth['facebook_app_secret']) ? $auth['facebook_app_secret'] : ''?>"/>
            </dd>
        </dl>
        <p class="button-wrapper"><span id="fb-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>

        <h1 class="desc-following">Twitter integration</h1>
        <p class="desc"><a target="_blank" href="http://docs.social-streams.com/article/48-authenticate-with-twitter">Setup guide</a></p>
        <dl class="section-settings">
            <dt class="vert-aligned">Consumer Key (API Key)</dt>
            <dd>
                <input class="clearcache" type="text" name="flow_flow_options[consumer_key]" placeholder="Copy and paste from Twitter" value="<?php echo isset($options['consumer_key']) ? $options['consumer_key'] : ''?>"/>
            </dd>
            <dt class="vert-aligned">Consumer Secret (API Secret)</dt>
            <dd>
                <input class="clearcache" type="text" name="flow_flow_options[consumer_secret]" placeholder="Copy and paste from Twitter" value="<?php echo isset($options['consumer_secret']) ? $options['consumer_secret'] : ''?>"/>
            </dd>
            <dt class="vert-aligned">Access Token</dt>
            <dd>
                <input class="clearcache" id="oauth_access_token" type="text" name="flow_flow_options[oauth_access_token]" placeholder="Copy and paste from Twitter" value="<?php echo isset($options['oauth_access_token']) ? $options['oauth_access_token'] : ''?>"/>
            </dd>
            <dt class="vert-aligned">Access Token Secret</dt>
            <dd>
                <input class="clearcache" type="text" name="flow_flow_options[oauth_access_token_secret]" placeholder="Copy and paste from Twitter" value="<?php echo isset($options['oauth_access_token_secret']) ? $options['oauth_access_token_secret'] : ''?>"/>						</dd>

        </dl>
        <p class="button-wrapper"><span id="tw-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>

        <h1 class="desc-following">Google & YouTube integration</h1>
        <p class="desc"><a target="_blank" href="http://docs.social-streams.com/article/49-authenticate-with-google-and-youtube">Setup guide</a></p>
        <dl class="section-settings">
            <dt class="vert-aligned">API key</dt>
            <dd>
                <input class="clearcache" type="text" id="google_api_key" name="flow_flow_options[google_api_key]" placeholder="Copy and paste from Google+" value="<?php echo isset($options['google_api_key']) ? $options['google_api_key'] : ''?>"/>
            </dd>
        </dl>
        <p class="button-wrapper"><span id="gp-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>


        <h1 class="desc-following">Foursquare integration  <span id="foursquare-auth" class='admin-button auth-button blue-button'>Authorize</span></h1>
        <p class="desc"><a target="_blank" href="http://docs.social-streams.com/article/54-authenticate-with-foursquare">Setup guide</a></p>
        <dl class="section-settings">
            <dt class="vert-aligned">Access Token</dt>
            <dd>
                <input class="clearcache" type="text" id="foursquare_access_token" name="flow_flow_options[foursquare_access_token]" placeholder="Copy and paste from Foursquare" value="<?php echo isset($options['foursquare_access_token']) ? $options['foursquare_access_token'] : '';?>"/>
            </dd>
            <dt class="vert-aligned">Client ID</dt>
            <dd>
                <input class="clearcache" id="foursquare_client_id" type="text" name="flow_flow_options[foursquare_client_id]" placeholder="Copy and paste from Foursquare" value="<?php echo isset($options['foursquare_client_id']) ? $options['foursquare_client_id'] : ''?>"/>
            </dd>
            <dt class="vert-aligned">Client Secret</dt>
            <dd>
                <input class="clearcache" id="foursquare_client_secret" type="text" name="flow_flow_options[foursquare_client_secret]" placeholder="Copy and paste from Foursquare" value="<?php echo isset($options['foursquare_client_secret']) ? $options['foursquare_client_secret'] : ''?>"/>
            </dd>
        </dl>
        <p class="button-wrapper"><span id="fq-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>

        <h1 class="desc-following">LinkedIn integration</h1>
        <p class="desc"><a target="_blank" href="http://docs.social-streams.com/article/53-authenticate-with-linkedin">Setup guide</a></p>

        <dl class="section-settings">
            <dt class="vert-aligned">Client ID</dt>
            <dd>
                <input class="clearcache" type="text" id="linkedin_api_key" name="flow_flow_options[linkedin_api_key]" placeholder="Copy and paste from LinkedIn" value="<?php echo isset($options['linkedin_api_key']) ? $options['linkedin_api_key'] : ''?>"/>
            </dd>
            <dt class="vert-aligned">Client Secret</dt>
            <dd>
                <input class="clearcache" type="text" id="linkedin_secret_key" name="flow_flow_options[linkedin_secret_key]" placeholder="Copy and paste from LinkedIn" value="<?php echo isset($options['linkedin_secret_key']) ? $options['linkedin_secret_key'] : ''?>"/>
            </dd>
            <dt class="vert-aligned">Access token</dt>
            <dd>
                <input class="clearcache" type="text" name="flow_flow_options[linkedin_access_token]" placeholder="Copy and paste from LinkedIn" value="<?php echo isset($options['linkedin_access_token']) ? $options['linkedin_access_token'] : '' ?>"/>
            </dd>
        </dl>
        <p class="button-wrapper"><span id="linkedin-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>

        <h1 class="desc-following">SoundCloud integration</h1>
        <p class="desc"><a target="_blank" href="http://soundcloud.com/you/apps/new">Create SoundCloud app</a> and paste its ID below.</p>


        <dl class="section-settings">
            <dt class="vert-aligned">Your app Client ID</dt>
            <dd>
                <input class="clearcache" type="text" name="flow_flow_options[soundcloud_api_key]" placeholder="Copy and paste from SoundCloud" value="<?php echo isset($options['soundcloud_api_key']) ? $options['soundcloud_api_key'] : ''?>"/>
            </dd>
        </dl>

        <p class="button-wrapper"><span id="sc-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>

        <h1 class="desc-following">Dribbble integration</h1>
        <p class="desc"><a target="_blank" href="http://developer.dribbble.com">Create Dribbble app</a> and paste its access token below.</p>
        <dl class="section-settings">
            <dt class="vert-aligned">Client Access Token</dt>
            <dd>
                <input class="clearcache" type="text" name="flow_flow_options[dribbble_access_token]" placeholder="Copy and paste from Dribbble" value="<?php echo isset($options['dribbble_access_token']) ? $options['dribbble_access_token'] : ''?>"/>
            </dd>
        </dl>
        <p class="button-wrapper"><span id="dribbble-auth-settings-sbmt" class='admin-button green-button submit-button'>Save Changes</span></p>
    </div>
	<?php
		/** @noinspection PhpIncludeInspection */
		include($context['root']  . 'views/footer.php');
	?>
</div>