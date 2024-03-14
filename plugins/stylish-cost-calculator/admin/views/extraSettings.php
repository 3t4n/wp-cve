<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
?>
<!-- FOR LATER -->

<div class="row mt-2 scc-no-gutter">
	<div class="scc-calculator-settings-bottom scc-col-xs-12 scc-col-md-7 scc-col-lg-7 clearfix">
		<!-- START CALCULATOR SETTINGS SECTION -->
		<div class="editing-action-cards action-quoteform scc-calc-settings-bottom mb-0 py-0">
			<div class="row gx-0">
				<i class="material-icons col-md-1 scc-vcenter" style="width: 3.33%">keyboard_arrow_right</i>
				<div id="calc-settings-accordion" class="card-content mb-0 col-md-11">
					<h3>Settings</h3>
				</div>
			</div>
			<div class="card-action-btns mx-3 mb-3 d-none">
				<p>Customize your settings from the options below.</p>
				<div class="payment-options-wrapper p-2 mb-3">
				<div class="col-xs-12 col-md-12 col-lg-12">
		<button id="btn_dfscc_tab_font_settings_" class="btn btn-cards" data-bs-toggle="modal" data-bs-target="#settingsModal">
			<span>Font Settings</span>
		</button>
		<button id="btn_dfscc_tab_calculator_" class="btn btn-cards" data-bs-toggle="modal" data-bs-target="#settingsModal1">
			<span>Calculator Settings</span>
		</button>
		<button id="btn_dfscc_tab_translations_" class="btn btn-cards"  data-bs-toggle="modal" data-bs-target="#settingsModal2">
			<span>Wordings</span>
		</button>
		<a href="<?php echo admin_url( 'admin.php?page=scc-coupons-management' ); ?>">
			<button class="btn btn-cards">
				<span>Coupon Codes</span>
				<span class="material-icons-outlined">navigate_next</span>
			</button></a>
		<!-- CHANGE STATIC LINK -->            

	</div>
				</div>
			</div>
		</div>
		<!-- END CALCULATOR SETTINGS SECTION -->
		<!-- QUOTE FORM SECTION -->
		<div class="editing-action-cards action-quoteform scc-quote-form-settings mb-0 py-0">
			<div class="row gx-0">
				<i class="material-icons col-md-1 scc-vcenter" style="width: 3.33%">keyboard_arrow_right</i>
				<div class="card-content mb-0 col-md-11">
					<h3>Email Quote | Form Builder</h3>
				</div>
			</div>
			<div class="card-action-btns mx-3 mb-3 d-none
			<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled use-tooltip-child-nodes';
            }
?>
			">
				<div class="btns-container d-inline-block">
				<?php foreach ( $formFieldsArray as $fieldIndex => $fieldValue ) { ?>
					<?php
        $fieldKey   = array_keys( $fieldValue )[0];
				    $fieldProps = $fieldValue[ $fieldKey ];
				    ?>
					<button class="btn btn-cards disabled" data-btn-fieldtype="custom" data-field-key="<?php echo esc_attr( $fieldKey ); ?>">
						<span><?php echo esc_attr( $fieldProps['name'] ); ?></span>
						<i class="scc-icon-formbuilder material-icons" data-form-builder-action-type="edit" onclick="console.log">edit</i>
					</button>
				<?php } ?>
				</div>
				<button class="btn btn-cards btn-plus 
				<?php
                if ( $isSCCFreeVersion ) {
                    echo 'disabled';
                }
?>
				" data-btn-fieldtype="more-fields" onclick="doFormFieldsSetup(this, event, <?php echo $isSCCFreeVersion ? 'false' : 'true'; ?>)">
					<span class="material-icons">done</span>+
				</button>
				
				<div class="scc-form-checkbox" style="margin: 10px 0 0 0">
				<label class="scc-accordion_switch_button" for="toggle-build-quote">
					<input type="checkbox" id="toggle-build-quote" 
					<?php
    echo $ShowFormBuilderOnDetails ? 'checked' : '';

if ( $isSCCFreeVersion ) {
    echo 'disabled';
}
?>
					 onchange="toggleFormBuilderOnDetails(this)">
					<span class="scc-accordion_toggle_button round"></span>
				</label>
				<span><label for="toggle-build-quote" class="lblExtraSettingsEditCalc" data-setting-tooltip-type="require-acceptance-tt" data-bs-original-title="" title="">Require acceptance (GDPR/Terms & Conditions)
				<i class="material-icons-outlined with-tooltip"  style="margin-right:5px">help_outline</i></label>
				</span>
			</div>
			</div>
		</div>
		<!-- END FORM SECTION -->
		<!-- Start Payment processing section -->
		<div class="editing-action-cards action-payment scc-payment-settings mb-0 py-0">
			<div class="row gx-0">
				<i class="material-icons col-md-1 scc-vcenter" style="width: 3.33%">keyboard_arrow_right</i>
				<div class="card-content mb-0 col-md-11">
					<h3>Payment Options</h3>
				</div>
			</div>
			<div class="card-action-btns mx-3 d-none has-checkmark
			<?php
            /* if ( $isSCCFreeVersion ) {
                echo 'use-tooltip-child-nodes';} */
?>
			"
>
			<div class="d-flex mb-3 scc-payment-methods">
				<button class="btn btn-cards me-3 <?php echo $isPayPalEnabled ? 'active' : ''; ?>" onclick="doPaypalSetupModal(<?php echo intval( $f1->id ); ?>)" data-setting-tooltip-type="payment-option-paypal-tt" data-bs-original-title="" title=""><span class="material-icons">done</span>Paypal</button>
				<button class="btn btn-cards me-3 <?php echo $isStripeEnabled ? 'active' : ''; ?>" onclick="<?php echo $isStripeSetupDone ? 'toggleStripe(this)' : 'stripeOptionsModal(this)'; ?>" data-setting-tooltip-type="payment-option-stripe-tt"  data-bs-original-title="" title=""  <?php echo $isStripeSetupDone ? esc_attr( $stripeDataAttr ) : ''; ?>><span class="material-icons">done</span><span>Stripe</span></button>
				<button class="btn btn-cards me-3 
								 <?php
                        if ( ! $isSCCFreeVersion ) {
                            if ( ! $isWoocommerceActive ) {
                                echo 'disabled tooltipadmin-right';
                            }
                        }

                        if ( $isWoocommerceCheckoutEnabled ) {
                            echo 'active';
                        }
?>
												" onclick="javascript:(function($this) {if (!$this.classList.contains('disabled')) setWoocommerceCheckoutStatus($this.classList.toggle('active'))})(this)" 
												<?php
            if ( ! $isSCCFreeVersion ) {
                if ( ! $isWoocommerceActive ) {
                    echo "data-tooltip='Please enable woocommerce'";
                }
            }
?>
												 data-setting-tooltip-type="payment-option-woocommerce-tt"  data-bs-original-title="" title=""><span class="material-icons">done</span>Woocommerce</button>
			</div>
	 
												 <div class="scc-form-checkbox	scc-email-quote-before-checkout" style="margin: 10px 0 0 0" >
				<label class="scc-accordion_switch_button" for="force-email-quote">
					<input 
					<?php
                    if ( $isSCCFreeVersion ) {
                        echo 'disabled';
                    }
?>
					 type="checkbox" id="force-email-quote" <?php echo $isForceQuoteFormEnabled ? 'checked' : ''; ?> onchange="setForceQuoteFormStatus(this, event)">
					<span class="scc-accordion_toggle_button round"></span>
				</label>
				<span><label for="force-email-quote" class="lblExtraSettingsEditCalc" data-setting-tooltip-type="force-email-form-before-checkout-tt" data-bs-original-title="" title="">Force Email Form before Checkout
						<i class="material-icons-outlined with-tooltip"  style="margin-right:5px">help_outline</i>
					</label>						
				</span>
			</div>
			</div>

		</div><!-- End Payment processing section -->
		<!-- Start New save section -->
		<div class="editing-action-cards action-save">
			<div class="card-action-btns">
				<div class="d-inline-block scc-save-btn-cont" data-setting-tooltip-type="" data-bs-original-title="">
					<button class="btn btn-cards-primary d-inline-flex align-items-center scc-bottom-save-btn" onclick="saveDataFields()" style="background-color:#314AF3;color:white">
						<i class="scc-btn-spinner scc-save-btn-spinner scc-d-none"></i>Save
					</button>
				</div>
				<button class="btn btn-cards 
				<?php
                if ( $isSCCFreeVersion ) {
                    echo 'use-premium-tooltip';
                }
?>
				" onclick="downloadBackup(<?php echo $isSCCFreeVersion ? 'false' : 'true'; ?>)">Backup</button>
				<a href="javascript:void(0)">
					<button class="btn btn-cards use-premium-tooltip">Restore</button>
				</a>
			</div>
			<!-- helper elements -->
			<a id="downloadAnchorElem"></a>
		</div><!-- End New save section -->
	</div>
</div>
<div id="yourNameModal" style="display:none">
	<h4 style="font-weight: bolder;">Add New Field</h4>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Field Name</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Field Description</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Field Type</label>
		<select name="form-field-type" class="df-scc-eui-Select" aria-label="Use aria labels when no actual label is in use">
			<option value="0">Select A Type</option>
			<option value="date">Date</option>
			<option value="address">Address</option>
			<option value="phone">Phone</option>
			<option value="text" selected="">Text</option>
			<option value="email">Email</option>
		</select>
	</div>
	<div class="scc-form-checkbox">
		<input type="checkbox" name="is-mandatory"><label class="df-scc-euiFormLabel df-scc-euiFormRow__label" for="is-mandatory">Make Mandatory</label>
	</div>
	<div class="row">
		<div class="btn-group col-md-12 justify-content-end">
			<button class="btn " onclick="sssclose(this)">Cancel</button>
			<button class="btn " onclick="sssclose(this)" style="background-color: #006BB4;color:white">Save</button>
		</div>
	</div>
</div>
<div id="addNewFieldModal" style="display:none" class="fade in" role="dialog">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z">
					</path>
				</svg></button>
			<form class="df-scc-euiModal__flex" onsubmit="addOrUpdateFormField(event, this)">
				<div class="df-scc-euiModalHeader">
					<div class="df-scc-euiModalHeader__title">Add New Field</div>
				</div>
				<div class="df-scc-euiModalBody">
					<div class="df-scc-euiModalBody__overflow">
						<div class="df-scc-euiText df-scc-euiText--medium">
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label">Field Name</label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper">
											<input type="text" name="field_name" class="df-scc-euiFieldText">
										</div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">This field cannot be
										empty!</span>
								</div>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label">Field
										Description</label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper"><input type="text" name="field_description" class="df-scc-euiFieldText"></div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">This field cannot be
										empty!</span>
								</div>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label">Field Type</label>
								</div>
								<div class="df-scc-eui-FormControlLayout__childrenWrapper"><select name="form-field-type" class="df-scc-eui-Select" aria-label="Use aria labels when no actual label is in use">
										<option value="0">Select A Type</option>
										<option value="date">Date</option>
										<option value="address">Address</option>
										<option value="phone">Phone</option>
										<option value="text" selected="">Text</option>
										<option value="email">Email</option>
									</select>
									<div class="df-scc-eui-FormControlLayoutIcons df-scc-eui-FormControlLayoutIcons--right">
										<span class="df-scc-eui-FormControlLayoutCustomIcon"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-eui-Icon df-scc-eui-Icon--medium df-scc-eui-FormControlLayoutCustomIcon__icon" focusable="false" role="img" aria-hidden="true">
												<path fill-rule="non-zero" d="M13.069 5.157L8.384 9.768a.546.546 0 01-.768 0L2.93 5.158a.552.552 0 00-.771 0 .53.53 0 000 .759l4.684 4.61c.641.631 1.672.63 2.312 0l4.684-4.61a.53.53 0 000-.76.552.552 0 00-.771 0z">
												</path>
											</svg></span>
									</div>
								</div>
								<span class="text-danger" style="display: none; font-size: .75rem;">Please choose a field
									type!</span>
							</div>
							<div class="scc-form-checkbox">
								<input type="checkbox" name="is-mandatory"><label class="df-scc-euiFormLabel df-scc-euiFormRow__label" for="is-mandatory">Make
									Mandatory</label>
							</div>
						</div>
						<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
					</div>
				</div>
				<div class="df-scc-euiModalFooter">
					<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" type="button" data-dismiss="modal"><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">Cancel</span></span>
					</button>
					<button class="df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill" type="submit">
						<span class="df-scc-euiButtonContent df-scc-euiButton__content" style="background-color:#006BB4;border-radius:3px">
							<span class="trn df-scc-euiButton__text">Add</span>
						</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- user survey modal, initiates if the editing page has been used more than 9 times -->
<div class="modal df-scc-modal fade in" id="user-scc-sv" style="padding-right: 0px; display: none;" role="dialog">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
			<button onclick="sccSkipFeedbackModal()" class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal" aria-label="Closes this modal window">
				<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"></path>
				</svg>
			</button>
			<div class="df-scc-euiModal__flex">
				<div class="step1-wrapper">
					<div class="df-scc-euiModalHeader d-block pb-0">
						<div class="progress w-25">
							<div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
							<div class="progress-bar bg-secondary" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="df-scc-euiModalHeader__title pt-2">Rate your experience with our product...</div>
					</div>
					<div class="df-scc-euiModalBody">
						<div class="df-scc-euiModalBody__overflow d-flex align-items-center pb-0">
								<ul class="pagination pagination-lg me-3 mb-0 ratings-picker">
									<li class="page-item me-2">
										<span class="page-link text-dark" role="button">1</span>
									</li>
									<li class="page-item me-2"><span class="page-link text-dark" role="button">2</span></li>
									<li class="page-item me-2"><span class="page-link text-dark" role="button">3</span></li>
									<li class="page-item me-2"><span class="page-link text-dark" role="button">4</span></li>
									<li class="page-item"><span class="page-link text-dark" role="button">5</span></li>
								</ul>
								<p><i style="vertical-align: sub;" class="material-icons-outlined text-info">star</i>&nbsp;<span>Stars</span></p>
						</div>
					</div>
					<div class="df-scc-euiModalFooter"></div>
				</div>
				<div class="step2-wrapper d-none">
					<div class="df-scc-euiModalHeader d-block pb-0">
						<div class="progress w-25">
							<div class="progress-bar bg-secondary" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
							<div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="pt-2 d-flex align-items-center justify-content-between">
							<div class="df-scc-euiModalHeader__title">Anything that can be improved?</div>
							<p><i style="vertical-align: sub;" class="material-icons-outlined text-info">star</i>&nbsp;<span class="rating-chosen">5</span></p>
						</div>
					</div>
					<div class="df-scc-euiModalBody">
						<div class="df-scc-euiModalBody__overflow d-block align-items-center pb-0">
							<div class="">
								<textarea id="comments-text-input" class="form-control h-auto" placeholder="Your feedback (optional)" rows="4"></textarea>
							</div>
							<div class="form-group" id="survey-email-input-wrapper">
								<label for="feedback-email-input">Your email address (optional)</label>
								<input id="feedback-email-input" class="form-control" value="<?php echo esc_attr( get_option( 'df_scc_emailsender', get_option( 'admin_email' ) ) ); ?>" >
							</div>
							<div class="scc-form-checkbox">
								<label class="scc-accordion_switch_button" for="feedback-opt-in">
									<input onchange="document.querySelector('#survey-email-input-wrapper').classList.toggle('d-none')" checked type="checkbox" id="feedback-opt-in">
									<span class="scc-accordion_toggle_button round"></span>
								</label>
								<span><label for="feedback-opt-in" class="lblExtraSettingsEditCalc">I don't mind receiving a reply by email.</label>
								</span>
							</div>
							<div class="">
								<div id="comments-submit-btn" class="btn btn-primary">Submit</div>
							</div>
						</div>
					</div>
					<div class="df-scc-euiModalFooter"></div>
				</div>
				<div class="step3-wrapper d-none">
					<div class="df-scc-euiModalHeader d-block mb-0">
						<div class="df-scc-euiModalHeader__title">
							<i style="vertical-align: sub;" class="material-icons-outlined bg-info rounded">check</i>
							<span>Thanks for the feedback!</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.modal -->
<!-- placeholder for editing existing for field. This div will be populated by template rendering -->
<div id="editFieldModal" style="display:none" class="fade in" role="dialog"></div>
<div id="paypalSetupModal" style="display:none" class="fade in" role="dialog"></div>
<div id="stripe_opts_modal" style="display:none" class="fade in" role="dialog"></div>
