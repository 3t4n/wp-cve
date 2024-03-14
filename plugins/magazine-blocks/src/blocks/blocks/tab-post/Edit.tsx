import { useClientId, useCopyPasteStyles, useDeviceType } from "@blocks/hooks";
import { Button, Flex, FlexItem } from "@wordpress/components";
import { useSelect, withSelect } from "@wordpress/data";
import { dateI18n, __experimentalGetSettings } from "@wordpress/date";
import { Fragment, useMemo, useState } from "@wordpress/element";
import { EditProps } from "blocks/types";
import classnames from "classnames";
import React from "react";
import { Icon } from "../../components";
import { useBlockStyle } from "../../hooks";
import "../assets/sass/blocks/tab-post/style.scss";
import InspectorControls from "./components/InspectorControls";

interface Props extends EditProps<any> {
	categories: Array<any>;
	popularPosts: Array<any>;
	tags: Array<any>;
	numberOfPosts: number;
	page: number;
	latestPosts: Array<any>;
	author: Array<any>;
}

const Edit: React.ComponentType<Props> = (props) => {
	const {
		latestPosts,
		popularPosts,
		className,
		attributes: { category, size, hideOnDesktop },
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
		blockName: "tab-post",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const [tab, setTab] = useState("latest");

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
		`mzb-tab-post mzb-tab-post-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

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
			totalPages={Math.ceil(numberOfPosts / 4)}
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
				{
					<div className="mzb-posts">
						<div className="mzb-tab-controls">
							{/* eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */}
							<div
								data-value="latest"
								onClick={(e) => {
									setTab(
										(e.target as HTMLElement).getAttribute(
											"data-value"
										) ?? ""
									); // Provide a default value if null
								}}
								className={`mzb-tab-title ${
									"latest" === tab ? "active" : ""
								}`}
							>
								Latest
							</div>
							{/* eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */}
							<div
								data-value="popular"
								onClick={(e) => {
									setTab(
										(e.target as HTMLElement).getAttribute(
											"data-value"
										) ?? ""
									); // Provide a default value if null
								}}
								className={`mzb-tab-title ${
									"popular" === tab ? "active" : ""
								}`}
							>
								Popular
							</div>
						</div>
						{"latest" === tab &&
							(latestPosts || []).map((post, idx) => {
								return (
									<div className="mzb-post" key={idx}>
										{post
											?.magazine_blocks_featured_image_url
											?.thumbnail?.[0] && (
											<div className="mzb-featured-image">
												{/* eslint-disable-next-line jsx-a11y/alt-text */}
												<img
													src={
														post
															.magazine_blocks_featured_image_url
															.thumbnail[0]
													}
												/>
											</div>
										)}
										<div className="mzb-post-content">
											<h3 className="mzb-post-title">
												{/* eslint-disable-next-line react/no-unescaped-entities,jsx-a11y/anchor-is-valid */}
												<a href={post.link}>
													{post.title.rendered}
												</a>
											</h3>

											<div className="mzb-post-entry-meta">
												<div className="mzb-post-entry-meta">
													{/* eslint-disable-next-line no-restricted-syntax,jsx-a11y/anchor-is-valid */}
													<span className="mzb-post-date">
														<Icon
															type="blockIcon"
															name="calendar"
															size={24}
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
												</div>
											</div>
										</div>
									</div>
								);
							})}
						{"popular" === tab &&
							(popularPosts || []).map((post, idx) => {
								return (
									<div className="mzb-post" key={idx}>
										{post
											?.magazine_blocks_featured_image_url
											?.thumbnail?.[0] && (
											<div className="mzb-featured-image">
												{/* eslint-disable-next-line jsx-a11y/alt-text */}
												<img
													src={
														post
															.magazine_blocks_featured_image_url
															.thumbnail[0]
													}
												/>
											</div>
										)}
										<div className="mzb-post-content">
											<h3 className="mzb-post-title">
												{/* eslint-disable-next-line react/no-unescaped-entities,jsx-a11y/anchor-is-valid */}
												<a href={post.link}>
													{post.title.rendered}
												</a>
											</h3>

											<div className="mzb-post-entry-meta">
												{/* eslint-disable-next-line no-restricted-syntax,jsx-a11y/anchor-is-valid */}
												<span className="mzb-post-date">
													<Icon
														type="blockIcon"
														name="calendar"
														size={24}
													/>{" "}
													<a href="#">
														{" "}
														{dateI18n(
															__experimentalGetSettings()
																.formats.date,
															post.date_gmt,
															undefined
														)}{" "}
													</a>
												</span>
											</div>
										</div>
									</div>
								);
							})}
					</div>
				}
			</div>
		</Fragment>
	);
};

// @ts-ignore
export default withSelect((select, props) => {
	const { getEntityRecords } = select("core");
	const {
		attributes: { category, postCount, page },
	} = props;

	interface Post {
		magazine_blocks_comment?: number; // Assuming magazine_blocks_comment is a number
	}

	return {
		latestPosts: getEntityRecords("postType", "post", {
			per_page: postCount,
			page,
			categories: "all" === category ? undefined : parseInt(category),
		}),
		popularPosts: (
			getEntityRecords("postType", "post", { per_page: -1 }) || []
		)
			.sort(
				(a: Post, b: Post) =>
					(b.magazine_blocks_comment || 0) -
					(a.magazine_blocks_comment || 0)
			)
			.slice(0, postCount),
		numberOfPosts:
			getEntityRecords("postType", "post", {
				per_page: -1,
				categories: "all" === category ? undefined : parseInt(category),
			})?.length || 0,
	};
})(Edit);
