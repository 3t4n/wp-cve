const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
	},
	buttonLabel: {
		type: "string",
		default: "Button",
	},
	buttonTextColor: {
		type: "string",
		default: "#ffffff",
	},
	buttonborderHoverColor: {
		type: "string",
		default: "#ffffff",
	},
	buttonTextHoverColor: {
		type: "string",
		default: "#ffffff",
	},
	buttonBGColor: {
		type: "string",
		default: customizationData?.btnColor || "#2670FF",
	},
	buttonBGHoverColor: {
		type: "string",
		default: customizationData?.btnHoverColor || "#084ACA",
	},
	buttonTypography: {
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
	buttonMargin: {
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
	buttonPadding: {
		type: "object",
		default: {
			desktop: {
				top: "",
				left: "",
				right: "",
				bottom: "",
			},
			mobile: {
				top: "",
				left: "",
				right: "",
				bottom: "",
			},
			tablet: {
				top: "",
				left: "",
				right: "",
				bottom: "",
			},
		},
	},
	iconPosition: {
		type: "string",
		default: "axBtnleft",
	},
	buttonURL: {
		type: "string",
		default: "",
	},
	buttonREL: {
		type: "string",
		default: "",
	},
	openInNewTab: {
		type: "boolean",
		default: false,
	},
	btnRelNoFollow: {
		type: "boolean",
		default: false,
	},
	btnRelSponsored: {
		type: "boolean",
		default: false,
	},
	btnDownload: {
		type: "boolean",
		default: false,
	},
	roundedButton: {
		type: "boolean",
		default: false,
	},
	buttonRadius: {
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
	buttonSize: {
		type: "string",
		default: "medium",
	},
	buttonWidth: {
		type: "string",
		default: "flexible",
	},
	buttonBorder: {
		type: "object",
		default: {
			width: "1",
			style: "none",
			color: {
				color: "#dddddd",
			},
		},
	},
	buttonBGType: {
		type: "string",
		default: "solid",
	},
	buttonBgGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)",
		},
	},
	buttonAlignment: {
		type: "string",
		default: "flex-start",
	},
	buttonShadow: {
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
	buttonIconSize: {
		type: "string",
		default: "18px",
	},
	buttonIconColor: {
		type: "string",
		default: "#ffffff",
	},
	buttonIconHoverColor: {
		type: "string",
		default: "#ffffff",
	},
	edButtonIcon: {
		type: "boolean",
		default: false,
	},
	buttonFixWidth: {
		type: "string",
		default: "100px",
	},
	ButtonIcon: {
		type: "object",
		default: {
			name: "thumb-up-simple",
			value: "far fa-thumbs-up",
		},
	},
	layoutStyle: {
		type: "string",
		default: "layout-type-1",
	},
	priceTagPosition: {
		type: "string",
		default: "tagBtnright",
	},
	productPrice: {
		type: "string",
		default: "$145",
	},
	priceTextColor: {
		type: "string",
		default: "#2670FF",
	},
	priceBackgroundColor: {
		type: "string",
		default: "#ffff",
	},
};

export default attributes;
