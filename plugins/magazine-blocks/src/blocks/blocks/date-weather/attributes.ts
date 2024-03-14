import { COMMON_BLOCK_ATTRIBUTES } from "../../constants";

const attributes = {
	clientId: {
		type: String,
	},
	apiKey: {
		type: String,
	},
	postalCode: {
		type: Number,
	},
	...COMMON_BLOCK_ATTRIBUTES,
};
export default attributes;
