import { TemplateArray } from "@wordpress/blocks";
import { isString } from "lodash";
import { DEFAULT_LAYOUT } from "../constants";

const layoutTemplate = (
	columns: number,
	defaultLayout: typeof DEFAULT_LAYOUT
): TemplateArray => {
	let noOfColumns = columns;
	if (!columns) return [];
	if (isString(columns)) {
		noOfColumns = parseInt(columns);
	}
	return [...Array(noOfColumns)].map((data, index) => {
		const columnWidth = {
			desktop: defaultLayout.desktop[index],
			tablet: defaultLayout.tablet[index],
			mobile: defaultLayout.mobile[index],
		};
		return ["magazine-blocks/column", { colWidth: columnWidth }];
	});
};

export default layoutTemplate;
