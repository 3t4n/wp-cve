import { Box, Heading, SimpleGrid, Stack } from "@chakra-ui/react";
import { useSelect } from "@wordpress/data";
import { __ } from "@wordpress/i18n";
import React from "react";
import { PLUGINS, THEMES } from "../../constants/products";
import { MAGAZINE_BLOCKS_DASHBOARD_STORE } from "../../store";
import { MagazineBlocksLocalized } from "../../types";
import ProductCard from "./components/ProductCard";

const Products: React.FC = () => {
	const { pluginsStatus, themesStatus } = useSelect((select) => {
		const { getPluginsStatus, getThemesStatus } = select(
			MAGAZINE_BLOCKS_DASHBOARD_STORE
		) as any;
		return {
			pluginsStatus:
				getPluginsStatus() as MagazineBlocksLocalized["plugins"],
			themesStatus:
				getThemesStatus() as MagazineBlocksLocalized["themes"],
		};
	}, []);
	return (
		<Stack my="8" mx="6">
			<Box>
				<Heading size="md" fontSize="xl" fontWeight="semibold" mb="8">
					{__("Plugins", "magazine-blocks")}
				</Heading>
				<SimpleGrid
					columns={{ base: 1, md: 2, lg: 3, xl: 4 }}
					spacing="5"
				>
					{PLUGINS.map((plugin) => (
						<ProductCard
							key={plugin.slug}
							{...plugin}
							pluginsStatus={pluginsStatus}
							themesStatus={themesStatus}
						/>
					))}
				</SimpleGrid>
			</Box>
			<Box>
				<Heading size="md" fontSize="xl" fontWeight="semibold" my="8">
					{__("Themes", "magazine-blocks")}
				</Heading>
				<SimpleGrid
					columns={{ base: 1, md: 2, lg: 3, xl: 4 }}
					spacing="5"
				>
					{THEMES.map((theme) => (
						<ProductCard
							key={theme.slug}
							{...theme}
							pluginsStatus={pluginsStatus}
							themesStatus={themesStatus}
						/>
					))}
				</SimpleGrid>
			</Box>
		</Stack>
	);
};

export default Products;
