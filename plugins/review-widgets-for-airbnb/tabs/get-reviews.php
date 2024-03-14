<?php
defined('ABSPATH') or die('No script kiddies please!');
?>
<h1 class="ti-header-title"><?php echo __('Get Reviews', 'trustindex-plugin'); ?></h1>
<div class="ti-box">
<div class="ti-box-header"><?php echo sprintf(__('Want more %s reviews?', 'trustindex-plugin'), 'Airbnb'); ?></div>
<p><?php echo __('Get 100+ REAL Customer reviews, with only 3 minutes of work, without developer knowledge...', 'trustindex-plugin'); ?></p>
<a href="https://wordpress.trustindex.io/collect-reviews/?source=wpcs-airbnb" target="_blank" class="ti-btn"><?php echo __('DOWNLOAD OUR FREE GUIDE', 'trustindex-plugin'); ?></a>
</div>
<?php if (class_exists('Woocommerce')): ?>
<div class="ti-box">
<div class="ti-box-header"><?php echo __('Collect reviews automatically for your WooCommerce shop', 'trustindex-plugin'); ?></div>
<?php if (!class_exists('TrustindexCollectorPlugin')): ?>
<p><?php echo sprintf(__("Download our new <a href='%s' target='_blank'>%s</a> plugin and get features for free!", 'trustindex-plugin'), 'https://wordpress.org/plugins/customer-reviews-collector-for-woocommerce/', 'Customer Reviews Collector for WooCommerce'); ?></p>
<?php endif; ?>
<ul class="ti-check-list">
<li><?php echo __('Send unlimited review invitations for free', 'trustindex-plugin'); ?></li>
<li><?php echo __('E-mail templates are fully customizable', 'trustindex-plugin'); ?></li>
<li><?php echo __('Collect reviews on 100+ review platforms (Google, Facebook, Yelp, etc.)', 'trustindex-plugin'); ?></li>
</ul>
<?php if (class_exists('TrustindexCollectorPlugin')): ?>
<a href="?page=customer-reviews-collector-for-woocommerce%2Fadmin.php&tab=settings" class="ti-btn">
<?php echo __('Collect reviews automatically', 'trustindex-plugin'); ?>
</a>
<?php else: ?>
<a href="https://wordpress.org/plugins/customer-reviews-collector-for-woocommerce/" target="_blank" class="ti-btn">
<?php echo __('Download plugin', 'trustindex-plugin'); ?>
</a>
<?php endif; ?>
</div>
<?php endif; ?>