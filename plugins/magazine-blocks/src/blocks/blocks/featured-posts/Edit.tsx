import { Button, Flex, FlexItem } from "@wordpress/components";
import { useSelect, withSelect } from "@wordpress/data";
import { dateI18n, __experimentalGetSettings } from "@wordpress/date";
import { Fragment, useMemo } from "@wordpress/element";
import { EditProps } from "blocks/types";
import classnames from "classnames";
import React from "react";

import { useBlockStyle } from "../../hooks";

import { useClientId, useCopyPasteStyles, useDeviceType } from "@blocks/hooks";

import { Icon } from "../../components";
import "../assets/sass/blocks/featured-posts/style.scss";
import InspectorControls from "./components/InspectorControls";

interface Props extends EditProps<any> {
	categories: Array<any>;
	posts: Array<any>;
	tags: Array<any>;
	numberOfPosts: number;
	page: number;
	author: Array<any>;
}

const Edit: React.ComponentType<Props> = (props) => {
	const {
		posts,
		className,
		attributes: {
			layout,
			layout1AdvancedStyle,
			layout2AdvancedStyle,
			column,

			category,
			postCount,
			postBoxStyle,

			enableHeading,
			headingLayout,
			headingLayout1AdvancedStyle,
			headingLayout2AdvancedStyle,

			enableFeaturedImage,
			hoverAnimation,

			postTitleMarkup,

			enableCategory,
			enableComment,

			metaPosition,
			enableAuthor,
			enableDate,

			enableExcerpt,
			excerptLimit,

			enableReadMore,
			readMoreText,

			size,

			enablePagination,
			hideOnDesktop,
		},
		setAttributes,
		categories,
		tags,
		numberOfPosts,
		page,
	} = props;

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "featured-posts",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	// @ts-ignore
	const authors = useSelect((select) => {
		const { getUsers } = select("core") as any;
		return getUsers({ per_page: -1 });
	}) as any[];

	const authorOptions = authors
		? authors.map((author) => ({
				value: author.id.toString(),
				label: author.name,
		  }))
		: [];

	const currentCategoryIndex = useMemo(() => {
		return categories
			? categories.findIndex((cat) => cat.id === parseInt(category))
			: -1; // Use -1 or another appropriate default value
	}, [category, categories]);

	const classNames = classnames(
		`mzb-featured-posts mzb-featured-posts-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

	const classNames2 = classnames(
		`mzb-posts mzb-${layout} mzb-post-col--${
			column || "1"
		} mzb-${postBoxStyle} `,
		{
			[`mzb-${layout1AdvancedStyle}`]: layout === `layout-1`,
			[`mzb-${layout2AdvancedStyle}`]: layout === `layout-2`,
		}
	);

	const classNames3 = classnames(`mzb-post-heading mzb-${headingLayout}`, {
		[`mzb-${headingLayout1AdvancedStyle}`]:
			headingLayout === `heading-layout-1`,
		[`mzb-${headingLayout2AdvancedStyle}`]:
			headingLayout === `heading-layout-2`,
	});

	const NumberedPagination = ({
		totalPages,
		currentPage,
		onPageChange,
	}: {
		totalPages: number;
		currentPage: number;
		onPageChange: (val: number) => void;
	}) => {
		if (totalPages < 2) {
			return null; // Don't render pagination if there's only one page
		}

		const pages = Array.from(
			{ length: totalPages },
			(_, index) => index + 1
		);

		return (
			<Flex>
				{/* eslint-disable-next-line */}
				{pages.map((page) => (
					<FlexItem key={page}>
						<Button
							isPrimary={page === currentPage}
							onClick={() => onPageChange(page)}
						>
							{page}
						</Button>
					</FlexItem>
				))}
			</Flex>
		);
	};

	// Fetch the next page of posts
	const loadMorePosts = (nextPage: number) => {
		// Update the block's attributes to trigger a re-render with the next page
		setAttributes({ page: nextPage });
	};

	// Render numbered pagination
	const pagination = (
		<NumberedPagination
			totalPages={Math.ceil(numberOfPosts / postCount)}
			currentPage={page}
			onPageChange={loadMorePosts}
		/>
	);

	return (
		<Fragment>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
				categories={categories}
				tags={tags}
				authorOptions={authorOptions}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<div className={classNames}>
				{enableHeading === true && (
					<div className={classNames3}>
						{" "}
						<h2>
							{categories?.[currentCategoryIndex]?.name ||
								"Latest"}
						</h2>
					</div>
				)}
				{
					<div className={classNames2}>
						<Fragment>
							{
								// eslint-disable-next-line array-callback-return
								(posts || []).map((post, idx) => {
									const maxWords = excerptLimit; // Replace with your desired word limit
									const excerpt = post.excerpt.rendered
										.split(" ")
										.slice(0, maxWords)
										.join(" ");

									// @ts-ignore
									return (
										<div
											className={`mzb-post ${
												(1 ===
													parseInt(column || "1") &&
													0 === idx) ||
												(2 ===
													parseInt(column || "1") &&
													(0 === idx || 1 === idx))
													? "mzb-first-post--highlight"
													: ""
											}`}
											key={idx}
										>
											{/* eslint-disable-next-line jsx-a11y/alt-text */}
											{post
												?.magazine_blocks_featured_image_url
												?.full?.[0] &&
												enableFeaturedImage && (
													<div
														className={`mzb-featured-image ${hoverAnimation}`}
													>
														{/* eslint-disable-next-line jsx-a11y/alt-text */}
														<img
															src={
																post
																	.magazine_blocks_featured_image_url
																	.full[0]
															}
														/>
													</div>
												)}
											<div className="mzb-post-content">
												{(enableCategory ||
													enableComment) && (
													<div className="mzb-post-meta">
														{enableCategory && (
															<span
																className="mzb-post-categories"
																dangerouslySetInnerHTML={{
																	__html: post.magazine_blocks_category,
																}}
															/>
														)}
														{enableComment && (
															<span className="comments-link">
																<Icon
																	type="frontendIcon"
																	name="commentIcon"
																	size={24}
																/>{" "}
																{
																	post.magazine_blocks_comment
																}
															</span>
														)}
													</div>
												)}

												{(enableAuthor || enableDate) &&
													"top" === metaPosition && (
														<div className="mzb-post-entry-meta">
															{enableAuthor && (
																<span className="mzb-post-author">
																	{/* eslint-disable-next-line jsx-a11y/alt-text */}
																	<img
																		className="post-author-image"
																		src={
																			post.magazine_blocks_author_image
																		}
																	/>
																	{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
																	<a href="#">
																		{" "}
																		{
																			post
																				.magazine_blocks_author
																				.display_name
																		}{" "}
																	</a>
																</span>
															)}

															{enableDate && (
																/* eslint-disable-next-line no-restricted-syntax,jsx-a11y/anchor-is-valid */
																<span className="mzb-post-date">
																	<Icon
																		type="blockIcon"
																		name="calendar"
																		size={
																			24
																		}
																	/>{" "}
																	<a href="#">
																		{" "}
																		{dateI18n(
																			__experimentalGetSettings()
																				.formats
																				.date,
																			post.date_gmt,
																			undefined
																		)}{" "}
																	</a>
																</span>
															)}
														</div>
													)}

												<>
													{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
													{React.createElement(
														postTitleMarkup,
														{
															className:
																"mzb-post-title",
														},
														<a href="#">
															{
																post.title
																	.rendered
															}
														</a>
													)}
												</>

												{(enableAuthor || enableDate) &&
													"bottom" ===
														metaPosition && (
														<div className="mzb-post-entry-meta">
															{enableAuthor && (
																<span className="mzb-post-author">
																	{/* eslint-disable-next-line jsx-a11y/alt-text */}
																	<img
																		className="post-author-image"
																		src={
																			post.magazine_blocks_author_image
																		}
																	/>
																	{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
																	<a href="#">
																		{" "}
																		{
																			post
																				.magazine_blocks_author
																				.display_name
																		}{" "}
																	</a>
																</span>
															)}

															{enableDate && (
																/* eslint-disable-next-line no-restricted-syntax,jsx-a11y/anchor-is-valid */
																<span className="mzb-post-date">
																	<Icon
																		type="blockIcon"
																		name="calendar"
																		size={
																			24
																		}
																	/>{" "}
																	<a href="#">
																		{" "}
																		{dateI18n(
																			__experimentalGetSettings()
																				.formats
																				.date,
																			post.date_gmt,
																			undefined
																		)}{" "}
																	</a>
																</span>
															)}
														</div>
													)}
												{(enableExcerpt ||
													enableReadMore) && (
													<div className="mzb-entry-content">
														{enableExcerpt && (
															<div
																className="mzb-entry-summary"
																dangerouslySetInnerHTML={{
																	__html: excerpt,
																}}
															/>
														)}
														{enableReadMore && (
															<div className="mzb-read-more">
																{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
																<a href="#">
																	{
																		readMoreText
																	}
																</a>
															</div>
														)}
													</div>
												)}
											</div>
										</div>
									);
								})
							}
						</Fragment>
					</div>
				}
				{enablePagination && (
					<div className="mzb-pagination-numbers">{pagination}</div>
				)}
			</div>
		</Fragment>
	);
};

// @ts-ignore
export default withSelect((select, props) => {
	const { getEntityRecords } = select("core");
	const {
		attributes: {
			category,
			tag,
			postCount,
			order,
			orderBy,
			orderType,
			authorName,
			excludedCategory,
			page,
		},
	} = props;
	const query = {
		order,
	};

	return {
		posts: getEntityRecords("postType", "post", {
			...query,
			categories: "all" === category ? undefined : parseInt(category),
			tags: "all" === tag ? undefined : tag,
			per_page: postCount,
			page,
			orderby: orderBy,
			order: orderType,
			author: authorName,
			categories_exclude: "" === excludedCategory ? [] : excludedCategory,
		}),
		numberOfPosts:
			getEntityRecords("postType", "post", {
				per_page: -1,
				categories: "all" === category ? undefined : parseInt(category),
			})?.length || 0,
		categories:
			getEntityRecords("taxonomy", "category", { per_page: -1 }) || [],
		tags: getEntityRecords("taxonomy", "post_tag", { per_page: -1 }) || [],
	};
})(Edit);
