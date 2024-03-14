declare module "@wordpress/data" {
	export * from "@wordpress/data/build-types/index";
}

declare module "@wordpress/keycodes" {
	export * from "@wordpress/keycodes/build-types/index";
}

declare module "@wordpress/keyboard-shortcuts";

declare module "@wordpress/compose" {
	export * from "@wordpress/compose/build-types/index";
}

declare module "*.png" {
	const value: string;
	export default value;
}

declare var wp: any;

declare var pagenow: string | boolean;

declare module "countup.js" {
	export type CountUp = any;
	export var CountUp = any;
}

declare namespace JSX {
	interface IntrinsicElements {
		"lottie-player": any;
	}
}
