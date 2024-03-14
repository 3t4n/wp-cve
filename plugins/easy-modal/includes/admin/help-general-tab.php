<?php
add_filter('emodal_admin_help_tabs', 'emodal_admin_help_general_tab', 10);
function emodal_admin_help_general_tab($tabs)
{
	$tabs[] = array( 'id' => 'general', 'label' => __('General Usage', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_help_tab_general', 'emodal_admin_help_general_tab_content', 10);
function emodal_admin_help_general_tab_content()
{?>
	<h4><?php _e('Copy the class to the link/button you want to open this modal.', 'easy-modal' );?><span class="desc"><?php _e('Will start with eModal- and end with a # of the modal you want to open.', 'easy-modal' );?></span></h4>
	<div class="tab-box">
		<h4><?php _e('Link Example', 'easy-modal' );?></h4>
		<a href="#" onclick="return false;" class="eModal-1"><?php _e('Open Modal', 'easy-modal' );?></a>
		<pre>&lt;a href="#" class="eModal-1"><?php _e('Open Modal', 'easy-modal' );?>&lt;/a></pre>
	</div>
	<div class="tab-box">
		<h4><?php _e('Button Example', 'easy-modal' );?></h4>
		<button onclick="return false;" class="eModal-1"><?php _e('Open Modal', 'easy-modal' );?></button>
		<pre>&lt;button class="eModal-1"><?php _e('Open Modal', 'easy-modal' );?>&lt;/button></pre>
	</div>
	<div class="tab-box">
		<h4><?php _e('Image Example', 'easy-modal' );?></h4>
		<img style="cursor:pointer;" src="<?php echo EMCORE_URL?>/assets/images/admin/easy-modal-icon.png" onclick="return false;" class="eModal-1" />
		<pre>&lt;img src="easy-modal-icon.png" class="eModal-1" /></pre>
	</div><?php
}