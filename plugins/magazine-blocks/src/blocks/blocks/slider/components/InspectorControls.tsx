import { Tab, TabList, TabPanel, TabPanels, Tabs } from "@chakra-ui/react";
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
	TextAlignCenter,
	TextAlignLeft,
	TextAlignRight,
} from "../../../components/icons/Icons";
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
			alignment,
			enableAutoPlay,
			enablePauseOnHover,
			height,
			sliderSpeed,

			enableArrow,
			arrowHeight,
			arrowWidth,
			arrowSize,
			arrowColor,
			arrowBackground,
			arrowHoverColor,
			arrowHoverBackground,

			category,
			tag,
			orderBy,
			orderType,
			authorName,
			excludedCategory,
			postCount,

			postTitleTypography,
			postTitleMarkup,
			postTitleColor,
			postTitleHoverColor,

			enableCategory,
			categoryColor,
			categoryBackground,
			categoryHoverColor,
			categoryHoverBackground,
			categoryPadding,
			enableCategoryBorder,
			categoryBorder,
			categoryBoxShadow,
			categoryHoverBorder,
			categoryBoxShadowHover,

			metaPosition,
			enableAuthor,
			enableDate,
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
			readMorewHoverColor,
			readMoreBackground,
			readMoreHoverBackground,
			readMoreSpacing,
			readMorePadding,
			enableReadMoreBorder,
			readMoreBorder,
			readMoreHoverBorder,
			readMoreBoxShadow,
			readMoreBoxShadowHover,

			enableDot,
			dotGap,
			dotHeight,
			dotWidth,
			horizontalPosition,
			verticalPosition,
			dotBackground,
			enableDotBorder,
			dotBorder,
			dotBoxShadow,
			dotHoverBorder,
			dotBoxShadowHover,

			blockPadding,
			blockMargin,
			cssID,
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
					<Toggle
						checked={enableAutoPlay}
						onChange={() =>
							setAttributes({
								enableAutoPlay: !enableAutoPlay,
							})
						}
						label={__("Auto Play", "magazine-blocks")}
					/>
					<Toggle
						checked={enablePauseOnHover}
						onChange={() =>
							setAttributes({
								enablePauseOnHover: !enablePauseOnHover,
							})
						}
						label={__("Pause On Hover", "magazine-blocks")}
					/>
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
							{ value: "all", label: __("All") },
							...(categories ?? [])?.map(({ name, id }) => ({
								label: name,
								value: id.toString(),
							})),
						]}
						//@ts-ignore
						multiple={true}
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
				<Panel title={__("Arrow", "magazine-blocks")}>
					<Toggle
						checked={enableArrow}
						onChange={() =>
							setAttributes({ enableArrow: !enableArrow })
						}
						label={__("Enable", "magazine-blocks")}
					/>
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
							setAttributes({ postTitleMarkup: val })
						}
						value={postTitleMarkup}
						label={__("HTML Markup", "magazine-blocks")}
					/>
				</Panel>
				<Panel title={__("Header Meta", "magazine-blocks")}>
					<Toggle
						checked={enableCategory}
						onChange={() =>
							setAttributes({
								enableCategory: !enableCategory,
							})
						}
						label={__("Category", "magazine-blocks")}
					/>
				</Panel>
				<Panel title={__("Meta", "magazine-blocks")}>
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
					<Toggle
						checked={enableAuthor}
						onChange={() =>
							setAttributes({
								enableAuthor: !enableAuthor,
							})
						}
						label={__("Author", "magazine-blocks")}
					/>
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
							setAttributes({
								enableExcerpt: !enableExcerpt,
							})
						}
						label={__("Enable", "magazine-blocks")}
					/>
					{enableExcerpt && (
						<Slider
							onChange={(val) =>
								setAttributes({ excerptLimit: val })
							}
							label={__("Limit", "magazine-blocks")}
							min={0}
							max={500}
							step={1}
							value={excerptLimit}
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
				<Panel title={__("Dots", "magazine-blocks")}>
					<Toggle
						checked={enableDot}
						onChange={() =>
							setAttributes({ enableDot: !enableDot })
						}
						label={__("Enable", "magazine-blocks")}
					/>
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("General", "magazine-blocks")} initialOpen>
					<Slider
						onChange={(val: any) => setAttributes({ height: val })}
						label={__("Height", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={300}
						max={1000}
						value={height}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({ sliderSpeed: val })
						}
						label={__("Speed", "magazine-blocks")}
						min={0}
						max={10000}
						value={sliderSpeed}
					/>
				</Panel>
				<Panel title={__("Arrow", "magazine-blocks")}>
					<Slider
						onChange={(val: any) =>
							setAttributes({ arrowHeight: val })
						}
						label={__("Heigth", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={60}
						value={arrowHeight}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({ arrowWidth: val })
						}
						label={__("Width", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={60}
						value={arrowWidth}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({ arrowSize: val })
						}
						label={__("Size", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={100}
						value={arrowSize}
					/>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{ color: arrowColor, id: "arrowColor" },
										{
											color: arrowHoverColor,
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
										saved={arrowColor}
										resetKey="arrowColor"
										onReset={(v) => {
											setAttributes({ arrowColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={arrowColor}
										onChange={(v) =>
											setAttributes({ arrowColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={arrowHoverColor}
										resetKey="arrowHoverColor"
										onReset={(v) => {
											setAttributes({
												arrowHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={arrowHoverColor}
										onChange={(v) =>
											setAttributes({
												arrowHoverColor: v,
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
										saved={arrowBackground}
										resetKey="arrowBackground"
										onReset={(v) => {
											setAttributes({
												arrowBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={arrowBackground}
										onChange={(v) =>
											setAttributes({
												arrowBackground: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={arrowHoverBackground}
										resetKey="arrowHoverBackground"
										onReset={(v) => {
											setAttributes({
												arrowHoverBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={arrowHoverBackground}
										onChange={(v) =>
											setAttributes({
												arrowHoverBackground: v,
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
				<Panel title={__("Header Meta", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: categoryColor,
											id: "categoryColor",
										},
										{
											color: categoryHoverColor,
											id: "categoryHoverColor",
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
										saved={categoryColor}
										resetKey="categoryColor"
										onReset={(v) => {
											setAttributes({ categoryColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={categoryColor}
										onChange={(v) =>
											setAttributes({ categoryColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={categoryHoverColor}
										resetKey="categoryHoverColor"
										onReset={(v) => {
											setAttributes({
												categoryHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={categoryHoverColor}
										onChange={(v) =>
											setAttributes({
												categoryHoverColor: v,
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
										saved={categoryBackground}
										resetKey="categoryBackground"
										onReset={(v) => {
											setAttributes({
												categoryBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={categoryBackground}
										onChange={(v) =>
											setAttributes({
												categoryBackground: v,
											})
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={categoryHoverBackground}
										resetKey="categoryHoverBackground"
										onReset={(v) => {
											setAttributes({
												categoryHoverBackground: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={categoryHoverBackground}
										onChange={(v) =>
											setAttributes({
												categoryHoverBackground: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<Dimensions
						value={categoryPadding}
						responsive
						label={__("Padding", "magazine-blocks")}
						type="padding"
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) =>
							setAttributes({ categoryPadding: val })
						}
					/>
					<Toggle
						checked={enableCategoryBorder}
						onChange={() =>
							setAttributes({
								enableCategoryBorder: !enableCategoryBorder,
							})
						}
						label={__("Border", "magazine-blocks")}
					/>
					{enableCategoryBorder && (
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
										value={categoryBorder}
										onChange={(v) => {
											setAttributes({
												categoryBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="categoryBoxShadow"
										value={categoryBoxShadow}
										onChange={(val) =>
											setAttributes({
												categoryBoxShadow: val,
											})
										}
									/>
								</TabPanel>
								<TabPanel p="16px 0">
									<Border
										value={categoryHoverBorder}
										onChange={(v) => {
											setAttributes({
												categoryHoverBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="categoryBoxShadowHover"
										value={categoryBoxShadowHover}
										onChange={(val) =>
											setAttributes({
												categoryBoxShadowHover: val,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					)}
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
											color: readMorewHoverColor,
											id: "readMorewHoverColor",
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
										saved={readMorewHoverColor}
										resetKey="readMorewHoverColor"
										onReset={(v) => {
											setAttributes({
												readMorewHoverColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={readMorewHoverColor}
										onChange={(v) =>
											setAttributes({
												readMorewHoverColor: v,
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
				<Panel title={__("Dots", "magazine-blocks")}>
					<Slider
						onChange={(val: any) => setAttributes({ dotGap: val })}
						label={__("Gap", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={50}
						value={dotGap}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({ dotHeight: val })
						}
						label={__("Height", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={50}
						value={dotHeight}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({ dotWidth: val })
						}
						label={__("Width", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={50}
						value={dotWidth}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({
								horizontalPosition: val,
							})
						}
						label={__("Horizontal Position", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={-1000}
						max={1000}
						value={horizontalPosition}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({ verticalPosition: val })
						}
						label={__("Vertical Position", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={-1000}
						max={1000}
						value={verticalPosition}
					/>
					<PopoverDrawer label={__("Background", "magazine-blocks")}>
						<Tabs position="relative">
							<TabList>
								<Tab>{__("Normal", "magazine-blocks")}</Tab>
							</TabList>
							<TabPanels>
								<TabPanel>
									<Reset
										saved={dotBackground}
										resetKey="dotBackground"
										onReset={(v) => {
											setAttributes({ dotBackground: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<Background
										value={dotBackground}
										onChange={(v) =>
											setAttributes({ dotBackground: v })
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
					<Toggle
						checked={enableDotBorder}
						onChange={() =>
							setAttributes({ enableDotBorder: !enableDotBorder })
						}
						label={__("Border", "magazine-blocks")}
					/>
					{enableDotBorder && (
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
										value={dotBorder}
										onChange={(v) => {
											setAttributes({
												dotBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="dotBoxShadow"
										value={dotBoxShadow}
										onChange={(val) =>
											setAttributes({
												dotBoxShadow: val,
											})
										}
									/>
								</TabPanel>
								<TabPanel p="16px 0">
									<Border
										value={dotHoverBorder}
										onChange={(v) => {
											setAttributes({
												dotHoverBorder: v,
											});
										}}
									/>
									<BoxShadow
										resetAttributeKey="dotBoxShadowHover"
										value={dotBoxShadowHover}
										onChange={(val) =>
											setAttributes({
												dotBoxShadowHover: val,
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
