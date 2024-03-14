export type URLInputValue = {
	url?: string;
	newTab?: boolean;
	noFollow?: boolean;
};

export type URLInputProps = {
	value?: URLInputValue;
	label?: string;
	onChange?: (val: URLInputValue) => void;
	newTab?: boolean;
	noFollow?: boolean;
	[k: string]: unknown;
};
