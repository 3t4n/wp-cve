import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

const attributes = {
	clientId: {
		type: "string",
	},
	link: {
		type: "object",
	},
	icon: {
		default: {
			enable: true,
			icon: "facebook",
		},
	},
	iconSize: {
		type: "object",
		default: {
			desktop: {
				value: 14,
				unit: "px",
			},
		},
		style: [
			{
				selector:
					"{{WRAPPER}} svg { width: {{VALUE}}; height: {{VALUE}}; }",
			},
		],
	},
	...COMMON_BLOCK_ATTRIBUTES,
};

export default attributes;
