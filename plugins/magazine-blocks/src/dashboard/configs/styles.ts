export const reactSelectStyles = {
	indicatorSeparator(base: any) {
		return {
			...base,
			display: "none",
		};
	},
};

export const reactSelectTheme = (theme: any) => ({
	...theme,
	borderRadius: 4,
	colors: {
		...theme.colors,
		neutral20: "#E2E8F0",
		primary: "#690aa0",
	},
});
