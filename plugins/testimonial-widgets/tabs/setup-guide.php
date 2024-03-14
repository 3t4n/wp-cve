<?php
defined('ABSPATH') or die('No script kiddies please!');
?>
<div id="testimonial-widgets-plugin-settings-page" class="ti-toggle-opacity">
<h1 class="ti-free-title">
<?php echo TrustindexTestimonialsPlugin::___("%s", ["WP Testimonials"]); ?>
<a href="https://www.trustindex.io" target="_blank" title="Trustindex" class="ti-pull-right">
<img src="<?php echo esc_url($trustindex_testimonials_pm->get_plugin_file_url('static/img/trustindex.svg')); ?>" />
</a>
</h1>
<div class="container_wrapper">
<div class="container_cell" id="container-main">
<?php $trustindex_testimonials_pm->generate_page_menu(); ?>
<?php
$reviews_reached = 3 <= count($trustindex_testimonials_pm->get_reviews()) ? true : false;
$has_widget = count($trustindex_testimonials_pm->get_widgets()) ? true : false;
$visited_get_reviews = (get_option( 'wp-testimonials-visited-get-reviews') === 'yes');
?>

<div class="ti-box">
<div class="ti-header text-center"><?php echo TrustindexTestimonialsPlugin::___('Welcome to the %s plugin guide.', ["WP Testimonials"]); ?></div>
<div class="ti-testimonials-setup-guide">
<a href="edit.php?post_type=wpt-testimonial">
<div class="ti-step<?php if ($reviews_reached) : ?> done<?php endif; ?>">
<div class="ti-icon">1</div>
<div class="ti-details">
<h2><?php echo TrustindexTestimonialsPlugin::___("Upload your customer's testimonials"); ?></h2>
<p><?php echo TrustindexTestimonialsPlugin::___("Write at least 3 testimonials to display them in your website nicely"); ?></p>
</div>
</div>
</a>
<a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/create-widget.php'; ?>">
<div class="ti-step<?php if ($has_widget) : ?> done<?php endif; ?><?php if (!$reviews_reached) : ?> disabled<?php endif; ?>">
<div class="ti-icon">2</div>
<div class="ti-details">
<h2><?php echo TrustindexTestimonialsPlugin::___('Display your testimonials'); ?></h2>
<p><?php echo TrustindexTestimonialsPlugin::___('You can select from %s widget type and %s widget style', ["21", "25"]); ?></p>
</div>
</div>
</a>
<a href="https://www.trustindex.io/ti-redirect.php?a=wagner2&c=wp-testi-1">
<div class="ti-step<?php if ($visited_get_reviews && $has_widget && $reviews_reached) : ?> done<?php endif; ?><?php if (!$has_widget || !$reviews_reached) : ?> disabled<?php endif; ?>">
<div class="ti-icon">3</div>
<div class="ti-details">
<h2><?php echo TrustindexTestimonialsPlugin::___('Create testimonials widget with real customer reviews'); ?></h2>
<p><?php echo TrustindexTestimonialsPlugin::___("Add reviews from %s and other platforms to your website. %s review platforms to choose from. Create an account and make your first widget.", [ 'Google, Facebook, Trustpilot, Airbnb, Booking, Tripadvisor', "134" ]); ?></p>
</div>
</div>
</a>
</div>
</div>
</div>
</div>
</div>