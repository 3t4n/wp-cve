import { useDebounceCallback } from "@blocks/hooks";
import {
	Box,
	Button,
	chakra,
	Flex,
	FormLabel,
	HStack,
	Select,
	Tooltip,
	useClipboard,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { colord, getFormat } from "colord";
import { Format } from "colord/types";
import React, { useRef, useState } from "react";
import { HexAlphaColorPicker, HslaColor, RgbaColor } from "react-colorful";
import useGlobalStyles from "../../hooks/useGlobalStyles";
import { DEFAULT_FORMAT } from "./constants";
import HexInput from "./HexInput";
import HslInput from "./HslInput";
import RgbInput from "./RgbInput";
import { reactColorfulStyles } from "./styles";
import { ColorPickerProps } from "./types";
import { parseColor, serializeColor } from "./utils";

export const ColorPicker = ({
	onChange,
	value = "#000000",
	showGlobalPalette = true,
}: ColorPickerProps) => {
	const [color, setColor] = useState(parseColor(value));
	const [format, setFormat] = useState<Format>(
		() => getFormat(value) ?? DEFAULT_FORMAT
	);
	const { onCopy, hasCopied } = useClipboard(serializeColor(color, format));
	const boxRef = useRef<any>();
	const debouncedOnChange = useDebounceCallback(onChange, 400);
	const { styles } = useGlobalStyles();

	const update = (v: string | RgbaColor | HslaColor) => {
		const _colord = colord(v);
		setColor(() => {
			const newColor = {
				hex: typeof v === "string" ? v : _colord.toHex(),
				rgb: _colord.toRgb(),
				hsl: _colord.toHsl(),
			};
			debouncedOnChange(serializeColor(newColor, format));
			return newColor;
		});
	};

	React.useEffect(() => {
		setColor(parseColor(value));
		setFormat(getFormat(value) ?? DEFAULT_FORMAT);
	}, [value]);

	return (
		<Box ref={boxRef} sx={reactColorfulStyles}>
			<HexAlphaColorPicker
				color={color.hex}
				onChange={(v) => {
					update(v);
				}}
			/>

			<HStack gap="8px" mt="16px" maxW="254px">
				<Select
					defaultValue={format}
					onChange={(e) => {
						const val = e.target.value as Format;
						setFormat(val);
					}}
					w="40px"
					iconSize="16px"
					sx={{
						"+ .chakra-select__icon-wrapper": {
							w: "14px",
							right: "0",
						},
					}}
				>
					<option value="hex">{__("Hex", "magazine-blocks")}</option>
					<option value="rgb">{__("RGB", "magazine-blocks")}</option>
					<option value="hsl">{__("HSL", "magazine-blocks")}</option>
				</Select>
				<Box
					flex={format === "hex" ? "0" : "1"}
					mr={format === "hex" ? "auto" : undefined}
					minW={format === "hex" ? "120px" : undefined}
				>
					{format === "hex" ? (
						<HexInput
							color={color.hex}
							onChange={(v) => update(v)}
						/>
					) : format === "hsl" ? (
						<HslInput
							color={color.hsl}
							onChange={(v) => update(v)}
						/>
					) : (
						<RgbInput
							color={color.rgb}
							onChange={(v) => update(v)}
						/>
					)}
				</Box>
				<Tooltip
					label={
						hasCopied
							? __("Copied", "magazine-blocks")
							: __("Copy", "magazine-blocks")
					}
					placement="top"
					portalProps={{
						containerRef: boxRef,
					}}
					closeOnClick={false}
					hasArrow
				>
					<Button variant="icon" onClick={onCopy}>
						<svg
							xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 24 24"
							width="24"
							height="24"
							aria-hidden="true"
							focusable="false"
						>
							<path d="M20.2 8v11c0 .7-.6 1.2-1.2 1.2H6v1.5h13c1.5 0 2.7-1.2 2.7-2.8V8zM18 16.4V4.6c0-.9-.7-1.6-1.6-1.6H4.6C3.7 3 3 3.7 3 4.6v11.8c0 .9.7 1.6 1.6 1.6h11.8c.9 0 1.6-.7 1.6-1.6zm-13.5 0V4.6c0-.1.1-.1.1-.1h11.8c.1 0 .1.1.1.1v11.8c0 .1-.1.1-.1.1H4.6l-.1-.1z"></path>
						</svg>
					</Button>
				</Tooltip>
			</HStack>
			{showGlobalPalette && (
				<Flex flexWrap="wrap" gap="2" mt="4">
					<FormLabel w="full" mb="2">
						{__("Global Colors", "magazine-blocks")}
					</FormLabel>
					{styles.colors.map((v) => {
						return (
							<Tooltip
								key={v.id}
								label={v.name}
								placement="top"
								hasArrow
							>
								<Box
									w="20px"
									h="20px"
									borderRadius="full"
									border="1px"
									borderColor="gray.200"
									bgColor={v.value}
									onClick={() =>
										onChange(`var(--mzb-colors-${v.id})`)
									}
								>
									{value === `var(--mzb-colors-${v.id})` && (
										<chakra.svg
											stroke="currentColor"
											fill="white"
											strokeWidth="0"
											viewBox="0 0 512 512"
											height="18px"
											width="18px"
											xmlns="http://www.w3.org/2000/svg"
										>
											<path d="M186.301 339.893L96 249.461l-32 30.507L186.301 402 448 140.506 416 110z"></path>
										</chakra.svg>
									)}
								</Box>
							</Tooltip>
						);
					})}
				</Flex>
			)}
		</Box>
	);
};

export default ColorPicker;
