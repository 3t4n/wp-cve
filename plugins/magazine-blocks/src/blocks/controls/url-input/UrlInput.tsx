import { FormControl, FormLabel } from "@chakra-ui/react";
import { URLInput as WPURLInput } from "@wordpress/block-editor";
import { BaseControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import React from "react";
import Toggle from "../toggle/Toggle";
import { URLInputProps } from "./types";

const URLInput: React.FC<URLInputProps> = ({
	value,
	label = __("URL", "magazine-blocks"),
	onChange,
	newTab = false,
	noFollow = false,
	...otherProps
}) => {
	const setSettings = (type: string, val?: string) => {
		const data =
			"newTab" === type || "noFollow" === type
				? { [type]: !value?.[type] }
				: { [type]: val };
		onChange?.({
			...(value ?? {}),
			...data,
		});
	};

	return (
		<FormControl>
			<FormLabel>{label}</FormLabel>
			{/* @ts-ignore */}
			<BaseControl>
				<WPURLInput
					value={value?.url ?? ""}
					onChange={(val) => setSettings("url", val)}
					autoFocus={false}
					disableSuggestions
					{...otherProps}
				/>
			</BaseControl>
			{newTab && (
				<Toggle
					checked={value?.newTab ?? false}
					onChange={() => setSettings("newTab")}
					label={__("Open Link in a New Tab", "magazine-blocks")}
				/>
			)}
			{noFollow && (
				<Toggle
					checked={value?.noFollow ?? false}
					onChange={() => setSettings("noFollow")}
					label={__("Nofollow Link", "magazine-blocks")}
				/>
			)}
		</FormControl>
	);
};

export default URLInput;
