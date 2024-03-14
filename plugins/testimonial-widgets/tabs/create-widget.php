<?php
include(plugin_dir_path(__FILE__) . "create-widget-header.php");
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
<div class="tab-setup_no_reg">
<ul class="ti-free-steps">
<li class="<?php echo $selected || $widget['saved'] >= 1 ? "done" : "active"; ?><?php if ($current_step == 1) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=1&id=<?php echo esc_attr($id); ?>">
<span>1</span>
<?php echo TrustindexTestimonialsPlugin::___('Select testimonials'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $style_id || $widget['saved'] >= 2 ? "done" : ($selected || $widget['saved'] == 1 ? "active" : ""); ?><?php if ($current_step == 2) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=2&id=<?php echo esc_attr($id); ?>&selected">
<span>2</span>
<?php echo TrustindexTestimonialsPlugin::___('Select Layout'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo ($widget['2'] == '17' || $widget['2'] == '21') ? '' : ($scss_set || $widget['saved'] >= 3 ? "done" : ($style_id || $widget['saved'] == 2 ? "active" : "")); ?><?php if ($current_step == 3) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=3&id=<?php echo esc_attr($id); ?>&selected<?php echo $widget['2'] ? "&style_id=" . esc_attr($widget['2']) : ""; ?>">
<span>3</span>
<?php echo TrustindexTestimonialsPlugin::___('Select Style'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $widget_setted_up || $widget['saved'] >= 4 ? "done" : ($scss_set || $widget['saved'] == 3 ? "active" : ""); ?><?php if ($current_step == 4) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=4&id=<?php echo esc_attr($id); ?>&selected<?php echo $widget['2'] ? "&style_id=" . esc_attr($widget['2']) : ""; ?><?php echo $widget['3'] ? "&scss_set=" . esc_attr($widget['3']) : ""; ?>">
<span>4</span>
<?php echo TrustindexTestimonialsPlugin::___('Setup widget'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $widget['saved'] == 5 ? "done" : ($widget_setted_up || $widget['saved'] == 4 ? "active" : ""); ?><?php if ($current_step == 5) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=5&&id=<?php echo esc_attr($id); ?>selected&setup_widget<?php echo $widget['2'] ? "&style_id=" . esc_attr($widget['2']) : ""; ?><?php echo $widget['3'] ? "&scss_set=" . esc_attr($widget['3']) : ""; ?>">
<span>5</span>
<?php echo TrustindexTestimonialsPlugin::___('Get shortcode'); ?>
</li>
</ul>
<?php if ($current_step == 1) : ?>
<hr>
<h1 class="ti-free-title">
1. <?php echo TrustindexTestimonialsPlugin::___('Select testimonials'); ?>
</h1>
<div class="ti-box">

<br />
<div class="ti-row">
<div class="ti-col">
<?php /*
<?php if ($current_step == 1) : ?>
*/ ?>
<span class="wp-heading-inline"><?php echo TrustindexTestimonialsPlugin::___('Add widget name'); ?></span>

<?php /*
<?php else : ?>
<span class="wp-heading-inline"><?php echo TrustindexTestimonialsPlugin::___('Add widget name'); ?></span>
 <a href="<?php echo esc_url('admin.php?page=' . $trustindex_testimonials_pm->get_plugin_slug() . '/tabs/create-widget.php'); ?>" class="button btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Add new"); ?></a>
<a href="/wp-admin/admin.php?page=testimonial-widgets/tabs/index-widget.php" class="button btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Back to list"); ?></a>
<a href="<?php echo esc_url('admin.php?page=' . $trustindex_testimonials_pm->get_plugin_slug() . '/tabs/index-widget.php&duplicate_widget=' . $id); ?>" class="button btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Duplicate widget"); ?></a>
<?php endif; ?>
*/ ?>
</div>
</div>

<br />
<div class="ti-row">
<div class="ti-col">
<input class="form-control" placeholder="<?php echo 'Widget - ' . esc_attr($next_widget_id); ?>" id="widget-name" type="text" data-default-name="<?php echo 'Widget - ' . esc_attr($next_widget_id); ?>" value="<?php echo ($id && $widget_name) ? esc_attr($widget_name) : ''; ?>" <?php echo $current_step > 1 ? 'disabled' : ''; ?>>
</div>
</div>
<?php if ($name_missing): ?>
<div class="ti-row">
<div class="ti-col">
<div class="wp-warning-box">
<span class='dashicons dashicons-warning'></span> 
<span><?php echo TrustindexTestimonialsPlugin::___("Widget title required!"); ?></span>
</div>
</div>
</div>
<?php endif; ?>
</div>
<form method="post" action="#">
<input type="hidden" name="command" value="save-review-filter" />
<input type="hidden" name="widget-name" value="<?php echo 'Widget - ' . esc_attr($next_widget_id); ?>" />
<div class="ti-box">
<?php if (count($reviews) < 3): ?>
<div class="ti-row">
<div class="ti-col">
<div class="wp-info-box">
<span class='dashicons dashicons-info'></span>
<strong><?php echo TrustindexTestimonialsPlugin::___("INFO"); ?></strong><br />
<?php echo TrustindexTestimonialsPlugin::___('Please upload at least 3 testimonials to display them in your website nicely!'); ?>
<a href="edit.php?post_type=wpt-testimonial"> <?php echo TrustindexTestimonialsPlugin::___('Upload your testimonials here!'); ?></a>
</div>
</div>
</div>
<?php endif; ?>
<p><?php echo TrustindexTestimonialsPlugin::___("Display testimonials"); ?>:</p>
<input type="radio" id="display_testimonials_all" name="mode" value="all" <?php echo $widget['1']['mode'] == 'all' ? 'checked' : '' ?>>
<label for="display_testimonials_all"><?php echo TrustindexTestimonialsPlugin::___("All"); ?></label><br>
<?php if (count($categories)): ?>
<input type="radio" id="display_testimonials_categories" name="mode" value="category" <?php echo $widget['1']['mode'] == 'category' ? 'checked' : '' ?>>
<label for="display_testimonials_categories"><?php echo TrustindexTestimonialsPlugin::___("Categories"); ?></label>
<br>
<div id="category_select" <?php echo $widget['1']['mode'] !== 'category' ? 'style="display: none;"' : '' ?>>
<hr>
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___('Select category'); ?></label>
<select class="form-control" name="category" id="ti-category-id">
<?php foreach ($categories as $category) : ?>
<option value="<?php echo esc_attr($category['term_id']); ?>" <?php echo $widget['1']['mode'] == 'category' ? ($widget['1']['selected'] == $category['term_id'] ? 'selected' : '') : '' ?>><?php echo esc_attr($category['name']); ?></option>
<?php endforeach; ?>
</select>
</div>
<hr>
</div>
<?php endif; ?>
<input type="radio" id="display_testimonials_manual" name="mode" value="manual_select" <?php echo $widget['1']['mode'] == 'manual_select' ? 'checked' : '' ?>>
<label for="display_testimonials_manual"><?php echo TrustindexTestimonialsPlugin::___("Select manually"); ?></label>
<div id="review_select" <?php echo $widget['1']['mode'] !== 'manual_select' ? 'style="display: none;"' : '' ?>>
<hr>
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___('Select testimonials'); ?></label>
<?php foreach ($reviews as $k => $review) : ?>
<input type="checkbox" id="review_<?php echo esc_attr($review['id']); ?>" name="review[<?php echo esc_attr($review['id']); ?>]" value="<?php echo esc_attr($review['id']); ?>" <?php echo $widget['1']['mode'] == 'manual_select' ? (in_array($review['id'], explode(',', $widget['1']['selected'])) ? 'checked' : '') : '' ?>>
<label for="review_<?php echo esc_attr($review['id']); ?>" class="ti-review-box">
<div class="ti-row">
<div class="ti-col">
<span style="font-size:16px;"><b><?php echo esc_attr($review['title']); ?></b></span>
</div>
<div class="ti-col">
<span class="strong-rating">
<?php
for ($i = 1; $i <= 5; $i++) {
if ($i <= $review['star_rating']) {
echo '<img height="20px" width="20px" src=' . esc_url($trustindex_testimonials_pm->get_full_star()) . ' alt="full star"></img>';
} else {
echo '<img height="20px" width="20px" src=' . esc_url($trustindex_testimonials_pm->get_empty_star()) . ' alt="empty star"></img>';
}
}
?>
</span>
</div>
<div class="ti-col">
<span class="ti-pull-right"><?php echo esc_attr($review['date']); ?></span>
</div>
</div>
<div class="ti-row">
<?php if ($review['photo'][120]): ?>
<span style="max-width: 120px; padding: 10px;"><img src="<?php echo esc_url($review['photo'][120]); ?>" alt="photo-<?php echo esc_attr($review['client_name']); ?>"></span>
<?php endif; ?>
<div class="ti-col">
<p><?php echo wp_kses_post($review['content']); ?></p>
</div>
</div>
<div class="ti-row">
<?php if ($review['client_name']) : ?>
<div class="ti-col">
<span><?php echo TrustindexTestimonialsPlugin::___('Client name'); ?>: <?php echo esc_attr($review['client_name']); ?></span>
</div>
<?php endif; ?>
<?php if ($review['company_name']) : ?>
<div class="ti-col">
<span><?php echo TrustindexTestimonialsPlugin::___('Company name'); ?>: <?php echo esc_attr($review['company_name']); ?></span>
</div>
<?php endif; ?>
<?php if ($review['company_website']) : ?>
<div class="ti-col">
<span><?php echo TrustindexTestimonialsPlugin::___('Website'); ?>: <?php echo esc_attr($review['company_website']); ?></span>
</div>
<?php endif; ?>
</div>
</label>
<?php endforeach; ?>
</div>
<hr>
</div>
</div>
<div id="star_select" <?php echo $widget['1']['mode'] == 'manual_select' ? 'style="display: none;"' : '' ?>>
<div class="ti-box">
<p><?php echo TrustindexTestimonialsPlugin::___('Rating filter'); ?>:</p>
<select class="form-control" name="rating" id="ti-rating-filter-id">
<option value="all" <?php echo $widget['1']['rating'] == 'all' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Show all'); ?></option>
<option value="only_5" <?php echo $widget['1']['rating'] == 'only_5' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Only '); ?>&starf;&starf;&starf;&starf;&starf;</option>
<option value="min_4" <?php echo $widget['1']['rating'] == 'min_4' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Minimum '); ?>&starf;&starf;&starf;&starf;</option>
<option value="min_3" <?php echo $widget['1']['rating'] == 'min_3' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Minimum '); ?>&starf;&starf;&starf;</option>
<option value="max_3" <?php echo $widget['1']['rating'] == 'max_3' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Maximum '); ?>&starf;&starf;&starf;</option>
</select>
</div>
</div>
<input type="submit" class="btn-text btn-refresh ti-pull-right" value="<?php echo TrustindexTestimonialsPlugin::___("Next"); ?>">
</form>
<?php elseif ($current_step == 2 || !$style_id) : ?>
<h1 class="ti-free-title">
2. <?php echo TrustindexTestimonialsPlugin::___('Select Layout'); ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=1&id=<?php echo esc_attr($id); ?>" class="ti-back-icon"><?php echo TrustindexTestimonialsPlugin::___('Back'); ?></a>
</h1>
<?php if (!count($reviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexTestimonialsPlugin::___('There are no testimonials'); ?>
</p>
</div>
<?php else : ?>
<div class="ti-filter-row">
<label><?php echo TrustindexTestimonialsPlugin::___('Layout'); ?>:</label>
<span class="ti-checkbox">
<input type="radio" name="layout-select" value="" data-ids="" checked>
<label><?php echo TrustindexTestimonialsPlugin::___('All'); ?></label>
</span>
<?php foreach (TrustindexTestimonialsPlugin::$widget_templates['categories'] as $category => $ids) : ?>
<span class="ti-checkbox">
<input type="radio" name="layout-select" value="<?php echo esc_attr($category); ?>" data-ids="<?php echo esc_attr($ids); ?>">
<label><?php echo esc_html(TrustindexTestimonialsPlugin::___(ucfirst($category))); ?></label>
</span>
<?php endforeach; ?>
</div>
<form action="">
<div class="ti-preview-boxes-container">
<input type="hidden" name="widget-name" value="" />
<?php foreach (TrustindexTestimonialsPlugin::$widget_templates['templates'] as $style_id => $template) : ?>
<?php
$class_name = 'ti-full-width';
if (in_array($template['type'], ['badge', 'button', 'floating', 'popup', 'sidebar'])) {
$class_name = 'ti-half-width';
}
?>
<div class="<?php echo esc_attr($class_name); ?>">
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo esc_attr($style_id); ?>" data-set-id="light-background">
<div class="ti-header">
<span class="ti-header-layout-text">
<?php echo TrustindexTestimonialsPlugin::___('Layout'); ?>:
<strong><?php echo esc_html(TrustindexTestimonialsPlugin::___($template['name'])); ?></strong>
</span>
<a href="?page=<?php echo esc_attr($page); ?>&step=3&id=<?php echo esc_attr($id); ?>&command=save-style&selected&style_id=<?php echo esc_attr($style_id); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Select"); ?></a>
<?php if ($template['image'] > 120): ?>
<br />
<span class="ti-header-layout-text">
<?php echo TrustindexTestimonialsPlugin::___('Recommended minimum image size is %s x %s pixel', [esc_attr($template['image']), esc_attr($template['image'])]); ?>
</span>
<?php endif; ?>
<div class="clear"></div>
</div>
<div class="preview">
<?php echo wp_kses_post($trustindex_testimonials_pm->get_noreg_list_reviews($id, true, $style_id, 'light-background', true)); ?>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</form>
<?php endif; ?>
<?php elseif ($current_step == 3 || !$scss_set) : ?>
<h1 class="ti-free-title">
3. <?php echo TrustindexTestimonialsPlugin::___('Select Style'); ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=2&id=<?php echo esc_attr($id); ?>&selected" class="ti-back-icon"><?php echo TrustindexTestimonialsPlugin::___('Back'); ?></a>
</h1>
<?php if (!count($reviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexTestimonialsPlugin::___('There are no testimonials'); ?>
</p>
</div>
<?php else : ?>
<?php
$class_name = 'ti-full-width';
if (in_array(TrustindexTestimonialsPlugin::$widget_templates['templates'][$style_id]['type'], ['badge', 'button', 'floating', 'popup', 'sidebar'])) {
$class_name = 'ti-half-width';
}
?>
<div class="ti-preview-boxes-container">
<?php foreach (TrustindexTestimonialsPlugin::$widget_styles as $scss_id => $style) : ?>
<div class="<?php echo esc_attr($class_name); ?>">
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo esc_attr($style_id); ?>" data-set-id="<?php echo esc_attr($scss_id); ?>">
<div class="ti-header">
<span class="ti-header-layout-text">
<?php echo TrustindexTestimonialsPlugin::___('Style'); ?>:
<strong><?php echo TrustindexTestimonialsPlugin::___(esc_attr($style['name'])); ?></strong>
</span>
<a href="?page=<?php echo esc_attr($page); ?>&step=4&id=<?php echo esc_attr($id); ?>&command=save-set&selected&style_id=<?php echo esc_attr($style_id); ?>&scss_set=<?php echo esc_attr($scss_id); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Select"); ?></a>
<div class="clear"></div>
</div>
<div class="preview">
<?php echo wp_kses_post($trustindex_testimonials_pm->get_noreg_list_reviews($id, true, $style_id, $scss_id, true)); ?>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php elseif ($current_step == 4 || !$widget_setted_up) : ?>
<?php
$widget_type = TrustindexTestimonialsPlugin::$widget_templates['templates'][$style_id]['type'];
$widget_has_reviews = !in_array($widget_type, ['button', 'badge']) || in_array($style_id, [23, 30, 32]);
?>
<h1 class="ti-free-title">
4. <?php echo TrustindexTestimonialsPlugin::___('Set up widget'); ?>
<?php if ($widget['2'] == '17' || $widget['2'] == '21'): ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=2&id=<?php echo esc_attr($id); ?>&selected" class="ti-back-icon"><?php echo TrustindexTestimonialsPlugin::___('Back'); ?></a>
<?php else: ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=3&id=<?php echo esc_attr($id); ?>&selected&style_id=<?php echo esc_attr($style_id); ?>" class="ti-back-icon"><?php echo TrustindexTestimonialsPlugin::___('Back'); ?></a>
<?php endif; ?>
</h1>
<?php if (!count($reviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexTestimonialsPlugin::___('There are no testimonials'); ?>
</p>
</div>
<?php else : ?>
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo esc_attr($style_id); ?>" data-set-id="<?php echo esc_attr($scss_set); ?>">
<div class="ti-header">
<?php echo TrustindexTestimonialsPlugin::___('Widget Preview'); ?>
<?php if (!in_array($style_id, [17, 21])) : ?>
<span class="ti-header-layout-text ti-pull-right">
<?php echo TrustindexTestimonialsPlugin::___('Style'); ?>:
<strong><?php echo esc_html(TrustindexTestimonialsPlugin::___(TrustindexTestimonialsPlugin::$widget_styles[$scss_set]['name'])); ?></strong>
</span>
<?php endif; ?>
<span class="ti-header-layout-text ti-pull-right">
<?php echo TrustindexTestimonialsPlugin::___('Layout'); ?>:
<strong><?php echo esc_html(TrustindexTestimonialsPlugin::$widget_templates['templates'][$style_id]['name']); ?></strong>
</span>
</div>
<div class="preview">
<div id="ti-review-list"><?php echo wp_kses_post($trustindex_testimonials_pm->get_noreg_list_reviews($id, true, $style_id, $scss_set)); ?></div>
<div style="display: none; text-align: center">
<?php echo TrustindexTestimonialsPlugin::___("You do not have reviews with the current filters. <br />Change your filters if you would like to display reviews on your page!"); ?>
</div>
</div>
</div>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexTestimonialsPlugin::___('Widget Settings'); ?></div>
<?php /* 
<div class="tab">
<button class="tablinks" id="<?php echo $widget['4']['last_saved'] == 'general' ? 'active-tab' : '' ?>" onclick="openTab(event, 'General')"><?php echo TrustindexTestimonialsPlugin::___("General"); ?></button>
<button class="tablinks" id="<?php echo $widget['4']['last_saved'] == 'appearance' ? 'active-tab' : '' ?>" onclick="openTab(event, 'Appearance')"><?php echo TrustindexTestimonialsPlugin::___("Appearance"); ?></button>
<button class="tablinks" id="<?php echo $widget['4']['last_saved'] == 'navigation' ? 'active-tab' : '' ?>" onclick="openTab(event, 'Navigation')"><?php echo TrustindexTestimonialsPlugin::___("Navigation"); ?></button>
</div>
*/ ?>
<p class="ti-settings-title"><?php echo TrustindexTestimonialsPlugin::___('General'); ?></p>
<!-- <div id="General" class="tabcontent"> -->
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___('Order'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-order" />
<select class="form-control" name="order" id="ti-order-id">
<option value="newest" <?php echo $widget['4']['general']['order'] == 'newest' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Newest'); ?></option>
<option value="oldest" <?php echo $widget['4']['general']['order'] == 'oldest' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Oldest'); ?></option>
<option value="random" <?php echo $widget['4']['general']['order'] == 'random' ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Random'); ?></option>
</select>
</form>
</div>
<form method="post" action="" id="ti-widget-options">
<input type="hidden" name="command" value="save-display-options" />
<span class="ti-checkbox row">
<input type="checkbox" name="hide_stars" value="1" <?php echo (isset($widget['4']['appearance']['hide_stars']) && $widget['4']['appearance']['hide_stars']) ? 'checked' : ''; ?>>
<label><?php echo TrustindexTestimonialsPlugin::___("Hide stars"); ?></label>
</span>
<span class="ti-checkbox row">
<input type="checkbox" name="hide_image" value="1" <?php echo (isset($widget['4']['appearance']['hide_image']) && $widget['4']['appearance']['hide_image']) ? 'checked' : ''; ?>>
<label><?php echo TrustindexTestimonialsPlugin::___("Hide user image"); ?></label>
</span>
<?php if ($widget['2'] != '17' && $widget['2'] != '21'): ?>
<span class="ti-checkbox row">
<input type="checkbox" name="auto_height" value="1" <?php echo (isset($widget['4']['appearance']['auto_height']) && $widget['4']['appearance']['auto_height']) ? 'checked' : ''; ?>>
<label><?php echo TrustindexTestimonialsPlugin::___("Review height auto"); ?></label>
</span>
<?php endif; ?>
<span class="ti-checkbox row">
<input type="checkbox" name="enable-font" value="1" <?php echo (isset($widget['4']['appearance']['enable-font']) && $widget['4']['appearance']['enable-font']) ? 'checked' : ''; ?>>
<label><?php echo TrustindexTestimonialsPlugin::___("Use site's font"); ?></label>
</span>
<span class="ti-checkbox row">
<input type="checkbox" name="hover-anim" value="1" <?php echo (isset($widget['4']['appearance']['hover-anim']) && $widget['4']['appearance']['hover-anim']) ? 'checked' : ''; ?>>
<label><?php echo TrustindexTestimonialsPlugin::___("Enable mouse over anim"); ?></label>
</span>
</form>
<?php if (in_array($widget['2'],array('10','16', '17', '18', '19', '21', '31', '33', '37', '38', '48')) == false ): ?>
<br />
<div class="ti-input-row" id="arrow-selector">
<label><?php echo TrustindexTestimonialsPlugin::___('Navigation arrow'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-arrow" />
<select class="form-control" name="arrow" id="ti-arrow-id">
<option value="false" <?php echo (isset($widget['4']['appearance']['nav']) && $widget['4']['appearance']['nav'] == 'false') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Hide'); ?></option>
<option value="mobile" <?php echo (isset($widget['4']['appearance']['nav']) && $widget['4']['appearance']['nav'] == 'mobile') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Mobile only'); ?></option>
<option value="desktop" <?php echo (isset($widget['4']['appearance']['nav']) && $widget['4']['appearance']['nav'] == 'desktop') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Desktop only'); ?></option>
<option value="true" <?php echo (isset($widget['4']['appearance']['nav']) && $widget['4']['appearance']['nav'] == 'true') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Show'); ?></option>
</select>
</form>
</div>
<?php endif; ?>
<?php if (in_array($widget['2'],array('10','16', '17', '18', '19', '21', '31', '33', '37', '38', '48')) == false ): ?>
<br />
<div class="ti-input-row" id="dots-selector">
<label><?php echo TrustindexTestimonialsPlugin::___('Navigation dots'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-dots" />
<select class="form-control" name="dots" id="ti-dots-id">
<option value="false" <?php echo (isset($widget['4']['appearance']['dots']) && $widget['4']['appearance']['dots'] == 'false') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Hide'); ?></option>
<option value="mobile" <?php echo (isset($widget['4']['appearance']['dots']) && $widget['4']['appearance']['dots'] == 'mobile') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Mobile only'); ?></option>
<option value="true" <?php echo (isset($widget['4']['appearance']['dots']) && $widget['4']['appearance']['dots'] == 'true') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Show'); ?></option>
</select>
</form>
</div>
<?php endif; ?>
<br />
<div class="ti-input-row" id="dateformat-selector">
<label><?php echo TrustindexTestimonialsPlugin::___('Select date format'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-dateformat" />
<select class="form-control" name="date_format" id="ti-dateformat-id">
<?php foreach (TrustindexTestimonialsPlugin::$widget_dateformats as $format) : ?>
<option value="<?php echo esc_attr($format); ?>" <?php echo $widget['4']['appearance']['date_format'] == $format ? 'selected' : ''; ?>><?php echo esc_attr(date($format)); ?></option>
<?php endforeach; ?>
</select>
</form>
</div>
<?php if (in_array($widget['2'],array('4','5','13','15','19','34','36','37','39','44','45','46','47')) == true ): ?>
<br />
<div class="ti-input-row">
<form method="post" action="" id="ti-slider-interval">
<label><?php echo TrustindexTestimonialsPlugin::___("Slider interval"); ?></label>
<input type="hidden" name="command" value="save-slider-interval" />
<div class="ti-input-group">
<input type="number" min="0" max="20" name="slider-interval" class="ti-form-control" value="<?php echo isset($widget['4']['navigation']['slider_interval']) ? esc_attr($widget['4']['navigation']['slider_interval']) : esc_attr($navigation['slider_interval']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">sec</span>
</div>
</div>
</form>
</div>
<?php endif;?>
<!-- </div> -->
<br />
<p class="ti-settings-title"><?php echo TrustindexTestimonialsPlugin::___('Review box'); ?></p>
<div class="ti-input-row">
<form method="post" action="" id="ti-box">
<input type="hidden" name="command" value="save-box" />
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Background color"); ?></label>
<input type="color" name="box-background-color" value="<?php echo isset($widget['4']['appearance']['box-background-color']) ? esc_attr($widget['4']['appearance']['box-background-color']) : esc_attr($appearance['box-background-color']); ?>" >
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Border color"); ?></label>
<input type="color" name="box-border-color" value="<?php echo isset($widget['4']['appearance']['box-border-color']) ? esc_attr($widget['4']['appearance']['box-border-color']) : esc_attr($appearance['box-border-color']); ?>" >
</div>
</div>
</div>
<br />
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Border padding"); ?></label>
<div class="ti-input-group">
<input type="number" min="0" max="100" name="box-padding" class="ti-form-control" value="<?php echo isset($widget['4']['appearance']['box-padding']) ? esc_attr($widget['4']['appearance']['box-padding']) : esc_attr($appearance['box-padding']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Border width"); ?></label>
<div class="ti-input-group">
<input type="number" min="0" max="20" name="box-border-width" class="ti-form-control" value="<?php echo isset($widget['4']['appearance']['box-border-top-width']) ? esc_attr($widget['4']['appearance']['box-border-top-width']) : esc_attr($appearance['box-border-top-width']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Border radius"); ?></label>
<div class="ti-input-group">
<input type="number" min="0" max="100" name="box-border-radius" class="ti-form-control" value="<?php echo isset($widget['4']['appearance']['box-border-radius']) ? esc_attr($widget['4']['appearance']['box-border-radius']) : esc_attr($appearance['box-border-radius']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
</div>
</form>
</div>
<br />
<p class="ti-settings-title"><?php echo TrustindexTestimonialsPlugin::___('Font settings'); ?></p>
<div class="ti-input-row">
<form method="post" action="" id="ti-font">
<input type="hidden" name="command" value="save-font" />
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Name font size"); ?></label>
<div class="ti-input-group">
<input type="number" min="6" max="72" name="profile-font-size" class="ti-form-control" value="<?php echo isset($widget['4']['appearance']['profile-font-size']) ? esc_attr($widget['4']['appearance']['profile-font-size']) : esc_attr($appearance['profile-font-size']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Name font color"); ?></label>
<input type="color" name="profile-color" value="<?php echo isset($widget['4']['appearance']['profile-color']) ? esc_attr($widget['4']['appearance']['profile-color']) : esc_attr($appearance['profile-color']); ?>" >
</div>
</div>
</div>
<br />
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Review font size"); ?></label>
<div class="ti-input-group">
<input type="number" min="6" max="72" name="review-font-size" class="ti-form-control" value="<?php echo isset($widget['4']['appearance']['review-font-size']) ? esc_attr($widget['4']['appearance']['review-font-size']) : esc_attr($appearance['review-font-size']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Review font color"); ?></label>
<input type="color" name="text-color" value="<?php echo isset($widget['4']['appearance']['text-color']) ? esc_attr($widget['4']['appearance']['text-color']) : esc_attr($appearance['text-color']); ?>" >
</div>
</div>
</div>
</form>
</div>
<?php /*
<br />
<div class="ti-input-row" id="review-card-selector">
<label><?php echo TrustindexTestimonialsPlugin::___('Edit review card'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-review-card" />
<div class="ti-row">
<div class="ti-col">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Info color"); ?></label>
<input type="color" name="profile-color" value="<?php echo isset($widget['4']['appearance']['profile-color']) ? esc_attr($widget['4']['appearance']['profile-color']) : esc_attr($appearance['profile-color']); ?>" >
</div>
</div>
<div class="ti-col">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Review color"); ?></label>
<input type="color" name="text-color" value="<?php echo isset($widget['4']['appearance']['text-color']) ? esc_attr($widget['4']['appearance']['text-color']) : esc_attr($appearance['text-color']);?>" >
</div>
</div>
</div>
</form>
</div>
*/ ?>
<?php /*
<br />
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Display type"); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-display_type" />
<select class="form-control" name="display_type" id="ti-display-type-id">
<option value="website" <?php echo $widget['4']['appearance']['display_type'] === "website" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Website'); ?></option>
<option value="company" <?php echo $widget['4']['appearance']['display_type'] === "company" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Company'); ?></option>
<option value="date" <?php echo $widget['4']['appearance']['display_type'] === "date" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Date'); ?></option>
</select>
</form>
</div>
*/ ?>
<br />
<div class="ti-input-row">
<form method="post" action="" id="ti-review-lines">
<label><?php echo TrustindexTestimonialsPlugin::___("Review lines"); ?></label>
<input type="hidden" name="command" value="save-review-lines" />
<div class="ti-input-group">
<input type="number" min="0" max="20" name="review-lines" class="ti-form-control" value="<?php echo isset($widget['4']['appearance']['review-lines']) ? esc_attr($widget['4']['appearance']['review-lines']) : esc_attr($appearance['review-lines']); ?>" >
</div>
</form>
</div>
<br />
<?php if ($widget['2'] != '17' && $widget['2'] != '21'): ?>
<div class="ti-input-row" id="text-align-selector">
<label><?php echo TrustindexTestimonialsPlugin::___('Align'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-text-align" />
<select class="form-control" name="text_align" id="ti-text-align-id">
<option value="left" <?php echo (isset($widget['4']['appearance']['text-align']) && $widget['4']['appearance']['text-align'] == 'left') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Left'); ?></option>
<option value="center" <?php echo (isset($widget['4']['appearance']['text-align']) && $widget['4']['appearance']['text-align'] == 'center') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Center'); ?></option>
<option value="right" <?php echo (isset($widget['4']['appearance']['text-align']) && $widget['4']['appearance']['text-align'] == 'right') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Right'); ?></option>
<option value="justify" <?php echo (isset($widget['4']['appearance']['text-align']) && $widget['4']['appearance']['text-align'] == 'justify') ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Justify'); ?></option>
</select>
</form>
</div>
<?php endif; ?>
<!-- </div> -->
<?php /*
<div id="Navigation" class="tabcontent">
<br />
<div class="ti-input-row">
<label><?php echo TrustindexPlugin::___('Navigation style'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-navigation" />
<select class="form-control" name="navigation_style" id="ti-navigation-id">
<option value="arrow" <?php echo $widget['4']['navigation']['navigation_style'] === "arrow" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Arrow'); ?></option>
<option value="dots" <?php echo $widget['4']['navigation']['navigation_style'] === "dots" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Dots'); ?></option>
<option value="rotate_interval" <?php echo $widget['4']['navigation']['navigation_style'] === "rotate_interval" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Rotate interval'); ?></option>
</select>
</form>
</div>
</div>

<div class="clear"></div>
*/ ?>
<div class="ti-footer">
<a href="?page=<?php echo esc_attr($page); ?>&step=5&id=<?php echo esc_attr($id); ?>&command=setup-widget&setup_widget&selected&style_id=<?php echo esc_attr($style_id); ?>&scss_set=<?php echo esc_attr($scss_set); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Save and get code"); ?></a>
<div class="clear"></div>
</div>

</div>
<?php endif; ?>
<?php else : ?>
<h1 class="ti-free-title">
5. <?php echo TrustindexTestimonialsPlugin::___('Get shortcode'); ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=4&id=<?php echo esc_attr($id); ?>&selected&style_id=<?php echo esc_attr($style_id); ?>&scss_set=<?php echo esc_attr($scss_set); ?>" class="ti-back-icon"><?php echo TrustindexTestimonialsPlugin::___('Back'); ?></a>
</h1>
<?php if (!count($reviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexTestimonialsPlugin::___('There are no testimonials'); ?>
</p>
</div>
<?php else : ?>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexTestimonialsPlugin::___('Insert this shortcode into your website'); ?></div>
<div class="ti-input-row" style="margin-bottom: 2px">
<label><?php echo TrustindexTestimonialsPlugin::___('Shortcode'); ?></label>
<code class="code-shortcode">[wp-testimonials widget-id=<?php echo esc_attr($id); ?>]</code>
<a href=".code-shortcode" class="btn-text btn-copy2clipboard"><?php echo TrustindexTestimonialsPlugin::___("Copy to clipboard"); ?></a>
</div>
<?php echo TrustindexTestimonialsPlugin::___('Copy and paste this shortcode into post, page or widget.'); ?>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
</div>
</div>
</div>
<div id="ti-loading">
<div class="ti-loading-effect">
<div></div>
<div></div>
<div></div>
</div>
</div>