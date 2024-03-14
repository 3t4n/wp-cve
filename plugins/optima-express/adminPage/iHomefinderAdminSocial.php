<?php

class iHomefinderAdminSocial extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function registerSettings() {
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_FACEBOOK_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_LINKEDIN_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_TWITTER_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_PINTEREST_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_INSTAGRAM_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_GOOGLE_PLUS_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_YOUTUBE_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::SOCIAL_YELP_URL_OPTION);
	}
	
	protected function getContent() {
		?>
		<style type="text/css">
			.ihf-title {
				padding-bottom: 0px !important;
			}
			.ihf-title h3 {
				margin: 0px !important;
			}
		</style>
		<h2>Social Widget Setup</h2>
		<p>Enter your social media addresses for the Optima Express Social Media Widget.</p>
		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_SOCIAL); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th class="ihf-title">
							<h3>Facebook</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_FACEBOOK_URL_OPTION; ?>">https://www.facebook.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_FACEBOOK_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_FACEBOOK_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_FACEBOOK_URL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th class="ihf-title">
							<h3>LinkedIn</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_LINKEDIN_URL_OPTION; ?>">https://www.linkedin.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_LINKEDIN_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_LINKEDIN_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_LINKEDIN_URL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th class="ihf-title">
							<h3>Twitter</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_TWITTER_URL_OPTION; ?>">https://www.twitter.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_TWITTER_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_TWITTER_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_TWITTER_URL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th class="ihf-title">
							<h3>Pinterest</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_PINTEREST_URL_OPTION; ?>">https://www.pinterest.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_PINTEREST_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_PINTEREST_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_PINTEREST_URL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th class="ihf-title">
							<h3>Instagram</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_INSTAGRAM_URL_OPTION; ?>">https://instagram.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_INSTAGRAM_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_INSTAGRAM_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_INSTAGRAM_URL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th class="ihf-title">
							<h3>Google+</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_GOOGLE_PLUS_URL_OPTION; ?>">https://plus.google.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_GOOGLE_PLUS_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_GOOGLE_PLUS_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_GOOGLE_PLUS_URL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th class="ihf-title">
							<h3>YouTube</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_YOUTUBE_URL_OPTION; ?>">https://www.youtube.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_YOUTUBE_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_YOUTUBE_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_YOUTUBE_URL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th class="ihf-title">
							<h3>Yelp</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SOCIAL_YELP_URL_OPTION; ?>">https://www.yelp.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::SOCIAL_YELP_URL_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::SOCIAL_YELP_URL_OPTION; ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::SOCIAL_YELP_URL_OPTION, null)) ?>" />
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
		</form>
		<?php
	}
	
}