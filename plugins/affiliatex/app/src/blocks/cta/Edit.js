import BlockInspector from "./Inspector";
import { __ } from "@wordpress/i18n";
import { useEffect } from "@wordpress/element";
import blocks_styling from "./styling";
const { InnerBlocks, RichText } = wp.blockEditor;
import WebfontLoader from "../ui-components/typography/fontloader";

export default ({
	attributes,
	setAttributes,
	className,
	isSelected,
	clientId,
}) => {
	useEffect(() => {
		const $style = document.createElement("style");
		$style.setAttribute("id", "affiliatex-style-" + clientId);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById("affiliatex-style-" + clientId);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-style",
				clientId
			);
		}
	}, [attributes]);

	const {
		ctaTitleTypography,
		ctaContentTypography,
		ctaLayout,
		ctaTitle,
		ctaContent,
		ctaBGType,
		ctaAlignment,
		columnReverse,
		edButtons,
		edButtonTwo,
	} = attributes;

	const { Fragment } = wp.element;

	const MY_TEMPLATE = edButtonTwo
		? [
				[
					"affiliatex/buttons",
					{
						buttonLabel: "Buy Now",
						buttonMargin: {
							desktop: {
								top: "0px",
								left: "0px",
								right: "0px",
								bottom: "0px",
							},
							mobile: {
								top: "0px",
								left: "0px",
								right: "0px",
								bottom: "0px",
							},
							tablet: {
								top: "0px",
								left: "0px",
								right: "0px",
								bottom: "0px",
							},
						},
					},
				],
				[
					"affiliatex/buttons",
					{
						buttonLabel: "More Details",
						buttonBGColor: "#FFB800",
						buttonMargin: {
							desktop: {
								top: "0px",
								left: "0px",
								right: "0px",
								bottom: "0px",
							},
							mobile: {
								top: "0px",
								left: "0px",
								right: "0px",
								bottom: "0px",
							},
							tablet: {
								top: "0px",
								left: "0px",
								right: "0px",
								bottom: "0px",
							},
						},
					},
				],
		  ]
		: [
				[
					"affiliatex/buttons",
					{
						buttonLabel: "Buy Now",
					},
				],
		  ];

	const layoutClass =
		ctaLayout === "layoutOne"
			? " layout-type-1"
			: ctaLayout === "layoutTwo"
			? " layout-type-2"
			: "";

	const bgClass = ctaBGType == "image" ? " img-opacity" : " bg-color";

	const columnReverseClass =
		columnReverse && ctaLayout === "layoutTwo" ? " col-reverse" : "";

	let ctaBlockTitleTypography;
	let ctaBlockContentTypography;

	if ("Default" !== ctaTitleTypography.family) {
		const ctaBlockTitleTypoConfig = {
			google: {
				families: [
					ctaTitleTypography.family +
						(ctaTitleTypography.variation
							? ":" + ctaTitleTypography.variation
							: ""),
				],
			},
		};

		ctaBlockTitleTypography = (
			<WebfontLoader config={ctaBlockTitleTypoConfig}></WebfontLoader>
		);
	}

	if ("Default" !== ctaContentTypography.family) {
		const ctaContentTypoConfig = {
			google: {
				families: [
					ctaContentTypography.family +
						(ctaContentTypography.variation
							? ":" + ctaContentTypography.variation
							: ""),
				],
			},
		};

		ctaBlockContentTypography = (
			<WebfontLoader config={ctaContentTypoConfig}></WebfontLoader>
		);
	}

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-style-${clientId}`}
				className={`affblk-cta-wrapper ${className}`}
			>
				<div
					className={
						layoutClass +
						` ` +
						ctaAlignment +
						columnReverseClass +
						`${ctaLayout === "layoutOne" ? bgClass : ""}`
					}
				>
					<div className="content-wrapper">
						<div className="content-wrap">
							<RichText
								tagName="h2"
								placeholder={__(
									"Call to Action Title",
									"affiliatex"
								)}
								value={ctaTitle}
								className="affliatex-cta-title"
								onChange={(ctaTitle) =>
									setAttributes({ ctaTitle })
								}
							/>

							<RichText
								tagName="p"
								placeholder={__(
									"Start creating CTAs in seconds, and convert more of your visitors into leads.",
									"affiliatex"
								)}
								value={ctaContent}
								className="affliatex-cta-content"
								onChange={(ctaContent) =>
									setAttributes({ ctaContent })
								}
							/>
						</div>
						{edButtons === true && (
							<div className="button-wrapper">
								<InnerBlocks
									orientation="horizontal"
									template={MY_TEMPLATE}
									templateLock="all"
								/>
							</div>
						)}
					</div>

					{ctaLayout === "layoutTwo" && (
						<div className="image-wrapper"></div>
					)}
				</div>
			</div>
			{ctaBlockTitleTypography}
			{ctaBlockContentTypography}
		</Fragment>
	);
};
