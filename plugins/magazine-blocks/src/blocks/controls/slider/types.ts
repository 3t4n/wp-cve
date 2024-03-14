import { Unit } from "../types/units";
import { Measurement, Responsive } from "../types/utils";

type BaseSliderProps = {
	label: string;
	min?: number;
	max?: number;
	step?: number;
	resetAttributeKey?: string;
	defaultUnit?: string;
	hiddenLabel?: boolean;
	forceShowUnit?: boolean;
};

type ResponsiveSliderWithUnitsProps = BaseSliderProps & {
	responsive: true;
	units: Array<Unit>;
	value: Responsive<Measurement> | undefined;
	onChange: (val: ResponsiveSliderWithUnitsProps["value"]) => void;
};

type ResponsiveSliderWithoutUnitsProps = BaseSliderProps & {
	responsive: true;
	units?: undefined;
	value: Responsive<number> | undefined;
	onChange: (val: ResponsiveSliderWithoutUnitsProps["value"]) => void;
};

type StaticSliderWithUnitsProps = BaseSliderProps & {
	responsive?: false;
	units: Array<Unit>;
	value: Measurement | undefined;
	onChange: (val: StaticSliderWithUnitsProps["value"]) => void;
};

type StaticSliderWithoutUnitsProps = BaseSliderProps & {
	responsive?: false;
	units?: undefined;
	value: number | undefined;
	onChange: (val: StaticSliderWithoutUnitsProps["value"]) => void;
};

export type SliderProps =
	| StaticSliderWithUnitsProps
	| StaticSliderWithoutUnitsProps
	| ResponsiveSliderWithUnitsProps
	| ResponsiveSliderWithoutUnitsProps;
