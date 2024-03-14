import {
	Alert,
	AlertDescription,
	FormControl,
	FormLabel,
	Grid,
	HStack,
	Stack,
	Switch,
	useToast,
} from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import React from "react";
import { Controller, useForm } from "react-hook-form";
import { useMutation, useQuery, useQueryClient } from "react-query";
import { BLOCKS } from "../../constants";
import { useDebounce } from "../../hooks";
import BlocksSkeleton from "../../skeleton/BlocksSkeleton";
import { BlockSettingsMap, SettingsMap } from "../../types";
import Filters from "./components/Filters";

const Blocks: React.FC = () => {
	const methods = useForm();
	const toast = useToast();
	const queryClient = useQueryClient();
	const settingsQuery = useQuery(["settings"], () =>
		apiFetch<SettingsMap>({
			path: "magazine-blocks/v1/settings",
		})
	);
	const [search, setSearch] = React.useState<string>("");
	const [sort, setSort] = React.useState<"newest" | "alphabetical">("newest");
	const debouncedSearch = useDebounce(search, 400);

	const save = useMutation(
		(data: BlockSettingsMap) =>
			apiFetch({
				path: "magazine-blocks/v1/settings",
				method: "POST",
				data: data,
			}),
		{
			onSuccess() {
				queryClient.invalidateQueries(["settings"]);
				toast({
					status: "success",
					isClosable: true,
					title: __("Blocks saved successfully", "magazine-blocks"),
				});
			},
			onError(e: Error) {
				toast({
					status: "error",
					isClosable: true,
					description: e.message,
					title: __("Error saving blocks", "magazine-blocks"),
				});
			},
		}
	);

	const onSubmit = (data: any) => {
		save.mutate(data);
	};

	const activateDeactivate = (
		mode: "activate" | "deactivate" = "activate"
	) => {
		return () => {
			const data = Object.entries(BLOCKS).reduce((acc, [key, value]) => {
				acc[key] = "activate" === mode ? true : false;
				return acc;
			}, {});
			methods.setValue("blocks", data);
			methods.handleSubmit(onSubmit)();
		};
	};

	const blocks = Object.entries(BLOCKS)
		.sort(([, { label: a }], [, { label: b }]) => {
			if (sort === "alphabetical") {
				if (a < b) {
					return -1;
				}
				if (a > b) {
					return 1;
				}
			}
			return 0;
		})
		.map(([key, val]) => ({
			...val,
			key: key,
			hidden:
				debouncedSearch &&
				!val.label.toLowerCase().includes(debouncedSearch),
		}));

	const allBlockHidden = blocks.every((b) => b.hidden);

	if (settingsQuery.isLoading) return <BlocksSkeleton />;

	return (
		<>
			<Stack px="6" mt="8">
				<Filters
					onActivateAll={activateDeactivate("activate")}
					onDeactivateAll={activateDeactivate("deactivate")}
					isAllActive={Object.values(
						settingsQuery.data?.blocks ?? {}
					).every((v) => v)}
					isAllInactive={Object.values(
						settingsQuery.data?.blocks ?? {}
					).every((v) => !v)}
					loading={save.isLoading}
					onSort={setSort}
					onSearch={setSearch}
					search={search}
					sort={sort}
				/>
				<form onSubmit={methods.handleSubmit(onSubmit)}>
					<Grid
						gridTemplateColumns="repeat(auto-fill, minmax(300px, 1fr))"
						gridGap="4"
						mt="6"
					>
						{allBlockHidden && (
							<Alert status="info" gridColumn="1/-1">
								<AlertDescription>
									{__(
										`Sorry, we couldn't find any blocks matching your search query. Please try a different search.`,
										"magazine-blocks"
									)}
								</AlertDescription>
							</Alert>
						)}
						{blocks.map(
							({ label, icon: BlockIcon, key, hidden }) => (
								<Controller
									key={key}
									name={`blocks.${key}`}
									control={methods.control}
									defaultValue={
										settingsQuery.data?.blocks?.[key]
									}
									render={({
										field: { onChange, value },
									}) => (
										<FormControl
											display={hidden ? "none" : "flex"}
											alignItems="center"
											border="1px"
											borderColor="gray.100"
											p="3"
											borderRadius="base"
											justifyContent="space-between"
											bgColor="white"
										>
											<HStack gap="3">
												<BlockIcon
													fill="#690aa0"
													w="7"
													h="7"
													m="14px"
												/>
												<Stack gap="2">
													<FormLabel
														fontSize="sm"
														fontWeight="semibold"
														lineHeight="150%"
														color="gray.700"
														margin="0"
													>
														{label}
													</FormLabel>
													{/* <HStack gap="4px">
														<HStack
															as={Link}
															gap="4px"
															align="center"
															fontSize="xs"
															color="gray.500"
														>
															<Icon.Docs
																w="14px"
																h="14px"
																fill="currentColor"
															/>
															<Text as="span">{__('Docs', 'magazine-blocks')}</Text>
														</HStack>
														<Text as="span">|</Text>
														<HStack
															as={Link}
															gap="4px"
															align="center"
															fontSize="xs"
															color="gray.500"
														>
															<Icon.ExternalLink
																w="14px"
																h="14px"
																fill="currentColor"
															/>
															<Text as="span">{__('Demo', 'magazine-blocks')}</Text>
														</HStack>
													</HStack> */}
												</Stack>
											</HStack>
											<Switch
												size="sm"
												isChecked={value}
												m="14px"
												colorScheme="primary"
												onChange={(e) => {
													onChange(e.target.checked);
													methods.handleSubmit(
														onSubmit
													)();
												}}
											/>
										</FormControl>
									)}
								/>
							)
						)}
					</Grid>
				</form>
			</Stack>
		</>
	);
};

export default Blocks;
