import { __ } from "@wordpress/i18n";

export default ({ attributes, className }) => {
	const { RichText } = wp.blockEditor;

	const {
		block_id,
		titleTag1,
		layoutStyle,
		noticeTitle,
		noticeTitleIcon,
		noticeListItems,
		noticeListType,
		noticeContent,
		noticeContentType,
		noticeListIcon,
		noticeunorderedType,
		edTitleIcon,
	} = attributes;

	const titleIcon = edTitleIcon
		? `affiliatex-icon-${noticeTitleIcon.name}`
		: "";
	const Tag1 = titleTag1;

	return (
		<div
			id={`affiliatex-notice-style-${block_id}`}
			className={`affx-notice-wrapper${className ? ` ${className}` : ""}`}
		>
			<div className={`affx-notice-inner-wrapper ${layoutStyle}`}>
				{layoutStyle === "layout-type-3" && (
					<span
						className={`affiliatex-notice-icon affiliatex-icon afx-icon-before ${titleIcon}`}
					></span>
				)}
				<div className={"affx-notice-inner"}>
					<Tag1
						className={`affiliatex-notice-title affiliatex-icon afx-icon-before ${titleIcon}`}
					>
						<RichText.Content
							value={noticeTitle}
							className="affiliatex-title"
						/>
					</Tag1>
					<div className={"affiliatex-notice-content"}>
						<div className="list-wrapper">
							{noticeContentType === "list" &&
								noticeunorderedType === "bullet" && (
									<RichText.Content
										tagName={
											noticeListType == "unordered"
												? "ul"
												: "ol"
										}
										multiline="li"
										value={noticeListItems}
										className="affiliatex-list bullet"
									/>
								)}
							{noticeContentType === "list" &&
								noticeunorderedType === "icon" && (
									<RichText.Content
										tagName={
											noticeListType == "unordered"
												? "ul"
												: "ol"
										}
										multiline="li"
										value={noticeListItems}
										className={`affiliatex-list icon affiliatex-icon affiliatex-icon-${noticeListIcon.name}`}
									/>
								)}
							{noticeContentType == "paragraph" && (
								<RichText.Content
									tagName="p"
									value={noticeContent}
									placeholder={__(
										"Notice Content",
										"affiliatex"
									)}
									className="affiliatex-content"
								/>
							)}
						</div>
					</div>
				</div>
			</div>
		</div>
	);
};
