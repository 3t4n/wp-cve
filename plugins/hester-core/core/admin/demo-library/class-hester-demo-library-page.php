<?php

/**
 * Hester Demo Library. Install a copy of a Hester demo to your website.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Hester Demo Library Class.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Demo_Library_Page
{

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Main Hester Demo Library Page Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Demo_Library_Page
	 */
	public static function instance()
	{

		if (!isset(self::$instance) && !(self::$instance instanceof Hester_Demo_Library_Page)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{

		$theme_name =  hester_core()->theme_name;

		add_action('admin_menu', array($this, 'add_admin_menu'), 100);
		add_action('admin_print_footer_scripts-hester_page_'.$theme_name.'-demo-library', array($this, 'print_templates'));
		add_filter( $theme_name . '_admin_page_tabs', array($this, 'add_admin_page_tabs'));
		add_filter( $theme_name . '_dashboard_navigation_items', array($this, 'update_navigation_items'));

		do_action('hester_demo_library_page_loaded');
	}

	/**
	 * Add to menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu()
	{

		$theme_name =  hester_core()->theme_name;
		
		// Demo Library page.
		add_submenu_page(
			$theme_name . '-dashboard',
			esc_html__('Demo Library', 'hester-core'),
			'Demo Library',
			apply_filters('hester_manage_cap', 'edit_theme_options'),
			$theme_name . '-demo-library',
			array($this, 'render_demo_library')
		);
	}

	/**
	 * Render Demo Library content.
	 *
	 * @since 1.0.0
	 */
	public function render_demo_library()
	{
		$theme_name =  hester_core()->theme_name;
		$hester_dashboard =  $theme_name . '_dashboard';


		$hester_dashboard()->render_navigation();
		
?>
		<div class="hester-container">

			<div class="hester-section-title">
				<h2 class="hester-section-title"><?php esc_html_e('Demo Library', 'hester-core'); ?></h2>

				<div class="demo-search">
					<input type="search" placeholder="<?php esc_html_e('Filter&hellip;', 'hester-core'); ?>" id="hester-search-demos" />
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8" />
						<path d="M21 21l-4.35-4.35" />
					</svg>
				</div>

				<a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page='.$theme_name.'-demo-library'), 'refresh_templates', 'hester_core_nonce')); ?>" class="hester-btn secondary"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M23 4v6h-6M1 20v-6h6" />
						<path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
					</svg><?php esc_html_e('Refresh', 'hester-core'); ?></a>
			</div><!-- END .hester-section-title -->

			<div class="demo-filters">
				<?php $templates = get_transient('hester_core_demo_templates'); ?>
				<ul class="demo-categories">
					<li class="selected" data-category=""><a href="#"><?php esc_html_e('All', 'hester-core'); ?></a></li>
					<?php if (is_array($templates) && !empty($templates)) {
						$cats = array_values(array_column($templates, 'categories'));

						foreach ($cats as $id => $cat) {
							$arr[] =  array_keys($cat);
						}

						$templateCatArray = array();
						for($i = 0; $i < count($arr); $i++) {

						    for ( $j=0; $j < count($arr[$i]); $j++ ) {
								$templateCatArray[] = $arr[$i][$j];
						    }
						}
						$cats = array_values(array_unique($templateCatArray));
						for($i = 0; $i < count($cats); $i++) { ?>
							<li><a href="#" data-category="<?php echo esc_attr($cats[$i]) ?>"><?php echo ucfirst($cats[$i]) ?></a></li>
						<?php }
					} else { ?>
						<li><a href="#" data-category="blog"><?php esc_html_e('Blog', 'hester-core'); ?></a></li>
						<li><a href="#" data-category="shop"><?php esc_html_e('Shop', 'hester-core'); ?></a></li>
						<li><a href="#" data-category="agency"><?php esc_html_e('Agency', 'hester-core'); ?></a></li>
						<li><a href="#" data-category="business"><?php esc_html_e('Business', 'hester-core'); ?></a></li>
						<li><a href="#" data-category="food"><?php esc_html_e('Food', 'hester-core'); ?></a></li>
					<?php } ?>
				</ul>

				<ul class="demo-builders">
					<li><a href="#" data-builder="block-editor"><?php esc_html_e('Gutenberg', 'hester-core'); ?></a></li>
					<li><a href="#" data-builder="elementor"><?php esc_html_e('Elementor', 'hester-core'); ?></a></li>
				</ul>
			</div>

			<div class="hester-section hester-columns demos">
			</div><!-- END .demos -->

			<p class="demo-notice">
				<?php esc_html_e('New demos coming soon', 'hester-core'); ?>
			</p>

		</div>
	<?php
	}

	/**
	 * Print the JavaScript templates used to render demo library page.
	 *
	 * Templates are imported into the JS use wp.template.
	 *
	 * @since 1.0.0
	 */
	public function print_templates()
	{
	?>
		<script type="text/template" id="tmpl-hester-core-template">
		</script>

		<script type="text/template" id="tmpl-hester-core-demo-item">
			<div class="hester-column">
				<div class="hester-demo"
					data-demo-id="{{data.slug}}"
					data-demo-pro="{{data.pro}}"
					data-demo-url="{{{data.url}}}"
					data-demo-description="{{{data.description}}}"
					data-demo-screenshot="{{{data.screenshot}}}"
					data-demo-name="{{{data.name}}}"
					data-demo-slug="{{{data.slug}}}"
					data-required-plugins="{{JSON.stringify( data.required_plugins )}}"
					>

					<div class="demo-screenshot">
						<img src="{{{data.screenshot}}}" />
						<span class="text-overlay">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
							<span>Preview Demo</span>
						</span>

						<# if ( ! _.isEmpty( data.categories ) ) { #>
							<div class="demo-cat-list">
								<# _.each( data.categories, function( category ) { #>
									<span>{{{category}}}</span>
								<# } ); #>
							</div>
						<# } #>

						<# if ( data.pro ) { #>
							<span class="site-type {{{data.pro}}}">Pro</span>
						<# } #>
					</div>

					<div class="demo-meta">
						<div class="demo-name">
							<span class="name">{{{data.name}}}</span>
						</div>
						<div class="demo-actions">
							<# if(data.pro && !data.is_pro) { #>
								<a class="hester-btn primary btn-small" href="{{ data.upgrade_to_pro }}" target="_blank" aria-label="<?php esc_attr_e('Upgrade to pro', 'hester-core'); ?> {{data.name}}"><?php esc_html_e('Upgrade to pro', 'hester-core'); ?></a>
							<# } else { #>
							<a class="hester-btn primary btn-small import" href="#" aria-label="<?php esc_attr_e('Import', 'hester-core'); ?> {{data.name}}"><?php esc_html_e('Import Demo', 'hester-core'); ?></a>
							<# } #>
							<a class="hester-btn secondary btn-small preview" href="#" aria-label="<?php esc_attr_e('Preview', 'hester-core'); ?> {{data.name}}"><?php esc_html_e('Preview', 'hester-core'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</script>

		<script type="text/template" id="tmpl-hester-core-demo-preview">
			<#  #>
			<div class="hester-demo-preview theme-install-overlay wp-full-overlay expanded">

				<div class="wp-full-overlay-sidebar">

					<div class="wp-full-overlay-header"
							data-
							data-demo-id="{{{data.id}}}"
							data-demo-pro="{{{data.pro}}}"
							data-demo-url="{{{data.url}}}"
							data-demo-name="{{{data.name}}}"
							data-demo-description="{{{data.description}}}"
							data-demo-slug="{{{data.slug}}}"
							data-demo-screenshot="{{{data.screenshot}}}"
							data-content="{{{data.content}}}"
							data-required-plugins="{{data.required_plugins}}">

						<button class="close-full-overlay"><span class="screen-reader-text"><?php esc_html_e('Close', 'hester-core'); ?></span></button>
						<button class="previous-theme"><span class="screen-reader-text"><?php esc_html_e('Previous', 'hester-core'); ?></span></button>
						<button class="next-theme"><span class="screen-reader-text"><?php esc_html_e('Next', 'hester-core'); ?></span></button>
						<span class="spinner"></span>

						<# if(data.pro && !data.is_pro) { #>
							<a class="hester-btn primary btn-small" href="{{ data.upgrade_to_pro }}" target="_blank" aria-label="<?php esc_attr_e('Upgrade to pro', 'hester-core'); ?> {{data.name}}"><?php esc_html_e('Upgrade to pro', 'hester-core'); ?></a>
						<# } else { #>
							<a class="hester-btn primary hide-if-no-customize hester-demo-import" href="#" disabled="disabled">
								<?php esc_html_e('Import Demo', 'hester-core'); ?>
							</a>
						<# } #>

					</div>
					<div class="wp-full-overlay-sidebar-content">
						<div class="install-theme-info">

							<# if ( data.pro ) { #>
								<span class="site-type {{{data.pro}}}">Pro</span>
							<# } #>

							<div class="hester-demo-name">
								<span><?php esc_html_e('You are previewing', 'hester-core'); ?></span>
								<h3>{{{data.name}}}</h3>
							</div>

							<# if ( data.screenshot ) { #>
								<div class="theme-screenshot-wrap">
									<img class="theme-screenshot" src="{{{data.screenshot}}}" alt="">
								</div>
							<# } #>

							<div class="theme-description">
								{{{data.description}}}
							</div>

							<div class="hester-demo-section">

								<div class="hester-demo-section-title">
									<span class="control-heading"><?php esc_html_e('Import Options', 'hester-core'); ?></span>
									<span class="control-toggle">
										<input type="checkbox" id="options_toggle" name="options_toggle" aria-hidden="true">
										<label for="options_toggle" aria-hidden="true"></label>
									</span>
								</div>

								<div class="hester-demo-section-content import-options">
									<p>
										<label class="hester-checkbox">
											<input type="checkbox" checked name="import_customizer" id="import_customizer" />
											<span class="hester-label"><?php esc_html_e('Import Customizer Settings', 'hester-core'); ?></span>
										</label>
									</p>

									<p>
										<label class="hester-checkbox">
											<input type="checkbox" checked name="import_content" id="import_content" />
											<span class="hester-label"><?php esc_html_e('Import Content', 'hester-core'); ?></span>
											<span class="hester-tooltip" data-tooltip="<?php esc_html_e('Import pages, posts and menus from this demo.', 'hester-core'); ?>"><span class="dashicons dashicons-editor-help"></span>
										</label>
									</p>

									<p>
										<label class="hester-checkbox">
											<input type="checkbox" checked name="import_media" id="import_media" />
											<span class="hester-label"><?php esc_html_e('Import Media', 'hester-core'); ?></span>
										</label>
									</p>

									<p>
										<label class="hester-checkbox">
											<input type="checkbox" checked name="import_widgets" id="import_widgets" />
											<span class="hester-label"><?php esc_html_e('Import Widgets', 'hester-core'); ?></span>
										</label>
									</p>

								</div>
							</div>

							<# if ( ! _.isEmpty( data.required_plugins ) ) { #>

								<div class="hester-demo-section">
									<div class="hester-demo-section-title">
										<span class="control-heading"><?php esc_html_e('Plugins Used in This Demo', 'hester-core'); ?> ({{{ _.size( data.required_plugins )}}})</span>
										<span class="control-toggle">
											<input type="checkbox" id="install_plugins_toggle" name="install_plugins_toggle" aria-hidden="true">
											<label for="install_plugins_toggle" aria-hidden="true"></label>
										</span>
									</div>

									<div class="hester-demo-section-content plugin-list">

										<# _.each( data.required_plugins, function( plugin ) { #>

											<p>
												<label class="hester-checkbox plugin-{{plugin.status}}">
													<input type="checkbox" name="install_plugin_{{plugin.slug}}" id="install_plugin_{{plugin.slug}}" data-slug="{{plugin.slug}}" checked="checked" data-status="{{plugin.status}}" <# if ( 'active' === plugin.status ) { #> disabled="disabled" <# } #>/>
													<span class="hester-label">{{{plugin.name}}}</span>

													<# if ( 'active' === plugin.status ) { #>
														<em><i class="dashicons dashicons-yes"></i><?php esc_html_e('Already installed', 'hester-core'); ?></em>
													<# } #>
												</label>
											</p>

										<# } ) #>

										<em class="theme-description"><?php esc_html_e('These plugins will be auto-installed for you.', 'hester-core'); ?></em>
									</div>
								</div>

							<# } #>

						</div>
					</div>

					<div class="wp-full-overlay-footer">
						<div class="footer-import-button-wrap">

							<# if(data.pro && !data.is_pro) { #>
								<a class="hester-btn primary btn-small" href="{{ data.upgrade_to_pro }}" target="_blank" aria-label="<?php esc_attr_e('Upgrade to pro', 'hester-core'); ?> {{data.name}}"><?php esc_html_e('Upgrade to pro', 'hester-core'); ?></a>
							<# } else { #>

							<a class="hester-btn primary large-button hide-if-no-customize hester-demo-import" href="#" disabled="disabled">
								<span class="spinner hester-spinner"></span>
								<span class="status"><?php esc_html_e('Import Demo', 'hester-core'); ?></span>
								<span class="percent"></span>
							</a>
							<# } #>
							<div id="hester-progress-bar">
								<div class="hester-progress-percentage"></div>
							</div>
						</div>
						<button type="button" class="collapse-sidebar button" aria-expanded="true"
								aria-label="<?php esc_html_e('Collapse Sidebar', 'hester-core'); ?>">
							<span class="collapse-sidebar-arrow"></span>
							<span class="collapse-sidebar-label"><?php esc_html_e('Collapse', 'hester-core'); ?></span>
						</button>

						<div class="devices-wrapper">
							<div class="devices">
								<button type="button" class="preview-desktop active" aria-pressed="true" data-device="desktop">
									<span class="screen-reader-text"><?php esc_html_e('Enter desktop preview mode', 'hester-core'); ?></span>
								</button>
								<button type="button" class="preview-tablet" aria-pressed="false" data-device="tablet">
									<span class="screen-reader-text"><?php esc_html_e('Enter tablet preview mode', 'hester-core'); ?></span>
								</button>
								<button type="button" class="preview-mobile" aria-pressed="false" data-device="mobile">
									<span class="screen-reader-text"><?php esc_html_e('Enter mobile preview mode', 'hester-core'); ?></span>
								</button>
							</div>
						</div>

					</div>
				</div>
				<div class="wp-full-overlay-main">
					<iframe src="{{{data.url}}}" title="<?php esc_attr_e('Preview', 'hester-core'); ?>"></iframe>
				</div>
			</div>
		</script>
<?php
	}

	/**
	 * Add tabs to Hester Dashboard page.
	 *
	 * @since 1.0.0
	 * @param array $items Array of navigation items.
	 */
	public function update_navigation_items($items)
	{
		$theme_name =  hester_core()->theme_name;
		$demo = array(
			'demo-library' => array(
				'id'   => 'demo-library',
				'name' => esc_html__('Demo Library', 'hester-core'),
				'icon' => '',
				'url'  => admin_url('admin.php?page='.$theme_name.'-demo-library'),
			),
		);

		$items = hester_array_insert($items, $demo, 'changelog', 'before');

		return $items;
	}
}

/**
 * The function which returns the one Hester_Demo_Library_Page instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $hester_demo_library_page = hester_demo_library_page(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function hester_demo_library_page()
{
	return Hester_Demo_Library_Page::instance();
}

hester_demo_library_page();
