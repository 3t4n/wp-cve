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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('formbehavior.chosen');

$vik = VAPApplication::getInstance();

// create base URI
$uri = 'index.php?option=com_vikappointments&task=api&event=' . $plugin->getName();
$uri = $vik->routeForExternalUse($uri);

?>

<p>This plugin can be used to delete/cancel an appointment after deleting the related event from an external ICS calendar.</p>

<p>The data to delete can be passed as JSON within the body of the payload.</p>

<h3>Usage</h3>

<pre>
<strong>End-Point URL</strong>
<?php echo $uri; ?>


<strong>Params</strong>
username    (string)    The username of the application.
password    (string)    The password of the application.
</pre>

<br />

<h3>Generate Cancel URL</h3>

<div style="margin-bottom: 10px;" class="form-with-select">

	<select id="plg-login">
		<?php echo JHtml::fetch('select.options', JHtml::fetch('vaphtml.admin.apilogins', $blank = true)); ?>
	</select>

	<button type="button" class="btn btn-primary" id="plg-commit">
		POST
	</button>

</div>

<div style="margin-bottom: 10px;">
	<?php
	echo $vik->getCodeMirror('plg_payload', $plugin->getDummyPayload(), array('syntax' => 'json'));
	?>
</div>

<pre id="plgurl">

</pre>

<br />

<h3>Successful Response (JSON)</h3>

<pre>
{
    "id": 1,
    ...
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
		
			$('#plg-login').on('change', () => {
				var clean = '<?php echo $uri; ?>';

				var login = $('#plg-login').val().split(/;/);

				clean += '&username=' + login[0];
				clean += '&password=' + (login[1] ? login[1] : '');

				var url = encodeURI(clean);

				let payload;

				try
				{
					// extract JSON from editor
					payload = JSON.parse(Joomla.editors.instances.plg_payload.getValue());
				} catch (err) {
					// invalid JSON
					alert('Invalid JSON: ' + err);
					payload = {};
				}

				// convert payload into query string by wrapping the whole object into "args" property
				payload = $.param({args: payload});

				if (payload) {
					// append payload after encoding the URI, because param method provided
					// by jQuery already encodes the URI components
					url += '&' + payload;
					// append also the clean version
					clean += '&' + decodeURIComponent(payload);
				}

				$("#plgurl").html('<a href="' + url + '" target="_blank">' + clean + '</a>');
			});

			$('#plg-commit').on('click', () => {
				// commit payload
				$('#plg-login').trigger('change');
				// reach URL on completion
				window.open($("#plgurl").find('a').attr('href'), '_blank');
			});

			$('#plg-login').trigger('change');
		});
	})(jQuery);

</script>
