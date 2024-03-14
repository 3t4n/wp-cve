import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

export default {
	clientId: {
		type: "string",
		default: "",
	},
	colWidth: {
		type: "object",
		default: { desktop: 50, tablet: 100, mobile: 100 },
		style: [{ selector: "{{WRAPPER}} { width: {{VALUE}}%; }" }],
	},
	background: {
		type: "object",
		default: { background: 1 },
		style: [{ selector: "{{WRAPPER}} > .mzb-column-inner" }],
	},
	hoverBackground: {
		type: "object",
		default: { background: 1 },
		style: [{ selector: "{{WRAPPER}}:hover > .mzb-column-inner" }],
	},
	border: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}} > .mzb-column-inner" }],
	},
	borderHover: {
		type: "object",
		default: {
			border: 1,
			radius: { desktop: { lock: true } },
			size: { desktop: { lock: true } },
		},
		style: [{ selector: "{{WRAPPER}}:hover > .mzb-column-inner" }],
	},
	boxShadow: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}} > .mzb-column-inner" }],
	},
	boxShadowHover: {
		type: "object",
		default: { boxShadow: 1 },
		style: [{ selector: "{{WRAPPER}}:hover > .mzb-column-inner" }],
	},
	...{
		...COMMON_BLOCK_ATTRIBUTES,
		blockPadding: {
			type: "object",
			default: {
				dimension: 1,
				desktop: { lock: true },
			},
			style: [
				{
					selector:
						"{{WRAPPER}} > .mzb-column-inner { padding: {{VALUE}}; }",
				},
			],
		},
	},
};
