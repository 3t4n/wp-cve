<?php
	global $otw_qtsw_plugin_id;
$message = '';
$massages = array();
$messages[1] = esc_html__( 'Settings saved', 'otw_qtsw' );

	$db_values = array();
	$db_values['otw_cm_promotions'] = get_option( $otw_qtsw_plugin_id.'_dnms' );
	
	if( empty( $db_values['otw_cm_promotions'] ) ){
		$db_values['otw_cm_promotions'] = 'on';
	}


if( otw_get('message',false) && isset( $messages[ otw_get('message','') ] ) ){
	$message .= $messages[ otw_get('message','') ];
}
?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo esc_html( $message ); ?></p></div>
<?php endif; ?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php esc_html_e('Plugin Settings', 'otw_qtsw') ?>
	</h2>
	<div class="form-wrap otw_qtsw_options" id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_qtsw_action" value="otw_qtsw_settings_action" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-qtsw-settings'); ?>
			<div id="post-body">
				<div id="post-body-content">
					<?php include_once( 'otw_qtsw_help.php' );?>
				</div>
				<div class="form-field">
					<label for="otw_cm_promotions"><?php esc_html_e('Show OTW Promotion Messages in my WordPress admin', 'otw_qtsw'); ?></label>
					<select id="otw_cm_promotions" name="otw_cm_promotions">
						<option value="on" <?php echo ( isset( $db_values['otw_cm_promotions'] ) && ( $db_values['otw_cm_promotions'] == 'on' ) )? 'selected="selected"':''?>>on(default)</option>
						<option value="off"<?php echo ( isset( $db_values['otw_cm_promotions'] ) && ( $db_values['otw_cm_promotions'] == 'off' ) )? 'selected="selected"':''?>>off</option>
					</select>
				</div>
				<p class="submit">
					<input type="submit" value="<?php esc_html_e( 'Save Settings', 'otw_qtsw') ?>" name="submit" class="button"/>
				</p>

			</div>
		</form>
	</div>
</div>

