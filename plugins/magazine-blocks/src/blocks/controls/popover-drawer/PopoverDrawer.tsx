import {
	Box,
	Button,
	FormControl,
	FormLabel,
	useDisclosure,
	useId,
} from "@chakra-ui/react";
import { Popover } from "@wordpress/components";
import React from "react";
import { Pencil } from "../../components/icons/Icons";
import { useSharedState } from "../../hooks/useSharedState";
import { PopoverDrawerContext } from "./PopoverDrawerContext";
import { PopoverDrawerProps } from "./types";

const Trigger = (
	props: ReturnType<typeof useDisclosure> & {
		children?: React.ReactNode;
	}
) => {
	const { onToggle, isOpen, children } = props;

	const childrenWithProps = React.Children.map(children, (child) => {
		if (React.isValidElement(child)) {
			return React.cloneElement(child, props);
		}
		return child;
	});

	return (
		<Button onClick={onToggle} variant="icon">
			{children ? (
				childrenWithProps
			) : (
				<Box
					w="5"
					h="5"
					display="flex"
					justifyContent="center"
					alignItems="center"
					bgColor="gray.100"
					borderRadius="sm"
					sx={{
						svg: {
							fill: "gray.400",
							w: "4",
							h: "4",
						},
					}}
					border={isOpen ? "1px" : undefined}
					borderColor="primary.500"
				>
					<Pencil />
				</Box>
			)}
		</Button>
	);
};

export const PopoverDrawer = (props: PopoverDrawerProps) => {
	const id = useId();
	const _id = `popover-drawer-${id}`;
	const popoverContext = React.useContext(PopoverDrawerContext);
	const [popover, setPopover] = useSharedState<Record<string, boolean>>(
		"PopoverDrawers",
		{}
	);
	const disclosure = useDisclosure({
		id: _id,
		onClose() {
			setPopover({ ...popover, [_id]: false });
		},
		onOpen() {
			if (popoverContext?.isChildren) {
				setPopover({
					...popover,
					[_id]: true,
				});
				return;
			}
			setPopover({
				...Object.keys(popover ?? {}).reduce((acc, k) => {
					acc[k] = false;
					return acc;
				}, {}),
				[_id]: true,
			});
		},
		isOpen: popover?.[_id] ?? false,
	});

	const { isOpen, onClose } = disclosure;

	return (
		<>
			<FormControl
				display="flex"
				alignItems="center"
				justifyContent="space-between"
				onClick={(e) => e.stopPropagation()}
			>
				<FormLabel onClick={(e) => e.stopPropagation()}>
					{"string" === typeof props.label
						? props.label
						: props.label({})}
				</FormLabel>
				{props.trigger ? (
					props.trigger(disclosure)
				) : (
					<Trigger {...disclosure} />
				)}
			</FormControl>
			{isOpen && (
				<Box
					className="magazine-blocks-popover-drawer"
					variant="unstyled"
					animate={false}
					placement="left-start"
					as={Popover}
					focusOnMount
					onFocusOutside={props.closeOnFocusOutside ? onClose : false}
					{...props.popoverProps}
					{...props.popoverDivProps}
				>
					<PopoverDrawerContext.Provider
						value={{
							isChildren: true,
						}}
					>
						{props.children}
					</PopoverDrawerContext.Provider>
				</Box>
			)}
		</>
	);
};

PopoverDrawer.Trigger = Trigger;

export default PopoverDrawer;
