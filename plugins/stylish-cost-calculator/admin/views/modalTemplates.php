<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// TODO move templates used in frontend to some other file
?>
<div class="modal df-scc-modal fade in" id="quote-form-placeholder" style="padding-right: 0px;" role="dialog" data-backdrop="0"></div>
<div class="" id="detail-view-placeholder" style="padding-right: 0px;" role="dialog" data-backdrop="0"></div>
<div class="modal df-scc-modal fade in" id="scc-coupon-modal-placeholder" style="padding-right: 0px;" role="dialog" data-backdrop="0"></div>
<div class="" id="woocommerce-loading-placeholder"></div>
<script type="text/html" id="tmpl-scc-quote-text-field">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z">
					</path>
				</svg></button>
			<form class="df-scc-euiModal__flex" onSubmit="javascript:handleQuoteSubmission({{data.calcId}}, this, '{{data.objectColor}}', {{data.isCaptchaEnabled || false}}, {{data.disableUnitColumn}},{{data.showFormInDetail}})">
				<div class="df-scc-euiModalHeader">
					<div class="df-scc-euiModalHeader__title">{{data.title}}</div>
				</div>
				<div class="df-scc-euiModalBody">
					<div class="df-scc-euiModalBody__overflow">
						<div class="df-scc-euiText df-scc-euiText--medium">
							<# 
								var {calcId}=data;
								var pendingLicense=jQuery('.scc-pending-license').length ? true : false;
								var { quoteFormFields }=JSON.parse(document.getElementById('scc-config-' + calcId).textContent);
								var submitBtnText= sccGetTranslationByKey(calcId, 'Submit') ;
								var cancelBtnText= sccGetTranslationByKey(calcId, 'Cancel') ;
								var fieldsByKey=quoteFormFields.map((e,i)=>
								  {return Object.keys(e)}).flat();
								!pendingLicense && fieldsByKey.forEach(function(fieldKey,index) {
								let fieldData = quoteFormFields[_.findKey(quoteFormFields, fieldKey)][fieldKey];
								#>
								<div class="df-scc-euiFormRow">
									<div class="df-scc-euiFormRow__labelWrapper">
										<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label" for="{{fieldKey}}" data-trn-key="{{fieldData?.trnKey}}">
											<span class="trn" style="vertical-align: middle">{{ sccGetTranslationByKey(data.calcId, fieldData?.name) }}</span>&nbsp;
											<# if (fieldData?.description?.length) { #>
												<i class="material-icons" title="{{fieldData.description}}">
													<svg width="12pt" height="12pt" version="1.1" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
														<path d="m6 1c-2.7617 0-5 2.2383-5 5s2.2383 5 5 5 5-2.2383 5-5-2.2383-5-5-5zm0.5 7.5h-1v-3h1zm0-4h-1v-1h1z" />
													</svg>
												</i>
												<# } #>
										</label>
									</div>
									<div class="df-scc-euiFormRow__fieldWrapper">
										<div class="df-scc-euiFormControlLayout">
											<div class="df-scc-euiFormControlLayout__childrenWrapper">
												<input type="{{fieldData.type}}" name="{{fieldKey}}" class="df-scc-euiFieldText" {{ fieldData?.isMandatory ? 'required' : '' }}>
											</div>
										</div>
									</div>
								</div>
								<# }) #>
									<# if(pendingLicense) { #>
										<p>Please Activate Stylish Cost Calculator Premium</p>
										<# } #>
											<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
						</div>
					</div>
					<# if(!pendingLicense) { #>
						<div class="df-scc-euiModalFooter">
							<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" type="button" data-dismiss="modal"><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">{{cancelBtnText}}</span></span>
							</button>
							<button class="df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill" type="submit">
								<span class="df-scc-euiButtonContent df-scc-euiButton__content">
									<span class="trn df-scc-euiButton__text">{{ sccGetTranslationByKey(data.calcId, submitBtnText) }}</span>
								</span>
							</button>
						</div>
						<div class="df-scc-euiModalFooter errorModalFooter">
							<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" type="button" data-dismiss="modal">
								<span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="df-scc-euiButtonEmpty__text">Close</span></span>
							</button>
							<a href="https://designful.freshdesk.com/support/solutions/articles/48001186269-email-pdf-troubleshooting-issues-with-the-email-quote-and-pdf-generator" target="_blank">
								<button class="df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill" type="button">
									<span class="df-scc-euiButtonContent df-scc-euiButton__content"><span class="df-scc-euiButton__text">Troubleshoot Problem</span></span>
								</button>
							</a>
						</div>
						<# } #>
			</form>
		</div>
	</div>
</script>
<!-- start of edit existing quote form field modal's template. In this template data represents fieldKey -->
<script type="text/html" id="tmpl-scc-quote-form-field-edit">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<# const urlParams=new URLSearchParams(window.location.search); const calcId=urlParams.get('id_form'); var { quoteFormFields }=JSON.parse(document.getElementById('scc-config-' + calcId).textContent); let fieldProps=quoteFormFields.filter(e=> Object.keys(e)[0] == data)[0][data];
			#>
			<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
				<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
						<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z">
						</path>
					</svg></button>
				<form class="df-scc-euiModal__flex" onsubmit="addOrUpdateFormField(event, this)">
					<div class="df-scc-euiModalHeader">
						<div class="df-scc-euiModalHeader__title">Edit Field</div>
					</div>
					<div class="df-scc-euiModalBody">
						<div class="df-scc-euiModalBody__overflow">
							<div class="df-scc-euiText df-scc-euiText--medium">
								<input type="hidden" name="fieldKey" value={{ data }}>
								<div class="df-scc-euiFormRow">
									<div class="df-scc-euiFormRow__labelWrapper">
										<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label">Field Name</label>
									</div>
									<div class="df-scc-euiFormRow__fieldWrapper">
										<div class="df-scc-euiFormControlLayout">
											<div class="df-scc-euiFormControlLayout__childrenWrapper">
												<input type="text" name="field_name" class="df-scc-euiFieldText" value="{{ fieldProps.name }}">
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
											<div class="df-scc-euiFormControlLayout__childrenWrapper"><input type="text" name="field_description" class="df-scc-euiFieldText" value="{{ fieldProps.description }}"></div>
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
											<option value="date" {{ fieldProps.type == "date" ? 'selected' : '' }}>Date</option>
											<option value="address" {{ fieldProps.type == "address" ? 'selected' : '' }}>Address</option>
											<option value="phone" {{ fieldProps.type == "phone" ? 'selected' : '' }}>Phone</option>
											<option value="text" {{ fieldProps.type == "text" ? 'selected' : '' }}>Text</option>
											<option value="email" {{ fieldProps.type == "email" ? 'selected' : '' }}>Email</option>
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
									<input type="checkbox" name="is-mandatory" {{ (fieldProps.isMandatory && fieldProps.isMandatory !== "false")  ? 'checked' : '' }}><label class="df-scc-euiFormLabel df-scc-euiFormRow__label" for="is-mandatory">Make
										Mandatory</label>
								</div>
							</div>
							<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
						</div>
					</div>
					<div class="df-scc-euiModalFooter">
						<# if (fieldProps?.deletable !==false) { #>
							<button class="df-scc-euiButtonEmpty text-danger" type="button" onclick="javascript:handleQuoteFieldDeletion(this)" data-field-key={{ data }}><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">Delete</span></span>
							</button>
							<# } #>
								<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" type="button" data-dismiss="modal"><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">Cancel</span></span>
								</button>
								<button class="df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill" type="submit">
									<span class="df-scc-euiButtonContent df-scc-euiButton__content" style="background-color:#006BB4;border-radius: 3px;">
										<span class="trn df-scc-euiButton__text">Update</span>
									</span>
								</button>
					</div>
				</form>
			</div>
	</div>
</script>
<script type="text/html" id="tmpl-scc-quote-items-list-modal">
	<#
	let { pdfConfig } = data;
	data.pdf.rows = data.pdf.rows.filter(e => typeof(e) !== 'undefined');
	function getCoupon() {
	  return data.pdf.rows.filter(e => e?.type == 'coupon')[0];
	}
	let totalRow = data.pdf.rows.filter(e => e.type =="total")[0];
	let showPremiumNotice = !pdfConfig.turnoffSave && !pdfConfig.isPremium && pdfConfig.isAdmin;
	let excludedRowTypes = ['section_title', 'tax', 'total', 'subtotal_tax', 'coupon', 'section_subtotal', 'comment'];
	#>
	<style>
		.span-title{
			font-weight: bolder;
		}
	</style>
	<div class="scc-cstm-desktop-view ">
		<div id="scctableprice" class="scctableform hover_bkgr_fricctableprice-1 " style="width: 100%; display: block;z-index: 2147483647;">
			<div id="sccTale_price-1" data-simplebar="init">
				<div class=" sscfull-width position-relative scc-buttons-visibility" style="margin-top:15px;text-align:right;background-color:#FFF;">
					<div class="scc-detailed-list-head"></div>
					  <# if (!pdfConfig.turnoffSave && pdfConfig.isPremium) { #>
						<span class="pdf-preview-icons" style="color: {{ data.objectColor }}" onclick="sendPDF(1, {{ data.calcId }})"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="save" class="svg-inline--fa fa-save fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
							<path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM272 80v80H144V80h128zm122 352H54a6 6 0 0 1-6-6V86a6 6 0 0 1 6-6h42v104c0 13.255 10.745 24 24 24h176c13.255 0 24-10.745 24-24V83.882l78.243 78.243a6 6 0 0 1 1.757 4.243V426a6 6 0 0 1-6 6zM224 232c-48.523 0-88 39.477-88 88s39.477 88 88 88 88-39.477 88-88-39.477-88-88-88zm0 128c-22.056 0-40-17.944-40-40s17.944-40 40-40 40 17.944 40 40-17.944 40-40 40z"></path>
						</svg></span>
						<span class="pdf-preview-icons" style="color: {{ data.objectColor }}" id="sccprinterid" onclick="PrintDoc(1, {{ data.calcId }})"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="print" class="svg-inline--fa fa-print fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="currentColor" d="M448 192V77.25c0-8.49-3.37-16.62-9.37-22.63L393.37 9.37c-6-6-14.14-9.37-22.63-9.37H96C78.33 0 64 14.33 64 32v160c-35.35 0-64 28.65-64 64v112c0 8.84 7.16 16 16 16h48v96c0 17.67 14.33 32 32 32h320c17.67 0 32-14.33 32-32v-96h48c8.84 0 16-7.16 16-16V256c0-35.35-28.65-64-64-64zm-64 256H128v-96h256v96zm0-224H128V64h192v48c0 8.84 7.16 16 16 16h48v96zm48 72c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z"></path>
						</svg></span>
					  <# } #>
					  <# if (showPremiumNotice) { #>
						<span class="pdf-preview-icons" style="color:#000" onclick="javascript:jQuery('.premium-pdf').show()"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="save" class="svg-inline--fa fa-save fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
							<path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM272 80v80H144V80h128zm122 352H54a6 6 0 0 1-6-6V86a6 6 0 0 1 6-6h42v104c0 13.255 10.745 24 24 24h176c13.255 0 24-10.745 24-24V83.882l78.243 78.243a6 6 0 0 1 1.757 4.243V426a6 6 0 0 1-6 6zM224 232c-48.523 0-88 39.477-88 88s39.477 88 88 88 88-39.477 88-88-39.477-88-88-88zm0 128c-22.056 0-40-17.944-40-40s17.944-40 40-40 40 17.944 40 40-17.944 40-40 40z"></path>
						</svg></span>
						<span class="pdf-preview-icons" style="color:#000" id="sccprinterid" onclick="javascript:jQuery('.premium-pdf').show()"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="print" class="svg-inline--fa fa-print fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="currentColor" d="M448 192V77.25c0-8.49-3.37-16.62-9.37-22.63L393.37 9.37c-6-6-14.14-9.37-22.63-9.37H96C78.33 0 64 14.33 64 32v160c-35.35 0-64 28.65-64 64v112c0 8.84 7.16 16 16 16h48v96c0 17.67 14.33 32 32 32h320c17.67 0 32-14.33 32-32v-96h48c8.84 0 16-7.16 16-16V256c0-35.35-28.65-64-64-64zm-64 256H128v-96h256v96zm0-224H128V64h192v48c0 8.84 7.16 16 16 16h48v96zm48 72c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z"></path>
						</svg></span>
					  <# } #>
						<span class="pdf-preview-icons" style="color: {{ data.objectColor }}" onclick="jQuery('#detail-view-placeholder').html('')"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="window-close" class="svg-inline--fa fa-window-close fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
							<path fill="currentColor" d="M464 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm0 394c0 3.3-2.7 6-6 6H54c-3.3 0-6-2.7-6-6V86c0-3.3 2.7-6 6-6h404c3.3 0 6 2.7 6 6v340zM356.5 194.6L295.1 256l61.4 61.4c4.6 4.6 4.6 12.1 0 16.8l-22.3 22.3c-4.6 4.6-12.1 4.6-16.8 0L256 295.1l-61.4 61.4c-4.6 4.6-12.1 4.6-16.8 0l-22.3-22.3c-4.6-4.6-4.6-12.1 0-16.8l61.4-61.4-61.4-61.4c-4.6-4.6-4.6-12.1 0-16.8l22.3-22.3c4.6-4.6 12.1-4.6 16.8 0l61.4 61.4 61.4-61.4c4.6-4.6 12.1-4.6 16.8 0l22.3 22.3c4.7 4.6 4.7 12.1 0 16.8z"></path>
						</svg></span>
				</div>
				<p class="scc-col-md-12 scc-col-xs-12 Desc-Quantity-Price-Title-SCC premium-pdf" style="display: none; font-weight: bold;">You need to buy Stylish Cost Calculator Premium</p>
				<div class=" sscfull-width position-relative " style="display:flex;align-items: center;">
				  <# if (pdfConfig.bannerImage.length && pdfConfig.logo.length) { #>
					<div class='scc_email_template_view' id='scctembanimage' style='width:100%;'><center><img src="{{ pdfConfig.bannerImage }}" alt='banner-img' style='width100%;padding-top:30px;'><div style='width:100%;text-align: center;'><img src="{{ pdfConfig.logo }}" alt='logo-img' style='margin-top:25px;max-width: 275px;'></div></center></div>
				  <# } #>
				  <# if (pdfConfig.bannerImage.length && !pdfConfig.logo.length) { #>
					<div class='scc_email_template_view' id='scctembanimage' style='width:100%;'><img src="{{ pdfConfig.bannerImage }}" alt='banner-img' style='width: 100%;padding-top:30px;'></div>
				  <# } #>
				  <# if (!pdfConfig.bannerImage.length && pdfConfig.logo.length) { #>
					<div class='scc_email_template_view' id='scctembanimage' style='width:100%;'><div style='width:100%;text-align: center;'><img src="{{ pdfConfig.logo }}" alt='logo-img' style='width: 250px;'></div></div>
				  <# } #>
				  <# if (!pdfConfig.bannerImage.length && !pdfConfig.logo.length) { #>
					<div class='scc_email_template_view' id='scctembanimage' style='width:100%;'></div>
				  <# } #>
				</div>
				<div style="text-align:right; margin-top: 30px;" class="detailview-date"><span style="width: 100%;height: 100%;position: relative;align-items: right;justify-content:right;position:relative; right:0px;width:100%;text-align:right; color:black">{{ data.pdf.date }}</span></div>
				<div class=" sscfull-width position-relative" style="padding-bottom:30px; display: flex;justify-content: center; align-items: center; text-align:center;">
					<div class="scc-col-md-10 scc-col-xs-12 main-title" style="padding-top:20px;font-size:22px; color:black;">{{ data.pdf.pdf_title }}</div>
				</div>
				<div id="scc-summary-view-header" class=" sscfull-width position-relative table__fricctableprice">
					<div class="scc-col-md-6 scc-col-xs-5 Desc-Quantity-Price-Title-SCC" style="text-align:left"><span class="Title-Descriptions-Summary-Window trn Description" style="text-align: right;" data-trn-key="Description">{{ data.pdf.description }}</span></div>
					<div class="scc-col-md-2 scc-col-xs-2 Desc-Quantity-Price-Title-SCC" style="text-align:right"><span class="Title-Descriptions-Summary-Window trn Quantity" data-trn-key="Quantity">{{ data.pdf.quantity }}</span></div>
					<div class="scc-col-md-2 scc-col-xs-2 Desc-Quantity-Price-Title-SCC" style="text-align:right"><span class="Title-Descriptions-Summary-Window trn Unit Price" data-trn-key="Unit Price">{{ data.pdf.unit }}</span></div>
					<div class="scc-col-md-2 scc-col-xs-3 Desc-Quantity-Price-Title-SCC" style="text-align:right"><span class="Title-Descriptions-Summary-Window trn Price" data-trn-key="Price">{{ data.pdf.price }}</span></div>
				</div>
				<# data.pdf.rows.forEach(function(row, index) { if (row?.type=='section_title' ) { #>
					<div class=" sscfull-width position-relative">
						<div class="Section-Title-Summary-Window">{{ row.section_title }}</div>
					</div>
					<# } #>
					<# if (row?.type=='section_subtotal' ) { #>
					  <div class="scc-preview-row">
						<div class="scc-col-md-6 scc-col-xs-5 sscfull-height sccfull-height-2 " style="text-align:left !important;padding-left:0px;">
							<div class="row-fluid Product-Titles-Summary-Window" style="">
								<div class="span-title">{{ row.attr.name }}</div>
								<div class="span2"></div>
								<div class="product-desc-summary-scc"></div>
							</div>
						</div>
						<div class="scc-col-md-2 scc-col-xs-2 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc"></span></div>
						<div class="scc-col-md-2 scc-col-xs-2 sscfull-height " style="text-align:right"><span class="users-price-summary-scc" style="position:relative; right:0px;"><span class="currency-prefix"></span><span></span></span></div>
						<div class="scc-col-md-2 scc-col-xs-3 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc"><span class="currency-prefix"></span><span class="span-title">{{ row.attr.total_price }}</span></span></div>
					  </div>
					<# } #>
					<# if ( !excludedRowTypes.some(e => e == row.type) ) { #>
						<div class="scc-preview-row">
							<div class="scc-col-md-6 scc-col-xs-5 sscfull-height sccfull-height-2 " style="text-align:left !important;padding-left:0px;">
								<div class="row-fluid Product-Titles-Summary-Window" style="">
									<div class="span-title">{{ row.attr?.title }}</div>
									<div class="span2">{{{ row.attr.name }}}</div>
									<div class="product-desc-summary-scc">{{ row.attr?.description }}</div>
								</div>
							</div>
							<# if (row.attr.unit!=0 &&row.attr.unit_price!=0) { #>
							<div class="scc-col-md-2 scc-col-xs-2 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc">{{ row.attr.unit }}</span></div>
							<div class="scc-col-md-2 scc-col-xs-2 sscfull-height " style="text-align:right"><span class="users-price-summary-scc" style="position:relative; right:0px;"><span class="currency-prefix"></span><span>{{ row.attr.unit_price }}</span></span></div>
							<div class="scc-col-md-2 scc-col-xs-3 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc"><span class="currency-prefix"></span><span>{{ row.attr.total_price }}</span></span></div>
							<# } #>
							<# if (row.attr?.isQuantityModifier) { #>
							<div class="scc-col-md-2 scc-col-xs-2 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc">{{ row.attr.unit }}</span></div>
							<div class="scc-col-md-2 scc-col-xs-2 sscfull-height " style="text-align:right"><span class="users-price-summary-scc" style="position:relative; right:0px;"><span class="currency-prefix"></span><span></span></span></div>
							<div class="scc-col-md-2 scc-col-xs-3 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc"><span class="currency-prefix"></span><span></span></span></div>
							<# } #>
							<# if (row.attr.unit==0 &&row.attr.unit_price!=0 && row.attr?.math_type !== undefined) { #>
							  <div class="scc-col-md-2 scc-col-xs-2 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc"></span></div>
							<div class="scc-col-md-2 scc-col-xs-2 sscfull-height " style="text-align:right"><span class="users-price-summary-scc" style="position:relative; right:0px;"><span class="currency-prefix"></span><span></span></span></div>
							<div class="scc-col-md-2 scc-col-xs-3 sscfull-height  " style="text-align:right"><span class="users-price-summary-scc"><span class="currency-prefix"></span><span>{{ row.attr?.math_type ? row.attr.math_type + ' ' + row.attr.total_price : row.attr?.unit_price }}</span></span></div>
							<# } #>
						</div>
						<# } #>
					<# if (row?.type == "comment") { #>
					  <div class="comments-printing-improvement"
						style="display: flex;justify-content: left;padding-bottom:10px;padding-top:10px">
						<div class="scc-col-md-9 scc-col-xs-9 sscfull-height  " style="padding:0"><span class="users-price-summary-scc ">
								<div class="row-fluid Product-Titles-Summary-Window">
									<div class="span2">{{ row.attr.title }}</div>
								</div>
							</span></div>
						<div class="scc-col-md-6 scc-col-xs-6 sscfull-height" style="text-align:left"><span
								class="users-price-summary-scc ">{{ row.attr.comment }}</span></div>
					  </div>
						  <# } #>
							<# }) #>
								<div class=" full-width position-relative" style="align-items:center;">
									<div class="scc-col-md-6 scc-col-xs-4" id="display_price_table" style="margin-top: 30px;padding:0px;margin:20px 0 0 0;"></div>
									<div class="scc-col-md-6 scc-col-xs-8" id="display_price_table" style="margin-top: 30px;border-top: 2px solid #E8E8E8;padding:10px 0 0 0;">
										<# if (data.pdf.hasTax && !pdfConfig.turnoffTax) {
										  let subtotalRow = data.pdf.rows.find(e => e.type == "subtotal_tax");
										  let taxRow = data.pdf.rows.find(e => e.type == "tax");
										#>
											<div class=" scc-col-md-6 scc-col-xs-6 sscfull-height position-relative table__fricctableprice" style="border: 1px solid white;text-align:right; height:35px;max-width:150px"><span class="users-price-summary-scc trn subtotal_tax_for_pdf" style="" data-trn-key="SubTotal">{{ subtotalRow.attr.title }}</span></div>
											<div style="border: 1px solid white;  justify-content: right; align-items: right;height:35px;text-align:right;padding-right:15px;"><span class="users-price-summary-scc" style="position:relative; right:5px;" id="subtotal_price_with_currency_label_1">{{ subtotalRow.attr.price }}</span></div>
											<div class=" scc-col-md-6 scc-col-xs-6 sscfull-height position-relative table__fricctableprice" style="border: 1px solid white;text-align:right; height:35px;max-width:150px"><span class="users-price-summary-scc"><span class="trn tax_for_pdf" data-trn-key="TAX">{{ taxRow.attr.title }}</span></span></div>
											<div style="border: 1px solid white; text-align:right; justify-content: right; align-items: center;height:35px;padding-right:15px;"><span class="users-price-summary-scc" style="position:relative; right:5px;" id="tax_price_with_currency_label_1">{{ taxRow.attr.price }}</span></div>
											<# } #>
											<# if (getCoupon()) { #>
											  <div class="scc-col-md-6 scc-col-xs-6 sscfull-height position-relative table__fricctableprice"
												  style="border: 1px solid white;text-align:right; height:35px; margin-left: 41px;"><span
													  class="Coupon_percentage_3 users-price-summary-scc" style="color: black;"><span class="trn">Coupon Discount</span> 3.00%</span></div>
											  <div
												  style="border: 1px solid white;  justify-content: right; align-items: right;height:35px;text-align:right;padding-right:15px;">
												  <span class="users-price-summary-scc" id="coupon_discount_with_currency_label_3"
													  style="position:relative; right:5px;width:100%;">£E 10.05</span></div>
											<# } #>
												<div class=" scc-col-md-6 scc-col-xs-6 sscfull-height position-relative table__fricctableprice" style="border: 1px solid white;text-align:right; max-width:150px"><span class="trn Total_Price" style="font-weight:bold;" data-trn-key="Total Price">{{ sccGetTranslationByKey(data.calcId, "Total Price") }}</span></div>
												<div style="border: 1px solid white;text-align:right; justify-content: right; align-items: center;padding-right:15px;"><span class="Total_Price" id="total_price_with_currency_label_1" style="font-weight:bold;position:relative; right:0px;width:100%;text-align:right">{{{ totalRow.attr.price }}}</span></div>
									</div>
								</div>
								<div class="scc_email_template_view" id="scc_email_temfootdiscr" style="margin-top: 84px;">
								  <div style="max-width: 65%;margin: auto;padding-top:75px;">{{ pdfConfig.footer }}</div>
								</div>
								<div class="row sscfull-width position-relative" style="height:5%; display: flex;justify-content: center; align-items: center;"></div>
			</div>
		</div>
	</div>
</script>
<!-- paypal setup modal -->
<script type="text/html" id="tmpl-paypal-setup-form">
	<#
	let { paypalConfig } = data;
	#>
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z">
					</path>
				</svg></button>
			<div class="df-scc-euiModal__flex">
				<div class="df-scc-euiModalHeader">
					<div class="df-scc-euiModalHeader__title trn">PayPal Setup Form</div>
				</div>
				<div class="df-scc-euiModalBody">
					<div class="df-scc-euiModalBody__overflow">
						<div class="df-scc-euiText df-scc-euiText--medium">
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="df-scc-euiFormLabel df-scc-euiFormRow__label df-scc-with-tooltip">Business Email Address: <i class="material-icons" title="Choose the email that you want the payments to be sent to.">info</i></label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper"><input type="email" id="paypal_email_form" class="df-scc-euiFieldText" value={{ paypalConfig?.paypal_email || '' }}>
										</div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">Must enter a valid email!</span>
								</div>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="df-scc-euiFormLabel df-scc-euiFormRow__label df-scc-with-tooltip">Shopping Cart Name: <i class="material-icons" title="Choose any name you wish.The name (127-alphanumeric character limit) of the item or shopping cart.">info</i></label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper"><input type="text" id="paypal_shopping_cart_name_form" class="df-scc-euiFieldText" value={{ paypalConfig?.paypal_shopping_cart_name || '' }}>
										</div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">Invalid name!</span>
								</div>
							</div>
							<div class="scc-form-checkbox"><input type="checkbox" id="paypal_tax_inclusion_settings_form" {{ paypalConfig.paypal_checked == "true" ? 'checked' : '' }}><label class="df-scc-euiFormLabel df-scc-euiFormRow__label" for="paypal_tax_inclusion_settings_form">Include Tax amount </label>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="df-scc-euiFormLabel df-scc-euiFormRow__label df-scc-with-tooltip">Choose a Currency: </label>
								</div>
								<div class="df-scc-eui-FormControlLayout__childrenWrapper"><select id="paypal_currency_form" class="df-scc-eui-Select" aria-label="Use aria labels when no actual label is in use">
										<option value="0">Select Currency</option>
										<option value="AUD">Australian Dollar</option>
										<option value="Bs">Bolivian Boliviano (Symbol After)</option>
										<option value="BRL">Brazilian Real </option>
										<option value="CAD">Canadian Dollar</option>
										<option value="CZK">Czech Koruna</option>
										<option value="DKK">Danish Krone</option>
										<option value="EGP">Egyptian Pound</option>
										<option value="EUR">Euro</option>
										<option value="HKD">Hong Kong Dollar</option>
										<option value="ILS">Israeli New Sheqel</option>
										<option value="INR">Indian Rupee</option>
										<option value="JPY">Japanese Yen</option>
										<option value="KES">Kenyan Shilling</option>
										<option value="MYR">Malaysian Ringgit</option>
										<option value="MXN">Mexican Peso</option>
										<option value="MNT">Mongolian tögrög</option>
										<option value="ANG">Netherlands Antillean Guilder</option>
										<option value="NOK">Norwegian Krone</option>
										<option value="NGN">Nigerian naira</option>
										<option value="NZD">New Zealand Dollar</option>
										<option value="PHP">Philippine Peso</option>
										<option value="PLN">Polish Zloty</option>
										<option value="GBP">British Pound Sterling</option>
										<option value="RON">Romanian leu</option>
										<option value="RUB">Russian Rubles</option>
										<option value="BGN">Bulgarian lev</option>
										<option value="SGD">Singapore Dollar</option>
										<option value="SEK">Swedish Krona</option>
										<option value="CHF">Swiss Franc</option>
										<option value="SAR">Saudi Riyal</option>
										<option value="KRW">South Korean won</option>
										<option value="TWD">Taiwan New Dollar</option>
										<option value="THB">Thai Baht</option>
										<option value="UAH">Ukrainian hryvnia</option>
										<option value="UGX">Ugandan Shilling</option>
										<option value="USD">U.S. Dollar</option>
									</select>
									<div class="df-scc-eui-FormControlLayoutIcons df-scc-eui-FormControlLayoutIcons--right"><span class="df-scc-eui-FormControlLayoutCustomIcon"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-eui-Icon df-scc-eui-Icon--medium df-scc-eui-FormControlLayoutCustomIcon__icon" focusable="false" role="img" aria-hidden="true">
												<path fill-rule="non-zero" d="M13.069 5.157L8.384 9.768a.546.546 0 01-.768 0L2.93 5.158a.552.552 0 00-.771 0 .53.53 0 000 .759l4.684 4.61c.641.631 1.672.63 2.312 0l4.684-4.61a.53.53 0 000-.76.552.552 0 00-.771 0z">
												</path>
											</svg></span></div>
								</div>
								<span class="text-danger" style="display: none; font-size: .75rem;">Please choose a currency!</span>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="df-scc-euiFormLabel df-scc-euiFormRow__label df-scc-with-tooltip">Payment Success Redirect URL <i class="material-icons" title="The page to show after a successful checkout via PayPal">info</i></label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper"><input type="text" id="paypal_shopping_cart_success_url_form" class="df-scc-euiFieldText" value={{ paypalConfig.paypalSuccessURL }}>
										</div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">Must be a valid link!!</span>
								</div>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="df-scc-euiFormLabel df-scc-euiFormRow__label df-scc-with-tooltip">Payment Cancel Redirect URL <i class="material-icons" title="The page to show if checkout via PayPal was canceled">info</i></label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper"><input type="text" id="paypal_shopping_cart_cancel_url_form" class="df-scc-euiFieldText" value={{ paypalConfig.paypalCancelURL }}>
										</div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">Must be a valid link!</span>
								</div>
							</div>
						</div>
						<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
					</div>
				</div>
				<div class="df-scc-euiModalFooter">
					<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" data-action-type="disable-paypal" type="button" data-dismiss="modal"><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">Disable PayPal</span></span>
					</button>
					<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" type="button" data-dismiss="modal"><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">Cancel</span></span>
					</button>
					<button class="df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill" data-action-type="save-paypal-config" type="button">
						<span class="df-scc-euiButtonContent df-scc-euiButton__content" style="background-color:#006BB4;border-radius:3px">
							<span class="trn df-scc-euiButton__text">Enable PayPal</span>
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</script>

<!-- webhook setup template -->
<script type="text/html" id="tmpl-scc-webhook-setup">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal">
				<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"></path>
				</svg>
			</button>
			<form class="df-scc-euiModal__flex" onSubmit="event.preventDefault();handleWebHookSetup(this)">
				<div class="df-scc-euiModalHeader">
					<div class="df-scc-euiModalHeader__title">{{data.title}}</div>
				</div>
				<div class="df-scc-euiModalBody">
					<div class="df-scc-euiModalBody__overflow">
						<div class="df-scc-euiText df-scc-euiText--medium">
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label" for="name">
										<span style="vertical-align: middle">Webhook link</span>&nbsp;
									</label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper">
											<input type="text" value="{{data?.webhookEndPoint}}" name="webhook-link" class="df-scc-euiFieldText">
										</div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">This field
									cannot be empty!</span>
								</div>
							</div>
							<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
						</div>
					</div>
					<div class="df-scc-euiModalFooter">
						<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" type="button" data-dismiss="modal"><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">Cancel</span></span>
						</button>
						<button class="df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill" type="submit">
							<span class="df-scc-euiButtonContent df-scc-euiButton__content">
								<span class="trn df-scc-euiButton__text">Submit</span>
							</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</script>
<!-- stripe setup modal -->
<script type="text/html" id="tmpl-scc-stripe-setup-modal">
<!-- test private_key: sk_test_4eC39HqLyjWDarjtT1zdp7dc
test public_key: pk_test_TYooMQauvdEDq54NiTphI7jx
-->
<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
  <div
	class="
	  df-scc-euiModal
	  df-scc-euiModal--maxWidth-default
	  df-scc-euiModal--confirmation
	"
  >
	<button
	  class="
		df-scc-euiButtonIcon df-scc-euiButtonIcon--text
		df-scc-euiModal__closeIcon
	  "
	  type="button"
	  data-dismiss="modal"
	>
	  <svg
		width="16"
		height="16"
		viewBox="0 0 16 16"
		xmlns="http://www.w3.org/2000/svg"
		class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon"
		focusable="false"
		role="img"
		aria-hidden="true"
	  >
		<path
		  d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"
		></path>
	  </svg>
	</button>
	<div class="df-scc-euiModal__flex">
	  <div class="df-scc-euiModalHeader">
		<div
		  class="df-scc-euiModalHeader__title trn"
		  data-test-subj="confirmModalTitleText"
		>
		  Enter your Stripe API key
		</div>
	  </div>
	  <div class="df-scc-euiModalBody">
		<div class="df-scc-euiModalBody__overflow">
		  <div
			class="df-scc-euiText df-scc-euiText--medium"
			data-test-subj="confirmModalBodyText"
		  >
			<div class="df-scc-euiFormRow">
			  <div class="df-scc-euiFormRow__labelWrapper">
				<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label"
				  >Private Key</label
				>
			  </div>
			  <div class="df-scc-euiFormRow__fieldWrapper">
				<div class="df-scc-euiFormControlLayout">
				  <div class="df-scc-euiFormControlLayout__childrenWrapper">
					<input
					  type="text"
					  name="stripe-api-priv-key"
					  class="df-scc-euiFieldText"
					/>
				  </div>
				</div>
				<span
				  class="text-danger"
				  style="display: none; font-size: 0.75rem"
				  >This field cannot be empty!</span
				>
			  </div>
			</div>
			<div class="df-scc-euiFormRow">
			  <div class="df-scc-euiFormRow__labelWrapper">
				<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label"
				  >Public Key</label
				>
			  </div>
			  <div class="df-scc-euiFormRow__fieldWrapper">
				<div class="df-scc-euiFormControlLayout">
				  <div class="df-scc-euiFormControlLayout__childrenWrapper">
					<input
					  type="text"
					  name="stripe-api-pub-key"
					  class="df-scc-euiFieldText"
					/>
				  </div>
				</div>
				<span
				  class="text-danger"
				  style="display: none; font-size: 0.75rem"
				  >This field cannot be empty!</span
				>
			  </div>
			</div>
		  </div>
		  <p class="trn text-danger" style="display: none">
			There has been an error. Try again
		  </p>
		</div>
	  </div>
	  <div class="df-scc-euiModalFooter">
		<button
		  class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary"
		  type="button"
		  data-dismiss="modal"
		>
		  <span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"
			><span class="trn df-scc-euiButtonEmpty__text">Cancel</span></span
		  >
		</button>
		<button
		  class="
			df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill
		  "
		  type="button"
		  onclick="setupStripeKey(this)"
		>
		  <span class="df-scc-euiButtonContent df-scc-euiButton__content">
			<span class="trn df-scc-euiButton__text">Submit</span>
		  </span>
		</button>
	  </div>
	</div>
  </div>
</div>
</script>
<script type="text/html" id="tmpl-scc-coupon-modal">
<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
  <div
	class="
	  df-scc-euiModal
	  df-scc-euiModal--maxWidth-default
	  df-scc-euiModal--confirmation
	"
  >
	<button
	  class="
		df-scc-euiButtonIcon df-scc-euiButtonIcon--text
		df-scc-euiModal__closeIcon
	  "
	  type="button"
	  data-dismiss="modal"
	>
	  <svg
		width="16"
		height="16"
		viewBox="0 0 16 16"
		xmlns="http://www.w3.org/2000/svg"
		class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon"
		focusable="false"
		role="img"
		aria-hidden="true"
	  >
		<path
		  d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"
		></path>
	  </svg>
	</button>
	<div class="df-scc-euiModal__flex">
	  <div class="df-scc-euiModalHeader">
		<div
		  class="df-scc-euiModalHeader__title trn"
		  data-test-subj="confirmModalTitleText"
		>
		{{sccGetTranslationByKey(data.calcId, 'Enter your coupon code')}}
		</div>
	  </div>
	  <div class="df-scc-euiModalBody">
		<div class="df-scc-euiModalBody__overflow">
		  <div
			class="df-scc-euiText df-scc-euiText--medium"
			data-test-subj="confirmModalBodyText"
		  >
			<div class="df-scc-euiFormRow">
			  <div class="df-scc-euiFormRow__fieldWrapper">
				<div class="df-scc-euiFormControlLayout">
				  <div class="df-scc-euiFormControlLayout__childrenWrapper">
					<input
					  type="text"
					  name="coupon-code"
					  class="df-scc-euiFieldText"
					/>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		  <p class="trn text-danger" style="display: none">
			{{ sccGetTranslationByKey(data.calcId, 'This code is not valid') }}
		  </p>
		</div>
	  </div>
	  <div class="df-scc-euiModalFooter">
		<button
		  class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary"
		  type="button"
		  data-dismiss="modal"
		>
		  <span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"
			><span class="trn df-scc-euiButtonEmpty__text">{{ sccGetTranslationByKey(data.calcId, 'Cancel')}}</span></span
		  >
		</button>
		<button
		  class="
			df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill
		  "
		  type="button"
		  onclick="checkCouponCode({{ data.calcId }})"
		>
		  <span class="df-scc-euiButtonContent df-scc-euiButton__content">
			<span class="trn df-scc-euiButton__text">{{sccGetTranslationByKey(data.calcId,'Submit')}}</span>
		  </span>
		</button>
	  </div>
	</div>
  </div>
</div>
</script>

<script type="text/html" id="tmpl-scc-discount-notice">
<div class="row coupon_info_container" style="display: block; text-align: right;">
	<# if (data.percent) { #>
	  <div>
		  <span class="trn">{{ sccGetTranslationByKey('Discount percentage') }}</span>: {{data.percent}}%
	  </div>
	<# } #>
	<div>
	  <p style="color:green"> <span class="trn">{{ sccGetTranslationByKey('Your discount has been applied correctly') }}</span>. -{{ data.discountAmountText }} </p>
	</div>
</div>
</script>

<script type="text/html" id="tmpl-scc-diag-alert">
	<# if (data) {
	  Object.keys(data).forEach(e => {
		let msgContents = data[e];
		#>
		  <div class="alert alert-warning" role="alert">
			<div class="diag-msg-container">
			  <b>{{ msgContents.title }}</b>
			  <p>{{{ jQuery('<span>' + msgContents.message + '</span>').html() }}}</p>
			  <i class="material-icons diag-msg-close" onclick="javascript:handleDiagRemove(this)" data-diag-key={{ e }}>close</i>
			</div>
		  </div>
		<#
	  })
	} #>
</script>
