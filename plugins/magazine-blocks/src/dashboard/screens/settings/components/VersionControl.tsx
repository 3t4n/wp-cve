import {
	Alert,
	AlertDescription,
	Box,
	Heading,
	Stack,
	Switch,
} from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import React from "react";
import { useFormContext } from "react-hook-form";
import { useQuery } from "react-query";
import { VersionControlSettingsMap } from "../../../types";
import FormTwoColumn from "./FormTwoColumn";

type Props = {
	data?: VersionControlSettingsMap;
};

const VersionControl: React.FC<Props> = (props) => {
	const [version, setVersion] = React.useState<{
		label?: string;
		value?: string;
	}>();
	const { register } = useFormContext();
	const versionControlQuery = useQuery(
		["version-control"],
		() =>
			apiFetch<
				Array<{
					label: string;
					value: string;
				}>
			>({
				path: "/magazine-blocks/v1/version-control",
			}),
		{
			onSuccess(data) {
				setVersion(data[0]);
			},
		}
	);

	return (
		<Box>
			<Heading fontWeight="semibold" fontSize="3xl" as="h2" mb="8">
				{__("Version Control", "magazine-blocks")}
			</Heading>
			<Stack
				bgColor="#FAFAFC"
				border="1px"
				borderColor="gray.200"
				borderRadius="lg"
				maxWidth="4xl"
				py="4"
				px="5"
			>
				<Stack gap="7">
					{/* <FormTwoColumn
						label={__('Rollback Version', 'magazine-blocks')}
						description={sprintf(
							__(
								'Encountering a problem with Magazine Blocks version %s? Consider reverting to an earlier version that was trouble-free prior to the issue arising.',
								'magazine-blocks',
							),
							localized.version,
						)}
						labelAlignStart
					>
						<Stack mt="1">
							<HStack>
								<Select
									options={versionControlQuery.data}
									isLoading={versionControlQuery.isLoading}
									value={version}
									isSearchable={false}
									onChange={(value) =>
										setVersion({
											label: value?.label,
											value: value?.value,
										})
									}
									styles={{
										...reactSelectStyles,
										container(base) {
											return {
												...base,
												width: 150,
											};
										},
									}}
									theme={reactSelectTheme}
								/>
								{version && (
									<Button
										variant="outline"
										colorScheme="primary"
										size="sm"
										fontWeight="normal"
										borderRadius="base"
									>
										{sprintf(__('Reinstall v%s', 'magazine-blocks'), version.label)}
									</Button>
								)}
							</HStack>
							<Alert status="warning">
								<AlertDescription>
									{__(
										'Before initiating the rollback, make sure to create a backup of your website.',
										'magazine-blocks',
									)}
								</AlertDescription>
							</Alert>
						</Stack>
					</FormTwoColumn> */}
					<FormTwoColumn
						label={__("Become Beta Tester")}
						description={__(
							"Activate the Beta Tester feature to receive notifications whenever a new beta release of Magazine Blocks becomes available.",
							"magazine-blocks"
						)}
						labelAlignStart
					>
						<Stack mt="1">
							<Switch
								{...register("version-control.beta-tester", {
									value: props.data?.["beta-tester"],
								})}
							/>
							<Alert status="warning">
								<AlertDescription>
									{__(
										"It is not advisable to install a beta version on production websites.",
										"magazine-blocks"
									)}
								</AlertDescription>
							</Alert>
						</Stack>
					</FormTwoColumn>
				</Stack>
			</Stack>
		</Box>
	);
};

export default VersionControl;
