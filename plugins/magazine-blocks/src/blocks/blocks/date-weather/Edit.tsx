import { useClientId, useCopyPasteStyles, useDeviceType } from "@blocks/hooks";
import { localized } from "@blocks/utils";
import { dateI18n, __experimentalGetSettings } from "@wordpress/date";
import { Fragment } from "@wordpress/element";
import { EditProps } from "blocks/types";
import classnames from "classnames";
import React from "react";
import { Icon } from "../../components";
import { useBlockStyle } from "../../hooks";
import "../assets/sass/blocks/featured-posts/style.scss";
import InspectorControls from "./components/InspectorControls";

const Edit: React.ComponentType<EditProps<any>> = (props) => {
	const {
		className,
		attributes: { size, hideOnDesktop },
		setAttributes,
	} = props;

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "date-weather",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const classNames = classnames(
		`mzb-date-weather mzb-date-weather-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
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
				<span className="mzb-weather-icon">
					<Icon type="blockIcon" name="weather" size={34} />{" "}
				</span>

				<span className="mzb-temperature">
					{localized.temperature}°
				</span>
				<div className="mzb-weather-date">
					{localized.weather}
					{/* eslint-disable-next-line no-restricted-syntax */},{" "}
					{dateI18n(
						__experimentalGetSettings().formats.date,
						undefined,
						undefined
					)}
					in {localized.location}
				</div>
			</div>
		</Fragment>
	);
};

// @ts-ignore
export default Edit;
