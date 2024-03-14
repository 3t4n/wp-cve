<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php // phpcs:disable WordPress.WP.EnqueuedResources ?>
<?php // - We specifically *WANT* to inline these scripts this way vs enqueueing ?>
<?php // - We do not want WP or any plugins to be modifying these scripts ?>

<!-- --------- START PLUGIN NUTSHELL-ANALYTICS - HEADER SCRIPTS --------- -->

<!-- Nutshell - Primary Tracking Script -->
<script type='text/javascript' data-registered="nutshell-plugin" >
	/* global mcfx */
	(function (w,d,o,a,m) {
		w[o]=w[o]||function(){(w[o].q=w[o].q||[]).push(arguments);
		},w[o].e=1*new Date();a=d.createElement('script'),
		m=d.getElementsByTagName('script')[0];a.async=1;
		a.src='https://agent.marketingcloudfx.com/mcfx.js';m.parentNode.insertBefore(a, m);
	})(window, document, 'mcfx');

	mcfx('create','<?php echo esc_js( $nutshell_instance_id ); ?>');
</script>

<!-- Helper Script -->
<script type='text/javascript' data-registered="nutshell-plugin" >
<?php
	/**
		 * This method will send data as a form lead
		 *
		 * @param data - array of objects with field data, eg:
		 *  [
		 *    { name: 'name', value: 'Bill' },
		 *    { name: 'email', value: 'bill@example.com' }
		 *  ]
		 *
		 * @param formId - id for form to submit
		 *
		 */
?>
	/* global mcfx */
	window.mcfxCaptureCustomFormData = function( data, formId='form-from-mcfxCaptureCustomFormData' ) {
		const formEl = document.createElement('form');
		formEl.id = formId;
		for ( const field of data ) {
			const fieldEl = document.createElement('input');
			fieldEl.type = 'hidden';
			for ( const key in field ) {
				fieldEl[key] = field[key];
			}
			formEl.appendChild(fieldEl);
		}
		mcfx( 'capture', formEl );
	}
</script>

<!-- --------- END PLUGIN NUTSHELL-ANALYTICS - HEADER SCRIPTS --------- -->

<?php // phpcs:enable WordPress.WP.EnqueuedResources ?>

<?php // IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN ?>
