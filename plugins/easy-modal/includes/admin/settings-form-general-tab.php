<?php
add_filter('emodal_admin_settings_form_tabs', 'emodal_admin_settings_general_tab', 10);
function emodal_admin_settings_general_tab($tabs)
{
	$tabs[] = array( 'id' => 'general', 'label' => __('General', 'easy-modal' ) );
	return $tabs;
}

add_action('emodal_admin_settings_form_tab_general', 'emodal_admin_settings_form_general_tab', 20);
function emodal_admin_settings_form_general_tab()
{
	?><table class="form-table">
		<tbody>
			<?php do_action('emodal_admin_settings_form_tab_general_settings');?>
		</tbody>
	</table><?php
}


add_action('emodal_admin_settings_form_tab_general_settings', 'emodal_admin_settings_form_general_tab_license', 10);
function emodal_admin_settings_form_general_tab_license()
{?>
<!--
	<tr class="form-field">
		<th scope="row">
			<label for="license_key"><?php _e('License Key', 'easy-modal' );?></label>
		</th>
		<td>
			<input type="<?php echo emodal_get_license('key') ? 'password' : 'text'?>" id="license_key" name="license[key]" value="<?php esc_attr_e(emodal_get_license('key'))?>" class="regular-text"/>
			<p class="description"><?php _e( emodal_get_license('status.message') ? emodal_get_license('status.message') : 'Enter a key to unlock Easy Modal Pro.', 'easy-modal' )?></p>
			<?php if(emodal_get_license('status.valid')){?>
			<p class="description expires"><?php echo '<strong>'.__('Expiration Date: ' , 'easy-modal' ) .'</strong>'. emodal_get_license('status.expires');?></p>
			<p class="description domains"><?php echo '<strong>'.__('Domains Using this License: ' , 'easy-modal' ) .'</strong>'. emodal_get_license('status.domains');?></p>
 			<?php }?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php 
			$box_src = EMCORE_URL.'/assets/images/admin/box-shot';
			if(emodal_get_license('status.valid') && emodal_get_license('status.license_type') > 0)
			{
				if(intval(emodal_get_license('status.license_type')) === 2)
				{
					$box_src .= '-developer';
				}
				elseif(intval(emodal_get_license('status.license_type')) === 1)
				{
					$box_src .= '-pro';
				}
			}?>
			<img style="max-width:623px;width:100%;" src="<?php esc_attr_e($box_src.'.jpg');?>"/>
		</td>
	</tr>-->
	<?php if(1==0 && emodal_get_option(EMCORE_SLUG.'_migration_approval')) : ?>
	<tr class="form-field">
		<th scope="row">
			<label><?php _e('Approve Migration', 'easy-modal' );?></label>
		</th>
		<td>
			<button type="submit" name="remove_old_emodal_data">Aprove</button>
			<p class="description"><?php _e('Click this if you are sure your modals, themes and settings imported successfully.' , 'easy-modal' )?></p>
		</td>
	</tr>
	<?php endif; ?>
	<tr class="form-field">
		<th scope="row">
			<label><?php _e('Reset Easy Modal Database', 'easy-modal' );?></label>
		</th>
		<td>
			<button type="submit" name="reset_emodal_db">Reset</button>
			<p class="description"><?php _e('Use this to reset the database and remove all modals.' , 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row">
			<label><?php _e('Import Old Easy Modal Settings', 'easy-modal' );?></label>
		</th>
		<td>
			<button type="submit" name="migrate_emodal_db">Import</button>
			<p class="description"><?php _e('Use this to import your modals and themes from your older version of easy modal.' , 'easy-modal' )?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row">
			<label><?php _e('Uninstall Easy Modal Settings', 'easy-modal' );?></label>
		</th>
		<td>
			<button type="submit" name="uninstall_emodal_db">Uninstall</button>
			<p class="description"><?php _e('Use this to reset the database and remove all modals, themes and database tables.' , 'easy-modal' )?></p>
		</td>
	</tr><?php
}