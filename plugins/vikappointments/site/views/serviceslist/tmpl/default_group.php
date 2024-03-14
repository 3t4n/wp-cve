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
 * @todo 	Should we use a default label for
 * 			the group with no name (e.g. "uncategorized")?
 *
 * 			A default label should be used only in case
 * 			there are other services assigned to a specific group.
 * 			This because if the owner doesn't need to use groups,
 * 			it is not correct to display the "uncategorised" group.
 *
 * 			Anyhow, it could be helpful to evaluate the uncategorised
 * 			label within the view.html.php and to decide if it should
 * 			be used within this template file.
 */

if ($this->group->name)
{
	?>
	<div class="vapsergroupdiv">
		<?php echo $this->group->name; ?>
	</div>

	<?php
	if (trim($this->group->description))
	{
		?>
		<div class="vapsergroupdescriptiondiv">
			<?php echo VikAppointments::renderHtmlDescription($this->group->description, 'serviceslist'); ?>
		</div>
		<?php
	}
}
