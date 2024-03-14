<?php
/**
 * Menu Page.
 */
$sites_and_pages = Demo_Importer_Plus::get_instance()->get_all_sites();

$page_builder_sites = array_filter(
	$sites_and_pages,
	function( $site ) {
		return $site['site_page_builder'] === $this->get_setting( 'page_builder' );
	}
);

?>
<div class="demo-importer-menu-page">
	<div class="nav-tab-wrapper">
		<div class="menu-content-left">
			<div class="logo">
				<div class="dip-sites-logo-wrap">
					<svg xmlns="http://www.w3.org/2000/svg" width="190" height="37.667" viewBox="0 0 190 37.667">
						<g id="Group_5915" data-name="Group 5915" transform="translate(-708 -127)">
							<g id="Rectangle_1829" data-name="Rectangle 1829" transform="translate(708 127)" fill="rgba(255,189,189,0.1)" stroke="#3772ff" stroke-width="4" opacity="0.25">
							<rect width="28" height="37.667" stroke="none"/>
							<rect x="2" y="2" width="24" height="33.667" fill="none"/>
							</g>
							<text id="Demo" transform="translate(748 153)" fill="#23262f" font-size="17" font-family="SegoeUI-Bold, Segoe UI" font-weight="700" opacity="0.85"><tspan x="0" y="0">Demo</tspan></text>
							<text id="Importer_" data-name="Importer +" transform="translate(809 153)" fill="#23262f" font-size="17" font-family="SegoeUI-Bold, Segoe UI" font-weight="700" opacity="0.85"><tspan x="0" y="0">Importer +</tspan></text>
							<rect id="Rectangle_1830" data-name="Rectangle 1830" width="14" height="21" transform="translate(722 135)" fill="#fff"/>
							<path id="Path_30907" data-name="Path 30907" d="M1303.618,174.808c0,.016,7.648,7.815,7.648,7.815l7.634-7.815h-4.237v-3.479h-6.8v3.479S1303.618,174.792,1303.618,174.808Z" transform="translate(553.406 1456.9) rotate(-90)" fill="#3772ff"/>
						</g>
					</svg>
				</div>
			</div>
			<div class="back-to-layout" title="Back to Layout"><span class="dashicons dashicons-arrow-left-alt2"></span></div>
		</div>
		<div class="search-filter-wrap">
			<div class="filter-inner">
		<?php
		$categories = Demo_Importer_Plus::get_instance()->get_api_option( 'demo-importerplus-sites-categories' );

		if ( ! empty( $categories ) ) {
			?>
			<div id="dip-category-filter" class="search-filter-wrap">
			<span class="dip-category-filter-anchor" data-slug=""><?php esc_html_e( 'All', 'demo-importer-plus' ); ?></span>
			<ul class="dip-category-filter-items">
			<span class="dip-filterby-lbl"><?php echo esc_html__( 'Demo Type', 'demo-importer-plus' ); ?></span>
				<li class="dip-filter-wrap-checkbox first-wrap">
					<label>
						<input id="radio-all" type="radio" name="dip-radio" class="checkbox active" value="" checked /><?php esc_html_e( 'All', 'demo-importer-plus' ); ?>
					</label>
				</li>
				<li class="dip-filter-wrap-checkbox">
					<label>
						<input id="radio-free" type="radio" name="dip-radio" class="checkbox" value="free" /><?php esc_html_e( 'Free', 'demo-importer-plus' ); ?>
					</label>
				</li>
				<li class="dip-filter-wrap-checkbox">
					<label>
						<input id="radio-agency" type="radio" name="dip-radio" class="checkbox" value="pro" /><?php esc_html_e( 'Premium', 'demo-importer-plus' ); ?>
					</label>
				</li>
				<span class="dip-filterby-lbl"><?php echo esc_html__( 'Demo Category', 'demo-importer-plus' ); ?></span>
				<li class="dip-category category-active" data-slug=""><?php esc_html_e( 'All', 'demo-importer-plus' ); ?> </li>
				<?php
				foreach ( $categories as $key => $value ) {
					if ( 'free' !== $value['slug'] && 'uncategorized' !== $value['slug'] ) {
						?>
					<li class="dip-category" data-slug="<?php echo esc_attr( $value['slug'] ); ?>"><?php echo esc_html( $value['name'] ); ?> </li>
						<?php
					}
				}
				?>
			</ul>
			</div>
			<?php
		}
		// echo '<pre>' . print_r( $categories, true ) . '</pre>';
		?>
		<input class="demo-importer-plus-search" type="search" placeholder="Search...">
			</div>
		</div>
	</div><!-- .nav-tab-wrapper -->
</div>
<div class="theme-browser rendered">
			<div id="dip-sites" class="themes wp-clearfix"></div>
			<div id="site-pages" class="themes wp-clearfix"></div>
			<div class="dip-sites-result-preview" style="display: none;"></div>

			<div class="dip-sites-popup" style="display: none;">
				<div class="overlay"></div>
				<div class="inner">
					<div class="heading">
						<h3><?php esc_html_e( 'Heading', 'demo-importer-plus' ); ?></h3>
						<span class="dashicons close dashicons-no-alt"></span>
					</div>
					<div class="demo-import-plus-import-content"></div>
					<div class="di-actioms-wrap"></div>
				</div>
			</div>
		</div>
<div class="di-sites__search-title"><?php _e( 'Available Templates', 'demo-importer-plus' ); ?></div>
<div id="demo-import-plus" class="themes wp-clearfix theme-browser">
	<div class="theme di-sites-ldr-placeholder">
		<div class="svg-animated-loader">
			<svg xmlns="http://www.w3.org/2000/svg" width="381.63" height="559.11" viewBox="0 0 381.63 559.11">
				<g id="Group_2" data-name="Group 2" transform="translate(-619 -169)">
					<rect id="Rectangle_1" data-name="Rectangle 1" width="381.63" height="559.11" rx="8" transform="translate(619 169)" fill="#f5f8ff"/>
					<rect id="Rectangle_2" data-name="Rectangle 2" width="109" height="42" rx="4" transform="translate(867 660)" fill="#dce7ff"/>
					<g id="Group_1" data-name="Group 1" transform="translate(0 6)">
					<rect id="Rectangle_3" data-name="Rectangle 3" width="128" height="11" transform="translate(645 660)" fill="#dce7ff"/>
					<rect id="Rectangle_4" data-name="Rectangle 4" width="64" height="7" transform="translate(645 683)" fill="#e9f0ff"/>
					</g>
					<line id="Line_1" data-name="Line 1" x2="331" transform="translate(645.5 636.5)" fill="none" stroke="#dce7ff" stroke-width="1"/>
				</g>
			</svg>
		</div>
	</div>
	<div class="theme di-sites-ldr-placeholder">
		<div class="svg-animated-loader">
			<svg xmlns="http://www.w3.org/2000/svg" width="381.63" height="559.11" viewBox="0 0 381.63 559.11">
				<g id="Group_2" data-name="Group 2" transform="translate(-619 -169)">
					<rect id="Rectangle_1" data-name="Rectangle 1" width="381.63" height="559.11" rx="8" transform="translate(619 169)" fill="#f5f8ff"/>
					<rect id="Rectangle_2" data-name="Rectangle 2" width="109" height="42" rx="4" transform="translate(867 660)" fill="#dce7ff"/>
					<g id="Group_1" data-name="Group 1" transform="translate(0 6)">
					<rect id="Rectangle_3" data-name="Rectangle 3" width="128" height="11" transform="translate(645 660)" fill="#dce7ff"/>
					<rect id="Rectangle_4" data-name="Rectangle 4" width="64" height="7" transform="translate(645 683)" fill="#e9f0ff"/>
					</g>
					<line id="Line_1" data-name="Line 1" x2="331" transform="translate(645.5 636.5)" fill="none" stroke="#dce7ff" stroke-width="1"/>
				</g>
			</svg>
		</div>
	</div>
	<div class="theme di-sites-ldr-placeholder">
		<div class="svg-animated-loader">
			<svg xmlns="http://www.w3.org/2000/svg" width="381.63" height="559.11" viewBox="0 0 381.63 559.11">
				<g id="Group_2" data-name="Group 2" transform="translate(-619 -169)">
					<rect id="Rectangle_1" data-name="Rectangle 1" width="381.63" height="559.11" rx="8" transform="translate(619 169)" fill="#f5f8ff"/>
					<rect id="Rectangle_2" data-name="Rectangle 2" width="109" height="42" rx="4" transform="translate(867 660)" fill="#dce7ff"/>
					<g id="Group_1" data-name="Group 1" transform="translate(0 6)">
					<rect id="Rectangle_3" data-name="Rectangle 3" width="128" height="11" transform="translate(645 660)" fill="#dce7ff"/>
					<rect id="Rectangle_4" data-name="Rectangle 4" width="64" height="7" transform="translate(645 683)" fill="#e9f0ff"/>
					</g>
					<line id="Line_1" data-name="Line 1" x2="331" transform="translate(645.5 636.5)" fill="none" stroke="#dce7ff" stroke-width="1"/>
				</g>
			</svg>
		</div>
	</div>
</div>
<div class="demo-importer-plus-search-results themes wp-clearfix theme-browser">
<div class="theme di-sites-ldr-placeholder">
		<div class="svg-animated-loader">
			<svg xmlns="http://www.w3.org/2000/svg" width="381.63" height="559.11" viewBox="0 0 381.63 559.11">
				<g id="Group_2" data-name="Group 2" transform="translate(-619 -169)">
					<rect id="Rectangle_1" data-name="Rectangle 1" width="381.63" height="559.11" rx="8" transform="translate(619 169)" fill="#f5f8ff"/>
					<rect id="Rectangle_2" data-name="Rectangle 2" width="109" height="42" rx="4" transform="translate(867 660)" fill="#dce7ff"/>
					<g id="Group_1" data-name="Group 1" transform="translate(0 6)">
					<rect id="Rectangle_3" data-name="Rectangle 3" width="128" height="11" transform="translate(645 660)" fill="#dce7ff"/>
					<rect id="Rectangle_4" data-name="Rectangle 4" width="64" height="7" transform="translate(645 683)" fill="#e9f0ff"/>
					</g>
					<line id="Line_1" data-name="Line 1" x2="331" transform="translate(645.5 636.5)" fill="none" stroke="#dce7ff" stroke-width="1"/>
				</g>
			</svg>
		</div>
	</div>
	<div class="theme di-sites-ldr-placeholder">
		<div class="svg-animated-loader">
			<svg xmlns="http://www.w3.org/2000/svg" width="381.63" height="559.11" viewBox="0 0 381.63 559.11">
				<g id="Group_2" data-name="Group 2" transform="translate(-619 -169)">
					<rect id="Rectangle_1" data-name="Rectangle 1" width="381.63" height="559.11" rx="8" transform="translate(619 169)" fill="#f5f8ff"/>
					<rect id="Rectangle_2" data-name="Rectangle 2" width="109" height="42" rx="4" transform="translate(867 660)" fill="#dce7ff"/>
					<g id="Group_1" data-name="Group 1" transform="translate(0 6)">
					<rect id="Rectangle_3" data-name="Rectangle 3" width="128" height="11" transform="translate(645 660)" fill="#dce7ff"/>
					<rect id="Rectangle_4" data-name="Rectangle 4" width="64" height="7" transform="translate(645 683)" fill="#e9f0ff"/>
					</g>
					<line id="Line_1" data-name="Line 1" x2="331" transform="translate(645.5 636.5)" fill="none" stroke="#dce7ff" stroke-width="1"/>
				</g>
			</svg>
		</div>
	</div>
	<div class="theme di-sites-ldr-placeholder">
		<div class="svg-animated-loader">
			<svg xmlns="http://www.w3.org/2000/svg" width="381.63" height="559.11" viewBox="0 0 381.63 559.11">
				<g id="Group_2" data-name="Group 2" transform="translate(-619 -169)">
					<rect id="Rectangle_1" data-name="Rectangle 1" width="381.63" height="559.11" rx="8" transform="translate(619 169)" fill="#f5f8ff"/>
					<rect id="Rectangle_2" data-name="Rectangle 2" width="109" height="42" rx="4" transform="translate(867 660)" fill="#dce7ff"/>
					<g id="Group_1" data-name="Group 1" transform="translate(0 6)">
					<rect id="Rectangle_3" data-name="Rectangle 3" width="128" height="11" transform="translate(645 660)" fill="#dce7ff"/>
					<rect id="Rectangle_4" data-name="Rectangle 4" width="64" height="7" transform="translate(645 683)" fill="#e9f0ff"/>
					</g>
					<line id="Line_1" data-name="Line 1" x2="331" transform="translate(645.5 636.5)" fill="none" stroke="#dce7ff" stroke-width="1"/>
				</g>
			</svg>
		</div>
	</div>
</div>
<div id="site-pages">
	<div class="single-site-wrap">
		<div class="svg-animated-loader ">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1635.23" height="801.5" viewBox="0 0 1635.23 801.5">
				<defs>
					<clipPath id="clip-path">
					<rect width="737.23" height="709.5" fill="none"/>
					</clipPath>
				</defs>
				<g id="Group_4" data-name="Group 4" transform="translate(-74 -66)">
					<rect id="Rectangle_5" data-name="Rectangle 5" width="846.14" height="667" transform="translate(74 115)" fill="#f5f8ff"/>
					<g id="Repeat_Grid_1" data-name="Repeat Grid 1" transform="translate(972 158)" clip-path="url(#clip-path)">
					<g transform="translate(-972 -158)">
						<g id="Group_3" data-name="Group 3">
						<rect id="Rectangle_6" data-name="Rectangle 6" width="228.23" height="338.5" transform="translate(972 158)" fill="#f5f8ff"/>
						<rect id="Rectangle_8" data-name="Rectangle 8" width="222" height="42" transform="translate(975 451)" fill="#fff"/>
						<rect id="Rectangle_7" data-name="Rectangle 7" width="93" height="9" transform="translate(993 468)" fill="#dce7ff"/>
						</g>
					</g>
					<g transform="translate(-718 -158)">
						<g id="Group_3-2" data-name="Group 3">
						<rect id="Rectangle_6-2" data-name="Rectangle 6" width="228.23" height="338.5" transform="translate(972 158)" fill="#f5f8ff"/>
						<rect id="Rectangle_8-2" data-name="Rectangle 8" width="222" height="42" transform="translate(975 451)" fill="#fff"/>
						<rect id="Rectangle_7-2" data-name="Rectangle 7" width="93" height="9" transform="translate(993 468)" fill="#dce7ff"/>
						</g>
					</g>
					<g transform="translate(-464 -158)">
						<g id="Group_3-3" data-name="Group 3">
						<rect id="Rectangle_6-3" data-name="Rectangle 6" width="228.23" height="338.5" transform="translate(972 158)" fill="#f5f8ff"/>
						<rect id="Rectangle_8-3" data-name="Rectangle 8" width="222" height="42" transform="translate(975 451)" fill="#fff"/>
						<rect id="Rectangle_7-3" data-name="Rectangle 7" width="93" height="9" transform="translate(993 468)" fill="#dce7ff"/>
						</g>
					</g>
					<g transform="translate(-972 211)">
						<g id="Group_3-4" data-name="Group 3">
						<rect id="Rectangle_6-4" data-name="Rectangle 6" width="228.23" height="338.5" transform="translate(972 158)" fill="#f5f8ff"/>
						<rect id="Rectangle_8-4" data-name="Rectangle 8" width="222" height="42" transform="translate(975 451)" fill="#fff"/>
						<rect id="Rectangle_7-4" data-name="Rectangle 7" width="93" height="9" transform="translate(993 468)" fill="#dce7ff"/>
						</g>
					</g>
					<g transform="translate(-718 211)">
						<g id="Group_3-5" data-name="Group 3">
						<rect id="Rectangle_6-5" data-name="Rectangle 6" width="228.23" height="338.5" transform="translate(972 158)" fill="#f5f8ff"/>
						<rect id="Rectangle_8-5" data-name="Rectangle 8" width="222" height="42" transform="translate(975 451)" fill="#fff"/>
						<rect id="Rectangle_7-5" data-name="Rectangle 7" width="93" height="9" transform="translate(993 468)" fill="#dce7ff"/>
						</g>
					</g>
					<g transform="translate(-464 211)">
						<g id="Group_3-6" data-name="Group 3">
						<rect id="Rectangle_6-6" data-name="Rectangle 6" width="228.23" height="338.5" transform="translate(972 158)" fill="#f5f8ff"/>
						<rect id="Rectangle_8-6" data-name="Rectangle 8" width="222" height="42" transform="translate(975 451)" fill="#fff"/>
						<rect id="Rectangle_7-6" data-name="Rectangle 7" width="93" height="9" transform="translate(993 468)" fill="#dce7ff"/>
						</g>
					</g>
					</g>
					<rect id="Rectangle_9" data-name="Rectangle 9" width="164" height="20" transform="translate(972 115)" fill="#dce7ff"/>
					<rect id="Rectangle_10" data-name="Rectangle 10" width="265" height="28" transform="translate(74 66)" fill="#dce7ff"/>
				</g>
				</svg>
		</div>
	</div>
</div>
<div class="loader-wrap">
	<div class="loader-ellips">
	<span class="loader-ellips__dot"></span>
	<span class="loader-ellips__dot"></span>
	<span class="loader-ellips__dot"></span>
	<span class="loader-ellips__dot"></span>
	</div>
</div>
<div class="demo-import-sitest-result-prev" style="display: none;"></div>
<div class="demo-import-sites-popup" style="display: none;">
	<div class="overlay"></div>
	<div class="inner">
		<div class="heading">
			<h3><?php esc_html_e( 'Heading', 'demo-importer-plus' ); ?></h3>
			<span class="dashicons close dashicons-no-alt"></span>
		</div>
		<div class="demo-import-plus-import-content"></div>
		<div class="di-actioms-wrap"></div>
	</div>
</div>
<?php
/**
 * TMPL - Single Site Preview
 */
?>
<script type="text/template" id="tmpl-demo-imprt-single-site-preview">
	<#
		var AllPagz = [];
		for ( page_id in data.pages ) {
			if ( '' !== page_id ) {
				AllPagz.push( {...data.pages[page_id]} );
			}
		}

		var containerStyle = AllPagz.length > 0 && 'yes' !== data?.is_onepage_demo  ? '' : 'style=width:100%';
	#>
	<div class="single-site-wrap">
		<div class="single-site">
			<div class="single-site-pages-header">
				<h3 class="dip-site-title">{{{data['site_title']}}}</h3>
				<span class="count" style="display: none"></span>
			</div>
			<div class="single-site-preview-wrap" {{containerStyle}}>
				<div class="single-site-preview">
					<img class="theme-screenshot" data-src="" src="{{data['site_featured_image']}}" />
				</div>
			</div>
			<# if( 0 < AllPagz.length && 'yes' !== data?.is_onepage_demo ) { #>
				<div class="single-site-pages-wrap">
					<div class="di-pages-title-wrap">
						<span class="di-pages-title"><?php esc_html_e( 'Page Templates', 'demo-importer-plus' ); ?></span>
					</div>
					<div class="single-site-pages">
						<div id="single-pages">
							<# for ( page_id in data.pages ) {
								if ( '' === page_id ) { continue };
								var dynamic_page = data.pages[page_id]['dynamic-page'] || 'no'; #>
								<div class="theme dip-theme site-single" data-page-id="{{page_id}}" data-dynamic-page="{{dynamic_page}}" >
									<div class="inner">
										<#
										var featured_image_class = '';
										var featured_image = data.pages[page_id]['fullpage-screenshot'] || '';
										if( '' === featured_image ) {
											featured_image = '<?php echo esc_url( DEMO_IMPORTER_PLUS_URI . 'assets/images/placeholder.png' ); ?>';
											featured_image_class = ' no-featured-image ';
										}

										var thumbnail_image = data.pages[page_id]['thumbnail-image-url'] || '';
										if( '' === thumbnail_image ) {
											thumbnail_image = featured_image;
										}
										#>
										<span class="site-preview" data-title="{{ data.pages[page_id]['title'] }}">
											<div class="theme-screenshot one loading {{ featured_image_class }}" data-src="{{ thumbnail_image }}" data-featured-src="{{ featured_image }}" style="background-image: url('{{ featured_image }}');"></div>
										</span>
										<div class="theme-id-container">
											<h3 class="theme-name">
												{{{ data.pages[page_id]['title'] }}}
											</h3>
										</div>
									</div>
								</div>
							<# } #>
						</div>
					</div>
				</div>
			<# } #>
			<div class="single-site-footer">
				<div class="site-action-buttons-wrap">
				<a href="{{data['site_url']}}/" class="button button-hero site-preview-button" target="_blank">Preview "{{{data['site_title']}}}" Site <i class="dashicons dashicons-external"></i></a>
				<div class="site-action-buttons-right">
				<# if( 'free' !== data['site_type'] && ( !demoImporterVars.isPro || !demoImporterVars.proLicenseActive ) ){ #>
					<# if ( demoImporterVars.isPro && !demoImporterVars.proLicenseActive ) { #>
						<a class="button button-hero button-secondary" href="{{demoImporterVars.licensePageURL}}">{{demoImporterVars.activateLicenseTxt}}</i></a>
					<# } #>
					<# if( demoImporterVars.getProText.length > 0 ) { #>
						<a style="margin-left: 15px;" class="button button-hero button-primary" href="{{demoImporterVars.getProURL}}" target="_blank">{{demoImporterVars.getProText}}<i class="dashicons dashicons-external"></i></a>
					<# } #>
					<!-- <span class="dashicons dashicons-editor-help dip-sites-get-agency-bundle-button"><?php esc_html_e( 'Get Pro version license and activate it on your site to import this template.', 'demo-importer-plus' ); ?></span> -->
				<# } else { #>
						<div class="button button-hero button-primary site-import-site-button"><?php esc_html_e( 'Import Complete Site', 'demo-importer-plus' ); ?></div>
						<div style="margin-left: 5px;" class="button button-hero button-primary site-import-layout-button disabled"><?php esc_html_e( 'Import Template', 'demo-importer-plus' ); ?></div>
					<# } #>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<?php
/**
 * TMPL - First Screen
 */
?>
<script type="text/template" id="tmpl-demo-import-sitest-result-prev">

	<div class="overlay"></div>
	<div class="inner">

		<div class="default">
			<div class="heading">
				<# if( 'demo-importer-plus' === data ) { #>
					<h3><?php esc_html_e( 'Your Selected Website is Being Imported.', 'demo-importer-plus' ); ?></h3>
				<# } else { #>
					<h3><?php esc_html_e( 'Your Selected Template is Being Imported.', 'demo-importer-plus' ); ?></h3>
				<# } #>
				<span class="dashicons close dashicons-no-alt"></span>
			</div>

			<div class="demo-import-plus-import-content">
				<div class="install-theme-info">
					<div class="dip-sites-advanced-options-wrap">
						<div class="dip-sites-advanced-options">
							<ul class="dip-site-contents">
								<# if( 'dip-sites' === data ) { #>
									<li class="demo-importer-plus-reset-data">
										<label>
											<input type="checkbox" name="reset" class="checkbox">
											<strong><?php esc_html_e( 'Delete Previously Imported Site', 'demo-importer-plus' ); ?></strong>
											<span class="demo-importer-plus-tooltip-icon" data-tip-id="dip-sites-tooltip-reset-data"><span class="dashicons dashicons-editor-help"></span></span>
											<div class="dip-sites-tooltip-message" id="dip-sites-tooltip-reset-data" style="display: none;"><p><?php esc_html_e( 'Selecting this option will delete all the data from the previous import. Kindly uncheck this option if this is not intended.', 'demo-importer-plus' ); ?></p><p><?php esc_html_e( 'You can find the backup to the current customizer settings at ', 'demo-importer-plus' ); ?><code><?php esc_html_e( '/wp-content/uploads/demo-importer-plus/', 'demo-importer-plus' ); ?></code></p></div>
										</label>
									</li>

									<?php
									$site_data = get_option( 'demo_importer_plus_import_data' );
									if ( ! empty( $site_data ) ) {
										if ( $site_data['data']['theme_name'] ) {
											$theme_name = $site_data['data']['theme_name'];
										}
									} else {
										$theme_name = '';
									}
									 $theme_status = Demo_Importer_Plus::get_instance()->get_theme_status( $theme_name );
									?>
									<?php $theme_dependancy_class = ''; ?>
									<?php $site_import_options['activate-theme'] = true; ?>
									<?php if ( 'installed-and-active' !== $theme_status ) { ?>
										<?php $theme_dependancy_class = 'coachpress-lite-theme-module'; ?>
										<li class="demo-importer-plus-theme-activation">
											<label>
												<input type="checkbox" name="activate-theme" class="checkbox" <?php checked( $site_import_options['activate-theme'], true ); ?> data-status="<?php echo esc_attr( $theme_status ); ?>">
												<strong><?php esc_html_e( 'Install & Activate Compatible Theme', 'demo-importer-plus' ); ?></strong>
												<span class="demo-importer-plus-tooltip-icon" data-tip-id="dip-sites-tooltip-theme-activation"><span class="dashicons dashicons-editor-help"></span></span>
												<div class="dip-sites-tooltip-message" id="dip-sites-tooltip-theme-activation" style="display: none;"><p><?php esc_html_e( 'To import the site in the original format, you would need the same theme activated. You can import it with any other theme, but the site might lose some of the design settings and look a bit different.', 'demo-importer-plus' ); ?></p></div>
											</label>
										</li>
									<?php } ?>

									<li class="demo-importer-plus-import-customizer">
										<label>
											<input type="checkbox" name="customizer" checked="checked" class="checkbox">
											<strong><?php esc_html_e( 'Import Customizer Settings', 'demo-importer-plus' ); ?></strong>
											<span class="demo-importer-plus-tooltip-icon" data-tip-id="dip-sites-tooltip-customizer-settings"><span class="dashicons dashicons-editor-help"></span></span>
											<div class="dip-sites-tooltip-message" id="dip-sites-tooltip-customizer-settings" style="display: none;"><p><?php esc_html_e( 'This will import the customizer settings of the template you have chosen. Please note that, this will override your current customizer settings.', 'demo-importer-plus' ); ?></p>
											</div>
										</label>
									</li>

								<# } #>

								<# if( 'dip-sites' === data ) { #>
									<li class="demo-importer-plus-import-widgets">
										<label>
											<input type="checkbox" name="widgets" checked="checked" class="checkbox">
											<strong><?php esc_html_e( 'Import Widgets', 'demo-importer-plus' ); ?></strong>
										</label>
									</li>
								<# } #>

								<li class="dip-sites-import-plugins">
									<input type="checkbox" name="plugins" checked="checked" class="disabled checkbox" readonly>
									<strong><?php esc_html_e( 'Install Required Plugins', 'demo-importer-plus' ); ?></strong>
									<span class="demo-importer-plus-tooltip-icon" data-tip-id="dip-sites-tooltip-plugins-settings"><span class="dashicons dashicons-editor-help"></span></span>
									<div class="dip-sites-tooltip-message" id="dip-sites-tooltip-plugins-settings" style="display: none;">
										<p><?php esc_html_e( 'Required plugins will be installed and activated automatically.', 'demo-importer-plus' ); ?></p>
										<ul class="required-plugins-list"><span class="spinner is-active"></span></ul>
									</div>
								</li>

								<# if( 'dip-sites' === data ) { #>
									<li class="demo-importer-plus-import-xml">
										<label>
											<input type="checkbox" name="xml" checked="checked" class="checkbox">
											<strong><?php esc_html_e( 'Import Content', 'demo-importer-plus' ); ?></strong>
										</label>
										<span class="demo-importer-plus-tooltip-icon" data-tip-id="dip-sites-tooltip-site-content"><span class="dashicons dashicons-editor-help"></span></span>
										<div class="dip-sites-tooltip-message" id="dip-sites-tooltip-site-content" style="display: none;"><p><?php esc_html_e( 'Selecting this option will import the demo content of the template you have chosen. It will import all the dummy pages, posts, images, and menus. If you do not want the demo content, kindly uncheck this option.', 'demo-importer-plus' ); ?></p></div>
									</li>
								<# } #>
							</ul>
						</div>
					</div>
				</div>
				<div class="dip-importing-wrap">
					<#
					if( 'dip-sites' === data ) {
						var string = 'sites';
					} else {
						var string = 'template';
					}
					#>
					<p>
					<?php
					/* translators: %s is the dynamic string. */
					printf( esc_html__( 'Depending on the %s you have chosen and your server configuration, the process can take a couple of minutes to import the demo content.', 'demo-importer-plus' ), '{{string}}' );
					?>
					</p>
					<p>
					<?php
					/* translators: %s is the dynamic string. */
					printf( esc_html__( 'Please do NOT close this browser window until the %s is imported completely.', 'demo-importer-plus' ), '{{string}}' );
					?>
					</p>

					<div class="current-importing-status-wrap">
						<div class="current-importing-status">
							<div class="current-importing-status-title"></div>
							<div class="current-importing-status-description"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="di-actioms-wrap">
				<a href="#" class="button button-hero button-primary demo-import-plus-impr disabled site-install-site-button"><?php esc_html_e( 'Import', 'demo-importer-plus' ); ?></a>
				<a href="#" class="button button-hero button-primary dip-sites-skip-and-import" style="display: none;"><?php esc_html_e( 'Skip & Import', 'demo-importer-plus' ); ?></a>
				<div class="button button-hero site-import-cancel"><?php esc_html_e( 'Cancel', 'demo-importer-plus' ); ?></div>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="tmpl-demo-importer-plus-site-import-success">
	<div class="heading">
		<h3><?php esc_html_e( 'Imported Successfully!', 'demo-importer-plus' ); ?></h3>
		<span class="dashicons close dashicons-no-alt"></span>
	</div>
	<div class="demo-import-plus-import-content">
		<p><b><?php esc_html_e( 'The Website Imported Successfully! 🎉', 'demo-importer-plus' ); ?></b></p>
		<p>
			<?php esc_html_e( 'You can now start customizing the website to suit your preference. Enjoy website your building!', 'demo-importer-plus' ); ?>&nbsp;
		</p>
		<p><?php esc_html_e( 'PS: The images used in the demo are free for personal uses. We strong recommend you to replace the images and any copyrighted media before publishing your website online.', 'demo-importer-plus' ); ?></p>
	</div>
	<div class="di-actioms-wrap">
		<a class="button button-primary button-hero" href="<?php echo esc_url( site_url() ); ?>" target="_blank"><?php esc_html_e( 'View Site', 'demo-importer-plus' ); ?> <i class="dashicons dashicons-external"></i></a>
	</div>
</script>
<?php
/**
 * TMPL - Import Process Interrupted
 */
?>
<script type="text/template" id="tmpl-demo-importr-plus-request-failed">
	<p><?php esc_html_e( 'Your website is facing a temporary issue connecting to the template server.', 'demo-importer-plus' ); ?></p>
	<p>
		<?php
		/* translators: %s doc link. */
		printf( __( 'Read an article <a href="%s" target="_blank">here</a> to resolve the issue.', 'demo-importer-plus' ), 'https://rishitheme.com/docs/how-to-resolve-demo-import-issue/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</p>
</script>

<script type="text/template" id="tmpl-demo-importer-plus-page-import-success">
	<div class="heading">
		<h3><?php esc_html_e( 'Imported Successfully!', 'demo-importer-plus' ); ?></h3>
		<span class="dashicons close dashicons-no-alt"></span>
	</div>
	<div class="demo-import-plus-import-content">
		<p><b><?php esc_html_e( 'The Template Imported Successfully! 🎉', 'demo-importer-plus' ); ?></b></p>
		<p><?php esc_html_e( 'You can now start customizing the website to suit your preference. Enjoy website your building!', 'demo-importer-plus' ); ?></p>
		<p><?php esc_html_e( 'PS: The images used in the demo are free for personal uses. We strong recommend you to replace the images and any copyrighted media before publishing your website online.', 'demo-importer-plus' ); ?></p>
	</div>
	<div class="di-actioms-wrap">
		<a class="button button-primary button-hero" href="{{data['link']}}" target="_blank"><?php esc_html_e( 'View Template', 'demo-importer-plus' ); ?> <i class="dashicons dashicons-external"></i></a>
	</div>
</script>

<script type="text/template" id="tmpl-demo-importer-plus-compatibility-messages">

	<div class="skip-and-import">
		<div class="heading">
			<h3><?php esc_html_e( 'We\'re Almost There!', 'demo-importer-plus' ); ?></h3>
			<span class="dashicons close dashicons-no-alt"></span>
		</div>
		<div class="demo-import-plus-import-content">

		<# for ( code in data ) { #>
			<# if( Object.keys( data[ code ] ).length ) { #>
				<# for ( id in data[ code ] ) { #>
					<#if ( 'Disable Debug Mode' == data[ code ][id].title ) { #>
						<p><?php esc_html_e( 'Currently, WordPress debug mode is enabled on your website. With this option enabled, any errors caused by the third-party plugin will hinder the import process.', 'demo-importer-plus' ); ?></p>
						<p><?php esc_html_e( 'We recommend you to disable it before starting the import process. You can do this, by adding the following code into the wp-config.php file.', 'demo-importer-plus' ); ?></p>
						<p><?php esc_html_e( 'define(\'WP_DEBUG\', false);', 'demo-importer-plus' ); ?></p>
					<# } else if( 'Updata Plugin' == data[ code ][id].title ) { #>
						<p><?php esc_html_e( 'Updates for some of the installed plugins used in this template are available.', 'demo-importer-plus' ); ?></p>
						<p><?php esc_html_e( 'Please update them for the successful import. You can also skip this but it might affect the template imported.', 'demo-importer-plus' ); ?></p>
					<#	} #>
				<# } #>
			<# } #>
		<# } #>
		</div>
		<div class="di-actioms-wrap">
			<# if( Object.keys( data['errors'] ).length ) { #>
				<a href="#" class="button button-hero button-primary dip-demo-import disabled site-install-site-button"><?php esc_html_e( 'Skip & Import', 'demo-importer-plus' ); ?></a>
				<div class="button button-hero site-import-cancel"><?php esc_html_e( 'Cancel', 'demo-importer-plus' ); ?></div>
			<# } else {
				var plugin_update = data['warnings']['update-available'] || 0;
				if( plugin_update ) { #>
					<a href="<?php echo esc_url( network_admin_url( 'update-core.php' ) ); ?>" class="button button-hero button-primary" target="_blank"><?php esc_html_e( 'Update', 'demo-importer-plus' ); ?></a>
					<a href="#" class="button button-hero button-primary demo-importer-plus-skip-and-import-step"><?php esc_html_e( 'Skip & Import', 'demo-importer-plus' ); ?></a>
				<# } else { #>
					<a href="#" class="button button-hero button-primary demo-importer-plus-skip-and-import-step"><?php esc_html_e( 'Skip & Import', 'demo-importer-plus' ); ?></a>
					<div class="button button-hero site-import-cancel"><?php esc_html_e( 'Cancel', 'demo-importer-plus' ); ?></div>
				<# } #>
			<# } #>
		</div>
	</div>

</script>

<script type="text/template" id="tmpl-demo-importer-plus-page-builder-sites-search">
	<# var pages_list = []; #>
	<# var sites_list = []; #>
	<# var pages_list_arr = []; #>
	<# var sites_list_arr = []; #>
	<# for ( site_id in data ) {
		var type = data[site_id]['type'] || 'site';
		if ( 'site' === type ) {
			sites_list_arr.push( data[site_id] );
			sites_list[site_id] = data[site_id];
		} else {
			pages_list_arr.push( data[site_id] );
			pages_list[site_id] = data[site_id]
		}
	} #>
	<# if ( sites_list_arr.length > 0 ) { #>
		<# for ( site_id in sites_list ) { #>
		<#
			var current_site_id     = site_id;
			var type                = sites_list[site_id]['type'] || 'site';
			var page_site_id        = sites_list[site_id]['site_id'] || '';
			var favorite_status     = false;
			var favorite_class      = '';
			var favorite_title      = '<?php esc_html_e( 'Make as Favorite', 'demo-importer-plus' ); ?>';
			var featured_image_url = sites_list[site_id]['site_featured_image'];
			var thumbnail_image_url = featured_image_url;

			var site_type = sites_list[site_id]['site_type'] || '';
			var page_id = '';

			var title = sites_list[site_id]['site_title'] || '';
			var pages_count = parseInt( sites_list[site_id]['pages-count'] ) || 0;
			var pages_count_string = ( pages_count !== 1 ) ? pages_count + ' Templates' : pages_count + ' Template';
			var pages_count_class = '';
			if( pages_count ) {
				pages_count_class = 'has-pages';
			} else {
				pages_count_class = 'no-pages';
			}
			var site_title = sites_list[site_id]['site-title'] || '';

			var pro_class = site_type && 'free' !== site_type ? 'premium-layout' : '';

			<!-- console.log(site_type); -->
		#>
			<div class="theme demo-import-plus-theme site-single {{favorite_class}} {{pro_class}} {{pages_count_class}} demo-import-plus-previewing-{{type}}" data-site-id="{{current_site_id}}" data-page-id="{{page_id}}">
				<div class="inner">
					<span class="site-preview" data-title="{{{title}}}">
					<div class="btn-wrap">
							<a target="_blank" href="{{data[site_id]['site_url']}}" class="button button-hero button-primary site-preview-button" target="_blank"><?php echo esc_html__( 'Preview', 'demo-importer-plus' ); ?></a>
						</div>
						<div class="theme-screenshot one loading" data-src="{{thumbnail_image_url}}" data-featured-src="{{featured_image_url}}" style="background-image:url('{{featured_image_url}}')">
						<# if ( 'free' !== site_type ) { #>
							<div class="pro-ribbon"><?php esc_html_e( 'Pro', 'demo-importer-plus' ); ?></div>
						<# } #>
				</div>
					</div>
					</span>
					<div class="theme-id-container">
						<div class="theme-name">
							<span class="title">
								<# if ( 'site' === type ) { #>
									<div class='site-title'>{{{title}}}</div>
									<# if ( pages_count ) { #>
										<div class='pages-count'>{{{pages_count_string}}}</div>
									<# } #>
								<# } else { #>
									<div class='site-title'>{{{site_title}}}</div>
									<div class='page-title'>{{{title}}}</div>
								<# } #>
							</span>
						</div>
						<# if ( '' === type || 'site' === type ) { #>
							<div class="favorite-action-wrap" data-favorite="{{favorite_class}}" title="{{favorite_title}}">
								<i class="ast-icon-heart"></i>
							</div>
						<# } #>
					</div>
					<# if ( site_type && 'free' !== site_type ) { #>
						<?php /* translators: %s are white label strings. */ ?>
						<div class="agency-ribbons" title=""><?php esc_html_e( 'Agency', 'demo-importer-plus' ); ?></div>
					<# } #>
				</div>
		<# } #>
	<# } #>
	<# if ( pages_list_arr.length > 0 ) { #>

		<h3 class="dip__search-title"><?php esc_html_e( 'Page Templates', 'demo-importer-plus' ); ?></h3>
		<div class="dip__search-wrap">
		<# for ( site_id in pages_list ) { #>
		<#
			var current_site_id     = site_id;
			var type                = pages_list[site_id]['type'] || 'site';
			var page_site_id        = pages_list[site_id]['site_id'] || '';
			var favorite_status     = false;
			var favorite_class      = '';
			var favorite_title      = '<?php esc_html_e( 'Make as Favorite', 'demo-importer-plus' ); ?>';
			var featured_image_url = pages_list[site_id]['site_featured_image'];
			var thumbnail_image_url = pages_list[site_id]['thumbnail-image-url'] || featured_image_url;

			var site_type = pages_list[site_id]['type'] || '';
			var page_id = '';
			thumbnail_image_url = featured_image_url;
			current_site_id = page_site_id;
			page_id = site_id;

			var title = pages_list[site_id]['title'] || '';
			var pages_count = pages_list[site_id]['pages-count'] || 0;
			var pages_count_class = '';
			if( 'site' === type ) {
				if( pages_count ) {
					pages_count_class = 'has-pages';
				} else {
					pages_count_class = 'no-pages';
				}
			}
			var site_title = pages_list[site_id]['site-title'] || '';

			var pro_class = site_type && 'free' !== site_type ? 'premium-layout' : '';

		#>
			<div class="theme demo-import-plus-theme site-single {{favorite_class}} {{pro_class}} {{pages_count_class}} demo-import-plus-previewing-{{type}}" data-site-id="{{current_site_id}}" data-page-id="{{page_id}}">
				<div class="inner">
					<span class="site-preview" data-title="{{{title}}}">
						<div class="theme-screenshot one loading" data-src="{{thumbnail_image_url}}" data-featured-src="{{featured_image_url}}" style="background-image:url('{{featured_image_url}}')"></div>
					</span>
					<div class="theme-id-container">
						<div class="theme-name">
							<span class="title">
								<div class='site-title'>{{{site_title}}}</div>
								<div class='page-title'>{{{title}}}</div>
							</span>
						</div>
						<# if ( '' === type || 'site' === type ) { #>
							<div class="favorite-action-wrap" data-favorite="{{favorite_class}}" title="{{favorite_title}}">
								<i class="ast-icon-heart"></i>
							</div>
						<# } #>
					</div>
					<# if ( site_type && 'free' !== site_type ) { #>
						<?php /* translators: %s are white label strings. */ ?>
						<div class="agency-ribbons" title=""><?php esc_html_e( 'Agency', 'demo-importer-plus' ); ?></div>
					<# } #>
				</div>
			</div>
		<# } #>
		</div>
	<# } #>

</script>

<script type="text/template" id="tmpl-demo-importer-plus-no-sites">
	<div class="demo-importer-plus-no-sites">
		<div class="inner">
			<h3><?php esc_html_e( 'Sorry! No Results Found.', 'demo-importer-plus' ); ?></h3>
			<div class="content">
				<div class="empty-item">
					<img class="empty-collection-part" src="<?php echo esc_url( DEMO_IMPORTER_PLUS_URI . '/assets/images/not-found.svg' ); ?>" alt="empty-collection">
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-demo-importer-plus-page-builder-sites">
<# for ( Id in data ) { #>
	<#
		var site_id             = data[Id]['id'] || '';
		var current_site_id     = site_id;
		var type                = data[Id]['type'] || 'site';
		var page_site_id        = data[Id]['site_id'] || '';
		var favorite_status     = false;
		var favorite_class      = '';
		var favorite_title      = '<?php esc_html_e( 'Make as Favorite', 'demo-importer-plus' ); ?>';
		var featured_image_url = data[Id]['site_featured_image'];
		var thumbnail_image_url = featured_image_url;

		var site_type = data[Id]['site_type'] || '';
		var page_id = '';

		var title = data[Id]['site_title'] || '';
		var pages_count = 0 !== parseInt( Object.keys(data[Id]['pages']).length ) ? parseInt( Object.keys(data[Id]['pages']).length ) : data[Id]['page_count'] || 0;
		var pages_count_string = ( pages_count !== 1 ) ? pages_count + ' Templates' : pages_count + ' Template';
		var pages_count_class = '';
		if( pages_count ) {
			pages_count_class = 'has-pages';
		} else {
			pages_count_class = 'no-pages';
		}
		var site_title = data[Id]['site-title'] || '';

		var pro_class = site_type && 'free' !== site_type ? 'premium-layout' : '';

	#>
	<div class="theme demo-import-plus-theme site-single {{favorite_class}} {{pro_class}} {{pages_count_class}} demo-import-plus-previewing-{{type}}" data-site-id="{{current_site_id}}" data-page-id="{{page_id}}">
		<div class="inner">
			<span class="site-preview" data-title="{{{title}}}">
			<div class="btn-wrap">
				<a target="_blank" href="{{data[Id]['site_url']}}" class="button button-hero button-primary site-preview-button" target="_blank"><?php echo esc_html__( 'Preview', 'demo-importer-plus' ); ?></a>
			</div>
				<div class="theme-screenshot one loading" data-src="{{thumbnail_image_url}}" data-featured-src="{{featured_image_url}}" style="background-image:url('{{featured_image_url}}')">
				<# if ( 'free' !== site_type ) { #>
							<div class="pro-ribbon"><?php esc_html_e( 'Pro', 'demo-importer-plus' ); ?></div>
						<# } #>
				</div>
			</span>
			<div class="theme-id-container">
				<div class="theme-name">
					<span class="title">
						<# if ( 'site' === type ) { #>
							<div class='site-title'>{{{title}}}</div>
							<# if ( pages_count ) { #>
								<div class='pages-count'>{{{pages_count_string}}}</div>
							<# } #>
						<# } else { #>
							<div class='site-title'>{{{site_title}}}</div>
							<div class='page-title'>{{{title}}}</div>
						<# } #>
					</span>
					<button class="button button-hero demo-import-button"><?php echo esc_html__( 'Import', 'demo-importer-plus' ); ?></button>
				</div>
				<# if ( '' === type || 'site' === type ) { #>
					<div class="favorite-action-wrap" data-favorite="{{favorite_class}}" title="{{favorite_title}}">
						<i class="ast-icon-heart"></i>
					</div>
				<# } #>
			</div>
		</div>
	</div>
<# } #>

</script>
