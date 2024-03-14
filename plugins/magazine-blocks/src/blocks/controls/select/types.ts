import { Responsive } from "../types/utils";

export interface BaseSelectProps {
	label: string;
	options: Array<{
		label: string;
		value: string;
	}>;
	hasSearch?: boolean;
	placeholder?: string;
}

export interface StaticSelectProps extends BaseSelectProps {
	responsive?: false;
	isMulti?: false;
	value?: string;
	onChange: (value: string) => void;
}

export interface ResponsiveSelectProps extends BaseSelectProps {
	responsive: true;
	isMulti?: false;
	value?: Responsive<string>;
	onChange: (value: Responsive<string>) => void;
}

export interface StaticMultiSelectProps extends BaseSelectProps {
	responsive?: false;
	isMulti: true;
	value?: string[];
	onChange: (value: StaticMultiSelectProps["value"]) => void;
}

export interface ResponsiveMultiSelectProps extends BaseSelectProps {
	responsive: true;
	isMulti: true;
	value?: Responsive<string[]>;
	onChange: (value: ResponsiveMultiSelectProps["value"]) => void;
}

export type SelectProps =
	| StaticSelectProps
	| ResponsiveSelectProps
	| StaticMultiSelectProps
	| ResponsiveMultiSelectProps;
