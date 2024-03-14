<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$app_title = isset($this->app->app_config['nts_app_title']) ? $this->app->app_config['nts_app_title'] : 'Store Locator';
$app_title_pro = 'Locatoraid Pro';
?>
<?php if( 0 ) : ?>
<div class="hc-fs3">
<span class="dashicons dashicons-star-filled hc-olive"></span> <a target="_blank" href="http://www.locatoraid.com/order/"><strong><?php echo $app_title_pro; ?></strong></a> with nice features like bulk locations upload, CSV export, custom fields, location categories and more!
</div>
<?php endif; ?>

<?php if( 0 ) : ?>
<div class="notice notice-success inline" style="padding: .5em 1em;">
	<p>
	Interested in more features? Check out the <a href="<?= admin_url('admin.php?page=locatoraid&hca=promo'); ?>">Add-ons</a> page.
	</p>
</div>
<?php endif; ?>

<?php if( 1 ) : ?>
<div class="notice notice-success inline" style="padding: .5em 1em;">
	<p>
	Interested in more features? Upgrade to <a target="_blank" href="https://www.locatoraid.com/order/"><strong>Locatoraid Pro</strong></a> or check out the <a href="<?= admin_url('admin.php?page=locatoraid&hca=promo'); ?>">Add-ons</a> page!
	</p>
</div>
<?php endif; ?>


<?php if( 0 ) : ?>
<div class="notice notice-success inline" style="padding: 1em;">
	<p style="margin-bottom: .25em;">
	Interested in <a target="_blank" href="https://www.locatoraid.com/upload-export/">Bulk Import & Export</a>, 
	<a target="_blank" href="https://www.locatoraid.com/custom-fields/">Custom Fields</a>, 
	<a target="_blank" href="https://www.locatoraid.com/custom-map-icons/">Custom Map Icons</a>,
	<a target="_blank" href="https://www.locatoraid.com/products/">Location Categories</a> and more?
	</p>

	<p style="font-size: 1.1em;">
	Upgrade to <a target="_blank" href="http://www.locatoraid.com/order/"><strong>Locatoraid Pro</strong></a>!
	</p>
</div>
<?php endif; ?>

<?php if( 0 ) : ?>
<div class="notice notice-success inline" style="padding: 1em;">
	&#9733; <a target="_blank" href="http://www.locatoraid.com/order/"><strong>Locatoraid Pro</strong></a> with nice features like 
	<a target="_blank" href="https://www.locatoraid.com/upload-export/">Bulk Import & Export</a>, 
	<a target="_blank" href="https://www.locatoraid.com/custom-fields/">Custom Fields</a>, 
	<a target="_blank" href="https://www.locatoraid.com/custom-map-icons/">Custom Map Icons</a>,
	<a target="_blank" href="https://www.locatoraid.com/products/">Location Categories</a> and more!
</div>
<?php endif; ?>
