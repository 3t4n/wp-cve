import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

const attributes = {
	clientId: {
		type: String,
	},
	category: {
		type: String,
		default: "all",
	},
	tag: {
		type: String,
		default: "all",
	},
	orderBy: {
		type: String,
		default: "date",
	},
	orderType: {
		type: String,
		default: "desc",
	},
	authorName: {
		type: String,
		default: "",
	},
	excludedCategory: {
		type: String,
		default: "",
	},
	postCount: {
		type: Number,
		default: 4,
	},
	size: {
		type: String,
	},
	alignment: {
		type: "object",
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-post-content {text-align: {{VALUE}}; }",
			},
			{
				selector:
					"{{WRAPPER}} .mzb-post-entry-meta {justify-content: {{VALUE}}; }",
			},
		],
	},
	height: {
		type: "object",
		default: {
			value: 420,
			unit: "px",
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__slide { height: {{VALUE}} }",
			},
		],
	},
	sliderSpeed: {
		type: Number,
		default: 3000,
	},
	enableAutoPlay: {
		type: Boolean,
		default: true,
	},
	enablePauseOnHover: {
		type: Boolean,
		default: false,
	},
	enableArrow: {
		type: Boolean,
		default: false,
	},
	arrowHeight: {
		type: "object",
		default: {
			value: "",
			unit: "px",
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__arrows.splide__arrows--ltr .splide__arrow { height: {{VALUE}} }",
			},
		],
	},
	arrowWidth: {
		type: "object",
		default: {
			value: "",
			unit: "px",
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__arrows.splide__arrows--ltr .splide__arrow { width: {{VALUE}} }",
			},
		],
	},
	arrowSize: {
		type: "object",
		default: {
			value: "",
			unit: "px",
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__arrows svg { width: {{VALUE}} }",
			},
			{
				selector:
					"{{WRAPPER}} .splide .splide__arrows svg { height: auto }",
			},
		],
	},
	arrowColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide__arrows svg, .editor-styles-wrapper .splide .splide__arrows svg {fill: {{VALUE}}; }",
			},
		],
	},
	arrowHoverColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide__arrows svg:hover, .editor-styles-wrapper .splide .splide__arrows svg:hover {fill: {{VALUE}}; }",
			},
		],
	},
	arrowBackground: {
		type: "object",
		default: { background: 1 },
		style: [
			{
				selector:
					"{{WRAPPER}} .splide__arrows.splide__arrows--ltr .splide__arrow, .editor-styles-wrapper .splide .splide__arrows.splide__arrows--ltr .splide__arrow",
			},
		],
	},
	arrowHoverBackground: {
		type: "object",
		default: { background: 1 },
		style: [
			{
				selector:
					"{{WRAPPER}} .splide__arrows.splide__arrows--ltr .splide__arrow:hover, .editor-styles-wrapper .splide .splide__arrows.splide__arrows--ltr .splide__arrow:hover",
			},
		],
	},
	postTitleTypography: {
		type: "object",
		default: { typography: 1, weight: 500 },
		style: [{ selector: "{{WRAPPER}} .mzb-post-title a" }],
	},
	postTitleMarkup: {
		type: "string",
		default: "h3",
	},
	postTitleColor: {
		type: "string",
		style: [
			{ selector: "{{WRAPPER}} .mzb-post-title a {color: {{VALUE}}; }" },
		],
	},
	postTitleHoverColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-post-title a:hover {color: {{VALUE}}; }",
			},
		],
	},
	enableCategory: {
		type: Boolean,
		default: true,
	},
	categoryColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .mzb-post-categories a {color: {{VALUE}}; }",
			},
		],
	},
	categoryBackground: {
		type: "object",
		default: { background: 1 },
		style: [{ selector: "{{WRAPPER}} .splide .mzb-post-categories a" }],
	},
	categoryHoverColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .mzb-post-categories a:hover {fill: {{VALUE}}; }",
			},
		],
	},
	categoryHoverBackground: {
		type: "object",
		default: { background: 1 },
		style: [
			{ selector: "{{WRAPPER}} .splide .mzb-post-categories a:hover" },
		],
	},
	categoryPadding: {
		type: "object",
		default: {
			dimension: 1,
			desktop: { lock: true },
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .mzb-post-categories a { padding: {{VALUE}}; }",
			},
		],
	},
	categoryBorder: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}} .splide .mzb-post-categories a" }],
	},
	categoryHoverBorder: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [
			{ selector: "{{WRAPPER}} .splide .mzb-post-categories a:hover" },
		],
	},
	categoryBoxShadow: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}} .splide .mzb-post-categories a" }],
	},
	categoryBoxShadowHover: {
		type: "object",
		default: { boxShadow: 1 },
		style: [
			{ selector: "{{WRAPPER}} .splide .mzb-post-categories a:hover" },
		],
	},
	enableAuthor: {
		type: Boolean,
		default: true,
	},
	enableDate: {
		type: Boolean,
		default: true,
	},
	metaPosition: {
		type: "string",
		default: "top",
	},
	metaIconColor: {
		type: "string",
		style: [
			{ selector: "{{WRAPPER}} .mzb-post-date svg {fill: {{VALUE}}; }" },
		],
	},
	metaLinkColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-post-date a {color: {{VALUE}}; } {{WRAPPER}} .mzb-post-author a {color: {{VALUE}}; }",
			},
		],
	},
	metaLinkHoverColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-post-date a:hover {color: {{VALUE}}; } {{WRAPPER}} .mzb-post-author a:hover {color: {{VALUE}}; }",
			},
		],
	},
	enableExcerpt: {
		type: Boolean,
		default: false,
	},
	excerptLimit: {
		type: Number,
		default: 20,
	},
	excerptColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-entry-summary p {color: {{VALUE}}; }",
			},
		],
	},
	excerptMargin: {
		type: "object",
		default: {
			dimension: 1,
			desktop: { lock: true },
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .mzb-entry-content .mzb-entry-summary p { margin: {{VALUE}}; }",
			},
		],
	},
	enableReadMore: {
		type: Boolean,
		default: false,
	},
	readMoreText: {
		type: String,
		default: "Read More",
	},
	readMoreColor: {
		type: "string",
		style: [
			{ selector: "{{WRAPPER}} .mzb-read-more a {color: {{VALUE}}; }" },
		],
	},
	readMoreBackground: {
		type: "object",
		default: { background: 1 },
		style: [{ selector: "{{WRAPPER}} .mzb-read-more a" }],
	},
	readMorewHoverColor: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-read-more a:hover {color: {{VALUE}}; }",
			},
		],
	},
	readMoreHoverBackground: {
		type: "object",
		default: { background: 1 },
		style: [{ selector: "{{WRAPPER}} .mzb-read-more a:hover" }],
	},
	readMoreSpacing: {
		type: "object",
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-read-more { margin-top: {{VALUE}} }",
			},
		],
	},
	readMorePadding: {
		type: "object",
		default: {
			dimension: 1,
			desktop: { lock: true },
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-read-more a { padding: {{VALUE}}; }",
			},
		],
	},
	enableReadMoreBorder: {
		type: Boolean,
		default: false,
	},
	readMoreBorder: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}} .splide .mzb-read-more a" }],
	},
	readMoreHoverBorder: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}} .splide .mzb-read-more a:hover" }],
	},
	readMoreBoxShadow: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}} .splide .mzb-read-more a" }],
	},
	readMoreBoxShadowHover: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}} .splide .mzb-read-more a:hover" }],
	},
	enableDot: {
		type: Boolean,
		default: false,
	},
	dotGap: {
		type: "object",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination { gap: {{VALUE}}; }",
			},
		],
	},
	dotHeight: {
		type: "object",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination button { height: {{VALUE}} }",
			},
		],
	},
	dotWidth: {
		type: "object",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination button { width: {{VALUE}} }",
			},
		],
	},
	horizontalPosition: {
		type: "object",
		default: {
			value: 0,
			unit: "px",
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination { left: {{VALUE}} }",
			},
		],
	},
	verticalPosition: {
		type: "object",
		default: {
			value: 10,
			unit: "px",
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination { bottom: {{VALUE}} }",
			},
		],
	},
	dotBackground: {
		type: "string",
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination button { background-color: {{VALUE}}; }",
			},
		],
	},
	dotBorder: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}} .splide .splide__pagination button" }],
	},
	dotHoverBorder: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination button:hover",
			},
		],
	},
	dotBoxShadow: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}} .splide .splide__pagination button" }],
	},
	dotBoxShadowHover: {
		type: "object",
		default: { boxShadow: 1 },
		style: [
			{
				selector:
					"{{WRAPPER}} .splide .splide__pagination button:hover",
			},
		],
	},
	...COMMON_BLOCK_ATTRIBUTES,
};

export default attributes;
