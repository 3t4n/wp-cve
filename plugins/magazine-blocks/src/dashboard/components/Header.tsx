import {
	Button,
	Drawer,
	DrawerBody,
	DrawerCloseButton,
	DrawerContent,
	DrawerHeader,
	DrawerOverlay,
	Link,
	Stack,
	Tag,
	useDisclosure,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import { NavLink } from "react-router-dom";
import { ROUTES } from "../constants";
import { localized } from "../utils";
import Changelog from "./Changelog";
import { Logo, Megaphone } from "./Icon";
import IntersectObserver from "./IntersectionObserver";

const Header: React.FC = () => {
	const { isOpen, onOpen, onClose } = useDisclosure();
	const ref = React.useRef<any>();
	return (
		<>
			<Stack
				direction="row"
				minH="70px"
				justify="space-between"
				px="6"
				borderBottom="1px solid #E9E9E9"
				bg="white"
				position={{
					sm: "sticky",
				}}
				top="var(--wp-admin--admin-bar--height, 0)"
				zIndex={1}
			>
				<Stack direction="row" align="center" gap="7">
					<Link as={NavLink} to="/dashboard">
						<Logo h="10" w="10" />
					</Link>
					<IntersectObserver routes={ROUTES}>
						{ROUTES.map(({ route, label }) => (
							<Link
								data-target={route}
								key={route}
								as={NavLink}
								to={route}
								fontSize="sm"
								fontWeight="semibold"
								lineHeight="150%"
								color="#383838"
								_hover={{
									color: "primary.500",
								}}
								_focus={{
									boxShadow: "none",
								}}
								_activeLink={{
									color: "primary.500",
									borderBottom: "3px solid",
									borderColor: "primary.500",
									marginBottom: "-2px",
								}}
								display="inline-flex"
								alignItems="center"
								px="2"
								h="full"
							>
								{label}
							</Link>
						))}
					</IntersectObserver>
				</Stack>
				<Stack direction="row" align="center">
					<Tag
						variant="outline"
						colorScheme="primary"
						borderRadius="xl"
						bgColor="#F8FAFF"
					>
						{localized.version}
					</Tag>
					<Button onClick={onOpen} variant="unstyled">
						<Megaphone w="10" h="10" />
					</Button>
				</Stack>
			</Stack>
			<Drawer
				isOpen={isOpen}
				placement="right"
				onClose={onClose}
				finalFocusRef={ref}
			>
				<DrawerOverlay bgColor="rgba(0,0,0,0.05)" />
				<DrawerContent top="var(--wp-admin--admin-bar--height, 0) !important">
					<DrawerCloseButton />
					<DrawerHeader>
						{__("Latest Updates", "magazine-blocks")}
					</DrawerHeader>
					<DrawerBody>
						<Changelog />
					</DrawerBody>
				</DrawerContent>
			</Drawer>
		</>
	);
};

export default Header;
