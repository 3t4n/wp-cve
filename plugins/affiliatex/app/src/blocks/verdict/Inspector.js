import { RangeControl, SelectControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes }) => {
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl, TextControl } = wp.components;
	const {
		verdictLayout,
		edProsCons,
		verdictTitleTypography,
		verdictContentTypography,
		contentAlignment,
		verdictBorder,
		verdictBorderWidth,
		verdictBorderRadius,
		verdictBoxPadding,
		verdictMargin,
		verdictBoxShadow,
		verdictTitleColor,
		verdictContentColor,
		verdictBgType,
		verdictBgColorSolid,
		verdictBgColorGradient,
		edverdictTotalScore,
		scoreTextColor,
		scoreBgTopColor,
		scoreBgBotColor,
		verdictTotalScore,
		ratingContent,
		edRatingsArrow,
		verdictArrowColor,
		ratingAlignment,
		verdictTitleTag,
	} = attributes;

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("Layout Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliatex-verdict-layout">
					<SelectControl
						value={verdictLayout}
						options={[
							{ value: "layoutOne", label: "Layout One" },
							{ value: "layoutTwo", label: "Layout Two" },
						]}
						onChange={(value) =>
							setAttributes({ verdictLayout: value })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody
				title={__("General Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliatex-verdict-general">
					<SelectControl
						label={__("Verdict Heading Tag", "affiliatex")}
						value={verdictTitleTag}
						options={[
							{ value: "h2", label: "Heading 2 (h2)" },
							{ value: "h3", label: "Heading 3 (h3)" },
							{ value: "h4", label: "Heading 4 (h4)" },
							{ value: "h5", label: "Heading 5 (h5)" },
							{ value: "h6", label: "Heading 6 (h6)" },
							{ value: "p", label: "Paragraph (p)" },
						]}
						onChange={(value) =>
							setAttributes({ verdictTitleTag: value })
						}
					/>
					{verdictLayout === "layoutTwo" && (
						<div className="affiliate-blocks-option">
							<ToggleControl
								label={__("Display arrow", "affiliatex")}
								checked={!!edRatingsArrow}
								onChange={() =>
									setAttributes({
										edRatingsArrow: !edRatingsArrow,
									})
								}
							/>
						</div>
					)}
					{verdictLayout === "layoutOne" && (
						<div className="affiliate-blocks-option">
							<ToggleControl
								label={__("Show Pros and Cons", "affiliatex")}
								checked={!!edProsCons}
								onChange={() =>
									setAttributes({ edProsCons: !edProsCons })
								}
							/>
						</div>
					)}
					{verdictLayout === "layoutTwo" && (
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentAlignment}
								values={contentAlignment}
								id="content-alignment"
								option={{
									id: "content-alignment",
									label: __(
										"Content Alignment",
										"affiliatex"
									),
									attr: { "data-type": "alignment" },
									type: "ab-radio",
									value:
										DefaultAttributes.contentAlignment
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
										contentAlignment: newValue,
									})
								}
							/>
						</div>
					)}
					<div className="affiliate-blocks-option affiliate-shadow-option">
						<GenericOptionType
							value={verdictBoxShadow}
							values={verdictBoxShadow}
							id="verdict-box-shadow"
							option={{
								id: "verdict-box-shadow",
								label: __("Box Shadow", "affiliatex"),
								type: "ab-box-shadow",
								divider: "top",
								value:
									DefaultAttributes.verdictBoxShadow.default,
							}}
							hasRevertButton={true}
							onChange={(newBtnShadow) =>
								setAttributes({
									verdictBoxShadow: newBtnShadow,
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
						value={verdictBorder}
						values={verdictBorder}
						id="verdict-border"
						option={{
							id: "verdict-border",
							label: __("Border", "affiliatex"),
							type: "ab-border",
							value: DefaultAttributes.verdictBorder.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ verdictBorder: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={verdictBorderWidth}
						values={verdictBorderWidth}
						id="verdict-border-width"
						option={{
							id: "verdict-border-width",
							label: __("Border Width", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.verdictBorderWidth.default,
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
							setAttributes({ verdictBorderWidth: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={verdictBorderRadius}
						values={verdictBorderRadius}
						id="verdict-border-radius"
						option={{
							id: "verdict-border-radius",
							label: __("Border Radius", "affiliatex"),
							type: "ab-spacing",
							value:
								DefaultAttributes.verdictBorderRadius.default,
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
							setAttributes({ verdictBorderRadius: newValue })
						}
					/>
				</div>
			</PanelBody>
			{verdictLayout === "layoutOne" && (
				<PanelBody
					title={__("Rating Settings", "affiliatex")}
					initialOpen={false}
				>
					<div className="affiliate-blocks-option">
						<ToggleControl
							label={__("Enable Score Rating", "affiliatex")}
							checked={!!edverdictTotalScore}
							onChange={() =>
								setAttributes({
									edverdictTotalScore: !edverdictTotalScore,
								})
							}
						/>
					</div>
					{edverdictTotalScore && (
						<div className="affx-verdict-rating-number">
							<div className="affiliate-blocks-option">
								<RangeControl
									label={__("Total Score", "affiliatex")}
									value={verdictTotalScore}
									min={0}
									max={10}
									step={0.5}
									onChange={(newValue) =>
										setAttributes({
											verdictTotalScore: newValue,
										})
									}
								/>
							</div>
							<div className="affiliate-blocks-option">
								<TextControl
									label={__(
										"Rating Score Content",
										"affiliatex"
									)}
									className="affx-input-field"
									value={ratingContent}
									onChange={(ratingContent) =>
										setAttributes({ ratingContent })
									}
								/>
							</div>
							<div className="affiliate-blocks-option">
								<GenericOptionType
									value={ratingAlignment}
									values={ratingAlignment}
									id="title-alignment"
									option={{
										id: "title-alignment",
										label: __(
											"Rating Alignment",
											"affiliatex"
										),
										attr: { "data-type": "alignment" },
										type: "ab-radio",
										value:
											DefaultAttributes.ratingAlignment
												.default,
										choices: {
											left: "",
											right: "",
										},
									}}
									hasRevertButton={true}
									onChange={(newValue) =>
										setAttributes({
											ratingAlignment: newValue,
										})
									}
								/>
							</div>
						</div>
					)}
				</PanelBody>
			)}
			<PanelBody title={__("Colors", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: verdictTitleColor },
						}}
						option={{
							id: "title-color",
							label: __("Title Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.verdictTitleColor
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
								verdictTitleColor: colorValue.textColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: verdictContentColor },
						}}
						option={{
							id: "content-color",
							label: __("Content Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.verdictContentColor
											.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Content Color", "affiliatex"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								verdictContentColor: colorValue.textColor.color,
							})
						}
					/>
				</div>
				{verdictLayout === "layoutOne" && edverdictTotalScore && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: scoreTextColor },
								bgTopColor: { color: scoreBgTopColor },
								bgBotColor: { color: scoreBgBotColor },
							}}
							option={{
								id: "score-box-color",
								label: __("Score Box Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.scoreTextColor
												.default,
									},
									bgTopColor: {
										color:
											DefaultAttributes.scoreBgTopColor
												.default,
									},
									bgBotColor: {
										color:
											DefaultAttributes.scoreBgBotColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Text Color", "affiliatex"),
									},
									{
										id: "bgTopColor",
										title: __(
											"Top Background Color",
											"affiliatex"
										),
									},
									{
										id: "bgBotColor",
										title: __(
											"Bottom Background Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									scoreTextColor: colorValue.textColor.color,
									scoreBgTopColor:
										colorValue.bgTopColor.color,
									scoreBgBotColor:
										colorValue.bgBotColor.color,
								})
							}
						/>
					</div>
				)}
				{verdictLayout === "layoutTwo" && edRatingsArrow && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: verdictArrowColor },
							}}
							option={{
								id: "arrow-color",
								label: __("Arrow Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.verdictArrowColor
												.default,
									},
								},
								pickers: [
									{
										id: "textColor",
										title: __("Arrow Color", "affiliatex"),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									verdictArrowColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={verdictBgType}
						values={verdictBgType}
						id="pros-bg-type"
						option={{
							id: "pros-bg-type",
							label: __("Background Type", "affiliatex"),
							type: "ab-radio",
							value: DefaultAttributes.verdictBgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ verdictBgType: newValue })
						}
					/>
				</div>
				{verdictBgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: { color: verdictBgColorSolid },
							}}
							option={{
								id: "arrow-color",
								label: __("Background Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes
												.verdictBgColorSolid.default,
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
									verdictBgColorSolid:
										colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{verdictBgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={verdictBgColorGradient}
							onChange={(gradientValue) =>
								setAttributes({
									verdictBgColorGradient: gradientValue,
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
				<div className="affiliate-blocks-option affiliatex-verdict-typography">
					<div className="affiliate-blocks-option verdict-title-typography">
						<GenericOptionType
							value={verdictTitleTypography}
							id="title-typography"
							option={{
								id: "title-typography",
								label: __("Title Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.verdictTitleTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									verdictTitleTypography: newTypographyObject,
								});
							}}
						/>
					</div>
					<div className="affiliate-blocks-option verdict-content-typography">
						<GenericOptionType
							value={verdictContentTypography}
							id="content-typography"
							option={{
								id: "content-typography",
								label: __("Content Typography", "affiliatex"),
								type: "ab-typography",
								value:
									DefaultAttributes.verdictContentTypography
										.default,
							}}
							device="desktop"
							hasRevertButton={true}
							onChange={(newTypographyObject) => {
								setAttributes({
									verdictContentTypography: newTypographyObject,
								});
							}}
						/>
					</div>
				</div>
			</PanelBody>
			<PanelBody title={__("Spacing", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option affiliatex-verdict-general">
					<GenericOptionType
						value={verdictBoxPadding}
						values={verdictBoxPadding}
						id="verdict-box-padding"
						option={{
							id: "verdict-box-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.verdictBoxPadding.default,
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
							setAttributes({ verdictBoxPadding: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={verdictMargin}
						values={verdictMargin}
						id="verdict-margin"
						option={{
							id: "verdict-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.verdictMargin.default,
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
							setAttributes({ verdictMargin: newValue })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
