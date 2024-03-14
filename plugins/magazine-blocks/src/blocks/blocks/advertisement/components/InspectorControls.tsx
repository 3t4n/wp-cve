import { Media } from "@blocks/components";
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
	TextAlignCenter,
	TextAlignLeft,
	TextAlignRight,
} from "../../../components/icons/Icons";
import {
	Dimensions,
	Select,
	ToggleButton,
	ToggleButtonGroup,
	UrlInput,
} from "../../../controls";

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
			imageSize,
			alignment,
			advertisementImage,
			link,
			radius,
			blockPadding,
			blockMargin,
			blockZIndex,
			cssID,
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
					<Select
						options={[
							{
								label: __("728 x 90", "magazine-blocks"),
								value: "728x90",
							},
							{
								label: __("160 x 600", "magazine-blocks"),
								value: "160x600",
							},
							{
								label: __("250 x 250", "magazine-blocks"),
								value: "250x250",
							},
							{
								label: __("468 x 60", "magazine-blocks"),
								value: "468x60",
							},
							{
								label: __("970 x 90", "magazine-blocks"),
								value: "970x90",
							},
						]}
						onChange={(val) => setAttributes({ imageSize: val })}
						value={imageSize}
						label={__("Image Size", "magazine-blocks")}
					/>
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
					<Media
						onChange={(val) =>
							setAttributes({ advertisementImage: val })
						}
						label={__("Advertisement Image", "magazine-block")}
						type={"image"}
						value={advertisementImage}
					/>
					<UrlInput
						onChange={(val) => setAttributes({ link: val })}
						label={__("Advertisement URL", "magazine-blocks")}
						value={link}
						newTab
						noFollow
					/>
				</Panel>
			</GeneralInspectorControls>
			<StyleInspectorControls>
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
