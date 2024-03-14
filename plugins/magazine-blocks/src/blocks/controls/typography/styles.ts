import { StylesConfig } from "react-select";

export const reactSelectStyles = {
	indicatorSeparator: () => ({
		display: "none",
	}),
	indicatorsContainer: (provided: any) => ({
		...provided,
		maxHeight: "40px",
	}),
	menuPortal: (provided: any) => ({
		...provided,
		zIndex: 99999999999,
	}),
	control: (provided: any) => ({
		...provided,
		border: "1px solid var(--chakra-colors-gray-400)",
	}),
	valueContainer: (provided: any) => ({
		...provided,
		padding: "0 8px",
	}),
	input: (provided: any) => ({
		...provided,
		margin: 0,
		padding: 0,
		":focus": {
			boxShadow: "none !important",
		},
	}),
	menuList: (provided: any) => ({
		...provided,
		maxHeight: "200px",
	}),
	menu: (provided: any) => ({
		...provided,
		zIndex: 2,
	}),
} as StylesConfig;
