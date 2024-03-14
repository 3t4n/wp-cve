<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if(!class_exists('NEXForms_Builder7')){
	class NEXForms_Builder7{

		public 
		$form_Id, 
		$form_type, 
		$form_title, 
		$admin_html,
		$mail_to,
		$reply_to,
		$confirmation_mail_body,
		$admin_email_body,
		$confirmation_mail_subject,
		$user_confirmation_mail_subject,
		$from_address,
		$from_name,
		$on_screen_confirmation_message,
		$on_screen_confirmation_message_admin,
		$confirmation_page,
		$send_user_mail,
		$user_email_field,
		$on_form_submission,
		$hidden_fields,
		$custom_url,
		$post_type,
		$post_action,
		$bcc,
		$bcc_user_mail,
		$custom_css,
		$is_paypal,
		$email_on_payment_success,
		$conditional_logic,
		$conditional_logic_array,
		$server_side_logic,
		$form_status,
		$currency_code,
		$products,
		$business,
		$cmd,
		$return_url,
		$cancel_url,
		$lc,
		$environment,
		$email_subscription,
		$mc_field_map,
		$mp_list_id,
		$mp_field_map,
		$mc_list_id,
		$gr_field_map,
		$gr_list_id,
		$pdf_html,
		$attach_pdf_to_email,
		$pdf_settings,
		$form_to_post_map,
		$is_form_to_post,
		$md_theme,
		$form_theme,
		$jq_theme,
		$form_style,
		$msg_style,
		$multistep_settings,
		$upload_settings,
		$option_settings,
		$attachment_settings,
		$google_analytics_conversion_code,
		$plugin_version,
		$multistep_html;
		
		public function __construct(){
			
			global $wpdb;
			
			$form_id = isset($_REQUEST['open_form']) ? sanitize_text_field($_REQUEST['open_form']) : '1';
			$tut_id = isset($_REQUEST['tut']) ? sanitize_text_field($_REQUEST['tut']) : '0';
			
			$form_type = isset($_REQUEST['form_type']) ? sanitize_text_field($_REQUEST['form_type']) : '';
			
			if($form_id)
				{
				$this->form_Id = filter_var($form_id,FILTER_VALIDATE_INT);
				$this->form_type = filter_var($form_type,FILTER_SANITIZE_STRING);
				
				$this->tut_id = filter_var($tut_id,FILTER_VALIDATE_INT);
				
				$form_attr = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id='.$this->form_Id);
				
				$plugin_data = new NEXForms5_Config();
				$this->plugin_version = $plugin_data->plugin_version;
				
				$this->form_title = esc_html(strip_tags(wp_unslash($form_attr->title)));
				$this->mail_to = $form_attr->mail_to;
				$this->reply_to = $form_attr->reply_to;
				$this->confirmation_mail_body = $form_attr->confirmation_mail_body;
				$this->admin_email_body = $form_attr->admin_email_body;
				$this->confirmation_mail_subject = $form_attr->confirmation_mail_subject;
				$this->user_confirmation_mail_subject = $form_attr->user_confirmation_mail_subject;
				$this->from_address = $form_attr->from_address;
				$this->from_name = $form_attr->from_name;
				$this->on_screen_confirmation_message = $form_attr->on_screen_confirmation_message;
				$this->on_screen_confirmation_message_admin = $form_attr->on_screen_confirmation_message_admin;
				$this->confirmation_page = $form_attr->confirmation_page;
				$this->send_user_mail = $form_attr->send_user_mail;
				$this->user_email_field = $form_attr->user_email_field;
				$this->on_form_submission = $form_attr->on_form_submission;
				$this->hidden_fields = $form_attr->hidden_fields;
				$this->custom_url = $form_attr->custom_url;
				$this->post_type = $form_attr->post_type;
				$this->post_action = $form_attr->post_action;
				$this->bcc = $form_attr->bcc;
				$this->bcc_user_mail = $form_attr->bcc_user_mail;
				$this->custom_css = wp_unslash($form_attr->custom_css);
				$this->is_paypal = $form_attr->is_paypal;
				$this->email_on_payment_success = $form_attr->email_on_payment_success;
				$this->conditional_logic = $form_attr->conditional_logic;
				$this->conditional_logic_array = $form_attr->conditional_logic_array;
				$this->server_side_logic = $form_attr->server_side_logic;
				$this->form_status = $form_attr->form_status;
				$this->currency_code = $form_attr->currency_code;
				$this->products = $form_attr->products;
				$this->business = $form_attr->business;
				$this->cmd = $form_attr->cmd;
				$this->return_url = $form_attr->return_url;
				$this->cancel_url = $form_attr->cancel_url;
				$this->lc = $form_attr->lc;
				$this->environment = $form_attr->environment;
				$this->email_subscription = $form_attr->email_subscription;
				$this->mc_field_map = $form_attr->mc_field_map;
				$this->mc_list_id = $form_attr->mc_list_id;
				$this->gr_field_map = $form_attr->gr_field_map;
				$this->gr_list_id = $form_attr->gr_list_id;
				$this->pdf_html = $form_attr->pdf_html;
				$this->attach_pdf_to_email = $form_attr->attach_pdf_to_email;
				$this->pdf_settings = $form_attr->pdf_settings;
				$this->form_to_post_map = $form_attr->form_to_post_map;
				$this->is_form_to_post = $form_attr->is_form_to_post;
				$this->admin_html = wp_unslash($form_attr->form_fields);
				$this->md_theme = $form_attr->md_theme;
				$this->jq_theme = $form_attr->jq_theme;
				$this->form_theme = $form_attr->form_theme;
				$this->form_style = $form_attr->form_style;
				$this->msg_style = $form_attr->msg_style;
				$this->multistep_settings = $form_attr->multistep_settings;
				$this->upload_settings = $form_attr->upload_settings;
				$this->option_settings = $form_attr->option_settings;
				$this->attachment_settings = $form_attr->attachment_settings;
				$this->google_analytics_conversion_code = $form_attr->google_analytics_conversion_code;
				$this->multistep_html = wp_unslash($form_attr->multistep_html);
				}
		}
		
		
		public function get_c_logic_ui(){
			
			global $wpdb;
			
			$form_Id = sanitize_title($_POST['form_Id']);
			$c_logic = $wpdb->get_var($wpdb->prepare('SELECT conditional_logic_array FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id= %d ',$form_Id));
			
			$rules = json_decode($c_logic);

				$i = 0;
			foreach($rules as $rule)
				{
				$rule_operator = $rule->operator;
				$reverse_action = $rule->reverse_actions;
				foreach($rule->conditions as $condition)
					{
					$get_the_condition 	=  $condition->condition;
					$get_the_value 		=  $condition->condition_value;
					$selection_value 	=  $condition->selected_value;
					}
				$targets = array();		
				foreach($rule->actions as $action)
					{
					$get_action_to_take = $action->do_action;
					$selection_value = $action->selected_value;
					$arrows[$i][$condition->field_Id] = $action->target_field_Id;
					}
				$i++;	
				}
			$u_arrows = array();
			foreach($arrows as $arrows1)
					{
					foreach($arrows1 as $key=>$arrow)
						{
						$u_arrows[$key] = array();
						}
					}
			
			foreach($u_arrows as $key=>$val)
					{
					foreach($arrows as $arrows4)
						{
						foreach($arrows4 as $key2=>$arrow)
							{
							if($key == $key2)
								array_push($u_arrows[$key2],$arrow);
							}
						}
					}
				$output .= '<div class="con_logic_rules">';
				foreach($u_arrows as $arrow=>$targets)
					{
					
						$output .= '<div class="the_rule" data-cl-arrow="'.$arrow.'" data-cl-targets=\''.json_encode($targets).'\'></div>';
					
					}
				$output .= '</div>';
				
		echo $output;
		wp_die();
		}
		
		
		public function builder7_top_menu(){
				
		
				$nf_function = new NEXForms_Functions();
				$db_action = new NEXForms_Database_Actions();
				$output = '';
				
				
				$theme = wp_get_theme();
				
				
				
				$output .= '<div class="nf_context_menu nf_context_menu_1 aa_menu aa_bg_main"><div class="cctm_head">'.__('Insert Smart Data Tag','nex-forms').'</div><ul class="aa_menu"><ul></div>';
				
				$output .= '<div class="nf_context_menu nf_context_menu_2 aa_menu aa_bg_main">
				
				<ul class="aa_menu">
					<li class="cm-action-item overall-edit" data-action="style"><a href="#" class="cm-item-text"><span class="fas fa-paint-brush"></span> Style Fields</span><span class="kbsc">Enter</span></a></li>
					<li class="cm-action-item group-edit" data-action="style"><a href="#" class="cm-item-text"><span class="fas fa-edit"></span> Edit Field Group</span><span class="kbsc">Enter</span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item" data-action="copy"><a href="#" class="cm-item-text"><span class="fas fa-copy"></span> Copy Fields</span><span class="kbsc">CTRL+C</span></a></li>
					<li class="cm-action-item" data-action="cut"><a href="#" class="cm-item-text"><span class="fas fa-cut"></span> Cut Fields</span><span class="kbsc">CTRL+X</span></a></li>
					<li class="cm-action-item" data-action="delete"><a href="#" class="cm-item-text"><span class="fas fa-trash"></span> Delete Fields</span><span class="kbsc">Delete</span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item" data-action="required" data-sec-action="required"><a href="#" class="cm-item-text"><span class="far fa-check-square"></span> Set as Required</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item" data-action="required" data-sec-action="unrequired"><a href="#" class="cm-item-text"><span class="far fa-square"></span> Set as Not Required</span><span class="kbsc"></span></a></li>
				<ul>
				
				</div>';
				
				
				$output .= '<div class="nf_context_menu nf_context_menu_3 aa_menu aa_bg_main">
				
				<ul class="aa_menu">
					<li class="cm-action-item field-action" data-action="edit"><a href="#" class="cm-item-text"><span class="fas fa-edit"></span> Edit</span><span class="kbsc"></span></a></li>
					<li class="sec_divider field-action"></li>
					<li class="cm-action-item field-action" data-action="copy"><a href="#" class="cm-item-text"><span class="fas fa-copy"></span> Copy</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item field-action" data-action="cut"><a href="#" class="cm-item-text"><span class="fas fa-cut"></span> Cut</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item duplicate-action" data-action="duplicate"><a href="#" class="cm-item-text"><span class="fas fa-clone"></span> Duplicate</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item delete-action" data-action="delete"><a href="#" class="cm-item-text"><span class="fas fa-trash"></span> Delete</span><span class="kbsc"></span></a></li>
					<li class="sec_divider required-action"></li>
					<li class="cm-action-item required-action" data-action="required" data-sec-action="required"><a href="#" class="cm-item-text"><span class="far fa-check-square"></span> Set as Required</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item required-action" data-action="required" data-sec-action="unrequired"><a href="#" class="cm-item-text"><span class="far fa-square"></span> Set as Not Required</span><span class="kbsc"></span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item paste-action disabled" data-action="paste-above"><a href="#" class="cm-item-text"><span class="fas fa-clipboard"></span> Paste Above</span><span class="kbsc">CTRL+SHIFT+V</span></a></li>
					<li class="cm-action-item paste-action disabled" data-action="paste-below"><a href="#" class="cm-item-text"><span class="fas fa-clipboard"></span> Paste Below</span><span class="kbsc">CTRL+V</span></a></li>
					<li class="cm-action-item paste-action-inside disabled" data-action="paste-inside-top"><a href="#" class="cm-item-text"><span class="fas fa-paste"></span> Paste Inside at Top</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item paste-action-inside disabled" data-action="paste-inside-bottom"><a href="#" class="cm-item-text"><span class="fas fa-paste"></span> Paste Inside at Bottom</span><span class="kbsc"></span></a></li>
				<ul>
				</div>';
				
				
				$output .= '<div class="nf_context_menu nf_context_menu_4 aa_menu aa_bg_main">
				
				<ul class="aa_menu">
					<li class="cm-action-item field-action" data-action="edit"><a href="#" class="cm-item-text"><span class="fas fa-palette"></span> Style Form Wrapper</span><span class="kbsc"></span></a></li>
					<li class="sec_divider field-action"></li>
					<li class="cm-action-item selection-action" data-action="select-all"><a href="#" class="cm-item-text"><span class="fas fa-object-ungroup"></span> Select all</span><span class="kbsc">CTRL+A</span></a></li>
					<li class="cm-action-item selection-action" data-action="deselect-all"><a href="#" class="cm-item-text"><span class="far fa-object-ungroup"></span> Unselect All</span><span class="kbsc">CTRL+SHIFT+A</span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item paste-action-inside disabled" data-action="paste-inside-top"><a href="#" class="cm-item-text"><span class="fas fa-paste"></span> Paste to Top</span><span class="kbsc"></span></a></li>
					<li class="cm-action-item paste-action-inside disabled" data-action="paste-inside-bottom"><a href="#" class="cm-item-text"><span class="fas fa-paste"></span> Paste to Bottom</span><span class="kbsc"></span></a></li>
					<li class="sec_divider"></li>
					<li class="cm-action-item undo-action disabled" data-action="undo"><a href="#" class="cm-item-text"><span class="fas fa-undo"></span> Undo</span><span class="kbsc">CTRL+Z</span></a></li>
					<li class="cm-action-item redo-action disabled" data-action="redo"><a href="#" class="cm-item-text"><span class="fas fa-redo"></span> Redo</span><span class="kbsc">CTRL+SHIFT+Z</span></a></li>
					
				<ul>
				</div>';
				
				
				$output .= '<div class="right_click_id" style="display:none;"></div>';
				$output .= '<div class="check_save" style="display:none;"></div>';
				$output .= '<div class="site_url" style="display:none;">'.get_option('siteurl').'</div>';
				$output .= '<div class="admin_url" style="display:none;">'.admin_url().'</div>';
				$output .= '<div class="plugin_url" style="display:none;">'.plugins_url('',dirname(dirname(__FILE__))).'</div>';
				$output .= '<div class="plugins_path" style="display:none;">'.plugins_url('',dirname(dirname(dirname(__FILE__)))).'</div>';
				$output .= '<div id="the_plugin_url" style="display:none;">'.plugins_url('',dirname(dirname(__FILE__))).'</div>';
				$output .= '<div id="form_update_id" style="display:none;">'.(($this->form_Id) ? $this->form_Id : '1').'</div>';
				$output .= '<div id="tut_id" style="display:none;">'.(($this->tut_id) ? $this->tut_id : '0').'</div>';
				
				
				
				$output .= '<div id="form_theme" style="display:none;">'.$this->form_theme.'</div>';
				$output .= '<div id="demo_site" style="display:none;">'.(($theme->Name=='NEX-Forms Demo') ? 'yes' : 'no').'</div>';
				$output .= '<div id="form_type" style="display:none;">'.$this->form_type.'</div>';		
				
				$output .= $nf_function->new_form_setup($db_action->checkout());

				$output .= '<div class="row row_zero_margin menu_wrapper ">';
					$output .= '
						<div class="col-sm-12">
						  <div class="help_menu dropdown_menu aa_menu">
							  	<ul>
								<li class="kbsc-tut"><a class="kbsc-tut"  data-toggle="tooltip_bs" data-placement="bottom" data-title="Keyboard<br>Shortcuts"><span class="fas fa-keyboard" ></span></a></li>
								<li><a class="btn waves-effect-nf waves-light-nf  tutorial-menu"><span class="fa fa-graduation-cap"></span></a>
									<ul class="aa_bg_sec aa_menu_2">
										<li class="heading aa_font_color_default">INTERACTIVE TUTORIALS</li>
										<li class="tut-1"><a class="tut-1">'.__('Build a Simple Contact Form','nex-forms').'</a></li>
										<li class="tut-2"><a class="tut-2">'.__('Using Conditional Logic','nex-forms').'</a></li>
										<li class="tut-3"><a class="tut-3">'.__('Using Math Logic','nex-forms').'</a></li>
										<li class="tut-4"><a class="tut-4">'.__('Creating Multi-Steps','nex-forms').'</a></li>
									</ul>
								</li>
								
								<li><a class="btn waves-effect-nf waves-light-nf tours-menu"><span class="fa fa-info-circle"></span></a>
									<ul class="aa_bg_sec aa_menu_2">
										<li class="heading aa_font_color_default">BACKEND TOURS</li>
										<li class="tour-whats-new"><a class="tour-whats-new">'.__('Whats New','nex-forms').'</a></li>
										<li class="tour-main"><a class="tour-main">'.__('Main Tour','nex-forms').'</a></li>
										<li class="heading aa_font_color_default">'.__('Email Setups','nex-forms').'</li>
											<li class="tour-email-setup"><a class="tour-email-setup">'.__('Admin Email Setup','nex-forms').'</a></li>
											<li class="tour-email-setup-user"><a class="tour-email-setup">'.__('User Email Setup','nex-forms').'</a></li>
										<li class="heading aa_font_color_default">'.__('Integration Setups','nex-forms').'</li>
											<li class="tour-paypal-setup"><a class="tour-paypal-setup">'.__('PayPal Setup','nex-forms').'</a></li>
											<li class="tour-pdf-setup"><a class="tour-pdf-setup">'.__('PDF Creator','nex-forms').'</a></li>
											<li class="tour-ftp-setup"><a class="tour-ftp-setup">'.__('Form to Post/Page Setup','nex-forms').'</a></li>
											<li class="tour-mc-setup"><a class="tour-mc-setup">'.__('MailChimp Setup','nex-forms').'</a></li>
											<li class="tour-gr-setup"><a class="tour-gr-setup">'.__('GetResponse Setup','nex-forms').'</a></li>
										<li class="heading aa_font_color_default">'.__('Form Options','nex-forms').'</li>
											<li class="tour-form-submit-setup"><a class="tour-form-submit-setup">'.__('Extra Options','nex-forms').'</a></li>
											<li class="tour-hidden-fields-setup"><a class="tour-hidden-fields-setup">'.__('Hidden Fields Setup','nex-forms').'</a></li>
											<li class="tour-other-options-setup"><a class="tour-other-options-setup">'.__('Other Options','nex-forms').'</a></li>
									</ul>
								</li>
						  </div>';
						  
						$output .= '<div class="icon-menu dropdown_menu aa_menu"><ul class="">';
						//$output .= '<li class="expand_fullscreen" ><a class="btn waves-effect-nf waves-light-nf btn-fullscreen" href="#" data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Enter Full Screen Mode','nex-forms').'"><span class="fa fas fa-expand-arrows-alt"></span></a></li>';
						//$output .= '<li class="colapse_fullscreen" style="display:none"><a class="btn waves-effect-nf waves-light-nf btn-wordpress" href="#" data-toggle="tooltip_bs" data-html="true" data-placement="bottom" title="'.__('Exit Fullscreen Mode<br>Show WordPress Menus','nex-forms').'"><span class="fas fa-compress-arrows-alt"></span></a></li>';
						$output .= '<li class="back-to-dashboard" ><a class="btn waves-effect-nf waves-light-nf btn-dashboard" href="'.get_admin_url().'admin.php?page=nex-forms-dashboard" data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Back to NEX-Forms Dashboard','nex-forms').'"></a></li>';
						
						$output .= '<li class="open-form-item"><a class="btn waves-effect-nf waves-light-nf saved-forms" href="#" data-toggle="tooltip_bs2" data-placement="bottom" title="'.__('Forms','nex-forms').'"><span class="fas fa-folder-open"></span></a>
											
											  <!-- Dropdown Structure -->
											  <ul id="" class="aa_menu_2">';
											  global $wpdb;
					
												$forms = $wpdb->get_results('SELECT Id, title FROM '.$wpdb->prefix.'wap_nex_forms WHERE is_form=1 ORDER BY Id DESC');
												
												foreach($forms as $form)
													{
													$output .= '<li '.(($this->form_Id==$form->Id) ? 'class="active"' : '').'><span class="form_id"><strong>'.$form->Id.'</strong></span><a href="'.get_admin_url().'admin.php?page=nex-forms-builder&open_form='.$form->Id.'">'.$nf_function->view_excerpt2($form->title,40).' <br /></a></li>';	
													}
											  $output .= '</ul>';
						$output .= '<li class="new-form-item"><a class="btn waves-effect-nf waves-light-nf  create_new_form" data-toggle="tooltip_bs2" data-placement="bottom" title="'.__('Create New Form','nex-forms').'"><span class="fas fa-folder-plus"></span></a></li>';
						
						$output .= '</ul></div>';	 
						  
						  
						  $output .= '<nav class="nav-extended aa_bg_main builder_nav">';
							
						
				
				$output .= '<div class="nav-content prime-menu">';
							
							 
						$theme = wp_get_theme();	
						$output.= '<div class="top-menu-dropdown"><ul class="tabs_nf sec-menu aa_menu"> 
										<li class="always-current form_title"><input id="form_name" name="form_name" class="form-control aa_bg_main_input" type="text" placeholder="Enter Form Title" value="'.$this->form_title.'"></li>
										<li class="tab current"><a class="active canvas_view" href="#builder_view"><span class="top-icon fas fa-file-alt"></span><span class="menu-text">'.__('Form','nex-forms').'</span></a></li>
										<li class="tab"><a href="#email_setup" class="email_setup"><span class="top-icon fas fa-envelope"></span><span class="menu-text">'.__('Email Setup','nex-forms').'</span></a></li>
										<li class="tab"><a href="#form_integration" class="integration"><span class="top-icon fas fa-code-branch"></span><span class="menu-text">'.__('Integration','nex-forms').'</span></a></li>
										<li class="tab"><a href="#form_options" class="form_options"><span class="top-icon fas fa-cog"></span><span class="menu-text">'.__('Extra Options','nex-forms').'</span></a></li>
										<li class="tab"><a href="#embed_options" class="embed_options"><span class="top-icon fas fa-code"></span><span class="menu-text">'.__('Embed','nex-forms').'</span></a></li>
										<li class="tab save-menu-item"><div class="save_nex_form prime_save"><span class="top-icon fas fa-save"></span><span class="menu-text">'.__('SAVE','nex-forms').'</span></div> </li>
										
										
										
									  	<li>
										<div class="history-tools">
											<div class="history_action do_undo" data-action="undo"><span class="fas fa-undo" data-toggle="tooltip_bs2" data-placement="bottom" title="'.__('Undo last action<br><small>CTRL+Z</small>','nex-forms').'"></span></div>
											<div class="history_action do_redo" data-action="redo"><span class="fas fa-redo" data-toggle="tooltip_bs2" data-placement="bottom" title="'.__('Redo last undo<br><small>CTRL+SHIFT+Z</small>','nex-forms').'"></span></div>
										</div>
										</li>
										'.((!get_option('nf_activated') || $theme->Name=='NEX-Forms Demo') ? '<li><a href="https://1.envato.market/zQ6de" target="_blank" class="btn waves-effect-nf waves-light-nf upgrade_pro">BUY NEX-FORMS</a>' : '').'</li>
									  </ul>
									  </div>
									  
									  
									   
									  
									  
										</div>
									</nav>
								
						'.(($theme->Name=='NEX-Forms Demo') ? '<a class="fullscreen-demo-test-page" href="http://basixonline.net/nex-forms-admin-demo/nex-forms-demo-form/user-test-form-'.$_GET['open_form'].'/?form_id='.$_GET['open_form'].'"><span class="top-icon fas fa-external-link-alt"></span><span class="menu-text"> Demo Test Page</span></a></li>' : '' ).'
											
								
								</div>';
		return $output;
				
		}
		
		
		
		public function print_overall_settings(){
		
		
			$theme_settings = json_decode($this->md_theme,true);
			
			$set_theme 			= (isset($theme_settings['0']['theme_name'])) 	? $theme_settings['0']['theme_name'] 	: 'default';
			$set_theme_shade 	= (isset($theme_settings['0']['theme_shade'])) ? $theme_settings['0']['theme_shade'] 	: 'light';
			
			
			$overall_font				= (isset($theme_settings['0']['overall_font'])) ? $theme_settings['0']['overall_font'] : '';
			
			$field_spacing				= (isset($theme_settings['0']['field_spacing'])) ? $theme_settings['0']['field_spacing'] : '';
			
			
			$overall_label_font			= (isset($theme_settings['0']['overall_label_font'])) ? $theme_settings['0']['overall_label_font'] : '';
			$overall_label_font_size	= (isset($theme_settings['0']['overall_label_font_size'])) ? $theme_settings['0']['overall_label_font_size'] : '';
			$overall_label_align		= (isset($theme_settings['0']['overall_label_align'])) ? $theme_settings['0']['overall_label_align'] : '';
			
			$overall_label_bold			= (isset($theme_settings['0']['overall_label_bold'])) ? $theme_settings['0']['overall_label_bold'] : '';
			$overall_label_italic		= (isset($theme_settings['0']['overall_label_italic'])) ? $theme_settings['0']['overall_label_italic'] : '';
			$overall_label_underline	= (isset($theme_settings['0']['overall_label_underline'])) ? $theme_settings['0']['overall_label_underline'] : '';
			
			$overall_label_color		= (isset($theme_settings['0']['overall_label_color'])) ? $theme_settings['0']['overall_label_color'] : '';
			
			$overall_input_font			= (isset($theme_settings['0']['overall_input_font'])) ? $theme_settings['0']['overall_input_font'] : '';
			$overall_input_font_size	= (isset($theme_settings['0']['overall_input_font_size'])) ? $theme_settings['0']['overall_input_font_size'] : '';
			$overall_input_align		= (isset($theme_settings['0']['overall_input_align'])) ? $theme_settings['0']['overall_input_align'] : '';
			
			$overall_input_color		= (isset($theme_settings['0']['overall_input_color'])) ? $theme_settings['0']['overall_input_color'] : '';
			$overall_input_bg_color		= (isset($theme_settings['0']['overall_input_bg_color'])) ? $theme_settings['0']['overall_input_bg_color'] : '';
			$overall_input_border_color	= (isset($theme_settings['0']['overall_input_border_color'])) ? $theme_settings['0']['overall_input_border_color'] : '';
			
			
			$overall_input_bold			= (isset($theme_settings['0']['overall_input_bold'])) ? $theme_settings['0']['overall_input_bold'] : '';
			$overall_input_italic		= (isset($theme_settings['0']['overall_input_italic'])) ? $theme_settings['0']['overall_input_italic'] : '';
			$overall_input_underline	= (isset($theme_settings['0']['overall_input_underline'])) ? $theme_settings['0']['overall_input_underline'] : '';
			
			$overall_field_layout		= (isset($theme_settings['0']['overall_field_layout'])) ? $theme_settings['0']['overall_field_layout'] : '';
			$overall_field_corners		= (isset($theme_settings['0']['overall_field_corners'])) ? $theme_settings['0']['overall_field_corners'] : '';
			
			$overall_icon_font_size 	= (isset($theme_settings['0']['overall_icon_font_size'])) ? $theme_settings['0']['overall_icon_font_size'] : '';
			
			$overall_icon_color			= (isset($theme_settings['0']['overall_icon_color'])) ? $theme_settings['0']['overall_icon_color'] : '';
			$overall_icon_bg_color		= (isset($theme_settings['0']['overall_icon_bg_color'])) ? $theme_settings['0']['overall_icon_bg_color'] : '';
			$overall_icon_border_color	= (isset($theme_settings['0']['overall_icon_border_color'])) ? $theme_settings['0']['overall_icon_border_color'] : '';
			
			$overall_field_errors 		= (isset($theme_settings['0']['overall_field_errors'])) ? $theme_settings['0']['overall_field_errors'] : '';
			$overall_field_errors_pos 	= (isset($theme_settings['0']['overall_field_errors_pos'])) ? $theme_settings['0']['overall_field_errors_pos'] : '';
			
			
			$set_form_theme = ($this->form_theme) ? $this->form_theme : 'bootstrap';
			$set_jq_theme 	= ($this->jq_theme) ? $this->jq_theme : 'default';
			
			$set_current_theme = '';
			
			if($set_form_theme=='m_design')
				$set_current_theme = 'material_theme';
			
			$output = '';
			
			$output .= '<div class="overall-settings-column mutistep-settings-column  settings-column-style '.$set_current_theme.' right_hand_col">';
			
					$output .= '
					<div id="close-settings" class="close-area">
						<span class="fa fa-close"></span>
					</div>
					';
						
						$output .= '<div class="material_box_head aa_bg_main"><span class="fa fa-layer-group"></span>'.__('Multi-Step Settings','nex-forms').'</div>';
						
						$output .= '<div class="overall-setting-categories field-setting-categories-style">';
							
							
							$output .= '<nav class="nav-extended settings_tabs_nf">
									<div class="nav-content aa_bg_main">
									  <ul class="tabs_nf tabs_nf-transparent sec-menu aa_menu">
									  	
										<li id="ms-overall-settings" class="tab always_current"><a href="#ms-overall-settings-panel" class="active">'.__('Setup','nex-forms').'</a></li>
										<li id="ms-css-settings" class="tab always_current"><a href="#ms-css-settings-panel">'.__('Breadcrumb','nex-forms').'</a></li>
										<li id="ms-timer-settings" class="tab always_current"><a href="#ms-timer-settings-panel">'.__('Timer','nex-forms').'</a></li>
									  </ul>
									</div>
								 </nav>';
						$output .= '</div>';
					
					
						$output .= '<div class="inner">';
//LABEL SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
						
						
						
					
						
									
		$bc_settings = json_decode($this->multistep_settings,true);

		$bc_type 						= (isset($bc_settings['0']['breadcrumb_type'])) ? $bc_settings['0']['breadcrumb_type'] 						: 'basic';
		$bc_text_pos 					= (isset($bc_settings['0']['text_pos'])) ? $bc_settings['0']['text_pos'] 										: 'text-bottom';	
		$bc_data_theme 					= (isset($bc_settings['0']['data_theme'])) ? $bc_settings['0']['data_theme'] 									: 'light-green';
		$bc_show_front_end 				= (isset($bc_settings['0']['show_front_end'])) ? $bc_settings['0']['show_front_end'] 							: 'yes';	
		$bc_show_inside 				= (isset($bc_settings['0']['show_inside'])) ? $bc_settings['0']['show_inside'] 								: 'no';	
		$scroll_to_top 					= (isset($bc_settings['0']['scroll_to_top'])) ? $bc_settings['0']['scroll_to_top'] 							: 'yes';
		$bc_position 					= (isset($bc_settings['0']['bc_position'])) ? $bc_settings['0']['bc_position'] 								: 'top';
		$bc_gutter 						= (isset($bc_settings['0']['bc_gutter'])) ? $bc_settings['0']['bc_gutter'] 									: '20';
		$bc_folded 						= (isset($bc_settings['0']['bc_folded'])) ? $bc_settings['0']['bc_folded'] 									: 'bc-unfolded';
		$bc_connected 					= (isset($bc_settings['0']['bc_connected'])) ? $bc_settings['0']['bc_connected'] 								: 'bc-connected';
		$bc_style 						= (isset($bc_settings['0']['bc_style'])) ? $bc_settings['0']['bc_style'] 										: 'bc-solid';
		$bc_css 						= (isset($bc_settings['0']['bc_css'])) ? $bc_settings['0']['bc_css'] 											: '';
		$multi_step_transition_in		= (isset($bc_settings['0']['multi_step_transition_in'])) ? $bc_settings['0']['multi_step_transition_in'] 		: 'fadeIn';
		$multi_step_transition_out		= (isset($bc_settings['0']['multi_step_transition_out'])) ? $bc_settings['0']['multi_step_transition_out'] 	: 'fadeOut';
		$multi_step_back_disabled		= (isset($bc_settings['0']['multi_step_back_disabled'])) ? $bc_settings['0']['multi_step_back_disabled'] 		: 'no';
		
		
		
		
		
		
		if($bc_type =='dotted' || $bc_type =='dotted_count')
			$bc_type = 'pilled';
		if($bc_type == 'basic' || $bc_type =='basix')
			$bc_type = 'pilled';		

		$output .= '<div id="ms-overall-settings-panel" class="ms-settings row settings-section active">';
			$output .= '<div class="field-setting col-xs-6 s-all">';	
								$output .= '<div role="group" class="btn-group ms-scroll-top">';
									$output .= '<small>'.__('Auto-scroll to top of next step','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf yes '.(($scroll_to_top!='no') ? 'active' : '' ).'" type="button" title="'.__('Auto scroll to the<br />top of next step on<br />step advance','nex-forms').'"><i class="fa fa-check"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no '.(($scroll_to_top=='no') ? 'active' : '' ).'" type="button" title="'.__('No auto scrolling on<br />step advance','nex-forms').'"><i class="fa fa-close"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
			$output .= '<div class="field-setting col-xs-6 s-all">';	
								$output .= '<div role="group" class="btn-group disable_back" data-value="'.$multi_step_back_disabled.'">';
									$output .= '<small>'.__('Disable back stepping','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf yes '.(($multi_step_back_disabled!='no') ? 'active' : '' ).'" type="button" title="'.__('Allow user to step back','nex-forms').'"><i class="fa fa-check"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no '.(($multi_step_back_disabled=='no') ? 'active' : '' ).'" type="button" title="'.__('Prevents user from stepping back','nex-forms').'"><i class="fa fa-close"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
			
			$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Step Transition Settings','nex-forms').'</span></div>';
							$output .= '</div>';
			
			$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<div class="input-group input-group-sm">';	
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Step In</span>';
									$output .= '</span>';
									$output .= '<select id="step_in_transition_animation" class="form-control" name="step_in_transition_animation" data-selected="'.$multi_step_transition_in.'">
															  <option selected="selected" value="fadeIn">Default (fadeIn)</option>
															  <option value="none">No Animation</option>
																	<optgroup label="Attention Seekers">
																	  <option value="bounce">bounce</option>
																	  <option value="flash">flash</option>
																	  <option value="pulse">pulse</option>
																	  <option value="rubberBand">rubberBand</option>
																	  <option value="shake">shake</option>
																	  <option value="swing">swing</option>
																	  <option value="tada">tada</option>
																	  <option value="wobble">wobble</option>
																	  <option value="jello">jello</option>
																	</optgroup>
															
																	<optgroup label="Bouncing Entrances">
																	  <option value="bounceIn">bounceIn</option>
																	  <option value="bounceInDown">bounceInDown</option>
																	  <option value="bounceInLeft">bounceInLeft</option>
																	  <option value="bounceInRight">bounceInRight</option>
																	  <option value="bounceInUp">bounceInUp</option>
																	</optgroup>
													
															
													
															<optgroup label="Fading Entrances">
															  <option value="fadeIn">fadeIn</option>
															  <option value="fadeInDown">fadeInDown</option>
															  <option value="fadeInDownBig">fadeInDownBig</option>
															  <option value="fadeInLeft">fadeInLeft</option>
															  <option value="fadeInLeftBig">fadeInLeftBig</option>
															  <option value="fadeInRight">fadeInRight</option>
															  <option value="fadeInRightBig">fadeInRightBig</option>
															  <option value="fadeInUp">fadeInUp</option>
															  <option value="fadeInUpBig">fadeInUpBig</option>
															</optgroup>
													
															
															<optgroup label="Flippers">
															  <option value="flip">flip</option>
															  <option value="flipInX">flipInX</option>
															  <option value="flipInY">flipInY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedIn">lightSpeedIn</option>
															</optgroup>
													
															<optgroup label="Rotating Entrances">
															  <option value="rotateIn">rotateIn</option>
															  <option value="rotateInDownLeft">rotateInDownLeft</option>
															  <option value="rotateInDownRight">rotateInDownRight</option>
															  <option value="rotateInUpLeft">rotateInUpLeft</option>
															  <option value="rotateInUpRight">rotateInUpRight</option>
															</optgroup>
													
															
													
															<optgroup label="Sliding Entrances">
															  <option value="slideInUp">slideInUp</option>
															  <option value="slideInDown">slideInDown</option>
															  <option value="slideInLeft">slideInLeft</option>
															  <option value="slideInRight">slideInRight</option>
													
															</optgroup>
															
															
															<optgroup label="Zoom Entrances">
															  <option value="zoomIn">zoomIn</option>
															  <option value="zoomInDown">zoomInDown</option>
															  <option value="zoomInLeft">zoomInLeft</option>
															  <option value="zoomInRight">zoomInRight</option>
															  <option value="zoomInUp">zoomInUp</option>
															</optgroup>
															
															
													
															<optgroup label="Specials">
															  <option value="rollIn">rollIn</option>
															</optgroup>
														  </select>';
									
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								
								$output .= '<div class="input-group input-group-sm">';	
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Step Out</span>';
									$output .= '</span>';
									$output .= '<select id="step_out_transition_animation" class="form-control" name="step_out_transition_animation" data-selected="'.$multi_step_transition_out.'">
															  <option selected="selected" value="fadeOut">Default (fadeOut)</option>
															  <option value="none">No Transition</option>
																	
													
															<optgroup label="Bouncing Exits">
															  <option value="bounceOut">bounceOut</option>
															  <option value="bounceOutDown">bounceOutDown</option>
															  <option value="bounceOutLeft">bounceOutLeft</option>
															  <option value="bounceOutRight">bounceOutRight</option>
															  <option value="bounceOutUp">bounceOutUp</option>
															</optgroup>
													
															
													
															<optgroup label="Fading Exits">
															  <option value="fadeOut">fadeOut</option>
															  <option value="fadeOutDown">fadeOutDown</option>
															  <option value="fadeOutDownBig">fadeOutDownBig</option>
															  <option value="fadeOutLeft">fadeOutLeft</option>
															  <option value="fadeOutLeftBig">fadeOutLeftBig</option>
															  <option value="fadeOutRight">fadeOutRight</option>
															  <option value="fadeOutRightBig">fadeOutRightBig</option>
															  <option value="fadeOutUp">fadeOutUp</option>
															  <option value="fadeOutUpBig">fadeOutUpBig</option>
															</optgroup>
													
															<optgroup label="Flippers">
															  <option value="flipOutX">flipOutX</option>
															  <option value="flipOutY">flipOutY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedOut">lightSpeedOut</option>
															</optgroup>
													
															
													
															<optgroup label="Rotating Exits">
															  <option value="rotateOut">rotateOut</option>
															  <option value="rotateOutDownLeft">rotateOutDownLeft</option>
															  <option value="rotateOutDownRight">rotateOutDownRight</option>
															  <option value="rotateOutUpLeft">rotateOutUpLeft</option>
															  <option value="rotateOutUpRight">rotateOutUpRight</option>
															</optgroup>
													
															
															<optgroup label="Sliding Exits">
															  <option value="slideOutUp">slideOutUp</option>
															  <option value="slideOutDown">slideOutDown</option>
															  <option value="slideOutLeft">slideOutLeft</option>
															  <option value="slideOutRight">slideOutRight</option>
															  
															</optgroup>
															
															
															
															<optgroup label="Zoom Exits">
															  <option value="zoomOut">zoomOut</option>
															  <option value="zoomOutDown">zoomOutDown</option>
															  <option value="zoomOutLeft">zoomOutLeft</option>
															  <option value="zoomOutRight">zoomOutRight</option>
															  <option value="zoomOutUp">zoomOutUp</option>
															</optgroup>
													
															<optgroup label="Specials">
															  <option value="hinge">hinge</option>
															  <option value="rollOut">rollOut</option>
															</optgroup>
														  </select>';
									
									

								$output .= '</div>';
							$output .= '</div>';				
			
			
							
			
		$output .= '</div>';
		
		
		
		
		$output .= '<div id="ms-css-settings-panel" class="ms-settings row settings-section" style="display:none;">';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group bc_show_front_end">';
									$output .= '<small>'.__('Add Breadcrumb','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf show_front '.(($bc_show_front_end=='yes') ? 'active' : '' ).'" type="button" title="'.__('Display Breadcrumb on front-end','nex-forms').'"><i class="fa fa-check"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf dont_show_front '.(($bc_show_front_end!='yes') ? 'active' : '' ).'" type="button" title="'.__('No Breadcrumb on front-end','nex-forms').'"><i class="fa fa-close"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group bc_position" data-value="'.$bc_position.'">';
									$output .= '<small>'.__('Add To','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf position_top '.(($bc_position=='top') ? 'active' : '' ).'" type="button" title="'.__('Add to Form Header','nex-forms').'"><i class="fa fa-window-maximize"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf position_bottom '.(($bc_position=='bottom') ? 'active' : '' ).'" type="button" title="'.__('Add to Form Footer','nex-forms').'"><i style="transform:rotate(180deg);" class="fa fa-window-maximize"></i></button>';
									//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf position_both '.(($bc_position=='both') ? 'active' : '' ).'" type="button" title="'.__('Display top and <br />bottom of the Form','nex-forms').'"><i class="fa fa-arrows-v"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group crumb-position">';
												$output .= '<small>'.__('Align','nex-forms').'</small>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
											$output .= '</div>';
							$output .= '</div>';			
							
							
							
							
							
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Overall Style and Color','nex-forms').'</span></div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<small>'.__('Type','nex-forms').'</small>';
								$output .= '<select name="set_breadcrumb_type" id="set_breadcrumb_type" class="form-control set_breadcrumb_type" data-selected="'.$bc_type.'">
												<!--<option value="basix">'.__('Basic','nex-forms').'</option>-->
												<option value="pilled">'.__('Pilled','nex-forms').'</option>
												<option value="triangular">'.__('Arrowed','nex-forms').'</option>
												<option value="rectangular">'.__('Tiled','nex-forms').'</option>
												<option value="rounded">'.__('Rounded','nex-forms').'</option>
												<!--<option value="dotted">'.__('Timeline','nex-forms').'</option>-->
												<!--<option value="dotted">'.__('Slider','nex-forms').'</option>-->
												<option value="skewed-f">'.__('Slanted - Forward','nex-forms').'</option>
												<option value="skewed-b">'.__('Slanted - Backward','nex-forms').'</option>
												<option value="p_bar">'.__('Percentage Bar','nex-forms').'</option>
												
											</select>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
									$output .= '<small>'.__('Color Scheme','nex-forms').'</small>';
									$output .= '<select name="bc_theme_selection" id="bc_theme_selection" class="form-control bc_theme_selection" data-selected="'.$bc_data_theme.'">
													
													<option value="light-green" selected="selected">'.__('Light Green','nex-forms').'</option>
													<option value="green">'.__('Green','nex-forms').'</option>
													<option value="red">'.__('Red','nex-forms').'</option>
													<option value="pink">'.__('Pink','nex-forms').'</option>
													<option value="purple">'.__('Purple','nex-forms').'</option>
													<option value="deep-purple">'.__('Deep Purple','nex-forms').'</option>
													<option value="indigo">'.__('Indigo','nex-forms').'</option>
													<option value="blue">'.__('Blue','nex-forms').'</option>
													<option value="light-blue">'.__('Light Blue','nex-forms').'</option>
													<option value="cyan">'.__('Cyan','nex-forms').'</option>
													<option value="teal">'.__('Teal','nex-forms').'</option>
													<option value="lime">'.__('Lime','nex-forms').'</option>
													<!--<option value="yellow">'.__('Yellow','nex-forms').'</option>-->
													<option value="amber">'.__('Amber','nex-forms').'</option>
													<option value="orange">'.__('Orange','nex-forms').'</option>
													<option value="brown">'.__('Brown','nex-forms').'</option>
													<option value="gray">'.__('Gray','nex-forms').'</option>
													<option value="blue-gray">'.__('Blue Gray','nex-forms').'</option>
												</select>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
									$output .= '<small>'.__('Style','nex-forms').'</small>';
									$output .= '<select name="bc_style_selection" id="bc_style_selection" class="form-control bc_style_selection" data-selected="'.$bc_style.'">
													<option value="bc-solid" selected="selected">'.__('Solid','nex-forms').'</option>
													<option value="bc-bordered">'.__('Bordered','nex-forms').'</option>
												</select>';
							$output .= '</div>';
							
							
							
							/*$output .= '<div class="field-setting col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Item Styling & Settings','nex-forms').'</span></div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-9 s-all">';
								$output .= '<small>'.__('Unvisited Colors','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
									
									
									
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Background','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false"><input type="text" class="form-control bc-crumb-bg-color" name="bc-crumb-bg-color" id="bs-color"></span>';
								
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Text','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false"><input type="text" class="form-control bc-crumb-text-color" name="bc-crumb-text-color" id="bs-color"></span>';
								
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Border','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false"><input type="text" class="form-control bc-crumb-border-color" name="bc-crumb-border-color" id="bs-color"></span>';
								
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Connector','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false"><input type="text" class="form-control bc-crumb-connector-color" name="bc-crumb-connector-color" id="bs-color"></span>';
								
									
									
									
									
									
								$output .= '</div>';
							$output .= '</div>';
							*/
							$output .= '<div class="field-setting col-xs-3 s-all">';
								$output .= '<small>'.__('Gutter / Spacing','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
										$output .= '<span class="input-group-addon" title="'.__('Space between items','nex-forms').'">';
											$output .= '<span class="fa fa-arrows-h"></span>';
										$output .= '</span>';
							
									
									$output .= '<input name="bc-gutter" id="bc-gutter" class="form-control" value="'.$bc_gutter.'">';
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-1 s-all">';
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group bc_folded" data-value="'.$bc_folded.'">';
									$output .= '<small>'.__('Folded Crumb Items','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf folded '.(($bc_folded=='bc-folded') ? 'active' : '' ).'" type="button" title="'.__('Display Step title<br>only when active','nex-forms').'"><i class="fas fa-book"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf unfolded '.(($bc_folded=='bc-unfolded') ? 'active' : '' ).'" type="button" title="'.__('Always display Step title','nex-forms').'"><i class="fas fa-book-open"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group bc_connected" data-value="'.$bc_connected.'">';
									$output .= '<small>'.__('Connected','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf connected '.(($bc_connected=='bc-connected') ? 'active' : '' ).'" type="button" title="'.__('Connect items','nex-forms').'"><i class="fa fa-link"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf unconnected '.(($bc_connected=='bc-unconnected') ? 'active' : '' ).'" type="button" title="'.__('Disconnect items','nex-forms').'"><i class="fa fa-unlink"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							/*$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group bc_show_inside">';
									$output .= '<small>'.__('Show Inside Form Wrapper?','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf show_inside '.(($bc_show_inside!='no') ? 'active' : '' ).'" type="button" title="'.__('Breadcrumb will be displayed<br />inside the form wrapper','nex-forms').'"><i class="fa fa-check"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf show_outside '.(($bc_show_inside=='no') ? 'active' : '' ).'" type="button" title="'.__('Breadcrumb will be displayed<br />outside the form wrapper','nex-forms').'"><i class="fa fa-close"></i></button>';
								$output .= '</div>';
							$output .= '</div>';*/
							
							
							
								
											
							/*$output .= '<div class="field-setting col-xs-4 s-all">';				
								$output .= '<div role="group" class="btn-group bc-text-pos">';
									$output .= '<small>'.__('Dotted Type Text Position','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf top '.(($bc_text_pos!='text-bottom') ? 'active' : '' ).'" type="button" title="'.__('Top','nex-forms').'"><i class="fa fa-caret-up"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf bottom '.(($bc_text_pos=='text-bottom') ? 'active' : '' ).'" type="button" title="'.__('Bottom','nex-forms').'"><i class="fa fa-caret-down"></i></button>';
								$output .= '</div>';
							$output .= '</div>';*/	
								
						
						
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Breadcrumb Wrapper Styling','nex-forms').'</span></div>';
							$output .= '</div>';
							
							
							/*$output .= '<div class="field-setting col-xs-12 s-all">';
								$output .= '<small>'.__('Overall Font','nex-forms').'</small>';
								$output .= '<select name="google_fonts_overall" id="google_fonts_overall" data-selected="'.$overall_font.'" class="sfm form-control"><option value="">'.__('-- Select Google Font --','nex-forms').'</option><option value="">'.__('Default','nex-forms').'</option>';
											$get_google_fonts = new NF5_googlefonts();
											$output .= $get_google_fonts->get_google_fonts();
								$output .= '</select>';
							$output .= '</div>';*/
							
							
							
							
							$output .= '<div class="field-setting col-xs-5 s-all">';
								$output .= '<small>'.__('Background','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
									
									
									
									
									/*$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Color','nex-forms').'</span>';
									$output .= '</span>';*/
									$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false"><input type="text" class="form-control bc-bg-color" name="bc-bg-color" id="bs-color"></span>';
								
									
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Shadow','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn bc-drop-shadow shadow-light" title="'.__('Light Shadow','nex-forms').'"><span class="shadow-light"></span></span>';
									$output .= '<span class="input-group-addon action-btn bc-drop-shadow shadow-dark" title="'.__('Dark Shadow','nex-forms').'"><span class="shadow-dark"></span></span>';
									
									
									
									
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-7 s-all">';
								$output .= '<small>'.__('Border Styling','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
									
									
									
									
									
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Border Color','nex-forms').'</span>';
										$output .= '</span>';
									
									$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input type="text" class="form-control bc-brd-color" name="bc-brd-color" id="bs-color"></span>';
								
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Width','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<input name="bc-brd-size" id="bc-brd-size" class="form-control" value="1">';
									$output .= '<span class="input-group-addon">';
									
									$output .= '<span class="icon-text">'.__('Radius','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<input name="bc-brd-radius" id="bc-brd-radius" class="form-control" value="0">';
									
									
									
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Margins','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="bc_margin_left" id="bc_margin_left" class="form-control" value="0">';
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="bc_margin_right" id="bc_margin_right" class="form-control" value="0">';
									

								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="bc_margin_top" id="bc_margin_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="bc_margin_bottom" id="bc_margin_bottom" class="form-control" value="15">';
									
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Padding','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="bc_padding_left" id="bc_padding_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="bc_padding_right" id="bc_padding_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="bc_padding_top" id="bc_padding_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="bc_padding_bottom" id="bc_padding_bottom" class="form-control" value="0">';
									
								
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Breadcrumb Background Image','nex-forms').'</span></div>';
							$output .= '</div>';
							/*** Background settings ***/
							$output .= '<div class="field-setting col-xs-12 s-all">';	
								$output .= '<div class="setting-bc-bg-image ">';						
									//$output .= '<small>'.__('Background Image Settings','nex-forms').'</small>';
									$output .= '<div role="toolbar" class="btn-toolbar bg-settings">';
	/*** Background image ***/									
										$output .= '<div role="group" class="btn-group image-preview">';
											$output .= '<small>'.__('Image','nex-forms').'</small>';
											$output .= '<form name="do-upload-bc-image" id="do-upload-bc-image" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">';
												$output .= '<input type="hidden" name="action" value="do_upload_image">';
												$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">';
													$output .= '<div class="the_input_element fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100px; height: 100px;"></div>';
													$output .= '<div class="upload-image-controls">';
														$output .= '<span class="input-group-addon btn-file the_input_element error_message" data-content="'.__('Please select an image','nex-forms').'" data-secondary-message="'.__('Invalid image extension','nex-forms').'" data-placement="top">';
															$output .= '<span class="fileinput-new"><span class="fa fa-cloud-upload"></span></span>';
															$output .= '<span class="fileinput-exists"><span class="fa fa-edit"></span></span>';
															$output .= '<input type="file" name="do_image_upload_preview" >';
														$output .= '</span>';
														$output .= '<a href="#" class="input-group-addon fileinput-exists" data-dismiss="fileinput"><span class="fa fa-close"></span></a>';
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</form>';
										$output .= '</div>';
	/*** Background size ***/									
										$output .= '<div role="group" class="btn-group bc-bg-size">';
											$output .= '<small>'.__('Size','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto" type="button" title="'.__('Auto','nex-forms').'"><i class="btn-tx">Auto</i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf contain" type="button" title="'.__('Contain','nex-forms').'"><i class="fa fa-compress"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf cover" type="button" title="'.__('Cover','nex-forms').'"><i class="fa fa-expand"></i></button>';
										$output .= '</div>';
	/*** Background repeat ***/									
										$output .= '<div role="group" class="btn-group bc-bg-repeat">';
											$output .= '<small>'.__('Repeat','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat" type="button" title="'.__('Repeat X &amp; Y','nex-forms').'"><i class="fa fa-arrows"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-x" type="button" title="'.__('Repeat X','nex-forms').'"><i class="fa fa-arrows-h"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-y" type="button" title="'.__('Repeat Y','nex-forms').'"><i class="fa fa-arrows-v"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no-repeat" type="button" title="'.__('None','nex-forms').'"><i class="fa fa-remove"></i></button>';
										$output .= '</div>';
	/*** Background position ***/									
										$output .= '<div role="group" class="btn-group bc-bg-position">';
											$output .= '<small>'.__('Position','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
										$output .= '</div>';
									
									$output .= '</div>';
								$output .= '</div>';
							
							
							
							
							
							
							$output .= '</div>';
						
						
						
						
						
						
						
							
							
						$output .= '</div>';
						
						
						
						
						
						
						
						
						
						
						$add_timer				= (isset($bc_settings['0']['add_timer'])) ? $bc_settings['0']['add_timer'] 						: 'no';
						$timer_add_to			= (isset($bc_settings['0']['timer_add_to'])) ? $bc_settings['0']['timer_add_to'] 					: 'header';
						
						$timer_type				= (isset($bc_settings['0']['timer_type'])) ? $bc_settings['0']['timer_type'] 						: 'overall';
						
						$timer_size				= (isset($bc_settings['0']['timer_size'])) ? $bc_settings['0']['timer_size'] 						: 'small';
						
						$timer_animation		= (isset($bc_settings['0']['timer_animation'])) ? $bc_settings['0']['timer_animation'] 			: 'smooth';
						
						
						
						$timer_position			= (isset($bc_settings['0']['timer_position'])) ? $bc_settings['0']['timer_position'] 				: 'timer_inline';
						$timer_align			= (isset($bc_settings['0']['timer_align'])) ? $bc_settings['0']['timer_align'] 					: 'timer_right';
						
						$enabled_units			= (isset($bc_settings['0']['enabled_units'])) ? $bc_settings['0']['enabled_units'] 				: 'minutes,seconds';
						$timer_hours			= (isset($bc_settings['0']['timer_hours'])) ? $bc_settings['0']['timer_hours'] 					: 0;
						$timer_minutes			= (isset($bc_settings['0']['timer_minutes'])) ? $bc_settings['0']['timer_minutes'] 				: 0;
						$timer_seconds			= (isset($bc_settings['0']['timer_seconds'])) ? $bc_settings['0']['timer_seconds'] 				: 30;
						
						
						$timer_hours_label			= (isset($bc_settings['0']['timer_hours_label'])) ? $bc_settings['0']['timer_hours_label'] 				: '';
						$timer_minutes_label		= (isset($bc_settings['0']['timer_minutes_label'])) ? $bc_settings['0']['timer_minutes_label'] 			: '';
						$timer_seconds_label		= (isset($bc_settings['0']['timer_seconds_label'])) ? $bc_settings['0']['timer_seconds_label'] 			: '';
						
						
						$timer_hours_color			= (isset($bc_settings['0']['timer_hours_color'])) ? $bc_settings['0']['timer_hours_color'] 				: '#2979FF';
						$timer_minutes_color		= (isset($bc_settings['0']['timer_minutes_color'])) ? $bc_settings['0']['timer_minutes_color'] 			: '#00bcd4';
						$timer_seconds_color		= (isset($bc_settings['0']['timer_seconds_color'])) ? $bc_settings['0']['timer_seconds_color'] 			: '#40C4FF';
						
						
						$timer_direction		= (isset($bc_settings['0']['timer_direction'])) ? $bc_settings['0']['timer_direction'] 			: 'clockwise';
						
						$timer_text_color				= (isset($bc_settings['0']['timer_text_color'])) ? $bc_settings['0']['timer_text_color'] 						: '#888';
						$timer_inner_circle_color		= (isset($bc_settings['0']['timer_inner_circle_color'])) ? $bc_settings['0']['timer_inner_circle_color'] 		: '#aaa';
						
						$timer_bg_width			= (isset($bc_settings['0']['timer_bg_width'])) ? $bc_settings['0']['timer_bg_width'] 	: '0.1';
						$timer_fg_width			= (isset($bc_settings['0']['timer_fg_width'])) ? $bc_settings['0']['timer_fg_width'] 	: '0.05';
						
						$timer_start			= (isset($bc_settings['0']['timer_start'])) ? $bc_settings['0']['timer_start'] 	: '1';
						$timer_end				= (isset($bc_settings['0']['timer_end'])) ? $bc_settings['0']['timer_end'] 	: '0';
						
						
						$enabled_units_array = explode(',',$enabled_units);
						
						$output .= '<div id="ms-timer-settings-panel" class="ms-settings row settings-section" style="display:none;">';
						
						
								//$output .= '<div class="field-setting col-xs-12 s-all">';	
								//	$output .= '<div class="settings-header"><span>'.__('Timer Settings','nex-forms').'</span></div>';
								//$output .= '</div>';
								
								
								$output .= '<div class="field-setting col-xs-3 s-all" >';	
									$output .= '<div role="group" class="btn-group add-timer" data-value="'.$add_timer.'">';
										$output .= '<small>'.__('Add Timer','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf yes '.(($add_timer=='yes') ? 'active' : '' ).'" type="button" title="'.__('Adds a timer to the form','nex-forms').'"><i class="fa fa-check"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no '.(($add_timer=='no') ? 'active' : '' ).'" type="button" title="'.__('No time limit','nex-forms').'"><i class="fa fa-close"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-3 s-all">';
									$output .= '<div role="group" class="btn-group timer-add-to" data-value="'.$timer_add_to.'">';
												$output .= '<small>'.__('Add To','nex-forms').'</small>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf header 		'.(($timer_add_to=='header') ? 'active' : '' ).' " 		type="button" title="'.__('Add to Form Header','nex-forms').'"><i class="fa fa-window-maximize"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf footer 		'.(($timer_add_to=='footer') ? 'active' : '' ).'" 	type="button" title="'.__('Add to Form Footer','nex-forms').'"><i style="transform:rotate(180deg);" class="fa fa-window-maximize"></i></button>';
											$output .= '</div>';
								$output .= '</div>';	
								
								$output .= '<div class="field-setting col-xs-3 s-all">';
									$output .= '<div role="group" class="btn-group timer-position" data-value="'.$timer_position.'">';
												$output .= '<small>'.__('Position','nex-forms').'</small>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf timer_inline	'.(($timer_position=='timer_inline') ? 'active' : '' ).' " 	type="button" title="'.__('Inline with Breadcrumb','nex-forms').'"><i class="fa fa-arrows-h"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf timer_top 		'.(($timer_position=='timer_top') ? 'active' : '' ).'" 			type="button" title="'.__('Top of Breadcrumb','nex-forms').'"><i class="fa fa-arrow-up"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf timer_bottom 	'.(($timer_position=='timer_bottom') ? 'active' : '' ).'" 	type="button" title="'.__('Bottom of Breadcrumb','nex-forms').'"><i class="fa fa-arrow-down"></i></button>';
											$output .= '</div>';
								$output .= '</div>';	
								$output .= '<div class="field-setting col-xs-3 s-all">';
									$output .= '<div role="group" class="btn-group timer-align" data-value="'.$timer_align.'">';
												$output .= '<small>'.__('Alignment','nex-forms').'</small>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf timer_left 		'.(($timer_align=='timer_left') ? 'active' : '' ).' " 		type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf timer_center 	'.(($timer_align=='timer_center') ? 'active' : '' ).'" 	type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf timer_right 	'.(($timer_align=='timer_right') ? 'active' : '' ).'" 		type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
											$output .= '</div>';
								$output .= '</div>';			
								
								$output .= '<div class="field-setting col-xs-5 s-all" >';	
									$output .= '<div role="group" class="btn-group timer-type" data-value="'.$timer_type.'">';
										$output .= '<small>'.__('Set timer on','nex-forms').'</small>';
										$output .= '<button class="btn btn-default text-btn waves-effect-nf waves-light-nf overall '.(($timer_type=='overall') ? 'active' : '' ).'" type="button" title="'.__('Adds a time limit to the form overall','nex-forms').'">'.__('Overall Form','nex-forms').'</button>';
										$output .= '<button class="btn btn-default text-btn waves-effect-nf waves-light-nf per_step '.(($timer_type=='per_step') ? 'active' : '' ).'" type="button" title="'.__('Adds a time limit each step','nex-forms').'">'.__('Individual Steps','nex-forms').'</button>';
									$output .= '</div>';
								$output .= '</div>';
						
								
								$output .= '<div class="field-setting col-xs-3 s-all" >';	
										$output .= '<small>'.__('Start timer on','nex-forms').'</small>';
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<span class="input-group-addon">';
												$output .= '<span class="icon-text">Step</span>';
											$output .= '</span>';
											$output .= '<input name="timer_start" id="timer_start" class="form-control" value="'.$timer_start.'">';
										$output .= '</div>';
								$output .= '</div>';
								$output .= '<div class="field-setting col-xs-1 s-all" >';	
								$output .= '</div>';
								$output .= '<div class="field-setting col-xs-3 s-all" >';	
										$output .= '<small>'.__('End timer after','nex-forms').'</small>';
										$output .= '<div class="input-group input-group-sm">';	
											$output .= '<span class="input-group-addon">';
												$output .= '<span class="icon-text">Step</span>';
											$output .= '</span>';
											$output .= '<input name="timer_end" id="timer_end" class="form-control" value="'.$timer_end.'">';
										$output .= '</div>';
								$output .= '</div>';
								
								
								
								$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
									$output .= '<div class="settings-header"><span>'.__('Overall Timer Settings','nex-forms').'</span></div>';
								$output .= '</div>';
								
								
								$output .= '<div class="field-setting col-xs-4 s-all">';
										$output .= '<div class="btn-group enabled_units" data-value="'.$enabled_units.'">';
										$output .= '<small>'.__('Show Units','nex-forms').'</small>';
										
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm '.((in_array('hours',$enabled_units_array)) ? 'active' : '').' units_0" data-val="hours" type="button"><i class="btn-tx">Hrs</i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm '.((in_array('minutes',$enabled_units_array)) ? 'active' : '').' units_1" data-val="minutes" type="button"><i class="btn-tx">Mns</i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm '.((in_array('seconds',$enabled_units_array)) ? 'active' : '').' units_2" data-val="seconds" type="button"><i class="btn-tx">Scs</i></button>';
										//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active" data-val="00" type="button"><i class="btn-tx">00</i></button>';
										$output .= '</div>';
								$output .= '</div>';
								
								
								$output .= '<div class="field-setting col-xs-8 s-all">';
									$output .= '<small>'.__('Set Timer','nex-forms').'</small>';
										
									$output .= '<div class="input-group input-group-sm">';	
										//HOURS
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Hours</span>';
										$output .= '</span>';
										$output .= '<input name="timer_hours" id="timer_hours" class="form-control" value="'.$timer_hours.'">';
										//MINUTES
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Minutes</span>';
										$output .= '</span>';
										$output .= '<input name="timer_minutes" id="timer_minutes" class="form-control" value="'.$timer_minutes.'">';
										//SECONDS
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Seconds</span>';
										$output .= '</span>';
										$output .= '<input name="timer_seconds" id="timer_seconds" class="form-control" value="'.$timer_seconds.'">';
										
										
	
									$output .= '</div>';
								$output .= '</div>';
								
								
								
								$output .= '<div class="field-setting col-xs-5 s-all" >';	
									$output .= '<div role="group" class="btn-group timer-size" data-value="'.$timer_size.'">';
										$output .= '<small>'.__('Size','nex-forms').'</small>';
										$output .= '<button class="btn btn-default btn-sm waves-effect-nf waves-light-nf extra-small 	'.(($timer_size=='extra-small') ? 'active' : '' ).'" type="button" title="'.__('Extra Small','nex-forms').'"><i class="btn-tx">'.__('Xs','nex-forms').'</i></button>';
										$output .= '<button class="btn btn-default btn-sm waves-effect-nf waves-light-nf small 			'.(($timer_size=='small') ? 'active' : '' ).'" type="button" title="'.__('Small','nex-forms').'"><i class="btn-tx">'.__('S','nex-forms').'</button></i>';
										$output .= '<button class="btn btn-default btn-sm waves-effect-nf waves-light-nf normal 			'.(($timer_size=='normal') ? 'active' : '' ).'" type="button" title="'.__('Small Medium','nex-forms').'"><i class="btn-tx">'.__('Sm','nex-forms').'</i></button>';
										$output .= '<button class="btn btn-default btn-sm waves-effect-nf waves-light-nf large 			'.(($timer_size=='large') ? 'active' : '' ).'" type="button" title="'.__('Large','nex-forms').'"><i class="btn-tx">'.__('L','nex-forms').'</button></i>';
										$output .= '<button class="btn btn-default btn-sm waves-effect-nf waves-light-nf extra-large 	'.(($timer_size=='extra-large') ? 'active' : '' ).'" type="button" title="'.__('Extra Large','nex-forms').'"><i class="btn-tx">'.__('Xl','nex-forms').'</i></button>';
										//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf super-size 		'.(($timer_size=='super-size') ? 'active' : '' ).'" type="button" title="'.__('Super Size','nex-forms').'">'.__('Sz','nex-forms').'</button>';
									$output .= '</div>';
								$output .= '</div>'; 
								
								
								$output .= '<div class="field-setting col-xs-3 s-all" >';	
									$output .= '<div role="group" class="btn-group timer-animation" data-value="'.$timer_animation.'">';
										$output .= '<small>'.__('Animation','nex-forms').'</small>';
										$output .= '<button class="btn btn-default text-btn waves-effect-nf waves-light-nf smooth '.(($timer_animation=='smooth') ? 'active' : '' ).'" type="button" title="'.__('Smooth Transition','nex-forms').'">Smooth</button>';
										$output .= '<button class="btn btn-default text-btn waves-effect-nf waves-light-nf ticks '.(($timer_animation=='ticks') ? 'active' : '' ).'" type="button" title="'.__('Ticking Transition','nex-forms').'">Ticks</button>';
									$output .= '</div>';
								$output .= '</div>';
								$output .= '<div class="field-setting col-xs-1 s-all" >';
								$output .= '</div>';
								
								
								$output .= '<div class="field-setting col-xs-3 s-all" >';	
									$output .= '<div role="group" class="btn-group timer-direction" data-value="'.$timer_direction.'">';
										$output .= '<small>'.__('Direction','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf anti-clockwise '.(($timer_direction=='anti-clockwise') ? 'active' : '' ).'" type="button" title="'.__('Clockwise','nex-forms').'"><i style="transform:rotate(270deg);" class="fa fa-redo"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf clockwise '.(($timer_direction=='clockwise') ? 'active' : '' ).'" type="button" title="'.__('Anti-Clockwise','nex-forms').'"><i style="transform:rotate(90deg);" class="fa fa-undo"></i></button>';
										
									$output .= '</div>';
								$output .= '</div>';
								
								
								
								$output .= '<div class="field-setting col-xs-4 s-all" >';	
									//$output .= '<div role="group" class="btn-group">';
										$output .= '<small>Labels</small>';
										
										//HOURS
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Color</span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input name="timer_text_color" id="timer_text_color" class="form-control" value="'.$timer_text_color.'"></span>';
										
										$output .= '<span class="input-group-addon action-btn timer-bold" title="'.__('Bold','nex-forms').'">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon action-btn timer-italic" title="'.__('Italic','nex-forms').'">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
										
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-8 s-all">';
									$output .= '<small>&nbsp;</small>';
										
									$output .= '<div class="input-group input-group-sm">';	
										//HOURS
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Hours</span>';
										$output .= '</span>';
										$output .= '<input name="timer_hours_label" id="timer_hours_label" class="form-control" value="'.$timer_hours_label.'">';
										//MINUTES
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Minutes</span>';
										$output .= '</span>';
										$output .= '<input name="timer_minutes_label" id="timer_minutes_label" class="form-control" value="'.$timer_minutes_label.'">';
										//SECONDS
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Seconds</span>';
										$output .= '</span>';
										$output .= '<input name="timer_seconds_label" id="timer_seconds_label" class="form-control" value="'.$timer_seconds_label.'">';
										
										
	
									$output .= '</div>';
								$output .= '</div>';
								
								
								$output .= '<div class="field-setting col-xs-4 s-all" >';	
									//$output .= '<div role="group" class="btn-group">';
										$output .= '<small>Inner Circle</small>';
										
										$output .= '<div class="input-group input-group-sm">';	
											/*$output .= '<span class="input-group-addon">';
												$output .= '<span class="icon-text">Color</span>';
											$output .= '</span>';*/
											$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input name="timer_inner_circle_color" id="timer_inner_circle_color" class="form-control" value="'.$timer_inner_circle_color.'"></span>';
											
											//BG Width
											$output .= '<span class="input-group-addon">';
												$output .= '<span class="icon-text">Width</span>';
											$output .= '</span>';
											$output .= '<input name="timer_bg_width" id="timer_bg_width" class="form-control" value="'.$timer_bg_width.'">';
											
										$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-8 s-all">';
									$output .= '<small>'.__('Time Remaining Circles','nex-forms').'</small>';
										
									$output .= '<div class="input-group input-group-sm">';	
										//HOURS
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Hours</span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input name="timer_hours_color" id="timer_hours_color" class="form-control" value="'.$timer_hours_color.'"></span>';
										//MINUTES
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Minutes</span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input name="timer_minutes_color" id="timer_minutes_color" class="form-control" value="'.$timer_minutes_color.'"></span>';
										//SECONDS
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Seconds</span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input name="timer_seconds_color" id="timer_seconds_color" class="form-control" value="'.$timer_seconds_color.'"></span>';
										
										//FG Width
											$output .= '<span class="input-group-addon">';
												$output .= '<span class="icon-text">Width</span>';
											$output .= '</span>';
											$output .= '<input name="timer_fg_width" id="timer_fg_width" class="form-control" value="'.$timer_fg_width.'">';
										
	
									$output .= '</div>';
								$output .= '</div>';
								
								
						
						
						
						
						$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Timer Wrapper Styling','nex-forms').'</span></div>';
							$output .= '</div>';
							
							
							/*$output .= '<div class="field-setting col-xs-12 s-all">';
								$output .= '<small>'.__('Overall Font','nex-forms').'</small>';
								$output .= '<select name="google_fonts_overall" id="google_fonts_overall" data-selected="'.$overall_font.'" class="sfm form-control"><option value="">'.__('-- Select Google Font --','nex-forms').'</option><option value="">'.__('Default','nex-forms').'</option>';
											$get_google_fonts = new NF5_googlefonts();
											$output .= $get_google_fonts->get_google_fonts();
								$output .= '</select>';
							$output .= '</div>';*/
							
							
							
							
							$output .= '<div class="field-setting col-xs-5 s-all">';
								$output .= '<small>'.__('Background','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
									
									
									
									
									/*$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Color','nex-forms').'</span>';
									$output .= '</span>';*/
									$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false"><input type="text" class="form-control timer-bg-color" name="timer-bg-color" id="bs-color"></span>';
								
									
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Shadow','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn timer-drop-shadow shadow-light" title="'.__('Light Shadow','nex-forms').'"><span class="shadow-light"></span></span>';
									$output .= '<span class="input-group-addon action-btn timer-drop-shadow shadow-dark" title="'.__('Dark Shadow','nex-forms').'"><span class="shadow-dark"></span></span>';
									
									
									
									
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-7 s-all">';
								$output .= '<small>'.__('Border Styling','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
									
									
									
									
									
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Border Color','nex-forms').'</span>';
										$output .= '</span>';
									
									$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input type="text" class="form-control timer-brd-color" name="timer-brd-color" id="bs-color"></span>';
								
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Width','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<input name="timer-brd-size" id="timer-brd-size" class="form-control" value="1">';
									$output .= '<span class="input-group-addon">';
									
									$output .= '<span class="icon-text">'.__('Radius','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<input name="timer-brd-radius" id="timer-brd-radius" class="form-control" value="0">';
									
									
									
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Margins','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="timer_margin_left" id="timer_margin_left" class="form-control" value="0">';
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="timer_margin_right" id="timer_margin_right" class="form-control" value="0">';
									

								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="timer_margin_top" id="timer_margin_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="timer_margin_bottom" id="timer_margin_bottom" class="form-control" value="15">';
									
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Padding','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="timer_padding_left" id="timer_padding_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="timer_padding_right" id="timer_padding_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="timer_padding_top" id="timer_padding_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="timer_padding_bottom" id="timer_padding_bottom" class="form-control" value="0">';
									
								
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							
							
							
							
							
						 $output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Timer Wrapper Background Image','nex-forms').'</span></div>';
							$output .= '</div>';
							/*** Background settings ***/
							$output .= '<div class="field-setting col-xs-12 s-all">';	
								$output .= '<div class="setting-timer-bg-image ">';						
									//$output .= '<small>'.__('Background Image Settings','nex-forms').'</small>';
									$output .= '<div role="toolbar" class="btn-toolbar bg-settings">';
	/*** Background image ***/									
										$output .= '<div role="group" class="btn-group image-preview">';
											$output .= '<small>'.__('Image','nex-forms').'</small>';
											$output .= '<form name="do-upload-timer-image" id="do-upload-timer-image" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">';
												$output .= '<input type="hidden" name="action" value="do_upload_image">';
												$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">';
													$output .= '<div class="the_input_element fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100px; height: 100px;"></div>';
													$output .= '<div class="upload-image-controls">';
														$output .= '<span class="input-group-addon btn-file the_input_element error_message" data-content="'.__('Please select an image','nex-forms').'" data-secondary-message="'.__('Invalid image extension','nex-forms').'" data-placement="top">';
															$output .= '<span class="fileinput-new"><span class="fa fa-cloud-upload"></span></span>';
															$output .= '<span class="fileinput-exists"><span class="fa fa-edit"></span></span>';
															$output .= '<input type="file" name="do_image_upload_preview" >';
														$output .= '</span>';
														$output .= '<a href="#" class="input-group-addon fileinput-exists" data-dismiss="fileinput"><span class="fa fa-close"></span></a>';
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</form>';
										$output .= '</div>';
	/*** Background size ***/									
										$output .= '<div role="group" class="btn-group timer-bg-size">';
											$output .= '<small>'.__('Size','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto" type="button" title="'.__('Auto','nex-forms').'"><i class="btn-tx">Auto</i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf contain" type="button" title="'.__('Contain','nex-forms').'"><i class="fa fa-compress"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf cover" type="button" title="'.__('Cover','nex-forms').'"><i class="fa fa-expand"></i></button>';
										$output .= '</div>';
	/*** Background repeat ***/									
										$output .= '<div role="group" class="btn-group timer-bg-repeat">';
											$output .= '<small>'.__('Repeat','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat" type="button" title="'.__('Repeat X &amp; Y','nex-forms').'"><i class="fa fa-arrows"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-x" type="button" title="'.__('Repeat X','nex-forms').'"><i class="fa fa-arrows-h"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-y" type="button" title="'.__('Repeat Y','nex-forms').'"><i class="fa fa-arrows-v"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no-repeat" type="button" title="'.__('None','nex-forms').'"><i class="fa fa-remove"></i></button>';
										$output .= '</div>';
	/*** Background position ***/									
										$output .= '<div role="group" class="btn-group timer-bg-position">';
											$output .= '<small>'.__('Position','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
										$output .= '</div>';
									
									$output .= '</div>';
								$output .= '</div>';
							
							
							
							
							
							
							$output .= '</div>';
						
						
						
						
						
								
								
							
								
						$output .= '</div>';
						
						
						
						
						
						
						
						$output .= '<div class="setting-buffer"></div>';	
					$output .= '</div>';
				$output .= '</div>';
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			$output .= '<div class="overall-settings-column overall-form-styling-column settings-column-style '.$set_current_theme.' right_hand_col">';
			
					$output .= '
					<div id="close-settings" class="close-area">
						<span class="fa fa-close"></span>
					</div>
					';
						
						$output .= '<div class="material_box_head aa_bg_main"><span class="fas fa-palette"></span>'.__('Overall Styling','nex-forms').'</div>';
						
						$output .= '<div class="overall-setting-categories field-setting-categories-style">';
							
							
							$output .= '<nav class="nav-extended settings_tabs_nf">
									<div class="nav-content aa_bg_main">
									  <ul class="tabs_nf tabs_nf-transparent sec-menu aa_menu">
									  	<li id="form-settings" class="tab always_current"><a class="active" href="#form-settings-panel"><span class="editor-form">'.__('Form Wrapper','nex-forms').'</span><span class="editor-msg" style="display:none;">'.__('Success Messsage','nex-forms').'</span></a></li>
										<li id="overall-fields-styling" class="tab always_current form-editor-only"><a href="#overall-fields-styling-panel">'.__('Fields','nex-forms').'</a></li>
										<li id="custom-css-settings" class="tab always_current form-editor-only"><a href="#custom-css-settings-panel">'.__('Custom CSS','nex-forms').'</a></li>
										<li id="submit-loader-settings" class="tab always_current msg-editor-only"><a href="#submit-loader-settings-panel">'.__('Loader','nex-forms').'</a></li>
									  </ul>
									</div>
								 </nav>';
						$output .= '</div>';
					
					
						$output .= '<div class="inner">';
//LABEL SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
						
						
						
						$output .= '<div id="form-settings-panel" class="form-settings row settings-section active">';
							
							/*$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<small>'.__('Theme','nex-forms').'</small>';
								$output .= '<select name="set_form_theme" class="form-control set_form_theme" data-selected="'.$set_form_theme.'">
												<option value="bootstrap">'.__('Bootstrap','nex-forms').'</option>
												<option value="m_design">'.__('Material Design','nex-forms').'</option>
												<option value="neumorphism">'.__('Nuemorphism','nex-forms').'</option>
												<option value="jquery_ui">'.__('jQuery UI','nex-forms').'</option>
												<option value="browser">'.__('Browser Default','nex-forms').'</option>
											</select>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<small>'.__('Color Scheme','nex-forms').'</small>';
								$disabled = 'disabled="disabled"';
								if(is_plugin_active( 'nex-forms-themes-add-on7/main.php' ) || is_plugin_active( 'nex-forms-themes-add-on/main.php' ))
									$disabled = '';
									
									$output .= '<select name="md_theme_selection" class="form-control md_theme_selection '.(($set_form_theme!='m_design') ? 'hidden' : '').'" data-selected="'.$set_theme.'">
										<option  value="default" 		'.(($set_theme=='default' || !$set_theme) ? 'selected="selected"' : '').' >'.__('--- Color Scheme ---','nex-forms').'</option>
										<option  value="default" 		'.(($set_theme=='default' || !$set_theme) ? 'selected="selected"' : '').'>'.__('Default','nex-forms').'</option>
										<option '.$disabled.' value="red" 			'.(($set_theme=='red') ? 'selected="selected"' : '').'>'.__('Red','nex-forms').'</option>
										<option '.$disabled.' value="pink"			'.(($set_theme=='pink') ? 'selected="selected"' : '').'>'.__('Pink','nex-forms').'</option>
										<option '.$disabled.' value="purple"			'.(($set_theme=='purple') ? 'selected="selected"' : '').'>'.__('Purple','nex-forms').'</option>
										<option '.$disabled.' value="deep-purple"		'.(($set_theme=='deep-purple') ? 'selected="selected"' : '').'>'.__('Deep Purple','nex-forms').'</option>
										<option '.$disabled.' value="indigo"			'.(($set_theme=='indigo') ? 'selected="selected"' : '').'>'.__('Indigo','nex-forms').'</option>
										<option '.$disabled.' value="blue"			'.(($set_theme=='blue') ? 'selected="selected"' : '').'>'.__('Blue','nex-forms').'</option>
										<option '.$disabled.' value="light-blue"		'.(($set_theme=='light-blue') ? 'selected="selected"' : '').'>'.__('Light Blue','nex-forms').'</option>
										<option '.$disabled.' value="cyan"			'.(($set_theme=='cyan') ? 'selected="selected"' : '').'>'.__('Cyan','nex-forms').'</option>
										<option '.$disabled.' value="teal"			'.(($set_theme=='teal') ? 'selected="selected"' : '').'>'.__('Teal','nex-forms').'</option>
										<option '.$disabled.' value="green"			'.(($set_theme=='green') ? 'selected="selected"' : '').'>'.__('Green','nex-forms').'</option>
										<option '.$disabled.' value="light-green"		'.(($set_theme=='light-green') ? 'selected="selected"' : '').'>'.__('Light Green','nex-forms').'</option>
										<option '.$disabled.' value="lime"			'.(($set_theme=='lime') ? 'selected="selected"' : '').'>'.__('Lime','nex-forms').'</option>
										<option '.$disabled.' value="yellow"			'.(($set_theme=='yellow') ? 'selected="selected"' : '').'>'.__('Yellow','nex-forms').'</option>
										<option '.$disabled.' value="amber"			'.(($set_theme=='amber') ? 'selected="selected"' : '').'>'.__('Amber','nex-forms').'</option>
										<option '.$disabled.' value="orange"			'.(($set_theme=='orange') ? 'selected="selected"' : '').'>'.__('Orange','nex-forms').'</option>
										<option '.$disabled.' value="brown"			'.(($set_theme=='brown') ? 'selected="selected"' : '').'>'.__('Brown','nex-forms').'</option>
										<option '.$disabled.' value="gray"			'.(($set_theme=='gray') ? 'selected="selected"' : '').'>'.__('Gray','nex-forms').'</option>
										<option '.$disabled.' value="blue-gray"		'.(($set_theme=='blue-gray') ? 'selected="selected"' : '').'>'.__('Blue Gray','nex-forms').'</option>
									</select> ';
							
									$output .= '<select name="choose_form_theme" class="form-control choose_form_theme '.(($set_form_theme=='m_design') ? 'hidden' : '').'" data-selected="'.$set_jq_theme.'">
												<option  value="default" selected="selected">'.__('--- Color Scheme ---','nex-forms').'</option>
												<option  value="default">Default</option>
												<option '.$disabled.' value="black-tie">'.__('black-tie','nex-forms').'</option>
												<option '.$disabled.' value="cupertino">'.__('cupertino','nex-forms').'</option>
												<option '.$disabled.' value="dark-hive">'.__('dark-hive','nex-forms').'</option>
												<option '.$disabled.' value="dot-luv">'.__('dot-luv','nex-forms').'</option>
												<option '.$disabled.' value="eggplant">'.__('eggplant','nex-forms').'</option>
												<option '.$disabled.' value="excite-bike">'.__('excite-bike','nex-forms').'</option>
												<option '.$disabled.' value="flick">'.__('flick','nex-forms').'</option>
												<option '.$disabled.' value="hot-sneaks">'.__('hot-sneaks','nex-forms').'</option>
												<option '.$disabled.' value="humanity">'.__('humanity','nex-forms').'</option>
												<option '.$disabled.' value="le-frog">'.__('le-frog','nex-forms').'</option>
												<option '.$disabled.' value="mint-choc">'.__('mint-choc','nex-forms').'</option>
												<option '.$disabled.' value="overcast">'.__('overcast','nex-forms').'</option>
												<option '.$disabled.' value="pepper-grinder">'.__('pepper-grinder','nex-forms').'</option>
												<option '.$disabled.' value="redmond">'.__('redmond','nex-forms').'</option>
												<option '.$disabled.' value="smoothness">'.__('smoothness','nex-forms').'</option>
												<option '.$disabled.' value="south-street">'.__('south-street','nex-forms').'</option>
												<option '.$disabled.' value="start">'.__('start','nex-forms').'</option>
												<option '.$disabled.' value="sunny">'.__('sunny','nex-forms').'</option>
												<option '.$disabled.' value="swanky-purse">'.__('swanky-purse','nex-forms').'</option>
												<option '.$disabled.' value="trontastic">'.__('trontastic','nex-forms').'</option>							
												<option '.$disabled.' value="ui-darkness">'.__('ui-darkness','nex-forms').'</option>
												<option '.$disabled.' value="ui-lightness">'.__('ui-lightness','nex-forms').'</option>
												<option '.$disabled.' value="vader">'.__('vader','nex-forms').'</option>
											</select>
									';
							$output .= '</div>';
							
							*/
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 msg-editor-only s-all">';	
								$output .= '<div class="settings-header"><span>'.__('On Form Submission','nex-forms').'</span></div>'; 
							$output .= '</div>';
							
							
							$msg_hide_form		= (isset($theme_settings['0']['msg_hide_form'])) ? $theme_settings['0']['msg_hide_form'] 	: 'yes';
							$msg_position		= (isset($theme_settings['0']['msg_position'])) ? $theme_settings['0']['msg_position'] 	: 'top';
							$msg_placement		= (isset($theme_settings['0']['msg_placement'])) ? $theme_settings['0']['msg_placement'] 	: 'outside';
							
							
							$output .= '<div class="field-setting col-xs-4 s-all msg-editor-only">';
								$output .= '<div role="group" class="btn-group msg_hide_form" data-value="'.$msg_hide_form.'">';
									$output .= '<small>'.__('Hide Form','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf yes '.(($msg_hide_form=='yes') ? 'active' : '' ).'" type="button" title="'.__('Hide Form','nex-forms').'"><i class="fa fa-check"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no '.(($msg_hide_form=='no') ? 'active' : '' ).'" type="button" title="'.__('Dont Hide Form','nex-forms').'"><i class="fa fa-close"></i></button>';
									//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf position_both '.(($bc_position=='both') ? 'active' : '' ).'" type="button" title="'.__('Display top and <br />bottom of the Form','nex-forms').'"><i class="fa fa-arrows-v"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-4 s-all msg-editor-only">';
								$output .= '<div role="group" class="btn-group msg_position" data-value="'.$msg_position.'">';
									$output .= '<small>'.__('Show','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf top '.(($msg_position=='top') ? 'active' : '' ).'" type="button" title="'.__('Show above the Form','nex-forms').'"><i class="fa fa-window-maximize"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf bottom '.(($msg_position=='bottom') ? 'active' : '' ).'" type="button" title="'.__('Show below the Form','nex-forms').'"><i style="transform:rotate(180deg);" class="fa fa-window-maximize"></i></button>';
									//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf position_both '.(($bc_position=='both') ? 'active' : '' ).'" type="button" title="'.__('Display top and <br />bottom of the Form','nex-forms').'"><i class="fa fa-arrows-v"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all msg-editor-only">';
								$output .= '<div role="group" class="btn-group msg_placement" data-value="'.$msg_placement.'">';
									$output .= '<small>'.__('Placement','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf outside '.(($msg_placement=='outside') ? 'active' : '' ).'" type="button" title="'.__('Show Outside the<br />Form Wrapper','nex-forms').'"><i class="fa fa-sign-out-alt"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf inside '.(($msg_placement=='inside') ? 'active' : '' ).'" type="button" title="'.__('Show Inside the<br />Form Wrapper','nex-forms').'"><i class="fa fa-sign-in-alt"></i></button>';
									//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf position_both '.(($bc_position=='both') ? 'active' : '' ).'" type="button" title="'.__('Display top and <br />bottom of the Form','nex-forms').'"><i class="fa fa-arrows-v"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 msg-editor-only s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Message Wrapper Styling','nex-forms').'</span></div>'; 
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-12 s-all">';
								$output .= '<small>'.__('Overall Font','nex-forms').'</small>';
								$output .= '<select name="google_fonts_overall" id="google_fonts_overall" data-selected="'.$overall_font.'" class="sfm form-control"><option value="">'.__('-- Select Google Font --','nex-forms').'</option><option value="">'.__('Default','nex-forms').'</option>';
											$get_google_fonts = new NF5_googlefonts();
											$output .= $get_google_fonts->get_google_fonts();
								$output .= '</select>';
							$output .= '</div>';
							
							
							
							
							$output .= '<div class="field-setting col-xs-12 s-all">';
								$output .= '<small>'.__('Border Styling','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
									
									
									/*$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('BG Color','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false"><input type="text" class="form-control wrapper-bg-color" name="wrapper-bg-color" id="bs-color"></span>';
									*/
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Border Color','nex-forms').'</span>';
										$output .= '</span>';
									
									$output .= '<span class="input-group-addon   action-btn color-picker" spellcheck="false"><input type="text" class="form-control wrapper-brd-color" name="wrapper-brd-color" id="bs-color"></span>';
								
									$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Border Width','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<input name="wrapper-brd-size" id="wrapper-brd-size" class="form-control" value="1">';
									$output .= '<span class="input-group-addon">';
									
									$output .= '<span class="icon-text">'.__('Border Radius','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<input name="wrapper-brd-radius" id="wrapper-brd-radius" class="form-control" value="0">';
									//$output .= '<span class="input-group-addon">';
									//		$output .= '<span class="icon-text">'.__('Padding','nex-forms').'</span>';
									//	$output .= '</span>';
									//	$output .= '<input name="form_padding" id="form_padding" class="form-control" value="0">';
									/*$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">'.__('Shadow','nex-forms').'</span>';
										$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn drop-shadow shadow-light" title="'.__('Light Shadow','nex-forms').'"><span class="shadow-light"></span></span>';
									$output .= '<span class="input-group-addon action-btn drop-shadow shadow-dark" title="'.__('Dark Shadow','nex-forms').'"><span class="shadow-dark"></span></span>';
									$output .= '<span class="input-group-addon action-btn drop-shadow shadow-none" title="'.__('No Shadow','nex-forms').'"><span class="fa fa-close"></span></span>';
									*/
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Margins','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="form_margin_left" id="form_margin_left" class="form-control" value="0">';
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="form_margin_right" id="form_margin_right" class="form-control" value="0">';
									

								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="form_margin_top" id="form_margin_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="form_margin_bottom" id="form_margin_bottom" class="form-control" value="15">';
									
									
									//$output .= '<span class="reset-button reset-field-margins">';
									//	$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									//$output .= '</span>';
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Padding','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="form_padding_left" id="form_padding_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="form_padding_right" id="form_padding_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="form_padding_top" id="form_padding_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="form_padding_bottom" id="form_padding_bottom" class="form-control" value="0">';
									
									
									//$output .= '<span class="reset-button reset-form-padding">';
									//	$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									//$output .= '</span>';
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							/*** Background settings ***/
							$output .= '<div class="field-setting col-xs-12 s-all">';	
								$output .= '<div class="setting-form-bg-image ">';						
									$output .= '<small>'.__('Wrapper Background Image Settings','nex-forms').'</small>';
									$output .= '<div role="toolbar" class="btn-toolbar bg-settings">';
	/*** Background image ***/									
										$output .= '<div role="group" class="btn-group image-preview">';
											$output .= '<small>'.__('Image','nex-forms').'</small>';
											$output .= '<form name="do-upload-form-image" id="do-upload-form-image" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">';
												$output .= '<input type="hidden" name="action" value="do_upload_image">';
												$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">';
													$output .= '<div class="the_input_element fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100px; height: 100px;"></div>';
													$output .= '<div class="upload-image-controls">';
														$output .= '<span class="input-group-addon btn-file the_input_element error_message" data-content="'.__('Please select an image','nex-forms').'" data-secondary-message="'.__('Invalid image extension','nex-forms').'" data-placement="top">';
															$output .= '<span class="fileinput-new"><span class="fa fa-cloud-upload"></span></span>';
															$output .= '<span class="fileinput-exists"><span class="fa fa-edit"></span></span>';
															$output .= '<input type="file" name="do_image_upload_preview" >';
														$output .= '</span>';
														$output .= '<a href="#" class="input-group-addon fileinput-exists" data-dismiss="fileinput"><span class="fa fa-close"></span></a>';
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</form>';
										$output .= '</div>';
	/*** Background size ***/									
										$output .= '<div role="group" class="btn-group form-bg-size">';
											$output .= '<small>'.__('Size','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto" type="button" title="'.__('Auto','nex-forms').'"><i class="btn-tx">Auto</i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf contain" type="button" title="'.__('Contain','nex-forms').'"><i class="fa fa-compress"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf cover" type="button" title="'.__('Cover','nex-forms').'"><i class="fa fa-expand"></i></button>';
										$output .= '</div>';
	/*** Background repeat ***/									
										$output .= '<div role="group" class="btn-group form-bg-repeat">';
											$output .= '<small>'.__('Repeat','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat" type="button" title="'.__('Repeat X &amp; Y','nex-forms').'"><i class="fa fa-arrows"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-x" type="button" title="'.__('Repeat X','nex-forms').'"><i class="fa fa-arrows-h"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-y" type="button" title="'.__('Repeat Y','nex-forms').'"><i class="fa fa-arrows-v"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no-repeat" type="button" title="'.__('None','nex-forms').'"><i class="fa fa-remove"></i></button>';
										$output .= '</div>';
	/*** Background position ***/									
										$output .= '<div role="group" class="btn-group form-bg-position">';
											$output .= '<small>'.__('Position','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
										$output .= '</div>';
									
									$output .= '</div>';
								$output .= '</div>';
							
							
							
							
							
							
							$output .= '</div>';
							
							
					$output .= '</div>';		
							
		$output .= '<div id="overall-fields-styling-panel" class="overall-fields-styling-settings row settings-section">';
				
				
				$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
					$output .= '<div class="settings-header"><span>'.__('Field Layout','nex-forms').'</span></div>';
				$output .= '</div>';
				
				
				$output .= '<div class="field-setting col-xs-5 s-all">';
								$output .= '<small>'.__('Field Layout Settings','nex-forms').'</small>';
								$output .= '<button data-style-tool-group="layout" class="styling-tool-item btn-default set_layout set_layout_left 	'.(($overall_field_layout == 'set_layout_left') ? 'active': '').'" data-style-tool="set_layout_left" data-toggle="tooltip_bs" type="button" title="'.__('Label Left','nex-forms').'"></button>';
								$output .= '<button data-style-tool-group="layout" class="styling-tool-item set_layout set_layout_right 			'.(($overall_field_layout == 'set_layout_right') ? 'active': '').'" data-style-tool="set_layout_right" data-toggle="tooltip_bs" type="button" title="'.__('Label Right','nex-forms').'"></button>';
								$output .= '<button data-style-tool-group="layout" class="styling-tool-item btn-default  set_layout set_layout_top 	'.(($overall_field_layout == 'set_layout_top') ? 'active': '').'" data-style-tool="set_layout_top" data-toggle="tooltip_bs" type="button" title="'.__('Label Top','nex-forms').'"></button>';
								$output .= '<button data-style-tool-group="layout" class="styling-tool-item set_layout set_layout_hide				'.(($overall_field_layout == 'set_layout_hide') ? 'active': '').'" data-style-tool="set_layout_hide" data-toggle="tooltip_bs" type="button" title="'.__('Hide Label','nex-forms').'"></button>';
								
							$output .= '</div>';
				
				$output .= '<div class="field-setting col-xs-3 s-all">';
								$output .= '<small>'.__('Field Spacing','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Margin','nex-forms').'</span>';
									$output .= '</span>';
								
									$output .= '<input name="field_spacing" id="field_spacing" class="form-control" value="'.(($field_spacing) ? $field_spacing : '15').'">';

								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="field-setting col-xs-1 s-all"></div>';
							$output .= '<div class="field-setting col-xs-3 s-all">';
									$output .= '<div role="group" class="btn-group overall-input-corners ">';
										$output .= '<small>'.__('Corners','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf square '.(($overall_field_corners == 'square') ? 'active': '').'" type="button" data-style-tool="square" title="Square border"><i class="fas fa-square-full"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal '.(($overall_field_corners == 'normal' || !$overall_field_layout) ? 'active': '').'" type="button" data-style-tool="normal"  title="Rounded Border"><i class="fa fa-square"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf pill   '.(($overall_field_corners == 'pill') ? 'active': '').'" type="button" data-style-tool="pill"  title="Pill"><i class="fa fa-circle"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
				
				
				$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Field Labels','nex-forms').'</span></div>';
							$output .= '</div>';
				
				
				$output .= '<div class="field-setting col-xs-12 s-all">';	
									$output .= '<small>'.__('Label Settings','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
								
	/*** Text Alignment ***/		
									$output .= '<select name="google_fonts_lable" id="google_fonts_lable" data-selected="'.$overall_label_font.'" class="sfm form-control"><option value="">'.__('-- Select Google Font --','nex-forms').'</option><option value="">'.__('Default','nex-forms').'</option>';
										$get_google_fonts = new NF5_googlefonts();
										$output .= $get_google_fonts->get_google_fonts();
									$output .= '</select>';
									$output .= '<span class="input-group-addon spacer">';
										$output .= '<span class="icon-text"></span>';
									$output .= '</span>';
									$output .= '<input type="text" class="form-control" name="label_font_size" id="label_font_size" value="'.(($overall_label_font_size) ? $overall_label_font_size : '13').'"  placeholder="'.__('Font Size','nex-forms').'">';
	
									$output .= '<span class="input-group-addon action-btn o-label-text-align _left '.(($overall_label_align == 'left' || !$overall_label_align) ? 'active': '').'" data-style-tool="left"  title="'.__('Text Align Left','nex-forms').'">';
										$output .= '<span class="fa fa-align-left"></span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn o-label-text-align _center '.(($overall_label_align == 'center') ? 'active': '').'" data-style-tool="center" title="'.__('Text Align Center','nex-forms').'">';
										$output .= '<span class="fa fa-align-center"></span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn o-label-text-align _right '.(($overall_label_align == 'right') ? 'active': '').'" data-style-tool="right" title="'.__('Text Align Right','nex-forms').'">';
										$output .= '<span class="fa fa-align-right"></span>';
									$output .= '</span>';
									
	/*** Label text bold ***/
									$output .= '<span class="input-group-addon action-btn o-label-bold 		'.(($overall_label_bold && $overall_label_bold=='bold') ? '' : 'active').'" title="'.__('Bold','nex-forms').'">';
										$output .= '<span class="fa fa-bold"></span>';
									$output .= '</span>';
	/*** Label text italic ***/
									$output .= '<span class="input-group-addon action-btn o-label-italic 	'.(($overall_label_italic && $overall_label_italic=='italic') ? '' : 'active').'" title="'.__('Italic','nex-forms').'">';
										$output .= '<span class="fa fa-italic"></span>';
									$output .= '</span>';
	/*** Label text underline ***/
									$output .= '<span class="input-group-addon action-btn o-label-underline '.(($overall_label_underline && $overall_label_underline=='underline') ? '' : 'active').'" title="'.__('Underline','nex-forms').'">';
										$output .= '<span class="fa fa-underline"></span>';
									$output .= '</span>';
									
	/*** Label text color ***/
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control o-label-color" name="o-label-color" id="bs-color" value="'.(($overall_label_color) ? $overall_label_color : '#5b5b5b').'"></span>';
									
									
								$output .= '</div>';
							$output .= '</div>';
							
						
						$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Field Inputs','nex-forms').'</span></div>';
							$output .= '</div>';
									
							$output .= '<div class="field-setting col-xs-12 s-all">';
								$output .= '<small>'.__('Input Settings','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
								
	/*** Text Alignment ***/		
									$output .= '<select name="google_fonts_input" id="google_fonts_input" data-selected="'.$overall_input_font.'" class="sfm form-control"><option value="">'.__('-- Select Google Font --','nex-forms').'</option><option value="">'.__('Default','nex-forms').'</option>';
										$get_google_fonts = new NF5_googlefonts();
										$output .= $get_google_fonts->get_google_fonts();
									$output .= '</select>';
									$output .= '<input type="text" class="form-control" name="input_font_size" id="input_font_size" value="'.(($overall_input_font_size) ? $overall_input_font_size : '13').'"  placeholder="'.__('Font Size','nex-forms').'">';
									
									$output .= '<span class="input-group-addon action-btn o-input-text-align _left '.(($overall_input_align == 'left' || !$overall_input_align) ? 'active': '').'" data-style-tool="left" title="'.__('Text Align Left','nex-forms').'">';
										$output .= '<span class="fa fa-align-left"></span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn o-input-text-align _center '.(($overall_input_align == 'center') ? 'active': '').'" data-style-tool="center" title="'.__('Text Align Center','nex-forms').'">';
										$output .= '<span class="fa fa-align-center"></span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn o-input-text-align _right '.(($overall_input_align == 'right') ? 'active': '').'" data-style-tool="right" title="'.__('Text Align Right','nex-forms').'">';
										$output .= '<span class="fa fa-align-right"></span>';
									$output .= '</span>';
									
	/*** Label text bold ***/
									$output .= '<span class="input-group-addon action-btn o-input-bold '.(($overall_input_bold) ? '' : 'active').'" title="'.__('Bold','nex-forms').'">';
										$output .= '<span class="fa fa-bold"></span>';
									$output .= '</span>';
	/*** Label text italic ***/
									$output .= '<span class="input-group-addon action-btn o-input-italic '.(($overall_input_italic) ? '' : 'active').'" title="'.__('Italic','nex-forms').'">';
										$output .= '<span class="fa fa-italic"></span>';
									$output .= '</span>';
	/*** Label text underline ***/
									$output .= '<span class="input-group-addon action-btn o-input-underline '.(($overall_input_underline) ? '' : 'active').'" title="'.__('Underline','nex-forms').'">';
										$output .= '<span class="fa fa-underline"></span>';
									$output .= '</span>';
									
	/*** Label text color ***/
									$output .= '<span class="input-group-addon ">';
										$output .= '<span class="icon-text">'.__('Text','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control o-input-color" name="o-input-color" id="bs-color" value="'.(($overall_input_color) ? $overall_input_color : '#5b5b5b').'"></span>';
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Background','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control o-input-bg-color" name="o-input-bg-color" id="bs-color" value="'.(($overall_input_bg_color) ? $overall_input_bg_color : '#ffffff').'"></span>';
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Border','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control o-input-border-color" name="o-input-border-color" id="bs-color" value="'.(($overall_input_border_color) ? $overall_input_border_color : '#dddddd').'"></span>';
							
								$output .= '</div>';
							$output .= '</div>';
						
						
						$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Field Icons (pre and post)','nex-forms').'</span></div>';
							$output .= '</div>';
						
							
							$output .= '<div class="field-setting col-xs-12 s-all">';
								$output .= '<small>'.__('Icon Settings','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
								
	/*** Text Alignment ***/		
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Icon Size','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<input type="text" class="form-control" name="icon_font_size" id="icon_font_size" value="'.(($overall_icon_font_size) ? $overall_icon_font_size : '17' ).'"  placeholder="'.__('Font Size','nex-forms').'">';
									
	/*** Label text color ***/
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Text','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control o-icon-text-color" name="o-icon-text-color" id="bs-color" value="'.(($overall_icon_color) ? $overall_icon_color : '#888888').'"></span>';
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Background','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control o-icon-bg-color" name="o-icon-bg-color" id="bs-color" value="'.(($overall_icon_bg_color) ? $overall_icon_bg_color : '#ffffff').'"></span>';
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">'.__('Border','nex-forms').'</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control o-icon-brd-color" name="o-icon-brd-color" id="bs-color" value="'.(($overall_icon_border_color) ? $overall_icon_border_color : '#dddddd').'"></span>';
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting is-settings-header  col-xs-12 s-all">';	
								$output .= '<div class="settings-header"><span>'.__('Validation Errors','nex-forms').'</span></div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group overall-error-style ">';
									$output .= '<small>'.__('Validation Error Style','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf modern '.(($overall_field_errors == 'modern' || !$overall_field_errors) ? 'active': '').'" type="button" data-style-tool="modern"  title="Modern"><i class="fa fa-info-circle"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf classic '.(($overall_field_errors == 'classic') ? 'active': '').'" type="button" data-style-tool="classic" title="Classic"><i class="btn-tx">___</i></button>';
								$output .= '</div>';
								
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group overall-error-position ">';
									$output .= '<small>'.__('Validation Error Position','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set_left '.(($overall_field_errors_pos == 'left') ? 'active': '').'" type="button" data-style-tool="left" title="Left"><i class="btn-tx"><i class="fa fa-arrow-left"></i></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set_right '.(($overall_field_errors_pos == 'right' || !$overall_field_errors_pos) ? 'active': '').'" type="button" data-style-tool="right"  title="Right"><i class="fa fa-arrow-right"></i></button>';
									
								$output .= '</div>';
								
							$output .= '</div>';
							
						$output .= '</div>';	
				
								
//CUSTOM CSS							
		$output .= '<div  id="custom-css-settings-panel" class="custom-css-settings row settings-section" style="display:none;">';
			$output .= '<div class="field-setting col-xs-12 s-all">';
				$output .= '<small>'.__('Add CSS','nex-forms').'</small>';
				$output .= '<textarea name="custom_css" id="custom_css" class="form-control">'.wp_unslash($this->custom_css).'</textarea>';
			$output .= '</div>';
		$output .= '</div>';
						




//LOADER SETTINGS
		$loader_type		= (isset($theme_settings['0']['loader_type'])) ? $theme_settings['0']['loader_type'] 		: 'ellipsis';
		$loader_color		= (isset($theme_settings['0']['loader_color'])) ? $theme_settings['0']['loader_color'] 	: '#40C4FF';
		
		
							
		
	$output .= '<div  id="submit-loader-settings-panel" class="submit-loader-settings row settings-section" style="display:none;">';
		
		
		$output .= '<div class="field-setting col-xs-12 s-all">';

			$output .= '<div class="loader-color" data-value="'.$loader_color.'">';
				$output .= '<small>'.__('Loader Color','nex-forms').'</small>';
					$output .='
							   <button class="btn md-btn waves-effect waves-light btn-light-blue popup-button '.(($loader_color=='#40C4FF') ? 'active' : '').'" data-btn-class="btn-light-blue"  data-default-values="" data-color="#40C4FF" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-red popup-button '.(($loader_color=='#f44336') ? 'active' : '').'" data-btn-class="btn-red"  data-default-values="" data-color="#f44336" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-pink popup-button '.(($loader_color=='#e91e63') ? 'active' : '').'" data-btn-class="btn-pink"  data-default-values="" data-color="#e91e63" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-purple popup-button '.(($loader_color=='#9c27b0') ? 'active' : '').'" data-btn-class="btn-purple"  data-default-values="" data-color="#9c27b0" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-deep-purple popup-button '.(($loader_color=='#673ab7') ? 'active' : '').'" data-btn-class="btn-deep-purple"  data-default-values="" data-color="#673ab7" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-indigo popup-button '.(($loader_color=='#3f51b5') ? 'active' : '').'" data-btn-class="btn-indigo"  data-default-values="" data-color="#3f51b5" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-blue popup-button '.(($loader_color=='#2979FF') ? 'active' : '').'" data-btn-class="btn-blue"  data-default-values="" data-color="#2979FF" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-cyan popup-button '.(($loader_color=='#00bcd4') ? 'active' : '').'" data-btn-class="btn-cyan"  data-default-values="" data-color="#00bcd4" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-teal popup-button '.(($loader_color=='#009688') ? 'active' : '').'" data-btn-class="btn-teal"  data-default-values="" data-color="#009688" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-green popup-button '.(($loader_color=='#4caf50') ? 'active' : '').'" data-btn-class="btn-green"  data-default-values="" data-color="#4caf50" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-light-green popup-button '.(($loader_color=='#8bc34a') ? 'active' : '').'" data-btn-class="btn-light-green"  data-default-values="" data-color="#8bc34a" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-lime popup-button '.(($loader_color=='#cddc39') ? 'active' : '').'" data-btn-class="btn-lime"  data-default-values="" data-color="#cddc39" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-yellow popup-button '.(($loader_color=='#ffeb3b') ? 'active' : '').'" data-btn-class="btn-yellow"  data-default-values="" data-color="#ffeb3b" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-amber popup-button '.(($loader_color=='#ffc107') ? 'active' : '').'" data-btn-class="btn-amber"  data-default-values="" data-color="#ffc107" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-orange popup-button '.(($loader_color=='#ff9800') ? 'active' : '').'" data-btn-class="btn-orange"  data-default-values="" data-color="#ff9800" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-brown popup-button '.(($loader_color=='#795548') ? 'active' : '').'" data-btn-class="btn-brown"  data-default-values="" data-color="#795548" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-gray popup-button '.(($loader_color=='#9e9e9e') ? 'active' : '').'" data-btn-class="btn-gray"  data-default-values="" data-color="#9e9e9e" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-blue-gray popup-button '.(($loader_color=='#607d8b') ? 'active' : '').'" data-btn-class="btn-blue-gray"  data-default-values="" data-color="#607d8b" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-nf-555  popup-button '.(($loader_color=='#555') ? 'active' : '').'" data-btn-class="btn-nf-555"  data-default-values="" data-color="#555" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-nf-333 popup-button '.(($loader_color=='#333') ? 'active' : '').'" data-btn-class="btn-nf-333"  data-default-values="" data-color="#333" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-nf-000 popup-button '.(($loader_color=='#000') ? 'active' : '').'" data-btn-class="btn-nf-000"  data-default-values="" data-color="#000" ></button>
							   <button class="btn md-btn waves-effect waves-light btn-nf-fff popup-button '.(($loader_color=='#fff') ? 'active' : '').'" data-btn-class="btn-nf-fff"  data-default-values="" data-color="#fff" ></button>
							   ';
			$output .= '</div>';
		$output .= '</div>';
		
		
		
		
		$output .= '<div class="field-setting col-xs-12 s-all">';
			
			$output .= '<small>'.__('Available Loaders','nex-forms').'</small>';
			
			$output .= '<div class="loader-type" data-value="'.$loader_type.'">';
				$output .= '<div class="nf-loader-container '.(($loader_type=='circle') ? 'active' : '').'" data-type="circle"><div class="nf-loader-type nf-loader-lds-circle"><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='dual-ring') ? 'active' : '').'" data-type="dual-ring"><div class="nf-loader-type nf-loader-lds-dual-ring"></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='fb') ? 'active' : '').'" data-type="fb"><div class="nf-loader-type nf-loader-lds-facebook"><div></div><div></div><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='heart') ? 'active' : '').'" data-type="heart"><div class="nf-loader-type nf-loader-lds-heart"><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='ring') ? 'active' : '').'" data-type="ring"><div class="nf-loader-type nf-loader-lds-ring"><div></div><div></div><div></div><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='roller') ? 'active' : '').'" data-type="roller"><div class="nf-loader-type nf-loader-lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='roller2') ? 'active' : '').'" data-type="roller2"><div class="nf-loader-type nf-loader-lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='ellipsis') ? 'active' : '').'" data-type="ellipsis"><div class="nf-loader-type nf-loader-lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='grid') ? 'active' : '').'" data-type="grid"><div class="nf-loader-type nf-loader-lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='umbrella') ? 'active' : '').'" data-type="umbrella"><div class="nf-loader-type nf-loader-lds-hourglass"></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='ripple') ? 'active' : '').'" data-type="ripple"><div class="nf-loader-type nf-loader-lds-ripple"><div></div><div></div></div></div>';
				$output .= '<div class="nf-loader-container '.(($loader_type=='spinner') ? 'active' : '').'" data-type="spinner"><div class="nf-loader-type nf-loader-lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
			$output .= '</div>';
			
			
			
			$output .= '</div>';
		$output .= '</div>';
						
						
		
						
						$output .= '<div id="animations-settings-panel" class="animations-settings settings-section" style="display:none;">';
							$output .= '<div class="input-group input-group-sm">';
								$output .= '<small>'.__('Animation Settings','nex-forms').'</small>';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="setting-buffer"></div>';	
					$output .= '</div>';
				$output .= '</div>';
			
			
				
			return $output;
		}
		
		public function print_field_settings(){
			
			
			$theme_settings = json_decode($this->md_theme,true);
			
			$set_theme 			= ($theme_settings['0']['theme_name']) 	? $theme_settings['0']['theme_name'] 	: 'default';
			$set_theme_shade 	= ($theme_settings['0']['theme_shade']) ? $theme_settings['0']['theme_shade'] 	: 'light';
			
			
			
			
			
			$set_form_theme = ($this->form_theme) ? $this->form_theme : 'bootstrap';
			$set_jq_theme 	= ($this->jq_theme) ? $this->jq_theme : 'default';
			
			$set_current_theme = '';
			
			if($set_form_theme=='m_design')
				$set_current_theme = 'material_theme';
			
			$output = '';
			
			$output .= '<div class="field-settings-column settings-column-style right_hand_col '.$set_current_theme.'">';
			
					$output .= '
					<div id="close-settings" class="close-area">
						<span class="fa fa-close"></span>
					</div>
					
					<div class="current_id" style="display:none;"></div>';
						
						
						$output .= '<div class="material_box_head aa_bg_main"><div class="normal-edit"><span class="fa fa-edit"></span>Edit Field</div><div class="batch-group-edit"><span class="far fa-object-group"></span>Edit Field Selection Group</div></div>';
						
						$output .= '<div class="field-setting-categories field-setting-categories-style">';
							
							
							$output .= '<nav class="nav-extended settings_tabs_nf">
									<div class="nav-content aa_bg_main">
									  <ul class="tabs_nf tabs_nf-transparent sec-menu aa_menu">
										<li id="label-settings" class="tab"><a class="active" href="#label-settings-panel">'.__('Label','nex-forms').'</a></li>
										<li id="input-settings" class="tab"><a href="#input-settings-panel">'.__('Input','nex-forms').'</a></li>
										<li id="validation-settings" class="tab"><a href="#validation-settings-panel">'.__('Validation','nex-forms').'</a></li>
										<li id="math-settings" class="tab form-editor-only"><a href="#math-settings-panel">'.__('Math Logic','nex-forms').'</a></li>
										<li id="animation-settings" class="tab"><a href="#animation-settings-panel">'.__('Animation','nex-forms').'</a></li>
										<li id="extra-settings" class="tab"><a href="#extra-settings-panel">'.__('Advanced','nex-forms').'</a></li>
									  </ul>
									</div>
								 </nav>';
						$output .= '</div>';
						
						
						
						
/*****************************************************/	
/******************SETTINGS***************************/
/*****************************************************/	
					
						$output .= '<div class="inner"><form enctype="multipart/form-data" method="post" action="'.get_option('siteurl').'/wp-admin/admin-ajax.php" id="do_upload_image_selection" name="do_upload_image_selection" style="display:none;">
								<div data-provides="fileinput" class="fileinput fileinput-new hidden">
																		  <div style="width: 100px; height: 100px;" data-trigger="fileinput" class="the_input_element fileinput-preview thumbnail"></div>
																		  <div>
																			<span data-placement="top" data-secondary-message="Invalid image extension" data-content="Please select an image" class="btn btn-default waves-effect-nf waves-light-nf btn-file the_input_element error_message"><span class="fileinput-new">'.__('Select image','nex-forms').'</span><span class="fileinput-exists">'.__('Change','nex-forms').'</span>
																			<input type="file" name="do_image_select_upload_preview">
																			</span>
																			<a data-dismiss="fileinput" class="btn btn-default waves-effect-nf waves-light-nf fileinput-exists" href="#">'.__('Remove','nex-forms').'</a>
																		  </div>
																		  <div style="display:none;" class="get_file_ext">gif
jpg
jpeg
png
psd
tif
tiff</div></div></form>';
//LABEL SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="label-settings row settings-section">';
	/*** Label text ***/
								
								$output .= '<div class="field-setting col-xs-12 s-text material_only">';
									$output .= '<small>'.__('Label','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_material_label" id="set_material_label"  placeholder="'.__('Label text','nex-forms').'">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-12 s-all s-odd_setting">';	
									$output .= '<small>'.__('Label Text','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_label" id="set_label"  placeholder="'.__('Add text','nex-forms').'">';
								$output .= '</div>';
								$output .= '<div class="field-setting col-xs-4 s-all"></div>';
								$output .= '<div class="field-setting col-xs-8 s-all s-odd_setting">';										
									$output .= '<div class="input-group input-group-sm">';
										
		/*** Label text bold ***/
										$output .= '<span class="input-group-addon action-btn label-bold" title="'.__('Bold','nex-forms').'">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
		/*** Label text italic ***/
										$output .= '<span class="input-group-addon action-btn label-italic" title="'.__('Italic','nex-forms').'">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
		/*** Label text underline ***/
										$output .= '<span class="input-group-addon action-btn label-underline" title="'.__('Underline','nex-forms').'">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>'; 
										
										$output .= '<span class="input-group-addon label-text-alignment action-btn text-left none_material" title="'.__('Align Text Left','nex-forms').'">';
											$output .= '<span class="fa fa-align-left"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon label-text-alignment action-btn text-center none_material" title="'.__('Align Text Center','nex-forms').'">';
											$output .= '<span class="fa fa-align-center"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon label-text-alignment action-btn text-right none_material" title="'.__('Align Text Right','nex-forms').'">';
											$output .= '<span class="fa fa-align-right"></span>';
										$output .= '</span>';
		/*** Label text color ***/
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control label-color" name="label-color" id="bs-color"></span>';
										$output .= '<input type="text" class="form-control" name="set_label_font_size" id="set_label_font_size" value="14"  placeholder="Font Size">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Margin Bottom" data-original-title="Margin Bottom">MB</span>';
										$output .= '<input type="text" class="form-control" name="set_label_margin_bottom" id="set_label_margin_bottom" value="15"  placeholder="Margin Bottom">';
									$output .= '</div>';
								$output .= '</div>';
	/*** Sub-label text ***/
								
								$output .= '<div class="field-setting col-xs-12 s-all none_material">';	
									$output .= '<small>'.__('Sub-label Text','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_subtext" placeholder="Add text" id="set_subtext">';
								$output .= '</div>';
								$output .= '<div class="field-setting col-xs-4 s-all"></div>';
								$output .= '<div class="field-setting col-xs-8 s-all none_material">';	
									$output .= '<div class="input-group input-group-sm">';
										
		/*** Sub-Label text bold ***/
										$output .= '<span class="input-group-addon action-btn sub-label-bold" title="'.__('Bold','nex-forms').'">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
		/*** Sub-Label text italic ***/
										$output .= '<span class="input-group-addon action-btn sub-label-italic" title="'.__('Italic','nex-forms').'">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
		/*** Sub-Label text underline ***/
										$output .= '<span class="input-group-addon action-btn sub-label-underline" title="'.__('Underline','nex-forms').'">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>';
										
										
										$output .= '<span class="input-group-addon sub-label-text-alignment action-btn text-left none_material" title="'.__('Align Text Left','nex-forms').'">';
											$output .= '<span class="fa fa-align-left"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon sub-label-text-alignment action-btn text-center none_material" title="'.__('Align Text Center','nex-forms').'">';
											$output .= '<span class="fa fa-align-center"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon sub-label-text-alignment action-btn text-right none_material" title="'.__('Align Text Right','nex-forms').'">';
											$output .= '<span class="fa fa-align-right"></span>';
										$output .= '</span>';
										
		/*** Sub-Label text color ***/
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control sub-label-color" name="label-color" id="bs-color"></span>';
										
										$output .= '<input type="text" class="form-control" name="set_sub_label_font_size" id="set_sub_label_font_size" value="13"  placeholder="Font Size">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Margin Bottom" data-original-title="Margin Bottom">MB</span>';
										$output .= '<input type="text" class="form-control" name="set_sub_label_margin_bottom" id="set_sub_label_margin_bottom" value="13"  placeholder="Margin Bottom">';
									
									$output .= '</div>';
								$output .= '</div>';
								
										
								
	/*** Label alignment ***/
								/*$output .= '<div class="field-setting col-xs-4 s-all s-odd_setting">';
									$output .= '<div role="group" class="btn-group align-label">';
										$output .= '<small>'.__('Text Alignment','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
								$output .= '</div>';*/
	/*** Label size ***/
								/*$output .= '<div class="field-setting col-xs-4 s-all s-odd_setting">';
									$output .= '<div role="group" class="btn-group label-size">';
										$output .= '<small>'.__('Text Size','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf small" type="button" title="'.__('Small','nex-forms').'"><i class="fa fa-font" style="font-size:9px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="'.__('Normal','nex-forms').'"><i class="fa fa-font" style="font-size:12px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf large" type="button" title="'.__('Large','nex-forms').'"><i class="fa fa-font" style="font-size:15px"></i></button>';
									$output .= '</div>';
								$output .= '</div>';*/
	/*** Label width ***/		
								
								
								$output .= '<div class="field-setting col-xs-4 s-all s-odd_setting">';
	/*** Label position ***/
									$output .= '<div role="group" class="btn-group label-position">';
										$output .= '<small>'.__('Layout','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" 	title="'.__('Left','nex-forms').'"><i class="fa fa-arrow-left"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf top" type="button" 	title="'.__('Top','nex-forms').'"><i class="fa fa-arrow-up"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-arrow-right"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf none" type="button" 	title="Hidden"><i class="fa fa-eye-slash"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-8 s-all s-odd_setting">';	
									$output .= '<small class="width_distribution">'.__('Width Distribution','nex-forms').'</small>';						
									$output .= '<div class="row">';
										$output .= '<div class="col-xs-1">';
											$output .= '<small class="width_indicator left"><input type="text" name="set_label_width" id="set_label_width" class="form-control">'.__('','nex-forms').'</small>';
										$output .= '</div>';
										$output .= '<div class="col-xs-10 width_slider"><br />';
											$output .= '<select name="label_width" id="label_width">
															<option>1</option>
															<option>2</option>
															<option>3</option>
															<option>4</option>
															<option>5</option>
															<option>6</option>
															<option>7</option>
															<option>8</option>
															<option>9</option>
															<option>10</option>
															<option>11</option>
															<option>12</option>
														</select>';
										$output .= '</div>';
											
										$output .= '<div class="col-xs-1">';
											$output .= '<small class="width_indicator right"><input type="text" name="set_input_width" id="set_input_width" class="form-control">'.__('','nex-forms').'</small>';
										$output .= '</div>';
									
									$output .= '</div>';
								$output .= '</div>';
								
								
							$output .= '</div>';
				
				$output .= '<div class="extra-settings row settings-section">';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Field Wrapper Margins','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_margin_left" id="field_spacing_margin_left" class="form-control" value="0">';
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_margin_right" id="field_spacing_margin_right" class="form-control" value="0">';
									

								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_margin_top" id="field_spacing_margin_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_margin_bottom" id="field_spacing_margin_bottom" class="form-control" value="15">';
									
									
									$output .= '<span class="reset-button reset-field-margins">';
										$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									$output .= '</span>';
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Field Wrapper Padding','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_padding_left" id="field_spacing_padding_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_padding_right" id="field_spacing_padding_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_padding_top" id="field_spacing_padding_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="field_spacing_padding_bottom" id="field_spacing_padding_bottom" class="form-control" value="0">';
									
									
									$output .= '<span class="reset-button reset-field-padding">';
										$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									$output .= '</span>';
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						$output .= '<div class="field-setting col-xs-12 s-all">';	
											$output .= '<div role="group" class="btn-group setting-device-only">';
												$output .= '<small>'.__('Enable/Show this field only on','nex-forms').'</small>';
												//$output .= '<small><em>'.__('The grid will hold its inline layout up to the device selected','nex-forms').'</em></small>';
												$output .= '<button class="btn btn-default active waves-effect-nf waves-light-nf set-mobile" data-val="nf-no-mobile" type="button" title="'.__('Mobiles','nex-forms').'"><i class="fa fa-mobile"></i></button>';
												$output .= '<button class="btn btn-default active waves-effect-nf waves-light-nf set-tablet" data-val="nf-no-tablet" type="button" title="'.__('Tablets','nex-forms').'"><i class="fa fa-tablet"></i></button>';
												$output .= '<button class="btn btn-default active waves-effect-nf waves-light-nf set-laptops" data-val="nf-no-laptop" type="button" title="'.__('Laptops').'"><i class="fa fa-laptop"></i></button>';
												$output .= '<button class="btn btn-default active waves-effect-nf waves-light-nf set-pc" type="button" data-val="nf-no-pc" title="'.__('Desktops and larger devices').'"><i class="fa fa-desktop"></i></button>';
												
											$output .= '</div>';
										$output .= '</div>';
						
						
						
						
						$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-math s-math-slider">';
								$output .= '<small>'.__('Field Border Radius','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top Left</span>';
									$output .= '</span>';
									$output .= '<input name="field_border_radius_top_left" id="field_border_radius_top_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top Right</span>';
									$output .= '</span>';
									$output .= '<input name="field_border_radius_top_right" id="field_border_radius_top_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-math s-math-slider">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom Left</span>';
									$output .= '</span>';
									$output .= '<input name="field_border_radius_bottom_left" id="field_border_radius_bottom_left" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom Right</span>';
									$output .= '</span>';
									$output .= '<input name="field_border_radius_bottom_right" id="field_border_radius_bottom_right" class="form-control" value="0">';
									
									
									/*$output .= '<span class="reset-button reset-field-padding">';
										$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									$output .= '</span>';*/
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						
						
						
						$output .= '</div>';
							
//INPUT SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
				$output .= '<div class="input-settings row settings-section">';
							
/*** Input Name ***/

						$output .= '<div class="field-setting col-xs-12 s-thumbs-select-single s-thumbs-select-multi">';
								
									$output .= '<small>'.__('Convert field to Thumbs 2.0','nex-forms').'<br><em>NOTE: If you convert and save the form you will not be able to go back to the old thumbs select.</em></small>';
									$output .= '<div class="convert_image_field_button">Convert to Thumbs 2.0</div>';
								
							$output .= '</div>';

							$output .= '<div class="field-setting no-batch-edit col-xs-12 s-text s-upload-image s-sigs s-slider s-password s-tags s-spinner s-select s-checks s-radios s-thumbs-select s-thumbs-select-single s-thumbs-select-multi s-super-select">';
								
									$output .= '<small>'.__('Input Name','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_input_name" id="set_input_name"  placeholder="'.__('Can not be empty!','nex-forms').'">';
								
							$output .= '</div>';								
									
							
						
/*** Input Placeholder ***/	
							$output .= '<div class="field-setting no-batch-edit col-xs-6 s-text">';
									$output .= '<small>'.__('Input Placeholder','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_place_holder" id="set_input_placeholder"  placeholder="'.__('Placeholder text','nex-forms').'">';	
							$output .= '</div>';								

/*** Input ID ***/					
							$output .= '<div class="field-setting no-batch-edit col-xs-6 s-text s-tags s-select s-password none_material">';	
									$output .= '<small>'.__('Input ID','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_input_id" id="set_input_id"  placeholder="'.__('Unique Identifier','nex-forms').'">';
							$output .= '</div>';
							
/*** IMAGE OPTIONS ***/
							$output .= '<div class="field-setting col-xs-6 s-image">';	
									$output .= '<small>'.__('Alt Text','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_alt_text" id="set_alt_text"  placeholder="'.__('Insert image alt text','nex-forms').'">';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-3 s-image">';	
									$output .= '<small>'.__('Width','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_image_width" id="set_image_width" value=""  placeholder="Width">';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-3 s-image">';	
									$output .= '<small>'.__('Height','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_image_height" id="set_image_height" value=""  placeholder="Height">';
							$output .= '</div>';
							
							
/*** SIGNATURE OPTIONS ***/
					
							
							
/*** Signature Width ***/	

							$output .= '<div class="field-setting col-xs-3 s-sigs">';	
									$output .= '<div class="btn-group auto_fit">';
										$output .= '<small>'.__('Auto-fit','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm yes" type="button"><i class="fa fa-check" ></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm no active" type="button"><i class="fa fa-remove" ></i></button>';
									$output .= '</div>';
								$output .= '</div> ';
							
							
							$output .= '<div class="field-setting col-xs-9 s-sigs">';	
									$output .= '<small>'.__('Stroke Styling','nex-forms').'</small>';									
									$output .= '<div class="input-group input-group-sm">';
										
										
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="'.__('Set Stroke Width','nex-forms').'" data-original-title="'.__('Set Stroke Width','nex-forms').'">'.__('Width','nex-forms').'</span>';
										$output .= '<input type="text" class="form-control" name="set_signature_line_width" id="set_signature_line_width"  value="2"  placeholder="'.__('Set Line Width','nex-forms').'">';
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="'.__('Set Stroke Color','nex-forms').'" data-original-title="'.__('Set Stroke Color','nex-forms').'">'.__('Color','nex-forms').'</span>';
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control stroke-color" name="stroke-color" id="bs-color"></span>';
										//$output .= '<input type="text" class="form-control" name="set_label_font_size" id="set_label_font_size" value="14"  placeholder="Font Size">';
										//$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Margin Bottom" data-original-title="Margin Bottom">MB</span>';
										//$output .= '<input type="text" class="form-control" name="set_label_margin_bottom" id="set_label_margin_bottom" value="15"  placeholder="Margin Bottom">';
									$output .= '</div>'; 
								$output .= '</div>';
							
								
							$output .= '<div class="field-setting col-xs-12 s-sigs">';	
									$output .= '<small>'.__('Canvas Styling','nex-forms').'</small>';									
									$output .= '<div class="input-group input-group-sm">';
										
										
										$output .= '<span class="input-group-addon sig-alignment action-btn sig-left none_material" title="'.__('Align Left','nex-forms').'">';
											$output .= '<span class="fa fa-align-left"></span>';
										$output .= '</span>';
	
										$output .= '<span class="input-group-addon sig-alignment action-btn sig-center none_material" title="'.__('Align Center','nex-forms').'">';
											$output .= '<span class="fa fa-align-center"></span>';
										$output .= '</span>';
	
										$output .= '<span class="input-group-addon sig-alignment action-btn sig-right none_material" title="'.__('Align Right','nex-forms').'">';
											$output .= '<span class="fa fa-align-right"></span>';
										$output .= '</span>';
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="'.__('Canvas Background','nex-forms').'" data-original-title="'.__('Canvas Background','nex-forms').'">'.__('BG','nex-forms').'</span>';
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control sig-bg-color" name="sig-bg-color" id="bs-color"></span>';
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="'.__('Set Canvas Width','nex-forms').'" data-original-title="'.__('Set Canvas Width','nex-forms').'">'.__('Width','nex-forms').'</span>';
										$output .= '<input type="text" class="form-control" name="set_signature_width" id="set_signature_width" value="300"  placeholder="'.__('Set Width','nex-forms').'">';
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="'.__('Set Canvas Height','nex-forms').'" data-original-title="'.__('Set Canvas Height','nex-forms').'">'.__('Height','nex-forms').'</span>';
										$output .= '<input type="text" class="form-control" name="set_signature_height" id="set_signature_height"  value="100"  placeholder="'.__('Set Height','nex-forms').'">';	
		
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="'.__('Border Color','nex-forms').'" data-original-title="'.__('Border Color','nex-forms').'">'.__('Border','nex-forms').'</span>';
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control sig-br-color" name="sig-br-color" id="bs-color"></span>';
										
										
									$output .= '</div>'; 
								$output .= '</div>';
								
								
								
								
							
							/*$output .= '<div class="field-setting col-xs-4 s-sigs">';
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<small>'.__('Line Width','nex-forms').'</small>';
											$output .= '<input type="text" class="form-control" name="set_signature_line_width" id="set_signature_line_width"  placeholder="'.__('Set Line Width','nex-forms').'">';	
								$output .= '</div>';
							$output .= '</div>';*/
							
							/*$output .= '<div class="field-setting col-xs-4 s-sigs">';
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<small>'.__('Width','nex-forms').'</small>';
											$output .= '<input type="text" class="form-control" name="set_signature_width" id="set_signature_width"  placeholder="'.__('Set Width','nex-forms').'">';	
								$output .= '</div>';
							$output .= '</div>';	*/
/*** Signature Height ***/		
							/*$output .= '<div class="field-setting col-xs-4 s-sigs">';			
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<small>'.__('Height','nex-forms').'</small>';
											$output .= '<input type="text" class="form-control" name="set_signature_height" id="set_signature_height"  placeholder="'.__('Set Height','nex-forms').'">';	
								$output .= '</div>';
							$output .= '</div>';*/	
							
												
						
						
						
/*** DATE TIME OPTIONS ***/
					
/*** Date Format Placeholder ***/
							
							
							
							$output .= '<div class="field-setting col-xs-4 s-time none_jqui">';
									$output .= '<div class="btn-group display_calendar">
											<small>'.__('Time Display','nex-forms').'</small>
											<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active popup" title="Display field and popup Time Selector" type="button"><i class="fas fa-comment-alt"></i></button>
											<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm inline" title="Display Time Selector inline" type="button"><i class="far fa-clock"></i></button>
										  </div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-date none_jqui">';
									$output .= '<div class="btn-group display_calendar">
											<small>'.__('Calendar Display','nex-forms').'</small>
											<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active popup" title="Display field and popup Calendar" type="button"><i class="fas fa-comment-alt"></i></button>
											<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm inline" title="Display Calendar inline" type="button"><i class="far fa-calendar-alt"></i></button>
										  </div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-time align-time-inline">';	
								$output .= '<div role="group" class="btn-group align-input-container">';
									$output .= '<small>Alignment</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-4 s-time s-date set-popup-direction">';	
										$output .= '<div role="group" class="btn-group  popup-direction">';
														$output .= '<small>'.__('Popup Direction','nex-forms').'</small>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf bottom active" type="button" title="Popup Down"><i class="fa fa-arrow-down"></i></button>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf top" type="button" title="Popup Up"><i class="fa fa-arrow-up"></i></button>';
													$output .= '</div>';
									$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-time s-date set-popup-keep-open">';	
										$output .= '<div role="group" class="btn-group  popup-keep-open">';
														$output .= '<small>'.__('Keep Open','nex-forms').'</small>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm yes" title="Disable the selection<br />of Past Dates" type="button"><i class="fa fa-check"></i></button>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm no active" title="Enable the selection<br />of Past Dates" type="button"><i class="fa fa-remove"></i></button>';
													$output .= '</div>';
									$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-12 s-date s-divider">';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-time">';	
									$output .= '<small>'.__('Intervals','nex-forms').'</small>';
									
									$output .= '<div class="input-group input-group-sm">';	
											
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">&nbsp;Step&nbsp;</span>';
										$output .= '</span>';
									
										$output .= '<input type="text" class="form-control outside-group" name="set_time_stepping" id="set_time_stepping" value="5"  placeholder="Set Minute Interval Stepping">';
										
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">&nbsp;Minutes&nbsp;</span>';
										$output .= '</span>';
										
									$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-date">';	
								$output .= '<div class="btn-group disable_past_dates">
											<small>'.__('Disable Past Dates','nex-forms').'</small>
											<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm yes" title="Disable the selection<br />of Past Dates" type="button"><i class="fa fa-check"></i></button>
											<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm no active" title="Enable the selection<br />of Past Dates" type="button"><i class="fa fa-remove"></i></button>
										  </div>';
							$output .= '</div>';	
							
							$output .= '<div class="field-setting col-xs-8 s-date none_jqui">';
									$output .= '<div class="btn-group enabled_days">';
									$output .= '<small>'.__('Enabled Days','nex-forms').'</small>';
									
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active days_0" data-val="0" type="button"><i class="btn-tx">Su</i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active days_1" data-val="1" type="button"><i class="btn-tx">Mo</i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active days_2" data-val="2" type="button"><i class="btn-tx">Tu</i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active days_3" data-val="3" type="button"><i class="btn-tx">We</i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active days_4" data-val="4" type="button"><i class="btn-tx">Th</i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active days_5" data-val="5" type="button"><i class="btn-tx">Fr</i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active days_6" data-val="6" type="button"><i class="btn-tx">Sa</i></button>';
										
									
									//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active" data-val="00" type="button"><i class="btn-tx">00</i></button>';
									$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting no-batch-edit col-xs-6 s-date">';	
								$output .= '<small>'.__('Min Date <br /><em>(Set minimum selectable date. Use field data tags to link pickers)</em>','nex-forms').'</small>';
								$output .= '<input type="text" class="form-control" name="set_min_date" id="set_min_date"  placeholder="'.__('YYYY/MM/DD','nex-forms').'">';
							$output .= '</div>';
							
							$output .= '<div class="field-setting no-batch-edit col-xs-6 s-date">';	
								$output .= '<small>'.__('Max Date <br /><em>(Set maximum selectable date. Use field data tags to link pickers)</em>','nex-forms').'</small>';
								$output .= '<input type="text" class="form-control" name="set_max_date" id="set_max_date"  placeholder="'.__('YYYY/MM/DD','nex-forms').'">';
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-12 s-date">';	
								$output .= '<small>'.__('Disable Dates <br /><em>(Comma separated list of dates in YYYY/MM/DD format)</em> ','nex-forms').'</small>';
								$output .= '<textarea class="form-control" name="set_disabled_dates" id="set_disabled_dates" placeholder="YYYY/MM/DD,YYYY/MM/DD,YYYY/MM/DD" ></textarea>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-12 s-time none_jqui">';
									$output .= '<div class="btn-group enabled_hours">';
									$output .= '<small>'.__('Enabled Hours','nex-forms').'</small>';
									for($i=1;$i<25;$i++)
										{
										if($i==13)
											$output .= '<br /><br />';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active hour_'.$i.'" data-val="'.$i.'" type="button"><i class="btn-tx">'.$i.'</i></button>';
										}
									//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm active" data-val="00" type="button"><i class="btn-tx">00</i></button>';
									$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-4 s-date none_jqui">';
								$output .= '<small>'.__('Starting View','nex-forms').'</small>';
									$output .= '<select class="form-control" id="select_view_mode">	
													<option value="days">Days</option>
													<option value="months">Months</option>
													<option value="years">Years</option>
													<option value="decades">Decades</option>
												</select>';	
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-4 s-date none_jqui">';
								$output .= '<small>'.__('Date Format','nex-forms').'</small>';
									$output .= '<select class="form-control" id="select_date_format">	
													<option value="DD/MM/YYYY">DD/MM/YYYY</option>
													<option value="YYYY/MM/DD">YYYY/MM/DD</option>
													<option value="DD-MM-YYYY">DD-MM-YYYY</option>
													<option value="YYYY-MM-DD">YYYY-MM-DD</option>
													<option value="custom">Custom</option>
												</select>';	
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-4 s-date set-custom-date-format hidden none_jqui">';
									$output .= '<small>'.__('Custom Format','nex-forms').'</small>';
										$output .= '<input type="text" class="form-control " value="" placeholder="'.__('Set date format','nex-forms').'" name="set_date_format" id="set_date_format">';
							$output .= '</div>';
/*** Date Format Language ***/	
							$output .= '<div class="field-setting col-xs-4 s-date none_jqui">';	
									$output .= '<small>'.__('Language','nex-forms').'</small>';
									$output .= '<select class="form-control" id="date-picker-lang-selector"><option value="en">en</option><option value="ar-ma">ar-ma</option><option value="ar-sa">ar-sa</option><option value="ar-tn">ar-tn</option><option value="ar">ar</option><option value="bg">bg</option><option value="ca">ca</option><option value="cs">cs</option><option value="da">da</option><option value="de-at">de-at</option><option value="de">de</option><option value="el">el</option><option value="en-au">en-au</option><option value="en-ca">en-ca</option><option value="en-gb">en-gb</option><option value="es">es</option><option value="fa">fa</option><option value="fi">fi</option><option value="fr-ca">fr-ca</option><option value="fr">fr</option><option value="he">he</option><option value="hi">hi</option><option value="hr">hr</option><option value="hu">hu</option><option value="id">id</option><option value="is">is</option><option value="it">it</option><option value="ja">ja</option><option value="ko">ko</option><option value="lt">lt</option><option value="lv">lv</option><option value="nb">nb</option><option value="nl">nl</option><option value="pl">pl</option><option value="pt-br">pt-br</option><option value="pt">pt</option><option value="ro">ro</option><option value="ru">ru</option><option value="sk">sk</option><option value="sl">sl</option><option value="sr-cyrl">sr-cyrl</option><option value="sr">sr</option><option value="sv">sv</option><option value="th">th</option><option value="tr">tr</option><option value="uk">uk</option><option value="vi">vi</option><option value="zh-cn">zh-cn</option><option value="zh-tw">zh-tw</option></select>';	
								
							$output .= '</div>';
							
							
														

										
							 
							 
								
							$output .= '<div class="field-setting col-xs-4 no-batch-edit s-super-select">';
								$output .= '<div role="group" class="btn-group icon-select-type">';
									$output .= '<small>'.__('Select Type','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf icon-normal-select" type="button" title="'.__('Normal Check/Radio Select Style','nex-forms').'"> <i class="fa fa-dot-circle-o"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf icon-dropdown-select" type="button" title="'.__('Dropdown Select Style','nex-forms').'"><i class="fa fas fa-list-ul"></i> </button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf icon-spin-select" type="button" title="'.__('Spinner Select Style','nex-forms').'"><i class="fas fa-arrows-alt-h"></i> </button>';
								$output .= '</div>';
							$output .= '</div>';
								
							$output .= '<div class="field-setting col-xs-4 no-batch-edit s-super-select">';
								$output .= '<div role="group" class="btn-group icon-selection-type">';
									$output .= '<small>'.__('Selection Type','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf single-icon-select" type="button" title="'.__('Single option selection only','nex-forms').'"><i class="fas fa-check"></i></span></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf multi-icon-select" type="button" title="'.__('Multiple option selections','nex-forms').'"><i class="fas fa-check-double"></i></button>';
								$output .= '</div>';
							$output .= '</div>';	
								
							$output .= '<div class="field-setting col-xs-4 no-batch-edit s-super-select">';		
										$output .= '<div role="group" class="btn-group icon-auto-step">';
											$output .= '<small>'.__('Auto Advance','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto-step-no active" type="button" title="'.__('Do not advance to next step<br />on selection','nex-forms').'"><i class="fa fa-close"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto-step-yes" type="button" title="'.__('Advance to next step<br />on selection','nex-forms').'"><i class="fas fa-check"></i></button>';
										$output .= '</div>';
									$output .= '</div>';	
							
							$output .= '<div class="field-setting col-xs-12 no-batch-edit s-super-select">';
								$output .= '<small>'.__('Attach to Field','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm set_field_attachment">';
										$output .= '<span class="input-group-addon action-btn pre-attach" title="Prepend to field">';
											$output .= '<span class="fa fa-arrow-left"></span>';
										$output .= '</span>';
										
										$output .= '<span class="input-group-addon action-btn post-attach active" title="Append to Field">';
											$output .= '<span class="fa fa-arrow-right"></span>';
										$output .= '</span>';
										
										$output .= '<select name="attach_to_field" id="attach_to_field" class="form-control"></select>';
								$output .= '</div>';
							$output .= '</div>';
						
							$output .= '<div class="field-setting col-xs-12 no-batch-edit s-super-select">';
								$output .= '<div role="group" class="btn-group settings-icon-drop-down-styling field-setting s-super-select">';
									
									$output .= '<small>'.__('Dropdown Styling','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';	
											
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Width</span>';
										$output .= '</span>';
										$output .= '<input name="icon_dropdown_width" id="icon_dropdown_width" placeholder="Set width in pixels" class="form-control" value="">';
										
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Background</span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-dropdown-bg" name="icon-dropdown-bg" id="bs-color"></span>';

										$output .= '<input name="icon_dropdown_border" id="icon_dropdown_border" placeholder="Set border width in pixels" class="form-control" value="0">';
										
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Color</span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon_dropdown_border_color" name="icon_dropdown_border_color" id="bs-color"></span>';

									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';	
	/*** Input Styling ***/		
							
							$output .= '<div class="field-setting col-xs-12 no-batch-edit s-html s-paragraph">';
								
								
								$output .= '<small>'.__('Add Text or HTML','nex-forms').'</small>';
									$output .= '<textarea class="form-control" name="set_html" id="set_html" ></textarea>';
									
									
							$output .= '</div>';
							
							$output .= '<div class="field-setting no-batch-edit col-xs-12 s-text s-select s-spinner s-submit s-headings s-tags s-math s-math-slider">';	
								$output .= '<small>'.__('Default value & Input Styling','nex-forms').'</small>';
								$output .= '<input type="text" class="form-control field-setting s-headings s-math s-math-slider" name="set_heading_text" id="set_heading_text"  placeholder="Use {math_result} for math result place holder">';
							$output .= '</div>';
							$output .= '<div class="field-setting col-xs-12 s-text s-select s-spinner s-submit s-headings s-paragraph s-html s-tags s-math s-math-slider">';				
									$output .= '<div class="input-group input-group-sm">';
	/*** Input value ***/
										$output .= '<input type="text" class="form-control field-setting s-text" name="set_input_val" placeholder="Set default value" id="set_input_val">';
										$output .= '<input type="text" class="form-control field-setting s-select" name="set_default_select_value" placeholder="Set default option" id="set_default_select_value">';
										$output .= '<input type="text" class="form-control field-setting s-spinner" name="spin_start_value" id="spin_start_value"  placeholder="Enter start value">';
										$output .= '<input type="text" class="form-control field-setting s-submit" name="set_button_val" id="set_button_val"  placeholder="Enter button text">';
										
										$output .= '<input type="text" class="form-control field-setting s-tags" name="max_tags" id="max_tags"  placeholder="Enter maximum tags">';
										//$output .= '<span class="input-group-addon group-addon-label google_font" data-toggle="tooltip_bs" title="Google Fonts" data-original-title="Google Fonts"><i class="fa fa-font"></i></span>';
										$output .= '<select name="google_font_html" id="google_font_html" class="field-setting s-headings s-paragraph s-html s-math s-math-slider sfm form-control"><option value="">'.__('-- Font --','nex-forms').'</option><option value="">'.__('Default','nex-forms').'</option>';
											$get_google_fonts = new NF5_googlefonts();
											$output .= $get_google_fonts->get_google_fonts();
										$output .= '</select>';
										$output .= '<span class="input-group-addon action-btn input-bold" title="'.__('Bold','nex-forms').'">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon action-btn input-italic" title="'.__('Italic','nex-forms').'">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon action-btn input-underline none_material" title="'.__('Underline','nex-forms').'">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>';
	/*** Input text color ***/
										
										$output .= '<input type="text" class="form-control " name="set_font_size" id="set_font_size" value="13"  placeholder="Font Size">';
										
											$output .= '<span class="input-group-addon group-addon-label input-text-color" data-toggle="tooltip_bs" title="Text Color">TX</span><span class=" input-group-addon  action-btn color-picker input-text-color"><input type="text" class="form-control input-color" name="input-color" id="bs-color"></span>';
		/*** Input text color ***/
											$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color">BG</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control input-bg-color" name="input-bg-color" id="bs-color"></span>';
		/*** Input text color ***/	
		
											$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Icon BRD Width">BRD</span><input type="text" class="form-control " name="set_element_border_size" id="set_element_border_size" value="0"  placeholder="Border">';
											$output .= '<span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control input-border-color" name="input-border-color" id="bs-color"></span>';
											
										$output .= '<span class="input-group-addon text-alignment action-btn text-left none_material" title="'.__('Align Text Left','nex-forms').'">';
											$output .= '<span class="fa fa-align-left"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon text-alignment action-btn text-center none_material" title="'.__('Align Text Center','nex-forms').'">';
											$output .= '<span class="fa fa-align-center"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon text-alignment action-btn text-right none_material" title="'.__('Align Text Right','nex-forms').'">';
											$output .= '<span class="fa fa-align-right"></span>';
										$output .= '</span>';
									
										
									$output .= '</div>';
							
								$output .= '</div>';
							
	
	/**** SPINNER SETTINGS ****/
						
								
								
		/*** Min Value ***/
							$output .= '<div class="field-setting col-xs-3 s-spinner">';	
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<small>'.__('Min Value','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="spin_minimum_value" id="spin_minimum_value"  placeholder="'.__('Enter min value','nex-forms').'">';
								$output .= '</div>';
							$output .= '</div>';
		/*** Max Value ***/		
							$output .= '<div class="field-setting col-xs-3 s-spinner">';					
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<small>'.__('Max Value','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="spin_maximum_value" id="spin_maximum_value"  placeholder="'.__('Enter max value','nex-forms').'">';
								$output .= '</div>';
							$output .= '</div>';
		/*** Step Value ***/
							$output .= '<div class="field-setting col-xs-3 s-spinner">';						
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<small>'.__('Step','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="spin_step_value" id="spin_step_value"  placeholder="'.__('Enter step value','nex-forms').'">';
								$output .= '</div>';
							$output .= '</div>';
							
		/*** Decimals ***/	
							$output .= '<div class="field-setting col-xs-3 s-spinner">';
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<small>'.__('Decimals','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="spin_decimal" id="spin_decimal"  placeholder="'.__('Enter start value','nex-forms').'">';
								$output .= '</div>';
							$output .= '</div>';
											
	
							
	/*** Input alignment ***/
							
								/*$output .= '<div class="field-setting col-xs-3 s-text s-spinner s-select none_material">';	
									$output .= '<div role="group" class="btn-group align-input">';
										$output .= '<small>'.__('Text Alignment','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
	/*** Input size ***/
	
								/*$output .= '<div class="field-setting col-xs-4 s-text s-select s-spinner">';	
								$output .= '<div role="group" class="btn-group align-input-container">';
									$output .= '<small>Alignment</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
								$output .= '</div>';
							$output .= '</div>';*/
								
								$output .= '<div class="field-setting col-xs-3 s-text s-tags  s-spinner s-select none_material">';
									$output .= '<div role="group" class="btn-group input-size ">';
										$output .= '<small>'.__('Input Size','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf small" type="button" title="'.__('Small','nex-forms').'"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="'.__('Normal','nex-forms').'"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf large" type="button" title="'.__('Large','nex-forms').'"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-3 s-text s-tags s-spinner s-select none_material">';
									$output .= '<div role="group" class="btn-group input-corners ">';
										$output .= '<small>'.__('Corners','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf square" type="button" title="Square border"><i class="fas fa-square-full"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="Rounded Border"><i class="fa fa-square"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf pill" type="button" title="Pill"><i class="fa fa-circle"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
									
								$output .= '<div class="field-setting col-xs-3 s-text s-tags s-radios s-checks s-spinner s-select s-file-uploader s-multi-file-uploader none_material">';
									$output .= '<div role="group" class="btn-group input-disabled">';
										$output .= '<small>'.__('Disabled','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm no active" title="Editable<br />Field" type="button"><i class="fa fa-remove"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm yes" type="button" title="Uneditable<br />Field"><i class="fa fa-check"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								/*$output .= '<div class="field-setting col-xs-4 s-text">';	
									$output .= '<div role="group" class="btn-group recreate-field setting-recreate-field none_material">';
											$output .= '<small>'.__('Field Replication','nex-forms').'</small>';
											$output .= '<button class="btn btn-default not-rounded waves-effect-nf waves-light-nf enable-recreation" type="button" title="Enables Field Replication">Enable</button>';
											$output .= '<button class="btn btn-default not-rounded waves-effect-nf waves-light-nf disable-recreation active" type="button" title="Disables Field Replication">Disable</button>';
									$output .= '</div>';
								$output .= '</div>';*/
						
	
	/*** Button Options ***/
			/*** Button alignment ***/
								
									
								$output .= '<div class="field-setting col-xs-4 s-submit">';	
									$output .= '<div role="group" class="btn-group button-type">';
										$output .= '<small>'.__('Button Type','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf do-submit" type="button" 	title="Submit"><i class="fa fa-send"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf next" type="button" 	title="Next Step"><i class="fa fa-arrow-right"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf prev" type="button" title="Previous Step"><i class="fa fa-arrow-left"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-submit">';	
									$output .= '<div role="group" class="btn-group button-position">';
										$output .= '<small>'.__('Button Position','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" 	title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" 	title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								/*$output .= '<div class="field-setting col-xs-4 s-submit">';		
									$output .= '<div role="group" class="btn-group button-text-align">';
										$output .= '<small>'.__('Text Alignment','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							*/
							
							
			/*** Button size ***/
								$output .= '<div class="field-setting col-xs-4 s-submit">';	
									$output .= '<div role="group" class="btn-group button-size">';
										$output .= '<small>'.__('Button Size','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf small" type="button" title="'.__('Small','nex-forms').'"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="'.__('Normal','nex-forms').'"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf large" type="button" title="'.__('Large','nex-forms').'"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
									
								$output .= '<div class="field-setting col-xs-4 s-submit">';		
									$output .= '<div role="group" class="btn-group button-width">';
										$output .= '<small>'.__('Button Width','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf default" type="button" title="Default"><i class="fa fa-minus"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf full_button" type="button" title="Full"><i class="fa fa-arrows-alt-h"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-submit">';		
									$output .= '<div role="group" class="btn-group input-corners">';
										$output .= '<small>'.__('Corners','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf square" type="button" title="Square border"><i class="fas fa-square-full"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="Rounded Border"><i class="fa fa-square"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf pill" type="button" title="Pill"><i class="fa fa-circle"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								
								$output .= '<div class="field-setting col-xs-4 s-submit">';		
									$output .= '<div role="group" class="btn-group add-button-shine">';
										$output .= '<small>'.__('Add Shine Effect (on hover)','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf do_shine" type="button" title="Add Button Hover Shine"><i class="fa fa-check"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no_shine" type="button" title="No Shine"><i class="fa fa-close"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-12 s-divider">';
								$output .= '<small>'.__('Styling','nex-forms').'</small>';
								$output .= '<div class="input-group input-group-sm">';
									$output .= '<span class="input-group-addon group-addon-label" title="Divider Height">Height</span><input type="text" class="form-control" name="set_divider_height" id="set_divider_height" value="1">';
									$output .= '<span class="input-group-addon group-addon-label" title="Color">Color</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control input-border-color" name="input-border-color" id="bs-color"></span>';
								$output .= '</div>';
							$output .= '</div>';
							
	/*** HTML options ***/						
							
							
							
							/*$output .= '<div class="field-setting col-xs-12 s-html s-paragraph">';
								$output .= '<div class="input-group input-group-sm">';
									
									
									$output .= '<span class="input-group-addon spacer">';
										$output .= '<span class="icon-text"></span>';
									$output .= '</span>';
									$output .= '<input type="text" class="form-control" name="paragraph_font_size" id="paragraph_font_size" value="13"  placeholder="Font Size">';
									
									$output .= '<span class="input-group-addon action-btn input-bold" title="'.__('Bold','nex-forms').'">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';

										$output .= '<span class="input-group-addon action-btn input-italic" title="'.__('Italic','nex-forms').'">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
	
										$output .= '<span class="input-group-addon action-btn input-underline none_material" title="'.__('Underline','nex-forms').'">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>';
									$output .= '<span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control input-color" name="input-color" id="bs-color"></span>';
									
									
									
									$output .= '<span class="input-group-addon text-alignment action-btn text-left none_material" title="'.__('Align Text Left','nex-forms').'">';
											$output .= '<span class="fa fa-align-left"></span>';
										$output .= '</span>';
	
										$output .= '<span class="input-group-addon text-alignment action-btn text-center none_material" title="'.__('Align Text Center','nex-forms').'">';
											$output .= '<span class="fa fa-align-center"></span>';
										$output .= '</span>';
	
										$output .= '<span class="input-group-addon text-alignment action-btn text-right none_material" title="'.__('Align Text Right','nex-forms').'">';
											$output .= '<span class="fa fa-align-right"></span>';
										$output .= '</span>';
									
									
									
								$output .= '</div>';
							$output .= '</div>';*/
	/*** ICON OPTIONS ***/
	
						$output .= '<div class="field-setting col-xs-12 s-icon">';
	/*** Input PRE Add-on ***/
									$output .= '<small>'.__('Icon','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon action-btn current_icon_element"><i class="no-icon"></i></span>';
											$output .= '<input type="text" class="form-control" name="set_icon_element" id="set_icon_element"  placeholder="or enter icon class">';
											
											$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Icon Size">Size</span><input type="text" class="form-control " name="set_icon_size" id="set_icon_size" value="34"  placeholder="Icon Size">';
											$output .= '<span class="input-group-addon group-addon-label input-text-color" data-toggle="tooltip_bs" title="Text Color">TX</span><span class=" input-group-addon  action-btn color-picker input-text-color"><input type="text" class="form-control input-color" name="input-color" id="bs-color"></span>';
		/*** Input text color ***/
											$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color">BG</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control input-bg-color" name="input-bg-color" id="bs-color"></span>';
		/*** Input text color ***/
											//$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Color">BRD</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-border-color" name="icon-border-color" id="bs-color"></span>';
											
											$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Icon BRD Width">BRD</span><input type="text" class="form-control " name="set_icon_border_size" id="set_icon_border_size" value="0"  placeholder="Icon Border">';
											$output .= '<span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-border-color" name="icon-border-color" id="bs-color"></span>';
										
											$output .= '<span class="input-group-addon icon-alignment action-btn icon-left none_material" title="'.__('Align Icon Left','nex-forms').'">';
											$output .= '<span class="fa fa-align-left"></span>';
											$output .= '</span>';
		/*** Input italic ***/
											$output .= '<span class="input-group-addon icon-alignment action-btn icon-center none_material" title="'.__('Align Icon Center','nex-forms').'">';
												$output .= '<span class="fa fa-align-center"></span>';
											$output .= '</span>';
		/*** Input underline ***/
											$output .= '<span class="input-group-addon icon-alignment action-btn icon-right none_material" title="'.__('Align Icon Right','nex-forms').'">';
												$output .= '<span class="fa fa-align-right"></span>';
											$output .= '</span>';
									
									$output .= '</div>';
						$output .= '</div>';
	/*** Heading Options ***/
			/*** Heading Size ***/
							
							$output .= '<div class="field-setting col-xs-8 s-headings s-math">';		
								$output .= '<div role="group" class="btn-group heading-size heading-settings">';
									$output .= '<small>'.__('Heading Size','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf heading_1" type="button" title="Heading 1"><span class="btn-tx">H1</span></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf heading_2" type="button" title="Heading 2"><span class="btn-tx">H2</span></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf heading_3" type="button" title="Heading 3"><span class="btn-tx">H3</span></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf heading_4" type="button" title="Heading 4"><span class="btn-tx">H4</span></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf heading_5" type="button" title="Heading 5"><span class="btn-tx">H5</span></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf heading_6" type="button" title="Heading 6"><span class="btn-tx">H6</span></button>';
								$output .= '</div>';
							$output .= '</div>';
			/*** heading alignment ***/		
							/*$output .= '<div class="field-setting col-xs-4 s-headings s-html s-paragraph s-math">';			
								$output .= '<div role="group" class="btn-group heading-text-align heading-settings settings-html">';
									$output .= '<small>'.__('Text Alignment','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
								$output .= '</div>';
							$output .= '</div>';*/		
							
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-math-slider">';
								$output .= '<div class="settings-header"><span>'.__('Slider Styling &amp; Setup','nex-forms').'</span></div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-12 s-math-slider">';
									$output .= '<small>'.__('Slider Styling &amp; Max Range','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<input type="text" class="form-control" name="set_maximum_range" id="set_maximum_range"  placeholder="Maximum Range, default: 1000">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Total Background Color">TBG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-math-slider-handel-bg-color" name="set-math-slider-handel-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Slide Background">BG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-math-slider-bg-color" name="set-math-slider-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Slide Background Fill">BGF</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-math-slider-fill-color" name="set-math-slider-fill-color" id="bs-color"></span>';
									
									$output .= '</div>';
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-6 s-math-slider">';
									$output .= '<div role="group" class="btn-group math-slider-size ">';
										$output .= '<small>'.__('Slider Size','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf small" type="button" title="'.__('Small','nex-forms').'"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="'.__('Normal','nex-forms').'"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf large" type="button" title="'.__('Large','nex-forms').'"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-3 s-math-slider none_material">';
									$output .= '<div role="group" class="btn-group math-slider-corners ">';
										$output .= '<small>'.__('Corners','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="Circular"><i class="fa fa-circle"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf pill" type="button" title="Rounded Border"><i class="fa fa-square"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf square" type="button" title="Square border"><i class="fas fa-square-full"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-paragraph s-headings s-html s-divider s-icon s-math s-math-slider">';
								$output .= '<div class="settings-header"><span>'.__('Outer Wrapper Styling','nex-forms').'</span></div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-divider s-icon s-math s-math-slider">';
								$output .= '<small>'.__('Margins','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_margin_left" id="html_field_spacing_margin_left" class="form-control" value="0">';
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_margin_right" id="html_field_spacing_margin_right" class="form-control" value="0">';
									

								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-divider s-icon s-math s-math-slider">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_margin_top" id="html_field_spacing_margin_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_margin_bottom" id="html_field_spacing_margin_bottom" class="form-control" value="15">';
									
									
									$output .= '<span class="reset-button reset-html-field-margins">';
										$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									$output .= '</span>';
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						
				
						$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-divider s-icon s-math s-math-slider">';
								$output .= '<small>'.__('Padding','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_padding_left" id="html_field_spacing_padding_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_padding_right" id="html_field_spacing_padding_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-divider s-icon s-math s-math-slider">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_padding_top" id="html_field_spacing_padding_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_spacing_padding_bottom" id="html_field_spacing_padding_bottom" class="form-control" value="0">';
									
									
									$output .= '<span class="reset-button reset-html-field-padding">';
										$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									$output .= '</span>';
									
								$output .= '</div>';
							$output .= '</div>';
						
						
						
						
						$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-divider s-icon s-math s-math-slider">';
								$output .= '<small>'.__('Border Radius','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top Left</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_border_radius_top_left" id="html_field_border_radius_top_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top Right</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_border_radius_top_right" id="html_field_border_radius_top_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-paragraph s-headings s-html s-divider s-icon s-math s-math-slider">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom Left</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_border_radius_bottom_left" id="html_field_border_radius_bottom_left" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom Right</span>';
									$output .= '</span>';
									$output .= '<input name="html_field_border_radius_bottom_right" id="html_field_border_radius_bottom_right" class="form-control" value="0">';
									
									
									/*$output .= '<span class="reset-button reset-field-padding">';
										$output .= '<span class="fa fa-refresh" title="Reset to Default"></span>';
									$output .= '</span>';*/
									
								$output .= '</div>';
							$output .= '</div>';
	
	/*** Panel Options ***/
			
							
								$output .= '<div class="field-setting col-xs-12 s-panel">';
									$output .= '<small>'.__('Panel','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										$output .= '<input type="text" class="form-control" name="set_panel_heading" id="set_panel_heading"  placeholder="Panel Heading">';

										$output .= '<span class="input-group-addon action-btn panel-heading-bold" title="'.__('Bold','nex-forms').'" style="border-right:1px solid #ccc">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon action-btn panel-heading-italic" title="'.__('Italic','nex-forms').'">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon action-btn panel-heading-underline" title="'.__('Underline','nex-forms').'">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-6 s-panel">';
									$output .= '<div role="group" class="input-group input-group-sm">';		
											$output .= '<span class="input-group-addon group-addon-label" title="Panel Heading Text Color">TX</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-panel-heading-text-color" name="set-panel-heading-text-color" id="bs-color"></span>';
											$output .= '<span class="input-group-addon group-addon-label"  title="Panel Heading Background Color">BG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-panel-heading-bg-color" name="set-panel-heading-bg-color" id="bs-color"></span>';
											$output .= '<span class="input-group-addon group-addon-label" title="Panel Heading Border Color">BR</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-panel-heading-border-color" name="set-panel-heading-border-color" id="bs-color"></span>';	
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-6 s-panel">';
									$output .= '<div role="group" class="input-group input-group-sm">';		
											$output .= '<span class="input-group-addon group-addon-label" title="Panel Body Background">BBG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-panel-body-bg-color" name="set-panel-body-bg-color" id="bs-color"></span>';
											$output .= '<span class="input-group-addon group-addon-label" title="Panel Body Border Color">BBR</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-panel-body-border-color" name="set-panel-body-border-color" id="bs-color"></span>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-panel">';
									$output .= '<div role="group" class="btn-group show_panel-heading">';
										$output .= '<small>'.__('Show heading','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf yes" type="button" title="'.__('Show Panel Heading','nex-forms').'"><i class="fa fa-check"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no" type="button" title="'.__('Hide Panel Heading','nex-forms').'"><i class="fa fa-remove"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-panel">';	
									$output .= '<div role="group" class="btn-group panel-heading-text-align">';
										$output .= '<small>'.__('Text Alignment','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-panel">';	
									$output .= '<div role="group" class="btn-group panel-heading-size">';
										$output .= '<small>'.__('Heading Size','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf small" type="button" title="'.__('Small','nex-forms').'"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="'.__('Normal','nex-forms').'"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf large" type="button" title="'.__('Large','nex-forms').'"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							
	
	
	
	/*** Select options ***/	
							$output .= '<div class="field-setting no-batch-edit col-xs-6 s-select s-radios s-thumbs-select-single">';
								$output .= '<div role="group" class="btn-group select-auto-step" >';
									$output .= '<small>'.__('Auto Advance to next step?','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto-step-no active" type="button" title="'.__('Do not advance to next step<br />on selection','nex-forms').'"><i class="fa fa-close"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto-step-yes" type="button" title="'.__('Advance to next step<br />on selection','nex-forms').'"><i class="fas fa-check"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting no-batch-edit col-xs-4 s-thumbs-select">';
								$output .= '<div role="group" class="btn-group thumb-selection-type">';
									$output .= '<small>'.__('Selection Type','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf single-thumb-select active" type="button" title="'.__('Single option selection only','nex-forms').'"><i class="fas fa-check"></i></span></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf multi-thumd-select" type="button" title="'.__('Multiple option selections','nex-forms').'"><i class="fas fa-check-double"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting no-batch-edit col-xs-6 s-thumbs-select">';
								$output .= '<div role="group" class="btn-group thumb-auto-step" >';
									$output .= '<small>'.__('Auto Advance to next step?','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto-step-no active" type="button" title="'.__('Do not advance to next step<br />on selection','nex-forms').'"><i class="fa fa-close"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto-step-yes" type="button" title="'.__('Advance to next step<br />on selection','nex-forms').'"><i class="fas fa-check"></i></button>';
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting no-batch-edit col-xs-12 s-select">';
								$output .= '<div class="settings-select-options" >';
									$output .= '<small>'.__('Set Options','nex-forms').'</small>';
									$output .= '<textarea class="form-control" name="set_options" id="set_options" ></textarea>';
								$output .= '</div>';					
							$output .= '</div>';
	/*** Radio AND Check options ***/						
							$output .= '<div class="field-setting no-batch-edit col-xs-12 s-radios s-checks s-thumbs-select-single s-thumbs-select s-thumbs-select-multi">';
								$output .= '<small>'.__('Set Options','nex-forms').'</small>';
								$output .= '<textarea class="form-control" name="set_radios" id="set_radios" ></textarea>';
							$output .= '</div>';
							
	/*** Autocomplete options ***/						
							$output .= '<div class="field-setting col-xs-12 no-batch-edit s-autocomplete">';
								$output .= '<small>'.__('Set Selection list','nex-forms').'</small>';
								$output .= '<textarea class="form-control" name="set_selections" id="set_selections"></textarea>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-12 s-text s-select s-tags">';
	/*** Input PRE Add-on ***/
									$output .= '<small>'.__('Set Icon before','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon action-btn current_icon_before"><i class="no-icon"></i></span>';
											$output .= '<input type="text" class="form-control" name="set_icon_before" id="set_icon_before"  placeholder="or enter icon class">';
											$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Icon Size">Size</span><input type="text" class="form-control " name="set_icon_font_size_before" id="set_icon_font_size_before" value="17"  placeholder="Icon Size">';
											$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Text Color">TX</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control pre-icon-text-color" name="pre-icon-text-color" id="bs-color"></span>';
											$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color">BG</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control pre-icon-bg-color" name="pre-icon-text-color" id="bs-color"></span>';
											$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Color">BRD</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control pre-icon-border-color" name="pre-icon-border-color" id="bs-color"></span>';
											
									$output .= '</div>';
	/*** Input POST Add-on ***/
	
								$output .= '<div class="none_material">';
									$output .= '<small>'.__('Set Icon After','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon action-btn current_icon_after"><i class="no-icon"></i></span>';
										$output .= '<input type="text" class="form-control" name="set_icon_after" id="set_icon_after"  placeholder="or enter icon class">';
										$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Icon Size">Size</span><input type="text" class="form-control " name="set_icon_font_size_after" id="set_icon_font_size_after" value="17"  placeholder="Icon Size">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Text Color">TX</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control post-icon-text-color" name="post-icon-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color">BG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control post-icon-bg-color" name="post-icon-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Color">BRD</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control post-icon-border-color" name="post-icon-border-color" id="bs-color"></span>';
										
									$output .= '</div>';
								$output .= '</div>';
									
									
									
							$output .= '</div>';
				
							
							
							
							
							
							
							
							
							
	
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-thumbs-select">';	
								$output .= '<div class="settings-header"><span>'.__('Thumb Styling','nex-forms').'</span></div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';	
									$output .= '<small class="">'.__('Image Wrapper','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Padding" data-original-title="Padding">Padding</span>';
										$output .= '<input type="text" class="form-control" name="image-wrapper-padding" id="image-wrapper-padding" value="5"  placeholder="Padding">';
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color" data-original-title="Background Color">Background</span>';
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control image-wrapper-color" name="image-wrapper-color" id="bs-color"></span>';

									$output .= '</div>';
								$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';
									$output .= '<small class="">'.__('Image Border','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
										
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Width" data-original-title="Border Width">Width</span>';
										$output .= '<input type="text" class="form-control" name="image-wrapper-border-width" id="image-wrapper-border-width" value="0"  placeholder="Border Width">';
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Radius" data-original-title="Border Radius">Radius</span>';
										$output .= '<input type="text" class="form-control" name="image-wrapper-border-radius" id="image-wrapper-border-radius" value="0"  placeholder="Border Radius">';
									
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control image-border-color" name="image-border-color" id="bs-color"></span>';
										
									
									$output .= '</div>';
								$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';	
									$output .= '<small class="">'.__('Image Size','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
										
										
										$output .= '<span class="input-group-addon action-btn set-dimentions image-auto active" title="'.__('Auto','nex-forms').'">';
											$output .= '<span class="fa fa-arrows"></span>&nbsp;&nbsp;'.__('Auto','nex-forms').'&nbsp;&nbsp;';
										$output .= '</span>';
										
										$output .= '<span class="input-group-addon action-btn set-dimentions image-crop" title="'.__('Fixed','nex-forms').'">';
											$output .= '<span class="fas fa-crop-alt"></span>&nbsp;&nbsp;'.__('Fixed','nex-forms').'&nbsp;&nbsp;';
										$output .= '</span>';
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Image Width" data-original-title="Image Width">Width</span>';
										$output .= '<input type="text" class="form-control" name="thumb-image-width" id="thumb-image-width" value="100"  placeholder="Set Width">';
										
										//$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Image Height" data-original-title="Image Height">Height</span>';
										//$output .= '<input type="text" class="form-control" name="thumb-image-height" id="thumb-image-height" value="100"  placeholder="Set Height">';
										
										
										
										//$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Image Width" data-original-title="Image Width">Width</span>';
										//$output .= '<input type="text" class="form-control" name="thumb-image-width" id="thumb-image-heigh" value="100"  placeholder="Padding">';
										
										
										
									$output .= '</div>';
								$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';		
									$output .= '<div role="group" class="btn-group display-radios-checks">';
											$output .= '<small>'.__('Width Distribution','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf 1c" type="button" title="1 Thumb per row"><i class="fas fa-arrows-alt-h"></i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 2c" type="button" title="2 Thumbs per row"><i class="btn-tx tx-lg">&frac12;</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 3c" type="button" title="3 Thumbs per row"><i class="btn-tx tx-lg">&frac13;</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 4c" type="button" title="4 Thumbs per row"><i class="btn-tx tx-lg">&frac14;</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 6c" type="button" title="6 Thumbs per row"><i class="btn-tx tx-lg">&frac16;</i></button>';
										$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-3 s-thumbs-select">';		
									$output .= '<div role="group" class="btn-group thumbs-direction">';
											$output .= '<small>'.__('Direction','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf inline" type="button" title="Display Inline"><i class="fas fa-arrow-right"></i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 1c" type="button" title="Display Block"><i class="fas fa-arrow-down"></i></button>';
										$output .= '</div>';
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-3 s-thumbs-select">';	
										$output .= '<div role="group" class="btn-group align-thumbs">';
														$output .= '<small>Thumb Alignment</small>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
													$output .= '</div>';
									$output .= '</div>';	
							
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-thumbs-select">';	
								$output .= '<div class="settings-header"><span>'.__('Label Styling','nex-forms').'</span></div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-12 s-thumbs-select">';	
									$output .= '<small class="">'.__('Label Text','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
										
										
										
										/*** Label text bold ***/
										$output .= '<span class="input-group-addon action-btn thumb-label-pos set-label-bottom active" title="'.__('Set Label Above','nex-forms').'">';
											$output .= '<span class="fa fa-arrow-down"></span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon action-btn thumb-label-pos set-label-top" title="'.__('Set Label Below','nex-forms').'">';
											$output .= '<span class="fa fa-arrow-up"></span>';
										$output .= '</span>';
										
										
										$output .= '<span class="input-group-addon action-btn thumb-bold" title="'.__('Bold','nex-forms').'">';
											$output .= '<span class="fa fa-bold"></span>';
										$output .= '</span>';
		/*** Label text italic ***/
										$output .= '<span class="input-group-addon action-btn thumb-italic" title="'.__('Italic','nex-forms').'">';
											$output .= '<span class="fa fa-italic"></span>';
										$output .= '</span>';
		/*** Label text underline ***/
										$output .= '<span class="input-group-addon action-btn thumb-underline" title="'.__('Underline','nex-forms').'">';
											$output .= '<span class="fa fa-underline"></span>';
										$output .= '</span>'; 
										
										$output .= '<span class="input-group-addon thumb-text-alignment action-btn text-left none_material" title="'.__('Align Text Left','nex-forms').'">';
											$output .= '<span class="fa fa-align-left"></span>';
										$output .= '</span>';
	/*** Input italic ***/
										$output .= '<span class="input-group-addon thumb-text-alignment action-btn text-center none_material" title="'.__('Align Text Center','nex-forms').'">';
											$output .= '<span class="fa fa-align-center"></span>';
										$output .= '</span>';
	/*** Input underline ***/
										$output .= '<span class="input-group-addon thumb-text-alignment action-btn text-right none_material" title="'.__('Align Text Right','nex-forms').'">';
											$output .= '<span class="fa fa-align-right"></span>';
										$output .= '</span>';
		/*** Label text color ***/
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Label Color">Color</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-radio-label-color" name="set-radio-label-color" id="bs-color"></span>';
										
										$output .= '<select name="google_fonts_thumbs" id="google_fonts_thumbs" class="sfm form-control"><option value="">'.__('Font','nex-forms').'</option><option value="">'.__('Default','nex-forms').'</option>';
										$get_google_fonts = new NF5_googlefonts();
										$output .= $get_google_fonts->get_google_fonts();
										$output .= '</select>';
										$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Size</span>';
										$output .= '</span>';
										$output .= '<input type="text" class="form-control" name="set_thumb_font_size" id="set_thumb_font_size" value="13"  placeholder="Font Size">';

									$output .= '</div>';
								$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';	
									$output .= '<small class="">'.__('Label Wrapper','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Padding" data-original-title="Padding">Padding</span>';
										$output .= '<input type="text" class="form-control" name="label-wrapper-padding" id="label-wrapper-padding" value="5"  placeholder="Padding">';
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color" data-original-title="Background Color">Background</span>';
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control label-wrapper-color" name="label-wrapper-color" id="bs-color"></span>';
										
										
										
									$output .= '</div>';
								$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';	
									$output .= '<small class="">'.__('Label Border','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
										
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Width" data-original-title="Border Width">Width</span>';
										$output .= '<input type="text" class="form-control" name="label-wrapper-border-width" id="label-wrapper-border-width" value="0"  placeholder="Border Width">';
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Radius" data-original-title="Border Radius">Radius</span>';
										$output .= '<input type="text" class="form-control" name="label-wrapper-border-radius" id="label-wrapper-border-radius" value="0"  placeholder="Border Radius">';
										
										$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control label-border-color" name="label-border-color" id="bs-color"></span>';
										
										
									$output .= '</div>';
								$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';
								$output .= '<small>'.__('Label Wrapper Margin','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="set_thumb_label_margin_left" id="set_thumb_label_margin_left" class="form-control" value="0">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="set_thumb_label_margin_right" id="set_thumb_label_margin_right" class="form-control" value="0">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="set_thumb_label_margin_top" id="set_thumb_label_margin_top" class="form-control" value="0">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="set_thumb_label_margin_bottom" id="set_thumb_label_margin_bottom" class="form-control" value="0">';
									
									
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-thumbs-select">';	
								$output .= '<div class="settings-header"><span>'.__('Checked Styling','nex-forms').'</span></div>';
							$output .= '</div>';
							$output .= '<div class="field-setting col-xs-12 s-thumbs-select">';
									$output .= '<small class="">'.__('Checked Icon Styling','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm none_material">';
										
										$output .= '<span class="input-group-addon current_radio_icon"><i class="">Select Icon</i></span>';
										$output .= '<input type="text" class="form-control" name="set_radio_icon" id="set_radio_icon"  placeholder="or enter icon class">';
										
										
										$output .= '<span class="input-group-addon checked-radius action-btn icon_round active" title="'.__('Circle Checked','nex-forms').'">';
											$output .= '<span class="fa fas fa-circle"></span>';
										$output .= '</span>';
										
										$output .= '<span class="input-group-addon checked-radius action-btn icon_squared  " title="'.__('Square Checked','nex-forms').'">';
											$output .= '<span class="fa fas fa-square"></span>';
										$output .= '</span>';
										
										
										//$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Vertical Position" data-original-title="Vertical Position">V</span>';
										
										$output .= '<span class="input-group-addon checked-v-position action-btn v_center  active" title="'.__('Verticaly align Checked Icon Center','nex-forms').'">';
											$output .= '<span class="fa fas fa-arrows-alt-v"></span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon checked-v-position action-btn v_top " title="'.__('Verticaly align Checked Icon Top','nex-forms').'">';
											$output .= '<span class="fa fas fa-arrow-up"></span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon checked-v-position action-btn v_bottom " title="'.__('Verticaly align Checked Icon Bottom','nex-forms').'">';
											$output .= '<span class="fa  fas fa-arrow-down"></span>';
										$output .= '</span>';
										
										//$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Horizontal Position" data-original-title="Vertical Position">H</span>';
										
										$output .= '<span class="input-group-addon checked-h-position action-btn h_center  active" title="'.__('Horizontaly align Checked Icon Center','nex-forms').'">';
											$output .= '<span class="fa fas fa-arrows-alt-h"></span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon checked-h-position action-btn h_left " title="'.__('Horizontaly align Checked Icon left','nex-forms').'">';
											$output .= '<span class="fa fas fa-arrow-left"></span>';
										$output .= '</span>';
										$output .= '<span class="input-group-addon checked-h-position action-btn h_right " title="'.__('Horizontaly align Checked Icon Right','nex-forms').'">';
											$output .= '<span class="fa fas fa-arrow-right"></span>';
										$output .= '</span>';
										
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color (checked)">BG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-radio-bgc-color" name="set-radio-bgc-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Icon Color">IC</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-radio-text-color" name="set-radio-text-color" id="bs-color"></span>';
									$output .= '</div>';
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';
								$output .= '<small>'.__('Checked/Unckecked Animations','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Checked</span>';
									$output .= '</span>';
									$output .= '<select id="check_image_animation" class="form-control" name="check_image_animation">
															  <option selected="selected" value="fadeInDown">Default (fadeInDown)</option>
															  <option value="none">No Animation</option>
																	<optgroup label="Attention Seekers">
																	  <option value="bounce">bounce</option>
																	  <option value="flash">flash</option>
																	  <option value="pulse">pulse</option>
																	  <option value="rubberBand">rubberBand</option>
																	  <option value="shake">shake</option>
																	  <option value="swing">swing</option>
																	  <option value="tada">tada</option>
																	  <option value="wobble">wobble</option>
																	  <option value="jello">jello</option>
																	</optgroup>
															
																	<optgroup label="Bouncing Entrances">
																	  <option value="bounceIn">bounceIn</option>
																	  <option value="bounceInDown">bounceInDown</option>
																	  <option value="bounceInLeft">bounceInLeft</option>
																	  <option value="bounceInRight">bounceInRight</option>
																	  <option value="bounceInUp">bounceInUp</option>
																	</optgroup>
													
															
													
															<optgroup label="Fading Entrances">
															  <option value="fadeIn">fadeIn</option>
															  <option value="fadeInDown">fadeInDown</option>
															  <option value="fadeInDownBig">fadeInDownBig</option>
															  <option value="fadeInLeft">fadeInLeft</option>
															  <option value="fadeInLeftBig">fadeInLeftBig</option>
															  <option value="fadeInRight">fadeInRight</option>
															  <option value="fadeInRightBig">fadeInRightBig</option>
															  <option value="fadeInUp">fadeInUp</option>
															  <option value="fadeInUpBig">fadeInUpBig</option>
															</optgroup>
													
															
															<optgroup label="Flippers">
															  <option value="flip">flip</option>
															  <option value="flipInX">flipInX</option>
															  <option value="flipInY">flipInY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedIn">lightSpeedIn</option>
															</optgroup>
													
															<optgroup label="Rotating Entrances">
															  <option value="rotateIn">rotateIn</option>
															  <option value="rotateInDownLeft">rotateInDownLeft</option>
															  <option value="rotateInDownRight">rotateInDownRight</option>
															  <option value="rotateInUpLeft">rotateInUpLeft</option>
															  <option value="rotateInUpRight">rotateInUpRight</option>
															</optgroup>
													
															
													
															<optgroup label="Sliding Entrances">
															  <option value="slideInUp">slideInUp</option>
															  <option value="slideInDown">slideInDown</option>
															  <option value="slideInLeft">slideInLeft</option>
															  <option value="slideInRight">slideInRight</option>
													
															</optgroup>
															
															
															<optgroup label="Zoom Entrances">
															  <option value="zoomIn">zoomIn</option>
															  <option value="zoomInDown">zoomInDown</option>
															  <option value="zoomInLeft">zoomInLeft</option>
															  <option value="zoomInRight">zoomInRight</option>
															  <option value="zoomInUp">zoomInUp</option>
															</optgroup>
															
															
													
															<optgroup label="Specials">
															  <option value="rollIn">rollIn</option>
															</optgroup>
														  </select>';
									
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-thumbs-select">';
								$output .= '<small>&nbsp;</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Unchecked</span>';
									$output .= '</span>';
									$output .= '<select id="uncheck_image_animation" class="form-control" name="uncheck_image_animation">
															  <option selected="selected" value="fadeOutUp">Default (fadeOutUp)</option>
															  <option value="none">No Animation</option>
																	
													
															<optgroup label="Bouncing Exits">
															  <option value="bounceOut">bounceOut</option>
															  <option value="bounceOutDown">bounceOutDown</option>
															  <option value="bounceOutLeft">bounceOutLeft</option>
															  <option value="bounceOutRight">bounceOutRight</option>
															  <option value="bounceOutUp">bounceOutUp</option>
															</optgroup>
													
															
													
															<optgroup label="Fading Exits">
															  <option value="fadeOut">fadeOut</option>
															  <option value="fadeOutDown">fadeOutDown</option>
															  <option value="fadeOutDownBig">fadeOutDownBig</option>
															  <option value="fadeOutLeft">fadeOutLeft</option>
															  <option value="fadeOutLeftBig">fadeOutLeftBig</option>
															  <option value="fadeOutRight">fadeOutRight</option>
															  <option value="fadeOutRightBig">fadeOutRightBig</option>
															  <option value="fadeOutUp">fadeOutUp</option>
															  <option value="fadeOutUpBig">fadeOutUpBig</option>
															</optgroup>
													
															<optgroup label="Flippers">
															  <option value="flipOutX">flipOutX</option>
															  <option value="flipOutY">flipOutY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedOut">lightSpeedOut</option>
															</optgroup>
													
															
													
															<optgroup label="Rotating Exits">
															  <option value="rotateOut">rotateOut</option>
															  <option value="rotateOutDownLeft">rotateOutDownLeft</option>
															  <option value="rotateOutDownRight">rotateOutDownRight</option>
															  <option value="rotateOutUpLeft">rotateOutUpLeft</option>
															  <option value="rotateOutUpRight">rotateOutUpRight</option>
															</optgroup>
													
															
															<optgroup label="Sliding Exits">
															  <option value="slideOutUp">slideOutUp</option>
															  <option value="slideOutDown">slideOutDown</option>
															  <option value="slideOutLeft">slideOutLeft</option>
															  <option value="slideOutRight">slideOutRight</option>
															  
															</optgroup>
															
															
															
															<optgroup label="Zoom Exits">
															  <option value="zoomOut">zoomOut</option>
															  <option value="zoomOutDown">zoomOutDown</option>
															  <option value="zoomOutLeft">zoomOutLeft</option>
															  <option value="zoomOutRight">zoomOutRight</option>
															  <option value="zoomOutUp">zoomOutUp</option>
															</optgroup>
													
															<optgroup label="Specials">
															  <option value="hinge">hinge</option>
															  <option value="rollOut">rollOut</option>
															</optgroup>
														  </select>';
									
									

								$output .= '</div>';
							$output .= '</div>';
							
							
							
							$output .= '<div class="field-setting col-xs-12 s-radios s-checks s-thumbs-select-single s-thumbs-select-multi none_material">';
									$output .= '<small class="">'.__('Option Styling','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm none_material">';
										
										$output .= '<span class="input-group-addon current_radio_icon"><i class="">Select Icon</i></span>';
										$output .= '<input type="text" class="form-control" name="set_radio_icon" id="set_radio_icon"  placeholder="or enter icon class">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Label Colors">LC</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-radio-label-color" name="set-radio-label-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Background Color (checked)">BG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-radio-bgc-color" name="set-radio-bgc-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Icon Color">IC</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-radio-text-color" name="set-radio-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Border Color">BRD</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-radio-border-color" name="set-radio-border-color" id="bs-color"></span>';
									$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="field-setting col-xs-6 s-radios s-checks s-thumbs-select-single s-thumbs-select-multi none_material">';		
									$output .= '<div role="group" class="btn-group display-radios-checks">';
											$output .= '<small>'.__('Layout','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf inline" title="Inline" type="button"><i class="fas fa-arrow-right"></i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 1c" type="button" title="1 Column"><i class="fas fa-arrow-down"></i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 2c" type="button" title="2 Columns"><i class="btn-tx">2c</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 3c" type="button" title="3 Columns"><i class="btn-tx">3c</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 4c" type="button" title="4 Columns"><i class="btn-tx">4c</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 6c" type="button" title="6 Columns"><i class="btn-tx">6c</i></button>';
										$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							
							$output .= '<div class="field-setting col-xs-3 s-thumbs-select-single s-thumbs-select-multi ">';	
									$output .= '<div role="group" class="btn-group thumb-size">';
										$output .= '<small>'.__('Thumb Size','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf small" type="button" title="'.__('Small','nex-forms').'"><i class="fa fa-font" style="font-size:10px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal" type="button" title="'.__('Normal','nex-forms').'"><i class="fa fa-font" style="font-size:13px"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf large" type="button" title="'.__('Large','nex-forms').'"><i class="fa fa-font" style="font-size:16px"></i></button>';
									$output .= '</div>';
							$output .= '</div>';
							
							/*** Slider Styling ***/
							$output .= '<div class="field-setting col-xs-12 s-slider none_material">';
									$output .= '<small>'.__('Slider Styling','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<input type="text" class="form-control" name="count_text" id="count_text"  placeholder="{x}=Count placeholder">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Handle Text Color">HTX</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-slider-handel-text-color" name="set-slider-handel-text-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Handle Background Color">HBG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-slider-handel-bg-color" name="set-slider-handel-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Handle Border Color">HBR</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-slider-handel-border-color" name="set-slider-handel-border-color" id="bs-color"></span>';	
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Slide Background">BG</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-slider-bg-color" name="set-slider-bg-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Slide Background Fill">BGF</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-slider-fill-color" name="set-slider-fill-color" id="bs-color"></span>';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Slide Border">BR</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control set-slider-border-color" name="set-slider-border-color" id="bs-color"></span>';	
									
									$output .= '</div>';
							$output .= '</div>';
							
							
	
							
							$output .= '<div class="field-setting is-settings-header col-xs-12 s-text s-tags s-select s-spinner s-date s-time s-password s-panel none_material">';
								$output .= '<div class="settings-header"><span>'.__('Background Settings','nex-forms').'</span></div>';
							$output .= '</div>';
	/*** Background settings ***/	
								$output .= '<div class="field-setting col-xs-12 s-text s-tags s-select s-spinner s-date s-time s-password s-panel none_material">';	
									$output .= '<div role="toolbar" class="btn-toolbar bg-settings">';
	/*** Background image ***/									
										$output .= '<div role="group" class="btn-group align-label">';
											$output .= '<small>'.__('Image','nex-forms').'</small>';
											$output .= '<form name="do-upload-image" id="do-upload-image" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">';
												$output .= '<input type="hidden" name="action" value="do_upload_image">';
												$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">';
													$output .= '<div class="the_input_element fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100px; height: 100px;"></div>';
													$output .= '<div class="upload-image-controls">';
														$output .= '<span class="input-group-addon btn-file the_input_element error_message" data-content="Please select an image" data-secondary-message="Invalid image extension" data-placement="top">';
															$output .= '<span class="fileinput-new"><span class="fa fa-cloud-upload"></span></span>';
															$output .= '<span class="fileinput-exists"><span class="fa fa-edit"></span></span>';
															$output .= '<input type="file" name="do_image_upload_preview" >';
														$output .= '</span>';
														$output .= '<a href="#" class="input-group-addon fileinput-exists" data-dismiss="fileinput"><span class="fa fa-close"></span></a>';
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</form>';
											
											
											
										$output .= '</div>';
	/*** Background size ***/									
										$output .= '<div role="group" class="btn-group bg-size">';
											$output .= '<small>'.__('Size','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf auto" type="button" title="Auto"><i class="btn-tx">Auto</i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf contain" type="button" title="Contain"><i class="fa fa-compress"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf cover" type="button" title="Cover"><i class="fa fa-expand"></i></button>';
										$output .= '</div>';
	/*** Background repeat ***/									
										$output .= '<div role="group" class="btn-group bg-repeat">';
											$output .= '<small>'.__('Repeat','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat" type="button" title="Repeat X &amp; Y"><i class="fa fa-arrows"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-x" type="button" title="Repeat X"><i class="fa fa-arrows-h"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf repeat-y" type="button" title="Repeat Y"><i class="fa fa-arrows-v"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no-repeat" type="button" title="None"><i class="fa fa-close"></i></button>';
										$output .= '</div>';
	/*** Background position ***/									
										$output .= '<div role="group" class="btn-group bg-position">';
											$output .= '<small>'.__('Position','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
										$output .= '</div>';
									
									$output .= '</div>';
								
								$output .= '</div>';
	
	/**** THUMB RATING SETTINGS ****/
								$output .= '<div class="field-setting col-xs-6 s-thumb-rating">';
									$output .= '<small>'.__('Thumbs Up','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_thumbs_up_val" placeholder="'.__('Yes','nex-forms').'" id="set_thumbs_up_val">';
								$output .= '</div>';	
								
								$output .= '<div class="field-setting col-xs-6 s-thumb-rating">';
									$output .= '<small>'.__('Thumbs Down','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_thumbs_down_val" placeholder="'.__('No','nex-forms').'" id="set_thumbs_down_val">';
								$output .= '</div>';
	/**** SMILY RATING SETTINGS ****/
								$output .= '<div class="field-setting col-xs-4 s-smiley-rating">';
									$output .= '<small>'.__('Bad','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_smily_frown_val" placeholder="Bad" id="set_smily_frown_val">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-smiley-rating">';	
									$output .= '<small>'.__('Average','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_smily_average_val" placeholder="Average" id="set_smily_average_val">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-smiley-rating">';
									$output .= '<small>'.__('Good','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_smily_good_val" placeholder="Good" id="set_smily_good_val">';
								$output .= '</div>';
								
								
	
	
	/**** ICON FIELD SETTINGS ****/
								
									$output .= '<div class="field-setting col-xs-8 s-super-select">';	
										$output .= '<div role="group" class="btn-group set-icon-colums">';
											$output .= '<small>Layout</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf inline" title="Inline" type="button"><i class="fa fa-arrow-right"></i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 1c" type="button" title="1 Column"><i class="fa fa-arrow-down"></i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 2c" type="button" title="2 Columns"><i class="btn-tx">2c</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 3c" type="button" title="3 Columns"><i class="btn-tx">3c</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 4c" type="button" title="4 Columns"><i class="btn-tx">4c</i></button>
														<button class="btn btn-default waves-effect-nf waves-light-nf 6c" type="button" title="6 Columns"><i class="btn-tx">6c</i></button>';
										$output .= '</div>';
									$output .= '</div>';
									
									$output .= '<div class="field-setting col-xs-3 s-stars s-image s-thumb-rating  s-smiley-rating s-super-select s-radios s-checks s-thumbs-select-single s-thumbs-select-multi ">';	
										$output .= '<div role="group" class="btn-group align-input-container">';
														$output .= '<small>Alignment</small>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="Left"><i class="fa fa-align-left"></i></button>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="Center"><i class="fa fa-align-center"></i></button>';
														$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="Right"><i class="fa fa-align-right"></i></button>';
													$output .= '</div>';
									$output .= '</div>';
									
									if(function_exists('nf_super_select_field_settings'))
									{
									$output .= '<div class="field-setting is-settings-header col-xs-12 s-super-select">';	
										$output .= '<div class="settings-header"><span>'.__('Overall Option Setup & Styling','nex-forms').'</span></div>';
									$output .= '</div>';
									
									$output .= '<div class="setting-wrapper settings-icon-field field-setting col-xs-12 s-super-select">';		
										$output .= '<div role="group" class="">';
											
											
												$output .= '<small>Icons</small>';
												$output .= '<div class="input-group input-group-sm">';	
													$output .= '<span class="input-group-addon group-addon-label">OFF</span><span class="input-group-addon action-btn current_field_icon_off_overall icon-select" data-icon-target=".off-icon span"><i class=""><span class="small_addon_text">Icon</span></i></span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-field-icon-off-color-overall" name="icon-field-icon-off-color-overall" id="bs-color"></span>';
													$output .= '<span class="input-group-addon group-addon-label">ON</span><span class="input-group-addon action-btn current_field_icon_on_overall icon-select" data-icon-target=".on-icon span"><i class=""><span class="small_addon_text">Icon</span></i></span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-field-icon-on-color-overall" name="icon-field-icon-on-color-overall" id="bs-color"></span>';
													$output .= '<input name="icon_field_icon_size" id="icon_field_icon_size" class="form-control" value="30">';
													$output .= '<select name="icon_field_on_animation" id="icon_field_on_animation" class="form-control" data-selected="flipInY">
																	<option value="no_animation">= Set Animation =</option>
																	<option value="flipInY">Default</option>
																			  <option value="bounce">bounce</option>
																			  <option value="bounceIn">bounceIn</option>
																			  <option value="flash">flash</option>
																			  <option value="fadeIn">fadeIn</option>
																			  <option value="flip">flip</option>
																			  <option value="flipInX">flipInX</option>
																			  <option value="flipInY" selected="selected">flipInY</option>
																			  <option value="jello">jello</option>
																			  <option value="pulse">pulse</option>
																			  <option value="rotateIn">rotateIn</option>
																			  <option value="rubberBand">rubberBand</option>
																			  <option value="shake">shake</option>
																			  <option value="swing">swing</option>
																			  <option value="tada">tada</option>
																			  <option value="wobble">wobble</option>
																			  <option value="zoomIn">zoomIn</option>
																	</select>
																';
												$output .= '</div>';
											
								
											
										$output .= '</div>';
										
										
										$output .= '<div class="field-setting col-xs-12 s-super-select"></div>';
										
										
										
										$output .= '<div role="group" class="">';
											$output .= '<small>Labels</small>';
												
											$output .= '<div class="input-group input-group-sm">';	
												$output .= '<span class="input-group-addon group-addon-label">Off</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-field-label-off-color-overall" name="icon-field-label-off-color-overall" id="bs-color"></span>';
												$output .= '<span class="input-group-addon action-btn off-icon-label-bold" title="Bold">';
													$output .= '<span class="fa fa-bold"></span>';
												$output .= '</span>';
												$output .= '<span class="input-group-addon group-addon-label">On</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-field-label-on-color-overall" name="icon-field-label-on-color-overall" id="bs-color"></span>';
												$output .= '<span class="input-group-addon action-btn on-icon-label-bold" title="Bold">';
													$output .= '<span class="fa fa-bold"></span>';
												$output .= '</span>';	
												$output .= '<input name="icon_field_label_size" id="icon_field_label_size" class="form-control" value="15">';
												
												$output .= '<span class="input-group-addon action-btn icon-labels-position icon-label-hidden" data-set-class="icon-label-hidden"  title="Icon Labels Hidden"><span class="fa fa-eye-slash"></span></span>';
												$output .= '<span class="input-group-addon action-btn icon-labels-position icon-label-tip" data-set-class="icon-label-tip"  title="Icon Labels Tooltip"><span class="fas fa-comment-alt"></span></span>';
												$output .= '<span class="input-group-addon action-btn icon-labels-position icon-label-right" data-set-class="icon-label-right"  title="Icon Labels Right"><span class="fa fa-chevron-right"></span></span>';
												$output .= '<span class="input-group-addon action-btn icon-labels-position icon-label-top" data-set-class="icon-label-top"  title="Icon Labels Top"><span class="fa fa-chevron-up"></span></span>';
												$output .= '<span class="input-group-addon action-btn icon-labels-position icon-label-bottom" data-set-class="icon-label-bottom"  title="Icon Labels Bottom"><span class="fa fa-chevron-down"></span></span>';
												$output .= '<span class="input-group-addon action-btn icon-labels-position icon-label-left" data-set-class="icon-label-left"  title="Icon Labels Left"><span class="fa fa-chevron-left"></span></span>';
												
												
											$output .= '</div>';
										$output .= '</div>';
										
										
										$output .= '<div class="field-setting is-settings-header col-xs-12 no-batch-edit s-super-select">';	
											$output .= '<div class="settings-header"><span>'.__('Individual Option Setup & Styling','nex-forms').'</span></div>';
										$output .= '</div>';
										
										$output .= '<div class="field-setting col-xs-12 no-batch-edit s-super-select"><br />&nbsp;</div>';
										
										$output .= '<div role="group" class="icon-selection no-batch-edit">';
										$output .= '</div><div style="clear:both;"></div>';
										
										$output .= '<div role="group" class="input-group input-group-sm single-icon-settings cloneable">';
								
											$output .= '<span class="input-group-addon group-addon-label">Off</span><span class="input-group-addon action-btn current_field_icon_off" data-icon-target=""><i class="">Icon</i></span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-field-icon-off-color" name="icon-field-icon-off-color" id="bs-color"></span>';
											$output .= '<span class="input-group-addon group-addon-label">On</span><span class="input-group-addon action-btn current_field_icon_on" data-icon-target=""><i class="">Icon</i></span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control icon-field-icon-on-color" name="icon-field-icon-on-color" id="bs-color"></span>';
											$output .= '<span class="settings-add-on-group"><span class="settings-add-on-text">Val</span><input type="text" class="form-control" name="set_icon_value" id="set_icon_value"  placeholder="Set Value"></span>';
											$output .= '<span class="settings-add-on-group"><span class="settings-add-on-text">Tip</span><input type="text" class="form-control" name="set_icon_tooltip" id="set_icon_tooltip"  placeholder="Set Tooltip"></span>';
											
											$output .= '<span class="duplicate_delete">
															<span class="delete_icon fa fa-close" title="Delete Icon"></span>
															<span class="duplicate_icon fa fa-files-o" title="Duplicate Icon"></span>
														</span>';
								
										$output .= '</div>';
										$output .= '<div class="setting-buffer"></div>';
									$output .= '</div>';	
									}
								else
									{
									$output .= '<div class="field-setting col-xs-12 s-super-select">';	
										$output .= '<div class="alert alert-info">Please install <a href="https://codecanyon.net/item/super-selection-form-field-for-nexforms/23748570" target="_blank">Super Select Add-on for NEX-Forms</a> to customize this field.</div>';
									$output .= '</div>';
									}
									
									
							
	/**** STAR RATING SETTINGS ****/
	
	
							$output .= '<div class="field-setting col-xs-12 s-star-rating">';	
									$output .= '<small>'.__('Rating Settings','nex-forms').'</small>';
									$output .= '<div class="input-group input-group-sm">';
								
	/*** Text Alignment ***/		
									$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Total" data-original-title="Total">Total</span>';
									$output .= '<input type="text" class="form-control" name="total_stars" placeholder="Total stars" id="total_stars">';
										
										
									
									$output .= '<span class="input-group-addon action-btn set_half_stars no" title="'.__('Disable Half Ratings','nex-forms').'">';
										$output .= '<span class="fa fa-star"></span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon action-btn set_half_stars yes" title="'.__('Enable Half Ratings','nex-forms').'">';
										$output .= '<span class="fa fa-star-half"></span>';
									$output .= '</span>';
										
									
									$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Set Icon Size" data-original-title="Set Icon Size">Size</span>';
									$output .= '<input type="text" class="form-control" name="star_rating_size" placeholder="Set Size" id="star_rating_size" value="25">';
										
									$output .= '<span class="input-group-addon star-rating-alignment action-btn text-left none_material active" title="'.__('Align Left','nex-forms').'">';
										$output .= '<span class="fa fa-align-left"></span>';
									$output .= '</span>';
/*** Input italic ***/
									$output .= '<span class="input-group-addon star-rating-alignment action-btn text-center none_material" title="'.__('Align Center','nex-forms').'">';
										$output .= '<span class="fa fa-align-center"></span>';
									$output .= '</span>';
/*** Input underline ***/
									$output .= '<span class="input-group-addon star-rating-alignment action-btn text-right none_material" title="'.__('Align Right','nex-forms').'">';
										$output .= '<span class="fa fa-align-right"></span>';
									$output .= '</span>';
									
	
									
								$output .= '</div>';
							$output .= '</div>';
								
								
								
								/*$output .= '<div class="field-setting col-xs-4 s-star-rating">';
											$output .= '<small>'.__('Rating Total','nex-forms').'</small>';
											$output .= '<input type="text" class="form-control" name="total_stars" placeholder="Total stars" id="total_stars">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-star-rating">';
								$output .= '<div role="group" class="btn-group set_half_stars" >';
									$output .= '<small>'.__('Enable Half Ratings','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf no" type="button" title="'.__('No','nex-forms').'"><i class="fa fa-star"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf yes active" type="button" title="'.__('Yes','nex-forms').'"><i class="fa fa-star-half"></i></button>';
									
								$output .= '</div>';
							$output .= '</div>';
										
								
		
		
								$output .= '<div class="field-setting col-xs-4 s-star-rating">';	
										$output .= '<div role="group" class="btn-group align-input-container none_material">';
											$output .= '<small>'.__('Alignment','nex-forms').'</small>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left" type="button" title="'.__('Left','nex-forms').'"><i class="fa fa-align-left"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center" type="button" title="'.__('Center','nex-forms').'"><i class="fa fa-align-center"></i></button>';
											$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right" type="button" title="'.__('Right','nex-forms').'"><i class="fa fa-align-right"></i></button>';
										$output .= '</div>';
								$output .= '</div>';
						
							*/
							
							$output .= '<div class="field-setting col-xs-12 s-star-rating">';
	
	/*** Input POST Add-on ***/
	
									$output .= '<small>'.__('Set On Icon','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon action-btn current_icon_on setting_star_rating_on" data-edit-icon="star_rating_on"><i class="fa fa-star"></i></span>';
										$output .= '<input type="text" class="form-control" name="set_rating_icon_on" id="set_rating_icon_on"  placeholder="or enter icon class">';
										$output .= '<span class="input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Text Color">On Icon Color</span><span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control rating-on-icon-text-color" name="rating-on-icon-text-color" id="bs-color"></span>';
										
									$output .= '</div>';
/*** Input PRE Add-on ***/
									$output .= '<small>'.__('Set Off Icon','nex-forms').'</small>';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<span class="input-group-addon action-btn current_icon_off setting_star_rating_off" data-edit-icon="star_rating_off"><i class="fa fa-star-o"></i></span>';
											$output .= '<input type="text" class="form-control" name="set_rating_icon_off" id="set_rating_icon_off"  placeholder="or enter icon class">';
											$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Text Color">Off Icon Color</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control rating-off-icon-text-color" name="rating-off-icon-text-color" id="bs-color"></span>';
											
									$output .= '</div>';									
									
									$output .= '<div class="show-half-rating hidden">';
									$output .= '<small>'.__('Set Half Icon','nex-forms').'</small>';
										$output .= '<div role="group" class="input-group input-group-sm">';
											
											$output .= '<span class="input-group-addon action-btn current_icon_half setting_star_rating_half" data-edit-icon="star_rating_half"><i class="fa fa-star-half-o"></i></span>';
												$output .= '<input type="text" class="form-control" name="set_rating_icon_half" id="set_rating_icon_half"  placeholder="or enter icon class">';
												$output .= '<span class="none_material input-group-addon group-addon-label" data-toggle="tooltip_bs" title="Text Color">Half Icon Color</span><span class="none_material input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control rating-half-icon-text-color" name="rating-half-icon-text-color" id="bs-color"></span>';
												
										$output .= '</div>';	
									$output .= '</div>';	
									
							$output .= '</div>';
						
		
		/**** SLIDER SETTINGS ****/
								
										
											
				/*** Start Value ***/	
											$output .= '<div class="field-setting col-xs-3 s-slider">';
												$output .= '<small>'.__('Start Value','nex-forms').'</small>';
												$output .= '<input type="text" class="form-control" name="start_value" id="start_value"  placeholder="Enter start value">';
											$output .= '</div>';
				/*** Min Value ***/
											$output .= '<div class="field-setting col-xs-3 s-slider">';
												$output .= '<small>'.__('Min Value','nex-forms').'</small>';
												$output .= '<input type="text" class="form-control" name="minimum_value" id="minimum_value"  placeholder="Enter min value">';
											$output .= '</div>';
				/*** Max Value ***/				
											$output .= '<div class="field-setting col-xs-3 s-slider">';	
												$output .= '<small>'.__('Max Value','nex-forms').'</small>';
												$output .= '<input type="text" class="form-control" name="maximum_value" id="maximum_value"  placeholder="Enter max value">';
											$output .= '</div>';
				/*** Step Value ***/			
											$output .= '<div class="field-setting col-xs-3 s-slider">';	
												$output .= '<small>'.__('Step Value','nex-forms').'</small>';
												$output .= '<input type="text" class="form-control" name="step_value" id="step_value"  placeholder="Enter step value">';
											$output .= '</div>';
											
										
										
								
			/**** GRID SETTINGS ****/
				
										$output .= '<div class="field-setting no-batch-edit form-editor-only col-xs-12 s-grid">';
												$output .= '<small>'.__('Grid Name','nex-forms').'</small>';
												$output .= '<input type="text" class="form-control" name="set_grid_name" id="set_grid_name"  placeholder="'.__('Enter Grid Name','nex-forms').'">';
										$output .= '</div>';
										
										
										$output .= '<div class="field-setting col-xs-12 s-grid">';	
											$output .= '<div role="group" class="btn-group setting-responsive-grid">';
												$output .= '<small>'.__('Grid Inline Responsiveness','nex-forms').'</small>';
												$output .= '<small><em>'.__('The grid will hold its inline layout up to the device selected','nex-forms').'</em></small>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set-xs" type="button" title="'.__('Grid stays inline no matter<br />the device max width','nex-forms').'"><i class="fa fa-mobile"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set-sm" type="button" title="'.__('If device max-width <= 480px<br />Break grids','nex-forms').'"><i class="fa fa-tablet"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set-md" type="button" title="'.__('If device max-width <= 768px<br />Break grids').'"><i class="fa fa-laptop"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set-lg" type="button" title="'.__('If device max-width <= 1024px<br />Break grids').'"><i class="fa fa-desktop"></i></button>';
												
											$output .= '</div>';
										$output .= '</div>';
										
										
										$output .= '<div class="field-setting no-batch-edit form-editor-only col-xs-4 s-grid">';	
											$output .= '<div role="group" class="btn-group recreate-grid setting-recreate-grid">';
												$output .= '<small>'.__('Grid Replication','nex-forms').'</small>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf enable-recreation" type="button" title="'.__('Enables Grid Replication','nex-forms').'"><i class="fa fa-check"></i></button>';
												$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf disable-recreation active" type="button" title="'.__('Disables Grid Replication','nex-forms').'"><i class="fa fa-close"></i></button>';
											$output .= '</div>';
										$output .= '</div>';
											
										
										
										$output .= '<div class="field-setting no-batch-edit form-editor-only col-xs-3 s-grid">';
											$output .= '<small>'.__('Replication Limit','nex-forms').'</small>';
											$output .= '<div class="input-group input-group-sm grid-replication-limit">';	
											
												$output .= '<span class="input-group-addon">';
													$output .= '<span class="icon-text"> Limit to </span>';
												$output .= '</span>';
											
											
												
												$output .= '<input type="text" class="form-control" name="replication_limit" id="replication_limit" value="0"  placeholder="Replication Limit">';
											$output .= '</div>';
										$output .= '</div>';
										
										
										
							
					
					$output .= '</div>';
					
						

//VALIDATION SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="validation-settings row settings-section" >';
								
								
	/*** Required ***/	
								$output .= '<div class="field-setting col-xs-4 s-all">';	
									$output .= '<div class="btn-group required">';
										$output .= '<small>'.__('Required','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm yes" type="button"><i class="fa fa-check" ></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm no active" type="button"><i class="fa fa-remove" ></i></button>';
									$output .= '</div>';
								$output .= '</div> ';
									
									
								$output .= '<div class="field-setting col-xs-4 s-all">';
								$output .= '<div role="group" class="btn-group error-style">';
									$output .= '<small>'.__('Error Style','nex-forms').'</small>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf modern  active" type="button" data-style-tool="modern"  title="Modern"><i class="fa fa-info-circle"></i></button>';
									$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf classic" type="button" data-style-tool="classic" title="Classic"><i class="btn-tx">___</i></button>';
								$output .= '</div>';
								
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-all">';
									$output .= '<div role="group" class="btn-group error-position ">';
										$output .= '<small>'.__('Error Position','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set_left" type="button" data-style-tool="left" title="Left"><i class="btn-tx"><i class="fa fa-arrow-left"></i></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf set_right active" type="button" data-style-tool="right"  title="Right"><i class="fa fa-arrow-right"></i></button>';
										
									$output .= '</div>';
									
								$output .= '</div>';
									
									$output .= '<!--<div class="btn-group required-star">';
										$output .= '<small>'.__('Indicator','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm full active" type="button">&nbsp;<span class="glyphicon glyphicon-star"></span>&nbsp;</button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm empty" type="button">&nbsp;<span class="glyphicon glyphicon-star-empty"></span>&nbsp;</button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm asterisk" type="button">&nbsp;<span class="glyphicon glyphicon-asterisk"></span>&nbsp;</button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm none" type="button">&nbsp;<span class="fa fa-eye-slash"></span></button>';
									$output .= '</div>-->';
								
								$output .= '<div class="field-setting col-xs-6 s-v-text">';		
									$output .= '<small>'.__('Validate As','nex-forms').'</small>';
										$output .= '<select class="form-control validate_as" name="validate-as">';
											$output .= '<option value="none">Any Format</option>';
											$output .= '<option value="email">Email</option>';
											$output .= '<option value="url">URL</option>';
											$output .= '<option value="phone_number">Phone Number</option>';
											$output .= '<option value="numbers_only">Numbers Only</option>';
											$output .= '<option value="text_only">Text Only</option>';
										$output .= '</select>';
								$output .= '</div> ';								  
									
								
								
								
								
								
	/*** Error Messsage ***/	
								$output .= '<div class="field-setting col-xs-6 s-all">';
									$output .= '<small>'.__('Error Message','nex-forms').'</small>';
										$output .= '<input type="text" placeholder="Error Message" id="the_error_mesage" name="the_error_mesage" class="form-control">';
								$output .= '</div> ';	
								
								$output .= '<div class="field-setting col-xs-6 s-v-text s-v-multi-selections s-upload-file-multi s-upload-file s-upload-image">';	
									$output .= '<small>'.__('Secondary Error Message','nex-forms').'</small>';
										$output .= '<input type="text" placeholder="Enter Secondary Message" id="set_secondary_error" name="set_secondary_error" class="form-control">';
								$output .= '</div> ';			  
									
								
	/*** MAX MIN ***/	
								
								$output .= '<div class="field-setting col-xs-6 s-v-multi-selections">';	
									$output .= '<small>'.__('Minimum Selections Required','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Enter minimum required selections" id="set_min_selection" name="set_min_selection" class="form-control">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-6 s-v-multi-selections">';	
									$output .= '<small>'.__('Maximum Selections Allowed','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Enter maximum allowed selections" id="set_max_selection" name="set_max_selection" class="form-control">';
								$output .= '</div>';
												
								
								
								
								$output .= '<div class="field-setting col-xs-6 s-v-text">';	
									$output .= '<small>'.__('Maximum Characters','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Enter maximum allowed characters" id="set_max_val" name="set_max_val" class="form-control">';
								$output .= '</div>';
												
								$output .= '<div class="field-setting col-xs-6 s-v-text">';	
									$output .= '<small>'.__('Minimum Characters','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Enter minimum allowed characters" id="set_min_val" name="set_min_val" class="form-control">';
								$output .= '</div>';
									
								$output .= '<div class="field-setting col-xs-6 s-v-text">';	
									$output .= '<small>'.__('Set Input Mask','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Set input mask eg. (000) 000-0000" id="set_input_mask" name="set_input_mask" class="form-control">';
									$output .= '<span class="setting_help_text">'.__('0 = Numbers 0-9').'<br />'.__('S = Alphabetic A-Z a-z').'<br />'.__('A = Alphanomeric 0-9 A-Z a-z').'</span>';
								$output .= '</div>';	
									
									
									
		/*** Multi Uploader Messsages ***/	
		
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';
									$output .= '<small>'.__('Set Max File Size Limit per File','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Set max file size per file in MB (0=unlimited)" id="max_file_size_pf" name="max_file_size_pf" class="form-control">';
								$output .= '</div> ';
								
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';	
									$output .= '<small>'.__('Error exceeding max file size p/file','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Message if max size is exceeded per file" id="max_file_size_pf_error" name="max_file_size_pf_error" class="form-control">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';
									$output .= '<small>'.__('Set Min File Size Limit per File','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Set min file size per file in MB (0=no limit)" id="min_file_size_pf" name="min_file_size_pf" class="form-control">';
								$output .= '</div> ';
								
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';	
									$output .= '<small>'.__('Error exceeding min file size p/file','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Message if min size is exceeded per file" id="min_file_size_pf_error" name="min_file_size_pf_error" class="form-control">';
								$output .= '</div>';
								
		
								
									
									
									
									
	/*** Multi Uploader Messsages ***/	
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';
									$output .= '<small>'.__('Set Max Size for all Files','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Set max size for all files in MB (0=unlimited)" id="max_file_size_af" name="max_file_size_af" class="form-control">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';
									$output .= '<small>'.__('Error Message exceeding Size of all Files','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Message if size of all files are exceeded" id="max_file_size_af_error" name="max_file_size_af_error" class="form-control">';
								$output .= '</div>';
									
									
									
	/*** Multi Uploader Messsages ***/	
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';
									$output .= '<small>'.__('Set File Upload Limit','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Set max files that can be uploaded (0=unlimited)" id="max_upload_limit" name="max_upload_limit" class="form-control">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-6 s-upload-file-multi">';	
									$output .= '<small>'.__('Error Message exceding max file upload limit','nex-forms').'</small>';
									$output .= '<input type="text" placeholder="Message if upload limit is exceeded" id="max_upload_limit_error" name="max_upload_limit_error" class="form-control">';
								$output .= '</div>';
										
										
										
									
									
									
									$output .= '<div class="field-setting col-xs-12 s-upload-file-multi s-upload-file s-upload-image">';
										$output .= '<small>'.__('Allowed Extentions','nex-forms').'</small><textarea class="form-control" name="set_extensions" id="set_extensions"></textarea>';
									$output .= '</div>';
									
								$output .= '</div>';

//MATH SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="math-settings settings-section row">';
								$output .= '<div class="field-setting no-batch-edit col-xs-6 s-headings s-math s-math-slider s-paragraph s-html">';
	/*** Input Placeholder ***/	;
	/*** Input Name ***/
										$output .= '<small>'.__('Form fields','nex-forms').'</small>';
										$output .= '<select class="form-control" name="math_fields"></select>';
								$output .= '</div>';	
									
									
									
	/*** Input ID ***/			
								$output .= '<div class="field-setting no-batch-edit col-xs-6 s-headings s-math s-math-slider s-paragraph s-html">';
										$output .= '<small>'.__('Math Result Name','nex-forms').'</small>';
										$output .= '<input type="text" class="form-control" name="set_math_input_name" id="set_math_input_name"  placeholder="Unique Identifier">';
									
								$output .= '</div>';	
								
								
								
								$output .= '<div class="field-setting no-batch-edit col-xs-12 s-headings s-math s-math-slider s-paragraph s-html">';
									$output .= '<small>'.__('Math Equation','nex-forms').'</small><textarea class="form-control" name="set_math_logic_equation" id="set_math_logic_equation"></textarea>';
								$output .= '</div>';
						
						
								$output .= '<div class="field-setting col-xs-4 s-headings s-math s-math-slider s-paragraph s-html">';
										$output .= '<small>'.__('Thousand Delimiter','nex-forms').'</small>';
										$output .= '<input type="text" class="form-control" name="set_thousand_delimiter" id="set_thousand_delimiter"  placeholder="Default: ,">';
									
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-headings s-math s-math-slider s-paragraph s-html">';
										$output .= '<small>'.__('Decimal Places','nex-forms').'</small>';
										$output .= '<input type="text" class="form-control" name="set_decimals" id="set_decimals"  placeholder="Default: 0">';
									
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-4 s-headings s-math s-math-slider s-paragraph s-html">';
										$output .= '<small>'.__('Decimal Delimiter','nex-forms').'</small>';
										$output .= '<input type="text" class="form-control" name="set_decimals_delimiter" id="set_decimals_delimiter"  placeholder="Default: .">';
									
								$output .= '</div>';
								
								/*$output .= '<div class="field-setting col-xs-6 s-math-slider">';
										$output .= '<small>'.__('Maximum Total','nex-forms').'</small>';
										$output .= '<input type="text" class="form-control" name="set_maximum_range" id="set_maximum_range"  placeholder="Maximum Range, default: 1000">';
									
								$output .= '</div>';*/
								
								$output .= '<div class="field-setting col-xs-6 s-headings s-math s-math-slider s-paragraph s-html">';	
									$output .= '<div class="btn-group enable_count_animation">';
										$output .= '<small>'.__('Enable Count Animation','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm yes" type="button"><i class="fa fa-check" ></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf btn-sm no active" type="button"><i class="fa fa-remove" ></i></button>';
									$output .= '</div>';
								$output .= '</div> ';
								
								
								
							$output .= '</div>';
//ANIMATION SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
							$output .= '<div class="animation-settings row settings-section" >';
								
								$output .= '<div class="field-setting col-xs-12 s-all">';
	/*** Animation Selection ***/	
									$output .= ' <small>'.__('Animation','nex-forms').'</small>
														<select id="field_animation" class="form-control" name="field_animation">
															  <option value="no_animation">No Animation</option>
																	<optgroup label="Attention Seekers">
																	  <option value="bounce">bounce</option>
																	  <option value="flash">flash</option>
																	  <option value="pulse">pulse</option>
																	  <option value="rubberBand">rubberBand</option>
																	  <option value="shake">shake</option>
																	  <option value="swing">swing</option>
																	  <option value="tada">tada</option>
																	  <option value="wobble">wobble</option>
																	  <option value="jello">jello</option>
																	</optgroup>
															
																	<optgroup label="Bouncing Entrances">
																	  <option value="bounceIn">bounceIn</option>
																	  <option value="bounceInDown">bounceInDown</option>
																	  <option value="bounceInLeft">bounceInLeft</option>
																	  <option value="bounceInRight">bounceInRight</option>
																	  <option value="bounceInUp">bounceInUp</option>
																	</optgroup>
													
															<optgroup label="Bouncing Exits">
															  <option value="bounceOut">bounceOut</option>
															  <option value="bounceOutDown">bounceOutDown</option>
															  <option value="bounceOutLeft">bounceOutLeft</option>
															  <option value="bounceOutRight">bounceOutRight</option>
															  <option value="bounceOutUp">bounceOutUp</option>
															</optgroup>
													
															<optgroup label="Fading Entrances">
															  <option value="fadeIn">fadeIn</option>
															  <option value="fadeInDown">fadeInDown</option>
															  <option value="fadeInDownBig">fadeInDownBig</option>
															  <option value="fadeInLeft">fadeInLeft</option>
															  <option value="fadeInLeftBig">fadeInLeftBig</option>
															  <option value="fadeInRight">fadeInRight</option>
															  <option value="fadeInRightBig">fadeInRightBig</option>
															  <option value="fadeInUp">fadeInUp</option>
															  <option value="fadeInUpBig">fadeInUpBig</option>
															</optgroup>
													
															<optgroup label="Fading Exits">
															  <option value="fadeOut">fadeOut</option>
															  <option value="fadeOutDown">fadeOutDown</option>
															  <option value="fadeOutDownBig">fadeOutDownBig</option>
															  <option value="fadeOutLeft">fadeOutLeft</option>
															  <option value="fadeOutLeftBig">fadeOutLeftBig</option>
															  <option value="fadeOutRight">fadeOutRight</option>
															  <option value="fadeOutRightBig">fadeOutRightBig</option>
															  <option value="fadeOutUp">fadeOutUp</option>
															  <option value="fadeOutUpBig">fadeOutUpBig</option>
															</optgroup>
													
															<optgroup label="Flippers">
															  <option value="flip">flip</option>
															  <option value="flipInX">flipInX</option>
															  <option value="flipInY">flipInY</option>
															  <option value="flipOutX">flipOutX</option>
															  <option value="flipOutY">flipOutY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedIn">lightSpeedIn</option>
															  <option value="lightSpeedOut">lightSpeedOut</option>
															</optgroup>
													
															<optgroup label="Rotating Entrances">
															  <option value="rotateIn">rotateIn</option>
															  <option value="rotateInDownLeft">rotateInDownLeft</option>
															  <option value="rotateInDownRight">rotateInDownRight</option>
															  <option value="rotateInUpLeft">rotateInUpLeft</option>
															  <option value="rotateInUpRight">rotateInUpRight</option>
															</optgroup>
													
															<optgroup label="Rotating Exits">
															  <option value="rotateOut">rotateOut</option>
															  <option value="rotateOutDownLeft">rotateOutDownLeft</option>
															  <option value="rotateOutDownRight">rotateOutDownRight</option>
															  <option value="rotateOutUpLeft">rotateOutUpLeft</option>
															  <option value="rotateOutUpRight">rotateOutUpRight</option>
															</optgroup>
													
															<optgroup label="Sliding Entrances">
															  <option value="slideInUp">slideInUp</option>
															  <option value="slideInDown">slideInDown</option>
															  <option value="slideInLeft">slideInLeft</option>
															  <option value="slideInRight">slideInRight</option>
													
															</optgroup>
															<optgroup label="Sliding Exits">
															  <option value="slideOutUp">slideOutUp</option>
															  <option value="slideOutDown">slideOutDown</option>
															  <option value="slideOutLeft">slideOutLeft</option>
															  <option value="slideOutRight">slideOutRight</option>
															  
															</optgroup>
															
															<optgroup label="Zoom Entrances">
															  <option value="zoomIn">zoomIn</option>
															  <option value="zoomInDown">zoomInDown</option>
															  <option value="zoomInLeft">zoomInLeft</option>
															  <option value="zoomInRight">zoomInRight</option>
															  <option value="zoomInUp">zoomInUp</option>
															</optgroup>
															
															<optgroup label="Zoom Exits">
															  <option value="zoomOut">zoomOut</option>
															  <option value="zoomOutDown">zoomOutDown</option>
															  <option value="zoomOutLeft">zoomOutLeft</option>
															  <option value="zoomOutRight">zoomOutRight</option>
															  <option value="zoomOutUp">zoomOutUp</option>
															</optgroup>
													
															<optgroup label="Specials">
															  <option value="hinge">hinge</option>
															  <option value="rollIn">rollIn</option>
															  <option value="rollOut">rollOut</option>
															</optgroup>
														  </select>';
												$output .= '</div>';
													 
											 $output .= '<div class="field-setting col-xs-6 s-all">';		 
												$output .= '<small>'.__('Animation Delay','nex-forms').'</small>';
												$output .= '<input type="text" class="form-control" name="animation_delay" placeholder="Set delay in seconds" id="animation_delay">';
											 $output .= '</div>';	
											  
											 $output .= '<div class="field-setting col-xs-6 s-all">';	 
												$output .= '<small>'.__('Animation Duration','nex-forms').'</small>';
												$output .= '<input type="text" class="form-control" name="animation_duration" placeholder="Set duration in seconds" id="animation_duration">';	 
											$output .= '</div>';
												 
											 $output .= '<div class="field-setting col-xs-12 s-all">';		 
												 $output .= '<small>'.__('Animation Preview','nex-forms').'</small>';
												 $output .= '<div class="animation_preview_container"><div class="animation_preview">Animation</div></div>';
											 $output .= '</div>';
										
										
										
										
										
										
										
									$output .= '</div>';
								$output .= '</div>';
							
						$output .= '</div>';
					
					$output .= '</div>';
					
				$output .= '</div>';
				
				$output .= '<div class="setting-buffer"></div>';
				
			$output .= '</div>';
			
			
			
			$output .= '<div class="fa-icons-list  aa_bg_main">';
							$output .= '<div class="row">';
								$output .= '<div class="col-xs-11">';
									$output .= '<div role="group" class="input-group input-group-sm">';
										
										$output .= '<input type="text" placeholder="...search term" class="icon_search form-control" name="icon_search" id="icon_search">';
										$output .= '<span class="input-group-addon search_add_on"><span class="fa fa-search"></span>&nbsp;'.__('Search Icons','nex-forms').'</span>';
									$output .= '</div>';
								$output .= '</div>';
								$output .= '<div class="col-xs-1">';
									$output .= '<span class="close_icons fa fa-close"></span>';
								$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="inner">';
								$get_icons = new NF5_icons();
								$output .= $get_icons->get_fa_icons();
							$output .= '</div>';
						$output .= '</div>';
			
			
			return $output;
		
		}
		
		
		public function print_form_canvas(){
			
			$nf_functions = new NEXForms_Functions();
			$builder = new NEXForms_builder7();
			$output = '';
			$user_config = get_user_option('nex-forms-user-config',get_current_user_id());

			$theme_settings = json_decode($this->md_theme,true);
			
			$set_theme 			= ($theme_settings['0']['theme_name']) 	? $theme_settings['0']['theme_name'] 	: 'default';
			$set_theme_shade 	= (($theme_settings['0']['theme_shade']) ? $theme_settings['0']['theme_shade'] 	: 'light');
			
			$set_form_theme = ($this->form_theme) ? $this->form_theme : 'bootstrap';
			$set_jq_theme 	= ($this->jq_theme) ? $this->jq_theme : 'default';
	
			
			
			
			
			if($set_jq_theme=='base')
				$set_jq_theme = 'default';
			
			echo '<link class="jquery_ui_theme" name="jquery_ui_theme" rel="stylesheet" type="text/css" href="'.(($set_form_theme!='m_design') ? plugins_url( '/nex-forms-themes-add-on7/css/'.$set_jq_theme.'/jquery.ui.theme.css',dirname(dirname(dirname(__FILE__)))) : '' ).'"/>';

			$output .= '<div class="form-canvas-area form-editor-view '.$set_theme_shade.'" data-sec-pre-class=".inner-form-canvas">';
				$output .= '<div class="form-canvas-area-mask"></div>';
				$output .= '<div class="preview-tools">';
					$output .= '<span class="expand_fullscreen"><a class="btn waves-effect-nf waves-light-nf btn-fullscreen fc-options" href="#" data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Enter Full Screen Mode','nex-forms').'"><span class="fa fas fa-expand-arrows-alt"></span></a></span>';
					$output .= '<span class="colapse_fullscreen" style="display:none;"><a class="btn waves-effect-nf waves-light-nf btn-wordpress fc-options active" href="#" data-toggle="tooltip_bs" data-html="true" data-placement="bottom" title="'.__('Exit Fullscreen Mode<br>Show WordPress Menus','nex-forms').'"><span class="fas fa-compress-arrows-alt"></span></a></span>';
					
					$output .= '<div class="btn workspace_theme workspace_theme_light '.(($set_theme_shade=='light') ? 'active' : '').'" data-view="light" ><span class="fas fa-sun" data-toggle="tooltip_bs" data-placement="bottom" title="Light Workspace"></span></div>';
					$output .= '<div class="btn workspace_theme workspace_theme_dark '.(($set_theme_shade=='dark') ? 'active' : '').'" data-view="dark"><span class="fas fa-moon" data-toggle="tooltip_bs" data-placement="bottom" title="Dark Workspace"></span></div>';
					
					$output .= '<div class="btn workspace normal active" data-view="normal"><span data-toggle="tooltip_bs2" data-placement="bottom" title="Form Design/Build View"><span class="fas fa-edit" ></span> '.__('Form Design','nex-forms').'</span></div>';
					$output .= '<div class="btn workspace success-msg" data-view="success-msg"><span data-toggle="tooltip_bs2" data-placement="bottom" title="Success Messsage<br />Design/Build View"><span class="fas fa-check" ></span> '.__('Success Message','nex-forms').'</span></div>';
					
					$output .= '<div class="btn workspace c-logic" data-view="normal"><span data-toggle="tooltip_bs2" data-placement="bottom" title="Add,Edit,Delete Conditional Logic"><span class="fas fa-project-diagram" ></span> '.__('Conditional Logic','nex-forms').'</span></div>';
					
					$output .= '<div class="btn workspace split" data-view="split"><span data-toggle="tooltip_bs2" data-placement="bottom" title="Form Design + Preview<br>Split Screen"><span class="fas fa-columns" ></span> '.__('Split','nex-forms').'</span></div>';
					$output .= '<div class="btn workspace preview" ><span data-view="preview" data-toggle="tooltip_bs2" data-placement="bottom" title="Preview Form"><span class="fas fa-eye" ></span> '.__('Preview','nex-forms').'</span></div>';
				$output .= '</div>';
				
				/*$output .= '<div class="history-tools">';
					$output .= '<div class="history_action do_undo" data-action="undo"><span class="fas fa-undo" data-toggle="tooltip_bs" data-placement="left" title="'.__('Undo last action<br><small>CTRL+Z</small>','nex-forms').'"></span></div>';
					$output .= '<div class="history_action do_redo" data-action="redo"><span class="fas fa-redo" data-toggle="tooltip_bs" data-placement="left" title="'.__('Redo last undo<br><small>CTRL+SHIFT+Z</small>','nex-forms').'"></span></div>';
				$output .= '</div>';*/
				
				$output .= '<div class="canvas-tools field-selection-tools">';
							
					$output .= '<div class="tool-section select-other-fields other-form-elements" id="toolbar-fields">';
							
							$droppables = array(
								//FORM FIELDS
								//PRESET FIELDS		
								/*'tool-spacer-0' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Add-ons','nex-forms'),
									'class' => 'form-editor add-on-fields',
									),
								
								
								
								
								
								'tool-spacer-end-0' => array
									(
									'type' => 'tool-spacer-end',
									),
								*/
								'tool-spacer-1' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Preset Fields','nex-forms'),
									'class' => 'form-editor preset-fields',
									),
								
								
								
								'name' => array
									(
									'category'	=>	'preset_fields',
									'label'	=>	__('Name','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-user',
									'type' => 'preset_field',
									'format' => '',
									'required' => 'required',
									'field_name' => '_name',
									'settings_class' => '.s-text, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								// POSSIBLE TO BE READDED
								/*'surname' => array
									(
									'category'	=>	'preset_fields',
									'label'	=>	__('Surname','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa-user',
									'type' => 'preset_field',
									'format' => '',
									'required' => 'required',
									'field_name' => 'surname',
									'settings_class' => 's-text',
									),*/
								'email' => array
									(
									'category'	=>	'preset_fields',
									'label'	=>	__('Email','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-envelope',
									'type' => 'preset_field',
									'format' => 'email',
									'required' => 'required',
									'field_name' => 'email',
									'settings_class' => '.s-text, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),	
								'phone_number' => array
									(
									'category'	=>	'preset_fields',
									'label'	=>	__('Phone Number','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-phone',
									'type' => 'preset_field',
									'format' => 'phone_number',
									'required' => 'required',
									'field_name' => 'phone_number',
									'settings_class' => '.s-text,s-phone, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'url' => array
									(
									'category'	=>	'preset_fields',
									'label'	=>	__('URL','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-link',
									'type' => 'preset_field',
									'format' => 'url',
									'required' => '',
									'field_name' => 'url',
									'settings_class' => '.s-text,s-url, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'Query' => array
									(
									'category'	=>	'preset_fields',
									'label'	=>	__('Query','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-comment',
									'type' => 'preset_field',
									'format' => '',
									'field_name' => 'query',
									'required' => 'required',
									'settings_class' => '.s-text, .s-texarea, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								
								'tool-spacer-end-1' => array
									(
									'type' => 'tool-spacer-end',
									),
								'tool-spacer-2' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Input Fields','nex-forms'),
									'class' => 'form-editor input-fields',
									),
								
								
								'text' => array
									(
									'category'	=>	'common_fields',
									'label'	=>	__('Text Field','nex-forms'),
									'tooltip'	=>	__('Text Field (single-line)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-text-width',
									'type' => 'input',
									'settings_class' => '.s-text, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'textarea' => array
									(
									'category'	=>	'common_fields',
									'label'	=>	__('Textarea','nex-forms'),
									'tooltip'	=>	__('Textarea (multi-line)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-text-height',
									'type' => 'textarea',
									'settings_class' => '.s-text, .s-texarea, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'password' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Password','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-key',
									'type' => 'password',
									'settings_class' => '.s-text, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								
								'tool-spacer-end-2' => array
									(
									'type' => 'tool-spacer-end',
									),
								'tool-spacer-3' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Selection Fields','nex-forms'),
									'class' => 'form-editor selection-fields',
									),
									
								
								'select' => array
									(
									'category'	=>	'common_fields selection_fields',
									'label'	=>	__('Dropdown Select','nex-forms'),
									'tooltip'	=>	__('Dropdown Select (single select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-list-ul',
									'type' => 'select',
									'settings_class' => '.s-select',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'multi-select' => array
									(
									'category'	=>	'selection_fields',
									'label'	=>	__('Dropdown Select','nex-forms'),
									'tooltip'	=>	__('Dropdown Select (multi select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-tasks',
									'type' => 'multi-select',
									'settings_class' => '.s-select, .s-v-multi-selections',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
									
								'radio-group' => array
									(
									'category'	=>	'common_fields selection_fields',
									'label'	=>	__('Radio Buttons','nex-forms'),
									'tooltip'	=>	__('Radio Buttons (single select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-dot-circle',
									'type' => 'radio-group',
									'settings_class' => '.s-radios',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'check-group' => array
									(
									'category'	=>	'common_fields selection_fields',
									'label'	=>	__('Check Boxes','nex-forms'),
									'tooltip'	=>	__('Check Boxes (multi select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'far fa-check-square',
									'type' => 'check-group',
									'settings_class' => '.s-checks, .s-v-multi-selections',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'single-image-select-group' => array
									(
									'category'	=>	'selection_fields',
									'label'	=>	__('Thumb Select','nex-forms'),
									'tooltip'	=>	__('Thumb Select (single select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-image',
									'type' => 'single-image-select-group',
									'settings_class' => '.s-thumbs-select-single',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'multi-image-select-group' => array
									(
									'category'	=>	'selection_fields',
									'label'	=>	__('Multi-Thumbs','nex-forms'),
									'tooltip'	=>	__('Thumbs Select (multi select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-images',
									'type' => 'multi-image-select-group',
									'settings_class' => '.s-thumbs-select-multi',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
									
								
								'image-choices-field' => array
									(
									'category'	=>	'selection_fields',
									'label'	=>	__('Thumb Select','nex-forms'),
									'tooltip'	=>	__('Thumb / Image Select<br>(single or multi select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-image',
									'type' => 'image-choices-field',
									'settings_class' => '.s-thumbs-select, .s-v-multi-selections',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								'icon-select-group' => array
									(
									'category'	=>	'selection_fields',
									'label'	=>	__('Icon Select','nex-forms'),
									'tooltip'	=>	__('Icon Select (AKA Super Select)<br>(multi or single select)','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-check-double',
									'type' => 'icon-select-group',
									'settings_class' => '.s-super-select, .s-v-multi-selections',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								'tool-spacer-end-3' => array
									(
									'type' => 'tool-spacer-end',
									),
								'tool-spacer-4' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Special Fields','nex-forms'),
									'class' => 'form-editor special-fields',
									),
								
								
								
								
								'slider' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Slider','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-sliders-h',
									'type' => 'slider',
									'settings_class' => '.s-slider',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),	
								'touch_spinner' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Spinner','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-sort',
									'type' => 'spinner',
									'settings_class' => '.s-spinner',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								
								
								'autocomplete' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Auto-complete','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-pencil',
									'type' => 'autocomplete',
									'settings_class' => '.s-autocomplete, .s-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								'tags' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Tags','nex-forms'),
									'tooltip'	=>	__('Tags Input Field','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-tag',
									'type' => 'tags',
									'settings_class' => '.s-tags',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								'digital-signature' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Signature','nex-forms'),
									'tooltip'	=>	__('Digital Signatures','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-file-signature',
									'type' => 'digital-signature',
									'settings_class' => '.s-sigs',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'star-rating' => array
									(
									'category'	=>	'survey_fields',
									'label'	=>	__('Star Rating','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-star',
									'type' => 'star-rating',
									'settings_class' => '.s-star-rating',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								/*'nf-color-picker' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	'Color Picker',
									'sub_label'	=>	'',
									'icon'	=>	'fa-paint-brush',
									'type' => 'nf-color-picker',
									'settings_class' => '.s-text, .s-v-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								*/
								'tool-spacer-end-4' => array
									(
									'type' => 'tool-spacer-end',
									),
								'tool-spacer-5' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Date/Time','nex-forms'),
									'class' => 'form-editor date-fields',
									),	
									
									
									
								'date' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Date','nex-forms'),
									'tooltip'	=>	__('Date Picker','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'far fa-calendar',
									'type' => 'date',
									'settings_class' => '.s-date, .s-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'time' => array
									(
									'category'	=>	'special_fields',
									'label'	=>	__('Time','nex-forms'),
									'tooltip'	=>	__('Time Picker','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'far fa-clock',
									'type' => 'time',
									'settings_class' => '.s-time, .s-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
									
								
								
								
								'tool-spacer-end-5' => array
									(
									'type' => 'tool-spacer-end',
									),	
								'tool-spacer-6' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Survey Fields','nex-forms'),
									'class' => 'form-editor survey-fields',
									),
									
									
								
								
								'thumb-rating' => array
									(
									'category'	=>	'survey_fields',
									'label'	=>	__('Thumb Rating','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-thumbs-up',
									'type' => 'thumb-rating',
									'settings_class' => '.s-thumb-rating',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'smily-rating' => array
									(
									'category'	=>	'survey_fields',
									'label'	=>	__('Smiley Rating','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fa fa-smile-o',
									'type' => 'smily-rating',
									'settings_class' => '.s-smiley-rating',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								// POSSIBLE TO BE READDED
								
								
								
						//UPLOADER FIELDS
						
								'tool-spacer-end-6' => array
									(
									'type' => 'tool-spacer-end',
									),
								'tool-spacer-7' => array
									(
									'type' => 'tool-spacer-start',
									'section' => __('Uploaders','nex-forms'),
									'class' => 'form-editor uploader-fields',
									),
								
								'upload-multi' => array
									(
									'category'	=>	'upload_fields',
									'label'	=>	__('Multi-Upload','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-reply-all',
									'type' => 'upload-multi',
									'settings_class' => '.s-upload-file-multi, .s-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								
								'upload-single' => array
									(
									'category'	=>	'upload_fields',
									'label'	=>	__('File Upload','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-reply',
									'type' => 'upload-single',
									'settings_class' => '.s-upload-file, .s-text',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
								'upload-image' => array
									(
									'category'	=>	'upload_fields',
									'label'	=>	__('Image Upload','nex-forms'),
									'sub_label'	=>	'',
									'icon'	=>	'fas fa-file-image',
									'type' => 'upload-image',
									'settings_class' => '.s-upload-image',
									'settings_tabs' => '#label-settings, #input-settings, #validation-settings, #animation-settings, #extra-settings',
									),
						
								'tool-spacer-end-7' => array
									( 
									'type' => 'tool-spacer-end',
									),
								);
	
			
			
			//SET PREFERENCES
							$label_width = 'col-sm-12';
							$input_width = 'col-sm-12';
							$hide_label = '';
							$label_pos = 'left';
							$align_class = '';
							$preferences = get_option('nex-forms-preferences'); 							
							switch($preferences['field_preferences']['pref_label_align'])
								{
								case 'top':
									$label_width = 'col-sm-12';
									$input_width = 'col-sm-12';
								break;
								case 'left':
									$label_width = 'col-sm-3';
									$input_width = 'col-sm-9';
								break;
								case 'right':
									$label_width = 'col-sm-3';
									$input_width = 'col-sm-9';
									$label_pos = 'right';
									$align_class = 'pos_right';
								break;
								case 'hidden':
									$label_width = 'col-sm-12';
									$input_width = 'col-sm-12';
									$hide_label = 'style="display: none;"';
								break;
								default:
									$label_width = 'col-sm-12';
									$input_width = 'col-sm-12';
									$hide_label = '';
									$label_pos = 'left';
									$align_class = '';
								break;
								
									}
				
				
				
							
							
							
							$output .= '<div class="tool-spacer grid-selection"><span class="tool-section-title" title="Selection Fields" data-toggle="" data-placement="top">'.__('Layout / Grid System','nex-forms').'</span>';		
										$output .= '<div class="field form_field grid grid-system grid-system-1" data-settings=".s-grid" data-settings-tabs="#input-settings, #animation-settings, #extra-settings">';
											$output .= '<div class="draggable_object">';
												$output .= '<span class="col-badge column_icons" title="'.__('Insert 1 Column','nex-forms').'"  data-toggle="tooltip_bs" data-placement="bottom"><div class="column_icon column_1"></div></span>';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
													$output .= '<div class="input-inner" data-svg="demo-input-1">';
														$output .= '<div class="row grid_row">';
															$output .= '<div class="grid_input_holder col-sm-12">';
																$output .= '<div class="panel grid-system grid-system panel-default">';
																	$output .= '<div class="panel-body">';
																	$output .= '</div>';
																$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
																$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
																$output .= '<div class="btn btn-default btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
																$output .= '<div title="'.__('Duplicate Field','nex-forms').'" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
																$output .= '<div class="btn btn-default btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
															$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
										
										
		//2 Columns
										$output .= '<div class="field form_field grid grid-system grid-system-2" data-settings=".s-grid" data-settings-tabs="#input-settings, #animation-settings, #extra-settings">';
											$output .= '<div class="draggable_object">';
												$output .= '<span class="col-badge column_icons" title="'.__('Insert 2 Columns','nex-forms').'"  data-toggle="tooltip_bs" data-placement="bottom"><div class="column_icon column_2"></div><div class="column_icon column_2"></div></span>';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row grid_row">';
																$output .= '<div class="grid_input_holder col-sm-6">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
																$output .= '</div>';
																$output .= '<div class="grid_input_holder col-sm-6">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
																$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
																$output .= '<div class="btn btn-default btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
																$output .= '<div title="'.__('Duplicate Field','nex-forms').'" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
																$output .= '<div class="btn btn-default btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
															$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
		//3 Columns								
										$output .= '<div class="field form_field grid grid-system grid-system-3" data-settings=".s-grid" data-settings-tabs="#input-settings, #animation-settings, #extra-settings">';
											$output .= '<div class="draggable_object">';
												$output .= '<span class="col-badge column_icons" title="'.__('Insert 3 Columns','nex-forms').'"  data-toggle="tooltip_bs" data-placement="bottom"><div class="column_icon column_3"></div><div class="column_icon column_3"></div><div class="column_icon column_3"></div></span>';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row  grid_row">';
																$output .= '<div class="grid_input_holder col-sm-4">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
																$output .= '</div>';
																$output .= '<div class="grid_input_holder col-sm-4">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
																$output .= '</div>';
																$output .= '<div class="grid_input_holder col-sm-4">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="field_settings grid" style="display:none">';
																$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
																$output .= '<div class="btn btn-default btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
																$output .= '<div title="'.__('Duplicate Field','nex-forms').'" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
																$output .= '<div class="btn btn-default btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
															$output .= '</div>';
														$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
										
		//4 Columns								
										$output .= '<div class="field form_field grid grid-system grid-system-4" data-settings=".s-grid" data-settings-tabs="#input-settings, #animation-settings, #extra-settings">';
											$output .= '<div class="draggable_object">';
												$output .= '<span class="col-badge column_icons column_icons_4" title="'.__('Insert 4 Columns','nex-forms').'"  data-toggle="tooltip_bs" data-placement="bottom"><div class="column_icon column_4"></div><div class="column_icon column_4"></div><div class="column_icon column_4"></div><div class="column_icon column_4"></div></span>';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row grid_row">';
																$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-3">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
															$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
															$output .= '<div class="btn btn-default btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
															$output .= '<div title="'.__('Duplicate Field','nex-forms').'" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
															$output .= '<div class="btn btn-default btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
														$output .= '</div>';
													$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
										
		//6 Columns								
										/*$output .= '<div class="field form_field grid grid-system grid-system-6" data-settings=".s-grid" data-settings-tabs="#input-settings, #animation-settings, #extra-settings">';
											$output .= '<div class="draggable_object">';
												$output .= '<span class="col-badge column_icons column_icons_6" title="'.__('Insert 6 Columns','nex-forms').'"  data-toggle="tooltip_bs" data-placement="bottom"><div class="column_icon column_6"></div><div class="column_icon column_6"></div><div class="column_icon column_6"></div><div class="column_icon column_6"></div><div class="column_icon column_6"></div><div class="column_icon column_6"></div></span>';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="input-inner" data-svg="demo-input-1">';
															$output .= '<div class="row grid_row">';
																$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
															$output .= '<div class="grid_input_holder col-sm-2">';
																	$output .= '<div class="panel grid-system panel-default">';
																		$output .= '<div class="panel-body">';
																		$output .= '</div>';
																	$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
														$output .= '<div class="field_settings grid" style="display:none">';
															$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
															$output .= '<div class="btn btn-default btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
															$output .= '<div title="'.__('Duplicate Field','nex-forms').'" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
															$output .= '<div class="btn btn-default btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
														$output .= '</div>';
													$output .= '</div>';	
											$output .= '</div>';
											
										$output .= '</div>';*/
										
								$output .= '</div>';
							
							
							foreach($droppables as $type=>$attr)
								{
									$set_format = isset($attr['format']) ? $attr['format'] : '';
									$set_required  = isset($attr['required']) ? $attr['required'] : '';
								
								if($attr['type']=='tool-spacer-start')
									{
									$output .= '<div class="tool-spacer '.$attr['class'].'"><span class="tool-section-title" title="'.$attr['section'].'" data-toggle="" data-placement="top" >'.$attr['section'].'</span>';
									}
								
								if($attr['type']!='tool-spacer-start' && $attr['type']!='tool-spacer-end')
									{
									$tooltip = isset($attr['tooltip']) ? $attr['tooltip'] : '';
									$icon = isset($attr['icon']) ? $attr['icon'] : '';
									$label = isset($attr['label']) ? $attr['label'] : '';
									
									
									$output .= '<div class="field form_field all_fields '.$set_format.' '.$type.' '.$attr['category'].' '.(($set_required) ? 'required' : '').'" data-settings="'.$attr['settings_class'].'" data-settings-tabs="'.$attr['settings_tabs'].'">';
										
										$output .= '<div class="draggable_object "   >';
											$output .= '<i title="'.(($tooltip!='') ? $tooltip : $attr['label']).'" data-title="'.(($tooltip!='') ? $tooltip : $attr['label']).'" data-toggle="tooltip_bs" data-placement="bottom" class="'.$attr['icon'].'"></i><span class="object_title">'.$attr['label'].'</span>';
										$output .= '</div>';
										
										$output .= '<div id="form_object" class="form_object" style="display:none;">';
											$output .= '<div class="row">';
												$output .= '<div class="col-sm-12" id="field_container">';
													$output .= '<div class="row">';
														if($attr['type']!='submit-button' && $attr['type']!='submit-button2')
															{
															if($label_pos != 'right')
																{
																$output .= '<div class="'.$label_width.' '.$align_class.' label_container '.(($preferences['field_preferences']['pref_label_text_align']) ? $preferences['field_preferences']['pref_label_text_align'] : 'align_left').'" '.$hide_label.'>';
																	$output .= '<label class="nf_title '.$preferences['field_preferences']['pref_label_size'].'"><span class="the_label">'.(($set_required) ? '*' : '').''.$attr['label'].'</span><small class="sub-text style_italic">'.(($preferences['field_preferences']['pref_sub_label']=='on') ? 'Sub label' : '').'</small></label>';
																$output .= '</div>';
																}
															}
									
																switch($attr['type'])
																	{
																	case 'smily-rating':
																		$output .= '<div class="'.$input_width.' input_container error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'">';
																				$output .= '<label class="radio-inline " for="nf-smile-bad">
																							  <input class="nf-smile-bad the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-smile-bad" value="'.__('Bad','nex-forms').'">
																							  <span class="fa the-smile fa-frown-o nf-smile-bad" data-toggle="tooltip_bs" data-placement="top" title="'.__('Bad','nex-forms').'">&nbsp;</span>
																						  </label>
																						  <label class="radio-inline" for="nf-smile-average">
																							  <input class="nf-smile-average the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-smile-average" value="'.__('Average','nex-forms').'">
																							  <span class="fa the-smile fa-meh-o nf-smile-average" data-toggle="tooltip_bs" data-placement="top" title="'.__('Average','nex-forms').'">&nbsp;</span>
																						  </label>
																						  <label class="radio-inline" for="nf-smile-good">
																							  <input class="nf-smile-good the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-smile-good" value="'.__('Good','nex-forms').'">
																							  <span class="fa the-smile fa-smile-o nf-smile-good" data-toggle="tooltip_bs" data-placement="top" title="'.__('Good','nex-forms').'">&nbsp;</span>
																						  </label>';
																		$output .= '</div>';
																	break;
																	case 'thumb-rating':
																		$output .= '<div class="'.$input_width.' input_container error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'">';
																				$output .= '<label class="radio-inline" for="nf-thumbs-up">
																							  <input class="nf-thumbs-o-up the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-thumbs-up" value="'.__('Yes','nex-forms').'">
																							  <span class="fa the-thumb fa-thumbs-o-up" data-toggle="tooltip_bs" data-placement="top" title="'.__('Yes','nex-forms').'">&nbsp;</span>
																						  </label>
																						  <label class="radio-inline" for="nf-thumbs-down">
																							  <input class="nf-thumbs-o-down the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="nf-thumbs-down" value="'.__('No','nex-forms').'">
																							  <span class="fa the-thumb fa-thumbs-o-down" data-toggle="tooltip_bs" data-placement="top" title="'.__('No','nex-forms').'">&nbsp;</span>
																						  </label>';
																		$output .= '</div>';
																	break;
																	case 'digital-signature':
																		if ( function_exists('enqueue_nf_digital_sigs_scripts'))
																			{
																			$output .= '<div class="'.$input_width.'  input_container">';
																					$output .= '<textarea  name="'.$nf_functions->format_name($attr['label']).'" class="the_input_element digital-signature-data error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'"></textarea><div class="clear_digital_siganture"><span class="fa fa-eraser"></span></div><div class="js-signature"></div>';
																			$output .= '</div>';
																			}
																		else
																			{
																			$output .= '<div class="'.$input_width.'  input_container">';
																					$output .= '<div class="alert alert-success">'.__('You need the "<strong><em>Digital Signatures for NEX-forms</em></strong></a>" Add-on to use digital signatures! <br /><br><a href="https://1.envato.market/zQ6de" target="_blank" class="btn btn-success btn-sm">Buy Now</a>','nex-forms').'</div>';
																			$output .= '</div>';
																			}
																	break;
																	case 'input':
																		$output .= '<div class="'.$input_width.'  input_container">';
																				$output .= '<input type="text" name="'.$nf_functions->format_name($attr['label']).'" class="form-control error_message the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value=""  data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="" title="">';
																		$output .= '</div>';
																	break;
																	case 'textarea':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<textarea name="'.$nf_functions->format_name($attr['label']).'" placeholder=""  data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value="" class="error_message the_input_element textarea pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></textarea>';
																		$output .= '</div>';
																	break;
																	case 'select':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<select name="'.$nf_functions->format_name($attr['label']).'" class="the_input_element error_message text pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'">
																							<option value="0" selected="selected">'.__('--- Select ---','nex-forms').'</option>
																							<option value="'.__('Option 1','nex-forms').'">'.__('Option 1','nex-forms').'</option>
																							<option value="'.__('Option 2','nex-forms').'">'.__('Option 2','nex-forms').'</option>
																							<option value="'.__('Option 3','nex-forms').'">'.__('Option 3','nex-forms').'</option>
																						</select>';
																	$output .= '</div>';
																	break;
																	case 'multi-select':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<select name="'.$nf_functions->format_name($attr['label']).'[]" multiple class="the_input_element error_message text pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="Minimum of {x} selections required">
																							<option value="0" selected="selected">'.__('--- Select ---','nex-forms').'</option>
																							<option value="'.__('Option 1','nex-forms').'">'.__('Option 1','nex-forms').'</option>
																							<option value="'.__('Option 2','nex-forms').'">'.__('Option 2','nex-forms').'</option>
																							<option value="'.__('Option 3','nex-forms').'">'.__('Option 3','nex-forms').'</option>
																						</select>';
																	$output .= '</div>';
																	break;
																	case 'radio-group':
																		$output .= '<div class="input_holder radio-group no-pre-suffix">';
																			$output .= '<div class="'.$input_width.' the-radios input_container error_message" id="the-radios" data-checked-color="" data-checked-class="fa-circle" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" >';
																				$output .= '<div class="input-inner">';
																					$output .= '<label class="radio-inline " for="radios_0">
																						  <input class="radio the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="radios_0" value="'.__('Radio 1','nex-forms').'" >
																							  <span class="input-label radio-label">'.__('Radio 1','nex-forms').'</span>
																						  </label>
																						  <label class="radio-inline" for="radios_1">
																							  <input class="radio the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="radios_1" value="'.__('Radio 2','nex-forms').'">
																							  <span class="input-label radio-label">'.__('Radio 2','nex-forms').'</span>
																						  </label>
																						  <label class="radio-inline" for="radios_2">
																							  <input class="radio the_input_element" type="radio" name="'.$nf_functions->format_name($attr['label']).'" id="radios_2" value="'.__('Radio 3','nex-forms').'" >
																							  <span class="input-label radio-label">'.__('Radio 3','nex-forms').'</span>
																						  </label>
																						';
																				$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	case 'check-group':
																		$output .= '<div class="input_holder radio-group">';
																			$output .= '<div class="'.$input_width.' the-radios input_container error_message" id="the-radios" data-checked-color="alert-success" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'"  data-secondary-message="Minimum of {x} selections required" title="" >';
																				$output .= '<div class="input-inner">';
																					$output .= '<label class="checkbox-inline" for="check_1">
																								  <input class="check the_input_element" type="checkbox" name="checks[]" id="check_1" value="'.__('Check 1','nex-forms').'" >
																								  <span class="input-label check-label">'.__('Check 1','nex-forms').'</span>
																							  </label>
																							  <label class="checkbox-inline" for="check_2">
																								  <input class="check the_input_element" type="checkbox" name="checks[]" id="check_2" value="'.__('Check 2','nex-forms').'">
																								  <span class="input-label check-label">'.__('Check 2','nex-forms').'</span>
																							  </label>
																							  <label class="checkbox-inline" for="check_3">
																								  <input class="check the_input_element" type="checkbox" name="checks[]" id="check_3" value="'.__('Check 3','nex-forms').'" >
																								  <span class="input-label check-label">'.__('Check 3','nex-forms').'</span>
																							  </label>';	
																				$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	
																	
																	
																	case 'single-image-select-group':
																		$output .= '<div class="input_holder '.$input_width.' input_container">';
																			$output .= '<div class="the-radios error_message" id="the-radios" data-checked-color="" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" >';
																	$output .= '<div class="input-inner" data-svg="demo-input-1">';
																	$output .= '<label class="radio-inline " for="radios-0"  data-svg="demo-input-1">
																				  <span class="svg_ready">
																				  <input class="radio svg_ready the_input_element" type="radio" name="single_thumb_select" id="radios-0" value="1" >
																				  <span class="input-label radio-label">'.__('Thumbnail 1','nex-forms').'</span>
																				  </span>
																			  </label>
																			  <label class="radio-inline" for="radios-1"  data-svg="demo-input-1">
																				<span class="svg_ready">
																				  <input class="radio svg_ready the_input_element" type="radio" name="single_thumb_select" id="radios-1" value="2">
																				  <span class="input-label radio-label">'.__('Thumbnail 2','nex-forms').'</span>
																				</span>
																			  </label>
																			  <label class="radio-inline" for="radios-2"  data-svg="demo-input-1">
																				<span class="svg_ready">
																				  <input class="radio svg_ready the_input_element" type="radio" name="single_thumb_select" id="radios-2" value="3">
																				  <span class="input-label radio-label">'.__('Thumbnail 3','nex-forms').'</span>
																				</span>
																			  </label>
																		 
																			';
																	
																	
																	
																	
																	$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	
																	
																	case 'image-choices-field':
																		$output .= '<div class="input_holder '.$input_width.' input_container">';
																			$output .= '<div id="the-radios" class="the-radios error_message" data-checked-color="" data-layout="3c" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'"  data-secondary-message="Minimum of {x} selections required" title="" >';
																				$output .= '<div class="image-choices-inner row">';
																					$output .= '<div class="input-inner">';
																						
																						//image-choices-choice-selected
																						$output .= '<div class="image-choices-choice col-sm-4">
																									  <label class="radio-inline" for="thumb_select_thumb_1" >
																										  <div class="thumb-image-outer-wrap">
																										  <div class="prettyradio"><input name="thumb_select" type="radio" value="1" id=""  style="display: none;" autocomplete="disabled" class="the_input_element"><a class="ui-state-default" style="background: rgb(139, 195, 74);"></a></div>
																										  
																										  	<div class="image-choices-choice-image-wrap">
																												<div class="thumb-placeholder"><span class="fa far fa-image"></span><br>Click to add image</div>
																										  	</div>
																										  </div>
																										  <span class="image-choices-choice-text input-label">Thumb 1</span>
																									  </label>
																									</div>
																								';
																						
																						
																						$output .= '<div class="image-choices-choice col-sm-4">
																									  <label class="radio-inline" for="thumb_select_thumb_1">
																										  <div class="thumb-image-outer-wrap">
																										  <div class="prettyradio"><input name="thumb_select" type="radio" value="2" id="" style="display: none;" autocomplete="disabled" class="the_input_element"><a class="ui-state-default" style="background: rgb(139, 195, 74);"></a></div>
																										  
																										  	<div class="image-choices-choice-image-wrap">
																												<div class="thumb-placeholder"><span class="fa far fa-image"></span><br>Click to add image</div>
																										  	</div>
																										  </div>
																										  <span class="image-choices-choice-text input-label">Thumb 2</span>
																									  </label>
																									</div>
																								';
																		
																						$output .= '<div class="image-choices-choice col-sm-4">
																								  <label class="radio-inline" for="thumb_select_thumb_1">
																									  <div class="thumb-image-outer-wrap">
																									  <div class="prettyradio"><input name="thumb_select" type="radio" value="3" id="" style="display: none;" autocomplete="disabled" class="the_input_element"><a class="ui-state-default" style="background: rgb(139, 195, 74);"></a></div>
																									  
																										<div class="image-choices-choice-image-wrap">
																											<div class="thumb-placeholder"><span class="fa far fa-image"></span><br>Click to add image</div>
																										</div>
																									  </div>
																									  <span class="image-choices-choice-text input-label">Thumb 3</span>
																								  </label>
																								</div>
																							';
																		
																					$output .= '</div>';
																				$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	
																	
																	case 'icon-select-group':
																	
																		if(!function_exists('nf_not_found_notice_ss'))
																			{
																			$output .= '<div class="'.$input_width.'  input_container">';
																					$output .= '<div class="alert alert-success">'.__('You need the "<strong><em>Super Select for NEX-forms</em></strong></a>" Add-on to use enable this field <br /><br><a href="https://1.envato.market/zQ6de" target="_blank" class="btn btn-success btn-sm">Buy Now</a>','nex-forms').'</div>';
																			$output .= '</div>';
																			}
																		else
																			{	
																			
																			$output .= '<div class="'.$input_width.' input_container error_message icon-label-right" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'"  data-secondary-message="Minimum of {x} selections required">';
																				
																				$output .= '<div class="icon-spin-prev" style="display:none;"><span class="fa fa-caret-left"></span></div>';
																				
																				$output .= '<div class="the-icon-field-container the_input_element">';
																					
																					$output .= '<div class="selected-icon-holder" style="display:none;">
																									
																										<div class="icon-holder default-selected-icon is_default_selection icon_holder_0" data-icon-number="0">
																										  <input class="the_input_element" type="radio" name="super_select" value="0">
																										  <div class="icon-select">
																											  <div class="off-icon off_icon_number_0"><span class="far fa-square" data-toggle="tooltip_bs" data-placement="top" title="" data-original-title="--Select--" style="font-size: 24px;"></span></div>
																											  <div class="on-icon on_icon_number_0"><span class="fas fa-check-square" data-toggle="tooltip_bs" data-placement="top" title="" data-original-title="--Select--" style="font-size: 24px;"></span></div>
																										  </div>
																										  <div class="icon-label">
																											<div class="off-label" style="line-height: 24px;">--Select--</div>
																											<div class="on-label" style="line-height: 24px;">--Select--</div>
																										  </div>
																										</div>
																										<span class="fa fa-caret-down" style="line-height: 24px;"></span>
																									</div>';
																					$output .= '</div>';
																					
																				$output .= '<div class="the-icon-option-container">';	
																					$output .= '<div class="icon-container col-sm-12">
																									<div class="icon-holder icon_holder_1" data-icon-number="1">
																									  <input class="the_input_element" type="radio" name="super_select" value="1">
																									  <div class="icon-select">
																										  <div class="off-icon off_icon_number_1"><span class="far fa-square" data-toggle="tooltip_bs" data-placement="top" title="Checkbox 1" data-original-title="Cloudy" style="font-size: 24px;"></span></div>
																										  <div class="on-icon on_icon_number_1"><span class="fas fa-check-square" data-toggle="tooltip_bs" data-placement="top" title="Checkbox 1" data-original-title="Cloudy" data-on-color="rgba(64,196,255,1)" style="font-size: 24px; color: rgb(64, 196, 255);"></span></div>
																									  </div>
																									  <div class="icon-label">
																										<div class="off-label" style="line-height: 24px;">Checkbox 1</div>
																										<div class="on-label" style="line-height: 24px;">Checkbox 1</div>
																									  </div>
																									  
																								  </div>
																								  <div class="icon-holder icon_holder_2" data-icon-number="2">
																									  <input class="the_input_element" type="radio" name="super_select" value="2">
																									  <div class="icon-select">
																										  <div class="off-icon off_icon_number_2"><span class="far fa-square" data-toggle="tooltip_bs" data-placement="top" title="Checkbox 2" data-original-title="Partly Cloudy" style="font-size: 24px;"></span></div>
																										  <div class="on-icon on_icon_number_2"><span class="fas fa-check-square" data-toggle="tooltip_bs" data-placement="top" title="Checkbox 2" data-original-title="Partly Cloudy" data-on-color="rgba(64,196,255,1)" style="color: rgb(64, 196, 255); font-size: 24px;"></span></div>
																									  </div>
																									  <div class="icon-label">
																										<div class="off-label" style="line-height: 24px;">Checkbox 2</div>
																										<div class="on-label" style="line-height: 24px;">Checkbox 2</div>
																									  </div>
																									 
																								  </div>
																								  <div class="icon-holder icon_holder_3" data-icon-number="3">
																									  <input class="the_input_element" type="radio" name="super_select" value="1">
																									  <div class="icon-select">
																										  <div class="off-icon off_icon_number_3"><span class="far fa-circle" data-toggle="tooltip_bs" data-placement="top" title="Radio 1" data-original-title="Sunny" style="font-size: 24px;"></span></div>
																										  <div class="on-icon on_icon_number_3"><span class="fas fa-check-circle" data-toggle="tooltip_bs" data-placement="top" title="Radio 1" data-original-title="Sunny" style="font-size: 24px;"></span></div>
																									  </div>
																									  <div class="icon-label">
																										<div class="off-label" style="line-height: 24px;">Radio 1</div>
																										<div class="on-label" style="line-height: 24px;">Radio 1</div>
																									  </div>
																									  
																								  </div><div class="icon-holder icon_holder_4" data-icon-number="4">
																									  <input class="the_input_element" type="radio" name="super_select" value="2">
																									  <div class="icon-select">
																										  <div class="off-icon off_icon_number_4"><span class="far fa-circle" data-toggle="tooltip_bs" data-placement="top" title="Radio 2" data-original-title="Sunny" style="font-size: 24px;"></span></div>
																										  <div class="on-icon on_icon_number_4"><span class="fas fa-check-circle" data-toggle="tooltip_bs" data-placement="top" title="Radio 2" data-original-title="Sunny" style="font-size: 24px;"></span></div>
																									  </div>
																									  <div class="icon-label">
																										<div class="off-label" style="line-height: 24px;">Radio 2</div>
																										<div class="on-label" style="line-height: 24px;">Radio 2</div>
																									  </div>
																									  
																								  </div>
																							</div>';
																				$output .= '</div>';
																				$output .= '<div class="icon-spin-next" style="display:none;"><span class="fa fa-caret-right"></span></div>';
																				$output .= '</div>';
																			}
																	break;
																	
																	
																	
																	case 'multi-image-select-group':
																		$output .= '<div class="input_holder '.$input_width.' input_container">';
																			$output .= '<div class="the-radios error_message" id="the-radios" data-checked-color="" data-checked-class="fa-check" data-unchecked-class="" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" >';
																				$output .= '<div class="input-inner" data-svg="demo-input-1">';
																					$output .= '<label class="radio-inline " for="check-0"  data-svg="demo-input-1">
																									  <span class="svg_ready">
																									  <input class="radio svg_ready the_input_element" type="checkbox" name="multi_thumbs_select" id="check-0" value="1" >
																									  <span class="input-label radio-label">'.__('Thumbnail 1','nex-forms').'</span>
																									  </span>
																								  </label>
																								  <label class="radio-inline " for="check-2"  data-svg="demo-input-1">
																									  <span class="svg_ready">
																									  <input class="radio svg_ready the_input_element" type="checkbox" name="multi_thumbs_select" id="check-2" value="2" >
																									  <span class="input-label radio-label">'.__('Thumbnail 2','nex-forms').'</span>
																									  </span>
																								  </label>
																								  <label class="radio-inline " for="check-3"  data-svg="demo-input-1">
																									  <span class="svg_ready">
																									  <input class="radio svg_ready the_input_element" type="checkbox" name="multi_thumbs_select" id="check-3" value="3" >
																									  <span class="input-label radio-label">'.__('Thumbnail 3','nex-forms').'</span>
																									  </span>
																								  </label>';
																	
																				$output .= '</div>';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	
																	case 'star-rating':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div id="star" data-total-stars="5" data-enable-half="false" class="error_message svg_ready " style="cursor: pointer;" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></div>';
																		$output .= '</div>';
																	break;
																	case 'slider' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<div class="error_message slider" id="slider" data-fill-color="#ddd" data-min-value="0" data-max-value="100" data-step-value="1" data-starting-value="0" data-background-color="#ffffff" data-slider-border-color="#CCCCCC" data-handel-border-color="#CCCCCC" data-handel-background-color="#FFFFFF" data-text-color="#777" data-dragicon="" data-dragicon-class="btn btn-default" data-count-text="{x}"  data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></div>';
																		$output .= '<input name="slider" class="hidden the_input_element the_slider" type="text">';
																		$output .= '</div>';
																	break;
																	case 'spinner' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input name="spinner" type="text" id="spinner" class="error_message the_spinner the_input_element form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-minimum="0" data-maximum="100" data-step="1" data-starting-value="0" data-decimals="0"  data-postfix-icon="" data-prefix-icon="" data-postfix-text="" data-prefix-text="" data-postfix-class="btn-default" data-prefix-class="btn-default" data-down-icon="fa fa-minus" data-up-icon="fa fa-plus" data-down-class="btn-default" data-up-class="btn-default" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" />';
																		$output .= '</div>';
																	break;
																	case 'tags' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input id="tags" value="" name="tags" type="text" class="tags error_message  the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-max-tags="" data-tag-class="label-info" data-tag-icon="fa fa-tag" data-border-color="#CCCCCC" data-background-color="#FFFFFF" data-placement="bottom" data-content="Please enter a value" title="">';
																		$output .= '</div>';
																	break;
																	case 'nf-color-picker':
																		$output .= '<div class="'.$input_width.'  input_container"><div class="input-group colorpicker-component">';
																				$output .= '<input type="color" name="'.$nf_functions->format_name($attr['label']).'" class="form-control error_message the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value=""  data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="" title="">';
																		$output .= '<span class="input-group-addon"><i></i></span></div></div>';
																	break;
																	case 'password' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input id="" type="password" name="text_field" data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value="" maxlength="200" class="error_message svg_ready the_input_element text pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="" title="">';
																		$output .= '</div>';
																	break;
																	
																	case 'autocomplete' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																		$output .= '<input id="autocomplete" value="" name="autocomplete" type="text" class="error_message svg_ready form-control  the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-text-color="#000000" data-border-color="#CCCCCC" data-background-color="#FFFFFF" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="">';
																		$output .= '<div style="display:none;" class="get_auto_complete_items"></div>';
																		$output .= '</div>';
																	break;
																	
																	case 'date' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="input-group date" id="datetimepicker" data-format="DD/MM/YYYY" data-language="en">';
																				$output .= '<span class="input-group-addon prefix"><span class="fa fa-calendar-o"></span></span>';
																				$output .= '<input type="text" name="date" class="error_message form-control the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].' " data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" />';
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	case 'time' :
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="input-group time" id="datetimepicker" data-format="hh:mm A" data-language="en">';
																				$output .= '<span class="input-group-addon prefix"><span class="fa fa-clock-o"></span></span>';
																				$output .= '<input type="text" name="time" class="error_message form-control the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" />';
																			$output .= '</div>';
																		$output .= '</div>';
																		
																	break;	
																	
																	case 'submit-button':
																		$output .= '<div class="col-sm-12  input_container">';
																			$output .= '<button class="nex-submit svg_ready the_input_element btn btn-default" data-ga="'.$this->google_analytics_conversion_code.'">Submit</button>';
																		$output .= '</div>';
																		$i=0;
																	break;
																	case 'submit-button2':
																		$output .= '<div class="col-sm-12  input_container">';
																			$output .= '<button class="nex-submit svg_ready the_input_element btn btn-default" data-ga="'.$this->google_analytics_conversion_code.'">'.$attr['label'].'</button>';
																		$output .= '</div>';
																		
																	break;
																	
																	case 'upload-multi':
																	
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
																			  <div class="input-group">
																				<div name="multi_upload" class="the_input_element form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].' uneditable-input span3 error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="'.$preferences['validation_preferences']['pref_invalid_file_ext_msg'].'" data-max-per-file-message="'.$preferences['validation_preferences']['pref_max_file_exceded'].'" data-min-per-file-message="'.$preferences['validation_preferences']['pref_min_file_exceded'].'"  data-max-all-file-message="'.$preferences['validation_preferences']['pref_max_file_af_exceded'].'" data-file-upload-limit-message="'.$preferences['validation_preferences']['pref_max_file_ul_exceded'].'" data-max-size-pf="0" data-min-size-pf="0" data-max-size-overall="0" data-max-files="0" data-placement="bottom" data-trigger="fileinput" name="multi-upload[]"><i class="fas fa-cloud-upload-alt fileinput-exists"></i> <span class="fileinput-filename"></span></div>
																				<span class="input-group-addon btn btn-default btn-file postfix"><span class="fas fa-cloud-upload-alt"></span><input type="file" name="multi_file[]" multiple="" class="the_input_element"></span>
																				<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput"><span class="fa fa-trash-o"></span></a>
																				<div class="get_file_ext" style="display:none;">doc,docx,mpg,mpeg,mp3,mp4,odt,odp,ods,pdf,ppt,pptx,txt,xls,xlsx,jpg,jpeg,png,psd,tif,tiff</div>
																			  </div>
																			</div>';	
																		$output .= '</div>';
																	break;
																	
																	case 'upload-single':
																	
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
																			  <div class="input-group">
																				<div name="file_upload" class="the_input_element form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].' uneditable-input span3 error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="'.$preferences['validation_preferences']['pref_invalid_file_ext_msg'].'" data-placement="bottom" data-trigger="fileinput"><i class="fas fa-cloud-upload-alt fileinput-exists"></i> <span class="fileinput-filename"></span></div>
																				<span class="input-group-addon btn btn-default btn-file postfix"><span class="fas fa-cloud-upload-alt"></span><input type="file" name="single_file" class="the_input_element"></span>
																				<a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput"><span class="fa fa-trash-o"></span></a>
																				<div class="get_file_ext" style="display:none;">doc,docx,mpg,mpeg,mp3,mp4,odt,odp,ods,pdf,ppt,pptx,txt,xls,xlsx</div>
																			  </div>
																			</div>';	
																		$output .= '</div>';
																	break;
																	
																	case 'upload-image':
																	
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
																				  <div name="image_upload" class="the_input_element fileinput-preview thumbnail" data-trigger="fileinput"></div>
																				  <div class="nf_add_image">
																					<span class="btn btn-default btn-file the_input_element error_message" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" data-secondary-message="'.$preferences['validation_preferences']['pref_invalid_file_ext_msg'].'" data-placement="top"><span class="fileinput-new "><span class="fa fa-cloud-upload"></span></span><span class="fileinput-exists nf_change_image"><span class="fa fa-refresh"></span><input type="file" name="image_upload" ></span>
																					<a href="#" class="btn btn-default fileinput-exists nf_remove_image" data-dismiss="fileinput"><span class="fa fa-close"></span></a>
																				  </div>
																				  <div class="get_file_ext" style="display:none;">gif,jpg,jpeg,png,psd,tif,tiff</div>
																				</div>';	
																		$output .= '</div>';
																		$i=0;
																	break;
																	case 'preset_field':
																		$output .= '<div class="'.$input_width.'  input_container">';
																			$output .= '<div class="input-group">';
																				$output .= '<span class="input-group-addon prefix "><span class="fa '.$attr['icon'].'"></span></span>';
																				$sec_message = '';
																				if($attr['field_name']=='query')
																					{
																						$output .= '<textarea name="'.$nf_functions->format_name($attr['label']).'" placeholder=""  data-maxlength-color="label label-success" data-maxlength-position="bottom" data-maxlength-show="false" data-default-value="" class="error_message '.$set_required.' the_input_element textarea pre-format form-control '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title=""></textarea>';
																						
																					}
																				else
																					{
																					if($attr['field_name']=='email')
																						$sec_message = $preferences['validation_preferences']['pref_email_format_msg'];
																					if($attr['field_name']=='phone_number')
																						$sec_message = $preferences['validation_preferences']['pref_phone_format_msg'];
																					if($attr['field_name']=='url')
																						$sec_message = $preferences['validation_preferences']['pref_url_format_msg'];
																					if($attr['field_name']=='numbers')
																						$sec_message = $preferences['validation_preferences']['pref_numbers_format_msg'];
																					if($attr['field_name']=='char')
																						$sec_message = $preferences['validation_preferences']['pref_char_format_msg'];
																					
																					$output .= '<input type="text" name="'.$attr['field_name'].'" class="error_message '.$set_required.' '.$attr['format'].' form-control the_input_element '.$preferences['field_preferences']['pref_input_size'].' '.$preferences['field_preferences']['pref_input_text_align'].'" data-onfocus-color="#66AFE9" data-drop-focus-swadow="1" data-placement="bottom" data-content="'.$preferences['validation_preferences']['pref_requered_msg'].'" title="" data-secondary-message="'.$sec_message.'"/>';
																					}
																			
																			$output .= '</div>';
																		$output .= '</div>';
																	break;
																	}
														
														if($attr['type']!='submit-button' && $attr['type']!='submit-button2')
															{
															if($label_pos == 'right')
																{
																$output .= '<div class="'.$label_width.' '.$align_class.' label_container '.(($preferences['field_preferences']['pref_label_text_align']) ? $preferences['field_preferences']['pref_label_text_align'] : 'align_left').'" '.$hide_label.'>';
																	$output .= '<label class="nf_title '.$preferences['field_preferences']['pref_label_size'].'"><span class="the_label">'.(($set_required) ? '*' : '').''.$attr['label'].'</span><small class="sub-text style_italic">'.(($preferences['field_preferences']['pref_sub_label']=='on') ? 'Sub label' : '').'</small></label>';
																$output .= '</div>';
																}
															}
																
																$output .= '<span class="help-block hidden">'.__('Help Text...','nex-forms').'</span>';
													$output .= '</div>';
												$output .= '</div>';
												
												$output .= '<div class="field_settings" style="display:none">';
													$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs move_field"><i class="fa fa-arrows"></i></div>';
													$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
													$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs duplicate_field"  	title="'.__('Duplicate Field','nex-forms').'"><i class="fa fa-files-o"></i></div>';
													$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
												$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</div>';	
									
								}
								
								
									
							if($attr['type']=='tool-spacer-end')
								{
								$output .= '</div>';
								}
								
								
							}
							
							$other_elements = array
							(
						//HEADING
							'submit-button' => array
								(
								'category'	=>	'the_submit button_fields common_fields preset_fields special_fields selection_fields',
								'label'	=>	__('Submit Button','nex-forms'),
								'sub_label'	=>	'',
								'icon'	=>	'fa fa-send',
								'type' => 'submit-button',
								'settings_class' => '.s-submit',
								'settings_tabs' => '#input-settings, #animation-settings, #extra-settings',
								),
							'heading' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('Heading','nex-forms'),
								'icon'	=>	'fa fa-header',
								'type' => 'heading',
								'settings_class' => '.s-headings',
								'settings_tabs' => '#input-settings, #animation-settings',
								),
							'icon' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('Icon','nex-forms'),
								'icon'	=>	'fa fa-flag',
								'type' => 'icon',
								'settings_class' => '.s-icon',
								'settings_tabs' => '#input-settings, #animation-settings',
								),
							/*'math_logic' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('Math Logic','nex-forms'),
								'icon'	=>	'fa fa-calculator',
								'type' => 'math_logic',
								'settings_class' => '.s-math',
								'settings_tabs' => '#input-settings, #animation-settings, #math-settings',
								),
							'math_logic_slider' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('Math Logic Slider','nex-forms'),
								'icon'	=>	'fa fa-calculator',
								'type' => 'math_logic_slider math_logic',
								'settings_class' => '.s-math',
								'settings_tabs' => '#input-settings, #animation-settings, #math-settings',
								),*/
							'html_image' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('Image','nex-forms'),
								'icon'	=>	'far fa-image',
								'type' => 'html_image',
								'settings_class' => '.s-image',
								'settings_tabs' => '#input-settings, #animation-settings, #extra-settings',
								),
							'paragraph' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('Paragraph','nex-forms'),
								'icon'	=>	'fa fa-align-justify',
								'type' => 'paragraph',
								'settings_class' => '.s-paragraph',
								'settings_tabs' => '#input-settings, #animation-settings, #math-settings',
								),
							'html' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('HTML','nex-forms'),
								'icon'	=>	'fa fa-code',
								'type' => 'html',
								'settings_class' => '.s-html',
								'settings_tabs' => '#input-settings, #animation-settings, #math-settings',
								),
							
							'divider' => array
								(
								'category'	=>	'html_fields',
								'label'	=>	__('Divider','nex-forms'),
								'icon'	=>	'fa fa-minus',
								'type' => 'divider',
								'settings_class' => '.s-divider',
								'settings_tabs' => '#input-settings, #animation-settings',
								),
											
							);
							
							
							$output .= '<div class="tool-spacer form-editor"><span class="tool-section-title" title="'.__('Math','nex-forms').'" data-toggle="" data-placement="top">'.__('Math','nex-forms').'</span>';
							
								$output .= '<div class="field form_field all_fields math_logic html_fields " data-settings=".s-math" data-settings-tabs="#input-settings, #animation-settings, #math-settings" >';
												
									$output .= '<div class="draggable_object "   >';
										$output .= '<i title="Math Logic" data-toggle="tooltip_bs" data-placement="bottom" class="fa fa-calculator"></i>';
									$output .= '</div>';
									
										$output .= '<div id="form_object" class="form_object" style="display:none;">';
											$output .= '<div class="row">';
												$output .= '<div class="col-sm-12" id="field_container">';
													$output .= '<div class="row">';
														$output .= '<div class="col-sm-12 input_container">';
										
																	$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
																	$output .= '<h1 class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">{math_result}</h1>';
																
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</div>';
													
											$output .= '<div class="field_settings" style="display:none">';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs move_field"><i class="fa fa-arrows"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs duplicate_field"  	title="'.__('Duplicate Field','nex-forms').'"><i class="fa fa-files-o"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
											$output .= '</div>';
											
										
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';	
								
								
								
								//SLIDER
								
								$output .= '<div class="field form_field all_fields math_logic math_logic_slider math_fields " data-settings=".s-math-slider" data-settings-tabs="#input-settings, #animation-settings, #math-settings" >';
												
									$output .= '<div class="draggable_object "   >';
										$output .= '<i title="Math Logic Slider" data-toggle="tooltip_bs" data-placement="bottom" class="fa far fa-arrows-h"></i>';
									$output .= '</div>';
									
										$output .= '<div id="form_object" class="form_object" style="display:none;">';
											$output .= '<div class="row">';
												$output .= '<div class="col-sm-12" id="field_container">';
													$output .= '<div class="row">';
														$output .= '<div class="col-sm-12 input_container">';
										
															
																	$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
																	$output .= '
																	<div id="math-slider-wrapper" class="math-slider-wrapper">
																		<div class="math-slider-progress">
																			<div class="math-slider-progress-bar" style="width: 0%;">
																				<div class="math-slider-progress-bar-total" style="left: 0%;">
																					<span style="position: relative; top: 0px;" class="the_input_element align_center" data-max-value="1000" data-math-equation="" data-original-math-equation="" data-decimal-places="0">{math_result}</span>
																				</div>
																			</div>
																		</div>
																	</div>
																	';
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</div>';
													
											$output .= '<div class="field_settings" style="display:none">';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs move_field"><i class="fa fa-arrows"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs duplicate_field"  	title="'.__('Duplicate Field','nex-forms').'"><i class="fa fa-files-o"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
											$output .= '</div>';
											
										
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';
								
								
							
							$output .= '</div>';
							
							$output .= '<div class="tool-spacer"><span class="tool-section-title" title="'.__('HTML Elements','nex-forms').'" data-toggle="" data-placement="top">'.__('HTML Elements','nex-forms').'</span>';
							foreach($other_elements as $type=>$attr)
								{
								$output .= '<div class="field form_field all_fields '.$type.' '.$attr['type'].' '.$attr['category'].' '.(($set_required) ? 'required' : '').'" data-settings="'.$attr['settings_class'].'" data-settings-tabs="'.$attr['settings_tabs'].'" >';
												
									$output .= '<div class="draggable_object "   >';
										$output .= '<i title="'.$attr['label'].'" data-toggle="tooltip_bs" data-placement="bottom" class="'.$attr['icon'].'"></i>';
									$output .= '</div>';
									if($attr['type']=='icon')
										{
										$output .= '<div id="form_object" class="form_object" style="display:none;">';
											$output .= '<div class="icon_container_wrapper">';
												$output .= '<div class="icon_container" id="field_container">';
													$output .= '<div class="">';
														$output .= '<div class="input_container">';
										}
									else
										{
										$output .= '<div id="form_object" class="form_object" style="display:none;">';
											$output .= '<div class="row">';
												$output .= '<div class="col-sm-12" id="field_container">';
													$output .= '<div class="row">';
														$output .= '<div class="col-sm-12 input_container">';
										}
															switch($attr['type'])
																{
																case 'heading':
																	$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
																	$output .= '<h1 class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">'.__('Heading 1','nex-forms').'</h1>';
																break;
																
																case 'paragraph':
																	$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
																	$output .= '<div class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">'.__('Add your paragraph','nex-forms').'</div><div style="clear:both;"></div>';
																break;
																case 'html':
																	$output .= '<input type="hidden" class="set_math_result" value="0" name="math_result">';
																	$output .= '<div class="the_input_element" data-math-equation="" data-original-math-equation="" data-decimal-places="0">'.__('Add Text or HTML','nex-forms').'</div><div style="clear:both;"></div>';
																break;
																case 'divider':
																	$output .= '<hr class="the_input_element" />';
																break;
																case 'html_image':
																	$output .= '<div class="image_container empty"><span class="far fa-image"></span></div>';
																break;
																
																case 'icon':
																	$output .= '<div class="the_input_element icon_element"><span class="fas fa-check-circle"></span></div>';
																break;
																
																case 'submit-button':
																	$output .= '<button class="nex-submit svg_ready the_input_element btn btn-default" data-ga="'.$this->google_analytics_conversion_code.'">'.__('Submit','nex-forms').'</button>';
																
																break;
																}
												
													$output .= '</div>';
												$output .= '</div>';
											$output .= '</div>';
													
											$output .= '<div class="field_settings" style="display:none">';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs move_field"><i class="fa fa-arrows"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs duplicate_field"  	title="'.__('Duplicate Field','nex-forms').'"><i class="fa fa-files-o"></i></div>';
												$output .= '<div class="btn btn-default waves-effect-nf waves-light-nf btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
											$output .= '</div>';
											
										
										$output .= '</div>';
									$output .= '</div>';
								$output .= '</div>';	
											}
								$i = $i+0.08;	
								
								
								$output .= '<div class="field form_field grid other-elements is_panel" data-settings=".s-panel" data-settings-tabs="#input-settings, #animation-settings, #extra-settings">';
											$output .= '<div class="draggable_object input-group-sm">';
												$output .= '<i title="Panel" data-toggle="tooltip_bs" data-placement="bottom" class="fa fa-window-maximize"></i>';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
													$output .= '<div class="input-inner" data-svg="demo-input-1">';
														$output .= '<div class="row">';
															$output .= '<div class="input_holder col-sm-12">';
																$output .= '<div class="panel panel-default ">';
																	$output .= '<div class="panel-heading">'.__('Panel Heading','nex-forms').'</div>';
																	$output .= '<div class="panel-body the-panel-body">';
																	$output .= '</div>';
																$output .= '</div>';
															$output .= '</div>';
														$output .= '</div>';
													$output .= '</div>';
												$output .= '<div class="field_settings grid" style="display:none">';
													$output .= '<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>';
													$output .= '<div class="btn btn-default btn-xs edit"  	title="'.__('Edit Field Attributes','nex-forms').'"><i class="fa fa-edit"></i></div>';
													$output .= '<div title="'.__('Duplicate Field','nex-forms').'" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
													$output .= '<div class="btn btn-default btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
												$output .= '</div>';
											$output .= '</div>';
										$output .= '</div>';
							$output .= '</div>';
					
					
					
					
								
								
								
								
								
					$output .= '<div class="tool-spacer"><span class="tool-section-title" title="Other" data-toggle="" data-placement="top">'.__('Misc','nex-forms').'</span>';
					
					$output .= '<div class="field form_field field_spacer">';
											$output .= '<div class="draggable_object input-group-sm">';
												$output .= '<i title="Vertical Spacer" data-toggle="tooltip_bs" data-placement="bottom" class="fas fa-arrows-alt-v"></i>';
											$output .= '</div>';
											$output .= '<div id="form_object" class="form_object" style="display:none;">';
														$output .= '<div class="field_spacer">';
														
															$output .= '<div class="up_arrow fas fa-minus">';
															$output .= '</div>';
															
															$output .= '<div class="v_line">';
															$output .= '</div>';
															
															$output .= '<div class="height_display">';
																$output .= '<span class="total_px">10</span>px';
															$output .= '</div>';
															
															$output .= '<div class="down_arrow fas fa-caret-down">';
															$output .= '</div>';	
															
														$output .= '</div>';
														$output .= '<div class="field_settings" style="display:none">';
															$output .= '<div class="btn btn-default btn-xs delete" title="'.__('Delete field','nex-forms').'"><i class="fa fa-close"></i></div>';
															$output .= '<div title="'.__('Duplicate Field','nex-forms').'" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>';															
															
														$output .= '</div>';
													$output .= '</div>';	
											$output .= '</div>';
											
										$output .= '</div>';
										
								$output .= '</div>';
					
					
					
					
				
				
				$output .= '<div class="canvas-action-btns">';
						$output .= '<a class="canvas-action-btn conditional-logic-btn btn"  ><span data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Conditional Logic','nex-forms').'" class="fa fa-random"></span></a>';
						$output .= '<a class="canvas-action-btn overall-styling-btn btn"  ><span data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Overall Form Styling','nex-forms').'" class="fa dashicons-before dashicons-admin-appearance"></span></a>';
					$output .= '</div>';
					
				
					
				
				
				$output .= '</div>';
				
				
				
				
				$output .= '<div class="canvas-tools multi-step-tools"><span class="tool-label">'.__('Steps','nex-forms').'<span class="fa fa-caret-right"></span></span>';
				
					$ms_settings = json_decode($this->multistep_settings,true);
					
					
					$output .= '<div class="tool-section">';
					$output .= '
					
					<div class="multi-step-settings-btn" ><span class="fas fa-tools" data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Multi-Step Settings','nex-forms').'"></span></div>';
					$output .= '</div>';
					
					
					$output .= '<div class="tool-section multi-step-fields">';
						$output .= '<div class="field form_field custom-fields grid step ui-draggable ui-draggable-handle">
														  <div class="draggable_object">
												<span class="add-step-btn" data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Add New Step','nex-forms').'"><span class="fas fa-file-medical"></span></span>
											</div>
														  
														  <div style="display:none;" class="form_object" id="form_object">
															<div data-svg="demo-input-1" class="input-inner">
															  <div class="row">
																<div class="col-sm-12">
																  <div class="tab-pane grid-system grid-system panel panel-default">
																	<div class="zero-clipboard step_2piont0">
																	  <div class="ms-step-number-wrapper">
																		<div class="ms-step-number">1</div>
																	  </div>
																	  <div class="ms-step-expand-wrapper">
																		<div class="ms-step-expand"><span class="fas fa-chevron-down"></span></div>
																	  </div>
																	  <div class="row step-settings">
																		  <div class="col-xs-10">
																			<div class="row">
																			  <div class="col-xs-3"><small>Icon</small>
																				<div class="input-group input-group-sm">
																				  <div class="input-group-addon action-btn set_step_icon"><i class=""></i></div>
																				  <input name="multi_step_icon" type="text" class="form-control" placeholder="Icon class" value="">
																				</div>
																			  </div>
																			  <div class="col-xs-4"><small>Title</small>
																				<input name="multi_step_name" type="text" class="form-control" placeholder="Step 1 title" value="">
																			  </div>
																			  <div class="col-xs-5">
																				<div class="step-description-container">
																				  <div class="description"><small>Description</small>
																					<input name="multi_step_description" type="text" class="form-control" placeholder="Step 1 description" value="">
																				  </div>
																				  <div class="set_time_limit"><small>Time Limit</small>
																					<input name="multi_step_time_limit" type="text" class="form-control" placeholder="Seconds" value="">
																				  </div>
																				</div>
																			  </div>
																			</div>
																		  </div>
																		  <div class="col-xs-2 step-controls">
																			<div title="Delete Step" class="btn delete"><i class="fa fa-close"></i></div>
																			<div title="Duplicate Step" class="btn duplicate_field"><i class="fa fa-files-o"></i></div>
																			<div title="Edit Step" class="btn edit"><i class="fa fa-edit"></i></div>
																		  </div>
																		</div>
</div>
																	<div class="panel-body">
																	
																	
																		
																		<div class="form_field nex_prev_steps grid grid-system grid-system-2 dropped" data-settings=".s-grid" data-settings-tabs="#input-settings, #animation-settings, #extra-settings">
																			  <div class="edit_mask"></div>
																			  <div id="form_object" class="form_object" style="">
																				<div class="input-inner" data-svg="demo-input-1">
																				  <div class="row grid_row">
																					<div class="grid_input_holder col-xs-6">
																					  <div class="panel grid-system panel-default">
																						<div class="panel-body ui-droppable ui-sortable">
																						  <div class="form_field all_fields submit-button the_submit button_fields common_fields preset_fields special_fields selection_fields dropped" data-settings=".s-submit" data-settings-tabs="#input-settings, #animation-settings, #extra-settings" style="position: relative; top: 0px; left: 0px;">
																							<div class="edit_mask"></div>
																							<div id="form_object" class="form_object" style="">
																							  <div class="row">
																								<div class="col-sm-12" id="field_container">
																								  <div class="row">
																									<div class="col-sm-12 input_container">
																									  <button class="prev-step svg_ready the_input_element btn btn-default" data-ga="">Back</button>
																									</div>
																								  </div>
																								</div>
																								<div class="field_settings">
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs move_field"><i class="fa fa-arrows"></i></div>
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs edit" title="Edit Field Attributes"><i class="fa fa-edit"></i></div>
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs duplicate_field" title="Duplicate Field"><i class="fa fa-files-o"></i></div>
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs delete" title="Delete field"><i class="fa-trash-alt far"></i></div>
																								</div>
																							  </div>
																							</div>
																						  </div>
																						</div>
																					  </div>
																					</div>
																					<div class="grid_input_holder col-xs-6">
																					  <div class="panel grid-system panel-default">
																						<div class="panel-body ui-droppable ui-sortable">
																						  <div class="form_field all_fields submit-button the_submit button_fields common_fields preset_fields special_fields selection_fields dropped" data-settings=".s-submit" data-settings-tabs="#input-settings, #animation-settings, #extra-settings" style="position: relative; left: 0px; top: 0px;">
																							<div class="edit_mask"></div>
																							<div id="form_object" class="form_object" style="">
																							  <div class="row">
																								<div class="col-sm-12" id="field_container">
																								  <div class="row">
																									<div class="col-sm-12 input_container align_right">
																									  <button class="nex-step svg_ready the_input_element btn btn-default" data-ga="">Next</button>
																									</div>
																								  </div>
																								</div>
																								<div class="field_settings">
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs move_field"><i class="fa fa-arrows"></i></div>
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs edit" title="Edit Field Attributes"><i class="fa fa-edit"></i></div>
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs duplicate_field" title="Duplicate Field"><i class="fa fa-files-o"></i></div>
																								  <div class="btn btn-default waves-effect-nf waves-light-nf btn-xs delete" title="Delete field"><i class="fa-trash-alt far"></i></div>
																								</div>
																							  </div>
																							</div>
																						  </div>
																						</div>
																					  </div>
																					</div>
																				  </div>
																				  <div class="field_settings grid">
																					<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>
																					<div class="btn btn-default btn-xs edit" title="Edit Field Attributes"><i class="fa fa-edit"></i></div>
																					<div title="Duplicate Field" class="btn btn-default btn-xs duplicate_field"><i class="fa fa-files-o"></i></div>
																					<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa-trash-alt far"></i></div>
																				  </div>
																				</div>
																			  </div>
																			</div>
																		

																	
																	</div>
																  </div>
																</div>
															  </div>
															</div>
														  </div>
														</div>';
				$output .= '</div>';
				
				$multi_step_stepping 	= (isset($ms_settings['0']['multi_step_stepping'])) ? $ms_settings['0']['multi_step_stepping'] : '';
				$multi_step_total 		= (isset($ms_settings['0']['multi_step_total'])) ? $ms_settings['0']['multi_step_total'] : '';
				
				$output .= '<div class="tool-section  muli-step-selection" >';
						$output .= '<ul class="show_all_steps" '.((!$multi_step_stepping) ? 'style="display:none;"' : '').'><li class="all_steps" ><a data-show-step="all" data-toggle="tooltip_bs" data-placement="bottom" title="'.__('Show all Steps','nex-forms').'">'.__('All','nex-forms').' <span class="all_steps_count">'.$multi_step_total.'</span></a></li></ul>';
						$output .= '<ul class="multi-step-stepping">';
							
							$output .= wp_unslash($multi_step_stepping);
							
						$output .= '</ul>';
					$output .= '</div>';
				
				
				
				$output .= '<div class="tool-section hidden">';
				
				$output .= '</div>';
				
				
				
				
				$output .= '</div>';
				
				$output .= '<div class="canvas-tools hidden">';

					
					
					$output .= '<div class="tool-section select-other-fields">';
					$output .= '<span class="tool-label top-tools">'.__('Canvas','nex-forms').'</span>';
					
					
								
					$output .= '</div>';
					
				$output .= '</div>';
			
				
				
				
				
				
				
				$output .= '<div class="panel-heading" style="display:none;">';
					$output .= '<span class="btn btn-primary glyphicon glyphicon-hand-down"></span>';
				$output .= '</div>';
				
				$output .= '<div class="clean_html hidden"></div>';
				$output .= '<div class="admin_html hidden"></div>';
				
				$output .= '<div class="clean_success_html hidden"></div>';
				$output .= '<div class="clean_success_admin_html hidden"></div>';
			
				$output .= '<style type="text/css" name="custom_css_live" class="custom_css_live" id="custom_css_live">'.$this->custom_css.'</style>';
				
				$output .= '<div class="undo hidden"></div>';
				$output .= '<div class="redo hidden"></div>';
	
				$output .= '<div class="form_canvas">';

				$output .= '<div class="width_bar" style="display:none;">';
					$output .= '<div class="width_input" style="display:none;">';
						
						
						$theme_settings = json_decode($this->md_theme,true);
			
						$set_theme 			= ($theme_settings['0']['theme_name']) 	? $theme_settings['0']['theme_name'] 	: 'default';
						$set_theme_shade 	= ($theme_settings['0']['theme_shade']) ? $theme_settings['0']['theme_shade'] 	: 'light';	
						
						$set_form_theme = ($this->form_theme) ? $this->form_theme : 'bootstrap';
						$set_jq_theme 	= ($this->jq_theme) ? $this->jq_theme : 'default';
						
						if($set_form_theme=='m_design')
							$set_current_theme = 'material_theme';
						
						$disabled = 'disabled="disabled"';
						$get_theme = 'ft_not_installed';
						if(function_exists('nf_form_themes_prefix_register_resources'))
							{
							$disabled = '';
							$get_theme = '';	
							}
						
						$output .= '<div class="input-group settings_form_theme"  >';
						$output .= '<small class="label">'.__('Theme','nex-forms').'</small>';
						$output .= '<span class="input-group-addon">';
									$output .= '<span class="icon-text"><span class="dashicons-before dashicons-admin-appearance"></span></span>';
								$output .= '</span>';
						$output .= '<select name="set_form_theme" class="form-control set_form_theme '.$get_theme.'" data-selected="'.$set_form_theme.'" data-toggle="tooltip_bs2" data-placement="top" title="Select Overall Form Theme">
									<option value="bootstrap">'.__('Bootstrap','nex-forms').'</option>
									<option '.$disabled.' value="m_design">'.__('Material','nex-forms').'</option>
									<option '.$disabled.' value="neumorphism">'.__('Neumorphism','nex-forms').'</option>
									<option '.$disabled.' value="jquery_ui">'.__('jQuery UI','nex-forms').'</option>
									<option value="browser">'.__('Classic','nex-forms').'</option>
								</select>';	
						
						$output .= '</div>'; 
						
						
						
						$output .= '<div class="input-group settings_form_theme "  >';
						$output .= '<small class="label">'.__('Color Scheme','nex-forms').'</small>';
						$output .= '<span class="input-group-addon">';
									$output .= '<span class=""><span class="fa fa-paint-brush"></span></span>';
								$output .= '</span>';
						$output .= '<select name="md_theme_selection" class="form-control md_theme_selection '.$get_theme.' '.(($set_form_theme=='m_design' || $set_form_theme=='neumorphism') ?  : 'hidden').'" data-selected="'.$set_theme.'" data-toggle="tooltip_bs2" data-placement="top" title="Select Overall<br>Preset Color Scheme">
										<option  value="default" 		'.(($set_theme=='default' || !$set_theme) ? 'selected="selected"' : '').'>'.__('Default','nex-forms').'</option>
										<option '.$disabled.' value="red" 			'.(($set_theme=='red') ? 'selected="selected"' : '').'>'.__('Red','nex-forms').'</option>
										<option '.$disabled.' value="pink"			'.(($set_theme=='pink') ? 'selected="selected"' : '').'>'.__('Pink','nex-forms').'</option>
										<option '.$disabled.' value="purple"			'.(($set_theme=='purple') ? 'selected="selected"' : '').'>'.__('Purple','nex-forms').'</option>
										<option '.$disabled.' value="deep-purple"		'.(($set_theme=='deep-purple') ? 'selected="selected"' : '').'>'.__('Deep Purple','nex-forms').'</option>
										<option '.$disabled.' value="indigo"			'.(($set_theme=='indigo') ? 'selected="selected"' : '').'>'.__('Indigo','nex-forms').'</option>
										<option '.$disabled.' value="blue"			'.(($set_theme=='blue') ? 'selected="selected"' : '').'>'.__('Blue','nex-forms').'</option>
										<option '.$disabled.' value="light-blue"		'.(($set_theme=='light-blue') ? 'selected="selected"' : '').'>'.__('Light Blue','nex-forms').'</option>
										<option '.$disabled.' value="cyan"			'.(($set_theme=='cyan') ? 'selected="selected"' : '').'>'.__('Cyan','nex-forms').'</option>
										<option '.$disabled.' value="teal"			'.(($set_theme=='teal') ? 'selected="selected"' : '').'>'.__('Teal','nex-forms').'</option>
										<option '.$disabled.' value="green"			'.(($set_theme=='green') ? 'selected="selected"' : '').'>'.__('Green','nex-forms').'</option>
										<option '.$disabled.' value="light-green"		'.(($set_theme=='light-green') ? 'selected="selected"' : '').'>'.__('Light Green','nex-forms').'</option>
										<option '.$disabled.' value="lime"			'.(($set_theme=='lime') ? 'selected="selected"' : '').'>'.__('Lime','nex-forms').'</option>
										<option '.$disabled.' value="yellow"			'.(($set_theme=='yellow') ? 'selected="selected"' : '').'>'.__('Yellow','nex-forms').'</option>
										<option '.$disabled.' value="amber"			'.(($set_theme=='amber') ? 'selected="selected"' : '').'>'.__('Amber','nex-forms').'</option>
										<option '.$disabled.' value="orange"			'.(($set_theme=='orange') ? 'selected="selected"' : '').'>'.__('Orange','nex-forms').'</option>
										<option '.$disabled.' value="brown"			'.(($set_theme=='brown') ? 'selected="selected"' : '').'>'.__('Brown','nex-forms').'</option>
										<option '.$disabled.' value="gray"			'.(($set_theme=='gray') ? 'selected="selected"' : '').'>'.__('Gray','nex-forms').'</option>
										<option '.$disabled.' value="blue-gray"		'.(($set_theme=='blue-gray') ? 'selected="selected"' : '').'>'.__('Blue Gray','nex-forms').'</option>
									</select> ';
							
									$output .= '<select name="choose_form_theme" class="form-control choose_form_theme '.$get_theme.' '.(($set_form_theme=='m_design' || $set_form_theme=='neumorphism') ? 'hidden' : '').'" data-selected="'.$set_jq_theme.'" data-toggle="tooltip_bs2" data-placement="top" title="Select Overall<br>Preset Color Scheme">
												<option  value="default">Default</option>
												<option '.$disabled.' value="black-tie">'.__('black-tie','nex-forms').'</option>
												<option '.$disabled.' value="blitzer">'.__('blitzer','nex-forms').'</option>
												<option '.$disabled.' value="cupertino">'.__('cupertino','nex-forms').'</option>
												<option '.$disabled.' value="dark-hive">'.__('dark-hive','nex-forms').'</option>
												<option '.$disabled.' value="dot-luv">'.__('dot-luv','nex-forms').'</option>
												<option '.$disabled.' value="eggplant">'.__('eggplant','nex-forms').'</option>
												<option '.$disabled.' value="excite-bike">'.__('excite-bike','nex-forms').'</option>
												<option '.$disabled.' value="flick">'.__('flick','nex-forms').'</option>
												<option '.$disabled.' value="hot-sneaks">'.__('hot-sneaks','nex-forms').'</option>
												<option '.$disabled.' value="humanity">'.__('humanity','nex-forms').'</option>
												<option '.$disabled.' value="le-frog">'.__('le-frog','nex-forms').'</option>
												<option '.$disabled.' value="mint-choc">'.__('mint-choc','nex-forms').'</option>
												<option '.$disabled.' value="overcast">'.__('overcast','nex-forms').'</option>
												<option '.$disabled.' value="pepper-grinder">'.__('pepper-grinder','nex-forms').'</option>
												<option '.$disabled.' value="redmond">'.__('redmond','nex-forms').'</option>
												<option '.$disabled.' value="smoothness">'.__('smoothness','nex-forms').'</option>
												<option '.$disabled.' value="south-street">'.__('south-street','nex-forms').'</option>
												<option '.$disabled.' value="start">'.__('start','nex-forms').'</option>
												<option '.$disabled.' value="sunny">'.__('sunny','nex-forms').'</option>
												<option '.$disabled.' value="swanky-purse">'.__('swanky-purse','nex-forms').'</option>
												<option '.$disabled.' value="trontastic">'.__('trontastic','nex-forms').'</option>							
												<option '.$disabled.' value="ui-darkness">'.__('ui-darkness','nex-forms').'</option>
												<option '.$disabled.' value="ui-lightness">'.__('ui-lightness','nex-forms').'</option>
												<option '.$disabled.' value="vader">'.__('vader','nex-forms').'</option>
											</select>
									';
						$output .= '</div>'; 
						
						
						$form_settings = json_decode($this->multistep_settings,true);
				
						$form_width_percentage 	= (isset($form_settings['0']['form_width_percentage'])) ? $form_settings['0']['form_width_percentage'] : '100';
						$form_width_pixels	 	= (isset($form_settings['0']['form_width_pixels'])) ? $form_settings['0']['form_width_pixels'] : '950';
						$form_width_unit	 	= (isset($form_settings['0']['form_width_unit'])) ? $form_settings['0']['form_width_unit'] : '%';
						
						$msg_width_percentage 	= (isset($form_settings['0']['msg_width_percentage'])) ? $form_settings['0']['msg_width_percentage'] : '100';
						$msg_width_pixels	 	= (isset($form_settings['0']['msg_width_pixels'])) ? $form_settings['0']['msg_width_pixels'] : '950';
						$msg_width_unit	 		= (isset($form_settings['0']['msg_width_unit'])) ? $form_settings['0']['msg_width_unit'] : '%';
						
						
						
						if($form_width_unit=='%')
							$set_form_width = $form_width_percentage;
						else
							$set_form_width = $form_width_pixels;
							
						
						if($msg_width_unit=='%')
							$set_msg_width = $msg_width_percentage;
						else
							$set_msg_width = $msg_width_pixels;	
							
							
							
							
						$output .= '<div class="input-group settings_form_width"  >';
							 $output .= '<small class="label">'.__('Width','nex-forms').'</small>';
							$output .= '<input type="text" class="form-control set_form_width" value="'.$set_form_width.'" />';
							$output .= '<span class="input-group-addon '.(($form_width_unit=='%') ? 'active' : '').' percentage width_type" data-toggle="tooltip_bs2" data-placement="top" title="Set Form Width in Percentage<br />(Responsive)">';
								$output .= '%';
							$output .= '</span>';
							$output .= '<span class="input-group-addon '.(($form_width_unit!='%') ? 'active' : '').' pixels width_type" data-toggle="tooltip_bs2" data-placement="top" title="Set Form Width in Pixels<br />(Non-Responsive)">';
								$output .= 'px';
							$output .= '</span>';
							
						
						$output .= '</div>'; 
							
						$output .= '<div class="input-group settings_form_bg" >';
							$output .= '<small class="label">'.__('&nbsp;&nbsp;Background','nex-forms').'</small>';
							/*$output .= '<span class="input-group-addon">';
								$output .= '<span class="icon-text">'.__('Background','nex-forms').'</span>';
							$output .= '</span>';*/
							$output .= '<span class="input-group-addon action-btn color-picker" spellcheck="false" data-toggle="tooltip_bs2" data-placement="top" title="Set Form Wrapper<br>Background Color"><input type="text" class="form-control wrapper-bg-color" name="wrapper-bg-color" id="bs-color"></span>';
							
							
							$output .= '<span class="input-group-addon action-btn drop-shadow shadow-light" title="'.__('Light Shadow','nex-forms').'"><span class="shadow-light" data-toggle="tooltip_bs2" data-placement="top" title="Drop a light shadow<br>on form wrapper"></span></span>';
							$output .= '<span class="input-group-addon action-btn drop-shadow shadow-dark" title="'.__('Dark Shadow','nex-forms').'"><span class="shadow-dark" data-toggle="tooltip_bs2" data-placement="top" title="Drop a dark shadow<br>on form wrapper"></span></span>';
							
							
						
						$output .= '</div>'; 
						
						$output .= '<div class="input-group settings_form_padding" >';	
							$output .= '<small class="label">'.__('Padding','nex-forms').'</small>';
							$output .= '<input name="form_padding" id="form_padding" class="form-control" value="0">';
						$output .= '</div>'; 
							
						$output .= '<div class="settings_more_styling_options" >';
							$output .= '<small class="label">&nbsp;</small>';
							$output .= '<div class="btn" data-toggle="tooltip_bs" data-placement="top" title="More Styling Options"><span class="fas fa-paint-brush" ></span><span class="fas fa-wrench" ></span> </div>';
						$output .= '</div>'; 	
				
									
					$output .= '</div>';
				
				
					
				$output .= '</div>';
					
					$form_style = wp_unslash($this->form_style);
					$form_style = str_replace('"','\'',$form_style);
					
					$form_style = ($this->admin_html) ? $form_style : "background: #fff; box-shadow: rgba(0, 0, 0, 0.2) 0px 7px 16px 0px; border-radius: 4px; padding: 30px; border-color:#ddd;";
					
					$theme = wp_get_theme();
					//if(!$form_style && $theme->Name=='NEX-Forms Demo')
						//$form_style = 'padding: 40px; background: rgb(255, 255, 255) none repeat scroll 0% 0%; box-shadow: 0px 0px 4px rgb(204, 204, 204); border-color: rgb(221, 221, 221); border-width: 0px;';
					
	
					$bc_settings = json_decode($this->multistep_settings,true);
					
					if(!$bc_settings)
						$bc_settings = json_decode(strip_tags($this->multistep_settings),true);
						
					
					
								
					$bc_type 				= (isset($bc_settings['0']['breadcrumb_type'])) ? $bc_settings['0']['breadcrumb_type'] 	: 'pilled';
					$bc_text_pos 			= (isset($bc_settings['0']['text_pos'])) ? $bc_settings['0']['text_pos'] 					: 'text-bottom';	
					$bc_data_theme 			= (isset($bc_settings['0']['data_theme'])) ? $bc_settings['0']['data_theme'] 				: 'default';
					$bc_show_front_end 		= (isset($bc_settings['0']['show_front_end'])) ? $bc_settings['0']['show_front_end'] 		: 'yes';	
					$bc_show_inside 		= (isset($bc_settings['0']['show_inside'])) ? $bc_settings['0']['show_inside'] 			: 'no';	
					$scroll_to_top 			= (isset($bc_settings['0']['scroll_to_top'])) ? $bc_settings['0']['scroll_to_top'] 		: 'yes';
					
					$form_width_percentage 	= (isset($bc_settings['0']['form_width_percentage'])) ? $bc_settings['0']['form_width_percentage'] : '100';
					$form_width_pixels	 	= (isset($bc_settings['0']['form_width_pixels'])) ? $bc_settings['0']['form_width_pixels'] : '950';
					$form_width_unit	 	= (isset($bc_settings['0']['form_width_unit'])) ? $bc_settings['0']['form_width_unit'] 	: '%';
					
					
					$msg_width_percentage 	= (isset($bc_settings['0']['msg_width_percentage'])) ? $bc_settings['0']['msg_width_percentage'] : '100';
					$msg_width_pixels	 	= (isset($bc_settings['0']['msg_width_pixels'])) ? $bc_settings['0']['msg_width_pixels'] : '950';
					$msg_width_unit	 		= (isset($bc_settings['0']['msg_width_unit'])) ? $bc_settings['0']['msg_width_unit'] 	: '%';
					
					$align_crumb	 		= (isset($bc_settings['0']['crumb_align'])) ? $bc_settings['0']['crumb_align'] 			: 'align_left';
					$bc_list	 			= (isset($bc_settings['0']['breadcrumb_list'])) ? $bc_settings['0']['breadcrumb_list'] 	: '';
					$bc_position	 		= (isset($bc_settings['0']['bc_position'])) ? $bc_settings['0']['bc_position'] 			: 'top';
					$bc_gutter	 			= (isset($bc_settings['0']['bc_gutter']) ) ? $bc_settings['0']['bc_gutter'] : '20';
					$bc_folded 				= (isset($bc_settings['0']['bc_folded'])) ? $bc_settings['0']['bc_folded'] 				: 'bc-unfolded';
					$bc_connected 			= (isset($bc_settings['0']['bc_connected'])) ? $bc_settings['0']['bc_connected'] 			: 'bc-connected';
					$bc_style 				= (isset($bc_settings['0']['bc_style'])) ? $bc_settings['0']['bc_style'] 					: 'bc-solid';
					$bc_css 				= (isset($bc_settings['0']['bc_css'])) ? $bc_settings['0']['bc_css'] 						: '';
					
					
					
					if($bc_type =='dotted' || $bc_type =='dotted_count')
							$bc_type = 'pilled';
					
					if($bc_type == 'basic' || $bc_type =='basix')
							$bc_type = 'pilled';
					
					/*$output .= '<div class="nf_step_breadcrumb '.(($bc_show_front_end=='yes') ? '' : 'no-front-end').'" style="display:none;">
									<ol class="the_br '.(($bc_type=='p_bar') ? 'hidden' : '').' '.$align_crumb.' '.$bc_type.' '.(($bc_type=='basix' || $bc_type=='triangular') ? 'cd-breadcrumb' : 'cd-multi-steps').'  '.(($bc_type=='rectangular') ? 'text-center' : '').' '.(($bc_type=='triangular') ? 'triangle' : '').' '.(($bc_type=='dotted_count') ? 'count' : '').' '.(($bc_type=='dotted' || $bc_type=='dotted_count') ? $bc_text_pos : '').' md-color-'.$bc_data_theme.'" data-align-crumb="'.$align_crumb.'" data-text-pos="'.$bc_text_pos.'" data-breadcrumb-type="'.$bc_type.'" data-theme="'.$bc_data_theme.'" data-show-front-end="'.$bc_show_front_end.'" data-show-inside="'.$bc_show_inside.'">
									
									'.wp_unslash($bc_list).'
									
									</ol>
									
									<div class="nf_progressbar the_br" '.(($bc_type!='p_bar') ? 'style="display:none;"' : '').'  data-text-pos="'.$bc_text_pos.'" data-breadcrumb-type="'.$bc_type.'" data-theme="'.$bc_data_theme.'" data-show-front-end="'.$bc_show_front_end.'" data-show-inside="'.$bc_show_inside.'">
										<div class="nf_progressbar_percentage md-color-'.$bc_data_theme.'" style="width:5%;">
											<span>0%</span>
										</div>
									</div>
									
								</div>';*/
								
					
					
					
					
				
					$output .= '<div class="nf_step_scroll_top hidden">'.$scroll_to_top.'</div>';
					$output .= '<div class="nf_bc_position hidden">'.$bc_position.'</div>';
				
				
				
				$output .= '<div class="con_logic_ui">';
				
					$db_actions = new NEXForms_Database_Actions();
				
					$output .= $db_actions->get_c_logic_ui($this->form_Id);
				$output .= '</div>';
				
				$bc_settings = json_decode($this->multistep_settings,true);
				$multi_step_back_disabled = (isset($bc_settings['0']['multi_step_back_disabled'])) ? $bc_settings['0']['multi_step_back_disabled'] 	: 'no';
				$ms_back_disabled_class = ($multi_step_back_disabled=='yes') ? 'ms_disable_back' : '';				
		
				
				$breadcrumb = '<div class="bc-outer-container hide_on_load '.(($bc_show_front_end=='yes') ? '' : 'no-front-end').'" style="display:none;">
									<div class="bc_settings">
											<div class="edit_breadcrumb" data-title="Edit Breadcrumb" data-toggle="tooltip_bs" data-placement="top">
												<span class="label-text">'.__('Edit Breadcrumb','nex-forms').'</span><span class="fa fa-edit"></span>
											</div>
										</div>
									
									<div class="nf_ms_breadcrumb  bc-gutter-'.$bc_gutter.' bc-'.$bc_position.' '.$bc_style.' '.$bc_connected.' '.$bc_folded.' '.$bc_type.'" style="'.$bc_css.'">
										<ul class="the_br '.(($bc_type=='p_bar') ? 'hidden' : '').' '.$align_crumb.' '.$bc_type.'  md-color-'.$bc_data_theme.'" data-align-crumb="'.$align_crumb.'" data-text-pos="'.$bc_text_pos.'" data-breadcrumb-type="'.$bc_type.'" data-theme="'.$bc_data_theme.'" data-show-front-end="'.$bc_show_front_end.'" data-show-inside="'.$bc_show_inside.'">
										'.((isset($multistep_settings[0]['bc_position'])) ? $multistep_settings[0]['bc_position'] : '' ).wp_unslash($bc_list).'
										</ul>
										<div class="nf_progressbar the_br" '.(($bc_type!='p_bar') ? 'style="display:none;"' : '').'  data-text-pos="'.$bc_text_pos.'" data-breadcrumb-type="'.$bc_type.'" data-theme="'.$bc_data_theme.'" data-show-front-end="'.$bc_show_front_end.'" data-show-inside="'.$bc_show_inside.'">
											<div class="nf_progressbar_percentage md-color-'.$bc_data_theme.'" style="width:5%;">
												<span>0%</span>
											</div>
										</div>
										
										<div style="clear:both"></div>
									</div>
								</div>';
				
				
				$timer_add_to			= (isset($bc_settings['0']['timer_add_to'])) ? $bc_settings['0']['timer_add_to'] 				: 'header';
				$timer_position			= (isset($bc_settings['0']['timer_position'])) ? $bc_settings['0']['timer_position'] 			: 'timer_inline';
				$timer_align			= (isset($bc_settings['0']['timer_align'])) ? $bc_settings['0']['timer_align'] 				: 'timer_right';
				
				$add_timer				= (isset($bc_settings['0']['add_timer'])) ? $bc_settings['0']['add_timer'] 					: 'no';
				$timer_type				= (isset($bc_settings['0']['timer_type'])) ? $bc_settings['0']['timer_type'] 					: 'overall';
				
				$timer_size				= (isset($bc_settings['0']['timer_size'])) ? $bc_settings['0']['timer_size'] 					: 'small';
				$timer_animation		= (isset($bc_settings['0']['timer_animation'])) ? $bc_settings['0']['timer_animation'] 		: 'smooth';
				
				
				
				$enabled_units			= (isset($bc_settings['0']['enabled_units'])) ? $bc_settings['0']['enabled_units'] 			: 'minutes,seconds';
				
				$timer_hours			= (isset($bc_settings['0']['timer_hours'])) ? $bc_settings['0']['timer_hours'] 				: 0;
				$timer_minutes			= (isset($bc_settings['0']['timer_minutes'])) ? $bc_settings['0']['timer_minutes'] 			: 0;
				$timer_seconds			= (isset($bc_settings['0']['timer_seconds'])) ? $bc_settings['0']['timer_seconds'] 			: 30;
				
				$timer_hours_label		= (isset($bc_settings['0']['timer_hours_label'])) ? $bc_settings['0']['timer_hours_label'] 		: '';
				$timer_minutes_label	= (isset($bc_settings['0']['timer_minutes_label'])) ? $bc_settings['0']['timer_minutes_label'] 	: '';
				$timer_seconds_label	= (isset($bc_settings['0']['timer_seconds_label'])) ? $bc_settings['0']['timer_seconds_label'] 	: '';
				
				$timer_hours_color			= (isset($bc_settings['0']['timer_hours_color'])) ? $bc_settings['0']['timer_hours_color'] 				: '#2979FF';
				$timer_minutes_color		= (isset($bc_settings['0']['timer_minutes_color'])) ? $bc_settings['0']['timer_minutes_color'] 			: '#00bcd4';
				$timer_seconds_color		= (isset($bc_settings['0']['timer_seconds_color'])) ? $bc_settings['0']['timer_seconds_color'] 			: '#40C4FF';
				
				$timer_direction		= (isset($bc_settings['0']['timer_direction'])) ? $bc_settings['0']['timer_direction'] 			: 'clockwise';
				
				$timer_wrapper_css		= (isset($bc_settings['0']['timer_wrapper_css'])) ? $bc_settings['0']['timer_wrapper_css'] 		: '';
				
				
				$timer_text_color				= (isset($bc_settings['0']['timer_text_color'])) ? $bc_settings['0']['timer_text_color'] 						: '#888';
				$timer_inner_circle_color		= (isset($bc_settings['0']['timer_inner_circle_color'])) ? $bc_settings['0']['timer_inner_circle_color'] 		: '#aaa';
				
				$timer_bg_width			= (isset($bc_settings['0']['timer_bg_width'])) ? $bc_settings['0']['timer_bg_width'] 	: '0.1';
				$timer_fg_width			= (isset($bc_settings['0']['timer_fg_width'])) ? $bc_settings['0']['timer_fg_width'] 	: '0.05';
				
				$timer_output = '
					<div class="timer-outer-container hide_on_load '.(($add_timer=='yes') ? '' : 'no-front-end').'">
						<div class="timer_settings">
							<div class="edit_timer" data-title="Edit Timer" data-toggle="tooltip_bs" data-placement="top">
								<span class="label-text">'.__('Edit Timer','nex-forms').'</span><span class="fa fa-edit"></span>
							</div>
						</div>
						
						<div class="timer-inner-container" style="'.$timer_wrapper_css.'">
							<div class="nf-timer '.$timer_size.'" data-timer-bg-width="'.$timer_bg_width.'" data-timer-fg-width="'.$timer_fg_width.'" data-timer-text-color="'.$timer_text_color.'" data-timer-inner-circle-color="'.$timer_inner_circle_color.'" data-timer-animation="'.$timer_animation.'" data-timer="30" data-timer-direction="'.$timer_direction.'" data-enabled-units="'.$enabled_units.'"  data-timer-hours="'.$timer_hours.'" data-timer-minutes="'.$timer_minutes.'" data-timer-seconds="'.$timer_seconds.'" data-timer-hours-label="'.$timer_hours_label.'" data-timer-minutes-label="'.$timer_minutes_label.'" data-timer-seconds-label="'.$timer_seconds_label.'" data-timer-hours-color="'.$timer_hours_color.'" data-timer-minutes-color="'.$timer_minutes_color.'" data-timer-seconds-color="'.$timer_seconds_color.'"></div>
						</div>
					</div>';

$success_style = wp_unslash($this->msg_style);
$success_style = str_replace('"','\'',$success_style);

$success_style = ($this->msg_style) ? $success_style : "background: #fff; box-shadow: rgba(0, 0, 0, 0.2) 0px 7px 16px 0px; border-radius: 4px; padding: 30px; border-color:#ddd;";
					

$output .= '<div class="inner-msg-canvas" style="display:none;" >';
	
	$output .= '<div class="outer-container">';
		
		/*$output .= '<pre>';
$output .= $preferences['other_preferences']['pref_other_on_screen_message'];
$output .= '</pre>';*/
		$set_success_msg = ($this->on_screen_confirmation_message) ? wp_unslash($this->on_screen_confirmation_message) : wp_unslash($preferences['other_preferences']['pref_other_on_screen_message']);
		$set_success_msg = trim($set_success_msg);
		
		
		
		$output .= '<div class="inner-canvas-container panel-body nex-forms-msg-container" style="'.$success_style.'" data-width-percentage="'.$msg_width_percentage.'" data-width-pixels="'.$msg_width_pixels.'" data-width-unit="'.$msg_width_unit.'">';
			
			if($this->on_screen_confirmation_message_admin)
				{
				$output .= wp_unslash($this->on_screen_confirmation_message_admin);	
				}
			else
				{
				$output .= '<div class="form_field all_fields icon html_fields  ui-draggable ui-draggable-handle dropped wow bounceInUp " data-settings=".s-icon" data-settings-tabs="#input-settings, #animation-settings" style="margin-bottom: 15px;" id="_21501" data-animation-name="bounceInUp">
							  <div class="edit_mask"></div>
							  <div id="form_object" class="form_object" style="">
								<div class="icon_container_wrapper icon_center">
								  <div class="icon_container" id="field_container" style="border-width: 0px; background-color: transparent;">
									<div class="">
									  <div class="input_container">
										<div class="the_input_element icon_element" style="font-size: 50px; color: rgb(139, 195, 74);"><span class="fas fa-check-circle"></span></div>
									  </div>
									</div>
								  </div>
								  <div class="field_settings">
									<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>
									<div class="btn btn-default btn-xs edit" title="Edit Field Attributes"><i class="fa fa-edit"></i></div>
									<div class="btn btn-default btn-xs duplicate_field" title="Duplicate Field"><i class="fa fa-files-o"></i></div>
									<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>
								  </div>
								</div>
							  </div>
							</div>
							<div class="form_field all_fields html html_fields  ui-draggable ui-draggable-handle dropped ui-sortable-handle  wow fadeInDown" data-settings=".s-html" data-settings-tabs="#input-settings, #animation-settings, #extra-settings, #math-settings" style="margin-bottom: 15px;" data-animation-name="fadeInDown" data-wow-delay="0.5s">
							  <div class="edit_mask"></div>
							  <div id="form_object" class="form_object" style="">
								<div class="row">
								  <div class="col-sm-12" id="field_container">
									<div class="row">
									  <div class="col-sm-12 input_container">
										<div class="the_input_element align_center" data-math-equation="" data-original-math-equation="" data-decimal-places="0">'.$set_success_msg.'</div>
										<div style="clear:both;"></div>
									  </div>
									</div>
								  </div>
								  <div class="field_settings">
									<div class="btn btn-default btn-xs move_field"><i class="fa fa-arrows"></i></div>
									<div class="btn btn-default btn-xs edit" title="Edit Field Attributes"><i class="fa fa-edit"></i></div>
									<div class="btn btn-default btn-xs duplicate_field" title="Duplicate Field"><i class="fa fa-files-o"></i></div>
									<div class="btn btn-default btn-xs delete" title="Delete field"><i class="fa fa-close"></i></div>
								  </div>
								</div>
							  </div>
							</div>';
				}
			
			
		$output .= '</div>';
		
	
	$output .= '</div>';
	
	
$output .= '</div>';

//INNER FORM CANVAS				
$output .= '<div class="inner-form-canvas">';
				$output .= '<div class="nex-forms-header-footer  nex-forms-header '.$timer_position.' '.$timer_align.'">';
					
					if($bc_position=='top')
						$output .= $breadcrumb;
					
					if($timer_add_to=='header')
						$output .= $timer_output;
						
				$output .= '</div>';
				
				$output .= '<div class="nf-loader-container form_loader" data-type="ellipsis"><div class="nf-loader-type nf-loader-lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>';
				
				if($this->form_theme=='bootstrap' || $this->form_theme=='jquery_ui')
					$set_theme = $this->jq_theme;
					
					
				$output .= '<div class="outer-container '.(($add_timer=='yes') ? 'has_time_limit timer_'.$timer_type : '' ).' '.$ms_back_disabled_class.' theme-'.$set_theme.'" style="display:none;">';
					
					
					$output .= '<div class="form_settings">';
							/*$output .= '<a class="conditional-logic-btn btn"><span title="'.__('Conditional Logic','nex-forms').'" class="fa fa-random" data-toggle="tooltip_bs2" data-placement="top"></span></a>';
							$output .= '<a class="overall-styling-btn btn"><span title="'.__('Overall Form Styling','nex-forms').'" class="fa dashicons-before dashicons-admin-appearance" data-toggle="tooltip_bs2" data-placement="top"></span></a>';
						*/$output .= '</div>';
					
					$output .= '<div id="droppable-container" class="active nex-forms-container panel-body inner-canvas-container  ui-nex-forms-container ui-nex-forms-container-admin  '.$set_form_theme.'" data-form-theme="'.$set_form_theme.'" data-width-percentage="'.$form_width_percentage.'" data-width-pixels="'.$form_width_pixels.'" data-width-unit="'.$form_width_unit.'" style="'.$form_style.'">';
						
						
						
						if(!strstr($this->admin_html,'ms_current_step'))
							{
							$output .= '
							<div class="form_field hidden" id="_ms_current_step">
								<input class="ms_current_step" value="1" name="ms_current_step" type="hidden">
							</div>
							';
							}
						//$admin_html = str_replace('<div class="change_image">','',$this->admin_html);
						$output .= $this->admin_html;
						
					$output .= '</div>';

					
				$output .= '</div>';
					
				$output .= '<div class="nex-forms-header-footer nex-forms-footer '.$timer_position.' '.$timer_align.'">';	
					
					if($bc_position=='bottom')
						$output .= $breadcrumb;
						
					if($timer_add_to=='footer')
						$output .= $timer_output;
				
				$output .= '</div>';
				
	//INNER FORM CANVAS			
	$output .= '</div>';
				
			$output .= '</div>';
				
				
				
				$output .= '<div class="preview_canvas">';
					
					$output .= '<div class="preview_settings aa_bg_main">';
						$output .= '<div class="form_preview_loader page_load aa_bg_main">
											<div class="preloader-wrapper small active">
												<div class="spinner-layer spinner-blue-only">
													<div class="circle-clipper left">
														<div class="circle"></div>
													</div>
													<div class="gap-patch">
														<div class="circle"></div>
													</div>
													<div class="circle-clipper right">
														<div class="circle"></div>
													</div>
												</div>
											</div>
											<h4>'.__('','nex-forms').'</h4>
									  </div>';
						$output .= '<div class="resposive_tests">
											<span class="close-preview fa fa-close" title="'.__('Close Preview','nex-forms').'"></span>
											
											<i class="laptop fas fa-expand active"></i>
											<i class="tablet fa fa-tablet"></i>
											<i class="phone fa fa-mobile"></i>
											<span class="refresh-preview fa fa-refresh" data-toggle="" data-placement="bottom" title="'.__('Refresh Preview','nex-forms').'"></span>
										</div>
						';
					
						
					$output .= '</div>';
					$output .= '<iframe class="show_form_preview" src=""></iframe>';
				$output .= '</div>';
				
				$output .= '<div class="material_box settings-column-style  conditional_logic_wrapper conditional_logic simple_view">';
					$output .= '<div class="material_box_head aa_bg_main">';
						$output .= '<span class="fa fa-random"></span> '.__('Conditional Logic','nex-forms').' ';
					$output .= '</div>
					<div id="close-settings" class="close-area">
						<span class="fa fa-close"></span>
					</div>
					';
					
					$output .= '<div class="cl-tools-container">';
					
					
						$output .= '
						<div class="advanced_cl_options"><input name="adv_cl" id="adv_cl" value="1" type="checkbox"><label for="adv_cl">'.__('Advanced Options','nex-forms').'</label> </div>
						<button class="button btn btn-default add_new_rule cl-tool-btn"><span class="fa fa-plus"></span>&nbsp;<span class="btn-tx">'.__('Add Rule','nex-forms').'</span></button>
						<button class="button btn btn-default refresh_cl_fields cl-tool-btn"><span class="fa fa-refresh"></span>&nbsp;<span class="btn-tx">'.__('Refresh Fields','nex-forms').'</span></button>
						
						';
					
					$output .= '</div>';
					
					$output .= '<div class="inner">';					
							$set_code = true;
						if(!get_option('nf_activated'))
							$set_code = false;
						
						if(!$set_code)
								$output .= '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For Conditional Logic to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
							
						
						$output .= '<div class="con-logic-column con_col">';
							
						
						
							
							$db_actions = new NEXForms_Database_Actions();
							$nf_functions = new NEXForms_Functions();
							
								//if($nf_functions->isJson($this->conditional_logic_array) && !empty($form_attr->conditional_logic_array))
								//	{
									//$output .= $db_actions->load_conditional_logic_array($this->form_Id);
								//	}
								//else
								//	{
									$output .= $db_actions->load_conditional_logic($this->form_Id);
								//	}
						$output .= '</div>';
					
					$output .= '<div class="setting-buffer"></div>';
								
					$output .= '</div>';
				$output .= '</div>';
				
				
				
				$output .= $builder->print_overall_settings();
				$output .= $builder->print_field_settings();
				
				
				
			$output .= '</div>';
			
			$output .= '<div class="builder-footer">';
			
			$output .= '
			'.(($theme->Name=='NEX-Forms Demo') ? '<!--<a href="https://1.envato.market/zQ6de" target="_blank"  style="padding:7px !important;" class="btn waves-effect-nf waves-light-nf upgrade_pro animated fadeInRight">BUY NEX-FORMS</a>-->' : '' ).'
			'.__('NEX-Forms version:','nex-forms').' '. $this->plugin_version.'';
			
			$output .= '</div>';

			return $output;
		}
		
		public function print_email_setup(){
			
			
			$preferences = get_option('nex-forms-preferences');
			$attach_to_email = json_decode($this->attachment_settings, true);
			$option_settings = json_decode($this->option_settings,true);
			$set_code = true;
			if(!get_option('nf_activated'))
				$set_code = false;
			
			echo '<div class="form_attr_wrapper">';
				
				echo '<div class="navigation" style="display:none;">
							<div class="nav-content">
								<ul class="tabs_nf tri-menu" >
									<li class="tab admin_email_tab"><a class="active" href="#admin_email">'.__('Admin (Email Notifications)','nex-forms').'</a></li>
									<li class="tab user_email_tab"><a class="user_email_tab" href="#user_email">'.__('User (Autoresponder)','nex-forms').'</a></li>
								</ul>
							</div>
						</div>';
					echo '<div class="form_attr_left_menu aa_bg_sec aa_menu">';
					echo '<ul>';
						echo '<li class="active"><a class="show-admin-email-setup" ><span class="fa fa-user-plus"></span> <span class="sidemenu_text">'.__('Admin Emails','nex-forms').'</span></a></li>';
						echo '<li><a class="show-user-email-setup" ><span class="fa fa-user"></span> <span class="sidemenu_text">'.__('User Emails','nex-forms').'</span></a></li>';
					echo '</ul>';
				echo '</div>';//navigation

				echo '<div class="form_attr_settings_wrapper">';
					
					echo '<div id="admin_email">';
							
						$send_admin_email 	= (isset($option_settings['0']['send_admin_email'])) ? $option_settings['0']['send_admin_email'] 	: 'true';
						
						if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For these settings to take effect and to receive Emails you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
				
						
						echo '<div class="row">';
							echo '<div class="integration_form_label">'.__('Send Admin Email','nex-forms').'</div>';
							echo '<div class="integration_form_field no_input tut_admin_email_7">';
									echo  '<input class="with-gap" name="send_admin_email" '.((!$send_admin_email || $send_admin_email=='true') ? 'checked="checked"' : '' ).' id="send_admin_email_yes" value="true" type="radio">
										   <label for="send_admin_email_yes">'.__('Yes','nex-forms').'</label>
											<input class="with-gap" name="send_admin_email" '.(($send_admin_email =='false') ? 'checked="checked"' : '' ).' id="send_admin_email_no" value="false" type="radio">
											<label for="send_admin_email_no">'.__('No','nex-forms').'</label>
											';
							echo '</div>';
						echo '</div>';
						
						echo '<div class="row">';
							echo '<div class="integration_form_label">'.__('From Address','nex-forms').'</div>';
							echo '<div class="integration_form_field tut_admin_email_1">';
								echo '<input type="text" class="form-control" name="nex_autoresponder_from_address" id="nex_autoresponder_from_address"  placeholder="'.__('Enter From Address','nex-forms').'" value="'.(($this->from_address) ? wp_unslash($this->from_address) : $preferences['email_preferences']['pref_email_from_address']).'">';
							echo '</div>';
						echo '</div>';
						
						echo '<div class="row">';
							echo '<div class="integration_form_label">'.__('From Name','nex-forms').'</div>';
							echo '<div class="integration_form_field tut_admin_email_2">';
								echo '<input type="text" class="form-control" name="nex_autoresponder_from_name" id="nex_autoresponder_from_name"  placeholder="'.__('Enter From Name','nex-forms').'"  value="'.(($this->from_name) ? wp_unslash($this->from_name) : $preferences['email_preferences']['pref_email_from_name']).'">';
							echo '</div>';
						echo '</div>';
						
						echo '<div class="row">';
							echo '<div class="integration_form_label">'.__('Recipients','nex-forms').'</div>';
							echo '<div class="integration_form_field tut_admin_email_3">';
								echo '<input type="text" class="form-control" name="nex_autoresponder_recipients" id="nex_autoresponder_recipients"  placeholder="'.__('Example: email@domain.com, email2@domain.com','nex-forms').'" value="'.(($this->mail_to) ? wp_unslash(str_replace('"','\'',$this->mail_to)) : $preferences['email_preferences']['pref_email_recipients']).'">';
							echo '</div>';
						echo '</div>';
						
						echo '<div class="row">';
							echo '<div class="integration_form_label">'.__('BCC','nex-forms').'</div>';
							echo '<div class="integration_form_field tut_admin_email_4">';
								echo '<input type="text" class="form-control" name="nex_admin_bcc_recipients" id="nex_admin_bcc_recipients"  placeholder="'.__('Example: email@domain.com, email2@domain.com','nex-forms').'" value="'.(($this->bcc) ? wp_unslash($this->bcc) : '').'" >';
							echo '</div>';
						echo '</div>';
						
						
						echo '<div class="row">';
							echo '<div class="integration_form_label">'.__('Reply-To','nex-forms').'</div>';
							echo '<div class="integration_form_field tut_admin_email_4">';
								echo '<input type="text" class="form-control" name="nex_admin_reply_to" id="nex_admin_reply_to"  placeholder="'.__('Example: email@domain.com, email2@domain.com','nex-forms').'" value="'.(($this->reply_to) ? wp_unslash($this->reply_to) : '').'" >';
							echo '</div>';
						echo '</div>';
						
						
						echo '<div class="row">';
							echo '<div class="integration_form_label">'.__('Subject','nex-forms').'</div>';
							echo '<div class="integration_form_field tut_admin_email_5">';
								echo '<input type="text" class="form-control" name="nex_autoresponder_confirmation_mail_subject" id="nex_autoresponder_confirmation_mail_subject"  placeholder="'.__('Enter Email Subject','nex-forms').'" value="'.(($this->confirmation_mail_subject) ? wp_unslash($this->confirmation_mail_subject) : $preferences['email_preferences']['pref_email_subject']).'">';
							echo '</div>';
						echo '</div>';
						
						
						$attach_to_admin_email 	= (isset($attach_to_email['0']['attach_to_admin_email'])) ? $attach_to_email['0']['attach_to_admin_email'] 	: 'true';
						
						echo '<div class="row last">';
							echo '<div class="integration_form_label">'.__('Attach uploaded Files?','nex-forms').'</div>';
							echo '<div class="integration_form_field no_input tut_admin_email_6">';
									echo  '<input class="with-gap" name="attach_to_admin_email" '.((!$attach_to_admin_email || $attach_to_admin_email=='true') ? 'checked="checked"' : '' ).' id="attach_to_admin_email_yes" value="true" type="radio">
										   <label for="attach_to_admin_email_yes">'.__('Yes','nex-forms').'</label>
											<input class="with-gap" name="attach_to_admin_email" '.(($attach_to_admin_email =='false') ? 'checked="checked"' : '' ).' id="attach_to_admin_email_no" value="false" type="radio">
											<label for="attach_to_admin_email_no">'.__('No','nex-forms').'</label>
											';
								
							echo '</div>';
						echo '</div>';
						
						
						
						
						
						
						echo '<div class="row">';
							echo '<div class="editor_wrapper">';
								wp_editor( (($this->admin_email_body) ? wp_unslash($this->admin_email_body) : $preferences['email_preferences']['pref_email_body']), 'admin_email_body_content', array('media_buttons'=>true));
							echo '</div>';
						echo '</div>';
								
						
					echo '</div>';//admin_email
				
					
					echo '<div id="user_email">';
					
						if(!$set_code)
									echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For these settings to take effect and to send USER Emails you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
									echo  '<div class="row">';
										echo  '<div class="integration_form_label">'.__('Recipients (map email field)','nex-forms').'</div>';
										echo  '<div class="integration_form_field tut_user_email_1">';
											echo  '<select class="form-control posible_email_fields" data-selected="'.$this->user_email_field.'" id="nex_autoresponder_user_email_field" name="posible_email_fields"><option value="">'.__('Dont send confirmation mail to user','nex-forms').'</option></select>';
										echo  '</div>';
									echo  '</div>';
									
									echo  '<div class="row">';
										echo  '<div class="integration_form_label">'.__('BCC','nex-forms').'</div>';
										echo  '<div class="integration_form_field tut_user_email_2">';
											echo  '<input type="text" class="form-control" name="nex_autoresponder_bcc_recipients" id="nex_autoresponder_bcc_recipients"  placeholder="'.__('Example: email@domain.com, email2@domain.com','nex-forms').'" value="'.(($this->bcc_user_mail) ? $this->bcc_user_mail : '').'" >';
										echo  '</div>';
									echo  '</div>';
									
									echo  '<div class="row last">';
										echo  '<div class="integration_form_label">'.__('Subject','nex-forms').'</div>';
										echo  '<div class="integration_form_field tut_user_email_3">';
											echo  '<input type="text" class="form-control" name="nex_autoresponder_user_confirmation_mail_subject" id="nex_autoresponder_user_confirmation_mail_subject"  placeholder="'.__('Enter Email Subject','nex-forms').'" value="'.(($this->user_confirmation_mail_subject) ? wp_unslash($this->user_confirmation_mail_subject) :  $preferences['email_preferences']['pref_user_email_subject']).'">';
										echo  '</div>';
									echo  '</div>';
									
									echo '<div class="row">';
										echo '<div class="editor_wrapper">';
											wp_editor( (($this->confirmation_mail_body) ? wp_unslash($this->confirmation_mail_body) : $preferences['email_preferences']['pref_user_email_body']), 'user_email_body_content');
										echo '</div>';
									echo '</div>';
				echo '</div>';//user_email
			echo '</div>';	//form_attr_settings_wrapper
				
		echo '</div>';//form_attr_wrapper
					
		}
	
	public function print_options_setup(){
			
		$preferences = get_option('nex-forms-preferences');
			
		$upload_settings = json_decode($this->upload_settings,true);
		$option_settings = json_decode($this->option_settings,true);
		
		
			
		echo '<div class="form_attr_wrapper ">';
			
			
			
			echo '	<div class="navigation"  style="display:none;"><div class="nav-content">
									<ul class="tabs_nf tri-menu" >
										<li class="tab on_submission_options_tab"><a class="active" href="#on_submission_settings">'.__('On Submit','nex-forms').'</a></li>
										<li class="tab file_uploads_options_tab"><a href="#file_upload_settings">'.__('File Uploads','nex-forms').'</a></li>
										<li class="tab hidden_fields_options_tab"><a href="#saved_hidden_fields">'.__('File Uploads','nex-forms').'</a></li>
									</ul>
								</div></div>';
			
			echo '<div class="form_attr_left_menu aa_bg_sec aa_menu">';
				echo '<ul>';
					echo '<li class="active"><a class="show_on_submission_options"><span class="fa fa fa-send"></span> <span class="sidemenu_text">'.__('On Submit','nex-forms').'</span></a></li>';
					echo '<li><a class="show_hidden_fields"><span class="fa fa-eye-slash"></span> <span class="sidemenu_text">'.__('Hidden Fields','nex-forms').'</span></a></li>';
					echo '<li><a class="show_file_uploads_options"><span class="fa fa-cog"></span> <span class="sidemenu_text">'.__('Other','nex-forms').'</span></a></li>';
				echo '</ul>';
				echo '</div>';
				
						$set_code = true;
						if(!get_option('nf_activated'))
							$set_code = false;
						
						echo '<div class="form_attr_settings_wrapper">';
							
							
							echo '<div id="on_submission_settings" >';
							
							if(!$set_code)
								echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For these settings to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
							
							
							echo  '<div class="row">';
								echo  '<div class="integration_form_label ">'.__('Submission Type','nex-forms').'</div>'; 
								echo  '<div class="integration_form_field no_input tour_form_submit_setup_1">';
									echo  '<input class="with-gap" name="form_post_action" '.((!$this->post_action || $this->post_action=='ajax') ? 'checked="checked"' : '' ).' id="post_action_ajax" value="ajax" type="radio">
											<label for="post_action_ajax">'.__('AJAX (default)','nex-forms').'</label>
											
											<input class="with-gap" '.(($set_code) ? '' : 'disabled="disabled"').' name="form_post_action" '.(($this->post_action =='custom') ? 'checked="checked"' : '' ).' id="post_action_custom" value="custom" type="radio">
											<label for="post_action_custom">'.__('Custom (For developers)','nex-forms').'</label>';
								echo  '</div>';
							echo  '</div>';	
							
							
							
							echo  '<div class="row submit_ajax_options '.((!$this->post_action || $this->post_action=='ajax') ? '' : 'hidden' ).'">';
								echo  '<div class="integration_form_label ">'.__('After Form Submission','nex-forms').'</div>'; 
								echo  '<div class="integration_form_field no_input tour_form_submit_setup_2">';
									echo  '<input class="with-gap" name="on_form_submission" '.((!$this->on_form_submission || $this->on_form_submission=='message') ? 'checked="checked"' : '' ).' id="on_form_submission_message" value="message" type="radio">
											<label for="on_form_submission_message">'.__('Show Message','nex-forms').'</label>
											
											<input class="with-gap" '.(($set_code) ? '' : 'disabled="disabled"').' name="on_form_submission" '.(($this->on_form_submission =='redirect') ? 'checked="checked"' : '' ).' id="on_form_submission_redirect" value="redirect" type="radio">
											<label for="on_form_submission_redirect">'.__('Redirect to URL','nex-forms').'</label>';
								echo  '</div>';
							echo  '</div>';
							
							echo  '<div class="row on_submit_redirect '.(($this->on_form_submission =='redirect') ? '' : 'hidden' ).'">';
								echo  '<div class="integration_form_label">'.__('Redirect to','nex-forms').'</div>';
								echo  '<div class="integration_form_field">';
									echo  '<input type="text" class="form-control" name="confirmation_page" id="nex_autoresponder_confirmation_page"  placeholder="'.__('Enter Custom URL','nex-forms').'" value="'.(($this->confirmation_page) ? $this->confirmation_page : '').'" >';
								echo  '</div>';
							echo  '</div>';	
							
							
							echo  '<div class="row submit_custom_options  '.((!$this->post_action || $this->post_action=='ajax') ? 'hidden' : '' ).'">';
								echo  '<div class="integration_form_label">'.__('Post Method','nex-forms').'</div>';
								echo  '<div class="integration_form_field no_input">';
									echo  '<input class="with-gap" name="form_post_method" '.((!$this->post_type || $this->post_type=='POST') ? 'checked="checked"' : '' ).' id="form_post_method_post" value="POST" type="radio">
											<label for="form_post_method_post">POST</label>
											
											<input class="with-gap" name="form_post_method" '.(($this->post_type =='GET') ? 'checked="checked"' : '' ).' id="form_post_method_get" value="GET" type="radio">
											<label for="form_post_method_get">GET</label>';
								echo  '</div>';
							echo  '</div>';	
							echo  '<div class="row submit_custom_options '.((!$this->post_action || $this->post_action=='ajax') ? 'hidden' : '' ).'">';
									echo  '<div class="integration_form_label">'.__('Submit Form To','nex-forms').'</div>';
									echo  '<div class="integration_form_field">';
										echo  '<input type="text" class="form-control" name="custum_url" id="on_form_submission_custum_url"  placeholder="'.__('Enter Custom URL','nex-forms').'" value="'.(($this->custom_url) ? $this->custom_url : '').'" >';
									echo  '</div>';
							echo  '</div>';	
							
							
							
							/*echo  '<div class="row on_submit_show_message">';
								echo  '<div class="integration_form_label">'.__('On-screen message','nex-forms').'</div>';
								echo  '<div class="integration_form_field no_input" style="background:#f2f8fd; border-left:none"></div>';
							echo  '</div>';
							*/
							
							//echo '<div class="row on_submit_show_message '.(((!$this->on_form_submission || $this->on_form_submission=='message') && $this->post_action!='custom') ? '' : 'hidden' ).'">';
								
								
								/*echo '<div class="editor_wrapper">';	
									wp_editor( (($this->on_screen_confirmation_message) ? wp_unslash($this->on_screen_confirmation_message) : $preferences['other_preferences']['pref_other_on_screen_message'] ), 'on_screen_message');
								echo '</div>';*/
							//echo '</div>';
							
										$save_to_db = '1';
										if(isset($option_settings[0]['save_to_db']))
											{
											if($option_settings[0]['save_to_db']=='0')
												$save_to_db = '0';
											}
										
										echo  '<div class="row">';
												echo  '<div class="integration_form_label ">'.__('Save Form Data to Database','nex-forms').'</div>'; 
												echo  '<div class="integration_form_field no_input tour_form_submit_setup_1">';
													echo  '<input class="with-gap" name="save_to_db" '.(($save_to_db) ? 'checked="checked"' : '' ).' id="save_to_db_1" value="1" type="radio">
															<label for="save_to_db_1">'.__('Yes','nex-forms').'</label>
															
															<input class="with-gap" name="save_to_db" '.(($save_to_db=='0') ? 'checked="checked"' : '' ).' id="save_to_db_2" value="0" type="radio">
															<label for="save_to_db_2">'.__('No','nex-forms').'</label>';
												echo  '</div>';
											echo  '</div>';	
							
										echo  '<div class="row">';
												echo  '<div class="integration_form_label" >'.__('Submission Limit','nex-forms').'</em></div>';
												echo  '<div class="integration_form_field zero_padding tour_other_options_setup_4">';
													echo  '<input type="text" class="form-control" name="submit_limit" id="submit_limit"  placeholder="'.__('Leave Empty/Zero for unlimited submissions','nex-forms').'" value="'.(($option_settings[0]['submit_limit']) ? $option_settings[0]['submit_limit'] : '').'" >';
												echo  '</div>';
										echo  '</div>';	
										
												
										
										
										echo  '<div class="row">';
												echo  '<div class="integration_form_label" >'.__('Limit Reached Message<br><em>Only applicable when you have set a submission limit.</em>','nex-forms').'</em></div>';
												echo  '<div class="integration_form_field zero_padding tour_other_options_setup_5">';
													echo  '<textarea class="form-control" name="submit_limit_msg" id="submit_limit_msg"  placeholder="'.__('Leave blank to hide/remove the form without any message.','nex-forms').'">'.(($option_settings[0]['submit_limit_msg']) ? $option_settings[0]['submit_limit_msg'] : '').'</textarea>';
												echo  '</div>';
										echo  '</div>';	
										
										
										
										echo  '<div class="row">';
												echo  '<div class="integration_form_label">'.__('Google Tracking Code<br /><em>Example: ga(\'send\', \'event\', \'link\', \'click\', \'http://example.com\')','nex-forms').'</em></div>';
												echo  '<div class="integration_form_field zero_padding tour_other_options_setup_16">';
													echo  '<input type="text"  class="form-control" name="google_analytics_conversion_code" id="google_analytics_conversion_code"  placeholder="'.__('Enter ga onclick code','nex-forms').'" value="'.(($this->google_analytics_conversion_code) ? $this->google_analytics_conversion_code : '').'" >';
												echo  '</div>';
										echo  '</div>';	
										
										echo  '<div class="row">';
												echo  '<div class="integration_form_label" >'.__('Before Submit JS<br><em>Custom JS to be executed before a submission.</em>','nex-forms').'</em></div>';
												echo  '<div class="integration_form_field zero_padding tour_other_options_setup_17">';
												if(isset($option_settings[0]['before_submit_js']))
													{
													echo  '<textarea class="form-control" name="set_before_submit_js" id="before_submit_js"   placeholder="'.__('Add Custom JS','nex-forms').'">'.(($option_settings[0]['before_submit_js']) ? wp_unslash($option_settings[0]['before_submit_js']) : '').'</textarea>';
													}
												else
													{
													echo '<textarea class="form-control" name="set_before_submit_js" id="before_submit_js"   placeholder="'.__('Add Custom JS','nex-forms').'"></textarea>';
													}
												echo  '</div>';
										echo  '</div>';	
										
										echo  '<div class="row last">';
												echo  '<div class="integration_form_label" >'.__('After Submit JS<br><em>Custom JS to be executed when submission is successful.</em>','nex-forms').'</em></div>';
												echo  '<div class="integration_form_field zero_padding tour_other_options_setup_18">';
												if(isset($option_settings[0]['after_submit_js']))
													{
													echo  '<textarea class="form-control" name="set_after_submit_js" id="after_submit_js"  placeholder="'.__('Add Custom JS','nex-forms').'">'.(($option_settings[0]['after_submit_js']) ? wp_unslash($option_settings[0]['after_submit_js']) : '').'</textarea>';
													}
												else
													{
													echo '<textarea class="form-control" name="set_after_submit_js" id="after_submit_js"   placeholder="'.__('Add Custom JS','nex-forms').'"></textarea>';	
													}
												echo  '</div>';
										echo  '</div>';	
							
							
							
						echo  '</div>';	
						
					
					
					
					
					
					
					echo '<div id="file_upload_settings" class="integration">';
							
							if(!$set_code)
								echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For these settings to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
							
							
							
										
										echo  '<div class="row">';
											echo  '<div class="integration_form_label" >'.__('Save Form Progress?<br><em>Allow users to complete the form at a later stage</em>','nex-forms').'</em></div>';
											echo  '<div class="integration_form_field no_input tour_other_options_setup_3" style="padding: 15px 0px;">';
												echo  '<input class="with-gap" name="save_form_progress" '.(($option_settings[0]['save_form_progress']=='true') ? 'checked="checked"' : '' ).' id="save_form_progress_yes" value="true" type="radio">
														<label for="save_form_progress_yes">'.__('Enable','nex-forms').'</label>
														<input class="with-gap" name="save_form_progress" '.((!$option_settings[0]['save_form_progress'] || $option_settings[0]['save_form_progress'] =='false') ? 'checked="checked"' : '' ).' id="save_form_progress_no" value="false" type="radio">
														<label for="save_form_progress_no">'.__('Disable','nex-forms').'</label>';
											echo  '</div>';
										echo  '</div>';
										
										
										echo  '<div class="row">';
										
											echo  '<div class="integration_form_label">'.__('Save Submitted Files to Server?','nex-forms').'</div>';
											echo  '<div class="integration_form_field no_input tour_other_options_setup_1">';
												echo  '<input class="with-gap" name="upload_to_server" '.((!$upload_settings[0]['upload_to_server'] || $upload_settings[0]['upload_to_server']=='true') ? 'checked="checked"' : '' ).' id="upload_to_server_yes" value="true" type="radio">
														<label for="upload_to_server_yes">'.__('Yes','nex-forms').'</label>
														
														<input class="with-gap" name="upload_to_server" '.(($upload_settings[0]['upload_to_server'] =='false') ? 'checked="checked"' : '' ).' id="upload_to_server_no" value="false" type="radio">
														<label for="upload_to_server_no">'.__('No','nex-forms').'</label>';
											echo  '</div>';
										echo  '</div>';
										
										
										
									
						
					echo  '</div>';		
						
						
					echo '<div id="saved_hidden_fields" class="integration">';
						
						//echo  '<div class="col-xs-6 form-setup-column">';
							//echo  '<div class="material_box">';
								//echo  '<div class="material_box_head">Hidden Fields &nbsp;</div>';
						
								
								//echo  '<div class="material_box_content tour_hidden_fields_setup_2">';
								
									echo  '<div class="row">';		
									
										/*echo  '<div class="col-sm-2">';	
											echo '<button class="add_hidden_field tour_hidden_fields_setup_1"><span class="btn-tx"><span class="fa fa-plus"></span> '.__('Add hidden Field','nex-forms').'</span></button></button>';
										echo  '</div>';*/
										//echo  '<div class="col-sm-9">';	
											$db_actions = new NEXForms_Database_Actions();
											$nf_functions = new NEXForms_Functions();
											
											if($nf_functions->isJson($this->hidden_fields))
												{
												echo $db_actions->get_form_hidden_fields($this->form_Id); //NEW
												}
											else
												{
												echo $db_actions->get_hidden_fields($this->form_Id); //OLD	
												}
									  // echo  '</div>';
								    echo  '</div>';
								//echo  '</div>';
							//echo  '</div>';
						
						//echo  '</div>';
					echo  '</div>';
				echo  '</div>';
			echo  '</div>';		
		echo '</div>';
		echo '</div>';
		}
	
	
	public function print_styling_tools(){
		
		$output = '';
		
		$output .= '<div class="styling-bar">';
				$output .= '<div class="styling-tool">';
						
						
						$output .= '<div role="toolbar" class="btn-toolbar">';
						$output .= '<div role="group" class="btn-group style-alignment">';
							$output .= '<button class="btn active styling-tool-item btn-default" data-toggle="tooltip_bs" data-style-tool="default-tool" type="button" title="'.__('Normal Mode (Alt+C&nbsp;or&nbsp;Enter)','nex-forms').'"><i class="fa fa-mouse-pointer"></i></button>';
						$output .= '</div>';
						
						
						$output .= '<div role="group" class="btn-group style-font">';
							
							$output .= '<button data-style-tool-group="font-style" class="btn styling-tool-item btn-default" data-style-tool="text-bold" data-toggle="tooltip_bs" type="button" title="'.__('Bold','nex-forms').'"><i class="fa fa-bold"></i></button>';
							$output .= '<button data-style-tool-group="font-style" class="btn styling-tool-item" data-style-tool="text-italic" data-toggle="tooltip_bs" type="button" title="'.__('Italic','nex-forms').'"><i class="fa fa-italic"></i></button>';
							$output .= '<button data-style-tool-group="font-style" class="btn styling-tool-item" data-style-tool="text-underline" data-toggle="tooltip_bs" type="button" title="'.__('Underline','nex-forms').'"><i class="fa fa-underline"></i></button>';
						$output .= '</div>';
						
						
						
						$output .= '<div role="group" class="btn-group style-alignment">';
							
							$output .= '<button class="btn styling-tool-item btn-default" data-style-tool-group="text-align" data-style-tool="align-left" data-toggle="tooltip_bs" type="button" title="'.__('Left align text','nex-forms').'"><i class="fa fa-align-left"></i></button>';
							$output .= '<button class="btn styling-tool-item" data-style-tool-group="text-align" data-style-tool="align-center" data-toggle="tooltip_bs" type="button" title="'.__('Center align text','nex-forms').'"><i class="fa fa-align-center"></i></button>';
							$output .= '<button class="btn styling-tool-item" data-style-tool-group="text-align" data-style-tool="align-right" data-toggle="tooltip_bs" type="button" title="'.__('Right align text','nex-forms').'"><i class="fa fa-align-right"></i></button>';
						$output .= '</div>';
						
						$output .= '<div role="group" class="btn-group style-size">';
							
							$output .= '<button data-style-tool-group="size" class="btn styling-tool-item btn-default" data-style-tool="size-sm" data-toggle="tooltip_bs" type="button" title="'.__('Size Small','nex-forms').'"><i class="fa fa-font" style="font-size:10px"></i></button>';
							$output .= '<button data-style-tool-group="size" class="btn styling-tool-item" data-style-tool="size-normal" data-toggle="tooltip_bs" type="button" title="'.__('Size Normal','nex-forms').'"><i class="fa fa-font" style="font-size:13px"></i></button>';
							$output .= '<button data-style-tool-group="size" class="btn styling-tool-item" data-style-tool="size-lg" data-toggle="tooltip_bs" type="button" title="'.__('Size Large','nex-forms').'"><i class="fa fa-font" style="font-size:16px"></i></button>';
						$output .= '</div>';
												
										$output .= '</div>';
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<input type="text" class="form-control font-color-tool" name="font-color-tool" id="bs-color">
													<span class="input-group-addon  styling-tool-item" data-style-tool-group="color" data-style-tool="set-font-color" data-toggle="tooltip_bs" title="'.__('Text Color','nex-forms').'">';
												$output .= '<i class="fa fa-font"></i>';
											$output .= '</span>';
										$output .= '</div>';
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<input type="text" class="form-control background-color-tool" name="background-color-tool" id="bs-color">
													<span class="input-group-addon  styling-tool-item" data-style-tool-group="color" data-style-tool="set-background-color" data-toggle="tooltip_bs" title="'.__('Background Color','nex-forms').'">';
												$output .= '<i class="fa dashicons-before dashicons-admin-appearance"></i>';
											$output .= '</span>';
										$output .= '</div>';
										
										$output .= '<div class="input-group input-group-sm">';
											$output .= '<input type="text" class="form-control border-color-tool" name="border-color-tool" id="bs-color">
													<span class="input-group-addon  styling-tool-item" data-style-tool-group="color" data-style-tool="set-border-color" data-toggle="tooltip_bs" title="'.__('Border Color','nex-forms').'">';
												$output .= '<i class="fa fa-square-o"></i>';
											$output .= '</span>';
										$output .= '</div>';
								
								
								
										
								
										$output .= '<div class="input-group-sm">';
											
											$output .= '<select name="google_fonts" class="sfm form-control">';
												$get_google_fonts = new NF5_googlefonts();
												$output .= $get_google_fonts->get_google_fonts();
											$output .= '</select>';
											
											$output .= '<div role="group" class="btn-group ">
											
											
											<button data-style-tool-group="font-family" class="btn set-font-family styling-tool-item btn-default" data-style-tool="font-family" data-toggle="tooltip_bs" type="button" title="'.__('Font Family','nex-forms').'"><i class="fa fa-google"></i></button>';
							
							
									$output .= '</div></div>';
										
									$output .= '</div>';
										
										$output .= '<div role="group" class="btn-group ">';
							
											$output .= '<button data-style-tool-group="layout" class="styling-tool-item btn-default set_layout set_layout_left" data-style-tool="layout-left" data-toggle="tooltip_bs" type="button" title="'.__('Label Left','nex-forms').'"></button>';
											$output .= '<button data-style-tool-group="layout" class="styling-tool-item set_layout set_layout_right" data-style-tool="layout-right" data-toggle="tooltip_bs" type="button" title="'.__('Label Right','nex-forms').'"></button>';
											
										$output .= '</div>';
										$output .= '<div role="group" class="btn-group style-layout-2">';
							
											$output .= '<button data-style-tool-group="layout" class="styling-tool-item btn-default  set_layout set_layout_top" data-style-tool="layout-top" data-toggle="tooltip_bs" type="button" title="'.__('Label Top','nex-forms').'"></button>';
											$output .= '<button data-style-tool-group="layout" class="styling-tool-item set_layout set_layout_hide" data-style-tool="layout-hide" data-toggle="tooltip_bs" type="button" title="'.__('Hide Label','nex-forms').'"></button>';
											
										$output .= '</div>';
										
									
								
								$output .= '</div>';
								
				
				return $output;
			
	}
	
	
	
	public function print_integration_setup(){
			
		$set_code = true;
            if(!get_option('nf_activated'))
                $set_code = false;
					
			$preferences = get_option('nex-forms-preferences');
			
			$db_actions = new NEXForms_Database_Actions();
			
			echo '<div class="form_attr_wrapper">';
				
						echo '	<div class="navigation" style="display:none;"><div class="nav-content">
									<ul class="tabs_nf tri-menu">
										<li class="tab show_pdf_setup_menu_item"><a class="active" href="#pdfcreator">'.__('PDF Creator','nex-forms').'</a></li>
										<li class="tab show_paypal_setup_menu_item"><a class="" href="#paypal_integration">'.__('PayPal','nex-forms').'</a></li>
										<li class="tab show_zapier_setup_menu_item"><a class="" href="#zapier_integration">'.__('Zapier','nex-forms').'</a></li>
										<!--<li class="tab show_hubspot_setup_menu_item"><a class="" href="#hubspot_integration">'.__('Hubspot','nex-forms').'</a></li>-->
										<li class="tab show_ftp_setup_menu_item"><a class="" href="#formtopost">'.__('Form to Post','nex-forms').'</a></li>
										<li class="tab show_mc_setup_menu_item"><a class="" href="#mailchimp">'.__('MailChimp','nex-forms').'</a></li>
										<li class="tab show_gr_setup_menu_item"><a class="" href="#getresponse">'.__('GetResponse','nex-forms').'</a></li>
										<li class="tab show_mp_setup_menu_item"><a class="" href="#mailpoet">'.__('MailPoet','nex-forms').'</a></li>
										<li class="tab show_ms_setup_menu_item"><a class="" href="#mailster">'.__('Mailster','nex-forms').'</a></li>
									</ul>
								</div></div>';
				
				echo '<div class="form_attr_left_menu aa_bg_sec aa_menu">';
				echo '<ul>';
					echo '<li class="active"><a class="show_pdf_setup"><span class="fa fa-file-pdf-o"></span> <span class="sidemenu_text">'.__('PDF Creator','nex-forms').'</span></a></li>';
					
					echo '<li class=""><a class="show_paypal_setup"><span class="fa fa-paypal"></span> <span class="sidemenu_text">'.__('PayPal','nex-forms').'</span></a></li>';
					echo '<li><a class="show_zapier_setup"><span class="fa fa-asterisk"></span> <span class="sidemenu_text">'.__('Zapier','nex-forms').'</span></a></li>';
					//echo '<li><a class="show_hubspot_setup"><span class="fa fas fa-project-diagram"></span> <span class="sidemenu_text">'.__('Hubspot','nex-forms').'</span></a></li>';
					echo '<li><a class="show_ftp_setup"><span class="fa fa-edit"></span> <span class="sidemenu_text">'.__('Form to Post','nex-forms').'</span></a></li>';
					echo '<li><a class="show_mc_setup"><span class="fa fa-envelope"></span> <span class="sidemenu_text">'.__('MailChimp','nex-forms').'</span></a></li>';
					echo '<li><a class="show_gr_setup"><span class="fa fa-envelope"></span> <span class="sidemenu_text">'.__('GetResponse','nex-forms').'</span></a></li>';
					echo '<li><a class="show_mp_setup"><span class="fa fa-envelope"></span> <span class="sidemenu_text">'.__('MailPoet','nex-forms').'</span></a></li>';
					echo '<li><a class="show_ms_setup"><span class="fa fa-envelope"></span> <span class="sidemenu_text">'.__('Mailster','nex-forms').'</span></a></li>';
				echo '</ul>';
				echo '</div>';

				//echo '<div class="form_attr_setup">';
				
				echo '<div class="form_attr_settings_wrapper">';
				
						
					echo '<div id="zapier_integration" class="integration">';
						
						if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
							
						if ( function_exists('NEXForms_not_found_notice_zapier'))
							{	
							
													
													global $wpdb;
													$get_zapier_url = $wpdb->prepare('SELECT zapier_web_hook_url FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',filter_var($this->form_Id,FILTER_SANITIZE_NUMBER_INT));
													$zapier_URL = $wpdb->get_var($get_zapier_url);
													
													
													
													
													echo '<div class="row">';
														echo '<br /><div class="alert alert-info">
														1. Use <a href="https://zapier.com/developer/public-invite/161949/d12842cda054f597d0b94260c4c6b53b/" target="_blank"><strong>THIS LINK</strong></a> to go to Zapier.com.<br />2. Hit Make a Zap! button. This will take you to the setup screen.<br />3. Under Choose a Trigger App, select NEX-Forms, and choose New Form Entry as the trigger. <br />4. On the Test this Setup screen, you will find a Webhook URL. Copy this URL and paste it in the field below.</div>';
													echo '</div>';
													
													echo '<div class="row">';
														echo '<div class="integration_form_label">Zapier Webhook URL</div>';
														echo '<div class="integration_form_field zero_padding">';
															echo '<input type="text" placeholder="Enter your webhook URL" value="'.$zapier_URL.'" name="zapier_web_hook_url" class="form-control">';
														echo '</div>';
													echo '</div>';
		
													
										
							}
						else
							echo '<div class="zapier_not_installed add_on_not_found"><span class="ni-icon fa fa-asterisk"></span><span class="message">'.__('Zapier add-on not installed','nex-forms').'</span><a class="button buy_item" href="https://codecanyon.net/item/paypal-pro-for-nexforms/22449576?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';
							
					
					
					echo '</div>';	
					
					
					
					
					/*echo '<div id="hubspot_integration" class="integration">';
						
						if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
							
						if ( function_exists('NEXForms_not_found_notice_hubspot'))
							{	
							
													
													global $wpdb;
													$get_hs_portal_id = $wpdb->prepare('SELECT hubspot_portal_id FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',filter_var($this->form_Id,FILTER_SANITIZE_NUMBER_INT));
													$hs_portal_id = $wpdb->get_var($get_hs_portal_id);
													
													$get_hs_form_id = $wpdb->prepare('SELECT hubspot_form_id FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',filter_var($this->form_Id,FILTER_SANITIZE_NUMBER_INT));
													$hs_form_id = $wpdb->get_var($get_hs_form_id);
													
													$get_hs_integration = $wpdb->prepare('SELECT is_hubspot FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',filter_var($this->form_Id,FILTER_SANITIZE_NUMBER_INT));
													$is_hubspot = $wpdb->get_var($get_hs_integration);
													
													
													
													/*echo '<div class="row">';
														echo '<br /><div class="alert alert-info">
														1. Use <a href="https://zapier.com/developer/public-invite/161949/d12842cda054f597d0b94260c4c6b53b/" target="_blank"><strong>THIS LINK</strong></a> to go to Zapier.com.<br />2. Hit Make a Zap! button. This will take you to the setup screen.<br />3. Under Choose a Trigger App, select NEX-Forms, and choose New Form Entry as the trigger. <br />4. On the Test this Setup screen, you will find a Webhook URL. Copy this URL and paste it in the field below.</div>';
													echo '</div>';*/
													/*echo  '<div class="row">';
										
														echo '<div class="integration_form_label">';
															echo 'Integrate Hubspot';
														echo '</div>';
													
														echo  '<div class="integration_form_field no_input tour_paypal_setup_1" style="margin-left:0 !important;">';
															echo  '
																	<div class="col-sm-1 zero_padding"><input class="with-gap" name="integrate_hubspot" '.(($is_hubspot=='yes') ? 'checked="checked"' : '' ).' id="go_to_hs_yes" value="yes" type="radio">
																	<label for="go_to_hs_yes">Yes</label></div>
																	
																	<div class="col-sm-1 zero_padding"><input class="with-gap" name="integrate_hubspot" '.((!$is_hubspot || $is_hubspot=='no') ? 'checked="checked"' : '' ).' id="go_to_hs_no" value="no" type="radio">
																	<label for="go_to_hs_no">No</label>
																	</div>
																	
																	
																	<div class="col-sm-10"></div>
																	';
														echo '</div>';
													echo '</div>';	
													
													echo '<div class="row">';
														echo '<div class="integration_form_label">Hubspot Portal ID</div>';
														echo '<div class="integration_form_field zero_padding">';
															echo '<input type="text" placeholder="Enter your Hubspot portal ID" value="'.$hs_portal_id.'" name="hubspot_portal_id" class="form-control">';
														echo '</div>';
													echo '</div>';
													echo '<div class="row">';
														echo '<div class="integration_form_label">Hubspot Form ID</div>';
														echo '<div class="integration_form_field zero_padding">';
															echo '<input type="text" placeholder="Enter your Hubspot form ID" value="'.$hs_form_id.'" name="hubspot_form_id" class="form-control">';
														echo '</div>';
													echo '</div>';
		
													
										
							}
						else
							echo '<div class="zapier_not_installed add_on_not_found"><span class="ni-icon fas fa-project-diagram"></span><span class="message">'.__('Hubspot add-on not installed','nex-forms').'</span><a class="button buy_item" href="https://codecanyon.net/item/paypal-pro-for-nexforms/22449576?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';
							
					
					
					echo '</div>';	*/
					
					
					
					
					
					
					
					echo '<div id="paypal_integration" class="integration">';
						
						if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
						
						
						if ( function_exists('nf_not_found_notice_pp') || function_exists('run_nf_adv_paypal')){
						
							echo '<div class="paypal-setup-column form-setup-column">';
								echo '<div class="material_box">';
									echo '<div class="material_box_head aa_bg_main aa_font_color_default">';
										echo ''.__('PayPal Setup','nex-forms').'';
									echo '</div>';
									echo '<div class="material_box_content">';
										echo '<div class="paypal-column">';
											echo '<div class="inner">';
												
													echo $db_actions->print_paypal_setup($this->form_Id);
													
												echo '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
							
							echo '<div class="paypal-items-column">';
								echo '<div class="material_box">';
									echo '<div class="material_box_head">';
										echo ''.__('PayPal Checkout Items','nex-forms').'';
										echo '<button id="add_paypal_product" class="button btn btn-default tour_paypal_setup_9"><span class="fa fa-cart-plus"></span> '.__('Add Paypal Item','nex-forms').'</button>';
									echo '</div>';
									echo '<div class="material_box_content tour_paypal_setup_10">';
										echo '<div class="paypal-column">';
											echo '<div class="inner">';
													echo $db_actions->build_paypal_products($this->form_Id);
											echo '</div>';
											
										echo '</div>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						}
						else
							echo '<div class="paypal_not_installed add_on_not_found"><span class="ni-icon fa fa-paypal"></span><span class="message">'.__('PayPal add-on not installed','nex-forms').'</span><a class="button buy_item" href="https://codecanyon.net/item/paypal-pro-for-nexforms/22449576?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';
											
							
						echo '</div>';
					
					
					
						echo '<div id="pdfcreator">';
							if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
						
							if (function_exists('nf_not_found_notice_pdf'))
								echo $this->print_pdf_creator($this->form_Id);
							else
								echo '<div class="pdf_not_installed add_on_not_found"><span class="ni-icon fa fa-file-pdf-o"></span><span class="message">'.__('PDF Creator add-on not installed','nex-forms').'</span><a class="button buy_item" href="https://codecanyon.net/item/pdf-creator-for-nexforms/11220942?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';

						echo '</div>';
						
						echo '<div id="formtopost">';
							if(!$set_code)
								echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
							if ( function_exists('nexforms_ftp_setup'))
									echo nexforms_ftp_setup($this->form_Id);
							else
								echo '<div class="ftp_not_installed add_on_not_found"><span class="ni-icon fa fa-edit"></span><span class="message">'.__('Form to Post add-on not installed','nex-forms').'</span><a class="button buy_item" href="http://codecanyon.net/item/form-to-postpage-for-nexforms/19538774?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';

						echo '</div>';
						
						
						echo '<div id="mailchimp" class="integration">';
							if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
						if ( function_exists('nexforms_mc_test_api'))
							{
							echo '<div class="form-setup-column">';
								echo '<div class="material_box">';
									echo '<div class="material_box_head">';
										echo ''.__('MailChimp','nex-forms').'';
									echo '</div>';
									echo '<div class="material_box_content">';
											
												echo nexforms_mc_get_lists($this->form_Id, $this->mc_list_id);
												echo '<div class="mc_field_map tour_mc_setup_3">';
													echo nexforms_mc_get_form_fields($this->form_Id, $this->mc_list_id);
												echo '</div>';
												
											
									echo '</div>';
								echo '</div>';
							echo '</div>';
							}
						else
							echo '<div class="mc_not_installed add_on_not_found"><span class="ni-icon fa fa-envelope"></span><span class="message">'.__('Mailchimp add-on not installed','nex-forms').'</span><a class="button buy_item" href="https://codecanyon.net/item/mailchimp-for-nexforms/18030221?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';

						echo '</div>';
					
						
						echo '<div id="getresponse" class="integration">';
						if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
							if ( function_exists('nexforms_gr_test_api'))
								{
							echo '<div class="form-setup-column">';
								echo '<div class="material_box">';
									echo '<div class="material_box_head">';
										echo ''.__('GetResponse','nex-forms').'';
									echo '</div>';
									echo '<div class="material_box_content">';	
										
									
										echo nexforms_gr_get_lists($this->form_Id, $this->gr_list_id);
										echo '<div class="gr_field_map tour_gr_setup_3">';
											echo nexforms_gr_get_form_fields($this->form_Id, $this->gr_list_id);
										echo '</div>';
										
									echo '</div>';
								echo '</div>';
							echo '</div>';
							}
						else
							echo '<div class="gr_not_installed add_on_not_found"><span class="ni-icon fa fa-envelope"></span><span class="message">GetResponse add-on not installed</span><a class="button buy_item" href="https://codecanyon.net/item/getresponse-for-nexforms/18462247?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';
						
						echo '</div>';
					
					
						
						echo '<div id="mailpoet" class="integration">';
						if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
						if ( function_exists('nexforms_mp_test_api'))
							{
							echo '<div class="form-setup-column">';
								echo '<div class="material_box">';
									echo '<div class="material_box_head">';
										echo ''.__('MailPoet','nex-forms').'';
									echo '</div>';
									echo '<div class="material_box_content">';
												if(function_exists('mailpoet_wp_version_notice')) { 
												echo nexforms_mp_get_lists($this->form_Id, $this->mp_list_id);
												echo '<div class="mp_field_map tour_mp_setup_3">';
													echo nexforms_mp_get_form_fields($this->form_Id, $this->mp_list_id);
												echo '</div>';
												
												}
												else{
													echo '<div class="alert alert-danger">MailPoet is not installed. Please install the MailPoet plugin to use this add-on.</div>';	
												}
											
									echo '</div>';
								echo '</div>';
							echo '</div>';
							}
						else
							echo '<div class="mc_not_installed add_on_not_found"><span class="ni-icon fa fa-envelope"></span><span class="message">'.__('MailPoet add-on not installed','nex-forms').'</span><a class="button buy_item" href="https://codecanyon.net/item/mailchimp-for-nexforms/18030221?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';

						echo '</div>';
						
						
						echo '<div id="mailster" class="integration">';
						if(!$set_code)
							echo '<div class="row"><div class="alert alert-danger"><strong>PLUGIN NOT REGISTERED: </strong> For integrations to take effect you need to <a href="https://1.envato.market/zQ6de" target="_blank" class="">UPGRADE to PRO</a> by registering NEX-Forms with your purchase code from your NEX-Forms Dashboard -> Global Settings.</div></div>';
						
						if ( function_exists('nexforms_ms_test_api'))
							{
							echo '<div class="form-setup-column">';
								echo '<div class="material_box">';
									echo '<div class="material_box_head">';
										echo ''.__('MailSter','nex-forms').'';
									echo '</div>';
									echo '<div class="material_box_content">';
												if( function_exists( 'mailster' ) ){
												echo nexforms_ms_get_lists($this->form_Id, $this->ms_list_id);
												echo '<div class="ms_field_map tour_ms_setup_3">';
													echo nexforms_ms_get_form_fields($this->form_Id, $this->ms_list_id);
												echo '</div>';
												
												}
												else{
													echo '<div class="alert alert-danger">Mailster is not installed. Please install the Mailster plugin to use this add-on.</div>';	
												}
											
									echo '</div>';
								echo '</div>';
							echo '</div>';
							}
						else
							echo '<div class="ms_not_installed add_on_not_found"><span class="ni-icon fa fa-envelope"></span><span class="message">'.__('Mailster add-on not installed','nex-forms').'</span><a class="button buy_item" href="https://codecanyon.net/item/mailchimp-for-nexforms/18030221?ref=Basix" target="_blank"><span class="fa fa-shopping-cart"></span><br />'.__('Buy Add-on','nex-forms').'</a><a class="button elements buy_item" href="https://elements.envato.com/user/Basix?ref=Basix" target="_blank"><span class="fa fa-cloud-download"></span><br />'.__('Download','nex-forms').'</a></div>';

						echo '</div>';
						
					
					
			
					//echo '</div>';	
				echo '</div>';	
			echo '</div>';	
		}
	
	
	public function print_pdf_creator($form_Id){
			
		global $wpdb;
			
		$preferences = get_option('nex-forms-preferences');
		
		$pdf_attach = array();
		
		if($form_Id)
			{
			$get_form = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',filter_var($form_Id,FILTER_SANITIZE_NUMBER_INT));
			$form = $wpdb->get_row($get_form);
		
			$pdf_attach = explode(',',$form->attach_pdf_to_email);
			}
			
			$pdf_settings = json_decode($this->pdf_settings,true);
			
			$orientation = $pdf_settings[0]['pdf_orientation'];
			$pdf_size = $pdf_settings[0]['pdf_size'];
			
			
			echo '<div class="">';
				
				echo '<div class="row">';
					echo '<div class="integration_form_label">'.__('PDF Email Attachements','nex-forms').'</div>';
					
					echo '<div class="integration_form_field no_input tour_pdf_setup_1" >';
						echo '<input '.(in_array('admin',$pdf_attach) ? 'checked="checked"': '').' name="pdf_admin_attach" value="1" id="pdf_admin_attach" type="checkbox"><label for="pdf_admin_attach">'.__('Attach this PDF to Admin Notifications Emails','nex-forms').'<em></em></label>';
						echo '<br /><input '.(in_array('user',$pdf_attach) ? 'checked="checked"': '').' name="pdf_user_attach" value="1" id="pdf_user_attach" type="checkbox"><label for="pdf_user_attach">'.__('Attach this PDF to Autoresponder User Emails','nex-forms').'<em></em></label>';
					echo '</div>';
				echo '</div>';
				
				
				
				echo  '<div class="row">';
										
						echo '<div class="integration_form_label">';
							echo 'Page Orientation';
						echo '</div>';
					
						echo  '<div class="integration_form_field no_input " style="margin-left:0 !important;">';
							echo  '
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_orientation" '.((!$orientation || $orientation=='P') ? 'checked="checked"' : '' ).' id="pdf_p" value="P" type="radio">
									<label for="pdf_p">Portriat</label></div>
									
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_orientation" '.(($orientation=='L') ? 'checked="checked"' : '' ).' id="pdf_l" value="L" type="radio">
									<label for="pdf_l">Landscape</label>
									</div>
									
									
									<div class="col-sm-10"></div>
									';
						echo '</div>';
					echo '</div>';	
				
				
				/*echo  '<div class="row">';
										
						echo '<div class="integration_form_label">';
							echo 'Page Size';
						echo '</div>';
					
						echo  '<div class="integration_form_field no_input " style="margin-left:0 !important;">';
							echo  '
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_size" '.(($pdf_size=='A1') ? 'checked="checked"' : '' ).' id="pdf_size_a1" value="A1" type="radio">
									<label for="pdf_size_a1">A1</label></div>
									
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_size" '.(($pdf_size=='A2') ? 'checked="checked"' : '' ).' id="pdf_size_a2" value="A2" type="radio">
									<label for="pdf_size_a2">A2</label></div>
									
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_size" '.(($pdf_size=='A3') ? 'checked="checked"' : '' ).' id="pdf_size_a3" value="A3" type="radio">
									<label for="pdf_size_a3">A3</label></div>
									
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_size" '.((!$pdf_size || $pdf_size=='A4') ? 'checked="checked"' : '' ).' id="pdf_size_a4" value="A4" type="radio">
									<label for="pdf_size_a4">A4</label></div>
									
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_size" '.(($pdf_size=='A5') ? 'checked="checked"' : '' ).' id="pdf_size_a5" value="A5" type="radio">
									<label for="pdf_size_a5">A5</label></div>
									
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_size" '.(($pdf_size=='A6') ? 'checked="checked"' : '' ).' id="pdf_size_a6" value="A6" type="radio">
									<label for="pdf_size_a6">A6</label></div>
									
									<div class="col-sm-1 zero_padding"><input class="with-gap" name="pdf_size" '.(($pdf_size=='A7') ? 'checked="checked"' : '' ).' id="pdf_size_a7" value="A7" type="radio">
									<label for="pdf_size_a7">A7</label></div>
									
									<div class="col-sm-5"></div>
									';
						echo '</div>';
					echo '</div>';	*/
				
				echo '<div class="row">';
									echo '<div class="integration_form_label ">Page Size</div><div class="integration_form_field zero_padding"><select name="pdf_size" class="set_currency_code form-control" data-selected="'.(($pdf_size) ? $pdf_size : 'A4').'">
												  <option value="A1">A1</option>
												  <option value="A2">A2</option>
												  <option value="A3">A3</option>
												  <option value="A4">A4</option>
												  <option value="A5">A5</option>
												  <option value="A6">A6</option>
												  <option value="A7">A7</option>
												</select></div>';
								echo '</div>';
				
				
				echo '<div class="row">';
					echo '<div class="editor_wrapper">';
						wp_editor( (($this->pdf_html) ? wp_unslash($this->pdf_html) : '' ), 'pdf_html');		
					echo '</div>';
				echo '</div>';
			echo '</div>';
		
		}
	
	
	
	
			  
	
	
	public function print_embed_setup(){
			
			
			
		
			$output  ='';
			$output .= '<div class="form_embed_settings_wrapper">';
					
					$set_code = '';
						if(!get_option('nf_activated'))
							$set_code = 'no_code';
					
					$output .= '<div class="form_embed_shortcode_display '.$set_code.'">';	
						
						$output .= '<div class="embed_tools set_form_type">';
							$output .= '<div class="inline btn btn-default active"><span class="fa fa-file-invoice"></span> '.__('Inline','nex-forms').'</div>';
							$output .= '<div class="popup btn btn-default"><span class="fa fa-window-restore"></span> '.__('Popup','nex-forms').'</div>';
							//$output .= '<div class="sticky btn btn-default"><span class="fa fa-chalkboard" style="transform:rotate(270deg);font-size: 12px !important;"></span></span> '.__('Sticky Form','nex-forms').'</div>';
						$output .= '</div>';
					
						$output .= '<div class="embed_tools shortcode_php">';							
							$output .= '<div class="show_shortcode btn active">Shortcode <span class="fab fa-wordpress"></span></div>';
							$output .= '<div class="show_php btn"><span class="fab fa-php"></span></div>';
						$output .= '</div>';	
						
						
						
						
						$output .= '<div class="shortcode ">';
							$output .= '<div class="embed_code">';
								$output .= '[NEXForms id="'.$this->form_Id.'"]';
							$output .= '</div>';
						$output .= '</div>';
						
					
					
						$output .= '<div class="php" style="display:none;">';
							$output .= '<div class="embed_code">';
								$output .= '&lt;?php NEXForms_ui_output('.$this->form_Id.',true); ?&gt;';
							$output .= '</div>';
						$output .= '</div>';
						
					
					$output .= '</div>';
					
					$output .= '<div class="popup-previews hidden">';	
						
						$output .= '<div class="col-md-4 button-preview hidden">';
							$output .= '<button class="btn md-btn btn-light-blue">Open Form</button>';
						$output .= '</div>';
						
						$output .= '<div class="col-md-8">';
						
							$output .= '<div class="modal-preview">';
								$output .= '<div class="modal-container">';
									$output .= '<div class="close-preview"><span class="fa fa-close"></span></div>';
									$output .= '<div class="modal-inner-container">';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
							
						$output .= '</div>';
						
					$output .= '</div>';	
			
			
			$output .= '<div class="overall-settings-column embed-settings-column overall-form-styling-column settings-column-style  right_hand_col">';
			
						
						$output .= '<div class="material_box_head aa_bg_main"><span class="fa fa-code"></span>'.__('Popup Settings','nex-forms').'</div>';
						
						$output .= '<div class="overall-setting-categories field-setting-categories-style">';
							
							
							$output .= '<nav class="nav-extended settings_tabs_nf hidden">
											<div class="nav-content aa_bg_main">
											  <ul class="tabs_nf tabs_nf-transparent sec-menu aa_menu form_embed_types">
												<li id="" class="tab always_current"><a class="emded_type active inline" href="#embed-inline-panel">'.__('Inline','nex-forms').'</a></li>
												<li id="" class="tab always_current"><a class="emded_type popup" href="#embed-popup-panel">'.__('Popup','nex-forms').'</a></li>
												<li id="" class="tab always_current"><a class="emded_type sticky" href="#embed-sticky-panel">'.__('Sticky','nex-forms').'</a></li>
												-->
											  </ul>
											</div>
										 </nav>';
							$output .= '</div>';
					
					
						$output .= '<div class="inner row">';
//LABEL SETTINGS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
						
						
							
							
							
							/*$output .= '<div id="embed-inline-panel" class="form-settings row settings-section active">';
							
								$output .= '<div class="field-setting col-xs-12 s-all">';
									$output .= '<div role="group" class="btn-group embed-form-style ">';
										$output .= '<small>'.__('Form Style','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal active" type="button" data-style-tool="normal" title="Normal Inline Style"><i class="fa fa-file-invoice"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf conversational " type="button" data-style-tool="conversational"  title="Conversational Style"><i class="fas fa-comment"></i></button>';
										//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf chat   " type="button" data-style-tool="chat"  title="Chat Style"><i class="fas fa-comments"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							
							$output .= '</div>';*/	
							
							//$output .= '<div id="embed-popup-panel" class="form-settings row settings-section">';
							
								/*$output .= '<div class="field-setting col-xs-12 s-all">';
									$output .= '<div role="group" class="btn-group embed-form-style ">';
										$output .= '<small>'.__('Form Style','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf normal active" type="button" data-style-tool="normal" title="Normal Inline Style"><i class="fa fa-file-invoice"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf conversational " type="button" data-style-tool="conversational"  title="Conversational Style"><i class="fas fa-comment"></i></button>';
										//$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf chat   " type="button" data-style-tool="chat"  title="Chat Style"><i class="fas fa-comments"></i></button>';
									$output .= '</div>';
								$output .= '</div>';*/
								
								
								$output .= '<div class="field-setting col-xs-12 s-all">';
									$output .= '<div role="group" class="btn-group embed-poppup-trigger ">';
										$output .= '<small>'.__('Popup Trigger','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf button active" type="button" data-style-tool="button" title="Button"><i class="fa fa-minus-square"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf link" type="button" data-style-tool="link"  title="Link"><i class="fas fa-link"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf custom" type="button" data-style-tool="custom"  title="Custom Element"><i class="fa fa-gear"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf timer" type="button" data-style-tool="timer"  title="Timer"><i class="fa fa-clock"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf scroll" type="button" data-style-tool="scroll"  title="Scroll Position"><i class="fa fa-sort-amount-down"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf exit" type="button" data-style-tool="exit"  title="Exit Intent"><i class="fas fa-door-open"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
								
								//BUTTON
								$output .= '<div class="field-setting col-xs-12 s-all embed-button-text">';
									$output .= '<small>'.__('Button Text','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_popup_button_text" id="set_popup_button_text" placeholder="Add Button Text" value="Open Form">';
								$output .= '</div>';
								//LINK
								$output .= '<div class="field-setting col-xs-12 s-all embed-link-text hidden">';
									$output .= '<small>'.__('Link Text','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_popup_link_text" id="set_popup_link_text" placeholder="Add Link Text" value="Open Form">';
								$output .= '</div>';
								//CUSTOM
								$output .= '<div class="field-setting col-xs-12 s-all embed-custom-class hidden">';
									$output .= '<small>'.__('Element Classname or ID','nex-forms').'</small>';
									$output .= '<input type="text" class="form-control" name="set_popup_custom_text" id="set_popup_custom_text" placeholder="Add Element Class or ID">';
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-12 s-all embed-on-timer hidden">';
									
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Auto-popup </span>';
										$output .= '</span>';
									
										$output .= '<input type="text" class="form-control" name="set_popup_time" id="set_popup_time" value="15" placeholder="Add total seconds before auto popup">';
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">&nbsp; seconds after page load</span>';
										$output .= '</span>';
									$output .= '</div>';
								
								$output .= '</div>';
								
								$output .= '<div class="field-setting col-xs-12 s-all embed-on-scroll hidden">';
									
									$output .= '<div class="input-group input-group-sm">';
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text">Auto-popup when scroll position reaches &nbsp;</span>';
										$output .= '</span>';
										$output .= '<input type="text" class="form-control" name="set_popup_scroll_pos" id="set_popup_scroll_pos"  value="500" placeholder="Add scroll depth for auto-popup (in pixels)">';
										$output .= '<span class="input-group-addon">';
											$output .= '<span class="icon-text"> &nbsp;pixels from the top</span>';
										$output .= '</span>';
									$output .= '</div>';
								$output .= '</div>';
								
								
								
								
								$output .= '<div class="field-setting col-xs-12 s-all embed-poppup-button-color">';
									$output .= '<small>'.__('Preset Material &amp; Bootstrap Button Colors','nex-forms').'</small>';
									$output .= '<div role="group" class="btn-group">';
										$output .='
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-light-blue popup-button active" data-btn-class="btn-light-blue"  data-default-values="" ></button>

												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-nf-default popup-button " data-btn-class="btn-nf-default"  data-default-values="" style="border:1px solid #ccc !important;" ></button>
													
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-red popup-button" data-btn-class="btn-red"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-pink popup-button" data-btn-class="btn-pink"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-purple popup-button" data-btn-class="btn-purple"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-deep-purple popup-button" data-btn-class="btn-deep-purple"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-indigo popup-button" data-btn-class="btn-indigo"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-blue popup-button" data-btn-class="btn-blue"  data-default-values="" ></button>

												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-cyan popup-button" data-btn-class="btn-cyan"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-teal popup-button" data-btn-class="btn-teal"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-green popup-button" data-btn-class="btn-green"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-light-green popup-button" data-btn-class="btn-light-green"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-lime popup-button" data-btn-class="btn-lime"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-yellow popup-button" data-btn-class="btn-yellow"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-amber popup-button" data-btn-class="btn-amber"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-orange popup-button" data-btn-class="btn-orange"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-brown popup-button" data-btn-class="btn-brown"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-gray popup-button" data-btn-class="btn-gray"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-blue-gray popup-button" data-btn-class="btn-blue-gray"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-primary popup-button" data-btn-class="btn-primary"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-info popup-button" data-btn-class="btn-info"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-warning  popup-button" data-btn-class="btn-warning"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-success popup-button" data-btn-class="btn-success"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-danger popup-button" data-btn-class="btn-danger"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-nf-555  popup-button" data-btn-class="btn-nf-555"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-nf-333 popup-button" data-btn-class="btn-nf-333"  data-default-values="" ></button>
												   
												   <button class="btn md-btn waves-effect-nf waves-light-nf btn-nf-000 popup-button" data-btn-class="btn-nf-000"  data-default-values="" ></button>
												   
												   
                                                   ';
									$output .= '</div>';
								$output .= '</div>';
								
								$output .= '<div class="field-setting is-settings-header col-xs-12 s-all">';	
									$output .= '<div class="settings-header"><span>'.__('Popup Styling','nex-forms').'</span></div>';
								$output .= '</div>';
							
								
							$output .= '<div class="field-setting col-xs-6 s-all">';
									$output .= '<div role="group" class="btn-group embed-popup-v-position ">';
										$output .= '<small>'.__('Vertical Postition','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center active" type="button" data-style-tool="center" title="Center"><i class="fas fa-arrows-alt-v"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf top " type="button" data-style-tool="top"  title="Top"><i class="fas fa-arrow-up"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf bottom   " type="button" data-style-tool="bottom"  title="Bottom"><i class="fas fa-arrow-down"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
									$output .= '<div role="group" class="btn-group embed-popup-h-position ">';
										$output .= '<small>'.__('Horizontal Postition','nex-forms').'</small>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf center active" type="button" data-style-tool="center" title="Center"><i class="fas fa-arrows-alt-h"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf left " type="button" data-style-tool="top"  title="Left"><i class="fas fa-arrow-left"></i></button>';
										$output .= '<button class="btn btn-default waves-effect-nf waves-light-nf right   " type="button" data-style-tool="bottom"  title="Right"><i class="fas fa-arrow-right"></i></button>';
									$output .= '</div>';
								$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Background','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon action-btn set_popup_bg use_form active">';
										$output .= '<span class="icon-text" data-toggle="tooltip_bs" data-placement="top" title="Applies the form Wrapper<br>Styling to the Popup">Inherit</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon set_popup_bg action-btn use_custom">';
										$output .= '<span class="icon-text" data-toggle="tooltip_bs" data-placement="top" title="Use a custom background<br>for the popup and keep<br>the form Wrapper in tact">Custom</span>';
									$output .= '</span>';
									$output .= '<span class="input-group-addon  action-btn color-picker" spellcheck="false"><input type="text" class="form-control popup-bg-color" name="popup-bg-color" id="bs-color" value="#ffffff"></span>';
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Dimentions','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Width</span>';
									$output .= '</span>';
									$output .= '<input name="popup_width" id="popup_width" class="form-control" value="50">';
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">%</span>';
									$output .= '</span>';
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Height</span>';
									$output .= '</span>';
									$output .= '<input name="popup_height" id="popup_height" class="form-control" value="80">';
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">%</span>';
									$output .= '</span>';
									

								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Animation','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">On Open</span>';
									$output .= '</span>';
									$output .= '<select id="popup_open_animation" class="form-control" name="popup_open_animation">
															  <option selected="selected" value="fadeInDown">Default (fadeInDown)</option>
															  <option value="none">No Animation</option>
																	<optgroup label="Attention Seekers">
																	  <option value="bounce">bounce</option>
																	  <option value="flash">flash</option>
																	  <option value="pulse">pulse</option>
																	  <option value="rubberBand">rubberBand</option>
																	  <option value="shake">shake</option>
																	  <option value="swing">swing</option>
																	  <option value="tada">tada</option>
																	  <option value="wobble">wobble</option>
																	  <option value="jello">jello</option>
																	</optgroup>
															
																	<optgroup label="Bouncing Entrances">
																	  <option value="bounceIn">bounceIn</option>
																	  <option value="bounceInDown">bounceInDown</option>
																	  <option value="bounceInLeft">bounceInLeft</option>
																	  <option value="bounceInRight">bounceInRight</option>
																	  <option value="bounceInUp">bounceInUp</option>
																	</optgroup>
													
															
													
															<optgroup label="Fading Entrances">
															  <option value="fadeIn">fadeIn</option>
															  <option value="fadeInDown">fadeInDown</option>
															  <option value="fadeInDownBig">fadeInDownBig</option>
															  <option value="fadeInLeft">fadeInLeft</option>
															  <option value="fadeInLeftBig">fadeInLeftBig</option>
															  <option value="fadeInRight">fadeInRight</option>
															  <option value="fadeInRightBig">fadeInRightBig</option>
															  <option value="fadeInUp">fadeInUp</option>
															  <option value="fadeInUpBig">fadeInUpBig</option>
															</optgroup>
													
															
															<optgroup label="Flippers">
															  <option value="flip">flip</option>
															  <option value="flipInX">flipInX</option>
															  <option value="flipInY">flipInY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedIn">lightSpeedIn</option>
															</optgroup>
													
															<optgroup label="Rotating Entrances">
															  <option value="rotateIn">rotateIn</option>
															  <option value="rotateInDownLeft">rotateInDownLeft</option>
															  <option value="rotateInDownRight">rotateInDownRight</option>
															  <option value="rotateInUpLeft">rotateInUpLeft</option>
															  <option value="rotateInUpRight">rotateInUpRight</option>
															</optgroup>
													
															
													
															<optgroup label="Sliding Entrances">
															  <option value="slideInUp">slideInUp</option>
															  <option value="slideInDown">slideInDown</option>
															  <option value="slideInLeft">slideInLeft</option>
															  <option value="slideInRight">slideInRight</option>
													
															</optgroup>
															
															
															<optgroup label="Zoom Entrances">
															  <option value="zoomIn">zoomIn</option>
															  <option value="zoomInDown">zoomInDown</option>
															  <option value="zoomInLeft">zoomInLeft</option>
															  <option value="zoomInRight">zoomInRight</option>
															  <option value="zoomInUp">zoomInUp</option>
															</optgroup>
															
															
													
															<optgroup label="Specials">
															  <option value="rollIn">rollIn</option>
															</optgroup>
														  </select>';
									
								$output .= '</div>';
							$output .= '</div>';
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>&nbsp;</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">On Close</span>';
									$output .= '</span>';
									$output .= '<select id="popup_close_animation" class="form-control" name="popup_close_animation">
															  <option selected="selected" value="fadeOutUp">Default (fadeOutUp)</option>
															  <option value="none">No Animation</option>
																	
													
															<optgroup label="Bouncing Exits">
															  <option value="bounceOut">bounceOut</option>
															  <option value="bounceOutDown">bounceOutDown</option>
															  <option value="bounceOutLeft">bounceOutLeft</option>
															  <option value="bounceOutRight">bounceOutRight</option>
															  <option value="bounceOutUp">bounceOutUp</option>
															</optgroup>
													
															
													
															<optgroup label="Fading Exits">
															  <option value="fadeOut">fadeOut</option>
															  <option value="fadeOutDown">fadeOutDown</option>
															  <option value="fadeOutDownBig">fadeOutDownBig</option>
															  <option value="fadeOutLeft">fadeOutLeft</option>
															  <option value="fadeOutLeftBig">fadeOutLeftBig</option>
															  <option value="fadeOutRight">fadeOutRight</option>
															  <option value="fadeOutRightBig">fadeOutRightBig</option>
															  <option value="fadeOutUp">fadeOutUp</option>
															  <option value="fadeOutUpBig">fadeOutUpBig</option>
															</optgroup>
													
															<optgroup label="Flippers">
															  <option value="flipOutX">flipOutX</option>
															  <option value="flipOutY">flipOutY</option>
															</optgroup>
													
															<optgroup label="Lightspeed">
															  <option value="lightSpeedOut">lightSpeedOut</option>
															</optgroup>
													
															
													
															<optgroup label="Rotating Exits">
															  <option value="rotateOut">rotateOut</option>
															  <option value="rotateOutDownLeft">rotateOutDownLeft</option>
															  <option value="rotateOutDownRight">rotateOutDownRight</option>
															  <option value="rotateOutUpLeft">rotateOutUpLeft</option>
															  <option value="rotateOutUpRight">rotateOutUpRight</option>
															</optgroup>
													
															
															<optgroup label="Sliding Exits">
															  <option value="slideOutUp">slideOutUp</option>
															  <option value="slideOutDown">slideOutDown</option>
															  <option value="slideOutLeft">slideOutLeft</option>
															  <option value="slideOutRight">slideOutRight</option>
															  
															</optgroup>
															
															
															
															<optgroup label="Zoom Exits">
															  <option value="zoomOut">zoomOut</option>
															  <option value="zoomOutDown">zoomOutDown</option>
															  <option value="zoomOutLeft">zoomOutLeft</option>
															  <option value="zoomOutRight">zoomOutRight</option>
															  <option value="zoomOutUp">zoomOutUp</option>
															</optgroup>
													
															<optgroup label="Specials">
															  <option value="hinge">hinge</option>
															  <option value="rollOut">rollOut</option>
															</optgroup>
														  </select>';
									
									

								$output .= '</div>';
							$output .= '</div>';
							
							
							
							
							$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('Extra Padding (in %)','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									//LEFT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Left</span>';
									$output .= '</span>';
									$output .= '<input name="popup_padding_left" id="popup_padding_left" class="form-control" value="2">';
									
									//RIGHT
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Right</span>';
									$output .= '</span>';
									$output .= '<input name="popup_padding_right" id="popup_padding_right" class="form-control" value="2">';
									
									

								$output .= '</div>';
							$output .= '</div>';
					
				
						$output .= '<div class="field-setting col-xs-6 s-all">';
								$output .= '<small>'.__('&nbsp;','nex-forms').'</small>';
									
								$output .= '<div class="input-group input-group-sm">';	
									
									//TOP
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Top</span>';
									$output .= '</span>';
									$output .= '<input name="popup_padding_top" id="popup_padding_top" class="form-control" value="2">';
									
									//BOTTOM
									$output .= '<span class="input-group-addon">';
										$output .= '<span class="icon-text">Bottom</span>';
									$output .= '</span>';
									$output .= '<input name="popup_padding_bottom" id="popup_padding_bottom" class="form-control" value="2">';
									
									
								$output .= '</div>';
							$output .= '</div>';
							
							
				
				$output .= '</div>';
				
		
			
			
			
			
			
			
			
			
			
			
			
$output .= '</div>';	
		
		
		echo $output;
		}
	
	
	}
}


/*************************************************************************************
 *	Add our shortcode button to the editor
 *************************************************************************************/
 
//Creating TinyMCE buttons
//********************************************************************
function NEXFormsadd_editor_button() {
        add_filter('mce_external_plugins', 'NEXFormsadd_custom');
        add_filter('mce_buttons', 'NEXFormsregister_button');
} 

//add action is a wordpress function, it adds a function to a specific action...
//in this case the function is added to the 'init' action. Init action runs after wordpress is finished loading!
add_action('init', 'NEXFormsadd_editor_button');


//Add button to the button array.
function NEXFormsregister_button($buttons) {
   //Use PHP 'array_push' function to add the columnThird button to the $buttons array
   array_push($buttons, "nf_tags_button");
   //Return buttons array to TinyMCE
   return $buttons;
} 

//Add custom plugin to TinyMCE - returns associative array which contains link to JS file. The JS file will contain your plugin when created in the following step.
function NEXFormsadd_custom($plugin_array) {
       $plugin_array['nf_tags_button'] = plugins_url('/admin/js/'.NF_PATH.'editor.js',dirname(dirname(__FILE__)));
       return $plugin_array;
}
