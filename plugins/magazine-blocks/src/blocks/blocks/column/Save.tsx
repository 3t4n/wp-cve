import { cn } from "@blocks/utils";
import { InnerBlocks } from "@wordpress/block-editor";
import { BlockSaveProps } from "@wordpress/blocks";
import React from "react";

const Save: React.ComponentType<
	BlockSaveProps<{
		clientId: string;
		className?: string;
		cssID?: string;
	}>
> = (props) => {
	const {
		attributes: { clientId, className, cssID },
	} = props;

	return (
		<div
			id={cssID ?? undefined}
			className={cn(`mzb-column mzb-column-${clientId}`, className)}
		>
			<div className="mzb-column-inner">
				<InnerBlocks.Content />
			</div>
		</div>
	);
};

export default Save;
