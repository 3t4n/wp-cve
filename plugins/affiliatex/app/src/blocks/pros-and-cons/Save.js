import { __ } from "@wordpress/i18n";

export default ({ attributes, className }) => {
	const { RichText } = wp.blockEditor;
	const {
		block_id,
		prosTitle,
		consTitle,
		prosIcon,
		consIcon,
		titleTag1,
		layoutStyle,
		prosListItems,
		consListItems,
		listType,
		prosContent,
		contentType,
		consContent,
		prosListIcon,
		consListIcon,
		unorderedType,
		prosIconStatus,
		consIconStatus,
	} = attributes;
	const Tag1 = titleTag1;

	return (
		<div
			id={`affiliatex-pros-cons-style-${block_id}`}
			className={`affx-pros-cons-wrapper${
				className ? ` ${className}` : ""
			}`}
		>
			<div className={`affx-pros-cons-inner-wrapper ${layoutStyle}`}>
				<div className={"affx-pros-inner"}>
					<div className="pros-icon-title-wrap">
						<div className={"affiliatex-block-pros"}>
							<RichText.Content
								tagName={Tag1}
								value={prosTitle}
								className={`affiliatex-title affiliatex-icon ${
									prosIconStatus
										? ` affiliatex-icon-${prosListIcon.name}`
										: ""
								}`}
							/>
						</div>
					</div>
					<div className={"affiliatex-pros"}>
						{contentType === "list" &&
							unorderedType === "bullet" && (
								<RichText.Content
									tagName={
										listType == "unordered" ? "ul" : "ol"
									}
									multiline="li"
									value={prosListItems}
									className="affiliatex-list bullet"
								/>
							)}
						{contentType === "list" && unorderedType === "icon" && (
							<RichText.Content
								tagName={listType == "unordered" ? "ul" : "ol"}
								multiline="li"
								value={prosListItems}
								className={`affiliatex-list icon affiliatex-icon affiliatex-icon-${prosIcon.name}`}
							/>
						)}
						{contentType === "paragraph" && (
							<RichText.Content
								tagName="p"
								value={prosContent}
								className="affiliatex-content"
							/>
						)}
					</div>
				</div>
				<div className={"affx-cons-inner"}>
					<div className="cons-icon-title-wrap">
						<div className={"affiliatex-block-cons"}>
							<RichText.Content
								tagName={Tag1}
								value={consTitle}
								className={`affiliatex-title affiliatex-icon ${
									consIconStatus
										? ` affiliatex-icon-${consListIcon.name}`
										: ""
								}`}
							/>
						</div>
					</div>
					<div className={"affiliatex-cons"}>
						{contentType === "list" &&
							unorderedType === "bullet" && (
								<RichText.Content
									tagName={
										listType == "unordered" ? "ul" : "ol"
									}
									multiline="li"
									value={consListItems}
									className="affiliatex-list bullet"
								/>
							)}
						{contentType === "list" && unorderedType === "icon" && (
							<RichText.Content
								tagName={listType == "unordered" ? "ul" : "ol"}
								multiline="li"
								value={consListItems}
								className={`affiliatex-list icon affiliatex-icon affiliatex-icon-${consIcon.name}`}
							/>
						)}
						{contentType === "paragraph" && (
							<RichText.Content
								tagName="p"
								value={consContent}
								className="affiliatex-content"
							/>
						)}
					</div>
				</div>
			</div>
		</div>
	);
};
