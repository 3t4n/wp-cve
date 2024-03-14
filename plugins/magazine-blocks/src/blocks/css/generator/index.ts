import { ResponsiveCSS } from "../types";
import advancedCSS from "./advanced-css";
import backgroundCSS from "./background-css";
import borderCSS from "./border-css";
import boxShadowCSS from "./box-shadow-css";
import commonCSS from "./common-css";
import dimensionCSS from "./dimension-css";
import positionCSS from "./position-css";
import separatorCSS from "./separator-css";
import typographyCSS from "./typography-css";

const generator = {
	empty: (): ResponsiveCSS => ({
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	}),
	border: borderCSS,
	dimension: dimensionCSS,
	background: backgroundCSS,
	boxShadow: boxShadowCSS,
	typography: typographyCSS,
	separator: separatorCSS,
	advanced: advancedCSS,
	common: commonCSS,
	position: positionCSS,
};

export default generator;
