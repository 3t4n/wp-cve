<?php
function trustindex_plugin_change_step($step = 4)
{
global $trustindex_woocommerce;
if($step < 4)
{
$options_to_delete = [
'widget-setted-up',
'align',
'verified-icon',
'enable-animation',
'no-rating-text',
'disable-font',
'show-reviewers-photo',
'show-logos',
'show-stars'
];
foreach($options_to_delete as $name)
{
delete_option($trustindex_woocommerce->get_option_name($name));
}
}
if($step < 3)
{
delete_option($trustindex_woocommerce->get_option_name('scss-set'));
}
if($step < 2)
{
delete_option($trustindex_woocommerce->get_option_name('style-id'));
}
}
if($ti_command == 'save-style')
{
$style_id = sanitize_text_field($_REQUEST['style_id']);
update_option($trustindex_woocommerce->get_option_name('style-id') , $style_id, false);
delete_option($trustindex_woocommerce->get_option_name('review-content'));
trustindex_plugin_change_step(2);
if(in_array($style_id, [ 17, 21 ]))
{
$trustindex_woocommerce->noreg_save_css();
}
if(isset($_GET['style_id']))
{
header("Location: admin.php?page=$_page&tab=setup&step=5");
}
exit;
}
elseif($ti_command == 'save-set')
{
update_option($trustindex_woocommerce->get_option_name('scss-set'), sanitize_text_field($_REQUEST['set_id']), false);
trustindex_plugin_change_step(3);
$trustindex_woocommerce->noreg_save_css(true);
header("Location: admin.php?page=$_page&tab=setup&step=5");
exit;
}
elseif($ti_command == 'save-filter')
{
$filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : null;
$filter = json_decode(stripcslashes($filter), true);
update_option($trustindex_woocommerce->get_option_name('filter') , $filter, false);
exit;
}
elseif($ti_command == 'save-language')
{
check_admin_referer('ti-woocommerce-save-language');
update_option($trustindex_woocommerce->get_option_name('lang') , sanitize_text_field($_POST['lang']), false);
delete_option($trustindex_woocommerce->get_option_name('review-content'));
exit;
}
elseif($ti_command == 'save-dateformat')
{
check_admin_referer('ti-woocommerce-save-dateformat');
update_option($trustindex_woocommerce->get_option_name('dateformat'), sanitize_text_field($_POST['dateformat']), false);
exit;
}
elseif($ti_command == 'save-options')
{
check_admin_referer('ti-woocommerce-save-options');
$r = 0;
if(isset($_POST['verified-icon']))
{
$r = sanitize_text_field($_POST['verified-icon']);
}
update_option($trustindex_woocommerce->get_option_name('verified-icon') , $r, false);
$r = 1;
if(isset($_POST['enable-animation']))
{
$r = sanitize_text_field($_POST['enable-animation']);
}
update_option($trustindex_woocommerce->get_option_name('enable-animation') , $r, false);
$r = 1;
if(isset($_POST['show-arrows']))
{
$r = sanitize_text_field($_POST['show-arrows']);
}
update_option($trustindex_woocommerce->get_option_name('show-arrows') , $r, false);
$r = 1;
if(isset($_POST['show-reviewers-photo']))
{
$r = sanitize_text_field($_POST['show-reviewers-photo']);
}
update_option($trustindex_woocommerce->get_option_name('show-reviewers-photo') , $r, false);
$r = 0;
if(isset($_POST['no-rating-text']))
{
$r = sanitize_text_field($_POST['no-rating-text']);
}
update_option($trustindex_woocommerce->get_option_name('no-rating-text') , $r, false);
$r = 0;
if(isset($_POST['disable-font']))
{
$r = sanitize_text_field($_POST['disable-font']);
}
update_option($trustindex_woocommerce->get_option_name('disable-font') , $r, false);
$r = 1;
if(isset($_POST['show-logos']))
{
$r = sanitize_text_field($_POST['show-logos']);
}
update_option($trustindex_woocommerce->get_option_name('show-logos') , $r, false);
delete_option($trustindex_woocommerce->get_option_name('review-content'));
$trustindex_woocommerce->noreg_save_css(true);
exit;
}
elseif($ti_command == 'save-align')
{
check_admin_referer('ti-woocommerce-save-align');
update_option($trustindex_woocommerce->get_option_name('align') , sanitize_text_field($_POST['align']), false);
$trustindex_woocommerce->noreg_save_css(true);
exit;
}
elseif($ti_command == 'save-amp-notice-hide')
{
update_option($trustindex_woocommerce->get_option_name('amp-hidden-notification'), 1, false);
exit;
}
$style_id = get_option($trustindex_woocommerce->get_option_name('style-id'));
$scss_set = get_option($trustindex_woocommerce->get_option_name('scss-set'));
$lang = get_option($trustindex_woocommerce->get_option_name('lang'), 'en');
$dateformat = get_option($trustindex_woocommerce->get_option_name('dateformat'), 'Y-m-d');
$no_rating_text = get_option($trustindex_woocommerce->get_option_name('no-rating-text'), $trustindex_woocommerce->get_default_no_rating_text($style_id, $scss_set));
$filter = get_option($trustindex_woocommerce->get_option_name('filter'), [ 'stars' => [1, 2, 3, 4, 5], 'only-ratings' => true ]);
$verified_icon = get_option($trustindex_woocommerce->get_option_name('verified-icon'), 0);
$enable_animation = get_option($trustindex_woocommerce->get_option_name('enable-animation'), 1);
$show_arrows = get_option($trustindex_woocommerce->get_option_name('show-arrows'), 1);
$widget_setted_up = get_option($trustindex_woocommerce->get_option_name('widget-setted-up'), 0);
$disable_font = get_option($trustindex_woocommerce->get_option_name('disable-font'), 0);
$align = get_option($trustindex_woocommerce->get_option_name('align'), in_array($style_id, [ 36, 37, 38, 39 ]) ? 'center' : 'left' );
$scss_set_tmp = $scss_set ? $scss_set : 'light-background';
$show_reviewers_photo = get_option($trustindex_woocommerce->get_option_name('show-reviewers-photo'), TrustindexWoocommercePlugin::$widget_styles[$scss_set_tmp]['reviewer-photo'] ? 1 : 0);
$show_logos = get_option($trustindex_woocommerce->get_option_name('show-logos'), TrustindexWoocommercePlugin::$widget_styles[$scss_set_tmp]['hide-logos'] ? 0 : 1);
if(isset($_GET['setup_widget']))
{
update_option($trustindex_woocommerce->get_option_name('widget-setted-up') , 1, false);
header("Location: admin.php?page=$_page&tab=setup&step=5");
exit;
}
$sub_step = isset($_GET['substep']) ? intval(sanitize_text_field($_GET['substep'])) : 0;
if($sub_step == 3 && in_array($style_id, [ 17, 21 ]))
{
$sub_step = 4;
}
wp_enqueue_style("trustindex-widget-preview-css", "https://cdn.trustindex.io/assets/ti-preview-box.css");
$reviews = [];
if($trustindex_woocommerce->is_noreg_table_exists())
{
$reviews = $wpdb->get_results('SELECT * FROM '. $trustindex_woocommerce->get_noreg_tablename() .' ORDER BY date DESC');
}
?>
<?php if(TrustindexWoocommercePlugin::is_amp_active() && !get_option($trustindex_woocommerce->get_option_name('amp-hidden-notification'), 0)): ?>
<div class="ti-notice notice-warning is-dismissible" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexWoocommercePlugin::___("Free plugin features are unavailable with AMP plugin."); ?>
 <a href="https://www.trustindex.io/ti-redirect.php?a=sys&c=wp-amp" target="_blank"><?php echo TrustindexWoocommercePlugin::___("Try premium features (like AMP) for free"); ?></a>
</p>
<button type="button" class="notice-dismiss" data-command="save-amp-notice-hide"></button>
</div>
<?php endif; ?>
<div class="ti-box">
<h1 class="ti-free-title">
<?php echo TrustindexWoocommercePlugin::___('Add Review Widgets to Your Website'); ?>
<a href="?page=<?php echo $_page; ?>&tab=setup" class="ti-back-icon"><?php echo TrustindexWoocommercePlugin::___('Back'); ?></a>
</h1>
<p><?php echo TrustindexWoocommercePlugin::___('87%% of website visitors are more likely to make a purchase after reading Trustindex reviews on-site. %d widget types, %d widget styles available.', [ 40, 25 ]); ?></p>
</div>
<ul class="ti-free-steps">
<li class="<?php echo $style_id ? "done" : 'active'; ?><?php if($sub_step == 1): ?> current<?php endif; ?>" href="?page=<?php echo $_page; ?>&tab=setup&step=5&substep=1">
<span>1</span>
<?php echo TrustindexWoocommercePlugin::___('Select Layout'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $scss_set ? "done" : ($style_id ? "active" : ""); ?><?php if($sub_step == 2): ?> current<?php endif; ?>" href="?page=<?php echo $_page; ?>&tab=setup&step=5&substep=2">
<span>2</span>
<?php echo TrustindexWoocommercePlugin::___('Select Style'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $widget_setted_up ? "done" : ($scss_set ? "active" : ""); ?><?php if($sub_step == 3): ?> current<?php endif; ?>" href="?page=<?php echo $_page; ?>&tab=setup&step=5&substep=3">
<span>3</span>
<?php echo TrustindexWoocommercePlugin::___('Set up widget'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $widget_setted_up ? "active" : ""; ?><?php if($sub_step == 4): ?> current<?php endif; ?>" href="?page=<?php echo $_page; ?>&tab=setup&step=5&substep=4">
<span>4</span>
<?php echo TrustindexWoocommercePlugin::___('Insert code'); ?>
</li>
</ul>
<?php if($sub_step == 1 || !$style_id): ?>
<h1 class="ti-free-title">1. <?php echo TrustindexWoocommercePlugin::___('Select Layout'); ?></h1>
<?php if(!count($reviews)): ?>
<div class="ti-notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexWoocommercePlugin::___('There are no reviews on your page.'); ?>
</p>
</div>
<?php endif; ?>
<div class="ti-filter-row">
<label><?php echo TrustindexWoocommercePlugin::___('Layout'); ?>:</label>
<span class="ti-checkbox">
<input type="radio" name="layout-select" value="" data-ids="" checked>
<label><?php echo TrustindexWoocommercePlugin::___('All'); ?></label>
</span>
<?php foreach(TrustindexWoocommercePlugin::$widget_templates['categories'] as $category => $ids): ?>
<span class="ti-checkbox">
<input type="radio" name="layout-select" value="<?php echo $category; ?>" data-ids="<?php echo $ids; ?>">
<label><?php echo TrustindexWoocommercePlugin::___(ucfirst($category)); ?></label>
</span>
<?php endforeach; ?>
</div>
<div class="ti-preview-boxes-container">
<?php foreach(TrustindexWoocommercePlugin::$widget_templates['templates'] as $id => $template): ?>
<?php
$class_name = 'ti-full-width';
if(in_array($template['type'], [ 'badge', 'button', 'floating', 'popup', 'sidebar' ]))
{
$class_name = 'ti-half-width';
}
?>
<div class="<?php echo $class_name; ?>">
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo $id; ?>" data-set-id="light-background">
<div class="ti-header">
<span class="ti-header-layout-text">
<?php echo TrustindexWoocommercePlugin::___('Layout'); ?>:
<strong><?php echo TrustindexWoocommercePlugin::___($template['name']); ?></strong>
</span>
<a href="?page=<?php echo $_page; ?>&tab=setup&step=5&command=save-style&style_id=<?php echo urlencode($id); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Select"); ?></a>
<div class="clear"></div>
</div>
<div class="preview">
<?php echo $trustindex_woocommerce->get_noreg_list_reviews(null, true, $id, 'light-background', true, true); ?>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php elseif($sub_step == 2 || !$scss_set): ?>
<h1 class="ti-free-title">2. <?php echo TrustindexWoocommercePlugin::___('Select Style'); ?></h1>
<?php if(!count($reviews)): ?>
<div class="ti-notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexWoocommercePlugin::___('There are no reviews on your page.'); ?>
</p>
</div>
<?php endif; ?>
<?php
$class_name = 'ti-full-width';
if(in_array(TrustindexWoocommercePlugin::$widget_templates['templates'][$style_id]['type'], [ 'badge', 'button', 'floating', 'popup', 'sidebar' ]))
{
$class_name = 'ti-half-width';
}
?>
<div class="ti-preview-boxes-container">
<?php foreach(TrustindexWoocommercePlugin::$widget_styles as $id => $style): ?>
<div class="<?php echo $class_name; ?>">
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo $style_id; ?>" data-set-id="<?php echo $id; ?>">
<div class="ti-header">
<span class="ti-header-layout-text">
<?php echo TrustindexWoocommercePlugin::___('Style'); ?>:
<strong><?php echo TrustindexWoocommercePlugin::___($style['name']); ?></strong>
</span>
<a href="?page=<?php echo $_page; ?>&tab=setup&step=5&command=save-set&set_id=<?php echo urlencode($id); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Select"); ?></a>
<div class="clear"></div>
</div>
<div class="preview">
<?php echo $trustindex_woocommerce->get_noreg_list_reviews(null, true, $style_id, $id, true, true); ?>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php elseif($sub_step == 3 || !$widget_setted_up): ?>
<?php
$widget_type = TrustindexWoocommercePlugin::$widget_templates[ 'templates' ][ $style_id ]['type'];
$widget_has_reviews = !in_array($widget_type, [ 'button', 'badge' ]) || in_array($style_id, [ 23, 30, 32 ]);
?>
<h1 class="ti-free-title">3. <?php echo TrustindexWoocommercePlugin::___('Set up widget'); ?></h1>
<?php if(!count($reviews)): ?>
<div class="ti-notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexWoocommercePlugin::___('There are no reviews on your page.'); ?>
</p>
</div>
<?php endif; ?>
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo $style_id; ?>" data-set-id="<?php echo $scss_set; ?>">
<div class="ti-header">
<?php echo TrustindexWoocommercePlugin::___('Widget Preview'); ?>
<?php if(!in_array($style_id, [ 17, 21 ])): ?>
<span class="ti-header-layout-text ti-pull-right">
<?php echo TrustindexWoocommercePlugin::___('Style'); ?>:
<strong><?php echo TrustindexWoocommercePlugin::___(TrustindexWoocommercePlugin::$widget_styles[$scss_set]['name']); ?></strong>
</span>
<?php endif; ?>
<span class="ti-header-layout-text ti-pull-right">
<?php echo TrustindexWoocommercePlugin::___('Layout'); ?>:
<strong><?php echo TrustindexWoocommercePlugin::___(TrustindexWoocommercePlugin::$widget_templates['templates'][$style_id]['name']); ?></strong>
</span>
</div>
<div class="preview">
<div id="ti-review-list"><?php echo $trustindex_woocommerce->get_noreg_list_reviews(null, true, null, null, false, true); ?></div>
<div style="display: none; text-align: center">
<?php echo TrustindexWoocommercePlugin::___("You do not have reviews with the current filters. <br />Change your filters if you would like to display reviews on your page!"); ?>
</div>
</div>
</div>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexWoocommercePlugin::___('Widget Settings'); ?></div>
<div class="ti-left-block">
<?php if($widget_has_reviews): ?>
<div id="ti-filter" class="ti-input-row">
<label><?php echo TrustindexWoocommercePlugin::___('Filter your ratings'); ?></label>
<div class="ti-select" id="show-star" data-platform="trustindex">
<font></font>
<ul>
<li data-value="1,2,3,4,5" <?php echo count($filter['stars']) > 2 ? 'class="selected"' : ''; ?>><?php echo TrustindexWoocommercePlugin::___('Show all'); ?></li>
<li data-value="4,5" <?php echo count($filter['stars']) == 2 ? 'class="selected"' : ''; ?>>
<?php echo $trustindex_woocommerce->get_rating_stars(4); ?> - <?php echo $trustindex_woocommerce->get_rating_stars(5); ?>
</li>
<li data-value="5" <?php echo count($filter['stars']) == 1 ? 'class="selected"' : ''; ?>>
<?php echo TrustindexWoocommercePlugin::___('only'); ?> <?php echo $trustindex_woocommerce->get_rating_stars(5); ?>
</li>
</ul>
</div>
</div>
<?php endif; ?>
<div class="ti-input-row">
<label><?php echo TrustindexWoocommercePlugin::___('Select language'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-language" />
<?php wp_nonce_field('ti-woocommerce-save-language'); ?>
<select class="form-control" name="lang" id="ti-lang-id">
<?php foreach(TrustindexWoocommercePlugin::$widget_languages as $id => $name): ?>
<option value="<?php echo $id; ?>" <?php echo $lang == $id ? 'selected' : ''; ?>><?php echo $name; ?></option>
<?php endforeach; ?>
</select>
</form>
</div>
<?php if($widget_has_reviews): ?>
<div class="ti-input-row">
<label><?php echo TrustindexWoocommercePlugin::___('Select date format'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-dateformat" />
<?php wp_nonce_field('ti-woocommerce-save-dateformat'); ?>
<select class="form-control" name="dateformat" id="ti-dateformat-id">
<?php foreach(TrustindexWoocommercePlugin::$widget_dateformats as $format): ?>
<option value="<?php echo $format; ?>" <?php echo $dateformat == $format ? 'selected' : ''; ?>><?php echo date($format); ?></option>
<?php endforeach; ?>
</select>
</form>
</div>
<?php if(!in_array($style_id, [ 17, 21 ])): ?>
<div class="ti-input-row">
<label><?php echo TrustindexWoocommercePlugin::___('Align'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-align" />
<?php wp_nonce_field('ti-woocommerce-save-align'); ?>
<select class="form-control" name="align" id="ti-align-id">
<?php foreach([ 'left', 'center', 'right', 'justify' ] as $align_type): ?>
<option value="<?php echo esc_attr($align_type); ?>" <?php echo $align_type == $align ? 'selected' : ''; ?>><?php echo TrustindexWoocommercePlugin::___($align_type); ?></option>
<?php endforeach; ?>
</select>
</form>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
<div class="ti-right-block">
<form method="post" action="" id="ti-widget-options">
<input type="hidden" name="command" value="save-options" />
<?php wp_nonce_field('ti-woocommerce-save-options'); ?>
<?php if($widget_has_reviews): ?>
<span class="ti-checkbox row">
<input type="checkbox" id="ti-filter-only-ratings" class="no-form-update" name="only-ratings" value="1" <?php if($filter['only-ratings']): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Hide reviews without comments"); ?></label>
</span>
<?php endif; ?>
<?php if(!in_array($style_id, [ 11, 17, 18, 21, 24, 25, 26, 27, 28, 29, 30, 35 ]) && TrustindexWoocommercePlugin::$widget_styles[$scss_set]['_vars']['dots'] !== 'true'): ?>
<span class="ti-checkbox row">
<input type="checkbox" name="no-rating-text" value="1" <?php if($no_rating_text): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Hide rating text"); ?></label>
</span>
<?php endif; ?>
<?php if($widget_has_reviews): ?>
<span class="ti-checkbox row">
<input type="checkbox" name="verified-icon" value="1" <?php if($verified_icon): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Show verified review icon"); ?></label>
</span>
<?php endif; ?>
<?php if(in_array($widget_type, [ 'slider', 'sidebar' ]) && !in_array($style_id, [ 8, 9, 10, 18, 19, 37 ])): ?>
<span class="ti-checkbox row">
<input type="checkbox" name="show-arrows" value="1" <?php if($show_arrows): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Show navigation arrows"); ?></label>
</span>
<?php endif; ?>
<?php if($widget_has_reviews): ?>
<span class="ti-checkbox row">
<input type="checkbox" name="show-reviewers-photo" value="1" <?php if($show_reviewers_photo): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Show reviewers' photo"); ?></label>
</span>
<span class="ti-checkbox row disabled">
<input type="checkbox" value="1" disabled>
<label class="ti-tooltip">
<?php echo TrustindexWoocommercePlugin::___("Show reviewers' photos locally, from a single image (less requests)"); ?>
<span class="ti-tooltip-message"><?php echo TrustindexWoocommercePlugin::___("Paid package feature"); ?></span>
</label>
</span>
<?php endif; ?>
<span class="ti-checkbox row">
<input type="checkbox" name="enable-animation" value="1" <?php if($enable_animation): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Enable mouseover animation"); ?></label>
</span>
<span class="ti-checkbox row">
<input type="checkbox" name="disable-font" value="1" <?php if($disable_font): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Use site's font"); ?></label>
</span>
<?php if($widget_has_reviews): ?>
<span class="ti-checkbox row">
<input type="checkbox" name="show-logos" value="1" <?php if($show_logos): ?>checked<?php endif;?>>
<label><?php echo TrustindexWoocommercePlugin::___("Show platform logos"); ?></label>
</span>
<?php endif; ?>
</form>
</div>
<div class="clear"></div>
<div class="ti-footer">
<a href="?page=<?php echo $_page; ?>&tab=setup&step=5&setup_widget" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Save and get code"); ?></a>
<div class="clear"></div>
</div>
</div>
<?php else: ?>
<h1 class="ti-free-title">4. <?php echo TrustindexWoocommercePlugin::___('Insert code'); ?></h1>
<?php if(!count($reviews)): ?>
<div class="ti-notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo TrustindexWoocommercePlugin::___('There are no reviews on your page.'); ?>
</p>
</div>
<?php endif; ?>
<div class="ti-box">
<div class="ti-header"><?php echo TrustindexWoocommercePlugin::___('Insert this shortcode into your website'); ?></div>
<div class="ti-input-row" style="margin-bottom: 2px">
<label>Shortcode</label>
<code class="code-shortcode">[<?php echo $trustindex_woocommerce->get_shortcode_name(); ?>]</code>
<a href=".code-shortcode" class="btn-text btn-copy2clipboard"><?php echo TrustindexWoocommercePlugin::___("Copy to clipboard") ;?></a>
</div>
<?php echo TrustindexWoocommercePlugin::___('Copy and paste this shortcode into post, page or widget.'); ?>
</div>
<?php endif; ?>