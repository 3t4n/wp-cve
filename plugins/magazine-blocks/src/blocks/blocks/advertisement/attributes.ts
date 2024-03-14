import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

const attributes = {
	clientId: {
		type: String,
	},
	size: {
		type: String,
	},
	imageSize: {
		type: String,
		default: "728x90",
	},
	alignment: {
		type: Object,
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-advertisement-content  {justify-content: {{VALUE}}; }",
			},
		],
	},
	advertisementImage: {
		type: String,
		default: "",
	},
	link: {
		type: Object,
		default: "",
	},
	radius: {
		type: Object,
		default: {
			dimension: 1,
			desktop: { lock: true },
		},
		style: [
			{
				selector:
					"{{WRAPPER}} .mzb-advertisement-content img { border-radius: {{VALUE}}; }",
			},
		],
	},
	...COMMON_BLOCK_ATTRIBUTES,
};

export default attributes;
