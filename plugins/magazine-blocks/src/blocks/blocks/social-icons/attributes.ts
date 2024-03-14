import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

const attributes = {
	clientId: {
		type: "string",
	},
	alignment: {
		type: "object",
		style: [{ selector: "{{WRAPPER}} {justify-content: {{VALUE}}; }" }],
	},
	columnGap: {
		type: "object",
		default: {
			desktop: {
				value: 10,
				unit: "px",
			},
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-social-links .block-editor-block-list__layout, {{WRAPPER}} {column-gap: {{VALUE}}; }",
			},
		],
	},
	backgroundColor: {
		type: String,
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-social-icon a {background-color: {{VALUE}}}",
			},
		],
	},
	...COMMON_BLOCK_ATTRIBUTES,
};

export default attributes;
