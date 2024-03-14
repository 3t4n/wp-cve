<?php

namespace LIBRARY;

$license_options = get_option( 'borderless_license_option_name' );
$borderless_license = isset( $license_options['borderless_license_key'] ) ? $license_options['borderless_license_key'] : '';

$predefined_themes = $this->import_files;

if ( ! empty( $this->import_files ) && isset( $_GET['import-mode'] ) && 'manual' === $_GET['import-mode'] ) {
	$predefined_themes = array();
}
?>

<div class="borderless-library">

	<div class="container-fluid">
		<div class="row">

			<nav class="borderless-library__header navbar py-3 sticky-top">
				<div class="container-fluid">
					<a class="navbar-brand" href="#">
						<img src=<?php echo BORDERLESS__ASSETS . "img/borderless.svg" ?> alt="Logo">
					</a>

					<div class="borderless-library__live-search">
						<i class="bi bi-search"></i>
						<input type="search" class="borderless-library__live-search-input form-control js-library-gl-search" name="library-gl-search" value="" placeholder="<?php esc_html_e( 'Search Templates...', 'borderless' ); ?>">
					</div>
				</div>
			</nav>

			<?php $theme = wp_get_theme(); ?>

			<div class="borderless-library__filters col">
				<?php $categories = Helpers::get_all_demo_import_categories( $predefined_themes ); ?>
			
				<div class="borderless-library__filters-inner d-flex flex-column flex-shrink-0 my-3">

					<div class="borderless-library__filter nav-item">
						<a class="borderless-library__dropdown-nav-link nav-link dropdown-toggle collapsed" href="#borderlessLibraryFilterLicense" role="button" data-bs-toggle="collapse" data-bs-target="#borderlessLibraryFilterLicense" aria-expanded="false" aria-controls="borderlessLibraryFilterLicense">
							<div class="borderless-library__dropdown-nav-link-inner">
								<i class="bi-key nav-icon"></i>
								<span class="nav-link-title">License</span>
							</div>
						</a>

						<div id="borderlessLibraryFilterLicense" class="borderless-library__collapse-nav nav-collapse collapse " data-bs-parent="#borderlessLibraryFilter" hs-parent-area="#borderlessLibraryFilter" data-filter-group="license">
							<a class="borderless-library__collapse-nav-link nav-link active" href="#filter" data-filter="*"><?php esc_html_e( 'All Templates', 'borderless' ); ?></a>
							<a class="borderless-library__collapse-nav-link nav-link" href="#filter" data-filter=".pro-template"><?php esc_html_e( 'Pro Templates', 'borderless' ); ?></a>
						</div>
					</div>


					<div class="borderless-library__filter nav-item">
						<a class="borderless-library__dropdown-nav-link nav-link dropdown-toggle collapsed" href="#borderlessLibraryFilterPageBuilder" role="button" data-bs-toggle="collapse" data-bs-target="#borderlessLibraryFilterPageBuilder" aria-expanded="false" aria-controls="borderlessLibraryFilterPageBuilder">
							<div class="borderless-library__dropdown-nav-link-inner">
								<i class="bi-app nav-icon"></i>
								<span class="nav-link-title">Page Builder</span>
							</div>
						</a>

						<div id="borderlessLibraryFilterPageBuilder" class="borderless-library__collapse-nav nav-collapse collapse " data-bs-parent="#borderlessLibraryFilter" hs-parent-area="#borderlessLibraryFilter" data-filter-group="page-builders">
						<a class="borderless-library__collapse-nav-link nav-link active" href="#filter" data-filter="*"><?php esc_html_e( 'All Page Builders', 'borderless' ); ?></a>
							<a class="borderless-library__collapse-nav-link nav-link" href="#filter" data-filter=".elementor"><?php esc_html_e( 'Elementor', 'borderless' ); ?></a>
							<a class="borderless-library__collapse-nav-link nav-link" href="#filter" data-filter=".wpbakery"><?php esc_html_e( 'WPBakery', 'borderless' ); ?></a>
						</div>
					</div>


					<div class="borderless-library__filter nav-item">
						<a class="borderless-library__dropdown-nav-link nav-link dropdown-toggle" href="#borderlessLibraryFilterCategories" role="button" data-bs-toggle="collapse" data-bs-target="#borderlessLibraryFilterCategories" aria-expanded="true" aria-controls="borderlessLibraryFilterCategories">
							<div class="borderless-library__dropdown-nav-link-inner">
								<i class="bi-grid nav-icon"></i>
								<span class="nav-link-title">Categories</span>
							</div>
						</a>

						<div id="borderlessLibraryFilterCategories" class="borderless-library__collapse-nav nav-collapse collapse show" data-bs-parent="#borderlessLibraryFilter" hs-parent-area="#borderlessLibraryFilter" data-filter-group="categories">
						<a class="borderless-library__collapse-nav-link nav-link active" href="#filter" data-filter="*"><?php esc_html_e( 'All Categories', 'borderless' ); ?></a>
						<?php foreach ( $categories as $category => $title ) { ?>
								<a class="borderless-library__collapse-nav-link nav-link" href="#filter" data-filter=".<?php echo esc_html( $category ); ?>"><?php echo esc_html( $title ); ?></a>
							<?php } ?>
						</div>
					</div>
					
				</div>
			</div>
			<div class="borderless-library__templates-container col">
				<div class="borderless-library__templates row my-3">

					<?php foreach ( $predefined_themes as $index => $import_file ) { ?>
						<?php
							$img_src = isset( $import_file['import_preview_image_url'] ) ? $import_file['import_preview_image_url'] : '';
							if ( empty( $img_src ) ) {
								$theme = wp_get_theme();
								$img_src = $theme->get_screenshot();
							}
						?>
						
						<div class="borderless-library__template col-md-4 <?php echo esc_attr( Helpers::get_demo_import_item_categories( $import_file ) ) .' '. esc_attr( $import_file['license'] ) .' '. esc_attr( $import_file['page_builder'] ); ?>">
							<div class="borderless-library__template-inner">
								<div class="borderless-library__template-image-container">
									<?php if ( ! empty( $img_src ) ) { ?>
										<img class="borderless-library__template-item-image" src="<?php echo esc_url( $img_src ) ?>">
									<?php } else { ?>
										<div class="borderless-library__template-item-image  borderless-library__template-image--no-image"><?php esc_html_e( 'No preview image.', 'borderless' ); ?></div>
									<?php } ?>
								</div>
								<div class="borderless-library__template-body text-center<?php echo ! empty( $import_file['preview_url'] ) ? '  library__gl-item-footer--with-preview' : ''; ?>">
									<h4 class="borderless-library__template-body-title" title="<?php echo esc_attr( $import_file['import_file_name'] ); ?>"><?php echo esc_html( $import_file['import_file_name'] ); ?></h4>
									<span class="borderless-library__template-body-buttons">
										<?php if ( ! empty( $import_file['preview_url'] ) ) { ?>
											<a class="borderless-library__template-body-button" href="<?php echo esc_url( $import_file['preview_url'] ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'borderless' ); ?></a>
										<?php } ?>
											<?php if ( $import_file['license'] == 'pro-template' && !( strlen($borderless_license) == 40 && preg_match('/\d/', $borderless_license) && preg_match('/[a-zA-Z]/', $borderless_license) ) ) { ?>
												<a class="borderless-library__template-body-button borderless-library__template-body-button--no-license" href="https://visualmodo.com/borderless/" target="_blank"><?php esc_html_e( 'Buy Pro Version', 'borderless' ); ?></a>
											<?php } else { ?>
												<a class="borderless-library__template-body-button" href="<?php echo $this->get_plugin_settings_url( [ 'step' => 'import', 'import' => esc_attr( $index ) ] ); ?>"><?php esc_html_e( 'Import', 'borderless' ); ?></a>
											<?php } ?>
									</span>
								</div>
							</div>
						</div>

					<?php } ?>

				</div>
			</div>
		</div>
	</div>
</div>

<?php Helpers::do_action( 'library/plugin_page_footer' ); ?>
