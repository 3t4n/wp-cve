import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import IconSelector from "../ui-components/icon-picker";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes }) => {
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl } = wp.components;

	const {
		pcRibbon,
		starColor,
		starInactiveColor,
		pcImage,
		pcTitle,
		pcRating,
		pcPrice,
		boxShadow,
		pcButton,
		pcButtonIcon,
		buttonIconAlign,
		buttonIcon,
		buttonPadding,
		buttonMargin,
		border,
		borderWidth,
		borderRadius,
		priceColor,
		tableRowBgColor,
		contentColor,
		buttonTextColor,
		buttonTextHoverColor,
		bgType,
		bgColorSolid,
		bgColorGradient,
		buttonBgColor,
		buttonBgHoverColor,
		titleTypography,
		ribbonTypography,
		priceTypography,
		buttonTypography,
		contentTypography,
		margin,
		padding,
		titleColor,
		ribbonColor,
		ribbonTextColor,
		imagePadding,
	} = attributes;

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("General Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliatex-pc-general">
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Ribbon", "affiliatex")}
							checked={!!pcRibbon}
							onChange={() =>
								setAttributes({
									pcRibbon: !pcRibbon,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Images", "affiliatex")}
							checked={!!pcImage}
							onChange={() =>
								setAttributes({
									pcImage: !pcImage,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Title", "affiliatex")}
							checked={!!pcTitle}
							onChange={() =>
								setAttributes({
									pcTitle: !pcTitle,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Prices", "affiliatex")}
							checked={!!pcPrice}
							onChange={() =>
								setAttributes({
									pcPrice: !pcPrice,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Ratings", "affiliatex")}
							checked={!!pcRating}
							onChange={() =>
								setAttributes({
									pcRating: !pcRating,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option affiliate-shadow-option">
						<GenericOptionType
							value={boxShadow}
							values={boxShadow}
							id="pc-box-shadow"
							option={{
								id: "pc-box-shadow",
								label: __("Box Shadow", "affiliatex"),
								type: "ab-box-shadow",
								divider: "top",
								value: DefaultAttributes.boxShadow.default,
							}}
							hasRevertButton={true}
							onChange={(newBtnShadow) =>
								setAttributes({
									boxShadow: newBtnShadow,
								})
							}
						/>
					</div>
				</div>
			</PanelBody>
			<PanelBody
				title={__("Button Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliatex-button-general">
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Button", "affiliatex")}
							checked={!!pcButton}
							onChange={() =>
								setAttributes({
									pcButton: !pcButton,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Icon", "affiliatex")}
							className="affiliate-blocks-option"
							checked={!!pcButtonIcon}
							onChange={() =>
								setAttributes({ pcButtonIcon: !pcButtonIcon })
							}
						/>
					</div>
					{pcButtonIcon && (
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${buttonIcon.value}`}
						>
							<label>{__("Select Icon", "affiliatex")}</label>
							<IconSelector
								value={buttonIcon.name}
								enableSearch
								icons={[
									{
										name: "check",
										value: "fas fa-check",
									},
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
									{
										name: "square",
										value: "fas fa-square",
									},
									{
										name: "square-outline",
										value: "far fa-square",
									},
									{
										name: "circle",
										value: "fas fa-circle",
									},
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
									{
										name: "times",
										value: "fas fa-times",
									},
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
									setAttributes({
										buttonIcon: val,
									});
								}}
							/>
						</div>
					)}
					{pcButtonIcon && (
						<GenericOptionType
							value={buttonIconAlign}
							values={buttonIconAlign}
							id="product-content-align"
							option={{
								id: "product-content-align",
								label: __("Icon Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.buttonIconAlign.default,
								choices: { left: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ buttonIconAlign: newValue })
							}
						/>
					)}
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonPadding}
						values={buttonPadding}
						id="pc-button-padding"
						option={{
							id: "pc-box-padding",
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
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={buttonMargin}
						values={buttonMargin}
						id="pc-button-margin"
						option={{
							id: "pc-margin",
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
			</PanelBody>
			<PanelBody
				title={__("Border Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliate-border-option">
					<GenericOptionType
						value={border}
						values={border}
						id="pc-border"
						option={{
							id: "pc-border",
							label: __("Border", "affiliatex"),
							type: "ab-border",
							value: DefaultAttributes.border.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({
								border: newValue,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={borderWidth}
						values={borderWidth}
						id="pc-border-width"
						option={{
							id: "pc-border-width",
							label: __("Border Width", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.borderWidth.default,
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
							setAttributes({
								borderWidth: newValue,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={borderRadius}
						values={borderRadius}
						id="pc-border-radius"
						option={{
							id: "pc-border-radius",
							label: __("Border Radius", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.borderRadius.default,
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
							setAttributes({
								borderRadius: newValue,
							})
						}
					/>
				</div>
			</PanelBody>
			<PanelBody title={__("Colors", "affiliatex")} initialOpen={false}>
				{pcRibbon && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								ribbonColor: { color: ribbonColor },
								ribbonTextColor: {
									color: ribbonTextColor,
								},
							}}
							option={{
								id: "pc-ribbon-color",
								label: __("Ribbon Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									ribbonColor: {
										color:
											DefaultAttributes.ribbonColor
												.default,
									},
									ribbonTextColor: {
										color:
											DefaultAttributes.ribbonTextColor
												.default,
									},
								},
								pickers: [
									{
										id: "ribbonColor",
										title: __("Text Color"),
									},
									{
										id: "ribbonTextColor",
										title: __("Text Hover Color"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									ribbonColor: colorValue.ribbonColor.color,
									ribbonTextColor:
										colorValue.ribbonTextColor.color,
								})
							}
						/>
					</div>
				)}
				{pcTitle && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								titleColor: { color: titleColor },
							}}
							option={{
								id: "pc-title-color",
								label: __("Title Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									titleColor: {
										color:
											DefaultAttributes.titleColor
												.default,
									},
								},
								pickers: [
									{
										id: "titleColor",
										title: __("Title Color", "affiliatex"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									titleColor: colorValue.titleColor.color,
								})
							}
						/>
					</div>
				)}
				{pcPrice && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: priceColor },
							}}
							option={{
								id: "pc-title-color",
								label: __("Price Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.priceColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Price Color", "affiliatex"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									priceColor: colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				{pcRating && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								ratingColor: { color: starColor },
								inactiveColor: {
									color: starInactiveColor,
								},
							}}
							option={{
								id: "pc-star-color",
								label: __("Star Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									ratingColor: {
										color:
											DefaultAttributes.starColor.default,
									},
									inactiveColor: {
										color:
											DefaultAttributes.starInactiveColor
												.default,
									},
								},
								pickers: [
									{
										id: "ratingColor",
										title: __("Star Color", "affiliatex"),
									},
									{
										id: "inactiveColor",
										title: __(
											"Inactive Star Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									starColor: colorValue.ratingColor.color,
									starInactiveColor:
										colorValue.inactiveColor.color,
								})
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							backgroundColor: { color: tableRowBgColor },
						}}
						option={{
							id: "pc-tablebg-color",
							label: __(
								"Alternate Table Row Color",
								"affiliatex"
							),
							type: "ab-color-picker",
							value: {
								backgroundColor: {
									color:
										DefaultAttributes.tableRowBgColor
											.default,
								},
							},
							pickers: [
								{
									id: "backgroundColor",
									title: __("Background Color"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								tableRowBgColor:
									colorValue.backgroundColor.color,
							})
						}
					/>
				</div>

				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: contentColor },
						}}
						option={{
							id: "pc-tablebg-color",
							label: __("Content Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.contentColor.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Text Color"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								contentColor: colorValue.textColor.color,
							})
						}
					/>
				</div>
				{pcButton && (
					<>
						<div className="affiliate-blocks-option affiliate-color-option">
							<GenericOptionType
								value={{
									textColor: { color: buttonTextColor },
									textHoverColor: {
										color: buttonTextHoverColor,
									},
								}}
								option={{
									id: "pc-text-color",
									label: __(
										"Button Text Color",
										"affiliatex"
									),
									type: "ab-color-picker",
									value: {
										textColor: {
											color:
												DefaultAttributes
													.buttonTextColor.default,
										},
										textHoverColor: {
											color:
												DefaultAttributes
													.buttonTextHoverColor
													.default,
										},
									},
									pickers: [
										{
											id: "textColor",
											title: __("Text Color"),
										},
										{
											id: "textHoverColor",
											title: __("Text Hover Color"),
										},
									],
								}}
								hasRevertButton={true}
								onChange={(colorValue) =>
									setAttributes({
										buttonTextColor:
											colorValue.textColor.color,
										buttonTextHoverColor:
											colorValue.textHoverColor.color,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option affiliate-color-option">
							<GenericOptionType
								value={{
									backgroundColor: { color: buttonBgColor },
									backgroundHoverColor: {
										color: buttonBgHoverColor,
									},
								}}
								option={{
									id: "pc-text-color",
									label: __("Button Color", "affiliatex"),
									type: "ab-color-picker",
									value: {
										backgroundColor: {
											color:
												DefaultAttributes.buttonBgColor
													.default,
										},
										backgroundHoverColor: {
											color:
												DefaultAttributes
													.buttonBgHoverColor.default,
										},
									},
									pickers: [
										{
											id: "backgroundColor",
											title: __("Background Color"),
										},
										{
											id: "backgroundHoverColor",
											title: __("Background Hover Color"),
										},
									],
								}}
								hasRevertButton={true}
								onChange={(colorValue) =>
									setAttributes({
										buttonBgColor:
											colorValue.backgroundColor.color,
										buttonBgHoverColor:
											colorValue.backgroundHoverColor
												.color,
									})
								}
							/>
						</div>
					</>
				)}

				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={bgType}
						values={bgType}
						id="pc-bg-type"
						option={{
							id: "pc-bg-type",
							label: __("Background Type", "affiliatex"),
							type: "ab-radio",
							value: DefaultAttributes.bgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({
								bgType: newValue,
							})
						}
					/>
				</div>
				{bgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: {
									color: bgColorSolid,
								},
							}}
							option={{
								id: "pc-bg-color",
								label: __("Background Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes.bgColorSolid
												.default,
									},
								},
								pickers: [
									{
										id: "bgColor",
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
									bgColorSolid: colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{bgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={bgColorGradient}
							onChange={(gradientValue) =>
								setAttributes({
									bgColorGradient: gradientValue,
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
				<div className="affiliate-blocks-option affiliatex-pc-typography">
					<div className="affiliate-blocks-option pc-title-typography">
						<GenericOptionType
							value={titleTypography}
							id="title-typography"
							option={{
								id: "title-typography",
								label: __("Title Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.titleTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									titleTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option pc-title-typography">
						<GenericOptionType
							value={ribbonTypography}
							id="ribbon-typography"
							option={{
								id: "ribbon-typography",
								label: __("Ribbon Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.ribbonTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									ribbonTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option pc-title-typography">
						<GenericOptionType
							value={priceTypography}
							id="pricing-typography"
							option={{
								id: "pricing-typography",
								label: __("Pricing Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.priceTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									priceTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option pc-content-typography">
						<GenericOptionType
							value={buttonTypography}
							id="button-typography"
							option={{
								id: "button-typography",
								label: __("Button Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.buttonTypography.default,
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
					<div className="affiliate-blocks-option pc-typography">
						<GenericOptionType
							value={contentTypography}
							id="content-typography"
							option={{
								id: "content-typography",
								label: __("Content Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.contentTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									contentTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				</div>
			</PanelBody>
			<PanelBody title={__("Spacing", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={imagePadding}
						values={imagePadding}
						id="pc-image-padding"
						option={{
							id: "pc-image-padding",
							label: __("Image Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.imagePadding.default,
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
							setAttributes({ imagePadding: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={margin}
						values={margin}
						id="pc-margin"
						option={{
							id: "pc-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.margin.default,
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
							setAttributes({ margin: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={padding}
						values={padding}
						id="pc-padding"
						option={{
							id: "pc-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.padding.default,
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
							setAttributes({ padding: newValue })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
