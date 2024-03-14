import { __ } from "@wordpress/i18n";
import React from "react";
import { RatingView } from "react-simple-star-rating";
import { applyFilters } from "@wordpress/hooks";

export default ({ attributes, className }) => {
	const { RichText, InnerBlocks } = wp.blockEditor;

	const {
		block_id,
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
		productIconList,
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
		edButton,
		edProductImage,
		edRibbon,
		productRibbonLayout,
		ribbonText,
		ImgUrl,
		numberRatings,
		productRatingAlign,
		productStarRatingAlign,
		productImageType,
		productImageExternal,
		productImageSiteStripe,
		productPricingAlign,
	} = attributes;

	const TagTitle = productTitleTag;
	const TagSubtitle = productSubTitleTag;

	const layoutClass =
		productLayout === "layoutOne"
			? " product-layout-1"
			: productLayout === "layoutTwo"
				? " product-layout-2"
				: productLayout === "layoutThree"
					? " product-layout-3"
					: "";

	const ratingClass =
		PricingType === "picture"
			? "star-rating"
			: PricingType === "number"
				? "number-rating"
				: "";

	const imageAlign =
		edProductImage == true ? "image-" + productImageAlign : "";

	const ribbonLayout =
		productRibbonLayout === "one"
			? " ribbon-layout-1"
			: productRibbonLayout === "two"
				? " ribbon-layout-2"
				: "";

	const imageClass = edProductImage == false ? "no-image" : "";

	const productRatingNumberClass =
		PricingType === "number" ? "rating-align-" + productRatingAlign : "";

	const ImageURL =
		productImageType === "default" ? ImgUrl : productImageExternal;

	const isSiteStripe = "sitestripe" === productImageType && '' !== productImageSiteStripe ? true : false;

	return (
		<div
			id={`affiliatex-single-product-style-${block_id}`}
			className={className}
		>
			{productLayout &&
				(productLayout == "layoutOne" ||
					productLayout == "layoutTwo" ||
					productLayout == "layoutThree") && (
					<div
						className={`affx-single-product-wrapper` + layoutClass}
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
												<div
													className={`affx-sp-ribbon-title`}
												>
													<RichText.Content
														value={ribbonText}
													/>
												</div>
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
											</div>
										)}
										<div
											className={`affx-sp-content-wrapper`}
										>
											<div
												className={`title-wrapper affx-${ratingClass} ${productRatingNumberClass}`}
											>
												<div className="affx-title-left">
													{edTitle == true && (
														<TagTitle
															className={`affx-single-product-title`}
														>
															<RichText.Content
																value={
																	productTitle
																}
															/>
														</TagTitle>
													)}
													{edSubtitle == true && (
														<TagSubtitle
															className={`affx-single-product-subtitle`}
														>
															<RichText.Content
																placeholder={__(
																	"Enter Product Sub Title",
																	"affiliatex"
																)}
																value={
																	productSubTitle
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
															<RatingView
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
														<div className="affx-rating-box affx-rating-number">
															<span className="num">
																{numberRatings}
															</span>
															<span className="label">
																{ratingContent}
															</span>
														</div>
													)}
											</div>
											{edPricing && (
												<div
													className={`affx-sp-price pricing-align-${productPricingAlign}`}
												>
													<div className="affx-sp-marked-price">
														{productSalePrice}
													</div>
													<div className="affx-sp-sale-price">
														<del>
															{productPrice}
														</del>
													</div>
												</div>
											)}
											<div
												className={`affx-single-product-content`}
											>
												{productContentType ===
													"list" && (
														<RichText.Content
															tagName={
																ContentListType ==
																	"unordered"
																	? "ul"
																	: "ol"
															}
															multiline="li"
															className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
															value={
																productContentList
															}
														/>
													)}
												{productContentType ===
													"paragraph" && (
														<RichText.Content
															tagName="p"
															value={productContent}
															className="affiliatex-content"
														/>
													)}
											</div>
											{edButton == true && (
												<div className="button-wrapper">
													<InnerBlocks.Content />
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
														<div
															className={`affx-sp-ribbon-title`}
														>
															<RichText.Content
																value={
																	ribbonText
																}
															/>
														</div>
													</div>
												)}
												{edTitle == true && (
													<TagTitle
														className={`affx-single-product-title`}
													>
														<RichText.Content
															value={productTitle}
														/>
													</TagTitle>
												)}
												{edSubtitle == true && (
													<TagSubtitle
														className={`affx-single-product-subtitle`}
													>
														<RichText.Content
															placeholder={__(
																"Enter Product Sub Title",
																"affiliatex"
															)}
															value={
																productSubTitle
															}
														/>
													</TagSubtitle>
												)}
											</div>
											{edRatings == true &&
												PricingType === "picture" && (
													<div
														className={`affx-sp-pricing-pic rating-align-${productStarRatingAlign}`}
													>
														<RatingView
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
												PricingType === "number" && (
													<div className="affx-rating-box affx-rating-number">
														<span className="num">
															{numberRatings}
														</span>
														<span className="label">
															{ratingContent}
														</span>
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
											</div>
										)}
									</>
								)}
								{layoutClass == " product-layout-2" &&
									edPricing == true && (
										<div
											className={`affx-sp-price pricing-align-${productPricingAlign}`}
										>
											<div className="affx-sp-marked-price">
												{productSalePrice}
											</div>
											<div className="affx-sp-sale-price">
												<del>{productPrice}</del>
											</div>
										</div>
									)}
								{layoutClass == " product-layout-2" &&
									edContent == true && (
										<div
											className={`affx-single-product-content`}
										>
											{productContentType === "list" && (
												<RichText.Content
													tagName={
														ContentListType ==
															"unordered"
															? "ul"
															: "ol"
													}
													multiline="li"
													className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
													value={productContentList}
												/>
											)}
											{productContentType ===
												"paragraph" && (
													<RichText.Content
														tagName="p"
														value={productContent}
														className="affiliatex-content"
													/>
												)}
										</div>
									)}
								{layoutClass == " product-layout-2" &&
									edContent == true && (
										<>
											{edButton == true && (
												<div className="button-wrapper">
													<InnerBlocks.Content />
												</div>
											)}
										</>
									)}
								{layoutClass == " product-layout-3" &&
									edContent == true && (
										<>
											{edButton == true && (
												<div className="button-wrapper">
													<InnerBlocks.Content />
												</div>
											)}
										</>
									)}
							</div>
						</div>
					</div>
				)}
			{applyFilters("affx_save_single_product_layouts", null, attributes)}
		</div>
	);
};
