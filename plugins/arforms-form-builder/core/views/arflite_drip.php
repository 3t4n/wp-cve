<?php

$arf_drip = new arflite_drip();

class arflite_drip {

	function __construct() {

		add_action( 'arflite_autoresponder_global_setting_block', array( $this, 'arflite_add_drip_global_setting_block' ) );
	}

	function arflite_add_drip_global_setting_block() {

		global $wpdb, $ARFLiteMdlDb;

		?>
				<table class="wp-list-table widefat post arflite-email-marketer-tbl3">

				<tr>
					<th class="email-marketer-img-th" width="18%">&nbsp;</th>
					<th style="background:none; border:none;height:98px;" class="email-marketer-img-wrapth" colspan="2"><img alt='' src="<?php echo esc_url( ARFLITEURL ); ?>/images/drip.png" align="absmiddle"  height='38px'/></th>
				</tr>

				<tr>
					
					<th class="email-marketer-img-th"></th>
					<th id="th_drip" class="arf-email-marketer-radioth">
						<div class="arf_radio_wrapper">
							<div class="arf_custom_radio_div" >
								<div class="arf_custom_radio_wrapper">
									<input type="radio" class="arf_submit_action arf_custom_radio" id="drip_18" checked="checked"  name="drip_type" value="1"  />
									<svg width="18px" height="18px">
									<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
									<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
									</svg>
								</div>
							</div>
							<span>
								<label for="drip_18"><?php echo esc_html__( 'Using API', 'arforms-form-builder' ); ?></label>
							</span>
						</div>
					</th>

				</tr>

				<tr id="drip_api_tr1">

					<td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'API Token', 'arforms-form-builder' ); ?></label></td>

					<td style="padding-bottom:3px; padding-left:5px;">
						<input type="text" name="drip_api" class="txtmodal1"  id="drip_api" size="80" onkeyup="show_verify_btn('drip');" value="" /> &nbsp; &nbsp;
						<div class="arferrmessage" id="drip_api_error" style="display:none;"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>
					</td>
				</tr>

				<tr id="drip_api_tr1">

					<td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'Account ID', 'arforms-form-builder' ); ?></label></td>

					<td style="padding-bottom:3px; padding-left:5px;">

						<input type="text" name="drip_account_id" class="txtmodal1" id="drip_account_id" size="80" onkeyup="show_verify_btn('drip');" value="" />
						&nbsp; &nbsp;
							<span id="drip_link"><a href="javascript:void(0);" class="arlinks arf_restricted_control"><?php echo esc_html__( 'Verify', 'arforms-form-builder' ); ?></a></span>
						<span id="drip_loader" style="display:none;">
							<div class="arf_imageloader" style="float: none !important;display:inline-block !important; "></div>
						</span>
						<span id="drip_verify" class="frm_verify_li" style="display:none;"><?php echo esc_html__( 'Verified', 'arforms-form-builder' ); ?></span>
						<span id="drip_error" class="frm_not_verify_li" style="display:none;"><?php echo esc_html__( 'Not Verified', 'arforms-form-builder' ); ?></span>
						<input type="hidden" name="drip_status" id="drip_status" value="" />
						<div class="arferrmessage" id="drip_account_error" style="display:none;"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div></td>
				</tr>


				<tr id="drip_api_tr2">

					<td class="tdclass arflitelist-id-lbltd"><label class="lblsubtitle"><?php echo esc_html__( 'Campaign Name', 'arforms-form-builder' ); ?></label></td>

					<td style=" padding-top:3px; padding-bottom:3px; padding-left:5px; overflow: visible;">
						<span id="select_drip">
							<div class="sltstandard" style="float:none;display:inline;">
								<input name="drip_listid" id="drip_listid" value="" type="hidden" class="frm-dropdown frm-pages-dropdown">
								<dl class="arf_selectbox" data-name="drip_listid" data-id="drip_listid" style="width: 400px;">
									<dt><span><?php echo esc_html__( 'Nothing Selected', 'arforms-form-builder' ); ?></span>
									<svg viewBox="0 0 2000 1000" width="15px" height="15px">
									<g fill="#000">
									<path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
									</g>
									</svg></dt>
									<dd>
										<ul class="field_dropdown_menu field_dropdown_list_menu" style="display: none;" data-id="drip_listid">
							
										</ul>
									</dd>
								</dl>
							</div></span>




						<div id="drip_del_link" class="arlinks arfemailmarketer-delref-link-div">
							<a href="javascript:void(0);" onclick="action_autores('refresh', 'drip');"><?php echo esc_html__( 'Refresh List', 'arforms-form-builder' ); ?></a>
							&nbsp;  &nbsp;  &nbsp;  &nbsp;
							<a href="javascript:void(0);" onclick="action_autores('delete', 'drip');"><?php echo esc_html__( 'Delete Configuration', 'arforms-form-builder' ); ?></a>
						</div>


					</td>

				</tr>

				<tr>
					<td colspan="2" style="padding-left:5px;"><div class="dotted_line" style="width:96%"></div></td>
				</tr>


			</table>
		<?php

	}
} ?>
