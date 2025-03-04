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
	HeadingLayout1Style1,
	HeadingLayout2Style1,
	LayoutAlignCenter,
	LayoutAlignRight,
	LayoutAlignTop,
	PostListLayout1Style1,
	PostListLayout1Style2,
	PostListLayout2Style1,
	TextAlignCenter,
	TextAlignLeft,
	TextAlignRight,
} from "../../../components/icons/Icons";
import SidebarDrawer from "../../../components/sidebar-drawer/SidebarDrawer";
import {
	Background,
	Border,
	BoxShadow,
	ColorPicker,
	Dimensions,
	Input,
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

			gap,
			alignment,
			verticalAlignment,
			category,
			tag,
			orderBy,
			orderType,
			authorName,
			excludedCategory,
			postCount,
			blockPadding,
			blockMargin,

			enableHeading,
			headingLayout,
			headingLayout1AdvancedStyle,
			headingLayout2AdvancedStyle,
			headingColor,
			headingHoverColor,
			headingBorderColor,
			headingBackground,

			imageToggle,
			position,
			imageBorderRadius,
			imageHeight,
			imageWidth,
			hoverAnimation,

			postBoxBackground,
			postBoxedBoxShadow,
			postBoxedBoxShadowHover,
			postTitleTypography,
			postTitleColor,
			postTitleHoverColor,

			enableDate,
			metaPosition,
			metaIconColor,
			metaLinkColor,
			metaLinkHoverColor,

			excerptLimit,
			enableExcerpt,
			excerptColor,
			excerptMargin,

			enableReadMore,
			readMoreText,
			readMoreColor,
			readMoreHoverColor,
			readMoreBackground,
			readMoreHoverBackground,
			readMoreSpacing,
			readMorePadding,
			enableReadMoreBorder,
			readMoreBorder,
			readMoreHoverBorder,
			readMoreBoxShadow,
			readMoreBoxShadowHover,

			enablePagination,
			paginationAlignment,
			paginationColor,
			paginationBackground,
			paginationHoverColor,
			paginationHoverBackground,
			paginationPadding,
			enablePaginationBorder,
			paginationBorder,
			paginationBoxShadow,
			paginationHoverBorder,
			paginationBoxShadowHover,

			hideOnDesktop,
			hideOnTablet,
			hideOnMobile,
		},
		setAttributes,
		categories,
		tags,
		authorOptions,
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
											icon: PostListLayout1Style1,
										},
										{
											label: __(
												"Style 2",
												"magazine-blocks"
											),
											value: "layout-1-style-2",
											icon: PostListLayout1Style2,
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
											icon: PostListLayout2Style1,
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
					<ToggleButtonGroup
						value={alignment}
						onChange={(v) => {
							setAttributes({ alignment: v });
						}}
						label={__("Content Alignment", "magazine-blocks")}
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
									svg: {
										w: 6,
										h: 6,
									},
								},
							},
						}}
					>
						<ToggleButton value="flex-start">
							<LayoutAlignTop />
						</ToggleButton>
						<ToggleButton value="center">
							<LayoutAlignCenter />
						</ToggleButton>
						<ToggleButton value="flex-end">
							<LayoutAlignRight />
						</ToggleButton>
					</ToggleButtonGroup>
				</Panel>
				<Panel title={__("Query", "magazine-blocks")}>
					<Select
						value={category}
						onChange={(val) => setAttributes({ category: val })}
						label={__("Category", "magazine-blocks")}
						options={[
							{ value: "all", label: __("All") },
							...(categories ?? [])?.map(({ name, id }) => ({
								label: name,
								value: id.toString(),
							})),
						]}
					/>
					<Select
						value={tag}
						onChange={(val) => setAttributes({ tag: val })}
						label={__("Tag", "magazine-blocks")}
						options={[
							{ value: "all", label: __("All") },
							...(tags ?? [])?.map(({ name, id }) => ({
								label: name,
								value: id.toString(),
							})),
						]}
					/>
					<Select
						options={[
							{
								label: __("Date", "magazine-blocks"),
								value: "date",
							},
							{
								label: __("Title", "magazine-blocks"),
								value: "title",
							},
							{
								label: __("Random", "magazine-blocks"),
								value: "rand",
							},
						]}
						onChange={(val) => setAttributes({ orderBy: val })}
						value={orderBy}
						label={__("Order By", "magazine-blocks")}
					/>
					<>
						<ToggleButtonGroup
							value={orderType}
							onChange={(v) => {
								setAttributes({ orderType: v });
							}}
							label={__("Order", "magazine-blocks")}
							resetAttributeKey="orderType"
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
							<ToggleButton value="asc">Ascending</ToggleButton>
							<ToggleButton value="desc">Descending</ToggleButton>
						</ToggleButtonGroup>
					</>
					<Select
						isMulti
						value={excludedCategory}
						onChange={(val) =>
							setAttributes({ excludedCategory: val })
						}
						label={__("Excluded Category", "magazine-blocks")}
						options={[
							...(categories ?? [])?.map(({ name, id }) => ({
								label: name,
								value: id.toString(),
							})),
						]}
					/>
					<Select
						options={authorOptions ?? []}
						onChange={(val) => setAttributes({ authorName: val })}
						value={authorName}
						label={__("Authors", "magazine-blocks")}
					/>
					<Slider
						label={__("Post Count", "magazine-blocks")}
						value={postCount}
						step={1}
						min={1}
						max={15}
						onChange={(val) => setAttributes({ postCount: val })}
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
				<Panel title={__("Image", "magazine-blocks")}>
					<Toggle
						checked={imageToggle}
						onChange={() =>
							setAttributes({ imageToggle: !imageToggle })
						}
						label={__("Enable", "magazine-blocks")}
					/>
					{imageToggle && (
						<ToggleButtonGroup
							value={position}
							onChange={(v) => {
								setAttributes({ position: v });
							}}
							label={__("Position", "magazine-blocks")}
							resetAttributeKey="position"
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
							<ToggleButton value="top">
								<TextAlignLeft />
							</ToggleButton>
							<ToggleButton value="bottom">
								<TextAlignLeft />
							</ToggleButton>
						</ToggleButtonGroup>
					)}
					{imageToggle && (
						<Select
							options={[
								{
									label: __("None", "magazine-blocks"),
									value: "none",
								},
								{
									label: __("Zoom In", "magazine-blocks"),
									value: "zoomIn",
								},
								{
									label: __("Zoom Out", "magazine-blocks"),
									value: "zoomOut",
								},
								{
									label: __("Opacity", "magazine-blocks"),
									value: "opacity",
								},
								{
									label: __("Rotate Left", "magazine-blocks"),
									value: "rotateLeft",
								},
								{
									label: __(
										"Rotate Right",
										"magazine-blocks"
									),
									value: "rotateRight",
								},
								{
									label: __("Slide Left", "magazine-blocks"),
									value: "slideLeft",
								},
								{
									label: __("Slide Right", "magazine-blocks"),
									value: "slideRight",
								},
							]}
							onChange={(val) =>
								setAttributes({ hoverAnimation: val })
							}
							value={hoverAnimation}
							label={__("Hover Animation", "magazine-blocks")}
						/>
					)}
				</Panel>
				<Panel title={__("Meta", "magazine-blocks")}>
					{enableDate && (
						<ToggleButtonGroup
							value={metaPosition}
							onChange={(v) => {
								setAttributes({ metaPosition: v });
							}}
							label={__("Position", "magazine-blocks")}
							resetAttributeKey="metaPosition"
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
							<ToggleButton value="top">
								<TextAlignLeft />
							</ToggleButton>
							<ToggleButton value="bottom">
								<TextAlignLeft />
							</ToggleButton>
						</ToggleButtonGroup>
					)}
					<Toggle
						checked={enableDate}
						onChange={() =>
							setAttributes({ enableDate: !enableDate })
						}
						label={__("Date", "magazine-blocks")}
					/>
				</Panel>
				<Panel title={__("Excerpt", "magazine-blocks")}>
					<Toggle
						checked={enableExcerpt}
						onChange={() =>
							setAttributes({ enableExcerpt: !enableExcerpt })
						}
						label={__("Enable", "magazine-blocks")}
					/>
					{enableExcerpt && (
						<Slider
							label={__("Limit", "magazine-blocks")}
							value={excerptLimit}
							step={1}
							min={1}
							max={20}
							onChange={(val) =>
								setAttributes({ excerptLimit: val })
							}
						/>
					)}
				</Panel>
				<Panel title={__("Read More", "magazine-blocks")}>
					<Toggle
						checked={enableReadMore}
						onChange={() =>
							setAttributes({
								enableReadMore: !enableReadMore,
							})
						}
						label={__("Enable", "magazine-blocks")}
					/>
					{enableReadMore && (
						<Input
							value={readMoreText}
							label={__("Text", "magazine-blocks")}
							onChange={(val) =>
								setAttributes({ readMoreText: val })
							}
						/>
					)}
				</Panel>
				<Panel title={__("Pagination", "magazine-blocks")}>
					<Toggle
						checked={enablePagination}
						onChange={() =>
							setAttributes({
								enablePagination: !enablePagination,
							})
						}
						label={__("Enable", "magazine-blocks")}
					/>
					{enablePagination && (
						<ToggleButtonGroup
							value={paginationAlignment}
							onChange={(v) => {
								setAttributes({ paginationAlignment: v });
							}}
							label={__("Content Alignment", "magazine-blocks")}
							responsive
							resetAttributeKey="paginationAlignment"
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
					)}
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("General", "magazine-blocks")} initialOpen>
					<Slider
						onChange={(val: any) => setAttributes({ gap: val })}
						label={__("Gap", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={100}
						value={gap}
					/>
				</Panel>
				<Panel title={__("Post Box", "magazine-blocks")}>
					<PopoverDrawer label={__("Background", "magazine-blocks")}>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={postBoxBackground}
										resetKey="postBoxBackground"
										onReset={(v) => {
											setAttributes({
												postBoxBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={postBoxBackground}
										onChange={(v) =>
											setAttributes({
												postBoxBackground: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
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
								<BoxShadow
									resetAttributeKey="postBoxedBoxShadow"
									value={postBoxedBoxShadow}
									onChange={(val) =>
										setAttributes({
											postBoxedBoxShadow: val,
										})
									}
								/>
							</TabPanel>
							<TabPanel p="16px 0">
								<BoxShadow
									resetAttributeKey="postBoxedBoxShadowHover"
									value={postBoxedBoxShadowHover}
									onChange={(val) =>
										setAttributes({
											postBoxedBoxShadowHover: val,
										})
									}
								/>
							</TabPanel>
						</TabPanels>
					</Tabs>
				</Panel>
				<Panel title={__("Heading", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Heading Color", "magazine-blocks")}
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

					{"heading-layout-2" === headingLayout && (
						<PopoverDrawer
							label={__("Background", "magazine-blocks")}
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
										<Background
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
				</Panel>
				<Panel title={__("Image", "magazine-blocks")}>
					{imageToggle && (
						<Slider
							onChange={(val: any) =>
								setAttributes({ imageHeight: val })
							}
							label={__("Height", "magazine-blocks")}
							units={["px", "em", "%"]}
							responsive={true}
							min={74}
							max={500}
							value={imageHeight}
						/>
					)}
					{imageToggle && (
						<Slider
							onChange={(val: any) =>
								setAttributes({ imageWidth: val })
							}
							label={__("Width", "magazine-blocks")}
							units={["px", "em", "%"]}
							responsive={true}
							min={74}
							max={500}
							value={imageWidth}
						/>
					)}
					{imageToggle && (
						<Dimensions
							value={imageBorderRadius || {}}
							responsive
							label={__("Radius", "magazine-blocks")}
							min={0}
							max={500}
							defaultUnit="px"
							units={["px", "rem", "em", "%"]}
							onChange={(val) =>
								setAttributes({ imageBorderRadius: val })
							}
						/>
					)}
				</Panel>
				<Panel title={__("Post Title", "magazine-blocks")}>
					<Typography
						value={postTitleTypography}
						onChange={(val) =>
							setAttributes({ postTitleTypography: val })
						}
					/>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: postTitleColor,
											id: "postTitleColor",
										},
										{
											color: postTitleHoverColor,
											id: "postTitleHoverColor",
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
										saved={postTitleColor}
										resetKey="postTitleColor"
										onReset={(v) => {
											setAttributes({
												postTitleColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={postTitleColor}
										onChange={(v) =>
											setAttributes({ postTitleColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={postTitleHoverColor}
										resetKey="postTitleHoverColor"
										onReset={(v) => {
											setAttributes({
												postTitleHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={postTitleHoverColor}
										onChange={(v) =>
											setAttributes({
												postTitleHoverColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
				</Panel>
				<Panel title={__("Meta", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Icon Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: metaIconColor,
											id: "metaIconColor",
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
										saved={metaIconColor}
										resetKey="metaIconColor"
										onReset={(v) => {
											setAttributes({ metaIconColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={metaIconColor}
										onChange={(v) =>
											setAttributes({ metaIconColor: v })
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<PopoverDrawer
						label={__("Link Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{ color: metaLinkColor, id: "color" },
										{
											color: metaLinkHoverColor,
											id: "hoverColor",
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
										saved={metaLinkColor}
										resetKey="metaLinkColor"
										onReset={(v) => {
											setAttributes({ metaLinkColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={metaLinkColor}
										onChange={(v) =>
											setAttributes({ metaLinkColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={metaLinkHoverColor}
										resetKey="metaLinkHoverColor"
										onReset={(v) => {
											setAttributes({
												metaLinkHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={metaLinkHoverColor}
										onChange={(v) =>
											setAttributes({
												metaLinkHoverColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
				</Panel>
				<Panel title={__("Excerpt", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{ color: excerptColor, id: "color" },
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
										saved={excerptColor}
										resetKey="excerptColor"
										onReset={(v) => {
											setAttributes({ excerptColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={excerptColor}
										onChange={(v) =>
											setAttributes({ excerptColor: v })
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<Dimensions
						value={excerptMargin}
						responsive
						label={__("Margin", "magazine-blocks")}
						type="margin"
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) =>
							setAttributes({ excerptMargin: val })
						}
					/>
				</Panel>
				<Panel title={__("Read More", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: readMoreColor,
											id: "readMoreColor",
										},
										{
											color: readMoreHoverColor,
											id: "readMoreHoverColor",
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
										saved={readMoreColor}
										resetKey="readMoreColor"
										onReset={(v) => {
											setAttributes({ readMoreColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={readMoreColor}
										onChange={(v) =>
											setAttributes({ readMoreColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={readMoreHoverColor}
										resetKey="readMoreHoverColor"
										onReset={(v) => {
											setAttributes({
												readMoreHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={readMoreHoverColor}
										onChange={(v) =>
											setAttributes({
												readMoreHoverColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<PopoverDrawer label={__("Background", "magazine-blocks")}>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
								<Tab>{__("Hover", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={readMoreBackground}
										resetKey="readMoreBackground"
										onReset={(v) => {
											setAttributes({
												readMoreBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={readMoreBackground}
										onChange={(v) =>
											setAttributes({
												readMoreBackground: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={readMoreHoverBackground}
										resetKey="readMoreHoverBackground"
										onReset={(v) => {
											setAttributes({
												readMoreHoverBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={readMoreHoverBackground}
										onChange={(v) =>
											setAttributes({
												readMoreHoverBackground: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<Slider
						onChange={(val: any) =>
							setAttributes({ readMoreSpacing: val })
						}
						label={__("Spacing", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={100}
						value={readMoreSpacing}
					/>
					<Dimensions
						value={readMorePadding}
						responsive
						label={__("Padding", "magazine-blocks")}
						type="margin"
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) =>
							setAttributes({ readMorePadding: val })
						}
					/>
					<Toggle
						checked={enableReadMoreBorder}
						onChange={() =>
							setAttributes({
								enableReadMoreBorder: !enableReadMoreBorder,
							})
						}
						label={__("Border", "magazine-blocks")}
					/>
					{enableReadMoreBorder && (
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
										value={readMoreBorder}
										onChange={(v) => {
											setAttributes({
												readMoreBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="readMoreBoxShadow"
										value={readMoreBoxShadow}
										onChange={(val) =>
											setAttributes({
												readMoreBoxShadow: val,
											})
										}
									/>
								</TabPanel>
								<TabPanel p="16px 0">
									<Border
										value={readMoreHoverBorder}
										onChange={(v) => {
											setAttributes({
												readMoreHoverBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="readMoreBoxShadowHover"
										value={readMoreBoxShadowHover}
										onChange={(val) =>
											setAttributes({
												readMoreBoxShadowHover: val,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					)}
				</Panel>
				<Panel title={__("Pagination", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: paginationColor,
											id: "arrowColor",
										},
										{
											color: paginationHoverColor,
											id: "arrowHoverColor",
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
										saved={paginationColor}
										resetKey="paginationColor"
										onReset={(v) => {
											setAttributes({
												paginationColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={paginationColor}
										onChange={(v) =>
											setAttributes({
												paginationColor: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={paginationHoverColor}
										resetKey="paginationHoverColor"
										onReset={(v) => {
											setAttributes({
												paginationHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={paginationHoverColor}
										onChange={(v) =>
											setAttributes({
												paginationHoverColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<PopoverDrawer label={__("Background", "magazine-blocks")}>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
								<Tab>{__("Hover", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={paginationBackground}
										resetKey="paginationBackground"
										onReset={(v) => {
											setAttributes({
												paginationBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={paginationBackground}
										onChange={(v) =>
											setAttributes({
												paginationBackground: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={paginationHoverBackground}
										resetKey="paginationHoverBackground"
										onReset={(v) => {
											setAttributes({
												paginationHoverBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={paginationHoverBackground}
										onChange={(v) =>
											setAttributes({
												paginationHoverBackground: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<Dimensions
						value={paginationPadding}
						responsive
						label={__("Padding", "magazine-blocks")}
						type="padding"
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) =>
							setAttributes({ paginationPadding: val })
						}
					/>
					<Toggle
						checked={enablePaginationBorder}
						onChange={() =>
							setAttributes({
								enablePaginationBorder: !enablePaginationBorder,
							})
						}
						label={__("Border", "magazine-blocks")}
					/>
					{enablePaginationBorder && (
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
										value={paginationBorder}
										onChange={(v) => {
											setAttributes({
												paginationBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="paginationBoxShadow"
										value={paginationBoxShadow}
										onChange={(val) =>
											setAttributes({
												paginationBoxShadow: val,
											})
										}
									/>
								</TabPanel>
								<TabPanel p="16px 0">
									<Border
										value={paginationHoverBorder}
										onChange={(v) => {
											setAttributes({
												paginationHoverBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="paginationBoxShadowHover"
										value={paginationBoxShadowHover}
										onChange={(val) =>
											setAttributes({
												paginationBoxShadowHover: val,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
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
