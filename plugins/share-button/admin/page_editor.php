<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use MaxButtons\maxButton as maxButton;
use MaxButtons\maxBlocks as maxBlocks;

$button = new maxButton(); // this loads the maxblocks template files


$title = (Install::isPro()) ? __('Wordpress Share Buttons PRO', 'mbsocial') : __('WordPress Share Buttons','mbsocial');
$header = array(
		'title' => $title,
);

$mainclass = 'maxbuttons-social-editor';
$admin = mbSocial()->admin();
$admin->header($header);


$collection_ids = collections::getCollections();
$number_of_collections = count($collection_ids);

$the_collection_id = isset($_GET['social_id']) ? intval($_GET['social_id']) : false;

if ($the_collection_id === false && $number_of_collections > 0 )
{
	$the_collection_id = $collection_ids[0]; // load the first one
}
else {
	$collection_id = 0;
}
?>

<form class='mb-ajax-form save-on-key' data-action='save-share'>


<?php if($number_of_collections > 0) : ?>
<div class='help-side collection_switcher'>
	<h3><?php _e('Your Social Groups','mbsocial'); ?></h3>
	<?php //

			echo '<div class="item heading"><span>' . __('name', 'mbsocial') . '</span><span>' .  __('no buttons', 'mbsocial') . '</span><span>' . __('shortcode', 'mbsocial') . '</span></div>';

	 		foreach($collection_ids as $id) {
			$collection = new collection($id);
			$genBlock = $collection->getBlock('generalBlock');
			$name = $genBlock->getValue('name');
			$nwBlock = $collection->getBlock('networkBlock');

			$networks_number = count($nwBlock->getValue('network_active')); // )

			$name = ( strlen($name) > 0) ? $name : '#' .  $id;
			$active = ($id == $the_collection_id) ? 'active' : '';



			if ( Install::isPRO() )
			{
				$link = '<a href="' . add_query_arg('social_id', $id) . '"><span>' . $name . '</span>';
				$linkend = '</a>';
			}
			else {
				$link = '<span>' . $name . '</span>';
				$linkend = '';
			}
			echo  '<div class="item ' . $active . '">';
			echo $link;
			echo '<span> [' . $networks_number . ' buttons] </span>';
			echo '<span> [maxsocial id="' . $id . '"] </span>';
			echo $linkend;
			echo '</div>';

			}

			if ( Install::isPRO() )
			{
				$link = '<a href="' . add_query_arg('social_id', 0) . '" title="' . __('Add', 'mbsocial') . '"> <span class="plus">+</span> ' . __('Add', 'mbsocial') . '</a>';
			}
			else {
				$link =  '<a><span class="plus">+</span> ' . __('Add', 'mbsocial') . '</a>';
			}

			echo '<span class="item new">' . $link . '</span>';


		if (! Install::isPro() )
		{ ?>
	 		<div class='forpro overlay'><div><?php echo $admin->getProMessage(); ?></div></div>
		<?php } ?>
</div>
<?php endif; ?>

	<?php if ($the_collection_id > 0):
		$collection_id = $the_collection_id;
		 ?>

	<div class='mb-message shortcode'>
		<?php printf(__('Shortcode for manual use: [maxsocial id="%s"]','mbsocial'), $collection_id); ?>
	</div>
	<?php endif; ?>

	<input type='hidden' name="collection_id" value="<?php echo $collection_id ?>" />
	<input type='hidden' name="form_post" value="true" />

	<div class="form-actions ">
		<button type="button" class="button-primary button-save mb-ajax-submit" data-action='save_collection'><?php _e('Save', 'mbsocial') ?></button>
		<?php if ($collection_id > 0 &&  Install::isPro()): ?>
			<button type='button' class='button remove maxmodal' data-modal='modal_remove'><?php _e('Delete', 'mbsocial'); ?></button>
		<?php endif; ?>
	</div>


	<?php include('share_editor.php'); ?>


	<div class="form-actions">
		<button type="button" class="button-primary button-save mb-ajax-submit" data-action='save_collection'><?php _e('Save', 'mbsocial') ?></button>
	</div>

	<div id='modal_remove' class='maxmodal-data'>
		<div class='title'><?php _e('Delete Social Group', 'mbsocial'); ?></div>
		<span class="content"><p><?php _e("You are about to permanently remove this Social Group. Are you sure?", "mbsocial"); ?></p></span>
			<div class='controls'>
				<button type="button" class='button-primary mb-ajax-action' data-action='remove-collection' data-param='<?php echo $collection_id ?>'>
				<?php _e('Yes','mbsocial'); ?></button>

				<a class="modal_close button-primary"><?php _e("No", "mbsocial"); ?></a>

			</div>

	</div> <!-- modal -->
	<?php $admin->footer(); ?>
</form>
