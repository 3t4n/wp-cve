import { MagazineBlocksLocalized } from "../../types";

export type IconType = MagazineBlocksLocalized["icons"]["magazine-blocks"][0];

export interface IconPickerPropsWithToggle {
	hasToggle: true;
	value:
		| {
				enable: boolean;
				icon: string | undefined;
		  }
		| undefined;
	onChange: (val: IconPickerPropsWithToggle["value"]) => void;
}

export interface IconPickerPropsWithoutToggle {
	hasToggle?: false;
	value: string | undefined;
	onChange: (val: string | undefined) => void;
}

export type IconPickerProps =
	| IconPickerPropsWithToggle
	| IconPickerPropsWithoutToggle;
