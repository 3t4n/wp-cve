<?php
// Header contet on the dashboard menu pages.
$logo_url = WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/images/wisdmlabs_logo.svg';
?>

<div class='wrld-dashboard-header'>
	<div class='wrld-company-logo'>
		<img src="<?php echo esc_attr( $logo_url ); ?>" width="70px" alt="WisdmLabs">
	</div>
	<div class="wrld-header-title-container">
		<div>
			<span class='wrld-header-title-main'> <?php esc_attr_e( 'WISDM Reports for LearnDash', 'learndash-reports-by-wisdmlabs' ); ?> </span>
		</div>
		<div class='wrld-header-subtitle'> 
			<span class='wrld-header-subtitle-text'> <?php esc_attr_e( 'by ', 'learndash-reports-by-wisdmlabs' ); ?> </span>
			<span class='wrld-header-subtitle-text-name'></span>
		</div>
	</div>
</div>
