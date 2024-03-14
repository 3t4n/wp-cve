import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

const attributes = {
	clientId: {
		type: String,
	},
	columnGap: {
		type: Object,
		default: {
			desktop: {
				value: 20,
				unit: "px",
			},
		},
		style: [
			{
				selector: "{{WRAPPER}} .mzb-posts {column-gap: {{VALUE}}; }",
			},
		],
	},
	category: {
		type: String,
		default: "all",
	},
	postCount: {
		type: Number,
		default: 4,
	},
	radius: {
		type: Object,
		default: {
			dimension: 1,
			desktop: { lock: true },
		},
		style: [
			{ selector: "{{WRAPPER}} .mzb-post { border-radius: {{VALUE}}; }" },
		],
	},
	size: {
		type: String,
	},
	column: {
		type: Number,
		default: 3,
	},
	...COMMON_BLOCK_ATTRIBUTES,
};
export default attributes;
