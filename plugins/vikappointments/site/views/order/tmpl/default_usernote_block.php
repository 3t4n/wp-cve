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

$note = $this->itemNote;

?>

<div class="vap-order-usernote vaporderdetailsbox">

	<!-- HEADING -->

	<div class="usernote-head">

		<!-- TITLE -->

		<h4><?php echo $note->title; ?></h4>

		<!-- AUTHOR - DATE -->

		<div class="usernote-subtitle">
			<?php
			// format last modify date date
			$date = JHtml::fetch(
				'date',
				VAPDateHelper::isNull($note->modifiedon) ? $note->createdon : $note->modifiedon,
				JText::translate('DATE_FORMAT_LC2'),
				VikAppointments::getUserTimezone()->getName()
			);

			// make sure we have an author name
			if ($note->author)
			{
				// display using the form "Created by [AUTHOR] on [DATE]"
				echo JText::sprintf('VAPAUTHORDATE', $note->authorName, $date);
			}
			else
			{
				// missing author, display only the creation date without label
				echo $date;
			}
			?>
		</div>

	</div>

	<!-- CONTENT -->

	<?php
	if ($note->content)
	{
		?>
		<div class="usernote-content">
			<?php echo VikAppointments::renderHtmlDescription($note->content, 'paymentorder'); ?>
		</div>
		<?php
	}
	?>

	<!-- ATTACHMENTS -->

	<?php
	if ($note->attachments)
	{
		?>
		<div class="usernote-attachments-bar">
			<?php
			foreach ($note->attachments as $file)
			{
				$pretty = $file->name;

				// in case the length of the file name exceeds 24 characters, place
				// the ellipsis in the middle of the word and take only the first
				// 11 characters and the last 10
				if (strlen($pretty) > 24)
				{
					$pretty = substr($pretty, 0, 11) . '...' . substr($pretty, -10);
				}

				?>
				<a href="<?php echo $file->uri; ?>" target="_blank" title="<?php echo $this->escape($file->name); ?>" class="usernote-attachment">
					<i class="fas fa-paperclip"></i>
					<?php echo $pretty; ?>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>

</div>
