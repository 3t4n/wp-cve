import { __ } from "@wordpress/i18n";
import AddProductRow from "./AddProductRow";
import RemoveProductRow from "./RemoveProductRow";
import ButtonPopOver from "../../ui-components/ButtonPopOver";
import { RatingNumber, SingleRate } from "../../ui-components/ScoreRating";

const { Button } = wp.components;
const { RichText, MediaUpload } = wp.blockEditor;

const LayoutOneTwo = (props) => {
	const {
		attributes,
		setAttributes,
		handleChangeIndex,
		onSelectImage,
		onReplaceImage,
		onRemoveImage,
		onChangeProductDetails,
		setSelectedButton,
		handleRemoveProductRow,
		handleProductRow,
		selectedButton,
	} = props;

	const {
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
		productContentType,
		contentListType,
		productIconList,
		isSelected,
	} = attributes;

	return (
		<table className="affx-pdt-table">
			<thead>
				<tr>
					{edImage && (
						<td className="affx-img-col">
							<RichText
								tagName="span"
								placeholder="Image"
								value={imageColTitle}
								onChange={(newTitle) =>
									setAttributes({
										imageColTitle: newTitle,
									})
								}
							/>
						</td>
					)}
					<td>
						<RichText
							tagName="span"
							placeholder="Product Name"
							value={productColTitle}
							onChange={(newTitle) =>
								setAttributes({
									productColTitle: newTitle,
								})
							}
						/>
					</td>
					{layoutStyle == "layoutOne" && (
						<td>
							<RichText
								tagName="span"
								placeholder="Features"
								value={featuresColTitle}
								onChange={(newTitle) =>
									setAttributes({
										featuresColTitle: newTitle,
									})
								}
							/>
						</td>
					)}
					{layoutStyle == "layoutTwo" && edRating && (
						<td>
							<RichText
								tagName="span"
								placeholder="Rating"
								value={ratingColTitle}
								onChange={(newTitle) =>
									setAttributes({
										ratingColTitle: newTitle,
									})
								}
								className="affx-rating-col"
							/>
						</td>
					)}
					<td className="affx-price-col">
						<RichText
							tagName="span"
							placeholder="Price"
							value={priceColTitle}
							onChange={(newTitle) =>
								setAttributes({
									priceColTitle: newTitle,
								})
							}
						/>
					</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				{productTable?.map((product, index) => (
					<tr key={index} className="affx-toolbar-row">
						{edImage && (
							<td className="affx-img-col">
								<div className="affx-pdt-img-container">
									{edRibbon && (
										<RichText
											tagName="span"
											className="affx-pdt-ribbon affx-ribbon-2"
											placeholder="Ribbon text"
											value={product.ribbon}
											onChange={(newRibbon) =>
												onChangeProductDetails(
													newRibbon,
													index,
													"ribbon"
												)
											}
										/>
									)}
									{edCounter && (
										<span className="affx-pdt-counter">
											{index + 1}
										</span>
									)}
									<div className="affx-pdt-img-wrapper">
										<img src={product.imageUrl} />
										{!product?.imageId ? (
											<MediaUpload
												onSelect={(img) =>
													onSelectImage(img, index)
												}
												type="image"
												value={product.imageId}
												render={({ open }) => (
													<Button
														className={
															"affx-upload-btn"
														}
														onClick={open}
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
														onSelect={(replace) =>
															onReplaceImage(
																replace,
																index
															)
														}
														type="image"
														value={product.imageId}
														render={({ open }) => (
															<Button
																className={
																	"affx-replace-btn"
																}
																onClick={open}
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
														onRemoveImage(index)
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
									{layoutStyle == "layoutOne" && edRating && (
										<SingleRate
											value={product.rating}
											onChange={(newRating) =>
												onChangeProductDetails(
													newRating,
													index,
													"rating"
												)
											}
										/>
									)}
								</div>
							</td>
						)}
						<td>
							{edProductName && (
								<RichText
									tagName="h5"
									className="affx-pdt-name"
									placeholder="Product Name"
									value={product.name}
									onChange={(newName) =>
										onChangeProductDetails(
											newName,
											index,
											"name"
										)
									}
								/>
							)}
							{layoutStyle == "layoutTwo" && (
								<div className="affx-pdt-desc">
									{productContentType === "list" && (
										<RichText
											tagName={
												contentListType == "unordered"
													? "ul"
													: "ol"
											}
											multiline="li"
											className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
											placeholder={__(
												"Enter Description",
												"affiliatex"
											)}
											value={product.featuresList}
											onChange={(newFeatures) =>
												onChangeProductDetails(
													newFeatures,
													index,
													"featuresList"
												)
											}
										/>
									)}
									{productContentType === "paragraph" && (
										<RichText
											tagName="p"
											value={product.features}
											className="affiliatex-content"
											placeholder={__(
												"Enter Description",
												"affiliatex"
											)}
											onChange={(newFeatures) =>
												onChangeProductDetails(
													newFeatures,
													index,
													"features"
												)
											}
										/>
									)}
								</div>
							)}
						</td>
						{layoutStyle == "layoutOne" && (
							<td>
								{productContentType === "list" && (
									<RichText
										tagName={
											contentListType == "unordered"
												? "ul"
												: "ol"
										}
										multiline="li"
										className={`affx-unordered-list affiliatex-icon affiliatex-icon-${productIconList.name}`}
										placeholder={__(
											"Enter Description",
											"affiliatex"
										)}
										value={product.featuresList}
										onChange={(newFeatures) =>
											onChangeProductDetails(
												newFeatures,
												index,
												"featuresList"
											)
										}
									/>
								)}
								{productContentType === "paragraph" && (
									<RichText
										tagName="p"
										value={product.features}
										className="affiliatex-content"
										placeholder={__(
											"Enter Description",
											"affiliatex"
										)}
										onChange={(newFeatures) =>
											onChangeProductDetails(
												newFeatures,
												index,
												"features"
											)
										}
									/>
								)}
							</td>
						)}
						{layoutStyle == "layoutTwo" && edRating && (
							<td className="affx-rating-col">
								<RatingNumber
									value={product.rating.toString()}
									onChange={(newRating) =>
										onChangeProductDetails(
											newRating,
											index,
											"rating"
										)
									}
									type="circle"
								/>
							</td>
						)}
						<td className="affx-price-col">
							{edPrice && (
								<div className="affx-pdt-price-wrap">
									<RichText
										tagName="span"
										className="affx-pdt-offer-price"
										placeholder="$00"
										value={product.offerPrice}
										onChange={(newPrice) =>
											onChangeProductDetails(
												newPrice,
												index,
												"offerPrice"
											)
										}
									/>
									<RichText
										tagName="del"
										className="affx-pdt-reg-price"
										placeholder="$00"
										value={product.regularPrice}
										onChange={(newPrice) =>
											onChangeProductDetails(
												newPrice,
												index,
												"regularPrice"
											)
										}
									/>
								</div>
							)}
							{(edButton1 || edButton2) && (
								<div className="affx-btn-wrapper">
									{edButton1 && (
										<div className="affx-btn-inner">
											<span
												className={`affiliatex-button primary ${
													edButton1Icon &&
													"icon-btn icon-" +
														button1IconAlign
												}`}
												onMouseEnter={() =>
													setSelectedButton(
														`${index}-first-button`
													)
												}
											>
												{edButton1Icon == true &&
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
												<RichText
													placeholder={__(
														"Check Price",
														"affiliatex"
													)}
													value={product.button1}
													formattingControls={[
														"bold",
														"italic",
														"underline",
													]}
													href={product.button1URL}
													onChange={(newLabel) =>
														onChangeProductDetails(
															newLabel,
															index,
															"button1"
														)
													}
												/>
												{edButton1Icon == true &&
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
												{selectedButton ==
													`${index}-first-button` && (
													<ButtonPopOver
														index={index}
														buttonNumber={1}
														url={product.button1URL}
														opensInNewTab={
															product.btn1OpenInNewTab
														}
														relNoFollow={
															product.btn1RelNoFollow
														}
														relSponsored={
															product.btn1RelSponsored
														}
														download={
															product.btn1Download
														}
														onChangeProductLinks={
															onChangeProductDetails
														}
														setSelectedButton={
															setSelectedButton
														}
													/>
												)}
											</span>
										</div>
									)}
									{edButton2 && (
										<div className="affx-btn-inner">
											<span
												className={`affiliatex-button secondary ${
													edButton2Icon &&
													"icon-btn icon-" +
														button2IconAlign
												}`}
												onMouseEnter={() =>
													setSelectedButton(
														`${index}-second-button`
													)
												}
											>
												{edButton2Icon == true &&
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
												<RichText
													placeholder={__(
														"Check Price",
														"affiliatex"
													)}
													value={product.button2}
													formattingControls={[
														"bold",
														"italic",
														"underline",
													]}
													href={product.button2URL}
													onChange={(newLabel) =>
														onChangeProductDetails(
															newLabel,
															index,
															"button2"
														)
													}
												/>
												{edButton2Icon == true &&
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
												{selectedButton ==
													`${index}-second-button` && (
													<ButtonPopOver
														index={index}
														buttonNumber={2}
														url={product.button2URL}
														opensInNewTab={
															product.btn2OpenInNewTab
														}
														relNoFollow={
															product.btn2RelNoFollow
														}
														relSponsored={
															product.btn2RelSponsored
														}
														download={
															product.btn2Download
														}
														onChangeProductLinks={
															onChangeProductDetails
														}
														setSelectedButton={
															setSelectedButton
														}
													/>
												)}
											</span>
										</div>
									)}
								</div>
							)}
						</td>
						{index > 0 ? (
							<td>
								<RemoveProductRow
									index={index}
									handleRemoveProductRow={
										handleRemoveProductRow
									}
								/>
								<div className="affx-toolbar">
									<button
										type="button"
										onClick={() => {
											handleChangeIndex(index, "up");
										}}
									>
										<svg
											width="14"
											height="8"
											viewBox="0 0 14 8"
											fill="none"
											xmlns="http://www.w3.org/2000/svg"
											stroke="currentColor"
										>
											<path
												d="M13 7L7 1L1 7"
												strokeWidth="2"
												strokeLinecap="round"
												strokeLinejoin="round"
											/>
										</svg>
									</button>

									<button
										type="button"
										onClick={() => {
											handleChangeIndex(index, "down");
										}}
										disabled={
											index === productTable.length - 1
												? true
												: false
										}
									>
										<svg
											width="14"
											height="8"
											viewBox="0 0 14 8"
											fill="none"
											xmlns="http://www.w3.org/2000/svg"
											stroke="currentColor"
										>
											<path
												d="M1 0.999999L7 7L13 1"
												strokeWidth="2"
												strokeLinecap="round"
												strokeLinejoin="round"
											/>
										</svg>
									</button>
								</div>
							</td>
						) : (
							<td>
								<div className="affx-toolbar">
									<button
										type="button"
										onClick={() => {
											handleChangeIndex(index, "up");
										}}
										disabled={true}
									>
										<svg
											width="14"
											height="8"
											viewBox="0 0 14 8"
											fill="none"
											xmlns="http://www.w3.org/2000/svg"
											stroke="currentColor"
										>
											<path
												d="M13 7L7 1L1 7"
												strokeWidth="2"
												strokeLinecap="round"
												strokeLinejoin="round"
											/>
										</svg>
									</button>

									<button
										type="button"
										onClick={() => {
											handleChangeIndex(index, "down");
										}}
										disabled={
											index === productTable.length - 1
												? true
												: false
										}
									>
										<svg
											width="14"
											height="8"
											viewBox="0 0 14 8"
											fill="none"
											xmlns="http://www.w3.org/2000/svg"
											stroke="currentColor"
										>
											<path
												d="M1 0.999999L7 7L13 1"
												strokeWidth="2"
												strokeLinecap="round"
												strokeLinejoin="round"
											/>
										</svg>
									</button>
								</div>
							</td>
						)}
					</tr>
				))}
				<tr>
					<td colSpan="5" className="row-appender">
						<AddProductRow
							productTable={productTable}
							handleProductRow={handleProductRow}
						/>
					</td>
				</tr>
			</tbody>
		</table>
	);
};

export default LayoutOneTwo;
