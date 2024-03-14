<?php

$arf_convertkit = new arflite_convertkit();
class arflite_convertkit {

	function __construct() {
		add_action( 'arflite_autoresponder_global_setting_block', array( $this, 'arflite_add_convertkit_global_setting_block' ) );
	}

	function arflite_add_convertkit_global_setting_block() {
		global $wpdb, $ARFLiteMdlDb;

		?>
			<table class="wp-list-table widefat post arflite-email-marketer-tbl3">

				<tr>
					<th class="email-marketer-img-th" width="18%">&nbsp;</th>
					<th class="email-marketer-img-wrapth" colspan="2"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/convertkit.png" align="absmiddle" height='40px' /></th>
				</tr>

				<tr>
					<th class="email-marketer-img-th"></th>
					<th id="th_convertkit" class="arf-email-marketer-radioth">
						<div class="arf_radio_wrapper">
							<div class="arf_custom_radio_div" >
								<div class="arf_custom_radio_wrapper">
									<input type="radio" class="arf_submit_action arf_custom_radio" id="convertkit_17" checked="checked" name="convertkit_type" value="1" />
									<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
									</svg>
								</div>
							</div>
							<span>
								<label for="convertkit_17"><?php echo esc_html__( 'Using API', 'arforms-form-builder' ); ?></label>
							</span>
						</div>
					</th>

				</tr>

				<tr id="convertkit_api_tr1" class="display-none-cls">

					<td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'API Key', 'arforms-form-builder' ); ?></label></td>

					<td class="arfemailinputtd"><input type="text" name="convertkit_api" class="txtmodal1" id="convertkit_api" size="80" onkeyup="show_verify_btn('convertkit');" value="" /> &nbsp; &nbsp;
						
						<div class="arferrmessage display-none-cls" id="convertkit_api_error" ><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>
				</tr>

				<tr id="convertkit_api_tr1">

					<td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'API Secret', 'arforms-form-builder' ); ?></label></td>

					<td class="arfemailinputtd">
						<input type="text" name="convertkit_api_secret" class="txtmodal1" id="convertkit_api_secret" size="80" onkeyup="show_verify_btn('convertkit');" value="<?php echo isset( $convertkit_data->consumer_secret ) ? esc_attr( $convertkit_data->consumer_secret ) : ''; ?>" /> &nbsp; &nbsp;
						<span id="convertkit_link"><a href="javascript:void(0);" class="arlinks arf_restricted_control"><?php echo esc_html__( 'Verify', 'arforms-form-builder' ); ?></a></span>
						<span id="convertkit_loader" class="display-none-cls"><div class="arf_imageloader arfemailmarketerloaderdiv"></div></span>
						<span id="convertkit_verify" class="frm_verify_li display-none-cls"><?php echo esc_html__( 'Verified', 'arforms-form-builder' ); ?></span>
						<span id="convertkit_error" class="frm_not_verify_li display-none-cls"><?php echo esc_html__( 'Not Verified', 'arforms-form-builder' ); ?></span>
						<input type="hidden" name="convertkit_status" id="convertkit_status" />
						<div class="arferrmessage display-none-cls" id="convertkit_api_secret_error" ><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
					</td>
				</tr>


				<tr id="convertkit_api_tr2">
				
					<?php /* added class to remove inline css*/ ?>
					<td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'List Name', 'arforms-form-builder' ); ?></label></td>

					<td class="arfselect-email-marketer-list-td">
						<span id="select_convertkit">
							<div class="sltstandard arfemail_marketer_list_div">
								<input name="convertkit_listid" id="convertkit_listid" value="" type="hidden" class="frm-dropdown frm-pages-dropdown">
								<dl class="arf_selectbox arfemailmar_width400px" data-name="convertkit_listid" data-id="convertkit_listid">
									<dt><span><?php echo esc_html__( 'Nothing Selected', 'arforms-form-builder' ); ?></span>
									<svg viewBox="0 0 2000 1000" width="15px" height="15px">
									<g fill="#000">
									<path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
									</g>
									</svg></dt>
									<dd>
										<ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls" data-id="convertkit_listid">
										</ul>
									</dd>
								</dl>
							</div></span>

						<div id="convertkit_del_link" class="arlinks arfemailmarketer-delref-link-div">
							<a href="javascript:void(0);" onclick="action_autores('refresh', 'convertkit');"><?php echo esc_html__( 'Refresh List', 'arforms-form-builder' ); ?></a>
							&nbsp;  &nbsp;  &nbsp;  &nbsp;
							<a href="javascript:void(0);" onclick="action_autores('delete', 'convertkit');"><?php echo esc_html__( 'Delete Configuration', 'arforms-form-builder' ); ?></a>
						</div>


					</td>

				</tr>

				<tr>
					<td colspan="2" class="arfpadding-left5px"><div class="dotted_line dottedline-width96"></div></td>
				</tr>


			</table>
		<?php
	}
}
?>
