export interface ColorPickerProps {
	label?: string;
	value?: string;
	onChange: (value: string) => void;
	resetKey?: string;
	showGlobalPalette?: boolean;
}
