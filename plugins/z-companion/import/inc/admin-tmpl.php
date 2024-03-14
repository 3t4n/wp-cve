<?php
/**
 * TMPL - Single Demo Preview
 *
 */
defined( 'ABSPATH' ) or exit;
?>
<div class="wrap" id="z-companion-sites-admin">

	<div id="z-companion-sites-filters" class="wp-filter hide-if-no-js">

		<div class="section-left">

			<!-- All Filters -->
			<div class="filter-count">
				<span class="count">Royal Shop Demo</span>
			</div>
			<div class="filters-wrap" style="display:none;">
				<div id="z-companion-sites-page-builder">
				<select id='zsl-demo-type' class="cs-select cs-skin-elastic zsl-demo-type" style="display:none;">
					<option value="" disabled selected>Select Builder</option>
					<option value='zitashop' data-class="zitashop"><?php _e('WooCommerce Shop','z-companion-sites') ?></option>

<!-- 					<option value='siteorigin' data-class="builder-siteorigin"><php _e('SiteOrigin','z-companion-sites') ?></option>
 -->					</select>
				</div>			
			</div>
		</div> <!-- Section Left -->

		<div class="section-right" style="display:none;">
			<div class="filters-wrap">
				<div id="z-companion-sites-category"></div>
				</div>
			<div class="search-form" style="display:none;">
				<label class="screen-reader-text" for="wp-filter-search-input"><?php _e( 'Search Sites', 'z-companion-sites' ); ?> </label>
				<input placeholder="<?php _e( 'Search Sites...', 'z-companion-sites' ); ?>" type="search" aria-describedby="live-search-desc" id="wp-filter-search-input" class="wp-filter-search">
			</div>

		</div>

	</div>

	<?php do_action( 'zita_site_library_before_site_grid' ); ?>

	<div class="theme-browser rendered">
		<div id="z-companion-sites" class="themes wp-clearfix"></div>
	</div>

	<div class="select-page-builder">
		<div class="note-wrap">
		<!-- 	<h3>
				<span class="up-arrow dashicons dashicons-editor-break"></span>
				<div class="note">'Select Your Elementor Demo', 'z-companion-sites' ); ?></div>
			</h3>
 -->		</div>
		<!--img-->
	</div>

	<div class="spinner-wrap">
	</div>

	<?php do_action( 'zita_site_library_after_site_grid' ); ?>
<div class='z-companion-sites-theme-preview'></div>

</div>

<?php
/**
 * TMPL - Single Demo Preview
 */
?>

<script type="text/template" id="tmpl-z-companion">
	<ul class="">
	<# for ( key in data.elementor ) { #>
	<li  style="width:300px;" class="zitademo themes demo_{{{data.elementor[key].type}}}" slug="{{{data.elementor[key].slug}}}" demo_type ="{{{data.elementor[key].type}}}" zita_template ="{{{data.elementor[key].template}}}" api ="{{{data.elementor[key].demo_api}}}" zita_import="{{{data.elementor[key].import_url}}}" zita_demo="{{{data.elementor[key].demo_url}}}" cate="{{{data.elementor[key].category}}}" thumb="{{{data.elementor[key].thumb}}}" plugins='<# for( keys in data.elementor[key].plugins) { #>{"slug":"{{{data.elementor[key].plugins[keys].slug}}}", "init":"{{{data.elementor[key].plugins[keys].init}}}","name":"{{{data.elementor[key].plugins[keys].name}}}"},<# } #>'>
		<a><img style="width:300px;" src="{{{data.elementor[key].thumb}}}"></a>
		<div class="zita-tmpl-container">
			<h3 class="theme-name" id="zita-theme-name"> {{{data.elementor[key].template}}} </h3>
			<div class="theme-actions">
				<button class="button preview install-theme-preview">Preview</button>
			</div>
		</div>
	</li>
			<# } #>
</ul>
</script>


<!-- site demo , install plugins and import demo -->
 <script type="text/template" id="tmpl-z-companion-demo-template">
  <div class="zita-sites-preview theme-install-overlay wp-full-overlay expanded" style="display: block;">
  		<div class="wp-full-overlay-sidebar">
  			<div class="wp-full-overlay-header" data-required-plugins="{{data.required_plugins}}" data-demo-api="{{{data.demo_api}}}" data-demo-slug="{{{data.slug}}}">
  				<button class="close-full-overlay"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'z-companion-sites' ); ?></span></button>

				<button class="previous-theme"><span class="screen-reader-text"><?php esc_html_e( 'Previous', 'z-companion-sites' ); ?></span></button>

				<button class="next-theme"><span class="screen-reader-text"><?php esc_html_e( 'Next', 'z-companion-sites' ); ?></span></button>

				<a class="button hide-if-no-customize zita-demo-import" href="#" data-import="disabled"><?php esc_html_e( 'Install Plugins', 'z-companion-sites' ); ?></a>
			</div>

			<div class="wp-full-overlay-sidebar-content">
				<div class="install-theme-info">

					<!-- <span class="site-type {{{data.zita_demo_type}}}">{{{data.zita_demo_type}}}</span> -->
					<h3 class="theme-name">{{{data.demo_name}}}</h3>
					<# if ( data.screenshot.length ) { #>
						<img class="theme-screenshot" src="{{{data.screenshot}}}" alt="">
					<# } #>

					<div class="theme-details">
						<!-- {{{data.content}}} -->
					</div>
					<a href="#" class="theme-details-read-more"><?php _e( '', 'z-companion-sites' ); ?></a>

					<div class="required-plugins-wrap">
						<h4><?php _e( 'Required Plugins', 'z-companion-sites' ); ?> </h4>
							<div class="required-plugins">
								<div class="spinner-wrap">
									<span class="spinner is-active"></span>
								</div>
							<!-- <# for ( keys in data.required_plugins) { #>
								

							<div class="plugin-card  		plugin-card-{{{data.required_plugins[keys].slug}}}" data-slug="so-widgets-bundle" data-init="{{{data.required_plugins[keys].init}}}">	
							<span class="title">{{{data.required_plugins[keys].name}}}</span>	

							<button class="button install-now" data-init="{{{data.required_plugins[keys].init}}}" data-slug="{{{data.required_plugins[keys].slug}}}" data-name="{{{data.required_plugins[keys].name}}}">Install Now	</button>

							</div>


								<# } #> -->

						</div>
					</div>
				</div>
				<div class="wp-full-overlay-footer">

				<div class="footer-import-button-wrap">
					<a class="button button-hero hide-if-no-customize zita-demo-import" href="#" data-import="disabled">
						<?php esc_html_e( 'Install Plugins', 'z-companion-sites' ); ?>
					</a>
				</div>

				<button type="button" class="collapse-sidebar button" aria-expanded="true"
						aria-label="Collapse Sidebar">
					<span class="collapse-sidebar-arrow"></span>
					<span class="collapse-sidebar-label"><?php esc_html_e( 'Collapse', 'z-companion-sites' ); ?></span>
				</button>

				<div class="devices-wrapper">
					<div class="devices">
						<button type="button" class="preview-desktop active" aria-pressed="true" data-device="desktop">
							<span class="screen-reader-text"><?php _e( 'Enter desktop preview mode', 'z-companion-sites' ); ?></span>
						</button>
						<button type="button" class="preview-tablet" aria-pressed="false" data-device="tablet">
							<span class="screen-reader-text"><?php _e( 'Enter tablet preview mode', 'z-companion-sites' ); ?></span>
						</button>
						<button type="button" class="preview-mobile" aria-pressed="false" data-device="mobile">
							<span class="screen-reader-text"><?php _e( 'Enter mobile preview mode', 'z-companion-sites' ); ?></span>
						</button>
					</div>
				</div>
			</div>
			</div>
		</div>
		<div class="wp-full-overlay-main">
			<iframe src="{{{data.demo_url}}}" title="<?php esc_attr_e( 'Preview', 'z-companion-sites' ); ?>"></iframe>
		</div>
  </div>
</script>
<?php
wp_print_admin_notice_templates();