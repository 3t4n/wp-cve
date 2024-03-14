import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import IconSelector from "../ui-components/icon-picker";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes }) => {
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl, SelectControl } = wp.components;
	const {
		layoutStyle,
		edImage,
		edRibbon,
		edProductName,
		edRating,
		edPrice,
		edCounter,
		edButton1,
		edButton1Icon,
		button1Icon,
		button1IconAlign,
		edButton2,
		edButton2Icon,
		button2Icon,
		button2IconAlign,
		boxShadow,
		border,
		borderWidth,
		borderRadius,
		button1Padding,
		button1Margin,
		button2Padding,
		button2Margin,
		ribbonColor,
		ribbonBgColor,
		counterColor,
		counterBgColor,
		tableHeaderColor,
		tableHeaderBgColor,
		priceColor,
		ratingColor,
		ratingBgColor,
		rating2Color,
		rating2BgColor,
		starColor,
		starInactiveColor,
		titleColor,
		contentColor,
		bgType,
		bgColorSolid,
		bgColorGradient,
		button1TextColor,
		button1TextHoverColor,
		button1BgColor,
		button1BgHoverColor,
		button2TextColor,
		button2TextHoverColor,
		button2BgColor,
		button2BgHoverColor,
		margin,
		padding,
		ribbonTypography,
		counterTypography,
		priceTypography,
		buttonTypography,
		headerTypography,
		titleTypography,
		contentTypography,
		ratingTypography,
		rating2Typography,
		productIconList,
		productContentType,
		contentListType,
		productIconColor,
		imagePadding,
	} = attributes;

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
							{ value: "layoutOne", label: "Layout 1" },
							{ value: "layoutTwo", label: "Layout 2" },
							{ value: "layoutThree", label: "Layout 3" },
						]}
						onChange={(value) =>
							setAttributes({ layoutStyle: value })
						}
					/>
				</div>
			</PanelBody>

			<PanelBody
				title={__("General Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliatex-pdt-tbl-general">
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Image", "affiliatex")}
							checked={!!edImage}
							onChange={() =>
								setAttributes({
									edImage: !edImage,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Ribbon", "affiliatex")}
							checked={!!edRibbon}
							onChange={() =>
								setAttributes({
									edRibbon: !edRibbon,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Product Name", "affiliatex")}
							checked={!!edProductName}
							onChange={() =>
								setAttributes({
									edProductName: !edProductName,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Rating", "affiliatex")}
							checked={!!edRating}
							onChange={() =>
								setAttributes({
									edRating: !edRating,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Price", "affiliatex")}
							checked={!!edPrice}
							onChange={() =>
								setAttributes({
									edPrice: !edPrice,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Counter", "affiliatex")}
							checked={!!edCounter}
							onChange={() =>
								setAttributes({
									edCounter: !edCounter,
								})
							}
						/>
					</div>

					<div className="affiliate-blocks-option affiliate-shadow-option">
						<GenericOptionType
							value={boxShadow}
							values={boxShadow}
							id="pdt-tbl-box-shadow"
							option={{
								id: "pdt-tbl-box-shadow",
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
				title={"Content Settings"}
				className={"affx-content-panel"}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={productContentType}
						values={productContentType}
						id="product-content-type"
						option={{
							id: "product-content-type",
							label: __("Content Type", "affiliatex"),
							type: "ab-radio",
							choices: {
								list: __("List", "affiliatex"),
								paragraph: __("Paragraph", "affiliatex"),
							},
							value: DefaultAttributes.productContentType.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ productContentType: newValue })
						}
					/>
				</div>

				{productContentType === "list" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={contentListType}
							values={contentListType}
							id="list-type"
							option={{
								id: "list-type",
								type: "ab-radio",
								choices: {
									unordered: __(
										"Unordered List",
										"affiliatex"
									),
									ordered: __("Ordered List", "affiliatex"),
								},
								value:
									DefaultAttributes.contentListType.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ contentListType: newValue })
							}
						/>
					</div>
				)}
				{productContentType === "list" &&
					contentListType === "unordered" && (
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
			</PanelBody>

			<PanelBody
				title={__("Primary Button Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliatex-button-general">
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Primary Button", "affiliatex")}
							checked={!!edButton1}
							onChange={() =>
								setAttributes({
									edButton1: !edButton1,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Icon", "affiliatex")}
							className="affiliate-blocks-option"
							checked={!!edButton1Icon}
							onChange={() =>
								setAttributes({ edButton1Icon: !edButton1Icon })
							}
						/>
					</div>
					{edButton1Icon && (
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${button1Icon.value}`}
						>
							<label>{__("Select Icon", "affiliatex")}</label>
							<IconSelector
								value={button1Icon.name}
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
										button1Icon: val,
									});
								}}
							/>
						</div>
					)}
					{edButton1Icon && (
						<GenericOptionType
							value={button1IconAlign}
							values={button1IconAlign}
							id="product-content-align"
							option={{
								id: "product-content-align",
								label: __("Icon Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								choices: { left: "", right: "" },
								value:
									DefaultAttributes.button1IconAlign.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ button1IconAlign: newValue })
							}
						/>
					)}
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={button1Padding}
						values={button1Padding}
						id="pdt-tbl-button-padding"
						option={{
							id: "pdt-tbl-box-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.button1Padding.default,
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
							setAttributes({ button1Padding: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={button1Margin}
						values={button1Margin}
						id="pdt-tbl-button-margin"
						option={{
							id: "pdt-tbl-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.button1Margin.default,
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
							setAttributes({ button1Margin: newValue })
						}
					/>
				</div>
			</PanelBody>

			<PanelBody
				title={__("Secondary Button Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliatex-button-general">
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Secondary Button", "affiliatex")}
							checked={!!edButton2}
							onChange={() =>
								setAttributes({
									edButton2: !edButton2,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Icon", "affiliatex")}
							className="affiliate-blocks-option"
							checked={!!edButton2Icon}
							onChange={() =>
								setAttributes({ edButton2Icon: !edButton2Icon })
							}
						/>
					</div>
					{edButton2Icon && (
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${button2Icon.value}`}
						>
							<label>{__("Select Icon", "affiliatex")}</label>
							<IconSelector
								value={button2Icon.name}
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
										button2Icon: val,
									});
								}}
							/>
						</div>
					)}
					{edButton2Icon && (
						<GenericOptionType
							value={button2IconAlign}
							values={button2IconAlign}
							id="product-content-align"
							option={{
								id: "product-content-align",
								label: __("Icon Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								choices: { left: "", right: "" },
								value:
									DefaultAttributes.button2IconAlign.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ button2IconAlign: newValue })
							}
						/>
					)}
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={button2Padding}
						values={button2Padding}
						id="pdt-tbl-button-padding"
						option={{
							id: "pdt-tbl-box-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.button2Padding.default,
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
							setAttributes({ button2Padding: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={button2Margin}
						values={button2Margin}
						id="pdt-tbl-button-margin"
						option={{
							id: "pdt-tbl-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.button2Margin.default,
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
							setAttributes({ button2Margin: newValue })
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
						id="pdt-tbl-border"
						option={{
							id: "pdt-tbl-border",
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
						id="pdt-tbl-border-width"
						option={{
							id: "pdt-tbl-border-width",
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
						id="pdt-tbl-border-radius"
						option={{
							id: "pdt-tbl-border-radius",
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
				{edRibbon == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: ribbonColor },
								textBgColor: { color: ribbonBgColor },
							}}
							option={{
								id: "pc-ribbon-color",
								label: __("Ribbon Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.ribbonColor
												.default,
									},
									textBgColor: {
										color:
											DefaultAttributes.ribbonBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Text Color"),
									},
									{
										id: "textBgColor",
										title: __("Background Color"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									ribbonColor: colorValue.textColor.color,
									ribbonBgColor: colorValue.textBgColor.color,
								})
							}
						/>
					</div>
				)}

				{edCounter == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: counterColor },
								textBgColor: { color: counterBgColor },
							}}
							option={{
								id: "pc-counter-color",
								label: __("Counter Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.counterColor
												.default,
									},
									textBgColor: {
										color:
											DefaultAttributes.counterBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Text Color"),
									},
									{
										id: "textBgColor",
										title: __("Background Color"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									counterColor: colorValue.textColor.color,
									counterBgColor:
										colorValue.textBgColor.color,
								})
							}
						/>
					</div>
				)}

				{edRating == true && layoutStyle && layoutStyle == "layoutOne" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								ratingColor: { color: ratingColor },
								ratingBgColor: {
									color: ratingBgColor,
								},
							}}
							option={{
								id: "pc-rating-color",
								label: __("Rating Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									ratingColor: {
										color:
											DefaultAttributes.ratingColor
												.default,
									},
									ratingBgColor: {
										color:
											DefaultAttributes.ratingBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "ratingColor",
										title: __("Rating Color", "affiliatex"),
									},
									{
										id: "ratingBgColor",
										title: __(
											"Rating Background Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									ratingColor: colorValue.ratingColor.color,
									ratingBgColor:
										colorValue.ratingBgColor.color,
								})
							}
						/>
					</div>
				)}

				{edRating == true && layoutStyle && layoutStyle == "layoutTwo" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								ratingColor: { color: rating2Color },
								ratingBgColor: {
									color: rating2BgColor,
								},
							}}
							option={{
								id: "pc-rating-2-color",
								label: __("Rating Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									ratingColor: {
										color:
											DefaultAttributes.rating2Color
												.default,
									},
									ratingBgColor: {
										color:
											DefaultAttributes.rating2BgColor
												.default,
									},
								},
								pickers: [
									{
										id: "ratingColor",
										title: __("Rating Color", "affiliatex"),
									},
									{
										id: "ratingBgColor",
										title: __(
											"Rating Background Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									rating2Color: colorValue.ratingColor.color,
									rating2BgColor:
										colorValue.ratingBgColor.color,
								})
							}
						/>
					</div>
				)}

				{edRating == true &&
					layoutStyle &&
					layoutStyle == "layoutThree" && (
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
												DefaultAttributes.starColor
													.default,
										},
										inactiveColor: {
											color:
												DefaultAttributes
													.starInactiveColor.default,
										},
									},
									pickers: [
										{
											id: "ratingColor",
											title: __(
												"Star Color",
												"affiliatex"
											),
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

				{edPrice == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: priceColor },
							}}
							option={{
								id: "pc-price-color",
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

				{layoutStyle != "layoutThree" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: tableHeaderColor },
								textBgColor: { color: tableHeaderBgColor },
							}}
							option={{
								id: "pc-table-header-color",
								label: __("Table Header Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.tableHeaderColor
												.default,
									},
									textBgColor: {
										color:
											DefaultAttributes.tableHeaderBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Text Color"),
									},
									{
										id: "textBgColor",
										title: __("Background Color"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									tableHeaderColor:
										colorValue.textColor.color,
									tableHeaderBgColor:
										colorValue.textBgColor.color,
								})
							}
						/>
					</div>
				)}

				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: titleColor },
						}}
						option={{
							id: "pc-title-color",
							label: __("Title Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color: DefaultAttributes.titleColor.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Title Color"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								titleColor: colorValue.textColor.color,
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
							id: "pc-content-color",
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

				{productContentType === "list" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: productIconColor },
							}}
							option={{
								id: "pc-list-icon-color",
								label: __("List Icon Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.productIconColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Icon Color", "affiliatex"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									productIconColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}

				{edButton1 == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: button1TextColor },
								textHoverColor: {
									color: button1TextHoverColor,
								},
							}}
							option={{
								id: "pc-primary-btn-text-color",
								label: __(
									"Primary Button Text Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.button1TextColor
												.default,
									},
									textHoverColor: {
										color:
											DefaultAttributes
												.button1TextHoverColor.default,
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
									button1TextColor:
										colorValue.textColor.color,
									button1TextHoverColor:
										colorValue.textHoverColor.color,
								})
							}
						/>
					</div>
				)}

				{edButton1 == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								backgroundColor: { color: button1BgColor },
								backgroundHoverColor: {
									color: button1BgHoverColor,
								},
							}}
							option={{
								id: "pc-primary-btn-color",
								label: __("Primary Button Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									backgroundColor: {
										color:
											DefaultAttributes.button1BgColor
												.default,
									},
									backgroundHoverColor: {
										color:
											DefaultAttributes
												.button1BgHoverColor.default,
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
									button1BgColor:
										colorValue.backgroundColor.color,
									button1BgHoverColor:
										colorValue.backgroundHoverColor.color,
								})
							}
						/>
					</div>
				)}

				{edButton2 == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: button2TextColor },
								textHoverColor: {
									color: button2TextHoverColor,
								},
							}}
							option={{
								id: "pc-secn-btn-txt-color",
								label: __(
									"Secondary Button Text Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.button2TextColor
												.default,
									},
									textHoverColor: {
										color:
											DefaultAttributes
												.button2TextHoverColor.default,
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
									button2TextColor:
										colorValue.textColor.color,
									button2TextHoverColor:
										colorValue.textHoverColor.color,
								})
							}
						/>
					</div>
				)}

				{edButton2 == true && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								backgroundColor: { color: button2BgColor },
								backgroundHoverColor: {
									color: button2BgHoverColor,
								},
							}}
							option={{
								id: "pc-second-btn-color",
								label: __(
									"Secondary Button Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									backgroundColor: {
										color:
											DefaultAttributes.button2BgColor
												.default,
									},
									backgroundHoverColor: {
										color:
											DefaultAttributes
												.button2BgHoverColor.default,
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
									button2BgColor:
										colorValue.backgroundColor.color,
									button2BgHoverColor:
										colorValue.backgroundHoverColor.color,
								})
							}
						/>
					</div>
				)}

				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={bgType}
						values={bgType}
						id="pdt-tbl-bg-type"
						option={{
							id: "pdt-tbl-bg-type",
							label: __("Background Type", "affiliatex"),
							type: "ab-radio",
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
							value: DefaultAttributes.bgType.default,
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
								id: "pc-background-color",
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
				<div className="affiliate-blocks-option affiliatex-pdt-tbl-typography">
					{edRibbon && (
						<div className="affiliate-blocks-option pdt-tbl-title-typography">
							<GenericOptionType
								value={ribbonTypography}
								option={{
									id: "ribbon-typography",
									label: __(
										"Ribbon Typography",
										"affiliatex"
									),
									type: "ab-typography",
									value:
										DefaultAttributes.ribbonTypography
											.default,
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
					)}
					{edCounter && (
						<div className="affiliate-blocks-option pdt-tbl-title-typography">
							<GenericOptionType
								value={counterTypography}
								option={{
									id: "counter-typography",
									label: __(
										"Counter Typography",
										"affiliatex"
									),
									type: "ab-typography",
									value:
										DefaultAttributes.counterTypography
											.default,
								}}
								device="desktop"
								hasRevertButton={true}
								onChange={(newTypographyObject) => {
									setAttributes({
										counterTypography: newTypographyObject,
									});
								}}
							/>
						</div>
					)}
					{edRating && layoutStyle && layoutStyle == "layoutOne" && (
						<div className="affiliate-blocks-option pdt-tbl-rating-typography">
							<GenericOptionType
								value={ratingTypography}
								option={{
									id: "rating-typography",
									label: __(
										"Rating Typography",
										"affiliatex"
									),
									type: "ab-typography",
									value:
										DefaultAttributes.ratingTypography
											.default,
								}}
								device="desktop"
								hasRevertButton={true}
								onChange={(newTypographyObject) => {
									setAttributes({
										ratingTypography: newTypographyObject,
									});
								}}
							/>
						</div>
					)}
					{edRating && layoutStyle && layoutStyle == "layoutTwo" && (
						<div className="affiliate-blocks-option pdt-tbl-rating-typography">
							<GenericOptionType
								value={rating2Typography}
								option={{
									id: "rating-typography",
									label: __(
										"Rating Typography",
										"affiliatex"
									),
									type: "ab-typography",
									value:
										DefaultAttributes.rating2Typography
											.default,
								}}
								device="desktop"
								hasRevertButton={true}
								onChange={(newTypographyObject) => {
									setAttributes({
										rating2Typography: newTypographyObject,
									});
								}}
							/>
						</div>
					)}
					{edPrice && (
						<div className="affiliate-blocks-option pdt-tbl-title-typography">
							<GenericOptionType
								value={priceTypography}
								option={{
									id: "price-typography",
									label: __(
										"Pricing Typography",
										"affiliatex"
									),
									type: "ab-typography",
									value:
										DefaultAttributes.priceTypography
											.default,
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
					)}
					<div className="affiliate-blocks-option pdt-tbl-content-typography">
						<GenericOptionType
							value={buttonTypography}
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
					<div className="affiliate-blocks-option pdt-tbl-typography">
						<GenericOptionType
							value={headerTypography}
							option={{
								id: "table-typography",
								label: __(
									"Table Header Typography",
									"affiliatex"
								),
								type: "ab-typography",
								value:
									DefaultAttributes.headerTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									headerTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option pdt-tbl-typography">
						<GenericOptionType
							value={titleTypography}
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
					<div className="affiliate-blocks-option pdt-tbl-typography">
						<GenericOptionType
							value={contentTypography}
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
						id="pdt-tbl-img-padding"
						option={{
							id: "pdt-tbl-img-padding",
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
						id="pdt-tbl-margin"
						option={{
							id: "pdt-tbl-margin",
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
						id="pdt-tbl-padding"
						option={{
							id: "pdt-tbl-padding",
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
