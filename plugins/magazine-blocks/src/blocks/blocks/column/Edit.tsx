import {
	useBlockStyle,
	useClientId,
	useCopyPasteStyles,
	useDeviceType,
} from "@blocks/hooks";
import { cn } from "@blocks/utils";
import {
	BlockControls,
	InnerBlocks,
	useInnerBlocksProps,
} from "@wordpress/block-editor";
import { createBlock } from "@wordpress/blocks";
import { Button, Toolbar } from "@wordpress/components";
import { dispatch, select, useSelect } from "@wordpress/data";
import { applyFilters } from "@wordpress/hooks";
import { __ } from "@wordpress/i18n";
import { EditProps } from "blocks/types";
import React from "react";
import * as selectors from "wordpress__block-editor/store/selectors";
import InspectorControls from "./components/InspectorControls";

const Edit: React.ComponentType<EditProps<any>> = (props) => {
	const {
		attributes: { colWidth, cssID, className },
		setAttributes,
	} = props;

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "column",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const classNames = applyFilters(
		"mzb.column.classnames",
		cn(`mzb-column mzb-column-${clientId}`, className)
	) as string;

	const { columnsIds, hasChildBlocks, rootClientId } = useSelect(
		(select) => {
			const {
				getBlockOrder,
				getBlockRootClientId,
				getPreviousBlockClientId,
				getNextBlockClientId,
			} = select("core/block-editor") as typeof selectors;

			const rootId = getBlockRootClientId(props.clientId);

			return {
				hasChildBlocks: getBlockOrder(props.clientId).length > 0,
				rootClientId: rootId ?? undefined,
				columnsIds: getBlockOrder(rootId ?? undefined),
				nextBlockClientId: getNextBlockClientId(props.clientId),
				prevBlockClientId: getPreviousBlockClientId(props.clientId),
			};
		},
		[props.clientId]
	);

	const addRemoveBlock = (type: string) => {
		const { getBlockIndex, getBlocks } = select("core/block-editor");
		const { replaceInnerBlocks } = dispatch("core/block-editor");
		const selectedBlockIndex = getBlockIndex(props.clientId, rootClientId);
		const innerBlocks = [...getBlocks(rootClientId)];

		if (type === "delete") {
			innerBlocks.splice(selectedBlockIndex, 1);
		} else {
			innerBlocks.splice(
				selectedBlockIndex + 1,
				0,
				createBlock("magazine-blocks/column")
			);
		}

		replaceInnerBlocks(rootClientId as string, innerBlocks, false);
	};

	const innerBlockProps = useInnerBlocksProps(
		{
			className: "mzb-column-inner",
		},
		{
			templateLock: false,
			renderAppender: hasChildBlocks
				? undefined
				: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
			/>
			{/* @ts-ignore */}
			<BlockControls group="block">
				<Toolbar>
					<Button
						label={__("Add Column", "magazine-blocks")}
						onClick={() => addRemoveBlock("add")}
						icon="plus"
					/>
					{columnsIds.length > 1 && (
						<Button
							label={__("Delete Column", "magazine-blocks")}
							onClick={() => addRemoveBlock("delete")}
							icon="trash"
						/>
					)}
				</Toolbar>
			</BlockControls>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<style>{`#block-${props.clientId} { width: ${colWidth[deviceType]}%; }`}</style>
			<div id={cssID ? cssID : null} className={classNames}>
				<div {...innerBlockProps} />
			</div>
		</>
	);
};

export default Edit;
