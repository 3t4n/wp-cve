import { extendTheme } from "@chakra-ui/react";

const theme = extendTheme({
	fontSizes: {
		"3xs": "7.2px",
		"2xs": "10px",
		xs: "12px",
		sm: "14px",
		md: "16px",
		lg: "18px",
		xl: "20px",
		"2xl": "24px",
		"3xl": "30px",
		"4xl": "36px",
		"5xl": "48px",
		"6xl": "60px",
		"7xl": "72px",
		"8xl": "96px",
		"9xl": "128px",
	},
	sizes: {
		"1": "4px",
		"2": "8px",
		"3": "12px",
		"4": "16px",
		"5": "20px",
		"6": "24px",
		"7": "28px",
		"8": "32px",
		"9": "36px",
		"10": "40px",
		"12": "48px",
		"14": "56px",
		"16": "64px",
		"20": "80px",
		"24": "96px",
		"28": "112px",
		"32": "128px",
		"36": "144px",
		"40": "160px",
		"44": "176px",
		"48": "192px",
		"52": "208px",
		"56": "224px",
		"60": "240px",
		"64": "256px",
		"72": "288px",
		"80": "320px",
		"96": "384px",
		px: "16px",
		"0-5": "2px",
		"1-5": "6px",
		"2-5": "10px",
		"3-5": "14px",
		max: "max-content",
		min: "min-content",
		full: "100%",
		"3xs": "224px",
		"2xs": "256px",
		xs: "320px",
		sm: "384px",
		md: "448px",
		lg: "512px",
		xl: "576px",
		"2xl": "672px",
		"3xl": "768px",
		"4xl": "896px",
		"5xl": "1024px",
		"6xl": "1152px",
		"7xl": "1280px",
		"8xl": "1440px",
	},
	space: {
		"1": "4px",
		"2": "8px",
		"3": "12px",
		"4": "16px",
		"5": "20px",
		"6": "24px",
		"7": "28px",
		"8": "32px",
		"9": "36px",
		"10": "40px",
		"12": "48px",
		"14": "56px",
		"16": "64px",
		"20": "80px",
		"24": "96px",
		"28": "112px",
		"32": "128px",
		"36": "144px",
		"40": "160px",
		"44": "176px",
		"48": "192px",
		"52": "208px",
		"56": "224px",
		"60": "240px",
		"64": "256px",
		"72": "288px",
		"80": "320px",
		"96": "384px",
		px: "1px",
		"0-5": "0.125rem",
		"1-5": "0.375rem",
		"2-5": "0.625rem",
		"3-5": "0.875rem",
	},
	radii: {
		none: 0,
		sm: "2px",
		base: "4px",
		md: "6px",
		lg: "8px",
		xl: "12px",
		"2xl": "16px",
		"3xl": "24px",
	},
	colors: {
		primary: {
			50: "#fafafc",
			100: "#e8eefd",
			200: "#b9cdf9",
			300: "#8aabf4",
			400: "#5c8af0",
			500: "#7E36F4",
			600: "#134fd2",
			700: "#0f3ea3",
			800: "#0b2c75",
			900: "#061a46",
		},
	},
	styles: {
		global: {
			".magazine-blocks-library-sidebar,.magazine-blocks-inserters-sidebar":
				{
					width: "full",
					maxWidth: "300px",
					borderRight: "1px solid",
					borderColor: "gray.200",
					h: "full",
					".magazine-blocks": {
						h: "full",
					},
				},
			".magazine-blocks-shortcut-sidebar .magazine-blocks": {
				h: "full",
			},
			".magazine-blocks-popover": {
				".components-popover__content": {
					maxW: "300px",
					padding: "4",
				},
			},
			".chakra-select": {
				background: "none !important",
				borderRadius: "2px !important",
				borderColor: "var(--chakra-colors-gray-400) !important",
				color: "var(--chakra-colors-gray-800) !important",
				boxShadow: "none !important",
				fontSize: "13px",
			},
			'[data-ba-tab]:not([data-ba-tab="advanced"])': {
				".block-editor-block-inspector__advanced": {
					display: "none",
				},
			},

			".chakra-button__group button svg": {
				width: "20px",
				height: "20px",
			},
			".magazine-blocks-popover-drawer": {
				bg: "white",
				borderRadius: "sm",
				border: "1px",
				borderColor: "gray.200",
			},
			".modal-open": {
				".magazine-blocks-popover-drawer": {
					visibility: "hidden",
					opacity: 0,
					pointerEvents: "none",
				},
			},
			".block-editor-block-inspector__advanced": {
				label: {
					fontSize: "xs",
					fontWeight: "normal",
					textTransform: "capitalize",
				},

				".components-base-control": {
					marginBottom: "16px",
				},

				".components-text-control__input": {
					height: "40px",
					borderColor: "#949494 !important",
				},
			},

			".block-editor-block-inspector__advanced p": {
				fontSize: "xs",
				fontStyle: "italic",
				fontWeight: "normal",
				color: "#909090",
				textTransform: "capitalize",

				".chakra-form-control": {
					mt: "6px !important",
					mb: "12px !important",
				},
			},

			":where(img, svg, video, canvas, audio, iframe, embed, object)": {
				display: "unset",
			},
			"label.chakra-switch": {
				"--switch-track-width": "22px",
				"--switch-track-height": "13px",
			},
			".chakra-switch__track": {
				"--switch-bg": "#CBD5E0 !important",
			},
			".chakra-switch__track[data-checked]": {
				"--switch-bg": "#7E36F4 !important",
			},
			".chakra-tabs__tab-panel": {
				px: "0px !important",
			},
			".magazine-blocks-panel-body": {
				background: "#ffffff !important",
			},
			".magazine-blocks-panel-body.is-opened button": {
				boxShadow: "none !important",
			},
			".magazine-blocks-panel:last-child .magazine-blocks-panel-body.is-opened":
				{
					border: "none !important",
				},
			".magazine-blocks-box-shadow-popover": {
				display: "inline-flex",
			},
			".block-editor-url-input input[type=text]": {
				width: "100% !important",
				border: "1px solid #949494 !important",
				borderRadius: "2px !important",
			},
			".magazine-blocks-popover-drawer .components-popover__content": {
				p: "0 16px 16px 16px",
				"> .chakra-tabs > .chakra-tabs__tablist": {
					mx: "-16px",
				},
			},
			".mzb-style-guide--open .edit-post-visual-editor": {
				display: "none !important",
			},
		},
	},
	components: {
		Button: {
			defaultProps: {
				colorScheme: "primary",
			},
			variants: {
				icon: {
					minW: "6",
					color: "gray.800",
					colorScheme: "gray",
					w: "6",
					h: "6",
					p: "0 !important",
					borderRadius: "sm",
					svg: {
						width: "4",
						height: "4",
						fill: "currentColor",
					},
				},
			},
			baseStyle: {
				fontWeight: "normal",
				fontSize: "md",
				borderRadius: "sm",
			},
		},
		Switch: {
			defaultProps: {
				size: "sm",
				colorScheme: "primary",
			},
		},
		FormLabel: {
			baseStyle: {
				fontWeight: "normal",
				fontSize: "sm",
				mb: "0",
				color: "#222222",
			},
		},
		Tooltip: {
			baseStyle: {
				fontSize: "xs",
				fontWeight: "normal",
				borderRadius: "base",
			},
		},
		Tabs: {
			defaultProps: {
				colorScheme: "primary",
			},
		},
	},
});

export default theme;
