import BlockInspector from "./Inspector";
import { __ } from "@wordpress/i18n";
import { useEffect } from "@wordpress/element";
import blocks_styling from "./styling";
const { RichText } = wp.blockEditor;
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
		$style.setAttribute("id", "affiliatex-notice-style-" + clientId);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-notice-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-notice-style",
				clientId
			);
		}
	}, [attributes]);

	const { Fragment } = wp.element;

	const {
		titleTypography,
		listTypography,
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

	let noticeBlockTitleTypography;
	let noticeBlockContentTypography;

	if ("Default" !== titleTypography.family) {
		const noticeBlockTitleTypoConfig = {
			google: {
				families: [
					titleTypography.family +
						(titleTypography.variation
							? ":" + titleTypography.variation
							: ""),
				],
			},
		};

		noticeBlockTitleTypography = (
			<WebfontLoader config={noticeBlockTitleTypoConfig}></WebfontLoader>
		);
	}

	if ("Default" !== listTypography.family) {
		const noticeContentTypoConfig = {
			google: {
				families: [
					listTypography.family +
						(listTypography.variation
							? ":" + listTypography.variation
							: ""),
				],
			},
		};

		noticeBlockContentTypography = (
			<WebfontLoader config={noticeContentTypoConfig}></WebfontLoader>
		);
	}

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-notice-style-${clientId}`}
				className={`affx-notice-wrapper${
					className ? ` ${className}` : ""
				}`}
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
							<RichText
								value={noticeTitle}
								className="affiliatex-title"
								onChange={(noticeTitle) =>
									setAttributes({ noticeTitle })
								}
							/>
						</Tag1>
						<div className={"affiliatex-notice-content"}>
							<div className="list-wrapper">
								{noticeContentType === "list" &&
									noticeunorderedType === "bullet" && (
										<RichText
											tagName={
												noticeListType == "unordered"
													? "ul"
													: "ol"
											}
											multiline="li"
											className={`affiliatex-list affiliatex-list-type-unordered bullet`}
											placeholder={__(
												"Enter new item",
												"affiliatex"
											)}
											value={noticeListItems}
											onChange={(noticeListItems) =>
												setAttributes({
													noticeListItems,
												})
											}
											keepPlaceholderOnFocus
										/>
									)}
								{noticeContentType === "list" &&
									noticeunorderedType === "icon" && (
										<RichText
											tagName={
												noticeListType == "unordered"
													? "ul"
													: "ol"
											}
											multiline="li"
											className={`affiliatex-list affiliatex-list-type-unordered affiliatex-icon afx-icon-before affiliatex-icon-${noticeListIcon.name}`}
											placeholder={__(
												"Enter new item",
												"affiliatex"
											)}
											value={noticeListItems}
											onChange={(noticeListItems) =>
												setAttributes({
													noticeListItems,
												})
											}
											keepPlaceholderOnFocus
										/>
									)}
								{noticeContentType === "paragraph" && (
									<RichText
										tagName="p"
										value={noticeContent}
										placeholder={__(
											"Notice Content",
											"affiliatex"
										)}
										className="affiliatex-content"
										onChange={(noticeContent) =>
											setAttributes({ noticeContent })
										}
									/>
								)}
							</div>
						</div>
					</div>
				</div>
			</div>
			{noticeBlockTitleTypography}
			{noticeBlockContentTypography}
		</Fragment>
	);
};
