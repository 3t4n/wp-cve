<?php
trait shortcodeConstructor{
	
	public function solwzd_openWrapper(){
		return '<div class="sw_form_wrapper">';
	}
	
	public function solwzd_closeWrapper(){
		return '</div>';
	}
	
	public function solwzd_wizardSelection($atts){
		$content = '
		<div class="wizard_selection_card">
			<form class="select_wizard_form" id="'.$atts['select_wizard_form_id'].'">
				<div class="solwzd-container">
					<div class="form-card">
						<div class="solwzd-row">
							<div class="solwzd-col-md-12">
								<div class="text-title">';
									$sw_wizard_logo = get_option( 'sw_wizard_logo' );
									if( ! $sw_wizard_logo ) {
										$sw_wizard_logo = 'Please set the wizard Logo.';
									} else {
										$sw_wizard_logo = '<img src="'.$sw_wizard_logo.'" class="wizard-logo" alt="Wizard Logo" />';
									}
									$content .= $sw_wizard_logo.'
									<h3>'.get_option( 'sw_wizard_title' ).'</h3>
									<h4>Pick an assessment type & enter your name</h4>
								</div>
							</div>
						</div>
						<div class="solwzd-row">
							<div class="solwzd-col-md-3 solwzd-offset-md-3 solwzd-col-sm-6 solwzd-col-6">
								<div class="box option">
									<div class="radio-round">
										<input type="radio" class="wizard_selection" id="ws1" height="50px" name="wizard_selection" value="'.$atts['form_id_lite'].'"  />
										<label for="ws1"></label>
									</div>
									<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/QuickIcon.svg" onload="SVGInject(this)" alt="Icon" /></div>
									<h3>Quick</h3>
								</div>
							</div>
							<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
								<div class="box option">
									<div class="radio-round">
										<input type="radio" class="wizard_selection" id="ws2" height="50px" name="wizard_selection" value="'.$atts['form_id_full'].'" />
										<label for="ws2"></label>
									</div>
									<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/ThoroughIcon.svg" onload="SVGInject(this)" alt="Icon" /></div>
									<h3>More thorough</h3>
								</div>
							</div>
						</div>
						<div class="selw-error"></div>
						<div class="solwzd-row">
							<div class="solwzd-col-md-6 solwzd-offset-md-3 solwzd-col-sm-12 solwzd-col-12">
								<div class="input-box sel-wizard-space-box">
									<div class="sel-input-box">
										<input type="text" placeholder="First Name" name="select_wizard_fname" />
									</div>
									<div class="sel-input-box">
										<input type="text" placeholder="Last Name" name="select_wizard_lname" />
									</div>
									<button type="button" class="select-wizard-btn arrow-btn full-wizard lite-wizard"></button>
								</div>
							</div>
						</div>	
						<div class="selwname-error"></div>
					</div>	
				</div>
			</form>
		</div>';
					
		return $content;
		//<input type="button" name="next" class="select_wizard action-button" value="Select" />
	}
	
	//Open the form tag
	public function solwzd_openForm($atts){
		$openForm = '<form id="'.$atts['form_id_full'].'" class="sw_form '.$atts['class_full'].'" action="" method="POST" enctype="multipart/form-data"><input type="hidden" name="form_used" value="Full Wizard" />';
		if(get_option('sw_google_autocomplete_address') != ''){
				$openForm .= '<input type="hidden" class="sw_google_autocomplete_address" value="'.$atts['address_id_full'].'" />';
			}
		return $openForm;
	}
	
	public function solwzd_openFormLite($atts){
		$openForm = '<form id="'.$atts['form_id_lite'].'" class="sw_form '.$atts['class_lite'].'" action="" method="POST" enctype="multipart/form-data"><input type="hidden" name="form_used" value="Lite Wizard" />';
		if(get_option('sw_google_autocomplete_address') != ''){
				$openForm .= '<input type="hidden" class="sw_google_autocomplete_address" value="'.$atts['address_id_lite'].'" />';
			}
		return $openForm;
	}
	
	//Close the form tag
	public function solwzd_closeForm(){
		$closeForm = '</form>';
		return $closeForm;
	}
	
	//create dynamic style
	public function solwzd_createStyle(){
		$style ='<style>
		.sw_form_wrapper svg, .sw_form_wrapper svg *{
			fill: '.get_option( 'sw_primary_color' ).';
		}
		form.sw_form p, form.sw_form h2, form.sw_form h3, .system-result .flexbox.no-gap .flex-box, .system-result .flexbox,
.sw_form_wrapper .box h3, .sw_form_wrapper .form-card{
			color: '.get_option( 'sw_secondary_color' ).';
		}
		.sw_form svg polygon{
			stroke: '.get_option( 'sw_primary_color' ).';
		}
		label.bolder, .sw_form fieldset .fixed span, .system-result .top{
			color: '.get_option( 'sw_primary_color' ).';
		}
		.sw_form_wrapper, .sw_form .box, .sw_form div.slider-range, .system-result .flexbox, .system-result .flexbox.no-gap .flex-box, .sw_form_wrapper input[type=text], .sw_form_wrapper input[type=email], .sw_form_wrapper input[type=number], .sw_form_wrapper input[type=tel], .sw_form_wrapper textarea, .sw_form_wrapper .box {
			border-color: '.get_option( 'sw_primary_color' ).';
		}
		.sw_form_wrapper .button-arrow-right, .arrow-btn:after{
			border-left-color: '.get_option( 'sw_primary_color' ).';
		}
		.sw_form .loader{
			border-top-color: '.get_option( 'sw_primary_color' ).';
		}
		.sw_form  .action-button, .sw_form div.slider-range, .sw_form .progressbar li.active{
			    background: '.get_option( 'sw_primary_color' ).';
		}
		.sw_form  .action-button:hover, .sw_form  .action-button:focus{
			box-shadow: 0 0 0 2px white, 0 0 0 3px '.get_option( 'sw_primary_color' ).';
		}
		.sw_form input[type=text],
		.sw_form input[type=email],
		.sw_form input[type=number],
		.sw_form input[type=tel],
		.sw_form textarea{
			border-color: '.get_option( 'sw_primary_color' ).' !important;
		}
		'.get_option( 'sw_custom_css' ).'
		</style>';
		return $style;
	}
	
	//create dynamic style
	public function solwzd_setHiddenFields($atts){
		$fields ='
			<input type="hidden" class="sw_currency_symbol" value="'.$this->solwzd_get_sw_currency_symbol(get_option( 'sw_currency_symbol' )[0]).'" />
			<input type="hidden" name="firstname" value="" />
			<input type="hidden" name="lastname" value="" />
			<input type="hidden" name="username" value="" />
			<input type="hidden" class="sw_quote_id" name="sw_quote_id" value="" />
			<input type="hidden" class="sw_battery_price" value="" />
			
			<input type="hidden" name="panel_required" value="" />
			<input type="hidden" name="system_size" value="" />
			<input type="hidden" name="potential_savings" value="" />
			<input type="hidden" name="storage_battery" value="" />
			
			<input type="hidden" name="system_cost" value="" />
			<input type="hidden" name="incentive" value="" />
			<input type="hidden" name="net_cost" value="" />
			<input type="hidden" name="payback_period" value="" />
			<input type="hidden" name="utility_bill_per_month" value="" />';
			
			if(isset(get_option( 'sw_email_enable_admin_notification' )[0])){
				$sw_email_enable_admin_notification = get_option( 'sw_email_enable_admin_notification' )[0];
			} else {
				$sw_email_enable_admin_notification = '';
			}

			if(isset(get_option( 'sw_email_enable_user_notification' )[0])){
				$sw_email_enable_user_notification = get_option( 'sw_email_enable_user_notification' )[0];
			} else {
				$sw_email_enable_user_notification = '';
			}
			
			$fields .= '<input type="hidden" class="half_email_sent" value="0" />
			<input type="hidden" class="sw_email_enable_admin_notification" value="'.$sw_email_enable_admin_notification.'" />
			<input type="hidden" class="sw_email_enable_user_notification" value="'.$sw_email_enable_user_notification.'" />';
			
			if($atts['battery_step'] == false){
				$fields .= '<input type="radio" checked class="hidden" name="battery_storage" value="solar_pv" />';
			}
			
			if($atts['property_type_step'] == false){
				$fields .= '<div class="dy-error hidden"></div><input type="radio" checked class="hidden" name="describe_you" value="'.$atts['property_type'].'" />';
			}
		return $fields;
	}
	
	public function solwzd_stepCard(){
		$content = '<fieldset class="-1 wizard_step_logo">
						<div class="form-card">
							<div class="text-center"> ';
							$sw_wizard_logo = get_option( 'sw_wizard_logo' );
							if( ! $sw_wizard_logo ) {
								$sw_wizard_logo = 'Please set the wizard Logo.';
							} else {
								$sw_wizard_logo = '<img src="'.$sw_wizard_logo.'" alt="Wizard Logo" />';
							}
							$content .= $sw_wizard_logo.'
							
							<h3>'.get_option( 'sw_wizard_title' ).'</h3>
							</div>
						</div> <input type="button" name="next" class="next action-button home-btn" value="Start" />
					</fieldset>';
					
		return $content;
	}
	
	public function solwzd_step_zero(){
		$content = '<fieldset class="0">
                                <div class="form-card">
									<div class="text-title text-center"> 
                                    <p>Thanks for learning more about going solar with '.get_option( 'sw_company_name' ).'. Now it\'s time for us to get to know you.</p>
									<h3>What to Expect With Solar Wizard.</h3>
									</div>
									<div class="wizard_options">
										<div class="opts"><div class="icon"><img src="'.plugin_dir_url( __FILE__ ).'../images/icon1.svg" onload="SVGInject(this)" alt="Icon-1" /></div><p>Getting to know you</p></div>
										<div class="opts"><div class="icon"><img src="'.plugin_dir_url( __FILE__ ).'../images/icon2.svg" onload="SVGInject(this)" alt="Icon-2" /></div><p>Calculating System Size & Potential Savings</p></div>
										<div class="opts"><div class="icon"><img src="'.plugin_dir_url( __FILE__ ).'../images/icon3.svg" onload="SVGInject(this)" alt="Icon-3" /></div><p>Going Solar Price Range and Return On Investment.</p></div>
										<div class="opts"><div class="icon"><img src="'.plugin_dir_url( __FILE__ ).'../images/icon4.svg" onload="SVGInject(this)" alt="Icon-4" /></div><p>Upload Utility Bill & Schedule Consultation.</p></div>
									</div>
									<div class="fields">
										<div class="group g-flex">
											<label class="bolder">Let\'s start!</label> 
											<div class="input-box">
												<input type="text" placeholder="Enter your name" name="username" />
												<a href="#" class="trigger-next arrow-btn"><div class="button-arrow-right"></div></a>
												<div class="name-error"></div>
											</div>
										</div>
									</div>
                                </div> <input type="button" name="next" class="next action-button hidden expectation-btn" value="Start" />
                            </fieldset>';
		return $content;
	}
	
	public function solwzd_step_one(){
		$content = '
		<fieldset class="1">
			<!--<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>-->
			<div class="solwzd-container">
				<div class="form-card">
					<div class="solwzd-row">
						<div class="solwzd-col-md-10 solwzd-offset-md-1 solwzd-col-sm-12 solwzd-col-12">
							<div class="text-center text-title"> 
								<p>Hi <span class="firstname"></span>,<br/>Besides saving money on your utility bill, what else motivates you to go solar?<br /><small>(please choose one)</small></p>
							</div>
						</div>		
					</div>				
					<div class="solwzd-row">
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="motivation_option" id="mo1" value="Be more Green" name="motivate_option"  />
									<label for="mo1"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/mo1.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Be more Green</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="motivation_option" id="mo2" value="Use more Power Guilt Free (i.e. AC/Devices)" name="motivate_option"  />
									<label for="mo2"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/mo2.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Use more Power<br />Guilt Free<br />(i.e. AC/Devices)</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="motivation_option" id="mo3" value="Fear of Outages (i.e. Grid Down)" name="motivate_option"  />
									<label for="mo3"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/mo3.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Fear of Outages<br />(i.e. Grid Down)</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="motivation_option" id="mo4" value="Cash in on \'Going Solar\' Incentives" name="motivate_option"  />
									<label for="mo4"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/mo4.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Cash in on \'Going<br />Solar\' Incentives</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="motivation-error"></div>
			</div>
			<button type="button" name="next" class="next action-button motivation-btn inside-button-arrow-right">Next</button>
		</fieldset>';
		return $content;
	}
	
	public function solwzd_step_two(){
		$content = '
		<fieldset class="2 commercial-skip">
			<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>
			<div class="solwzd-container">
				<div class="form-card">
					<div class="solwzd-row">
						<div class="solwzd-col-md-12">
							<div class="text-center text-title"> 
								<p>What best describes you?</p>
							</div>
						</div>
					</div>					
					<div class="solwzd-row">
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="more_about" id="ma1" value="I\'m retired or dreaming of retirement" name="more_about"  />
									<label for="ma1"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/bd1.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>I’m retired or dreaming of retirement</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="more_about" id="ma2" value="I just bought my first home" name="more_about"  />
									<label for="ma2"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/bd2.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>I just bought my first home</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="more_about" id="ma3" value="I\'m ready to start my career/family" name="more_about"  />
									<label for="ma3"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/bd3.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>I’m ready to start my career/family</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="more_about" id="ma4" value="My kids are in college" name="more_about"  />
									<label for="ma4"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/bd4.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>My kids are in college</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="bd-error"></div>
			</div> 
			<button type="button" name="next" class="next action-button describes-btn inside-button-arrow-right">Next</button>
		</fieldset>';
		return $content;
	}
	public function solwzd_step_three(){
		$content = '
		<fieldset class="3">
			<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>
			<div class="solwzd-container">	
				<div class="form-card">
					<div class="solwzd-row">
						<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">
							<div class="text-center text-title"> 
								<p>Fill in the blank. "When picking a solar partner, getting the best <span class="blank">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> is most important to me."<br /><small>(please choose one)</small></p>
							</div>
						</div>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="getting_best" id="fb1" value="Warranty" name="getting_best"  />
									<label for="fb1"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/fb1.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Warranty</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="getting_best" id="fb2" value="Value" name="getting_best"  />
									<label for="fb2"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/fb2.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Value</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="getting_best" id="fb3" value="Technology" name="getting_best"  />
									<label for="fb3"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/fb3.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Technology</h3>
							</div>
						</div>
						<div class="solwzd-col-md-3 solwzd-col-sm-6 solwzd-col-6">
							<div class="box option">
								<div class="radio-round">
									<input type="radio" class="getting_best" id="fb4" value="Company" name="getting_best"  />
									<label for="fb4"></label>
								</div>
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/fb4.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<h3>Company</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="fb-error"></div>
            </div>  
			<button type="button" name="next" class="next action-button important-btn inside-button-arrow-right">Next</button>
        </fieldset>';
		return $content;
	}
	
	public function solwzd_step_four($atts, $form = ''){
		$content = '<fieldset class="4 step-address calculate-panel">
						<button type="button" name="previous" class="previous action-button-previous ';
						if($atts['battery_step'] == false){
							$content .= 'hidden';
						}
						$content .= '"><div class="inside-button-arrow-left"></div></button>
						<div class="solwzd-container">
							<div class="form-card">
							<div class="solwzd-row">
								<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">
									<div class="text-center text-title"> 
										<p>Thanks <span class="firstname"></span>!<br /><span class="hide_wizard_lite">We\'re building your solar persona.</span></p>
										<p>Now, it\'s time to calculate the perfect system size for your <span class="property-type-text">house</span> and find out how much you can save when you go solar.</p>
									</div>
								</div>
							</div>
							
							<div class="solwzd-row">

								<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">';
								if($form == 'Lite'){
									$content .= '<input type="text" placeholder="Enter Address" name="address" id="'.$atts['address_id_lite'].'" />
												<input type="hidden" name="zipcode" class="'.$atts['address_id_lite'].'_geo-zipcode" />';
								} else if($form == 'Full'){
									$content .= '<input type="text" placeholder="Enter Address" name="address" id="'.$atts['address_id_full'].'" />
												<input type="hidden" name="zipcode" class="gzipcode '.$atts['address_id_full'].'_geo-zipcode" />';
								}
								$content .= '
								<div class="solwzd-row"> 
									<div class="solwzd-col-md-12">
										<label class="bolder monthly_bill">Average monthly utility Bill <span class="sym sw_monthly_bill_label">'.$this->solwzd_get_sw_currency_symbol(get_option( 'sw_currency_symbol' )[0]).'</span></label>
										<input type="text" placeholder="" class="sw_monthly_bill hidden" name="monthly_bill" readonly />
										<div class="slider-range"></div>
									</div>
								</div>
								<div class="solwzd-row checkbox text-center">
									<div class="solwzd-col-md-12">
										<label class="flexbox"><input type="checkbox" name="acknowledge" value="true" /> I acknowledge that Solar Wizard is not providing a quote. It is a range to give me an idea of the system size and potential savings.</label>
										<div class="ack-error"></div>
									</div>
								</div>
							</div>
						</div>
					</div>	
				</div> 
				<button type="button" name="next" class="next action-button address-btn inside-button-arrow-right">Calculate</button>
		</fieldset>';
		return $content;
	}
	public function solwzd_step_five(){
		$content = '<fieldset class="5 waiting">
						<div class="solwzd-container">
							<div class="form-card">
								<div class="text-center text-title"> ';
									$sw_wizard_logo = get_option( 'sw_wizard_logo' );
									if( $sw_wizard_logo ) {
										$content .= '<img src="'.$sw_wizard_logo.'" alt="Wizard Logo" />';
									}
									$content .= '<h3>Solar Wizard</h3>
									<p>is calculating your system size</p>
								</div>
								<div class="loader hidden"></div>
							</div>
						</div> 
						<input type="button" name="previous" class="previous action-button-previous hidden" value="Previous" />
						<input type="button" name="next" class="next action-button hidden" value="Calculate" />
                    </fieldset>';
		return $content;
	}
	public function solwzd_step_six(){
		$content = '
		<fieldset class="6 send-email-partial">
			<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>
			<div class="solwzd-container">
				<div class="form-card">
					<div class="solwzd-row">
						<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">	
							<h2 class="text-center">Here are your results!</h2>
						</div>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-10 solwzd-offset-md-1 solwzd-col-sm-12 solwzd-col-12">
							<div class="text-title text-center">
								<p class="solar_pv_storage_msg chioce_msg">To cover your average utility bill, you’ll need:</p>
							</div>
						</div>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-6 solwzd-col-sm-12 solwzd-col-12 solwzd-align-self-center">
							<div class="panel-image">
								<img src="'.get_option( 'sw_panel_image' ).'" alt="Panel" />
							</div>
						</div>
						<div class="solwzd-col-md-6 solwzd-col-sm-12 solwzd-col-12 solwzd-align-self-center">
							<div class="panel-results">
								<label class="bolder">Number of Panels:</label>
								<p class="panel-required"></p>
								<label class="bolder">System size:</label>
								<p class="system-size"></p>
								<div class="b-storage">
									<label class="bolder">Battery:</label>
									<p class="storage-battery"></p>
								</div>
								<div class="cal-potential-savings">
									<label class="bolder">Potential savings:</label>
									<p class="potential-savings"></p>
								</div>';
							$content .= '</div>
						</div>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">
							<div class="solwzd-row">
								<div class="solwzd-col-md-6 solwzd-col-sm-12">
									<input type="text" placeholder="Enter Phone" class="input-enter" name="phone" />
								</div>
								<div class="solwzd-col-md-6 solwzd-col-sm-12">
										<input type="text" placeholder="Enter Email" class="input-enter" name="email" />
								</div>
							</div>
							<div class="solwzd-row text-center">
								<div class="solwzd-col-md-12 solwzd-col-sm-12 checkbox">
									<label class="flexbox">
										<input type="checkbox" checked="checked" name="opt_in" value="true" /> Opt-in to receiving promotional messages. Don\'t worry, we won\'t share it with third parties.
									</label>
								</div>
							</div>
							<div class="solwzd-row text-center">
								<div class="solwzd-col-md-12 solwzd-col-sm-12">
									<button type="button" name="next" class="action-button calculate-cost inside-button-arrow-right">How much<br/>will it cost?</button>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<button type="button" name="next" class="next action-button hidden cost-start-btn inside-button-arrow-right">How much<br/>will it cost?</button>
		</fieldset>';
		return $content;
	}
	public function solwzd_step_eight(){
		$content = '<fieldset class="8 padding-box-top">
		`	<div  class="fixed">
				<p class="offset-change hidden"><span class="offset-value"></span>% offset of your bill.</p>
				<p># of Panels: <span class="panel-required"></span></p>
				<p>System size: <span class="system-size"></span></p>
				<p class="b-storage">Battery: <span class="storage-battery"></span</p>
				<p class="cal-potential-savings">Potential savings: <span class="potential-savings"></span></p>
			</div>
			<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>
			<div class="solwzd-container">
				<div class="form-card">
					<div class="solwzd-row">
						<div class="solwzd-col-md-12">
							<div class="text-center text-title"> 
								<p>How do you plan on aquiring your system?</p>
							</div>
						</div>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">
							<div class="solwzd-row center-row">
								<div class="solwzd-col-md-4 solwzd-col-sm-6 solwzd-col-6">
									<div class="box option">
										<div class="radio-round">
											<input type="radio" class="system_purchase_plan" id="ps1" value="Cash" name="system_purchase_plan"  />
											<label for="ps1"></label>
										</div>
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/ps1.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<h3>Cash Purchase</h3>
									</div>
								</div>
								<div class="solwzd-col-md-4 solwzd-col-sm-6 solwzd-col-6">
									<div class="box option">
										<div class="radio-round">
											<input type="radio" class="system_purchase_plan" id="ps2" value="Finance" name="system_purchase_plan"  />
											<label for="ps2"></label>
										</div>
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/ps2.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<h3>Financing</h3>
									</div>
								</div>
								<div class="solwzd-col-md-4 solwzd-col-sm-6 solwzd-col-6 lease-option hidden">
									<div class="box option">
										<div class="radio-round">
											<input type="radio" class="system_purchase_plan" id="ps3" value="Lease" name="system_purchase_plan"  />
											<label for="ps3"></label>
										</div>
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/ps3.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<h3>Lease</h3>
									</div>
								</div>
							</div>
							<div class="ps-error"></div>';
							$content .= '
						</div> 
					</div>
				</div>
			</div>
			<button type="button" name="next" class="next action-button purchase-type-btn inside-button-arrow-right">Next</button>
		</fieldset>';
		return $content;
	}
	public function solwzd_step_nine($atts, $form = ''){
		$content = '<fieldset class="9 padding-box-top">
					<div  class="fixed">
						<p># of Panels: <span class="panel-required"></span></p>
						<p>System size: <span class="system-size"></span></p>
						<p class="b-storage">Battery: <span class="storage-battery"></span</p>
						<p>Potential savings: <span class="potential-savings"></span></p>
					</div>
					<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>
					<div class="form-card">
						<div class="text-center text-title w-600"> 
							<p>You’re almost there!<br />Confirm your address and we’ll look for local & state solar incentives that you may qualify for.</p>
						</div>
						
						<div class="fields">
							<div class="group text-center">
							';
										if($form == 'Lite'){
											$content .= '<input type="text" placeholder="Address" name="confirmaddress" id="'.$atts['address_id_lite'].'_sw_confirm_address" />';
										} else if($form == 'Full'){
											$content .= '<input type="text" placeholder="Address" name="confirmaddress" id="'.$atts['address_id_full'].'_sw_confirm_address" />';
										}
										$content .= '
								
								<label><input type="checkbox" value="Yes" name="confirm_address_check">
								Confirm your address by checking this box</label>
								<div class="ca-error"></div>
							</div>
							<div class="group">
								<label><input type="checkbox" value="Yes" name="military" /> I\'m in military or a veteran</label>
								<label><input type="checkbox" value="Yes" name="nurse" /> I\'m a nurse or state worker</label>
							</div>
						</div>
						
					</div> <button type="button" name="next" class="next action-button confirm-address-btn inside-button-arrow-right">Next</button>
				</fieldset>';
		return $content;
	}
	public function solwzd_step_ten(){
		$content = '
		<fieldset class="10 padding-box-top calculate-cost-final">
			<div  class="fixed">
				<p class="offset-change hidden"><span class="offset-value"></span>% offset of your bill.</p>
				<p># of Panels: <span class="panel-required"></span></p>
				<p>System size: <span class="system-size"></span></p>
				<p class="b-storage">Battery: <span class="storage-battery"></span</p>
				<p class="cal-potential-savings">Potential savings: <span class="potential-savings"></span></p>
			</div>
			<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>
			<div class="solwzd-container">
				<div class="form-card waiting-card hidden">
					<div class="text-center text-title"> ';
						$sw_wizard_logo = get_option( 'sw_wizard_logo' );
						if( $sw_wizard_logo ) {
							$content .= '<img src="'.$sw_wizard_logo.'" alt="Wizard Logo" />';
						}
						$content .= '<h3>Solar Wizard</h3>
						<p>is calculating range of your system</p>
					</div>
				</div>
				<div class="form-card wait-complete">
					<div class="solwzd-row cash_result system-result hidden">
						<div class="solwzd-col-md-12 solwzd-col-sm-12 solwzd-col-12 solwzd-position-static">
							<div class="top"><img src="'.plugin_dir_url( __FILE__ ).'../images/ps1.svg" onload="SVGInject(this)" alt="Icon" /> Cash</div>
							
							<div class="flexbox">
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icon_Cost.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<div class="res_values">
									<ul>
										<li><p><strong>Gross Cost Range of Your System:</strong> <span class="system-cost">X - Y</span></p></li>
										<li><p><strong>Available Incentives:</strong> <span class="incentive">X - Y</span></p></li>
										<li><p><strong>Net Cost Range of Your System:</strong> <span class="net-cost">X - Y</span></p></li>
									</ul>
									<p><small>*Please consult your tax adviser regarding your individual tax situation and income tax credit eligibility.</small></p>
								</div>
							</div>
						</div>
					</div>
					<div class="solwzd-row financing_result system-result hidden">
						<div class="solwzd-col-md-12 solwzd-col-sm-12 solwzd-col-12 solwzd-position-static">
							<div class="top"><img src="'.plugin_dir_url( __FILE__ ).'../images/ps2.svg" onload="SVGInject(this)" alt="Icon" /> Financing (Loan)</div>
							<div class="flexbox">
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icon_Cost.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<div class="res_values">
									<ul>
										<li><p><strong>Your new electric bill with solar per month :</strong> <span class="utility-bill-per-month">X - Y</span></p></li>
										<li><p><strong>Gross Cost Range of Your System:</strong> <span class="system-cost">X - Y</span></p></li>
										<li><p><strong>Available Incentives:</strong> <span class="incentive">X - Y</span></p></li>
										<li><p><strong>Net Cost Range of Your System:</strong> <span class="net-cost">X - Y</span></p></li>
									</ul>
									<p><small>*Please consult your tax adviser regarding your individual tax situation and income tax credit eligibility.</small></p>
								</div>
							</div>
							<div class="flexbox no-gap">
								<div class="flex-box">
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icons_blk_Term.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<div class="details">
											Term: <br /> <span class="loan_term"></span>
										</div>
								</div>
								<div class="flex-box">
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icons_blk_Rate.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<div class="details">
											Rate: <br /> <span class="loan_rate"></span>
										</div>
								</div>
								<div class="flex-box">
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icons_blk_CreditScore.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<div class="details">
											Credit Score: <br /> <span class="loan_credit_score"></span>
										</div>
								</div>
							</div>
						</div>
					</div>
					<div class="solwzd-row lease_result system-result hidden">
						<div class="solwzd-col-md-12 solwzd-col-sm-12 solwzd-col-12 solwzd-position-static">	
							<div class="top"><img src="'.plugin_dir_url( __FILE__ ).'../images/ps3.svg" onload="SVGInject(this)" alt="Icon" /> Financing (Lease)</div>
							<div class="flexbox">
								<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icon_Cost.svg" onload="SVGInject(this)" alt="Icon" /></div>
								<div class="res_values">
									<ul>
										<li><p><strong>Your new electric bill with solar per month :</strong> <span class="utility-bill-per-month">X - Y</span></p></li>
									</ul>
								</div>
							</div>
							<div class="flexbox no-gap">
								<div class="flex-box">
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icons_blk_Term.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<div class="details">
											Term: <br /> <span class="lease_term"></span>
										</div>
								</div>
								<div class="flex-box">
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icons_blk_Rate.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<div class="details">
											Rate: <br /> <span class="lease_rate"></span>
										</div>
								</div>
								<div class="flex-box">
										<div class="image"><img src="'.plugin_dir_url( __FILE__ ).'../images/Icons_blk_CreditScore.svg" onload="SVGInject(this)" alt="Icon" /></div>
										<div class="details">
											Credit Score: <br /> <span class="lease_credit_score"></span>
										</div>
								</div>
							</div>
						</div>
					</div>
					<div class="result hidden text-center">
						<p><strong>Gross Cost of Your System:</strong> <span class="system-cost">X - Y</span></p>
						<p><strong>Available Incentives:</strong> <span class="incentive">X - Y</span></p>
						<p><strong>Net Cost of System:</strong> <span class="net-cost">X - Y</span></p>
						<p><strong>Payback Period:</strong> <span class="payback-period">X - Y</span> Years</p>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">		
							<div class="text-center max-width-box">
								<div class="group">
									<label class="flexbox"><input type="checkbox" value="Yes" name="military" /> I\'m in military or a veteran</label>
									<label class="flexbox"><input type="checkbox" value="Yes" name="nurse" /> I\'m a nurse or state worker</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="loader hidden"></div>
			</div>
			<button type="button" name="next" class="hidden next action-button schedule-consultant-btn inside-button-arrow-right max-250">Schedule Consultant</button>
			<div class="solwzd-row">
				<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">		
					<div class="wait-complete">
						<div class="learn_more_about_battery text-center hidden">
							<label class="flexbox">
								<input type="checkbox" value="Yes" name="learn_battery_storage" /> I\'d like to learn more about battery storage
							</label>
						</div>
					</div>
				</div>
			</div>
		</fieldset>';
		return $content;
	}
	public function solwzd_step_eleven(){
		$content = '
		<fieldset class="11 padding-box-top final-step-data">
			<div  class="fixed">
				<p class="offset-change hidden"><span class="offset-value"></span>% offset of your bill.</p>
				<p># of Panels: <span class="panel-required"></span></p>
				<p>System size: <span class="system-size"></span></p>
				<p class="b-storage">Battery: <span class="storage-battery"></span</p>
				<p class="cal-potential-savings">Potential savings: <span class="potential-savings"></span></p>
			</div>
			<button type="button" name="previous" class="previous action-button-previous"><div class="inside-button-arrow-left"></div></button>
			<div class="solwzd-container">
				<div class="form-card">
					<div class="solwzd-row">
						<div class="solwzd-col-md-10 solwzd-offset-md-1 solwzd-col-sm-12 solwzd-col-12">
							<div class="text-center text-title"> 
								<h2 class="text-center">Thanks <span class="firstname"></span>!</h3>
								<p>Let us know how you’d like us to communicate with you. You can also upload a photo of your utility bill and electrical panel so we can assess your possibilities.</p>
							</div>
						</div>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-5 solwzd-offset-md-1 solwzd-col-sm-12 solwzd-col-12">
							<div class="group">
								<div class="solwzd-row">
									<div class="solwzd-col-md-12">
										<label>Please select one:</label>
									</div>
								</div>
								<div class="solwzd-row">
									<div class="solwzd-col-md-12">
										<div class="check-box">
											<label>
												<input type="radio" name="communication_method" value="Call"> 
												<img src="'.plugin_dir_url( __FILE__ ).'../images/phone.svg" onload="SVGInject(this)" alt="Icon" />
												Call me
											</label>
										</div>
										<div class="check-box">
											<label>
												<input type="radio" name="communication_method" value="Virtual Meeting"> 
												<img src="'.plugin_dir_url( __FILE__ ).'../images/email.svg" onload="SVGInject(this)" alt="Icon" />
												Virtual meeting / send a link
											</label>
										</div>
										<div class="check-box">
											<label>
												<input type="radio" name="communication_method" value="In-Person"> 
												<img src="'.plugin_dir_url( __FILE__ ).'../images/location.svg" onload="SVGInject(this)" alt="Icon" />
												In person meeting
											</label>
										</div>
										<div class="cm-error"></div>
									</div>
								</div>
							</div>
							<div class="solwzd-row">
								<div class="solwzd-col-md-12">
									<div class="group">
										<input type="text" placeholder="Phone, email or home address" name="communication_details" />
										<em>All personal information is confidential.</em>
									</div>
								</div>
							</div>
						</div>
						<div class="solwzd-col-md-5 solwzd-col-sm-12 solwzd-col-12">
							<div class="solwzd-row">
								<div class="solwzd-col-md-12">
									<div class="group">
										<label>Pick a date</label>
										<input type="text" placeholder="" class="datepicker" autocomplete="none" name="date" />
									</div>
								</div>
							</div>
							<div class="solwzd-row">
								<div class="solwzd-col-md-12">
									<div class="group">
										<label class="upload_file_label"><img src="'.plugin_dir_url( __FILE__ ).'../images/upload.svg" onload="SVGInject(this)" alt="Icon" /><span>upload utility bill<br /> <small>(optional)</small></span></label>
										<input type="file" name="bill[]" class="files-data"/>
										<br /><small>Following file types allowed: <strong>jpg, jpeg, png, bmp, pdf, gif.</strong><br>File size must be <b>less than 3MB.</b></small>
									</div>
								</div>
							</div>
							<div class="solwzd-row">
								<div class="solwzd-col-md-12">
									<div class="group">
										<input type="submit" value="Submit" class="action-button upload-bill-btn" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="solwzd-row">
						<div class="solwzd-col-md-8 solwzd-offset-md-2 solwzd-col-sm-12 solwzd-col-12">
							<div class="clear"></div>
							<div class="loader hidden"></div>
							<p class="msg text-center"></p>
						</div> 
					</div>
				</div>
			</div>
			<input type="button" name="next" class="next action-button hidden final-step" value="Submit" />
        </fieldset>';
		return $content;
	}
	public function solwzd_step_twelve(){
		$content = '
		<fieldset class="12 submit-msg">
			<div class="form-card">
				<div class="text-center">';
					$sw_wizard_logo = get_option( 'sw_wizard_logo' );
					if( $sw_wizard_logo ) {
						$content .= '<img src="'.$sw_wizard_logo.'" alt="Wizard Logo" />';
					}
				
					
					$content.= '<p>Thank you for using <strong>Solar Wizard</strong>.<br />
						Data submitted successfully.<br />
						</p>
					<h3>Thank You<br /><span class="firstname"></span></h3>
				</div>
				<div class="wizard_options">
					<div class="opts">
						<div class="icon"></div>
						<p>Our representative will contact you soon.</p>
					</div>
				</div>
			</div>
		</fieldset>';
		return $content;
	}
		public function solwzd_progressFull($atts){
		$content = '
				
				<!-- progressbar -->
				<div class="sw_progress">
				<div class="step-count">Step <span class="count">1</span>/<span class="count_end"></span></div>
				<ul class="progressbar">
					<li class="active"></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
				</div>
				';
		return $content;
	}
	
	public function solwzd_progressLite($atts){
		$content = '
				
				<!-- progressbar -->
				<div class="sw_progress">
				<div class="step-count">Step <span class="count">1</span>/<span class="count_end"></span></div>
				<ul class="progressbar">
					<li class="active"></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
				</div>
				';
		return $content;
	}

	public function solwzd_get_sw_currency_symbol($code){
		if($code == ''){
			return '&#36;';
		} else {
		$symbols = array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x62f;.&#x639;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => '&#8376;',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRU' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#1088;&#1089;&#1076;',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STN' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		);
		return $symbols[$code];
		}
	}
}

?>