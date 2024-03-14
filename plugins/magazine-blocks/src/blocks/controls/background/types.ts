import { BackgroundImageProps } from "./background-image/types";

export interface BackgroundProps {
	value?: {
		color?: string;
		type?: "color" | "image" | "gradient";
		image?: BackgroundImageProps["value"];
		gradient?: string;
	};
	onChange: (value: BackgroundProps["value"]) => void;
}
