<?php
/**
 * General settings - cs - partial page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Is the current configuration type is simplified.
$is_cs_simplified = ( new Iubenda_CS_Product_Service() )->is_cs_simplified();
$site_id          = iub_array_get( iubenda()->options['global_options'], 'site_id' );
?>
<div class="tabs">
	<h3 class="text-bold text-gray text-md mb-0"><?php esc_html_e( 'Configuration', 'iubenda' ); ?></h3>
	<div class="scrollable gap-fixer">
		<fieldset class="radio-large">
			<div class="d-flex tabs__nav">
				<?php
				if ( $site_id ) :
					$_status = '';
					if ( $is_cs_simplified ) {
						$_status = 'active';
					}
					?>
					<div class="m-1 mr-2 tabs__nav__item <?php echo esc_html( $_status ); ?>" data-target="configuration-type-simplified-tab" data-group="configuration-type">
						<input class="section-radio-control cs-configuration-type" type="radio" id="radioSimplified" name="iubenda_cookie_law_solution[configuration_type]" value="simplified" <?php checked( 'simplified', iub_array_get( iubenda()->options['cs'], 'configuration_type' ) ); ?>>
						<label for="radioSimplified">
							<div class=" d-flex align-items-center">
								<svg xmlns="http://www.w3.org/2000/svg" width="29" height="27" viewBox="0 0 29 27">
									<title><?php esc_html_e( 'Simplified', 'iubenda' ); ?></title>
									<g fill="none" fill-rule="evenodd" transform="translate(1 1)">
										<rect width="25" height="11" stroke="currentColor" rx="5.5"/>
										<rect width="25" height="11" y="14" fill="currentColor" stroke="currentColor" rx="5.5"/>
										<rect width="7" height="7" x="2" y="2" fill="currentColor" rx="3.5"/>
										<rect width="7" height="7" x="16" y="16" fill="#FFF" rx="3.5"/>
										<rect width="25" height="11" x="3" y="2" fill="currentColor" fill-opacity=".119" rx="5.5"/>
									</g>
								</svg>
								<span href="#tab-3" class="ml-2"><?php esc_html_e( 'Simplified', 'iubenda' ); ?></span>
							</div>
						</label>
					</div>
					<?php
				endif;

				$_status = '';
				if ( 'manual' === (string) iub_array_get( iubenda()->options['cs'], 'configuration_type' ) ) {
					$_status = 'active';
				}
				?>
				<div class="m-1 mr-2 tabs__nav__item <?php echo esc_attr( $_status ); ?>" data-target="configuration-type-manual-tab" data-group="configuration-type">
					<input type="radio" id="radioManual" class="section-radio-control cs-configuration-type" name="iubenda_cookie_law_solution[configuration_type]" value="manual" <?php checked( 'manual', iub_array_get( iubenda()->options['cs'], 'configuration_type' ) ); ?>>
					<label for="radioManual">
						<div class="d-flex align-items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25">
								<title><?php esc_html_e( 'Manual embed', 'iubenda' ); ?></title>
								<g fill="none" fill-rule="evenodd" transform="translate(1 1)">
									<rect width="21" height="21" x="3" y="3" fill="currentColor" fill-opacity=".119" rx="2"/>
									<rect width="21" height="21" stroke="currentColor" rx="2"/>
									<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M5.818 6.406l5.657 4-5.657 4M11.5 14.5h4"/>
								</g>
							</svg>
							<span href="#tab-4" class="ml-2"><?php esc_html_e( 'Manual embed', 'iubenda' ); ?></span>
						</div>
					</label>
				</div>

			</div>
		</fieldset>
	</div>
	<div class="my-4 subOptions">
		<?php
		if ( $site_id ) {
			$_status = '';
			if ( $is_cs_simplified ) {
				$_status = 'active';
			}
			?>
			<section class="tabs__target <?php echo esc_attr( $_status ); ?>" data-target="configuration-type-simplified-tab" data-group="configuration-type">
			<?php
				// Including partial cs-simplified-configuration.
				require_once IUBENDA_PLUGIN_PATH . 'views/partials/cs-simplified-configuration.php';
			?>
			</section>
			<?php
		}

		$_status = '';
		if ( 'manual' === (string) iub_array_get( iubenda()->options['cs'], 'configuration_type' ) ) {
			$_status = 'active';
		}
		?>
		<section class="tabs__target <?php echo esc_attr( $_status ); ?>" data-target="configuration-type-manual-tab" data-group="configuration-type">
			<?php
			// Including partial cs-manual-configuration.
			require_once IUBENDA_PLUGIN_PATH . 'views/partials/cs-manual-configuration.php';
			?>
		</section>
	</div>
</div>

<?php
// Including partial cs-simplified-configuration.
require_once IUBENDA_PLUGIN_PATH . '/views/partials/auto-block-section.php';
?>

<div class="d-flex align-items-center pt-3">
	<label class="checkbox-regular">
		<input type="checkbox" class="mr-2 section-checkbox-control" name="iubenda_cookie_law_solution[amp_support]" value="1" <?php checked( true, (bool) iubenda()->options['cs']['amp_support'] ); ?> data-section-name="#amp_support"/>
		<span><?php esc_html_e( 'Enable Google AMP support', 'iubenda' ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['enable_amp_support'] ); ?>" class="ml-1 tooltip-icon">?</a></span>
	</label>
</div>
<?php
$_status = '';
if ( ! (bool) iubenda()->options['cs']['amp_support'] ) {
	$_status = 'hidden';
}
?>
<section id="amp_support" class="subOptions my-2 <?php echo esc_attr( $_status ); ?>">
	<h4><?php esc_html_e( 'Select the iubenda AMP configuration file location.', 'iubenda' ); ?></h4>
	<div class="mb-2 d-flex flex-wrap align-items-center">
		<label class="radio-regular mb-3 mr-4">
			<input type="radio" name="iubenda_cookie_law_solution[amp_source]" value="local" class="mr-2 section-radio-control" data-section-name="#auto_generated_conf_file" data-section-group=".amp_configuration_file" <?php checked( 'local', iub_array_get( iubenda()->options['cs'], 'amp_source' ) ); ?>>
			<span><?php esc_html_e( 'Auto-generated configuration file', 'iubenda' ); ?></span>
		</label>
		<label class="mr-4 mb-3 radio-regular text-xs">
			<input type="radio" name="iubenda_cookie_law_solution[amp_source]" value="remote" class="mr-2 section-radio-control" data-section-name="#custom_conf_file" data-section-group=".amp_configuration_file" <?php checked( 'remote', iub_array_get( iubenda()->options['cs'], 'amp_source' ) ); ?>>
			<span><?php esc_html_e( 'Custom configuration file', 'iubenda' ); ?></span>
		</label>
	</div>
	<?php
	$_status = '';
	if ( 'local' !== (string) iub_array_get( iubenda()->options['cs'], 'amp_source' ) ) {
		$_status = 'hidden';
	}
	?>
	<section id="auto_generated_conf_file" class="text-xs text-gray amp_configuration_file <?php echo esc_attr( $_status ); ?>">
		<div class="border-1 border-gray rounded mt-2 py-2 px-3 d-flex flex-wrap align-items-center">
			<?php
			// Including partial amp-files-section.
			require_once IUBENDA_PLUGIN_PATH . '/views/partials/amp-template-links.php';
			?>
		</div>

		<div class="notice notice--general mt-2 p-3 d-flex align-items-center text-xs">
			<p><?php esc_html_e( 'Seeing the AMP cookie notice when testing from Google but not when visiting your AMP pages directly?', 'iubenda' ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['amp_support'] ); ?>" class="link-underline"><?php esc_html_e( 'Learn how to fix it', 'iubenda' ); ?></a></p>
		</div>

	</section>
	<?php
	$_status = '';
	if ( 'remote' !== (string) iub_array_get( iubenda()->options['cs'], 'amp_source' ) ) {
		$_status = 'hidden';
	}
	?>
	<section id="custom_conf_file" class="text-xs text-gray amp_configuration_file <?php echo esc_attr( $_status ); ?>">
		<table class="table">
			<tbody>
			<?php
			$languages = ( new Product_Helper() )->get_languages();
			foreach ( $languages as $lang_id => $lang_name ) {
				?>
				<tr>
					<td><label class="text-bold" for="iub_amp_template-<?php echo esc_attr( $lang_id ); ?>"><?php echo esc_html( $lang_name ); ?></label></td>
					<td><input id="iub_amp_template-<?php echo esc_attr( $lang_id ); ?>" type="text" class="regular-text" name="iubenda_cookie_law_solution[amp_template][<?php echo esc_attr( $lang_id ); ?>]" value="<?php echo esc_attr( iub_array_get( iubenda()->options['cs'], "amp_template.{$lang_id}" ) ); ?>"/></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</section>

</section>

<div class="d-flex align-items-center pt-3">
	<label class="checkbox-regular">
		<input type="checkbox" name="iubenda_cookie_law_solution[parse]" value="1" class="mr-2 section-checkbox-control blocking-method native-blocking-method" data-section-name="#iub_parser_engine_container" <?php checked( true, (bool) iubenda()->options['cs']['parse'] ); ?>>
		<span><?php esc_html_e( 'Native Blocking', 'iubenda' ); ?> <a target="_blank" href="<?php echo esc_url( iubenda()->settings->links['automatic_block_scripts'] ); ?>" class="ml-1 tooltip-icon">?</a></span>
	</label>
</div>
<?php
$_status = '';
if ( ! (bool) iubenda()->options['cs']['parse'] ) {
	$_status = 'hidden';
}

if ( ! can_use_dom_document_class() ) {
	$current_parser = 'default';
} else {
	$current_parser = iubenda()->options['cs']['parser_engine'];
}
?>
<section id="iub_parser_engine_container" class="subOptions <?php echo esc_attr( $_status ); ?>">
	<h4><?php esc_html_e( 'Select Parsing Engine', 'iubenda' ); ?></h4>
	<div class="mb-3 d-flex flex-wrap align-items-center">
		<label class="radio-regular mr-4 mb-3">
			<input <?php echo esc_attr( ! can_use_dom_document_class() ? 'disabled' : '' ); ?> type="radio" name="iubenda_cookie_law_solution[parser_engine]" value="new" class="mr-2 section-radio-control" <?php checked( 'new', $current_parser ); ?>>
			<span><?php esc_html_e( 'Primary', 'iubenda' ); ?></span>
		</label>
		<label class="mr-4 mb-3 radio-regular text-xs">
			<input type="radio" name="iubenda_cookie_law_solution[parser_engine]" value="default" class="mr-2 section-radio-control" <?php checked( 'default', $current_parser ); ?>>
			<span><?php esc_html_e( 'Secondary', 'iubenda' ); ?></span>
		</label>
		<?php if ( ! can_use_dom_document_class() ) : ?>
			<div class="notice notice--warning mt-2 p-3 d-flex align-items-center text-warning text-xs">
				<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
				<p><?php echo wp_kses_post( ( __( "You won't be able to use the Primary engine since you don't have the <span class='text-bold'>PHP XML</span> extension", 'iubenda' ) ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<div class="mb-2 d-flex flex-wrap align-items-center">
		<label class="checkbox-regular">
			<input type="checkbox" name="iubenda_cookie_law_solution[skip_parsing]" value="1" class="mr-2 section-checkbox-control" data-section-name="#section-block-script" <?php checked( true, (bool) iubenda()->options['cs']['skip_parsing'] ); ?>>
			<div class="px-0 py-1">
				<span class="p-0"><?php esc_html_e( 'Leave scripts untouched on the page if the user has already given consent', 'iubenda' ); ?></span>
				<div class="notice notice--info mt-2 mb-3 p-3 d-flex align-items-center text-xs">
					<p>
						<?php echo wp_kses_post( ( __( "Enable this option to improve performance <strong>only</strong> if your site does <strong>not</strong> use a cache system or a cache plugin and if you're <strong>not</strong> collecting per-category consent. If you're in doubt, keep this setting disabled", 'iubenda' ) ) ); ?>
					</p>
				</div>
			</div>
		</label>

	</div>
	<div class="mb-2 d-flex flex-wrap align-items-center">
		<label class="checkbox-regular">
			<input type="checkbox" name="iubenda_cookie_law_solution[block_gtm]" value="1" class="mr-2 section-checkbox-control" <?php checked( true, (bool) iubenda()->options['cs']['block_gtm'] ); ?>>
			<div class="px-0 py-1">
				<span class="p-0"><?php esc_html_e( 'Block Google Tag Manager', 'iubenda' ); ?></span>
				<div class="notice notice--info mt-2 mb-3 p-3 d-flex align-items-center text-xs">
					<p>
					<?php
						/* translators: %s: Google tag manager blocking documentation URL. */
						echo wp_kses_post( sprintf( __( 'Enable this option to prevent Google Tag Manager from running. If, on the contrary, you would like to learn how to use Google Tag Manager to simplify the blocking of cookies, <a class="link-underline" target="_blank" href="%s">read our dedicated guide</a>', 'iubenda' ), esc_url( iubenda()->settings->links['google_tag_manager_blocking'] ) ) );
					?>
					</p>
				</div>
			</div>
		</label>
	</div>

	<h4><?php esc_html_e( 'Blocked domains', 'iubenda' ); ?></h4>
	<fieldset class="custom-scripts mb-3 p-0 tabs tabs--style2">
		<ul class="tabs__nav text-xs">
			<li data-target="tab-custom-scripts" data-group="custom-scripts" class="tabs__nav__item active"><?php esc_html_e( 'Custom scripts', 'iubenda' ); ?></li>
			<li data-target="tab-custom-iframes" data-group="custom-scripts" class="tabs__nav__item"><?php esc_html_e( 'Custom iframes', 'iubenda' ); ?></li>
		</ul>
		<div data-target="tab-custom-scripts" data-group="custom-scripts" class="tabs__target p-3 active">
			<section id="custom-script-field" class="custom-script-field hidden">
				<input type="text" class="regular-text" name="iubenda_cookie_law_solution[custom_scripts][script][]" placeholder="<?php esc_html_e( 'Enter custom script', 'iubenda' ); ?>" disabled>
				<select name="iubenda_cookie_law_solution[custom_scripts][type][]" disabled>
					<option value="0" selected="selected"><?php esc_html_e( 'Not set', 'iubenda' ); ?></option>
					<option value="1"><?php esc_html_e( 'Strictly necessary', 'iubenda' ); ?></option>
					<option value="2"><?php esc_html_e( 'Basic interactions &amp; functionalities', 'iubenda' ); ?></option>
					<option value="3"><?php esc_html_e( 'Experience enhancement', 'iubenda' ); ?></option>
					<option value="4"><?php esc_html_e( 'Analytics', 'iubenda' ); ?></option>
					<option value="5"><?php esc_html_e( 'Targeting &amp; Advertising', 'iubenda' ); ?></option>
				</select>
				<a target="_blank" href="javascript:void(0)" class="remove-custom-script-field button-secondary remove-custom-section" data-remove-section=".custom-script-field" title="Remove">-</a>
			</section>
			<div id="custom-script-fields">
				<?php
				foreach ( iubenda()->options['cs']['custom_scripts'] as $custom_script => $custom_script_type ) {
					?>
					<section class="custom-script-field">
						<input type="text" class="regular-text" name="iubenda_cookie_law_solution[custom_scripts][script][]" placeholder="<?php esc_html_e( 'Enter custom script', 'iubenda' ); ?>" value="<?php echo esc_attr( stripslashes( $custom_script ) ); ?>">
						<select name="iubenda_cookie_law_solution[custom_scripts][type][]">
							<option value="0" <?php selected( $custom_script_type, 0 ); ?>><?php esc_html_e( 'Not set', 'iubenda' ); ?></option>
							<option value="1" <?php selected( $custom_script_type, 1 ); ?>><?php esc_html_e( 'Strictly necessary', 'iubenda' ); ?></option>
							<option value="2" <?php selected( $custom_script_type, 2 ); ?>><?php esc_html_e( 'Basic interactions &amp; functionalities', 'iubenda' ); ?></option>
							<option value="3" <?php selected( $custom_script_type, 3 ); ?>><?php esc_html_e( 'Experience enhancement', 'iubenda' ); ?></option>
							<option value="4" <?php selected( $custom_script_type, 4 ); ?>><?php esc_html_e( 'Analytics', 'iubenda' ); ?></option>
							<option value="5" <?php selected( $custom_script_type, 5 ); ?>><?php esc_html_e( 'Targeting &amp; Advertising', 'iubenda' ); ?></option>
						</select>
						<a target="_blank" href="javascript:void(0)" class="remove-custom-script-field button-secondary remove-custom-section" data-remove-section=".custom-script-field" title="Remove">-</a>
					</section>
				<?php } ?>
			</div>

			<p class=" text-gray-lighter m-0 mb-3"><?php esc_html_e( "Provide a list of domains for any custom scripts you'd like to block, and assign their purposes. To make sure they are blocked correctly, please add domains in the same format as 'example.com', without any protocols e.g. 'http://' or 'https://'. You may also use wildcards (*) to include parent domains or subdomains.", 'iubenda' ); ?></p>
			<button class="btn btn-gray-outline btn-xs add-custom-section" data-append-section="#custom-script-fields" data-clone-section="#custom-script-field"><?php esc_html_e( 'Add New Script', 'iubenda' ); ?></button>
		</div>
		<div data-target="tab-custom-iframes" data-group="custom-scripts" class="tabs__target p-3">
			<section id="custom-iframe-field" class="custom-iframe-field hidden">
				<input type="text" class="regular-text" name="iubenda_cookie_law_solution[custom_iframes][iframe][]" placeholder="<?php esc_html_e( 'Enter custom iframe', 'iubenda' ); ?>" disabled>
				<select name="iubenda_cookie_law_solution[custom_iframes][type][]" disabled>
					<option value="0" selected="selected"><?php esc_html_e( 'Not set', 'iubenda' ); ?></option>
					<option value="1"><?php esc_html_e( 'Strictly necessary', 'iubenda' ); ?></option>
					<option value="2"><?php esc_html_e( 'Basic interactions &amp; functionalities', 'iubenda' ); ?></option>
					<option value="3"><?php esc_html_e( 'Experience enhancement', 'iubenda' ); ?></option>
					<option value="4"><?php esc_html_e( 'Analytics', 'iubenda' ); ?></option>
					<option value="5"><?php esc_html_e( 'Targeting &amp; Advertising', 'iubenda' ); ?></option>
				</select>
				<a target="_blank" href="javascript:void(0)" class="remove-custom-iframe-field button-secondary remove-custom-section" data-remove-section=".custom-iframe-field" title="Remove">-</a>
			</section>
			<div id="custom-iframe-fields">
				<?php
				foreach ( iub_array_get( iubenda()->options['cs'], 'custom_iframes' ) as $custom_iframe => $custom_iframe_type ) {
					?>
					<section id="custom-iframe-field" class="custom-iframe-field">
						<input type="text" class="regular-text" name="iubenda_cookie_law_solution[custom_iframes][iframe][]" placeholder="<?php esc_html_e( 'Enter custom iframe', 'iubenda' ); ?>" value='<?php echo esc_attr( stripslashes( $custom_iframe ) ); ?>'>
						<select name="iubenda_cookie_law_solution[custom_iframes][type][]">
							<option value="0" <?php selected( $custom_iframe_type, 0 ); ?>><?php esc_html_e( 'Not set', 'iubenda' ); ?></option>
							<option value="1" <?php selected( $custom_iframe_type, 1 ); ?>><?php esc_html_e( 'Strictly necessary', 'iubenda' ); ?></option>
							<option value="2" <?php selected( $custom_iframe_type, 2 ); ?>><?php esc_html_e( 'Basic interactions &amp; functionalities', 'iubenda' ); ?></option>
							<option value="3" <?php selected( $custom_iframe_type, 3 ); ?>><?php esc_html_e( 'Experience enhancement', 'iubenda' ); ?></option>
							<option value="4" <?php selected( $custom_iframe_type, 4 ); ?>><?php esc_html_e( 'Analytics', 'iubenda' ); ?></option>
							<option value="5" <?php selected( $custom_iframe_type, 5 ); ?>><?php esc_html_e( 'Targeting &amp; Advertising', 'iubenda' ); ?></option>
						</select>
						<a target="_blank" href="javascript:void(0)" class="remove-custom-iframe-field button-secondary remove-custom-section" data-remove-section=".custom-iframe-field" title="Remove">-</a>
					</section>
				<?php } ?>

			</div>
			<p class="text-gray-lighter m-0 mb-3"><?php esc_html_e( "Provide a list of domains for any custom iframes you'd like to block, and assign their purposes. To make sure they are blocked correctly, please add domains in the same format as 'example.com', without any protocols e.g. 'http://' or 'https://'. You may also use wildcards (*) to include parent domains or subdomains.", 'iubenda' ); ?></p>
			<button class="btn btn-gray-outline btn-xs add-custom-section" data-append-section="#custom-iframe-fields" data-clone-section="#custom-iframe-field"><?php esc_html_e( 'Add New iframe', 'iubenda' ); ?></button>
		</div>
	</fieldset>
</section>
<div id="both-blocking-methods-disabled-warning-message" class="mxx-4 mb-4 notice notice--warning mt-2 p-3 align-items-center text-warning text-xs d-flex <?php echo iubenda()->options['cs']['parse'] ? 'd-flex' : ''; ?>">
	<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
	<p>
		<?php esc_html_e( 'Most legislation explicitly require prior consent in order to process user’s data. By disabling these blocking options you may be in breach of such requirements', 'iubenda' ); ?>
	</p>
</div>
<div class="d-flex align-items-center pt-3">
	<label class="checkbox-regular">
		<input type="checkbox" class="mr-2" name="iubenda_cookie_law_solution[stop_showing_cs_for_admins]" value="1" <?php checked( true, (bool) iubenda()->options['cs']['stop_showing_cs_for_admins'] ); ?>>
		<span><?php esc_html_e( 'Do not show Cookie Banner for admin users (recommended)', 'iubenda' ); ?></span>
	</label>
</div>
<div class="pt-3">

</div>
