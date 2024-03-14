<?php
/**
 * Simple_Page_Access_Restriction deactivation Content.
 * @package Simple_Page_Access_Restriction
 * @version 1.0.0
 */
	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ps_simple_par_deactivation_nonce = wp_create_nonce( 'ps_simple_par_deactivation_nonce' ); 
?>

<div class="simple-par-popup-overlay">
	<div class="simple-par-serveypanel">
		<form action="#" method="post" id="simple-par-deactivate-form">
			<div class="simple-par-popup-header">
				<h2><?php _e( 'Quick feedback about ' . SIMPLE_PAGE_ACCESS_RESTRICTION_NAME, 'simple-page-access-restriction' ); ?></h2>
			</div>
			<div class="simple-par-popup-body">
				<h3><?php _e( 'If you have a moment, please let us know why you are deactivating:', 'simple-page-access-restriction' ); ?></h3>
				<input type="hidden" class="ps_simple_par_deactivation_nonce" name="ps_simple_par_deactivation_nonce" value="<?php esc_attr_e( $ps_simple_par_deactivation_nonce ); ?>">
				<ul id="simple-par-reason-list">
					<li class="simple-par-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="simple-par-selected-reason" value="1">
							</span>
							<span class="reason_text"><?php _e( 'I only needed the plugin for a short period', 'simple-page-access-restriction' ); ?></span>
						</label>
						<div class="simple-par-internal-message"></div>
					</li>
					<li class="simple-par-reason has-input" data-input-type="textfield">
						<label>
							<span>
								<input type="radio" name="simple-par-selected-reason" value="2">
							</span>
							<span class="reason_text"><?php _e( 'I found a better plugin', 'simple-page-access-restriction' ); ?></span>
						</label>
						<div class="simple-par-internal-message"></div>
						<div class="simple-par-reason-input"><span class="message error-message "><?php _e( 'Kindly tell us the Plugin name.', 'simple-page-access-restriction' ); ?></span><input type="text" name="better_plugin" placeholder="What's the plugin's name?"></div>
					</li>
					<li class="simple-par-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="simple-par-selected-reason" value="3">
							</span>
							<span class="reason_text"><?php _e( 'The plugin broke my site', 'simple-page-access-restriction' ); ?></span>
						</label>
						<div class="simple-par-internal-message"></div>
					</li>
					<li class="simple-par-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="simple-par-selected-reason" value="4">
							</span>
							<span class="reason_text"><?php _e( 'The plugin suddenly stopped working', 'simple-page-access-restriction' ); ?></span>
						</label>
						<div class="simple-par-internal-message"></div>
					</li>
					<li class="simple-par-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
							<input type="radio" name="simple-par-selected-reason" value="5">
							</span>
							<span class="reason_text"><?php _e( 'I no longer need the plugin', 'simple-page-access-restriction' ); ?></span>
						</label>
						<div class="simple-par-internal-message"></div>
					</li>
					<li class="simple-par-reason" data-input-type="" data-input-placeholder="">
						<label>
							<span>
								<input type="radio" name="simple-par-selected-reason" value="6">
							</span>
							<span class="reason_text"><?php _e( "It's a temporary deactivation. I'm just debugging an issue.", 'simple-page-access-restriction' ); ?></span>
						</label>
						<div class="simple-par-internal-message"></div>
					</li>
					<li class="simple-par-reason has-input" data-input-type="textfield" >
						<label>
							<span>
								<input type="radio" name="simple-par-selected-reason" value="7">
							</span>
							<span class="reason_text"><?php _e( 'Other', 'simple-page-access-restriction' ); ?></span>
						</label>
						<div class="simple-par-internal-message"></div>
						<div class="simple-par-reason-input"><span class="message error-message "><?php _e( 'Kindly tell us the reason so we can improve.', 'simple-page-access-restriction' ); ?></span><input type="text" name="other_reason" placeholder="Kindly tell us the reason so we can improve."></div>
					</li>
				</ul>
			</div>
			<div class="simple-par-popup-footer">
				<label class="simple-par-anonymous"><input type="checkbox" /><?php _e( 'Anonymous feedback', 'simple-page-access-restriction' ); ?></label>
				<input type="button" class="button button-secondary button-skip simple-par-popup-skip-feedback" value="<?php _e( 'Skip & Deactivate', 'simple-page-access-restriction'); ?>" >
				<div class="action-btns">
					<span class="simple-par-spinner"><img src="<?php echo admin_url( '/images/spinner.gif' ); ?>" alt=""></span>
					<input type="submit" class="button button-secondary button-deactivate simple-par-popup-allow-deactivate" value="<?php _e( 'Submit & Deactivate', 'simple-page-access-restriction'); ?>" disabled="disabled">
					<a href="#" class="button button-primary simple-par-popup-button-close"><?php _e( 'Cancel', 'simple-page-access-restriction' ); ?></a>
				</div>
			</div>
		</form>
	</div>
</div>
