import {
	useBlockStyle,
	useClientId,
	useCopyPasteStyles,
	useDeviceType,
} from "@blocks/hooks";
import "@splidejs/splide/dist/css/themes/splide-default.min.css";
import { InnerBlocks } from "@wordpress/block-editor";
import { createBlock } from "@wordpress/blocks";
import { dispatch, select, withSelect } from "@wordpress/data";
import { Fragment } from "@wordpress/element";
import classnames from "classnames";
import React from "react";
import { Icon } from "../../components";
import { EditProps } from "../../types";
import "../assets/sass/blocks/social-icons/style.scss";
import InspectorControls from "./components/InspectorControls";
interface Props extends EditProps<any> {}

const Edit: React.ComponentType<Props> = (props) => {
	const {
		className,
		attributes: { size },

		setAttributes,
	} = props;

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "social-icons",
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
			<div className={classNames}>
				<div className="mzb-social-links">
					<InnerBlocks
						// @ts-ignore
						template={getDefaultSocialIcon()}
						allowedBlocks={["magazine-blocks/social-icon"]}
					/>
					<div className="mzb-social-icons-insert">
						<button onClick={addIcon}>
							{" "}
							<Icon type="frontendIcon" name="plus"></Icon>
						</button>
					</div>
				</div>
			</div>
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
