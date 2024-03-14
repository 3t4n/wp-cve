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

$name   = !empty($displayData['name'])  ? $displayData['name']   : 'name';
$id     = !empty($displayData['id'])    ? $displayData['id']     : $name;
$value  = isset($displayData['value'])  ? $displayData['value']  : '';
$class  = isset($displayData['class'])  ? $displayData['class']  : '';
$toggle = isset($displayData['toggle']) ? $displayData['toggle'] : true;

?>

<div class="input-append">
	
	<input
		type="password"
		name="<?php echo $this->escape($name); ?>"
		id="<?php echo $this->escape($id); ?>"
		value="<?php echo $this->escape($value); ?>"
		size="40"
		class="<?php echo $this->escape($class); ?>"
	/>

	<?php
	if ($toggle)
	{
		?>
		<button type="button" class="btn" id="<?php echo $id; ?>-btn">
			<i class="fas fa-eye"></i>
		</button>
		<?php
	}
	?>

</div>

<?php
if ($toggle)
{
	?>
	<script>
		(function($) {
			'use strict';

			$(function() {
				$('#<?php echo $id; ?>-btn').on('click', function() {
					const input = $(this).prev();

					if (input.is(':password')) {
						input.attr('type', 'text');
						$(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
					} else {
						input.attr('type', 'password');
						$(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
					}
				});
			});
		})(jQuery);
	</script>
	<?php
}
