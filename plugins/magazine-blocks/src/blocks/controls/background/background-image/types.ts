type MaybeResponsiveValue =
	| {
			desktop: string | number;
			tablet: string | number;
			mobile: string | number;
	  }
	| undefined;

export interface BackgroundImageProps {
	value?: {
		image?: Record<string, any>;
		position?: MaybeResponsiveValue;
		size?: MaybeResponsiveValue;
		repeat?: MaybeResponsiveValue;
		attachment: string;
	};
	onChange: (value: BackgroundImageProps["value"]) => void;
}
