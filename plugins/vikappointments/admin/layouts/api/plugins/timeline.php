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

<p>This plugin can be used to load the availability timeline for a specific day.</p>

<h3>Usage</h3>

<pre>
<strong>End-Point URL</strong>
<?php echo $uri; ?>


<strong>Params</strong>
username    (string)    The username of the application.
password    (string)    The password of the application.

// to be sent as payload
id_ser      (integer)   The service ID.
id_emp      (integer)   The employee ID (optional).
date        (string)    The UTC check-in date (in military format).
people      (integer)   The number of participants (1 by default).
locations   (array)     An optional array of locations (ID).
admin       (boolean)   True if we are presenting the timeline to an administrator.
</pre>

<br />

<h3>Generate Timeline URL</h3>

<style>
	.form-with-select .field-calendar {
		display: inline-block;
	}
</style>

<div style="margin-bottom: 10px;" class="form-with-select">

	<select id="plg-login">
		<?php echo JHtml::fetch('select.options', JHtml::fetch('vaphtml.admin.apilogins', $blank = true)); ?>
	</select>

	<select id="plg-service">
		<?php echo JHtml::fetch('select.options', JHtml::fetch('vaphtml.admin.services', $strict = false, $blank = true)); ?>
	</select>

	<select id="plg-employee">
		<?php echo JHtml::fetch('select.options', JHtml::fetch('vaphtml.admin.employees', $strict = false, $blank = true)); ?>
	</select>

	<?php echo $vik->calendar(null, 'plg_date'); ?>

	<div class="input-append">
		<input type="number" id="plg-people" value="1" min="1" max="9999" step="1" />

		<span class="btn"><i class="fas fa-user"></i></span>
	</div>

	<select id="plg-admin">
		<?php
		echo JHtml::fetch('select.options', array(
			JHtml::fetch('select.option', 0, 'Client'),
			JHtml::fetch('select.option', 1, 'Admin'),
		));
		?>
	</select>

</div>

<pre id="plgurl">

</pre>

<br />

<h3>Successful Response (JSON)</h3>

<pre>
[
    [
        {
            "checkin": "2021-06-21T08:00:00",
            "checkout": "2021-06-21T09:00:00",
            "status": 1,
            "price": 120,
            "ratesTrace": [],
            "occupancy": 2,
            "capacity": 5
        },
        {
            "checkin": "2021-06-21T09:00:00",
            "checkout": "2021-06-21T10:00:00",
            "status": 1,
            "price": 125,
            "ratesTrace": [],
            "occupancy": 0,
            "capacity": 5
        }
    ]
]
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
		
			$('#plg-login, #plg-service, #plg-employee, #plg_date, #plg-people, #plg-admin').on('change', () => {
				var clean = '<?php echo $uri; ?>';

				var login = $('#plg-login').val().split(/;/);
				var date = $('#plg_date').val();

				if (date) {
					// convert date to military format
					date = getDateFromFormat(date, '<?php echo VAPFactory::getConfig()->get('dateformat'); ?>', false);
				}

				clean += '&username=' + login[0];
				clean += '&password=' + (login[1] ? login[1] : '');
				clean += '&args[id_ser]=' + $('#plg-service').val();
				clean += '&args[id_emp]=' + $('#plg-employee').val();
				clean += '&args[date]=' + date;
				clean += '&args[people]=' + parseInt($('#plg-people').val());
				clean += '&args[admin]=' + $('#plg-admin').val();

				var url = encodeURI(clean);

				$("#plgurl").html('<a href="' + url + '" target="_blank">' + clean + '</a>');
			});

			$('#plg-login').trigger('change');
		});
	})(jQuery);

</script>
