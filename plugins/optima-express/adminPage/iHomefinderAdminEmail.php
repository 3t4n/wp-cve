<?php

class iHomefinderAdminEmail extends iHomefinderAdminAbstractPage {
	
	//Only possible values for
	const EMAIL_DISPLAY_TYPE_DEFAULT_VALUE = "ihf-email-display-type-default";
	const EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE = "ihf-email-display-type-custom-images";
	const EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE = "ihf-email-display-type-custom-hi";
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function registerSettings() {
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_HEADER_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_FOOTER_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_PHOTO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_LOGO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_NAME_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_COMPANY_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_PHONE_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION);
	}
	
	protected function getContent() {
		$emailDisplayType = get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION, null);
		?>
		<h2>Email Branding</h2>
		<p>Add branding to the emails sent to leads by choosing an option below.</p>
		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY); ?>
			<?php if($this->includeDefaultDisplay()) { ?>
				Default Logo <?php echo $this->getDefaultLogo(); ?>
				<label>
					<input
						type="radio"
						name="<?php echo iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION ?>"
						<?php if(self::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE == $emailDisplayType) { ?>
							checked
						<?php } ?>
						value="<?php echo self::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE ?>"
					/>
					Use Agent Bio photo &amp; Header logo
				</label>
				<br />
			<?php } ?>
			<p>
				<label>
					<input
						type="radio"
						name="<?php echo iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION ?>"
						<?php if(self::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE == $emailDisplayType || empty($emailDisplayType)) { ?>
							checked
						<?php } ?>
						value="<?php echo self::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE ?>"
						onclick="jQuery('#basicBranding').show(); jQuery('#customBranding').hide();"
					/>
					Basic Branding
				</label>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<label>
					<input
						type="radio"
						name="<?php echo iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION ?>"
						<?php if(self::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE == $emailDisplayType) { ?>
							checked
						<?php } ?>
						value="<?php echo self::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE ?>"
						onclick="jQuery('#customBranding').show(); jQuery('#basicBranding').hide();"
					/>
					Custom HTML
				</label>
			</p>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
			<div
				id="basicBranding"
				style="
					<?php if(self::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE !== $emailDisplayType && $emailDisplayType !== null) { ?>
						display: none;
					<?php } ?>
				"
			>
				<p>Add the logo, photo and business information you would like displayed in your email branding.</p>
				<h3>Agent Photo</h3>
				<?php if(esc_js(get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null))) { ?>
					<img
						id="ihf_upload_agent_photo_image"
						src="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null)) ?>"
						<?php if(!esc_js(get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null))) { ?>
							style="display:none"
						<?php } ?>
					/>
					<br />
				<?php } ?>
				<input
					id="ihf_upload_agent_photo"
					class="regular-text"
					type="text"
					name="<?php echo iHomefinderConstants::EMAIL_PHOTO_OPTION ?>"
					value="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null)) ?>"
				/>
				<button id="ihf_upload_agent_photo_button" class="button-secondary" type="button">Upload Agent Photo</button>
				<p>Enter an image URL or use an image from the Media Library</p>
				<h3>Logo</h3>
				<?php if(esc_js(get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null))) { ?>
					<img
						id="ihf_upload_email_logo_image"
						src="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null)) ?>"
						<?php if(!esc_js(get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null))) { ?>
							style="display:none"
						<?php } ?>
					/>
					<br />
				<?php } ?>
				<input
					id="ihf_upload_email_logo"
					class="regular-text"
					type="text"
					name="<?php echo iHomefinderConstants::EMAIL_LOGO_OPTION ?>"
					value="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null)) ?>"
				/>
				<button id="ihf_upload_email_logo_button" class="button-secondary" type="button">Upload Logo</button>
				<p>Enter an image URL or use an image from the Media Library</p>
				<h3>Business Information</h3>
				<table class="form-table condensed">
					<tbody>
						<tr>
							<th>
								<label for="<?php echo iHomefinderConstants::EMAIL_NAME_OPTION ?>">Name</label>
							</th>
							<td>
								<input id="<?php echo iHomefinderConstants::EMAIL_NAME_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::EMAIL_NAME_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_NAME_OPTION, null)) ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="<?php echo iHomefinderConstants::EMAIL_COMPANY_OPTION ?>">Company</label>
							</th>
							<td>
								<input id="<?php echo iHomefinderConstants::EMAIL_COMPANY_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::EMAIL_COMPANY_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION, null)) ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="<?php echo iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION ?>">Address Line 1</label>
							</th>
							<td>
								<input id="<?php echo iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION, null)) ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="<?php echo iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION ?>">Address Line 2</label>
							</th>
							<td>
								<input id="<?php echo iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION, null)) ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="<?php echo iHomefinderConstants::EMAIL_PHONE_OPTION ?>" for="">Phone</label>
							</th>
							<td>
								<input id="<?php echo iHomefinderConstants::EMAIL_PHONE_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::EMAIL_PHONE_OPTION ?>" value="<?php echo esc_js(get_option(iHomefinderConstants::EMAIL_PHONE_OPTION, null)) ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div
				id="customBranding"
				style="
					<?php if(self::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE !== $emailDisplayType) { ?>
						display: none;
					<?php } ?>
				"
			>
				<p>Insert custom HTML for your email header and footer.</p>
				<h3>Email Header</h3>
				<?php
					$emailHeaderEditorSettings = array (
						"textarea_rows" => 15,
						"media_buttons" => true,
						"teeny" => true,
						"tinymce" => true,
						"textarea_name" => iHomefinderConstants::EMAIL_HEADER_OPTION
					);
					$emailHeaderContent = get_option(iHomefinderConstants::EMAIL_HEADER_OPTION, null);
					wp_editor($emailHeaderContent, "emailheaderid", $emailHeaderEditorSettings);
				?>
				<br />
				<h3>Email Footer</h3>
				<?php
					$emailFooterEditorSettings = array (
						"textarea_rows" => 15,
						"media_buttons" => true,
						"teeny" => true,
						"tinymce" => true,
						"textarea_name" => iHomefinderConstants::EMAIL_FOOTER_OPTION
					);
					$emailFooterContent = get_option(iHomefinderConstants::EMAIL_FOOTER_OPTION, null);
					wp_editor($emailFooterContent, "emailfooterid", $emailFooterEditorSettings);
				?>
			</div>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
		</form>
		<?php
	}
	
	public function includeDefaultDisplay() {
		$result = false;
		$result = $this->getDefaultLogo();
		return $result;
	}
	
	public function getDefaultLogo() {
		$defaultLogo = false;
		if(function_exists("get_option_tree")) {
			$defaultLogo = get_option_tree("office_logo");
		}
		return $defaultLogo;
	}
	
	public function getHeader() {
		$result = null;
		$emailDisplayType = get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION, null);
		if(!$emailDisplayType) {
			$emailDisplayType = self::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE;
		}
		switch ($emailDisplayType) {
			case self::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE:
				$agentPhoto = get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null);
				$logo = get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null);
				$name = get_option(iHomefinderConstants::EMAIL_NAME_OPTION, null);
				$company = get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION, null);
				$address1 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION, null);
				$address2 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION, null);
				$phone = get_option(iHomefinderConstants::EMAIL_PHONE_OPTION, null);
				$result = $this->getBasicEmailHeader($agentPhoto, $logo, $name, $company, $address1, $address2, $phone);
				break;
			case self::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE:
				$result = get_option(iHomefinderConstants::EMAIL_HEADER_OPTION, null);
				break;
			case self::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE:
				//Use the agent photo and office logo that were previoulsy uploaded
				$agentPhoto = get_option(iHomefinderConstants::AGENT_PHOTO_OPTION, null);
				$logo = $this->getDefaultLogo();
				$result = $this->getBasicEmailHeader($agentPhoto, $logo, null, null, null, null, null);
				break;
		}
	
		return $result;
	}
	
	public function getFooter() {
		$result = null;
		$emailDisplayType = get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION, null);
		if(!$emailDisplayType) {
			$emailDisplayType = self::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE;
		}
		switch ($emailDisplayType) {
			case self::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE:
				$agentPhoto = get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null);
				$logo = get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null);
				$name = get_option(iHomefinderConstants::EMAIL_NAME_OPTION, null);
				$company = get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION, null);
				$address1 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION, null);
				$address2 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION, null);
				$phone = get_option(iHomefinderConstants::EMAIL_PHONE_OPTION, null);
				$result = $this->getBasicEmailFooter($agentPhoto, $logo, $name, $company, $address1, $address2, $phone);
				break;
			case self::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE:
				$result = get_option(iHomefinderConstants::EMAIL_FOOTER_OPTION, null);
				break;
			case self::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE:
				//Use the agent photo and office logo that were previoulsy uploaded
				$agentPhoto = get_option(iHomefinderConstants::AGENT_PHOTO_OPTION, null);
				$logo = $this->getDefaultLogo();
				$result = $this->getBasicEmailFooter($agentPhoto, $logo, null, null, null, null, null);
				break;
		}
		return $result;
	}
	
	private function getBasicEmailHeader($agentPhoto, $logo, $name, $company, $address1, $address2, $phone) {
		$result = "<table width='650' border='0' cellpadding='2' cellspacing='0' bgcolor='#9b9b9b'><tr><td>";
		$result .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#ffffff'><tr>";
		$result .= "<td>";
		if($agentPhoto) {
			$agentPhotoSize = getimagesize($agentPhoto);
			$agentPhotoHeight = $agentPhotoSize[1];
			if($agentPhotoHeight > 142) {
				$result .= "<img src='" . $agentPhoto . "' height='142px' />";
			} else {
				$result .= "<img src='" . $agentPhoto . "' />";
			}
		}
		$result .=	"</td>";
		$result .= "<td>";
		$result .= "<font face='Arial, Helvetica, sans-serif'>";
		if($name != null) {
			$result .= "<b>" . $name . "</b><br/>";
		}
		if($company != null) {
			$result .= "<b>" . $company . "</b><br/><br/>";
		}
		if($address1 != null) {
			$result .= $address1 . "<br/>";
		}
		if($address2 != null) {
			$result .= $address2 . "<br/>";
		}
		if($phone != null) {
			$result .= $phone . "<br/>";
		}
		$result .= "</font>";
		$result .= "</td>";
		$result .= "<td align='right'>";
		if($logo) {
			$logoSize = getimagesize($logo);
			$logoHeight = $logoSize[1];
			if($logoHeight > 142) {
				$result .= "<img src='" . $logo . "' height='142px' />";
			} else {
				$result .= "<img src='" . $logo . "'/>";
			}
		}
		$result .=	"</td>";
		$result .= "</tr></table>";
		$result .= "</td></tr>";
		$result .= "<tr><td>";
		$result .= "<table width='100%' bgcolor='#ffffff'><tr><td>";
		return $result;
	}
	
	
	private function getBasicEmailFooter($agentPhoto, $logo, $name, $company, $address1, $address2, $phone) {
		$result = "</td></tr></table>";
		$result .= "</td></tr><tr><td>";
		$result .= "<table width='100%' cellpadding='10' cellspacing='0' border='0' bgcolor='#dedede'><tr>";
		$result .= "<td align='right'>";
		$result .= "<font face='Arial, Helvetica, sans-serif'>";
		if($name != null) {
			$result .= "<b>" . $name . "</b><br/>";
		}
		if($company != null) {
			$result .= "<b>" . $company . "</b><br/><br/>";
		}
		if($address1 != null) {
			$result .= $address1 . "<br/>";
		}
		if($address2 != null) {
			$result .= $address2 . "<br/>";
		}
		if($phone != null) {
			$result .= $phone . "<br/>";
		}
		$result .= "</font>";
		$result .= "</td>";
		$result .= "</tr></table>";
		$result .= "</td></tr></table>";
		return $result;
	}
	
}