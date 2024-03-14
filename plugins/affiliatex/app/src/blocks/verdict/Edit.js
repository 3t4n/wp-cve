import BlockInspector from "./Inspector";
import { __ } from "@wordpress/i18n";
import { useEffect } from "@wordpress/element";
import blocks_styling from "./styling";
const { RichText, InnerBlocks } = wp.blockEditor;
const { TextControl } = wp.components;
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
		$style.setAttribute("id", "affiliatex-verdict-style-" + clientId);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-verdict-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-verdict-style",
				clientId
			);
		}
	}, [attributes]);

	const ProsAndCons = [
		[
			"affiliatex/pros-and-cons",
			{
				prosListBgColor: "#fff",
				consListBgColor: "#fff",
				alignment: "left",
				margin: {
					desktop: {
						top: "0",
						left: "0",
						right: "0",
						bottom: "0",
					},
					mobile: {
						top: "0",
						left: "0",
						right: "0",
						bottom: "0",
					},
					tablet: {
						top: "0",
						left: "0",
						right: "0",
						bottom: "0",
					},
				},
			},
		],
	];

	const MY_TEMPLATE = [["affiliatex/buttons", { buttonAlignment: "center" }]];

	const {
		verdictLayout,
		verdictTitle,
		verdictContent,
		edverdictTotalScore,
		verdictTotalScore,
		ratingContent,
		edRatingsArrow,
		edProsCons,
		verdictTitleTag,
		ratingAlignment,
		verdictTitleTypography,
		verdictContentTypography,
	} = attributes;

	const TagTitle = verdictTitleTag;

	const layoutClass =
		verdictLayout === "layoutOne"
			? " verdict-layout-1"
			: verdictLayout === "layoutTwo"
			? " verdict-layout-2"
			: "";

	const ratingClass = edverdictTotalScore ? " number-rating" : "";
	const arrowClass = edRatingsArrow ? " display-arrow" : "";

	const { Fragment } = wp.element;

	let verdictBlockTitleTypography;
	let verdictBlockContentTypography;

	if ("Default" !== verdictTitleTypography.family) {
		const verdictBlockTitleTypoConfig = {
			google: {
				families: [
					verdictTitleTypography.family +
						(verdictTitleTypography.variation
							? ":" + verdictTitleTypography.variation
							: ""),
				],
			},
		};

		verdictBlockTitleTypography = (
			<WebfontLoader config={verdictBlockTitleTypoConfig}></WebfontLoader>
		);
	}

	if ("Default" !== verdictContentTypography.family) {
		const verdictContentTypoConfig = {
			google: {
				families: [
					verdictContentTypography.family +
						(verdictContentTypography.variation
							? ":" + verdictContentTypography.variation
							: ""),
				],
			},
		};

		verdictBlockContentTypography = (
			<WebfontLoader config={verdictContentTypoConfig}></WebfontLoader>
		);
	}

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-verdict-style-${clientId}`}
				className={className}
			>
				<div className={`affblk-verdict-wrapper`}>
					<div className={layoutClass + arrowClass}>
						<div className="main-text-holder">
							<div className="content-wrapper">
								<TagTitle className="verdict-title">
									<RichText
										placeholder={__(
											"Enter Verdict Title",
											"affiliatex"
										)}
										value={verdictTitle}
										onChange={(verdictTitle) =>
											setAttributes({ verdictTitle })
										}
									/>
								</TagTitle>

								<RichText
									tagName="p"
									placeholder={__(
										"Enter Verdict Content",
										"affiliatex"
									)}
									value={verdictContent}
									className="verdict-content"
									onChange={(verdictContent) =>
										setAttributes({ verdictContent })
									}
								/>
							</div>

							{verdictLayout === "layoutOne" && (
								<>
									{edverdictTotalScore == true && (
										<div
											className={`affx-verdict-rating-number${ratingClass} ${
												ratingAlignment == "right"
													? "align-right"
													: "align-left"
											}`}
										>
											<TextControl
												className="affx-rating-input-number num"
												value={verdictTotalScore}
											/>
											<RichText
												value={ratingContent}
												className="affx-rating-input-content"
												onChange={(ratingContent) =>
													setAttributes({
														ratingContent,
													})
												}
											/>
										</div>
									)}
								</>
							)}
						</div>
						{verdictLayout === "layoutOne" && (
							<>
								{edProsCons && (
									<InnerBlocks
										template={ProsAndCons}
										templateLock="all"
									/>
								)}
							</>
						)}

						{verdictLayout === "layoutTwo" && (
							<InnerBlocks
								template={MY_TEMPLATE}
								templateLock="all"
							/>
						)}
					</div>
				</div>
			</div>
			{verdictBlockTitleTypography}
			{verdictBlockContentTypography}
		</Fragment>
	);
};
