import {
	Alert,
	AlertDescription,
	Box,
	Collapse,
	Heading,
	Stack,
	Switch,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import { Controller, useFormContext } from "react-hook-form";
import Select from "react-select";
import { reactSelectStyles, reactSelectTheme } from "../../../configs/styles";
import { PerformanceSettingsMap } from "../../../types";
import { localized } from "../../../utils";
import FormTwoColumn from "./FormTwoColumn";

type Props = {
	data?: PerformanceSettingsMap;
};

const Performance: React.FC<Props> = (props) => {
	const { data } = props;
	const { register, control, watch } = useFormContext();

	const watchAllowedFonts = watch("performance.allow-only-selected-fonts");
	const watchLoadGoogleFontsLocally = watch("performance.local-google-fonts");

	return (
		<Box>
			<Heading fontWeight="semibold" fontSize="3xl" as="h2" mb="8">
				{__("Performance", "magazine-blocks")}
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
						label={__(
							"Load Google Fonts Locally",
							"magazine-blocks"
						)}
					>
						<Switch
							{...register("performance.local-google-fonts", {
								value: data?.["local-google-fonts"],
							})}
							colorScheme="primary"
						/>
					</FormTwoColumn>
					<Collapse in={watchLoadGoogleFontsLocally}>
						<Stack gap="7">
							<FormTwoColumn
								label={__(
									"Preload Local Fonts",
									"magazine-blocks"
								)}
							>
								<Switch
									{...register(
										"performance.preload-local-fonts",
										{
											value: data?.[
												"preload-local-fonts"
											],
										}
									)}
									colorScheme="primary"
								/>
							</FormTwoColumn>
							<FormTwoColumn
								label={__(
									"Allow Only Selected Fonts",
									"magazine-blocks"
								)}
								labelAlignStart
							>
								<Stack>
									<Switch
										{...register(
											"performance.allow-only-selected-fonts",
											{
												value: data?.[
													"allow-only-selected-fonts"
												],
											}
										)}
										colorScheme="primary"
									/>
									<Collapse in={watchAllowedFonts}>
										<Controller
											name="performance.allowed-fonts"
											control={control}
											defaultValue={
												data?.["allowed-fonts"]
											}
											render={({
												field: { value, onChange },
											}) => (
												<Select
													value={value}
													onChange={onChange}
													isMulti
													hideSelectedOptions
													closeMenuOnSelect={false}
													options={localized.googleFonts.slice(
														1
													)}
													styles={reactSelectStyles}
													theme={reactSelectTheme}
													menuPosition="fixed"
													placeholder={__(
														"Select fonts...",
														"magazine-blocks"
													)}
													getOptionLabel={(v) =>
														v.family
													}
													getOptionValue={(v) =>
														v.family
													}
												/>
											)}
										/>
									</Collapse>
									<Alert status="info">
										<AlertDescription>
											{__(
												"Magazine Blocks provides the option to incorporate Google Fonts, allowing you to select from a curated collection of fonts in the block settings. This feature allows for a more streamlined and focused font selection experience.",
												"magazine-blocks"
											)}
										</AlertDescription>
									</Alert>
								</Stack>
							</FormTwoColumn>
						</Stack>
					</Collapse>
				</Stack>
			</Stack>
		</Box>
	);
};

export default Performance;
