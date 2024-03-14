<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$checkout = WC()->checkout;
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>
	<?php do_action( 'woocommerce_checkout_before_form_checkout' ); ?>
	<form name="checkout" method="post" class="checkout woocommerce-checkout <?php
	if(get_option('cwmp_activate_login')=="S"){
	if ( !is_user_logged_in() ) {
		?>
		hide
		<?php
		}
		}
		?>" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" autocomplete="off">
	<div class="cwmp_woo_wrapper">
	<?php if ( $checkout->get_checkout_fields() ) : ?>
	<?php
	$cwmp_brazilian = get_option('wcbcf_settings');
	?>
			<div class="cwmp_woo_checkout">
				<div class="cwmp_woo_fields">
				<div class="cwmp_woo_form_billing">
					<h2>
						<i class="fa <?php echo get_option('cwmp_checkout_box_icon_dados_pessoais'); ?>"></i>
						<?php echo esc_attr_e( 'User Data', 'checkout-mestres-wp'); ?>
						<a href="javascript:void(0)" class="edit_billing hide">
							<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M24.3104 6.93481L22.0594 9.18579C21.8299 9.41528 21.4588 9.41528 21.2293 9.18579L15.8094 3.76587C15.5799 3.53638 15.5799 3.16528 15.8094 2.93579L18.0604 0.684814C18.9735 -0.228271 20.4578 -0.228271 21.3758 0.684814L24.3104 3.61938C25.2283 4.53247 25.2283 6.01685 24.3104 6.93481ZM13.8758 4.86939L1.05353 17.6916L0.0183743 23.6243C-0.123227 24.425 0.575015 25.1184 1.3758 24.9817L7.30841 23.9416L20.1307 11.1194C20.3602 10.8899 20.3602 10.5188 20.1307 10.2893L14.7108 4.86939C14.4764 4.63989 14.1053 4.63989 13.8758 4.86939V4.86939ZM6.05841 16.593C5.78986 16.3245 5.78986 15.8948 6.05841 15.6262L13.5779 8.10669C13.8465 7.83814 14.2762 7.83814 14.5447 8.10669C14.8133 8.37524 14.8133 8.80493 14.5447 9.07349L7.02521 16.593C6.75666 16.8616 6.32697 16.8616 6.05841 16.593V16.593ZM4.29572 20.6995H6.63947V22.4719L3.49005 23.0237L1.9715 21.5051L2.52326 18.3557H4.29572V20.6995Z" fill="black"/>
							</svg>
						</a>
					</h2>
					<p><?php echo esc_attr_e( 'We will use your email to: Identify your profile, purchase history, order notification and shopping cart.', 'checkout-mestres-wp'); ?></p>
					<div class="cwmp_retorno_billing hide">
						<div>
							<a href="javascript:void(0)" class="edit_billing">
								<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M4 15.013L8.413 14.998L18.045 5.45799C18.423 5.07999 18.631 4.57799 18.631 4.04399C18.631 3.50999 18.423 3.00799 18.045 2.62999L16.459 1.04399C15.703 0.287994 14.384 0.291994 13.634 1.04099L4 10.583V15.013ZM15.045 2.45799L16.634 4.04099L15.037 5.62299L13.451 4.03799L15.045 2.45799ZM6 11.417L12.03 5.44399L13.616 7.02999L7.587 13.001L6 13.006V11.417Z" fill="black"/>
								<path d="M2 19H16C17.103 19 18 18.103 18 17V8.332L16 10.332V17H5.158C5.132 17 5.105 17.01 5.079 17.01C5.046 17.01 5.013 17.001 4.979 17H2V3H8.847L10.847 1H2C0.897 1 0 1.897 0 3V17C0 18.103 0.897 19 2 19Z" fill="black"/>
								</svg>
							</a>
						</div>
						<div>
							<p><strong></strong></p>
							<p></p>
							<p></p>
						</div>
					</div>
					<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
					<p class="cwmp-form-row validate-required" id="cwmp_billing_name_field">
						<label for="cwmp_billing_name" class="screen-reader-text"><?php echo esc_attr_e( 'Full Name', 'checkout-mestres-wp'); ?><abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text" name="cwmp_billing_name" id="cwmp_billing_name" placeholder="<?php echo esc_attr_e( 'Full Name', 'checkout-mestres-wp'); ?>" value="<?php if($checkout->get_value('billing_first_name')){ echo $checkout->get_value('billing_first_name'); } ?><?php if($checkout->get_value('billing_last_name')){ echo " ".$checkout->get_value('billing_last_name'); } ?>" />
						</span>
						<span class="error hide"><?php if(get_option('billing_first_name_error')){ echo get_option('billing_first_name_error'); }else{ echo esc_attr_e( 'Enter your full name', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_name'); ?>
					<input type="hidden" class="input-text" name="billing_first_name" id="billing_first_name" placeholder="" autocomplete="given-name" autocomplete="off" />
					<input type="hidden" class="input-text" name="billing_last_name" id="billing_last_name" placeholder=""  autocomplete="family-name" autocomplete="off" />
					<p class="cwmp-form-row  validate-required" id="billing_phone_field">
						<label for="billing_phone" class="screen-reader-text"><?php echo esc_attr_e( 'Whatsapp', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
							<input type="tel" class="input-text" name="billing_phone" id="billing_phone" placeholder="<?php echo esc_attr_e( 'Whatsapp', 'checkout-mestres-wp'); ?>"  value="<?php if(get_option('cwmp_international_phone')=="1"){ if(get_option('cwmp_whatsapp_ddi')=="BR"){ echo "+55"; } ?><?php $numero = preg_replace('/[^0-9]/', '', $checkout->get_value('billing_phone')); if(strlen($numero)>="13"){ echo substr($numero,2,15); }else{ echo $numero; } }else{ echo $checkout->get_value('billing_phone'); } ?>" autocomplete="off">
						</span>
						<span class="error hide"><?php if(get_option('billing_phone_error')){ echo get_option('billing_phone_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_phone'); ?>
					<?php if(!empty($cwmp_brazilian['cell_phone'])){ if($cwmp_brazilian['cell_phone']=="0"){}else{ ?>
					<p class="cwmp-form-row" id="billing_cellphone_field">
						<label for="billing_cellphone" class="screen-reader-text"><?php echo esc_attr_e( 'Phone', 'checkout-mestres-wp'); ?></label>
						<span class="woocommerce-input-wrapper">
							<input type="tel" class="input-text " name="billing_cellphone" id="billing_cellphone"  placeholder="<?php if(!get_option('cwmp_international_phone')=="1"){ echo esc_attr_e( 'Phone', 'checkout-mestres-wp'); } ?>" value="<?php echo $checkout->get_value('billing_cellphone'); ?>" autocomplete="off">
						</span>
						<span class="error hide"><?php if(get_option('billing_cellphone_error')){ echo get_option('billing_cellphone_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_cellphone'); ?>
					<?php }} ?>					
					<p class="cwmp-form-row  validate-required" id="billing_email_field">
						<label for="billing_email" class="screen-reader-text"><?php echo esc_attr_e( 'E-mail', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text " name="billing_email" id="billing_email" placeholder="<?php echo esc_attr_e( 'E-mail', 'checkout-mestres-wp'); ?>" value="<?php echo $checkout->get_value('billing_email'); ?>" autocomplete="off" />
						</span>
						<span class="error hide"><?php if(get_option('billing_email_error')){ echo get_option('billing_email_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_email'); ?>					
					<?php if(isset($cwmp_brazilian['gender'])=="1"){ ?>
					<p class="cwmp-form-row  validate-required" id="billing_gender_field">
						<label for="billing_gender" class="screen-reader-text"><?php echo esc_attr_e( 'Sex', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
						<select name="billing_gender" id="billing_gender" data-placeholder="<?php echo get_option('billing_gender_label'); ?>" tabindex="-1" aria-hidden="true" autocomplete="off">
						<option value="" selected="selected"><?php echo esc_attr_e( 'Select', 'checkout-mestres-wp'); ?></option>
						<option value="<?php echo esc_attr_e( 'I don`t want to inform', 'checkout-mestres-wp'); ?>" <?php if($checkout->get_value('billing_gender')==__( 'I don`t want to inform', 'checkout-mestres-wp')){ ?>selected="selected"<?php } ?>><?php echo esc_attr_e( 'I don`t want to inform', 'checkout-mestres-wp'); ?></option>
						<option value="<?php echo esc_attr_e( 'Woman', 'checkout-mestres-wp'); ?>" <?php if($checkout->get_value('billing_gender')==__( 'Woman', 'checkout-mestres-wp')){ ?>selected="selected"<?php } ?>><?php echo esc_attr_e( 'Woman', 'checkout-mestres-wp'); ?></option>
						<option value="<?php echo esc_attr_e( 'Man', 'checkout-mestres-wp'); ?>" <?php if($checkout->get_value('billing_gender')==__( 'Man', 'checkout-mestres-wp')){ ?>selected="selected"<?php } ?>><?php echo esc_attr_e( 'Man', 'checkout-mestres-wp'); ?></option>
						<option value="<?php echo esc_attr_e( 'Other', 'checkout-mestres-wp'); ?>" <?php if($checkout->get_value('billing_gender')==__( 'Other', 'checkout-mestres-wp')){ ?>selected="selected"<?php } ?>><?php echo esc_attr_e( 'Other', 'checkout-mestres-wp'); ?></option>
						</select>
						</span>
						<span class="error hide"><?php if(get_option('billing_gender_error')){ echo get_option('billing_gender_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_gender'); ?>
					<?php } ?>
					<?php if(isset($cwmp_brazilian['birthdate'])=="1"){ ?>
					<p class="cwmp-form-row  validate-required" id="billing_birthdate_field">
						<label for="billing_birthdate" class="screen-reader-text"><?php echo esc_attr_e( 'Date of birth', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text " name="billing_birthdate" value="<?php echo $checkout->get_value('billing_birthdate'); ?>" id="billing_birthdate" placeholder="<?php echo esc_attr_e( 'Date of birth', 'checkout-mestres-wp'); ?>" autocomplete="off">
						</span>
						<span class="error hide"><?php if(get_option('billing_birthdate_error')){ echo get_option('billing_birthdate_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_birthdate'); ?>
					<?php } ?>
					<?php if ( !is_user_logged_in() ) { ?>
					<?php if(get_option('woocommerce_registration_generate_username')=="no"){ ?>
						<p class="cwmp-form-row validate-required" id="account_username_field" data-priority="">
						<label for="account_username" class="screen-reader-text"><?php echo esc_attr_e( 'Username', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
						<input type="text" class="input-text " name="account_username" id="account_username" placeholder="<?php echo esc_attr_e( 'Username', 'checkout-mestres-wp'); ?>" value="" autocomplete="off">
						</span>
						<span class="error hide"><?php if(get_option('account_username_error')){ echo get_option('account_username_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
						</p>
					<?php } ?>
					<?php if(get_option('woocommerce_registration_generate_password')=="no"){ ?>
						<p class="cwmp-form-row validate-required" id="account_password_field" data-priority="">
						<label for="account_password"  class="screen-reader-text"><?php echo esc_attr_e( 'Password', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper password-input">
						<input type="password" class="input-text " name="account_password" id="account_password" placeholder="<?php echo esc_attr_e( 'Password', 'checkout-mestres-wp'); ?>" autocomplete="off">
						<span class="show-password-input">
						</span>
						</span>
						<span class="error hide"><?php if(get_option('account_password_error')){ echo get_option('account_password_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
						</p>
					<?php } ?>
					<?php } ?>
					<?php if(isset($cwmp_brazilian['person_type'])){ if($cwmp_brazilian['person_type']=="1"){ ?>
					<p class="cwmp-form-row" id="billing_persontype_field">
						<label for="billing_persontype" class="screen-reader-text"><?php echo esc_attr_e( 'CPF', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
							<select name="billing_persontype" id="billing_persontype" class="select" data-placeholder="Tipo de Pessoa" autocomplete="off">
								<?php if($cwmp_brazilian['person_type']=="1"){ ?>
								<option value="1" selected="selected"><?php echo esc_attr_e( 'Physical person', 'checkout-mestres-wp'); ?></option>
								<option value="2"><?php echo esc_attr_e( 'Legal person', 'checkout-mestres-wp'); ?></option>
								<?php } ?>
								<?php if($cwmp_brazilian['person_type']=="2"){ ?>
								<option value="1" selected="selected"><?php echo esc_attr_e( 'Physical person', 'checkout-mestres-wp'); ?></option>
								<?php } ?>
								<?php if($cwmp_brazilian['person_type']=="3"){ ?>
								<option value="1" selected="selected"><?php echo esc_attr_e( 'Physical person', 'checkout-mestres-wp'); ?></option>
								<?php } ?>
							</select>
						</span>
						<span class="error hide"><?php echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); ?></span>
					</p>
					<?php do_action('cwmp_after_field_persontype'); ?>
					<?php } ?>
					<?php if($cwmp_brazilian['person_type']=="1" OR $cwmp_brazilian['person_type']=="2"){ ?>
					<p class="cwmp-form-row" id="billing_cpf_field">
						<label for="billing_cpf" class="screen-reader-text"><?php echo esc_attr_e( 'CPF', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text " name="billing_cpf" id="billing_cpf" value="<?php echo $checkout->get_value('billing_cpf'); ?>" placeholder="<?php echo esc_attr_e( 'CPF', 'checkout-mestres-wp'); ?>" autocomplete="off">
							<!--<i class="fa fa-check" aria-hidden="true"></i>-->
						</span>
						<span class="error hide"><?php if(get_option('billing_cpf_error')){ echo get_option('billing_cpf_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_cpf'); ?>
					<?php if(isset($cwmp_brazilian['rg'])=="1"){ ?>
					<p class="cwmp-form-row" id="billing_rg_field">
						<label for="billing_rg" class="screen-reader-text"><?php echo esc_attr_e( 'RG', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text " name="billing_rg" id="billing_rg" value="<?php echo $checkout->get_value('billing_rg'); ?>" placeholder="<?php echo esc_attr_e( 'RG', 'checkout-mestres-wp'); ?>" autocomplete="off">
						</span>
					</p>
					<?php do_action('cwmp_after_field_rg'); ?>
					<?php } ?>
					<?php } ?>
					<?php if($cwmp_brazilian['person_type']=="1" OR $cwmp_brazilian['person_type']=="3"){ ?>
					<p class="cwmp-form-row <?php if($cwmp_brazilian['person_type']=="1"){ echo "hide"; } ?>" id="billing_cnpj_field">
						<label for="billing_cnpj" class="screen-reader-text"><?php echo esc_attr_e( 'CNPJ', 'checkout-mestres-wp'); ?>&nbsp;</label>
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text " name="billing_cnpj" id="billing_cnpj" value="<?php echo $checkout->get_value('billing_cnpj'); ?>" placeholder="<?php echo esc_attr_e( 'CNPJ', 'checkout-mestres-wp'); ?>" autocomplete="off">
						</span>
						<span class="error hide"><?php if(get_option('billing_cnpj_error')){ echo get_option('billing_cnpj_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
					</p>
					<?php do_action('cwmp_after_field_cnpj'); ?>
					<p class="cwmp-form-row <?php if($cwmp_brazilian['person_type']=="1"){ echo "hide"; } ?>" id="billing_company_field">
						<label for="billing_company" class="screen-reader-text"><?php echo esc_attr_e( 'Business Name', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text " name="billing_company" id="billing_company" value="<?php echo $checkout->get_value('billing_company'); ?>" placeholder="<?php echo esc_attr_e( 'Business Name', 'checkout-mestres-wp'); ?>" autocomplete="off">
						</span>
					</p>
					<?php if(isset($cwmp_brazilian['ie'])=="1"){ ?>
					<p class="cwmp-form-row <?php if($cwmp_brazilian['person_type']=="1"){ echo "hide"; } ?>" id="billing_ie_field">
						<label for="billing_ie" class="screen-reader-text"><?php echo esc_attr_e( 'IE', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label
						<span class="woocommerce-input-wrapper">
							<input type="text" class="input-text " name="billing_ie" id="billing_ie" value="<?php echo $checkout->get_value('billing_ie'); ?>" placeholder="<?php echo esc_attr_e( 'IE', 'checkout-mestres-wp'); ?>" autocomplete="off">
						</span>
					</p>
					<?php do_action('cwmp_after_field_ie'); ?>
					<?php } ?>
					<?php } ?>
					<?php } ?>
					<div class="cwmp_mobile" id="cwmp_step_1">
						<a href="" class="cwmp_button">
						<?php echo esc_attr_e( 'Continue', 'checkout-mestres-wp'); ?>
						</a>
					</div>
					<div class="cwmp_mobile hide" id="cwmp_edit_step_1">
						<a href="" class="cwmp_button">
						<?php echo esc_attr_e( 'Continue', 'checkout-mestres-wp'); ?>
						</a>
					</div>
				</div>
				<?php if(get_option('cwmp_view_active_address')=="N"){ ?>
				<div class="cwmp_woo_form_shipping ">
					<h2>
					<i class="fa <?php echo get_option('cwmp_checkout_box_icon_entrega'); ?>"></i>
					<?php echo __( 'Address', 'checkout-mestres-wp'); ?>	
					<a href="javascript:void(0)" class="edit_shipping hide">
						<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M24.3104 6.93481L22.0594 9.18579C21.8299 9.41528 21.4588 9.41528 21.2293 9.18579L15.8094 3.76587C15.5799 3.53638 15.5799 3.16528 15.8094 2.93579L18.0604 0.684814C18.9735 -0.228271 20.4578 -0.228271 21.3758 0.684814L24.3104 3.61938C25.2283 4.53247 25.2283 6.01685 24.3104 6.93481ZM13.8758 4.86939L1.05353 17.6916L0.0183743 23.6243C-0.123227 24.425 0.575015 25.1184 1.3758 24.9817L7.30841 23.9416L20.1307 11.1194C20.3602 10.8899 20.3602 10.5188 20.1307 10.2893L14.7108 4.86939C14.4764 4.63989 14.1053 4.63989 13.8758 4.86939V4.86939ZM6.05841 16.593C5.78986 16.3245 5.78986 15.8948 6.05841 15.6262L13.5779 8.10669C13.8465 7.83814 14.2762 7.83814 14.5447 8.10669C14.8133 8.37524 14.8133 8.80493 14.5447 9.07349L7.02521 16.593C6.75666 16.8616 6.32697 16.8616 6.05841 16.593V16.593ZM4.29572 20.6995H6.63947V22.4719L3.49005 23.0237L1.9715 21.5051L2.52326 18.3557H4.29572V20.6995Z" fill="black"/>
						</svg>
					</a>
					</h2>
					<p><?php echo esc_attr_e( 'Fill in the delivery information.', 'checkout-mestres-wp'); ?></p>
					<div class="cwmp_retorno_shipping hide">
						<div>
							<a href="javascript:void(0)" class="edit_shipping">
								<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M4 15.013L8.413 14.998L18.045 5.45799C18.423 5.07999 18.631 4.57799 18.631 4.04399C18.631 3.50999 18.423 3.00799 18.045 2.62999L16.459 1.04399C15.703 0.287994 14.384 0.291994 13.634 1.04099L4 10.583V15.013ZM15.045 2.45799L16.634 4.04099L15.037 5.62299L13.451 4.03799L15.045 2.45799ZM6 11.417L12.03 5.44399L13.616 7.02999L7.587 13.001L6 13.006V11.417Z" fill="black"/>
								<path d="M2 19H16C17.103 19 18 18.103 18 17V8.332L16 10.332V17H5.158C5.132 17 5.105 17.01 5.079 17.01C5.046 17.01 5.013 17.001 4.979 17H2V3H8.847L10.847 1H2C0.897 1 0 1.897 0 3V17C0 18.103 0.897 19 2 19Z" fill="black"/>
								</svg>
							</a>
						</div>
						<div>
							<p><strong></strong></p>
							<p></p>
							<p></p>
						</div>
					</div>
					<div class="woocommerce-shipping-fields hide">
						<p class="cwmp-form-row  validate-required" id="billing_postcode_field">
							<label for="billing_postcode" class="screen-reader-text"><?php echo esc_attr_e( 'Code Postal', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
							<span class="woocommerce-input-wrapper">
								<input type="text" class="input-text " name="billing_postcode" id="billing_postcode" value="<?php echo $checkout->get_value('billing_postcode'); ?>" placeholder="<?php echo esc_attr_e( 'Code Postal', 'checkout-mestres-wp'); ?>">
							</span>
							<span class="error hide"><?php if(get_option('billing_postcode_error')){ echo get_option('billing_postcode_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
						</p>
						<div class="cwmp_loading_address hide">
							<img src="<?php echo CWMP_PLUGIN_URL; ?>assets/images/lloading.gif" width="200" height="auto" />
						</div>

						<p class="cwmp-form-row <?php if(get_option("cwmp_view_active_address_auto")=="S"){ ?>hide<?php } ?> validate-required" id="billing_address_1_field">
							<label for="billing_address_1" class="screen-reader-text"><?php echo esc_attr_e( 'Address', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
							<span class="woocommerce-input-wrapper">
								<input type="text" class="input-text " name="billing_address_1" id="billing_address_1" value="<?php echo $checkout->get_value('billing_address_1'); ?>" placeholder="<?php echo esc_attr_e( 'Address', 'checkout-mestres-wp'); ?>">
							</span>
							<span class="error hide"><?php if(get_option('billing_address_1_error')){ echo get_option('billing_address_1_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
						</p>
						<p class="cwmp-form-row <?php if(get_option("cwmp_view_active_address_auto")=="S"){ ?>hide<?php } ?> validate-required" id="billing_number_field">
							<label for="billing_number" class="screen-reader-text"><?php echo esc_attr_e( 'Number', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
							<span class="woocommerce-input-wrapper">
								<input type="text" class="input-text " name="billing_number" id="billing_number" value="<?php echo $checkout->get_value('billing_number'); ?>" placeholder="<?php echo esc_attr_e( 'Number', 'checkout-mestres-wp'); ?>">
							</span>
							<span class="error hide"><?php if(get_option('billing_number_error')){ echo get_option('billing_number_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
						</p>
						<p class="cwmp-form-row <?php if(get_option("cwmp_view_active_address_auto")=="S"){ ?>hide<?php } ?> validate-required" id="billing_neighborhood_field">
							<label for="billing_neighborhood" class="screen-reader-text"><?php echo esc_attr_e( 'Neighborhood', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
							<span class="woocommerce-input-wrapper">
								<input type="text" class="input-text " name="billing_neighborhood" id="billing_neighborhood" value="<?php echo $checkout->get_value('billing_neighborhood'); ?>" placeholder="<?php echo esc_attr_e( 'Neighborhood', 'checkout-mestres-wp'); ?>">
							</span>
							<span class="error hide"><?php if(get_option('billing_neighborhood_error')){ echo get_option('billing_neighborhood_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
						</p>
						<p class="cwmp-form-row <?php if(get_option("cwmp_view_active_address_auto")=="S"){ ?>hide<?php } ?> validate-required" id="billing_address_2_field">
							<label for="billing_address_2" class="screen-reader-text"><?php echo esc_attr_e( 'Complement', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
							<span class="woocommerce-input-wrapper">
								<input type="text" class="input-text " name="billing_address_2" id="billing_address_2" value="<?php echo $checkout->get_value('billing_address_2'); ?>" placeholder="<?php echo esc_attr_e( 'Complement', 'checkout-mestres-wp'); ?>">
							</span>
							
						</p>
						<p class="cwmp-form-row <?php if(get_option("cwmp_view_active_address_auto")=="S"){ ?>hide<?php } ?> validate-required" id="billing_city_field">
							<label for="billing_address_2" class="screen-reader-text"><?php echo esc_attr_e( 'City', 'checkout-mestres-wp'); ?>&nbsp;<abbr class="required" title="obrigatório">*</abbr></label>
							<span class="woocommerce-input-wrapper">
								<input type="text" class="input-text " name="billing_city" id="billing_city" placeholder="<?php echo esc_attr_e( 'City', 'checkout-mestres-wp'); ?>" value="<?php echo $checkout->get_value('billing_city'); ?>">
							</span>
							<span class="error hide"><?php if(get_option('billing_city_error')){ echo get_option('billing_city_error'); }else{ echo esc_attr_e( 'Required field', 'checkout-mestres-wp'); } ?></span>
						</p>
						<?php
						if(get_option("cwmp_view_active_address_auto")=="S"){
							$countries_obj   = new WC_Countries();
							$countries   = $countries_obj->__get('countries');
							$default_country = $countries_obj->get_base_country();
							$default_county_states = $countries_obj->get_states( $default_country );
							woocommerce_form_field('billing_state', array(
								'type'       => 'select',
								'class'      => array( 'cwmp-form-row','hide' ),
								'placeholder'    => __('State', 'checkout-mestres-wp'),
								'options'    => $default_county_states,
							), $checkout->get_value('billing_state')
							);
							woocommerce_form_field('billing_country', array(
								'type'       => 'select',
								'class'      => array( 'cwmp-form-row','hide' ),
								'placeholder'    => __('Country', 'checkout-mestres-wp'),
								'options'    => $countries
							), $checkout->get_value('billing_country')
							);
						}else{
							$countries_obj   = new WC_Countries();
							$countries   = $countries_obj->__get('countries');
							$default_country = $countries_obj->get_base_country();
							$default_county_states = $countries_obj->get_states( $default_country );
							woocommerce_form_field('billing_state', array(
								'type'       => 'select',
								'class'      => array( 'cwmp-form-row' ),
								'placeholder'    => __('State', 'checkout-mestres-wp'),
								'options'    => $default_county_states,
							), $checkout->get_value('billing_state')
							);
							woocommerce_form_field('billing_country', array(
								'type'       => 'select',
								'class'      => array( 'cwmp-form-row' ),
								'placeholder'    => __('Country', 'checkout-mestres-wp'),
								'options'    => $countries
							), $checkout->get_value('billing_country')
							);
						}

						

						?>
						<div class="clear"></div>
						<div class="cwmp_mobile <?php if(get_option("cwmp_view_active_address_auto")=="S"){ ?>hide<?php } ?>" id="cwmp_step_2">
							<a href="" class="cwmp_button">
							<?php echo esc_attr_e( 'Continue', 'checkout-mestres-wp'); ?>
							</a>
						</div>
						<div class="cwmp_mobile hide" id="cwmp_edit_step_2" style="clear:both">
							<a href="" class="cwmp_button">
							<?php echo esc_attr_e( 'Continue', 'checkout-mestres-wp'); ?>
							</a>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
				<?php } ?>
				</div>
			</div>
			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			<div class="cwmp_woo_cart">
				
					<div class="title">
						<div>
							<h2><?php echo esc_attr_e( 'Resumo', 'checkout-mestres-wp'); ?> <span>(<?php echo wc_price(WC()->cart->total); ?>)</span></h2>
							<p><?php echo esc_attr_e( 'Informações da sua compra', 'checkout-mestres-wp'); ?></p>
						</div>
						<div>
						<?php echo wc_price(WC()->cart->cart_contents_total); ?>
						<a href="javascript:void(0)" class="open_resume">
							<svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10.293 0.292999L6.00003 4.586L1.70703 0.292999L0.29303 1.707L6.00003 7.414L11.707 1.707L10.293 0.292999Z" fill="black"/>
							</svg>
						</a>
						</div>
					</div>
				
				<div class="mobile_cart">
				<?php
				if ( wc_coupons_enabled() ) {
				?>
				<p class="textCupom"><?php echo esc_attr_e( 'Discount coupon', 'checkout-mestres-wp'); ?></p>
				<div class="box_form_coupon" >
					<p class="cwmp-form-row cwmp-form-row-first">
						<input type="text" name="coupon_code" class="input-text" placeholder="<?php echo esc_attr_e( 'Coupon code', 'checkout-mestres-wp'); ?>" id="coupon_code" value="" />
					</p>
					<p class="cwmp-form-row cwmp-form-row-last">
						<button type="submit" class="button cwmp_button" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'checkout-mestres-wp' ); ?>"><?php esc_attr_e( 'Apply', 'checkout-mestres-wp' ); ?></button>
					</p>
				</div>
				<div class="return_cupom"></div>
				<?php
				}
				?>
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>
</form>





<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>



