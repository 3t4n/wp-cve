<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.rss
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$vik = VAPApplication::getInstance();

?>

<style>
	/* make background color of checkbox darker */
	#rss_optin_status1:not(:checked) + label:before {
		background-color: #ccc; 
	}
</style>

<!-- RSS intro -->

<p>
	<?php
	_e(
		'VikAppointments supports the possibility of subscribing to a RSS channel and we are wondering whether you might be interested in using this service.', 
		'vikappointments'
	);
	?>
</p>

<!-- explain RSS usage -->

<p>
	<b>
		<?php
		_e(
			'Why should I opt in to this service?',
			'vikappointments'
		);
		?>
	</b>
</p>

<p>
	<?php
	_e(
		'This RSS service mainly covers these macro sections: <b>news</b>, <b>tips</b> and <b>offers</b>. You might receive news about VikAppointments or anything else that interests the WordPress world, such as the jQuery conflict that broke millions of websites with WP 5.5. Sometimes you could receive notifications about tips or features that you didn\'t even think they could exist. During the most important festivities you might receive coupon codes to renew you license at a discount price. We guarantee you that this service won\'t result in an annoying advertising system.', 
		'vikappointments'
	);
	?>
</p>

<!-- privacy policy -->

<p>
	<b>
		<?php
		_e(
			'What kind of personal data do we collect?',
			'vikappointments'
		);
		?>
	</b>
</p>

<p>
	<?php
	_e(
		'Our company does not collect any personal data here. The syndication URL never includes sensitive data that may be linked back to you.',
		'vikappointments'
	);
	?>
</p>

<!-- opt in checkbox -->

<p>
	<?php
	_e(
		'We need you to explicitly opt in to this RSS service for GDPR compliance. Toggle the checkbox below if you are interested or leave it unchecked. You are free to change your decision in any time from the configuration of VikAppointments.',
		'vikappointments'
	);
	?>
</p>

<p style="display: flex; align-items: center;">
	<?php
	$yes = $vik->initRadioElement('', '', true);
	$no  = $vik->initRadioElement('', '', false);

	echo $vik->radioYesNo('rss_optin_status', $yes, $no);
	?>
	<label style="margin-left: 6px;">
		<?php
		_e(
			'I want to opt in to VikWP RSS service',
			'vikappointments'
		);
		?>
	</label>
</p>

<!-- finalisation -->

<p>
	<?php
	_e(
		'Hit the <b>Save</b> button to confirm your choice and close this popup.',
		'vikappointments'
	);
	?>
</p>
