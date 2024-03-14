<?php

namespace LIBRARY;

class ViewHelpers {

	public static function plugin_header_output() {
		ob_start(); ?>
		<nav class="borderless-library__header navbar py-3 sticky-top">
			<div class="container-fluid">
				<a class="navbar-brand" href="#">
					<img src=<?php echo BORDERLESS__ASSETS . "img/borderless.svg" ?> alt="Logo">
				</a>
			</div>
		</nav>
		<?php $plugin_title = ob_get_clean();

		return Helpers::apply_filters( 'library/plugin_page_title', $plugin_title );
	}

	public static function small_theme_card( $selected = null ) {
		$theme      = wp_get_theme();
		$screenshot = $theme->get_screenshot();
		$name       = $theme->name;

		if ( isset( $selected ) ) {
			$library          = BorderlessLibraryImporter::get_instance();
			$selected_data = $library->import_files[ $selected ];
			$name          = ! empty( $selected_data['import_file_name'] ) ? $selected_data['import_file_name'] : $name;
			$screenshot    = ! empty( $selected_data['import_preview_image_url'] ) ? $selected_data['import_preview_image_url'] : $screenshot;
		}

		ob_start(); ?>
		<div class="library__card library__card--theme">
			<div class="library__card-content">
				<?php if ( $screenshot ) : ?>
					<div class="screenshot"><img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'borderless' ); ?>" /></div>
				<?php else : ?>
					<div class="screenshot blank"></div>
				<?php endif; ?>
			</div>
			<div class="library__card-footer">
				<h3><?php echo esc_html( $name ); ?></h3>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
