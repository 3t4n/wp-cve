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
 * @param 	string 	 id         An optional ID to be set for the card.
 * @param 	string 	 class      An additional class to be used (optional).
 * @param 	string 	 image      Either the source image or the HTML itself.
 * @param 	string 	 badge      The text to display within the badge icon in the summary.
 * 								It is possible to specify plain text or HTML.
 * @param 	string 	 primary    The primary text to be displayed in the summary.
 * @param 	string 	 secondary  The secondary text to be displayed in the summary.
 * @param 	string 	 edit       True to display the EDIT button. In case a string was
 * 								passed, it will be used as callback when the button is clicked.
 */
$id        = isset($displayData['id'])        ? $displayData['id']        : null;
$class     = isset($displayData['class'])     ? $displayData['class']     : null;
$image     = isset($displayData['image'])     ? $displayData['image']     : null;
$badge     = isset($displayData['badge'])     ? $displayData['badge']     : null;
$primary   = isset($displayData['primary'])   ? $displayData['primary']   : '';
$secondary = isset($displayData['secondary']) ? $displayData['secondary'] : '';
$edit      = isset($displayData['edit'])      ? $displayData['edit']      : null;
$editText  = isset($displayData['editText'])  ? $displayData['editText']  : JText::translate('VAPEDIT');
$editClass = isset($displayData['editClass']) ? $displayData['editClass'] : '';

?>
	
<div
	class="vap-card<?php echo $class ? ' ' . $class : ''; ?>"
	<?php echo $id ? 'id="' . $id . '"' : ''; ?>
>

	<div class="vap-card-image" style="<?php echo $image ? '' : 'display:none;'; ?>">
		<?php
		if ($image)
		{
			if (preg_match("/^<img/", $image))
			{
				echo $image;
			}
			else
			{
				?><img src="<?php echo $image; ?>" /><?php
			}
		}
		?>
	</div>

	<div class="vap-card-summary">
		
		<?php
		if ($badge)
		{
			?>
			<div class="card-badge-icon">
				<?php echo $badge; ?>
			</div>
			<?php
		}
		?>

		<div class="card-text">
			<div class="card-text-primary"><?php echo $primary ?></div>

			<div class="card-text-secondary"><?php echo $secondary ?></div>
		</div>

		<?php
		if ($edit)
		{
			if (is_string($edit))
			{
				$onclick = ' onclick="' . $this->escape($edit) . '"';
			}
			else if (is_numeric($edit))
			{
				$onclick = ' data-id="' . $edit . '"';
			}
			else
			{
				$onclick = '';
			}

			?>
			<button type="button" class="btn card-edit<?php echo $editClass ? ' ' . $editClass : ''; ?>"<?php echo $onclick; ?>>
				<?php echo $editText; ?>
			</button>
			<?php
		}
		?>

	</div>

</div>
