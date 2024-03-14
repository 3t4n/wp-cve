import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes, className, isSelected }) => {
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl, SelectControl } = wp.components;
	const {
		layoutStyle,
		specificationBorder,
		specificationBorderWidth,
		specificationBorderRadius,
		specificationBoxShadow,
		specificationBgType,
		specificationBgColorSolid,
		specificationBgColorGradient,
		specificationTitleTypography,
		specificationLabelTypography,
		specificationValueTypography,
		specificationTitleColor,
		specificationTitleBgColor,
		specificationLabelColor,
		specificationValueColor,
		specificationRowColor,
		edSpecificationTitle,
		specificationTitleAlign,
		specificationLabelAlign,
		specificationValueAlign,
		specificationMargin,
		specificationPadding,
		specificationColumnWidth,
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
							{ value: "layout-1", label: "Layout 1" },
							{ value: "layout-2", label: "Layout 2" },
							{ value: "layout-3", label: "Layout 3" },
						]}
						onChange={(value) =>
							setAttributes({ layoutStyle: value })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affx-layout-options">
					<GenericOptionType
						value={specificationColumnWidth}
						values={specificationColumnWidth}
						id="specification-table-layout"
						option={{
							id: "specification-table-layout",
							label: __("Table Column", "affiliatex"),
							attr: { "data-type": "" },
							type: "ab-radio",
							value:
								DefaultAttributes.specificationColumnWidth
									.default,
							choices: {
								styleOne:
									'<svg width="67" height="20" viewBox="0 0 67 20"><g id="Group_3" data-name="Group 3" transform="translate(-119 -103)"><g id="Group_2" data-name="Group 2" transform="translate(22 -77)"><rect id="Rectangle_5" data-name="Rectangle 5" width="45" height="20" transform="translate(119 180)" fill="#e6e9ec"/></g><g id="Group_1" data-name="Group 1" transform="translate(-46 -77)"><rect id="Rectangle_6" data-name="Rectangle 6" width="21" height="20" transform="translate(165 180)" fill="#e6e9ec"/></g></g></svg><span class="label">33,66</span>',
								styleTwo:
									'<svg xmlns="http://www.w3.org/2000/svg" width="67" height="20" viewBox="0 0 67 20"><g id="Group_4" data-name="Group 4" transform="translate(-119 -129)"><rect id="Rectangle_1" data-name="Rectangle 1" width="33" height="20" transform="translate(119 129)" fill="#e6e9ec"/><rect id="Rectangle_2" data-name="Rectangle 2" width="33" height="20" transform="translate(153 129)" fill="#e6e9ec"/></g></svg><span class="label">50,50</span>',
								styleThree:
									'<svg xmlns="http://www.w3.org/2000/svg" width="67" height="20" viewBox="0 0 67 20"><g id="Group_5" data-name="Group 5" transform="translate(-119 -155)"><rect id="Rectangle_3" data-name="Rectangle 3" width="45" height="20" transform="translate(119 155)" fill="#e6e9ec"/><rect id="Rectangle_4" data-name="Rectangle 4" width="21" height="20" transform="translate(165 155)" fill="#e6e9ec"/></g></svg><span class="label">66,33</span>',
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({
								specificationColumnWidth: newValue,
							})
						}
					/>
				</div>
			</PanelBody>

			<PanelBody
				title={__("General Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliatex-specifications-general">
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Title", "affiliatex")}
							checked={!!edSpecificationTitle}
							onChange={() =>
								setAttributes({
									edSpecificationTitle: !edSpecificationTitle,
								})
							}
						/>
					</div>
					{edSpecificationTitle && (
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={specificationTitleAlign}
								values={specificationTitleAlign}
								id="specification-title-align"
								option={{
									id: "specification-title-align",
									label: __("Title Alignment", "affiliatex"),
									attr: { "data-type": "alignment" },
									type: "ab-radio",
									value:
										DefaultAttributes
											.specificationTitleAlign.default,
									choices: {
										left: "",
										center: "",
										right: "",
									},
								}}
								hasRevertButton={true}
								onChange={(newValue) =>
									setAttributes({
										specificationTitleAlign: newValue,
									})
								}
							/>
						</div>
					)}
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={specificationLabelAlign}
							values={specificationLabelAlign}
							id="specification-label-align"
							option={{
								id: "specification-label-align",
								label: __("Label Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.specificationLabelAlign
										.default,
								choices: {
									left: "",
									center: "",
									right: "",
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({
									specificationLabelAlign: newValue,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={specificationValueAlign}
							values={specificationValueAlign}
							id="specification-value-align"
							option={{
								id: "specification-value-align",
								label: __("Value Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value:
									DefaultAttributes.specificationValueAlign
										.default,
								choices: {
									left: "",
									center: "",
									right: "",
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({
									specificationValueAlign: newValue,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option affiliate-shadow-option">
						<GenericOptionType
							value={specificationBoxShadow}
							values={specificationBoxShadow}
							id="specifications-box-shadow"
							option={{
								id: "specifications-box-shadow",
								label: __("Box Shadow", "affiliatex"),
								type: "ab-box-shadow",
								divider: "top",
								value:
									DefaultAttributes.specificationBoxShadow
										.default,
							}}
							hasRevertButton={true}
							onChange={(newBtnShadow) =>
								setAttributes({
									specificationBoxShadow: newBtnShadow,
								})
							}
						/>
					</div>
				</div>
			</PanelBody>

			<PanelBody
				title={__("Border Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliate-border-option">
					<GenericOptionType
						value={specificationBorder}
						values={specificationBorder}
						id="specification-border"
						option={{
							id: "specification-border",
							label: __("Border", "affiliatex"),
							type: "ab-border",
							value:
								DefaultAttributes.specificationBorder.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({
								specificationBorder: newValue,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={specificationBorderWidth}
						values={specificationBorderWidth}
						id="specification-border-width"
						option={{
							id: "specification-border-width",
							label: __("Border Width", "affiliatex"),
							type: "ab-spacing",
							value:
								DefaultAttributes.specificationBorderWidth
									.default,
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
								specificationBorderWidth: newValue,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={specificationBorderRadius}
						values={specificationBorderRadius}
						id="specification-border-radius"
						option={{
							id: "specification-border-radius",
							label: __("Border Radius", "affiliatex"),
							type: "ab-spacing",
							value:
								DefaultAttributes.specificationBorderRadius
									.default,
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
								specificationBorderRadius: newValue,
							})
						}
					/>
				</div>
			</PanelBody>

			<PanelBody title={__("Colors", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: specificationTitleColor },
							textBgColor: { color: specificationTitleBgColor },
						}}
						option={{
							id: "title-color",
							label: __("Title Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes
											.specificationTitleColor.default,
								},
								textBgColor: {
									color:
										DefaultAttributes
											.specificationTitleBgColor.default,
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
								specificationTitleColor:
									colorValue.textColor.color,
								specificationTitleBgColor:
									colorValue.textBgColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: specificationLabelColor },
						}}
						option={{
							id: "label-color",
							label: __("Label Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes
											.specificationLabelColor.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Label Color", "affiliatex"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								specificationLabelColor:
									colorValue.textColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: specificationValueColor },
						}}
						option={{
							id: "value-color",
							label: __("Value Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes
											.specificationValueColor.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Value Color", "affiliatex"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								specificationValueColor:
									colorValue.textColor.color,
							})
						}
					/>
				</div>
				{layoutStyle && layoutStyle != "layout-1" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: specificationRowColor },
							}}
							option={{
								id: "value-color",
								label: __("Table Row Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes
												.specificationRowColor.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Row Color", "affiliatex"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									specificationRowColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={specificationBgType}
						values={specificationBgType}
						id="specification-bg-type"
						option={{
							id: "specification-bg-type",
							label: __("Background Color Type", "affiliatex"),
							type: "ab-radio",
							value:
								DefaultAttributes.specificationBgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({
								specificationBgType: newValue,
							})
						}
					/>
				</div>
				{specificationBgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: {
									color: specificationBgColorSolid,
								},
							}}
							option={{
								id: "bg-color",
								label: __("Background Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes
												.specificationBgColorSolid
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
									specificationBgColorSolid:
										colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{specificationBgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={specificationBgColorGradient}
							onChange={(gradientValue) =>
								setAttributes({
									specificationBgColorGradient: gradientValue,
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
				<div className="affiliate-blocks-option affiliatex-specification-typography">
					<div className="affiliate-blocks-option specification-title-typography">
						<GenericOptionType
							value={specificationTitleTypography}
							id="title-typography"
							option={{
								id: "title-typography",
								label: __("Title Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes
										.specificationTitleTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									specificationTitleTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option specification-label-typography">
						<GenericOptionType
							value={specificationLabelTypography}
							option={{
								id: "label-typography",
								label: __("Label Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes
										.specificationLabelTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									specificationLabelTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option specification-value-typography">
						<GenericOptionType
							value={specificationValueTypography}
							option={{
								id: "value-typography",
								label: __("Value Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes
										.specificationValueTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									specificationValueTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				</div>
			</PanelBody>

			<PanelBody title={__("Spacing", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={specificationMargin}
						values={specificationMargin}
						id="specification-margin"
						option={{
							id: "specification-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value:
								DefaultAttributes.specificationMargin.default,
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
							setAttributes({ specificationMargin: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={specificationPadding}
						values={specificationPadding}
						id="specification-padding"
						option={{
							id: "specification-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value:
								DefaultAttributes.specificationPadding.default,
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
							setAttributes({ specificationPadding: newValue })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
