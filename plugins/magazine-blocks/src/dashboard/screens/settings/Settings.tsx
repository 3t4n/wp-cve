import {
	Box,
	Button,
	HStack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Text,
	useMediaQuery,
	useToast,
} from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import React from "react";
import { FormProvider, useForm } from "react-hook-form";
import { useMutation, useQuery, useQueryClient } from "react-query";
import { useSearchParams } from "react-router-dom";
import * as Icon from "../../components/Icon";
import { SETTINGS_TABS } from "../../constants";
import { useLocalStorage } from "../../hooks";
import SettingsSkeleton from "../../skeleton/SettingsSkeleton";
import { SettingsMap } from "../../types";
import AssetGeneration from "./components/AssetGeneration";
import EditorOptions from "./components/EditorOptions";
import Integrations from "./components/Integrations";
import MaintenanceMode from "./components/MaintenanceMode";
import Performance from "./components/Performance";
import VersionControl from "./components/VersionControl";

const TABS_INDEX_MAP = [
	"editor-options",
	"asset-generation",
	"performance",
	"integrations",
	"maintenance-mode",
];

const Settings: React.FC = () => {
	const methods = useForm();
	const toast = useToast();
	const queryClient = useQueryClient();
	const settingsQuery = useQuery(["settings"], () =>
		apiFetch<SettingsMap>({
			path: "magazine-blocks/v1/settings",
		})
	);
	const isSmallThan961 = useMediaQuery("(max-width: 961px)");
	const [isCollapsed, setIsCollapsed] = useLocalStorage({
		key: "_magazine_blocks_settings_nav_collapsed",
		defaultValue: false,
	});
	const [searchParams, setSearchParams] = useSearchParams();
	const currentTab = searchParams.get("tab") ?? "editor-options";

	const save = useMutation(
		(data: SettingsMap) =>
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
					title: __("Settings saved successfully", "magazine-blocks"),
				});
			},
			onError(e: Error) {
				toast({
					status: "error",
					isClosable: true,
					title: __("Error saving settings", "magazine-blocks"),
					description: e.message,
				});
			},
		}
	);

	const onSubmit = (data: any) => {
		save.mutate(data);
	};

	const TabButton: React.FC<{
		label: string;
		icon?: React.ReactNode;
	}> = ({ label, icon }) => {
		return (
			<Tab
				color="gray.700"
				py="3.5"
				px={!isCollapsed ? "6" : 0}
				borderBottom="1px solid #E1E1E1"
				_selected={{
					borderRight: "1px solid white",
					marginRight: "-1px",
					color: "primary.500",
					bgColor: "white",
					position: "relative",
					"&::after": {
						content: '""',
						width: "1",
						height: "full",
						left: 0,
						bgColor: "primary.500",
						position: "absolute",
					},
				}}
			>
				{icon}
				{!isCollapsed && (
					<HStack
						flex="1"
						justify="space-between"
						align="center"
						ml="3"
					>
						<Text fontSize="sm" as="span">
							{label}
						</Text>
						<Icon.ArrowRight w="5" h="5" />
					</HStack>
				)}
			</Tab>
		);
	};

	if (settingsQuery.isLoading) {
		return <SettingsSkeleton />;
	}
	return (
		<Box my="8" mx="6">
			<FormProvider {...methods}>
				<form onSubmit={methods.handleSubmit(onSubmit)}>
					<Tabs
						variant="unstyled"
						orientation="vertical"
						onChange={(t) => {
							setSearchParams({
								tab: TABS_INDEX_MAP.at(t) ?? "editor-options",
							});
						}}
						defaultIndex={
							TABS_INDEX_MAP.includes(currentTab)
								? TABS_INDEX_MAP.findIndex(
										(t) => t === currentTab
								  )
								: 0
						}
					>
						<TabList
							width="30%"
							bgColor="#FAFAFC"
							border="1px solid #E9E9E9"
							borderTopStartRadius="base"
							maxW={isCollapsed ? "73px" : undefined}
						>
							{SETTINGS_TABS.map(({ label, icon: TabIcon }) => (
								<TabButton
									key={label}
									label={label}
									icon={
										<TabIcon
											w="6"
											h="6"
											fill="currentcolor"
										/>
									}
								/>
							))}
							<Button
								variant="unstyled"
								color="gray.700"
								py="3.5"
								px={!isCollapsed ? "6" : 0}
								borderBottom="1px"
								borderBottomColor="gray.300"
								display="flex"
								height="auto"
								borderRadius="0"
								onClick={() => setIsCollapsed((prev) => !prev)}
							>
								<Icon.ArrowRightFill
									transform={
										isCollapsed
											? "rotate(180deg)"
											: undefined
									}
									w="6"
									h="6"
									fill="currentColor"
								/>
								{!isCollapsed && (
									<HStack
										flex="1"
										justify="space-between"
										align="center"
										ml="3"
									>
										<Text fontSize="sm" as="span">
											{__(
												"Collapse Menu",
												"magazine-blocks"
											)}
										</Text>
									</HStack>
								)}
							</Button>
						</TabList>
						<TabPanels
							width="70%"
							border="1px"
							borderColor="gray.200"
							borderLeft="none"
							bgColor="white"
							py="4"
							px="5"
						>
							<TabPanel p="0">
								<EditorOptions
									data={settingsQuery.data?.editor}
								/>
							</TabPanel>
							<TabPanel p="0">
								<AssetGeneration
									data={
										settingsQuery.data?.["asset-generation"]
									}
								/>
							</TabPanel>
							<TabPanel p="0">
								<Performance
									data={settingsQuery.data?.performance}
								/>
							</TabPanel>
							<TabPanel p="0">
								<VersionControl
									data={
										settingsQuery.data?.["version-control"]
									}
								/>
							</TabPanel>
							<TabPanel p="0">
								<Integrations
									data={settingsQuery.data?.integrations}
								/>
							</TabPanel>
							<TabPanel p="0">
								<MaintenanceMode
									data={
										settingsQuery.data?.["maintenance-mode"]
									}
								/>
							</TabPanel>
							<Button
								fontWeight="normal"
								fontSize="xs"
								colorScheme="primary"
								variant="solid"
								mt="5"
								type="submit"
								isLoading={save.isLoading}
							>
								{__("Save Changes")}
							</Button>
						</TabPanels>
					</Tabs>
				</form>
			</FormProvider>
		</Box>
	);
};

export default Settings;
