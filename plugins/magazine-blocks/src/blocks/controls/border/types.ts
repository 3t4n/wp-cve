import { Responsive } from "../types/utils";

export type BorderProps = {
	value?: {
		type?: string;
		size?: Responsive<{
			value?: number;
			unit?: string;
		}>;
		radius?: Responsive<{
			value?: number;
			unit?: string;
		}>;
		color?: string;
	};
	onChange?: (val: BorderProps["value"]) => void;
	resetAttributeKey?: string;
};
