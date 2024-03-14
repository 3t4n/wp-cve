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

JHtml::fetch('formbehavior.chosen');

$vik = VAPApplication::getInstance();

?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"notes.start","type":"field"} -->

<?php
// plugins can use the "notes.start" key to introduce custom
// HTML before the notes grid
if (isset($this->addons['notes.start']))
{
	echo $this->addons['notes.start'];

	// unset details form to avoid displaying it twice
	unset($this->addons['notes.start']);
}

?>

<div class="user-notes-container">
					
	<?php
	foreach ($this->notes as $note)
	{
		$tags = $this->tagModel->readTags($note->tags);
		?>
		<div class="user-note-box">
			<fieldset>
				<legend>
					<?php echo $note->title; ?>

					<i class="fas fa-<?php echo $note->status ? 'eye ok' : 'low-vision no'; ?>"></i>
				</legend>

				<div class="user-note-body"><?php echo $note->content; ?></div>

				<div class="user-note-footer">
					<?php
					if ($tags)
					{
						?>
						<div class="user-note-tags">
							<?php
							foreach ($tags as $tag)
							{
								echo JHtml::fetch('vikappointments.tag', $tag, 'icon', array('style' => 'margin-right: 4px;'));
							}
							?>
						</div>
						<?php
					}
					?>

					<div class="user-note-date">
						<small><?php echo JHtml::fetch('date', VAPDateHelper::isNull($note->modifiedon) ? $note->createdon : $note->modifiedon, JText::translate('DATE_FORMAT_LC2')); ?></small>
					</div>
				</div>

			</fieldset>
		</div>
		<?php
	}
	?>

</div>

<p style="text-align: center">
	<em><?php echo JText::translate('VAPNOTESMODALHINT'); ?></em>
</p>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"notes.end","type":"field"} -->

<?php
// plugins can use the "notes.end" key to introduce custom
// HTML after the notes grid
if (isset($this->addons['notes.end']))
{
	echo $this->addons['notes.end'];

	// unset details form to avoid displaying it twice
	unset($this->addons['notes.end']);
}
