import { localized } from "@blocks/utils";
import {
	Box,
	FormControl,
	FormLabel,
	List,
	ListItem,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import ReactSelect, { components } from "react-select";
import { Global } from "../../components/icons/Icons";
import useGlobalStyles from "../../hooks/useGlobalStyles";
import Reset from "../common/Reset";
import { PopoverDrawer, Select } from "../index";
import Slider from "../slider/Slider";
import { reactSelectStyles } from "./styles";
import { TypographyProps } from "./types";

export const RawTypography = (props: TypographyProps) => {
	const { value, onChange, resetAttributeKey } = props;
	const selectiveFonts =
		localized.configs.performance["allow-only-selected-fonts"];

	const setSetting = (val: { [key: string]: any }) => {
		onChange({
			...(value ?? {}),
			...val,
			_className: undefined,
		});
	};

	const selectedFonts = [
		{
			label: "Default",
			value: "Default",
			variants: [
				"100",
				"200",
				"300",
				"400",
				"500",
				"600",
				"700",
				"700",
				"800",
				"900",
			],
			family: "Default",
			defVariant: "400",
		},
		...localized.configs.performance["allowed-fonts"],
	];

	const weights =
		(selectiveFonts ? selectedFonts : localized.googleFonts)
			?.find((f) => f.family === (value?.family ?? "Default"))
			?.variants?.map((v) => {
				if (v === "regular") {
					return "400";
				}
				const newV = parseInt(v);
				if (isNaN(newV)) {
					return false;
				}

				return newV.toString();
			})
			?.filter((v, i, arr) => !v || arr.indexOf(v) === i) ??
		selectedFonts[0].variants;

	const updateFamily = (val: string | string[]) => {
		let newVal: {
			[key: string]: any;
		} = {
			family: val,
		};
		const newFamily = (
			selectiveFonts ? selectedFonts : localized.googleFonts
		)?.find((v) => v.family === val);
		if (newFamily) {
			const weights = newFamily.variants.map((v: string) => {
				if (v == "regular") {
					return "400";
				}
				return parseInt(v).toString();
			});
			let currentWeight = value?.weight ?? "400";
			currentWeight = Number.isSafeInteger(currentWeight)
				? currentWeight.toString()
				: currentWeight;
			if (!weights.includes(currentWeight as string)) {
				if (weights.includes("400")) {
					newVal.weight = "400";
				} else {
					newVal.weight = weights.includes("400")
						? "400"
						: weights[0];
				}
			}
		}
		setSetting(newVal);
	};
	return (
		<Box
			sx={{
				"> *:not(:last-child)": {
					mb: 4,
				},
			}}
		>
			<FormControl
				sx={{
					".magazine-blocks-react-select input": {
						boxShadow: "none !important",
					},
				}}
			>
				<FormLabel mb="2">
					{__("Font family", "magazine-blocks")}
				</FormLabel>
				<ReactSelect
					className="magazine-blocks-react-select"
					options={
						selectiveFonts ? selectedFonts : localized.googleFonts
					}
					isSearchable
					getOptionLabel={(v: any) => v.family}
					getOptionValue={(v: any) => v.family}
					value={(selectiveFonts
						? selectedFonts
						: localized.googleFonts
					).find(
						(v) =>
							v.family ===
							(selectiveFonts &&
							value?.family &&
							!selectedFonts.some(
								(v) => v.family === value?.family
							)
								? "Default"
								: value?.family ?? "Default")
					)}
					onChange={(v: any) => {
						updateFamily(v?.family);
					}}
					theme={(provided) => ({
						borderRadius: 2,
						colors: {
							...provided.colors,
							primary: "var(--wp-admin-theme-color)",
						},
						spacing: {
							controlHeight: 40,
							baseUnit: 3,
							menuGutter: 3,
						},
					})}
					styles={reactSelectStyles}
					components={{
						DropdownIndicator: (dropdownIndicatorProps) => (
							<components.DropdownIndicator
								{...dropdownIndicatorProps}
							>
								<svg
									viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg"
									fill="#000"
									width="22"
									height="22"
									className="components-panel__arrow"
									aria-hidden="true"
									focusable="false"
								>
									<path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
								</svg>
							</components.DropdownIndicator>
						),
					}}
				/>
			</FormControl>
			<Select
				label={__("Weight", "magazine-blocks")}
				onChange={(val) => {
					setSetting({
						weight: val,
					});
				}}
				value={(value?.weight ?? "400").toString()}
				options={(weights as Array<string>)?.map((v) => ({
					label: v,
					value: v,
				}))}
			/>
			<Slider
				responsive
				value={value?.size}
				onChange={(v: any) => {
					setSetting({
						size: v,
					});
				}}
				min={0}
				max={500}
				units={["px", "em", "rem"]}
				defaultUnit="px"
				label={__("Font Size", "magazine-blocks")}
				resetAttributeKey={
					resetAttributeKey ? `${resetAttributeKey}.size` : undefined
				}
			/>
			<Slider
				responsive
				value={value?.lineHeight}
				onChange={(v: any) => {
					setSetting({
						lineHeight: v,
					});
				}}
				min={0}
				max={100}
				units={["px", "em", "rem"]}
				defaultUnit="px"
				label={__("Line height", "magazine-blocks")}
				resetAttributeKey={
					resetAttributeKey
						? `${resetAttributeKey}.lineHeight`
						: undefined
				}
			/>
			<Slider
				responsive
				value={value?.letterSpacing}
				onChange={(v: any) => {
					setSetting({
						letterSpacing: v,
					});
				}}
				min={0}
				max={100}
				units={["px", "em", "rem"]}
				defaultUnit="px"
				label={__("Letter spacing", "magazine-blocks")}
				resetAttributeKey={
					resetAttributeKey
						? `${resetAttributeKey}.letterSpacing`
						: undefined
				}
			/>
			<Select
				label={__("Style", "magazine-blocks")}
				onChange={(val) => setSetting({ fontStyle: val })}
				value={value?.fontStyle ?? "default"}
				options={[
					{
						label: __("Default", "magazine-blocks"),
						value: "default",
					},
					{
						label: __("Italic", "magazine-blocks"),
						value: "italic",
					},
					{
						label: __("Oblique", "magazine-blocks"),
						value: "oblique",
					},
				]}
			/>
			<Select
				label={__("Decoration", "magazine-blocks")}
				onChange={(val) =>
					setSetting({
						decoration: val,
					})
				}
				value={value?.decoration ?? "default"}
				options={[
					{
						label: __("Default", "magazine-blocks"),
						value: "default",
					},
					{
						label: __("Overline", "magazine-blocks"),
						value: "overline",
					},
					{
						label: __("Underline", "magazine-blocks"),
						value: "underline",
					},
					{
						label: __("Line Through", "magazine-blocks"),
						value: "line-through",
					},
				]}
			/>
			<Select
				label={__("Transformation", "magazine-blocks")}
				onChange={(val) =>
					setSetting({
						transform: val,
					})
				}
				value={value?.transform ?? "default"}
				options={[
					{
						label: __("Default", "magazine-blocks"),
						value: "default",
					},
					{
						label: __("Capitalize", "magazine-blocks"),
						value: "capitalize",
					},
					{
						label: __("Uppercase", "magazine-blocks"),
						value: "uppercase",
					},
					{
						label: __("Lowercase", "magazine-blocks"),
						value: "lowercase",
					},
				]}
			/>
		</Box>
	);
};

const Typography = (props: TypographyProps) => {
	const { styles } = useGlobalStyles();
	return (
		<PopoverDrawer
			label={__("Typography", "magazine-blocks")}
			closeOnFocusOutside
			popoverDivProps={{
				sx: {
					".components-popover__content": {
						minW: "280px",
					},
				},
			}}
			trigger={
				props.value?._className
					? (triggerProps) => (
							<Box position="relative">
								<PopoverDrawer.Trigger
									{...triggerProps}
								></PopoverDrawer.Trigger>
								<Global
									position="absolute"
									fill="primary.500"
									height="14px"
									width="14px"
									top="-5px"
									left="-5px"
									bg="white"
									borderRadius="full"
								/>
							</Box>
					  )
					: undefined
			}
		>
			<Tabs defaultIndex={props.value?._className ? 1 : undefined}>
				<TabList position="relative">
					<Tab fontSize="xs">{__("Default", "magazine-blocks")}</Tab>
					<Tab fontSize="xs">{__("Global", "magazine-blocks")}</Tab>
					{props.resetAttributeKey && (
						<Reset
							saved={props.value}
							resetKey={props.resetAttributeKey}
							onReset={(val) => {
								props.onChange(val);
							}}
							buttonProps={{
								position: "absolute",
								right: "10px",
								top: "50%",
								transform: "translateY(-50%)",
							}}
						/>
					)}
				</TabList>
				<TabPanels>
					<TabPanel>
						<RawTypography {...props} />
					</TabPanel>
					<TabPanel>
						<List>
							{styles.typographies.map((typography) => (
								<ListItem
									border={
										`mzb-typography-${typography.id}` ===
										props.value?._className
											? "1px"
											: undefined
									}
									borderColor="primary.500"
									mb="0"
									px="8px"
									py="10px"
									key={typography.id}
									onClick={() => {
										props.onChange({
											...(props.value ?? {}),
											_className: `mzb-typography-${typography.id}`,
										});
									}}
									cursor="pointer"
									borderRadius="sm"
								>
									{typography.name}
								</ListItem>
							))}
						</List>
					</TabPanel>
				</TabPanels>
			</Tabs>
		</PopoverDrawer>
	);
};

export default Typography;
