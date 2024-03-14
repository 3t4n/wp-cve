<table class="popupally-style-responsive-container">
	<tbody>
		<tr class="popupally-style-responsive-top-row">
			<td id="popupally-fluid-responsive-header-{{id}}-bxsjbi-0" class="popupally-style-responsive-tab-label-col popupally-style-responsive-tab-active"
				tab-group="popupally-responsive-tab-group-{{id}}-bxsjbi" target="0" active-class="popupally-style-responsive-tab-active">
				Desktop
			</td>
			<td id="popupally-fluid-responsive-header-{{id}}-bxsjbi-0" class="popupally-style-responsive-tab-label-col"
				tab-group="popupally-responsive-tab-group-{{id}}-bxsjbi" target="1" active-class="popupally-style-responsive-tab-active">
				Tablets
			</td>
			<td id="popupally-fluid-responsive-header-{{id}}-bxsjbi-0" class="popupally-style-responsive-tab-label-col"
				tab-group="popupally-responsive-tab-group-{{id}}-bxsjbi" target="2" active-class="popupally-style-responsive-tab-active">
				Mobile Phones
			</td>
		</tr>
		<tr>
			<td colspan="3" class="popupally-style-responsive-content-cell">
<div class="popupally-sub-setting-content-container" popupally-responsive-tab-group-{{id}}-bxsjbi="0">
	<div style="height:1px;"></div>
	<div class="popupally-setting-section" popup-id="{{id}}" template-id="bxsjbi" level="0" margin-before="#customization-section-{{id}}">
		<div class="popupally-setting-section-header">Preview</div>
		<div class="popupally-setting-section-help-text">preview your changes automatically here</div>
		<div class="popupally-style-full-size-scroll">{{preview-code-2}}</div>
	</div>
	<div class="popupally-setting-section" id="customization-section-{{id}}">
		<div class="popupally-setting-section-header">Customization</div>

		<div class="popupally-configure-element">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Background color</div>
				<div>
					<table class="popupally-setting-configure-table">
						<tbody>
							<tr>
								<td style="width:40%;">
									<div><input size="8" class="nqpc-picker-input-iyxm" name="[{{id}}][background-color]" type="text" value="{{background-color}}" preview-update-target-css=".popupally-outer-preview-background-bxsjbi-{{id}}" preview-update-target-css-property="background-color" data-default-color="#FFFFFF"></div>
								</td>
								<td><div class="popupally-inline-help-text">To have a transparent popup, leave this field blank.</div></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-headline-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Hide Headline</div>
				<div>
					<input popupally-change-source="bxsjbi-headline-hide-toggle-{{id}}" id="bxsjbi-headline-hide-toggle-{{id}}" name="[{{id}}][bxsjbi-headline-hide-toggle]" {{bxsjbi-headline-hide-toggle}} type="checkbox" value="true"
						   preview-update-target-hide-checked="#preview-headline-{{id}}">
					<label for="bxsjbi-headline-hide-toggle-{{id}}">Yes</label>
				</div>
			</div>
			<div hide-toggle="bxsjbi-headline-hide-toggle" data-dependency="bxsjbi-headline-hide-toggle-{{id}}" data-dependency-value="false">
				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-sub-header">Headline (HTML code allowed)</div>
					<div>
						<textarea rows="3" class="full-width" name="[{{id}}][headline]" html-error-check="#bxsjbi-headline-error-{{id}}" preview-update-target=".preview-headline-{{id}}">{{headline}}</textarea>
						<small class="sign-up-error" id="bxsjbi-headline-error-{{id}}" popup-id="{{id}}" html-code-source="Headline"></small>
					</div>
				</div>

				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-sub-header">Headline Style</div>
					{{headline-advanced}}
				</div>
			</div>
		</div>

		<div class="popupally-configure-element">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Hide Logo Row (both the logo image and the introduction text will be hidden)</div>
				<div>
					<input popupally-change-source="bxsjbi-logo-row-hide-toggle-{{id}}" id="bxsjbi-logo-row-hide-toggle-{{id}}" name="[{{id}}][bxsjbi-logo-row-hide-toggle]" {{bxsjbi-logo-row-hide-toggle}} type="checkbox" value="true"
						   preview-update-target-hide-checked=".logo-row-{{id}}">
					<label for="bxsjbi-logo-row-hide-toggle-{{id}}">Yes</label>
				</div>
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-logo-img-{{id}}" hide-toggle="bxsjbi-logo-row-hide-toggle" data-dependency="bxsjbi-logo-row-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Hide Logo Image</div>
				<div>
					<input popupally-change-source="bxsjbi-logo-img-hide-toggle-{{id}}" id="bxsjbi-logo-img-hide-toggle-{{id}}" name="[{{id}}][bxsjbi-logo-img-hide-toggle]" {{bxsjbi-logo-img-hide-toggle}} type="checkbox" value="true"
						   preview-update-target-hide-checked=".preview-img-{{id}}">
					<label for="bxsjbi-logo-img-hide-toggle-{{id}}">Yes</label>
				</div>
			</div>
			<div hide-toggle="bxsjbi-logo-img-hide-toggle" data-dependency="bxsjbi-logo-img-hide-toggle-{{id}}" data-dependency-value="false">
				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-sub-header">Logo Image</div>

					<div>
						<table class="popupally-setting-configure-table">
							<tbody>
								<tr>
									<td style="width:60%;">
										<input class="full-width" type="text" id="bxsjbi-logo-img-url-{{id}}" preview-update-target-img=".preview-img-{{id}}" name="[{{id}}][image-url]" value="{{image-url}}" />
										<div upload-image="#bxsjbi-logo-img-url-{{id}}">Upload Image</div>
									</td>
									<td><div class="popupally-inline-help-text">Leave this field blank if you do not want to show an image with the popup.</div></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-sales-text-{{id}}" hide-toggle="bxsjbi-logo-row-hide-toggle" data-dependency="bxsjbi-logo-row-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Introduction Text (HTML code allowed)</div>
				<div>
					<textarea rows="3" class="full-width" name="[{{id}}][sales-text]" html-error-check="#bxsjbi-sales-text-error-{{id}}" preview-update-target=".preview-sales-text-{{id}}">{{sales-text}}</textarea>
					<small class="sign-up-error" id="bxsjbi-sales-text-error-{{id}}" popup-id="{{id}}" html-code-source="Introduction Text"></small>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Introduction Text Style</div>
				{{sales-text-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-name-input-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Name Input Placeholder</div>
				<div>
					<input class="full-width" name="[{{id}}][name-placeholder]" type="text" value="{{name-placeholder}}" preview-update-target-placeholder=".preview-name-{{id}}">
				</div>
			</div>

			<div class="popupally-setting-configure-block" id="bxsjbi-email-input-{{id}}">
				<div class="popupally-setting-section-sub-header">Email Input Placeholder</div>
				<div>
					<input class="full-width" name="[{{id}}][email-placeholder]" type="text" value="{{email-placeholder}}" preview-update-target-placeholder=".preview-email-{{id}}">
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Input Box Style</div>
				{{input-box-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-subscribe-button-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text</div>
				<div>
					<input class="full-width" name="[{{id}}][subscribe-button-text]" type="text" value="{{subscribe-button-text}}" preview-update-target-value=".subscribe-button-{{id}}">
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Color</div>
				<div>
					<input size="8" class="nqpc-picker-input-iyxm" name="[{{id}}][subscribe-button-color]" type="text" value="{{subscribe-button-color}}" preview-update-target-css=".subscribe-button-{{id}}" preview-update-target-css-property="background-color" data-default-color="#00c98d">
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text Style</div>
				{{subscribe-button-text-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-privacy-text-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Hide Privacy Text</div>
				<div>
					<input popupally-change-source="bxsjbi-privacy-hide-toggle-{{id}}" id="bxsjbi-privacy-hide-toggle-{{id}}" name="[{{id}}][bxsjbi-privacy-hide-toggle]" {{bxsjbi-privacy-hide-toggle}} type="checkbox" value="true"
						   preview-update-target-hide-checked=".privacy-text-{{id}}">
					<label for="bxsjbi-privacy-hide-toggle-{{id}}">Yes</label>
				</div>
			</div>
			<div hide-toggle="bxsjbi-privacy-hide-toggle" data-dependency="bxsjbi-privacy-hide-toggle-{{id}}" data-dependency-value="false">
				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-sub-header">Privacy Text (HTML code allowed)</div>
					<div>
						<textarea rows="3" class="full-width" name="[{{id}}][privacy-text]" html-error-check="#bxsjbi-privacy-text-error-{{id}}" preview-update-target=".privacy-text-{{id}}">{{privacy-text}}</textarea>
						<small class="sign-up-error" id="bxsjbi-privacy-text-error-{{id}}" popup-id="{{id}}" html-code-source="Privacy Text"></small>
					</div>
				</div>

				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-sub-header">Privacy Text Style</div>
					{{privacy-text-advanced}}
				</div>
			</div>
		</div>
	</div>
</div>
<div style="display:none;" class="popupally-sub-setting-content-container" popupally-responsive-tab-group-{{id}}-bxsjbi="1">
	<div style="height:1px;"></div>
	<div class="popupally-setting-section" popup-id="{{id}}" template-id="bxsjbi" level="1" margin-before="#bxsjbi-customization-960-section-{{id}}">
		<div class="popupally-setting-section-header">Preview for Tablets</div>
		<div class="popupally-setting-section-help-text">preview your changes automatically here</div>
		<div class="popupally-style-full-size-scroll">{{preview-code-3}}</div>
	</div>
	<div class="popupally-setting-section" id="bxsjbi-customization-960-section-{{id}}">
		<div class="popupally-setting-section-header">Customization for Tablet display</div>
		<div class="popupally-setting-section-help-text">screen width between 640px - 960px</div>

		<div class="popupally-configure-element" id="bxsjbi-headline-960-{{id}}" hide-toggle="bxsjbi-headline-hide-toggle" data-dependency="bxsjbi-headline-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Style</div>
				{{headline-960-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-sales-text-960-{{id}}" hide-toggle="bxsjbi-logo-row-hide-toggle" data-dependency="bxsjbi-logo-row-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Introduction Text Style</div>
				{{sales-text-960-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-input-960-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Input Boxes Style</div>
				{{input-box-960-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-subscribe-button-960-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text Style</div>
				{{subscribe-button-text-960-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-privacy-text-960-{{id}}" hide-toggle="bxsjbi-privacy-hide-toggle" data-dependency="bxsjbi-privacy-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Privacy Text Style</div>
				{{privacy-text-960-advanced}}
			</div>
		</div>
	</div>
</div>
<div style="display:none;" class="popupally-sub-setting-content-container" popupally-responsive-tab-group-{{id}}-bxsjbi="2">
	<div style="height:1px;"></div>
	<div class="popupally-setting-section" popup-id="{{id}}" template-id="bxsjbi" level="1" margin-before="#bxsjbi-customization-640-section-{{id}}">
		<div class="popupally-setting-section-header">Preview for Mobile Phones</div>
		<div class="popupally-setting-section-help-text">preview your changes automatically here</div>
		<div class="popupally-style-full-size-scroll">{{preview-code-4}}</div>
	</div>
	<div class="popupally-setting-section" id="bxsjbi-customization-640-section-{{id}}">
		<div class="popupally-setting-section-header">Customization for Mobile Phone display</div>
		<div class="popupally-setting-section-help-text">screen width less than 640px</div>

		<div class="popupally-configure-element" id="bxsjbi-headline-640-{{id}}" hide-toggle="bxsjbi-headline-hide-toggle" data-dependency="bxsjbi-headline-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Style</div>
				{{headline-640-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-sales-text-640-{{id}}" hide-toggle="bxsjbi-logo-row-hide-toggle" data-dependency="bxsjbi-logo-row-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Introduction Text Style</div>
				{{sales-text-640-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-input-640-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Input Boxes Style</div>
				{{input-box-640-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-subscribe-button-640-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text Style</div>
				{{subscribe-button-text-640-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="bxsjbi-privacy-text-640-{{id}}" hide-toggle="bxsjbi-privacy-hide-toggle" data-dependency="bxsjbi-privacy-hide-toggle-{{id}}" data-dependency-value="false">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Privacy Text Style</div>
				{{privacy-text-640-advanced}}
			</div>
		</div>
	</div>
</div>
			</td>
		</tr>
	</tbody>
</table>