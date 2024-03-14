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
JHtml::fetch('bootstrap.tooltip', '.hasTooltip');

$vik = VAPApplication::getInstance();

// create base URI
$uri = 'index.php?option=com_vikappointments&task=api&event=' . $plugin->getName();
$uri = $vik->routeForExternalUse($uri);

?>

<p>This plugin can be used to delete any element via API according to the specifications of the requested model.</p>

<p>The records to delete can be passed as JSON within the body of the payload, under the ID attribute as integer or array.</p>

<h3>Usage</h3>

<pre>
<strong>End-Point URL</strong>
<?php echo $uri; ?>


<strong>Params</strong>
username    (string)    The username of the application.
password    (string)    The password of the application.
model       (string)    The model handling the cancellation process.

// to be sent as payload
id          (mixed)     The ID(s) of the records to remove (array or int).
</pre>

<br />

<h3>Generate Delete URL</h3>

<div style="margin-bottom: 10px;" class="form-with-select">

	<select id="plg-login">
		<?php echo JHtml::fetch('select.options', JHtml::fetch('vaphtml.admin.apilogins', $blank = true)); ?>
	</select>

	<select id="plg-model">
		<?php echo JHtml::fetch('select.options', JHtml::fetch('vaphtml.admin.models', $usepath = false, $blank = true)); ?>
	</select>

	<div class="input-append">
		<input type="text" id="plg-ids" />

		<span class="btn hasTooltip" title="<?php echo $this->escape('The IDs must be separated by a comma'); ?>">
			<i class="fas fa-info-circle"></i>
		</span>
	</div>

</div>

<pre id="plgurl">

</pre>

<br />

<h3>Successful Response (JSON)</h3>

<pre>
true
</pre>

<br />

<h3>Failure Response (JSON)</h3>

<pre>
false
</pre>

<script>

	(function($) {
		'use strict';

		$(function() {
			VikRenderer.chosen('.form-with-select');
			
			$('#plg-login, #plg-model, #plg-ids').on('change', () => {
				var clean = '<?php echo $uri; ?>';

				var login = $('#plg-login').val().split(/;/);

				clean += '&username=' + login[0];
				clean += '&password=' + (login[1] ? login[1] : '');
				clean += '&model=' + $('#plg-model').val();

				var url = encodeURI(clean);

				let payload = {id: $('#plg-ids').val().split(/,\s*/g)};
				// convert payload into query string by wrapping the whole object into "args" property
				payload = '&' + $.param({args: payload});

				// append payload after encoding the URI, because param method provided
				// by jQuery already encodes the URI components
				url += payload;
				// append also the clean version
				clean += decodeURI(payload);

				$("#plgurl").html('<a href="' + url + '" target="_blank">' + clean + '</a>');
			});

			$('#plg-login').trigger('change');
		});
	})(jQuery);

</script>
