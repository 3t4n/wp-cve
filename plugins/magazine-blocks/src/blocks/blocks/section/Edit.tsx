import { Icon } from "@blocks/components";
import {
	useBlockStyle,
	useClientId,
	useCopyPasteStyles,
	useDeviceType,
} from "@blocks/hooks";
import { cn } from "@blocks/utils";
import { useInnerBlocksProps } from "@wordpress/block-editor";
import { select } from "@wordpress/data";
import { Prettify } from "blocks/components/types";
import { EditProps } from "blocks/types";
import React, { useEffect, useState } from "react";
import InspectorControls from "./components/InspectorControls";
import LayoutPlaceholder from "./components/LayoutPlaceholder";
import { DEFAULT_LAYOUT } from "./constants";
import layoutTemplate from "./helpers/layout-template";

const Edit: React.ComponentType<Prettify<EditProps<any>>> = (props) => {
	const {
		attributes: {
			columns,
			hasModal,
			modalOnly,
			childRow,
			container,
			height,
			overlay,
			cssID,
			className,
			align,
			topSeparator,
			bottomSeparator,
		},
		setAttributes,
	} = props;
	const [defaultLayout, setDefaultLayout] = useState(DEFAULT_LAYOUT);
	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();

	useEffect(() => {
		const { getBlockRootClientId } = select("core/block-editor");
		const parentClientId = getBlockRootClientId(props.clientId);
		setAttributes({ childRow: !!parentClientId });
	}, [props.clientId, setAttributes]);

	const { Style } = useBlockStyle({
		blockName: "section",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const template = layoutTemplate(columns, defaultLayout);

	const innerBlockProps = useInnerBlocksProps(
		{
			className: `mzb-section-inner${
				"fit-to-screen" === height ? " mzb-height-fit-to-screen" : ""
			}`,
		},
		{
			template: template,
			allowedBlocks: ["magazine-blocks/column"],
			renderAppender: undefined,
		}
	);
	if (!columns) {
		return (
			<LayoutPlaceholder
				setAttributes={setAttributes}
				clientId={props.clientId}
				modalOnly={modalOnly}
				hasModal={hasModal}
				setDefaultLayout={setDefaultLayout}
			/>
		);
	}

	return (
		<>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<div
				id={cssID ? cssID : null}
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
						{
							"mzb-container":
								container === "contained" || !childRow,
						},
						{
							"mzb-container-fluid":
								container === "stretched" || childRow,
						}
					)}
				>
					<div {...innerBlockProps} />
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
		</>
	);
};

export default Edit;
