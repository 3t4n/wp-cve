import { Box } from "@chakra-ui/react";
import { MediaUpload } from "@wordpress/block-editor";
import React, { useEffect } from "react";
import { useDebounceCallback } from "../../../hooks";
import * as Preview from "./Preview";
import { BackgroundImageProps } from "./types";

export const BackgroundImage = ({ value, ...props }: BackgroundImageProps) => {
	const [localValue, setLocalValue] = React.useState(value);
	const debouncedChange = useDebounceCallback(props.onChange, 400);

	useEffect(() => {
		setLocalValue(value);
	}, [value]);

	const onChange = (v: Record<string, any>) => {
		setLocalValue((prev) => {
			const nextValue = {
				...prev,
				...v,
			} as BackgroundImageProps["value"];
			debouncedChange(nextValue);
			return nextValue;
		});
	};

	return (
		<Box>
			<MediaUpload
				onSelect={(media) => {
					if (!media?.url) {
						return;
					}
					const { url, id, height, width } = media;
					onChange({ image: { url, id, height, width } });
				}}
				allowedTypes={["image"]}
				render={({ open }) => {
					return (
						<Box w="254px">
							{localValue?.image?.url ? (
								<Preview.Image
									value={localValue}
									openMediaFrame={open}
									onChange={onChange}
								/>
							) : (
								<Preview.Placeholder openMediaFrame={open} />
							)}
						</Box>
					);
				}}
			/>
		</Box>
	);
};

export default BackgroundImage;
