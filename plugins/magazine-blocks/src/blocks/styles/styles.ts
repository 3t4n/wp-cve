export const toggleButtonPresetStyles = {
	display: "grid",
	gridTemplateColumns: "1fr",
	gridTemplateRows: "max-content",
	gridGap: "8px",
	borderWidth: "0",
	height: "100vh",
	sx: {
		".chakra-button": {
			flexDirection: "column",
			p: "6px !important",
			border: "1px",
			borderColor: "gray.200",
			borderRadius: "4px",
			gap: "4px",
			color: "#222222",
			fontSize: "11px",
			fontWeight: "600",
			lineHeight: "16px",
			height: "130px",

			"&:focus": {
				bgColor: "white",
				color: "#222222",
			},

			"&[data-active]": {
				bgColor: "transparent",
				borderColor: "primary.500",
				color: "#222222",
			},

			svg: {
				width: "100%",
				height: "auto",
				fill: "unset",
				border: "1px",
				borderColor: "gray.200",
				borderRadius: "4px",
			},
		},
	},
};

export const transparentBg = {
	bg: `white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' fill-opacity='.1'%3E%3Cpath d='M200 0h200v200H200zM0 200h200v200H0z'/%3E%3C/svg%3E")`,
	bgSize: "15%",
};
