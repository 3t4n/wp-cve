import { Box, Tab, TabList, TabPanel, TabPanels, Tabs } from "@chakra-ui/react";
import { TextareaControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import React from "react";
import AdvancedControls from "../../../components/common/AdvancedControls";
import InspectorTabs, {
	AdvancedInspectorControls,
	GeneralInspectorControls,
	StyleInspectorControls,
} from "../../../components/common/InspectorTabs";
import Panel from "../../../components/common/Panel";
import ResponsiveControls from "../../../components/common/ResponsiveControls";
import ZStack from "../../../components/common/ZStack";
import {
	HeadingLayout1Style1,
	HeadingLayout2Style1,
	TextAlignCenter,
	TextAlignLeft,
	TextAlignRight,
} from "../../../components/icons/Icons";
import SidebarDrawer from "../../../components/sidebar-drawer/SidebarDrawer";
import {
	Border,
	BoxShadow,
	ColorPicker,
	Dimensions,
	PopoverDrawer,
	Select,
	Toggle,
	ToggleButton,
	ToggleButtonGroup,
	Typography,
} from "../../../controls";
import Reset from "../../../controls/common/Reset";
import { toggleButtonPresetStyles } from "../../../styles/styles";

type Props = {
	attributes: any;
	setAttributes: (attributes: any) => void;
};

const InspectorControls: React.ComponentType<Props> = (props) => {
	const {
		attributes: {
			enableSubHeading,
			label,
			subHeadingColor,
			subHeadingHoverColor,
			subHeadingBackground,
			subHeadingHoverBackground,
			subHeadingPadding,
			subHeadingBorder,
			subHeadingBoxShadow,
			subHeadingHoverBorder,
			subHeadingBoxShadowHover,

			headingLayout1AdvancedStyle,
			headingLayout2AdvancedStyle,
			headingLayout,
			blockPadding,
			blockMargin,
			alignment,
			size,
			type,
			typeHover,
			borderBottomColorHover,
			sizeHover,
			color,
			borderBottomColor,
			margin,
			markup,
			background,
			hoverColor,
			hoverBackground,
			typography,
			hideOnDesktop,
			hideOnTablet,
			hideOnMobile,
		},
		setAttributes,
	} = props;
	return (
		<>
			<InspectorTabs />
			<GeneralInspectorControls>
				<Panel title={__("General", "magazine-blocks")} initialOpen>
					<ToggleButtonGroup
						value={alignment}
						onChange={(v) => {
							setAttributes({ alignment: v });
						}}
						label={__("Text Alignment", "magazine-blocks")}
						responsive
						resetAttributeKey="alignment"
						groupProps={{
							sx: {
								".chakra-button": {
									minH: "32px",
									svg: {
										w: 6,
										h: 6,
									},
								},
							},
						}}
					>
						<ToggleButton value="left">
							<TextAlignLeft />
						</ToggleButton>
						<ToggleButton value="center">
							<TextAlignCenter />
						</ToggleButton>
						<ToggleButton value="right">
							<TextAlignRight />
						</ToggleButton>
					</ToggleButtonGroup>
					<SidebarDrawer label="Preset">
						<Select
							options={[
								{
									label: __("Layout 1", "magazine-blocks"),
									value: "heading-layout-1",
								},
								{
									label: __("Layout 2", "magazine-blocks"),
									value: "heading-layout-2",
								},
							]}
							onChange={(val) =>
								setAttributes({ headingLayout: val })
							}
							value={headingLayout}
							label={__("Layout", "magazine-blocks")}
						/>
						{"heading-layout-1" === headingLayout && (
							<Box borderTop="1px solid #E0E0E0">
								<ToggleButtonGroup
									responsive={false}
									value={headingLayout1AdvancedStyle}
									onChange={(val) =>
										setAttributes({
											headingLayout1AdvancedStyle: val,
										})
									}
									label={__("Preset")}
									groupProps={toggleButtonPresetStyles}
								>
									{[
										{
											label: __(
												"Style 1",
												"magazine-blocks"
											),
											value: "heading-layout-1-style-1",
											icon: HeadingLayout1Style1,
										},
									].map((item) => (
										<ToggleButton
											key={item.value}
											value={item.value}
										>
											<item.icon />
											{
												// @ts-ignore
												item.label
											}
										</ToggleButton>
									))}
								</ToggleButtonGroup>
							</Box>
						)}
						{"heading-layout-2" === headingLayout && (
							<Box borderTop="1px solid #E0E0E0">
								<ToggleButtonGroup
									responsive={false}
									value={headingLayout2AdvancedStyle}
									onChange={(val) =>
										setAttributes({
											headingLayout2AdvancedStyle: val,
										})
									}
									label={__("Preset")}
									groupProps={toggleButtonPresetStyles}
								>
									{[
										{
											label: __(
												"Style 1",
												"magazine-blocks"
											),
											value: "heading-layout-2-style-1",
											icon: HeadingLayout2Style1,
										},
									].map((item) => (
										<ToggleButton
											key={item.value}
											value={item.value}
										>
											<item.icon />
											{
												// @ts-ignore
												item.label
											}
										</ToggleButton>
									))}
								</ToggleButtonGroup>
							</Box>
						)}
					</SidebarDrawer>
					<Select
						options={[
							{
								label: __("H1", "magazine-blocks"),
								value: "h1",
							},
							{
								label: __("H2", "magazine-blocks"),
								value: "h2",
							},
							{
								label: __("H3", "magazine-blocks"),
								value: "h3",
							},
							{
								label: __("H4", "magazine-blocks"),
								value: "h4",
							},
							{
								label: __("H5", "magazine-blocks"),
								value: "h5",
							},
							{
								label: __("H6", "magazine-blocks"),
								value: "h6",
							},
						]}
						onChange={(val) => setAttributes({ markup: val })}
						value={markup}
						label={__("HTML Markup", "magazine-blocks")}
					/>
				</Panel>
				<Panel title={__("Sub Heading", "magazine-blocks")}>
					<Toggle
						checked={enableSubHeading}
						onChange={() =>
							setAttributes({
								enableSubHeading: !enableSubHeading,
							})
						}
						label={__("Enable", "magazine-blocks")}
					/>
					{enableSubHeading && (
						<TextareaControl
							onChange={(val) => setAttributes({ label: val })}
							label={__("Text", "magazine-blocks")}
							value={label || ""}
						/>
					)}
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("Heading", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{ color: color, id: "color" },
										{ color: hoverColor, id: "hoverColor" },
									]}
								/>
							</PopoverDrawer.Trigger>
						)}
						closeOnFocusOutside
					>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
								<Tab>{__("Hover", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={color}
										resetKey="color"
										onReset={(v) => {
											setAttributes({ color: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={color}
										onChange={(v) =>
											setAttributes({ color: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={hoverColor}
										resetKey="hoverColor"
										onReset={(v) => {
											setAttributes({ hoverColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={hoverColor}
										onChange={(v) =>
											setAttributes({ hoverColor: v })
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					{"heading-layout-2" === headingLayout && (
						<PopoverDrawer
							label={__("Background Color", "magazine-blocks")}
							trigger={(props) => (
								<PopoverDrawer.Trigger {...props}>
									<ZStack
										items={[
											{
												color: background,
												id: "background",
											},
										]}
									/>
								</PopoverDrawer.Trigger>
							)}
							closeOnFocusOutside
						>
							<Tabs position="relative">
								<TabList>
									<Tab>{__("Normal", "magazine-blocks")}</Tab>
								</TabList>
								<TabPanels>
									<TabPanel>
										<Reset
											saved={background}
											resetKey="background"
											onReset={(v) => {
												setAttributes({
													background: v,
												});
											}}
											buttonProps={{
												position: "absolute",
												top: "12px",
												right: "10px",
											}}
										/>
										<ColorPicker
											value={background}
											onChange={(v) =>
												setAttributes({ background: v })
											}
										/>
									</TabPanel>
								</TabPanels>
							</Tabs>
						</PopoverDrawer>
					)}
					<PopoverDrawer
						label={__("Border Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: borderBottomColor,
											id: "borderBottomColor",
										},
									]}
								/>
							</PopoverDrawer.Trigger>
						)}
						closeOnFocusOutside
					>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={borderBottomColor}
										resetKey="borderBottomColor"
										onReset={(v) => {
											setAttributes({
												borderBottomColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={borderBottomColor}
										onChange={(v) =>
											setAttributes({
												borderBottomColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<Typography
						value={typography}
						onChange={(val) => setAttributes({ typography: val })}
					/>
				</Panel>
				<Panel title={__("Sub Heading", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: subHeadingColor,
											id: "categoryTitleColor",
										},
										{
											color: subHeadingHoverColor,
											id: "categoryTitleHoverColor",
										},
									]}
								/>
							</PopoverDrawer.Trigger>
						)}
						closeOnFocusOutside
					>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
								<Tab>{__("Hover", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={subHeadingColor}
										resetKey="subHeadingColor"
										onReset={(v) => {
											setAttributes({
												subHeadingColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={subHeadingColor}
										onChange={(v) =>
											setAttributes({
												subHeadingColor: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={subHeadingHoverColor}
										resetKey="subHeadingHoverColor"
										onReset={(v) => {
											setAttributes({
												subHeadingHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={subHeadingHoverColor}
										onChange={(v) =>
											setAttributes({
												subHeadingHoverColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<PopoverDrawer
						label={__("Background Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: subHeadingBackground,
											id: "subHeadingBackground",
										},
										{
											color: subHeadingHoverBackground,
											id: "subHeadingHoverBackground",
										},
									]}
								/>
							</PopoverDrawer.Trigger>
						)}
						closeOnFocusOutside
					>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
								<Tab>{__("Hover", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={subHeadingBackground}
										resetKey="subHeadingBackground"
										onReset={(v) => {
											setAttributes({
												subHeadingBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={subHeadingBackground}
										onChange={(v) =>
											setAttributes({
												subHeadingBackground: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={subHeadingHoverBackground}
										resetKey="countHoverBackground"
										onReset={(v) => {
											setAttributes({
												subHeadingHoverBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={subHeadingHoverBackground}
										onChange={(v) =>
											setAttributes({
												subHeadingHoverBackground: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					{enableSubHeading && (
						<Dimensions
							value={subHeadingPadding}
							responsive
							label={__("Padding", "magazine-blocks")}
							type="padding"
							defaultUnit="px"
							units={["px", "rem", "em", "%"]}
							onChange={(val) =>
								setAttributes({ subHeadingPadding: val })
							}
						/>
					)}
					<Tabs isFitted variant="unstyled">
						<TabList
							border="1px solid #E0E0E0"
							p="4px"
							borderRadius="2px"
						>
							<Tab
								_selected={{
									color: "white",
									bg: "#7E36F4",
									borderRadius: "2px",
								}}
							>
								Normal
							</Tab>
							<Tab
								_selected={{
									color: "white",
									bg: "#7E36F4",
									borderRadius: "2px",
								}}
							>
								Hover
							</Tab>
						</TabList>
						<TabPanels>
							<TabPanel p="16px 0">
								<Border
									value={subHeadingBorder}
									onChange={(v) => {
										setAttributes({
											subHeadingBorder: v,
										});
									}}
								/>
								<BoxShadow
									resetAttributeKey="subHeadingHoverBorder"
									value={subHeadingHoverBorder}
									onChange={(val) =>
										setAttributes({
											subHeadingHoverBorder: val,
										})
									}
								/>
							</TabPanel>
							<TabPanel p="16px 0">
								<Border
									value={subHeadingBoxShadow}
									onChange={(v) => {
										setAttributes({
											subHeadingBoxShadow: v,
										});
									}}
								/>
								<BoxShadow
									resetAttributeKey="readMoreBoxShadowHover"
									value={subHeadingBoxShadowHover}
									onChange={(val) =>
										setAttributes({
											subHeadingBoxShadowHover: val,
										})
									}
								/>
							</TabPanel>
						</TabPanels>
					</Tabs>
				</Panel>
				<Panel title={__("Spacing", "magazine-blocks")}>
					<Dimensions
						value={margin}
						responsive
						label={__("Margin", "magazine-blocks")}
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) => setAttributes({ margin: val })}
						type="margin"
					/>
				</Panel>
			</StyleInspectorControls>
			<AdvancedInspectorControls>
				<AdvancedControls
					blockMargin={blockMargin}
					setAttributes={setAttributes}
					blockPadding={blockPadding}
				/>
				<ResponsiveControls
					{...{
						hideOnDesktop,
						hideOnTablet,
						hideOnMobile,
						setAttributes,
					}}
				/>
			</AdvancedInspectorControls>
		</>
	);
};

export default InspectorControls;
