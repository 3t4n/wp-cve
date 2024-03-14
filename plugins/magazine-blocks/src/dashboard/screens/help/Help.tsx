import {
	Button,
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
import facebook from "../../images/facebook.webp";
import x from "../../images/x.webp";
import youtube from "../../images/youtube.webp";

const Help: React.FC = () => {
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
				<Grid
					gridTemplateColumns={{
						sm: "1fr",
						md: "1fr 1fr",
					}}
					gridGap="5"
				>
					<Stack
						px="6"
						py="8"
						align="center"
						gap="3"
						bgColor="white"
						borderRadius="base"
						border="1px"
						borderColor="gray.100"
						textAlign="center"
					>
						<Icon.Chat w="8" h="8" fill="primary.500" />
						<Heading as="h3" size="sm" fontWeight="semibold">
							{__("Support", "magazine-blocks")}
						</Heading>
						<Text fontSize="13px" color="gray.700">
							{__(
								"We would be happy to guide any of your issues and queries 24/7",
								"magazine-blocks"
							)}
						</Text>
						<Button
							mt="10"
							as={Link}
							variant="outline"
							colorScheme="primary"
							borderRadius="base"
							fontSize="14px"
							fontWeight="normal"
							href="https://wpblockart.com/contact/"
							isExternal
						>
							{__("Contact Support", "magazine-blocks")}
						</Button>
					</Stack>
					<Stack
						px="6"
						py="8"
						align="center"
						gap="3"
						bgColor="white"
						borderRadius="base"
						border="1px"
						borderColor="gray.100"
						textAlign="center"
					>
						<Icon.DocsLines w="8" h="8" fill="primary.500" />
						<Heading as="h3" size="sm" fontWeight="semibold">
							{__("Need Some Help?", "magazine-blocks")}
						</Heading>
						<Text fontSize="13px" color="gray.700">
							{__(
								"Please check out basic documentation for detailed information on how to use Magazine Blocks.",
								"magazine-blocks"
							)}
						</Text>
						<Button
							mt="10"
							as={Link}
							colorScheme="primary"
							variant="link"
							borderRadius="base"
							fontSize="14px"
							fontWeight="normal"
							textDecor="underline"
							href="https://docs.wpblockart.com/magazine-blocks/"
							isExternal
						>
							{__("View Now", "magazine-blocks")}
						</Button>
					</Stack>
				</Grid>
				<Stack>
					<Heading as="h3" fontSize="lg" fontWeight="semibold">
						{__("Join Our Community", "magazine-blocks")}
					</Heading>
				</Stack>
				<Grid
					gridTemplateColumns="1fr 1fr"
					p="4"
					bgColor="white"
					border="1px"
					borderColor="gray.100"
					borderRadius="base"
					gridGap="7"
				>
					<Image src={facebook} w="full" />
					<Stack gap="2" justify="center">
						<Heading
							as="h3"
							fontSize="xl"
							fontWeight="normal"
							color="gray.700"
						>
							{__("Facebook Community", "magazine-blocks")}
						</Heading>
						<Text fontSize="13px" color="gray.700">
							{__(
								"Join our Facebook community, where the latest news and updates eagerly await your arrival.",
								"magazine-blocks"
							)}
						</Text>
						<Button
							as={Link}
							colorScheme="primary"
							borderRadius="base"
							fontSize="14px"
							fontWeight="normal"
							alignSelf="start"
							mt="5"
							color="white !important"
							isExternal
							href="https://www.facebook.com/themegrill"
						>
							{__("Join Group", "magazine-blocks")}
						</Button>
					</Stack>
				</Grid>
				<Grid
					gridTemplateColumns="1fr 1fr"
					p="4"
					bgColor="white"
					border="1px"
					borderColor="gray.100"
					borderRadius="base"
					gridGap="7"
				>
					<Image src={x} />
					<Stack gap="2" justify="center">
						<Heading
							as="h3"
							fontSize="xl"
							fontWeight="normal"
							color="gray.700"
						>
							{__("X Community", "magazine-blocks")}
						</Heading>
						<Text fontSize="13px" color="gray.700">
							{__(
								"Join our X community, where the latest news and updates eagerly await your arrival.",
								"magazine-blocks"
							)}
						</Text>
						<Button
							as={Link}
							borderRadius="base"
							fontSize="14px"
							fontWeight="normal"
							alignSelf="start"
							mt="5"
							color="white !important"
							bgColor="black !important"
							isExternal
							href="https://twitter.com/themegrill"
						>
							{__("Join Group", "magazine-blocks")}
						</Button>
					</Stack>
				</Grid>
				<Grid
					gridTemplateColumns="1fr 1fr"
					p="4"
					bgColor="white"
					border="1px"
					borderColor="gray.100"
					borderRadius="base"
					gridGap="7"
				>
					<Image src={youtube} />
					<Stack gap="2" justify="center">
						<Heading
							as="h3"
							fontSize="xl"
							fontWeight="normal"
							color="gray.700"
						>
							{__("YouTube Community", "magazine-blocks")}
						</Heading>
						<Text fontSize="13px" color="gray.700">
							{__(
								"Subscribe to our YouTube channel, where the latest news and updates eagerly await your arrival.",
								"magazine-blocks"
							)}
						</Text>
						<Button
							as={Link}
							colorScheme="red"
							borderRadius="base"
							fontSize="14px"
							fontWeight="normal"
							alignSelf="start"
							mt="5"
							color="white !important"
							isExternal
							href="https://www.youtube.com/@ThemeGrillOfficial"
						>
							{__("Subscribe", "magazine-blocks")}
						</Button>
					</Stack>
				</Grid>
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
						isExternal
						color="primary.500 !important"
						textDecor="underline"
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
						href="https://wpblockart.com/contact/"
						color="primary.500 !important"
						textDecor="underline"
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
						href="https://wordpress.org/support/plugin/magazine-blocks/reviews/?rate=5#new-post"
						color="primary.500 !important"
						textDecor="underline"
						isExternal
					>
						{__("Submit a Review", "magazine-blocks")}
					</Link>
				</Stack>
			</Stack>
		</Grid>
	);
};

export default Help;
