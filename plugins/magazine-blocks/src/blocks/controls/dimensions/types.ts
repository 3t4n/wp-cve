import { Unit } from "../types/units";
import { Maybe, Measurement } from "../types/utils";

export type Value = Number | Measurement;

export interface Sides {
	top?: Value;
	right?: Value;
	bottom?: Value;
	left?: Value;
	unit?: string;
	lock?: boolean;
}

export interface ResponsiveSides {
	desktop: Maybe<Sides>;
	tablet: Maybe<Sides>;
	mobile: Maybe<Sides>;
}

export interface Responsive<T> {
	desktop?: Maybe<T>;
	tablet?: Maybe<T>;
	mobile?: Maybe<T>;
}

export interface BaseDimensionProps {
	label: string;
	units?: Array<Unit>;
	min?: number;
	max?: number;
	step?: number;
	defaultUnit?: string;
	type?: string;
	resetAttributeKey?: string;
	isRadius?: boolean;
}

interface ResponsiveDimensionsProps extends BaseDimensionProps {
	responsive: true;
	value: Responsive<Sides>;
	onChange: (value: Responsive<Sides>) => void;
}

interface StaticDimensionsProps extends BaseDimensionProps {
	responsive?: false;
	value: Sides;
	onChange: (value: Sides) => void;
}

export type DimensionsProps = ResponsiveDimensionsProps | StaticDimensionsProps;
