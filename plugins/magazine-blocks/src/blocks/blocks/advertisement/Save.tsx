import { BlockSaveProps } from "@wordpress/blocks";
import classnames from "classnames";
import React from "react";
import { Element } from "../../components";
import "./style.scss";

const Save: React.ComponentType<BlockSaveProps<any>> = (props) => {
	const {
		className,
		attributes: { advertisementImage = {}, clientId, imageSize, link },
	} = props;

	const classNames = classnames(
		`mzb-advertisement mzb-advertisement-${clientId}`,
		className
	);

	const imageClassNames = classnames({
		[`wp-image-${advertisementImage?.id}`]: !!advertisementImage?.id,
	});

	let imageHTML = (
		<img
			className={imageClassNames || undefined}
			src={advertisementImage?.url}
			alt={advertisementImage?.alt ?? ""}
			height={advertisementImage?.height ?? undefined}
			width={advertisementImage?.width ?? undefined}
		/>
	);

	if (link) {
		imageHTML = (
			<Element
				tagName="a"
				htmlAttrs={{
					href: link.url,
					target: link?.newTab ? "_blank" : undefined,
					rel: link?.rel ? "noopener" : undefined,
				}}
				children={imageHTML}
			/>
		);
	}

	return (
		<div className={classNames}>
			<div className={`mzb-advertisement-content`}>
				<div className={`mzb-advertisement-${imageSize}`}>
					{imageHTML}
				</div>
			</div>
		</div>
	);
};

export default Save;
