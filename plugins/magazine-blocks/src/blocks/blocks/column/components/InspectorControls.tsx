import { Tab, TabList, TabPanel, TabPanels, Tabs } from "@chakra-ui/react";
import { InspectorAdvancedControls } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import React from "react";
import InspectorTabs, {
	AdvancedInspectorControls,
	GeneralInspectorControls,
	StyleInspectorControls,
} from "../../../components/common/InspectorTabs";
import Panel from "../../../components/common/Panel";
import ResponsiveControls from "../../../components/common/ResponsiveControls";
import {
	Background,
	Border,
	BoxShadow,
	Dimensions,
	Input,
	PopoverDrawer,
	Slider,
} from "../../../controls";
import Reset from "../../../controls/common/Reset";

type Props = {
	attributes: any;
	setAttributes: (attributes: any) => void;
};

const InspectorControls: React.ComponentType<Props> = (props) => {
	const {
		attributes: {
			colWidth,
			background,
			hoverBackground,
			border,
			borderHover,
			boxShadow,
			boxShadowHover,
			blockMargin,
			blockPadding,
			blockZIndex,
			hideOnDesktop,
			hideOnTablet,
			hideOnMobile,
			cssID,
			className,
		},
		setAttributes,
	} = props;
	return (
		<>
			<InspectorTabs />
			<GeneralInspectorControls>
				<Panel title={__("Properties", "magazine-blocks")} initialOpen>
					<Slider
						label={__("Width", "magazine-blocks")}
						onChange={(val) => setAttributes({ colWidth: val })}
						value={colWidth}
						min={0}
						max={100}
						step={1}
						defaultUnit="%"
						forceShowUnit
						responsive
					/>
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("Background", "magazine-blocks")} initialOpen>
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
				<Panel title={__("Border", "magazine-blocks")}>
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
									value={border}
									onChange={(v) => {
										setAttributes({
											border: v,
										});
									}}
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
							<TabPanel p="16px 0">
								<Border
									value={borderHover}
									onChange={(v) => {
										setAttributes({
											borderHover: v,
										});
									}}
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
			</StyleInspectorControls>
			<AdvancedInspectorControls>
				<Panel title={__("Spacing", "magazine-blocks")}>
					<Dimensions
						value={blockPadding}
						responsive
						label={__("Block Padding", "magazine-blocks")}
						min={0}
						max={500}
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) => setAttributes({ blockPadding: val })}
						resetAttributeKey="blockPadding"
					/>
					<Dimensions
						value={blockMargin}
						responsive
						label={__("Block Margin", "magazine-blocks")}
						min={0}
						max={500}
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) => setAttributes({ blockMargin: val })}
						resetAttributeKey="blockMargin"
					/>
				</Panel>
				<Panel title={__("Z-index", "magazine-blocks")}>
					<Slider
						label={__("Z-Index", "magazine-blocks")}
						value={blockZIndex}
						min={0}
						max={10000}
						step={1}
						onChange={(val) => setAttributes({ blockZIndex: val })}
						resetAttributeKey="blockZIndex"
					/>
				</Panel>
				<ResponsiveControls
					{...{
						hideOnDesktop,
						hideOnTablet,
						hideOnMobile,
						setAttributes,
					}}
				/>
				<InspectorAdvancedControls>
					<Input
						value={cssID || ""}
						label={__("CSS ID", "magazine-blocks")}
						onChange={(val) => setAttributes({ cssID: val })}
					/>
				</InspectorAdvancedControls>
			</AdvancedInspectorControls>
		</>
	);
};

export default InspectorControls;
