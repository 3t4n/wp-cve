export type BoxShadowProps = {
	value?: {
		enable?: boolean;
		color?: string;
		horizontalX?: number;
		verticalY?: number;
		blur?: number;
		spread?: number;
		position?: string;
	};
	onChange: (val: BoxShadowProps["value"]) => void;
	resetAttributeKey?: string;
};
