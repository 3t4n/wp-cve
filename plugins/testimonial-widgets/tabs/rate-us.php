<?php
defined('ABSPATH') or die('No script kiddies please!');
wp_enqueue_script('trustindex_testimonials_loader_js', 'https://cdn.trustindex.io/' . 'loader.js', [], false, true);
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
<div class="ti-box rate-us en">
<div class="ti-box-head">
<div class="ti-row">
<div class="ti-col">
<span class="wp-heading-inline"><?php echo TrustindexTestimonialsPlugin::___('Please help us by reviewing our Plugin.'); ?></span>
<p><?php echo TrustindexTestimonialsPlugin::___("We've spent a lot of time developing this plugin. If you use the plugin, you can still support us by leaving a review!"); ?></p>
<p><?php echo TrustindexTestimonialsPlugin::___('Thank you in advance!'); ?></p>
</div>
<div class="ti-col-auto rate-us-wrapper">
<a class="btn-text btn-lg" href="https://wordpress.org/support/plugin/<?php echo esc_attr($trustindex_testimonials_pm->get_plugin_slug()); ?>/reviews/?rate=5#new-post" target="_blank"><?php echo TrustindexTestimonialsPlugin::___('Click here to rate us!'); ?></a>
</div>
</div>
</div>
<hr>
<div class="ti-row">
<div class="ti-col-12">
<div src="<?php echo 'https://cdn.trustindex.io/' . 'loader.js?1f2d73951f873073bd54ce1956'; ?>"></div>
</div>
</div>
</div>
</div>
</div>
</div>