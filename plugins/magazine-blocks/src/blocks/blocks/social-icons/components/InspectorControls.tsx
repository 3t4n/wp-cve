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
	TextAlignJustify,
	TextAlignLeft,
	TextAlignRight,
} from "../../../components/icons/Icons";
import {
	ColorPicker,
	PopoverDrawer,
	Slider,
	ToggleButton,
	ToggleButtonGroup,
} from "../../../controls";
import Reset from "../../../controls/common/Reset";

type Props = {
	attributes: any;
	setAttributes: (attributes: any) => void;
};

const InspectorControls: React.ComponentType<Props> = (props) => {
	const {
		attributes: {
			alignment,
			columnGap,
			backgroundColor,

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
					<ToggleButtonGroup
						value={alignment}
						onChange={(v) => {
							setAttributes({ alignment: v });
						}}
						label={__("Icon Alignment", "magazine-blocks")}
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
						<ToggleButton value="justify">
							<TextAlignJustify />
						</ToggleButton>
					</ToggleButtonGroup>
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("General", "magazine-blocks")} initialOpen>
					<Slider
						// @ts-ignore
						onChange={(val) => setAttributes({ columnGap: val })}
						label={__("Column Gap", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={40}
						value={columnGap}
					/>
				</Panel>
				<Panel
					title={__("Social Icons", "magazine-blocks")}
					initialOpen
				>
					<PopoverDrawer
						label={__("Background Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{
											color: backgroundColor,
											id: "backgroundColor",
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
										saved={backgroundColor}
										resetKey="backgroundColor"
										onReset={(v) => {
											setAttributes({
												backgroundColor: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={backgroundColor}
										onChange={(v) =>
											setAttributes({
												backgroundColor: v,
											})
										}
									/>
								</TabPanel>
							</TabPanels>
						</Tabs>
					</PopoverDrawer>
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
