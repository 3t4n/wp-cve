<?php
include(plugin_dir_path(__FILE__) . "index-widget-header.php");
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
<div class="ti-box">
<form id="posts-filter" method="get">
<div class="ti-box-head">
<div class="ti-row">
<div class="ti-col">
<span class="wp-heading-inline"><?php echo TrustindexTestimonialsPlugin::___('Create Widgets'); ?></span>
</div>
</div>
<div class="ti-row">
<div class="ti-col">
<a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/create-widget.php'; ?>" class="btn-text btn-refresh"><?php echo TrustindexTestimonialsPlugin::___('Add New'); ?></a>
</div>
</div>
<br />
<div class="ti-row">
<div class="ti-col">
<a class="current" href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php'; ?>"><?php echo TrustindexTestimonialsPlugin::___('All'); ?> <span class="count">(<?php echo esc_attr(count($widgets)); ?>)</span></a>
</div>
<div class="ti-col">
<p class="search-box">
<label class="screen-reader-text" for="post-search-input"><?php echo TrustindexTestimonialsPlugin::___('Search'); ?></label>
<input type="search" id="post-search-input" name="search" value="">
<input type="submit" id="search-submit" class="button" value="Search">
<input type="hidden" name="page" value="testimonial-widgets/tabs/index-widget.php">
</p>
</div>
</div>
</div>
<div class="ti-box-head">
<div class="ti-row">
<table class="wp-list-table widefat fixed striped wpm-testimonial_page_testimonial-views">
<thead>
<tr>
<th scope="col" id="id" class="manage-column column-id <?php echo $order_setting['id']['active'] ? ($order_setting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($order_setting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php&order_by=id&order=' . esc_attr($order_setting['id']['order']); ?>"><span><?php echo TrustindexTestimonialsPlugin::___('ID'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="name" class="manage-column column-name <?php echo $order_setting['name']['active'] ? ($order_setting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($order_setting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php&order_by=name&order=' . esc_attr($order_setting['name']['order']); ?>"><span><?php echo TrustindexTestimonialsPlugin::___('Name'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="shortcode" class="manage-column column-shortcode"><?php echo TrustindexTestimonialsPlugin::___('Shortcode'); ?></th>
</tr>
</thead>
<tbody id="the-list">
<?php foreach ($widgets as $widget) : ?>
<?php
$widget_value = unserialize($widget->value);
$params = '&id=' . $widget->id . '&step=' . $widget_value['current'];
if ($widget_value['current'] > 1)
{
$params .= '&selected';
}
if ($widget_value['current'] > 2)
{
$params .= '&style_id=' . $widget_value['2'];
}
if ($widget_value['current'] > 3)
{
$params .= '&command=save-set&scss_set=' . $widget_value['3'];
}
if ($widget_value['current'] > 4)
{
$params .= '&setup_widget';
}
?>
<tr>
<td class="id column-id"><?php echo esc_attr($widget->id); ?></td>
<td class="name column-name"><a class="row-title" href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/create-widget.php' . esc_attr($params); ?>"><?php echo esc_attr($widget->name); ?></a>
<div class="row-actions">
<span class="edit">
<a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/create-widget.php' . esc_attr($params); ?>"><?php echo TrustindexTestimonialsPlugin::___('Edit'); ?></a> |
</span>
<span class="duplicate">
<a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php&duplicate_widget=' . esc_attr($widget->id); ?>"><?php echo TrustindexTestimonialsPlugin::___('Duplicate'); ?></a> |
</span>
<span class="delete">
<a class="submitdelete" href="<?php echo wp_nonce_url('admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php&delete_widget=' . esc_attr($widget->id), 'delete_widget', 'delete_nonce'); ?>" onclick="if ( confirm('<?php echo TrustindexTestimonialsPlugin::___('Delete') . ' ' . esc_attr($widget->name) . '?'; ?>') ) { return true;} return false;"><?php echo TrustindexTestimonialsPlugin::___('Delete'); ?></a>
</span>
</div>
</td>
<td class="shortcode column-shortcode">[wp-testimonials widget-id="<?php echo esc_attr($widget->id); ?>"]</td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<th scope="col" id="id" class="manage-column column-id <?php echo $order_setting['id']['active'] ? ($order_setting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($order_setting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php&order_by=id&order=' . esc_attr($order_setting['id']['order']); ?>"><span><?php echo TrustindexTestimonialsPlugin::___('ID'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="name" class="manage-column column-name <?php echo $order_setting['name']['active'] ? ($order_setting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($order_setting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php&order_by=name&order=' . esc_attr($order_setting['name']['order']); ?>"><span><?php echo TrustindexTestimonialsPlugin::___('Name'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="shortcode" class="manage-column column-shortcode"><?php echo TrustindexTestimonialsPlugin::___('Shortcode'); ?></th>
</tr>
</tfoot>
</table>
<div class="clear"></div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>