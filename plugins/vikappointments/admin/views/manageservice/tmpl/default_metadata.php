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

$service = $this->service;

$vik = VAPApplication::getInstance();

/**
 * Get service metadata.
 *
 * @since 1.6.1
 */
$meta = $service->metadata ? (object) json_decode($service->metadata, true) : new stdClass;

?>

<div class="row-fluid">

	<div class="span6 full-width">
		<?php echo $vik->openEmptyFieldset(); ?>

			<!-- BROWSER PAGE TITLE - Text -->

			<?php
			$help = $vik->createPopover(array(
				'title' 	=> JText::translate('COM_CONTENT_FIELD_BROWSER_PAGE_TITLE_LABEL'),
				'content' 	=> JText::translate('COM_CONTENT_FIELD_BROWSER_PAGE_TITLE_DESC'),
			));
			
			echo $vik->openControl(JText::translate('COM_CONTENT_FIELD_BROWSER_PAGE_TITLE_LABEL') . $help); ?>
				<input type="text" name="metadata[title]" value="<?php echo isset($meta->title) ? $meta->title : ''; ?>" size="40" />
			<?php echo $vik->closeControl(); ?>

			<!-- META DESCRIPTION - Textarea -->

			<?php
			$help = $vik->createPopover(array(
				'title' 	=> JText::translate('JFIELD_META_DESCRIPTION_LABEL'),
				'content' 	=> JText::translate('JFIELD_META_DESCRIPTION_DESC'),
			));

			echo $vik->openControl(JText::translate('JFIELD_META_DESCRIPTION_LABEL') . $help); ?>
				<textarea name="metadata[description]" cols="40" rows="6"><?php echo isset($meta->description) ? $meta->description : ''; ?></textarea>
			<?php echo $vik->closeControl(); ?>

			<!-- META KEYWORDS - Textarea -->

			<?php
			$help = $vik->createPopover(array(
				'title' 	=> JText::translate('JFIELD_META_KEYWORDS_LABEL'),
				'content' 	=> JText::translate('JFIELD_META_KEYWORDS_DESC'),
			));

			echo $vik->openControl(JText::translate('JFIELD_META_KEYWORDS_LABEL') . $help); ?>
				<textarea name="metadata[keywords]" cols="40" rows="6"><?php echo isset($meta->keywords) ? $meta->keywords : ''; ?></textarea>
			<?php echo $vik->closeControl(); ?>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewService","key":"metadata","type":"field"} -->

			<?php
			/**
			 * Look for any additional fields to be pushed within
			 * the "Metadata" fieldset.
			 *
			 * @since 1.6.6
			 */
			if (isset($this->forms['metadata']))
			{
				echo $this->forms['metadata'];

				// unset metadata form to avoid displaying it twice
				unset($this->forms['metadata']);
			}

		echo $vik->closeEmptyFieldset(); ?>
	</div>
	
</div>
