import { __ } from "@wordpress/i18n";

export default ({ attributes, className, clientId }) => {
	const { InnerBlocks, RichText } = wp.blockEditor;
	const {
		block_id,
		ctaTitle,
		ctaContent,
		ctaBGType,
		ctaLayout,
		ctaAlignment,
		columnReverse,
		ctaButtonAlignment,
	} = attributes;

	const layoutClass =
		ctaLayout === "layoutOne"
			? " layout-type-1"
			: ctaLayout === "layoutTwo"
			? " layout-type-2"
			: "";
	const columnReverseClass =
		columnReverse && ctaLayout !== "layoutOne" ? " col-reverse" : "";
	return (
		<div
			id={`affiliatex-style-${block_id}`}
			className={`affblk-cta-wrapper ${className}`}
		>
			<div
				className={
					layoutClass +
					` ` +
					ctaAlignment +
					columnReverseClass +
					`${ctaBGType == "image" ? " img-opacity" : " bg-color"}`
				}
			>
				<div className="content-wrapper">
					<div className="content-wrap">
						<RichText.Content
							tagName="h2"
							className="affliatex-cta-title"
							value={ctaTitle}
						/>
						<RichText.Content
							tagName="p"
							className="affliatex-cta-content"
							value={ctaContent}
						/>
					</div>
					<div
						className={`button-wrapper cta-btn-${ctaButtonAlignment}`}
					>
						<InnerBlocks.Content />
					</div>
				</div>
				{ctaLayout === "layoutTwo" && (
					<div className="image-wrapper"></div>
				)}
			</div>
		</div>
	);
};
