<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
wp_enqueue_script('trustindex-js', 'https://cdn.trustindex.io/loader.js', [], false, true);
?>
<div class="ti-box">
<div class="ti-row">
<div class="ti-col-6">
<h3><?php echo TrustindexWoocommercePlugin::___('Upgrade to Pro Version and Get More Features - only %d USD / Year', [ 65 ]); ?>!</h1>
<p><?php echo TrustindexWoocommercePlugin::___('With the Pro functions of Trustindex you can boost significally your sales, and can easily manage all your reviews in 1 place!'); ?></p>
<p><?php echo TrustindexWoocommercePlugin::___('%d+ businesses use Trustindex to increase SEO, trust and sales using customer reviews!', [ 200.000 ]); ?></p>
<a class="btn-text" href="https://www.trustindex.io/ti-redirect.php?a=sys&c=wp-woo-3" target="_blank"><?php echo TrustindexWoocommercePlugin::___('Try PRO Features for FREE'); ?></a>
<br />
<h3><?php echo TrustindexWoocommercePlugin::___('5 of our greatest functions that guarantee to increase your sales:'); ?></h3>
<ul class="ti-check" style="margin-bottom: 15px">
<li>
<strong><?php echo TrustindexWoocommercePlugin::___('Unlimited Number of Widgets'); ?></strong>
<?php echo TrustindexWoocommercePlugin::___('Display your reviews with our beautiful, easy-to-integrate and mobile responsive widgets. Choose from %d layouts and %d pre-designed styles.', [ 40, 25 ]); ?>
</li>
<li>
<strong><?php echo TrustindexWoocommercePlugin::___('Unlimited Number of Review Invitation Email'); ?></strong>
<?php echo TrustindexWoocommercePlugin::___('You can send to your customers unlimited review invitation email every month.'); ?>
</li>
<li>
<strong><?php echo TrustindexWoocommercePlugin::___('Review Image Generator'); ?></strong>
<?php echo TrustindexWoocommercePlugin::___('Turn your reviews to breathtaking social content with the help of our 3.000.000 unique background photos.'); ?>
</li>
<li>
<strong><?php echo TrustindexWoocommercePlugin::___('Company Email Signatures'); ?></strong>
<?php echo TrustindexWoocommercePlugin::___('Create email signatures containing review summary widgets and integrate them into your newsletters, so your clients can see your greatest reviews more.'); ?>
</li>
<li>
<strong><?php echo TrustindexWoocommercePlugin::___("%d Review Platform in 1 Place", [ 128 ]); ?></strong>
<?php echo TrustindexWoocommercePlugin::___('Display and manage all your reviews in one place. Boost your online reputation with our review summary page.'); ?>
</li>
</ul>
<a class="btn-text" href="https://www.trustindex.io/ti-redirect.php?a=sys&c=wp-woo-3" target="_blank"><?php echo TrustindexWoocommercePlugin::___('Try PRO Features for FREE'); ?></a>
</div>
<div class="ti-col-6">
<div src='https://cdn.trustindex.io/loader.js?76afafc10ad42261d7587d98bf'></div>
</div>
</div>
</div>