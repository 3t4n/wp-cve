import { __ } from "@wordpress/i18n";

export const LAYOUTS = [
	{
		label: "100",
		columns: 1,
		structure: { desktop: [100], tablet: [100], mobile: [100] },
	},
	{
		label: "50/50",
		columns: 2,
		structure: {
			desktop: [50, 50],
			tablet: [100, 100],
			mobile: [100, 100],
		},
	},
	{
		label: "34/66",
		columns: 2,
		structure: {
			desktop: [34, 66],
			tablet: [100, 100],
			mobile: [100, 100],
		},
	},
	{
		label: "66/34",
		columns: 2,
		structure: {
			desktop: [66, 34],
			tablet: [100, 100],
			mobile: [100, 100],
		},
	},
	{
		label: "33/33/33",
		columns: 3,
		structure: {
			desktop: [33.33, 33.33, 33.34],
			tablet: [100, 100, 100],
			mobile: [100, 100, 100],
		},
	},
	{
		label: "25/50/25",
		columns: 3,
		structure: {
			desktop: [25, 50, 25],
			tablet: [100, 100, 100],
			mobile: [100, 100, 100],
		},
	},
	{
		label: "25/25/50",
		columns: 3,
		structure: {
			desktop: [25, 25, 50],
			tablet: [100, 100, 100],
			mobile: [100, 100, 100],
		},
	},
	{
		label: "50/25/25",
		columns: 3,
		structure: {
			desktop: [50, 25, 25],
			tablet: [100, 100, 100],
			mobile: [100, 100, 100],
		},
	},
	{
		label: "25/25/25/25",
		columns: 4,
		structure: {
			desktop: [25, 25, 25, 25],
			tablet: [100, 100, 100, 100],
			mobile: [100, 100, 100, 100],
		},
	},
];

export const DEFAULT_LAYOUT = { desktop: [100], tablet: [100], mobile: [100] };

export const TOP_SEPARATOR_OPTIONS = [
	{
		label: __("Separator 1", "magazine-blocks"),
		value: "top_separator_1",
		icon: "top_separator_icon_1",
	},
	{
		label: __("Separator 2", "magazine-blocks"),
		value: "top_separator_2",
		icon: "top_separator_icon_2",
	},
	{
		label: __("Separator 3", "magazine-blocks"),
		value: "top_separator_3",
		icon: "top_separator_icon_3",
	},
	{
		label: __("Separator 4", "magazine-blocks"),
		value: "top_separator_4",
		icon: "top_separator_icon_4",
	},
	{
		label: __("Separator 5", "magazine-blocks"),
		value: "top_separator_5",
		icon: "top_separator_icon_5",
	},
	{
		label: __("Separator 6", "magazine-blocks"),
		value: "top_separator_6",
		icon: "top_separator_icon_6",
	},
	{
		label: __("Separator 7", "magazine-blocks"),
		value: "top_separator_7",
		icon: "top_separator_icon_7",
	},
	{
		label: __("Separator 8", "magazine-blocks"),
		value: "top_separator_8",
		icon: "top_separator_icon_8",
	},
	{
		label: __("Separator 9", "magazine-blocks"),
		value: "top_separator_9",
		icon: "top_separator_icon_9",
	},
	{
		label: __("Separator 10", "magazine-blocks"),
		value: "top_separator_10",
		icon: "top_separator_icon_10",
	},
	{
		label: __("Separator 11", "magazine-blocks"),
		value: "top_separator_11",
		icon: "top_separator_icon_11",
	},
	{
		label: __("Separator 12", "magazine-blocks"),
		value: "top_separator_12",
		icon: "top_separator_icon_12",
	},
	{
		label: __("Separator 13", "magazine-blocks"),
		value: "top_separator_13",
		icon: "top_separator_icon_13",
	},
];

export const BOTTOM_SEPARATOR_OPTIONS = [
	{
		label: __("Separator 1", "magazine-blocks"),
		value: "bottom_separator_1",
		icon: "bottom_separator_icon_1",
	},
	{
		label: __("Separator 2", "magazine-blocks"),
		value: "bottom_separator_2",
		icon: "bottom_separator_icon_2",
	},
	{
		label: __("Separator 3", "magazine-blocks"),
		value: "bottom_separator_3",
		icon: "bottom_separator_icon_3",
	},
	{
		label: __("Separator 4", "magazine-blocks"),
		value: "bottom_separator_4",
		icon: "bottom_separator_icon_4",
	},
	{
		label: __("Separator 5", "magazine-blocks"),
		value: "bottom_separator_5",
		icon: "bottom_separator_icon_5",
	},
	{
		label: __("Separator 6", "magazine-blocks"),
		value: "bottom_separator_6",
		icon: "bottom_separator_icon_6",
	},
	{
		label: __("Separator 7", "magazine-blocks"),
		value: "bottom_separator_7",
		icon: "bottom_separator_icon_7",
	},
	{
		label: __("Separator 8", "magazine-blocks"),
		value: "bottom_separator_8",
		icon: "bottom_separator_icon_8",
	},
	{
		label: __("Separator 9", "magazine-blocks"),
		value: "bottom_separator_9",
		icon: "bottom_separator_icon_9",
	},
	{
		label: __("Separator 10", "magazine-blocks"),
		value: "bottom_separator_10",
		icon: "bottom_separator_icon_10",
	},
	{
		label: __("Separator 11", "magazine-blocks"),
		value: "bottom_separator_11",
		icon: "bottom_separator_icon_11",
	},
	{
		label: __("Separator 12", "magazine-blocks"),
		value: "bottom_separator_12",
		icon: "bottom_separator_icon_12",
	},
	{
		label: __("Separator 13", "magazine-blocks"),
		value: "bottom_separator_13",
		icon: "bottom_separator_icon_13",
	},
];
