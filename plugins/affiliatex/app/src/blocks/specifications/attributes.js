const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
	},
	specificationTitle: {
		type: "string",
		default: "Specifications",
	},
	specificationTable: {
		type: "array",
		default: [
			{
				specificationLabel: "",
				specificationValue: "",
			},
		],
	},
	layoutStyle: {
		type: "string",
		default: "layout-1",
	},
	specificationBorder: {
		type: "object",
		default: {
			width: "1",
			style: "solid",
			color: {
				color: "#E6ECF7",
			},
		},
	},
	specificationColumnWidth: {
		type: "string",
		default: "styleOne",
	},
	specificationBorderWidth: {
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
	specificationBorderRadius: {
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
	specificationBoxShadow: {
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
	specificationBgType: {
		type: "string",
		default: "solid",
	},
	specificationBgColorSolid: {
		type: "string",
		default: "#FFFFFF",
	},
	specificationBgColorGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)",
		},
	},
	specificationTitleTypography: {
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
	specificationLabelTypography: {
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
	specificationValueTypography: {
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
	specificationTitleColor: {
		type: "string",
		default: "#292929",
	},
	specificationTitleBgColor: {
		type: "string",
		default: "#FFFFFF",
	},
	specificationLabelColor: {
		type: "string",
		default: "#000000",
	},
	specificationValueColor: {
		type: "string",
		default: customizationData?.fontColor || "#292929",
	},
	specificationRowColor: {
		type: "string",
		default: "#F5F7FA",
	},
	edSpecificationTitle: {
		type: "boolean",
		default: true,
	},
	specificationTitleAlign: {
		type: "string",
		default: "left",
	},
	specificationLabelAlign: {
		type: "string",
		default: "left",
	},
	specificationValueAlign: {
		type: "string",
		default: "left",
	},
	specificationMargin: {
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
	specificationPadding: {
		type: "object",
		default: {
			desktop: {
				top: "16px",
				left: "24px",
				right: "24px",
				bottom: "16px",
			},
			mobile: {
				top: "16px",
				left: "24px",
				right: "24px",
				bottom: "16px",
			},
			tablet: {
				top: "16px",
				left: "24px",
				right: "24px",
				bottom: "16px",
			},
		},
	},
};

export default attributes;
