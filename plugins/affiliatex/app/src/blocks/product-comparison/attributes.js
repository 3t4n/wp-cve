import { __ } from "@wordpress/i18n";
const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
	},
	productComparisonTable: {
		type: "array",
		default: [
			{
				title: "",
				imageUrl: AffiliateX.pluginUrl + "app/src/images/fallback.jpg",
				ribbonText: __("Our Pick", "affiliatex"),
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
		],
	},
	comparisonSpecs: {
		type: "array",
		default: [
			{
				title: "",
				specs: [],
			},
		],
	},
	titleColor: {
		type: "string",
		default: "#262B33",
	},
	ribbonColor: {
		type: "string",
		default: "#F13A3A",
	},
	ribbonTextColor: {
		type: "string",
		default: "#fff",
	},
	starColor: {
		type: "string",
		default: "#FFB800",
	},
	starInactiveColor: {
		type: "string",
		default: "#A3ACBF",
	},
	pcRibbon: {
		type: "boolean",
		default: true,
	},
	pcImage: {
		type: "boolean",
		default: true,
	},
	pcTitle: {
		type: "boolean",
		default: true,
	},
	pcRating: {
		type: "boolean",
		default: true,
	},
	pcPrice: {
		type: "boolean",
		default: true,
	},
	boxShadow: {
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
	pcButton: {
		type: "boolean",
		default: true,
	},
	pcButtonIcon: {
		type: "boolean",
		default: true,
	},
	buttonIcon: {
		type: "string",
		default: {
			name: "angle-right",
			value: "fas fa-angle-right",
		},
	},
	buttonIconAlign: {
		type: "string",
		default: "right",
	},
	buttonPadding: {
		type: "object",
		default: {
			desktop: {
				top: "10px",
				left: "10px",
				right: "10px",
				bottom: "10px",
			},
			mobile: {
				top: "10px",
				left: "10px",
				right: "10px",
				bottom: "10px",
			},
			tablet: {
				top: "10px",
				left: "10px",
				right: "10px",
				bottom: "10px",
			},
		},
	},
	buttonMargin: {
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
	border: {
		type: "object",
		default: {
			width: "1",
			style: "solid",
			color: {
				color: "#E6ECF7",
			},
		},
	},
	borderWidth: {
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
	borderRadius: {
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
	priceColor: {
		type: "string",
		default: "#262B33",
	},
	tableRowBgColor: {
		type: "string",
		default: "#F5F7FA",
	},
	contentColor: {
		type: "string",
		default: customizationData.fontColor || "#292929",
	},
	buttonTextColor: {
		type: "string",
		default: "#fff",
	},
	buttonTextHoverColor: {
		type: "string",
		default: "#fff",
	},
	bgType: {
		type: "string",
		default: "solid",
	},
	bgColorSolid: {
		type: "string",
		default: "#FFFFFF",
	},
	bgColorGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)",
		},
	},
	buttonBgColor: {
		type: "string",
		default: customizationData.btnColor || "#2670FF",
	},
	buttonBgHoverColor: {
		type: "string",
		default: customizationData.btnHoverColor || "#084ACA",
	},
	titleTypography: {
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
	ribbonTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "13px",
				mobile: "13px",
				tablet: "13px",
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
	priceTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "20px",
				mobile: "20px",
				tablet: "20px",
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
	buttonTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "16px",
				mobile: "16px",
				tablet: "16px",
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
	contentTypography: {
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
	margin: {
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
	padding: {
		type: "object",
		default: {
			desktop: {
				top: "24px",
				left: "24px",
				right: "24px",
				bottom: "24px",
			},
			mobile: {
				top: "16px",
				left: "16px",
				right: "16px",
				bottom: "16px",
			},
			tablet: {
				top: "16px",
				left: "16px",
				right: "16px",
				bottom: "16px",
			},
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
};

export default attributes;
