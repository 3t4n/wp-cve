import { __ } from "@wordpress/i18n";
import React from "react";
import AdvancedControls from "../../../components/common/AdvancedControls";
import InspectorTabs, {
	AdvancedInspectorControls,
	GeneralInspectorControls,
} from "../../../components/common/InspectorTabs";
import Panel from "../../../components/common/Panel";
import ResponsiveControls from "../../../components/common/ResponsiveControls";
import { IconPicker, Slider, UrlInput } from "../../../controls";

type Props = {
	attributes: any;
	setAttributes: (attributes: any) => void;
};

const InspectorControls: React.ComponentType<Props> = (props) => {
	const {
		attributes: {
			link,
			icon,
			iconSize,
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
					<UrlInput
						label={__("URL", "magazine-blocks")}
						onChange={(val) => setAttributes({ link: val })}
						value={link}
						placeholder="https://"
						newTab
					/>
				</Panel>
				<Panel title={__("Icons", "magazine-blocks")}>
					<IconPicker
						hasToggle={true}
						value={icon || {}}
						onChange={(val) => setAttributes({ icon: val })}
					/>
					<Slider
						onChange={(val: any) =>
							setAttributes({ iconSize: val })
						}
						label={__("Size", "magazine-blocks")}
						units={["px", "em", "%"]}
						responsive={true}
						min={0}
						max={40}
						value={iconSize}
					/>
				</Panel>
			</GeneralInspectorControls>
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
