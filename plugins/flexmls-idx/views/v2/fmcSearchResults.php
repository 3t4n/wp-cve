<?php flexmlsPortalPopup::popup_portal('search_page'); ?>
<?php global $fmc_api_portal; ?>
<div class="flexmls_connect__search_results_v2 flexmls-v2-widget flexmls-body-font">
	<?php
		$is_saved_search_enabled = ! empty( $options['portal_saving_searches'] ) && $options['portal_saving_searches'];
		$is_logged_in = $fmc_api_portal->is_logged_in();
		$show_login_buttons = $is_saved_search_enabled && ! $is_logged_in;
		$show_title = ! empty( $title );
	?>

	<?php if ( $show_login_buttons || $show_title ) : ?>
		<div class="flexmls-title-and-login-wrapper">
			<?php if ( $show_title ) : ?>
				<h2 class="flexmls-title flexmls-primary-color-font flexmls-heading-font"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $show_login_buttons ) : ?>
				<div class="flexmls-login-buttons">
					<a href="<?php echo esc_url( $fmc_api_portal->get_portal_page() ); ?>" class="flexmls-btn flexmls-btn-primary flexmls-btn-sm flexmls-primary-color-background">Sign up or log in</a>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php $map_parameter_set = ( ! empty( $_GET['view'] ) && 'map' == $_GET['view'] ); ?>
	<?php require( __DIR__ . '/fmcSearchResults/_listings_map.php' ); ?>

	<div class="flexmls-actions-wrapper">
		<?php if ( empty( $is_widget ) && $is_saved_search_enabled && $is_logged_in ) : ?>
			<?php $is_existing_saved_search = flexmlsSearchUtil::is_existing_saved_search( $search_criteria ); ?>

			<?php if ( ! $is_existing_saved_search ) : ?>
				<a href="#" class="flexmls-btn flexmls-btn-primary flexmls-btn-sm saved-search-button save-search">Save this search</a>
				<?php flexmlsSearchUtil::saved_search_dialog_html( $params['_filter'] ); ?>
				<?php flexmlsSearchUtil::saved_search_dialog_javascript( $params['_filter'] ); ?>
			<?php endif; ?>
		<?php endif; ?>
<?php if( isset( $options['google_maps_api_key'] ) && $options['google_maps_api_key'] && $this->total_rows != 0 ) : ?>
		<div class="flexmls-btn flexmls-btn-secondary flexmls-btn-sm close-map-button"><?php echo ( ! empty( $settings ) && $settings['default_view'] == 'map' ) || $map_parameter_set ? 'Close' : 'Open'; ?> Map
		<?php flexmlsSearchUtil::close_map_javascript(); ?>
		</div>
<?php endif; ?>
	</div>
	<div class="flexmls-count-and-filters-wrapper">
		<div class="flexmls-count-wrapper">
			<span class="flexmls-matches-count"><?php echo number_format( $this->total_rows, 0, '.', ',' ); ?> Matches Found</span>
		</div>

		<div class="flexmls-filters-wrapper">
			<?php require( __DIR__ . '/fmcSearchResults/_search_filters.php' ); ?>
		</div>
	</div>

	<?php require( __DIR__ . '/fmcSearchResults/_listings_list.php' ); ?>

	<?php $widget = new fmcSearchResults; ?>
	<?php echo $widget->pagination( $this->current_page, $this->total_pages ); ?>

	<div class='flexmls_connect__idx_disclosure_text flexmls_connect__disclaimer_text'>
		<?php echo flexmlsConnect::get_big_idx_disclosure_text(); ?>
	</div>
	<hr />
	<div class="fbs-branding" style="text-align: center;">
		<?php echo flexmlsConnect::fbs_products_branding_link(); ?>
	</div>
</div>
