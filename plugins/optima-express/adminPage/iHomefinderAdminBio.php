<?php

class iHomefinderAdminBio extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function registerSettings() {
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_PHOTO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::OFFICE_LOGO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_TEXT_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_LICENSE_INFO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_DESIGNATIONS_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::CONTACT_PHONE_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::CONTACT_EMAIL_OPTION);
	}
	
	protected function getContent() {
		?>
		<h2>Bio Widget Setup</h2>
		<p>Configure and edit the Optima Express Bio Widget display here.</p>
		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_BIO); ?>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
			<h3>Agent Photo</h3>
			<?php if(get_option(iHomefinderConstants::AGENT_PHOTO_OPTION, null)) { ?>
				<img
					id="ihf_upload_agent_photo_image"
					src="<?php echo get_option(iHomefinderConstants::AGENT_PHOTO_OPTION, null) ?>"
					<?php if(!get_option(iHomefinderConstants::AGENT_PHOTO_OPTION, null)) { ?>
						style="display:none;"
					<?php } ?>
				/>
				<br />
			<?php } ?>
			<input id="ihf_upload_agent_photo" class="regular-text" type="text" name="<?php echo iHomefinderConstants::AGENT_PHOTO_OPTION ?>" value="<?php echo get_option(iHomefinderConstants::AGENT_PHOTO_OPTION, null) ?>" />
			<button id="ihf_upload_agent_photo_button" class="button-secondary" type="button">Upload Agent Photo</button>
			<p>Enter an image URL or use an image from the Media Library</p>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION ?>">Display Name</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::CONTACT_PHONE_OPTION ?>">Contact Phone</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::CONTACT_PHONE_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::CONTACT_PHONE_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::CONTACT_PHONE_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::CONTACT_EMAIL_OPTION ?>">Contact Email</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::CONTACT_EMAIL_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::CONTACT_EMAIL_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::CONTACT_EMAIL_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::AGENT_DESIGNATIONS_OPTION ?>">Designations</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::AGENT_DESIGNATIONS_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::AGENT_DESIGNATIONS_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::AGENT_DESIGNATIONS_OPTION, null)) ?>" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::AGENT_LICENSE_INFO_OPTION ?>">License Info</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::AGENT_LICENSE_INFO_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::AGENT_LICENSE_INFO_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::AGENT_LICENSE_INFO_OPTION, null)) ?>" />
						</td>
					</tr>
				</tbody>
			</table>
			<br />
			<br />
			<h3>Agent Bio Text</h3>
			<?php
			$agent_bio_editor_settings = array (
				"textarea_rows" => 15,
				"media_buttons" => true,
				"teeny" => true,
				"tinymce" => true,
				"textarea_name" => iHomefinderConstants::AGENT_TEXT_OPTION
			);
			wp_editor(get_option(iHomefinderConstants::AGENT_TEXT_OPTION), "agentbiotextid", $agent_bio_editor_settings);
			?>
			<br />
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
		</form>
		<?php
	}
	
}