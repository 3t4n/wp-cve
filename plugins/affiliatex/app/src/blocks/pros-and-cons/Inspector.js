import { __ } from "@wordpress/i18n";
import GenericOptionType from "../ui-components/GenericOptionType";
import IconSelector from "../ui-components/icon-picker";
import GradientPicker from "../ui-components/options/background/GradientPicker";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes, className, isSelected }) => {
	const { InspectorControls } = wp.blockEditor;
	const {
		TextControl,
		ToggleControl,
		PanelBody,
		SelectControl,
		RangeControl,
	} = wp.components;
	const {
		prosTitle,
		consTitle,
		prosIconStatus,
		consIconStatus,
		prosIcon,
		consIcon,
		titleTag1,
		layoutStyle,
		boxShadow,
		alignment,
		alignmentThree,
		titleTypography,
		listTypography,
		contentType,
		listType,
		unorderedType,
		prosListIcon,
		consListIcon,
		prosTextColor,
		prosTextColorThree,
		prosIconSize,
		prosIconColor,
		prosBgType,
		prosBgColor,
		prosBgGradient,
		prosListColor,
		consTextColor,
		consIconSize,
		consTextColorThree,
		consIconColor,
		consBgType,
		consBgColor,
		consBgGradient,
		consListColor,
		prosListBgType,
		prosListBgColor,
		prosListBgGradient,
		consListBgType,
		consListBgColor,
		consListBgGradient,
		titleMargin,
		titlePadding,
		contentMargin,
		contentPadding,
		prosBorder,
		prosBorderThree,
		titleBorderWidthOne,
		titleBorderRadiusOne,
		titleBorderWidthTwo,
		titleBorderRadiusTwo,
		titleBorderWidthThree,
		titleBorderRadiusThree,
		titleBorderWidthFour,
		titleBorderRadiusFour,
		prosContentBorder,
		prosContentBorderThree,
		contentBorderWidthOne,
		contentBorderRadiusOne,
		contentBorderWidthTwo,
		contentBorderRadiusTwo,
		contentBorderWidthThree,
		contentBorderRadiusThree,
		contentBorderWidthFour,
		contentBorderRadiusFour,
		consBorder,
		consBorderThree,
		consContentBorder,
		consContentBorderThree,
		margin,
		padding,
		contentAlignment,
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
							{ value: "layout-type-4", label: "Layout Four" },
						]}
						onChange={(value) =>
							setAttributes({ layoutStyle: value })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody
				title={__("General Settings", "affiliatex")}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option">
					<SelectControl
						label={__("Pros and Cons Heading Tag", "affiliatex")}
						className="affiliate-blocks-option"
						value={titleTag1}
						options={[
							{ value: "h2", label: "Heading 2 (h2)" },
							{ value: "h3", label: "Heading 3 (h3)" },
							{ value: "h4", label: "Heading 4 (h4)" },
							{ value: "h5", label: "Heading 5 (h5)" },
							{ value: "h6", label: "Heading 6 (h6)" },
							{ value: "p", label: "Paragraph (p)" },
						]}
						onChange={(value) =>
							setAttributes({ titleTag1: value })
						}
					/>
				</div>
				{layoutStyle !== "layout-type-4" && (
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
				)}
				{layoutStyle !== "layout-type-3" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={alignment}
							values={alignment}
							id="text-alignment"
							option={{
								id: "text-alignment",
								label: __("Title Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value: DefaultAttributes.alignment.default,
								choices: { left: "", center: "", right: "" },
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ alignment: newValue })
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-3" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={alignmentThree}
							values={alignmentThree}
							id="text-alignment-three"
							option={{
								id: "text-alignment-three",
								label: __("Title Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								value: DefaultAttributes.alignmentThree.default,
								choices: {
									"flex-start": "",
									center: "",
									"flex-end": "",
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ alignmentThree: newValue })
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={contentType}
						values={contentType}
						id="content-type"
						option={{
							id: "content-type",
							label: __("Content Type", "affiliatex"),
							type: "ab-radio",
							value: DefaultAttributes.contentType.default,
							choices: {
								list: __("List", "affiliatex"),
								paragraph: __("Paragraph", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ contentType: newValue })
						}
					/>
				</div>
				{contentType === "list" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={listType}
							values={listType}
							id="list-type"
							option={{
								id: "list-type",
								label: __("List Type", "affiliatex"),
								type: "ab-radio",
								value: DefaultAttributes.listType.default,
								choices: {
									unordered: __("Unordered", "affiliatex"),
									ordered: __("Ordered", "affiliatex"),
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ listType: newValue })
							}
						/>
					</div>
				)}
				{contentType === "list" && listType === "unordered" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={unorderedType}
							values={unorderedType}
							id="unordered-type"
							option={{
								id: "unordered-type",
								label: __("Unordered Type", "affiliatex"),
								type: "ab-radio",
								value: DefaultAttributes.unorderedType.default,
								choices: {
									icon: __("Show Icon", "affiliatex"),
									bullet: __("Show Bullet", "affiliatex"),
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ unorderedType: newValue })
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={contentAlignment}
						values={contentAlignment}
						id="content-alignment"
						option={{
							id: "content-alignment",
							label: __("Content Alignment", "affiliatex"),
							attr: { "data-type": "alignment" },
							value: DefaultAttributes.contentAlignment.default,
							type: "ab-radio",
							choices: { left: "", center: "", right: "" },
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ contentAlignment: newValue })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody
				title={__("Pros Settings", "affiliatex")}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				<TextControl
					label={__("Pros Heading Title", "affiliatex")}
					className="affiliate-blocks-option affx-input-field"
					value={prosTitle}
					onChange={(prosTitle) => setAttributes({ prosTitle })}
				/>
				<ToggleControl
					label={__("Enable Pros Title Icon", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!prosIconStatus}
					onChange={() =>
						setAttributes({ prosIconStatus: !prosIconStatus })
					}
				/>
				{prosIconStatus && (
					<>
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${prosListIcon.value}`}
						>
							<label>
								{__("Select Pros Title Icon", "affiliatex")}
							</label>
							<IconSelector
								value={prosListIcon.name}
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
									setAttributes({ prosListIcon: val });
								}}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<RangeControl
								value={prosIconSize}
								min={5}
								max={40}
								step={1}
								onChange={(newValue) =>
									setAttributes({ prosIconSize: newValue })
								}
							/>
						</div>
					</>
				)}
				{contentType === "list" &&
					listType === "unordered" &&
					unorderedType === "icon" && (
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${prosIcon.value}`}
						>
							<label>
								{__("Select Pros List Icon", "affiliatex")}
							</label>
							<IconSelector
								value={prosIcon.name}
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
									setAttributes({ prosIcon: val });
								}}
							/>
						</div>
					)}
			</PanelBody>
			<PanelBody
				title={__("Cons Settings", "affiliatex")}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				<TextControl
					label={__("Cons Heading Title", "affiliatex")}
					className="affiliate-blocks-option affx-input-field"
					value={consTitle}
					onChange={(consTitle) => setAttributes({ consTitle })}
				/>
				<ToggleControl
					label={__("Enable Cons Title Icon", "affiliatex")}
					className="affiliate-blocks-option"
					checked={!!consIconStatus}
					onChange={() =>
						setAttributes({ consIconStatus: !consIconStatus })
					}
				/>
				{consIconStatus && (
					<>
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${consListIcon.value}`}
						>
							<label>
								{__("Select Cons Title Icon", "affiliatex")}
							</label>
							<IconSelector
								value={consListIcon.name}
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
									setAttributes({ consListIcon: val });
								}}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<RangeControl
								value={consIconSize}
								min={5}
								max={40}
								step={1}
								onChange={(newValue) =>
									setAttributes({ consIconSize: newValue })
								}
							/>
						</div>
					</>
				)}

				{contentType === "list" &&
					listType === "unordered" &&
					unorderedType === "icon" && (
						<div
							className={`affiliate-blocks-option affiliate-icon-option ${consIcon.value}`}
						>
							<label>
								{__("Select Cons List Icon", "affiliatex")}
							</label>
							<IconSelector
								value={consIcon.name}
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
									setAttributes({ consIcon: val });
								}}
							/>
						</div>
					)}
			</PanelBody>
			<PanelBody
				title={__("Border Settings", "affiliatex")}
				initialOpen={false}
			>
				{layoutStyle !== "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={prosBorder}
							values={prosBorder}
							id="pros-border"
							option={{
								id: "pros-border",
								label: __("Pros Title Border", "affiliatex"),
								type: "ab-border",
								value: DefaultAttributes.prosBorder.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ prosBorder: newValue })
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={prosBorderThree}
							values={prosBorderThree}
							id="pros-border"
							option={{
								id: "pros-border",
								label: __("Pros Title Border", "affiliatex"),
								type: "ab-border",
								value:
									DefaultAttributes.prosBorderThree.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ prosBorderThree: newValue })
							}
						/>
					</div>
				)}
				{layoutStyle !== "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={prosContentBorder}
							values={prosContentBorder}
							id="pros-content-border"
							option={{
								id: "pros-content-border",
								label: __("Pros Content Border", "affiliatex"),
								type: "ab-border",
								value:
									DefaultAttributes.prosContentBorder.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ prosContentBorder: newValue })
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={prosContentBorderThree}
							values={prosContentBorderThree}
							id="pros-content-border"
							option={{
								id: "pros-content-border",
								label: __("Pros Content Border", "affiliatex"),
								type: "ab-border",
								value:
									DefaultAttributes.prosContentBorderThree
										.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({
									prosContentBorderThree: newValue,
								})
							}
						/>
					</div>
				)}
				{layoutStyle !== "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={consBorder}
							values={consBorder}
							id="cons-border"
							option={{
								id: "cons-border",
								label: __("Cons Title Border", "affiliatex"),
								type: "ab-border",
								value: DefaultAttributes.consBorder.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ consBorder: newValue })
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={consBorderThree}
							values={consBorderThree}
							id="cons-border"
							option={{
								id: "cons-border",
								label: __("Cons Title Border", "affiliatex"),
								type: "ab-border",
								value:
									DefaultAttributes.consBorderThree.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ consBorderThree: newValue })
							}
						/>
					</div>
				)}
				{layoutStyle !== "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={consContentBorder}
							values={consContentBorder}
							id="cons-content-border"
							option={{
								id: "cons-content-border",
								label: __("Cons Content Border", "affiliatex"),
								type: "ab-border",
								value:
									DefaultAttributes.consContentBorder.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ consContentBorder: newValue })
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-border-option">
						<GenericOptionType
							value={consContentBorderThree}
							values={consContentBorderThree}
							id="cons-content-border"
							option={{
								id: "cons-content-border",
								label: __("Cons Content Border", "affiliatex"),
								type: "ab-border",
								value:
									DefaultAttributes.consContentBorderThree
										.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({
									consContentBorderThree: newValue,
								})
							}
						/>
					</div>
				)}
				{layoutStyle === "layout-type-1" && (
					<>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderWidthOne}
								values={titleBorderWidthOne}
								id="title-border-width"
								option={{
									id: "title-border-width",
									label: __(
										"Title Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderWidthOne
											.default,
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
									setAttributes({
										titleBorderWidthOne: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderRadiusOne}
								values={titleBorderRadiusOne}
								id="title-border-radius"
								option={{
									id: "title-border-radius",
									label: __(
										"Title Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderRadiusOne
											.default,
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
									setAttributes({
										titleBorderRadiusOne: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderWidthOne}
								values={contentBorderWidthOne}
								id="content-border-width"
								option={{
									id: "content-border-width",
									label: __(
										"Content Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.contentBorderWidthOne
											.default,
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
									setAttributes({
										contentBorderWidthOne: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderRadiusOne}
								values={contentBorderRadiusOne}
								id="content-border-radius"
								option={{
									id: "content-border-radius",
									label: __(
										"Content Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.contentBorderRadiusOne
											.default,
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
									setAttributes({
										contentBorderRadiusOne: newValue,
									})
								}
							/>
						</div>
					</>
				)}
				{layoutStyle === "layout-type-2" && (
					<>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderWidthTwo}
								values={titleBorderWidthTwo}
								id="title-border-width"
								option={{
									id: "title-border-width",
									label: __(
										"Title Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderWidthTwo
											.default,
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
									setAttributes({
										titleBorderWidthTwo: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderRadiusTwo}
								values={titleBorderRadiusTwo}
								id="title-border-radius"
								option={{
									id: "title-border-radius",
									label: __(
										"Title Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderRadiusTwo
											.default,
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
									setAttributes({
										titleBorderRadiusTwo: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderWidthTwo}
								values={contentBorderWidthTwo}
								id="content-border-width"
								option={{
									id: "content-border-width",
									label: __(
										"Content Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.contentBorderWidthTwo
											.default,
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
									setAttributes({
										contentBorderWidthTwo: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderRadiusTwo}
								values={contentBorderRadiusTwo}
								id="content-border-radius"
								option={{
									id: "content-border-radius",
									label: __(
										"Content Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.contentBorderRadiusTwo
											.default,
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
									setAttributes({
										contentBorderRadiusTwo: newValue,
									})
								}
							/>
						</div>
					</>
				)}
				{layoutStyle === "layout-type-3" && (
					<>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderWidthThree}
								values={titleBorderWidthThree}
								id="title-border-width"
								option={{
									id: "title-border-width",
									label: __(
										"Title Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderWidthThree
											.default,
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
									setAttributes({
										titleBorderWidthThree: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderRadiusThree}
								values={titleBorderRadiusThree}
								id="title-border-radius"
								option={{
									id: "title-border-radius",
									label: __(
										"Title Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderRadiusThree
											.default,
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
									setAttributes({
										titleBorderRadiusThree: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderWidthThree}
								values={contentBorderWidthThree}
								id="content-border-width"
								option={{
									id: "content-border-width",
									label: __(
										"Content Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes
											.contentBorderWidthThree.default,
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
									setAttributes({
										contentBorderWidthThree: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderRadiusThree}
								values={contentBorderRadiusThree}
								id="content-border-radius"
								option={{
									id: "content-border-radius",
									label: __(
										"Content Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes
											.contentBorderRadiusThree.default,
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
									setAttributes({
										contentBorderRadiusThree: newValue,
									})
								}
							/>
						</div>
					</>
				)}
				{layoutStyle === "layout-type-4" && (
					<>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderWidthFour}
								values={titleBorderWidthFour}
								id="title-border-width"
								option={{
									id: "title-border-width",
									label: __(
										"Title Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderWidthFour
											.default,
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
									setAttributes({
										titleBorderWidthFour: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleBorderRadiusFour}
								values={titleBorderRadiusFour}
								id="title-border-radius"
								option={{
									id: "title-border-radius",
									label: __(
										"Title Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleBorderRadiusFour
											.default,
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
									setAttributes({
										titleBorderRadiusFour: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderWidthFour}
								values={contentBorderWidthFour}
								id="content-border-width"
								option={{
									id: "content-border-width",
									label: __(
										"Content Border Width",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes.contentBorderWidthFour
											.default,
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
									setAttributes({
										contentBorderWidthFour: newValue,
									})
								}
							/>
						</div>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={contentBorderRadiusFour}
								values={contentBorderRadiusFour}
								id="content-border-radius"
								option={{
									id: "content-border-radius",
									label: __(
										"Content Border Radius",
										"affiliatex"
									),
									type: "ab-spacing",
									value:
										DefaultAttributes
											.contentBorderRadiusFour.default,
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
									setAttributes({
										contentBorderRadiusFour: newValue,
									})
								}
							/>
						</div>
					</>
				)}
			</PanelBody>
			<PanelBody
				title={__("Colors", "affiliatex")}
				className={"affx-panel-label"}
				initialOpen={false}
			>
				{layoutStyle === "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								prosTextColor: { color: prosTextColorThree },
								consTextColor: { color: consTextColorThree },
							}}
							option={{
								id: "prosandcons-text-color",
								label: __("Title Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									prosTextColor: {
										color:
											DefaultAttributes.prosTextColorThree
												.default,
									},
									consTextColor: {
										color:
											DefaultAttributes.consTextColorThree
												.default,
									},
								},
								pickers: [
									{
										id: "prosTextColor",
										title: __(
											"Pros Title Color",
											"affiliatex"
										),
									},
									{
										id: "consTextColor",
										title: __(
											"Cons Title Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									prosTextColorThree:
										colorValue.prosTextColor.color,
									consTextColorThree:
										colorValue.consTextColor.color,
								})
							}
						/>
					</div>
				)}
				{layoutStyle !== "layout-type-3" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								prosTextColor: { color: prosTextColor },
								consTextColor: { color: consTextColor },
							}}
							option={{
								id: "prosandcons-title-color",
								label: __("Title Color", "affiliatex"),
								type: "ab-color-picker",
								value: {
									prosTextColor: {
										color:
											DefaultAttributes.prosTextColor
												.default,
									},
									consTextColor: {
										color:
											DefaultAttributes.consTextColor
												.default,
									},
								},
								pickers: [
									{
										id: "prosTextColor",
										title: __(
											"Pros Title Color",
											"affiliatex"
										),
									},
									{
										id: "consTextColor",
										title: __(
											"Cons Title Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									prosTextColor:
										colorValue.prosTextColor.color,
									consTextColor:
										colorValue.consTextColor.color,
								})
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							prosTextColor: { color: prosListColor },
							consTextColor: { color: consListColor },
						}}
						option={{
							id: "prosandcons-content-color",
							label: __("Content Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								prosTextColor: {
									color:
										DefaultAttributes.prosListColor.default,
								},
								consTextColor: {
									color:
										DefaultAttributes.consListColor.default,
								},
							},
							pickers: [
								{
									id: "prosTextColor",
									title: __(
										"Pros Content Color",
										"affiliatex"
									),
								},
								{
									id: "consTextColor",
									title: __(
										"Cons Content Color",
										"affiliatex"
									),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								prosListColor: colorValue.prosTextColor.color,
								consListColor: colorValue.consTextColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							prosTextColor: { color: prosIconColor },
							consTextColor: { color: consIconColor },
						}}
						option={{
							id: "prosandcons-content-color",
							label: __("Checkmark Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								prosTextColor: {
									color:
										DefaultAttributes.prosIconColor.default,
								},
								consTextColor: {
									color:
										DefaultAttributes.consIconColor.default,
								},
							},
							pickers: [
								{
									id: "prosTextColor",
									title: __(
										"Pros Checkmark Color",
										"affiliatex"
									),
								},
								{
									id: "consTextColor",
									title: __(
										"Cons Checkmark Color",
										"affiliatex"
									),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								prosIconColor: colorValue.prosTextColor.color,
								consIconColor: colorValue.consTextColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={prosBgType}
						values={prosBgType}
						id="pros-bg-type"
						option={{
							id: "pros-bg-type",
							label: __("Pros Background Type", "affiliatex"),
							type: "ab-radio",
							value: DefaultAttributes.prosBgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ prosBgType: newValue })
						}
					/>
				</div>
				{prosBgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: { color: prosBgColor },
							}}
							option={{
								id: "pc-pros-bg-color",
								label: __(
									"Pros Background Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes.prosBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "bgColor",
										title: __(
											"Pros Bg Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									prosBgColor: colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{prosBgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={prosBgGradient}
							onChange={(gradientValue) =>
								setAttributes({ prosBgGradient: gradientValue })
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={prosListBgType}
						values={prosListBgType}
						id="list-bg-type"
						option={{
							id: "list-bg-type",
							label: __(
								"Pros List Background Type",
								"affiliatex"
							),
							type: "ab-radio",
							value: DefaultAttributes.prosListBgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ prosListBgType: newValue })
						}
					/>
				</div>
				{prosListBgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: { color: prosListBgColor },
							}}
							option={{
								id: "pc-pros-list-bg-color",
								label: __(
									"Pros List Background Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes.prosListBgColor
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
									prosListBgColor: colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{prosListBgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={prosListBgGradient}
							onChange={(gradientValue) =>
								setAttributes({
									prosListBgGradient: gradientValue,
								})
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={consBgType}
						values={consBgType}
						id="cons-bg-type"
						option={{
							id: "cons-bg-type",
							label: __("Cons Background Type", "affiliatex"),
							type: "ab-radio",
							value: DefaultAttributes.consBgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ consBgType: newValue })
						}
					/>
				</div>
				{consBgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: { color: consBgColor },
							}}
							option={{
								id: "pc-cons-bg-color",
								label: __(
									"Cons Background Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes.consBgColor
												.default,
									},
								},
								pickers: [
									{
										id: "bgColor",
										title: __(
											"Cons Bg Color",
											"affiliatex"
										),
									},
								],
							}}
							hasRevertButton={true}
							onChange={(colorValue) =>
								setAttributes({
									consBgColor: colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{consBgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={consBgGradient}
							onChange={(gradientValue) =>
								setAttributes({ consBgGradient: gradientValue })
							}
						/>
					</div>
				)}
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={consListBgType}
						values={consListBgType}
						id="list-bg-type"
						option={{
							id: "list-bg-type",
							label: __(
								"Cons List Background Type",
								"affiliatex"
							),
							type: "ab-radio",
							value: DefaultAttributes.consListBgType.default,
							choices: {
								solid: __("Solid Color", "affiliatex"),
								gradient: __("Gradient", "affiliatex"),
							},
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ consListBgType: newValue })
						}
					/>
				</div>
				{consListBgType === "solid" && (
					<div className="affiliate-blocks-option affiliate-color-option">
						<GenericOptionType
							value={{
								bgColor: { color: consListBgColor },
							}}
							option={{
								id: "pc-con-list-bg-color",
								label: __(
									"Cons List Background Color",
									"affiliatex"
								),
								type: "ab-color-picker",
								value: {
									bgColor: {
										color:
											DefaultAttributes.consListBgColor
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
									consListBgColor: colorValue.bgColor.color,
								})
							}
						/>
					</div>
				)}
				{consListBgType === "gradient" && (
					<div className="affiliate-blocks-option affiliate-gradient-option">
						<GradientPicker
							value={consListBgGradient}
							onChange={(gradientValue) =>
								setAttributes({
									consListBgGradient: gradientValue,
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
						value={titleTypography}
						id="title-typography"
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
						id="list-typography"
						option={{
							id: "list-typography",
							label: __("List/Content Typography", "affiliatex"),
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
			<PanelBody
				title={__("Spacing Settings", "affiliatex")}
				initialOpen={false}
			>
				{layoutStyle !== "layout-type-3" && (
					<>
						<div className="affiliate-blocks-option">
							<GenericOptionType
								value={titleMargin}
								values={titleMargin}
								id="title-margin"
								option={{
									id: "title-margin",
									label: __("Title Margin", "affiliatex"),
									type: "ab-spacing",
									value:
										DefaultAttributes.titleMargin.default,
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
									setAttributes({ titleMargin: newValue })
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
									value:
										DefaultAttributes.titlePadding.default,
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
					</>
				)}

				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={contentMargin}
						values={contentMargin}
						id="content-margin"
						option={{
							id: "content-margin",
							label: __("Content Margin", "affiliatex"),
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
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={margin}
						values={margin}
						id="margin"
						option={{
							id: "margin",
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
						id="padding"
						option={{
							id: "padding",
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
