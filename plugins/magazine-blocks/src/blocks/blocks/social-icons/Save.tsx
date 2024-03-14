import { InnerBlocks } from "@wordpress/block-editor";
import { BlockSaveProps } from "@wordpress/blocks";
import classnames from "classnames";
import React from "react";
import "../assets/sass/blocks/heading/style.scss";
import "../assets/sass/blocks/social-icons/style.scss";

const Save: React.ComponentType<BlockSaveProps<any>> = (props) => {
	const {
		className,
		attributes: { clientId, size, hideOnDesktop },
	} = props;

	const classNames = classnames(
		`mzb-social-icons mzb-social-icons-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

	return (
		<div className={classNames}>
			<InnerBlocks.Content />
		</div>
	);
};

export default Save;
