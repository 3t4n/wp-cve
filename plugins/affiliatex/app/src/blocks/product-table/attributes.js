const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
	},
	imageColTitle: {
		type: "string",
		default: "Image",
	},
	productColTitle: {
		type: "string",
		default: "Product",
	},
	featuresColTitle: {
		type: "string",
		default: "Features",
	},
	ratingColTitle: {
		type: "string",
		default: "Rating",
	},
	priceColTitle: {
		type: "string",
		default: "Price",
	},
	productTable: {
		type: "array",
		default: [
			{
				imageUrl: AffiliateX.pluginUrl + "app/src/images/fallback.jpg",
				imageId: "",
				imageAlt: "",
				ribbon: "Our Pick",
				name: "Product Name",
				features: "Product Features",
				featuresList: [],
				offerPrice: "$49.00",
				regularPrice: "$59.00",
				rating: "5",
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
		],
	},
	edImage: {
		type: "boolean",
		default: true,
	},
	edRibbon: {
		type: "boolean",
		default: true,
	},
	edProductName: {
		type: "boolean",
		default: true,
	},
	edRating: {
		type: "boolean",
		default: true,
	},
	edPrice: {
		type: "boolean",
		default: false,
	},
	edCounter: {
		type: "boolean",
		default: true,
	},
	edButton1: {
		type: "boolean",
		default: true,
	},
	edButton1Icon: {
		type: "boolean",
		default: true,
	},
	button1Icon: {
		type: "string",
		default: {
			name: "angle-right",
			value: "fas fa-angle-right",
		},
	},
	button1IconAlign: {
		type: "string",
		default: "right",
	},
	edButton2: {
		type: "boolean",
		default: true,
	},
	edButton2Icon: {
		type: "boolean",
		default: true,
	},
	button2Icon: {
		type: "string",
		default: {
			name: "angle-right",
			value: "fas fa-angle-right",
		},
	},
	button2IconAlign: {
		type: "string",
		default: "right",
	},
	layoutStyle: {
		type: "string",
		default: "layoutOne",
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
				color: "rgba(137,138,140,0.2)",
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
	button1Padding: {
		type: "object",
		default: {
			desktop: {
				top: "10px",
				left: "5px",
				right: "5px",
				bottom: "10px",
			},
			mobile: {
				top: "10px",
				left: "5px",
				right: "5px",
				bottom: "10px",
			},
			tablet: {
				top: "10px",
				left: "5px",
				right: "5px",
				bottom: "10px",
			},
		},
	},
	button1Margin: {
		type: "object",
		default: {
			desktop: {
				top: "5px",
				left: "0px",
				right: "0px",
				bottom: "5px",
			},
			mobile: {
				top: "5px",
				left: "0px",
				right: "0px",
				bottom: "5px",
			},
			tablet: {
				top: "5px",
				left: "0px",
				right: "0px",
				bottom: "5px",
			},
		},
	},
	button2Padding: {
		type: "object",
		default: {
			desktop: {
				top: "10px",
				left: "5px",
				right: "5px",
				bottom: "10px",
			},
			mobile: {
				top: "10px",
				left: "5px",
				right: "5px",
				bottom: "10px",
			},
			tablet: {
				top: "10px",
				left: "5px",
				right: "5px",
				bottom: "10px",
			},
		},
	},
	button2Margin: {
		type: "object",
		default: {
			desktop: {
				top: "5px",
				left: "0px",
				right: "0px",
				bottom: "5px",
			},
			mobile: {
				top: "5px",
				left: "0px",
				right: "0px",
				bottom: "5px",
			},
			tablet: {
				top: "5px",
				left: "0px",
				right: "0px",
				bottom: "5px",
			},
		},
	},
	ribbonColor: {
		type: "string",
		default: "#FFFFFF",
	},
	ribbonBgColor: {
		type: "string",
		default: "#F13A3A",
	},
	counterColor: {
		type: "string",
		default: "#FFFFFF",
	},
	counterBgColor: {
		type: "string",
		default: "#24B644",
	},
	tableHeaderColor: {
		type: "string",
		default: "#FFFFFF",
	},
	tableHeaderBgColor: {
		type: "string",
		default: "#084ACA",
	},
	priceColor: {
		type: "string",
		default: "#262B33",
	},
	ratingColor: {
		type: "string",
		default: "#FFFFFF",
	},
	ratingBgColor: {
		type: "string",
		default: "#24B644",
	},
	rating2Color: {
		type: "string",
		default: "#262B33",
	},
	rating2BgColor: {
		type: "string",
		default: "#24B644",
	},
	starColor: {
		type: "string",
		default: "#FFB800",
	},
	starInactiveColor: {
		type: "string",
		default: "#A3ACBF",
	},
	contentColor: {
		type: "string",
		default: customizationData.fontColor || "#292929",
	},
	titleColor: {
		type: "string",
		default: customizationData.fontColor || "#292929",
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
	button1TextColor: {
		type: "string",
		default: "#fff",
	},
	button1TextHoverColor: {
		type: "string",
		default: "#fff",
	},
	button1BgColor: {
		type: "string",
		default: customizationData.btnColor || "#2670FF",
	},
	button1BgHoverColor: {
		type: "string",
		default: customizationData.btnHoverColor || "#084ACA",
	},
	button2TextColor: {
		type: "string",
		default: "#fff",
	},
	button2TextHoverColor: {
		type: "string",
		default: "#fff",
	},
	button2BgColor: {
		type: "string",
		default: "#FFB800",
	},
	button2BgHoverColor: {
		type: "string",
		default: "#084ACA",
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
	ribbonTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n5",

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
	counterTypography: {
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
	ratingTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n7",

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
	rating2Typography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "24px",
				mobile: "24px",
				tablet: "24px",
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
	buttonTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "14px",
				mobile: "14px",
				tablet: "14px",
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
	headerTypography: {
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
	titleTypography: {
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
	contentTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n4",

			size: {
				desktop: "18px",
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
	productContentType: {
		type: "string",
		default: "paragraph",
	},
	contentListType: {
		type: "string",
		default: "unordered",
	},
	productIconList: {
		type: "string",
		default: {
			name: "check-circle-outline",
			value: "far fa-check-circle",
		},
	},
	productIconColor: {
		type: "string",
		default: "#24B644",
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
