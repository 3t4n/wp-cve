<?php

/*
Plugin Name: Contact Form 7 to Mailjet connector
Description: Permet d'ajouter un email à une liste Mailjet lors de la soumission d'un formulaire Contact Form 7 - Nécessite que le plugin officiel de Mailjet soit correctement configuré (API). Ceci n'est pas un plugin officiel ni de Contact Form 7 ni de Mailjet.
Author: Youdemus
Version: 1.7.7
Author URI: http://www.youdemus.fr/
Text Domain: ydumailjet
Domain Path: /languages
*/


add_action( 'admin_enqueue_scripts', function(){
	wp_enqueue_script("mf7_admin_js", plugins_url('js/admin.js', __FILE__), array("jquery"));
	wp_enqueue_style("mf7_admin_css", plugins_url('css/admin.css', __FILE__));
});




/**
 * Register configuration admin page
*/
function ydu_mailjet_form_7_menu_page(){
    add_menu_page(
        __( 'Mailjet Form 7', 'ydumailjet' ),
        __( 'Mailjet Form 7', 'ydumailjet' ),
        'manage_options',
        'mailjet_form_7',
        'ydu_mailjet_form_7',
        '',
       101
    );
}
add_action( 'admin_menu', 'ydu_mailjet_form_7_menu_page' );

/**
 * Activate Text Domain
*/
function mailjet_form_7_plugin_textdomain() {
    load_plugin_textdomain( 'ydumailjet', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'mailjet_form_7_plugin_textdomain' );

/**
 * Display a custom menu page to configure the plugin
*/
function cf7_everything_checked()
{
	global $wpdb;
	$contact_forms = $wpdb->get_col("SELECT `ID` FROM {$wpdb->prefix}posts WHERE `post_type`='wpcf7_contact_form'");
	$output = array_flip($contact_forms);

	foreach ($output as $key => $val)
	{ $output[$key] = "on"; }

	return $output;
}

function ydu_mailjet_form_7(){
	global $wpdb;
	if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) && is_plugin_active( 'mailjet-for-wordpress/wp-mailjet.php') ) {

		if ( ! empty( $_POST ) && check_admin_referer( 'mailjet_form_7', 'mailjet_form_7_security_form' ) && current_user_can('administrator'))
		{
			update_option( 'mf7_list_options', $_POST['mf7_list_options']);
			if (!class_exists('WP_Mailjet_Api_Strategy_V3')) {
				update_option( 'mailjet_form_7_custom_1_label', sanitize_text_field($_POST['custom_1_label']));
				update_option( 'mailjet_form_7_custom_1_field', sanitize_text_field($_POST['custom_1_field']));
				update_option( 'mailjet_form_7_custom_2_label', sanitize_text_field($_POST['custom_2_label']));
				update_option( 'mailjet_form_7_custom_2_field', sanitize_text_field($_POST['custom_2_field']));
				update_option( 'mailjet_form_7_custom_3_label', sanitize_text_field($_POST['custom_3_label']));
				update_option( 'mailjet_form_7_custom_3_field', sanitize_text_field($_POST['custom_3_field']));
				update_option( 'mailjet_form_7_custom_4_label', sanitize_text_field($_POST['custom_4_label']));
				update_option( 'mailjet_form_7_custom_4_field', sanitize_text_field($_POST['custom_4_field']));
				update_option( 'mailjet_form_7_custom_5_label', sanitize_text_field($_POST['custom_5_label']));
				update_option( 'mailjet_form_7_custom_5_field', sanitize_text_field($_POST['custom_5_field']));
			}
		}
		?>
		<div class="wrap">
			<h1><?php echo __("Contact Form 7 - Mailjet configuration", "ydumailjet"); ?></h1>
			<span><?php echo __("Type the name without \"[\" and \"]\" for email, name and checkbox. Example : your-email", "ydumailjet"); ?></span>
			<form action="" method="POST" class="parameter">
				<h2><?php echo __('Main parameters', 'ydumailjet'); ?></h2>
				<?php
				if (!($options_list = get_option("mf7_list_options")) || empty($options_list))
				{
					$options_list = array(
						array(
							"email"				=> esc_attr(get_option('mailjet_form_7_email_field')),
							"name"				=> esc_attr(get_option('mailjet_form_7_name_field')),
							"box_to_check"		=> esc_attr(get_option('mailjet_form_7_checkbox_field')),
							"mailjet_list_id"	=> esc_attr(get_option('mailjet_form_7_mailjet_list_id')),
							"disabled"			=> esc_attr(get_option('mailjet_form_7_checkbox_disable')),
							"cf7_list_id"		=> cf7_everything_checked()
						)
					);
				}
				$contact_forms = $wpdb->get_results("SELECT `ID`, `post_title` FROM {$wpdb->prefix}posts WHERE `post_type`='wpcf7_contact_form'", ARRAY_A);
				?>
				<div id="mf7RepeatableOption">
					<?php
					foreach($options_list as $key => $option)
					{
						/* <input type="text" name="mf7_list_options[<?php echo $key?>][cf7_list_id]" value="<?php echo esc_attr($option['cf7_list_id']); ?>" placeholder="<?php echo __("Cf7 Forms IDs (obligatoire)", "ydumailjet"); ?>" />*/
						?>
						<div class="mf7_option_group" data-id="<?php echo $key ?>">
							<input type="text" name="mf7_list_options[<?php echo $key?>][email]"
								value="<?php echo esc_attr($option['email']); ?>"
								placeholder="<?php echo __("Email field (obligatoire)", 'ydumailjet'); ?>"
								class="mcf7_admin_email"/>
							<input type="text" name="mf7_list_options[<?php echo $key?>][name]"
								value="<?php echo esc_attr($option['name']); ?>"
								placeholder="<?php echo __("Name field", 'ydumailjet'); ?>"
								class="mcf7_admin_name"/>
							<input type="text" name="mf7_list_options[<?php echo $key?>][box_to_check]"
								value="<?php echo esc_attr($option['box_to_check']); ?>"
								placeholder="<?php echo __("Checkbox field (obligatoire)", "ydumailjet"); ?>"
								class="mcf7_admin_box_to_check"/>
							<input type="text" name="mf7_list_options[<?php echo $key?>][mailjet_list_id]"
								value="<?php echo esc_attr($option['mailjet_list_id']); ?>"
								placeholder="<?php echo __("Mailjet List ID (obligatoire)", "ydumailjet"); ?>"
								class="mcf7_admin_mailjet_list_id"/>
							<span class="mf7_fancy_select">
								<a href="#" class="mf7_fancy_select_btn">
									<?php _e('Forms &#9660;', 'ydumailjet'); ?>
								</a>
								<ul>
									<?php
									foreach($contact_forms as $form)
									{
										?>
										<li>
											<label>
												<input type="checkbox" name="mf7_list_options[<?php echo $key?>][cf7_list_id][<?php echo $form['ID'] ?>]" <?php checked("on", $option['cf7_list_id'][$form['ID']], true) ?> >
												<?php echo $form['post_title'] ?>
											</label>
										</li>
										<?php
									}
									?>
								</ul>
							</span>
							<input type="checkbox" name="mf7_list_options[<?php echo $key?>][disabled]" <?php checked("on", $option['disabled'], true) ?> />
							<?php echo __("Désactiver l'option avec la checkbox.", "ydumailjet"); ?>
							<button type="button" class="mf7_delete_listing_btn" data-id="<?php echo $key ?>">
								-
							</button>
						</div>
						<?php
					}
					?>
				</div>
				<div>
					<button type="button" id="addRowBtn">
						+
					</button>
				</div>
				<div class="clearfix"></div>
				<?php if (!class_exists('WP_Mailjet_Api_Strategy_V3')) { ?>
					<h2><?php echo __('Secondary parameters', 'ydumailjet'); ?></h2>
					<label><?php echo __('1 - Label', 'ydumailjet'); ?></label> <input type="" name="custom_1_label" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_1_label')); ?>" />
					<label><?php echo __('1 - Contact form 7 field', 'ydumailjet'); ?></label> <input type="" name="custom_1_field" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_1_field')); ?>" /><br/>
					<label><?php echo __('2 - Label', 'ydumailjet'); ?></label> <input type="" name="custom_2_label" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_2_label')); ?>" />
					<label><?php echo __('2 - Contact form 7 field', 'ydumailjet'); ?></label> <input type="" name="custom_2_field" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_2_field')); ?>" /><br/>
					<label><?php echo __('3 - Label', 'ydumailjet'); ?></label> <input type="" name="custom_3_label" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_3_label')); ?>" />
					<label><?php echo __('3 - Contact form 7 field', 'ydumailjet'); ?></label> <input type="" name="custom_3_field" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_3_field')); ?>" /><br/>
					<label><?php echo __('4 - Label', 'ydumailjet'); ?></label> <input type="" name="custom_4_label" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_4_label')); ?>" />
					<label><?php echo __('4 - Contact form 7 field', 'ydumailjet'); ?></label> <input type="" name="custom_4_field" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_4_field')); ?>" /><br/>
					<label><?php echo __('5 - Label', 'ydumailjet'); ?></label> <input type="" name="custom_5_label" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_5_label')); ?>" />
					<label><?php echo __('5 - Contact form 7 field', 'ydumailjet'); ?></label> <input type="" name="custom_5_field" value="<?php echo esc_attr(get_option('mailjet_form_7_custom_5_field')); ?>" />
				<?php } ?>
				<div class="clearfix"></div>
				<input type="submit" value="<?php echo __("sauvegarder", "ydumailjet"); ?>" />
				<?php wp_nonce_field( 'mailjet_form_7', 'mailjet_form_7_security_form'); ?>
			</form>
		</div>
		<?php
	}
	else
	{
		echo __("Mailjet and Contact Form 7 must be active.", "ydumailjet");
	}
}

/**
 * code to subscribe user to Mailjet newsletter if the checkbox input is checked.
*/
function ydu_saveFormData ($cf7) {
	if (!function_exists('is_plugin_active') && @file_exists(ABSPATH . 'wp-admin/includes/plugin.php')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
	}


	$options_list = get_option("mf7_list_options");
	if (!$options_list)
	{
		$options_list = array(
			array(
				"email"				=> esc_attr(get_option('mailjet_form_7_email_field')),
				"name"				=> esc_attr(get_option('mailjet_form_7_name_field')),
				"box_to_check"		=> esc_attr(get_option('mailjet_form_7_checkbox_field')),
				"mailjet_list_id"	=> esc_attr(get_option('mailjet_form_7_mailjet_list_id')),
				"disabled"			=> esc_attr(get_option('mailjet_form_7_checkbox_disable')),
				"cf7_list_id"		=> cf7_everything_checked()
			)
		);
	}
	if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' )
		&& is_plugin_active( 'mailjet-for-wordpress/wp-mailjet.php')
		&& !empty($options_list)
		&& get_option('mailjet_apikey')
		&& get_option('mailjet_apisecret'))
	{
		$submission = WPCF7_Submission::get_instance();
		$contact_form = $submission->get_contact_form();
		$form_id = $contact_form->id();
		$data = $submission->get_posted_data();
		$lists_to_subscribe = array();
				
		foreach($options_list as $option){
			if (!empty($option["cf7_list_id"])) {			
				if (array_key_exists($form_id, $option["cf7_list_id"]) && $option["cf7_list_id"][$form_id] == "on") {
					$lists_to_subscribe[] = $option;
				}
			}
		}
		if (!class_exists('WP_Mailjet_Api_Strategy_V3')) {
			$custom_label_1 = esc_attr(get_option( 'mailjet_form_7_custom_1_label'));
			$custom_field_1 = esc_attr(get_option( 'mailjet_form_7_custom_1_field'));
			$custom_label_2 = esc_attr(get_option( 'mailjet_form_7_custom_2_label'));
			$custom_field_2 = esc_attr(get_option( 'mailjet_form_7_custom_2_field'));
			$custom_label_3 = esc_attr(get_option( 'mailjet_form_7_custom_3_label'));
			$custom_field_3 = esc_attr(get_option( 'mailjet_form_7_custom_3_field'));
			$custom_label_4 = esc_attr(get_option( 'mailjet_form_7_custom_4_label'));
			$custom_field_4 = esc_attr(get_option( 'mailjet_form_7_custom_4_field'));
			$custom_label_5 = esc_attr(get_option( 'mailjet_form_7_custom_5_label'));
			$custom_field_5 = esc_attr(get_option( 'mailjet_form_7_custom_5_field'));
		}
		
		foreach ($lists_to_subscribe as $sub)
		{
			$email = esc_attr(				$sub['email']);
			$checkbox = esc_attr(			$sub['box_to_check']);
			$checkbox_disable = esc_attr(	$sub['disabled']);
			$list = esc_attr(				$sub['mailjet_list_id']);
			$name = esc_attr(				$sub['name']);


			$infos_contact = array(
				'Email' => $data[$email]
			);
			if ($data[$name] != "")
			{
				$infos_contact['Name'] = $data[$name];
			}
			else
			{
				$infos_contact['Name'] = $data[$email];
			}
			if (!class_exists('WP_Mailjet_Api_Strategy_V3')) {
				if ($custom_label_1 != "" AND $custom_field_1 != "" AND isset($data[$custom_field_1]))
				{
					if (is_array($data[$custom_field_1]))
						$data_to_mailjet = implode(' ', $data[$custom_field_1]);
					else
						$data_to_mailjet = $data[$custom_field_1];
					$infos_contact['Properties'][strtolower($custom_label_1)] = $data_to_mailjet;
				}
				if ($custom_label_2 != "" AND $custom_field_2 != "" AND isset($data[$custom_field_2]))
				{
					if (is_array($data[$custom_field_2]))
						$data_to_mailjet = implode(' ', $data[$custom_field_2]);
					else
						$data_to_mailjet = $data[$custom_field_2];
					$infos_contact['Properties'][strtolower($custom_label_2)] = $data_to_mailjet;
				}
				if ($custom_label_3 != "" AND $custom_field_3 != "" AND isset($data[$custom_field_3]))
				{
					if (is_array($data[$custom_field_3]))
						$data_to_mailjet = implode(' ', $data[$custom_field_3]);
					else
						$data_to_mailjet = $data[$custom_field_3];
					$infos_contact['Properties'][strtolower($custom_label_3)] = $data_to_mailjet;
				}
				if ($custom_label_4 != "" AND $custom_field_4 != "" AND isset($data[$custom_field_4]))
				{
					if (is_array($data[$custom_field_4]))
						$data_to_mailjet = implode(' ', $data[$custom_field_4]);
					else
						$data_to_mailjet = $data[$custom_field_4];
					$infos_contact['Properties'][strtolower($custom_label_4)] = $data_to_mailjet;
				}
				if ($custom_label_5 != "" AND $custom_field_5 != "" AND isset($data[$custom_field_5]))
				{
					if (is_array($data[$custom_field_5]))
						$data_to_mailjet = implode(' ', $data[$custom_field_5]);
					else
						$data_to_mailjet = $data[$custom_field_5];
					$infos_contact['Properties'][strtolower($custom_label_5)] = $data_to_mailjet;
				}
			}

			$contacts[] = $infos_contact;
			

			// Add the contact to the contact list
			if ($checkbox_disable == "on")
			{
				if ($email != "" && $list != "")
				{
					mailjet_add_to_list($list, $contacts);
				}
			}
			else
			{
				if (isset($data[$checkbox]) && ($data[$checkbox] == "on" || isset($data[$checkbox][0]) && !empty($data[$checkbox][0])) && $email != "" && $list != "")
				{
					mailjet_add_to_list($list, $contacts);
				}
			}
		}
	}
}

function mailjet_add_to_list($list, $contacts)
{

	if (class_exists('WP_Mailjet_Api_Strategy_V3'))
	{
		$mailjet = new WP_Mailjet_Api_Strategy_V3(esc_attr(get_option('mailjet_username')), esc_attr(get_option('mailjet_password')));
		$result = $mailjet->addContact(array(
			'action' => 'addforce',
			'ListID' => $list,
			'contacts' => $contacts
		));
	}
	else
	{
		//Wordpress 5.0

		$mailjet = new \MailjetPlugin\Includes\MailjetApi();
		$mailjet->getApiClient();

		foreach ($contacts[0]['Properties'] as $prop => $value) {
			if (!$mailjet->getPropertyIdByName(strtolower($prop)))
			{
				$mailjet->createMailjetContactProperty(strtolower($prop));
			}
		}
		$mailjet->syncMailjetContacts($list, $contacts, 'addforce');
	}
	return true;
}


/**
 * add the action for contact form 7
*/
add_action('wpcf7_before_send_mail', 'ydu_saveFormData');
?>
