<?php
/*
 * @link http://www.apoyl.com
 * @since 1.0.0
 * @package Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/admin/partials
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$options_name = 'apoyl-baidupush-settings';
if (! empty ( $_POST ['submit'] ) && check_admin_referer ( 'apoyl-baidupush-settings', '_wpnonce' )) {
	isset ( $_POST ['https'] ) ? $https = ( int ) sanitize_key ( $_POST ['https'] ) : $https = 0;
	isset ( $_POST ['autopush'] ) ? $autopush = ( int ) sanitize_key ( $_POST ['autopush'] ) : $autopush = 0;
	$arr_options = array (
			'site' => sanitize_text_field ( $_POST ['site'] ),
			'secret' => sanitize_text_field ( $_POST ['secret'] ),
			'https' => $https,
			'autopush' => $autopush 
	);
	
	$updateflag = update_option ( $options_name, $arr_options );
	$updateflag = true;
}
$arr = get_option($options_name);

?>
<?php if( !empty( $updateflag ) ) { echo '<div id="message" class="updated fade"><p>' . __('updatesuccess','apoyl-baidupush') . '</p></div>'; } ?>

<div class="wrap">
	<h2><?php _e('settings','apoyl-baidupush'); ?></h2>
	<p>
<?php _e('settings_desc','apoyl-baidupush'); ?>
</p>
	<form
		action="<?php echo admin_url('options-general.php?page=apoyl-baidupush-settings');?>"
		name="settings-apoyl-baidupush" method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th><label><?php _e('site','apoyl-baidupush'); ?></label></th>
					<td><input type="text" class="regular-text"
						value="<?php _e($arr['site']); ?>" id="site" name="site">
						<p class="description"><?php _e('site_desc','apoyl-baidupush'); ?></p>
					</td>
				</tr>
				<tr>
					<th><label><?php _e('secret','apoyl-baidupush'); ?></label></th>
					<td><input type="text" class="regular-text"
						value="<?php _e($arr['secret']); ?>" id="secret" name="secret">
						<p class="description"><?php _e('secret_desc','apoyl-baidupush'); ?></p>
					</td>
				</tr>
				<tr>
					<th><label><?php _e('https','apoyl-baidupush'); ?></label></th>
					<td><input type="checkbox" class="regular-text"
						value="1" id="https" name="https" <?php if($arr['https']) _e('checked="checked"'); ?>>
					<?php _e('https_desc','apoyl-baidupush'); ?>
					</td>
				</tr>
				<tr>
					<th><label><?php _e('autopush','apoyl-baidupush'); ?></label></th>
					<td><input type="checkbox" class="regular-text"
						value="1" id="autopush" name="autopush" <?php if($arr['autopush']) _e('checked="checked"'); ?>>
					<?php _e('autopush_desc','apoyl-baidupush'); ?>
					</td>
				</tr>
				<tr><th><label><?php _e('fastpush','apoyl-baidupush'); ?></label></th>
				<td><?php _e('fastpush_desc','apoyl-baidupush'); ?></td></tr>		
			</tbody>
		</table>
            <?php
            wp_nonce_field("apoyl-baidupush-settings");
            submit_button();
            ?>
           
</form>
</div>