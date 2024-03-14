import {
	Alert,
	AlertDescription,
	Box,
	Collapse,
	FormControl,
	FormErrorMessage,
	Heading,
	Select,
	Stack,
} from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import React from "react";
import { Controller, useFormContext } from "react-hook-form";
import { useQuery } from "react-query";
import AsyncSelect from "react-select/async";
import { reactSelectStyles, reactSelectTheme } from "../../../configs/styles";
import { MaintenanceModeSettingsMap, WPPagesResponse } from "../../../types";
import FormTwoColumn from "./FormTwoColumn";

type Props = {
	data?: MaintenanceModeSettingsMap;
};

const MaintenanceMode: React.FC<Props> = (props) => {
	const {
		register,
		watch,
		formState: { errors },
		clearErrors,
	} = useFormContext();
	const watchMode = watch("maintenance-mode.mode");

	const pagesQuery = useQuery(
		["pages"],
		() =>
			apiFetch<WPPagesResponse>({
				path: `wp/v2/pages?per_page=20&status=publish`,
			}),
		{
			keepPreviousData: true,
		}
	);

	return (
		<Box>
			<Heading fontWeight="semibold" fontSize="3xl" as="h2" mb="8">
				{__("Maintenance Mode", "magazine-blocks")}
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
					<FormTwoColumn
						label={__("Mode", "magazine-blocks")}
						description={__(
							'Select either "Coming Soon" mode (which returns an HTTP 200 code) or "Maintenance Mode" (which returns an HTTP 503 code).',
							"magazine-blocks"
						)}
						labelAlignStart
					>
						<Stack>
							<Select
								{...register("maintenance-mode.mode", {
									value: props.data?.["mode"],
								})}
								bg="unset !important"
								borderColor="gray.200 !important"
								sx={{
									"&.chakra-select": {
										maxWidth: "full !important",
									},
								}}
							>
								<option value="none">
									{__("None", "magazine-blocks")}
								</option>
								<option value="maintenance">
									{__("Maintenance", "magazine-blocks")}
								</option>
								<option value="coming-soon">
									{__("Coming soon", "magazine-blocks")}
								</option>
							</Select>
							<Alert status="info" mb="2">
								<AlertDescription>
									{__(
										'Enable "MAINTENANCE MODE" for temporary site offline, or "COMING SOON" for pre-launch.',
										"magazine-blocks"
									)}
								</AlertDescription>
							</Alert>
						</Stack>
					</FormTwoColumn>
					<Collapse in={watchMode && "none" !== watchMode}>
						<FormTwoColumn
							label={__(
								"Maintenance Mode Page",
								"magazine-blocks"
							)}
							labelAlignStart
						>
							<Controller
								name="maintenance-mode.maintenance-page"
								defaultValue={props.data?.["maintenance-page"]}
								rules={{
									required:
										"none" !== watchMode
											? __(
													"Please select a maintenance page.",
													"magazine-blocks"
											  )
											: false,
								}}
								render={({ field: { value, onChange } }) => {
									return (
										<FormControl
											isInvalid={
												!!errors?.[
													"maintenance-mode"
												]?.["maintenance-page"]
											}
										>
											<AsyncSelect
												isClearable
												cacheOptions
												defaultOptions={
													pagesQuery.isSuccess
														? pagesQuery.data?.map(
																(page) => ({
																	value: page.id,
																	label: `#${
																		page.id
																	} ${
																		page
																			.title
																			.rendered ||
																		__(
																			"(No title)",
																			"magazine-blocks"
																		)
																	}`,
																})
														  )
														: []
												}
												loadOptions={(
													search,
													callback
												) => {
													if (!search) {
														callback([]);
														return;
													}
													apiFetch<WPPagesResponse>({
														path: `wp/v2/pages?per_page=20&status=publish&search=${search}`,
													}).then((data) => {
														callback(
															data?.map(
																(page) => ({
																	value: page.id,
																	label: `#${
																		page.id
																	} ${
																		page
																			.title
																			.rendered ||
																		__(
																			"(No title)",
																			"magazine-blocks"
																		)
																	}`,
																})
															)
														);
													});
												}}
												noOptionsMessage={() =>
													__(
														"No pages found",
														"magazine-blocks"
													)
												}
												menuPosition="fixed"
												onChange={(v) => onChange(v)}
												value={value}
												styles={reactSelectStyles}
												theme={reactSelectTheme}
											/>
											{errors?.["maintenance-mode"]?.[
												"maintenance-page"
											] && (
												<FormErrorMessage>
													{
														errors?.[
															"maintenance-mode"
														]?.["maintenance-page"]
															?.message
													}
												</FormErrorMessage>
											)}
										</FormControl>
									);
								}}
							/>
						</FormTwoColumn>
					</Collapse>
				</Stack>
			</Stack>
		</Box>
	);
};

export default MaintenanceMode;
