<div class="popupally-setting-div {{selected_item_opened}}" id="popupally-style-div-{{id}}">
	<input type="hidden" name-sync-master="{{id}}" name="[{{id}}][name]" value="{{name}}"/>
	<div class="popupally-header popupally-header-icon" toggle-target="#style-toggle-{{id}}" id="popupally-style-header-{{id}}">
		<div class="view-toggle-block">
			<input class="popupally-update-follow-scroll" name="[{{id}}][is-open]" {{selected_item_checked}} type="checkbox" value="true"
				   toggle-group="style" toggle-class="popupally-item-opened" toggle-element="#popupally-style-div-{{id}}" min-height="40" min-height-element="#popupally-style-header-{{id}}"
				   popupally-change-source="style-toggle-{{id}}" id="style-toggle-{{id}}" popup-id="{{id}}">
			<label hide-toggle="is-open" data-dependency="style-toggle-{{id}}" data-dependency-value="false">&#x25BC;</label>
			<label hide-toggle="is-open" data-dependency="style-toggle-{{id}}" data-dependency-value="true">&#x25B2;</label>
		</div>
		<div class="popupally-name-display-block">
			<div class="popupally-name-display" hide-toggle data-dependency="edit-name-style-{{id}}" data-dependency-value="display">
				<table class="popupally-header-table">
					<tbody>
						<tr>
							<td class="popupally-number-col">{{id}}. </td>
							<td class="popupally-name-label-col"><div class="popupally-name-label" name-sync-text="{{id}}">{{name}}</div></td>
							<td class="popupally-name-edit-col"><div class="pencil-icon" click-value="edit" click-target="#edit-name-style-{{id}}"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<input type="hidden" id="edit-name-style-{{id}}" popupally-change-source="edit-name-style-{{id}}" value="display" />
			<input class="popupally-name-edit full-width" name-sync-val="{{id}}" style="display:none;"
				   hide-toggle data-dependency="edit-name-style-{{id}}" data-dependency-value="edit" size="12" value="{{name}}" />
		</div>
	</div>

	<div hide-toggle="is-open" data-dependency="style-toggle-{{id}}" data-dependency-value="true">
		<div class="popupally-setting-section" popup-id="{{id}}" signup-html-template="form">
			<div>
				<div class="popupally-setting-section-header">Sign Up HTML</div>
				<div class="popupally-setting-section-help-text">place the embed code from your email provider below</div>
				<div class="popupally-setting-section-help-text">need help getting the Sign Up HTML code for your email platform? See the <a href="https://kb.accessally.com/tutorials/how-to-integrate-popupally-with-your-email-service-provider/" target="_blank">tutorial</a> for detail!</div>
				<div class="popupally-setting-configure-block">
					<input type="hidden" name="[{{id}}][sign-up-form-method]" id="sign-up-form-method-{{id}}" value="{{sign-up-form-method}}" />
					<input type="hidden" name="[{{id}}][sign-up-form-action]" id="sign-up-form-action-{{id}}" value="{{sign-up-form-action}}" />
					<input type="hidden" name="[{{id}}][sign-up-form-valid]" popupally-change-source="sign-up-form-valid-{{id}}" id="sign-up-form-valid-{{id}}" value="{{sign-up-form-valid}}" />
					<div>
						{{generated_fields}}
						<textarea class="full-width sign-up-form-raw-html" popup-id="{{id}}" name="[{{id}}][signup-form]" rows="6">{{signup-form}}</textarea>
						<small class="sign-up-error" id="sign-form-error-{{id}}"></small>
					</div>

					<div {{form-valid-false-hide}} hide-toggle data-dependency="sign-up-form-valid-{{id}}" data-dependency-value="true">
						<div class="sign-up-form-section" popup-id="{{id}}" signup-html-template="name">
							<div class="popupally-setting-section-sub-header">Name field</div>
							<div>
								<span class="sign-up-form-span">
									<label for="sign-up-form-name-{{id}}">Form field</label>
									<select id="sign-up-form-name-{{id}}" class="sign-up-form-select-{{id}}" sign-up-form-field="name" name="[{{id}}][sign-up-form-name-field]">
										{{signup_name_field_selection}}
									</select>
								</span>
							</div>
						</div>
						<div class="sign-up-form-section" popup-id="{{id}}" signup-html-template="email">
							<div class="popupally-setting-section-sub-header">Email field</div>
							<div>
								<span class="sign-up-form-span">
									<label for="sign-up-form-email-{{id}}">Form field</label>
									<select id="sign-up-form-email-{{id}}" class="sign-up-form-select-{{id}}" sign-up-form-field="email" name="[{{id}}][sign-up-form-email-field]">
										{{signup_email_field_selection}}
									</select>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="popupally-setting-section">
			<div class="popupally-setting-section-header">Popup Template</div>
			<div class="popupally-setting-section-help-text">choose a template with custom sizing and other options</div>
			<div class="popupally-setting-configure-block">
				<input type="hidden" name="[{{id}}][selected-template]" id="template-selection-value-{{id}}" value="{{selected-template}}" />
				<select class="popupally-setting-style-template-select" popup-id="{{id}}" popupally-change-source="template-selector-{{id}}">
					{{template_selection}}
					<option value="sample-simple-choice">Simple Choice (Pro only)</option>
					<option value="sample-before-you-go">Before You Go (Pro only)</option>
					<option value="sample-circular">Circular (Pro only)</option>
					<option value="sample-contact-form">Contact Form (Pro only)</option>
				</select>
			</div>
		</div>
		<div id="template-customization-section-{{id}}">
			{{template_customization}}
			<div class="template-customization-block popupally-setting-section" id="template-customization-block-{{id}}-sample-simple-choice">
				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-header">Want to use this template?</div>
					<a class="popupally-trial-button" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank"><span class="popupally-click-arrow"></span>Try PopupAlly Pro for $1!</a>
				</div>
				<a class="popupally-sample-template-image" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">
					<img class="popupally-sample-template" src="{{plugin-uri}}resource/backend/img/simple-choice-sample.png" />
				</a>
			</div>
			<div class="template-customization-block popupally-setting-section" id="template-customization-block-{{id}}-sample-before-you-go">
				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-header">Want to use this template?</div>
					<a class="popupally-trial-button" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank"><span class="popupally-click-arrow"></span>Try PopupAlly Pro for $1!</a>
				</div>
				<a class="popupally-sample-template-image" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">
					<img class="popupally-sample-template" src="{{plugin-uri}}resource/backend/img/before-you-go-sample.png" />
				</a>
			</div>
			<div class="template-customization-block popupally-setting-section" id="template-customization-block-{{id}}-sample-circular">
				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-header">Want to use this template?</div>
					<a class="popupally-trial-button" href="https://popupally.com/upgrading-to-popupally-pro//" target="_blank"><span class="popupally-click-arrow"></span>Try PopupAlly Pro for $1!</a>
				</div>
				<a class="popupally-sample-template-image" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">
					<img class="popupally-sample-template" src="{{plugin-uri}}resource/backend/img/circular-sample.png" />
				</a>
			</div>
			<div class="template-customization-block popupally-setting-section" id="template-customization-block-{{id}}-sample-contact-form">
				<div class="popupally-setting-configure-block">
					<div class="popupally-setting-section-header">Want to use this template?</div>
					<a class="popupally-trial-button" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank"><span class="popupally-click-arrow"></span>Try PopupAlly Pro for $1!</a>
				</div>
				<a class="popupally-sample-template-image" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">
					<img class="popupally-sample-template" src="{{plugin-uri}}resource/backend/img/contact-form-sample.png" />
				</a>
			</div>
		</div>
	</div>
</div>