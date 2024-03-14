import { __ } from "@wordpress/i18n";
import BlockInspector from "./Inspector";
import blocks_styling from "./styling";
import { useEffect, useState } from "@wordpress/element";
import { Rating } from "react-simple-star-rating";
const { RichText, MediaUpload } = wp.blockEditor;
const { Button } = wp.components;
const { Fragment } = wp.element;
import WebfontLoader from "../ui-components/typography/fontloader";
import ButtonPopOver from "../ui-components/ButtonPopOver";

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
			"affiliatex-product-comparison-blocks-style-" + clientId
		);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-product-comparison-blocks-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-product-comparison-blocks-style",
				clientId
			);
		}
	}, [attributes]);
	const {
		productComparisonTable,
		comparisonSpecs,
		itemCount,
		specsCount,
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
		titleTypography,
		ribbonTypography,
		priceTypography,
		buttonTypography,
		contentTypography,
	} = attributes;

	const [selectedButton, setSelectedButton] = useState(null);

	const handleProductColumn = (productComparisonTable) => {
		let itemCount = 0;
		let tempProductComparisonTable = [...productComparisonTable];
		tempProductComparisonTable = [
			...productComparisonTable,
			{
				title: "",
				imageUrl: AffiliateX.pluginUrl + "app/src/images/fallback.jpg",
				ribbonText: "",
				imageId: "",
				imageAlt: "",
				price: "$59.00",
				rating: 4,
				button: "Buy Now",
				buttonURL: "",
				btnRelNoFollow: false,
				btnRelSponsored: false,
				btnOpenInNewTab: false,
				btnDownload: false,
			},
		];
		setAttributes({ productComparisonTable: tempProductComparisonTable });
		itemCount = productComparisonTable.length;
		setAttributes({ itemCount });
	};

	const handleRemoveProductColumn = (index) => {
		let tempProductComparisonTable = [...productComparisonTable];
		tempProductComparisonTable.splice(index, 1);
		setAttributes({
			productComparisonTable: tempProductComparisonTable,
			itemCount: specsCount - 1,
		});
	};

	const handleRemoveProductSpecs = (index) => {
		let tempComparisonSpecs = [...comparisonSpecs];
		tempComparisonSpecs.splice(index, 1);
		setAttributes({
			comparisonSpecs: tempComparisonSpecs,
			itemCount: itemCount - 1,
		});
	};

	const handleProductSpecs = (comparisonSpecs) => {
		let specsCount = 0;
		let tempComparisonSpecs = [...comparisonSpecs];
		tempComparisonSpecs = [
			...comparisonSpecs,
			{
				title: "",
				specs: [],
			},
		];
		setAttributes({ comparisonSpecs: tempComparisonSpecs });
		specsCount = comparisonSpecs.length;
		setAttributes({ specsCount });
	};

	const onChangeComparisonSpecs = (value, index, countIndex, item) => {
		let tempComparisonSpecs = [...comparisonSpecs];
		if (item === "specs") {
			tempComparisonSpecs[index] = {
				...tempComparisonSpecs[index],
				specs: {
					...tempComparisonSpecs[index][`${item}`],
					[`${countIndex}`]: value,
				},
			};
		} else {
			tempComparisonSpecs[index] = {
				...tempComparisonSpecs[index],
				[`${item}`]: value,
			};
		}
		setAttributes({ comparisonSpecs: tempComparisonSpecs });
	};

	const onChangeProductComparisonTable = (value, index, item) => {
		let tempProductComparisonTable = [...productComparisonTable];
		tempProductComparisonTable[index] = {
			...tempProductComparisonTable[index],
			[`${item}`]: value,
		};
		setAttributes({ productComparisonTable: tempProductComparisonTable });
	};

	const onSelectImage = (img, index) => {
		let tempProductComparisonTable = [...productComparisonTable];
		tempProductComparisonTable[index] = {
			...tempProductComparisonTable[index],
			imageUrl: img.url,
			imageId: img.id,
			imageAlt: img.alt,
		};
		setAttributes({ productComparisonTable: tempProductComparisonTable });
	};
	const onReplaceImage = (replace, index) => {
		let tempProductComparisonTable = [...productComparisonTable];
		tempProductComparisonTable[index] = {
			...tempProductComparisonTable[index],
			imageUrl: replace.url,
			imageId: replace.id,
			imageAlt: replace.alt,
		};
		setAttributes({ productComparisonTable: tempProductComparisonTable });
	};
	const onRemoveImage = (index) => {
		let tempProductComparisonTable = [...productComparisonTable];
		tempProductComparisonTable[index] = {
			...tempProductComparisonTable[index],
			imageUrl: AffiliateXPro.pluginUrl + "app/src/images/fallback.jpg",
			imageId: null,
			imageAlt: null,
		};
		setAttributes({ productComparisonTable: tempProductComparisonTable });
	};

	let productComparisionTitleTypography,
		productComparisionRibbonTypography,
		productComparisionPriceTypography,
		productComparisionButtonTypography,
		productComparisionContentTypography;

	if ("Default" !== titleTypography.family) {
		const productComparisionBlockTitleTypoConfig = {
			google: {
				families: [
					titleTypography.family +
						(titleTypography.variation
							? ":" + titleTypography.variation
							: ""),
				],
			},
		};

		productComparisionTitleTypography = (
			<WebfontLoader
				config={productComparisionBlockTitleTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== ribbonTypography.family) {
		const productComparisionBlockRibbonTypoConfig = {
			google: {
				families: [
					ribbonTypography.family +
						(ribbonTypography.variation
							? ":" + ribbonTypography.variation
							: ""),
				],
			},
		};

		productComparisionRibbonTypography = (
			<WebfontLoader
				config={productComparisionBlockRibbonTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== priceTypography.family) {
		const productComparisionBlockPriceTypoConfig = {
			google: {
				families: [
					priceTypography.family +
						(priceTypography.variation
							? ":" + priceTypography.variation
							: ""),
				],
			},
		};

		productComparisionPriceTypography = (
			<WebfontLoader
				config={productComparisionBlockPriceTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== buttonTypography.family) {
		const productComparisionBlockButtonTypoConfig = {
			google: {
				families: [
					buttonTypography.family +
						(buttonTypography.variation
							? ":" + buttonTypography.variation
							: ""),
				],
			},
		};

		productComparisionButtonTypography = (
			<WebfontLoader
				config={productComparisionBlockButtonTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== contentTypography.family) {
		const productComparisionBlockContentTypoConfig = {
			google: {
				families: [
					contentTypography.family +
						(contentTypography.variation
							? ":" + contentTypography.variation
							: ""),
				],
			},
		};

		productComparisionContentTypography = (
			<WebfontLoader
				config={productComparisionBlockContentTypoConfig}
			></WebfontLoader>
		);
	}

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-product-comparison-blocks-style-${clientId}`}
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
									{productComparisonTable?.map(
										(item, index) => (
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
												<button
													type="button"
													className="affx-specification-remove-btn"
													onClick={() => {
														handleRemoveProductColumn(
															index
														);
													}}
												>
													<svg
														xmlns="http://www.w3.org/2000/svg"
														width="13.549"
														height="18.065"
														viewBox="0 0 13.549 18.065"
													>
														<g
															transform="translate(-132.5 -10)"
															opacity="0.2"
														>
															<path
																d="M146.049,12.319h-3.764v-.94A1.378,1.378,0,0,0,140.906,10h-3.268a1.376,1.376,0,0,0-1.373,1.379v.94H132.5v.691h.992l1.11,13.676a1.378,1.378,0,0,0,1.379,1.379h6.633a1.378,1.378,0,0,0,1.379-1.379l1.1-13.676h.96Zm-9.1-.94a.691.691,0,0,1,.688-.691h3.27a.688.688,0,0,1,.688.691v.94H136.95ZM143.3,26.65v.037a.691.691,0,0,1-.688.691h-6.633a.688.688,0,0,1-.688-.691V26.65l-1.115-13.638H144.4Z"
																transform="translate(0 0)"
																fill="%23191919"
															/>
															<path
																d="M482.1,255h.723v12.466H482.1Z"
																transform="translate(-343.188 -241.83)"
																fill="%23191919"
															/>
															<path
																d="M600.009,255h-.756l-.553,12.466h.756Z"
																transform="translate(-457.688 -241.83)"
																fill="%23191919"
															/>
															<path
																d="M337.45,255h-.75l.553,12.466h.756Z"
																transform="translate(-200.472 -241.83)"
																fill="%23191919"
															/>
														</g>
													</svg>
												</button>
												{pcRibbon && (
													<RichText
														tagName="span"
														placeholder={__(
															"Ribbon Text",
															"affiliatex"
														)}
														className="affx-pc-ribbon"
														value={item.ribbonText}
														onChange={(
															newRibbonText
														) =>
															onChangeProductComparisonTable(
																newRibbonText,
																index,
																"ribbonText"
															)
														}
													/>
												)}
												<div className="affx-versus-product">
													{pcImage && (
														<div className="affx-versus-product-img">
															<img
																src={
																	item.imageUrl
																}
															/>
															{!item?.imageId ? (
																<MediaUpload
																	onSelect={(
																		img
																	) =>
																		onSelectImage(
																			img,
																			index
																		)
																	}
																	type="image"
																	value={
																		item.imageId
																	}
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
																			onSelect={(
																				replace
																			) =>
																				onReplaceImage(
																					replace,
																					index
																				)
																			}
																			type="image"
																			value={
																				item.imageId
																			}
																			render={({
																				open,
																			}) => (
																				<Button
																					className={
																						"affx-replace-btn"
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
																		className="remove-image"
																		onClick={() =>
																			onRemoveImage(
																				index
																			)
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
													<div className="affx-product-content">
														{pcTitle && (
															<div className="affx-product-title-wrap">
																<RichText
																	tagName="h2"
																	placeholder={__(
																		"Product Title",
																		"affiliatex"
																	)}
																	className={`affx-comparison-title`}
																	value={
																		item.title
																	}
																	onChange={(
																		newTitle
																	) =>
																		onChangeProductComparisonTable(
																			newTitle,
																			index,
																			"title"
																		)
																	}
																/>
															</div>
														)}
														{pcPrice && (
															<div className="affx-price-wrap">
																<span className="affx-price">
																	<RichText
																		placeholder="Price"
																		value={
																			item.price
																		}
																		onChange={(
																			newPrice
																		) =>
																			onChangeProductComparisonTable(
																				newPrice,
																				index,
																				"price"
																			)
																		}
																	/>
																</span>
															</div>
														)}
														{pcRating && (
															<div className="affx-rating-wrap">
																<Rating
																	onClick={(
																		rating
																	) =>
																		onChangeProductComparisonTable(
																			rating,
																			index,
																			"rating"
																		)
																	}
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
															{pcButton && (
																<span
																	className={`affiliatex-button affx-winner-button ${
																		pcButtonIcon &&
																		"icon-btn icon-" +
																			buttonIconAlign
																	}`}
																	onMouseEnter={() =>
																		setSelectedButton(
																			index
																		)
																	}
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
																	<RichText
																		placeholder={__(
																			"Check Price",
																			"affiliatex"
																		)}
																		value={
																			item.button
																		}
																		formattingControls={[
																			"bold",
																			"italic",
																			"underline",
																		]}
																		href={
																			item.buttonURL
																		}
																		onChange={(
																			newLabel
																		) =>
																			onChangeProductComparisonTable(
																				newLabel,
																				index,
																				"button"
																			)
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
																	{selectedButton ==
																		index && (
																		<ButtonPopOver
																			index={
																				index
																			}
																			buttonNumber=""
																			url={
																				item.buttonURL
																			}
																			opensInNewTab={
																				item.btnOpenInNewTab
																			}
																			relNoFollow={
																				item.btnRelNoFollow
																			}
																			relSponsored={
																				item.btnRelSponsored
																			}
																			download={
																				item.btnDownload
																			}
																			onChangeProductLinks={
																				onChangeProductComparisonTable
																			}
																			setSelectedButton={
																				setSelectedButton
																			}
																		/>
																	)}
																</span>
															)}
														</div>
													</div>
												</div>
											</th>
										)
									)}
									<th className="affx-product-col affx-add-col">
										<Button
											isDefault
											className="affx-add-specification-btn components_panel_btn"
											onClick={() =>
												handleProductColumn(
													productComparisonTable
												)
											}
										>
											<span className="screen-reader-text">
												{__(
													"Add Product",
													"affiliatex"
												)}
											</span>
											<svg
												xmlns="http://www.w3.org/2000/svg"
												viewBox="-2 -2 24 24"
												width="24"
												height="24"
												role="img"
												aria-hidden="true"
												focusable="false"
											>
												<path d="M10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6zM10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6z"></path>
											</svg>
										</Button>
									</th>
								</tr>
							</thead>
							<tbody>
								{comparisonSpecs?.map((item, index) => (
									<tr key={index}>
										{productComparisonTable?.map(
											(count, countIndex) =>
												countIndex != 0 ? (
													<td>
														<RichText
															placeholder="Specification"
															value={
																item.specs[
																	countIndex
																]
															}
															onChange={(
																newSpecs
															) =>
																onChangeComparisonSpecs(
																	newSpecs,
																	index,
																	countIndex,
																	"specs"
																)
															}
														/>
													</td>
												) : (
													<>
														<td className="data-label">
															<RichText
																placeholder="Title"
																value={
																	item.title
																}
																onChange={(
																	newTitle
																) =>
																	onChangeComparisonSpecs(
																		newTitle,
																		index,
																		countIndex,
																		"title"
																	)
																}
															/>
														</td>
														<td>
															<RichText
																placeholder="Specification"
																value={
																	item.specs[
																		countIndex
																	]
																}
																onChange={(
																	newSpecs
																) =>
																	onChangeComparisonSpecs(
																		newSpecs,
																		index,
																		countIndex,
																		"specs"
																	)
																}
															/>
														</td>
													</>
												)
										)}
										<td>
											<button
												type="button"
												className="affx-specification-remove-btn"
												onClick={() => {
													handleRemoveProductSpecs(
														index
													);
												}}
											>
												<svg
													xmlns="http://www.w3.org/2000/svg"
													width="13.549"
													height="18.065"
													viewBox="0 0 13.549 18.065"
												>
													<g
														transform="translate(-132.5 -10)"
														opacity="0.2"
													>
														<path
															d="M146.049,12.319h-3.764v-.94A1.378,1.378,0,0,0,140.906,10h-3.268a1.376,1.376,0,0,0-1.373,1.379v.94H132.5v.691h.992l1.11,13.676a1.378,1.378,0,0,0,1.379,1.379h6.633a1.378,1.378,0,0,0,1.379-1.379l1.1-13.676h.96Zm-9.1-.94a.691.691,0,0,1,.688-.691h3.27a.688.688,0,0,1,.688.691v.94H136.95ZM143.3,26.65v.037a.691.691,0,0,1-.688.691h-6.633a.688.688,0,0,1-.688-.691V26.65l-1.115-13.638H144.4Z"
															transform="translate(0 0)"
															fill="%23191919"
														/>
														<path
															d="M482.1,255h.723v12.466H482.1Z"
															transform="translate(-343.188 -241.83)"
															fill="%23191919"
														/>
														<path
															d="M600.009,255h-.756l-.553,12.466h.756Z"
															transform="translate(-457.688 -241.83)"
															fill="%23191919"
														/>
														<path
															d="M337.45,255h-.75l.553,12.466h.756Z"
															transform="translate(-200.472 -241.83)"
															fill="%23191919"
														/>
													</g>
												</svg>
											</button>
										</td>
									</tr>
								))}
								<tr>
									<td
										className="affx-product-col"
										colSpan={
											productComparisonTable.length + 2
										}
									>
										<Button
											isDefault
											className="affx-add-specification-btn components_panel_btn"
											onClick={() =>
												handleProductSpecs(
													comparisonSpecs
												)
											}
										>
											<span className="screen-reader-text">
												{__(
													"Add Product",
													"affiliatex"
												)}
											</span>
											<svg
												xmlns="http://www.w3.org/2000/svg"
												viewBox="-2 -2 24 24"
												width="24"
												height="24"
												role="img"
												aria-hidden="true"
												focusable="false"
											>
												<path d="M10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6zM10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6z"></path>
											</svg>
										</Button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			{productComparisionTitleTypography}
			{productComparisionRibbonTypography}
			{productComparisionPriceTypography}
			{productComparisionButtonTypography}
			{productComparisionContentTypography}
		</Fragment>
	);
};
