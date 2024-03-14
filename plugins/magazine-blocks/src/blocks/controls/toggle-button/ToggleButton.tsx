import {
	Button,
	ButtonGroup as ChakraButtonGroup,
	ButtonGroupProps,
	ButtonProps,
	FormControl,
	FormLabel,
	forwardRef,
	HStack,
} from "@chakra-ui/react";
import React from "react";
import useDevice from "../../hooks/useDevice";
import DevicePicker from "../common/DevicePicker";
import Reset from "../common/Reset";
import { ToggleButtonProps } from "./types";

export const ButtonGroup = forwardRef<ButtonGroupProps, "div">((props, ref) => (
	<ChakraButtonGroup
		ref={ref}
		w="full"
		justifyContent="space-between"
		{...props}
	/>
));

export const ToggleButton = forwardRef<ButtonProps, "button">((props, ref) => (
	<Button
		ref={ref}
		variant="icon"
		minH="10"
		h="auto"
		m="0 !important"
		w="full"
		px="4"
		color="gray.500"
		fontSize="sm"
		_active={{
			bgColor: "primary.500",
			color: "white",
		}}
		{...props}
	/>
));

export const ToggleButtonGroup = ({
	onChange,
	value,
	children,
	groupProps,
	responsive,
	label,
	resetAttributeKey,
}: ToggleButtonProps) => {
	const { device, setDevice } = useDevice();

	if (!children) throw new Error("Children required");
	return (
		<FormControl>
			<HStack align="center" justify="space-between" mb="2">
				<HStack align="center">
					<FormLabel>{label}</FormLabel>
					{responsive && (
						<DevicePicker device={device} setDevice={setDevice} />
					)}
				</HStack>
				{resetAttributeKey && (
					<Reset
						resetKey={resetAttributeKey}
						saved={value}
						onReset={(v) => {
							onChange(v);
						}}
					/>
				)}
			</HStack>
			<ButtonGroup
				p="4px"
				border="1px"
				borderRadius="sm"
				borderColor="#E0E0E0"
				{...groupProps}
			>
				{React.Children.map(
					children as React.ReactElement[],
					(child) => {
						return React.cloneElement(child, {
							onClick: () => {
								if (responsive) {
									onChange({
										...(value ?? {}),
										[device]:
											value?.[device] ===
											child?.props?.value
												? undefined
												: child?.props?.value,
									});
								} else {
									onChange(
										value === child?.props?.value
											? undefined
											: child?.props?.value
									);
								}
							},
							isActive: responsive
								? value?.[device] === child?.props?.value
								: value === child?.props?.value,
						});
					}
				)}
			</ButtonGroup>
		</FormControl>
	);
};

export default ToggleButtonGroup;
