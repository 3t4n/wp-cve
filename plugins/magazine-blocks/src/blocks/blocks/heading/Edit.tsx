import { useClientId, useCopyPasteStyles, useDeviceType } from "@blocks/hooks";
import { RichText } from "@wordpress/block-editor";
import { Fragment } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { EditProps } from "blocks/types";
import classnames from "classnames";
import { escape } from "lodash";
import React from "react";
import { useBlockStyle } from "../../hooks";

import "../assets/sass/blocks/heading/style.scss";
import InspectorControls from "./components/InspectorControls";

const Edit: React.ComponentType<EditProps<any>> = (props) => {
	const {
		className,
		attributes: {
			enableSubHeading,
			label,
			text,
			cssID,
			markup,
			typography,

			headingLayout1AdvancedStyle,
			headingLayout2AdvancedStyle,
			headingLayout,

			size,
			hideOnDesktop,
		},
		setAttributes,
	} = props;

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "heading",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const classNames = classnames(
		`mzb-heading mzb-heading-${clientId} mzb-${headingLayout}`,
		size && `is-${size}`,
		className,
		typography?._className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop",
		{
			[`mzb-${headingLayout1AdvancedStyle}`]:
				headingLayout === `heading-layout-1`,
			[`mzb-${headingLayout2AdvancedStyle}`]:
				headingLayout === `heading-layout-2`,
		}
	);

	return (
		<Fragment>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<div className={classNames}>
				<div className="mzb-heading-inner">
					<RichText
						id={cssID ? cssID : null}
						tagName={markup}
						placeholder={__("This is heading", "magazine-blocks")}
						value={text}
						onChange={(val) => {
							setAttributes({ text: val });
						}}
					/>
				</div>
				{enableSubHeading && (
					<div className="sub-heading">
						<RichText
							id={cssID ? cssID : null}
							tagName="p"
							value={escape(label)}
							onChange={(val) => {
								setAttributes({ label: val });
							}}
						/>
					</div>
				)}
			</div>
		</Fragment>
	);
};

// @ts-ignore
export default Edit;
