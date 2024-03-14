<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_body_classes_admin_infobar() {
	global $wp247_mobile_detect;
	return array(
  'Version:' => '<p>' . WP247_BODY_CLASSES_VERSION  . '</p>'
, 'About this plugin' => '
<ul>
	<li><a href="http://wp247.net/wp247-body-classes/" target="_blank">Plugin background</a></li>
	<li><a href="http://wordpress.org/support/plugin/wp247-body-classes" target="_blank">Plugin support</a></li>
	<li><a href="http://wordpress.org/support/view/plugin-reviews/wp247-body-classes" target="_blank">Review this plugin</a></li>
</ul>'
, 'Enjoy this plugin?' => '
<p>If you find this plugin useful, would you consider making a donation to one or more of my favorite causes?</p>
<p><a class="wp247sapi-button button-primary" href="http://www.ijm.org/" target="_blank">Help rescue the oppressed</a></p>
<p><a class="wp247sapi-button button-primary" href="http://www.compassion.com/donate.htm" target="_blank">Show compassion on an impoverished child</a></p>
<p><a class="wp247sapi-button button-primary" href="https://thelastwell.org/" target="_blank">Give someone clean and safe drinking water</a></p>
<p><a class="wp247sapi-button button-primary" href="https://www.tbmtx.org//" target="_blank">Help provide disaster relief</a></p>
<p><a class="wp247sapi-button button-primary" href="https://www.samaritanspurse.org//" target="_blank">Help provide humanitarian aid</a></p>
<p><a class="wp247sapi-button button-primary" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RM26LBV2K6NAU" target="_blank">Buy me a coffee :)</a></p>
' );
}
function wp247_body_classes_admin_infobar_width() {
	return 15;
}
?>
