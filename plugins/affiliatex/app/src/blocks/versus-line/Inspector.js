import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes, className, isSelected }) => {
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody } = wp.components;
	const {
		boxShadow,
		border,
		borderWidth,
		borderRadius,
		vsTextColor,
		vsBgColor,
		contentColor,
		versusRowColor,
		bgType,
		bgColorSolid,
		bgColorGradient,
		vsTypography,
		versusContentTypography,
		margin,
		padding,
	} = attributes;

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("General Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliatex-specifications-general">
					<div className="affiliate-blocks-option affiliate-shadow-option">
						<GenericOptionType
							value={boxShadow}
							values={boxShadow}
							id="versus-box-shadow"
							option={{
								id: "versus-box-shadow",
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
				title={__("Border Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliate-border-option">
					<GenericOptionType
						value={border}
						values={border}
						id="versus-border"
						option={{
							id: "versus-border",
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
						id="versus-border-width"
						option={{
							id: "versus-border-width",
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
						id="versus-border-radius"
						option={{
							id: "versus-border-radius",
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
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: vsTextColor },
							backgroundColor: { color: vsBgColor },
						}}
						option={{
							label: __("VS Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.vsTextColor.default,
								},
								backgroundColor: {
									color: DefaultAttributes.vsBgColor.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Text Color"),
								},
								{
									id: "backgroundColor",
									title: __("Background Color"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								vsTextColor: colorValue.textColor.color,
								vsBgColor: colorValue.backgroundColor.color,
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

				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: versusRowColor },
						}}
						option={{
							label: __("Table Row Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.versusRowColor
											.default,
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
								versusRowColor: colorValue.textColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={bgType}
						values={bgType}
						id="versus-bg-type"
						option={{
							id: "versus-bg-type",
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
				<div className="affiliate-blocks-option affiliatex-versus-typography">
					<div className="affiliate-blocks-option versus-typography">
						<GenericOptionType
							value={vsTypography}
							id="vs-label-typography"
							option={{
								id: "vs-label-typography",
								label: __("VS Label Typography", "affiliatex"),
								type: "ab-typography",
								value: DefaultAttributes.vsTypography.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									vsTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option versus-typography">
						<GenericOptionType
							value={versusContentTypography}
							id="content-typography"
							option={{
								id: "content-typography",
								label: __("Content Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.versusContentTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									versusContentTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				</div>
			</PanelBody>

			<PanelBody title={__("Spacing", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={margin}
						values={margin}
						id="versus-margin"
						option={{
							id: "versus-margin",
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
						id="versus-padding"
						option={{
							id: "versus-padding",
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
