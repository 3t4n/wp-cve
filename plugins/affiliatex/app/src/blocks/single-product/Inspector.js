import { RangeControl } from "@wordpress/components";
import { applyFilters } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import IconSelector from "../ui-components/icon-picker";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes }) => {
	const { InspectorControls } = wp.blockEditor;
	const {
		TextControl,
		PanelBody,
		SelectControl,
		TextareaControl,
		ToggleControl,
	} = wp.components;
	const {
		productLayout,
		productLayoutOptions,
		productTitle,
		productTitleTag,
		productTitleAlign,
		productTitleTypography,
		productTitleColor,
		productSubTitle,
		productSubTitleTag,
		productSubtitleAlign,
		productSubtitleTypography,
		productSubtitleColor,
		productIconList,
		productContentType,
		productContentTypography,
		productContentAlign,
		productContentColor,
		productBorderWidth,
		productBorderRadius,
		productShadow,
		ContentListType,
		PricingType,
		productImageAlign,
		productImageWidth,
		productImageExternal,
		productImageSiteStripe,
		productImageType,
		productImageCustomWidth,
		productSalePrice,
		productPrice,
		pricingTypography,
		productBorder,
		ratingContent,
		edRatings,
		edTitle,
		edSubtitle,
		edContent,
		edPricing,
		productRatingColor,
		ratingInactiveColor,
		productBgColorType,
		productBgGradient,
		productBGColor,
		contentSpacing,
		pricingHoverColor,
		pricingColor,
		contentMargin,
		productDivider,
		ratingStarSize,
		productRibbonLayout,
		edRibbon,
		ribbonText,
		ribbonBGColor,
		ribbonColor,
		ribbonContentTypography,
		edProductImage,
		edButton,
		iconColor,
		numberRatings,
		productPricingAlign,
		productRatingAlign,
		productRateNumberColor,
		productRateContentColor,
		productRateNumBgColor,
		productRateContentBgColor,
		productStarRatingAlign,
		numRatingTypography,
		imagePadding,
	} = attributes;

	const layoutClass =
		productLayout === "layoutOne"
			? " product-layout-1"
			: productLayout === "layoutTwo"
				? " product-layout-2"
				: productLayout === "layoutThree"
					? " product-layout-3"
					: "";

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("Layout Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affxduct-layout-opt">
					<SelectControl
						value={productLayout}
						options={productLayoutOptions}
						onChange={(value) =>
							setAttributes({ productLayout: value })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody
				title={__("Ribbon Settings", "affiliatex")}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Ribbon", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edRibbon}
					onChange={() => setAttributes({ edRibbon: !edRibbon })}
				/>
				{edRibbon == true && (
					<div className="affiliate-blocks-option affxduct-ribbon-settings">
						{productLayout !== "layoutTwo" && (
							<SelectControl
								value={productRibbonLayout}
								options={[
									{ value: "one", label: "Ribbon One" },
									{ value: "two", label: "Ribbon Two" },
								]}
								onChange={(value) =>
									setAttributes({
										productRibbonLayout: value,
									})
								}
							/>
						)}
						<TextControl
							label={__("Ribbon Text", "affiliatex")}
							className="affx-input-field"
							placeholder={__("Enter Ribbon Text", "affiliatex")}
							value={ribbonText}
							onChange={(ribbonText) =>
								setAttributes({ ribbonText })
							}
						/>
					</div>
				)}
				{applyFilters(
					"affx_single_product_ribbon_settings",
					null,
					attributes,
					setAttributes
				)}
			</PanelBody>
			<PanelBody
				title={"General Settings"}
				className={"affx-general-panel"}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Button", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edButton}
					onChange={() => setAttributes({ edButton: !edButton })}
				/>
				<ToggleControl
					label={__("Enable Product Image", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edProductImage}
					onChange={() =>
						setAttributes({ edProductImage: !edProductImage })
					}
				/>
				{edProductImage && layoutClass !== " product-layout-2" && (
					<>
						<GenericOptionType
							value={productImageAlign}
							values={productImageAlign}
							id="product-content-align"
							option={{
								id: "product-content-align",
								label: __("Image Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.productImageAlign.default,
								choices: { left: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ productImageAlign: newValue })
							}
						/>
						<br />
						<GenericOptionType
							value={productImageWidth}
							values={productImageWidth}
							id="product-image-width"
							option={{
								id: "product-content-width",
								label: __("Image Width", "affiliatex"),
								attr: { "data-type": "width" },
								type: "ab-radio",
								value:
									DefaultAttributes.productImageWidth.default,
								choices: {
									inherit: "Inherit",
									custom: "Custom",
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ productImageWidth: newValue })
							}
						/>
						<br />
						{productImageWidth === "custom" && (
							<TextControl
								label={__("Image width ( % )", "affiliatex")}
								className="affx-input-field"
								placeholder={__(
									"Enter Image Width",
									"affiliatex"
								)}
								value={productImageCustomWidth}
								onChange={(productImageCustomWidth) =>
									setAttributes({ productImageCustomWidth })
								}
							/>
						)}
					</>
				)}
				{edProductImage && (
					<GenericOptionType
						value={productImageType}
						values={productImageType}
						id="product-image-type"
						option={{
							id: "product-image-type",
							label: __("Image Source", "affiliatex"),
							attr: { "data-type": "width" },
							type: "ab-radio",
							value: DefaultAttributes.productImageType.default,
							choices: {
								default: "Upload",
								external: "External",
								sitestripe: "SiteStripe",
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({
								productImageType: newValue,
							})
						}
					/>
				)}
				{productImageType === "external" && (
					<TextareaControl
						label={__(
							__("External Link", "affiliatex"),
							"affiliatex"
						)}
						className="affx-input-field"
						placeholder={__("Enter Image Link", "affiliatex")}
						value={productImageExternal}
						onChange={(productImageExternal) =>
							setAttributes({ productImageExternal })
						}
					/>
				)}

				{productImageType === "sitestripe" && (
					<TextareaControl
						label={__(
							__("SiteStripe", "affiliatex"),
							"affiliatex"
						)}
						className="affx-input-field"
						placeholder={__("Enter SiteStripe Markup", "affiliatex")}
						value={productImageSiteStripe}
						onChange={(productImageSiteStripe) =>
							setAttributes({ productImageSiteStripe })
						}
					/>
				)}

				<div className="affiliate-blocks-option affiliate-border-option">
					<GenericOptionType
						value={productBorder}
						values={productBorder}
						id="product-border"
						option={{
							id: "product-border",
							label: __("Border", "affiliatex"),
							type: "ab-border",
							value: DefaultAttributes.productBorder.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ productBorder: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={productBorderWidth}
						values={productBorderWidth}
						id="product-padding"
						option={{
							id: "product-padding",
							label: __("Border Width", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.productBorderWidth.default,
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
							setAttributes({ productBorderWidth: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={productBorderRadius}
						values={productBorderRadius}
						id="product-padding"
						option={{
							id: "product-padding",
							label: __("Border Radius", "affiliatex"),
							type: "ab-spacing",
							value:
								DefaultAttributes.productBorderRadius.default,
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
							setAttributes({ productBorderRadius: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-shadow-option">
					<GenericOptionType
						value={productShadow}
						values={productShadow}
						id="product-border-shadow"
						option={{
							id: "product-border-shadow",
							label: __("Border Shadow", "affiliatex"),
							type: "ab-box-shadow",
							divider: "top",
							value: DefaultAttributes.productShadow.default,
						}}
						hasRevertButton={true}
						onChange={(newBtnShadow) =>
							setAttributes({ productShadow: newBtnShadow })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-shadow-option">
					<GenericOptionType
						value={productDivider}
						values={productDivider}
						id="product-border"
						option={{
							id: "product-border",
							label: __("Divider", "affiliatex"),
							type: "ab-border",
							value: DefaultAttributes.productDivider.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ productDivider: newValue })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody
				title={"Title Settings"}
				className={"affx-title-panel"}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Title", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edTitle}
					onChange={() => setAttributes({ edTitle: !edTitle })}
				/>
				{edTitle == true && (
					<div className="affiliate-blocks-option affxduct-title">
						<TextControl
							label={__("Product Title", "affiliatex")}
							className="affx-input-field"
							placeholder={__(
								"Enter Product Title",
								"affiliatex"
							)}
							value={productTitle}
							onChange={(productTitle) =>
								setAttributes({ productTitle })
							}
						/>
						<SelectControl
							label={__("Product Heading Tag", "affiliatex")}
							value={productTitleTag}
							options={[
								{ value: "h2", label: "Heading 2 (h2)" },
								{ value: "h3", label: "Heading 3 (h3)" },
								{ value: "h4", label: "Heading 4 (h4)" },
								{ value: "h5", label: "Heading 5 (h5)" },
								{ value: "h6", label: "Heading 6 (h6)" },
								{ value: "p", label: "Paragraph (p)" },
							]}
							onChange={(value) =>
								setAttributes({ productTitleTag: value })
							}
						/>
						<GenericOptionType
							value={productTitleAlign}
							values={productTitleAlign}
							id="product-title-align"
							option={{
								id: "product-title-align",
								label: __("Title Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.productTitleAlign.default,
								choices: { left: "", center: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ productTitleAlign: newValue })
							}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={"Subtitle Settings"}
				className={"affx-subtitle-panel"}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Subtitle", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edSubtitle}
					onChange={() => setAttributes({ edSubtitle: !edSubtitle })}
				/>
				{edSubtitle == true && (
					<div className="affiliate-blocks-option affxduct-subtitle">
						<TextControl
							label={__("Product Subtitle", "affiliatex")}
							className="affx-input-field"
							placeholder={__(
								"Enter Product Subtitle",
								"affiliatex"
							)}
							value={productSubTitle}
							onChange={(productSubTitle) =>
								setAttributes({ productSubTitle })
							}
						/>
						<SelectControl
							label={__("Product Subtitle Tag", "affiliatex")}
							value={productSubTitleTag}
							options={[
								{ value: "h2", label: "Heading 2 (h2)" },
								{ value: "h3", label: "Heading 3 (h3)" },
								{ value: "h4", label: "Heading 4 (h4)" },
								{ value: "h5", label: "Heading 5 (h5)" },
								{ value: "h6", label: "Heading 6 (h6)" },
								{ value: "p", label: "Paragraph (p)" },
							]}
							onChange={(value) =>
								setAttributes({ productSubTitleTag: value })
							}
						/>
						<GenericOptionType
							value={productSubtitleAlign}
							values={productSubtitleAlign}
							id="product-subtitle-align"
							option={{
								id: "product-subtitle-align",
								label: __("Sub Title Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.productSubtitleAlign
										.default,
								choices: { left: "", center: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({
									productSubtitleAlign: newValue,
								})
							}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={"Rating Settings"}
				className={"affx-pricing-panel"}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Rating", "affiliatex")}
					checked={!!edRatings}
					className="affiliate-blocks-option"
					onChange={() => setAttributes({ edRatings: !edRatings })}
				/>
				{edRatings == true && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={PricingType}
							id="rating-style"
							option={{
								id: "rating-style",
								type: "ab-radio",
								value: DefaultAttributes.PricingType.default,
								choices: {
									picture: __("Star rating", "affiliatex"),
									number: __("Score Box", "affiliatex"),
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ PricingType: newValue })
							}
						/>
					</div>
				)}
				{edRatings == true && PricingType === "picture" && (
					<div className="affiliate-blocks-option">
						<RangeControl
							label={__("Star Rating size", "affiliatex")}
							className="affiliate-blocks-option"
							value={ratingStarSize}
							min={10}
							max={100}
							step={2}
							onChange={(newValue) =>
								setAttributes({ ratingStarSize: newValue })
							}
						/>
					</div>
				)}
				{edRatings == true && PricingType === "number" && (
					<div className="affiliate-blocks-option affx-sp-rating-number">
						<TextControl
							type="number"
							label={__("Rating Number", "affiliatex")}
							className="affiliate-blocks-option affx-input-field"
							value={numberRatings}
							onChange={(numberRatings) =>
								setAttributes({ numberRatings })
							}
						/>
						<TextControl
							label={__("Rating Content", "affiliatex")}
							className="affiliate-blocks-option affx-input-field"
							value={ratingContent}
							onChange={(ratingContent) =>
								setAttributes({ ratingContent })
							}
						/>
					</div>
				)}
				{edRatings == true && PricingType === "picture" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={productStarRatingAlign}
							values={productStarRatingAlign}
							id="product-rating-align"
							option={{
								id: "product-rating-align",
								label: __("Rating Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.productStarRatingAlign
										.default,
								choices: { left: "", center: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({
									productStarRatingAlign: newValue,
								})
							}
						/>
					</div>
				)}
				{edRatings == true && PricingType === "number" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={productRatingAlign}
							values={productRatingAlign}
							id="product-rating-align"
							option={{
								id: "product-rating-align",
								label: __("Rating Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								choices: { left: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ productRatingAlign: newValue })
							}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={"Pricing Settings"}
				className={"affx-pricing-panel"}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Pricing", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edPricing}
					onChange={() => setAttributes({ edPricing: !edPricing })}
				/>
				{edPricing == true && (
					<div className="affiliate-blocks-option affxduct-pricing">
						<TextControl
							label={__("Product Marked Price", "affiliatex")}
							className="affxduct-price"
							placeholder={__(
								"Enter Product Price",
								"affiliatex"
							)}
							value={productPrice}
							onChange={(productPrice) =>
								setAttributes({ productPrice })
							}
						/>
						<TextControl
							label={__("Product Sale Price", "affiliatex")}
							className="affxduct-price"
							placeholder={__(
								"Enter Product Sale Price",
								"affiliatex"
							)}
							value={productSalePrice}
							onChange={(productSalePrice) =>
								setAttributes({ productSalePrice })
							}
						/>
						<GenericOptionType
							value={productPricingAlign}
							values={productPricingAlign}
							id="product-pricing-align"
							option={{
								id: "product-pricing-align",
								label: __("Pricing Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.productPricingAlign
										.default,
								choices: { left: "", center: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ productPricingAlign: newValue })
							}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={"Content Settings"}
				className={"affx-content-panel"}
				initialOpen={false}
			>
				<ToggleControl
					label={__("Enable Content", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!edContent}
					onChange={() => setAttributes({ edContent: !edContent })}
				/>
				{edContent == true && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={productContentType}
							values={productContentType}
							id="product-content-type"
							option={{
								id: "product-content-type",
								label: __("Content Type", "affiliatex"),
								type: "ab-radio",
								value:
									DefaultAttributes.productContentType
										.default,
								choices: {
									list: __("List", "affiliatex"),
									paragraph: __("Paragraph", "affiliatex"),
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ productContentType: newValue })
							}
						/>
					</div>
				)}
				{edContent == true && productContentType === "list" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={ContentListType}
							values={ContentListType}
							id="list-type"
							option={{
								id: "list-type",
								type: "ab-radio",
								value:
									DefaultAttributes.ContentListType.default,
								choices: {
									unordered: __(
										"Unordered List",
										"affiliatex"
									),
									ordered: __("Ordered List", "affiliatex"),
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ ContentListType: newValue })
							}
						/>
					</div>
				)}
				{edContent == true &&
					productContentType === "list" &&
					ContentListType === "unordered" && (
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${productIconList.value}`}
						>
							<label>
								{__("Select List Icon", "affiliatex")}
							</label>
							<IconSelector
								value={productIconList.name}
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
									setAttributes({ productIconList: val });
								}}
							/>
						</div>
					)}
				{edContent == true && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={productContentAlign}
							values={productContentAlign}
							id="product-content-align"
							option={{
								id: "product-content-align",
								label: __("Content Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.productContentAlign
										.default,
								choices: { left: "", center: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ productContentAlign: newValue })
							}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={"Colors"}
				className={"affx-color-panel"}
				initialOpen={false}
			>
				{edTitle == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: productTitleColor },
							}}
							option={{
								id: "title-color",
								label: __("Title Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.productTitleColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Title Color", "affiliatex"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									productTitleColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				{edSubtitle == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: productSubtitleColor },
							}}
							option={{
								id: "subtitle-color",
								label: __("Subtitle Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes
												.productSubtitleColor.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __(
											"Subtitle Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									productSubtitleColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				{edContent == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: productContentColor },
							}}
							option={{
								id: "content-color",
								label: __("Content Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes
												.productContentColor.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __(
											"Content Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									productContentColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				{edContent == true && productContentType === "list" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								iconColor: { color: iconColor },
							}}
							option={{
								id: "content-color",
								label: __("Icon Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									iconColor: {
										color:
											DefaultAttributes.iconColor.default,
									},
								},
								pickers: [
									{
										id: "iconColor",
										title: __("Icon Color", "affiliatex"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									iconColor: colorValue.iconColor.color,
								})
							}
						/>
					</div>
				)}
				{edPricing == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								pricingColor: { color: pricingColor },
								pricingHoverColor: { color: pricingHoverColor },
							}}
							option={{
								id: "pricing-color",
								label: __("Pricing Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									pricingColor: {
										color:
											DefaultAttributes.pricingColor
												.default,
									},
									pricingHoverColor: {
										color:
											DefaultAttributes.pricingHoverColor
												.default,
									},
								},
								pickers: [
									{
										id: "pricingColor",
										title: __(
											"Sale Price Color",
											"affiliatex"
										),
									},
									{
										id: "pricingHoverColor",
										title: __(
											"Marked Price Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									pricingColor: colorValue.pricingColor.color,
									pricingHoverColor:
										colorValue.pricingHoverColor.color,
								})
							}
						/>
					</div>
				)}
				{edRatings == true && PricingType === "picture" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								ratingColor: { color: productRatingColor },
								ratingInactiveColor: {
									color: ratingInactiveColor,
								},
							}}
							option={{
								id: "pricing-color",
								label: __("Rating Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									ratingColor: {
										color:
											DefaultAttributes.productRatingColor
												.default,
									},
									ratingInactiveColor: {
										color:
											DefaultAttributes
												.ratingInactiveColor.default,
									},
								},
								pickers: [
									{
										id: "ratingColor",
										title: __("Rating Color", "affiliatex"),
									},
									{
										id: "ratingInactiveColor",
										title: __(
											"Inactive Rating Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									productRatingColor:
										colorValue.ratingColor.color,
									ratingInactiveColor:
										colorValue.ratingInactiveColor.color,
								})
							}
						/>
					</div>
				)}
				{edRatings == true && PricingType === "number" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								productRateNumberColor: {
									color: productRateNumberColor,
								},
								productRateContentColor: {
									color: productRateContentColor,
								},
								productRateNumBgColor: {
									color: productRateNumBgColor,
								},
								productRateContentBgColor: {
									color: productRateContentBgColor,
								},
							}}
							option={{
								id: "score-box-color",
								label: __("Score Box Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									productRateNumberColor: {
										color:
											DefaultAttributes
												.productRateNumberColor.default,
									},
									productRateContentColor: {
										color:
											DefaultAttributes
												.productRateContentColor
												.default,
									},
									productRateNumBgColor: {
										color:
											DefaultAttributes
												.productRateNumBgColor.default,
									},
									productRateContentBgColor: {
										color:
											DefaultAttributes
												.productRateContentBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "productRateNumberColor",
										title: __(
											"Score Box Color",
											"affiliatex"
										),
									},
									{
										id: "productRateContentColor",
										title: __(
											"Content Rating Color",
											"affiliatex"
										),
									},
									{
										id: "productRateNumBgColor",
										title: __(
											"Score Box Background Color",
											"affiliatex"
										),
									},
									{
										id: "productRateContentBgColor",
										title: __(
											"Content Rating Background Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									productRateNumberColor:
										colorValue.productRateNumberColor.color,
									productRateContentColor:
										colorValue.productRateContentColor
											.color,
									productRateNumBgColor:
										colorValue.productRateNumBgColor.color,
									productRateContentBgColor:
										colorValue.productRateContentBgColor
											.color,
								})
							}
						/>
					</div>
				)}
				{edRibbon == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								ribbonColor: { color: ribbonColor },
								ribbonBackgroundColor: { color: ribbonBGColor },
							}}
							option={{
								id: "ribbon-color",
								label: __("Ribbon Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									ribbonColor: {
										color:
											DefaultAttributes.ribbonColor
												.default,
									},
									ribbonBackgroundColor: {
										color:
											DefaultAttributes.ribbonBGColor
												.default,
									},
								},
								pickers: [
									{
										id: "ribbonColor",
										title: __("Text Color", "affiliatex"),
									},
									{
										id: "ribbonBackgroundColor",
										title: __("Background Color"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									ribbonColor: colorValue.ribbonColor.color,
									ribbonBGColor:
										colorValue.ribbonBackgroundColor.color,
								})
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option affxduct-bg-type">
					<GenericOptionType
						value={productBgColorType}
						values={productBgColorType}
						id="product-bg-color-type"
						option={{
							id: "product-bg-color-type",
							label: __("Background Type", "affiliatex"),
							type: "ab-radio",
							value: DefaultAttributes.productBgColorType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ productBgColorType: newValue })
						}
					/>

					{productBgColorType === "solid" && (
						<div className="affiliate-blocks-option affiliate-color-option">
							<GenericOptionType
								value={{
									backgroundColor: { color: productBGColor },
								}}
								option={{
									id: "ribbon-color",
									label: __("Background Color", "affiliatex"),
									type: "ab-color-picker",
									value: {
										backgroundColor: {
											color:
												DefaultAttributes.productBGColor
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
										productBGColor:
											colorValue.backgroundColor.color,
									})
								}
							/>
						</div>
					)}

					{productBgColorType === "gradient" && (
						<div className="affiliate-blocks-option affiliate-gradient-option">
							<label>
								{__("Background Grdient", "affiliatex")}
							</label>
							<GradientPicker
								value={productBgGradient}
								onChange={(gradientValue) =>
									setAttributes({
										productBgGradient: gradientValue,
									})
								}
							/>
						</div>
					)}
				</div>
			</PanelBody>
			<PanelBody
				title={"Typography"}
				className={"affx-typography-panel"}
				initialOpen={false}
			>
				{edTitle == true && (
					<div className="affiliate-blocks-option affx-sp-title-typo">
						<GenericOptionType
							value={productTitleTypography}
							id="title-typography"
							option={{
								id: "title-typography",
								label: __("Title Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.productTitleTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									productTitleTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				)}
				{edSubtitle == true && (
					<div className="affiliate-blocks-option affx-sp-subtitle-typo">
						<GenericOptionType
							value={productSubtitleTypography}
							id="subTitle-typography"
							option={{
								id: "subTitle-typography",
								label: __("Subtitle Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.productSubtitleTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									productSubtitleTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				)}
				{edPricing == true && (
					<div className="affiliate-blocks-option affx-sp-price-typo">
						<GenericOptionType
							value={pricingTypography}
							id="pricing-typography"
							option={{
								id: "pricing-typography",
								label: __("Pricing Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.pricingTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									pricingTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				)}
				{edContent == true && (
					<div className="affiliate-blocks-option affx-sp-content-typo">
						<GenericOptionType
							value={productContentTypography}
							id="content-typography"
							option={{
								id: "content-typography",
								label: __("Content Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.productContentTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									productContentTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				)}
				{edRibbon == true && (
					<div className="affiliate-blocks-option affx-sp-ribbon-typo">
						<GenericOptionType
							value={ribbonContentTypography}
							id="ribbon-typography"
							option={{
								id: "ribbon-typography",
								label: __(
									"Ribbon Text Typography",
									"affiliatex"
								),
								type: "ab-typography",
								value:
									DefaultAttributes.ribbonContentTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									ribbonContentTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				)}
				{edRatings == true && PricingType === "number" && (
					<div className="affiliate-blocks-option affx-sp-ribbon-typo">
						<GenericOptionType
							value={numRatingTypography}
							id="rating-typography"
							option={{
								id: "rating-typography",
								label: __("Score Box Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.numRatingTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									numRatingTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={"Spacing"}
				className={"affx-general-panel"}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={imagePadding}
						values={imagePadding}
						id="image-padding"
						option={{
							id: "image-padding",
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
						value={contentMargin}
						values={contentMargin}
						id="content-margin"
						option={{
							id: "content-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.contentMargin.default,
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
							setAttributes({ contentMargin: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={contentSpacing}
						values={contentSpacing}
						id="content-padding"
						option={{
							id: "content-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.contentSpacing.default,
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
							setAttributes({ contentSpacing: newValue })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
