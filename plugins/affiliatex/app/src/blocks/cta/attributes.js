import { __ } from "@wordpress/i18n";
const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
	},
	ctaTitle: {
		type: "string",
		default: __("Call to Action Title.", "affiliatex"),
	},
	ctaContent: {
		type: "string",
		default: __(
			"Start creating CTAs in seconds, and convert more of your visitors into leads.",
			"affiliatex"
		),
	},
	ctaLayout: {
		type: "string",
		default: "layoutOne",
	},
	ctaBGType: {
		type: "string",
		default: "color",
	},
	imgURL: {
		type: "string",
		default: AffiliateX.pluginUrl + "app/src/images/fallback.jpg",
	},
	imgID: {
		type: "number",
		default: "",
	},
	imgAlt: {
		type: "string",
		default: "",
	},
	overlayOpacity: {
		type: "decimalPoint",
		default: 0.1,
	},
	imagePosition: {
		type: "string",
		default: "center",
	},
	ctaBgColorType: {
		type: "string",
		default: "solid",
	},
	ctaBgGradient: {
		type: "object",
		default: {
			gradient: "linear-gradient(270deg, #8615CB 0%, #084ACA 100%)",
		},
	},
	ctaBGColor: {
		type: "string",
		default: "#fff",
	},
	columnReverse: {
		type: "boolean",
		default: false,
	},
	ctaAlignment: {
		type: "string",
		default: "center",
	},
	contentAlignment: {
		type: "string",
		default: "center",
	},
	ctaButtonAlignment: {
		type: "string",
		default: "center",
	},
	ctaTitleTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n5",

			size: {
				desktop: "40px",
				mobile: "40px",
				tablet: "40px",
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
	ctaContentTypography: {
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
	edButtons: {
		type: "boolean",
		default: true,
	},
	edButtonTwo: {
		type: "boolean",
		default: true,
	},
	ctaBorder: {
		type: "object",
		default: {
			style: "solid",
			color: {
				color: "#E6ECF7",
			},
		},
	},
	ctaBorderWidth: {
		type: "object",
		default: {
			top: "1px",
			left: "1px",
			right: "1px",
			bottom: "1px",
		},
	},
	ctaBorderRadius: {
		type: "object",
		default: {
			desktop: {
				top: "8px",
				left: "8px",
				right: "8px",
				bottom: "8px",
			},
			mobile: {
				top: "8px",
				left: "8px",
				right: "8px",
				bottom: "8px",
			},
			tablet: {
				top: "8px",
				left: "8px",
				right: "8px",
				bottom: "8px",
			},
		},
	},
	ctaTitleColor: {
		type: "string",
		default: "#262B33",
	},
	ctaTextColor: {
		type: "string",
		default: customizationData?.fontColor || "#292929",
	},
	ctaBoxPadding: {
		type: "object",
		default: {
			desktop: {
				top: "60px",
				left: "30px",
				right: "30px",
				bottom: "60px",
			},
			mobile: {
				top: "60px",
				left: "30px",
				right: "30px",
				bottom: "60px",
			},
			tablet: {
				top: "60px",
				left: "30px",
				right: "30px",
				bottom: "60px",
			},
		},
	},
	ctaMargin: {
		type: "object",
		default: {
			desktop: {
				top: "0px",
				left: "0",
				right: "0",
				bottom: "30px",
			},
			mobile: {
				top: "0px",
				left: "0",
				right: "0",
				bottom: "30px",
			},
			tablet: {
				top: "0px",
				left: "0",
				right: "0",
				bottom: "30px",
			},
		},
	},
	ctaBoxShadow: {
		type: "object",
		default: {
			enable: true,
			h_offset: 2,
			v_offset: 5,
			blur: 20,
			spread: 0,
			inset: false,
			color: {
				color: "rgba(210,213,218,0.2)",
			},
		},
	},
};

export default attributes;
