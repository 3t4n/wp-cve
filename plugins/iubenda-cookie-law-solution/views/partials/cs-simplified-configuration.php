<?php
/**
 * Simplified configuration - cs - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="d-lg-flex">
	<?php
	// Including partial banner-style.
	require_once IUBENDA_PLUGIN_PATH . '/views/partials/banner-position.php';
	// Including partial banner-position.
	require_once IUBENDA_PLUGIN_PATH . '/views/partials/banner-style.php';
	?>
</div>

<?php
$legislation             = (array) iub_array_get( iubenda()->options['cs'], 'simplified.legislation', array() );
$legislation_gdpr_status = (bool) iub_array_get( $legislation, 'gdpr' );
$legislation_uspr_status = (bool) iub_array_get( $legislation, 'uspr' );
$legislation_lgpd_status = (bool) iub_array_get( $legislation, 'lgpd' );
$legislation_all_status  = (bool) iub_array_get( $legislation, 'all' );
?>
<div id="legalisation-section" class="mb-5">
	<h4><?php esc_html_e( 'Legislation', 'iubenda' ); ?></h4>
	<div class="scrollable gap-fixer">
		<fieldset class="d-flex checkbox-large checkbox-group required">
			<div class="checkbox-controller">
				<input type="checkbox" id="legislation-gdpr" name="iubenda_cookie_law_solution[simplified][legislation][gdpr]" value="1" <?php checked( true, $legislation_gdpr_status, true ); ?> class="legislation-checkbox">
				<label for="legislation-gdpr"><?php esc_html_e( 'GDPR', 'iubenda' ); ?></label>
			</div>
			<div class="checkbox-controller">
				<input type="checkbox" id="legislation-uspr" name="iubenda_cookie_law_solution[simplified][legislation][uspr]" value="1" <?php checked( true, $legislation_uspr_status, true ); ?> class="legislation-checkbox">
				<label for="legislation-uspr"><?php esc_html_e( 'US State Laws', 'iubenda' ); ?></label>
			</div>
			<div class="checkbox-controller">
				<input type="checkbox" id="legislation-lgpd" name="iubenda_cookie_law_solution[simplified][legislation][lgpd]" value="1" <?php checked( true, $legislation_lgpd_status, true ); ?> class="legislation-checkbox">
				<label for="legislation-lgpd"><?php esc_html_e( 'LGPD', 'iubenda' ); ?></label>
			</div>
			<div class="checkbox-controller">
				<input type="checkbox" id="legislation-all" name="iubenda_cookie_law_solution[simplified][legislation][all]" value="1" <?php checked( true, $legislation_all_status, true ); ?> class="legislation-checkbox">
				<label for="legislation-all"><?php esc_html_e( 'All', 'iubenda' ); ?></label>
			</div>
		</fieldset>
	</div>
</div>
<div id="require-consent-div" class="mb-5">
	<h4><?php esc_html_e( 'Require consent from', 'iubenda' ); ?></h4>
	<div class="scrollable gap-fixer">
		<fieldset class="d-flex radio-large">
			<div class="radio-controller" id="require-consent-worldwide-div">
				<input type="radio" id="require-consent-worldwide" name="iubenda_cookie_law_solution[simplified][require_consent]" value="worldwide" checked required>
				<label for="require-consent-worldwide">
					<div class="d-flex align-items-center">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="30" viewBox="0 0 32 30">
							<g fill="none" fill-rule="evenodd" transform="translate(1 1.632)">
								<circle cx="18" cy="14.368" r="13" fill="currentColor" fill-opacity=".1"/>
								<circle cx="13" cy="13.368" r="13" stroke="currentColor"/>
								<path stroke="currentColor" d="M17.545 4.368h-3.409l-2.045 1.715v1.143h-2.046L8 8.368v1.143h3.409l.682.572h1.364l1.363 1.143h2.727l1.364 1.142 1.364-1.142H23M15.5 15.243h-1.25l-1.875-1.875h-2.5L8 15.243l1.25 3.125h1.875l1.25 1.25v3.125l.625.625h1.25l1.875-1.875v-1.875L18 17.743z"/>
							</g>
						</svg>
						<span class="ml-2"><?php esc_html_e( 'Worldwide', 'iubenda' ); ?></span>
					</div>
				</label>
			</div>
		</fieldset>
	</div>
</div>

<div class="my-5">
	<h4><?php esc_html_e( 'Banner buttons', 'iubenda' ); ?></h4>
	<fieldset id="explicit-fieldset">
		<label class="checkbox-regular">
			<?php
			$_status         = '';
			$_accept_checked = checked( true, (bool) iub_array_get( iubenda()->options['cs'], 'simplified.explicit_accept' ), false );
			$_reject_checked = checked( true, (bool) iub_array_get( iubenda()->options['cs'], 'simplified.explicit_reject' ), false );
			if ( $legislation_gdpr_status || $legislation_all_status ) {
				$_status         = 'disabled';
				$_accept_checked = 'checked';
				$_reject_checked = 'checked';
			}
			?>
			<input type="checkbox" class="mr-2" name="iubenda_cookie_law_solution[simplified][explicit_accept]" <?php echo esc_attr( $_accept_checked ); ?> <?php echo esc_attr( $_status ); ?>>
			<span><?php esc_html_e( 'Explicit Accept and Customize buttons', 'iubenda' ); ?></span>
		</label>

		<label class="checkbox-regular">
			<input type="checkbox" class="mr-2" name="iubenda_cookie_law_solution[simplified][explicit_reject]" <?php echo esc_attr( $_reject_checked ); ?> <?php echo esc_attr( $_status ); ?>>
			<span><?php esc_html_e( 'Explicit Reject button', 'iubenda' ); ?></span>
		</label>
	</fieldset>
</div>

<div class="my-5 <?php echo $legislation_all_status || $legislation_gdpr_status ? '' : esc_attr( 'hidden' ); ?>" id="iab-div">
	<h4><?php esc_html_e( 'Other options', 'iubenda' ); ?></h4>
	<label class="checkbox-regular">
		<input type="checkbox" class="mr-2" name="iubenda_cookie_law_solution[simplified][tcf]" <?php checked( true, (bool) iub_array_get( iubenda()->options['cs'], 'simplified.tcf' ) ); ?>>
		<span>
			<div>
			<?php esc_html_e( 'Enable IAB Transparency and Consent Framework', 'iubenda' ); ?> - <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['enable_iab'] ); ?>" class="link-underline"><?php esc_html_e( 'Learn More', 'iubenda' ); ?></a>
			</div>
			<div class="notice notice--warning mt-2 p-3 d-flex align-items-center text-warning text-xs">
				<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
				<p><?php esc_html_e( 'You should activate this feature if you show ads on your website', 'iubenda' ); ?></p>
			</div>
		</span>
	</label>
</div>
