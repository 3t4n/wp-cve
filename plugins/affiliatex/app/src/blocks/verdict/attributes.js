import { __ } from "@wordpress/i18n";
const customizationData = AffiliateX.customizationData;
const attributes = {
	block_id: {
		type: "string",
	},
	verdictTitle: {
		type: "string",
		default: __("Verdict Title.", "affiliatex"),
	},
	verdictContent: {
		type: "string",
		default: __(
			"Start creating Verdict in seconds, and convert more of your visitors into leads.",
			"affiliatex"
		),
	},
	verdictLayout: {
		type: "string",
		default: "layoutOne",
	},
	verdictTitleTag: {
		type: "string",
		default: "h3",
	},
	verdictTitleTypography: {
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
	verdictContentTypography: {
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
	contentAlignment: {
		type: "string",
		default: "center",
	},
	verdictBorder: {
		type: "object",
		default: {
			width: "1",
			style: "solid",
			color: {
				color: "#E6ECF7",
			},
		},
	},
	verdictBorderWidth: {
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
	verdictBorderRadius: {
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
	verdictBoxPadding: {
		type: "object",
		default: {
			desktop: {
				top: "24px",
				left: "24px",
				right: "24px",
				bottom: "24px",
			},
			mobile: {
				top: "24px",
				left: "24px",
				right: "24px",
				bottom: "24px",
			},
			tablet: {
				top: "24px",
				left: "24px",
				right: "24px",
				bottom: "24px",
			},
		},
	},
	verdictMargin: {
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
	verdictBoxShadow: {
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
	edProsCons: {
		type: "boolean",
		default: true,
	},
	edVerdictRatings: {
		type: "boolean",
		default: true,
	},
	edUserRatings: {
		type: "boolean",
		default: true,
	},
	userRatingLabel: {
		type: "string",
		default: "User Ratings:",
	},
	userRatingContent: {
		type: "string",
		default: "No ratings received yet.",
	},
	verdictRatings: {
		type: "number",
		default: "",
	},
	verdictRatingColor: {
		type: "string",
		default: "#FFD700",
	},
	verdictRatingInactiveColor: {
		type: "string",
		default: "#808080",
	},
	verdictRatingStarSize: {
		type: "number",
		default: 25,
	},
	edverdictTotalScore: {
		type: "boolean",
		default: true,
	},
	verdictTotalScore: {
		type: "decimalPoint",
		default: 8.5,
	},
	ratingContent: {
		type: "string",
		default: "Our Score",
	},
	scoreTextColor: {
		type: "string",
		default: "#FFFFFF",
	},
	scoreBgTopColor: {
		type: "string",
		default: "#2670FF",
	},
	scoreBgBotColor: {
		type: "string",
		default: "#262B33",
	},
	edRatingsArrow: {
		type: "boolean",
		default: true,
	},
	verdictArrowColor: {
		type: "string",
		default: "#2670FF",
	},
	verdictTitleColor: {
		type: "string",
		default: "#060C0E",
	},
	verdictContentColor: {
		type: "string",
		default: customizationData?.fontColor || "#292929",
	},
	ratingAlignment: {
		type: "string",
		default: "left",
	},
	verdictBgType: {
		type: "string",
		default: "solid",
	},
	verdictBgColorSolid: {
		type: "string",
		default: "#FFFFFF",
	},
	verdictBgColorGradient: {
		type: "object",
		default: {
			gradient:
				"linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)",
		},
	},
};
export default attributes;
