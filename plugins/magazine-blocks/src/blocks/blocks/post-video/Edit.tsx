import { withSelect } from "@wordpress/data";
import { Fragment } from "@wordpress/element";
import classnames from "classnames";
import React from "react";
import { Icon } from "../../components";
import {
	useBlockStyle,
	useClientId,
	useCopyPasteStyles,
	useDeviceType,
} from "../../hooks";
import { EditProps } from "../../types";
import "../assets/sass/blocks/post-video/style.scss";
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
		attributes: { size, column, hideOnDesktop },
		setAttributes,
		categories,
		tags,
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

	const classNames = classnames(
		`mzb-post-video mzb-post-video-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

	return (
		<Fragment>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
				categories={categories}
				tags={tags}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<div className={classNames}>
				{
					<div
						className={`mzb-posts  mzb-post-col--${column || "3"}`}
					>
						<Fragment>
							{
								// eslint-disable-next-line array-callback-return
								(posts || []).map((post, idx) => {
									if (post.format === "video") {
										return (
											<div
												className={`mzb-post`}
												key={idx}
											>
												{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
												<a href="#">
													<div className="mzb-image-overlay">
														{/* eslint-disable-next-line jsx-a11y/alt-text */}
														<img
															className="mzb-featured-image"
															src={
																post
																	.magazine_blocks_featured_image_url
																	.full[0]
															}
														/>
													</div>
													<div
														className="mzb-custom-embed-play"
														role="button"
													>
														<Icon
															type="frontendIcon"
															name="youtube-play"
															size={24}
														/>
													</div>
												</a>
											</div>
										);
									}
								})
							}
						</Fragment>
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
		attributes: {
			category,
			tag,
			postCount,
			order,
			orderBy,
			orderType,
			authorName,
			excludedCategory,
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
