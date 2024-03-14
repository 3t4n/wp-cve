import {
	FormControl,
	FormLabel,
	HStack,
	Select as ChakraSelect,
	Spinner,
} from "@chakra-ui/react";
import React from "react";
import {
	components,
	default as ReactSelect,
	DropdownIndicatorProps,
	StylesConfig,
} from "react-select";
import useDevice from "../../hooks/useDevice";
import { _set } from "../../utils";
import DevicePicker from "../common/DevicePicker";
import { reactSelectStyles } from "./styles";
import { SelectProps } from "./types";

export const Select = ({
	label,
	responsive,
	options,
	isMulti,
	hasSearch,
	placeholder,
	value,
	onChange,
}: SelectProps) => {
	const { device } = useDevice();

	const currentValue =
		isMulti && responsive
			? options.filter((o) => value?.[device]?.includes(o.value))
			: isMulti && !responsive
			? options.filter((o) =>
					(value as unknown as Array<string>)?.includes(o.value)
			  )
			: !isMulti && responsive
			? options.find((o) => o.value === value?.[device])
			: options.find((o) => o.value === value);

	return (
		<FormControl>
			<HStack mb="2">
				<FormLabel>{label}</FormLabel>
				{responsive && <DevicePicker />}
			</HStack>
			{isMulti || hasSearch ? (
				<ReactSelect
					isSearchable={hasSearch}
					isMulti={isMulti}
					styles={reactSelectStyles as unknown as StylesConfig}
					theme={(theme) => ({
						...theme,
						borderRadius: 2,
						colors: {
							...theme.colors,
							primary: "var(--chakra-colors-primary-500)",
						},
						spacing: {
							controlHeight: 30,
							baseUnit: 3,
							menuGutter: 3,
						},
					})}
					options={options}
					menuPortalTarget={document.body}
					placeholder={placeholder}
					controlShouldRenderValue
					components={{
						DropdownIndicator: (
							dropdownIndicatorProps: DropdownIndicatorProps
						) => (
							<components.DropdownIndicator
								{...dropdownIndicatorProps}
							>
								<svg
									viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg"
									fill="#000"
									width="18"
									height="18"
									className="components-panel__arrow"
									aria-hidden="true"
									focusable="false"
								>
									<path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
								</svg>
							</components.DropdownIndicator>
						),
						LoadingIndicator: () => <Spinner />,
					}}
					value={currentValue}
					onChange={(v: any) => {
						let nextValue;
						if (responsive && isMulti) {
							nextValue = _set(
								value ?? {},
								device,
								v.map((o: any) => o.value)
							);
						} else if (responsive && !isMulti) {
							nextValue = _set(value ?? {}, device, v.value);
						} else if (!responsive && isMulti) {
							nextValue = v.map(
								(o: any) => o.value
							) as Array<string>;
						} else {
							nextValue = v;
						}
						onChange(nextValue);
					}}
				/>
			) : (
				<ChakraSelect
					value={responsive ? value?.[device] : value}
					onChange={(e) => {
						const nextValue = e.target.value;
						if (responsive) {
							onChange(_set(value ?? {}, device, nextValue));
						} else {
							onChange(nextValue);
						}
					}}
					placeholder={placeholder}
				>
					{options.map((o) => (
						<option key={o.value} value={o.value}>
							{o.label}
						</option>
					))}
				</ChakraSelect>
			)}
		</FormControl>
	);
};

export default Select;
