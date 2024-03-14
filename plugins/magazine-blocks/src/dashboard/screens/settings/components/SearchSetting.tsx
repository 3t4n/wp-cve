import { Box } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import { useNavigate } from "react-router-dom";
import Select, { components, DropdownIndicatorProps } from "react-select";
import * as Icon from "../../../components/Icon";
import { reactSelectStyles, reactSelectTheme } from "../../../configs/styles";

const OPTIONS = [
	{
		label: __("File Generation"),
		value: "/settings?tab=asset-generation",
	},
];

const SearchSetting: React.FC = () => {
	const navigate = useNavigate();
	return (
		<Box
			p="22px 12px 24px 12px"
			sx={{
				"#react-select-2-input": {
					boxShadow: "none !important",
					gridArea: "1/-1",
				},
			}}
		>
			<Select
				options={OPTIONS}
				theme={reactSelectTheme}
				styles={{
					...reactSelectStyles,
					input(provided) {
						return {
							...provided,
							display: "flex",
						};
					},
				}}
				value={null}
				placeholder={__("Search setting...", "magazine-blocks")}
				noOptionsMessage={() =>
					__("No search results found", "magazine-blocks")
				}
				components={{
					DropdownIndicator: (props: DropdownIndicatorProps) => (
						<components.DropdownIndicator {...props}>
							<Icon.Search
								w="20px"
								h="20px"
								fill="currentColor"
							/>
						</components.DropdownIndicator>
					),
				}}
				onChange={(value: any) => {
					if (value?.value) {
						navigate(value.value);
					}
				}}
			/>
		</Box>
	);
};

export default SearchSetting;
