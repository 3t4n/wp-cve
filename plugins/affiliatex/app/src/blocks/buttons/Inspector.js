import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import IconSelector from "../ui-components/icon-picker";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes }) => {
	const { InspectorControls, URLInput } = wp.blockEditor;
	const { PanelBody, ToggleControl, SelectControl } = wp.components;
	const {
		buttonTextColor,
		buttonTextHoverColor,
		buttonBGColor,
		buttonBGHoverColor,
		buttonTypography,
		buttonMargin,
		buttonPadding,
		iconPosition,
		edButtonIcon,
		buttonSize,
		buttonWidth,
		buttonBorder,
		buttonBGType,
		buttonBgGradient,
		buttonAlignment,
		buttonShadow,
		ButtonIcon,
		buttonIconSize,
		buttonIconColor,
		buttonRadius,
		buttonFixWidth,
		buttonURL,
		btnRelNoFollow,
		btnRelSponsored,
		btnDownload,
		openInNewTab,
		buttonborderHoverColor,
		buttonIconHoverColor,
		layoutStyle,
		priceTagPosition,
		priceTextColor,
		priceBackgroundColor,
	} = attributes;

	const relAttributes = [];

	if (btnRelNoFollow) {
		relAttributes.push("nofollow");
	}

	if (btnRelSponsored) {
		relAttributes.push("sponsored");
	}

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("Layout Settings", "affiliatex")}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option">
					<SelectControl
						label={__("Choose Layout", "affiliatex")}
						value={layoutStyle}
						options={[
							{
								value: "layout-type-1",
								label: __("Default Button", "affiliatex"),
							},
							{
								value: "layout-type-2",
								label: __("Price Button", "affiliatex"),
							},
						]}
						onChange={(value) =>
							setAttributes({ layoutStyle: value })
						}
					/>
				</div>
				{layoutStyle === "layout-type-2" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={priceTagPosition}
							values={priceTagPosition}
							id="price-tag-position"
							option={{
								id: "price-tag-position",
								label: __("Price Tag Position", "affiliatex"),
								type: "ab-radio",
								choices: {
									tagBtnleft: __("Left", "affiliatex"),
									tagBtnright: __("Right", "affiliatex"),
								},
								value: "tagBtnright",
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ priceTagPosition: newValue })
							}
						/>
					</div>
				)}
			</PanelBody>

			<PanelBody
				title={__("Button Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-btn-new-tab">
					<URLInput
						value={buttonURL}
						onChange={(buttonURL) => setAttributes({ buttonURL })}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<ToggleControl
						label={__('Add rel="nofollow"?', "affiliatex")}
						checked={!!btnRelNoFollow}
						onChange={() =>
							setAttributes({ btnRelNoFollow: !btnRelNoFollow })
						}
						autoFocus={true}
					/>
					<ToggleControl
						label={__('Add rel="sponsored"?', "affiliatex")}
						checked={!!btnRelSponsored}
						onChange={() =>
							setAttributes({ btnRelSponsored: !btnRelSponsored })
						}
						autoFocus={true}
					/>
					<ToggleControl
						label={__("Add download attribute", "affiliatex")}
						checked={!!btnDownload}
						onChange={() =>
							setAttributes({ btnDownload: !btnDownload })
						}
						autoFocus={true}
					/>
					<ToggleControl
						label={__("Open Link in new window", "affiliatex")}
						checked={!!openInNewTab}
						onChange={() =>
							setAttributes({ openInNewTab: !openInNewTab })
						}
						autoFocus={true}
					/>
				</div>
				<div className="affiliate-blocks-option border-hover">
					<label>{__("Button Border", "affiliatex")}</label>
					<GenericOptionType
						value={buttonBorder}
						values={buttonBorder}
						id="button-border"
						option={{
							id: "button-border",
							type: "ab-border",
							value: DefaultAttributes.buttonBorder.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ buttonBorder: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-color-option">
					{buttonBorder && buttonBorder.style != "none" && (
						<GenericOptionType
							className="border-hover-color"
							value={{
								borderHoverColor: {
									color: buttonborderHoverColor,
								},
							}}
							option={{
								id: "border-hover-color",
								label: __("Border Hover Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									borderHoverColor: {
										color:
											DefaultAttributes
												.buttonborderHoverColor.default,
									},
								},
								pickers: [
									{
										id: "borderHoverColor",
										title: __(
											"Border Hover Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									buttonborderHoverColor:
										colorValue.borderHoverColor.color,
								})
							}
						/>
					)}
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonRadius}
						values={buttonRadius}
						id="product-radius"
						option={{
							id: "product-radius",
							label: __("Border Radius", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.buttonRadius.default,
							responsive: true,
							units: [
								{
									unit: "",
									min: 0,
									max: 10,
									decimals: 1,
								},

								{
									unit: "px",
									min: 0,
									max: 50,
								},

								{
									unit: "em",
									min: 0,
									max: 50,
								},

								{
									unit: "pt",
									min: 0,
									max: 50,
								},

								{
									unit: "%",
									min: 0,
									max: 100,
								},
							],
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ buttonRadius: newValue })
						}
					/>
				</div>
				{buttonWidth != "fixed" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={buttonSize}
							values={buttonSize}
							id="button-size"
							option={{
								id: "button-size",
								label: __("Button Size", "affiliatex"),
								type: "ab-radio",
								choices: {
									small: __("S", "affiliatex"),
									medium: __("M", "affiliatex"),
									large: __("L", "affiliatex"),
									xlarge: __("XL", "affiliatex"),
								},
								value: "medium",
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ buttonSize: newValue })
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonWidth}
						values={buttonWidth}
						id="button-width"
						option={{
							id: "button-width",
							label: __("Button Width", "affiliatex"),
							type: "ab-radio",
							choices: {
								fixed: __("Fixed", "affiliatex"),
								flexible: __("Flexible", "affiliatex"),
								full: __("Full", "affiliatex"),
							},
							value: "flexible",
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ buttonWidth: newValue })
						}
					/>
					{buttonWidth == "fixed" && (
						<GenericOptionType
							value={buttonFixWidth}
							values={buttonFixWidth}
							id="button-fix-width"
							option={{
								id: "button-fix-width",
								label: __("Button Size", "affiliatex"),
								type: "ab-slider",
								value: DefaultAttributes.buttonFixWidth.default,
								units: [
									{
										unit: "px",
										min: 0,
										max: 600,
									},
								],
							}}
							hasRevertButton={true}
							onChange={(newValue) => {
								setAttributes({ buttonFixWidth: newValue });
							}}
						/>
					)}
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonAlignment}
						values={buttonAlignment}
						id="button-alignment"
						option={{
							id: "button-alignment",
							label: __("Button Alignment", "affiliatex"),
							attr: { "data-type": "alignment" },
							type: "ab-radio",
							choices: {
								"flex-start": "",
								center: "",
								"flex-end": "",
							},
							value: "flex-start",
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ buttonAlignment: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-shadow-option">
					<GenericOptionType
						value={buttonShadow}
						values={buttonShadow}
						id="button-shadow"
						option={{
							id: "button-shadow",
							label: __("Box Shadow", "affiliatex"),
							type: "ab-box-shadow",
							divider: "top",
							value: buttonShadow,
						}}
						hasRevertButton={true}
						onChange={(newBtnShadow) =>
							setAttributes({ buttonShadow: newBtnShadow })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody
				title={__("Icon Settings", "affiliatex")}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Icon in button", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edButtonIcon}
					onChange={() =>
						setAttributes({ edButtonIcon: !edButtonIcon })
					}
				/>
				{edButtonIcon == true && (
					<div className="affx-btn-icon-wrapper">
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${ButtonIcon.value}`}
						>
							<label>
								{__("Select Title Icon", "affiliatex")}
							</label>
							<IconSelector
								value={ButtonIcon.name}
								enableSearch
								icons={[
									{ name: "check", value: "fas fa-check" },
									{
										name: "check-square",
										value: "fas fa-check-square",
									},
									{
										name: "check-square-outline",
										value: "far fa-check-square",
									},
									{
										name: "check-double",
										value: "fas fa-check-double",
									},
									{
										name: "check-circle",
										value: "fas fa-check-circle",
									},
									{
										name: "check-circle-outline",
										value: "far fa-check-circle",
									},
									{ name: "square", value: "fas fa-square" },
									{
										name: "square-outline",
										value: "far fa-square",
									},
									{ name: "circle", value: "fas fa-circle" },
									{
										name: "circle-outline",
										value: "far fa-circle",
									},
									{
										name: "arrow-right",
										value: "fas fa-arrow-right",
									},
									{
										name: "arrow-left",
										value: "fas fa-arrow-left",
									},
									{
										name: "arrow-circle-right",
										value: "fas fa-arrow-circle-right",
									},
									{
										name: "arrow-circle-left",
										value: "fas fa-arrow-circle-left",
									},
									{
										name: "arrow-alt-circle-right",
										value: "far fa-arrow-alt-circle-right",
									},
									{
										name: "arrow-alt-circle-left",
										value: "far fa-arrow-alt-circle-left",
									},
									{
										name: "long-arrow-alt-right",
										value: "fas fa-long-arrow-alt-right",
									},
									{
										name: "long-arrow-alt-left",
										value: "fas fa-long-arrow-alt-left",
									},
									{
										name: "chevron-right",
										value: "fas fa-chevron-right",
									},
									{
										name: "chevron-left",
										value: "fas fa-chevron-left",
									},
									{
										name: "angle-right",
										value: "fas fa-angle-right",
									},
									{
										name: "angle-left",
										value: "fas fa-angle-left",
									},
									{ name: "star", value: "fas fa-star" },
									{
										name: "star-outline",
										value: "far fa-star",
									},
									{
										name: "windows-close-fill",
										value: "fas fa-window-close",
									},
									{ name: "ban", value: "fas fa-ban" },
									{
										name: "window-close-simple",
										value: "far fa-window-close",
									},
									{ name: "times", value: "fas fa-times" },
									{
										name: "times-circle",
										value: "fas fa-times-circle",
									},
									{
										name: "times-circle-simple",
										value: "far fa-times-circle",
									},
									{
										name: "dot-circle-fill",
										value: "fas fa-dot-circle",
									},
									{
										name: "dot-circle-simple",
										value: "far fa-dot-circle",
									},
									{
										name: "thumb-up-fill",
										value: "fas fa-thumbs-up",
									},
									{
										name: "thumb-up-simple",
										value: "far fa-thumbs-up",
									},
									{
										name: "thumb-down-fill",
										value: "fas fa-thumbs-down",
									},
									{
										name: "thumb-down-simple",
										value: "far fa-thumbs-down",
									},
									{
										name: "info-simple",
										value: "fa fa-info",
									},
									{
										name: "info-circle",
										value: "fa fa-info-circle",
									},
									{
										name: "question-simple",
										value: "fa fa-question",
									},
									{
										name: "question-circle",
										value: "fa fa-question-circle",
									},
									{
										name: "trash-simple",
										value: "fa fa-trash",
									},
									{
										name: "exclamation-triangle",
										value: "fa fa-exclamation-triangle",
									},
									{
										name: "exclamation-simple",
										value: "fa fa-exclamation",
									},
									{
										name: "exclamation-circle",
										value: "fa fa-exclamation-circle",
									},
								]}
								onChange={(val) => {
									setAttributes({ ButtonIcon: val });
								}}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={iconPosition}
								values={iconPosition}
								id="icon-position"
								option={{
									id: "icon-position",
									label: __("Icon Position", "affiliatex"),
									type: "ab-radio",
									choices: {
										axBtnleft: __("Left", "affiliatex"),
										axBtnright: __("Right", "affiliatex"),
									},
								}}
								hasRevertButton={true}
								onChange={(newValue) =>
									setAttributes({ iconPosition: newValue })
								}
							/>
						</div>
						<div className="affiliate-blocks-option affiliate-icon-size">
							<GenericOptionType
								value={buttonIconSize}
								values={buttonIconSize}
								id="button-icon-size"
								option={{
									id: "button-icon-size",
									label: __("Icon Size", "affiliatex"),
									type: "ab-slider",
									value: buttonIconSize,
									units: [
										{
											unit: "px",
											min: 0,
											max: 200,
										},
									],
								}}
								hasRevertButton={false}
								onChange={(newValue) =>
									setAttributes({ buttonIconSize: newValue })
								}
							/>
						</div>
					</div>
				)}
			</PanelBody>
			<PanelBody title={__("Colors", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: buttonTextColor },
							textHoverColor: { color: buttonTextHoverColor },
						}}
						values={{
							textColor: { color: buttonTextColor },
							textHoverColor: { color: buttonTextHoverColor },
						}}
						option={{
							id: "button-text-color",
							label: __("Text Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.buttonTextColor
											.default,
								},
								textHoverColor: {
									color:
										DefaultAttributes.buttonTextHoverColor
											.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Text Color", "affiliatex"),
								},
								{
									id: "textHoverColor",
									title: __("Text Hover Color", "affiliatex"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								buttonTextColor: colorValue.textColor.color,
								buttonTextHoverColor:
									colorValue.textHoverColor.color,
							})
						}
					/>
				</div>
				{edButtonIcon == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								buttoniconcolor: { color: buttonIconColor },
								buttonIconHoverColor: {
									color: buttonIconHoverColor,
								},
							}}
							option={{
								id: "button-icon-color",
								label: __("Icon Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									buttoniconcolor: {
										color:
											DefaultAttributes.buttonIconColor
												.default,
									},
									buttonIconHoverColor: {
										color:
											DefaultAttributes
												.buttonIconHoverColor.default,
									},
								},
								pickers: [
									{
										id: "buttoniconcolor",
										title: __(
											"Button Icon Color",
											"affiliatex"
										),
									},
									{
										id: "buttonIconHoverColor",
										title: __(
											"Button Icon Hover Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									buttonIconColor:
										colorValue.buttoniconcolor.color,
									buttonIconHoverColor:
										colorValue.buttonIconHoverColor.color,
								})
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-2" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<label>{__("Price Tag Color", "affiliatex")}</label>
						<GenericOptionType
							value={{
								textColor: { color: priceTextColor },
								textBgColor: { color: priceBackgroundColor },
							}}
							option={{
								id: "button-price-tag-color",
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.priceTextColor
												.default,
									},
									textBgColor: {
										color:
											DefaultAttributes
												.priceBackgroundColor.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Text Color", "affiliatex"),
									},
									{
										id: "textBgColor",
										title: __(
											"Background Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									priceTextColor: colorValue.textColor.color,
									priceBackgroundColor:
										colorValue.textBgColor.color,
								})
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonBGType}
						values={buttonBGType}
						option={{
							id: "button-bg-type",
							label: __("Background Type", "affiliatex"),
							type: "ab-radio",
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
							value: "solid",
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ buttonBGType: newValue })
						}
					/>
				</div>
				{"solid" === buttonBGType && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								backgroundColor: { color: buttonBGColor },
								backgroundHoverColor: {
									color: buttonBGHoverColor,
								},
							}}
							option={{
								id: "button-price-tag-color",
								label: __("Background Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									backgroundColor: {
										color:
											DefaultAttributes.buttonBGColor
												.default,
									},
									backgroundHoverColor: {
										color:
											DefaultAttributes.buttonBGHoverColor
												.default,
									},
								},
								pickers: [
									{
										id: "backgroundColor",
										title: __(
											"Background Color",
											"affiliatex"
										),
									},
									{
										id: "backgroundHoverColor",
										title: __(
											"Background Hover Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									buttonBGColor:
										colorValue.backgroundColor.color,
									buttonBGHoverColor:
										colorValue.backgroundHoverColor.color,
								})
							}
						/>
					</div>
				)}
				{"gradient" === buttonBGType && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<label>{__("Background Gradient", "affiliatex")}</label>
						<GradientPicker
							value={buttonBgGradient}
							onChange={(gradientValue) =>
								setAttributes({
									buttonBgGradient: gradientValue,
								})
							}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={__("Typography", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonTypography}
						values={buttonTypography}
						id="button-typography"
						option={{
							id: "button-typography",
							label: __("Button Typography", "affiliatex"),
							type: "ab-typography",
							value: DefaultAttributes.buttonTypography.default,
						}}
						device="desktop"
						hasRevertButton={true}
						onChange={(newTypographyObject) => {
							setAttributes({
								buttonTypography: newTypographyObject,
							});
						}}
					/>
				</div>
			</PanelBody>
			<PanelBody title={__("Spacing", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonMargin}
						values={buttonMargin}
						id="button-margin"
						option={{
							id: "button-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.buttonMargin.default,
							responsive: true,
							units: [
								{
									unit: "",
									min: 0,
									max: 10,
									decimals: 1,
								},

								{
									unit: "px",
									min: 0,
									max: 50,
								},

								{
									unit: "em",
									min: 0,
									max: 50,
								},

								{
									unit: "pt",
									min: 0,
									max: 50,
								},

								{
									unit: "%",
									min: 0,
									max: 100,
								},
							],
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ buttonMargin: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonPadding}
						values={buttonPadding}
						id="button-padding"
						option={{
							id: "button-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.buttonPadding.default,
							responsive: true,
							units: [
								{
									unit: "",
									min: 0,
									max: 10,
									decimals: 1,
								},

								{
									unit: "px",
									min: 0,
									max: 50,
								},

								{
									unit: "em",
									min: 0,
									max: 50,
								},

								{
									unit: "pt",
									min: 0,
									max: 50,
								},

								{
									unit: "%",
									min: 0,
									max: 100,
								},
							],
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ buttonPadding: newValue })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
