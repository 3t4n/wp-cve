<?php

class iHomefinderAdminInformation extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		?>
		<h2>Information</h2>
		<h3>Shortcodes</h3>
		<p>Create Optima Express shortcodes and copy them to your clipboard.</p>
		<h3>Register</h3>
		<p>The Optima Express plugin needs to be registered with iHomefinder. Registration is automatic if you signup for a trial account or purchase a live account from this page. Or, you can enter a registration key that you've received separately.</p>
		<h3>IDX Pages</h3>
		<p>View and configure your Optima Express IDX pages here. Change permalinks, page titles and templates.</p>
		<h3>Configuration</h3>
		<p>This page provides customization features including the ability to override default styles for Optima Express.</p>
		<h3>Bio Widget</h3>
		<p>Setup your bio information. Upload a photo and insert contact information.</p>
		<h3>Social Widget</h3>
		<p>Enter your social network information.</p>
		<h3>Email Branding</h3>
		<p>Customize your email header and footer</p>
		<?php if(iHomefinderDisplayRules::getInstance()->isCommunityPagesEnabled()) { ?>
			<h3>Community Pages</h3>
			<p>Create custom pages for your communities. These pages contain a list of properties in the community, SEO friendly URLs and the ability to add custom content.</p>
		<?php } ?>
		<?php if(iHomefinderDisplayRules::getInstance()->isSeoCityLinksEnabled()) { ?>
			<h3>SEO City Links</h3>
			<p>Create SEO links for display in the SEO City Links widget.
		<?php } ?>
		<?php
	}
	
}