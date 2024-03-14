import {
	Box,
	Button,
	FormControl,
	FormLabel,
	HStack,
	Input,
	InputGroup,
	InputRightElement,
	Modal,
	ModalBody,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
	useDisclosure,
} from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import React from "react";
import VirtualList from "react-tiny-virtual-list";
import { Search } from "../../../base/components/Icons";
import { useDebounce } from "../../hooks";
import IconGrid from "./IconGrid";
import { IconPreview } from "./IconPreview";
import {
	ALL_ICONS,
	FONT_AWESOME_BRANDS,
	FONT_AWESOME_REGULAR,
	FONT_AWESOME_SOLID,
	MAGAZINE_BLOCKS_ICONS,
} from "./icons";
import IconVariants from "./IconVariants";
import { IconType } from "./types";

type Props = {
	value?: string;
	onChange: (value: string | undefined) => void;
};

const IconModal = (props: Props) => {
	const { isOpen, onOpen, onClose } = useDisclosure();
	const [variant, setVariant] = React.useState<
		"any" | "magazine-blocks" | "solid" | "regular" | "brands"
	>("any");
	const [searchTerm, setSearchTerm] = React.useState<string>("");
	const debouncedSearchTerm = useDebounce(searchTerm);
	const [selectedIcon, setSelectedIcon] = React.useState<string | undefined>(
		props.value
	);
	const virtualListRef = React.useRef<any>();

	const { icons, initialIndex } = React.useMemo<{
		icons: Array<IconType[]>;
		initialIndex: number;
	}>(() => {
		let temp =
			"magazine-blocks" === variant
				? MAGAZINE_BLOCKS_ICONS
				: "solid" === variant
				? FONT_AWESOME_SOLID
				: "regular" === variant
				? FONT_AWESOME_REGULAR
				: "brands" === variant
				? FONT_AWESOME_BRANDS
				: ALL_ICONS;

		temp = temp.filter(({ label }) =>
			debouncedSearchTerm
				? label
						.toLowerCase()
						.includes(debouncedSearchTerm.toLowerCase())
				: true
		);
		if (!temp.length) {
			temp.push({
				label: __("No icons found", "magazine-blocks"),
				svg: "",
				id: "",
			});
		}
		const icons = Array.from(
			{ length: Math.ceil(temp.length / 6) },
			(v, i) => temp.slice(i * 6, i * 6 + 6)
		);
		return {
			icons,
			initialIndex: icons.findIndex((ii) =>
				ii.find((i) => i.id === props.value)
			),
		};
	}, [variant, debouncedSearchTerm, props.value]);

	return (
		<FormControl>
			<FormLabel mb="4">{__("Icon", "magazine-blocks")}</FormLabel>
			<IconPreview
				openModal={onOpen}
				onRemoveIcon={() => {
					props?.onChange(undefined);
					setSelectedIcon(undefined);
				}}
				previewIcon={props.value}
			/>
			<Modal
				isOpen={isOpen}
				onClose={() => {
					onClose();
					setVariant("any");
					setSearchTerm("");
					setSelectedIcon(props.value);
				}}
				isCentered
			>
				<ModalOverlay />
				<ModalContent
					maxW={["container.lg", "container.xl"]}
					h="calc(100vh - 100px)"
				>
					<ModalHeader
						borderBottom="1px solid"
						borderColor="gray.200"
					>
						{__("Icon Library", "magazine-blocks")}
					</ModalHeader>
					<ModalCloseButton />
					<ModalBody h="calc(100% - 134px)" p="0">
						<HStack align="start" h="full" gap="0">
							<IconVariants
								variant={variant}
								onVariantSelect={(v) => {
									setVariant(v);
									virtualListRef.current?.scrollTo(0);
								}}
							/>
							<Box
								h="full"
								borderLeft="1px"
								borderColor="gray.200"
								px="8"
								pt="8"
								w="full"
							>
								<Box h="full">
									<InputGroup>
										<Input
											borderRadius="base"
											fontSize="sm"
											fontWeight="normal"
											onChange={(e) =>
												setSearchTerm(e.target.value)
											}
											placeholder={__(
												"Filter by name..."
											)}
										/>
										<InputRightElement pointerEvents="none">
											<Search
												w="4"
												h="4"
												fill="gray.400"
											/>
										</InputRightElement>
									</InputGroup>
									<Box mt="6" h="calc(100% - 64px)">
										<VirtualList
											ref={virtualListRef}
											height="100%"
											itemCount={icons.length}
											itemSize={100}
											style={{
												paddingRight: "1rem",
											}}
											scrollOffset={
												-1 !== initialIndex
													? initialIndex * 100
													: undefined
											}
											renderItem={({
												index,
												style,
											}: {
												index: number;
												style: any;
											}) => {
												return (
													<IconGrid
														key={index}
														icons={icons[index]}
														style={style}
														onSelectIcon={
															setSelectedIcon
														}
														selectedIcon={
															selectedIcon
														}
													/>
												);
											}}
										/>
									</Box>
								</Box>
							</Box>
						</HStack>
					</ModalBody>
					<ModalFooter borderTop="1px solid" borderColor="gray.200">
						<Button
							isDisabled={!selectedIcon}
							colorScheme="primary"
							borderRadius="base"
							size="sm"
							fontWeight="normal"
							onClick={() => {
								onClose();
								props.onChange?.(selectedIcon);
								setVariant("any");
								setSearchTerm("");
							}}
						>
							{__("Insert", "magazine-blocks")}
						</Button>
					</ModalFooter>
				</ModalContent>
			</Modal>
		</FormControl>
	);
};

export default IconModal;
