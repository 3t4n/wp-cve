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
import { Dimensions, Select, Slider } from "../../../controls";

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
			columnGap,
			category,
			postCount,
			radius,
			column,

			blockPadding,
			blockMargin,
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
				<Panel title={__("General", "magazine-blocks")} initialOpen>
					<Select
						value={column}
						onChange={(val) => setAttributes({ column: val })}
						label={__("Column", "magazine-blocks")}
						options={[
							{
								label: __("1", "magazine-blocks"),
								value: "1",
							},
							{
								label: __("2", "magazine-blocks"),
								value: "2",
							},
							{
								label: __("3", "magazine-blocks"),
								value: "3",
							},
						]}
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
					<Slider
						label={__("Number of Posts", "magazine-blocks")}
						value={postCount}
						step={1}
						min={1}
						max={15}
						onChange={(val) => setAttributes({ postCount: val })}
					/>
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
				<Panel title={__("General", "magazine-blocks")} initialOpen>
					<Slider
						onChange={(val: any) =>
							setAttributes({ columnGap: val })
						}
						label={__("Gap", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={40}
						value={columnGap}
					/>
				</Panel>
				<Panel title={__("Image", "magazine-blocks")}>
					<Dimensions
						value={radius || {}}
						responsive
						label={__("Border Radius", "magazine-blocks")}
						defaultUnit="px"
						units={["px", "rem", "em", "%"]}
						onChange={(val) => setAttributes({ radius: val })}
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
