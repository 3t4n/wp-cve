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

?>

<div class="vap-ips-container" id="ips-container">

	<?php
	foreach ($this->user->ips as $ip)
	{
		?>
		<div class="control-group input-append ip-wrapper">
			<input type="text" name="ip[]" value="<?php echo $this->escape($ip); ?>" />

			<button type="button" class="btn trash-ip">
				<i class="fas fa-trash"></i>
			</button>
		</div>
		<?php
	}
	?>

</div>

<?php
echo VAPApplication::getInstance()->alert(
	JText::translate('VAPAPIUSEREMPTYIPNOTICE'),
	'info',
	$dismissible = false,
	array(
		'id'    => 'no-ip-notice',
		'style' => $this->user->ips ? 'display:none;' : '',
	)
);
?>

<div class="control-group" id="ips-container">
	<button type="button" class="btn" id="add-ip"><?php echo JText::translate('VAPMANAGEAPIUSER9'); ?></button>
</div>

<script>

	jQuery(function($) {
		$(document).on('click', '.trash-ip', function() {
			$(this).closest('.ip-wrapper').remove();
		});

		$('#add-ip').on('click', () => {
			const box = $('<div class="control-group input-append ip-wrapper"></div>');

			box.append($('<input type="text" name="ip[]" />'));
			box.append($('<button type="button" class="btn trash-ip"><i class="fas fa-trash"></i></button>'));

			$('#ips-container').append(box);

			// hide notice when adding an IP
			$('#no-ip-notice').hide();
		});
	});

</script>
