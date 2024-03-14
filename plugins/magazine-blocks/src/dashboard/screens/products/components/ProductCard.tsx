import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Button,
	Card,
	CardBody,
	CardFooter,
	Divider,
	Heading,
	HStack,
	Image,
	Link,
	Stack,
	Text,
	useDisclosure,
	useToast,
} from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { useDispatch } from "@wordpress/data";
import { sprintf, __ } from "@wordpress/i18n";
import React from "react";
import { useMutation } from "react-query";
import { PLUGINS } from "../../../constants/products";
import { MAGAZINE_BLOCKS_DASHBOARD_STORE } from "../../../store";
import { MagazineBlocksLocalized, Prettify } from "../../../types";
import { localized } from "../../../utils";

type Props = Prettify<
	Omit<(typeof PLUGINS)[0], "demo" | "logo" | "shortDescription"> & {
		demo?: string;
		logo?: React.ReactNode | React.ElementType;
		shortDescription?: string;
		pluginsStatus: MagazineBlocksLocalized["plugins"];
		themesStatus: MagazineBlocksLocalized["themes"];
	}
>;

const ProductCard: React.FC<Props> = (props) => {
	const {
		label,
		description,
		image,
		website,
		pluginsStatus,
		slug,
		type,
		themesStatus,
	} = props;
	const { setPluginsStatus } = useDispatch(MAGAZINE_BLOCKS_DASHBOARD_STORE);
	const toast = useToast();
	const { isOpen, onOpen, onClose } = useDisclosure();
	const cancelRef = React.useRef<any>();
	const activatePlugin = useMutation(
		({ slug, file }: { slug: string; file: string }) =>
			apiFetch({
				path: `wp/v2/plugins/${slug}`,
				method: "POST",
				data: {
					plugin: file.replace(".php", ""),
					status: "active",
				},
			}),
		{
			onSuccess(data: any) {
				setPluginsStatus({
					[`${data.plugin}.php`]: data.status,
				});
				toast({
					status: "success",
					description: sprintf(
						__(
							"%s plugin activated successfully",
							"magazine-blocks"
						),
						data.name
					),
					isClosable: true,
				});
				onClose();
			},
			onError(e: Error) {
				toast({
					status: "error",
					description: e.message,
					isClosable: true,
				});
				onClose();
			},
		}
	);

	const installPlugin = useMutation(
		(plugin: string) =>
			apiFetch({
				path: "wp/v2/plugins",
				method: "POST",
				data: {
					slug: plugin,
					status: "active",
				},
			}),
		{
			onSuccess(data: any) {
				setPluginsStatus({
					[`${data.plugin}.php`]: data.status,
				});
				toast({
					status: "success",
					description: sprintf(
						__(
							"%s plugin installed and activated successfully",
							"magazine-blocks"
						),
						data.name
					),
					isClosable: true,
				});
				onClose();
			},
			onError(e: Error) {
				toast({
					status: "error",
					description: e.message,
					isClosable: true,
				});
				onClose();
			},
		}
	);

	const installOrActivatePlugin = (pluginFile: string) => {
		const slug = pluginFile.split("/")[0];

		if (pluginsStatus[pluginFile] === "not-installed") {
			installPlugin.mutate(slug);
		} else if (pluginsStatus[pluginFile] === "inactive") {
			activatePlugin.mutate({
				slug: slug,
				file: pluginFile,
			});
		}
	};

	const status = type === "theme" ? themesStatus[slug] : pluginsStatus[slug];
	return (
		<>
			<Card
				overflow="hidden"
				boxShadow="none"
				border="1px"
				borderRadius="base"
				borderColor="gray.100"
			>
				<CardBody p="0">
					<Image w="full" src={image} />
					<Stack gap="2" px="4" py="5">
						<Heading
							as="h3"
							size="md"
							m="0"
							fontSize="md"
							fontWeight="semibold"
						>
							{label}
						</Heading>
						<Text m="0" color="gray.600" fontSize="13px">
							{description}
						</Text>
					</Stack>
				</CardBody>
				<Divider color="gray.300" />
				<CardFooter
					px="4"
					py="5"
					justifyContent="space-between"
					alignItems="center"
				>
					<HStack gap="1" align="center">
						<Link
							href={website}
							fontSize="xs"
							color="gray.500"
							textDecoration="underline"
							isExternal
						>
							{__("Learn More", "magazine-blocks")}
						</Link>
						<Text as="span" lineHeight="1" color="gray.500">
							|
						</Text>
						<Link
							href={website}
							fontSize="xs"
							color="gray.500"
							textDecoration="underline"
							isExternal
						>
							{__("Live Demo", "magazine-blocks")}
						</Link>
					</HStack>
					<Button
						colorScheme="primary"
						size="sm"
						fontSize="xs"
						borderRadius="base"
						fontWeight="semibold"
						_hover={{
							color: "white",
							textDecoration: "none",
						}}
						_focus={{
							color: "white",
							textDecoration: "none",
						}}
						isDisabled={"active" === status}
						as={"theme" === type ? Link : undefined}
						href={
							"theme" === type
								? "inactive" === status
									? `${localized.adminUrl}themes.php?search=${slug}`
									: `${localized.adminUrl}/theme-install.php?search=${slug}`
								: undefined
						}
						onClick={"plugin" === type ? onOpen : undefined}
						isLoading={
							"plugin" === type
								? activatePlugin.isLoading ||
								  installPlugin.isLoading
								: undefined
						}
					>
						{"active" === status
							? __("Active", "magazine-blocks")
							: "inactive" === status
							? __("Activate", "magazine-blocks")
							: __("Install", "magazine-blocks")}
					</Button>
				</CardFooter>
			</Card>
			{type === "plugin" && (
				<AlertDialog
					isOpen={isOpen}
					leastDestructiveRef={cancelRef}
					onClose={onClose}
					isCentered
				>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader
								fontSize="lg"
								fontWeight="semibold"
							>
								{"inactive" === pluginsStatus[slug]
									? __("Activate Plugin", "magazine-blocks")
									: __("Install Plugin", "magazine-blocks")}
							</AlertDialogHeader>
							<AlertDialogBody>
								{"inactive" === pluginsStatus[slug]
									? sprintf(
											__(
												"Are you sure? You want to activate %s plugin.",
												"magazine-blocks"
											),
											label
									  )
									: sprintf(
											__(
												"Are you sure? You want to install and activate %s plugin.",
												"magazine-blocks"
											),
											label
									  )}
							</AlertDialogBody>
							<AlertDialogFooter>
								<Button
									size="sm"
									fontSize="xs"
									fontWeight="normal"
									variant="outline"
									colorScheme="primary"
									isDisabled={
										activatePlugin.isLoading ||
										installPlugin.isLoading
									}
									ref={cancelRef}
									onClick={onClose}
								>
									{__("Cancel", "magazine-blocks")}
								</Button>
								<Button
									size="sm"
									fontSize="xs"
									fontWeight="normal"
									colorScheme="primary"
									onClick={() =>
										installOrActivatePlugin(slug)
									}
									ml={3}
									isLoading={
										activatePlugin.isLoading ||
										installPlugin.isLoading
									}
								>
									{"inactive" === pluginsStatus[slug]
										? __("Activate", "magazine-blocks")
										: __("Install", "magazine-blocks")}
								</Button>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>
			)}
		</>
	);
};

export default ProductCard;
