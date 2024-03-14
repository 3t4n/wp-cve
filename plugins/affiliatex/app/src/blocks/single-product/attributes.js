import { applyFilters } from "@wordpress/hooks";
const customizationData = AffiliateX.customizationData;

const attributes = applyFilters("affx_single_product_attributes", {
	block_id: {
		type: "string",
	},
	productLayout: {
		type: "string",
		default: "layoutOne",
	},
	productLayoutOptions: {
		type: "array",
		default: [
			{ value: "layoutOne", label: "Layout One" },
			{ value: "layoutTwo", label: "Layout Two" },
			{ value: "layoutThree", label: "Layout Three" },
		],
	},
	productRibbonLayout: {
		type: "string",
		default: "one",
	},
	productTitle: {
		type: "string",
		default: "Title",
	},
	productTitleTag: {
		type: "string",
		default: "h2",
	},
	productTitleAlign: {
		type: "string",
		default: "left",
	},
	productPricingAlign: {
		type: "string",
		default: "left",
	},
	productStarRatingAlign: {
		type: "string",
		default: "left",
	},
	productRatingAlign: {
		type: "string",
		default: "right",
	},
	productTitleTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n5",

			size: {
				desktop: "24px",
				mobile: "24px",
				tablet: "24px",
			},
			"line-height": {
				desktop: "1.333",
				mobile: "1.333",
				tablet: "1.333",
			},
			"letter-spacing": {
				desktop: "0em",
				mobile: "0em",
				tablet: "0em",
			},
			"text-transform": "none",
			"text-decoration": "none",
		},
	},
	productTitleColor: {
		type: "string",
		default: "#060c0e",
	},
	productImageWidth: {
		type: "string",
		default: "inherit",
	},
	productImageCustomWidth: {
		type: "string",
		default: "33",
	},
	productImageType: {
		type: "string",
		default: "default",
	},
	productImageExternal: {
		type: "string",
		default: "",
	},
	productImageSiteStripe: {
		type: "string",
		default: "",
	},
	productSubTitle: {
		type: "string",
		default: "Subtitle",
	},
	productSubTitleTag: {
		type: "string",
		default: "h6",
	},
	productSubtitleAlign: {
		type: "string",
		default: "left",
	},
	productSubtitleTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n5",

			size: {
				desktop: "18px",
				mobile: "18px",
				tablet: "18px",
			},
			"line-height": {
				desktop: "1.5",
				mobile: "1.5",
				tablet: "1.5",
			},
			"letter-spacing": {
				desktop: "0em",
				mobile: "0em",
				tablet: "0em",
			},
			"text-transform": "none",
			"text-decoration": "none",
		},
	},
	productSubtitleColor: {
		type: "string",
		default: "#A3ACBF",
	},
	productIconList: {
		type: "string",
		default: {
			name: "check-circle-outline",
			value: "far fa-check-circle",
		},
	},
	productContent: {
		type: "string",
		default:
			"You can have short product description here. It can be added as and enable/disable toggle option from which user can have control on it.",
	},
	productContentType: {
		type: "string",
		default: "paragraph",
	},
	ContentListType: {
		type: "string",
		default: "unordered",
	},
	PricingType: {
		type: "string",
		default: "picture",
	},
	productContentList: {
		type: "array",
		filterElements: true,
		default: [],
	},
	productContentAlign: {
		type: "string",
		default: "left",
	},
	productContentTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "18px",
				mobile: "18px",
				tablet: "18px",
			},
			"line-height": {
				desktop: "1.65",
				mobile: "1.65",
				tablet: "1.65",
			},
			"letter-spacing": {
				desktop: "0em",
				mobile: "0em",
				tablet: "0em",
			},
			"text-transform": "none",
			"text-decoration": "none",
		},
	},
	productContentColor: {
		type: "string",
		default: customizationData?.fontColor || "#292929",
	},
	productRatingColor: {
		type: "string",
		default: "#FFB800",
	},
	pricingColor: {
		type: "string",
		default: "#262B33",
	},
	pricingHoverColor: {
		type: "string",
		default: "#A3ACBF",
	},
	ratingInactiveColor: {
		type: "string",
		default: "#808080",
	},
	ImgID: {
		type: "number",
		default: "",
	},
	ImgUrl: {
		type: "string",
		default: AffiliateX.pluginUrl + "app/src/images/fallback.jpg",
	},
	ImgAlt: {
		type: "string",
		default: "",
	},
	contentMargin: {
		type: "object",
		default: {
			desktop: {
				top: "0px",
				left: "0px",
				right: "0px",
				bottom: "30px",
			},
			mobile: {
				top: "0px",
				left: "0px",
				right: "0px",
				bottom: "30px",
			},
			tablet: {
				top: "0px",
				left: "0px",
				right: "0px",
				bottom: "30px",
			},
		},
	},
	contentSpacing: {
		type: "object",
		default: {
			desktop: {
				top: "30px",
				left: "25px",
				right: "25px",
				bottom: "30px",
			},
			mobile: {
				top: "30px",
				left: "25px",
				right: "25px",
				bottom: "30px",
			},
			tablet: {
				top: "30px",
				left: "25px",
				right: "25px",
				bottom: "30px",
			},
		},
	},
	productBorderWidth: {
		type: "object",
		default: {
			desktop: {
				top: "1px",
				left: "1px",
				right: "1px",
				bottom: "1px",
			},
			mobile: {
				top: "1px",
				left: "1px",
				right: "1px",
				bottom: "1px",
			},
			tablet: {
				top: "1px",
				left: "1px",
				right: "1px",
				bottom: "1px",
			},
		},
	},
	productBorderRadius: {
		type: "object",
		default: {
			desktop: {
				top: "0",
				left: "0",
				right: "0",
				bottom: "0",
			},
			mobile: {
				top: "0",
				left: "0",
				right: "0",
				bottom: "0",
			},
			tablet: {
				top: "0",
				left: "0",
				right: "0",
				bottom: "0",
			},
		},
	},
	productShadow: {
		type: "object",
		default: {
			enable: false,
			h_offset: 0,
			v_offset: 5,
			blur: 20,
			spread: 0,
			inset: false,
			color: {
				color: "rgba(210,213,218,0.2)",
			},
		},
	},
	productImageAlign: {
		type: "string",
		default: "left",
	},
	productPrice: {
		type: "string",
		default: "$59",
	},
	productSalePrice: {
		type: "string",
		default: "$49",
	},
	pricingTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "22px",
				mobile: "22px",
				tablet: "22px",
			},
			"line-height": {
				desktop: "1.65",
				mobile: "1.65",
				tablet: "1.65",
			},
			"letter-spacing": {
				desktop: "0em",
				mobile: "0em",
				tablet: "0em",
			},
			"text-transform": "none",
			"text-decoration": "none",
		},
	},
	productBorder: {
		type: "object",
		default: {
			style: "solid",
			color: {
				color: "#E6ECF7",
			},
		},
	},
	productDivider: {
		type: "object",
		default: {
			style: "none",
			width: "1",
			color: {
				color: "#E6ECF7",
			},
		},
	},
	ratings: {
		type: "number",
		default: 5,
	},
	numberRatings: {
		type: "number",
		default: "8.5",
	},
	ratingStarSize: {
		type: "number",
		default: 25,
	},
	ratingContent: {
		type: "string",
		default: "Our Score",
	},
	edRatings: {
		type: "boolean",
		default: false,
	},
	edTitle: {
		type: "boolean",
		default: true,
	},
	edSubtitle: {
		type: "boolean",
		default: false,
	},
	edPricing: {
		type: "boolean",
		default: false,
	},
	edContent: {
		type: "boolean",
		default: true,
	},
	productBgColorType: {
		type: "string",
		default: "solid",
	},
	productBgGradient: {
		type: "object",
		default: {
			gradient: "linear-gradient(270deg, #8615CB 0%, #084ACA 100%)",
		},
	},
	productBGColor: {
		type: "string",
		default: "#fff",
	},
	ribbonText: {
		type: "string",
		default: "Sale",
	},
	ribbonBgColorType: {
		type: "string",
		default: "solid",
	},
	ribbonBGColor: {
		type: "string",
		default: "#ff0000",
	},
	ribbonBgGradient: {
		type: "object",
		default: {
			gradient: "linear-gradient(270deg, #8615CB 0%, #084ACA 100%)",
		},
	},
	ribbonBgHoverType: {
		type: "string",
		default: "solid",
	},
	ribbonBgHoverGradient: {
		type: "object",
		default: {
			gradient: "linear-gradient(270deg, #8615CB 0%, #084ACA 100%)",
		},
	},
	edRibbon: {
		type: "boolean",
		default: false,
	},
	ribbonColor: {
		type: "string",
		default: "#fff",
	},
	ribbonContentTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "17px",
				mobile: "17px",
				tablet: "17px",
			},
			"line-height": {
				desktop: "1.65",
				mobile: "1.65",
				tablet: "1.65",
			},
			"letter-spacing": {
				desktop: "0em",
				mobile: "0em",
				tablet: "0em",
			},
			"text-transform": "none",
			"text-decoration": "none",
		},
	},
	ribbonAlign: {
		type: "string",
		default: "left",
	},
	edButton: {
		type: "boolean",
		default: true,
	},
	edProductImage: {
		type: "boolean",
		default: true,
	},
	iconColor: {
		type: "string",
		default: "#24B644",
	},
	productRateNumberColor: {
		type: "string",
		default: "#ffffff",
	},
	productRateContentColor: {
		type: "string",
		default: "#ffffff",
	},
	productRateNumBgColor: {
		type: "string",
		default: "#2670FF",
	},
	productRateContentBgColor: {
		type: "string",
		default: "#262B33",
	},
	numRatingTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "36px",
				mobile: "36px",
				tablet: "36px",
			},
			"line-height": {
				desktop: "1.65",
				mobile: "1.65",
				tablet: "1.65",
			},
			"letter-spacing": {
				desktop: "0em",
				mobile: "0em",
				tablet: "0em",
			},
			"text-transform": "none",
			"text-decoration": "none",
		},
	},
	imagePadding: {
		type: "object",
		default: {
			desktop: {
				top: "0px",
				left: "0px",
				right: "0px",
				bottom: "0px",
			},
			mobile: {
				top: "0px",
				left: "0px",
				right: "0px",
				bottom: "0px",
			},
			tablet: {
				top: "0px",
				left: "0px",
				right: "0px",
				bottom: "0px",
			},
		},
	},
});

export default attributes;
