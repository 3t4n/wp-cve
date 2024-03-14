<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @var  VAPApiEvent  $plugin  The instance of the event.
 */
extract($displayData);

JHtml::fetch('formbehavior.chosen');

$vik = VAPApplication::getInstance();

// create base URI
$uri = 'index.php?option=com_vikappointments&task=api&event=' . $plugin->getName();
$uri = $vik->routeForExternalUse($uri);

?>

<p>This plugin is needed to verify the connection between the application client and the server.</p>

<h3>Usage</h3>

<pre>
<strong>End-Point URL</strong>
<?php echo $uri; ?>


<strong>Params</strong>
username    (string)    The username of the application.
password    (string)    The password of the application.
</pre>

<br />

<h3>Generate Ping URL</h3>

<div style="margin-bottom: 10px;" class="form-with-select">

	<select id="plg-login">
		<?php echo JHtml::fetch('select.options', JHtml::fetch('vaphtml.admin.apilogins', true)); ?>
	</select>

</div>

<pre id="plgurl">

</pre>

<br />

<h3>Successful Response (JSON)</h3>

<pre>
{
    "status": 1,
    "version": "<?php echo VIKAPPOINTMENTS_SOFTWARE_VERSION; ?>",
    "platform": "<?php echo VersionListener::getPlatform(); ?>"
}
</pre>

<br />

<h3>Failure Response (JSON)</h3>

<pre>
{
    "errcode": 500,
    "error": "The reason of the error"
}
</pre>

<script>

	(function($) {
		'use strict';

		$(function() {
			VikRenderer.chosen('.form-with-select');
		
			$('#plg-login').on('change', function() {
				var clean = '<?php echo $uri; ?>';

				var login = $('#plg-login').val().split(/;/);

				clean += '&username=' + login[0];
				clean += '&password=' + (login[1] ? login[1] : '');

				var url = encodeURI(clean);

				$("#plgurl").html('<a href="' + url + '" target="_blank">' + clean + '</a>');
			});

			$('#plg-login').trigger('change');
		});
	})(jQuery);

</script>
