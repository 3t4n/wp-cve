<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

$categories = $this->categories;
$vri_tn = $this->vri_tn;
$navig = $this->navig;

$pshowdescr = VikRequest::getInt('showdescr', 0, 'request');
?>

<div class="vri-page-content vri-categories-list">
	<div class="vri-categories-list-inner">
	<?php
	foreach ($categories as $category) {
		$link_to_items = JRoute::rewrite('index.php?option=com_vikrentitems&view=itemslist&category_id=' . $category['id'])
		?>
		<div class="vri-category-wrap">
			<div class="vri-category-inner">
			<?php
			if (!empty($category['img'])) {
				?>
				<div class="vri-category-img-container">
					<a href="<?php echo $link_to_items; ?>">
						<img src="<?php echo VRI_ADMIN_URI . 'resources/' . $category['img']; ?>" alt="<?php echo $this->escape($category['name']); ?>" />
					</a>
				</div>
				<?php
			}
			?>
				<div class="vri-category-info-container">
					<div class="vri-category-name">
						<h4>
							<a href="<?php echo $link_to_items; ?>"><?php echo $category['name']; ?></a>
						</h4>
					</div>
				<?php
				if ($pshowdescr > 0) {
					?>
					<div class="vri-category-description">
						<?php echo $category['descr']; ?>
					</div>
					<?php
				}
				?>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	</div>
<?php
// pagination
if (!empty($navig)) {
	?>
	<div class="vri-pagination"><?php echo $navig; ?></div>
	<?php
}
?>
</div>
