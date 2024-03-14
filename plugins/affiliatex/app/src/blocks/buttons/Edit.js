import BlockInspector from "./Inspector";
import { __ } from "@wordpress/i18n";
import { useEffect } from "@wordpress/element";
import blocks_styling from "./styling";
import classnames from "classnames";
import WebfontLoader from "../ui-components/typography/fontloader";

const { RichText } = wp.blockEditor;

export default ({
	attributes,
	setAttributes,
	className,
	isSelected,
	clientId,
}) => {
	useEffect(() => {
		const $style = document.createElement("style");
		$style.setAttribute("id", "affiliatex-blocks-style-" + clientId);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-blocks-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-blocks-style",
				clientId
			);
		}
	}, [attributes]);

	const {
		buttonTypography,
		buttonLabel,
		buttonURL,
		edButtonIcon,
		buttonSize,
		buttonWidth,
		buttonAlignment,
		ButtonIcon,
		iconPosition,
		layoutStyle,
		priceTagPosition,
		productPrice,
	} = attributes;
	const { Fragment } = wp.element;

	let loadButtonGoogleFont;

	if ("Default" !== buttonTypography.family) {
		const buttonFontConfig = {
			google: {
				families: [
					buttonTypography.family +
						(buttonTypography.variation
							? ":" + buttonTypography.variation
							: ""),
				],
			},
		};

		loadButtonGoogleFont = (
			<WebfontLoader config={buttonFontConfig}></WebfontLoader>
		);
	}

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-blocks-style-${clientId}`}
				className={`affx-btn-wrapper`}
			>
				<div className={`affx-btn-inner`}>
					<Fragment>
						<span
							className={classnames(
								`affiliatex-button btn-align-${buttonAlignment} btn-is-${buttonSize} ${
									buttonWidth == "fixed"
										? "btn-is-fixed"
										: buttonWidth === "full"
										? "btn-is-fullw"
										: buttonWidth === "flexible"
										? `btn-is-flex-${buttonSize}`
										: ""
								}
								${
									layoutStyle == "layout-type-2" &&
									priceTagPosition == "tagBtnleft"
										? "left-price-tag"
										: layoutStyle == "layout-type-2" &&
										  priceTagPosition == "tagBtnright"
										? "right-price-tag"
										: ""
								}
								${
									edButtonIcon == true &&
									iconPosition &&
									iconPosition == "axBtnright"
										? "icon-right"
										: "icon-left"
								}`
							)}
						>
							{edButtonIcon == true &&
								iconPosition &&
								iconPosition == "axBtnleft" && (
									<i
										className={
											`button-icon ` + ButtonIcon.value
										}
									></i>
								)}
							<RichText
								placeholder={__("Button", "affiliatex")}
								value={buttonLabel}
								className="affiliatex-btn"
								formattingControls={[
									"bold",
									"italic",
									"underline",
								]}
								href={buttonURL}
								onChange={(newLabel) =>
									setAttributes({ buttonLabel: newLabel })
								}
							/>
							{layoutStyle == "layout-type-2" &&
								priceTagPosition && (
									<RichText
										tagName="span"
										placeholder="$00"
										value={productPrice}
										className="price-tag"
										onChange={(newPrice) =>
											setAttributes({
												productPrice: newPrice,
											})
										}
									/>
								)}
							{edButtonIcon == true &&
								iconPosition &&
								iconPosition == "axBtnright" && (
									<i
										class={
											`button-icon ` + ButtonIcon.value
										}
									></i>
								)}
						</span>
					</Fragment>
				</div>
			</div>
			{loadButtonGoogleFont}
		</Fragment>
	);
};
