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

$review    = isset($displayData['review'])          ? $displayData['review']          : false;
$dt_format = isset($displayData['datetime_format']) ? $displayData['datetime_format'] : 'Y-m-d H:i';

if (!$review)
{
	// do not proceed if the review is not provided
	return;
}

// Alter this value in case you want to increase/decrease
// the total number of characters shown for each review.
// By default, this value is set to 300 characters.
$COMMENT_DESC_LENGTH = 300;

$review_comment = $review->comment;          

if (strlen($review->comment) > $COMMENT_DESC_LENGTH + 3)
{
	$review_comment = trim(mb_substr($review_comment, 0, $COMMENT_DESC_LENGTH, 'UTF-8'), '. ') . ' ...';
}

if (!empty($review->image))
{
	$avatar = VAPCUSTOMERS_AVATAR_URI . $review->image;
}
else
{
	$avatar = VAPASSETS_URI . 'css/images/default-profile.png';
}

$vik = VAPApplication::getInstance();

// use customer timezone
$tz = VikAppointments::getUserTimezone()->getName();

?>

<div class="vap-review-line <?php echo $vik->getThemeClass('background'); ?>">

	<!-- LEFT BOX -->
	<div class="vap-review-left">

		<div class="vap-review-profile">

			<div class="vap-review-userimage">
				<img src="<?php echo $avatar; ?>" />
			</div>

			<div class="vap-review-username">
				<?php echo $review->name; ?>
			</div>

		</div>

	</div>

	<!-- CENTER BOX -->
	<div class="vap-review-center">

		<div class="vap-review-header">

			<div class="vap-review-title">
				<?php echo $review->title; ?>
			</div>

			<div class="vap-review-rating">
				<?php
				// Display the rating stars through this helper method.
				// Set the argument to false to use FontAwesome icons
				// in place of the images.
				echo JHtml::fetch('vikappointments.rating', $review->rating, $image = true);
				?>
			</div>

			<div class="vap-review-date">
				<?php echo JHtml::fetch('date', $review->timestamp, $dt_format, $tz); ?>
			</div>

		</div>

		<div class="vap-review-comment" id="vaprevcomment<?php echo $review->id; ?>">
			<?php echo $review_comment; ?>
		</div>

		<div class="vap-review-morecomment">
			<?php
			if (strlen($review_comment) != strlen($review->comment))
			{
				?>
				<a href="javascript:void(0)" onClick="showMoreLessDescription(this, <?php echo $review->id; ?>);">
					<?php echo JText::translate('VAPREVIEWCOMMENTSHOWMORE'); ?>
				</a>

				<input type="hidden" id="vaprevcomtype<?php echo $review->id; ?>" value="more" />
				<input type="hidden" id="vaprevcomfull<?php echo $review->id; ?>" value="<?php echo $this->escape($review->comment); ?>" />
				<?php
			}
			?>
		</div>

	</div>

</div>
