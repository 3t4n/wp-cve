import {
	useBlockStyle,
	useClientId,
	useCopyPasteStyles,
	useDeviceType,
} from "@blocks/hooks";
import "@splidejs/splide/dist/css/themes/splide-default.min.css";
import { createBlock } from "@wordpress/blocks";
import { dispatch, select, withSelect } from "@wordpress/data";
import { Fragment, useState } from "@wordpress/element";
import classnames from "classnames";
import React from "react";
import IconRenderer from "../../components/common/IconRenderer";
import { EditProps } from "../../types";
import "../assets/sass/blocks/social-icons/style.scss";
import InspectorControls from "./components/InspectorControls";

interface Props extends EditProps<any> {
	enableByDefault: true; // Update the type to match your actual prop type
	value: true; // Update the type to match your actual prop type
	type: string;
}

const Edit: React.ComponentType<Props> = (props) => {
	const {
		className,
		attributes: { size, icon },

		setAttributes,
	} = props;

	const [isVisible, setIsVisible] = useState(false);

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "social-icon",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const classNames = classnames(
		`mzb-social-icons mzb-social-icons-${clientId}`,
		size && `is-${size}`,
		className
	);

	const getDefaultSocialIcon = () =>
		["facebook", "twitter", "linkedin", "youtube"].map((icon) => [
			"magazine-blocks/social-icon",
			{ icon: { enable: true, icon } },
		]);

	const addIcon = () => {
		const { getBlocks } = select("core/block-editor");
		const { replaceInnerBlocks } = dispatch("core/block-editor");
		const innerBlocks = [...getBlocks(props.clientId)];
		innerBlocks.splice(
			innerBlocks.length + 1,
			0,
			createBlock("magazine-blocks/social-icon")
		);
		replaceInnerBlocks(props.clientId, innerBlocks, false);
	};

	return (
		<Fragment>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			{/* eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions */}
			<span
				className={`mzb-social-icon mzb-social-icon-${clientId}`}
				onClick={() => setIsVisible(true)}
			>
				{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
				<a href="#">
					{" "}
					<IconRenderer name={icon.icon || ""} />{" "}
				</a>
			</span>
		</Fragment>
	);
};

// @ts-ignore
export default withSelect((select, props) => {
	const {
		attributes: { size },
	} = props;
	const query = {};
})(Edit);
