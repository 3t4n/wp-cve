<?php

class iHomefinderAgentBioWidget extends iHomefinderWidget {

	const STANDARD_DISPLAY_TYPE = "standard";
	const NARROW_DISPLAY_TYPE = "narrow";
	
	public function __construct() {
		parent::__construct(
			"iHomefinderAgentBioWidget",
			"IDX: Agent Bio",
			array(
				"description" => "Displays an agent bio."
			)
		);
	}
	
	public function widget($args, $instance) {
		$instance = $this->migrate($instance);
		if($this->isEnabled($instance)) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
			$displayType = self::STANDARD_DISPLAY_TYPE;
			if(array_key_exists("displayType", $instance)) {
				$displayType = $instance["displayType"];
			}
			$agentPhotoUrl = get_option(iHomefinderConstants::AGENT_PHOTO_OPTION, null);
			$agentText = get_option(iHomefinderConstants::AGENT_TEXT_OPTION, null);
			$displayTitle = get_option(iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION, null);
			$contactPhone = get_option(iHomefinderConstants::CONTACT_PHONE_OPTION, null);
			$contactEmail = get_option(iHomefinderConstants::CONTACT_EMAIL_OPTION, null);
			$agentDesignations = get_option(iHomefinderConstants::AGENT_DESIGNATIONS_OPTION, null);
			$agentLicenseInfo = get_option(iHomefinderConstants::AGENT_LICENSE_INFO_OPTION, null);
			echo $beforeWidget;
			if(!empty($displayTitle)) {
				echo $beforeTitle . $displayTitle . $afterTitle;
			}
			?>
			<table>
				<tr>
					<?php if(!empty($agentPhotoUrl)) { ?>
						<td class="ihf-bio-img">
							<img id="ihf-bio-img" src="<?php echo esc_js($agentPhotoUrl); ?>" alt="<?php echo esc_js($displayTitle); ?>" />
						</td>
					<?php } ?>
					<?php if($displayType == iHomefinderAgentBioWidget::NARROW_DISPLAY_TYPE) { ?>
						</tr><tr>
					<?php } ?>
					<td>
						<div class="ihf-bio-about-info">
							<?php if(!empty($agentText)) { ?>
								<div>
									<?php echo html_entity_decode($agentText) ?>
								</div>
								<br />				
							<?php } ?>
							<?php if(!empty($contactPhone)) { ?>
								<div>
									<?php echo esc_js($contactPhone); ?>
								</div>
							<?php } ?>
							<?php if(!empty($contactEmail)) { ?>
								<div>
									<?php echo esc_js($contactEmail); ?>
								</div>
							<?php } ?>
							<?php if(!empty($agentDesignations)) { ?>
								<div>
									<?php echo esc_js($agentDesignations); ?>
								</div>
							<?php } ?>
							<?php if(!empty($agentLicenseInfo)) { ?>
								<div>
									<?php echo esc_js($agentLicenseInfo); ?>
								</div>
							<?php } ?>
						</div>
					</td>
				</tr>
			</table>
			<?php
			echo html_entity_decode(esc_js($afterWidget));
		}
	}
	
	public function update($newInstance, $oldInstance) {
		$newInstance = $this->migrate($newInstance);
		$oldInstance = $this->migrate($oldInstance);
		$instance = $oldInstance;
		$instance["displayType"] = $newInstance["displayType"];
		return $instance;
	}
	
	public function form($instance) {
		$instance = $this->migrate($instance);
		$displayType = null;
		if (array_key_exists("displayType",$instance)) {
			$displayType = esc_attr($instance["displayType"]);
		}
		?>

		<p>
			<label>
				Display Type:
				<select class="widefat" name="<?php echo esc_js($this->get_field_name("displayType")); ?>">
					<option value="<?php echo self::STANDARD_DISPLAY_TYPE ?>">Standard</option>
					<option value="<?php echo self::NARROW_DISPLAY_TYPE ?>" <?php if($displayType == self::NARROW_DISPLAY_TYPE) {echo "selected";} ?>>Narrow</option>
				</select>
			</label>
		</p>
		<?php
		$configurationUrl = admin_url("admin.php?page=" . iHomefinderConstants::PAGE_BIO);
		?>
		<p>
			<a href="<?php echo $configurationUrl ?>">Configure Bio</a>
		</p>
		<?php
	}
	
}