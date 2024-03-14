<?php
/**
 * Select language - global - partial modal page.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="modalSync">
	<img src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/modals/modal_sync.svg" alt=""/>
	<h1 class="text-lg mb-4">
		<?php esc_html_e( 'Now, select your website language', 'iubenda' ); ?>
	</h1>
	<div>
		<?php
		$language_helper                   = new Language_Helper();
		$default_website_language          = $language_helper->get_default_website_language_code( true );
		$iubenda_intersect_supported_langs = $language_helper->get_local_supported_language();
		// Fallback to EN if no supported local languages intersect with iubenda supported languages.
		if ( empty( $iubenda_intersect_supported_langs ) ) {
			$iubenda_intersect_supported_langs[] = 'en';
		}

		// Unify intersect (iubenda with local) supported languages by STR to Lower function.
		$iubenda_intersect_supported_langs = array_map( 'strtolower', $iubenda_intersect_supported_langs );
		?>
		<select id="iub-website-language" name="website-language" required>
			<?php foreach ( iubenda()->supported_languages as $key => $label ) : ?>

				<option <?php selected( strtolower( $default_website_language ) === strtolower( $key ) ); ?> <?php disabled( ! in_array( strtolower( $key ), $iubenda_intersect_supported_langs, true ) ); ?> value=<?php echo esc_attr( $key ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php if ( empty( $language_helper->get_local_supported_language() ) ) : ?>
		<div class="notice notice--warning mt-2 mb-4 p-3 d-flex align-items-center text-warning text-xs">
			<img class="mr-2" src="<?php echo esc_url( IUBENDA_PLUGIN_URL ); ?>/assets/images/warning-icon.svg">
			<p class="text-left"><?php esc_html_e( 'In cases where the language(s) available on your site is not one of the languages currently supported by iubenda, your policy documents will be displayed in English by default.', 'iubenda' ); ?></p>
		</div>
	<?php endif; ?>
	<br>
	<div id="iubenda-policy-config-start"></div>
</div>
<script>
	var _iub = _iub || [];

	_iub.quick_generator = {
		input: {
			privacy_policy: {
				type: 'web_site',
				cookie_solution: true,
				url: '<?php echo esc_url( get_site_url() ); ?>',
				langs: ['<?php echo esc_attr( $default_website_language ); ?>']
			},
			user: {
				email: '<?php echo esc_attr( get_bloginfo( 'admin_email' ) ); ?>',
			},
		},
		no_style: true,
		css: "background-color:#e7e7e7;cursor:pointer;color: #585858;padding: 0.5rem 1.7rem;font-size: .95rem;border: 0;border-radius: 3rem;font-weight: bold;width: 100%;",
		placeholder: document.getElementById("iubenda-policy-config-start"),
		callback: iubendaQuickGeneratorCallback,
		api_key: "<?php echo esc_html( iubenda()->settings->iub_qg_api_key ); ?>",
		language: "<?php echo esc_html( $language_helper->get_user_profile_language_code( true ) ); ?>",
		caption: "<?php esc_html_e( 'Continue', 'iubenda' ); ?>"
	};

	function iubendaQuickGeneratorCallback(payload) {
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: iub_js_vars['site_url'] + "/wp-admin/admin-ajax.php",
			data: {
				action: "quick_generator_api",
				payload: payload,
				iub_nonce: iub_js_vars['iub_quick_generator_callback_nonce']
			},
			success: function (result) {
				if (result.status === 'done') {
					window.location = result.redirect
				} else {

				}
			},
			error: function (response) {
				// if error occurred.
				jQuery("#alert-div").addClass("alert--failure");
				jQuery("#alert-image").attr('src', iub_js_vars['plugin_url'] + '/assets/images/banner_failure.svg')
				jQuery("#alert-message").text(response.responseText);

				jQuery("#alert-div").removeClass("hidden");
			},
		});
	}

</script>
