import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import IconSelector from "../ui-components/icon-picker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes, className, isSelected }) => {
	const { InspectorControls } = wp.blockEditor;
	const {
		TextControl,
		PanelBody,
		SelectControl,
		ToggleControl,
		RangeControl,
	} = wp.components;
	const {
		edTitleIcon,
		noticeBorder,
		noticeBorderWidth,
		noticeBorderRadius,
		noticeTitle,
		noticeTitleIcon,
		titleTag1,
		layoutStyle,
		boxShadow,
		alignment,
		titleTypography,
		listTypography,
		noticeContentType,
		noticeListType,
		noticeListIcon,
		noticeTextColor,
		noticeTextTwoColor,
		noticeIconColor,
		noticeIconTwoColor,
		noticeBgType,
		noticeBgColor,
		noticeBgGradient,
		noticeBgTwoType,
		noticeBgTwoGradient,
		noticeBgTwoColor,
		noticeListColor,
		listBgType,
		listBgColor,
		listBgGradient,
		noticeMargin,
		titlePadding,
		contentPadding,
		noticePadding,
		noticeunorderedType,
		noticeIconSize,
		noticeListIconSize,
		titleAlignment,
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
							{ value: "layout-type-1", label: "Layout One" },
							{ value: "layout-type-2", label: "Layout Two" },
							{ value: "layout-type-3", label: "Layout Three" },
						]}
						onChange={(value) =>
							setAttributes({ layoutStyle: value })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody
				title={"General Settings"}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliate-border-option">
					<GenericOptionType
						value={noticeBorder}
						values={noticeBorder}
						id="notice-box-border"
						option={{
							id: "notice-box-border",
							label: __("Box Border", "affiliatex"),
							type: "ab-border",
							value: DefaultAttributes.noticeBorder.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ noticeBorder: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={noticeBorderWidth}
						values={noticeBorderWidth}
						id="notice-border-width"
						option={{
							id: "notice-border-width",
							label: __("Border Width", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.noticeBorderWidth.default,
							responsive: true,
							units: [
								{
									unit: "",
									min: 0,
									max: 100,
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
							setAttributes({ noticeBorderWidth: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={noticeBorderRadius}
						values={noticeBorderRadius}
						id="notice-border-radius"
						option={{
							id: "notice-border-radius",
							label: __("Border Radius", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.noticeBorderRadius.default,
							responsive: true,
							units: [
								{
									unit: "",
									min: 0,
									max: 100,
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
							setAttributes({ noticeBorderRadius: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-shadow-option">
					<GenericOptionType
						value={boxShadow}
						values={boxShadow}
						id="box-shadow"
						option={{
							id: "box-shadow",
							label: __("Box Shadow", "affiliatex"),
							type: "ab-box-shadow",
							divider: "top",
							value: DefaultAttributes.boxShadow.default,
						}}
						hasRevertButton={true}
						onChange={(newBoxShadow) =>
							setAttributes({ boxShadow: newBoxShadow })
						}
					/>
				</div>
				{(layoutStyle === "layout-type-2" ||
					layoutStyle === "layout-type-3") && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={noticePadding}
							values={noticePadding}
							id="title-padding"
							option={{
								id: "title-padding",
								label: __("Padding", "affiliatex"),
								type: "ab-spacing",
								value: DefaultAttributes.noticePadding.default,
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
								setAttributes({ noticePadding: newValue })
							}
						/>
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={"Notice Settings"}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				<SelectControl
					label={__("Heading Tag", "affiliatex")}
					value={titleTag1}
					className="affiliate-blocks-option"
					options={[
						{ value: "h2", label: "Heading 2 (h2)" },
						{ value: "h3", label: "Heading 3 (h3)" },
						{ value: "h4", label: "Heading 4 (h4)" },
						{ value: "h5", label: "Heading 5 (h5)" },
						{ value: "h6", label: "Heading 6 (h6)" },
					]}
					onChange={(value) => setAttributes({ titleTag1: value })}
				/>
				<TextControl
					label={__("Heading Title", "affiliatex")}
					className="affiliate-blocks-option affx-input-field"
					value={noticeTitle}
					onChange={(noticeTitle) => setAttributes({ noticeTitle })}
				/>
				{layoutStyle != "layout-type-3" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={titleAlignment}
							values={titleAlignment}
							id="title-alignment"
							option={{
								id: "title-alignment",
								label: __("Title Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value: DefaultAttributes.titleAlignment.default,
								choices: {
									left: "",
									center: "",
									right: "",
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ titleAlignment: newValue })
							}
						/>
					</div>
				)}
				<ToggleControl
					label={__("Show Title Icon", "affiliatex")}
					checked={!!edTitleIcon}
					onChange={() =>
						setAttributes({ edTitleIcon: !edTitleIcon })
					}
				/>
				{edTitleIcon && (
					<div
						className={`affiliate-blocks-option affiliate-icon-option ${noticeTitleIcon.value}`}
					>
						<label>{__("Select Title Icon", "affiliatex")}</label>
						<IconSelector
							value={noticeTitleIcon.name}
							enableSearch
							icons={[
								{ name: "check", value: "fa fa-check" },
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
								{ name: "star-outline", value: "far fa-star" },
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
								{ name: "info-simple", value: "fa fa-info" },
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
								{ name: "trash-simple", value: "fa fa-trash" },
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
								setAttributes({ noticeTitleIcon: val });
							}}
						/>
					</div>
				)}
				{edTitleIcon && (
					<div className="affiliate-blocks-option affiliate-icon-size">
						<RangeControl
							value={noticeIconSize}
							min={0}
							max={100}
							step={1}
							onChange={(newValue) =>
								setAttributes({ noticeIconSize: newValue })
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-wrapper">
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={noticeContentType}
							values={noticeContentType}
							id="notice-content-type"
							option={{
								id: "notice-content-type",
								label: __("Content Type", "affiliatex"),
								type: "ab-radio",
								value:
									DefaultAttributes.noticeContentType.default,
								choices: {
									list: __("List", "affiliatex"),
									paragraph: __("Paragraph", "affiliatex"),
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({
									noticeContentType: newValue,
								})
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={alignment}
							values={alignment}
							id="text-alignment"
							option={{
								id: "text-alignment",
								label: __("Text Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value: DefaultAttributes.alignment.default,
								choices: {
									left: "",
									center: "",
									right: "",
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ alignment: newValue })
							}
						/>
					</div>
					{noticeContentType === "list" && (
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={noticeListType}
								values={noticeListType}
								id="list-type"
								option={{
									id: "list-type",
									label: __("List Type", "affiliatex"),
									type: "ab-radio",
									value:
										DefaultAttributes.noticeListType
											.default,
									choices: {
										unordered: __(
											"Unordered",
											"affiliatex"
										),
										ordered: __("Ordered", "affiliatex"),
									},
								}}
								hasRevertButton={true}
								onChange={(newValue) =>
									setAttributes({
										noticeListType: newValue,
									})
								}
							/>
						</div>
					)}
					{noticeContentType === "list" &&
						noticeListType === "unordered" && (
							<div className="affiliate-blocks-option">
								<GenericOptionType
									value={noticeunorderedType}
									values={noticeunorderedType}
									id="unordered-type"
									option={{
										id: "unordered-type",
										label: __(
											"Unordered Type",
											"affiliatex"
										),
										type: "ab-radio",
										value:
											DefaultAttributes
												.noticeunorderedType.default,
										choices: {
											icon: __("Show Icon", "affiliatex"),
											bullet: __(
												"Show Bullet",
												"affiliatex"
											),
										},
									}}
									hasRevertButton={true}
									onChange={(newValue) =>
										setAttributes({
											noticeunorderedType: newValue,
										})
									}
								/>
							</div>
						)}
					{noticeContentType === "list" &&
						noticeListType === "unordered" &&
						noticeunorderedType === "icon" && (
							<div
								className={`affiliate-blocks-option affiliate-icon-option ${noticeListIcon.value}`}
							>
								<label>
									{__("Select List Icon", "affiliatex")}
								</label>
								<IconSelector
									label="Top List Icon"
									value={noticeListIcon.name}
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
											value:
												"far fa-arrow-alt-circle-right",
										},
										{
											name: "arrow-alt-circle-left",
											value:
												"far fa-arrow-alt-circle-left",
										},
										{
											name: "long-arrow-alt-right",
											value:
												"fas fa-long-arrow-alt-right",
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
										{
											name: "star",
											value: "fas fa-star",
										},
										{
											name: "star-outline",
											value: "far fa-star",
										},
										{
											name: "windows-close-fill",
											value: "fas fa-window-close",
										},
										{
											name: "ban",
											value: "fas fa-ban",
										},
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
											noticeListIcon: val,
										});
									}}
								/>
							</div>
						)}
					{noticeContentType === "list" &&
						noticeListType === "unordered" &&
						noticeunorderedType === "icon" && (
							<div className="affiliate-blocks-option affiliate-icon-size">
								<RangeControl
									value={noticeListIconSize}
									min={0}
									max={100}
									step={1}
									onChange={(newValue) =>
										setAttributes({
											noticeListIconSize: newValue,
										})
									}
								/>
							</div>
						)}
				</div>
			</PanelBody>
			<PanelBody
				title={"Colors"}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				{layoutStyle === "layout-type-1" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: noticeTextColor },
							}}
							option={{
								id: "button-text-color",
								label: __("Title Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.noticeTextColor
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
									noticeTextColor: colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				{(layoutStyle === "layout-type-2" ||
					layoutStyle === "layout-type-3") && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: noticeTextTwoColor },
							}}
							option={{
								id: "notice-title-color",
								label: __("Title Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.noticeTextTwoColor
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
									noticeTextTwoColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-1" && (
					<div className="affiliate-block-wrapper">
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={noticeBgType}
								values={noticeBgType}
								id="notice-bg-type"
								option={{
									id: "notice-bg-type",
									label: __(
										"Title Background Type",
										"affiliatex"
									),
									type: "ab-radio",
									value:
										DefaultAttributes.noticeBgType.default,
									choices: {
										solid: __("Solid Color", "affiliatex"),
										gradient: __("Gradient", "affiliatex"),
									},
								}}
								hasRevertButton={true}
								onChange={(newValue) =>
									setAttributes({ noticeBgType: newValue })
								}
							/>
						</div>
						{noticeBgType === "solid" && (
							<div className="affiliate-blocks-option affiliate-color-option">
								<GenericOptionType
									value={{
										bgColor: { color: noticeBgColor },
									}}
									option={{
										id: "notice-titleBg-color",
										label: __(
											"Title Background Color",
											"affiliatex"
										),
										type: "ab-color-picker",
										value: {
											bgColor: {
												color:
													DefaultAttributes
														.noticeBgColor.default,
											},
										},
										pickers: [
											{
												id: "bgColor",
												title: __(
													"Bg Color",
													"affiliatex"
												),
											},
										],
									}}
									hasRevertButton={true}
									onChange={(colorValue) =>
										setAttributes({
											noticeBgColor:
												colorValue.bgColor.color,
										})
									}
								/>
							</div>
						)}
						{noticeBgType === "gradient" && (
							<div className="affiliate-blocks-option affiliate-gradient-option">
								<GradientPicker
									value={noticeBgGradient}
									onChange={(gradientValue) =>
										setAttributes({
											noticeBgGradient: gradientValue,
										})
									}
								/>
							</div>
						)}
					</div>
				)}
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: noticeListColor },
						}}
						option={{
							id: "notice-list-color",
							label: __("Content Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.noticeListColor
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
								noticeListColor: colorValue.textColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={listBgType}
						values={listBgType}
						id="list-bg-type"
						option={{
							id: "list-bg-type",
							label: __("Content Background Type", "affiliatex"),
							type: "ab-radio",
							value: DefaultAttributes.listBgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ listBgType: newValue })
						}
					/>
				</div>
				{listBgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: { color: listBgColor },
							}}
							option={{
								id: "notice-list-bg-color",
								label: __(
									"Content Background Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes.listBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "bgColor",
										title: __(
											"List Bg Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									listBgColor: colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{listBgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={listBgGradient}
							onChange={(gradientValue) =>
								setAttributes({
									listBgGradient: gradientValue,
								})
							}
						/>
					</div>
				)}
				{(layoutStyle === "layout-type-1" ||
					layoutStyle === "layout-type-3") &&
					noticeContentType === "list" && (
						<div className="affiliate-blocks-option affiliate-color-option">
							<GenericOptionType
								value={{
									textColor: { color: noticeIconColor },
								}}
								option={{
									id: "notice-icon-color",
									label: __("List Icon Color", "affiliatex"),
									type: "ab-color-picker",
									value: {
										textColor: {
											color:
												DefaultAttributes
													.noticeIconColor.default,
										},
									},
									pickers: [
										{
											id: "textColor",
											title: __(
												"Icon Color",
												"affiliatex"
											),
										},
									],
								}}
								hasRevertButton={true}
								onChange={(colorValue) =>
									setAttributes({
										noticeIconColor:
											colorValue.textColor.color,
									})
								}
							/>
						</div>
					)}
				{layoutStyle !== "layout-type-1" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								textColor: { color: noticeIconTwoColor },
							}}
							option={{
								id: "notice-icon2-color",
								label: __("Icon Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									textColor: {
										color:
											DefaultAttributes.noticeIconTwoColor
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
									noticeIconTwoColor:
										colorValue.textColor.color,
								})
							}
						/>
					</div>
				)}
				{(layoutStyle === "layout-type-2" ||
					layoutStyle === "layout-type-3") && (
					<div className="affiliate-blocks-wrapper">
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={noticeBgTwoType}
								values={noticeBgTwoType}
								option={{
									id: "notice-bg-type",
									label: __(
										"Background Color Type",
										"affiliatex"
									),
									type: "ab-radio",
									value:
										DefaultAttributes.noticeBgTwoType
											.default,
									choices: {
										solid: __("Solid Color", "affiliatex"),
										gradient: __("Gradient", "affiliatex"),
									},
								}}
								hasRevertButton={true}
								onChange={(newValue) =>
									setAttributes({ noticeBgTwoType: newValue })
								}
							/>
						</div>
						{noticeBgTwoType === "solid" && (
							<div className="affiliate-blocks-option affiliate-color-option">
								<GenericOptionType
									value={{
										bgColor: { color: noticeBgTwoColor },
									}}
									option={{
										id: "notice-bg2-color",
										label: __(
											"Background Color",
											"affiliatex"
										),
										type: "ab-color-picker",
										value: {
											bgColor: {
												color:
													DefaultAttributes
														.noticeBgTwoColor
														.default,
											},
										},
										pickers: [
											{
												id: "bgColor",
												title: __(
													"Bg Color",
													"affiliatex"
												),
											},
										],
									}}
									hasRevertButton={true}
									onChange={(colorValue) =>
										setAttributes({
											noticeBgTwoColor:
												colorValue.bgColor.color,
										})
									}
								/>
							</div>
						)}
						{noticeBgTwoType === "gradient" && (
							<div className="affiliate-blocks-option affiliate-gradient-option">
								<GradientPicker
									value={noticeBgTwoGradient}
									onChange={(gradientValue) =>
										setAttributes({
											noticeBgTwoGradient: gradientValue,
										})
									}
								/>
							</div>
						)}
					</div>
				)}
			</PanelBody>
			<PanelBody
				title={__("Typography", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={titleTypography}
						values={titleTypography}
						option={{
							id: "title-typography",
							label: __("Title Typography", "affiliatex"),
							type: "ab-typography",
							value: DefaultAttributes.titleTypography.default,
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
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={listTypography}
						values={listTypography}
						option={{
							id: "content-typography",
							label: __("Content Typography", "affiliatex"),
							type: "ab-typography",
							value: DefaultAttributes.listTypography.default,
						}}
						device="desktop"
						hasRevertButton={true}
						onChange={(newTypographyObject) => {
							setAttributes({
								listTypography: newTypographyObject,
							});
						}}
					/>
				</div>
			</PanelBody>
			{layoutStyle === "layout-type-1" && (
				<PanelBody
					title={__("Spacing", "affiliatex")}
					initialOpen={false}
				>
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={noticeMargin}
							values={noticeMargin}
							id="notice-margin"
							option={{
								id: "notice-margin",
								label: __("Margin", "affiliatex"),
								type: "ab-spacing",
								value: DefaultAttributes.noticeMargin.default,
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
								setAttributes({ noticeMargin: newValue })
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={titlePadding}
							values={titlePadding}
							id="title-padding"
							option={{
								id: "title-padding",
								label: __("Title Padding", "affiliatex"),
								type: "ab-spacing",
								value: DefaultAttributes.titlePadding.default,
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
								setAttributes({ titlePadding: newValue })
							}
						/>
					</div>
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={contentPadding}
							values={contentPadding}
							id="content-padding"
							option={{
								id: "content-padding",
								label: __("Content Padding", "affiliatex"),
								type: "ab-spacing",
								value: DefaultAttributes.contentPadding.default,
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
								setAttributes({ contentPadding: newValue })
							}
						/>
					</div>
				</PanelBody>
			)}
		</InspectorControls>
	);
};
