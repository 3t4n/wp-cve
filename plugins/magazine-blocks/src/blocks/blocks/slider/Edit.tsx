import {
	useBlockStyle,
	useClientId,
	useCopyPasteStyles,
	useDeviceType,
} from "@blocks/hooks";
import { Splide, SplideSlide } from "@splidejs/react-splide";
import "@splidejs/splide/dist/css/themes/splide-default.min.css";
import { useSelect, withSelect } from "@wordpress/data";
import { dateI18n, __experimentalGetSettings } from "@wordpress/date";
import { Fragment } from "@wordpress/element";
import classnames from "classnames";
import React from "react";
import { Icon } from "../../components";
import { EditProps } from "../../types";
import "../assets/sass/blocks/slider/editor.scss";
import "../assets/sass/blocks/slider/style.scss";
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
			enablePauseOnHover,
			sliderSpeed,

			enableArrow,

			enableCategory,

			metaPosition,
			enableAuthor,
			enableDate,

			excerptLimit,
			enableExcerpt,

			enableReadMore,
			readMoreText,

			enableDot,
			hideOnDesktop,
		},

		setAttributes,
		categories,
		tags,
	} = props;

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "slider",
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

	const classNames = classnames(
		`mzb-slider-${clientId}`,
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
				authorOptions={authorOptions}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<div className={classNames}>
				<Splide
					options={{
						perPage: 1,
						pagination: enableDot,
						paginationType: "dot",
						pauseOnHover: enablePauseOnHover,
						interval: 2000,
						speed: sliderSpeed,
						autoplay: false,
						arrows: enableArrow,
						type: "loop",
					}}
				>
					{
						// eslint-disable-next-line array-callback-return
						(posts || []).map((post, idx) => {
							const maxWords = excerptLimit; // Replace with your desired word limit
							const excerpt = post.excerpt.rendered
								.split(" ")
								.slice(0, maxWords)
								.join(" ");

							return (
								<SplideSlide key={idx}>
									{post?.magazine_blocks_featured_image_url
										?.full?.[0] && (
										<div className="mzb-featured-image">
											{/* eslint-disable-next-line jsx-a11y/alt-text */}
											<img
												src={
													post
														.magazine_blocks_featured_image_url
														.full[0]
												}
												alt=""
											/>
										</div>
									)}

									<div className="mzb-post-content">
										{enableCategory && (
											<div className="mzb-post-meta">
												<span
													className="mzb-post-categories"
													dangerouslySetInnerHTML={{
														__html: post.magazine_blocks_category,
													}}
												/>
											</div>
										)}

										{metaPosition === "bottom" && (
											<>
												<h3 className="mzb-post-title">
													{/* eslint-disable-next-line react/no-unescaped-entities,jsx-a11y/anchor-is-valid */}
													<a href={post.link}>
														{post.title.rendered}
													</a>
												</h3>

												<div className="mzb-post-entry-meta">
													{/* eslint-disable-next-line no-restricted-syntax */}
													{enableAuthor === true && (
														<span className="mzb-post-author">
															{/* eslint-disable-next-line jsx-a11y/alt-text */}
															<img
																className="author-display-image"
																src={
																	post.magazine_blocks_author_image
																}
															/>
															<a
																href={
																	post
																		.magazine_blocks_author
																		.author_link
																}
															>
																{" "}
																{
																	post
																		.magazine_blocks_author
																		.display_name
																}{" "}
															</a>
														</span>
													)}

													{enableDate === true && (
														/* eslint-disable-next-line no-restricted-syntax,jsx-a11y/anchor-is-valid */
														<span className="mzb-post-date">
															<Icon
																type="blockIcon"
																name="calendar"
																size={24}
															/>
															<a href={post.link}>
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
											</>
										)}

										{metaPosition === "top" && (
											<>
												<div className="mzb-post-entry-meta">
													{/* eslint-disable-next-line no-restricted-syntax */}
													{enableAuthor === true && (
														<span className="mzb-post-author">
															{/* eslint-disable-next-line jsx-a11y/alt-text */}
															<img
																className="author-display-image"
																src={
																	post.magazine_blocks_author_image
																}
															/>
															<a
																href={
																	post
																		.magazine_blocks_author
																		.author_link
																}
															>
																{" "}
																{
																	post
																		.magazine_blocks_author
																		.display_name
																}{" "}
															</a>
														</span>
													)}

													{enableDate === true && (
														/* eslint-disable-next-line no-restricted-syntax,jsx-a11y/anchor-is-valid */
														<span className="mzb-post-date">
															<Icon
																type="blockIcon"
																name="calendar"
																size={24}
															/>
															<a href={post.link}>
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

												<h3 className="mzb-post-title">
													{/* eslint-disable-next-line react/no-unescaped-entities,jsx-a11y/anchor-is-valid */}
													<a href={post.link}>
														{post.title.rendered}
													</a>
												</h3>
											</>
										)}

										{(enableExcerpt || enableReadMore) && (
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
														<a
															href={
																post.excerpt
																	.rendered
															}
														>
															{readMoreText}
														</a>
													</div>
												)}
											</div>
										)}
									</div>
								</SplideSlide>
							);
						})
					}
				</Splide>
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
