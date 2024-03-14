<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

if ($listing->title()):
?>
	<header class="directorypress-listing-title clearfix">
		<h2 itemprop="name"><?php echo esc_html($listing->title()); ?></h2>			
	</header>
<?php endif; ?>

 