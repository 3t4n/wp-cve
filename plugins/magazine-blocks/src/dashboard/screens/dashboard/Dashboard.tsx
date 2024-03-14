import {
	Box,
	Button,
	ButtonGroup,
	Grid,
	Heading,
	HStack,
	Image,
	Link,
	Stack,
	Text,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import * as Icon from "../../components/Icon";
import hero from "../../images/magazine-blocks-hero.jpg";
import { localized } from "../../utils";
import UsefulPlugins from "./components/UsefulPlugins";

const Dashboard: React.FC = () => {
	return (
		<Grid
			my="8"
			mx="6"
			gridGap="5"
			gridTemplateColumns={{
				sm: "1fr",
				md: "3fr 1fr",
			}}
		>
			<Stack gap="5">
				<Box
					p="6"
					borderRadius="base"
					border="1px"
					borderColor="gray.100"
					bgColor="white"
				>
					<Heading
						as="h3"
						mb="5"
						fontSize="2xl"
						fontWeight="semibold"
					>
						{__("Welcome to Magazine Blocks", "magazine-blocks")}
					</Heading>
					{/* <Text fontSize="13px" color="gray.700" mb="6">
						{__(
							'Watch our "Getting Started" video series for better understanding of Magazine Blocks. It will guide you through the steps needed to create your website. Then click to create your first page.',
							'magazine-blocks',
						)}
					</Text> */}
					<Image src={hero} />
					<ButtonGroup mt="5" spacing="6">
						<Button
							as={Link}
							colorScheme="primary"
							fontSize="14px"
							fontWeight="normal"
							borderRadius="base"
							color="white !important"
							textDecor="none !important"
							py="3"
							px="6"
							href={`${localized.adminUrl}post-new.php?post_type=page`}
						>
							{__("Create Your First Page", "magazine-blocks")}
						</Button>
						<Button
							as={Link}
							colorScheme="primary"
							fontSize="14px"
							fontWeight="normal"
							borderRadius="base"
							variant="link"
							textDecor="underline"
							color="primary.500 !important"
							href="https://wpblockart.com/"
							isExternal
						>
							{__("Visit Our Website", "magazine-blocks")}
						</Button>
					</ButtonGroup>
				</Box>
				<Box
					bgColor="white"
					border="1px"
					borderColor="gray.100"
					borderRadius="base"
					p="4"
				>
					<Heading as="h3" mb="4" fontSize="lg" fontWeight="semibold">
						{__("Useful Plugins", "magazine-blocks")}
					</Heading>
					<UsefulPlugins />
				</Box>
			</Stack>
			<Stack gap="5">
				<Stack
					p="4"
					gap="3"
					bgColor="white"
					borderRadius="base"
					border="1px"
					borderColor="gray.100"
				>
					<HStack gap="2">
						<Icon.DocsLines w="5" h="5" fill="primary.500" />
						<Heading as="h3" size="sm" fontWeight="semibold">
							{__("Getting Started", "magazine-blocks")}
						</Heading>
					</HStack>
					<Text fontSize="13px" color="gray.700" mb="5">
						{__(
							"Please check out basic documentation for detailed information on how to use Magazine Blocks.",
							"magazine-blocks"
						)}
					</Text>
					<Link
						color="primary.500 !important"
						textDecor="underline"
						href="https://docs.wpblockart.com/magazine-blocks/"
						isExternal
					>
						{__("View Documentation", "magazine-blocks")}
					</Link>
				</Stack>
				<Stack
					p="4"
					gap="3"
					bgColor="white"
					borderRadius="base"
					border="1px"
					borderColor="gray.100"
				>
					<HStack gap="2">
						<Icon.Bulb w="5" h="5" fill="primary.500" />
						<Heading as="h3" size="sm" fontWeight="semibold">
							{__("Feature Request", "magazine-blocks")}
						</Heading>
					</HStack>
					<Text fontSize="13px" color="gray.700" mb="5">
						{__(
							"Please take a moment to suggest any features that could enhance our product.",
							"magazine-blocks"
						)}
					</Text>
					<Link
						color="primary.500 !important"
						textDecor="underline"
						href="https://wpblockart.com/contact/"
						isExternal
					>
						{__("Request a Feature", "magazine-blocks")}
					</Link>
				</Stack>
				<Stack
					p="4"
					gap="3"
					bgColor="white"
					borderRadius="base"
					border="1px"
					borderColor="gray.100"
				>
					<HStack gap="2">
						<Icon.Star w="5" h="5" fill="primary.500" />
						<Heading as="h3" size="sm" fontWeight="semibold">
							{__("Submit us a Review", "magazine-blocks")}
						</Heading>
					</HStack>
					<Text fontSize="13px" color="gray.700" mb="5">
						{__(
							"Sharing your review is a valuable way to help us enhance your experience.",
							"magazine-blocks"
						)}
					</Text>
					<Link
						color="primary.500 !important"
						textDecor="underline"
						href="https://wordpress.org/support/plugin/magazine-blocks/reviews/?rate=5#new-post"
						isExternal
					>
						{__("Submit a Review", "magazine-blocks")}
					</Link>
				</Stack>
				<Stack
					p="4"
					gap="3"
					bgColor="white"
					borderRadius="base"
					border="1px"
					borderColor="gray.100"
				>
					<HStack gap="2">
						<Icon.Video w="5" h="5" fill="primary.500" />
						<Heading as="h3" size="sm" fontWeight="semibold">
							{__("Video Tutorials", "magazine-blocks")}
						</Heading>
					</HStack>
					<Text fontSize="13px" color="gray.700" mb="5">
						{__(
							"Have a look at video tutorials to walk you through getting started.",
							"magazine-blocks"
						)}
					</Text>
					<Link
						color="primary.500 !important"
						textDecor="underline"
						isExternal
						href="https://www.youtube.com/@ThemeGrillOfficial"
					>
						{__("Watch Videos", "magazine-blocks")}
					</Link>
				</Stack>
				<Stack
					p="4"
					gap="3"
					bgColor="white"
					borderRadius="base"
					border="1px"
					borderColor="gray.100"
				>
					<HStack gap="2">
						<Icon.Headphones w="5" h="5" fill="primary.500" />
						<Heading as="h3" size="sm" fontWeight="semibold">
							{__("Support", "magazine-blocks")}
						</Heading>
					</HStack>
					<Text fontSize="13px" color="gray.700" mb="5">
						{__(
							"Get in touch with our support team. You can always submit a support ticket for help.",
							"magazine-blocks"
						)}
					</Text>
					<Link
						color="primary.500 !important"
						textDecor="underline"
						href="https://wordpress.org/support/plugin/magazine-blocks/#new-topic-0"
						isExternal
					>
						{__("Create a Ticket", "magazine-blocks")}
					</Link>
				</Stack>
			</Stack>
		</Grid>
	);
};

export default Dashboard;
