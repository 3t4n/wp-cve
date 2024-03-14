import {
	Box,
	Heading,
	HStack,
	InputGroup,
	InputRightAddon,
	NumberInput,
	NumberInputField,
	Stack,
	Switch,
	Tooltip,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import { Controller, useFormContext } from "react-hook-form";
import { EditorSettingsMap } from "../../../types";
import FormTwoColumn from "./FormTwoColumn";

type Props = {
	data?: EditorSettingsMap;
};

const EditorOptions: React.FC<Props> = (props) => {
	const { control, register } = useFormContext();
	return (
		<Box>
			<Heading fontWeight="semibold" fontSize="3xl" as="h2" mb="8">
				{__("Editor Options", "magazine-blocks")}
			</Heading>
			<Stack
				bgColor="primary.50"
				py="4"
				px="5"
				border="1px solid"
				borderColor="gray.200"
				borderRadius="lg"
				maxWidth="4xl"
			>
				<Stack gap="7">
					<Controller
						name="editor.section-width"
						control={control}
						defaultValue={props.data?.["section-width"]}
						render={({ field: { onChange, value } }) => (
							<FormTwoColumn
								label={__(
									"Default Section Width",
									"magazine-blocks"
								)}
								description={__(
									"Default section block content width",
									"magazine-blocks"
								)}
							>
								<InputGroup>
									<NumberInput
										defaultValue={value}
										flex={1}
										step={1}
										min={0}
										onChange={onChange}
									>
										<NumberInputField
											color="gray.500 !important"
											borderTopRightRadius="0 !important"
											borderBottomRightRadius="0 !important"
											borderColor="gray.200 !important"
										/>
									</NumberInput>
									<InputRightAddon
										color="gray.400"
										fontWeight="medium"
										bgColor="white"
									>
										{"px"}
									</InputRightAddon>
								</InputGroup>
							</FormTwoColumn>
						)}
					/>
					{/* <Controller
						name="editor.editor-blocks-spacing"
						control={control}
						defaultValue={props.data?.['editor-blocks-spacing']}
						render={({ field: { onChange, value } }) => (
							<FormTwoColumn
								label={__('Spacing Between Blocks', 'magazine-blocks')}
								description={__(
									'Manage the gap between blocks in the block editor.',
									'magazine-blocks',
								)}
							>
								<InputGroup>
									<NumberInput
										defaultValue={value}
										flex={1}
										step={1}
										min={0}
										onChange={onChange}
									>
										<NumberInputField
											color="gray.500 !important"
											borderTopRightRadius="0 !important"
											borderBottomRightRadius="0 !important"
											borderColor="gray.200 !important"
										/>
									</NumberInput>
									<InputRightAddon
										color="gray.400"
										fontWeight="medium"
										bgColor="white"
									>
										{'px'}
									</InputRightAddon>
								</InputGroup>
							</FormTwoColumn>
						)}
					/> */}
					<FormTwoColumn
						label={__("Responsive Breakpoints", "magazine-blocks")}
						description={__(
							"Manage responsive breakpoints to suit your website's needs.",
							"magazine-blocks"
						)}
					>
						<HStack>
							<Controller
								control={control}
								name="editor.responsive-breakpoints.tablet"
								defaultValue={
									props.data?.["responsive-breakpoints"]
										.tablet
								}
								render={({ field: { onChange, value } }) => (
									<Tooltip
										label={__("Tablet", "magazine-blocks")}
										placement="top"
										hasArrow
									>
										<InputGroup>
											<NumberInput
												defaultValue={value}
												flex={1}
												step={1}
												min={0}
												onChange={onChange}
											>
												<NumberInputField
													color="gray.500 !important"
													borderTopRightRadius="0 !important"
													borderBottomRightRadius="0 !important"
													borderColor="gray.200 !important"
												/>
											</NumberInput>
											<InputRightAddon
												color="gray.400"
												fontWeight="medium"
												bgColor="white"
											>
												{"px"}
											</InputRightAddon>
										</InputGroup>
									</Tooltip>
								)}
							/>
							<Controller
								control={control}
								name="editor.responsive-breakpoints.mobile"
								defaultValue={
									props.data?.["responsive-breakpoints"]
										.mobile
								}
								render={({ field: { onChange, value } }) => (
									<Tooltip
										label={__("Mobile", "magazine-blocks")}
										placement="top"
										hasArrow
									>
										<InputGroup>
											<NumberInput
												defaultValue={value}
												flex={1}
												step={1}
												min={0}
												onChange={onChange}
											>
												<NumberInputField
													color="gray.500 !important"
													borderTopRightRadius="0 !important"
													borderBottomRightRadius="0 !important"
													borderColor="gray.200 !important"
												/>
											</NumberInput>
											<InputRightAddon
												color="gray.400"
												fontWeight="medium"
												bgColor="white"
											>
												{"px"}
											</InputRightAddon>
										</InputGroup>
									</Tooltip>
								)}
							/>
						</HStack>
					</FormTwoColumn>
					{/* <FormTwoColumn
						label={__('Auto Collapse Panels', 'magazine-blocks')}
						description={__(
							'Ensures one inspector panel is open at a time, automatically close others when you open a new one.',
							'magazine-blocks',
						)}
					>
						<Switch
							{...register('editor.auto-collapse-panels', {
								value: props.data?.['auto-collapse-panels'],
							})}
							colorScheme="primary"
						/>
					</FormTwoColumn> */}
					<FormTwoColumn
						label={__("Copy Paste Styles", "magazine-blocks")}
						description={__(
							"Enable copy-paste style option in block controls to copy and apply Magazine Blocks block styles.",
							"magazine-blocks"
						)}
					>
						<Switch
							{...register("editor.copy-paste-styles", {
								value: props.data?.["copy-paste-styles"],
							})}
							colorScheme="primary"
						/>
					</FormTwoColumn>
				</Stack>
			</Stack>
		</Box>
	);
};

export default EditorOptions;
