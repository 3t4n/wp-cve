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

$reviews        = isset($displayData['reviews'])         ? $displayData['reviews']         : array();
$can_leave      = isset($displayData['canLeave'])        ? $displayData['canLeave']        : false;
$ordering_links	= isset($displayData['orderingLinks'])   ? $displayData['orderingLinks']   : false;
$id_service     = isset($displayData['id_service'])      ? $displayData['id_service']      : 0;
$id_employee    = isset($displayData['id_employee'])     ? $displayData['id_employee']     : 0;
$rev_sub_title  = isset($displayData['subtitle'])        ? $displayData['subtitle']        : '';
$dt_format      = isset($displayData['datetime_format']) ? $displayData['datetime_format'] : '';
$itemid         = isset($displayData['itemid'])          ? $displayData['itemid']          : null;

if ($id_service)
{
	// reviews for a service
	$col_name 	= 'id_service';
	$col_value 	= $id_service;
	$controller = 'servicesearch';
}
else
{
	// reviews for an employee
	$col_name 	= 'id_employee';
	$col_value 	= $id_employee;
	$controller = 'employeesearch';
}

if (is_null($itemid))
{
	// item id not provided, get the current one (if set)
	$itemid = JFactory::getApplication()->input->getInt('Itemid');
}

$config = VAPFactory::getConfig();

/**
 * Returns the mode used to load the reviews:
 * - [1] on scroll down
 * - [2] button click
 */
$reviews_load_mode = $config->getUint('revloadmode');

$MIN_COMMENT_LENGTH      = $config->getUint('revminlength');
$MAX_COMMENT_LENGTH      = $config->getUint('revmaxlength');
$REVIEW_COMMENT_REQUIRED = $config->getBool('revcommentreq');

$vik = VAPApplication::getInstance();

$reviews->rows = isset($reviews->rows) ? $reviews->rows : array();
$reviews->size = isset($reviews->size) ? $reviews->size : 0;

?>

<div class="vap-allreviews-intro">

	<div class="vap-allreviews-title">
		<h2><?php echo JText::translate('VAPREVIEWSTITLE'); ?></h2>
		<span><?php echo $rev_sub_title; ?></span>
	</div>

	<div class="vap-allreviews-actions">
		<?php
		foreach ($ordering_links as $link)
		{
			?>
			<a href="<?php echo JRoute::rewrite($link['uri']); ?>" class="vap-btn dark-gray vap-revord-link <?php echo ($link['active'] ? 'active' : ''); ?>">
				<i class="fas fa-<?php echo $link['mode'] == 'ASC' ? 'sort-amount-up-alt' : 'sort-amount-down'; ?>"></i>
				<span><?php echo $link['name']; ?></span>
			</a>
			<?php
		}
		?>
	</div>
</div>

<?php if ($can_leave) { ?>
	
	<div class="vap-postreview-block">
		
		<div class="vap-postreview-form" style="display: none;">
			<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&task=' . $controller . '.leavereview' . ($itemid ? '&Itemid=' . $itemid : '')); ?>" method="post" id="vaprevformpost">
				
				<div class="vap-postreview-top">
					
					<!-- Rating -->
					<div class="vap-postreview-ratingwrap">
						<div class="vap-postreview-label review-rating"><?php echo JText::translate('VAPPOSTREVIEWLBLRATING'); ?>*</div>
						<div class="vap-postreview-field">
							<div class="vap-rating-field">
								<?php for ($i = 1; $i <= 5; $i++) { ?>
									<div class="vap-rating-box rating-nostar" id="vaprating<?php echo $i; ?>"></div>
								<?php } ?>
							</div>
							<input type="hidden" name="rating" value="" class="required" id="vapreviewrating" />
						</div>
					</div>
					
					<!-- Title -->
					<div class="vap-postreview-titlewrap">
						<div class="vap-postreview-label review-title"><?php echo JText::translate('VAPPOSTREVIEWLBLTITLE'); ?>*</div>
						<div class="vap-postreview-field">
							<input type="text" name="title" value="" maxlength="64" size="32" class="required" id="vapreviewtitle" />
						</div>
					</div>

				</div>
				
				<!-- Comment -->
				<div class="vap-postreview-label review-comment">
					<?php echo JText::translate('VAPPOSTREVIEWLBLCOMMENT'); ?>
					<?php echo ($REVIEW_COMMENT_REQUIRED ? '*' : ''); ?>
				</div>
				
				<div class="vap-postreview-field">
					<div class="vap-postreview-commentarea">
						
						<textarea maxlength="<?php echo $MAX_COMMENT_LENGTH; ?>" name="comment" class="<?php echo $REVIEW_COMMENT_REQUIRED ? 'required' : ''; ?>" id="vapreviewcomment"></textarea>
						
						<div class="vap-postreview-charsleft">
							<span><?php echo JText::translate('VAPPOSTREVIEWCHARSLEFT'); ?>&nbsp;</span>
							<span id="vapcommentchars"><?php echo $MAX_COMMENT_LENGTH; ?></span>
						</div>

						<?php if ($MIN_COMMENT_LENGTH > 0) { ?>
							<div class="vap-postreview-minchars">
								<span><?php echo JText::translate('VAPPOSTREVIEWMINCHARS'); ?>&nbsp;</span>
								<span id="vapcommentminchars"><?php echo $MIN_COMMENT_LENGTH; ?></span>
							</div>
						<?php } ?>
					</div>
				</div>
			
				<input type="hidden" name="option" value="com_vikappointments" />
				<input type="hidden" name="task" value="<?php echo $controller; ?>.leavereview" />
				<input type="hidden" name="<?php echo $col_name; ?>" value="<?php echo $col_value; ?>" />
			</form>
		</div>
		
		<div class="vap-postreview-bottom">
			<button type="button" class="vap-btn blue" onClick="vapLeaveReview(this);"><?php echo JText::translate('VAPLEAVEREVIEWLINK'); ?></button>
		</div>
		
	</div>
	
<?php } ?>

<div class="vap-reviews-cont">
	<div class="vap-reviews-list">
		
		<?php
		foreach ($reviews->rows as $review)
		{
			$data = array(
				/**
				 * An object containing the review details.
				 *
				 * @var object
				 */
				'review' => $review,

				/**
				 * The date time format used to display when the review was created.
				 * If not provided, it will be used the default one (military format).
				 *
				 * @var string
				 */
				'datetime_format' => $dt_format,
			);

			/**
			 * The review block is displayed from the layout below:
			 * /components/com_vikappointments/layouts/review/default.php
			 * 
			 * If you need to change something from this layout, just create
			 * an override of this layout by following the instructions below:
			 * - open the back-end of your Joomla
			 * - visit the Extensions > Templates > Templates page
			 * - edit the active template
			 * - access the "Create Overrides" tab
			 * - select Layouts > com_vikappointments > review
			 * - start editing the default.php file on your template to create your own layout
			 *
			 * @since 1.6
			 */
			echo JLayoutHelper::render('review.default', $data);
		}
		?>

	</div>
</div>

<?php
if ($reviews_load_mode == 2 && $reviews->size > count($reviews->rows))
{
	// AJAX disabled, load reviews with the apposite buttons
	?>
	<div class="vap-reviews-load-wrap">
		<button class="vap-btn blue" onClick="loadMoreReviews();">
			<?php echo JText::translate('VAPREVIEWLOADMOREBTN'); ?>
		</button>
	</div>
	<?php
}
?>

<div id="vap-reviews-limit"></div>

<?php
JText::script('VAPREVIEWCOMMENTSHOWLESS');
JText::script('VAPREVIEWCOMMENTSHOWMORE');
JText::script('VAPSUBMITREVIEWLINK');
?>

<script>

	var LOAD_REVIEWS      = true;
	var REVIEWS_START_LIM = <?php echo count($reviews->rows); ?>;
	var ALL_LOADED        = <?php echo ($reviews->size > count($reviews->rows) ? 0 : 1); ?>;

	var reviewsValidator = null;

	jQuery(function($) {
		if (document.location.search.indexOf('revordby') != -1) {
			$('html,body').animate({
				scrollTop: $('.vap-allreviews-title').first().offset().top - 5,
			}, {
				duration: 'normal',
			});
		}

		// create reviews form validator
		reviewsValidator = new VikFormValidator('#vaprevformpost', 'vap-reviewfield-required');
		reviewsValidator.setLabel($('#vapreviewrating'), $('.vap-postreview-label.review-rating'));
		reviewsValidator.setLabel($('#vapreviewtitle'), $('.vap-postreview-label.review-title'));
		reviewsValidator.setLabel($('#vapreviewcomment'), $('.vap-postreview-label.review-comment'));
		// validate comment length
		reviewsValidator.addCallback(() => {
			const comment = $('#vapreviewcomment');

			if (REVIEW_COMMENT_REQUIRED && comment.hasClass('vap-reviewfield-required')) {
				// do not neet to validate comment again
				return false;
			}

			let val = comment.val();

			// make sure the user specified at least the minimum number of characters
			if (val.length > 0 && val.length < MIN_COMMENT_LENGTH) {
				reviewsValidator.setInvalid(comment);
				return false;
			}

			reviewsValidator.unsetInvalid(comment);
			return true;
		});

		<?php
		if ($reviews_load_mode == 1)
		{
			?>
			// load the reviews via AJAX when the scrollbar touches the limit
			$(window).scrollStopped(function() {
				if (LOAD_REVIEWS && isReviewsLimitReached()) {
					loadMoreReviews();  
				}
			});
			<?php
		}
		?>
	});
	
	function showMoreLessDescription(action, id) {
		var app = jQuery('#vaprevcomment' + id).text();
		jQuery('#vaprevcomment' + id).text(jQuery('#vaprevcomfull'+id).val());
		jQuery('#vaprevcomfull' + id).val(app);
		
		if (jQuery('#vaprevcomtype' + id).val() == 'more') {
			jQuery(action).text(Joomla.JText._('VAPREVIEWCOMMENTSHOWLESS'));
			jQuery('#vaprevcomtype' + id).val('less');
		} else {
			jQuery(action).text(Joomla.JText._('VAPREVIEWCOMMENTSHOWMORE'));
			jQuery('#vaprevcomtype' + id).val('more');
		}
	}
	
	function loadMoreReviews(attempt) {
		if (ALL_LOADED) {
			return;
		}

		if (!attempt) {
			attempt = 1;
		}
		
		LOAD_REVIEWS = false;

		UIAjax.do(
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=' . $controller . '.loadreviews' . ($itemid ? '&Itemid=' . $itemid : '')); ?>',
			{
				<?php echo $col_name; ?>: <?php echo $col_value; ?>,
				start: REVIEWS_START_LIM,
			},
			(resp) => {
				resp.reviews.forEach((rev) => {
					jQuery('.vap-reviews-list').append(rev);
				});
				
				REVIEWS_START_LIM += resp.reviews.length
				ALL_LOADED = (REVIEWS_START_LIM >= resp.size);
				
				if (ALL_LOADED) {
					jQuery('.vap-reviews-load-wrap').hide();
				}
				
				LOAD_REVIEWS = true;
			},
			(err) => {
				// enable reviews again
				LOAD_REVIEWS = true;
			}
		);
	}
	
	function isReviewsLimitReached() {
		var rev_limit_y = jQuery('#vap-reviews-limit').offset().top;
		var scroll = jQuery(window).scrollTop();
		var screen_height = jQuery(window).height();
		
		if (rev_limit_y - scroll - 150 > screen_height) {
			return false;
		}
		
		return true;
	}
	
	jQuery.fn.scrollStopped = function(callback) {
		var elem = jQuery(this), self = this;
		elem.scroll(function() {
			if (elem.data('scrollTimeout')) {
			  clearTimeout(elem.data('scrollTimeout'));
			}
			elem.data('scrollTimeout', setTimeout(callback, 250, self));
		});
	};
	
	// SUBMIT REVIEW
	
	function vapLeaveReview(action) {
		var form = jQuery('.vap-postreview-form');
		if (!form.is(':visible')) {
			form.slideDown();
			jQuery(action).text(Joomla.JText._('VAPSUBMITREVIEWLINK'));
			return;
		}
		
		if (reviewsValidator.validate()) {
			jQuery('#vaprevformpost').submit();
		}
	}
	
	function vapValidatesReviewFields() {
		var input = ['title', 'rating'];
		
		var resp = true;
		jQuery.each(input, function(i,v){
			var elem = jQuery('#vapreview'+v);
			if (elem.val().length > 0) {
				elem.parent().prev().removeClass('vap-reviewfield-required');
			} else {
				elem.parent().prev().addClass('vap-reviewfield-required');
				resp = false;
			}
		});
		
		if (resp && REVIEW_COMMENT_REQUIRED) {
			var elem = jQuery('#vapreviewcomment');
			if (elem.val().length > 0) {
				elem.parent().parent().prev().removeClass('vap-reviewfield-required');
			} else {
				elem.parent().parent().prev().addClass('vap-reviewfield-required');
				resp = false;
			}
		}
		
		if (resp) {
			var comment = jQuery('#vapreviewcomment');
			if (comment.val().length > 0 && comment.val().length < MIN_COMMENT_LENGTH) {
				comment.parent().parent().prev().addClass('vap-reviewfield-required');
				jQuery('.vap-postreview-minchars').addClass('vap-reviewfield-required');
				resp = false;
			} else {
				comment.parent().prev().removeClass('vap-reviewfield-required');
				jQuery('.vap-postreview-minchars').removeClass('vap-reviewfield-required');
			}
		}
		
		return resp;
	}

	var TO_RATE = true;
	var MAX_COMMENT_LENGTH = <?php echo $MAX_COMMENT_LENGTH; ?>;
	var MIN_COMMENT_LENGTH = <?php echo $MIN_COMMENT_LENGTH; ?>;
	var REVIEW_COMMENT_REQUIRED = <?php echo $REVIEW_COMMENT_REQUIRED ? 1 : 0; ?>;
	
	jQuery(document).ready(function() {

		jQuery('.vap-rating-box').on('click', function() {
			var id = jQuery(this).attr('id').split('vaprating')[1];
			
			jQuery('.vap-rating-box').removeClass('rating-nostar rating-hoverstar rating-yesstar');
			
			if (TO_RATE) {
				jQuery(this).addClass('rating-yesstar');
				jQuery(this).siblings().each(function() {
					if (jQuery(this).attr('id').split('vaprating')[1] < id) {
						jQuery(this).addClass('rating-yesstar');
					} else {
						jQuery(this).addClass('rating-nostar');
					}
				});
				
				jQuery('#vapreviewrating').val(id);
			} else {
				jQuery(this).addClass('rating-hoverstar');
				jQuery(this).siblings().each(function(){
					if (jQuery(this).attr('id').split('vaprating')[1] < id) {
						jQuery(this).addClass('rating-hoverstar');
					} else {
						jQuery(this).addClass('rating-nostar');
					}
				});
				
				jQuery('#vapreviewrating').val('');
			}
			
			TO_RATE = !TO_RATE
		});
		
		jQuery('.vap-rating-box').hover(function() {
			var id = jQuery(this).attr('id').split('vaprating')[1];
			
			if (TO_RATE) {
				jQuery('.vap-rating-box').removeClass('rating-nostar rating-hoverstar rating-yesstar');
				
				jQuery(this).addClass('rating-hoverstar');
				jQuery(this).siblings().each(function(){
					if (jQuery(this).attr('id').split('vaprating')[1] < id) {
						jQuery(this).addClass('rating-hoverstar');
					} else {
						jQuery(this).addClass('rating-nostar');
					}
				});
			}
			
		}, function() {
			
		});
		
		jQuery('#vapreviewcomment').on('keyup', function(e) {
			jQuery('#vapcommentchars').text((MAX_COMMENT_LENGTH - jQuery(this).val().length));       
		});

	});

</script>
