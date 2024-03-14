<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

function siteseo_xml_sitemap_general_enable_callback(){
	$docs  = siteseo_get_docs_links();

	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_general_enable']); ?>


<label for="siteseo_xml_sitemap_general_enable">
	<input id="siteseo_xml_sitemap_general_enable"
		name="siteseo_xml_sitemap_option_name[xml_sitemap_general_enable]" type="checkbox" <?php checked($check, '1') ?>
	value="1"/>
	<?php esc_html_e('Enable XML Sitemap', 'siteseo'); ?>
	<?php echo wp_kses_post(siteseo_tooltip_link($docs['sitemaps']['xml'], __('Guide to enable XML Sitemaps - new window', 'siteseo'))); ?>
</label>


<?php
}

function siteseo_xml_sitemap_img_enable_callback(){
	$docs  = siteseo_get_docs_links();

	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_img_enable']); ?>


<label for="siteseo_xml_sitemap_img_enable">
	<input id="siteseo_xml_sitemap_img_enable" name="siteseo_xml_sitemap_option_name[xml_sitemap_img_enable]"
		type="checkbox" <?php checked($check, '1') ?>
	value="1"/>
	<?php esc_html_e('Enable Image Sitemap (standard images, image galleries, featured image, WooCommerce product images)', 'siteseo'); ?>
	<?php echo wp_kses_post(siteseo_tooltip_link($docs['sitemaps']['image'], __('Guide to enable XML image sitemap - new window', 'siteseo'))); ?>
</label>


<p class="description">
	<?php esc_html_e('Images in XML sitemaps are visible only from the source code.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_xml_sitemap_author_enable_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_author_enable']); ?>

<label for="siteseo_xml_sitemap_author_enable">
	<input id="siteseo_xml_sitemap_author_enable"
		name="siteseo_xml_sitemap_option_name[xml_sitemap_author_enable]" type="checkbox" <?php checked($check, '1') ?>
	value="1"/>
	<?php esc_html_e('Enable Author Sitemap', 'siteseo'); ?>
</label>

<p class="description">
	<?php esc_html_e('Make sure to enable author archive from SEO, titles and metas, archives tab.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_xml_sitemap_html_enable_callback(){
	$docs  = siteseo_get_docs_links();

	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_html_enable']); ?>


<label for="siteseo_xml_sitemap_html_enable">
	<input id="siteseo_xml_sitemap_html_enable"
		name="siteseo_xml_sitemap_option_name[xml_sitemap_html_enable]" type="checkbox" <?php checked($check, '1') ?>
	value="1"/>
	<?php esc_html_e('Enable HTML Sitemap', 'siteseo'); ?>
	<?php echo wp_kses_post(siteseo_tooltip_link($docs['sitemaps']['html'], __('Guide to enable a HTML Sitemap - new window', 'siteseo'))); ?>
</label>


<?php 
}

function siteseo_xml_sitemap_post_types_list_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_post_types_list']);

	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();

	$postTypes[] = get_post_type_object('attachment');

	$postTypes = apply_filters( 'siteseo_sitemaps_cpt', $postTypes );

	foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) { ?>
<h3>
	<?php echo esc_html($siteseo_cpt_value->labels->name); ?>
	<em><small>[<?php echo esc_html($siteseo_cpt_value->name); ?>]</small></em>
</h3>

<!--List all post types-->
<div class="siteseo_wrap_single_cpt">

	<?php
		$options = get_option('siteseo_xml_sitemap_option_name');
		$check = isset($options['xml_sitemap_post_types_list'][$siteseo_cpt_key]['include']);
		?>

	<label
		for="siteseo_xml_sitemap_post_types_list_include[<?php echo esc_attr($siteseo_cpt_key); ?>]">
		<input
			id="siteseo_xml_sitemap_post_types_list_include[<?php echo esc_attr($siteseo_cpt_key); ?>]"
			name="siteseo_xml_sitemap_option_name[xml_sitemap_post_types_list][<?php echo esc_attr($siteseo_cpt_key); ?>][include]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Include', 'siteseo'); ?>
	</label>

	<?php if ('attachment' == $siteseo_cpt_value->name) { ?>
	<div class="siteseo-notice is-warning is-inline">
		<p>
			<?php echo wp_kses_post(__('You should never include <strong>attachment</strong> post type in your sitemap. Be careful if you checked this.', 'siteseo')); ?>
		</p>
	</div>
	<?php } ?>

</div>
<?php
	}
}

function siteseo_xml_sitemap_taxonomies_list_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_taxonomies_list']);

	$taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();

	$taxonomies = apply_filters( 'siteseo_sitemaps_tax', $taxonomies );

	foreach ($taxonomies as $siteseo_tax_key => $siteseo_tax_value) { ?>
<h3>
	<?php echo esc_html($siteseo_tax_value->labels->name); ?>
	<em><small>[<?php echo esc_html($siteseo_tax_value->name); ?>]</small></em>
</h3>

<!--List all taxonomies-->
<div class="siteseo_wrap_single_tax">

	<?php $options = get_option('siteseo_xml_sitemap_option_name');

		$check = isset($options['xml_sitemap_taxonomies_list'][$siteseo_tax_key]['include']); ?>

	<label
		for="siteseo_xml_sitemap_taxonomies_list_include[<?php echo esc_attr($siteseo_tax_key); ?>]">
		<input
			id="siteseo_xml_sitemap_taxonomies_list_include[<?php echo esc_attr($siteseo_tax_key); ?>]"
			name="siteseo_xml_sitemap_option_name[xml_sitemap_taxonomies_list][<?php echo esc_attr($siteseo_tax_key); ?>][include]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Include', 'siteseo'); ?>
	</label>

</div>

<?php
	}
}

function siteseo_xml_sitemap_html_mapping_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');
	$check = isset($options['xml_sitemap_html_mapping']) ? $options['xml_sitemap_html_mapping'] : null;

	printf(
		'<input type="text" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_mapping]" placeholder="' . esc_html__('eg: 2, 28, 68', 'siteseo') . '" aria-label="' . esc_html__('Enter a post, page or custom post type ID(s) to display the sitemap', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_xml_sitemap_html_exclude_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');
	$check = isset($options['xml_sitemap_html_exclude']) ? $options['xml_sitemap_html_exclude'] : null;

	printf(
		'<input type="text" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_exclude]" placeholder="' . esc_html__('eg: 13, 8, 38', 'siteseo') . '" aria-label="' . esc_html__('Exclude some Posts, Pages, Custom Post Types or Terms IDs', 'siteseo') . '" value="%s"/>',
		esc_html($check)
	);
}

function siteseo_xml_sitemap_html_order_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');

	$selected = isset($options['xml_sitemap_html_order']) ? $options['xml_sitemap_html_order'] : null; ?>

<select id="siteseo_xml_sitemap_html_order" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_order]">
	<option <?php if ('DESC' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="DESC"><?php esc_html_e('DESC (descending order from highest to lowest values (3, 2, 1; c, b, a))', 'siteseo'); ?>
	</option>
	<option <?php if ('ASC' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="ASC"><?php esc_html_e('ASC (ascending order from lowest to highest values (1, 2, 3; a, b, c))', 'siteseo'); ?>
	</option>
</select>

<?php
}

function siteseo_xml_sitemap_html_orderby_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');

	$selected = isset($options['xml_sitemap_html_orderby']) ? $options['xml_sitemap_html_orderby'] : null; ?>

<select id="siteseo_xml_sitemap_html_orderby"
	name="siteseo_xml_sitemap_option_name[xml_sitemap_html_orderby]">
	<option <?php if ('date' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="date"><?php esc_html_e('Default (date)', 'siteseo'); ?>
	</option>
	<option <?php if ('title' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="title"><?php esc_html_e('Post Title', 'siteseo'); ?>
	</option>
	<option <?php if ('modified' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="modified"><?php esc_html_e('Modified date', 'siteseo'); ?>
	</option>
	<option <?php if ('ID' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="ID"><?php esc_html_e('Post ID', 'siteseo'); ?>
	</option>
	<option <?php if ('menu_order' == $selected) { ?>
		selected="selected"
		<?php } ?>
		value="menu_order"><?php esc_html_e('Menu order', 'siteseo'); ?>
	</option>
</select>

<?php
}

function siteseo_xml_sitemap_html_date_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_html_date']); ?>

<label for="siteseo_xml_sitemap_html_date">
	<input id="siteseo_xml_sitemap_html_date" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_date]"
		type="checkbox" <?php if ('1' == $check) { ?>
	checked="yes"
	<?php } ?>
	value="1"/>
	<?php esc_html_e('Disable date after each post, page, post type?', 'siteseo'); ?>
</label>

<?php
}

function siteseo_xml_sitemap_html_archive_links_callback(){
	$options = get_option('siteseo_xml_sitemap_option_name');

	$check = isset($options['xml_sitemap_html_archive_links']); ?>

<label for="siteseo_xml_sitemap_html_archive_links">
	<input id="siteseo_xml_sitemap_html_archive_links"
		name="siteseo_xml_sitemap_option_name[xml_sitemap_html_archive_links]" type="checkbox" <?php checked($check, '1') ?>
	value="1"/>
	<?php esc_html_e('Remove links from archive pages (eg: Products)', 'siteseo'); ?>
</label>

<?php
}

function siteseo_print_section_info_xml_sitemap_general(){
	$docs = siteseo_get_docs_links(); ?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('General', 'siteseo'); ?>
	</h2>
</div>

<?php if ('' == get_option('permalink_structure')) { ?>
<div class="siteseo-notice is-error">
	<p>
		<?php echo wp_kses_post(__('Your permalinks are not <strong>SEO Friendly</strong>! Enable <strong>pretty permalinks</strong> to fix this.', 'siteseo')); ?>
	</p>
	<p>
		<a href="<?php echo esc_url(admin_url('options-permalink.php')); ?>"
			class="btn btnSecondary">
			<?php esc_html_e('Change this settings', 'siteseo'); ?>
		</a>
	</p>
</div>
<?php } ?>

<p>
	<?php echo wp_kses_post(__('A sitemap is a file where you provide information about the <strong>pages, images, videos... and the relationships between them</strong>. Search engines like Google read this file to <strong>crawl your site more efficiently</strong>.', 'siteseo')); ?>
</p>

<p>
	<?php echo wp_kses_post(__('The XML sitemap is an <strong>exploration aid</strong>. Not having a sitemap will absolutely <strong>NOT prevent engines from indexing your content</strong>. For this, opt for meta robots.', 'siteseo')); ?>
</p>

<p><?php esc_html_e('This is the URL of your index sitemaps to submit to search engines:','siteseo'); ?></p>

<p>
	<pre><span class="dashicons dashicons-external"></span><a href="<?php echo esc_url(get_option('home')); ?>/sitemaps.xml" target="_blank"><?php echo esc_url(get_option('home')); ?>/sitemaps.xml</a></pre>
</p>

<p>
	<button type="button" id="siteseo-flush-permalinks" class="btn btnSecondary">
		<?php esc_html_e('Flush permalinks', 'siteseo'); ?>
	</button>
	<span class="spinner"></span>
</p>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<div>
		<p>
			<?php echo wp_kses_post(__('To view your sitemap, <strong>enable permalinks</strong> (not default one), and save settings to flush them.', 'siteseo')); ?>
		</p>
		<p>
			<?php echo wp_kses_post(__('<strong>Noindex content</strong> will not be displayed in Sitemaps. Same for custom canonical URLs.', 'siteseo')); ?>
		</p>
		<p>
			<?php echo wp_kses_post(__('If you disable globally this feature (using the blue toggle from above), the <strong>native WordPress XML sitemaps</strong> will be re-activated.', 'siteseo')); ?>
		</p>

		<p class="siteseo-help">
		<span class="dashicons dashicons-external"></span>
			<a href="<?php echo esc_url($docs['sitemaps']['error']['blank']); ?>"
				target="_blank">
				<?php esc_html_e('Blank sitemap?', 'siteseo'); ?></a>

			<span class="dashicons dashicons-external"></span>
			<a href="<?php echo esc_url($docs['sitemaps']['error']['404']); ?>"
				target="_blank">
				<?php esc_html_e('404 error?', 'siteseo'); ?></a>

			<span class="dashicons dashicons-external"></span>
			<a href="<?php echo esc_url($docs['sitemaps']['error']['html']); ?>"
				target="_blank">
				<?php esc_html_e('HTML error? Exclude XML and XSL from caching plugins!', 'siteseo'); ?></a>
			<span class="dashicons dashicons-external"></span>
			<a href="<?php echo esc_url(array_shift($docs['get_started']['sitemaps'])); ?>"
				target="_blank">
				<?php esc_html_e('Add your XML sitemaps to Google Search Console (video)', 'siteseo'); ?></a>
		</p>
	</div>
</div>

<?php if (isset($_SERVER['SERVER_SOFTWARE'])) {
		$server_software = explode('/', sanitize_text_field(wp_unslash($_SERVER['SERVER_SOFTWARE'])));
		reset($server_software);

		if ('nginx' == current($server_software)) { //IF NGINX
			?>
<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<div>
		<p>
			<?php esc_html_e('Your server uses NGINX. If XML Sitemaps doesn\'t work properly, you need to add this rule to your configuration:', 'siteseo'); ?>
		</p>

		<pre>location ~ (([^/]*)sitemap(.*)|news|author|video(.*))\.x(m|s)l$ {
		## SiteSEO
		rewrite ^.*/sitemaps\.xml$ /index.php?siteseo_sitemap=1 last;
		rewrite ^.*/news.xml$ /index.php?siteseo_news=$1 last;
		rewrite ^.*/video.xml$ /index.php?siteseo_video=$1 last;
		rewrite ^.*/author.xml$ /index.php?siteseo_author=$1 last;
		rewrite ^.*/sitemaps_xsl\.xsl$ /index.php?siteseo_sitemap_xsl=1 last;
		rewrite ^.*/sitemaps_video_xsl\.xsl$ /index.php?siteseo_sitemap_video_xsl=1 last;
		rewrite ^.*/([^/]+?)-sitemap([0-9]+)?.xml$ /index.php?siteseo_cpt=$1&siteseo_paged=$2 last;
	}</pre>
	</div>
</div>
<?php }
	} ?>

<?php
}

function siteseo_print_section_info_html_sitemap(){
	$docs = siteseo_get_docs_links(); ?>

<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('HTML Sitemap', 'siteseo'); ?>
	</h2>
</div>

<p>
	<?php esc_html_e('Create an HTML Sitemap for your visitors and boost your SEO.', 'siteseo'); ?>
</p>
<p>
	<?php esc_html_e('Limited to 1,000 posts per post type. You can change the order and sorting criteria below.', 'siteseo'); ?>

	<a class="siteseo-doc"
		href="<?php echo esc_url($docs['sitemaps']['html']); ?>"
		target="_blank">
		<span class="dashicons dashicons-editor-help"></span>
		<span class="screen-reader-text">
			<?php esc_html_e('Guide to enable a HTML Sitemap - new window', 'siteseo'); ?>
		</span>
	</a>
</p>


<div class="siteseo-notice">
		<span class="dashicons dashicons-info"></span>
		<div>
			<h3><?php esc_html_e('How to use the HTML Sitemap?', 'siteseo'); ?></h3>

			<h4><?php esc_html_e('Block Editor', 'siteseo'); ?></h4>
			<p><?php echo wp_kses_post(__('Add the HTML sitemap block using the <strong>Block Editor</strong>.', 'siteseo')); ?></p>

			<hr>
			<h4><?php esc_html_e('Shortcode', 'siteseo'); ?></h4>

			<p><?php esc_html_e('You can also use this shortcode in your content (post, page, post type...):', 'siteseo'); ?></p>
			<pre>[siteseo_html_sitemap]</pre>

			<p><?php esc_html_e('To include specific custom post types, use the CPT attribute:', 'siteseo'); ?></p>
			<pre>[siteseo_html_sitemap cpt="post,product"]</pre>

			<h4><?php esc_html_e('Other', 'siteseo'); ?></h4>
		<p><?php esc_html_e('Dynamically display the sitemap by entering an ID to the first field below.', 'siteseo'); ?></p>
		</div>
	</div>
<?php
}

function siteseo_print_section_info_xml_sitemap_post_types(){
	?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Post Types', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php esc_html_e('Include/Exclude Post Types.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_print_section_info_xml_sitemap_taxonomies(){
	?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Taxonomies', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php esc_html_e('Include/Exclude Taxonomies.', 'siteseo'); ?>
</p>

<?php
}

//XML Sitemap SECTION======================================================================
add_settings_section(
	'siteseo_setting_section_xml_sitemap_general', // ID
	'',
	//__("General","siteseo"), // Title
	'siteseo_print_section_info_xml_sitemap_general', // Callback
	'siteseo-settings-admin-xml-sitemap-general' // Page
);

add_settings_field(
	'siteseo_xml_sitemap_general_enable', // ID
	__('Enable XML Sitemap', 'siteseo'), // Title
	'siteseo_xml_sitemap_general_enable_callback', // Callback
	'siteseo-settings-admin-xml-sitemap-general', // Page
	'siteseo_setting_section_xml_sitemap_general' // Section
);

add_settings_field(
	'siteseo_xml_sitemap_img_enable', // ID
	__('Enable XML Image Sitemap', 'siteseo'), // Title
	'siteseo_xml_sitemap_img_enable_callback', // Callback
	'siteseo-settings-admin-xml-sitemap-general', // Page
	'siteseo_setting_section_xml_sitemap_general' // Section
);

do_action('siteseo_settings_sitemaps_image_after');

add_settings_field(
	'siteseo_xml_sitemap_author_enable', // ID
	__('Enable Author Sitemap', 'siteseo'), // Title
	'siteseo_xml_sitemap_author_enable_callback', // Callback
	'siteseo-settings-admin-xml-sitemap-general', // Page
	'siteseo_setting_section_xml_sitemap_general' // Section
);

add_settings_field(
	'siteseo_xml_sitemap_html_enable', // ID
	__('Enable HTML Sitemap', 'siteseo'), // Title
	'siteseo_xml_sitemap_html_enable_callback', // Callback
	'siteseo-settings-admin-xml-sitemap-general', // Page
	'siteseo_setting_section_xml_sitemap_general' // Section
);

add_settings_section(
	'siteseo_setting_section_xml_sitemap_post_types', // ID
	'',
	//__("Post Types","siteseo"), // Title
	'siteseo_print_section_info_xml_sitemap_post_types', // Callback
	'siteseo-settings-admin-xml-sitemap-post-types' // Page
);

add_settings_field(
	'siteseo_xml_sitemap_post_types_list', // ID
	__('Check to INCLUDE Post Types', 'siteseo'), // Title
	'siteseo_xml_sitemap_post_types_list_callback', // Callback
	'siteseo-settings-admin-xml-sitemap-post-types', // Page
	'siteseo_setting_section_xml_sitemap_post_types' // Section
);

add_settings_section(
	'siteseo_setting_section_xml_sitemap_taxonomies', // ID
	'',
	//__("Taxonomies","siteseo"), // Title
	'siteseo_print_section_info_xml_sitemap_taxonomies', // Callback
	'siteseo-settings-admin-xml-sitemap-taxonomies' // Page
);

add_settings_field(
	'siteseo_xml_sitemap_taxonomies_list', // ID
	__('Check to INCLUDE Taxonomies', 'siteseo'), // Title
	'siteseo_xml_sitemap_taxonomies_list_callback', // Callback
	'siteseo-settings-admin-xml-sitemap-taxonomies', // Page
	'siteseo_setting_section_xml_sitemap_taxonomies' // Section
);

add_settings_section(
	'siteseo_setting_section_html_sitemap', // ID
	'',
	//__("HTML Sitemap","siteseo"), // Title
	'siteseo_print_section_info_html_sitemap', // Callback
	'siteseo-settings-admin-html-sitemap' // Page
);

add_settings_field(
	'siteseo_xml_sitemap_html_mapping', // ID
	__('Enter a post, page or custom post type ID(s) to display the sitemap', 'siteseo'), // Title
	'siteseo_xml_sitemap_html_mapping_callback', // Callback
	'siteseo-settings-admin-html-sitemap', // Page
	'siteseo_setting_section_html_sitemap' // Section
);

add_settings_field(
	'siteseo_xml_sitemap_html_exclude', // ID
	__('Exclude some Posts, Pages, Custom Post Types or Terms IDs', 'siteseo'), // Title
	'siteseo_xml_sitemap_html_exclude_callback', // Callback
	'siteseo-settings-admin-html-sitemap', // Page
	'siteseo_setting_section_html_sitemap' // Section
);

add_settings_field(
	'siteseo_xml_sitemap_html_order', // ID
	__('Sort order', 'siteseo'), // Title
	'siteseo_xml_sitemap_html_order_callback', // Callback
	'siteseo-settings-admin-html-sitemap', // Page
	'siteseo_setting_section_html_sitemap' // Section
);

add_settings_field(
	'siteseo_xml_sitemap_html_orderby', // ID
	__('Order posts by', 'siteseo'), // Title
	'siteseo_xml_sitemap_html_orderby_callback', // Callback
	'siteseo-settings-admin-html-sitemap', // Page
	'siteseo_setting_section_html_sitemap' // Section
);

add_settings_field(
	'siteseo_xml_sitemap_html_date', // ID
	__('Disable the display of the publication date', 'siteseo'), // Title
	'siteseo_xml_sitemap_html_date_callback', // Callback
	'siteseo-settings-admin-html-sitemap', // Page
	'siteseo_setting_section_html_sitemap' // Section
);

add_settings_field(
	'siteseo_xml_sitemap_html_archive_links', // ID
	__('Remove links from archive pages', 'siteseo'), // Title
	'siteseo_xml_sitemap_html_archive_links_callback', // Callback
	'siteseo-settings-admin-html-sitemap', // Page
	'siteseo_setting_section_html_sitemap' // Section
);

$this->options = get_option('siteseo_xml_sitemap_option_name');
if (function_exists('siteseo_admin_header')) {
	siteseo_admin_header();
} ?>
<form method="post" action="<?php echo esc_url(admin_url('options.php')); ?>" class="siteseo-option" name="siteseo-flush">

	<?php settings_fields('siteseo_xml_sitemap_option_group'); ?>

	<div id="siteseo-tabs" class="wrap">
		<?php
		echo wp_kses($this->siteseo_feature_title('xml-sitemap'), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);
		$current_tab = '';
		$plugin_settings_tabs	= [
			'tab_siteseo_xml_sitemap_general'	=> __('General', 'siteseo'),
			'tab_siteseo_xml_sitemap_post_types' => __('Post Types', 'siteseo'),
			'tab_siteseo_xml_sitemap_taxonomies' => __('Taxonomies', 'siteseo'),
			'tab_siteseo_html_sitemap'		   => __('HTML Sitemap', 'siteseo'),
		];

echo '<div class="nav-tab-wrapper">';
foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
	echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-xml-sitemap#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
}
echo '</div>'; ?>
				<div class="siteseo-tab <?php if ('tab_siteseo_xml_sitemap_general' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_xml_sitemap_general"><?php do_settings_sections('siteseo-settings-admin-xml-sitemap-general'); ?></div>
				<div class="siteseo-tab <?php if ('tab_siteseo_xml_sitemap_post_types' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_xml_sitemap_post_types"><?php do_settings_sections('siteseo-settings-admin-xml-sitemap-post-types'); ?></div>
				<div class="siteseo-tab <?php if ('tab_siteseo_xml_sitemap_taxonomies' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_xml_sitemap_taxonomies"><?php do_settings_sections('siteseo-settings-admin-xml-sitemap-taxonomies'); ?></div>
				<div class="siteseo-tab <?php if ('tab_siteseo_html_sitemap' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_html_sitemap"><?php do_settings_sections('siteseo-settings-admin-html-sitemap'); ?></div>
		</div>

		<?php siteseo_submit_button(__('Save changes', 'siteseo')); ?>
	</form>
<?php
