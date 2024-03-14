import { ResponsiveValueWithUnit } from "../../components/types";

export type TypographyProps = {
	value?: {
		family?: string;
		size?: ResponsiveValueWithUnit;
		weight?: number | string;
		lineHeight?: ResponsiveValueWithUnit;
		decoration?: string;
		transform?: string;
		fontStyle?: string;
		letterSpacing?: ResponsiveValueWithUnit;
		_className?: string;
	};
	onChange: (value: TypographyProps["value"]) => void;
	resetAttributeKey?: string;
};
