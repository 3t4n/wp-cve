import { Alert, AlertDescription, Grid } from "@chakra-ui/react";
import React from "react";
import Icon from "./Icon";
import { IconType } from "./types";

type Props = {
	icons: IconType[];
	style?: any;
	onSelectIcon?: React.Dispatch<React.SetStateAction<string | undefined>>;
	selectedIcon: string | undefined;
};

const IconGrid = ({ icons, style, onSelectIcon, selectedIcon }: Props) => {
	return (
		<Grid
			style={style}
			gridTemplateColumns="repeat(6, 1fr)"
			gridGap="4"
			pb={4}
		>
			{icons.length === 1 && icons[0].id === "" ? (
				<Alert status="info" gridColumn="1/-1">
					<AlertDescription>{icons[0].label}</AlertDescription>
				</Alert>
			) : (
				icons.map((icon, i) => (
					<Icon
						key={`${icon.id}${i}`}
						icon={icon}
						onSelectIcon={onSelectIcon}
						isSelected={icon.id === selectedIcon}
					/>
				))
			)}
		</Grid>
	);
};

export default IconGrid;
