import { __ } from "@wordpress/i18n";
import classnames from "classnames";
export default ({ attributes, className }) => {
	const {
		buttonLabel,
		buttonSize,
		buttonWidth,
		buttonURL,
		iconPosition,
		block_id,
		ButtonIcon,
		edButtonIcon,
		btnRelSponsored,
		openInNewTab,
		btnRelNoFollow,
		buttonAlignment,
		btnDownload,
		layoutStyle,
		priceTagPosition,
		productPrice,
	} = attributes;
	const { RichText } = wp.blockEditor;

	return (
		<div
			id={`affiliatex-blocks-style-${block_id}`}
			className={`affx-btn-wrapper`}
		>
			<div className={`affx-btn-inner`}>
				<a
					href={buttonURL}
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
					rel={__("noopener", "affiliatex")}
					{...(btnRelNoFollow ? { rel: "noopener nofollow" } : "")}
					{...(btnRelSponsored ? { rel: "noopener sponsored" } : "")}
					{...(btnRelNoFollow && btnRelSponsored
						? { rel: "noopener nofollow sponsored" }
						: "")}
					{...(openInNewTab ? { target: "_blank" } : "")}
					{...(btnDownload ? { download: "affiliatex" } : "")}
				>
					{edButtonIcon == true &&
						iconPosition &&
						iconPosition == "axBtnleft" && (
							<i class={`button-icon ` + ButtonIcon.value}></i>
						)}
					<span className="affiliatex-btn">
						<RichText.Content
							placeholder={__("Button", "affiliatex")}
							value={buttonLabel}
						/>
					</span>
					{layoutStyle == "layout-type-2" && priceTagPosition && (
						<RichText.Content
							tagName="span"
							placeholder="$00"
							value={productPrice}
							className="price-tag"
						/>
					)}
					{edButtonIcon == true &&
						iconPosition &&
						iconPosition == "axBtnright" && (
							<i class={`button-icon ` + ButtonIcon.value}></i>
						)}
				</a>
			</div>
		</div>
	);
};
