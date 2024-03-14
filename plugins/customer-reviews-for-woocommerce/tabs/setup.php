<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$current_step = isset($_GET['step']) ? intval(sanitize_text_field($_GET['step'])) : 0;
$trigger_event = get_option($trustindex_woocommerce->get_option_name('trigger-event'));
$widget_setted_up = get_option($trustindex_woocommerce->get_option_name('widget-setted-up'));
$ti_sub_id = $trustindex_woocommerce->is_trustindex_connected();
if($current_step)
{
if($current_step > 1 && !$ti_sub_id && $current_step != 5)
{
$current_step = 1;
}
}
if(isset($_GET['recreate']))
{
$trustindex_woocommerce->uninstall();
header('Location: admin.php?page=' . sanitize_text_field($_GET['page']));
exit;
}
if(isset($_GET['toggle_campaign']))
{
update_option($trustindex_woocommerce->get_option_name('campaign-active'), $trustindex_woocommerce->is_campaign_active() ? 0 : 1, false);
header("Location: admin.php?page=$_page&tab=setup");
exit;
}
?>
<?php /*
<ul class="ti-free-steps ti-setup-guide-steps">
<li class="<?php echo $ti_sub_id ? "done" : 'active'; ?><?php if(intval($current_step) == 1): ?> current<?php endif; ?>" href="?page=<?php echo $_page; ?>&tab=setup&step=1">
<span>1</span>
<?php echo TrustindexWoocommercePlugin::___('Review Summary Page'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $ti_sub_id ? ($trigger_event ? "done" : "active") : ""; ?><?php if(intval($current_step) == 2): ?> current<?php endif; ?>" href="?page=<?php echo $_page; ?>&tab=setup&step=2">
<span>2</span>
<?php echo TrustindexWoocommercePlugin::___('Invitation Settings'); ?>
</li>
</ul>
*/ ?>
<?php if(!$current_step): ?>
<div class="ti-box">
<div class="ti-header text-center"><?php echo TrustindexWoocommercePlugin::___('Welcome to the Trustindex plugin guide.'); ?></div>
<p class="text-center"><?php echo TrustindexWoocommercePlugin::___('Collect hundreds of real customer reviews fast and easily.<br />Increase your SEO, trust and sales! It only takes 5 minutes!'); ?>
<div class="ti-woocommerce-setup-guide">
<a href="?page=<?php echo $_page; ?>&tab=setup&step=1">
<div class="ti-step<?php if($ti_sub_id): ?> done<?php endif; ?>">
<div class="ti-icon">1</div>
<div class="ti-details">
<h2><?php echo TrustindexWoocommercePlugin::___('Create Your Review Summary Page'); ?></h2>
<p><?php echo TrustindexWoocommercePlugin::___("Set up your company's page, where you can collect your reviews. All your reviews will be listed on this page."); ?></p>
</div>
</div>
</a>
<a href="?page=<?php echo $_page; ?>&tab=setup&step=2">
<div class="ti-step<?php if($trigger_event): ?> done<?php endif; ?><?php if(!$ti_sub_id): ?> disabled<?php endif; ?>">
<div class="ti-icon">2</div>
<div class="ti-details">
<h2><?php echo TrustindexWoocommercePlugin::___('Automate Your Review Invitations'); ?></h2>
<p><?php echo TrustindexWoocommercePlugin::___('Set up automation of your invitations once to get a continuous flow of verified reviews.'); ?></p>
</div>
</div>
</a>
<div class="ti-step ti-activate-step<?php if($trustindex_woocommerce->is_campaign_active()): ?> done<?php endif; ?><?php if(!$trigger_event): ?> disabled<?php endif; ?>">
<div class="ti-icon">3</div>
<div class="ti-details">
<h2><?php echo TrustindexWoocommercePlugin::___('Turn on the Review Invitations'); ?></h2>
<p><?php echo TrustindexWoocommercePlugin::___('Boost your sales with customer reviews. Give your customers the confidence to click "buy now".'); ?></p>
</div>
<a href="?page=<?php echo $_page; ?>&tab=setup&toggle_campaign" class="ti-checkbox-toggle"></a>
</div>
<a href="?page=<?php echo $_page; ?>&tab=setup&step=4">
<div class="ti-step<?php if($trustindex_woocommerce->get_previous_orders()->total == 0): ?> done<?php endif; ?><?php if(!$trigger_event): ?> disabled<?php endif; ?>">
<div class="ti-icon">4</div>
<div class="ti-details">
<h2><?php echo TrustindexWoocommercePlugin::___('Invite Your Past Customers to Write Reviews'); ?></h2>
<p><?php echo TrustindexWoocommercePlugin::___('Kickstart your review collection by inviting your past customers easily.'); ?></p>
</div>
</div>
</a>
<a href="?page=<?php echo $_page; ?>&tab=setup&step=5">
<div class="ti-step<?php if($widget_setted_up): ?> done<?php endif; ?><?php if(!$trigger_event): ?> disabled<?php endif; ?>">
<div class="ti-icon">5</div>
<div class="ti-details">
<h2><?php echo TrustindexWoocommercePlugin::___('Add Review Widgets to Your Website'); ?></h2>
<p><?php echo TrustindexWoocommercePlugin::___('87%% of website visitors are more likely to make a purchase after reading Trustindex reviews on-site. %d widget types, %d widget styles available.', [ 40, 25 ]); ?></p>
</div>
</div>
</a>
<a href="?page=<?php echo $_page; ?>&tab=more_features">
<div class="ti-step">
<div class="ti-icon">6</div>
<div class="ti-details">
<h2><?php echo TrustindexWoocommercePlugin::___('Upgrade to Pro Version and Get More Features - only %d USD / Year', [ 65 ]); ?></h2>
<p><?php echo TrustindexWoocommercePlugin::___('With the Pro functions of Trustindex you can boost significally your sales, and can easily manage all your reviews in 1 place!'); ?></p>
</div>
</div>
</a>
</div>
</div>
<?php elseif($current_step == 1): ?>
<?php include('setup' . DIRECTORY_SEPARATOR . 'setup_trustindex.php'); ?>
<?php elseif($current_step == 2): ?>
<?php include('setup' . DIRECTORY_SEPARATOR . 'setup_campaign.php'); ?>
<?php elseif($current_step == 4): ?>
<?php include('setup' . DIRECTORY_SEPARATOR . 'previous_invites.php'); ?>
<?php elseif($current_step == 5): ?>
<?php if(!$trustindex_woocommerce->is_trustindex_connected()): ?>
<div class="ti-notice notice-warning" style="margin-left: 0">
<p>
<?php echo TrustindexWoocommercePlugin::___("Finish setup review summary page first!"); ?>
 <a href="?page=<?php echo esc_attr($_GET['page']); ?>&tab=setup"><?php echo TrustindexWoocommercePlugin::___("Setup Guide"); ?></a>
</p>
</div>
<?php else: ?>
<?php include('setup' . DIRECTORY_SEPARATOR . 'show_reviews.php'); ?>
<?php endif; ?>
<?php endif; ?>