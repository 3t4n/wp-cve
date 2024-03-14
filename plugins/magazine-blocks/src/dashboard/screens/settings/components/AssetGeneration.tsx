import {
	Box,
	Button,
	Heading,
	Stack,
	Switch,
	useToast,
} from "@chakra-ui/react";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import { AssetGenerationSettingsMap } from "dashboard/types";
import React from "react";
import { useFormContext } from "react-hook-form";
import { useMutation } from "react-query";
import FormTwoColumn from "./FormTwoColumn";

type Props = {
	data?: AssetGenerationSettingsMap;
};

const AssetGeneration: React.FC<Props> = (props) => {
	const toast = useToast();
	const regenerateAssets = useMutation(
		() =>
			apiFetch({
				path: "magazine-blocks/v1/regenerate-assets",
				method: "POST",
			}),
		{
			onSuccess() {
				toast({
					status: "success",
					description: __("Assets regenerated", "magazine-blocks"),
					isClosable: true,
				});
			},
			onError(e: Error) {
				toast({
					status: "error",
					description: e.message,
					isClosable: true,
				});
			},
		}
	);
	const { register } = useFormContext();

	return (
		<Box>
			<Heading fontWeight="semibold" fontSize="3xl" as="h2" mb="8">
				{__("Asset Generation", "magazine-blocks")}
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
						label={__("File Generation", "magazine-blocks")}
						description={__(
							"Magazine Blocks typically embeds dynamic assets (CSS/JS) directly within the page. You can opt to enable this feature for loading dynamic assets (CSS/JS) externally from a file.",
							"magazine-blocks"
						)}
					>
						<Switch
							{...register("asset-generation.external-file")}
							defaultChecked={props.data?.["external-file"]}
							colorScheme="primary"
						/>
					</FormTwoColumn>
					<FormTwoColumn
						label={__("Asset Generation", "magazine-blocks")}
						description={__(
							"To resolve any issues related to block styles, regenerate the assets.",
							"magazine-blocks"
						)}
					>
						<Button
							fontSize="xs"
							colorScheme="primary"
							variant="outline"
							borderRadius="base"
							isLoading={regenerateAssets.isLoading}
							onClick={() => regenerateAssets.mutate()}
						>
							{__(
								"Regenerate Asset Files and Data",
								"magazine-blocks"
							)}
						</Button>
					</FormTwoColumn>
				</Stack>
			</Stack>
		</Box>
	);
};

export default AssetGeneration;
