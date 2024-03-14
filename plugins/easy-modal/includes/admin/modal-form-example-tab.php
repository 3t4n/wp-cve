<?php
add_filter('emodal_admin_modal_form_tabs', 'emodal_admin_modal_form_examples_tab', 100);
function emodal_admin_modal_form_examples_tab($tabs)
{
	$tabs[] = array( 'id' => 'examples', 'label' => __('Examples', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_modal_form_tab_examples', 'emodal_admin_modal_form_examples_tab_settings', 30);
function emodal_admin_modal_form_examples_tab_settings()
{
	$modal = get_current_modal();
	?><h4>
		<?php _e('Copy this class to the link/button you want to open this modal.', 'easy-modal' )?>
		<span class="desc">eModal-<?php esc_html_e($modal->id)?></span>
	</h4>
	<div class="tab-box">
		<h4><?php _e('Link Example', 'easy-modal' )?></h4>
		<a href="#" onclick="return false;" class="eModal-<?php esc_html_e($modal->id)?>"><?php _e('Open Modal', 'easy-modal' )?></a>
		<pre>&lt;a href="#" class="eModal-<?php esc_html_e($modal->id)?>"><?php _e('Open Modal', 'easy-modal' )?>&lt;/a></pre>
	</div>
	<div class="tab-box">
		<h4><?php _e('Button Example', 'easy-modal' )?></h4>
		<button onclick="return false;" class="eModal-<?php esc_html_e($modal->id)?>"><?php _e('Open Modal', 'easy-modal' )?></button>
		<pre>&lt;button class="eModal-<?php esc_html_e($modal->id)?>"><?php _e('Open Modal', 'easy-modal' )?>&lt;/button></pre>
	</div>
	<div class="tab-box">
		<h4><?php _e('Image Example', 'easy-modal' )?></h4>
		<img style="cursor:pointer;" src="<?php echo EMCORE_URL?>/assets/images/admin/easy-modal-icon.png" onclick="return false;" class="eModal-<?php esc_html_e($modal->id)?>" />
		<pre>&lt;img src="easy-modal-icon.png" class="eModal-<?php esc_html_e($modal->id)?>" /></pre>
	</div><?php
}