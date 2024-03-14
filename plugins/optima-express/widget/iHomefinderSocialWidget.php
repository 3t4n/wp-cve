<?php

class iHomefinderSocialWidget extends iHomefinderWidget {
	
	public function __construct() {
		parent::__construct(
			"iHomefinderSocialWidget",
			"IDX: Social",
			array(
				"description" => "Displays icons linked to your social media pages."
			)
		);
	}
	
	public function widget($args, $instance) {
		$instance = $this->migrate($instance);
		if($this->isEnabled($instance)) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			echo $beforeWidget;
			?>
			<div id="social-icons">
				<?php
				$this->getLink(iHomefinderConstants::SOCIAL_FACEBOOK_URL_OPTION, "https://www.facebook.com/", "/images/facebook-icon.png");
				$this->getLink(iHomefinderConstants::SOCIAL_LINKEDIN_URL_OPTION, "https://www.linkedin.com/", "/images/linkedin-icon.png");
				$this->getLink(iHomefinderConstants::SOCIAL_TWITTER_URL_OPTION, "https://twitter.com/", "/images/twitter-icon.png");
				$this->getLink(iHomefinderConstants::SOCIAL_PINTEREST_URL_OPTION, "https://www.pinterest.com/", "/images/pinterest-icon.png");
				$this->getLink(iHomefinderConstants::SOCIAL_INSTAGRAM_URL_OPTION, "https://instagram.com/", "/images/instagram-icon.png");
				$this->getLink(iHomefinderConstants::SOCIAL_GOOGLE_PLUS_URL_OPTION, "https://plus.google.com/", "/images/google-plus-icon.png");
				$this->getLink(iHomefinderConstants::SOCIAL_YOUTUBE_URL_OPTION, "https://www.youtube.com/", "/images/youtube-icon.png");
				$this->getLink(iHomefinderConstants::SOCIAL_YELP_URL_OPTION, "https://www.yelp.com/", "/images/yelp-icon.png");
				?>
			</div>
			<?php
			echo $afterWidget;
		}
	}
	
	private function getLink($optionName, $socialUrl, $iconFileName) {
		$url = get_option($optionName, null);
		if(!empty($url)) {
			?>
			<a href="<?php echo esc_js($socialUrl . $url); ?>" target="_blank">
				<img class="ihf-social-icon" src="<?php echo plugins_url($iconFileName, dirname(__FILE__)); ?>" />
			</a>
			<?php
		} 
	}
	
	public function update($newInstance, $oldInstance) {
		$newInstance = $this->migrate($newInstance);
		$oldInstance = $this->migrate($oldInstance);
		$instance = $newInstance;
		return $instance;
	}
	
	public function form($instance) {
		$instance = $this->migrate($instance);
		$configurationUrl = admin_url("admin.php?page=" . iHomefinderConstants::PAGE_SOCIAL);
		?>
		<p>
			<a href="<?php echo $configurationUrl ?>">Configure Social Links</a>
		</p>
		<?php
	}
	
}
