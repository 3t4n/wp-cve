import { Box, Tab, TabList, TabPanel, TabPanels, Tabs } from "@chakra-ui/react";
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
	CategoryListLayout1Style1,
	CategoryListLayout2Style1,
	CategoryListLayout3Style1,
	HeadingLayout1Style1,
	HeadingLayout2Style1,
} from "../../../components/icons/Icons";
import SidebarDrawer from "../../../components/sidebar-drawer/SidebarDrawer";
import {
	Background,
	ColorPicker,
	PopoverDrawer,
	Select,
	Slider,
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
	categories?: any[];
	tags?: any[];
	authorOptions?: any[];
};

const InspectorControls: React.ComponentType<Props> = (props) => {
	const {
		attributes: {
			layout,
			layout1AdvancedStyle,
			layout2AdvancedStyle,
			layout3AdvancedStyle,
			postBoxStyle,
			categoryCount,
			gap,
			enableHeading,
			headingLayout,
			headingLayout1AdvancedStyle,
			headingLayout2AdvancedStyle,
			headingColor,
			headingHoverColor,
			headingBorderColor,
			headingBackground,

			categoryTitleMarkup,
			categoryTitleTypography,
			categoryTitleColor,
			categoryTitleHoverColor,

			countColor,
			countHoverColor,
			countBackground,
			countHoverBackground,
			countWidth,

			blockPadding,
			blockMargin,
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
					<SidebarDrawer label="Preset">
						<Select
							options={[
								{
									label: __("Layout 1", "magazine-blocks"),
									value: "layout-1",
								},
								{
									label: __("Layout 2", "magazine-blocks"),
									value: "layout-2",
								},
								{
									label: __("Layout 3", "magazine-blocks"),
									value: "layout-3",
								},
							]}
							onChange={(val) => setAttributes({ layout: val })}
							value={layout}
							label={__("Layout", "magazine-blocks")}
						/>
						{"layout-1" === layout && (
							<Box borderTop="1px solid #E0E0E0">
								<ToggleButtonGroup
									responsive={false}
									value={layout1AdvancedStyle}
									onChange={(val) =>
										setAttributes({
											layout1AdvancedStyle: val,
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
											value: "layout-1-style-1",
											icon: CategoryListLayout1Style1,
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
						{"layout-2" === layout && (
							<Box borderTop="1px solid #E0E0E0">
								<ToggleButtonGroup
									responsive={false}
									value={layout2AdvancedStyle}
									onChange={(val) =>
										setAttributes({
											layout2AdvancedStyle: val,
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
											value: "layout-2-style-1",
											icon: CategoryListLayout2Style1,
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
						{"layout-3" === layout && (
							<Box borderTop="1px solid #E0E0E0">
								<ToggleButtonGroup
									responsive={false}
									value={layout3AdvancedStyle}
									onChange={(val) =>
										setAttributes({
											layout3AdvancedStyle: val,
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
											value: "layout-3-style-1",
											icon: CategoryListLayout3Style1,
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
					{"layout-3" === layout && (
						<Toggle
							checked={postBoxStyle}
							onChange={() =>
								setAttributes({ postBoxStyle: !postBoxStyle })
							}
							label={__("Separator", "magazine-blocks")}
						/>
					)}
				</Panel>
				<Panel title={__("Query", "magazine-blocks")}>
					<Slider
						label={__("Category Count", "magazine-blocks")}
						value={categoryCount}
						step={1}
						min={1}
						max={15}
						onChange={(val) =>
							setAttributes({ categoryCount: val })
						}
					/>
				</Panel>
				<Panel title={__("Heading", "magazine-blocks")}>
					<Toggle
						checked={enableHeading}
						onChange={() =>
							setAttributes({ enableHeading: !enableHeading })
						}
						label={__("Enable", "magazine-blocks")}
					/>
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
				</Panel>
				<Panel title={__("Post Title", "magazine-blocks")}>
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
						onChange={(val) =>
							setAttributes({ categoryTitleMarkup: val })
						}
						value={categoryTitleMarkup}
						label={__("HTML Markup", "magazine-blocks")}
					/>
				</Panel>
				<Panel title={__("Count", "magazine-blocks")}>
					<Slider
						// @ts-ignore
						onChange={(val) => setAttributes({ countWidth: val })}
						label={__("Width", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={300}
						value={countWidth}
					/>
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("General", "magazine-blocks")} initialOpen>
					<Slider
						onChange={(val: any) => setAttributes({ gap: val })}
						label={__("Gap", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={5}
						max={100}
						value={gap}
					/>
				</Panel>
				<Panel title={__("Heading", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: headingColor,
											id: "headingColor",
										},
										{
											color: headingHoverColor,
											id: "headingHoverColor",
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
										saved={headingColor}
										resetKey="headingColor"
										onReset={(v) => {
											setAttributes({ headingColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={headingColor}
										onChange={(v) =>
											setAttributes({ headingColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={headingHoverColor}
										resetKey="headingHoverColor"
										onReset={(v) => {
											setAttributes({
												headingHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={headingHoverColor}
										onChange={(v) =>
											setAttributes({
												headingHoverColor: v,
											})
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
												color: headingBackground,
												id: "headingBackground",
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
											saved={headingBackground}
											resetKey="headingBackground"
											onReset={(v) => {
												setAttributes({
													headingBackground: v,
												});
											}}
											buttonProps={{
												position: "absolute",
												top: "12px",
												right: "10px",
											}}
										/>
										<ColorPicker
											value={headingBackground}
											onChange={(v) =>
												setAttributes({
													headingBackground: v,
												})
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
											color: headingBorderColor,
											id: "headingBorderColor",
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
										saved={headingBorderColor}
										resetKey="headingBorderColor"
										onReset={(v) => {
											setAttributes({
												headingBorderColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={headingBorderColor}
										onChange={(v) =>
											setAttributes({
												headingBorderColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
				</Panel>
				<Panel title={__("Post Title", "magazine-blocks")}>
					<Typography
						value={categoryTitleTypography}
						onChange={(val) =>
							setAttributes({ categoryTitleTypography: val })
						}
					/>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: categoryTitleColor,
											id: "categoryTitleColor",
										},
										{
											color: categoryTitleHoverColor,
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
										saved={categoryTitleColor}
										resetKey="categoryTitleColor"
										onReset={(v) => {
											setAttributes({
												categoryTitleColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={categoryTitleColor}
										onChange={(v) =>
											setAttributes({
												categoryTitleColor: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={categoryTitleHoverColor}
										resetKey="categoryTitleHoverColor"
										onReset={(v) => {
											setAttributes({
												categoryTitleHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={categoryTitleHoverColor}
										onChange={(v) =>
											setAttributes({
												categoryTitleHoverColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
				</Panel>
				<Panel title={__("Count", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{ color: countColor, id: "countColor" },
										{
											color: countHoverColor,
											id: "countHoverColor",
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
										saved={countColor}
										resetKey="countColor"
										onReset={(v) => {
											setAttributes({ countColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={countColor}
										onChange={(v) =>
											setAttributes({ countColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={countHoverColor}
										resetKey="countHoverColor"
										onReset={(v) => {
											setAttributes({
												countHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={countHoverColor}
										onChange={(v) =>
											setAttributes({
												countHoverColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<PopoverDrawer
						label={__("Background Color", "magazine-blocks")}
					>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
								<Tab>{__("Hover", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={countBackground}
										resetKey="countBackground"
										onReset={(v) => {
											setAttributes({
												countBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={countBackground}
										onChange={(v) =>
											setAttributes({
												countBackground: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={countHoverBackground}
										resetKey="countHoverBackground"
										onReset={(v) => {
											setAttributes({
												countHoverBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={countHoverBackground}
										onChange={(v) =>
											setAttributes({
												countHoverBackground: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<Slider
						// @ts-ignore
						onChange={(val) => setAttributes({ countWidth: val })}
						label={__("Width", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={300}
						value={countWidth}
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
