const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
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
	prosListItems: {
		type: "array",
		filterElements: true,
		default: [],
	},
	consListItems: {
		type: "array",
		filterElements: true,
		default: [],
	},
	prosIconStatus: {
		type: "boolean",
		default: true,
	},
	consIconStatus: {
		type: "boolean",
		default: true,
	},
	contentAlignment: {
		type: "string",
		default: "left",
	},
	prosIcon: {
		type: "object",
		default: {
			name: "check-circle",
			value: "far fa-circle",
		},
	},
	consIcon: {
		type: "object",
		default: {
			name: "times-circle",
			value: "far fa-circle",
		},
	},
	prosListIcon: {
		type: "object",
		default: {
			name: "thumb-up-simple",
			value: "far fa-thumbs-up",
		},
	},
	consListIcon: {
		type: "object",
		default: {
			name: "thumb-down-simple",
			value: "far fa-thumbs-down",
		},
	},
	titleTag1: {
		type: "string",
		default: "p",
	},
	layoutStyle: {
		type: "string",
		default: "layout-type-1",
	},
	prosTitle: {
		type: "string",
		default: "Pros",
	},
	consTitle: {
		type: "string",
		default: "Cons",
	},
	contentType: {
		type: "string",
		default: "list",
	},
	prosContent: {
		type: "string",
		default: "",
	},
	consContent: {
		type: "string",
		default: "",
	},
	listType: {
		type: "string",
		default: "unordered",
	},
	unorderedType: {
		type: "string",
		default: "icon",
	},
	alignment: {
		type: "string",
		default: "left",
	},
	alignmentThree: {
		type: "string",
		default: "center",
	},
	titleBgColor: {
		type: "string",
		default: "",
	},
	titleTextColor: {
		type: "string",
		default: "",
	},
	titleTypography: {
		type: "object",
		default: {
			family: customizationData?.typography?.family || "Default",
			variation: "n5",

			size: {
				desktop: "20px",
				mobile: "20px",
				tablet: "20px",
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
	listTypography: {
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
	listTextColor: {
		type: "string",
		default: "",
	},
	prosListBgType: {
		type: "string",
		default: "solid",
	},
	prosListBgGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)",
		},
	},
	prosListBgColor: {
		type: "string",
		default: "#F5FFF8",
	},
	consListBgType: {
		type: "string",
		default: "solid",
	},
	consListBgGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)",
		},
	},
	consListBgColor: {
		type: "string",
		default: "#FFF5F5",
	},
	prosBgType: {
		type: "string",
		default: "solid",
	},
	prosBgGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%)",
		},
	},
	prosBgColor: {
		type: "string",
		default: "#24B644",
	},
	prosIconColor: {
		type: "string",
		default: "#24B644",
	},
	prosIconSize: {
		type: "number",
		default: 18,
	},
	prosTextColor: {
		type: "string",
		default: "#ffffff",
	},
	prosTextColorThree: {
		type: "string",
		default: "#24B644",
	},
	consBgType: {
		type: "string",
		default: "solid",
	},
	consBgGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%)",
		},
	},
	consBgColor: {
		type: "string",
		default: "#F13A3A",
	},
	consTextColor: {
		type: "string",
		default: "#ffffff",
	},
	consTextColorThree: {
		type: "string",
		default: "#F13A3A",
	},
	consIconColor: {
		type: "string",
		default: "#F13A3A",
	},
	consIconSize: {
		type: "number",
		default: 18,
	},
	consListColor: {
		type: "string",
		default: customizationData?.fontColor || "#292929",
	},
	prosListColor: {
		type: "string",
		default: customizationData?.fontColor || "#292929",
	},
	titleMargin: {
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
	titlePadding: {
		type: "object",
		default: {
			desktop: {
				top: "10px",
				left: "20px",
				right: "20px",
				bottom: "10px",
			},
			mobile: {
				top: "10px",
				left: "20px",
				right: "20px",
				bottom: "10px",
			},
			tablet: {
				top: "10px",
				left: "20px",
				right: "20px",
				bottom: "10px",
			},
		},
	},
	contentMargin: {
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
	contentPadding: {
		type: "object",
		default: {
			desktop: {
				top: "10px",
				left: "20px",
				right: "20px",
				bottom: "10px",
			},
			mobile: {
				top: "10px",
				left: "20px",
				right: "20px",
				bottom: "10px",
			},
			tablet: {
				top: "10px",
				left: "20px",
				right: "20px",
				bottom: "10px",
			},
		},
	},
	margin: {
		type: "object",
		default: {
			desktop: {
				top: "0",
				left: "0",
				right: "0",
				bottom: "30px",
			},
			mobile: {
				top: "0",
				left: "0",
				right: "0",
				bottom: "30px",
			},
			tablet: {
				top: "0",
				left: "0",
				right: "0",
				bottom: "30px",
			},
		},
	},
	padding: {
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
	prosBorder: {
		type: "object",
		default: {
			style: "none",
			color: {
				color: "#dddddd",
			},
		},
	},
	prosBorderThree: {
		type: "object",
		default: {
			style: "solid",
			color: {
				color: "#ffffff",
			},
		},
	},
	titleBorderWidthOne: {
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
	titleBorderRadiusOne: {
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
	titleBorderWidthTwo: {
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
	titleBorderRadiusTwo: {
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
	titleBorderWidthThree: {
		type: "object",
		default: {
			desktop: {
				top: "4px",
				left: "4px",
				right: "4px",
				bottom: "4px",
			},
			mobile: {
				top: "4px",
				left: "4px",
				right: "4px",
				bottom: "4px",
			},
			tablet: {
				top: "4px",
				left: "4px",
				right: "4px",
				bottom: "4px",
			},
		},
	},
	titleBorderRadiusThree: {
		type: "object",
		default: {
			desktop: {
				top: "50px",
				left: "50px",
				right: "50px",
				bottom: "50px",
			},
			mobile: {
				top: "50px",
				left: "50px",
				right: "50px",
				bottom: "50px",
			},
			tablet: {
				top: "50px",
				left: "50px",
				right: "50px",
				bottom: "50px",
			},
		},
	},
	titleBorderWidthFour: {
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
	titleBorderRadiusFour: {
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
	prosContentBorder: {
		type: "object",
		default: {
			style: "none",
			color: {
				color: "#dddddd",
			},
		},
	},
	prosContentBorderThree: {
		type: "object",
		default: {
			width: "1",
			style: "solid",
			color: {
				color: "#24B644",
			},
		},
	},
	contentBorderWidthOne: {
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
	contentBorderRadiusOne: {
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
	contentBorderWidthTwo: {
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
	contentBorderRadiusTwo: {
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
	contentBorderWidthThree: {
		type: "object",
		default: {
			desktop: {
				top: "4px",
				left: "4px",
				right: "4px",
				bottom: "4px",
			},
			mobile: {
				top: "4px",
				left: "4px",
				right: "4px",
				bottom: "4px",
			},
			tablet: {
				top: "4px",
				left: "4px",
				right: "4px",
				bottom: "4px",
			},
		},
	},
	contentBorderRadiusThree: {
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
	contentBorderWidthFour: {
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
	contentBorderRadiusFour: {
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
	consBorder: {
		type: "object",
		default: {
			style: "none",
			color: {
				color: "#dddddd",
			},
		},
	},
	consBorderThree: {
		type: "object",
		default: {
			style: "solid",
			color: {
				color: "#ffffff",
			},
		},
	},
	consContentBorder: {
		type: "object",
		default: {
			style: "none",
			color: {
				color: "#dddddd",
			},
		},
	},
	consContentBorderThree: {
		type: "object",
		default: {
			style: "solid",
			color: {
				color: "#F13A3A",
			},
		},
	},
};

export default attributes;
