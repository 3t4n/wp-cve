import { defineStyleConfig, extendTheme } from "@chakra-ui/react";

const theme = extendTheme({
	colors: {
		primary: {
			50: "#fafafc",
			100: "#e8eefd",
			200: "#b9cdf9",
			300: "#8aabf4",
			400: "#5c8af0",
			500: "#690aa0",
			600: "#134fd2",
			700: "#0f3ea3",
			800: "#0b2c75",
			900: "#061a46",
		},
	},
	styles: {
		global: {
			".wp-admin #mzb": {
				ms: "-20px",
			},
			".toplevel_page_magazine_blocks #wpwrap": {
				bgColor: "primary.50",
			},
		},
	},
	components: {
		Button: defineStyleConfig({
			baseStyle: {
				borderRadius: "base",
			},
		}),
		Heading: defineStyleConfig({
			baseStyle: {
				margin: 0,
			},
		}),
		Text: defineStyleConfig({
			baseStyle: {
				margin: 0,
			},
		}),
	},
});

export default theme;
