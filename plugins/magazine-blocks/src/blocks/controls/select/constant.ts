import {
	ControlProps,
	IndicatorsContainerProps,
	InputProps,
	MenuListProps,
	MenuProps,
	ValueContainerProps,
} from "react-select";

export const STYLES = {
	indicatorSeparator: () => ({
		display: "none",
	}),
	indicatorsContainer: (provided: IndicatorsContainerProps) => ({
		...provided,
		maxHeight: "28px",
	}),
	menuPortal: (base: MenuProps) => ({
		...base,
		zIndex: 99999999999,
	}),
	control: (base: ControlProps) => ({
		...base,
		border: "1px solid #e2e8f0",
	}),
	valueContainer: (base: ValueContainerProps) => ({
		...base,
		padding: "0 6px",
	}),
	input: (base: InputProps) => ({
		...base,
		margin: 0,
		padding: 0,
	}),
	menuList: (base: MenuListProps) => ({
		...base,
		maxHeight: "200px",
	}),
};
