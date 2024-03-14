import { Icon } from "@blocks/components";
import { cn } from "@blocks/utils";
import { InnerBlocks } from "@wordpress/block-editor";
import { BlockSaveProps } from "@wordpress/blocks";
import React from "react";

const Save: React.ComponentType<BlockSaveProps<any>> = (props) => {
	const {
		attributes: {
			clientId,
			container,
			cssID,
			className,
			overlay,
			height,
			align,
			topSeparator,
			bottomSeparator,
		},
	} = props;

	return (
		<div
			id={cssID ?? undefined}
			className={cn(
				`mzb-section mzb-section-${clientId}`,
				className,
				align && `align${align}`
			)}
		>
			{overlay && <div className="mzb-overlay" />}
			{topSeparator.enable && (
				<span className="mzb-top-separator">
					<Icon
						type="frontendIcon"
						name={topSeparator.topSeparatorIcon}
					/>
				</span>
			)}
			<div
				className={cn(
					{ "mzb-container": container === "contained" },
					{ "mzb-container-fluid": container === "stretched" }
				)}
			>
				<div
					className={`mzb-section-inner${
						height === "fit-to-screen"
							? " mzb-height-fit-to-screen"
							: ""
					}`}
				>
					<InnerBlocks.Content />
				</div>
				{bottomSeparator.enable && (
					<span className="mzb-bottom-separator">
						<Icon
							type="frontendIcon"
							name={bottomSeparator.bottomSeparatorIcon}
						/>
					</span>
				)}
			</div>
		</div>
	);
};

export default Save;
