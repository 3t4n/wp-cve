import { BoxProps, useDisclosure } from "@chakra-ui/react";

export interface PopoverDrawerProps {
	label: string | React.FunctionComponent;
	children: React.ReactNode;
	trigger?: React.FunctionComponent<ReturnType<typeof useDisclosure>>;
	popoverProps?: {
		offset?: number;
	};
	popoverDivProps?: BoxProps;
	closeOnFocusOutside?: boolean;
}
