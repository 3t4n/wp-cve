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
import {
	AlignBottom,
	AlignLeft,
	AlignMiddle,
	AlignSpaceBetween,
	AlignTop,
	BottomSeparator_Curve1,
	BottomSeparator_Curve2,
	BottomSeparator_Curve3,
	BottomSeparator_Rounded1,
	BottomSeparator_Rounded2,
	BottomSeparator_Rounded3,
	BottomSeparator_Slant1,
	BottomSeparator_Slant2,
	BottomSeparator_Straight,
	BottomSeparator_Wave1,
	BottomSeparator_Wave2,
	BottomSeparator_Wave3,
	BottomSeparator_Wave4,
	Seperator_None,
	TopSeparator_Curve1,
	TopSeparator_Curve2,
	TopSeparator_Curve3,
	TopSeparator_Rounded1,
	TopSeparator_Rounded2,
	TopSeparator_Rounded3,
	TopSeparator_Slant1,
	TopSeparator_Slant2,
	TopSeparator_Straight,
	TopSeparator_Wave1,
	TopSeparator_Wave2,
	TopSeparator_Wave3,
	TopSeparator_Wave4,
} from "../../../components/icons/Icons";
import SidebarDrawer from "../../../components/sidebar-drawer/SidebarDrawer";
import {
	Background,
	Border,
	BoxShadow,
	PopoverDrawer,
	Select,
	Slider,
	Toggle,
	ToggleButton,
	ToggleButtonGroup,
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
			verticalAlignment,
			container,
			width,
			columnGap,
			height,
			minHeight,
			background,
			hoverBackground,
			border,
			borderHover,
			boxShadow,
			boxShadowHover,
			overlay,
			overlayBackground,
			blockMargin,
			blockPadding,
			hideOnDesktop,
			hideOnTablet,
			hideOnMobile,
			topSeparator,
			bottomSeparator,
		},
		setAttributes,
	} = props;
	return (
		<>
			<InspectorTabs />
			<GeneralInspectorControls>
				<Panel title={__("Layout", "magazine-blocks")} initialOpen>
					<ToggleButtonGroup
						value={container}
						onChange={(v) => {
							setAttributes({ container: v });
						}}
						label={__("Container", "magazine-blocks")}
						resetAttributeKey="container"
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
						<ToggleButton value="contained">
							{__("Contained", "magazine-blocks")}
						</ToggleButton>
						<ToggleButton value="stretched">
							{__("Stretched", "magazine-blocks")}
						</ToggleButton>
					</ToggleButtonGroup>
					{"contained" === container && (
						<Slider
							onChange={(val: any) =>
								setAttributes({ width: val })
							}
							label={__("Max Width", "magazine-blocks")}
							responsive={true}
							min={0}
							max={1920}
							value={width}
							units={["px", "em"]}
							resetAttributeKey="width"
						/>
					)}
					<Slider
						onChange={(val: any) =>
							setAttributes({ columnGap: val })
						}
						label={__("Column Gap", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={100}
						value={columnGap}
						resetAttributeKey="columnGap"
					/>
					<Select
						options={[
							{
								label: __("Min Height", "magazine-blocks"),
								value: "min-height",
							},
							{
								label: __("Default", "magazine-blocks"),
								value: "default",
							},
							{
								label: __("Fit To Screen", "magazine-blocks"),
								value: "fit-to-screen",
							},
						]}
						onChange={(val) => setAttributes({ height: val })}
						value={height}
						label={__("Height", "magazine-blocks")}
					/>
					{"min-height" === height && (
						<Slider
							onChange={(val: any) =>
								setAttributes({ minHeight: val })
							}
							label={__("Min Height", "magazine-blocks")}
							units={["px", "em", "vh"]}
							responsive={true}
							min={0}
							max={1200}
							value={minHeight}
							resetAttributeKey="minHeight"
						/>
					)}
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("Properties", "magazine-blocks")} initialOpen>
					<ToggleButtonGroup
						value={verticalAlignment}
						onChange={(v) => {
							setAttributes({ verticalAlignment: v });
						}}
						label={__("Vertical Alignment", "magazine-blocks")}
						responsive
						resetAttributeKey="verticalAlignment"
						groupProps={{
							sx: {
								".chakra-button": {
									minH: "32px",
									"&[data-active]": {
										svg: {
											fill: "#fff !important",
										},
									},
								},
							},
						}}
					>
						<ToggleButton value="left">
							<AlignLeft />
						</ToggleButton>
						<ToggleButton value="baseline">
							<AlignSpaceBetween />
						</ToggleButton>
						<ToggleButton value="flex-start">
							<AlignTop />
						</ToggleButton>
						<ToggleButton value="center">
							<AlignMiddle />
						</ToggleButton>
						<ToggleButton value="flex-end">
							<AlignBottom />
						</ToggleButton>
					</ToggleButtonGroup>
				</Panel>
				<Panel title={__("Background", "magazine-blocks")}>
					<PopoverDrawer label={__("Type", "magazine-blocks")}>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
								<Tab>{__("Hover", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={background}
										resetKey="background"
										onReset={(v) => {
											setAttributes({ background: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={background}
										onChange={(v) =>
											setAttributes({ background: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={hoverBackground}
										resetKey="hoverBackground"
										onReset={(v) => {
											setAttributes({
												hoverBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={hoverBackground}
										onChange={(v) =>
											setAttributes({
												hoverBackground: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
				</Panel>
				<Panel title={__("Separator", "magazine-blocks")}>
					<SidebarDrawer label={__("Preset", "magazine-blocks")}>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Top", "magazine-blocks")}</Tab>
								<Tab>{__("Bottom", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel p={0}>
									<ToggleButtonGroup
										label={__("Select", "magazine-blocks")}
										value={topSeparator?.topSeparatorIcon}
										onChange={(val) =>
											setAttributes({
												topSeparator: {
													topSeparator: 1,
													topSeparatorIcon: val,
												},
											})
										}
										groupProps={toggleButtonPresetStyles}
									>
										{[
											{
												label: __(
													"None",
													"magazine-blocks"
												),
												value: "none",
												icon: Seperator_None,
											},
											{
												label: __(
													"Separator 1",
													"magazine-blocks"
												),
												value: "top_separator_1",
												icon: TopSeparator_Curve1,
											},
											{
												label: __(
													"Separator 2",
													"magazine-blocks"
												),
												value: "top_separator_2",
												icon: TopSeparator_Curve2,
											},
											{
												label: __(
													"Separator 3",
													"magazine-blocks"
												),
												value: "top_separator_3",
												icon: TopSeparator_Curve3,
											},
											{
												label: __(
													"Separator 4",
													"magazine-blocks"
												),
												value: "top_separator_4",
												icon: TopSeparator_Rounded1,
											},
											{
												label: __(
													"Separator 5",
													"magazine-blocks"
												),
												value: "top_separator_5",
												icon: TopSeparator_Rounded2,
											},
											{
												label: __(
													"Separator 6",
													"magazine-blocks"
												),
												value: "top_separator_6",
												icon: TopSeparator_Rounded3,
											},
											{
												label: __(
													"Separator 7",
													"magazine-blocks"
												),
												value: "top_separator_7",
												icon: TopSeparator_Slant1,
											},
											{
												label: __(
													"Separator 8",
													"magazine-blocks"
												),
												value: "top_separator_8",
												icon: TopSeparator_Slant2,
											},
											{
												label: __(
													"Separator 9",
													"magazine-blocks"
												),
												value: "top_separator_9",
												icon: TopSeparator_Straight,
											},
											{
												label: __(
													"Separator 10",
													"magazine-blocks"
												),
												value: "top_separator_10",
												icon: TopSeparator_Wave1,
											},
											{
												label: __(
													"Separator 11",
													"magazine-blocks"
												),
												value: "top_separator_11",
												icon: TopSeparator_Wave2,
											},
											{
												label: __(
													"Separator 12",
													"magazine-blocks"
												),
												value: "top_separator_12",
												icon: TopSeparator_Wave3,
											},
											{
												label: __(
													"Separator 13",
													"magazine-blocks"
												),
												value: "top_separator_13",
												icon: TopSeparator_Wave4,
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
								</TabPanel>
								<TabPanel p={0}>
									<ToggleButtonGroup
										label={__("Select", "magazine-blocks")}
										value={
											bottomSeparator?.bottomSeparatorIcon
										}
										onChange={(val) =>
											setAttributes({
												bottomSeparator: {
													bottomSeparator: 1,
													bottomSeparatorIcon: val,
												},
											})
										}
										groupProps={toggleButtonPresetStyles}
									>
										{[
											{
												label: __(
													"None",
													"magazine-blocks"
												),
												value: "none",
												icon: Seperator_None,
											},
											{
												label: __(
													"Separator 1",
													"magazine-blocks"
												),
												value: "bottom_separator_1",
												icon: BottomSeparator_Curve1,
											},
											{
												label: __(
													"Separator 2",
													"magazine-blocks"
												),
												value: "bottom_separator_2",
												icon: BottomSeparator_Curve2,
											},
											{
												label: __(
													"Separator 3",
													"magazine-blocks"
												),
												value: "bottom_separator_3",
												icon: BottomSeparator_Curve3,
											},
											{
												label: __(
													"Separator 4",
													"magazine-blocks"
												),
												value: "bottom_separator_4",
												icon: BottomSeparator_Rounded1,
											},
											{
												label: __(
													"Separator 5",
													"magazine-blocks"
												),
												value: "bottom_separator_5",
												icon: BottomSeparator_Rounded2,
											},
											{
												label: __(
													"Separator 6",
													"magazine-blocks"
												),
												value: "bottom_separator_6",
												icon: BottomSeparator_Rounded3,
											},
											{
												label: __(
													"Separator 7",
													"magazine-blocks"
												),
												value: "bottom_separator_7",
												icon: BottomSeparator_Slant1,
											},
											{
												label: __(
													"Separator 8",
													"magazine-blocks"
												),
												value: "bottom_separator_8",
												icon: BottomSeparator_Slant2,
											},
											{
												label: __(
													"Separator 9",
													"magazine-blocks"
												),
												value: "bottom_separator_9",
												icon: BottomSeparator_Straight,
											},
											{
												label: __(
													"Separator 10",
													"magazine-blocks"
												),
												value: "bottom_separator_10",
												icon: BottomSeparator_Wave1,
											},
											{
												label: __(
													"Separator 11",
													"magazine-blocks"
												),
												value: "bottom_separator_11",
												icon: BottomSeparator_Wave2,
											},
											{
												label: __(
													"Separator 12",
													"magazine-blocks"
												),
												value: "bottom_separator_12",
												icon: BottomSeparator_Wave3,
											},
											{
												label: __(
													"Separator 13",
													"magazine-blocks"
												),
												value: "bottom_separator_13",
												icon: BottomSeparator_Wave4,
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
								</TabPanel>
							</TabPanels>
						</Tabs>
					</SidebarDrawer>
				</Panel>
				<Panel title={__("Border", "magazine-blocks")}>
					<Tabs variant="border">
						<TabList>
							<Tab>{__("Normal", "magazine-blocks")}</Tab>
							<Tab>{__("Hover", "magazine-blocks")}</Tab>
						</TabList>
						<TabPanels>
							<TabPanel>
								<Border
									value={border}
									onChange={(v) => {
										setAttributes({
											border: v,
										});
									}}
									resetAttributeKey="border"
								/>
								<BoxShadow
									resetAttributeKey="boxShadow"
									value={boxShadow}
									onChange={(val) =>
										setAttributes({
											boxShadow: val,
										})
									}
								/>
							</TabPanel>
							<TabPanel>
								<Border
									value={borderHover}
									onChange={(v) => {
										setAttributes({
											borderHover: v,
										});
									}}
									resetAttributeKey="borderHover"
								/>
								<BoxShadow
									resetAttributeKey="boxShadowHover"
									value={boxShadowHover}
									onChange={(val) =>
										setAttributes({
											boxShadowHover: val,
										})
									}
								/>
							</TabPanel>
						</TabPanels>
					</Tabs>
				</Panel>
				<Panel title={__("Overlay", "magazine-blocks")}>
					<Toggle
						checked={overlay}
						onChange={() => setAttributes({ overlay: !overlay })}
						label={__("Enable", "magazine-blocks")}
					/>
					{overlay && (
						<PopoverDrawer label={__("Type", "magazine-blocks")}>
							<Box padding={4}>
								<Reset
									saved={overlayBackground}
									resetKey="overlayBackground"
									onReset={(v) => {
										setAttributes({ overlayBackground: v });
									}}
									buttonProps={{
										position: "absolute",
										top: "12px",
										right: "10px",
									}}
								/>
								<Background
									value={overlayBackground}
									onChange={(v) =>
										setAttributes({ overlayBackground: v })
									}
								/>
							</Box>
						</PopoverDrawer>
					)}
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
