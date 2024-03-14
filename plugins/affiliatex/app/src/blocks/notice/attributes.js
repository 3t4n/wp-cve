const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
	},
	layoutStyle: {
		type: "string",
		default: "layout-type-1",
	},
	noticeBorder: {
		type: "object",
		default: {
			style: "solid",
			color: {
				color: "#e6ecf7",
			},
		},
	},
	noticeBorderWidth: {
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
	noticeBorderRadius: {
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
	boxShadow: {
		type: "object",
		default: {
			enable: true,
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
	noticeListItems: {
		type: "array",
		filterElements: true,
		default: ["List items"],
	},
	titleTag1: {
		type: "string",
		default: "h2",
	},
	edTitleIcon: {
		type: "boolean",
		default: true,
	},
	noticeTitleIcon: {
		type: "object",
		default: {
			name: "info-circle",
			value: "fa fa-info-circle",
		},
	},
	noticeTitle: {
		type: "string",
		default: "Notice",
	},
	noticeContentType: {
		type: "string",
		default: "list",
	},
	noticeContent: {
		type: "string",
		default: "This is the notice content",
	},
	noticeListType: {
		type: "string",
		default: "unordered",
	},
	alignment: {
		type: "string",
		default: "left",
	},
	noticeListIcon: {
		type: "object",
		default: {
			name: "check-circle",
			value: "fas fa-check-circle",
		},
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
	titleAlignment: {
		type: "string",
		default: "left",
	},
	listTextColor: {
		type: "string",
		default: "",
	},
	listBgType: {
		type: "string",
		default: "solid",
	},
	listBgGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)",
		},
	},
	listBgColor: {
		type: "string",
		default: "#ffffff",
	},
	noticeBgType: {
		type: "string",
		default: "solid",
	},
	noticeBgGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%)",
		},
	},
	noticeBgColor: {
		type: "string",
		default: "#24b644",
	},
	noticeBgTwoType: {
		type: "string",
		default: "solid",
	},
	noticeBgTwoGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%)",
		},
	},
	noticeBgTwoColor: {
		type: "string",
		default: "#F6F9FF",
	},
	noticeIconColor: {
		type: "string",
		default: "#24b644",
	},
	noticeIconTwoColor: {
		type: "string",
		default: "#084ACA",
	},
	noticeTextColor: {
		type: "string",
		default: "#ffffff",
	},
	noticeTextTwoColor: {
		type: "string",
		default: "#084ACA",
	},
	noticeListColor: {
		type: "string",
		default: customizationData?.fontColor || "#292929",
	},
	noticeIconSize: {
		type: "number",
		default: 17,
	},
	noticeListIconSize: {
		type: "number",
		default: 17,
	},
	noticeMargin: {
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
	titlePadding: {
		type: "object",
		default: {
			desktop: {
				top: "10px",
				right: "15px",
				bottom: "10px",
				left: "15px",
			},
			mobile: {
				top: "10px",
				right: "15px",
				bottom: "10px",
				left: "15px",
			},
			tablet: {
				top: "10px",
				right: "15px",
				bottom: "10px",
				left: "15px",
			},
		},
	},
	contentPadding: {
		type: "object",
		default: {
			desktop: {
				top: "10px",
				right: "15px",
				bottom: "10px",
				left: "15px",
			},
			mobile: {
				top: "10px",
				right: "15px",
				bottom: "10px",
				left: "15px",
			},
			tablet: {
				top: "10px",
				right: "15px",
				bottom: "10px",
				left: "15px",
			},
		},
	},
	noticePadding: {
		type: "object",
		default: {
			desktop: {
				top: "20px",
				right: "20px",
				bottom: "20px",
				left: "20px",
			},
			mobile: {
				top: "20px",
				right: "20px",
				bottom: "20px",
				left: "20px",
			},
			tablet: {
				top: "20px",
				right: "20px",
				bottom: "20px",
				left: "20px",
			},
		},
	},
	noticeunorderedType: {
		type: "string",
		default: "icon",
	},
};

export default attributes;
