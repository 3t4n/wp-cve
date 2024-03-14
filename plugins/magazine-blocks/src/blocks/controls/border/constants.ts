import { __ } from "@wordpress/i18n";
import {
	Dash,
	Dotted,
	Double,
	Groove,
	None,
	Solid,
} from "../../components/icons/Icons";

export const BORDER_STYLES = {
	none: None,
	solid: Solid,
	double: Double,
	dashed: Dash,
	dotted: Dotted,
	groove: Groove,
};

export const BORDER_OPTIONS = [
	{ label: __("None", "magazine-blocks"), value: "none" },
	{ label: __("Solid", "magazine-blocks"), value: "solid" },
	{ label: __("Double", "magazine-blocks"), value: "double" },
	{ label: __("Dashed", "magazine-blocks"), value: "dashed" },
	{ label: __("Dotted", "magazine-blocks"), value: "dotted" },
	{
		label: __("Groove", "magazine-blocks"),
		value: "groove",
	},
];

export const DEFAULT_BORDER_TYPE = "none";
