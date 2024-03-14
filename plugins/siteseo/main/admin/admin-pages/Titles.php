<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

//Titles & metas
function siteseo_titles_sep_callback(){
	
	$options = get_option('siteseo_titles_option_name');
	$check   = isset($options['titles_sep']) ? $options['titles_sep'] : null; ?>

	<input type="text" id="siteseo_titles_sep" name="siteseo_titles_option_name[titles_sep]"
		placeholder="<?php esc_html_e('Enter your separator, eg: "-"', 'siteseo'); ?>"
		aria-label="<?php esc_html_e('Separator', 'siteseo'); ?>"
		value="<?php echo esc_html($check); ?>" />

	<p class="description">
		<?php esc_html_e('Use this separator with %%sep%% in your title and meta description.', 'siteseo'); ?>
	</p>

<?php
}

function siteseo_titles_home_site_title_callback(){
	
	$options = get_option('siteseo_titles_option_name');
	$check = isset($options['titles_home_site_title']) ? $options['titles_home_site_title'] : null; ?>

<input type="text" id="siteseo_titles_home_site_title"
	name="siteseo_titles_option_name[titles_home_site_title]"
	placeholder="<?php esc_html_e('My awesome website', 'siteseo'); ?>"
	aria-label="<?php esc_html_e('Site title', 'siteseo'); ?>"
	value="<?php echo esc_html($check); ?>" />

<div class="wrap-tags">
	<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title" data-tag="%%sitetitle%%">
		<span class="dashicons dashicons-insert"></span>
		<?php esc_html_e('Site Title', 'siteseo'); ?>
	</button>

	<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-sep" data-tag="%%sep%%">
		<span class="dashicons dashicons-insert"></span>
		<?php esc_html_e('Separator', 'siteseo'); ?>
	</button>

	<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-desc" data-tag="%%tagline%%">
		<span class="dashicons dashicons-insert"></span>
		<?php esc_html_e('Tagline', 'siteseo'); ?>
	</button>

	<?php siteseo_render_dyn_variables('tag-title');
}

function siteseo_titles_home_site_title_alt_callback(){
	
	$options = get_option('siteseo_titles_option_name');
	$check = isset($options['titles_home_site_title_alt']) ? $options['titles_home_site_title_alt'] : null;
	$docs = siteseo_get_docs_links();
	?>

	<input type="text" id="siteseo_titles_home_site_title_alt"
		name="siteseo_titles_option_name[titles_home_site_title_alt]"
		placeholder="<?php esc_html_e('My alternative site title', 'siteseo'); ?>"
		aria-label="<?php esc_html_e('Alternative site title', 'siteseo'); ?>"
		value="<?php echo esc_html($check); ?>" />

	<p class="description"><?php printf(wp_kses_post(__('The alternate name of the website (for example, if there\'s a commonly recognized acronym or shorter name for your site), if applicable. Make sure the name meets the <a href="%s" target="_blank">content guidelines</a>.<span class="dashicons dashicons-external"></span>','siteseo')), esc_url($docs['titles']['alt_title'])); ?></p>

	<?php
}

function siteseo_titles_home_site_desc_callback(){
	
	$options = get_option('siteseo_titles_option_name');
	$check = isset($options['titles_home_site_desc']) ? $options['titles_home_site_desc'] : null; ?>

	<textarea id="siteseo_titles_home_site_desc" name="siteseo_titles_option_name[titles_home_site_desc]"
		placeholder="<?php esc_html_e('This is a cool website about Wookiees', 'siteseo'); ?>"
		aria-label="<?php esc_html_e('Meta description', 'siteseo'); ?>"><?php echo esc_html($check); ?></textarea>

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-meta-desc" data-tag="%%tagline%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Tagline', 'siteseo'); ?>
		</button>

		<?php siteseo_render_dyn_variables('tag-description');

	if (get_option('page_for_posts')) { ?>
		<p>
			<a
				href="<?php echo esc_url(admin_url('post.php?post=' . get_option('page_for_posts') . '&action=edit')); ?>">
				<?php esc_html_e('Looking to edit your blog page?', 'siteseo'); ?>
			</a>
		</p>
<?php }
}

//Single CPT
function siteseo_titles_single_titles_callback(){
	
	echo wp_kses_post(siteseo_get_empty_templates('cpt', 'title'));
	echo wp_kses_post(siteseo_get_empty_templates('cpt', 'description'));

	$docs = siteseo_get_docs_links();

	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
		?>
		<h3 id="siteseo-post-type-<?php echo esc_attr(str_replace(" ","-",strtolower(trim($siteseo_cpt_value->labels->name))))?>">
			<?php echo esc_html($siteseo_cpt_value->labels->name); ?>
			<em>
				<small>[<?php echo esc_html($siteseo_cpt_value->name); ?>]</small>
			</em>
			<!--Single on/off CPT-->
			<div class="siteseo_wrap_single_cpt">

				<?php $options = get_option('siteseo_titles_option_name');
			$check = isset($options['titles_single_titles'][$siteseo_cpt_key]['enable']) ? $options['titles_single_titles'][$siteseo_cpt_key]['enable'] : null; ?>

				<input
					id="siteseo_titles_single_cpt_enable[<?php echo esc_attr($siteseo_cpt_key); ?>]"
					data-id=<?php echo esc_attr($siteseo_cpt_key); ?>
				name="siteseo_titles_option_name[titles_single_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][enable]" class="toggle"
				type="checkbox"
				<?php if ('1' == $check) { ?>
				checked="yes" data-toggle="0"
				<?php } else { ?>
				data-toggle="1"
				<?php } ?>
				value="1"/>

				<label
					for="siteseo_titles_single_cpt_enable[<?php echo esc_attr($siteseo_cpt_key); ?>]">
					<?php esc_html_e('Click to hide any SEO metaboxes / columns for this post type', 'siteseo'); ?>
				</label>

				<?php if ('1' == $check) { ?>
				<span id="titles-state-default" class="feature-state">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					<?php esc_html_e('Click to display any SEO metaboxes / columns for this post type', 'siteseo'); ?>
				</span>
				<span id="titles-state" class="feature-state feature-state-off">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					<?php esc_html_e('Click to hide any SEO metaboxes / columns for this post type', 'siteseo'); ?>
				</span>
				<?php } else { ?>
				<span id="titles-state-default" class="feature-state">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					<?php esc_html_e('Click to hide any SEO metaboxes / columns for this post type', 'siteseo'); ?>
				</span>
				<span id="titles-state" class="feature-state feature-state-off">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					<?php esc_html_e('Click to display any SEO metaboxes / columns for this post type', 'siteseo'); ?>
				</span>
				<?php }

		$toggle_txt_on  = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to display any SEO metaboxes / columns for this post type', 'siteseo');
		$toggle_txt_off = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to hide any SEO metaboxes / columns for this post type', 'siteseo'); ?>
				<script>
					jQuery(document).ready(function($) {
						$('input[data-id=<?php echo esc_attr($siteseo_cpt_key); ?>]')
							.on('click', function() {
								$(this).attr('data-toggle', $(this).attr('data-toggle') == '1' ? '0' : '1');
								if ($(this).attr('data-toggle') == '1') {
									$(this).next().next('.feature-state').html(
										'<?php echo wp_kses_post($toggle_txt_off); ?>'
									);
								} else {
									$(this).next().next('.feature-state').html(
										'<?php echo wp_kses_post($toggle_txt_on); ?>'
									);
								}
							});
					});
				</script>

			</div>
		</h3>


		<!--Single Title CPT-->
		<div class="siteseo_wrap_single_cpt">
			<p>
				<?php esc_html_e('Title template', 'siteseo'); ?>
			</p>

			<?php
		 $check = isset($options['titles_single_titles'][$siteseo_cpt_key]['title']) ? $options['titles_single_titles'][$siteseo_cpt_key]['title'] : null; ?>
			<script>
				jQuery(document).ready(function($) {
					$('#siteseo-tag-single-title-<?php echo esc_attr($siteseo_cpt_key); ?>')
						.click(function() {
							$('#siteseo_titles_single_titles_<?php echo esc_attr($siteseo_cpt_key); ?>')
								.val(siteseo_get_field_length($(
									'#siteseo_titles_single_titles_<?php echo esc_attr($siteseo_cpt_key); ?>'
								)) + $(
									'#siteseo-tag-single-title-<?php echo esc_attr($siteseo_cpt_key); ?>'
								).attr('data-tag'));
						});
					$('#siteseo-tag-sep-<?php echo esc_attr($siteseo_cpt_key); ?>')
						.click(function() {
							$('#siteseo_titles_single_titles_<?php echo esc_attr($siteseo_cpt_key); ?>')
								.val(siteseo_get_field_length($(
									'#siteseo_titles_single_titles_<?php echo esc_attr($siteseo_cpt_key); ?>'
								)) + $(
									'#siteseo-tag-sep-<?php echo esc_attr($siteseo_cpt_key); ?>'
								).attr('data-tag'));
						});
					$('#siteseo-tag-single-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>')
						.click(function() {
							$('#siteseo_titles_single_titles_<?php echo esc_attr($siteseo_cpt_key); ?>')
								.val(siteseo_get_field_length($(
									'#siteseo_titles_single_titles_<?php echo esc_attr($siteseo_cpt_key); ?>'
								)) + $(
									'#siteseo-tag-single-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>'
								).attr('data-tag'));
						});
				});
			</script>

			<?php printf(
			'<input type="text" id="siteseo_titles_single_titles_' . esc_attr($siteseo_cpt_key) . '" name="siteseo_titles_option_name[titles_single_titles][' . esc_attr($siteseo_cpt_key) . '][title]" value="%s"/>',
			esc_html($check)
		); ?>

			<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-single-title-<?php echo esc_attr($siteseo_cpt_key); ?>"
					data-tag="%%post_title%%">
					<span class="dashicons dashicons-insert"></span>
					<?php esc_html_e('Post Title', 'siteseo'); ?>
				</button>

				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-sep-<?php echo esc_attr($siteseo_cpt_key); ?>"
					data-tag="%%sep%%">
					<span class="dashicons dashicons-insert"></span>
					<?php esc_html_e('Separator', 'siteseo'); ?>
				</button>

				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-single-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>"
					data-tag="%%sitetitle%%">
					<span class="dashicons dashicons-insert"></span>
					<?php esc_html_e('Site Title', 'siteseo'); ?>
				</button>

				<?php
				siteseo_render_dyn_variables('tag-title'); ?>
			</div>

			<!--Single Meta Description CPT-->
			<div class="siteseo_wrap_single_cpt">
				<p>
					<?php esc_html_e('Meta description template', 'siteseo'); ?>
				</p>

				<?php
		$check = isset($options['titles_single_titles'][$siteseo_cpt_key]['description']) ? $options['titles_single_titles'][$siteseo_cpt_key]['description'] : null; ?>

				<script>
					jQuery(document).ready(function($) {
						$('#siteseo-tag-single-desc-<?php echo esc_attr($siteseo_cpt_key); ?>')
							.click(function() {
								$('#siteseo_titles_single_desc_<?php echo esc_attr($siteseo_cpt_key); ?>')
									.val(siteseo_get_field_length($(
										'#siteseo_titles_single_desc_<?php echo esc_attr($siteseo_cpt_key); ?>'
									)) + $(
										'#siteseo-tag-single-desc-<?php echo esc_attr($siteseo_cpt_key); ?>'
									).attr('data-tag'));
							});
					});
				</script>

				<?php printf(
			'<textarea id="siteseo_titles_single_desc_' . esc_attr($siteseo_cpt_key) . '" name="siteseo_titles_option_name[titles_single_titles][' . esc_attr($siteseo_cpt_key) . '][description]">%s</textarea>',
			esc_html($check)
		); ?>
				<div class="wrap-tags">
					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-single-desc-<?php echo esc_attr($siteseo_cpt_key); ?>"
						data-tag="%%post_excerpt%%">
						<span class="dashicons dashicons-insert"></span>
						<?php esc_html_e('Post excerpt', 'siteseo'); ?>
					</button>
					<?php
					siteseo_render_dyn_variables('tag-description'); ?>
				</div>
			</div>

			<!--Single No-Index CPT-->
			<div class="siteseo_wrap_single_cpt">

				<?php
		$options = get_option('siteseo_titles_option_name');

		$check = isset($options['titles_single_titles'][$siteseo_cpt_key]['noindex']); ?>

				<label
					for="siteseo_titles_single_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]">
					<input
						id="siteseo_titles_single_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]"
						name="siteseo_titles_option_name[titles_single_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][noindex]"
						type="checkbox" <?php if ('1' == $check) { ?>
					checked="yes"
					<?php } ?>
					value="1"/>

					<?php echo wp_kses_post(__('Do not display this single post type in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
				</label>

				<?php $cpt_in_sitemap = siteseo_get_service('SitemapOption')->getPostTypesList();

		if ('1' == $check && isset($cpt_in_sitemap[$siteseo_cpt_key]) && '1' === $cpt_in_sitemap[$siteseo_cpt_key]['include']) { ?>
				<div class="siteseo-notice is-error is-inline">
					<p>
						<?php printf(wp_kses_post(__('This custom post type is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. We recommend that you <a href="%s">check this out here</a>.', 'siteseo')), esc_url(admin_url('admin.php?page=siteseo-xml-sitemap'))); ?>
					</p>
				</div>
				<?php }
				?>

			</div>

			<!--Single No-Follow CPT-->
			<div class="siteseo_wrap_single_cpt">

				<?php
		$options = get_option('siteseo_titles_option_name');

		$check = isset($options['titles_single_titles'][$siteseo_cpt_key]['nofollow']); ?>

				<label
					for="siteseo_titles_single_cpt_nofollow[<?php echo esc_attr($siteseo_cpt_key); ?>]">
					<input
						id="siteseo_titles_single_cpt_nofollow[<?php echo esc_attr($siteseo_cpt_key); ?>]"
						name="siteseo_titles_option_name[titles_single_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][nofollow]"
						type="checkbox" <?php if ('1' == $check) { ?>
					checked="yes"
					<?php } ?>
					value="1"/>

					<?php echo wp_kses_post(__('Do not follow links for this single post type <strong>(nofollow)</strong>', 'siteseo')); ?>
				</label>

			</div>

			<!--Single Published / modified date CPT-->
			<div class="siteseo_wrap_single_cpt">

				<?php $options = get_option('siteseo_titles_option_name');

		$check = isset($options['titles_single_titles'][$siteseo_cpt_key]['date']); ?>

				<label
					for="siteseo_titles_single_cpt_date[<?php echo esc_attr($siteseo_cpt_key); ?>]">
					<input
						id="siteseo_titles_single_cpt_date[<?php echo esc_attr($siteseo_cpt_key); ?>]"
						name="siteseo_titles_option_name[titles_single_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][date]"
						type="checkbox" <?php if ('1' == $check) { ?>
					checked="yes"
					<?php } ?>
					value="1"/>

					<?php echo wp_kses_post(__('Display date in Google search results by adding <code>article:published_time</code> and <code>article:modified_time</code> meta?', 'siteseo')); ?>
				</label>

				<p class="description">
					<?php esc_html_e('Unchecking this doesn\'t prevent Google to display post date in search results.', 'siteseo'); ?>
				</p>

			</div>

			<!--Single meta thumbnail CPT-->
			<div class="siteseo_wrap_single_cpt">

				<?php $options = get_option('siteseo_titles_option_name');

		$check = isset($options['titles_single_titles'][$siteseo_cpt_key]['thumb_gcs']); ?>

				<label
					for="siteseo_titles_single_cpt_thumb_gcs[<?php echo esc_attr($siteseo_cpt_key); ?>]">
					<input
						id="siteseo_titles_single_cpt_thumb_gcs[<?php echo esc_attr($siteseo_cpt_key); ?>]"
						name="siteseo_titles_option_name[titles_single_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][thumb_gcs]"
						type="checkbox" <?php if ('1' == $check) { ?>
					checked="yes"
					<?php } ?>
					value="1"/>

					<?php esc_html_e('Display post thumbnail in Google Custom Search results?', 'siteseo'); ?>
				</label>

				<p class="description">
					<?php printf(wp_kses_post(__('This option does not apply to traditional search results. <a href="%s" target="_blank">Learn more</a>', 'siteseo')), esc_url($docs['titles']['thumbnail'])); ?><span
						class="dashicons dashicons-external"></span>
				</p>
			</div>
			<?php
		if (empty($options['titles_single_titles'][$siteseo_cpt_key]['title'])) {
			$t[] = $siteseo_cpt_key;
		}
	}
}

//BuddyPress Groups
function siteseo_titles_bp_groups_title_callback(){
	if (is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php')) {
		$options = get_option('siteseo_titles_option_name'); ?>
		<h3>
			<?php esc_html_e('BuddyPress groups', 'siteseo'); ?>
		</h3>

		<p>
			<?php esc_html_e('Title template', 'siteseo'); ?>
		</p>

		<?php $check = isset($options['titles_bp_groups_title']) ? $options['titles_bp_groups_title'] : null; ?>

		<input id="siteseo_titles_bp_groups_title" type="text"
			name="siteseo_titles_option_name[titles_bp_groups_title]"
			value="<?php echo esc_html($check); ?>" />

		<div class="wrap-tags">
			<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-post-title-bd-groups" data-tag="%%post_title%%">
				<span class="dashicons dashicons-insert"></span>
				<?php esc_html_e('Post Title', 'siteseo'); ?>
			</button>
			<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-bd-groups" data-tag="%%sep%%">
				<span class="dashicons dashicons-insert"></span>
				<?php esc_html_e('Separator', 'siteseo'); ?>
			</button>

			<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-bd-groups" data-tag="%%sitetitle%%">
				<span class="dashicons dashicons-insert"></span>
				<?php esc_html_e('Site Title', 'siteseo'); ?>
			</button>

			<?php
			siteseo_render_dyn_variables('tag-title');
	}
}

function siteseo_titles_bp_groups_desc_callback(){
	if (is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php')) {
		$options = get_option('siteseo_titles_option_name'); ?>
		<p>
			<?php esc_html_e('Meta description template', 'siteseo'); ?>
		</p>

		<?php $check = isset($options['titles_bp_groups_desc']) ? $options['titles_bp_groups_desc'] : null; ?>

		<textarea name="siteseo_titles_option_name[titles_bp_groups_desc]"><?php echo esc_html($check); ?></textarea>

	<?php
	}
}

function siteseo_titles_bp_groups_noindex_callback(){
	if (is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php')) {
		$options = get_option('siteseo_titles_option_name');

		$check = isset($options['titles_bp_groups_noindex']); ?>

		<label for="siteseo_titles_bp_groups_noindex">
			<input id="siteseo_titles_bp_groups_noindex"
				name="siteseo_titles_option_name[titles_bp_groups_noindex]" type="checkbox" <?php checked($check, '1') ?>
			value="1"/>

			<?php echo wp_kses_post(__('Do not display BuddyPress groups in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
		</label>

	<?php
	}
}

//Taxonomies
function siteseo_titles_tax_titles_callback(){
	echo wp_kses_post(siteseo_get_empty_templates('tax', 'title'));
	echo wp_kses_post(siteseo_get_empty_templates('tax', 'description'));

	$taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();
	foreach ($taxonomies as $siteseo_tax_key => $siteseo_tax_value) { ?>
		<h3 id="siteseo-taxonomies-<?php echo esc_attr(str_replace(" ","-",strtolower(trim($siteseo_tax_value->labels->name))))?>">
			<?php echo esc_html($siteseo_tax_value->labels->name); ?>
			<em>
				<small>[<?php echo esc_html($siteseo_tax_value->name); ?>]</small>
			</em>
		</h3>

		<!--Single on/off Tax-->
		<div class="siteseo_wrap_tax">
		<?php
		$options = get_option('siteseo_titles_option_name');

		$check = isset($options['titles_tax_titles'][$siteseo_tax_key]['enable']) ? $options['titles_tax_titles'][$siteseo_tax_key]['enable'] : null;
		?>
			<input
				id="siteseo_titles_tax_titles_enable[<?php echo esc_attr($siteseo_tax_key); ?>]"
				data-id=<?php echo esc_attr($siteseo_tax_key); ?>
			name="siteseo_titles_option_name[titles_tax_titles][<?php echo esc_attr($siteseo_tax_key); ?>][enable]"
			class="toggle" type="checkbox"
			<?php if ('1' == $check) { ?>
			checked="yes" data-toggle="0"
			<?php } else { ?>
			data-toggle="1"
			<?php } ?>
			value="1"/>

			<label
				for="siteseo_titles_tax_titles_enable[<?php echo esc_attr($siteseo_tax_key); ?>]">
				<?php esc_html_e('Click to hide any SEO metaboxes for this taxonomy', 'siteseo'); ?>
			</label>

			<?php
if ('1' == $check) { ?>
			<span id="titles-state-default" class="feature-state">
				<span class="dashicons dashicons-arrow-left-alt"></span>
				<?php esc_html_e('Click to display any SEO metaboxes for this taxonomy', 'siteseo'); ?>
			</span>
			<span id="titles-state" class="feature-state feature-state-off">
				<span class="dashicons dashicons-arrow-left-alt"></span>
				<?php esc_html_e('Click to hide any SEO metaboxes for this taxonomy', 'siteseo'); ?>
			</span>
			<?php } else { ?>
			<span id="titles-state-default" class="feature-state">
				<span class="dashicons dashicons-arrow-left-alt"></span>
				<?php esc_html_e('Click to hide any SEO metaboxes for this taxonomy', 'siteseo'); ?>
			</span>
			<span id="titles-state" class="feature-state feature-state-off">
				<span class="dashicons dashicons-arrow-left-alt"></span>
				<?php esc_html_e('Click to display any SEO metaboxes for this taxonomy', 'siteseo'); ?>
			</span>
			<?php }

$toggle_txt_on  = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to display any SEO metaboxes for this taxonomy', 'siteseo');
$toggle_txt_off = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to hide any SEO metaboxes for this taxonomy', 'siteseo');
?>
			<script>
				jQuery(document).ready(function($) {
					$(' input[data-id=<?php echo esc_attr($siteseo_tax_key); ?>]')
						.on('click',
							function() {
								$(this).attr('data-toggle', $(this).attr('data-toggle') == '1' ? '0' :
									'1');
								if ($(this).attr('data-toggle') == '1') {
									$(this).next().next('.feature-state').html(
										'<?php echo wp_kses_post($toggle_txt_off); ?>'
									);
								} else {
									$(this).next().next('.feature-state').html(
										'<?php echo wp_kses_post($toggle_txt_on); ?>'
									);
								}
							});
				});
			</script>

		</div>

		<!--Tax Title-->
		<?php
	$check = isset($options['titles_tax_titles'][$siteseo_tax_key]['title']) ? $options['titles_tax_titles'][$siteseo_tax_key]['title'] : null;
?>

		<div class="siteseo_wrap_tax">
			<p>
				<?php esc_html_e('Title template', 'siteseo'); ?>
			</p>

			<script>
				jQuery(document).ready(function($) {
					$(' #siteseo-tag-tax-title-<?php echo esc_attr($siteseo_tax_key); ?>')
						.click(function() {
							$('#siteseo_titles_tax_titles_<?php echo esc_attr($siteseo_tax_key); ?>')
								.val(siteseo_get_field_length($(
									'#siteseo_titles_tax_titles_<?php echo esc_attr($siteseo_tax_key); ?>'
								)) + $(
									'#siteseo-tag-tax-title-<?php echo esc_attr($siteseo_tax_key); ?>'
								).attr('data-tag'));
						});
					$('#siteseo-tag-sep-<?php echo esc_attr($siteseo_tax_key); ?>')
						.click(function() {
							$('#siteseo_titles_tax_titles_<?php echo esc_attr($siteseo_tax_key); ?>')
								.val(siteseo_get_field_length($(
									'#siteseo_titles_tax_titles_<?php echo esc_attr($siteseo_tax_key); ?>'
								)) + $(
									'#siteseo-tag-sep-<?php echo esc_attr($siteseo_tax_key); ?>'
								).attr('data-tag'));
						});
					$('#siteseo-tag-tax-sitetitle-<?php echo esc_attr($siteseo_tax_key); ?>')
						.click(function() {
							$('#siteseo_titles_tax_titles_<?php echo esc_attr($siteseo_tax_key); ?>')
								.val(siteseo_get_field_length($(
									'#siteseo_titles_tax_titles_<?php echo esc_attr($siteseo_tax_key); ?>'
								)) + $(
									'#siteseo-tag-tax-sitetitle-<?php echo esc_attr($siteseo_tax_key); ?>'
								).attr('data-tag'));
						});
				});
			</script>

			<?php printf(
	'<input type="text" id="siteseo_titles_tax_titles_' . esc_attr($siteseo_tax_key) . '" name="siteseo_titles_option_name[titles_tax_titles][' . esc_attr($siteseo_tax_key) . '][title]" value="%s"/>',
	esc_html($check)
);

	if ('category' == $siteseo_tax_key) { ?>
		<div class=" wrap-tags">
			<span
				id="siteseo-tag-tax-title-<?php echo esc_attr($siteseo_tax_key); ?>"
				data-tag="%%_category_title%%" class="btn btnSecondary tag-title">
				<span class="dashicons dashicons-insert"></span>
				<?php esc_html_e('Category Title', 'siteseo'); ?>
			</span>
			<?php } elseif ('post_tag' == $siteseo_tax_key) { ?>
			<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-tax-title-<?php echo esc_attr($siteseo_tax_key); ?>"
					data-tag="%%tag_title%%">
					<span class="dashicons dashicons-insert"></span>
					<?php esc_html_e('Tag Title', 'siteseo'); ?>
				</button>
				<?php } else { ?>
				<div class="wrap-tags">
					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-tax-title-<?php echo esc_attr($siteseo_tax_key); ?>"
						data-tag="%%term_title%%">
						<span class="dashicons dashicons-insert"></span>
						<?php esc_html_e('Term Title', 'siteseo'); ?>
					</button>
					<?php } ?>

					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-sep-<?php echo esc_attr($siteseo_tax_key); ?>"
						data-tag="%%sep%%">
						<span class="dashicons dashicons-insert"></span>
						<?php esc_html_e('Separator', 'siteseo'); ?>
					</button>

					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-tax-sitetitle-<?php echo esc_attr($siteseo_tax_key); ?>"
						data-tag="%%sitetitle%%">
						<span class="dashicons dashicons-insert"></span>
						<?php esc_html_e('Site Title', 'siteseo'); ?>
					</button>

					<?php siteseo_render_dyn_variables('tag-title'); ?>
				</div>

				<!--Tax Meta Description-->
				<div class="siteseo_wrap_tax">
					<?php $check2 = isset($options['titles_tax_titles'][$siteseo_tax_key]['description']) ? $options['titles_tax_titles'][$siteseo_tax_key]['description'] : null; ?>

					<p>
						<?php esc_html_e('Meta description template', 'siteseo'); ?>
					</p>

					<script>
						jQuery(document).ready(function($) {
							$('#siteseo-tag-tax-desc-<?php echo esc_attr($siteseo_tax_key); ?>')
								.click(function() {
									$('#siteseo_titles_tax_desc_<?php echo esc_attr($siteseo_tax_key); ?>')
										.val(
											siteseo_get_field_length($(
												'#siteseo_titles_tax_desc_<?php echo esc_attr($siteseo_tax_key); ?>'
											)) + $(
												'#siteseo-tag-tax-desc-<?php echo esc_attr($siteseo_tax_key); ?>'
											)
											.attr('data-tag'));
								});
						});
					</script>

					<?php
		printf(
			'<textarea id="siteseo_titles_tax_desc_' . esc_attr($siteseo_tax_key) . '" name="siteseo_titles_option_name[titles_tax_titles][' . esc_attr($siteseo_tax_key) . '][description]">%s</textarea>',
			esc_html($check2)
		);
		?>
		<?php if ('category' == $siteseo_tax_key) { ?>
		<div class="wrap-tags">
			<button type="button" class="btn btnSecondary tag-title"
				id="siteseo-tag-tax-desc-<?php echo esc_attr($siteseo_tax_key); ?>"
				data-tag="%%_category_description%%">
				<span class="dashicons dashicons-insert"></span>
				<?php esc_html_e('Category Description', 'siteseo'); ?>
			</button>
			<?php } elseif ('post_tag' == $siteseo_tax_key) { ?>
			<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-tax-desc-<?php echo esc_attr($siteseo_tax_key); ?>"
					data-tag="%%tag_description%%">
					<span class="dashicons dashicons-insert"></span>
					<?php esc_html_e('Tag Description', 'siteseo'); ?>
				</button>
				<?php } else { ?>
				<div class="wrap-tags">
					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-tax-desc-<?php echo esc_attr($siteseo_tax_key); ?>"
						data-tag="%%term_description%%">
						<span class="dashicons dashicons-insert"></span>
						<?php esc_html_e('Term Description', 'siteseo'); ?>
					</button>
					<?php } siteseo_render_dyn_variables('tag-description'); ?>
				</div>

		<!--Tax No-Index-->
		<div class="siteseo_wrap_tax">

			<?php $options = get_option('siteseo_titles_option_name');

			$check = isset($options['titles_tax_titles'][$siteseo_tax_key]['noindex']); ?>

			<label
				for="siteseo_titles_tax_noindex[<?php echo esc_attr($siteseo_tax_key); ?>]">
				<input
					id="siteseo_titles_tax_noindex[<?php echo esc_attr($siteseo_tax_key); ?>]"
					name="siteseo_titles_option_name[titles_tax_titles][<?php echo esc_attr($siteseo_tax_key); ?>][noindex]"
					type="checkbox" <?php checked($check, '1') ?>
				value="1"/>
				<?php echo wp_kses_post(__('Do not display this taxonomy archive in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
				<?php if ($siteseo_tax_key ==='post_tag') { ?>
					<div class="siteseo-notice is-warning is-inline">
						<p>
							<?php echo wp_kses_post(__('We do not recommend indexing <strong>tags</strong> which are, in the vast majority of cases, a source of duplicate content.', 'siteseo')); ?>
						</p>
					</div>
				<?php } ?>
			</label>

		<?php $tax_in_sitemap = siteseo_get_service('SitemapOption')->getTaxonomiesList();

			if ('1' == $check && isset($tax_in_sitemap[$siteseo_tax_key]) && '1' === $tax_in_sitemap[$siteseo_tax_key]['include']) { ?>
			<div class="siteseo-notice is-error">
				<p>
					<?php echo wp_kses_post(__('This custom taxonomy is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. We recommend that you check this out.', 'siteseo')); ?>
				</p>
			</div>
		<?php
			}
		?>

		</div>

		<!--Tax No-Follow-->
		<div class="siteseo_wrap_tax">

		<?php
		$options = get_option('siteseo_titles_option_name');

		$check = isset($options['titles_tax_titles'][$siteseo_tax_key]['nofollow']);
		?>

		<label
			for="siteseo_titles_tax_nofollow[<?php echo esc_attr($siteseo_tax_key); ?>]">
			<input
				id="siteseo_titles_tax_nofollow[<?php echo esc_attr($siteseo_tax_key); ?>]"
				name="siteseo_titles_option_name[titles_tax_titles][<?php echo esc_attr($siteseo_tax_key); ?>][nofollow]"
				type="checkbox" <?php checked($check, '1') ?>
			value="1"/>
			<?php echo wp_kses_post(__('Do not follow links for this taxonomy archive <strong>(nofollow)</strong>', 'siteseo')); ?>
		</label>

	</div>
	<?php
	}
}

//Archives
function siteseo_titles_archives_titles_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	
	foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
		if (! in_array($siteseo_cpt_key, ['post', 'page'])) {
			$check = isset($options['titles_archive_titles'][$siteseo_cpt_key]['title']) ? $options['titles_archive_titles'][$siteseo_cpt_key]['title'] : null; ?>
			<h3 id="siteseo-archive-<?php echo esc_attr(str_replace(" ","-",strtolower(trim($siteseo_cpt_value->labels->name))))?>"><?php echo esc_html($siteseo_cpt_value->labels->name); ?>
				<em><small>[<?php echo esc_html($siteseo_cpt_value->name); ?>]</small></em>

				<?php if (get_post_type_archive_link($siteseo_cpt_value->name)) { ?>
				<span class="link-archive">
					<span class="dashicons dashicons-external"></span>
					<a href="<?php echo esc_url(get_post_type_archive_link($siteseo_cpt_value->name)); ?>"
						target="_blank">
						<?php esc_html_e('See archive', 'siteseo'); ?>
					</a>
				</span>
				<?php } ?>
			</h3>

			<!--Archive Title CPT-->
			<div class="siteseo_wrap_archive_cpt">
				<p>
					<?php esc_html_e('Title template', 'siteseo'); ?>
				</p>

				<script>
					jQuery(document).ready(function($) {
						$('#siteseo-tag-archive-title-<?php echo esc_attr($siteseo_cpt_key); ?>')
							.click(
								function() {
									$('#siteseo_titles_archive_titles_<?php echo esc_attr($siteseo_cpt_key); ?>')
										.val(siteseo_get_field_length($(
											'#siteseo_titles_archive_titles_<?php echo esc_attr($siteseo_cpt_key); ?>'
										)) + $(
											'#siteseo-tag-archive-title-<?php echo esc_attr($siteseo_cpt_key); ?>'
										).attr('data-tag'));
								});
						$('#siteseo-tag-archive-sep-<?php echo esc_attr($siteseo_cpt_key); ?>')
							.click(
								function() {
									$('#siteseo_titles_archive_titles_<?php echo esc_attr($siteseo_cpt_key); ?>')
										.val(siteseo_get_field_length($(
											'#siteseo_titles_archive_titles_<?php echo esc_attr($siteseo_cpt_key); ?>'
										)) + $(
											'#siteseo-tag-archive-sep-<?php echo esc_attr($siteseo_cpt_key); ?>'
										).attr('data-tag'));
								});
						$('#siteseo-tag-archive-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>')
							.click(function() {
								$('#siteseo_titles_archive_titles_<?php echo esc_attr($siteseo_cpt_key); ?>')
									.val(siteseo_get_field_length($(
										'#siteseo_titles_archive_titles_<?php echo esc_attr($siteseo_cpt_key); ?>'
									)) + $(
										'#siteseo-tag-archive-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>'
									).attr('data-tag'));
							});
					});
				</script>

			<?php printf(
'<input type="text" id="siteseo_titles_archive_titles_' . esc_attr($siteseo_cpt_key) . '"
			name="siteseo_titles_option_name[titles_archive_titles][' . esc_attr($siteseo_cpt_key) . '][title]"
			value="%s" />',
esc_html($check)
); ?>

			<div class="wrap-tags"><button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-archive-title-<?php echo esc_attr($siteseo_cpt_key); ?>"
					data-tag="%%cpt_plural%%"><span
						class="dashicons dashicons-insert"></span><?php esc_html_e('Post Type Archive Name', 'siteseo'); ?></button>

				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-archive-sep-<?php echo esc_attr($siteseo_cpt_key); ?>"
					data-tag="%%sep%%"><span
						class="dashicons dashicons-insert"></span><?php esc_html_e('Separator', 'siteseo'); ?></button>

				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-archive-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>"
					data-tag="%%sitetitle%%"><span
						class="dashicons dashicons-insert"></span><?php esc_html_e('Site Title', 'siteseo'); ?></button>

				<?php siteseo_render_dyn_variables('tag-title'); ?>

			</div>

			<!--Archive Meta Description CPT-->
			<div class="siteseo_wrap_archive_cpt">

				<p>
					<?php esc_html_e('Meta description template', 'siteseo'); ?>
				</p>

				<?php $check = isset($options['titles_archive_titles'][$siteseo_cpt_key]['description']) ? $options['titles_archive_titles'][$siteseo_cpt_key]['description'] : null; ?>

				<script>
					jQuery(document).ready(function($) {
						$('#siteseo-tag-archive-desc-<?php echo esc_attr($siteseo_cpt_key); ?>')
							.click(
								function() {
									$('#siteseo_titles_archive_desc_<?php echo esc_attr($siteseo_cpt_key); ?>')
										.val(siteseo_get_field_length($(
											'#siteseo_titles_archive_desc_<?php echo esc_attr($siteseo_cpt_key); ?>'
										)) + $(
											'#siteseo-tag-archive-desc-<?php echo esc_attr($siteseo_cpt_key); ?>'
										).attr('data-tag'));
								});
						$('#siteseo-tag-archive-desc-sep-<?php echo esc_attr($siteseo_cpt_key); ?>')
							.click(
								function() {
									$('#siteseo_titles_archive_desc_<?php echo esc_attr($siteseo_cpt_key); ?>')
										.val(siteseo_get_field_length($(
											'#siteseo_titles_archive_desc_<?php echo esc_attr($siteseo_cpt_key); ?>'
										)) + $(
											'#siteseo-tag-archive-desc-sep-<?php echo esc_attr($siteseo_cpt_key); ?>'
										).attr('data-tag'));
								});
						$('#siteseo-tag-archive-desc-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>')
							.click(function() {
								$('#siteseo_titles_archive_desc_<?php echo esc_attr($siteseo_cpt_key); ?>')
									.val(siteseo_get_field_length($(
										'#siteseo_titles_archive_desc_<?php echo esc_attr($siteseo_cpt_key); ?>'
									)) + $(
										'#siteseo-tag-archive-desc-sitetitle-<?php echo esc_attr($siteseo_cpt_key); ?>'
									).attr('data-tag'));
							});
					});
				</script>

		<?php printf(
'<textarea name="siteseo_titles_option_name[titles_archive_titles][' . esc_attr($siteseo_cpt_key) . '][description]">%s</textarea>',
esc_html($check)
); ?>
			<div class="wrap-tags">
				<?php siteseo_render_dyn_variables('tag-description'); ?>
			</div>

				<!--Archive No-Index CPT-->
			<div class="siteseo_wrap_archive_cpt">
				<?php
				$options = get_option('siteseo_titles_option_name');

				$check = isset($options['titles_archive_titles'][$siteseo_cpt_key]['noindex']); ?>

				<label
					for="siteseo_titles_archive_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]">
					<input
						id="siteseo_titles_archive_cpt_noindex[<?php echo esc_attr($siteseo_cpt_key); ?>]"
						name="siteseo_titles_option_name[titles_archive_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][noindex]"
						type="checkbox" <?php checked($check, '1') ?>
					value="1"/>
					<?php echo wp_kses_post(__('Do not display this post type archive in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
				</label>

			</div>

			<!--Archive No-Follow CPT-->
			<div class="siteseo_wrap_archive_cpt">

				<?php
				$options = get_option('siteseo_titles_option_name');

				$check = isset($options['titles_archive_titles'][$siteseo_cpt_key]['nofollow']); ?>

				<label
					for="siteseo_titles_archive_cpt_nofollow[<?php echo esc_attr($siteseo_cpt_key); ?>]">
					<input
						id="siteseo_titles_archive_cpt_nofollow[<?php echo esc_attr($siteseo_cpt_key); ?>]"
						name="siteseo_titles_option_name[titles_archive_titles][<?php echo esc_attr($siteseo_cpt_key); ?>][nofollow]"
						type="checkbox" <?php checked($check, '1') ?>
					value="1"/>
					<?php echo wp_kses_post(__('Do not follow links for this post type archive <strong>(nofollow)</strong>', 'siteseo')); ?>
				</label>

			</div>
			<?php
		}
	}
}

function siteseo_titles_archives_author_title_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>
	<h3 id='siteseo-archive-author-archives'>
		<?php esc_html_e('Author archives', 'siteseo'); ?>
	</h3>

	<p>
		<?php esc_html_e('Title template', 'siteseo'); ?>
	</p>

	<?php $check = isset($options['titles_archives_author_title']) ? $options['titles_archives_author_title'] : null; ?>

	<input id="siteseo_titles_archive_post_author" type="text"
		name="siteseo_titles_option_name[titles_archives_author_title]"
		value="<?php echo esc_html($check); ?>" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-post-author" data-tag="%%post_author%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Post author', 'siteseo'); ?>
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-author" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Separator', 'siteseo'); ?>
		</button>

		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-author" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Site Title', 'siteseo'); ?>
		</button>

	<?php
		siteseo_render_dyn_variables('tag-title');
}

function siteseo_titles_archives_author_desc_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>
	<p>
		<?php esc_html_e('Meta description template', 'siteseo'); ?>
	</p>

	<?php $check = isset($options['titles_archives_author_desc']) ? $options['titles_archives_author_desc'] : null; ?>

	<textarea
		name="siteseo_titles_option_name[titles_archives_author_desc]"><?php echo esc_html($check); ?></textarea>

	<?php
}

function siteseo_titles_archives_author_noindex_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_archives_author_noindex']); ?>

	<label for="siteseo_titles_archives_author_noindex">
		<input id="siteseo_titles_archives_author_noindex"
			name="siteseo_titles_option_name[titles_archives_author_noindex]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php echo wp_kses_post(__('Do not display author archives in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
	</label>

	<?php
}

function siteseo_titles_archives_author_disable_callback(){
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_archives_author_disable']); ?>

	<label for="siteseo_titles_archives_author_disable">
		<input id="siteseo_titles_archives_author_disable"
			name="siteseo_titles_option_name[titles_archives_author_disable]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Disable author archives', 'siteseo'); ?>
	</label>

	<?php
}

function siteseo_titles_archives_date_title_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>
	<h3 id='siteseo-archive-date-archives'>
		<?php esc_html_e('Date archives', 'siteseo'); ?>
	</h3>

	<p>
		<?php esc_html_e('Title template', 'siteseo'); ?>
	</p>

	<?php $check = isset($options['titles_archives_date_title']) ? $options['titles_archives_date_title'] : null; ?>

	<input id="siteseo_titles_archives_date_title" type="text"
		name="siteseo_titles_option_name[titles_archives_date_title]"
		value="<?php echo esc_html($check); ?>" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-archive-date" data-tag="%%archive_date%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Date archives', 'siteseo'); ?>
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-date" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Separator', 'siteseo'); ?>
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-date" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Site Title', 'siteseo'); ?>
		</button>
		<?php
		siteseo_render_dyn_variables('tag-title');
}

function siteseo_titles_archives_date_desc_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>

	<p>
		<?php esc_html_e('Meta description template', 'siteseo'); ?>
	</p>

	<?php $check = isset($options['titles_archives_date_desc']) ? $options['titles_archives_date_desc'] : null; ?>

	<textarea
		name="siteseo_titles_option_name[titles_archives_date_desc]"><?php echo esc_html($check); ?></textarea>

	<?php
}

function siteseo_titles_archives_date_noindex_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_archives_date_noindex']); ?>

	<label for="siteseo_titles_archives_date_noindex">
		<input id="siteseo_titles_archives_date_noindex"
			name="siteseo_titles_option_name[titles_archives_date_noindex]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php echo wp_kses_post(__('Do not display date archives in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
	</label>

	<?php
}

function siteseo_titles_archives_date_disable_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_archives_date_disable']); ?>

	<label for="siteseo_titles_archives_date_disable">
		<input id="siteseo_titles_archives_date_disable"
			name="siteseo_titles_option_name[titles_archives_date_disable]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Disable date archives', 'siteseo'); ?>
	</label>

	<?php
}

function siteseo_titles_archives_search_title_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>
	<h3 id='siteseo-archive-search-archives'>
		<?php esc_html_e('Search archives', 'siteseo'); ?>
	</h3>

	<p>
		<?php esc_html_e('Title template', 'siteseo'); ?>
	</p>

	<?php $check = isset($options['titles_archives_search_title']) ? $options['titles_archives_search_title'] : null; ?>

	<input id="siteseo_titles_archives_search_title" type="text"
		name="siteseo_titles_option_name[titles_archives_search_title]"
		value="<?php echo esc_html($check); ?>" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-search-keywords" data-tag="%%search_keywords%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Search Keywords', 'siteseo'); ?>
		</button>

		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-search" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Separator', 'siteseo'); ?>
		</button>

		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-search" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Site Title', 'siteseo'); ?>
		</button>
		<?php
		siteseo_render_dyn_variables('tag-title');
}

function siteseo_titles_archives_search_desc_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>
	<p>
		<?php esc_html_e('Meta description template', 'siteseo'); ?>
	</p>

	<?php $check = isset($options['titles_archives_search_desc']) ? $options['titles_archives_search_desc'] : null; ?>

	<textarea
		name="siteseo_titles_option_name[titles_archives_search_desc]"><?php echo esc_html($check); ?></textarea>

	<?php
}

function siteseo_titles_archives_search_title_noindex_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_archives_search_title_noindex']); ?>

	<label for="siteseo_titles_archives_search_title_noindex">
		<input
			id="siteseo_titles_archives_search_title_noindex"
			name="siteseo_titles_option_name[titles_archives_search_title_noindex]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php echo wp_kses_post(__('Do not display search archives in search engine results <strong>(noindex)</strong>', 'siteseo')); ?>
	</label>

	<?php
}

function siteseo_titles_archives_404_title_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>
	<h3 id='siteseo-archive-404-archives'>
		<?php esc_html_e('404 archives', 'siteseo'); ?>
	</h3>

	<p>
		<?php esc_html_e('Title template', 'siteseo'); ?>
	</p>

	<?php $check = isset($options['titles_archives_404_title']) ? $options['titles_archives_404_title'] : null; ?>

	<input id="siteseo_titles_archives_404_title" type="text"
		name="siteseo_titles_option_name[titles_archives_404_title]"
		value="<?php echo esc_html($check); ?>" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-404" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Site Title', 'siteseo'); ?>
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-404" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			<?php esc_html_e('Separator', 'siteseo'); ?>
		</button>
		<?php
		siteseo_render_dyn_variables('tag-title');
}

function siteseo_titles_archives_404_desc_callback(){
	
	$options = get_option('siteseo_titles_option_name'); ?>

	<p>
		<label for="siteseo_titles_archives_404_desc">
			<?php esc_html_e('Meta description template', 'siteseo'); ?>
		</label>
	</p>

	<?php $check = isset($options['titles_archives_404_desc']) ? $options['titles_archives_404_desc'] : null; ?>

	<textarea id="siteseo_titles_archives_404_desc"
		name="siteseo_titles_option_name[titles_archives_404_desc]"><?php echo esc_html($check); ?></textarea>

	<?php
}

//Advanced
function siteseo_titles_noindex_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_noindex']); ?>

	<label for="siteseo_titles_noindex">
		<input id="siteseo_titles_noindex"
			name="siteseo_titles_option_name[titles_noindex]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('noindex', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('Do not display all pages of the site in Google search results and do not display "Cached" links in search results.', 'siteseo'); ?>
	</p>

	<p class="description">
		<?php printf(wp_kses_post(__('Check also the <strong>"Search engine visibility"</strong> setting from the <a href="%s">WordPress Reading page</a>.', 'siteseo')), esc_url(admin_url('options-reading.php'))); ?>
	</p>

	<?php 
}

function siteseo_titles_nofollow_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_nofollow']); ?>

	<label for="siteseo_titles_nofollow">
		<input id="siteseo_titles_nofollow"
			name="siteseo_titles_option_name[titles_nofollow]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('nofollow', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('Do not follow links for all pages.', 'siteseo'); ?>
	</p>

	<?php
}

function siteseo_titles_noimageindex_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_noimageindex']); ?>

	<label for="siteseo_titles_noimageindex">
		<input id="siteseo_titles_noimageindex"
			name="siteseo_titles_option_name[titles_noimageindex]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('noimageindex', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('Do not index images from the entire site.', 'siteseo'); ?>
	</p>

	<?php
}

function siteseo_titles_noarchive_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_noarchive']); ?>

	<label for="siteseo_titles_noarchive">
		<input id="siteseo_titles_noarchive"
			name="siteseo_titles_option_name[titles_noarchive]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('noarchive', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('Do not display a "Cached" link in the Google search results.', 'siteseo'); ?>
	</p>

	<?php
}

function siteseo_titles_nosnippet_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_nosnippet']); ?>

	<label for="siteseo_titles_nosnippet">
		<input id="siteseo_titles_nosnippet"
			name="siteseo_titles_option_name[titles_nosnippet]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('nosnippet', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('Do not display a description in the Google search results for all pages.', 'siteseo'); ?>
	</p>

	<?php 
}

function siteseo_titles_nositelinkssearchbox_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_nositelinkssearchbox']); ?>

	<label for="siteseo_titles_nositelinkssearchbox">
		<input id="siteseo_titles_nositelinkssearchbox"
			name="siteseo_titles_option_name[titles_nositelinkssearchbox]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('nositelinkssearchbox', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('Prevents Google to display a sitelinks searchbox in search results. Enable this option will remove the "Website" schema from your source code.', 'siteseo'); ?>
	</p>

	<?php
}

function siteseo_titles_paged_rel_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_paged_rel']); ?>

	<label for="siteseo_titles_paged_rel">
		<input id="siteseo_titles_paged_rel"
			name="siteseo_titles_option_name[titles_paged_rel]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Add rel next/prev link in head of paginated archive pages', 'siteseo'); ?>
	</label>

	<?php
}

function siteseo_titles_paged_noindex_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_paged_noindex']); ?>

	<label for="siteseo_titles_paged_noindex">
		<input id="siteseo_titles_paged_noindex"
			name="siteseo_titles_option_name[titles_paged_noindex]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Add a "noindex" meta robots for all paginated archive pages', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('eg: https://example.com/category/my-category/page/2/', 'siteseo'); ?>
	</p>

	<?php
}

function siteseo_titles_attachments_noindex_callback(){
	
	$options = get_option('siteseo_titles_option_name');

	$check = isset($options['titles_attachments_noindex']); ?>

	<label for="siteseo_titles_attachments_noindex">
		<input id="siteseo_titles_attachments_noindex"
			name="siteseo_titles_option_name[titles_attachments_noindex]"
			type="checkbox" <?php checked($check, '1') ?>
		value="1"/>
		<?php esc_html_e('Add a "noindex" meta robots for all attachment pages', 'siteseo'); ?>
	</label>

	<p class="description">
		<?php esc_html_e('eg: https://example.com/my-media-attachment-page', 'siteseo'); ?>
	</p>

	<?php 
}function siteseo_print_section_info_titles(){
	$docs = siteseo_get_docs_links(); ?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Home', 'siteseo'); ?>
	</h2>
</div>

<div class="siteseo-notice">
	<span class="dashicons dashicons-info"></span>
	<p>
		<?php esc_html_e('Title and meta description are used by search engines to generate the snippet of your site in search results page.', 'siteseo'); ?>
	</p>
</div>

<p>
	<?php esc_html_e('Customize your title & meta description for homepage.', 'siteseo'); ?>
</p>

<span class="dashicons dashicons-external"></span>
<a href="<?php echo esc_attr($docs['titles']['wrong_meta']); ?>"
	target="_blank">
	<?php esc_html_e('Wrong meta title / description in SERP?', 'siteseo'); ?></a>

<script>
	function siteseo_get_field_length(e) {
		if (e.val().length > 0) {
			meta = e.val() + ' ';
		} else {
			meta = e.val();
		}
		return meta;
	}
</script>

<?php
}

function siteseo_print_section_info_single(){
	$anchor_html = '';
	$html = '';
	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	$active_tab = true;
	foreach ($postTypes as $anchor_val) {
		if(empty(!$anchor_val->labels->name)){
			$active_class = $active_tab ? 'class="siteseo-active-sub-tabs"' : '';
			$anchor_html .='<a '.$active_class.' href="#siteseo-post-type-'.esc_attr(str_replace(" ","-",strtolower(trim($anchor_val->labels->name)))).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val->labels->name))).'</a>'; 
			$active_tab = false;
		}
	}

	if(!empty($anchor_html)){
		$html .= '<div class="siteseo-sub-tabs">'. $anchor_html .'</div>';
	}
	
	echo wp_kses_post($html);
?>
<div class="siteseo-section-body">
	<div class="siteseo-section-header">
		<h2>
			<?php esc_html_e('Post Types', 'siteseo'); ?>
		</h2>
	</div>
<p>
	<?php esc_html_e('Customize your titles & metas for Single Custom Post Types.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_print_section_info_advanced()
{
	?>
<div class="siteseo-section-header">
	<h2>
		<?php esc_html_e('Advanced', 'siteseo'); ?>
	</h2>
</div>
<p>
	<?php esc_html_e('Customize your metas for all pages.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_print_section_info_tax(){

	$anchor_html = '';
	$html = '';
	$postTypes = siteseo_get_service('WordPressData')->getTaxonomies();
	$active_tab = true;
	foreach ($postTypes as $anchor_val) {
		if(empty(!$anchor_val->labels->name)){
			$active_class = $active_tab ? 'class="siteseo-active-sub-tabs"' : '';
			$anchor_html .='<a '.$active_class.' href="#siteseo-taxonomies-'.esc_attr(str_replace(" ","-",strtolower(trim($anchor_val->labels->name)))).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val->labels->name))).'</a>';
			$active_tab = false;
		}
	}
	
	if(!empty($anchor_html)){
		$html .= '<div class="siteseo-sub-tabs">'. $anchor_html .'</div>';
	}
	
	echo wp_kses_post($html);
?>
<div class="siteseo-section-body">	
	<div class="siteseo-section-header">
		<h2>
			<?php esc_html_e('Taxonomies', 'siteseo'); ?>
		</h2>
	</div>
<p>
	<?php esc_html_e('Customize your metas for all taxonomies archives.', 'siteseo'); ?>
</p>

<?php
}

function siteseo_print_section_info_archives(){

	$anchor_html = '';
	$html = '';
	$custom_field = array(
		'author-archives' => 'Author archives',
		'date-archives' => 'Date archives',
		'search-archives' => 'Search archives',
		'404-archives' => '404 archives'
	);
	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	$active_tab = true;
	//For Archive post type from wordpress
	foreach ($postTypes as $anchor_key=>$anchor_val) {
		if (! in_array($anchor_key, ['post', 'page'])) {
			if(empty(!$anchor_val->labels->name)){
				$active_class = $active_tab ? 'class="siteseo-active-sub-tabs"' : '';
				$anchor_html .='<a '.$active_class.' href="#siteseo-archive-'.esc_attr(str_replace(" ","-",strtolower(trim($anchor_val->labels->name)))).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val->labels->name))).'</a>';
				$active_tab = false;
			}
		}
	}

	//For Custom Archive post type
	foreach ($custom_field as $anchor_key => $anchor_val) {
		if(empty(!$anchor_val)){
			$anchor_html .='<a href="#siteseo-archive-'.esc_attr($anchor_key).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val))).'</a>'; 
		}
	}

	if(!empty($anchor_html)){
		$html .= '<div class="siteseo-sub-tabs">'. $anchor_html.'</div>';
	}
	
	echo wp_kses_post($html);
?>
<div class="siteseo-section-body">	
	<div class="siteseo-section-header">
		<h2>
			<?php esc_html_e('Archives', 'siteseo'); ?>
		</h2>
	</div>
<p>
	<?php esc_html_e('Customize your metas for all archives.', 'siteseo'); ?>
</p>

<?php
}

//Titles & metas SECTION===================================================================
add_settings_section(
	'siteseo_setting_section_titles_home', // ID
	'',
	//__("Home","siteseo"), // Title
	'siteseo_print_section_info_titles', // Callback
	'siteseo-settings-admin-titles-home' // Page
);

add_settings_field(
	'siteseo_titles_sep', // ID
	__('Separator', 'siteseo'), // Title
	'siteseo_titles_sep_callback', // Callback
	'siteseo-settings-admin-titles-home', // Page
	'siteseo_setting_section_titles_home' // Section
);

add_settings_field(
	'siteseo_titles_home_site_title', // ID
	__('Site title', 'siteseo'), // Title
	'siteseo_titles_home_site_title_callback', // Callback
	'siteseo-settings-admin-titles-home', // Page
	'siteseo_setting_section_titles_home' // Section
);

add_settings_field(
	'siteseo_titles_home_site_title_alt', // ID
	__('Alternative site title', 'siteseo'), // Title
	'siteseo_titles_home_site_title_alt_callback', // Callback
	'siteseo-settings-admin-titles-home', // Page
	'siteseo_setting_section_titles_home' // Section
);

add_settings_field(
	'siteseo_titles_home_site_desc', // ID
	__('Meta description', 'siteseo'), // Title
	'siteseo_titles_home_site_desc_callback', // Callback
	'siteseo-settings-admin-titles-home', // Page
	'siteseo_setting_section_titles_home' // Section
);

//Single Post Types SECTION================================================================
add_settings_section(
	'siteseo_setting_section_titles_single', // ID
	'',
	//__("Post Types","siteseo"), // Title
	'siteseo_print_section_info_single', // Callback
	'siteseo-settings-admin-titles-single', // Page
	array(
		'after_section' => '</div>' // closure of div created inside function 
	)

);

add_settings_field(
	'siteseo_titles_single_titles', // ID
	'',
	'siteseo_titles_single_titles_callback', // Callback
	'siteseo-settings-admin-titles-single', // Page
	'siteseo_setting_section_titles_single' // Section
);

if (is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php')) {
	add_settings_field(
		'siteseo_titles_bp_groups_title', // ID
		'',
		'siteseo_titles_bp_groups_title_callback', // Callback
		'siteseo-settings-admin-titles-single', // Page
		'siteseo_setting_section_titles_single' // Section
	);

	add_settings_field(
		'siteseo_titles_bp_groups_desc', // ID
		'',
		'siteseo_titles_bp_groups_desc_callback', // Callback
		'siteseo-settings-admin-titles-single', // Page
		'siteseo_setting_section_titles_single' // Section
	);

	add_settings_field(
		'siteseo_titles_bp_groups_noindex', // ID
		'',
		'siteseo_titles_bp_groups_noindex_callback', // Callback
		'siteseo-settings-admin-titles-single', // Page
		'siteseo_setting_section_titles_single' // Section
	);
}

//Archives SECTION=========================================================================
add_settings_section(
	'siteseo_setting_section_titles_archives', // ID
	'',
	//__("Archives","siteseo"), // Title
	'siteseo_print_section_info_archives', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	array(
		'after_section' => '</div>' // closure of div created inside function 
	)
);

add_settings_field(
	'siteseo_titles_archives_titles', // ID
	'',
	'siteseo_titles_archives_titles_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_author_title', // ID
	'',
	//__('Title template','siteseo'),
	'siteseo_titles_archives_author_title_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_author_desc', // ID
	'',
	//__('Meta description template','siteseo'),
	'siteseo_titles_archives_author_desc_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_author_noindex', // ID
	'',
	//__("noindex","siteseo"), // Title
	'siteseo_titles_archives_author_noindex_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_author_disable', // ID
	'',
	//__("disable","siteseo"), // Title
	'siteseo_titles_archives_author_disable_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_date_title', // ID
	'',
	//__('Title template','siteseo'),
	'siteseo_titles_archives_date_title_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_date_desc', // ID
	'',
	//__('Meta description template','siteseo'),
	'siteseo_titles_archives_date_desc_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_date_noindex', // ID
	'',
	//__("noindex","siteseo"), // Title
	'siteseo_titles_archives_date_noindex_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_date_disable', // ID
	'',
	//__("disable","siteseo"), // Title
	'siteseo_titles_archives_date_disable_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_search_title', // ID
	'',
	//__('Title template','siteseo'),
	'siteseo_titles_archives_search_title_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_search_desc', // ID
	'',
	//__('Meta description template','siteseo'),
	'siteseo_titles_archives_search_desc_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_search_title_noindex', // ID
	'',
	//__('noindex','siteseo'),
	'siteseo_titles_archives_search_title_noindex_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_404_title', // ID
	'',
	//__('Title template','siteseo'),
	'siteseo_titles_archives_404_title_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

add_settings_field(
	'siteseo_titles_archives_404_desc', // ID
	'',
	//__('Meta description template','siteseo'),
	'siteseo_titles_archives_404_desc_callback', // Callback
	'siteseo-settings-admin-titles-archives', // Page
	'siteseo_setting_section_titles_archives' // Section
);

//Taxonomies SECTION=======================================================================
add_settings_section(
	'siteseo_setting_section_titles_tax', // ID
	'',
	//__("Taxonomies","siteseo"), // Title
	'siteseo_print_section_info_tax', // Callback
	'siteseo-settings-admin-titles-tax', // Page
	array(
		'after_section' => '</div>' // closure of div created inside function 
	)
);

add_settings_field(
	'siteseo_titles_tax_titles', // ID
	'',
	'siteseo_titles_tax_titles_callback', // Callback
	'siteseo-settings-admin-titles-tax', // Page
	'siteseo_setting_section_titles_tax' // Section
);

//Advanced SECTION=========================================================================
add_settings_section(
	'siteseo_setting_section_titles_advanced', // ID
	'',
	//__("Advanced","siteseo"), // Title
	'siteseo_print_section_info_advanced', // Callback
	'siteseo-settings-admin-titles-advanced' // Page
);

add_settings_field(
	'siteseo_titles_noindex', // ID
	__('noindex', 'siteseo'), // Title
	'siteseo_titles_noindex_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

add_settings_field(
	'siteseo_titles_nofollow', // ID
	__('nofollow', 'siteseo'), // Title
	'siteseo_titles_nofollow_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

add_settings_field(
	'siteseo_titles_noimageindex', // ID
	__('noimageindex', 'siteseo'), // Title
	'siteseo_titles_noimageindex_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

add_settings_field(
	'siteseo_titles_noarchive', // ID
	__('noarchive', 'siteseo'), // Title
	'siteseo_titles_noarchive_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

add_settings_field(
	'siteseo_titles_nosnippet', // ID
	__('nosnippet', 'siteseo'), // Title
	'siteseo_titles_nosnippet_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

add_settings_field(
	'siteseo_titles_nositelinkssearchbox', // ID
	__('nositelinkssearchbox', 'siteseo'), // Title
	'siteseo_titles_nositelinkssearchbox_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

add_settings_field(
	'siteseo_titles_paged_rel', // ID
	__('Indicate paginated content to Google', 'siteseo'), // Title
	'siteseo_titles_paged_rel_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

add_settings_field(
	'siteseo_titles_paged_noindex', // ID
	__('noindex on paged archives', 'siteseo'), // Title
	'siteseo_titles_paged_noindex_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);
add_settings_field(
	'siteseo_titles_attachments_noindex', // ID
	__('noindex on attachment pages', 'siteseo'), // Title
	'siteseo_titles_attachments_noindex_callback', // Callback
	'siteseo-settings-admin-titles-advanced', // Page
	'siteseo_setting_section_titles_advanced' // Section
);

$this->options = get_option('siteseo_titles_option_name');

if(function_exists('siteseo_admin_header')) {
	siteseo_admin_header();
} ?>

<form method="post"
	action="<?php echo esc_url(admin_url('options.php')); ?>"
	class="siteseo-option">
	<?php
	settings_fields('siteseo_titles_option_group'); ?>

	<div id="siteseo-tabs" class="wrap">
		<?php
			echo wp_kses($this->siteseo_feature_title('titles'), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);
			$current_tab = '';
			
			$plugin_settings_tabs	= [
				'tab_siteseo_titles_home'	 => __('Home', 'siteseo'),
				'tab_siteseo_titles_single'   => __('Post Types', 'siteseo'),
				'tab_siteseo_titles_archives' => __('Archives', 'siteseo'),
				'tab_siteseo_titles_tax'	  => __('Taxonomies', 'siteseo'),
				'tab_siteseo_titles_advanced' => __('Advanced', 'siteseo'),
			];

echo '<div class="nav-tab-wrapper">';
foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
	echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-titles#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
}
echo '</div>'; ?>
		<div class="siteseo-tab <?php if ('tab_siteseo_titles_home' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_titles_home"><?php do_settings_sections('siteseo-settings-admin-titles-home'); ?>
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_titles_single' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_titles_single"><?php do_settings_sections('siteseo-settings-admin-titles-single'); ?>
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_titles_archives' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_titles_archives"><?php do_settings_sections('siteseo-settings-admin-titles-archives'); ?>
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_titles_tax' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_titles_tax"><?php do_settings_sections('siteseo-settings-admin-titles-tax'); ?>
		</div>
		<div class="siteseo-tab <?php if ('tab_siteseo_titles_advanced' == $current_tab) {
	echo 'active';
} ?>" id="tab_siteseo_titles_advanced"><?php do_settings_sections('siteseo-settings-admin-titles-advanced'); ?>
		</div>
	</div>

	<?php siteseo_submit_button(__('Save changes', 'siteseo')); ?>
</form>
<?php

