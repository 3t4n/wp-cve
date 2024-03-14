import { Box, HStack, Switch, Text, useDisclosure } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import ZStack from "../../components/common/ZStack";
import ColorPicker from "../color-picker/ColorPicker";
import Reset from "../common/Reset";
import PopoverDrawer from "../popover-drawer/PopoverDrawer";
import Select from "../select/Select";
import Slider from "../slider/Slider";
import { BoxShadowProps } from "./types";

export const BoxShadow = ({
	value,
	onChange,
	resetAttributeKey,
}: BoxShadowProps) => {
	const { isOpen, onToggle, onClose } = useDisclosure();
	const update = (v: Record<string, any>) => {
		onChange({
			...(value ?? {}),
			...v,
		});
	};
	return (
		<PopoverDrawer
			label={__("Box Shadow", "magazine-blocks")}
			trigger={(props) => (
				<HStack>
					{value?.enable && <PopoverDrawer.Trigger {...props} />}
					<Switch
						isChecked={!!value?.enable}
						onChange={(e) => update({ enable: e.target.checked })}
					/>
				</HStack>
			)}
			closeOnFocusOutside
			popoverProps={{
				offset: 16,
			}}
		>
			<Box minW="280px">
				<HStack
					justify="space-between"
					borderBottom="1px"
					borderColor="gray.200"
					px="4"
				>
					<Text>{__("Box Shadow", "magazine-blocks")}</Text>
					{resetAttributeKey && (
						<Reset
							saved={value}
							resetKey={resetAttributeKey}
							onReset={(v) => {
								onChange({
									...(v ?? {}),
									enable: true,
								});
							}}
						/>
					)}
				</HStack>
				<Box
					p={"4"}
					sx={{
						">div:not(:last-child)": {
							mb: "2",
						},
					}}
				>
					<PopoverDrawer
						label={__("Color", "magazine-blocks")}
						closeOnFocusOutside
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{ id: "color", color: value?.color },
									]}
								/>
							</PopoverDrawer.Trigger>
						)}
						popoverDivProps={{
							sx: {
								".components-popover__content": {
									p: 3,
								},
							},
						}}
					>
						<ColorPicker
							value={value?.color}
							onChange={(v) => update({ color: v })}
						/>
					</PopoverDrawer>
					<Slider
						min={-100}
						max={100}
						label={__("Horizontal-X", "magazine-blocks")}
						onChange={(v) => update({ horizontalX: v })}
						value={value?.horizontalX}
						resetAttributeKey={
							resetAttributeKey
								? `${resetAttributeKey}.horizontalX`
								: undefined
						}
					/>
					<Slider
						min={-100}
						max={100}
						label={__("Vertical-Y", "magazine-blocks")}
						onChange={(v) => update({ verticalY: v })}
						value={value?.verticalY}
						resetAttributeKey={
							resetAttributeKey
								? `${resetAttributeKey}.verticalY`
								: undefined
						}
					/>
					<Slider
						min={0}
						max={100}
						label={__("Blur", "magazine-blocks")}
						onChange={(v) => update({ blur: v })}
						value={value?.blur}
						resetAttributeKey={
							resetAttributeKey
								? `${resetAttributeKey}.blur`
								: undefined
						}
					/>
					<Slider
						min={-100}
						max={100}
						label={__("Spread", "magazine-blocks")}
						onChange={(v) => update({ spread: v })}
						value={value?.spread}
						resetAttributeKey={
							resetAttributeKey
								? `${resetAttributeKey}.spread`
								: undefined
						}
					/>
					<Select
						options={[
							{
								label: __("Outset", "magazine-blocks"),
								value: "outset",
							},
							{
								label: __("Inset", "magazine-blocks"),
								value: "inset",
							},
						]}
						value={value?.position ?? "outset"}
						onChange={(v) => update({ position: v })}
						label={__("Position", "magazine-blocks")}
					/>
				</Box>
			</Box>
		</PopoverDrawer>
	);
};

export default BoxShadow;
