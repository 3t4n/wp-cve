<?php
/**
 * 2FA Verification Template, which includes inner modal HTML and is shown when a user selects a verification method and verifies it.
 *
 * @version 1.0.0
 * @package Login With Ajax
 */
?>
<?php do_action('lwa_2FA_form_header'); ?>
<div class="lwa-modal-content lwa-wrapper lwa-bones">
	<div class="lwa pixelbones">
		<?php include(LoginWithAjax::locate_template('2FA/form.php')); ?>
	</div>
</div><!-- content -->
<?php do_action('lwa_2FA_form_footer'); ?>