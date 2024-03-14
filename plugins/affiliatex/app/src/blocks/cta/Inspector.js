import { __ } from "@wordpress/i18n";
import { Button, RangeControl, SelectControl } from "@wordpress/components";
import Typography from "../ui-components/typographyOptions";
import ColorPicker from "../ui-components/colorPicker";
import GenericOptionType from "../ui-components/GenericOptionType";
import DefaultAttributes from "./attributes";

export default ({ attributes, setAttributes, className, isSelected }) => {
	const { InspectorControls, MediaUpload } = wp.blockEditor;
	const { PanelBody, ToggleControl } = wp.components;
	const {
		ctaLayout,
		ctaBGType,
		imgURL,
		imgID,
		imgAlt,
		ctaBGColor,
		overlayOpacity,
		imagePosition,
		ctaAlignment,
		contentAlignment,
		ctaTitleColor,
		ctaTextColor,
		ctaTitleTypography,
		ctaContentTypography,
		edButtons,
		edButtonTwo,
		ctaBorder,
		ctaBorderWidth,
		ctaBorderRadius,
		ctaBoxPadding,
		ctaMargin,
		ctaBoxShadow,
		columnReverse,
		ctaButtonAlignment,
	} = attributes;

	const onSelectImage = (img) => {
		setAttributes({
			imgID: img.id,
			imgURL: img.url,
			imgAlt: img.alt,
		});
	};
	const onRemoveImage = () => {
		setAttributes({
			imgID: null,
			imgURL: null,
			imgAlt: null,
		});
	};

	return (
		<InspectorControls key="inspector">
			<PanelBody
				title={__("Layout Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliatex-layout-option">
					<SelectControl
						value={ctaLayout}
						options={[
							{ value: "layoutOne", label: "Layout One" },
							{ value: "layoutTwo", label: "Layout Two" },
						]}
						onChange={(value) =>
							setAttributes({ ctaLayout: value })
						}
					/>
				</div>

				{ctaLayout === "layoutOne" && (
					<div className="affiliate-blocks-option">
						<GenericOptionType
							value={ctaAlignment}
							values={ctaAlignment}
							id="cta-alignment"
							option={{
								id: "cta-alignment",
								label: __("Alignment", "affiliatex"),
								attr: { "data-type": "alignment" },
								type: "ab-radio",
								choices: { left: "", center: "", right: "" },
								value: DefaultAttributes.ctaAlignment.default,
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ ctaAlignment: newValue })
							}
						/>
					</div>
				)}
			</PanelBody>
			{ctaLayout === "layoutTwo" && (
				<>
					<PanelBody
						title={__("CTA Image", "affiliatex")}
						initialOpen={false}
					>
						<div className="affiliate-blocks-option">
							{!imgID ? (
								<MediaUpload
									onSelect={onSelectImage}
									type="image"
									value={imgID}
									render={({ open }) => (
										<Button
											className={"upload-btn"}
											onClick={open}
										>
											{__(" Upload Image", "affiliatex")}
										</Button>
									)}
								></MediaUpload>
							) : (
								<div className="image-wrapper">
									<img src={imgURL} alt={imgAlt} />

									{isSelected ? (
										<Button
											className="remove-image"
											onClick={onRemoveImage}
										>
											{__(" Remove Image", "affiliatex")}
										</Button>
									) : null}
								</div>
							)}
						</div>
						<div className="affiliate-blocks-option affiliatex-bg-postion-option">
							<SelectControl
								label={__("Image Position", "affiliatex")}
								value={imagePosition}
								options={[
									{ value: "center", label: "Center Center" },
									{
										value: "centerLeft",
										label: "Center Left",
									},
									{
										value: "centerRight",
										label: "Center Right",
									},
									{ value: "topCenter", label: "Top Center" },
									{ value: "topLeft", label: "Top Left" },
									{ value: "topRight", label: "Top Right" },
									{
										value: "bottomCenter",
										label: "Bottom Center",
									},
									{
										value: "bottomLeft",
										label: "Bottom Left",
									},
									{
										value: "bottomRight",
										label: "Bottom Right",
									},
								]}
								onChange={(value) =>
									setAttributes({ imagePosition: value })
								}
							/>
						</div>
						{ctaLayout === "layoutTwo" && (
							<div className="affiliate-blocks-option affiliatex-column-reverse">
								<ToggleControl
									label={__(
										"Enable Column Reverse",
										"affiliatex"
									)}
									checked={!!columnReverse}
									onChange={() =>
										setAttributes({
											columnReverse: !columnReverse,
										})
									}
								/>
							</div>
						)}
					</PanelBody>
				</>
			)}

			<PanelBody
				title={__("General Settings", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliatex-button-option">
					<ToggleControl
						label={__("Enable Buttons", "affiliatex")}
						checked={!!edButtons}
						onChange={() =>
							setAttributes({ edButtons: !edButtons })
						}
					/>

					{edButtons === true && (
						<ToggleControl
							label={__("Enable Button Two", "affiliatex")}
							checked={!!edButtonTwo}
							onChange={() =>
								setAttributes({ edButtonTwo: !edButtonTwo })
							}
						/>
					)}
				</div>
				<div className="affiliate-blocks-option affiliate-border-option">
					<GenericOptionType
						value={ctaBorder}
						values={ctaBorder}
						id="cta-border"
						option={{
							id: "cta-border",
							label: __("Border", "affiliatex"),
							type: "ab-border",
							value: DefaultAttributes.ctaBorder.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ ctaBorder: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={ctaBorderWidth}
						values={ctaBorderWidth}
						id="cta-border-width"
						option={{
							id: "cta-border-width",
							label: __("Border Width", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.ctaBorderWidth.default,
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
							setAttributes({ ctaBorderWidth: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={ctaBorderRadius}
						values={ctaBorderRadius}
						id="cta-border-radius"
						option={{
							id: "cta-border-radius",
							label: __("Border Radius", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.ctaBorderRadius.default,
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
							setAttributes({ ctaBorderRadius: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-shadow-option">
					<GenericOptionType
						value={ctaBoxShadow}
						values={ctaBoxShadow}
						id="cta-box-shadow"
						option={{
							id: "cta-box-shadow",
							label: __("Box Shadow", "affiliatex"),
							type: "ab-box-shadow",
							divider: "top",
							value: DefaultAttributes.ctaBoxShadow.default,
						}}
						hasRevertButton={true}
						onChange={(newBtnShadow) =>
							setAttributes({ ctaBoxShadow: newBtnShadow })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={contentAlignment}
						values={contentAlignment}
						id="content-alignment"
						option={{
							id: "content-alignment",
							label: __("Content Alignment", "affiliatex"),
							attr: { "data-type": "alignment" },
							type: "ab-radio",
							choices: { left: "", center: "", right: "" },
							value: DefaultAttributes.contentAlignment.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ contentAlignment: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option">
					<GenericOptionType
						value={ctaButtonAlignment}
						values={ctaButtonAlignment}
						id="button-alignment"
						option={{
							id: "button-alignment",
							label: __("Button Alignment", "affiliatex"),
							attr: { "data-type": "alignment" },
							type: "ab-radio",
							choices: { left: "", center: "", right: "" },
							value: DefaultAttributes.ctaButtonAlignment.default,
						}}
						hasRevertButton={true}
						onChange={(newValue) =>
							setAttributes({ ctaButtonAlignment: newValue })
						}
					/>
				</div>
			</PanelBody>
			<PanelBody title={__("Colors", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							titleColor: { color: ctaTitleColor },
						}}
						option={{
							id: "cta-title-color",
							label: __("Title Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								titleColor: {
									color:
										DefaultAttributes.ctaTitleColor.default,
								},
							},
							pickers: [
								{
									id: "titleColor",
									title: __("Title Color"),
								},
							],
						}}
						hasRevertButton={true}
						onChange={(colorValue) =>
							setAttributes({
								ctaTitleColor: colorValue.titleColor.color,
							})
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliate-color-option">
					<GenericOptionType
						value={{
							textColor: { color: ctaTextColor },
						}}
						onChange={(colorValue) =>
							setAttributes({
								ctaTextColor: colorValue.textColor.color,
							})
						}
						option={{
							id: "cta-text-color",
							label: __("Text Color", "affiliatex"),
							type: "ab-color-picker",
							value: {
								textColor: {
									color:
										DefaultAttributes.ctaTextColor.default,
								},
							},
							pickers: [
								{
									id: "textColor",
									title: __("Content Color"),
								},
							],
						}}
						hasRevertButton={true}
					/>
				</div>
				{ctaLayout === "layoutOne" && (
					<div className="affiliate-blocks-option affiliatex-bg-option">
						<GenericOptionType
							value={ctaBGType}
							values={ctaBGType}
							id="cta-bg-type"
							option={{
								id: "cta-bg-type",
								label: __("Background Type", "affiliatex"),
								type: "ab-radio",
								value: DefaultAttributes.ctaBGType.default,
								choices: {
									color: __("Color", "affiliatex"),
									image: __("Image", "affiliatex"),
								},
							}}
							hasRevertButton={true}
							onChange={(newValue) =>
								setAttributes({ ctaBGType: newValue })
							}
						/>
					</div>
				)}

				{((ctaLayout === "layoutOne" && ctaBGType === "color") ||
					ctaLayout === "layoutTwo") && (
					<div className="affiliate-blocks-option affiliate-color-bg-option">
						<div className="affiliate-blocks-option affiliate-color-option">
							<GenericOptionType
								value={{
									backgroundColor: { color: ctaBGColor },
								}}
								onChange={(colorValue) =>
									setAttributes({
										ctaBGColor:
											colorValue.backgroundColor.color,
									})
								}
								option={{
									id: "cta-background-color",
									label: __("Background Color", "affiliatex"),
									type: "ab-color-picker",
									value: {
										backgroundColor: {
											color:
												DefaultAttributes.ctaBGColor
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
							/>
						</div>
					</div>
				)}

				{ctaLayout === "layoutOne" && ctaBGType === "image" && (
					<>
						<div className={className}>
							{!imgID ? (
								<MediaUpload
									onSelect={onSelectImage}
									type="image"
									value={imgID}
									render={({ open }) => (
										<Button
											className={"upload-btn"}
											onClick={open}
										>
											{__(" Upload Image", "affiliatex")}
										</Button>
									)}
								></MediaUpload>
							) : (
								<div className="image-wrapper">
									<img src={imgURL} alt={imgAlt} />

									{isSelected ? (
										<Button
											className="remove-image"
											onClick={onRemoveImage}
										>
											{__(" Remove Image", "affiliatex")}
										</Button>
									) : null}
								</div>
							)}
						</div>

						{ctaLayout === "layoutOne" && ctaBGType === "image" && (
							<>
								<div className="affiliate-blocks-option affiliatex-bg-postion-option">
									<SelectControl
										label={__(
											"Image Position",
											"affiliatex"
										)}
										value={imagePosition}
										options={[
											{
												value: "center",
												label: "Center Center",
											},
											{
												value: "centerLeft",
												label: "Center Left",
											},
											{
												value: "centerRight",
												label: "Center Right",
											},
											{
												value: "topCenter",
												label: "Top Center",
											},
											{
												value: "topLeft",
												label: "Top Left",
											},
											{
												value: "topRight",
												label: "Top Right",
											},
											{
												value: "bottomCenter",
												label: "Bottom Center",
											},
											{
												value: "bottomLeft",
												label: "Bottom Left",
											},
											{
												value: "bottomRight",
												label: "Bottom Right",
											},
										]}
										onChange={(value) =>
											setAttributes({
												imagePosition: value,
											})
										}
									/>
								</div>

								<div className="affiliate-blocks-option affiliatex-opacity">
									<label>
										{__("Overlay Opacity", "affiliatex")}
									</label>
									<RangeControl
										value={overlayOpacity}
										min={0}
										max={1.0}
										step={0.1}
										onChange={(newValue) =>
											setAttributes({
												overlayOpacity: newValue,
											})
										}
									/>
								</div>
							</>
						)}
					</>
				)}
			</PanelBody>
			<PanelBody
				title={__("Typography", "affiliatex")}
				initialOpen={false}
			>
				<div className="affiliate-blocks-option affiliatex-title-typography">
					<GenericOptionType
						id="cta-title-typography"
						value={ctaTitleTypography}
						option={{
							id: "cta-title-typography",
							label: __("Title Typography", "affiliatex"),
							type: "ab-typography",
							value: DefaultAttributes.ctaTitleTypography.default,
						}}
						device="desktop"
						hasRevertButton={true}
						onChange={(newTypographyObject) => {
							setAttributes({
								ctaTitleTypography: newTypographyObject,
							});
						}}
					/>
				</div>
				<div className="affiliate-blocks-option affiliatex-content-typography">
					<GenericOptionType
						id="cta-content-typography"
						value={ctaContentTypography}
						option={{
							id: "cta-content-typography",
							label: __("Content Typography", "affiliatex"),
							type: "ab-typography",
							value:
								DefaultAttributes.ctaContentTypography.default,
						}}
						device="desktop"
						hasRevertButton={true}
						onChange={(newTypographyObject) => {
							setAttributes({
								ctaContentTypography: newTypographyObject,
							});
						}}
					/>
				</div>
			</PanelBody>

			<PanelBody title={__("Spacing", "affiliatex")} initialOpen={false}>
				<div className="affiliate-blocks-option affiliatex-padding-option">
					<GenericOptionType
						value={ctaBoxPadding}
						values={ctaBoxPadding}
						id="cta-box-padding"
						option={{
							id: "cta-box-padding",
							label: __("Padding", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.ctaBoxPadding.default,
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
							setAttributes({ ctaBoxPadding: newValue })
						}
					/>
				</div>
				<div className="affiliate-blocks-option affiliatex-margin-option">
					<GenericOptionType
						value={ctaMargin}
						values={ctaMargin}
						id="cta-margin"
						option={{
							id: "cta-margin",
							label: __("Margin", "affiliatex"),
							type: "ab-spacing",
							value: DefaultAttributes.ctaMargin.default,
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
							setAttributes({ ctaMargin: newValue })
						}
					/>
				</div>
			</PanelBody>
		</InspectorControls>
	);
};
