import { __ } from "@wordpress/i18n";
import { useEffect, useState } from "@wordpress/element";
import BlockInspector from "./Inspector";
import blocks_styling from "./styling";

import LayoutOneTwo from "./components/LayoutOneTwo";
import LayoutThree from "./components/LayoutThree";

const { Fragment } = wp.element;

import WebfontLoader from "../ui-components/typography/fontloader";

export default ({
	attributes,
	setAttributes,
	className,
	isSelected,
	clientId,
}) => {
	useEffect(() => {
		const $style = document.createElement("style");
		$style.setAttribute("id", "affiliatex-pdt-table-style-" + clientId);
		document.head.appendChild($style);
	}, [null]);

	useEffect(() => {
		setAttributes({ block_id: clientId });
		var element = document.getElementById(
			"affiliatex-pdt-table-style-" + clientId
		);
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = blocks_styling(
				attributes,
				"affiliatex-pdt-table-style",
				clientId
			);
		}
	}, [attributes]);

	const {
		productTable,
		itemCount,
		layoutStyle,
		ribbonTypography,
		counterTypography,
		ratingTypography,
		rating2Typography,
		priceTypography,
		buttonTypography,
		headerTypography,
		titleTypography,
		contentTypography,
	} = attributes;

	let productTableRibbonTypography,
		productTableCounterTypography,
		productTableRatingTypography,
		productTableRating2Typography,
		productTablePriceTypography,
		productTableButtonTypography,
		productTableHeadingTypography,
		productTableTitleTypography,
		productTableContentTypography;

	if ("Default" !== ribbonTypography.family) {
		const productTableBlockRibbonTypoConfig = {
			google: {
				families: [
					ribbonTypography.family +
						(ribbonTypography.variation
							? ":" + ribbonTypography.variation
							: ""),
				],
			},
		};

		productTableRibbonTypography = (
			<WebfontLoader
				config={productTableBlockRibbonTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== counterTypography.family) {
		const productTableBlockCounterTypoConfig = {
			google: {
				families: [
					counterTypography.family +
						(counterTypography.variation
							? ":" + counterTypography.variation
							: ""),
				],
			},
		};

		productTableCounterTypography = (
			<WebfontLoader
				config={productTableBlockCounterTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== ratingTypography.family) {
		const productTableBlockRatingTypoConfig = {
			google: {
				families: [
					ratingTypography.family +
						(ratingTypography.variation
							? ":" + ratingTypography.variation
							: ""),
				],
			},
		};

		productTableRatingTypography = (
			<WebfontLoader
				config={productTableBlockRatingTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== rating2Typography.family) {
		const productTableBlockRating2TypoConfig = {
			google: {
				families: [
					rating2Typography.family +
						(rating2Typography.variation
							? ":" + rating2Typography.variation
							: ""),
				],
			},
		};

		productTableRating2Typography = (
			<WebfontLoader
				config={productTableBlockRating2TypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== priceTypography.family) {
		const productTableBlockPriceTypoConfig = {
			google: {
				families: [
					priceTypography.family +
						(priceTypography.variation
							? ":" + priceTypography.variation
							: ""),
				],
			},
		};

		productTablePriceTypography = (
			<WebfontLoader
				config={productTableBlockPriceTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== buttonTypography.family) {
		const productTableBlockButtonTypoConfig = {
			google: {
				families: [
					buttonTypography.family +
						(buttonTypography.variation
							? ":" + buttonTypography.variation
							: ""),
				],
			},
		};

		productTableButtonTypography = (
			<WebfontLoader
				config={productTableBlockButtonTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== headerTypography.family) {
		const productTableBlockHeaderTypoConfig = {
			google: {
				families: [
					headerTypography.family +
						(headerTypography.variation
							? ":" + headerTypography.variation
							: ""),
				],
			},
		};

		productTableHeadingTypography = (
			<WebfontLoader
				config={productTableBlockHeaderTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== titleTypography.family) {
		const productTableBlockTitleTypoConfig = {
			google: {
				families: [
					titleTypography.family +
						(titleTypography.variation
							? ":" + titleTypography.variation
							: ""),
				],
			},
		};

		productTableTitleTypography = (
			<WebfontLoader
				config={productTableBlockTitleTypoConfig}
			></WebfontLoader>
		);
	}

	if ("Default" !== contentTypography.family) {
		const productTableBlockContentTypoConfig = {
			google: {
				families: [
					contentTypography.family +
						(contentTypography.variation
							? ":" + contentTypography.variation
							: ""),
				],
			},
		};

		productTableContentTypography = (
			<WebfontLoader
				config={productTableBlockContentTypoConfig}
			></WebfontLoader>
		);
	}

	const [selectedButton, setSelectedButton] = useState(null);

	const onSelectImage = (img, index) => {
		let tempproductTable = [...productTable];
		tempproductTable[index] = {
			...tempproductTable[index],
			imageUrl: img.url,
			imageId: img.id,
			imageAlt: img.alt,
		};
		setAttributes({ productTable: tempproductTable });
	};
	const onReplaceImage = (replace, index) => {
		let tempproductTable = [...productTable];
		tempproductTable[index] = {
			...tempproductTable[index],
			imageUrl: replace.url,
			imageId: replace.id,
			imageAlt: replace.alt,
		};
		setAttributes({ productTable: tempproductTable });
	};
	const onRemoveImage = (index) => {
		let tempproductTable = [...productTable];
		tempproductTable[index] = {
			...tempproductTable[index],
			imageUrl: AffiliateX.pluginUrl + "app/src/images/fallback.jpg",
			imageId: null,
			imageAlt: null,
		};
		setAttributes({ productTable: tempproductTable });
	};

	const onChangeProductDetails = (value, index, item) => {
		let tempproductTable = [...productTable];
		tempproductTable[index] = {
			...tempproductTable[index],
			[`${item}`]: value,
		};
		setAttributes({ productTable: tempproductTable });
	};

	const handleProductRow = (productTable) => {
		let itemCount = 0;
		let tempproductTable = [...productTable];
		tempproductTable = [
			...productTable,
			{
				imageUrl: AffiliateX.pluginUrl + "app/src/images/fallback.jpg",
				imageId: "",
				imageAlt: "",
				ribbon: "",
				name: "Product Name",
				features: "Product Features",
				featuresList: [],
				offerPrice: "$49.00",
				regularPrice: "$59.00",
				rating: "",
				button1: "Purchase Now",
				button1URL: "",
				btn1RelNoFollow: false,
				btn1RelSponsored: false,
				btn1OpenInNewTab: false,
				btn1Download: false,
				button2: "Check on Amazon",
				button2URL: "",
				btn2RelNoFollow: false,
				btn2RelSponsored: false,
				btn2OpenInNewTab: false,
				btn2Download: false,
			},
		];
		setAttributes({ productTable: tempproductTable });
		itemCount = productTable.length;
		setAttributes({ itemCount });
	};

	const handleRemoveProductRow = (index) => {
		let tempproductTable = [...productTable];
		tempproductTable.splice(index, 1);
		setAttributes({
			productTable: tempproductTable,
			itemCount: itemCount - 1,
		});
	};

	const handleChangeIndex = (index, action) => {
		let tempproductTable = [...productTable];
		if (action == "up" && index !== 0) {
			tempproductTable.splice(index, 1);
			tempproductTable.splice(index - 1, 0, productTable[index]);
		} else if (action == "down" && index !== tempproductTable.length - 1) {
			tempproductTable.splice(index, 1);
			tempproductTable.splice(index + 1, 0, productTable[index]);
		}
		setAttributes({ productTable: tempproductTable });
	};

	return (
		<Fragment>
			<BlockInspector
				{...{ attributes, setAttributes, className, isSelected }}
			/>
			<div
				id={`affiliatex-pdt-table-style-${clientId}`}
				className={className}
			>
				<div
					className={`affx-pdt-table-container--free affx-block-admin ${
						layoutStyle && layoutStyle == "layoutThree"
							? "layout-3"
							: ""
					}`}
				>
					<div className={`affx-pdt-table-wrapper`}>
						{layoutStyle &&
							(layoutStyle == "layoutOne" ||
								layoutStyle == "layoutTwo") && (
								<LayoutOneTwo
									attributes={attributes}
									setAttributes={setAttributes}
									handleChangeIndex={handleChangeIndex}
									onSelectImage={onSelectImage}
									onReplaceImage={onReplaceImage}
									onRemoveImage={onRemoveImage}
									onChangeProductDetails={
										onChangeProductDetails
									}
									setSelectedButton={setSelectedButton}
									selectedButton={selectedButton}
									handleProductRow={handleProductRow}
									handleRemoveProductRow={
										handleRemoveProductRow
									}
									isSelected={isSelected}
								/>
							)}

						{layoutStyle && layoutStyle == "layoutThree" && (
							<LayoutThree
								attributes={attributes}
								setAttributes={setAttributes}
								handleChangeIndex={handleChangeIndex}
								onSelectImage={onSelectImage}
								onReplaceImage={onReplaceImage}
								onRemoveImage={onRemoveImage}
								onChangeProductDetails={onChangeProductDetails}
								setSelectedButton={setSelectedButton}
								selectedButton={selectedButton}
								handleProductRow={handleProductRow}
								handleRemoveProductRow={handleRemoveProductRow}
								isSelected={isSelected}
							/>
						)}
					</div>
				</div>
			</div>
			{productTableRibbonTypography}
			{productTableCounterTypography}
			{productTableRatingTypography}
			{productTableRating2Typography}
			{productTablePriceTypography}
			{productTableButtonTypography}
			{productTableHeadingTypography}
			{productTableTitleTypography}
			{productTableContentTypography}
		</Fragment>
	);
};
