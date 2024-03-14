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
		$style.setAttribute("id", "affiliatex-pros-cons-style-" + clientId);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-pros-cons-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-pros-cons-style",
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
		prosTitle,
		consTitle,
		prosIcon,
		consIcon,
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

	let prosAndConsTitleTypography;
	let prosAndConsListTypography;

	if ("Default" !== titleTypography.family) {
		const proConsTitleTypoConfig = {
			google: {
				families: [
					titleTypography.family +
						(titleTypography.variation
							? ":" + titleTypography.variation
							: ""),
				],
			},
		};

		prosAndConsTitleTypography = (
			<WebfontLoader config={proConsTitleTypoConfig}></WebfontLoader>
		);
	}

	if ("Default" !== listTypography.family) {
		const proConsListTypoConfig = {
			google: {
				families: [
					listTypography.family +
						(listTypography.variation
							? ":" + listTypography.variation
							: ""),
				],
			},
		};

		prosAndConsListTypography = (
			<WebfontLoader config={proConsListTypoConfig}></WebfontLoader>
		);
	}

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-pros-cons-style-${clientId}`}
				className={`affx-pros-cons-wrapper${
					className ? ` ${className}` : ""
				}`}
			>
				<div className={`affx-pros-cons-inner-wrapper ${layoutStyle}`}>
					<div className={"affx-pros-inner"}>
						<div className="pros-icon-title-wrap">
							<div className={"affiliatex-block-pros"}>
								<RichText
									tagName={Tag1}
									value={prosTitle}
									className={`affiliatex-title affiliatex-icon ${
										prosIconStatus
											? ` affiliatex-icon-${prosListIcon.name}`
											: ""
									}`}
									onChange={(prosTitle) =>
										setAttributes({ prosTitle })
									}
								/>
							</div>
						</div>
						<div className={"affiliatex-pros"}>
							{contentType === "list" &&
								unorderedType === "bullet" && (
									<RichText
										tagName={
											listType == "unordered"
												? "ul"
												: "ol"
										}
										multiline="li"
										className={
											"affiliatex-list affiliatex-list-type-unordered bullet"
										}
										placeholder={__(
											"Enter new item",
											"affiliatex"
										)}
										value={prosListItems}
										onChange={(prosListItems) =>
											setAttributes({ prosListItems })
										}
										keepPlaceholderOnFocus
									/>
								)}
							{contentType === "list" &&
								unorderedType === "icon" && (
									<RichText
										tagName={
											listType == "unordered"
												? "ul"
												: "ol"
										}
										multiline="li"
										className={`affiliatex-list affiliatex-list-type-unordered icon affiliatex-icon affiliatex-icon-${prosIcon.name}`}
										placeholder={__(
											"Enter new item",
											"affiliatex"
										)}
										value={prosListItems}
										onChange={(prosListItems) =>
											setAttributes({ prosListItems })
										}
										keepPlaceholderOnFocus
									/>
								)}
							{contentType === "paragraph" && (
								<RichText
									tagName="p"
									value={prosContent}
									placeholder={__(
										"Pros Content",
										"affiliatex"
									)}
									className="affiliatex-content"
									onChange={(prosContent) =>
										setAttributes({ prosContent })
									}
								/>
							)}
						</div>
					</div>
					<div className={"affx-cons-inner"}>
						<div className="cons-icon-title-wrap">
							<div className={"affiliatex-block-cons"}>
								<RichText
									tagName={Tag1}
									value={consTitle}
									className={`affiliatex-title affiliatex-icon ${
										consIconStatus
											? ` affiliatex-icon-${consListIcon.name}`
											: ""
									}`}
									onChange={(consTitle) =>
										setAttributes({ consTitle })
									}
								/>
							</div>
						</div>
						<div className={"affiliatex-cons"}>
							{contentType === "list" &&
								unorderedType === "bullet" && (
									<RichText
										tagName={
											listType == "unordered"
												? "ul"
												: "ol"
										}
										multiline="li"
										className={
											"affiliatex-list affiliatex-list-type-unordered bullet"
										}
										placeholder={__(
											"Enter new item",
											"affiliatex"
										)}
										value={consListItems}
										onChange={(consListItems) =>
											setAttributes({ consListItems })
										}
										keepPlaceholderOnFocus
									/>
								)}
							{contentType === "list" &&
								unorderedType === "icon" && (
									<RichText
										tagName={
											listType == "unordered"
												? "ul"
												: "ol"
										}
										multiline="li"
										className={`affiliatex-list affiliatex-list-type-unordered icon affiliatex-icon affiliatex-icon-${consIcon.name}`}
										placeholder={__(
											"Enter new item",
											"affiliatex"
										)}
										value={consListItems}
										onChange={(consListItems) =>
											setAttributes({ consListItems })
										}
										keepPlaceholderOnFocus
									/>
								)}
							{contentType === "paragraph" && (
								<RichText
									tagName="p"
									value={consContent}
									className="affiliatex-content"
									placeholder={__(
										"Cons Content",
										"affiliatex"
									)}
									onChange={(consContent) =>
										setAttributes({ consContent })
									}
								/>
							)}
						</div>
					</div>
				</div>
			</div>
			{prosAndConsTitleTypography}
			{prosAndConsListTypography}
		</Fragment>
	);
};
