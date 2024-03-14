import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Button,
	Grid,
	Heading,
	HStack,
	Stack,
	Text,
	useDisclosure,
	useToast,
} from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { useDispatch, useSelect } from "@wordpress/data";
import { sprintf, __ } from "@wordpress/i18n";
import React from "react";
import { useMutation } from "react-query";
import { PLUGINS } from "../../../constants/products";
import { MAGAZINE_BLOCKS_DASHBOARD_STORE } from "../../../store";
import { MagazineBlocksLocalized } from "../../../types";

const Plugin: React.FC<{
	plugin: (typeof PLUGINS)[0];
	index: number;
	pluginsStatus: MagazineBlocksLocalized["plugins"];
}> = ({ plugin, index, pluginsStatus }) => {
	const { setPluginsStatus } = useDispatch(MAGAZINE_BLOCKS_DASHBOARD_STORE);
	const toast = useToast();
	const { isOpen, onOpen, onClose } = useDisclosure();
	const cancelRef = React.useRef<any>();
	const activate = useMutation(
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

	const install = useMutation(
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
			install.mutate(slug);
		} else if (pluginsStatus[pluginFile] === "inactive") {
			activate.mutate({
				slug: slug,
				file: pluginFile,
			});
		}
	};
	return (
		<HStack
			key={plugin.slug}
			gap="4px"
			justify="space-between"
			px="16px"
			py="18px"
			borderRight="1px"
			borderBottom="1px"
			borderColor="gray.100"
			pl={index % 2 === 0 ? "0" : undefined}
		>
			<HStack>
				<plugin.logo w="40px" h="40px" />
				<Stack gap="6px">
					<Heading as="h4" fontSize="14px" fontWeight="semibold">
						{plugin.label}
					</Heading>
					<Text as="span" color="gray.500">
						{plugin.shortDescription}
					</Text>
				</Stack>
			</HStack>
			<Button
				variant="link"
				colorScheme="primary"
				color="primary.500 !important"
				fontSize="14px"
				fontWeight="normal"
				textDecor="underline"
				isLoading={activate.isLoading || install.isLoading}
				isDisabled={"active" === pluginsStatus[plugin.slug]}
				onClick={onOpen}
			>
				{pluginsStatus[plugin.slug] === "active"
					? __("Active", "magazine-blocks")
					: pluginsStatus[plugin.slug] === "inactive"
					? __("Activate", "magazine-blocks")
					: __("Install", "magazine-blocks")}
			</Button>
			<AlertDialog
				isOpen={isOpen}
				leastDestructiveRef={cancelRef}
				onClose={onClose}
				isCentered
			>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader fontSize="lg" fontWeight="semibold">
							{"inactive" === pluginsStatus[plugin.slug]
								? __("Activate Plugin", "magazine-blocks")
								: __("Install Plugin", "magazine-blocks")}
						</AlertDialogHeader>
						<AlertDialogBody>
							{"inactive" === pluginsStatus[plugin.slug]
								? sprintf(
										__(
											"Are you sure? You want to activate %s plugin.",
											"magazine-blocks"
										),
										plugin.label
								  )
								: sprintf(
										__(
											"Are you sure? You want to install and activate %s plugin.",
											"magazine-blocks"
										),
										plugin.label
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
									activate.isLoading || install.isLoading
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
									installOrActivatePlugin(plugin.slug)
								}
								ml={3}
								isLoading={
									activate.isLoading || install.isLoading
								}
							>
								{"inactive" === pluginsStatus[plugin.slug]
									? __("Activate", "magazine-blocks")
									: __("Install", "magazine-blocks")}
							</Button>
						</AlertDialogFooter>
					</AlertDialogContent>
				</AlertDialogOverlay>
			</AlertDialog>
		</HStack>
	);
};

export const UsefulPlugins = () => {
	const pluginsStatus = useSelect((select) => {
		return (
			select(MAGAZINE_BLOCKS_DASHBOARD_STORE) as any
		).getPluginsStatus() as MagazineBlocksLocalized["plugins"];
	}, []);
	return (
		<Grid gridTemplateColumns="1fr 1fr">
			{PLUGINS.map((plugin, i) => (
				<Plugin
					key={plugin.slug}
					pluginsStatus={pluginsStatus}
					plugin={plugin}
					index={i}
				/>
			))}
		</Grid>
	);
};

export default UsefulPlugins;
