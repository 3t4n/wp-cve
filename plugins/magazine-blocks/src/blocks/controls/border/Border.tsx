import {
	Box,
	FormLabel,
	HStack,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Portal,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import { ColorPicker, Dimensions, PopoverDrawer } from "../../controls";
import {
	BORDER_OPTIONS,
	BORDER_STYLES,
	DEFAULT_BORDER_TYPE,
} from "./constants";
import { BorderProps } from "./types";

const Border = ({ onChange, value, resetAttributeKey }: BorderProps) => {
	const type = value?.type ?? DEFAULT_BORDER_TYPE;
	const TypeIcon = BORDER_STYLES[type];
	const update = (val: Record<string, any>) => {
		onChange?.({ ...(value ?? {}), ...val });
	};

	return (
		<Box>
			<HStack p="8px 0" justify="space-between">
				<FormLabel>{__("Type", "magazine-blocks")}</FormLabel>
				<Menu gutter={0} matchWidth>
					<MenuButton
						bg="gray.100"
						px="6"
						py="3"
						color="gray.600"
						_active={{
							bgColor: "primary.500",
							color: "white",
						}}
					>
						<TypeIcon w="10" />
					</MenuButton>
					<Portal>
						<MenuList
							borderRadius="none"
							borderBottomLeftRadius="sm"
							borderBottomRightRadius="sm"
							w="88px"
							minW="88px"
							p="0"
						>
							{BORDER_OPTIONS.filter(
								(o) =>
									o.value !==
									(value?.type ?? DEFAULT_BORDER_TYPE)
							).map((o) => {
								const Icon = BORDER_STYLES[o.value];
								return (
									<MenuItem
										px="6"
										py="3"
										key={o.value}
										onClick={() =>
											update({ type: o.value })
										}
										color="gray.600"
										_hover={{
											bgColor: "primary.500",
											color: "white",
										}}
									>
										<Icon w="10" />
									</MenuItem>
								);
							})}
						</MenuList>
					</Portal>
				</Menu>
			</HStack>

			{value?.type && "none" !== value.type && (
				<>
					<PopoverDrawer label={__("Color", "magazine-blocks")}>
						<ColorPicker
							onChange={(val) => update({ color: val })}
							value={value?.color}
						/>
					</PopoverDrawer>

					<Dimensions
						label={__("Size", "magazine-blocks")}
						responsive={true}
						units={["px", "rem", "em"]}
						defaultUnit="px"
						min={0}
						onChange={(val) => update({ size: val })}
						resetAttributeKey={
							resetAttributeKey
								? `${resetAttributeKey}.size`
								: undefined
						}
						value={value?.size as any}
					/>
				</>
			)}
			<Dimensions
				label={__("Radius", "magazine-blocks")}
				value={value?.radius as any}
				responsive
				units={["px", "em", "%"]}
				defaultUnit="px"
				min={0}
				onChange={(val) => update({ radius: val })}
				resetAttributeKey={
					resetAttributeKey
						? `${resetAttributeKey}.radius`
						: undefined
				}
			/>
		</Box>
	);
};

export default Border;
