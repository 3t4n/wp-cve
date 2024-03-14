import { ButtonGroupProps } from "@chakra-ui/react";
import { MagazineBlocksLocalized } from "../../types";

export type IconType = MagazineBlocksLocalized["icons"]["magazine-blocks"][0];

export interface BaseToggleButtonProps {
	label: string;
	resetAttributeKey?: string;
	groupProps?: ButtonGroupProps;
	children: React.ReactNode;
}

export interface ResponsiveToggleButtonProps extends BaseToggleButtonProps {
	responsive: true;
	value:
		| {
				[key: string]: string | undefined;
		  }
		| undefined;
	onChange: (val: ResponsiveToggleButtonProps["value"]) => void;
}

export interface StaticToggleButtonProps extends BaseToggleButtonProps {
	responsive?: false;
	value: string | undefined;
	onChange: (val: string | undefined) => void;
}

export type ToggleButtonProps =
	| ResponsiveToggleButtonProps
	| StaticToggleButtonProps;
