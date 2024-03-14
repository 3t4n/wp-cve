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

$vik = VAPApplication::getInstance();

?>

<div style="padding: 10px 10px 0 10px">

	<div class="row-fluid">

		<div class="span6">
			<?php
			echo $vik->openFieldset(JText::translate('VAPMANAGESERVICE13'));

			if ($this->employees)
			{
				?>
				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
					<?php
					foreach ($this->employees as $e)
					{
						?>
						<tr>
							<td style="text-align: center;">
								<?php echo $e->nickname; ?>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
			}
			else
			{
				echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
			}

			echo $vik->closeFieldset(); ?>
		</div>

		<div class="span6">
			<?php
			echo $vik->openFieldset(JText::translate('VAPMANAGESERVICE11'));
				
			if ($this->options)
			{
				?>
				<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
					<?php
					foreach ($this->options as $o)
					{
						?>
						<tr>
							<td style="text-align: center;">
								<?php echo $o->name; ?>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
			}
			else
			{
				echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
			}

			echo $vik->closeFieldset();
			?>
		</div>

	</div>

</div>
