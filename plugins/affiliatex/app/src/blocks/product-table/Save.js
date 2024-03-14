import { __ } from "@wordpress/i18n";
import { RatingView } from "react-simple-star-rating";

export default ({ attributes, className }) => {
	const { RichText } = wp.blockEditor;
	const {
		block_id,
		productTable,
		layoutStyle,
		imageColTitle,
		productColTitle,
		featuresColTitle,
		ratingColTitle,
		priceColTitle,
		edImage,
		edRibbon,
		edProductName,
		edRating,
		edPrice,
		edCounter,
		edButton1,
		edButton1Icon,
		button1Icon,
		button1IconAlign,
		edButton2,
		edButton2Icon,
		button2Icon,
		button2IconAlign,
		starColor,
		starInactiveColor,
		productContentType,
		contentListType,
		productIconList,
	} = attributes;

	const getReviewColumns = () => {
		let tempProductTable = [...productTable];
		let showReviewColumn = [];

		tempProductTable
			.map((item) => item.rating)
			.forEach((item) => {
				if (item.length > 0) {
					showReviewColumn.push(item);
				}
			});

		return showReviewColumn;
	};

	return (
		<div
			id={`affiliatex-pdt-table-style-${block_id}`}
			className={`${className}`}
		>
			<div
				className={`affx-pdt-table-container--free affx-block-admin ${
					layoutStyle && layoutStyle == "layoutThree"
						? "layout-3"
						: ""
				}`}
			>
				<div className="affx-pdt-table-wrapper">
					{layoutStyle &&
						(layoutStyle == "layoutOne" ||
							layoutStyle == "layoutTwo") && (
							<table className="affx-pdt-table">
								<thead>
									<tr>
										{edImage && (
											<td className="affx-img-col">
												<span>
													<RichText.Content
														value={imageColTitle}
													/>
												</span>
											</td>
										)}
										<td>
											<span>
												<RichText.Content
													value={productColTitle}
												/>
											</span>
										</td>
										{layoutStyle == "layoutOne" && (
											<td>
												<span>
													<RichText.Content
														value={featuresColTitle}
													/>
												</span>
											</td>
										)}
										{layoutStyle == "layoutTwo" &&
											edRating &&
											getReviewColumns().length > 0 && (
												<td className="affx-rating-col">
													<span>
														<RichText.Content
															value={
																ratingColTitle
															}
														/>
													</span>
												</td>
											)}
										<td className="affx-price-col">
											<span>
												<RichText.Content
													value={priceColTitle}
												/>
											</span>
										</td>
									</tr>
								</thead>
								<tbody>
									{productTable?.map((product, index) => (
										<tr key={index}>
											{edImage && (
												<td className="affx-img-col">
													<div className="affx-pdt-img-container">
														{edRibbon &&
															product.ribbon && (
																<span className="affx-pdt-ribbon affx-ribbon-2">
																	<RichText.Content
																		value={
																			product.ribbon
																		}
																	/>
																</span>
															)}
														{edCounter && (
															<span className="affx-pdt-counter">
																{index + 1}
															</span>
														)}
														<div className="affx-pdt-img-wrapper">
															<img
																src={
																	product.imageUrl
																}
																alt={
																	product.imageAlt
																}
															/>
														</div>
														{layoutStyle ==
															"layoutOne" &&
															edRating &&
															product.rating && (
																<span className="star-rating-single-wrap">
																	<RichText.Content
																		value={product.rating.toString()}
																	/>
																</span>
															)}
													</div>
												</td>
											)}
											<td>
												{edProductName && (
													<h5 className="affx-pdt-name">
														<RichText.Content
															value={product.name}
														/>
													</h5>
												)}
												{layoutStyle == "layoutTwo" && (
													<div className="affx-pdt-desc">
														{productContentType ===
															"list" && (
															<RichText.Content
																tagName={
																	contentListType ==
																	"unordered"
																		? "ul"
																		: "ol"
																}
																multiline="li"
																className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
																value={
																	product.featuresList
																}
															/>
														)}
														{productContentType ===
															"paragraph" && (
															<RichText.Content
																tagName="p"
																value={
																	product.features
																}
																className="affiliatex-content"
															/>
														)}
													</div>
												)}
											</td>
											{layoutStyle == "layoutOne" && (
												<td>
													{productContentType ===
														"list" && (
														<RichText.Content
															tagName={
																contentListType ==
																"unordered"
																	? "ul"
																	: "ol"
															}
															multiline="li"
															className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
															value={
																product.featuresList
															}
														/>
													)}
													{productContentType ===
														"paragraph" && (
														<RichText.Content
															tagName="p"
															value={
																product.features
															}
															className="affiliatex-content"
														/>
													)}
												</td>
											)}
											{layoutStyle == "layoutTwo" &&
												edRating &&
												product.rating &&
												getReviewColumns().length >
													0 && (
													<td className="affx-rating-col">
														<div className="affx-circle-progress-container">
															<span
																className="circle-wrap"
																style={{
																	"--data-deg":
																		"rotate(" +
																		180 *
																			(((product.rating.toString() /
																				10) *
																				100) /
																				100) +
																		"deg)",
																}}
															>
																<span className="circle-mask full">
																	<span className="fill"></span>
																</span>
																<span className="circle-mask">
																	<span className="fill"></span>
																</span>
															</span>
															<span className="affx-circle-inside">
																<RichText.Content
																	value={product.rating.toString()}
																/>
															</span>
														</div>
													</td>
												)}
											{layoutStyle == "layoutTwo" &&
												edRating &&
												getReviewColumns().length > 0 &&
												product.rating == false && (
													<td></td>
												)}

											<td className="affx-price-col">
												{edPrice && (
													<div className="affx-pdt-price-wrap">
														{product.offerPrice && (
															<span className="affx-pdt-offer-price">
																<RichText.Content
																	value={
																		product.offerPrice
																	}
																/>
															</span>
														)}
														{product.regularPrice && (
															<del className="affx-pdt-reg-price">
																<RichText.Content
																	value={
																		product.regularPrice
																	}
																/>
															</del>
														)}
													</div>
												)}
												{(edButton1 || edButton2) && (
													<div className="affx-btn-wrapper">
														{edButton1 &&
															product.button1 && (
																<div className="affx-btn-inner">
																	<a
																		href={
																			product.button1URL
																		}
																		className={`affiliatex-button primary ${
																			edButton1Icon &&
																			"icon-btn icon-" +
																				button1IconAlign
																		}`}
																		rel={__(
																			"noopener",
																			"affiliatex"
																		)}
																		{...(product.btn1RelNoFollow
																			? {
																					rel:
																						"noopener nofollow",
																			  }
																			: "")}
																		{...(product.btn1RelSponsored
																			? {
																					rel:
																						"noopener sponsored",
																			  }
																			: "")}
																		{...(product.btn1RelNoFollow &&
																		product.btn1RelSponsored
																			? {
																					rel:
																						"noopener nofollow sponsored",
																			  }
																			: "")}
																		{...(product.btn1OpenInNewTab
																			? {
																					target:
																						"_blank",
																			  }
																			: "")}
																		{...(product.btn1Download
																			? {
																					download:
																						"affiliatex",
																			  }
																			: "")}
																	>
																		{edButton1Icon ==
																			true &&
																			button1IconAlign &&
																			button1IconAlign ==
																				"left" && (
																				<i
																					className={
																						`button-icon ` +
																						button1Icon.value
																					}
																				></i>
																			)}
																		<RichText.Content
																			value={
																				product.button1
																			}
																		/>
																		{edButton1Icon ==
																			true &&
																			button1IconAlign &&
																			button1IconAlign ==
																				"right" && (
																				<i
																					className={
																						`button-icon ` +
																						button1Icon.value
																					}
																				></i>
																			)}
																	</a>
																</div>
															)}
														{edButton2 &&
															product.button2 && (
																<div className="affx-btn-inner">
																	<a
																		href={
																			product.button2URL
																		}
																		className={`affiliatex-button secondary ${
																			edButton2Icon &&
																			"icon-btn icon-" +
																				button2IconAlign
																		}`}
																		rel={__(
																			"noopener",
																			"affiliatex"
																		)}
																		{...(product.btn2RelNoFollow
																			? {
																					rel:
																						"noopener nofollow",
																			  }
																			: "")}
																		{...(product.btn2RelSponsored
																			? {
																					rel:
																						"noopener sponsored",
																			  }
																			: "")}
																		{...(product.btn2RelNoFollow &&
																		product.btn2RelSponsored
																			? {
																					rel:
																						"noopener nofollow sponsored",
																			  }
																			: "")}
																		{...(product.btn2OpenInNewTab
																			? {
																					target:
																						"_blank",
																			  }
																			: "")}
																		{...(product.btn2Download
																			? {
																					download:
																						"affiliatex",
																			  }
																			: "")}
																	>
																		{edButton2Icon ==
																			true &&
																			button2IconAlign &&
																			button2IconAlign ==
																				"left" && (
																				<i
																					className={
																						`button-icon ` +
																						button2Icon.value
																					}
																				></i>
																			)}
																		<RichText.Content
																			value={
																				product.button2
																			}
																		/>
																		{edButton2Icon ==
																			true &&
																			button2IconAlign &&
																			button2IconAlign ==
																				"right" && (
																				<i
																					className={
																						`button-icon ` +
																						button2Icon.value
																					}
																				></i>
																			)}
																	</a>
																</div>
															)}
													</div>
												)}
											</td>
										</tr>
									))}
								</tbody>
							</table>
						)}

					{layoutStyle && layoutStyle == "layoutThree" && (
						<>
							{productTable?.map((product, index) => (
								<div
									className="affx-pdt-table-single"
									key={index}
								>
									{edImage && (
										<div className="affx-pdt-img-wrapper">
											<img
												src={product.imageUrl}
												alt={product.imageAlt}
											/>
										</div>
									)}
									<div className="affx-pdt-content-wrap">
										<div className="affx-content-left">
											{edCounter && (
												<span className="affx-pdt-counter">
													{index + 1}
												</span>
											)}
											{edRibbon && product.ribbon && (
												<span className="affx-pdt-ribbon">
													<RichText.Content
														value={product.ribbon}
													/>
												</span>
											)}
											{edProductName && (
												<h5 className="affx-pdt-name">
													<RichText.Content
														value={product.name}
													/>
												</h5>
											)}
											{edRating && product.rating && (
												<div className="affx-rating-wrap">
													<RatingView
														ratingValue={parseInt(
															product.rating
														)}
														fillColor={starColor}
														emptyColor={
															starInactiveColor
														}
														className="rating-stars"
													/>
												</div>
											)}
											{edPrice && (
												<div className="affx-pdt-price-wrap">
													{product.offerPrice && (
														<span className="affx-pdt-offer-price">
															<RichText.Content
																value={
																	product.offerPrice
																}
															/>
														</span>
													)}
													{product.regularPrice && (
														<del className="affx-pdt-reg-price">
															<RichText.Content
																value={
																	product.regularPrice
																}
															/>
														</del>
													)}
												</div>
											)}
											<div className="affx-pdt-desc">
												{productContentType ===
													"list" && (
													<RichText.Content
														tagName={
															contentListType ==
															"unordered"
																? "ul"
																: "ol"
														}
														multiline="li"
														className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
														value={
															product.featuresList
														}
													/>
												)}
												{productContentType ===
													"paragraph" && (
													<RichText.Content
														tagName="p"
														value={product.features}
														className="affiliatex-content"
													/>
												)}
											</div>
										</div>
										{(edButton1 || edButton2) && (
											<div className="affx-pdt-button-wrap">
												<div className="affx-btn-wrapper">
													{edButton1 &&
														product.button1 && (
															<div className="affx-btn-inner">
																<a
																	href={
																		product.button1URL
																	}
																	className={`affiliatex-button primary ${
																		edButton1Icon &&
																		"icon-btn icon-" +
																			button1IconAlign
																	}`}
																	rel={__(
																		"noopener",
																		"affiliatex"
																	)}
																	{...(product.btn1RelNoFollow
																		? {
																				rel:
																					"noopener nofollow",
																		  }
																		: "")}
																	{...(product.btn1RelSponsored
																		? {
																				rel:
																					"noopener sponsored",
																		  }
																		: "")}
																	{...(product.btn1RelNoFollow &&
																	product.btn1RelSponsored
																		? {
																				rel:
																					"noopener nofollow sponsored",
																		  }
																		: "")}
																	{...(product.btn1OpenInNewTab
																		? {
																				target:
																					"_blank",
																		  }
																		: "")}
																	{...(product.btn1Download
																		? {
																				download:
																					"affiliatex",
																		  }
																		: "")}
																>
																	{edButton1Icon ==
																		true &&
																		button1IconAlign &&
																		button1IconAlign ==
																			"left" && (
																			<i
																				className={
																					`button-icon ` +
																					button1Icon.value
																				}
																			></i>
																		)}
																	<RichText.Content
																		value={
																			product.button1
																		}
																	/>
																	{edButton1Icon ==
																		true &&
																		button1IconAlign &&
																		button1IconAlign ==
																			"right" && (
																			<i
																				className={
																					`button-icon ` +
																					button1Icon.value
																				}
																			></i>
																		)}
																</a>
															</div>
														)}
													{edButton2 &&
														product.button2 && (
															<div className="affx-btn-inner">
																<a
																	href={
																		product.button2URL
																	}
																	className={`affiliatex-button secondary ${
																		edButton2Icon &&
																		"icon-btn icon-" +
																			button2IconAlign
																	}`}
																	rel={__(
																		"noopener",
																		"affiliatex"
																	)}
																	{...(product.btn2RelNoFollow
																		? {
																				rel:
																					"noopener nofollow",
																		  }
																		: "")}
																	{...(product.btn2RelSponsored
																		? {
																				rel:
																					"noopener sponsored",
																		  }
																		: "")}
																	{...(product.btn2RelNoFollow &&
																	product.btn2RelSponsored
																		? {
																				rel:
																					"noopener nofollow sponsored",
																		  }
																		: "")}
																	{...(product.btn2OpenInNewTab
																		? {
																				target:
																					"_blank",
																		  }
																		: "")}
																	{...(product.btn2Download
																		? {
																				download:
																					"affiliatex",
																		  }
																		: "")}
																>
																	{edButton2Icon ==
																		true &&
																		button2IconAlign &&
																		button2IconAlign ==
																			"left" && (
																			<i
																				className={
																					`button-icon ` +
																					button2Icon.value
																				}
																			></i>
																		)}
																	<RichText.Content
																		value={
																			product.button2
																		}
																	/>
																	{edButton2Icon ==
																		true &&
																		button2IconAlign &&
																		button2IconAlign ==
																			"right" && (
																			<i
																				className={
																					`button-icon ` +
																					button2Icon.value
																				}
																			></i>
																		)}
																</a>
															</div>
														)}
												</div>
											</div>
										)}
									</div>
								</div>
							))}
						</>
					)}
				</div>
			</div>
		</div>
	);
};
