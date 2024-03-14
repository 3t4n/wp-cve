import { BlockInstance } from "@wordpress/blocks";
import { StylesDef } from "./types";

/**
 * The `replacePlaceholders` function replaces placeholders in a string with
 * corresponding values from a given object.
 * @param {string} str - The `str` parameter is a string that contains placeholders
 * in the format `{{tag}}`. These placeholders will be replaced with corresponding
 * values from the `placeholders` object.
 * @param placeholders - An object containing key-value pairs where the key is the
 * placeholder tag and the value is the replacement value.
 * @returns the modified string after replacing all the placeholders with their
 * corresponding values.
 */
export const replacePlaceholders = (
	str: string,
	placeholders: {
		[key: string]: string;
	}
) => {
	Object.entries(placeholders).forEach(([tag, value]) => {
		str = str.replaceAll(`{{${tag}}}`, value);
	});

	return str;
};

/**
 * The `meetsConditions` function checks if a given set of settings meets certain
 * conditions specified in the `selectData` object.
 * @param settings - An object that contains various settings. Each setting is
 * identified by a key (string) and has a corresponding value (any data type).
 * @param selectData - The `selectData` parameter is an object that contains a
 * `condition` property. The `condition` property is an array of objects, where
 * each object represents a condition. Each condition object has three properties:
 * @returns The function `meetsConditions` returns a boolean value indicating
 * whether the given `settings` meet the specified conditions in the `selectData`
 * object.
 */
export const meetsConditions = (
	settings: { [key: string]: any },
	selectData: StylesDef[0]
) => {
	let depends = true;

	selectData?.condition?.forEach((data) => {
		const previous = depends;

		if (data.relation === "==" || data.relation === "===") {
			if (
				typeof data.value === "string" ||
				typeof data.value === "number" ||
				typeof data.value === "boolean"
			) {
				depends = settings[data.key] === data.value;
			} else {
				depends = !!data?.value?.includes(settings[data.key]);
			}
		} else if (data.relation === "!=" || data.relation === "!==") {
			if (
				typeof data.value === "string" ||
				typeof data.value === "number" ||
				typeof data.value === "boolean"
			) {
				depends = settings[data.key] !== data.value;
			} else {
				let selected = false;

				data?.value?.forEach((arrData: any) => {
					if (settings[data.key] !== arrData) {
						selected = true;
					}
				});

				if (selected) {
					depends = true;
				}
			}
		}

		if (previous === false) {
			depends = false;
		}
	});

	return depends;
};

/**
 * The function checks if an array of block instances contains any instances with a
 * name that includes "magazine-blocks/" or if any of its inner blocks contain such
 * instances.
 * @param blocks - An array of BlockInstance objects. Each BlockInstance object
 * represents a block in a block editor.
 * @returns a boolean value. It returns true if there is at least one block in the
 * given array of BlockInstance objects that has a name containing the string
 * 'magazine-blocks/', or if any of the innerBlocks of a block in the array also have a
 * block with the name containing 'magazine-blocks/'. Otherwise, it returns false.
 */
export const hasMagazineBlockBlocks = (blocks: Array<BlockInstance>) => {
	for (const block of blocks) {
		const name = block?.name ?? "";
		if (-1 !== name.indexOf("magazine-blocks/")) {
			return true;
		}

		if (block.innerBlocks && block.innerBlocks.length > 0) {
			if (hasMagazineBlockBlocks(block.innerBlocks)) {
				return true;
			}
		}
	}

	return false;
};
