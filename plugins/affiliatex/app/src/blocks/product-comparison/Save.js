import { __ } from "@wordpress/i18n";
import { RatingView } from "react-simple-star-rating";

export default ({ attributes, className }) => {
	const { RichText } = wp.blockEditor;
	const {
		block_id,
		productComparisonTable,
		comparisonSpecs,
		pcRibbon,
		pcTitle,
		starColor,
		starInactiveColor,
		pcImage,
		pcRating,
		pcPrice,
		pcButton,
		pcButtonIcon,
		buttonIconAlign,
		buttonIcon,
	} = attributes;

	return (
		<div
			id={`affiliatex-product-comparison-blocks-style-${block_id}`}
			className={className}
		>
			<div className="affx-versus-block-container affx-product-comparison-block-container">
				<div className="affx-versus-table-wrap">
					<table className="affx-product-versus-table layout-1">
						<thead>
							<tr>
								<th
									className="data-label"
									style={{
										width:
											92 /
												(productComparisonTable.length +
													1) +
											"%",
									}}
								></th>
								{productComparisonTable?.map((item, index) => (
									<th
										className="affx-product-col"
										key={index}
										style={{
											width:
												92 /
													(productComparisonTable.length +
														1) +
												"%",
										}}
									>
										{pcRibbon && item.ribbonText != "" && (
											<RichText.Content
												tagName="span"
												className="affx-pc-ribbon"
												value={item.ribbonText}
											/>
										)}
										<div className="affx-versus-product">
											{pcImage && (
												<div className="affx-versus-product-img">
													<img
														src={item.imageUrl}
														alt={item.imageAlt}
													/>
												</div>
											)}
											<div className="affx-product-content">
												{pcTitle && (
													<div className="affx-product-title-wrap">
														<RichText.Content
															tagName="h2"
															className={`affx-comparison-title`}
															value={item.title}
														/>
													</div>
												)}
												{pcPrice && (
													<div className="affx-price-wrap">
														<span className="affx-price">
															<RichText.Content
																value={
																	item.price
																}
															/>
														</span>
													</div>
												)}
												{pcRating && (
													<div className="affx-rating-wrap">
														<RatingView
															ratingValue={
																item.rating
															}
															fillColor={
																starColor
															}
															emptyColor={
																starInactiveColor
															}
															className="rating-stars"
														/>
													</div>
												)}
												<div className="affx-btn-wrap">
													{pcButton && item.button && (
														<a
															href={
																item.buttonURL
															}
															className={`affiliatex-button affx-winner-button ${
																pcButtonIcon &&
																"icon-btn icon-" +
																	buttonIconAlign
															}`}
															rel={__(
																"noopener",
																"affiliatex"
															)}
															{...(item.btnRelNoFollow
																? {
																		rel:
																			"noopener nofollow",
																  }
																: "")}
															{...(item.btnRelSponsored
																? {
																		rel:
																			"noopener sponsored",
																  }
																: "")}
															{...(item.btnRelNoFollow &&
															item.btnRelSponsored
																? {
																		rel:
																			"noopener nofollow sponsored",
																  }
																: "")}
															{...(item.btnOpenInNewTab
																? {
																		target:
																			"_blank",
																  }
																: "")}
															{...(item.btnDownload
																? {
																		download:
																			"affiliatex",
																  }
																: "")}
														>
															{pcButtonIcon ==
																true &&
																buttonIconAlign &&
																buttonIconAlign ==
																	"left" && (
																	<i
																		className={
																			`button-icon ` +
																			buttonIcon.value
																		}
																	></i>
																)}
															<RichText.Content
																value={
																	item.button
																}
															/>
															{pcButtonIcon ==
																true &&
																buttonIconAlign &&
																buttonIconAlign ==
																	"right" && (
																	<i
																		className={
																			`button-icon ` +
																			buttonIcon.value
																		}
																	></i>
																)}
														</a>
													)}
												</div>
											</div>
										</div>
									</th>
								))}
							</tr>
						</thead>
						<tbody>
							{comparisonSpecs?.map((item, index) => (
								<tr key={index}>
									{productComparisonTable?.map(
										(count, countIndex) =>
											countIndex != 0 ? (
												<td>
													<RichText.Content
														value={
															item.specs[
																countIndex
															]
														}
													/>
												</td>
											) : (
												<>
													<td className="data-label">
														<RichText.Content
															value={item.title}
														/>
													</td>
													<td>
														<RichText.Content
															value={
																item.specs[
																	countIndex
																]
															}
														/>
													</td>
												</>
											)
									)}
								</tr>
							))}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	);
};
