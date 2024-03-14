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

<div class="inspector-form">

	<div class="inspector-fieldset">
		<!-- DESCRIPTION -->

		<?php echo $vik->alert(JText::translate('VAPMANAGEEMPLOYEE33_DESC'), 'info', false, ['style' => 'margin-top: 0;']); ?>

		<!-- URL -->

		<div>
			<input type="text" name="ical_url" value="<?php echo $this->escape($this->employee->ical_url); ?>" placeholder="https://" />
		</div>

		<!-- WARNING -->

		<?php
		echo $vik->alert(JText::sprintf(
			'VAPMANAGEEMPLOYEE33_WARN',
			$this->escape('index.php?option=com_vikappointments&task=cronjob.add&class=icalendarimport.php')
		));
		?>

	</div>

</div>
