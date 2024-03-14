export type GeneratorArgs = {
	settingValue: any;
	settingDef: {
		style: StylesDef;
		[key: string]: any;
	};
	blockName: string;
	blockID: string;
	context: string;
	settings: {
		[key: string]: any;
	};
	settingName: string;
};

export type StylesDef = Array<{
	selector: string;
	condition?: Array<{
		key: string;
		relation: string;
		value?: string | number | boolean | Array<string | number | boolean>;
	}>;
}>;

export type ResponsiveCSS = {
	allDevice: Array<string>;
	desktop: Array<string>;
	tablet: Array<string>;
	mobile: Array<string>;
};
