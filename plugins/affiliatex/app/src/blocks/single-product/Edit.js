import BlockInspector from "./Inspector";
import { __ } from "@wordpress/i18n";
import { useEffect } from "@wordpress/element";
import blocks_styling from "./styling";
import React from "react";
import { Rating } from "react-simple-star-rating";
import { applyFilters } from "@wordpress/hooks";

const { RichText, MediaUpload, InnerBlocks } = wp.blockEditor;

const { Button } = wp.components;

export default ({
	attributes,
	setAttributes,
	className,
	isSelected,
	clientId,
}) => {
	useEffect(() => {
		const $style = document.createElement("style");
		$style.setAttribute(
			"id",
			"affiliatex-single-product-style-" + clientId
		);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-single-product-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-single-product-style",
				clientId
			);
		}
	}, [attributes]);

	const { Fragment } = wp.element;
	const {
		productLayout,
		productTitle,
		productTitleTag,
		productContent,
		productSubTitle,
		productSubTitleTag,
		productContentType,
		ContentListType,
		productContentList,
		productImageAlign,
		productSalePrice,
		productPrice,
		ImgID,
		ratings,
		edRatings,
		edTitle,
		edSubtitle,
		edContent,
		edPricing,
		PricingType,
		productRatingColor,
		ratingInactiveColor,
		ratingContent,
		ratingStarSize,
		edRibbon,
		ribbonText,
		productRibbonLayout,
		edButton,
		ImgUrl,
		productIconList,
		edProductImage,
		numberRatings,
		productPricingAlign,
		productRatingAlign,
		productImageType,
		productImageExternal,
		productImageSiteStripe,
		productStarRatingAlign,
	} = attributes;

	const layoutClass =
		productLayout === "layoutOne"
			? " product-layout-1"
			: productLayout === "layoutTwo"
				? " product-layout-2"
				: productLayout === "layoutThree"
					? " product-layout-3"
					: "";

	const ribbonLayout =
		productRibbonLayout === "one"
			? " ribbon-layout-1"
			: productRibbonLayout === "two"
				? " ribbon-layout-2"
				: "";

	const ratingClass =
		PricingType === "picture"
			? "star-rating"
			: PricingType === "number"
				? "number-rating"
				: "";

	const imageAlign =
		edProductImage == true ? "image-" + productImageAlign : "";

	const imageClass = edProductImage == false ? "no-image" : "";

	const productRatingNumberClass =
		PricingType === "number" ? "rating-align-" + productRatingAlign : "";

	const onSelectImage = (img) => {
		setAttributes({
			ImgID: img.id,
			ImgUrl: img.url,
			ImgAlt: img.alt,
		});
	};
	const onReplaceImage = (replace) => {
		setAttributes({
			ImgID: replace.id,
			ImgUrl: replace.url,
			ImgAlt: replace.alt,
		});
	};
	const onRemoveImage = () => {
		setAttributes({
			ImgID: null,
			ImgUrl: null,
			ImgAlt: null,
		});
	};

	const ImageURL =
		productImageType === "default" ? ImgUrl : productImageExternal;

	const isSiteStripe = "sitestripe" === productImageType && '' !== productImageSiteStripe ? true : false;

	const TagTitle = productTitleTag;
	const TagSubtitle = productSubTitleTag;

	const MY_TEMPLATE = [
		[
			"affiliatex/buttons",
			{
				buttonLabel: "Buy Now",
				buttonMargin: {
					desktop: {
						top: "16px",
						left: "0px",
						right: "0px",
						bottom: "0px",
					},
					mobile: {
						top: "16px",
						left: "0px",
						right: "0px",
						bottom: "0px",
					},
					tablet: {
						top: "16px",
						left: "0px",
						right: "0px",
						bottom: "0px",
					},
				},
			},
		],
	];

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-single-product-style-${clientId}`}
				className={className}
			>
				{productLayout &&
					(productLayout == "layoutOne" ||
						productLayout == "layoutTwo" ||
						productLayout == "layoutThree") && (
						<div
							className={
								`affx-single-product-wrapper` + layoutClass
							}
						>
							<div className={`affx-sp-inner`}>
								<div
									className={`affx-sp-content ${imageAlign} ${imageClass}`}
								>
									{layoutClass != " product-layout-2" && (
										<>
											{edRibbon == true && (
												<div
													className={
														`affx-sp-ribbon` +
														ribbonLayout
													}
												>
													<RichText
														placeholder={__(
															"Enter Ribbon Text",
															"affiliatex"
														)}
														className="affx-sp-ribbon-title"
														value={ribbonText}
														onChange={(newText) =>
															setAttributes({
																ribbonText: newText,
															})
														}
													/>
												</div>
											)}
											{edProductImage == true && (
												<div
													className={`affx-sp-img-wrapper`}
												>
													{isSiteStripe ? (
														<div
															dangerouslySetInnerHTML={{ __html: productImageSiteStripe }}
														>
														</div>
													) : (
														<img src={ImageURL} />
													)}
													{!ImgID ? (
														<MediaUpload
															onSelect={
																onSelectImage
															}
															type="image"
															value={ImgID}
															render={({
																open,
															}) => (
																<Button
																	className={
																		"affx-upload-btn"
																	}
																	onClick={
																		open
																	}
																>
																	{__(
																		" Upload Image",
																		"affiliatex"
																	)}
																</Button>
															)}
														></MediaUpload>
													) : (
														<div className="upload-btn-wrapper">
															{isSelected ? (
																<MediaUpload
																	onSelect={
																		onReplaceImage
																	}
																	type="image"
																	value={
																		ImgID
																	}
																	render={({
																		open,
																	}) => (
																		<Button
																			className={
																				"components-button affx-replace-btn"
																			}
																			onClick={
																				open
																			}
																		>
																			{__(
																				" Replace Image",
																				"affiliatex"
																			)}
																		</Button>
																	)}
																></MediaUpload>
															) : null}
															<Button
																className="components-button remove-image"
																onClick={
																	onRemoveImage
																}
															>
																{__(
																	"Remove Image",
																	"affiliatex"
																)}
															</Button>
														</div>
													)}
												</div>
											)}
											<div
												className={`affx-sp-content-wrapper ${edRibbon == true &&
													"has-ribbon"
													}`}
											>
												<div
													className={`title-wrapper affx-${ratingClass} ${productRatingNumberClass}`}
												>
													<div className="affx-title-left">
														{edTitle == true && (
															<TagTitle
																className={`affx-single-product-title`}
															>
																<RichText
																	placeholder={__(
																		"Enter Product Title",
																		"affiliatex"
																	)}
																	value={
																		productTitle
																	}
																	onChange={(
																		newTitle
																	) =>
																		setAttributes(
																			{
																				productTitle: newTitle,
																			}
																		)
																	}
																/>
															</TagTitle>
														)}
														{edSubtitle == true && (
															<TagSubtitle
																className={`affx-single-product-subtitle`}
															>
																<RichText
																	placeholder={__(
																		"Enter Product Sub Title",
																		"affiliatex"
																	)}
																	value={
																		productSubTitle
																	}
																	onChange={(
																		newTitle
																	) =>
																		setAttributes(
																			{
																				productSubTitle: newTitle,
																			}
																		)
																	}
																/>
															</TagSubtitle>
														)}
													</div>
													{edRatings == true &&
														PricingType ===
														"picture" && (
															<div
																className={`affx-sp-pricing-pic rating-align-${productStarRatingAlign}`}
															>
																<Rating
																	onClick={(
																		ratings
																	) =>
																		setAttributes(
																			{
																				ratings,
																			}
																		)
																	}
																	ratingValue={
																		ratings
																	}
																	fillColor={
																		productRatingColor
																	}
																	emptyColor={
																		ratingInactiveColor
																	}
																	size={
																		ratingStarSize
																	}
																/>
															</div>
														)}
													{edRatings == true &&
														PricingType ===
														"number" && (
															<div
																className={`affx-rating-number`}
															>
																<RichText
																	tagName="span"
																	type="number"
																	placeholder="0"
																	value={
																		numberRatings
																	}
																	className="affx-rating-num num"
																/>
																<RichText
																	tagName="span"
																	type="text"
																	placeholder="score label"
																	value={
																		ratingContent
																	}
																	className="affx-rating-number-label label"
																/>
															</div>
														)}
												</div>
												{edPricing == true && (
													<div
														className={`affx-sp-price pricing-align-${productPricingAlign}`}
													>
														<RichText
															className={`affx-sp-marked-price`}
															value={
																productSalePrice
															}
															onChange={(
																productSalePrice
															) =>
																setAttributes({
																	productSalePrice,
																})
															}
														/>
														<RichText
															tagName="del"
															className={`affx-sp-sale-price`}
															value={productPrice}
															onChange={(
																productPrice
															) =>
																setAttributes({
																	productPrice,
																})
															}
														/>
													</div>
												)}
												{edContent == true && (
													<div
														className={`affx-single-product-content`}
													>
														{productContentType ===
															"list" && (
																<RichText
																	tagName={
																		ContentListType ==
																			"unordered"
																			? "ul"
																			: "ol"
																	}
																	multiline="li"
																	className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
																	placeholder={__(
																		"Enter Description",
																		"affiliatex"
																	)}
																	value={
																		productContentList
																	}
																	onChange={(
																		productContentList
																	) =>
																		setAttributes(
																			{
																				productContentList,
																			}
																		)
																	}
																	keepPlaceholderOnFocus
																/>
															)}
														{productContentType ===
															"paragraph" && (
																<RichText
																	tagName="p"
																	value={
																		productContent
																	}
																	className="affiliatex-content"
																	placeholder={__(
																		"Enter Description",
																		"affiliatex"
																	)}
																	onChange={(
																		productContent
																	) =>
																		setAttributes(
																			{
																				productContent,
																			}
																		)
																	}
																/>
															)}
													</div>
												)}

												{edButton == true && (
													<div className="button-wrapper">
														<InnerBlocks
															orientation="horizontal"
															template={
																MY_TEMPLATE
															}
															templateLock="all"
														/>
													</div>
												)}
											</div>
										</>
									)}
									{layoutClass == " product-layout-2" && (
										<>
											<div
												className={`title-wrapper affx-${ratingClass} ${productRatingNumberClass}`}
											>
												<div className="affx-title-left">
													{edRibbon == true && (
														<div
															className={
																`affx-sp-ribbon` +
																ribbonLayout
															}
														>
															<RichText
																placeholder={__(
																	"Enter Ribbon Text",
																	"affiliatex"
																)}
																className="affx-sp-ribbon-title"
																value={
																	ribbonText
																}
																onChange={(
																	newText
																) =>
																	setAttributes(
																		{
																			ribbonText: newText,
																		}
																	)
																}
															/>
														</div>
													)}
													{edTitle == true && (
														<TagTitle
															className={`affx-single-product-title`}
														>
															<RichText
																placeholder={__(
																	"Enter Product Title",
																	"affiliatex"
																)}
																value={
																	productTitle
																}
																onChange={(
																	newTitle
																) =>
																	setAttributes(
																		{
																			productTitle: newTitle,
																		}
																	)
																}
															/>
														</TagTitle>
													)}
													{edSubtitle == true && (
														<TagSubtitle
															className={`affx-single-product-subtitle`}
														>
															<RichText
																placeholder={__(
																	"Enter Product Sub Title",
																	"affiliatex"
																)}
																value={
																	productSubTitle
																}
																onChange={(
																	newTitle
																) =>
																	setAttributes(
																		{
																			productSubTitle: newTitle,
																		}
																	)
																}
															/>
														</TagSubtitle>
													)}
												</div>
												{edRatings == true &&
													PricingType ===
													"picture" && (
														<div
															className={`affx-sp-pricing-pic rating-align-${productStarRatingAlign}`}
														>
															<Rating
																onClick={(
																	ratings
																) =>
																	setAttributes(
																		{
																			ratings,
																		}
																	)
																}
																ratingValue={
																	ratings
																}
																fillColor={
																	productRatingColor
																}
																emptyColor={
																	ratingInactiveColor
																}
																size={
																	ratingStarSize
																}
															/>
														</div>
													)}
												{edRatings == true &&
													PricingType ===
													"number" && (
														<div
															className={`affx-rating-number`}
														>
															<RichText
																tagName="span"
																type="number"
																placeholder="0"
																value={
																	numberRatings
																}
																className="affx-rating-num num"
															/>
															<RichText
																tagName="span"
																type="text"
																placeholder="score label"
																value={
																	ratingContent
																}
																className="affx-rating-number-label label"
															/>
														</div>
													)}
											</div>
											{edProductImage == true && (
												<div
													className={`affx-sp-img-wrapper`}
												>
													{isSiteStripe ? (
														<div
															dangerouslySetInnerHTML={{ __html: productImageSiteStripe }}
														>
														</div>
													) : (
														<img src={ImageURL} />
													)}
													{!ImgID ? (
														<MediaUpload
															onSelect={
																onSelectImage
															}
															type="image"
															value={ImgID}
															render={({
																open,
															}) => (
																<Button
																	className={
																		"affx-upload-btn"
																	}
																	onClick={
																		open
																	}
																>
																	{__(
																		" Upload Image",
																		"affiliatex"
																	)}
																</Button>
															)}
														></MediaUpload>
													) : (
														<div className="upload-btn-wrapper">
															{isSelected ? (
																<MediaUpload
																	onSelect={
																		onReplaceImage
																	}
																	type="image"
																	value={
																		ImgID
																	}
																	render={({
																		open,
																	}) => (
																		<Button
																			className={
																				"components-button affx-replace-btn"
																			}
																			onClick={
																				open
																			}
																		>
																			{__(
																				" Replace Image",
																				"affiliatex"
																			)}
																		</Button>
																	)}
																></MediaUpload>
															) : null}
															<Button
																className="components-button remove-image"
																onClick={
																	onRemoveImage
																}
															>
																{__(
																	"Remove Image",
																	"affiliatex"
																)}
															</Button>
														</div>
													)}
												</div>
											)}
										</>
									)}
									{layoutClass == " product-layout-2" &&
										edPricing == true && (
											<div
												className={`affx-sp-price pricing-align-${productPricingAlign}`}
											>
												<RichText
													className={`affx-sp-marked-price`}
													value={productSalePrice}
													onChange={(
														productSalePrice
													) =>
														setAttributes({
															productSalePrice,
														})
													}
												/>
												<RichText
													tagName="del"
													className={`affx-sp-sale-price`}
													value={productPrice}
													onChange={(productPrice) =>
														setAttributes({
															productPrice,
														})
													}
												/>
											</div>
										)}
									{layoutClass == " product-layout-2" &&
										edContent == true && (
											<div
												className={`affx-single-product-content`}
											>
												{productContentType ===
													"list" && (
														<RichText
															tagName={
																ContentListType ==
																	"unordered"
																	? "ul"
																	: "ol"
															}
															multiline="li"
															className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
															placeholder={__(
																"Enter Description",
																"affiliatex"
															)}
															value={
																productContentList
															}
															onChange={(
																productContentList
															) =>
																setAttributes({
																	productContentList,
																})
															}
															keepPlaceholderOnFocus
														/>
													)}
												{productContentType ===
													"paragraph" && (
														<RichText
															tagName="p"
															value={productContent}
															className="affiliatex-content"
															placeholder={__(
																"Enter Description",
																"affiliatex"
															)}
															onChange={(
																productContent
															) =>
																setAttributes({
																	productContent,
																})
															}
														/>
													)}
											</div>
										)}
									{layoutClass == " product-layout-2" &&
										edContent == true && (
											<>
												{edButton == true && (
													<div className="button-wrapper">
														<InnerBlocks
															orientation="horizontal"
															template={
																MY_TEMPLATE
															}
															templateLock="all"
														/>
													</div>
												)}
											</>
										)}
									{layoutClass == " product-layout-3" &&
										edContent == true && (
											<>
												{edButton == true && (
													<div className="button-wrapper">
														<InnerBlocks
															orientation="horizontal"
															template={
																MY_TEMPLATE
															}
															templateLock="all"
														/>
													</div>
												)}
											</>
										)}
								</div>
							</div>
						</div>
					)}
				{applyFilters(
					"affx_add_single_product_layouts",
					null,
					attributes,
					setAttributes
				)}
			</div>
		</Fragment>
	);
};
