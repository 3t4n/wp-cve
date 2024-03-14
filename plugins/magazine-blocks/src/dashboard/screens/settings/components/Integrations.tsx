import { Box, Heading, Input, Stack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import { useFormContext } from "react-hook-form";
import { IntegrationsSettingsMap } from "../../../types";
import FormTwoColumn from "./FormTwoColumn";

type Props = {
	data?: IntegrationsSettingsMap;
};

const Integrations: React.FC<Props> = (props) => {
	const { register } = useFormContext();

	return (
		<Box>
			<Heading fontWeight="semibold" fontSize="3xl" as="h2" mb="8">
				{__("Integrations", "magazine-blocks")}
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
							"Date & Weather Embed API Key",
							"magazine-blocks"
						)}
						labelAlignStart
					>
						<Stack>
							<Input
								{...register("integrations.dateWeatherApiKey", {
									value: props.data?.["dateWeatherApiKey"],
								})}
								borderRadius="base"
							/>
						</Stack>
					</FormTwoColumn>
				</Stack>
				<Stack gap="7">
					<FormTwoColumn
						label={__("Date & Weather Zip Code", "magazine-blocks")}
						labelAlignStart
					>
						<Stack>
							<Input
								{...register(
									"integrations.dateWeatherZipCode",
									{
										value: props.data?.[
											"dateWeatherZipCode"
										],
									}
								)}
								borderRadius="base"
							/>
						</Stack>
					</FormTwoColumn>
				</Stack>
			</Stack>
		</Box>
	);
};

export default Integrations;
