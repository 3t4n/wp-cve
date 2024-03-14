import { BlockSaveProps } from "@wordpress/blocks";
import classnames from "classnames";
import React from "react";
import { Element } from "../../components";
import IconRenderer from "../../components/common/IconRenderer";
import "../assets/sass/blocks/heading/style.scss";
import "../assets/sass/blocks/social-icons/style.scss";

const Save: React.ComponentType<BlockSaveProps<any>> = (props) => {
	const {
		className,
		attributes: { link, clientId, icon, size, hideOnDesktop },
	} = props;

	const classNames = classnames(
		`mzb-social-icon mzb-social-icon-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

	return (
		<span className={classNames}>
			<Element
				tagName="a"
				htmlAttrs={{
					href: link && link.url ? link.url : "#",
					target: link && link.newTab ? "_blank" : null,
					rel: link && link.newTab ? "noopener" : null,
				}}
			>
				<IconRenderer name={icon.icon || ""} />
			</Element>
		</span>
	);
};

export default Save;
