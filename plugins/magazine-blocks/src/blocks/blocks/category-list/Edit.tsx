import { useClientId, useCopyPasteStyles, useDeviceType } from "@blocks/hooks";
import { Button, Flex, FlexItem } from "@wordpress/components";
import { select, useSelect, withSelect } from "@wordpress/data";
import { Fragment, useMemo } from "@wordpress/element";
import { EditProps } from "blocks/types";
import classnames from "classnames";
import React from "react";
import { useBlockStyle } from "../../hooks";
import "../assets/sass/blocks/category-list/style.scss";
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
		className,
		attributes: {
			layout,
			layout1AdvancedStyle,
			layout2AdvancedStyle,
			postBoxStyle,

			category,
			postCount,

			categoryCount,

			enableHeading,
			headingLayout,
			headingLayout1AdvancedStyle,
			headingLayout2AdvancedStyle,
			size,
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
		blockName: "category-list",
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

	const featureImage = (id: number) => {
		const post = select("core").getEntityRecords("postType", "post", {
			per_page: 1,
			categories: id,
		});
		return post?.[0]?.magazine_blocks_featured_image_url?.full?.[0] || null;
	};

	const classNames = classnames(
		`mzb-category-list mzb-category-list-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

	const classNames2 = classnames(`mzb-posts mzb-${layout}`, {
		[`mzb-${layout1AdvancedStyle}`]: layout === `layout-1`,
		[`mzb-${layout2AdvancedStyle}`]: layout === `layout-2`,
		[`separator`]: postBoxStyle && layout === `layout-3`,
	});

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
						<h2> Categories </h2>
					</div>
				)}
				<div className={classNames2}>
					<Fragment>
						{/* eslint-disable-next-line array-callback-return */}
						{(categories?.slice(0, categoryCount) || []).map(
							({ id, name, count, link, slug }, idx) => {
								if (count > 0) {
									return (
										<div className="mzb-post" key={idx}>
											<div
												className="mzb-title-wrapper"
												style={{
													backgroundImage: `url(${featureImage(
														id
													)})`,
												}}
											>
												<span
													className={`mzb-post-categories ${slug}`}
												>
													{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
													<a href={link}>{name}</a>
												</span>
											</div>
											<div className="mzb-post-count-wrapper">
												<div className="mzb-post-count">
													{""}
													{/* eslint-disable-next-line react/no-unescaped-entities,jsx-a11y/anchor-is-valid */}
													<a href={link}>{count}</a>
													<a href={link}> Posts </a>
												</div>
											</div>
										</div>
									);
								}
							}
						)}
					</Fragment>
				</div>
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
