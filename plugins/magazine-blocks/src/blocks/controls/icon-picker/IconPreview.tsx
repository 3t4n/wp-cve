import { Box, Button, IconButton } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import { Trash } from "../../../base/components/Icons";

import { ALL_ICONS } from "./icons";

type Props = {
	openModal: () => void;
	previewIcon?: string;
	onRemoveIcon: () => void;
};

export const IconPreview = (props: Props) => {
	const previewIcon = ALL_ICONS.find((icon) => icon.id === props.previewIcon);
	return (
		<Box
			h="28"
			bg={`url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' fill-opacity='.1'%3E%3Cpath d='M200 0h200v200H200zM0 200h200v200H0z'/%3E%3C/svg%3E")`}
			onClick={props.openModal}
			bgSize="25px 25px"
			bgPos="50%"
			role="button"
			tabIndex={0}
			position="relative"
			display="flex"
			alignItems="center"
			justifyContent="center"
			overflow="hidden"
			border="1px"
			borderColor="gray.200"
			borderRadius="base"
			_hover={{
				".magazine-blocks-icon-text": {
					transform: "translateY(0%)",
				},
			}}
		>
			{props.previewIcon ? (
				<>
					<Box
						as="span"
						sx={{
							svg: {
								w: 16,
								h: 16,
								fill: "gray.600",
							},
						}}
						dangerouslySetInnerHTML={{
							__html: previewIcon?.svg ?? "",
						}}
					/>
					<IconButton
						variant="unstyled"
						position="absolute"
						top="0"
						right="0"
						h="6"
						minW="6"
						aria-label={__("Remove icon", "magazine-blocks")}
						icon={<Trash w="4" h="4" fill="gray.600" />}
						onClick={(e) => {
							e.stopPropagation();
							props?.onRemoveIcon();
						}}
					/>
					<Box
						as="span"
						color="gray.700"
						position="absolute"
						bottom="0"
						left="0"
						right="0"
						bg="white"
						textAlign="center"
						transform="translateY(+100%)"
						className="magazine-blocks-icon-text"
						transition="transform .2s ease-in-out"
					>
						{__("Change Icon", "magazine-blocks")}
					</Box>
				</>
			) : (
				<Button
					position="absolute"
					top="50%"
					left="50%"
					transform="translate(-50%, -50%)"
					variant="outline"
					colorScheme="primary"
					borderRadius="base"
					size="sm"
					fontWeight="normal"
					bgColor="white"
				>
					{__("Choose Icon", "magazine-blocks")}
				</Button>
			)}
		</Box>
	);
};
