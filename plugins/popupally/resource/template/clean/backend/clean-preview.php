<table class="popupally-style-responsive-container">
	<tbody>
		<tr class="popupally-style-responsive-top-row">
			<td id="popupally-fluid-responsive-header-{{id}}-plsbvs-0" class="popupally-style-responsive-tab-label-col popupally-style-responsive-tab-active"
				tab-group="popupally-responsive-tab-group-{{id}}-plsbvs" target="0" active-class="popupally-style-responsive-tab-active">
				Desktop
			</td>
			<td id="popupally-fluid-responsive-header-{{id}}-plsbvs-0" class="popupally-style-responsive-tab-label-col"
				tab-group="popupally-responsive-tab-group-{{id}}-plsbvs" target="1" active-class="popupally-style-responsive-tab-active">
				Tablets
			</td>
			<td id="popupally-fluid-responsive-header-{{id}}-plsbvs-0" class="popupally-style-responsive-tab-label-col"
				tab-group="popupally-responsive-tab-group-{{id}}-plsbvs" target="2" active-class="popupally-style-responsive-tab-active">
				Mobile Phones
			</td>
		</tr>
		<tr>
			<td colspan="3" class="popupally-style-responsive-content-cell">
<div class="popupally-sub-setting-content-container" popupally-responsive-tab-group-{{id}}-plsbvs="0">
	<div style="height:1px;"></div>
	<div class="popupally-setting-section" popup-id="{{id}}" template-id="plsbvs" level="0" margin-before="#plsbvs-customization-section-{{id}}">
		<div class="popupally-setting-section-header">Preview</div>
		<div class="popupally-setting-section-help-text">preview your changes automatically here</div>
		<div class="popupally-style-full-size-scroll">{{preview-code-2}}</div>
	</div>
	<div class="popupally-setting-section" id="plsbvs-customization-section-{{id}}">
		<div class="popupally-setting-section-header">Customization</div>

		<div class="popupally-configure-element">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Background color</div>
				<div>
					<table class="popupally-setting-configure-table">
						<tbody>
							<tr>
								<td style="width:40%;">
									<div><input size="8" class="nqpc-picker-input-iyxm" name="[{{id}}][plsbvs-background-color]" type="text" value="{{plsbvs-background-color}}" preview-update-target-css=".popupally-outer-preview-background-plsbvs-{{id}}" preview-update-target-css-property="background-color" data-default-color="#d3d3d3"></div>
								</td>
								<td><div class="popupally-inline-help-text">To have a transparent popup, leave this field blank.</div></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Background Image</div>
				<div>
					<table class="popupally-setting-configure-table">
						<tbody>
							<tr>
								<td style="width:60%;">
									<input class="full-width" type="text" id="plsbvs-image-url-{{id}}" name="[{{id}}][plsbvs-image-url]" value="{{plsbvs-image-url}}" preview-update-target-css-background-img="#plsbvs-popup-box-preview-{{id}}" />
									<div upload-image="#plsbvs-image-url-{{id}}">Upload Image</div>
								</td>
								<td><div class="popupally-inline-help-text">Leave this field blank if you do not want to show an image with the popup.</div></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="popupally-configure-element">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Popup Box Size</div>
				<div>
					<span class="two-by-two-input">
						Width
						<input size="4" name="[{{id}}][plsbvs-width]" type="text" value="{{plsbvs-width}}" preview-update-target-css="#plsbvs-popup-box-preview-{{id}}" preview-update-target-css-property-px="width">px
					</span>
					<span class="two-by-two-input">
						Height
						<input size="4" name="[{{id}}][plsbvs-height]" type="text" value="{{plsbvs-height}}" preview-update-target-css="#plsbvs-popup-box-preview-{{id}}" preview-update-target-css-property-px="height">px
					</span>
				</div>
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-headline-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline (HTML code allowed)</div>
				<div>
					<textarea rows="3" class="full-width" name="[{{id}}][plsbvs-headline]" html-error-check="#plsbvs-headline-error-{{id}}" preview-update-target=".plsbvs-preview-headline-{{id}}">{{plsbvs-headline}}</textarea>
					<small class="sign-up-error" id="plsbvs-headline-error-{{id}}" popup-id="{{id}}" html-code-source="Headline"></small>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Position</div>
				<div>
					<span class="two-by-two-input">
						Vertical offset
						<input size="4" name="[{{id}}][plsbvs-headline-top]" type="text" value="{{plsbvs-headline-top}}" preview-update-target-css="#plsbvs-preview-headline-{{id}}" preview-update-target-css-property-px="top">px
					</span>
					<span class="two-by-two-input">
						Horizontal offset
						<input size="4" name="[{{id}}][plsbvs-headline-left]" type="text" value="{{plsbvs-headline-left}}" preview-update-target-css="#plsbvs-preview-headline-{{id}}" preview-update-target-css-property-px="left">px
					</span>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Style</div>
				{{plsbvs-headline-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-name-input-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Name Input Placeholder</div>
				<div>
					<input size="10" name="[{{id}}][plsbvs-name-placeholder]" type="text" value="{{plsbvs-name-placeholder}}" preview-update-target-placeholder=".plsbvs-preview-name-{{id}}">
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Vertical offset
							<input size="4" name="[{{id}}][plsbvs-name-field-top]" type="text" value="{{plsbvs-name-field-top}}" preview-update-target-css="#plsbvs-preview-name-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span>
							Horizontal offset
							<input size="4" name="[{{id}}][plsbvs-name-field-left]" type="text" value="{{plsbvs-name-field-left}}" preview-update-target-css="#plsbvs-preview-name-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Name Input Style</div>
				{{plsbvs-name-field-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-email-input-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Email Input Placeholder</div>
				<div>
					<input size="10" name="[{{id}}][plsbvs-email-placeholder]" type="text" value="{{plsbvs-email-placeholder}}" preview-update-target-placeholder=".plsbvs-preview-email-{{id}}">
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Vertical offset
							<input size="4" name="[{{id}}][plsbvs-email-field-top]" type="text" value="{{plsbvs-email-field-top}}" preview-update-target-css="#plsbvs-preview-email-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span>
							Horizontal offset
							<input size="4" name="[{{id}}][plsbvs-email-field-left]" type="text" value="{{plsbvs-email-field-left}}" preview-update-target-css="#plsbvs-preview-email-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Email Input Style</div>
				{{plsbvs-email-field-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-subscribe-button-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text</div>
				<div>
					<input size="20" name="[{{id}}][plsbvs-subscribe-button-text]" type="text" value="{{plsbvs-subscribe-button-text}}" preview-update-target-value=".plsbvs-subscribe-button-{{id}}">
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Position</div>
				<div>
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Top
							<input size="4" name="[{{id}}][plsbvs-subscribe-button-top]" type="text" value="{{plsbvs-subscribe-button-top}}" preview-update-target-css="#plsbvs-subscribe-button-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span class="two-by-two-input">
							Left
							<input size="4" name="[{{id}}][plsbvs-subscribe-button-left]" type="text" value="{{plsbvs-subscribe-button-left}}" preview-update-target-css="#plsbvs-subscribe-button-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Color</div>
				<div>
					<input size="8" class="nqpc-picker-input-iyxm" name="[{{id}}][plsbvs-subscribe-button-color]" type="text" value="{{plsbvs-subscribe-button-color}}" preview-update-target-css=".plsbvs-subscribe-button-{{id}}" preview-update-target-css-property="background-color" data-default-color="#00c98d">
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text Style</div>
				{{plsbvs-subscribe-button-text-advanced}}
			</div>
		</div>
	</div>
</div>
<div style="display:none;" class="popupally-sub-setting-content-container" popupally-responsive-tab-group-{{id}}-plsbvs="1">
	<div style="height:1px;"></div>
	<div class="popupally-setting-section" popup-id="{{id}}" template-id="plsbvs" level="1" margin-before="#plsbvs-customization-960-section-{{id}}">
		<div class="popupally-setting-section-header">Preview for Tablets</div>
		<div class="popupally-setting-section-help-text">preview your changes automatically here</div>
		<div class="popupally-style-full-size-scroll">{{preview-code-3}}</div>
	</div>
	<div class="popupally-setting-section" id="plsbvs-customization-960-section-{{id}}">
		<div class="popupally-setting-section-header">Customization for Tablet display</div>
		<div class="popupally-setting-section-help-text">screen width between 640px - 960px</div>

		<div class="popupally-configure-element">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Popup Box Size</div>
				<div>
					<span class="two-by-two-input">
						Width
						<input size="4" name="[{{id}}][plsbvs-width-960]" type="text" value="{{plsbvs-width-960}}" preview-update-target-css="#plsbvs-popup-box-960-preview-{{id}}" preview-update-target-css-property-px="width">px
					</span>
					<span class="two-by-two-input">
						Height
						<input size="4" name="[{{id}}][plsbvs-height-960]" type="text" value="{{plsbvs-height-960}}" preview-update-target-css="#plsbvs-popup-box-960-preview-{{id}}" preview-update-target-css-property-px="height">px
					</span>
				</div>
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-headline-960-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Position</div>
				<div>
					<span class="two-by-two-input">
						Vertical offset
						<input size="4" name="[{{id}}][plsbvs-headline-960-top]" type="text" value="{{plsbvs-headline-960-top}}" preview-update-target-css="#plsbvs-preview-headline-960-{{id}}" preview-update-target-css-property-px="top">px
					</span>
					<span class="two-by-two-input">
						Horizontal offset
						<input size="4" name="[{{id}}][plsbvs-headline-960-left]" type="text" value="{{plsbvs-headline-960-left}}" preview-update-target-css="#plsbvs-preview-headline-960-{{id}}" preview-update-target-css-property-px="left">px
					</span>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Style</div>
				{{plsbvs-headline-960-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-name-input-960-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Name Input Position</div>
				<div>
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Vertical offset
							<input size="4" name="[{{id}}][plsbvs-name-field-960-top]" type="text" value="{{plsbvs-name-field-960-top}}" preview-update-target-css="#plsbvs-preview-name-960-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span>
							Horizontal offset
							<input size="4" name="[{{id}}][plsbvs-name-field-960-left]" type="text" value="{{plsbvs-name-field-960-left}}" preview-update-target-css="#plsbvs-preview-name-960-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Name Input Style</div>
				{{plsbvs-name-field-960-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-email-input-960-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Email Input Position</div>
				<div>
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Vertical offset
							<input size="4" name="[{{id}}][plsbvs-email-field-960-top]" type="text" value="{{plsbvs-email-field-960-top}}" preview-update-target-css="#plsbvs-preview-email-960-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span>
							Horizontal offset
							<input size="4" name="[{{id}}][plsbvs-email-field-960-left]" type="text" value="{{plsbvs-email-field-960-left}}" preview-update-target-css="#plsbvs-preview-email-960-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Email Input Style</div>
				{{plsbvs-email-field-960-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-subscribe-button-960-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Position</div>
				<div>
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Top
							<input size="4" name="[{{id}}][plsbvs-subscribe-button-960-top]" type="text" value="{{plsbvs-subscribe-button-960-top}}" preview-update-target-css="#plsbvs-subscribe-button-960-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span class="two-by-two-input">
							Left
							<input size="4" name="[{{id}}][plsbvs-subscribe-button-960-left]" type="text" value="{{plsbvs-subscribe-button-960-left}}" preview-update-target-css="#plsbvs-subscribe-button-960-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text Style</div>
				{{plsbvs-subscribe-button-text-960-advanced}}
			</div>
		</div>
	</div>
</div>
<div style="display:none;" class="popupally-sub-setting-content-container" popupally-responsive-tab-group-{{id}}-plsbvs="2">
	<div style="height:1px;"></div>
	<div class="popupally-setting-section" popup-id="{{id}}" template-id="plsbvs" level="1" margin-before="#plsbvs-customization-640-section-{{id}}">
		<div class="popupally-setting-section-header">Preview for Mobile Phones</div>
		<div class="popupally-setting-section-help-text">preview your changes automatically here</div>
		<div class="popupally-style-full-size-scroll">{{preview-code-4}}</div>
	</div>
	<div class="popupally-setting-section" id="plsbvs-customization-640-section-{{id}}">
		<div class="popupally-setting-section-header">Customization for Mobile Phone display</div>
		<div class="popupally-setting-section-help-text">screen width less than 640px</div>

		<div class="popupally-configure-element">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Popup Box Size</div>
				<div>
					<span class="two-by-two-input">
						Width
						<input size="4" name="[{{id}}][plsbvs-width-640]" type="text" value="{{plsbvs-width-640}}" preview-update-target-css="#plsbvs-popup-box-640-preview-{{id}}" preview-update-target-css-property-px="width">px
					</span>
					<span class="two-by-two-input">
						Height
						<input size="4" name="[{{id}}][plsbvs-height-640]" type="text" value="{{plsbvs-height-640}}" preview-update-target-css="#plsbvs-popup-box-640-preview-{{id}}" preview-update-target-css-property-px="height">px
					</span>
				</div>
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-headline-640-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Position</div>
				<div>
					<span class="two-by-two-input">
						Vertical offset
						<input size="4" name="[{{id}}][plsbvs-headline-640-top]" type="text" value="{{plsbvs-headline-640-top}}" preview-update-target-css="#plsbvs-preview-headline-640-{{id}}" preview-update-target-css-property-px="top">px
					</span>
					<span class="two-by-two-input">
						Horizontal offset
						<input size="4" name="[{{id}}][plsbvs-headline-640-left]" type="text" value="{{plsbvs-headline-640-left}}" preview-update-target-css="#plsbvs-preview-headline-640-{{id}}" preview-update-target-css-property-px="left">px
					</span>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Headline Style</div>
				{{plsbvs-headline-640-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-name-input-640-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Name Input Position</div>
				<div>
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Vertical offset
							<input size="4" name="[{{id}}][plsbvs-name-field-640-top]" type="text" value="{{plsbvs-name-field-640-top}}" preview-update-target-css="#plsbvs-preview-name-640-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span>
							Horizontal offset
							<input size="4" name="[{{id}}][plsbvs-name-field-640-left]" type="text" value="{{plsbvs-name-field-640-left}}" preview-update-target-css="#plsbvs-preview-name-640-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Name Input Style</div>
				{{plsbvs-name-field-640-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-email-input-640-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Email Input Position</div>
				<div>
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Vertical offset
							<input size="4" name="[{{id}}][plsbvs-email-field-640-top]" type="text" value="{{plsbvs-email-field-640-top}}" preview-update-target-css="#plsbvs-preview-email-640-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span>
							Horizontal offset
							<input size="4" name="[{{id}}][plsbvs-email-field-640-left]" type="text" value="{{plsbvs-email-field-640-left}}" preview-update-target-css="#plsbvs-preview-email-640-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Email Input Style</div>
				{{plsbvs-email-field-640-advanced}}
			</div>
		</div>

		<div class="popupally-configure-element" id="plsbvs-config-subscribe-button-640-{{id}}">
			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Position</div>
				<div>
					<div class="popupally-style-same-line-block">
						<span class="two-by-two-input">
							Top
							<input size="4" name="[{{id}}][plsbvs-subscribe-button-640-top]" type="text" value="{{plsbvs-subscribe-button-640-top}}" preview-update-target-css="#plsbvs-subscribe-button-640-{{id}}" preview-update-target-css-property-px="top">px
						</span>
						<span class="two-by-two-input">
							Left
							<input size="4" name="[{{id}}][plsbvs-subscribe-button-640-left]" type="text" value="{{plsbvs-subscribe-button-640-left}}" preview-update-target-css="#plsbvs-subscribe-button-640-{{id}}" preview-update-target-css-property-px="left">px
						</span>
					</div>
				</div>
			</div>

			<div class="popupally-setting-configure-block">
				<div class="popupally-setting-section-sub-header">Subscribe Button Text Style</div>
				{{plsbvs-subscribe-button-text-640-advanced}}
			</div>
		</div>
	</div>
</div>
			</td>
		</tr>
	</tbody>
</table>