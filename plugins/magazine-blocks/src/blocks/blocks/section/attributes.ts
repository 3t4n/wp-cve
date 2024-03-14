import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

export default {
	clientId: {
		type: "string",
	},
	hasModal: {
		type: "boolean",
		default: false,
	},
	modalOnly: {
		type: "boolean",
		default: false,
	},
	columns: {
		type: "number",
		default: "",
	},
	childRow: {
		type: "boolean",
		default: false,
	},
	verticalAlignment: {
		type: "object",
		default: {
			desktop: "",
		},
		style: [
			{
				selector:
					"{{WRAPPER}} > .mzb-container > .mzb-section-inner," +
					"{{WRAPPER}} > .mzb-container-fluid > .mzb-section-inner" +
					"{ align-items: {{VALUE}}; }",
			},
		],
	},
	container: {
		type: "string",
		default: "contained",
	},
	inheritFromTheme: {
		type: "boolean",
		default: false,
	},
	width: {
		type: "object",
		default: {
			desktop: {
				value: 1170,
				unit: "px",
			},
		},
		style: [
			{
				condition: [
					{ key: "container", relation: "==", value: "contained" },
					{ key: "inheritFromTheme", relation: "!=", value: true },
				],
				selector:
					"{{WRAPPER}} > .mzb-container { max-width: {{VALUE}}; }",
			},
		],
	},
	columnGap: {
		type: "object",
		default: {
			desktop: {
				value: 30,
				unit: "px",
			},
		},
		style: [
			{
				selector:
					"{{WRAPPER}} > .mzb-container > .mzb-section-inner > .mzb-column," +
					"{{WRAPPER}} > .mzb-container-fluid > .mzb-section-inner > .mzb-column" +
					"{ padding-left: {{VALUE}};}" +
					"{{WRAPPER}} > .mzb-container-fluid > .mzb-section-inner," +
					"{{WRAPPER}} > .mzb-container > .mzb-section-inner" +
					"{ margin-left: -{{VALUE}};}",
			},
		],
	},
	height: {
		type: "string",
		default: "default",
	},
	minHeight: {
		type: "object",
		style: [
			{
				condition: [
					{ key: "height", relation: "==", value: "min-height" },
				],
				selector:
					"{{WRAPPER}} > .mzb-container > .mzb-section-inner," +
					"{{WRAPPER}} > .mzb-container-fluid > .mzb-section-inner {min-height: {{VALUE}};}",
			},
		],
	},
	background: {
		type: "object",
		default: { background: 1 },
		style: [{ selector: "{{WRAPPER}}" }],
	},
	hoverBackground: {
		type: "object",
		default: { background: 1 },
		style: [{ selector: "{{WRAPPER}}:hover" }],
	},
	border: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}}" }],
	},
	borderHover: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}}:hover" }],
	},
	boxShadow: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}}" }],
	},
	boxShadowHover: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}}:hover" }],
	},
	overlay: {
		type: "boolean",
		default: false,
	},
	overlayBackground: {
		type: "object",
		default: {
			background: 1,
			color: "rgba(37, 99, 235,0.3)",
		},
		style: [
			{
				condition: [{ key: "overlay", relation: "==", value: true }],
				selector: "{{WRAPPER}} > .mzb-overlay",
			},
		],
	},
	...{
		...COMMON_BLOCK_ATTRIBUTES,
		blockPadding: {
			type: "object",
			default: {
				dimension: 1,
				desktop: { left: 15, right: 15, unit: "px" },
			},
			style: [{ selector: "{{WRAPPER}} { padding: {{VALUE}}; }" }],
		},
	},
};
