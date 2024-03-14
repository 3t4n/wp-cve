<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
if (directorypress_is_reviews_allowed() && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_position'] == 'notab'): ?>
	<div id="comments-reviews">
		<?php comments_template( '', true ); ?>
	</div>
<?php endif; ?>

 