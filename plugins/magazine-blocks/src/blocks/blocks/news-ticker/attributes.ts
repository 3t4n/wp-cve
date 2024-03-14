import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

const attributes = {
	clientId: {
		type: String,
	},
	text: {
		type: String,
	},
	category: {
		type: String,
		default: "all",
	},
	label: {
		type: String,
		default: "Breaking News",
	},
	listColor: {
		type: String,
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-news-ticker-list li a {color: {{VALUE}}; }",
			},
		],
	},
	listhoverColor: {
		type: String,
		style: [{ selector: "{{WRAPPER}}:hover {color: {{VALUE}}; }" }],
	},
	icon: {
		type: Object,
		default: {
			enable: true,
			icon: "flash",
		},
	},
	iconSize: {
		type: Object,
		style: [
			{
				condition: [{ key: "icon", relation: "!=", value: "" }],
				selector:
					"{{WRAPPER}} .mzb-news-ticker-item-wrapper svg { width: {{VALUE}}; height: {{VALUE}}; }",
			},
		],
	},
	iconGap: {
		type: Object,
		style: [
			{
				selector: "{{WRAPPER}} span { margin-left: {{VALUE}}; }",
			},
		],
	},
	...COMMON_BLOCK_ATTRIBUTES,
};
export default attributes;
