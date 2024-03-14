<?php

// SECURITY : Exit if accessed directly
if ( !defined('ABSPATH') ) {
	exit;
}


// Get the CPTs (Custom Post Type)
$args = array(
	'public'   => true,
	'_builtin' => false
);
$post_types = get_post_types( $args, 'names' ); 

// Get the Taxonomies
$args = array(
	'public'   => true,
	'_builtin' => false
	);
$taxonomies_names = get_taxonomies( $args );


// inital way to display the posts
$wsp_initial_posts_by_category = '<a href="{permalink}">{title}</a>';
?>
<div class="wrap">
	<form method="post" action="options.php">
		<?php settings_fields('wp-sitemap-page');?>
		
		
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php esc_html_e('WP Sitemap Page', 'wp-sitemap-page'); ?></h2>
		
		
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<h4><?php esc_html_e('Help us', 'wp-sitemap-page') ?></h4>
						<p class="message"><?php esc_html_e('This plugin is freely developped and distrubed to the WordPress community. Plenty of hours were necessary to develop this project.', 'wp-sitemap-page') ?></p>
						<p><?php esc_html_e('If you like this plugin, feel free to rate it 5 stars on the WordPress.org website or to donate.', 'wp-sitemap-page'); ?></p>
						<p><a href="<?php echo WSP_DONATE_LINK; ?>" class="button button-primary" target="_blank"><?php esc_html_e('Donate', 'wp-sitemap-page'); ?></a></p>
					</div>

					<div class="welcome-panel-column">
						<h4><?php esc_html_e('Traditionnal sitemap', 'wp-sitemap-page') ?></h4>
						<p><?php esc_html_e('To display a traditional sitemap, just use [wp_sitemap_page] on any page or post.', 'wp-sitemap-page'); ?></p>
					</div>

					<div class="welcome-panel-column">
						<h4><?php esc_html_e('Display only some content', 'wp-sitemap-page') ?></h4>
						<p><?php esc_html_e('Display only some kind of content using the following shortcodes.', 'wp-sitemap-page'); ?></p>
						<ul>
							<li>[wp_sitemap_page only="post"]</li>
							<li>[wp_sitemap_page only="page"]</li>
							<li>[wp_sitemap_page only="category"]</li>
							<li>[wp_sitemap_page only="tag"]</li>
							<li>[wp_sitemap_page only="archive"]</li>
							<li>[wp_sitemap_page only="author"]</li>
							
							<?php
							// list all the CPT
							foreach ( $post_types as $post_type ) :
								
								// extract CPT object
								$cpt = get_post_type_object( $post_type );
								?>
								<li>[wp_sitemap_page only="<?php echo esc_html($cpt->name); ?>"]</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		
		<?php wsp_show_tabs(); ?>
		
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<!-- main content -->
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						
<?php
$current_tab = wsp_get_current_tab();
switch ($current_tab) {
	// MAIN
	case 'main':
		?>
		
		
		<div class="postbox">
			<h3 class="hndle"><span><?php esc_html_e('General settings', 'wp-sitemap-page'); ?></span></h3>
			<div class="inside">
			<?php
			$wsp_add_nofollow = wsp_esc_to_keep_0_or_1(get_option('wsp_add_nofollow'));
			?>
			<label for="wsp_add_nofollow">
				<input type="checkbox" 
					name="wsp_add_nofollow" id="wsp_add_nofollow" 
					value="1" <?php echo ($wsp_add_nofollow==1 ? ' checked="checked"' : ''); ?> />
					<?php esc_html_e('Add a nofollow attribute to the links.', 'wp-sitemap-page'); ?>
			</label>
			<p class="description"><?php esc_html_e('Please be advice to avoid this feature as it may hurt your SEO (Search Engine Optimization), if you don\'t know what it is.'); ?></p>
			</div><!-- .inside -->
		</div><!-- .postbox -->
		
		
		<div class="postbox">
			<h3 class="hndle"><span><?php esc_html_e('Customize the way to display the posts', 'wp-sitemap-page'); ?></span></h3>
			<div class="inside">

			<p><?php esc_html_e('Please choose how you want to display the posts on the sitemap.', 'wp-sitemap-page');?></p>
			<ul>
				<li><?php printf( esc_html__('%1$s: title of the post.', 'wp-sitemap-page'), '{title}' );?></li>
				<li><?php printf( esc_html__('%1$s: URL of the post.', 'wp-sitemap-page'), '{permalink}' );?></li>
				<li><?php printf( esc_html__('%1$s: The year of the post, four digits, for example 2004.', 'wp-sitemap-page'), '{year}' );?></li>
				<li><?php printf( esc_html__('%1$s: Month of the year, for example 05.', 'wp-sitemap-page'), '{monthnum}' );?></li>
				<li><?php printf( esc_html__('%1$s: Day of the month, for example 28.', 'wp-sitemap-page'), '{day}' );?></li>
				<li><?php printf( esc_html__('%1$s: Hour of the day, for example 15.', 'wp-sitemap-page'), '{hour}' );?></li>
				<li><?php printf( esc_html__('%1$s: Minute of the hour, for example 43.', 'wp-sitemap-page'), '{minute}' );?></li>
				<li><?php printf( esc_html__('%1$s: Second of the minute, for example 33.', 'wp-sitemap-page'), '{second}' );?></li>
				<li><?php printf( esc_html__('%1$s: The unique ID # of the post, for example 423.', 'wp-sitemap-page'), '{post_id}' );?></li>
				<li><?php printf( esc_html__('%1$s: Category name. Nested sub-categories appear as nested directories in the URI.', 'wp-sitemap-page'), '{category}' );?></li>
			</ul>
			
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="wsp_posts_by_category">
						<?php esc_html_e('How to display the posts', 'wp-sitemap-page');?>
						</label>
					</th>
					<td>
						<?php
						// determine the code to place in the textarea
						$wsp_posts_by_category = get_option('wsp_posts_by_category');
						if ( $wsp_posts_by_category === false ) {
							// this option does not exists
							$textarea = $wsp_initial_posts_by_category;
							
							// save this option
							add_option( 'wsp_posts_by_category', $textarea );
						} else {
							// this option exists, display it in the textarea
							$textarea = $wsp_posts_by_category;
						}
						?>
						<textarea name="wsp_posts_by_category" id="wsp_posts_by_category" 
							rows="2" cols="50"
							class="large-text code"><?php
							echo esc_html(esc_textarea(wsp_esc_some_html_tags($textarea)));
							?></textarea>
						<p class="description"><?php printf(esc_html__('Initial way to display the content: %1$s', 'wp-sitemap-page'), htmlentities($wsp_initial_posts_by_category)); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e('Displayed multiple times', 'wp-sitemap-page'); ?>
					</th>
					<td>
						<?php
						$wsp_is_display_post_multiple_time = wsp_esc_to_keep_0_or_1(get_option('wsp_is_display_post_multiple_time'));
						?>
						<label for="wsp_is_display_post_multiple_time">
							<input type="checkbox" 
								name="wsp_is_display_post_multiple_time" id="wsp_is_display_post_multiple_time" 
								value="1" <?php echo ($wsp_is_display_post_multiple_time==1 ? ' checked="checked"' : ''); ?> />
								<?php esc_html_e('Displayed in each category if posts are in multiples categories.', 'wp-sitemap-page'); ?>
						</label>
					</td>
				</tr>
				</tbody>
			</table>

			</div><!-- .inside -->
		</div><!-- .postbox -->
		
		
		<div class="postbox">
			<h3 class="hndle"><span><?php esc_html_e('Exclude from traditional sitemap', 'wp-sitemap-page'); ?></span></h3>
			<div class="inside">
			
			<table class="form-table">
				<tbody>
				<tr>
					<th scope="row">
						<label for="wsp_exclude_pages">
						<?php esc_html_e('Exclude pages', 'wp-sitemap-page'); ?>
						</label>
					</th>
					<td>
						<?php
						// Exclude some pages
						$wsp_exclude_pages = get_option('wsp_exclude_pages');
						?>
						<input type="text" class="large-text code" 
							name="wsp_exclude_pages" id="wsp_exclude_pages" 
							value="<?php echo wsp_esc_to_keep_numeric_or_coma(esc_html($wsp_exclude_pages)); ?>" />
						<p class="description"><?php esc_html_e('Just add the IDs, separated by a comma, of the pages you want to exclude.', 'wp-sitemap-page'); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e('Exclude Custom Post Type', 'wp-sitemap-page'); ?>
					</th>
					<td>
						<?php
						// Is this CPT already excluded ?
						$wsp_exclude_cpt_page    = wsp_esc_to_keep_0_or_1(get_option('wsp_exclude_cpt_page'));
						$wsp_exclude_cpt_post    = wsp_esc_to_keep_0_or_1(get_option('wsp_exclude_cpt_post'));
						$wsp_exclude_cpt_archive = wsp_esc_to_keep_0_or_1(get_option('wsp_exclude_cpt_archive'));
						$wsp_exclude_cpt_author  = wsp_esc_to_keep_0_or_1(get_option('wsp_exclude_cpt_author'));
						?>
						<div>
							<label for="wsp_exclude_cpt_page">
								<input type="checkbox" 
									name="wsp_exclude_cpt_page" id="wsp_exclude_cpt_page" 
									value="1" <?php echo ($wsp_exclude_cpt_page==1 ? ' checked="checked"' : ''); ?> />
									<?php esc_html_e('Page', 'wp-sitemap-page'); ?>
							</label>
						</div>
						<div>
							<label for="wsp_exclude_cpt_post">
								<input type="checkbox" 
									name="wsp_exclude_cpt_post" id="wsp_exclude_cpt_post" 
									value="1" <?php echo ($wsp_exclude_cpt_post==1 ? ' checked="checked"' : ''); ?> />
									<?php esc_html_e('Post', 'wp-sitemap-page'); ?>
							</label>
						</div>
						<div>
							<label for="wsp_exclude_cpt_archive">
								<input type="checkbox" 
									name="wsp_exclude_cpt_archive" id="wsp_exclude_cpt_archive" 
									value="1" <?php echo ($wsp_exclude_cpt_archive==1 ? ' checked="checked"' : ''); ?> />
									<?php esc_html_e('Archive', 'wp-sitemap-page'); ?>
							</label>
						</div>
						<div>
							<label for="wsp_exclude_cpt_author">
								<input type="checkbox" 
									name="wsp_exclude_cpt_author" id="wsp_exclude_cpt_author" 
									value="1" <?php echo ($wsp_exclude_cpt_author==1 ? ' checked="checked"' : ''); ?> />
									<?php esc_html_e('Author', 'wp-sitemap-page'); ?>
							</label>
						</div>
						<?php
						// list all the CPT
						foreach ( $post_types as $post_type ) {
							
							// extract CPT object
							$cpt = get_post_type_object( $post_type );
							
							// Is this CPT already excluded ?
							$wsp_exclude_cpt = wsp_esc_to_keep_0_or_1(get_option('wsp_exclude_cpt_'.$cpt->name));
							?>
							<div>
								<label for="wsp_exclude_cpt_<?php echo esc_html($cpt->name); ?>">
									<input type="checkbox" 
										name="wsp_exclude_cpt_<?php echo esc_attr($cpt->name); ?>" id="wsp_exclude_cpt_<?php echo esc_attr($cpt->name); ?>" 
										value="1" <?php echo ($wsp_exclude_cpt=='1' ? ' checked="checked"' : ''); ?> />
										<?php echo esc_html($cpt->label); ?>
								</label>
							</div>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e('Exclude taxonomies', 'wp-sitemap-page'); ?>
					</th>
					<td>
						<?php
						// list all the taxonomies
						foreach ( $taxonomies_names as $taxonomy_name ) {
							
							// Extract
							$taxonomy_obj = get_taxonomy( $taxonomy_name );
							
							// get some data
							$taxonomy_name = $taxonomy_obj->name;
							$taxonomy_label = $taxonomy_obj->label;
							
							// Is this CPT already excluded ?
							$wsp_exclude_taxonomy = wsp_esc_to_keep_0_or_1(get_option('wsp_exclude_taxonomy_'.$taxonomy_name));
							?>
							<div>
								<label for="wsp_exclude_taxonomy_<?php echo esc_attr($taxonomy_name); ?>">
									<input type="checkbox" 
										name="wsp_exclude_taxonomy_<?php echo esc_attr($taxonomy_name); ?>" id="wsp_exclude_taxonomy_<?php echo esc_attr($taxonomy_name); ?>" 
										value="1" <?php echo ($wsp_exclude_taxonomy=='1' ? ' checked="checked"' : ''); ?> />
										<?php echo esc_html($taxonomy_label); ?>
								</label>
							</div>
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php esc_html_e('Password protected', 'wp-sitemap-page'); ?>
					</th>
					<td>
						<?php
						// Is the pages/posts/CPTs with password should be exclude from the sitemap
						$wsp_is_exclude_password_protected = wsp_esc_to_keep_0_or_1(get_option('wsp_is_exclude_password_protected'));
						?>
						<div>
							<label for="wsp_is_exclude_password_protected">
								<input type="checkbox" 
									name="wsp_is_exclude_password_protected" id="wsp_is_exclude_password_protected" 
									value="1" <?php echo ($wsp_is_exclude_password_protected==1 ? ' checked="checked"' : ''); ?> />
									<?php esc_html_e('Exclude content protected by password', 'wp-sitemap-page'); ?>
							</label>
						</div>
					</td>
				</tr>
				</tbody>
			</table>

			</div><!-- .inside -->
		</div><!-- .postbox -->
		
		
		<div class="postbox">
			<h3 class="hndle"><span><?php esc_html_e('Display the plugin link', 'wp-sitemap-page'); ?></span></h3>
			<div class="inside">
			<?php
			$wsp_is_display_copyright = wsp_esc_to_keep_0_or_1(get_option('wsp_is_display_copyright'));
			?>
			<label for="wsp_is_display_copyright">
				<input type="checkbox" 
					name="wsp_is_display_copyright" id="wsp_is_display_copyright" 
					value="1" <?php echo ($wsp_is_display_copyright==1 ? ' checked="checked"' : ''); ?> />
					<?php esc_html_e('Display the plugin name with a link at the bottom of the sitemap.', 'wp-sitemap-page'); ?>
			</label>

			</div><!-- .inside -->
		</div><!-- .postbox -->
		
		
		<?php
	break;
	
	// about
	case 'about':
		?>
		
		<div class="postbox">
			<h3 class="hndle"><span><?php esc_html_e('How to use', 'wp-sitemap-page'); ?></span></h3>
			<div class="inside">
			
			<p><?php esc_html_e('You can use any of the following shortcodes in the content of your pages (or posts) to display a dynamic sitemap.', 'wp-sitemap-page'); ?></p>
			
			<ul>
				<li><strong>[wp_sitemap_page]</strong> <?php esc_html_e('To display a traditionnal sitemap', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="page"]</strong> <?php esc_html_e('To display the pages', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="page" sort="menu_order"]</strong> <?php printf(esc_html__('To display the pages sorted by menu order. Possible values are: %1$s.', 'wp-sitemap-page'), '\'post_title\', \'menu_order\', \'post_date\', \'post_modified\', \'ID\', \'post_author\', \'post_name\''); ?></li>
				<li><strong>[wp_sitemap_page only="post"]</strong> <?php esc_html_e('To display the posts by category', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="post" sort="count"]</strong> <?php printf(esc_html__('To display the posts by category. Categories sorted by number of posts. Possible values are: %1$s', 'wp-sitemap-page'), '\'id\', \'name\', \'slug\', \'count\', \'term_group\''); ?></li>
				<li><strong>[wp_sitemap_page only="post" sort="post_name" order="ASC"]</strong> <?php printf(esc_html__('To display the posts by category. Posts sorted by name with ascendent order. Possible values for "order" attribute are: %1$s', 'wp-sitemap-page'), '\'ASC\', \'DESC\''); ?></li>
				<li><strong>[wp_sitemap_page display_category_title_wording="false"]</strong> <?php esc_html_e('To hide the word "category" on the title of each category', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="category"]</strong> <?php esc_html_e('To display the categories', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="category" sort="count"]</strong> <?php printf(esc_html__('To display the categories sorted by number of posts. Possible values are: %1$s', 'wp-sitemap-page'), '\'id\', \'name\', \'slug\', \'count\', \'term_group\''); ?></li>
				<li><strong>[wp_sitemap_page only="tag"]</strong> <?php esc_html_e('To display the tags', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="archive"]</strong> <?php esc_html_e('To display the archives', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="author"]</strong> <?php esc_html_e('To display the authors', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only="author" sort="post_count"]</strong> <?php printf(esc_html__('To display the authors, sorted by number of posts by author. Possible values are: %1$s.', 'wp-sitemap-page'), '\'name\', \'email\', \'url\', \'registered\', \'id\', \'user_login\', \'post_count\''); ?></li>
				<?php
				// list all the CPT
				foreach ( $post_types as $post_type ) :
					
					// extract CPT object
					$cpt = get_post_type_object( $post_type );
					?>
					<li><strong>[wp_sitemap_page only="<?php echo esc_html($cpt->name); ?>"]</strong> <?php printf(esc_html__('To display the %1$s', 'wp-sitemap-page'), strtolower(esc_html($cpt->label))); ?></li>
				<?php endforeach; ?>
				<?php
				// list all the taxonomies
				foreach ( $taxonomies_names as $taxonomy_name ) :
					
					// Extract
					$taxonomy_obj = get_taxonomy( $taxonomy_name );
					
					// get some data
					$taxonomy_name = $taxonomy_obj->name;
					$taxonomy_label = $taxonomy_obj->label;
					?>
					<li><strong>[wp_sitemap_page only="<?php echo esc_html($taxonomy_name); ?>"]</strong> <?php printf(esc_html__('To display the %1$s', 'wp-sitemap-page'), strtolower(esc_html($taxonomy_label))); ?></li>
				<?php endforeach; ?>
				<li><strong>[wp_sitemap_page display_title="false"]</strong> <?php esc_html_e('To display a traditionnal sitemap without the title', 'wp-sitemap-page'); ?></li>
				<li><strong>[wp_sitemap_page only_private="true"]</strong> <?php esc_html_e('Display only the private page (do not works with other kind of content)', 'wp-sitemap-page'); ?></li>
			</ul>
			
			</div><!-- .inside -->
		</div><!-- .postbox -->
		
		<?php
		break;
	
	// DEFAULT
	default:
		// nothing but do
		break;
}
?>


					</div><!-- .meta-box-sortables .ui-sortable -->
				</div><!-- post-body-content -->
				<!-- sidebar -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox">
						<h3 class="hndle"><span><?php esc_html_e('About', 'wp-sitemap-page'); ?></span></h3>
						<div style="padding:0 5px;">
							<?php
							$fr_lang = array('fr_FR', 'fr_BE', 'fr_CH', 'fr_LU', 'fr_CA');
							// WP Constant WPLANG does not exists anymore
							// https://core.trac.wordpress.org/changeset/29630
							$WPLANG = get_option('WPLANG', '');
							// check if language is in French
							$is_fr = (in_array($WPLANG, $fr_lang) ? true : false);
							// Get the URL author depending on the language
							$url_author = ( $is_fr===true ? 'http://tonyarchambeau.com/' : 'http://en.tonyarchambeau.com/' );
							?>
							<p><img src="<?php echo WSP_USER_PLUGIN_URL; ?>/images/icon-html-code-24.png" alt="" style="vertical-align:middle;" /> <?php echo wsp_esc_some_html_tags(sprintf(__('Developed by <a href="%1$s">Tony Archambeau</a>.', 'wp-sitemap-page'), $url_author)); ?></p>
							<p><img src="<?php echo WSP_USER_PLUGIN_URL; ?>/images/icon-star-24.png" alt="" style="vertical-align:middle;" /> <a href="https://wordpress.org/support/view/plugin-reviews/wp-sitemap-page?filter=5" target="_blank"><?php esc_html_e('Rate the plugin on Wordpress.org'); ?></a></p>
							<p><img src="<?php echo WSP_USER_PLUGIN_URL; ?>/images/icon-coin-24.png" alt="" style="vertical-align:middle;" /> <a href="<?php echo WSP_DONATE_LINK; ?>" target="_blank"><?php esc_html_e('Donate', 'wp-sitemap-page'); ?></a></p>
							<?php
							// Display the author for Russian audience
							if ($WPLANG == 'ru_RU') {
								?>
								<p><img src="<?php echo WSP_USER_PLUGIN_URL; ?>/images/icon-html-code-24.png" alt="" style="vertical-align:middle;" /> <?php echo wsp_esc_some_html_tags(sprintf(__('Translated in Russian by <a href="%1$s">skesov.ru</a>.', 'wp-sitemap-page'), 'http://skesov.ru/')); ?></p>
								<?php
							}
							?>
						</div>
						</div><!-- .postbox -->
					</div><!-- .meta-box-sortables -->
				</div><!-- #postbox-container-1 .postbox-container -->
			</div><!-- #post-body .metabox-holder .columns-2 -->
			<br class="clear" />
			
			<?php if ($current_tab=='main') : ?>
			<div>
				<?php submit_button();?>
			</div>
			<?php endif; ?>
			
		</div><!-- #poststuff -->
		

		
	</form>
</div><!-- .wrap -->
