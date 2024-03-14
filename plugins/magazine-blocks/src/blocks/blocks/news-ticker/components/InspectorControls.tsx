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
	ColorPicker,
	IconPicker,
	Input,
	PopoverDrawer,
	Select,
	Slider,
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
			category,
			blockPadding,
			blockMargin,
			icon,
			iconGap,
			iconSize,
			label,
			listColorHover,
			listColor,
			hideOnDesktop,
			hideOnTablet,
			hideOnMobile,
		},
		setAttributes,
		categories,
	} = props;
	return (
		<>
			<InspectorTabs />
			<GeneralInspectorControls>
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
				</Panel>
				<Panel title={__("Ticker Label", "magazine-blocks")}>
					<Input
						onChange={(val) => setAttributes({ label: val })}
						// @ts-ignore
						labelPosition="top"
						label={__("Label", "magazine-blocks")}
						value={label || ""}
					/>
				</Panel>
				<Panel title={__("Icons", "magazine-blocks")}>
					<>
						<IconPicker
							hasToggle={true}
							value={icon || {}}
							onChange={(val) => setAttributes({ icon: val })}
						/>
						<Slider
							label={__("Size", "magazine-blocks")}
							min={0}
							max={50}
							value={iconSize}
							onChange={(val: any) =>
								setAttributes({ iconSize: val })
							}
							responsive
							units={["px", "em", "%"]}
						/>
						<Slider
							label={__("Gap", "magazine-blocks")}
							min={0}
							max={60}
							value={iconGap}
							onChange={(val: any) =>
								setAttributes({ iconGap: val })
							}
							responsive
							units={["px", "em", "%"]}
						/>
					</>
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("Ticker Body", "magazine-blocks")}>
					<PopoverDrawer
						label={__("Text Color", "magazine-blocks")}
						trigger={(props) => (
							<PopoverDrawer.Trigger {...props}>
								<ZStack
									items={[
										{ color: listColor, id: "listColor" },
										{
											color: listColorHover,
											id: "listColorHover",
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
										saved={listColor}
										resetKey="listColor"
										onReset={(v) => {
											setAttributes({ listColor: v });
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={listColor}
										onChange={(v) =>
											setAttributes({ listColor: v })
										}
									/>
								</TabPanel>
								<TabPanel>
									<Reset
										saved={listColorHover}
										resetKey="postTitleHoverColor"
										onReset={(v) => {
											setAttributes({
												listColorHover: v,
											});
										}}
										buttonProps={{
											position: "absolute",
											top: "12px",
											right: "10px",
										}}
									/>
									<ColorPicker
										value={listColorHover}
										onChange={(v) =>
											setAttributes({ listColorHover: v })
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
