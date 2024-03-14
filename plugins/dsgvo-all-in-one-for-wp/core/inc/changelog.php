<?php

if ( ! defined( 'WPINC' ) ) { die; }

update_option('dsgvoaio_notification_count_v42', '0');

update_option('dsgvoaio_notification_count_v44', '0');

update_option('dsgvoaio_notification_count_v45', '0');
?>
<div class="wrap">
	
	<h2><?php echo __('Changelogs', 'dsgvo-all-in-one-for-wp'); ?></h2>

	<p><?php echo __('Here you will find an overview of the changes and new features in each version listed in detail.', 'dsgvo-all-in-one-for-wp'); ?></p>

	<dl class="dsgvoaio_changlog_accordion">
		<dt><a href="#"><?php echo __('Version 4.5 (current)', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Bugfix - Files not fund  (google_fonts.php, tooltipster.bundle.min, tooltipster.min.css)', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>


		<dt><a href="#"><?php echo __('Version 4.4', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Important security fix (CSRF)', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('E-Mail spam protection revised', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		<b style="color: green;"><?php echo __('New features', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Google Fonts Checker integrated', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Vimeo - added to the Free Version (Shortcode only)', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 4.3', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Some Code changes according to the WordPress Codex', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 4.2', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Important security fix - added wp_kses to frontend output to prevent xss injection', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		<b style="color: green;"><?php echo __('New features', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Added Public-law foundation (KöR) under Legal form', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('"Accept all" button removed if no external service is used', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 4.1', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Facebook Pixel - WooCommerce fix', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Some CSS fixes', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Piwik / Matomo Policy updated', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Multilanguage Fix', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Fatal Error PHP >8.0 fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 4.0', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Important Security Fix - dsgvoaio_write_log', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.9', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('jQuery render error fixed (jQuery 3.5.1)', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint - input error fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Google Analytics function updated', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.8', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint - Umlauts Bugfix', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint - imagecreate PHP Warning fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint - Layout revised (br tags removed)', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('German translation improved', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('VG Wort - Auto replace function bugfix', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		<b style="color: green;"><?php echo __('New features', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('YouTube - added to the Free Version (Shortcode only)', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Function added - Close Personalize Popup after approval or rejection of all services', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Inline Privacy Policy texts added (VG Wort, YouTube, Shareaholic, LinkedIn)', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.7', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint - Own Text - Bug fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Translation / Localization revised', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.6', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Cookie Notice - small bugfixes regarding design', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		<b style="color: green;"><?php echo __('New features', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Italian language added', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint - Spam protection - Graphic or text now selectable', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Close button (X) in the cookie notice can now be disabled and enabled', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.5', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('MonsterInsights + Analytify Code improved', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('PDF data extract umlauts revised', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint revised', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		<b style="color: green;"><?php echo __('New features', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Notice for outgoing links added', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Koko Analytics added', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.4', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('MonsterInsights bug fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Facebook Pixel Events revised (PageView, Purchase, AddToCart, InitiateCheckout)', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Gutenberg Blocks are no longer loaded if Wordpress is installed under V5.0', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.3', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Loading speed significantly improved - files from js/dist - for Gutenberg Block is no longer loaded in the frontend', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint - Pharmacist added', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Text "To be defined under imprint" removed', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('JS files compressed', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Cookie Notice Mobile - scrolling bug fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.2', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Facebook comments - bug fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		<b style="color: green;"><?php echo __('New features', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Gutenberg Blocks integrated - to be found under Edit Page - Add Block - Search Term = DSGVO', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Imprint Generator integrated', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Email address encrypted in source code', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Cookie Notice Design/Layout 3 - language switch (for multilingualism) + close function added', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.1', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Cookie Notice Animation added - duration freely selectable', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('MonsterInsights integrated (Google Analytics)', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Analytify integrated (Google Analytics)', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Import / Export Funktion integrated', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Log - delete Function integrated', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 3.0', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Cookie Notice - HTML bug fixed when saving', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Data extraction via PDF - function / PHP code renewed', 'dsgvo-all-in-one-for-wp'); ?></li>

		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 2.9', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Update because of small bugfixes (button/table/font sizes) + umlauts', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

		<dt><a href="#"><?php echo __('Version 2.8', 'dsgvo-all-in-one-for-wp'); ?></a></dt>
		<dd>
		<b><?php echo __('Bugfixes', 'dsgvo-all-in-one-for-wp'); ?></b>
		<ul>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Umlauts problem fixed', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Elementor Preview Mode', 'dsgvo-all-in-one-for-wp'); ?></li>
			<li><span class="dashicons dashicons-yes"></span><?php echo __('Formatting of the tables improved', 'dsgvo-all-in-one-for-wp'); ?></li>
		</ul>
		</dd>

	</dl>

</div>

	