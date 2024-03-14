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

<div class="import-modal-inner">

	<?php echo $vik->bootStartTabSet('worktime', ['active' => 'worktime_upload']); ?>

		<!-- UPLOAD -->
			
		<?php
		echo $vik->bootAddTab('worktime', 'worktime_upload', JText::translate('VAPUPLOAD'));
		echo $this->loadTemplate('workdays_import_upload');
		echo $vik->bootEndTab();
		?>

		<!-- UPLOAD -->
			
		<?php
		echo $vik->bootAddTab('worktime', 'worktime_sample', JText::translate('VAPSAMPLE'));
		echo $this->loadTemplate('workdays_import_sample');
		echo $vik->bootEndTab();
		?>

	<?php echo $vik->bootEndTabSet(); ?>

</div>