import { __ } from "@wordpress/i18n";

export default ({ attributes, className, clientId }) => {
	const { RichText, InnerBlocks } = wp.blockEditor;

	const {
		block_id,
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

	return (
		<div id={`affiliatex-verdict-style-${block_id}`} className={className}>
			<div className="affblk-verdict-wrapper">
				<div className={layoutClass + arrowClass}>
					<div className="main-text-holder">
						<div className="content-wrapper">
							<TagTitle className={`verdict-title`}>
								<RichText.Content value={verdictTitle} />
							</TagTitle>
							<RichText.Content
								tagName="p"
								className="verdict-content"
								value={verdictContent}
							/>
						</div>
						{verdictLayout === "layoutOne" &&
							edverdictTotalScore == true && (
								<div
									className={`affx-verdict-rating-number${ratingClass} ${
										ratingAlignment == "right"
											? "align-right"
											: "align-left"
									}`}
								>
									<span className="num">
										{verdictTotalScore}
									</span>
									<div className="rich-content">
										<RichText.Content
											className="rating-content"
											value={ratingContent}
										/>
									</div>
								</div>
							)}
					</div>
					{verdictLayout === "layoutOne" && edProsCons && (
						<InnerBlocks.Content />
					)}
					{verdictLayout === "layoutTwo" && <InnerBlocks.Content />}
				</div>
			</div>
		</div>
	);
};
