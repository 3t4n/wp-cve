import {
	Box,
	ButtonGroup,
	ButtonGroupProps,
	IconButton,
	Tooltip,
	useDisclosure,
	useOutsideClick,
} from "@chakra-ui/react";
import React from "react";
import { Desktop, Mobile, Tablet } from "../../components/icons/Icons";
import { DEVICES } from "../../constants";
import useDevice from "../../hooks/useDevice";

type Props = {
	buttonGroupProps?: ButtonGroupProps;
	device?: "desktop" | "tablet" | "mobile";
	setDevice?: (device: "desktop" | "tablet" | "mobile") => void;
};

const DEVICE_ICONS = {
	desktop: Desktop,
	tablet: Tablet,
	mobile: Mobile,
};

const DevicePicker = ({ buttonGroupProps }: Props) => {
	const { device, setDevice } = useDevice();
	const { isOpen, onClose, onOpen } = useDisclosure();
	const ref = React.useRef<HTMLDivElement>(null);

	useOutsideClick({
		ref: ref,
		handler: onClose,
	});

	return (
		<Box h="6" w="6" ref={ref}>
			<ButtonGroup
				{...buttonGroupProps}
				sx={{
					">*:not(style)~*:not(style)": {
						marginInlineStart: "0",
						marginTop: "0",
					},
				}}
				orientation="vertical"
				zIndex={isOpen ? 1 : undefined}
				position="relative"
				translateY={
					device === "desktop"
						? "0%"
						: device === "tablet"
						? "calc(calc(100% / 3) * -1)"
						: "calc(calc(100% / 1.5) * -1)"
				}
				transform={`translateY(var(--chakra-translate-y))`}
				border={isOpen ? "1px" : 0}
				borderColor="primary.500"
				borderRadius="sm"
			>
				{Object.entries(DEVICES).map(([id, label]) => {
					const DeviceIcon = DEVICE_ICONS[id];
					return (
						<Tooltip label={label} placement="right" key={id}>
							<IconButton
								isActive={isOpen && device === id}
								onClick={() => {
									if (device !== id) {
										setDevice(id as typeof device);
										onClose();
									} else {
										onOpen();
									}
								}}
								h={6}
								w={6}
								minW={6}
								aria-label={label}
								visibility={
									device !== id && !isOpen
										? "hidden"
										: "visible"
								}
								opacity={device !== id && !isOpen ? "0" : "1"}
								pointerEvents={
									device !== id && !isOpen ? "none" : "auto"
								}
								bgColor={isOpen ? "white" : "transparent"}
								border="0"
								color="gray.500"
								_active={{
									bgColor: "primary.500",
									color: "white",
								}}
								_hover={{
									color: "white",
									bgColor: "primary.500",
								}}
								borderRadius="0"
								outline="none !important"
								icon={
									<DeviceIcon
										fill="currentColor"
										h="5"
										w="5"
									/>
								}
							></IconButton>
						</Tooltip>
					);
				})}
			</ButtonGroup>
		</Box>
	);
};

export default DevicePicker;
