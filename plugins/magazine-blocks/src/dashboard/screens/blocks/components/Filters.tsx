import {
	Box,
	Button,
	ButtonGroup,
	HStack,
	Input,
	InputGroup,
	InputLeftElement,
	Select,
	Stack,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import * as Icon from "../../../components/Icon";

type Props = {
	sort?: string;
	search?: string;
	onActivateAll?: () => void;
	onDeactivateAll?: () => void;
	isAllActive?: boolean;
	isAllInactive?: boolean;
	onSort?: React.Dispatch<React.SetStateAction<"newest" | "alphabetical">>;
	onSearch?: React.Dispatch<React.SetStateAction<string>>;
	loading?: boolean;
};

const Filters: React.FC<Props> = (props) => {
	const {
		sort,
		search,
		onActivateAll,
		onDeactivateAll,
		isAllActive,
		isAllInactive,
		onSort,
		onSearch,
		loading,
	} = props;
	return (
		<Stack
			align={{
				base: "center",
				xs: "flex-start",
			}}
			justify="space-between"
			direction={{
				base: "column",
				md: "row",
			}}
		>
			<HStack justify={["space-between", "space-between", "normal"]}>
				<Select
					placeholder="Select option"
					w="120px"
					bg="unset !important"
					borderColor="gray.200 !important"
					onChange={(e) => {
						if (e.target.value) {
							onSort?.(
								e.target.value as "newest" | "alphabetical"
							);
						}
					}}
					defaultValue={sort}
				>
					<option value="newest">
						{__("Newest", "magazine-blocks")}
					</option>
					<option value="alphabetical">
						{__("Alphabetical", "magazine-blocks")}
					</option>
				</Select>
				<ButtonGroup borderRadius="base" isDisabled={loading}>
					<Button
						variant="outline"
						borderTopRightRadius="0"
						borderBottomRightRadius="0"
						fontSize="sm"
						fontWeight="normal"
						py="6px"
						px="4"
						onClick={onActivateAll}
						isDisabled={isAllActive}
						_hover={{
							bgColor: "transparent",
							color: "primary.500",
						}}
						_active={{
							bgColor: "transparent",
							color: "primary.500",
						}}
					>
						{__("Activate all", "magazine-blocks")}
					</Button>
					<Button
						ml="0 !important"
						variant="outline"
						borderTopLeftRadius="0"
						borderBottomLeftRadius="0"
						fontSize="sm"
						fontWeight="normal"
						py="6px"
						px="4"
						onClick={onDeactivateAll}
						isDisabled={isAllInactive}
						_hover={{
							bgColor: "transparent",
							color: "primary.500",
						}}
						_active={{
							bgColor: "transparent",
							color: "primary.500",
						}}
					>
						{__("Deactivate all", "magazine-blocks")}
					</Button>
				</ButtonGroup>
			</HStack>
			<Box>
				<InputGroup>
					<InputLeftElement>
						<Icon.Search h="5" w="5" fill="gray.400" />
					</InputLeftElement>
					<Input
						border="1px"
						borderColor="gray.200 !important"
						pl="9 !important"
						type="text"
						placeholder="Search..."
						defaultValue={search}
						fontSize="xs"
						onChange={(e) => onSearch?.(e.target.value)}
					/>
				</InputGroup>
			</Box>
		</Stack>
	);
};

export default Filters;
